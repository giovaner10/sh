//MASCARAS DE INPUT
$(document).on('keydown', '.cpf', function () { $(this).mask('999.999.999-99'); });
$(document).on('keydown', '.cnpj', function () { $(this).mask('99.999.999/9999-99'); });
$(document).on('keydown', '.cpfCnpj', function () { $(this).mask(maskCpfCnpj, ccOptions); });
$(document).on('keydown', '.cep', function () { $(this).mask('99999-999'); });
$(document).on('keydown', '.cnh', function () { $(this).mask('99999999999'); });
$(document).on('keydown', '.ano_veiculo', function () { $(this).mask('0000/0000'); });
$(document).on('keydown', '.celular', function () { $(this).mask(maskCell, optionsCell); });
$(document).on('keydown', '.fone', function () { $(this).mask(maskCell, optionsCell); });
$(document).on('keydown', '.telefone', function () { $(this).mask('(99) 9999-9999'); });
$(document).on('keydown', '.ncm', function () { $(this).mask('9999.99.99'); });
$(document).on('keydown', '.chassi', function () {
	$(this).mask('ZZZ ZZZZZZ ZZ ZZZZZZ', { translation: { 'Z': { pattern: /[A-Za-z0-9]/, optional: true } } });
});
$(document).on('keydown', '.placa', function () {
	$(this).mask('AAAZZZZ', { translation: { 'A': { pattern: /[A-Za-z]/ }, 'Z': { pattern: /[A-Za-z0-9]/ } }, optional: true });
});


$('.moeda').mask("#.##0,00", {
	reverse: true
});


var maskCpfCnpj = function (val) {
	return val.replace(/\D/g, '').length < 12 ? '000.000.000-009999' : '00.000.000/0000-00';
},
ccOptions = {
	onKeyPress: function (val, e, field, options) {
		field.mask(maskCpfCnpj.apply({}, arguments), options);
	}
};

var maskCell = function (val) {
	return val.replace(/\D/g, '').length < 11 ? '(00) 0000-0000' : '(00) 00000-0000';
},
optionsCell = {
	onKeyPress: function (val, e, field, options) {
		field.mask(maskCell.apply({}, arguments), options);
	}
};