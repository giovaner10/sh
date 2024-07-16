<style>
    .ag-theme-alpine .ag-ltr .ag-header-select-all {
        margin-right: 5px;
    }

    .centralizado {
        display: flex;
        justify-content: center;
    }

    .ag-theme-alpine .ag-ltr .ag-header-select-all {
        margin-left: 14px;
    }
</style>

<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('permissoes_gestor') ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?= lang('cadastro') ?> >
        <?= lang('permissoes_gestor') ?>
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
                    <a class='menu-interno-link' id="menu-produtos"><?= lang("produtos") ?></a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBusca">
				<div class="form-group filtro">
                    <div class="input-container">
						<label class="control-label" for="coluna">Tipo de busca:</label>
						<select class="form-control" name="coluna" id="coluna" style="width: 100%;">
							<option value="descricao" default>Descrição</option>
							<option value="codigo">Código</option>
                            <option value="modulo">Módulo</option>
                            <option value="tecnologia">Tecnologia</option>
						</select>
					</div>

                    <div class="input-container buscaDescricao">
						<label class="control-label" for="descricaoPermissao">Descrição:</label>
						<input type="text" class="form-control" name="descricaoPermissao" maxlength="45" id="descricaoPermissao" placeholder="Digite a descrição da permissão...">
					</div>

                    <div class="input-container buscaCodigo" style="display: none;">
						<label class="control-label" for="codigoPermissao">Código:</label>
						<input type="text" class="form-control" name="codigoPermissao" maxlength="45" id="codigoPermissao" placeholder="Digite o código da permissão...">
					</div>

                    <div class="input-container buscaModulo" style="display: none;">
						<label class="control-label" for="moduloPermissao">Módulo:</label>
						<select class="form-control" name="moduloPermissao" id="moduloPermissao" style="width: 100%;">
                            <?php
                                foreach ($modulos as $modulo) {
                                    echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                }
                            ?>
						</select>
					</div>

                    <div class="input-container buscaTecnologia" style="display: none;">
						<label class="control-label" for="tecnologiaPermissao">Tecnologia:</label>
						<select class="form-control" name="tecnologiaPermissao" id="tecnologiaPermissao" style="width: 100%;">
                            <?php
                                foreach ($tecnologias as $tecnologia) {
                                    echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                }
                            ?>
						</select>
					</div>

					<div class="button-container">
						<button class="btn btn btn-success" style='width:100%' id="BtnPesquisarPermissoes" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparPermissoes" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>

				</div>
			</form>
		</div>

        <div id="filtroBuscaProdutos" class="card" style="margin-bottom: 50px; display: none;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBuscaProdutos">
				<div class="form-group filtro">
                    <div class="input-container">
						<label class="control-label" for="colunaProdutos">Tipo de busca:</label>
						<select class="form-control" name="colunaProdutos" id="colunaProdutos" style="width: 100%;">
							<option value="nome" default>Nome</option>
                            <option value="descricao">Descrição</option>
							<option value="codigo">Código</option>
						</select>
					</div>

                    <div class="input-container buscaNomeProdutos">
						<label for="nomeProdutos">Nome:</label>
						<input type="text" class="form-control" name="nomeProdutos" maxlength="45" id="nomeProdutos" placeholder="Digite o nome do produto...">
					</div>

                    <div class="input-container buscaDescricaoProdutos" style="display: none;">
						<label class="control-label" for="descricaoProdutos">Descrição:</label>
						<input type="text" class="form-control" name="descricaoProdutos" maxlength="100" id="descricaoProdutos" placeholder="Digite a descrição do produto...">
					</div>

                    <div class="input-container buscaCodigoProdutos" style="display: none;">
						<label class="control-label" for="codigoProdutos">Código:</label>
						<input type="text" class="form-control" name="codigoProdutos" maxlength="100" id="codigoProdutos" placeholder="Digite o código do produto...">
					</div>

					<div class="button-container">
						<button class="btn btn btn-success" style='width:100%' id="BtnPesquisarProdutos" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparProdutos" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>

				</div>
			</form>
		</div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div id="card-permissoes" class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative;'>
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
        <div id="card-produtos" class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative; display: none;'>
			<h3>
				<b style="margin-bottom: 5px;"><?= lang('produtos') ?>: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="btnAddProdutos" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
						<button class="btn btn-primary" type="button" id="dropdownMenuButtonProdutos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonProdutos" id="opcoes_exportacao_produtos" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;margin-bottom: 5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-produtos" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <input class="form-control inputBusca" type="text" id="search-input-produtos" placeholder="Pesquisar" style="margin-bottom: 10px;">
            </div>
			<div style="position: relative;">
				<div class="wrapperProdutos">
					<div id="tableProdutos" class="ag-theme-alpine my-grid-produtos" style="height: 500px">
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<!-- Modal Cadastrar Permissao -->
<div id="modalCadPermissao" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPermissao">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?= lang('cadastrar_permissao') ?></h3>
                </div>

                <div class="modal-body row-fluid">
                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="permissao_nome" title="<?= lang('nome') ?>"><?= lang('nome') ?>:</label>
                        <input class="form-control" id="permissao_nome" name="permissao_nome" placeholder="Digite o nome..." required />
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="permissao_modulo" title="<?= lang('modulo') ?>"><?= lang('modulo') ?>:</label>
                        <select id="permissao_modulo" class="form-control" name="permissao_modulo" required>
                            <option value="" disabled selected hidden><?= lang('selecione_modulo') ?>...</option>
                            <?php
                            foreach ($modulos as $modulo) {
                                echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="permissao_tecnologia" title="<?= lang('tecnologia') ?>"><?= lang('tecnologia') ?>:</label>
                        <select id="permissao_tecnologia" class="form-control" name="permissao_tecnologia" required>
                            <option value="" disabled selected hidden><?= lang('selecione_tecnologia') ?>...</option>
                            <?php
                            foreach ($tecnologias as $tecnologia) {
                                echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('fechar') ?></button>
                        <button type="submit" id="submit_permissao" class="btn btn-success"><?= lang('salvar') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Permissao -->
<div id="modalEditPermissao" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPermissaoEdit">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title"><?= lang('editar_permissao') ?></h3>
                </div>

                <div class="modal-body row-fluid">

                    <input class="hidden" id="id_permissao_edit" name="id_permissao_edit" />
                    <input class="hidden" id="permissao_codigo_edit" name="permissao_codigo_edit" />

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" title="<?= lang('nome') ?>"><?= lang('nome') ?>:</label>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <input class="form-control" id="permissao_nome_edit" name="permissao_nome_edit" required />
                        <?php else : ?>
                            <input class="form-control" id="permissao_nome_edit" name="permissao_nome_edit" readonly required />
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" title="<?= lang('modulo') ?>"><?= lang('modulo') ?>:</label>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <select id="permissao_modulo_edit" class="form-control" name="permissao_modulo_edit" required>
                                <option value="" disabled selected hidden><?= lang('selecione_modulo') ?></option>
                                <?php
                                foreach ($modulos as $modulo) {
                                    echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                }
                                ?>
                            </select>
                        <?php else : ?>
                            <select id="permissao_modulo_edit" class="form-control" name="permissao_modulo_edit" disabled required>
                                <option value="" disabled selected hidden><?= lang('selecione_modulo') ?></option>
                                <?php
                                foreach ($modulos as $modulo) {
                                    echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                }
                                ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" title="<?= lang('tecnologia') ?>"><?= lang('tecnologia') ?>:</label>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <select id="permissao_tecnologia_edit" class="form-control" name="permissao_tecnologia_edit" required>
                                <option value="" disabled selected hidden><?= lang('selecione_tecnologia') ?></option>
                                <?php
                                foreach ($tecnologias as $tecnologia) {
                                    echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                }
                                ?>
                            </select>
                        <?php else : ?>
                            <select id="permissao_tecnologia_edit" class="form-control" name="permissao_tecnologia_edit" disabled required>
                                <option value="" disabled selected hidden><?= lang('selecione_tecnologia') ?></option>
                                <?php
                                foreach ($tecnologias as $tecnologia) {
                                    echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                }
                                ?>
                            </select>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('fechar') ?></button>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <button type="submit" id="submit_permissao_edit" class="btn btn-success"><?= lang('salvar') ?></button>
                        <?php else : ?>
                            <button type="submit" id="submit_permissao_edit" class="btn btn-success" disabled><?= lang('salvar') ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Cadastrar Produto -->
<div id="modalCadProduto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formProduto">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Cadastrar Produto</h3>
                </div>

                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle" style="padding: 10px 0px;">Dados</h4>
                    </div>
                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="produto_nome" title="Nome do Produto">Nome:</label>
                        <input type="text" id="produto_nome" name="produto_nome" placeholder="Digite o nome..." class="uppercase form-control" minlength="3" maxlength="45" required />
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="produto_descricao" title="Descrição">Descrição:</label>
                        <input type="text" class="form-control" id="produto_descricao" placeholder="Digite a descrição..." name="produto_descricao" maxlength="100" required/>
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="id_licenca" title="Licença"><?= lang('licenca') ?>:</label>
                        <select name="id_licenca" class="select_licencas form-control" id="id_licenca" disabled required></select>
                    </div>

                    <div class="col-md-12">
                        <h4 class="subtitle" style="padding: 10px 0px;">Permissões</h4>
                    </div>

                    <div class="col-md-12  input-container form-group">
                        <label for="id_licenca" title="Licença" style="margin-bottom: 20px;">Selecione na tabela abaixo as permissões do produto:</label>
                        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                            <div class="registrosDiv">
                                <label for="filter_modulo" class="label_input" style="font-weight:normal; margin-right: 5px;">Módulo</label>
                                <select id="filter_modulo" class="filter_produtos form-control">
                                    <option value="Todos">Todos</option> 
                                    <?php
                                        foreach ($modulos as $modulo) {
                                            echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="registrosDiv">
                                <label for="filter_tecnologia" class="label_input" style="font-weight:normal; margin-right: 5px;">Tecnologia</label>
                                <select id="filter_tecnologia" class="filter_produto form-control">
                                    <option value="Todos">Todos</option> 
                                    <?php
                                        foreach ($tecnologias as $tecnologia) {
                                            echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <input class="form-control inputBusca" type="text" id="search-input-permissoes-produto" placeholder="Pesquisar" style="margin-bottom: 10px; max-width: 250px !important;">
                        </div>
                        <div class="wrapperPermissoesProduto">
                            <div id="tablePermissoesProduto" class="ag-theme-alpine my-grid" style="height: 350px">
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-12  input-container form-group" style="max-height: 300px; overflow-y: auto;">
                        <label class="control-label" title="Permissões">Permissões:</label>
                        <table id="tabela_permissoes_produto" class="pull-left table table-bordered display">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="checked_all_permissoes" data-form="formProduto" /></th>
                                    <th>Permissão</th>
                                    <th>Módulo</th>
                                    <th>Tecnologia</th>
                                </tr>
                            </thead>
                        </table>
                    </div> -->

                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" id="submit_produto" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Produto -->
<div id="modalEditProduto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formProdutoEdit">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Editar Produto</h3>
                </div>

                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle" style="padding: 10px 0px;">Dados</h4>
                    </div>

                    <input class="hidden" id="id_produto_edit" name="id_produto_edit" />

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="produto_nome_edit" title="Nome do Produto">Nome:</label>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <input type="text" style="width:100%" id="produto_nome_edit" placeholder="Digite o nome..." name="produto_nome_edit" class="uppercase form-control" required minlength="3" maxlength="45"/>
                        <?php else : ?>
                            <input type="text" style="width:100%" id="produto_nome_edit" name="produto_nome_edit" class="uppercase form-control" required minlength="3" maxlength="45" readonly />
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="produto_descricao_edit" title="Descrição">Descrição:</label>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <input type="text" style="width:100%" id="produto_descricao_edit" class="form-control" placeholder="Digite a descrição..." name="produto_descricao_edit" maxlength="100" required/>
                        <?php else : ?>
                            <input type="text" style="width:100%" id="produto_descricao_edit" class="form-control" name="produto_descricao_edit" maxlength="100" readonly />
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" title="Licença"><?= lang('licenca') ?>:</label>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <select name="id_licenca_edit" class="select_licencas form-control" id="id_licenca_edit" required></select>
                        <?php else : ?>
                            <label style="margin-top: 5px; margin-left: 5px">Não possui permissão para editar a licença do produto.</label>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12">
                        <h4 class="subtitle" style="padding: 10px 0px;">Permissões</h4>
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <label title="Licença" style="margin-bottom: 20px;">Selecione na tabela abaixo as permissões do produto:</label>
                            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                                <div class="registrosDiv">
                                    <label for="filter_modulo_edit" class="label_input" style="font-weight:normal; margin-right: 5px;">Módulo</label>
                                    <select id="filter_modulo_edit" class="filter_produtos form-control">
                                        <option value="Todos">Todos</option> 
                                        <?php
                                            foreach ($modulos as $modulo) {
                                                echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="registrosDiv">
                                    <label for="filter_tecnologia_edit" class="label_input" style="font-weight:normal; margin-right: 5px;">Tecnologia</label>
                                    <select id="filter_tecnologia_edit" class="filter_produto form-control">
                                        <option value="Todos">Todos</option> 
                                        <?php
                                            foreach ($tecnologias as $tecnologia) {
                                                echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <input class="form-control inputBusca" type="text" id="search-input-permissoes-produto-edit" placeholder="Pesquisar" style="margin-bottom: 10px; max-width: 250px !important;">
                            </div>
                            <div class="wrapperPermissoesProdutoEdit">
                                <div id="tablePermissoesProdutoEdit" class="ag-theme-alpine my-grid" style="height: 350px">
                                </div>
                            </div>

                            <!-- <table id="tabela_permissoes_produto_edit" class="pull-left table table-bordered display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checked_all_permissoes_edit" data-form="formProdutoEdit" /></th>
                                        <th>Permissão</th>
                                        <th>Módulo</th>
                                        <th>Tecnologia</th>
                                    </tr>
                                </thead>
                            </table> -->
                        <?php else : ?>
                            <label style="margin-top: 5px; margin-left: 5px">Não possui permissão para editar as permissões do produto.</label>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('fechar') ?></button>
                        <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                            <button type="submit" id="submit_produto_edit" class="btn btn-success"><?= lang('salvar') ?></button>
                        <?php else : ?>
                            <button type="submit" id="submit_produto_edit" class="btn btn-success" disabled><?= lang('salvar') ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
	var Router = '<?= site_url('cadastros') ?>';
	var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/Produtos/Permissoes', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/Produtos/Permissoes', 'exportacoes.js') ?>"></script>