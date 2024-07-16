$(document).ready(function () {
	$('#view-anexo-omentario').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
});

$(document).on('change', '#anexo-comentario', function (e) {
	let uploadField = $('#view-anexo-omentario');
	var uploadImput = document.getElementById("anexo-comentario");

	if (uploadImput) {
		let extensoes_suportadas = [
			'image/jpeg',
			'image/jpg',
			'image/png',
			'application/pdf'
		];

		if (uploadImput.files[0].size > (1024 * 1024 * 5)) {  //limita o tamanho da imagem em 5mb
			showAlert('error', 'O arquivo selecionado é muito grande. O tamanho máximo permitido é de 5MB.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else if (extensoes_suportadas.indexOf(uploadImput.files[0].type) === -1) {
			showAlert('error', 'O tipo de arquivo selecionado não é suportado. Por favor, selecione um arquivo de imagem (JPEG, JPG, PNG) ou PDF.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else {
			let icon = uploadImput.files[0].type == 'application/pdf' ? 'fa-file-pdf-o' : 'fa-file-image-o';
			uploadField.html(`<p class="truncade" style="color: #348AD6;max-width: 200px; cursor: pointer;"><i class="fa ${icon}"></i>  ${uploadImput.files[0].name}</p>`);
		}
	}
});

function limparCamposComentario() {
	$('#mensagem-comentario').val('');
	$('#anexo-comentario').val('');
	$('#view-anexo-omentario').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
}

const estiloMensagemPelaFuncao = {
	solicitante: 'estilo-mensagem-solicitante',
	aprovador: 'estilo-mensagem-aprovador',
	area_compras: 'estilo-mensagem-area-compras',
	area_financeira: 'estilo-mensagem-area-financeira',
	area_fiscal: 'estilo-mensagem-area-fiscal',
}

const funcao = {
	solicitante: 'Solicitante',
	aprovador: 'Aprovador',
	area_compras: 'Área de Compras',
	area_financeira: 'Área Financeira',
	area_fiscal: 'Área Fiscal',
}

// 'mensagem-setor-compras' : 'mensagem-solicitante'

function adicionarComentario(origem, autor, mensagem, data, link) {
  const dataAtual = dayjs(data).format('DD/MM/YYYY, HH:mm:ss');
	const classeMensagem = origem ? estiloMensagemPelaFuncao?.[origem] : 'estilo-mensagem-solicitante';
	const textoHeader = `${autor} (${funcao?.[origem]}) - ${dataAtual}`;

  const htmlMensagem = /*HTML*/`
    <div class="balao-mensagem ${classeMensagem}" role="alert">
        <div class="mensagem-header">${textoHeader}</div>
        ${mensagem}
        ${link ? /*HTML*/`<div class="mensagem-footer"><a href="${BASE_URL}${link}" target="_blank">Visualizar Anexo</a></div>` : ''}
    </div>`;

  $('#messages').prepend(htmlMensagem);
  $('#messages').scrollTop(0); // Scroll to bottom
}

/** Função envio de novas mensagens */
$(document).on('click', '#btn-enviar-comentario', function () {
  // Valida se mensagem está sendo enviada
  let botao = $(this);
  const mensagem = $('#mensagem-comentario').val();
	const anexo = $('#anexo-comentario')[0].files[0];

  if (!mensagem) return showAlert('error', 'Digite uma mensagem para enviar.');
	if (mensagem.length < 2) return showAlert('error', 'A mensagem deve conter no mínimo 2 caracteres.');
	if (mensagem.length > 240) return showAlert('error', 'A mensagem deve conter no máximo 240 caracteres.');

  // Inicia efeito do botão e o bloqueia
  botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando');

  var formData = new FormData();
  formData.set('mensagem', mensagem);
	formData.set('origem', funcaoUsuario);
  formData.set('id_solicitacao', idSolicitacao);
  formData.set('anexo', anexo || '');

  axios.post(`${SITE_URL}/PortalCompras/Comentarios/cadastrar`, formData)
    .then(response => {
      const data = response.data;
      if (data.status == 1) {
        const comentario = data.comentario;
        adicionarComentario(comentario.origem, comentario.nomeUsuario, comentario.mensagem, comentario.datahoraCadastro, comentario.pathAnexo);
				limparCamposComentario();
      }
      else {
        showAlert('error', data.mensagem);
      }
    })
    .catch(error => {
      showAlert('error', 'Não foi possível enviar o comentário. Tente novamente em alguns minutos!');
    })
    .finally(() => {
			botao.removeAttr('disabled').html('Enviar');
    });

});

async function buscarComentariosSolicitacao() {
  const url = `${SITE_URL}/PortalCompras/Comentarios/listarParaSolicitacao/${idSolicitacao}`;
  return await axios.get(url)
    .then(response => {
      const data = response.data;

      if (data.status != '1') {
        showAlert('error', data.mensagem);
        return;
      }

      const comentarios = data.comentarios;
      if (comentarios.length > 0) {
        comentarios.forEach(comentario => {
          adicionarComentario(comentario.origem, comentario.nomeUsuario, comentario.mensagem, comentario.datahoraCadastro, comentario.pathAnexo);
        });
      }
    })
    .catch(error => {
      console.error(error);
      showAlert('error', 'Não foi possível carregar os comentários. Tente novamente em alguns minutos!');
    });
}