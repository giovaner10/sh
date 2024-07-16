<h3><?=lang('operacoes')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('operacoes')?> >
    <?=lang('informacoes_gerais')?>
</div>

<div class="row ">
    <div class="col-md-12">

        <!-- Departamento - Operações -->
        <input type="hidden" id="departamentoId" value="7">
        
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_contatos" data-toggle="tab"><?=lang('contatos')?></a></li>
            <li><a href="#tab_politicas" data-toggle="tab"><?=lang('politicas')?></a></li>
            <li><a href="#tab_formularios" data-toggle="tab"><?=lang('formularios')?></a></li>
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
        </div>
        
    </div>
</div>