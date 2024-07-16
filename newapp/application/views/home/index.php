<link rel="stylesheet" type="text/css" href="<?=versionFile('media/css', 'home_NS.css') ?>">

<h3 style="padding: 0 20px;"><?=lang("bem_vindo_ao_shownet")?></h3>

<div id="acessoRapidoDiv"></div>

<div class="row" style="margin: 15px 0 0 0;">

    <!-- Acesso rapido -->
    <div class="col-md-6" style="padding: 0px 37px;">
        <div class="row" style="margin-bottom: 15px;">
            <h4><?=lang('acesso_rapido')?>
                <span style="float: right;">
                    <button class="btn btn-primary" id="acessoRapidoBotao" style="position: relative;
                        top: -2px; margin-right: 15px; padding: 0px 5px 3px 6px; margin-bottom: -7px;">
                        <i class="fa fa-pencil"></i>
                    </button>
                </span>
            </h4>
        </div>

        <div id="divDashboardAtalhosUsuario">
            <?php foreach (array_chunk($atalhosUsuario, 3) as $atalhosLinha) : ?>

                <div class="row" style="margin-bottom: 15px; margin-left: -30px;">

                    <?php foreach ($atalhosLinha as $atalho): ?>

                        <div class="col-sm-4 col-md-4">

                            <a href="<?=$atalho->menuCaminho?>" class="btn btn-default btn-lg btn-block btn-atalho"
                                data-toggle="tooltip" title="<?=$atalho->menuNomeCompleto?>">
                                <i class="material-icons material-icons-atalho">
                                    <?=$atalho->menuIcone?>
                                </i>

                                <?php if (count(explode(' ', $atalho->menuNome)) > 2) : ?>

                                    <span class="text-button-atalho">
                                        <?php 
                                            $menuNomeArray = explode(' ', $atalho->menuNome);
                                            echo "$menuNomeArray[0] $menuNomeArray[1]...";
                                        ?>
                                    </span>

                                <?php else : ?>

                                    <span class="text-button-atalho">
                                        <?= strlen($atalho->menuNome) > 30
                                            ? substr($atalho->menuNome, 0, 30) . '...'
                                            : $atalho->menuNome
                                        ?>
                                    </span>

                                <?php endif;?>

                            </a>

                        </div>

                    <?php endforeach; ?>
                    
                </div>

            <?php endforeach; ?>
        </div>

        <hr style="margin: 15px -20px 20px -30px;">
    </div>

    <!-- Banners -->
    <div class="col-md-6" style="padding: 0px 37px;">
        <div class="row" style="margin-right: -30px;">

            <?php if (empty($banners) || count($banners) == 0) : ?>
                <img src="<?=base_url('media/img/work.png')?>" class="img-responsive" style="width: 600px; height:194.1px">
                <hr style="margin: 30px -30px 20px -15px;">
            <?php else : ?>

                <div class="carousel slide quote-carousel" data-ride="carousel" id="quote-carousel">

                    <!-- Bottom Carousel Indicators -->
                    <ol class="carousel-indicators">
                        <?php foreach ($banners as $indice => $banner) : ?>
                            <li data-target="#quote-carousel" data-slide-to="<?=$indice?>" class="<?=$indice == 0 ? 'active' : ''?>"></li>
                        <?php endforeach; ?>
                    </ol>
                
                    <!-- Carousel Slides / Quotes -->
                    <div class="carousel-inner">
                        
                        <!-- Quotes -->
                        <?php foreach ($banners as $indice => $banner) : ?>
                            <div class="item <?=$indice == 0 ? 'active' : ''?>">
                                <img class="img-responsive" src="<?= base_url("uploads/banners/$banner->file"); ?>" style="width: 600px; height:194.1px">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Carousel Buttons Next/Prev -->
                    <a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
                    <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
                </div>

                <hr style="margin: 0px 0px 20px 0px;">
            <?php endif;?>

        </div>
    </div>
</div>

<div class="row">

    <!-- Comunicados -->
    <div class="col-md-6" style="padding: 0px 37px;">
        <h4><?=lang('comunicados')?></h4>

        <!-- Lista de comunicados -->
        <div style="margin-top: 10px; padding:5px; height: 170px" class="div-scroll">

            <?php foreach ($comunicados as $indice => $comunicado) : ?>
                <p>
                    <a href="<?=base_url('uploads/comunicados').'/'.$comunicado->file?>" target="_blank">
                        <?=$comunicado->comunicado?>
                    </a>
                    <span style="float: right;"><?=dataFormatar($comunicado->data)?></span>
                </p>
                
                <hr style="margin: 0 0 10px;">
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Aparecer na tela o carousel apenas quando tiver aniversariante -->
    <?php if (count($aniversariantes) >= 1) : ?>
        <!-- Aniversariantes -->
        <div class="col-md-6" style="padding: 0px 37px;">
        
            <h4 style="margin-bottom: 20px;">
                <?=lang('aniversariantes')?>
                <span class="title-primary" style="float: right;"><?=date("d/m/Y");?></span>
            </h4>

            <div class="carousel slide quote-carousel" id="quote-carousel-aniversariantes" data-interval="false">

                <!-- Bottom Carousel Indicators -->
                <?php if (count($aniversariantes) < 25) : ?>
                    
                    <ol class="carousel-indicators carousel-indicators-aniversariantes">

                        <?php foreach ($aniversariantes as $indice => $aniversarianteGrupo) : ?>
                            <li data-target="#quote-carousel-aniversariantes" data-slide-to="<?=$indice?>" class="<?=$indice == 0 ? 'active' : ''?>" style="margin-bottom: -15px !important;"></li>
                        <?php endforeach; ?>

                    </ol>
                
                <?php endif; ?>
                        
                <!-- Carousel Slides / Quotes -->
                <div class="carousel-inner">
                            
                    <!-- Quotes -->
                    <?php $i = 0;
                        foreach ($aniversariantes as $aniversarianteGrupo) : ?>

                        <div class="row item <?= $i == 0 ? 'active' : ''?>">

                            <?php foreach ($aniversarianteGrupo as $aniversariante) : ?>

                                <div class="col-sm-4 col-md-4">
                                    <img src="<?=base_url('media/img/perfil-usuarios/perfil-sem-foto.png')?>" class="img-circle img-responsive" alt="Cinque Terre" style="width:50%">
                                    <div style="margin-top: 10px; text-align: center;">
                                        <p>
                                            <span class="title-primary"><?=$aniversariante->nome?></span>
                                            <br>
                                            <?=$aniversariante->ocupacao?>
                                        </p>
                                    </div>
                                </div>
            
                            <?php $i++; endforeach; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

                <!-- Carousel Buttons Next/Prev -->
                <a data-slide="prev" href="#quote-carousel-aniversariantes" class="left carousel-control carousel-control-aniversariantes"><i class="fa fa-chevron-left"></i></a>
                <a data-slide="next" href="#quote-carousel-aniversariantes" class="right carousel-control carousel-control-aniversariantes"><i class="fa fa-chevron-right"></i></a>
            </div>     
        </div>
    <?php endif; ?>
    <!-- Release Notes -->
    <div class="col-md-6" style="padding: 0px 37px;">
        <h4><?=lang('release_notes')?></h4>

        <!-- Lista de Release notes -->
        <div style="margin-top: 10px; padding:5px; height: 170px" class="div-scroll">
                    
            <?php foreach ($releases as $indice => $release) : ?>
                <p>
                    <a href="<?=base_url('uploads/release_notes').'/'.$release->file?>" target="_blank">
                        <?=$release->release_note?>
                    </a>
                    <span style="float: right;"><?=dataFormatar($release->data)?></span>
                </p>
                
                <hr style="margin: 0 0 10px;">
            <?php endforeach; ?>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#acessoRapidoBotao').on('click', function()
    {
        $("#acessoRapidoBotao")
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        $("#acessoRapidoDiv").load(
            "<?=site_url('Homes/acessoRapidoModal')?>",
            {},
            function()
            {
                $("#acessoRapidoBotao")
                    .html('<i class="fa fa-pencil"></i>')
                    .attr('disabled', false);
            }
        );
    });

</script>
