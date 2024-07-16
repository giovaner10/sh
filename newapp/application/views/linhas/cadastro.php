<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("lista_de_chips") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('relatorios') ?> >
        <?= lang('lista_de_chips') ?>
    </h4>
</div>

<style>
    .select2 {
        width: 100% !important;
    }
</style>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container">
                        <label for="ano">Ano: <span class="text-danger">*</span></label>
                        <select class="form-control" name="ano" id="ano">
                            <option value="" selected disabled>Selecione o Ano</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="operadora">Operadora: <span class="text-danger">*</span></label>
                        <select class="form-control" name="operadora" id="operadora">
                            <option value="" selected disabled>Selecione a Operadora</option>
                            <option value="vivo">VIVO</option>
                            <option value="claro">CLARO</option>
                            <option value="vodaphone">VODAPHONE</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="empresa">Cliente: <span class="text-danger">*</span></label>
                        <select class="form-control" name="empresa" id="empresa">
                            <option value="" selected disabled>Selecione uma opção</option>
                            <option value="1">VINCULADA</option>
                            <option value="0">NÃO VINCULADA</option>
                        </select>
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-lista-chips" style='margin-bottom: 20px;'>
            <h3>
                <b><?= lang("lista_de_chips") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonChips" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_chips" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-chips" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageCentral" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageChips" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperChips">
                    <div id="tableChips" class="ag-theme-alpine my-grid-chips" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/Relatorios/listaChips', 'Chips.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/Relatorios/listaChips', 'Exportacoes.js') ?>"></script>

<script>
    var Router = '<?= site_url('linhas') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>