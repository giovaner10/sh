$(document).ready(function() {
    criarAgGrid();

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = $(this).attr("data-tableId");
        abrirDropdown(dropdownId, buttonId, tableId);
    });
});

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);
    var altDropdown = dropdown.height() + 10;
    dropdown.css('bottom', `auto`).css('top', '100%');
 
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posDropdown = $('#' + dropdownId).get(0).getBoundingClientRect().bottom;
    var posDropdownTop = $('#' + dropdownId).get(0).getBoundingClientRect().top;

    if (altDropdown > (posBordaTabela - posDropdownTop)) {
        if (altDropdown < (posDropdownTop - posBordaTabelaTop)){
            dropdown.css('top', `auto`).css('bottom', '80%');
        } else {
            let diferenca = altDropdown - (posDropdownTop - posBordaTabelaTop);
            dropdown.css('top', `-${(altDropdown - 60) - (diferenca) }px`);
        }
    }
}

// Ajax
function getAgendamentosAgGrid(callback) {
    let router = Router + '/buscarAgendamentos';
    $.ajax({
        url: router,
        type: 'POST',
        dataType: 'json',
        beforeSend: function() {
            AgGrid.gridOptions.api.setRowData([]);
            AgGrid.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data && data.status == 200) {
                AgGrid.gridOptions.api.setRowData(data.dados);
            } else if (data && data.status == 404) {
                AgGrid.gridOptions.api.setRowData([]);
            } else {
                showAlert('error', 'Não foi possível listar as permissões!');
                AgGrid.gridOptions.api.setRowData([]);
            }
            if( typeof callback == 'function') callback();
        },
        error: function () {
            showAlert('error', 'Não foi possível listar as permissões!');
            AgGrid.gridOptions.api.setRowData([]);
            if( typeof callback == 'function') callback();
        }
    });
}

function formularioNovaAgendamento()
{
    ShowLoadingScreen();

    // Modal
    $("#modalAgendamento").load(Router + "/formularioAgendamento",
        function()
        {
            HideLoadingScreen();
        });
}

function formularioEditarAgendamento(agendamentoId)
{
    ShowLoadingScreen();
    
    // Modal
    $("#modalAgendamento").load(
        Router + "/formularioAgendamento/" + agendamentoId,
        function()
        {
            HideLoadingScreen();
        }
    );
}

function modalExcluirAgendamento(agendamentoId)
{
    Swal.fire({
        title: "Atenção!",
        text: `Tem certeza que deseja excluir esse Agendamento?`,
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: Router + "/excluirAgendamento/" + agendamentoId,
                type: "POST",
                dataType: "JSON",
                beforeSend: function() {
                    ShowLoadingScreen();
                },
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        // Mensagem de retorno
                        showAlert('success', retorno.mensagem);
        
                        // Recarrega a tabela
                        getAgendamentosAgGrid();
                    }
                    else
                    {
                        // Mensagem de retorno
                        showAlert('warning', retorno.mensagem);
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    // Mensagem de retorno
                    showAlert('warning', lang.mensagem_erro);
                },
                complete: function ()
                {
                    // Carregado
                    HideLoadingScreen();
                }
            });
        }
    });
}

// AgGrid
var AgGrid;
function criarAgGrid() {
    stopAgGRID();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Título',
                field: 'titulo_agendamento',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                sortable: false,
                filter: false,
                resizable: false,
                suppressMenu: true,
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "table";
                    let dropdownId = "dropdown-menu" + data.id;
                    let buttonId = "dropdownMenuButton_" + data.id
                    let btnsComPermissao = '';
                    if (data.id) {
                        if (canEdit == "true") {
                            btnsComPermissao += `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:formularioEditarAgendamento('${data.id}')" style="cursor: pointer; color: black;">Editar</a>
                                </div>
                            `;
                        } 
                        
                        if (canDel == "true") {
                            btnsComPermissao += `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:modalExcluirAgendamento('${data.id}')" style="cursor: pointer; color: black;">Excluir</a>
                                </div>
                            `;
                        }
                        return `
                            <div class="dropdown dropdown-table" data-tableId="${tableId}" style="position: relative;">
                                <button class="btn btn-dropdown dropdown-toggle" onclick=fecharDrop() type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="${BaseURL + 'uploads/processos_atendimento_agendamento/' + data.file}" target="blank_" style="cursor: pointer; color: black;">${lang.visualizar}</a>
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="${BaseURL + 'uploads/processos_atendimento_agendamento/' + data.file}" download="${data.titulo_agendamento}" style="cursor: pointer; color: black;">${lang.baixar}</a>
                                    </div>
                                    ${btnsComPermissao}
                                </div>
                            </div>`;
                    } else {
                        return '';
                    }
                },
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            filter: true,
            resizable: true,
            suppressMenu: false
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
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        localeText: AG_GRID_LOCALE_PT_BR,
        domLayout: 'normal',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>'
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '530px');
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    $('#search-input').off().on('input', function() {
        var searchInput = $(this).val();
        gridOptions.api.setQuickFilter(searchInput);
    });

    getAgendamentosAgGrid();
    
    preencherExportacoes(gridOptions, "Relatório de Agendamento", "Agendamento", 'opcoes_exportacao');
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#table');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapper');
    if (wrapper) {
        wrapper.innerHTML = '<div id="table" class="ag-theme-alpine my-grid"></div>';
    }
}

// Visibiliade
function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}