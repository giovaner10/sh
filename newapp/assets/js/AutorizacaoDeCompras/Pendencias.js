var cliente = '';
var localeText = AG_GRID_LOCALE_PT_BR;
let filialPedido;
let numPedido;
let nivelPedido;
let timeRefresh = 300000;
idUsuarioLogado;
emailUsuario;
$(document).ready(function () {
    atualizarAgGrid(idUsuarioLogado);
    disabledButtons();
    showLoadingPesquisarButton();

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#formBusca').submit(function(e) {
        e.preventDefault();
        processarBusca();
    });
    

    $('#btnLimparFiltro').click(function() {
        if($("#dataInicial").val() ||  $("#dataFinal").val()){
            disabledButtons();
            showLoadingPesquisarButton(); 
            atualizarAgGrid(idUsuarioLogado);
            $("#dataInicial").val('')
            $("#dataFinal").val('')
        }
    });

    setupDropdown('#dropdownMenuButton', '#opcoes_exportacao');

    $('#infoModal').on('shown.bs.modal', function () {
        $('input[type="radio"][name="aprovacao"]').prop('checked', false);
        $('#motivoRejeicao').prop('disabled', true).val('');
        atualizarContador();
    });
    

    $('input[type="radio"][name="aprovacao"]').change(function() {
        if (this.value == 'nao') {
            $('#motivoRejeicao').prop('disabled', false);
            atualizarContador();
        } else {
            $('#motivoRejeicao').prop('disabled', true).val('');
            atualizarContador();
        }
    });

    $("#motivoRejeicao").on("input", function () {
      atualizarContador();
    });

    $("#enviarModal").click(function () {
      var radioSelecionado = $('input[name="aprovacao"]:checked').length > 0;
      if (!radioSelecionado) {
        showAlert("warning", "Por favor, selecione uma opção de aprovação.");
        return;
      }
      enviarAprovacaoPedido();
    });

    $('#aprovarSelecionados').click(aprovarOuRejeitarPedidosSelecionados);
    
});

let menuAberto = false;
function expandirGrid() {
    menuAberto = !menuAberto;

    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

    if (menuAberto) {
        $('.img-expandir').attr("src", buttonShow);
        $('#filtroBusca').hide();
        $('.menu-interno').hide();
        $('#conteudo').removeClass('col-md-9');
        $('#conteudo').addClass('col-md-12');
    } else {
        $('.img-expandir').attr("src", buttonHide);
        $('#filtroBusca').show();
        $('.menu-interno').show();
        $('#conteudo').css('margin-left', '0px');
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

// AGGRID
var AgGrid;
async function atualizarAgGrid(codUsuario) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: async function (params) {
                let codAprovador = await buscarCodigoAprovadorLogado(codUsuario);
                let dataInicial = formatDateTime($("#dataInicial").val());
                let dataFinal = formatDateTime($("#dataFinal").val());

                if (!codAprovador) {
                    enableButtons();
                    resetPesquisarButton();
                    params.successCallback([], 0);
                    AgGrid.gridOptions.api.showNoRowsOverlay();
                    return;
                }

                let requestData = {
                    startRow: params.request.startRow,
                    endRow: params.request.endRow,
                    aprovador: codAprovador,
                    dataInicio: dataInicial || '',
                    dataFim: dataFinal || ''
                };

                disabledButtons();
                showLoadingPesquisarButton();
                let route = Router + "/buscarDadosPedidos";
                $.ajax({
                    cache: false,
                    url: route,
                    type: "POST",
                    data: requestData,
                    dataType: "json",
                    success: function (response) {
                        if (response.success && Array.isArray(response.rows)) {
                            const rows = response.rows.map(row => ({
                                ...row,
                                valor: row.valor ? `R$ ${(parseFloat(row.valor).toLocaleString("pt-BR", { minimumFractionDigits: 2 }))}` : '-'
                            }));
                            params.successCallback(rows, response.lastRow);
                        } else {
                            showAlert('warning', 'Não há autorizações pendentes para este usuário.');
                            enableButtons();
                            resetPesquisarButton();
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                            params.successCallback([],0);
                            return;
                        }
                    },
                    error: function () {
                        showAlert("error", "Erro na obtenção dos dados, contate o suporte técnico.");
                        params.failCallback();
                    },
                    complete: function () {
                        enableButtons();
                        resetPesquisarButton();
                    },
                });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: "Selecionar",
                checkboxSelection: true,
                width: 60,
                colId: "aprovar",
                pinned: "left",
                headerComponentParams: {
                    template: `<div class="ag-cell-label-container" role="presentation" style="display: flex; justify-content: center; align-items: center; margin-left: -10px;">
                                <svg width="18" height="18" style="display: block; margin-left: 5px !important;"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#009952" d="M64 80c-8.8 0-16 7.2-16 16V416c0 8.8 7.2 16 16 16H384c8.8 0 16-7.2 16-16V96c0-8.8-7.2-16-16-16H64zM0 96C0 60.7 28.7 32 64 32H384c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                             </div>`,
                },
                cellStyle: {
                    textAlign: "center",
                    display: "flex",
                    justifyContent: "center",
                    alignItems: "center",
                },
            },
            {
                headerName: "Nº Pedido",
                field: "numPedido",
                chartDataType: "series",
                width: 100,
                suppressSizeToFit: true,
            },
            {
                headerName: "Valor",
                field: "valor",
                chartDataType: "category",
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: "Fornecedor",
                field: "nomeFornecedor",
                chartDataType: "category",
                flex: 1,
                minWidth: 250,
                suppressSizeToFit: true,
            },
            {
                headerName: "Emissão",
                field: "emissao",
                chartDataType: "category",
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    const value = params.value;
                    if (value) {
                      return formatDateTime(value)
                    }
                    return "-";
                  }
            },
            {
                headerName: "Grupo",
                field: "grupo",
                chartDataType: "category",
                width: 120,
                suppressSizeToFit: true,
            },
            {
                headerName: "Nome do Grupo",
                field: "descGrupo",
                chartDataType: "category",
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: "Filial",
                field: "filial",
                chartDataType: "category",
                width: 80,
                suppressSizeToFit: true,
            },
            {
                headerName: "Status da Aprovação",
                field: "statusAprovacao",
                chartDataType: "category",
                flex: 1,
                minWidth: 300,
                suppressSizeToFit: true,
            },
            {
                headerName: "Ações",
                width: 80,
                pinned: "right",
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "table";
                    let dropdownId = "dropdown-menu-" + data.numPedido;
                    let buttonId = "dropdownMenuButton_" + data.numPedido;

                    return `
                        <div class="dropdown" style="position: relative;">
                            <button onclick="abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id_evento}" nome="${data.placa_lida}" id="${data.id_evento}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:agGridPedido('${data.filial}', '${data.numPedido}', '${data.nivel}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
                            </div>
                        </div>`;
                },
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
        },
        rowSelection: "multiple",
        suppressRowClickSelection: true,
        getRowId: (params) => params.data.numPedido,
        sideBar: {
            toolPanels: [
                {
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100,
                    },
                },
            ],
            defaultToolPanel: false,
        },
        popupParent: document.body,
        pagination: true,
        paginationPageSize: parseInt($("#select-quantidade-por-pagina-dados").val()),
        cacheBlockSize: 50,
        localeText: localeText,
        domLayout: "normal",
        rowModelType: "serverSide",
        serverSideStoreType: "partial",
        onCellClicked: function (params) {
            if (params.column.colId === "aprovar") {
                const node = params.node;
                node.setSelected(!node.isSelected());
            }
        },
        onCellDoubleClicked: function (params) {
            if (params.column.colId !== "aprovar") {
                let data = params.data;
                if (data) {
                    agGridPedido(data.filial, data.numPedido, data.nivel);
                }
            }
        },
        getContextMenuItems: function (params) {
            var result = [
                {
                    name: "Selecionar Todos da Página",
                    action: function () {
                        const pageSize = gridOptions.api.paginationGetPageSize();
                        const currentPage = gridOptions.api.paginationGetCurrentPage();
                        const startRow = currentPage * pageSize;
                        const endRow = startRow + pageSize;
                        gridOptions.api.forEachNode(function (node) {
                            if (node.rowIndex >= startRow && node.rowIndex < endRow) {
                                node.setSelected(true);
                            }
                        });
                    },
                },
                {
                    name: "Desmarcar Todos",
                    action: function () {
                        gridOptions.api.deselectAll();
                    },
                },
                "copy",
                "copyWithHeaders",
                "copyWithGroupHeaders",
                "paste",
            ];
            return result;
        },
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '519px');

    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    gridOptions.api.addEventListener('paginationChanged', function (event) {
        $('#loadingMessage').show();

        let paginaAtual = Number(event.api.paginationGetCurrentPage());
        let tamanhoPagina = Number(event.api.paginationGetPageSize());

        const filteredData = [];
        event.api.forEachNode((n) => {
            filteredData.push(n.data);
        });

        const startIndex = paginaAtual * tamanhoPagina;
        const endIndex = startIndex + tamanhoPagina;
        const pageData = filteredData.slice(startIndex, endIndex);

        var dados = [];
        pageData.forEach((data) => {
            if (data) {
                dados.push(data);
            }
        });
        $("#loadingMessage").hide();
    });

    let datasource = getServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions);
}


function buscarCodigoAprovadorLogado(idUser) {
    let route = Router + "/buscarCodAprovador";
    return $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: { login: idUser }
    })
    .then(response => {
        if (response && response.resultado && response.resultado.length > 0) {
            return response.resultado[0].codigoAprovador;
        } else {
            showAlert("warning", "Dados não encontrados");
            return null;
        }
    })
    .catch(error => {
        showAlert("error", "Erro na solicitação ao servidor. Contate o suporte técnico.");
        return null;
    });
}

var agGridPedidoModal;

async function agGridPedido(idFilial, idPedido, nivel) {
    stopAgGRIDPedido();
    $("#loading").show();

    async function getServerSideData() {
        let route = Router + "/buscarDadosPedidoById";
        try {
            const response = await $.ajax({
                url: route,
                type: 'POST',
                dataType: 'json',
                data: {
                    filial: idFilial,
                    pedido: idPedido
                }
            });

            if (response.status === 200 && response.resultado && response.resultado.length > 0) {
                $("#loading").hide();
                $('#infoModal').modal('show');

                const pedido = response.resultado[0];
                
                let aprovadorEncontrado = pedido.APROVADORES.find(aprovador => aprovador.COD_STATUS === "02");
                if (!aprovadorEncontrado) {
                    aprovadorEncontrado = pedido.APROVADORES[0];
                }
                
                manipulaBtnEnviar(aprovadorEncontrado.COD_STATUS);

                let dadosModal = {
                    pedido: idPedido,
                    fornecedor: `${pedido.NOME_FORNECEDOR} (${formatCpfCnpj(pedido.CNPJ)})`,
                    dataEmissao: formatDateTime(pedido.DATA_EMISSAO),
                    condPagamento: pedido.CONDICAO_PAGAMENTO,
                    moeda: pedido.MOEDA,
                    comprador: aprovadorEncontrado.USUARIO,
                    aprovador: aprovadorEncontrado.NOME_APROVADOR,
                    vlrPedido: `R$ ${parseFloat(pedido.TOTAL_PEDIDO_COMPRA).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                };

                preencherDadosDetalhes(dadosModal);

                pedido.ITENS.forEach(item => {
                    item.CENTRO_CUSTO = pedido.CENTRO_CUSTO;
                });

                filialPedido = pedido.FILIAL;
                numPedido = pedido.PEDIDO;
                nivelPedido = nivel;

                return pedido.ITENS;
            } else {
                $("#loading").hide();
                enableButtons();
                resetPesquisarButton();
                showAlert("warning", "Dados não encontrados para o pedido: " + idPedido);
                return [];
            }
        } catch (error) {
            enableButtons();
            resetPesquisarButton();
            showAlert("error", "Erro na solicitação ao servidor. Contate o suporte técnico.");
            document.getElementById("loading").style.display = "none";
            return [];
        }
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: "Nº",
                field: "ITEM",
                width: 80,
            },
            {
                headerName: "Cód. Produto",
                field: "CODIGO_PRODUTO",
                width: 150,
            },
            {
                headerName: "Descrição",
                field: "DESCRICAO_PRODUTO",
                width: 300,
            },
            {
                headerName: "Observação",
                field: "OBSERVACAO",
                width: 350,
            },
            {
                headerName: "Dt. de Entrega",
                field: "DATA_ENTREGA",
                width: 150,
                cellRenderer: function (params) {
                    const value = params.value;
                    if (value) {
                        let partes = value.split("T")
                        let partesData = partes[0].split("-");
                        let ano = partesData[0]
                        let mes = partesData[1]
                        let dia = partesData[2]
                        let hora = partes[1]
                        let dataTratada = `${dia}/${mes}/${ano}  ${hora}` 
                        return dataTratada
                    }
                    return "-";
                }
            },
            {
                headerName: "Centro de Custo",
                field: "CENTRO_CUSTO",
                width: 300,
            },
            {
                headerName: "Unid",
                field: "UNIDADE_MEDIDA",
                width: 100,
            },
            {
                headerName: "Quantidade",
                field: "QUANTIDADE",
                width: 130,
            },
            {
                headerName: "Vl. Unit.",
                field: "PRECO_UNITARIO",
                width: 150,
                cellRenderer: function (params) {
                    const value = params.value;
                    if (value !== null && value !== undefined) {
                        return `R$ ${parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }
                    return "-";
                }
            },
            {
                headerName: "% Ipi",
                field: "ALIQUOTA_IPI",
                width: 100,
            },
            {
                headerName: "Vl. Ipi",
                field: "VALOR_IPI",
                width: 100,
                cellRenderer: function (params) {
                    const value = params.value;
                    if (value !== null && value !== undefined) {
                        return `R$ ${parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }
                    return "-";
                }
            },
            {
                headerName: "Vl. Total Item",
                field: "VALOR",
                width: 150,
                cellRenderer: function (params) {
                    const value = params.value;
                    if (value !== null && value !== undefined) {
                        return `R$ ${parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }
                    return "-";
                }
            },
            {
                headerName: "Dt. Últ. Compra",
                field: "DATA_ULTIMA_COMPRA",
                width: 150,
                cellRenderer: function (params) {
                    const value = params.value;
                    if (value) {
                        let partes = value.split("T")
                        let partesData = partes[0].split("-");
                        let ano = partesData[0]
                        let mes = partesData[1]
                        let dia = partesData[2]
                        let hora = partes[1]
                        let dataTratada = `${dia}/${mes}/${ano}  ${hora}` 
                        return dataTratada
                    }
                    return "-";
                }
            },
            {
                headerName: "Vlr. Últ. Compra",
                field: "VALOR_ULTIMA_COMPRA",
                width: 150,
                cellRenderer: function (params) {
                    const value = params.value;
                    if (value !== null && value !== undefined) {
                        return `R$ ${parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }
                    return "-";
                }
            },
            {
                headerName: "Saldo Estoque",
                field: "SALDO_ESTOQUE",
                width: 120,
            },
        ],
        defaultColDef: {
            wrapText: false,
            autoHeight: true,
            resizable: true,
            filter: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
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
        },
        domLayout: "normal",
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    let datasource = await getServerSideData();
    var gridDiv = document.querySelector("#tableModal");
    gridDiv.style.setProperty("height", "300px");

    agGridPedidoModal = new agGrid.Grid(gridDiv, gridOptions);

    if (datasource.length > 0) {
        gridOptions.api.setRowData(datasource);
    }
}



function exportarArquivo(tipo, gridOptions, menu, titulo) {
    // Colunas fixas para exportação
    let colunas = ["numPedido", "nomeFornecedor", "valor", "emissao", "grupo", "descGrupo", "filial", "statusAprovacao"];

    switch (tipo) {
        case 'csv':
            fileName = menu + '.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: colunas,
            });
            break;
        case 'excel':
            fileName = menu + '.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: colunas,
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                titulo,
                colunas
            );
            pdfMake.createPdf(definicoesDocumento).download(menu + '.pdf');
            break;
    }
}

function printParams(pageSize) {
    return {
        PDF_HEADER_COLOR: "#ffffff",
        PDF_INNER_BORDER_COLOR: "#dde2eb",
        PDF_OUTER_BORDER_COLOR: "#babfc7",
        PDF_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
        PDF_HEADER_LOGO: 'omnilink',
        PDF_ODD_BKG_COLOR: "#fff",
        PDF_EVEN_BKG_COLOR: "#F3F3F3",
        PDF_PAGE_ORITENTATION: "landscape",
        PDF_WITH_FOOTER_PAGE_COUNT: true,
        PDF_HEADER_HEIGHT: 25,
        PDF_ROW_HEIGHT: 25,
        PDF_WITH_CELL_FORMATTING: true,
        PDF_WITH_COLUMNS_AS_LINKS: false,
        PDF_SELECTED_ROWS_ONLY: false,
        PDF_PAGE_SIZE: pageSize,
    }
}

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

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
        div.classList.add('opcao_exportacao');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';
        div.style.marginTop = margin;
        div.style.borderRadius = '1px';
        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivo(opcao, gridOptions, 'RelatorioAutPendentes', 'Relatório de Autorizações Pendentes');
        });

        formularioExportacoes.appendChild(div);
    });
}

$(window).scroll(function() {
    $('.select2-container--open').each(function () {
        var $select2 = $(this).prev('select');
        $select2.select2('close');
    });
});

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);
   
    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }
 
    $(".dropdown-menu").hide();
 
    dropdown.show();
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
            dropdown.hide();
        }
    });
}

function preencherDadosDetalhes(dados) {
    enableButtons();
    $('#modalPedido').html(dados.pedido);
    $('#modalFornecedor').html(dados.fornecedor);
    $('#modalEmissao').html(dados.dataEmissao);
    $('#modalCondPagamento').html(dados.condPagamento);
    $('#modalMoeda').html(dados.moeda);
    $('#modalComprador').html(dados.comprador);
    $('#modalAprovador').html(dados.aprovador);
    $('#modalValorTotalPedido').html(dados.vlrPedido);
}

function enviarAprovacaoPedido(){
    showLoadingEnviarButton()
    let route = Router + '/enviarAprovacaoPedido';



    let dadosEnvio = {
        'Filial': filialPedido,
        'Pedido': numPedido,
        'Nivel': nivelPedido,
        'Status': $('input[name="aprovacao"]:checked').val() === 'sim' ? 'A' : 'R',
        'Motivo': $('#motivoRejeicao').val() || `Pedido APROVADO por ${nomeUsuario} (ID: ${idUsuarioLogado}) em ${dataNow()}`
    };

    if (dadosEnvio.Status === 'R' && dadosEnvio.Motivo.includes("APROVADO")) {
            showAlert("warning", "O campo Motivo deve ter pelo menos 3 caracteres.")
            resetEnviarButton();
            return
    }

    if (dadosEnvio.Motivo.length < 3) {
        showAlert("warning", "O campo Motivo deve ter pelo menos 3 caracteres.")
        resetEnviarButton();
        return
    }

        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: dadosEnvio,
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    if (data.resultado.Mensagem.includes('anteriormente')) {
                        showAlert("warning", 'Pedido já enviado anteriormente!');
                    } else if (dadosEnvio.Status === 'A') {
                        showAlert("success", `APROVAÇÃO do pedido ${dadosEnvio.Pedido} realizada com sucesso!`);
                    } else if (dadosEnvio.Status === 'R') {
                        showAlert("success", `REJEIÇÃO do pedido ${dadosEnvio.Pedido} realizada com sucesso!`);
                    }
                    $('#infoModal').modal('hide');
                    atualizarAgGrid(idUsuarioLogado);
                    $("#dataInicial").val('');
                    $("#dataFinal").val('');
                } else if (data.status == 400) {
                    const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                    showAlert("error", errorMessage);
                } else if (data.status == 404) {
                    showAlert("error", "Dados não encontrados.");
                } else {
                    showAlert("error", "Erro interno do servidor. Entre em contato com o Suporte Técnico");
                }                
                resetEnviarButton();
            },
            error: function () {
                showAlert("error", "Erro ao realizar a operação. Tente novamente!")
                resetEnviarButton();
            }
        });
}


//Visibilidade
function showLoadingPesquisarButton() {
    $('#filtrar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}
 
function resetPesquisarButton() {
    $('#filtrar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}
 
function disabledButtons() {
    $('.btn').attr('disabled', true);
}
function enableButtons() {
    $('.btn').attr('disabled', false);
}

function showLoadingEnviarButton() {
    $('#enviarModal').html('<i class="fa fa-spinner fa-spin"></i> Enviando...').attr('disabled', true);
}
function resetEnviarButton() {
    $('#enviarModal').html('Enviar').attr('disabled', false);
}

function showLoadingButtonAcao(action) {
    if (action === 'Aprovar') {
        $('#aprovarSelecionados').html('<i class="fa fa-spinner fa-spin"></i> Aprovando...').attr('disabled', true);
    } else if (action === 'Rejeitar') {
        $('#aprovarSelecionados').html('<i class="fa fa-spinner fa-spin"></i> Rejeitando...').attr('disabled', true);
    }
}

function resetButtonAcao(action) {
    if (action === 'Aprovar') {
        $('#aprovarSelecionados').html('Aprovar / Rejeitar').attr('disabled', false);
    } else if (action === 'Rejeitar') {
        $('#aprovarSelecionados').html('Aprovar / Rejeitar').attr('disabled', false);
    }
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#table');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapper');
    if (wrapper) {
        wrapper.innerHTML = '<div id="table" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDPedido() {
    var gridDiv = document.querySelector('#tableModal');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperModal');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableModal" class="ag-theme-alpine my-grid-modal"></div>';
    }
}

// UTILITÁRIOS

function formatarData(data) {
    const partes = data.split('-');
    const ano = partes[0];
    const mes = partes[1];
    const dia = partes[2];
    return `${dia}/${mes}/${ano}`;
  }


  function formatarDataDetalhePedido(data) {
    data = data.toString();
    const ano = data.substring(0, 4);
    const mes = data.substring(4, 6);
    const dia = data.substring(6, 8);
    return `${dia}/${mes}/${ano}`;
}

  function processarBusca() {
    const dataInicial = $("#dataInicial").val();
    const dataFinal = $("#dataFinal").val();

    const isDataInicialValid = dataInicial !== '' && dataInicial != null;
    const isDataFinalValid = dataFinal !== '' && dataFinal != null;

    
    if (!isDataInicialValid && !isDataFinalValid) {
        showAlert("warning", "É necessário preencher os campos de data para pesquisar.");
        $("#filtrar").blur();
        resetPesquisarButton();
        enableButtons();
        return false; 
    }
    
    if (!validacaoFiltros(dataInicial, dataFinal)) {
        return false;
    }
    showLoadingPesquisarButton();
    disabledButtons();
    atualizarAgGrid(idUsuarioLogado);
}

function validacaoFiltros(dataInicio, dataFim) {
    if ((dataInicio && !dataFim) || (!dataInicio && dataFim)) {
        showAlert("warning", "Ambas as datas, Inicial e Final, devem ser informadas.");
        return false;
    }

    return validarDatas(dataInicio, dataFim);
}

function validarDatas(dataInicialStr, dataFinalStr) {
    const dataInicial = new Date(dataInicialStr);
    const dataFinal = new Date(dataFinalStr);
    const dataAtual = new Date();
    
    dataAtual.setHours(0, 0, 0, 0); 
    
    if (dataInicial > dataAtual) {
        showAlert("warning", "A data inicial não pode ser maior que a data atual.");
        return false;
    }
    else if (dataFinal > dataAtual) {
        showAlert("warning", "A data final não pode ser maior que a data atual.");
        return false;
    }
    else if (dataInicial > dataFinal) {
        showAlert("warning", "A data inicial não pode ser maior que a data final.");
        return false;
    }else if (dataFinal === dataInicial) {
        return true
    }

    return true;
}

  function formatDateTime(date) {
    if (!date) {
        return '';
    }
    let dates = date.split('-');
    if (dates.length !== 3) {
        return '';
    }
    return `${dates[2]}/${dates[1]}/${dates[0]}`;
}

function formatCpfCnpj(value) {
    const formattedValue = value.replace(/\D/g, ''); 
    if (formattedValue.length === 11) { 
        return `CPF: ${formattedValue.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')}`;
    } else if (formattedValue.length === 14) { 
        return `CNPJ: ${formattedValue.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5')}`;
    } else {
        return value; 
    }
}


function atualizarContador() {
    var caracteresRestantes = 255 - $('#motivoRejeicao').val().length;
    $('#contador').text(caracteresRestantes + ' caracteres restantes');
}

function manipulaBtnEnviar(statusAprovacao) {
    var statusNormalizado = statusAprovacao.toLowerCase();
    var statusDesejado = '02'; // Supondo que o status desejado seja '02' (aguardando liberação)
    if (statusNormalizado == statusDesejado) {
        $('#enviarModal').show();
        $('.custom-separator').show();
        $('.form-approval').show();
    } else {
        $('#enviarModal').hide();
        $('.custom-separator').hide();
        $('.form-approval').hide();
    }
}

function dataNow() {
    var dataAtual = new Date();
    var dia = String(dataAtual.getDate()).padStart(2, '0');
    var mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
    var ano = dataAtual.getFullYear();
    var hora = String(dataAtual.getHours()).padStart(2, '0');
    var minutos = String(dataAtual.getMinutes()).padStart(2, '0');
    var segundos = String(dataAtual.getSeconds()).padStart(2, '0');

    return `${dia}/${mes}/${ano} - ${hora}:${minutos}:${segundos}`
}

async function aprovarOuRejeitarPedidosSelecionados() {
    const selectedNodes = AgGrid.gridOptions.api.getSelectedNodes();
    if (selectedNodes.length === 0) {
        showAlert("warning", "Nenhum pedido selecionado para aprovação ou rejeição.");
        return;
    }

    const selectedData = selectedNodes.map(node => node.data);
    const pedidosComErro = [];

    Swal.fire({
        title: "Atenção!",
        text: "Deseja aprovar ou rejeitar os pedidos selecionados?",
        icon: "warning",
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Aprovar",
        denyButtonText: "Rejeitar"
    }).then(async (result) => {
        if (result.isConfirmed) {
            // Aprovar pedidos
            showLoadingButtonAcao('Aprovar');
            disabledButtons();

            for (const pedido of selectedData) {
                try {
                    await $.ajax({
                        url: Router + '/enviarAprovacaoPedido',
                        type: 'POST',
                        data: {
                            Filial: pedido.filial,
                            Pedido: pedido.numPedido,
                            Nivel: pedido.nivel,
                            Status: 'A',
                            Motivo: `Pedido APROVADO por ${nomeUsuario} (ID: ${idUsuarioLogado}) em ${dataNow()}`
                        },
                        dataType: 'json'
                    });
                    showAlert("success", `APROVAÇÃO do pedido ${pedido.numPedido} realizada com sucesso!`);
                } catch (error) {
                    pedidosComErro.push(pedido.numPedido);
                }
            }

            enableButtons();
            resetButtonAcao('Aprovar');

            if (pedidosComErro.length > 0) {
                showAlert("error", `Erro ao APROVAR os pedidos: ${pedidosComErro.join(', ')}`);
            } else {
                atualizarAgGrid(idUsuarioLogado); // Atualizar a AG Grid após a aprovação
            }
        } else if (result.isDenied) {
            // Rejeitar pedidos
            Swal.fire({
                title: 'Rejeitar Pedidos',
                input: 'textarea',
                inputLabel: 'Motivo da Rejeição',
                inputPlaceholder: 'Escreva o motivo da rejeição aqui...',
                inputAttributes: {
                    maxlength: 255,
                    'aria-label': 'Motivo da Rejeição'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirmar Rejeição',
                confirmButtonColor: "#d33",
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const motivo = Swal.getInput().value;
                    if (motivo.length < 3) {
                        Swal.showValidationMessage('O motivo deve ter pelo menos 3 caracteres.');
                        return false;
                    }
                    return motivo;
                }
            }).then(async (result) => {
                if (result.isConfirmed && result.value) {
                    const motivoRejeicao = result.value;

                    showLoadingButtonAcao('Rejeitar');
                    disabledButtons();

                    for (const pedido of selectedData) {
                        try {
                            await $.ajax({
                                url: Router + '/enviarAprovacaoPedido',
                                type: 'POST',
                                data: {
                                    Filial: pedido.filial,
                                    Pedido: pedido.numPedido,
                                    Nivel: pedido.nivel,
                                    Status: 'R',
                                    Motivo: motivoRejeicao
                                },
                                dataType: 'json'
                            });
                            showAlert("success", `REJEIÇÃO do pedido ${pedido.numPedido} realizada com sucesso!`);
                        } catch (error) {
                            pedidosComErro.push(pedido.numPedido);
                        }
                    }

                    enableButtons();
                    resetButtonAcao('Rejeitar');

                    if (pedidosComErro.length > 0) {
                        showAlert("error", `Erro ao REJEITAR os pedidos: ${pedidosComErro.join(', ')}`);
                    } else {
                        atualizarAgGrid(idUsuarioLogado); // Atualizar a AG Grid após a rejeição
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Cancelar
                    showAlert("info", "Ação de REJEIÇÃO cancelada.");
                }
            });
        }
    });
}


function setupDropdown(buttonId, dropdownId) {
    var dropdown = $(dropdownId);

    $(buttonId).click(function(event) {
        event.stopPropagation();

        if (dropdown.is(':visible')) {
            dropdown.hide();
        } else {
            $(".dropdown-menu").hide();
            dropdown.show();
        }
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== buttonId.substring(1) && dropdown.has(event.target).length === 0) {
            dropdown.hide();
        }
    });
}