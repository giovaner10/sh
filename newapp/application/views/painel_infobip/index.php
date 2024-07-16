
<h3><?=lang('painel_infobip')?></h3>

<hr>

<!-- Button administrar parâmetros -->
<?php if ($this->auth->is_allowed_block('cad_administrarparametrosdopainelinfobip')) :?>
   
    <button class="btn btn-lg btn-primary" id="buttonParametros" onclick="parametrosAdministrar()">
        <i class="fa fa-cogs m-r-10" aria-hidden="true"></i>
        <?=lang("administrar_parametros")?>
    </button>

    <div id="divParametrosAdministrar"></div>

<?php endif; ?>

<div class="m-t-30">

    <!-- Filas -->
    <div class="row">
        <div class="col-md-6">

            <select name="grupos[]" id="grupos" class="form-control" style="width: 100% !important;">
                <option value=""><?=lang("selecione_um_grupo_de_filas")?></option>

                <?php foreach ($dados["grupos"] as $grupo) : ?>
                    <option value="<?=$grupo->id?>"><?=$grupo->nome?></option>
                <?php endforeach; ?>

            </select>
        </div>

        <div class="col-md-6">
            
            <select name="filas[]" id="filas" multiple class="form-control filas"
                data-placeholder="<?=lang("validacao_filas")?>" style="width: 100% !important;">

                <?php foreach ($dados["filas"] as $fila) : ?>
                    <option value="<?=$fila->id?>"><?=$fila->name?></option>
                <?php endforeach; ?>

            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-outline-primary" id="buttonGetDados" onclick="getDados()" style="margin-top: 15px;"><?=lang('pesquisar')?></button>
        </div>
    </div>

    <!-- Reporta erro de carregamento de filas -->
    <?php if ($status == 0) :?>
        <div class="alert alert-warning" role="alert" style="margin-top: 20px;">
            <?=lang("excecao_filas")?>
        </div>
    <?php endif; ?>

    <div id="filas_validacao" class="hidden alert alert-warning" role="alert" style="margin-top: 20px;">
        <?=lang('validacao_filas')?>
    </div>

    <!-- Dados load -->
    <div id="dados"></div>
    
</div>

<script>
    
    var filasOrdens = new Array();
    var primeiroLoad = true;
    
    $(document).ready(function()
    {
        $("#grupos").multiselect({
            buttonWidth: "100%",
            enableCaseInsensitiveFiltering: true,
            nSelectedText: ' <?=lang("selecionados")?>'.toLowerCase(),
            onChange: function(option, checked)
            {
                // Get grupo selecionado.
                let grupoId = $("#grupos option:selected").val();

                // Get todas as filas desse grupo
                $.getJSON(
                    "<?=site_url('PaineisInfobip/getGrupoFilas')?>"+ "/" + grupoId, // URL
                    function (retorno) // Callback
                    {
                        // Mensagem de retorno
                        if (retorno.status == 0)
                            toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                            
                        let filasIds = retorno.dados;

                        // Reseta o select filas
                        $("option", $("#filas")).each(function(i)
                        {
                            $(this).removeAttr("selected").prop("selected", false);
                        });
                        $("#filas").multiselect("refresh");

                        // Seleciona as filas do grupo
                        $("option", $("#filas")).each(function(i)
                        {
                            let filaId = $(this).val();

                            // Se a fila for do grupo - seleciona
                            if (filasIds.find(id => id == filaId))
                                $(this).prop("selected", true);
                        });
                        $("#filas").multiselect("refresh");

                        var selectedOptions = $('#filas option:selected');

                        if (selectedOptions.length >= 15) {
                            // Disable all other checkboxes.
                            var nonSelectedOptions = $('#filas option').filter(function() {
                                return !$(this).is(':selected');
                            });
                        
                            nonSelectedOptions.each(function() {
                                var input = $('input[value="' + $(this).val() + '"]');
                                input.prop('disabled', true);
                                input.parent('.multiselect-option').addClass('disabled');
                            });
                        }
                    }
                );
            }
        });

        $("#filas").multiselect({
            buttonWidth: '100%',
            enableCaseInsensitiveFiltering: true,
            nSelectedText: ' <?=lang("selecionados")?>'.toLowerCase(),
            onChange: function(option, checked)
            {
                // Get selected options.
                var selectedOptions = $('#filas option:selected');

                if (selectedOptions.length >= 15) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#filas option').filter(function() {
                        return !$(this).is(':selected');
                    });
 
                    nonSelectedOptions.each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('.multiselect-option').addClass('disabled');
                    });
                }
                else {
                    // Enable all checkboxes.
                    $('#filas option').each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('.multiselect-option').addClass('disabled');
                    });
                }
            }
        });
            
    });

    function getDados(atualizacao)
    {
        /* Campo obrigatório */
        if ($("#filas option:selected").length == 0)
        {
            if (!atualizacao)
                $("#filas_validacao").removeClass("hidden");

            return false;
        }
        if ($("#filas option:selected").length > 15)
        {
            alert("Mais de 15 filas selecionadas, configure em Administrar Parâmetros->Editar");
        }
        else
        $("#filas_validacao").addClass("hidden");

        if (!atualizacao)
        {
            $('#buttonGetDados')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('pesquisando')?>')
                .attr('disabled', true);
        }
        
        $("#dados").load(
            "<?=site_url('PaineisInfobip/getDados')?>",
            {
                filaIds: $("#filas").val(),
                filasOrdens
            },
            function() // Callback
            {
                if (!atualizacao)
                {
                    $('#buttonGetDados')
                        .html('<?=lang('pesquisar')?>')
                        .attr('disabled', false);
                }

                // Inicializa atualização de dados a cada 30 segundos
                if (primeiroLoad === true)
                {
                    setInterval(function()
                    {
                        let atualizacao = true;
                        getDados(atualizacao);
                    }, 60000);

                    primeiroLoad = false;
                }
            }
        );
    }

    function parametrosAdministrar()
    {
        $('#buttonParametros')
            .html('<i class="fa fa-spin fa-spinner"></i>&nbsp&nbsp <?=lang('administrar_parametros')?>')
            .attr('disabled', true);

        $("#divParametrosAdministrar").load(
            "<?=site_url('PaineisInfobip/parametrosAdministrar')?>",
            {filaIds: $("#filas").val()},
            function() // Callback
            {
                $('#buttonParametros')
                    .html(`
                        <i class="fa fa-cogs m-r-10" aria-hidden="true"></i>
                        <?=lang('administrar_parametros')?>
                    `)
                    .attr('disabled', false);
            }
        );
    }

</script>

<style>
    .multiselect-container {
        width: 100% !important;
    }
</style>