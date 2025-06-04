<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css" />

</head>
<body>
    <div class="container d-flex justify-content-center align-items-center login-container">
        <div class="w-100" style="max-width: 400px;">
            <h2 class="mb-4 text-center">Login</h2>
            <form method="post" action="auth.php">
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Senha:</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <div class="text-center mt-3">
                <a href="criar_admin.php">Não é registrado ainda? faça seu cadastro aqui</a>
            </div>
        </div>
    </div>
</body>
</html>
