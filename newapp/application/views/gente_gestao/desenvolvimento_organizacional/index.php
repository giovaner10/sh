<h3><?=lang("desenvolvimento_organizacional")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <a href="<?=site_url('GentesGestoes/GentesGestoesInfoGerais')?>"><?=lang('gente_gestao')?></a> > 
    <?=lang('desenvolvimento_organizacional')?>
</div>

<div class="m-t-20">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_treinamentos_ead" data-toggle="tab"><?=lang("treinamentos_ead")?></a></li>
        <li><a href="#tab_meus_treinamentos" data-toggle="tab">
            <?= $this->auth->is_allowed_block('cad_atividades') ? lang("treinamentos") : lang("meus_treinamentos");?>
        </a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_treinamentos_ead">
            <?php $this->load->view("gente_gestao/desenvolvimento_organizacional/treinamentos_ead", $treinamentosEad);?>
        </div>
        <div class="tab-pane" id="tab_meus_treinamentos">
		    <?php $this->load->view("gente_gestao/desenvolvimento_organizacional/meus_treinamentos");?>
        </div>
    </div>
</div>

<!-- Administrar Treinamentos Ead -->
<div id="divAdmTreinamentosEad"></div>