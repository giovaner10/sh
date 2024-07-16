<h3><?=lang('administracao_pessoal')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <a href="<?=site_url('GentesGestoes/GentesGestoesInfoGerais')?>"><?=lang('gente_gestao')?></a> > 
    <?=lang('administracao_pessoal')?>
</div>

<!-- links -->
<div class="row div-btn-block-atalho">

    <!-- RH - Portal Colabordaor -->
    <div class="col-xl-offset-3 col-xl-3 col-lg-offset-2 col-lg-4 col-md-offset-1 col-md-5 col-sm-offset-0 col-sm-6">
        <a href="http://www.adpexpert.com.br/" target="_blank" class="btn btn-default btn-lg btn-block btn-block-atalho">
            <i class="material-icons-outlined">
                people
            </i>
            <span class="text">
                <?=lang("rh_portal_colaborador");?>
            </span>
        </a>
    </div>

    <!-- Bradesco Saúde -->
    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6">
        <a href="javascript::" onclick="formBradescoSaude()" class="btn btn-default btn-lg btn-block btn-block-atalho">
            <i class="material-icons">
                medical_services
            </i>
            <span class="text">
                <?=lang("bradesco_saude");?>
            </span>
        </a>
    </div>
    
</div>

<div id="divBradescoSaude"></div>

<div class="row ">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_cadastro_colaborador" data-toggle="tab"><?=lang("cadastro_colaborador");?></a></li>
            <li><a href="#tab_documentos_pendentes" data-toggle="tab"><?=lang("documentos_pendentes");?></a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_cadastro_colaborador">
                <?php $this->load->view("gente_gestao/administracao_pessoal/cadastro_colaborador");?>
            </div>
            <div class="tab-pane" id="tab_documentos_pendentes">
                <?php $this->load->view("gente_gestao/administracao_pessoal/documentos_pendentes", $documentosPendentes);?>
            </div>
        </div>
    </div>
</div>

<div id="divAdmDocumentosPendentes"></div>

<script>

    function formBradescoSaude()
    {
        $("#divBradescoSaude").load("<?=site_url('GentesGestoes/AdministracoesPessoais/bradescoSaude')?>");
    }

</script>