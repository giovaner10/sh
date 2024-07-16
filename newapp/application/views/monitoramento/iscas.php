<h3><?=lang('monitoramento_de_iscas')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('iscas')?> >
    <?=lang('monitoramento_de_iscas')?>
</div>

<link href="<?=base_url('media/css/contas.css')?>" rel="stylesheet">

<!-- Estilo do botão status -->
<link rel="stylesheet" type="text/css" href="<?= base_url("media/css/toggle-button.css") ?>">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYK_0JnaXcWej_b62el2v38Xb4sL1ctB4&libraries=drawing"></script>
<style>
    html {
        scroll-behavior:smooth
    }

    body {
        background-color: #fff !important;
    }
    
    table {
        width: 100% !important;
    }
    .blem{
        color: red;
    }

    .container-fluid {
        padding: 0;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th, td.wordWrap {
        max-width: 100px;
        word-wrap: break-word;
        text-align: center;
    }

    .checkbox label {
        font-weight: 700;
    }
    .select-container .select-selection--single{
        height: 35px !important;
    }

    .my-1 {
        margin-top: 1em !important;
        margin-bottom: 1em !important;
    }

    .mx-1 {
        margin-left: 1em;
        margin-right: 1em;
    }

    .pt-1 {
        padding-top: 1em;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }
    
    .justify-content-end {
        justify-content: flex-end;
    }
    .align-center {
        align-items: center;
    }

    .modal-xl {
        max-width: 1300px;
        width: 100%;
    }

    .btnsTratamentos{
        float:right;
    }
    #btns_dispositivos{
        display:none;
    }
    #btns_comandos{
        display:none;
    }

    .popup-isca {
        list-style-type: none;
        padding: 0px;
    }

    .border-0 {
        border: none !important;
    }

    .tab-content {
        background-color: #fff !important;
        padding: 2rem 1rem;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .markerLabel {
        background-color: #fff;
        border-radius: 4px;
        padding: 4px;
    }

    .action-bar * {
        margin-left: 5px;
    }
    .select-selection--multiple .select-search__field{
        width:100%!important;
    }
</style>
<section id="iscas" style="min-height: calc(100vh - 10px);">
    <div class="container-fluid">
        <div class="col-sm-12">            
            <div class="col-sm-2 mapSwitch" style="float:right; margin-top:20px">
                <label class="switch" style="float:right;" >
                    <input type="checkbox" id="exibirMapa">
                    <span class="slider"></span>
                </label>
                <label style="float:right; margin-right:10px"><b>Exibir Mapa</b></label>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="col-sm-12">
            <div class="map-container" style="display: none;">
                <div id="map" style="height:60vh;"></div>
            </div>
        </div>
    </div>

    <div class="container-fluid my-1">
        <div class="col-sm-12">
            <ul class="nav nav-tabs" style="height: 38px">
                <li class="dispositivos"><a data-toggle="tab" href="#dispositivos" class="show">Dispositivos</a></li>
                <li class="todos active"><a data-toggle="tab" href="#todos" class="show">Todos</a></li>
                <li class="comandos"><a data-toggle="tab" href="#comandos" >Comandos</a></li>

                <div class="btnsTratamentos" id="btns_dispositivos">
                    <div class="action-bar d-flex justify-content-between">
                        <a class="btn btn-primary" id="btnInserirDispositivo"><i class="fa fa-plus" aria-hidden="true"></i> Inserir</a>
                        <a class="btn btn-primary" id="btnRemoverDispositivo"><i class="fa fa-trash" aria-hidden="true"></i> Remover</a>
                        <a class="btn btn-primary" id="btnLimparGrid"><i class="fa fa-hashtag" aria-hidden="true"></i> Limpar Grid</a>
                        <button id="btnModalTratamentosDispositivos" class="btn btn-primary">Tratamentos</button>
                    </div>
                </div>

                <div class="btnsTratamentos" id="btns_todos">
                    <div class="action-bar d-flex justify-content-between">
                        <div class="btn-group">
                            <label for="filters">
                                Filtrar por modo
                                <select name="filters" id="filters">
                                    <option value="" selected>Todos</option>
                                    <option value="1">Normal</option>
                                    <option value="2">Emergência por comando via GPRS</option>
                                    <option value="3">Emergência por comando via RF</option>
                                    <option value="4">Emergência por detecção de Jammer</option>
                                    <option value="5">Bateria backup com carga abaixo do limite</option>
                                    <option value="6">Embalagem de cartão aberta</option>
                                    <option value="R">Rastreado</option>
                                    <option value="B">Bloqueado</option>
                                    <option value="D">Desabilitado</option>
                                </select>
                            </label>
                        </div>

                        <div class="btn-group">
                            <button id="btnModalTratamentos" class="btn btn-primary">Tratamentos</button>
                        </div>
                    </div>
                </div>

                <div class="btnsTratamentos" id="btns_comandos">
                    <div class="action-bar d-flex justify-content-between">
                        <div class="btn-group">
                            <label for="filtroComando">
                                Filtrar por comando
                                <select name="filtroComando" id="filtroComando">
                                    <option selected value="">Todos</option>
                                    <option value="CONFIG_CONEXAO">Configurar Conexão (Veicular)</option>
                                    <option value="PARAM_ENVIO">Parâmetros de Envio - Rede Colaborativa</option>
                                    <option value="START_REDE_COLAB">Iniciar Emergência</option>
                                    <option value="STOP_REDE_COLAB">Parar Emergência</option>
                                    <option value="SOLICITAR_ICCID">Solicitar ICCID</option>
                                    <option value="SOLICITAR_CONFIG">Solicitar Configuração</option>
                                    <option value="SOLICITAR_POSICAO">Solicitar Posição</option>
                                    <option value="SOLICITAR_VERCAO_FIRMWARE">Solicitar Versão Firmware</option>
                                </select>
                            </label>
                        </div>    
                        <div class="btn-group">
                            <button id="btnModalTratamentosComandos" class="btn btn-primary">Tratamentos</button>
                        </div>
                    </div>
                </div>
            </ul>

            <div class="tab-content">
                <!-- ABA TODOS -->
                <div id="todos" class="tab-pane fade in active">
                    <div class="container-fluid">
                        <table class="table-responsive table-bordered table " id="tabelaIscas">
                            <thead>
                                <tr class="tableheader">
                                <th>ID</th>
                                <th>Serial</th>
                                <th>Rótulo</th>
                                <!-- <th>Modelo</th> -->
                                <th>Fabricante</th>
                                <th>Cliente/Estoque</th>
                                <th>Placa</th>
                                <th>Data GPS</th>
                                <th>Data Sistema</th>
                                <th class="no-sort">Latitude</th>
                                <th class="no-sort">Longitude</th>
                                <th class="no-sort">Endereço</th>
                                <th class="no-sort">Velocidade</th>
                                <th class="no-sort">Origem</th>
                                <th class="no-sort">GPRS</th>
                                <th class="no-sort">Bateria</th>
                                <th class="no-sort">Modo</th>
                                <th class="no-sort">Histórico diário</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>    
                    </div>
                </div>
                <!-- ABA DISPOSITIVOS -->
                <div id="dispositivos" class="tab-pane fade">
                    <div class="container-fluid">
                        <table class="table-responsive table-bordered table " id="tabelaDispositivos">
                            <thead>
                                <tr class="tableheader">
                                <th>ID</th>
                                <th>Serial</th>
                                <th>Rótulo</th>
                                <!-- <th>Modelo</th> -->
                                <th>Fabricante</th>
                                <th>Cliente/Estoque</th>
                                <th>Placa</th>
                                <th class="no-sort">Data GPS</th>
                                <th class="no-sort">Data Sistema</th>
                                <th class="no-sort">Latitude</th>
                                <th class="no-sort">Longitude</th>
                                <th class="no-sort">Endereço</th>
                                <th class="no-sort">Origem</th>
                                <!-- <th class="no-sort">Ignição</th> -->
                                <!-- <th class="no-sort">Velocidade</th> -->
                                <th class="no-sort">Bateria</th>
                                <!-- <th class="no-sort">RPM</th> -->
                                <th class="no-sort">Modo</th>
                                <th class="no-sort">Histótrico diário</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>    
                    </div>
                </div>
                <!-- ABA COMANDOS -->
                <div id="comandos" class="tab-pane fade">
                    <div class="container-fluid">
                        <table class="table-responsive table-bordered table " id="tabelaComandos">
                            <thead>
                                <tr class="tableheader">
                                    <th>ID</th>
                                    <th>Fabricante</th>
                                    <th>Cliente/Estoque</th>
                                    <th>Placa</th>
                                    <th class="no-sort">Comandos</th>
                                    <th class="no-sort">Data Solicitação</th>
                                    <th class="no-sort">Data Envio</th>
                                    <th class="no-sort">Data Confirmação</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>    
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="modalInserirDispositivo" tabindex="-1" role="dialog" aria-labelledby="inserirDispositivoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="migrarEquipamentoModalLabel">Inserir Dispositivo</h4>
                </div>
                <form id="formDispositivo">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <!-- <input type="text" class="form-control" id="serial" name="serial" placeholder="Serial"> -->
                                    <select class="form-control" name="tipoBusca" id="tipoBusca" style="width: 100%">
                                        <option value="id" selected>ID</option>
                                        <option value="serial"><?php echo lang('serial') ?></option>
                                        <option value="nome"><?php echo lang('nome') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group pesquisarNome" style="display: none">
                                    <select class="form-control pesqnome select" id="pesqnome" name="nome" type="text"></select>
                                </div>
                                <div class="form-group pesquisarIDSerial">
                                    <select class="form-control pesqIDSerial" id="pesqIDSerial" multiple="multiple" name="id_iscas[]" type="text"></select>
                                </div>
                            </div>
                            
                        </div>
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" id="btnReset"><?php echo lang('cancelar') ?></button>
                        <button type="submit" class="btn btn-success" id="btnInserir"><?php echo lang('inserir') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Tratamentos -->
	<div id="modalTratamentos" class="modal fade" role="dialog">
	    <div class="modal-dialog modal-xl">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h3 class="modal-title">Tratamentos</small></h3>
	            </div>
	            <div class="modal-body">

                    <ul class="nav nav-tabs tratamentos">
                        <li class="relatorioTratamentos active"><a data-toggle="tab" href="#relatorioTratamentos" class="show">Relatórios</a></li>
                        <li class="comandosTratamentos"><a data-toggle="tab" href="#comandosTratamentos" >Comandos</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="relatorioTratamentos" class="tab-pane fade in active pt-1">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        Isca selecionada
                                        <input id="iscaSelecionada" type="text" class="form-control" value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Data Início
                                        <input class="form-control" type="date" id="dataInicioRelatorio" name="dataInicioRelatorio">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Hora Início
                                        <input class="form-control" type="time" value="00:00:00" id="horaInicioRelatorio" name="horaInicioRelatorio">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Data Fim
                                        <input class="form-control" type="date" id="dataFimRelatorio" name="dataFimRelatorio">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Hora Fim
                                        <input class="form-control" type="time" value="23:59:59" id="horaFimRelatorio" name="horaFimRelatorio">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex align-center">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        Tipo de Relatório
                                        <select class="form-control" name="tipoRelatorio" id="tipoRelatorio">
                                            <option disabled selected value="">Selecionar Tipo</option>
                                            <option value="posicoes">Posições</option>
                                            <option value="eventos">Eventos</option>
                                            <option value="comandos">Comandos enviados</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide" id="relatorioPosicoes">
                                <div class="col-sm-12">
                                    <table id="tableRelatorioPosicoes" class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Equipamento</th>
                                                <th>Data</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Endereço</th>
                                                <th>Bateria</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Ajax results -->
                                        </tbody>
                                    </table>
                                </div>  
                            </div>
                            <div class="row hide" id="relatorioEventos">
                                <div class="col-sm-12">
                                    <table id="tableRelatorioEventos" class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Equipamento</th>
                                                <th>Data</th>
                                                <th>Evento</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Endereço</th>
                                                <th>Bateria</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Ajax results -->
                                        </tbody>
                                    </table>
                                </div>  
                            </div>
                            <div class="row hide" id="relatorioComandos">
                                <div class="col-sm-12">
                                    <table id="tableRelatorioComandos" class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Equipamento</th>
                                                <th>Comando</th>
                                                <th>Data Cadastro</th>
                                                <th>Data Envio</th>
                                                <th>Data Confirmação</th>
                                                <th>Status</th>
                                                <th>Usuário</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Ajax results -->
                                        </tbody>
                                    </table>
                                </div>  
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary mx-1" id="gerarRelatorio">Gerar Relatório</button>
                                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                        <div id="comandosTratamentos" class="tab-pane fade pt-1">
                            <div class="row d-flex">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Isca selecionada
                                        <input id="iscaSelecionadaComandos" type="text" class="form-control" value="" readonly>
                                        <input id="fabricante" type="text" class="hide" value="">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Tipo de Comando
                                        <select class="form-control" name="tipoComando" id="tipoComando">
                                            <option disabled selected value="">Selecionar Comando</option>
                                            <option value="CONFIG_CONEXAO">Configurar Conexão (Veicular)</option>
                                            <option value="PARAM_ENVIO">Parâmetros de Envio - Rede Colaborativa</option>
                                            <option value="START_REDE_COLAB">Iniciar Emergência</option>
                                            <option value="STOP_REDE_COLAB">Parar Emergência</option>
                                            <option value="SOLICITAR_ICCID">Solicitar ICCID</option>
                                            <option value="SOLICITAR_CONFIG">Solicitar Configuração</option>
                                            <option value="SOLICITAR_POSICAO">Solicitar Posição</option>
                                            <option value="SOLICITAR_VERCAO_FIRMWARE">Solicitar Versão Firmware</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 d-flex align-center">
                                    <button class="btn btn-primary mx-1" id="enviarComando">Enviar Comando</button>
                                </div>
                            </div>
                            <div class="row hide" id="configurarConexaoInputs">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        Autenticação
                                        <select class="form-control" name="authConexao" id="authConexao">
                                            <option disabled selected value="">Selecionar Tipo de Autenticação</option>
                                            <option value="0">Não (PAP)</option>
                                            <option value="1">Sim (CHAP)</option>
                                            <option value="A">Automático (GPRS)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        APN
                                        <input type="text" class="form-control" name="apnConexao" id="apnConexao">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                <div class="form-group">
                                        Usuário
                                        <input type="text" class="form-control" name="usuarioConexao" id="usuarioConexao">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        Senha
                                        <input type="text" class="form-control" name="senhaConexao" id="senhaConexao">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        IP_Serv_1
                                        <input type="text" class="form-control" name="ipServ1Conexao" id="ipServ1Conexao">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        Porta_Serv_1
                                        <input type="text" class="form-control" name="portaServ1Conexao" id="portaServ1Conexao">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        IP_Serv_2
                                        <input type="text" class="form-control" name="ipServ2Conexao" id="ipServ2Conexao">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        Porta_Serv_2
                                        <input type="text" class="form-control" name="portaServ2Conexao" id="portaServ2Conexao">
                                    </div>
                                </div>
                            </div>
                            <div class="row hide" id="parametrosDeEnvioInputs">
                                <div class="col-sm-3 parametroGeral">
                                    <div class="form-group">
                                        Intervalo RX em modo normal(seg):
                                        <input type="text" class="form-control" name="intervaloRX" id="intervaloRX" value="10" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroGeral">
                                    <div class="form-group ">
                                        Período RX em modo normal(ms):
                                        <input type="text" class="form-control" name="periodoRXNormal" id="periodoRXNormal" value="300" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroPersonalizado">
                                    <div class="form-group">
                                        Inter. de envio GPRS em modo normal(min):
                                        <input type="number" class="form-control" name="intervaloGPRSNormal" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSNormal" value="10">
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroGeral">
                                    <div class="form-group">
                                        Intervalo TX em emergencia(seg):
                                        <input type="text" class="form-control" name="intervaloTX" id="intervaloTX" value="2" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroGeral">
                                    <div class="form-group">
                                        Numero de transmissões em emergência:
                                        <input type="text" class="form-control" name="numeroTransm" id="numeroTransm" value="1" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroPersonalizado">
                                    <div class="form-group">
                                        Inter. de envio GPRS em modo emerg.(min):
                                        <input type="number" class="form-control" name="intervaloGPRSEmerg" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSEmerg" value="5">
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroGeral">
                                    <div class="form-group">
                                        Periodo RX em emergência(ms):
                                        <input type="text" class="form-control" name="periodoRXEmerg" id="periodoRXEmerg" value="300" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroGeral">
                                    <div class="form-group">
                                        Tempo para entrar em modo sleep(seg):
                                        <input type="number" class="form-control" name="tempoSleep" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="tempoSleep" value="60">
                                    </div>
                                </div>
                                <div class="col-sm-3 parametroGeral">
                                    <div class="form-group">
                                        Tempo para Confirmar Presença do Jammer(min):
                                        <input type="text" class="form-control" name="tempoJammer" id="tempoJammer" value="0" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide" id="solicitar">
                                <div class="col-sm-12 text-center" id="solicitarResults">
                                    <span class="fa fa-spinner fa-spin"></span>
                                </div>
                            </div>
                            <div class="row" id="tablesComandos">
                                <div class="col-sm-12">
                                    <table id="tableComandosTrat">
                                        <thead>
                                            <th class="no-sort">Comando</th>
                                            <th class="no-sort">Data Solicitação</th>
                                            <th class="no-sort">Data Envio</th>
                                            <th class="no-sort">Data Conf.</th>
                                            <th>Status</th>
                                            <th class="no-sort">Ações</th>
                                        </thead>
                                        <tbody>
                                            <!-- Ajax results -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>

	            </div>
	            <!-- <div class="modal-footer">
	                <button class="btn btn-primary" data-dismiss="modal">Imprimir</button>
	                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
	            </div> -->
	        </div>
	    </div>
	</div>

</section>

<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

<script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
<!-- <script src="<?= base_url('assets/plugins/maps/markerclusterer_google.js') ?>"></script> -->
<script type="text/javascript" charset="utf8" src="<?= base_url('assets/plugins/maps/markerwithlabel.js') ?>"></script>
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>

<script>
let timeOutComandos;
let timeOutIscas;
let timeOutDispositivos;
let timeOutTratComandos;
let tabelaComandos;
let tabelaIscas;
let tabelaDispositivos;
let relatorioPosicoes;
let relatorioEventos;
let relatorioComandos;
let tabelaTratComando;

$('#exibirMapa').prop('checked', false);

function cancelarComando(comando_id) {
    button = $(event.target);
    data = {
        cmd_id: comando_id,
    }

    button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    $.ajax({
        url: '<?= site_url("iscas/isca/ajaxCancelarComando") ?>',
        type: 'POST',
        data: data,
        success: function(callback){
            let results = JSON.parse(callback);
            alert(results.msg)
            tabelaTratComando.ajax.reload(null, false);
        },
        error: function(){
            button.attr('disabled', false).html('&times;');
            alert("Erro ao cancelar o comando, tente novamente.");
        }
    });
}


$(document).ready(function() {

    $('.pesqnome').select({
        ajax: {
            url: '<?= site_url('clientes/ajaxListSelect') ?>',
            dataType: 'json',
            delay: 1000,
        },
        placeholder: "Selecione o cliente",
        allowClear: true,
        minimumInputLength: 3,
        width: '100%'
    });
    
    $('.pesqIDSerial').select({
        ajax: {
            url: '<?= site_url('iscas/isca/ajaxListSelect') ?>',
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    tipo_busca: $("#tipoBusca").val(),
                };
            },
            delay: 1000,
        },
        multiple: true,
        placeholder: lang.selec_disps,
        allowClear: true,
        minimumInputLength: 1,
        width: '100%'
    });

    // Chama função para atualizar a tabela de iscas
    updateDatatableIscas(true);

    function porcentagemBateria(modelo,voltagem, porcentagem){
        if(modelo && voltagem) {
            if(modelo == "GL33"){
                return porcentagem + "%";
            }else if(parseFloat(voltagem) > 4.5) {
                return String(100) + "%";
            }else if(parseFloat(voltagem) < 3.1){ 
                return String(0) + "%";
            }
            voltagem = String(voltagem);
            voltagem = voltagem.substring(0,3);
            modelo = modelo.substring(0,5);
        
            var porcentagemDeModelos = {
                "ST400":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":100,
                    "4.2":100,
                    "4.1":97,
                    "4.0":86,
                    "4":86,
                    "3.9":74,
                    "3.8":61,
                    "3.7":32,
                    "3.6":8,
                    "3.5":4,
                    "3.4":2,
                    "3.3":1,
                    "3.2":0,
                    "3.1":0,
                },
                "ST420":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":97,
                    "4.2":90,
                    "4.1":86,
                    "4.0":78,
                    "4":78,
                    "3.9":65,
                    "3.8":40,
                    "3.7":16,
                    "3.6":5,
                    "3.5":0,
                    "3.4":0,
                    "3.3":0,
                    "3.2":0,
                    "3.1":0,
                },
                "ST440":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":100,
                    "4.2":100,
                    "4.1":98,
                    "4.0":96,
                    "4":96,
                    "3.9":84,
                    "3.8":69,
                    "3.7":47,
                    "3.6":17,
                    "3.5":11,
                    "3.4":4,
                    "3.3":2,
                    "3.2":1,
                    "3.1":0,
                },
                "ST480":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":100,
                    "4.2":100,
                    "4.1":98,
                    "4.0":96,
                    "4":96,
                    "3.9":84,
                    "3.8":69,
                    "3.7":47,
                    "3.6":17,
                    "3.5":11,
                    "3.4":4,
                    "3.3":2,
                    "3.2":1,
                    "3.1":0,
                },
                "ST410":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":100,
                    "4.2":100,
                    "4.1":97,
                    "4.0":86,
                    "4":86,
                    "3.9":74,
                    "3.8":61,
                    "3.7":32,
                    "3.6":8,
                    "3.5":4,
                    "3.4":2,
                    "3.3":1,
                    "3.2":0,
                    "3.1":0,
                },
                "ST390":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":100,
                    "4.2":100,
                    "4.1":100,
                    "4.0":99,
                    "4":99,
                    "3.9":75,
                    "3.8":53,
                    "3.7":18,
                    "3.6":3,
                    "3.5":2,
                    "3.4":1,
                    "3.3":0,
                    "3.2":0,
                    "3.1":0,
                },
                "ST419":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":100,
                    "4.2":100,
                    "4.1":97,
                    "4.0":86,
                    "4":86,
                    "3.9":74,
                    "3.8":61,
                    "3.7":32,
                    "3.6":8,
                    "3.5":4,
                    "3.4":2,
                    "3.3":1,
                    "3.2":0,
                    "3.1":0,
                },
                "ST449":{
                    "4.5":100,
                    "4.4":100,
                    "4.3":100,
                    "4.2":100,
                    "4.1":97,
                    "4.0":86,
                    "4":86,
                    "3.9":74,
                    "3.8":61,
                    "3.7":32,
                    "3.6":8,
                    "3.5":4,
                    "3.4":2,
                    "3.3":1,
                    "3.2":0,
                    "3.1":0,
                },
            };
            
            if(porcentagemDeModelos[modelo] && porcentagemDeModelos[modelo][voltagem] !== undefined) {
                return porcentagemDeModelos[modelo][voltagem] + "%";
            }
        }

        return '';
    }

    function formatDateTime(dateObject) {
        var d = new Date(dateObject);
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        var hour = d.getHours();
        var minute = d.getMinutes();
        var second = d.getSeconds();

        if (day < 10) {
            day = "0" + day;
        }
        if (month < 10) {
            month = "0" + month;
        }
        if (hour < 10) {
            hour = "0" + hour;
        }
        if (minute < 10) {
            minute = "0" + minute;
        }
        if (second < 10) {
            second = "0" + second;
        }
        var date = day + "/" + month + "/" + year
                    + " " + hour + ":" + minute + ":" + second;

        return date;
    };

    function definirModo(modo) {
        switch(modo) {
            case "1":
                return "Normal";
            case "2":
                return "Emergência por comando via GPRS";
            case "3":
                return "Emergência por comando via RF";
            case "4":
                return "Emergência por comando via detecção de Jammer";
            case "5":
                return "Bateria backup com carga abaixo do limite";
            case "6":
                return "Embalagem de cartão aberta";
            case "R":
                return "Rastreado";
            case "B":
                return "Bloqueado";
            case "D":
                return "Desabilitado";
            default:
                return "";
        }
    }
    
    
    /**
     * Função que atualiza tabela de iscas e define um timeout caso update seja true
     */
    function updateDatatableIscas(update){
        var site_url = "<?= site_url(); ?>";
        if(update){ // caso update seja true, seta um timeout chamando a função novamente
            if($.fn.DataTable.isDataTable( '#tabelaIscas' ) == false){//caso não seja um datatable, instancia
                tabelaIscas = $('#tabelaIscas').DataTable({
                    "responsive": true,
                    "aLengthMenu": [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
                    "dom": 'Blfrtip',
                    "buttons": [],
                    "oLanguage": {
                        "sEmptyTable": "Nenhum registro encontrado.",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "<div><i class='fa fa-spinner fa-spin'></i><div>",
                        "sZeroRecords": "Nenhum registro encontrado.",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        },
                        "select": {
                            "rows": {
                                "_": " Você selecionou %d linhas.",
                                "0": " Clique numa linha para selecionar.",
                                "1": " 1 linha selecionada."
                            }
                        }
                    },
                    "bAutoWidth": false,
                    "autoWidth": false,
                    "processing": true,
                    "serverSide": true,
                    "searchDelay": 1500,
                    "order": [[6, "desc"]],
                    "ajax": {
                        "url":"<?= site_url('/monitoramento_iscas/ajax_list_iscas') ?>",
                        "type": "POST",
                        "data": function (data) {
                            data.filters = $('#filters').children("option:selected").val();
                        },
                        "error": function (xhr, error, thrown) {
                            // alert('<?php // echo lang('sessao_expirada') ?>');
                            // location.reload();
                        },
                        "dataSrc": function ( json ) {
                            if(!json.ceabs){
                                tabelaIscas.column(4).visible(false)
                            } else {
                                tabelaIscas.column(4).visible(true)
                            }
                            for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                                json.data[i][0] = json.data[i].id;
                                json.data[i][1] = json.data[i].serial;
                                json.data[i][2] = json.data[i].rotulo;
                                // json.data[i][3] = json.data[i].modelo;
                                json.data[i][3] = json.data[i].fabricante;
                                json.data[i][4] = json.data[i].cliente;
                                json.data[i][5] = json.data[i].placa;
                                json.data[i][6] = json.data[i].dataGPS ? formatDateTime(json.data[i].dataGPS) : '';
                                json.data[i][7] = json.data[i].dataSis ? formatDateTime(json.data[i].dataSis) : '';
                                json.data[i][8] = json.data[i].lat;
                                json.data[i][9] = json.data[i].lng;
                                json.data[i][10] = (json.data[i].lat === null || json.data[i].lat === ""
                                                    || json.data[i].lng === null || json.data[i].lng === "")
                                                    ? "" : "<a href='#' onclick='centralizarMapa(" + json.data[i].lng + ',' + json.data[i].lat + ")'>"+ json.data[i].endereco + "</a>";
                                json.data[i][11] = json.data[i].velocidade;
                                json.data[i][12] = json.data[i].origem;
                                json.data[i][13] = json.data[i].gprs;
                                json.data[i][14] = json.data[i].serial.substring(0, 2) === 'I3' ? json.data[i].porcentagem + '%' : porcentagemBateria(json.data[i].modelo,json.data[i].voltagem, json.data[i].porcentagem); 
                                json.data[i][15] = definirModo(json.data[i].modo);
                                json.data[i][16] = '<a href="<?= site_url('relatorio_iscas/getHistoricoDia/') ?>/'+json.data[i].serial+'" class="btn btn-sm btn-primary" title="Visualizar"><i class="fa fa-file"></i></a>';
                                
                            }
                            return json.data;
                        },
                        "columns": [
                            {data: 0},
                            {data: 1},
                            {data: 2},
                            {data: 3},
                            {data: 4},
                            {data: 5},
                            {data: 6},
                            {data: 7},
                            {data: 8},
                            {data: 9},
                            {data: 10},
                            {data: 11},
                            {data: 12},
                            {data: 13},
                            {data: 14},
                            
                            
                        ],
                    },
                    "columnDefs": [
                        { targets: "no-sort", orderable: false },
                        { targets: 0, class: "wordWrap" },
                        { targets: 1, class: "wordWrap" }
                    ],
                    "select": {
                        style: 'single',
                        selector: 'td:not(:nth-child(11))'
                    },
                    "fnDrawCallback": function() {
                        carregarMapa($('#tabelaIscas').DataTable());
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            title: 'Monitoramento de Iscas - Status Atual',
                            messageTop: function(){
                                date = new Date();
                                return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                            },
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            extend: 'pdfHtml5',
                            className: 'dt-button buttons-pdf buttons-html5 btn btn-outline-primary',
                            exportOptions: {
                                columns: ':visible',
                                modified: {
                                    page: 'all',
                                },
                            },
                            action: function (e, dt, node, config) {
                                let self = this;
                                let start = dt.page.info().start;
                                let length = dt.page.len();
        
                                dt.one('preXhr', function (e, settings, data) {
                                    data.start = 0;
                                    data.length = dt.page.info().recordsTotal;

                                    dt.one('preDraw', function (e, settings) {
                                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, node, config);
        
                                        dt.one('preXhr', function (e, settings, data) {
                                            data.start = start;
                                            data.length = length;
                                        });
        
                                        setTimeout(dt.ajax.reload, 0);
        
                                        return false;
                                    });
                                });
        
                                dt.ajax.reload();
						    },
                        },
                        {
                            title: 'Monitoramento de Iscas - Status Atual',
                            messageTop: function(){
                                date = new Date();
                                return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                            },
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            extend: 'excel',
                            className: 'dt-button buttons-pdf buttons-html5 btn btn-outline-primary',
                            exportOptions: {
                                columns: ':visible',
                                modified: {
                                    page: 'all',
                                },
                            },
                            action: function (e, dt, node, config) {
                                let self = this;
                                let start = dt.page.info().start;
                                let length = dt.page.len();
        
                                dt.one('preXhr', function (e, settings, data) {
                                    data.start = 0;
                                    data.length = dt.page.info().recordsTotal;
        
                                    dt.one('preDraw', function (e, settings) {
                                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, node, config);
        
                                        dt.one('preXhr', function (e, settings, data) {
                                            data.start = start;
                                            data.length = length;
                                        });
        
                                        setTimeout(dt.ajax.reload, 0);
        
                                        return false;
                                    });
                                });
        
                                dt.ajax.reload();
						    },
                        },
                        {
                            title: 'Monitoramento de Iscas - Status Atual',
                            messageTop: function(){
                                date = new Date();
                                return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                            },
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            extend: 'csv',
                            className: 'dt-button buttons-pdf buttons-html5 btn btn-outline-primary',
                            exportOptions: {
                                columns: ':visible',
                                modified: {
                                    page: 'all',
                                },
                            },
                            action: function (e, dt, node, config) {
                                let self = this;
                                let start = dt.page.info().start;
                                let length = dt.page.len();
        
                                dt.one('preXhr', function (e, settings, data) {
                                    data.start = 0;
                                    data.length = dt.page.info().recordsTotal;
        
                                    dt.one('preDraw', function (e, settings) {
                                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, node, config);
        
                                        dt.one('preXhr', function (e, settings, data) {
                                            data.start = start;
                                            data.length = length;
                                        });
        
                                        setTimeout(dt.ajax.reload, 0);
        
                                        return false;
                                    });
                                });
        
                                dt.ajax.reload();
						    },
                        },
                        {
                            title: 'Monitoramento de Iscas - Status Atual',
                            messageTop: function(){
                                date = new Date();
                                return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                            },
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            text: 'Imprimir',
                            extend: 'print',
                            className: 'dt-button buttons-pdf buttons-html5 btn btn-outline-primary',
                            exportOptions: {
                                columns: ':visible',
                                modified: {
                                    page: 'all',
                                },
                            },
                            action: function (e, dt, node, config) {
                                let self = this;
                                let start = dt.page.info().start;
                                let length = dt.page.len();
        
                                dt.one('preXhr', function (e, settings, data) {
                                    data.start = 0;
                                    data.length = dt.page.info().recordsTotal;
        
                                    dt.one('preDraw', function (e, settings) {
                                        $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, node, config);
        
                                        dt.one('preXhr', function (e, settings, data) {
                                            data.start = start;
                                            data.length = length;
                                        });
        
                                        setTimeout(dt.ajax.reload, 0);
        
                                        return false;
                                    });
                                });
        
                                dt.ajax.reload();
						    },
                        },
                    ],
                });
            }else{//caso seja, chama o ajax novamente
                tabelaIscas.ajax.reload(null, false);
            }

            // Seta o timeout
            timeOutIscas = setTimeout(function() {
                updateDatatableIscas(true);
            }, 50000);
        }
        else{ // caso contrario, limpa o timeout
            clearTimeout(timeOutIscas);
        }
    }
    // define filtro para datatable de iscas
    $("#filters").change(function() {
        tabelaIscas.ajax.reload(null, false);
    });

    // define filtro para datatable de iscas
    $("#filtroComando").change(function() {
        tabelaComandos.ajax.reload(null, false);
    });
    /**
     * Função que atualiza tabela de dispositivos e define um timeout caso update seja true
     */
    function updateDatatableDispositivos(update){
        if(update){ // caso update seja true, seta um timeout chamando a função novamente
            if($.fn.DataTable.isDataTable( '#tabelaDispositivos' ) == false){//caso não seja um datatable, instancia
                tabelaDispositivos = $('#tabelaDispositivos').DataTable({
                    "responsive": true,
                    "aLengthMenu": [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
                    "dom": 'Blfrtip',
                    "buttons": [],
                    "oLanguage": {
                        "sEmptyTable": "Nenhum registro encontrado.",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "<div><i class='fa fa-spinner fa-spin'></i><div>",
                        "sZeroRecords": "Nenhum registro encontrado.",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        },
                        "select": {
                            "rows": {
                                "_": " Você selecionou %d linhas.",
                                "0": " Clique numa linha para selecionar.",
                                "1": " 1 linha selecionada."
                            }
                        }
                    },
                    "bAutoWidth": false,
                    "autoWidth": false,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url":"<?= site_url('/monitoramento_iscas/ajax_list_dispositivos') ?>",
                        "type": "POST",
                        "data": function (data) {
                            data.filters = $('#filters').children("option:selected").val();
                        },
                        "error": function (xhr, error, thrown) {
                            // alert('<?php // echo lang('sessao_expirada') ?>');
                            // location.reload();
                        },
                        "dataSrc": function ( json ) {
                            for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                                let modo = json.data[i][12];
                                json.data[i][6] = json.data[i][6] ? formatDateTime(json.data[i][6]) : '';
                                json.data[i][7] = json.data[i][7] ? formatDateTime(json.data[i][7]) : '';
                                json.data[i][10] = (json.data[i][8] === null || json.data[i][8] === ""
                                                    || json.data[i][9] === null || json.data[i][9] === "")
                                                    ? "" : "<a href='#' onclick='centralizarMapa(" + json.data[i][9] + ',' + json.data[i][8] + ")'>"+ json.data[i][14] + "</a>";
                                json.data[i][12] = porcentagemBateria(json.data[i][13],json.data[i][15]);
                                json.data[i][13] = definirModo(modo); 
                            }
                            return json.data;
                        },
                        "columns": [
                            {data: 0},
                            {data: 1},
                            {data: 2},
                            {data: 3},
                            {data: 4},
                            {data: 5},
                            {data: 6},
                            {data: 7},
                            {data: 8},
                            {data: 9},
                            {data: 10},
                            {data: 11},
                            {data: 12},
                            {data: 13},
                        ],
                    },
                    "columnDefs": [
                        { targets: "no-sort", orderable: false },
                        { targets: 1, class: "wordWrap" },
                        { targets: 2, class: "wordWrap" }
                    ],
                    "select": {
                        style: 'single',
                        selector: 'td:not(:nth-child(11))'
                    },
                    "fnDrawCallback": function() {
                        carregarMapa($('#tabelaDispositivos').DataTable());
                    },

                });
            }else{//caso seja, chama o ajax novamente
                tabelaDispositivos.ajax.reload(null, false);
            }

            // Seta o timeout
            timeOutDispositivos = setTimeout(function() {
                updateDatatableDispositivos(true);
            }, 20000);
        }
        else{ // caso contrario, limpa o timeout
            clearTimeout(timeOutDispositivos);
        }
    }

    

    /**
     * Função que atualiza tabela de comandos e define um timeout caso update seja true
     */
    function updateDatatableComandos(update){
        if(update){// caso update seja true, seta um timeout chamando a função novamente
            if($.fn.DataTable.isDataTable( '#tabelaComandos' ) == false){//caso não seja um datatable, instancia
                // TABELA COMANDOS
                tabelaComandos = $('#tabelaComandos').DataTable({
                    "responsive": true,
                    "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, 'Todos']],
                    "dom": 'Blfrtip',
                    "buttons": [],
                    "oLanguage": {
                        "sEmptyTable": "Nenhum registro encontrado.",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "<div><i class='fa fa-spinner fa-spin'></i><div>",
                        "sZeroRecords": "Nenhum registro encontrado.",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        },
                        "select": {
                            "rows": {
                                "_": " Você selecionou %d linhas.",
                                "0": " Clique numa linha para selecionar.",
                                "1": " 1 linha selecionada."
                            }
                        }
                    },
                    "bAutoWidth": false,
                    "autoWidth": false,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url":"<?= site_url('/monitoramento_iscas/ajax_list_comandos') ?>",
                        "type": "POST",
                        "data": function (data) {
                            data.filters = $('#filtroComando').children("option:selected").val();
                        },
                        "error": function (xhr, error, thrown) {
                            // alert('<?php // echo lang('sessao_expirada') ?>');
                            // location.reload();
                        },
                        "dataSrc": function ( json ) {
                            
                            for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                                json.data[i][5] = json.data[i][5] ? formatDateTime(json.data[i][5]) : '';
                                json.data[i][6] = json.data[i][6] ? formatDateTime(json.data[i][6]) : '';
                                json.data[i][7] = json.data[i][7] ? formatDateTime(json.data[i][7]) : '';
                            }
                            return json.data;
                        },
                        "columns": [
                            {data: 0},
                            {data: 1},
                            {data: 2},
                            {data: 3},
                            {data: 4},
                            {data: 5},
                            {data: 6},
                            {data: 7},
                        ],
                    },
                    "columnDefs": [
                        { targets: "no-sort", orderable: false },
                        { targets: 0, class: "wordWrap" }
                    ],
                    "select": {
                        style: 'single'
                    },
    
                });
            }else{//caso seja, chama o ajax novamente
                tabelaComandos.ajax.reload(null, false);
            }
    
            // Seta o timeout
            timeOutComandos = setTimeout(() => {
                updateDatatableComandos(true)
            }, 20000);
        }else{// caso contrario, limpa o timeout
            clearTimeout(timeOutComandos);
            
        }
    }
    
    // Click datatable Dispositivos
    $('#tabelaDispositivos tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            tabelaDispositivos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
 
    $('#button').click( function () {
        tabelaDispositivos.row('.selected').remove().draw( false );
    } );
    
    // exibe modal para inserir isca
    $("#btnInserirDispositivo").click(function(){
        $('#pesqnome').val('').trigger('change');
        $('#pesqIDSerial').val(['']).trigger('change');
        $('#tipoBusca').val('id');
        $('.pesquisarNome').hide();
        $('.pesquisarIDSerial').show();
        $("#modalInserirDispositivo").modal('show');
    });

    // reseta modal de inserir iscas
    $("#btnReset").click(function(){
        $('#pesqnome').val('').trigger('change');
        $('#pesqIDSerial').val(['']).trigger('change');
        $('#tipoBusca').val('id');

        $("#modalInserirDispositivo").modal('hide');
    });
    
    // insere dispositivo no grid
    $("#formDispositivo").submit(function(event){
        event.preventDefault();

        let btn = $("#btnInserir");
        let tipo = $("#tipoBusca").val();

        let input = '';
        if(tipo == 'nome'){
            input = $('#pesqnome').val();
        } else {
            let iscas = [];
            
            let dados = $(this).serializeArray();
            dados.forEach(element => {
                if (element.name == "id_iscas[]") {
                    iscas.push(element.value)
                }    
            });

            if(iscas.length > 0){
                input = iscas.join(';')
            }else{
                alert(lang.nenhuma_isca)
                return false;
            }
        }

        const data = {
            tipo: tipo,
            input: input,
        };

        btn.attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> Inserindo")
        $.ajax({
            url: "<?= site_url('/monitoramento_iscas/ajax_inserir_dispositivo') ?>",
            type: 'post',
            data: data,
            success: function(callback){
                btn.attr('disabled',false).html("Inserir");
                const resposta = parseJson(callback);
                if(resposta != null && resposta.status){
                    
                    if(resposta.iscas_inseridas.length == 0){
                        alert(`${lang.disps_nao_inseridos}: ${resposta.iscas_nao_inseridas.join(', ')}.`);
                    }else{
                        if(resposta.iscas_inseridas.length != 0 && resposta.iscas_nao_inseridas.length != 0){
                            alert(`${lang.disps_inseridos}: ${resposta.iscas_inseridas.join(', ')}. \n\n${lang.disps_nao_inseridos}: ${resposta.iscas_nao_inseridas.join(', ')}. \n\n${lang.info_disps_inseridos}`);
                        }else{
                            alert(`${resposta.msg}\n\n${lang.info_disps_inseridos}`);
                        }
                    }
                    
                    $("#modalInserirDispositivo").modal('hide');
                    // Para chamada anterior
                    updateDatatableDispositivos(false);
                    // Chama função para atualizar datatable Dispositivos
                    updateDatatableDispositivos(true);
                }else{
                    alert("Erro ao Inserir Dispositivos");
                }
            },
            error: function(error){
                alert("Erro ao Inserir Dispositivos");
                btn.attr('disabled',false).html("Inserir");
            }
        });

        return false;
    });
    // Remover Dispositivo na grid
    $("#btnRemoverDispositivo").click(function(){
        const row = tabelaDispositivos.row('.selected').data();
        if(row != undefined){
            const id_isca = tabelaDispositivos.row('.selected').data()[0];
            const confirma = confirm("Você deseja remover o dispositivo da grid de monitoramento?");
            let btn = $("#btnRemoverDispositivo");
            btn.attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> Removendo")
            if(confirma){
                $.ajax({
                    url: "<?= site_url('/monitoramento_iscas/ajax_remover_dispositivo') ?>",
                    type: 'post',
                    data: {id_isca: id_isca},
                    success: function(callback){
                        btn.attr('disabled',false).html('<i class="fa fa-trash" aria-hidden="true"></i> Remover');
                        const resposta = parseJson(callback);
                        alert(resposta.msg);
                        // Para chamada anterior
                        updateDatatableDispositivos(false);
                        // Chama função para atualizar datatable Dispositivos
                        updateDatatableDispositivos(true);
                        
                    },
                    error: function(error){
                        alert('Erro ao remover dispositivo');
                        btn.attr('disabled',false).html('<i class="fa fa-trash" aria-hidden="true"></i> Remover');
                    }
                });
            } else {
                btn.attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> Removendo")
            }

        }else{
            alert('Selecione um Dispositivo.');
        }
    });
    // limpar Grid
    $("#btnLimparGrid").click(function(){
        let btn = $("#btnLimparGrid");
        const rows = tabelaDispositivos.rows().data();
        if(rows.length == 0){
            alert("Não é possível remover pois nenhum está sendo exibido na grid.");
        }else{
            const confirma = confirm("Você deseja limpar o grid de monitoramento?");
            if(confirma){
                btn.attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> Limpando");
                $.ajax({
                    url: "<?= site_url('/monitoramento_iscas/ajax_limpar_grid_dispositivo') ?>",
                    type: 'get',
                    success: function(callback){
                        btn.attr('disabled',false).html('<i class="fa fa-hashtag" aria-hidden="true"></i> Limpar Grid');
                        resposta = parseJson(callback);
                        alert(resposta.msg);
                        // Para chamada anterior
                        updateDatatableDispositivos(false);
                        // Chama função para atualizar datatable Dispositivos
                        updateDatatableDispositivos(true);
                    },
                    error: function(error){
                        btn.attr('disabled',false).html('<i class="fa fa-hashtag" aria-hidden="true"></i> Limpar Grid');
                        alert('Erro ao limpar grid');
                    },
                });
            }
        }
    });

    $("#tipoBusca").change(function(){
        const tipo = $(this).val();
        if(tipo == 'id'){
            $('#pesqIDSerial').val(['']).trigger('change');

            $('.pesquisarNome').hide();
            $('.pesquisarIDSerial').show();
        }else if(tipo == 'serial') {
            $('#pesqIDSerial').val(['']).trigger('change');

            $('.pesquisarNome').hide();
            $('.pesquisarIDSerial').show();
        } else {
            $('#pesqnome').val('').trigger('change');

            $('.pesquisarNome').show();
            $('.pesquisarIDSerial').hide();
        }
    });

    var ultimoIntervaloGPRSNormal = 10;
    var ultimoIntervaloGPRSEmerg = 5;
    var ultimoTempoSleep = 60;

    /*
    *   Função que atualiza tabela de comandos em tratamentos e define um timeout caso update seja true
    */
    function updateDatatableTratComandos(update) {
        if(update) {
            if($.fn.DataTable.isDataTable( '#tableComandosTrat' ) == false){//caso não seja um datatable, instancia
                // TABELA COMANDOS TRATAMENTOS
                tabelaTratComando = $('#tableComandosTrat').DataTable({
                    "responsive": true,
                    "ordering": false,
                    "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, 'Todos']],
                    "dom": 'Blfrtip',
                    "buttons": [],
                    "oLanguage": {
                        "sEmptyTable": "Nenhum registro encontrado.",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "",
                        "sProcessing": "<div><i class='fa fa-spinner fa-spin'></i><div>",
                        "sZeroRecords": "Nenhum registro encontrado.",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        }
                    },
                    "bAutoWidth": false,
                    "autoWidth": false,
                    "processing": true,
                    "ajax": {
                        "url":"<?= site_url('/iscas/isca/ajaxComandosIsca') ?>",
                        "type": "POST",
                        "data": function (data) {
                            data.iscaSelecionada = $('#iscaSelecionadaComandos').val();
                        },
                        "error": function (xhr, error, thrown) {
                            // alert('<?php // echo lang('sessao_expirada') ?>');
                            // location.reload();
                        },
                        "dataSrc": function ( json ) {                            
                            string_comando = false;
                            let cmd_param_envio_confirmado= false;
                            
                            for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                                if(!cmd_param_envio_confirmado && json.data[i][6] && json.data[i][0] == "Parâmetros de Envio - Rede Colaborativa" && json.data[i][4] == "Confirmado"){
                                    string_comando = json.data[i][6];
                                    cmd_param_envio_confirmado = true;
                                }

                                json.data[i][1] = json.data[i][1] ? formatDateTime(json.data[i][1]) : '';
                                json.data[i][2] = json.data[i][2] ? formatDateTime(json.data[i][2]) : '';
                                json.data[i][3] = json.data[i][3] ? formatDateTime(json.data[i][3]) : '';
                                (json.data[i][4] == 'Aguardando Envio' ? json.data[i][5] = '<button class="btn btn-danger" title="Cancelar comando" onclick="cancelarComando('+ json.data[i][5] + ')">&times;</button>' : json.data[i][5] = '');
                            }

                            if(string_comando){
                                let parametros = string_comando.split(':')[1];
                                ultimoIntervaloGPRSNormal = parametros.split(',')[2];
                                ultimoIntervaloGPRSEmerg = parametros.split(',')[5];
                                ultimoTempoSleep = parametros.split(',')[7];
                            }
                            return json.data;
                        },
                        "columns": [
                            {data: 0},
                            {data: 1},
                            {data: 2},
                            {data: 3},
                            {data: 4},
                            {data: 5},
                        ],
                    },
                    "columnDefs": [
                        { targets: "no-sort", orderable: false },
                        { targets: 0, class: "wordWrap" },
                    ],
    
                });
            }else{//caso seja, chama o ajax novamente
                tabelaTratComando.ajax.reload(null, false);
            }
    
            // Seta o timeout
            timeOutTratComandos = setTimeout(() => {
                updateDatatableTratComandos(true)
            }, 20000);
        }else{// caso contrario, limpa o timeout
            clearTimeout(timeOutTratComandos); 
        }
    }

    $('.nav.nav-tabs>li').click(function(){
        let tabActive = $(this).attr('class');
        
        switch (tabActive) {
            case 'todos':
                // Chama função para atualizar datatable iscas
                updateDatatableIscas(true);
                // Para update de comandos
                updateDatatableComandos(false);
                // Para update Dispositivos
                updateDatatableDispositivos(false);

                // Exibe botões de tratamento
                $("#btns_dispositivos").css("display","none");
                $("#btns_todos").css("display","block");
                $("#btns_comandos").css("display","none");
                
                $('.mapSwitch').show();

                break;
            case 'comandos':
                // Para update Iscas
                updateDatatableIscas(false);
                // Chama função para atualizar datatable comandos
                updateDatatableComandos(true);
                // Para update Dispositivos
                updateDatatableDispositivos(false);

                // Exibe botões de tratamento
                $("#btns_dispositivos").css("display","none");
                $("#btns_todos").css("display","none");
                $("#btns_comandos").css("display","block");

                $('.mapSwitch').hide();
                $('#exibirMapa').prop('checked', false);
                $(".map-container").hide();

                break;
            case 'dispositivos':
                // Para update Iscas
                updateDatatableIscas(false);
                // Para update Iscas
                updateDatatableComandos(false);
                // Chama função para atualizar datatable Dispositivos
                updateDatatableDispositivos(true);

                // Exibe botões de tratamento
                $("#btns_dispositivos").css("display","block");
                $("#btns_todos").css("display","none");
                $("#btns_comandos").css("display","none");
                
                $('.mapSwitch').show();

                break;
            default:
                break;
        }
        
    });

    function parseJson(callback){
        try {
            return JSON.parse(callback);
        } catch (error) {
            return null;
        }
    }

    // Atualizar a tabela de comandos em tratamentos apenas se a aba estiver selecionada
    $('.nav.nav-tabs.tratamentos>li').click(function(){
        let tabActive = $(this).attr('class');

        switch (tabActive) {
            case 'comandosTratamentos':
                // Chama função para atualizar datatable comandos em tratamentos
                updateDatatableTratComandos(true);
                break;
            default:
                updateDatatableTratComandos(false);
                break;
        }
        
    });

    $('#modalTratamentos').on('hidden.bs.modal', function () {
        updateDatatableTratComandos(false);
    })

     // Cria Datatable Posicoes
     relatorioPosicoes = $('#tableRelatorioPosicoes').DataTable({
        responsive: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        buttons: [
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 11;
                    doc.styles.tableHeader.fontSize = 11;
                    doc.content[1].table.widths = ["15%", "20%", "15%", "15%", "25%", "10%"];
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'Imprimir',
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
        ],
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado.",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado.",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        
    });

    // Cria Datatable Eventos
    relatorioEventos = $('#tableRelatorioEventos').DataTable({
        responsive: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        buttons: [
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 11;
                    doc.styles.tableHeader.fontSize = 11;
                    doc.content[1].table.widths = ["15%", "20%", "15%", "10%", "10%", "20%", "10%"];
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'Imprimir',
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
        ],
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado.",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado.",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        
    });

    // Cria Datatable Comandos
    relatorioComandos = $('#tableRelatorioComandos').DataTable({
        responsive: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        buttons: [
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 11;
                    doc.styles.tableHeader.fontSize = 11;
                    doc.content[1].table.widths = ["5%", "10%", "10%", "15%", "15%", "15%", "15%", "15%"];
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'Imprimir',
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
        ],
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado.",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado.",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
        },
        
    });

    /*
    * Lista iscas encontradas no banco pelos fitros definidos e mostrar na datatables 
    */
    function gerarRelatorio(data){
        let button          = $("#gerarRelatorio");
        let tabelaPosicoes  = $('#relatorioPosicoes');
        let tabelaEventos   = $('#relatorioPosicoes');
        let tabelaComandos  = $('#relatorioComandos');
        
        switch(data.tipoRelatorio) {
            case 'posicoes':
                tabelaEventos.addClass('hide');
                gerarRelatorioPosicoes(data, button);
                break;
            case 'eventos':
                tabelaPosicoes.addClass('hide');
                gerarRelatorioEventos(data, button);
                break;
            case 'comandos':
                tabelaComandos.addClass('hide');
                gerarRelatorioComandos(data, button);
                break;
            default:
                alert("Selecione o tipo de relatório.");
        }
        
    }

    function gerarRelatorioPosicoes(data, button) {
        let tableContainer = $('#relatorioPosicoes');
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Gerando');
        tableContainer.addClass('hide');
        
        relatorioPosicoes.rows().remove().draw();
        $.ajax({
            url: '<?= site_url("iscas/isca/ajaxRelatorio") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                let results = JSON.parse(callback);
                if(results.status == true && results.dados.Items.length > 0) {
                    results.dados.Items.forEach(linha => {
                        relatorioPosicoes.row.add([
                            linha.ID_OBJECT_TRACKER,
                            formatDateTime(linha.DATA),
                            linha.Y,
                            linha.X,
                            (linha.Y && linha.X && linha.ENDERECO ? '<a href="https://www.google.com/maps/?q='+ linha.X + ',' + linha.Y + '" target="_blank">' + linha.ENDERECO + '</a>' : ''),
                            porcentagemBateria(linha.model, linha.VOLTAGE),                  
                        ]).draw();
                    });
                }

                tableContainer.removeClass('hide');
                button.attr('disabled', false).html('Gerar Relatório');
            },
            error: function(){
                button.attr('disabled', false).html('Gerar Relatório');
                alert("Erro ao gerar o relatório, tente novamente.");
            }
        });
    }

    function gerarRelatorioEventos(data, button) {
        let tableContainer = $('#relatorioEventos');
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Gerando');
        tableContainer.addClass('hide');
        
        relatorioEventos.rows().remove().draw();
        $.ajax({
            url: '<?= site_url("iscas/isca/ajaxRelatorio") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                let results = JSON.parse(callback);
                if(results.status == true && results.dados.Items.length > 0) {
                    results.dados.Items.forEach(linha => {
                        relatorioEventos.row.add([
                            linha.ID_OBJECT_TRACKER,
                            formatDateTime(linha.DATA),
                            (linha.Status ? definirModo(linha.Status) : ''),
                            linha.Y,
                            linha.X,
                            (linha.Y && linha.X && linha.ENDERECO ? '<a href="https://www.google.com/maps/?q='+ linha.X + ',' + linha.Y + '" target="_blank">' + linha.ENDERECO + '</a>' : ''),
                            porcentagemBateria(linha.model, linha.VOLTAGE),                  
                        ]).draw();
                    });
                }

                tableContainer.removeClass('hide');
                button.attr('disabled', false).html('Gerar Relatório');
            },
            error: function(){
                button.attr('disabled', false).html('Gerar Relatório');
                alert("Erro ao gerar o relatório, tente novamente.");
            }
        });
    }

    function gerarRelatorioComandos(data, button) {
        let tableContainer = $('#relatorioComandos');
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Gerando');
        tableContainer.addClass('hide');
        
        relatorioComandos.rows().remove().draw();
        $.ajax({
            url: '<?= site_url("iscas/isca/ajaxRelatorioComandos") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                let results = JSON.parse(callback);
                if(results.status == true && results.dados.length > 0) {
                    results.dados.forEach(linha => {
                        relatorioComandos.row.add([
                            linha.cmd_id,
                            linha.cmd_eqp,
                            linha.descricao_comando,
                            linha.cmd_cadastro ? formatDateTime(linha.cmd_cadastro) : '',
                            linha.cmd_envio ? formatDateTime(linha.cmd_envio) : '',
                            linha.cmd_confirmacao ? formatDateTime(linha.cmd_confirmacao) : '',
                            linha.status,
                            linha.usuario,
                        ]).draw();
                    });
                }

                tableContainer.removeClass('hide');
                button.attr('disabled', false).html('Gerar Relatório');
            },
            error: function(){
                button.attr('disabled', false).html('Gerar Relatório');
                alert("Erro ao gerar o relatório, tente novamente.");
            }
        });
    }

    function validarRelatorioInputs(data) {
        dateIni = new Date(data.dataInicioRelatorio + " " + data.horaInicioRelatorio);
        dateFim = new Date(data.dataFimRelatorio + " " + data.horaFimRelatorio);
        
        if(dateIni == 'Invalid Date'){
            alert('Informe data e hora de início válidas.')
            return false;
        }
        else if(dateFim == 'Invalid Date'){
            alert('Informe data e hora de fim válidas.')
            return false;
        }
        else if(validarDatas(data.dataInicioRelatorio, data.dataFimRelatorio) == false){
            alert('Informe datas válidas.');
            return false;
        }
        else if(validarDiferençaDatas(dateIni, dateFim,30) == false){
            alert('Informe um intervalo de 30 dias.');
            return false;
        }else if(!data.iscaSelecionada) {
            alert('Selecione uma isca na tabela.')
            return false;
        }else if(!data.tipoRelatorio) {
            alert('Selecione um tipo de relátorio.')
            return false;
        }else{
            return true;
        }
    }

    /*
    * Lista iscas encontradas no banco pelos fitros definidos e mostrar na datatables 
    */
    function enviarComando(data, button) {
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando');
        
        $.ajax({
            url: '<?= site_url("iscas/comandos_isca/ajax_envio_comando") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                let results = JSON.parse(callback);
                if(results.status == true) {
                    alert(results.msg);
                    tabelaTratComando.ajax.reload(null, false);
                    limparModalTratamentos();
                } else {
                    alert('Erro ao enviar comando, tente novamente.');
                }

                button.attr('disabled', false).html('Enviar Comando');
            },
            error: function(){
                button.attr('disabled', false).html('Enviar Comando');
                alert("Erro ao enviar o comando, tente novamente.");
            }
        });
        
    }

    function validarParamEnvio(data) {
        if(!data.serial) {
            alert('Preencha o campo serial.');
            return false;
        } else if(!data.tipoComando) {
            alert('Selecione o tipo de comando. ');
            return false;
        } else if(!data.intervaloRX) {
            alert('Preencha o campo intervalo RX em modo normal.');
            return false;
        } else if(!data.periodoRXNormal) {
            alert('Preencha o campo período RX em modo normal.');
            return false;
        } else if(!data.intervaloGPRSNormal) {
            alert('Preencha o campo intervalo de envio GPRS em modo normal.');
            return false;
        } else if(!data.intervaloTX) {
            alert('Preencha o campo intervalo TX em emergência.');
            return false;
        } else if(!data.numeroTransm) {
            alert('Preencha o campo número de transmissões em emergência.');
            return false;
        } else if(!data.intervaloGPRSEmerg) {
            alert('Preencha o campo inter. de envio GPRS em modo emerg.');
            return false;
        } else if(!data.periodoRXEmerg) {
            alert('Preencha o campo período RX em emergência.');
            return false;
        } else if(!data.tempoSleep) {
            alert('Preencha o campo tempo para entrar em modo sleep.');
            return false;
        } else if(!data.tempoJammer) {
            return true;
        }

        return true;
    }

    function validarConfigConexao(data) {
        if(!data.serial) {
            alert('Preencha o campo serial.');
            return false;
        } else if(!data.tipoComando) {
            alert('Selecione o tipo de comando.');
            return false;
        } else if(!data.auth) {
            alert('Preencha o campo autenticação.');
            return false;
        } else if(!data.apn) {
            alert('Preencha o campo APN.');
            return false;
        } else if(!data.user_id) {
            alert('Preencha o campo usuário.');
            return false;
        } else if(!data.password) {
            alert('Preencha o campo de senha.');
            return false;
        } else if(!data.server_ip_1) {
            alert('Preencha o campo IP_Serv_1.');
            return false;
        } else if(!data.server_port_1) {
            alert('Preencha o campo Porta_Serv_1.');
            return false;
        } else if(!data.server_ip_2) {
            alert('Preencha o campo IP_Serv_2.');
            return false;
        } else if(!data.server_port_2) {
            alert('Preencha o campo Porta_Serv_2');
            return false;
        }

        return true;
    }

    function limparModalTratamentos() {
        let dataInicioRelatorio     = $('#dataInicioRelatorio');
        let horaInicioRelatorio     = $('#horaInicioRelatorio');
        let dataFimRelatorio        = $('#dataFimRelatorio');
        let horaFimRelatorio        = $('#horaFimRelatorio');
        let tipoRelatorio           = $('#tipoRelatorio');
        let tabelaPosicoes          = $('#relatorioPosicoes');
        let tabelaEventos           = $('#relatorioEventos');
        let tabelaComandos          = $('#relatorioComandos');
        let intervaloRX             = $('#intervaloRX');
        let periodoRXNormal         = $('#periodoRXNormal');
        let intervaloGPRSNormal     = $('#intervaloGPRSNormal');
        let intervaloTX             = $('#intervaloTX');
        let numeroTransm            = $('#numeroTransm');
        let intervaloGPRSEmerg      = $('#intervaloGPRSEmerg');
        let periodoRXEmerg          = $('#periodoRXEmerg');
        let tempoSleep              = $('#tempoSleep');
        let tempoJammer             = $('#tempoJammer');
        let auth                    = $('#authConexao');
        let apn                     = $('#apnConexao');
        let user_id                 = $('#usuarioConexao');
        let password                = $('#senhaConexao');
        let server_ip_1             = $('#ipServ1Conexao');
        let server_port_1           = $('#portaServ1Conexao');
        let server_ip_2             = $('#ipServ2Conexao');
        let server_port_2           = $('#portaServ2Conexao');
        let iscaSelecionadaComando  = $('#iscaSelecionaComando');
        let tipoComando             = $('#tipoComando');
        let configurarConexaoInputs = $('#configurarConexaoInputs');
        let parametrosDeEnvioInputs = $('#parametrosDeEnvioInputs');
        let solicitar               = $('#solicitar');
        let solicitarResults        = $('#solicitarResults');

        dataInicioRelatorio.val('');
        horaInicioRelatorio.val('00:00:00');
        dataFimRelatorio.val('');
        horaFimRelatorio.val('23:59:59');
        tipoRelatorio.val('');
        tipoComando.val('');
        iscaSelecionadaComando.val('');
        intervaloRX.val('10');
        periodoRXNormal.val('300');
        intervaloGPRSNormal.val('10');
        intervaloTX.val('2');
        numeroTransm.val('1');
        intervaloGPRSEmerg.val('5');
        periodoRXEmerg.val('300');
        tempoSleep.val('60');
        tempoJammer.val('0');
        auth.val('');
        apn.val('');
        user_id.val('');
        password.val('');
        server_ip_1.val('');
        server_port_1.val('');
        server_ip_2.val('');
        server_port_2.val('');

        tabelaPosicoes.addClass('hide');
        tabelaEventos.addClass('hide');
        tabelaComandos.addClass('hide');
        configurarConexaoInputs.addClass('hide');
        parametrosDeEnvioInputs.addClass('hide');
        solicitar.addClass('hide');
        solicitarResults.html('<span class="fa fa-spinner fa-spin"></span>');
    }

    function buscarVersaoFirmware() {
        let iscaSelecionadaComando  = $('#iscaSelecionadaComandos').val();
        let solicitar = $('#solicitar');
        let solicitarResults = $('#solicitarResults');

        data = {
            serial: iscaSelecionadaComando,
        }

        solicitar.removeClass('hide');
        $.ajax({
            url: '<?= site_url("iscas/isca/buscarDadosIsca") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                let results = JSON.parse(callback);
                if(results.status == true) {
                    solicitarResults.html((results.firmware && results.firmware != "null" && results.firmware != "null" ? 'Versão de Firmware: ' + results.firmware : 'Versão de firmware indefinida.'));
                } else {
                    solicitarResults.html(results.msg);
                }
            },
            error: function(){
                alert("Erro ao solicitar versão de firmware, tente novamente.");
            }
        });
    }

    function buscarICCID() {
        let iscaSelecionadaComando  = $('#iscaSelecionadaComandos').val();
        let solicitar = $('#solicitar');
        let solicitarResults = $('#solicitarResults');

        data = {
            serial: iscaSelecionadaComando,
        }

        solicitar.removeClass('hide');
        $.ajax({
            url: '<?= site_url("iscas/isca/buscarDadosIsca") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                let results = JSON.parse(callback);
                if(results.status == true) {
                    solicitarResults.html((results.ccid && results.ccid != 'null' && results.ccid != "" ? 'ICCID: ' + results.ccid : 'ICCID indefinido.'));
                } else {
                    solicitarResults.html(results.msg);
                }
            },
            error: function(){
                alert("Erro ao solicitar ICCID, tente novamente.");
            }
        });
    }
    function buscarConfig(){
        let iscaSelecionadaComando  = $('#iscaSelecionadaComandos').val();
        let solicitar = $('#solicitar');
        let solicitarResults = $('#solicitarResults');

        data = {
            serial: iscaSelecionadaComando,
        }
        solicitar.removeClass('hide');
        $.ajax({
            url: '<?= site_url("iscas/isca/buscarDadosIsca") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                let results = JSON.parse(callback);
                if(results.status == true) {
                    solicitarResults.html(`
                        <p><b>APN:</b> ${results.apn && results.apn != "null" && results.apn != "" ? results.apn : "Indefinido"}</p>
                        <p><b>Usuario:</b> ${results.usuario && results.usuario != "null" && results.usuario != "" ? results.usuario : "Indefinido"}</p>
                        <p><b>Senha:</b> ${results.senha && results.senha != "null" && results.senha != "" ? results.senha : "Indefinido"}</p>
                        <p><b>IP1:</b> ${results.ip1 && results.ip1 != "null" && results.ip1 != "" ? results.ip1 : "Indefinido"}</p>
                        <p><b>Porta1:</b> ${results.porta1 && results.porta1 != "null" && results.porta1 != "" ? results.porta1 : "Indefinido"}</p>
                        <p><b>IP2:</b> ${results.ip2 && results.ip2 != "null" && results.ip2 != "" ? results.ip2 : "Indefinido"}</p>
                        <p><b>Porta2:</b> ${results.porta2 && results.porta2 != "null" && results.porta2 != "" ? results.porta2 : "Indefindido"}</p>
                    `);
                } else {
                    solicitarResults.html(results.msg);
                }
            },
            error: function(){
                alert("Erro ao solicitar ICCID, tente novamente.");
            }
        });
    }

    $('#enviarComando').click(function() {
        let button                  = $('#enviarComando');
        let iscaSelecionadaComando  = $('#iscaSelecionadaComandos').val();
        let tipoComando             = $('#tipoComando').val();
        let data;

        switch(tipoComando) {
            case 'CONFIG_CONEXAO':
                data = {
                    serial                  : iscaSelecionadaComando,
                    tipoComando             : tipoComando,
                    auth                    : $('#authConexao').val(),
                    apn                     : $('#apnConexao').val(),
                    user_id                 : $('#usuarioConexao').val(),
                    password                : $('#senhaConexao').val(),
                    server_ip_1             : $('#ipServ1Conexao').val(),
                    server_port_1           : $('#portaServ1Conexao').val(),
                    server_ip_2             : $('#ipServ2Conexao').val(),
                    server_port_2           : $('#portaServ2Conexao').val(),
                }

                if(validarConfigConexao(data)) {
                    enviarComando(data, button);
                }
                break;
            case 'PARAM_ENVIO':
                data = {
                    serial                  : iscaSelecionadaComando,
                    tipoComando             : tipoComando,
                    intervaloRX             : $('#intervaloRX').val(),
                    periodoRXNormal         : $('#periodoRXNormal').val(),
                    intervaloGPRSNormal     : $('#intervaloGPRSNormal').val(),
                    intervaloTX             : $('#intervaloTX').val(),
                    numeroTransm            : $('#numeroTransm').val(),
                    intervaloGPRSEmerg      : $('#intervaloGPRSEmerg').val(),
                    periodoRXEmerg          : $('#periodoRXEmerg').val(),
                    tempoSleep              : $('#tempoSleep').val(),
                    tempoJammer             : $('#tempoJammer').val(),
                }

                if(validarParamEnvio(data)) {
                    enviarComando(data, button);
                }
                break;
            case null:
                alert("Selecione um tipo de comando.");
                break;
            default:
                data = {
                    serial                  : iscaSelecionadaComando,
                    tipoComando             : tipoComando,
                }
                enviarComando(data, button);
        }
    })

    $("#gerarRelatorio").click(function(){

        let data = {
            iscaSelecionada     : $('#iscaSelecionada').val(),
            dataInicioRelatorio : $('#dataInicioRelatorio').val(),
            horaInicioRelatorio : $('#horaInicioRelatorio').val(),
            dataFimRelatorio    : $('#dataFimRelatorio').val(),
            horaFimRelatorio    : $('#horaFimRelatorio').val(),
            tipoRelatorio       : $('#tipoRelatorio').val()
        }

        if(data.tipoRelatorio === 'eventos') {
            console.log('eventos');
        } else if(data.tipoRelatorio === 'sinistro') {
            console.log('sinistro');
        }

        if(validarRelatorioInputs(data)){
            gerarRelatorio(data)
        }else{
            return false;
        }
    });
    
    $('#btnModalTratamentos').on('click', () => {
        let selectedRow             = $('#tabelaIscas tr.selected');
        let iscaSelecionada         = $('#iscaSelecionada');
        let iscaSelecionadaComandos = $('#iscaSelecionadaComandos');
        let tipoComando = $('#tipoComando');

        if(selectedRow.length > 0 && selectedRow[0].childNodes[1].innerHTML) {
            iscaSelecionada.val(selectedRow[0].childNodes[1].innerHTML);
            iscaSelecionadaComandos.val(selectedRow[0].childNodes[1].innerHTML);

            let fabricante = selectedRow[0].childNodes[3].innerHTML;
            AtualizarTipoComando(tipoComando, fabricante);

            limparModalTratamentos();
            
            // $('#tableComandosTrat').DataTable().clear().draw();
            $('#modalTratamentos').modal('show');
            $('.relatorioTratamentos a[href="#relatorioTratamentos"]').tab('show');

        } else {
            alert('Selecione uma isca da tabela.');
        }
    });

    $('#btnModalTratamentosComandos').on('click', () => {
        let selectedRow             = $('#tabelaComandos tr.selected');
        let iscaSelecionada         = $('#iscaSelecionada');
        let iscaSelecionadaComandos = $('#iscaSelecionadaComandos');
        
        if(selectedRow.length > 0 && selectedRow[0].childNodes[0].innerHTML) {
            iscaSelecionada.val(selectedRow[0].childNodes[0].innerHTML);
            iscaSelecionadaComandos.val(selectedRow[0].childNodes[0].innerHTML);
            limparModalTratamentos();
            
            // $('#tableComandosTrat').DataTable().clear().draw();
            $('#modalTratamentos').modal('show');
            $('.relatorioTratamentos a[href="#relatorioTratamentos"]').tab('show');

        } else {
            alert('Selecione uma isca da tabela.');
        }
    });

    $('#btnModalTratamentosDispositivos').on('click', () => {
        let selectedRow             = $('#tabelaDispositivos tr.selected');
        let iscaSelecionada         = $('#iscaSelecionada');
        let iscaSelecionadaComandos = $('#iscaSelecionadaComandos');
        
        if(selectedRow.length > 0 && selectedRow[0].childNodes[1].innerHTML) {
            iscaSelecionada.val(selectedRow[0].childNodes[1].innerHTML);
            iscaSelecionadaComandos.val(selectedRow[0].childNodes[1].innerHTML);
            limparModalTratamentos();
            
            // $('#tableComandosTrat').DataTable().clear().draw();
            $('#modalTratamentos').modal('show');
            $('.relatorioTratamentos a[href="#relatorioTratamentos"]').tab('show');

        } else {
            alert('Selecione uma isca da tabela.');
        }
    });

    $('#tipoRelatorio').on('change', (event) => {
        let tipoRelatorio    = $(event.target).val();
        let tipoEventoSelect = $('#tipoEventoSelect');
        let tabelaPosicoes   = $('#relatorioPosicoes');
        let tabelaEventos    = $('#relatorioEventos');
        let tabelaComandos   = $('#relatorioComandos');

        tabelaPosicoes.addClass('hide');
        tabelaEventos.addClass('hide');
        tabelaComandos.addClass('hide');
    });

    $('#tipoComando').on('change', (event) => {
        let tipoComando             = $(event.target).val();
        let configurarConexaoInputs = $('#configurarConexaoInputs');
        let parametrosDeEnvioInputs = $('#parametrosDeEnvioInputs');
        let solicitar = $('#solicitar');
        let solicitarResults = $('#solicitarResults');

        configurarConexaoInputs.addClass('hide');
        parametrosDeEnvioInputs.addClass('hide');
        solicitar.addClass('hide');
        solicitarResults.html('<span class="fa fa-spinner fa-spin"></span>');

        $("#enviarComando").attr('disabled',false);

        switch (tipoComando) {
            case 'CONFIG_CONEXAO':
                configurarConexaoInputs.removeClass('hide');
                break;
            case 'PARAM_ENVIO':
                parametrosDeEnvioInputs.removeClass('hide');
                fabricante = $('#fabricante').val();
                if(fabricante == 'Queclink'){
                    $('.parametroGeral').addClass('hide')
                }else{
                    $('.parametroGeral').removeClass('hide')
                }
                $("#intervaloGPRSNormal").val(ultimoIntervaloGPRSNormal);
                $("#intervaloGPRSEmerg").val(ultimoIntervaloGPRSEmerg);
                $("#tempoSleep").val(ultimoTempoSleep);
                break;
            case 'SOLICITAR_ICCID': 
                // $("#enviarComando").attr('disabled',true);
                buscarICCID();
                break;
            case 'SOLICITAR_VERCAO_FIRMWARE':
                // $("#enviarComando").attr('disabled',true);
                buscarVersaoFirmware();
                break;
            case 'SOLICITAR_CONFIG':
                // $("#enviarComando").attr('disabled',true);
                buscarConfig();
                break;
            default:
                break;
        }

    });
});

function AtualizarTipoComando(select, fabricante){
    select.empty();
    select.append('<option disabled selected value="">Selecionar Comando</option>');
    $('#fabricante').val(fabricante)
    
    if(fabricante == 'Queclink'){
        select.append('<option value="PARAM_ENVIO">Parâmetros de Envio - Rede Colaborativa</option>');
        select.append('<option value="START_REDE_COLAB">Iniciar Emergência</option>');
        select.append('<option value="STOP_REDE_COLAB">Parar Emergência</option>');
    }else{
        select.append('<option value="CONFIG_CONEXAO">Configurar Conexão (Veicular)</option>');
        select.append('<option value="PARAM_ENVIO">Parâmetros de Envio - Rede Colaborativa</option>');
        select.append('<option value="START_REDE_COLAB">Iniciar Emergência</option>');
        select.append('<option value="STOP_REDE_COLAB">Parar Emergência</option>');
        select.append('<option value="SOLICITAR_ICCID">Solicitar ICCID</option>');
        select.append('<option value="SOLICITAR_CONFIG">Solicitar Configuração</option>');
        select.append('<option value="SOLICITAR_POSICAO">Solicitar Posição</option>');
        select.append('<option value="SOLICITAR_VERCAO_FIRMWARE">Solicitar Versão Firmware</option>');
    }
}

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
<script type="text/javascript" src="<?= base_url("media/js/helpers/monitoramento-iscas-mapa.js") ?>"></script>