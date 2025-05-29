<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Atendente - Sistema de Senhas</title>
  <link rel="stylesheet" href="telaAtendente.css" />
</head>
<body>
  <header class="top-bar">
    <div class="atendente-info">
      <strong>Atendente:</strong> <span id="nomeAtendente">João Silva</span><br />
      <strong>Guichê:</strong> <span id="guicheAtendente">3</span>
    </div>
  </header>

  <main class="container">
    <h1>Painel do Atendente</h1>

    <section class="painel-senhas">
      <div>
        <p><strong>Próxima Senha:</strong></p>
        <span id="proximaSenha" class="painel-numero">A001</span>
      </div>
      <div class="caixa">
        <p><strong >Em atendimento:</strong></p>
        <span id="ultimaSenha" class="grenn">P002</span>
      </div>
    </section>
    <main class="containerr">

    <label for="cliente">Identificação do Cliente</label>
    <input type="text" id="cliente" placeholder="Digite o CPF ou nome completo" />

    <label for="assunto">Assunto do Atendimento</label>
    <select id="assunto">
      <option value="Informações gerais">Informações gerais</option>
      <option value="Cadastro/Atualização">Cadastro/Atualização</option>
      <option value="Reclamações">Reclamações</option>
      <option value="Financeiro">Financeiro</option>
      <option value="Outros">Outros</option>
    </select>

    <div class="buttons">
      <button >✅ Finalizar Atendimento</button>
    </div>

    <div class="info-box" id="infoBox" style="display: none;">
      <p><strong>Senha atual:</strong> <span id="senhaAtual">-</span></p>
      <p><strong>Cliente:</strong> <span id="clienteNome">-</span></p>
      <p><strong>Assunto:</strong> <span id="assuntoSelecionado">-</span></p>
    </div>
  </main>

    <form cla