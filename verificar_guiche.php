<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=fila;charset=utf8', 'usuario', 'senha');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro na conexão com o banco de dados.']);
    exit;
}

$guiche = $_POST['guiche'] ?? '';

if ($guiche === '') {
    echo json_encode(['status' => 'error', 'message' => 'Guichê inválido.']);
    exit;
}

// Verificar se guichê existe
$stmt = $pdo->prepare("SELECT id, em_uso FROM guiches WHERE numero = ?");
$stmt->execute([$guiche]);
$guicheData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$guicheData) {
    echo json_encode(['status' => 'error', 'message' => 'Guichê não cadastrado.']);
    exit;
}

// Verificar se está em uso
if ($guicheData['em_uso']) {
    echo json_encode(['status' => 'error', 'message' => 'Guichê já em uso.']);
    exit;
}

// Marcar o guichê como em uso
$update = $pdo->prepare("UPDATE guiches SET em_uso = 1 WHERE id = ?");
$update->execute([$guicheData['id']]);

echo json_encode(['status' => 'ok']);
?>
