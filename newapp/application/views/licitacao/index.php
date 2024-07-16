<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">
<link href="<?php echo base_url('media') ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url('media') ?>/css/bootstrap-responsive.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>

<!-- MENSAGENS (ALERTS) -->
<?php if ($this->session->flashdata('sucesso')) {?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('sucesso'); ?>
    </div>
<?php } elseif ($this->session->flashdata('erro')) { ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('erro'); ?>
    </div>
<?php } ?>

<h3>Termo de Adesão</h3>

<!--<a href="--><?php //echo site_url('licitacao/termo_adesao') ?><!--" class="btn btn-primary">NOVO TERMO DE ADESÃO</a>-->
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">NOVO TERMO DE ADESÃO
        <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <li class="dropdown-submenu">
            <a>ShowTecnologia</a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo site_url('licitacao/termo_adesao') ?>?pf=2">Pessoa Jurídica</a></li>
                <li><a href="<?php echo site_url('licitacao/termo_adesao') ?>?pf=1">Pessoa Física</a></li>
            </ul>
        </li>

        <li class="divider"></li>

        <li class="dropdown-submenu">
            <a>SimM2m</a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo site_url('licitacao/termo_adesao_sim') ?>">Pessoa Jurídica</a></li>
                <li><a href="#">Pessoa Física</a></li>
            </ul>
        </li>

    </ul>
</div>

<div class="container-fluid" style="margin-top: 2%">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Show Tecnologia</a></li>
        <li><a data-toggle="tab" href="#menu1">SimM2m</a></li>
    </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <table id="tblTermoshow" class="table table-hover table-responsive table-bordered">
                <thead>
                <th class="span1">Id</th>
                <th>Razão Social</th>
                <th>cpf/cnpj</th>
                <th>Prestadora</th>
                <th>Admin</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="menu1" class="tab-pane fade">
            <table id="tblTermosim" class="table table-hover table-responsive table-bordered">
                <thead>
                <th class="span1">Id</th>
                <th>Razão Social</th>
                <th>cpf/cnpj</th>
                <th>Prestadora</th>
                <th>Admin</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // CARREGA DADOS DA SHOW
    $.getJSON('licitacao/get/1', function (data) {
        var dynatable = $('#tblTermoshow').dynatable({
            features: {
                paginate: true,
                sort: false,
                pushState: false,
                search: true,
                recordCount: true,
                perPageSelect: false
            },
            dataset: {
                records: data
            }
        }).data("dynatable");
        dynatable.settings.dataset.originalRecords =  data;
        dynatable.process();

    });

    // CARREGA DADOS DA SIMM2M
    $.getJSON('licitacao/get/2', function (data) {
        var dynatable = $('#tblTermosim').dynatable({
            features: {
                paginate: true,
                sort: false,
                pushState: false,
                search: true,
                recordCount: true,
                perPageSelect: false
            },
            dataset: {
                records: data
            }
        }).data("dynatable");
        dynatable.settings.dataset.originalRecords =  data;
        dynatable.process();

    });

    $(document).on('click', '#btn_getTermo', function () {
        window.open('licitacao/page_print/'+$(this).data('id'), '_blank');
    });

    $(document).on('click', '#btn_editTermo', function () {
        window.open('licitacao/edit/1/'+$(this).data('id'), '_self');
    });

    $(document).on('click', '#btn_editTermo_sim', function () {
        window.open('licitacao/edit/2/'+$(this).data('id'), '_self');
    });

    $(document).on('click', '#btn_getTermo_sim', function () {
        window.open('licitacao/page_print/'+$(this).data('id')+'/1', '_blank');
    });

    // $(document).on('click', '#btn_add_aditivo_sim', function () {
    //     window.open('licitacao/edit/2/'+$(this).data('id'), '_self');
    // });
    //
    // $(document).on('click', '#btn_add_aditivo', function () {
    //     window.open('licitacao/page_print/'+$(this).data('id')+'/1', '_blank');
    // });

</script>
