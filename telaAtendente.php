<?php
session_start();
require 'config.php';

if (!isset($_SESSION['atendente_logado']) || $_SESSION['atendente_logado'] !== true) {
    header("Location: login.php");
    exit();
}

$id_atendente = $_SESSION['id_atendente'];

// Buscar nome e guichê do atendente
$sql = "SELECT a.nome, g.nome AS guiche
        FROM atendentes a
        LEFT JOIN ocupacao_guiches og ON og.id_atendente = a.id_atendente
        LEFT JOIN guiches g ON g.id_guiche = og.id_guiche
        WHERE a.id_atendente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_atendente);
$stmt->execute();
$stmt->bind_result($nome_atendente, $guiche_nome);
$stmt->fetch();
$stmt->close();

// Buscar total de senhas atendidas
$stmt = $conn->prepare("SELECT COUNT(*) FROM atendimentos WHERE id_atendente = ?");
$stmt->bind_param("i", $id_atendente);
$stmt->execute();
$stmt->bind_result($total_atendimentos);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Painel do Atendente</title>
  <link rel="stylesheet" href="telaAtendente.css" />
  <style>
    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(0,0,0,0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10;
    }

    .modal-content {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      width: 300px;
    }

    .modal select {
      padding: 8px;
      width: 100%;
      margin-bottom: 15px;
    }

    .modal button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 5px;
    }
  </style>
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
          <button id="btnChamarSenha" class="btn-call" type="button">Chamar próxima senha</button>
        </div>
        <div class="right-side">
          <div class="user-info">
            <span>Atendente: <?= htmlspecialchars($nome_atendente) ?></span><br />
            <span>Guichê: <?= $guiche_nome ? htmlspecialchars($guiche_nome) : 'Não selecionado' ?></span><br />
            <span>Senhas atendidas: <?= $total_atendimentos ?></span>
          </div>
        </div>
      </div>

      <div class="em-atendimento">
        <span>Em atendimento:</span><br />
        <strong><?php echo isset($_SESSION['senha_chamada']) ? $_SESSION['senha_chamada'] : '– – – –'; ?></strong>
      </div>

      <!-- Formulário de finalização -->
      <form class="form-section" id="formAtendimento" method="POST" action="finalizar_atendimento.php">
        <label>Assunto do Atendimento</label>
        <select name="tipo_de_servico" required>
          <option value="">Selecione</option>
          <?php
            $sql = "SELECT id_assunto, descricao FROM assuntos_atendimento ORDER BY descricao";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['descricao']) . '">' . htmlspecialchars($row['descricao']) . '</option>';
            }
          ?>
        </select>

        <input type="hidden" name="id_atendente" value="<?= $id_atendente ?>" />
        <input type="hidden" name="senha_nome" id="inputSenhaNome" value="<?= isset($_SESSION['senha_chamada']) ? $_SESSION['senha_chamada'] : '' ?>">

        <div class="form-buttons">
          <button type="submit" class="btn-finalizar">Finalizar Atendimento</button>
          <button type="button" class="btn-ausente" onclick="window.location.href='cliente_ausente.php'">Cliente ausente</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal seleção de guichê -->
  <?php if (!$guiche_nome): ?>
    <div class="modal" id="modalGuiche">
      <div class="modal-content">
        <h3>Selecione seu guichê</h3>
        <select id="selectGuiche">
          <option value="">-- Selecione --</option>
          <?php
            $sql = "SELECT id_guiche, nome FROM guiches WHERE id_guiche NOT IN (SELECT id_guiche FROM ocupacao_guiches)";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
              echo "<option value='{$row['id_guiche']}'>{$row['nome']}</option>";
            }
          ?>
        </select>
        <button onclick="confirmarGuiche()">Confirmar</button>
      </div>
    </div>
  <?php endif; ?>

  <script>
    document.getElementById('btnChamarSenha').addEventListener('click', function () {
      fetch('chamar_senha.php', {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.querySelector('.em-atendimento strong').innerText = data.senha;
          document.getElementById('inputSenhaNome').value = data.senha;

          const beep = new Audio('beep.mp3');
          beep.play();
        } else {
          alert(data.error || 'Nenhuma senha aguardando.');
        }
      })
      .catch(() => {
        alert('Erro ao chamar senha.');
      });
    });

    function confirmarGuiche() {
      const id_guiche = document.getElementById('selectGuiche').value;

      if (!id_guiche) {
        alert("Selecione um guichê.");
        return;
      }

      fetch('selecionar_guiche.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id_guiche=' + id_guiche
      })
      .then(res => res.json())
      .then(json => {
        if (json.success) {
          location.reload();
        } else {
          alert('Erro ao selecionar guichê. Tente novamente.');
        }
      })
      .catch(err => {
        alert('Erro: ' + err);
      });
    }
  </script>
</body>
</html>
