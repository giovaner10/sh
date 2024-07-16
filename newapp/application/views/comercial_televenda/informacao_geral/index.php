<h3><?=lang('comercial_e_televendas')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('comercial_e_televendas')?> >
    <?=lang('informacoes_gerais')?>
</div>

<?php if (
        $this->auth->is_allowed_block('vis_tabeladeprecos') ||
        $this->auth->is_allowed_block('vis_imagensparawhatsapp')
    ) : ?>

    <!-- links -->
    <div class="div-btn-block-atalho">

        <!-- btn edição de atalhos -->
        <?php if ($this->auth->is_allowed_block('edi_atalhoscomercialetelevendas')) : ?>

            <div class="row">
                <div class="col-md-12" style="margin: 0 0 -30px 0;">
                    <button class="btn btn-primary" id="btnEditarAtalhos" onclick="formularioEditarAtalhos()" style="float: right;
                        position: relative; top: -25px !important; padding: 0px 5px 3px 6px; border-radius: 9px !important;">
                        <i class="fa fa-pencil"></i>
                    </button>
                </div>
            </div>

        <?php endif; ?>

        <div class="row">

            <!-- Tabela de preços -->
            <?php if ($this->auth->is_allowed_block('vis_tabeladeprecos')) : ?>

                <div class="col-xl-offset-3 col-xl-3 col-lg-offset-2 col-lg-4 col-md-offset-1 col-md-5 col-sm-offset-0 col-sm-6">
                    <a href="<?=isset($atalhos[0]->url) ? $atalhos[0]->url : ''?>" class="btn btn-default btn-lg btn-block btn-block-atalho"
                        target="_blank" id="atalhoTabelaPrecos">
                        <i class="material-icons">
                            price_change
                        </i>
                        <span class="text">
                            <?=lang("tabela_de_precos")?>
                        </span>
                    </a>
                </div>

            <?php endif; ?>

            <!-- Imagens para WhatsApp -->
            <?php if ($this->auth->is_allowed_block('vis_imagensparawhatsapp')) : ?>

                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6">
                    <a href="<?=isset($atalhos[1]->url) ? $atalhos[1]->url : ''?>" class="btn btn-default btn-lg btn-block btn-block-atalho"
                        target="_blank" id="atalhoImagensParaWhatsapp">
                        <i class="material-icons-round">
                            crop_original
                        </i>
                        <span class="text">
                            <?=lang("imagens_para_whatsapp")?>
                        </span>
                    </a>
                </div>
            
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>


<div class="row ">
    <div class="col-md-12">
        
        <!-- Departamento - Comercial e Televendas -->
        <input type="hidden" id="departamentoId" value="5">
        
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_contatos" data-toggle="tab"><?=lang('contatos')?></a></li>
            <li><a href="#tab_politicas" data-toggle="tab"><?=lang('politicas')?></a></li>
            <li><a href="#tab_formularios" data-toggle="tab"><?=lang('formularios')?></a></li>
            <li><a href="#tab_dicas_vendas" data-toggle="tab"><?=lang('dicas_de_vendas')?></a></li>
            <li><a href="#tab_regulamentos_campanha_vendas" data-toggle="tab"><?=lang('regulamentos_de_campanha_de_vendas')?></a></li>
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
            <div class="tab-pane" id="tab_dicas_vendas">
                <?php $this->load->view('comercial_televenda/informacao_geral/dicas_vendas');?>
            </div>
            <div class="tab-pane" id="tab_regulamentos_campanha_vendas">
                <?php $this->load->view('comercial_televenda/informacao_geral/regulamentos_campanha_vendas');?>
            </div>
        </div>
    </div>
</div>

<div id="divModalAtalhos"></div>

<script>
    function formularioEditarAtalhos()
    {
        // Carregando
        $('#btnEditarAtalhos')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalAtalhos").load(
            "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/formularioAtalhosComercialTelevendas')?>",
            function()
            {
                // Carregado
                $('#btnEditarAtalhos')
                    .html('<i class="fa fa-pencil"></i>')
                    .attr('disabled', false);
            }
        );
    }
</script>