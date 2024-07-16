<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" />
                    </div>

                    <div class="input-container buscaData" id="dateContainer2">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" />
                    </div>


                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-politica" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b>Políticas: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary" id="buttonNovaPolitica" onclick="formularioNovaPolitica();" style="height: 36.5px;"><?= lang("nova_politica") ?></button>
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
                <select id="select-quantidade-por-pagina-politica" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div class="wrapperPolitica">
                    <div id="tablePolitica" class="ag-theme-alpine my-grid-politica" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<table id="dt_politicas" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <th><?= lang("codigo") ?></th>
            <th><?= lang("descricao") ?></th>
            <th style="min-width: 70px;"><?= lang("acoes") ?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="modalPolitica"></div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>

<script type="text/javascript">
    let dtPoliticasButtons = [];
    let dtPoliticasColumnDefs = [];
    let politicasEdicaoExclusaoHtml = '';
    let identificadorModalPolitica = 'Politica';
    let rota = '<?= site_url('PoliticasFormularios/getPoliticasFormularios/P') ?>'

    $(document).ready(function() {
        getDados();

    });

    function getDados(options) {

        $.ajax({
            url: rota + '/' + $("#departamentoId").val(),
            type: 'POST',
            success: function(data) {
                if (data == '') {
                    alert("Dados não encontrados para os parâmetros informados.")
                }
                for (let i = 0; i < data.length; i++) {
                    for (let chave in data[i]) {
                        if (data[i][chave] === null) {
                            data[i][chave] = '';
                        }
                    }
                }
                atualizarAgGrid(data);
            },
            error: function(error) {
                alert('Erro na solicitação ao servidor');
            },
        });
    }

    var AgGrid;

    function atualizarAgGrid(dados) {
        stopAgGRID();

        const gridOptions = {
            columnDefs: [{
                    headerName: 'Código',
                    field: 'codigo',
                    chartDataType: 'category',
                    width: 100,
                    suppressSizeToFit: true
                },
                {
                    headerName: 'Descrição',
                    field: 'descricao',
                    chartDataType: 'category',
                    flex: 1,
                    minWidth: 300,
                    suppressSizeToFit: true
                },
                {
                    headerName: 'Ação',
                    pinned: 'right',
                    width: 80,
                    cellClass: "actions-button-cell",
                    sortable: false,
                    cellRenderer: function(options) {
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
                        let tableId = "tablePolitica";
                        let dropdownId = "dropdown-menu-ultima-configuracao-" + data.id + varAleatorioIdBotao;
                        let buttonId = "dropdownMenuButtonMenu_" + data.id + varAleatorioIdBotao;

                        return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirModalComando('${encodeURIComponent(dataString)}', 'Historico')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:reenviarComando('${data.idCliente}', '${data.idPerfil}', '${data.serial}', '${data.numConfig}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Reenviar Comando</a>
                            </div>
                        </div>
                    </div>`;
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
                toolPanels: [{
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
                }, ],
                defaultToolPanel: false,
            },
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            pagination: true,
            domLayout: 'normal',
            paginationPageSize: parseInt($('#select-quantidade-por-pagina-politica').val()),
            localeText: localeText,
        };


        var gridDiv = document.querySelector('#tablePolitica');
        gridDiv.style.setProperty('height', '519px');
        AgGridCemUltSolic = new agGrid.Grid(gridDiv, gridOptions);
        AgGridCemUltSolic.gridOptions.api.setRowData(dados);


        $('#select-quantidade-por-pagina-politica').change(function() {
            var selectedValue = $(this).val();
            AgGridCemUltSolic.gridOptions.api.paginationSetPageSize(Number(selectedValue));
        });
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#tablePolitica');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapperPolitica');
        if (wrapper) {
            wrapper.innerHTML = '<div id="tablePolitica" class="ag-theme-alpine my-grid-politica"></div>';
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

        $(document).on('click', function(event) {
            if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
                dropdown.hide();
            }
        });
    }

    //////////////////

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_politicasformularios')) : ?>

        // Nova Política
        dtPoliticasButtons.push({
            className: 'btn btn-lg btn-primary',
            text: '<?= lang("nova_politica") ?>',
            attr: {
                id: 'buttonNovaPolitica'
            },
            action: function(e, dt, node, config) {
                formularioNovaPolitica();
            }
        });

        // Edição e Exclusão
        politicasEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarPolitica(__id__)" data-toggle="tooltip"
                id="buttonEditarPolitica___id__" class="btn btn-sm btn-primary" title="<?= lang('editar') ?>">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalExcluirPolitica(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?= lang('remover') ?>">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    dtPoliticasColumnDefs.push({
        render: function(data, type, row) // Visualizar Politica
        {
            return `\
                    <a href="<?= base_url('uploads/politica_formulario') ?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?= lang('visualizar') ?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?= base_url('uploads/politica_formulario') ?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?= lang('baixar') ?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${politicasEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
        },
        className: 'text-center',
        targets: 2
    });

    // DataTable
    var dt_politicas = $("#dt_politicas").DataTable({
        order: [],
        language: lang.datatable,
        autoWidth: false,
        bLengthChange: false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url: "<?= site_url('PoliticasFormularios/getPoliticasFormularios/P') ?>/" + $("#departamentoId").val()
        },
        dom: 'Bfrtip',
        buttons: dtPoliticasButtons,
        columnDefs: dtPoliticasColumnDefs,
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioNovaPolitica() {
        // Carregando
        $('#buttonNovaPolitica')
            .html('<i class="fa fa-spin fa-spinner"></i> <?= lang('carregando') ?>')
            .attr('disabled', true);

        // Modal
        $("#modalPolitica").load(
            "<?= site_url('PoliticasFormularios/formularioPolitica') ?>",
            function() {
                // Carregado
                $('#buttonNovaPolitica')
                    .html('<?= lang('nova_politica') ?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarPolitica(politicaId) {
        // Carregando
        $('#buttonEditarPolitica_' + politicaId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Modal
        $("#modalPolitica").load(
            "<?= site_url('PoliticasFormularios/formularioPolitica') ?>/" + politicaId,
            function() {
                // Carregado
                $('#buttonEditarPolitica_' + politicaId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            });
    }

    function modalExcluirPolitica(politicaId) {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalPolitica,
            politicaId,
            '<?= lang("confirmacao_exclusao_politica") ?>' // Texto modal
        )
    }

    function excluirPolitica() {
        let politicaId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalPolitica);

        // Carregando
        $('#btnExcluirPolitica')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta politica
        $.ajax({
            url: '<?= site_url("PoliticasFormularios/excluirPoliticaFormulario") ?>/' + politicaId,
            type: "POST",
            dataType: "JSON",
            success: function(retorno) {
                if (retorno.status == 1) {
                    // Mensagem de retorno
                    showAlert('success', retorno.mensagem)

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalPolitica);

                    // Recarrega a tabela
                    getDados();
                } else {
                    // Mensagem de retorno
                    showAlert('warning', retorno.mensagem)
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // Mensagem de retorno
                showAlert('error', "Erro na solicitação ao servidor")
            },
            complete: function() {
                // Carregado
                $('#btnExcluirPolitica')
                    .html('<?= lang("excluir") ?>')
                    .attr('disabled', false);
            }
        });
    }
</script>