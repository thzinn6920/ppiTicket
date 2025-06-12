<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_atendente = $_POST['id_atendente'];
    $senha_nome = $_POST['senha_nome'];
    $assunto = $_POST['tipo_de_servico'];

    // Busca a senha pelo nome
    $stmt = $conn->prepare("SELECT id_senha, hora_emissao FROM senhas WHERE nome = ?");

    $stmt->bind_param("s", $senha_nome);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($row = $resultado->fetch_assoc()) {
        $id_senha = $row['id_senha'];
        $hora_chamada = $row['hora_emissao'];

        if (!$hora_chamada) {
            echo "Erro: hora_chamada não registrada.";
            exit;
        }

        // Hora atual (finalização)
        date_default_timezone_set('America/Sao_Paulo');
        $hora_fim = new DateTime();

        // Converte hora_chamada para DateTime
        $hora_inicio = DateTime::createFromFormat('H:i:s', $hora_chamada);

        // Calcula tempo de atendimento (em minutos)
        $tempo_atendimento = $hora_inicio ? $hora_inicio->diff($hora_fim)->i : null;

        // Registra na tabela de atendimentos
        $stmt2 = $conn->prepare("INSERT INTO atendimentos (id_senha, id_atendente, data_atendimento, hora_chamada, tempo_espera, tempo_atendimento, observacoes)
                                 VALUES (?, ?, CURDATE(), ?, 0, ?, ?)");
        $hora_chamada_str = $hora_inicio->format('H:i:s');
        $observacoes = $assunto;

        $stmt2->bind_param("iisis", $id_senha, $id_atendente, $hora_chamada_str, $tempo_atendimento, $observacoes);
        $stmt2->execute();

        // Opcional: marcar a senha como "atendida" se quiser
        $stmt3 = $conn->prepare("UPDATE senhas SET status = 'atendida' WHERE id_senha = ?");
        $stmt3->bind_param("i", $id_senha);
        $stmt3->execute();

        unset($_SESSION['senha_chamada']);
        header("Location: telaAtendente.php");
        exit;
    } else {
        echo "Erro: senha não encontrada.";
    }
}
?>
