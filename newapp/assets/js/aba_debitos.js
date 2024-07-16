$(document).ready(function () {

    var tableItensNovos = tableComentariosFatruas= false;

    //declaração tabela de ítens novos inseridos nas faturas de contrato
    tableItensNovos = $('#tableItensNovos').DataTable({
        order: [[ 0, 'desc' ]],
        paging: true,
        info: true,
        processing: true,
        lengthChange: false,
        lengthMenu: false,
        responsive: true,
        searching: false,
        columns: [
            {data: 'fatura'},
            {data: 'contrato'},
            {data: 'item'},
            {data: 'vencimento'},
            {data: 'valor'}
        ],
        columnDefs: [
            {"className": "dt-center", "targets": "_all"}
        ],
        otherOptions: {},
        initComplete: function () {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
        language: lang.datatable
    });

    
    tableComentariosFatruas = $('#tabela_comentarios_faturas').DataTable({
        order: [[ 0, 'desc' ]],
        info: false,
        processing: false,
        lengthChange: false,
        lengthMenu: false,
        responsive: true,
        columns: [
            {data: 'id'},
            {data: 'comentario'},
            {data: 'user'},
            {data: 'data'}
        ],
        otherOptions: {},
        initComplete: function () {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
        beforeSend: function () {
            // criamos o loading
            $('#tabela_comentarios_faturas > tbody').html(
                '<tr class="odd">' +
                    '<td valign="top" colspan="15" class="dataTables_empty">' + lang.carregando + '</td>' +
                '</tr>'
            );
        },
        language: lang.datatable
    });    
    
    
    $(document).on('click', '.acao_a_cancelar', function () {
        var botao = $(this);
        //ADICIONA OS VALORES
        $("#fatura_individual").text(botao.attr('data-id_fatura'));
        $("#input_id_fatura_individual")[0].value = botao.attr('data-id_fatura');
        $("#input_status_individual")[0].value = botao.attr('data-status');
        //LIMPA OS CAMPOS DE IMPUT E MENSAGEM
        $('.a_cancelar_ind_alert').css('display', 'none');
        $('textarea[name=motivo_a_cancelar_individual]').val('');
        $('input[name=senha_a_cancelar_ind]').val('');
        $('input[name=faturas_substitutas_ind]').val('');
        //ABRE O MODAL
        $('#a_cancela_fatura_individual').modal('show');
    });

    //açoes do botao  mudar situação fatura para "a pagar" ou "a cancelar"
    $(document).on('click', '.aCancelarFaturaIndividual', function () {
        var botao = $(this);

        if (!$('textarea[name=motivo_a_cancelar_individual]').val()) {
            //mostra a mensagem de campo obrigatorio
            $('.a_cancelar_ind_alert').css('display', 'block');
            $("#msn_a_cancelar_ind").html('<div class="alert alert-danger"><p><b>Insira o motivo!</b></p></div>');

        }else if( !$('input[name=faturas_substitutas_ind]').val() && !$('input[name=senha_a_cancelar_ind]').val() ){
            //mostra a mensagem de campo obrigatorio
            $('.a_cancelar_ind_alert').css('display', 'block');
            $("#msn_a_cancelar_ind").html('<div class="alert alert-danger"><p><b>Insira as fatura(s) substituta(s) ou a senha!</b></p></div>');

        } else {
            $('.a_cancelar_ind_alert').css('display', 'none');

            var id_fatura = $("#input_id_fatura_individual")[0].value;
            var status = $("#input_status_individual")[0].value;
            var motivo = $('textarea[name=motivo_a_cancelar_individual]').val();
            var fats_substitutas = $('input[name=faturas_substitutas_ind]').val();
            var senha = $('input[name=senha_a_cancelar_ind]').val();
            var statusAtual = $('#opcaoDropACancelar').data('status_atual');

            $.ajax({
                type: 'POST',
                url: site_url + "/faturas/a_cancelar_faturas_individual",
                dataType: 'json',
                data: {
                    'id_fatura': id_fatura,
                    'status': status,
                    'motivo': motivo,
                    'faturas_substitutas': fats_substitutas,
                    'senha': senha,
                    'statusAtual': statusAtual

                },
                beforeSend: function () {
                    botao.html('<i class="fa fa-spinner fa-spin"></i> Processando...').attr('disabled', true);
                },
                success: function (retorno) {
                    if (retorno.status == 'OK') {
                        $("#msn_a_cancelar_ind").html('<div class="alert alert-success"><p><b>'+retorno.mensagem+'</b></p></div>');
                        $(".status_fatura_"+id_fatura).html('<span class="hidden">f_a_cancelar</span><span class="label label-default">A Cancelar</span>');
                        $(".cancel_"+id_fatura).attr('data-status', '0')
                            .removeClass('acao_a_cancelar')
                            .addClass('acao_a_pagar')
                            .html('<i class="fa fa-exchange"></i> A Pagar');

                    } else {
                        $("#msn_a_cancelar_ind").html('<div class="alert alert-danger"><p><b>'+retorno.mensagem+'</b></p></div>');
                    }
                },
                error: function(retorno){
                    $("#msn_a_cancelar_ind").html('<div class="alert alert-danger"><p><b>'+retorno.mensagem+'</b></p></div>');
                },
                complete: function(retorno){
                    //mostra a mensagem de retorno
                    $('.a_cancelar_ind_alert').css('display', 'block');
                    botao.html('Confirmar').removeAttr('disabled');
                }
            });
        }
    });    


    $(document).on('click', '.aCancelarFatura', function () {
        var botao = $(this);

        if (!$('input[type=checkbox]').serialize()) {
            //mostra a mensagem de campo obrigatorio
            $('.a_cancelar_alert').css('display', 'block');
            $("#msn_a_cancelar").html('<div class="alert alert-danger"><p><b>Nenhuma Fatura selecionada!</b></p></div>');

        } else if (!$('textarea[name=motivo_a_cancelar]').val()) {
            //mostra a mensagem de campo obrigatorio
            $('.a_cancelar_alert').css('display', 'block');
            $("#msn_a_cancelar").html('<div class="alert alert-danger"><p><b>Insira o motivo!</b></p></div>');

        }else if(!$('input[name=faturas_substitutas]').val() && !$('input[name=senha_a_cancelar]').val()){
            //mostra a mensagem de campo obrigatorio
            $('.a_cancelar_alert').css('display', 'block');
            $("#msn_a_cancelar").html('<div class="alert alert-danger"><p><b>Insira as fatura(s) substituta(s) ou a senha!</b></p></div>');

        } else {
            $('.a_cancelar_alert').css('display', 'none');

            var motivo = $('textarea[name=motivo_a_cancelar]').val();
            var fats_substitutas = $('input[name=faturas_substitutas]').val();
            var senha = $('input[name=senha_a_cancelar]').val();

            var lista_faturas = [];
            $('input[name="cod_fatura[]"]:checked').each(function(){
                lista_faturas.push($(this).val());
            });

            $.ajax({
                url: site_url + "/faturas/a_cancelar_faturas_lote",
                type: 'POST',
                data: {
                    'faturas': lista_faturas,
                    'motivo': motivo,
                    'faturas_substitutas': fats_substitutas,
                    'senha': senha
                },
                dataType: 'json',
                beforeSend: function () {
                    botao.html('<i class="fa fa-spinner fa-spin"></i> Processando...').attr('disabled', true);
                },
                success: function (retorno) {
                    if (retorno.status == 'pagas_a_cancel') {
                        $("#msn_a_cancelar").html('<div class="alert alert-success"><p><b>'+retorno.msn+'</b></p></div>');

                        var faturas = retorno.a_cancelar.split(' ');
                        for (var i = 0; i < faturas.length - 1; i++) {
                            $(".status_fatura_" + faturas[i]).html('<span class="hidden">f_a_cancelar</span><span class="label label-default">A Cancelar</span>');
                            $(".cancel_" + faturas[i]).attr('data-status', '0')
                                .removeClass('acao_a_cancelar')
                                .addClass('acao_a_pagar')
                                .html('<i class="fa fa-exchange"></i> A Pagar');
                        }

                    } else if (retorno.status == 'todas_a_cancel') {
                        $("#msn_a_cancelar").html('<div class="alert alert-success"><p><b>'+retorno.msn+'</b></p></div>');

                        var faturas = retorno.a_cancelar.split(' ');
                        for (var i = 0; i < faturas.length - 1; i++) {
                            $(".status_fatura_" + faturas[i]).html('<span class="hidden">f_a_cancelar</span><span class="label label-default">A Cancelar</span>');
                            $(".cancel_" + faturas[i]).attr('data-status', '0')
                                .removeClass('acao_a_cancelar')
                                .addClass('acao_a_pagar')
                                .html('<i class="fa fa-exchange"></i> A Pagar');
                        }

                    } else {
                        $("#msn_a_cancelar").html('<div class="alert alert-danger"><p><b>'+retorno.msn+'</b></p></div>');
                    }

                    // $("#a_cancela_fatura").modal('hide');
                },
                error: function(retorno){
                    $("#msn_a_cancelar").html('<div class="alert alert-danger"><p><b>'+retorno.msn+'</b></p></div>');
                },
                complete: function(retorno){
                    //mostra a mensagem de retorno
                    $('.a_cancelar_alert').css('display', 'block');
                    botao.html('Confirmar').removeAttr('disabled');
                }
            });
        }

    });

    //simula fechar a mensagem de debitos
    $(document).on('click', '.close_msn_item_novos', function() {
        $('.itens_novos-alert').css('display', 'none');
    });

    $(document).on('click', '.acao_a_pagar', function () {
        var botao = $(this);
        var id = botao.attr('data-id_fatura');
        var status = botao.attr('data-status');

        $('.debito-alert').css('display', 'block');
        $.ajax({
            type: 'POST',
            url: site_url + "/faturas/transFaturasCancelar/" + id + "/" + status,
            dataType: 'json',
            success: function (retorno) {
                if (retorno.status == 'OK') {
                    $("#mensagem_debitos").html('<div class="alert alert-success"><p><b>Status da fatura ' + id + ' Alterado para À PAGAR com sucesso.</p></div>');
                    $(".status_fatura_" + id).html('<span class="hidden">f_a_pagar</span><span class="label label-warning">A Pagar</span>');
                    botao.attr('data-status', '4')
                        .removeClass('acao_a_pagar')
                        .addClass('acao_a_cancelar')
                        .addClass('cancel_' + id)
                        .html('<i class="fa fa-exchange"></i> A Cancelar');

                } else {
                    $("#mensagem_debitos").html('<div class="alert alert-danger"><p><b>Não foi possível alterar o status da fatura ' + id + '.</p></div>');
                }

            }
        });

    });

    //SELICIONA TODOS OS CHECK DA TABELA DE DÉBITOS
    $("#checkTodos").click(function(){
     $('input:checkbox').prop('checked', $(this).prop('checked'));
    });

    //ADICIONA O STATUS SELECIONADO PARA FILTRAR OS DADOS CARREGADOS NA TABELA DE DEBITOS
    $(document).on('click', '.filtroStatusFatura', function(e) {
        e.preventDefault();
        //RESETA OS CAMPOS DE PESQUISA
        $('#searchTableDebitos').attr('disabled', true);
        $('#searchTableDebitos').val('');
        $('#btnSearchDebito').attr('disabled', true);
        $('#filtroDebitos option[value=todos]').prop('selected', true);

        //GUARDA O STATUS SELECIONADO E MANDA CARREGAR A TABELA
        filtro_status_fatura = $(this).attr('data-status');
        loadTableDebitos();
    });

    //GERENCIA A LIBERACAO DO CAMPO DE PESQUISA E DO BOTAO DE PESQUIA DA TABELA DEBITOS
    $(document).on('change', '#filtroDebitos', function(e) {
        e.preventDefault();
        $('#searchTableDebitos').attr('disabled', false);
        $('#btnSearchDebito').attr('disabled', false);

        let fil = $('#filtroDebitos').val();
        if (fil == 'data_vencimento') {
            $('#searchTableDebitos').attr('type', 'date');
        }else {
            $('#searchTableDebitos').attr('type', 'number');
        }
    });

    //EVENTO PARA PESQUISA NA TABELA DE DEBITOS
    $(document).on('click', '#btnSearchDebito', function(e) {
        e.preventDefault();
        //RESETA O FILTRO POR STATUS
        filtro_status_fatura = 'todas';

        var botao = $(this);
        botao.html('<i class="fa fa-spinner fa-spin"></i>');
        //CHAMA A FUNCAO QUE CARREGA OS DADOS DA TABELA FILTRANDO PELO SEARCH
        loadTableDebitos();
        botao.html('<i class="fa fa-search"></i>');
    });

    //EVENTO PARA PESQUISA NA TABELA DE DEBITOS
    $(document).on('click', '#btnResetsearchDebito', function(e) {
        e.preventDefault();
        //RESETA O FILTRO POR STATUS E DESABILITA O CAMPO E BOTAO DE PEQUISA
        filtro_status_fatura = 'todas';
        $('#searchTableDebitos').attr('disabled', true);
        $('#searchTableDebitos').val('');
        $('#btnSearchDebito').attr('disabled', true);

        //RESETA A SELECAO DO FILTRO DE PESQUSIA
        $('#filtroDebitos option[value=todos]').prop('selected', true);

        //CHAMA A FUNCAO QUE CARREGA OS DADOS DA TABELA
        loadTableDebitos();
    });


    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-uk-pre": function (a) {
            if (a == null || a == "") {
                return 0;
            }
            var ukDatea = a.split('/');
            return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
        },

        "date-uk-asc": function (a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-uk-desc": function (a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }

    });

    $("#checkTodos").click(function(){
        $('input:checkbox').prop('checked', $(this).prop('checked'));
    });

    $(document).on('click', '.enviaFat', function() {
        let numero = $(this).attr('data-numero');
        $.ajax({
            url: site_url+"/faturas/enviar/"+numero,
            success: function(data){
                $('.status_fatura_'+numero).html('<span class="label label-warning">A pagar</span>');  //atualiza o label do status da fatura
                alert(data);
            },
            error: function(data) {
                alert(data);

            }
        });
    });

    var serialize_checkbox = "";

    url = "ftp://show:show2592@ftp-arquivos.showtecnologia.com/particao_ftp/uploads/anexo_fatura/";
    controller1 = site_url + "/faturas/count_anexo";
    controller2 = site_url + "/faturas/list_anexos";
    controller3 = site_url + "/faturas/anexar";
    controller4 = site_url + "/faturas/comentario";
    controller5 = site_url + "/faturas/getComments";
    getController = site_url + "/faturas/getComentariosCliente";

    $('#sendAnexo').click(function () {
        $('#formUpload').ajaxForm({
            url: controller3,
            contentType: 'multipart/form-data',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('.alerta').html(data.mensagem);
                $('#anexo').val('');
            },
            error: function (data) {
                $('.alerta').html(data.mensagem);
            }
        })
    });

    $('#countAnexo').html(0);

    $(document).on('click', '.trocaParaSenhaInd', function() {
        var botao = $(this);
        botao.removeClass('trocaParaSenhaInd').addClass('trocaParaFaturasInd');
        $('#senha_a_cancelar_ind').css('display', 'block');
        $('#faturas_subst_a_cancelar_ind').css('display', 'none');
        $('input[name=faturas_substitutas_ind]').val('');
    });

    $(document).on('click', '.trocaParaFaturasInd', function() {
        var botao = $(this);
        botao.removeClass('trocaParaFaturasInd').addClass('trocaParaSenhaInd');
        $('#senha_a_cancelar_ind').css('display', 'none');
        $('#faturas_subst_a_cancelar_ind').css('display', 'block');
        $('input[name=senha_a_cancelar_ind]').val('');
    });

    $(document).on('click', '.trocaParaSenha', function() {
        var botao = $(this);
        botao.removeClass('trocaParaSenha').addClass('trocaParaFaturas');
        $('#senha_a_cancelar').css('display', 'block');
        $('#faturas_subst_a_cancelar').css('display', 'none');
        $('input[name=faturas_substitutas]').val('');
    });

    $(document).on('click', '.trocaParaFaturas', function() {
        var botao = $(this);
        botao.removeClass('trocaParaFaturas').addClass('trocaParaSenha');
        $('#senha_a_cancelar').css('display', 'none');
        $('#faturas_subst_a_cancelar').css('display', 'block');
        $('input[name=senha_a_cancelar]').val('');
    });

    $(document).on('click', '#enviarSenha', function() {
        var botao = $(this);
        $('.a_cancelar_ind_alert').css('display', 'none')
        botao.html('<i class="fa fa-spinner fa-spin"></i> Enviando...');
        botao.attr('disabled', true);
        $.ajax({
            url: site_url + "/faturas/gerarSenhaACancelar",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                alert(data.msg);
            },
            error: function(data) {
                alert("Erro ao gerar senha. Tente novamente!");
                botao.html('Gerar Senha');
                botao.attr('disabled', false);
            },
            complete: function(data) {
                botao.html('Gerar Senha');
                botao.attr('disabled', false);
            }
        });
    })

    $(document).ready(function() {
        //AÇÃO AO CLICAR NO BOTAO BOLETO/PAYPAL
        $(document).on('click', '.gerar_pagamento', function () {
            var botao = $(this);
            var id = botao.attr('data-id_fatura');
            var tipoPagamento = botao.attr('data-tipo_pagamento');
            var data_emissao = botao.attr('data-data_emissao');
            var data_vencimento = botao.attr('data-data_vencimento');
            var dataatualizado_fatura = botao.attr('data-dataatualizado_fatura');
        
            //CONFIGURA AS DATAS
            var emissao = new Date(data_emissao + ' 23:59:59');
            var vencimento = new Date(data_vencimento + ' 23:59:59');
            var dataAtualizado = new Date(dataatualizado_fatura !== '' ? dataatualizado_fatura + " 23:59:59" : data_vencimento + " 23:59:59");
            var hoje = new Date();
        
            if (emissao > vencimento) {
                alert('Data de emissão não pode ser maior que a data de vencimento!');
                window.open(site_url + '/faturas/abrir/' + id, '_blank');
            } else if (dataAtualizado < hoje) {
                alert('Fatura com vencimento desatualizado, atualize para continuar!');
                window.open(site_url + '/faturas/abrir/' + id, '_blank');
            } else {
                //MODIFICA O STATUS DA FATURA PARA 'A PAGAR'
                nEnviado_aPagar(id);
                // Abre a nova aba
                var novaAba = window.open(site_url + '/faturas/imprimir_fatura/' + id, '_blank');
        
                // Adiciona o botão se o tipo de pagamento for 'boleto'
                if (tipoPagamento == 'boleto') {
                    novaAba.onload = function () {
                        var htmlBotao = exibirBotaoAlterarFaturas();
                        novaAba.document.body.insertAdjacentHTML('afterbegin', htmlBotao);
        
                        $(novaAba.document).on("click", '#AlterarFaturas', function () {
                            var divPDF = novaAba.document.querySelectorAll('div.divPDF');
                            var divIMG = novaAba.document.querySelectorAll('div.divIMG');
        
                            divPDF.forEach((item) => {
                                item.hasAttribute("hidden") ? item.removeAttribute("hidden") : item.setAttribute("hidden", "");
                            });
        
                            divIMG.forEach((item) => {
                                item.hasAttribute("hidden") ? item.removeAttribute("hidden") : item.setAttribute("hidden", "");
                            });
        
                        });
                    };
                } else {
                    window.open(site_url + '/faturas/imprimir_fatura/' + id + '?formaPagamento=paypal', '_blank');
                }
            }
        });

        $(document).on('click', '.gerar_desc', async function () {
            var botao = $(this);
            var id = botao.attr('data-id_fatura');
            var novaAba = window.open(site_url + '/faturas/descritivo_fatura/' + id, '_blank');
            
            var checkInterval = setInterval(function() {
                if (novaAba.document.readyState == 'complete') {
                    novaAba.print();            
                    clearInterval(checkInterval); 
                }
            }, 100);

            setTimeout(function() {
                novaAba.close()

            }, 10000); 

        });
    });

    function exibirBotaoAlterarFaturas() {
        var html = `<center>
                        <button id='AlterarFaturas' class='btn btn-default' >
                            Alterar Visualização Faturas
                        </button>
                    </center>`;

        html += `<script>
                    document.getElementById("AlterarFaturas").addEventListener("click", function() { 
                        var divPDF = document.querySelectorAll('div.divPDF');
                        var divIMG = document.querySelectorAll('div.divIMG');

                        divPDF.forEach((item) => {
                            item.hasAttribute("hidden") ? item.removeAttribute("hidden") : item.setAttribute("hidden", "");
                        });
                        
                        divIMG.forEach((item) => {
                            item.hasAttribute("hidden") ? item.removeAttribute("hidden") : item.setAttribute("hidden", "");
                        });

                    });
                </script>`;
        return html;
    }

    $(document).on('focus', '#data_adesao', function() {
        $('#data_adesao').mask('00/0000');
    });
    $(document).on('focus', '#data_mensalidade_inicio', function() {
        $('#data_mensalidade_inicio').mask('00/0000');
    });
    $(document).on('focus', '#data_mensalidade_fim', function() {
        $('#data_mensalidade_fim').mask('00/0000');
    });

    /** Adicionar faturas (adesão e mensalidades) a um contrato */
    $("#form_gerar_fatura_contrato").submit(function(e){
        e.preventDefault();
        var dadosForm = $(this).serializeArray();
        var botao = $('.btn_gerar_faturas_contrato');
        var ids_contratos = [];
        var data_adesao = '';
        var data_mensalidade_inicio = '';
        var data_mensalidade_fim = '';

        for (var i in dadosForm) {
            if (dadosForm[i].name === 'id_contrato') {
                ids_contratos.push(dadosForm[i].value);
            } else if (dadosForm[i].name === 'data_adesao') {
                data_adesao = dadosForm[i].value;
            } else if (dadosForm[i].name === 'data_mensalidade_inicio') {
                data_mensalidade_inicio = dadosForm[i].value;
            } else {
                data_mensalidade_fim = dadosForm[i].value;
            }
        }

        if ( data_adesao != '' && data_mensalidade_inicio != '' && data_mensalidade_fim != '') {
            if (new Date('01/'+ data_adesao) > new Date('01/' + data_mensalidade_inicio)) {
                alert('A data de adesão não pode ser maior que a data de início da mensalidade!');
                return;
            }
            if (new Date('01/'+ data_mensalidade_inicio) > new Date('01/' + data_mensalidade_fim)) {
                alert('A data início da mensalidade não pode ser maior que a data de fim!');
                return;
            }
        } else if ( data_adesao != '' && data_mensalidade_inicio != '') {
            if (new Date('01/'+ data_adesao) > new Date('01/' + data_mensalidade_inicio)) {
                alert('A data de adesão não pode ser maior que a data de início da mensalidade!');
                return;
            }
        } else if ( data_adesao != '' && data_mensalidade_fim != '') {
            if (new Date('01/'+ data_adesao) > new Date('01/' + data_mensalidade_fim)) {
                alert('A data de adesão não pode ser maior que a data de fim da mensalidade!');
                return;
            }
        } else if ( data_mensalidade_inicio != '' && data_mensalidade_fim != '') {
            if (new Date('01/'+ data_mensalidade_inicio) > new Date('01/' + data_mensalidade_fim)) {
                alert('A data início da mensalidade não pode ser maior que a data de fim!');
                return;
            }
        }

        $.ajax({
            url: site_url+'/faturas/gerarFaturasParaContrato',
            type: "POST",
            dataType: "json",
            data: {
                ids_contratos: ids_contratos,
                data_adesao: data_adesao,
                data_mensalidade_inicio: data_mensalidade_inicio,
                data_mensalidade_fim: data_mensalidade_fim
            },
            beforeSend: function (callback) {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Gerando...');
                $('#tableItensNovos > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="5" class="dataTables_empty" style="align-items: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function (callback) {
                if (callback.status == 0) {
                    $("#msn_itens_novos").html('<div class="alert alert-success" role="alert"><p><b>'+callback.msn+'</b></p></div>');
                    // Mostra tabela de ítens inseridos
                    $('#divTableItensNovos').css('display','block');
                    tableItensNovos.clear();
                    tableItensNovos.rows.add(callback.itens_novos);
                    tableItensNovos.draw();

                    //reseta a tabela de debitos
                    initDataTable(id_cliente, 'debitos');

                }else if (callback.status == 1) {
                    $("#msn_itens_novos").html('<div class="alert" role="alert" style="background-color: #fcf8e3; border-color: #faebcc; color: #8a6d3b;"><p><b>'+callback.msn+'</b></p></div>');
                    // Mostra tabela de ítens inseridos
                    $('#divTableItensNovos').css('display','block');
                    tableItensNovos.clear();
                    tableItensNovos.rows.add(callback.itens_novos);
                    tableItensNovos.draw();

                    //reseta a tabela de debitos
                    initDataTable(id_cliente, 'debitos');
                } else {
                    $("#msn_itens_novos").html('<div class="alert alert-danger" role="alert"><p><b>'+callback.msn+'</b></p></div>');
                    tableItensNovos.clear();
                    tableItensNovos.draw();
                }
            },
            error: function(callback){
                $("#msn_itens_novos").html('<div class="alert alert-danger"><p><b>Erro, Tente mais tarde!</b></p></div>');
                tableItensNovos.clear();
                tableItensNovos.draw();
            },
            complete: function(callback){
                //mostra a mensagem de retorno
                $('.itens_novos-alert').css('display', 'block');
                botao.attr('disabled', false).html('Gerar');
            }
        })

    });    

    /** Limpa formulario - gerar faturas para contrato */
    $(document).on('click', '.btn_limpar_faturas_contrato', function() {
        //limpa a tabela de itens novos e esconde-a
        $('#divTableItensNovos').css('display','none');
        $('#id_contrato').val(null).trigger('change');
        tableItensNovos.clear();
        tableItensNovos.draw();
        //esconde a mensagem
        $('.itens_novos-alert').css('display','none');
    });

    /** LIMPA OS DADOS DO MODAL GERAR PARA CONTRATO ANTES DE ABRI-LO */
    $(document).on('click', '.gerarParaContrato', function() {
        //reseta os input do form
        $('#form_gerar_fatura_contrato')[0].reset();
        //limpa a tabela de itens novos e esconde-a
        $('#divTableItensNovos').css('display','none');
        tableItensNovos.clear().draw();
        //esconde a mensagem
        $('.itens_novos-alert').css('display','none');
        
        $('#id_contrato').select2({
            ajax: {
                url: site_url + '/contratos/listAjaxSelectContratos/' + id_cliente,
                dataType: 'json',
                delay: 1000,
            },
            language: idioma,
            allowClear: true,
            width: "100%",
            multiple: true
        });

        //abrir modal
        $('#gerar_faturas_contrato').modal();
    });

    /**
     * ABRE A PAGINA DE EXTRATOS/PAGAMENTOS EM OUTRA ABA
    */
    $(document).on('click', '#btnPagamentos', function () {
        window.open(site_url + '/extract', '_blank');
    });

    /**
     * RECEBE OS COMENTARIOS DE UMA FATURA
    */
    $(document).on('click', '.getComentariosFatura', function (e) {
        e.preventDefault();

        var botao = $(this);
        var id = botao.attr('data-id');

        $('#formComentariosFaturas')[0].reset();


        $.ajax({
            url: site_url + "/faturas/get_comments_fatura",
            type: 'GET',
            data: { id },
            dataType: 'json',
            beforeSend: function () {
                botao.html('<i class="fa fa-spinner fa-spin"></i> ' + lang.comentarios).attr('disabled', true);                
            },
            success: function (callback) {
                tableComentariosFatruas.clear();
                tableComentariosFatruas.rows.add(callback.table);
                tableComentariosFatruas.draw();

                $('#titulo_coment_fatura').html(lang.comentarios_fatura + '# ' + id);
                $('#btnFormComentariosFaturas').attr('data-id', id);
                $("#modalComentariosFatura").modal('show');
            },
            error: function () {
                alert(lang.operacao_nao_finalizada);
            },
            complete: function () {
                //mostra a mensagem de retorno
                $('.a_cancelar_alert').css('display', 'block');
                botao.html('<i class="fa fa-comments"></i>' + lang.comentarios).attr('disabled', false);
            }
        });
    });

    /**
     * ADICIONA UM COMENTARIO A UMA FATURA
    */
    $("#formComentariosFaturas").submit(function (e) {
        e.preventDefault();
        var botao = $('#btnFormComentariosFaturas');
        var id = botao.attr('data-id');
        var dadosform = $(this).serialize() +'&id_fatura='+id;

        $.ajax({
            url: site_url + '/faturas/add_comentario_fatura',
            type: "POST",
            dataType: "json",
            data: dadosform,
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.comentando + '...');
            },
            success: function (callback) {
                if (callback.success) {

                    //ADICIONA O NOVO TERMO NA DATATABLE
                    var dados = callback.retorno;
                    var id = dados.id;
                    var comentario = dados.comentario;
                    var user = dados.user;
                    var data = dados.data;

                    tableComentariosFatruas.rows.add(
                        [{
                            id: id,
                            comentario: comentario,
                            user: user,
                            data: data
                        }]
                    ).draw(null, false);

                } else {
                    alert(callback.msg);
                }
            },
            error: function () {
                alert(lang.operacao_nao_finalizada);
            },
            complete: function () {
                botao.attr('disabled', false).html(lang.comentar);
            }
        })

    });
    
    /**
     * RECEBE OS COMENTARIOS DE UMA FATURA
    */
    $(document).on('click', '.cancelarFatura', function (e) {
        e.preventDefault();

        var botao = $(this);
        var id = botao.attr('data-id');

        $('#formCancelarFatura')[0].reset();
        $('#titulo_cancelar_fatura').html(lang.cancelar_fatura + '# ' + id);
        $('#btnFormCancelarFatura').attr('data-id', id);
        $("#modalCancelarFatura").modal('show');

    });

    /**
     * CANCELAR UMA FATURA
    */
    $("#formCancelarFatura").submit(function (e) {
        e.preventDefault();
        var botao = $('#btnFormCancelarFatura');
        var id = botao.attr('data-id');
        var dadosform = $(this).serialize() +'&id_fatura='+id;

        $.ajax({
            url: site_url + '/faturas/cancelar_fatura',
            type: "POST",
            dataType: "json",
            data: dadosform,
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.cancelando + '...');
            },
            success: function (callback) {
                if (callback.success) {
                    var id = callback.id_fatura;
                    $('.status_fatura_' + id).html('<span class="label label-default">' + lang.cancelado + '</span>');
                    alert(callback.msg);
                    $("#modalCancelarFatura").modal('hide');

                } else {
                    alert(callback.msg);
                }
            },
            error: function () {
                alert(lang.operacao_nao_finalizada);
            },
            complete: function () {
                botao.attr('disabled', false).html(lang.cancelar);
            }
        })

    });

    /**
     * ABRE UMA FATURA PARA EDICAO
    */
    $(document).on('click', '.abrirFatura', function (e) {
        e.preventDefault();

        item_indice_fat = 0;

        var botao = $(this);
        var id = botao.attr('data-id');

        $('#formEditarFatura')[0].reset();
        $('.itemFatura').closest('div').remove();

        $('#titulo_editar_fatura').html(lang.editar_fatura + ' ' + id);
        $('#btnFormEditarFatura').attr('data-id', id);

        $.ajax({
            url: site_url + '/faturas/get_fatura/' + id,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.abrindo);
            },
            success: function (callback) {
                if (callback.success) {
                    //LISTA OS DADOS DA FATURA
                    var fatura = callback.fatura;
                    var itens = callback.itens;                    

                    Object.keys(fatura).forEach(key => {
                        if (key == 'status') 
                            $('#' + key + '_fatura').html(fatura[key]);
                        else
                            $('#' + key + '_fatura').val(fatura[key]);
                    });                   

                    $('#btnFormEditarFatura').attr('data-id_fatura', fatura.Id);
                    
                    var stringItens = [];
                    var qtdItens = Object.keys(itens).length;

                    if (qtdItens) {
                        for (let i = 0; i < qtdItens; i++) {
                            stringItens.push([
                                `<div class="col-md-12 divItemFatura${ item_indice_fat }" id="${ item_indice_fat }">
                                    <div class="form-group col-md-4">
                                        <label>${ lang.descricao }</label>
                                        <textarea class="form-control itemFatura itemFatura${item_indice_fat}"" data-tipo="descricao_item" id="descricaoFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][descricao_item]" rows="2" placeholder="${lang.descricao_item}" readonly>${itens[i].descricao_item}</textarea>
                                    </div>                                    
                                    <div class="form-group col-md-2">
                                        <label>${lang.valor}</label>
                                        <input class="form-control itemFatura itemFatura${item_indice_fat} moeda" id="valorFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][valor_item]" type="text" value="${parseFloat(itens[i].valor_item).toLocaleString('pt-BR')}"  placeholder="0,00" readonly >
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>${lang.tipo_item}</label>                                        
                                        <select class="form-control itemFatura itemFatura${item_indice_fat} selectItem" data-numero_item="${item_indice_fat}" id="tipo_itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][tipo_item]" style="pointer-events:none" readonly>
                                            <option value="" disabled selected></option>
                                            <option value="mensalidade" ${itens[i].tipo_item == "mensalidade" ? "selected" : ""} >${lang.mensalidade}</option>
                                            <option value="adesao" ${itens[i].tipo_item == "adesao" ? "selected" : ""} >${lang.adesao}</option>
                                            <option value="taxa" ${itens[i].tipo_item == "taxa" || itens[i].taxa_item == 'sim' ? "selected" : ""} >${lang.taxa}</option>
                                            <option value="avulso" ${itens[i].tipo_item == "avulso" ? "selected" : ""} >${lang.outros}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2" id="divContratoItemFatura${ item_indice_fat}" ${itens[i].relid_item == 0 ? 'style="display: none"' : 'style="display: block"' } >
                                        <div>
                                            <label>${lang.contrato}</label>
                                            <input class="form-control contrato itemFatura itemFatura${item_indice_fat}"" data-tipo="contrato_item" id="contrato${item_indice_fat}" name="itensFatura[${item_indice_fat}][relid_item]" value="${itens[i].relid_item }"  type="number" readonly >
                                        </div>
                                    </div>
                                    <div class="col-md-2" id="divTaxaItemFatura${ item_indice_fat}" ${ itens[i].taxa_item == 'nao' ? 'style="display: none"' : 'style="display: block"' } >
                                        <div class="form-group">
                                            <label>${lang.tipo_taxa}</label>
                                            <select class="form-control check-itemFatura${ item_indice_fat} itemFatura itemFatura${item_indice_fat}"" data-tipo="tipotaxa_item" id="check-itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat }][tipotaxa_item]" style="pointer-events:none" readonly >
                                                <option value=""></option>
                                                <option value="juros" ${ itens[i].tipo_item == "taxa" && itens[i].taxa_item == 'sim' && itens[i].tipotaxa_item === "juros" ? "selected" : "" } >${ lang.juros }</option>
                                                <option value="boleto" ${ itens[i].tipo_item == "taxa" && itens[i].taxa_item == 'sim' && itens[i].tipotaxa_item === "boleto" ? "selected" : "" } >${ lang.boleto }</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control itemFatura itemFatura${item_indice_fat}"" data-tipo="id_item" id="id_itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][id_item]"  value="${itens[i].id_item}" readonly>

                                    <button type="button" data-indice="${ item_indice_fat}" title="${lang.editar}" class="editItemFatura editItemFatura${item_indice_fat } btn btn-primary btn-mini" style="margin-top: 20px"><i class="fa fa-edit"></i></button>
                                    <button type="button" data-indice="${ item_indice_fat }" title="${ lang.remover }" class="removerItemFatura removerItemFatura${ item_indice_fat } btn btn-danger btn-mini" style="margin-top: 20px"><i class="fa fa-remove"></i></button>
                                </div>`]);

                            item_indice_fat++;
                        }

                        $('#itensDaFatura').html(stringItens.join(''));
                    }

                    //CARREGA OS VALORES DA FATURA
                    calculaValoresFatura();

                    $("#modalEditarFatura").modal('show');

                } else {
                    alert(callback.msg);
                }
            },
            error: function () {
                alert(lang.operacao_nao_finalizada);
            },
            complete: function () {
                botao.attr('disabled', false).html('<i class="fa fa-folder-open"></i> ' + lang.abrir);
            }
        });
    });
 
    var valorAnteriorItem = 0;
    /**
     * LIBERA A EDICAO DE UM ITEM 
     */ 
    $(document).on('click', '.editItemFatura', function (e) {
        e.preventDefault();
        var botao = $(this);
        var indice = botao.attr('data-indice');
        $('.itemFatura' + indice).attr('disabled', false)
        .attr('readonly', false)
        .css('pointer-events', 'auto');

        valorAnteriorItem = $('#valorFatura' + indice).val();
        botao.removeClass('editItemFatura')
        .addClass('saveitemFatura')
        .html('<i class="fa fa-floppy-o"></i>');
    });

    /**
     * BLOQUEIA A EDICAO DE UM ITEM 
     */
    $(document).on('click', '.saveitemFatura', function (e) {
        e.preventDefault();

        var botao = $(this);
        var indice = botao.attr('data-indice');
        
        //ATUALIZA OS VALORES DA FATURA
            calculaValoresFatura();

        $('.itemFatura' + indice).attr('readonly', 'readonly')
        .attr('disabled', false)
        .css('pointer-events', 'none');

        botao.removeClass('saveitemFatura')
            .addClass('editItemFatura')
            .html('<i class="fa fa-edit"></i>');
    });

    /**
     * REMOVE UM ITEM DA EDICAO
     */
    $(document).on('click', '.removerItemFatura ', function (e) {
        e.preventDefault();

        var indice = $(this).attr('data-indice');

        var confirma = confirm(lang.confirma_remocao_item);

        if (confirma) {
               
            //ATUALIZA OS VALORES DA FATURA
            calculaValoresFatura();            
            
            //REMOVE O ITEM
            $('.removerItemFatura' + indice).closest('div').remove();

            
        }
    });


    //ALTERNA ENTRE TIPOS DE ITEM, HABILITANDO/ DESABILITANDO TAXA (NOVO ITEM)
    $(document).on('change', '#tipo_itemFatura', function (e) {
        e.preventDefault()
        let itemSelecionado = $(this).val();

        if (itemSelecionado == 'taxa') {
            $('#divTaxaItemFatura').css('display', 'block');
            $('#divContratoItemFatura').css('display', 'none');

        } else if (itemSelecionado == 'avulso') {
            $('#divTaxaItemFatura').css('display', 'none');
            $('#divContratoItemFatura').css('display', 'none');

        } else {
            $('#divTaxaItemFatura').css('display', 'none');
            $('#divContratoItemFatura').css('display', 'block');
        }
    });

    //ALTERNA ENTRE TIPOS DE ITEM, HABILITANDO/ DESABILITANDO TAXA (EDITANDO UM ITEM)
    $(document).on('change', '.selectItem', function (e) {
        e.preventDefault()
        let itemSelecionado = $(this).val();
        let numero_item = $(this).attr('data-numero_item');

        if (itemSelecionado == 'taxa') {
            $('#divTaxaItemFatura' + numero_item).css('display', 'block');
            $('#divContratoItemFatura' + numero_item).css('display', 'none');
            
            $('#contrato' + numero_item).val('0');

        } else if (itemSelecionado == 'avulso') {
            $('#divTaxaItemFatura' + numero_item).css('display', 'none');
            $('#divContratoItemFatura' + numero_item).css('display', 'none');

            $('#contrato' + numero_item).val('0');
            $('#check-itemFatura' + numero_item).val('');

        } else {
            $('#divTaxaItemFatura' + numero_item).css('display', 'none');
            $('#divContratoItemFatura' + numero_item).css('display', 'block');

            $('#check-itemFatura' + numero_item).val('');
        }
    });

    //Varre os itens (menos juros e boleto) e retorna o somatorio de seus valores
    function calcula_subtotal() {
        let subTotal = 0;
        for (let i = 0; i < item_indice_fat; i++) {
            if ($('#check-itemFatura' + i).val() != 'juros') {
                subTotal += parseFloat($('#valorFatura' + i).val().replace('.', '').replace(',', '.'));
            }
        }
        return subTotal;
    }

    //ATUALIZA A FATURA PARA QUANDO A DATA DE VENCIMENTO FOR MODIFICADA
    $("#dataatualizado_fatura_fatura").blur(function (e) {
        e.preventDefault()

        let data_atual = $('#data_vencimento_fatura').val();
        let data_nova = $(this).val();
        let valor = calcula_subtotal();
        var taxa = 0.33;

        //VERIFICA SE A DATA EH INFERIOR A DATA ATUAL
        var hoje = new Date();
        var dia = String(hoje.getDate()).padStart(2, '0');
        var mes = String(hoje.getMonth() + 1).padStart(2, '0'); //January is 0!
        var ano = hoje.getFullYear();
        hoje = new Date(ano + '-' + mes + '-' + dia + ' 00:00:00');
        var data_nova_tem = new Date(data_nova + ' 00:00:00');
        
        if (data_nova_tem < hoje) {
            $(this).val('');
            return alert(lang.data_atual_maior);
        }

        var num_dias = qtd_dias_entre_datas(data_atual, data_nova);
        var taxa_juros = calcula_juros(parseFloat(valor), taxa, num_dias);
        taxa_juros = parseFloat(taxa_juros).toLocaleString('pt-BR');

        temJuros = false;
        for (let i = 0; i < item_indice_fat; i++) {
            if ($('#tipo_itemFatura' + i).val() == 'taxa' && $('#check-itemFatura' + i).val() == 'juros'){                
                $('#valorFatura' + i).val(taxa_juros);
                temJuros = true;
            }
        }

        if (!temJuros) {
            var novoItem = `<div class="col-md-12 divItemFatura${item_indice_fat}" >
                <div class="form-group col-md-4">
                    <label>${lang.descricao}</label>
                    <textarea class="form-control itemFatura itemFatura${item_indice_fat}" id="descricaoFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][descricao_item]" rows="2" placeholder="${lang.descricao_item}" required readonly >Juros</textarea >
                </div>
                <div class="form-group col-md-2">
                    <label>${lang.valor}</label>
                    <input class="form-control itemFatura itemFatura${item_indice_fat} moeda" id="valorFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][valor_item]" type="text" value="${taxa_juros}"  placeholder="0,00" required readonly >
                </div>
                <div class="form-group col-md-2">
                    <label>${lang.tipo_item}</label>
                    <select class="form-control itemFatura itemFatura${item_indice_fat} selectItem" data-numero_item="${item_indice_fat}" id="tipo_itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][tipo_item]" required style="pointer-events:none" readonly>
                        <option value="" disabled selected></option>
                        <option value="mensalidade">${lang.mensalidade}</option>
                        <option value="adesao">${lang.adesao}</option>
                        <option value="taxa" selected >${lang.taxa}</option>
                        <option value="avulso">${lang.outros}</option>
                    </select>
                </div>
                <div class="col-md-2" id="divContratoItemFatura${item_indice_fat}" style="display: none"} >
                    <div>
                        <label>${lang.contrato}</label>
                        <input class="form-control contrato itemFatura itemFatura${item_indice_fat}" id="contrato${item_indice_fat}" name="itensFatura[${item_indice_fat}][relid_item]" value=""  type="number" readonly >
                    </div>
                </div>
                <div class="col-md-2" id="divTaxaItemFatura${item_indice_fat}" style="display: block" >
                    <div class="form-group">
                        <label>${lang.tipo_taxa}</label>
                        <select class="form-control itemFatura itemFatura${item_indice_fat}" itemFatura${item_indice_fat}" id="check-itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][tipotaxa_item]" style="pointer-events:none" readonly >
                            <option value=""></option>
                            <option value="juros" selected>${lang.juros}</option>
                            <option value="boleto">${lang.boleto}</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" class="form-control itemFatura" disabled >
                <button data-indice="${item_indice_fat}" title="${lang.editar}" class="editItemFatura editItemFatura${item_indice_fat} btn btn-primary btn-mini" style="margin-top: 20px"><i class="fa fa-edit"></i></button>
                <button data-indice="${item_indice_fat}" title="${lang.remover}" class="removerItemFatura removerItemFatura${item_indice_fat} btn btn-danger btn-mini" style="margin-top: 20px"><i class="fa fa-remove"></i></button>
            </div>`;

            $(novoItem).eq(0).insertBefore('#itensDaFatura');
            item_indice_fat++;
        }

        //ATUALIZA OS VALORES DA FATURA
        calculaValoresFatura();

    });


    /**
     * ATUALIZA OS TOTAIS QUANDO SE ALTERA UM IMPOSTO
    */
    $(".inputImposto").blur(function () {
        calculaValoresFatura();
    });

    /**
     * EDITA UMA FATURA
    */
    $("#formEditarFatura").submit( function (e) {
        e.preventDefault();

        //VE SE TEM ALGUM ITEM FALTANDO CONFIRMAR A EDICAO
        for (let i = 0; i <= item_indice_fat; i++) {
            var confere = $('.editItemFatura' + i);
            if (typeof confere !== 'undefined' && confere.hasClass("saveitemFatura")) {
                return alert(lang.confirmar_alteracao_item + ': ' + $('#descricaoFatura' + i).val());                
            }
        }


        var botao = $('#btnFormEditarFatura');
        var id = botao.attr('data-id');
        var dadosform = $(this).serialize() +'&Id='+id;
        var dados = $(this).serialize() + '&faturaAtividade=' + faturaAtividade;

        if ($('#titulo_editar_fatura').html() == 'Mesclar Faturas') {
            botao.attr('disabled', true).html('<i class="fa fa fa-spin fa-spinner"></i> Mesclando...');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: site_url + '/faturas/gerar_fatura_mesclada',
                data:dados,
                success: function(callback){
                    if (callback.status === true){
                        loadTableDebitos();
                        loadTotaisTableDebitos(id_cliente);
                        alert(callback.msg);
                        botao.attr('disabled', false).html('Mesclar');
                        $("#modalEditarFatura").modal('hide');
                    }
                    else{
                        alert(callback.msg);
                        botao.attr('disabled', false).html('Mesclar');
                    }
                },
                complete: async function(data){
                    if (data.status === 200){
                        for (let i = 0; i <ids_faturas_a_cancelar.length; i++) {
                            const id_fatura = ids_faturas_a_cancelar[i];

                            try{
                                document.querySelector('#loading').setAttribute('data-content', 'Cancelando fatura ' + id_fatura + '...');
                                document.getElementById('loading').style.display = 'block';
                                const resposta = await $.ajax({
                                                type: "POST",
                                                dataType: "json",
                                                url: site_url + '/faturas/alterar_status_fatura',
                                                data: { id_fatura: id_fatura, status: 3 },
                                });

                                if (resposta.status === true){
                                    document.querySelector('#loading').setAttribute('data-content', 'Fatura ' + id_fatura + ' cancelada com sucesso!');
                                    document.getElementById('loading').style.display = 'block';
                                    tableDebitos.ajax.reload(null, false);
                                    await new Promise(resolve => setTimeout(resolve, 1000));
                                }else{
                                    document.querySelector('#loading').setAttribute('data-content', 'Erro ao cancelar fatura ' + id_fatura + '!');
                                }
                            }catch (error){
                                alert('Erro ao cancelar fatura ' + id_fatura + '!');
                                document.getElementById('loading').style.display = 'none';
                            } finally{
                                document.getElementById('loading').style.display = 'none';
                            }
                        }     
                    }else{
                        document.getElementById('loading').style.display = 'none';
                    }              
                },
                error: function(error){
                    alert(error)
                    botao.attr('disabled', false).html('Mesclar');
                }
            })
        }else {
            $.ajax({
                url: site_url + '/faturas/editar_fatura',
                type: "POST",
                dataType: "json",
                data: dadosform,
                beforeSend: function () {
                    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.atualizando);
                },
                success: function (callback) {
                    if (callback.success) {
                        tableDebitos.ajax.reload(null, false);
                        alert(callback.msg);
                        $("#modalEditarFatura").modal('hide');

                    } else {
                        alert(callback.msg);
                    }
                },
                error: function () {
                    alert(lang.operacao_nao_finalizada);
                },
                complete: function () {
                    botao.attr('disabled', false).html(lang.atualizar);
                }
            })
        }

    });


    //NOVO ITEM    
    $(document).on('click', '#btnNovoItemFatura', function (e) {
        e.preventDefault();

        var descricao_itemFatura = $('#descricao_itemFatura').val();
        var valor_itemFatura = $('#valor_itemFatura').val();
        var tipotaxa_itemFatura = $('#tipotaxa_itemFatura').val();
        var tipo_itemFatura = $('#tipo_itemFatura').val();
        var contrato_itemFatura = $('#contrato_itemFatura').val();

        if (descricao_itemFatura == '' || valor_itemFatura == '' || tipo_itemFatura == null) {            
            return alert(lang.campos_obrigatorios_add_item_fatura);        

        } else {
            if ((tipo_itemFatura == 'mensalidade' || tipo_itemFatura == 'adesao') && contrato_itemFatura == '') {                
                return alert(lang.informe_contrato_item);

            } else if (tipo_itemFatura == 'taxa' ) {
                if( tipotaxa_itemFatura == '') {
                    return alert(lang.informe_tipo_taxa);
                
                } else if (tipotaxa_itemFatura === 'boleto') {
                    var faturaTemTaxaBoleto = false;
                    for (let i = 0; i < item_indice_fat; i++) {
                        if ($('#check-itemFatura' + i).val() == 'boleto' )
                            faturaTemTaxaBoleto = true;
                    }
                    if (faturaTemTaxaBoleto) 
                        return alert(lang.fatura_ja_possui_taxa_boleto);

                
                } else if (tipotaxa_itemFatura === 'juros') {
                    var faturaTemTaxaJuros = false;
                    for (let j = 0; j < item_indice_fat; j++) {
                        if ($('#check-itemFatura' + j).val() == 'juros')
                            faturaTemTaxaJuros = true;
                    }
                    if (faturaTemTaxaJuros)
                        return alert(lang.fatura_ja_possui_taxa_juros);
                }
            }

            var novoItem = `<div class="col-md-12 divItemFatura${item_indice_fat}" >
                <div class="form-group col-md-4">
                    <label>${lang.descricao}</label>
                    <textarea class="form-control itemFatura itemFatura${item_indice_fat}" id="descricaoFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][descricao_item]" rows="2" placeholder="${lang.descricao_item}" required readonly >${descricao_itemFatura}</textarea >
                </div>
                <div class="form-group col-md-2">
                    <label>${lang.valor}</label>
                    <input class="form-control itemFatura itemFatura${item_indice_fat} moeda" id="valorFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][valor_item]" type="text" value="${valor_itemFatura}"  placeholder="0,00" required readonly >
                </div>
                <div class="form-group col-md-2">
                    <label>${lang.tipo_item}</label>
                    <select class="form-control itemFatura itemFatura${item_indice_fat} selectItem" data-numero_item="${item_indice_fat}" id="tipo_itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][tipo_item]" required style="pointer-events:none" readonly>
                        <option value="" disabled selected></option>
                        <option value="mensalidade" ${tipo_itemFatura == "mensalidade" ? "selected" : ""} >${lang.mensalidade}</option>
                        <option value="adesao" ${tipo_itemFatura == "adesao" ? "selected" : ""} >${lang.adesao}</option>
                        <option value="taxa" ${tipo_itemFatura == "taxa" ? "selected" : ""} >${lang.taxa}</option>
                        <option value="avulso" ${tipo_itemFatura == "avulso" ? "selected" : ""} >${lang.outros}</option>
                    </select>
                </div>
                <div class="col-md-2" id="divContratoItemFatura${item_indice_fat}" ${contrato_itemFatura == 0 ? 'style="display: none"' : 'style="display: block"'} >
                    <div>
                        <label>${lang.contrato}</label>
                        <input class="form-control contrato itemFatura itemFatura${item_indice_fat}" id="contrato${item_indice_fat}" name="itensFatura[${item_indice_fat}][relid_item]" value="${ contrato_itemFatura }"  type="number" readonly >
                    </div>
                </div>
                <div class="col-md-2" id="divTaxaItemFatura${item_indice_fat}" ${tipo_itemFatura == 'taxa' ? 'style="display: block"' : 'style="display: none"'} >
                    <div class="form-group">
                        <label>${lang.tipo_taxa}</label>
                        <select class="form-control itemFatura itemFatura${item_indice_fat}" itemFatura${item_indice_fat}" id="check-itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][tipotaxa_item]" style="pointer-events:none" readonly >
                            <option value=""></option>
                            <option value="juros" ${tipotaxa_itemFatura == "juros" ? "selected" : ""} >${lang.juros}</option>
                            <option value="boleto" ${tipotaxa_itemFatura == "boleto" ? "selected" : ""} >${lang.boleto}</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" class="form-control itemFatura" disabled >
                <button data-indice="${item_indice_fat}" title="${lang.editar}" class="editItemFatura editItemFatura${item_indice_fat} btn btn-primary btn-mini" style="margin-top: 20px"><i class="fa fa-edit"></i></button>
                <button data-indice="${item_indice_fat}" title="${lang.remover}" class="removerItemFatura removerItemFatura${item_indice_fat} btn btn-danger btn-mini" style="margin-top: 20px"><i class="fa fa-remove"></i></button>
            </div>`;

            $(novoItem).eq(0).insertBefore('#itensDaFatura');
            item_indice_fat++;

            //ATUALIZA OS VALORES DA FATURA
            calculaValoresFatura();

            //LIMPA OS CAMPOS DE INPUT DE NOVO ITEM
            $('#tipo_itemFatura').val('');
            $('#tipotaxa_itemFatura').val('');
            $('#valor_itemFatura').val('');
            $('#descricao_itemFatura').val('');
            $('#contrato_itemFatura').val('');
        }
    });    

    
});


function qtd_dias_entre_datas(data_atual, data_nova) {
    var date1 = new Date(data_atual);
    var date2 = new Date(data_nova);

    // To calculate the time difference of two dates
    var Difference_In_Time = date2.getTime() - date1.getTime();

    // To calculate the no. of days between two dates
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    return Difference_In_Days;
}

/*
* função para calcular juros por dia
* retorna o valor dos juros
*/
function calcula_juros(valor, taxa, num_dias) {
    var juros = valor * (2 / 100);
    var multa = (valor * taxa / 100) * num_dias;
    return (juros + multa).toFixed(2);
}

/**
 * ATUALIZA OS VALORES DA FATURA
*/
function calculaValoresFatura() {

    var valor_juros = 0.0;
    var valor_boleto = 0.0;
    var sub_total = 0.0;

    for (let i = 0; i < item_indice_fat; i++) {
        var valor_item = $('#valorFatura' + i).val();

        if (typeof valor_item !== 'undefined') {
            var valor_item = $('#valorFatura' + i).val().replace('.', '').replace(',', '.');
            sub_total += parseFloat(valor_item);
            var tipo_itemFatura = $('#tipo_itemFatura' + i).val();
            var tipo_item = $('#check-itemFatura' + i).val();

            if (tipo_itemFatura == 'taxa') {
                if (tipo_item == 'juros')
                    valor_juros = parseFloat(valor_item);

                if (tipo_item == 'boleto')
                    valor_boleto = parseFloat(valor_item);
            }
        }
    }

    var iss = $('#iss_fatura').val();
    var pis = $('#pis_fatura').val();
    var cofins = $('#cofins_fatura').val();
    var irpj = $('#irpj_fatura').val();
    var csll = $('#csll_fatura').val();

    $('#valor_total_fatura').val(sub_total);
    var valor_total = sub_total;

    if (pis) valor_total -= (valor_total * (pis / 100));
    if (iss) valor_total -= (valor_total * (iss / 100));
    if (cofins) valor_total -= (valor_total * (cofins / 100));
    if (irpj) valor_total -= (valor_total * (irpj / 100));
    if (csll) valor_total -= (valor_total * (csll / 100));

    $('#qtd_itens_fatura').text($('.itemFatura').length / 6);
    $('#juros_fatura').text(valor_juros.toLocaleString('pt-BR'));
    $('#boleto_fatura').text(valor_boleto.toLocaleString('pt-BR'));
    $('#subTotal_fatura').text(sub_total.toLocaleString('pt-BR'));
    $('#total_fatura').text(valor_total.toLocaleString('pt-BR'));

}

function fecharModalAnexo(){
    $('#anexo').val('');
}

function copyToClipboard(elementId) {
    // Create an auxiliary hidden input
    var aux = document.createElement("input");
    // Get the text from the element passed into the input
    aux.setAttribute("value", document.getElementById(elementId).innerHTML);
    // Append the aux input to the body
    document.body.appendChild(aux);
    // Highlight the content
    aux.select();
    // Execute the copy command
    if(document.execCommand("copy")){
        alert('Link copiado!');
    }
    // Remove the input from the body
    document.body.removeChild(aux);
}

var ids_faturas_a_cancelar = [];
var faturaAtividade;
async function getcheckBox($acao=false){
    var text = "";
    serialize_checkbox="";
    

    if ($acao && $acao == 'a cancelar') {
        $.each($('input[class=cod_fatura]:checked'),function(i,d){
            if(d.value){
                serialize_checkbox += d.value+",";
            }
        });
        document.getElementById('lista_de_faturas').innerHTML = serialize_checkbox;
        //LIMPA OS IMPUTS E MENSAGEM DO MODAL
        $('.a_cancelar_alert').css('display', 'none');
        $('textarea[name=motivo_a_cancelar]').val('');
        $('input[name=faturas_substitutas]').val('');
        $('input[name=senha_a_cancelar]').val('');
        //ABRE O MODAL
        $('#a_cancela_fatura').modal();
    }else if($acao && $acao == 'cancelar') {
        var qtdItens = 0;
        $.each($('input[class=cod_fatura]:checked'),function(i,d){
            if(d.value){
                qtdItens++;
                text+="<br>"+d.value;
                serialize_checkbox+="cod_fatura%5B%5D="+d.value+"&";
            }
        });
        if(qtdItens >= 1){
        document.getElementById('lista_faturas').innerHTML = text;
        $('#cancela_fatura').modal();
        }else{
            alert('Selecione alguma fatura para cancelar!');
        }
    }else if($acao && $acao == 'mesclar_fatura') {
        item_indice_fat = 0;
        faturaAtividade = "";
        var stringItens = [];
        var qtdItens = 0;
        var ids_faturas = [];
        var status_faturas = [];
        var status = true;
        var dataAtual = new Date();
        var dataInserir = dataAtual.toISOString().split('T')[0];
        ids_faturas_a_cancelar = [];

        $('#titulo_editar_fatura').html('Mesclar Faturas');
        
        $.each($('input[class=cod_fatura]:checked'),function(indice,d){
            qtdItens++;
            ids_faturas.push(d.value);
            ids_faturas_a_cancelar.push(d.value);
        });

        if (qtdItens > 1) {
            for (let i = 0; i < ids_faturas.length; i++) {
            
                await $.ajax({
                    url: site_url + '/faturas/status_faturaID',
                    type: "POST",
                    data: {id: ids_faturas[i]},
                    dataType: "json",
                    beforeSend: function () {
                        document.querySelector('#loading').setAttribute('data-content', 'Verificando faturas selecionadas...');
                        document.getElementById('loading').style.display = 'block';
                    },
                    success: function (callback) {
                        if (callback.status == true){
                            status_faturas.push(callback.data);
                            document.getElementById('loading').style.display = 'none';
                        }else{
                            alert(callback.msg);
                            document.getElementById('loading').style.display = 'none';
                        }
                    }
                
                })
            }

            status_faturas.some(function (element) {
                if (element != 0 && element != 2) {
                    status = false;
                    return true;
                }
    
            });

            if (status == true) {
                stopFlag = false;
                /* ids_faturas = [];
                ids_faturas.push(d.value) */
                for (let j = 0; j < ids_faturas.length; j++) {
                    await $.ajax({
                        url: site_url + '/faturas/get_fatura/' + ids_faturas[j],
                        type: "GET",
                        dataType: "json",
                        beforeSend: function () {
                            document.querySelector('#loading').setAttribute('data-content', 'Mesclando itens...');
                            document.getElementById('loading').style.display = 'block';
                        },
                        success: function (callback) {
                            if (callback.success) {
                                //LISTA OS DADOS DA FATURA
                                var fatura = callback.fatura;
                                var itens = callback.itens;
                            
                                Object.keys(fatura).forEach(key => {
                                    if (key == 'status')
                                        $('#' + key + '_fatura').html(fatura[key]);
                                    else
                                        $('#' + key + '_fatura').val(fatura[key]);
                                    
                                });

                                if (!("atividade" in fatura) || fatura.atividade == null) {
                                    fatura.atividade = '0';
                                }
                                
                                if (j > 0) {
                                    if (!("atividade" in fatura) || fatura.atividade != faturaAtividade) {
                                        alert("Não é possível mesclar faturas com atividades diferentes!");
                                        stopFlag = true;
                                        return;
                                        
                                    }
                                } else {
                                    faturaAtividade = fatura.atividade ? fatura.atividade : '';
                                }
                                
                            
                                /* var stringItens = []; */
                                var qtdItens = Object.keys(itens).length;
                            
                                if (qtdItens) {
                                    for (let i = 0; i < qtdItens; i++) {
                                        stringItens.push([
                                            `<div class="col-md-12 divItemFatura${ item_indice_fat }" id="${ item_indice_fat }">
                                                <div class="form-group col-md-4">
                                                    <label>${ lang.descricao }</label>
                                                    <textarea class="form-control itemFatura itemFatura${item_indice_fat}"" data-tipo="descricao_item" id="descricaoFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][descricao_item]" rows="2" placeholder="${lang.descricao_item}" readonly>${itens[i].descricao_item}</textarea>
                                                </div>                                    
                                                <div class="form-group col-md-2">
                                                    <label>${lang.valor}</label>
                                                    <input class="form-control itemFatura itemFatura${item_indice_fat} moeda" id="valorFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][valor_item]" type="text" value="${parseFloat(itens[i].valor_item).toLocaleString('pt-BR')}"  placeholder="0,00" readonly >
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>${lang.tipo_item}</label>                                        
                                                    <select class="form-control itemFatura itemFatura${item_indice_fat} selectItem" data-numero_item="${item_indice_fat}" id="tipo_itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][tipo_item]" style="pointer-events:none" readonly>
                                                        <option value="" disabled selected></option>
                                                        <option value="mensalidade" ${itens[i].tipo_item == "mensalidade" ? "selected" : ""} >${lang.mensalidade}</option>
                                                        <option value="adesao" ${itens[i].tipo_item == "adesao" ? "selected" : ""} >${lang.adesao}</option>
                                                        <option value="taxa" ${itens[i].tipo_item == "taxa" || itens[i].taxa_item == 'sim' ? "selected" : ""} >${lang.taxa}</option>
                                                        <option value="avulso" ${itens[i].tipo_item == "avulso" ? "selected" : ""} >${lang.outros}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2" id="divContratoItemFatura${ item_indice_fat}" ${itens[i].relid_item == 0 ? 'style="display: none"' : 'style="display: block"' } >
                                                    <div>
                                                        <label>${lang.contrato}</label>
                                                        <input class="form-control contrato itemFatura itemFatura${item_indice_fat}"" data-tipo="contrato_item" id="contrato${item_indice_fat}" name="itensFatura[${item_indice_fat}][relid_item]" value="${itens[i].relid_item }"  type="number" readonly >
                                                    </div>
                                                </div>
                                                <div class="col-md-2" id="divTaxaItemFatura${ item_indice_fat}" ${ itens[i].taxa_item == 'nao' ? 'style="display: none"' : 'style="display: block"' } >
                                                    <div class="form-group">
                                                        <label>${lang.tipo_taxa}</label>
                                                        <select class="form-control check-itemFatura${ item_indice_fat} itemFatura itemFatura${item_indice_fat}"" data-tipo="tipotaxa_item" id="check-itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat }][tipotaxa_item]" style="pointer-events:none" readonly >
                                                            <option value=""></option>
                                                            <option value="juros" ${ itens[i].tipo_item == "taxa" && itens[i].taxa_item == 'sim' && itens[i].tipotaxa_item === "juros" ? "selected" : "" } >${ lang.juros }</option>
                                                            <option value="boleto" ${ itens[i].tipo_item == "taxa" && itens[i].taxa_item == 'sim' && itens[i].tipotaxa_item === "boleto" ? "selected" : "" } >${ lang.boleto }</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="form-control itemFatura itemFatura${item_indice_fat}"" data-tipo="id_item" id="id_itemFatura${item_indice_fat}" name="itensFatura[${item_indice_fat}][id_item]"  value="${itens[i].id_item}" readonly>
                                        
                                                <button type="button" data-indice="${ item_indice_fat}" title="${lang.editar}" class="editItemFatura editItemFatura${item_indice_fat } btn btn-primary btn-mini" style="margin-top: 20px"><i class="fa fa-edit"></i></button>
                                                <button type="button" data-indice="${ item_indice_fat }" title="${ lang.remover }" class="removerItemFatura removerItemFatura${ item_indice_fat } btn btn-danger btn-mini" style="margin-top: 20px"><i class="fa fa-remove"></i></button>
                                            </div>`]);
                                        
                                        item_indice_fat++;
                                    }
                                
                                    $('#itensDaFatura').html(stringItens.join(''));
                                }
                            
                                
                                $('#data_emissao_fatura').val(dataInserir);
                                $('#data_vencimento_fatura').attr('disabled', false);
                                $('#data_vencimento_fatura').val('');
                                $('#dataatualizado_fatura_fatura').val('');
                                $('#dataatualizado_fatura_fatura').hide();
                                $('#labelDataAtulizado').hide();
                                $('#chave_nfe_fatura').hide();
                                $('#labelChaveNfe').hide();
                                $('#statusFatura').hide();
                                $('#divStatusFatura').hide();
                                $('#divVencAtualizado').hide();
                                $('#divChaveNfe').hide();
                                $('#periodo_inicial_fatura').attr('disabled', false);
                                $('#periodo_inicial_fatura').val('');
                                $('#periodo_final_fatura').attr('disabled', false);
                                $('#periodo_final_fatura').val('');
                                $('#mes_referencia_fatura').val('');
                                $('#status_fatura').hide();
                                $('#divMotivoEdicao').hide();
                                $('#btnFormEditarFatura').html('Mesclar');
                                $('#nota_fiscal_fatura').val('');
                                $('#motivo_edicao').attr('required', false);
                                $('#forma_pagamento').val('');
                                $('#divFormaPagamento').show();
                                $('#forma_pagamento').attr('required', true);
                            
                                //CARREGA OS VALORES DA FATURA
                                calculaValoresFatura();
                            
                                if (j === ids_faturas.length - 1) {
                                    $("#modalEditarFatura").modal('show');
                                }
                                
                            
                            } else {
                                alert(callback.msg);
                            }
                        },
                        error: function () {
                            alert(lang.operacao_nao_finalizada);
                        },
                        complete: function () {
                            document.getElementById('loading').style.display = 'none';
                        }
                    });

                    if (stopFlag) {
                        break;
                    }
                }
            }else{
                alert('Somente é possível mesclar faturas com status a pagar, não enviado e atrasado!');
            }

        }else{
            alert('Selecione mais de uma fatura para realizar a mesclagem!');
        }

    }
}

$('#modalEditarFatura').on('hidden.bs.modal', function () {
    $('#data_emissao_fatura').val('');
    $('#data_vencimento_fatura').attr('disabled', true);
    $('#data_vencimento_fatura').val('');
    $('#dataatualizado_fatura_fatura').val('');
    $('#dataatualizado_fatura_fatura').show();
    $('#labelDataAtulizado').show();
    $('#chave_nfe_fatura').show();
    $('#labelChaveNfe').show();
    $('#statusFatura').show();
    $('#divStatusFatura').show();
    $('#divVencAtualizado').show();
    $('#divChaveNfe').show();
    $('#periodo_inicial_fatura').attr('disabled', false);
    $('#periodo_final_fatura').attr('disabled', false);
    $('#status_fatura').show();
    $('#divMotivoEdicao').show();
    $('#btnFormEditarFatura').html('Editar');
    $('#nota_fiscal_fatura').val('');
    $('#motivo_edicao').attr('required', true);
    $('#forma_pagamento').val('');
    $('#divFormaPagamento').hide();
    $('#forma_pagamento').attr('required', false);
})

var janelaFaturas = null;
async function imprimir_fatura(fatura)
{
    let http = new XMLHttpRequest();
    let url = site_url+"/faturas/imprimir_fatura/"+fatura;

    http.open("GET", url, false);

    http.send(null);

    let html = "<style>@media print {@page { margin-top: 0cm;margin-bottom: 0cm; size: auto;}}</style>";
    html += `<center>
                <button id='AlterarFaturas' class='btn btn-default' >
                    Alterar Visualização Faturas
                </button>
            </center>`;

    html += `<script>
                document.getElementById("AlterarFaturas").addEventListener("click", function() { 
                    
                    var divPDF = document.querySelectorAll('div.divPDF');
                    var divIMG = document.querySelectorAll('div.divIMG');

                    divPDF.forEach((item) => {
                        item.hasAttribute("hidden") ? item.removeAttribute("hidden") : item.setAttribute("hidden", "");
                    });
                    
                    divIMG.forEach((item) => {
                        item.hasAttribute("hidden") ? item.removeAttribute("hidden") : item.setAttribute("hidden", "");
                    });

                });
            </script>`;

    if(janelaFaturas == null || janelaFaturas.closed) {
        janelaFaturas = window.open(fatura, fatura);
        janelaFaturas.document.write(html+http.responseText);
    } else {
        janelaFaturas.document.write(http.responseText);
    };
}

async function imprimir_faturas(){
    let faturas = [];
    if(!$('input[name="cod_fatura[]"]:checked').serialize()){
        alert('Nenhuma Fatura selecionada.');
        return false;

    }else {
        $('input[name="cod_fatura[]"]:checked').each(async function(){
            faturas.push($(this).val());
        });
        //CARREGA OS DADOS DAS FATURAS
        $.ajax({
            type: "post",
            data: {faturas: faturas},
            url: site_url+'/faturas/ajaxListaFaturasPorGrupoId',
            dataType: "json",
            success: function(data){
                if (data.success) {

                    let fats = data.faturas;
                    let fatsVencidas = '';
                    let fatsStatus = '';
                    //VERIFICA OS VENCIMENTO SE ESTAO ATUALIZADOS
                    fats.forEach((fat, i) => {

                        var vencimento = new Date(fat.data_vencimento+' 23:59:59');
                        var emissao = new Date(fat.data_emissao+' 23:59:59');
                        var dataAtualizado = new Date(fat.dataatualizado_fatura ? fat.dataatualizado_fatura+' 23:59:59': fat.data_vencimento+' 23:59:59');
                        var hoje = new Date();

                        if (fat.status != 0 && fat.status != 2) {
                            fatsStatus += fat.Id + ', ' ;
                        }

                        if (emissao > dataAtualizado) {
                            fatsVencidas += fat.Id + ', ';

                        }else if (dataAtualizado < hoje) {
                            fatsVencidas += fat.Id + ', ' ;

                        }

                    });

                    if (fatsStatus !== '') {
                        alert('Atencão, \nfatura(s): paga(s), a cancelar e cancelada(s), não é permitida a impressão, verifique-a(s): '+fatsStatus+' e tente novamente!');

                    }else if (fatsVencidas !== '') {
                        alert('A(s) fatura(s): '+fatsVencidas+' esta(ão) vencida(s), Atualize-a(s) e tente novamente!');

                    }else {
                        //IMPRIMI AS FATURAS
                        $('input[name="cod_fatura[]"]:checked').each(async function(){
                            let fatura = $(this).val();
                            await imprimir_fatura(fatura);
                            nEnviado_aPagar(fatura);
                        });
                    }
                }
            }
        });
    }
    return false;
}


function nEnviado_aPagar(id){
    if ($('.status_fatura_'+id).text() == 'f_abertoNão enviado') {
        $('.status_fatura_'+id).html(
            '<span class="hidden">f_aberto</span>'+
            '<span class="label label-warning">A pagar</span>');
    }
}

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);
    var dropdownDiv = $('#' + dropdownId).parent();
   
    if (dropdown.is(':visible')) {
        dropdownDiv.removeClass('open');
        return;
    }
 
    $(".dropdown").removeClass('open');
 
    dropdownDiv.addClass('open');
    var posDropdown = dropdown.height() + 4;

    var dropdownItems = $('#' + dropdownId + ' .dropdown-item-acoes');
    var alturaDrop = 0;
    for (var i=0; i <= dropdownItems.length;i++) {
        alturaDrop += dropdownItems.height();
    }
 
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)){
            dropdown.css('top', `-${alturaDrop - 60}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(alturaDrop - 50) - (diferenca + diferenca * 0.5) }px`);
        }
    } 
    
    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdownDiv.removeClass('open');
        }
    });

    $(document).on('contextmenu', function () {
        dropdownDiv.removeClass('open');
    });
}

function abrirDropdownModalNew(dropdownId, buttonId, tableId) {

    var dropdown = $('#' + dropdownId);
    var posDropdown = dropdown.height() + 4;
 
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)){
            dropdown.css('top', `-${posDropdown - 40}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(posDropdown - 40) - (diferenca) }px`);
        }
    }
}

$(document).ready(function() {
    $('#anexo_nf').change(function() {
        if (this.files.length > 0) {
            var fileSize = this.files[0].size; // Tamanho do arquivo em bytes
            var maxSize = 2 * 1024 * 1024; // 2MB em bytes

            if (fileSize > maxSize) {
                $('#fileSizeError').show();
                $(this).val(''); // Limpa o campo de arquivo selecionado
            } else {
                $('#fileSizeError').hide();
            }
        } else {
            $('#fileSizeError').hide();
        }
        
    });

    $('#anexoNFEdit').change(function() {
        if (this.files.length > 0) {
            var fileSize = this.files[0].size; // Tamanho do arquivo em bytes
            var maxSize = 2 * 1024 * 1024; // 2MB em bytes

            if (fileSize > maxSize) {
                $('#fileSizeErrorEdit').show();
                $(this).val(''); // Limpa o campo de arquivo selecionado
            } else {
                $('#fileSizeErrorEdit').hide();
            }
        } else {
            $('#fileSizeErrorEdit').hide();
        }
    });
});

var localeText = AG_GRID_LOCALE_PT_BR;
var AgGrid;
var anexarNFController = site_url + "/faturas/uploadNF";
var atualizarNFController = site_url + "/faturas/editNF";
var listarAnexosController = site_url + "/faturas/listarAnexosNF";
var deleteAnexoController = site_url + "/faturas/excluirAnexosNF";

$(document).ready(function(){
    $(document).on('click', '.visualizarAnexosNF', function(e){
        e.preventDefault();
        
        var botao = $(this);
        var idFatura = botao.attr('data-id');

        visualizarAnexos(idFatura, botao);
    });

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = "tableAnexos";
        abrirDropdownModalNew(dropdownId, buttonId, tableId);
    });

    $('#editar_anexo').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });

    $('#formUploadNF').submit(function(e){
        e.preventDefault();
        
        var formData = new FormData($(this)[0]);

        var idFatura = $('#id_fatura_nf').val();
        
        // Envie a requisição AJAX
        $.ajax({
            url: anexarNFController,
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 
            beforeSend: function () {
                $('#sendAnexoNF').html('<i class="fa fa-spinner fa-spin"></i> Enviando...').attr('disabled', true);
            },
            success: function(response){
                try {
                    var response = JSON.parse(response);
                    if (response.status == 201) {
                        alert('Envio realizado com sucesso!');
                        $('#formUploadNF')[0].reset();
                        AgGrid.gridOptions.api.setRowData([]);
                        AgGrid.gridOptions.api.showLoadingOverlay();
                        listarAnexos(idFatura, function (data, error, message) {
                            if (error) {
                                alert(message);
                            } else {
                                atualizarAgGridAnexos(data);
                            }
                            AgGrid.gridOptions.api.hideOverlay();
                        })
                    } else if (response.status == 400 || response.status == 404) {
                        if (response.resultado && 'mensagem' in response.resultado) {
                            alert(response.resultado.mensagem);
                        } else if ('mensagem' in response) {
                            alert(response.mensagem);
                        } else {
                            alert('Erro ao enviar anexo!');
                        }
                    } else {
                        alert('Erro ao enviar anexo!');
                    }
                } catch (e) {
                    AgGrid.gridOptions.api.hideOverlay();
                    alert('Erro ao tentar exibir resposta! Entre em contato com o suporte técnico!');
                }
            },
            error: function(xhr, status, error){
                alert('Erro ao enviar anexo!');
            },
            complete: function () {
                $('#sendAnexoNF').html('Enviar').attr('disabled', false);
            }
        });
    });

    $('#formEditNF').submit(function(e){
        e.preventDefault();
        
        var formData = new FormData($(this)[0]);

        var idFatura = $('#id_fatura_edit').val();
        
        // Envie a requisição AJAX
        $.ajax({
            url: atualizarNFController,
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 
            beforeSend: function () {
                ShowLoadingScreen();
                $('#editAnexoNF').html('<i class="fa fa-spinner fa-spin"></i> Enviando...').attr('disabled', true);
            },
            success: function(response){
                try {
                    var response = JSON.parse(response);
                    if (response.status == 200) {
                        alert('Anexo atualizado com sucesso!');
                        $('#formEditNF')[0].reset();
                        $('#editar_anexo').modal('hide');
                        AgGrid.gridOptions.api.setRowData([]);
                        AgGrid.gridOptions.api.showLoadingOverlay();
                        listarAnexos(idFatura, function (data, error, message) {
                            if (error) {
                                alert(message);
                            } else {
                                atualizarAgGridAnexos(data);
                            }
                            AgGrid.gridOptions.api.hideOverlay();
                        })
                    } else if (response.status == 400 || response.status == 404) {
                        if (response.resultado && 'mensagem' in response.resultado) {
                            alert(response.resultado.mensagem);
                        } else if ('mensagem' in response) {
                            alert(response.mensagem);
                        } else {
                            alert('Erro ao enviar anexo!');
                        }
                    } else {
                        alert('Erro ao enviar anexo!');
                    }
                } catch (e) {
                    AgGrid.gridOptions.api.hideOverlay();
                    alert('Erro ao tentar exibir resposta! Entre em contato com o suporte técnico!');
                }
            },
            error: function(xhr, status, error){
                alert('Erro ao enviar anexo!');
            },
            complete: function () {
                HideLoadingScreen();
                $('#editAnexoNF').html('Enviar').attr('disabled', false);
            }
        });
    });
});

function visualizarAnexos(faturaId, botao) {
    ShowLoadingScreen();
    botao.html(`<i class="fa fa-spinner fa-spin"></i> ${lang.notas_fiscais}`).attr('disabled', true);
    $('#id_fatura_nf').val('');
    $('#descricaoNF').val('');
    $('#anexo_nf').val('');
    $('#fileSizeError').hide();
    listarAnexos(faturaId, function (data, error, message) {
        if (error) {
            alert(message);
        } else {
            atualizarAgGridAnexos(data);
            $('#id_fatura_nf').val(faturaId);
            $('#visualizar_anexo').modal('show');
        }
        botao.html(`<i class="fa fa-file-pdf-o"></i> ${lang.notas_fiscais}`).attr('disabled', false);
        HideLoadingScreen();
    })
    
}

function listarAnexos(faturaId, callback) {
    $.ajax({
        url: listarAnexosController + '/' + faturaId,
        type: 'GET',
        data: {
            faturaId: faturaId,
        },
        dataType: 'json',
        success: function(response){
            if (response.status == 200) {
                if ("anexos" in response && response.anexos) {
                    if (typeof callback === 'function') { callback(response.anexos, false, 'Sucesso!'); }
                } else {
                    if (typeof callback === 'function') { callback([], false, 'Fatura não possui anexo'); }
                }
            } else if (response.status == 404) {
                if (typeof callback === 'function') { callback([], false, response.results.mensagem); }
            } else {
                if (response.results && 'mensagem' in response.results && response.status != 500) {
                    if (typeof callback === 'function') { callback([], true, response.results.mensagem); }
                } else {
                    if (typeof callback === 'function') { callback([], true, 'Erro ao listar anexos!'); }
                }
            } 
        },
        error: function(xhr, status, error){
            if (typeof callback === 'function') { callback([], true, 'Erro ao listar anexos!'); }
        }
    })
}

function deleteAnexo(idNF, idFatura) {
    if (confirm('Você tem certeza que deseja remover esse anexo?')) {
        $.ajax({
            url: deleteAnexoController + '/' + idNF,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                ShowLoadingScreen();
            },
            success: function(response){
                var dados = response.resultado;
                if (response.status == 200) {
                    alert('Anexo removido com sucesso!');
                    AgGrid.gridOptions.api.setRowData([]);
                    AgGrid.gridOptions.api.showLoadingOverlay();
                    listarAnexos(idFatura, function (data, error, message) {
                        if (error) {
                            alert(message);
                        } else {
                            atualizarAgGridAnexos(data);
                        }
                        AgGrid.gridOptions.api.hideOverlay();
                    })
                } else if ('mensagem' in dados && response.status != 500) {
                    alert(dados.mensagem);
                } else {
                    alert('Erro ao remover anexo!');
                } 
            },
            error: function(xhr, status, error){
                alert('Erro ao remover anexo!');
            },
            complete: function () {
                HideLoadingScreen();
            }
        })
    }
}

function abrirAnexo(arquivo) {
    var pdfByteArray = arquivo;

    var byteCharacters = atob(pdfByteArray);
    var byteNumbers = new Array(byteCharacters.length);
    for (var i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    var byteArray = new Uint8Array(byteNumbers);
    var blob = new Blob([byteArray], { type: 'application/pdf' });

    var url = URL.createObjectURL(blob);

    var newWindow = window.open(url, '_blank');
    
    if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
        alert('A janela pop-up foi bloqueada pelo seu navegador. Habilite pop-ups para ver o PDF.');
    }
}

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
}

function editarAnexo(id, descricao, idFatura) {
    $('#id_nf').val(id);
    $('#descricaoNFEdit').val(descricao);
    $('#id_fatura_edit').val(idFatura);
    $('#anexoNFEdit').val("");
    $('#fileSizeErrorEdit').hide();
    $('#editar_anexo').modal('show');
}

function atualizarAgGridAnexos(dados) {
    stopAgGRID();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Nome',
                field: 'descricao',
                flex: 1,
                minWidth: 200,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Data de Criação',
                field: 'data_created',
                width: 172,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value) {
                        return formatDateTime(options.value);
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: 'Data de Atualização',
                field: 'data_updated',
                width: 200,
                sort: 'desc',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value) {
                        return formatDateTime(options.value);
                    } else {
                        return '';
                    }
                }
            },
            { 
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                suppressMenu: true,
                sortable: false,
                filter: false,
                resizable: false,
                cellRenderer: function (options) {
                    let data = options.data;

                    let tableId = "tableAnexos";
                    let dropdownId = "dropdown-menu" + data.id;
                    let buttonId = "dropdownMenuButton_" + data.id;
                    if (data.id) {
                        return `
                            <div class="dropdown dropdown-table">
                                <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:abrirAnexo('${data.arquivo}')" style="cursor: pointer; color: black;">Visualizar</a>
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:editarAnexo('${data.id}', '${data.descricao}', '${data.id_fatura}')" style="cursor: pointer; color: black;">Editar</a>
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:deleteAnexo('${data.id}', '${data.id_fatura}')" style="cursor: pointer; color: black;">Remover</a>
                                    </div>
                                </div>
                            </div>`;
                    } else {
                        return '';
                    }
                },
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            filter: true,
            resizable: true,
            suppressMenu: false,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                    },
                },
            ],
            defaultToolPanel: false,
        },
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: 5,
        localeText: localeText,
    }

    var gridDiv = document.querySelector('#tableAnexos');
    gridDiv.style.setProperty('height', '310px');
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#tableAnexos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperAnexos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableAnexos" class="ag-theme-alpine my-grid"></div>';
    }
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function countAnexo(fatura) {
    $('.alerta').html("");
    $('.listAnexo').html("");
    $('#id_fatura').val(fatura);
    $.ajax({
        type: "post",
        data: {id: fatura},
        url: controller1,
        dataType: "json",
        success: function(data){
            $('#countAnexo').html(data);
        }
    });
    $.ajax({
        type: "post",
        data: {id: fatura},
        url: controller2,
        dataType: "JSON",
        success: function(data){
            $.each(data, function (i, arquivo) {
                $('.listAnexo').append("<li><a class='linkAnexo' href="+url+encodeURI(arquivo.file)+" target='_blank'><i class='fa fa-file'></i> " +(i+1)+" - "+arquivo.file+"</a></li><hr>");
            });
        }
    });
};
