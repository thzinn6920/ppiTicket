<?php
date_default_timezone_set('America/Sao_Paulo');
$host = "localhost";
$dbname = "fila";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<script>console.error('Erro na conexão: " . $e->getMessage() . "');</script>");
}

if (!isset($_POST['tipo'])) {
    die("<script>console.error('Tipo não enviado');</script>");
}

$tipo = $_POST['tipo'];
$prefixo = strtoupper(substr($tipo, 0, 1));

// Buscar última senha emitida do mesmo tipo
$stmt = $pdo->prepare("SELECT nome FROM senhas WHERE LEFT(nome,1) = :prefixo ORDER BY id_senha DESC LIMIT 1");
$stmt->execute(['prefixo' => $prefixo]);
$ultima = $stmt->fetch(PDO::FETCH_ASSOC);

$numero = 1;
if ($ultima && preg_match('/^[CP]\d{3}$/', $ultima['nome'])) {
    $numero = intval(substr($ultima['nome'], 1)) + 1;
}

$novaSenha = $prefixo . str_pad($numero, 3, '0', STR_PAD_LEFT);

// Inserir nova senha sem assunto nem cpf
$data = date('Y-m-d');
$hora = date('H:i:s');
$stmt = $pdo->prepare("INSERT INTO senhas (data_emissao, hora_emissao, nome, status) VALUES (?, ?, ?, 'aguardando')");
$stmt->execute([$data, $hora, $novaSenha]);

// Exibir modal na tela
echo "<script>parent.exibirSenha(" . json_encode($tipo) . ", " . json_encode($novaSenha) . ");</script>";
?>
