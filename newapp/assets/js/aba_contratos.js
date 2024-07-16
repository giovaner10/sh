
var tableContratos= false;

$(document).ready(function() {


    $('.contratos').on('click', function () {
        if ($.fn.DataTable.isDataTable('#tabelaContratos')) {
            return ;
        }
        tableContratos= $('#tabelaContratos').DataTable({
            order: [ 0, 'desc' ],
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,
            info: false,
            otherOptions: {},
            initComplete: function() {
                // Força a desativação do autocomplete do campo pesquisa
                $('.dataTables_filter input[type="search"]', this.api().table().container()).attr('autocomplete', 'off');
                $(this.api().table().container()).find('input[type="search"]').parent().wrap('<form>').parent().attr('autocomplete', 'off').css('overflow','hidden').css('margin','auto');
            },
            ajax: {
                url: site_url + '/contratos/ajax_load_contratos',
                type: 'POST',
                data: {id_cliente: id_cliente },
                dataType: 'json'
            },
            columns: [
               { data: "contrato" },
               { data: "vendedor" },
               { data: "itens" },
               { data: "itens_ativos" },
               { data: "valor_mensal" },
               { data: "valor_instalacao" },
               { data: "dataFim_aditivo"},
               { data: "status" },
               { data: "digitalizar" },
               { data: "administrar" }
            ],
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
            }
        });

        $.ajax({
            url: site_url + '/contratos/total_itens_ativos',
            type: 'POST',
            data: {id_cliente: id_cliente },
            dataType: 'json',
            success: function(retorno){
                if(retorno.status){
                    $(tableContratos.columns(2).header()).html('Ítens <span class="badge badge-info pull-right">'+retorno.totais.itens+'</span>');
                    $(tableContratos.column(3).header()).html('Ítens Ativos <span class="badge badge-info pull-right">'+retorno.totais.itens_ativos+'</span>');
                    $(tableContratos.column(4).header()).html('Valor Mensal <span class="badge badge-info pull-right">'+retorno.totais.mensalidades+'</span>');
                    $(tableContratos.column(5).header()).html('Valor Instalação  <span class="badge badge-info pull-right">'+retorno.totais.instalacao+'</span>');
                    $(tableContratos.column(6).header()).html('Fim do aditivo  <span class="badge badge-info pull-right">'+retorno.totais.dataFim_aditivo+'</span>');
                    $(tableContratos.column(7).header()).html('Status  <span class="badge badge-info pull-right">'+retorno.totais.contratos_ativos+'</span>');
                }
            }
        });

    });

    //preenche o select edit vendedor
    $(document).on('click', '.modal_vendedor', function(e) {
        e.preventDefault();
        var str = "";
        $.ajax({
            url: site_url + '/usuarios/listar_todos_usuarios',
            datatype: 'json',
            success: function (data) {
                //str += '<option value=""></option>';
                $(JSON.parse(data)).each(function (index, value) {
                    str += '<option value=' + value.id + '>' + value.nome + '</option>';
                })
                $('#Optvendedor').html(str);
                $('#Optvendedor').css('display', 'block');
                $('span.mensagem').css('display', 'none');
                str = "";
            },
            error: function (error) {
            }
        })
    });

    $(document).on('click', '.edit_vend', function(e) {
        e.preventDefault();
        var url_vend = site_url + '/contratos/update_vendedor',
            id_contrato = $('#idContrato_vend').val(),
            vendedor = $('.vendedor option:selected').val();

        $('.placa-alert').css('display', 'block');

        $.ajax({
            url: url_vend,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_contrato : id_contrato,
                id_vendedor : vendedor
            },
            success: function(retorno){
                if (retorno.status == 'OK') {

                    $("#text_vendedor").html($('.vendedor option:selected').text());
                }
                $("#mensagem").html(retorno.mensagem);
                $('#modalVendedor').modal('hide');

            },
            error: function(){
                $("#mensagem").html('Sistema temporariamente indisponivel. Tente novamente mais tarde!');
            }
        })
    });

    $('#myModal_digitalizar').on('hidden', function(){
        $(this).data('modal', null);
    });

    $('#myModal_cancelar_contrato').on('hidden', function(){
        $(this).data('modal', null);
    });

    // paginação cliente
    $(document).on('click', '.pag_cli a', function(ev) {
        ev.preventDefault();

        $('#loading').css('display', 'block');
        var urlPag = $(this).attr('href');

        $('#contratos').load(urlPag, function(result){
            $('#loading').css('display', 'none');
        });
    });

    $('#remove_filtro').click(function(e){
        e.preventDefault();

        $('#loading').css('display', 'block');
        var urlPag = $(this).attr('href');

        $('#veiculos').load(urlPag, function(result){
            $('#loading').css('display', 'none');
        });
    });

    $('#busca_veiculo').submit(function(e){
        e.preventDefault();
        $('#loading').css('display', 'block');
        input = $('input[name=placa]');
        controller = input.attr('data-controller');
        url = controller+'/'+input.val();

        $.post(url, {placa: input.val()}, function(resultado){
            $('#veiculos').html(resultado);
            $('#loading').css('display', 'none');
        });
    });

    $(document).on('click', '.consumoFatura', function(e) {
        e.preventDefault();
        var botao = $(this);
        var id = botao.attr('data-id_contrato');
        var status = botao.attr('data-novostatus');
        botao.html('<i class="fa fa-spinner fa-spin" style="font-size: x-large;"></i>').attr('disabled', true);
        $('.placa-alert').css('display', 'block');

        $.ajax({
            url: site_url + '/contratos/faturar_consumo',
            type: 'POST',
            dataType: 'JSON',
            data: {
                status : status,
                id : id
            },
            success:function(data){
                if(status == 1){
                    botao.removeAttr('disabled')
                    .attr("title","Destivar fatura por disp.")
                    .attr("data-novoStatus", 0)
                    .html("<img src='"+ img_fatura + "' alt='Fatura por disponibilidade' class='ativado' />");
                     $("#mensagem").html('<div class="alert alert-success"><p><b>Fatura por disp. ativada com sucesso!</b></p></div>');
                }else {
                    botao.removeAttr('disabled')
                    .attr("title","Ativar fatura por disp.")
                    .attr("data-novoStatus", 1)
                    .html("<img src='"+ img_fatura + "' alt='Fatura por disponibilidade' class='desativado' />");
                     $("#mensagem").html('<div class="alert alert-success"><p><b>Fatura por disp. desativada com sucesso!</b></p></div>');
                }
            },
            error:function(data){
                $("#mensagem").html('<div class="alert alert-error"><p><b>Não foi possível possível no momento, tente mais tarde!</b></p></div>');
            }
        });
    });

    $(document).on('click', '.taxaBoleto', function(e) {
        e.preventDefault();
        var botao = $(this);
        var id = botao.attr('data-id_contrato');
        var status = botao.attr('data-novostatus');
        botao.html('<i class="fa fa-spinner fa-spin" style="font-size: x-large;"></i>').attr('disabled', true);
        $('.placa-alert').css('display', 'block');

        $.ajax({
            url: site_url + '/contratos/taxa_boleto',
            type: 'POST',
            dataType: 'JSON',
            data: {
                status : status,
                id : id
            },
            success:function(data){
                if(status == 1){
                    botao.removeAttr('disabled')
                    .attr("title","Destivar taxa de boleto")
                    .attr("data-novoStatus", 0)
                    .html("<img src='"+ img_taxa_boleto + "' alt='Taxa de boleto' class='ativado' />");
                     $("#mensagem").html('<div class="alert alert-success"><p><b>Taxa de boleto ativada com sucesso!</b></p></div>');
                }else {
                    botao.removeAttr('disabled')
                    .attr("title","Ativar taxa de boleto")
                    .attr("data-novoStatus", 1)
                    .html("<img src='"+ img_taxa_boleto + "' alt='Taxa de boleto' class='desativado' />");
                     $("#mensagem").html('<div class="alert alert-success"><p><b>Taxa de boleto desativada com sucesso!</b></p></div>');
                }
            },
            error:function(data){
                botao.removeAttr('disabled');
                $('.placa-alert').css('display', 'block');
                $("#mensagem").html('<div class="alert alert-error"><p><b>Não foi possível no momento, tente mais tarde!</b></p></div>');
            }
        });
    });


    $(document).on('click', '.dataFim', function(e) {
        e.preventDefault();
        var id_contrato = $("#input_dataFim_aditivo_contrato")[0].value;
        var dataFim_aditivo = $("#input_dataFim_aditivo")[0].value;
        var botao = $(this);

        botao.html('<i class="fa fa-spinner fa-spin"></i> Processando...').attr('disabled', true);
        $('.placa-alert').css('display', 'block');

        $.ajax({
            url: site_url + '/contratos/update_dataFim_aditivo',
            type: 'POST',
            dataType: 'JSON',
            data: {
                dataFim_aditivo : dataFim_aditivo,
                id_contrato : id_contrato
            },
            success:function(data){
                if (dataFimAtual && dataFimAtual != null) {
                    $("#fim_aditivo_modal").modal('hide');
                    $("#mensagem").html(data.mensagem);
                    $('.dataFim'+id_contrato).attr("data-fim_aditivo", $("#input_dataFim_aditivo")[0].value );
                    
                    botao.addClass('btn btn-success')
                        .text('Salvar')
                        .attr('disabled', false);
                }else {
                    $("#fim_aditivo_modal").modal('hide');
                    $("#mensagem").html(data.mensagem);
                    $('.dataFim'+id_contrato).attr("data-fim_aditivo", $("#input_dataFim_aditivo")[0].value )
                        .html("<img src='"+ img_data_fim + "' alt='Fim do Aditivo' class='ativado' />");

                    botao.addClass('btn btn-success')
                        .text('Salvar')
                        .attr('disabled', false);

                }

            },
            error:function(data){
                $("#fim_aditivo_modal").modal('hide');
                $("#mensagem").html(data.mensagem);
                botao.addClass('btn btn-success')
                .text('Salvar')
                .attr('disabled', false);
            }
        });
    });


    $(document).on('click', '.modalDataFim', function(e) {
        e.preventDefault();
        dataFimAtual = $(this).attr('data-fim_aditivo');
        $("#input_dataFim_aditivo")[0].value = $(this).attr('data-fim_aditivo');
        $("#input_dataFim_aditivo_contrato")[0].value = $(this).attr('data-id_contrato');
        $("#fim_aditivo_modal").modal('show');
    });


});

//funções

function edit_vendedor(contrato) {
    document.getElementById("small_contrato").innerHTML = '#'+contrato;
    document.getElementById("idContrato_vend").value = contrato;
}
