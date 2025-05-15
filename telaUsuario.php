<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Retire sua Senha</title>
  <link rel="stylesheet" href="telaUsuario.css" />
</head>
<body>
  <main class="container">
    <h1>Bem-vindo!</h1>
    <h2>Retire sua Senha</h2>

    <form class="form" onsubmit="return false;">
      <div class="radio-group">
        <input type="radio" id="comum" name="atendimento" value="Comum" class="radio-input" checked>
        <label for="comum" class="radio-label">👤 Atendimento Comum</label>

        <input type="radio" id="prioritario" name="atendimento" value="Prioritário" class="radio-input">
        <label for="prioritario" class="radio-label">♿ Prioritário</label>

        <input type="radio" id="gestantes" name="atendimento" value="Gestantes" class="radio-input">
        <label for="gestantes" class="radio-label">🤱 Gestantes / Crianças de colo</label>
      </div>

      <button type="submit" class="btn emitir" onclick="mostrarPopup()">Emitir Senha</button>
    </form>
  </main>

  <!-- Modal (popup) -->
  <div class="popup" id="popup">
    <div class="popup-content">
      <h2>Senha Emitida</h2>
      <p class="tipo">Tipo: <strong id="tipoSenha">Atendimento</strong></p>
      <p class="senha-grande" id="codigoSenha">C000</p>
      <p class="temporizador">Esta janela fechará em <span id="tempo">5</span> segundos...</p>
    </div>
  </div>

  <script>
    let contador = 0;

    function gerarSenha(tipo) {
      const letra = tipo[0].toUpperCase();
      contador++;
      return `${letra}${String(contador).padStart(3, '0')}`;
    }

    function mostrarPopup() {
      const tipo = document.querySelector('input[name="atendimento"]:checked').value;
      const senha = gerarSenha(tipo);

      document.getElementById("tipoSenha").innerText = tipo;
      document.getElementById("codigoSenha").innerText = senha;

      const popup = document.getElementById("popup");
      popup.style.display = "flex";

      let tempo = 5;
      const tempoEl = document.getElementById("tempo");
      tempoEl.innerText = tempo;

      const intervalo = setInterval(() => {
        tempo--;
        tempoEl.innerText = tempo;
        if (tempo === 0) {
          clearInterval(intervalo);
          popup.style.display = "none";
        }
      }, 1000);
    }
  </script>
</body>
</html>
