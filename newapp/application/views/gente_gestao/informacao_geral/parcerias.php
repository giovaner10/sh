<?php if ($this->auth->is_allowed_block('cad_parcerias')) : ?>

    <button type="button" id="buttonAdministrarParcerias" onclick="administrarParcerias()" class="btn btn-primary btn-lg"
        style="margin-bottom: 15px;">
        <?=lang("administrar_parcerias")?>
    </button>

<?php endif; ?>

<!-- Parcerias -->
<?php foreach ($categoriasParcerias as $categoria) :?>

    <p class="titulo-tipo-parceria"><?=$categoria["categoriaNome"]?></p>

    <div class="row">
        <?php foreach ($categoria["parcerias"] as $parcerias) :?>

            <div class="col-md-2">
                <a href="<?=$parcerias->link?>" target="_blank">
                    <img src='<?=base_url("uploads/gente_gestao/desenv_organizagional/parcerias/$parcerias->file")?>'
                        class="img-parceria img-responsive img-rounded" alt="">
                </a>
                <p class="titulo-parceria"><?=nl2br($parcerias->descricao)?></p>
            </div>

        <?php endforeach;?>
    </div>
    <hr>

<?php endforeach;?>

<!-- ------------ -->

<script>

    function administrarParcerias()
    {
        // Carregando
        $('#buttonAdministrarParcerias')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#modalAdministrarParcerias").load(
            "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/administrarParcerias')?>",
            function()
            {
                // Carregado
                $('#buttonAdministrarParcerias')
                    .html('<?=lang("administrar_parcerias")?>')
                    .attr('disabled', false);
            }
        );
    }

</script>


<style>
    .titulo-tipo-parceria {
        font-weight: bold;
        color: #666;
    }

    .img-responsive {
        margin: 0 auto;
    }

    .img-parceria {
        width: 150px;
        height: 150px;
    }

    .titulo-parceria {
        margin-top: -20px;
        color: #666;
        text-align: center;
    }

</style>