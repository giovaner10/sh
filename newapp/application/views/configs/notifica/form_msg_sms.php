<div id="msg" class="alert" style="display:none">
	<p></p>
</div>
<?php echo form_open(site_url($controller_form), array('id' => 'form-save'), array('tipo' => 1))?>
<div class="row-fluid">
	<div class="span12">
	  <div class="control-group">
	    <label class="control-label" for="desc">Descrição</label>
	    <div class="controls">
	      <input type="text" class="span12" id="desc" value="<?php echo isset($message) ? $message->descricao : ''?>" name="descricao" placeholder="Digite uma descrição para a mensagem" required />
	    </div>
	  </div>

	  <div class="control-group">
	  		<label class="control-label" for="data">Mensagem</label>
	    <div class="controls">
	      
	        <textarea rows="3" class="span12" name="mensagem"><?php echo isset($message) ? $message->mensagem : ''?></textarea>
	      
	      
	    </div>
	  </div>
	</div>

</div>
<div class="row-fluid">
	<div class="alert alert-info">
		<p><i><strong>Use os itens abaixo para indicar valores personalizados na mensagem:</strong></i></p>
		<p>
			Ex.: {dd/mm} - Exibe 15/03<br>
			
			<span class="label label-info">{dd/mm} - dia/mês</span>
			<span class="label label-info">{hh:mm} - hora:minuto</span>
			<span class="label label-info">{placa} - MMM-1111</span>
			<span class="label label-info">{prefixo} - 1234</span>
			
		</p>
		
	</div>

</div>

<?php echo form_close()?>

