<!DOCTYPE html>
<html lang="pt" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Mostrar Tabela com Registros de Prova</title>
  </head>
  <body>

    <div class="table_container"> <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Q1</th>
                    <th>Q2</th>
                    <th>Q3</th>
                    <th>Q4</th>
                    <th>Média</th>
                    <th>Turma</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Inclui o arquivo de conexão PDO.
            // Certifique-se de que o caminho está correto para o seu arquivo conexao.php
            // Se este arquivo está em 'matematica-estatica/' e conexao.php em 'seu_projeto_raiz/',
            // o caminho pode ser '../../conexao.php'. Aqui assumo que está na raiz do projeto.
            require_once "../servicos-professor/conexaoDados.php"; // Ajuste este caminho se necessário

            try {
                // Prepara a consulta SQL para selecionar todos os registros da tabeladados
                $sql = "SELECT * FROM tabeladados";
                $stmt = $conexao->prepare($sql);
                $stmt->execute();

                // Recupera todos os resultados
                $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Conta o número de registros
                $num_registros = count($registros);

                echo "<caption>Registros encontrados: " . $num_registros . "</caption>"; // Usando <caption> para título da tabela
                // echo "<br><br>"; // Isso não deve estar aqui, atrapalha a estrutura da tabela

                // Itera sobre os resultados e exibe cada linha na tabela
                if ($num_registros > 0) {
                    foreach ($registros as $reg) {
                        echo "<tr>";
                        // htmlspecialchars para evitar ataques XSS ao exibir dados do DB
                        echo "<td>" . htmlspecialchars($reg['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($reg['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($reg['q1']) . "</td>";
                        echo "<td>" . htmlspecialchars($reg['q2']) . "</td>";
                        echo "<td>" . htmlspecialchars($reg['q3']) . "</td>"; // Verifique se 'q3' está correto. No seu código anterior era $reg[2] para q3 e q1. Ajustei aqui para $reg['q3']
                        echo "<td>" . htmlspecialchars($reg['q4']) . "</td>";
                        echo "<td>" . htmlspecialchars(number_format($reg['nota'], 1)) . "</td>"; // Formata a média para 1 casa decimal
                        echo "<td>" . htmlspecialchars($reg['turma']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Nenhum registro encontrado.</td></tr>";
                }

            } catch (PDOException $e) {
                // Em caso de erro na conexão ou consulta
                echo "<tr><td colspan='8' style='color:red;'>Erro ao carregar dados: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                // Opcional: registrar o erro em um log para depuração
                // error_log("Erro PDO em mostrar-tabela-registros.php: " . $e->getMessage());
            }

            // Com PDO, não há uma função mysqli_close(). A conexão é automaticamente fechada
            // quando o script termina ou quando o objeto PDO não tem mais referências.
            ?>
            </tbody>
        </table>
    </div>

    <br><br><br>
    <a href="../index.php"><em>CLIQUE PARA HomePage</em></a>

  </body>
</html>