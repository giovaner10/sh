<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("agendamentos_manutencao") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('auditoria') ?> >
        <?= lang('auditoria_agendamento') ?> >
        <?= lang('agendamentos_manutencao') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader">

    </div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link selected' id="menu-agendamento-manutencao">Agendamentos de Manutenção</a>
                </li>
                <li>
                    <a class='menu-interno-link' id="menu-dashboard-manutencao">Dashboard de Manutenções</a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form action="" id="formBusca">
                <div class="form-group filtro">
                    <div class="buscaSelection" id="filtrarPor">
                        <label for="tipoData">Buscar por:</label>
                        <select id="tipoData" name="tipoData" class="form-control">
                            <option value="dateRangeAgendamentoManutencao">Intervalo de dias</option>
                            <option value="conversation_id">Conversation</option>
                            <option value="status">Status</option>
                        </select>
                    </div>

                    <div class="input-container_ag" id="dateContainer1">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value="" />
                    </div>

                    <div class="input-container_ag" id="dateContainer2">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value="" />
                    </div>

                    <div class="input-container_ag" id="conversationContainer" style="display:none;">
                        <label for="conversationInput">Conversation:</label>
                        <input type="text" id="conversationInput" class="form-control" placeholder="Insira um ID de conversa">
                    </div>

                    <div class="input-container_ag" id="statusContainer" style="display:none;">
                        <label for="statusInput">Status:</label>
                        <select class="form-control" id="statusInput">
                            <option value="0">Aguardando Mantenedor</option>
                            <option value="1">Agendado</option>
                            <option value="2">Atendente</option>
                            <option value="3">Não Agendado</option>
                            <option value="4">Agendado/Atendente</option>
                            <option value="5">Cancelado/Ausente</option>
                            <option value="6">Em Atendimento</option>
                            <option value="7">Concluído/Finalizado</option>
                            <option value="8">Cancelado</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" id="BtnPesquisar" type="submit" style="width:100%;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" id="BtnLimparPesquisar" type="button" style="margin-top: 10px;width:100%;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>

            <form action="" id="formBuscaDashboard" style="display: none;">
                <div class="form-group filtro">

                    <div class="buscaSelection" id="filtrarPorDashboard">
                        <label for="tipoDataDashboard">Buscar por:</label>
                        <select id="tipoDataDashboard" name="tipoDataDashboard" class="form-control">
                            <option value="dateRange">Intervalo de dias</option>
                            <option value="mes">Mês</option>
                            <option value="ano">Ano</option>
                            <option value="periodo">Período</option>
                        </select>
                    </div>

                    <div class="input-container" id="dateContainer1Dashboard">
                        <label for="dataInicialDashboard">Data Inicial:</label>
                        <input type="date" name="dataInicialDashboard" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicialDashboard" value="" />
                    </div>

                    <div class="input-container" id="dateContainer2Dashboard">
                        <label for="dataFinalDashboard">Data Final:</label>
                        <input type="date" name="dataFinalDashboard" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinalDashboard" value="" />
                    </div>

                    <div class="input-container" id="mesContainerDashboard" style="display:none;">
                        <label for="mesInputDashboard">Mês:</label>
                        <input type="month" id="mesInputDashboard" class="form-control">
                    </div>

                    <div class="input-container" id="anoContainerDashboard" style="display:none;">
                        <label for="anoInputDashboard">Ano:</label>
                        <input type="number" id="anoInputDashboard" class="form-control" placeholder="Insira um ano para busca" min="1900" max="2099" step="1" value="2023" />
                    </div>

                    <div class="input-container" id="periodoContainerDashboard" style="display:none;">
                        <label for="periodoInputDashboard">Período:</label>
                        <select id="periodoInputDashboard" class="form-control">
                            <option value="7days">Últimos 7 dias</option>
                            <option value="1mes">Último mês</option>
                            <option value="3mes">Últimos 3 meses</option>
                            <option value="6mes">Últimos 6 meses</option>
                            <option value="12mes">Último ano</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" id="BtnPesquisarDashboard" type="submit" style="width: 100%;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" id="BtnLimparDashboard" type="button" style="width: 100%; margin-top: 10px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-agendamento-manutencao" style='margin-bottom: 20px; position: relative;'>
            <div class="tablePageHeader">
                <h3>Agendamentos de Manutenção: </h3>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center; margin-top:15px; margin-bottom:15px;">
                    <div class="dropdown" style="margin-right: 10px;" id="dropdown_exportar">
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
            </div>

            <div id="notFoundMessage" style="display: none;">
                <h5>Nenhum dado encontrado para a pesquisa feita</h5>
            </div>

            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;" id="tabelaInstalacoes">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-dashboard-manutencao" style='margin-bottom: 20px; position: relative; display: none;'>
            <div style="display: flex; flex-direction:row; justify-content: space-between; margin-bottom: 20px;">
                <h3>Dashboard de Manutenções: </h3>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </div>

            <div id="loadingDashboard" style="display: none;">
                <i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b>
            </div>

            <div class="chartPlotContainer" id="chartContainer" style="flex-wrap: wrap;">
                

                <div class="chartContainer container" id="chartBarButton">
                    <div class="container chartHeader">
                        <charttitle>Estatísticas de Agendamento de Manutenção - Gráfico Barra</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="myChartBar"></div>
                        <div id="anoAgManutenBar" class="chartHeaderFooterText">Ano</div>
                    </div>
                </div>

            
                <div class="chartContainer container" id="chartLineButton">
                    <div class="container chartHeader">
                        <charttitle>Estatísticas de Agendamento de Manutenção - Gráfico Linha</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="myChartLine"></div>
                        <div id="anoAgManutenLine" class="chartHeaderFooterText">Ano</div>
                    </div>
                </div>


                <div class="chartContainer container" id="chartMotivosRecusaManutenButton">
                    <div class="container chartHeader">
                        <charttitle>Recusa de Manutenção - Motivos Frequentes</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="totalRecusasSubtitle" class="chartHeaderFooterText">
                        <div style="
                            width: 12px; 
                            height:12px; 
                            background-color: #006DF9; 
                            border-radius:100%;">
                        </div>
                        <div> Nenhuma recusa</div>
                        </div>
                    <div id="recusaManutencaoChart" style="max-height: 280px !important;"></div>
                        <div id="mesRecusas" class="chartHeaderFooterText">Mês</div>
                    </div>
                </div>


                <div class="chartContainer container" id="chartMaiorRecusaTecnicosManutenButton">
                    <div class="container chartHeader">
                        <charttitle>Técnicos que Mais Recusam Manutenção</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="maiorRecusaTecnicosChart"></div>
                        <div id="mesMaiorRecusaTecnicos" class="chartHeaderFooterText">Mês</div>
                    </div>
                </div>


                <div class="chartContainer container" id="chartMenorRecusaTecnicosManutenButton">
                    <div class="container chartHeader">
                        <charttitle>Técnicos que Menos Recusam Manutenção</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="menorRecusaTecnicosChart"></div>
                        <div id="mesMenorRecusaTecnicos" class="chartHeaderFooterText">Mês</div>
                    </div>
                </div>
            </div>

            <div id="emptyMessage" style="display: none; position: relative; margin: 15px;">
                <b>Nenhuma informação a ser exibida.</b>
            </div>
        </div>
    </div>
</div>

<div id="modalConversa" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="modal-header-text">Cliente: <span id="clientNameSpan" class="clientNameClass"></span></div>
                <div class="modal-header-text">Conversa: <span id="conversationIdSpan" class="conversationIdClass"></span></div>
            </div>
            <?php
            require("VisualizarConversaManutencao.php");
            ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="modalAuditTrack" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Audit Track</h3>
            </div>
            <div class="modal-body scrollModal">
                <div class="container-fluid" style="display: flex; flex-direction: column;">
                    <div>
                        <input type="text" id="search-input-audit" placeholder="Buscar" style="float: right; margin: 10px; height:30px; padding-left: 10px;">
                    </div>
                    <div class="wrapperAuditTrack">
                        <div id="tableAuditTrack" class="ag-theme-alpine my-grid-audit-track" style="height: 500px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>

        </div>
    </div>
</div>

<div id="infoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title">Informações Adicionais</h5>
            </div>
            <div class="modal-body">
                <span style="color: #333; font-size: 15px;">Conversa Manutenção:</span>
                <span id="modalInfoText" style="color: #333; font-size: 15px;"></span>
                <i class="fa fa-copy" style="cursor: pointer; margin-left: 10px; font-size: 20px;" title="Copiar para área de transferência" onclick="copyModalInfoToClipboard()"></i>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="ModalStatusAgendamento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Alterar Status do Agendamento</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12 form-group" hidden>
                    <input type="text" class="form-control" id="idAgendamento" hidden>
                </div>
                <span style="color: #333; font-size: 15px;">Status atual: <span id="statusAgendamento"></span></span>
                <br><br>
                <span style="color: #333; font-size: 15px;">Selecione o novo status:</span>
                <form>
                    <div class="form-group">
                        <select class="form-control" id="statusSelect">
                            <option value="0">Aguardando Mantenedor</option>
                            <option value="1">Agendado</option>
                            <option value="2">Atendente</option>
                            <option value="3">Não Agendado</option>
                            <option value="4">Agendado/Atendente</option>
                            <option value="5">Cancelado/Ausente</option>
                            <option value="6">Em Atendimento</option>
                            <option value="7">Concluído/Finalizado</option>
                            <option value="8">Cancelado</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="alterarStatusAgendamento(this, $('#idAgendamento').val(), $('#statusSelect').val())">Alterar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="agendamentoManutenBarChartModal" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage1" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Estatísticas de Agendamento de Manutenção - Gráfico Barra</h3>
            </div>

            <div id="agendamentoManutenBarChart"></div>
            <div id="anoAgManutenBarModal" class="chartHeaderFooterText" style="margin-bottom: 20px;">Mês</div>

        </div>
    </div>
</div>

<div id="agendamentoManutenLineChartModal" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div id="loadingMessage2" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Estatísticas de Agendamento de Manutenção - Gráfico Linha</h3>
            </div>

            <div id="agendamentoManutenLineChart"></div>
            <div id="anoAgManutenLineModal" class="chartHeaderFooterText" style="margin-bottom: 20px;">Mês</div>

        </div>
    </div>
</div>

<div id="agendamentoManutenReportRecusaTecnicos" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- <div id="loadingMessage2" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div> -->

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalRecusa">Recusa de Manutenção - Motivos Frequentes</h3>
            </div>

            <div style="margin: 30px 0 30px 0;">
                <div id="totalRecusasSubtitleModal" class="chartHeaderFooterText">
                    <div style="
                            width: 12px; 
                            height:12px; 
                            background-color: #006DF9; 
                            border-radius:100%;">
                    </div>
                    <div> Nenhuma recusa</div>  
                </div>
                <div id="recusaManutencaoChartModal" class="ag-theme-alpine"></div>
                <div id="mesRecusasModal" class="chartHeaderFooterText" style="margin-bottom: 20px;">Mês</div>
            </div>

        </div>
    </div>
</div>

<div id="agendamentoManutenReportTecnicosMaiorRecusa" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalRecusa">Técnicos que Mais Recusam Manutenção</h3>
            </div>

            <div id="maiorRecusaTecnicosChartModal"></div>
            <div id="mesMaiorRecusaTecnicosModal" class="chartHeaderFooterText" style="margin-bottom: 15px;">Mês</div>

        </div>
    </div>
</div>

<div id="agendamentoManutenReportTecnicosMenorRecusa" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalRecusa">Técnicos que Menos Recusam Manutenção</h3>
            </div>

            <div id="menorRecusaTecnicosChartModal"></div>
            <div id="mesMenorRecusaTecnicosModal" class="chartHeaderFooterText" style="margin-bottom: 15px;">Mês</div>

        </div>
    </div>
</div>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>

<script>
    var Router = '<?= site_url('Auditoria/Agendamento') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/agendamento', 'layout.css') ?>">

<!-- Libraries & Utils -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/agendamento', 'Exportacoes.js') ?>"></script>

<!-- Custom Scripts -->
<script type="text/javascript" src="<?= versionFile('assets/js/agendamento/Manutencao', 'Constants.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/agendamento/Manutencao', 'VisualizarManutencao.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/agendamento', 'AgendamentoManutencao.js') ?>"></script>
