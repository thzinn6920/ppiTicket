<?php
session_start();
require 'config.php';
header('Content-Type: application/json');

$id_atendente = $_SESSION['id_atendente'] ?? 0;
$id_guiche = intval($_POST['id_guiche'] ?? 0);
$response = ['success' => false];

if ($id_atendente > 0 && $id_guiche > 0) {
    $verifica = $conn->prepare("SELECT COUNT(*) AS ocupado FROM ocupacao_guiches WHERE id_guiche = ?");
    $verifica->bind_param("i", $id_guiche);
    $verifica->execute();
    $ocupado = $verifica->get_result()->fetch_assoc()['ocupado'];

    if ($ocupado == 0) {
        $insere = $conn->prepare("INSERT INTO ocupacao_guiches (id_guiche, id_atendente) VALUES (?, ?)");
        $insere->bind_param("ii", $id_guiche, $id_atendente);
        if ($insere->execute()) {
            $response['success'] = true;
        }
    }
}

echo json_encode($response);
exit;
?>
