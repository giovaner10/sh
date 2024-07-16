var localeText = AG_GRID_LOCALE_PT_BR;
$(document).ready(function() {
    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    //$('#emailBusca').popover({placement: 'bottom', trigger: 'manual'});

    $('#formBusca').submit(function (e) {
        e.preventDefault();

        let searchOptions = {
            email: $('#emailBusca').val(),
            dataInicial: $("#dataInicial").val(),
            dataFinal: $("#dataFinal").val()
        }
        if (!searchOptions.email && !searchOptions.dataInicial && !searchOptions.dataFinal) {
            exibirAlerta('warning', 'Falha!', 'Preencha pelo menos um parâmetro para fazer a busca!');
        } else if (searchOptions.dataInicial && !searchOptions.dataFinal) {
            exibirAlerta('warning', 'Falha!', 'Para fazer a busca com data, informe uma Data Final!');
        } else if (searchOptions.dataFinal && !searchOptions.dataInicial) {
            exibirAlerta('warning', 'Falha!', 'Para fazer a busca com data, informe uma Data Inicial!');
        } else if ((searchOptions.dataInicial && searchOptions.dataFinal) && (new Date(searchOptions.dataInicial) > new Date(searchOptions.dataFinal))) {
            exibirAlerta('warning', 'Falha!', "Data Final não pode ser menor que a Data Inicial!");
        } else {
            showLoadingPesquisarButton();
            atualizarAgGrid(searchOptions);
        }

    });

    $('#BtnLimparFiltro').on('click', function (e) {
        showLoadingLimparButton();
        limparPesquisa();
        atualizarAgGrid();
    });

    atualizarAgGrid();
})

//Ultilitarios 
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
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

function exibirAlerta(icon, title, text) {
    Swal.fire({
      position: 'top-start',
      icon: icon,
      title: title,
      text: text,
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true
    });
}

function limparPesquisa() {
    $('#formBusca').trigger("reset");
}

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
    $(document).on('contextmenu', function () {
        dropdown.hide();
    });
}

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
}

// AgGrid
var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();
    function getServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + '/buscarUltimosAcessosServerSide';
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        email: options ? options.email : '',
                        dataInicial: options ? options.dataInicial : '',
                        dataFinal: options ? options.dataFinal : '',
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        if (AgGrid){
                            AgGrid.gridOptions.api.showLoadingOverlay();
                        }
                    },
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            var dados = data.rows;
                            if (dados && dados.length > 0) {
                                for (let i = 0; i < dados.length; i++) {
                                    for (let chave in dados[i]) {
                                        // Verifica se o valor é null e substitui por uma string vazia
                                        if (dados[i][chave] === null) {
                                            dados[i][chave] = '';
                                        }
                                    }
                                }
                                AgGrid.gridOptions.api.hideOverlay();
                                params.success({
                                    rowData: dados,
                                    rowCount: data.lastRow,
                                });
                            } else {
                                exibirAlerta('warning', 'Falha!', 'Dados não encontrados para os parâmentros informados.');
                                params.failCallback();
                                params.success({
                                    rowData: [],
                                    rowCount: 0,
                                });
                                AgGrid.gridOptions.api.showNoRowsOverlay();
                            }
                        } else if (data && data.message && data.status !== 500) {
                            exibirAlerta('warning', 'Falha!', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else if (data.status == 504) {
                            exibirAlerta('warning', 'Falha!', "A solicitação ultrapassou o tempo limite! Tente novamente mais tarde.");
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            exibirAlerta('error', 'Erro!', 'Erro na solicitação ao servidor.');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        }
                        if (options) {
                            resetPesquisarButton();
                        } else {
                            resetLimparButton();
                        }
                        
                    },
                    error: function (error) {
                        exibirAlerta('error', 'Erro!', 'Erro na solicitação ao servidor.');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        if (options) {
                            resetPesquisarButton();
                        } else {
                            resetLimparButton();
                        }
                        
                        AgGrid.gridOptions.api.showNoRowsOverlay();
                    },
                });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data',
                field: 'dataHora',
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
                headerName: 'ID Usuário',
                field: 'idUsuario',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'ID Função API',
                field: 'idFuncaoApi',
                suppressSizeToFit: true
            },
            {
                headerName: 'Parâmetros',
                field: 'parametros',
                suppressSizeToFit: true
            },
            {
                headerName: 'Cód. Retorno',
                field: 'codRetorno',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Qtd. Retorno',
                field: 'qntRetorno',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Qtd. Bytes',
                field: 'qntBytes',
                width: 150,
                suppressSizeToFit: true
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true
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
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '519px');
    
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions)
}

// Carregamento
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

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Filtrando...').attr('disabled', true);
    $('#BtnLimparFiltro').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
    $('#BtnLimparFiltro').attr('disabled', false);
}

function showLoadingLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-spinner fa-spin"></i> Limpando...').attr('disabled', true);
    $('#BtnPesquisar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
    $('#BtnPesquisar').attr('disabled', false);
}

function showLoadingSalvarButton() {
    $('#btnSalvar').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButton() {
    $('#btnSalvar').html('Salvar').attr('disabled', false);
}

// EXPORTAÇÃO DA TEBELA
function exportarArquivo(tipo, gridOptions, titulo) {
	var colunas = ["id", "dataHora", "idUsuario", "idFuncaoApi", "parametros", "codRetorno", "qntRetorno", "qntBytes"];

	switch (tipo) {
		case "csv":
			fileName = titulo + ".csv";
			gridOptions.api.exportDataAsCsv({
				fileName: fileName,
				columnKeys: colunas,
			});
			break;
		case "excel":
			fileName = titulo + ".xlsx";
			gridOptions.api.exportDataAsExcel({
				fileName: fileName,
				columnKeys: colunas,
			});
			break;
		case "pdf":
			let definicoesDocumento = getDocDefinition(
				printParams("A4"),
				gridOptions.api,
				gridOptions.columnApi,
				"",
				titulo
			);
			pdfMake.createPdf(definicoesDocumento).download(titulo + ".pdf");
			break;
	}
}

function printParams(pageSize) {
	return {
		PDF_HEADER_COLOR: "#ffffff",
		PDF_INNER_BORDER_COLOR: "#dde2eb",
		PDF_OUTER_BORDER_COLOR: "#babfc7",
		PDF_LOGO: BaseURL + "media/img/new_icons/omnilink.png",
		PDF_HEADER_LOGO: "omnilink",
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
	};
}

$(document).ready(function () {
	var dropdown = $("#opcoes_exportacao");
	$("#dropdownMenuButton").click(function () {
		dropdown.toggle();
	});
	$(document).click(function (event) {
		if (
			!dropdown.is(event.target) &&
			event.target.id !== "dropdownMenuButton"
		) {
			dropdown.hide();
		}
	});
});

function preencherExportacoes(gridOptions) {
	const formularioExportacoes = document.getElementById("opcoes_exportacao");
	const opcoes = ["csv", "excel", "pdf"];
	let buttonCSV = BaseURL + "/media/img/new_icons/csv.png";
	let buttonEXCEL = BaseURL + "media/img/new_icons/excel.png";
	let buttonPDF = BaseURL + "media/img/new_icons/pdf.png";
	formularioExportacoes.innerHTML = "";
	opcoes.forEach((opcao) => {
		let button = "";
		let texto = "";
		switch (opcao) {
			case "csv":
				button = buttonCSV;
				texto = "CSV";
				margin = "-5px";
				break;
			case "excel":
				button = buttonEXCEL;
				texto = "Excel";
				margin = "0px";
				break;
			case "pdf":
				button = buttonPDF;
				texto = "PDF";
				margin = "0px";
				break;
		}
		let div = document.createElement("div");
		div.classList.add("dropdown-item");
		div.classList.add("opcao_exportacao");
		div.setAttribute("data-tipo", opcao);
		div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px; color: #1C69AD !important; font-family: 'Mont SemiBold';" title="Exportar no formato ${texto}">${texto}</label>
    `;
		div.style.height = "30px";
		div.style.marginTop = margin;
		div.style.borderRadius = "1px";
		div.style.transition = "background-color 0.3s ease";
		div.addEventListener("mouseover", function () {
			div.style.backgroundColor = "#f0f0f0";
		});
		div.addEventListener("mouseout", function () {
			div.style.backgroundColor = "";
		});
		div.style.border = "1px solid #ccc";
		div.addEventListener("click", function (event) {
			event.preventDefault();
			exportarArquivo(opcao, gridOptions, "Relatório de Últimos Acessos WSTT");
		});
		formularioExportacoes.appendChild(div);
	});
}