var localeText = AG_GRID_LOCALE_PT_BR;
let dadosInserir = [];
let seriaisConsulta;
let seriaLoraTela;

//MAPA
var mapaDadosPartidaChegada = "";
let mapaDadosPartidaChegadaObserver;
var markers = L.layerGroup();
var marcadores = [];
var marcadoresPartidaChegada = [];

var mapaDadosPartidaChegadaTela = ""; 
var mapaDadosPartidaChegadaObserverTela;
var marcadoresPartidaChegadaTela = []; 
var marcadoresTela = [];



$(document).ready(function () {

    atualizarAgGridStatusCliente([]);

    document.getElementById("serialBusca", "inputSerial").addEventListener("input", function (event) {
        var input = event.target;
        input.value = input.value.toUpperCase();
    });

    $("#serialBusca, #inputSerial").on('keypress', function (e) {
        if (e.keyCode < 32 || e.keyCode === 127) {
            return true;
        }

        let char = String.fromCharCode(e.which || e.keyCode);
        if (!char.match(/[a-zA-Z0-9]/)) {
            e.preventDefault();
        }
    });


    $("#btnVisualizarEmTela").click(function (e) {
        marcarMapaLoraTela(seriaLoraTela)        
    });

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    // IMPORTAÇÃO ARQUIVO
    $("#BtnImportarArquivo").click(function (e) {
        $("#importarLoraArquivo").modal("show");
        preencherImportacaoArquivo([]);
    });

    $("#importarLoraArquivo").on("hide.bs.modal", function () {
        seriaisConsulta = [];
    });

    $("#importarLoraLote").on("hide.bs.modal", function () {
        dadosInserir = [];
    });

    //IMPORTAÇÃO INPUT LOTE
    $("#BtnImportarLote").click(function (e) {
        $("#importarLoraLote").modal("show");
        preencherImportacaoLote([]);
    });

    $("#inputSerial").on('keypress', function (e) {
        var charCode = e.which || e.keyCode;
        if (!(charCode >= 48 && charCode <= 57) &&
            !(charCode >= 65 && charCode <= 90) &&
            !(charCode >= 97 && charCode <= 122)) {
            e.preventDefault();
        }

        if (e.which === 13) {
            adicionarSerial($(this).val().trim().toUpperCase());
            $(this).val("");
        }
    });

    $("#serialBusca").on('keypress', function (e) {
        var charCode = e.which || e.keyCode;
        if (!(charCode >= 48 && charCode <= 57) &&
            !(charCode >= 65 && charCode <= 90) &&
            !(charCode >= 97 && charCode <= 122)) {
            e.preventDefault();
        }

        if (e.which === 13) {
            e.preventDefault();
            if ($("#serialBusca").val() != '') {
                showLoadingPesquisarButton();
                disableButtons([
                    '#BtnLimparFiltro',
                    '#dropdownMenuButtonImportacao',
                    '#dropdownMenuButton',
                    '#btnVisualizarEmTela'
                ]);
                getDadosTela();
            } else {
                showAlert("warning", "Informe um serial para pesquisar.")
                $("#BtnPesquisar").blur();

            }
        }
    });

    $("#importarIscasLote").on("hide.bs.modal", function () {
        seriaisConsulta = [];
        dadosInserir = [];
        $('#inputSerial').val('');
    });

    $("#info-icon").click(function (e) {
        $("#modalModeloItens").modal("show");
    });

    var dropdown_import = $("#opcoes_importacao");

    $("#dropdownMenuButtonImportacao").click(function () {
        dropdown_import.toggle();
    });

    $("#dropdownMenuButtonImportacaoLote").click(function () {
        dropdown_import.toggle();
    });

    $("#limparTabelaItens").click(function () {
        limparAgGrid();
    });

    $("#limparTabelaLote").click(function () {
        limparAgGridLote();
    });

    $(document).click(function (event) {
        if (
            !dropdown_import.is(event.target) &&
            event.target.id !== "dropdownMenuButtonImportacao"
        ) {
            dropdown_import.hide();
        }
    });

    $("#btnSalvarImportacaoLora").click(function (e) {
        if(agGridImportacao.gridOptions.api.getDisplayedRowCount() === 0){
            showAlert("warning", "Não foi encontrado nenhum serial importado.")
            $("#btnSalvarImportacaoLora").blur();
            return
        }
        showLoadingSalvarImportButton();
        getDadosInputArquivo();
    });

    $("#btnSalvarImportacaoLoraLote").click(function (e) {
        if (dadosInserir.length > 0) {
            showLoadingSalvarImportLoteButton();
            getDadosInput();
        } else {
            showAlert("warning", "Adicione pelo menos um serial a lista");
            $("#btnSalvarImportacaoLoraLote").blur();
            return;
        }
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        if ($("#serialBusca").val() != '') {
            showLoadingPesquisarButton();
            disableButtons([
                '#BtnLimparFiltro',
                '#dropdownMenuButtonImportacao',
                '#dropdownMenuButton',
                '#btnVisualizarEmTela'
            ]);
            getDadosTela();
        } else {
            showAlert("warning", "Informe um serial para pesquisar.")
            $("#BtnPesquisar").blur();
        }
    });

    $("#BtnLimparFiltro").click(function (e) {
        atualizarAgGridStatusCliente([]);
        if ($("#serialBusca").val() != '') {
            limparPesquisa()
        } else {
            $('#serialBusca').val('');
            $('#btnVisualizarEmTela').hide()
            $('.resultadoBuscaSerial').empty();
        }
    });

    $('.dropdown-toggle').click(function () {
        var $dropdownMenu = $(this).next('.dropdown-menu');

        if ($dropdownMenu.length) {
            var screenWidth = $(window).width();
            var menuRight = $dropdownMenu.offset().left + $dropdownMenu.outerWidth();

            if (menuRight > screenWidth) {
                $dropdownMenu.css('left', screenWidth - menuRight);
            }
        }
    });

    $("#visualizarLora").on("hidden.bs.modal", function () {
        resetarMapaPartidaChegada()
    mapaDadosPartidaChegada = "";
       mapaDadosPartidaChegadaObserver = null;
       marcadores = [];
       marcadoresPartidaChegada = [];
    });
});

function importarItensExcel(event) {
    event.preventDefault();
    const fileInput = document.getElementById('arquivoItens');
    const file = fileInput.files[0];

    if (!file) {
        showAlert("warning", 'Por favor, selecione um arquivo.');
        return;
    }

    const validExtensions = ['.xls', '.xlsx'];
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

    if (!validExtensions.includes(fileExtension)) {
        showAlert("warning", 'Por favor, selecione um arquivo Excel válido (.xls ou .xlsx).');
        fileInput.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        if (jsonData.length === 0 || jsonData[0].length === 0) {
            showAlert("warning", 'O arquivo está vazio.');
            return;
        }

        if (jsonData[0].length !== 1 || jsonData[0][0].toLowerCase() !== 'serial') {
            showAlert("warning", 'A planilha deve conter exatamente uma coluna com o cabeçalho "SERIAL".');
            return;
        }

        jsonData.shift();

        if (jsonData.length === 0) {
            showAlert("warning", 'Não há dados válidos na planilha.');
            return;
        }

        let seriaisSet = new Set();
        let duplicatasEncontradas = false;

        jsonData.forEach((row) => {
            if (row.length > 0 && row[0] !== null && row[0] !== undefined) {
                let serialOriginal = String(row[0]).toUpperCase();
                let serialLimpo = removerAcentosEspeciais(serialOriginal);
                if (!seriaisSet.has(serialLimpo)) {
                    seriaisSet.add(serialLimpo);
                } else {
                    duplicatasEncontradas = true;
                }
            }
        });

        var dadosInserir = Array.from(seriaisSet).map(serial => ({ serial: serial.toUpperCase() }));

        if (dadosInserir.length > 500) {
            showAlert("warning", 'A planilha contém mais de 500 registros. Apenas os primeiros 500 registros foram processados.');
            dadosInserir = dadosInserir.slice(0, 500);
        }

        preencherImportacaoArquivo(dadosInserir);
        fileInput.value = '';

        if (duplicatasEncontradas) {
            showAlert("warning", 'Foram identificados e removidos registros duplicados durante a importação.');
        }
    };

    reader.readAsArrayBuffer(file);
}

function removerAcentosEspeciais(s) {
    let entradaComoString = String(s);
    let semAcentos = entradaComoString.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    let semCaracteresEspeciais = semAcentos.replace(/[^a-zA-Z0-9]/g, "");
    return semCaracteresEspeciais.toLowerCase();
}

function adicionarSerial(serial) {
    if (serial.length === 0) {
        showAlert("warning", "É necessário digitar um serial.");
        return;
    }

    if (dadosInserir.some(item => item.serial === serial)) {
        showAlert("warning", "Serial já informado.");
        return;
    }

    dadosInserir.push({ serial: serial });
    preencherImportacaoLote(dadosInserir);
}

// REQUISIÇÕES:
function getDadosInput() {
    var dados = {
        seriais: seriaisConsulta
    };
    var route = Router + '/verificarSerialLora';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(dados),
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data.status == 200) {
                let dadosTratados = data.resultado.resultado.serialStatusList.map(tratarDados);
                atualizarAgGridStatusCliente(dadosTratados);
            } else if (data.status == 400) {
                const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                showAlert("error", errorMessage);
            } else if (data.status == 404) {
                showAlert("warning", "Dados não encontrados.")
            } else {
                showAlert("error", "Erro interno do servidor. Entre em contato com o Suporte Técnico")
            }
            $('#importarLoraLote').modal('hide');
            resetSalvarImportLoteButton();
        },
    });
}

function getDadosInputArquivo() {
    var dados = {
        seriais: seriaisConsulta
    };
    var route = Router + '/verificarSerialLora';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(dados),
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data.status == 200) {
                let dadosTratados = data.resultado.resultado.serialStatusList.map(tratarDados);
                atualizarAgGridStatusCliente(dadosTratados);
                $('#importarLoraArquivo').modal('hide');
                resetSalvarImportButton();
            } else if (data.status == 400) {
                const errorMessage = data.resultado?.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                showAlert("error", errorMessage);
                resetSalvarImportButton();
            } else if (data.status == 404) {
                showAlert("warning", "Dados não encontrados.");
                resetSalvarImportButton();
            } else {
                showAlert("error", "Erro interno do servidor. Entre em contato com o Suporte Técnico")
            }
        },
    });
}

function getDadosTela() {
    var route = Router + '/verificarSerialLora';

    $.ajax({
        cache: false,
        url: route,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({ seriais: [$("#serialBusca").val()] }),
        dataType: "json",
        async: true,
        success: function (data) {
            if (data.status === 200) {
                let dadosTratados = data.resultado.resultado.serialStatusList.map(tratarDados);

                preencherModal(dadosTratados);

                var mensagemHtml = dadosTratados
                    .map((item) => {
                        seriaLoraTela = item.serial;
                        let clienteInfo = '';
                        if ((item.nomeCliente && item.nomeCliente !== '') || (item.documento && item.documento !== '')) {
                            clienteInfo = `
                              <h5 style="text-align: center; font-size: 17px; color: #1C69AD; margin-top: 10px; margin-bottom: 10px;">Dados do Cliente</h5>
                              <div>Cliente: ${item.nomeCliente || ''}</div>
                              <div>Documento: ${item.documento || ''}</div>
                            `;
                        }

                        let localizacaoInfo = '';
                        if ((item.latitude && item.latitude !== '') || (item.longitude && item.longitude !== '') || (item.endereco && item.endereco !== '')) {
                            localizacaoInfo = `
                              <h5 style="text-align: center; font-size: 17px; color: #1C69AD; margin-top: 10px; margin-bottom: 10px;">Dados de Localização</h5>
                              <div>Longitude: ${item.longitude || ''}</div>
                              <div>Latitude: ${item.latitude || ''}</div>
                              <div style="margin-bottom: 5px; word-wrap: break-word; word-break: break-all; white-space: normal; overflow-wrap: break-word;">Endereço: ${item.endereco || ''}</div>
                            `;
                        }

                        let dadosComunicacao = ''
                        if (item.dataComunicacao && item.dataComunicacao != '') {
                            dadosComunicacao =  `
                            <h5 style="text-align: center; font-size: 17px; color: #1C69AD; margin-top: 10px; margin-bottom: 10px;">Última Comunicação:</h5>
                            <div>Data: ${item.dataComunicacao || ''}</div>
                          `;                            
                        }

                        return `
                          <h4 style="font-size: 17px; color: #1C69AD; text-align: center; margin-top: 10px; margin-bottom: 10px;">${item.serial.toUpperCase()}</h4>
                          <div style="margin-bottom: 5px; word-wrap: break-word; word-break: break-all; white-space: normal; overflow-wrap: break-word;">${item.motivo.replace(/ \/ /g, "<br>")}</div>
                          ${clienteInfo}
                          ${localizacaoInfo}
                          ${dadosComunicacao}
                        `;
                    })
                    .join("");
                $(".resultadoBuscaSerial").html(mensagemHtml);
                $("#btnVisualizarEmTela").show();
            } else {
                var errorMessage = "Ocorreu um erro inesperado.";
                if (data.status == 400) errorMessage = data.resultado ? data.resultado[0] : data.resultado;
                if (data.status == 404) errorMessage = "Dados não encontrados.";
                showAlert("error", errorMessage);
            }
            resetPesquisarButton();
            enableButtons([
                "#BtnLimparFiltro",
                "#dropdownMenuButtonImportacao",
                "#dropdownMenuButton",
                '#btnVisualizarEmTela'
            ]);
            $('#serialBusca').val('');
        },
        error: function () {
            showAlert("error", "Erro de comunicação com o servidor.");
            resetPesquisarButton();
            enableButtons([
                "#BtnLimparFiltro",
                "#dropdownMenuButtonImportacao",
                "#dropdownMenuButton",
                '#btnVisualizarEmTela'
            ]);
            $('#serialBusca').val('');
        }
    });
}


// AG-GRID:
var AgGridStatus;
function atualizarAgGridStatusCliente(dados) {
    stopAgGRIDStatusCliente();

    const gridOptions = {
        columnDefs: [
            {
                headerName: "Serial LoRa",
                field: "serial",
                width: 120,
                sortable: true,
            },
            {
                headerName: "Status",
            field: "motivo",
            width: 400,
            sortable: true,
            autoHeight: true,
                cellRenderer: function (params) {
                    if (params.value) {
                        const messages = params.value.split(' / ').map((message, index) => `<div>${message}</div>`).join('');
                        return messages;
                    } else {
                        return "";
                    }
                }
            },
            {
                headerName: "Cliente",
                field: "nomeCliente",
                width: 120,
                sortable: true,
            },
            {
                headerName: "Documento",
                field: "documento",
                width: 120,
                sortable: true,
            },          
            {
                headerName: "Tipo de Comunicação",
                field: "tipoComunicacaoLora",
                width: 180
            },
            {
                headerName: "Endereço",
                field: "endereco",
                width: 250,
                autoHeight: true,
                sortable: true,
                cellRenderer: function (options) {
                    let data = options.data;
                    let inlineStyle = `
                        <style>
                            #linkEnderecoEvento {
                                text-decoration: none;
                                color: #337ab7;
                            }
                            #linkEnderecoEvento:hover {
                                color: #275d8b;
                            }
                        </style>`;
                    let url = `https://www.google.com.br/maps/dir//${data.latitude},${data.longitude}/@${data.latitude},${data.longitude},17z?entry=ttu`;
                    if (data.latitude && data.longitude) {
                        return `${inlineStyle} <a id="linkEnderecoEvento" href="${url}" target="_blank">${data.endereco}</a>`;
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: "Latitude",
                field: "latitude",
                width: 120,
                autoHeight: true,
                sortable: true,
            },
            {
                headerName: "Longitude",
                field: "longitude",
                width: 120,
                autoHeight: true,
                sortable: true,
            },           
            {
                headerName: "Últ. Comun.",
                field: "dataComunicacao",
                width: 200,
                autoHeight: true,
                sortable: true,
            },
            {
                headerName: "Situação",
                field: "ignicao",
                width: 120,
                cellRenderer: function (params) {
                    if (params.data.motivo.includes("Erro - Serial não encontrado")) {
                        return '';
                    } else if (params.value == 0) {
                        return 'Parado';
                    } else if (params.value == 1) {
                        return 'Em movimento';
                    }
                }
            },
            {
                headerName: "Emergência",
                field: "modoEmergencia",
                width: 120,
                chartDataType: 'category',
                cellRenderer: function (options) {
                    if (options.data.motivo.includes("Erro - Serial não encontrado")) {
                        return '';
                    }
                    let data = options.data['modoEmergencia'];
                    const emergencias = [104, 106, 107, 108, 109, 111, 120, 122];
                    if (emergencias.includes(data)) {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `;
                    }
                }
            },            
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
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
                    let tableId = "tableStatusLora";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdownModal('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                    ${!data.motivo.includes("Erro - Serial não encontrado") ? `
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:visualizarLora('${data.serial}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                        </div>
                    </div>
                    ` : ''}
                    </div>`;
                }
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
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
                        width: 100,
                    },
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-StatusLora').val()),
        onRowDoubleClicked: function (params) {
            let data = "data" in params ? params.data : null;
            if (data && !data.motivo.includes("Erro - Serial não encontrado")) {
                if ("serial" in data) {
                    visualizarLora(data.serial);
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableStatusLora');
    gridDiv.style.setProperty('height', '519px');
    AgGridCemUltSolic = new agGrid.Grid(gridDiv, gridOptions);
    AgGridCemUltSolic.gridOptions.api.setRowData(dados);

    $('#select-quantidade-por-pagina-StatusLora').change(function () {
        var selectedValue = $(this).val();
        AgGridCemUltSolic.gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    preencherExportacoesLora(gridOptions, "Relatórido de Rastreador LoRa");
}

function stopAgGRIDStatusCliente() {
    var gridDiv = document.querySelector('#tableStatusLora');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperStatusLora');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableStatusLora" class="ag-theme-alpine my-grid-Lora"></div>';
    }
}

//Importação Excel
var agGridImportacao;
function preencherImportacaoArquivo(dadosInserir) {
    stopAgGRIDImportacao();

    dadosInserir = dadosInserir.map((item) => ({
        ...item,
    }));

    const columnDefs = [
        {
            headerName: "Serial LoRa",
            field: "serial",
            flex: 1,
            sortable: true,
        },
        {
            headerName: 'Ações',
            pinned: 'right',
            width: 100,
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
                let tableId = "tableImportacaoLora";
                let dropdownId = "dropdown-menu" + data.serial + varAleatorioIdBotao;
                let buttonId = "dropdownMenuButton_" + data.serial + varAleatorioIdBotao;

                return `
            <div class="dropdown">
                <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" aria-labelledby="${buttonId}">
                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                        <a href="javascript:removerIsca('${data.serial}')" style="cursor: pointer; color: black;">Remover</a>
                    </div>
                </div>
            </div>`;
            }
        },
    ];

    const gridOptions = {
        columnDefs: columnDefs,
        rowData: dadosInserir,
        domLayout: 'normal',
        pagination: true,
        paginationPageSize: 50,
        rowHeight: 45,
        defaultColDef: {
            resizable: true,
            filter: true,
        },
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableImportacaoLora');
    gridDiv.style.height = '420px';

    agGridImportacao = new agGrid.Grid(gridDiv, gridOptions);
    seriaisConsulta = agGridImportacao.gridOptions.api.getModel().rowsToDisplay.map(node => node.data.serial);
}

function removerIsca(serial) {
    agGridImportacao.gridOptions.api.forEachNode(function (node) {
        if (node.data.serial === serial) {
            agGridImportacao.gridOptions.api.applyTransaction({ remove: [node.data] });
            seriaiConsulta = agGridImportacao.gridOptions.api.getModel().rowsToDisplay.map(node => node.data.serial);
        }
    });
}

function stopAgGRIDImportacao() {
    var gridDiv = document.querySelector('#tableImportacaoLora');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperImportacaoLora');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoLora" class="ag-theme-alpine my-grid-lora"></div>';
    }
}

//Importação InputLote
var agGridImportacaoLote;
function preencherImportacaoLote(dadosInserir) {
    stopAgGRIDImportacaoLote();

    dadosInserir = dadosInserir.map((item) => ({
        ...item,
    }));

    const columnDefs = [
        {
            headerName: "Serial LoRa",
            field: "serial",
            flex: 1,
            sortable: true,
        },
        {
            headerName: 'Ações',
            pinned: 'right',
            width: 100,
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
                let tableId = "tableImportacaoLoraLote";
                let dropdownId = "dropdown-menu" + data.serial + varAleatorioIdBotao;
                let buttonId = "dropdownMenuButton_" + data.serial + varAleatorioIdBotao;

                return `
                <div class="dropdown">
                    <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" aria-labelledby="${buttonId}">
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:removerIscaLote('${data.serial}')" style="cursor: pointer; color: black;">Remover</a>
                        </div>
                    </div>
                </div>`;
            }
        },
    ];

    const gridOptions = {
        columnDefs: columnDefs,
        rowData: dadosInserir,
        domLayout: 'normal',
        pagination: true,
        paginationPageSize: 50,
        rowHeight: 45,
        defaultColDef: {
            resizable: true,
            filter: true,
        },
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableImportacaoLoraLote');
    gridDiv.style.height = '420px';

    agGridImportacaoLote = new agGrid.Grid(gridDiv, gridOptions);

    seriaisConsulta = agGridImportacaoLote.gridOptions.api.getModel().rowsToDisplay.map(node => node.data.serial);
}

function removerIscaLote(serial) {
    agGridImportacaoLote.gridOptions.api.forEachNode(function (node) {
        if (node.data.serial === serial) {
            agGridImportacaoLote.gridOptions.api.applyTransaction({ remove: [node.data] });
            dadosInserir = dadosInserir.filter(item => item.serial !== serial);
            seriaisConsulta = agGridImportacaoLote.gridOptions.api.getModel().rowsToDisplay.map(node => node.data.serial);
        }
    });
}

async function visualizarLora(serial) {
    let dados = await buscarLora(serial);
    $('#detalheSerial').text(dados['resultado']['resultado']['serialStatusList'][0]['serial']);
    $('#detalheStatus').text(dados['resultado']['resultado']['serialStatusList'][0]['status']);
    $('#detalheEndereco').text(dados['resultado']['resultado']['serialStatusList'][0]['endereco']);
    $('#detalheLatitude').text(dados['resultado']['resultado']['serialStatusList'][0]['latitude']);
    $('#detalheLongitude').text(dados['resultado']['resultado']['serialStatusList'][0]['longitude']);

    let partesDataHora = dados['resultado']['resultado']['serialStatusList'][0]['dataComunicacao'].split(" ")
    let partesData = partesDataHora[0].split("-")
    let dataFormatada = `${partesData[2]}/${partesData[1]}/${partesData[0]}  ${partesDataHora[1]}`

    $('#detalheUltimaComunicacao').text(dataFormatada);
    
    $('#visualizarLora').modal('show');
    limparMarcasNoMapaPartidaChegada(marcadoresPartidaChegada);
    carregarMapaPartidaChegada(-15.39, -55.73, 4);
    marcadoresPartidaChegada = marcarNoMapaPartidaChegada(dados['resultado']['resultado']['serialStatusList'][0]);
}

async function marcarMapaLoraTela(serial){
    let dados = await buscarLora(serial);
    if(dados['resultado']['resultado']['serialStatusList'][0].latitude != null &&
        dados['resultado']['resultado']['serialStatusList'][0].longitude != null
    ){
        limparMarcasNoMapaPartidaChegadaTela(marcadoresPartidaChegadaTela);
        carregarMapaPartidaChegadaTela(-15.39, -55.73, 4);
        marcadoresPartidaChegadaTela = marcarNoMapaPartidaChegadaTela(dados['resultado']['resultado']['serialStatusList'][0]);
    }else{
        limparMarcasNoMapaPartidaChegadaTela(marcadoresPartidaChegadaTela);
        carregarMapaPartidaChegadaTela(-15.39, -55.73, 4);
    }
    $('#serialEmTela').modal('show');
}

function buscarLora(serial) {
    ShowLoadingScreen();
    var dados = {
        seriais: [serial]
    };

    var route = Router + '/verificarSerialLora';

    return new Promise((resolve, reject) => {
        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            dataType: 'json',
            async: true,
            success: function (data) {
                if (data.status == 200) {
                    resolve(data);
                }
                else {
                    showAlert("warning", "Dados não encontrados para o rastreador.");
                }
                HideLoadingScreen();
            },
        });
    });
}

function stopAgGRIDImportacaoLote() {
    var gridDiv = document.querySelector('#tableImportacaoLoraLote');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperImportacaoLoraLote');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoLoraLote" class="ag-theme-alpine my-grid-lora-lote"></div>';
    }
}

function abrirDropdownModal(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);

    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }

    $(".dropdown-menu").hide();

    dropdown.show();
    var posDropdown = dropdown.height() + 4;

    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;

    if (posDropdown > (posBordaTabela - posButton)) {
        dropdown.css('top', '5%');
    }

    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });

    $(document).on('contextmenu', function () {
        dropdown.hide();
    });
}

function limparAgGrid() {
    agGridImportacao.gridOptions.api.setRowData([]);
    seriaisConsulta = [];
    $("#arquivoItens").val("")
}

function limparAgGridLote() {
    agGridImportacaoLote.gridOptions.api.setRowData([]);
    dadosInserir = [];
    seriaisConsulta = [];
}

let menuAberto = false;
function expandirGrid() {
    menuAberto = !menuAberto;

    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

    if (menuAberto) {
        $('.img-expandir').attr("src", buttonShow);
        $('#filtroBusca').hide();
        $('.card-conteudo').removeClass('col-md-9');
        $('.card-conteudo').addClass('col-md-12');
    } else {
        $('.img-expandir').attr("src", buttonHide);
        $('#filtroBusca').show();
        $('.card-conteudo').css('margin-left', '0px');
        $('.card-conteudo').removeClass('col-md-12');
        $('.card-conteudo').addClass('col-md-9');
    }
}

function preencherModal(dadosTratados) {
    var serialBuscado = $("#serialBusca").val().toUpperCase();
    var statusEncontrado = dadosTratados.find(item => item.serial.toUpperCase() === serialBuscado);

    if (!statusEncontrado) {
        $('#statusIscaModal').html('<p style="font-size: 17px; margin-left: 10px;">Sem informações para o serial fornecido.</p>');
        $('#dadosCliente').html('<p style="font-size: 17px; margin-left: 10px;">Sem informações do cliente.</p>');
    } else {
        var statusHtml = `<p style="font-size: 17px; margin-left: 10px;">${statusEncontrado.motivo.replace(/ \/ /g, "<br>")}</p>`;
        $('#statusIscaModal').html(statusHtml);

        if (statusEncontrado.hasOwnProperty('dataComunicacao') && statusEncontrado.dataComunicacao) {
            if (statusEncontrado.dataComunicacao !== '') {
                var ultimaComunicacao = `<p style="font-size: 17px; margin-left: 10px;">Data: ${statusEncontrado.dataComunicacao}</p>`;
                $('#ultimaComunicacao').html(ultimaComunicacao);
            } else {
                $("#comunicacao").hide();
            }
        } else {
            $("#comunicacao").hide();
        }        

        var dadosClienteHtml = `
            <p style="font-size: 17px; margin-left: 10px;">Nome: ${statusEncontrado.nomeCliente}</p>
            <p style="font-size: 17px; margin-left: 10px;">Documento: ${statusEncontrado.documento}</p>
        `;
        $('#dadosCliente').html(dadosClienteHtml);

        var dadosLocalizacaoHtml = `
            <p style="font-size: 17px; margin-left: 10px;">Latitude: ${statusEncontrado.latitude}</p>
            <p style="font-size: 17px; margin-left: 10px;">Longitude: ${statusEncontrado.longitude}</p>
            <p style="font-size: 17px; margin-left: 10px;">Endereço: ${statusEncontrado.endereco}</p>
        `;
        $('#dadosLocalizacao').html(dadosLocalizacaoHtml);
    }

    $('#serialModal').text(`Informações do Serial #${serialBuscado}`);
}

function formatarDocumento(documento) {
    documento = documento.replace(/\D/g, '');

    if (documento.length === 11) { // CPF
        return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    } else if (documento.length === 14) { // CNPJ
        return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }
    return documento;
}

function tratarDados(item) {
    const motivosDivididos = item.status?.split(' | ') || [];
    const statusFormatado = motivosDivididos
        .map((motivo, index) => `${index + 1}. ${motivo}`)
        .join('<br>');

    const erroSerialNaoEncontrado = statusFormatado.includes("Erro - Serial não encontrado");

    if (erroSerialNaoEncontrado) {
        return {
            serial: item.serial || "",
            motivo: statusFormatado,
            tipoComunicacaoLora: "",
            endereco: "",
            latitude: "",
            longitude: "",
            ignicao: "",
            modoEmergencia: "",
            nomeCliente: "",
            documento: "",
            dataComunicacao: ""
        };
    }

    const nomeCliente = item.dadosCliente?.nomeCliente || "";
    const documentoFormatado = item.dadosCliente?.documento ? formatarDocumento(item.dadosCliente.documento) : "";

    const tipoComunicacaoLora = item.comunicacaoLora === 3 || item.comunicacaoLora === 4 ? item.tipoComunicacaoLora : "";

    let dataComunicacao = "";
    if (item.dataComunicacao) {
        const partesDataHora = item.dataComunicacao.split(" ");
        const partesData = partesDataHora[0].split("-");
        dataComunicacao = `${partesData[2]}/${partesData[1]}/${partesData[0]} ${partesDataHora[1]}`;
    }

    return {
        serial: item.serial || "",
        motivo: statusFormatado || "",
        tipoComunicacaoLora: tipoComunicacaoLora || "",
        endereco: item.endereco || "",
        latitude: item.latitude || "",
        longitude: item.longitude || "",
        ignicao: item.ignicao || "",
        modoEmergencia: item.modoEmergencia || "",
        nomeCliente: nomeCliente,
        documento: documentoFormatado,
        dataComunicacao: dataComunicacao
    };
}



// visualizações:
function ShowLoadingScreen() {
    $('#loading').show();
}

function HideLoadingScreen() {
    $('#loading').hide();
}

function limparPesquisa() {
    $('#serialBusca').val('');
    $('#btnVisualizarEmTela').hide();
    $('.resultadoBuscaSerial').empty();
    dadosInserir = [];
    seriaisConsulta = [];
    atualizarAgGridStatusCliente([]);
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function showLoadingSalvarImportLoteButton() {
    $('#btnSalvarImportacaoLoraLote').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetSalvarImportLoteButton() {
    $('#btnSalvarImportacaoLoraLote').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarImportButton() {
    $('#btnSalvarImportacaoLora').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetSalvarImportButton() {
    $('#btnSalvarImportacaoLora').html('Salvar').attr('disabled', false);
}

function disableButtons(selectors) {
    selectors.forEach(selector => {
        $(selector).attr('disabled', true);
    });
}

function enableButtons(selectors) {
    selectors.forEach(selector => {
        $(selector).attr('disabled', false);
    });
}

//MAPA 1
function carregarMapaPartidaChegada(lat = 0, log = 0, zoom = 2) {
    resetarMapaPartidaChegada();
    //carregar mapa na mesma posicao do evento
    mapaDadosPartidaChegada = L.map('mapaDadosPartidaChegada', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false });
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaDadosPartidaChegada.setView([lat, log], zoom);
    mapaDadosPartidaChegada.dragging.enable();

    osm.addTo(mapaDadosPartidaChegada);

    // Função para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaDadosPartidaChegada.invalidateSize();
    }

    // Adicionar observador de mutação com jQuery
    const target = document.getElementById('mapaDadosPartidaChegada');
    mapaDadosPartidaChegadaObserver = new MutationObserver(ajustarTamanhoMapa);

    const config = { attributes: true, childList: true, subtree: true };

    mapaDadosPartidaChegadaObserver.observe(target, config);

    // Certificar-se de chamar a função inicialmente
    ajustarTamanhoMapa();
}

//funcao responsavel por resetar o mapa
function resetarMapaPartidaChegada() {
    if (mapaDadosPartidaChegada != "") {
        mapaDadosPartidaChegada.off();
        mapaDadosPartidaChegada.remove();
        mapaDadosPartidaChegadaObserver.disconnect();
    }
}

function limparMarcasNoMapaPartidaChegada(marcadores) {
    if (marcadores != null) {
        marcadores.forEach(marker => {
            mapaDadosPartidaChegada.removeLayer(marker);
        });
    }
}

//marca os pins no mapa
function marcarNoMapaPartidaChegada(dados) {
    let popup;
    let marker;

    if (dados != null) { // todos os eventos selecionados
        if (dados.length != 0) {

            popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUpPartidaChegada(dados));

            var dotStyles = `
                    background-color: #FFFFFF; /* Cor de fundo branca */
                    width: 1rem;
                    height: 1rem;
                    display: block;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    border-radius: 50%; /* Bordas arredondadas para formar uma bolinha */
                `;

            var markerHtmlStyles = `
                background-color: #0064FF;
                width: 3rem;
                height: 3rem;
                display: block;
                left: -1.5rem;
                top: -1.5rem;
                position: relative;
                border-radius: 3rem 3rem 0;
                transform: rotate(45deg);
                border: 1px solid #FFFFFF;
            `;

            var icon = L.divIcon({
                className: "my-custom-pin",
                iconAnchor: [0, 24],
                labelAnchor: [-6, 0],
                popupAnchor: [0, -36],
                html: `
                        <span style="${markerHtmlStyles}">
                        <span style="${dotStyles}">
                        </span>
                    `
            });

            marker = L.marker([dados.latitude, dados.longitude], {
                draggable: false, id: -1, icon: icon
            }).addTo(mapaDadosPartidaChegada);

            marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });

            marcadores.push(marker);
        }
    }

    return marcadores;
}

function htmlPopUpPartidaChegada(data) {
    return `
    <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item"> 
                <span class="item-popup-title">${lang.serial}:</span>      
                <span class="float-right" style="color:#909090">${data.serial ? data.serial : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Coordenadas:</span> <br/>   
                <div style="margin-top: 5px;">
                    <span class="badge badge-info" style="padding-top: 3px;">${data.latitude}</span>
                    <span class="badge badge-info" style="padding-top: 3px;">${data.longitude}</span> 
                </div>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Ref.:</span>      
                <span class="" style="color:#909090">${data.endereco ? data.endereco : "Referência não encontrada"}</span>
            </li>
        </ul>
    </div>
    `;
}


//funcao responsavel por carregar mapa e o ponto
function carregarMapaPartidaChegadaTela(lat = 0, log = 0, zoom = 4) {
    resetarMapaPartidaChegadaTela();
    //carregar mapa na mesma posicao do evento
    mapaDadosPartidaChegadaTela = L.map('mapaDadosPartidaChegadaTela', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaDadosPartidaChegadaTela.setView([lat, log], zoom);
    mapaDadosPartidaChegadaTela.dragging.enable();

    osm.addTo(mapaDadosPartidaChegadaTela);

    // Função para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaDadosPartidaChegadaTela.invalidateSize();
    }

    // Adicionar observador de mutação com jQuery
    const target = document.getElementById('mapaDadosPartidaChegadaTela');
    mapaDadosPartidaChegadaObserverTela = new MutationObserver(ajustarTamanhoMapa);

    const config = { attributes: true, childList: true, subtree: true };

    mapaDadosPartidaChegadaObserverTela.observe(target, config);

    // Certificar-se de chamar a função inicialmente
    ajustarTamanhoMapa();

}

//funcao responsavel por resetar o mapa
function resetarMapaPartidaChegadaTela() {
    if (mapaDadosPartidaChegadaTela != "") {
        mapaDadosPartidaChegadaTela.off();
        mapaDadosPartidaChegadaTela.remove();
        mapaDadosPartidaChegadaObserverTela.disconnect();
    }
}

function limparMarcasNoMapaPartidaChegadaTela(marcadores) {
    if (marcadores != null) {
        marcadores.forEach(marker => {
            mapaDadosPartidaChegadaTela.removeLayer(marker);
        });
    }
}

//marca os pins no mapa
function marcarNoMapaPartidaChegadaTela(dados) {
    let popup;
    let marker;

    if (dados != null) { // todos os eventos selecionados
        if (dados.length != 0) {

            popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUpPartidaChegadaTela(dados));
            

            var dotStyles = `
                    background-color: #FFFFFF; /* Cor de fundo branca */
                    width: 1rem;
                    height: 1rem;
                    display: block;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    border-radius: 50%; /* Bordas arredondadas para formar uma bolinha */
                `;

            var markerHtmlStyles = `
                background-color: #${dados.tipo_match == 1 ? 'FF002D' : '0064FF'};
                width: 3rem;
                height: 3rem;
                display: block;
                left: -1.5rem;
                top: -1.5rem;
                position: relative;
                border-radius: 3rem 3rem 0;
                transform: rotate(45deg);
                border: 1px solid #FFFFFF;
            `;

            var icon = L.divIcon({
                className: "my-custom-pin",
                iconAnchor: [0, 24],
                labelAnchor: [-6, 0],
                popupAnchor: [0, -36],
                html: `
                        <span style="${markerHtmlStyles}">
                        <span style="${dotStyles}">
                        </span>
                    `
            });


            marker = L.marker([dados.latitude, dados.longitude],
                {
                    draggable: false, id: -1, icon: icon
                }).addTo(mapaDadosPartidaChegadaTela);

            marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });

            marcadores.push(marker);
        }
    }

    return marcadores;
}

//funcao interna responsavel pelo popup com as informacoes dos eventos
function htmlPopUpPartidaChegadaTela(data) {
    return `
   <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item"> 
                <span class="item-popup-title">${lang.serial}:</span>      
                <span class="float-right" style="color:#909090">${data.serial ? data.serial : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Coordenadas:</span> <br/>   
                <div style="margin-top: 5px;">
                    <span class="badge badge-info" style="padding-top: 3px;">${data.latitude}</span>
                    <span class="badge badge-info" style="padding-top: 3px;">${data.longitude}</span> 
                </div>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Ref.:</span>      
                <span class="" style="color:#909090">${data.endereco ? data.endereco : "Referência não encontrada"}</span>
            </li>
        </ul>
    </div>
    `;
}