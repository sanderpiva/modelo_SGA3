<?php

// Inicia a sessão para armazenar informações do usuário após o login
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once "conexao.php"; // Certifique-se de que este arquivo conecta ao seu banco de dados PDO

// Define a URL da página inicial para o botão/link de retorno
$homePageUrl = "../index.php"; // <--- AJUSTE AQUI se sua página inicial for diferente (ex: "/", "home.php")

/**
 * Função para exibir uma mensagem de erro formatada com um link para a home page.
 * @param string $message A mensagem de erro a ser exibida.
 * @param string $homeUrl A URL da página inicial.
 */
function displayError($message, $homeUrl) {
    // Basicamente, renderizamos um HTML completo para a página de erro.
    // Isso é importante porque, se o script estivesse sendo incluído em outra página,
    // essa saída seria misturada. Ao gerar um HTML completo, garantimos que a página
    // de erro seja auto-suficiente.
    echo "<!DOCTYPE html>
          <html lang='pt-br'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Erro de Login</title>
              <style>
                  body {
                      font-family: sans-serif;
                      display: flex;
                      justify-content: center;
                      align-items: center;
                      min-height: 100vh; /* Garante que o contêiner ocupe a altura total da viewport */
                      margin: 0;
                      background-color: #f4f4f4; /* Fundo suave */
                  }
                  .error-container {
                      text-align: center;
                      background-color: white;
                      padding: 30px;
                      border-radius: 8px;
                      box-shadow: 0 0 15px rgba(0,0,0,0.1); /* Sombra para destacar */
                      max-width: 400px; /* Largura máxima para o contêiner de erro */
                      width: 90%; /* Responsividade */
                  }
                  .error-container p {
                      color: red; /* Cor do texto de erro */
                      font-weight: bold;
                      margin-bottom: 20px;
                      font-size: 1.1em; /* Tamanho da fonte um pouco maior */
                  }
                  .error-container a {
                      display: inline-block; /* Permite definir padding e margin */
                      padding: 12px 25px;
                      background-color: #007bff; /* Cor azul para o botão */
                      color: white;
                      text-decoration: none; /* Remove sublinhado */
                      border-radius: 5px;
                      transition: background-color 0.3s ease; /* Transição suave no hover */
                  }
                  .error-container a:hover {
                      background-color: #0056b3; /* Cor mais escura no hover */
                  }
              </style>
          </head>
          <body>
              <div class='error-container'>
                  <p>" . htmlspecialchars($message) . "</p>
                  <a href='" . htmlspecialchars($homeUrl) . "'>Voltar para a Home</a>
              </div>
          </body>
          </html>";
    exit(); // ESSENCIAL: Interrompe a execução do script PHP após exibir a página de erro
}

// Verifica se a requisição é do tipo POST (ou seja, o formulário foi enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $senhaDigitada = $_POST['senha'] ?? '';

    // Validação básica: verifica se os campos não estão vazios
    if (empty($login) || empty($senhaDigitada)) {
        displayError("Por favor, preencha todos os campos de login e senha.", $homePageUrl);
    }

    try {
        
        $sqlProfessor = "SELECT id_professor, nome, email, senha FROM professor WHERE email = :login";
        $stmtProfessor = $conexao->prepare($sqlProfessor);
        $stmtProfessor->bindParam(':login', $login, PDO::PARAM_STR);
        $stmtProfessor->execute();

        if ($stmtProfessor->rowCount() === 1) { // Encontrou um professor com este email
            $professor = $stmtProfessor->fetch(PDO::FETCH_ASSOC);
            $hashArmazenadoProfessor = $professor['senha'];

            if (password_verify($senhaDigitada, $hashArmazenadoProfessor)) {
        
                $_SESSION['logado'] = true;
                $_SESSION['tipo_usuario'] = 'professor';
                $_SESSION['id_usuario'] = $professor['id_professor'];
                $_SESSION['nome_usuario'] = $professor['nome'];
                $_SESSION['email_usuario'] = $professor['email'];

                header("Location: login-selecao-professor.php?sucesso=1");
                exit(); // Interrompe a execução após o redirecionamento
            }
            
        }

        $sqlAluno = "SELECT a.id_aluno, a.nome, a.email, a.senha, t.nomeTurma
             FROM aluno a
             JOIN turma t ON a.Turma_id_turma = t.id_turma
             WHERE a.email = :login";
        $stmtAluno = $conexao->prepare($sqlAluno);
        $stmtAluno->bindParam(':login', $login, PDO::PARAM_STR);
        $stmtAluno->execute();

        if ($stmtAluno->rowCount() === 1) { // Encontrou um aluno com este email
            $aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);
            $hashArmazenadoAluno = $aluno['senha'];

            // Verifica a senha digitada com o hash armazenado
            if (password_verify($senhaDigitada, $hashArmazenadoAluno)) {
                // Login de aluno bem-sucedido
                $_SESSION['logado'] = true;
                $_SESSION['tipo_usuario'] = 'aluno';
                $_SESSION['id_usuario'] = $aluno['id_aluno'];
                $_SESSION['nome_usuario'] = $aluno['nome'];
                $_SESSION['email_usuario'] = $aluno['email'];
                $_SESSION['nome_turma'] = $aluno['nomeTurma']; // Armazena o nome da turma do aluno
                // Redireciona para a página de seleção do aluno
                header("Location: login-selecao-aluno.php?sucesso=1");
                exit(); // Interrompe a execução após o redirecionamento
            }
        }

        // Se chegou até aqui, significa que o login falhou:
        // - O email não foi encontrado nem como professor, nem como aluno.
        // - O email foi encontrado, mas a senha estava incorreta para o tipo de usuário correspondente.
        displayError("Login ou senha inválidos. Por favor, tente novamente.", $homePageUrl);

    } catch (PDOException $e) {
        // Captura e trata erros de conexão ou consulta ao banco de dados
        // Para segurança, evite exibir detalhes técnicos do erro ($e->getMessage()) ao usuário final.
        displayError("Erro ao acessar o banco de dados. Por favor, tente novamente mais tarde.", $homePageUrl);
        // Opcional: Para depuração, você pode registrar $e->getMessage() em um arquivo de log do servidor.
        // error_log("Erro PDO em verificar-login-professor.php: " . $e->getMessage());
    }
} else {
    // Se a requisição não for do tipo POST (acesso direto à página verificar-login-professor.php sem enviar o formulário),
    // redireciona para a página inicial.
    header("Location: " . $homePageUrl);
    exit();
}

?>