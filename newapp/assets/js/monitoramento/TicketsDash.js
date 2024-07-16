var chartBar = false;
var modalChartBar = false;

$(document).ready(function() {
    $(".btn-expandir").on("click", function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#downloadChart').off('click').click( function(event) {
        event.preventDefault();
        agCharts.AgCharts.download(
            chartBar, 
            { 
                width: 800, 
                height: 500, 
                fileName: 'Quantitativo de Tickets por Status'
            }
        );
    })

    $('#formBusca').submit(async function (e) {
        e.preventDefault();

        var dataInicial = $("#dataInicial").val();
        var dataFinal = $("#dataFinal").val();

        if (new Date(dataInicial) > new Date(dataFinal)) {
            resetPesquisarButton();
            exibirAlerta('warning', "Falha!", "Data Final não pode ser menor que a Data Inicial!")
            return;
        }
        showLoadingPesquisarButton();
        getTickets(formatDate(dataInicial), formatDate(dataFinal));
            
    });

    $('#BtnLimparFiltro').click(function() {
        $('#formBusca').trigger("reset");
        $("#dataInicial").val('');
        $("#dataFinal").val('');
        showLoadingLimparButton();
        getTickets();
    });

    getMetricas();
    getTickets();
});

// Utilitários
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

function formatDate(date) {
    dateCalendar = date.split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0];
}

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

function ajustarAltura() {
    $(".metrica-card").height('auto');
    var heights = [];
    $(".metrica-card").each(function() {
        heights.push($(this).height());
    });
    var maxHeight = Math.max.apply(null, heights);
    $(".metrica-card").height(maxHeight);
}

$(document).ready(function() {

    ajustarAltura();

    $(window).resize(function() {
        ajustarAltura();
    });
});

function carregarChart(dados, dataInicial, dataFinal) {
    data = [];
    
    data.push({
        'nome': 'Aberto',
        'qtd': dados.open ? dados.open : 0
    })

    data.push({
        'nome': 'Andamento',
        'qtd': dados.progress ? dados.progress : 0
    })

    data.push({
        'nome': 'Concluído',
        'qtd': dados.close ? dados.close : 0
    })

    let subtitulo = {
        text: dataInicial ? `Período: ${dataInicial} - ${dataFinal}` : `Período: ${new Date().toLocaleDateString('pt-BR')}`,
        fontWeight: "lighter",
        fontFamily: "Mont",
        color: "#333",
    };

    let objetoData = { quarter: "Tickets" };
    data.forEach((ticket) => {
        objetoData[ticket.nome] = ticket.qtd;
    });

    let series = data.map((ticket) => ({
        type: "bar",
        xKey: "quarter",
        yKey: ticket.nome,
        yName: ticket.nome,
    }));

    const options = {
        container: document.getElementById('myChartBar'),
        theme: {
            baseTheme: "ag-default",
        },
        subtitle: subtitulo,
        data: [objetoData],
        series: series,
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
                text: 'Não há dados para serem exibidos.'
            }
        },
        autoSize: true
    };

    if (chartBar) {
        chartBar.destroy();
    }
    
    chartBar = agCharts.AgCharts.create(options);
}

function carregarModalChart(dados, dataInicial, dataFinal) {
    data = [];
    
    data.push({
        'nome': 'Aberto',
        'qtd': dados.open ? dados.open : 0
    })

    data.push({
        'nome': 'Andamento',
        'qtd': dados.progress ? dados.progress : 0
    })

    data.push({
        'nome': 'Concluído',
        'qtd': dados.close ? dados.close : 0
    })

    let objetoData = { quarter: "Tickets" };
    data.forEach((ticket) => {
        objetoData[ticket.nome] = ticket.qtd;
    });

    let series = data.map((ticket) => ({
        type: "bar",
        xKey: "quarter",
        yKey: ticket.nome,
        yName: ticket.nome,
    }));

    let subtitulo = {
        text: dataInicial ? `Período: ${dataInicial} - ${dataFinal}` : `Período: ${new Date().toLocaleDateString('pt-BR')}`,
        fontWeight: "lighter",
        fontFamily: "Mont",
        color: "#333",
    };

    const options = {
        container: document.getElementById('myModalChartBar'),
        theme: {
            baseTheme: "ag-default",
        },
        subtitle: subtitulo,
        data: [objetoData],
        series: series,
        overlays: {
            noData: {
                text: 'Não há dados para serem exibidos.'
            }
        },
        autoSize: true
    };

    if (modalChartBar) {
        modalChartBar.destroy();
    }
    
    modalChartBar = agCharts.AgCharts.create(options);
}

// Chamadas
async function getMetricas () {

    $.ajax({
        cache: false,
        url: Router + '/amount',
        type: 'GET',
        dataType: 'json',
        beforeSend: function () {
            $('#tickets-suporte').html(' <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> ');
            $('#tickets-comercial').html(' <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> ');
            $('#tickets-financeiro').html(' <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> ');
        },
        success: function (data) {
            if (data) {
                $('#tickets-suporte').text(data.support !== null ? data.support.toLocaleString('pt-BR') : '--');
                $('#tickets-comercial').text(data.commercial !== null ? data.commercial.toLocaleString('pt-BR') : '--');
                $('#tickets-financeiro').text(data.financial !== null ? data.financial.toLocaleString('pt-BR') : '--');
                ajustarAltura();
            } else {
                $('#tickets-suporte').text('--');
                $('#tickets-comercial').text('--');
                $('#tickets-financeiro').text('--');
                exibirAlerta('error', 'Erro!', 'Não foi possível obter as métricas do dashboard. Recarregue a página.');
            }
        },
        error: function () {
            $('#tickets-suporte').text('--');
            $('#tickets-comercial').text('--');
            $('#tickets-financeiro').text('--');
            exibirAlerta('error', 'Erro!', 'Não foi possível obter as métricas do dashboard. Recarregue a página.');
        }
    });

}

async function getTickets(dataInicial, dataFinal) {
    $.ajax({
        cache: false,
        url: Router + '/search',
        type: 'GET',
        data: {
            init: dataInicial,
            end: dataFinal
        },
        dataType: 'json',
        beforeSend: function () {
            $('#tickets-andamento').html(' <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> ');
            $('#tickets-abertos').html(' <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> ');
            $('#tickets-concluidos').html(' <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> ');
            showLoadingChart();
            $('#chartBarButton').off('click');
            $('#clickableArea').addClass('notClickable');
        },
        success: function (data) {
            if (data) {
                $('#tickets-andamento').text(data.progress !== null ? data.progress.toLocaleString('pt-BR') : '--');
                $('#tickets-abertos').text(data.open !== null ? data.open.toLocaleString('pt-BR') : '--');
                $('#tickets-concluidos').text(data.close !== null ? data.close.toLocaleString('pt-BR') : '--');
                ajustarAltura();
                hideLoadingChart();
                carregarChart(data, dataInicial, dataFinal);
                carregarModalChart(data, dataInicial, dataFinal);
                $('#clickableArea').removeClass('notClickable');
                $('#chartBarButton').on('click', function (e) {
                    e.preventDefault();
                    $('#chartModal').modal('show');
                });
            } else {
                $('#tickets-andamento').text('--');
                $('#tickets-abertos').text('--');
                $('#tickets-concluidos').text('--');
                exibirAlerta('error', 'Erro!', 'Não foi possível obter as métricas do dashboard. Recarregue a página.');
            }

            if (dataInicial) {
                resetPesquisarButton();
            } else {
                resetLimparButton();
            }
        },
        error: function () {
            $('#tickets-andamento').text('--');
            $('#tickets-abertos').text('--');
            $('#tickets-concluidos').text('--');
            exibirAlerta('error', 'Erro!', 'Não foi possível obter as métricas do dashboard. Recarregue a página.');
            if (dataInicial) {
                resetPesquisarButton();
            } else {
                resetLimparButton();
            }
        }
    });
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...').attr('disabled', true);
    $('#BtnLimparFiltro').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
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

function showLoadingChart() {
    $('#loadingMessageChartBar').show();
}

function hideLoadingChart() {
    $('#loadingMessageChartBar').hide();
}