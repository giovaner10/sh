<div id="modalAtalhosComercialTelevendas" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="formAtalhosComercialTelevendas">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                
                    <div class="form-group">
                        <label class="control-label" for="tabelaDePrecos">URL <?=lang("tabela_de_precos")?></label>
                        <input type="text" id="tabelaDePrecos" name="tabelaDePrecos" class="form-control" required
                            value="<?=isset($atalhos[0]->url) ? $atalhos[0]->url : ''?>"
                            placeholder="ex: https://www.google.com.br" maxlength="500">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="imagensParaWhatsapp">URL <?=lang("imagens_para_whatsapp")?></label>
                        <input type="text" id="imagensParaWhatsapp" name="imagensParaWhatsapp" class="form-control" required
                            value="<?=isset($atalhos[1]->url) ? $atalhos[1]->url : ''?>"
                            placeholder="ex: https://www.google.com.br" maxlength="500">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSalvarAtalho"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalAtalhosComercialTelevendas").modal({backdrop:'static'});

        // Salva Formulário
        $("#formAtalhosComercialTelevendas").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#btnSalvarAtalho')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formAtalhosComercialTelevendas")[0]);
            let url = "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/alterarAtalhosComercialTelevendas')?>";

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                        $("#modalAtalhosComercialTelevendas").modal('hide');
                        
                        // carrega valores novos
                        $('#atalhoTabelaPrecos').attr("href", retorno.dados.tabelaDePrecos);
                        $('#atalhoImagensParaWhatsapp').attr("href", retorno.dados.imagensParaWhatsapp);
                    }
                    else
                        toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
                },
                complete: function ()
                {
                    // Carregado
                    $('#btnSalvarAtalho')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

</script>