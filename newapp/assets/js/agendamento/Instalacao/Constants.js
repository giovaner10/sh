let routeAgendamentoListar = Router + "/listarAgendamentosServerSide";
let routePegarAgendamento = Router + "/pegarAgendamento";
let routePegarAgenda = Router + "/pegarAgenda";
let routePegarSMS = Router + "/pegarSMS";
let routePegarAuditTrack = Router + "/pegarAuditTrack";
let routeListarDadosDashboardAgendamento =
	Router + "/listarDadosDashboardAgendamento";
let routeListarReportRecusaTecnicos =
	Router + "/listarDadosRecusaTecnicosGlobal";
let routeListarReportRecusaTecnicosDezMais =
	Router + "/listarDadosMaiorRecusaTecnicos";
let routeListarReportRecusaTecnicosDezMenos =
	Router + "/listarDadosMenorRecusaTecnicos";
let routeAlterarStatusAgendamento = Router + "/alterarStatusAgendamento";

const ctxBar = document.getElementById("myChartBar");
const ctxRecusaInstalacao = document.getElementById("recusaInstalacaoChart");
const ctxRecusaInstalacaoModal = document.getElementById(
	"recusaInstalacaoChartModal"
);
const ctxLine = document.getElementById("myChartLine");
const ctxBarModal = document.getElementById("agendamentoInstBarChart");
const ctxLineModal = document.getElementById("agendamentoInstLineChart");

const ctxMaiorRecusaTecnicosInstalacao = document.getElementById(
	"maiorRecusaTecnicosChart"
);
const ctxMenorRecusaTecnicosInstalacao = document.getElementById(
	"menorRecusaTecnicosChart"
);
const ctxMaiorRecusaTecnicosInstalacaoModal = document.getElementById(
	"maiorRecusaTecnicosChartModal"
);
const ctxMenorRecusaTecnicosInstalacaoModal = document.getElementById(
	"menorRecusaTecnicosChartModal"
);

let lineModalChart;
let barModalChart;

let chartRecusaInstalacaoModal;
let chartRecusaInstalacao;
let chartMaiorRecusaTecnicosInstalacao;
let chartMaiorRecusaTecnicosInstalacaoModal;
let chartMenorRecusaTecnicosInstalacao;
let chartMenorRecusaTecnicosInstalacaoModal;

let chartBar;
let chartLine;
let anoAgInstLineModal = document.getElementById("anoAgInstLineModal");

const nomesMeses = [
	"Janeiro",
	"Fevereiro",
	"Março",
	"Abril",
	"Maio",
	"Junho",
	"Julho",
	"Agosto",
	"Setembro",
	"Outubro",
	"Novembro",
	"Dezembro",
];

var localeText = AG_GRID_LOCALE_PT_BR;
var defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;
