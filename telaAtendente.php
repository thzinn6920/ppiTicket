<?php
session_start();
if (!isset($_SESSION['atendente_logado']) || $_SESSION['atendente_logado'] !== true) {
    header("Location: login.php");
    exit();
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
          <button class="btn-call">Chamar próxima senha</button>
        </div>
        <div class="right-side">
          <div class="user-info">
            <span>Atendente: João Exemplo</span><br />
            <span>Guichê: 03</span><br />
            <span>Senhas atendidas: 0</span>
          </div>
         
        </div>
      </div>

      <div class="em-atendimento">
        <span>Em atendimento:</span><br />
        <strong>– – – –</strong>
      </div>

      <form class="form-section" id="formAtendimento" method="POST">
       
        <label>Assunto do Atendimento</label>
        <select name="tipo_de_servico" required>
          <option value="">Selecione</option>
          <option value="Informações gerais">Informações gerais</option>
          <option value="Cadastro">Cadastro</option>
          <option value="Suporte">Suporte</option>
          <option value="Reclamação">Reclamação</option>
        </select>

        
        <input type="hidden" name="id_atendente" value="2" />

        <div class="form-buttons">
          <button type="submit" class="btn-finalizar">Finalizar Atendimento</button>
          <button type="button" class="btn-ausente">Cliente ausente</button>
        </div>
      </form>

    </div>
  </div>

  <script>
    document.getElementById('formAtendimento').addEventListener('submit', function(e) {
      e.preventDefault(); // previne o envio normal

      const form = e.target;
      const formData = new FormData(form);

      fetch('finalizar_atendimento.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(text => {
        alert(text);   // mostra a mensagem retornada pelo PHP
        form.reset();  // limpa o formulário
      })
      .catch(error => {
        alert('Erro ao enviar dados: ' + error);
      });
    });
  </script>
</body>
</html>
