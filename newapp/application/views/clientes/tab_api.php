<div class="row-fluid">
	<strong>Chave API:</strong> <?=$chave ? $chave : '<span id="chave"><a href="'.site_url('clientes/gerar_chave_api/'.$id_cliente).'" 
									class="btn btn-primary btn-small" id="gerarChave"> Gerar chave</a></span>'?>
</div>

<script type="text/javascript">
	$("#gerarChave").click(function(ev){
		ev.preventDefault();
		$(this).text("Gerando Chave...");
		var url = $(this).attr("href");

		$.get(url, function(data){
			$("#chave").text(data);
		});
	});
</script>