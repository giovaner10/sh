<!-- <br style="clear:both" /> -->
	<?php //if($this->agent->is_mobile()):?>	
	<!-- <footer> -->
		<!-- <p>&copy;<?php //echo date('Y')?> Show Tecnologia - Todos os direitos reservados.</p> -->
	<!-- </footer> -->
	<?php //endif;?>
<!-- </div> fim container -->
<!-- <div id="confirmDiv" ></div>  -->
<?php //if(!$this->agent->is_mobile()):?>
<!-- <div class="navbar">
      <div class="navbar-inner">
        <div class="container">
          
        </div>
      </div>
    </div> -->

</div>

<div class="container-fluid">
	<div class="span12"><p style="text-align: center;" class="navbar-text">&copy;<?php echo date('Y')?> Show Tecnologia - Todos os direitos reservados.</p></div>
</div>
<?php //endif;?>


<!-- javascripts (melhora desempenho no final da página) -->
<script type="text/javascript" src="<?php echo base_url('media')?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/bootstrap-confirm.js"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/jquery.maskedinput-1.3.js"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/calendario.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	$('.datepicker').focus(function(){
	    $(this).calendario({target: $(this)});
	});

	$('.data').mask('99/99/9999');
	$('.tel').mask('(99) 9999-9999');
	$('.hora').mask('99:99:99');
	$('.cep').mask('99999-999');
	$('.cpf').mask('999.999.999-99');
	$('.placa').mask('aaa9999');
	$("#ajax").css('display', 'none');

	// $('a').click(function(){
	// 	if($(this).attr('href') != '#'){
	// 		$("#ajax").css('display', 'block');
	// 	}
	// });

	$(".funcao-apagar").click(function(){ //apagar conteudo
        var targetUrl = $(this).attr('data-controller');
        confirmaExclusao(targetUrl);

	});
	
});
	function confirmaExclusao(url){
		var url = url;
		$('#confirmDiv').confirmModal({
	    	heading:'EXCLUSÃO',
	    	body:'Tem certeza que deseja excluir o registro?',
	    	callback: function() {
	    		window.location.href = url;
	    	 }
	    });
	}
	function imprimir(){
	    window.print();
	
	}
</script>

</body>
</html>