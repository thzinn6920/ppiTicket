<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu Inicial</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="menu.css">
</head>
<body>
  <main class="container">
    <h1>Bem-vindo!</h1>
    <h2>O que deseja fazer?</h2>

    <form class="form" onsubmit="return false;">
      <a href="auth.php" class="btn"><i class="fas fa-sign-in-alt"></i> Login</a>
      <a href="telaUsuario.html" class="btn"><i class="fas fa-ticket-alt"></i> Retirar senhas</a>
      <a href="ver.php" class="btn"><i class="fas fa-list-ul"></i> Ver senhas retiradas</a>
    </form>
  </main>
</body>
</html>
