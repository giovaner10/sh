<h3><?=lang('meu_omnilink')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('marketing')?> >
    <?=lang('meu_omnilink')?>
</div>

<div class="row ">
    <div class="col-md-12">

        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_banners" data-toggle="tab">Banners</a></li>
            <li><a href="#tab_ultimas_noticias" data-toggle="tab"><?=lang('ultimas_noticias')?></a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_banners">
                <?php $this->load->view('marketing/meu_omnilink/banners/index');?>
            </div>
            <div class="tab-pane" id="tab_ultimas_noticias">
                <?php $this->load->view('marketing/meu_omnilink/ultimas_noticias/index');?>
            </div>
        </div>
        
    </div>
</div>
