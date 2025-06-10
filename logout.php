<?php
session_start();

// Conexão com o banco
require 'config.php'; // certifique-se de que este arquivo define $conn corretamente

// Libera o guichê ocupado pelo atendente (se estiver logado)
if (isset($_SESSION['id_atendente'])) {
    $id = $_SESSION['id_atendente'];
    $conn->query("DELETE FROM ocupacao_guiches WHERE id_atendente = $id");
}

// Encerra a sessão
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
