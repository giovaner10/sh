let abasBrowser = [];
let abasWebDesk = [];
let portaSocket = null;
let URL_WEBSOCKET = ''

onconnect = function(e) {
  const porta = e.ports[0];
  porta.onmessage = function(event) {
    if (event?.data?.type === 'socketFilas') {
      abasWebDesk.push(porta);
    }
    executarEventoAba(event, porta);
  };

  abasBrowser.push(porta);
};


function executarEventoAba(evento, porta) {
  if(evento?.data?.type !== "message") {
    const tipoEvento = evento?.data?.type

    const { url } = evento?.data;
    switch (tipoEvento) {
      case "atualizarSessaoAgente":
      if(url && evento?.data?.token && abasBrowser.length === 1) {
        const { token } = evento.data

        requisicaoApiTelevendas(url, token)

        setInterval(() => {
          requisicaoApiTelevendas(url, token)
        }, 295000) // 4 minutos e 55 segundos
      }

      break

      case "socketFilas":
        if(((url && abasWebDesk.length === 1))) {
          porta.postMessage({
            type: 'socketFilasEvent',
            detail: {
              url: url,
            }
          });
          portaSocket = porta;
          URL_WEBSOCKET = url;
        }
        break
        
        default:
          const index = abasBrowser.indexOf(evento.target)
          const indexWebDesk = abasWebDesk.indexOf(evento.target)

          if (index !== -1) abasBrowser.splice(index, 1)
          if (indexWebDesk !== -1) abasWebDesk.splice(indexWebDesk, 1)
    
          if(portaSocket === evento.target && abasWebDesk.length) {
            portaSocket =  abasWebDesk[0];

            portaSocket.postMessage({
              type: 'socketFilasEvent',
              detail: {
                url: URL_WEBSOCKET,
              }
            });

          }
    
    }
  } 
}

async function requisicaoApiTelevendas(url, token, body = {}) {
  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`,
      },
      body: JSON.stringify(body),
    })

    if (!response.ok) throw new Error("Ocorreu um erro ao realizar uma requisição à API Televendas", url)

    return await response.json()
  } catch (error) {
    console.error("Ocorreu um erro ao realizar uma requisição à API Televendas", error)
  }
}