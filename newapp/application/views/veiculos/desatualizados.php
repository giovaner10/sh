<h3>Veículos Desatualizados</h3>
	<?php if(!empty($msg)):?>
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $msg?>
		</div>
	<?php endif;?>

	<hr>

	<div class="well well-small well-action-bar">
	
		<div class="pull-left">
			<a href="#imprimir" class="btn btn-info" onclick="imprimir(); return false;"><i class="fa fa-print"></i> Imprimir</a>
			
		</div>

		<div class="pull-right">
			
			<?php echo form_open(site_url('veiculos/desatualizados'), array('class' => 'form-horizontal pull-right'))?>
				<input type="text" name="keyword" value="<?php echo $filter ? $filter['keyword'] : ''?>" placeholder="Busca">
				<select name="tipo_busca" style="width: inherit;">
					<option value="veiculo" <?php echo $filter ? set_selecionado('veiculo', $filter['tipo_busca'], 'selected') : ''?>>Veículo</option>
					<option value="placa" <?php echo $filter ? set_selecionado('placa', $filter['tipo_busca'], 'selected') : ''?>>Placa</option>
					<option value="serial" <?php echo $filter ? set_selecionado('serial', $filter['tipo_busca'], 'selected') : ''?>>Serial</option>
					<option value="user" <?php echo $filter ? set_selecionado('user', $filter['tipo_busca'], 'selected') : ''?>>Usuário</option>
				</select>
				<div class="input-prepend input-append">
					<span class="add-on">Mais de</span>
					<input type="number" name="horas" value="<?php echo $filter ? $filter['horas'] : '24'?>" class="span1" min="0" />
					<span class="add-on"> horas s/ atualizar</span>
				</div>
				
				<button type="submit" class="btn"><i class="icon-search"></i></button>
				<?php if ($filter):?>
				<a href="<?php echo site_url('veiculos/desatualizados/0/1')?>" title="Remover Filtro" class="btn btn-danger"><i class="icon-remove icon-white"></i></a>
				<?php endif;?>
			</form>
		</div>
	
		<div class="clearfix"></div>

	</div>
	
<div class="row-fluid" style="height: 75px">
	<div class="span6 hidden-print" style="margin-top: 20px">
		<!-- <a href="<?php echo site_url('veiculos/desatualizados/all')?>" class="btn btn-primary">
			<i class="icon-list icon-white"></i> Listar Todos
		</a> -->
		<span class="label label-info"><?php echo $total_veiculos?> registros encontrado(s)</span>
	</div>
	<div class="span6 pull-right">
		<div class="pagination pagination-right">
			<?php echo $this->pagination->create_links()?>
		</div>
	</div>
	
</div>
		
<table class="table table-hover table-bordered table-nowrap">
	<thead>
		<th class="span1">Usuário</th>
		<th class="span5">Cliente</th>
		<th class="span2">Veículo</th>
		<th class="span2">Placa</th>
		<th>Serial</th>
		<th>Última Atualização</th>
		<th>Manutenção</th>
	</thead>
	<?php $CI =& get_instance(); ?>

	<tbody>
		<?php if(count($veiculos)): ?>
			<?php foreach ($veiculos as $veiculo): ?>
				<tr id="<?php echo $veiculo->code ?>">
					
					<?php
						$dados = $CI->get_usuario_cliente($veiculo->placa, $veiculo->serial);
					?>

					<td>
						<a href="javascript:;" class="btn btn-mini btn-link popupover" data-html="true" data-content="
							<?php 
								if(is_array($dados) && count($dados) > 0) { 
									foreach($dados as $dado){ 
										echo $dado->usuario . "<br>"; 
									} 
								} 
							?>" 
							data-placement="right" data-toggle="popover" data-original-title="Usuarios"><i class="fa fa-plus"></i></a>
					</td>

					<td>
						<?php echo character_limiter($veiculo->cliente, 40)?>
					</td>

					<td><?php echo $veiculo->veiculo ?></td>
					<td><?php echo $veiculo->placa ?></td>
					<td><?php echo $veiculo->serial ?></td>
					<td>
						<?php if ($veiculo->ultima_atualizacao):?>
						<?php echo dh_for_humans($veiculo->ultima_atualizacao) ?>
						<br>
						<?php $time_up = round(diff_entre_datas($veiculo->ultima_atualizacao, date('Y-m-d H:i:s'), 'minutes') / 60)?>
						<span class="label <?php echo $time_up > 0 ? 'label-warning' : 'label-success'?>">
							<?php echo $time_up > 0 ? "{$time_up}h s/ atualizar" : 'Veículo Atualizado'?>
						</span>
						<?php else:?>
							<span class="label label-important">Veículo s/ infomações</span>
						<?php endif;?>
					</td>
					<td class="coluna-manutencao">
						<a href="#modal_manutencao" data-toggle="modal" data-id="<?php echo $veiculo->code ?>" class="btn btn-mini btn-primary btn-agendar-manutencao" title="Agendar Manutenção"><i class="fa fa-wrench"></i> Agendar</a>
					</td>
				</tr>
			<?php endforeach;?>
		<?php else: ?>
			<tr>
				<td colspan="100">Nenhum registro encontrado no momento!</td>
			</tr>
		<?php endif;?>
	</tbody>
</table>

<div class="pagination pagination-right">
	<?php echo $this->pagination->create_links()?>
</div>

<form action="<?php echo site_url('veiculos/agendar_manutencao') ?>" method="post" id="form-agendar-manutencao">
	<div id="modal_manutencao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Agendar Manutenção</h3>
		</div>
		<div class="modal-body" style="padding-left: 0; padding-right: 0">
			<input type="hidden" name="id" value="">
			<div class="container-fluid">
				<div class="alert alert-error hide">
					Não foi possível agendar a manutenção. Por favor, tente novamente!
				</div>
				<div class="row-fluid">
					<div class="span6">
						<label for="">Data da Manutenção</label>
						<input type="text" name="data" value="" class="input-block-level data datepicker">
					</div>
					<div class="span6">
						<label for="">Hora da Manutenção</label>
						<input type="text" name="hora" value="" class="input-block-level hora">
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<label for="">Observações</label>
						<textarea name="observacoes" rows="6" class="input-block-level"></textarea>
					</div>
				</div>
				<div class="row-fluid">
					<small>Obs: É muito importante colocar na observação, além das suas considerações, o local onde será realizada a manutenção.</small>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			<button type="submit" class="btn btn-primary salvar-manutencao"><img src="<?php echo base_url('media/img/ajax-button-primary.gif') ?>" class="hide"> Agendar</button>
		</div>
	</div>
</form>

<script>
	$(function(){

		$('.popupover').popover();

		$('.btn-agendar-manutencao').click(function(e){
			e.preventDefault();
			$('#modal_manutencao').find('input[name="id"]').val($(this).data('id'));
		});

		$('#form-agendar-manutencao').ajaxForm({
			dataType: 'json',
			beforeSubmit: function(){
				$('.salvar-manutencao').find('img').show();
			},
			success: function(response){

				if (response.success) {

					$('#'+response.id).find('.label-status').toggleClass('label-important label-warning').text('Manutenção');
					$('#'+response.id).find('.coluna-manutencao a').remove();
					$('#'+response.id).find('.coluna-manutencao').text($('#modal_manutencao').find('input[name="data"]').val() + ' ' + $('#modal_manutencao').find('input[name="hora"]').val());

					$('.salvar-manutencao').find('img').hide();
					$('#modal_manutencao').modal('hide');

				} else {
					$('#modal_manutencao').find('.alert').show();
				};

			}
		});

		$('#modal_manutencao').on('hidden', function(){
			$(this).find('.alert').hide();
			$(this).find('input[name=data]').val('');
			$(this).find('input[name=hora]').val('');
			$(this).find('textarea[name=observacoes]').val('');
		});

	});
</script>