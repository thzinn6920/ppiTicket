<?php
session_start();
require_once 'config.php'; // Arquivo de conexão com $conn

// Verifica se os campos foram enviados
if (!isset($_POST['email'], $_POST['senha'])) {
    header('Location: login.php?erro=1'); // Campos não enviados
    exit;
}

$email = $_POST['email'];
$senha = $_POST['senha'];

// Consulta o atendente no banco
$stmt = $conn->prepare("SELECT id_atendente, nome, senha, nivel FROM atendentes WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($senha, $user['senha'])) {
        // Autenticado com sucesso
        $_SESSION['usuario_id'] = $user['id_atendente'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_tipo'] = $user['nivel'];

        // Redireciona conforme o nível
        if ($user['nivel'] === 'admin') {
            header("Location: adminPainel.php");
        } else {
            $_SESSION['atendente_logado'] = true; // <<< FLAG DE ATENDENTE LOGADO
            header("Location: telaAtendente.php");
        }
        exit;
    } else {
        // Senha incorreta
        header("Location: login.php?erro=2");
        exit;
    }
} else {
    // Usuário não encontrado
    header("Location: login.php?erro=1");
    exit;
}
?>
