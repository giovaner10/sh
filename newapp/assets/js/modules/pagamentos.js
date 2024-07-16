//INSTANCIA A TABELA DE PERFIS ANALISADOS
tabela_pagamentos = $('#tabela_pagamentos').DataTable({
    responsive: true,
    order: [ 1, 'desc' ],
    destroy: true,
    columns: [
        {
            data: 'check',
            orderable: false,
            render: function (data, type, row, meta) {
                return `<p><input type="checkbox" name="cod_baixa[]" class="cod_baixa" value="${row.id}"/></p>`;
            }
        },
        { data: 'id' },
        { data: 'conta_movimentacao' },
        { data: 'data' },
        { data: 'categoria' },
        { data: 'historico' },
        { data: 'codigo_transacao' },
        { data: 'valor' },
        { data: 'tipo' },
        { data: 'densidade' },
        { data: 'fatura' },
        { data: 'data_baixa' },
        { data: 'usuario' },
        {data:'status'},
        {
            data: 'admin',
            orderable: false,
            render: function (data, type, row, meta) {
                return `<button class="btn editar_pagamneto" data-id="${row.id}" data-empresa="${row.empresa}" title="${lang.msg_vincular_multi_faturas}"> <i class="fa fa-gear"></i></button>`;
            }
        }
    ],
    language: lang.datatable
});


$(document).ready(function () {

    let selects = ` <button type="button" class="btn btn-primary" id="vincularPagamentos" title="${lang.msg_vincular_multi_pagamentos}" style="margin-bottom:10px;">${lang.vincular_pagamento}</button>
                    <button type="button" class="btn btn-primary" id="subirArquivo" title="${lang.msg_subir_arquivo}" style="margin-bottom:10px;">${lang.subir_arquivo}</button>`;

    $('#tabela_pagamentos_length').html(selects).css('width', '100%');
    
    //ABRE O MODAL DE VINCULAR VARIOS PAGAMENTOS A UMA FATURA
    $(document).on('click', '#vincularPagamentos', function () {

        //Limpa os campos do formulario
        $('#formMultiPagamentos')[0].reset();
        
        let pagamentos = [];
        if (!$('input[name="cod_baixa[]"]:checked').serialize()) {
            return alert(lang.nenhum_pagamento_selecionado);

        } else {
            $('input[name="cod_baixa[]"]:checked').each(async function () {
                pagamentos.push($(this).val());
            });
            
            //Abre o modal
            $('#modal_fatura_multi_pagamento').modal('show');
        }

        $('#extratos').val(pagamentos);
    });
    

    //CARREGA A TABELA COM OS DADOS FILTRADOS
    $('#formPagamentos').submit(function (e) {
        e.preventDefault();
        
        var botao = $('.btnFormPagamentos');
        page_pagamentos = 0;
        load_tabela_pagamentos(botao);    
    });


    //Vincula um ou varios pagamentos a uma fatura
    $('#formMultiPagamentos').submit(function (e) {
        e.preventDefault();
        
        var botao = $('#btnConfirmarMultiPagamento');
        var data_form = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: site_url + '/extract/vincular_multi_pagamentos',
            data: data_form,
            dataType: 'json',
            beforeSend: function () {
                if (botao) botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
            },
            success: function (callback) {
                alert(callback.msg);

                if (callback.success) {
                    //Atualiza a tabela
                    load_tabela_pagamentos();
                }
            },
            error: function () {
                alert(lang.operacao_nao_finalizada);
            },
            complete: function () {
                if (botao) botao.attr('disabled', false).html(lang.salvar);
            }
        });

    });
    
    /**
     * Envia o arquivo de baixa para o sistema
    */
    $(document).on('click', '#subirArquivo', function () {
        //reseta os campos do formulario
        $('#formFile')[0].reset();
        //Abre o modal
        $('#modalSubirArquivo').modal('show');
    });

    /**
     * Filtra para passar apenas arquivos de extensão: .bbt .txt .csv de tamanho inferior a 2M
    */
    $(document).on('click', '#file', function (e) {
        var uploadField = document.getElementById("file");
        if (uploadField != null) {
            uploadField.onchange = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.bbt|.txt|.csv)$/;

                if (uploadField.files[0].size > 5242880) {
                    $('#file').val('');
                    alert(lang.limite_tam_arq_2m);
                } 
                else if (!regex.test(uploadField.files[0].name.toLowerCase())) {
                    $('#file').val('');
                    alert(lang.msg_formato_x_arquivo_invalido);
                }
            };
        }
    });

    /**
     * CARREGA A TABELA COM OS DADOS FILTRADOS
    */
    $('#formFile').submit(function (e) {
        e.preventDefault();

        var botao = $('#btnFormFile');
        let formData = new FormData($('#formFile')[0]);

        $.ajax({
            url: site_url + '/extract/adicionar_extrato',
            type: 'POST',
            dataType: 'JSON',
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function () {
                botao.html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando).attr('disabled', true);
            },
            success: function (callback) {
                alert(callback.msg);

                if (callback.success) {
                    page_pagamentos = 0;
                    load_tabela_pagamentos();
                }               
            },
            error: function () {
                alert(lang.falha_na_requisição);
            },
            complete: function () {
                botao.html(lang.salvar).attr('disabled', false);
            }
        });

    });


    /**
     * Vincula uma fatura a um pagamento/baixa
    */
    $(document).on('click', '.vinc', function () {
        //Guarda a pagina corrente da tabela
        page_pagamentos = tabela_pagamentos.page();

        var id = $(this).attr('data-id');
        var idf = $('#vinc' + id).val();
        var type = $(this).attr('data-type');
        var date = $(this).attr('data-date');
        var empresa = $(this).attr('data-empresa');
        var value = $(this).attr('data-value').replace('.', '').replace(',', '.');
        var botao = $(this);

        idf = Number.parseInt(idf);

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: site_url + '/extract/saveIdFatura',
            data: { idf: idf, id: id, type: type, value: value, date: date, empresa: empresa },
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
            },
            success: function (callback) {
                load_tabela_pagamentos();                
                alert(callback.msg);
            },
            error: function () {
                alert(lang.erro_params);
            },
            complete: function () {
                botao.attr('disabled', false).html('Salvar');
            }
        });
    });

    //Abre um pagamento para edicao
    $(document).on('click', '.editar_pagamneto', function () {
        //Guarda a pagina corrente da tabela
        page_pagamentos = tabela_pagamentos.page();

        let botao = $(this);
        let id = botao.attr('data-id');
        let empresa = botao.attr('data-empresa');

        //reseta o modal de vinculacao
        limpaModalVincular();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: site_url + '/extract/get',
            data: { id: id },
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (callback) {
                let pagamento = callback[0];
                let tipo = pagamento.tipo == 'D' ? 'Conta' : 'Fatura';                            

                $('#descricao_extrato').text(pagamento.historico);
                $('#cod_transacao_extrato').val(pagamento.codigo_transacao);
                $('#valor_extrato').val(pagamento.valor);
                $('#tipo_extrato').text(tipo + 's');
                
                //Inicia a configuracao do form de add de vinculacao
                $('#form_vinc').html(`<input type="hidden" name="empresa" value="${empresa}"><input type="hidden" name="id" value="${pagamento.id}"><input type="hidden" name="tipo" value="${pagamento.tipo}">`);

                //Verifica se teve vinculacao de varias faturas ao pagamento
                if (!isNaN(pagamento.fatura.split(',')) || pagamento.fatura.substring(0, 8) == "Vinculou") {
                    if (isNormalInteger(callback[0].fatura)) 
                        $('#btn_remover_vinculo').attr('data-tipo', tipo).attr('data-id_extrato', pagamento.id).attr('data-fatura', pagamento.fatura).removeClass('esconde');                
                }
                else {
                    $('#btn_salvar_vinculacao').removeClass('esconde');
                    $('#btn_adicionar_vinculo').removeClass('esconde');
                }

                $('#modalEditarPagamento').modal('show');
            },
            error: function () {
                alert(lang.erro_params);
            },
            complete: function () {
                botao.attr('disabled', false).html('<i class="fa fa-gear"></i>');
            }
        });
    });    


    //Abre um pagamento para edicao
    $(document).on('click', '#btn_remover_vinculo', function () {    
        let botao = $(this);
        let id_extrato = botao.attr('data-id_extrato');
        let fatura = botao.attr('data-fatura');
        let tipo = botao.attr('data-tipo');

        let url = site_url + '/extract/desvincularConta';
        let dados = { id: id_extrato, id_conta: fatura };
        if (tipo === 'Fatura') {
            url = site_url + '/extract/desvincularFatura';
            dados = { id: id_extrato, id_fatura: fatura };
        }

        $.ajax({
            dataType: 'json',
            url: url,
            type: "POST",
            data: dados,
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Removendo Vinculação');
            },
            success: function (callback) {
                alert(callback.msg);

                if (callback.success) {                    
                    //atualiza a tabela retornando para a pagina corrente
                    load_tabela_pagamentos();                    

                    $('#modalEditarPagamento').modal('hide');                    
                }

            },
            error: function () {
                alert(lang.erro_params);
            },
            complete: function () {
                botao.attr('disabled', false).html('<i class="fa fa-minus"></i> Remover Vinculação');
            }
        });
    });

    //Abre um pagamento para edicao
    $(document).on('click', '#btn_salvar_vinculacao', function () {
        let botao = $(this);

        $.ajax({
            dataType: 'json',
            url: site_url + '/extract/vincularFatura',
            type: "POST",
            data: $('#form_vinc').serialize(),
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> '+ lang.salvando);
            },
            success: function (callback) {
                //atualiza a tabela retornando para a pagina corrente
                load_tabela_pagamentos();                

                alert(callback);
            },
            error: function () {
                alert(lang.erro_params);
            },
            complete: function () {
                botao.attr('disabled', false).html(lang.salvar);
            }
        });
    });


});


/**
 * Atualiza a tabela retornando para a pagina corrente
 */
function load_tabela_pagamentos(botao=false) {
    var data_form = $('#formPagamentos').serialize();

    $.ajax({
        type: 'POST',
        url: site_url + '/extract/get',
        data: data_form,
        dataType: 'json',
        beforeSend: function () {
            if (botao) botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.gerando);
        },
        success: function (callback) {
            // Atualiza Tabela
            tabela_pagamentos.clear();
            tabela_pagamentos.rows.add(callback);
            tabela_pagamentos.draw();

            tabela_pagamentos.page(page_pagamentos).draw(false);
        },
        error: function () {
            //Limpa a tabela
            tabela_pagamentos.clear().draw();
            alert(lang.operacao_nao_finalizada);
        },
        complete: function () {
            if (botao) botao.attr('disabled', false).html(lang.gerar);
        }
    });    
}

function limpaModalVincular() {
    $('#btn_remover_vinculo').addClass('esconde');
    $('#btn_adicionar_vinculo').addClass('esconde');
    $('#btn_salvar_vinculacao').addClass('esconde');
    $('#form_vinc').html('');
}



function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
}


function adicionar_vinculacao() {
    $('#form_vinc').append(`<div class="form-group">
            <label>ID:</label>
            <input type="number" name="vinculacao[]" class="form-control">
        </div>`);
}

// function vincular() {
//     $.ajax({
//         type: "POST",
//         url: site_url + '/extract/vincularFatura',
//         data: $('#form_vinc').serialize(),
//         success: function (data) {
//             data = JSON.parse(data);
//             alert(data);
//         }
//     });
// }
