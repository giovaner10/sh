<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.js" integrity="sha512-yfb1lLOhiYYJh7C3dsBE4XGCnDCEe4dJ/jdVgoinVdKwVuDP2SJqrEngf0Q+m6gaU8vOjCaJ0EaeakGzXXfWIA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js" integrity="sha512-cktKDgjEiIkPVHYbn8bh/FEyYxmt4JDJJjOCu5/FQAkW4bc911XtKYValiyzBiJigjVEvrIAyQFEbRJZyDA1wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

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
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('listagem_de_dashboards') ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('BI') ?> >
        <?= lang('dashboards') ?> >
        <?= lang('listagem_de_dashboards') ?>
    </h4>
</div>

<?php if ($this->auth->is_allowed_block('cad_cadastrodashboard')) { ?>

    <div class="row" style="margin: 15px 0 0 15px;">
        <div class="col-md-12" id="conteudo">
            <div class="card-conteudo card-dados-gerenciamento" id="tableGRID" style='margin-bottom: 20px; position: relative;'>
                <h3 style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <b style="margin-top: 5px;">Dados:</b>
                    <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                        <div class="dropdown" style="width: 100px; margin-top: 5px;">
                            <button class="btn btn-gestor btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonListagemDashboards" data-toggle="dropdown" style="height: 36.5px;">
                                <?= lang('exportar') ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_listagem_dashboards" style="min-width: 100px; top: 62px; height: 91px;">
                            </div>
                        </div>
                        <button type="button" data-toggle="modal" data-target='#modal-dashboard' class="btn btn-gestor btn-primary dropdown-toggle" style="height: 36.5px; margin-right: 20px; margin-top: 5px;">Cadastrar Dashboard</button>
                        <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-top: 5px;" disabled>
                            <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                        </button>
                    </div>
                </h3>
                <div id="emptyMessage" class="emptyMessage" style="display: none;">
                    <h4><b>Nenhum dado a ser listado.</b></h4>
                </div>
                <div id="loadingMessage" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <select id="select-quantidade-por-pagina" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <input type="text" id="search-input" placeholder="Pesquisar" style="float: right; margin-top: 19px;">
                </div>
                <div class="wrapper-listagem-dashboards" style='margin-top: 20px;'>
                    <div id="table-listagem-dashboards" class="ag-theme-alpine my-grid-listagem-dashboards">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal cadatro/edição do dashboard -->
    <div id="modal-dashboard" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalDashboard" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 id="header-modal">Cadastrar Dashboard</h3>
                </div>

                <div class="modal-body">
                    <input id="id" type="hidden" />
                    <input id="id-menu" type="hidden" />

                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <label for="titulo">Título:</label>
                        <input id="titulo" class="form-control" type="text" />
                    </div>

                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <label for="link-bi">Link BI:</label>
                        <input id="link-bi" class="form-control" type="text" />
                    </div>

                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <label for="permissao">Permissão:</label>
                        <select id="permissao" class="form-control" type="text">
                            <option value="" disabled selected> Selecione uma permissão</option>
                        </select>
                    </div>

                    <div class="col-md-12" style="margin-bottom: 20px;">
                        <label for="ativo">Status:</label>
                        </br>
                        <label class="switch">
                            <input id="ativo" type="checkbox">
                            <span id="btn-switch" class="slider round"></span>
                        </label>
                    </div>
                </div>

                <hr style="margin-bottom: 10px">
                <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="margin-left: 20px;float: inline-start;">Fechar</button>
                    <button type="button" id="btn-salvar-dashboard" class="btn btn-submit btn-success" style="margin-right: 20px;float: inline-end;">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        //constante usada no JS para pegar o path do controller Dashboards
        const CAMINHO_CONTROLLER = '<?= site_url("Dashboards") ?>'
        var BaseURL = '<?= base_url('') ?>';
        var localeText = AG_GRID_LOCALE_PT_BR;

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
    <script type="text/javascript" src="<?php echo base_url('newAssets/js/dashboard.js') . "?v=" . filesize('newAssets/js/dashboard.js') ?>"></script>
<?php } else { ?>
    <h3 style="display: flex;justify-content: center;padding-top: 5%;">Você não tem permissão para visualizar esta página.</h3>
<?php } ?>