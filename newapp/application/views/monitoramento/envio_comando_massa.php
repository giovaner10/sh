<?php
?>

<style>
    table {
        width: 100%;
        table-layout:fixed;
    }
    td {
        word-wrap:break-word;
    }
    .dt-buttons{
        margin-bottom: 20px;
    }
    .input checkbox{
        font-size: 16;
    }
    .mt-1 {
        margin-top: 1rem !important;
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<div class="row justify-content-center">
    <div class="col-sm-12">
        <h2 ><?= $titulo?></h2>
    </div>
</div>
<div class="row justify-content-center" style="margin: 20px 0 20px 0">
    <div class="row">
        <div class="col-sm-12 form-group">
            <label for="tipoComando">Cliente <span id="load"></span></label>
            <select class="form-control" name="cliente" id="cliente"  required>
                <option value="" selected disabled>Selecionar Cliente</option>
            </select>
        </div>
        <div class="col-sm-12 form-group" style="display: none" id="checkboxesDispositivos">
            <label for="tipoComando">Dispositivos <span id="load"></span></label>
            <select class="form-control" name="seriais" id="dispositivosId" data-placeholder="Selecione pelo menos um dispositivo" multiple="" required>

            </select>
            <!-- <div id="dispositivosId" class="col-sm-12" name="seriais[]">

            </div> -->
            <div class="mt-1">
                <label for="selectAll">
                    <input type="checkbox" name="selectAll" id="selectAll">
                    Selecionar todos
                </label>
            </div>
        </div>
        <div class="col-sm-12 form-group" style="margin-bottom: 0;">
            <label for="tipoComando">Comando</label>
            <select class="form-control" name="tipoComando" id="tipoComando" required>
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
    </div>
</div>
<div class="row justify-content-center" style="margin: 20px 0 20px 0">
    <form>
        <!-- Comando Configurar Conexão -->
        <div id="CONFIG_CONEXAO" class="well" style="display: none;">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="auth">Autenticação</label>
                        <select class="form-control" id="auth" name="auth" required>
                            <option value="" selected disabled>Tipo de Autenticação</option>
                            <option value="0">Não (PAP)</option>
                            <option value="1">Sim (CHAP)</option>
                            <option value="A">Automático (GPRS)</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="apn">APN</label>
                        <input type="text" class="form-control" id="apn" name="apn" placeholder="APN">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="apn" class="col-form-label">Usuário</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Usuário">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="text" class="form-control" id="password" name="password" placeholder="Senha">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="apn">IP_Serv_1: </label>
                        <input type="text" class="form-control" id="server_ip_1" name="server_ip_1" placeholder="IP_Serv_1">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="apn">Porta_Serv_1: </label>
                        <input type="text" class="form-control" id="server_port_1" name="server_port_1" placeholder="Porta_Serv_1">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="server_ip_2">IP_Serv_2: </label>
                        <input type="text" class="form-control" id="server_ip_2" name="server_ip_2" placeholder="IP_Serv_2">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="server_port_2">Porta_Serv_2: </label>
                        <input type="text" class="form-control" id="server_port_2" name="server_port_2" placeholder="Porta_Serv_2">
                    </div>
                </div>
            </div>
        </div>

        <div id="PARAM_ENVIO" class="well" style="display: none;">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="intervaloRX">Intervalo RX em modo normal(seg):</label>
                        <input type="text" class="form-control" name="intervaloRX" id="intervaloRX" value="10" disabled>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="periodoRXNormal">Período RX em modo normal(ms):</label>
                        <input type="text" class="form-control" name="periodoRXNormal" id="periodoRXNormal" value="300" disabled>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="intervaloGPRSNormal">Intervalo de envio GPRS em modo normal(min):</label>
                        <input type="number" class="form-control" name="intervaloGPRSNormal" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSNormal" value="10">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="numeroTransm">Numero de transmissões em emergência:</label>
                        <input type="text" class="form-control" name="numeroTransm" id="numeroTransm" value="1" disabled>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="intervaloTX">Intervalo TX em emergencia(seg):</label>
                        <input type="text" class="form-control" name="intervaloTX" id="intervaloTX" value="2" disabled>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="intervaloGPRSEmerg">Inter. de envio GPRS em modo emerg.(min):</label>
                        <input type="number" class="form-control" name="intervaloGPRSEmerg" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSEmerg" value="5">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="periodoRXEmerg">Periodo RX em emergência(ms):</label>
                        <input type="text" class="form-control" name="periodoRXEmerg" id="periodoRXEmerg" value="300" disabled>
                    </div>
                    
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="tempoSleep">Tempo para entrar em modo sleep(seg):</label>
                        <input type="number" class="form-control" name="tempoSleep" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="tempoSleep" value="60">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="tempoJammer">Tempo para Confirmar Presença do Jammer(min):</label>
                        <input type="text" class="form-control" name="tempoJammer" id="tempoJammer" value="0" disabled>
                    </div>
                </div>
            </div>
        </div>

        <a href="" id="enviarComando" class="btn btn-primary">Enviar Comando</a>
    </form>
</div>

<div class="row" id="row_table_iccid" style="display: none">
    <div class="col-sm-12">
        <table class="table table-striped" id="table_iccid">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>ICCID</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="row" id="row_table_firmware" style="display: none">
    <div class="col-sm-12">
        <table class="table table-striped" id="table_firmware">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Firmware</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="row" id="row_table_config" style="display: none">
    <div class="col-sm-12">
        <table class="table table-striped" id="table_config">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>APN</th>
                    <th>Usuario</th>
                    <th>Senha</th>
                    <th>IP1</th>
                    <th>Porta1</th>
                    <th>IP2</th>
                    <th>Porta2</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<!-- CSS -->
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>


<script type="text/javascript">

    $(document).ready(function(){
        $("#dispositivosId").chosen({
            disable_search_threshold: 10,
            no_results_text: "Nenhum item encontrado.",
            width: "100%"
        });

        var mask = function (val) {
            val = val.split(":");
            return (parseInt(val[0]) > 19)? "HZ:M0:M0" : "H0:M0:M0";
        }

        pattern = {
            onKeyPress: function(val, e, field, options) {
                field.mask(mask.apply({}, arguments), options);
            },
            translation: {
                'H': { pattern: /[0-2]/, optional: false },
                'Z': { pattern: /[0-3]/, optional: false },
                'M': { pattern: /[0-5]/, optional: false }
            },
        };
        $('.time').mask(mask, pattern);

       /* $('#serial').chosen();*/
    });

    $(document).ready(function(){
        loadClientes();
        $("#cliente").change(function(){
            let valor = $(this).val();
            load_iscas_by_cliente_id(valor);
        })

        function loadClientes(){
        $('#load').html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: '<?= site_url("monitoramento_iscas/lista_clientes_iscascontrato") ?>',
                type: 'POST',
                success: function(callback){

                    let arrayIN = JSON.parse(callback);
                    $.each(arrayIN.data, function (i, item) {

                        $('#cliente').append($('<option>', {
                            value: item.id,
                            text : item.nome
                        }));
                    });
                    $('#load').html('');
                    /*button.attr('disabled', false).html('Gerar Relatório');
                    $('#displayrelatorioComandos').css('display','block');*/

                },
                error: function(){
                  /*  button.attr('disabled', false).html('Gerar Relatório');
                    alert("Erro ao gerar o relatório. Tente novamente.");*/
                }
            });
        }
    });

    $('#selectAll').on('change', function() {
        if($(this).prop('checked')) {
            $('#dispositivosId').attr('disabled', true).trigger('chosen:updated');
        } else {
            $('#dispositivosId').attr('disabled', false).trigger('chosen:updated');
        }
    })


    function  load_iscas_by_cliente_id(id)
    {
        if(id != null && id != '' && id != undefined)
        {
            $('#load').html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: '<?= site_url("monitoramento_iscas/lista_iscas_byCliente") ?>',
                type: 'POST',
                data:{
                    id : id
                },
                success: function(callback) {
                    let arrayIN = JSON.parse(callback);

                    if(arrayIN['status'] === false) {
                        $('#dispositivosId').html('');
                        $('#checkboxesDispositivos').hide();
                        alert(arrayIN['msg']);
                    } else {
                        $('#dispositivosId').html('');
                        $('#checkboxesDispositivos').show();
                        $.each(arrayIN.data, function (i, item) {
                            $('#dispositivosId').append('<option value="'+item.serial+'">'+item.serial+'</option>').trigger("chosen:updated");
                        });
                    }

                    $('#load').html('');
                },
                error: function(){
                    alert("Erro na comunicação com o servidor, tente novamente.");
                }
            });
        }
        else
        {
            alert('Por favor informe um cliente!');
        }
    }



    $("#tipoComando").change(function(){

        if($(this).val() == 'CONFIG_CONEXAO'){
            $('#CONFIG_CONEXAO').css('display','block');
            $('#PARAM_ENVIO').css('display','none');
            // limparParametros();
        }
        else if($(this).val() == 'PARAM_ENVIO'){
            $('#CONFIG_CONEXAO').css('display','none');
            $('#PARAM_ENVIO').css('display','block');
            // limparParametros();
        }
        else{
            $('#CONFIG_CONEXAO').css('display','none');
            $('#PARAM_ENVIO').css('display','none');
            // limparParametros();
        }
    })

    // Envia o comando
    $("#enviarComando").click(function(event){
        let selectAll = $('#selectAll').prop('checked');
        

        $("#row_table_iccid").css("display",'none');
        $("#row_table_firmware").css("display",'none');
        $("#row_table_config").css("display",'none');
        event.preventDefault();
        
        var searchIDs = [];
        if(!selectAll) {
            let selectedValues = $('#dispositivosId').val();
            selectedValues.forEach(function(value) {
                searchIDs.push(value);
            });

        }
        
        let serial = searchIDs;
        let tipoComando = $("#tipoComando").val();
        let cliente = $("#cliente").val();

        if(cliente === null) {
            alert("Selecione o cliente.");
            return false;
        }

        if(!selectAll && serial == "" || serial == null) {
            alert("Escolha os dispositivos do cliente.");
            return false;
        }
        
        if(tipoComando == null) {
            alert("Selecione o comando.");
            return false;
        } 
        else if(tipoComando == 'SOLICITAR_ICCID'){
            let data = {
                serial: selectAll ? '' : serial,
                selectAll: selectAll,
                cliente: $('#cliente').val()
            }
            buscarICCID(data);
        }
        else if(tipoComando == 'SOLICITAR_CONFIG'){
            let data = {
                serial: selectAll ? '' : serial,
                selectAll: selectAll,
                cliente: $('#cliente').val()
            }
            buscarConfig(data);
        }
        else if(tipoComando == 'SOLICITAR_VERCAO_FIRMWARE'){
            let data = {
                serial: selectAll ? '' : serial,
                selectAll: selectAll,
                cliente: $('#cliente').val()
            }
            buscarVersaoFirmware(data);
        }
        // Envia parametros comando CONFIG_CONEXAO
        if( tipoComando == 'CONFIG_CONEXAO'){
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
                serial: selectAll ? '' : serial,
                tipoComando: $("#tipoComando").val(),
                selectAll: selectAll,
                cliente: $('#cliente').val()
            }

            if(validarParametrosConfigConexao(data)){
                enviarComando(data);
            }

        } else if (tipoComando == 'PARAM_ENVIO') {
            let data = {
                intervaloRX             : $('#intervaloRX').val(),
                periodoRXNormal         : $('#periodoRXNormal').val(),
                intervaloGPRSNormal     : $('#intervaloGPRSNormal').val(),
                intervaloTX             : $('#intervaloTX').val(),
                numeroTransm            : $('#numeroTransm').val(),
                intervaloGPRSEmerg      : $('#intervaloGPRSEmerg').val(),
                periodoRXEmerg          : $('#periodoRXEmerg').val(),
                tempoSleep              : $('#tempoSleep').val(),
                tempoJammer             : $('#tempoJammer').val(),
                serial: selectAll ? '' : serial,
                tipoComando: $("#tipoComando").val(),
                selectAll: selectAll,
                cliente: $('#cliente').val()
            }

            if(validaParametrosRedeColabTemp(data)){
                enviarComando(data);
            }

        } else {
            let data = {
                serial: selectAll ? '' : serial,
                tipoComando: tipoComando,
                selectAll: selectAll,
                cliente: $('#cliente').val()
            }

            enviarComando(data);
        }
    });

    let table_iccid = $("#table_iccid").DataTable({
        responsive: true,
        paging: true,
        sort: true,
        order: [[ 1, 'asc' ]],
        searching: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar em todas as colunas",
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
    let table_firmware = $("#table_firmware").DataTable({
        responsive: true,
        paging: true,
        sort: true,
        order: [[ 1, 'asc' ]],
        searching: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar em todas as colunas",
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
    let table_config = $("#table_config").DataTable({
        responsive: true,
        paging: true,
        sort: true,
        order: [[ 1, 'asc' ]],
        searching: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar em todas as colunas",
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

    function buscarICCID(data){
        let button = $("#enviarComando");
        button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
        table_iccid.clear();
        $.ajax({
                url: '<?= site_url("iscas/isca/buscarDadosIscaMassa") ?>',
                type: 'POST',
                data: data,
                success: function(data){
                    button.attr('disabled', false).html('Enviar');
                    $("#row_table_iccid").css("display",'block');
                    let results = JSON.parse(data);
                    if(results.status == true) {
                        results.dados.forEach(dado => {
                            table_iccid.row.add([
                                dado.serial,
                                (dado.ccid != null && dado.ccid != '' && dado.ccid != '') ? dado.ccid : 'Indefinido'
                            ]).draw();
                        });
                    } else {
                        alert("Erro ao solicitar ICCID, tente novamente.");
                    }
                },
                error: function(){
                    alert("Erro ao solicitar ICCID, tente novamente.");
                }
            });
    }
    function buscarConfig(data){
        let button = $("#enviarComando");
        button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
        table_config.clear();
        $.ajax({
            url: '<?= site_url("iscas/isca/buscarDadosIscaMassa") ?>',
            type: 'POST',
            data: data,
            success: function(data){
                button.attr('disabled', false).html('Enviar');
                $("#row_table_config").css("display",'block');
                let results = JSON.parse(data);
                if(results.status == true) {
                    results.dados.forEach(dado => {
                        table_config.row.add([
                            dado.serial,
                            (dado.apn != null && dado.apn != '' && dado.apn != 'null') ? dado.apn : 'Indefinido',
                            (dado.usuario != null && dado.usuario != '' && dado.usuario != 'null') ? dado.usuario : 'Indefinido',
                            (dado.senha != null && dado.senha != '' && dado.senha != 'null') ? dado.senha : 'Indefinido',
                            (dado.ip1 != null && dado.ip1 != '' && dado.ip1 != 'null') ? dado.ip1 : 'Indefinido',
                            (dado.porta1 != null && dado.porta1 != '' && dado.porta1 != 'null') ? dado.porta1 : 'Indefinido',
                            (dado.porta1 != null && dado.porta1 != '' && dado.porta1 != 'null') ? dado.porta1 : 'Indefinido',
                            (dado.ip2 != null && dado.ip2 != '' && dado.ip2 != 'null') ? dado.ip2 : 'Indefinido',
                            (dado.porta2 != null && dado.porta2 != '' && dado.porta2 != 'null') ? dado.porta2 : 'Indefinido'
                        ]).draw();
                    });
                }
                 else {
                    $("#resposta_comando").html(results.msg);
                }
            },
            error: function(){
                alert("Erro ao solicitar ICCID, tente novamente.");
            }
        });
    }
    function buscarVersaoFirmware(data){
        let button = $("#enviarComando");
        button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
        table_firmware.clear();
        $.ajax({
            url: '<?= site_url("iscas/isca/buscarDadosIscaMassa") ?>',
            type: 'POST',
            data: data,
            success: function(data){
                button.attr('disabled', false).html('Enviar');
                $("#row_table_firmware").css("display",'block');
                let results = JSON.parse(data);
                if(results.status == true) {
                        results.dados.forEach(dado => {
                            table_firmware.row.add([
                                dado.serial,
                                (dado.firmware != null && dado.firmware != '' && dado.firmware != '') ? dado.firmware : 'Indefinido'
                            ]).draw();
                        });
                    } else {
                    $("#resposta_comando").html(results.msg);
                }
            },
            error: function(){
                alert("Erro ao solicitar versão de firmware, tente novamente.");
            }
        });
    }

    function enviarComando(data){
        let button = $("#enviarComando");
        button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');

        $.ajax({
            url : '<?php echo site_url('iscas/comandos_isca/ajax_envio_comandos_massa') ?>',
            type : 'POST',
            data: data,
            success : function(callback){
                resposta = JSON.parse(callback);
                alert(converterRespostas(resposta));
                limparParametros();
                button.attr('disabled', false).html('Enviar Comando');

                // Esconde as divs de configurações
                $('#CONFIG_CONEXAO').css('display','none');
                $('#PARAM_ENVIO').css('display','none');

            },
            error : function () {
                alert('Erro ao enviar o comando');
                limparParametros();
                button.attr('disabled', false).html('Enviar Comando');

                // Esconde as divs de configurações
                $('#CONFIG_CONEXAO').css('display','none');
                $('#PARAM_ENVIO').css('display','none');
            }
        });

        // button.attr('disabled', false).html('Enviar Comando');
        return false;
    }
    function validarParametrosConfigConexao(data){
        if(data.tipoComando == "" || data.tipoComando == null ){
            alert("Selecione o Comando");
            return false;
        }
        else if(data.serial == ""){
            alert("Informe o serial;");
            return false;
        }
        else if(data.auth == null){
            alert("Selecione o tipo de autenticação.");
            return false;
        }
        else if(data.apn == ""){
            alert("Informe o APN.");
            return false;
        }
        else if(data.user_id == ""){
            alert("Informe o usuário.");
            return false;
        }
        else if(data.password == ""){
            alert("Informe a senha.");
            return false;
        }
        else if(data.server_ip_1 == ""){
            alert("Informe o IP_Serv_1.");
            return false;
        }
        else if(data.server_port_1 == ""){
            alert("Informe a Porta_Serv_1.");
            return false;
        }
        else if(data.server_ip_2 == ""){
            alert("Informe o IP_Serv_2.");
            return false;
        }
        else if(data.server_port_2 == ""){
            alert("Informe a Porta_Serv_2.");
            return false;
        }
        else{
            return true;
        }
    }

    function validaParametrosRedeColabTemp(data){
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

    function limparParametros(){
        $('#intervaloRX').val('10');
        $('#periodoRXNormal').val('300');
        $('#intervaloGPRSNormal').val('60');
        $('#intervaloTX').val('2');
        $('#numeroTransm').val('1');
        $('#intervaloGPRSEmerg').val('10');
        $('#periodoRXEmerg').val('300');
        $('#tempoSleep').val('5');
        $('#tempoJammer').val('0');
        $("#cliente").val("");
        $('#dispositivosId').html('');
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

            $("#serial").val("");
        $("#tipoComando").val("");
        $('#checkboxesDispositivos').hide();
    }

    function converterRespostas(json) {
        let sucessos = ""
        let falhas = ""
        for( let i = 0; i < json.length; i++) {
            if(json[i].status === true) {
                sucessos += json[i].serial + ((json.length - 1) !== i ? ", " : ".");
            } else {
                falhas += json[i].serial + ((json.length - 1) !== i ? ", " : ".");
            }
        }

        if(sucessos !== "") {
            sucessos = "Comando enviado com sucesso ao(s) dispositivo(s): " + sucessos;
        } 
        if(falhas !== "") {
            falhas = "Erro ao enviar comando ao(s) dispositivo(s): " + falhas;
        }
        respostas = sucessos + "\n" + falhas;
        return respostas;
    }

</script>
