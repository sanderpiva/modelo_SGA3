<!DOCTYPE html>
<html>
<head>
<title>Sistema Academico</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>SISTEMA ACADEMICO: IRACEMA RODRIGUES</h1>
<div class="content-columns-wrapper">
<div class="img_home">
<img class="img_dimens" src="img/home2.jpg" alt="Foto Iracema Rodrigues">
</div>
<div class="form_container">
<form class="form" method="post" action="login/verifica-login.php">
<h2>Login</h2>
<label for="login">Login:</label>
<input type="text" name="login" id="login" required>
<label for="senha">Senha:</label>
<input type="password" name="senha" id="senha" required>
<button type="submit">Login</button>
</form>
<div class="cadastro-links" style="margin-top: 20px; text-align: center;">
<p>Não tem cadastro? Crie sua conta:</p>
<a href="login/cadastro-professor.php">Cadastrar como Professor</a> |
<a href="login/cadastro-aluno.php">Cadastrar como Aluno</a>
</div>
</div>
</div>
<footer><hr><hr><hr><hr><hr>
<p>Desenvolvido por Juliana e Sander</p>
</footer>
</html>