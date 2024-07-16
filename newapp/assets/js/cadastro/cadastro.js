var localeText = AG_GRID_LOCALE_PT_BR;
let menuAberto = false;

document.addEventListener("DOMContentLoaded", async function () {

	$(document).on('shown.bs.dropdown', '.dropdown-table', function() {
		var dropdownId = $(this).find('.dropdown-menu').attr('id');
		var buttonId = $(this).find('.btn-dropdown').attr('id');
		var tableId = $(this).attr("data-tableId");
		abrirDropdown(dropdownId, tableId);
	});

  	$('#selectBuscaTipo').select2({
		language: "pt-BR",
		placeholder: "Selecione uma opção",
		minimumResultsForSearch: -1,
		width: '100%'
	});

	criarAgGrid();

	$(".btn-expandir").on("click", function (e) {
		e.preventDefault();
		menuAberto = !menuAberto;

		if (menuAberto) {
		$(".img-expandir").attr(
			"src",
			`${BaseURL}/assets/images/icon-filter-show.svg`
		);
		$("#filtroBusca").hide();
		$("#filtroLista").hide();
		$(".menu-interno").hide();
		$("#conteudo").removeClass("col-md-9");
		$("#conteudo").addClass("col-md-12");
		} else {
		$(".img-expandir").attr(
			"src",
			`${BaseURL}/assets/images/icon-filter-hide.svg`
		);
		$("#filtroBusca").show();
		$(".menu-interno").show();
		$("#filtroLista").show();
		$("#conteudo").css("margin-left", "0px");
		$("#conteudo").removeClass("col-md-12");
		$("#conteudo").addClass("col-md-9");
		}
	});

	$('#selectBuscaTipo').on('change', function(e) {
		let val = $(this).val();
		$('#filtrar-busca').val('');

		if (val == "serial") {
		$('#labelForValor').html('Serial:');
		$('#filtrar-busca').attr('placeholder', 'Digite o serial...');
		} else if (val == 'veiculo') {
			$('#labelForValor').html('Veículo:');
			$('#filtrar-busca').attr('placeholder', 'Digite o veículo...');
		} else if (val == 'code') {
			$('#labelForValor').html('Código:');
			$('#filtrar-busca').attr('placeholder', 'Digite o código...');
		} else if (val == 'cliente') {
			$('#labelForValor').html('Cliente:');
			$('#filtrar-busca').attr('placeholder', 'Digite o cliente...');
		} else {
			$('#labelForValor').html('Placa:');
			$('#filtrar-busca').attr('placeholder', 'Digite a placa...');
		}
	});

	$("#formBusca").on("submit", async function (e) {
		e.preventDefault();
		showLoadingPesquisarButton();
		let searchOptions = {
			coluna: $('#selectBuscaTipo').val(),
			valor: $('#filtrar-busca').val()
		}
		atualizarAgGrid(searchOptions);
	});

	$("#BtnLimparReiniciar").on("click", async function (e) {
		showLoadingLimparButton();
		$("#filtrar-busca").val("");
		atualizarAgGrid();
	});
});

//Ajax
function getServerSideDados(options) {
	return {
		getRows: (params) => {
			var route = Router + '/listarVeiculos';

			$.ajax({
				cache: false,
				url: route,
				type: 'POST',
				data: {
					startRow: params.request.startRow,
					endRow: params.request.endRow,
					coluna: options ? options.coluna : '',
					valor: options ? options.valor : ''
				},
				dataType: 'json',
				async: true,
				beforeSend: function () {
					if (AgGrid) {
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

								if (chave === 'telefone' || chave === 'celular') {
									dados[i][chave] = formatarTelefone(dados[i][chave]);
								}
							}
						}
						AgGrid.gridOptions.api.hideOverlay();
						params.success({
							rowData: dados,
							rowCount: data.lastRow,
						});
					} else if (data && data.message && data.statusCode != 500) {
						showAlert('warning', data.message);
						params.failCallback();
						params.success({
							rowData: [],
							rowCount: 0,
						});
						AgGrid.gridOptions.api.showNoRowsOverlay();
					} else {
						showAlert('error', 'Erro na solicitação ao servidor.');
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
					showAlert('error', 'Erro na solicitação ao servidor.');
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

function atualizarAgGrid(options) {
	let datasource = getServerSideDados(options)
    AgGrid.gridOptions.api.setServerSideDatasource(datasource);
}

// AgGrid
var AgGrid;
function criarAgGrid() {
    stopAgGRID();

    const gridOptions = {
        columnDefs: [
			{
				headerName: "Código",
				field: "codigo",
				minWidth: 200,
				flex: 1,
				chartDataType: "category",
				valueGetter: function (params) {
					return params.data.codigo || "";
				},
			},
			{
				headerName: "Cliente",
				field: "cliente",
				minWidth: 300,
				flex: 1,
				chartDataType: "category",
				valueGetter: function (params) {
					return params.data.cliente || "";
				},
			},
			{
				headerName: "Veículo",
				field: "veiculo",
				minWidth: 200,
				flex: 1,
				chartDataType: "category",
				valueGetter: function (params) {
					return params.data.veiculo || "";
				},
			},
			{
				headerName: "Placa",
				field: "placa",
				minWidth: 200,
				flex: 1,
				chartDataType: "category",
				valueGetter: function (params) {
					return params.data.placa || "";
				},
			},
			{
				headerName: "Serial",
				field: "serial",
				chartDataType: "category",
				minWidth: 300,
				flex: 1,
				valueGetter: function (params) {
					return params.data.serial || "";
				},
			},
			{
				headerName: "Ações",
				width: 90,
				pinned: "right",
				cellClass: "actions-button-cell",
				cellRenderer: function (options) {
					let data = options.data;
		
					let tableId = "tableContatos";
					let buttonId = "dropdownMenuButton_" + data.codigo
		
					const serial = data.serial;
					const placa = data.placa;
					const code_cliente = data.code_cliente;
					const codigo = data.codigo;
					var numeroAleatorio = Math.floor(Math.random() * 10000) + 1;
		
					return `
					<div class="dropdown dropdown-table" data-tableId="${tableId}" style="position: relative;">
						<button id="${buttonId}" class="btn btn-dropdown dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
							<img src="${
								BaseURL + "media/img/new_icons/icon_acoes.svg"
							}" alt="Ações">
						</button>
						<div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="modal_${
							placa ? placa : numeroAleatorio
						}" style="width: 180px; left: -166px; width: 175px; !important">
							
							<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
							<a class="botao_posicao" data-serial="${serial}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Posição</a>
							</div>
		
							<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
							<a href="${coordenadasDiaria}/${placa ? placa : "null"}/${
					code_cliente ? code_cliente : "null"
					}" target="_blank" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Coordenadas Diária</a>
							</div>
		
							<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
							<a href="${coordenadaSemana}/${placa ? placa : "null"}/${
					code_cliente ? code_cliente : "null"
					}" target="_blank" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Coordenadas Semanal</a>
							</div>
		
							${
								administrarVeiculos
								? `<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;"><a data-href="${cadastroVeiculo}/${codigo}" data-code="${codigo}" class="botao_adm"  style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Administrar</a></div>`
								: ""
							}
		
							<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
							<a data-placa="${placa}" class="botao_grupos"  style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Grupos do Veículo</a>
							</div>
		
							<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
							<a data-serial="${serial}" data-code="${codigo}" class="botao_comando"  style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Comando</a>
							</div>
		
							${
								administrarVeiculos
								? `<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;"><a data-serial="${serial}" data-code="${codigo}" data-placa="${placa}" title="Desvincular"  class="botao_desvincular"  style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Desvincular</a></div>`
								: ""
							}
		
						</div>
					</div>`;
				},
			}
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
        paginationPageSize: parseInt($('#select-quantidade-por-contatos-corporativos').val()),
        cacheBlockSize: 100,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
    };

    var gridDiv = document.querySelector('#tableContatos');
    gridDiv.style.setProperty('height', '519px');
    
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-contatos-corporativos').off().change(function () {
        var selectedValue = $('#select-quantidade-por-contatos-corporativos').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

	atualizarAgGrid();
    preencherExportacoes(gridOptions)
}

function abrirDropdown(dropdownId, tableId) {
	var dropdown = $('#' + dropdownId);
	var altDropdown = dropdown.height() + 10;
	dropdown.css('bottom', `auto`).css('top', '100%');

	var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
	var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
	var posDropdown = $('#' + dropdownId).get(0).getBoundingClientRect().bottom;
	var posDropdownTop = $('#' + dropdownId).get(0).getBoundingClientRect().top;

	if (altDropdown > (posBordaTabela - posDropdownTop)) {
		if (altDropdown < (posDropdownTop - posBordaTabelaTop)){
			dropdown.css('top', `auto`).css('bottom', '80%');
		} else {
			let diferenca = altDropdown - (posDropdownTop - posBordaTabelaTop);
			dropdown.css('top', `-${(altDropdown - 60) - (diferenca) }px`);
		}
	}
}

// Carregamento
function stopAgGRID() {
    var gridDiv = document.querySelector('#tableContatos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperContatos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableContatos" class="ag-theme-alpine my-grid"></div>';
    }
}

function showLoadingPesquisarButton() {
  $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Filtrando...').attr('disabled', true);
  $('#BtnLimparReiniciar').attr('disabled', true);
}

function resetPesquisarButton() {
  $('#BtnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
  $('#BtnLimparReiniciar').attr('disabled', false);
}

function showLoadingLimparButton() {
  $('#BtnLimparReiniciar').html('<i class="fa fa-spinner fa-spin"></i> Limpando...').attr('disabled', true);
  $('#BtnPesquisar').attr('disabled', true);
}

function resetLimparButton() {
  $('#BtnLimparReiniciar').html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
  $('#BtnPesquisar').attr('disabled', false);
}