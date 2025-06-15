<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'fila');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Erro de conexão']);
    exit;
}

// Quantidade total de senhas
$total = $conn->query("SELECT COUNT(*) AS total FROM senhas")->fetch_assoc()['total'];

// Quantidade de senhas atendidas
$concluidos = $conn->query("SELECT COUNT(*) AS concluidos FROM senhas WHERE status = 'atendido'")->fetch_assoc()['concluidos'];

// Quantidade em atendimento
$emAtendimento = $conn->query("SELECT COUNT(*) AS emAtendimento FROM senhas WHERE status = 'emAtendimento'")->fetch_assoc()['emAtendimento'];

// Quantidade aguardando atendimento
$aguardando = $conn->query("SELECT COUNT(*) AS aguardando FROM senhas WHERE status = 'aguardando'")->fetch_assoc()['aguardando'];

// Média do tempo de atendimento
$mediaTempoQuery = $conn->query("SELECT AVG(tempo_atendimento) AS media_tempo FROM atendimentos");
$mediaTempo = 0;
if ($mediaTempoQuery && $row = $mediaTempoQuery->fetch_assoc()) {
    $mediaTempo = round($row['media_tempo'], 2); // em segundos
}

echo json_encode([
    'total' => $total,
    'concluidos' => $concluidos,
    'emAtendimento' => $emAtendimento,
    'aguardando' => $aguardando,
    'mediaTempoAtendimento' => $mediaTempo
]);

$conn->close();
?>
