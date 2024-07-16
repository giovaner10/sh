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
    .bord{
        border-left: 3px solid #03A9F4;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th, td {
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
    
    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
</style>

<div class="alert alert-info col-md-12">
    <div class ="col-md-12">
        <p class ="col-md-12" style="padding-left: 0;">Selecione o <u>Tipo de Pesquisa</u> e informe os dados para localizar o pedido gerado desejado e clique em pesquisar.</p>
        <span class="help help-block"></span>
        <form action="post" id="formPesquisa">
            <div class="col-md-2" style="padding-left: 0;">
                <label class="col-md-12" style="margin: 0; padding: 0;">Pesquisar por:</label>
                <select type="text" id="sel-pesquisa" class="form-control">
                    <option value="0">Número de Pedido</option>
                    <option value="1">Nota Fiscal</option>
                </select>
            </div>
            <div id="pesquisaNumPedido" class="form-group col-md-2" style="padding-left: 0px;">
                <label id="labelPedido" class="col-md-12" style="margin: 0; padding: 0;">Número de Pedido: </label>
                <input placeholder="Digite o Número do Pedido..." class="form-control numPedido" type="text" id="pesqNumPedido" name="numPedido" type="text"/>
            </div>
            <div id="pesquisanotaFiscal" class="form-group col-md-2" style="padding-left: 0px;">
                <label id="labelDocumento" class="col-md-12" style="margin: 0; padding: 0;">Nota Fiscal: </label>
                <input placeholder="Digite o Número da nota fiscal..." class="form-control notaFiscal" id="pesqnotaFiscal" name="notaFiscal" type="text"/>
            </div>
            <div class="form-group col-md-4" style="margin-bottom: 0; padding: 0;">
                <button class="btn btn-primary col-md-4" id="pesquisaId" type="submit" style="margin-top: 20px; margin-right: 15px; padding: 6px;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                <button class="btn btn-primary col-md-4" id="BtnLimparPesquisar" type="button" style="margin-top: 20px; padding: 6px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
            </div>  
        </form>
    </div>
</div>

<div class="container-fluid my-1">

	<div class="col-sm-12" style="padding: 0;">
       
        <div id="estornos" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                			        	
                <table class="table-responsive table-bordered table-striped table-hover table" id="tabelaPedidosGeradosNFBiExpedicao">
                    <thead>
                        <tr class="tableheader">
                        <th>Filial</th>
                        <th>Número</th>
                        <th>Nota</th>
                        <th>Série</th>
                        <th>Romaneio</th>
                        <th>Código transação</th>
                        <th>Nome Transportadora</th>
                        <th type="date">Data Expedição</th> 
                        <th type="date">Data de Atualização</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>    
            </div>
        </div>
	</div>
</div>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<!-- Masks -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
<script>

    $.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss' );
    $.fn.dataTable.moment('DD/MM/YYYY');

    var tabelaPedidosGeradosNFBiExpedicao = $('#tabelaPedidosGeradosNFBiExpedicao').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        order: [[8, 'desc']],
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum registro encontrado.",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum registro encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
        ajax:{
            url: '<?= site_url('levantamentoPedidos/listarPedidosGeradosComNFBiExpedicao') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaPedidosGeradosNFBiExpedicao.clear().draw();
                    tabelaPedidosGeradosNFBiExpedicao.rows.add(data.results).draw();
                }else{
                    alert('Erro ao buscar pedidos gerados.');
                    tabelaPedidosGeradosNFBiExpedicao.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar pedidos gerados. Tente novamente!');
                tabelaPedidosGeradosNFBiExpedicao.clear().draw();
            }
        },
        dom: 'Bfrtip',
        columnDefs: [
            {className: "dt-center", targets: "_all"},
        ],
        columns: 
        [               
            { data: 'filial'},
            { data: 'numPedido'},
            { data: 'notaFiscal'},
            { data: 'serie'},
            { data: 'romaneio'},
            { data: 'codigoTransacao'},
            { data: 'nomeTransportadora'},
            {
                data: 'dataExpedicao',
                render: function (data) {
                    if (data) {
                        var formattedDate = moment(data, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY');
                        return formattedDate;
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'dataUpdated',
                render: function (data) {
                    if (data) {
                        var formattedDate = moment(data, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY');
                        return formattedDate;
                    } else {
                        return '';
                    }
                }
            },
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function ( doc , tes)
                {                
                    titulo = `Levantamento de Pedidos`;
                    widths = ['10%', '10%', '10%', '5%', '10%', '10%', '10%', '15%' ,'15%'];
                    pdfTemplateIsolated(doc, titulo, 'A4', widths, [], '', 16, 14)
                }
            },
            {
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function ( win )
                {
                    titulo = `Levantamento de Pedidos`;
                    printTemplateOmni(win, titulo);
                }
            }
        ]
    });

    $(document).ready(function () {
        $('#pesquisanotaFiscal').hide();
        $('#pesqnotaFiscal').attr('disabled', true);
        alterarTextoComBaseNaTela()
    });
    
    function alterarTextoComBaseNaTela() {
        var larguraTela = $(window).width();
        var valorX = 1024;
        var labelPedido = $('#labelPedido');
        var labelDocumento = $('#labelDocumento');
        var inputPedido = $('#pesqNumPedido');
        var inputDocumento = $('#pesqnotaFiscal');
        var optionPedido = $('#sel-pesquisa option[value="0"]');
        var optionDocumento = $('#sel-pesquisa option[value="1"]');
        // Verificar se a largura da tela é menor que o valor X
        if (larguraTela <= valorX) {
            // Modificar o texto se a condição for atendida
            labelPedido.text('N° do Pedido');
            labelDocumento.text('N° da Nota Fiscal');
            inputPedido.attr('placeholder', 'Digite o N° do Pedido...');
            inputDocumento.attr('placeholder', 'Digite o N° da nota fiscal...');
            optionPedido.text('N° do Pedido');
            optionDocumento.text('N° do Documento');
        } else {
            // Restaurar o texto padrão se a condição não for atendida
            labelPedido.text('Número do Pedido');
            labelDocumento.text('Número da Nota Fiscal');
            inputPedido.attr('placeholder', 'Digite o Número do Pedido...');
            inputDocumento.attr('placeholder', 'Digite o Número da nota fiscal...');
            optionPedido.text('Número do Pedido');
            optionDocumento.text('Número da Nota Fiscal');
        }
    }
    
    $(window).resize(alterarTextoComBaseNaTela);
    $('#sel-pesquisa').on('change', function() {
        let tipo = $('#sel-pesquisa').val();
        $('#pesqNumPedido').val('');
        $('#pesqnotaFiscal').val('');
        if (tipo == 0) {
            $('#pesquisaNumPedido').show();
            $('#pesqNumPedido').attr('disabled', false);
            $('#pesquisanotaFiscal').hide();
            $('#pesqnotaFiscal').attr('disabled', true);
        } else if (tipo == 1) {
            $('#pesquisaNumPedido').hide();
            $('#pesqNumPedido').attr('disabled', true);
            $('#pesquisanotaFiscal').show();
            $('#pesqnotaFiscal').attr('disabled', false);
        } else {
            $('#pesquisaNumPedido').hide();
            $('#pesqNumPedido').attr('disabled', true);
            $('#pesquisanotaFiscal').hide();
            $('#pesqnotaFiscal').attr('disabled', true);
        }
    });
    $('#formPesquisa').submit(event => {
        event.preventDefault();
        var numPedido = $('#pesqNumPedido').val();
        var notaFiscal = $('#pesqnotaFiscal').val();
        if ((!$("#pesqNumPedido").prop("disabled")) && (numPedido == '')) {
            alert("Informe o número do pedido e tente novamente!")
            return;
        }
        if ((!$("#pesqnotaFiscal").prop("disabled")) && (notaFiscal == '')) {
            alert("Informe o número da nota fiscal e tente novamente!")
            return;
        }
        
        $("#pesquisaId").html('<i class="fa fa-spinner fa-spin"></i>  Pesquisando...');
        $("#pesquisaId").attr("disabled", true);
        $.ajax({
            url: '<?= site_url('levantamentoPedidos/buscarPedidosGeradosNFAmarraBIExpedicao'); ?>',
            type: 'POST',
            data: {
                "numPedido": numPedido,
                "notaFiscal": notaFiscal,
            },
            dataType: 'json',
            beforeSend: function () {
                $('#tabelaPedidosGeradosNFBiExpedicao > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data) {
                if (data.status === 200){
                    tabelaPedidosGeradosNFBiExpedicao.clear().draw();
                    tabelaPedidosGeradosNFBiExpedicao.rows.add(data.results).draw();
                }else{
                    alert('Não foi possível encontrar nenhum pedido com esse número. Tente novamente!');
                    tabelaPedidosGeradosNFBiExpedicao.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar pedidos gerados. Tente novamente!');
                tabelaPedidosGeradosNFBiExpedicao.clear().draw();
            },
            complete: function() {
                $("#pesquisaId").attr("disabled", false);
                $('#pesquisaId').html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
            }
        });
    });
    $('#BtnLimparPesquisar').click(function (e){
        e.preventDefault();
        $('#pesqNumPedido').val('');
        $('#pesqnotaFiscal').val('');
        $.ajax({
            url: '<?= site_url('levantamentoPedidos/listarPedidosGeradosComNFBiExpedicao'); ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $("#BtnLimparPesquisar").html('<i class="fa fa-spinner fa-spin"></i>  Limpando...');
                $("#BtnLimparPesquisar").attr("disabled", true);
                $('#tabelaPedidosGeradosNFBiExpedicao > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data) {
                if (data.status === 200){
                    tabelaPedidosGeradosNFBiExpedicao.clear().draw();
                    tabelaPedidosGeradosNFBiExpedicao.rows.add(data.results).draw();
                }else{
                    alert('Erro ao limpar pedidos gerados. Tente novamente!');
                    tabelaPedidosGeradosNFBiExpedicao.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao limpar pedidos gerados. Tente novamente!');
                tabelaPedidosGeradosNFBiExpedicao.clear().draw();
            },
            complete: function() {
                $("#BtnLimparPesquisar").html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar');
                $("#BtnLimparPesquisar").attr("disabled", false);
            }
        });
    });

</script>
