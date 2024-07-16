$(document).on('click', '#botao-rediscagem', function (e) {
  e.preventDefault();
  const telefone = $(this).data('telefone');
  const nome = $(this).data('nome');

  ativarGuia('chamada-em-curso')

  // Tirar o +55 do telefone
  const telefoneSem55 = telefone.replace('+55', '');

  if(nome) {
    nomeContato.text(nome);
  }
  if(telefone) {
    numeroDestino.value = telefoneSem55;
  }
  $('#btn-ligar').click();

  tabDiscador.addClass('active');
  liDiscador.addClass('active');
  tabContatos.removeClass('active');
  liContatos.removeClass('active');
});