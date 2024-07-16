<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 0;">
    <div class="col-md-3" style="padding-left: 0;">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="filtrar-atributos-provi">Providência:</label>
                        <input type="text" name="filtrar-atributos-provi" class="form-control" placeholder="Filtrar Providência" id="filtrar-atributos-provi" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltroOcorrencias" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo" style="padding: 0;">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card" style="margin-left: 5px;">Providências: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">

                    <button class="btn btn-primary" id="btnAddProvidenciaCliente" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Nova Providência</button>

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
                <select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
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
                    <div id="tableProvidencia" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalProvidencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="formProvidencia" id="formProvidencia">
                <div class="modal-header">
                    <h3 style="margin-top: 10px" id="titleModalProvi">Visualizar Providência <span id="tz_name"></span></h3>
                    <button style="margin-top: -37px" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="padding: 0 !important">
                    <div class="pos-venda-container">
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Pergunta e Resposta</div>
                            <div class="pos-venda-info-group">
                                <div class="pos-venda-info"><strong>Pergunta:</strong> <span id="pergunta"></span></div>
                                <div class="pos-venda-info"><strong>Resposta:</strong> <span id="resposta"></span></div>
                            </div>
                        </div>
                        <div id="infoPessoasProvidencia"></div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;">
                    <button type="button" class="btn btn-secondary" id="fecharModalSalvarProvidencia" data-dismiss="modal">Fechar</button>
                    <button type="submit" style="margin-left: auto;" class="btn btn-success" id="btnSalvarProvidencia" disabled>Salvar</button>
                </div>
            </form>
        </div>
    </div>
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
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">

<script>
    //var RouterOCR = '';
    // var Router = '<?= site_url('Empresas/ContatosCorporativos') ?>';
    var BaseURL = '<?= base_url('') ?>';

    $(document).ready(async function() {

        var result = [];

        let edicaoModal = 'nao';
        let id_usuario = 0;

        cliente_selecionado_atual.clienteAuxiliarModel.idCrm = '6bc95e40-41b4-de11-9386-00237de5099c' ///lembrar de apagar

        let usuario_logado = '<?= site_url("PaineisOmnilink") ?>'

        async function buscarDadosAgGrid() {
            $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
            await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_get_providencias/${cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm}`,
                type: 'POST',
                data: {
                    draw: "1",
                    length: 100,
                    start: 0,
                    'search[value]': $('#filtrar-atributos-provi').val()
                },
                success: function(response) {
                    result = JSON.parse(response);
                    if (result.status == 200) {
                        updateData(result.providencias)
                    } else {
                        updateData([])
                    }
                },
                error: function(xhr, status, error) {
                    showAlert("error", "Erro ao receber dados, contate o suporte técnico!");
                }
            });
        }

        const gridOptions = {
            columnDefs: [{
                    headerName: "Nome",
                    field: "tz_name",
                    width: 290,
                    valueGetter: function(params) {
                        return params.data.tz_name !== null ? params.data.tz_name : "";
                    }
                },
                {
                    headerName: "Data de Criação",
                    field: "createdon",
                    width: 290,
                    valueGetter: function(params) {
                        return params.data.createdon !== null ? params.data.createdon : "";
                    }
                },
                {
                    headerName: "Status",
                    field: "statecode",
                    width: 2000,
                    valueGetter: function(params) {
                        return params.data.statecode !== null ? params.data.statecode : "";
                    }
                },
                {
                    headerName: 'Ações',
                    width: 80,
                    pinned: 'right',
                    cellClass: "actions-button-cell",
                    suppressMenu: true,
                    sortable: false,
                    filter: false,
                    resizable: false,
                    cellRenderer: function(options) {
                        let data = options.data;

                        let tableId = "tableProvidencia";
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
                            ajusteAltura = 530;
                        } else if (i > 2) {
                            ajusteAltura = 40;
                        } else {
                            ajusteAltura = 0;
                        }
                        return `
                            <div class="dropdown dropdown-table" data-tableId=${tableId}>
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}%);" aria-labelledby="${buttonId}">
                                   
                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-id=${data.tz_providenciasid} class="modalVerProvidencia_acoes" title="Visualizar"> Visualizar</a>
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-id=${data.tz_providenciasid} class="modalRemoverProvidencia_acoes" title="Remover"> Remover</a>
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-id=${data.tz_providenciasid} class="modalEditarProvidencia_acoes" title="Editar"> Editar</a>
                                    </div>

                                </div>
                            </div>`;
                    },
                },
            ],
            rowData: [],
            getRowId: params => params.data.tz_providenciasid,
            pagination: true,
            defaultColDef: {
                resizable: true,
            },
            sideBar: {
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
                }, ],
                defaultToolPanel: false,
            },
            paginationPageSize: $("#select-quantidade-por-contatos-corporativos").val(),
            localeText: AG_GRID_LOCALE_PT_BR,
        };
        var gridDiv = document.querySelector("#tableProvidencia");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        preencherExportacoes(gridOptions, "RelatorioDeProvidencias", ['tz_name', 'createdon']);

        function updateData(newData = []) {
            gridOptions.api.setRowData(newData);
        }

        await buscarDadosAgGrid();

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
        });

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
            gridOptions.api.refreshCells({
                force: true
            });
        });

        $(document).on('click', '.btn-expandir', async function(e) {
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
                $('.tab-acoes-pesquisa').hide()
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
                $('.tab-acoes-pesquisa').show()

            }
        });

        $("#BtnLimparFiltroOcorrencias").on("click", async function() {
            if ($('#filtrar-atributos-provi').val().trim().length > 0) {
                $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando');
                $('#BtnPesquisar').prop('disabled', true);
                $('#BtnLimparFiltroOcorrencias').prop('disabled', true);
                $("#filtrar-atributos-provi").val("");
                updateData()
                await buscarDadosAgGrid()
                $('#BtnPesquisar').html('<i class="fa fa-search"></i> Buscar');
                $('#BtnPesquisar').prop('disabled', false);
                $('#BtnLimparFiltroOcorrencias').prop('disabled', false);
            }
        });


        $('#BtnPesquisar').click(async function(e) {
            e.preventDefault()
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando');
            $(this).prop('disabled', true);
            $('#BtnLimparFiltroOcorrencias').prop('disabled', true);
            updateData()
            await buscarDadosAgGrid()
            $(this).html('<i class="fa fa-search"></i> Buscar');
            $(this).prop('disabled', false);
            $('#BtnLimparFiltroOcorrencias').prop('disabled', false);

            gridOptions.api.refreshCells({
                force: true
            })
        })

        async function salvar_auditoria(url_api, clause, valores_antigos, valores_novos) {
            if (valores_antigos && typeof(valores_antigos) === 'object') {
                valoresAuditoriaAntigo = [];
                Object.keys(valores_antigos).forEach(i => {
                    valoresAuditoriaAntigo.push(i)
                });
            } else {
                valoresAuditoriaAntigo = valores_antigos;
            }
            return new Promise((resolve, reject) => {
                const cpf_cpnj_cliente = (cliente_selecionado_atual.cnpj ? cliente_selecionado_atual.cnpj : cliente_selecionado_atual.cpf).replace(/[^0-9]/g, '');
                $.ajax({
                    url: URL_PAINEL_OMNILINK + '/ajax_salvar_auditoria',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        url_api,
                        clause,
                        valoresAuditoriaAntigo,
                        valores_novos,
                        cpf_cpnj_cliente
                    },
                    success: function(callback) {
                        if (callback.status) {
                            resolve(callback);
                        } else {
                            showAlert("error", 'Erro ao salvar auditoria! Não foi possível completar a operação.');
                            reject(callback);
                        }
                    },
                    error: function(error) {
                        showAlert("error", 'Erro ao salvar auditoria! Não foi possível completar a operação.');
                        reject(error);
                    }

                });
            });
        }


        // modal de providencias

        $(document).on('click', '.modalVerProvidencia_acoes', async function() {
            montar_inputs_providencias();
            $('#titleModalProvi').text('Visualizar Providência')
            mudarPropriedadeDisabledFormProvidencia(true);
            let idProvidencia = $(this).attr('data-id');

            ShowLoadingScreen()

            await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_buscar_providencia/${idProvidencia}`,
                type: 'GET',
                success: function(callback) {
                    callback = JSON.parse(callback);
                    if (callback.status) {
                        let providencia = callback.providencia;
                        atualizar_form_providencia(providencia);
                        $('#modalProvidencia').modal('show');
                    }
                },
                error: function(error) {
                    showAlert("error", 'Erro ao buscar Providência!');
                }
            });
            HideLoadingScreen()
        })


        function montar_inputs_providencias() {

            const pessoas = [
                'Primeira',
                'Segunda',
                'Terceira',
                'Quarta',
                'Quinta',
                'Sexta',
                'Sétima',
                'Oitava',
            ];

            let html = `
    <input type="hidden" id="providencia_tz_providenciasid" name="tz_providenciasid">
    <div class="row">
        <div class="col-md-6 form-group">
            <label class="control-label" for="perguntaProvidencia">Pergunta</label>
            <input class="form-control" type="text" id="providencia_tz_pergunta" name="tz_pergunta"disabled required>
        </div>
        <div class="col-md-6">
            <label class="control-label" for="respostaProvidencia">Resposta</label>
            <input class="form-control" type="text" id="providencia_tz_resposta" name="tz_resposta" disabled required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h5><b>Pessoas Autorizadas</b></h5>
        </div>
    </div>
`;
            for (let i = 0; i < pessoas.length; i++) {
                let pessoa = pessoas[i];
                let numPessoa = i + 1;
                html += `
        <div class="row">
            <div class="col-md-12">
                <h5>${pessoa} Pessoa</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label ${i == 0 ? 'class="control-label"' : ''} for="">Pessoa</label>
                <input class="form-control" type="text" id="providencia_tz_pessoa_${numPessoa}" name="tz_pessoa_${numPessoa}" disabled ${i == 0 ? 'required' : ''}>
            </div>
            <div class="col-md-2 form-group">
                <label ${i == 0 ? 'class="control-label"' : ''} for="">DDD</label>
                <input class="form-control ddd_providencia" type="text" id="providencia_tz_ddd1_pessoa${numPessoa}" name="tz_ddd1_pessoa${numPessoa}" disabled ${i == 0 ? 'required' : ''}>
            </div>
            <div class="col-md-4 form-group">
                <label ${i == 0 ? 'class="control-label"' : ''} for="">Telefone</label>
                <input class="form-control telefone_providencia" type="text" id="providencia_tz_telefone1_pessoa${numPessoa}" name="tz_telefone1_pessoa${numPessoa}" disabled ${i == 0 ? 'required' : ''}>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-2 form-group">
                <label for="">DDD</label>
                <input class="form-control ddd_providencia" type="text" id="providencia_tz_ddd2_pessoa${numPessoa}" name="tz_ddd2_pessoa${numPessoa}" disabled>
            </div>
            <div class="col-md-4 form-group">
                <label for="">Telefone</label>
                <input class="form-control telefone_providencia" type="text" id="providencia_tz_telefone2_pessoa${numPessoa}" name="tz_telefone2_pessoa${numPessoa}" disabled>
            </div>
        </div>
    `;

            }

            $("#infoPessoasProvidencia").html(html);
            $(".ddd_providencia").mask('00');

            $(".telefone_providencia").on('keydown keypress input blur', function() {

                if ($(this).val().length < 9) {
                    $(this).mask('0000-00009');
                } else {
                    $(this).mask('00000-0000');
                }
            });

        }

        function atualizar_form_providencia(providencia) {
            if (providencia && typeof(providencia) === 'object') {
                Object.keys(providencia).forEach(i => {
                    if (i == 'tz_name') $(`#providencia_${i}`).html(' - ' + providencia[i]);
                    if (!i.includes(".")) $(`#providencia_${i}`).val(providencia[`${i}`]);
                });
            }
        }

        function removeRowFromGrid(rowId) {
            const rowNode = gridOptions.api.getRowNode(rowId);
            gridOptions.api.applyTransaction({
                remove: [rowNode.data]
            });
        }

        $(document).on('click', '.modalRemoverProvidencia_acoes', async function() {
            let idProvidencia = $(this).data('id');
            Swal.fire({
                title: "Atenção!",
                text: "Você tem certeza que deseja escluir esta provicência?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007BFF",
                cancelButtonColor: "#d33",
                confirmButtonText: "Continuar",
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ShowLoadingScreen()
                    const url = `${URL_PAINEL_OMNILINK}/ajax_remover_providencia/${idProvidencia}`;
                    salvar_auditoria(url, 'delete', null, {
                            idProvidenciaRemovida: idProvidencia
                        })
                        .then(async () => {
                            await $.ajax({
                                url: url,
                                type: 'DELETE',
                                success: function(callback) {
                                    callback = JSON.parse(callback);
                                    if (callback.status) {
                                        removeRowFromGrid(idProvidencia)
                                        showAlert("success", "Providência removida com sucesso!");
                                        HideLoadingScreen()
                                    } else {
                                        showAlert("error", 'Erro ao excluir Providência!');
                                        HideLoadingScreen()
                                    }
                                },
                                error: function(error) {
                                    showAlert("error", 'Erro ao excluir Providência!');
                                    HideLoadingScreen()
                                }
                            });
                        })
                        .catch(error => {
                            showAlert("error", 'Erro ao excluir Providência!');
                            HideLoadingScreen()
                        });
                }
            })

        })


        /// modal cadastrar providencia
        let isEdit = false;
        $(document).on('click', '#btnAddProvidenciaCliente', async function() {
            isEdit = false;
            montar_inputs_providencias();
            mudarPropriedadeDisabledFormProvidencia(false);
            $('#titleModalProvi').text('Incluir Providência')
            $('#modalProvidencia').modal('show');
        })

        function mudarPropriedadeDisabledFormProvidencia(isDisabled) {
            $("#formProvidencia").find("input").attr('disabled', isDisabled);
            $("#btnSalvarProvidencia").attr('disabled', isDisabled);
            if (!isDisabled) {
                $('#btnSalvarProvidencia').show();
                $('#fecharModalSalvarProvidencia').css('margin-left', '0');
            } else {
                $('#btnSalvarProvidencia').hide();
                $('#fecharModalSalvarProvidencia').css('margin-left', 'auto');
            }
        }


        $("#formProvidencia").submit(async function(event) {
            event.preventDefault();
            let dadosForm = $(this).serializeArray();
            let data = {};
            dadosForm.forEach(element => {
                data[element.name] = element.value;
            });

            ShowLoadingScreen()
            if (isEdit) {
                salvar_auditoria(`${URL_PAINEL_OMNILINK}/ajax_editar_providencia`, 'update', dadosFormAntigo, data)
                    .then(async () => {
                        await editarProvidencia(data);
                        HideLoadingScreen()
                        $("#modalProvidencia").modal('hide');
                        buscarDadosAgGrid()
                    })
                    .catch(error => {
                        HideLoadingScreen()
                    });

            } else {
                salvar_auditoria(`${URL_PAINEL_OMNILINK}/ajax_cadastrar_providencia`, 'insert', null, data)
                    .then(async () => {
                        await cadastrarProvidencia(data);
                        HideLoadingScreen()
                        $("#modalProvidencia").modal('hide');
                        buscarDadosAgGrid()
                    })
                    .catch(error => {
                        HideLoadingScreen()
                    });
            }

        });


        function cadastrarProvidencia(data) {
            if (data) {
                data.clientEntity = 'accounts';
                data.idCliente = cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm;
                $.ajax({
                    url: `${URL_PAINEL_OMNILINK}/ajax_cadastrar_providencia`,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(callback) {
                        if (callback.status) {
                            if (callback.status) {
                                showAlert("success", 'Providência cadastrada com sucesso!');
                            } else {
                                showAlert("error", 'Erro ao cadastrar providência!');
                            }
                        }
                    },
                    error: function(error) {
                        showAlert("error", 'Erro ao cadastrar a providência!');
                    }
                });
            } else {
                showAlert("error", "Não foi possível cadastrar a providência!");
            }
        }



        // modal editar providencia 
        let dadosFormAntigo = null;
        let idProvidenciaEdicao = 0;

        $(document).on('click', '.modalEditarProvidencia_acoes', async function() {
            isEdit = true;
            montar_inputs_providencias();
            $('#titleModalProvi').text('Editar Providência')
            mudarPropriedadeDisabledFormProvidencia(false);
            idProvidenciaEdicao = $(this).attr('data-id');

            ShowLoadingScreen()

            await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_buscar_providencia/${idProvidenciaEdicao}`,
                type: 'GET',
                success: function(callback) {
                    callback = JSON.parse(callback);
                    if (callback.status) {
                        let providencia = callback.providencia;
                        atualizar_form_providencia(providencia);
                        $('#modalProvidencia').modal('show');
                        dadosFormAntigo = $(this).serializeArray();
                    }
                },
                error: function(error) {
                    showAlert("error", 'Erro ao buscar Providência!');
                }
            });
            HideLoadingScreen()
        })



        async function editarProvidencia(data) {
            data.tz_providenciasid = idProvidenciaEdicao
            $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_editar_providencia`,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(callback) {
                    if (callback.status) {
                        showAlert("success", 'Providência atualizada com sucesso!');
                    } else {
                        showAlert("error", "Erro ao editar Providência!");
                    }
                },
                error: function(error) {
                    showAlert("error", "Erro ao editar Providência!");
                }
            });
        }


    })

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }
</script>

<style>
    .modal-header .close {
        margin-top: -27px;
    }
</style>