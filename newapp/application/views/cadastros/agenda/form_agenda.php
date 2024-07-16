<div id="msg" class="alert" style="display:none">
	<p></p>
</div>
<?php echo form_open(site_url('agendamento/ajax_save'), array('id' => 'save-agenda'))?>
<div class="row-fluid">
<div class="span6">
  <div class="control-group">
    <label class="control-label" for="placa">Placa/Prefixo</label>
    <div class="controls">
      <input type="text" id="placa" name="placa" data-provide="typeahead" data-items="7" data-source='<?php echo $placas?>' placeholder="Placa ou prefixo" required autocomplete="off">
    </div>
  </div>
 	
 	<div class="control-group">
	    <label class="control-label" for="hora">Hora</label>
	    <div class="controls">
	      <input type="text" class="hora" id="hora" name="hora" required placeholder="Horário">
	    </div>
  	</div> 
 
  <div class="control-group">
  		<label class="control-label" for="data">Enviar SMS<small><i>(opcional)</i></small></label>
    <div class="controls">
        <input type="text" name="fone_sms" placeholder="Ex.: 8399991111">
    </div>
  </div>
    
</div>

<div class="span6">
	
	<div class="control-group">
    <label class="control-label" for="data">Data</label>
    <div class="controls">
      <input
			type="text" name="data" class="datepick"
			placeholder="Data" autocomplete="off" id="data"
			required /> 
    </div>
  </div>
	
  	
  	
  	 <div class="control-group">
    <label class="control-label" for="data">Tipo do Serviço</label>
    <div class="controls">
      <select name="servico_agenda" required>
      		<option value="">Escolha um serviço</option>
      		<option value="1">Instalação</option>
      		<option value="2">Manutenção</option>
      		<option value="3">Remoção</option>
      </select>
    </div>
  </div>
  
  <div class="control-group">
    <label class="control-label" for="data">Mensagem SMS</label>
    <div class="controls">
      <select name="id_msg" required>
      		<option value="">Escolha uma mensagem</option>
      		<?php if (count($messages)):?>
      			<?php foreach ($messages as $msg):?>
      				<option value="<?php echo $msg->id_msg?>"><?php echo $msg->descricao?></option>
      			<?php endforeach;?>
      		<?php endif;?>
      </select>
    </div>
  </div>
  	
</div>
</div>
<?php echo form_close()?>

<script type="text/javascript" src="<?php echo base_url('assets')?>/plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/jquery.maskedinput-1.3.js"></script>

<script type="text/javascript">

$('.datepick').datepicker(
		{format: 'dd/mm/yyyy'
});

$('.hora').mask('99:99');
</script>
