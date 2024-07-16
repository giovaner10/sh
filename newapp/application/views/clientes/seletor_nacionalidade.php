<style>
    img#brasil {
        width: 80%;
    }

    img#eua {
        width: 75%;
    }

    .galeria img:hover {
        -webkit-transform: scale(1.3);
        -moz-transform: scale(1.3);
        -o-transform: scale(1.3);
        -ms-transform: scale(1.3);
        transform: scale(1.3);
    }
</style>

<div class="container" style="text-align: center;">
        <h1>Qual a Nacionalidade do Cliente?</h1>
        <div style="margin-top: 3%;">
            <div style="margin-left: 25%;" class="span3 galeria">
                <a href="<?= site_url('clientes/registro') ?>"><img id="brasil" title="Brasil" src="<?= base_url('media/img/icon_brazil.png') ?>"></a>
            </div>
            <div class="span3 galeria">
                <a href=""><img title="EM BREVE!" id="eua" src="<?= base_url('media/img/icon_eua.png') ?>"></a>
            </div>
        </div>
</div>