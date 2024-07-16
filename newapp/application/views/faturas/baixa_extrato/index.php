<style>
	.input_file {
		width: 90%!important;
		float: left;
		margin-right: 5px;
	}
	.input_select {
		width: 90%!important;
		float: left;
		margin-left: 5px;
	}
	.semPaddingLeft {
		padding-left: 0px!important;
	}
	.esconde {
		display: none;
	}
	.icon_gerar {
		font-size: 22px; 
		float: left; padding-top: 5px;
	}
	.dataTables_filter, .dataTables_length {
		margin-bottom: 0px!important;
	}
    
</style>

<div class="row">
	<div class="col-md-12">
	<h3><?=lang('pagamentos')?></h3>
	
	<div class="well well-small col-md-12">
		<form id="formPagamentos">
			<div class="col-md-3">
				<i class="fa fa-briefcase icon_gerar"></i>
				<select name="empresa" id="empresa" class="form-control input_select" required >
					<option value="" selected disabled><?=lang('select_prestadora')?></option>
					<option value="0">Show Tecnologia</option>
					<option value="1">Norio Momoi</option>
					<option value="2">Show Tecnology</option>
				</select>				
			</div>
			<div class="col-md-3">
				<i class="fa fa-university icon_gerar"></i>
				<select name="banco" id="banco" class="form-control input_select" required >
					<option value="" selected disabled><?=lang('select_banco')?></option>
					<option value="0"><?=lang('todos')?></option>
					<option value="1">Banco do Brasil</option>
					<option value="2">Caixa</option>
					<option value="3">Bradesco</option>
					<option value="4">PayPal</option>
				</select>
			</div>

			<div>
				
				<button type="submit" class="btn btn-primary btnFormPagamentos" >
					<?=lang('gerar') ?>
				</button>

			</div>

		</form>
	</div>




	
	<div class="" style="margin-top: 10px;">
		<table id="tabela_pagamentos" class="table table-bordered table-hover" style="width:100%">
			<thead>
				<tr>
					<th></th>
					<th>Id</th>
					<th><?= lang('conta_movimentacao') ?></th>
					<th><?= lang('data') ?></th>
					<th><?= lang('categoria') ?></th>
					<th><?= lang('historico') ?></th>
					<th><?= lang('codigo_transacao') ?></th>
					<th><?= lang('valor') ?></th>
					<th><?= lang('tipo') ?></th>
					<th><?= lang('densidade') ?></th>
					<th><?= lang('fatura') ?></th>
					<th><?= lang('data_baixa') ?></th>
					<th><?= lang('usuario') ?></th>
					<th>status</th>
					<th><?= lang('admin') ?></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<!-- modals -->
<div id="modalEditarPagamento" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h3 id="myModalLabel1"><?=lang('editar')?></h3>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="descricao_extrato"><?=lang('descricao')?></label>
					<textarea type="text" id="descricao_extrato" name="descricao" class="form-control" value="" style="width:100%;" disabled></textarea>
				</div>
				<div class="form-group col-md-6">
					<label for="cod_transacao_extrato"><?=lang('cod_transacao')?></label>
					<input type="text" id="cod_transacao_extrato" name="cod_transacao" class="form-control" value="" style="width:100%;" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="valor_extrato"><?=lang('valor')?></label>
					<input type="text" id="valor_extrato" name="valor" class="form-control" value="" style="width:100%;" disabled>
				</div>
				<div class="col-md-12">
					<h4 id="tipo_extrato"></h4>
					<button class="btn btn-primary esconde" id="btn_adicionar_vinculo" onclick="adicionar_vinculacao()" style="margin-bottom: 10px;"><i class="fa fa-plus"></i> <?=lang('adicionar_vinculacao')?></button>
					<button class="btn btn-danger esconde" id="btn_remover_vinculo"><i class="fa fa-remove"></i> <?=lang('remover_vinculacao')?></button>
				</div>
				<div class="col-md-12">
					<form id="form_vinc"></form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn_salvar_vinculacao" class="btn btn-primary"><?=lang('salvar')?></button>
			</div>
		</div>
	</div>
</div>


<!-- Modal vincular varios pagamentos a uma fatura-->
<div id="modal_fatura_multi_pagamento" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3><?=lang('baixa_por_multi_pagamentos')?></h3>
            </div>
			<form id="formMultiPagamentos">
				<div class="modal-body">
					<div class="row">
						<label class="alert alert-info col-md-12"><?=lang('msg_informe_fatura_para_vinculo')?></label>
						<div class="form-group col-md-12">
							<label><?=lang('id_fatura')?>:</label>
							<input type="number" class="form-control" name="fatura_id" id="fatura_id" placeholder="Ex.: 1234567" value="">
							<input type="hidden" class="form-control" name="extratos" id="extratos" value="">
						</div>
					</div>                
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="btnConfirmarMultiPagamento"><?=lang('salvar')?></button>
				</div>
			</form>
        </div>
    </div>
</div>


<!-- Modal Subir arquivo de extrato-->
<div id="modalSubirArquivo" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3><?=lang('subir_arquivo')?></h3>
            </div>	
			<form id="formFile" enctype="multipart/form-data" >
				<div class="modal-body">
					<div>
						<p class="alert alert-info"><?=lang('msg_extensao_files')?></p>
					</div>
					<div style="float: left;">
						<label style="width:100%"><?=lang('select_prestadora')?></label>
						<select name="empresa" class="form-control input_file" required >
							<option value="" selected disabled><?=lang('select_prestadora')?></option>
							<option value="0">Show Tecnologia</option>
							<option value="1">Norio Momoi</option>
							<option value="2">Show Tecnology</option>   
						</select>						
					</div>
					<div style="float: left;">
						<label style="width:100%"><?=lang('arquivo')?></label>
						<input id="file" name="file" type="file" class="form-control input_file" accept=".bbt, .txt, .CSV" required />
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="btnFormFile" style="margin-top: 30px;"><?=lang('salvar')?></button>
				</div>			
			</form>             
        </div>
    </div>
</div>





<script type="text/javascript">
    var tabela_pagamentos, page_pagamentos = false;
    var site_url = "<?= site_url() ?>";
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/modules', 'pagamentos.js') ?>"></script>
