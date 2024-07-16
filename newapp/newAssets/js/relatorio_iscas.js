var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
  atualizarAgGrid();

  var dropdown = $("#opcoes_exportacao");

  $("#dropdownMenuButtonRelatorio").click(function () {
    dropdown.toggle();
  });

  $(document).click(function (event) {
    if (
      !dropdown.is(event.target) &&
      event.target.id !== "dropdownMenuButtonRelatorio"
    ) {
      dropdown.hide();
    }
  });
});

function formatarData(dataString) {
    var dataObj = new Date(dataString);
    var dia = ('0' + dataObj.getDate()).slice(-2); 
    var mes = ('0' + (dataObj.getMonth() + 1)).slice(-2); 
    var ano = dataObj.getFullYear();
    var horas = ('0' + dataObj.getHours()).slice(-2);
    var minutos = ('0' + dataObj.getMinutes()).slice(-2);
    var segundos = ('0' + dataObj.getSeconds()).slice(-2);
    return `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
}

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
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
            exportarArquivo(opcao, gridOptions);
        });

        formularioExportacoes.appendChild(div);
    });
};

var AgGridRelatorioIscas;
function atualizarAgGrid() {
    stopAgGrid();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarRelatorioIscasServerSide';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        itemInicial: Number(params.request.startRow) + 1,
                        itemFinal: Number(params.request.endRow),
                        serial: serial
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        console.log(data);
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    // Verifica se o valor é null e substitui por uma string vazia
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                    if (chave === 'data') {
                                        let dataOriginal = dados[i][chave].toString();
                                        let dataFormatada = formatarData(dataOriginal)
                                        dados[i][chave] = dataFormatada
                                    }
                                    if (chave === 'modoEnvio') {
                                        if (dados[i][chave] == 0) {
                                            dados[i][chave] = 'Offline / Bufferizado';
                                        } else if (dados[i][chave] == 1) {
                                            dados[i][chave] = 'Online';
                                        }else if (dados[i][chave] == 2) {
                                            dados[i][chave] = 'Satélite';
                                        }else if (dados[i][chave] == 3) {
                                            dados[i][chave] = 'LoRa P2P';
                                        }else if (dados[i][chave] == 4) {
                                            dados[i][chave] = 'LoRa Wan';
                                        }
                                    }
                                    if (chave === 'porcentagemBateria') {
                                        var voltagem = dados[i][chave]
                                        let percentual = calcularPercentualBateria(voltagem);
                                        dados[i][chave] = `${voltagem}V (${percentual})`
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            alert(data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        } else {
                            alert('Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        }
                    },
                    error: function (error) {
                        alert('Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                    },
                });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'series',
                width: 130,
                suppressSizeToFit: true                
            },
            {
                headerName: 'Latitude',
                field: 'latitude',
                chartDataType: 'category',
                width: 130,
                suppressSizeToFit: true
            },
            {
                headerName: 'Longitude',
                field: 'longitude',
                chartDataType: 'category',
                width: 130,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 100,
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Data',
                field: 'data',
                width: 130,
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Modo de envio',
                field: 'modoEnvio',
                width: 130,
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Porcentagem de bateria',
                field: 'porcentagemBateria',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Endereço',
                field: 'endereco',
                width: 380,
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let inlineStyle = `
                        <style>
                            .linkEnderecoEvento {
                                text-decoration: none;
                                color: #337ab7;
                            }
                            .linkEnderecoEvento:hover {
                                color: #275d8b;
                            }
                        </style>`;
                    let url = `https://www.google.com.br/maps/dir//${data.latitude},${data.longitude}/@${data.latitude},${data.longitude},17z?entry=ttu`;
                    return `${inlineStyle} <a class="linkEnderecoEvento" href="${url}" target="_blank">${data.enredeco}</a>`;
                },
                suppressSizeToFit: true
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
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
                        width: 100

                    },
                },
            ],
            defaultToolPanel: false,
        },
        popupParent: document.body,
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '519px');
    AgGridRelatorioIscas = new agGrid.Grid(gridDiv, gridOptions);
    $('#select-quantidade-por-pagina').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    gridOptions.api.addEventListener('paginationChanged', function(event) {
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
                dados.push(data)
            }
        }) 
        $('#loadingMessage').hide();
    
    });
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoes(gridOptions);
}

function stopAgGrid() {
    var gridDiv = document.querySelector('#table');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapper');
    if (wrapper) {
        wrapper.innerHTML = '<div id="table" class="ag-theme-alpine my-grid"></div>';
    }
}

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
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
        div.classList.add('opcoes_exportacao');
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
            exportarArquivoRelatorio(opcao, gridOptions, 'Relatório de Isca Ultimas 24h');
        });

        formularioExportacoes.appendChild(div);
    });
}

function exportarArquivoRelatorio(tipo, gridOptions, titulo) {
    switch (tipo) {
        case 'csv':
            fileName = 'RelatorioIsca.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: ['serial','latitude','longitude','status','data','modoEnvio','porcentagemBateria', 'endereco']
            });
            break;
        case 'excel':
            fileName = 'RelatorioIsca.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: ['serial','latitude','longitude','status','data','modoEnvio','porcentagemBateria', 'endereco']
            });
            break;
            case 'pdf':
                let definicoesDocumento = getDocDefinition(
                    printParams('A4'),
                    gridOptions.api,
                    gridOptions.columnApi,
                    '',
                    titulo
                );
                pdfMake.createPdf(definicoesDocumento).download('RelatorioIsca_'+ serial + '.pdf');
                break;
    
        }
}

function prepararDadosExportacaoRelatorio() {
    let rodape = `Relatório de Isca - ${new Date().toLocaleString('pt-br')}`;
    let nomeArquivo = `RelatorioIsca_${serial}.pdf`;

    return {
        nomeArquivo,
        rodape
    };
}

function printParams(pageSize) {
    return {
        PDF_HEADER_COLOR: "#ffffff",
        PDF_INNER_BORDER_COLOR: "#dde2eb",
        PDF_OUTER_BORDER_COLOR: "#babfc7",
        PDF_LOGO: BaseURL + 'media/img/new_icons/ceabs.png',
        PDF_HEADER_LOGO: 'ceabs',
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


function calcularPercentualBateria(voltagemAtual, voltagemMin = 0.0, voltagemMax = 4.2) {
    voltagemAtual = Math.max(voltagemMin, Math.min(voltagemAtual, voltagemMax));
    var percentual = (voltagemAtual - voltagemMin) / (voltagemMax - voltagemMin) * 100;
    return percentual.toFixed(0) + '%';
}
