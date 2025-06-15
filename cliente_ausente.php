<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['senha_chamada'])) {
    header("Location: telaAtendente.php");
    exit;
}

$senha_nome = $_SESSION['senha_chamada'];

// Atualiza o status da senha para "cancelado"
$stmt = $conn->prepare("UPDATE senhas SET status = 'cancelado' WHERE nome = ?");
$stmt->bind_param("s", $senha_nome);
$stmt->execute();

// Limpa a sessão
unset($_SESSION['senha_chamada']);

// Redireciona de volta ao painel
header("Location: telaAtendente.php");
exit;
?>
