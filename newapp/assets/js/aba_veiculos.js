$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#table-veiculos')) {
        return tableVeic.destroy();
    }

    $(document).on('click', '.abrir_modal_cadastro_veiculo', function () {
        var code = $(this).data('code');
        var url = site_url + '/cadastros/cadastro_veiculo/' + code;

        abrirModalVisualizarCadastro(url, code);
    });

    tableVeic = $('#table-veiculos').DataTable({
        paging: true,
        searching: true,
        responsive: true,
        processing: true,
        ordering: false,
        lengthChange: false,
        otherOptions: {},
        initComplete: function () {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
        columnDefs: [
            {
                "targets": "datatable-ignore",
                "orderable": false,
                "searchable": false,
            },
        ],
        columns: [
            { data: 'code' },
            { data: 'veiculo'},
            { data: 'placa' },
            { data: 'serial' },
            { data: 'prefixo_veiculo' },
            { 
                data: 'contratos_veiculo',
                render: function (data) {
                    if (Array.isArray(data) && data.length > 0) {
                        var contractList = '<ul style="padding-left: 0px; margin-left: 0px; margin-bottom: 0px">';
                        data.forEach(function (contract) {
                            contractList += '<li style="padding-left: 0px; margin-left: 0px;">' + contract + '</li>';
                        });
                        contractList += '</ul>';
                        return contractList;
                    } else {
                        return '';
                    }
                }
            },
            { 
                data: {'code':'code', 'serial':'serial'},
                render: function (data) {
                    return `
                        <div>
                            <button 
                                data-code="${data.code}" 
                                title="Visualizar cadastro"
                                class="btn btn-primary btn-mini abrir_modal_cadastro_veiculo">
                                    <i class="fa fa-edit"></i>
                            </button>
                            <button 
                                class="btn btn-primary btn-mini abrir_modal"
                                data-terminal="${data.serial}"
                                title="Visualizar detalhes">
                                    <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: lang.datatable,
    });

    carregarCheckBox('Post', '#modal-dados-table-1');
    carregarPesquisa('Post', '#modal-dados-table-1');

    carregarCheckBox('PostCtrl', '#modal-dados-table-3');
    carregarPesquisa('PostCtrl', '#modal-dados-table-3');

    carregarPesquisa('PostIscas', '#modal-dados-table-5');

    carregarPesquisa('PostOmniFrota', '#modal-dados-table-6');

    carregarPesquisa('PostOmniSafe', '#modal-dados-table-7');

    carregarPesquisa('PostOmniSafeCtrl', '#modal-dados-table-8');
});

function abrirModalVisualizarCadastro(href, code) {
    $("#modalVeiculoCadastro .modal-body").html('');
    $('.abrir_modal_cadastro_veiculo[data-code=' + code+ ']').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    $("#modalVeiculoCadastro .modal-content").load(href, function() {
        $("#codeVeiculo").val(code);
        $('.abrir_modal_cadastro_veiculo[data-code=' + code+ ']').attr('disabled', false).html('<i class="fa fa-edit"></i>');
        $("#modalVeiculoCadastro").modal("show");
    });
};

function preencherTabela(idTabela, data, tabela) {

    let indexTabela = idTabela.split('-')[3];
    
    if ($.fn.DataTable.isDataTable(idTabela)) {
        $(idTabela).DataTable().destroy();
    }

    $(idTabela + ' tbody').empty();

    if (data.results && data.results.length > 0) {
        $.each(data.results, function (index, item) {
            const dataFormatada = new Date(item.dataHora).toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            $(idTabela + ' tbody').append(
                '<tr>' +
                '<td width: 10%">' + item.id + '</td>' +
                '<td width: 20%">' + dataFormatada + '</td>' +
                '<td width: 20%">' + item.terminal + '</td>' +
                '<td width: 10%">' + item.codmsg + '</td>' +
                '<td style="word-wrap: break-word; width: 40%">' +
                    '<div class="row align-items-center">' +
                        '<div class="col-md-11">' +
                            '<span class="copiar-valor-' + index + '-table-' + indexTabela + ' truncate-text text-lg" data-valor="' + item.post + '">' + item.post + '</span>' +
                        '</div>' +
                        '<div class="col-md3">' +
                            '<button id="btn-copiar-' + index + '-table-' + indexTabela + '" data-valor="' + item.post + '" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Aperte para copiar">' +
                                '<i class="fa fa-clone" style="font-size: 14px; color: #9FA6B2;"></i>' +
                            '</button>' +
                        '</div>' +
                    '</div>' +
                '</td>' +
                '</tr>'
            );

            // Adiciona evento de clique ao botão para copiar o valor
            $('#btn-copiar-' + index + '-table-' + indexTabela).click(function() {
                const valorParaCopiar = $(this).data('valor');
                copiarParaAreaDeTransferencia(valorParaCopiar, $(this));
            });

            resumirTexto($('.copiar-valor-' + index + '-table-' + indexTabela))
            
        });

        $(idTabela).DataTable({
            "ordering": true,
            "autoWidth": false,
            "order": [
                [1, "desc"]
            ],
            "columnDefs": [
                {
                    targets: [0],
                    width: "10%"
                },
                {
                    "type": "date-br",
                    targets: [1],
                    width: "20%"
                },
                {
                    targets: [2],
                    width: "20%"
                },
                {
                    targets: [3],
                    width: "10%"
                },
                {
                    targets: [4],
                    className: "break-text-column",
                    width: "40%"
                }
            ],
            dom: 'Bfrtip',
            language: {
                loadingRecords: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhuma ocorrência a ser listada",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
                zeroRecords:        "Nenhum resultado encontrado.",
                lengthMenu:         "Mostrar _MENU_ resultados por página",
                paginate: {
                    first:          "Primeira",
                    last:           "Última",
                    next:           "Próxima",
                    previous:       "Anterior"
                },
                infoFiltered: "(filtrado de _MAX_ registros no total)"
            },
            buttons: [
                {
                    filename: filenameGenerator("Informações da ocorrência - " + tabela),
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
                    filename: filenameGenerator("Informações de ocorrência - " + tabela),
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    customize: function ( doc , tes)
                    {                
                        titulo = `Informações de ocorrência - ` + tabela;
                        // Personaliza a página do PDF
                        widths = ['10%', '20%', '10%', '10%', '50%']
                        pdfTemplateIsolated(doc, titulo, 'A4', widths)
                    }
                },
                {
                    filename: filenameGenerator("Informações da ocorrência - " + tabela),
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
                    filename: filenameGenerator("Informações da ocorrência - " + tabela),
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> IMPRIMIR',
                    customize: function ( win )
                    {
                        titulo = `Informações da ocorrência - ` + tabela;
                        // Personaliza a página Impressale
                        printTemplateOmni(win, titulo);
                    }
                }
            ]
        });

        $('[data-toggle="tooltip"]').tooltip( { trigger: 'hover'} );

    } else {
        
        $(idTabela + ' tbody').append(
            '<tr>' +
            '<td colspan="5" style="text-align: center;">Nenhum registro encontrado.</td>' +
            '</tr>'
        );
    }
}

function carregarCheckBox(tabela, idTabela) {
    $("#checkbox" + tabela + "ByData").change(function() {
        if ($(this).is(":checked")) {
            $.ajax({
                url: "veiculos/listarDados" + tabela + "ByData",
                type: "POST",
                dataType: "json",
                data: { terminal: serialLimpo },
                beforeSend: function () {
                    $('#checkbox' + tabela + 'ByData').prop("disabled", true);
                    $('#btnpesq' + tabela).prop("disabled", true);
                    $('#btnlimpar' + tabela).prop("disabled", true);
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                        '</tr>'
                    );
                },
                success: function (data, textStatus, jqXHR) {
                    if (jqXHR.status === 200) {
                        preencherTabela(idTabela, data, tabela);
                    } else if (jqXHR.status === 404) {
                        $(idTabela + ' > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                            '</tr>'
                        );

                        alert("Não existem resgistros para o período informado.")
                    } else {
                        $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                        
                        alert("Ocorreu um erro ao carregar os dados.");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                    
                    alert("Ocorreu um erro ao carregar os dados.");
                },
                complete: function () {
                    $('#checkbox' + tabela + 'ByData').prop("disabled", false);
                    $('#btnpesq' + tabela).prop("disabled", false);
                    $('#btnlimpar' + tabela).prop("disabled", false);
                }
            });
        } else {
            $.ajax({
                url: "veiculos/listarDados" + tabela,
                type: "POST",
                dataType: "json",
                data: { terminal: serialLimpo },
                beforeSend: function () {
                    $('#checkbox' + tabela + 'ByData').prop("disabled", true);
                    $('#btnpesq' + tabela).prop("disabled", true);
                    $('#btnlimpar' + tabela).prop("disabled", true);
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                        '</tr>'
                    );
                },
                success: function (data, textStatus, jqXHR) {
                    if (jqXHR.status === 200) {
                        preencherTabela(idTabela, data, tabela);
                    } else if (jqXHR.status === 404) {
                        $(idTabela + ' > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                            '</tr>'
                        );

                        alert("Não existem resgistros para o período informado.")
                    } else {
                        $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                        
                        alert("Ocorreu um erro ao carregar os dados.");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                    
                    alert("Ocorreu um erro ao carregar os dados.");
                },
                complete: function () {
                    $('#checkbox' + tabela + 'ByData').prop("disabled", false);
                    $('#btnpesq' + tabela).prop("disabled", false);
                    $('#btnlimpar' + tabela).prop("disabled", false);
                }
            });
        }
    });
}

function carregarPesquisa(tabela, idTabela) {
    $("#btnpesq" + tabela).click(function () {
        var dInicial = document.getElementById('dataInicial' + tabela).value;
        var dateSeparaInicial = dInicial.split("-");
        var dataFormatadaInicial = dateSeparaInicial[2] + "/" + dateSeparaInicial[1] + "/" + dateSeparaInicial[0];
    
        var dFinal = document.getElementById('dataFinal' + tabela).value;
        var dateSeparaFinal = dFinal.split("-");
        var dataFormatadaFinal = dateSeparaFinal[2] + "/" + dateSeparaFinal[1] + "/" + dateSeparaFinal[0];
        
        if(dInicial == "" || dFinal == ""){
            alert("Informe as datas corretamente.");
            $('#btnpesq' + tabela)
                    .html('<i class="fa fa-search" aria-hidden="true" style="font-size: 16px;"></i> Pesquisar')
                    .attr('disabled', false);
        } else if (new Date(dFinal) < new Date(dInicial)) {
            alert("A data final não pode ser anterior à data inicial.");
            $('#btnpesq' + tabela)
                    .html('<i class="fa fa-search" aria-hidden="true" style="font-size: 16px;"></i> Pesquisar')
                    .attr('disabled', false);
        } else {
            $.ajax({
                url: "veiculos/listarDados" + tabela + "ByPeriod",
                type: "POST",
                dataType: "json",
                data: { 
                    dataInicial: dataFormatadaInicial,
                    dataFinal: dataFormatadaFinal,
                    terminal: serialLimpo 
                },
                beforeSend: function () {
                    $('#checkbox' + tabela + 'ByData').prop("checked", false);
                    $('#checkbox' + tabela + 'ByData').prop("disabled", true);
                    $('#btnlimpar' + tabela).prop("disabled", true);
                    $('#btnpesq' + tabela)
                        .html('<i class="fa fa-spin fa-spinner" style="font-size: 16px;"></i> Buscando...')
                        .attr('disabled', true);
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                        '</tr>'
                    );
                },
                success: function (data) {
                    if (data.status === 200) {
                        preencherTabela(idTabela, data, tabela);
                    } else if (data.status === 400) {
                        $(idTabela + ' > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                            '</tr>'
                        );
                        
                        alert("Informe datas com intervalo menor que 15 dias.")
                    } else if (data.status === 404) {
                        $(idTabela + ' > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                            '</tr>'
                        );

                        alert("Não existem resgistros para o período informado.")
                    } else {
                        $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                        
                        alert("Ocorreu um erro ao carregar os dados.");
                    }
                    $('#checkbox' + tabela + 'ByData').prop("disabled", false);
                    $('#btnlimpar' + tabela).prop("disabled", false);
                    $('#btnpesq' + tabela)
                                .html('<i class="fa fa-search" aria-hidden="true" style="font-size: 16px;"></i> Pesquisar')
                                .attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                    
                    alert("Ocorreu um erro ao carregar os dados.");
                    $('#checkbox' + tabela + 'ByData').prop("disabled", false);
                    $('#btnlimpar' + tabela).prop("disabled", false);
                    $('#btnpesq' + tabela)
                                .html('<i class="fa fa-search" aria-hidden="true" style="font-size: 16px;"></i> Pesquisar')
                                .attr('disabled', false);
                },
            });
        }
    });

    $('#btnlimpar' + tabela).click(function () {
        var url = '';

        $('#dataInicial' + tabela).val('');
        $('#dataFinal' + tabela).val('');

        if ( tabela == 'Post' || tabela == 'PostCtrl'){
            url = "veiculos/listarDados" + tabela
        } else {
            url = "veiculos/listarDados" + tabela + "ByData"
        }
        
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: { terminal: serialLimpo },
            beforeSend: function () {
                $('#checkbox' + tabela + 'ByData').prop("checked", false);
                $('#checkbox' + tabela + 'ByData').prop("disabled", true);
                $('#btnpesq' + tabela).attr('disabled', true);
                $('#btnlimpar' + tabela)
                    .html('<i class="fa fa-spin fa-spinner" style="font-size: 16px;"></i> Limpando...')
                    .attr('disabled', true);
                $(idTabela + ' > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function (data, textStatus, jqXHR) {
                if (jqXHR.status === 200) {
                    preencherTabela(idTabela, data, tabela);
                } else if (jqXHR.status === 404) {
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );

                    alert("Não existem resgistros para o período informado.")
                } else {
                    $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                    
                    alert("Ocorreu um erro ao carregar os dados.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(idTabela + ' > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="5" class="dataTables_empty" style="text-align: center;">Nenhum registro encontrado.</td>' +
                        '</tr>'
                    );
                
                alert("Ocorreu um erro ao carregar os dados.");
            },
            complete: function () {
                $('#checkbox' + tabela + 'ByData').prop("disabled", false);
                $('#btnpesq' + tabela).attr('disabled', false);
                $('#btnlimpar' + tabela)
                    .html('<i class="fa fa-leaf" aria-hidden="true" style="font-size: 16px;"></i> Limpar')
                    .attr('disabled', false);
                $("#seuCheckbox").prop("checked", false);
            }
        });
    });
}

// Função para copiar o valor para a área de transferência
function copiarParaAreaDeTransferencia(valor, botao) {
    navigator.clipboard.writeText(valor)
        .then(function() {
            botao.tooltip('hide');
            botao.attr('data-original-title', 'Copiado!'); // Atualiza a tooltip para "Copiado!"
            botao.tooltip('show');
            setTimeout(function() {
                botao.tooltip('hide');
                botao.attr('data-original-title', 'Aperte para copiar'); 
            }, 1000); 
        })
        .catch(function(err) {
            console.log('Não foi possível copiar Post');
        });
}

function resumirTexto(objeto) {
    var valor = objeto.data('valor');

    if (valor.length > 100) {
        objeto.text(valor.slice(0, 100) + '...');

        objeto.on('click', function () {
            if (objeto.hasClass('truncate-text')) {
                objeto.removeClass('truncate-text');
                objeto.text(valor); 
            } else {
                objeto.addClass('truncate-text');
                objeto.text(valor.slice(0, 100) + '...');
            }
        });
    }
    
}

var serialLimpo;

function abrir_modal(serial) {

    if (serial === null || serial === undefined || serial === '') {
        alert('O serial não está disponível para este veículo.');
        return; 
    }

    $('.abrir_modal[data-terminal=' + serial+ ']').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    
    $("#modal-listar-dados-post").on("shown.bs.modal", function () {
        // Ativa a primeira aba (Post)
        $("#tab-listarDadosPost").tab("show");
        $('.abrir_modal[data-terminal=' + serial+ ']').attr('disabled', false).html('<i class="fa fa-eye"></i>');
    });

    serialLimpo = removerLetras(serial);

    $("#checkboxPostByData").prop("checked", false);
    $("#checkboxPostCrtlByData").prop("checked", false);

    $.ajax({
        url: "veiculos/listarDadosPost",
        type: "POST",
        dataType: "json",
        data: { terminal: serialLimpo },
        success: function (data, textStatus, jqXHR) {
            if (jqXHR.status === 200) {
                preencherTabela('#modal-dados-table-1', data, 'Post');
                $("#modal-listar-dados-post").modal("show");
            } else {
                alert("Ocorreu um erro ao carregar os dados.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ocorreu um erro ao carregar os dados.");
        },
    });

    $.ajax({
        url: "veiculos/listarDadosPostCtrl",
        type: "POST",
        dataType: "json",
        data: { terminal: serialLimpo },
        success: function (data, textStatus, jqXHR) {
            if (jqXHR.status === 200) {
                preencherTabela('#modal-dados-table-3', data, 'PostCtrl');
            } else {
                alert("Ocorreu um erro ao carregar os dados.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ocorreu um erro ao carregar os dados.");
        },
    });

    $.ajax({
        url: "veiculos/listarDadosPostIscasByData",
        type: "POST",
        dataType: "json",
        data: { terminal: serialLimpo },
        success: function (data, textStatus, jqXHR) {
            if (jqXHR.status === 200) {
                preencherTabela('#modal-dados-table-5', data, 'PostIscas');
            } else {
                alert("Ocorreu um erro ao carregar os dados.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ocorreu um erro ao carregar os dados.");
        },
    });

    $.ajax({
        url: "veiculos/listarDadosPostOmniFrotaByData",
        type: "POST",
        dataType: "json",
        data: { terminal: serialLimpo },
        success: function (data, textStatus, jqXHR) {
            if (jqXHR.status === 200) {
                preencherTabela('#modal-dados-table-6', data, 'PostOmniFrota');
            } else {
                alert("Ocorreu um erro ao carregar os dados.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ocorreu um erro ao carregar os dados.");
        },
    });

    $.ajax({
        url: "veiculos/listarDadosPostOmniSafeByData",
        type: "POST",
        dataType: "json",
        data: { terminal: serialLimpo },
        success: function (data, textStatus, jqXHR) {
            if (jqXHR.status === 200) {
                preencherTabela('#modal-dados-table-7', data, 'PostOmniSafe');
            } else {
                alert("Ocorreu um erro ao carregar os dados.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ocorreu um erro ao carregar os dados.");
        },
    });

    $.ajax({
        url: "veiculos/listarDadosPostOmniSafeCtrlByData",
        type: "POST",
        dataType: "json",
        data: { terminal: serialLimpo },
        success: function (data, textStatus, jqXHR) {
            if (jqXHR.status === 200) {
                preencherTabela('#modal-dados-table-8', data, 'PostOmniSafeCtrl');
            } else {
                alert("Ocorreu um erro ao carregar os dados.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ocorreu um erro ao carregar os dados.");
        },
    });
}


$(document).on('click', '.abrir_modal', function () {
    var terminal = $(this).data('terminal');
    abrir_modal(terminal);
});

$(document).ready(function() {
    $('.abrir-modal').on('click', function() {
        var serial = $(this).data('serial'); 
        abrir_modal(serial);
    });
});

function removerLetras(serial) {
    if (typeof serial === 'string') {
        return serial.replace(/[a-zA-Z]/g, '');
    }

    return;
}

