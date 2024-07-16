<div class="alert alert-success display"></div>
<div class="alert alert-error display"></div>

<!-- Modal Grafico Veiculos -->
<div id="modalCharts1" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage1" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Quantidade de veículos</h3>
            </div>

            <canvas id="myChartModal1" style="position: relative; height:26rem; width:30rem;"></canvas>

        </div>
    </div>
</div>

<!-- Modal Grafico Licitacoes -->
<div id="modalCharts2" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage2" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Quantidade de licitações</h3>
            </div>

            <canvas id="myChartModal2" style="position: relative; height:26rem; width:30rem;"></canvas>

        </div>
    </div>
</div>

<!-- Modal Grafico Valores -->
<div id="modalCharts3" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage3" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Valores</h3>
            </div>

            <canvas id="myChartModal3" style="position: relative; height:26rem; width:30rem; "></canvas>

        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="text-title">
        <h3 style="padding: 0 20px; margin-left: 15px;">Licitações</h3>
        <h4 style="padding: 0 20px; margin-left: 15px;">
            <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
            <?= lang('cadastros') ?> >
            <?= lang('licitacoes') ?>
        </h4>
    </div>

    <div class="container-fluid">
        <div class="row" style="margin: 15px 0 0 15px;">
            <div class="col-md-12" id="conteudo">
                <div class="card-conteudo" id="tableGRID" style='margin-bottom: 20px; position: relative;'>
                    <h3>
                        <b>Dados:</b>
                    </h3>

                    <div class="d-flex align-items-center" id="internal-navbar" style="display: inline-flex; gap: 10px; justify-content: end; margin: 10px 0px 10px;">

                        <div id="adicionarLicitacao" class="btn btn-gestor btn-primary" title="Adicionar">
                            <i class="fa fa-plus"></i> Adicionar
                        </div>

                        <div class="dropdown btn btn-gestor btn-primary" style="width: auto; margin: 0; border: none;">
                            <button type="button" id="dropdownMenuButton_licitacoes" data-toggle="dropdown" style="background-color: transparent; border: none;">
                                <i class="fa fa-download"></i> <?= lang('exportar') ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton_licitacoes" id="opcoes_exportacao_licitacoes" style="min-width: 100px; top: 62px; height: 105px; padding: 10px; border: none; color: black;">
                            </div>
                        </div>

                        <a class="btn btn-gestor btn-primary" id="exibir_dashboard" onclick="exibir_dashboard()" title="Exibir dashboard">
                            <i class="fa fa-bar-chart"></i> <span>Dashboard</span>
                        </a>

                    </div>

                    <div id="loadingMessage" class="loadingMessage" style="display: none; margin-top: 35px;">
                        <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                    </div>

                    <div id="table-related" style="display: flex; flex-direction: column;">
                        <div id="filterAndPaginationDiv">
                            <select id="select-quantidade-por-pagina" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <label for="select-quantidade-por-pagina" style="float: left; margin-top: 18px; margin-left: 10px; color: #7F7F7F; font-size: 15px;">Registros
                                por página</label>
                            <input type="text" id="search-input" placeholder="Buscar" style="float: right; margin: 10px; height:30px; padding-left: 10px;">

                        </div>
                        <div class="wrapperAcompanhamento" style='margin-top: 20px;'>
                            <div id="tableAcompanhamento" class="ag-theme-alpine my-grid-acompanhamento">
                            </div>
                        </div>
                    </div>

                    <div id="div2" style="display:none">
                        <center id="load_dash">
                            <i class="fa fa-spinner fa-spin fa-fw" style="font-size: 50px; "></i>
                        </center>

                        <body onload="displayLineChart();">
                            <div style="display: flex; flex-direction: column; gap: 20px; justify-content: center; width: 100%;">
                                <div id="quadros_dash" style="display:none">
                                    <div class="container" style="border-radius: 5px;font-size: 14px; width: 100%; text-align:center; align-content: center;">
                                        <h4>Esferas</h4>
                                        <div class="information-card">
                                            <div class="inner-item span4" style="text-align: center;">
                                                Municipal
                                                <br>
                                                <span id="esfera_municipal" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center; justify-items: center;">
                                                Estadual
                                                <br>
                                                <span id="esfera_estadual" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center;">
                                                Federal
                                                <br>
                                                <span id="esfera_federal" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container" style="border-radius: 5px;font-size: 14px; width: 100%; text-align:center;">
                                        <h4>Tipo</h4>
                                        <div class="information-card">
                                            <div class="inner-item span4" style="text-align: center;">
                                                Presencial
                                                <br>
                                                <span id="tipo_presencial" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center;">
                                                Eletrônico
                                                <br>
                                                <span id="tipo_eletrônico" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center;">
                                                Carona
                                                <br>
                                                <span id="tipo_carona" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container" style="border-radius: 5px;font-size: 14px; width: 100%; text-align:center;">
                                        <h4>Total licitações</h4>
                                        <div class="information-card">
                                            <div class="inner-item span12" id="total-licitacoes-div" style="text-align: center;">
                                                <span id="total_licitacoes" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="chart-plot-container">
                                    <div class="box chart-plot container" style="margin-top: 10px;">
                                        <canvas id="myChart1" onclick="abrirModalVeiculos()" style="position: relative; height:26rem; width:30rem;"></canvas>
                                    </div>
                                    <div class="box chart-plot container" style="margin-top: 10px;">
                                        <canvas id="myChart2" onclick="abrirModalLicitacoes()" style="position: relative; height:26rem; width:30rem;"></canvas>
                                    </div>
                                    <div class="box chart-plot container" style="margin-top: 10px;">
                                        <canvas id="myChart3" onclick="abrirModalValores()" style="position: relative; height:26rem; width:30rem;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </body>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modalAddLicitacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form_licitacao">
                <div class="modal-header header-layout">
                    <button type="button" id="close" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleModalProduto">Cadastrar Licitação</h3>
                </div>
                <div class="modal-body">
                    <div class="modal-body-container">
                        <ul id="abasModalProduto" class="custom-tabs nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active-tab" href="#" data-target="#abaDadosPessoais">Dados Licitação</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-target="#abaDadosContrato">Contrato</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-target="#abaValoresContrato">Valores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-target="#abaContato">Pós Etapa de lances</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="abasModalProdutoContent">
                            <div class="custom-tab-pane active-pane" id="abaDadosPessoais">
                                <div class="col-md-12">
                                    <div class="row default-row">
                                        <div class="col-sm-12 form-input-container">
                                            <label>Orgão<span class="text-danger">*</span></label>
                                            <input id="orgao" type="text" class="form-control" name="orgao" placeholder="Digite o nome do orgão" required>
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Data da licitação<span class="text-danger">*</span></label>
                                            <input id="data_licitacao" type="date" class="form-control" name="data_licitacao" required>
                                        </div>
                                        <div class="col-md-6 default-column form-input-container">
                                            <label for="periodo">Estado<span class="text-danger">*</span></label>
                                            <select class="form-control" name="estado" id="estado" required>
                                                <option selected disabled>Escolha um estado</option>
                                                <option value="AL">AL</option>
                                                <option value="AP">AP</option>
                                                <option value="AM">AM</option>
                                                <option value="BA">BA</option>
                                                <option value="CE">CE</option>
                                                <option value="DF">DF</option>
                                                <option value="ES">ES</option>
                                                <option value="GO">GO</option>
                                                <option value="MA">MA</option>
                                                <option value="MT">MT</option>
                                                <option value="MS">MS</option>
                                                <option value="MG">MG</option>
                                                <option value="PA">PA</option>
                                                <option value="PB">PB</option>
                                                <option value="PR">PR</option>
                                                <option value="PE">PE</option>
                                                <option value="PI">PI</option>
                                                <option value="RJ">RJ</option>
                                                <option value="RN">RN</option>
                                                <option value="RS">RS</option>
                                                <option value="RO">RO</option>
                                                <option value="RR">RR</option>
                                                <option value="SC">SC</option>
                                                <option value="SP">SP</option>
                                                <option value="SE">SE</option>
                                                <option value="TO">TO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Esfera<span class="text-danger">*</span></label>
                                            <select class="form-control" name="esfera" id="esfera" required>
                                                <option selected disabled>Escolha uma esfera</option>
                                                <option value="0">Federal</option>
                                                <option value="1">Estadual</option>
                                                <option value="2">Municipal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Empresa<span class="text-danger">*</span></label>
                                            <select class="form-control" name="empresa" id="empresa" required>
                                                <option selected disabled>Escolha a empresa</option>
                                                <option value="0">Show</option>
                                                <option value="1">Norio</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Tipo<span class="text-danger">*</span></label>
                                            <select class="form-control" onchange="changeTipo(this);" name="tipo" id="tipo" required>
                                                <option selected disabled>Escolha o tipo</option>
                                                <option value="0">Presencial</option>
                                                <option value="1">Eletrônico</option>
                                                <option value="2">Carona</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-input-container" id="div_plataforma" style="display:none;">
                                            <label>Plataforma</label>
                                            <input id="plataforma" type="text" class="form-control" name="plataforma">
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="custom-tab-pane" id="abaDadosContrato">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6 form-input-container">
                                            <label>Tipo de contrato<span class="text-danger">*</span></label>
                                            <select class="form-control" name="tipo_contrato" id="tipo_contrato" required>
                                                <option selected disabled>Escolha um tipo de contrado</option>
                                                <option value="0">Licitação</option>
                                                <option value="1">Adesão à ata</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Quantidade de veículos</label>
                                            <input id="qtd_veiculos" class="form-control" placeholder="Digite a quantidade de veículos" type="text" onchange="changeVeic(this)" name="qtd_veiculos">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Descrição do serviço</label>
                                            <input id="descricao_servico" class="form-control" placeholder="Descreva o serviço" type="text" name="descricao_servico">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Ata de registro de preço<span class="text-danger">*</span></label>
                                            <select class="form-control" name="ata_registro_preco" id="ata_registro_preco" required>
                                                <option selected disabled>Escolha uma opção</option>
                                                <option value="0">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Situação preliminar</label>
                                            <select class="form-control" name="situacao_preliminar" id="situacao_preliminar">
                                                <option selected disabled>Escolha uma situação preliminar</option>
                                                <option value="2">Não Participou</option>
                                                <option value="3">Suspenso</option>
                                                <option value="4">Anulado</option>
                                                <option value="5">Em andamento</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="custom-tab-pane" id="abaValoresContrato">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6 form-input-container">
                                            <label>Meses</label>
                                            <input id="meses" type="text" placeholder="Digite a duração da licitação" class="form-control" onchange="changeVeic(this)" name="meses">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Valor unitário ref.</label>
                                            <input id="valor_unitario_ref" type="text" data-prefix="R$ " placeholder="Digite o valor unitário de referência" class="form-control" onchange="changeVeic(this)" name="valor_unitario_ref">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Valor instalação ref.</label>
                                            <input id="valor_instalacao_ref" type="text" data-prefix="R$ " placeholder="Digite o valor de instalação da referência" class="form-control" onchange="changeVeic(this)" name="valor_instalacao_ref">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Valor global ref.</label>
                                            <input id="valor_global_ref" type="text" data-prefix="R$ " placeholder="Digite o valor global da referência" class="form-control" onchange="changeVeic(this)" name="valor_global_ref">
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="custom-tab-pane" id="abaContato">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6 form-input-container">
                                            <label>Vencedor</label>
                                            <input id="vencedor" type="text" placeholder="Digite o nome do vencedor" class="form-control" name="vencedor">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Valor unitário arremate</label>
                                            <input id="valor_unitario_arremate" data-prefix="R$ " placeholder="Digite o valor unitário do arremate" type="text" class="form-control" onchange="changeVeic(this)" name="valor_unitario_arremate">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Valor instalação arremate</label>
                                            <input id="preco_instalacao" data-prefix="R$ " placeholder="Digite o valor de instalação do arremate" type="text" class="form-control" name="preco_instalacao" onchange="changeVeic(this)">
                                        </div>
                                        <div class="col-md-6 form-input-container">
                                            <label>Valor global arremate</label>
                                            <input id="valor_global_arremate" data-prefix="R$ " placeholder="Digite o valor global do arremate" type="text" class="form-control" onchange="changeVeic(this)" name="valor_global_arremate">
                                        </div>
                                        <div class="col-md-12 form-input-container">
                                            <label>Situação final</label>
                                            <select class="form-control" name="situacao_final" id="situacao_final">
                                                <option selected disabled>Escolha a situação final</option>
                                                <option value="0">Arrematado</option>
                                                <option value="1">Contrato Assinado</option>
                                                <option value="2">Perdido</option>
                                                <option value="3">Suspenso</option>
                                                <option value="4">Em andamento</option>
                                                <option value="5">Em período de teste</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-input-container">
                                            <label>Observações</label>
                                            <input id="observacoes" class="form-control" type="text" placeholder="Deixe alguma observação" name="observacoes" maxlength="150">
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnFechar">Fechar</button>
                        <button type="button" class="btn btn-success" id="submit">
                            <i id="load" class="fa fa-spinner fa-pulse fa-3x fa-fw display"></i>Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url('assets'); ?>/js/jquery.maskMoney.min.js" type="text/javascript"></script>
<!-- Script Dashboard -->
<script src="https://npmcdn.com/chart.js@2.7.2/dist/Chart.bundle.js"></script>
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.js" integrity="sha512-yfb1lLOhiYYJh7C3dsBE4XGCnDCEe4dJ/jdVgoinVdKwVuDP2SJqrEngf0Q+m6gaU8vOjCaJ0EaeakGzXXfWIA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js" integrity="sha512-cktKDgjEiIkPVHYbn8bh/FEyYxmt4JDJJjOCu5/FQAkW4bc911XtKYValiyzBiJigjVEvrIAyQFEbRJZyDA1wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/licitacoes', 'layout.css') ?>">

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>

<!-- Script Botões da Tabela -->
<script>
    $(document).ready(function() {
        var element = $('body > div:nth-child(5) > ul > li:nth-child(2) > a');
        if (element.length > 0) {
            element.attr("href", '<?= base_url() ?>index.php/licitacao/acompanhamento');
        }
    });

    var exibir = 0;

    function exibir_dashboard() {
        if ($('#table-related')[0].style.display == "flex") {
            $('#table-related')[0].style.display = "none";
            $('#div2')[0].style.display = null;
            $('#exibir_dashboard').html('<i class="fa fa-table"></i> <span>Relatórios</span>');
            $('#quadros_dash').addClass("informations container");
        } else {
            $('#div2')[0].style.display = "none";
            $('#table-related')[0].style.display = "flex";
            $('#exibir_dashboard').html('<i class="fa fa-bar-chart"></i> <span>Dashboard</span>');
            $('#quadros_dash').removeClass("informations container");
        }
    }
</script>

<script language="JavaScript">
    var myChart;

    function displayLineChart() {
        $.getJSON("<?= base_url() ?>index.php/licitacao/dash_acompanhamento", function(data) {
            $('#esfera_municipal')[0].innerHTML = data.qtd_esfera[2].qtd;
            $('#esfera_estadual')[0].innerHTML = data.qtd_esfera[1].qtd;
            $('#esfera_federal')[0].innerHTML = data.qtd_esfera[0].qtd;
            $('#load_dash')[0].style.display = "none";
            $('#quadros_dash')[0].style.display = null;

            $('#tipo_presencial')[0].innerHTML = data.qtd_tipo[0].qtd;
            $('#tipo_eletrônico')[0].innerHTML = data.qtd_tipo[1].qtd;
            $('#tipo_carona')[0].innerHTML = data.qtd_tipo[2].qtd;

            $('#total_licitacoes')[0].innerHTML = `${data.qtd_total}<span style="color:#0066ff; font-weight: bold; font-size: 20pt;"> x</span>`;

            var label_veiculo = [];
            var data_veiculo = [];
            $.each(data.grafico_veiculos, function(key, val) {
                label_veiculo.push(val.situacao_final);
                data_veiculo.push(val.qtd);
            });
            new Chart(document.getElementById("myChart1"), {
                type: 'bar',
                data: {
                    labels: label_veiculo,
                    datasets: [{
                        label: "Quantidade de Veículos",
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#c42840", "#e8c3b9", "#c45850", "#a98585"],
                        data: data_veiculo
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Quantidade de veículos'
                    }
                }
            });

            var label_licitacao = [];
            var data_licitacao = [];
            $.each(data.grafico_licitacao, function(key, val) {
                label_licitacao.push(val.situacao_final);
                data_licitacao.push(val.qtd);
            });
            new Chart(document.getElementById("myChart2"), {
                type: 'bar',
                data: {
                    labels: label_licitacao,
                    datasets: [{
                        label: "Quantidade de licitações",
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#c42840", "#e8c3b9", "#c45850", "#a98585"],
                        data: data_licitacao
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Quantidade de licitações'
                    }
                }
            });

            var label_valores = [];
            var data_valores = [];
            $.each(data.grafico_valor, function(key, val) {
                label_valores.push(val.situacao_final);
                data_valores.push(val.qtd);
            });
            new Chart(document.getElementById("myChart3"), {
                type: 'bar',
                data: {
                    labels: label_valores,
                    datasets: [{
                        label: "Valores",
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#c42840", "#e8c3b9", "#c45850", "#a98585"],
                        data: data_valores
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Valores'
                    }
                }
            });

        });
    }

    function abrirModalVeiculos() {
        $('#modalCharts1').modal('show');

        $("#loadingMessage1").show();

        $.getJSON("<?= base_url() ?>index.php/licitacao/getGraficoVeiculosJson", function(data) {
            var label_veiculo = [];
            var data_veiculo = [];
            $.each(data.grafico_veiculos, function(key, val) {
                label_veiculo.push(val.situacao_final);
                data_veiculo.push(val.qtd);
            });
            new Chart(document.getElementById("myChartModal1"), {
                type: 'bar',
                data: {
                    labels: label_veiculo,
                    datasets: [{
                        label: "Quantidade de Veículos",
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#c42840", "#e8c3b9", "#c45850", "#a98585"],
                        data: data_veiculo
                    }]
                },
            });
            $("#loadingMessage1").hide();
        });



    }

    function abrirModalLicitacoes() {
        $('#modalCharts2').modal('show');

        $("#loadingMessage2").show();

        $.getJSON("<?= base_url() ?>index.php/licitacao/getGraficoLicitacoesJson", function(data) {
            var label_licitacao = [];
            var data_licitacao = [];
            $.each(data.grafico_licitacao, function(key, val) {
                label_licitacao.push(val.situacao_final);
                data_licitacao.push(val.qtd);
            });
            new Chart(document.getElementById("myChartModal2"), {
                type: 'bar',
                data: {
                    labels: label_licitacao,
                    datasets: [{
                        label: "Quantidade de licitações",
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#c42840", "#e8c3b9", "#c45850", "#a98585"],
                        data: data_licitacao
                    }]
                }
            });
            $("#loadingMessage2").hide();
        });
    }

    function abrirModalValores() {
        $('#modalCharts3').modal('show');

        $("#loadingMessage3").show();

        $.getJSON("<?= base_url() ?>index.php/licitacao/getGraficoValoresJson", function(data) {
            var label_valores = [];
            var data_valores = [];
            $.each(data.grafico_valor, function(key, val) {
                label_valores.push(val.situacao_final);
                data_valores.push(val.qtd);
            });
            new Chart(document.getElementById("myChartModal3"), {
                type: 'bar',
                data: {
                    labels: label_valores,
                    datasets: [{
                        label: "Valores",
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#c42840", "#e8c3b9", "#c45850", "#a98585"],
                        data: data_valores
                    }]
                }
            });

            $("#loadingMessage3").hide();
        });
    }
</script>

<!-- Tabela AgGrid -->
<script>
    var BaseURL = '<?= base_url('') ?>';
    var Router = '<?= site_url('licitacoes') ?>';
    var localeText = AG_GRID_LOCALE_PT_BR;

    $(document).ready(function() {
        atualizarAgGrid();
    });

    let convertToReal = new Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL',
    });

    var esfera = function(item) {
        let esferaL = null

        if (item.esfera == 0) {
            esferaL = "Federal";
        } else if (item.esfera == 1) {
            esferaL = "Estadual";
        } else if (item.esfera == 2) {
            esferaL = "Municipal";
        }

        return esferaL;
    };

    var empresa = function(item) {
        let empresaL = null
        if (item.empresa == 1) {
            empresaL = "Norio Momoi";
        } else {
            empresaL = "Show Tecnologia";
        }

        return empresaL;
    };

    var tipo = function(item) {
        let tipoL = null
        if (item.tipo == 0) {
            tipoL = "Presencial";
        } else if (item.tipo == 1) {
            tipoL = "Eletrônico";
        } else if (item.tipo == 2) {
            tipoL = "Carona";
        }

        return tipoL;
    };

    var tipoContrato = function(item) {
        let tipoContratoL = null
        if (item.tipo_contrato == 0) {
            tipoContratoL = "Licitação";
        } else if (item.tipo_contrato == 1) {
            tipoContratoL = "Adesão à ata";
        }

        return tipoContratoL;
    };

    var ataRegistroPreco = function(item) {
        let ataRegistroPrecoL = null
        if (item.ata_registro_preco == 0) {
            ataRegistroPrecoL = "Não";
        } else if (item.ata_registro_preco == 1) {
            ataRegistroPrecoL = "Sim";
        }

        return ataRegistroPrecoL;
    };

    var situacaoPreliminar = function(item) {
        let situacaoPreliminarL = null

        if (item.situacao_preliminar == 0) {
            situacaoPreliminarL = "Arrematado";
        } else if (item.situacao_preliminar == 1) {
            situacaoPreliminarL = "Perdido";
        } else if (item.situacao_preliminar == 2) {
            situacaoPreliminarL = "Não Participou";
        } else if (item.situacao_preliminar == 3) {
            situacaoPreliminarL = "Suspenso";
        } else if (item.situacao_preliminar == 4) {
            situacaoPreliminarL = "Anulado";
        } else if (item.situacao_preliminar == 5) {
            situacaoPreliminarL = "Em andamento";
        }

        return situacaoPreliminarL;
    };

    var situacaoFinal = function(item) {
        let situacaoFinalL = null

        if (item.situacao_final == 0) {
            situacaoFinalL = "Aguardando";
        } else if (item.situacao_final == 1) {
            situacaoFinalL = "Contrato Assinado";
        } else if (item.situacao_final == 2) {
            situacaoFinalL = "Perdido";
        } else if (item.situacao_final == 3) {
            situacaoFinalL = "Suspenso";
        } else if (item.situacao_final == 4) {
            situacaoFinalL = "Em andamento";
        }

        return situacaoFinalL;
    };

    $("#loadingMessage").show();

    function corrigirDataHora(dataHora) {
        const dataHoraCorrigida = new Date(dataHora);

        dataHoraCorrigida.setHours(dataHoraCorrigida.getHours() + 3);

        return dataHoraCorrigida.toLocaleDateString('pt-BR', {
            timezone: 'UTC'
        });
    }

    var AgGridLicitacoes;
    var DadosAgGrid = [];

    function atualizarAgGrid() {
        $.ajax({
            url: '<?= base_url() ?>index.php/licitacao/getLicitacoesJson',
            type: 'POST',
            dataType: 'json',
            beforeSend: function(param) {
                $("#loadingMessage").show();
            },
            success: function(data) {
                var dadosMapeados = data.map(function(item) {
                    return {
                        ID: item.id || '-',
                        Orgao: item.orgao || '-',
                        Data_licitacao: corrigirDataHora(item.data_licitacao) || '-',
                        Estado: item.estado || '-',
                        Esfera: esfera(item) || '-',
                        Empresa: empresa(item) || '-',
                        Tipo: tipo(item) || '-',
                        Tipo_contrato: tipoContrato(item) || '-',
                        Ata_registro_precos: ataRegistroPreco(item) || '-',
                        Plataforma: item.plataforma || '-',
                        Quantidade_veiculos: item.qtd_veiculos || '-',
                        Valor_unitario_ref: convertToReal.format(item.valor_unitario_ref) || '-',
                        Valor_global_ref: convertToReal.format(item.valor_global_ref) || '-',
                        Valor_uni_arremate: convertToReal.format(item.valor_unitario_arremate) || '-',
                        Valor_global_arremate: convertToReal.format(item.valor_global_arremate) || '-',
                        Valor_instalacao: convertToReal.format(item.preco_instalacao) || '-',
                        Descricao_servico: item.descricao_servico || '-',
                        Vencedor: item.vencedor || '-',
                        Status_preliminar: situacaoPreliminar(item) || '-',
                        Status_final: situacaoFinal(item) || '-',
                        Observacoes: item.observacoes || '-',
                    };
                });

                stopAgGRID();

                const gridOptions = {
                    columnDefs: [{
                            headerName: 'ID',
                            field: 'ID',
                            chartDataType: 'category'
                        },
                        {
                            headerName: 'Orgão',
                            field: 'Orgao',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Data da Licitação',
                            field: 'Data_licitacao',
                            chartDataType: 'date'
                        },
                        {
                            headerName: 'Estado',
                            field: 'Estado',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Esfera',
                            field: 'Esfera',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Empresa',
                            field: 'Empresa',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Tipo',
                            field: 'Tipo',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Tipo de contrato',
                            field: 'Tipo_contrato',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Ata de registro de preços',
                            field: 'Ata_registro_precos',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Plataforma',
                            field: 'Plataforma',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Quantidade de veículos',
                            field: 'Quantidade_veiculos',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Valor unitário ref.',
                            field: 'Valor_unitario_ref',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Valor Global ref.',
                            field: 'Valor_global_ref',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Valor unitário Arremate',
                            field: 'Valor_uni_arremate',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Valor Global Arremate',
                            field: 'Valor_global_arremate',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Valor Instalação',
                            field: 'Valor_instalacao',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Descrição do serviço',
                            field: 'Descricao_servico',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Vencedor',
                            field: 'Vencedor',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Status preliminar',
                            field: 'Status_preliminar',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Status final',
                            field: 'Status_final',
                            chartDataType: 'series'
                        },
                        {
                            headerName: 'Observações',
                            field: 'Observacoes',
                            chartDataType: 'series'
                        },
                    ],
                    defaultColDef: {
                        enablePivot: true,
                        editable: false,
                        sortable: true,
                        minWidth: 100,
                        minHeight: 100,
                        filter: true,
                        resizable: true,
                    },
                    statusBar: {
                        statusPanels: [{
                                statusPanel: 'agTotalRowCountComponent',
                                align: 'right'
                            },
                            {
                                statusPanel: 'agFilteredRowCountComponent'
                            },
                            {
                                statusPanel: 'agSelectedRowCountComponent'
                            },
                            {
                                statusPanel: 'agAggregationComponent'
                            },
                        ],
                    },
                    localeText: localeText,
                    popupParent: document.body,
                    enableRangeSelection: true,
                    enableCharts: true,
                    domLayout: 'autoHeight',
                    pagination: true,
                    paginationPageSize: 10,
                    paginationPageSizeSelector: false
                };

                var gridDiv = document.querySelector('#tableAcompanhamento');
                AgGridLicitacoes = new agGrid.Grid(gridDiv, gridOptions);

                gridOptions.api.setRowData(dadosMapeados);

                gridOptions.quickFilterText = '';

                document.querySelector('#search-input').addEventListener('input', function() {
                    var searchInput = document.querySelector('#search-input');
                    gridOptions.api.setQuickFilter(searchInput.value);
                });

                document.querySelector('#select-quantidade-por-pagina').addEventListener('change', function() {
                    var selectedValue = document.querySelector('#select-quantidade-por-pagina').value;
                    gridOptions.api.paginationSetPageSize(Number(selectedValue));
                });

                preencherExportacoes(gridOptions);
                $("#loadingMessage").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Erro na requisição AJAX:', textStatus, errorThrown);
                atualizarAgGrid([]);
                $("#loadingMessage").hide();
            },
            complete: function(param) {
                $("#loadingMessage").hide();
            }
        });
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#tableAcompanhamento');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapperAcompanhamento');
        if (wrapper) {
            wrapper.innerHTML = '<div id="tableAcompanhamento" class="ag-theme-alpine my-grid-acompanhamento"></div>';
        }
    }

    function preencherExportacoes(gridOptions) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao_licitacoes');
        const opcoes = ['csv', 'excel', 'pdf'];

        let buttonCSV = "<?php echo base_url('media/img/new_icons/csv.png') ?>";
        let buttonEXCEL = "<?php echo base_url('media/img/new_icons/excel.png') ?>";
        let buttonPDF = "<?php echo base_url('media/img/new_icons/pdf.png') ?>";

        formularioExportacoes.innerHTML = '';

        opcoes.forEach(opcao => {
            let button = '';
            let texto = '';
            let margin;
            switch (opcao) {
                case 'csv':
                    button = buttonCSV;
                    texto = 'CSV';
                    margin = '-5px';
                    break;
                case 'excel':
                    button = buttonEXCEL;
                    texto = 'Excel';
                    margin = '0px';
                    break;
                case 'pdf':
                    button = buttonPDF;
                    texto = 'PDF';
                    margin = '0px';
                    break;
            }

            let div = document.createElement('div');
            div.classList.add('dropdown-item');
            div.classList.add('opcao_exportacao');
            div.setAttribute('data-tipo', opcao);
            div.innerHTML = `
                        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer; border: none;" title="Exportar no formato ${texto}">
                        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
                `;

            div.style.height = '30px';

            div.style.marginTop = margin;

            div.style.borderRadius = '1px';

            div.style.transition = 'background-color 0.3s ease';

            div.addEventListener('mouseover', function() {
                div.style.backgroundColor = '#f0f0f0';
            });

            div.addEventListener('mouseout', function() {
                div.style.backgroundColor = '';
            });

            //div.style.border = '1px solid #ccc';

            div.addEventListener('click', function(event) {
                event.preventDefault();
                exportarArquivo(opcao, gridOptions);
            });

            formularioExportacoes.appendChild(div);
        });
    }

    function exportarArquivo(tipo, gridOptions) {

        let fileName;
        switch (tipo) {
            case 'csv':
                fileName = 'licitações.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'licitações.xlsx';
                gridOptions.api.exportDataAsExcel({
                    fileName: fileName
                });
                break;
            case 'pdf':
                let dadosExportacao = prepararDadosExportacaoRelatorio();

                let definicoesDocumento = getDocDefinition(
                    printParams('A4'),
                    gridOptions.api,
                    gridOptions.columnApi,
                    dadosExportacao.informacoes,
                    dadosExportacao.rodape
                );
                pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
                break;

        }


    }

    function prepararDadosExportacaoRelatorio() {
        let informacoes = DadosAgGrid.map((item) => ({
            ID: item.id,
            Orgao: item.orgao,
            Data_licitacao: item.data_licitacao,
            Estado: item.estado,
            Esfera: item.esfera,
            Empresa: item.empresa,
            Tipo: item.tipo,
            Tipo_contrato: item.tipo_contrato,
            Ata_registro_precos: item.ata_registro_preco,
            Plataforma: item.plataforma,
            Quantidade_veiculos: item.qtd_veiculos,
            Valor_unitario_ref: item.valor_unitario_ref,
            Valor_global_ref: item.valor_global_ref,
            Valor_uni_arremate: item.valor_unitario_arremate,
            Valor_global_arremate: item.valor_global_arremate,
            Valor_instalacao: item.preco_instalacao,
            Descricao_servico: item.descricao_servico,
            Vencedor: item.vencedor,
            Status_preliminar: item.situacao_preliminar,
            Status_final: item.situacao_final,
            Observacoes: item.observacoes,
        }));

        let rodape = `Licitações`;
        let nomeArquivo = `Licitações.pdf`;

        return {
            informacoes,
            nomeArquivo,
            rodape
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('opcoes_exportacao_licitacoes');

        document.getElementById('dropdownMenuButton_licitacoes').addEventListener('click', function() {
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButton_licitacoes') {
                dropdown.style.display = 'none';
            }
        });
    });

    function printParams(pageSize) {
        return {
            PDF_HEADER_COLOR: "#ffffff",
            PDF_INNER_BORDER_COLOR: "#dde2eb",
            PDF_OUTER_BORDER_COLOR: "#babfc7",
            PDF_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
            PDF_HEADER_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
            PDF_ODD_BKG_COLOR: "#fff",
            PDF_EVEN_BKG_COLOR: "#F3F3F3",
            PDF_PAGE_ORITENTATION: "landscape",
            PDF_WITH_FOOTER_PAGE_COUNT: true,
            PDF_HEADER_HEIGHT: 25,
            PDF_ROW_HEIGHT: 25,
            PDF_WITH_CELL_FORMATTING: true,
            PDF_WITH_COLUMNS_AS_LINKS: false,
            PDF_SELECTED_ROWS_ONLY: false,
            PDF_PAGE_SIZE: pageSize,
        }
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll("#abasModalProduto .nav-link");
        const tabContents = document.querySelectorAll(".custom-tab-pane");

        tabs.forEach(tab => {
            tab.addEventListener("click", function(e) {
                e.preventDefault();
                const target = this.getAttribute("data-target");

                tabContents.forEach(content => {
                    content.style.display = "none";
                });

                tabs.forEach(tab => {
                    tab.classList.remove("active-tab");
                });

                document.querySelector(target).style.display = "block";

                this.classList.add("active-tab");
            });
        });

        $('#modalAddLicitacao').on('show.bs.modal', function() {
            document.querySelector("#abasModalProduto .nav-link").click();
        });
    });

    $(document).ready(function() {
        $('#adicionarLicitacao').on('click', function() {
            $('#modalAddLicitacao').modal('show');
        });

        $("#estado").select2({
            placeholder: "Escolha um estado",
            allowClear: true,
        });

        $("#modalAddLicitacao").on("hide.bs.modal", function(e) {
            document.getElementById("form_licitacao").reset();
            $("#estado").val(null).trigger("change");
        });

        window.changeTipo = function(e) {
            $('#div_plataforma').toggle(e.value !== "0");
        };

        window.changeVeic = function(e) {
            var qtdVeiculos = parseFloat(formatoFloat($("#qtd_veiculos").val()));
            var meses = parseInt($('#meses').val());

            if (!qtdVeiculos) return;

            if (formatoFloat($("#valor_unitario_ref").val()) && e.id !== "valor_global_ref") {
                var instalacao = parseFloat(formatoFloat($("#valor_instalacao_ref").val())) * qtdVeiculos || 0;
                var valorTotal = (parseFloat(formatoFloat($("#valor_unitario_ref").val())) * qtdVeiculos * meses) + instalacao || 0;
                $("#valor_global_ref").maskMoney('mask', valorTotal);
            } else if (formatoFloat($("#valor_global_ref").val())) {
                $("#valor_instalacao_ref").maskMoney('mask', 0);
                var unitarioCalc = (parseFloat(formatoFloat($("#valor_global_ref").val())) / qtdVeiculos / meses).toFixed(2);
                $("#valor_unitario_ref").maskMoney('mask', unitarioCalc);
            }

            if (formatoFloat($("#valor_unitario_arremate").val()) && e.id !== "valor_global_arremate") {
                var instalacao = parseFloat(formatoFloat($("#preco_instalacao").val())) * qtdVeiculos || 0;
                var valorArremate = (parseFloat(formatoFloat($("#valor_unitario_arremate").val())) * qtdVeiculos * meses) + instalacao || 0;
                $("#valor_global_arremate").maskMoney('mask', valorArremate);
            } else if (formatoFloat($("#valor_global_arremate").val())) {
                $("#preco_instalacao").maskMoney('mask', 0);
                var unitarioCalc = (parseFloat(formatoFloat($("#valor_global_arremate").val())) / qtdVeiculos / meses).toFixed(2);
                $("#valor_unitario_arremate").maskMoney('mask', unitarioCalc);
            }
        };

        $('#valor_unitario_ref, #valor_global_ref, #valor_instalacao_ref, #valor_global_arremate, #valor_unitario_arremate, #preco_instalacao').maskMoney();

        function formatoFloat(valor) {
            if (valor) {
                return valor.replace('R$ ', '').replace(/,/g, '').replace(/\./g, '');
            }
            return 0;
        }

        window.alterar_formato_dinheiro = function() {
            $('#preco_instalacao, #valor_unitario_arremate, #valor_global_arremate, #valor_global_ref, #valor_instalacao_ref, #valor_unitario_ref').each(function() {
                this.value = formatoFloat(this.value);
            });
        };

        $('#submit').on('click', function() {
            alterar_formato_dinheiro();

            var formData = {
                orgao: $('#orgao').val(),
                data_licitacao: $('#data_licitacao').val(),
                estado: $('#estado').val(),
                esfera: $('#esfera').val(),
                empresa: $('#empresa').val(),
                tipo: $('#tipo').val(),
                plataforma: $('#plataforma').val(),
                tipo_contrato: $('#tipo_contrato').val(),
                qtd_veiculos: $('#qtd_veiculos').val(),
                descricao_servico: $('#descricao_servico').val(),
                ata_registro_preco: $('#ata_registro_preco').val(),
                situacao_preliminar: $('#situacao_preliminar').val(),
                meses: $('#meses').val(),
                valor_unitario_ref: $('#valor_unitario_ref').val(),
                valor_instalacao_ref: $('#valor_instalacao_ref').val(),
                valor_global_ref: $('#valor_global_ref').val(),
                vencedor: $('#vencedor').val(),
                valor_unitario_arremate: $('#valor_unitario_arremate').val(),
                preco_instalacao: $('#preco_instalacao').val(),
                valor_global_arremate: $('#valor_global_arremate').val(),
                situacao_final: $('#situacao_final').val(),
                observacoes: $('#observacoes').val()
            };

            if (!formData.orgao) {
                showAlert("warning", "O campo 'Órgão' é obrigatório!");
                return;
            }
            if (!formData.data_licitacao) {
                showAlert("warning", "O campo 'Data da Licitação' é obrigatório!");
                return;
            }
            if (!formData.estado) {
                showAlert("warning", "O campo 'Estado' é obrigatório!");
                return;
            }
            if (!formData.esfera) {
                showAlert("warning", "O campo 'Esfera' é obrigatório!");
                return;
            }
            if (!formData.empresa) {
                showAlert("warning", "O campo 'Empresa' é obrigatório!");
                return;
            }
            if (!formData.tipo) {
                showAlert("warning", "O campo 'Tipo' é obrigatório!");
                return;
            }
            if (!formData.tipo_contrato) {
                showAlert("warning", "O campo 'Tipo de Contrato' é obrigatório!");
                return;
            }
            if (!formData.ata_registro_preco) {
                showAlert("warning", "O campo 'Ata de Registro de Preços' é obrigatório!");
                return;
            }

            $("#submit").attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '<?= base_url() ?>index.php/licitacao/add',
                method: 'post',
                data: formData,
                success: function(response) {
                    if (response == true) {
                        $('#modalAddLicitacao').modal('hide');
                        document.getElementById("form_licitacao").reset();
                        atualizarAgGrid();
                        showAlert('success', 'Licitação cadastrada com sucesso!');
                    } else {
                        showAlert('error', 'Não foi possível cadastrar a licitação!');
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('error', 'Ocorreu um erro ao cadastrar a licitação.');
                },
                complete: function() {
                    $("#submit").attr("disabled", false).html('Salvar');
                }
            });
        });
    });
</script>