<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Tipos de Documento", site_url('Homes'), lang('gente_gestao'), "RH > Tipos de Documento");
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container" style="width:100%;">
                        <label for="nomeDocumento">Tipo do Documento:</label>
                        <input type="text" name="nomeDocumento" class="form-control" placeholder="Digite o Tipo do Documento" id="nomeDocumento" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success search-button" id="BtnPesquisar" type="button" style="width:100%;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default clear-search-button" id="BtnLimparPesquisar" type="button" style="margin-top: 10px;width:100%;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-relatorio-instalacao" style='margin-bottom: 20px; position: relative;'>
            <div class="tablePageHeader">
                <h3>Gerenciamento de Tipos de Documento: </h3>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; gap: 10px; align-items: center; margin-top:15px; margin-bottom:15px;">
                    <div class="dropdown" id="dropdown_exportar">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 67px; height: 91px;">
                        </div>
                    </div>
                    <button style="height: 36.5px;" class="btn btn-primary" id="btnCadastroTipoDocumento">
                        <i class="fa fa-plus"></i> Cadastrar
                    </button>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </div>

            <div id="notFoundMessage" style="display: none;">
                <h5>Nenhum dado encontrado para a pesquisa feita</h5>
            </div>
            <div class="registrosDiv">
                <select id="paginationSelect" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;" id="tabelaInstalacoes">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid-instaladores" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="cadastroTipoDocumento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div id="modalDivAlerta" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formAlertasEmail'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAlertasEmail">Cadastro de Tipo de Documento</h3>
                </div>
                <div class="modal-body" style="padding: 15px;">
                    <div style="display: flex; flex-direction: column; width: 100%;">
                        <div class="row" style="padding: 15px;">
                            <div class="col-md-3 input-container form-group">
                                <label for="obrigatorioCheck">Obrigatório:</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" name="obrigatorioCheck" id="obrigatorioCheck" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-3 input-container form-group">
                                <label for="temValidacaoCheck">Tem Validação:</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" name="temValidacaoCheck" id="temValidacaoCheck" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 input-container form-group">
                            <label for="nomeTipoDocumento">Título: <span style="font-size: 16px; color: #ff0000a8;">*</span></label>
                            <br>
                            <input type="text" class="form-control" name="nomeTipoDocumento" id="nomeTipoDocumento" placeholder="Digite um título para o tipo de documento"  minlength="1" maxlength="120" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id='btnSalvarTipoDocumento'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edicaoTipoDocumento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div id="modalDiv" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formEditarDocumento'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleEditarDocumento">Editar Tipo de Documento</h3>
                </div>
                <div class="modal-body" style="padding: 15px;">
                    <div style="display: flex; flex-direction: column; width: 100%;">
                        <div class="row" style="padding: 15px;">
                            <div class="col-md-3 input-container form-group">
                                <label for="obrigatorioCheckEdit">Obrigatório:</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" name="obrigatorioCheckEdit" id="obrigatorioCheckEdit" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-3 input-container form-group">
                                <label for="temValidacaoCheckEdit">Tem Validação:</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" name="temValidacaoCheckEdit" id="temValidacaoCheckEdit" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 input-container form-group">
                            <label for="nomeTipoDocumentoEdit">Título:</label>
                            <br>
                            <input type="text" class="form-control" name="nomeTipoDocumentoEdit" id="nomeTipoDocumentoEdit" placeholder="Digite um título para o tipo de documento" maxlength="120" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id='btnSalvarTipoDocumentoEdit'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMAÇÃO -->
<div id="modalConfirmacaoMudancaStatus" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Confirmação</h4>
            </div>
            <div class="modal-body">
            <p style="text-align: center; font-size: 16px;" id='mensagemConfirmacao'>Tem certeza que deseja prosseguir com a ação?</p>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button id="btnConfirmarAcaoUsuario" class="btn btn-success" type="button">Confirmar</button>
                <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var Router = '<?= site_url('documentos/TiposDocumento') ?>';
    var SiteURL = '<?= site_url('') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<!-- Style -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/instaladores', 'layout.css') ?>">

<!-- Default Scripts -->
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/EzMock', 'app.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'agGridTable.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/TipoDocumento', 'Exportacoes.js') ?>"></script>

<!-- Custom Scripts -->
<script type="text/javascript" src="<?= versionFile('assets/js/TipoDocumento', 'TipoDocumento.js') ?>"></script>