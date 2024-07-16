$(document).on('click', '#btn-reprovar-solicitacao', function () {
	$('#motivo-reprovacao').val('');
  $('#modal-reprovacao').modal('show');
});

$(document).on('click', '#btn-confirmar-reprovacao', function () {
  // Valida se mensagem está sendo enviada
  let botao = $(this);
	const motivo = $('#motivo-reprovacao').val();

	if (!motivo) return showAlert('error', 'É necessário informar o motivo.');
	if (motivo.length < 5) return showAlert('error', 'O motivo deve conter no mínimo 5 caracteres.');
	if (motivo.length > 240) return showAlert('error', 'O motivo deve conter no máximo 240 caracteres.');

  // Inicia efeito do botão e o bloqueia
  botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Confirmando...');

  var formData = new FormData();
  formData.set('motivo', motivo);
	formData.set('acao', 'reprovado');
  formData.set('id_solicitacao', idSolicitacao);

  axios.post(`${SITE_URL}/PortalCompras/Solicitacoes/aprovacao`, formData)
    .then(response => {
      const data = response.data;
      if (data.status == 1) {
				showAlert('success', data.mensagem);

				$('#div-mensagem-aprovacao-realizada').css('display', 'block');
				$('#div-btn-aprovacao').css('display', 'none');

				$('.btn-aprovacao').attr('disabled', true);
				botao.html('Confirmar');

				$('#modal-reprovacao').modal('hide');
      }
      else {
        showAlert('error', data.mensagem);
				botao.removeAttr('disabled').html('Confirmar');
      }
    })
    .catch(error => {
      console.error(error);
      showAlert('error', lang.erro_inesperado);
			botao.removeAttr('disabled').html('Confirmar');
    })

});

$(document).on('click', '#btn-aprovar-solicitacao', async function () {
	// Valida se mensagem está sendo enviada
	let botao = $(this);

	const confirmouAprovacao = await confirmarAcao({
		titulo: 'Deseja realmente aprovar esta solicitação?',
		texto: 'Esta ação não poderá ser desfeita!'
	});

	if (!confirmouAprovacao) return;

	// Inicia efeito do botão e o bloqueia
	botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Aprovando...');

	var formData = new FormData();
	formData.set('motivo', '');
	formData.set('acao', 'aprovado');
	formData.set('id_solicitacao', idSolicitacao);

	axios.post(`${SITE_URL}/PortalCompras/Solicitacoes/aprovacao`, formData)
		.then(response => {
			const data = response.data;
			if (data.status == 1) {
				showAlert('success', data.mensagem);

				// Desabilita os botões de aprovacao
				$('.btn-aprovacao').attr('disabled', true);
				$('#div-mensagem-aprovacao-realizada').css('display', 'block');
				$('#div-btn-aprovacao').css('display', 'none');
			}
			else {
				showAlert('error', data.mensagem);
				botao.removeAttr('disabled').html('Aprovar');
			}
		})
		.catch(error => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
			botao.removeAttr('disabled').html('Aprovar');
		});

});
