var localeText = AG_GRID_LOCALE_PT_BR;
var defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;
var chartBar = false;
var chartLine = false;
var chartBarModal = false;
var chartLineModal = false;

const tableId = "#table";
const paginationSelect = "#select-quantidade-por-pagina-dados";
const getListarRelatorioTecnicos = Router + "/listarRelatorioManutencaoDetalhadoServerSide";

var agGridTable;

$(document).ready(function () {
	$(".btn-expandir").on("click", function (e) {
		e.preventDefault();
		expandirGrid();
	});

	var dropdown = $("#opcoes_exportacao");

	$("#dropdownMenuButton").click(function () {
		dropdown.toggle();
	});

	$(document).click(function (event) {
		if (
			!dropdown.is(event.target) &&
			!$("#dropdownMenuButton").is(event.target) &&
			!$("#dropdownMenuButton").has(event.target).length
		) {
			dropdown.hide();
		}
	});

	$(".opcao_exportacao").click(function () {
		var reportType = $(this).attr("data-tipo");
		getServerSideReport(searchOptions, reportType);
	});

	var searchOptions = {};

	agGridTable = new AgGridTable(
		tableId,
		paginationSelect,
		getListarRelatorioTecnicos,
		true,
		(key, item) => {
			if (item == null || item === "") {
				item = "Não informado";
			}

			return item;
		},
		searchOptions
	);

	agGridTable.updateColumnDefs([
		{
			headerName: "Técnico",
			field: "nomeTecnico",
			minWidth: 420,
			flex: 1,
			suppressSizeToFit: true,
		},
		{
			headerName: "Ação",
			field: "acao",
			width: 140,
			suppressSizeToFit: true,
			cellRenderer: function (options) {
				let data = options.value;
				let acao = "";
				switch (data) {
					case "TENTOU AGENDAR":
						acao =
							'<span class="label label-danger" style="font-size: 11px; background-color: rgba(75, 192, 192, 1);">Tentativa</span>';
						break;
					case "RECUSA":
						acao =
							'<span class="label label-success" style="font-size: 11px; background-color: rgba(54, 162, 235, 1);">Recusa</span>';
						break;
					case "AGENDADO":
						acao =
							'<span class="label label-warning" style="font-size: 11px; background-color: rgba(255, 99, 132, 1);">Agendado</span>';
						break;
					default:
						acao =
							'<span class="label label-default" style="font-size: 11px; background-color: rgba(128, 128, 128, 1);">Não informado</span>';
						break;
				}
				return acao;
			},
		},
		{
			headerName: "Cliente",
			field: "nomeCliente",
			minWidth: 420,
			flex: 1,
			suppressSizeToFit: true,
		},
		{
			headerName: "Cidade Cliente",
			field: "cidadeCliente",
			minWidth: 350,
			flex: 1,
			suppressSizeToFit: true,
		},
		{
			headerName: "Estado Cliente",
			field: "estadoCliente",
			minWidth: 160,
			flex: 1,
			suppressSizeToFit: true,
		},
		{
			headerName: "Descrição",
			field: "descricao",
			minWidth: 420,
			flex: 1,
			suppressSizeToFit: true,
		},
		{
			headerName: "Data",
			field: "data",
			width: 190,
			suppressSizeToFit: true,
		},
	]);

	$("#formBusca").submit(async function (e) {
		e.preventDefault();
		showLoadingPesquisarButton();
		searchOptions = {
			nomeTecnico: $("#tecnicoBusca").val(),
			dataInicial: $("#dataInicial").val(),
			dataFinal: $("#dataFinal").val(),
		};

		if (
			!(
				searchOptions.nomeTecnico ||
				searchOptions.dataInicial ||
				searchOptions.dataFinal
			)
		) {
			resetPesquisarButton();
			showAlert('warning', 'Você precisa inserir algum campo para fazer a buscar.')
			return;
		} else {
			if (searchOptions.dataInicial || searchOptions.dataFinal) {
				if (!searchOptions.dataInicial && searchOptions.dataFinal) {
					showAlert('warning', 'Data inicial obrigatória!');
					resetPesquisarButton();
					return;
				} else if (
					searchOptions.dataInicial &&
					!searchOptions.dataFinal
				) {
					showAlert('warning', 'Data final obrigatória!');
					resetPesquisarButton();
					return;
				} else if (searchOptions.dataInicial > searchOptions.dataFinal) {
					showAlert('warning', 'Data final não pode ser maior que data inicial!');
					resetPesquisarButton();
					return;
				}
			}
		}

		let agGridData = await agGridTable.refreshAgGrid(searchOptions, function () {
			if (
				searchOptions.nomeTecnico &&
				!(searchOptions.dataInicial && searchOptions.dataFinal) &&
				agGridData.length === 0
			) {
				alert("Este técnico não tem dados para os ultimos três meses!");
			}
		});
	});

	$("#BtnLimparFiltro").on("click", async function () {
		showLoadingLimparButton();
		$("#tecnicoBusca").val(null).trigger("change");
		$("#formBusca")[0].reset();
		await agGridTable.refreshAgGrid({}, () => resetLimparButton());
		searchOptions = {};
	});

	$("#menu-relatorio-instalacao").on("click", function () {
		if (!$(this).hasClass("selected")) {
			$(this).addClass("selected");
			$("#menu-dashboard-instalacao").removeClass("selected");
			$(".card-dashboard").hide();
			$(".card-dados").show();
			limparFiltros();
		}
	});

	buscarTecnicos();
});

// Utilitários
let menuAberto = false;
function expandirGrid() {
	menuAberto = !menuAberto;
	let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
	let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;
	if (menuAberto) {
		$(".img-expandir").attr("src", buttonShow);
		$("#filtroBusca").hide();
		$(".menu-interno").hide();
		$("#conteudo").removeClass("col-md-9");
		$("#conteudo").addClass("col-md-12");
	} else {
		$(".img-expandir").attr("src", buttonHide);
		$("#filtroBusca").show();
		$(".menu-interno").show();
		$("#conteudo").removeClass("col-md-12");
		$("#conteudo").addClass("col-md-9");
	}
}

function formatDate(date) {
	dateCalendar = date.split("-");
	return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0];
}

function abrirDropdown(dropdownId, buttonId, tableId) {
	var dropdown = $("#" + dropdownId);
	var posDropdown = dropdown.height() + 10;

	var posBordaTabela = $("#" + tableId + " .ag-body-viewport")
		.get(0)
		.getBoundingClientRect().bottom;
	var posBordaTabelaTop = $("#" + tableId + " .ag-body-viewport")
		.get(0)
		.getBoundingClientRect().top;
	var posButton = $("#" + buttonId)
		.get(0)
		.getBoundingClientRect().bottom;
	var posButtonTop = $("#" + buttonId)
		.get(0)
		.getBoundingClientRect().top;
	if (posDropdown > posBordaTabela - posButton) {
		if (posDropdown < posButtonTop - posBordaTabelaTop) {
			dropdown.css("top", `-${10}px`);
		} else {
			let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
			dropdown.css("top", `-${posDropdown - 60 - diferenca}px`);
		}
	}
}

// Chamadas
async function buscarTecnicos() {
	let tecnicos = await $.ajax({
		url: Router + "/listarTecnicos",
		dataType: "json",
		delay: 1000,
		type: "GET",
		data: function (params) {
			return {
				q: params.term,
			};
		},
		error: function () {
			showAlert('error', 'Erro ao buscar técnicos, tente novamente')
		},
	});

	$("#tecnicoBusca").find("option").get(0).remove();

	$("#tecnicoBusca").select2({
		data: tecnicos.dados,
		placeholder: "Selecione o técnico",
		allowClear: true,
		language: "pt-BR",
		width: "100%",
	});

	$("#tecnicoBusca").val("").trigger("change");

	$("#tecnicoBusca").attr("disabled", false);
}

// Carregamentos
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

function ShowLoadingScreen() {
	$("#loading").show();
}

function HideLoadingScreen() {
	$("#loading").hide();
}

function showLoadingPesquisarButton() {
	$("#BtnPesquisar")
		.html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...')
		.attr("disabled", true);
	$("#BtnLimparFiltro").attr("disabled", true);
}

function resetPesquisarButton() {
	$("#BtnPesquisar")
		.html('<i class="fa fa-search"></i> Pesquisar')
		.attr("disabled", false);
	$("#BtnLimparFiltro").attr("disabled", false);
}

function showLoadingLimparButton() {
	$("#BtnLimparFiltro")
		.html('<i class="fa fa-spinner fa-spin"></i> Limpando...')
		.attr("disabled", true);
	$("#BtnPesquisar").attr("disabled", true);
}

function resetLimparButton() {
	$("#BtnLimparFiltro")
		.html('<i class="fa fa-leaf"></i> Limpar')
		.attr("disabled", false);
	$("#BtnPesquisar").attr("disabled", false);
}

// EXPORTAÇÃO DA TABELA
function getServerSideReport(options, type = "pdf") {
	const requestData = {
		type: type,
		searchOptions: options,
	};

	let acceptType = "application/json";
	let docExtension;

	if (type === "xlsx") {
		acceptType = "application/octet-stream";
		docExtension = "xlsx";
	} else if (type === "pdf") {
		acceptType = "application/pdf";
		docExtension = "pdf";
	}

	axios
		.post(Router + "/generateServerSideReportInstallationDetailed", requestData, {
			headers: {
				"Content-Type": "application/json",
				Accept: acceptType,
			},
			responseType: "blob",
		})
		.then((response) => {
			const contentType = response.headers["content-type"];
			if (contentType.includes("application/json")) {
				showAlert('error', 'Dados não encontrados para os parâmetros de pesquisa informados!')
			} else {
				const url = window.URL.createObjectURL(
					new Blob([response.data])
				);
				const link = document.createElement("a");
				link.href = url;

				link.setAttribute("download", `report.${docExtension}`);
				document.body.appendChild(link);
				link.click();
				link.parentNode.removeChild(link);
			}
		})
		.catch((error) => {
			showAlert('error', 'Erro ao fazer requisição!');
		});
}
