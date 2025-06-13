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

        // Salva id_atendente para uso posterior (como no logout)
        $_SESSION['id_atendente'] = $user['id_atendente'];

        if ($user['nivel'] === 'admin') {
            header("Location: adminPainel.php");
        } else {
            // Desvincula o atendente do guichê, se estiver vinculado
            $conn->query("DELETE FROM ocupacao_guiches WHERE id_atendente = " . intval($user['id_atendente']));

            $_SESSION['atendente_logado'] = true;
            header("Location: telaAtendente.php");
        }
        exit;
    } else {
        header("Location: login.php?erro=2");
        exit;
    }
} else {
    header("Location: login.php?erro=1");
    exit;
}
?>
