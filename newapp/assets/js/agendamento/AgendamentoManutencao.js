$(document).ready(function () {
	$(".btn-expandir").on("click", function (e) {
		e.preventDefault();
		expandirGrid();
	});

	if (!$(this).hasClass("selected")) {
		limparFiltros();
	}
	showLoadingPesquisarDashboardManutenButton();

	$("#menu-agendamento-manutencao").on("click", function () {
		if (!$(this).hasClass("selected")) {
			$(this).addClass("selected");
			$("#menu-dashboard-manutencao").removeClass("selected");
			$(".card-dashboard-manutencao").hide();
			$(".card-agendamento-manutencao").show();
			$("#formBuscaDashboard").hide();
			$("#formBusca").show();

			limparFiltros();
		}
	});

	$("#menu-dashboard-manutencao").on("click", function () {
		if (!$(this).hasClass("selected")) {
			$(this).addClass("selected");
			$("#menu-agendamento-manutencao").removeClass("selected");
			$(".card-agendamento-manutencao").hide();
			$(".card-dashboard-manutencao").show();
			$("#formBusca").hide();
			$("#formBuscaDashboard").show();

			limparFiltrosDashboard();
		}
	});
});

let form = $("#formBusca").serializeArray();
let dataInicial = form[0]["value"];
let dataFinal = form[1]["value"];

$(document).on("hidden.bs.modal", function (event) {
	

	if ($(".modal:visible").length) {
		$("body").addClass("modal-open");
	}
});

$("#BtnPesquisar").click(function (event) {
	event.preventDefault();
	let btn = $(this);

	var tipoData = $("#tipoData").val();
	var searchOptions = {
		dataInicial: null,
		dataFinal: null,
		id_conversation: null,
		status: null,
	};

	if (
		tipoData === "dateRangeAgendamentoManutencao" &&
		(!$("#dataInicial").val() || !$("#dataFinal").val())
	) {
		showAlert('warning',"Insira um intervalo de datas válido.");
		document.getElementById("loading").style.display = "none";
		resetPesquisarButton();
		return;
	} else if (
		tipoData === "dateRangeAgendamentoManutencao" &&
		$("#dataInicial").val() > $("#dataFinal").val()
	) {
		showAlert('warning',"Insira um intervalo de datas válido.");
		document.getElementById("loading").style.display = "none";
		resetPesquisarButton();
		return;
	} else if (
		tipoData === "conversation_id" &&
		!$("#conversationInput").val()
	) {
		showAlert('warning',"Insira uma conversation válida.");
		document.getElementById("loading").style.display = "none";
		resetPesquisarButton();
		return;
	} else if (tipoData === "status" && !$("#statusInput").val()) {
		showAlert('warning',"Insira um status válido.");
		document.getElementById("loading").style.display = "none";
		resetPesquisarButton();
		return;
	}

	switch (tipoData) {
		case "dateRangeAgendamentoManutencao":
			searchOptions.dataInicial = $("#dataInicial").val();
			searchOptions.dataFinal = $("#dataFinal").val();
			break;
		case "conversation_id":
			searchOptions.id_conversation = $("#conversationInput").val();
			break;
		case "status":
			searchOptions.status = $("#statusInput").val();
			break;
	}

	if (searchOptions != null) {
		atualizarAgGrid(searchOptions);
	}
});

$("#BtnLimparPesquisar").click(limparFiltros);

$("#tipoData").change(function () {
	$(".input-container_ag").hide();

	switch ($(this).val()) {
		case "status":
			$("#statusContainer").show();
			break;
		case "conversation_id":
			$("#conversationContainer").show();
			break;
		case "dateRangeAgendamentoManutencao":
			$("#dateContainer1, #dateContainer2").show();
			break;
		default:
			$("#dateContainer1, #dateContainer2").show();
			break;
	}
});

$("#tipoDataDashboard").change(function () {
	$(".input-container").hide();

	switch ($(this).val()) {
		case "mes":
			$("#mesContainerDashboard").show();
			break;
		case "ano":
			$("#anoContainerDashboard").show();
			break;
		case "periodo":
			$("#periodoContainerDashboard").show();
			break;
		default:
			$("#dateContainer1Dashboard, #dateContainer2Dashboard").show();
			break;
	}
});

$("#BtnPesquisarDashboard").click(async function (event) {
	event.preventDefault();

	$("#emptyMessage").hide();

	let btn = $(this);

	var tipoDataDashboard = $("#tipoDataDashboard").val();
	var searchOptions = {
		dataInicialDashboard: null,
		dataFinalDashboard: null,
		mes: null,
		ano: null,
		periodo: null,
	};

	if (
		tipoDataDashboard === "dateRange" &&
		(!$("#dataInicialDashboard").val() || !$("#dataFinalDashboard").val())
	) {
		showAlert('warning', "Insira um intervalo de datas válido.");
		$("#loadingDashboard").hide();
		resetPesquisarDashboardButton();
		return;
	} else if (
		tipoDataDashboard === "dateRange" &&
		$("#dataInicialDashboard").val() > $("#dataFinalDashboard").val()
	) {
		showAlert('warning', "Insira um intervalo de datas válido.");
		$("#loadingDashboard").hide();
		resetPesquisarDashboardButton();
		return;
	} else if (tipoDataDashboard === "mes" && !$("#mesInputDashboard").val()) {
		showAlert('warning', "Insira um mês válido.");
		$("#loadingDashboard").hide();
		resetPesquisarDashboardButton();
		return;
	} else if (tipoDataDashboard === "ano" && !$("#anoInputDashboard").val()) {
		showAlert('warning', "Insira um ano válido.");
		$("#loadingDashboard").hide();
		resetPesquisarDashboardButton();
		return;
	} else if (
		tipoDataDashboard === "periodo" &&
		!$("#periodoInputDashboard").val()
	) {
		showAlert('warning', "Insira um período válido.");
		$("#loadingDashboard").hide();
		resetPesquisarDashboardButton();
		return;
	}

	switch (tipoDataDashboard) {
		case "dateRange":
			searchOptions.dataInicialDashboard = $(
				"#dataInicialDashboard"
			).val();
			searchOptions.dataFinalDashboard = $("#dataFinalDashboard").val();
			break;
		case "mes":
			searchOptions.mes = $("#mesInputDashboard").val();
			break;
		case "ano":
			searchOptions.ano = $("#anoInputDashboard").val();
			break;
		case "periodo":
			searchOptions.periodo = $("#periodoInputDashboard").val();
			break;
	}

	carregarDashboards(searchOptions, $("#mesInputDashboard").val());
});

$("#BtnLimparDashboard").click(limparFiltrosDashboard);

$(".nav-tabs a").on("click", function (e) {
	var currentAttrValue = $(this).attr("href");
	$(currentAttrValue).addClass("active").siblings().removeClass("active");
	$(this).parent("li").addClass("active").siblings().removeClass("active");
	e.preventDefault();
});

$("#tab-conversas").click(function () {
	$("#tab_conversas").show();
	$("#tab_agenda").hide();
	$("#tab_sms").hide();
});

$("#tab-agenda").click(function () {
	$("#tab_agenda").show();
	$("#tab_conversas").hide();
	$("#tab_sms").hide();
});

$("#tab-sms").click(function () {
	$("#tab_sms").show();
	$("#tab_conversas").hide();
	$("#tab_agenda").hide();
});

// Filter Related
async function carregarDashboards(options = null, option) {
	let btn = $(this);
	showLoadingPesquisarDashboardManutenButton();

	try {
		await Promise.all([
			montarReportRecusaTecnicos(option),
			montarReportRecusaTecnicosTopDez(option),
			montarReportRecusaTecnicosTopDezMenos(option),
			montarDashboard(btn, options),
		]);
	} catch (error) {
		console.error("Erro ao executar as funções do dashboard:", error);
	}

	resetPesquisarDashboardButton();
}

function showLoadingPesquisarDashboardManutenButton() {
	$("#BtnPesquisarDashboard")
		.html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
		.attr("disabled", true);
	$("#BtnLimparDashboard").attr("disabled", true);
}

function resetPesquisarDashboardButton() {
	$("#BtnPesquisarDashboard")
		.html('<i class="fa fa-search"></i> Pesquisar')
		.attr("disabled", false);
	$("#BtnLimparDashboard").attr("disabled", false);
}

function showLoadingPesquisarButton() {
	$("#BtnPesquisar")
		.html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
		.attr("disabled", true);
	$("#BtnLimparPesquisar").attr("disabled", true);
}

function resetPesquisarButton() {
	$("#BtnPesquisar")
		.html('<i class="fa fa-search"></i> Pesquisar')
		.attr("disabled", false);
	$("#BtnLimparPesquisar").attr("disabled", false);
}

function limparFiltros() {
	let btn = $(this);

	$("#formBusca")[0].reset();

	$(".input-container_ag").hide();

	$(
		"#dateContainer1, #dateContainer2, #filtrarPor, .registrosDiv, #tabelaInstalacoes, #dropdown_exportar"
	).show();
	var searchOptions = {
		dataInicial: null,
		dataFinal: null,
		id_conversation: null,
		status: null,
	};
	atualizarAgGrid(searchOptions);
	resetPesquisarButton();
}

async function limparFiltrosDashboard() {
	$("#formBuscaDashboard")[0].reset();

	$("#dataInicialDashboard").val("");
	$("#dataFinalDashboard").val("");
	$("#mesInputDashboard").val("");
	$("#anoInputDashboard").val("");
	$("#periodoInputDashboard").val("7days");
	$("#tipoDataDashboard").val("dateRange");
	$(".input-container").hide();
	$(
		"#dateContainer1Dashboard, #dateContainer2Dashboard, #filtrarPorDashboard"
	).show();

	stopDashboard();
	stopModalDashboard();

	carregarDashboards();
}

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

// Dashboard related

function montarDashboard(btn = null, form = null) {
	return $.ajax({
		url: routeListarDadosDashboardAgendamentoManutencao,
		type: "POST",
		data: {
			searchOptions: form,
		},
		dataType: "json",
		beforeSend: function () {
			if (form != null) {
				btn.attr("disabled", true).html(
					'<i class="fa fa-spin fa-spinner"></i>'
				);
			}
			$("#emptyMessage").hide();
			$("#chartContainer").hide();
			$("#loadingDashboard").show();
		},
		success: function (data) {
			if (data) {
				stopDashboard();
				$("#emptyMessage").hide();
				$("#loadingDashboard").hide();

				btn = form != null ? $(this) : null;

				var dadosObjetos = [];

				Object.keys(data).forEach((mesAno) => {
					const [mes, ano] = mesAno.split("/");
					const nomeMes = nomesMeses[parseInt(mes) - 1];
					const novaLabel = `${nomeMes} de ${ano}`;

					dadosObjetos.push({
						label: novaLabel,
						agendado: data[mesAno]["AGENDADO"],
						atendente: data[mesAno]["ATENDENTE"],
						aguardandoMantenedor:
							data[mesAno]["AGUARDANDO_MANTENEDOR"],
						naoAgendado: data[mesAno]["NAO_AGENDADO"],
						agendado_atendente: data[mesAno]["AGENDADO_ATENDENTE"],
						cancelado_ausente: data[mesAno]["CANCELADO_AUSENTE"],
						em_atendimento: data[mesAno]["EM_ATENDIMENTO"],
						concluido_finalizado:
							data[mesAno]["CONCLUIDO_FINALIZADO"],
						cancelado: data[mesAno]["CANCELADO_TECNICO"],
					});
				});

				dadosObjetos.sort((a, b) => {
					const [mesA, anoA] = a.label.split(" de ");
					const [mesB, anoB] = b.label.split(" de ");

					const indiceMesA = nomesMeses.indexOf(mesA);
					const indiceMesB = nomesMeses.indexOf(mesB);

					if (anoA === anoB) {
						return indiceMesA - indiceMesB;
					} else {
						return anoA - anoB;
					}
				});

				var anos = [];

				dadosObjetos.forEach((item) => {
					var [mes, ano] = item.label.split(" de ");
					item.label = `${mes}`;
					anos.push(`${ano}`);
				});

				function isValid() {
					var valid;
					dadosObjetos.forEach((item) => {
						valid = Object.values(item)
							.slice(1)
							.every((i) => i == 0);
					});
					return valid;
				}

				function gerarLabelAno() {
					var elements = "";
					let tempAnos = [];

					for (let i = 0; i < anos.length; i++) {
						if (tempAnos.indexOf(anos[i]) === -1) {
							elements += `<div style="background-color: rgb(120, 120, 220); width: 40px; height: 15px; border-radius: 5px; color: #FFFFFF"> ${anos[i]}</div>`;
							tempAnos.push(anos[i]);
						}
					}

					return elements;
				}
				if (isValid()) {
					stopDashboard();
					$("#chartContainer").hide();
					$("#emptyMessage").show();
					if (form != null) {
						btn.attr("disabled", false).html(
							'<i class="fa fa-search"></i> Pesquisar'
						);
					}
					document.getElementById("loading").style.display = "none";
				} else {
					stopDashboard();
					//resetPesquisarDashboardButton();
					if (form != null) {
						btn.attr("disabled", false).html(
							'<i class="fa fa-search"></i> Pesquisar'
						);
					}
					$("#chartContainer").show();

					$("#anoAgManutenBar").html(gerarLabelAno());

					const options = {
						container: ctxBar,
						data: dadosObjetos,
						series: [
							{
								type: "bar",
								xKey: "label",
								yKey: "agendado",
								yName: "Agendado",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "atendente",
								yName: "Atendente",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "aguardandoMantenedor",
								yName: "Aguardando Mantenedor",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "naoAgendado",
								yName: "Não Agendado",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "agendado_atendente",
								yName: "Agendado/Atendente",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "cancelado_ausente",
								yName: "Cancelado/Ausente",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "em_atendimento",
								yName: "Em Atendimento",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "concluido_finalizado",
								yName: "Concluído/Finalizado",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "label",
								yKey: "cancelado",
								yName: "Cancelado",
								tooltip: { renderer: renderer },
							},
						],
						axes: [
							{
								type: "category",
								position: "bottom",
								label: {
									fontSize: 10,
									avoidCollisions: true,
								},
							},
							{
								type: "number",
								position: "left",
								label: {},
							},
						],
						background: {
							fill: "transparent",
						},
						padding: {
							top: 20,
							right: 20,
							bottom: 20,
							left: 20,
						},
						label: {
							avoidCollisions: true,
						},
						overlays: defaultOverlay,
					};

					chartBar = agCharts.AgCharts.create(options);
					agCharts.AgCharts.update(chartBar, options);

					$("#chartBarButton").on("click", function () {
						stopModalDashboard();

						$("#agendamentoManutenBarChartModal").modal("show");

						$("#anoAgManutenBarModal").html(gerarLabelAno());

						const optionsModalBar = {
							container: ctxBarModal,
							data: dadosObjetos,
							series: [
								{
									type: "bar",
									xKey: "label",
									yKey: "agendado",
									yName: "Agendado",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "atendente",
									yName: "Atendente",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "aguardandoMantenedor",
									yName: "Aguardando Mantenedor",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "naoAgendado",
									yName: "Não Agendado",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "agendado_atendente",
									yName: "Agendado/Atendente",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "cancelado_ausente",
									yName: "Cancelado/Ausente",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "em_atendimento",
									yName: "Em Atendimento",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "concluido_finalizado",
									yName: "Concluído/Finalizado",
									tooltip: { renderer: renderer },
								},
								{
									type: "bar",
									xKey: "label",
									yKey: "cancelado",
									yName: "Cancelado",
									tooltip: { renderer: renderer },
								},
							],
							axes: [
								{
									type: "category",
									position: "bottom",
									label: {
										fontSize: 10,
										avoidCollisions: true,
									},
								},
								{
									type: "number",
									position: "left",
									label: {},
								},
							],
							background: {
								fill: "transparent",
							},
							padding: {
								top: 20,
								right: 20,
								bottom: 20,
								left: 20,
							},
							label: {
								avoidCollisions: true,
							},
							overlays: defaultOverlay,
						};

						barModalChart =
							agCharts.AgCharts.create(optionsModalBar);
						agCharts.AgCharts.update(
							barModalChart,
							optionsModalBar
						);
					});

					$("#anoAgManutenLine").html(gerarLabelAno());

					const optionsLine = {
						container: ctxLine,
						data: dadosObjetos,
						series: [
							{
								type: "line",
								xKey: "label",
								yKey: "agendado",
								yName: "Agendado",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "atendente",
								yName: "Atendente",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "aguardandoMantenedor",
								yName: "Aguardando Mantenedor",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "naoAgendado",
								yName: "Não Agendado",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "agendado_atendente",
								yName: "Agendado/Atendente",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "cancelado_ausente",
								yName: "Cancelado/Ausente",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "em_atendimento",
								yName: "Em Atendimento",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "concluido_finalizado",
								yName: "Concluído/Finalizado",
								tooltip: { renderer: renderer },
							},
							{
								type: "line",
								xKey: "label",
								yKey: "cancelado",
								yName: "Cancelado",
								tooltip: { renderer: renderer },
							},
						],
						axes: [
							{
								type: "category",
								position: "bottom",
								paddingInner: 0.4,
								label: {
									fontSize: 10,
									avoidCollisions: true,
								},
							},
							{
								type: "number",
								position: "left",
								label: {},
							},
						],
						background: {
							fill: "transparent",
						},
						padding: {
							top: 20,
							right: 20,
							bottom: 20,
							left: 20,
						},
						label: {
							avoidCollisions: true,
						},
						overlays: defaultOverlay,
					};

					chartLine = agCharts.AgCharts.create(optionsLine);
					agCharts.AgCharts.update(chartLine, optionsLine);

					$("#chartLineButton").on("click", function () {
						stopModalDashboard();
						$("#agendamentoManutenLineChartModal").modal("show");

						$("#anoAgManutenLineModal").html(gerarLabelAno());

						const optionsModalLine = {
							container: ctxLineModal,
							data: dadosObjetos,
							series: [
								{
									type: "line",
									xKey: "label",
									yKey: "agendado",
									yName: "Agendado",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "atendente",
									yName: "Atendente",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "aguardandoMantenedor",
									yName: "Aguardando Mantenedor",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "naoAgendado",
									yName: "Não Agendado",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "agendado_atendente",
									yName: "Agendado/Atendente",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "cancelado_ausente",
									yName: "Cancelado/Ausente",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "em_atendimento",
									yName: "Em Atendimento",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "concluido_finalizado",
									yName: "Concluído/Finalizado",
									tooltip: { renderer: renderer },
								},
								{
									type: "line",
									xKey: "label",
									yKey: "cancelado",
									yName: "Cancelado",
									tooltip: { renderer: renderer },
								},
							],
							axes: [
								{
									type: "category",
									position: "bottom",
									label: {
										fontSize: 10,
										avoidCollisions: true,
									},
								},
								{
									type: "number",
									position: "left",
									label: {},
								},
							],
							background: {
								fill: "transparent",
							},
							padding: {
								top: 20,
								right: 20,
								bottom: 20,
								left: 20,
							},
							label: {
								avoidCollisions: true,
							},
							overlays: defaultOverlay,
						};

						lineModalChart =
							agCharts.AgCharts.create(optionsModalLine);
						agCharts.AgCharts.update(
							lineModalChart,
							optionsModalLine
						);
					});
				}
			}
		},
	});
}

function montarReportRecusaTecnicos(monthVal = "") {
	return $.ajax({
		url: routeListarReportRecusaTecnicos,
		type: "POST",
		data: {
			month: monthVal || "",
		},
		dataType: "json",
		success: function (response) {
			if (response["data"] && response["data"] != null) {

				var dadosObjeto = [];

				$("#totalRecusasSubtitle").html(
                    `<div style="
                        width: 12px;
                        height:12px;
                        background-color: #006DF9;
                        border-radius:100%;"
                    >
                    </div>
                    <div> Nenhuma recusa</div>`
                );
 
                var validResponse = !Object.values(response["data"])
                                        .every((i) => i == 0);

				if(validResponse){
					dadosObjeto.push({
						dataIndisponivel: response["data"]["dataIndisponivel"],
						pontoFixo: response["data"]["pontoFixo"],
						operacao: response["data"]["operacao"],
						kmMuitoLonge: response["data"]["kmMuitoLonge"],
						erro: response["data"]["erro"],
						localRisco: response["data"]["localRisco"],
						atestado: response["data"]["atestado"],
						naoAtendeOmnilink: response["data"]["naoAtendeOmnilink"],
						motivoNaoInformado: response["data"]["motivoNaoInformado"],
						totalRecusa: response["data"]["totalRecusa"],
					});

					$("#totalRecusasSubtitle").html(
						`<div style="
							width: 12px; 
							height:12px; 
							background-color: #006DF9; 
							border-radius:100%;"
						> 
						</div> 
						<div> ${dadosObjeto[0].totalRecusa} recusas</div>`
					);
				}

				const [ano, mes] = response["mes"].split("-");
				const nomeMes = nomesMeses[parseInt(mes) - 1];
				const novaLabel = `${nomeMes} de ${ano}`;

				if (chartRecusaManutencao) {
					chartRecusaManutencao.destroy();
				}

				$("#mesRecusas").html(`<div> ${novaLabel}</div>`);

				const options = {
					container: ctxRecusaManutencao,
					data: dadosObjeto,
					series: [
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "dataIndisponivel",
							yName: "Data Indisponível",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "pontoFixo",
							yName: "Ponto Fixo",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "operacao",
							yName: "Operação",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "kmMuitoLonge",
							yName: "Muito Longe",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "erro",
							yName: "Erro",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "localRisco",
							yName: "Local de Risco",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "atestado",
							yName: "Atestado",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "naoAtendeOmnilink",
							yName: "Não Atende a Omnilink",
							tooltip: { renderer: renderer },
						},
						{
							type: "bar",
							xKey: "totalRecusa",
							yKey: "motivoNaoInformado",
							yName: "Motivo Não Informado",
							tooltip: { renderer: renderer },
						},
					],
					background: {
						fill: "transparent",
					},
					overlays: defaultOverlay,
				};

				chartRecusaManutencao = agCharts.AgCharts.create(options);
				agCharts.AgCharts.update(chartRecusaManutencao, options);

				$("#chartMotivosRecusaManutenButton").on("click", function () {
					$("#totalRecusasSubtitleModal").html(
                        `<div style="
                            width: 12px;
                            height:12px;
                            background-color: #006DF9;
                            border-radius:100%;"
                        >
                        </div>
                        <div> Nenhuma recusa</div>`
                    );
 
                    if(validResponse){
                        $("#totalRecusasSubtitleModal").html(
                            `<div style="
                                width: 12px;
                                height:12px;
                                background-color: #006DF9;
                                border-radius:100%;"
                            >
                            </div>
                            <div> ${dadosObjeto[0].totalRecusa} recusas</div>`
                        );
                    }
 

					$("#mesRecusasModal").html(`<div> ${novaLabel}</div>`);
					$("#agendamentoManutenReportRecusaTecnicos").modal("show");

					if (chartRecusaManutencaoModal) {
						chartRecusaManutencaoModal.destroy();
					}

					const optionsModal = {
						container: ctxRecusaManutencaoModal,
						data: dadosObjeto,
						series: [
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "dataIndisponivel",
								yName: "Data Indisponível",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "pontoFixo",
								yName: "Ponto Fixo",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "operacao",
								yName: "Operação",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "kmMuitoLonge",
								yName: "Muito Longe",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "erro",
								yName: "Erro",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "localRisco",
								yName: "Local de Risco",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "atestado",
								yName: "Atestado",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "naoAtendeOmnilink",
								yName: "Não Atende a Omnilink",
								tooltip: { renderer: renderer },
							},
							{
								type: "bar",
								xKey: "totalRecusa",
								yKey: "motivoNaoInformado",
								yName: "Motivo Não Informado",
								tooltip: { renderer: renderer },
							},
						],
						background: {
							fill: "transparent",
						},
						overlays: defaultOverlay,
					};

					chartRecusaManutencaoModal =
						agCharts.AgCharts.create(optionsModal);
					agCharts.AgCharts.update(
						chartRecusaManutencaoModal,
						optionsModal
					);
				});
			}
		},
	});
}

function montarReportRecusaTecnicosTopDez(monthVal = "") {
	return $.ajax({
		url: routeListarReportRecusaTecnicosDezMais,
		type: "POST",
		data: {
			month: monthVal || "",
		},
		dataType: "json",
		success: function (response) {
			if (response["data"] && response["data"] != null) {
				var dadosObjetos = [];

				Object.keys(response["data"]).forEach((item) => {
					dadosObjetos.push({
						nomeTecnico: response["data"][item].nomeTecnico,
						totalRecusa: response["data"][item].totalRecusa,
					});
				});

				const [ano, mes] = response["mes"].split("-");
				const nomeMes = nomesMeses[parseInt(mes) - 1];
				const novaLabel = `${nomeMes} de ${ano}`;

				if (chartMaiorRecusaTecnicosManutencao) {
					chartMaiorRecusaTecnicosManutencao.destroy();
				}

				$("#mesMaiorRecusaTecnicos").html(`<div> ${novaLabel}</div>`);

				const options = {
					container: ctxMaiorRecusaTecnicosManutencao,
					data: dadosObjetos,
					series: [
						{
							type: "pie",
							angleKey: "totalRecusa",
							legendItemKey: "nomeTecnico",
							calloutLabelKey: "nomeTecnico",
							calloutLabel: {
								fontSize: 10,
								fontWeight: "lighter",
								avoidCollisions: true,
							},
							tooltip: {
								renderer: function (params) {
									return (
										'<div class="ag-chart-tooltip-title" style="background-color:' +
										params.color +
										'; font-weight: 900;"> Técnico: ' +
										params.datum[params.calloutLabelKey] +
										"</div>" +
										'<div class="ag-chart-tooltip-content">' +
										"Total: " +
										params.datum[params.angleKey].toFixed(
											0
										) +
										"</div>"
									);
								},
							},
						},
					],
					background: {
						fill: "transparent",
					},
					padding: {
						top: 20,
						right: 20,
						bottom: 20,
						left: 20,
					},
					overlays: defaultOverlay,
				};

				chartMaiorRecusaTecnicosManutencao =
					agCharts.AgCharts.create(options);
				agCharts.AgCharts.update(
					chartMaiorRecusaTecnicosManutencao,
					options
				);

				$("#chartMaiorRecusaTecnicosManutenButton").on(
					"click",
					function () {
						$("#mesMaiorRecusaTecnicosModal").html(
							`<div> ${novaLabel}</div>`
						);
						$("#agendamentoManutenReportTecnicosMaiorRecusa").modal(
							"show"
						);

						if (chartMaiorRecusaTecnicosManutencaoModal) {
							chartMaiorRecusaTecnicosManutencaoModal.destroy();
						}

					const optionsModal = {
						container: ctxMaiorRecusaTecnicosManutencaoModal,
						data: dadosObjetos,
						series: [
							{
								type: "pie",
								angleKey: "totalRecusa",
								modal: true,
								legendItemKey: "nomeTecnico",
								tooltip: {
									renderer: function (params){
										return (
											'<div class="ag-chart-tooltip-content">' + "Total: " +
											params.datum[params.angleKey].toFixed(0) +
											"</div>"
										);
									}
								}
							},
						],
						background: {
							fill: "transparent",
						},
						overlays: defaultOverlay,
					};

						chartMaiorRecusaTecnicosManutencaoModal =
							agCharts.AgCharts.create(optionsModal);
						agCharts.AgCharts.update(
							chartMaiorRecusaTecnicosManutencaoModal,
							optionsModal
						);
					}
				);
			}
		},
	});
}

function montarReportRecusaTecnicosTopDezMenos(monthVal = "") {
	return $.ajax({
		url: routeListarReportRecusaTecnicosDezMenos,
		type: "POST",
		data: {
			month: monthVal || "",
		},
		dataType: "json",
		success: function (response) {
			if (response["data"] && response["data"] != null) {
				var dadosObjetos = [];

				Object.keys(response["data"]).forEach((item) => {
					dadosObjetos.push({
						nomeTecnico: response["data"][item].nomeTecnico,
						totalRecusa: response["data"][item].totalRecusa,
					});
				});

				const [ano, mes] = response["mes"].split("-");
				const nomeMes = nomesMeses[parseInt(mes) - 1];
				const novaLabel = `${nomeMes} de ${ano}`;

				if (chartMenorRecusaTecnicosManutencao) {
					chartMenorRecusaTecnicosManutencao.destroy();
				}

				$("#mesMenorRecusaTecnicos").html(`<div> ${novaLabel}</div>`);

				const options = {
					container: ctxMenorRecusaTecnicosManutencao,
					data: dadosObjetos,
					series: [
						{
							type: "pie",
							angleKey: "totalRecusa",
							legendItemKey: "nomeTecnico",
							calloutLabelKey: "nomeTecnico",
							calloutLabel: {
								fontSize: 10,
								fontWeight: "lighter",
								avoidCollisions: true,
							},
							tooltip: {
								renderer: function (params) {
									return (
										'<div class="ag-chart-tooltip-title" style="background-color:' +
										params.color +
										'; font-weight: 900;"> Técnico: ' +
										params.datum[params.calloutLabelKey] +
										"</div>" +
										'<div class="ag-chart-tooltip-content">' +
										"Total: " +
										params.datum[params.angleKey].toFixed(
											0
										) +
										"</div>"
									);
								},
							},
						},
					],
					background: {
						fill: "transparent",
					},
					padding: {
						top: 20,
						right: 20,
						bottom: 20,
						left: 20,
					},
					overlays: defaultOverlay,
				};

				chartMenorRecusaTecnicosManutencao =
					agCharts.AgCharts.create(options);
				agCharts.AgCharts.update(
					chartMenorRecusaTecnicosManutencao,
					options
				);

				$("#chartMenorRecusaTecnicosManutenButton").on(
					"click",
					function () {
						$("#mesMenorRecusaTecnicosModal").html(
							`<div> ${novaLabel}</div>`
						);
						$("#agendamentoManutenReportTecnicosMenorRecusa").modal(
							"show"
						);

						if (chartMenorRecusaTecnicosManutencaoModal) {
							chartMenorRecusaTecnicosManutencaoModal.destroy();
						}

					const optionsModal = {
						container: ctxMenorRecusaTecnicosManutencaoModal,
						data: dadosObjetos,
						series: [
							{
								type: "pie",
								angleKey: "totalRecusa",
								modal: true,
								legendItemKey: "nomeTecnico",
								tooltip: {
									renderer: function (params){
										return (
											'<div class="ag-chart-tooltip-content">' + "Total: " +
											params.datum[params.angleKey].toFixed(0) +
											"</div>"
										);
									}
								}
							},
						],
						background: {
							fill: "transparent",
						},
						overlays: defaultOverlay,
					};

						chartMenorRecusaTecnicosManutencaoModal =
							agCharts.AgCharts.create(optionsModal);
						agCharts.AgCharts.update(
							chartMenorRecusaTecnicosManutencaoModal,
							optionsModal
						);
					}
				);
			}
		},
	});
}

function stopDashboard() {
	if (chartBar) {
		chartBar.destroy();
	}
	if (chartLine) {
		chartLine.destroy();
	}
}

function stopModalDashboard() {
	if (barModalChart) {
		barModalChart.destroy();
	}
	if (lineModalChart) {
		lineModalChart.destroy();
	}
}

function renderer(params) {
	return (
		'<div class="ag-chart-tooltip-title" style="background-color:' +
		params.color +
		'; font-weight: 900;"> Total: ' +
		params.datum[params.xKey] +
		"</div>" +
		'<div class="ag-chart-tooltip-content">' +
		params.yName +
		": " +
		params.datum[params.yKey].toFixed(0) +
		"</div>"
	);
}

// Agendamento - Tabela
var AgGrid;
function atualizarAgGrid(options) {
	stopAgGRID();

	function getServerSideDados() {
		return {
			getRows: (params) => {
				$.ajax({
					cache: false,
					url: routeListarAgendamentoManutencaoByPeriod,
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
						$("#tabelaInstalacoes").show();
						$("#dropdown_exportar").show();
						$("#notFoundMessage").hide();
						showLoadingPesquisarButton();
					},
					success: function (data) {
						if (data && data.success) {
							var dados = data.rows;
							for (let i = 0; i < dados.length; i++) {
								Object.keys(dados[i]).forEach((key) => {
									if (key == "cliente") {
										Object.defineProperty(
											dados[i],
											"nomeCliente",
											Object.getOwnPropertyDescriptor(
												dados[i],
												"cliente"
											)
										);
										delete dados[i]["cliente"];
									}

									if (key == "cpf_cnpj") {
										Object.defineProperty(
											dados[i],
											"documento",
											Object.getOwnPropertyDescriptor(
												dados[i],
												"cpf_cnpj"
											)
										);
										delete dados[i]["cpf_cnpj"];
									}
								});

								for (let chave in dados[i]) {
									if (chave == "documento") {
										if (
											dados[i][chave] === "" ||
											dados[i][chave] == null
										) {
											dados[i][chave] =
												"Documento não fornecido";
										}
									}

									if (chave == "nomeCliente") {
										if (
											dados[i][chave] === "" ||
											dados[i][chave] == null
										) {
											dados[i][chave] =
												"Nome não fornecido";
										}
									}

									if (chave == "nomeTecnico") {
										if (
											dados[i][chave] === "" ||
											dados[i][chave] == null
										) {
											dados[i][chave] =
												"Nome do Técnico não fornecido";
										}
									}
									if (chave == "createdAt") {
										if (
											dados[i][chave] === "" ||
											dados[i][chave] == null
										) {
											dados[i][chave] =
												"Data não informada";
										}
										dados[i][chave] = new Date(
											dados[i][chave]
										).toLocaleString();
									}
								}
							}
							params.success({
								rowData: dados,
								rowCount: data.lastRow,
							});
						} else if (data && data.message) {
							$(".registrosDiv").hide();
							$("#tabelaInstalacoes").hide();
							$("#dropdown_exportar").hide();
							$("#notFoundMessage").show();
							params.failCallback();
							params.success({
								rowData: [],
								rowCount: 0,
							});
						} else {
							showAlert('error', "Erro na solicitação ao servidor");
							params.failCallback();
							params.success({
								rowData: [],
								rowCount: 0,
							});
						}
						if (options) resetPesquisarButton();
					},
					error: function (error) {
						showAlert('error', "Erro na solicitação ao servidor");
						params.failCallback();
						params.success({
							rowData: [],
							rowCount: 0,
						});
						if (options) resetPesquisarButton();
					},
				});

				$("#loadingMessage").hide();
				if (!options) resetPesquisarButton();
			},
		};
	}

	const gridOptions = {
		columnDefs: [
			{
				headerName: "Documento",
				field: "documento",
				chartDataType: "series",
				width: 230,
				resizable: true,
				suppressSizeToFit: true,
				cellRenderer: function (data) {
					return `
					                    <div style="display: flex; justify-content: space-between; align-items: center;">
					                        <span style="text-align: center; flex-grow: 1; margin-right: 10px;">${data.data["documento"]}</span>
					                        <i class="fa fa-info-circle" style="cursor: pointer; color: black;" title="Informações Adicionais" data-toggle="tooltip" onclick="showModalAndCopy('${data.data["id"]}')"></i>
					                    </div>
					                `;
				},
			},
			{
				headerName: "Nome do Cliente",
				field: "nomeCliente",
				chartDataType: "category",
				width: 260,
				resizable: true,
				suppressSizeToFit: true,
			},
			{
				headerName: "Nome do Técnico",
				field: "nomeTecnico",
				chartDataType: "category",
				width: 260,
				resizable: true,
				suppressSizeToFit: true,
			},
			{
				headerName: "Data de Criação",
				field: "createdAt",
				chartDataType: "category",
				width: 210,
				resizable: true,
				suppressSizeToFit: true,
			},
			{
				headerName: "Status",
				field: "status",
				width: 200,
				resizable: true,
				cellRenderer: function (data) {
					return getStatusLabel(data.value);
				},
			},
			{
				headerName: "Ações",
				width: 80,
				pinned: "right",
				resizable: false,
				cellClass: "actions-button-cell",
				cellRenderer: function (options) {
					var firstRandom = Math.random() * 10;
					var secRandom = Math.random() * 10;
					var thirdRandom = Math.random() * 10;

					var varAleatorioIdBotao =
						(firstRandom * secRandom).toFixed(0) +
						"-" +
						(thirdRandom * firstRandom).toFixed(0) +
						"-" +
						(secRandom * thirdRandom).toFixed(0);

					let data = options.data;
					let tableId = "table";
					let dropdownId = "dropdown-menu-" + varAleatorioIdBotao;
					let buttonId = "dropdownMenuButton_" + varAleatorioIdBotao;

					return `
                    <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${
									BaseURL +
									"media/img/new_icons/icon_acoes.svg"
								}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:visualizarConversa('${
										data.id
									}', '${
						data.nomeCliente
					}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:ModalStatusAgendamento('${
										data.idAgendaManutencao
									}', '${
						data.status
					}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Mudar Status</a>
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
		paginationPageSize: parseInt(
			$("#select-quantidade-por-pagina-dados").val()
		),
		cacheBlockSize: 25,
		localeText: localeText,
		domLayout: "normal",
		rowModelType: "serverSide",
		serverSideStoreType: "partial",
	};

	var gridDiv = document.querySelector("#table");
	gridDiv.style.setProperty("height", "519px");

	AgGrid = new agGrid.Grid(gridDiv, gridOptions);

	$("#select-quantidade-por-pagina-dados")
		.off()
		.change(function () {
			var selectedValue = $("#select-quantidade-por-pagina-dados").val();
			gridOptions.api.paginationSetPageSize(Number(selectedValue));
		});

	gridOptions.api.addEventListener("paginationChanged", function (event) {
		$("#loadingMessage").show();

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

	preencherExportacoes(
		gridOptions,
		"Relatório de Agendamentos de Manutenção"
	);
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

function abrirDropdown(dropdownId, buttonId, tableId) {
	var dropdown = $("#" + dropdownId);

	if (dropdown.is(":visible")) {
		dropdown.hide();
		return;
	}

	$(".dropdown-menu").hide();

	dropdown.show();
	var posDropdown = dropdown.height() + 4;

	var dropdownItems = $("#" + dropdownId + " .dropdown-item-acoes");
	var alturaDrop = 0;
	for (var i = 0; i <= dropdownItems.length; i++) {
		alturaDrop += dropdownItems.height();
	}

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
			dropdown.css("top", `-${alturaDrop - 60}px`);
		} else {
			let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
			dropdown.css(
				"top",
				`-${alturaDrop - 50 - (diferenca + diferenca * 0.5)}px`
			);
		}
	}

	$(document).on("click", function (event) {
		if (!dropdown.is(event.target) && !$("#" + buttonId).is(event.target)) {
			dropdown.hide();
		}
	});
}

// Agendamento - Ações

function ModalStatusAgendamento(id, status) {
	var statusLabel = getStatusLabel(status);
	$("#statusAgendamento").html(statusLabel);
	$("#idAgendamento").val(id);

	$("#ModalStatusAgendamento").modal("show");
}

function getStatusLabel(modalInfoText) {
	var status;
	switch (modalInfoText) {
		case "NAO_AGENDADO":
			status =
				'<span class="badge badge-danger" style="font-size: 11px; background-color: rgba(255, 99, 132, 1);">Não Agendado</span>';
			break;
		case "AGENDADO":
			status =
				'<span class="badge badge-success" style="font-size: 11px; background-color: rgba(75, 192, 192, 1);">Agendado</span>';
			break;
		case "ATENDENTE":
			status =
				'<span class="badge badge-info" style="font-size: 11px; background-color: rgba(52, 152, 219, 1);">Atendente</span>';
			break;
		case "AGUARDANDO_MANTENEDOR":
			status =
				'<span class="badge badge-warning" style="font-size: 11px; background-color: rgba(255, 206, 86, 1);">Aguardando Mantenedor</span>';
			break;
		case "AGENDADO_ATENDENTE":
			status =
				'<span class="badge badge-primary" style="font-size: 11px; background-color: rgba(155, 89, 182, 1);">Agendado/Atendente</span>';
			break;
		case "CANCELADO_AUSENTE":
			status =
				'<span class="badge badge-danger" style="font-size: 11px; background-color: rgba(255, 159, 64, 1);">Cancelado/Ausente</span>';
			break;
		case "EM_ATENDIMENTO":
			status =
				'<span class="badge badge-info" style="font-size: 11px; background-color: rgba(3, 201, 169, 1);">Em Atendimento</span>';
			break;
		case "CONCLUIDO_FINALIZADO":
			status =
				'<span class="badge badge-success" style="font-size: 11px; background-color: rgba(46, 204, 113, 1);">Concluído/Finalizado</span>';
			break;
		case "CANCELADO_TECNICO":
			status =
				'<span class="badge badge-danger" style="font-size: 11px; background-color: rgba(255, 0, 0, 1);">Cancelado</span>';
			break;
		default:
			status =
				'<span class="badge badge-default" style="font-size: 11px;">' +
				modalInfoText +
				"</span>";
	}
	return status;
}

function alterarStatusAgendamento(botao, id, status) {
	btn = $(botao);

	btn.html('<i class="fa fa-spinner fa-spin"></i>').attr("disabled", true);

	// let form = {
	// 	dataInicial: $("#dataInicial").val(),
	// 	dataFinal: $("#dataFinal").val(),
	// };

	var searchOptions = {
		dataInicial: $("#dataInicial").val() || null,
		dataFinal: $("#dataFinal").val() || null,
		id_conversation: null,
		status: null,
	};

	$.ajax({
		url: routeAlterarStatusAgendamentoManutencao,
		type: "POST",
		data: {
			idLinha: id,
			status: status,
		},
		dataType: "json",
		success: function (data) {
			if (data.status === 200) {
				showAlert('success', data.dados.mensagem);
				btn.html("Alterar").attr("disabled", false);

				atualizarAgGrid(searchOptions);
			} else {
				showAlert('error', 
					data?.dados?.mensagem
						? data.dados.mensagem
						: "Erro ao alterar status do agendamento, tente novamente"
				);
				btn.html("Alterar").attr("disabled", false);
			}
		},
		error: function (data) {
			showAlert('error', "Erro ao alterar status do agendamento, tente novamente");
			btn.html("Alterar").attr("disabled", false);
		},
		complete: function () {
			btn.html("Alterar").attr("disabled", false);
			$("#ModalStatusAgendamento").modal("hide");
		},
	});
}

function showModalAndCopy(infoText) {
	$("#modalInfoText").text(infoText);
	$("#infoModal").modal("show");
}

function copyModalInfoToClipboard() {
	const text = $("#modalInfoText").text();
	copyToClipboard(text);
}

async function copyToClipboard(text) {
	try {
		await navigator.clipboard.writeText(text);
		showAlert('success', "Informações copiadas para a área de transferência!");
	} catch (err) {
		showAlert('error', "Falha ao copiar texto: ", err);
	}
}
