<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("retencao") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('processos_atendimento') ?> >
        <?= lang('retencao') ?>
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
        <div class="card-conteudo card-retencao" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b><?= lang("retencao") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-md btn-primary" id='buttonNovaRetencao' onclick="formularioNovaRetencao()" style="height: 36.5px;"><i class="fa fa-plus" aria-hidden="true"></i> <?= lang("adicionar_retencao") ?></button>
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
                <select id="select-quantidade-por-pagina-retencao" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div class="wrapperRetencao">
                    <div id="tableRetencao" class="ag-theme-alpine my-grid-retencao" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var Router = '<?= site_url('ProcessosAtendimento/Retencao') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var uploadUrl = "<?= base_url('uploads/processos_atendimento_retencao') ?>";
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/ProcessosAtendimento/retencao', 'retencao.js') ?>"></script>

<div id="modalRetencao"></div>

<script type="text/javascript">
    let canEdit = "<?= $this->auth->is_allowed_block('edi_retencao_processos_atendimento') ? "true" : "false"; ?>";
    let canDel = "<?= $this->auth->is_allowed_block('del_retencao_processos_atendimento') ? "true" : "false"; ?>";
    let identificadorModalRetencao = 'Retencao';

    function formularioNovaRetencao() {
        $('#buttonNovaRetencao')
            .html('<i class="fa fa-spin fa-spinner"></i> <?= lang('carregando') ?>')
            .attr('disabled', true);

        $("#modalRetencao").load(
            "<?= site_url('ProcessosAtendimento/Retencao/formularioRetencao') ?>",
            function() {
                $('#buttonNovaRetencao')
                    .html('<i class="fa fa-plus" aria-hidden="true"></i> <?= lang('adicionar_retencao') ?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarRetencao(retencaoId) {
        ShowLoadingScreen();
        $('#buttonEditarRetencao_' + retencaoId).attr('disabled', true);

        $("#modalRetencao").load(
            "<?= site_url('ProcessosAtendimento/Retencao/formularioRetencao') ?>/" + retencaoId,
            function() {
                $('#buttonEditarRetencao_' + retencaoId).attr('disabled', false);
                HideLoadingScreen();
            }
        );
    }

    function excluirRetencao(retencaoId) {

        Swal.fire({
            title: "Atenção!",
            text: "Tem certeza que deseja excluir essa Retenção ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar",
            cancelButtonText: "Cancelar",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: '<?= site_url("ProcessosAtendimento/Retencao/excluirRetencao") ?>/' + retencaoId,
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {
                        ShowLoadingScreen();
                    },
                    success: function(retorno) {
                        if (retorno.status == 1) {
                            showAlert('success', retorno.mensagem)

                            getDados();
                        } else {
                            showAlert('warning', retorno.mensagem);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        showAlert('error', "Erro na solicitação ao servidor.")

                    },
                    complete: function() {
                        HideLoadingScreen();
                    }
                });

            }
        })
    }
</script>