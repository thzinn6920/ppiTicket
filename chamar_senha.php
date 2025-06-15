<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Impede acesso direto via navegador
if (
    !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    echo json_encode(['success' => false, 'error' => 'Acesso não autorizado']);
    exit;
}

// Verifica se atendente está logado
if (!isset($_SESSION['id_atendente'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

// Impede chamar nova senha se ainda houver uma em atendimento
if (!empty($_SESSION['senha_chamada'])) {
    echo json_encode(['success' => false, 'error' => 'Finalize ou cancele o atendimento atual antes de chamar uma nova senha.']);
    exit;
}

$id_atendente = $_SESSION['id_atendente'];

// Primeiro tenta buscar a senha PRIORITÁRIA mais antiga
$stmt = $conn->prepare("
    SELECT id_senha, nome FROM senhas
    WHERE status = 'aguardando' AND nome LIKE 'P%'
    ORDER BY id_senha ASC LIMIT 1
");
$stmt->execute();
$stmt->bind_result($id_senha, $nome);
$stmt->fetch();
$stmt->close();

// Se não encontrou senha prioritária, busca senha comum mais antiga
if (empty($id_senha)) {
    $stmt = $conn->prepare("
        SELECT id_senha, nome FROM senhas
        WHERE status = 'aguardando' AND nome NOT LIKE 'P%'
        ORDER BY id_senha ASC LIMIT 1
    ");
    $stmt->execute();
    $stmt->bind_result($id_senha, $nome);
    $stmt->fetch();
    $stmt->close();
}

if (!empty($id_senha)) {
    // Atualiza status para "chamado"
    $stmt = $conn->prepare("UPDATE senhas SET status = 'chamado' WHERE id_senha = ?");
    $stmt->bind_param("i", $id_senha);
    $stmt->execute();
    $stmt->close();

    // Salva na sessão
    $_SESSION['senha_chamada'] = $nome;

    echo json_encode(['success' => true, 'senha' => $nome]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nenhuma senha aguardando']);
}
