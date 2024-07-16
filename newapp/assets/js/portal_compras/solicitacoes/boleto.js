$(document).on('click', '.incluirBoleto', async function (e) {
  e.stopPropagation();

  const botao = $(this);
  const idSolicitacao = botao.attr('data-id');

  $('#btn-incluir-boleto').attr('data-id_solicitacao', idSolicitacao);
  $('#modal-boleto').modal('show');
});


$(document).on('change', '#anexo-boleto', function (e) {
  let uploadField = $('#viewAnexo-boleto');
  var uploadInput = document.getElementById("anexo-boleto");

  if (uploadInput) {
    let extensoes_suportadas = [
      'application/pdf',
    ];

    if (uploadInput.files[0].size > (1024 * 1024 * 5)) {  //limita o tamanho da imagem em 5mb
      showAlert('error', 'O arquivo selecionado é muito grande. O tamanho máximo permitido é de 5MB.');
      uploadInput.value = "";
      uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
    }
    else if (extensoes_suportadas.indexOf(uploadInput.files[0].type) === -1) {
      showAlert('error', 'O tipo de arquivo selecionado não é suportado. Por favor, selecione um arquivo PDF.');
      uploadInput.value = "";
      uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
    }
    else {
      uploadField.html(`<p style="" class="truncade link-upload-valid"><i class="fa fa-file-pdf-o"></i>  ${uploadInput.files[0].name}</p>`);
    }
  }
});

async function incluirBoleto() {
  let botao = $('#btn-incluir-boleto');
  let formData = new FormData($('#form-boleto')[0]);
  const idSolicitacao = botao.attr('data-id_solicitacao');

  const anexo = formData.get('anexo');
  if (!anexo?.size) return showAlert('error', 'Anexo é necessário.');

  botao
    .html('<i class="fa fa-spinner fa-spin"></i> Enviando...')
    .attr('disabled', true);

  const url = `${SITE_URL}/PortalCompras/Boleto/incluir/${idSolicitacao}`;

  await axios.post(url, formData)
    .then((response) => {
      const data = response.data;
      if (data.status == '1') {
        // Atualiza a tabela de solicitacoes e modifica a situacao
        const nodeRow = buscarNodeNaAgGrid(idSolicitacao);
        if (nodeRow) {
          const indexRow = nodeRow.rowIndex;
          const solicitacao = nodeRow.data;
          solicitacao.situacao = 'finalizado';
          atualizarRegistroNaAgGrid(indexRow, solicitacao);
        }

        $('#modal-boleto').modal('hide');

        showAlert('success', data.mensagem);
      }
      else {
        showAlert('error', data.mensagem);
      }
    })
    .catch((error) => {
      console.error(error);
      showAlert('error', lang.erro_inesperado);
    })
    .finally(() => {
      botao.html('Enviar').attr('disabled', false);
    });

  $('#form-boleto')[0].reset();
  $('#viewAnexo-boleto').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
  $('#anexo-boleto').val('');

  removerErrorFormulario('form-boleto');
}