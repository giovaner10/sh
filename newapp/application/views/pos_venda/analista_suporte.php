<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente('Analistas de Suporte', site_url('Homes'), "Pós-vendas", 'Analistas de Suporte');
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">

        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a style="background: #1C69AD !important; color: #fff !important; font-family: 'Mont SemiBold';" class='menu-interno-link <?= $menu_omnicom == 'CadastroDeClientes' ? 'selected' : '' ?>' id="menu-cadastro-clientes">Analistas de Suporte</a>
                </li>
            </ul>
        </div>


        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="filtrar-atributos">Nome:</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="Nome do cliente" id="filtrar-atributos" />
                    </div>


                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card">Analistas de Suporte: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="BtnAdicionarCliente" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>

                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-analistas" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value=10 selected>10</option>
                    <option value=25>25</option>
                    <option value=50>50</option>
                    <option value=100>100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageCadastroClientes" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageTecnologias" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>

                <div class="wrapperContatos">
                    <div id="tableContatos" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addClientes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" id='formAddClientes' data-edicao="nao" data-edicao-id-usuario="0">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="status" id="status">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleTecnologias">Cadastrar Analista de Suporte</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="idCrm">CRM: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="idCrm" id="idCrm" placeholder="Digite o CRM" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="nomeUsuario">Nome de Usuário: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="nomeUsuario" id="nomeUsuario" placeholder="Digite o nome de usuário" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="usuarioSistema">Usuário do Sistema:</label>
                                <input class="form-control" type="text" name="usuarioSistema" id="usuarioSistema" placeholder="Digite o usuário do sistema" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="nomeCompleto">Nome Completo: <span class="text-danger">*</label>
                                <input class="form-control" type="text" name="nomeCompleto" id="nomeCompleto" placeholder="Digite o nome completo" required minlength="3" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="apelido">Apelido:</label>
                                <input class="form-control" type="text" name="apelido" id="apelido" placeholder="Digite o apelido" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="titulo">Título:</label>
                                <input class="form-control" type="text" name="titulo" id="titulo" placeholder="Digite o título" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="emailPrimario">Email Primário: <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" name="emailPrimario" id="emailPrimario" placeholder="Digite o email primário" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="email2">Email Secundário:</label>
                                <input class="form-control" type="email" name="email2" id="email2" placeholder="Digite o email secundário" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="emailAlertaMovel">Email de Alerta Móvel:</label>
                                <input class="form-control" type="email" name="emailAlertaMovel" id="emailAlertaMovel" placeholder="Digite o email de alerta móvel" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="emailYammer">Email Yammer:</label>
                                <input class="form-control" type="email" name="emailYammer" id="emailYammer" placeholder="Digite o email Yammer" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="telefoneCelular">Telefone Celular: <span class="text-danger">*</span></label>
                                <input class="form-control" type="tel" name="telefoneCelular" id="telefoneCelular" placeholder="Digite o telefone celular" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="telefonePrincipal">Telefone Principal: <span class="text-danger">*</span></label>
                                <input class="form-control" type="tel" name="telefonePrincipal" id="telefonePrincipal" placeholder="Digite o telefone principal" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="statusEmailPrincipal">Status do Email Principal: <span class="text-danger">*</span></label>
                                <select class="form-control" name="statusEmailPrincipal" id="statusEmailPrincipal" required>
                                    <option value="0">Aprovação Pendente</option>
                                    <option value="1" selected>Aprovado</option>
                                    <option value="2">Rejeitado</option>
                                    <option value="3">Vazio</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="statusConvite">Status do Convite: <span class="text-danger">*</span></label>
                                <select class="form-control" name="statusConvite" id="statusConvite" required>
                                    <option value="0" selected>Convidado</option>
                                    <option value="1">Convite Aceito</option>
                                    <option value="2">Convite não Enviado</option>
                                    <option value="3">Convite Quase Vencido</option>
                                    <option value="4">Convite Recusado</option>
                                    <option value="5">Convite Revogado</option>
                                    <option value="6">Convite Vencido</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="unidadeNegocios">Unidade de Negócios: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="unidadeNegocios" id="unidadeNegocios" placeholder="Digite a unidade de negócios" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="cargo">Cargo: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="cargo" id="cargo" placeholder="Digite o cargo" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="departamento">Departamento: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="departamento" id="departamento" placeholder="Digite o departamento" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="aprovadorDescontoReembolso">Aprovador de Desconto/Reembolso: <span class="text-danger">*</span></label>
                                <select class="form-control" name="aprovadorDescontoReembolso" id="aprovadorDescontoReembolso" required>
                                    <option value="true">Sim</option>
                                    <option value="false">Não</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="caixaCorreio">Caixa de Correio:</label>
                                <input class="form-control" type="email" name="caixaCorreio" id="caixaCorreio" placeholder="Digite o caixa de correio" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="endereco">Endereço: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="endereco" id="endereco" placeholder="Digite o endereço" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="estado">Estado: <span class="text-danger">*</span></label>
                                <select class="form-control" name="estado" id="estado" required>
                                    <option value="" selected disabled>Selecione o estado</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="cidade">Cidade: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="cidade" id="cidade" placeholder="Digite a cidade" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="taxaCambio">Taxa de Câmbio:</label>
                                <input class="form-control" type="number" step="0.01" name="taxaCambio" id="taxaCambio" placeholder="Digite a taxa de câmbio" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="vendedor">Vendedor:</label>
                                <input class="form-control" type="text" name="vendedor" id="vendedor" placeholder="Digite o vendedor" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="gerente">Gerente:</label>
                                <input class="form-control" type="text" name="gerente" id="gerente" placeholder="Digite o gerente" />
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarCliente'>Salvar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div id="loading">
    <div class="loader"></div>
</div>
<style>
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }


    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
</style>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/EzMock', 'app.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/posvendas', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">

<script>
    //var RouterOCR = '';
    var Router = '<?= site_url('Empresas/ContatosCorporativos') ?>';
    var BaseURL = '<?= base_url('') ?>';


    $(document).ready(async function() {

        var result = [];

        let edicaoModal = 'nao';
        let id_usuario = 0;

        function ShowLoadingScreen() {
            $('#loading').show()
        }

        function HideLoadingScreen() {
            $('#loading').hide()
        }


        function getServerSideDados() {
            return {
                getRows: (params) => {
                    $.ajax({
                        cache: false,
                        url: '<?php echo base_url("PosVenda/Gestao/listar_analistas_suporte"); ?>',
                        type: "POST",
                        data: {
                            startRow: params.request.startRow,
                            endRow: params.request.endRow,
                            nome: $("#filtrar-atributos").val() || null,
                        },
                        dataType: "json",
                        async: true,
                        beforeSend: disableSearchButtons,
                        success: (data) => handleSuccess(params, data),
                        error: (error) => handleError(params, error),
                        complete: enableSearchButtons
                    });
                },
            };
        }

        function disableSearchButtons() {
            $('#BtnPesquisar').prop('disabled', true);
            $('#BtnLimparFiltro').prop('disabled', true);
        }

        function enableSearchButtons() {
            $('#BtnPesquisar').prop('disabled', false);
            $('#BtnLimparFiltro').prop('disabled', false);
        }

        function handleSuccess(params, data) {
            if (data && data.success) {
                params.success({
                    rowData: data.rows,
                    rowCount: data.lastRow,
                });
            } else {
                showAlert('error', data.message || "Erro na solicitação ao servidor");
                showNoRowsOverlay(params);
            }
        }

        function handleError(params, error) {
            console.error("Erro na solicitação ao servidor:", error);
            showAlert('error', "Erro na solicitação ao servidor");
            showNoRowsOverlay(params);
        }

        function showNoRowsOverlay(params) {
            AgGrid.gridOptions.api.showNoRowsOverlay();
            params.failCallback();
            params.success({
                rowData: [],
                rowCount: 0,
            });
        }

        // Função para fechar dropdown
        function fecharDrop() {
            $('#opcoes_exportacao').hide();
        }

        // Função para atualizar dados
        function updateData(newData = []) {
            gridOptions.api.setServerSideDatasource(newData);
        }

        // Função para renderizar AG Grid
        async function renderAgGrid() {
            gridOptions = {
                columnDefs: getColumnDefs(),
                rowData: [],
                getRowId: params => params.data.id,
                pagination: true,
                defaultColDef: {
                    resizable: true,
                },
                sideBar: getSideBarConfig(),
                popupParent: document.body,
                paginationPageSize: parseInt($("#select-quantidade-por-analistas").val()),
                cacheBlockSize: 25,
                localeText: AG_GRID_LOCALE_PT_BR,
                domLayout: "normal",
                rowModelType: "serverSide",
                serverSideStoreType: "partial",
            };

            initializeAgGrid();

            const data = await getServerSideDados();
            updateData(data);
        }

        function getColumnDefs() {
            return [
                getColumnDef("ID", "id"),
                getColumnDef("Nome Completo", "nomeCompleto"),
                getColumnDef("ID CRM", "idCrm"),
                getColumnDef("Usuário do Sistema", "usuarioSistema"),
                getColumnDef("E-mail", "emailPrimario"),
                getColumnDef("Telefone Celular", "telefoneCelular"),
                getColumnDef("Estado", "estado"),
                getColumnDef("Gerente", "gerente"),
                getActionsColumnDef()
            ];
        }

        function getColumnDef(headerName, field) {
            return {
                headerName,
                field,
                valueGetter: params => params.data[field] !== null ? params.data[field] : ""
            };
        }

        function getActionsColumnDef() {
            return {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                suppressMenu: true,
                sortable: false,
                filter: false,
                resizable: false,
                cellRenderer: (options) => getActionsCellRenderer(options),
            };
        }

        function getActionsCellRenderer(options) {
            let data = options.data;

            let tableId = "tableContatos";
            let dropdownId = "dropdown-menu" + data.id;
            let buttonId = "dropdownMenuButton_" + data.id;
            let i = options.rowIndex;
            let ajusteAltura = 0;
            let paginaAtual = gridOptions.api.paginationGetCurrentPage();
            let qtd = $('#select-quantidade-por-contatos-corporativos').val()


            if (paginaAtual > 0) {
                i = i - (paginaAtual) * qtd
            }

            if (i > 9) {
                i = 9;
            }

            if (i > 4) {
                ajusteAltura = 185;
            } else {
                ajusteAltura = 0;
            }
            return `
        <div class="dropdown dropdown-table" data-tableId=${tableId}>
            <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
            </button>
            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}% - ${i}px);" aria-labelledby="${buttonId}">
               
                <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                <a class="deletar_id_usuario" data-id-user=${data.id} style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Inativar</a>
                </div>

                <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                <a class="editar_id_usuario" data-id-user=${data.id} style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                </div>

            </div>
        </div>`;
        }

        function getSideBarConfig() {
            return {
                toolPanels: [{
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
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
                }],
                defaultToolPanel: false,
            };
        }

        function initializeAgGrid() {
            const gridDiv = document.querySelector("#tableContatos");
            AgGrid = new agGrid.Grid(gridDiv, gridOptions);
            $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
            preencherExportacoes(gridOptions, "RelatorioAnalistaSuporte");

            setupPageSizeSelector();
            setupPaginationChangedEvent();
        }

        function setupPageSizeSelector() {
            $("#select-quantidade-por-analistas").off().change(function() {
                const selectedValue = $("#select-quantidade-por-analistas").val();
                gridOptions.api.paginationSetPageSize(Number(selectedValue));
            });
        }

        function setupPaginationChangedEvent() {
            gridOptions.api.addEventListener("paginationChanged", function(event) {
                $("#loadingMessage").show();

                const paginaAtual = Number(event.api.paginationGetCurrentPage());
                const tamanhoPagina = Number(event.api.paginationGetPageSize());

                const filteredData = [];
                event.api.forEachNode((node) => {
                    filteredData.push(node.data);
                });

                const startIndex = paginaAtual * tamanhoPagina;
                const endIndex = startIndex + tamanhoPagina;
                const pageData = filteredData.slice(startIndex, endIndex);

                const dados = pageData.filter(data => data !== null);
                $("#loadingMessage").hide();
            });
        }

        renderAgGrid();

        $(".btn-expandir").on("click", function(e) {
            e.preventDefault();
            menuAberto = !menuAberto;

            if (menuAberto) {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-show.svg`
                );
                $("#filtroBusca").hide();
                $(".menu-interno").hide();
                $("#conteudo").removeClass("col-md-9");
                $("#conteudo").addClass("col-md-12");
            } else {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-hide.svg`
                );
                $("#filtroBusca").show();
                $(".menu-interno").show();
                $("#conteudo").css("margin-left", "0px");
                $("#conteudo").removeClass("col-md-12");
                $("#conteudo").addClass("col-md-9");
            }
        });

        $("#BtnLimparFiltro").on("click", async function() {
            if ($('#filtrar-atributos').val().trim().length > 0) {
                $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando');
                $('#BtnPesquisar').prop('disabled', true);
                $('#BtnLimparFiltro').prop('disabled', true);
                $("#filtrar-atributos").val("");
                let data = await getServerSideDados();
                updateData(data);
                $('#BtnPesquisar').html('<i class="fa fa-search"></i> Buscar');
                $('#BtnPesquisar').prop('disabled', false);
                $('#BtnLimparFiltro').prop('disabled', false);
            }
        });


        $('#BtnPesquisar').click(async function(e) {
            e.preventDefault()
            if ($('#filtrar-atributos').val().trim().length > 0) {
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando');
                $(this).prop('disabled', true);
                $('#BtnLimparFiltro').prop('disabled', true);
                let data = await getServerSideDados();
                updateData(data);
                $(this).html('<i class="fa fa-search"></i> Buscar');
                $(this).prop('disabled', false);
                $('#BtnLimparFiltro').prop('disabled', false);
            }
        })

        $('#formAddClientes').on('submit', async function(event) {
            event.preventDefault();
            ShowLoadingScreen()

            var formData = $(this).serialize();

            await $.ajax({
                type: 'POST',
                url: '<?= site_url("PosVenda/Gestao/criar_analista_suporte") ?>' + '/?edicao=' + edicaoModal + '&idEdicao=' + id_usuario,
                data: formData,
                success: function(response) {
                    response = JSON.parse(response)
                    if (response.status == 200) {
                        formData = convertToJSONObject(formData)

                        let valorEdicao = edicaoModal == 'sim'

                        showAlert("success", "Analista de Suporte " + (valorEdicao ? 'editado' : 'cadastrado') + " com sucesso!");

                        var newClient = {
                            id: valorEdicao ? id_usuario : response.resultado.mensagem,
                            nomeCompleto: formData.nomeCompleto,
                            idCrm: formData.idCrm,
                            usuarioSistema: formData.usuarioSistema,
                            emailPrimario: formData.emailPrimario,
                            telefoneCelular: formData.telefoneCelular,
                            estado: formData.estado,
                            gerente: formData.gerente
                        };
                        let data = getServerSideDados();
                        updateData(data);
                        limparForm()
                        $('#addClientes').modal('hide');
                    } else {
                        if (response.resultado?.errors) {
                            response.resultado?.errors.forEach((item) => {
                                showAlert("warning", item);
                            });
                        } else {
                            showAlert("warning", response.resultado?.mensagem);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    showAlert("error", "Falha ao salvar Analista de Suporte.");
                }
            });
            HideLoadingScreen();
        });


        function convertToJSONObject(queryString) {
            const pairs = queryString.split('&');
            const result = {};

            pairs.forEach(pair => {
                const [key, value] = pair.split('=');
                result[decodeURIComponent(key)] = decodeURIComponent(value || '');
            });

            return result;
        }

        $('#nomeUsuario').on('input', function() {
            var nomeUsuario = $(this).val();
            var sanitizedNomeUsuario = nomeUsuario.replace(/\s/g, '');
            $(this).val(sanitizedNomeUsuario);
        })

        $(document).on('click', '.editar_id_usuario', async function() {
            limparForm()
            id_usuario = $(this).data('id-user');
            edicaoModal = 'sim'
            ShowLoadingScreen();
            $("#titleTecnologias").text('Editar Analista de Suporte')
            $('#formAddClientes').attr('data-edicao-id-usuario', id_usuario);


            await $.ajax({
                url: '<?php echo base_url("PosVenda/Gestao/buscar_analista_id"); ?>' + '/?id=' + id_usuario,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        if (response?.resultado.length > 0) {
                            var data = response?.resultado[0];

                            $.each(data, function(key, value) {
                                var campo = $('#' + key);
                                if (campo.length) {
                                    if (campo.is('select') && typeof value === 'boolean') {
                                        value = value.toString();
                                    }
                                    campo.val(value);
                                }
                            });
                        }
                        $('#addClientes').modal('show');

                    } else {
                        showAlert("error", response.mensagem);
                    }
                    HideLoadingScreen();
                },
                error: function(error) {
                    showAlert("error", "Erro na solicitação ao servidor");
                    HideLoadingScreen();
                }
            })
        })

        function limparForm() {
            $('#formAddClientes').find('input, select').val('');
        }

        $(document).on('click', '.deletar_id_usuario', async function() {
            let id_usuario = $(this).data('id-user');
            Swal.fire({
                title: "Atenção!",
                text: "Deseja realmente inativar o analista de suporte?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007BFF",
                cancelButtonColor: "#d33",
                confirmButtonText: "Continuar"
            }).then((result) => {
                if (result.isConfirmed) {
                    ShowLoadingScreen();
                    $.ajax({
                        url: '<?php echo base_url("PosVenda/Gestao/inativar_analistas_suporte"); ?>' + '/?id=' + id_usuario,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response['status'] == 200) {
                                let data = getServerSideDados();
                                updateData(data);
                                showAlert("success", "Status alterado com sucesso!")
                            } else if (response['status'] == 400) {
                                showAlert("error", response['resultado']['mensagem']);
                            } else {
                                showAlert("error", response['resultado']['mensagem']);
                            }

                            HideLoadingScreen();
                        },
                        error: function(error) {
                            showAlert("error", "Erro na solicitação ao servidor");
                            HideLoadingScreen();
                        }
                    })


                }
            });
        })

        $('#BtnAdicionarCliente').on('click', function() {
            limparForm()
            edicaoModal = 'nao'
            id_usuario = 0
            $("#titleTecnologias").text('Cadastrar Analista de Suporte')
            $('#formAddClientes').attr('data-edicao-id-usuario', 0);
            $('#addClientes').modal('show');
        })


    })

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }
</script>