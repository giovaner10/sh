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
        max-width: 150px;
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

    /* CSS para ajustar o posicionamento do botão "Show entries" */
    /* CSS para ajustar o posicionamento do botão "Show entries" */
    .data-table-container {
    position: absolute;
    }

    #resultTable_length {
    position: relative;
    padding-top: 20px;
    padding-bottom: 30px;
    left: -260px; /* Alinhar à esquerda */
    bottom: -40px; /* Ajuste esse valor conforme necessário */
    }

    #linha-vertical{
        border-left: 1px solid #03A9F4;
        height: 100%;
        width: 1px;
        margin-left: 20px;
        margin-right: 20px;
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

    

    #loading::after {
        content: attr(data-content);
        font-size: 20px;
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: fade 2s infinite;
    }

    @keyframes fade {
      0% {
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }

    #tabelaComissoesCalculadasSolicitacao_filter{
        margin-top: 5px;
    }

    #tabelaComissoesCalculadasSolicitacao th:nth-child(2) {
        padding-right: 49px;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.1/exceljs.min.js"></script>

<h3><?=lang('calculo_comissao')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('comissionamento_vendas')?>
</div>

<hr>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a id="tab-vendedores" data-toggle="tab" href="" class="nav-link active">Vendedores</a>
    </li>
    <li class="nav-item">
        <a id="tab-comissoesCalculadas" data-toggle="tab" href="" class="nav-link">Solicitação de Cálculo</a>
    </li>
    <li class="nav-item">
        <a id="tab-solicitacoesComissao" data-toggle="tab" href="" class="nav-link">Cálculos Solicitados</a>
    </li>
</ul>

<div class="container-fluid my-1" id="divDadosVendedores" hidden>
	<div class="col-sm-12">
        <form id="formPesquisaComissoesCalculadas">
       	    <div class="row">
       	        <div id="pesquisa" class="col-md-4">
       	            <label>Nome da empresa: </label>
       	            <select class="form-control input-sm" id="pesqnomeEmpresa" name="nome" type="text" style="width: 100%" required></select>
       	        </div>
		    	<div id="buttonPesquisar" class="col-md-1">
		    		<button class="btn btn-primary" id="pesquisaComissoesCalcEmpresa" type="submit" style="margin-top: 22px;">Pesquisar</button>
		    	</div>
                
       	    </div>
    	</form>  
                  
        <div id="comissoesCalculadas" class="tab-pane fade in active" style="margin-top: 20px">
        <a id="openModalButton" class="btn btn-primary">Calcular Comissão</a>
            <div class="container-fluid" id="tabelaGeral">
                <table class="table table-bordered table-hover table-striped responsive" id="tabelaComissoesCalculadas">
                    <thead>
                        <tr class="tableheader">
                        <th>Id</th>
                        <th>Empresa</th>
                        <th>Vendedor</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Valor Total Vendas</th>
                        <th>Valor Total Devoluções</th>
                        <th>Teve Campanha</th>
                        <th>Percentual Médio</th>
                        <th>Data de Cadastro</th>
                        <th>Data de Atualização</th>
						<th>Status</th>
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



<!-- Modal de calculo de comissão -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"> 
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 class="modal-title" id="myModalLabel">Calcular Comissionamento</h5>
      </div>
      <div class="modal-body">
        <!-- Formulário de pesquisa -->
        <form id="searchForm">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="pesqnomeVendedor">Vendedor:</label>
              <select id="pesqnomeVendedor" class="form-control" required></select>
            </div>
            <div class="form-group col-md-2">
              <label for="dataInicio">Data de Início:</label>
              <input type="date" class="form-control" id="dataInicio" name="dataInicio" required>
            </div>
            <div class="form-group col-md-2">
              <label for="dataFim">Data de Fim:</label>
              <input type="date" class="form-control" id="dataFim" name="dataFim" required>
            </div>
            <div class="form-group col-md-2 d-flex align-items-end">
              <button style="margin-top: 25px;" id="btnbuscarcomissaoCalculada" type="submit" class="btn btn-primary btn-block">Calcular</button>
            </div>
            <div class="form-group col-md-2 d-flex align-items-end">
                <button style="margin-top: 25px;" id="btnLimparDados" class="btn btn-secondary btn-block">Limpar Dados</button>
            </div>
          </div>
        </form>
        <br>
        <br>
        <br>
        <br>

     
        <!-- Tabela para mostrar os resultados da pesquisa -->
        <div class="container-fluid my-1">
	    <div class="col-sm-12">
        <table class="table table-bordered mt-4 d-none" id="resultTable">
          <thead>
            <tr>
              <th>AF</th>
              <th>Clientes</th>
              <th>Cenário</th>
              <th>Quantidade</th>
              <th>Total HW (R$) / Quantidade</th>
              <th>Regra</th>              
              <th>Comissão (R$)</th>
            </tr>
          </thead>          
          <tbody>
            <!--OBS.: NÃO apagar o body, pois, os dados da tabela serão preenchidos aqui dinamicamente-->
          </tbody>
          <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total:</th>
                <th colspan="2"></th>
            </tr>
          </tfoot>
        </table>
        </div>
      </div>
      </div>
      <div class="modal-footer">       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>        
      </div>
    </div>
  </div>
</div>


<div class="container-fluid my-1" id="divComissoesCalculadas" hidden>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="row alert alert-info">
                    <p>Selecione a empresa para buscar os vendedores.</p>
                    <br>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="control-label">Nome da empresa:</label>
                            <select class="form-control input-sm" id="pesqnomeEmpresaVendedores" name="nome" type="text" style="width: 100%" required></select>
                        </div>
                        <div class="col-md-2 form-group">
                            <button class="btn btn-primary" id="pesquisaVendedorEmpresa" type="submit" style="margin-top: 20px;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        </div>
                        <div class="col-md-2 form-group gerarSolicitacao">
                            <label class="control-label">Data Início:</label>
                            <input type="date" class="form-control input-sm" id="dataIni" name="dataIni" required>
                        </div>
                        <div class="col-md-2 form-group gerarSolicitacao">
                            <label class="control-label">Data Fim:</label>
                            <input type="date" class="form-control input-sm" id="dataF" name="dataF" required>
                        </div>
                        <div class="col-md-2 form-group gerarSolicitacao">
                            <button class="btn btn-primary" id="solicitarCalculo" style="margin-top: 20px;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Solicitar Cálculo(s)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover table-striped responsive" id="tabelaVendedores" style="margin-top: 10px;">
            <thead>
                <tr class="tableheader checks">
                        <th id="selecionarTodos">
                            <input type="checkbox" id="checkTodos" name="checkTodos">
                        </th>
                    <th>Vendedor</th>
                    <th>Cargo</th>
                    <th>Empresa</th>
                    <th>Regional</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table> 
    </div>
</div>

<!-- Modal itens da comissão calculada -->
<div id="modalItensComissaoCalculada" tabindex="-1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form name="formItensComissaoCalculada">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("itens_comissao_calculada")?></h3>
                    <label id="labelTotalComissao" style="float: right;"></label>
                    <label id="labelNomeVendedor"></label>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <table class="table-responsive table-bordered table" id="tabelaItensComissaoCalculada">
                	    	<thead>
                	    	    <tr class="tableheader">
                	    	    <th>AF</th>
                	    	    <th>Cliente</th>
                	    	    <th>Cenário</th>
                	    	    <th>Quantidade</th>
                                <th>Total HW (R$) / Quantidade</th>
                                <th>Regra</th>
                                <th>Comissão (R$)</th>
                	    	    </tr>
                	    	</thead>
                	    	<tbody>
                	    	</tbody>
                            <tfoot>
                              <tr>
                                  <th colspan="5" style="text-align:right">Total:</th>
                                  <th colspan="2"></th>
                              </tr>
                            </tfoot>
                	    </table> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid my-1" id="divComissoesSolicitadas" hidden>
    <div class="col-md-12">
        <table class="table-responsive table-bordered table-hover table-striped table" id="tabelaComissoesSolicitadas">
        	<thead>
        	    <tr class="tableheader">
        	    <th>Id</th>
        	    <th>Data Início</th>
        	    <th>Data Fim</th>
                <th>Data da Solicitação</th>
                <th>Data de Atualização</th>
                <th>Status</th>
                <th>Ações</th>
        	    </tr>
        	</thead>
        	<tbody>
        	</tbody>
        </table> 
    </div>
</div>

<div id="modalComissoesCalculadasSolicitacao" tabindex="-1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3><?= lang('comissoes_calculadas') ?></h3>
                <h4 id="periodoHeader" style="float: right;"></h4>
            </div>
            <div class="modal-body scrollModal">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-responsive" id="tabelaComissoesCalculadasSolicitacao">
                        <thead>
                            <tr class="tableheader">
                            <th>Id</th>
                            <th>Matrícula</th>
                            <th style="padding-left: 49px;">Vendedor</th>
                            <th>Cargo</th>
                            <th>Centro de Resultado - Código</th>
                            <th>Centro de Resultado - Nome</th>
                            <th>Unidade</th>
                            <th>Salário</th>
                            <th>Data de Admissão</th>
                            <th>Chefia Imediata</th>
                            <th>Executivo</th>
			        	    <th>Gestor</th>
                            <th>Televendas</th>
                            <th>Comissão</th>
                            <th>Garantia de Comissão</th>
                            <th>Valor de Comissão à Receber</th>
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
</div>
<div id="loading" style="display: none;">
    <div class="loader"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>



<script>
    $(document).ready(function() {
        $('#myModal').on('hidden.bs.modal', function (e) {
            $('#searchForm')[0].reset();
        });
    });

    var tabelaComissoesCalculadas = $('#tabelaComissoesCalculadas').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            order: [0, 'desc'],
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhuma comissão calculada a ser listada",
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
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarComissoesCalculadas') ?>',
                type: 'GET',
                dataType: 'json',
                beforeSend: function(data){
                    processando = true;
                    $('#tabelaComissoesCalculadas > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                        '</tr>'
                    );
                },
                success: function(data){
                    if (data.status === 200){
                        tabelaComissoesCalculadas.clear().draw();
                        tabelaComissoesCalculadas.rows.add(data.results).draw();
                    }else{
                        tabelaComissoesCalculadas.clear().draw();
                    }
                },
                error: function(){
                    alert('Erro ao buscar comissões calculadas');
                    tabelaComissoesCalculadas.clear().draw();
                    processando = false;
                },
                complete: function(){
                    processando = false;
                }
            },
            columns: [
                { data: 'id',
                    visible: false},
                {data: 'nomeEmpresa'},
                { data: 'nomeVendedor'},
                { data: 'dataInicio',
                    render: function (data) {
                        return new Date(data).toLocaleDateString();
                    }},
                { data: 'dataFim',
                    render: function (data) {
                        return new Date(data).toLocaleDateString();
                    }},
                { data: 'valorTotalVendas',
                    render: function (data) {
                        return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    }},
                { data: 'valorTotalDevolucoes',
                    render: function (data) {
                        return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    }},
                { data: 'teveCampanha',
                    render: function (data) {
                        if (data == 1){
                            return 'Sim';
                        }else{
                            return 'Não';
                        }
                    }},
                { data: 'percentualMedio',
                    render: function (data){
                        return data + '%';
                    }},
                { data: 'dataCadastro',
                    render: function (data) {
                        return new Date(data).toLocaleDateString();
                    }},
                { data: 'dataUpdate',
                    render: function (data) {
                        return new Date(data).toLocaleDateString();
                    }},
                { data: 'status',
                visible: false},
                {data: {'id':'id'}, className: 'text-center',
                    render: function(data){
                        return `
                            <button 
	    			            class="btn btn-primary"
	    			            title="Visualizar Itens da Comissão"
	    			            id="btnVisualizarItensComissaoVendedor"
                                onClick="javascript:visualizarItensComissaoVendedor(this,'${data['id']}', '${data['totalComissao']}', '${data['nomeVendedor']}', '${data['dataInicio']}', '${data['dataFim']}', '${data['cargo']}', '${data['chefiaImediata']}', '${data['teveCampanha']}','${data['valorTotalVendas']}','${data['valorTotalDevolucoes']}', '${data['nomeRegional']}', event)">
	    			            <i class="fa fa-file-text" aria-hidden="true"></i>
	    			        </button>
                        `;
                    }
                },
            ],
    });

    $('#pesqnomeEmpresa').select2({
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            type: 'GET',
            dataType: 'json',
            delay: 1000,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR",
        minimumInputLength: 3,
    })

    $('#formPesquisaComissoesCalculadas').submit(function(e){
        e.preventDefault();
        var idEmpresa = $('#pesqnomeEmpresa').val();
        $('#pesquisaComissoesCalcEmpresa').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');

        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarComissoesCalculadasPorEmpresa') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idEmpresa: idEmpresa
            },
            success: function(data){
                if (data.status === 200){
                    tabelaComissoesCalculadas.clear().draw();
                    tabelaComissoesCalculadas.rows.add(data.results).draw();
                    $('#pesquisaComissoesCalcEmpresa').attr('disabled', false).html('Pesquisar');
                }else{
                    tabelaComissoesCalculadas.clear().draw();
                    $('#pesquisaComissoesCalcEmpresa').attr('disabled', false).html('Pesquisar');
                }
            },
            error: function(){
                alert('Erro ao pesquisar comissões calculadas')
                $('#pesquisaComissoesCalcEmpresa').attr('disabled', false).html('Pesquisar');
            }
        });
    });
    


</script>

<script>

async function listarVendedores() {
    try {
        $('#pesqnomeVendedor').empty();
        const listarVendedores = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendedoresSelect2') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            }
        });

        $('#pesqnomeVendedor').select2({
            data: listarVendedores.results,
            placeholder: "Selecione o vendedor",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });

        $('#pesqnomeVendedor').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#pesqnomeVendedor').val(null).trigger('change');
    } catch (error) {
        throw new Error('Erro ao listar vendedores. Tente novamente.');
    }
}


document.getElementById("openModalButton").addEventListener("click", async function() {
    let botao = $(this);
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    try {
        await listarVendedores();
        $('#myModal').modal('show');
        botao.attr('disabled', false).html('Calcular Comissão');
    } catch (error) {
        alert(error);
        botao.attr('disabled', false).html('Calcular Comissão');
    }
});

$(document).ready(function() {
    

    document.getElementById("searchForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário

    var dataInicio = $('#dataInicio').val();
    var dataFim = $('#dataFim').val();

    var parte1 = dataInicio.split('-');
    var anoInicio = parseInt(parte1[0]);
    var mesInicio = parseInt(parte1[1]);
    var parte2 = dataFim.split('-');
    var anoFim = parseInt(parte2[0]);
    var mesFim = parseInt(parte2[1]);

    if (!(anoInicio == anoFim && mesInicio == mesFim)){
        alert('O intervalo de dias precisam ser do mesmo mês e ano.');
        return;
    }else{
    $('#btnbuscarcomissaoCalculada').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Calculando...');
    // Show the loading screen
    $('#loading').removeClass('d-none');
      // Hide the table
    $('#resultTable').addClass('d-none');

    // Obter os valores dos campos do formulário
    const vendedorId = document.getElementById("pesqnomeVendedor").value;

    const datainit = document.getElementById("dataInicio").value;
    const dataInicio = moment(datainit).format("DD/MM/YYYY");

    const dataFinit = document.getElementById("dataFim").value;
    const dataFim = moment(dataFinit).format("DD/MM/YYYY");

    const meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
    dataFormat = datainit + " 00:00:00";
    dataFormat = new Date(dataFormat);

    dataNomeArquivo = `${meses[dataFormat.getMonth()]}${dataFormat.getFullYear()}`
    
    // Fazer a requisição AJAX para o servidor com os dados
    $.ajax({
        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarCalculoporVendedor') ?>',
        type: 'GET', 
        dataType: 'json',
        data: {
        vendedorId: vendedorId,
        dataInicio: dataInicio,
        dataFim: dataFim,
        },

        success: function(response) {
            if (response.length === 0) {
                alert("Não existem dados para cálculo de comissão.");
                $('#btnbuscarcomissaoCalculada').attr('disabled', false).html('Calcular');
                if (tabelaSalesValidation){
                    tabelaSalesValidation.clear().draw();
                }
            } 
            else {
                const dadosTabela = response[0]['ComissaoCalculadaItens'];
                var nomeArquivoPDF = response[0]['nomeVendedor'] + "-" + response[0]['nomeCargo'] + "-" + dataNomeArquivo;
                var nomeCargo = response[0]['nomeCargo'];
                var chefiaVendedor = response[0]['chefiaImediata'];
                chefiaVendedor = chefiaVendedor != null && chefiaVendedor != 'null' ? chefiaVendedor : '';
                var regionalVendedor = response[0]['nomeRegional'];
                var temCampanha = response[0]['temCampanha'] == 1 ? true : false;
                initializeDataTable([],dataInicio, dataFim, nomeArquivoPDF, nomeCargo, chefiaVendedor, regionalVendedor, temCampanha);

                dadosTabela.forEach(function (item, index) {
                    item['regra'] = item['valorFixo'] == "0.0" ? item['percentual'] + "%" : item['valorFixo'] + ",00";
                });

                // Hide the loading screen
                $('#loading').addClass('d-none');
                // Show the table with data
                $('#resultTable').removeClass('d-none');

                // Update the DataTables with the fetched data
                tabelaSalesValidation.clear().rows.add(dadosTabela).draw();
    			$('#btnbuscarcomissaoCalculada').attr('disabled', false).html('Calcular');
            }
           
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Erro na requisição:', textStatus, errorThrown);
        },
    });
    }
    });

    $('#btnLimparDados').click(function(e) {
        e.preventDefault();

        $('#pesqnomeVendedor').val(null).trigger('change');
        $('#dataInicio').val('');
        $('#dataFim').val('');
        
        if (tabelaSalesValidation){
            tabelaSalesValidation.clear().draw();
        }
    });

});

$('#pesqnomeVendedor').on('change', function() {
  const selectedData = $(this).select2('data');
  vendedorNome = selectedData.length ? selectedData[0].text : '';
});

let tabelaSalesValidation; 
let indiceDevolucoes;
let temVendas = false;
let temDevolucoes = false;
let indiceTotalVendas;
let indiceTotalDevolucoes;

  // Função para inicializar o DataTables
  function initializeDataTable(dadosTabela, dataInicio, dataFim, nomeArquivoPDF, cargoVendedor, chefiaVendedor, regionalVendedor, temCampanha) {
   
   
    tabelaSalesValidation = $('#resultTable').DataTable({
      destroy: true, // Destruir a tabela antiga antes de criar uma nova
      responsive: true,
      ordering: false,
      paging: true,
      language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhuma comissão calculada a ser listada",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
                zeroRecords:        "Nenhum resultado encontrado.",
                paginate: {
                    first:          "Primeira",
                    last:           "Última",
                    next:           "Próxima",
                    previous:       "Anterior"
                },
                lengthMenu: "Mostrar _MENU_ resultados por página"
            },
      searching: true,
      info: true,
      data: dadosTabela,
      columns: [
        { data: 'af' },
        { data: 'cliente' },
        { data: 'executivo' },     
        { data: 'quantidade' },      
        { data: 'valor',
            render: function (data) {
                return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }},
        { data: 'regra' },
        { data: 'comissao',
            render: function (data) {
                return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }}       
      ],            
        
    initComplete: function()
        {
            $(document).on('click', '#gerarPDF', function() {
                $(".buttons-pdf").trigger("click");
            });

            $(document).on('click', '#gerarEXCEL', function() {
                $(".buttons-excel").trigger("click");
            });           

            $(document).on('click', '#gerarPRINT', function() {
                $(".buttons-print").trigger("click");
            });
        },        
        footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
        
                    // Total over all pages
                     totalComissao = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                        }, 0);
                    
                    totalHw = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                        }, 0);
                    // Total over this page
                    
                    // Update footer
                    $(api.column(5).footer()).html( ' ( ' + totalComissao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                                        
                },
        rowGroup: {
            dataSrc: function (row) {
                var valorColuna = row['valor'];
                var grupo;

                if (valorColuna >= 0) {
                    grupo = "<strong>Vendas</strong>";
                } else {
                    grupo = "<strong>Devoluções</strong>";
                }
            
                return grupo;
            }
        },
        dom: 'Blfrtip',
        buttons:
        [{            
            filename: nomeArquivoPDF,
            orientation: 'landscape',
            pageSize: 'LEGAL',
            extend: 'pdfHtml5',
            className: 'btn btn-outline-primary',
            text: '<?= lang('gerar_pdf') ?>',

            customize: function ( doc , tes)
            {                
                titulo = `Vendedor: ${vendedorNome} | Cargo: ${cargoVendedor} \n Chefia: ${chefiaVendedor} | Regional: ${regionalVendedor}\n Data Início: ${dataInicio} Data Fim: ${dataFim}`;
                const tamanhoColunas = [70, '*', 200, 70, 90, 70, 90];
                const alinhamentoColunas = ['center', 'left', 'left', 'center', 'right', 'right', 'right'];
                // Personaliza a página do PDF
                pdfTemplateICalculoComissao(doc, titulo, 'A4', tamanhoColunas, alinhamentoColunas)
                var textoCampanha = temCampanha ? '*Tem Campanha' : '*Não tem Campanha';
               doc.content[1].table.body.push([{
                    text: `\n\nTotal HW: ${totalHw.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})} \n Total Comissão: ${totalComissao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})} \n ${textoCampanha}`,
                    colSpan: 7,
                    fillColor: 'white',
                    color: 'black',
                    bold: true,
                    fontSize: 15,
                    alignment: 'right'
                }]);
            }
        },
        {
           
            filename: nomeArquivoPDF,
            orientation: 'landscape',
            pageSize: 'LEGAL',
            extend: 'excel',
            className: 'btn btn-outline-primary',
            text: '<?= lang('gerar_excel') ?>',
            messageTop: function () {      
                var textoCampanhaEx = temCampanha ? '*Tem Campanha' : '*Não tem Campanha';         
                return `Data Início: ${dataInicio}|Data Fim: ${dataFim}|Vendedor: ${vendedorNome}|Total Comissão: ${totalComissao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}|Total HW: ${totalHw.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}|Cargo: ${cargoVendedor}|Chefia: ${chefiaVendedor}|Regional: ${regionalVendedor}|${textoCampanhaEx}`;
            },
            customizeData: function(data) {
                let devolucoes = [];
                let vendas = [];
                let totalHWDevolucoes = 0;
                let totalComissoesDevolucoes = 0;
                let totalHWVendas = 0;
                let totalComissoesVendas = 0;

                for (let i = 0; i < data.body.length; i++) {
                    let valorComissao = '';
                    let valorHwQuantidade = '';
                    let regra = '';
                
                    if (data.body[i] && data.body[i][6]) {
                        if (data.body[i][6].indexOf('R$') === 0 || data.body[i][6].indexOf('-R$') === 0) {
                            if (data.body[i][6].indexOf('-R$') === 0) {
                                valorComissao = data.body[i][6].substring(4);
                                valorComissao = valorComissao.replace(".", "").replace(",", ".");
                                data.body[i][6] = '-' + valorComissao;
                            } else {
                                valorComissao = data.body[i][6].substring(3);
                                valorComissao = valorComissao.replace(".", "").replace(",", ".");
                                data.body[i][6] = valorComissao;
                            }
                        }
                    }
                
                    if (data.body[i] && data.body[i][4]) {
                        if (data.body[i][4].indexOf('R$') === 0 || data.body[i][4].indexOf('-R$') === 0) {
                            if (data.body[i][4].indexOf('-R$') === 0) {
                                valorHwQuantidade = data.body[i][4].substring(4);
                                valorHwQuantidade = valorHwQuantidade.replace(".", "").replace(",", ".");
                                data.body[i][4] = '-' + valorHwQuantidade;
                            } else {
                                valorHwQuantidade = data.body[i][4].substring(3);
                                valorHwQuantidade = valorHwQuantidade.replace(".", "").replace(",", ".");
                                data.body[i][4] = valorHwQuantidade;
                            }
                        }
                    }
                
                    if (data.body[i] && data.body[i][5]) {
                        if (!(data.body[i][5].endsWith('%'))) {
                            regra = data.body[i][5].replace(".", "").replace(",", ".");
                            data.body[i][5] = regra;
                        }
                    }
                
                    // Agrupamento para Excel
                    let dadosHw = data.body[i][4].toString().trim();
                    if (dadosHw.startsWith('-')) {
                        devolucoes.push(data.body[i]);
                        totalHWDevolucoes += parseFloat(data.body[i][4]);
                        totalComissoesDevolucoes += parseFloat(data.body[i][6]);
                    } else {
                        vendas.push(data.body[i]);
                        totalHWVendas += parseFloat(data.body[i][4]);
                        totalComissoesVendas += parseFloat(data.body[i][6])
                    }
                }               
                data.body.length = 0;
                data.body = [];

                if (vendas.length){
                    data.body.push(['*', '*', '*', 'VENDAS', '*', '*', '*']);
                    data.body.push(...vendas);
                    data.body.push(['', '', '', '', `Total HW Vendas: ${(totalHWVendas.toFixed(2)).replace('.', ',')}`,'', `Total Comissão Vendas: ${(totalComissoesVendas.toFixed(2)).replace('.',',')}`]);
                    indiceDevolucoes = data.body.length + 3;
                    indiceTotalVendas = data.body.length + 2;
                    temVendas = true;

                }
                if (devolucoes.length){
                    data.body.push(['*', '*', '*', 'DEVOLUÇÕES', '*', '*', '*']);
                    data.body.push(...devolucoes);
                    data.body.push(['', '', '', '', `Total HW Devoluções: ${(totalHWDevolucoes.toFixed(2)).replace('.', ',')}`,'', `Total Comissão Devoluções: ${(totalComissoesDevolucoes.toFixed(2)).replace('.', ',')}`]);
                    indiceTotalDevolucoes = data.body.length + 2;
                    temDevolucoes = true;
                }
                
            },
            customize: function (xlsx) {

                function personalizaLinha(indices) {
                    for (var j = 0; j < indices.length; j++) {
                        var rowIndex = indices[j];
                        var row = sheet.getElementsByTagName('row')[rowIndex];
                    
                        for (var i = 0; i < row.childNodes.length; i++) {
                            var cell = row.childNodes[i];
                            cell.setAttribute('s', '7');
                        
                            var textoCelula = cell.getElementsByTagName('t')[0];
                        
                            if (!textoCelula) {
                                textoCelula = document.createElement('t');
                                var isNode = document.createElement('is');
                                isNode.appendChild(textoCelula);
                                cell.appendChild(isNode);
                            }
                        
                            if (textoCelula.textContent == '*') {
                                textoCelula.textContent = '';
                            }
                        }
                    }
                }

                function personalizaLinhaTotal(indices) {
                    for (var j = 0; j < indices.length; j++) {
                        var rowIndex = indices[j];
                        var row = sheet.getElementsByTagName('row')[rowIndex];
                    
                        for (var i = 0; i < row.childNodes.length; i++) {
                            var cell = row.childNodes[i];
                            cell.setAttribute('s', '25');
                        }
                    }
                }
                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                if (temVendas && !temDevolucoes){
                    personalizaLinha([3])
                    personalizaLinhaTotal([indiceTotalVendas])

                }else if (!temVendas && temDevolucoes){
                    personalizaLinha([3])
                    personalizaLinhaTotal([indiceTotalDevolucoes])
                }else if (temVendas && temDevolucoes){
                    personalizaLinha([3, indiceDevolucoes])
                    personalizaLinhaTotal([indiceTotalVendas, indiceTotalDevolucoes])
                }

                temVendas = false;
                temDevolucoes = false;
                indiceDevolucoes = 0;
                indiceTotalVendas = 0;
                indiceTotalDevolucoes = 0;
            },
        },        
        {
         
            filename: nomeArquivoPDF,
            orientation: 'landscape',
            pageSize: 'LEGAL',
            extend: 'print',
            className: 'btn btn-outline-primary',
            text: '<?= lang('imprimir') ?>',
            customize: function ( win )
            {
                titulo = `Vendedor: ${vendedorNome} | Total: ${total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})} <br> Cargo: ${cargoVendedor} | Chefia: ${chefiaVendedor} | Regional: ${regionalVendedor} <br> Data Início: ${dataInicio} | Data Fim: ${dataFim}`;
                // Personaliza a página Impressa
                printTemplateOmniComissoes(win, titulo);
            }
        }],
    });
   
  }

    var idsVendedores = [];
    var tabelaComissoesCalculadasVendedores
    var vendedoresSelecionados = [];

    $(document).ready(function(){
        $('#tab-vendedores').click();
    })

    var tabelaVendedores = $('#tabelaVendedores').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        order: [1, 'asc'],
        autoWidth: false,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum vendedor a ser listado",
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
        columns: [
            {
                data: "check",
                render: function(data, type, row, meta) {
                    return `<input type="checkbox" data-row="${row.id}" id="checkVendedor" class="checksClass">`;
                },
                width: '10%',
                orderable: false
            },
            { data: 'nome', width: '30%'},
            { data: 'nomeCargo', width: '20%'},
            { data: 'nomeEmpresa', width: '20%'},
            { data: 'nomeRegional', width: '20%'}
        ],
        columnDefs: [
            {
                targets: [0],
                className: 'dt-center'
            },
        ],
    })

    tabelaVendedores.on('draw.dt', function(){
        var elementoInput  = document.querySelector("td input.checksClass")
        if (elementoInput ){
            var elementoTd = elementoInput.closest("td");
            elementoTd.classList.add("col-md-1");
        }
    })

    $('#checkTodos').click(function(){
        if (tabelaVendedores.page.info().pages > 1){
            for (var i = 1; i <= tabelaVendedores.page.info().pages; i++) {
                tabelaVendedores.page(i).draw('page');
            }
        }

        tabelaVendedores.rows().every(function () {
            var row = this.node();
            var check = $(row).find('input[type="checkbox"]');
            if ($('#checkTodos').prop('checked')){
                check.prop('checked', true);
            }else{
                check.prop('checked', false);
            }    
        })
    })

    $('#tabelaVendedores').on('click', '.checksClass', function() {
        var todosCheck = true;
        if (!$(this).prop('checked')){
            $('#checkTodos').prop('checked', false);
        }
        tabelaVendedores.rows().every(function () {
            var row = this.node();
            var check = $(row).find('input[type="checkbox"]');
            
            if (!check.prop('checked')){
                todosCheck = false;
            }
        })

        if (todosCheck){
            $('#checkTodos').prop('checked', true);
        }
    });
$('#tab-vendedores').click(function(){
    $('#divDadosVendedores').show();
    $('#divComissoesCalculadas').hide();
    $('#divComissoesSolicitadas').hide();
    if (!processando){
        tabelaComissoesCalculadas.columns.adjust().draw();
        tabelaComissoesCalculadas.clear().draw();
        tabelaComissoesCalculadas.ajax.reload();
    }
});

$('#tab-comissoesCalculadas').click(function(){
    $('#divComissoesCalculadas').show();
    $('#divDadosVendedores').hide();
    $('#divComissoesSolicitadas').hide();
    if (!(tabelaVendedores.rows().data().length)){
        $('.gerarSolicitacao').hide();
    }
    tabelaVendedores.columns.adjust().draw();
});

$('#tab-solicitacoesComissao').click(function(){
    $('#divComissoesCalculadas').hide();
    $('#divDadosVendedores').hide();
    $('#divComissoesSolicitadas').show();
    if (!processando){
        tabelaComissoesSolicitadas.columns.adjust().draw();
        tabelaComissoesSolicitadas.clear().draw();
        tabelaComissoesSolicitadas.ajax.reload();
    }
});

var totalComissaoTitulo = 0;
var nomeVendedorTitulo = '';
var dataInicialTitulo = '';
var dataFinalTitulo = '';
var cargoVendedor = '';
var regionalVendedor = '';
var chefiaVendedor = '';
var tabelaItensComissaoCalculada = '';

function visualizarItensComissao(botao, idComissao, totalComissao, nomeVendedor, dataInicial, dataFinal, nomeCargo, chefiaImediata, regional, teveCampanha, valorTotalVendas, valorTotalDevolucoes, event){
    event.preventDefault();
    botao = $(botao);
    totalComissaoTitulo = parseFloat(totalComissao);
    nomeVendedorTitulo = nomeVendedor;
    dataInicialTitulo = new Date(dataInicial).toLocaleDateString();
    dataFinalTitulo = new Date(dataFinal).toLocaleDateString();
    cargoVendedor = nomeCargo ? nomeCargo : '';
    chefiaVendedor = chefiaImediata != null && chefiaImediata != 'null' ? chefiaImediata : '';
    const meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
    dataNomeArquivo = new Date(dataInicial);
    dataNomeArquivo = `${meses[dataNomeArquivo.getMonth()]}${dataNomeArquivo.getFullYear()}`;
    nomeArquivoPDF = `${nomeVendedorTitulo}-${cargoVendedor}-${dataNomeArquivo}`;
    var nomeRegional = regional ? regional : '';
    var campanha = teveCampanha == 1 ? true : false;
    var valorTotalHw = parseFloat(valorTotalVendas) + parseFloat(valorTotalDevolucoes);


    if (tabelaItensComissaoCalculada){
        tabelaItensComissaoCalculada.destroy();
    }

    inicializarTabelaItensComissaoCalculada(nomeArquivoPDF, nomeVendedorTitulo, cargoVendedor, chefiaVendedor, dataInicialTitulo, dataFinalTitulo, totalComissaoTitulo, nomeRegional, campanha, valorTotalHw);
    
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    $.ajax({
        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarItensComissaoCalculadaTeveCampanha') ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            idComissao: idComissao
        },
        success: function(data){
            if (data.status == 200){
                tabelaItensComissaoCalculada.clear().draw();
                tabelaItensComissaoCalculada.rows.add(data.results).draw();
                totalComissao ? $('#labelTotalComissao').html('Total: ' + (totalComissaoTitulo.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }))) : ''
                nomeVendedorTitulo ? $('#labelNomeVendedor').html('Vendedor: ' + nomeVendedorTitulo) : ''
                $('#modalItensComissaoCalculada').modal('show');
            }else if(data.status == 400 && data.results.mensagem){
                alert(data.results.mensagem);
                tabelaItensComissaoCalculada.clear().draw();
            }else{
                alert('Não foi possível buscar os itens da comissão calculada. Tente novamente.');
                tabelaItensComissaoCalculada.clear().draw();
            }
        },
        error: function(){
            alert('Erro ao buscar itens da comissão calculada. Tente novamente');
            tabelaItensComissaoCalculada.clear().draw();
            botao.attr('disabled', false).html('<i class="fa fa-file-text" aria-hidden="true"></i>');
        },
        complete: function(){
            botao.attr('disabled', false).html('<i class="fa fa-file-text" aria-hidden="true"></i>');
        }
    })
}

function inicializarTabelaItensComissaoCalculada(nomeArquivoPDF,nomeVendedor, cargoVendedor, chefiaVendedor, dataInicial, dataFinal, totalComissao, nomeRegional, temCampanha, totalHw){
    tabelaItensComissaoCalculada = $('#tabelaItensComissaoCalculada').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum item a ser listado",
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
        columns: [
            {data: 'af'},
            {data: 'cliente'},
            {data: 'cenario'},
            {data: 'quantidade'},
            {data: 'totalHw',
                render: function (data) {
                    return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            },
            {data: 'regra'},
            {data: 'comissao',
                render: function (data) {
                    return parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            },
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            
            totalHwFooterPdf = api
                .column(4)
                .data()
                .reduce(function (a, b) {
                    return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                }, 0);

            $(api.column(5).footer()).html( ' ( ' + totalComissao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                                
        },
        rowGroup: {
            dataSrc: function (row) {
                var valorColuna = row['totalHw'];
                var grupo;

                if (valorColuna >= 0) {
                    grupo = "<strong>Vendas</strong>";
                } else {
                    grupo = "<strong>Devoluções</strong>";
                }
            
                return grupo;
            }
        },
        dom: 'Blfrtip',
        buttons:
        [{            
            filename: nomeArquivoPDF,
            orientation: 'landscape',
            pageSize: 'LEGAL',
            extend: 'pdfHtml5',
            className: 'btn btn-primary',
            text: '<i class="fa fa-file-pdf-o"></i> <?= lang('gerar_pdf') ?>',
            customize: function (doc, tes)
            {
                titulo = `Vendedor: ${nomeVendedor} | Cargo: ${cargoVendedor}\n Chefia: ${chefiaVendedor} | Regional: ${nomeRegional}\n Data Início: ${dataInicial} Data Fim: ${dataFinal}`;
                const tamanhoColunas = [70, '*', 200, 70, 90, 70, 90];
                const alinhamentoColunas = ['center', 'left', 'left', 'center', 'right', 'right', 'right'];
                pdfTemplateICalculoComissao(doc, titulo, 'A4', tamanhoColunas, alinhamentoColunas);
                var textoCampanha = temCampanha ? '*Tem Campanha' : '*Não tem Campanha';
                doc.content[1].table.body.push([{
                    text: `\n\nTotal HW: ${totalHwFooterPdf.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})} \n Total Comissão: ${totalComissao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})} \n ${textoCampanha}`,
                    colSpan: 7,
                    fillColor: 'white',
                    color: 'black',
                    bold: true,
                    fontSize: 15,
                    alignment: 'right'
                }]);
            }
        },
        {
            filename: nomeArquivoPDF,
            orientation: 'landscape',
            pageSize: 'LEGAL',
            extend: 'excel',
            className: 'btn btn-primary',
            text: '<i class="fa fa-file-excel-o"></i> <?= lang('gerar_excel') ?>',
            messageTop: function () {
                var txtCampanha = temCampanha ? '*Tem Campanha' : '*Não tem Campanha';
                return `Data Início: ${dataInicialTitulo}|Data Fim: ${dataFinalTitulo}|Vendedor: ${nomeVendedorTitulo}|Total Comissão: ${totalComissaoTitulo.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}|Total HW: ${totalHwFooterPdf.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}|Cargo: ${cargoVendedor}|Chefia: ${chefiaVendedor}|Regional: ${nomeRegional}|${txtCampanha}`
            },
            customizeData: function(data) {
                let devolucoes = [];
                let vendas = [];
                let totalHWDevolucoes = 0;
                let totalComissoesDevolucoes = 0;
                let totalHWVendas = 0;
                let totalComissoesVendas = 0;

                for (var i = 0; i < data.body.length; i++) {
                    var valorComissao = '';
                    var valorHwQuantidade ='';
                    var regra = '';

                    if (data.body[i] && data.body[i][6]) {
                        if (data.body[i][6].indexOf('R$') === 0 || data.body[i][6].indexOf('-R$') === 0) {
                            if (data.body[i][6].indexOf('-R$') === 0) {
                                valorComissao = data.body[i][6].substring(4);
                                valorComissao = valorComissao.replace(".", "").replace(",", ".");
                                data.body[i][6] = '-' + valorComissao;
                            }else{
                                valorComissao = data.body[i][6].substring(3);
                                valorComissao = valorComissao.replace(".", "").replace(",", ".");
                                data.body[i][6] = valorComissao;
                            }
                        }

                    }

                    if (data.body[i] && data.body[i][4]) {
                        if (data.body[i][4].indexOf('R$') === 0 || data.body[i][4].indexOf('-R$') === 0) {
                            if (data.body[i][4].indexOf('-R$') === 0) {
                                valorHwQuantidade = data.body[i][4].substring(4);
                                valorHwQuantidade = valorHwQuantidade.replace(".", "").replace(",", ".");
                                data.body[i][4] = '-' + valorHwQuantidade;
                            }else{
                                valorHwQuantidade = data.body[i][4].substring(3);
                                valorHwQuantidade = valorHwQuantidade.replace(".", "").replace(",", ".");
                                data.body[i][4] = valorHwQuantidade;
                            }
                        }

                    }

                    if (data.body[i] && data.body[i][5]) {
                        if (!(data.body[i][5].endsWith('%'))) {
                            regra = data.body[i][5].replace(".", "").replace(",", ".");
                            data.body[i][5] = regra;
                        }
                    }

                    let dadosHw = data.body[i][4].toString().trim();
                    if (dadosHw.startsWith('-')) {
                        devolucoes.push(data.body[i]);
                        totalHWDevolucoes += parseFloat(data.body[i][4]);
                        totalComissoesDevolucoes += parseFloat(data.body[i][6]);
                    } else {
                        vendas.push(data.body[i]);
                        totalHWVendas += parseFloat(data.body[i][4]);
                        totalComissoesVendas += parseFloat(data.body[i][6])
                    }
                }

                data.body.length = 0;
                data.body = [];

                if (vendas.length){
                    data.body.push(['*', '*', '*', 'VENDAS', '*', '*', '*']);
                    data.body.push(...vendas);
                    data.body.push(['', '', '', '', `Total HW Vendas: ${(totalHWVendas.toFixed(2)).replace('.', ',')}`,'', `Total Comissão Vendas: ${(totalComissoesVendas.toFixed(2)).replace('.',',')}`]);
                    indiceDevolucoes = data.body.length + 3;
                    indiceTotalVendas = data.body.length + 2;
                    temVendas = true;

                }
                if (devolucoes.length){
                    data.body.push(['*', '*', '*', 'DEVOLUÇÕES', '*', '*', '*']);
                    data.body.push(...devolucoes);
                    data.body.push(['', '', '', '', `Total HW Devoluções: ${(totalHWDevolucoes.toFixed(2)).replace('.', ',')}`,'', `Total Comissão Devoluções: ${(totalComissoesDevolucoes.toFixed(2)).replace('.', ',')}`]);
                    indiceTotalDevolucoes = data.body.length + 2;
                    temDevolucoes = true;
                }
            },
            customize: function (xlsx) {

                function personalizaLinha(indices) {
                    for (var j = 0; j < indices.length; j++) {
                        var rowIndex = indices[j];
                        var row = sheet.getElementsByTagName('row')[rowIndex];
                    
                        for (var i = 0; i < row.childNodes.length; i++) {
                            var cell = row.childNodes[i];
                            cell.setAttribute('s', '7');
                        
                            var textoCelula = cell.getElementsByTagName('t')[0];
                        
                            if (!textoCelula) {
                                textoCelula = document.createElement('t');
                                var isNode = document.createElement('is');
                                isNode.appendChild(textoCelula);
                                cell.appendChild(isNode);
                            }
                        
                            if (textoCelula.textContent == '*') {
                                textoCelula.textContent = '';
                            }
                        }
                    }
                }

                function personalizaLinhaTotal(indices) {
                    for (var j = 0; j < indices.length; j++) {
                        var rowIndex = indices[j];
                        var row = sheet.getElementsByTagName('row')[rowIndex];
                    
                        for (var i = 0; i < row.childNodes.length; i++) {
                            var cell = row.childNodes[i];
                            cell.setAttribute('s', '25');
                        }
                    }
                }
                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                if (temVendas && !temDevolucoes){
                    personalizaLinha([3])
                    personalizaLinhaTotal([indiceTotalVendas])
                
                }else if (!temVendas && temDevolucoes){
                    personalizaLinha([3])
                    personalizaLinhaTotal([indiceTotalDevolucoes])
                }else if (temVendas && temDevolucoes){
                    personalizaLinha([3, indiceDevolucoes])
                    personalizaLinhaTotal([indiceTotalVendas, indiceTotalDevolucoes])
                }

                temVendas = false;
                temDevolucoes = false;
                indiceDevolucoes = 0;
                indiceTotalVendas = 0;
                indiceTotalDevolucoes = 0;
            },

        },         
        {
            filename: nomeArquivoPDF,
            orientation: 'landscape',
            pageSize: 'LEGAL',
            extend: 'print',
            className: 'btn btn-primary',
            text: '<i class="fa fa-print"></i> <?= lang('imprimir') ?>',
            customize: function (win)
            {
                titulo = `Vendedor: ${nomeVendedorTitulo} | Total: ${totalComissaoTitulo.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})} <br> Cargo: ${cargoVendedor} | Chefia: ${chefiaVendedor} | Regional: ${nomeRegional} <br> Data Início: ${dataInicial} | Data Fim: ${dataFinal}`;

                printTemplateOmniComissoes(win, titulo);
            }
        }],

    })
}

    $('#pesqnomeEmpresaVendedores').select2({
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            type: 'GET',
            dataType: 'json',
            delay: 1000,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR",
    })

</script>

<script>

    $('#pesquisaVendedorEmpresa').click(function(){
        var botao = $(this);
        var idEmpresa = $('#pesqnomeEmpresaVendedores').val();

        if (!idEmpresa){
            alert('Selecione uma empresa para pesquisar os vendedores.');
            return;
        }else{
            botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendedorPorEmpresa') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idEmpresa: idEmpresa
                },
                success: function(data){
                    if (data.status === 200){
                        $('.gerarSolicitacao').show();
                        tabelaVendedores.clear().draw();
                        tabelaVendedores.rows.add(data.results).draw();
                        tabelaVendedores.columns.adjust().draw();
                    }else{
                        tabelaVendedores.clear().draw();
                        $('.gerarSolicitacao').hide();
                    }
                },
                error: function(){
                    alert('Erro ao pesquisar vendedores. Tente novamente.');
                    tabelaVendedores.clear().draw();
                    botao.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
                    $('.gerarSolicitacao').hide();
                },
                complete: function(){
                    botao.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
                }
            })
        }

        if ($('#checkTodos').prop('checked')){
            $('#checkTodos').prop('checked', false);
        }
    })

     function parseValueToFloat(value) {
        if (!value.includes(",")) {
            if (Number.isNaN(Number.parseFloat(value))) {
                return 0;
            }
            return parseFloat(value);            
        }
        value = value.replace(".", "").replace(",", ".");
        if (Number.isNaN(Number.parseFloat(value))) {
            return 0;
        }
        return parseFloat(value);
    }

    $('#solicitarCalculo').click(function(){
        var dataInicio = $('#dataIni').val();
        var dataFim = $('#dataF').val();

        var parte1 = dataInicio.split('-');
        var anoInicio = parseInt(parte1[0]);
        var mesInicio = parseInt(parte1[1]);

        var parte2 = dataFim.split('-');
        var anoFim = parseInt(parte2[0]);
        var mesFim = parseInt(parte2[1]);

        dataInicio = dataInicio.split('-').reverse().join('/');
        dataFim = dataFim.split('-').reverse().join('/');

        vendedoresSelecionados = [];
        idsVendedores = [];
        tabelaVendedores.rows().every(function(){
            var row = this.node();
            var check = $(row).find('input[type="checkbox"]');
            if (check.prop('checked')){
                vendedoresSelecionados.push($(row).find('td:nth-child(2)').text());
            }
        })
        
        if (vendedoresSelecionados.length){
            tabelaVendedores.rows().data().each(function(row, index){
                vendedoresSelecionados.forEach(function(vendedor){
                    if (row.nome == vendedor){
                        idsVendedores.push(row.id);
                    }
                })
            })
        }
        if (!idsVendedores.length){
            alert('Selecione, pelo menos, um vendedor para realizar a solicitação.');
            return;
        }else if (!dataInicio || !dataFim){
            alert('Selecione o período corretamente para realizar a solicitação.');
            return;
        }else if (!(anoInicio == anoFim && mesInicio == mesFim)){
            alert('O intervalo de dias precisam ser do mesmo mês e ano.');
            return;
        }else{

            $('#solicitarCalculo').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Solicitando...');
            document.querySelector('#loading').setAttribute('data-content', 'Solicitando cálculo(s)...')
			document.getElementById('loading').style.display = 'block';
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/solicitarCalculoVendedoresSelecionados') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idVendedores: idsVendedores,
                    dataInicio: dataInicio,
                    dataFim: dataFim
                },
                success: function(data){
                    if (data.status === 200){
                        setTimeout(function(){
                            document.querySelector('#loading').setAttribute('data-content', 'Cálculo(s) solicitado(s) com sucesso. Redirecionando para solicitações...');
                        }, 2000);
                        setTimeout(function(){
                            document.getElementById('loading').style.display = 'none';
                        }, 5000);
                        setTimeout(function(){
                            $('#tab-solicitacoesComissao').click();
                        }, 5000);
                        
                    }else if (data.status === 400 && data.dados.mensagem){
                        setTimeout(function(){
                            document.querySelector('#loading').setAttribute('data-content', data.dados.mensagem)
                        }, 2000);
                        setTimeout(function(){
                            document.getElementById('loading').style.display = 'none';
                        }, 5000);
                    }else if (data.status === 404 && data.dados.mensagem){
                        setTimeout(function(){
                            document.querySelector('#loading').setAttribute('data-content', data.dados.mensagem)
                        }, 2000);
                        setTimeout(function(){
                            document.getElementById('loading').style.display = 'none';
                        }, 5000);
                    }else{
                        setTimeout(function(){
                            document.querySelector('#loading').setAttribute('data-content', 'Não foi possível solicitar o(s) cálculo(s). Tente novamente.')
                        }, 2000);
                        setTimeout(function(){
                            document.getElementById('loading').style.display = 'none';
                        }, 5000);
                        
                    }
                },
                error: function(){
                    document.querySelector('#loading').setAttribute('data-content', 'Erro ao solicitar cálculo(s). Tente novamente.')
                    setTimeout(function(){
                        document.getElementById('loading').style.display = 'none';
                    }, 3000);
                    $('#solicitarCalculo').attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Solicitar Cálculo(s)');
                },
                complete: function(){
                    $('#solicitarCalculo').attr('disabled', false).html('<i class="fa fa-paper-plane" aria-hidden="true"></i> Solicitar Cálculo(s)');
                }
            }); 
        }
    });

    let processando = false;
    var tabelaComissoesSolicitadas = $('#tabelaComissoesSolicitadas').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        autoWidth: false,
        order : [[ 0, "desc" ]],
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhuma comissão solicitada a ser listada",
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
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarSolicitacoesCalculoComissao') ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                processando = true;
                    $('#tabelaComissoesSolicitadas > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                        '</tr>'
                    );
                },
            success: function(data){
                if (data.status === 200){
                    tabelaComissoesSolicitadas.clear().draw();
                    tabelaComissoesSolicitadas.rows.add(data.results).draw();
                    tabelaComissoesSolicitadas.columns.adjust().draw();
                }else{
                    tabelaComissoesSolicitadas.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar solicitações de cálculo');
                tabelaComissoesSolicitadas.clear().draw();
                processando = false;
            },
            complete: function(){
                processando = false;
            }
        },
        columns: [
            {data: 'id', className: 'text-center'},
            {data: 'dataInicio', className: 'text-center',
                render: function(data){
                    return data ? data.split('-').reverse().join('/') : '-';
                }
            },
            {data: 'dataFim',  className: 'text-center',
                render: function(data){
                    return data ? data.split('-').reverse().join('/') : '-';
                }
            },
            {data: 'dataCadastro',  className: 'text-center',
                render: function(data){
                    return new Date(data).toLocaleDateString();
                }
            },
            {data: 'dataUpdate',  className: 'text-center',
                render: function(data){
                    return new Date(data).toLocaleDateString();
                }
            },
            {data: 'status', className: 'text-center',
                render: function(data){
                    let status = '';
                    switch (data){
                        case 'Pendente':
                            status = '<span class="label label-info" style="font-size: 11px;">Pendente</span>';
                            break;
                        case 'Em_Processamento':
                            status = '<span class="label label-warning" style="font-size: 11px;">Em Processamento</span>';
                            break;
                        case 'Processado':
                            status = '<span class="label label-success" style="font-size: 11px;">Processado</span>';
                            break;
                        case 'Falha_ao_Processar':
                            status = '<span class="label label-danger" style="font-size: 11px;">Falha ao Processar</span>';
                            break;
                        default:
                            status = '<span class="label label-danger" style="font-size: 11px;">Falha ao Processar</span>';
                            break;
                    }
                    return status;
                }
            },
            {data: {'id':'id'}, className: 'text-center',
                render: function(data){
                    var botaoVisualizar =  `
                        <button 
					    	class="btn btn-primary"
					    	title="Visualizar Comissões Calculadas"
					    	id="btnVisualizarComissoesCalculadas"
                            onClick="javascript:visualizarComissoesCalculadas(this,'${data['id']}', '${data['dataInicio']}', '${data['dataFim']}')">
					    	<i class="fa fa-file-text" aria-hidden="true"></i>
					    </button>
                    `;
                    if (data['status'] == 'Processado'){
                        return botaoVisualizar;
                    }else{
                        return '';
                    }
                }
            },
        ],
    })
    var tabelaComissoesCalculadasSolicitacao;
    function visualizarComissoesCalculadas(botao, id, dataInicio, dataFim){
        botao = $(botao);
        var dataInicial = dataInicio.split('-').reverse().join('/');
        var dataFinal = dataFim.split('-').reverse().join('/');
        var data = dataInicio + " 00:00:00";
        data = new Date(data);
        const meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
        var dataNomeArquivo = `${meses[data.getMonth()]}${data.getFullYear()}`;
        var nomeArquivo = `Comissões Calculadas-${dataNomeArquivo}`;

        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        
        if(tabelaComissoesCalculadasSolicitacao){
             tabelaComissoesCalculadasSolicitacao.destroy();
        }

        inicializarTabelaItensComissaoCalculadaPorSolicitacao(nomeArquivo, dataInicial, dataFinal);
        
        
        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarComissoesCalculadasPorSolicitacao') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idSolicitacao: id
            },
            success: function(data){
                if (data.status === 200){
                    tabelaComissoesCalculadasSolicitacao.clear().draw();
                    tabelaComissoesCalculadasSolicitacao.rows.add(data.results).draw();
                    tabelaComissoesCalculadasSolicitacao.columns.adjust().draw();
                    $('#tabelaComissoesCalculadasSolicitacao_wrapper').css('width', 'fit-content');
                    $('#periodoHeader').html(`Período: ${dataInicial} a ${dataFinal}`);
                    $('#modalComissoesCalculadasSolicitacao').modal('show');
                }else if (data.status === 400 && data.results.mensagem){
                    alert(data.results.mensagem);
                }else if (data.status === 404){
                    alert(data.results.mensagem ? data.results.mensagem : 'Não foi possível buscar as comissões calculadas.');
                }else{
                    alert('Não foi possível buscar as comissões calculadas. Tente novamente.');
                }
            },
            error: function(){
                alert('Erro ao buscar comissões calculadas. Tente novamente');
            },
            complete: function(){
                botao.attr('disabled', false).html('<i class="fa fa-file-text" aria-hidden="true"></i>');
            }
        })

    }
    function inicializarTabelaItensComissaoCalculadaPorSolicitacao(nomeArquivo, dataInicial, dataFinal){
        tabelaComissoesCalculadasSolicitacao = $('#tabelaComissoesCalculadasSolicitacao').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            order: [[ 0, "desc" ]],
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhuma comissão calculada a ser listada",
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
            columns: [
                { data: 'idComissao',
                    visible: false},
                {data: 'matricula',
                    render: function(data){
                        return data ? data : '-';
                    }
                },    
                { data: 'nomeVendedor',
                    render: function(data){
                        return data ? data : '-';
                    }
                },
                { data: 'nomeCargo',
                    render: function(data){
                        return data ? data : '-';
                    }
                },
                { data: 'codCentroResultado',
                    visible: false,
                    render: function(data){
                        return data ? data : '-';
                    }
                },
                { data: 'nomeCentroResultado',
                    visible: false,
                    render: function(data){
                        return data ? data : '-';
                    }
                },
                { data: 'nomeEmpresa',
                    visible: false,
                    render: function(data){
                        return data ? data : '-';
                    }
                },
                { data: 'salario',
                    render: function (data) {
                        return data ? parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00';
                    }
                },
                { data: 'dataAdmissao',
                    visible: false,
                    render: function (data) {
                        return data ? data.split('-').reverse().join('/') : '-';
                    }
                },
                { data: 'chefiaImediata',
                    visible: false,
                    render: function(data){
                        return data ? data : '-';
                    }
                },
                { data: 'executivo',
                    render: function (data) {
                        return data ? parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00'
                    }
                },
                { data: 'gestor',
                    render: function (data) {
                        return data ? parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00'
                    }
                },
                { data: 'televendas',
                    render: function (data) {
                        return data ? parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00'
                    }
                },
                { data: 'valorTotalComissao',
                    render: function (data) {
                        return data ? parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00'
                    }
                },
                { data: 'valorGarantia',
                    render: function (data) {
                        return data ? parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00'
                    }
                },
                { data: 'valorTotalReceber',
                    render: function (data) {
                        return data ? parseFloat(data).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00'
                    }
                },
                {data: {'id':'id'}, className: 'text-center',
                    render: function(data){
                        return `
                            <button 
	    			            class="btn btn-primary"
	    			            title="Visualizar Itens da Comissão"
	    			            id="btnVisualizarItensComissao"
                                onClick="javascript:visualizarItensComissao(this,'${data['idComissao']}', '${data['valorTotalComissao']}', '${data['nomeVendedor']}', '${data['dataInicio']}', '${data['dataFim']}', '${data['nomeCargo']}', '${data['chefiaImediata']}', '${data['nomeRegional']}', '${data['teveCampanha']}', '${data['valorTotalVendas']}', '${data['valorTotalDevolucoes']}', event)">
	    			            <i class="fa fa-file-text" aria-hidden="true"></i>
	    			        </button>
                        `;
                    }
                },
            ],
            columnDefs: [
                {
                    targets: [1,2,4,7,8,10,11,12,13,14,15],
                    className: 'dt-center',
                },
                {
                    targets: [3,5,6,9],
                    className: 'dt-left'
                },
            ],
            dom: 'Blfrtip',
            buttons:[
                {
                    filename: nomeArquivo,
                    pageSize: 'A3',
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> <?= lang('gerar_pdf') ?>',
                    customize: function ( doc , tes)
                    {   
                        const titulo = `Comissões Calculadas\nPeríodo: ${dataInicial} a ${dataFinal}`;
                        const tamanhoColunas = [50, 80, 70, 30, 80, 60, 55, 60, 80, 55, 55, 55, 55, 55, 55];
                        const alinhamentoColunas = ['center', 'left', 'left', 'center', 'left', 'left', 'center', 'center', 'left', 'center', 'center', 'center', 'center', 'center', 'center']; 
                        // Personaliza a página do PDF
                        pdfTemplateIsolated(doc, titulo, 'A3', tamanhoColunas, alinhamentoColunas);
                    },
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                    }
                },
                {
                    filename: nomeArquivo,
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    extend: 'excel',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> <?= lang('gerar_excel') ?>',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15],
                    },
                    messageTop: function () {
                        return `Período: ${dataInicial} a ${dataFinal}`;
                    },
                },
                {
                    filename: nomeArquivo,
                    pageSize: 'LEGAL',
                    extend: 'print',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> <?= lang('imprimir') ?>',
                    customize: function (win)
                    {
                        win.document.head.insertAdjacentHTML('beforeend', '<style>@page { size: legal landscape; }</style>');
                        const titulo = `Comissões Calculadas <br> Período: ${dataInicial} a ${dataFinal}`;
                        printTemplateOmniComissoes(win, titulo);
                    },
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15],
                    }
                }
            ]
        })

    }

    $('#modalItensComissaoCalculada').on('hidden.bs.modal', function (e) {
        if ($('#modalComissoesCalculadasSolicitacao').css('display') == 'block'){
            $('body').addClass('modal-open');
            $('#modalComissoesCalculadasSolicitacao').modal('handleUpdate');
            $('#modalComissoesCalculadasSolicitacao').focus();
        }
    });

function visualizarItensComissaoVendedor(botao, idComissao, totalComissao, nomeVendedor, dataInicial, dataFinal, nomeCargo, chefiaImediata, teveCampanha, valorTotalVendas, valorTotalDevolucoes, regional, event){
    event.preventDefault();
    botao = $(botao);
    totalComissaoTitulo = parseFloat(totalComissao);
    nomeVendedorTitulo = nomeVendedor;
    dataInicialTitulo = new Date(dataInicial).toLocaleDateString();
    dataFinalTitulo = new Date(dataFinal).toLocaleDateString();
    cargoVendedor = nomeCargo ? nomeCargo : '';
    chefiaVendedor = chefiaImediata != null && chefiaImediata != 'null' ? chefiaImediata : '';
    const meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
    dataNomeArquivo = new Date(dataInicial);
    dataNomeArquivo = `${meses[dataNomeArquivo.getMonth()]}${dataNomeArquivo.getFullYear()}`;
    nomeArquivoPDF = `${nomeVendedorTitulo}-${cargoVendedor}-${dataNomeArquivo}`;
    var nomeRegional = regional ? regional : '';
    var campanha = teveCampanha == 1 ? true : false;
    var valorTotalHw = parseFloat(valorTotalVendas) + parseFloat(valorTotalDevolucoes);


    if (tabelaItensComissaoCalculada){
        tabelaItensComissaoCalculada.destroy();
    }

    inicializarTabelaItensComissaoCalculada(nomeArquivoPDF, nomeVendedorTitulo, cargoVendedor, chefiaVendedor, dataInicialTitulo, dataFinalTitulo, totalComissaoTitulo, nomeRegional, campanha, valorTotalHw);
    
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    $.ajax({
        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarItensComissaoCalculadaTeveCampanha') ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            idComissao: idComissao
        },
        success: function(data){
            if (data.status == 200){
                tabelaItensComissaoCalculada.clear().draw();
                tabelaItensComissaoCalculada.rows.add(data.results).draw();
                totalComissao ? $('#labelTotalComissao').html('Total: ' + (totalComissaoTitulo.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }))) : ''
                nomeVendedorTitulo ? $('#labelNomeVendedor').html('Vendedor: ' + nomeVendedorTitulo) : ''
                $('#modalItensComissaoCalculada').modal('show');
            }else if(data.status == 400 && data.results.mensagem){
                alert(data.results.mensagem);
                tabelaItensComissaoCalculada.clear().draw();
            }else{
                alert('Não foi possível buscar os itens da comissão calculada. Tente novamente.');
                tabelaItensComissaoCalculada.clear().draw();
            }
        },
        error: function(){
            alert('Erro ao buscar itens da comissão calculada. Tente novamente');
            tabelaItensComissaoCalculada.clear().draw();
            botao.attr('disabled', false).html('<i class="fa fa-file-text" aria-hidden="true"></i>');
        },
        complete: function(){
            botao.attr('disabled', false).html('<i class="fa fa-file-text" aria-hidden="true"></i>');
        }
    })
}
</script>
