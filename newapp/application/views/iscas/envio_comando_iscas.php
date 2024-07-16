<style>
    hr {
        border-color: #ddd;
    }

    h4 {
        font-weight: bold;
    }

    .select2-container .select2-selection--single {
        height: 35px !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente('Envio de Comandos', site_url('Homes'), 'Comandos Isca', 'Envio de Comandos');
?>



<div class="card-conteudo card-cadastro-clientes" style='margin-left: 30px; margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>

    <form>
        <h3 class="col-sm-12">
            <b>Envio de Comandos: </b>
        </h3>

        <div class="col-sm-12" style="margin-bottom: 30px;">

            <div class="row">
                <div class="opcoes_isca col-sm-6">
                    <label for="tipoComando">Comando</label>
                    <br>
                    <select class="form-control" style="width: 100%;" name="tipoComando" id="tipoComando" required>
                        <option value="" selected disabled>Selecionar Comando</option>
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

                <div class="filtro_isca col-sm-6">
                    <label for="serial">Serial</label>
                    <br>
                    <select style="width: 100%;" name="serial form-control" id="serial"></select>
                </div>
            </div>
        </div>
        
        <div class="col-sm-12">
            <!-- Comando Configurar Conexão -->
            <div id="CONFIG_CONEXAO" class="well" style="display: none;">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="auth">Autenticação</label>
                            <select class="form-control" id="auth" name="auth" required>
                                <option value="" selected disabled>Tipo de Autenticação</option>
                                <option value="0">Não (PAP)</option>
                                <option value="1">Sim (CHAP)</option>
                                <option value="A">Automático (GPRS)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="apn">APN</label>
                            <input type="text" class="form-control" id="apn" name="apn" placeholder="APN">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="apn" class="col-form-label">Usuário</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Usuário">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="password">Senha</label>
                            <input type="text" class="form-control" id="password" name="password" placeholder="Senha">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="apn">IP_Serv_1: </label>
                            <input type="text" class="form-control" id="server_ip_1" name="server_ip_1" placeholder="IP_Serv_1">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="apn">Porta_Serv_1: </label>
                            <input type="text" class="form-control" id="server_port_1" name="server_port_1" placeholder="Porta_Serv_1">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="server_ip_2">IP_Serv_2: </label>
                            <input type="text" class="form-control" id="server_ip_2" name="server_ip_2" placeholder="IP_Serv_2">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="server_port_2">Porta_Serv_2: </label>
                            <input type="text" class="form-control" id="server_port_2" name="server_port_2" placeholder="Porta_Serv_2">
                        </div>
                    </div>
                </div>

            </div>

            <!-- parametro de envio, rede colaborativa -->
            <div id="PARAM_ENVIO" class="well" style="display: none;">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="intervaloRX">Intervalo RX em modo normal(seg):</label>
                            <input type="text" class="form-control" name="intervaloRX" id="intervaloRX" value="10" disabled>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="periodoRXNormal">Período RX em modo normal(ms):</label>
                            <input type="text" class="form-control" name="periodoRXNormal" id="periodoRXNormal" value="300" disabled>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="intervaloGPRSNormal">Intervalo de envio GPRS em modo normal(min):</label>
                            <input type="number" class="form-control" name="intervaloGPRSNormal" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSNormal" value="10">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="numeroTransm">Numero de transmissões em emergência:</label>
                            <input type="text" class="form-control" name="numeroTransm" id="numeroTransm" value="1" disabled>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="intervaloTX">Intervalo TX em emergencia(seg):</label>
                            <input type="text" class="form-control" name="intervaloTX" id="intervaloTX" value="2" disabled>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="intervaloGPRSEmerg">Inter. de envio GPRS em modo emerg.(min):</label>
                            <input type="number" class="form-control" name="intervaloGPRSEmerg" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSEmerg" value="5">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="periodoRXEmerg">Periodo RX em emergência(ms):</label>
                            <input type="text" class="form-control" name="periodoRXEmerg" id="periodoRXEmerg" value="300" disabled>
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="tempoSleep">Tempo para entrar em modo sleep(seg):</label>
                            <input type="number" class="form-control" name="tempoSleep" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="tempoSleep" value="60">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="label-equal-height" for="tempoJammer">Tempo para Confirmar Presença do Jammer(min):</label>
                            <input type="text" class="form-control" name="tempoJammer" id="tempoJammer" value="0" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>



    <div class="col-sm-12">
        <p id="resposta_comando" style="margin: 20px; "></p>
    </div>

    <div class="acoes_botoes col-sm-12">
        <button id="limparComando" class="btn btn-danger"> <i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

        <button id="enviarComando" class="btn btn-primary"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar</button>
    </div>
</div>

<div style="margin-left: 30px; display: none;" id="tabelas">
    <div class="card-conteudo" style='margin-bottom: 20px;'>
        <h3>
            <?= lang('resposta_comando') ?>
            <div class="btn-div-responsive" id="btn-div">
                <div class="dropdown" style="margin-right: 10px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                    </div>
                </div>
            </div>
        </h3>
        <div class="registrosDiv" style="display: none;">
            <select id="select-quantidade-por-pagina-comandos" class="form-control" style="float: left; width: auto; height: 34px;">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
        </div>
        <div style="position: relative;">
            <div id="loadingMessageComandos" class="loadingMessage" style="display: none;">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div class="wrapperComandos">
                <div id="tableComandos" class="ag-theme-alpine my-grid-comandos" style="height: 500px">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
</script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/iscas/comandos', 'RespostaComando.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $("#tipoComando").select2({
            language: 'pt-BR',
        });

        $('#tabelas').hide();

        $("#serial").select2({
            minimumInputLength: 3,
            language: 'pt-BR',
            ajax: {
                url: '<?php echo site_url('iscas/isca/ajaxGetIsca') ?>',
                dataType: "json",
                type: "POST",
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        $("#limparComando").click(() => {
            limparParametros();
            $('#tabelas').hide();
        });
        $("#tipoComando").change(function() {
            $('#tabelas').hide();
            if ($(this).val() == 'CONFIG_CONEXAO') {
                $('#CONFIG_CONEXAO').css('display', 'block');
                $('#PARAM_ENVIO').css('display', 'none');
                resizeFields();
                // limparParametros();
            } else if ($(this).val() == 'PARAM_ENVIO') {
                $('#CONFIG_CONEXAO').css('display', 'none');
                $('#PARAM_ENVIO').css('display', 'block');
                resizeFields();
                // limparParametros();
            } else {
                $('#CONFIG_CONEXAO').css('display', 'none');
                $('#PARAM_ENVIO').css('display', 'none');
                resizeFields();
                // limparParametros();
            }
        })

        $(window).resize(function(){
            resizeFields();
        });

        // Envia o comando
        $("#enviarComando").click(function(event) {


            event.preventDefault();
            let serial = $("#serial").val();
            let tipoComando = $("#tipoComando").val();
            $('#tabelas').hide();

            if (tipoComando == null) {
                alert("Selecione o comando.");
                return false;
            } else if (serial == "" || serial == null) {
                alert("Informe o serial.");
                return false;
            } else {
                // Envia parametros comando CONFIG_CONEXAO
                if (tipoComando == 'CONFIG_CONEXAO') {
                    // Dados
                    let data = {
                        auth: $("#auth").val(),
                        apn: $("#apn").val(),
                        user_id: $("#user_id").val(),
                        password: $("#password").val(),
                        server_ip_1: $("#server_ip_1").val(),
                        server_port_1: $("#server_port_1").val(),
                        server_ip_2: $("#server_ip_2").val(),
                        server_port_2: $("#server_port_2").val(),
                        serial: serial,
                        tipoComando: $("#tipoComando").val()
                    }
                    if (validarParametrosConfigConexao(data)) {

                        enviarComando(data);

                    }
                } else if (tipoComando == 'PARAM_ENVIO') {
                    let data = {
                        intervaloRX: $('#intervaloRX').val(),
                        periodoRXNormal: $('#periodoRXNormal').val(),
                        intervaloGPRSNormal: $('#intervaloGPRSNormal').val(),
                        intervaloTX: $('#intervaloTX').val(),
                        numeroTransm: $('#numeroTransm').val(),
                        intervaloGPRSEmerg: $('#intervaloGPRSEmerg').val(),
                        periodoRXEmerg: $('#periodoRXEmerg').val(),
                        tempoSleep: $('#tempoSleep').val(),
                        tempoJammer: $('#tempoJammer').val(),
                        serial: serial,
                        tipoComando: $("#tipoComando").val()
                    }

                    if (validaParametrosRedeColabTemp(data)) {

                        enviarComando(data);

                    }

                } else {
                    let data = {
                        serial: serial,
                        tipoComando: tipoComando
                    }

                    enviarComando(data);
                }

            }




        });

        function enviarComando(data) {

            if (data.tipoComando == 'SOLICITAR_ICCID') {
                buscarICCID(data);
            } else if (data.tipoComando == 'SOLICITAR_CONFIG') {
                buscarConfig(data);
            } else if (data.tipoComando == 'SOLICITAR_VERCAO_FIRMWARE') {
                buscarVersaoFirmware(data);
            }

            let button = $("#enviarComando");
            button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
            $.ajax({
                url: '<?php echo site_url('iscas/comandos_isca/ajax_envio_comando') ?>',
                type: 'POST',
                data: data,
                success: function(callback) {
                    resposta = JSON.parse(callback);
                    alert(resposta.msg);
                    limparParametros();
                    button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');

                    // Esconde as divs de configurações
                    $('#CONFIG_CONEXAO').css('display', 'none');
                    $('#PARAM_ENVIO').css('display', 'none');

                },
                error: function() {
                    alert('Erro ao enviar o comando.');
                    limparParametros();
                    button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');

                    // Esconde as divs de configurações
                    $('#CONFIG_CONEXAO').css('display', 'none');
                    $('#PARAM_ENVIO').css('display', 'none');
                }
            });


            return false;
        }

        function buscarICCID(data) {
            let button = $("#enviarComando");
            button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
            $.ajax({
                url: '<?= site_url("iscas/isca/buscarDadosIsca") ?>',
                type: 'POST',
                data: data,
                success: function(data) {
                    button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');
                    let results = JSON.parse(data);
                    if (results.status == true) {
                        let dados = [results];
                        for (let i = 0; i < dados.length; i++) {
                            for (let chave in dados[i]) {
                                // Verifica se o valor é null e substitui por uma string vazia
                                if (dados[i][chave] === null || dados[i][chave] === '' || dados[i][chave] === 'null') {
                                    dados[i][chave] = '';
                                }
                            }
                        }

                        preencherRespostaComandos(dados, 'SOLICITAR_CCID');
                        document.querySelector('#tableComandos').style.setProperty('height', '160px');
                        //$("#resposta_comando").html((results.ccid ? 'ICCID: ' + results.ccid : 'ICCID indefinido.'));
                    } else if (results.msg == 'Dados da isca não encontrados.') {
                        alert(results.msg);
                    } else {
                        alert("Erro ao solicitar ICCID, tente novamente.");
                    }
                },
                error: function() {
                    alert("Erro ao solicitar ICCID, tente novamente.");
                }
            });
        }

        function buscarConfig(data) {
            let button = $("#enviarComando");
            button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
            $.ajax({
                url: '<?= site_url("iscas/isca/buscarDadosIsca") ?>',
                type: 'POST',
                data: data,
                success: function(data) {
                    button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');
                    let results = JSON.parse(data);
                    if (results.status == true) {
                        let dados = [results];
                        for (let i = 0; i < dados.length; i++) {
                            for (let chave in dados[i]) {
                                // Verifica se o valor é null e substitui por uma string vazia
                                if (dados[i][chave] === null || dados[i][chave] === '' || dados[i][chave] === 'null') {
                                    dados[i][chave] = '';
                                }
                            }
                        }

                        preencherRespostaComandos(dados, 'SOLICITAR_CONFIG');
                        document.querySelector('#tableComandos').style.setProperty('height', '160px');
                    } else if (results.msg == 'Dados da isca não encontrados.') {
                        alert(results.msg);
                    } else {
                        alert("Erro ao solicitar ICCID, tente novamente.");
                    }
                },
                error: function() {
                    alert("Erro ao solicitar ICCID, tente novamente.");
                }
            });
        }

        function buscarVersaoFirmware(data) {
            let button = $("#enviarComando");
            button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
            $.ajax({
                url: '<?= site_url("iscas/isca/buscarDadosIsca") ?>',
                type: 'POST',
                data: data,
                success: function(data) {
                    button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');
                    let results = JSON.parse(data);
                    if (results.status == true) {
                        let dados = [results];
                        for (let i = 0; i < dados.length; i++) {
                            for (let chave in dados[i]) {
                                // Verifica se o valor é null e substitui por uma string vazia
                                if (dados[i][chave] === null || dados[i][chave] === '' || dados[i][chave] === 'null') {
                                    dados[i][chave] = '';
                                }
                            }
                        }

                        preencherRespostaComandos(dados, 'SOLICITAR_VERCAO_FIRMWARE');
                        document.querySelector('#tableComandos').style.setProperty('height', '160px');
                        //$("#resposta_comando").html((results.firmware ? 'Versão de Firmware: ' + results.firmware : 'Versão de firmware indefinida.'));
                    } else if (results.msg == 'Dados da isca não encontrados.') {
                        alert(results.msg);
                    } else {
                        alert("Erro ao solicitar ICCID, tente novamente.");
                    }
                },
                error: function() {
                    alert("Erro ao solicitar versão de firmware, tente novamente.");
                }
            });
        }

        function validarParametrosConfigConexao(data) {
            if (data.tipoComando == "" || data.tipoComando == null) {
                alert("Selecione o comando.");
                return false;
            } else if (data.serial == "") {
                alert("Informe o serial.");
                return false;
            } else if (data.auth == null) {
                alert("Selecione o tipo de autenticação.");
                return false;
            } else if (data.apn == "") {
                alert("Informe o APN.");
                return false;
            } else if (data.user_id == "") {
                alert("Informe o usuário.");
                return false;
            } else if (data.password == "") {
                alert("Informe a senha.");
                return false;
            } else if (data.server_ip_1 == "") {
                alert("Informe o IP_Serv_1.");
                return false;
            } else if (data.server_port_1 == "") {
                alert("Informe a Porta_Serv_1.");
                return false;
            } else if (data.server_ip_2 == "") {
                alert("Informe o IP_Serv_2.");
                return false;
            } else if (data.server_port_2 == "") {
                alert("Informe a Porta_Serv_2.");
                return false;
            } else {
                return true;
            }
        }

        function validaParametrosRedeColabTemp(data) {
            if (!data.serial) {
                alert('Preencha o campo serial.');
                return false;
            } else if (!data.tipoComando) {
                alert('Selecione o tipo de comando. ');
                return false;
            } else if (!data.intervaloRX) {
                alert('Preencha o campo intervalo RX em modo normal.');
                return false;
            } else if (!data.periodoRXNormal) {
                alert('Preencha o campo período RX em modo normal.');
                return false;
            } else if (!data.intervaloGPRSNormal) {
                alert('Preencha o campo intervalo de envio GPRS em modo normal.');
                return false;
            } else if (!data.intervaloTX) {
                alert('Preencha o campo intervalo TX em emergência.');
                return false;
            } else if (!data.numeroTransm) {
                alert('Preencha o campo número de transmissões em emergência.');
                return false;
            } else if (!data.intervaloGPRSEmerg) {
                alert('Preencha o campo inter. de envio GPRS em modo emerg.');
                return false;
            } else if (!data.periodoRXEmerg) {
                alert('Preencha o campo período RX em emergência.');
                return false;
            } else if (!data.tempoSleep) {
                alert('Preencha o campo tempo para entrar em modo sleep.');
                return false;
            } else if (!data.tempoJammer) {
                return true;
            }

            return true;

        }

        function limparParametros() {
            $('#intervaloRX').val('10');
            $('#periodoRXNormal').val('300');
            $('#intervaloGPRSNormal').val('60');
            $('#intervaloTX').val('2');
            $('#numeroTransm').val('1');
            $('#intervaloGPRSEmerg').val('10');
            $('#periodoRXEmerg').val('300');
            $('#tempoSleep').val('5');
            $('#tempoJammer').val('0');
            $("#auth").val("");
            $("#apn").val("");
            $("#user_id").val("");
            $("#password").val("");
            $("#server_ip_1").val("");
            $("#server_port_1").val("");
            $("#server_ip_2").val("");
            $("#server_port_2").val("");

            $("#ligaDesliga").val(""),
                $("#sensorTemperatura").val(""),

                $("#serial").val("").trigger("change");

            let tipoComando = $("#tipoComando").val();
            if (tipoComando != 'SOLICITAR_ICCID' && tipoComando != 'SOLICITAR_CONFIG' && tipoComando != 'SOLICITAR_VERCAO_FIRMWARE') {
                $("#tipoComando").val("").trigger("change");
                $("#resposta_comando").html('');
            }


        }

    });

    function resizeFields() {
        var maxHeight = 0;
        $('.label-equal-height').each(function(){
            $(this).height('auto');
            var height = $(this).height();
            if(height > maxHeight){
                maxHeight = height;
            }
        });
        $('.label-equal-height').height(maxHeight);
    }
</script>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">

<style>
    .display_acoes {
        width: 100%;
    }

    .form-control {
        width: 100%;
    }

    .btn {
        text-align: center;
        height: 40px;
        max-height: 48px;
        align-self: center;
    }

    .acoes_botoes {
        display: flex;
        justify-content: space-between;
        flex-direction: row;
        flex-wrap: wrap;
    }

    #enviarComando {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.16), 0 2px 10px rgba(0, 0, 0, 0.12);
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        -ms-border-radius: 2px;
        border-radius: 2px;
        border: 1px solid transparent;
        font-size: 13px;
        outline: none;
        background-color: #007BFF !important;
    }


    #limparComando {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.16), 0 2px 10px rgba(0, 0, 0, 0.12);
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        -ms-border-radius: 2px;
        border-radius: 2px;
        border: 1px solid transparent;
        font-size: 13px;
        outline: none;
        color: #333;
        background-color: #fff !important;
        border-color: #ccc;

    }

</style>