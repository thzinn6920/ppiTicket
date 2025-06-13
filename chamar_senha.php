<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_atendente'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

// Consulta a próxima senha aguardando
$result = $conn->query("SELECT id_senha, nome FROM senhas WHERE status = 'aguardando' ORDER BY id_senha ASC LIMIT 1");

if ($row = $result->fetch_assoc()) {
    $id_senha = $row['id_senha'];
    $nome = $row['nome'];

    // Atualiza status
    $conn->query("UPDATE senhas SET status = 'chamado' WHERE id_senha = $id_senha");

    // Salva na sessão
    $_SESSION['senha_chamada'] = $nome;

    echo json_encode(['success' => true, 'senha' => $nome]);
} else {
    echo json_encode(['success' => false, 'error' => 'Nenhuma senha aguardando']);
}
