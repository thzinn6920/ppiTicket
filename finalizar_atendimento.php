<?php
include 'config.php';  // sua conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_cliente = $conn->real_escape_string($_POST['nome_cliente']);
    $cpf_cliente = $conn->real_escape_string($_POST['cpf_cliente']);
    $descricao_assunto = $conn->real_escape_string($_POST['tipo_de_servico']);
    $observacoes = $conn->real_escape_string($_POST['observacoes']);
    $id_atendente = intval($_POST['id_atendente']);

    // Buscar o id_assunto correspondente ao texto recebido no select
    $sqlBuscaAssunto = "SELECT id_assunto FROM assuntos_atendimento WHERE descricao = ?";
    $stmt = $conn->prepare($sqlBuscaAssunto);
    $stmt->bind_param("s", $descricao_assunto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Assunto não encontrado: " . htmlspecialchars($descricao_assunto);
        exit;
    }

    $row = $result->fetch_assoc();
    $id_assunto = $row['id_assunto'];

    // Inserir na tabela senhas (emissão da senha)
    $data_emissao = date('Y-m-d');
    $hora_emissao = date('H:i:s');
    $status = 'atendido';

    $sqlInsertSenha = "INSERT INTO senhas (data_emissao, hora_emissao, id_assunto, nome, cpf, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtSenha = $conn->prepare($sqlInsertSenha);
    $stmtSenha->bind_param("ssisss", $data_emissao, $hora_emissao, $id_assunto, $nome_cliente, $cpf_cliente, $status);

    if ($stmtSenha->execute()) {
        $id_senha = $stmtSenha->insert_id;

        // Agora insere na tabela atendimentos
        $data_atendimento = $data_emissao;
        $hora_chamada = $hora_emissao;
        $tempo_espera = 0;
        $tempo_atendimento = 0;

        $sqlInsertAtendimento = "INSERT INTO atendimentos (id_senha, id_atendente, data_atendimento, hora_chamada, tempo_espera, tempo_atendimento, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtAtendimento = $conn->prepare($sqlInsertAtendimento);
        $stmtAtendimento->bind_param("iissiis", $id_senha, $id_atendente, $data_atendimento, $hora_chamada, $tempo_espera, $tempo_atendimento, $observacoes);

        if ($stmtAtendimento->execute()) {
            echo "Atendimento finalizado e salvo com sucesso!";
        } else {
            echo "Erro ao salvar atendimento: " . $conn->error;
        }
        $stmtAtendimento->close();
    } else {
        echo "Erro ao salvar senha: " . $conn->error;
    }
    $stmtSenha->close();
    $stmt->close();
    $conn->close();
} else {
    echo "Método inválido.";
}
?>
