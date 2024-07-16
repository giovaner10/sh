<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("politicas") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('departamentos') ?> >
        <?= lang('controle_de_qualidade') ?> >
        <?= lang('politicas') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaData serial">
                        <label for="busca">Pesquisar:</label>
                        <input type="text" name="busca" class="form-control" placeholder="Digite a busca" id="busca" />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
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
                        <?php if ($this->auth->is_allowed_block('cad_politicasformularios')) : ?>
                            <button class="btn btn-primary" id="buttonNovaPolitica" onclick="formularioNovaPolitica();" style="height: 36.5px;"><?= lang("nova_politica") ?></button>
                        <?php endif; ?>
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

<input type="hidden" id="departamentoId" value="8">
<div id="modalPolitica"></div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/nexxera', 'layout.css') ?>">
<!-- <script type="text/javascript" src="<?= versionFile('assets/js/nexxera', 'Exportacoes.js') ?>"></script> -->
<script type="text/javascript" src="<?= versionFile('assets/js/departamentos/controle_qualidade', 'politicas.js') ?>"></script>

<script type="text/javascript">
    var Router = '<?= site_url('ControlesQualidades/Politicas') ?>';
    var BaseURL = '<?= base_url('') ?>';
    let rota = '<?= site_url('PoliticasFormularios/getPoliticasFormularios/P') ?>';
    let uploadUrl = '<?= base_url('uploads/politica_formulario') ?>';
    <?php if ($this->auth->is_allowed_block('cad_politicasformularios')) : ?>
        let temPermissao = true;
    <?php endif; ?>
    let identificadorModalPolitica = 'Politica';

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
            }
        );
    }

    function formularioEditarPolitica(politicaId) {
        ShowLoadingScreen();

        $("#modalPolitica").load(
            "<?= site_url('PoliticasFormularios/formularioPolitica') ?>/" + politicaId,
            function() {
                // Carregado
                $('#buttonEditarPolitica_' + politicaId).attr('disabled', false);
                HideLoadingScreen();
            }
        );
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
            .html('<i class="fa fa-spin fa-spinner"></i> Excluindo')
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
                    .html('Excluir')
                    .attr('disabled', false);
            }
        });
    }
</script>