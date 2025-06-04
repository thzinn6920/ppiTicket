<?php
$senhaAtual = file_exists("senha_atual.txt") ? trim(file_get_contents("senha_atual.txt")) : "00";
$guiche = file_exists("guiche.txt") ? trim(file_get_contents("guiche.txt")) : "--";
$proximaSenha = file_exists("proxima_senha.txt") ? trim(file_get_contents("proxima_senha.txt")) : "00";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Chamado de Senhas</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #e0ecff, #f1f5ff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      text-align: center;
      max-width: 500px;
      width: 90%;
    }

    h1 {
      font-size: 22px;
      color: #1e3a8a;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .linha {
      border-top: 3px solid #1e3a8a;
      margin: 20px 0 30px;
    }

    .proxima, .aguarde {
      font-size: 18px;
      color: #333;
      margin: 20px 0;
    }

    .senha-destaque {
      display: inline-block;
      background: #1e3a8a;
      color: white;
      padding: 12px 24px;
      font-size: 28px;
      font-weight: bold;
      border-radius: 8px;
      margin-top: 10px;
      letter-spacing: 2px;
    }

    .aguarde {
      color: #666;
      font-style: italic;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>
      Senha <strong><?= htmlspecialchars($senhaAtual) ?></strong> sendo atendida no guichê <strong><?= htmlspecialchars($guiche) ?></strong>
    </h1>

    <div class="linha"></div>

    <div class="proxima">
      Próxima senha a ser atendida:
      <br>
      <div class="senha-destaque"><?= htmlspecialchars($proximaSenha) ?></div>
    </div>

    <div class="aguarde">
      Aguarde, você já será atendido.
    </div>
  </div>
</body>
</html>
