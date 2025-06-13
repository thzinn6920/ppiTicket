<?php
ob_start(); // Inicia o buffer de saída
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel do Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
  <style>
    .section { display: none; }
    .section.active { display: block; }
    .form-container { max-width: 500px; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h4>Administrador</h4>
    <nav class="nav flex-column">
      <a href="#dashboard" class="nav-link active" onclick="mostrarSecao('dashboard', this)">📊 Dashboard</a>
      <a href="#metricas" class="nav-link" onclick="mostrarSecao('metricas', this)">📈 Métricas</a>
      <a href="#criar" class="nav-link" onclick="mostrarSecao('criar', this)">👤 Criar Atendente</a>
      <a href="#criarGuiche" class="nav-link" onclick="mostrarSecao('criarGuiche', this)">🏢 Criar Guichê</a>
      <a href="#online" class="nav-link" onclick="mostrarSecao('online', this)">🟢 Atendentes Online</a>
    </nav>
  </div>

  <div class="content">
    <div id="dashboard" class="section active">
      <h2>📊 Dashboard</h2>
      <div id="dashboardDados">Carregando...</div>
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
        <form method="post" action="adminPainel.php#criar">
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
          <div class="mb-3">
            <label>Guichê:</label>
            <select name="id_guiche" class="form-control" required>
              <?php
                $conn = new mysqli('localhost', 'root', '', 'fila');
                $res = $conn->query("SELECT id_guiche, nome FROM guiches");
                while ($row = $res->fetch_assoc()) {
                  echo "<option value='{$row['id_guiche']}'>{$row['nome']}</option>";
                }
                $conn->close();
              ?>
            </select>
          </div>
          <button type="submit" name="criar_atendente" class="btn btn-primary w-100">Criar Usuário</button>
        </form>
      </div>
    </div>

    <div id="criarGuiche" class="section">
      <h2>🏢 Criar Guichê</h2>
      <div class="form-container">
        <?php if (isset($_SESSION['guiche_msg'])): ?>
          <div class="alert alert-warning d-flex align-items-center" role="alert">
  <?php echo $_SESSION['guiche_msg']; unset($_SESSION['guiche_msg']); ?>
</div>
        <?php endif; ?>
        <form method="post" action="adminPainel.php#criarGuiche">
          <div class="mb-3">
            <label>Nome do Guichê:</label>
            <input type="text" name="nome_guiche" class="form-control" maxlength="20" required placeholder="Ex: Guichê 01">
          </div>
          <button type="submit" name="criar_guiche" class="btn btn-primary w-100">Criar Guichê</button>
        </form>
      </div>
    </div>

    <div id="online" class="section">
      <h2>🟢 Atendentes Online</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-success">
            <tr>
              <th>Nome</th>
              <th>Email</th>
              <th>Guichê</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $conn = new mysqli('localhost', 'root', '', 'fila');
              $res = $conn->query("SELECT a.nome, a.email, g.nome AS guiche FROM atendentes a INNER JOIN guiches g ON a.id_guiche = g.id_guiche");
              while ($row = $res->fetch_assoc()) {
                echo "<tr><td>{$row['nome']}</td><td>{$row['email']}</td><td>{$row['guiche']}</td></tr>";
              }
              $conn->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function mostrarSecao(id, link = null) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      if (link) link.classList.add('active');
      if (id === 'dashboard') carregarDashboard();
      window.location.hash = id;
    }

    function carregarDashboard() {
      fetch('api_dashboard.php')
        .then(res => res.json())
        .then(data => {
          document.getElementById('dashboardDados').innerHTML = `
            <div class="row">
              <div class="col-md-4"><div class="card text-white bg-primary mb-3"><div class="card-body"><h5 class="card-title">Total de Atendimentos</h5><p class="card-text fs-3">${data.total}</p></div></div></div>
              <div class="col-md-4"><div class="card text-white bg-success mb-3"><div class="card-body"><h5 class="card-title">Concluídos</h5><p class="card-text fs-3">${data.concluidos}</p></div></div></div>
              <div class="col-md-4"><div class="card text-white bg-warning mb-3"><div class="card-body"><h5 class="card-title">Em Atendimento</h5><p class="card-text fs-3">${data.emAtendimento}</p></div></div></div>
            </div>
          `;
        });
    }

    window.onload = () => {
      const hash = window.location.hash.replace('#', '') || 'dashboard';
      const link = [...document.querySelectorAll('.nav-link')].find(l => l.getAttribute('onclick')?.includes(hash));
      mostrarSecao(hash, link);
    };
  </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar_atendente'])) {
  $conn = new mysqli('localhost', 'root', '', 'fila');
  if ($conn->connect_error) {
    $_SESSION['admin_msg'] = "Erro de conexão: " . $conn->connect_error;
    header("Location: adminPainel.php#criar");
    exit;
  }

  $nome = $_POST['nome'];
  $matricula = $_POST['matricula'];
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $nivel = 'atendente';
  $id_guiche = $_POST['id_guiche'];

  $stmt = $conn->prepare("SELECT 1 FROM atendentes WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['admin_msg'] = "⚠️ Esse email já está cadastrado.";
    $stmt->close();
    $conn->close();
    header("Location: adminPainel.php#criar");
    exit;
  }

  $stmt->close();

  $stmt = $conn->prepare("INSERT INTO atendentes (nome, matricula, email, senha, nivel, id_guiche) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sisssi", $nome, $matricula, $email, $senha, $nivel, $id_guiche);
  if ($stmt->execute()) {
    $_SESSION['admin_msg'] = "✅ Usuário criado com sucesso!";
  } else {
    $_SESSION['admin_msg'] = "Erro ao criar usuário: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();

  header("Location: adminPainel.php#criar");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar_guiche'])) {
  $conn = new mysqli('localhost', 'root', '', 'fila');
  if ($conn->connect_error) {
    $_SESSION['guiche_msg'] = "Erro de conexão: " . $conn->connect_error;
    header("Location: adminPainel.php#criarGuiche");
    exit;
  }

  $nome_guiche = trim($_POST['nome_guiche']);

  $stmt = $conn->prepare("SELECT 1 FROM guiches WHERE nome = ?");
  $stmt->bind_param("s", $nome_guiche);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['guiche_msg'] = "⚠️ Já existe um guichê com esse nome.";
    $stmt->close();
    $conn->close();
    header("Location: adminPainel.php#criarGuiche");
    exit;
  }

  $stmt->close();

  $stmt = $conn->prepare("INSERT INTO guiches (nome) VALUES (?)");
  $stmt->bind_param("s", $nome_guiche);
  if ($stmt->execute()) {
    $_SESSION['guiche_msg'] = "✅ Guichê criado com sucesso!";
  } else {
    $_SESSION['guiche_msg'] = "Erro ao criar guichê: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();

  header("Location: adminPainel.php#criarGuiche");
  exit;
}
?>
