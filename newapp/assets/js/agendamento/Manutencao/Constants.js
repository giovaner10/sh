let routeListarAgendamentoManutencaoByPeriod =
	Router + "/listarAgendamentoManutencaoByPeriodServerSide";

let routePegarConversationManutencao = Router + "/pegarConversationManutencao";
let routePegarAgendamentoManutencao = Router + "/pegarAgendamentoManutencao";
let routePegarSMSManutencao = Router + "/pegarSMSManutencao";
let routePegarAuditTrackManutencao = Router + "/pegarAuditTrackManutencao";

let routeListarDadosDashboardAgendamentoManutencao =
	Router + "/listarDadosDashboardAgendamentoManutencao";
let routeAlterarStatusAgendamentoManutencao =
	Router + "/alterarStatusAgendamentoManutencao";

let routeListarReportRecusaTecnicos =
	Router + "/listarDadosRecusaManutencaoTecnicosGlobal";
let routeListarReportRecusaTecnicosDezMais =
    Router + "/listarDadosMaiorRecusaManutencaoTecnicos";
let routeListarReportRecusaTecnicosDezMenos =
    Router + "/listarDadosMenorRecusaManutencaoTecnicos";

const ctxBar = document.getElementById("myChartBar");
const ctxLine = document.getElementById("myChartLine");
const ctxBarModal = document.getElementById("agendamentoManutenBarChart");
const ctxLineModal = document.getElementById("agendamentoManutenLineChart");

const ctxRecusaManutencao = document.getElementById("recusaManutencaoChart");
const ctxRecusaManutencaoModal = document.getElementById(
	"recusaManutencaoChartModal"
);
const ctxMaiorRecusaTecnicosManutencao = document.getElementById(
    "maiorRecusaTecnicosChart"
);
const ctxMaiorRecusaTecnicosManutencaoModal = document.getElementById(
    "maiorRecusaTecnicosChartModal"
);
const ctxMenorRecusaTecnicosManutencao = document.getElementById(
    "menorRecusaTecnicosChart"
);
const ctxMenorRecusaTecnicosManutencaoModal = document.getElementById(
    "menorRecusaTecnicosChartModal"
);

let chartRecusaManutencaoModal;
let chartRecusaManutencao;
let chartMaiorRecusaTecnicosManutencao;
let chartMaiorRecusaTecnicosManutencaoModal;
let chartMenorRecusaTecnicosManutencao;
let chartMenorRecusaTecnicosManutencaoModal;

let lineModalChart;
let barModalChart;
let chartBar;
let chartLine;

var localeText = AG_GRID_LOCALE_PT_BR;
var defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;

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