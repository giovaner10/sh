<script type="text/javascript" src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>

<style>
    .d-flex {
        display: flex;
    }

    body {
        background-color: #fff !important;
    }

    .justify-content-center {
        justify-content: center;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-center {
        align-items: center;
    }

    .align-start {
        align-items: flex-start;
    }

    /* Estilo da aba ativa */
    .pagination .selected a {
        background-color: #06a9f6;
        color: #FFFFFF;
    }

    /* Estilo das abas não ativas */
    .pagination li:not(.selected) a {
        background-color: #fff;
        color: #007bff;
        border: 1px solid #007bff;
        margin-right: 5px;
    }

    .pagination-info {
        margin-top: 10px;
        text-align: center;
        font-style: italic;
        display: contents;
    }

    #loading-indicator {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 9999;
    }

    h3.modal-title {
        color: #1C69AD !important;
        font-size: 22px !important;
        display: flex;
        justify-content: space-between;
    }

    .header-layout {
        border-bottom: 2px solid #e5e5e5;
        margin-top: 0.8rem
    }

    .close {
        border-radius: 25px;
        background-color: #e5e5e5 !important;
        width: 30px;
        height: 30px;
        color: #7F7F7F;
        font-size: 32px;
    }

    .modal-content {
        border-radius: 25px;
        gap: 25px;
    }

    .footer-group {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 8px 0px;
    }

    .btn {
        border-radius: 7px !important;
    }

    .btn-success {
        background-color: #28A745 !important;
    }

    .btn-success:hover {
        background-color: #28a746e7 !important;
    }

    .veic-form-group {
        padding: 0 10px;
    }
</style>
<div class="col-md-12">
    <h3><?= lang("c") ?></h3>

    <div class="div-caminho-menus-pais">
        <a href="<?= site_url('Homes') ?>">Home</a> >
        <a href="<?= site_url('cadastros') ?>">Cadastros</a> >
        <?= lang('veiculos') ?>
    </div>

    <div id="overlay">
        <div id="loading-indicator">
            <div class="spinner"></div>
        </div>
    </div>

    <hr>
    <form class="form-inline" style="float: right;display: inline-flex;">
        <div class="form-group" style="float: right; display: inline-flex; margin-bottom: 55px; margin-top: 10px; margin-right: 3px;">
            <input type="text" name="palavra" value="" placeholder="Palavra" class="form-control" data-provide="typeahead" autocomplete="off" required />
        </div>
        <div class="form-group" style="margin-right: 4px; position: relative; margin-top: 10px;">
            <select name="coluna" style="width: inherit;" class="form-control">
                <option value="placa">Placa</option>
                <option value="serial">Serial</option>
            </select>
        </div>
        <button style="font-style: italic; text-shadow: #444 0.5px 0.5px 1px; height: 33px; margin-top: 10px; margin-right: 3px;" type="submit" class="btn btn-primary">
            <i style="color: #FFFFFF; font-size: 18px" class="fa fa-search"></i>
        </button>
        <?php if ($this->input->get()) : ?>
            <a href="<?php echo site_url('cadastros/veiculos') ?>" class="btn btn-primary" style="font-style: italic; text-shadow: #444 0.5px 0.5px 1px; height: 33px; width: 72px; margin-top: 10px;">
                <i class="fa fa-icon-arrow-left" style="font-size: 16px;">Voltar</i></a>
        <?php endif ?>
    </form>

    <?php if ($per_page == 20 || $per_page == 50 || $per_page == 100 || $per_page == 200) : ?>
        <div class="form-group d-flex align-center col-md-6">
            <label for="itens_pagina" style="margin-right: 3px; font-weight: normal;">Exibindo</label>
            <select name="itens_pagina" id="itens_pagina" class="form-control font-normal" style="width: 75px; margin-right: 5px;">
                <option <?= $per_page == 20 ? 'selected' : '' ?> value="20">20</option>
                <option <?= $per_page == 50 ? 'selected' : '' ?> value="50">50</option>
                <option <?= $per_page == 100 ? 'selected' : '' ?> value="100">100</option>
                <option <?= $per_page == 200 ? 'selected' : '' ?> value="200">200</option>
            </select>
            <label for="itens_pagina" style="font-weight: normal;">resultados por página</label>
        </div>
    <?php endif; ?>

    <!-- <div class="dataTables_info" id="tabelaListaVeiculo_info" role="status" aria-live="polite">
                Mostrando 1 até <?php echo $total_rows; ?> de <?php echo $total_rows; ?> registros
            </div> -->

    <br style="clear: both" />
    <div>
        <div>
            <?php if ($this->session->flashdata('sucesso')) : ?>
                <div class="alert alert-success">
                    <p><?php echo $this->session->flashdata('sucesso') ?></p>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('erro')) : ?>
                <div class="alert alert-error">
                    <p><?php echo $this->session->flashdata('erro') ?></p>
                </div>
            <?php endif; ?>

            <?php if ($clientes) : ?>
                <table class="table table-responsive table-bordered" id="tabelaListaVeiculo" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Placa</th>
                            <th>Serial</th>
                            <?php if ($this->auth->is_allowed_block('administrar_veiculos')) : ?>
                                <th>Desvincular</th>
                            <?php endif; ?>
                            <th>Comando</th>
                            <th>Coordenadas Diária</th>
                            <th>Coordenadas Semanal</th>
                            <th>Grupos do Veículo</th>
                            <?php if ($this->auth->is_allowed_block('administrar_veiculos')) : ?>
                                <th>Administrar</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php if (!empty($clientes) && is_array($clientes)) : ?>
                        <?php foreach ($clientes as $cliente) : ?>
                            <tr>
                                <td>
                                    <button data-serial="<?php echo $cliente->serial ?>" class="btn btn-primary botao_posicao" title="Posição Equipamento"> <i class="fa fa-map-marker"></i></button>
                                </td>
                                <td>
                                    <?php echo $cliente->code ?>
                                </td>
                                <td>
                                    <?php echo $cliente->cliente ?>
                                </td>
                                <td>
                                    <?php echo $cliente->veiculo ?>
                                </td>
                                <td>
                                    <?php echo $cliente->placa ?>
                                </td>
                                <td>
                                    <?php echo $cliente->serial ?>
                                </td>
                                <?php if ($this->auth->is_allowed_block('administrar_veiculos')) : ?>
                                    <td style="text-align: center">
                                        <?php if ($cliente->serial) : ?>
                                            <a href="#" class="btn btn-mini btn-primary" data-toggle="modal" data-target="#modalDesvincular_<?php echo $cliente->code ?>" title="Desvincular" style="font-size: 12px;">
                                                <i class="fa fa-warning"></i>
                                            </a>
                                        <?php else : ?>
                                            <button class="btn btn-mini btn-primary" title="Desvincular" style="font-size: 12px;" onclick="alert('Nao foi possível desvincular, o veículo não possui serial.')">
                                                <i class="fa fa-warning"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td style="text-align: center">
                                    <?php if ($cliente->serial) : ?>
                                        <a class="btn btn-mini btn-primary comando-button" href="<?php echo site_url('comandos/envio/' . $cliente->serial . '/' . $cliente->code) ?>" data-serial="<?php echo $cliente->serial; ?>" title="Comando" target="_blank">
                                            <i class="fa fa-stack-overflow"></i>
                                        </a>

                                    <?php else : ?>
                                        <button class="btn btn-mini btn-primary" title="comandos" style="font-size: 12px;" onclick="alert('Serial não encontrado para este veículo.')">
                                            <i class="fa fa-stack-overflow"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>

                                <td style="text-align: center">
                                    <a style="font-size: 12px;" href="<?php echo site_url('relatorio_coordenadas/getCoordenadasDia/' . ($cliente->placa ? $cliente->placa : 'null') . '/' . $cliente->code_cliente) ?>" class="btn btn-mini btn-primary" target="_blank" title="Visualizar" style="box-shadow: #444 2px 2px 2px;">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    <a style="font-size: 12px;" href="<?php echo site_url('relatorio_coordenadas/getCoordenadasSemana/' . ($cliente->placa ? $cliente->placa : 'null') . '/' . $cliente->code_cliente) ?>" class="btn btn-mini btn-primary" target="_blank" title="Visualizar">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>

                                <td style="text-align: center">
                                    <button class="btn btn-primary" target="_blank" onclick='carregarModalGruposVeiculo("<?= $cliente->placa ?>")'>
                                        <i class="fa fa-users fa-lg"></i>
                                    </button>
                                </td>

                                <?php if ($this->auth->is_allowed_block('administrar_veiculos')) : ?>
                                    <td style="text-align: center">
                                        <button onclick="abrirModalAdministrar('<?php echo site_url('cadastros/cadastro_veiculo/' . $cliente->code) ?>', '<?php echo $cliente->code; ?>')" class="btn btn-mini btn-primary open-modal-btn" title="Visualizar">
                                            <i style="font-size: 20px; color: whitesmoke; text-shadow: #444 1px 1px 1px;" class="fa fa-cog">
                                            </i>
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>

                        <?php endforeach ?>
                    <?php endif; ?>
                </table>
            <?php endif ?>

            <div class="pagination-info">
                Mostrando <?php echo ($page) + 1; ?> a <?php echo min(($page) + count($dados['clientes']), $page + $per_page); ?> de <?php echo $total_rows; ?> resultados
            </div>


            <?php if (!empty($clientes) && is_array($clientes)) : ?>
                <?php foreach ($clientes as $cliente) : ?>
                    <!-- Modal de Desvinculação -->
                    <div class="modal fade" id="modalDesvincular_<?php echo $cliente->code ?>" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitle">Confirmar Desvinculação</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Placa</th>
                                            <th>Serial</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo $cliente->placa; ?></td>
                                            <td><?php echo $cliente->serial; ?></td>
                                        </tr>
                                    </table>
                                    Tem certeza de que deseja desvincular o veículo?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="desvincular('<?php echo $cliente->serial; ?>', '<?php echo $cliente->placa; ?>')">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function desvincular(serial, placa) {
                            $.ajax({
                                url: "<?php echo site_url('veiculos/desvincular_confirmado') ?>/" + serial + "/" + placa,
                                type: "GET",
                                success: function() {
                                    alert("Desvinculado com sucesso.");
                                    location.reload();
                                },
                                error: function(xhr, status, error) {
                                    var errorMessage = "Erro ao desvincular, tente novamente.";
                                    if (xhr.status === 404) {
                                        errorMessage = "Página não encontrada.";
                                    } else if (xhr.status === 500) {
                                        errorMessage = "Erro interno do servidor.";
                                    }
                                    alert(errorMessage + " Status: " + xhr.status);
                                }
                            });
                            $('#modalDesvincular_<?php echo $cliente->code ?>').modal('hide');
                        }
                    </script>
                <?php endforeach ?>

            <?php endif; ?>

            </hr>
            <!-- MODAL POSIÇÃO DO EQUIPAMENTO -->
            <div id="modal_posicao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            <h4 id="myModalLabel1">Posição Equipamento</h4>
                        </div>
                        <div class="modal-body">
                            <div style="background-color: #f5f5f5; padding: 10px;">
                                <div id="posicaoveic"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL GRUPOS DO VEICULO -->
            <div id="modal_grupos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            <h4 id="myModalLabel1">Grupos do Veiculo</h4>
                        </div>
                        <div class="modal-body">
                            <div style="background-color: #f5f5f5; padding: 10px;">
                                <table id='tabela_grupos_veiculo' class="datatable display responsive table-bordered table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Grupo</th>
                                            <th>Cliente</th>
                                            <th>Espelhamento</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_grupos_veiculo">
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>


            <center>
                <?php echo $this->pagination->create_links(); ?>
            </center>
        </div>
    </div>


    <div class="modal fade" id="modalVeiculoCadastro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleWhitelist">Cadastro de Veículo</h3>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success bt-salvar-veiculo" id="salvar" data-tipo="atualizar">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var tabelaGruposVeiculo;

    //Define a tabela de grupos do veiculo quando a pagiba é carregada
    $(document).ready(function() {
        tabelaGruposVeiculo = $('#tabela_grupos_veiculo').DataTable({
            dom: 'Bfrtip',
            reponsive: true,
            order: [
                [0, "asc"]
            ],
            columns: [{
                    data: 'nome_grupo'
                },
                {
                    data: 'nome_cliente'
                },
                {
                    data: 'espelhamento'
                }
            ],
            language: langDatatable,

        });
    });

    //Carrega os grupos do veiculo no modal
    function carregarModalGruposVeiculo(placa) {
        tabelaGruposVeiculo.clear().draw();
        $.ajax({
            url: "<?php echo site_url('veiculos/listar_grupos_veiculos') ?>",
            type: "POST",
            data: {
                placa: placa
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status === "OK") {
                    var dadosTratados = tratarEspelhamento(data.results);
                    tabelaGruposVeiculo.rows.add(dadosTratados).draw();
                    $('#modal_grupos').modal('show');

                }
            }
        });
    }


    var tabelaEspelhamentoVeiculo;

    //Define a tabela de espelhamentos do veiculo quando a pagina é carregada
    $(document).ready(function() {
        tabelaEspelhamentoVeiculo = $('#tabela_espelhamentos_veiculo').DataTable({
            dom: 'Bfrtip',
            reponsive: true,
            order: [
                [0, "asc"]
            ],
            columns: [{
                data: 'Nome'
            }, {
                data: 'IP'
            }, {
                data: 'Porta'
            }, {
                data: 'CNPJ'
            }],
            language: langDatatable,
        });
    });

    var tabelaListaVeiculo;

    //Define a tabela de listagem do veiculo quando busca pela placa ou serial
    $(document).ready(function() {
        tabelaListaVeiculo = $('#tabelaListaVeiculo').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            paging: false,
            bInfo: false,
            order: [
                [1, "desc"] // Ordenar pela coluna do "Código" em ordem descendente por padrão
            ],
            language: {
                emptyTable: "Nenhum registro encontrado"
            },
            buttons: [{
                    extend: 'excelHtml5',
                    orientation: customPageExport('tabelaListaVeiculo', 'orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL',
                    exportOptions: {
                        format: {
                            body: function(data, row, column, node) {
                                var columnsDates = [14, 15, 16, 17];
                                if (columnsDates.includes(column)) {
                                    if (data)
                                        return data.replace('.', ';').replace(',', '.').replace(';', ','); //deixa no formato 1,254.21
                                }
                                return data;
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: customPageExport('tabelaListaVeiculo', 'orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    orientation: customPageExport('tabelaListaVeiculo', 'orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    extend: 'print', // Adicione o botão de impressão
                    orientation: customPageExport('tabelaListaVeiculo', 'orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> IMPRIMIR',
                }
            ],
        });

        // Ordenar por coluna do "Código" quando o usuário realizar uma pesquisa
        $('#tabelaListaVeiculo_filter input').on('keyup', function() {
            tabelaListaVeiculo.order([1, 'desc']).draw();
        });
    });

    //Carrega os Veículos espelhados com as centrais saver
    function carregarModalEspelhamentoVeiculos(serial) {
        tabelaEspelhamentoVeiculo.clear().draw();

        $('#modal_espelhamento').modal('show');
        $.ajax({
            url: "<?php echo site_url('veiculos/listar_espelhamentos_veiculos') ?>",
            type: "POST",
            data: {
                serial: serial
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status === "OK") {
                    tabelaEspelhamentoVeiculo.rows.add(data.results).draw();
                    $('#modal_espelhamento').modal('show');
                }
            }
        });
    }


    //Trata os dados para exibir o espelhamento
    function tratarEspelhamento(dados) {
        Object.keys(dados).forEach(function(key) {
            if (dados[key].espelhamento == 0) {
                dados[key].espelhamento = "Contrato";
            } else if (dados[key].espelhamento == 1) {
                dados[key].espelhamento = "Espelhamento Mhs";
            } else if (dados[key].espelhamento == 2) {
                dados[key].espelhamento = "Compartilhamento de sinal";
            } else {
                dados[key].espelhamento = "Rejeitado";
            }
        });
        return dados;
    }



    $(document).on('click', '.botao_posicao', function(e) {
        e.preventDefault();
        var botao = $(this);
        var serial = botao.attr('data-serial');
        $("#modal_posicao").modal();
        var href = "<?= site_url('/equipamentos/posicao/') ?>/" + serial;

        carregar_viewPosicao(serial, href);
    });

    function carregar_viewPosicao(serial, href) {
        $('#posicaoveic').html('<p>Carregando...</p>');
        $.ajax({
            url: href,
            dataType: 'html',
            success: function(html) {
                if (serial) {
                    $('#posicaoveic').html(html);
                    setTimeout(function() {
                        carregar_viewPosicao(serial, href);
                    }, 60000000);
                } else {
                    html = '<p>Veiculo sem informação de posição</p>';
                    $('#posicaoveic').html(html);
                }
            }
        });

    }

    $(document).ready(function() {
        // Pega a página atual da URL
        var url = new URL(window.location.href);
        var pagina_atual = url.searchParams.get("page");

        if (pagina_atual !== null) {
            // Adiciona a classe "selected" à aba correspondente
            $('.pagination li').eq(pagina_atual - 1).addClass('selected');
        }
    });


    $('#itens_pagina').on('change', function() {
        var url = `<?= site_url('cadastros/veiculos/') ?>/0/${$(this).val()}`;
        window.location = url;
    });

    function abrirModalAdministrar(href, code) {
        $("#modalVeiculoCadastro .modal-body").html('');
        document.getElementById('loading-indicator').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
        $("#modalVeiculoCadastro .modal-content").load(href, function() {
            document.getElementById('overlay').style.display = 'none';
            $("#codeVeiculo").val(code);
            $("#modalVeiculoCadastro").modal("show");
        });
    };

    // Exibe o loading-indicator assim que a página começa a carregar
    window.addEventListener('load', function() {
        document.getElementById('loading-indicator').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';

        var clientes = <?php echo json_encode($clientes); ?>;

        if (!clientes) {
            setTimeout(function() {
                alert('Veículo não possui item de contrato.');
            }, 1000);
        }

        setTimeout(function() {
            document.getElementById('overlay').style.display = 'none';
        }, 1000);
    });


    document.addEventListener("DOMContentLoaded", function() {
        var comandoButtons = document.querySelectorAll(".comando-button");

        comandoButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                // Obtém o valor do serial a partir do atributo "data-serial"
                var serial = this.getAttribute("data-serial");

                // Verifica se o serial começa com "MDVR"

                if (serial.startsWith("MDVR")) {
                    alert("Comandos não disponíveis para esse serial.");

                    event.preventDefault();
                } else if (serial.startsWith("OM")) {
                    alert("Comandos não disponíveis para esse serial.");

                    event.preventDefault();
                }

            });
        });
    });
</script>