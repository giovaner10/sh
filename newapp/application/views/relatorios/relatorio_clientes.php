<!-- bibliotecas usadas apenas nessa pagina -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/select2.css">

<script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js">
</script>

<script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
</script>

<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js">
</script>
<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js">
</script>
<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js">
</script>
<script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
</script>

<!-- datapicket -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- agrupar -->
<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js">
</script>


<style>
html {
        scroll-behavior:smooth
    }

    body {
        background-color: #fff !important;
    }
    
    table {
        width: 100% !important;
    }
    .blem{
        color: red;
    }

    .container-fluid {
        padding: 0;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th, td.wordWrap {
        max-width: 100px;
        word-wrap: break-word;
        text-align: center;
    }

    .checkbox label {
        font-weight: 700;
    }
    .select-container .select-selection--single{
        height: 35px !important;
    }

    .my-1 {
        margin-top: 1em !important;
        margin-bottom: 1em !important;
    }

    .mx-1 {
        margin-left: 1em;
        margin-right: 1em;
    }

    .pt-1 {
        padding-top: 1em;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }
    
    .justify-content-end {
        justify-content: flex-end;
    }
    .align-center {
        align-items: center;
    }

    .modal-xl {
        max-width: 1300px;
        width: 100%;
    }

    .border-0 {
        border: none !important;
    }
    .markerLabel {
        background-color: #fff;
        border-radius: 4px;
        padding: 4px;
    }

    .action-bar * {
        margin-left: 5px;
    }
    .select-selection--multiple .select-search__field{
        width:100%!important;
    }
</style>

<h3><?php echo $titulo; ?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('relatorios')?> >
	<?=lang('clientes')?> >
    <?=lang('relatorio_clientes_publicos')?>
</div>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a id="tab-relatorioClientesContrato" data-toggle="tab" href="" class="nav-link active">Clientes-Contratos</a>
    </li>
    <li class="nav-item">
        <a id="tab-relatorioClientes" data-toggle="tab" href="" class="nav-link">Clientes</a>
    </li>
</ul>

<div class="container-fluid my-1" id="divRelatorioClientesContratos">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <form class="alinhar_horizontal" id="formGerarResult" action="<?=site_url('relatorios/ajax_clientes')?>" method="get" accept-charset="utf-8">
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label>Prestadora: </label>
                        <select class="form-control input-sm" name="prestadora" id="prestadora" required>
                            <option value="" disabled selected>Selecione a Prestadora</option>
                            <option value="NORIO">Norio</option>
                            <option value="TRACKER">Show Tecnologia</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Status do Contrato: </label>
                        <select class="form-control input-sm" name="statusContrato" id="statusContrato" >
                            <option value="" disabled selected>Selecione o Status</option>
                            <option value="0">Cadastrado</option>
                            <option value="1">Esperando OS</option>
                            <option value="2">Ativo</option>
                            <option value="3">Cancelado</option>
                            <option value="5">Bloqueado</option>
                            <option value="6">Encerrado</option>
                            <option value="7">Cancelamento em Processo de Retirada</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Status do Cliente: </label>
                        <select class="form-control input-sm" name="status" id="status" >
                            <option value="" disabled selected>Selecione o Status</option>
                            <option value="0">Bloqueado</option>
                            <option value="1">Ativo</option>
                            <option value="2">Prospectado</option>
                            <option value="3">Em teste</option>
                            <option value="4">A reativar</option>
                            <option value="5">Inativo</option>
                            <option value="6">Bloqueio parcial</option>
                            <option value="7">Negativado</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Órgão: </label>
                        <select class="form-control input-sm" name="orgao" id="orgao" >
                            <option value="" disabled selected>Selecione o Órgão</option>
                            <option value="">Todos</option>
                            <option value="publico">Público</option>
                            <option value="privado">Privado</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Cliente: </label>
                        <select class="form-control input-sm" name="idCliente" id="clientes" style="width: 100%;">
                            <option value="" disabled selected>Selecione o Cliente</option>
                        </select>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-4 form-group">
                        <button class="btn" id="resetCampos" type="reset" style="background-color: red;color: white">Limpar</button>
                        <button type="submit" class="btn btn-primary gerar_rel">Gerar</button>
                        <button class="btn btn-warning" id="btnFiltro" style="padding: 3.9px;" data-acao="mostrar" type="button">
                            <i class="fa fa-cogs"></i> <?= lang('filtro') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="filtro_colunas" class="collaps row">
        <br>
        <div class="alert alert-info">
            <?= lang('info_filtro_relatorio'); ?>
        </div>
        <h5><?= lang('informacoes'); ?>:</h5>        
        <div style="margin-bottom: 5px;">
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="0" checked><?= lang('id_cliente'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="1" checked><?= lang('cliente'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="2" checked><?= lang('cpf_cnpj'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="3" checked><?= lang('status_cliente'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="4" checked><?= lang('contrato'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="5" checked><?= lang('itens'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="6" checked><?= lang('itens_ativos'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="7" checked><?= lang('status_contrato'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="8" checked><?= lang('mensalidade'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="9" checked><?= lang('instalacao'); ?></input></label>
            <label class="checkbox-inline"><input class="item-filtro" type="checkbox" value="10" checked><?= lang('fim_aditivo'); ?></input></label>

        </div>
    </div>
    <hr>
    <div class="alert_acao row" style="display:none">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <span id="mensagem"></span>
    </div>

    <div class="row">
        <h4><span id="total_veiculos"></span></h4>
        <div class="col-md-12">
            <table id="table_cli" class="display table responsive table-striped table-bordered">
    			<thead>
                    <th>Id Cliente</th>
                    <th>Cliente</th>
                    <th>Cpf/Cnpj</th>
                    <th>Status Cliente</th>
                    <th>Contrato</th>
                    <th>Faturamento</th>
                    <th>Itens</th>
                    <th>Itens Ativos</th>
                    <th>Valor Unitário</th>
                    <th>Valor Contratado</th>
                    <th>Valor Total</th>
                    <th>Status Contrato</th>
                    <th>Mensalidade</th>
                    <th>Instalação</th>
                    <th>Fim de Aditivo</th>
    			</thead>
    			<tbody>
    			</tbody>
    		</table>
        </div>
    </div>
</div>

<div class="container-fluid my-1" id="divRelatorioClientes" style="display: none;">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <form id="formRelatorioClientes">
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label>Prestadora: </label>
                        <select class="form-control input-sm" name="prestadoraCliente" id="prestadoraCliente" required>
                            <option value="" disabled selected>Selecione a Prestadora</option>
                            <option value="NORIO">Norio</option>
                            <option value="TRACKER">Show Tecnologia</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Status do Contrato: </label>
                        <select class="form-control input-sm" name="statusContratoClientes" id="statusContratoClientes" >
                            <option value="" disabled selected>Selecione o Status</option>
                            <option value="0">Cadastrado</option>
                            <option value="1">Esperando OS</option>
                            <option value="2">Ativo</option>
                            <option value="3">Cancelado</option>
                            <option value="5">Bloqueado</option>
                            <option value="6">Encerrado</option>
                            <option value="7">Cancelamento em Processo de Retirada</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Status do Cliente: </label>
                        <select class="form-control input-sm" name="statusCliente" id="statusCliente">
                            <option value="" disabled selected>Selecione o Status</option>
                            <option value="0">Bloqueado</option>
                            <option value="1">Ativo</option>
                            <option value="2">Prospectado</option>
                            <option value="3">Em teste</option>
                            <option value="4">A reativar</option>
                            <option value="5">Inativo</option>
                            <option value="6">Bloqueio parcial</option>
                            <option value="7">Negativado</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Órgão: </label>
                        <select class="form-control input-sm" name="orgaoCliente" id="orgaoCliente">
                            <option value="" disabled selected>Selecione o Órgão</option>
                            <option value="">Todos</option>
                            <option value="publico">Público</option>
                            <option value="privado">Privado</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <button class="btn" id="resetarCampos" type="reset" style="background-color: red;color: white">Limpar</button>
                        <button type="submit" class="btn btn-primary">Gerar</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabelaRelatorioClientes" class="display table responsive table-striped table-bordered">
    	            		<thead>
                                <th>Id Cliente</th>
                                <th>Nome</th>
                                <th>Documento</th>
                                <th>Prestadora</th>
                                <th>Órgão</th>
                                <th>Data de Cadastro</th>
                                <th>Quantidade Contratos</th>
                                <th>Veiculos Contratados</th>
                                <th>Veiculos Ativos</th>
                                <th>Valor Contratado</th>
                                <th>Valor Total</th>
                                <th>Valor Instalação</th>
    	            		</thead>
    	            		<tbody>
    	            		</tbody>
    	            	</table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
    var table = $('#table_cli').DataTable( {
        dom: 'Bfrtip',
        paging: true,
        responsive: true,
        "columnDefs": [
            {
                "className": "dt-center",
                "targets": "_all"
            }
        ],
        order: [[8, 'asc']],
        rowGroup: {
            dataSrc: function ( row ) {
                var base = Math.floor(row[8] / 10);
                return "" + base + "0 - " + base + '9';
            }
        },
        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL',
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-text-o"></i> CSV',
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',

            }
        ],
        oLanguage: {
            "decimal": ",",
            "thousands": ".",
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });

    $('form#formGerarResult').submit(function () {
        var data_get = $(this).serialize();
        // $('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');
        $('.gerar_rel').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');
        $.getJSON('<?=  site_url('relatorios/ajax_clientes') ?>', data_get, function(data) {
            if (data.status == 'OK') {
                $('.alert_acao').css('display', 'none');

                // Atualiza Tabela
                table.clear();
                table.rows.add(data.tabela);
                table.draw();

            } else {
                table.clear();
                table.draw();
                $('.alert_acao').css('display', 'block');
                $("#mensagem").html('<div class="alert alert-danger"><p><b>'+data.msg+'</p></div>');
            }

            // Ativa e troca html button "GERAR"
            $('.gerar_rel').removeAttr('disabled').html('Gerar');
        });

        return false;
    });

    let tabelaRelatorioClientes = $('#tabelaRelatorioClientes').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        search: {
            smart: false
        },
        info: true,
        order: [1, 'asc'],
        language: {
        loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum cliente a ser listado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
        deferRender: false,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL',
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function (doc, tes)
                {
                    const titulo = `Relatório de Clientes`;
                
                    pdfTemplate(doc, titulo, 'A4');
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-text-o"></i> CSV',
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function (win)
                {
                    titulo = `Relatório de Clientes`;
                    printTemplate(win, titulo);
                },
            }
        ],
        columnDefs:[
            {
                targets: [0, 1, 2, 3, 4, 5, 6],
                className: 'dt-center',
            }
        ]

    })

    $('#tab-relatorioClientesContrato').click();

    $('#tab-relatorioClientesContrato').click(function() {
        $('#divRelatorioClientesContratos').css('display', 'block');
        $('#divRelatorioClientes').css('display', 'none');
        if (table){
            table.columns.adjust().draw();
        }
    });

    $('#tab-relatorioClientes').click(function() {
        $('#divRelatorioClientesContratos').css('display', 'none');
        $('#divRelatorioClientes').css('display', 'block');
        if (tabelaRelatorioClientes){
            tabelaRelatorioClientes.columns.adjust().draw();
        }
    });

    $('#formRelatorioClientes').submit(function (e){
        e.preventDefault();
        var dados = $(this).serialize();
        var botao = $(this).find('button[type=submit]');
        botao.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');

        $.ajax({
            url: '<?= site_url('relatorios/ajax_clientesRel') ?>',
            type: "GET",
            data: dados,     
			processData: false,
			contentType: false,  
            dataType : 'JSON',
            success: function(data){
                if (data.status ==  200){
                    tabelaRelatorioClientes.clear();
                    tabelaRelatorioClientes.rows.add(data.tabela);
                    tabelaRelatorioClientes.draw();
                    tabelaRelatorioClientes.columns.adjust().draw();
                }else{
                    alert(data.msg);
                    tabelaRelatorioClientes.clear();
                    tabelaRelatorioClientes.columns.adjust().draw();
                }
                botao.removeAttr('disabled').html('Gerar');
            },
            error: function(error){
                alert("Ocorreu um problema ao Gerar.");
                botao.removeAttr('disabled').html('Gerar');
            },
            
        });  

    })


    
});

$(document).ready(function (){
    $('#clientes').select2({
        ajax: {
            url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
            dataType: 'json'
        },
        placeholder: "Selecione o cliente",
        language: "pt-BR",
        allowClear: true,
        minimumInputLength: 3,

    });
});

$('#resetCampos').click(function() {
    $('#prestadora').val(null).trigger('change');
    $('#status').val(null).trigger('change');
    $('#orgao').val(null).trigger('change');
    $('#clientes').val(null).trigger('change');
    $('#status_contrato').val(null).trigger('change');
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
            $('#table_cli').DataTable().columns($(this).val()).visible(status).draw();
        });

        //Mostra apenas as colunas padroes
        $('.item-filtro').each(function() {
            var status = true;
            if ($(this).is(':checked') == false) {
                status = false;                
            }
            $('#table_cli').DataTable().columns($(this).val()).visible(status).draw();
        });

    });
</script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>