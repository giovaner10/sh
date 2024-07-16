<div id="modalVisualizarArquivos" class="modal fade" tabindex="-1" role="dialog"
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
            
            <div class="row">

                <!-- lista arquivos -->
                <?php foreach ($arquivos as $arquivo) :?>
                    <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">

                        <!-- link -->
                        <a href="<?=$arquivo->arquivoCompleto?>" target="_blank">
                            <img src='<?=$arquivo->imagem?>'
                                class="img-arquivo img-responsive img-rounded" alt="">
                        </a>

                        <!-- download -->
                        <a download href="<?=$arquivo->arquivoCompleto?>" target="_blank">
                            <p class="titulo-arquivo">
                                <?=strlen($arquivo->file) > 20 ? substr($arquivo->file, 0, 20) . '...' : $arquivo->file;?>
                                <span class="material-icons material-icons-download">
                                    get_app
                                </span>
                            </p>
                        </a>

                    </div>
                <?php endforeach?>

            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalVisualizarArquivos").modal({backdrop: 'static' });
    });

    // Trabalha com o overflow para 2 modais
    $('#modalVisualizarArquivos').on('hidden.bs.modal', function(e){
        $("body").css('overflow-y', 'hidden');
        $("#modalDocumentosPendentes").css('overflow-y', 'auto');
    });

</script>


<style>
    .titulo-tipo-arquivo {
        font-weight: bold;
        color: #666;
    }

    .img-responsive {
        margin: 0 auto;
    }

    .img-arquivo {
        width: 250px;
        height: 150px;
        border-radius: 20px;
        margin-top: 20px;
    }

    .titulo-arquivo {
        margin-top: 4px;
        color: #666;
        text-align: center;
    }

    .material-icons-download {
        color: #03A9F4 !important;
        position: relative;
        top: 5px;
    }
</style>