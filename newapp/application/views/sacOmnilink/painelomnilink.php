<script>
    // url do controller painel_omnilink
    const URL_PAINEL_OMNILINK = '<?= site_url("PaineisOmnilink") ?>';
    // url da api crmintegration
    const URL_API = '<?= $this->config->item("base_url_api_crmintegration") . "/crmintegration/api/" ?>';
    const EMAIL_USUARIO = '<?= $this->auth->get_login_dados('email') ?>';
    const NOME_USUARIO = '<?= $this->auth->get_login_dados('nome') ?>'
    const URL_API_COMUNICACAO_CHIP = '<?= $this->config->item("url_api_shownet_rest") ?>';
    //url da apisaver
    const URL_REQUEST_IRIDIUM = '<?= $this->config->item("url_apishownet") ?>';
    const URL_API_INFOMACOES_MHS = '<?= $this->config->item("url_api_informacoes_mhs") ?>';
    var clientes = <?= json_encode($clientes) ?>
    //envio de email
    const TOKEN_API_EMAIL = '<?= $this->config->item("token_api_email") ?>';
    const URL_API_EMAIL = '<?= $this->config->item("url_api_email") ?>';
</script>

<!-- STYLES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="<?php echo base_url('newAssets/css/jquery-ui.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('newAssets/css/painelomnilink.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap.min.css">
<link href="<?php echo base_url('newAssets/css/painelsuporteomnilink.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('newAssets/css/notepad.css') ?>" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

<style>
    .nav-tabs>li.active>a {
        color: #06a9f6 !important;
    }

    .select2-selection__rendered {
        line-height: 31px !important;
    }

    .select2-container .select2-selection--single {
        height: 31px !important;
    }

    .select2-selection__arrow {
        height: 31px !important;
    }

    .select2-selection__clear {
        height: 31px !important;
    }

    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }


    table.dataTable>th,
    table.dataTable>td {
        word-wrap: break-word !important;
        max-width: 300px !important;
    }

    table.dataTable>tbody>tr.child ul.dtr-details>li {
        word-wrap: break-word !important;
    }
</style>

<!-- FILTRO CNPJ -->
<div class="row margin_bottom_20">
    <div class="col-md-5 titulo">
        <?= $titulo ?>
    </div>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div style="color: black !important" class="div-caminho-menus-pais">
    <a href="<?= site_url('Homes') ?>">Home</a> >
    <?= lang('sac_omnilink') ?>
</div>

<!-- BLOCO DE NOTAS -->
<div class="panel panel-primary notepad">
    <div class="panel-heading">
        <i class="fa fa-file-text-o notepad-title-icon" aria-hidden="true"></i>
        <h3 class="panel-title notepad-title">Bloco de Notas</h3>
        <button type="button" class="close notepad-close-button" title="Fechar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="panel-body">
        <textarea class="form-control notepad-control" rows="10"></textarea>
    </div>
</div>
<!-- BOTÃO PARA ABRIR O BLOCO DE NOTAS -->
<button id="notepadOpenButton" type="submit" class="btn btn-primary btn-notepad" title="Abrir o Bloco de Notas">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>
</button>

<div class="row">
    <div class="col-md-12 form-group">
        <div class="card" style="min-height: 0">
            <div class="card-header">
                Cliente
            </div>
            <div class="card-body clearfix">
                <div class="row" style='padding: 8px'>

                    <div class="col-md-2 col-sm-2" style="margin-top: 5px; padding: 0 4px">
                        <label>Pesquisar por:</label>
                        <select id="sel-pesquisa" style='height: 31px;' class="form-control">
                            <option value="0" selected>Documento</option>
                            <option value="1">Nome</option>
                            <option value="2">ID</option>
                            <option value="3">Usuário</option>
                        </select>
                    </div>

                    <div class="col-md-5 col-sm-7" style="margin-top: 5px; padding: 0 4px">
                        <div id="pesquisaDoc">
                            <label style="width: 100%">Documento:</label>
                            <input id="pesqDoc" class="form-control pesqId cpf_cpnj" name="documento" type="text" placeholder="Digite o CPF ou CNPJ do Cliente" style="width: 100%" />
                        </div>
                        <div id="pesquisaNome" hidden>
                            <label style="width: 100%">Nome:</label>
                            <select id="pesqNome" class="form-control pesqNome" style="width: 100%" name="nome" type="text"></select>
                        </div>
                        <div id="pesquisaId" hidden>
                            <label>ID: </label>
                            <select class="form-control " id="pesqId" name="id" type="number" min="0" style="width: 100%" placeholder="Digite o ID do Cliente"></select>
                        </div>
                        <div id="pesquisaUsuario"  style="display:none;" >
                            <label>Usuário: </label>
                            <select class="form-control pesqUsuario" id="pesqUsuario" name="usuario" type="text" style="width: 100%;"> </select>    
                        </div>
                    </div>

                    <form id="formPesquisa" class="col-sm-2 col-sm-3" style="margin-top: 28px; padding: 0 4px">
                        <div class="col-md-6" style="padding: 0px;">
                            <button id="pesquisa" class="btn btn-primary btn-block" style="height: 33px; padding: 6px;" type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-6" style="padding: 0px;">
                            <button id='limpar-dados-cliente' class="btn btn-primary btn-block" style="height: 33px; padding: 6px;" type="button">
                                <i class="fa fa-leaf" aria-hidden="true"></i> Limpar
                            </button>
                        </div>
                    </form>

                    <div id="advancedSearchSupportPanelButtonContainer" class="col-md-3 col-sm-12 text-right" style="margin-top: 28px;">
                        <div class="btn-group">
                            <button class="dropbtn btn" id="btnDropdown" type="button" style="height: 33px;">
                                <span style="margin-right: 12px;" id="spanPesqAvancada">Pesquisa Avançada</span><span class="caret"></span>
                            </button>
                            <div id="myDropdown" class="dropdown-content" style="top: 100%">
                                <a style="margin-left: 2px; text-align: left;" class="btn btn-default" href="#modalBuscarOcorrencia" data-toggle="modal" data-target="#modalBuscarOcorrencia">Pesquisar Ocorrência</a>
                                <a style="margin-left: 2px; text-align: left;" class="btn btn-default" href="#modalBuscarContrato" data-toggle="modal" data-target="#modalBuscarContrato">Pesquisar Item de Contrato</a>
                                <a style="margin-left: 2px; text-align: left;" class="btn btn-default" href="#modalBuscarBaseInstalada" data-toggle="modal" data-target="#modalBuscarBaseInstalada">Pesquisar Base Instalada</a>
                                <a style="margin-left: 2px; text-align: left;" class="btn btn-default" href="#modalBuscarOS" data-toggle="modal" data-target="#modalBuscarOS">Pesquisar Ordem de Serviço</a>
                                <a style="margin-left: 2px; text-align: left;" class="btn btn-default" href="#modal-busca-na" data-toggle="modal" data-target="#modal-busca-na">Pesquisar Atividade de Serviço</a>
                                <a style="margin-left: 2px; text-align: left;" class="btn btn-default" href="#modal-comunicacao-chip" onclick="abrirModalComunicacaoChip( this, null, true )">Pesquisar Chip de Comunicação</a>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-primary dropbtn btn" id="btnCadastrosDrop" type="button" style="height: 33px;">
                                <span style="margin-right: 12px;" id="spanCadastrosDrop">Cadastro</span><span class="caret"></span>
                            </button>
                            <div id="cadastrosDropdown" style="top: 100%; position:absolute;display:none">
                                <a class="btn btn-primary" href="#modalCadNAAvulsa" data-toggle="modal" data-target="#modalCadNAAvulsa" style="width: 98px;">NA</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #btnDropdown {
        background-color: rgb(19, 164, 229) !important;
        color: #ffffff !important;
    }

    #btnDropdown:hover {
        background-color: rgba(19, 164, 229) !important;
        color: #ffffff !important;
    }

    .dropdown {
        position: relative;
        display: inline-block;
        color: #ffffff;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        color: #ffffff;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0);
        z-index: 1;
        right: 0;
        left: auto;
    }

    .dropdown-content a {
        text-decoration: none;
        display: block;
        color: #000000;
    }

    .centered {
        margin: auto !important;
    }
</style>

<script>


</script>

<!-- Div que guarda dados do cliente -->
<div id="dados_cliente" hidden>

    <ul id="ul-clientes" class="nav nav-tabs" role="tablist">
        <li role="presentation" class="nav-item nav-tab-dados_cliente abas-cliente active" id="nav_dados_cliente-01">
            <a href="#cliente-01" aria-controls="cliente-01" role="tab" data-toggle="tab">
                <h4 id="nome-cliente-01" style="margin-top: 28px;">01</h4>
            </a>
        </li>
        <li role="presentation" class="nav-item nav-tab-dados_cliente abas-cliente" id="nav_dados_cliente-02" style="display: none;">
            <i class="fa fa-times-circle" aria-hidden="true" onclick="removerAba(2)" style="font-size: 15px; margin-left: 50%;color:#404040 " onmouseover="this.style.color='#06a9f6'" onmouseout="this.style.color='#404040'"></i>
            <a href="#cliente-02" aria-controls="cliente-02" role="tab" data-toggle="tab">
                <h4 id="nome-cliente-02">02</h4>
            </a>
        </li>
        <li role="presentation" class="nav-item nav-tab-dados_cliente abas-cliente" id="nav_dados_cliente-03" style="display: none;">
            <i class="fa fa-times-circle" aria-hidden="true" onclick="removerAba(3)" style="font-size: 15px; margin-left: 50%; color:#404040" onmouseover="this.style.color='#06a9f6'" onmouseout="this.style.color='#404040'"></i>
            <a href="#cliente-03" aria-controls="cliente-03" role="tab" data-toggle="tab">
                <h4 id="nome-cliente-03">03</h4>
            </a>
        </li>
        <li role="presentation" class="nav-tab-dados_cliente abas-cliente" id="nav_dados_cliente-04" style="display: none;">
            <i class="fa fa-times-circle" aria-hidden="true" onclick="removerAba(4)" style="font-size: 15px; margin-left: 50%; color:#404040" onmouseover="this.style.color='#06a9f6'" onmouseout="this.style.color='#404040'"></i>
            <a href="#cliente-04" aria-controls="cliente-04" role="tab" data-toggle="tab">
                <h4 id="nome-cliente-04">04</h4>
            </a>
        </li>
        <li role="presentation" class="nav-tab-dados_cliente abas-cliente" id="nav_dados_cliente-05" style="display: none;">
            <i class="fa fa-times-circle" aria-hidden="true" onclick="removerAba(5)" style="font-size: 15px; margin-left: 50%; color:#404040" onmouseover="this.style.color='#06a9f6'" onmouseout="this.style.color='#404040'"></i>
            <a href="#cliente-05" aria-controls="cliente-05" role="tab" data-toggle="tab">
                <h4 id="nome-cliente-05">05</h4>
            </a>
        </li>

        <li id="btn-adiconar-aba">
            <a id="adicionar-aba" aria-controls="adiconar-aba" role="tab" data-toggle="tab" style="margin-top: 17px;">
                <h4><i class="fa fa-plus" aria-hidden="true" style="color: #d3cccc"></i></h4>
            </a>
        </li>
    </ul>

    <div class="tab-content">

        <?php for ($i = 1; $i < 6; $i++) { ?>
            <!-- DADOS CLIENTE -->
            <div role="tabpanel" class="tab-pane" id="cliente-0<?= $i ?>" data-nav-tab="nav_dados_cliente-0<?= $i ?>">
                <div class="row">
                    <div class="col-md-12">
                        <!-- TABS -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" id="nav_dados_cliente_tab-0<?= $i ?>">
                                <a href="#tab_dados_cliente-0<?= $i ?>" aria-controls="tab_dados_cliente-0<?= $i ?>" role="tab" data-toggle="tab">
                                    <span>Cliente</span>
                                </a>
                            </li>
                            <li role="presentation" class="nav-tab-ocorrencias nav_ocorrencias" id="nav_ocorrencias-0<?= $i ?>">
                                <a href="#tab_ocorrencias-0<?= $i ?>" aria-controls="tab_ocorrencias-0<?= $i ?>" role="tab" data-toggle="tab">
                                    <span>Ocorrências</span>
                                </a>
                            </li>
                            <li role="presentation" class="nav-tab-contratos nav_contratos" id="nav_contratos-0<?= $i ?>">
                                <a href="#tab_contratos-0<?= $i ?>" aria-controls="tab_contratos-0<?= $i ?>" role="tab" data-toggle="tab">
                                    <span>Contratos</span>
                                </a>
                            </li>

                            <li role="presentation" class="nav-tab-atividades_servico tab_atividades_servico nav_atividades_servico" id="nav_atividades_servico-0<?= $i ?>">
                                <a href="#tab_atividades_servico-0<?= $i ?>" aria-controls="tab_atividades_servico-0<?= $i ?>" role="tab" data-toggle="tab">
                                    <span>Atividades de Serviço</span>
                                </a>
                            </li>
                            <li role="presentation" class="nav-tab-providencias nav_providencias" id="nav_providencias-0<?= $i ?>">
                                <a href="#tab_providencias-0<?= $i ?>" aria-controls="tab_providencias-0<?= $i ?>" role="tab" data-toggle="tab">

                                    <span>Providências</span>
                                    <i class="fa fa-exclamation-circle color_red icon_alert" id="icon_alert_providencia-0<?= $i ?>" style="display: none;" aria-hidden="true"></i>

                                </a>
                            </li>
                            <li role="presentation" class="nav-tab-base-instalada nav_base_instalada" id="nav_base_instalada-0<?= $i ?>">
                                <a href="#tab_base_instalada-0<?= $i ?>" aria-controls="tab_base_instalada" role="tab" data-toggle="tab">
                                    <span>Base Instalada</span>
                                </a>
                            </li>
                            <li role="presentation" class="nav-tab-cotacao nav_cotacao" id="nav_cotacao-0<?= $i ?>">
                                <a href="#tab_cotacao-0<?= $i ?>" aria-controls="tab_cotacao" role="tab" data-toggle="tab">
                                    <span>Cotações</span>
                                </a>
                            </li>
                            <?php if ($this->auth->is_allowed_block('vis_funcoes_programaveis')) : ?>
                                <li role="presentation" class="nav-tab-funcoes nav_funcoes" id="nav_funcoes-0<?= $i ?>">
                                    <a href="#tab_funcoes-0<?= $i ?>" aria-controls="tab_funcoes" role="tab" data-toggle="tab">
                                        <span>Grupos de Funções Programáveis</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab-content">
                            <!-- DADOS CLIENTE -->
                            <div role="tabpanel" class="tab-pane  tab-pane-dados_cliente active" id="tab_dados_cliente-0<?= $i ?>" data-nav-tab="nav_dados_cliente-0<?= $i ?>">
                                <!-- VISÃO DOS PRODUTOS -->
                                <div class="row">
                                    <div class="col-md-12 subtitulo">
                                        <span>Visão Financeira</span>
                                        <div style='float: right'>
                                            <a class="btn btn-primary" id="btnPosicaoFinanceiraCliente" onclick="exibirVisaoFinanceira()" title="Exibir visão financeira do cliente" style="margin-right: 20px;">
                                                Posição Financeira <i class="fa fa-external-link" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card-small card-blue">
                                            <a href="javascript:void(0)" id="abertos-0<?= $i ?>" style="float: right;" class="visualizar_valores visualizar_valores-0<?= $i ?>"><i class="fa fa-eye" style="font-size: 20px;color: darkgray;" title="Visualizar"></i></a>
                                            <label class="card-header">Abertos <span id="spinnerVisaoDosProdutosAberto"></span></label>
                                            <div class="card-body">
                                                <span class="big-info">
                                                    <span id="ABERTO-0<?= $i ?>">R$ - </span>
                                                    <span style="float:right"><i class="fa fa-money color_blue" aria-hidden="true"></i></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card-small card-yellow">
                                            <a href="javascript:void(0)" id="vencidos-0<?= $i ?>" style="float: right;" class="visualizar_valores visualizar_valores-0<?= $i ?>"><i class="fa fa-eye" style="font-size: 20px;color: darkgray;" title="Visualizar"></i></a>
                                            <label class="card-header">Vencidos <span id="spinnerVisaoDosProdutosVencido"></span></label>
                                            <div class="card-body">
                                                <span class="big-info">
                                                    <span id="VENCIDO-0<?= $i ?>">R$ - </span>
                                                    <span style="float:right"><i class="fa fa-money color_yellow" aria-hidden="true"></i></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card-small card-green">
                                            <a href="javascript:void(0)" id="liquidados-0<?= $i ?>" style="float: right;" class="visualizar_valores visualizar_valores-0<?= $i ?>"><i class="fa fa-eye" style="font-size: 20px;color: darkgray;" title="Visualizar"></i></a>
                                            <label class="card-header">Liquidados <span id="spinnerVisaoDosProdutosLiquidado"></span></label>
                                            <div class="card-body">
                                                <span class="big-info">
                                                    <span id="LIQUIDADO-0<?= $i ?>">R$ - </span>
                                                    <span style="float:right"><i class="fa fa-money color_green" aria-hidden="true"></i></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informações do cliente -->
                                <div class="row">
                                    <div class="col-md-12 subtitulo">
                                        <span>Dados do Cliente</span>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card heigth_100pc">
                                            <div class="card-header">Cliente</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Status: </label><span id="StatusCliente-0<?= $i ?>">-</span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Nome: </label><span id="NomeCliente-0<?= $i ?>">-</span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>CNPJ/CPF: </label><span id="CNPJCliente-0<?= $i ?>">-</span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="">Logradouro: </label><span id="EnderecoCliente-0<?= $i ?>">-</span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="">Bairro: </label><span id="BairroCliente-0<?= $i ?>">-</span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="">CEP: </label><span id="CEPCliente-0<?= $i ?>">-</span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="">Cidade/Estado: </label>
                                                        <span>
                                                            <span id="CidadeCliente-0<?= $i ?>"></span> - <span id="UFCliente-0<?= $i ?>"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="border_top_bottom">
                                                <div class="card-header">Situação financeira <a href="javascript:void(0)" style="color:blue; font-weight: bold; font-size: 10px; margin-left: 10px;" class="avisoPagamento">Aviso de Pagamento</a></div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Agendamento: </label>
                                                            <select class="form-control alterStatus" id="selectAtendimentoVeic-0<?= $i ?>" name="status_atendimentoriveiculo" disabled="true">
                                                                <option value="true">Liberado</option>
                                                                <option value="false">Não Liberado</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Comunicação Chip: </label>
                                                            <select class="form-control alterStatus" id="selectComunicacaoChip-0<?= $i ?>" name="status_comunicacaochip" disabled="true">
                                                                <option value="true">Liberado</option>
                                                                <option value="false">Não Liberado</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Comunicação Satelital: </label>
                                                            <select class="form-control alterStatus" id="selectComunicacaoSatelital-0<?= $i ?>" name="status_comunicacaosatelital" disabled="true">
                                                                <option value="true">Liberado</option>
                                                                <option value="false">Não Liberado</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Data Desbloqueio Portal: </label>
                                                            <input type="text" style="margin-bottom: 5px;" class="form-control" id="statusDataDesbloqueio-0<?= $i ?>" name="status_data_desbloqueio_portal" readonly="true">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Emissão PV: </label>
                                                            <select class="form-control alterStatus" id="selectEmissaoPV-0<?= $i ?>" name="status_emissaopv" disabled="true">
                                                                <option value="true">Liberado</option>
                                                                <option value="false">Não Liberado</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Bloqueio Total: </label>
                                                            <select class="form-control alterStatus" id="selectBloqueioTotal-0<?= $i ?>" name="status_bloqueiototal" disabled="true">
                                                                <option value="true">SIM</option>
                                                                <option value="false">NÃO</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Desbloqueio Portal: </label>
                                                            <select class="form-control alterStatus" id="selectDesbloqueioPortal-0<?= $i ?>" name="status_desbloqueioportal" disabled="true">
                                                                <option value="true">SIM</option>
                                                                <option value="false">NÃO</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border_top_bottom">
                                                <div class="card-header">Contrato</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Nome Fantasia: </label><span id="NomeFantasiaCliente-0<?= $i ?>">-</span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Telefone: </label><span id="TelefoneCliente-0<?= $i ?>">-</span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>E-mail: </label><span id="EmailCliente-0<?= $i ?>">-</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-header">Segmentação</div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Segmentação de Cliente: </label><span id="SegmentacaoCliente-0<?= $i ?>">-</span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Analista de Pós Venda
                                                                : </label><span id="SuporteCliente-0<?= $i ?>">-</span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Vendedor: </label><span id="VendedorCliente-0<?= $i ?>">-</span>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>Código Cliente (Microsiga): </label><span id="CodigoClienteZatix-0<?= $i ?>">-</span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Loja: </label><span id="Loja-0<?= $i ?>">-</span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Código Cliente (Graber): </label><span id="CodigoClienteGraber-0<?= $i ?>">-</span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Código Cliente (Show): </label><span id="CodigoClienteShow-0<?= $i ?>">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-8">
                                        <div class="card heigth_100pc">
                                            <form id="form-edit-cliente-0<?= $i ?>" action="javascript: void(0)">
                                                <div class="row margin_bottom_20">
                                                    <div class="col-md-12">
                                                        <!-- TABS -->
                                                        <nav class="nav nav-tabs">
                                                            <ul class="nav nav-tabs" role="tablist">
                                                                <li role="presentation" class="nav-tab-cliente nav_cliente active" id="nav_cliente-0<?= $i ?>"><a href="#tab_cliente-0<?= $i ?>" aria-controls="tab_cliente-0<?= $i ?>" role="tab" data-toggle="tab">Cliente</a></li>
                                                                <li role="presentation" class="nav-tab-cliente" id="nav_contatos_relacionados-0<?= $i ?>"><a href="#tab_contatos_relacionados-0<?= $i ?>" aria-controls="tab_contatos_relacionados-0<?= $i ?>" role="tab" data-toggle="tab">Contatos Relacionados</a></li>
                                                                <li role="presentation" class="nav-tab-cliente" id="nav_grupo_economico"><a href="#tab_grupo_economico-0<?= $i ?>" aria-controls="tab_cliente-0<?= $i ?>" role="tab" data-toggle="tab">Grupo Econômico</a></li>
                                                                <li role="presentation" class="nav-tab-cliente" id="nav_contato"><a href="#tab_contato-0<?= $i ?>" aria-controls="tab_contato-0<?= $i ?>" role="tab" data-toggle="tab">Contato</a></li>
                                                                <li role="presentation" class="nav-tab-cliente" id="nav_endereco_principal"><a href="#tab_endereco_principal-0<?= $i ?>" aria-controls="tab_endereco_principal-0<?= $i ?>" role="tab" data-toggle="tab">Endereço principal</a></li>
                                                                <li role="presentation" class="nav-tab-cliente" id="nav_endereco_cobranca"><a href="#tab_endereco_cobranca-0<?= $i ?>" aria-controls="tab_endereco_cobranca-0<?= $i ?>" role="tab" data-toggle="tab">Endereço de cobrança</a></li>
                                                                <li role="presentation" class="nav-tab-cliente" id="nav_endereco_entrega"><a href="#tab_endereco_entrega-0<?= $i ?>" aria-controls="tab_endereco_entrega-0<?= $i ?>" role="tab" data-toggle="tab">Endereço de entrega</a></li>
                                                                <li role="presentation" class="nav-tab-cliente nav_vendas_af" id="nav_vendas_af-0<?= $i ?>"><a href="#tab_vendas_af-0<?= $i ?>" aria-controls="tab_vendas_af-0<?= $i ?>" role="tab" data-toggle="tab">Vendas (AF)</a></li>
                                                                <li role="presentation" class="nav-tab-cliente nav_grupo_email" id="nav_grupo_email-0<?= $i ?>"><a href="#tab_grupo_email-0<?= $i ?>" aria-controls="tab_grupo_email-0<?= $i ?>" role="tab" data-toggle="tab">Grupos de E-mail</a></li>
                                                                <li role="presentation" style="float: right;">
                                                                    <?php if ($this->auth->is_allowed_block('edi_alterarcadastrodecliente')) : ?>
                                                                        <button class="btn btn-primary btn_edit_cliente" title="Editar Cliente" id="btn_edit_cliente-0<?= $i ?>">
                                                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                        </button>
                                                                    <?php else : ?>
                                                                        <button class="btn btn-primary btn_edit_cliente" title="Editar Cliente" id="btn_edit_cliente-0<?= $i ?>" disabled>
                                                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="tab-content">
                                                            <!-- CLIENTE -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente nav_cliente active" id="tab_cliente-0<?= $i ?>" data-nav-tab="nav_cliente">
                                                                <!-- Nome do cliente PJ adicionado dinamicamente -->
                                                                <div class="row" id="row_accounts-0<?= $i ?>"></div>
                                                                <!-- Nome do cliente PF adicionado dinamicamente -->
                                                                <div class="row" id="row_contacts-0<?= $i ?>"></div>
                                                                <div class="row">
                                                                    <div class="col-md-12 form-group">
                                                                        <label class='control-label'>Nome Fantasia/Sobrenome</label>
                                                                        <input class="form-control info-cliente" type="text" required maxlength="250" name="NomeFantasia_Sobrenome" value="" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4 form-group">
                                                                        <label class='control-label'>CPF/CNPJ</label>
                                                                        <input class="form-control info-cliente cpf_cpnj" required type="text" minlength="11" maxlength="14" name="Cpf_Cnpj" value="" disabled>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label class=''>Inscrição Estadual</label>
                                                                        <input class="form-control info-cliente" id='inscricao-estadual-0<?= $i ?>' type="text" maxlength="20" name="InscricaoEstadual" value="" disabled>
                                                                    </div>

                                                                    <div class="col-md-4 form-group">
                                                                        <label class=''>Inscrição Municipal</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="25" name="InscricaoMunicipal" value="" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4 form-group">
                                                                        <label class='control-label'>Tipo relação</label>
                                                                        <select class="form-control info-cliente" required name="TipoRelacao" disabled>
                                                                            <option value="">Escolha uma opção</option>
                                                                            <option value="1">Consumidor Final</option>
                                                                            <option value="2">Produtor Rural</option>
                                                                            <option value="3">Revendedor</option>
                                                                            <option value="4">Solidário</option>
                                                                            <option value="5">Exportação</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label class='control-label'>Vendedor</label>
                                                                        <select class="form-control info-cliente" required name="Vendedor" disabled>
                                                                            <option value="-1" selected>Carregando...</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label class='control-label'>Canal de venda</label>
                                                                        <select class="form-control info-cliente" required name="CanalVenda" disabled>
                                                                            <option selected value="-1">Carregando...</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4 form-group">
                                                                        <label class='control-label'>Classificação Cliente</label>
                                                                        <select class="form-control info-cliente" name="ClassificacaoCliente" disabled>
                                                                            <option></option>
                                                                            <option value="419400001">Key Account</option>
                                                                            <option value="419400000">ADO</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label class='control-label'>Segmentação Cliente</label>
                                                                        <select class="form-control info-cliente" name="Segmentacao" disabled>
                                                                            <option selected value="-1">Carregando...</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Segmentação Cliente Manual</label>
                                                                        <select class="form-control info-cliente" name="segmentacaoManual" disabled>
                                                                            <option value="1">Sim</option>
                                                                            <option value="0">Não</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Conta Primária</label>
                                                                        <select class="form-control" style='width: 100%;' name="contaPrimaria" type="text" disabled></select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Envio Sustentável</label>
                                                                        <select class="form-control info-cliente" name="envioSustentavel" disabled>
                                                                            <option value="1">Sim</option>
                                                                            <option value="2">Não</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Possui Particularidade</label>
                                                                        <select class="form-control info-cliente" name="particularidade" disabled>
                                                                            <option value="1">Sim</option>
                                                                            <option value="0">Não</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Nome Responsável</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="250" name="nomeResponsavel" value="" disabled>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Cargo Responsável</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="250" name="cargoResponsavel" value="" disabled>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Analista de Pós Venda
                                                                        </label>
                                                                        <select id="analista-suporte-0<?= $i ?>" class="form-control info-cliente" name="analistaSuporte" disabled></select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Forma de Cobrança</label>
                                                                        <select id="forma-cobranca-0<?= $i ?>" class="form-control info-cliente" name="formaCobranca" disabled></select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Gerenciadora de Risco</label>
                                                                        <select class="form-control info-cliente" name="gerenciadoraDeRisco" disabled>
                                                                            <option value="1">Sim</option>
                                                                            <option value="0">Não</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 form-group">
                                                                        <label>Nome da Gerenciadora de Risco</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="250" name="gerenciadoraRiscoNome" value="" disabled>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <!-- CONTATOS RELACIONADOS -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente" id="tab_contatos_relacionados-0<?= $i ?>" data-nav-tab="nav_contatos_relacionados-0<?= $i ?>">
                                                                <div id='div-tabela-contatos-associados-0<?= $i ?>' hidden>
                                                                    <table class="table table-responsive table-bordered tabela-contatos-associados" id="tabela-contatos-associados-0<?= $i ?>">
                                                                        <thead>
                                                                            <th style="width: 32%; min-width: 150px; text-align: center;">Nome</th>
                                                                            <th style="width: 24%; min-width: 100px; text-align: center;">Função</th>
                                                                            <th style="width: 15%; min-width: 80px; text-align: center;">Telefone</th>
                                                                            <th style="width: 30%; min-width: 150px; text-align: center;">E-mail</th>
                                                                            <th style="width: 4%; min-width: 60px; text-align: center;">Ações</th>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                </div>
                                                                <div id='div-loading-contatos-associados-0<?= $i ?>' style='height: 400px; display: flex; justify-content: center; align-items: center;'>
                                                                    <i class="fa fa-spinner fa-spin" style='color: #06a9f6; font-size: 36px;'></i>
                                                                </div>
                                                            </div>
                                                            <!-- GRUPO ECONÔMICO -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente" id="tab_grupo_economico-0<?= $i ?>" data-nav-tab="nav_grupo_economico">
                                                                <ul id="listaGrupoEconomico"></ul>
                                                            </div>
                                                            <!-- CONTATO -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente " id="tab_contato-0<?= $i ?>" data-nav-tab="nav_contato">
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label class=''>Email</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="255" name="Email" value="" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label class=''>Email Novo</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="255" name="EmailNovo" value="" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email Telemetria</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="255" name="EmailTelemetria" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email AF</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="255" name="EmailAF" value="" disabled>
                                                                    </div>

                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email Linker</label>
                                                                        <input class="form-control info-cliente" type="text" name="EmailLinker" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email de Alerta de Cerca</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="255" name="EmailAlertaCerca" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Telefone</label>
                                                                        <input class="form-control info-cliente telefone" type="text" maxlength="10" name="telefone" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Telefone 2</label>
                                                                        <input class="form-control info-cliente telefone" type="text" maxlength="10" name="telefone2" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Telefone 3</label>
                                                                        <input class="form-control info-cliente telefone" type="text" maxlength="10" name="telefone3" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Celular</label>
                                                                        <input class="form-control info-cliente celular" type="text" maxlength="11" name="celular" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Fax</label>
                                                                        <input class="form-control info-cliente telefone" type="text" maxlength="20" name="fax" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Site</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="50" name="site" value="" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- ENDERECO PRINCIPAL -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente " id="tab_endereco_principal-0<?= $i ?>" data-nav-tab="nav_endereco_principal">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <h4>Endereço Principal</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label>Logradouro</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="enderecoPrincipal" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label>Bairro</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="bairroPrincipal" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label class=''>CEP</label>
                                                                        <input class="form-control info-cliente cep" type="text" minlength='8' maxlength="8" name="CepPrincipal" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label>Complemento</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="complementoPrincipal" value="" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- ENDERECO COBRANCA -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente " id="tab_endereco_cobranca-0<?= $i ?>" data-nav-tab="nav_endereco_cobranca">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <h4>Endereço de Cobrança</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label>Logradouro</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="enderecoCobranca" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label>Bairro</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="bairroCobranca" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label class=''>CEP</label>
                                                                        <input class="form-control info-cliente cep" type="text" minlength='8' maxlength="8" name="CepCobranca" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label>Complemento</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="complementoCobranca" value="" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- ENDERECO ENTREGA -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente " id="tab_endereco_entrega-0<?= $i ?>" data-nav-tab="nav_endereco_entrega">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <h4>Endereço de Entrega</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label>Logradouro</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="enderecoEntrega" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label>Bairro</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="bairroEntrega" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label class=''>CEP</label>
                                                                        <input class="form-control info-cliente cep" type="text" minlength='8' maxlength="8" name="CepEntrega" value="" disabled>
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label>Complemento</label>
                                                                        <input class="form-control info-cliente" type="text" maxlength="150" name="complementoEntrega" value="" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- VENDAS(AF) -->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente" id="tab_vendas_af-0<?= $i ?>" data-nav-tab="nav_vendas_af">
                                                                <table class="table table-responsive table-bordered" id="tabela-vendas-af-0<?= $i ?>">
                                                                    <thead>
                                                                        <th style="text-align: center;">Número AF</th>
                                                                        <th style="text-align: center;">Modalidade de Venda</th>
                                                                        <th style="text-align: center;">Tipo de Pagamento</th>
                                                                        <th style="text-align: center;">Criado em </th>
                                                                        <th style="text-align: center;">Modificado em</th>
                                                                        <th style="text-align: center;">Status</th>
                                                                        <th style="text-align: center;align-content: center; width: 5%;">Ações</th>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                            <!-- GRUPOS DE EMAIL-->
                                                            <div role="tabpanel" class="tab-pane tab-pane-cliente" id="tab_grupo_email-0<?= $i ?>" data-nav-tab="tab_grupo_email">
                                                                <div class="row">
                                                                    <div class="form-group col-md-8">
                                                                        <label>Grupos Cadastrados</label>
                                                                        <div id='dropdown-grupos-email-0<?= $i ?>' class="dropdown-grupos-email">
                                                                            <select>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <input type="hidden" class="form-control" id="id-emails-0<?= $i ?>" name="id-emails" disabled>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 1</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email1-0<?= $i ?>" name="email1-0<?= $i ?>" disabled>
                                                                        <input type="hidden" id="id-email1-0<?= $i ?>" name="id-email1-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 2</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email2-0<?= $i ?>" name="email2-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email2-0<?= $i ?>" name="id-email2-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 3</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email3-0<?= $i ?>" name="email3-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email3-0<?= $i ?>" name="id-email3-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 4</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email4-0<?= $i ?>" name="email4-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email4-0<?= $i ?>" name="id-email4-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 5</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email5-0<?= $i ?>" name="email5-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email5-0<?= $i ?>" name="id-email5-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 6</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email6-0<?= $i ?>" name="email6-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email6-0<?= $i ?>" name="id-email6-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 7</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email7-0<?= $i ?>" name="email7-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email7-0<?= $i ?>" name="id-email7-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 8</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email8-0<?= $i ?>" name="email8-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email8-0<?= $i ?>" name="id-email8-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 9</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email9-0<?= $i ?>" name="email9-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email9-0<?= $i ?>" name="id-email9-0<?= $i ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class=''>Email 10</label>
                                                                        <input class="form-control" type="text" maxlength="50" id="email10-0<?= $i ?>" name="email10-0<?= $i ?>" value="" disabled>
                                                                        <input type="hidden" id="id-email10-0<?= $i ?>" name="id-email10-0<?= $i ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" id="buttons_edit_cliente-0<?= $i ?>" hidden>
                                                    <div class="col-md-12">
                                                        <button type='button' id='salvar-cliente-0<?= $i ?>' class="btn btn-primary salvar-cliente">Salvar</button>
                                                        <a class="btn btn-secondary cancelar-cliente" id="cancelar-cliente-0<?= $i ?>">Cancelar</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- OCORRÊNCIAS -->
                            <div role="tabpanel" class="tab-pane  tab-pane-ocorrencias" id="tab_ocorrencias-0<?= $i ?>" data-nav-tab="nav_ocorrencias">
                                <!-- DADOS DA OCORRÊNCIA -->
                                <div class="row ">
                                    <div class="col-md-12 subtitulo" style="margin-bottom: 10px;">
                                        <span>Dados das Ocorrências</span>
                                        <div style="float: right;">
                                            <!-- <button type="button" data-toggle="modal" data-target='#modalOcorrencia' class="btn btn-primary" style='float: right; position: relative; right: 20px'>Cadastrar Ocorrência</button> -->
                                            <button class="btn btn-primary" id="btnTicketCliente" type="button" style="height: 33px;">
                                                <span>Novo Ticket</span>
                                            </button>
                                            <div class="btn-group">
                                                <button class="dropbtn btn btn-primary" id="btnDropdownOcorrencias" type="button" style="height: 33px;">
                                                    <span style="margin-right: 12px;" id="spanOcorrencias">Cadastrar Ocorrência</span><span class="caret"></span>
                                                </button>
                                                <div id="myDropdownOcorrencias" class="dropdown-content" style="top: 100%">
                                                    <button style="text-align: left; width: 100%; border: none; margin: 0;" type="button" data-toggle="modal" data-target='#modalOcorrencia' class="btn btn-primary">Cadastrar Ocorrência</button>
                                                    <button style="text-align: left; width: 100%; border: none; margin: 0;" type="button" data-toggle="modal" id="ocorrenciaSuporte" class="btn btn-primary">Cadastrar Ocorrência como Suporte</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12" style="margin-bottom: 6px;">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="active" role="presentation">
                                                                <a id="active-incidents-link-0<?= $i ?>" class="active-incidents" aria-controls="active-incidents" role="tab" data-toggle="tab">
                                                                    <span>Ativas</span>
                                                                    <span class="badge">
                                                                        <i class="fa fa-spinner fa-spin"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a id="canceled-incidents-link-0<?= $i ?>" class="canceled-incidents" aria-controls="canceled-incidents-0<?= $i ?>" role="tab" data-toggle="tab">
                                                                    <span>Canceladas</span>
                                                                    <span class="badge">
                                                                        <i class="fa fa-spinner fa-spin"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a id="resolved-incidents-link-0<?= $i ?>" class="resolved-incidents" aria-controls="resolved-incidents-0<?= $i ?>" role="tab" data-toggle="tab">
                                                                    <span>Resolvidas</span>
                                                                    <span class="badge">
                                                                        <i class="fa fa-spinner fa-spin"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table id="tableIncidents-0<?= $i ?>" class="table table-responsive table-bordered nowrap tableIncidents" style="width: 100%;">
                                                            <thead>
                                                                <th>Nº Ticket</th>
                                                                <th>Assunto</th>
                                                                <th>Tipo de Ocorrência</th>
                                                                <th>Origem da Ocorrência</th>
                                                                <th>Tecnologia</th>
                                                                <th>Fila Atual</th>
                                                                <th>Razão status</th>
                                                                <th>Tipo de Serviço</th>
                                                                <th data-priority="10001">Descrição</th>
                                                                <th>Criado</th>
                                                                <th>Modificado</th>
                                                                <th>Ações</th>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- CONTRATOS -->
                            <div role="tabpanel" class="tab-pane  tab-pane-contratos" id="tab_contratos-0<?= $i ?>" data-nav-tab="nav_contratos-0<?= $i ?>">
                                <!-- DADOS DO CONTRATO -->
                                <div class="row ">
                                    <div class="col-md-12 subtitulo">
                                        <span>Dados dos Contratos</span>
                                        <?php if ($this->auth->is_allowed_block('out_alterarInfoItensContratoOmnilink')) : ?>
                                            <a id="btnAbrirModalCadastroItemDeContrato-0<?= $i ?>" class="btn btn-primary btnAbrirModalCadastroItemDeContrato" style="float: right;">Cadastrar Item de Contrato</a>
                                        <?php endif; ?>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xs-12 text-center">
                                                        <div class="alert alert-info col-md-12">
                                                            <div class="col-md-2 text-left">
                                                                <p>Filtre por status:</p>
                                                                <select class="form-control" id='filtroContratos'>
                                                                    <option value="0" id="ContratosTodos-0<?= $i ?>">Todos - 0</option>
                                                                    <option value="1" id="ContratosAtivos-0<?= $i ?>">Ativos - 0</option>
                                                                    <option value="2" id="ContratosAguardando-0<?= $i ?>">Aguardando - 0</option>
                                                                    <option value="3" id="ContratosCancelados-0<?= $i ?>">Cancelados - 0</option>
                                                                    <option value="4" id="ContratosSuspensos-0<?= $i ?>">Suspensos - 0</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-8 text-left" style="padding-left: 5px;">
                                                                <button type="button" id="btnfiltroContratos" class="btn btn-primary" style="margin-bottom: 3px; margin-right: 10px; margin-top: 30px;"><i class="fa fa-filter" style="font-size: 16px;"></i> Filtrar</button>
                                                                <button type="button" id="btnLimparfiltroContratos" class="btn btn-primary" style="margin-bottom: 3px; margin-top: 30px;"><i class="fa fa-leaf" style="font-size: 16px;"></i> Limpar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table id="tabelaContratos-0<?= $i ?>" class="table table-striped table-bordered" style="width: 100%;">
                                                            <thead>
                                                                <th>Nome</th>
                                                                <th>Veículo</th>
                                                                <th>Serial</th>
                                                                <th>Equipamento</th>
                                                                <th>Plano</th>
                                                                <th>Modalidade</th>
                                                                <th>Cod. Venda</th>
                                                                <th>Data Ativação</th>
                                                                <th>Status</th>
                                                                <th>Status Item de Contrato</th>
                                                                <th>Ações</th>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ATIVIDADES DE SERVICO -->
                            <div role="tabpanel" class="tab-pane tab-pane-providencias tab_providencias" id="tab_atividades_servico-0<?= $i ?>" data-nav-tab="nav_atividades_servico">
                                <!-- ATIVIDADES DE SERVIÇO -->
                                <div class="row col-md-12">
                                    <div class="subtitulo">
                                        <span>Atividades de Serviço (NAs)</span>
                                        <div style="float: right;">
                                            <button id="btn-cadastro-na-0<?= $i ?>" type="button" onclick="abrirModalNA(this)" class="btn btn-primary" style='width: 136px;margin-bottom: 20%;display: none;'>Cadastrar NA</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="atividadesDeServicoContainer-0<?= $i ?>" class="row" style="width: 100%;">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row margin_bottom_20">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist" id="listaAtividadesDeServico">
                                                            <li role="presentation" class="active">
                                                                <a id="tab_na_abertas-0<?= $i ?>" class="tab_na_abertas" aria-controls="tab_na_abertas" role="tab" data-toggle="tab">
                                                                    <span>Abertas</span> <small id="ASAberta-0<?= $i ?>" class="badge">0</small>
                                                                </a>
                                                            </li>
                                                            <li role="presentation" class="">
                                                                <a id="tab_na_fechadas-0<?= $i ?>" class="tab_na_fechadas" aria-controls="tab_na_fechadas" role="tab" data-toggle="tab">
                                                                    <span>Fechadas</span> <small id="ASFechada-0<?= $i ?>" class="badge">0</small>
                                                                </a>
                                                            </li>
                                                            <li role="presentation" class="">
                                                                <a id="tab_na_canceladas-0<?= $i ?>" class="tab_na_canceladas" aria-controls="tab_na_canceladas" role="tab" data-toggle="tab">
                                                                    <span>Canceladas</span> <small id="ASCancelada-0<?= $i ?>" class="badge">0</small>
                                                                </a>
                                                            </li>
                                                            <li role="presentation" class="">
                                                                <a id="tab_na_agendadas-0<?= $i ?>" class="tab_na_agendadas" aria-controls="tab_na_agendadas" role="tab" data-toggle="tab">
                                                                    <span>Agendadas</span> <small id="ASAgendada-0<?= $i ?>" class="badge">0</small>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table-responsive table-bordered table tableAtividadesDeServico no-wrap" id="tableAtividadesDeServico-0<?= $i ?>">
                                                            <thead>
                                                                <th>Cod.</th>
                                                                <th>Serial</th>
                                                                <th>Fornecedor</th>
                                                                <th>Serviço</th>
                                                                <th>Complemento</th>
                                                                <th>Assunto</th>
                                                                <th>Inicio</th>
                                                                <th>Fim</th>
                                                                <th>Razão do Status</th>
                                                                <th>Número OS</th>
                                                                <th>Ações</th>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- PROVIDENCIAS -->
                            <div role="tabpanel" class="tab-pane  tab-pane-providencias tab_providencias" id="tab_providencias-0<?= $i ?>" data-nav-tab="nav_providencias-0<?= $i ?>">
                                <div class="row">
                                    <div class="col-md-12 subtitulo">
                                        <span>Perguntas e Respostas</span>
                                        <button class="btn btn-primary" style="float: right;" onclick="abrirModalCadastroProvidencia()">Cadastrar Providência</button>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-responsive" id="table_providencias-0<?= $i ?>">
                                            <thead>
                                                <th>Nome</th>
                                                <th>Data de Criação</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                                <th>id</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- BASE INSTALADA -->
                            <div role="tabpanel" class="tab-pane  tab-pane-base-instalada" id="tab_base_instalada-0<?= $i ?>" data-nav-tab="nav_base_instalada-0<?= $i ?>">
                                <!-- BASE INSTALADA -->
                                <div class="row ">
                                    <div class="col-md-12 subtitulo">
                                        <span>Base Instalada</span>
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalBaseInstalada" style="float:right">Base Instalada</button>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="display table-responsive table-bordered table" id="tableBaseInstalada-0<?= $i ?>">
                                            <thead>
                                                <th>Nome</th>
                                                <th>Veículo</th>
                                                <th>Data de Instalação</th>
                                                <th>Data de Desinstalação</th>
                                                <th>Produto</th>
                                                <th>Numero De Série</th>
                                                <th>Ações</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Cotação -->
                            <div role="tabpanel" class="tab-pane  tab-pane-cotacao" id="tab_cotacao-0<?= $i ?>" data-nav-tab="nav_cotacao-0<?= $i ?>">
                                <!-- Cotação -->
                                <div class="row ">
                                    <div class="col-md-12 subtitulo">
                                        <span>Cotação</span>
                                        <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#modalBaseInstalada" style="float:right">Base Instalada</button> -->
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="display table-responsive table-bordered table" id="tableCotacao-0<?= $i ?>">
                                            <thead>
                                                <th><?= lang('quotenumber') ?></th>
                                                <th type="date" class="sorting_desc"><?= lang('createdon') ?></th>
                                                <th><?= lang('statecode') ?></th>
                                                <th><?= lang('tz_analise_credito') ?></th>
                                                <th><?= lang('tz_valor_total_licenca') ?></th>
                                                <th><?= lang('tz_valor_total_hardware') ?></th>
                                                <th><?= lang('effectivefrom') ?></th>
                                                <th><?= lang('effectiveto') ?></th>
                                                <th><?= lang('acoes') ?></th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Funções Porgramáveis -->
                            <div role="tabpanel" class="tab-pane tab-pane-funcoes" id="tab_funcoes-0<?= $i ?>" data-nav-tab="nav_funcoes-0<?= $i ?>">
                                <!-- Funções Porgramáveis -->
                                <div class="row ">
                                    <div class="col-md-12 subtitulo">
                                        <span>Grupo de Funções Programáveis</span>
                                        <button class="btn btn-primary" id="btnModalFuncoesProgramaveis" style="float:right">Associar Grupo de Função Programável</button>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="display table-responsive table-bordered table" id="tableFuncoesProgamaveis-0<?= $i ?>">
                                            <thead>
                                                <th>ID</th>
                                                <th>ID Grupo Saver</th>
                                                <th>Descrição</th>
                                                <th>Ações</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal Resetar Chip -->
<div id="modalResetarChip" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="formResetarChip">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Resetar Linha</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_resetar_chip">
                                    <div class="row">
                                        <div class="col-md-12 form-group" hidden>
                                            <input type="text" class="form-control" id="idOperadoraResetarChip">
                                            <input type="text" class="form-control" id="linhaResetarChip">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label class="control-label">Confirmação:</label>
                                            <p style="margin-top: 5px;">Tem certeza de que deseja resetar a linha? Esta ação não pode ser desfeita.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    <a class="btn btn-primary" id="btnSalvarResetarChip" style="margin-right: 10px;" onclick="resetarChip(this)">Resetar</a>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/i18n/pt-BR.js"></script>

<script>
    // DEFINE CONSTANTE COM PERMISSÕES DE ACESSO AOS ITENS DO SAC
    const permissoes = JSON.parse('<?= isset($permissoes) ? $permissoes : "{}" ?>');

    const cpfCnpjRedirecionado = <?= json_encode($cpfCnpjRedirecionado) ?>;

    // ANIMAÇÃO DO ICONE DE ALERT
    $(document).ready(function() {
        const transitionTime = '0.5s';
        let changeOpacity = false;
        setInterval(function() {
            if (!changeOpacity) {
                $(".icon_alert").css('opacity', 0.5).css('transition', transitionTime);
                changeOpacity = true;
            } else {
                $(".icon_alert").css('opacity', 1).css('transition', transitionTime);
                changeOpacity = false;
            }

        }, 500);
    });

    // INSERE LIMITE NOS INPUTS DE DATA
    $(document).ready(function() {
        $("input[type=date]").attr('max', '2999-12-31');
    });

    $("#editarClienteERP").click(function() {

    })


    $('#form-buscar-ocorrencia').submit(async e => {
        e.preventDefault();

        let botao = $('#button-buscar-ocorrencia');
        let htmlBotao = botao.html();
        botao.html(iconSpinner + ' ' + htmlBotao);
        botao.attr('disabled', true);

        let ticket = $('#input-buscar-ocorrencia').val();
        limparModalBuscarOcorrencia();
        $("#containerBuscarOcorrencia").hide();
        await getInfoOcorrencia(ticket)
            .then(res => {
                if (res.status == 1) {
                    if (!res.info) return showAlert("warning",'Ticket não encontrado!');

                    $('#clienteContatado').html(res.info.clienteContatado ? 'Sim' : 'Não');
                    $('#dataCriacaoTicket').html(res.info.dataCriacao ? new Date(res.info.dataCriacao).toLocaleString() : '-');
                    $('#ultimaModificacaoTicket').html(res.info.dataModificacao ? new Date(res.info.dataModificacao).toLocaleString() : '-');
                    $('#observacoesTicket').html(res.info.observacoes || '-');
                    $('#detalhamentoTicket').html(res.info.detalhamento || '-');
                    $('#tecnologiaCriadoPor').html(res.info.criadoPor || '-');
                    $('#tecnologiaModificadoPor').html(res.info.modificadoPor || '-');
                    $('#tituloTicket').html(res.info.titulo || '-');
                    $('#nomeFantasiaTicket').html(res.info.nomeFantasia || '-');
                    $('#nomeFantasiaTicket').click(function() {
                        exibirClienteDaOcorrencia(res.info.cnpj || res.info.cpf || '')
                    });
                    $('#filaAtualTicket').html(res.info.filaAtual || '-');
                    $('#documentoTicket').html(res.info.cnpj || res.info.cpf || '-');
                    $('#placasTicket').html(res.info.placas || '-');

                    $('#assuntoTicket').html($(`#Assunto option[value='${res.info.codigoAssunto}']`).text() || '-');
                    $('#tipoTicket').html($(`#TipoOcorrencia option[value='${res.info.codigoTipoOcorrencia}']`).text() || '-');
                    $('#origemTicket').html($(`#OrigemOcorrencia option[value='${res.info.codigoOrigemOcorrencia}']`).text() || '-');
                    $('#tecnologiaTicket').html($(`#Tecnologia option[value='${res.info.codigoTecnologia}']`).text() || '-');
                    $('#statusTicket').html(['Ativa', 'Resolvida'][res.info.codigoStatusOcorrencia] || 'Cancelada');

                    let razaoStatusTicket = res.info.codigoRazaoStatusOcorrencia;
                    if (!isNaN(razaoStatusTicket)) razaoStatusTicket = $(`.statusOcorrenciaAux option[value='${razaoStatusTicket}']`).text();
                    $('#razaoStatusTicket').html(razaoStatusTicket || '-');

                    // Carrega listagem de Anotações
                    tableAnotacoesModal.clear();
                    tableAnotacoesModal.rows.add(res.info.anotacoes.data);
                    tableAnotacoesModal.columns.adjust().draw();

                    if (res.info.codigoStatusOcorrencia === 1) {
                        $('#modificado-por-avancado').html("Resolvido Por: ")
                    } else if (res.info.codigoStatusOcorrencia === 2) {
                        $('#modificado-por-avancado').html("Cancelado Por: ")
                    } else {
                        $('#modificado-por-avancado').html("Modificado Por: ")
                    }

                    $("#containerBuscarOcorrencia").show();
                } else {
                    showAlert("error","Erro ao buscar ocorrências do cliente!");
                }
            })
            .catch(err => {
                showAlert("error","Erro ao buscar ocorrências do cliente!");
            });

        botao.html(htmlBotao);
        botao.attr('disabled', false);
    });

    function exibirVisaoFinanceira() {
        const codigoCliente = buscarDadosClienteAbaAtual()?.codeERP;
        const lojaCliente = buscarDadosClienteAbaAtual()?.storeERP;
        const tokenCrm = '<?= $this->config->item("token_erp") ?>';

        if (codigoCliente && codigoCliente != "" && tokenCrm && tokenCrm != '') {
            window.open(`http://webprotheus.omnilink.com.br/?tokenCrm=${tokenCrm}&cliente=${codigoCliente}${lojaCliente}`);
        } else {
            showAlert("warning",'Não foi possível exibir a Posição Financeira!');
        }
    }

    function limparModalBuscarOcorrencia() {
        $('#clienteContatado').html('');
        $('#dataCriacaoTicket').html('');
        $('#ultimaModificacaoTicket').html('');
        $('#observacoesCriadoPor').html('');
        $('#observacoesTicket').html('');
        $('#tituloTicket').html('');
        $('#nomeFantasiaTicket').html('');
        $('#filaAtualTicket').html('');
        $('#documentoTicket').html('');
        $('#placasTicket').html('');

        $('#assuntoTicket').html('');
        $('#tipoTicket').html('');
        $('#origemTicket').html('');
        $('#tecnologiaTicket').html('');
        $('#statusTicket').html('');

        $('#razaoStatusTicket').html('');
    }

    function formatarDataISO(data) {
        let dataObject = new Date(data);
        return dataObject.toLocaleDateString('pt-br', {
            year: 'numeric',
            month: ('numeric'),
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
        });
    }


    //Pesquisa cliente
    $('#sel-pesquisa').on('change', function() {
        let tipo = $('#sel-pesquisa').val();
        if (tipo == 0) {
            $('#pesquisaDoc').show();
            $('#pesqDoc').removeAttr('disabled');
            $('#pesquisaNome').hide();
            $('#pesqNome').removeAttr('disabled', true);
            $('#pesquisaId').hide();
            $('#pesqId').removeAttr('disabled', true);
            $('#pesqUsuario').attr('disabled', true);
            $('#pesquisaUsuario').hide();
        }
        else if (tipo == 1) {
            $('#pesquisaNome').show();
            $('#pesqNome').removeAttr('disabled');
            $('#pesquisaDoc').hide();
            $('#pesqDoc').attr('disabled', true);
            $('#pesquisaId').hide();
            $('#pesqId').removeAttr('disabled', true);
            $('#pesqUsuario').attr('disabled', true);
            $('#pesquisaUsuario').hide();
        }
        else if (tipo == 2) {
            $('#pesquisaNome').hide();
            $('#pesqNome').removeAttr('disabled');
            $('#pesquisaDoc').hide();
            $('#pesqDoc').attr('disabled', true);
            $('#pesquisaId').show();
            $('#pesqId').removeAttr('disabled', true);
            $('#pesqUsuario').attr('disabled', true);
            $('#pesquisaUsuario').hide();
        }else if(tipo == 3){
            $('#pesquisaUsuario').show();
            $('#pesqUsuario').removeAttr('disabled');
            $('#pesquisaNome').hide();
            $('#pesqNome').removeAttr('disabled');
            $('#pesquisaId').hide();
            $('#pesqId').removeAttr('disabled', true);
            $('#pesquisaDoc').hide();
            $('#pesqDoc').attr('disabled', true);
        }

        // Limpa input documento
        $("#pesqDoc").val('');
        // Limpa input nome
        $("#pesqNome").val('').trigger('change');

        $("#pesquisaId").val('');
    });

    $('#pesqNome').select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/ajax_buscar_cliente_por_nome`,
            dataType: 'json',
            delay: 2000,
        },
        placeholder: "Selecione o cliente",
        allowClear: true,
        minimumInputLength: 3,
        language: "pt-BR"
    });

    $('#pesqId').select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/ajax_buscar_cliente_por_id`,
            dataType: 'json',
            delay: 2000,
        },
        placeholder: "Selecione o cliente",
        allowClear: true,
        minimumInputLength: 1,
        language: "pt-BR"
    });


    $('#pesqUsuario').select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/ajax_buscar_cliente_por_usuario`,
            dataType: 'json',
            delay: 2000,
        },
        placeholder: "Selecione o cliente",
        allowClear: true,
        minimumInputLength: 3,
        language: "pt-BR"
    });


    // SELETOR DE RASTREADORES (PRODUTOS)
    $("#tz_rastreadorid").select2(
        getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o rastreador")
    );

    // SELETOR DE PLANO
    $("#tz_plano_linkerid").select2(
        getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o plano")
    );

    let selectProvidencias = $("#tz_providenciasid").select2({
        width: '100%',
        placeholder: "Selecione a providência",
        allowClear: true,
    });

    // SELETOR DE INDICE DE REAJUSTE
    $("#tz_indice_reajusteid").select2(
        getConfigBuscaServerSideSelect2('tz_indice_reajustes', 'tz_indice_reajusteid', 'tz_name', "Selecione o índice de reajuste")
    );


    // SELETOR DE ITEM DE CONTRATO ORIGINAL
    $("#tz_item_contrato_originalid").select2(
        getConfigBuscaServerSideSelect2('tz_item_contrato_vendas', 'tz_item_contrato_vendaid', 'tz_name', "Selecione o item de contrato original")
    );

    // MOTIVO ALTERAÇÃO
    $("#tz_motivo_alteracao").select2(
        getConfigBuscaServerSideSelect2('tz_motivo_cancelamentos', 'tz_motivo_cancelamentoid', 'tz_name', "Selecione o motivo da alteração")
    );

    // PLATAFORMA
    $("#tz_plataformaid").select2(
        getConfigBuscaServerSideSelect2('tz_plataformas', 'tz_plataformaid', 'tz_name', "Selecione a plataforma")
    );

    // CENÁRIO DE VENDA
    $("#tz_cenario_vendaid").select2(
        getConfigBuscaServerSideSelect2('tz_cenario_vendas', 'tz_cenario_vendaid', 'tz_name', "Selecione o cenário de venda")
    );

    // CENÁRIO DE VENDA
    $("#tz_tecnologiaid").select2(
        getConfigBuscaServerSideSelect2('tz_tecnologias', 'tz_tecnologiaid', 'tz_name', "Selecione a tecnologia")
    );
    // VEICULO
    $("#tz_veiculoid").select2(
        getConfigBuscaServerSideSelect2('tz_veiculos', 'tz_veiculoid', 'tz_placa', "Selecione o veículo")
    );

    // ALTERACAO CONTRATO -> MOTIVO
    $("#alteracao_contrato_tz_motivoid").select2(
        getConfigBuscaServerSideSelect2('tz_motivo_manutencao_contratos', 'tz_motivo_manutencao_contratoid', 'tz_name', "Selecione o motivo")
    );
    // ALTERACAO CONTRATO -> OCORRÊNCIA
    $("#alteracao_contrato_tz_incidentid").select2({
        width: '100%',
        placeholder: "Selecione a ocorrência",
        allowClear: true,
    });
    // SERVICO CONTRATADO -> ITEM DE CONTRATO DE VENDA
    $("#servico_contratado_tz_codigo_item_contratoid").select2({
        width: '100%',
        placeholder: "Selecione o item de contrato",
        allowClear: true,
    });

    // ALTERACAO CONTRATO -> GRUPO RECEITA
    $("#servico_contratado_tz_grupo_receitaid").select2(
        getConfigBuscaServerSideSelect2('tz_grupo_receitas', 'tz_grupo_receitaid', 'tz_name', "Selecione o grupo de receita")
    );
    // ALTERACAO CONTRATO -> CLASSIFICAÇÃO PRODUTO
    $("#servico_contratado_tz_classificacao_produtoid").select2(
        getConfigBuscaServerSideSelect2('tz_classificacao_produtos', 'tz_classificacao_produtoid', 'tz_name', "Selecione a classificação de produto")
    );
    // ALTERACAO CONTRATO -> MOEDAS
    $("#servico_contratado_transactioncurrencyid").select2(
        getConfigBuscaServerSideSelect2('transactioncurrencies', 'transactioncurrencyid', 'currencyname', "Selecione a moeda")
    );

    // ALTERACAO CONTRATO -> MOEDAS
    $("#servico_contratado_tz_produtoid").select2(
        getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o serviço")
    );


    // Cliente PJ
    $(".accounts").select2(
        getConfigBuscaServerSideSelect2('accounts', 'accountid', 'name', "Selecione o cliente Pessoa Jurídica")
    );
    // Cliente PF
    $(".contacts").select2(
        getConfigBuscaServerSideSelect2('contacts', 'contactid', 'fullname', "Selecione o cliente pessoa Física")
    );
    // VEÍCULOS
    $(".tz_veiculos").select2(
        getConfigBuscaServerSideSelect2('tz_veiculos', 'tz_veiculoid', 'tz_placa', "Selecione o veículo")
    );
    // ITEM CONTRATO VENDA
    $(".tz_item_contrato_vendas").select2(
        getConfigBuscaServerSideSelect2('tz_item_contrato_vendas', 'tz_item_contrato_vendaid', 'tz_name', "Selecione o item de contrato")
    );
    // MARCAS
    $(".tz_marcas").select2(
        getConfigBuscaServerSideSelect2('tz_marcas', 'tz_marcaid', 'tz_name', "Selecione a marca")
    );
    // PRODUTOS
    $(".products").select2(
        getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o produto")
    );
    // PLATAFORMA
    $(".tz_plataformas").select2(
        getConfigBuscaServerSideSelect2('tz_plataformas', 'tz_plataformaid', 'tz_name', "Selecione a plataforma")
    );
    // TECNOLOGIA
    $(".tz_tecnologias").select2(
        getConfigBuscaServerSideSelect2('tz_tecnologias', 'tz_tecnologiaid', 'tz_name', "Selecione a tecnologia")
    );
    // GRUPO EMAILS CLIENTE
    $(".tz_grupo_emails_clientes").select2(
        getConfigBuscaServerSideSelect2('tz_tecnologias', 'tz_grupo_emails_clienteid', 'tz_name', "Selecione o grupo de email do cliente")
    );
    // MODELO
    $(".tz_modelos").select2(
        getConfigBuscaServerSideSelect2('tz_modelos', 'tz_modeloid', 'tz_name', "Selecione o modelo")
    );
    // AF
    $(".tz_afs").select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/ajax_buscar_af`,
            dataType: 'json',
            delay: 2000,
            data: function(params) {
                return {
                    q: params.term,
                }
            }
        },
        width: '100%',
        placeholder: 'Selecione o pedido de venda',
        allowClear: true,
        minimumInputLength: 3,
    });

    $(".tz_afs").change(function() {
        const data = $(this).select2('data')[0];
        if (data) {
            if (data.tz_numero_af && data.tz_numero_af != "") {
                $("#tz_numero_af").val(data.tz_numero_af);
            }
        }
    });

    $("#filas").select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/listar_filas`,
            dataType: 'json',
            delay: 2000,
            data: function(params) {
                return {
                    q: params.term,
                }
            }
        },
        width: '100%',
        placeholder: 'Selecione a fila',
        allowClear: true,
        minimumInputLength: 3,
    });

    $("#filas").change(function() {
        const data = $(this).select2('data')[0];

        if (data) {
            if (data.text && data.id) {
                $("#filas").val(data.id);
                $("#fila-nome").val(data.text);
            }

        }
    });

    $("#serialBuscarContrato").select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/listarContratosSerial`,
            dataType: 'json',
            delay: 2000,
            data: function(params) {
                return {
                    q: params.term,
                    buscaPorContratosAtivos: $('#switch-contrato-ativo').is(':checked')
                }
            }
        },
        width: '100%',
        placeholder: 'Informe o serial',
        allowClear: true,
        minimumInputLength: 3,
        language: "pt-BR"
    });

    $("#placaBuscarContrato").select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/listarContratosPlaca`,
            dataType: 'json',
            delay: 2000,
            data: function(params) {
                return {
                    q: params.term,
                    buscaPorContratosAtivos: $('#switch-contrato-ativo').is(':checked'),
                }
            },
        },
        width: '100%',
        placeholder: 'Informe a placa',
        allowClear: true,
        minimumInputLength: 3,
        language: "pt-BR"
    }).next(".select2-container").hide();

    $(document).on('keypress', 'input#pesqId', function(e) {
        var $this = $(this);
        var chave = (window.event)?event.keyCode:e.which;
        var tratarponto = $this.data('accept-dot');
        var tratarvirgula = $this.data('accept-comma');
        var aceitarPonto = (typeof tratarponto !== 'undefined' && (tratarponto == true || tratarponto == 1)?true:false);
        var aceitarVirgula = (typeof tratarvirgula !== 'undefined' && (tratarvirgula == true || tratarvirgula == 1)?true:false);
  
		if((chave > 47 && chave < 58) || (chave == 46 && aceitarPonto) || (chave == 44 && aceitarVirgula)) {
    	        return true;
  	        } else {
 			    return (chave == 8 || chave == 0)?true:false;
 		    }
    });


    $("#produto-item-os").select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/listarProdutos`,
            dataType: 'json',
            delay: 2000,
            data: function(params) {
                return {
                    q: params.term,
                }
            }
        },
        width: '100%',
        placeholder: 'Selecione um produto',
        allowClear: true,
        minimumInputLength: 3,
        language: "pt-BR"
    });

    $("#produto-item-os").change(function() {
        const data = $(this).select2('data')[0];
        if (data) {
            $("#produto-item-os").val(data?.id)
            $("#valor-unitario-item-os").val(data?.valorUnitario)
        }
    });

    $("#aprovador-itens-os").select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/buscarUsuarios`,
            dataType: 'json',
            delay: 2000,
            data: function(params) {
                return {
                    q: params.term,
                }
            }
        },
        width: '100%',
        placeholder: 'Selecione um usuário',
        allowClear: true,
        minimumInputLength: 3,
        language: "pt-BR"
    });

    $("#aprovador-itens-os").change(function() {
        const data = $(this).select2('data')[0];
        if (data) {
            $("#aprovador-itens-os").val(data?.id)
        }
    });

    /**
     * @param {String} url - entidade do crm que será realizada a busca
     * @param {String} id - id que será selecionado 
     * @param {String} name - nome que será selecionado
     * @param {Integer} munimumInputLength - número mínimo de caracteres que deverá ser digitado para ser feita a busca
     * @return {Object} - Ex.: { ajax: {} }
     * 
     */
    function getConfigBuscaServerSideSelect2(url, id, name, placeholder, minimumInputLength = 3) {
        return {
            ajax: {
                url: `${URL_PAINEL_OMNILINK}/ajax_search_select/${url}`,
                dataType: 'json',
                delay: 2000,
                data: function(params) {
                    return {
                        q: params.term,
                        id: id,
                        name: name
                    }
                }
            },
            width: '100%',
            placeholder: placeholder,
            allowClear: true,
            minimumInputLength: minimumInputLength,
        }
    }

    $("#box-fila").click(function() {
        //verifica se o checkbox está marcado e limpa a variável da fila
        if (!$('#box-fila').is(':checked')) {
            limparVariaveisFila()
        }

        $('#assunto-row').toggle("slow");
        $("#filas-row").toggle("slow");
    });

    $('#cliente-NaAvulsa').select2({
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/ajax_buscar_cliente_por_nome`,
            dataType: 'json',
            delay: 2000,
        },
        placeholder: "Selecione o cliente",
        allowClear: true,
        minimumInputLength: 3,
        language: "pt-BR"
    });

    function modalResetarChip(botao, linha, idOperadora) {
        $('#modalResetarChip').modal('show');
        $('#idOperadoraResetarChip').val(idOperadora);
        $('#linhaResetarChip').val(linha);

    }

    function resetarChip(botao) {
        btn = $(botao);
        btn.html('<i class="fa fa-spinner fa-spin"></i>');
        btn.attr('disabled', true);

        var idOperadora = $('#idOperadoraResetarChip').val();
        var linha = $('#linhaResetarChip').val();

        $.ajax({
            url: `${URL_PAINEL_OMNILINK}/resetarChip`,
            type: 'POST',
            data: {
                idOperadora: idOperadora,
                linha: linha
            },
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    // var mensagem = JSON.parse(data.dados).mensagem;
                    showAlert("success",'Linha resetada com sucesso!');
                    $('#modalResetarChip').modal('hide');
                } else {
                    showAlert("error",'Erro ao resetar linha, tente novamente');
                }
            },
            error: function(data) {
                showAlert("error",'Erro ao resetar linha, tente novamente');
                btn.html('<i class="fa fa-refresh" aria-hidden="true" style="font-size: 20px; display: block; margin: 0 auto;"></i>');
                btn.attr('disabled', false);
            },
            complete: function() {
                btn.html('<i class="fa fa-refresh" aria-hidden="true" style="font-size: 20px; display: block; margin: 0 auto;"></i>');
                btn.attr('disabled', false);
            }
        });

    }

    function atualizarLicencaContrato(botao, id) {
        btn = $(botao);
        btn.html('<i class="fa fa-spinner fa-spin"></i>');
        btn.attr('disabled', true);

        $.ajax({
            url: `${URL_PAINEL_OMNILINK}/atualizarLicencaContratoCliente`,
            type: 'POST',
            data: {
                servicoContratadoId: id,
            },
            dataType: 'json',
            success: function(data) {
                data = JSON.parse(data);
                if (data.Status === 200) {
                    showAlert("success",'Licença atualizada com sucesso!');
                } else {
                    showAlert("error",'Erro ao atualizar licença, tente novamente!');
                }
            },
            error: function(data) {
                showAlert("error",'Erro ao atualizar licença, tente novamente!');
                btn.html('<i class="fa fa-refresh" aria-hidden="true" style="font-size: 20px; display: block; margin: 0 auto;"></i>');
                btn.attr('disabled', false);
            },
            complete: function() {
                btn.html('<i class="fa fa-refresh" aria-hidden="true" style="font-size: 20px; display: block; margin: 0 auto;"></i>');
                btn.attr('disabled', false);
            }
        });
    }
</script>

<script>
    var RouterWebdesk = '<?= site_url('webdesk') ?>';
    var RouterUsuariosGestor = '<?= site_url('usuarios_gestor') ?>';
    var emailUsuarioLogado = '<?= $emailUser ?>';
    var Router = '<?= site_url('PaineisOmnilink') ?>';
</script>

<!-- VARIAVEIS E CONSTANTES PAINEL OMNILINK -->
<script type="text/javascript" src="<?php echo base_url('newAssets/js/sacOmnilink/variaveis-e-constantes-painel-omnilink.js')  . "?v=" . filesize('newAssets/js/sacOmnilink/variaveis-e-constantes-painel-omnilink.js') ?>"></script>
<!-- SCRIPTS -->
<script type="text/javascript" src="<?php echo base_url('newAssets/js/jquery-ui.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets/js/sacOmnilink/tables-painel-omnilink.js')  . "?v=" . filesize('newAssets/js/sacOmnilink/tables-painel-omnilink.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets/js/sacOmnilink/selects-painel-omnilink.js') . "?v=" . filesize('newAssets/js/sacOmnilink/selects-painel-omnilink.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets/js/sacOmnilink/forms-painel-omnilink.js') . "?v=" . filesize('newAssets/js/sacOmnilink/forms-painel-omnilink.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets/js/sacOmnilink/painel-omnilink.js') . "?v=" . filesize('newAssets/js/sacOmnilink/painel-omnilink.js') ?>"></script>
<!-- BOTÕES DATATABLES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>