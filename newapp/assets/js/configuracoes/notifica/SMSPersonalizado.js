var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    $(".btn-expandir").on("click", function (e) {
        e.preventDefault();
        expandirGrid();
    });    

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            descricao: $("#descricaoBusca").val(),
            mensagem: $("#mensagemBusca").val()
        }

        if (searchOptions && (searchOptions.descricao || searchOptions.mensagem)) {
            atualizarAgGrid(searchOptions);
        } else {
            alert('Dados insuficientes para fazer uma busca');
            resetPesquisarButton();
        }
    });

    $('#BtnLimparFiltro').click(function() {
        $('#formBusca').trigger("reset");
        showLoadingLimparButton();
        atualizarAgGrid();
    });

    $('#BtnAdicionar').on('click', function (e) {
        e.preventDefault();
        $('#titleSMS').html('Cadastrar SMS Personalizado');
        limparModal();
        $('#modalSMS').modal('show');
    })

    $('#formSms').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        let id = $('#idSMS').val();

        if (id) {
            $.ajax({
                url: Router + '/edit_message',
                cache: false,
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function () {
                    showLoadingSalvarButton();
                },
                success: function (data) {
                    if (data.success) {
                        exibirAlerta('success', 'Sucesso!', data.msg);
                        atualizarTableSMS();
                        $('#modalSMS').modal('hide');
                    } else {
                        if (data.msg) {
                            exibirAlerta('warning', 'Falha!', data.msg);
                        } else {
                            exibirAlerta('error', 'Erro!', 'Não foi possível editar a mensagem!');
                        }
                    }
                    
                    resetSalvarButton();
                },
                error: function () {
                    exibirAlerta('error', 'Erro!', 'Não foi possível editar a mensagem!');
                    resetSalvarButton();
                }
            });
        } else {
            $.ajax({
                url: Router + '/add_message',
                cache: false,
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function () {
                    showLoadingSalvarButton();
                },
                success: function (data) {
                    if (data.success) {
                        exibirAlerta('success', 'Sucesso!', data.msg);
                        atualizarTableSMS();
                        $('#modalSMS').modal('hide');
                    } else {
                        if (data.msg) {
                            exibirAlerta('warning', 'Falha!', data.msg);
                        } else {
                            exibirAlerta('error', 'Erro!', 'Não foi possível cadastrar a mensagem!');
                        }
                    }
                    
                    resetSalvarButton();
                },
                error: function () {
                    exibirAlerta('error', 'Erro!', 'Não foi possível cadastrar a mensagem!');
                    resetSalvarButton();
                }
            });
        }
    })

    atualizarAgGrid();
})

function exibirAlerta(icon, title, text) {
    Swal.fire({
      position: 'top-start',
      icon: icon,
      title: title,
      text: text,
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
    });
}

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
        //$('#conteudo').css('margin-left', '0px');
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

function abrirSMSDetalhes(id) {
    $.ajax({
        url: Router + '/get_msg_by_id',
        cache: false,
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        beforeSend: function () {
            limparModal();
            $('#titleSMS').html('Editar SMS Personalizado');
            ShowLoadingScreen();
        },
        success: function (data) {
            if (data.success) {
                if (data.mensagem) {
                    let dados = data.mensagem;
                    if (dados.status === '0') {
                        exibirAlerta('warning', 'Falha!', 'A mensagem foi removida! Portanto, não pode ser editada.');
                        atualizarTableSMS();
                    } else {
                        $('#idSMS').val(id);
                        $('#mensagem').val(dados.mensagem);
                        $('#descricao').val(dados.descricao);
                        $('#modalSMS').modal('show');
                    }
                    
                } else {
                    exibirAlerta('warning', 'Falha!', 'Não foi possível obter dados da mensagem!');
                }
            } else {
                exibirAlerta('warning', 'Falha!', data.msg);
            }
            
            HideLoadingScreen();
        },
        error: function () {
            exibirAlerta('error', 'Erro!', 'Não foi possível obter dados da mensagem!');
            HideLoadingScreen();
        }
    });
}

function deleteSMS(id) {
    if (confirm('Você tem certeza que deseja remover essa mensagem?')) {
        $.ajax({
            url: Router + '/delete_message',
            cache: false,
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function () {
                ShowLoadingScreen();
            },
            success: function (data) {
                if (data.success) {
                    exibirAlerta('success', 'Sucesso!', data.msg);
                    atualizarTableSMS();
                    $('#modalSMS').modal('hide');
                } else {
                    if (data.msg) {
                        exibirAlerta('warning', 'Falha!', data.msg);
                    } else {
                        exibirAlerta('error', 'Erro!', 'Não foi possível remover a mensagem!');
                    }
                }
                
                HideLoadingScreen();
            },
            error: function () {
                exibirAlerta('error', 'Erro!', 'Não foi possível remover a mensagem!');
                HideLoadingScreen();
            }
        });
    }
}

var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscar_sms_server_side';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        descricao: options ? options.descricao : '',
                        mensagem: options ? options.mensagem : ''
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
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    // Verifica se o valor é null e substitui por uma string vazia
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                    if (chave === 'best_plate') {
                                        if (dados[i][chave] == '1') {
                                            dados[i][chave] = 'Melhor Placa'
                                        } else {
                                            dados[i][chave] = 'Candidato'
                                        }
                                    }
                                    if (chave === 'tipo_match') {
                                        if (dados[i][chave] == '1') {
                                            dados[i][chave] = 'Hot List';
                                        } else if (dados[i][chave] == '2') {
                                            dados[i][chave] = 'Cold List';
                                        } else {
                                            dados[i][chave] = 'Indefinido';
                                        }
                                    }
                                }
                            }
                            AgGrid.gridOptions.api.hideOverlay();
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
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            alert('Erro na solicitação ao servidor');
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
                        alert('Erro na solicitação ao servidor');
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
                if (!options) resetPesquisarButton();
            },
        };
    }

    function getContextMenuItems(params) {
        if (params && params.node && 'data' in params.node && 'id_msg' in params.node.data) {
            var result = [
                {
                    name: 'Editar',
                    action: () => {
                        abrirSMSDetalhes(params.node.data.id_msg)
                    },
                },
                {
                    name: 'Remover',
                    action: () => {
                        deleteSMS(params.node.data.id_msg)
                    },
                }
            ];
        } else {
            var result = [];
        }

        return result;
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id_msg',
                chartDataType: 'series',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                chartDataType: 'category',
                width: 300,
                suppressSizeToFit: true
            },
            {
                headerName: 'Mensagem',
                field: 'mensagem',
                chartDataType: 'category',
                width: 500,
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options)  {
                    let data = options.data;
                    let tableId = "table";
                    let dropdownId = "dropdown-menu-" + data.id_msg;
                    let buttonId = "dropdownMenuButton_" + data.id_msg;

                    return `
                    <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id_msg}" nome="${data.placa_lida}" id="${data.id_msg}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:abrirSMSDetalhes(${data.id_msg})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:deleteSMS(${data.id_msg})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
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
        getContextMenuItems: getContextMenuItems,
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

function atualizarTableSMS() {
    var searchOptions = {
        descricao: $("#descricaoBusca").val(),
        mensagem: $("#mensagemBusca").val()
    }

    atualizarAgGrid(searchOptions);
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
    $('#btnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Filtrando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#btnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
}

function showLoadingLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-spinner fa-spin"></i> Limpando...').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
}

function showLoadingSalvarButton() {
    $('#btnSalvar').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButton() {
    $('#btnSalvar').html('Salvar').attr('disabled', false);
}

// EXPORTAÇÃO DA TEBELA
function exportarArquivo(tipo, gridOptions, titulo) {
	var colunas = [
		"id_msg",
		"descricao",
		"mensagem",
	];
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
			exportarArquivo(opcao, gridOptions, "Relatório de Mensagem Personalizadas SMS");
		});
		formularioExportacoes.appendChild(div);
	});
}

function limparModal() {
    $('#descricao').val('');
    $('#mensagem').val('');
    $('#idSMS').val('');
}