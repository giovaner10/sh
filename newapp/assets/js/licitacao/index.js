var defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;

$(document).ready(function () {
	$(".btn-expandir").on("click", function (e) {
		e.preventDefault();
		expandirGrid();
	});

	var element = $("body > div:nth-child(5) > ul > li:nth-child(2) > a");
	if (element.length > 0) {
		element.attr("href", Router + "/acompanhamento");
	}
	// Inicializa a tela com o menu selecionado
	inicializarTelas();
    displayLineChart();
    
});

// Função para alternar entre telas
function alternarTelas(
	menuSelecionado,
	menuNaoSelecionado,
	telaMostrar,
	telaEsconder
) {
	$(menuSelecionado).addClass("selected");
	$(menuNaoSelecionado).removeClass("selected");
	$(telaMostrar).show();
	$(telaEsconder).hide();
}

// Inicializa as telas com base no menu selecionado
function inicializarTelas() {
	if ($("#menu-relatorio").hasClass("selected")) {
		alternarTelas(
			"#menu-relatorio",
			"#menu-dashboard",
			".card-relatorio",
			".card-dashboard"
		);
	} else {
		alternarTelas(
			"#menu-dashboard",
			"#menu-relatorio",
			".card-dashboard",
			".card-relatorio"
		);
	}
}

// Evento de clique no menu de relatório
$("#menu-relatorio").on("click", function () {
	if (!$(this).hasClass("selected")) {
		alternarTelas(
			"#menu-relatorio",
			"#menu-dashboard",
			".card-relatorio",
			".card-dashboard"
		);
	}
});

// Evento de clique no menu de dashboard
$("#menu-dashboard").on("click", function () {
	if (!$(this).hasClass("selected")) {
		alternarTelas(
			"#menu-dashboard",
			"#menu-relatorio",
			".card-dashboard",
			".card-relatorio"
		);
	}
});

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
		$("#conteudo").css("margin-left", "0px");
		$("#conteudo").removeClass("col-md-12");
		$("#conteudo").addClass("col-md-9");
	}
}

var chartVeiculos;
var chartLicitacoes;
var chartValores;
var chartModalVeiculos;
var chartModalLicitacoes;
var chartModalValores;

function displayLineChart() {
	$.getJSON(Router + "/dash_acompanhamento", function (data) {
		$("#esfera_municipal").html(data.qtd_esfera[2].qtd);
		$("#esfera_estadual").html(data.qtd_esfera[1].qtd);
		$("#esfera_federal").html(data.qtd_esfera[0].qtd);
		$("#load_dash").hide();
		$("#quadros_dash").show();

		$("#tipo_presencial").html(data.qtd_tipo[0].qtd);
		$("#tipo_eletrônico").html(data.qtd_tipo[1].qtd);
		$("#tipo_carona").html(data.qtd_tipo[2].qtd);

        $("#total_licitacoes").html(`${data.qtd_total}`);
        
        

		renderAgChart(
			"myChart1",
			data.grafico_veiculos,
			"Quantidade de Veículos",
			"qtd",
			"Quantidade de Veículos",
			"chartVeiculos"
		);
		renderAgChart(
			"myChart2",
			data.grafico_licitacao,
			"Quantidade de Licitações",
			"qtd",
			"Quantidade de Licitações",
			"chartLicitacoes"
		);
		renderAgChart(
			"myChart3",
			data.grafico_valor,
			"Valores",
			"qtd",
			"Valores",
			"chartValores"
        );
        
        
	});
}

function renderAgChart(
	containerId,
	chartData,
	chartTitle,
	valueKey,
	yAxisTitle,
	chartVariableName
) {
	var labels = [];
	var data = [];
	$.each(chartData, function (key, val) {
		labels.push(val.situacao_final);
		data.push(parseFloat(val[valueKey]));
	});

	if (window[chartVariableName]) {
		window[chartVariableName].destroy();
    }
    
    let itemGrafico = document.getElementById(containerId);

	const options = {
		container: itemGrafico,
		data: labels.map((label, index) => ({
			label: label,
			value: data[index],
		})),
		series: [
			{
				type: "bar",
				xKey: "label",
				yKey: "value",
				yName: yAxisTitle,
			},
		],
		axes: [
			{
				type: "category",
				position: "bottom",
				label: {
					rotation: 0,
				},
			},
			{
				type: "number",
				position: "left",
				title: {
					text: yAxisTitle,
				},
			},
		],
		legend: {
			enabled: false,
		},
	};

    window[chartVariableName] = agCharts.AgCharts.create(options);
    
    $(".downloadChartBtn")
		.off("click")
		.click(function (event) {
			event.preventDefault();
			agCharts.AgCharts.download(window[chartVariableName], {
				width: 800,
				height: 500,
				fileName: "Relatório de Licitação - Gráfico Barra",
			});
		});
}

function abrirModalVeiculos() {
	$("#modalCharts1").modal("show");
	$("#loadingMessage1").show();

	$.getJSON(Router + "/getGraficoVeiculosJson", function (data) {
		renderAgChart(
			"myChartModal1",
			data.grafico_veiculos,
			"Quantidade de Veículos",
			"qtd",
			"Quantidade de Veículos",
			"chartModalVeiculos"
		);
		$("#loadingMessage1").hide();
	});
}

function abrirModalLicitacoes() {
	$("#modalCharts2").modal("show");
	$("#loadingMessage2").show();

	$.getJSON(Router + "/getGraficoLicitacoesJson", function (data) {
		renderAgChart(
			"myChartModal2",
			data.grafico_licitacao,
			"Quantidade de Licitações",
			"qtd",
			"Quantidade de Licitações",
			"chartModalLicitacoes"
		);
		$("#loadingMessage2").hide();
	});
}

function abrirModalValores() {
	$("#modalCharts3").modal("show");
	$("#loadingMessage3").show();

	$.getJSON(Router + "/getGraficoValoresJson", function (data) {
		renderAgChart(
			"myChartModal3",
			data.grafico_valor,
			"Valores",
			"qtd",
			"Valores",
			"chartModalValores"
		);
		$("#loadingMessage3").hide();
	});
}

document.addEventListener("DOMContentLoaded", function () {
	const tabs = document.querySelectorAll("#abasModalProduto .nav-link");
	const tabContents = document.querySelectorAll(".custom-tab-pane");

	tabs.forEach((tab) => {
		tab.addEventListener("click", function (e) {
			e.preventDefault();
			const target = this.getAttribute("data-target");

			tabContents.forEach((content) => {
				content.style.display = "none";
			});

			tabs.forEach((tab) => {
				tab.classList.remove("active-tab");
			});

			document.querySelector(target).style.display = "block";

			this.classList.add("active-tab");
		});
	});

	$("#modalAddLicitacao").on("show.bs.modal", function () {
		document.querySelector("#abasModalProduto .nav-link").click();
	});
});

$(document).ready(function () {
	$("#adicionarLicitacao").on("click", function () {
		$("#modalAddLicitacao").modal("show");
	});

	$("#estado").select2({
		placeholder: "Escolha um estado",
		allowClear: true,
	});

	$("#modalAddLicitacao").on("hide.bs.modal", function (e) {
		document.getElementById("form_licitacao").reset();
		$("#estado").val(null).trigger("change");
	});

	window.changeTipo = function (e) {
		$("#div_plataforma").toggle(e.value !== "0");
	};

	window.changeVeic = function (e) {
		var qtdVeiculos = parseFloat(formatoFloat($("#qtd_veiculos").val()));
		var meses = parseInt($("#meses").val());

		if (!qtdVeiculos) return;

		if (
			formatoFloat($("#valor_unitario_ref").val()) &&
			e.id !== "valor_global_ref"
		) {
			var instalacao =
				parseFloat(formatoFloat($("#valor_instalacao_ref").val())) *
					qtdVeiculos || 0;
			var valorTotal =
				parseFloat(formatoFloat($("#valor_unitario_ref").val())) *
					qtdVeiculos *
					meses +
					instalacao || 0;
			$("#valor_global_ref").maskMoney("mask", valorTotal);
		} else if (formatoFloat($("#valor_global_ref").val())) {
			$("#valor_instalacao_ref").maskMoney("mask", 0);
			var unitarioCalc = (
				parseFloat(formatoFloat($("#valor_global_ref").val())) /
				qtdVeiculos /
				meses
			).toFixed(2);
			$("#valor_unitario_ref").maskMoney("mask", unitarioCalc);
		}

		if (
			formatoFloat($("#valor_unitario_arremate").val()) &&
			e.id !== "valor_global_arremate"
		) {
			var instalacao =
				parseFloat(formatoFloat($("#preco_instalacao").val())) *
					qtdVeiculos || 0;
			var valorArremate =
				parseFloat(formatoFloat($("#valor_unitario_arremate").val())) *
					qtdVeiculos *
					meses +
					instalacao || 0;
			$("#valor_global_arremate").maskMoney("mask", valorArremate);
		} else if (formatoFloat($("#valor_global_arremate").val())) {
			$("#preco_instalacao").maskMoney("mask", 0);
			var unitarioCalc = (
				parseFloat(formatoFloat($("#valor_global_arremate").val())) /
				qtdVeiculos /
				meses
			).toFixed(2);
			$("#valor_unitario_arremate").maskMoney("mask", unitarioCalc);
		}
	};

	$(
		"#valor_unitario_ref, #valor_global_ref, #valor_instalacao_ref, #valor_global_arremate, #valor_unitario_arremate, #preco_instalacao"
	).maskMoney();

	function formatoFloat(valor) {
		if (valor) {
			return valor
				.replace("R$ ", "")
				.replace(/,/g, "")
				.replace(/\./g, "");
		}
		return 0;
	}

	window.alterar_formato_dinheiro = function () {
		$(
			"#preco_instalacao, #valor_unitario_arremate, #valor_global_arremate, #valor_global_ref, #valor_instalacao_ref, #valor_unitario_ref"
		).each(function () {
			this.value = formatoFloat(this.value);
		});
	};

	$("#submit").on("click", function () {
		alterar_formato_dinheiro();

		var formData = {
			orgao: $("#orgao").val(),
			data_licitacao: $("#data_licitacao").val(),
			estado: $("#estado").val(),
			esfera: $("#esfera").val(),
			empresa: $("#empresa").val(),
			tipo: $("#tipo").val(),
			plataforma: $("#plataforma").val(),
			tipo_contrato: $("#tipo_contrato").val(),
			qtd_veiculos: $("#qtd_veiculos").val(),
			descricao_servico: $("#descricao_servico").val(),
			ata_registro_preco: $("#ata_registro_preco").val(),
			situacao_preliminar: $("#situacao_preliminar").val(),
			meses: $("#meses").val(),
			valor_unitario_ref: $("#valor_unitario_ref").val(),
			valor_instalacao_ref: $("#valor_instalacao_ref").val(),
			valor_global_ref: $("#valor_global_ref").val(),
			vencedor: $("#vencedor").val(),
			valor_unitario_arremate: $("#valor_unitario_arremate").val(),
			preco_instalacao: $("#preco_instalacao").val(),
			valor_global_arremate: $("#valor_global_arremate").val(),
			situacao_final: $("#situacao_final").val(),
			observacoes: $("#observacoes").val(),
		};

		if (!formData.orgao) {
			showAlert("warning", "O campo 'Órgão' é obrigatório!");
			return;
		}
		if (!formData.data_licitacao) {
			showAlert("warning", "O campo 'Data da Licitação' é obrigatório!");
			return;
		}
		if (!formData.estado) {
			showAlert("warning", "O campo 'Estado' é obrigatório!");
			return;
		}
		if (!formData.esfera) {
			showAlert("warning", "O campo 'Esfera' é obrigatório!");
			return;
		}
		if (!formData.empresa) {
			showAlert("warning", "O campo 'Empresa' é obrigatório!");
			return;
		}
		if (!formData.tipo) {
			showAlert("warning", "O campo 'Tipo' é obrigatório!");
			return;
		}
		if (!formData.tipo_contrato) {
			showAlert("warning", "O campo 'Tipo de Contrato' é obrigatório!");
			return;
		}
		if (!formData.ata_registro_preco) {
			showAlert(
				"warning",
				"O campo 'Ata de Registro de Preços' é obrigatório!"
			);
			return;
		}

		$("#submit")
			.attr("disabled", true)
			.html('<i class="fa fa-spinner fa-spin"></i>');

		$.ajax({
			url: Router + "/add",
			method: "post",
			data: formData,
			success: function (response) {
				if (response == true) {
					$("#modalAddLicitacao").modal("hide");
					document.getElementById("form_licitacao").reset();
					atualizarAgGrid();
					showAlert("success", "Licitação cadastrada com sucesso!");
				} else {
					showAlert(
						"error",
						"Não foi possível cadastrar a licitação!"
					);
				}
			},
			error: function (xhr, status, error) {
				showAlert("error", "Ocorreu um erro ao cadastrar a licitação.");
			},
			complete: function () {
				$("#submit").attr("disabled", false).html("Salvar");
			},
		});
	});
});

var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
	atualizarAgGrid();
});

let convertToReal = new Intl.NumberFormat("pt-br", {
	style: "currency",
	currency: "BRL",
});

const mapaEsfera = {
	0: "Federal",
	1: "Estadual",
	2: "Municipal",
};

const mapaEmpresa = {
	1: "Norio Momoi",
	2: "Show Tecnologia",
};

const mapaTipo = {
	0: "Presencial",
	1: "Eletrônico",
	2: "Carona",
};

const mapaTipoContrato = {
	0: "Licitação",
	1: "Adesão à ata",
};

const mapaAtaRegistroPreco = {
	0: "Não",
	1: "Sim",
};

const mapaSituacaoPreliminar = {
	0: "Arrematado",
	1: "Perdido",
	2: "Não Participou",
	3: "Suspenso",
	4: "Anulado",
	5: "Em andamento",
};

const mapaSituacaoFinal = {
	0: "Aguardando",
	1: "Contrato Assinado",
	2: "Perdido",
	3: "Suspenso",
	4: "Em andamento",
};

const mapValue = (mapa, valor) => mapa[valor] || "-";

const corrigirDataHora = (dataHora) => {
	const dataHoraCorrigida = new Date(dataHora);
	dataHoraCorrigida.setHours(dataHoraCorrigida.getHours() + 3);
	return dataHoraCorrigida.toLocaleDateString("pt-BR", { timezone: "UTC" });
};

$("#loadingMessage").show();

var AgGridLicitacoes;
var DadosAgGrid = [];

function atualizarAgGrid() {
	$.ajax({
		url: Router + "/getLicitacoesJson",
		type: "POST",
		dataType: "json",
		beforeSend: function () {
			$("#loadingMessage").show();
		},
		success: function (data) {
			var dadosMapeados = data.map(function (item) {
				return {
					ID: item.id || "-",
					Orgao: item.orgao || "-",
					Data_licitacao:
						corrigirDataHora(item.data_licitacao) || "-",
					Estado: item.estado || "-",
					Esfera: mapValue(mapaEsfera, item.esfera),
					Empresa: mapValue(mapaEmpresa, item.empresa),
					Tipo: mapValue(mapaTipo, item.tipo),
					Tipo_contrato: mapValue(
						mapaTipoContrato,
						item.tipo_contrato
					),
					Ata_registro_precos: mapValue(
						mapaAtaRegistroPreco,
						item.ata_registro_preco
					),
					Plataforma: item.plataforma || "-",
					Quantidade_veiculos: item.qtd_veiculos || "-",
					Valor_unitario_ref:
						convertToReal.format(item.valor_unitario_ref) || "-",
					Valor_global_ref:
						convertToReal.format(item.valor_global_ref) || "-",
					Valor_uni_arremate:
						convertToReal.format(item.valor_unitario_arremate) ||
						"-",
					Valor_global_arremate:
						convertToReal.format(item.valor_global_arremate) || "-",
					Valor_instalacao:
						convertToReal.format(item.preco_instalacao) || "-",
					Descricao_servico: item.descricao_servico || "-",
					Vencedor: item.vencedor || "-",
					Status_preliminar: mapValue(
						mapaSituacaoPreliminar,
						item.situacao_preliminar
					),
					Status_final: mapValue(
						mapaSituacaoFinal,
						item.situacao_final
					),
					Observacoes: item.observacoes || "-",
				};
			});

			stopAgGRID();

			const gridOptions = {
				columnDefs: [
					{
						headerName: "ID",
						field: "ID",
						chartDataType: "category",
					},
					{
						headerName: "Orgão",
						field: "Orgao",
						chartDataType: "series",
					},
					{
						headerName: "Data da Licitação",
						field: "Data_licitacao",
						chartDataType: "date",
					},
					{
						headerName: "Estado",
						field: "Estado",
						chartDataType: "series",
					},
					{
						headerName: "Esfera",
						field: "Esfera",
						chartDataType: "series",
					},
					{
						headerName: "Empresa",
						field: "Empresa",
						chartDataType: "series",
					},
					{
						headerName: "Tipo",
						field: "Tipo",
						chartDataType: "series",
					},
					{
						headerName: "Tipo de contrato",
						field: "Tipo_contrato",
						chartDataType: "series",
					},
					{
						headerName: "Ata de registro de preços",
						field: "Ata_registro_precos",
						chartDataType: "series",
					},
					{
						headerName: "Plataforma",
						field: "Plataforma",
						chartDataType: "series",
					},
					{
						headerName: "Quantidade de veículos",
						field: "Quantidade_veiculos",
						chartDataType: "series",
					},
					{
						headerName: "Valor unitário ref.",
						field: "Valor_unitario_ref",
						chartDataType: "series",
					},
					{
						headerName: "Valor Global ref.",
						field: "Valor_global_ref",
						chartDataType: "series",
					},
					{
						headerName: "Valor unitário Arremate",
						field: "Valor_uni_arremate",
						chartDataType: "series",
					},
					{
						headerName: "Valor Global Arremate",
						field: "Valor_global_arremate",
						chartDataType: "series",
					},
					{
						headerName: "Valor Instalação",
						field: "Valor_instalacao",
						chartDataType: "series",
					},
					{
						headerName: "Descrição do serviço",
						field: "Descricao_servico",
						chartDataType: "series",
					},
					{
						headerName: "Vencedor",
						field: "Vencedor",
						chartDataType: "series",
					},
					{
						headerName: "Status preliminar",
						field: "Status_preliminar",
						chartDataType: "series",
					},
					{
						headerName: "Status final",
						field: "Status_final",
						chartDataType: "series",
					},
					{
						headerName: "Observações",
						field: "Observacoes",
						chartDataType: "series",
					},
				],
				defaultColDef: {
					editable: false,
					sortable: true,
					minWidth: 80,
					minHeight: 100,
					filter: true,
					resizable: true,
					suppressMenu: true,
				},
				overlayLoadingTemplate:
					'<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
				popupParent: document.body,
				domLayout: "normal",
				pagination: true,
				localeText: localeText,
				cacheBlockSize: 50,
				paginationPageSize: 10,
			};

			var gridDiv = document.querySelector("#tableAcompanhamento");
			gridDiv.style.setProperty("height", "527px");

			AgGridLicitacoes = new agGrid.Grid(gridDiv, gridOptions);

			gridOptions.api.setRowData(dadosMapeados);
			gridOptions.quickFilterText = "";

			document
				.querySelector("#search-input")
				.addEventListener("input", function () {
					gridOptions.api.setQuickFilter(this.value);
				});

			document
				.querySelector("#select-quantidade-por-pagina")
				.addEventListener("change", function () {
					gridOptions.api.paginationSetPageSize(Number(this.value));
				});

			preencherExportacoes(gridOptions);
			$("#loadingMessage").hide();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error("Erro na requisição AJAX:", textStatus, errorThrown);
			atualizarAgGrid([]);
			$("#loadingMessage").hide();
		},
		complete: function () {
			$("#loadingMessage").hide();
		},
	});
}

function stopAgGRID() {
	var gridDiv = document.querySelector("#tableAcompanhamento");
	if (gridDiv && gridDiv.api) {
		gridDiv.api.destroy();
	}
	var wrapper = document.querySelector(".wrapperAcompanhamento");
	if (wrapper) {
		wrapper.innerHTML =
			'<div id="tableAcompanhamento" class="ag-theme-alpine my-grid-acompanhamento"></div>';
	}
}

function exportarArquivo(tipo, gridOptions) {
	let fileName;
	switch (tipo) {
		case "csv":
			fileName = "licitações.csv";
			gridOptions.api.exportDataAsCsv({ fileName });
			break;
		case "excel":
			fileName = "licitações.xlsx";
			gridOptions.api.exportDataAsExcel({ fileName });
			break;
		case "pdf":
			let dadosExportacao = prepararDadosExportacaoRelatorio();
			let definicoesDocumento = getDocDefinition(
				printParams("A4"),
				gridOptions.api,
				gridOptions.columnApi,
				dadosExportacao.informacoes,
				dadosExportacao.rodape
			);
			pdfMake
				.createPdf(definicoesDocumento)
				.download(dadosExportacao.nomeArquivo);
			break;
	}
}

function prepararDadosExportacaoRelatorio() {
	let informacoes = DadosAgGrid.map((item) => ({
		ID: item.id,
		Orgao: item.orgao,
		Data_licitacao: item.data_licitacao,
		Estado: item.estado,
		Esfera: item.esfera,
		Empresa: item.empresa,
		Tipo: item.tipo,
		Tipo_contrato: item.tipo_contrato,
		Ata_registro_precos: item.ata_registro_preco,
		Plataforma: item.plataforma,
		Quantidade_veiculos: item.qtd_veiculos,
		Valor_unitario_ref: item.valor_unitario_ref,
		Valor_global_ref: item.valor_global_ref,
		Valor_uni_arremate: item.valor_unitario_arremate,
		Valor_global_arremate: item.valor_global_arremate,
		Valor_instalacao: item.preco_instalacao,
		Descricao_servico: item.descricao_servico,
		Vencedor: item.vencedor,
		Status_preliminar: item.situacao_preliminar,
		Status_final: item.situacao_final,
		Observacoes: item.observacoes,
	}));

	let rodape = `Licitações`;
	let nomeArquivo = `Licitações.pdf`;

	return {
		informacoes,
		nomeArquivo,
		rodape,
	};
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

function preencherExportacoes(gridOptions, titulo) {
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
                    <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
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
			exportarArquivo(opcao, gridOptions, titulo);
		});

		formularioExportacoes.appendChild(div);
	});
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
