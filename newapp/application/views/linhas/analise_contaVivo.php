<style type="text/css">
    #preloader {
        text-align: center;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
        z-index: 99;
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

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .ajuste-form {
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 400px;
        min-width: 400px;
        margin: 20px;
        margin-bottom: 150px;
    }

    .card-flex-menu {
        display: flex;
        flex-direction: row !important;
        justify-content: space-evenly;
        flex-wrap: wrap;
    }

    .btn {
        margin-top: 10px;
    }

    @media screen and (max-width: 1027px) {
        .ajuste-form {
            justify-content: flex-start;
        }

    }
    .text-title h3, .text-title h4{
        padding: 0 !important;
    }
</style>

<div id="preloader" style="display: none;">
    <div class="loader"></div>
    <h4 style="color: white">Aguarde... Isso pode levar alguns minutos</h4>
</div>

<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente(lang('fones_enderecos_corporativos'), site_url('Homes'), lang('a_empresa'), lang('contatos_corporativos'));
?>

<div class=" card-conteudo card-flex-menu" style="margin-left:15px;">

    <form action="<?= site_url('linhas/analise_contaVivo') ?>" method="POST" enctype="multipart/form-data" class="ajuste-form">
        <div>
            <h5>Importar Relatório - Contas VIVO</h5>
            <input type="file" name="conta" />
            <button class="btn btn-success" id="upload" type="submit"><i class="fa fa-cloud-upload"></i></button>
        </div>
        <i class="fa fa-upload" style="font-size:50px; margin-left: 15px;"></i>
    </form>

    <form action="<?= site_url('linhas/gerar_relContas') ?>" method="post" class="ajuste-form">
        <div>
            <h5>Gerar Detalhamento</h5>
            <select name="ref" class="form-control" id="sel1">
                <?php foreach ($referencias as $ref) : ?>
                    <option><?= $ref->referencia ?></option>
                <?php endforeach; ?>
            </select>
            <button id="gerar" type="submit" class="btn btn-success"> Gerar</button>
        </div>
        <i class="fa fa-file-archive-o" style="font-size:50px; margin-left: 15px;"></i>
    </form>

</div>
<script>
    $('#upload').click(function() {
        $('#preloader').removeAttr('style');
    });

    $('#gerar').click(function() {
        $('#preloader').removeAttr('style');
    });
</script>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">