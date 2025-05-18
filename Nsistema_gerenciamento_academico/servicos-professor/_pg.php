<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciamento_academico_completo";
$pdo = null;
$erro_conexao = null;
$conteudo_detalhe = null;

if (isset($_GET['assunto'])) {
    $titulo_url = $_GET['assunto'];
    $titulo_buscar = urldecode($titulo_url);

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_conteudo = "SELECT titulo, descricao FROM conteudo WHERE titulo = :titulo";
        $stmt_conteudo = $pdo->prepare($sql_conteudo);
        $stmt_conteudo->bindParam(':titulo', $titulo_buscar, PDO::PARAM_STR);
        $stmt_conteudo->execute();
        $conteudo_detalhe = $stmt_conteudo->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $erro_conexao = "<p style='color:red;'>Erro na conexão com o banco de dados ou na consulta: " . $e->getMessage() . "</p>";
    } finally {
        $pdo = null;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title><?php echo htmlspecialchars($conteudo_detalhe['titulo'] ?? 'Conteúdo'); ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .conteudo-principal {
            padding: 20px;
        }

        .exercicio {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="conteudo-principal">
        <h1><?php echo htmlspecialchars($conteudo_detalhe['titulo'] ?? 'Conteúdo'); ?></h1>

        <?php if ($erro_conexao): ?>
            <?php echo $erro_conexao; ?>
        <?php elseif ($conteudo_detalhe): ?>
            <p><?php echo nl2br(htmlspecialchars($conteudo_detalhe['descricao'])); ?></p>

            <div class="exercicio">
                <h2>Demonstrativo Interativo</h2>
                <p>Aqui você pode adicionar os campos de entrada e a lógica PHP/JavaScript para o exercício demonstrativo, seguindo o modelo da sua imagem.</p>
                <label for="a1">Digite o primeiro termo: a1</label><br>
                <input type="number" id="a1" name="a1"><br><br>

                <label for="r">Digite a razão: r</label><br>
                <input type="number" id="r" name="r"><br><br>

                <label for="n">Digite o número de termos: n</label><br>
                <input type="number" id="n" name="n"><br><br>

                <button onclick="calcularPA()">Calcular</button>
                <div id="resultado"></div>

                <script>
                    function calcularPA() {
                        // Adicione sua lógica de cálculo aqui
                        var a1 = parseInt(document.getElementById('a1').value);
                        var r = parseInt(document.getElementById('r').value);
                        var n = parseInt(document.getElementById('n').value);
                        var resultado = "";
                        if (!isNaN(a1) && !isNaN(r) && !isNaN(n)) {
                            for (var i = 0; i < n; i++) {
                                resultado += (a1 + i * r) + (i < n - 1 ? ", " : "");
                            }
                            document.getElementById('resultado').innerText = "Sequência: " + resultado;
                        } else {
                            document.getElementById('resultado').innerText = "Por favor, preencha todos os campos.";
                        }
                    }
                </script>
            </div>

            <button onclick="window.location.href='atividades.php'">Voltar para Atividades</button>

        <?php else: ?>
            <p>Conteúdo não encontrado.</p>
            <button onclick="window.location.href='atividades.php'">Voltar para Atividades</button>
        <?php endif; ?>
    </div>
</body>
</html>