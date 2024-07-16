<!-- Responsividade -->
<style>
  .open>.dropdown-menu {
    display: block;
  }

  @media (max-width: 600px) {
    .ag-paging-row-summary-panel {
      display: none;
    }

    .open>.dropdown-menu {
      display: inline-flex;
    }
  }
</style>
<div class="text-title">
  <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("autoajuda") ?></h3>
  <h4 style="padding: 0 20px; margin-left: 15px;">
    <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
    <?= lang('suporte') ?> >
    <a style="text-decoration: none" href="<?= site_url('/autoajuda') ?>"><?= lang("autoajuda") ?></a>
  </h4>
</div>

<div id="loading">
  <div class="loader"></div>
</div>

<div class="col-sm-12" id="conteudo">
  <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative;'>
    <h3>
      <b style="margin-bottom: 5px;"><?= lang("autoajuda") ?></b>
      <div class="btn-div-responsive">
        <div style="margin-right: 10px; margin-bottom: 5px;">
          <button class="btn btn-primary btn-cadastrar" id="btnCriarAutoajuda" type="button" style="height: 36.5px;">
            <?= lang('cadastrar') ?>
          </button>
        </div>
      </div>
    </h3>
    <div class="registrosDiv">
      <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
        <option value="10" selected>10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
    </div>
    <div style="position: relative;">
      <div class="wrapper">
        <div id="table" class="ag-theme-alpine my-grid" style="height: 530px">
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('servicos/autoajuda_cadastro_modal'); ?>
</div>
</div>
<style>
  h4.subtitle {
    padding: 10px 0px !important;
  }
</style>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
  var BaseURL = '<?= base_url('') ?>';
  let autoajudaData = [];
  var urlApiAutoajuda = <?= json_encode($this->url_api_autoajuda); ?>;
</script>


<script type="text/javascript" src="<?= versionFile('assets/js/suporte', 'autoajuda.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/suporte', 'cadastro_autoajuda.js') ?>"></script>