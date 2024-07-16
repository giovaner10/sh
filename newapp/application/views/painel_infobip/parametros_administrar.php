<div id="modalParametros" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 95%;">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
            </div>

            <div class="modal-body">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tabGruposFilas" data-toggle="tab"><?=lang("grupos_filas")?></a></li>
                    <li><a href="#tabStatusAtendimentosNaoAtribuidos" data-toggle="tab"><?=lang("status_atendimentos_nao_atribuidos")?></a></li>
                    <li><a href="#tabTempoPausas" data-toggle="tab"><?=lang("tempo_pausas")?></a></li>
                    <li><a href="#tabTempoMedioAtendimento" data-toggle="tab"><?=lang("tempo_medio_atendimentos")?></a></li>
                </ul>

                <div class="tab-content">
                    
                    <div class="tab-pane active" id="tabGruposFilas">
                        <?php $this->load->view('painel_infobip/parametros_filas_grupos');?>
                    </div>

                    <div class="tab-pane" id="tabStatusAtendimentosNaoAtribuidos">
                        <?php $this->load->view('painel_infobip/parametros_atendimentos_nao_atribuidos_status');?>
                    </div>

                    <div class="tab-pane" id="tabTempoPausas">
                        <?php $this->load->view('painel_infobip/parametros_tempo_pausas');?>
                    </div>

                    <div class="tab-pane" id="tabTempoMedioAtendimento">
                        <?php $this->load->view('painel_infobip/parametros_atendimentos_tempo_medio');?>
                    </div>

                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
            </div>

        </div>
    </div>
</div>

<!-- Load modais -->
<div id="divFilaGrupo"></div>
<div id="divAtendimentoNaoAtribuidoStatus"></div>
<div id="divTempoPausa"></div>
<div id="divAtendimentosTempoMedio"></div>

<!-- Modal de confirmacao de exclusao de grupos -->
<div id="modalFilaGrupoConfirmarExcluir" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    
    <input type="hidden" id="modalConfirmarFilaGrupoId">
    <div class="modal-dialog" role="document" style="width: 30%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=lang("modal_confirmacao_titulo")?></h4>
            </div>

            <div class="modal-body">
                <?=lang("confirmacao_exclusao_grupo_filas")?>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="excluirFilaGrupo()" id="buttonExcluirFilaGrupo" class="btn btn-warning"><?=lang("excluir")?></button>
                <button type="button" class="btn" data-dismiss="modal"><?=lang("cancelar")?></button>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalParametros").modal({backdrop: 'static' });
    });

    // Trabalha com o overflow para 2 modais
    $('#modalParametros').on('hidden.bs.modal', function(e){
        $("body").css('overflow-y', 'auto');
    });
    $('#modalParametros').on('show.bs.modal', function(e){
        $("body").css('overflow-y', 'hidden');
    });

</script>