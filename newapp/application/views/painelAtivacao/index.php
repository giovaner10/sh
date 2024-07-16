<style type="text/css">
    #div-formulario-ativacao .form-group {
        margin-bottom: 25px;
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("painel_ativacao") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('painel_ativacao') ?>
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
                        <label for="serial">Serial:</label>
                        <input type="text" maxlength="24" name="serial" class="form-control" placeholder="Digite o serial" id="serial" />
                    </div>
                    <div class="input-container">
                        <label for="numNA">NA:</label>
                        <input type="text" maxlength="8" name="numNA" class="form-control" placeholder="Digite o número da NA" id="numNA" />
                    </div>
                    <div class="input-container buscaProcessamento">
						<label for="dataInicial">Data Inicial:</label>
						<input type="date" name="dataInicial" id="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off"  />
					</div>
					<div class="input-container buscaProcessamento">
						<label for="dataFinal">Data Final:</label>
						<input type="date" name="dataFinal" id="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off"  />
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
        <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px; color: #7F7F7F !important;">Registros por página</h6>
                </div>
                <div class="btn-div-responsive" id="btn-div-alertas">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button style="display: flex !important; align-items: center;" class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButtonAtualizar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <svg style="margin-right: 5px;" heigth="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="#f5f5f5" d="M463.5 224H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1c-87.5 87.5-87.5 229.3 0 316.8s229.3 87.5 316.8 0c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0c-62.5 62.5-163.8 62.5-226.3 0s-62.5-163.8 0-226.3c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5z" />
                            </svg> Atualizar <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-acoes opcoes_importacao dropdown-menu-atualizacao" aria-labelledby="dropdownMenuButtonAtualizar" id="opcoes_atualizar" style="width: 180px; height: 100px;">
                            <h5 class="dropdown-title"> Atualização automática </h5>
                            <div class="dropdown-item opcao_importacao" id="10seg">
                                A cada 10 Segundos
                            </div>
                            <div class="dropdown-item opcao_importacao" id="60seg">
                                A cada 60 Segundos
                            </div>
                            <div class="dropdown-item opcao_importacao" id="180seg">
                                A cada 180 Segundos
                            </div>
                            <div class="dropdown-item opcao_importacao" id="stopInterval">
                                Desativar
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

            <div style="position: relative;">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 530px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="ativacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleProcessoAtivacao">Visualizar Ativação</h3>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" style="margin-left: 5px">
                    <li class="nav-item active">
                        <a id="tab-processo-ativacao" href="" data-toggle="tab" class="nav-link active" type="button">Processo de Ativação</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-formulario-ativacao" href="" data-toggle="tab" class="nav-link" type="button">Formulário de Ativação</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-inventario-ativacao" href="" data-toggle="tab" class="nav-link" type="button">Inventário de Ativação</a>
                    </li>
                </ul>
                <div id="div-processo-ativacao" class="col-md-12" style="padding-top: 20px; padding-bottom: 15px;">
                    <div id="alert-processo-ativacao" class="alert alert-danger" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar" style="width: 20px; height: 20px; font-size: 24px;"><span aria-hidden="true">&times;</span></button>
                        <span id="alert-msg-processo-ativacao"></span>
                    </div>
                    <div class="wrapperProcessoAtivacao">
                        <div id="tableProcessoAtivacao" class="ag-theme-alpine my-grid" style="height: 500px">
                        </div>
                    </div>
                </div>
                <div id="div-formulario-ativacao" class="col-md-12" style="padding-top: 20px; display: none;">
                    <div id="alert-formulario-ativacao" class="alert alert-danger" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar" style="width: 20px; height: 20px; font-size: 24px;"><span aria-hidden="true">&times;</span></button>
                        <span id="alert-msg-formulario-ativacao"></span>
                    </div>
                    <div class='row'>
                        <div class="col-sm-4 input-container form-group">
                            <label for="origemForm">Origem: </label>
                            <input type="text" name="origemForm" class="form-control" id="origemForm" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="naCRMForm">NA: </label>
                            <input type="text" name="naCRMForm" class="form-control" id="naCRMForm" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="tipoServ">Tipo de Serviço: </label>
                            <input type="text" name="tipoServ" class="form-control" id="tipoServ" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="serieAntForm">Série Ant: </label>
                            <input type="text" name="serieAntForm" class="form-control" id="serieAntForm" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="idSataNt">ID SAT Ant: </label>
                            <input type="text" name="idSataNt" class="form-control" id="idSataNt" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="iccid1Ant">ICCID 1 Ant: </label>
                            <input type="text" name="iccid1Ant" class="form-control" id="iccid1Ant" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="iccid2Ant">ICCID 2 Ant: </label>
                            <input type="text" name="iccid2Ant" class="form-control" id="iccid2Ant" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="renavan">Renavan: </label>
                            <input type="text" name="renavan" class="form-control" id="renavan" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="placa">Placa: </label>
                            <input type="text" name="placa" class="form-control" id="placa" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="celTecnico">Celular do Técnico: </label>
                            <input type="text" name="celTecnico" class="form-control" id="celTecnico" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="tipoVeiculo">Tipo do Veículo: </label>
                            <input type="text" name="tipoVeiculo" class="form-control" id="tipoVeiculo" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="corVeiculo">Cor do Veículo: </label>
                            <input type="text" name="corVeiculo" class="form-control" id="corVeiculo" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="modeloVeiculo">Modelo do Veículo: </label>
                            <input type="text" name="modeloVeiculo" class="form-control" id="modeloVeiculo" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="codCRM">Código: </label>
                            <input type="text" name="codCRM" class="form-control" id="codCRM" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="servCRM">Serviço: </label>
                            <input type="text" name="servCRM" class="form-control" id="servCRM" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="hodometro">Hodômetro: </label>
                            <input type="text" name="hodometro" class="form-control" id="hodometro" disabled />
                        </div>
                    </div>
                </div>
                <div id="div-inventario-ativacao" class="col-md-12" style="padding-top: 20px; padding-bottom: 15px; display: none;">
                    <div id="alert-inventario-ativacao" class="alert alert-danger" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar" style="width: 20px; height: 20px; font-size: 24px;"><span aria-hidden="true">&times;</span></button>
                        <span id="alert-msg-inventario-ativacao"></span>
                    </div>
                    <div class='row'>
                        <div class="col-sm-4 input-container form-group">
                            <label for="origemInvent">Origem: </label>
                            <input type="text" name="origemInvent" class="form-control" id="origemInvent" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="antenaSat">Antena SAT: </label>
                            <input type="text" name="antenaSat" class="form-control" id="antenaSat" disabled />
                        </div>
                        <div class="col-sm-4 input-container form-group">
                            <label for="nserie">N° Série: </label>
                            <input type="text" name="nserie" class="form-control" id="nserie" disabled />
                        </div>
                    </div>
                    <h4 class="subtitle">
                        Itens de Inventário de Ativação
                    </h4>
                    <div id="alert-itens-inventario-ativacao" class="alert alert-danger" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar" style="width: 20px; height: 20px; font-size: 24px;"><span aria-hidden="true">&times;</span></button>
                        <span id="alert-msg-itens-inventario-ativacao"></span>
                    </div>
                    <div class="wrapperItensInventarioAtivacao">
                        <div id="tableItensInventarioAtivacao" class="ag-theme-alpine my-grid" style="height: 370px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/PainelAtivacao', 'index.js') ?>"></script>

<script>
    var Router = '<?= site_url('PainelAtivacao') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>