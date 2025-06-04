<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Criar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3 class="text-center mb-4">Criar Usuário</h3>
        <?php if (isset($_SESSION['admin_msg'])): ?>
            <div class="alert alert-info"><?php echo $_SESSION['admin_msg']; unset($_SESSION['admin_msg']); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label>Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Matrícula:</label>
                <input type="number" name="matricula" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha:</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nível de Acesso:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="nivel" value="admin" required>
                    <label class="form-check-label">Admin</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="nivel" value="atendente" required>
                    <label class="form-check-label">Atendente</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Criar Usuário</button>
            <a href="login.php" class="d-block mt-3 text-center">Voltar para login</a>
        </form>
    </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexão com o banco
    $conn = new mysqli('localhost', 'root', '', 'ppi');

    if ($conn->connect_error) {
        $_SESSION['admin_msg'] = "Erro de conexão: " . $conn->connect_error;
        header("Location: criar_admin.php");
        exit;
    }

    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $nivel = $_POST['nivel'];

    // Verifica se já existe
    $stmt = $conn->prepare("SELECT id_atendente FROM atendentes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['admin_msg'] = "⚠️ Esse email já está cadastrado.";
    } else {
        $stmt = $conn->prepare("INSERT INTO atendentes (nome, matricula, email, senha, nivel, id_guiche) VALUES (?, ?, ?, ?, ?, NULL)");
        $stmt->bind_param("sisss", $nome, $matricula, $email, $senha, $nivel);

        if ($stmt->execute()) {
            $_SESSION['admin_msg'] = "✅ Usuário criado com sucesso!";
        } else {
            $_SESSION['admin_msg'] = "Erro ao criar usuário: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();

    header("Location: criar_admin.php");
    exit;
}
?>
