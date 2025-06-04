<?php
$host = "localhost";
$dbname = "rate";
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

// Buscar última senha
$stmt = $pdo->prepare("SELECT nome FROM senhas WHERE LEFT(nome,1) = :prefixo ORDER BY id_senha DESC LIMIT 1");
$stmt->execute(['prefixo' => $prefixo]);
$ultima = $stmt->fetch(PDO::FETCH_ASSOC);

$numero = 1;
if ($ultima && preg_match('/^[CP]\d{3}$/', $ultima['nome'])) {
    $numero = intval(substr($ultima['nome'], 1)) + 1;
}

$novaSenha = $prefixo . str_pad($numero, 3, '0', STR_PAD_LEFT);

// Pegar assunto disponível
$stmt = $pdo->query("SELECT id_assunto FROM assuntos_atendimento LIMIT 1");
$linha = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$linha) {
    die("<script>console.error('Nenhum assunto disponível');</script>");
}
$id_assunto = $linha['id_assunto'];

// Inserir senha
$data = date('Y-m-d');
$hora = date('H:i:s');
$stmt = $pdo->prepare("INSERT INTO senhas (data_emissao, hora_emissao, id_assunto, nome, status) VALUES (?, ?, ?, ?, 'aguardando')");
$stmt->execute([$data, $hora, $id_assunto, $novaSenha]);

// Enviar comando JS para exibir senha
echo "<script>parent.exibirSenha(" . json_encode($tipo) . ", " . json_encode($novaSenha) . ");</script>";
?>
