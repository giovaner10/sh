$(document).ready(function () {
    var mensagensTicket, linhaTabelaTicket;

    if ($.fn.DataTable.isDataTable('#tableTickets')) {
        tableTickets.destroy();
    }

    $('#tableTickets').hide();

    // Adiciona o símbolo de loading

    // TABELA DE TICKETS
    tableTickets = $('#tableTickets').DataTable({
        initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                $('#tableTickets').show(); // Exibe a tabela quando a inicialização estiver concluída

            },
        dom: 'Bfrtip',
        language: langDatatable,
        paging: true,
        searching: true,
        info: false,
        processing: true,
        responsive: true,
        columnDefs: [
            {
                className: 'dt-center',
                targets: '_all'
            }
        ],
        order: [[0, 'desc']],
        columns: [
            {data: 'id'},
            {data: 'nome_usuario'},
            {data: 'usuario'},
            {data: 'placa'},
            {data: 'departamento'},
            {data: 'assunto'},
            {data: 'responsavel'},
            {data: 'ultima_interacao'},
            {data: 'status'},
            {
                data: 'visualizar',
                render: function(data, type, row, meta){
                    return '<a onclick="return false;" class="viewTicket btn btn-mini btn-primary" title="'+lang.visualizar+'"><i class="fa fa-eye"></i></a>';
                }
            }
        ],

        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF'
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                },
                title: lang.tick,
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR'
            }
        ],
        language: lang.datatable
    });

});




$('.addTicket').on('click', function (e) {
    e.preventDefault();
    //LIMPA O MODAL
    limparModalNovoTickt();

    //CARREGA O SELECT DE USUARIOS
    $('.t_select_usuario').select2({
        ajax: {
            url: site_url+'/usuarios_gestor/get_ajax_usuarios_gestores',
            dataType: 'json',
            type: 'get',
            data: { id_cliente: id_cliente }
        },
        placeholder: lang.selecione_usuario,
        allowClear: true
    });

    //preenche as opcoes do select de placas
    $('.t_select_usuario').on('select2:select', function (e) {
        e.preventDefault();
        var id_usuario = $(this).val();
        var str = "";
        if (informacoes === 'SIMM2M') {
            $.ajax({
                url: site_url+'/linhas/listaLinhasCliente',
                datatype: 'json',
                type: 'post',
                data: {id_usuario: id_usuario},
                success: function (data) {
                    var res = JSON.parse(data);
                    if (res.status == 'OK') {
                        $("#t_input_id_cliente")[0].value = id_cliente;
                        $("#t_input_usuario")[0].value = res.results[0].usuario;
                        $("#t_input_nome_usuario")[0].value = res.results[0].nome_usuario;
                        str += '<option value="" disabled selected></option>';
                        $(res.results).each(function (index, value) {
                          str += '<option value=' + value.numero + '>' + value.numero + '</option>';
                        })
                        $('#t_placa').html(str);

                        window.setTimeout(function(){
                            document.getElementById('t_placa').disabled = false;
                        }, 1000);
                    }
                },
                error: function (error) {
                }
            });

        }else {
            $.ajax({
                url: site_url+'/veiculos/lista_placas_usuario/'+id_usuario,
                datatype: 'json',
                success: function (data) {
                    var res = JSON.parse(data);
                    if (res.status == 'OK') {
                        $("#t_input_id_cliente")[0].value = res.results[0].id_cliente;
                        $("#t_input_usuario")[0].value = res.results[0].usuario;
                        $("#t_input_nome_usuario")[0].value = res.results[0].nome_usuario;
                        str += '<option value="" disabled selected></option>';
                        $(res.results).each(function (index, value) {
                          str += '<option value=' + value.placa + '>' + value.placa + '</option>';
                        })
                        $('#t_placa').html(str);

                        window.setTimeout(function(){
                            document.getElementById('t_placa').disabled = false;
                        }, 1000);
                    }
                },
                error: function (error) {
                }
            });
        }

    });


    //PREENCHE AS OPCOES DO SELECT DE DEPARTAMENTO
    var departamentos = "";
    $.ajax({
        url: site_url+'/crm_assuntos/listar_assuntos',
        datatype: 'json',
        success: function (data) {
            var res = JSON.parse(data);
            if (res.status == 'OK') {
                departamentos += '<option value="" disabled selected></option>';
                $(res.results).each(function (index, value) {
                  departamentos += '<option value=' + value.id_assunto +'>' + value.assunto + '</option>';
                })
                $('#t_departamento').html(departamentos);
            }
        },
        error: function (error) {
        }
    });

});

//Cria novo ticket
$("#formNovoTicket").submit(function(e) {
    e.preventDefault();
    var botao = $("#salvar_ticket");
    var formdata = new FormData($("#formNovoTicket")[0]);

    $.ajax({
        url: site_url+'/webdesk/new_ticket/'+informacoes,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: 'json',
        data: formdata,
        beforeSend:function(){
            botao.attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> '+lang.criando_ticket);
        },
        success: function(callback) {
            if (callback.success){
                var dados = callback.dados;

                var id = callback.idTicket;
                var nome_usuario = dados.usuario;
                var usuario = dados.nome_usuario;
                var placa = dados.placa;
                var departamento = dados.departamento;
                var assunto = dados.assunto;
                var responsavel = ' ';
                var ultima_interacao = dados.ultima_interacao;
                var status = dados.status;

                tableTickets.rows.add(
                    [{
                        id,
                        nome_usuario,
                        usuario,
                        placa,
                        departamento,
                        assunto,
                        responsavel,
                        ultima_interacao,
                        status
                    }]
                ).draw(null, false);
                $("#mensagem_ticket_alert").html('<div class="alert alert-success"><p><b>'+callback.mensagem+'</b></p></div>');

            }else {
                $("#mensagem_ticket_alert").html('<div class="alert alert-danger"><p><b>'+callback.mensagem+'</b></p></div>');
            }
        },
        error: function(callback) {
            $("#mensagem_ticket_alert").html('<div class="alert alert-danger"><p><b>'+lang.ticket_nao_salvo+'</b></p></div>');
        },
        complete: function(callback){
            //mostra a mensagem de retorno
            $('.novo_ticket_alert').css('display', 'block');
            botao.html(lang.adicionar).removeAttr('disabled');
        }
    });
});

//FILTRA O CONTEUDO DA TABELA PELO STATUS SELECIONADO
$(document).on('click', '.statusTickets', function () {
    var status = $(this).attr('data-status');
    if (status !== 'todos')
        $('#tableTickets').DataTable().column(8).search(status).draw();
    else
        $('#tableTickets').DataTable().column(8).search('').draw();
});


//LIMPA O SELECT2 DO FORMULARIO 'NOVO TICKET'
function limparModalNovoTickt(){
    $('.t_select_usuario').val('').trigger('change');
    $('.novo_ticket_alert').css('display', 'none');
    $('#formNovoTicket')[0].reset();
}

//LIMITA O NUMERO DE CARACTERES NO INPUT DESCREICAO
$(function() {
    var max_ant = 0;
    $(".t_maxlength").keyup(function(event) {

        var target = $("#content-countdown");
        var max = target.attr('title');
        var len = $(this).val().length;
        var remain = len;

        if (len > max && len > max_ant) {
            $("#t_descricao_ticket").css("color", "red");
            var tpl = [
                '<div class="alert alert-info">',
                '<button type="button" class="close" data-dismiss="alert">&times;</button>',
                '<strong>Ops! </strong>',
                lang.usar_500_caracteres,
                '</div>'
            ].join('');

            $(".t_msg_caracter").html(tpl);
            $(".t_msg_caracter").show();
            max_ant = len;

        } else if (len <= max && len < max_ant) {
            $("#t_descricao_ticket").css("color", "black");
            max_ant = len;
        }
        target.html(remain);
    });
});

// VISUALIZAR TICKET
$(document).on('click', '.viewTicket', function(e) {
    e.preventDefault();
    var botao = $(this);

    //PEGA OS DADOS DA LINHA CLICADA
    linhaTabelaTicket = tableTickets.row($(this).closest('tr'));
    var id_ticket = linhaTabelaTicket.data().id;

    //LIMPA OS DADOS NO MODAL
    fecharMensagem('resposta_ticket_alert');
    $('#formRespotaTicket')[0].reset();
    $('#divFormComent').css('display', 'none');
    $('#divReabrirTicket').css('display', 'none');
    $('#assuntoTicket').html('');
    $('#dataTicket').html('');

    $("#tituloVerTicket").html(id_ticket);
    $('#modalViewTicket').modal();

    //INICIALIZA A TABELA DE MENSAGENS DO TICKET
    mensagensTicket = $('#mensagensTicket').DataTable({
        destroy: true,
        responsive: true,
        searching: false,
        paging: false,
        ordering: false,
        info: false,
        lengthChange: false,
        columns: [
            {data: 'msn'}
        ]
    });

    $.ajax({
       url: site_url+'/webdesk/ajaxLoadTicket/'+id_ticket+'/'+informacoes,
       dataType: 'json',
       beforeSend:function(){
           // criamos o loading
           $('#mensagensTicket > tbody').html(
             '<tr class="odd">' +
               '<td valign="top" colspan="12" class="dataTables_empty">'+lang.carregando+'</td>' +
             '</tr>'
           );
       },
       success: function(callback) {
            if (callback.status){
                var id = callback.dados['id'];
                var status = callback.dados['status'];
                var status_anterior = callback.dados['status_anterior'];
                var assunto = callback.dados['assunto'];
                var dataAbertura = callback.dados['dataAbertura'];
                var coment_trello = callback.dados['coment_trello'];
                var mensagens = callback.dados['mensagens'];

                if (status !== '3') {
                    $('#divFormComent').css('display', 'block');
                }else {
                    $('#divReabrirTicket').css('display', 'block');
                }

                $('#assuntoTicket').html(assunto);
                $('#dataTicket').html(dataAbertura);
                $('#coment_trello').val(coment_trello);
                $('.fechar_ticket').attr('data-href', site_url+'/webdesk/ajaxFecharTicket/'+id+'/'+status);
                $('.reabrirTicket').attr('data-href', site_url+'/webdesk/ajaxReabrirTicket/'+id+'/'+status_anterior);
                $('.salvar_resposta_ticket').attr('data-href', site_url+'/webdesk/ajaxEnviarResposta/'+id+'/'+status_anterior);
                $('#idClienteRespostaTicket').val(id_cliente);

                // Atualiza Tabela
                mensagensTicket.clear();
                mensagensTicket.rows.add(mensagens);
                mensagensTicket.draw();

            }else {
                mensagensTicket.clear();
                mensagensTicket.draw();
            }
       },
       error: function(callback) {
           //mostra a mensagem de retorno
           $('.resposta_ticket_alert').css('display', 'block');
           $("#mensagem_resposta_alert").html('<div class="alert alert-danger"><p><b>'+callback.mensagem+'</b></p></div>');
       }
   });

});

//SALVAR RESPOSTA
$("#formRespotaTicket").submit(function(e) {
    e.preventDefault();

    var botao = $(".salvar_resposta_ticket");
    var url = botao.attr('data-href');
    var formdata = new FormData($("#formRespotaTicket")[0]);

    $.ajax({
        url: url,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: 'json',
        data: formdata,
        beforeSend:function(){
            botao.attr("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> "+lang.adicionando);
        },
        success: function(callback) {
            if (callback.status){
                var msn = callback.dados.msn;
                //ADICIONA A LINHA NOVA NA TABELA
                mensagensTicket.rows.add(
                    [{ msn }]
                ).draw(null, false);
                //MUDA OS DADOS DO TICKET NA TABELA DE TICKETS
                linhaTabelaTicket.data().responsavel = lang.suporte;
                linhaTabelaTicket.data().ultima_interacao = formataDataHora();
                linhaTabelaTicket.data().status = '<span class="label label-info">'+lang.respondido_em+'</span><span class="hidden">t_andamento</span><p style="color: #808080; font-weight: bold; font-size:12px;">00m e 02s</p>';
                tableTickets.row( linhaTabelaTicket.index() ).data( linhaTabelaTicket.data() ).draw();

            }else {
                $("#mensagem_resposta_alert").html('<div class="alert alert-danger"><p><b>'+callback.mensagem+'</b></p></div>');
                $('.resposta_ticket_alert').css('display', 'block');
            }
        },
        error: function(callback) {
            $("#mensagem_resposta_alert").html('<div class="alert alert-danger"><p><b>'+lang.tente_mais_tarde+'</b></p></div>');
            $('.resposta_ticket_alert').css('display', 'block');
        },
        complete: function(callback){
            //mostra a mensagem de retorno
            botao.html(lang.adicionar).removeAttr('disabled');
        }
    });

});

/*
* FECHAR TICKET
*/
$('.fechar_ticket').on('click', function (e) {
    e.preventDefault();

    var botao = $(this);
    var url = botao.attr('data-href');
    $.ajax({
        url: url,
        dataType: 'json',
        beforeSend:function(){
            botao.attr("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> "+lang.fechando);
        },
        success: function(callback) {
            if (callback.success){
                $("#mensagem_resposta_alert").html('<div class="alert alert-success"><p><b>'+callback.msg+'</b></p></div>');
                //ESCONDE A DIV DE COMENTARIOS E MOSTA O BOTAO DE REABRIR TICKET
                $('#divFormComent').css('display', 'none');
                $('#divReabrirTicket').css('display', 'block');

                //MUDA O STATUS DO TICKET NA TABELA DE TICKETS
                var tempoUltimaInteracao = linhaTabelaTicket.data().status.split('data-ultima_interacao="')[1].split('"')[0];
                linhaTabelaTicket.data().status = '<span class="label label-success" data-ultima_interacao="'+tempoUltimaInteracao+'"><span class="hidden">t_concluido</span>'+lang.concluido+'</span>';
                tableTickets.row( linhaTabelaTicket.index() ).data( linhaTabelaTicket.data() ).draw();
            }else {
                $("#mensagem_resposta_alert").html('<div class="alert alert-danger"><p><b>'+callback.msg+'</b></p></div>');
            }
        },
        error: function(callback) {
            $("#mensagem_resposta_alert").html('<div class="alert alert-danger"><p><b>'+lang.tente_mais_tarde+'</b></p></div>');
        },
        complete: function(callback){
            //mostra a mensagem de retorno
            $('.resposta_ticket_alert').css('display', 'block');
            botao.html(lang.fechar_ticket).removeAttr('disabled');
        }
    });

});

/*
* FECHAR TICKET
*/
$('.reabrirTicket').on('click', function (e) {
    e.preventDefault();

    var botao = $(this);
    var url = botao.attr('data-href');
    $.ajax({
        url: url,
        dataType: 'json',
        beforeSend:function(){
            botao.attr("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> "+lang.reabrindo);
        },
        success: function(callback) {
            if (callback.success){
                $("#mensagem_resposta_alert").html('<div class="alert alert-success"><p><b>'+callback.msg+'</b></p></div>');
                //ESCONDE O BOTAO DE REABRIR TICKET E MOSTA A DIV DE COMENTARIOS
                $('#divFormComent').css('display', 'block');
                $('#divReabrirTicket').css('display', 'none');

                //MUDA O STATUS DO TICKET NA TABELA DE TICKETS
                var tempoUltimaInteracao = linhaTabelaTicket.data().status.split('data-ultima_interacao="')[1].split('"')[0];
                linhaTabelaTicket.data().status = '<span class="label label-info" data-ultima_interacao="'+tempoUltimaInteracao+'">'+lang.respondido_em+'</span><span class="hidden">t_andamento</span><p style="color: #808080; font-weight: bold; font-size: 12px;">'+tempoUltimaInteracao+'</p>';
                tableTickets.row( linhaTabelaTicket.index() ).data( linhaTabelaTicket.data() ).draw();
            }else {
                $("#mensagem_resposta_alert").html('<div class="alert alert-danger"><p><b>'+callback.msg+'</b></p></div>');
            }
        },
        error: function(callback) {
            $("#mensagem_resposta_alert").html('<div class="alert alert-danger"><p><b>'+lang.tente_mais_tarde+'</b></p></div>');
        },
        complete: function(callback){
            //mostra a mensagem de retorno
            $('.resposta_ticket_alert').css('display', 'block');
            botao.html(lang.reabrir_ticket).removeAttr('disabled');
        }
    });

});
