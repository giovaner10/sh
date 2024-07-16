
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

    th.dt-center, td.dt-center { 
        text-align: center !important; 
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

    .select2-selection__rendered {
        line-height: 35px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
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
    .subtitle{
        text-align: center;
    }
</style>

<h3><?=lang("solicitacao_expedicao")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
	<?=lang('solicitacao_expedicao')?>
</div>

<div id="modalSE" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3><?=lang("nova_solicitacao_expedicao")?></h3>
            </div>
            <div class="modal-body scrollModal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content" style="padding: 0 10px;">
                            <form name="formSE" id="formSE">
                                <input type="hidden" id="id" style="display: none;"><h4 class="subtitle">Informações do Cliente</h4>
                                
                                <div class="row">
                                    <div id ="divBuscarPor"class="col-md-2 col-sm-2 form-group  style="border-left: 3px solid #03A9F4"bord" >
                                        <label>Pesquisar por:</label>
                                        <select class="form-control input-sm" name="buscar" id="tipo-busca-expedicao">
                                            <option value="0">Id</option>
                                            <option value="1">Nome</option>
                                            <option value="2">Documento</option>
                                        </select>
                                    </div>
                                    <div id ="divNomeCliente" class="col-md-8 col-sm-6" style="border-left: 8px solid #03A9F4;">
                                        <label>Cliente:</label>
                                        <select class="form-control input-sm" name="cliente" id="cliente-expedicao" type="text" style="width: 100%;">
                                        </select>
                                    </div>
                                    <div id ="divDocCliente" class="col-md-6 col-sm-6 bord" hidden>
                                        <label>Cliente:</label>
                                        <input class="form-control input-sm" name="clienteDoc" id="cliente-expedicaoDoc" type="text" placeholder="Digite o CPF/CNPJ do cliente">
                                        </input>
                                    </div>
                                    <div id ="divIdCliente" class="col-md-6 col-sm-6 bord" style="display: none;">
                                        <label>Cliente:</label>
                                        <input class="form-control input-sm" name="clienteId" id="cliente-expedicaoId" type="text" placeholder="Digite o ID do cliente">
                                        </input>
                                    </div>

                                    <div id ="divBtnPesquisaCliente" class="col-md-2 col-sm-2" style="display: contents;" hidden>
                                        <button id="btnPesquisaClienteDoc" class="btn btn-primary" type="button" style="margin-top: 23px;">Buscar</button>
                                        <button id="btnLimparPesquisaClienteDoc" class="btn btn-danger" type="button" style="margin-top: 23px;">Limpar</button>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="subtitle">Solicitação</h4>
                                <div class="row">
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">Tipo de Solicitação</label>
                                        <div class="control-group" >
                                            <label class="radio-inline">
                                                <input type="radio" name="tipoSolicitacao" value="0" required /> Instalação
                                            </label> 
                                            <label class="radio-inline">
                                                <input type="radio" name="tipoSolicitacao" value="1" required /> Manutenção
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="tipoSolicitacao" value="2" required /> Insumos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">Solicitante</label>
                                        <input type="text" class="form-control" id="solicitante" placeholder="Digite o email do solicitante" value=<?php echo $emailUsuario; ?> readonly>
                                    </div>
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">Data: </label>
                                        <input type="date" class="form-control date" id="data" readonly value=<?php echo date("Y-m-d"); ?>>
                                    </div>
                                </div>
                                <hr>
                                
                                <h4 class="subtitle">Informações para Envio</h4>
                                <div class="row">
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">CEP: </label>
                                        <input type="text" class="form-control" id="cep" placeholder="Digite o cep">
                                    </div>
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">Estado: </label>
                                        <input type="text" class="form-control" id="estado" placeholder="Digite o estado">
                                    </div>
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">Cidade: </label>
                                        <input type="text" class="form-control" id="cidade" placeholder="Digite a cidade">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">Bairro: </label>
                                        <input type="text" class="form-control" id="bairro" placeholder="Digite o bairro">
                                    </div>
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">Logradouro: </label>
                                        <input type="text" class="form-control" id="logradouro" placeholder="Digite o logradouro">
                                    </div>
                                    <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                        <label class="control-label">N° / Complemento: </label>
                                        <input type="text" class="form-control" id="complemento" placeholder="Digite o N° / complemento">
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <h4 class="subtitle">Especificações Contratuais</h4>
                            <div class="row">                                
                                <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                    <label>Serial:</label>
                                    <select class="form-control" name="equipamento" id="equipamento" type="text" placeholder="Digite o Serial do Equipamento"></select>
                                </div>
                                <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Referência:</label>
                                    <input class="form-control" name="referencia" id="referencia" type="text" placeholder="Digite a referência ou selecione o serial">
                                </div>
                                <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Operadora: </label>
                                    <select class="form-control" id="operadora" name="nome" type="text" style="width: 100%;">
                                        <option value="0" selected disabled>Buscando...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Informações para Configuração: </label>
                                    <input type="text" class="form-control optional" id="info_config" placeholder="Digite a Informação">
                                </div>
                                <div class="col-md-12 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Informações para Estoque: </label>
                                    <input type="text" class="form-control optional" id="info_estoque" placeholder="Digite a Informação">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Sirene: </label>
                                    <!-- <input type="text" class="form-control" id="sirene" placeholder="Digite a Sirene"> -->
                                    <select class="form-control" name="sirene" id="sirene" placeholder="Digite a Sirene">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label class="control-label">Qtd.: </label>
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="sirene-qtd" placeholder="Digite a Quantidade">
                                </div>
                                <div class="col-md-2 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Leitor: </label>
                                    <!-- <input type="text" class="form-control" id="leitor" placeholder="Digite o Leitor"> -->
                                    <select class="form-control" name="leitor" id="leitor" placeholder="Digite a Leitor">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label class="control-label">Qtd.: </label>
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="leitor-qtd" placeholder="Digite a Quantidade">
                                </div>
                                <div class="col-md-2 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">IButton: </label>
                                    <!-- <input type="text" class="form-control" id="ibutton" placeholder="Digite o IButton"> -->
                                    <select class="form-control" name="ibutton" id="ibutton" placeholder="Digite a IButton">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label class="control-label">Qtd.: </label>
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="ibutton-qtd" placeholder="Digite a Quantidade">
                                </div>
                                <div class="col-md-2 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Rele 12V: </label>
                                    <!-- <input type="text" class="form-control" id="rele_12v" placeholder="Digite a Rele"> -->
                                    <select class="form-control" name="rele_12v" id="rele_12v" placeholder="Digite a Rele">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label class="control-label">Qtd.: </label>
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="rele_12v-qtd" placeholder="Digite a Quantidade">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Rele 24V: </label>
                                    <!-- <input type="text" class="form-control" id="rele_24v" placeholder="Digite a Rele"> -->
                                    <select class="form-control" name="rele_24v" id="rele_24v" placeholder="Digite a Rele">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label class="control-label">Qtd.: </label>
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="rele_24v-qtd" placeholder="Digite a Quantidade">
                                </div>
                                <div class="col-md-2 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Pânico: </label>
                                    <!-- <input type="text" class="form-control" id="panico" placeholder="Digite o Pânico"> -->
                                    <select class="form-control" name="panico" id="panico" placeholder="Digite a Pânico">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label class="control-label">Qtd.: </label>
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="panico-qtd" placeholder="Digite a Quantidade">
                                </div>
                                <div class="col-md-2 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Outros: </label>
                                    <!-- <input type="text" class="form-control" id="outros" placeholder="Digite Outros"> -->
                                    <select class="form-control" name="outros" id="outros" placeholder="Digite a Outros">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label class="control-label">Qtd.: </label>
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="outros-qtd" placeholder="Digite a Quantidade">
                                </div>
                                <div class="col-md-2 form-group">
                                    <a class="btn btn-primary" id="AdicionarEquipamento" style="width:100%; margin-top: 23px;">Adicionar</a>
                                </div>
                            </div>

                            <table class="table-responsive table-bordered table" id="tabelaEquipamento">
                                <thead>
                                    <tr class="tableheader">
                                        <th hidden>ID</th>
                                        <th>Equipamento</th>
                                        <th>Referência</th>
                                        <th>Operadora</th>
                                        <th>Info. Configuração</th>
                                        <th>Info. Estoque</th>
                                        <th>Sirene</th>
                                        <th>Leitor</th>
                                        <th>IButton</th>
                                        <th>Rele 12V</th>
                                        <th>Rele 24V</th>
                                        <th>Pânico</th>
                                        <th>Outros</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>  

                            <hr>
                            <h4 class="subtitle">Informações Complementares</h4>
                            
                            <div class="row">
                                <div class="col-md-4 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Veiculo: </label>
                                    <select class="form-control input-sm" name="addveiculo" id="addveiculo" type="text" style="width: 100%;">
                                    </select>
                                </div>
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Informação Adicional: </label>
                                    <input type="text" class="form-control" id="obs" placeholder="Digite a Informação Adicional">
                                </div>
                                <div class="col-md-2 form-group">
                                    <a class="btn btn-primary" id="AdicionarVeiculo" style="width:100%; margin-top: 23px;">Adicionar</a>
                                </div>
                            </div>

                            <table class="table-responsive table-bordered table" id="tabelaComplementar">
                                <thead>
                                    <tr class="tableheader">
                                        <th hidden>ID</th>
                                        <th>Veiculo</th>
                                        <th>Placa</th>
                                        <th>Serial</th>
                                        <th>Observações</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                <a class="btn btn-primary" id="btnSalvarSE">Salvar</a>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info col-md-12">
    <div class ="col-md-12">
        <p class ="col-md-12">Selecione o <u>Tipo de Pesquisa</u> e informe os dados para localizar a solicitação de expedição desejada e clique em pesquisar.</p>
        <span class="help help-block"></span>
        <form action="post" id="formPesquisa">
            <div class="col-md-2">
                <label class="col-md-12" style="margin: 0; padding: 0;">Pesquisar por:</label>
                <select type="text" id="sel-pesquisa" class="form-control" disabled>
                    <option value="0">Número da SE</option>
                    <option value="1">Nome do Cliente</option>
                    <option value="2">CPF do Cliente</option>
                    <option value="3">CNPJ do Cliente</option>
                    <option value="4">Email do Cliente</option>
                    <option value="5">ID do Cliente</option>
                    <option value="6">Número de Contrato</option>
                </select>
            </div>
            <div id="pesquisa" class="form-group col-md-3" style="padding-left: 0px;">
                <label class="col-md-12" style="margin: 0; padding: 0;">Número da SE: </label>
                <select class="form-control solicitacaoId" type="text" id="solicitacaoId" name="solicitacaoId" disabled><option value="busca">Buscando...</option></select>
            </div>
            <div id="pesquisaNomeCliente" class="form-group col-md-3" style="padding-left: 0px;">
                <label class="col-md-12" style="margin: 0; padding: 0;">Nome do Cliente: </label>
                <select class="form-control nomeCliente" id="pesqNomeCliente" name="nomeCliente" type="text"></select>
            </div>
            <div id="pesquisaCPF" class="form-group col-md-3" style="padding-left: 0px;">
                <label class="col-md-12" style="margin: 0; padding: 0;">CPF do Cliente: </label>
                <input placeholder="Digite o CPF do cliente..." class="form-control cpf" id="pesqCPF" name="cpf" type="text"/>
            </div>
            <div id="pesquisaCNPJ" class="form-group col-md-3" style="padding-left: 0px;">
                <label class="col-md-12" style="margin: 0; padding: 0;">CNPJ do Cliente: </label>
                <input placeholder="Digite o CNPJ do cliente..." class="form-control cnpj" id="pesqCNPJ" name="cnpj" type="text"/>
            </div>
            <div id="pesquisaEmailCliente" class="form-group col-md-3" style="padding-left: 0px;">
                <label class="col-md-12" style="margin: 0; padding: 0;">Email do Cliente: </label>
                <input placeholder="Digite o email do cliente..." class="form-control emailCliente" id="pesqEmailCliente" name="emailCliente" type="email"></select>
            </div>
            <div id="pesquisaIdCliente" class="form-group col-md-3" style="padding-left: 0px;">
                <label class="col-md-12" style="margin: 0; padding: 0;">ID do Cliente: </label>
                <input placeholder="Digite o ID do cliente..." class="form-control idCliente" id="pesqIdCliente" name="idCliente" type="number" min=0></select>
            </div>
            <div id="pesquisaNumeroContrato" class="form-group col-md-3" style="padding-left: 0px;">
                <label class="col-md-12" style="margin: 0; padding: 0;">Número do Contrato: </label>
                <input placeholder="Digite o número do contrato..." class="form-control numeroContrato" id="pesqNumeroContrato" name="numeroContrato" type="number" min=0></select>
            </div>
            <div class="form-group col-md-4" style="margin-bottom: 0; padding: 0;">
                <button class="btn btn-primary col-md-4" id="pesquisaId" type="submit" style="margin-top: 20px; margin-right: 15px; padding: 6px;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                <button class="btn btn-primary col-md-4" id="BtnLimparPesquisar" type="button" style="margin-top: 20px; padding: 6px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
            </div>  
        </form>
    </div>
</div>

<div class="container-fluid my-1">
	<div class="col-sm-12">
        <div id="solicitacoes" class="tab-pane fade in active" style="margin-top: 10px">
            <div class="container-fluid" id="divTabelaSolicitacoes">
		        	<a id="abrirModalCadastrar" class="btn btn-primary" style="margin-bottom: 20px"><?=lang("nova_solicitacao")?></a>
                <table class="table-responsive table-bordered table" id="tabelaSolicitacoes">
                    <thead>
                        <tr class="tableheader">
                        <th>Número da SE</th>
                        <th>Tipo</th>
                        <th>Solicitante</th>
                        <th>Status Expedição</th>
                        <th hidden>Status</th>
                        <th>Data</th>
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
<hr/>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>

<script>
    var listaEquipamento = []
    var listaVeiculo = []

    $.fn.dataTable.moment( 'DD/MM/YYYY' );

    var tabelaSolicitacoes = $('#tabelaSolicitacoes').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        autoWidth: false,
        order: [[5, 'desc'], [0, 'desc']],
        language: {
           loadingRecords: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b>',
           searchPlaceholder:  'Pesquisar',
           emptyTable:         "Nenhum tipo de movimento a ser listado",
           info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
           infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
           zeroRecords:        "Nenhum resultado encontrado.",
           paginate: {
               first:          "Primeira",
               last:           "Última",
               next:           "Próxima",
               previous:       "Anterior"
           },
           infoFiltered: "(filtrado de _MAX_ registros no total)" 
        },
        deferRender: true,
        lengthChange: false,
        ajax:{
            url: '<?= site_url('SolicitacaoExpedicao/listarSolicitacoes') ?>',
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $('#tabelaSolicitacoes > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty" style="align-items: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data){
                if (data.status === 200){
                    tabelaSolicitacoes.clear().draw();
                    tabelaSolicitacoes.rows.add(data.dados).draw();
                } else {
                    tabelaSolicitacoes.clear().draw();
                }
            },
            error: function(erro){
                alert('Erro ao listar as Solicitações');
                tabelaSolicitacoes.clear().draw();
            }
        },
        columnDefs: [
            {className: "dt-center", targets: "_all"}
        ],
        columns: [
            { data: 'id', width: "15%"},
            { data: 'tipoSolicitacao', width: "15%"},
            { data: 'solicitante',  width: "25%"},
            { data: 'statusExpedicao',  width: "15%"},
            { data: 'status', visible: false},
            { 
                data: 'dataSE',
                render: function (data){
                    var date = new Date(data);
                    date.setDate(date.getDate() + 1); // Adicionar 1 dia à data

                    return date.toLocaleDateString('pt-BR');
                },
                width: "10%"
            },
            {
				data: null,
				orderable: false,
				render: function (data) {
                return `
                    <button 
                        class="btn btn-primary"
                        title="Editar SE"
                        data-id="${data.id}"
                        style="width: auto; margin: 0 auto;text-align: center;"
                        disabled
                        id="btnEditarSE"                            
                        >
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    `;
                },
                width: "20%"
			}
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                filename: filenameGenerator("Solicitação de Expedição"),
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                filename: filenameGenerator("Solicitação de Expedição"),
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function ( doc , tes)
                {                
                    titulo = `Solicitação de Expedição`;
                    // Personaliza a página do PDF
                    pdfTemplateIsolated(doc, titulo)
                }
            },
            {
                filename: filenameGenerator("Solicitação de Expedição"),
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                filename: filenameGenerator("Solicitação de Expedição"),
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function ( win )
                {
                    titulo = `Solicitação de Expedição`;
                    // Personaliza a página Impressale
                    printTemplateOmni(win, titulo);
                }
            }
        ]
    });

    var tabelaComplementar = $('#tabelaComplementar').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
           loadingRecords: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b>',
           searchPlaceholder:  'Pesquisar',
           emptyTable:         "Nenhum veiculo a ser listado",
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
            { data: 'id' ,
                visible: false},
            { data: 'veiculo'},
            { data: 'placa'},
            { data: 'serial'},
            { data: 'observacoes'},
            { 
                data: null,
				render: function (data) {
					return  `
					<button 
						class="btn btn-danger removeComplemento"
						title="Remover Complemento"
						style="width: 38px; margin: 0 auto;">
						<i class="fa fa-trash" aria-hidden="true"></i>
					</button>
                    `;
				} 
            },
        ]
    });

    
    var tabelaEquipamento = $('#tabelaEquipamento').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
           loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
           searchPlaceholder:  'Pesquisar',
           emptyTable:         "Nenhum veiculo a ser listado",
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
            { data: 'id' ,
                visible: false},
            { data: 'equipamento'},
            { data: 'referencia'},
            { data: 'operadora'},
            { data: 'info_config'},
            { data: 'info_estoque'},
            { 
                data: null,
				render: function (data) {
					return data['sireneTxt']+' - '+ data['sirene-qtd'];
				} 
            },
            { 
                data: null,
				render: function (data) {
					return data['leitorTxt']+' - '+ data['leitor-qtd'];
				} 
            },
            { 
                data: null,
				render: function (data) {
					return data['ibuttonTxt']+' - '+ data['ibutton-qtd'];
				} 
            },
            { 
                data: null,
				render: function (data) {
					return data['rele_12vTxt']+' - '+ data['rele_12v-qtd'];
				} 
            },
            { 
                data: null,
				render: function (data) {
					return data['rele_24vTxt']+' - '+ data['rele_24v-qtd'];
				} 
            },
            { 
                data: null,
				render: function (data) {
					return data['panicoTxt']+' - '+ data['panico-qtd'];
				} 
            },
            { 
                data: null,
				render: function (data) {
					return data['outrosTxt']+' - '+ data['outros-qtd'];
				} 
            },
            { 
                data: null,
				render: function (data) {
					return  `
					<button 
						class="btn btn-danger removeEquipamento"
						title="Remover Equipamento"
						style="width: 38px; margin: 0 auto;">
						<i class="fa fa-trash" aria-hidden="true"></i>
					</button>
                    `;
				} 
            },
        ]
    });

    $('#tabelaEquipamento tbody').on( 'click', '.removeEquipamento', function () {
        let row = $(this).parents('tr');
        let rowID = row[0]['_DT_RowIndex'];
        
        tabelaEquipamento
            .row( row )
            .remove()
            .draw();

        listaEquipamento.splice(rowID, 1);
    });

    $('#tabelaComplementar tbody').on( 'click', '.removeComplemento', function () {
        let row = $(this).parents('tr');
        let rowID = row[0]['_DT_RowIndex'];
        
        tabelaComplementar
            .row( row )
            .remove()
            .draw();

        listaVeiculo.splice(rowID, 1);
    });

    $(document).ready(async() => {
        $('#pesquisaNomeCliente').hide();
        $('#pesqNomeCliente').attr('disabled', true);
        $('#pesquisaCPF').hide();
        $('#pesqCPF').attr('disabled', true);
        $('#pesquisaCNPJ').hide();
        $('#pesqCNPJ').attr('disabled', true);
        $('#pesquisaEmailCliente').hide();
        $('#pesqEmailCliente').attr('disabled', true);
        $('#pesquisaIdCliente').hide();
        $('#pesqIdCliente').attr('disabled', true);
        $('#pesquisaNumeroContrato').hide();
        $('#pesqNumeroContrato').attr('disabled', true);

        $("#pesqCPF").inputmask({
            mask: ["999.999.999-99"],
            keepStatic: true,
            placeholder: " ",
            });

        $("#pesqCNPJ").inputmask({
            mask: ["99.999.999/9999-99"],
            keepStatic: true,
            placeholder: " ",
        });           

        $('.nomeCliente').select2({
            ajax: {
                url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
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
            width: "100%",
            placeholder: "Selecione o cliente...",
            allowClear: true,
            minimumInputLength: 3,
            language: "pt-BR"
        });  

        const proprietarios = await preencherExpedicaoSelect();
    });

    $('#sel-pesquisa').on('change', function() {
        let tipo = $('#sel-pesquisa').val();
        $('.solicitacaoId').val(null).trigger('change');
        $('.nomeCliente').val(null).trigger('change');
        $('.cpf').val('');
        $('.cnpj').val('');
        $('.emailCliente').val('');
        $('.idCliente').val('');
        $('.numeroContrato').val('');

        if (tipo == 0) {
            $('#pesquisa').show();
            $('#solicitacaoId').attr('disabled', false);
            $('#pesquisaNomeCliente').hide();
            $('#pesqNomeCliente').attr('disabled', true);
            $('#pesquisaCPF').hide();
            $('#pesqCPF').attr('disabled', true);
            $('#pesquisaCNPJ').hide();
            $('#pesqCNPJ').attr('disabled', true);
            $('#pesquisaEmailCliente').hide();
            $('#pesqEmailCliente').attr('disabled', true);
            $('#pesquisaIdCliente').hide();
            $('#pesqIdCliente').attr('disabled', true);
            $('#pesquisaNumeroContrato').hide();
            $('#pesqNumeroContrato').attr('disabled', true);
        } else if (tipo == 1) {
            $('#pesquisa').hide();
            $('#solicitacaoId').attr('disabled', true);
            $('#pesquisaNomeCliente').show();
            $('#pesqNomeCliente').attr('disabled', false);
            $('#pesquisaCPF').hide();
            $('#pesqCPF').attr('disabled', true);
            $('#pesquisaCNPJ').hide();
            $('#pesqCNPJ').attr('disabled', true);
            $('#pesquisaEmailCliente').hide();
            $('#pesqEmailCliente').attr('disabled', true);
            $('#pesquisaIdCliente').hide();
            $('#pesqIdCliente').attr('disabled', true);
            $('#pesquisaNumeroContrato').hide();
            $('#pesqNumeroContrato').attr('disabled', true);
        } else if (tipo == 2) {
            $('#pesquisa').hide();
            $('#solicitacaoId').attr('disabled', true);
            $('#pesquisaNomeCliente').hide();
            $('#pesqNomeCliente').attr('disabled', true);
            $('#pesquisaCPF').show();
            $('#pesqCPF').attr('disabled', false);
            $('#pesquisaCNPJ').hide();
            $('#pesqCNPJ').attr('disabled', true);
            $('#pesquisaEmailCliente').hide();
            $('#pesqEmailCliente').attr('disabled', true);
            $('#pesquisaIdCliente').hide();
            $('#pesqIdCliente').attr('disabled', true);
            $('#pesquisaNumeroContrato').hide();
            $('#pesqNumeroContrato').attr('disabled', true);
        } else if (tipo == 3) {
            $('#pesquisa').hide();
            $('#solicitacaoId').attr('disabled', true);
            $('#pesquisaNomeCliente').hide();
            $('#pesqNomeCliente').attr('disabled', true);
            $('#pesquisaCPF').hide();
            $('#pesqCPF').attr('disabled', true);
            $('#pesquisaCNPJ').show();
            $('#pesqCNPJ').attr('disabled', false);
            $('#pesquisaEmailCliente').hide();
            $('#pesqEmailCliente').attr('disabled', true);
            $('#pesquisaIdCliente').hide();
            $('#pesqIdCliente').attr('disabled', true);
            $('#pesquisaNumeroContrato').hide();
            $('#pesqNumeroContrato').attr('disabled', true);
        } else if (tipo == 4) {
            $('#pesquisa').hide();
            $('#solicitacaoId').attr('disabled', true);
            $('#pesquisaNomeCliente').hide();
            $('#pesqNomeCliente').attr('disabled', true);
            $('#pesquisaCPF').hide();
            $('#pesqCPF').attr('disabled', true);
            $('#pesquisaCNPJ').hide();
            $('#pesqCNPJ').attr('disabled', true);
            $('#pesquisaEmailCliente').show();
            $('#pesqEmailCliente').attr('disabled', false);
            $('#pesquisaIdCliente').hide();
            $('#pesqIdCliente').attr('disabled', true);
            $('#pesquisaNumeroContrato').hide();
            $('#pesqNumeroContrato').attr('disabled', true);
        } else if (tipo == 5) {
            $('#pesquisa').hide();
            $('#solicitacaoId').attr('disabled', true);
            $('#pesquisaNomeCliente').hide();
            $('#pesqNomeCliente').attr('disabled', true);
            $('#pesquisaCPF').hide();
            $('#pesqCPF').attr('disabled', true);
            $('#pesquisaCNPJ').hide();
            $('#pesqCNPJ').attr('disabled', true);
            $('#pesquisaEmailCliente').hide();
            $('#pesqEmailCliente').attr('disabled', true);
            $('#pesquisaIdCliente').show();
            $('#pesqIdCliente').attr('disabled', false);
            $('#pesquisaNumeroContrato').hide();
            $('#pesqNumeroContrato').attr('disabled', true);
        } else {
            $('#pesquisa').hide();
            $('#solicitacaoId').attr('disabled', true);
            $('#pesquisaNomeCliente').hide();
            $('#pesqNomeCliente').attr('disabled', true);
            $('#pesquisaCPF').hide();
            $('#pesqCPF').attr('disabled', true);
            $('#pesquisaCNPJ').hide();
            $('#pesqCNPJ').attr('disabled', true);
            $('#pesquisaEmailCliente').hide();
            $('#pesqEmailCliente').attr('disabled', true);
            $('#pesquisaIdCliente').hide();
            $('#pesqIdCliente').attr('disabled', true);
            $('#pesquisaNumeroContrato').show();
            $('#pesqNumeroContrato').attr('disabled', false);
        }
    });

    async function preencherExpedicaoSelect(){
        let proprietario  = await $.ajax ({
                                url: '<?= site_url('SolicitacaoExpedicao/listarExpedicaoSelect2') ?>',
                                dataType: 'json',
                                delay: 1000,
                                type: 'GET',
                                data: function (params) {
                                    return {
                                        q: params.term
                                    };
                                }
                            });

        $('#solicitacaoId').empty();
                       
        $('#solicitacaoId').select2({
            data: proprietario.results,
            placeholder: "Selecione o número...",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            heigth: '100%'
            });
        
        
        
        $('#solicitacaoId').on('select2:select', function (e) {
            var data = e.params.data;
        });
        $('#solicitacaoId').val(null).trigger('change');

        $('#solicitacaoId').attr('disabled', false);
        $('#sel-pesquisa').attr('disabled', false);
    }

    $('#formPesquisa').submit(event => {
        event.preventDefault();

        var solicitacaoId = $('#solicitacaoId').val();
        var nomeCliente = $('#pesqNomeCliente').val();
        var cpf = $('#pesqCPF').val();
        var cnpj = $('#pesqCNPJ').val();
        var emailCliente = $('#pesqEmailCliente').val();
        var idCliente = $('#pesqIdCliente').val();
        var contrato = $('#pesqNumeroContrato').val();

        if (((!$("#solicitacaoId").prop("disabled")) && (solicitacaoId == null)) || ($("#solicitacaoId").prop("disabled") && $("#solicitacaoId").is(":visible"))) {
            alert("Selecione um número de Solicitação de Expedição!")
            return;
        }

        if ((!$("#pesqNomeCliente").prop("disabled")) && (nomeCliente == null)) {
            alert("Selecione um cliente!")
            return;
        }

        if ((!$("#pesqCPF").prop("disabled")) && (cpf == '')) {
            alert("Informe o cpf do cliente!")
            return;
        }

        if ((!$("#pesqCNPJ").prop("disabled")) && (cnpj == '')) {
            alert("Informe o cnpj do cliente!")
            return;
        }

        if ((!$("#pesqEmailCliente").prop("disabled")) && (emailCliente == '')) {
            alert("Informe o email do cliente!")
            return;
        }

        if ((!$("#pesqIdCliente").prop("disabled")) && (idCliente == '')) {
            alert("Informe o ID do cliente!")
            return;
        }

        if ((!$("#pesqNumeroContrato").prop("disabled")) && (contrato == '')) {
            alert("Informe o número de contrato!")
            return;
        }

        $("#pesquisaId").html('<i class="fa fa-spinner fa-spin"></i>  Pesquisando...');
        $("#pesquisaId").attr("disabled", true);

        $.ajax({
            url: '<?= site_url('SolicitacaoExpedicao/buscarSolicitacoes') ?>',
            type: 'POST',
            data: { 
                "idSolicitacao": solicitacaoId,
                "nomeCliente": nomeCliente,
                "idCliente": idCliente,
                "cpf": cpf,
                "cnpj": cnpj,
                "contrato": contrato,
                "email": emailCliente
            },
            dataType: 'json',
            beforeSend: function() {
                $('#tabelaSolicitacoes > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data){
                if (data.status === 200){
                    tabelaSolicitacoes.clear().draw();
                    tabelaSolicitacoes.rows.add(data.dados).draw();
                } else {
                    alert("Não foi possível encontrar nenhuma solicitação com esse parâmetro. Tente novamente.")
                    tabelaSolicitacoes.clear().draw();
                }
            },
            error: function(erro){
                alert('Erro ao buscar as solicitações');
                tabelaSolicitacoes.clear().draw();
            },
             complete: function() {
                $("#pesquisaId").attr("disabled", false);
                $('#pesquisaId').html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
             }
        });
    });

    $('#BtnLimparPesquisar').click(function (e){
            e.preventDefault();

            $("#BtnLimparPesquisar").html('<i class="fa fa-spinner fa-spin"></i>  Limpando...');
            $("#BtnLimparPesquisar").attr("disabled", true);

            $("#solicitacaoId").val(null).trigger('change');
            $('#pesqNomeCliente').val(null).trigger('change');
            $('#pesqCPF').val('');
            $('#pesqCNPJ').val('');
            $('#pesqEmailCliente').val('');
            $('#pesqIdCliente').val('');
            $('#pesqNumeroContrato').val('');

            $.ajax({
                url: '<?= site_url('SolicitacaoExpedicao/listarSolicitacoes') ?>',
                type: 'POST',
                dataType: 'json',
                beforeSend: function() {
                    $('#tabelaSolicitacoes > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                        '</tr>'
                    );
                },
                success: function(data){
                    if (data.status === 200){
                        tabelaSolicitacoes.clear().draw();
                        tabelaSolicitacoes.rows.add(data.dados).draw();
                    } else {
                        tabelaSolicitacoes.clear().draw();
                    }
                },
                error: function(erro){
                    alert('Erro ao listar as Solicitações');
                    tabelaSolicitacoes.clear().draw();
                },
                complete: function() {
                    $("#BtnLimparPesquisar").html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar');
                    $("#BtnLimparPesquisar").attr("disabled", false);                
                }
            });
    })

    $('#abrirModalCadastrar').click(function(){
        $('#id').val('');
        $('#modalSE').modal('show');
        $("#divNomeCliente").attr("style", "border-left: none");
        
        $('#divBuscarPor').show();
        $('#tipo-busca-expedicao').val(2).trigger('change');        
    })

    function limparModal(){
    
        $("#divDocCliente").hide()
        $('#divBuscarPor').hide()
        $('#divBtnPesquisaCliente').hide()
        $("#divNomeCliente").attr("style", "border-left: 3px solid #03A9F4");
        $("#divDocCliente").hide()
    }

    var idClienteDoc;
    
    $('#btnPesquisaClienteDoc').click(function(){
        if ($('#divDocCliente').is(':visible')){
            $('#cliente-expedicaoDoc').attr('disabled', true)
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)

            var documento = $('#cliente-expedicaoDoc').val();
            if (documento != ''){
                $.ajax({
                    url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
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
                            $('#cliente-expedicaoDoc').attr('disabled', true)
                            idClienteDoc = data.results[0].id
                            $('#cep-expedicao').val(data.results[0].cep)
                            $('#endereco-expedicao').val(data.results[0].endereco)
                            $('#uf-expedicao').val(data.results[0].uf)
                            $('#bairro-expedicao').val(data.results[0].bairro)
                            $('#cidade-expedicao').val(data.results[0].cidade)
                            $('#tipo-orgao-expedicao').val(data.results[0].orgao)
                            BuscarVeiculos();
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoDoc').val('')
                            $('#cliente-expedicaoDoc').attr('disabled', false)
                            $("#addveiculo").empty()
                            $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
                        }
                    }
                })
            }else{
                alert('Digite o cpf ou cnpj do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#cliente-expedicaoDoc').attr('disabled', false)
                $("#addveiculo").empty()
                $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
            }
        }else{
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)
            var id = $('#cliente-expedicaoId').val();
            if (id != ''){
                $.ajax({
                    url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {q: id,
                        tipoBusca: 'id'},
                    success: function(data){
                        if(data.results.length){
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoId').attr('disabled', false)
                            $('#cliente-expedicaoId').val(data.results[0].text)
                            $('#cliente-expedicaoId').attr('disabled', true)
                            idClienteDoc = data.results[0].id
                            $('#cep-expedicao').val(data.results[0].cep)
                            $('#endereco-expedicao').val(data.results[0].endereco)
                            $('#uf-expedicao').val(data.results[0].uf)
                            $('#bairro-expedicao').val(data.results[0].bairro)
                            $('#cidade-expedicao').val(data.results[0].cidade)
                            $('#tipo-orgao-expedicao').val(data.results[0].orgao)
                            BuscarVeiculos();
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoId').val('')
                            $('#cliente-expedicaoId').attr('disabled', false)
                            
                            $("#addveiculo").empty()
                            $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
                        }
                    }
                })
            }else{
                alert('Digite o id do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#cliente-expedicaoId').attr('disabled', false)
                        
                $("#addveiculo").empty()
                $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
            }
        }
    })
    
    $('#btnLimparPesquisaClienteDoc').click(function(){
        $('#cep-expedicao').val('')
        $('#endereco-expedicao').val('')
        $('#uf-expedicao').val('')
        $('#bairro-expedicao').val('')
        $('#cidade-expedicao').val('')
        $('#tipo-orgao-expedicao').val('')
        if ($('#divDocCliente').is(':visible')){
            $('#cliente-expedicaoDoc').val('')
            $('#cliente-expedicaoDoc').attr('disabled', false)
            $("#cliente-expedicaoDoc").inputmask({
                mask: ["999.999.999-99", "99.999.999/9999-99"],
                keepStatic: true,
                placeholder: " ",
                });
        }else{
            $('#cliente-expedicaoId').val('')
            $('#cliente-expedicaoId').attr('disabled', false)
        }
    })
    
    $('#tipo-busca-expedicao').change(function(){ 
        $('#cep-expedicao').val('');
        $('#endereco-expedicao').val('');
        $('#uf-expedicao').val('');
        $('#bairro-expedicao').val('');
        $('#cidade-expedicao').val('');
        $('#tipo-orgao-expedicao').val('');

        if ($(this).val() == 1){

            $('#cliente-expedicaoDoc').val('');
            $('#cliente-expedicaoDoc').attr('disabled', false);
            $('#cliente-expedicaoId').val('');
            $('#cliente-expedicaoId').attr('disabled', false);

            $('#divDocCliente').hide();
            $('#divBtnPesquisaCliente').hide();
            $('#divIdCliente').hide();
            $("#divNomeCliente").show();
            $("#cliente-expedicao").select2({
                ajax: {
                    url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
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
            $('#cliente-expedicaoId').attr('disabled', false);
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
            $('#cliente-expedicaoDoc').attr('disabled', false);
            $("#divNomeCliente").hide();
            $('#divBtnPesquisaCliente').show();
            $('#divDocCliente').hide();
            $('#divIdCliente').show();
        }
    })

    $('#cliente-expedicao').change(function(){ 
        BuscarVeiculos();
    })
    
    $('#cliente-expedicao').on('select2:unselecting ', function (e) {
        $("#addveiculo").empty()
        $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
    });

    $('#btnSalvarSE').click(event=> {

        let tipo = $('#tipo-busca-expedicao').val()
        let cliente_id = 0;
        if (tipo == 0){
            cliente_id = idClienteDoc
        } else if (tipo == 1){
            cliente_id = $('#cliente-expedicao').val()
        } else if (tipo == 2){
            cliente_id = idClienteDoc
        } 
        
        if(!cliente_id || cliente_id == 0){
            alert("Selecione um Cliente")
            return;
        }

        let tipoSolicitacao = $('input[name="tipoSolicitacao"]:checked').val()

        if(tipoSolicitacao && (listaEquipamento.length == 0 || listaVeiculo.length == 0)){
            alert("Adicione equipamentos e veiculos")
            return;
        }

        if(!tipoSolicitacao){
            alert("Selecione um Tipo de Solicitação")
            return;
        }

        if(!$('#logradouro').val() || !$('#bairro').val() || !$('#cidade').val() || !$('#estado').val() || !$('#cep').val()){
            alert("Preencha os dados de Envio")
            return;
        }
        
        

        var formSE = {
            clienteID : cliente_id,
            tipoSolicitacao : $('input[name="tipoSolicitacao"]:checked').val(),
            solicitante : $('#solicitante').val(),
            data : $('#data').val(),
            logradouro : $('#logradouro').val(),
            bairro : $('#bairro').val(),
            cidade : $('#cidade').val(),
            estado : $('#estado').val(),
            cep : $('#cep').val(),
            complemento : $('#complemento').val(),
        };

        var id = $('#id').val();
        let url = '<?= site_url('SolicitacaoExpedicao/cadastrarSolicitacao') ?>'

        if(id){
            url = '<?= site_url('SolicitacaoExpedicao/atualizarSolicitacao') ?>'
        }

        botao = $('#btnSalvarSE');
        botao.html('<i class="fa fa-spin fa-spinner"></i> <?=lang('Salvando')?>');
        botao.attr('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                form: formSE,
                equipamentos: listaEquipamento,
                veiculos: listaVeiculo,
            },
            dataType: 'json',
            success: async function(data){
                if(data.status == 200 || data.status == 201){
                    alert("Solicitação salva com sucesso")
                    $('#modalSE').modal('hide');
                    LimparModal()
                    await preencherExpedicaoSelect();
                    tabelaSolicitacoes.ajax.reload();
                }else{
                    alert("Erro ao salvar Solicitação")
                }
                botao.html('Salvar');
                botao.attr('disabled', false);
            },
            error: function(data){
                alert('Erro ao inserir solicitação. Tente novamente.')
                botao.html('Salvar');
                botao.attr('disabled', false);
            }
        })
    })

    $('#AdicionarVeiculo').click(event=> {
        let tipo = $('#tipo-busca-expedicao').val()
        let cliente_id = 0;
        if (tipo == 0){
            cliente_id = idClienteDoc
        } else if (tipo == 1){
            cliente_id = $('#cliente-expedicao').val()
        } else if (tipo == 2){
            cliente_id = idClienteDoc
        } 

        let code = $("#addveiculo").val()
        if(!code){
            alert("Selecione um veiculo!")
        }
        $.ajax({
            url: '<?= site_url('SolicitacaoExpedicao/listar_veiculos?id_cliente=') ?>'+cliente_id+'&code='+code,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.results){        
                    let obs = $("#obs").val();
                    $("#addveiculo").select2("val", " ");
                    $("#obs").val("")   

                    listaVeiculo.push({
                        veiculoID: code,
                        placa: data.results[0]['placa'],
                        marca: data.results[0]['veiculo'],
                        observacoes: obs
                    })

                    data.results[0]["observacoes"] = obs;
                    tabelaComplementar.rows.add(data.results).draw();
                }
            },
            error: function(){
                alert('Erro ao inserir solicitação. Tente novamente.')
                botao.html('Salvar');
                botao.attr('disabled', false);
            }
        })
    })

    $('#AdicionarEquipamento').click(event=> {
        let dados = [];

        if(!$("#equipamento option:selected").text()){
            alert('Selecione um Serial.')
            return;
        }

        if(!$("#operadora option:selected").text() || $("#operadora option:selected").text() == 'Selecione a operadora'){
            alert('Selecione uma Operadora.')
            return;
        }

        if(!$('#info_config').val()){
            alert('Adicione uma informação de configuração.')
            return;
        }

        if(!$('#info_estoque').val()){
            alert('Adicione uma informação de estoque.')
            return;
        }

        dados['id'] = 0;        
        dados['equipamento'] = $("#equipamento option:selected").text()
        dados['referencia'] = $('#referencia').val()
        dados['operadora'] = $("#operadora option:selected").text()
        dados['info_config'] = $('#info_config').val()
        dados['info_estoque'] = $('#info_estoque').val()
        dados['sirene'] = $('#sirene').val()
        dados['sireneTxt'] = $('#sirene').val() ? $("#sirene option:selected").text() : ""
        dados['sirene-qtd'] = $('#sirene-qtd').val()
        dados['leitor'] = $('#leitor').val()
        dados['leitorTxt'] = $('#leitor').val() ? $("#leitor option:selected").text(): ""
        dados['leitor-qtd'] = $('#leitor-qtd').val()
        dados['ibutton'] = $('#ibutton').val()
        dados['ibuttonTxt'] = $('#ibutton').val() ? $("#ibutton option:selected").text(): ""
        dados['ibutton-qtd'] = $('#ibutton-qtd').val()
        dados['rele_12v'] = $('#rele_12v').val()
        dados['rele_12vTxt'] =$('#rele_12v').val() ? $("#rele_12v option:selected").text(): ""
        dados['rele_12v-qtd'] = $('#rele_12v-qtd').val()
        dados['rele_24v'] = $('#rele_24v').val()
        dados['rele_24vTxt'] = $('#rele_24v').val() ? $("#rele_24v option:selected").text(): ""
        dados['rele_24v-qtd'] = $('#rele_24v-qtd').val()
        dados['panico'] = $('#panico').val()
        dados['panicoTxt'] = $('#panico').val() ? $("#panico option:selected").text(): ""
        dados['panico-qtd'] = $('#panico-qtd').val()
        dados['outros'] = $('#outros').val()
        dados['outrosTxt'] = $('#outros').val() ? $("#outros option:selected").text(): ""
        dados['outros-qtd'] = $('#outros-qtd').val()   

        let equip = {
            equipamento : dados['equipamento'],
            referencia : dados['referencia'],
            operadora : dados['operadora'],
            info_config : dados['info_config'],
            info_estoque : dados['info_estoque'],
            sirene : dados['sirene'],
            sireneTxt : dados['sireneTxt'],
            sireneQtd : dados['sirene-qtd'],
            leitor : dados['leitor'],
            leitorTxt : dados['leitorTxt'],
            leitorQtd : dados['leitor-qtd'],
            ibutton : dados['ibutton'],
            ibuttonTxt : dados['ibuttonTxt'],
            ibuttonQtd : dados['ibutton-qtd'],
            rele_12v : dados['rele_12v'],
            rele_12vTxt : dados['rele_12vTxt'],
            rele_12vQtd : dados['rele_12v-qtd'],
            rele_24v : dados['rele_24v'],
            rele_24vTxt : dados['rele_24vTxt'],
            rele_24vQtd : dados['rele_24v-qtd'],
            panico : dados['panico'],
            panicoTxt : dados['panicoTxt'],
            panicoQtd : dados['panico-qtd'],
            outros : dados['outros'],
            outrosTxt : dados['outrosTxt'],
            outrosQtd : dados['outros-qtd'],
        }

        let itensEquipamentos = [];
        if(dados['sirene-qtd'] > 0){
            itensEquipamentos.push({
                idInsumo: dados['sirene'],
                qtd: dados['sirene-qtd']
            })
        }
        if(dados['leitor-qtd'] > 0){
            itensEquipamentos.push({
                idInsumo: dados['leitor'],
                qtd: dados['leitor-qtd']
            })
        }
        if(dados['ibutton-qtd'] > 0){
            itensEquipamentos.push({
                idInsumo: dados['ibutton'],
                qtd: dados['ibutton-qtd']
            })
        }
        if(dados['rele_12v-qtd'] > 0){
            itensEquipamentos.push({
                idInsumo: dados['rele_12v'],
                qtd: dados['rele_12v-qtd']
            })
        }
        if(dados['rele_24v-qtd'] > 0){
            itensEquipamentos.push({
                idInsumo: dados['rele_24v'],
                qtd: dados['rele_24v-qtd']
            })
        }
        if(dados['panico-qtd'] > 0){
            itensEquipamentos.push({
                idInsumo: dados['panico'],
                qtd: dados['panico-qtd']
            })
        }
        if(dados['outros-qtd'] > 0){
            itensEquipamentos.push({
                idInsumo: dados['outros'],
                qtd: dados['outros-qtd']
            })
        }

        let equipamento = {
            equipamento : dados['equipamento'],
            referencia : dados['referencia'],
            operadora : dados['operadora'],
            infoConfig : dados['info_config'],
            infoEstoque : dados['info_estoque'],
            itensEquipamentos: itensEquipamentos
        }
        listaEquipamento.push(equipamento)

        LimparDadosEquipamento()
        tabelaEquipamento.row.add(dados).draw();
    })

    function LimparDadosEquipamento(){
       $("#equipamento").select2("val", " ");
       $('#referencia').val('') 
       $("#operadora").select2("val", " ");
       $('#info_config').val('') 
       $('#info_estoque').val('') 
       $('#sirene').val('0') 
       $('#sireneTxt').val('')
       $('#sirene-qtd').val('')
       $('#leitor').val('0') 
       $('#leitorTxt').val('')
       $('#leitor-qtd').val('')
       $('#ibutton').val('0') 
       $('#ibuttonTxt').val('')
       $('#ibutton-qtd').val('')
       $('#rele_12v').val('0') 
       $('#rele_12vTxt').val('')
       $('#rele_12v-qtd').val('')
       $('#rele_24v').val('0') 
       $('#rele_24vTxt').val('')
       $('#rele_24v-qtd').val('')
       $('#panico').val('0') 
       $('#panicoTxt').val('')
       $('#panico-qtd').val('')
       $('#outros').val('0') 
       $('#outrosTxt').val('')
       $('#outros-qtd').val('')  
    }

    $(document).ready(async function(){
        $("#cep").mask("00000-000", {reverse: true});

        $("#cep").change(function () {
            var cep = this.value.replace(/[^0-9]/, "");

            var url = "https://viacep.com.br/ws/"+cep+"/json/";

            $.getJSON(url, function(dadosRetorno){
                try{
                    $("#logradouro").val(dadosRetorno.logradouro);
                    $("#bairro").val(dadosRetorno.bairro);
                    $("#cidade").val(dadosRetorno.localidade);
                    $("#estado").val(dadosRetorno.uf);
                    $("#complemento").val('')
                }catch(ex){}
            });
        })
        
        $('#equipamento').select2({
            ajax: {
                url: '<?= site_url('MovimentosEstoque/buscarSeriais') ?>',
                dataType: 'json',
                delay: 1000,
                type: 'GET',
                data: function (params) {
                    return {
                        q: params.term
                    };
                },

            },
            placeholder: 'Selecione o serial',
            allowClear: true,
            language: "pt-BR",
            minimumInputLength: 3,
            width: '100%',
        });
        
        $('#equipamento').on('select2:select', function (e) {
            var data = e.params.data;
            var marca = data.marca;
            var modelo = data.modelo;
            $('#referencia').val(marca + '-' + modelo);
            $('#referencia').attr('readonly', true);
        });
        
        $('#equipamento').on('select2:clear', function (e) {
            $('#referencia').val('');
            $('#referencia').attr('readonly', false);
        });

        $("#addveiculo").select2({
            placeholder: "Digite o veiculo ou Placa",
            allowClear: true,
            language: "pt-BR",
            width: 'resolve',
        })

        $("#ibutton").append($('<option>', { value : 0,  text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
        $("#leitor").append($('<option>', { value : 0, text : 'Selecione uma Opção'  }).prop('disabled', true).prop('selected', true))
        $("#outros").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
        $("#panico").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
        $("#rele_12v").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
        $("#rele_24v").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
        $("#sirene").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))

        $.ajax({
            url: '<?= site_url('SolicitacaoExpedicao/listarInsumos') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data['status'] && data['results']){
                    
                    data['results']['IButton'].forEach( opcao =>{
                        $("#ibutton").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['nome']
                        }))
                    });
                    
                    data['results']['Leitor'].forEach( opcao =>{
                        $("#leitor").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['nome']
                        }))
                    });
                    
                    data['results']['Outros'].forEach( opcao =>{
                        $("#outros").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['nome']
                        }))
                    });
                    
                    data['results']['Pânico'].forEach( opcao =>{
                        $("#panico").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['nome']
                        }))
                    });
                    
                    data['results']['Rele 12V'].forEach( opcao =>{
                        $("#rele_12v").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['nome']
                        }))
                    });
                    
                    data['results']['Rele 24V'].forEach( opcao =>{
                        $("#rele_24v").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['nome']
                        }))
                    });
                    
                    data['results']['Sirene'].forEach( opcao =>{
                        $("#sirene").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['nome']
                        }))
                    });
                }
            },
            error: function(data){
                alert("Erro ao buscar Insumos")
            }
        })

    });
    
    $(document).ready(async function(){
        let operadoras = await $.ajax ({
            url: '<?= site_url('GestaoDeChips/linhas/listarOperadoras') ?>',
            dataType: 'json',
            type: 'GET',  
            success: function(data){
                return data;
            }           
        })

        $('#operadora').select2({
            data: operadoras,
            placeholder: "Selecione a operadora",
            allowClear: true,
            language: "pt-BR",
        });

        $('#operadora').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#operadora').find('option').get(0).remove();
        $('#operadora').prepend('<option value="0" selected="selected" disabled>Selecione a operadora</option>');
        $('#operadora').attr('disabled', false);
    });

    function BuscarVeiculos(){
        let tipo = $('#tipo-busca-expedicao').val()
        let cliente_id = 0;
        if (tipo == 0){
            cliente_id = idClienteDoc
        } else if (tipo == 1){
            cliente_id = $('#cliente-expedicao').val()
        } else if (tipo == 2){
            cliente_id = idClienteDoc
        } 
        if(cliente_id == 0){
            alert("Selecione um cliente");
        }

        $("#addveiculo").empty()
        $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))

        $.ajax({
            url: '<?= site_url('SolicitacaoExpedicao/listar_veiculos') ?>'+'?id_cliente='+cliente_id,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data['results']){
                    data['results'].forEach( opcao =>{
                        $("#addveiculo").append($('<option>', {
                            value : opcao['id'],
                            text : opcao['text']
                        }))
                    });
                }
            },
            error: function(data){
                alert("Erro ao buscar Insumos")
            }
        })
    }

    function LimparModal(){
        var inputs = $('input[name="tipoSolicitacao"]');
        inputs.attr('checked', false);
        inputs.prop('checked', false);

        $('#logradouro').val('')
        $('#bairro').val('')
        $('#cidade').val('')
        $('#estado').val('')
        $('#cep').val('')
        $('#complemento').val('')
        $('#tipo-busca-expedicao').val('2')  
        $('#cliente-expedicao').val('')  
        $('#cliente-expedicaoDoc').val('')
        $('#cliente-expedicaoId').val('');
        listaEquipamento = []
        listaVeiculo = []
            
        tabelaEquipamento.clear().draw();
        tabelaComplementar.clear().draw();
        
        $("#addveiculo").select2("val", " ");
        $("#addveiculo").empty()
        $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
    }
  
</script>   

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
