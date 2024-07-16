function disableEnterKey(e) {
    var key;
    if (window.event)
        key = window.event.keyCode; //IE
    else
        key = e.which; //firefox      
    return (key != 13);
}
$(document).ready(function() {
	$('.dataJS').mask("99/99/9999");
	$(function() {
	    $('.dataJS').datepicker();
	});
	$(".dataJS").datepicker({
	    dateFormat: 'dd/mm/yy',
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    monthNames: ['Janeiro de','Fevereiro de','Março de','Abril de','Maio de','Junho de','Julho de','Agosto de','Setembro de','Outubro de','Novembro de','Dezembro de'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    nextText: 'Próximo',
	    prevText: 'Anterior'
	});
	var d = new Date();
	var data = ("0" + d.getDate()).substr(-2) + "/" + ("0" + (d.getMonth() + 1)).substr(-2) + "/" + d.getFullYear();
	$('.dataJS').val(data);
	$(document).on('click', '#data5', function() {
		$('#form1').fadeIn(1000);
	});
	$(document).on('click', '#data6', function() {
		$('#form2').fadeIn(1000);
	});
	$(document).on('click', '#salvar_1', function() {
		var dataDigi = $('#dataRetorno_1').val();
		var dataChegada = $('#datahidden1').val();
		if(moment(dataDigi.split('/').reverse().join('-')).isBefore(dataChegada.split('/').reverse().join('-'))){
			$('#dataRetorno_1').val("");
			alert("A data informada está inválida!\nA data de retorno do equimaneto antigo não pode ser anterior a data de instalação ou chegada do equimaneto novo.");
		}
	});
	$(document).on('click', '#salvar_2', function() {
		var dataDigi = $('#dataRetorno_2').val();
		var dataChegada = $('#datahidden2').val();
		if(moment(dataDigi.split('/').reverse().join('-')).isBefore(dataChegada.split('/').reverse().join('-'))){
			$('#dataRetorno_2').val("");
			alert("A data informada está inválida!\nA data de confimação da chegado do equimaneto antigo não pode ser anterior a data de envio do mesmo.");
		}
	});
	$('#okay').click(function() {
		$("#mensagem").slideUp(500);
		$.getJSON('gerencia_equipamentos/unsetSessionData', function(data) {});
	});
});