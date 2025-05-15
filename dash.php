<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $novaSenha = $_POST["senha"];
    file_put_contents("proxima_senha.txt", $novaSenha);
    $mensagem = "Senha atualizada para: " . htmlspecialchars($novaSenha);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Atendente</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #eef1f7;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        input, button {
            padding: 10px 20px;
            font-size: 18px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #1e66f5;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .mensagem {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Atualizar Próxima Senha</h2>
    <form method="post">
        <input type="text" name="senha" placeholder="Digite a nova senha" required>
        <br>
        <button type="submit">Atualizar</button>
    </form>

    <?php if (!empty($mensagem)): ?>
        <div class="mensagem"><?= $mensagem ?></div>
    <?php endif; ?>
</div>
</body>
</html>
