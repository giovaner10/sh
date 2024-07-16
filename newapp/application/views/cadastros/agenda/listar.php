<h3>Agendamento de Serviços</h3>
<hr>
<div class="row-fluid">
	<div class="well well-small" style="height: 70px">
		<?php echo form_open('', array('id' => 'form-sms'))?>		
		<div class="control-group">
			<div class="col-sm-3">
				<input type="text" name="keyword" class="form-control"	placeholder="Digite uma busca (opcional)"	value="<?php echo $this->input->post('keyword') ?>" />
			</div>
		</div>
		<div class="control-group">
			<div class="col-sm-3">
				<div class="input-group input-group-sm">
    				<span class="input-group-addon">
    					<span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
    				</span> 
    				<select class="form-control" name="tipo">
        				<option value="" <?php echo set_select('tipo', '')?>> Buscar por</option>
        				<option value="placa" <?php echo set_select('tipo', 'placa')?>> Placa</option>
        				<option value="prefixo" <?php echo set_select('tipo', 'prefixo')?>> Prefixo</option>
        			</select>
    			</div>
			</div>
		</div>
		<div class="control-group">
			<div class="col-sm-2">
				<div class="input-group input-group-sm">
    				<span class="input-group-addon">
    					<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
    				</span> 
    				<input type="text" name="dt_ini" class="form-control datepicker" placeholder="Data Início" autocomplete="off" id="dp1"	value="<?php echo $this->input->post('dt_ini') ?>" required /> 
				</div>
			</div>
		</div>
		<div class="control-group">
			<div class="col-sm-2">
				<div class="input-group input-group-sm">
    				<span class="input-group-addon">
    					<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
    				</span> 
    				<input	type="text" name="dt_fim" class="form-control datepicker" 	placeholder="Data Fim" autocomplete="off" id="dp2"	value="<?php echo $this->input->post('dt_fim') ?>" required />
				</div>
			</div>
		</div>
		<div class="span2">
			<button class="btn btn-primary" id="sendForm">
				<i class="fa fa-search"></i> Filtrar
			</button>
			<a href="<?php echo site_url('agendamento/ajax_form_add')?>" class="btn btn-success" title="Adicionar agendamento" data-toggle="modal" data-target="#add-agendamento"><i class="fa fa-plus"></i></a>
			
			<?php if($this->input->post()):?>			
				<a href="<?php echo site_url('agendamento/imprimir/'.data_for_unix($this->input->post('dt_ini')).'/'.data_for_unix($this->input->post('dt_fim')))?>" class="btn"
				title="Imprimir" target="_blank"> <i class="icon-print"></i> 
			</a>
			<?php endif;?>
		</div>
	<?php echo form_close()?>
	
	</div>
</div>
<table class="table">
	<thead>
	  <tr>
	    <th class="span1">#</th>
	    <th>Prefixo</th>
	    <th>Placa</th>
	    <th>Data</th>
	    <th>Serviço</th>
	    <th>Administrar</th>
	  </tr>
	</thead>
	<tbody>
	<?php if (count($agendados)):?>
		<?php foreach ($agendados as $agenda):?>
			  <tr>
			    <td><?php echo $agenda->id_agenda?></td>
			    <td><?php echo $agenda->prefixo?></td>
			    <td><?php echo $agenda->placa?></td>
			    <td><?php echo dh_for_humans($agenda->data_agenda)?></td>
			    <td>
			    	<?php switch ($agenda->servico_agenda){
			    		case 1:
			    			$servico = 'Instalação';
			    			break;
			    		case 2:
			    			$servico = 'Manutenção';
			    			break;
			    		case 3:
			    			$servico = 'Remoção';
			    			break;
			    		default:
			    			$servico = 'Não informado';
			    	}
			    		echo $servico?>
			    </td>
			    <td></td>
			  </tr>
		<?php endforeach;?>
	<?php endif;?>
  	</tbody>
</table>
<div class="pagination">
	<?php echo $this->pagination->create_links()?>
</div>
<!-- Modal -->
<div id="add-agendamento" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Novo Agendamento</h4>
			</div>
			<div class="modal-body">
				<p>Carregando formulário</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button class="btn btn-primary" id="btn-add">Salvar</button>
			</div>
		</div>
	</div>
<div id="add-agendamento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Novo Agendamento</h3>
  </div>
  <div class="modal-body">
    <p>Carregando formulário</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
    <button class="btn btn-primary" id="btn-add">Salvar</button>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets')?>/js/modules/agenda.js"></script>
<script type="text/javascript">
jQuery(function(){
	var agenda = new Agenda();
	agenda.init();
});
</script>