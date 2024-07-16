var idClienteDoc;
var cliente = '';
var email = '';
var existingEmails;
var showMap = false;
var localeText = AG_GRID_LOCALE_PT_BR;
var marcadores = [];
var marcadoresPartidaChegada = [];
let typingTimer;
const doneTypingInterval = 1000; 
const minChars = 3; 

$(document).ready(function () {
    disabledButtons()
    showLoadingPesquisarButton()
    atualizarAgGrid()
    buscarUsuario()


    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#autorizadorBusca').on('select2:unselect', function (e) {
        disabledButtons();
        showLoadingPesquisarButton();
        atualizarAgGrid();
    });

    $('#formBusca').submit(async function(e) {
        e.preventDefault();
        showLoadingPesquisarButton();
        disabledButtons();
        const autorizador = $("#autorizadorBusca").val();
        if(!autorizador){
            showAlert("warning", "É necessário selecionar o autorizador.")
            resetPesquisarButton();
            enableButtons();
            return;

        }else if (autorizador) {
            showLoadingPesquisarButton();
            disabledButtons();
            let dados =   await getDados(autorizador);
            atualizarAgGridByUser(dados);
            }
        else {
            atualizarAgGrid();
            
        }
    })

    $('#formAssociarAprovador').submit(async function(e){
        e.preventDefault()
        const idAprovador = $("#idAprovador").val();
        const idUsuario = $("#idUsuarioShow").val();

        if((idAprovador != '' && idAprovador != null) && (idUsuario != '' && idUsuario != null)){
            disabledButtons();
            await enviarAssociação(idAprovador, idUsuario);

        }else {
            showAlert("warning", "É necessário selecionar o Usuário para realizar a associação.")
            resetSalvarBtn();
            enableButtons();
            return;
        }
    })



    $('#btnLimparFiltro').on('click', function() {
        if ($('#autorizadorBusca').val() != null ) {
        disabledButtons();
        showLoadingPesquisarButton();
        $('#autorizadorBusca').val(null).trigger('change');
        atualizarAgGrid()
    }})

    $("#addAssociacao").on("hidden.bs.modal", function () {
        $("#idAprovador").val('').trigger('change');
        $('#idUsuarioShow').val('').trigger('change');
    });
    

    var dropdown = $('#opcoes_exportacao');

    $('#dropdownMenuButton').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButton') {
            dropdown.hide();
        }
    });

    $("#autorizadorBusca").select2({
        placeholder: "Selecione o autorizador",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
        minimumInputLength: 4,
        ajax: {
            url: Router + '/buscarAutorizadoresSelect',
            type: "POST",
            dataType: "json",
            delay: 500,
            data: function (params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function (data) {
                if (data.status === 200) {
                    return {
                        results: data.resultado.map(item => {
                            return {
                                id: item.codigo,
                                text: item.nome
                            };
                        }),
                        pagination: {
                            more: false
                        }
                    };
                } else {
                    return {
                        results: [{
                            id: '',
                            text: 'Dados não encontrados'
                        }]
                    };
                }
            }
        }
    });

});

// Requisições

function getDados(usuario) {
    return new Promise((resolve, reject) => {
        var route = Router + '/buscarDadosAutorizadoresByUser';
        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: { usuario: usuario },
           dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    let dadosModificados = response.map(item => ({
                        ...item,
                        limite: item.limite ? `R$ ${parseFloat(item.limite).toFixed(2).replace('.', ',')}` : '',
                        limiteMaximo: item.limiteMaximo ? `R$ ${parseFloat(item.limiteMaximo).toFixed(2).replace('.', ',')}` : ''
                    }));
                    resolve(dadosModificados);
                } else {
                    showAlert("error", "Dados não encontrados.")
                    reject();
                }
            },
            error: function() {
                showAlert("error", "Erro na solicitação ao servidor. Contate o suporte técnico.")
                reject();
            }
        });
    });
}

// AGGRID
var AgGrid;
function atualizarAgGrid() {
    stopAgGRID();
    showLoadingPesquisarButton()
    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarDadosAutorizadores';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                    },
                    dataType: 'json',
                    async: true,
                    success: async function (data) {
                        // await aprovadorSelect2()
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    // Verifica se o valor é null e substitui por uma string vazia
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    } 
                                    if (chave === 'limite' || chave === 'limiteMaximo') {
                                        dados[i][chave] = `R$ ${(parseFloat(dados[i][chave]).toLocaleString("pt-BR", { minimumFractionDigits: 2 }))}`
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });

                            } else {
                            showAlert("error", "Dados não encontrados.")
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        }
                        resetPesquisarButton();
                        enableButtons();
                    },
                    error: function () {
                        showAlert("error", "Erro na solicitação ao servidor. Contate o suporte técnico.")
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        resetPesquisarButton();
                        enableButtons();
                    },
                });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Usuário',
                field: 'usuario',
                chartDataType: 'series',
                width:100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cód. Aprovador',
                field: 'codigo',
                chartDataType: 'series',
                width:150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Nome',
                field: 'nome',
                chartDataType: 'category',
                width:250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Limite',
                field: 'limite',
                chartDataType: 'category',
                width:250,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Limite Máximo',
                field: 'limiteMaximo',
                chartDataType: 'category',
                width:250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Login',
                field: 'login',
                chartDataType: 'series',
                flex: 1,
                minWidth: 120,
                suppressSizeToFit: true
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
                    let tableId = "table";
                    let dropdownId = "dropdown-menu-comandos-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButtonMenu_" + data.id + varAleatorioIdBotao;

                    let dataString = JSON.stringify(data);

                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:cadastrarAssociacao('${encodeURIComponent(dataString)}', 'Envio')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Associar</a>
                            </div>
                        </div>
                    </div>`;
                }
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true
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
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            let dataString = JSON.stringify(data);
            if (data) {
                if ('codigo' in data) {
                    cadastrarAssociacao(dataString);
                }
            }
        }
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '519px');
    
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    gridOptions.api.addEventListener('paginationChanged', function(event) {
        $('#loadingMessage').show();

        let paginaAtual = Number(event.api.paginationGetCurrentPage());
        let tamanhoPagina = Number(event.api.paginationGetPageSize());

        const filteredData = [];
        event.api.forEachNode((n) => {
            filteredData.push(n.data);
        });

        const startIndex = paginaAtual * tamanhoPagina;
        const endIndex = startIndex + tamanhoPagina;
        const pageData = filteredData.slice(startIndex, endIndex);
        
        var dados = [];
        pageData.forEach((data) => {
            if (data) {
                dados.push(data)
            }
        })
        $("#loadingMessage").hide();
    });
    
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions)
}

var agGridByUser;
function atualizarAgGridByUser(dados) {
    stopAgGRID();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Usuário',
                field: 'usuario',
                width: 100,
                suppressSizeToFit: true
            },            
            {
                headerName: 'Cód. Aprovador',
                field: 'codigo',
                chartDataType: 'series',
                width:150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Nome',
                field: 'nome',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Limite',
                field: 'limite',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Limite Máximo',
                field: 'limiteMaximo',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Login',
                field: 'login',
                width: 200,
                suppressSizeToFit: true
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            filter: true,
            resizable: true,
        },
        localeText: AG_GRID_LOCALE_PT_BR,
        domLayout: 'normal', // Change from autoHeight to normal
        pagination: true,
        paginationPageSize: 10
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '519px'); // Ensure the container has the fixed height
    
    agGridByUser = new agGrid.Grid(gridDiv, gridOptions);

    gridOptions.api.setRowData(dados);

    preencherExportacoes(gridOptions);
    resetPesquisarButton();
    enableButtons();
}

function buscarListaAutorizadores() {
    $("#autorizadorBusca").prop('disabled', true).append('<option value="" disabled>Carregando Autorizadores...</option>');
    let route = Router + "/buscarAutorizadores";
    $.ajax({
      cache: false,
      url: route,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
           $("#autorizadorBusca").empty().append('<option value="" disabled>Selecionar Autorizador</option>');
           response.resultado.forEach(function (dados) {
             $("#autorizadorBusca").prop('disabled', false).append('<option value="' + dados.usuario + '">' + dados.nome + '</option>');
           });
          $("#autorizadorBusca").select2({
            placeholder: "Selecionar Autorizador",
            allowClear: true
          }).val('').trigger('change');
        } else {
        showAlert("error", "Erro ao listar Autorizadores.")
        }
      },
      error: function () {
        showAlert("error", "Erro ao listar Autorizadores.")
    }
    });
  }  



function buscarUsuario() {
    function formatUsuario(usuario) {
        if (usuario.loading) {
            return usuario.text;
        }
        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__title'></div>" +
            "</div>"
        );
        $container.find(".select2-result-repository__title").text(usuario.text);
        return $container;
    }

    function formatUsuarioSelection(usuario) {
        return usuario.text || usuario.id;
    }

    $("#idUsuarioShow").select2({
        placeholder: "Digite o nome do usuario",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        minimumInputLength: 4,
        ajax: {
            cache: false,
            url: Router + "/buscarUsuario",
            type: "POST",
            delay: 1000,
            data: function(params) {
                return {
                    usuario: params.term
                };
            },
            dataType: "json",
            processResults: function(data) {
                if (data.resultado && data.resultado.status === 200) {
                    return {
                        results: data.resultado.resultado.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nome
                            };
                        })
                    };
                } else {
                    return {
                        results: [{
                            id: '',
                            text: 'Dados não encontrados'
                        }]
                    };
                }
            }
        },
        templateResult: formatUsuario,
        templateSelection: formatUsuarioSelection,
        language: {
            searching: function() {
                return "Buscando...";
            },
            noResults: function() {
                return "Dados não encontrados";
            },
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $("#idUsuarioShow").on('select2:open', function() {
        $(".select2-search__field").on('input', function() {
            var query = $(this).val();
            if (query.length >= 4) {
                $("#idUsuarioShow").select2("open");
            }
        });
    });
}

function cadastrarAssociacao(data){
    data = JSON.parse(data);

    $('#usuarioAprovador').val(data.usuario)
    $('#codAprovador').val(data.codigo)
    $('#nomeAprovador').val(data.nome)
    $('#limite').val(data.limite)
    $('#limiteMaximo').val(data.limiteMaximo)
    $('#loginAprovador').val(data.login)

    $('#idAprovador').append(`<option value="${data.id}" selected>${data.nome}</option>`) // option

    $('#addAssociacao').modal('show');
}

async function enviarAssociação(aprovador, usuario){
  ShowLoadingSalvarBtn();
  let codAprovador = await buscarCodigoAprovadorLogado(usuario);
  if (codAprovador != null) {
    showAlert(
      "warning",
      "Já existe um aprovador associado ao usuário selecionado."
    );
    enableButtons();
    resetSalvarBtn();
    return;
  }

  Swal.fire({
    title: "Atenção!",
    text: "Deseja realmente prosseguir com a associação ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#007BFF",
    confirmButtonText: "Continuar",
    footer: "Após a confirmação, essa ação não poderá ser desfeita.",
  }).then((result) => {
    if (result.isConfirmed) {
      ShowLoadingSalvarBtn();
      disabledButtons();
      let route = Router + "/associarAprovador";
      $.ajax({
        url: route,
        type: "POST",
        data: {
          idAprovador: aprovador,
          idUsuario: usuario,
        },
        dataType: "json",
        success: function (response) {
          if (response["status"] == 200) {
            showAlert("success", response["resultado"]["mensagem"]);
            $("#idAprovador").val('').trigger('change');
            $('#idUsuarioShow').val('').trigger('change');
          } else if (response["status"] == 400) {
            showAlert("error", response["resultado"]["mensagem"]);
          } else if (response["status"] == 404) {
            showAlert("error", response["resultado"]["mensagem"]);
          } else {
            showAlert(
              "error",
              "Erro ao realizar a associação, contate o suporte técnico."
            );
          }
          resetSalvarBtn();
          enableButtons();
          $('#addAssociacao').modal('hide');
        },
        error: function (error) {
          resetSalvarBtn();
          showAlert("error", "Erro na solicitação ao servidor.");
          enableButtons();
        },
      });
    } else {
        $("#idAprovador").val('').trigger('change');
        $('#idUsuarioShow').val('').trigger('change');
      enableButtons();
      resetSalvarBtn();
    }
  });
}

function exportarArquivo(tipo, gridOptions, menu = 'AutorizadoresCadastrados', titulo) {
    let colunas = [];
    if (menu === 'AutorizadoresCadastrados') {
        colunas = ['usuario', 'nome', 'limite', 'limiteMaximo', 'login'];
    }

    switch (tipo) {
        case 'csv':
            fileName = menu + '.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: colunas
            });
            break;
        case 'excel':
            fileName = menu + '.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: colunas
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                titulo
            );
            pdfMake.createPdf(definicoesDocumento).download(menu + '.pdf');
            break;

    }
}

function printParams(pageSize) {
    return {
        PDF_HEADER_COLOR: "#ffffff",
        PDF_INNER_BORDER_COLOR: "#dde2eb",
        PDF_OUTER_BORDER_COLOR: "#babfc7",
        PDF_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
        PDF_HEADER_LOGO: 'omnilink',
        PDF_ODD_BKG_COLOR: "#fff",
        PDF_EVEN_BKG_COLOR: "#F3F3F3",
        PDF_PAGE_ORITENTATION: "landscape",
        PDF_WITH_FOOTER_PAGE_COUNT: true,
        PDF_HEADER_HEIGHT: 25,
        PDF_ROW_HEIGHT: 25,
        PDF_WITH_CELL_FORMATTING: true,
        PDF_WITH_COLUMNS_AS_LINKS: false,
        PDF_SELECTED_ROWS_ONLY: false,
        PDF_PAGE_SIZE: pageSize,
    }
}

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

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
        div.classList.add('opcao_exportacao');
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
            exportarArquivo(opcao, gridOptions, 'RelatorioAutorizadores', 'Relatório de Autorizadores Cadastrados');
        });

        formularioExportacoes.appendChild(div);
    });
}

$(window).scroll(function() {
    $('.select2-container--open').each(function () {
        var $select2 = $(this).prev('select');
        $select2.select2('close');
    });
});

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
    for (var i=0; i <= dropdownItems.length;i++) {
        alturaDrop += dropdownItems.height();
    }
 
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)){
            dropdown.css('top', `-${alturaDrop - 60}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(alturaDrop - 50) - (diferenca + diferenca * 0.5) }px`);
        }
    } 
    
    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });
}


//Visibilidade

function ShowLoadingSalvarBtn() {
    $('#btnSalvarAssociacao').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetSalvarBtn() {
    $('#btnSalvarAssociacao').html('Salvar').attr('disabled', false);
}

function showLoadingPesquisarButton() {
    $('#filtrar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#filtrar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function disabledButtons(){
    $(".btn").attr('disabled', true)
}
function enableButtons(){
    $(".btn").attr('disabled', false)
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

//Utilitarios

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
}

function buscarCodigoAprovadorLogado(idUser) {
    let route = Router + "/buscarCodAprovador";
    return $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: { login: idUser }
    })
    .then(response => {
        if (response && response.resultado && response.resultado.length > 0) {
            return response.resultado[0].codigoAprovador;
        } else {
            return null;
        }
    })
    .catch(error => {
        showAlert("error", "Erro na solicitação ao servidor. Contate o suporte técnico.");
        return null;
    });
}