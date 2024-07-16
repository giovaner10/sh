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
<h3><?=lang('vendas_comissionadas')?></h3>

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

<div class="alert alert-info col-md-12" style="margin-bottom: 50px;">    
    <div class ="col-md-12">
        <p class ="col-md-12">Informe a Data a ser pesquisada</p>
        <span class="help help-block"></span>
        <form action="" id="formBusca">
            <div class="form-group col-md-5">
                <div class="col-md-6 data-pesquisa">
                    <label for="">Data Inicial:</label>
                    <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value=""/> 
                </div>

                <div class="col-md-6 data-pesquisa">
                    <label for="">Data Final:</label>
                    <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value=""/>
                </div>
            </div>
            <div class="col-md-4">
            </div> 
            <div class="form-group col-md-3">
                <div class="col-md-12">
                    <button class="btn btn-primary col-md-5" id="BtnPesquisar" type="button" style="margin-top: 20px"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                    <div class="col-md-1">
                    </div> 
                    <button class="btn btn-primary col-md-5" id="BtnLimparPesquisar" type="button" style="margin-top: 20px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                </div> 
            </div>  
        </form>
    </div>
</div>
<hr>
<div class="container-fluid my-1">
    
    <div class="row ">
        <div class="col-md-12 subtitulo">
            <div style="float: left;">
                <button type="button" id="btnAddVenda" class="btn btn-primary" style='float: left; position: relative; left: 20px'>Cadastrar Venda</button>
            </div>
        </div>
    </div>
	<div class="col-sm-12">
       
        <div id="vendas" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                			        	
                <table class="table-responsive table-bordered table-striped table-hover table" id="tabelaVenda">
                    <thead>
                        <tr class="tableheader">
                        <th>Id</th>
                        <th>AF</th>
                        <th>Quantidade</th>
                        <th>Cliente</th>
                        <th type="date">Data Criação</th>
                        <th type="date">Data Fechamento</th>
                        <th>Vendedor</th>
                        <th>Total Hw</th>
                        <th>Total Lu</th>
                        <th>Cenario</th>
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


<div class="modal" id="modalVenda" role="dialog" aria-labelledby="modalVenda" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4 !important">
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="modal-title" style="display: none" id="cadTitle">Cadastrar Venda</h3>
                        <h3 class="modal-title" style="display: none" id="editTitle">Editar Venda</h3>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <form id="formVenda" class="col-md-12">
                    <input type="text" id="id" name="id" style="display: none">
                    
                    <div class="row">
                        <div id ="divBuscarPor" class="col-md-2 col-sm-2 form-group"  style="border-left: 3px solid #03A9F4" >
                            <label>Pesquisar por:</label>
                            <select class="form-control input-sm" name="buscar" id="tipo-busca-expedicao">
                                <option value="0">Id</option>
                                <option value="1">Nome</option>
                                <option value="2">Documento</option>
                            </select>
                        </div>
                        <div id ="divNomeCliente" class="col-md-10 col-sm-6" style="border-left: 3px solid #03A9F4;">
                            <label>Cliente:</label>
                            <select class="form-control input-sm" name="cliente" id="cliente-expedicao" type="text" style="width: 100%;">
                            </select>
                        </div>
                        <div id ="divDocCliente" class="col-md-8 col-sm-6 bord" hidden>
                            <label>Cliente:</label>
                            <input class="form-control input-sm" name="cliente" id="cliente-expedicaoDoc" type="text" placeholder="Digite o CPF/CNPJ do cliente">
                            </input>
                        </div>
                        <div id ="divIdCliente" class="col-md-8 col-sm-6 bord" style="display: none;">
                            <label>Cliente:</label>
                            <input class="form-control input-sm" name="cliente" id="cliente-expedicaoId" type="text" placeholder="Digite o ID do cliente">
                            </input>
                        </div>
                        <div id ="divBtnPesquisaCliente" class="col-md-2 col-sm-2" style="display: contents;" hidden>
                            <button id="btnPesquisaClienteDoc" class="btn btn-primary" type="button" style="margin-top: 23px;">Buscar</button>
                            <button id="btnLimparPesquisaClienteDoc" class="btn btn-danger" type="button" style="margin-top: 23px;">Limpar</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Af </label>
                            <input type="text" class="form-control" id="af" name="af" required>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Qtd </label>
                            <input type="text" class="form-control" id="qtd" name="qtd" required>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Vendedor </label>
                            <input type="text" class="form-control" id="vendedor" name="vendedor" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>

                    <div class="row">
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Data Criacao </label>
                            <input type="date" class="form-control formatInput data" id="dataCriacao" name="dataCriacao" required>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Data Fechamento </label>
                            <input type="date" class="form-control formatInput data" id="dataFechamento" name="dataFechamento" required>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Cenario </label>                            
                            <select name="cenario" id="cenario" class="form-control" required placeholder="Selecione um Cenario">
                                <option value="" disabled selected >Selecione um Cenario</option>
                            </select>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    
                    <div class="row">

                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Total Hw </label>
                            <input type="text" class="form-control" id="totalHw" name="totalHw" required>
                        </div>
                        <div class="col-md-4" style="border-left: 3px solid #03A9F4">
                            <label>Total Lu </label>
                            <input type="text" class="form-control" id="totalLu" name="totalLu" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                </form>
                <span class="help help-block"></span>
                <div class="row"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit-form">Salvar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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

<script>
	$(document).ready( function() {
        preencherEstados();
        
        $("#ufCliente").change(function(e){
            e.preventDefault();
            var id = $("#ufCliente option:selected").data("id");
            preencherCidades(id);
        })

        $('#produto').change(async function() {
            await povoarPlanos();     
        })
        
        $('#tipoPagto').change(function() {
            let selectcondicao_pagamento = document.getElementById('condPagto');

            var valor = $('#tipoPagto').find(':selected').attr('tz_codigo_erp');

            var site_url = "<?= site_url() ?>";
            fetch(site_url + '/ComerciaisTelevendas/ComissionamentoDeVendas/pegar_condicaoPagamentoCRM?value=' + valor)

                .then(response => {
                    return response.text();
                })
                .then(texto => {
                    selectcondicao_pagamento.innerHTML = texto;
                });
        })

        $('#btnAddVenda').click(async function() {
            await povoarCenarios().then(e => {
                $("#cenario").select2({
                    width: '100%',
                    placeholder: "Selecione o Cenário",
                    allowClear: true
                })
                $("#cenario").find('option').get(0).remove()
                $("#cenario").prepend(
                    `<option selected disabled value="">Selecione um cenário de venda</option>`)
            })
        })

    });

    var tabelaVenda = $('#tabelaVenda').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        order: [4, 'desc'],           
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhuma campanha a ser listada",
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
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendas') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaVenda.clear().draw();
                    tabelaVenda.rows.add(data.results).draw();
                }else{
                    tabelaVenda.clear().draw();
                }
            },
            error: function(data){
                alert('Erro ao buscar vendas');
                tabelaVenda.clear().draw();
            }
        },
        columns: 
        [               
            { data: 'id', visible: false},         
            { data: 'af'},
            { data: 'qtd'},
            { data: 'cliente'},
            { 
                data: 'dataCriacao',
                render: function (data) {
                    var date = new Date(data);

                    return date.toLocaleDateString('pt-BR');
                }
            },
            { 
                data: 'dataFechamento',
                render: function (data) {
                    var date = new Date(data);
                    date.setDate(date.getDate());

                    return date.toLocaleDateString('pt-BR');
                }
            },
            { data: 'vendedor'},
            { 
                data: 'totalHw',
                render: function (data) {
                    return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            },
            { 
                data: 'totalLu',
                render: function (data) {
                    return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            },
            { data: 'cenario'},
            {
                data: null,
                orderable: false,
                render: function (data) {
                return `
                    <button 
                        class="btn btn-primary"
                        title="Editar Venda"
                        data-id="${data.id}"
                        style="width: auto; margin: 0 auto;text-align: center;"
                        onclick="javascript:abrirModalVenda(this)"
                        id="btnEditarVenda"                            
                        >
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    `;
                }
            }
        ]
    });

    function abrirModalVenda(botao){
        let id = $(botao).attr('data-id');
        $("#editTitle").show()
        $("#cadTitle").hide()
        document.getElementById('loading').style.display = 'block';

        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarVenda') ?>?id="+id,
            type: "GET",   
            dataType: 'json',
            success: function(data){
                if(data && data['status'] == 200 && data['results'].length > 0) {
                    $("#modalVenda").modal("show");
                    preencherModalVenda(data['results'][0]);
                }else{
                    alert("Ocorreu um problema ao Buscar os Dados.");
                }
            },
            error: function(error){
                alert("Ocorreu um problema ao Buscar os Dados.");
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });  
    }

    function preencherModalVenda(venda){
        $("#id").val(venda['id']);
        $("#cotacao").val(venda['cotacao']);
        $("#af").val(venda['af']);
        
        var dataAf = new Date(venda['dataAf']);
        dataAf.setDate(dataAf.getDate());
        $("#dataAf").val(dataAf.toLocaleDateString('pt-BR'));

        $("#produto").val(venda['produto']);
        $("#licenca").val(venda['licenca']);
        $("#satelital").val(venda['satelital']);
        $("#qtd").val(venda['qtd']);
        $("#cliente").val(venda['cliente']);
        $("#feelingVendedor").val(venda['feelingVendedor']);
        $("#classificacao").val(venda['classificacao']);
        $("#fase").val(venda['fase']);
        
        var dataCriacao = new Date(venda['dataCriacao']);
        dataCriacao.setDate(dataCriacao.getDate());
        $("#dataCriacao").val(dataCriacao.toLocaleDateString('pt-BR'));

        var dataFechamento = new Date(venda['dataFechamento']);
        dataFechamento.setDate(dataFechamento.getDate());
        $("#dataFechamento").val(dataFechamento.toLocaleDateString('pt-BR'));

        var dataAtualizadaCotacao = new Date(venda['dataAtualizadaCotacao']);
        dataAtualizadaCotacao.setDate(dataAtualizadaCotacao.getDate());
        $("#dataAtualizadaCotacao").val(dataAtualizadaCotacao.toLocaleDateString('pt-BR'));
        
        $("#origem").val(venda['origem']);
        $("#tzDiasLocacao").val(venda['tzDiasLocacao']);
        $("#plataforma").val(venda['plataforma']);
        $("#vendedor").val(venda['vendedor']);
        $("#clienteBase").val(venda['clienteBase']);
        $("#valorRi").val(venda['valorRi']);
        $("#descontoRi").val(venda['descontoRi']);
        $("#totalRi").val(venda['totalRi']);
        $("#descontoHw").val(venda['descontoHw']);
        $("#totalHw").val(venda['totalHw']);
        $("#valorLu").val(venda['valorLu']);
        $("#descontoLu").val(venda['descontoLu']);
        $("#totalLu").val(venda['totalLu']);
        $("#valorSat").val(venda['valorSat']);
        $("#totalSat").val(venda['totalSat']);
        $("#cenario").val(venda['cenario']);
        $("#base").val(venda['base']);
        $("#condPagto").val(venda['condPagto']);
        $("#modalidade").val(venda['modalidade']);
        $("#tipoPagto").val(venda['tipoPagto']);
        $("#canal").val(venda['canal']);
        $("#modulo").val(venda['modulo']);
        $("#status").val(venda['status']);
        $("#statusAf").val(venda['statusAf']);

        var dataStatusAfErp = new Date(venda['dataStatusAfErp']);
        dataStatusAfErp.setDate(dataStatusAfErp.getDate());
        $("#dataStatusAfErp").val(dataStatusAfErp.toLocaleDateString('pt-BR'));

        $("#statusAfErp").val(venda['statusAfErp']);

        var dataNfErp = new Date(venda['dataNfErp']);
        dataNfErp.setDate(dataNfErp.getDate());
        $("#dataNfErp").val(dataNfErp.toLocaleDateString('pt-BR'));

        $("#nfErp").val(venda['nfErp']);
        $("#aprovacaoAdv").val(venda['aprovacaoAdv']);
        $("#aprovacaoFin").val(venda['aprovacaoFin']);
        $("#afErp").val(venda['afErp']);
        $("#ownerName").val(venda['ownerName']);
        $("#businessUnitName").val(venda['businessUnitName']);
        
        var cotacaoCreatedon = new Date(venda['cotacaoCreatedon']);
        cotacaoCreatedon.setDate(cotacaoCreatedon.getDate());
        $("#cotacaoCreatedon").val(cotacaoCreatedon.toLocaleDateString('pt-BR'));
        
        var dataRelatorio = new Date(venda['dataRelatorio']);
        dataRelatorio.setDate(dataRelatorio.getDate());
        $("#dataRelatorio").val(dataRelatorio.toLocaleDateString('pt-BR'));
    }    

    function limparModal(){
        $("#id").val("");
        $("#af").val("");
        $("#qtd").val("");
        $("#cliente").val("");
        $("#dataCriacao").val("");
        $("#dataFechamento").val("");
        $("#vendedor").val("");
        $("#totalHw").val("");
        $("#totalLu").val("");
        $("#cenario").val("");
    }    

    $("#submit-form").click(function(e){
        e.preventDefault();
        let form = $("#formVenda").serialize();

        let af = $("#af").val()
        if(!af){
            alert("AF não pode ser vazio")
            return;
        }

        document.getElementById('loading').style.display = 'block';

        let id = $("#id").val();

        let url = "<?php echo site_url('ComerciaisTelevendas/ComissionamentoDeVendas/adicionarVenda') ?>";

        if(id)
            url = "<?php echo site_url('ComerciaisTelevendas/ComissionamentoDeVendas/atualizarVenda') ?>";

        $.ajax({
            url: url,
            type: "POST",   
            dataType: 'json',
            data: form,
            success: function(data){
                if(data && data['status'] == 200) {
                    if(id)
                        alert("Atualizado com sucesso.");
                    else
                        alert("Cadastrado com sucesso.");

                    $("#modalVenda").modal("hide");
                    tabelaVenda.ajax.reload();

                }else if(data['status'] == 400 && data['results']){
                    alert(data['results']['mensagem']);
                }else if(data['status'] == 500 && data['results']){
                    alert(data['results']['mensagem']);
                }else{
                    alert("Ocorreu um problema ao enviar os Dados.");
                }
            },
            error: function(error){
                alert("Ocorreu um problema ao enviar os Dados.");
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });  

    })

    $("#btnAddVenda").click(function(e){
        e.preventDefault();

        $("#editTitle").hide()
        $("#cadTitle").show()
        $("#modalVenda").modal("show");

        $('#divBuscarPor').show();
        $('#tipo-busca-expedicao').val(2).trigger('change');  

        limparModal();
    })

    $("#BtnPesquisar").click(function(e){
        e.preventDefault();
        
        let dataInicial = $("#dataInicial").val()
        let dataFinal = $("#dataFinal").val()

        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendas') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                dataInicial: dataInicial,
                dataFinal: dataFinal,
            },
            success: function(data){
                if (data.status === 200){
                    tabelaVenda.clear().draw();
                    tabelaVenda.rows.add(data.results).draw();
                }else{
                    tabelaVenda.clear().draw();
                }
            },
            error: function(data){
                alert('Erro ao buscar vendas');
                tabelaVenda.clear().draw();
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });

    })

    $("#BtnLimparPesquisar").click(function(e){
        e.preventDefault();
        
        $("#dataInicial").val("")
        $("#dataFinal").val("")
    })

    $('#tipo-busca-expedicao').change(function(){ 
        $('#cep-expedicao').val('');
        $('#endereco-expedicao').val('');
        $('#uf-expedicao').val('');
        $('#bairro-expedicao').val('');
        $('#cidade-expedicao').val('');
        $('#cliente-expedicaoDoc').attr('disabled', false);
        $('#cliente-expedicaoId').attr('disabled', false);
        $('#cliente-expedicaoDoc').attr('readonly', false);
        $('#cliente-expedicaoId').attr('readonly', false);
        $('#tipo-orgao-expedicao').val('');
        if ($(this).val() == 1){
            $('#cliente-expedicaoDoc').val('');
            $('#cliente-expedicaoDoc').attr('disabled', true);
            $('#cliente-expedicaoId').val('');
            $('#cliente-expedicaoId').attr('disabled', true);
            $('#divDocCliente').hide();
            $('#divBtnPesquisaCliente').hide();
            $('#divIdCliente').hide();
            $("#divNomeCliente").show();
            $("#cliente-expedicao").select2({
                ajax: {
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscar_cliente') ?>',
                    dataType: 'json',
                    delay: 1000,
                    type: 'GET',
                    data: function (params) {
                        return {
                            q: params.term,
                            tipoBusca: 'nome'
                        };
                    },
                },
                placeholder: "Digite o nome do cliente",
                allowClear: true,
                minimumInputLength: 3,
                language: "pt-BR",
                width: 'resolve',
            })
        }else if($(this).val() == 2){
            $('#cliente-expedicao').val('');
            $('#cliente-expedicaoId').val('');
            $('#cliente-expedicaoId').attr('disabled', true);
            $("#divNomeCliente").hide();
            $('#divIdCliente').hide();
            $('#divBtnPesquisaCliente').show();
            $('#divDocCliente').show();
            $("#cliente-expedicaoDoc").inputmask({
            mask: ["999.999.999-99", "99.999.999/9999-99"],
            keepStatic: true,
            placeholder: " ",
            });
        }else if($(this).val() == 0){
            $('#cliente-expedicao').val('');
            $('#cliente-expedicaoDoc').val('');
            $('#cliente-expedicaoDoc').attr('disabled', true);
            $("#divNomeCliente").hide();
            $('#divBtnPesquisaCliente').show();
            $('#divDocCliente').hide();
            $('#divIdCliente').show();
        }
    })

    $('#btnPesquisaClienteDoc').click(function(){
        if ($('#divDocCliente').is(':visible')){
            $('#cliente-expedicaoDoc').attr('disabled', true)
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)
            var documento = $('#cliente-expedicaoDoc').val();
            if (documento != ''){
                $.ajax({
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscar_cliente') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {q: documento,
                        tipoBusca: 'cpfCnpj'},
                    success: function(data){
                        if(data.results.length){
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoDoc').attr('disabled', false)
                            $('#cliente-expedicaoDoc').inputmask('remove')
                            $('#cliente-expedicaoDoc').val(data.results[0].text)
                            $('#cliente-expedicaoDoc').attr('readonly', true)
                            idClienteDoc = data.results[0].id
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoDoc').val('')
                            $('#cliente-expedicaoDoc').attr('disabled', false)
                        }
                    }
                })
            }else{
                alert('Digite o cpf ou cnpj do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#cliente-expedicaoDoc').attr('disabled', false)
                $('#cliente-expedicaoDoc').attr('readonly', false)
            }
        }else{
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)
            var id = $('#cliente-expedicaoId').val();
            if (id != ''){
                $.ajax({
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscar_cliente') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {q: id,
                        tipoBusca: 'id'},
                    success: function(data){
                        if(data.results.length){
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoId').attr('disabled', false)
                            $('#cliente-expedicaoId').val(data.results[0].text)
                            $('#cliente-expedicaoId').attr('readonly', true)
                            idClienteDoc = data.results[0].id
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoId').val('')
                            $('#cliente-expedicaoId').attr('disabled', false)
                        }
                    }
                })
            }else{
                alert('Digite o id do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#cliente-expedicaoId').attr('disabled', false)
                $('#cliente-expedicaoId').attr('readonly', false)
            }
        }
    })

    $('#btnLimparPesquisaClienteDoc').click(function(){

        if ($('#divDocCliente').is(':visible')){
            $('#cliente-expedicaoDoc').val('')
            $('#cliente-expedicaoDoc').attr('disabled', false)
            $('#cliente-expedicaoDoc').attr('readonly', false)
            $("#cliente-expedicaoDoc").inputmask({
                mask: ["999.999.999-99", "99.999.999/9999-99"],
                keepStatic: true,
                placeholder: " ",
                });
        }else{
            $('#cliente-expedicaoId').val('')
            $('#cliente-expedicaoId').attr('disabled', false)
            $('#cliente-expedicaoId').attr('readonly', false)
        }
    })

    function preencherEstados(){
        $.ajax({
            url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome",
            type: "GET",   
            dataType: 'json',
            success: function(data){
                var options = $('#ufCliente');
                $.each(data, function (key, value) {
                    $('<option>').val(value.sigla).text(value.sigla).attr("data-id", value.id).appendTo(options);
                });   
            },
            error: function(error){
                alert("Ocorreu um problema ao Buscar os Estados.");
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });
    }
    
    function preencherCidades(estado){
        $.ajax({
            url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados/"+estado+"/municipios",
            type: "GET",   
            dataType: 'json',
            success: function(data){
                var options = $('#cidadeCliente');
                options.find('option').remove();
                $.each(data, function (key, value) {
                    $('<option>').val(value.nome).text(value.nome).appendTo(options);
                });   
            },
            error: function(error){
                alert("Ocorreu um problema ao Buscar as Cidades.");
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });

    }

    async function povoarPlanos() {
        $("#satelital").empty()
        $("#satelital").prepend(
            `<option value="0">Buscando tipos de planos...</option>`)
        $("#satelital").select2({
            width: '100%',
            placeholder: "Buscando os tipos de planos disponíveis...",
            allowClear: true
        })
        let planos = await buscarPlanos()
        if (planos) planos = JSON.parse(planos)

        if (planos?.length) {
            planos.forEach(plano => {

                $("#satelital").append(
                    `<option value="${plano.tz_name}">${plano.tz_name}</option>`)

                $("#satelital").find('option').get(0).remove()
                $("#satelital").prepend(
                    `<option selected disabled value="">Selecione um tipo de plano</option>`)
            })
        } else {
            $("#satelital").find('option').get(0).remove()
            $("#satelital").prepend(`<option selected disabled value="">Nenhum tipo de plano encontrado</option>`)
        
        }
    }
    
    async function povoarProdutos() {
        $("#produto").empty()
        $("#produto").prepend(
            `<option value="0">Buscando produtos...</option>`)
        $("#produto").select2({
            width: '100%',
            placeholder: "Buscando produtos disponíveis...",
            allowClear: true,
            // matcher: matchCustom
        })
        let produtos = await buscarProdutos()
        if (produtos) produtos = JSON.parse(produtos)

        if (produtos?.length) {
            produtos.forEach(produto => {
                let nomeDoProduto = ""
                if (produto.name == "RASTREADOR INTELIGENTE RI4484") {
                    
                    nomeDoProduto = "OMNITURBO"
                }
                else{
                    if(produto.name == "RASTREADOR INTELIGENTE RI4454"){ 
                        nomeDoProduto = "OMNIDUAL"
                    }
                    else{
                        nomeDoProduto =  produto.name
                    }
                }
                $("#produto").append(`<option data-produto="${produto.productid}" value="${nomeDoProduto}">${nomeDoProduto} - ${produto.productnumber}</option>`)
                $("#produto").find('option').get(0).remove()
                $("#produto").prepend(`<option selected disabled value="">Selecione um produto</option>`)
            })
        } else {
            $("#produto").find('option').get(0).remove()
            $("#produto").prepend(`<option selected disabled value="">Nenhum produto encontrado</option>`)
            alert("Esta empresa não possui produtos cadastrados.")
        }
    }

    async function povoarCenarios() {
        $("#cenario").empty()
        $("#cenario").prepend(
            `<option value="0">Buscando cenários...</option>`)
        $("#cenario").select2({
            width: '100%',
            placeholder: "Buscando cenários disponíveis...",
            allowClear: true
        })
        let cenarios = await buscarCenarios()
        if (cenarios) cenarios = JSON.parse(cenarios)

        if (cenarios?.length) {
            cenarios.forEach(cenario => {
                $("#cenario").append(
                    `<option value="${cenario.tz_name}">${cenario.tz_name}</option>`)
                $("#cenario").find('option').get(0).remove()
                $("#cenario").prepend(`<option selected disabled value="">Selecione um cenário</option>`)
            })
        } else {
            $("#cenario").find('option').get(0).remove()
            $("#cenario").prepend(`<option selected disabled value="">Nenhum cenário encontrado</option>`)
            alert("Esta empresa não possui cenários cadastrados.")
        }
    }

    async function povoarPagamentos() {
        $("#tipoPagto").empty()
        $("#tipoPagto").prepend(
            `<option value="0">Buscando tipos de pagamento...</option>`)
        $("#tipoPagto").select2({
            width: '100%',
            placeholder: "Buscando os tipos de pagamento disponíveis...",
            allowClear: true
        })
        let pagamentos = await buscarPagamentos()
        if (pagamentos) pagamentos = JSON.parse(pagamentos)

        if (pagamentos?.length) {
            pagamentos.forEach(pagamento => {

                $("#tipoPagto").append(
                    `<option tz_codigo_erp="${pagamento.tz_codigo_erp}"  value="${pagamento.tz_name}">${pagamento.tz_name}</option>`
                )

                $("#tipoPagto").find('option').get(0).remove()
                $("#tipoPagto").prepend(
                    `<option selected disabled value="">Selecione um tipo de pagamento</option>`)
            })
        } else {
            $("#tipoPagto").find('option').get(0).remove()
            $("#tipoPagto").prepend(
                `<option selected disabled value="">Nenhum tipo de pagamento encontrado</option>`)
            alert("Esta empresa não possui tipos de pagamentos cadastrados.")
        }
    }

    async function buscarPlanos() {
        var valor = $('#produto').find(':selected').attr('data-produto');
        if ( $('#produto').val()== 0) {
            return false;
        }
        planos = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_planoSatelitalCRM') ?>`+'?value='+valor,
            type: "POST",
            success: function(response) {
                return response
            },
            error: function(error) {
                return false
            },
        })
        return planos
    }

    async function buscarProdutos() {
        produtos = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_idProdutoCRM') ?>`,
            type: "POST",
            success: function(response) {
                return response
            },
            error: function(error) {
                return false
            },
        })
        return produtos
    }
    
    async function buscarCenarios() {
        cenarios = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_cenarioVendaCRM') ?>`,
            type: "POST",
            success: function(response) {
                return response
            },
            error: function(error) {
                return false
            },
        })
        return cenarios
    }

    async function buscarPagamentos() {
        pagamentos = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_tipoPagamentoCRM') ?>`,
            type: "POST",
            success: function(response) {
                return response
            },
            error: function(error) {
                return false
            },
        })
        return pagamentos
    }

    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
</script>

