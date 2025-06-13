<?php
session_start();
require_once 'config.php';

if (!isset($_POST['senha_nome'], $_POST['id_atendente'])) {
    die("Dados incompletos.");
}

$senha_nome = $_POST['senha_nome'];
$id_atendente = $_POST['id_atendente'];

// Busca a senha no banco
$stmt = $conn->prepare("SELECT id_senha, data_emissao, hora_emissao FROM senhas WHERE nome = ?");
$stmt->bind_param("s", $senha_nome);
$stmt->execute();
$result = $stmt->get_result();
$senha = $result->fetch_assoc();

if (!$senha) {
    die("Senha não encontrada.");
}

date_default_timezone_set('America/Sao_Paulo');
$agora = new DateTime();
$emissao = new DateTime($senha['data_emissao'] . ' ' . $senha['hora_emissao']);
$data_atendimento = $agora->format('Y-m-d');
$hora_chamada = $agora->format('H:i:s');
$tempo_espera = $emissao->diff($agora)->i;
$tempo_atendimento = rand(3, 10); // Simulado

if (isset($_POST['ausente'])) {
    // Cliente ausente → apenas cancela
    $update = $conn->prepare("UPDATE senhas SET status = 'cancelado' WHERE id_senha = ?");
    $update->bind_param("i", $senha['id_senha']);
    $update->execute();
    $_SESSION['msg'] = "🚫 Cliente ausente. Senha cancelada.";
} elseif (isset($_POST['finalizar'])) {
    // Finaliza normalmente
    $update = $conn->prepare("UPDATE senhas SET status = 'atendido' WHERE id_senha = ?");
    $update->bind_param("i", $senha['id_senha']);
    $update->execute();

    // Registra o atendimento
    $insert = $conn->prepare("INSERT INTO atendimentos (
        id_senha, id_atendente, data_atendimento, hora_chamada,
        tempo_espera, tempo_atendimento, observacoes
    ) VALUES (?, ?, ?, ?, ?, ?, '')");
    $insert->bind_param(
        "iissii",
        $senha['id_senha'],
        $id_atendente,
        $data_atendimento,
        $hora_chamada,
        $tempo_espera,
        $tempo_atendimento
    );
    $insert->execute();
    $_SESSION['msg'] = "✅ Atendimento finalizado com sucesso.";
} else {
    $_SESSION['msg'] = "⚠️ Ação inválida.";
}

// Limpa a sessão da senha atual
unset($_SESSION['senha_chamada']);

header("Location: telaAtendente.php");
exit;
?>
