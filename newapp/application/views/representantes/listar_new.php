

<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('representantes') ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?= lang('cadastros') ?> >
        <?= lang('representantes') ?>
	</h4>
</div>

<div id="loading">
	<div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
	<div class="col-md-3">
		<div id="filtroBusca" class="card" style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBusca">
				<div class="form-group filtro">
					<div class="input-container">
						<label for="coluna">Tipo de busca:</label>
						<select class="form-control" name="coluna" id="coluna" style="width: 100%;">
							<option value="cidade" default>Cidade</option>
							<option value="estado">Estado</option>
							<option value="pais">País</option>
							<option value="nome">Nome</option>
							<option value="email">E-mail</option>
						</select>
					</div>

                    <div class="input-container buscaCliente">
						<label id="labelForValor" for="valor">Cidade:</label>
						<input type="text" class="form-control" name="valor" id="valor" placeholder="Digite a cidade...">
					</div>

					<div class="button-container">
						<button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>

				</div>
			</form>
		</div>
	</div>

	<div class="col-md-9" id="conteudo">
		<div class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative;'>
			<h3>
				<b style="margin-bottom: 5px;"><?= lang('representantes') ?>: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
					<button class="btn btn-primary" id="btnDigitalizarDocumento" title="Digitalizar Arquivos para Todos" type="button" style="margin-right: 10px;"><i class="fa fa-folder-open" aria-hidden="true"></i> Digitalizar Arquivos</button>
					<div class="dropdown" style="margin-right: 10px;">
						<button class="btn btn-primary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
			<div class="registrosDiv">
				<select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
					<option value="10" selected>10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
				<h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
			</div>
			<div style="position: relative;">
				<div class="wrapper">
					<div id="table" class="ag-theme-alpine my-grid-ordem" style="height: 500px">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modalEmailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="" id='formEmail'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleEmail">Editar E-mail Show</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <input type="text" hidden name='idRepresentante' id="idRepresentante" value='0' />
                        <div class='row'>
                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="emailShow">E-mail: <span class="text-danger">*</span></label>
                                <input type="email" name="emailShow" class="form-control" placeholder="Digite o e-mail..." id="emailShow" required maxlength="255"/>
                            </div>
						</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id='btnSalvarEmail'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalMaisInfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="titleEmail">Mais informações</h3>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<div style="position: relative;">
						<div class="wrapperInfo">
							<div id="tableInfo" class="ag-theme-alpine my-grid" style="height: 300px">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="footer-group">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				</div>
			</div>
        </div>
    </div>
</div>

<!-- Visualizar anexos -->
<div id="modalVisualizarAnexos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="titulo-digitalizar-arquivos">Digitalizar Arquivos</h3>
			</div>
			<div class="modal-body">
				<form id="formUploadAnexo">
					<div class="col-md-12">
						<h4 class="subtitle" style="padding: 10px 0px;">
							Enviar arquivo:
						</h4>
						<input type="hidden" id="id_representante_anexo" name="id_representante_anexo" value="000">
					</div>
					<div class="col-md-6 input-container form-group">
						<label for="descricao">Descrição: <span class="text-danger">*</span></label>
						<input class="form-control" maxlength="100" name="descricao" id="descricao" placeholder="Digite a descrição..." type="text" style="width: 100%;" required>
					</div>
					<div class="col-md-6 input-container form-group">
						<label for="arquivo">Arquivo: <span class="text-danger">*</span></label>
						<input type="file" class="form-control" name="arquivo" required id="arquivo" formenctype="multipart/form-data" accept="application/pdf, image/jpeg, image/png, image/gif">
						<span id="fileSizeError" style="color: red;font-size: 12px;padding: 0 10px; display: none;">O tamanho do arquivo não pode exceder 2MB.</span>
					</div>
					<div class="col-md-12" style="display: flex; justify-content: space-between;">
						<div>
							Formatos: 
							<span class="label label-danger">.pdf</span>
							<span class="label label-warning ">.jpg</span>
							<span class="label label-success ">.png</span>
							<span class="label label-info ">.jpeg</span>
							<span class="label label-primary ">.gif</span>
						</div>
						<div>
							<button type="button" class="btn btn-default" id="btnLimparArquivo" style="border-radius: 5px; margin-right: 10px; margin-bottom: 10px;">Limpar</button>
							<button type="submit" class="btn btn-primary" id="sendAnexo" style="border-radius: 5px; margin-bottom: 10px;">Enviar</button>
						</div>
					</div>
				</form>
				<div class="col-md-12" style="margin-bottom: 30px;">
					<h4 class="subtitle" style="padding: 10px 0px;">
						Arquivos enviados:
					</h4>
					<div style="position: relative;">
						<div class="wrapperAnexos">
							<div id="tableAnexos" class="ag-theme-alpine my-grid" style="height: 300px">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="footer-group">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/representantes/listar', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/representantes/listar', 'Exportacoes.js') ?>"></script>

<script>
	var Router = '<?= site_url('representantes') ?>';
	var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
</script>