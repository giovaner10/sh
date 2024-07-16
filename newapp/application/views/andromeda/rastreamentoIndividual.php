<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.js" integrity="sha512-yfb1lLOhiYYJh7C3dsBE4XGCnDCEe4dJ/jdVgoinVdKwVuDP2SJqrEngf0Q+m6gaU8vOjCaJ0EaeakGzXXfWIA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js" integrity="sha512-cktKDgjEiIkPVHYbn8bh/FEyYxmt4JDJJjOCu5/FQAkW4bc911XtKYValiyzBiJigjVEvrIAyQFEbRJZyDA1wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    const URL_PAINEL_OMNILINK = '<?= site_url("Andromeda") ?>';
</script>

<style>
    #header-modal {
        font-family: 'Mont SemiBold';
        color: #1C69AD !important;
        font-size: 22px !important;
        font-weight: bold !important;
        text-align: center;

    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('RastreamentoIndividual') ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('suporte') ?> >
        <?= lang('andromeda') ?> >
        <?= lang('RastreamentoIndividual') ?>
    </h4>
</div>


<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3" id="menu_nodes">
        <div id="filtroBusca" class="card " style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important; margin-left: -5px !important;">Filtrar Dados</h4>
            <form style="align-items:center" id="formPesquisa">

                <div id="pesquisa" class="form-group">
                    <label for="serial">Nº Série RI:</label>
                    <input class="form-control" type="text" id="serial" name="serial" required />
                </div>

                <div id="filtro" class="form-group" style="display: none;">
                    <label for="filtrarTecnologia">Tecnologia:</label>
                    <select class="form-control" id="filtrarTecnologia" name="filtrarTecnologia" style="width: 100% !important;"></select>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary" style="width:100%; margin-top: 10px" id="pesquisaserial"><i class="fa fa-search" aria-hidden="true"></i> <?= lang('pesquisar') ?></button>
                </div>

                <div class="button-container" style='margin-bottom: 20px; position: relative;'>
                    <button style='width:100%' id="BtnLimparPesquisar" class="btn btn-default" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> <?= lang('limpar') ?></button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card-conteudo card-dados-gerenciamento" id="tableGRID" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b>Dados:</b>
                <div class="d-flex align-items-center" style="display: inline-flex;">
                    <div class="dropdown" style="width: 100px;">
                        <button class="btn btn-gestor btn-primary dropdown-toggle" type="button" id="dropdownMenuButton_Rastreamento" data-toggle="dropdown" style="height: 35px;">
                            <?= lang('exportar') ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton_Rastreamento" id="opcoes_exportacao_rastreamento" style="margin-right: 16px; min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-grid" id="btn_grid" onclick="manipularMenuNodes()" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="height: 35px;">
                        <img id="botao_menu_nodes" src="<?php echo base_url('media/img/new_icons/icon-filter-hide.png') ?>" alt="Ícone" style="width: 30px; height: 30px; margin-top: -4px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <input type="text" id="search-input" placeholder="Pesquisar" style="margin-top: 19px; float: right;">
            </div>
            <div id="emptyMessage" class="emptyMessage" style="display: none;">
                <h4><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div id="loadingMessage" class="loadingMessage" style="display: none;">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div class="wrapper-rastreamento-individual" style='margin-top: 20px;'>
                <div id="table-rastreamento-individual" class="ag-theme-alpine my-grid-rastreamento-individual">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Testar Comunicação Chip -->
<div id="modalTestarChip" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog modal-xl" role="document" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="header-modal">Teste de Comunicação - Chip <span id="serieChip"></span></h3>
            </div>
            <div class="modal-body scrollModal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12 form-group" hidden>
                            <input type="text" class="form-control" id="idChip" hidden>
                        </div>
                        <div class="alert alert-info col-md-12">
                            <p class="col-md-12">Clique no botão para testar a comunicação do chip.</p>
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-primary" id="btnComunicacaoChip" onclick="testarComunicacaoChip($('#idChip').val())" style="margin-top: 20px;">
                                    <i class="fa fa-comments" aria-hidden="true"></i> Testar Comunicação do Chip
                                </button>
                            </div>
                        </div>
                        <br>
                        <div id="div_resultado_teste" style="margin-top: 20px;">
                        </div>
                        <br>
                        <div class="card-conteudo" style='margin-bottom: 20px; position: relative;'>
                            <h3>
                                <b>Dados:</b>
                                <div class="d-flex align-items-center" style="display: inline-flex;">
                                    <div class="dropdown" style="width: 100px;">
                                        <button class="btn btn-gestor btn-primary dropdown-toggle" type="button" id="dropdownMenuButton_Modal" data-toggle="dropdown" style="height: 35px;">
                                            <?= lang('exportar') ?>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton_Modal" id="opcoes_exportacao_teste_comunicacao" style="margin-right: 16px; min-width: 100px; top: 62px; height: 91px;">
                                        </div>
                                    </div>
                                    <button class="btn btn-light btn-grid" id="btn_grid" onclick="manipularMenuNodes()" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="height: 35px;" disabled>
                                        <img id="botao_menu_nodes" src="<?php echo base_url('media/img/new_icons/icon-filter-hide.png') ?>" alt="Ícone" style="width: 30px; height: 30px; margin-top: -4px;">
                                    </button>
                                </div>
                            </h3>
                            <div id="loadingMessageTeste" class="loadingMessageTeste" style="display: none;">
                                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                            </div>
                            <div class="wrapper-rastreamento-individual-teste-comunicacao" style='margin-top: 20px;'>
                                <div id="table-rastreamento-individual-teste-comunicacao" class="ag-theme-alpine my-grid-rastreamento-individual-teste-comunicacao">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="margin-right: 10px;">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var chaves = new Array()
    var opcoes = new Array()
    var equipamentos = new Array()
    var localeText = AG_GRID_LOCALE_PT_BR;

    $(document).ready(async function() {
        $("#filtrarTecnologia").select2({
            language: "pt-BR",
            placeholder: "Selecione uma opção",
            width: '100%'
        });

        $.ajax({
            type: 'GET',
            url: `${URL_PAINEL_OMNILINK}` + '/listarTesteComunicacaoChip',
            success: function(response) {
                response = JSON.parse(response);
                if (response.status === 200) {
                    DadosAgGridTesteComunicacao = response.dados.map(function(dado, index) {
                        return {
                            idTransp: dado.idTransp,
                            idFrota: dado.idFrota,
                            idTerminal: dado.idTerminal,
                            nomeRastreador: dado.nomeRastreador,
                            dataHoraInicio: dado.dataHoraInicio,
                            dataHoraFim: dado.dataHoraFim,
                            testeCel1: dado.testeCel1,
                            testeCel2: dado.testeCel2,
                            testeSat: dado.testeSat,
                            descricao: dado.descricao,
                            status: dado.status,
                            operCel1: dado.operCel1,
                            operCel2: dado.operCel2,
                            operSat: dado.operSat,
                        };
                    });
                    atualizarAgGridTesteComunicacao(DadosAgGridTesteComunicacao);
                } else {
                    alert('Erro ao listar testes de comunicação.');
                }
            },
            error: function(error) {
                alert('Erro ao obter dados do servidor.');
            }
        });
        atualizarAgGrid([]);
    });

    function ModaltestarComunicacaoChip($id) {
        $('#idChip').val($id);
        $('#serieChip').html($id);
        $('#div_resultado_teste').html('');
        $('#modalTestarChip').modal('show');
        atualizarAgGridTesteComunicacao(DadosAgGridTesteComunicacao);
    }

    function testarComunicacaoChip($id) {
        var idChip = $id;
        showLoadingTestarChipButton();

        $.ajax({
            type: 'POST',
            url: `${URL_PAINEL_OMNILINK}` + '/testeComunicacaoChip',
            data: {
                id: idChip
            },
            success: function(response) {
                response = JSON.parse(response);
                var modalContent = '';
                if (response.status === 200) {
                    modalContent += '<div class="alert alert-success" style="margin-bottom: 0px; margin-top: 100px;">';
                    modalContent += '<strong style="color: green;">' + lang.sucesso + '</strong>';
                    modalContent += '<br>' + lang.sucesso_teste_comunicacao;
                    modalContent += '</div>';

                    $('#div_resultado_teste').html(modalContent);
                    $('#modalTestarChip').modal('show');
                    stopLoadingTestarChipButton();

                    $.ajax({
                        type: 'GET',
                        url: `${URL_PAINEL_OMNILINK}` + '/listarTesteComunicacaoChip',
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.status === 200) {
                                DadosAgGridTesteComunicacao = response.dados.map(function(dado, index) {
                                    return {
                                        idTransp: dado.idTransp,
                                        idFrota: dado.idFrota,
                                        idTerminal: dado.idTerminal,
                                        nomeRastreador: dado.nomeRastreador,
                                        dataHoraInicio: dado.dataHoraInicio,
                                        dataHoraFim: dado.dataHoraFim,
                                        testeCel1: dado.testeCel1,
                                        testeCel2: dado.testeCel2,
                                        testeSat: dado.testeSat,
                                        descricao: dado.descricao,
                                        status: dado.status,
                                        operCel1: dado.operCel1,
                                        operCel2: dado.operCel2,
                                        operSat: dado.operSat,
                                    };
                                });
                                atualizarAgGridTesteComunicacao(DadosAgGridTesteComunicacao);
                            } else {
                                alert('Erro ao listar testes de comunicação.');
                            }
                        },
                    });

                } else {
                    modalContent += '<div class="alert alert-danger" style="margin-bottom: 0px; margin-top: 100px;">';
                    modalContent += '<strong style="color: red;">' + lang.erro + '</strong>';
                    modalContent += '<br>' + lang.erro_teste_comunicacao;
                    modalContent += '</div>';
                }

                $('#div_resultado_teste').html(modalContent);
                $('#modalTestarChip').modal('show');

                stopLoadingTestarChipButton();
            },
            error: function(error) {
                stopLoadingTestarChipButton();
            }
        });
    }

    function showLoadingPesquisarButton() {
        $('#pesquisaserial').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
    }

    function stopLoadingPesquisarButton() {
        $('#pesquisaserial').html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar').attr('disabled', false);
    }

    function showLoadingTestarChipButton() {
        $('#btnComunicacaoChip').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
    }

    function stopLoadingTestarChipButton() {
        $('#btnComunicacaoChip').html('<i class="fa fa-comments" aria-hidden="true"></i> Testar Comunicação do Chip').attr('disabled', false);
    }

    $('#BtnLimparPesquisar').click(function(e) {
        e.preventDefault();
        $("#serial").val("");
        // remove opções
        $('#filtrarTecnologia').children().remove();
        $("#filtro").attr('style', 'display:none;')
        atualizarAgGrid([]);
    })

    $('#filtrarTecnologia').change(event => {
        let filtro = $('#filtrarTecnologia').val()
        let arrTemp = new Array()
        arrTemp.push(equipamentos[filtro]);
        let dados = new Array()

        for (var i = 0; i < arrTemp[0].length; i++) {

            dados = dados.concat([{
                "serie": arrTemp[0][i]['idEquipamento'] ?? '-',
                "tecnologia": arrTemp[0][i]['nomeTecnologia'] ?? '-',
                "modelo": arrTemp[0][i]['nomeModelo'] ?? '-',
                "status": arrTemp[0][i]['descricaoStatus'] ?? '-',
                "celular": '(' + (arrTemp[0][i]['ddd'] ?? '-') + ') ' + (arrTemp[0][i]['fone'] ?? '-'),
                "operadora": arrTemp[0][i]['operadoraNome'] ?? "-",
                "data_hora": arrTemp[0][i]['data'].toLocaleString() ?? '-',
                "entrada": arrTemp[0][i]['sumInput'] ?? '-',
                "saida": arrTemp[0][i]['sumOutput'] ?? '-',
                "total_MB": arrTemp[0][i]['total'] ?? '-'
            }])
        }

        DadosAgGrid = dados.map(function(dado, index) {
            return {
                serie: dado.serie,
                tecnologia: dado.tecnologia,
                modelo: dado.modelo,
                status: dado.status,
                celular: dado.celular,
                operadora: dado.operadora,
                data_hora: dado.data_hora,
                entrada: dado.entrada,
                saida: dado.saida,
                total_MB: dado.total_MB,
            };
        });

        atualizarAgGrid(DadosAgGrid);
    });

    $('#formPesquisa').submit(event => {
        chaves = new Array()
        opcoes = new Array()
        equipamentos = new Array()
        let resultados = new Array()
        showLoadingPesquisarButton();
        event.preventDefault();
        $("option").remove('.opcao')
        $("#filtro").attr('style', 'display:none;')

        $.ajax({
            url: `${URL_PAINEL_OMNILINK}` + '/rastrearSerialIndividual',
            dataType: 'json',
            type: 'POST',
            data: {
                'serial': $("#serial").val()
            },
            success: function(resposta) {
                let status = resposta.status

                if (status == 200) {
                    let tamanho = 0
                    if (Object.keys(resposta.dados).length >= 1) {
                        requisicao = resposta.dados

                        Object.values(resposta.dados).forEach(equipamento => {
                            if (!opcoes.includes(equipamento['nomeTecnologia'] + ' ' + equipamento['nomeModelo'])) {
                                opcoes.push(equipamento['nomeTecnologia'] + ' ' + equipamento['nomeModelo'])
                                equipamentos[equipamento['nomeTecnologia'] + ' ' + equipamento['nomeModelo']] = new Array()

                            }
                            tamanho += 1
                            equipamentos[equipamento['nomeTecnologia'] + ' ' + equipamento['nomeModelo']].push(equipamento)
                        });

                        opcoes.forEach(opcao => {
                            $("#filtrarTecnologia").append($('<option>', {
                                value: opcao,
                                text: opcao,
                                class: 'opcao'
                            }));
                        });

                        $("#filtro").removeAttr('style')

                        chaves = Object.keys(equipamentos)

                        for (var i = 0; i < equipamentos[chaves[0]].length; i++) {

                            resultados.push({
                                "serie": equipamentos[chaves[0]][i]['idEquipamento'] ?? '-',
                                "tecnologia": equipamentos[chaves[0]][i]['nomeTecnologia'] ?? '-',
                                "modelo": equipamentos[chaves[0]][i]['nomeModelo'] ?? '-',
                                "status": equipamentos[chaves[0]][i]['descricaoStatus'] ?? '-',
                                "celular": '(' + (equipamentos[chaves[0]][i]['ddd'] ?? '-') + ') ' + (equipamentos[chaves[0]][i]['fone'] ?? '-'),
                                "operadora": equipamentos[chaves[0]][i]['operadoraNome'] ?? "-",
                                "data_hora": equipamentos[chaves[0]][i]['data'].toLocaleString() ?? '-',
                                "entrada": equipamentos[chaves[0]][i]['sumInput'] ?? '-',
                                "saida": equipamentos[chaves[0]][i]['sumOutput'] ?? '-',
                                "total_MB": equipamentos[chaves[0]][i]['total'] ?? '-'
                            })
                        }

                        DadosAgGrid = resultados.map(function(dado, index) {
                            return {
                                serie: dado.serie,
                                tecnologia: dado.tecnologia,
                                modelo: dado.modelo,
                                status: dado.status,
                                celular: dado.celular,
                                operadora: dado.operadora,
                                data_hora: dado.data_hora,
                                entrada: dado.entrada,
                                saida: dado.saida,
                                total_MB: dado.total_MB,
                            };
                        });
                        
                        atualizarAgGrid(DadosAgGrid);
                        stopLoadingPesquisarButton();

                    } else {
                        alert("Não foi possivel encontrar nenhum equipamento com o serial fornecido!");
                        stopLoadingPesquisarButton();
                    }
                } else {
                    alert("Não foi possivel encontrar nenhum equipamento com o serial fornecido!");
                    stopLoadingPesquisarButton();
                }
            },
            error: function(e) {
                alert("Ocorreu um problema ao tentar buscar os equipamentos, tente novamente mais tarde.")
            }

        })

    });

    function corrigirDataHora(dataHora) {
        const dataHoraCorrigida = new Date(dataHora);

        dataHoraCorrigida.setHours(dataHoraCorrigida.getHours() + 3);

        return dataHoraCorrigida
    }

    var AgGridRastreamentoIndividual;
    var DadosAgGrid = [];

    function atualizarAgGrid(dados) {
        stopAgGRID();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'Número de Série',
                    field: 'serie',
                    chartDataType: 'category',
                },
                {
                    headerName: 'Tecnologia',
                    field: 'tecnologia',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Modelo',
                    field: 'modelo',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Status',
                    field: 'status',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Celular',
                    field: 'celular',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Operadora',
                    field: 'operadora',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Data e Hora',
                    field: 'data_hora',
                    chartDataType: 'series',
                    cellRenderer: function(options) {
                        return corrigirDataHora(options.value).toLocaleString();
                    }
                }, {
                    headerName: 'Entrada (Bytes)',
                    field: 'entrada',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Saída (Bytes)',
                    field: 'saida',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Total (MB)',
                    field: 'total_MB',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Ações',
                    field: 'serie',
                    cellRenderer: function(params) {
                        return `
                        <div style="display: flex">
                            <button
                                class="btn btn-primary"
                                title="Testar Comunicação do Chip"
                                id="btnTestarComunicacaoChip"
                                style="margin-top: 2px; width: 40px; height: 35px; display: inline-flex; justify-content: center; align-items: center;"
                                onClick="javascript:ModaltestarComunicacaoChip('${params.value}')"
                            >
                                <i class="fa fa-comments" aria-hidden="true" style="margin-top: -6px;"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            defaultColDef: {
                enablePivot: true,
                editable: false,
                sortable: true,
                minWidth: 100,
                minHeight: 100,
                filter: true,
                resizable: true,
            },
            statusBar: {
                statusPanels: [{
                        statusPanel: 'agTotalRowCountComponent',
                        align: 'right'
                    },
                    {
                        statusPanel: 'agFilteredRowCountComponent'
                    },
                    {
                        statusPanel: 'agSelectedRowCountComponent'
                    },
                    {
                        statusPanel: 'agAggregationComponent'
                    },
                ],
            },
            localeText: localeText,
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            domLayout: 'autoHeight',
            pagination: true,
            paginationPageSize: 10,
        };

        var gridDiv = document.querySelector('#table-rastreamento-individual');
        AgGridRastreamentoIndividual = new agGrid.Grid(gridDiv, gridOptions);

        gridOptions.api.setRowData(dados);

        gridOptions.quickFilterText = '';
    document.querySelector('#search-input').addEventListener('input', function() {
        var searchInput = document.querySelector('#search-input');
        gridOptions.api.setQuickFilter(searchInput.value);
    });
    
    document.querySelector('#select-quantidade-por-pagina').addEventListener('change', function() {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

        preencherExportacoes(gridOptions);
    }

    var AgGridRastreamentoIndividualTesteComunicacao;
    var DadosAgGridTesteComunicacao = [];

    function atualizarAgGridTesteComunicacao(dados) {
        stopAgGRIDTesteComunicacao();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'Transporte',
                    field: 'idTransp',
                    chartDataType: 'category',
                },
                {
                    headerName: 'Frota',
                    field: 'idFrota',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Terminal',
                    field: 'idTerminal',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Rastreador',
                    field: 'nomeRastreador',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Data Início',
                    field: 'dataHoraInicio',
                    chartDataType: 'series',
                    cellRenderer: function(options) {
                        return corrigirDataHora(options.value).toLocaleString()
                    }
                },
                {
                    headerName: 'Data Fim',
                    field: 'dataHoraFim',
                    chartDataType: 'series',
                    cellRenderer: function(options) {
                        return corrigirDataHora(options.value).toLocaleString()
                    }
                },
                {
                    headerName: 'Celular 1',
                    field: 'testeCel1',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Celular 2',
                    field: 'testeCel2',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Satélite',
                    field: 'testeSat',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Descrição',
                    field: 'descricao',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Status',
                    field: 'status',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Operadora Celular 1',
                    field: 'operCel1',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Operadora Celular 2',
                    field: 'operCel2',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Operadora Setélite',
                    field: 'operSat',
                    chartDataType: 'series',
                }

            ],
            defaultColDef: {
                enablePivot: true,
                editable: false,
                sortable: true,
                minWidth: 100,
                minHeight: 100,
                filter: true,
                resizable: true,
            },
            statusBar: {
                statusPanels: [{
                        statusPanel: 'agTotalRowCountComponent',
                        align: 'right'
                    },
                    {
                        statusPanel: 'agFilteredRowCountComponent'
                    },
                    {
                        statusPanel: 'agSelectedRowCountComponent'
                    },
                    {
                        statusPanel: 'agAggregationComponent'
                    },
                ],
            },
            localeText: localeText,
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            domLayout: 'autoHeight',
            pagination: true,
            paginationPageSize: 10,
        };

        var gridDiv = document.querySelector('#table-rastreamento-individual-teste-comunicacao');
        AgGridRastreamentoIndividualTesteComunicacao = new agGrid.Grid(gridDiv, gridOptions);

        gridOptions.api.setRowData(dados);

        preencherExportacoesTesteComunicacao(gridOptions);
    }

    function stopAgGRID() {
        DadosAgGrid = [];
        var gridDiv = document.querySelector('#table-rastreamento-individual');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper-rastreamento-individual');
        if (wrapper) {
            wrapper.innerHTML = '<div id="table-rastreamento-individual" class="ag-theme-alpine my-grid-rastreamento-individual"></div>';
        }
    }

    function stopAgGRIDTesteComunicacao() {
        DadosAgGridTesteComunicacao = [];
        var gridDiv = document.querySelector('#table-rastreamento-individual-teste-comunicacao');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper-rastreamento-individual-teste-comunicacao');
        if (wrapper) {
            wrapper.innerHTML = '<div id="table-rastreamento-individual-teste-comunicacao" class="ag-theme-alpine my-grid-rastreamento-individual-teste-comunicacao"></div>';
        }
    }

    let menuAberto = false;

    function manipularMenuNodes() {
        menuAberto = !menuAberto;

        let buttonHide = "<?php echo base_url('media/img/new_icons/icon-filter-hide.png') ?>";
        let buttonShow = "<?php echo base_url('media/img/new_icons/icon-filter-show.png') ?>";

        if (menuAberto) {
            $('#botao_menu_nodes').attr("src", buttonShow);
            $('#filtroBusca').hide();
            $('#tableGRID').css('margin-left', -($('#menu_nodes').outerWidth() + 3) + 'px');
        } else {
            $('#botao_menu_nodes').attr("src", buttonHide);
            $('#filtroBusca').show();
            $('#tableGRID').css('margin-left', '0px');
        }
    }

    function preencherExportacoes(gridOptions) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao_rastreamento');
        const opcoes = ['csv', 'excel', 'pdf'];

        let buttonCSV = "<?php echo base_url('media/img/new_icons/csv.png') ?>";
        let buttonEXCEL = "<?php echo base_url('media/img/new_icons/excel.png') ?>";
        let buttonPDF = "<?php echo base_url('media/img/new_icons/pdf.png') ?>";

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
                exportarArquivo(opcao, gridOptions);
            });

            formularioExportacoes.appendChild(div);
        });
    }

    function preencherExportacoesTesteComunicacao(gridOptions) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao_teste_comunicacao');
        const opcoes = ['csv', 'excel', 'pdf'];

        let buttonCSV = "<?php echo base_url('media/img/new_icons/csv.png') ?>";
        let buttonEXCEL = "<?php echo base_url('media/img/new_icons/excel.png') ?>";
        let buttonPDF = "<?php echo base_url('media/img/new_icons/pdf.png') ?>";

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
            div.classList.add('dropdown-item-teste-comunicacao');
            div.classList.add('opcoes_exportacao_teste_comunicacao');
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
                exportarArquivoTesteComunicacao(opcao, gridOptions);
            });

            formularioExportacoes.appendChild(div);
        });
    }

    function exportarArquivo(tipo, gridOptions) {
        switch (tipo) {
            case 'csv':
                fileName = 'RastreamentoIndividual.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'RastreamentoIndividual.xlsx';
                gridOptions.api.exportDataAsExcel({
                    fileName: fileName
                });
                break;
            case 'pdf':
                let dadosExportacao = prepararDadosExportacaoRelatorio();

                let definicoesDocumento = getDocDefinition(
                    printParams('A4'),
                    gridOptions.api,
                    gridOptions.columnApi,
                    dadosExportacao.informacoes,
                    dadosExportacao.rodape
                );
                pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
                break;

        }
    }

    function exportarArquivoTesteComunicacao(tipo, gridOptions) {
        switch (tipo) {
            case 'csv':
                fileName = 'TesteComunicacaoChip.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'TesteComunicacaoChip.xlsx';
                gridOptions.api.exportDataAsExcel({
                    fileName: fileName
                });
                break;
            case 'pdf':
                let dadosExportacao = prepararDadosExportacaoRelatorioTesteComunicacao();

                let definicoesDocumento = getDocDefinition(
                    printParams('A4'),
                    gridOptions.api,
                    gridOptions.columnApi,
                    dadosExportacao.informacoes,
                    dadosExportacao.rodape
                );
                pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
                break;

        }
    }

    function prepararDadosExportacaoRelatorio() {
        let informacoes = DadosAgGrid.map((dados) => ({
            serie: dados.serie,
            tecnologia: dados.tecnologia,
            modelo: dados.modelo,
            status: dados.status,
            celular: dados.celular,
            operadora: dados.operadora,
            data_hora: dados.data_hora,
            entrada: dados.entrada,
            saida: dados.saida,
            total_MB: dados.total_MB,
        }));

        let rodape = `Rastreamento Individual`;
        let nomeArquivo = `RastreamentoIndividual.pdf`;

        return {
            informacoes,
            nomeArquivo,
            rodape
        };
    }

    function prepararDadosExportacaoRelatorioTesteComunicacao() {
        let informacoes = DadosAgGridTesteComunicacao.map((dados) => ({
            idTransp: dados.idTransp,
            idFrota: dados.idFrota,
            idTerminal: dados.idTerminal,
            nomeRastreador: dados.nomeRastreador,
            dataHoraInicio: dados.dataHoraInicio,
            dataHoraFim: dados.dataHoraFim,
            testeCel1: dados.testeCel1,
            testeCel2: dados.testeCel2,
            testeSat: dados.testeSat,
            descricao: dados.descricao,
            status: dados.status,
            operCel1: dados.operCel1,
            operCel2: dados.operCel2,
            operSat: dados.operSat,
        }));

        let rodape = `Teste de Comunicação`;
        let nomeArquivo = `TesteComunicacao.pdf`;

        return {
            informacoes,
            nomeArquivo,
            rodape
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('opcoes_exportacao_rastreamento');

        document.getElementById('dropdownMenuButton_Rastreamento').addEventListener('click', function() {
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButton_Rastreamento') {
                dropdown.style.display = 'none';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('opcoes_exportacao_teste_comunicacao');

        document.getElementById('dropdownMenuButton_Modal').addEventListener('click', function() {
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButton_Modal') {
                dropdown.style.display = 'none';
            }
        });
    });


    function printParams(pageSize) {
        return {
            PDF_HEADER_COLOR: "#ffffff",
            PDF_INNER_BORDER_COLOR: "#dde2eb",
            PDF_OUTER_BORDER_COLOR: "#babfc7",
            PDF_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
            PDF_HEADER_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
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
</script>