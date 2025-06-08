<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel do Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="sidebar">
    <h4>Administrador</h4>
    <nav class="nav flex-column">
      <a href="#" class="nav-link active" onclick="mostrarSecao('dashboard')">📊 Dashboard</a>
      <a href="#" class="nav-link" onclick="mostrarSecao('metricas')">📈 Métricas</a>
      <a href="#" class="nav-link" onclick="mostrarSecao('criar')">👤 Criar Atendente</a>
    </nav>
  </div>

  <div class="content">
    <div id="dashboard" class="section active">
      <h2>📊 Dashboard</h2>
      <p>Aqui você verá um resumo geral dos atendimentos.</p>
    </div>

    <div id="metricas" class="section">
      <h2>📈 Métricas</h2>
      <form method="post" class="form-container">
        <div class="mb-3">
          <label for="dia">Filtrar por dia:</label>
          <input type="date" name="dia" id="dia" class="form-control">
        </div>
        <div class="mb-3">
          <label for="inicio">Hora Início:</label>
          <input type="time" name="inicio" id="inicio" class="form-control">
        </div>
        <div class="mb-3">
          <label for="fim">Hora Fim:</label>
          <input type="time" name="fim" id="fim" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
      </form>
    </div>

    <div id="criar" class="section">
      <h2>👤 Criar Atendente</h2>
      <div class="form-container">
        <?php if (isset($_SESSION['admin_msg'])): ?>
          <div class="alert alert-info"><?php echo $_SESSION['admin_msg']; unset($_SESSION['admin_msg']); ?></div>
        <?php endif; ?>
        <form method="post" action="">
          <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Matrícula:</label>
            <input type="number" name="matricula" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Senha:</label>
            <input type="password" name="senha" class="form-control" required>
          </div>
          <br>
          <div class="mb-3">
            <label>Guichê:</label><br>
            <input class="form-control" type="number" min="1">
          </div>
          <button type="submit" class="btn btn-primary w-100">Criar Usuário</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function mostrarSecao(id) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      event.target.classList.add('active');
    }
  </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
  $conn = new mysqli('localhost', 'root', '', 'fila');
  if ($conn->connect_error) {
    $_SESSION['admin_msg'] = "Erro de conexão: " . $conn->connect_error;
    header("Location: admin_painel.php");
    exit;
  }
  $nome = $_POST['nome'];
  $matricula = $_POST['matricula'];
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $nivel = $_POST['nivel'];

  $stmt = $conn->prepare("SELECT id_atendente FROM atendentes WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['admin_msg'] = "⚠️ Esse email já está cadastrado.";
  } else {
    $stmt = $conn->prepare("INSERT INTO atendentes (nome, matricula, email, senha, nivel, id_guiche) VALUES (?, ?, ?, ?, ?, NULL)");
    $stmt->bind_param("sisss", $nome, $matricula, $email, $senha, $nivel);
    if ($stmt->execute()) {
      $_SESSION['admin_msg'] = "✅ Usuário criado com sucesso!";
    } else {
      $_SESSION['admin_msg'] = "Erro ao criar usuário: " . $stmt->error;
    }
  }

  $stmt->close();
  $conn->close();

  header("Location: admin_painel.php");
  exit;
}
?>
