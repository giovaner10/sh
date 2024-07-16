var localeText = AG_GRID_LOCALE_PT_BR;
var defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;
var chartBar = false;
var chartLine = false;
var chartBarModal = false;
var chartLineModal = false;

const tableId = "#table";
const paginationSelect = "#select-quantidade-por-pagina-dados";
const getListarRelatorioTecnicos =
	Router + "/listarRelatorioManutencaoConsolidadoServerSide";

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

	var searchOptions = {
		
	};

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
			headerName: "Motivo",
			field: "motivo",
			minWidth: 400,
			flex: 1,
			suppressSizeToFit: true,
		},
		{
			headerName: "Quantidade",
			field: "quantidade",
			width: 120,
			suppressSizeToFit: true,
		},
	]);

	getDados();

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
			showAlert(
				"warning",
				"Você precisa inserir algum campo para fazer a buscar."
			);
			return;
		} else {
			if (searchOptions.dataInicial || searchOptions.dataFinal) {
				if (!searchOptions.dataInicial && searchOptions.dataFinal) {
					showAlert(
						"warning",
						"Data inicial obrigatória!"
					);
					resetPesquisarButton();
					return;
				} else if (
					searchOptions.dataInicial &&
					!searchOptions.dataFinal
				) {
					showAlert(
						"warning",
						"Data final obrigatória!"
					);
					resetPesquisarButton();
					return;
				}
			}
		}

		await agGridTable.refreshAgGrid(searchOptions);
		getDados(searchOptions);
	});

	$("#menu-relatorio-manutencao").on("click", function () {
		if (!$(this).hasClass("selected")) {
			$(this).addClass("selected");
			$("#menu-dashboard-manutencao").removeClass("selected");
			$(".card-dashboard").hide();
			$(".card-dados").show();
			limparFiltros();
		}
	});

	$("#menu-dashboard-manutencao").on("click", function () {
		if (!$(this).hasClass("selected")) {
			$(this).addClass("selected");
			$("#menu-relatorio-manutencao").removeClass("selected");
			$(".card-dados").hide();
			$(".card-dashboard").show();
			limparFiltros();
		}
	});

	$("#downloadChart")
		.off("click")
		.click(function (event) {
			event.preventDefault();
			agCharts.AgCharts.download(chartBarModal, {
				width: 800,
				height: 500,
				fileName: "Relatório de Manutenção - Gráfico Barra",
			});
		});

	$("#downloadChartLine")
		.off("click")
		.click(function (event) {
			event.preventDefault();
			agCharts.AgCharts.download(chartLineModal, {
				width: 800,
				height: 500,
				fileName: "Relatório de Manutenção - Gráfico Linha",
			});
		});

	$("#BtnLimparFiltro").on("click", async function () {
		searchOptions = {};
		await agGridTable.refreshAgGrid({});
		showLoadingLimparButton();
		limparFiltros();
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

function limparFiltros() {
	$("#formBusca")[0].reset();
	$("#tecnicoBusca").val("").trigger("change");
	getDados();
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
		url: Router + "/listarTecnicosManutencao",
		dataType: "json",
		delay: 1000,
		type: "GET",
		data: function (params) {
			return {
				q: params.term,
			};
		},
		error: function () {
			alert("Erro ao buscar técnicos, tente novamente");
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

function getDados(searchOptions) {
	if (searchOptions) {
		$.ajax({
			url: Router + "/listarRelatorioManutencaoSmsPorNomeTecnicoEData",
			type: "POST",
			data: searchOptions,
			dataType: "json",
			beforeSend: function () {
				showLoadingChart();
			},
			success: function (data) {
				if (data.status === 200) {
					atualizarGraficos(data.contagemAcoes, searchOptions);
					hideLoadingChart();
				} else if (data.status === 400) {
					if ("mensagem" in data.results) {
						if (
							data.results.mensagem.search("Falha de Busca") >= 0
						) {
							showAlert(
								"warning",
								"Dados não encontrados para os parâmetros informados."
							);
						} else {
							showAlert(
								"warning",
								data.results.mensagem
							);
						}
					} else {
						showAlert(
							"error",
							"Não foi possível fazer a listagem."
						);
					}

					atualizarGraficos([], searchOptions);
					limparFiltros();
				} else if (data.status === 404) {
					showAlert(
						"warning",
						"Dados não encontrados para os parâmetros informados."
					);

					atualizarGraficos([], searchOptions);
					hideLoadingChart();
				} else {
					showAlert(
						"error",
						"Não foi possível fazer a listagem."
					);

					atualizarGraficos([], searchOptions);
					hideLoadingChart();
				}
			},
			error: function () {
				showAlert(
					"error",
					"Não foi possível fazer a listagem."
				);

				atualizarGraficos([], searchOptions);
				hideLoadingChart();
			},
			complete: function () {
				resetPesquisarButton();
			},
		});
	} else {
		$.ajax({
			url: Router + "/listarUltimosCemRelatorioTecnicosManutencao",
			type: "GET",
			dataType: "json",
			beforeSend: function () {
				showLoadingChart();
			},
			success: function (data) {
				if (data.status === 200) {
					atualizarGraficos(data.contagemAcoes);
				} else if (data.status === 400) {
					if ("mensagem" in data.results) {
						if (data.results.mensagem.search("Falha de Busca")) {
							showAlert(
								"warning",
								"Dados não encontrados para os parâmetros informados."
							);
						} else {
							showAlert(
								"warning",
								data.results.mensagem
							);
						}
					} else {
						showAlert(
							"error",
							"Não foi possível fazer a listagem."
						);
					}

					atualizarGraficos([], searchOptions);
				} else if (data.status === 404) {
					showAlert(
						"warning",
						"Dados não encontrados para os parâmetros informados."
					);

					atualizarGraficos([], searchOptions);
				} else {
					showAlert(
						"error",
						"Não foi possível fazer a listagem."
					);

					atualizarGraficos([], searchOptions);
				}
			},
			error: function () {
				showAlert(
					"error",
					"Não foi possível fazer a listagem."
				);

				atualizarGraficos([], searchOptions);
			},
			complete: function () {
				hideLoadingChart();
				resetLimparButton();
			},
		});
	}
}


function atualizarGraficos(dados, searchOptions) {
	const traducoes = {
		ATTEMPT: "Tentativa",
		CLOSE: "Fechar",
		OPEN: "Abrir",
		SUCCESS: "Sucesso",
		ACCEPT: "Aceitar",
		CANCEL: "Cancelar",
		NULL: "Não informado",
	};

	let subtitulo = {
		text: searchOptions
			? `Período: ${searchOptions.dataInicial} - ${searchOptions.dataFinal} | Técnico: ${searchOptions.nomeTecnico}`
			: `Top 100`,
		fontWeight: "lighter",
		fontFamily: "Mont",
		color: "#333",
	};

	dados = Object.keys(dados).map((acao) => ({
		nome: traducoes[acao],
		quantidade: dados[acao],
	}));

	let objetoData = { quarter: "Ações" };
	dados.forEach((acoes) => {
		objetoData[acoes.nome] = acoes.quantidade;
	});

	let seriesBar = dados.map((acoes) => ({
		type: "bar",
		xKey: "quarter",
		yKey: acoes.nome,
		yName: acoes.nome,
		tooltip: {
			renderer: function (data) {
				let datum = data.datum;
				return {
					title: data.yName,
					content: "Qtd. de Ações: " + datum[data.yKey].toFixed(0),
				};
			},
		},
	}));

	let seriesLine = dados.map((acoes) => ({
		type: "line",
		xKey: "quarter",
		yKey: acoes.nome,
		yName: acoes.nome,
	}));

	const optionsBar = {
		container: document.getElementById("myChartBar"),
		theme: {
			baseTheme: "ag-default",
			palette: {
				fills: [
					"rgba(75, 192, 192, 1)",
					"rgba(54, 162, 235, 1)",
					"rgba(255, 206, 86, 1)",
					"rgba(255, 99, 132, 1)",
					"rgba(153, 102, 255, 1)",
					"rgba(255, 159, 64, 1)",
					"rgba(128, 128, 128, 1)",
				],
				strokes: ["black"],
			},
		},
		subtitle: subtitulo,
		data: [objetoData],
		series: seriesBar,
		axes: [
			{
				type: "category",
			},
			{
				type: "number",
			},
		],
		overlays: {
			noData: {
				text: "Não há dados para serem exibidos.",
			},
		},
		autoSize: true,
		overlays: defaultOverlay,
	};

	const optionsLine = {
		container: document.getElementById("myChartLine"),
		theme: {
			baseTheme: "ag-default",
		},
		subtitle: subtitulo,
		data: dados,
		series: [
			{
				type: "line",
				xKey: "nome",
				yKey: "quantidade",
				yName: "Ações",
				tooltip: {
					renderer: function (data) {
						let datum = data.datum;
						return {
							title: datum[data.xKey],
							content:
								"Qtd. de Ações: " + datum[data.yKey].toFixed(0),
						};
					},
				},
			},
		],
		axes: [
			{
				type: "category",
			},
			{
				type: "number",
			},
		],
		overlays: {
			noData: {
				text: "Não há dados para serem exibidos.",
			},
		},
		autoSize: true,
		overlays: defaultOverlay,
	};

	const optionsBarModal = {
		container: document.getElementById("myModalChartBar"),
		theme: {
			baseTheme: "ag-default",
			palette: {
				fills: [
					"rgba(75, 192, 192, 1)",
					"rgba(54, 162, 235, 1)",
					"rgba(255, 206, 86, 1)",
					"rgba(255, 99, 132, 1)",
					"rgba(153, 102, 255, 1)",
					"rgba(255, 159, 64, 1)",
					"rgba(128, 128, 128, 1)",
				],
				strokes: ["black"],
			},
		},
		subtitle: subtitulo,
		data: [objetoData],
		series: seriesBar,
		axes: [
			{
				type: "category",
			},
			{
				type: "number",
			},
		],
		overlays: {
			noData: {
				text: "Não há dados para serem exibidos.",
			},
		},
		autoSize: true,
		overlays: defaultOverlay,
	};

	const optionsLineModal = {
		container: document.getElementById("myModalCharLinha"),
		theme: {
			baseTheme: "ag-default",
		},
		subtitle: subtitulo,
		data: dados,
		series: [
			{
				type: "line",
				xKey: "nome",
				yKey: "quantidade",
				yName: "Ações",
				tooltip: {
					renderer: function (data) {
						let datum = data.datum;
						return {
							title: datum[data.xKey],
							content:
								"Qtd. de Ações: " + datum[data.yKey].toFixed(0),
						};
					},
				},
			},
		],
		axes: [
			{
				type: "category",
			},
			{
				type: "number",
			},
		],
		overlays: {
			noData: {
				text: "Não há dados para serem exibidos.",
			},
		},
		autoSize: true,
		overlays: defaultOverlay,
	};

	if (chartBar) {
		chartBar.destroy();
	}

	if (chartLine) {
		chartLine.destroy();
	}

	if (chartBarModal) {
		chartBarModal.destroy();
	}

	if (chartLineModal) {
		chartLineModal.destroy();
	}

	chartBar = agCharts.AgCharts.create(optionsBar);
	chartLine = agCharts.AgCharts.create(optionsLine);
	chartBarModal = agCharts.AgCharts.create(optionsBarModal);
	chartLineModal = agCharts.AgCharts.create(optionsLineModal);
}

// // Carregamentos

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

function showLoadingChart() {
	$("#loadingMessageChartBar").show();
}

function hideLoadingChart() {
	$("#loadingMessageChartBar").hide();
}

function showLoadingChart() {
	$("#loadingMessageChartBar").show();
	$("#loadingMessageChartLine").show();
	$("#chartBarButton").off("click");
	$("#chartLineButton").off("click");
}

function hideLoadingChart() {
	$("#loadingMessageChartBar").hide();
	$("#loadingMessageChartLine").hide();
	$("#chartBarButton").on("click", function (e) {
		e.preventDefault();
		$("#chartModal").modal("show");
	});
	$("#chartLineButton").on("click", function (e) {
		e.preventDefault();
		$("#chartLineModal").modal("show");
	});
}

// EXPORTAÇÃO DA TABELA


function getServerSideReport(options, type = "pdf") {
    const requestData = {
        type: type,
        searchOptions: options,
    };

    let acceptType = "application/json";
    let docExtension;

    if (type === 'xlsx') {
        acceptType = "application/octet-stream"
        docExtension = 'xlsx';
    } else if (type === 'pdf') {
        acceptType = 'application/pdf';
        docExtension = 'pdf';
    }

    axios.post(Router + "/generateServerSideReport", requestData, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': acceptType
        },
        responseType: 'blob'
    })
    .then(response => {
        const contentType = response.headers['content-type'];
        if (contentType.includes('application/json')) {
            showAlert(
                "error",
                "Dados não encontrados para os parâmetros de pesquisa informados!"
            );
        } else {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;

            link.setAttribute('download', `report.${docExtension}`); 
            document.body.appendChild(link);
            link.click();
            link.parentNode.removeChild(link);
        }
    })
    .catch(error => {
        showAlert(
            "error",
            "Erro ao fazer requisição!"
        );
    });
}



