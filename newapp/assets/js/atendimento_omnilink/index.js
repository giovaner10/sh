const sidDestino = obterSidCall();
const divAtender = $("#botao-atender");
const divEncerrar = $("#div-encerrar");
const botaoEncerrar = $("#disconnect-call");

function obterSidCall() {
  const w = window.location.href;
  const url = new URL(w);
  const params = new URLSearchParams(url.search);
  const sid = params.get("sidcall");
  return sid;
}

if(sidDestino) {
  $(divAtender).removeClass('hidden');
}