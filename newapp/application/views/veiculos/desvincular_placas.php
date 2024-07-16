<?php

	//$placa = explode(",", $placas);
	$cont = 0;
	if ($placa) {
		$cont = count($placa);
	}


 ?>

<h3>Desvincular Placas</h3>

<hr class="featurette-divider">

<div class="well well-small">


</div>

<br style="clear:both" />

<div class="span9" style="float: none; margin-left: auto; margin-right: auto;">

    <div class="alert alert-block">
	  <button id="atencao" type="button" class="close" data-dismiss="alert">&times;</button>
	  <h4>Atenção!</h4>
	  Pra desvincular corretamente, analise e indentifique a(s) placa(s) que não deveriam estar vinculadas ao serial <b><?php echo $serial ?></b>.
	</div>

	<?php
		if ($retorno){
	        echo $retorno;
		}
    ?>

    <div class="row-fluid">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="span3">Serial</th>
					<th class="span3">Placa</th>
					<th>Ação</th>
				</tr>
			</thead>
			<tbody>
				<?php for ($i = 0; $i < $cont; $i++): ?>
					<tr>
						<td><span class="label label-inverse"><?php echo $serial ?></span></td>
						<td><?php echo $placa[$i] ?></span></td>
						<td>
						<button data-serial="<?php echo $serial ?>" data-placa="<?php echo $placa[$i] ?>" class="btn btn-primary desvincular_confirmar"
                        	title="Desvincular Veículo"> <i class="icon-plus icon-white"></i>Desvincular</button>
						</td>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>

</div>

<div id="modal_desvincular" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 id="myModalLabel1">Desvincular Placa</h4>
            </div>
            <div class="modal-body">
                <div style="background-color: #f5f5f5; padding: 10px;">
                    <div id="desvincular"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).on('click', '.desvincular_confirmar', function(e) {
		e.preventDefault();
		var botao = $(this);
		var serial = botao.attr('data-serial');
		var placa = botao.attr('data-placa');
		$("#modal_desvincular").modal();
		var href = "<?= site_url('veiculos/desvincular_confirmar/') ?>/" + serial + "/" + placa;

		carregarViewDesvincular(serial, href);
	});

	function carregarViewDesvincular(serial, href) {
		$('#desvincular').html('<p>Carregando...</p>');
		$.ajax({
			url: href,
			dataType: 'html',
			success: function(html) {
				if (serial) {
					$('#desvincular').html(html);
					setTimeout(function() {
						carregarViewDesvincular(serial, href);
					}, 60000000);
				} else {
					html = '<p>Veiculo sem informação de posição</p>';
					$('#posicaoveic').html(html);
				}
			}
		});
	}
</script>