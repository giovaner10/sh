$(document).ready(function () {
    // select2 das permissões
    $("#permissao").select2({
        placeholder: 'Selecione uma permissão',
        language: "pt-BR",
        width: '100%',
    });

    buscarPermissoesDashboard();

    atualizarAgGrid([]);

    $('#loadingMessage').show();

    $.ajax({
        url: `${CAMINHO_CONTROLLER}/buscar_dashboards`,
        type: 'GET',
        success: function (dados) {
            dados = JSON.parse(dados);
            if (dados) {
                switch (dados.status) {
                    case 200:
                        atualizarAgGrid(dados.data);
                        $('#loadingMessage').hide();
                        break;
                    case 500:
                        alert("Ocorreu um erro ao buscar os Dashboards, tente novamente em alguns instantes");
                        atualizarAgGrid([]);
                        $('#loadingMessage').hide();
                        break;
                    default:
                        alert("Ocorreu um erro ao buscar os Dashboards, tente novamente em alguns instantes");
                        atualizarAgGrid([]);
                        $('#loadingMessage').hide();
                        break;
                }
            }
        },
        error: function () {
            alert("Ocorreu um erro ao realizar a requisição, tente novamente em alguns instantes");
            atualizarAgGrid([]);
        }
    });
});

$(document).on('click', '#btn-salvar-dashboard', function (e) {
    let button = $(this);
    button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size: 15px;">');

    idDashboard = $('#id').val();
    idMenu = $('#id-menu').val();
    titulo = $('#titulo').val();
    linkBi = $('#link-bi').val();
    permissao = $('#permissao').val();
    $('#ativo').is(':checked') ? ativo = 1 : ativo = 0;

    if (!titulo || titulo === "") {
        alert("Um título é necessário para cadastrar o Dashboard!");
        button.attr('disabled', false).html('Salvar');
        return;
    }

    if (!linkBi || linkBi === "") {
        alert("Um link válido é necessário para cadastrar o Dashboard!");
        button.attr('disabled', false).html('Salvar');
        return;
    }

    if (!permissao || permissao === "") {
        alert("Uma permissão válida é necessário para cadastrar o Dashboard!");
        button.attr('disabled', false).html('Salvar');
        return;
    }

    data = {
        titulo: titulo,
        link_bi: linkBi,
        ativo: ativo,
        permissao: permissao,
    }

    if (!idDashboard || idDashboard === "") {
        urlAcao = `${CAMINHO_CONTROLLER}/salvar_dashboard`;
    } else {
        data.id = idDashboard;
        data.id_menu = idMenu;
        urlAcao = `${CAMINHO_CONTROLLER}/alterar_dashboard`;
    }
    $.getJSON({
        url: urlAcao,
        type: "POST",
        data: data,
        success: function (resposta) {
            resposta ? alert(resposta.msg) : alert("Ocorreu um problema ao cadastrar o Dashboard, tente novamente.");
        },
        error: function (error) {
            alert("Ocorreu um problema ao executar esta ação, tente novamente em instantes.");
        },
        complete: function () {
            button.attr('disabled', false).html('Salvar');
            $("#modal-dashboard").modal('hide');
            $.ajax({
                url: `${CAMINHO_CONTROLLER}/buscar_dashboards`,
                type: 'GET',
                success: function (dados) {
                    dados = JSON.parse(dados);
                    if (dados) {
                        switch (dados.status) {
                            case 200:
                                atualizarAgGrid(dados.data);
                                break;
                            case 500:
                                alert("Ocorreu um erro ao buscar os Dashboards, tente novamente em alguns instantes");
                                atualizarAgGrid([]);
                                break;
                            default:
                                alert("Ocorreu um erro ao buscar os Dashboards, tente novamente em alguns instantes");
                                atualizarAgGrid([]);
                                break;
                        }
                    }
                },
                error: function () {
                    alert("Ocorreu um erro ao realizar a requisição, tente novamente em alguns instantes");
                    atualizarAgGrid([]);
                }
            });
        }
    });
})

$('#modal-dashboard').on('hide.bs.modal', function () {
    limparModalDashboard()
});

function limparModalDashboard() {
    $('#id').val("");
    $('#titulo').val("");
    $('#link-bi').val("");
    $('#permissao').val("");
    $('#permissao').trigger('change');
    if ($('#ativo').is(':checked')) $('#ativo').prop("checked", false);
    $("#header-modal").html("Cadastrar Dashboard");
}

function editarDashboard(botao, id) {
    botao = $(botao);

    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size: 15px;">');
    $.getJSON({
        url: `${CAMINHO_CONTROLLER}/buscar_dashboard`,
        type: "POST",
        data: { id: id },
        success: function (resposta) {
            if (resposta) {
                switch (resposta.status) {
                    case 200:
                        dashboard = resposta.data;
                        $('#id').val(dashboard.id);
                        $('#id-menu').val(dashboard.id_menu);
                        $('#titulo').val(dashboard.titulo);
                        $('#link-bi').val(dashboard.link_bi);
                        $('#permissao').val(dashboard.permissao);
                        $('#permissao').trigger("change");

                        dashboard.ativo === "1" ? $('#ativo').prop("checked", true) : $('#ativo').prop("checked", false);
                        $("#header-modal").html("Editar Dashboard");
                        $("#modal-dashboard").modal('show')
                        break;
                    default:
                        alert("Ocorreu um problema ao buscar o Dashboard, tente novamente.");
                }
            } else {
                alert("Ocorreu um problema ao buscar o Dashboard, tente novamente.");
            }

        },
        error: function (error) {
            alert("Ocorreu um problema ao buscar o Dashboard, tente novamente.");
        },
        complete: function () {
            botao.attr('disabled', false).html("<i class='fa fa-pencil-square-o'></i>");
        }
    });
}

function deletarDashboard(botao, id) {
    botao = $(botao);
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size: 15px;">');

    if (confirm('Você tem certeza que deseja deletar este Dashboard?')) {

        $.getJSON({
            url: `${CAMINHO_CONTROLLER}/deletar_dashboard`,
            type: "POST",
            data: { id: id },
            success: function (resposta) {
                if (resposta) {
                    resposta ? alert(resposta.msg) : alert("Ocorreu um problema ao deletar o Dashboard, tente novamente.");
                } else {
                    alert("Ocorreu um problema ao deletar o Dashboard, tente novamente.");
                }
            },
            error: function (error) {
                alert("Ocorreu um problema ao deletar o Dashboard, tente novamente.");
            },
            complete: function () {
                botao.attr('disabled', false).html("<i class='fa fa-trash'></i>");
                $.ajax({
                    url: `${CAMINHO_CONTROLLER}/buscar_dashboards`,
                    type: 'GET',
                    success: function (dados) {
                        dados = JSON.parse(dados);
                        if (dados) {
                            switch (dados.status) {
                                case 200:
                                    atualizarAgGrid(dados.data);
                                    break;
                                case 500:
                                    alert("Ocorreu um erro ao buscar os Dashboards, tente novamente em alguns instantes");
                                    atualizarAgGrid([]);
                                    break;
                                default:
                                    alert("Ocorreu um erro ao buscar os Dashboards, tente novamente em alguns instantes");
                                    atualizarAgGrid([]);
                                    break;
                            }
                        }
                    },
                    error: function () {
                        alert("Ocorreu um erro ao realizar a requisição, tente novamente em alguns instantes");
                        atualizarAgGrid([]);
                    }
                });
            }
        });
    } else {
        botao.attr('disabled', false).html("<i class='fa fa-trash'></i>");
    }
}

function buscarPermissoesDashboard() {
    $.getJSON({
        url: `${CAMINHO_CONTROLLER}/buscar_permissoes_dashboard`,
        type: 'GET',
        dataType: 'json',
        success: function (resposta) {
            if (resposta.status === 200) {
                permissoes = resposta.data;
                permissoes.forEach(permissao => {
                    textoPermissao = permissao.descricao + " (" + permissao.cod_permissao + ")";
                    $("#permissao").append('<option value="' + permissao.cod_permissao + '">' + textoPermissao + '</option>')
                });
            } else {
                alert("Ocorreu um problema ao preencher os campos de permissões, tente novamente em instantes.");
            }
        },
        error: function (error) {
            alert("Ocorreu um problema ao buscar as permissões, tente novamente.");
        }
    });
}

var AgGridListagemDashboards;
var DadosAgGrid = [];

function atualizarAgGrid(dados) {
    stopAgGRID();
    const gridOptions = {
        columnDefs: [{
            headerName: 'Título',
            field: 'titulo',
            chartDataType: 'category',

        },
        {
            headerName: 'Link BI',
            field: 'linkBi',
            chartDataType: 'series',

            cellRenderer: function (params) {
                return `<a href="${params.value}" target="_blank">${params.value}</a>`;
            }
        },
        {
            headerName: 'Permissão',
            field: 'permissao',
            chartDataType: 'series',

        },
        {
            headerName: 'Criado Em',
            field: 'criadoEm',
            chartDataType: 'series',

        },
        {
            headerName: 'Criado Por',
            field: 'criadoPor',
            chartDataType: 'series',

        },
        {
            headerName: 'Modificado Em',
            field: 'modificadoEm',
            chartDataType: 'series',
        },
        {
            headerName: 'Modificado Por',
            field: 'modificadoPor',
            chartDataType: 'series',

        },
        {
            headerName: 'Ativo',
            field: 'ativo',
            chartDataType: 'series',

        },
        {
            headerName: 'Ações',
            field: 'acoes',
            chartDataType: 'series',

            cellRenderer: function (options) {
                return options.value;
            },
        }],
        defaultColDef: {
            enablePivot: true,
            editable: false,
            filter: true,
            resizable: true,
        },
        statusBar: {
            statusPanels: [{
                statusPanel: 'agTotalRowCountComponent',
                align: 'right'
            },
            {
                statusPanel: 'agFilteredRowCountComponent'
            },
            {
                statusPanel: 'agSelectedRowCountComponent'
            },
            {
                statusPanel: 'agAggregationComponent'
            },
            ],
        },
        localeText: localeText,
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
    };

    var gridDiv = document.querySelector('#table-listagem-dashboards');
    AgGridListagemDashboards = new agGrid.Grid(gridDiv, gridOptions);

    gridOptions.api.setRowData(dados);

    gridOptions.quickFilterText = '';

    document.querySelector('#search-input').addEventListener('input', function () {
        var searchInput = document.querySelector('#search-input');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

     document.querySelector('#select-quantidade-por-pagina').addEventListener('change', function () {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    preencherExportacoes(gridOptions);
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#table-listagem-dashboards');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapper-listagem-dashboards');
    if (wrapper) {
        wrapper.innerHTML = '<div id="table-listagem-dashboards" class="ag-theme-alpine my-grid-listagem-dashboards"></div>';
    }
}

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_listagem_dashboards');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + '/media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + '/media/img/new_icons/pdf.png';

    formularioExportacoes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_listagem_dashboards');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function () {
            div.style.backgroundColor = '#f0f0f0';
        });

        div.addEventListener('mouseout', function () {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function (event) {
            event.preventDefault();
            exportarArquivo(opcao, gridOptions);
        });

        formularioExportacoes.appendChild(div);
    });
}

function exportarArquivo(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'Dashboards.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName
            });
            break;
        case 'excel':
            fileName = 'Dashboards.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName
            });
            break;
        case 'pdf':
            let dadosExportacao = prepararDadosExportacaoRelatorio();

            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                dadosExportacao.informacoes,
                dadosExportacao.rodape
            );
            pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
            break;

    }
}

function prepararDadosExportacaoRelatorio() {
    let informacoes = DadosAgGrid.map((dados) => ({
        'Título': dados.titulo,
        'Link BI': dados.linkBi,
        'Permissão': dados.permissao,
        'Criado Em': dados.criadoEm,
        'Criado Por': dados.criadoPor,
        'Modificado Em': dados.modificadoEm,
        'Modificado Por': dados.modificadoPor,
        'Ativo': dados.ativo,
    }));

    let rodape = `Listagem de Dashboards`;
    let nomeArquivo = `Dashboards.pdf`;

    return {
        informacoes,
        nomeArquivo,
        rodape
    };
}

document.addEventListener('DOMContentLoaded', function () {
    var dropdown = document.getElementById('opcoes_exportacao_listagem_dashboards');

    document.getElementById('dropdownMenuButtonListagemDashboards').addEventListener('click', function () {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function (event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonListagemDashboards') {
            dropdown.style.display = 'none';
        }
    });
});

