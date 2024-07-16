var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function() {
    atualizarAgGridCintas([]);
    atualizarAgGridCarregadores([]);
    atualizarAgGridPowerbanks([]);

    $('.btn-expandir-cintas').on('click', function(e) {
        e.preventDefault();
        manipularMenuNodesCintas();
    });

    $('.btn-expandir-powerbanks').on('click', function(e) {
        e.preventDefault();
        manipularMenuNodesPowerbanks();
    });

    $('.btn-expandir-carregadores').on('click', function(e) {
        e.preventDefault();
        manipularMenuNodesCarregadores();
    });

    $('#menu_cintas').addClass('selected');

    $('#menu_cintas').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu_powerbanks').removeClass( "selected" );
            $('#menu_carregadores').removeClass( "selected" );
            $('.card-powerbanks').hide()
            $('.card-carregadores').hide()
            $('.card-cintas').show()
        }
    });

    $('#menu_powerbanks').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu_cintas').removeClass( "selected" );
            $('#menu_carregadores').removeClass( "selected" );
            $('.card-cintas').hide()
            $('.card-carregadores').hide()
            $('.card-powerbanks').show()
        }
    });

    $('#menu_carregadores').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu_cintas').removeClass( "selected" );
            $('#menu_powerbanks').removeClass( "selected" );
            $('.card-cintas').hide()
            $('.card-powerbanks').hide()
            $('.card-carregadores').show()
        }
    });

    $('#button_add_cinta').on('click', function() {
        $('#modalCadCinta').modal('show');
    })

    $('#button_add_powerbank').on('click', function() {
        $('#modalCadPowerbank').modal('show');
    })
    
    $('#button_add_carregador').on('click', function() {
        $('#modalCadCarregador').modal('show');
    })

    $('#fechar_cinta, #close_cinta').on('click', function() {
       limparCampos();
    });

    $('#fechar_carregador, #close_carregador').on('click', function() {
        limparCampos();
    });

    $('#fechar_powerbank, #close_powerbank').on('click', function() {
        limparCampos();
    });
    

    $("#loadingMessage").show();

    $.ajax({
        cache: false,
        url: Router+'/ajax_cintas',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $("#loadingMessage").hide();
            if (data) {
                DadosAgGridCintas = data.data.map(function(dado, index) {
                    return {
                        id: dado.id || '-',
                        serial: dado.serial || '-',
                        descricao: dado.descricao || '-',
                        data_cadastro: formatDateTime(dado.data_cadastro) || '-',
                    }
                });
                atualizarAgGridCintas(DadosAgGridCintas);
            } else {
                atualizarAgGridCintas([]);
            }
        },
        error: function(error) {
            $("#loadingMessage").hide();
            atualizarAgGridCintas([]);
        }
    });

    $.ajax({
        cache: false,
        url: Router+'/ajax_powerbanks',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $("#loadingMessage").hide();
            if (data) {
                DadosAgGridPowerbanks = data.data.map(function(dado, index) {
                    return {
                        id: dado.id || '-',
                        serial: dado.serial || '-',
                        descricao: dado.descricao || '-',
                        data_cadastro: formatDateTime(dado.data_cadastro) || '-',
                    }
                });
                atualizarAgGridPowerbanks(DadosAgGridPowerbanks);
            } else {
                atualizarAgGridPowerbanks([]);
            }
        },
        error: function(error) {
            $("#loadingMessage").hide();
            atualizarAgGridPowerbanks([]);
        }
    });

    $.ajax({
        cache: false,
        url: Router+'/ajax_carregadores',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $("#loadingMessage").hide();
            if (data) {
                DadosAgGridCarregadores = data.data.map(function(dado, index) {
                    return {
                        id: dado.id || '-',
                        serial: dado.serial || '-',
                        descricao: dado.descricao || '-',
                        data_cadastro: formatDateTime(dado.data_cadastro) || '-',
                    }
                });
                atualizarAgGridCarregadores(DadosAgGridCarregadores);
            } else {
                atualizarAgGridCarregadores([]);
            }
        },
        error: function(error) {
            $("#loadingMessage").hide();
            atualizarAgGridCarregadores([]);
        }
    });
})

function limparCampos(){
    $('#powerbank_serial').val('');
    $('#powerbank_descricao').val('');
    $('#cinta_serial').val('');
    $('#cinta_descricao').val('');
    $('#carregador_serial').val('');
    $('#carregador_descricao').val('');
}

$('#submit_carregador').on('click', function(){
    $('#submit_carregador').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');
        
        $.ajax({
            url: Router + '/add_carregador',
            type: "POST",
            dataType: "json",
            data: {
                carregador_serial: $('#carregador_serial').val(),
                carregador_descricao: $('#carregador_descricao').val()},
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    $('#carregador_serial').val('');
                    $('#carregador_descricao').val('');
                    $.ajax({
                        cache: false,
                        url: Router+'/ajax_carregadores',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $("#loadingMessage").hide();
                            if (data) {
                                DadosAgGridCarregadores = data.data.map(function(dado, index) {
                                    return {
                                        id: dado.id || '-',
                                        serial: dado.serial || '-',
                                        descricao: dado.descricao || '-',
                                        data_cadastro: formatDateTime(dado.data_cadastro) || '-',
                                    }
                                });
                                atualizarAgGridCarregadores(DadosAgGridCarregadores);
                            } else {
                                atualizarAgGridCarregadores([]);
                            }
                        },
                        error: function(error) {
                            $("#loadingMessage").hide();
                            atualizarAgGridCarregadores([]);
                        }
                    });
                    $('#modalCadCarregador').modal('hide');
                } else {
                    alert(callback.msg);
                }
 
                $('#submit_carregador').attr('disabled', false).html('Salvar');
            },
            error: function() {
                alert('Não foi possível cadastrar a cinta no momento. Tente novamente mais tarde!');
                $('#submit_carregador').attr('disabled', false).html('Salvar');
            }
        })
})

$('#submit_powerbank').on('click', function(){
    $('#submit_powerbank').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');
        
        $.ajax({
            url: Router + '/add_powerbank',
            type: "POST",
            dataType: "json",
            data: {
                powerbank_serial: $('#powerbank_serial').val(),
                powerbank_descricao: $('#powerbank_descricao').val()},
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    $('#powerbank_serial').val('');
                    $('#powerbank_descricao').val('');
                    $.ajax({
                        cache: false,
                        url: Router+'/ajax_powerbanks',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $("#loadingMessage").hide();
                            if (data) {
                                DadosAgGridPowerbanks = data.data.map(function(dado, index) {
                                    return {
                                        id: dado.id || '-',
                                        serial: dado.serial || '-',
                                        descricao: dado.descricao || '-',
                                        data_cadastro: formatDateTime(dado.data_cadastro) || '-',
                                    }
                                });
                                atualizarAgGridPowerbanks(DadosAgGridPowerbanks);
                            } else {
                                atualizarAgGridPowerbanks([]);
                            }
                        },
                        error: function(error) {
                            $("#loadingMessage").hide();
                            atualizarAgGridPowerbanks([]);
                        }
                    });
                    $('#modalCadPowerbank').modal('hide');
                } else {
                    alert(callback.msg);
                }
 
                $('#submit_powerbank').attr('disabled', false).html('Salvar');
            },
            error: function() {
                alert('Não foi possível cadastrar a cinta no momento. Tente novamente mais tarde!');
                $('#submit_powerbank').attr('disabled', false).html('Salvar');
            }
        })
})

$('#submit_cinta').on('click', function(){
    $('#submit_cinta').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');
        
        $.ajax({
            url: Router + '/add_cinta',
            type: "POST",
            dataType: "json",
            data: {
                cinta_serial: $('#cinta_serial').val(),
                cinta_descricao: $('#cinta_descricao').val()},
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    $('#cinta_serial').val('');
                    $('#cinta_descricao').val('');
                    $.ajax({
                        cache: false,
                        url: Router+'/ajax_cintas',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $("#loadingMessage").hide();
                            if (data) {
                                DadosAgGridCintas = data.data.map(function(dado, index) {
                                    return {
                                        id: dado.id || '-',
                                        serial: dado.serial || '-',
                                        descricao: dado.descricao || '-',
                                        data_cadastro: formatDateTime(dado.data_cadastro) || '-',
                                    }
                                });
                                atualizarAgGridCintas(DadosAgGridCintas);
                            } else {
                                atualizarAgGridCintas([]);
                            }
                        },
                        error: function(error) {
                            $("#loadingMessage").hide();
                            atualizarAgGridCintas([]);
                        }
                    });
                    $('#modalCadCinta').modal('hide');
                } else {
                    alert(callback.msg);
                }
 
                $('#submit_cinta').attr('disabled', false).html('Salvar');
            },
            error: function() {
                alert('Não foi possível cadastrar a cinta no momento. Tente novamente mais tarde!');
                $('#submit_cinta').attr('disabled', false).html('Salvar');
            }
        })
})

function formatDateTime(date){
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0]+" "+dates[1];
}

let menuAbertoCintas = false;
 
function manipularMenuNodesCintas() {
    menuAbertoCintas = !menuAbertoCintas;
    let buttonSrc = menuAbertoCintas ? "<?php echo base_url('assets/images/icon-filter-show.svg') ?>" : "<?php echo base_url('assets/images/icon-filter-hide.svg') ?>";
    $('#img-expandir-cintas').attr("src", buttonSrc);
    $('.menu-interno').toggle(!menuAbertoCintas);
    $('#conteudo').toggleClass('col-md-12 col-md-9');
}

let menuAbertoPowerbanks = false;
 
function manipularMenuNodesPowerbanks() {
    menuAbertoPowerbanks = !menuAbertoPowerbanks;
    let buttonSrc = menuAbertoPowerbanks ? "<?php echo base_url('assets/images/icon-filter-show.svg') ?>" : "<?php echo base_url('assets/images/icon-filter-hide.svg') ?>";
    $('#img-expandir-powerbanks').attr("src", buttonSrc);
    $('.menu-interno').toggle(!menuAbertoPowerbanks);
    $('#conteudo').toggleClass('col-md-12 col-md-9');
}

let menuAbertoCarregadores = false;
 
function manipularMenuNodesCarregadores() {
    menuAbertoCarregadores = !menuAbertoCarregadores;
    let buttonSrc = menuAbertoCarregadores ? "<?php echo base_url('assets/images/icon-filter-show.svg') ?>" : "<?php echo base_url('assets/images/icon-filter-hide.svg') ?>";
    $('#img-expandir-carregadores').attr("src", buttonSrc);
    $('.menu-interno').toggle(!menuAbertoCarregadores);
    $('#conteudo').toggleClass('col-md-12 col-md-9');
}
    var DadosAgGridCintas = [];
    var AgGridCintas;

function atualizarAgGridCintas(dados) {
        stopAgGRIDCintas();
        const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
            },
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'series',
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                chartDataType: 'series',
            },
            {
                headerName: 'Data de Cadastro',
                field: 'data_cadastro',
                chartDataType: 'series',
            },
        ],
        defaultColDef: {
            editable: false,
            filter: true,
            resizable: false,
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableCintas');
    AgGridCintas = new agGrid.Grid(gridDiv, gridOptions);

    // gridOptions.api.setRowData(mappedData);
    gridOptions.api.setRowData(dados);

    gridOptions.quickFilterText = '';
 
    document.querySelector('#search-input-cintas').addEventListener('input', function () {
        var searchInput = document.querySelector('#search-input-cintas');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    document.querySelector('#select-quantidade-por-pagina-cintas').addEventListener('change', function () {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina-cintas').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });
    preencherExportacoesCintas(gridOptions);    
}

function stopAgGRIDCintas() {
    var gridDiv = document.querySelector('#tableCintas');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperCintas');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableCintas" class="ag-theme-alpine my-grid-cintas"></div>';
    }
}

function preencherExportacoesCintas(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_cintas');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + '/media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + '/media/img/new_icons/pdf.png';

    formularioExportacoes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_cintas');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivoCintas(opcao, gridOptions);
        });

        formularioExportacoes.appendChild(div);
    });
}

function exportarArquivoCintas(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'SuprimentosCintas.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName
            });
            break;
        case 'excel':
            fileName = 'SuprimentosCintas.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName
            });
            break;
        case 'pdf':
            let dadosExportacao = prepararDadosExportacaoCintas();

            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                dadosExportacao.informacoes,
                dadosExportacao.rodape
            );
            pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
            break;

    }
}

function prepararDadosExportacaoCintas() {
    let informacoes = DadosAgGridCintas.map((dados) => ({
        ID: dados.id,
        Serial: dados.serial,
        Descrição: dados.descricao,
        Data_de_Cadastro: dados.data_cadastro,
    }));

    let rodape = `Suprimentos - Cintas`;
    let nomeArquivo = `SuprimentosCintas.pdf`;

    return {
        informacoes,
        nomeArquivo,
        rodape
    };
}

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_cintas');

    document.getElementById('dropdownMenuButtonCintas').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonCintas') {
            dropdown.style.display = 'none';
        }
    });
});

var DadosAgGridPowerbanks = [];
var AgGridPowerbanks;

function atualizarAgGridPowerbanks(dados) {
        stopAgGRIDPowerbanks();
        const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
            },
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'series',
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                chartDataType: 'series',
            },
            {
                headerName: 'Data de Cadastro',
                field: 'data_cadastro',
                chartDataType: 'series',
            },
        ],
        defaultColDef: {
            enablePivot: true,
            editable: false,
            filter: true,
            resizable: true,
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tablePowerBanks');
    AgGridPowerbanks = new agGrid.Grid(gridDiv, gridOptions);

    // gridOptions.api.setRowData(mappedData);
    gridOptions.api.setRowData(dados);

    gridOptions.quickFilterText = '';
 
    document.querySelector('#search-input-powerbanks').addEventListener('input', function () {
        var searchInput = document.querySelector('#search-input-powerbanks');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    document.querySelector('#select-quantidade-por-pagina-powerbanks').addEventListener('change', function () {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina-powerbanks').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });
    
    preencherExportacoesPowerbanks(gridOptions);
}

function stopAgGRIDPowerbanks() {
    var gridDiv = document.querySelector('#tablePowerBanks');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperPowerBanks');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tablePowerBanks" class="ag-theme-alpine my-grid-powerbanks"></div>';
    }
}

function preencherExportacoesPowerbanks(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_powerbanks');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + '/media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + '/media/img/new_icons/pdf.png';

    formularioExportacoes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_powerbanks');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivoPowerbanks(opcao, gridOptions);
        });

        formularioExportacoes.appendChild(div);
    });
}

function exportarArquivoPowerbanks(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'SuprimentosPowerbanks.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName
            });
            break;
        case 'excel':
            fileName = 'SuprimentosPowerbanks.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName
            });
            break;
        case 'pdf':
            let dadosExportacao = prepararDadosExportacaoPowerbanks();

            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                dadosExportacao.informacoes,
                dadosExportacao.rodape
            );
            pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
            break;
    }
}

function prepararDadosExportacaoPowerbanks() {
    let informacoes = DadosAgGridCintas.map((dados) => ({
        ID: dados.id,
        Serial: dados.Serial,
        Descrição: dados.Descrição,
        Data_de_Cadastro: dados.Data_de_Cadastro,
    }));

    let rodape = `Suprimentos - Powerbanks`;
    let nomeArquivo = `SuprimentosPowerbanks.pdf`;

    return {
        informacoes,
        nomeArquivo,
        rodape
    };
}

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_powerbanks');

    document.getElementById('dropdownMenuButtonPowerbanks').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonPowerbanks') {
            dropdown.style.display = 'none';
        }
    });
});

var DadosAgGridCarregadores = [];
var AgGridCarregadores;

function atualizarAgGridCarregadores(dados) {
        stopAgGRIDCarregadores();
        const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
            },
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'series',
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                chartDataType: 'series',
            },
            {
                headerName: 'Data de Cadastro',
                field: 'data_cadastro',
                chartDataType: 'series',
            },
        ],
        defaultColDef: {
            editable: false,
            filter: true,
            resizable: false,
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableCarregadores');
    AgGridCarregadores = new agGrid.Grid(gridDiv, gridOptions);

    // gridOptions.api.setRowData(mappedData);
    gridOptions.api.setRowData(dados);

    gridOptions.quickFilterText = '';
 
    document.querySelector('#search-input-carregadores').addEventListener('input', function () {
        var searchInput = document.querySelector('#search-input-carregadores');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    document.querySelector('#select-quantidade-por-pagina-carregadores').addEventListener('change', function () {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina-carregadores').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });
    
    preencherExportacoesCarregadores(gridOptions);
}

function stopAgGRIDCarregadores() {
    var gridDiv = document.querySelector('#tableCarregadores');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperCarregadores');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableCarregadores" class="ag-theme-alpine my-grid-carregadores"></div>';
    }
}

function preencherExportacoesCarregadores(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_carregadores');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + '/media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + '/media/img/new_icons/pdf.png';

    formularioExportacoes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_carregadores');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivoCarregadores(opcao, gridOptions);
        });

        formularioExportacoes.appendChild(div);
    });
}

function exportarArquivoCarregadores(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'SuprimentosCarregadores.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName
            });
            break;
        case 'excel':
            fileName = 'SuprimentosCarregadores.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName
            });
            break;
        case 'pdf':
            let dadosExportacao = prepararDadosExportacaoCarregadores();

            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                dadosExportacao.informacoes,
                dadosExportacao.rodape
            );
            pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
            break;

    }
}

function prepararDadosExportacaoCarregadores() {
    let informacoes = DadosAgGridCintas.map((dados) => ({
        ID: dados.id,
        Serial: dados.Serial,
        Descrição: dados.Descrição,
        Data_de_Cadastro: dados.Data_de_Cadastro,
    }));

    let rodape = `Suprimentos - Carregadores`;
    let nomeArquivo = `SuprimentosCarregadores.pdf`;

    return {
        informacoes,
        nomeArquivo,
        rodape
    };
}

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_carregadores');

    document.getElementById('dropdownMenuButtonCarregadores').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonCarregadores') {
            dropdown.style.display = 'none';
        }
    });
});