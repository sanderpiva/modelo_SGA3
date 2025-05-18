<?php
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
            $passos .= "Termo " . $i . ": a" . $i . " = " . $a1 . " * " . $q . "^(" . ($i - 1) . ") = " . number_format($termo_atual, 2, ',', '.') . "\n";
            $termo_atual *= $q;
        }
        $resultado_pg = "Sequência da PG: " . implode(', ', array_map(function($v){ return number_format($v, 2, ',', '.'); }, $sequencia));
        $passos_pg = $passos;
    } else {
        echo "<p style='color: red;'>Por favor, preencha todos os campos para calcular a PG.</p>";
    }
}
?>

<div class="exercicio-pg">
    <h2>Calcule o Termo Geral da Progressão Geométrica (PG)</h2>
    <form method="post">
        <label for="a1_pg">Primeiro Termo (a1):</label>
        <input type="number" id="a1_pg" name="a1_pg" required><br><br>

        <label for="q_pg">Razão (q)...................:</label>
        <input type="number" id="q_pg" name="q_pg" required><br><br>

        <label for="n_pg">Número de Termos (n):</label>
        <input type="number" id="n_pg" name="n_pg" required><br><br>

        <button type="submit" name="calcular_pg">Calcular PG</button>
    </form>

    <div id="resultado-pg">
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
        <a class="botao-voltar" href="dashboard-alunos-dinamico.php">← Finalizar</a>
    
    </div>
</div>