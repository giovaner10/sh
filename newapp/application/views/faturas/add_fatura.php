<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.js"></script>
<style type="text/css">
	.ui-autocomplete {
	    position: absolute;
	    z-index: 1000;
	    cursor: default;
	    padding: 0;
	    margin-top: 2px;
	    list-style: none;
	    background-color: #ffffff;
	    border: 1px solid #ccc;
	    -webkit-border-radius: 5px;
	       -moz-border-radius: 5px;
	            border-radius: 5px;
	    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	       -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	}
	.ui-autocomplete > li {
	  padding: 3px 20px;
	}
	.ui-autocomplete > li.ui-state-focus {
	  background-color: #DDD;
	}
	.ui-helper-hidden-accessible {
	  display: none;
	}
</style>

<h3>Nova Fatura</h3>
<hr>
<div class="well well-small">
	<div class="span2">
		<a href="<?php echo site_url('faturas/cancelar_sess')?>"
			class="btn btn-danger"><i class="icon-remove icon-white"></i>
			Cancelar Fatura</a>
	</div>
	<div class="span4">
		<div class="btn-group">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Ação
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="">Mesclar Faturas</a></li>
				<li><a href="">Enviar p/ Cliente</a></li>
			</ul>
		</div>
	</div>
	<div class="span4 input-append pull-right">
		<input type="text" name="filtro" class="span3"
			placeholder="Digite o código ou nome cliente" />
		<button type="submit" class="btn">
			<i class="icon-filter"></i> Filtrar
		</button>
	</div>
	<div class="clearfix"></div>
</div>

<?php echo form_open('faturas/add', '', array('add_fatura' => true))?>
<div class="row">
	<div class="span5">
		<div class="control-group">
			<div class="md-form">
			  	<label for="form-autocomplete" class="active"><b>Selecione um Cliente</b></label>
			  	<select class="js-example-basic-single span5" name="cliente" id="cliente" required <?= isset($fatura['id_cliente']) ? 'disabled' : '' ?>>
			  		<?php if (isset($fatura['id_cliente'])): ?>
			  			<option value="<?= $fatura['id_cliente'] ?>" selected="true">
			  				<?= $fatura['cliente']->nome ?>
			  			</option>
			  		<?php endif; ?>
				</select>
			</div>
		</div>
	</div>

	<div class="span3">
		<div class="control-group">
			<div class="md-form">
				<label for="form-autocomplete" class="active"><b>Selecione uma Secretaria</b></label>
				<select class="selecionar_secretaria span3" id="secretaria" name="secretaria" disabled <?= isset($fatura['id_secretaria']) ? 'disabled' : '' ?>>
					<option value="<?= isset($fatura['id_secretaria']) ? $fatura['id_secretaria'] : ''; ?>" selected="true">
						<?= $fatura['secretaria'] ?>
					</option>
				</select>
			</div>
		</div>
	</div>

	<div class="span3">
		<div class="control-group">
			<div class="md-form">
				<label for="form-autocomplete" class="active"><b>Selecione um Ticket</b></label>
				<select class="selecionar_ticket span3" id="id_ticket" name="id_ticket" disabled <?= isset($fatura['id_ticket']) ? 'disabled' : '' ?>>
					<option value="<?= isset($fatura['id_ticket']) ? $fatura['id_ticket'] : ''; ?>" selected="true">
						<?= $fatura['id_ticket'] ?>
					</option>
				</select>
			</div>
		</div>
	</div>
	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="dataFatura"><b>Data da Fatura:</b> </label>
			<div class="controls">
				<input type="text" class="span2" name="data_emissao" disabled
					value="<?= isset($fatura['data_emissao']) ? $fatura['data_emissao'] : date('d/m/Y', strtotime($data_emissao)); ?> " required />
			</div>
		</div>
	</div>

</div>

<div class="row">
	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="dataVencimento"><b>Data Vencimento:</b></label>
			<div class="controls">
				<input type="date" id="vencimento_fatura" class="span2" name="data_vencimento" value="<?= isset($fatura['data_vencimento']) ? $fatura['data_vencimento'] : ''?>" required <?php echo $fatura != false ? 'disabled' : '' ?> />
			</div>
		</div>
	</div>

	<div class="span3">
		<div class="control-group">
			<label class="control-label" for="formaPagamento"><b>Forma de Pagamento:</b> </label>
			<div class="controls">
				<select name="forma_pagamento" id="formaPagamento" <?php echo $fatura != false ? 'disabled' : ''?> required>
					<option value="">Escolha uma opção</option>
					<option value="1" <?php echo set_selecionado(1, $fatura['formapagamento_fatura'], 'selected')?>>Boleto</option>
					<option value="2" <?php echo set_selecionado(2, $fatura['formapagamento_fatura'], 'selected')?>>Cartão Crédito</option>
					<option value="3" <?php echo set_selecionado(3, $fatura['formapagamento_fatura'], 'selected')?>>Cartão Débito</option>
					<option value="4" <?php echo set_selecionado(4, $fatura['formapagamento_fatura'], 'selected')?>>Depósito/Transf. Bancária</option>
					<option value="5" <?php echo set_selecionado(5, $fatura['formapagamento_fatura'], 'selected')?>>Dinheiro</option>
				</select>
			</div>

		</div>
	</div>
	<div class="span3" style="width:100px">
		<div class="control-group">
			<label class="control-label" for="nota_fiscal"><b>Nº Nota fiscal:</b>
			</label>
			<div class="controls">
				<input type="text" class="span3" style="width:100px" <?php echo $fatura != false ? 'disabled' : ''?>  maxlength="10" name="nota_fiscal" value="<?= isset($fatura['nota_fiscal']) ? $fatura['nota_fiscal'] : '';?>"/>
			</div>

		</div>
	</div>

	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="mes_referencia"><b>Mês de referência:</b>
			</label>
			<div class="controls">
				<input type="text" id="mes_referencia" class="span2 ref" <?php echo $fatura != false ? 'disabled' : ''?>  name="mes_referencia" value="<?= isset($fatura['mes_referencia']) ? $fatura['mes_referencia'] : ''; ?>" />
			</div>
		</div>
	</div>

	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="periodo_inicial"><b>Início do período:</b>
			</label>
			<div class="controls">
				<input type="date" id="periodo_inicial" class="span2 input-small" <?php echo $fatura != false ? 'disabled' : ''?> name="periodo_inicial" value="<?= isset($fatura['periodo_inicial']) ? $fatura['periodo_inicial'] : '' ?>" />
			</div>
		</div>
	</div>

	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="periodo_final"><b>Fim do período:</b>
			</label>
			<div class="controls">
				<input type="date" id="periodo_final" <?php echo $fatura != false ? 'disabled' : ''?>  class="span2 input-small" name="periodo_final" value="<?= isset($fatura['periodo_final']) ? $fatura['periodo_final'] : '' ?>" />
			</div>
		</div>
	</div>

</div>
<?php if (!$fatura):?>
<div class="span6">
	<button type="submit" class="btn btn-success btn-large">
		<i class="icon-plus icon-white"></i> Add Itens
	</button>
</div>
<?php endif;?>
<?php echo form_close()?>

<?php if(count($fatura['itens']) > 0):?>
<div class="span4 offset6 well well-small">
	<div class="span2 pull-right"><?php echo count($fatura['itens'])?></div>
	<div class="span1">Qtd. Itens</div>
	<div class="span2 pull-right">R$ <?php echo number_format($this->fatura->sess_total_juros(),2, ',', '.')?></div>
	<div class="span1">Juros</div>
	<div class="span2 pull-right">R$ <?php echo number_format($this->fatura->sess_total_boleto(),2, ',', '.')?></div>
	<div class="span1">Boleto</div>
	<div class="span2 pull-right">R$ <?php echo number_format($this->fatura->sess_subtotal_fatura(),2, ',', '.')?></div>
	<div class="span1">Subtotal</div>
	<div class="span2 pull-right"><?=$fatura["cliente"]->PIS;?>%</div>
	<div class="span1">PIS</div>
	<div class="span2 pull-right"><?=$fatura["cliente"]->Cont_Social;?>%</div>
	<div class="span1" style="width: 120px;">Contribuição Social</div>
	<div class="span2 pull-right"><?= $fatura["cliente"]->IRPJ;?>%</div>
	<div class="span1">IRPJ</div>
	<div class="span2 pull-right"><?= $fatura["cliente"]->COFINS;?>%</div>
	<div class="span1">COFINS</div>
	<div class="span2 pull-right"><?= $fatura["cliente"]->ISS;?>%</div>
	<div class="span1">ISS</div>
	<div class="span2 pull-right">
		<b>R$ <?php echo number_format($this->fatura->sess_total_fatura()-($this->fatura->sess_total_fatura() * ($fatura["cliente"]->ISS / 100))-($this->fatura->sess_total_fatura() * ($fatura["cliente"]->COFINS / 100))-($this->fatura->sess_total_fatura() * ($fatura["cliente"]->IRPJ / 100))-($this->fatura->sess_total_fatura() * ($fatura["cliente"]->Cont_Social / 100))-($this->fatura->sess_total_fatura() * ($fatura["cliente"]->PIS / 100)),2, ',', '.')?></b>
	</div>
	<div class="span1">
		<b>Total</b>
	</div>
</div>
<div class="span2">
	<?php echo form_open('faturas/gerar_fatura', '', array('gerar_fatura' => true))?>
	<button type="submit" class="span2 btn btn-success btn-large"> Gerar Fatura</button><br>
	<?php echo form_close()?>
	<br> <a href="<?php echo site_url('faturas/cancelar_sess')?>" style="width: 140px" class="span2 btn btn-danger btn-large"><i
		class="icon-remove icon-white"></i> Cancelar</a>
</div>
<!-- fim infos fatura -->
<?php endif;?>


<?php if ($fatura):?>
<div class="span12">
	<fieldset>
		<legend>Itens da Fatura</legend>
		<div class="span7" style="margin-left: 0px !important">
			<b>Descrição</b>
		</div>
		<div class="span2">
			<b>Valor</b>
		</div>
		<div class="span1">
			<b>Taxa</b>
		</div>
		<div class="span1"></div>
	</fieldset>
	<br>
</div>
<?php echo form_open('', '', array('add_item' => true, 'vencimento_item' => $fatura['data_vencimento'],
								'tipo_item' => 'avulso', 'relid_item' => NULL, 'obs_item' => NULL))?>
<div class="span12"
	style="margin-bottom: 10px; border-bottom: 2px solid #F5F5F5">
	<div class="span7" style="margin-left: 0px !important">
		<textarea class="span7" name="descricao_item" placeholder="Digite a descrição do item" required></textarea>
	</div>
	<div class="span2">
		<input type="text" class="span2 valor" name="valor_item" placeholder="0,00" required />
	</div>
	<div class="span2">
		<input type="checkbox" name="taxa_item" value="sim" style="margin-top: -5px;" />
		&nbsp;&nbsp; <select name="tipotaxa_item" class="span1">
			<option value=""></option>
			<option value="juros">juros</option>
			<option value="boleto">boleto</option>
		</select>
	</div>
	<div class="span1">
		<button type="submit" class="btn btn-primary btn-mini">
			<i class="icon-plus icon-white"></i>
		</button>
	</div>
</div>
<?php echo form_close()?>
<div class="span12"
	style="overflow: auto; padding-top: 10px; max-height: 400px; margin-bottom: 15px;">
	<?php if (count($fatura['itens']) > 0):?>
		<?php foreach ($fatura['itens'] as $key => $item):?>
			<form method='post'>

				<input type="hidden" name="remover_item" value="1">
				<input type="hidden" name="index" value="<?=$key+1?>">
				<div class="span7" style="margin-left: 0px !important">
					<?php echo $item['descricao_item']?>
				</div>
				<div class="span2">
					R$ <?php echo $item['valor_item']?>
				</div>
				<div class="span1">
					<input type="checkbox" disabled name="taxa_item" <?php echo $item['taxa_item'] == 'sim' ? 'checked' : ''?>/> &nbsp;&nbsp;
				</div>
				<div class="span1">
					<button type="submit" title="Remover item" class="btn btn-danger btn-mini"><i
						class="icon-remove icon-white"></i> </button>
				</div>
				<div class="span11">
					<hr>
				</div>
			</form>
		<?php endforeach;?>
	<?php endif;?>
</div>
<?php endif;?>
<br>
<br>
<!--<script src="<?=base_url()?>assets/js/select2.js"/>-->
<script type="text/javascript">

	$(document).ready(function(){
		$('.data').mask('00/00/0000');
		$('.ref').mask('00/0000');
		$('.valor').mask("#.##0,00", {reverse: true});

		$('.js-example-basic-single').select2({
			ajax: {
				url: '<?= site_url('clientes/ajaxListSelect') ?>',
				dataType: 'json'
			},
			placeholder: "Selecione o cliente",
			allowClear: true
		});

		$('#cliente').change(function(){
			var id_cliente = $(this).val();
			 $.ajax({
				 url: '<?= site_url('clientes/get_ajax_secretaria') ?>'+'?id_cliente='+id_cliente,
				 dataType: 'json',
				 success: function(obj){
					 if (obj != null) {
						   var data = obj.results;
						   var selectbox = $('#secretaria');
						   // selectbox.find('option').remove();
						   $.each(data, function (i, d) {
							   //var id = d.id.split("-");
							   $('<option>').val(d.id).text(d.text).appendTo(selectbox);
						   });
					 }else{
		                // $('#mensagem').html('<span
		                // class="mensagem">Não foram encontradas
		                // secretaria!</span>');
		            }
				 }
			 });

			 //listar tickets
			 $.ajax({
				 url: '<?= site_url('webdesk/get_ajax_ticket') ?>/'+id_cliente,
				 dataType: 'json',
				 success: function(obj){
					 if (obj != null) {
						   var data = obj.results;
						   var selectbox = $('#id_ticket');
						   // selectbox.find('option').remove();
						   $.each(data, function (i, d) {
							   //var id = d.id.split("-");
							   $('<option>').val(d.id).text(d.id).appendTo(selectbox);
						   });
					 }
				 }
			 });

			 //remove o disabled de ticket e secretaria
			 window.setTimeout(function(){
				 document.getElementById('secretaria').disabled = false;
				 document.getElementById('id_ticket').disabled = false;
			 }, 3000);
	  })

		// $('.selecionar_os').on('click', function () {
		// 	 var id_cliente = document.getElementById("cliente").value;
		// 	$('.selecionar_os').select2({
		// 		ajax: {
		// 			url: '<?= site_url('servico/get_os_for_cliente') ?>'+'?id_cliente='+id_cliente,
		// 			dataType: 'json'
		// 		},
		// 		placeholder: "Selecione a OS",
	    // 		allowClear: true
		// 	});
		// });

	});

</script>
