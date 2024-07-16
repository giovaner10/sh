<style>
    .close {
        margin-right: 10px !important;
        margin-top: 10px !important;
    }

    th.dt-center, td.dt-center { text-align: center; }

    .select2-container {
        width: 90% !important;
    }
    .table {
        width: 100% !important;
    }

</style>
<div class="col-md-12">
    <div class="row">
        <h3><?= lang('relatorio') . ' - ' . lang('proporcional_tempo_contrato') ?></h3>
        <div class="well well-small col-md-12">
            <form style="align-items:center" id="formGerarResult" action="<?= site_url('relatorios/veiculosDiaAtualizacao') ?>" method="get" accept-charset="utf-8">
                <div class="row">
                    <div class="col-md-3">
                        <i class="fa fa-clock-o" style="font-size: 24px;"></i>
                        <select style="padding:3px 2px; width:90%" name="base_calculo" id="base_calculo">
                            <option value="" selected><?=lang('selecione_base_calculo')?></option>
                            <option value="30_dias"><?=lang('30_dias')?></option>
                            <option value="dias_do_mes"><?=lang('total_dias_mes')?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:90%" type="text" name="di" required placeholder="<?= lang('data_inicial') ?>" autocomplete="off" id="dp1" value="<?php if ($this->input->get('di')) echo $this->input->get('di'); ?>" />
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:90%" type="text" name="df" required placeholder="<?= lang('data_final') ?>" autocomplete="off" id="dp2" value="<?php if ($this->input->get('df')) echo $this->input->get('df'); ?>" />
                    </div>
                </div>
                <div class="row" style='margin-top: 20px;'>
                    <div class="col-md-3">
                        <i class="fa fa-user" style="font-size: 22px;"></i>
                        <select name="id_cliente" id="clientes" required>
                            <option value=""><?= lang('selecione_cliente') ?></option>
                        </select>
                    </div>
                    <!-- filtro de veiculos ativos  -->
                    <div class="col-md-3" >
                        <i class="fa fa-car" style="font-size: 22px;"></i>
                        <select name="statusVeiculo" id="status_veiculo_id" required>
                            <option value= 'todos'>Todos</option>
                            <option value='ativo'>Ativos</option>
                            <option value='inativo'> Inativos</option>
                            <option value='cancelado'> Cancelado</option>

                        </select>
                    </div>
                </div>
                
                <div class="col-md-12" style="text-align:right; margin-top:5px">
                    <button type="submit" class="btn btn-primary gerar_rel">
                        <?= lang('gerar') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="alert_acao row" style="display:none">
        <button type="button" class="close">&times;</button>
        <span id="mensagem"></span>
    </div>

    <div class="row">
        <button class="btn btn-warning" id="btnFiltro" data-acao="mostrar">
            <i class="fa fa-cogs"></i> <?= lang('filtro') ?>
        </button>
    </div>
    <div id="filtro_colunas" class="collaps row">
        <br>
        <div class="alert alert-info">
            <?= lang('info_filtro_relatorio'); ?>
        </div>
        <h5><?= lang('informacoes'); ?>:</h5>        
        <div style="margin-bottom: 5px;">
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="1" checked><?= lang('veiculo'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="2" checked><?= lang('id_contrato'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="3" checked><?= lang('data_vinc'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="4" checked><?= lang('data_desv'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="5"><?= lang('o_s'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="6"><?= lang('lote'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="7" checked><?= lang('placa'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="8"><?= lang('prefixo'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="9"><?= lang('modelo_veiculo'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="10"><?= lang('centro_custo'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="11"><?= lang('tipo_frota'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="12"><?= lang('fabricante'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="13"><?= lang('modelo_rastreador'); ?></input></label>
        </div>
        <div>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="14" checked><?= lang('serial_vinc'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="15" checked><?= lang('serial_desv'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="16"><?= lang('secretaria'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="17" checked><?= lang('valor_mensal'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="18" checked><?= lang('valor_diario'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="19" checked><?= lang('qtd_dias'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="20"><?= lang('valor_instalacao'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="21" checked><?= lang('total_proporcional'); ?></input></label>
        </div>
    </div>

    <div class="row">
        <h4><span id="total_veiculos"></span><span id="total" style="margin-left:3em"></span></h4>
        <table id="table" class="display table responsive table-striped table-bordered">
            <thead>
                <th>#</th>
                <th><?= lang('veiculo') ?></th>
                <th><?= lang('id_contrato') ?></th>
                <th><?= lang('data_vinc') ?></th>
                <th><?= lang('data_desv') ?></th>
                <th><?= lang('o_s') ?></th>
                <th><?= lang('lote') ?></th>
                <th><?= lang('placa') ?></th>
                <th><?= lang('prefixo') ?></th>
                <th><?= lang('modelo_veiculo') ?></th>
                <th><?= lang('centro_custo') ?></th>
                <th><?= lang('tipo_frota') ?></th>
                <th><?= lang('fabricante') ?></th>
                <th><?= lang('modelo_rastreador') ?></th>
                <th><?= lang('serial_vinc') ?></th>
                <th><?= lang('serial_desv') ?></th>
                <th><?= lang('secretaria') ?></th>
                <th><?= lang('valor_mensal') ?></th>
                <th><?= lang('valor_diario') ?></th>
                <th><?= lang('qtd_dias') ?></th>
                <th><?= lang('valor_instalacao') ?></th>
                <th><?= lang('total_proporcional') ?></th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var table = false;

    jQuery.browser = {};
    (function() {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();

    $(document).ready(function() {
        //esconde div de filtros
        $("#filtro_colunas").css('display', 'none');

        $('#clientes').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            }
        });

        //Oculta/exibe as colunas da tabela
        $('.item-filtro').change(function() {
            var status = true;
            if ($(this).is(':checked') == false) {
                status = false;
            }
            $('#table').DataTable().columns($(this).val()).visible(status).draw();
        });

        

        //Mostra apenas as colunas padroes
        $('.item-filtro').each(function() {
            var status = true;
            if ($(this).is(':checked') == false) {
                status = false;                
            }
            $('#table').DataTable().columns($(this).val()).visible(status).draw();
        });


        $('form#formGerarResult').submit(function() {
            var data_get = $(this).serialize();
            $('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> <?= lang('gerando') ?>');
            $.getJSON('<?= site_url('relatorios/ajax_veiculos_tempo_contrato') ?>', data_get, function(data) {
                if (data.status == 'OK') {
                    var tabela = data.tabela;
                    $('.alert_acao').css('display', 'none');

                    // Atualiza Tabela
                    table.clear();
                    table.rows.add(tabela);
                    table.draw();

                    //Atualiza qtd de veiculos e valor total
                    $('#total_veiculos').text("<?= lang('total_veiculo') ?>: " + data.total_veic);
                    $('#total').text("<?= lang('valor_total') ?>: " + data.valor_total);

                } else {
                    table.clear();
                    table.draw();

                    //Atualiza qtd de veiculos e valor total
                    $('#total_veiculos').text("<?= lang('total_veiculo') ?>: 0");
                    $('#total').text("<?= lang('valor_total') ?>: R$ 0,00");
                    $('.alert_acao').css('display', 'block');
                    $("#mensagem").html('<div class="alert alert-danger"><p><b>' + data.msg + '</p></div>');
                }

                // Ativa e troca html button "GERAR"
                $('.gerar_rel').removeAttr('disabled').html("<?= lang('gerar') ?>");
            });

            return false;
        });

    });

    table = $('#table').DataTable({
        dom: 'Bfrtip',
        paging: true,
        destroy: true,
        responsive: true,
        orderable: false,
        info: true,
        order: [0, 'asc'],
        columns: [
            { data: 'linha' },
            { data: 'veiculo' },
            { data: 'idContrato' },
            { data: 'dataVinculacao' },
            { data: 'dataDesvinculacao' },
            { data: 'os' },
            { data: 'lote' },
            { data: 'placaVinculacao' },
            { data: 'prefixo_veiculo' },
            { data: 'modelo_veiculo' },
            { data: 'centro_custo' },
            { data: 'tipo_frota' },
            { data: 'fabricante' },
            { data: 'modelo_equipamento' },
            { data: 'serialVinculacao' },
            { data: 'serialDesvinculacao' },
            { data: 'secretaria' },
            {
                data: 'valorMensal',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },                
            {
                data: 'valor_diario',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            { data: 'contagemDias' },
            {
                data: 'valor_instalacao',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            {
                data: 'valorPeriodo',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            }
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
                className: "dt-center"
            }
        ],
        buttons: [
            {
                extend: 'pdfHtml5',
                messageTop: function() {
                    return $('#total').text() + ' | ' + $('#total_veiculos').text() + ' | <?= ucfirst(lang('cliente')) ?>: ' + $('#clientes option:selected').text() + ' | <?= ucfirst(lang('periodo')) ?>: ' + $('#dp1').val() + ' à ' + $('#dp2').val()
                },
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                action: function(e, dt, button, config) {
                    var pdfButtonConfig = $.fn.DataTable.ext.buttons.pdfHtml5;
                    pdfButtonConfig.exportOptions.columns = colunas_selecionadas();         //SETA QUAIS COLUNAS VAO SER EXPORTADAS
                    pdfButtonConfig.orientation = customPageExport('table','orientation'),
                    pdfButtonConfig.pageSize = customPageExport('table','pageSize'),
                    pdfButtonConfig.action.call(dt.button(this), e, dt, button, pdfButtonConfig);
                }
                
            },
            {
                extend: 'excelHtml5',
                messageTop: function() {
                    return $('#total').text() + ' | ' + $('#total_veiculos').text() + ' | <?= ucfirst(lang('cliente')) ?>: ' + $('#clientes option:selected').text() + ' | <?= ucfirst(lang('periodo')) ?>: ' + $('#dp1').val() + ' à ' + $('#dp2').val()
                },
                orientation: customPageExport('table','orientation'),
                pageSize: customPageExport('table','pageSize'),
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL',
                action: function(e, dt, button, config) {
                    var excelButtonConfig = $.fn.DataTable.ext.buttons.excelHtml5;
                    excelButtonConfig.exportOptions.columns = colunas_selecionadas();   //SETA QUAIS COLUNAS VAO SER EXPORTADAS
                    excelButtonConfig.orientation = customPageExport('table','orientation'),
                    excelButtonConfig.pageSize = customPageExport('table','pageSize'),
                    excelButtonConfig.action.call(dt.button(this), e, dt, button, excelButtonConfig);                                          
                }                    
            },                
            {
                extend: 'csvHtml5',
                messageTop: function() {
                    return $('#total').text() + ' | ' + $('#total_veiculos').text() + ' | <?= ucfirst(lang('cliente')) ?>: ' + $('#clientes option:selected').text() + ' | <?= ucfirst(lang('periodo')) ?>: ' + $('#dp1').val() + ' à ' + $('#dp2').val()
                },
                orientation: customPageExport('table','orientation'),
                pageSize: customPageExport('table','pageSize'),
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV',
                action: function(e, dt, button, config) {
                    var csvButtonConfig = $.fn.DataTable.ext.buttons.print;
                    csvButtonConfig.exportOptions.columns = colunas_selecionadas();      //SETA QUAIS COLUNAS VAO SER EXPORTADAS
                    csvButtonConfig.orientation = customPageExport('table','orientation'),
                    csvButtonConfig.pageSize = customPageExport('table','pageSize'),
                    csvButtonConfig.action.call(dt.button(this), e, dt, button, csvButtonConfig);
                }
            },
            {
                extend: 'print',
                messageTop: function() {
                    return $('#total').text() + ' | ' + $('#total_veiculos').text() + ' | <?= ucfirst(lang('cliente')) ?>: ' + $('#clientes option:selected').text() + ' | <?= ucfirst(lang('periodo')) ?>: ' + $('#dp1').val() + ' à ' + $('#dp2').val()
                },
                orientation: customPageExport('table','orientation'),
                pageSize: customPageExport('table','pageSize'),
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> <?= strtoupper(lang('imprimir')) ?>',
                action: function(e, dt, button, config) {
                    var printButtonConfig = $.fn.DataTable.ext.buttons.print;
                    printButtonConfig.exportOptions.columns = colunas_selecionadas();      //SETA QUAIS COLUNAS VAO SER EXPORTADAS
                    printButtonConfig.orientation = customPageExport('table','orientation'),
                    printButtonConfig.pageSize = customPageExport('table','pageSize'),
                    printButtonConfig.action.call(dt.button(this), e, dt, button, printButtonConfig);                        
                }

            }
        ],
        language: langDatatable
    });


    //esconde a mensagem caso apertem o botao de fechar
    $(document).on('click', '.close', function() {
        $(".alert_acao").css('display', 'none');
    });

    //esconde a mensagem caso apertem o botao de fechar
    $(document).on('click', '#btnFiltro', function() {
        if ($(this).attr('data-acao') === 'mostrar'){
            $("#filtro_colunas").css('display', 'block');
            $(this).attr('data-acao', 'esconder');
        }else{
            $("#filtro_colunas").css('display', 'none');
            $(this).attr('data-acao', 'mostrar');
        }
    });

    //gerencia os dias que o usuario pode escolher no calendario
    $(function() {
        var dateFormat = 'dd/mm/yy',
            from = $("#dp1")
            .datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                maxDate: new Date()
            })
            .on("change", function() {
                // from.datepicker( "option", "maxDate", getDate( this ) );
                to.datepicker("option", "minDate", getDate(this));
                var hoje = new Date();
                var dateAtual = new Date(hoje.getTime());
                var dataImput01 = getDate(this);
                var dataFinal = new Date(dataImput01.getTime() + (31 * 24 * 60 * 60 * 1000));
                if (dataFinal > dateAtual) {
                    dataFinal = dateAtual;
                }
                to.datepicker("option", "maxDate", dataFinal.getDate() + "/" + (dataFinal.getMonth() + 1) + "/" + dataFinal.getFullYear());
            }),
            to = $("#dp2").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                maxDate: new Date()
            })
            .on("change", function() {
                from.datepicker("option", "maxDate", getDate(this));
            });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }
            return date;
        }
    });

    function colunas_selecionadas() {
        var selecionados = [0];
        $('.item-filtro').each(function() {
            var status = true;
            if ($(this).is(':checked') == true) {
                selecionados.push($(this).val());
            }
        });
        return selecionados;
    }

    /*
    * AJUSTA O TAMANHO E ORIENTACAO DA PAGINA DO ARQUIVO EXPORTADO DEPENDENDO DA QUANTIDADE DE COLUNAS DA TABELA
    */
    function customPageExport(id_tabela, atributo='orientation'){
        var orientation, pageSize, count = 0;

        $('#'+id_tabela).find('thead tr:first-child th').each(function () {
            count++;
        });

        if(count > 10 && count <= 15) {
            orientation = 'landscape';
            pageSize = 'LEGAL';

        }else if(count > 15 && count <= 24){
            orientation = 'landscape';
            pageSize = 'A3';

        }else if(count > 24){
            orientation = 'landscape';
            pageSize = 'A2';

        }else{
            orientation = 'portrait';
            pageSize = 'LEGAL';
        }

        if (atributo === 'orientation'){
            return orientation;
        }else{
            return pageSize;
        }

        return false;
    }

        /* TRADUCAO DO PLUGIN DATAPICKET */
    jQuery(function($){
        $.datepicker.regional['pt-BR'] = {
            closeText: 'Fechar',
            prevText: '&#x3c;Anterior',
            nextText: 'Pr&oacute;ximo&#x3e;',
            currentText: 'Hoje',
            monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
            dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };

        $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
    });

</script>