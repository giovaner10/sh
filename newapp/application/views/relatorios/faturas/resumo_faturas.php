<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/js/modules/jquery.battatech.excelexport.js') ?>"></script>

<?php if($this->session->flashdata('sucesso')) { ?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>CONCLUIDO!</strong>
	<?php echo $this->session->flashdata('sucesso'); ?>
</div>
<?php } elseif ($this->session->flashdata('erro')) { ?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Erro!</strong>
	<?php echo $this->session->flashdata('erro'); ?>
</div>
<?php } ?>

<h3>Relatório Faturas</h3>
<hr>
<?php echo form_open('')?>
<div class="well well-small">
	<div class="input-group pull-left">
		<select class="js-example-basic-multiple span6" name="cliente[]" multiple="multiple">
		</select>
		<span class="input-group-addon" style="margin-left: 10px;"><i class="fa fa-calendar" style="font-size: 22px;"></i> </span>
		<input type="text" name="dt_ini" id="data3" minlength="7"
			value="<?= $this->input->post('dt_ini') ? $this->input->post('dt_ini') : date('m/Y') ?>" required>
		<span class="input-group-addon" style="margin-left: 10px;"><i class="fa fa-calendar" style="font-size: 22px;"></i> </span>
        <input type="text" name="dt_fim" id="data4" minlength="7"
               value="<?= $this->input->post('dt_fim') ? $this->input->post('dt_fim') : date('m/Y') ?>" required>
	</div>
	<div class="pull-right" style="margin-left: 20px;">
		<button type="submit" class="btn">
			<i class="icon-list-alt"></i> Gerar
		</button>
		<?php if($relatorio):?>
		<a href="javascript:window.print();" class="btn btn-primary"
			title="Imprimir"> <i class="icon-print icon-white"></i>
		</a>
		<button class="btn btn-primary" id="btnExport">Gerar Excel</button>
		<?php endif;?>
	</div>
	<div class="clearfix"></div>
    <div>
        <label class="radio inline"><input type="radio" name="status" value="ativos" checked>Ativos</label>
        <label class="radio inline"><input type="radio" name="status" value="0">Cadastrado</label>
    </div>
</div>
<?php echo form_close()?>
<?php if ($relatorio) { ?>
<div id="dv">
  	<table id="tblExport" class=" table table-condensed table-bordered">
		<thead>
		  	<tr>
		  		<th rowspan="2" style="vertical-align: middle; text-align: center;">#</th>
		  		<th rowspan="2" style="vertical-align: middle; text-align: center;">ID CLIENTE</th>
		  		<?php foreach ($relatorio[key($relatorio)]->meses as $ref) { ?>
			    	<th style="text-align: center;" colspan="3"><?= $ref['referencia']; ?></th>
			    <?php } ?>
		  	</tr>
		  	<tr>
		  		<?php foreach ($relatorio[key($relatorio)]->meses as $ref) { ?>
			  		<th>Faturado</th>
			  		<th>Pago</th>
			  		<th>Saldo</th>
		  		<?php } ?>
		  	</tr>
		</thead>
		<tbody>
			<?php $i = 1; foreach ($relatorio as $rel) { ?>
		  	<tr>
		  		<td><?= $i; ?></td>
		  		<td title="<?= $rel->id; ?>"><?= $rel->nome; ?></td>
		  		<?php $fatura_ant = 0;
		  		foreach ($rel->meses as $ref) { ?>
			  		
			  		<?php if ($ref['faturado']->valor_total < $fatura_ant) { ?>
			  			<td title="ANTENÇÃO!"><b>R$ <?= $ref['faturado']->valor_total ? $ref['faturado']->valor_total : '0.00' ?> <i class="fa fa-exclamation-triangle" style="color: red;"></i></b></td>
			  		<?php } else { ?>
			  			<td><b>R$ <?= $ref['faturado']->valor_total ? $ref['faturado']->valor_total : '0.00' ?></b></td>
			  		<?php } ?>
			  		
			  		<td>R$ <?= $ref['pago']->valor_pago ? $ref['pago']->valor_pago : '0.00' ?></td>

			  		<?php if (($ref['pago']->valor_pago - $ref['faturado']->valor_total) >= 0) { ?>
			  			<td style="color: green;"><b>R$ <?= ($ref['pago']->valor_pago - $ref['faturado']->valor_total); ?></b></td>
			  		<?php } else { ?>
			  			<td style="color: red;"><b>R$ <?= ($ref['pago']->valor_pago - $ref['faturado']->valor_total); ?></b></td>
			  		<?php } ?>
		  		<?php $fatura_ant = $ref['faturado']->valor_total; } ?>
		  	</tr>
		  	<?php $i++; } ?>
		</tbody>
	</table>
</div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
    	$('.js-example-basic-multiple').select2({
    		placeholder: "Selecione o(s) cliente(s)",
    		allowClear: true,
    		ajax: {
				url: '<?= site_url('clientes/ajaxListSelect') ?>',
				dataType: 'json'
			}
    	});

        $("#btnExport").click(function () {
            $("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });

        $("#data3").mask("99/9999");
        $("#data4").mask("99/9999");
    });
</script>