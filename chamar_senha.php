<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$id_atendente = $_SESSION['usuario_id'];

// Primeiro tenta pegar uma senha PRIORITÁRIA
$stmt = $conn->prepare("
    SELECT id_senha, nome FROM senhas
    WHERE status = 'aguardando' AND nome LIKE 'P%'
    ORDER BY id_senha ASC LIMIT 1
");
$stmt->execute();
$result = $stmt->get_result();
$senha = $result->fetch_assoc();

// Se não encontrou prioritária, pega comum
if (!$senha) {
    $stmt = $conn->prepare("
        SELECT id_senha, nome FROM senhas
        WHERE status = 'aguardando' AND nome LIKE 'C%'
        ORDER BY id_senha ASC LIMIT 1
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $senha = $result->fetch_assoc();
}

if ($senha) {
    // Atualiza status da senha para "chamado"
    $update = $conn->prepare("UPDATE senhas SET status = 'chamado' WHERE id_senha = ?");
    $update->bind_param("i", $senha['id_senha']);
    $update->execute();

    // Você pode salvar na sessão para exibir na tela principal
    $_SESSION['senha_chamada'] = $senha['nome'];
} else {
    $_SESSION['senha_chamada'] = 'Nenhuma senha disponível';
}

header("Location: telaAtendente.php");
exit;
?>
