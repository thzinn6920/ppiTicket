<?php
$host = 'localhost';
$db = 'fila';         // Nome atualizado do banco
$user = 'root';      // Padrão do XAMPP
$pass = '';          // Senha padrão (vazia)
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

// Verificação da conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
