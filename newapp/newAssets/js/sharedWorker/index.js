document.addEventListener("DOMContentLoaded", function() {
  const worker = new SharedWorker(caminhoWorker)

  worker.port.start()

  window.addEventListener("beforeunload", function() {
    worker.port.postMessage("close")
    worker.port.close()
  })

  worker.port.postMessage(
    { 
      type: "atualizarSessaoAgente",
      url: `${BASE_URL_API_TELEVENDAS}/users-session/update-user-session`, 
      token: TOKEN_URL_API_TELEVENDAS,
      }
  )

  const pathAtual = window?.location?.pathname;

  if(pathAtual === '/shownet/newapp/index.php/webdesk') {
    worker.port.postMessage(
      { 
        type: "socketFilas",
        url: `${BASE_URL_API_TELEVENDAS_WEBSOCKET}`, 
      }
    )

    // Captura de mensagens do worker
    worker.port.onmessage = function(event) {
      if (event?.data?.type === 'socketFilasEvent') {
        conexaoComOWebSocket(event?.data?.detail?.url);
      }
    };

  }

});


