<!-- Reporta erro -->
<?php if ($status == 0) :?>
    <div class="alert alert-warning" role="alert">
        <?=$mensagem?>
    </div>
<?php else: ?>

    <div id="sortable" class="row" style="margin-top: 20px;">

        <!-- Fila de atendimentos não atribuídos -->
        <div class="col-md-4" id="div-unsortable">
            <div class="panel panel-primary">

                <div class="panel-heading unsortable">
                    <!-- Título -->
                    <?=lang('atendimentos_nao_atribuidos')?>
                    <!-- QTD Atendimentos Não Atribuídos -->
                    <span class="icone-contador"><?=count($dados["atendimentosNaoAtribuidos"])?></span>
                </div>

                <!-- Conteúdo -->
                <div class="panel-body div-scroll" style="height: 200px; overflow-y: scroll;">

                    <?php foreach ($dados["atendimentosNaoAtribuidos"] as $indice => $atendimento) :?>
                        
                        <!-- Farol status por tempo de espera -->
                        <span class="icone-status-farol background-color-<?=$atendimento->farolStatusClasse?>"></span>
                        <!-- Conteúdo -->
                        <p>
                            <?=
                                $atendimento->channel." - ".
                                $dados["filas"][$atendimento->queueId]->name." | ".
                                $atendimento->started." | ".
                                $atendimento->client
                            ?>
                        </p>
                        <?php if (end(array_keys($dados["atendimentosNaoAtribuidos"])) != $indice) : ?>
                            <hr style="margin: 0 0 10px;">
                        <?php endif; ?>
                        
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
        <!-- ----------------------------------- -->
        
        <!-- Agentes atribuídos por fila -->
        <?php foreach ($dados["filasAtendimentosAgentes"] as $filaId => $agentes) :?>

            <div class="col-md-4 sortable-panels-ids" id="<?=$filaId?>">
                <div class="panel panel-primary">

                    <div class="panel-heading">
                        <!-- Icone Fila -->
                        <span class="material-icons-outlined icone-fila-tipo-canal">
                            <?=$dados["filas"][$filaId]->icone?>
                        </span>
                        <!-- Título -->
                        <?=$dados["filas"][$filaId]->name?>
                        <!-- QTD Atendentes atribuídos na fila -->
                        <span class="icone-contador"><?=count($agentes)?></span>
                    </div>

                    <!-- Conteúdo -->
                    <div class="panel-body div-scroll" style="height: 200px; overflow-y: scroll;">

                        <?php foreach ($agentes as $indice => $agente) :?>

                            <!-- Farol estouro de pausa -->
                            <?php if ($agente->estouroPausa) : ?>
                                <span class="icone-status-farol-filas background-color-danger"></span>
                            <?php endif; ?>

                            <!-- Icone Disponibilidade -->
                            <span class="material-icons icone-disponibilidade">
                                <?=$agente->iconeDisponibilidade?>
                            </span>
                            
                            <!-- Se o tempo de atendimento for superior a 10 minutos o texto fica em vermelho -->
                            <p class="<?=$agente->textoVermelho ? "color-danger" : "" ?>">
                                <?=$agente->displayName." | ".
                                $agente->disponibilidade?>
                            </p>

                            <?php if (end(array_keys($agentes)) != $indice) : ?>
                                <hr style="margin: 0 0 10px;">
                            <?php endif; ?>

                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
            
        <?php endforeach; ?>
        <!-- --------------------------- -->
        
    </div>

<?php endif; ?>

<script>

    $(document).ready(function()
    {
        $(".sortable-panels-ids").each(function(i)
        {
            filasOrdens[i] = $(this).attr('id');
        });

        $("#sortable").sortable({
            handle: ".panel-heading:not(.unsortable)",
            // Quando clica
            start: function(e, ui)
            {
                // Cria um atributo temporário no elemento com o antigo index
                $(this).attr('data-prev-index', ui.item.index());
            },
            // Quando move até outro elemento
            change: function ()
            {
                // Copia div
                let divUnsortable = $('#div-unsortable').clone();
                // Remove
                $('#div-unsortable').remove();
                // Coloca novamente para cancelar o arrasta e solta em cima do primeiro painel
                divUnsortable.prependTo($("#sortable"));
            },
            // Quando solta em outro elemento
            update: function(e, ui)
            {
                // gets o novo e velho indice
                var newIndex = ui.item.index();
                var newId = ui.item.attr('id');
                var oldIndex = $(this).attr('data-prev-index');
                var oldId;

                // Identifica o item pelo indice e obtem o id
                $(".sortable-panels-ids").each(function(i)
                {
                    if (oldIndex == i +1)
                        oldId = $(this).attr('id');
                });
                
                // removes the temporary attribute
                $(this).removeAttr('data-prev-index');

                // Reatribui valores
                filasOrdens[oldIndex -1] = oldId;
                filasOrdens[newIndex -1] = newId;
            }
        });
    });

</script>

<style>
    #sortable > div {
        border-top: 1px solid transparent;
    }
    .panel-heading:not(.unsortable) {
        cursor: move;
    }
    /* scroll personalizado */
    .div-scroll {
        overflow: hidden;
    }
    .div-scroll:hover {
        overflow-y: scroll;
    }
    ::-webkit-scrollbar-track {
        background-color: #F4F4F4;
    }
    ::-webkit-scrollbar {
        width: 9px;
        background: #F4F4F4;
    }
    ::-webkit-scrollbar-thumb {
        background: #dad7d7;
    }
    /* *** */
    .icone-contador {
        float: right;
        background-color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        color: #03A9F4;
        text-align: center;
    }
    .icone-status-farol {
        float: left;
        border-radius: 50%;
        width: 15px;
        height: 15px;
        margin-right: 5px;
        top: 2px;
        position: relative;
    }
    .icone-status-farol-filas {
        float: left;
        border-radius: 50%;
        width: 19px;
        height: 19px;
        margin-right: 5px;
        top: 2px;
        position: relative;
    }
    .icone-fila-tipo-canal {
        line-height: 0 !important;
        margin: 0 10px 0 0 !important;
        position: relative !important;
        top: 7px !important;
    }
    .icone-disponibilidade {
        float: left;
        margin-right: 10px;
    }
    
</style>