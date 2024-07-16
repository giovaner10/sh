// Remove os erros do formulário que foram gerados pelo validate do jquery
function removerErrorFormulario(idForm) {
  $(`#${idForm} label.error`).remove();
  $(`#${idForm}`).find('.is-invalid').removeClass('is-invalid');
  $(`#${idForm}`).find('.error').removeClass('error');
}

function formatarParaMoedaBRL(valor) {
  return Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor || 0);
}

function formatarParaMoeda(valor, moeda = 'BRL', local = undefined) {
	if (local) return Intl.NumberFormat(local, { style: 'currency', currency: moeda }).format(valor || 0);
	return Intl.NumberFormat({ style: 'currency', currency: moeda }).format(valor || 0);
}

function formatarParaDecimal(valor) {
	if (typeof valor !== 'string') return valor;
	let valorFormatado = valor.replace(/\./g, '').replace(',', '.');
  return parseFloat(valorFormatado);
}

document.addEventListener('DOMContentLoaded', function () {
	var infoIcon = document.querySelector('.informativo_shownet');
	var infoBox = document.getElementById('pop_informativo_shownet');

	if (infoIcon && infoBox) {
		infoIcon.addEventListener('mouseenter', function () {
			infoBox.style.visibility = 'visible';
			infoBox.style.opacity = '1';
		});
	
		infoIcon.addEventListener('mouseleave', function () {
			infoBox.style.visibility = 'hidden';
			infoBox.style.opacity = '0';
		});
	}
});