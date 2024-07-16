<?php if ($this->auth->is_allowed_block('cad_docs_pendentes')) : ?>

	<button type="button" id="buttonAdmDocumentosPendentes" onclick="documentosPendentesAdministrar()" class="btn btn-primary btn-lg"
        style="margin-bottom: 15px;">
        <?=lang("administrar_solicitacoes")?>
    </button>

<?php endif; ?>

<?php if (count($documentosPendentes) == 0) :?>

	<div class="alert alert-info" role="alert">
        <?=lang("nenhum_documento_pendente");?>
    </div>

<?php else :?>
	

    <!-- Quando o funcionario envia os documentos mas ainda não foram analisados pelo RH -->
    <?php if ($documentosPendentes->status_documentos == "documentos enviados") :?>

        <div class="alert alert-info" role="alert">
            <?=lang("documentos_enviados_aguardando_analise");?>
        </div>

    <?php endif; ?>

	<form id="formEnvioDocumento" enctype="multipart/form-data">
				
		<div class="row">
			<div class="col-md-12">

				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<label><?=lang("nome")?></label>
							<p><?=$usuario->nome;?></p>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label"><?=lang("documentos_pendentes")?></label>

					<?php if ($documentosPendentes->residencia == 'sim') : ?>
						<div class="panel panel-default" style="padding: 10px; margin-bottom:10px"><?=lang("comprovante_residencia");?></div>
					<?php endif; ?>
					
					<?php if ($documentosPendentes->cpf == 'sim') : ?>
						<div class="panel panel-default" style="padding: 10px; margin-bottom:10px">CPF</div>
					<?php endif; ?>
					
					<?php if($documentosPendentes->rg == 'sim') : ?>
						<div class="panel panel-default" style="padding: 10px; margin-bottom:10px">RG</div>
					<?php endif; ?>

					<?php if($documentosPendentes->banco == 'sim') : ?>
						<div class="panel panel-default" style="padding: 10px; margin-bottom:10px"><?=lang("comprovante_dados_bancarios");?></div>
					<?php endif; ?>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label><?=lang("data_solicitacao")?></label>
							<input disabled type="text" class="form-control" value="<?=date("d/m/Y", strtotime($documentosPendentes->data_solicitacao))?>" />
						</div>
						<div class="col-md-6">
							<label><?=lang("data_limite")?></label>
							<input disabled type="text" class="form-control" value="<?=date("d/m/Y", strtotime($documentosPendentes->prazo_maximo))?>" />
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label class="control-label"><?=lang("enviar_arquivos")?></label> 
							<input type="file" class="form-control" name="arquivos[]" multiple required>
						</div>
					</div>
				</div>

			</div>
		</div>
		
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<input type="submit" id="documentoEnviar" class="btn btn-primary btn-lg btn-block" value="<?=lang('enviar')?>" />
				</div>
			</div>
		</div>

	</form>
		
<?php endif; ?>
	

<script>

	$(document).ready(function()
    {
		$("#formEnvioDocumento").on("submit", function(evento)
		{
			evento.preventDefault();

            // Carregando
            $('#documentoEnviar')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('enviando')?>')
                .attr('disabled', true);

            $.ajax({
                url: "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesEnviarArquivos')?>",
                data: new FormData($("#formEnvioDocumento")[0]),
                type: "POST",
                dataType: "JSON",
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        // Mensagem de retorno
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                        // Recarrega index doc pendentes
                        documentosPendentesAtualizarListagem();
                    }
                    else
                    {
                        // Mensagem de retorno
                        toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    // Mensagem de erro
                    toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
                },
                complete: function ()
                {
                    // Carregado
                    $('#documentoEnviar')
                        .html('<?=lang('enviar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
		});
	});

    function documentosPendentesAdministrar()
    {
        // Carregando
        $('#buttonAdmDocumentosPendentes')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#divAdmDocumentosPendentes").load(
            "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesAdministrar')?>",
            function()
            {
                // Carregado
                $('#buttonAdmDocumentosPendentes')
                    .html('<?=lang("administrar_solicitacoes")?>')
                    .attr('disabled', false);
            }
        );
    }
	
    function documentosPendentesAtualizarListagem()
    {
        $("#tab_documentos_pendentes").load("<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesAtualizarListagem')?>");
    }

</script>