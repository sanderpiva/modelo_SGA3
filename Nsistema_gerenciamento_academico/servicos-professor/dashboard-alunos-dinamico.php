<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciamento_academico_completo";
$pdo = null;
$erro_conexao = null;
$conteudos = [];

if (isset($_SESSION['turma_selecionada']) && isset($_SESSION['disciplina_selecionada'])) {
    $turma_selecionada = $_SESSION['turma_selecionada'];
    $disciplina_selecionada = $_SESSION['disciplina_selecionada'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_conteudos = "SELECT 
                                c.titulo, 
                                c.descricao 
                          FROM 
                                conteudo c 
                          INNER JOIN 
                                disciplina d ON c.Disciplina_id_disciplina = d.id_disciplina 
                          INNER JOIN 
                                turma t ON d.Turma_id_turma = t.id_turma 
                          WHERE 
                                LOWER(t.nomeTurma) LIKE LOWER(:turma_pattern) 
                                AND LOWER(d.nome) = LOWER(:disciplina)";

        $stmt_conteudos = $pdo->prepare($sql_conteudos);
        $turma_pattern = $turma_selecionada . '%';
        $stmt_conteudos->bindParam(':turma_pattern', $turma_pattern, PDO::PARAM_STR);
        $stmt_conteudos->bindParam(':disciplina', $disciplina_selecionada, PDO::PARAM_STR);
        $stmt_conteudos->execute();
        $conteudos = $stmt_conteudos->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $erro_conexao = "<p style='color:red;'>Erro na conexão com o banco de dados: " . $e->getMessage() . "</p>";
    } finally {
        $pdo = null;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Atividades Dinâmicas</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        #cards-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 20px;
            padding: 20px;
        }
        .card {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
            cursor: pointer;
            min-width: 200px;
            flex-shrink: 0;
            transition: background-color 0.3s;
        }
        .card:hover {
            background-color: #e0e0e0;
        }
        a.card {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body class="servicos_forms">
    <h1>Atividades Dinâmicas</h1>

    <?php
    if ($erro_conexao) {
        echo $erro_conexao;
    } elseif (isset($_SESSION['turma_selecionada']) && isset($_SESSION['disciplina_selecionada'])) {
        echo "<p>Turma selecionada: " . htmlspecialchars($_SESSION['turma_selecionada']) . "</p>";
        echo "<p>Disciplina selecionada: " . htmlspecialchars($_SESSION['disciplina_selecionada']) . "</p>";

        if (!empty($conteudos)) {
            echo "<div id='cards-container'>";
            foreach ($conteudos as $conteudo) {
                $titulo = urlencode($conteudo["titulo"]);
                echo "<a href='pagina-dinamica.php?titulo=$titulo' class='card'>";
                echo "<h2>" . htmlspecialchars($conteudo["titulo"]) . "</h2>";
                echo "<p>Clique para ver mais detalhes</p>";
                echo "</a>";
            }
            echo "</div>";
        } else {
            echo "<p>Nenhum conteúdo encontrado para a disciplina '" . htmlspecialchars($_SESSION['disciplina_selecionada']) . "' em turmas como '" . htmlspecialchars($_SESSION['turma_selecionada']) . "'.</p>";
        }
    } else {
        echo "<p style='color:red;'>Nenhuma turma e disciplina selecionadas.</p>";
    }
    ?>
    <div>
        <a class="botao-voltar" href="prova-dinamica">Prova</a>
    </div><hr><hr>
    <div>
        <a href="selecao-dashboard-dinamico.php">Voltar</a>
    </div>
</body>
<footer>
    <p>Desenvolvido por Juliana e Sander</p>
    </footer>
</html>
