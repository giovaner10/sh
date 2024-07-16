var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGridReleases();

    $('#BtnAdicionarReleases').on('click', function () {
        $('#modalReleaseNotes').modal('show');

    })

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#modalReleaseNotes').on('hide.bs.modal', function () {
        $('#form_release').trigger('reset');
        $('#titleRelease').text("Cadastrar Release")
    })

    $('#buttonSalvarRelease').on('click', function () {
        let nomeRelease = $('#nomeRelease').val();
        let nomeArquivo = $('#nomeArquivo').val();
        let idRelease = $('#idRelease').val();
        let releaseNotes = $("#nomeArquivo").prop("files")[0];

        if ($('#titleRelease').html() == "Editar Release") {
            if (nomeRelease == '' || nomeArquivo == '') {
                showAlert("warning",'Preencha todos os campos');
                return;

            } else {
                DadosReleaseNotes = {
                    idUsuario: $('#idUsuario').val(),
                    nomeRelease: $('#nomeRelease').val(),
                    nomeArquivo: releaseNotes.name,
                    arquivoBase64: null,
                    idRelease: $('#idRelease').val()
                };

                convertFileToBase64(releaseNotes, function (base64ReleaseNotes) {
                    DadosReleaseNotes.arquivoBase64 = base64ReleaseNotes;

                    ShowLoadingScreen();
                    cadastrarRelease(DadosReleaseNotes);
                })
            }

        } else {
            if (nomeRelease == '' || nomeArquivo == '') {
                showAlert("warning", 'Preencha todos os campos');
                return;
            }

            DadosReleaseNotes = {
                idUsuario: $('#idUsuario').val(),
                nomeRelease: $('#nomeRelease').val(),
                nomeArquivo: releaseNotes.name,
                arquivoBase64: null
            };

            convertFileToBase64(releaseNotes, function (base64ReleaseNotes) {
                DadosReleaseNotes.arquivoBase64 = base64ReleaseNotes;

                ShowLoadingScreen();
                cadastrarRelease(DadosReleaseNotes);
            })
        }


    })

    $('#formBusca').submit(function (e) {
        e.preventDefault();

        let searchOptions = {
            releaseNote: $('#release').val(),
            dataInicio: formatarData($('#dataInicial').val()),
            dataFim: formatarData($('#dataFinal').val()),
        }

        if (searchOptions.releaseNote || searchOptions.dataInicio || searchOptions.dataFim) {
            showLoadingPesquisarButton();
            disabledButtons();
            if (validacaoFiltros()) {
                atualizarAgGridReleases(searchOptions);
            } else {
                resetPesquisarButton();
                enableButtons();
                return;
            }
        } else {
            exibirAlerta('warning', 'Falha!', 'Digite pelo menos um campo para fazer a pesquisa.');
        }

    });


})

function validacaoFiltros() {
    dataInicio = $("#dataInicial").val();
    dataFim = $("#dataFinal").val();

    if (!dataInicio && !dataFim) {
        showAlert("warning", "É necessário informar a data inicial e data final");
        return false;
    }

    if (dataInicio && !dataFim) {
       showAlert("warning", "É necessário informar a data final");
        return false;
    }

    if (!dataInicio && dataFim) {
        showAlert("warning", "É necessário informar data inicial");
        return false;
    }

    if (!validarDatas(dataInicio, dataFim)) {
        return false;
    }

    return true;
}

function validarDatas(dataInicialStr, dataFinalStr) {
    const dataInicial = new Date(dataInicialStr);
    const dataFinal = new Date(dataFinalStr);
    const dataAtual = new Date();

    dataAtual.setHours(0, 0, 0, 0);

    const umDiaEmMilissegundos = 24 * 60 * 60 * 1000;

    const diferencaEmDias = Math.round(Math.abs((dataFinal - dataInicial) / umDiaEmMilissegundos));

    if (diferencaEmDias > 30) {
        showAlert("warning", "O período de busca não pode exceder 30 dias.");
        return false;
    }

    if (dataInicial > dataFinal) {
       showAlert("warning", "A data inicial não pode ser maior que a data final.");
        return false;
    }

    if (dataFinal > dataAtual) {
      showAlert("warning", "A data final não pode ser maior que a data atual.");
        return false;
    }

    if (dataInicial > dataAtual) {
       showAlert("warning", "A data inicial não pode ser maior que a data atual.");
        return false;
    }

    return true;
}

function formatarData(data) {
    let partesData = data.split('-');
    let dataFormatada = partesData[2] + '/' + partesData[1] + '/' + partesData[0];
    return dataFormatada;
}

function exibirAlerta(icon, title, text) {
    Swal.fire({
        position: 'top-start',
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true
    });
}

$('#BtnLimparFiltro').on('click', function (e) {
    $('#formBusca').trigger("reset");
    atualizarAgGridReleases();
});

var AgGridReleases;
function atualizarAgGridReleases(options) {
    stopAgGRIDAbaTecnologias();
    disabledButtons();
    showLoadingPesquisarButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/listarReleases';
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        releaseNote: options ? options.releaseNote : '',
                        dataInicio: options ? options.dataInicio : '',
                        dataFim: options ? options.dataFim : ''
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.lastRow;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.rows,
                            });
                        } else if (data && data.message) {
                            alert(data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        } else {
                            showAlert("error",'Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        }
                        enableButtons();
                        resetPesquisarButton();

                    },
                    error: function (error) {
                       showAlert("error",'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        enableButtons();
                        resetPesquisarButton();
                    },
                });

                $("#loadingMessageReleases").hide();
            },
        };
    }


    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Título da Release',
                field: 'releaseNote',
                chartDataType: 'category',
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data',
                field: 'data',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 200,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 100,
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['status'];
                    if (data == 'ativo') {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `
                    }
                }
            },
            {
                headerName: 'Ações',
                pinned: 'right',
                width: 80,
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
                    let tableId = "tableReleases";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    if (permissaoEditarExcluir) {
                        return `
                        <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:editarRelease(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:visualizarRelease(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:baixarRelease(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Baixar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:inativarRelease(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Inativar</a>
                                </div>
                            </div>
                        </div>`;
                    } else {
                        return `
                        <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:visualizarRelease(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:baixarRelease(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Baixar</a>
                                </div>
                            </div>
                        </div>`;
                    }

                }
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
                    id: 'columns',
                    labelDefault: 'Colunas',
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
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-releases').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-releases').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-releases').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableReleases');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);

    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
}

function cadastrarRelease(dados) {
    if (dados.idRelease) {
        let route = Router + '/editarReleaseNote';

        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: dados,
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    showAlert("success",'Release editada com Sucesso');
                    $('#modalReleaseNotes').modal('hide');
                    atualizarAgGridReleases();
                } else if (data.status == 404) {
                    const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                    alert(errorMessage);
                } else if (data.status == 500) {
                    showAlert("error","Erro interno do servidor. Entre em contato com o suporte técnico");
                }
                HideLoadingScreen();
            },
            error: function (error) {
                showAlert("error",'Erro na solicitação ao servidor');
                HideLoadingScreen();
            }
        });

    } else {

        let route = Router + '/cadastrarRelease';

        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: dados,
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    showAlert("success",'Release adicionada com sucesso');
                    $('#modalReleaseNotes').modal('hide');
                    atualizarAgGridReleases();
                } else if (data.status == 404) {
                    const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                    alert(errorMessage);
                } else if (data.status == 500) {
                    showAlert("error","Erro interno do servidor. Entre em contato com o suporte técnico");
                }
                HideLoadingScreen();
            },
            error: function (error) {
              showAlert("error",'Erro na solicitação ao servidor');
                HideLoadingScreen();
            }
        });
    }
}

async function editarRelease(id) {
    let data = await listarReleaseById(id);
    $('#titleRelease').text('Editar Release');
    $('#idRelease').val(data['resultado']['id']);
    $('#idUsuario').val(data['resultado']['idUsuario']);
    $('#nomeRelease').val(data['resultado']['releaseNote']);
    $('#modalReleaseNotes').modal('show');

    var fileReleaseName = data['resultado']['nomeArquivo'];
    var fileReleaseNotes64 = data['resultado']['arquivoBase64'];

    // Decode os dados Base64 para um Uint8Array
    var byteCharacters = atob(fileReleaseNotes64);
    var byteNumbers = new Array(byteCharacters.length);
    for (var i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    var byteArray = new Uint8Array(byteNumbers);

    // Cria arquivos Blob usando o Uint8Array corretamente
    var fileReleaseBlob = new Blob([byteArray], { type: 'application/pdf' });

    // Cria FileList com base nos blobs
    var fileReleaseFileList = new DataTransfer();
    var fileRelease = new File([fileReleaseBlob], fileReleaseName, { type: 'application/pdf' });
    fileReleaseFileList.items.add(fileRelease);

    // Aqui está o ajuste para usar a lista de arquivos corretamente
    $('#nomeArquivo').prop('files', fileReleaseFileList.files);
}

async function visualizarRelease(id) {
    let data = await listarReleaseById(id);
    let binaryString = atob(data['resultado']['arquivoBase64']);

    let length = binaryString.length;
    let arrayBuffer = new Uint8Array(new ArrayBuffer(length));

    for (let i = 0; i < length; i++) {
        arrayBuffer[i] = binaryString.charCodeAt(i);
    }

    let blob = new Blob([arrayBuffer], { type: 'application/pdf' });
    let url = URL.createObjectURL(blob);

    let modal = document.getElementById('visualizarRelease');
    let releaseDiv = modal.querySelector('#releaseNote');

    releaseDiv.innerHTML = '';
    let iframe = document.createElement('iframe');
    iframe.id = 'pdfIframe';
    iframe.setAttribute('allowfullscreen', '')
    iframe.src = url;
    iframe.width = '100%';
    iframe.height = '500px';
    releaseDiv.appendChild(iframe);

    $(modal).modal('show');
}

async function baixarRelease(id) {
    let data = await listarReleaseById(id);
    downloadPDF(data['resultado']['arquivoBase64'], data['resultado']['releaseNote'])
}

function downloadPDF(base64String, fileName) {
    try {
        var binaryString = window.atob(base64String);
        var len = binaryString.length;
        var bytes = new Uint8Array(len);

        for (var i = 0; i < len; i++) {
            bytes[i] = binaryString.charCodeAt(i);
        }

        var blob = new Blob([bytes], { type: "application/pdf" });
        var link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = fileName;
        link.click();

        setTimeout(function () {
            window.URL.revokeObjectURL(link.href);
            link.remove();
        }, 100);
    } catch (error) {
        console.error("Error decoding Base64 string: ", error);
    }
}

function listarReleaseById(id) {
    ShowLoadingScreen();
    let route = Router + '/listarReleaseById';
    return new Promise((resolve, reject) => {
        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: {
                idRelease: id
            },
            dataType: 'json',
            success: function (data) {
                HideLoadingScreen();
                resolve(data);
            },
            error: function (error) {
                HideLoadingScreen();
                reject('Erro ao listar Releases');
            }
        });
    });
}

function inativarRelease(id) {
    ShowLoadingScreen();
    let route = Router + '/inativarRelease';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            idRelease: id
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
               showAlert("success",'Release inativada com sucesso');
                atualizarAgGridReleases();
            } else if (data.status == 404) {
                const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                alert(errorMessage);
            } else if (data.status == 500) {
               showAlert("error","Erro interno do servidor. Entre em contato com o suporte técnico");
            }
            HideLoadingScreen();
        },
        error: function (error) {
            showAlert("error",'Erro na solicitação ao servidor');
            HideLoadingScreen();
        }
    });
}

function convertFileToBase64(file, callback) {
    let reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function (event) {
        const base64String = event.target.result;

        var formatedBase64 = () => {
            const index = base64String.indexOf(",");
            if (index === -1) {
                return [base64String];
            }
            return base64String.substring(index + 1);
        };
        callback(formatedBase64());
    };
    reader.onerror = function (error) {
        console.error("Error: ", error);
        callback(null);
    };
}

function stopAgGRIDAbaTecnologias() {
    var gridDiv = document.querySelector('#tableReleases');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperReleases');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableReleases" class="ag-theme-alpine my-grid-releases"></div>';
    }
}

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);

    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }

    $(".dropdown-menu").hide();

    dropdown.show();
    var posDropdown = dropdown.height() + 4;

    var dropdownItems = $('#' + dropdownId + ' .dropdown-item-acoes');
    var alturaDrop = 0;
    for (var i = 0; i <= dropdownItems.length; i++) {
        alturaDrop += dropdownItems.height();
    }

    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)) {
            dropdown.css('top', `-${alturaDrop - 60}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(alturaDrop - 50) - (diferenca + diferenca * 0.5)}px`);
        }
    }

    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });

}

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
        $('#conteudo').css('margin-left', '0px');
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

function formatDateTime(date) {
    if (!date || typeof date !== 'string') {
        return "";
    }

    const parts = date.split(' ');
    const dateParts = parts[0] ? parts[0].split('-') : null;
    if (!dateParts || dateParts.length !== 3) {
        return "";
    }

    const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    const timePart = parts.length > 1 ? ` ${parts[1]}` : "";

    return formattedDate + timePart;
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function disabledButtons() {
    $('.btn').attr('disabled', true);
}
function enableButtons() {
    $('.btn').attr('disabled', false);
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}