<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('pcp_iscas') ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?= lang('pcp') ?> > Iscas >
		<?= lang('pcp_iscas') ?>
	</h4>
</div>


<div id="loading">
	<div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
	<div class="col-md-3">
		<div id="filtroBusca" class="card" style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important;">Verificar Serial:</h4>

			<form id="formBusca">
				<div class="form-group filtro">

					<div class="input-container buscaSerial">
						<label for="serialBusca">Serial Isca:</label>
						<input type="text" name="serialBusca" class="form-control" placeholder="Digite o Serial..." id="serialBusca" />
					</div>

					<div class="input-container resultadoBuscaSerial" style="  overflow-x: hidden; overflow-y: auto;"></div>

					<div class="button-container">
						<button class="btn btn-primary" style='width:100%; margin-bottom: 25px; display:none' id="btnVisualizarEmTela" type="button"><i class="fa fa-eye" aria-hidden="true" hide="true"></i> Visualizar em Tela</button>
						<button class="btn btn-success" style='width:100%; margin-top: 5px' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="card-conteudo card-iscas" style="margin-bottom: 20px;">
		<h3>
			<b style="margin-bottom: 5px;"><?= lang("pcp_iscas") ?>: </b>
			<div class="btn-div-responsive" id="btn-div-hot-list">
				<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
					<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonImportacao" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
						<?= lang('importar') ?> <span class="caret" style="margin-left: 5px;"></span>
					</button>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-acoes opcoes_importacao dropdown-menu-importacao" aria-labelledby="dropdownMenuButton" id="opcoes_importacao" style="width: 180px; height: 100px;">
						<h5 class="dropdown-title"> O que você deseja fazer? </h5>
						<div class="dropdown-item opcao_importacao" id="BtnImportarArquivo">
							<i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i> Importar arquivo
						</div>
						<div class="dropdown-item opcao_importacao" id="BtnImportarLote">
							<i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i> Adicionar em lote
						</div>
					</div>
				</div>
				<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
					<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
						<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
					</button>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
					</div>
				</div>
				<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
					<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
				</button>
			</div>
		</h3>
		<div class="registrosDiv">
			<select id="select-quantidade-por-pagina-StatusIsca" class="form-control" style="float: left; width: auto; height: 34px;">
				<option value="10" selected>10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			<h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
		</div>
		<div style="position: relative;">
			<div id="loadingMessageStatusIsca" class="loadingMessage" style="display: none;">
				<b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
			</div>
			<div class="wrapperStatusIsca">
				<div id="tableStatusIsca" class="ag-theme-alpine my-grid-StatusIsca" style="height: 500px">
				</div>
			</div>
		</div>
	</div>
</div>

<div id="importarIscasArquivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title">Importar Arquivo</h3>
			</div>
			<div class="modal-body">
				<h4 class="subtitle">Dados da Importação:</h4>

				<div class="col-md-12">
					<div class="col-md-8 form-group" style="display: flex;">
						<input class="form-control input-sm" name="arquivoItens" id="arquivoItens" type="file">
						<div class="col-md-1 form-group" style="margin-top: 5px;">
							<i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon" aria-hidden="true" title="Clique para saber mais"></i>
						</div>
					</div>

					<div class="col-md-4 form-group" style="margin-left: auto;">
						<button class="btn btn-primary" onclick="importarItensExcel(event)" type="button" id="botao-adicionar-item-arquivo">Importar</button>
						<button class="btn" id="limparTabelaItens" style="background-color: red;color: white">Limpar Tabela</button>
					</div>
				</div>


				<div class="wrapperImportacaoIsca">
					<div id="tableImportacaoIsca" class="ag-theme-alpine my-grid-isca">
					</div>
				</div>


			</div>
			<div class="modal-footer">
				<div class="footer-group">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-success" id='btnSalvarImportacaoIsca'>Salvar</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="importarIscasLote" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title">Adicionar Seriais em Lote</h3>
			</div>
			<div class="modal-body">
				<h4 class="subtitle">Serial</h4>

				<div class="col-md-12">
					<p>Após digitar o serial é necessário pressionar a tecla "ENTER" para inseri-lo na tabela.</p>
					<div class="col-md-4 form-group" style="display: flex;">
						<input class="form-control input-sm" name="inputSerial" placeholder="Digite um Serial..." id="inputSerial" type="text">
					</div>

					<div class="col-md-4 form-group" style="margin-left: auto;">
						<button class="btn" id="limparTabelaLote" style="background-color: red;color: white">Limpar Tabela</button>
					</div>
				</div>


				<div class="wrapperImportacaoIscaLote">
					<div id="tableImportacaoIscaLote" class="ag-theme-alpine my-grid-isca-lote">
					</div>
				</div>


			</div>
			<div class="modal-footer">
				<div class="footer-group">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-success" id='btnSalvarImportacaoIscaLote'>Salvar</button>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="serialEmTela" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="serialModal">Informações do Serial #</h3>
			</div>
			<div class="modal-body">
				<h4 class="subtitle">Status</h4>
				<span id="statusIscaModal"></span>
			</div>
			<div class="modal-footer">
				<div class="footer-group">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- MODAL MODELO DOCUMENTO ITENS DE MOVIMENTO -->
<div id="modalModeloItens" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 id="header-modal">Modelo de documento <span id="tituloDetalhesDoContrato"></span></h3>
			</div>
			<div class="modal-body scrollModal">
				<div class="row">
					<div class="col-md-12">
						<div class="tab-content" style="padding: 0px 20px">
							<div id="div_identificacao">
								<div class="row">
									<div class="col-md-12" style="border-left: 3px solid #03A9F4; padding-bottom: 0px; margin-right: 0px">
										<p class="text-justify">
											A planilha deve conter as seguintes colunas:
										<ul>
											<li><strong>Serial</strong> (obrigatória)</li>
										</ul>
										Formatos suportados: .xls e .xlsx.
										<p>
										<ul>
											<li>
												<a href="<?= versionFile('arq/iscas', 'planilha_modelo_import.xlsx') ?>" download="planilha_modelo_import.xlsx">Clique aqui</a> para baixar a planilha modelo.
											</li>
										</ul>
										</p>
										</p>
									</div>
								</div>
								<div class="row" style="margin-top: 20px;">
									<img src="<?= versionFile('arq/iscas', 'modelo_planilha_iscas.png') ?>" alt="" class="img-responsive center-block" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
				<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>
	</div>
</div>

<div id="visualizarIsca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="titleIsca">Detalhes da Isca</h3>
			</div>
			<div class="modal-body">
				<div class="col-lg-12">
					<h4 class="subtitle">Dados Gerais</h4>
					<div style="width: 100%; overflow-x: auto;">
						<table class="table table-bordered table-responsive">
							<thead>
								<tr>
									<th>Serial</th>
									<th>Status</th>
									<th>Endereço</th>
									<th>Latitude</th>
									<th>Longitude</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><span id='detalheSerial'></i></span></td>
									<td><span id='detalheStatus'></i></span></td>
									<td><span id='detalheEndereco'></i></span></td>
									<td><span id='detalheLatitude'></i></span></td>
									<td><span id='detalheLongitude'></i></span></td>

								</tr>
							</tbody>
						</table>
					</div>

					<div class="row">
						<hr style="margin: 0;">
						<div class="col-lg-12 justify-items-center">
							<h4 class="subtitle">Localização</h4>

							<div class="col-lg-12" style="position: relative; margin-bottom: 10px; padding: 0px;">
								<div id="loadingMessage" class="loadingMessage" style="display: none;">
									<b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
								</div>
								<div id="mapaDadosPartidaChegada" style="width:100%; height:580px; border-radius: 9px; z-index: 1;"></div>
							</div>
						</div>

					</div>
					<hr>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/pcp', 'iscasLayout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/PCP', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/PCP', 'iscas.js') ?>"></script>

<script>
	var Router = '<?= site_url('Pcp') ?>';
	var BaseURL = '<?= base_url('') ?>';
</script>