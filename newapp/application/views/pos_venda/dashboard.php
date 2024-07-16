<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<!-- SELECT2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<!-- Traduções -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/pt-BR.js"></script>
<!---------------->

<style type="text/css">
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    border: none !important;
    margin-top: 8px !important;
    margin-bottom: 5px !important;
}

html {
    scroll-behavior: smooth
}

body {
    background-color: #fff !important;
}

table {
    width: 100% !important;
}

.blem {
    color: red;
}

.container-fluid {
    padding: 0;
}

.dataTables_wrapper .dataTables_processing {
    background: none;
}

.dataTables_wrapper .dataTables_processing div {
    display: inline-block;
    vertical-align: center;
    font-size: 68px;
    height: 100%;
    top: 0;
}

th,
td.wordWrap {
    max-width: 100px;
    word-wrap: break-word;
    text-align: center;
}

.checkbox label {
    font-weight: 700;
}

.select-container .select-selection--single {
    height: 35px !important;
}

.my-1 {
    margin-top: 1em !important;
    margin-bottom: 1em !important;
}

.mx-1 {
    margin-left: 1em;
    margin-right: 1em;
}

.pt-1 {
    padding-top: 1em;
}

.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.justify-content-end {
    justify-content: flex-end;
}

.align-center {
    align-items: center;
}

.modal-xl {
    max-width: 1300px;
    width: 100%;
}

.border-0 {
    border: none !important;
}

.markerLabel {
    background-color: #fff;
    border-radius: 4px;
    padding: 4px;
}

.action-bar * {
    margin-left: 5px;
}

.select-selection--multiple .select-search__field {
    width: 100% !important;
}

.bold-text {
    font-weight: bold;
}


.select2-container .select2-search--inline {
    float: none;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    margin: 10px;
}
</style>




<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Gestão Pós-Venda", site_url('Homes'), "Pós-Venda", " Dashboard");
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3" id="menu_nodes">
        <div id="filtroBusca" class="card " style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important; margin-left: -5px !important;">Filtrar Dados:</h4>
            <form style="align-items:center" action="#" id="formRel" method="POST">

                <div class="input-container status" id="statusContainer"
                    style='margin-bottom: 20px; position: relative;'>
                    <label for="analistaSelect"> Analista:</label>
                    <select id="analistaSelect" name="analistaSelect" class="form-control">
                        <option value="">Carregando...</option>
                    </select>
                </div>

                <div class="input-container2 status" id="statusContainer"
                    style='margin-bottom: 20px; position: relative;'>
                    <label for="clientesSelect">Selecione um Cliente:</label>
                    <select id="clientesSelect" name="clientesSelect" class="select2 form-control select2-responsive">
                        <option value="">Carregando...</option>
                    </select>
                </div>

                <div class="input-container status" id="statusContainer"
                    style='margin-bottom: 20px; position: relative;'>
                    <label for="categoria"> Categoria:</label>
                    <select id="categoria" name="categoria" class="form-control" style="width: 100%;" disabled>
                    </select>
                </div>

                <div class="input-container status" id="statusContainer"
                    style='margin-bottom: 20px; position: relative;'>
                    <label for="tag"> Tag:</label>
                    <select id="tag" name="tag" class="form-control" style="width: 100%;" disabled>
                    </select>
                </div>


                <div class="input-container status" id="statusContainer"
                    style='margin-bottom: 20px; position: relative;'>
                    <label for="status_financeiro_cliente">Status:</label>
                    <select name="status_financeiro_cliente" id="status_financeiro_cliente" style="width: 100%;"
                        class="form-control" disabled>
                        <!--<option value="">Todos</option>
						<option value="1">Ativo</option>
						<option value="5">Cancelado</option>
						<option value="5">Aguardando Ativação</option>-->
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-success gerar_rel" disabled
                        style='margin-bottom: 10px; position: relative; width: 100%;' id="gerarRelatorioIscas"><i
                            class="fa fa-search" aria-hidden="true"></i> Gerar </button>
                    <button class="btn btn-default" disabled style='width:100%; margin-bottom: 20px'
                        id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                </div>

            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px;'>
            <h3>
                <b id="titulo-card">Dashboard: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                </div>
                <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">

                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left"
                        title="<?php echo lang('expandir_grid') ?>"
                        style="border-radius:6px; padding:5px; margin-left: auto;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>"
                            posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>

            <div
                style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center; width: 100%; ">
                <div class="grade-principal"
                    style="display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-around; width: 100%; ">

                    <div class="grade-sec">

                        <div class="col-sm-2 metrica ">
                            <a href="<?php echo site_url('PosVenda/Gestao/clientes_ativos'); ?>"style="text-decoration: none; color: inherit;">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/user-solid.svg') ?>" class="metrica-img2" />
                                    <p id="q_clientes_ativos" style="font-size: 35px; color: #1C69AD"
                                       style="margin-top: 20px">Aplicar filtros </p>
                                </div>
                                    <div
                                        style="display: flex; flex-direction:column; justify-content:center; align-items: center;">
                                        <p>Clientes Ativos</p>
                                    </div>
                            </div>
                             </a>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/user-solid.svg') ?>" class="metrica-img2" />
                                    <p id="q_clientes_saver" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">Aplicar filtros </p>
                                </div>
                                <p>Cliente Saver</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/user-solid.svg.') ?>" class="metrica-img2" />
                                    <p id="q_clientes_gestor" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">Aplicar filtros </p>
                                </div>
                                <p>Cliente Gestor</p>
                            </div>
                        </div>
                    </div>


                    <div class="grade-sec">

                        <div class="col-sm-2 metrica mb-3">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/money-check-dollar-solid.svg') ?>"
                                        class="metrica-img" />
                                    <p id="q_financeiro" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Sit. Financeira</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/money-check-dollar-solid.svg') ?>"
                                        class="metrica-img" />
                                    <p id="q_financeiro_em_dia" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Sit.Financeira</p>
                                <p style="color: #1C69AD ;">Em dia</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/money-check-dollar-solid.svg') ?>"
                                        class="metrica-img" />
                                    <p id="q_finaceiro_atrasado" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Sit. Financeira</p>
                                <p style="color: red;">Atrasado</p>
                            </div>
                        </div>

                    </div>

                    <div class="grade-sec">

                        <div class="col-sm-2 metrica mb-3">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/file-signature-solid.svg') ?>"
                                        class="metrica-img" />
                                    <p id="q_contratos_ativos" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Contratos Ativos</<p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/file-signature-solid.svg') ?>"
                                        class="metrica-img" />
                                        <p id="q_contratos_com_comunicacao" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Contratos Ativos</p>
                                <p style="color: #1C69AD;">Com Comunicação 24h</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/file-signature-solid.svg') ?>"
                                        class="metrica-img" />
                                        <p id="q_contratos_sem_comunicacao" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Contratos Ativos</p>
                                <p style="color: red;">Sem comunicação 24h</p>
                            </div>
                        </div>

                    </div>

                    <div class="grade-sec">

                        <div class="col-sm-2 metrica mb-3">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/ticket-solid.svg') ?>" class="metrica-img" />
                                    <p id="q_tickets_aberto" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Tickets Abertos</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/ticket-solid.svg') ?>" class="metrica-img" />
                                    <p id="q_tickets_aberto_dentro" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Tickets Abertos</p>
                                <p style="color: #1C69AD ;">Dentro da SLA</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/ticket-solid.svg') ?>" class="metrica-img" />
                                    <p id="q_tickets_aberto_fora" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Tickets Abertos</p>
                                <p style="color: red;">Fora da SLA</p>
                            </div>
                        </div>
                    </div>

                    <div class="grade-sec">

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/gear-solid.svg') ?>" class="metrica-img" />
                                    <p id="q_manutencoes" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Manutenções</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/gear-solid.svg') ?>" class="metrica-img" />
                                    <p id="q_manutencoes_dentro" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Manutenções</p>
                                <p style="color: #1C69AD;">Dentro da SLA</p>
                            </div>
                        </div>

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/gear-solid.svg') ?>" class="metrica-img" />
                                    <p id="q_manutencoes_fora" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Manutenções</p>
                                <p style="color: red;">Fora da SLA</p>
                            </div>
                        </div>
                    </div>

                    <div class="grade-sec">

                        <div class="col-sm-2 metrica">
                            <div id="card-cold-list" class="card metrica-card">
                                <div class="card-header">
                                    <img src="<?= base_url('assets/images/hourglass-half-solid.svg') ?>"
                                        class="metrica-img2" />
                                        <p id="q_aguardando_ativacao" style="font-size: 35px; color: #1C69AD"
                                        style="margin-top: 20px">0</p>
                                </div>
                                <p>Aguardando Ativação no CRM</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- AG Charts Community edition. -->
        <script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>
        <!-- Leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <!-- JavaScript -->
        <link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Gestao', 'dashboard.css') ?>">


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
        <script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
        <script type="text/javascript" src="<?= versionFile('assets/js/fatura', 'exportacoes.js') ?>"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <style>
        .grade-sec {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            border-radius: 5px;
            margin: 5px;
            margin-bottom: 30px;
            min-width: 200px;
        }

        .metrica-card {
            width: 140px !important;
            height: 130px !important;
            align-items: center;
            /*justify-content: center;*/
            box-shadow: 0 4px 8px rgb(0 0 0 / 27%);
            min-width: 200px;
        }

        .card-conteudo {
            padding: 0;
        }

        .card-header {
            margin-bottom: 15px;
            /*  margin: 0 auto;
            /* display: block; */
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: space-between;
            margin-top: 20px;
        }
        </style>
        <script>
        var BaseURL = '<?= base_url('') ?>';
        </script>


        <script type="text/javascript">
        jQuery(function($) {

            //adicionar conteudo

            var menuAberto = false;
            $(".btn-expandir").on("click", function(e) {
                e.preventDefault();
                menuAberto = !menuAberto;

                if (menuAberto) {
                    $(".img-expandir").attr(
                        "src",
                        `${BaseURL}/assets/images/icon-filter-show.svg`
                    );
                    $("#menu_nodes").hide();
                    $("#conteudo").removeClass("col-md-9");
                    $("#conteudo").addClass("col-md-12");
                } else {
                    $(".img-expandir").attr(
                        "src",
                        `${BaseURL}/assets/images/icon-filter-hide.svg`
                    );
                    $("#menu_nodes").show();
                    $("#conteudo").css("margin-left", "0px");
                    $("#conteudo").removeClass("col-md-12");
                    $("#conteudo").addClass("col-md-9");
                }
            });

        });

        $(document).ready(async function() {

            let usuarioLogado = ''

            function verificarUsuarioLogado() {
                usuarioLogado = 'Suporte Interno' //'<?= $usuario->nome ?>'
            }

            // async function carregar_clientes_ativos() {
            // 	$('#q_clientes_ativos').html('<i class="fa fa-spinner fa-spin"></i>');
            // 	await $.ajax({
            // 		url: '<?php echo base_url("PosVenda/Gestao/carregar_clientes_ativos"); ?>', // URL do servidor
            // 		type: 'POST',
            // 		data: {
            // 			nome: usuarioLogado
            // 		},
            // 		success: function(response) {
            // 			response = JSON.parse(response)
            // 			if (response.status == 200) {
            // 				$('#q_clientes_ativos').html(response.resultado.quantidade);
            // 			} else {
            // 				showAlert("warning", "Falha ao carregar dados!");
            // 				$('#q_clientes_ativos').html('Falha ao carregar');
            // 			}
            // 		},
            // 		error: function(jqXHR, textStatus, errorThrown) {
            // 			showAlert("warning", "Falha ao carregar dados!");
            // 			$('#q_clientes_ativos').html('Falha ao carregar');
            // 		}
            // 	});
            // }

            async function carregar_clientes_ativos_select() {
                $('#q_clientes_ativos').html('<i class="fa fa-spinner fa-spin"></i>');
                const clientesSelect = $('#clientesSelect');
                await $.ajax({
                    url: '<?= site_url("PosVenda/Gestao/listar_clientes_por_analista") ?>',
                    type: 'POST',
                    data: {
                        nome: usuarioLogado
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        let quantidade_ativos = data?.resultado?.length;
                        $('#q_clientes_ativos').html(quantidade_ativos ? quantidade_ativos :
                            0);

                        if (data.status == 200 && quantidade_ativos > 0) {
                            clientesSelect.empty();
                            const options = data.resultado.map(cliente => ({
                                id: cliente.id,
                                text: cliente.nome
                            }));
                            clientesSelect.select2({
                                data: options,
                                width: "100%",
                                placeholder: 'Selecione um cliente',
                                allowClear: true
                            });
                        } else {
                            clientesSelect.empty();
                            clientesSelect.select2({
                                data: [],
                                width: "100%",
                                placeholder: 'Nenhum cliente encontrado',
                                allowClear: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        clientesSelect.empty();
                        clientesSelect.select2({
                            data: [],
                            placeholder: 'Falha ao carregar',
                            allowClear: true
                        });
                        $('#q_clientes_ativos').html('Falha ao carregar');
                    }
                });
            }

            async function carregar_clientes_saver() {
                $('#q_clientes_saver').html('<i class="fa fa-spinner fa-spin"></i>');
                await $.ajax({
                    url: '<?= site_url("PosVenda/Gestao/carregar_clientes_saver") ?>',
                    type: 'POST',
                    data: {
                        nome: usuarioLogado
                    },
                    success: function(data) {

                        data = JSON.parse(data)

                        let quantidade = data?.resultado?.quantidade

                        $('#q_clientes_saver').html(quantidade ? quantidade : 0)

                    },
                    error: function(xhr, status, error) {
                        $('#q_clientes_saver').html('Falha ao carregar');

                    }
                });
            }

            async function carregar_clientes_gestor() {
                $('#q_clientes_gestor').html('<i class="fa fa-spinner fa-spin"></i>');
                await $.ajax({
                    url: '<?= site_url("PosVenda/Gestao/carregar_clientes_gestor") ?>',
                    type: 'POST',
                    data: {
                        nome: usuarioLogado
                    },
                    success: function(data) {

                        data = JSON.parse(data)

                        let quantidade = data?.resultado?.quantidade

                        $('#q_clientes_gestor').html(quantidade ? quantidade : 0)

                    },
                    error: function(xhr, status, error) {
                        $('#q_clientes_saver').html('Falha ao carregar');

                    }
                });
            }

            async function carregar_nome_analista() {

                const analistaSelect = $('#analistaSelect');

                analistaSelect.empty();

                const option = $('<option></option>').val(usuarioLogado).text(usuarioLogado);

                analistaSelect.append(option);
            }

            async function contratos_ativos() {
                   $('#q_contratos_ativos').html('<i class="fa fa-spinner fa-spin"></i>');
                   $.ajax({
                      url: '<?= site_url("PosVenda/Gestao/contratos_ativos") ?>',
                      type: 'POST',
                      data: {
                          nome: usuarioLogado
                       },
                       dataType: 'json',
                       success: function(response) {
                           $('#q_contratos_ativos').html(response.resultado ? response.resultado.qtdTotal : 0);
                        },
                        error: function(xhr, status, error) {
                           $('#q_contratos_ativos').html('Falha ao carregar: ' + error);
                        }
                   });
                }

			async function contratos_ativos_com_comunicacao() {
					$('#q_contratos_com_comunicacao').html('<i class="fa fa-spinner fa-spin"></i>');
					await $.ajax({
						url: '<?= site_url("PosVenda/Gestao/contratos_ativos_com_comunicacao") ?>',
						type: 'POST',
						data: {
							nome: usuarioLogado
						},
						success: function(data) {

							data = JSON.parse(data)

							let quantidade = data?.resultado?.quantidade

							$('#q_contratos_com_comunicacao').html(quantidade ? quantidade : 0)

						},
						error: function(xhr, status, error) {
							$('#q_contratos_com_comunicacao').html('Falha ao carregar');

						}
					});
				}

			async function contratos_ativos_sem_comunicacao() {
					$('#q_contratos_sem_comunicacao').html('<i class="fa fa-spinner fa-spin"></i>');
					await $.ajax({
						url: '<?= site_url("PosVenda/Gestao/contratos_ativos_sem_comunicacao") ?>',
						type: 'POST',
						data: {
							nome: usuarioLogado
						},
						success: function(data) {

							data = JSON.parse(data)

							let quantidade = data?.resultado?.quantidade

							$('#q_contratos_sem_comunicacao').html(quantidade ? quantidade : 0)

						},
						error: function(xhr, status, error) {
							$('#q_contratos_sem_comunicacao').html('Falha ao carregar');

						}
					});
				}

			async function tickets_abertos() {
					$('#q_tickets_aberto').html('<i class="fa fa-spinner fa-spin"></i>');
					await $.ajax({
						url: '<?= site_url("PosVenda/Gestao/tickets_abertos") ?>',
						type: 'POST',
						data: {
							nome: usuarioLogado
						},
						success: function(data) {

							data = JSON.parse(data)

							let quantidade = data?.resultado?.quantidade

							$('#q_tickets_aberto').html(quantidade ? quantidade : 0)

						},
						error: function(xhr, status, error) {
							$('#q_tickets_aberto').html('Falha ao carregar');

						}
					});
				}
            
            async function tickets_abertos_dentro_sla() {
					$('#q_tickets_aberto_dentro').html('<i class="fa fa-spinner fa-spin"></i>');
					await $.ajax({
						url: '<?= site_url("PosVenda/Gestao/tickets_abertos_dentro_sla") ?>',
						type: 'POST',
						data: {
							nome: usuarioLogado
						},
						success: function(data) {

							data = JSON.parse(data)

							let quantidade = data?.resultado?.quantidade

							$('#q_tickets_aberto_dentro').html(quantidade ? quantidade : 0)

						},
						error: function(xhr, status, error) {
							$('#q_tickets_aberto_dentro').html('Falha ao carregar');

						}
					});
				}

             async function tickets_abertos_fora_sla() {
					$('#q_tickets_aberto_fora').html('<i class="fa fa-spinner fa-spin"></i>');
					await $.ajax({
						url: '<?= site_url("PosVenda/Gestao/tickets_abertos_fora_sla") ?>',
						type: 'POST',
						data: {
							nome: usuarioLogado
						},
						success: function(data) {

							data = JSON.parse(data)

							let quantidade = data?.resultado?.quantidade

							$('#q_tickets_aberto_fora').html(quantidade ? quantidade : 0)

						},
						error: function(xhr, status, error) {
							$('#q_tickets_aberto_fora').html('Falha ao carregar');

						}
					});
				}
                
            async function carregarClientesEmParalelo() {
                try {
                    await Promise.all([
                        carregar_nome_analista(),
                        carregar_clientes_ativos_select(),
                        //carregar_clientes_ativos(),
                        carregar_clientes_saver(),
                        carregar_clientes_gestor(),
                        contratos_ativos(),
						contratos_ativos_com_comunicacao(),
						contratos_ativos_sem_comunicacao(),
                        tickets_abertos(),
                        tickets_abertos_dentro_sla(),
                        tickets_abertos_fora_sla(),
                    ]);
                } catch (error) {}
            }

            verificarUsuarioLogado()
            await carregarClientesEmParalelo();

        })
        </script>
        </script>
