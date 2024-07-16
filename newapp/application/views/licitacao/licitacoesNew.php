<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;">Licitações</h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('cadastros') ?> >
        <?= lang('licitacoes') ?>
    </h4>
</div>
<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class="menu-interno-link selected" id="menu-relatorio">Relatório</a>
                </li>
                <li>
                    <a class="menu-interno-link" id="menu-dashboard">Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-relatorio" style="margin-bottom: 20px; position: relative;">
            <div class="tablePageHeader">
                <h3><b>Relatório:</b></h3>
                <div class="d-flex align-items-center" id="internal-navbar" style="display: inline-flex; gap: 10px; justify-content: end; margin: 10px 0px 10px;">
                    <button id="adicionarLicitacao" style="height: 36.5px;" class="btn btn-primary" title="Adicionar">
                        <i class="fa fa-plus"></i> Adicionar
                    </button>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button onclick="$('.dropdown-menu-acoes').hide();" class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;"></div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?= lang('expandir_grid') ?>" style="border-radius: 6px; padding: 5px;">
                        <img class="img-expandir" src="<?= base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
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
                    <label for="select-quantidade-por-pagina" style="float: left; margin-top: 18px; margin-left: 10px; color: #7F7F7F; font-size: 15px;">Registros por página</label>
                    <input type="text" id="search-input" placeholder="Buscar" style="float: right; margin: 10px 0; height: 30px; padding-left: 10px;">
                </div>
                <div class="wrapperAcompanhamento" style="margin-top: 20px;">
                    <div id="tableAcompanhamento" class="ag-theme-alpine my-grid-acompanhamento"></div>
                </div>
            </div>
        </div>
        <div class="card-conteudo card-dashboard" style="margin-bottom: 20px; position: relative;">
            <div class="tablePageHeader">
                <h3><b>Dashboard:</b></h3>
                <div class="d-flex align-items-center" id="internal-navbar" style="display: inline-flex; gap: 10px; justify-content: end; margin: 10px 0px 10px;">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?= lang('expandir_grid') ?>" style="border-radius: 6px; padding: 5px;">
                        <img class="img-expandir" src="<?= base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </div>
            <div id="quadros_dash" style="max-width: fit-content; display: contents; width: 100%; margin: 0;" class="container">
                <div class="dashboard-section">
                    <h4>Esferas</h4>
                    <div class="information-card">
                        <div class="inner-item card metrica-card">
                            <div class="card-header">
                                <img src="http://localhost/shownet/newapp/assets/images/file-signature-solid.svg" class="metrica-img2">
                                <p id="esfera_municipal" class="dashboard-number"></p>
                            </div>
                            <div class="card-body">
                                <p>Municipal</p>
                            </div>
                        </div>
                        <div class="inner-item card metrica-card">
                            <div class="card-header">
                                <img src="http://localhost/shownet/newapp/assets/images/file-signature-solid.svg" class="metrica-img2">
                                <p id="esfera_estadual" class="dashboard-number"></p>
                            </div>
                            <div class="card-body">
                                <p>Estadual</p>
                            </div>
                        </div>
                        <div class="inner-item card metrica-card">
                            <div class="card-header">
                                <img src="http://localhost/shownet/newapp/assets/images/file-signature-solid.svg" class="metrica-img2">
                                <p id="esfera_federal" class="dashboard-number"></p>
                            </div>
                            <div class="card-body">
                                <p>Federal</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-section">
                    <h4>Tipo</h4>
                    <div class="information-card">
                        <div class="inner-item card metrica-card">
                            <div class="card-header">
                                <img src="http://localhost/shownet/newapp/assets/images/file-signature-solid.svg" class="metrica-img2">
                                <p id="tipo_presencial" class="dashboard-number"></p>
                            </div>
                            <div class="card-body">
                                <p>Presencial</p>
                            </div>
                        </div>
                        <div class="inner-item card metrica-card">
                            <div class="card-header">
                                <img src="http://localhost/shownet/newapp/assets/images/file-signature-solid.svg" class="metrica-img2">
                                <p id="tipo_eletrônico" class="dashboard-number"></p>
                            </div>
                            <div class="card-body">
                                <p>Eletrônico</p>
                            </div>
                        </div>
                        <div class="inner-item card metrica-card">
                            <div class="card-header">
                                <img src="http://localhost/shownet/newapp/assets/images/file-signature-solid.svg" class="metrica-img2">
                                <p id="tipo_carona" class="dashboard-number"></p>
                            </div>
                            <div class="card-body">
                                <p>Carona</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-section">
                    <h4>Total licitações</h4>
                    <div class="information-card">
                        <div class="inner-item card metrica-card" id="total-licitacoes-div">
                            <div class="card-header">
                                <img src="http://localhost/shownet/newapp/assets/images/file-signature-solid.svg" class="metrica-img2">
                                <p id="total_licitacoes" class="dashboard-number"></p>
                            </div>
                            <div class="card-body">
                                <p>Total Licitações</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chartPlotContainer" style="flex-wrap: wrap;">
                <div class="chartContainer container" style="margin-top: 10px;" onclick="abrirModalVeiculos()">
                    <div class="container chartHeader">
                        <charttitle>Quantidade de Veículos</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="myChart1" class="chart-container"></div>
                    </div>
                </div>
                <div class="chartContainer container" style="margin-top: 10px;" onclick="abrirModalLicitacoes()">
                    <div class="container chartHeader">
                        <charttitle>Quantidade de Licitações</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="myChart2" class="chart-container"></div>
                    </div>
                </div>
                <div class="chartContainer container" style="margin-top: 10px;" onclick="abrirModalValores()">
                    <div class="container chartHeader">
                        <charttitle>Valores</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="myChart3" class="chart-container"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Grafico Veiculos -->
<div id="modalCharts1" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage1" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Quantidade de veículos</h3>
            </div>
            <div class="modal-body">
                <div id="myChartModal1" class="chart-container"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success downloadChartBtn">Baixar</button>
            </div>
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
                <h3 class="modal-title">Quantidade de licitações</h3>
            </div>
            <div class="modal-body">
                <div id="myChartModal2" class="chart-container"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success downloadChartBtn">Baixar</button>
            </div>
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
                <h3 class="modal-title">Valores</h3>
            </div>
            <div class="modal-body">
                <div id="myChartModal3" class="chart-container"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success downloadChartBtn">Baixar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Licitacao -->
<div id="modalAddLicitacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form_licitacao">
                <div class="modal-header header-layout">
                    <button type="button" id="close" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Cadastrar Licitação</h3>
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

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnFechar">Fechar</button>
                        <button type="button" class="btn btn-success" id="submit">
                            Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var BaseURL = '<?= base_url('') ?>';
    var Router = '<?= site_url('licitacao') ?>';
</script>

<script type="text/javascript" src="<?= base_url('assets/js/jquery.mask.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.maskMoney.min.js') ?>" type="text/javascript"></script>
<!-- Script Dashboard -->

<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/licitacoes', 'layout.css') ?>">

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/licitacao', 'index.js') ?>"></script>