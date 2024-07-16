<?php if ($this->auth->is_allowed_block('cad_treinamentos')) : ?>

    <button type="button" id="buttonAdmTreinamentosEad" onclick="administrarTreinamentosEad()" class="btn btn-primary btn-lg"
        style="margin-bottom: 15px;">
        <?=lang("administrar_treinamentos")?>
    </button>

<?php endif; ?>

<p class="titulo-tipo-treinamento-ead"><?=lang("treinamentos_online")?></p>

<!-- Treinamentos -->
<div class="row">
    <?php foreach ($treinamentosEad["treinamentos"] as $treinamento) :?>
        <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
            <a href="<?=$treinamento->link?>" target="_blank">
                <img src='<?=base_url("uploads/gente_gestao/desenv_organizagional/treinamentos/$treinamento->file")?>'
                    class="img-treinamento-ead img-responsive img-rounded" alt="">
                <p class="titulo-treinamento-ead">
                    <?=strlen($treinamento->descricao) > 30 ? substr($treinamento->descricao, 0, 30) . '...' : $treinamento->descricao; ?>
                </p>
            </a>
        </div>
    <?php endforeach?>
</div>
<!-- ------------ -->

<hr>

<p class="titulo-tipo-treinamento-ead"><?=lang("videoaulas")?></p>

<!-- Videoaulas -->
<div class="row">
    <?php foreach ($treinamentosEad["videoaulas"] as $videoaula) :?>
        <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
            <a href="<?=$videoaula->link?>" target="_blank">
                <img src='<?=base_url("uploads/gente_gestao/desenv_organizagional/treinamentos/$videoaula->file")?>'
                    class="img-treinamento-ead img-responsive img-rounded" alt="">
                <p class="titulo-treinamento-ead">
                    <?=strlen($videoaula->descricao) > 30 ? substr($videoaula->descricao, 0, 30) . '...' : $videoaula->descricao; ?>
                </p>
            </a>
        </div>
    <?php endforeach?>
</div>

<hr>

<p class="titulo-tipo-treinamento-ead"><?=lang("sites")?></p>

<!-- Sites -->
<div class="row m-t-20">
    <div class="col-md-6">
        <?php foreach ($treinamentosEad["sites"] as $site) :?>
            <a href="<?=$site->link?>" target="_blank" class="titulo-site-treinamento-ead">
                <?=$site->descricao?>
            </a>
            <hr class="hr-site-treinamento-ead">
        <?php endforeach?>
    </div>
</div>

<script>

    function administrarTreinamentosEad()
    {
        // Carregando
        $('#buttonAdmTreinamentosEad')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#divAdmTreinamentosEad").load(
            "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/administrarTreinamentosEad')?>",
            function()
            {
                // Carregado
                $('#buttonAdmTreinamentosEad')
                    .html('<?=lang("administrar_treinamentos")?>')
                    .attr('disabled', false);
            }
        );
    }

</script>


<style>
    .titulo-tipo-treinamento-ead {
        font-weight: bold;
        color: #666;
    }

    .img-responsive {
        margin: 0 auto;
    }

    .img-treinamento-ead {
        width: 250px;
        height: 150px;
        border-radius: 20px;
        margin-top: 20px;
    }

    .titulo-treinamento-ead {
        margin-top: 4px;
        color: #666;
        text-align: center;
    }

    .titulo-site-treinamento-ead, .titulo-site-treinamento-ead:hover {
        color: #03A9F4;
        margin-top: 20px;
    }

    .hr-site-treinamento-ead {
        margin-top: 6px;
        margin-bottom: 15px;
    }
</style>