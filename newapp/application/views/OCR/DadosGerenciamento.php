

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("dados_gerenciamento") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('ocr') ?> >
        <?= lang('dados_gerenciamento') ?>
    </h4>
</div>

<?php
$menu_ocr = $_SESSION['menu_ocr'];
?>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link <?= $menu_ocr == 'EventosPlacas' ? 'selected' : '' ?>' id="menu-eventos-placas"><?= lang("deteccao_placas") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link <?= $menu_ocr == 'DadosGerenciamentoOCR' ? 'selected' : '' ?>' id="menu-gerenciamento"><?= lang("leitura_placas") ?></a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaData" id="dateContainer1" style="<?= ($menu_ocr == 'Whitelist' || $menu_ocr == 'Blacklist' || $menu_ocr == 'AlertasEmail') ? 'display:none;' : '' ?>">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" />
                    </div>

                    <div class="input-container buscaData" id="dateContainer2" style="<?= ($menu_ocr == 'Whitelist' || $menu_ocr == 'Blacklist' || $menu_ocr == 'AlertasEmail') ? 'display:none;' : '' ?>">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" />
                    </div>

                    <div class="input-container buscaData buscaCliente" style="<?= $menu_ocr == 'AlertasEmail' ? 'display:none;' : '' ?>">
                        <label for="placaBusca">Placa:</label>
                        <input type="text" name="placaBusca" class="form-control" placeholder="Digite a Placa" id="placaBusca" />
                    </div>

                    <div class="input-container buscaTipoMatch">
                        <label for="tipoEventoBusca">Tipo Match:</label>
                        <select class="form-control" name="tipoEventoBusca" id="tipoEventoBusca" style="width: 100%;">
                            <option value="1">Hot List</option>
                            <option value="2">Cold List</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>

                    <!-- <div class="button-container">
                    <button class="btn btn-primary" style='width:100%' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                </div> -->
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative; <?= $menu_ocr == 'DadosGerenciamentoOCR' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("leitura_placas") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
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
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-dados-mapa" style="margin-bottom: 20px; display: none;">
            <h3>
                <b>Mapa</b>
            </h3>
            <div class="row">
                <div class="col-md-12" style="position: relative;">
                    <div id="loadingMessage" class="loadingMessage" style="display: none;">
                        <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                    </div>
                    <div id="mapDadosGerenciamento" style="width:100%; height:500px; border-radius: 15px; z-index: 1;"></div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-eventos-placas" style='margin-bottom: 20px; position: relative; <?= $menu_ocr == 'EventosPlacas' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("deteccao_placas") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonEventosPlacas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_eventos_placas" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-eventos-placas" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageEventosPlacas" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div>
                <div class="wrapperEventosPlacas">
                    <div id="tableEventosPlacas" class="ag-theme-alpine my-grid-eventos-placas" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-dados-mapa-eventos" style="margin-bottom: 20px; display: none; <?= $menu_ocr == 'EventosPlacas' ? '' : 'display: none;' ?>">
            <h3>
                <b>Mapa dos Eventos</b>
            </h3>
            <div class="row">
                <div class="col-md-12" style="position: relative;">
                    <div id="loadingMessageEventosPlacas" class="loadingMessage" style="display: none;">
                        <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                    </div>  
                    <div id="mapEventos" style="width:100%; height:500px; border-radius: 15px; z-index: 1;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="infoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title"><?= lang("leitura_placas") ?></h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <h4 class="subtitle">Dados Gerais</h4>
                    <div style="width: 100%; overflow-x: auto;">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Placa Lida</th>
                                    <th>Tipo de Match</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Data Inicial</th>
                                    <th>Data Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span id='detalheSerial'></i></span></td>
                                    <td><span id='detalhePlacaLida'></i></span></td>
                                    <td><span id='detalheTipo'></i></span></td>
                                    <td><span id='detalheMarca'></i></span></td>
                                    <td><span id='detalheModelo'></i></span></td>
                                    <td><span id='detalheDataInicial'></i></span></td>
                                    <td><span id='detalheDataFinal'></i></span></td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-6" style="margin-bottom: 20px;">
                        <h4 class="subtitle">Imagem Coletada</h4>
                        <div id="div-img" class="div-img">
                            <img src="" alt="Imagem da Placa Lida" style="max-width: 560px;" id="img">
                        </div>
                        <div id="div-img-mensagem" class="div-img-mensagem" style="display: none;">
                            <h4 class="subtitle">Imagem não  encontrada</h4>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 20px;">
                        <h4 class="subtitle">Imagem Placa</h4>
                        <div id="div-img-plate" class="div-img-plate">
                            <img src="" alt="Imagem da Placa Lida" style="width: 150px; height: 75px;" id="img-plate">
                        </div>
                        <div id="div-img-mensagem-plate" class="div-img-mensagem-plate" style="display: none;">
                            <h4 class="subtitle">Imagem não  encontrada</h4>
                        </div>
                    </div>
                    
                    <div class="row">
                        <hr style="margin: 0;">
                        <div class="col-md-12 justify-items-center">
                            <h4 class="subtitle">Ponto de Partida e Chegada</h4>
                            
                            <div class="col-md-12" style="margin-bottom: 30px;">
                                <div class="col-md-6" style="padding: 5px;">
                                    <div class="info-eventos-modal" style="padding: 8px; margin-bottom: 20px; height:70px;">
                                        Ref.: <span class="label label-info" style="font-size:90%; white-space: normal;" id='detalheRefInicial'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>                
                                    </div>
                                    <div class="info-eventos-modal align-items-center" style="padding: 8px;">
                                        Latitude: <span class="label label-info" style="font-size:90%;" id='detalheLatInicial'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                        <hr style="margin-top:6px; margin-bottom:6px;">
                                        Longitude: <span class="label label-info" style="font-size:90%;" id='detalheLonInicial'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding: 5px;">
                                    <div class="info-eventos-modal align-items-center" style="padding: 8px; margin-bottom: 20px; height:70px;">
                                        Ref. final: <span class="label label-info" style="font-size:90%; white-space: normal;" id='detalheRefFinal'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                    </div>
                                    <div class="info-eventos-modal align-items-center" style="padding: 8px;">
                                        Latitude final: <span class="label label-info" style="font-size:90%; margin-bottom: 5px;" id='detalheLatFinal'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                        <hr style="margin-top:6px; margin-bottom:6px;">
                                        Longitude final: <span class="label label-info" style="font-size:90%;" id='detalheLonFinal'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                    </div>
                                </div>
                                <br>
                            </div>
                            
                            <div class="col-md-12" style="position: relative; margin-bottom: 10px; padding: 0px;">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="eventosPlacas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="eventosPlacasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title"><?= lang("deteccao_placas") ?></h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <h4 class="subtitle">Dados Gerais</h4>
                    <div style="width: 100%; overflow-x: auto;">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Placa Lida</th>
                                    <th>Tipo de Match</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Cliente</th>
                                    <th>Início do Evento</th>
                                    <th>Fim do Evento</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span id='detalheSerialEvento'></i></span></td>
                                    <td><span id='detalhePlacaLidaEvento'></i></span></td>
                                    <td><span id='detalheTipoEvento'></i></span></td>
                                    <td><span id='detalheMarcaEvento'></i></span></td>
                                    <td><span id='detalheModeloEvento'></i></span></td>
                                    <td><span id='detalheClienteEvento'></i></span></td>
                                    <td><span id='detalheDataInicialEvento'></i></span></td>
                                    <td><span id='detalheDataFinalEvento'></i></span></td>
                                    <td><span id='detalheStatusEvento'></i></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-md-6" style="margin-top: 20px;">
                            <h4 class="subtitle">Imagem Coletada</h4>
                            <div id="div-img-evento" class="div-img">
                                <img src="" alt="Imagem da Placa Lida" style="width: fit-content;" id="img-evento">
                            </div>
                            <div id="div-img-mensagem-evento" class="div-img-mensagem" style="display: none;">
                                <h4 class="subtitle">Imagem não encontrada</h4>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top: 20px;">
                            <h4 class="subtitle">Imagem Placa</h4>
                            <div id="div-img-evento-placa" class="div-img-placa">
                                <img src="" alt="Imagem da Placa Lida" style="width: 150px; height: 75px;" id="img-evento-placa">
                            </div>
                            <div id="div-img-mensagem-evento-placa" class="div-img-mensagem-placa" style="display: none;">
                                <h4 class="subtitle">Imagem não encontrada</h4>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="subtitle">Tratamento de Evento</h4>
                    <div class="statusEvento">
                        <form class="row formEvento">
                            <!-- <input type="hidden" name="id"> -->
                            <div class="col-md-3">
                                <label id="labelForOcorrencia" class="labels" for="ocorrencia">Número da ocorrência: </label>
                                <input class="form-control form-control-sm" type="text" id="ocorrencia" name="ocorrencia">
                            </div>
                            <div class="col-md-3">
                                <label for="status">Status: <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" id="status" type="text" required>
                                    <option value="" selected></option>
                                    <option value="2">Tratado</option>
                                    <option value="3">Em Tratativa</option>
                                    <option value="4">Finalizado Evento Real</option>
                                    <option value="5">Finalizado Evento Falso</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="labels" for="motivo">Motivo: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="motivo" name="motivo" required>
                            </div>
                            <button type="submit" id="botaoTratamento" class="btn btn-success" style="margin: 22px 15px 0px 15px;">Salvar</button>
                        </form>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="subtitle">Mapa do Evento</h4>
                            <div id="loadingMessage" class="loadingMessage" style="display: none;">
                                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                            </div> 
                            <div class="col-md-12" style="margin-bottom: 30px;">
                                <div class="col-md-6 ref-eventos-modal">
                                    <div class="info-eventos-modal">
                                        Ref.: <span class="label label-info" style="font-size:90%; white-space: normal;" id='detalheRefEvento'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 lat-log-eventos-modal">
                                    <div class="info-eventos-modal">
                                        Latitude: <span class="label label-info" style="font-size:90%;" id='detalheLatEvento'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                        Longitude: <span class="label label-info" style="font-size:90%;" id='detalheLonEvento'><b>Processando...</b><i class="fa fa-spinner fa-spin" style="color: #06a9f6;"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div id="mapEventoDeteccao" style="width:100%; height:500px; border-radius: 15px; z-index: 1;"></div>
                        </div>
                        
                    </div>
                    <hr>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'DadosGerenciamento.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'MapaDadosGerenciamento.js') ?>"></script>

<script>
    var Router = '<?= site_url('OCR/DadosGerenciamentoOCR') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>