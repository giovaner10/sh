<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Gestão Pós Venda", site_url('Homes'), "Pós Venda", "Clientes Ativos");
?>


<div class="col-md-12">

    <div class="card-conteudo card-cadastro-clientes" style="margin-bottom: 20px;">

        <div class="alert alert-info col-md-12 tab-acoes-pesquisa">
            <div class="row" style="display: flex; align-items: center; padding: 15px; flex-wrap: wrap;">

                <div>
                    <label id="id-label-select">Pesquisar por ID, Nome ou documento: </label> <br>
                    <select class="form-control clientesSelect" id="clientesSelect" name="clientesSelect" type="text" style="width: 380px;">
                        <option value="">Carregando...</option>
                    </select>
                </div>


                <button class="btn btn-success" style="width: 150px; margin-left: 20px; margin-top: 21px;" id="pesquisacliente" type="button"><i class="fa fa-search"></i> Pesquisar</button>

                <button class="btn btn-default" style='width: 150px; margin-left: 20px;  margin-right: 20px; margin-top: 21px;' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

            </div>
        </div>

        <div class="pos-venda-container-sec" id="pos-venda-container-sec" style="display: none;">

            <div class="pos-venda-container" style="padding: 0;">

                <ul class="nav nav-tabs">
                    <li style=" margin-top: 3px; "  class="active"><a href="#tabAtividadesServico" data-toggle="tab">Atividades de Serviço</a></li>
                    <li style=" margin-top: 3px; "><a href="#tabBaseInstalada" data-toggle="tab">Base Instalada</a></li>
                    <li style=" margin-top: 3px; "><a href="#tabProvidencias" data-toggle="tab">Providências</a></li>
                    <li style=" margin-top: 3px; "><a href="#tabOcorrencias" data-toggle="tab">Ocorrências</a></li>
                    <li style=" margin-top: 3px; "><a href="#tabUsuario" data-toggle="tab">Usuário</a></li>
                    <li style=" margin-top: 3px; "><a href="#tabVeiculos" data-toggle="tab">Veículos</a></li>
                    <li style=" margin-top: 3px; "><a href="#tabVeiculosEspelhados" data-toggle="tab">Veículos Espelhados</a></li>
                    <li style=" margin-top: 3px; "><a href="#tabEquipamentos" data-toggle="tab">Equipamentos</a></li>
                    <li style=" margin-top: 3px; "><button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#clienteModal">Ver Dados do Cliente</button></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="tabOcorrencias"></div>
                    <div class="tab-pane active" id="tabAtividadesServico"></div>
                    <div class="tab-pane" id="tabProvidencias"></div>
                    <div class="tab-pane" id="tabBaseInstalada"></div>
                    <div class="tab-pane" id="tabUsuario"></div>
                    <div class="tab-pane" id="tabVeiculos"></div>
                    <div class="tab-pane" id="tabVeiculosEspelhados"></div>
                    <div class="tab-pane" id="tabEquipamentos"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="clienteModalLabel">Dados do Cliente</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="pos-venda-container">
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Informações Básicas</div>
                            <div class="pos-venda-info-group">
                                <div class="pos-venda-info"><strong>ID:</strong> <span id="id"></span></div>
                                <div class="pos-venda-info"><strong>Status:</strong> <span id="status"></span></div>
                                <div class="pos-venda-info"><strong>Situação:</strong> <span id="situacao"></span></div>
                                <div class="pos-venda-info"><strong>Nome:</strong> <span id="nome"></span></div>
                                <div class="pos-venda-info"><strong>Razão Social:</strong> <span id="razaoSocial"></span></div>
                                <div class="pos-venda-info"><strong>Inscrição Estadual:</strong> <span id="inscricaoEstadual"></span></div>
                                <div class="pos-venda-info"><strong>CNPJ:</strong> <span id="cnpj"></span></div>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Contato</div>
                            <div class="pos-venda-info-group">
                                <div class="pos-venda-info"><strong>Usuário:</strong> <span id="usuario"></span></div>
                                <div class="pos-venda-info"><strong>E-mail:</strong> <span id="email"></span></div>
                                <div class="pos-venda-info"><strong>Telefone:</strong> <span id="fone"></span></div>
                                <div class="pos-venda-info"><strong>Celular:</strong> <span id="cel"></span></div>
                                <div class="pos-venda-info"><strong>Tipo:</strong> <span id="tipoRelacao"></span></div>
                                <div class="pos-venda-info"><strong>Origem:</strong> <span id="origemCliente"></span></div>
                                <div class="pos-venda-info"><strong>Cadastrado em:</strong> <span id="dataCadastro"></span></div>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Endereço</div>
                            <div class="pos-venda-info-group">
                                <div class="pos-venda-info"><strong>Endereço:</strong> <span id="endereco"></span></div>
                                <div class="pos-venda-info"><strong>Bairro:</strong> <span id="bairro"></span></div>
                                <div class="pos-venda-info"><strong>Número:</strong> <span id="numero"></span></div>
                                <div class="pos-venda-info"><strong>Cidade:</strong> <span id="cidade"></span></div>
                                <div class="pos-venda-info"><strong>Estado:</strong> <span id="uf"></span></div>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Outras Informações</div>
                            <div class="pos-venda-info-group">
                                <div class="pos-venda-info"><strong>Empresa:</strong> <span id="empresa"></span></div>
                                <div class="pos-venda-info"><strong>Vendedor:</strong> <span id="vendedor"></span></div>
                                <div class="pos-venda-info"><strong>Orgão:</strong> <span id="orgao"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="loading">
    <div class="loader"></div>
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>

<script>
    const URL_PAINEL_OMNILINK = '<?= site_url("PaineisOmnilink") ?>';
    var RouterWebdesk = '<?= site_url('webdesk') ?>';
    const URL_API = '<?= $this->config->item("base_url_api_crmintegration") . "/crmintegration/api/" ?>';
    var RouterUsuariosGestor = '<?= site_url('usuarios_gestor') ?>';



    function ShowLoadingScreen() {
        $('#loading').show()
    }

    function HideLoadingScreen() {
        $('#loading').hide()
    }

    const usuarioLogado = 'Suporte Interno'

    function loadTabContent(tabId) {
        if ($("#" + tabId).is(":empty")) {
            $.ajax({
                url: Router + '/getTabContent/?tabName=' + tabId,
                method: "GET",
                success: function(response) {
                    $("#" + tabId).html(response);
                },
                error: function() {
                    $("#" + tabId).html("<p>Erro ao carregar o conteúdo.</p>");
                },
            });
        }
    }

    $(".nav-tabs a").on("click", function(e) {
        e.preventDefault();
        var tabId = $(this).attr("href").substring(1);
        loadTabContent(tabId);
        $(this).tab("show");
    });


    let cliente_selecionado_atual = null;
    var Router = '<?= site_url('PosVenda/Gestao') ?>';

    async function carregar_clientes_ativos_select() {
        const clientesSelect = $("#clientesSelect");
        await $.ajax({
            url: Router + "/listar_clientes_por_analista",
            type: "POST",
            data: {
                nome: usuarioLogado,
            },
            success: function(data) {
                data = JSON.parse(data);
                let quantidade_ativos = data?.resultado?.length;
                if (data.status == 200 && quantidade_ativos > 0) {
                    clientesSelect.empty();
                    $.each(data.resultado, function(index, cliente) {
                        const option = $("<option></option>")
                            .val(cliente.id)
                            .text(
                                `${cliente.id} - ${
                  cliente.nome ? cliente.nome : "Nome não Informado"
                } - ${cliente.cnpj ? cliente.cnpj : cliente.cpf}`
                            );
                        clientesSelect.append(option);
                    });
                } else {
                    clientesSelect.empty();
                    const option = $("<option></option>").val("").text("");
                    clientesSelect.append(option);
                }
                clientesSelect.select2({
                    placeholder: "Selecione um Cliente",
                    allowClear: true,
                    width: "380px",
                });
            },
            error: function(xhr, status, error) {
                const option = $("<option></option>").val("").text("");
                clientesSelect.append(option);
                clientesSelect.select2({
                    placeholder: "Select a client",
                    allowClear: true,
                    width: "380px",
                });
            },
        });
    }

    $(document).ready(function() {
        $('#clientesSelect').select2();
    });
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/posvendas/ativos', 'clientes_ativos.js') ?>"></script>

<style>
    .pos-venda-container {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        width: 100%;
        min-width: 450px;
    }

    .pos-venda-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .pos-venda-section {
        margin-bottom: 20px;
    }

    .pos-venda-section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        padding-bottom: 5px;
    }

    .pos-venda-title,
    .pos-venda-section-title {
        color: #1C69AD;
    }

    .pos-venda-info-group {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .pos-venda-info {
        flex: 1 1 50%;
        margin: 5px 0;
        min-width: 230px;
    }

    .pos-venda-info strong {
        font-weight: bold;
        color: #1C69AD;
    }

    .pos-venda-info:before {
        content: '';
    }


    .pos-venda-container-sec {
        display: flex;
        flex-direction: column;
        width: 100%;
        justify-content: space-between;
    }

    .modal .modal-header .modal-title {
        color: #1C69AD;
        font-size: 22px;
        text-align: left;
    }

    .modal .modal-header {
        padding-bottom: 0;
        height: 60px;
    }

    .modal-footer {
        padding: 20px !important;
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


    .modal-dialog {
        border-radius: 25px !important;
    }
</style>
<script type="text/javascript" src="<?= versionFile('assets/js/posvendas', 'exportacoes.js') ?>"></script>