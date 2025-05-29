<?php
session_start();
require_once 'config.php'; // Arquivo com a conexão ao banco de dados

// Verifica se os campos foram enviados
if (!isset($_POST['email'], $_POST['senha'])) {
    header('Location: login.php?erro=1'); // Campos não enviados
    exit;
}

$email = $_POST['email'];
$senha = $_POST['senha'];

// Consulta o usuário no banco com email e ativo = 1
$stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ? AND ativo = 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Se o usuário for encontrado
if ($user = $result->fetch_assoc()) {
    // Verifica se a senha digitada confere com o hash
    if (password_verify($senha, $user['senha'])) {
        // Autenticado com sucesso: cria a sessão
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_tipo'] = $user['tipo'];

        // Redireciona com base no tipo de usuário
        if ($user['tipo'] === 'admin') {
            header("Location: painel_admin.php");
        } elseif ($user['tipo'] === 'atendente') {
            header("Location: painel_atendente.php");
        } else {
            header("Location: login.php?erro=3"); // Tipo inválido
        }
        exit;
    } else {
        // Senha incorreta
        header("Location: login.php?erro=2");
        exit;
    }
} else {
    // Usuário não encontrado ou inativo
    header("Location: login.php?erro=1");
    exit;
}
?>
