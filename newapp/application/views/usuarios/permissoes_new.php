<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('permissoes_funcionarios') ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?= lang('cadastro') ?> >
        <?= lang('permissoes_funcionarios') ?>
	</h4>
</div>

<div id="loading">
	<div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
	<div id="conteudo-lateral" class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link selected' id="menu-permissoes"><?= lang("permissoes") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link' id="menu-cargos"><?= lang("cargos") ?></a>
                </li>
            </ul>
        </div>

		<div id="filtroBusca" class="card" style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBusca">
				<div class="form-group filtro">
					<div class="input-container">
						<label for="coluna">Tipo de busca:</label>
						<select class="form-control" name="coluna" id="coluna" style="width: 100%;">
							<option value="nome" default>Nome</option>
							<option value="cod_permissao">Código</option>
						</select>
					</div>

                    <div class="input-container buscaNome">
						<label for="nomePermissao">Nome:</label>
						<input type="text" class="form-control" name="nomePermissao" maxlength="100" id="nomePermissao" placeholder="Digite o nome da permissão...">
					</div>

                    <div class="input-container buscaCodigo" style="display: none;">
						<label for="codPermissao">Código:</label>
						<input type="text" class="form-control" name="codPermissao" maxlength="45" id="codPermissao" placeholder="Digite o código da permissão...">
					</div>

					<div class="button-container">
						<button class="btn btn btn-success" style='width:100%' id="BtnPesquisarPermissoes" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparPermissoes" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>

				</div>
			</form>
		</div>
        <div id="filtroBuscaCargo" class="card" style="margin-bottom: 50px; display: none;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBuscaCargo">
				<div class="form-group filtro">

                    <div class="input-container">
						<label for="nomeCargo">Nome:</label>
						<input type="text" class="form-control" name="nomeCargo" maxlength="45" id="nomeCargo" placeholder="Digite o nome da permissão...">
					</div>

					<div class="button-container">
						<button class="btn btn btn-success" style='width:100%' id="BtnPesquisarCargos" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparCargos" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>

				</div>
			</form>
		</div>
	</div>

	<div class="col-md-9" id="conteudo">
		<div id="card-permissao" class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative;'>
			<h3>
				<b style="margin-bottom: 5px;"><?= lang('permissoes') ?>: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="btnAddPermissoes" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
						<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;margin-bottom: 5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <input class="form-control inputBusca" type="text" id="search-input" placeholder="Pesquisar" style="margin-bottom: 10px;">
            </div>
			<div style="position: relative;">
				<div class="wrapper">
					<div id="table" class="ag-theme-alpine my-grid-ordem" style="height: 500px">
					</div>
				</div>
			</div>
		</div>
        <div id="card-cargos" class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative; display: none;'>
			<h3>
				<b style="margin-bottom: 5px;"><?= lang('cargos') ?>: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="btnAddCargo" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
						<button class="btn btn-primary" type="button" id="dropdownMenuButtonCargo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonCargo" id="opcoes_exportacao_cargo" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;margin-bottom: 5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-cargo" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <input class="form-control inputBusca" type="text" id="search-input-cargo" placeholder="Pesquisar" style="margin-bottom: 10px;">
            </div>
			<div style="position: relative;">
				<div class="wrapperCargos">
					<div id="tableCargos" class="ag-theme-alpine my-grid-cargos" style="height: 500px">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Cadastrar Permissao -->
<div id="modalCadPermissao" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="tituloCadPermissao"><?= lang('cadastrar_permissao') ?></h3>
            </div>
            <form name="formPermissao" id="formPermissao">
                <div class="modal-body">
                    <div class="novaPermissao-alert alert" style="display:none; margin-bottom:-20px!important;">
                        <button type="button" class="close" onclick="fecharMensagem('novaPermissao-alert')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span id="msgNovaPermissao"></span>
                    </div>
                    <div class="col-md-12 input-container form-group">
                        <label for="prefixo" class="control-label"><?= lang('finalidade') ?>: </label>
                        <select class="form-control" name="prefixo" id="prefixo" style="width: 100%;" required>
                            <option value="out"><?= lang('outra') ?></option>
                            <option value="cad"><?= lang('cadastrar') ?></option>
                            <option value="vis"><?= lang('visualizar') ?></option>
                            <option value="edi"><?= lang('editar') ?></option>
                            <option value="rem"><?= lang('remover') ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 input-container form-group">
                        <label for="descricao" class="control-label"><?= lang('nome') ?>: </label>
                        <input type="text" class="form-control" placeholder="Digite o nome..." name="descricao" id="descricao" required>
                    </div>
                    <div class="col-md-12 input-container form-group divSeguradoraBlacklist" style="height: 59px !important;">
                        <label for="modulo" class="control-label"><?= lang('modulo') ?>: </label>
                        <select class="form-control" name="modulo" id="modulo" style="width: 100%;" required>
                            <?php 
                                foreach ($permissoes as $value) {
                                    $Nome = isset($value['lang']) ? lang($value['lang']) : $value['nomeModulo'];
                                    $Nome = $Nome != "" ? $Nome : $value['nomeModulo'];
                                    echo '<option value="'.$value['nomeModulo'].'">'.$Nome.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" id="btnNovaPermissao"><?= lang('salvar') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Cargo -->
<div id="modalCadCargo" class="modal fade" data-toggle="modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCargo">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="tituloNovoCargo"><?= lang('cadastrar_cargo') ?></h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 input-container form-group">
                        <label for="descricaoCargo" class="control-label"><?= lang('nome') ?>:</label>
                        <input type="text" placeholder="Digite o nome do cargo..." class="form-control" name="descricao" id="descricaoCargo" onkeyup="this.value = this.value.toUpperCase();" required>
                    </div>

                    <div class="col-md-12">
                        <span id='msgPermissoesCargo'></span>
                    </div>
                    <div class="col-md-12 input-container form-group">
                        <label for="permissoes" class="control-label" title="Permissões"><?= lang('selecione_permissoes_para_cargo'); ?>:</label>
                        <select id="permissoesCargo" name="permissoes[]" class="adt" multiple="multiple"></select>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" id="submit_cargo" data-acao="novo" class="btn btn-submit btn-success">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link href="<?php echo base_url('media') ?>/css/jquery.tree-multiselect.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.tree-multiselect-2.6.3.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/permissoes', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/usuarios/Permissoes', 'PermissoesShownet.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/usuarios/Permissoes', 'Exportacoes.js') ?>"></script>

<script>
	var Router = '<?= site_url('usuarios') ?>';
	var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
</script>