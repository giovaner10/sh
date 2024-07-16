<h3><?=lang('gente_gestao')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('gente_gestao')?> >
    <?=lang('informacoes_gerais')?>
</div>

<?php if (
        $this->auth->is_allowed_block('vis_desenvolvimentoorganizacional') ||
        $this->auth->is_allowed_block('vis_administracaopessoal')
    ) : ?>

    <!-- links -->
    <div class="row div-btn-block-atalho">

        <!-- Desenvolvimento Organizacional -->
        <?php if ($this->auth->is_allowed_block('vis_desenvolvimentoorganizacional')) : ?>

            <div class="col-xl-offset-3 col-xl-3 col-lg-offset-2 col-lg-4 col-md-offset-1 col-md-5 col-sm-offset-0 col-sm-6">
                <a href="<?=site_url("GentesGestoes/DesenvolvimentosOrganizacionais")?>" class="btn btn-default btn-lg btn-block btn-block-atalho">
                    <i class="material-icons">
                        manage_accounts
                    </i>
                    <span class="text">
                        <?=lang("desenvolvimento_organizacional")?>
                    </span>
                </a>
            </div>

        <?php endif; ?>

        <!-- Administração de Pessoal -->
        <?php if ($this->auth->is_allowed_block('vis_administracaopessoal')) : ?>

            <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6">
                <a href="<?=site_url("GentesGestoes/AdministracoesPessoais")?>" class="btn btn-default btn-lg btn-block btn-block-atalho">
                    <i class="material-icons-round">
                        supervised_user_circle
                    </i>
                    <span class="text">

                        <?=lang("administracao_pessoal")?>
                    </span>
                </a>
            </div>
        
        <?php endif; ?>
        
    </div>

<?php endif; ?>


<div class="row ">
    <div class="col-md-12">
        <!-- Departamento - RH -->
        <input type="hidden" id="departamentoId" value="1">
        
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_contatos" data-toggle="tab"><?=lang('contatos')?></a></li>
            <li><a href="#tab_politicas" data-toggle="tab"><?=lang('politicas')?></a></li>
            <li><a href="#tab_formularios" data-toggle="tab"><?=lang('formularios')?></a></li>
            <li><a href="#tab_comunicados" data-toggle="tab"><?=lang('comunicados')?></a></li>
            <li><a href="#tab_parcerias" data-toggle="tab"><?=lang('parcerias')?></a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_contatos">
                <?php $this->load->view('empresa/contato_corporativo/contatos');?>
            </div>
            <div class="tab-pane" id="tab_politicas">
                <?php $this->load->view('politica_formulario/politicas');?>
            </div>
            <div class="tab-pane" id="tab_formularios">
                <?php $this->load->view('politica_formulario/formularios');?>
            </div>
            <div class="tab-pane" id="tab_comunicados">
                <?php $this->load->view('gente_gestao/informacao_geral/comunicados');?>
            </div>
            <div class="tab-pane" id="tab_parcerias">
                <?php $this->load->view('gente_gestao/informacao_geral/parcerias', $categoriasParcerias);?>
            </div>
        </div>
    </div>
</div>

<div id="modalAdministrarParcerias"></div>