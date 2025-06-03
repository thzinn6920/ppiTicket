<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Painel do Atendente</title>
  <link rel="stylesheet" href="telaAtendente.css" />
</head>
<body>
  <div class="header"></div>

  <div class="main-container">
    <div class="box">
      <h2>Painel do Atendente</h2>

      <div class="card-top">
        <div class="left-side">
          <button class="btn-offline">OFFLINE</button>
          <button class="btn-call">Chamar próxima senha</button>
        </div>
        <div class="right-side">
          <div class="user-info">
            <span>Atendente: João Exemplo</span><br />
            <span>Guichê: 03</span><br />
            <span>Senhas atendidas: 0</span>
          </div>
          <div class="user-icon">👤</div>
        </div>
      </div>

      <div class="em-atendimento">
        <span>Em atendimento:</span><br />
        <strong>– – – –</strong>
      </div>

      <form class="form-section">
        <label>Identificação do Cliente</label>
        <input type="text" placeholder="Nome e segundo nome do cliente" />
        <input type="text" placeholder="Digite o CPF" />

        <label>Assunto do Atendimento</label>
        <select>
          <option>Informações gerais</option>
          <option>Cadastro</option>
          <option>Suporte</option>
          <option>Reclamação</option>
        </select>

        <div class="form-buttons">
          <button class="btn-finalizar">
            Finalizar Atendimento
          </button>
          <button class="btn-ausente">Cliente ausente</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
