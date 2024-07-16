var localeText = AG_GRID_LOCALE_PT_BR;
let dadosInserir = [];
let seriaisConsulta;
let url = window.location.href;

//MAPA
var mapaDadosPartidaChegada = "";
let mapaDadosPartidaChegadaObserver
var markers = L.layerGroup();
var marcadores = [];
var marcadoresPartidaChegada = [];

$(document).ready(function () {
    atualizarAgGridStatus([]);

    document.getElementById("serialBusca", "inputSerial").addEventListener("input", function (event) {
        var input = event.target;
        input.value = input.value.toUpperCase();
    })

    $("#serialBusca, #inputSerial").on('keypress', function (e) {
        if (e.keyCode < 32 || e.keyCode === 127) {
            return true;
        }

        let char = String.fromCharCode(e.which || e.keyCode);
        if (!char.match(/[a-zA-Z0-9]/)) {
            e.preventDefault();
        }
    });

    $("#serialBusca, #inputSerial").on('input', function () {
        if (!this.value.startsWith("I")) {
            this.value = "I" + this.value;
        }
    });


    $("#btnVisualizarEmTela").click(function (e) {
        $('#serialEmTela').modal('show');
    });

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    // IMPORTAÇÃO ARQUIVO
    $("#BtnImportarArquivo").click(function (e) {
        $("#importarIscasArquivo").modal("show");
        preencherImportacaoArquivo([]);
    });

    $("#importarIscasArquivo").on("hide.bs.modal", function () {
        seriaisConsulta = [];
    });

    //IMPORTAÇÃO INPUT LOTE
    $("#BtnImportarLote").click(function (e) {
        $("#importarIscasLote").modal("show");
        preencherImportacaoLote([]);
    });

    $("#inputSerial").keypress(function (e) {
        if (e.which === 13) {
            adicionarSerial($(this).val().trim().toUpperCase());
            $(this).val("");
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

    $("#btnSalvarImportacaoIsca").click(function (e) {
        showLoadingSalvarImportButton();
        getDadosInputArquivo();
    })

    $("#btnSalvarImportacaoIscaLote").click(function (e) {
        if (dadosInserir.length > 0) {
            showLoadingSalvarImportLoteButton();
            getDadosInput();
        } else {
            showAlert("warning","Adicione pelo menos um serial à lista.");
            $("#btnSalvarImportacaoIscaLote").blur();
            return;
        }
    })

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
            showAlert("warning","Informe um serial para pesquisar.")
        }
    })

    $("#BtnLimparFiltro").click(function (e) {
        atualizarAgGridStatus([]);
        if ($("#serialBusca").val() != '') {
            limparPesquisa()
        } else {
            $("#serialBusca").val("");
            $("#btnVisualizarEmTela").hide();
            $(".resultadoBuscaSerial").empty();
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
});


function importarItensExcel(event) {
    event.preventDefault();
    const fileInput = document.getElementById('arquivoItens');
    const file = fileInput.files[0];

    if (!file) {
        alert('Por favor, selecione um arquivo.');
        return;
    }

    const validExtensions = ['.xls', '.xlsx'];
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

    if (!validExtensions.includes(fileExtension)) {
        alert('Por favor, selecione um arquivo Excel válido (.xls ou .xlsx).');
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
            alert('O arquivo está vazio.');
            return;
        }


        if (jsonData[0].length !== 1 || jsonData[0][0].toLowerCase() !== 'serial') {
            alert('A planilha deve conter exatamente uma coluna com o cabeçalho "SERIAL".');
            return;
        }

        jsonData.shift();

        if (jsonData.length === 0) {
            alert('Não há dados válidos na planilha.');
            return;
        }

        let seriaisSet = new Set();
        let duplicatasEncontradas = false;

        jsonData.forEach((row) => {
            if (row.length > 0 && row[0] !== null && row[0] !== undefined) {
                let serialOriginal = String(row[0]).toUpperCase();
                let serial = serialOriginal.startsWith("I") ? serialOriginal : "I" + serialOriginal;
                let serialLimpo = removerAcentosEspeciais(serial);
                if (!seriaisSet.has(serialLimpo)) {
                    seriaisSet.add(serialLimpo);
                } else {
                    duplicatasEncontradas = true;
                }
            }
        });


        var dadosInserir = Array.from(seriaisSet).map(serial => ({ serial: serial.toUpperCase() }));

        if (dadosInserir.length > 500) {
            alert('A planilha contém mais de 500 registros. Apenas os primeiros 500 registros foram processados.');
            dadosInserir = dadosInserir.slice(0, 500);
        }

        preencherImportacaoArquivo(dadosInserir);
        fileInput.value = '';

        if (duplicatasEncontradas) {
            alert('Foram identificados e removidos registros duplicados durante a importação.');
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
        alert("É necessário digitar um serial.");
        return;
    }

    if (dadosInserir.some(item => item.serial === serial)) {
        alert("Serial já informado.");
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
    var route = Router + '/verificarSerial';

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
                var serialMensagens = {};

                data.resultado.serialStatusList.forEach(function (item) {
                    if (!serialMensagens[item.serial]) {
                        serialMensagens[item.serial] = {
                            mensagens: [],
                            latitude: item.latitude,
                            longitude: item.longitude,
                            endereco: item.endereco
                        };
                    }
                    serialMensagens[item.serial].mensagens.push(item.status);
                });

                var dadosAgrupados = [];
                for (var serial in serialMensagens) {
                    if (serialMensagens.hasOwnProperty(serial)) {
                        let mensagensComIndice = serialMensagens[serial].mensagens.map((mensagem, index) => `${index + 1}. ${mensagem}`).join(' / ');
                        dadosAgrupados.push({
                            serial: serial,
                            mensagens: mensagensComIndice,
                            latitude: serialMensagens[serial].latitude || '',
                            longitude: serialMensagens[serial].longitude || '',
                            endereco: serialMensagens[serial].endereco || ''
                        });
                    }
                }
                atualizarAgGridStatus(dadosAgrupados);
                $('#importarIscasLote').modal('hide');
                resetSalvarImportLoteButton();
            }
            else if (data.status == 400) {
                const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                alert(errorMessage);
                resetSalvarImportLoteButton()
            } else if (data.status == 404) {
                alert("Dados não encontrados.")
                resetSalvarImportLoteButton()
            } else {
                alert("Erro interno do servidor. Entre em contato com o Suporte Técnico")
            }
        },
    });
}

function getDadosInputArquivo() {
    var dados = {
        seriais: seriaisConsulta
    };
    var route = Router + '/verificarSerial';

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
                var serialMensagens = {};

                data.resultado.serialStatusList.forEach(function (item) {
                    if (!serialMensagens[item.serial]) {
                        serialMensagens[item.serial] = [];
                    }
                    serialMensagens[item.serial].push(item.status);
                });

                var dadosAgrupados = [];
                for (var serial in serialMensagens) {
                    if (serialMensagens.hasOwnProperty(serial)) {
                        let mensagensComIndice = serialMensagens[serial].map((mensagem, index) => `${index + 1}. ${mensagem}`).join(' / ');
                        dadosAgrupados.push({
                            serial: serial,
                            mensagens: mensagensComIndice
                        });
                    }
                }
                atualizarAgGridStatus(dadosAgrupados);
                $('#importarIscasArquivo').modal('hide');
                resetSalvarImportButton()
            } else if (data.status == 400) {
                const errorMessage = data.resultado?.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                alert(errorMessage);
                resetSalvarImportButton()
            } else if (data.status == 404) {
                alert("Dados não encontrados.")
                resetSalvarImportButton()
            } else {
                alert("Erro interno do servidor. Entre em contato com o Suporte Técnico")
            }
        },
    });
}

function getDadosTela() {
    var route = Router + "/verificarSerial";

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
                var serialMensagens = {};

                data.resultado.serialStatusList.forEach(function (item) {
                    if (!serialMensagens[item.serial]) {
                        serialMensagens[item.serial] = {
                            mensagens: [],
                            latitude: item.latitude,
                            longitude: item.longitude,
                            endereco: item.endereco
                        };
                    }
                    serialMensagens[item.serial].mensagens.push(item.status);
                });

                var dadosAgrupados = [];
                for (var serial in serialMensagens) {
                    if (serialMensagens.hasOwnProperty(serial)) {
                        let mensagensComIndice = serialMensagens[serial].mensagens.map((mensagem, index) => `${index + 1}. ${mensagem}`).join(' / ');
                        dadosAgrupados.push({
                            serial: serial,
                            mensagens: mensagensComIndice,
                            latitude: serialMensagens[serial].latitude,
                            longitude: serialMensagens[serial].longitude,
                            endereco: serialMensagens[serial].endereco
                        });
                    }
                }
                preencherModal(data.resultado.serialStatusList);
                var mensagemHtml = dadosAgrupados
                    .map(
                        (item) => `
                                <h4 style="font-size: 17px; color: #1C69AD; text-align: center; margin-top: 10px; margin-bottom: 10px;">${item.serial.toUpperCase()}</h4>
                                <div style="margin-bottom: 5px; word-wrap: break-word; word-break: break-all; white-space: normal; overflow-wrap: break-word;">${item.mensagens
                                .split(" / ")
                                .join("<br>")}
                                <hr>
                                <h4 style="font-size: 17px; color: #1C69AD; text-align: left; margin-bottom: 5px;">Dados da Isca:</h4>
                                <div style="margin-bottom: 5px; word-wrap: break-word; word-break: break-all; white-space: normal; overflow-wrap: break-word;">Endereço: ${item.endereco}
                                <br>
                                Latitude: ${item.latitude}
                                <br>
                                Longitude: ${item.longitude}
                                </div>
                                `
                    )
                    .join("");
                $(".resultadoBuscaSerial").html(mensagemHtml);
                $("#btnVisualizarEmTela").show();
                resetPesquisarButton();
                enableButtons([
                    "#BtnLimparFiltro",
                    "#dropdownMenuButtonImportacao",
                    "#dropdownMenuButton",
                    '#btnVisualizarEmTela'
                ]);
            } else if (data.status == 400) {
                const errorMessage = data.resultado
                    ? data.resultado[0]
                    : data.resultado;
                alert(errorMessage);
                resetPesquisarButton();
                enableButtons([
                    "#BtnLimparFiltro",
                    "#dropdownMenuButtonImportacao",
                    "#dropdownMenuButton",
                    '#btnVisualizarEmTela'
                ]);
            } else if (data.status == 404) {
                alert("Dados não encontrados.");
                resetPesquisarButton();
                enableButtons([
                    "#BtnLimparFiltro",
                    "#dropdownMenuButtonImportacao",
                    "#dropdownMenuButton",
                    '#btnVisualizarEmTela'
                ]);
            } else {
                alert(
                    "Erro interno do servidor. Entre em contato com o Suporte Técnico"
                );
                resetPesquisarButton();
                enableButtons([
                    "#BtnLimparFiltro",
                    "#dropdownMenuButtonImportacao",
                    "#dropdownMenuButton",
                    '#btnVisualizarEmTela'
                ]);
            }
            $('#serialBusca').val('');
        },
        error: function () {
            alert("Erro de comunicação com o servidor.");
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
function atualizarAgGridStatus(dados) {
    stopAgGRIDStatus()

    const gridOptions = {
        columnDefs: [
            {
                headerName: "Serial Isca",
                field: "serial",
                width: 150,
                autoHeight: true,
                sortable: true,
            },
            {
                headerName: "Status",
                field: "mensagens",
                width: 400,
                wrapText: true,
                sortable: true,
                autoHeight: true,
                cellRenderer: function (params) {
                    const messages = params.value.split(' / ').map((message, index) => `<div>${message}</div>`).join('');
                    return messages;
                }
            },
            {
                headerName: "Endereço",
                field: "endereco",
                flex: 1,
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
                    let tableId = "tableStatusIsca";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdownModal('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:visualizarIsca('${data.serial}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                        </div>
                    </div>
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-StatusIsca').val()),
    };

    var gridDiv = document.querySelector('#tableStatusIsca');
    gridDiv.style.setProperty('height', '519px');
    AgGridCemUltSolic = new agGrid.Grid(gridDiv, gridOptions);
    AgGridCemUltSolic.gridOptions.api.setRowData(dados);

    $('#select-quantidade-por-pagina-StatusIsca').change(function () {
        var selectedValue = $(this).val();
        AgGridCemUltSolic.gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    preencherExportacoesIsca(gridOptions, "Relatórido de Validação de Iscas");
}

function stopAgGRIDStatus() {
    var gridDiv = document.querySelector('#tableStatusIsca');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperStatusIsca');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableStatusIsca" class="ag-theme-alpine my-grid-StatusIsca"></div>';
    }
}

async function visualizarIsca(serial) {
    let dados = await buscarIsca(serial);
    $('#detalheSerial').text(dados['resultado']['serialStatusList'][0]['serial']);
    $('#detalheStatus').text(dados['resultado']['serialStatusList'][0]['status']);
    $('#detalheEndereco').text(dados['resultado']['serialStatusList'][0]['endereco']);
    $('#detalheLatitude').text(dados['resultado']['serialStatusList'][0]['latitude']);
    $('#detalheLongitude').text(dados['resultado']['serialStatusList'][0]['longitude']);

    $('#visualizarIsca').modal('show');
    limparMarcasNoMapaPartidaChegada(marcadoresPartidaChegada);
    carregarMapaPartidaChegada(-15.39, -55.73, 4);
    marcadoresPartidaChegada = marcarNoMapaPartidaChegada(dados['resultado']['serialStatusList'][0]);
}

function buscarIsca(serial) {
    ShowLoadingScreen();
    var dados = {
        seriais: [serial]
    };

    var route = Router + '/verificarSerial';

    return new Promise((resolve, reject )=> {
        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            dataType: 'json',
            async: true,
            success: function (data) {
                if(data.status == 200){
                    resolve(data);
                }
                else{
                    reject("Dados não encontrados para a isca");
                }
                HideLoadingScreen();
            },
        });
    }) 
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
            headerName: "Serial Isca",
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
                let tableId = "tableImportacaoIsca";
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

    var gridDiv = document.querySelector('#tableImportacaoIsca');
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
    var gridDiv = document.querySelector('#tableImportacaoIsca');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperImportacaoIsca');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoIsca" class="ag-theme-alpine my-grid-isca"></div>';
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
            headerName: "Serial Isca",
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
                let tableId = "tableImportacaoIscaLote";
                let dropdownId = "dropdown-menu" + data.serial + varAleatorioIdBotao;
                let buttonId = "dropdownMenuButton_" + data.serial + varAleatorioIdBotao;

                return `
                <div class="dropdown">
                    <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" aria-labelledby="${buttonId}">
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

    var gridDiv = document.querySelector('#tableImportacaoIscaLote');
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

function stopAgGRIDImportacaoLote() {
    var gridDiv = document.querySelector('#tableImportacaoIscaLote');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperImportacaoIscaLote');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoIscaLote" class="ag-theme-alpine my-grid-isca-lote"></div>';
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
    dadosInserir = []
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

function preencherModal(serialStatusList) {
    var serialBuscado = $("#serialBusca").val().toUpperCase();
    var statusEncontrados = serialStatusList.filter(item => item.serial.toUpperCase() === serialBuscado);

    if (statusEncontrados.length === 0) {
        $('#statusIscaModal').html('<p style="font-size: 17px; margin-left: 10px;">Sem informações para o serial fornecido.</p>');
    } else {
        var statusHtml = statusEncontrados
            .map((item, index) => `<p style="font-size: 17px; margin-left: 10px;">${index + 1}. ${item.status}</p>`)
            .join('');

        $('#statusIscaModal').html(statusHtml);
        // Inserir os dados da isca
        var enderecoHtml = `Endereço: ${serialStatusList[0].endereco}<br>`;
        var latitudeHtml = `Latitude: ${serialStatusList[0].latitude}<br>`;
        var longitudeHtml = `Longitude: ${serialStatusList[0].longitude}<br>`;
        var dadosIscaHtml = `<hr><h4 style="font-family: 'Mont SemiBold'; color: #1C69AD !important; font-size: 17px !important; font-weight: bold !important; padding: 10px 5px;">Dados da Isca:</h4><p style="font-size: 17px; margin-left: 10px;">${enderecoHtml}</p><p style="font-size: 17px; margin-left: 10px;">${latitudeHtml}</p><p style="font-size: 17px; margin-left: 10px;">${longitudeHtml}</p>`;
        $('#serialModal').text(`Informações do Serial # ${serialBuscado}`);
        $('#statusIscaModal').append(dadosIscaHtml);

    }
}

// visualizações:
function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function limparPesquisa() {
    $('#serialBusca').val('');
    $('#btnVisualizarEmTela').hide()
    $('.resultadoBuscaSerial').empty();
    dadosInserir = [];
    seriaisConsulta = [];
    atualizarAgGridStatus([]);

}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function showLoadingSalvarImportLoteButton() {
    $('#btnSalvarImportacaoIscaLote').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetSalvarImportLoteButton() {
    $('#btnSalvarImportacaoIscaLote').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarImportButton() {
    $('#btnSalvarImportacaoIsca').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetSalvarImportButton() {
    $('#btnSalvarImportacaoIsca').html('Salvar').attr('disabled', false);
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

//MAPA
function carregarMapaPartidaChegada(lat = 0, log = 0, zoom = 2) {
    resetarMapaPartidaChegada();
    //carregar mapa na mesma posicao do evento
    mapaDadosPartidaChegada = L.map('mapaDadosPartidaChegada', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
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


            marker = L.marker([dados.latitude, dados.longitude],
                {
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