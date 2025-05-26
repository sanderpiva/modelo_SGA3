<!DOCTYPE html>
<html>
<head>
    <title>Login Aluno</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="servicos_forms">
    
<?php

    session_start(); // <--- ESSENCIAL: Inicia a sessão para acessar $_SESSION

    // Verifica se o usuário está logado e se é um professor
    // Esta é a verificação de controle de acesso.
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipo_usuario'] !== 'aluno') {
    // Se não estiver logado, não for um aluno, ou a sessão não estiver correta,
    // redireciona para a página de login ou uma página de erro/acesso negado.
    header("Location: ../index.php"); // Ou para uma página de login específica
    exit();
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (isset($_POST['tipo_atividade'])) {
            $tipo_atividade = $_POST['tipo_atividade'];

            if ($tipo_atividade === 'dinamica') {
                header("Location: ../servicos-professor/selecao-dashboard-dinamico.php");
                exit();
            } elseif ($tipo_atividade === 'estatica') {
        
                header("Location: ../servicos-professor/dashboard-alunos-algebrando-estatico.php");
                exit();
            } else {
                echo "<p style='color:red;'>Selecione uma opção válida.</p>";
            }
        } else {
            echo "<p style='color:red;'>Por favor, selecione uma opção no seletor.</p>";
        }
    }
    ?>
    <div class="form_container">
        <!-- Formulário de Login -->
    <form class="form" method="post" action="">
        
        <h2>Login Aluno</h2>
        
        <select id="tipo_atividade" name="tipo_atividade">
                <option value="">Selecione:</option>
                <option value="dinamica">Atividades dinâmicas</option>
                <option value="estatica">Atividades Algebrando</option>
                
        </select><br><br>

        <button type="submit">Login</button>
    </form>

    </div>
    <a href="../index.php">Home Page</a>
</body>
<footer>
    <p>Desenvolvido por Juliana e Sander</p>
</footer>

</html>
