<?php
$proximaSenha = file_exists("proxima_senha.txt") ? trim(file_get_contents("proxima_senha.txt")) : "00";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chamado de Senhas</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
        }

        h1 {
            font-size: 24px;
            color: #0a3d62;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .linha {
            border-top: 3px solid #0a3d62;
            margin: 10px 0 30px;
        }

        .proxima, .aguarde {
            font-size: 20px;
            color: #333;
            margin: 20px 0;
        }

        .senha-destaque {
            font-weight: bold;
            font-size: 26px;
            color: #0a3d62;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Senha 019 sendo atendida no guichê 03</h1>
    <div class="linha"></div>

    <div class="proxima">
        Próxima senha a ser atendida é: <br>
        <span class="senha-destaque"><?= htmlspecialchars($proximaSenha) ?></span>
    </div>

    <div class="aguarde">
        Aguarde você já será atendido
    </div>
</div>

</body>
</html>
