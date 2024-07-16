validar_campos_form('form-solicitacao');

$(document).ready(function () {
	$('#viewAnexo').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
	$('#viewAnexo-rateio').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
});

$(document).on('change', '#anexo', function (e) {
	possuiAnexoSolicitacao = false;

	let uploadField = $('#viewAnexo');
  var uploadImput = document.getElementById("anexo");

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
			possuiAnexoSolicitacao = true;

			let icon = uploadImput.files[0].type == 'application/pdf' ? 'fa-file-pdf-o' : 'fa-file-image-o';
			uploadField.html(`<p class="truncade link-upload-valid"><i class="fa ${icon}"></i>  ${uploadImput.files[0].name}</p>`);
		}
  }
});

$(document).on('change', '#anexo-rateio', function (e) {
	let uploadField = $('#viewAnexo-rateio');
  var uploadImput = document.getElementById("anexo-rateio");
  
  if (uploadImput) {
		let extensoes_suportadas = [
			'application/pdf', // pdf
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // excel
			'application/vnd.ms-excel' // excel
		];

		if (uploadImput.files[0].size > (1024 * 1024 * 5)) {  //limita o tamanho da imagem em 5mb
			showAlert('error', 'O arquivo selecionado é muito grande. O tamanho máximo permitido é de 5MB.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else if (extensoes_suportadas.indexOf(uploadImput.files[0].type) === -1) {
			showAlert('error', 'O tipo de arquivo selecionado não é suportado. Por favor, selecione um arquivo XLS, XLSX ou PDF.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else {
			let icon = uploadImput.files[0].type == 'application/pdf' ? 'fa-file-pdf-o' : 'fa-file-excel-o';
			uploadField.html(`<p style="" class="truncade link-upload-valid"><i class="fa ${icon}"></i>  ${uploadImput.files[0].name}</p>`);
		}
  }
});

$(document).on('click', '#rateio', function (e) {
  const checked = $(this).prop('checked');

  $('#viewAnexo-rateio')
    .html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`)
    .attr('disabled', true);

  $('#div-anexo-rateio').removeClass('required');
  $('#anexo-rateio')
    .val(null)
    .attr('disabled', true)
    .removeAttr('required');

  if (checked) {
    $('#div-anexo-rateio').addClass('required');
    $('#anexo-rateio').removeAttr('disabled').attr('required', true);
    $('#viewAnexo-rateio').removeAttr('disabled')
  }
});

function limparCamposSolicitacao() {
	possuiAnexoSolicitacao = false;
	
	$('#form-solicitacao').trigger('reset');
	$('select').val(null).trigger('change');

	const primeiraOption = $('#empresa option:eq(1)').val();
	$('#empresa').val(primeiraOption).trigger('change');

	// Remover as mensagens de erro
	removerErrorFormulario('form-solicitacao');

	$('#viewAnexo').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
	$('#viewAnexo-rateio').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
}