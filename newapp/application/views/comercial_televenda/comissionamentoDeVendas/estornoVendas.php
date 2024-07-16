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

    th, td.wordWrap {
        max-width: 100px;
        word-wrap: break-word;
        text-align: center;
    }

    .select2-selection__rendered {
        line-height: 35px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
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
<h3><?=lang('estorno_vendas')?></h3>

<div id="loading">
    <div class="loader"></div>
</div>


<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('comissionamento_vendas')?>
</div>

<hr>

<div class="alert alert-info my-1 col-md-12" style="margin-right: 15px;">
    <p class ="col-md-12">Informe os dados a serem pesquisado</p>
        <div class="col-md-2">
            <label>Pesquisar por:</label>
            <select id="sel-pesquisa" class="form-control">
                <option value="0">Nome Cliente</option>
                <option value="1">Proprietário</option>
                <option value="3">AF</option>
            </select>
        </div>

    <form id="formPesquisa">
        <div class="row">
            <div id="pesquisaNome" class="col-md-3">
                <label>Nome Cliente: </label>
                <br>
                <select class="form-control nome" id="pesqNome" name="nome" type="text" style="width: 100%;"></select>
            </div>
            <div id="pesquisaProp" class="col-md-3">
                <label>Proprietário: </label>
                <select class="form-control prop" id="pesqProp" name="prop" type="text"></select>
            </div>
            <div id="pesquisaAF" class="col-md-3">
                <label>AF: </label>
                <input class="form-control af" id="pesqAF" name="af" type="text" />
            </div>
            <div class="col-md-2">
                <label>Data Inicial: </label>
                <input class="form-control" id="dataInicial" name="dataInicial" type="date" style="padding-bottom: 3px;">
            </div>
            <div class="col-md-2">
                <label>Data Final: </label>
                <input class="form-control" id="dataFinal" name="dataFinal" type="date" style="padding-bottom: 3px;">
            </div>
            <button class="btn btn-primary" id="pesquisaestorno" type="submit" style="margin-top: 25px; height: 33px;"><i class="fa fa-search" aria-hidden="true" style="font-size: 18px;"></i>  Pesquisar</button>
            <button class="btn btn-primary" id="BtnLimparPesquisa" type="button" style="margin-top: 25px; height: 33px; margin-left:10px;"><i class="fa fa-leaf" aria-hidden="true" style="font-size: 18px;"></i> Limpar</button>
        </div>
    </form>
</div>

<div class="container-fluid my-1 col-md-12">
    
    <div class="row ">
        <div class="col-md-12 subtitulo">
            <div style="float: left;">
                <button type="button" id="btnAddEstorno" class="btn btn-primary" style='float: left; position: relative; left: 15px'>Cadastrar Estorno</button>
            </div>
        </div>
    </div>
	<div class="col-md-12">
       
        <div id="estornos" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                			        	
                <table class="table-responsive table-bordered table-striped table-hover table" id="tabelaEstorno">
                    <thead>
                        <tr class="tableheader">
                        <th>Id</th>
                        <th>AF</th>
                        <th>Nome Cliente</th>
                        <th>Proprietário</th>
                        <th>Regional</th>
                        <th>Quantidade</th>
                        <th>Valor Vendas</th>
                        <th>Cenário</th>
                        <th type="date">Data Emissão</th>
                        <th type="date">Data de Cadastro</th>
                        <th type="date">Data de Atualização</th>
                        <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>    
            </div>
        </div>
	</div>
</div>


<div class="modal" id="modalEstorno" role="dialog" aria-labelledby="modalEstorno" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4 !important">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="modal-title" style="display: none" id="cadTitle">Cadastrar Estorno</h3>
                        <h3 class="modal-title" style="display: none" id="editTitle">Editar Estorno</h3>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form id="formEstorno" class="col-md-12">
                    <div class="row">
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">AF: </label>
                            <input type="text" class="form-control" id="af" name="af" placeholder="Digite a AF" required>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Regional: </label>
                            <select type="text" name="regional" id="regional" class="form-control" style="width: 100%;" required></select>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Quantidade: </label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Digite a quantidade" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Valor Vendas: </label>
                            <input type="text" class="form-control" id="valorVendas" name="valorVendas" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor" required>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Cliente: </label>
                            <select type="text" class="form-control" id="cliente" name="cliente" style="width: 100%;" required></select>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Data de Emissão: </label>
                            <input type="date" class="form-control formatInput data" id="dataEmissao" name="dataEmissao" required autocomplete="off"> 
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Cenário: </label>
                            <select name="cenario" id="cenario" class="form-control" style="width: 100%" required>
                            </select>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Proprietário: </label>
                            <select type="text" class="form-control" id="proprietario" name="proprietario" required></select>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-id="" id="btnSalvarEstorno">Salvar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right: 15px;" >Fechar</button>
                </div>
            </form>
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

    var tabelaEstorno = $('#tabelaEstorno').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        order: [10, 'desc'],       
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhuma devolução a ser listada",
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
        deferRender: true,
        lengthChange: false,
        ajax:{
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarEstornosTop100') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaEstorno.clear().draw();
                    tabelaEstorno.rows.add(data.results).draw();
                }else{
                    tabelaEstorno.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar estornos');
                tabelaEstorno.clear().draw();
            }
        },
        columns: 
        [               
            { data: 'id', visible: false},                
            { data: 'af'},
            { data: 'cliente'}, 
            { data: 'proprietario'},
            { data: 'regional'},
            { data: 'quantidade'},                
            {
                data: 'valorVendas',
                render: function (data) {
                    return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            },  
            { data: 'cenario'},
            {
                data: 'dataEmissao',
                render: function (data) {
                    var date = new Date(data);
                    date.setDate(date.getDate() + 1); // Adicionar 1 dia à data

                    return date.toLocaleDateString('pt-BR');
                }
            },
            {
                data: 'dataCadastro',
                render: function (data) {
                    var date = new Date(data);
                    date.setDate(date.getDate());

                    return date.toLocaleDateString('pt-BR');
                }
            },
            {
                data: 'dataUpdate',
                render: function (data) {
                    var date = new Date(data);
                    date.setDate(date.getDate()); 

                    return date.toLocaleDateString('pt-BR');
                }
            },
            {
                data: {'id':'id', 'af': 'af', 'regional': 'regional', 'quantidade':'quantidade', 'valorVendas':'valorVendas', 'cliente':'cliente', 'dataEmissao':'dataEmissao', 'cenario':'cenario', 'executivo':'executivo', 'proprietario':'proprietario'},
                orderable: false,
                render: function (data) {
                return `
                    <button 
                        class="btn btn-primary"
                        title="Editar Estorno"
                        data-id="${data.id}"
                        style="width: auto; margin: 0 auto;text-align: center;"
                        onclick="javascript:abrirEditEstorno(this, '${data['id']}', '${data['af']}', '${data['regional']}', '${data['quantidade']}', '${data['valorVendas']}', '${data['cliente']}', '${data['dataEmissao']}', '${data['cenario']}', '${data['executivo']}', '${data['proprietario']}')"
                        id="btnEditarEstorno"                            
                        >
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    `;
                }
            }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                filename: filenameGenerator("Estornos de Vendas"),
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                filename: filenameGenerator("Estornos de Vendas"),
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function ( doc , tes)
                {                
                    titulo = `Estornos de Vendas`;
                    // Personaliza a página do PDF
                    pdfTemplateIsolated(doc, titulo)
                }
            },
            {
                filename: filenameGenerator("Estornos de Vendas"),
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                filename: filenameGenerator("Estornos de Vendas"),
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function ( win )
                {
                    titulo = `Estornos de Vendas`;
                    // Personaliza a página Impressale
                    printTemplateOmni(win, titulo);
                }
            }
        ]
    });

    $(document).ready(async() => {
 
        $('.nome').select2({
            ajax: {
                url: '<?= site_url('clientes/ajaxListSelect') ?>',
                dataType: 'json',
                delay: 1000
            },
            placeholder: "Selecione o cliente",
            allowClear: true,
            minimumInputLength: 3,
            language: "pt-BR"
        });  
        const proprietarios = await preencherProprietariosSelect();
    });

    async function preencherProprietariosSelect(){
        let proprietario  = await $.ajax ({
                                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarProprietariosSelect2') ?>',
                                dataType: 'json',
                                delay: 1000,
                                type: 'GET',
                                data: function (params) {
                                    return {
                                        q: params.term
                                    };
                                },
                                error: function(){
                                    alert('Erro ao buscar proprietários, tente novamente');
                                }
                            });
                       
        $('.prop').select2({
            data: proprietario.results,
            placeholder: "Selecione o proprietário",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });

        $('.prop').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('.prop').val(null).trigger('change');
        
    }

    async function preencherProprietariosSelect(){
        let proprietario  = await $.ajax ({
                                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarProprietariosSelect2') ?>',
                                dataType: 'json',
                                delay: 1000,
                                type: 'GET',
                                data: function (params) {
                                    return {
                                        q: params.term
                                    };
                                },
                                error: function(){
                                    alert('Erro ao buscar proprietários, tente novamente');
                                }
                            });
                       
        $('.prop').select2({
            data: proprietario.results,
            placeholder: "Selecione o proprietário",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });

        $('.prop').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('.prop').val(null).trigger('change');
        
    }

    async function abrirEditEstorno(botao, id, af, regional, quantidade, valorVendas, cliente, dataEmissao, cenario, executivo, proprietario){
        $('#btnSalvarEstorno').data('id', id);
        $("#editTitle").show();
        $("#cadTitle").hide();
        document.getElementById('loading').style.display = 'block';

        await preencheCamposSelectModal();

        var optionsRegionais = document.getElementById('regional').options;
        var optionsCenarios = document.getElementById('cenario').options;
        var optionsVendedores = document.getElementById('proprietario').options;
        var optionClientes = document.getElementById('cliente').options;

        Array.from(optionsRegionais).forEach(function(option){
            if(option.text == regional){
                $('#regional').val(option.value).trigger('change');
            }
        });
        Array.from(optionsCenarios).forEach(function(option){
            if(option.text == cenario){
                $('#cenario').val(option.value).trigger('change');
            }
        });
        Array.from(optionsVendedores).forEach(function(option){
            if(option.text == proprietario){
                $('#proprietario').val(option.value).trigger('change');
            }
        });
        Array.from(optionClientes).forEach(function(option){
            if(option.text == cliente){
                $('#cliente').val(option.value).trigger('change');
            }
        });

        $('#af').val(af);
        $('#quantidade').val(quantidade);
        $('#valorVendas').val(valorVendas);
        $('#dataEmissao').val(dataEmissao);

        document.getElementById('loading').style.display = 'none';

        $("#modalEstorno").modal("show");
   
    }

    $('#formPesquisa').submit(function() {
        var data = {};
        var cliente = $('#pesqNome').find(":selected").text();
        var prop = $('#pesqProp').find(":selected").text();
        var af = $('#pesqAF').val();
        var dataInicial = $('#dataInicial').val();
        var dataInicialFormatada = dataInicial.split("-").reverse().join("/")
        var dataFinal = $('#dataFinal').val();
        var dataFinalFormatada = dataFinal.split("-").reverse().join("/");

        if ((!$('#pesqAF').prop('disabled')) && (af == '')) {
            alert("Preencha o campo de AF para realizar a pesquisa!");
            return false;
        }

        if ((!$('#pesqNome').prop('disabled')) && (cliente == '')) {
            alert("Preencha o campo de Cliente para realizar a pesquisa!");
            return false;
        }

        if ((!$('#pesqProp').prop('disabled')) && (prop == '')) {
            alert("Preencha o campo de Proprietário para realizar a pesquisa!");
            return false;
        }

        if (cliente) {
            data['cliente'] = cliente;
        }

        if (prop) {
            data['prop'] = prop;
        }

        if (af) {
            data['af'] = af;
        }

        if (dataInicial) {
            data['dataInicial'] = dataInicialFormatada;
            if (!dataFinal){
                alert("Preencha a data final para poder pesquisar por data!");
                return false;
            }
        }

        if (dataFinal) {
            data['dataFinal'] = dataFinalFormatada;
            if (!dataInicial){
                alert("Preencha a data inicial para poder pesquisar por data!");
                return false;
            }
        }

        $('#pesquisaestorno').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando');

        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/get_estornos') ?>',
            dataType: 'json',
            type: 'POST',
            data: data, 
            success: function(data){
                if (data.status === 200){
                    tabelaEstorno.clear().draw();
                    tabelaEstorno.rows.add(data.results).draw();
                }else{
                    alert("Não foi possível achar estorno com esses parâmetros! Tente novamente.")
                    tabelaEstorno.clear().draw();
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Erro ao buscar estornos. Tente novamente.");
                tabelaEstorno.clear().draw();
            },
            complete: function() {
                $('#pesquisaestorno').attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true" style="font-size: 18px;"></i> Pesquisar');
            }
        });

        return false;
    });

    $("#formEstorno").submit(function(e){
        e.preventDefault();
        var af = $("#af").val();
        var regional = $("#regional").find(":selected").text();
        var quantidade = $("#quantidade").val();
        var valorVendas = formatValorInserir($("#valorVendas").val());
        var cliente = $("#cliente").find(":selected").text();
        var dataEmissao = ($("#dataEmissao").val()).split("-").reverse().join("/");  
        var cenario = $("#cenario").find(":selected").text();
        var proprietario = $("#proprietario").find(":selected").text();
        var id = $("#btnSalvarEstorno").data('id');

        document.getElementById('loading').style.display = 'block';

        let url = `<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/adicionarEstorno') ?>`;

        if(id != "")
            url = `<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/atualizarEstorno') ?>`;

        $.ajax({
            url: url,
            type: "POST",   
            dataType: 'json',
            data: {
                id: id,
                af: af,
                regional: regional,
                quantidade: quantidade,
                valorVendas: valorVendas,
                dataEmissao: dataEmissao,
                cenario: cenario,
                proprietario: proprietario,
                cliente: cliente,
            },
            success: function(data){
                if(data && data['status'] == 200) {
                    if(id != ""){
                        alert("Atualizado com sucesso!");
                    }else{
                        alert("Cadastrado com sucesso!");
                    }
                    $("#modalEstorno").modal("hide");
                    tabelaEstorno.ajax.reload();

                }else if(data['status'] == 400 && data['results'] != null){
                    alert(data['results'][0]['erro'] + ". Campo: "+ data['results'][0]['campo']);
                }else if(data['status'] == 500 && data['results']){
                    alert(data['results']['titulo'] + ". "+ data['results']['mensagem']);
                }else{
                    alert("Ocorreu um problema ao enviar os dados.");
                }
            },
            error: function(error){
                alert("Ocorreu um problema ao enviar os dados.");
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });

    })
    
    $("#btnAddEstorno").click(async function(e){
        e.preventDefault();
        $("#editTitle").hide()
        $("#cadTitle").show()

        document.getElementById('loading').style.display = 'block';
        await preencheCamposSelectModal();

        document.getElementById('loading').style.display = 'none';

        $("#modalEstorno").modal("show");
    })

    async function preencheCamposSelectModal(){

        $('#cliente').select2({
            ajax: {
                url: '<?= site_url('clientes/ajaxListSelect') ?>',
                dataType: 'json',
                delay: 1000
            },
            placeholder: "Selecione o cliente",
            allowClear: true,
            minimumInputLength: 3,
            language: "pt-BR"
        });  

        let regionais  = await $.ajax ({
                            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarRegionaisSelect2') ?>',
                            dataType: 'json',
                            delay: 1000,
                            type: 'GET',
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            error: function(){
                                alert('Erro ao buscar regionais, tente novamente');
                                btn.attr('disabled', false).html('Cadastrar Estorno');
                            }
                        });
                       
        $('#regional').select2({
            data: regionais.results,
            placeholder: "Selecione a regional",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#regional').on('select2:select', function (e) {
            var data = e.params.data;
        });


        let cenario  = await $.ajax ({
                                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                        dataType: 'json',
                                        delay: 1000,
                                        type: 'GET',
                                        data: function (params) {
                                            return {
                                                q: params.term
                                            };
                                        },
                                        error: function(){
                                            alert('Erro ao buscar cenários de vendas, tente novamente');
                                            btn.attr('disabled', false).html('Cadastrar Estorno');
                                        }
                                    });
                       
        $('#cenario').select2({
            data: cenario.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#cenario').on('select2:select', function (e) {
            var data = e.params.data;
        });

        let vendedor  = await $.ajax ({
                                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendedoresSelect2') ?>',
                                        dataType: 'json',
                                        delay: 1000,
                                        type: 'GET',
                                        data: function (params) {
                                            return {
                                                q: params.term
                                            };
                                        },
                                        error: function(){
                                            alert('Erro ao buscar vendedores, tente novamente');
                                            btn.attr('disabled', false).html('Cadastrar Estorno');
                                        }
                                    });
                       
        $('#proprietario').select2({
            data: vendedor.results,
            placeholder: "Selecione o vendedor",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#proprietario').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#cenario').val(null).trigger('change');
        $('#regional').val(null).trigger('change');
        $('#proprietario').val(null).trigger('change');
        $('#cliente').val(null).trigger('change');
    }

    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});

    function formatarMoeda(campo) {
        var elemento = document.getElementById(campo);
        var valor = elemento.value;

        valor = valor.toString().replace(/\D/g, '');
        valor = (parseFloat(valor) / 100).toFixed(2).toString();
        valor = valor.replace('.', ',');

        if (valor.length > 6) {
            valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        }

        elemento.value = valor;
        if (valor == 'NaN') elemento.value = '';
    }

    function formatValorInserir(value) {
		value = value.replace('.', '');
		value = value.replace(',', '.');

		return value;
		
	}

    $('#pesquisaNome').show();
    $('#pesquisaProp').hide();
    $('#pesqProp').attr('disabled', true);
    $('#pesquisaAF').hide();
    $('#pesqAF').attr('disabled', true);

    $('#modalEstorno').on('hidden.bs.modal', function () {
        $('#formEstorno').trigger("reset");
        $('#regional').val(null).trigger('change');
        $('#cenario').val(null).trigger('change');
        $('#proprietario').val(null).trigger('change');
        $('#btnSalvarEstorno').data('id', '');
    });

    $('#sel-pesquisa').on('change', function() {
        let tipo = $('#sel-pesquisa').val();
        $('.nome').val(null).trigger('change');
        $('.prop').val(null).trigger('change');
        $('.af').val('');
        if (tipo == 0) {
            $('#pesquisaNome').show();
            $('#pesqNome').removeAttr('disabled');
            $('#pesquisaProp').hide();
            $('#pesqProp').attr('disabled', true);
            $('#pesquisaAF').hide();
            $('#pesqAF').attr('disabled', true);
        } else if (tipo == 1) {
            $('#pesquisaProp').show();
            $('#pesqProp').removeAttr('disabled');
            $('#pesquisaAF').hide();
            $('#pesqAF').attr('disabled', true);
            $('#pesquisaNome').hide();
            $('#pesqNome').attr('disabled', true);
        } else {
            $('#pesquisaAF').show();
            $('#pesqAF').removeAttr('disabled');
            $('#pesquisaNome').hide();
            $('#pesqNome').attr('disabled', true);
            $('#pesquisaProp').hide();
            $('#pesqProp').attr('disabled', true);
        }
    });

    $('#BtnLimparPesquisa').on('click', function(e){
        e.preventDefault()
        var btnLimpar = $(this);
        btnLimpar.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Limpando...');

        $('.nome').val(null).trigger('change');
        $('.prop').val(null).trigger('change');
        $('.af').val('');
        $('#dataInicial').val('');
        $('#dataFinal').val('');

        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarEstornosTop100') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaEstorno.clear().draw();
                    tabelaEstorno.rows.add(data.results).draw();
                }else{
                    tabelaEstorno.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar estornos');
                tabelaEstorno.clear().draw();
            },
            complete: function() {
                btnLimpar.attr('disabled', false).html('<i class="fa fa-leaf" aria-hidden="true" style="font-size: 18px;"></i> Limpar');
            }
        });

        
    });

</script>

