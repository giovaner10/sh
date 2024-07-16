<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>
<div class="formulario" style="float: none; margin-left: auto; margin-right: auto;">
<form method="post" name="formcadastrar" id="formcadastrar" enctype="multipart/form-data" action="<?php echo site_url('equipamentos/adicionar')?>">
	<div class="modal-body">
		<div class="form-group">
		  <label for="nome"><span class="label label-inverse">Serial</span></label>
		  <input type="text" class="form-control" oninput="handleInput(event)" name="equipamento[serial]" id="serial" placeholder="Serial" required> 
		</div>
		<div class="form-group">
		  <label for="email"><span class="label label-inverse">Marca</span></label>
		  <input type="text" class="form-control" name="equipamento[marca]" id="marca" placeholder="Marca" required>
		</div>
		<div class="form-group">
		  <label for="senha"><span class="label label-inverse">Modelo</span></label>
		  <input type="text" class="form-control" oninput="handleInput(event)" name="equipamento[modelo]" id="modelo" placeholder="Modelo" required>
		</div>
		<div class="form-group">
		  <label for="senha"><span class="label label-inverse">Linha</span></label>
		  <input type="text" class="form-control" name="equipamento[linha]" id="linha" placeholder="Linha" data-provide="typeahead" 
		  autocomplete="off" data-items="6" data-source='<?php echo $j_linhas ?>'>
		</div>
		<div class="row-fluid">
			<div class="form-actions modal-footer">
				<button type="reset" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
				<input type="submit" id="enviar" value="Cadastrar" class="btn btn-success"/>
			</div>
		</div>
	</div>
  </form>
</div>

<script>
function handleInput(e) {
   var ss = e.target.selectionStart;
   var se = e.target.selectionEnd;
   e.target.value = e.target.value.toUpperCase();
   e.target.selectionStart = ss;
   e.target.selectionEnd = se;
}
</script>
