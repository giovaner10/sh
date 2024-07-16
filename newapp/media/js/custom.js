$(document).ready(function(){
	
	$(".upload-file").on('click', function(e){
	    e.preventDefault();
	    $("#upload:hidden").trigger('click');
	});
	
	// abre modal para atualizar data emissao
	$('.data-emissao').click(function(){
		
		var url = $(this).attr('data-controller');
		
		$('#update_emissao').modal({
			show: true,
			keyboard: true,
			remote: url
		});
	});
	
	$('#envia_fatura').on('hidden', function(){
		$(this).data('modal').$element.removeData();
	});
	
});


/*
 * fecha modal
 */
function fecharModal(modal) {
	$(modal).modal('hide');
	$(modal).data('modal').$element.removeData();
	return false;
}

