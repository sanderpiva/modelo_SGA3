<?php
session_start(); // Inicia a sessão

$resultado_pa = "";
$passos_pa = "";

// Processar cálculo da PA (se o formulário for enviado)
if (isset($_POST['calcular_pa'])) {
    $a1 = isset($_POST['a1_pa']) ? floatval($_POST['a1_pa']) : null;
    $r = isset($_POST['r_pa']) ? floatval($_POST['r_pa']) : null;
    $n = isset($_POST['n_pa']) ? intval($_POST['n_pa']) : null;

    if (!is_null($a1) && !is_null($r) && !is_null($n)) {
        $sequencia = [];
        $termo_atual = $a1;
        $passos = "Cálculo do Termo Geral da PA:\n";

        for ($i = 1; $i <= $n; $i++) {
            $sequencia[] = $termo_atual;
            // Exibindo a fórmula com os valores para o passo a passo
            $passos .= "Termo " . $i . ": a" . $i . " = " . number_format($a1, 2, ',', '.') . " + (" . ($i - 1) . ") * " . number_format($r, 2, ',', '.') . " = " . number_format($termo_atual, 2, ',', '.') . "\n";
            $termo_atual += $r;
        }
        $resultado_pa = "Sequência da PA: " . implode(', ', array_map(function($v){ return number_format($v, 2, ',', '.'); }, $sequencia));
        $passos_pa = $passos;

        // Opcional: Definir status na sessão ao calcular (se necessário para outras páginas)
        // Cuidado pois o comando a seguir afeta o Algebrando: Estatico
        // $_SESSION['pa_status'] = 1;
    } else {
        // Mensagem de erro para o usuário
        $resultado_pa = "<p style='color: red;'>Por favor, preencha todos os campos para calcular a PA.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Progressão Aritmética (PA)</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body class="home">
    <div id="pa_form"><br><br>
        <h1 class="titulo_pa">Calcule o Termo Geral da Progressão Aritmética (PA)</h1>
        
        <form method="post" action="">
            <label for="a1_pa">Primeiro Termo (a1):</label><br>
            <input type="number" id="a1_pa" name="a1_pa" placeholder="a1" min="1" step="any" value="<?php echo isset($_POST['a1_pa']) ? htmlspecialchars($_POST['a1_pa']) : ''; ?>" required><br><br>

            <label for="r_pa">Razão (r)...................:</label><br>
            <input type="number" id="r_pa" name="r_pa" placeholder="r" step="any" value="<?php echo isset($_POST['r_pa']) ? htmlspecialchars($_POST['r_pa']) : ''; ?>" required><br><br>

            <label for="n_pa">Número de Termos (n):</label><br>
            <input type="number" id="n_pa" name="n_pa" placeholder="n" min="1" value="<?php echo isset($_POST['n_pa']) ? htmlspecialchars($_POST['n_pa']) : ''; ?>" required><br><br>

            <button class="btn_calcular" type="submit" name="calcular_pa">Calcular</button><br><br>
        </form>
    </div>

    <div id="pa_result">
        <?php
        if (!empty($passos_pa)) {
            echo "<h3>Passo a Passo:</h3>";
            echo "<pre>" . htmlspecialchars($passos_pa) . "</pre>";
        }
        if (!empty($resultado_pa)) {
            echo "<h3>Resultado:</h3>";
            echo "<p>" . htmlspecialchars($resultado_pa) . "</p>";
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