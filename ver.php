<?php
$conn = new mysqli('localhost', 'root', '', 'fila');
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

// Pegar senha atual em atendimento e o guichê correspondente
$senhaAtual = "00";
$guiche = "--";
$sqlAtual = "
  SELECT s.nome AS senha_nome, g.nome AS guiche_nome
  FROM senhas s
  JOIN atendimentos a ON a.id_senha = s.id_senha
  JOIN atendentes at ON at.id_atendente = a.id_atendente
  JOIN guiches g ON g.id_guiche = at.id_guiche
  WHERE s.status = 'emAtendimento'
  ORDER BY s.id_senha DESC
  LIMIT 1
";
$resultAtual = $conn->query($sqlAtual);
if ($row = $resultAtual->fetch_assoc()) {
  $senhaAtual = $row['senha_nome'];
  $guiche = $row['guiche_nome'];
}

// Pegar próxima senha aguardando
$proximaSenha = "00";
$sqlProxima = "
  SELECT nome FROM senhas
  WHERE status = 'aguardando'
  ORDER BY id_senha ASC
  LIMIT 1
";
$resultProxima = $conn->query($sqlProxima);
if ($row = $resultProxima->fetch_assoc()) {
  $proximaSenha = $row['nome'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Chamado de Senhas</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
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
