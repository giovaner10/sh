var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGridCentral();

    $("#BtnAdicionarCentral").on('click', function () {
        $("#addCentral").modal('show');
    })

    $('#ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/,
                optional: true
            }
        }
    });

    $('#cnpj').mask('00.000.000/0000-00', {
        reverse: true
    });

    $('#formAddCentral').on('submit', function (event) {
        event.preventDefault();

        var ipAddress = $('#ip').val();
        var cnpj = $('#cnpj').val();

        if (ipAddress.length !== 15) {
            alert("Tamanho inválido para IP");
            return;
        } else if (cnpj.length !== 18) {
            alert("Tamanho inválido para CNPJ");
            return;
        } else {
            let form = arrayToObject($(this).serializeArray());
            cadastrarCentral(form);
        }
    });

    $('#BtnLimparFiltro').on('click', function (){
        $('#busca').val('');

        var event = new Event('input', {
            bubbles: true,
            cancelable: true,
        });
    
        document.querySelector('#busca').dispatchEvent(event);
    })

})

function cadastrarCentral(form) {
    var route = Router + '/inserir_centrais';
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            nome: form.nome,
            ip: form.ip,
            porta: form.porta,
            ativa: form.ativa,
            cnpj: form.cnpj,
            cliente: form.cliente
        },
        dataType: 'json',
        async: true,
        success: function (data) {
            alert(data['message']);
        },
        error: function (error) {
            console.error('Erro na solicitação ao servidor:', error);
            params.failCallback();
        },
    });
}

function arrayToObject(serializedArray) {
    var obj = {};
    serializedArray.forEach(function (item) {
        obj[item.name] = item.value;
    });
    return obj;
}

var AgGridCentral;
function atualizarAgGridCentral() {
    stopAgGRIDCentral();
    disabledButtons();
    
    function getServerSideDados(callback) {
        var route = Router + '/ajaxListCentrais';
        $.ajax({
            cache: false,
            url: route,
            type: 'GET',
            dataType: 'json',
            async: true,
            beforeSend: function () {
                gridOptions.api.showLoadingOverlay();
            },
            success: function (data) {
                if (data && data.length > 0) {
                    var dados = data;
                    for (let i = 0; i < dados.length; i++) {
                        for (let chave in dados[i]) {
                            if (dados[i][chave] === null) {
                                dados[i][chave] = '';
                            }
                        }
                    }
                    callback(dados);
                } else {
                    callback([]);
                }
                enableButtons();
            },
            error: function (error) {
                console.error('Erro na solicitação ao servidor:', error);
                callback([]);
                enableButtons();
            },
            complete: function () {
                gridOptions.api.hideOverlay();
                enableButtons();
            }
        });
    }
 
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Nome',
                field: 'nome',
                chartDataType: 'category',
                width: 280,
                suppressSizeToFit: true
            },
            {
                headerName: 'IP',
                field: 'ip',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Porta',
                field: 'porta',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ativa',
                field: 'ativa',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['ativa'];
                    if (data == '1') {
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
                headerName: 'CNPJ',
                field: 'cnpj',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true,
            },
            {
                headerName: 'ID Central MHS',
                field: 'id_central_mhs',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            }
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-central').val()),
        cacheBlockSize: 50,
        localeText: localeText,
    };
 
    $('#select-quantidade-por-pagina-central').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-central').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });
 
    document.querySelector('#busca').addEventListener('input', function () {
        var searchInput = document.querySelector('#busca');
        gridOptions.api.setQuickFilter(searchInput.value);
    });
 
    var gridDiv = document.querySelector('#tableCentral');
    gridDiv.style.setProperty('height', '519px');
 
    new agGrid.Grid(gridDiv, gridOptions);
    getServerSideDados(function (datasource) {
        gridOptions.api.setRowData(datasource);
        preencherExportacoes(gridOptions);
    });
}

function stopAgGRIDCentral() {
    var gridDiv = document.querySelector('#tableCentral');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperCentral');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableCentral" class="ag-theme-alpine my-grid-central"></div>';
    }
}
 
function disabledButtons() {
    $('.btn').attr('disabled', true);
}
function enableButtons() {
    $('.btn').attr('disabled', false);
}