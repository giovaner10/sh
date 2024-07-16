<h3>Mensagens personalizadas SMS</h3>
<hr>
<div class="row-fluid">
	<div class="well well-small" style="height: 30px">
		<?php echo form_open('', array('id' => 'form-sms'))?>
		<div class="span6 input-prepend input-append">
			<input type="text" name="keyword" class="span12"
			placeholder="Digite uma busca (opcional)"
			value="<?php echo $this->input->post('keyword') ?>" />
					
			
		</div>

		<div class="span2">
			<button class="btn btn-primary" id="sendForm">
				<i class="fa fa-search"></i> Filtrar
			</button>
			<a href="<?php echo site_url('configuracoes/form_msg_sms')?>" class="btn btn-success" title="Adicionar Mensagem" data-toggle="modal" data-target="#add-msg-sms"><i class="fa fa-plus"></i></a>
				<a href="javascript:window.print();" class="btn"
				title="Imprimir"> <i class="icon-print"></i> 
			</a>
		</div>
	<?php echo form_close()?>
	
	</div>
</div>


<table class="table">
	<thead>
	  <tr>
	    <th class="span1">#</th>
	    <th class="span4">Descrição</th>
	    <th class="span4">Mensagem</th>
	    <th class="span3">Administrar</th>
	  </tr>
	</thead>
	<tbody>
	<?php if (count($mensagens)):?>
		<?php foreach ($mensagens as $msg):?>
			  <tr>
			    <td><?php echo $msg->id_msg?></td>
			    <td><?php echo $msg->descricao?></td>
			    <td><?php echo $msg->mensagem?></td>
			    <td>
			    	<a href="<?php echo site_url('configuracoes/form_msg_sms/'.$msg->id_msg)?>"
			    		data-toggle="modal" data-target="#edit-msg-sms" class="btn btn-primary btn-small botao-edit">
			    		<i class="fa fa-edit"></i>
			    	</a>
			    </td>
			  </tr>
		<?php endforeach;?>
	<?php endif;?>
  	</tbody>
</table>
<div class="pagination">
	<?php echo $this->pagination->create_links()?>
</div>


<!-- Modal -->
<div id="add-msg-sms" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Mensagem personalizada SMS</h3>
  </div>
  <div class="modal-body">
    <p>Carregando formulário</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
    <button class="btn btn-primary" id="btn-add">Salvar</button>
  </div>
</div>

<div id="edit-msg-sms" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Mensagem personalizada SMS</h3>
  </div>
  <div class="modal-body">
    <p>Carregando formulário</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
    <button class="btn btn-primary" id="btn-edit">Salvar</button>
  </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets')?>/js/modules/configs.js"></script>
<script type="text/javascript">

jQuery(function(){
	var baseURL = '<?php echo base_url()?>';
	var configs = new Configs(baseURL);
	configs.init();
	configs.saveMessage();
	configs.editMessage();
});

</script>
