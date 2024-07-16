$(document).ready(function() {
    criarAgGrid();

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = $(this).attr("data-tableId");
        abrirDropdown(dropdownId, buttonId, tableId);
    });

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#BtnLimparFiltro').on('click', function () {
        var searchInput = document.querySelector('#busca');
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
    })
});

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
function getReclameAquiAgGrid(callback) {
    let router = Router + '/buscarReclameAqui';
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
                showAlert('error', 'Não foi possível listar os dados de Reclame Aqui!');
                AgGrid.gridOptions.api.setRowData([]);
            }
            if( typeof callback == 'function') callback();
        },
        error: function () {
            showAlert('error', 'Não foi possível listar os dados de Reclame Aqui!');
            AgGrid.gridOptions.api.setRowData([]);
            if( typeof callback == 'function') callback();
        }
    });
}

function formularioNovoReclameAqui()
{
    // Carregando
    ShowLoadingScreen();

    // Modal
    $("#modalReclameAqui").load(
        Router + "/formularioReclameAqui",
        function(response, status, xhr)
        {
            if (status == "error") {
                // Exibir uma mensagem de erro para o usuário
                showAlert("error", "Não foi possível abrir o modal de cadastro. Por favor, tente novamente mais tarde.");
                HideLoadingScreen();
            } else {
                // Se a requisição for bem-sucedida, ocultar a tela de carregamento
                HideLoadingScreen();
            }
        });
}

function formularioEditarReclameAqui(reclame_aquiId)
{
    // Carregando
    ShowLoadingScreen();
    
    // Modal
    $("#modalReclameAqui").load(
        Router + "/formularioReclameAqui/" + reclame_aquiId,
        function(response, status, xhr)
        {
            if (status == "error") {
                // Exibir uma mensagem de erro para o usuário
                showAlert("error", "Não foi possível abrir o modal de edição. Por favor, tente novamente mais tarde.");
                HideLoadingScreen();
            } else {
                // Se a requisição for bem-sucedida, ocultar a tela de carregamento
                HideLoadingScreen();
            }
        }
    );
}

function modalExcluirReclameAqui(reclame_aquiId)
{
    abrirModalConfirmarExclusaoBootstrap(
        identificadorModalReclameAqui,
        reclame_aquiId,
        lang.confirmacao_exclusao_reclame_aqui // Texto modal
    );
}

function excluirReclameAqui()
{
    let reclame_aquiId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalReclameAqui);

    // Carregando
    ShowLoadingScreen();

    // Deleta
    $.ajax({
        url: Router + '/excluirReclameAqui/' + reclame_aquiId,
        type: "POST",
        dataType: "JSON",
        success: function (retorno)
        {
            if (retorno.status == 1)
            {
                // Mensagem de retorno
                showAlert('success', retorno.mensagem);

                fecharModalConfirmarExclusaoBootstrap(identificadorModalReclameAqui);

                // Recarrega a tabela
                getReclameAquiAgGrid();
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
            showAlert('error', lang.mensagem_erro);
            HideLoadingScreen();
        },
        complete: function ()
        {
            // Carregado
            HideLoadingScreen();
        }
    });
}
function modalExcluirReclameAqui(reclame_aquiId)
{
    Swal.fire({
        title: "Atenção!",
        text: `Tem certeza que deseja excluir esse Reclame Aqui?`,
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: Router + '/excluirReclameAqui/' + reclame_aquiId,
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
                        getReclameAquiAgGrid();
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
                    showAlert('error', lang.mensagem_erro);
                    HideLoadingScreen();
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
                suppressSizeToFit: true,
                sort: "desc"
            },
            {
                headerName: 'Título',
                field: 'titulo_reclame_aqui',
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
                                    <a href="javascript:formularioEditarReclameAqui('${data.id}')" style="cursor: pointer; color: black;">Editar</a>
                                </div>
                            `;
                        } 
                        
                        if (canDel == "true") {
                            btnsComPermissao += `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:modalExcluirReclameAqui('${data.id}')" style="cursor: pointer; color: black;">Excluir</a>
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
                                        <a href="${BaseURL + 'uploads/processos_atendimento_reclame_aqui/' + data.file}" download="${data.titulo_reclame_aqui}" style="cursor: pointer; color: black;">${lang.baixar}</a>
                                    </div>
                                    ${btnsComPermissao}
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="${BaseURL + 'uploads/processos_atendimento_reclame_aqui/' + data.file}" target="blank_" style="cursor: pointer; color: black;">${lang.visualizar}</a>
                                    </div>
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
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>'
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '530px');
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    document.querySelector('#busca').addEventListener('input', function () {
        var searchInput = document.querySelector('#busca');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    getReclameAquiAgGrid();
    
    preencherExportacoes(gridOptions, "Relatório de Reclame Aqui", "ReclameAqui", 'opcoes_exportacao');
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