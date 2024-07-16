<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('comandos_enviados') ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a>
        > <?= lang('cadastros') ?>
        > <?= lang('veiculos') ?>
        > <?= lang('comandos_enviados') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div id="myModalResposta" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Resposta do Comando</h3>
            </div>
            <div class="modal-body">
                <div class="row align-items-center">
                    <div id="valor-resposta" class="col-md-12"></div>
                </div>
            </div>
            <div class="modal-footer" id="valor-copiar"></div>
        </div>
    </div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card" style="padding: 20px 30px;">
            <h4 style="margin: 0px !important;">Envio de Comando: </h4>
            <div class="col-md-6 form-group">
                <h5>Código</h5>
                <h6 id="code_inf">Carregando...</h6>
            </div>
            <div class="col-md-6 form-group">
                <h5>Usuário</h5>
                <h6 id="email_inf">Carregando...</h6>
            </div>
            <div class="col-md-6 form-group">
                <h5>Veículo</h5>
                <h6 id="veiculo_inf">Carregando...</h6>
            </div>
            <div class="col-md-6 form-group">
                <h5>Placa</h5>
                <h6 id="placa_inf">Carregando...</h6>
            </div>
            <div class="col-md-6 form-group">
                <h5>Serial</h5>
                <h6 id="serial_inf">Carregando...</h6>
            </div>
            <div class="col-md-6 form-group">
                <h5>Equipamento</h5>
                <h6 id="equipamento_inf">Carregando...</h6>
            </div>
            <form action="envio_comando" method="post" class="form-horizontal row" id="form-comando">
                <div style="display: flex; flex-direction: column; padding: 7px; height: 100%;">
                    <input type="text" hidden name='serial' id='serial_input' value=''>
                    <input type="text" hidden name='code' id='code_input' value=''>
                    <div class="input-container buscaProcessamento">
                        <select name="comando" id="comandos" class="form-control" style="width: 100%;">
                            <option value="">Carregando...</option>
                        </select>
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success btn-add-comando" data-form-id="form-comando" style='width:100%'
                            type="button">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i> Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-relatorio-instalacao"
            style='margin-bottom: 20px; position: relative; min-height: 240px; '>
            <h3 id="title_grid">Relatório de Comandos Enviados</h3>
            <div id="loading-button" class="loading-button" style="display: none; margin-top: 35px;">
                Aguarde...
            </div>
            <div id="container-table-comandos" style="margin: 0; display: flex; flex-direction:column;">
                <div id="registrosDiv">
                    <select id="select-quantidade-por-pagina" class="form-control"
                        style="width: 100px; float: left; margin-top: 10px; margin-bottom: 13px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 style="float: left; margin-top: 20px; margin-left: 10px;" class="label_input">Registros
                        por página</h6>
                </div>
                <div class="wrapperComandos">
                    <div id="myGrid" class="ag-theme-alpine" style="height: 529px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/comandosVeiculos', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'actionButton.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>

<script>
    var localeText = AG_GRID_LOCALE_PT_BR;
    var respostas = {};

    $(document).ready(function () {
        var serial = "<?= $serial ?>";
        var code = "<?= $code ?>";

        if (!serial || !code || serial === 'null' || code === 'null') {
            showAlert("error", "Não há dados de comandos para este veículo.");
            $("#loading-button").show();

            setTimeout(function () {
                window.location.href = "<?= site_url('cadastros/veiculos') ?>";
            }, 0);
            return;
        }

        function loadData(callback = () => null) {
            $.ajax({
                type: "GET",
                url: "<?php echo site_url('comandos/carregarDados') ?>/" + serial + "/" + code,
                dataType: "json",
                beforeSend: function () {
                    $("#loading-button").show();
                    $("#container-table-comandos").hide();

                },
                success: function (response) {
                    $("#loading-button").hide();
                    $("#container-table-comandos").show();

                    callback();
                    populateRespostas(response.comandos);
                    populateVehicleInfo(response.veiculos[0]);
                    populateComandosSelect(response.lista_comandos);
                    updateAgGridComandos(response.comandos);
                },
                error: function (err) {
                    showAlert("error", 'Ocorreu um erro ao carregar os dados');
                }
            });
        }

        function populateRespostas(dataArray) {
            dataArray.forEach(item => {
                respostas[item.id] = item.resposta;
            });
        }

        function populateVehicleInfo(vehicle) {
            $("#code_inf").text(vehicle.code || "Não informado");
            $("#email_inf").text(vehicle.email || "Não informado");
            $("#veiculo_inf").text(vehicle.veiculo || "Não informado");
            $("#placa_inf").text(vehicle.placa || "Não informado");
            $("#serial_inf").text(vehicle.serial || "Não informado");
            $("#equipamento_inf").text(vehicle.equipamento || "Não informado");

            $("#serial_input").val(vehicle.serial || "");
            $("#code_input").val(vehicle.code || "");
        }

        function populateComandosSelect(comandos) {
            var comandosSelect = $("#comandos");
            comandosSelect.empty();
            comandosSelect.append(new Option("Selecione um comando", "", true, true));
            comandos.forEach(function (cmd) {
                comandosSelect.append(new Option(cmd.nome, cmd.code));
            });
        }

        loadData();

        $(document).on('click', '.visualizarResposta', function () {
            var id = $(this).data('id');
            var resposta = respostas[id];
            $('#myModalResposta').modal('show');
            $('#valor-resposta').html('<span class="truncate-text" style="word-wrap: break-word; max-width: 200px;">' + resposta + '</span>');
            $('#valor-copiar').html(`<button type="submit" class="btn btn-primary visualizarResposta" id="btn-copiar" data-resposta-valor="${resposta}" data-toggle="tooltip" data-placement="top" title="Aperte para copiar">Copiar</button>`);
            $('#btn-copiar').tooltip();
        });

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $(document).on('click', '#btn-copiar', function (ev) {
            ev.preventDefault();
            const valorParaCopiar = $(this).data('resposta-valor');
            copiarParaAreaDeTransferencia(valorParaCopiar, $(this));
        });

        function copiarParaAreaDeTransferencia(valor, botao) {
            navigator.clipboard.writeText(valor)
                .then(function () {
                    mostrarMensagemTooltip(botao, 'Copiado!');
                    showAlert("success", "Comando copiado!");
                    $('#myModalResposta').modal('hide');
                })
                .catch(function (err) {
                    mostrarMensagemTooltip(botao, 'Erro ao copiar');
                });
        }

        function mostrarMensagemTooltip(botao, mensagem) {
            botao.attr('data-original-title', mensagem).tooltip('show');
            setTimeout(function () {
                botao.tooltip('hide').attr('data-original-title', 'Aperte para copiar');
            }, 1000);
        }

        $(document).on('click', '.btn-add-comando', function () {
            $(".btn-add-comando").blur();

            if ($("#comandos").val() == "" || $("#comandos").val() == null || !$("#comandos").val()) {
                showAlert("warning", "É necessário selecionar um comando!");
                return;
            }

            var formId = $(this).data('form-id');
            var form = document.getElementById(formId);
            var formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('comandos/envio_comando') ?>",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function () {
                    $("#loading-button").show();
                    $("#container-table-comandos").hide();
                },
                success: function (response) {
                    $("#loading-button").hide();
                    $("#container-table-comandos").show();

                    if (response) {
                        loadData(function () {
                            showAlert("success", response.message || 'Comando enviado com sucesso')
                        });
                        return;
                    }
                    showAlert("error", "Falha ao enviar comando");
                },
                error: function (err) {
                    $("#loading").hide();
                    showAlert("error", 'Ocorreu um erro ao enviar a solicitação');
                },
            });
        });

        function stopAgGridComandos() {
            var gridDiv = document.querySelector("#myGrid");
            if (gridDiv && gridDiv.__agGrid) {
                gridDiv.__agGrid.destroy();
            }

            var wrapper = document.querySelector(".wrapperComandos");
            if (wrapper) {
                wrapper.innerHTML = '<div id="myGrid" class="ag-theme-alpine" style="height: 529px; width: 100%;"></div>';
            }
        }

        function updateAgGridComandos(comandos) {
            stopAgGridComandos();

            var columnDefs = [{
                headerName: "Comando",
                field: "nome",
                tooltipField: "nome",
                minWidth: 210
            },
            {
                headerName: "Enviado",
                field: "datahora_criacao",
                minWidth: 160
            },
            {
                headerName: "Confirmado",
                field: "datahora_confirmacao",
                minWidth: 160
            },
            {
                headerName: "Tentativas",
                field: "tentativa",
                minWidth: 105
            },
            {
                headerName: "Enviado por",
                field: "nome_usuario",
                minWidth: 200
            },
            {
                headerName: "Status",
                field: "status",
                minWidth: 200,
                cellRenderer: function (params) {
                    var status = '';
                    var badgeClass = '';

                    switch (params.value) {
                        case 0:
                            status = 'Novo';
                            badgeClass = 'badge-novo-comando';
                            break;
                        case 1:
                            status = 'Em processamento';
                            badgeClass = 'badge-em-processamento';
                            break;
                        case 2:
                            status = 'Rejeitado';
                            badgeClass = 'badge-comando-rejeitado';
                            break;
                        case 3:
                            status = 'Múltiplos em Andamento';
                            badgeClass = 'badge-operacao-multipla';
                            break;
                        case 4:
                            status = 'Aceito';
                            badgeClass = 'badge-comando-aceito';
                            break;
                        default:
                            status = 'Aguardando';
                            badgeClass = 'badge-aguardando';
                    }

                    return `
                    <div class="status-container">
                        <span class="badge ${badgeClass}">${status}</span>
                    </div>
                `;
                }
            },
            {
                headerName: "Ações",
                pinned: "right",
                minWidth: 85,
                maxWidth: 85,
                cellClass: "actions-button-cell",
                cellRenderer: function (params) {
                    var firstRandom = Math.random() * 10;
                    var secRandom = Math.random() * 10;
                    var thirdRandom = Math.random() * 10;

                    var varAleatorioIdBotao =
                        (firstRandom * secRandom).toFixed(0) +
                        "-" +
                        (thirdRandom * firstRandom).toFixed(0) +
                        "-" +
                        (secRandom * thirdRandom).toFixed(0);

                    let data = params.data;
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;
                    let tableId = "myGrid";

                    let dropdownItems = "";

                    let ajusteAltura = 0;
                    let i = params.rowIndex;
                    let paginaAtual = gridOptions.api.paginationGetCurrentPage();
                    let qtd = $('#select-quantidade-por-pagina').val()

                    if (paginaAtual > 0) {
                        i = i - (paginaAtual) * qtd
                    }

                    if (i > 9) {
                        i = 9;
                    }

                    if (i > 4) {
                        ajusteAltura = 90;
                    } else {
                        ajusteAltura = 0;
                    }

                    if (data.resposta) {
                        dropdownItems += `
                    <div class="dropdown-item dropdown-item-acoes acoes-resposta" style="cursor: pointer;">
                        <a href="javascript:void(0);" class="visualizarResposta" data-id="${data.id}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">
                            Visualizar
                        </a>
                    </div>`;
                        return `
                    <div class="dropdown dropdown-table-comandos-enviados" data-tableId=${tableId}>
                        <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${"<?php echo base_url('') ?>" + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-detalhes-tecnologia" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}% - ${i}px);" aria-labelledby="${buttonId}">
                            ${dropdownItems}
                        </div>
                    </div>`;
                    } else {
                        return `
                    <div>
                        <button class="btn btn-dropdown" type="button" style="margin-top:-6px; width:35px; opacity: 0.5; cursor: default;" disabled>
                            <img src="${"<?php echo base_url('') ?>" + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                        </button>
                    </div>`;
                    }
                }
            }
            ];

            var rowData = comandos;

            var gridOptions = {
                columnDefs: columnDefs,
                rowData: rowData,
                defaultColDef: {
                    editable: false,
                    sortable: false,
                    filter: false,
                    resizable: true,
                    suppressMenu: true,
                },
                onGridReady: function () {
                    gridOptions.api.sizeColumnsToFit();
                },
                localeText: localeText,
                enableRangeSelection: true,
                enableCharts: true,
                pagination: true,
                paginateChildRows: true,
                domLayout: "normal",
                paginationPageSize: parseInt(
                    $("#select-quantidade-por-pagina").val()
                ),
                overlayNoRowsTemplate: "<p>Nenhum dado encontrado!</p>",
            };

            var eGridDiv = document.querySelector('#myGrid');
            new agGrid.Grid(eGridDiv, gridOptions);
        }


    });
</script>