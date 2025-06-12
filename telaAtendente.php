<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['atendente_logado']) || $_SESSION['atendente_logado'] !== true) {
    header("Location: login.php");
    exit();
}

// Recupera nome, guichê e id do atendente
$nomeAtendente = $_SESSION['usuario_nome'];
$idAtendente = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT g.nome AS guiche_nome FROM guiches g 
    INNER JOIN ocupacao_guiches og ON g.id_guiche = og.id_guiche 
    WHERE og.id_atendente = ?");
$stmt->bind_param("i", $idAtendente);
$stmt->execute();
$result = $stmt->get_result();
$guiche = $result->fetch_assoc();
$guicheNome = $guiche ? $guiche['guiche_nome'] : '—';

// Busca os assuntos
$assuntos = [];
$res = $conn->query("SELECT id_assunto, descricao FROM assuntos_atendimento ORDER BY descricao");
while ($row = $res->fetch_assoc()) {
    $assuntos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Painel do Atendente</title>
  <link rel="stylesheet" href="telaAtendente.css" />
</head>
<body>
  <div class="header">
    <a href="logout.php" style="position:absolute; top:10px; right:10px; padding:8px 12px; background-color:#f44336; color:white; text-decoration:none; border-radius:5px;">Logout</a>
  </div>

  <div class="main-container">
    <div class="box">
      <h2>Painel do Atendente</h2>

      <div class="card-top">
        <div class="left-side">
          <form action="chamar_senha.php" method="POST">
            <button type="submit" class="btn-call">Chamar próxima senha</button>
          </form>
        </div>
        <div class="right-side">
          <div class="user-info">
            <span>Atendente: <?php echo htmlspecialchars($nomeAtendente); ?></span><br />
            <span>Guichê: <?php echo htmlspecialchars($guicheNome); ?></span><br />
            <span>Senhas atendidas: —</span>
          </div>
        </div>
      </div>

      <div class="em-atendimento">
        <span>Em atendimento:</span><br />
        <strong><?php echo $_SESSION['senha_chamada'] ?? '– – – –'; ?></strong>
      </div>

      <form class="form-section" action="finalizar_atendimento.php" method="POST">
        <label>Assunto do Atendimento</label>
        <select name="tipo_de_servico" required>
          <option value="">Selecione</option>
          <?php foreach ($assuntos as $assunto): ?>
            <option value="<?php echo htmlspecialchars($assunto['descricao']); ?>">
              <?php echo htmlspecialchars($assunto['descricao']); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <input type="hidden" name="senha_nome" value="<?php echo $_SESSION['senha_chamada'] ?? ''; ?>">
        <input type="hidden" name="id_atendente" value="<?php echo $idAtendente; ?>">

        <div class="form-buttons">
          <button type="submit" class="btn-finalizar">Finalizar Atendimento</button>
          <button type="button" class="btn-ausente" onclick="alert('Cliente ausente — ação a ser implementada');">Cliente ausente</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
