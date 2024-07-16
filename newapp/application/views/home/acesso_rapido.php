<div id="acessoRapidoModal" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 70%;">
        <div class="modal-content">
            
            <form id="formAtalhosUsuario">
                <div class="modal-header">
                    <button type="button" class="close" aria-label="Close" onclick="fecharModalAcessoRapidoConfirmacao()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                    
                    <!-- Acessos -->
                    <div class="row">
                        <div id="sortable" class="col-md-offset-1 col-md-10" style="padding: 0px 37px;">

                            <?php foreach (array_chunk($atalhosUsuario, 3) as $atalhosLinha) : ?>

                                <div class="row" style="margin-top: 15px; margin-left: -30px;">

                                    <?php foreach ($atalhosLinha as $atalho) : ?>

                                        <div class="sortable-item col-sm-4 col-md-4" id="divAtalho_<?=$atalho->menuId?>">
                                            
                                            <input type="hidden" id="atalho_<?=$atalho->menuId?>" name="atalhos[]" value="<?=$atalho->menuId?>">

                                            <a href="javascript:;" class="btn btn-default btn-lg btn-block btn-atalho">
                                                <i class="material-icons material-icons-atalho">
                                                    <?=$atalho->menuIcone?>
                                                </i>

                                                <?php if (count(explode(' ', $atalho->menuNome)) > 3) : ?>

                                                    <span class="text-button-atalho">
                                                        <?php 
                                                            $menuNomeArray = explode(' ', $atalho->menuNome);
                                                            echo "$menuNomeArray[0] $menuNomeArray[1] $menuNomeArray[2]...";
                                                        ?>
                                                    </span>

                                                    <?php else : ?>

                                                    <span class="text-button-atalho">
                                                        <?= strlen($atalho->menuNome) > 30
                                                            ? substr($atalho->menuNome, 0, 30) . '...'
                                                            : $atalho->menuNome
                                                        ?>
                                                    </span>

                                                <?php endif;?>
                                            </a>
                                            
                                        </div>

                                    <?php endforeach; ?>
                                    
                                </div>

                            <?php endforeach; ?>
                            
                        </div>
                    </div>

                    <hr>

                    <!-- Menus -->
                    <div class="row">
                        <div class="col-md-offset- col-md-12">
                            <table id="menusTabela" class="table table-striped table-bordered">
                                <thead>
                                    <tr class="tableheader">
                                        <th>#</th>
                                        <th><?=lang("menu")?></th>
                                        <th><?=lang("icone")?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarAtalhosUsuario"><?=lang("salvar")?></button>
                    <button type="button" class="btn" onclick="fecharModalAcessoRapidoConfirmacao()"><?=lang("fechar")?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de validação de alteração -->
<div id="modalValidacaoAlteracao" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    
    <div class="modal-dialog" role="document" style="width: 30%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=lang("atencao")?></h4>
            </div>

            <div class="modal-body">
                <?=lang("podem_existir_alteracoes_nao_salvas")?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="salvarAlteracoesAcessoRapido()"><?=lang("salvar_alteracoes")?></button>
                <button type="button" class="btn" data-dismiss="modal" onclick="fecharModalAcessoRapido()"><?=lang("fechar")?></button>
            </div>

        </div>
    </div>
</div>

<script>

    var atalhosAlteracao = false;

    $(document).ready(function()
    {
        // Trabalha com o overflow para 2 modais
        $('#acessoRapidoModal').on('show.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
        });
        $('#acessoRapidoModal').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'auto');
        });
        
        $("#acessoRapidoModal").modal();

        $('[data-toggle="tooltip"]').tooltip();

        $("#sortable").sortable({
            items: '.sortable-item',
            // Quando move até outro elemento (depois que solta o elemento)
            update: function (e, ui)
            {
                atalhosAlteracao = true;
                atalhoProximo = ui.item[0].nextElementSibling;
                atalhoAnterior = ui.item[0].previousElementSibling;

                $("#sortable > .row").each(function (i, element)
                {
                    const atalhosLinhaQtd = $(element).find('.sortable-item').length;

                    // Se tiver mais de 3 atalhos
                    if (atalhosLinhaQtd > 3)
                    {
                        // Clona e remove o atalho da posição posterior
                        let atalhoRealocado = $(atalhoProximo).clone();
                        $(atalhoProximo).remove();

                        // Coloca antes do elemento em seleção
                        atalhoRealocado.insertBefore($(ui.item[0]));

                        normalizaLinhasColunasAtalhos();
                        return false;
                    }
                });
            }
        });
    });

    var atalho = <?=json_encode($atalhosUsuario)?>;
    var atalhoMenuIds = atalho.map(function(dados)
    {
        return dados.menuId;
    });

    var menusTabela = $("#menusTabela").DataTable(
    {
        order: [],
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        dom: 'Bfrtip',
        buttons: [],
        ajax: "<?=site_url('Homes/getMenus')?>",
        columnDefs: [
            {
                render: function (data, type, row, meta)
                {
                    let checked = '';
                    if (atalhoMenuIds.includes(data))
                    {
                        menusTabela.row(meta.row).select();
                        checked = 'checked';
                    }
                    return `\
                        <input type="checkbox" value="${data}" class="checkboxMenus" ${checked}
                            onchange="selecaoDeAtalhos(this, ${data}, '${row[1]}', '${row[2]}', '${row[3]}')"
                        >
                    `;
                },
                className: 'text-center',
                targets: 0
            },
            {
                render: function (data, type, row, meta)
                {
                    let menuNomeCompletoArray = data.split(' > ');
                    let menuNome = menuNomeCompletoArray[menuNomeCompletoArray.length -1];
                    menuNomeCompletoArray.splice(menuNomeCompletoArray.length -1, 1);

                    return `${menuNomeCompletoArray.join(' > ')}
                        ${menuNomeCompletoArray.length > 0 ? '> ' : ' '}
                        <b>${menuNome}</b>`;
                },
                targets: 1
            },
            {
                targets: [ 2, 3 ],
                visible: false,
                searchable: false
            }
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child > input:checkbox'
        },
        initComplete: function(settings, json) {
            bloqueiaOudesbloqueiaCheckboxsMenu();
        }
    });

    $("#formAtalhosUsuario").on("submit", function(evento)
    {
        evento.preventDefault();

        // Carregando
        $('#buttonSalvarAtalhosUsuario')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
            .attr('disabled', true);

        let dados = new FormData($("#formAtalhosUsuario")[0]);
        let url = "<?=site_url('Homes/atalhosUsuarioAtualizar')?>";

        $.ajax({
            url: url,
            data: dados,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    // Reload atalhos dashboard
                    getAtalhosUsuario();

                    // Fecha modal
                    $('#acessoRapidoModal').modal('hide');
                }
                else
                {
                    // Mensagem de retorno
                    toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                    $('#acessoRapidoModal').css('overflow-y', 'auto');
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                // Mensagem de erro
                toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
                $('#acessoRapidoModal').css('overflow-y', 'auto');
            },
            complete: function ()
            {
                // Carregado
                $('#buttonSalvarAtalhosUsuario')
                    .html('<?=lang('salvar')?>')
                    .attr('disabled', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    function fecharModalAcessoRapidoConfirmacao()
    {
        if (!atalhosAlteracao)
        {
            $('#acessoRapidoModal').modal('hide');
            return false;
        }
        
        $('#modalValidacaoAlteracao').modal();
    }

    function fecharModalAcessoRapido()
    {
        $('#acessoRapidoModal').modal('hide');
    }

    function salvarAlteracoesAcessoRapido()
    {
        $('#formAtalhosUsuario').submit();
    }

    function selecaoDeAtalhos(input, menuId, menuNomeCompleto, menuNome, menuIcone)
    {
        atalhosAlteracao = true;

        // (tabela)
        bloqueiaOudesbloqueiaCheckboxsMenu();

        // Remove atalho quando um menu é desselecionado
        if (input.checked === false)
        {
            $("#divAtalho_" + menuId).remove();
            return;
        }

        // Cria atalho quando um menu é selecionado 
        let menuNomeHtml = '';
        let menuNomeArray = menuNome.split(' ');
        let menuNomeEspacosQtd = menuNomeArray.length;

        if (menuNomeEspacosQtd > 3)
        {
            menuNomeHtml = `\
                <span class="text-button-atalho">
                    ${menuNomeArray[0]} ${menuNomeArray[1]} ${menuNomeArray[2]}...
                </span>
            `;
        }
        else
        {
            menuNomeHtml = `\
                <span class="text-button-atalho">
                    ${menuNome.length > 35
                        ? menuNome.substr(0, 35) + '...'
                        : menuNome
                    }
                </span>
            `;
        }

        let html = `\
            <div class="sortable-item col-sm-4 col-md-4" id="divAtalho_${menuId}">
                <input type="hidden" id="atalho_${menuId}" name="atalhos[]" value="${menuId}">
                <a href="javascript:;" class="btn btn-default btn-lg btn-block btn-atalho">
                    <i class="material-icons material-icons-atalho">
                        ${menuIcone}
                    </i>
                    ${menuNomeHtml}
                </a>
            </div>
        `;

        // Identifica uma linha que possa inserir o atalho
        $("#sortable > .row").each(function (i, element)
        {
            const atalhosLinhaQtd = $(element).find('.sortable-item').length;

            // Se tiver menos de 3 atalhos
            if (atalhosLinhaQtd < 3)
            {
                $(element).append(html);
                return false;
            }
        });

        $("#sortable").sortable('refresh');
        $('[data-toggle="tooltip"]').tooltip();
    }

    function bloqueiaOudesbloqueiaCheckboxsMenu()
    {
        let atalhosQtdMax = 6;
        let menusChecados = menusTabela.rows( { selected: true } ).count();
        let menusNaoSelecionados = menusTabela.rows( { selected: false } )[0];
         
        if (menusChecados >= atalhosQtdMax)
        {
            // Bloqueia todos os campos não selecionados
            for (x = 0; x < menusNaoSelecionados.length; x++)
            {
                let inputCheckbox = menusTabela.row(
                    menusNaoSelecionados[x] // indice da linha
                ).nodes().to$().find('td:first-child > input:checkbox'); // checkbox

                $(inputCheckbox).prop('disabled', true);
            }
        }
        else if (menusChecados < atalhosQtdMax)
        {
            // Desbloqueia todos os campos disableds
            for (x = 0; x < menusNaoSelecionados.length; x++)
            {
                let inputCheckbox = menusTabela.row(
                    menusNaoSelecionados[x] // indice da linha
                ).nodes().to$().find('td:first-child > input:checkbox'); // checkbox

                $(inputCheckbox).prop('disabled', false);
            }
        }
    }

    // Carrega os atalhos do dashboard principal (home)
    function getAtalhosUsuario()
    {
        $.getJSON('<?=site_url("Homes/getAtalhosUsuario")?>', function (atalhos)
        {
            let html = '';

            const chunk = (arr, size) =>
            Array.from({ length: Math.ceil(arr.length / size) }, (v, i) =>
                arr.slice(i * size, i * size + size)
            );
            let atalhosLinhas = chunk(atalhos, 3);
            
            atalhosLinhas.forEach(function(atalhoLinha, i)
            {
                html += `<div class="row" style="margin-bottom: 15px; margin-left: -30px;">`;

                atalhoLinha.forEach(function(atalho, i)
                {
                    
                    let menuNomeHtml = '';
                    let menuNomeArray = atalho.menuNome.split(' ');
                    let menuNomeEspacosQtd = menuNomeArray.length;

                    if (menuNomeEspacosQtd > 2)
                    {
                        menuNomeHtml = `\
                            <span class="text-button-atalho">
                                ${menuNomeArray[0]} ${menuNomeArray[1]}...
                            </span>
                        `;
                    }
                    else
                    {
                        menuNomeHtml = `\
                            <span class="text-button-atalho">
                                ${atalho.menuNome.length > 30
                                    ? atalho.menuNome.substr(0, 30) + '...'
                                    : atalho.menuNome
                                }
                            </span>
                        `;
                    }

                    html += `\
                        <div class="col-md-4">
                            <a href="${atalho.menuCaminho}" class="btn btn-default btn-lg btn-block btn-atalho"
                                data-toggle="tooltip" title="${atalho.menuNomeCompleto}">
                                <i class="material-icons material-icons-atalho">
                                    ${atalho.menuIcone}
                                </i>
                                ${menuNomeHtml}
                            </a>
                        </div>
                    `;
                });

                html += `</div>`;
            });

            $("#divDashboardAtalhosUsuario").html(html);
            $('[data-toggle="tooltip"]').tooltip();
        });
    }

    // Função que mantem as linhas com a qtd de colunas certas
    function normalizaLinhasColunasAtalhos()
    {
        $("#sortable > .row").each(function (i, element)
        {
            const atalhosLinhaQtd = $(element).find('.sortable-item').length;

            // Se tiver mais de 3 atalhos
            if (atalhosLinhaQtd > 3)
            {
                // Linha 2
                if (i == 1)
                {
                    // Pega o primeiro elemento da linha 2 e joga na última posição da linha 1
                    let atalhoRealocado = $(element).find('.sortable-item:first').clone();
                    $(element).find('.sortable-item:first').remove();
                    $("#sortable > .row:first").append(atalhoRealocado);
                }
                // Linha 1
                else if (i == 0)
                {
                    // Pega o último elemento da linha 1 e joga na primeira posição da linha 2
                    let atalhoRealocado = $(element).find('.sortable-item:last').clone();
                    $(element).find('.sortable-item:last').remove();
                    $("#sortable > .row:last").prepend(atalhoRealocado);
                }
            }
        });
    }

</script>