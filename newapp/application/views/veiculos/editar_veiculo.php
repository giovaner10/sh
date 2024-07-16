<form method="post" name="editarVeiculo" id="editarVeiculo" enctype="multipart/form-data" action="<?php echo site_url('veiculos/veiculo_editar/'.$placa)?>">

<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>

<div class="result">
	
</div>

<h4>Placa - <?php echo $placa ?></h4>

	<?php if ($seriais):?>

		<div class="row-fluid">
			<div class="span6">
				<div class="control-group">
					<label>Veículo:</label>
					<input type="text" name="veiculo" placeholder="Veículo" required />
				</div>
			</div>
		</div>	
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group">
					<label>Prefixo:</label>
					<input type="text" name="prefixo" placeholder="Prefixo" required />
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span3">
				<div class="control-group">
					<label>Táxi:</label>
					<select name="taxi" id="taxi" required>
						<option value="">Selecione</option>
						<option value="1">Sim</option>
						<option value="0">Não</option>	
					</select>
				</div>
			</div>
		</div>


	<?php else:?>

		<div class="alert">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Atenção!</strong> <?php echo $placa ?> não vinculada a nenhum veículo.
		</div>

	<?php endif;?>

	<div class="row-fluid">
	<div class="form-actions">
		<input type="submit" id="enviar" class="btn btn-primary btn"/>
		</button>
		<a onclick="fecharModal('#modal_editar');" class="btn fechar">Fechar</a>
	</div>
</div>


</form>



<script type="text/javascript">

$(document).ready(function(){

    $(".result").hide();
    $("#editarVeiculo").ajaxForm({
        target: '.result',
        dataType: 'json',
        success: function(retorno){
            $(".result").html(retorno.mensagem);
            $(".result").show();
            $("#editarVeiculo").resetForm();
           
        }
        

    });


});


</script>




