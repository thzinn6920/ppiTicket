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

// Marca como atendido
$update = $conn->prepare("UPDATE senhas SET status = 'atendido' WHERE id_senha = ?");
$update->bind_param("i", $senha['id_senha']);
$update->execute();

// Calcula os tempos
date_default_timezone_set('America/Sao_Paulo');
$agora = new DateTime();
$emissao = new DateTime($senha['data_emissao'] . ' ' . $senha['hora_emissao']);

$tempo_espera = $emissao->diff($agora)->i;     // em minutos
$tempo_atendimento = rand(3, 10);              // simulação

$data_atendimento = $agora->format('Y-m-d');
$hora_chamada = $agora->format('H:i:s');

// Insere o atendimento
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

// Limpa a sessão da senha atual
unset($_SESSION['senha_chamada']);

header("Location: telaAtendente.php");
exit;
?>