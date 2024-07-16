<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>

<div class="alert alert-block">
  <button id="atencao" type="button" class="close" data-dismiss="alert">&times;</button>
  <h4>Olá!</h4>
  Tem certeza que quer desvincular serial <b><?php echo $serial ?></b> da placa <b><?php echo $placa ?></b>?
  Se "<b>SIM</b>"" click em <b>confirmar</b>, se "<b>NÂO</b>"" click em <b>cancelar</b>.
</div>

<div class="row-fluid">
	<div class="form-actions">
		<a href="<?php echo site_url('veiculos/desvincular_confirmado/'.$serial.'/'.$placa)?>"
			class="btn btn-primary"> 
			Confirmar
		</a>
		<a onclick="fecharModal('#modal_desvincular');" class="btn fechar">Cancelar</a>
	</div>
</div>

