<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'fila');

if ($conn->connect_error) {
  echo json_encode(['error' => 'Erro de conexão']);
  exit;
}

$total = $conn->query("SELECT COUNT(*) AS total FROM senhas")->fetch_assoc()['total'];
$concluidos = $conn->query("SELECT COUNT(*) AS concluidos FROM senhas WHERE status = 'atendido'")->fetch_assoc()['concluidos'];
$emAtendimento = $conn->query("SELECT COUNT(*) AS emAtendimento FROM senhas WHERE status = 'emAtendimento'")->fetch_assoc()['emAtendimento'];

echo json_encode([
  'total' => $total,
  'concluidos' => $concluidos,
  'emAtendimento' => $emAtendimento
]);

$conn->close();
?>
