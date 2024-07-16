<div style="margin-left: 15px;">
    <div class="row">
        <div class="col-md-12">
            <div class="text-title">
                <h3 style="padding: 0;"><?= lang("dashboard") ?></h3>
                <h4 style="padding: 0;">
                    <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
                    <?= lang('monitoramento_uso_tecnologias') ?> >
                    <?= lang('dashboard_monitoramento_tecnologias') ?>
                </h4>
                <hr>
            </div>
        </div>
    </div>

    <div id="loading">
        <div class="loader"></div>
    </div>


        <div class="row" style="margin: 15px 0 0 15px; align-items: flex-start;">
            <div class="col-md-3">
                <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
                    <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
        
                    <form id="formBusca">
                        <div class="form-group filtro">
                            <div class="input-container buscaData" id="dateContainer1">
                                <label for="dataInicial">Data Inicial:</label>
                                <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" />
                            </div>
        
                            <div class="input-container buscaData" id="dateContainer2">
                                <label for="dataFinal">Data Final:</label>
                                <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" />
                            </div>
        
                            <div class="input-container buscaCliente" >
                                <label for="clienteBusca">Cliente:</label>
                                <select class="form-control" name="clienteBusca" id="clienteBusca" type="text" style="width: 100%; margin-bottom: 5px;">
                                    <option value="0" disabled selected>Buscando clientes...</option>
                                </select>
                            </div>
        
                            <div class="button-container">
                                <button class="btn btn btn-success" style='width:100%; margin-top: 10px' id="filtrar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                                <button class="btn btn-default" style='width:100%; margin-top: 5px' id="btnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-9" id="conteudo">
                <div class="row" style= "margin-bottom: 10px;">
                    <div class="col-md-9" style="padding: 0 20px;">
                        <div class="card" id="charts">
                            <div style="display: flex; align-items: top; flex-wrap: wrap; margin-bottom: 5px; flex-direction: row;">
                                <h4 id="charts-title" style="margin-right: auto;">Gráfico Consolidado de Uso das Tecnologias:</h4>
                                <button class="btn btn-primary" id="downloadChart" type="button" style="margin-top: 5px; margin-left: 5px; margin-bottom: 10px; max-height: 5vh;"><i class="fa fa-download" aria-hidden="true"></i> Baixar</button>
                            </div>
                            <div class="row">
                                <div class="col-md-12 chart" id="chart-1">
                                    <div style="position: relative; height: 400px;">
                                        <div id="loadingMessage" class="loadingMessage">
                                            <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                                        </div>
                                        <div id="myChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>




<script>
      (function () {const appLocation = "";
        window.__basePath = appLocation;
      })();


    var Router = '<?= site_url('MonitoramentoUsoTecnologias/Dashboard') ?>';
    var BaseURL = '<?= base_url('') ?>';

    $(document).ready(function() {
        $(".metrica-card").height('auto');
        var height = Math.max($(".metrica-card").height());
        $(".metrica-card").height(height);
    });

    $(window).resize(function() {
        $(".metrica-card").height('auto');
        var height = Math.max($(".metrica-card").height());
        $(".metrica-card").height(height);
    })
</script>

<!-- AG Charts Community edition. -->
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.1.1/dist/umd/ag-charts-community.js?t=1708596535415"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/MonitoramentoUsoTecnologias', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/MonitoramentoUsoTecnologias', 'Dashboard.js') ?>"></script>