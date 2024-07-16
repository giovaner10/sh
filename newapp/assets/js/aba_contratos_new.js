$(document).ready(function() {
    var tableContratos = false;  //declara a tabela de contratos
    var tableDigi = $('#table-digitalizar').DataTable();  //instancia a tabela de arquivos digitalizados
    // $('.contratos').on('click', function () {

    var contratos = [];
    var totais;

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
        $('.edit_vend').attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>Vinculando...')
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
                    $('.edit_vend').removeAttr('disabled').html('Vincular')
                    $("#text_vendedor").html($('.vendedor option:selected').text());
                    $("#mensagem").html(retorno.mensagem);
                    $('#modalVendedor').modal('hide');
                }else {
                    $('.edit_vend').removeAttr('disabled').html('Vincular')
                    $("#mensagem").html(retorno.mensagem);
                }
            },
            error: function(){
                $('.edit_vend').removeAttr('disabled').html('Vincular')
                $("#mensagem").html('Sistema temporariamente indisponivel. Tente novamente mais tarde!');
            }
        })
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
        var novoStatus = botao.attr('data-novo_status');
        botao.html('<i class="fa fa-spinner fa-spin" style="font-size: x-large;"></i>').attr('disabled', true);
        // $('.placa-alert').css('display', 'block');

        $.ajax({
            url: site_url + '/contratos/faturar_consumo',
            type: 'POST',
            dataType: 'JSON',
            data: {
                status : novoStatus,
                id : id
            },
            success:function(data){
                if (data.status) {
                    if(novoStatus == 1){
                        botao.removeAttr('disabled')
                            .attr("title","Desativar fatura por disp.")
                            .attr("data-novo_status", 0)
                            .html("<img src='"+ img_fatura + "' alt='Fatura por disponibilidade' class='ativado' style='height: 30px' />");
                        // $("#mensagem").html('<div class="alert alert-success"><p><b>Fatura por disp. ativada com sucesso!</b></p></div>');
                    }else {
                        botao.removeAttr('disabled')
                            .attr("title","Ativar fatura por disp.")
                            .attr("data-novo_status", 1)
                            .html("<img src='"+ img_fatura + "' alt='Fatura por disponibilidade' class='desativado' style='height: 30px' />");
                        // $("#mensagem").html('<div class="alert alert-success"><p><b>Fatura por disp. desativada com sucesso!</b></p></div>');
                    }

                }else {
                    $('.placa-alert').css('display', 'block');
                    $("#mensagem").html('<div class="alert alert-success"><p><b>'+data.msn+'</b></p></div>');
                }
            },
            error:function(data){
                $('.placa-alert').css('display', 'block');
                $("#mensagem").html('<div class="alert alert-error"><p><b>Não foi possível possível no momento, tente mais tarde!</b></p></div>');
            }
        });
    });

    $(document).on('click', '.taxaBoleto', function(e) {
        e.preventDefault();
        var botao = $(this);
        var id = botao.attr('data-id_contrato');
        var novoStatus = botao.attr('data-novo_status');
        botao.html('<i class="fa fa-spinner fa-spin" style="font-size: x-large;"></i>').attr('disabled', true);
        // $('.placa-alert').css('display', 'block');

        $.ajax({
            url: site_url + '/contratos/taxa_boleto',
            type: 'POST',
            dataType: 'JSON',
            data: {
                status : novoStatus,
                id : id
            },
            success:function(data){
                if (data.status) {
                    if(novoStatus == 1){
                        botao.removeAttr('disabled')
                            .attr("title","Desativar taxa de boleto")
                            .attr("data-novo_status", 0)
                            .html("<img src='"+ img_taxa_boleto + "' alt='Taxa de boleto' class='ativado' style='height: 30px' />");
                        // $("#mensagem").html('<div class="alert alert-success"><p><b>Taxa de boleto ativada com sucesso!</b></p></div>');
                    }else {
                        botao.removeAttr('disabled')
                            .attr("title","Ativar taxa de boleto")
                            .attr("data-novo_status", 1)
                            .html("<img src='"+ img_taxa_boleto + "' alt='Taxa de boleto' class='desativado' style='height: 30px' />");
                        // $("#mensagem").html('<div class="alert alert-success"><p><b>Taxa de boleto desativada com sucesso!</b></p></div>');
                    }
                }else {
                    $('.placa-alert').css('display', 'block');
                    $("#mensagem").html('<div class="alert alert-success"><p><b>'+data.msn+'</b></p></div>');
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
                    // $('.placa-alert').css('display', 'block');
                    // $("#mensagem").html(data.mensagem);
                    $('.dataFim'+id_contrato).attr("data-fim_aditivo", $("#input_dataFim_aditivo")[0].value );
                    botao.removeAttr('disabled').html('Salvar');
                }else {
                    $("#fim_aditivo_modal").modal('hide');
                    // $('.placa-alert').css('display', 'block');
                    // $("#mensagem").html(data.mensagem);
                    $('.dataFim'+id_contrato).attr("data-fim_aditivo", $("#input_dataFim_aditivo")[0].value )
                        .html("<img src='"+ img_data_fim + "' alt='Fim do Aditivo' class='ativado' style='height: 30px' />");
                    botao.removeAttr('disabled').html('Salvar');

                }

            },
            error:function(data){
                $("#fim_aditivo_modal").modal('hide');
                $("#mensagem").html(data.mensagem);
                $('.placa-alert').css('display', 'block');
                botao.addClass('btn btn-success').text('Salvar').attr('disabled', false);
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

    //CARREGA MODAL 'ALTERAR TIPO DE FATURAMENTO'
    $(document).on('click', '.tipoFaturamento', function(e) {
        e.preventDefault();

        //RESETA AS MENSAGENS DE RETORNO
        $("#msgTipoFaturamento").html('');
        $('.tipo_faturamento_alert').css('display', 'none');

        var id_contrato = $(this).attr('data-id_contrato');
        var consumo = $(this).attr('data-consumo');
        var tipo_proposta = $(this).attr('data-tipo_proposta');

        let fat_mensal = `<div class="col-md-6">
                            <input class="form-check-input" type="radio" name="consumo_fatura" id="faturamento_mensal" value="0">
                            <label class="form-check-label" for="faturamento_mensal"> ${lang.faturamento_mensal}</label>
                        </div>`;
        let fat_consumo = `<div class="col-md-6">
                            <input class="form-check-input" type="radio" name="consumo_fatura" id="faturamento_consumo" value="1">
                            <label class="form-check-label" for="faturamento_consumo"> ${lang.faturamento_consumo}</label>
                        </div>`;
        let fat_apartir_cadastro = `<div class="col-md-6">
                            <input class="form-check-input" type="radio" name="consumo_fatura" id="faturamento_apartir_cadastro" value="2">
                            <label class="form-check-label" for="faturamento_apartir_cadastro"> ${lang.faturamento_apartir_cadastro}</label>
                        </div>`;
        let fat_apartir_instalacao = `<div class="col-md-6">
                            <input class="form-check-input" type="radio" name="consumo_fatura" id="faturamento_apartir_instalacao" value="3">
                            <label class="form-check-label" for="faturamento_apartir_instalacao"> ${lang.faturamento_apartir_instalacao}</label>
                        </div>`;

        //MARCA O INPUT CHECK ATIVO
        switch (tipo_proposta) {
            case '1': //CHIPS
                $('#divTipoFaturamento').html(fat_mensal+fat_apartir_cadastro);
                break;
            case '4': //TORNOZELEIRAS
                $('#divTipoFaturamento').html(fat_mensal+fat_apartir_cadastro);
                break;
            case '6': //ISCAS
                $('#divTipoFaturamento').html(fat_mensal+fat_apartir_cadastro);
                break;
            default:  //VEICULOS
                $('#divTipoFaturamento').html(fat_mensal+fat_consumo+fat_apartir_cadastro+fat_apartir_instalacao);
                break;
        }

        //MARCA O INPUT CHECK ATIVO
        switch (consumo) {
            case '0':
                $('#faturamento_mensal').prop('checked', true);
                break;
            case '1':
                $('#faturamento_consumo').prop('checked', true);
                break;
            case '2':
                $('#faturamento_apartir_cadastro').prop('checked', true);
                break;
            case '3':
                $('#faturamento_apartir_instalacao').prop('checked', true);
                break;
        }

        $('#btnSalvarTipoFaturamento').attr('data-id_contrato', id_contrato);
        $("#modalTipoFaturamento").modal({
            keyboard: false,
            show: true
        });
    });

    /*
    * SALVA O TIPO DE FATURAMENTO
    */
    $("#formTipoFaturamento").submit(function(e){
         e.preventDefault();

         var botao = $('#btnSalvarTipoFaturamento');
         var id_contrato = botao.attr('data-id_contrato')
         var dadosform = $(this).serialize()+'&id_contrato='+id_contrato;

        $.ajax({
            url: site_url+'/contratos/salvarTipoFaturamento',
            type: "POST",
            dataType: "json",
            data: dadosform,
            beforeSend: function (callback) {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> '+lang.salvando);
            },
            success: function (callback) {
                if (callback.success) {
                    $("#msgTipoFaturamento").html('<div class="alert alert-success"><p>'+callback.msg+'</p></div>');
                    $(".tipFat_"+id_contrato).prop('title', callback.tituloConsumo).attr('data-consumo', callback.consumo);
                }else {
                    $("#msgTipoFaturamento").html('<div class="alert alert-danger"><p><b>'+callback.msg+'</b></p></div>');
                }
            },
            error: function(callback){
                $("#msgTipoFaturamento").html('<div class="alert alert-danger"><p><b>'+lang.tente_mais_tarde+'</b></p></div>');
            },
            complete: function(callback){
                //MOSTRA A MENSAGEM DE RETORNO
                $('.tipo_faturamento_alert').css('display', 'block');
                botao.attr('disabled', false).html(lang.salvar);
            }
        })

    });

    //remove o campo de mensagens das respostas de digitalizar contratos
    $(document).on('click', '.close_msn_contratos', function(e) {
        $(".placa-alert").css('display','none');
    });

    //remove o campo de mensagens das respostas de digitalizar contratos
    $(document).on('click', '.close_digi', function(e) {
        $(".digi_alert").css('display','none');
    });



    //carrega os documentos digitalizados
    $(document).on('click', '.digitalizar', function(e) {
        e.preventDefault();
        //reseta todos os dados do formulario
        $('#formDigiContrato')[0].reset();
        $('.digi_alert').css('display', 'none');
        //abre modal
        $('#myModal_digitalizar').modal('show');
        id_contrato = $(this).attr('data-id_contrato');
        //adiciona o id_contrato ao formulario para ser enviado junto dos demais dados
        $('#id_contrato_digi').val(id_contrato);
        //destroi a tabela, possibilitando recria-la com novos parametros. caso necessite
        tableDigi.destroy();

        tableDigi = $('#table-digitalizar').DataTable({
            ajax:{
                url: site_url+'/contratos/ajax_digi_contrato',
                type: 'POST',
                data: { id: id_contrato }
            },
            processing: true,
            pagingType: 'numbers',
            dom: 'Bfrtip',
            responsive: true,
            info: false,
            order: [ 0, 'desc' ],
            columnDefs: [
                {"className": "dt-center", "targets": "_all"}
            ],
            otherOptions: {},
            initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
            },
            columns: [
                {data: 'id'},
                {data: 'descricao'},
                {
                    data: 'file',
                    render: function (data) {
                        return '<a href="'+site_url+'/contratos/visualizar_contrato/'+data+'" target="_blank" class="btn btn-success btn-mini"><i class="icon-eye-open icon-white"></i>Visualizar</a>'
                    }
                }
            ],
            language: {
                loadingRecords: "&nbsp;",
                processing: "Carregando os arquivos...",
                emptyTable: "Nenhum registro encontrado",
                zeroRecords: "Nenhum registro encontrado",
                search: "Pesquisar"
            }
        });
    })

    //DIGITALIZA UM ARQUIVO DE CONTRATO
    $("#formDigiContrato").ajaxForm({
        dataType: 'json',
        beforeSend:function(){
            $('.clientes-alert').css('display', 'block');
            botaoDigi = $('.btn_digi');
            botaoDigi.attr('disabled', 'false').html('<i class="fa fa-spinner fa-spin"></i> Enviando...');
        },
        success: function(retorno){
            if (retorno.status) {
                $("#mensagem_digi").html(retorno.mensagem);
                var novaLinha = retorno.registro;
                var id = novaLinha.id;
                var desc = novaLinha.descricao;
                var view = novaLinha.file;

                //insere uma nova linha na tabela com os dados da nova digitalizacao
                tableDigi.rows.add(
                    [{
                        id: id,
                        descricao: desc,
                        file: view
                     }]).draw(null, false);

                //Muda o status do icone de digitalizados na listagem de contratos
                $('.digi_'+id_contrato).html("<img src='"+ img_digitalizar + "' alt='Digitalizar Documentos' class='ativado' style='height:30px' />");

            }else {
                $("#mensagem_digi").html(retorno.mensagem);
            }
        },
        error: function(retorno){
            $("#mensagem_digi").html(retorno.mensagem);
        },
        complete: function(retorno){
            //mostra a mensagem de retorno
            $('.digi_alert').css('display', 'block');
            botaoDigi.html('Enviar').attr('disabled', false);
        }
    });

    //confere se o tipo de arquivo é o requerido  -- digitalizar contrato
    $('#arquivo_digi').change(function () {
       //referencia pra o elemento de upload
       var fileUpload = document.getElementById("arquivo_digi");

       //verifica se o arquivo é um pdf válido
       var regex = /^(.)+(.pdf)$/;
       if (regex.test(fileUpload.value.toLowerCase())) {
           $('.btn_digi').prop('disabled', false);
       }else {
           alert("Por Favor, Use um Arquivo PDF Válido.");
           //limpa os dados do input file
           $('#formDigiContrato')[0].reset();
       }
   });


});

//funções

function edit_vendedor(contrato) {
    document.getElementById("small_contrato").innerHTML = '#'+contrato;
    document.getElementById("idContrato_vend").value = contrato;
}
