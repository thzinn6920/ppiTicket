<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sistema_senhas");

$email = $_POST['email'];
$senha = $_POST['senha'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email=? AND ativo=1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_tipo'] = $user['tipo'];

        if ($user['tipo'] == 'admin') {
            header("Location: painel_admin.php");
        } else {
            header("Location: painel_atendente.php");
        }
        exit;
    }
}

echo "Credenciais inválidas. <a href='login.php'>Tentar novamente</a>";
