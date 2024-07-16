var table_iccid;
var table_firmware;
var table_config;
var flagArquivo = true;

$(document).ready(function(){

    $('#tabelas').hide();

    $("#dispositivosId").select2({
        width: '100%',
        placeholder: "Selecione pelo menos um dispositivo",
        language: "pt-BR",
        mutiple: true
    });

    $('#info-icon-comandos').click(function() {
        $('#modalModeloItens').modal('show');
    });

    $('#dispositivosId').on('change', function() {
        if (flagArquivo) {
            $('#arquivoItensComandos').val('');
        }
        flagArquivo = true;
    });

    $('#limparDispositivosComandos').click(function() {
        $('#arquivoItensComandos').val('');
        $('#dispositivosId').empty().trigger('change');
        $('#cliente').val('').trigger('change');
        $('#tipoComando').val('').trigger('change');
        $('#tabelas').hide();
        $("#selectAll").prop("checked", false).trigger('change');
    });

    $('#arquivoItensComandos').on('change', function() {
        flagArquivo = false;
        $('#dispositivosId').val('').trigger('change');
    });

    $("#tipoComando").select2({
        width: '100%',
        placeholder: "Selecione um comando",
        language: "pt-BR"
    });

    loadClientes();

    $('#selectAll').on('change', function() {
        if($(this).prop('checked')) {
            $('#dispositivosId').attr('disabled', true).trigger('change');
            $('#arquivoItensComandos').attr('disabled', true).val('');
        } else {
            $('#dispositivosId').attr('disabled', false).trigger('change');
            $('#arquivoItensComandos').attr('disabled', false);
        }
    })

    $("#tipoComando").change(function(){
        $('#tabelas').hide();

        if($(this).val() == 'CONFIG_CONEXAO'){
            $('#CONFIG_CONEXAO').css('display','block');
            $('#PARAM_ENVIO').css('display','none');
            var maxHeight = 0;
            resizeFields();
            // limparParametros();
        }
        else if($(this).val() == 'PARAM_ENVIO'){
            $('#CONFIG_CONEXAO').css('display','none');
            $('#PARAM_ENVIO').css('display','block');
            resizeFields();
            // limparParametros();
        }
        else{
            $('#CONFIG_CONEXAO').css('display','none');
            $('#PARAM_ENVIO').css('display','none');
            resizeFields();
            // limparParametros();
        }
    });

    $(window).resize(function(){
        resizeFields();
    });

    // Envia o comando
    $("#enviarComando").click(async function(event){
        let selectAll = $('#selectAll').prop('checked');
        let fileInput = document.getElementById('arquivoItensComandos');
        let file = fileInput.files[0];
        var searchIDs = [];
        

        $("#row_table_iccid").css("display",'none');
        $("#row_table_firmware").css("display",'none');
        $("#row_table_config").css("display",'none');
        $('#tabelas').hide();
        event.preventDefault();
        
        
        if(!selectAll) {
            let selectedValues = $('#dispositivosId').val();
            selectedValues.forEach(function(value) {
                searchIDs.push(value);
            });

        }

        if (file) {
            try {
                var dadosInserir = await readExcel(file);
    
                if (!dadosInserir || dadosInserir.length < 1) {
                    return false;
                } else {
                    dadosInserir.forEach(element => {
                        if (element && 'dispositivo' in element){
                            searchIDs.push(element.dispositivo);
                        }
                    });
                }
            } catch (error) {
                alert(error);
                return false;
            }
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

    table_iccid = $("#table_iccid").DataTable({
        responsive: true,
        paging: true,
        sort: true,
        order: [[ 1, 'asc' ]],
        searching: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        oLanguage: lang.datatable
        
    });

    table_firmware = $("#table_firmware").DataTable({
        responsive: true,
        paging: true,
        sort: true,
        order: [[ 1, 'asc' ]],
        searching: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        oLanguage: lang.datatable
        
    });

    table_config = $("#table_config").DataTable({
        responsive: true,
        paging: true,
        sort: true,
        order: [[ 1, 'asc' ]],
        searching: true,
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blfrtip',
        oLanguage: lang.datatable
        
    });

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

function loadClientes() {
    $('#load').html('<i class="fa fa-spin fa-spinner"></i>');
    $('#cliente').attr('disabled', true);
    $.ajax({
        url: SiteURL + "/monitoramento_iscas/lista_clientes_iscascontrato",
        type: 'POST',
        success: function(callback){

            let arrayIN = JSON.parse(callback);
    
            $('#cliente').select2({
                data: arrayIN.data,
                allowClear: false,
                language: "pt-BR",
                placeholder: 'Selecione um Cliente'
            })

            $("#cliente").attr('disabled', false).select2('val', ' ');

            $("#cliente").change(function(){
                let valor = $(this).val();
                if (valor) {
                    load_iscas_by_cliente_id(valor);
                }
            });

            $('#load').html('');

        },
        error: function(){
            alert("Erro ao buscar clientes! Recarregue a página novamente.");
            $('#load').html('');
        }
    });
}

function load_iscas_by_cliente_id(id) {
    if(id != null && id != '' && id != undefined) {
        $('#loadDisp').html('<i class="fa fa-spin fa-spinner"></i>');
        $('#dispositivosId').attr('disabled', true);
        $.ajax({
            url: SiteURL + "/monitoramento_iscas/lista_iscas_byCliente",
            type: 'POST',
            data:{
                id : id
            },
            success: function(callback) {
                let arrayIN = JSON.parse(callback);

                if(arrayIN['status'] === false) {
                    $('#dispositivosId').empty();
                    alert(arrayIN['msg']);
                } else {
                    $('#dispositivosId').empty();
                    $.each(arrayIN.data, function (i, item) {
                        //$('#dispositivosId').append('<option value="'+item.serial+'">'+item.serial+'</option>').trigger("chosen:updated");
                        $('#dispositivosId').append(new Option(item.text, item.text, false, false));
                    });
                    $('#dispositivosId').trigger('change');
                }

                $('#loadDisp').html('');
                $('#dispositivosId').attr('disabled', false);
            },
            error: function(){
                alert("Erro ao buscar dispositivos! Tente novamente.");
                $('#loadDisp').html('');
                $('#dispositivosId').attr('disabled', false);
            }
        });
    }
    else
    {
        alert('Por favor informe um cliente!');
    }
}

function buscarICCID(data){
    let button = $("#enviarComando");
    button.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Enviando');
    table_iccid.clear();
    $.ajax({
        url: SiteURL + "/iscas/isca/buscarDadosIscaMassa",
        type: 'POST',
        data: data,
        success: function(data){
            button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');
            let results = JSON.parse(data);
            if(results.status == true) {
                let dados = results.dados;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null || dados[i][chave] === '' || dados[i][chave] === 'null') {
                            dados[i][chave] = '';
                        }
                    }
                }
                preencherRespostaComandos(dados, 'SOLICITAR_CCID');
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
        url: SiteURL + "/iscas/isca/buscarDadosIscaMassa",
        type: 'POST',
        data: data,
        success: function(data){
            button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');
            let results = JSON.parse(data);
            if(results.status == true) {
                let dados = results.dados;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null || dados[i][chave] === '' || dados[i][chave] === 'null') {
                            dados[i][chave] = '';
                        }
                    }
                }
                preencherRespostaComandos(dados, 'SOLICITAR_CONFIG')
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
        url: SiteURL + "/iscas/isca/buscarDadosIscaMassa",
        type: 'POST',
        data: data,
        success: function(data){
            button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');
            let results = JSON.parse(data);
            if(results.status == true) {
                let dados = results.dados;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null || dados[i][chave] === '' || dados[i][chave] === 'null') {
                            dados[i][chave] = '';
                        }
                    }
                }
                preencherRespostaComandos(dados, 'SOLICITAR_VERCAO_FIRMWARE');
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
        url : SiteURL + '/iscas/comandos_isca/ajax_envio_comandos_massa',
        type : 'POST',
        data: data,
        success : function(callback){
            resposta = JSON.parse(callback);
            alert(converterRespostas(resposta));
            limparParametros();
            button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');

            // Esconde as divs de configurações
            $('#CONFIG_CONEXAO').css('display','none');
            $('#PARAM_ENVIO').css('display','none');

        },
        error : function () {
            alert('Erro ao enviar o comando');
            limparParametros();
            button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');

            // Esconde as divs de configurações
            $('#CONFIG_CONEXAO').css('display','none');
            $('#PARAM_ENVIO').css('display','none');
        }
    });

    // button.attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar');
    return false;
}

function validarParametrosConfigConexao(data){
    if(data.tipoComando == "" || data.tipoComando == null ){
        alert("Selecione o Comando");
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
    if(!data.tipoComando) {
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
    $("#cliente").val("").trigger('change');
    $('#dispositivosId').empty().trigger('change');
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
}

function converterRespostas(json) {
    let sucessos = "";
    let qtdSucessos = 0;
    let falhas = "";
    let qtdFalhas = 0;
    let msgQtd = "";

    for( let i = 0; i < json.length; i++) {
        if(json[i].status === true) {
            sucessos += json[i].serial + ((json.length - 1) !== i ? ", " : ".");
            qtdSucessos++;
        } else {
            falhas += json[i].serial + ((json.length - 1) !== i ? ", " : ".");
            qtdFalhas++;
        }
    }

    if (qtdSucessos !== 0) {
        msgQtd += "Quantidade de Sucessos: " + qtdSucessos + ". ";
    }

    if (qtdFalhas !== 0) {
        msgQtd += "Quantidade de Falhas: " + qtdFalhas + ".";
    }

    if(sucessos !== "") {
        sucessos = "Comando enviado com sucesso ao(s) dispositivo(s): " + sucessos;
    } 
    if(falhas !== "") {
        falhas = "Erro ao enviar comando ao(s) dispositivo(s): " + falhas;
    }

    respostas = msgQtd + "\n" + sucessos + "\n" + falhas;
    return respostas;
}

function baixarModeloItens() {

    $('#btnBaixarModelo').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Baixando...');
    let route = SiteURL + '/monitoramento_iscas/downloadModeloItens';
    $.ajax({
        url: route,
        type: 'GET',
        dataType: 'json',
        error: function (e) {
            alert('Erro ao baixar modelo!');
        },
        success: function (data) {
            if (data.status === 200) {
                window.location.href = data.mensagem;
            } else {
                alert('Não foi possível baixar o modelo!');
            }
            $('#btnBaixarModelo').attr('disabled', false).html('Baixar Modelo');
        },
    });
}

function removeAcento(palavra) {
    return palavra.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
}

async function readExcel(file) {
    return new Promise((resolve, reject) => {
        var validExtensions = ['.xls', '.xlsx'];
        var fileExtension = '.' + file.name.split('.').pop();

        if (!validExtensions.includes(fileExtension)) {
            reject('Por favor, selecione um arquivo Excel válido (.xls ou .xlsx).');
        }

        var dadosInserir = [];
        var valorIncompleto = false;

        var reader = new FileReader();
        reader.onload = function (e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, { type: 'array' });
            var sheetName = workbook.SheetNames[0];
            var worksheet = workbook.Sheets[sheetName];
            var letras = /^[a-zA-ZÀ-ÿ]+$/;

            var jsonData = XLSX.utils.sheet_to_json(worksheet, { raw: true });

            if (jsonData.length === 0) {
                reject('O arquivo de dispositivos está vazio.');
            }

            jsonData.forEach((resultado) => {
                colunas = Object.keys(resultado);
                const arrayProcessado = colunas.map(palavra => removeAcento(palavra.toLowerCase()));
                if (arrayProcessado.includes("dispositivo")) {
                    dadosInserir.push({
                        dispositivo: resultado[Object.keys(resultado).find(key => removeAcento(key.toLowerCase()) === 'dispositivo')]
                    });
                } else {
                    valorIncompleto = true;
                }
            });

            if (valorIncompleto) {
                reject('O arquivo não possui a coluna "Dispositivo". Verifique e tente novamente.');
            } else {
                resolve(dadosInserir);
            }
        };

        reader.readAsArrayBuffer(file);
    });
}
