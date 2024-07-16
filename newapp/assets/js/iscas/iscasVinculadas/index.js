const tableId = "#table";
const paginationSelect = "#paginationSelect";
const getIscasVinculadasRoute = Router + "/getIscasVinculadasServerSide";
var localeText = AG_GRID_LOCALE_PT_BR;

let searchOptions = {};

$(document).ready(function () {
	$("#cnpjBuscarNovoCliente").mask("99.999.999/9999-99");
	$("#cpfBuscarNovoCliente").mask("999.999.999-99");

	$(".buttons-csv").addClass("btn btn-info");
	$(".buttons-excel").addClass("btn btn-info");
	$(".buttons-pdf").addClass("btn btn-info");
	$(".buttons-print").addClass("btn btn-info");

	$(".btn-expandir").on("click", function(e) {
		e.preventDefault();
		menuAberto = !menuAberto;

		if (menuAberto) {
			$(".img-expandir").attr(
				"src",
				`${BaseURL}/assets/images/icon-filter-show.svg`
			);
			$("#filtroBusca").hide();
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
			$("#conteudo").css("margin-left", "0px");
			$("#conteudo").removeClass("col-md-12");
			$("#conteudo").addClass("col-md-9");
		}
	});

	$(window).on("resize", function () {
		$("#coluna_search").select2("close");
	  });

	$("#coluna_search").select2({
        placeholder: "Selecione um filtro",
        allowClear: true,
        language: "pt-BR",
        minimumResultsForSearch: -1
    })

	var dropdown_import = $("#opcoes_acao");

	$("#dropdownMenuButtonAcaoExterna").click(function () {
        dropdown_import.toggle();
    });

	$(document).click(function (event) {
        if (
            !dropdown_import.is(event.target) &&
            event.target.id !== "dropdownMenuButtonAcaoExterna"
        ) {
            dropdown_import.hide();
        }
    });

	atualizarAgGrid([]);

	const dataExpiracaoIsca = new DataExpiracaoIsca(
		AgGrid.gridOptions,
		'data_expiracao',
		"checkIscas",
		"btnExibirModalAlterarDataExpiracao",
		checksIscas
	);

	dataExpiracaoIsca.carregarModalDataExpiracao("modalDataExpiracao");

	dataExpiracaoIsca.getButtonTriggerModal().click(function () {
		dataExpiracaoIsca.exibirModal();
	});

	dataExpiracaoIsca.getForm().submit((event) => {
		event.preventDefault();

		dataExpiracaoIsca.submit(Router + "/ajaxAlterarDataExpiracao");
	});
});

$(".numeric").on("input", function (event) {
	this.value = this.value.replace(/[^0-9]/g, "");
});

$(document).ready(function () {
	var mask = function (val) {
		val = val.split(":");
		return parseInt(val[0]) > 19 ? "HZ:M0:M0" : "H0:M0:M0";
	};

	pattern = {
		onKeyPress: function (val, e, field, options) {
			field.mask(mask.apply({}, arguments), options);
		},
		translation: {
			H: {
				pattern: /[0-2]/,
				optional: false,
			},
			Z: {
				pattern: /[0-3]/,
				optional: false,
			},
			M: {
				pattern: /[0-5]/,
				optional: false,
			},
		},
	};
	$(".time").mask(mask, pattern);
});


function lockButtons() {
	$(".btn")
		.attr("disabled", true);
}

function showLoadingSearchButton() {
	$(".search-button")
		.html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
		.attr("disabled", true);
	$(".clear-search-button").attr("disabled", true);
}

function resetSearchButton() {
	$(".btn")
		.attr("disabled", false);
	$(".search-button")
		.html('<i class="fa fa-search"></i> Pesquisar')
		.attr("disabled", false);
	$(".clear-search-button")
		.html('<i class="fa fa-leaf"></i> Limpar')
		.attr("disabled", false);
}

var AgGrid;
var checksIscas = [];

function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {
                $.ajax({
                    cache: false,
                    url: getIscasVinculadasRoute,
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        searchOptions: options,
                    },
                    dataType: "json",
                    async: true,
                    beforeSend: function () {
                        $(".registrosDiv").show();
                        lockButtons();
                        AgGrid.gridOptions.api.showLoadingOverlay();
                    },
                    success: function (data) {
                        if (data && data.rows.length > 0) {
                            var dados = data.rows;
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                            AgGrid.gridOptions.api.hideOverlay();
                        } else if (data.lastRow == 0) {
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert(
                                "error",
                                "Erro na solicitação ao servidor"
                            );
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        }
                        if (options) resetSearchButton();
                    },
                    error: function (error) {
                        showAlert("error", "Erro na solicitação ao servidor");
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        AgGrid.gridOptions.api.showNoRowsOverlay();
                        if (options) resetSearchButton();
                    },
                });

                $("#loading").hide();
                if (!options) resetSearchButton();
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
				headerName: "Selecionar",
				checkboxSelection: true,
				width: 60,
				colId: "desvincular",
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
                headerName: "ID",
                field: "id",
                filter: true,
                width: 100,
            },
            {
                headerName: "Serial",
                field: "serial",
                filter: true,
                width: 150,
            },
            {
                headerName: "Descrição",
                field: "descricao",
                flex: 0.6,
                minWidth: 200,
            },
            {
                headerName: "Modelo",
                field: "modelo",
                filter: true,
                width: 150,
            },
            {
                headerName: "Marca",
                field: "marca",
                filter: true,
                width: 150,
            },
            {
                headerName: "Data de Cadastro",
                field: "data_cadastro",
                filter: true,
                width: 200,
                cellRenderer: (params) => formatDateTime(params.value),
            },
            {
                headerName: "Data de Expiração",
                field: "data_expiracao",
                filter: true,
                width: 200,
                cellRenderer: (params) => formatDateTime(params.value),
            },
            {
                headerName: "Status",
                field: "status",
                width: 80,
                cellRenderer: (params) => {
                    if (params.value == 1) {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
						`;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
						`;
                    }
                },
            },
            {
                headerName: "Nome",
                field: "nome",
                filter: true,
                width: 150,
            },
            {
                headerName: "ID Contrato",
                field: "id_contrato",
                filter: true,
                width: 150,
            },
            {
                headerName: "Ações",
                pinned: "right",
                minWidth: 85,
                maxWidth: 85,
                cellClass: "actions-button-cell",
                cellRenderer: (params) => {
                    let data = params.data;
                    let dropdownId = "dropdown-menu-" + data.id;
                    let buttonId = "dropdownMenuButton_" + data.id;
                    let tableId = "table";

                    let ajusteAltura = 0;
                    let i = params.rowIndex;
                    let paginaAtual = gridOptions.api.paginationGetCurrentPage();
                    let qtd = $(paginationSelect).val();

                    if (paginaAtual > 0) {
                        i = i - paginaAtual * qtd;
                    }

                    if (i > 9) {
                        i = 9;
                    }

                    if (i > 4) {
                        ajusteAltura = 90;
                    } else {
                        ajusteAltura = 0;
                    }

                    if (
                        data.id_cliente == 0 ||
                        data.id_cliente == null ||
                        data.id_cliente == undefined
                    ) {
                        return `
                    <div>
                        <button class="btn btn-dropdown" type="button" style="margin-top:-6px; width:35px; opacity: 0.5; cursor: default;" disabled>
                            <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                        </button>
                    </div>`;
                    } else {
                        return `
                        <div class="dropdown dropdown-table-comandos-enviados" data-tableId=${tableId}>
                            <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-detalhes-tecnologia" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}% - ${i}px);" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">    
                                    <a title="Migrar" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" id="isca${data.id}" href="javascript:migrarIscaCliente('${data.id_cliente}', '${data.id}')">
                                        Migrar
                                    </a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" title="Desvincular Equipamento" id="desvincularIsca${data.id}" href="javascript:desvincularIsca('${data.id}')">
                                        Desvincular
                                    </a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" title="Mudar Status" id="ativarDesativarIsca${data.id}" href="javascript:ativarDesativarIsca('${data.status}', '${data.id}')">
                                        Mudar Status
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    }
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
        components: {
            formatDateTime,
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
                        width: 100,
                    },
                },
            ],
            defaultToolPanel: false,
        },
        rowSelection: 'multiple',
		suppressRowClickSelection: true,
        popupParent: document.body,
        pagination: true,
		getRowId: (params) => params.data.id,
        paginationPageSize: parseInt($(paginationSelect).val()),
        cacheBlockSize: 25,
        localeText: localeText,
        domLayout: "normal",
        rowModelType: "serverSide",
        serverSideStoreType: "partial",
        onCellClicked: onCellClicked,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
    };

    var gridDiv = document.querySelector("#table");
    gridDiv.style.setProperty("height", "519px");

    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $(paginationSelect)
        .off()
        .change(function () {
            var selectedValue = $(paginationSelect).val();
            gridOptions.api.paginationSetPageSize(Number(selectedValue));
        });

    gridOptions.api.addEventListener("paginationChanged", function (event) {
        $("#loading").show();

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
        $("#loading").hide();
    });

    let datasource = getServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoes(gridOptions);
}

function stopAgGRID() {
    var gridDiv = document.querySelector("#table");
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector(".wrapper");
    if (wrapper) {
        wrapper.innerHTML =
            '<div id="table" class="ag-theme-alpine my-grid"></div>';
    }
}

function onCellClicked(params) {

    if (params.colDef.field === "id") {
        let checkbox = params.event.target.querySelector('input[type="checkbox"]');
        if (checkbox) {
            let checked = checkbox.checked;
            let value = checkbox.value;

            const index = checksIscas.indexOf(value);

            if (checked && index == -1) {
                checksIscas.push(value);
            } else if (!checked && index > -1) {
                checksIscas.splice(index, 1);
            }
        }
    }
}



function formatDateTime(data) {
	return data ? new Date(data).toLocaleString() : "";
}


//GERENCIA A LIBERACAO DO CAMPO DE PESQUISA E DO BOTAO DE PESQUIA DA TABELA
$(document).on("change", "#coluna_search", function (e) {
	e.preventDefault();

	const filtro = $("#coluna_search").val();
	const searchTabela = $("#search_tabela");

	searchTabela.val("");

	const placeholders = {
		"cad_iscas.data_cadastro": "Selecione a data de cadastro",
		"cad_iscas.data_expiracao": "Selecione a data de expiração",
		"cad_iscas.serial": "Digite o serial",
		"cad_iscas.descricao": "Digite a descrição",
		"cad_iscas.modelo": "Digite o modelo",
		"cad_iscas.marca": "Digite a marca",
		"cad_iscas.status": "Selecione o status",
		"cad_clientes.nome": "Digite o nome do cliente",
		"cad_iscas.id_contrato": "Digite o ID do contrato",
		default: "Digite para buscar",
	};

	const placeholder = placeholders[filtro] || placeholders["default"];

	if (
		filtro === "cad_iscas.data_cadastro" ||
		filtro === "cad_iscas.data_expiracao"
	) {
		if (
			searchTabela.prop("tagName").toLowerCase() !== "input" ||
			searchTabela.attr("type") !== "date"
		) {
			searchTabela.replaceWith(
				`<input type="date" id="search_tabela" class="form-control" name="search_tabela" placeholder="${placeholder}">`
			);
		}
	} else if (
		[
			"serial",
			"descricao",
			"modelo",
			"marca",
			"cad_clientes.nome",
			"cad_iscas.id_contrato",
		].includes(filtro)
	) {
		if (
			searchTabela.prop("tagName").toLowerCase() !== "input" ||
			searchTabela.attr("type") !== "text"
		) {
			searchTabela.replaceWith(
				`<input type="search" id="search_tabela" class="form-control" name="search_tabela" placeholder="${placeholder}">`
			);
		} else {
			searchTabela.attr("type", "text");
			searchTabela.attr("placeholder", placeholder);
		}
	} else if (filtro === "cad_iscas.status") {
		if (searchTabela.prop("tagName").toLowerCase() !== "select") {
			searchTabela.replaceWith(
				`<select id="search_tabela" class="form-control">
                    <option value="ativa">Ativa</option>
                    <option value="inativa">Inativa</option>
                </select>`
			);
		}
	} else {
		if (
			searchTabela.prop("tagName").toLowerCase() !== "input" ||
			searchTabela.attr("type") !== "search"
		) {
			searchTabela.replaceWith(
				`<input type="search" id="search_tabela" class="form-control" name="search_tabela" placeholder="${placeholder}">`
			);
		} else {
			searchTabela.attr("type", "search");
			searchTabela.attr("placeholder", placeholder);
		}
	}
});

//EVENTO PARA PESQUISA NA TABELA
$(document).on("click", "#btnBuscarEstoque", function (e) {
	e.preventDefault();
	
	const searchTabela = $("#search_tabela");
	
	if(!searchTabela.val() || searchTabela.val() == ""){
		showAlert("warning", "Digite algum valor de filtro para fazer a busca!");
		return;
	}
	
	showLoadingSearchButton();

	searchOptions = {
		filtro: $("#coluna_search").val() || null,
		search: $("#search_tabela").val() || null,
	};

	atualizarAgGrid(searchOptions);
});

//EVENTO PARA LIMPAR TABELA
$(document).on("click", "#btnResetEstoque", function (e) {
	e.preventDefault();

	lockButtons();

	$(".clear-search-button")
		.html('<i class="fa fa-spinner"></i> Limpando...')

	$('#coluna_search').val(null).trigger('change');

	$("#search_tabela").val("");
	$("#formBusca").each(function () {
		this.reset();
	});

	searchOptions = {};

	atualizarAgGrid(searchOptions);
});

$("#tipoBuscaCliente").change(function () {
	let tipo = $(this).val();
	if (tipo == "cpf") {
		$("#cnpjBuscarNovoCliente").css("display", "none");
		$("#cnpjBuscarNovoCliente").val("");
		$("#idContratoBuscarNovoCliente").css("display", "none");
		$("#idContratoBuscarNovoCliente").val("");
		$("#cpfBuscarNovoCliente").css("display", "block");
	} else if (tipo == "cnpj") {
		$("#cnpjBuscarNovoCliente").css("display", "block");
		$("#cpfBuscarNovoCliente").css("display", "none");
		$("#cpfBuscarNovoCliente").val("");
		$("#idContratoBuscarNovoCliente").css("display", "none");
		$("#idContratoBuscarNovoCliente").val("");
	} else {
		$("#cnpjBuscarNovoCliente").css("display", "none");
		$("#cnpjBuscarNovoCliente").val("");
		$("#cpfBuscarNovoCliente").css("display", "none");
		$("#cpfBuscarNovoCliente").val("");
		$("#idContratoBuscarNovoCliente").css("display", "block");
	}
});

/**
Busca dados do cliente de acordo com o parametro informado (cpf ou cnpj) */
$("#btnBuscarClientePorCNPJ").click(function () {
	button = $(this);
	let cnpj = $("#cnpjBuscarNovoCliente").val();
	let cpf = $("#cpfBuscarNovoCliente").val();
	let id_contrato = $("#idContratoBuscarNovoCliente").val();
	let tipoBusca = $("#tipoBuscaCliente").val();
	let id_isca = $("#id_isca").val();

	let data = {
		cnpj: cnpj,
		cpf: cpf,
		id_contrato: id_contrato,
		tipoBusca: tipoBusca,
		id_isca: id_isca,
	};

	if (tipoBusca == "cnpj" && cnpj == "") {
		showAlert("warning", "Por favor, informe o CNPJ do cliente.");
		return false;
	} else if (tipoBusca == "cpf" && cpf == "") {
		showAlert("warning", "Por favor, informe o CPF do cliente.");
		return false;
	} else if (tipoBusca == "id_contrato" && id_contrato == "") {
		showAlert("warning", "Por favor, informe o ID do contrato do cliente.");
		return false;
	} else {
		// Limpa os dados exibidos ao buscar um novo cliente para fazer a vinculação
		limparDadosNovoCliente();
		button
			.attr("disabled", true)
			.html('<i class="fa fa-spinner fa-spin"></i>');
		$.ajax({
			url: Router + "/getDadosContratoClientePorCpfOuCnpj",
			type: "POST",
			data: data,
			success: function (callback) {
				data = JSON.parse(callback);
				if (data.status == false) {
					alert(data.msg);
				} else {
					$("#nomeNovoCliente").html(data[0].nome);
					if (tipoBusca == "cnpj") {
						$("#labelTipoBusca").html("CNPJ");
						$("#cnpjNovoCliente").html(data[0].cnpj);
					} else if (tipoBusca == "cpf") {
						$("#labelTipoBusca").html("CPF");
						$("#cnpjNovoCliente").html(data[0].cpf);
					} else {
						$("#labelTipoBusca").html("Contrato");
						$("#cnpjNovoCliente").html(data[0].id_contrato);
					}
					$("#enderecoNovoCliente").html(
						`${data[0].endereco}, ${data[0].numero} - ${data[0].bairro}, ${data[0].cidade}.`
					);
					$("#idNovoCliente").val(data[0].id);
					$("#idContratoNovoCliente").val(data[0].id_contrato);

					$("#dadosNovoCliente").css("display", "block");
				}
				button
					.attr("disabled", false)
					.html('<i class="fa fa-search" aria-hidden="true">');
			},
			error: function () {
				showAlert(
					"error",
					"Não foi possível buscar o cliente. Por favor, tente novamente."
				);
				button
					.attr("disabled", false)
					.html('<i class="fa fa-search" aria-hidden="true">');
			},
		});
	}
});

$("#MigrarIsca").click(function () {
	data = {
		id_isca: [$("#id_isca").val()],
		id_cliente: $("#idNovoCliente").val(),
		id_contrato: $("#idContratoNovoCliente").val(),
	};
	if (
		data.id_cliente == "" ||
		data.id_cliente == undefined ||
		data.id_cliente == null
	) {
		showAlert(
			"warning",
			"Informe o Cliente para concluir a transferência."
		);
		return false;
	} else {
		let button = $(this);
		button
			.attr("disabled", true)
			.html('<i class="fa fa-spinner fa-spin"></i> Migrando');
		$.ajax({
			url: Router + "/migrarIscaCliente",
			type: "POST",
			data: data,
			success: function (callback) {
				data = JSON.parse(callback);
				showAlert("success", data.msg);
				button.attr("disabled", false).html("Migrar");
				atualizarAgGrid([]);
				$("#modalMigrarIsca").modal("hide");
			},
			error: function () {
				showAlert(
					"error",
					"Não foi possível buscar o cliente. Por favor, tente novamente."
				);

				button.attr("disabled", false).html("Migrar");
				$("#modalMigrarIsca").modal("hide");
			},
		});
	}
});

function ativarDesativarIsca(statusAtual, id_isca) {
	let textoConfirmacao = statusAtual == 0 ? "Você tem certeza que deseja ativar a isca?" : "Você tem certeza que deseja desativar a isca?";

	Swal.fire({
		title: "Atenção!",
		text: textoConfirmacao,
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#007BFF",
		cancelButtonColor: "#d33",
		confirmButtonText: "Continuar",
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: Router + "/ajaxAtivarDesativarIsca",
				type: "POST",
				data: {
					id_isca: id_isca,
				},
				beforeSend: () => $("#loading").show(),
				success: function (callback) {
					data = JSON.parse(callback);
					showAlert("success", data.msg);
					atualizarAgGrid([]);
				},
				error: function () {
					showAlert("error", "Não foi possível buscar o cliente. Por favor, tente novamente.");
					atualizarAgGrid([]);
				},
				complete: () => $("#loading").hide(),
			});
		}
	});
}


function returnIdContratoCliente(id_contrato) {
	if (id_contrato == 0) return "";
	else return id_contrato;
}

function migrarIscaCliente(id_cliente, id_isca) {
	// Limpa os dados exibidos ao buscar um novo cliente para fazer a vinculação
	limparDadosNovoCliente();

	let button = $("#isca" + id_isca);
	button.attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>');

	$.ajax({
		url: Router + "/getDadosIscaVinculada",
		type: "POST",
		data: {
			id_isca: id_isca,
			id_cliente: id_cliente,
		},
		beforeSend: () => $("#loading").show(),
		success: function (callback) {
			let data = JSON.parse(callback);
			exibirDadosModalMigrarIscaCliente(data);
			button.attr("disabled", false).html("Migrar Equipamento");
		},
		error: function () {
			$("#loading").hide();
			button.attr("disabled", false).html("Migrar Equipamento");
			showAlert(
				"error",
				"Não foi possível exibir os dados refetentes à isca"
			);
		},
		complete: () => $("#loading").hide(),
	});
}

function desvincularIsca(id_isca) {
    if (!id_isca || id_isca.length === 0) {
        showAlert("warning", "Selecione pelo menos uma isca.");
        return false;
    }

    const dados = {
        id_isca: Array.isArray(id_isca) ? id_isca : id_isca.toString().split(",")
    };

    Swal.fire({
        title: "Atenção!",
        text: "Você tem certeza que deseja desvincular a(s) isca(s) do cliente?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: Router + "/desvincularIsca",
                type: "POST",
                data: dados,
                beforeSend: () => $("#loading").show(),
                success: function (callback) {
                    let data = JSON.parse(callback);
                    showAlert(
                        "success",
                        data.msg + (data.falhas.length > 0
                            ? "\nAs seguintes iscas não foram desvinculadas: " + data.falhas
                            : "")
                    );

                    atualizarAgGrid([]);
                },
                error: function () {
                    showAlert("error", "Não foi possível desvincular a isca.");
                },
                complete: () => $("#loading").hide(),
            });
        }
    });
}

$("#btnDesvincular").on("click", () => {
    const selectedNodes = AgGrid.gridOptions.api.getSelectedNodes();
    const selecionados = selectedNodes.map(node => node.data.id);
    desvincularIsca(selecionados);
});


$("#selectAll").click(function () {
    const allNodes = [];
    gridOptions.api.forEachNode(node => allNodes.push(node));

    if ($("#selectAll").is(':checked')) {
        allNodes.forEach(node => {
            node.setSelected(true);
            const value = node.data.id;
            if (checksIscas.indexOf(value) === -1) {
                checksIscas.push(value);
            }
        });
    } else {
        allNodes.forEach(node => {
            node.setSelected(false);
            const value = node.data.id;
            const index = checksIscas.indexOf(value);
            if (index > -1) {
                checksIscas.splice(index, 1);
            }
        });
    }

});

function limparDadosNovoCliente() {
	$("#cnpjBuscarNovoCliente").val("");
	$("#cpfBuscarNovoCliente").val("");
	$("#IdContratoBuscarNovoCliente").val("");
	$("#nomeNovoCliente").html("");
	$("#cnpjNovoCliente").html("");
	$("#enderecoNovoCliente").html("");
	$("#idNovoCliente").val(""), $("#idContratoNovoCliente").val("");
	$("#dadosNovoCliente").css("display", "none");
}

function exibirDadosModalMigrarIscaCliente(dados) {
	$("#id_isca").val(dados.id);
	$("#serialIsca").html(dados.serial);
	$("#descricaoIsca").html(formatarString(dados.descricao));
	$("#modeloIsca").html(formatarString(dados.modelo));
	$("#marcaIsca").html(dados.marca);
	$("#dataCadastroIsca").html(formatDateTime(dados.data_cadastro));
	$("#statusIsca").html(returnStatusAtivoInativo(dados.status));
	$("#nomeCliente").html(dados.nome);
	$("#cnpjCliente").html(dados.cnpj);
	$("#dataContrato").html(formatDate(dados.data_contrato));
	$("#tipoBuscaCliente").val("cnpj");
	$("#cnpjBuscarNovoCliente").css("display", "block");
	$("#cpfBuscarNovoCliente").css("display", "none");
	$("#cpfBuscarNovoCliente").val("");
	$("#idContratoBuscarNovoCliente").css("display", "none");
	$("#idContratoBuscarNovoCliente").val("");
	$("#modalMigrarIsca").modal("show");
}

/**
 * Caso a string ultrapasse 20 caracteres, formata a string para não ultrapassar o limite da coluna
 * @param String
 */
function formatarString(string) {
	if (string.length > 20) {
		return `<div style="cursor:pointer" title="${string}">${string.slice(
			0,
			20
		)}...</div>`;
	} else {
		return string;
	}
}
