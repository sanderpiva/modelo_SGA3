
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciamento_academico_completo";
$pdo = null;
$conteudo = null;
$erro = null;

if (isset($_GET['titulo'])) {
    $titulo_param = $_GET['titulo'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * 
                FROM conteudo 
                WHERE titulo = :titulo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo_param, PDO::PARAM_STR);
        $stmt->execute();
        $conteudo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$conteudo) {
            $erro = "Conteúdo não encontrado.";
        }
    } catch (PDOException $e) {
        $erro = "Erro na conexão com o banco de dados: " . $e->getMessage();
    } finally {
        $pdo = null;
    }
} else {
    $erro = "Título não especificado.";
}

// Lógica para escolher imagem com base no título
function obterImagemAssociada($titulo) {
    $titulo_lower = strtolower($titulo);

    if (strpos($titulo_lower, 'pa') !== false) {
        return 'img/i_pa.png';
    } elseif (strpos($titulo_lower, 'pg') !== false) {
        return 'img/i_pg.png';
    } elseif (strpos($titulo_lower, 'matriz') !== false) {
        return 'img/matriz.png';
    } elseif (strpos($titulo_lower, 'função') !== false || strpos($titulo_lower, 'funcao') !== false) {
        return 'img/funcao.png';
    }

    return 'img/default.png'; // imagem padrão
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Conteúdo Dinâmico</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .conteudo-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fdfdfd;
            text-align: center;
        }
        .conteudo-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .botao-voltar {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .botao-voltar:hover {
            background-color: #005fa3;
        }

        p{
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="conteudo-container">
        <?php if ($erro): ?>
            <h2 style="color: red;"><?= htmlspecialchars($erro) ?></h2>
        <?php elseif ($conteudo['disciplina']=='Matematica'): ?>
            <h1><?= htmlspecialchars($conteudo['titulo']) ?></h1>
            
            <img src="<?= obterImagemAssociada($conteudo['titulo']) ?>" alt="Imagem relacionada ao conteúdo">
            <p><?= nl2br(htmlspecialchars($conteudo['descricao'])) ?></p>
        
            <?php if($conteudo['titulo'] == 'A progressao geometrica'): ?>
                <a href="exercicio-pg.php">Exercicio demonstrativo</a> 
            <?php elseif($conteudo['titulo'] == 'A progressao aritmetica'): ?>
                <a href="exercicio-pa.php">Exercicio demonstrativo</a>               
            <?php else: ?>
                <p>IMPORTANTE! Não há exercicio demonstrativo disponivel</p>
            <?php endif; ?>
        <?php elseif ($conteudo['disciplina']!='Matematica'): ?>
            <h1><?= htmlspecialchars($conteudo['titulo']) ?></h1>
            
            <img src="<?= obterImagemAssociada($conteudo['titulo']) ?>" alt="Imagem relacionada ao conteúdo">
            <p><?= nl2br(htmlspecialchars($conteudo['descricao'])) ?></p>

        <?php endif; ?><br>

        <a class="botao-voltar" href="dashboard-alunos-dinamico.php">← Finalizar</a> 
    </div>
</body><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer>
        <p style="text-align: center;">Desenvolvido por Juliana e Sander</p>
</footer>
</html>
