<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("pcp_comandos_lora") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('pcp') ?> > LoRa >
        <?= lang('pcp_comandos_lora') ?>
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

                    <div class="input-container serialEquipamento">
                        <label for="serialEquipamento">ID Terminal:</label>
                        <input type="text" name="serialEquipamento" class="form-control" placeholder="Digite o ID do terminal..." id="serialEquipamento" autocomplete="off" />
                    </div>

                    <div class="input-container dataInicioBusca">
                        <label for="dataInicialBusca">Data Inicial:</label>
                        <input class="form-control" type="date" name="dataInicialBusca" id="dataInicialBusca" />
                    </div>

                    <div class="input-container dataFinalBusca">
                        <label for="dataFinalBusca">Data Final:</label>
                        <input class="form-control" type="date" name="dataFinalBusca" id="dataFinalBusca" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-hirtorico-comandos" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b><?= lang("pcp_historico_comandos_lora") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="cadastrarComando" type="button" style="margin-right: 10px;" data-toggle="modal" data-target="#enviarComando"><i class="fa fa-plus" aria-hidden="true"></i> Enviar Comando</button>
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
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
                <div class="wrapperHistoricoComandos">
                    <div id="tableHistoricoComandos" class="ag-theme-alpine my-grid-hitorico-comandos" style="height: 519px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ENVIO DE COMANDO -->
<div id="envioComando" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formEnvioComando'>
                <input type="hidden" name="idModelo" id="idModelo">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleEnviarComando">Enviar Comando</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0; margin-left: -4px;">Dados para Envio</h4>
                        <div class='row'>

                            <div class="col-md-6 input-container form-group" id="inputIdEquipamento" style="height: 59px !important; margin-left: -3px;">
                                <label for="idEquipamento">ID Equipamento: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="idEquipamento" id="idEquipamento" placeholder="ID do equipamento" required autocomplete="off" />
                            </div>

                            <div class="col-md-6 input-container form-group comando" style="height: 59px !important;">
                                <label for="idComando">Comando: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="idComando" id="idComando" placeholder="Selecione um comando para ser enviado..." style="width: 100%;">
                                    <option value="">Selecione o Comando</option>
                                    <option value="0">Desabilitar Modo Emergência</option>
                                    <option value="1">Habilitar Modo Emergência</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-group" style="justify-content: flex-end; margin-left: 15px;">
                            <button type="submit" class="btn btn-success" id='btnEnviarComando'>Enviar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL HISTÓRICO COMANDOS -->
<div id="modalHistorico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalHistorico" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <input type="hidden" name="idFirmwareAssociar" id="idFirmwareAssociar">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleAssociacao">Histórico Comando:</h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid" style="display: flex; flex-direction: column;">
                    <h4 class="subtitle" style="padding-left: 0; padding-right: 0; margin-left: -4px;">Detalhes do Comando</h4>

                    <div class="row input-row" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                        <div class="col-md-4 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px; margin-left: -3px;">
                            <label for="idComandoModal" style="width: 100%; text-align: left;">ID Comando: </label>
                            <input class="form-control" type="text" id="idComandoModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>

                        <div class="col-md-4 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                            <label for="idEquipamentoModal" style="width: 100%; text-align: left;">ID Equipamento: </label>
                            <input class="form-control" type="text" id="idEquipamentoModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>

                        <div class="col-md-4 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                            <label for="comandoModal" style="width: 100%; text-align: left;">Comando: </label>
                            <input class="form-control" type="text" id="comandoModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>
                    </div>
                    
                    <div class="row input-row" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                        <div class="col-md-6 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px; margin-left: -3px;">
                            <label for="dataCadastroModal" style="width: 100%; text-align: left;">Data de Cadastro: </label>
                            <input class="form-control" type="text" id="dataCadastroModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>

                        <div class="col-md-6 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                            <label for="dataEnvioModal" style="width: 100%; text-align: left;">Data de Envio: </label>
                            <input class="form-control" type="text" id="dataEnvioModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>
                    </div>

                    <div class="row input-row" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                        <div class="col-md-6 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px; margin-left: -3px;">
                            <label for="mensagemComandoModal" style="width: 100%; text-align: left;">Mensagem: </label>
                            <input class="form-control" type="text" id="mensagemComandoModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>

                        <div class="col-md-6 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                            <label for="observacaoComandoModal" style="width: 100%; text-align: left;">Observação: </label>
                            <input class="form-control" type="text" id="observacaoComandoModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>
                    </div>

                    <div class="row input-row" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                        <div class="col-md-12 form-group" style="flex: 1; min-width: 200px; margin-bottom: 15px; margin-left: -3px;">
                            <label for="statusComandoModal" style="width: 100%; text-align: left;">Status: </label>
                            <input class="form-control" type="text" id="statusComandoModal" autocomplete="off" disabled style="width: 100%;" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end">
                </div>
            </div>
        </div>
    </div>
</div>






<script>
    var Router = '<?= site_url('Pcp') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/firmware', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/PCP', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/PCP', 'comandosLora.js') ?>"></script>