<?php
session_start();
require_once 'config.php';

// Verifica se o atendente está logado
if (!isset($_SESSION['id_atendente'])) {
    header("Location: login.php");
    exit;
}

// Verifica se há senha em atendimento
if (!isset($_SESSION['senha_chamada'])) {
    die("Nenhuma senha está em atendimento.");
}

$senha_nome = $_POST['senha_nome'] ?? $_SESSION['senha_chamada'];
$id_atendente = $_POST['id_atendente'] ?? $_SESSION['id_atendente'];

// Busca a senha no banco
$stmt = $conn->prepare("SELECT id_senha, data_emissao, hora_emissao FROM senhas WHERE nome = ?");
$stmt->bind_param("s", $senha_nome);
$stmt->execute();
$result = $stmt->get_result();
$senha = $result->fetch_assoc();

if (!$senha) {
    die("Senha não encontrada.");
}

$id_senha = $senha['id_senha'];

// Define fuso horário
date_default_timezone_set('America/Sao_Paulo');
$agora = new DateTime();
$emissao = new DateTime($senha['data_emissao'] . ' ' . $senha['hora_emissao']);

// Calcula o tempo de espera em segundos
$diff = $emissao->diff($agora);
$tempo_espera_segundos = ($diff->days * 86400) + ($diff->h * 3600) + ($diff->i * 60) + $diff->s;

// Simula tempo de atendimento entre 3 e 10 minutos (em segundos)
$tempo_atendimento = rand(180, 600);

// Define data/hora
$data_atendimento = $agora->format('Y-m-d');
$hora_chamada = $agora->format('H:i:s');

// Atualiza o status da senha para "atendido"
$update = $conn->prepare("UPDATE senhas SET status = 'atendido' WHERE id_senha = ?");
$update->bind_param("i", $id_senha);
$update->execute();

// Insere o registro de atendimento
$insert = $conn->prepare("INSERT INTO atendimentos (
    id_senha, id_atendente, data_atendimento, hora_chamada,
    tempo_espera, tempo_atendimento, observacoes
) VALUES (?, ?, ?, ?, ?, ?, '')");

$insert->bind_param(
    "iissii",
    $id_senha,
    $id_atendente,
    $data_atendimento,
    $hora_chamada,
    $tempo_espera_segundos,
    $tempo_atendimento
);

$insert->execute();

// Limpa a sessão da senha atual
unset($_SESSION['senha_chamada']);

// Redireciona de volta ao painel
header("Location: telaAtendente.php");
exit;
?>
