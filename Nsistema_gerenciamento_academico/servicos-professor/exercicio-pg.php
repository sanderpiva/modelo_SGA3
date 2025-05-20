<?php
session_start(); // Inicia a sessão (boa prática, mesmo que não usada diretamente para PG aqui)

$resultado_pg = "";
$passos_pg = "";

// Processar cálculo da PG (se o formulário for enviado)
if (isset($_POST['calcular_pg'])) {
    $a1 = isset($_POST['a1_pg']) ? floatval($_POST['a1_pg']) : null;
    $q = isset($_POST['q_pg']) ? floatval($_POST['q_pg']) : null;
    $n = isset($_POST['n_pg']) ? intval($_POST['n_pg']) : null;

    if (!is_null($a1) && !is_null($q) && !is_null($n)) {
        $sequencia = [];
        $termo_atual = $a1;
        $passos = "Cálculo do Termo Geral da PG:\n";

        for ($i = 1; $i <= $n; $i++) {
            $sequencia[] = $termo_atual;
            // Exibindo a fórmula com os valores para o passo a passo
            $passos .= "Termo " . $i . ": a" . $i . " = " . number_format($a1, 2, ',', '.') . " * " . number_format($q, 2, ',', '.') . "^(" . ($i - 1) . ") = " . number_format($termo_atual, 2, ',', '.') . "\n";
            $termo_atual *= $q;
        }
        $resultado_pg = "Sequência da PG: " . implode(', ', array_map(function($v){ return number_format($v, 2, ',', '.'); }, $sequencia));
        $passos_pg = $passos;

        // Opcional: Definir status na sessão ao calcular (se necessário para outras páginas)
        // Cuidado pois o comando a seguir afeta o Algebrando: Estatico
        //$_SESSION['pg_status'] = 1;
    } else {
        // Mensagem de erro para o usuário
        $resultado_pg = "<p style='color: red;'>Por favor, preencha todos os campos para calcular a PG.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Progressão Geométrica (PG)</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body class="home">
    <div id="pg_form"><br><br>
        <h1 class="titulo_pg">Calcule o Termo Geral da Progressão Geométrica (PG)</h1>
        
        <form method="post" action="">
            <label for="a1_pg">Primeiro Termo (a1):</label><br>
            <input type="number" id="a1_pg" name="a1_pg" placeholder="a1" min="1" step="any" value="<?php echo isset($_POST['a1_pg']) ? htmlspecialchars($_POST['a1_pg']) : ''; ?>" required><br><br>

            <label for="q_pg">Razão (q)...................:</label><br>
            <input type="number" id="q_pg" name="q_pg" placeholder="q" min="1" step="any" value="<?php echo isset($_POST['q_pg']) ? htmlspecialchars($_POST['q_pg']) : ''; ?>" required><br><br>

            <label for="n_pg">Número de Termos (n):</label><br>
            <input type="number" id="n_pg" name="n_pg" placeholder="n" min="1" value="<?php echo isset($_POST['n_pg']) ? htmlspecialchars($_POST['n_pg']) : ''; ?>" required><br><br>

            <button class="btn_calcular" type="submit" name="calcular_pg">Calcular</button><br><br>
        </form>
    </div>

    <div id="pg_result">
        <?php
        if (!empty($passos_pg)) {
            echo "<h3>Passo a Passo:</h3>";
            echo "<pre>" . htmlspecialchars($passos_pg) . "</pre>";
        }
        if (!empty($resultado_pg)) {
            echo "<h3>Resultado:</h3>";
            echo "<p>" . htmlspecialchars($resultado_pg) . "</p>";
        }
        ?>
        <br><br>
        <a class="btn_dashboard" href="dashboard-alunos-dinamico.php">Finalizar</a>
    </div>
    
</body><br><br>
<footer>
    <p>Desenvolvido por Juliana e Sander</p>
</footer>

</html>