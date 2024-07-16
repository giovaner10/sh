<style>
    html {
        scroll-behavior: smooth
    }

    body {
        background-color: #fff !important;
    }

    table {
        width: 100% !important;
    }

    .blem {
        color: red;
    }

    .container-fluid {
        padding: 0;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
    }

    .bord {
        border-left: 3px solid #03A9F4;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th,
    td.wordWrap {
        max-width: 150px;
        word-wrap: break-word;
        text-align: center;
    }

    .checkbox label {
        font-weight: 700;
    }

    .select-container .select-selection--single {
        height: 35px !important;
    }

    #pesquisa > .select2-selection__rendered {
        line-height: 34px !important;
    }

    #pesquisa > .select2-container .select2-selection--single {
        height: 34px !important;
        line-height: 34px !important;
    }

    #pesquisa > .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 34px !important;
    }

    #pesquisa > .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px !important;
    }

    #pesquisa > .select2-selection__clear {
        height: 34px !important;
    }

    .select2-selection__choice {
        background-color: #f2f2f2 !important; 
        color: #333 !important; 
        border-radius: 20px !important; 
    }

    .select2-selection__choice__remove {
        border: none !important;
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

    .select-selection--multiple .select-search__field {
        width: 100% !important;
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

    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 56px;
        height: 30px;
        margin-bottom: 0;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .switch .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .switch .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    .switch input:checked + .slider {
        background-color: #2196F3;
    }

    .switch input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    .switch input:checked + .slider:before {
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .switch .slider.round {
        border-radius: 34px;
    }

    .switch .slider.round:before {
        border-radius: 50%;
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

<h3><?=lang('campanhas')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('comissionamento_vendas')?>
</div>

<hr>

<div class="container-fluid my-1">
    <div class="col-sm-12">
        <form id="formPesquisa">
            <div class="alert alert-info col-md-12" style="margin-bottom: 50px;">
                <div class="col-md-12">
                    <p class="col-md-12">Informe o dados da pesquisa</p>
                    <span class="help help-block"></span>
                    <form action="" id="formBusca">
                        <div class="form-group col-md-7">
                            <div id="pesquisa" class="col-md-4">
                                <label>Empresa: <span class="text-danger">*</span></label>
                                <select class="form-control" id="pesqnome" name="nome" type="text" style="width: 100%" required></select>
                            </div>
                            <div class="col-md-4 data-pesquisa">
                                <label for="dataInicial">Data Inicial:</label>
                                <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value="">
                            </div>

                            <div class="col-md-4 data-pesquisa">
                                <label for="dataFinal">Data Final:</label>
                                <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value="">
                            </div>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="form-group col-md-4">
                            <div class="col-md-12">
                                <button class="btn btn-primary col-md-5" id="pesquisacampanhaEmpresa" type="button" style="margin-top: 20px"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                                <div class="col-md-1">
                                </div>
                                <button class="btn btn-primary col-md-5" id="BtnLimparPesquisar" type="button" style="margin-top: 20px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-12" style="margin-left: 1.5rem">
                        <a id="abrirModalInserir" class="btn btn-primary">Nova Campanha</a>
                    </div>
                </div>
            </div>
        </form>
        <div id="campanhas" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                <table class="table table-striped table-bordered table-hover responsive nowrap" id="tabelaCampanha">
                    <thead>
                        <tr class="tableheader">
                            <th>Id</th>
                            <th>Empresa</th>
                            <th>Campanha</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Data de Cadastro</th>
                            <th>Data de Atualização</th>
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

<!-- Modal Cadastro/Edição de Campanha -->
<div id="modalCadCampanha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadCampanha" id="formCadastroCampanha">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="nomeHeaderModal"></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" style="margin-bottom: 10px;">
                            <li class="nav-item">
                                <a id="tab-dadosGerais" href="" data-toggle="tab" class="nav-link active">Dados Gerais</a>
                            </li>
                            <li class="nav-item">
                                <a id="tab-itensDaCampanha" href="" data-toggle="tab" class="nav-link">Itens da Campanha</a>
                            </li>
                            <li class="nav-item">
                                <a id="tab-cenariosDaCampanha" href="" data-toggle="tab" class="nav-link">Cenários da Campanha</a>
                            </li>
                        </ul>
                        <div id="dadosGerais" style="display: block;">
                            <div id="div_identificacao">
                                <div class="row">
                                    <div class="col-md-6 form-group bord">
                                        <label class="control-label">Nome</label>
                                        <input type="text" class="form-control input-sm" name="nome" id="nomeCampanha" placeholder="Digite o nome da campanha" required>
                                    </div>
                                    <div id="divSelectEmpresaCad" class="col-md-6 form-group bord">
                                        <label class="control-label">Empresa: </label>
                                        <select class="form-control input-sm" id="selectEmpresaCad" name="selectEmpresaCad" type="text" required></select>
                                    </div>
                                    <div id="divSelectEmpresaEdit" class="col-md-6 form-group bord">
                                        <label class="control-label">Empresa: </label>
                                        <select class="form-control input-sm" id="selectEmpresaEdit" name="selectEmpresaEdit" type="text" required></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group bord">
                                        <label class="control-label">Data Início:</label>
                                        <input type="date" class="form-control input-sm" name="dataInicio" id="dataInicio" placeholder="Digite a data de início da campanha" required>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label class="control-label">Data Fim:</label>
                                        <input type="date" class="form-control input-sm" name="dataFim" id="dataFim" placeholder="Digite a data de fim da campanha" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group bord">
                                        <label class="control-label" for="temCampanhaItem">Campanha com Itens:</label>
                                        <br>
                                        <label class="switch">
                                            <input type="checkbox" name="temCampanhaItem" id="temCampanhaItem" required>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label class="control-label" for="temCampanhaCenario">Campanha com Cenários</label>
                                        <br>
                                        <label class="switch">
                                          <input type="checkbox" id="temCampanhaCenario">
                                          <div class="slider round"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group bord" id="divStatus" hidden>
                                        <label class="control-label">Status</label>
                                        <select class="form-control input-sm" id="statusCampanha" style="display: none;" required>
                                            <option value="1">Ativo</option>
                                            <option value="0">Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="itensDaCampanha" style="display: none !important;">
							<div class="form-group">
								<div class="row">
									<div class="col-md-6 form-group bord">
										<label for="valorMeta" class="control-label">Valor Meta:</label>    
										<input class="form-control input-sm" id="valorMeta" name="valorMeta" type="text" style="width: 100%" placeholder="Digite o valor em R$">
    								</div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="percentualComissao" class="control-label">Percentual Comissão:</label>
                                        <input class="form-control input-sm" id="percentualComissao" name="percentualComissao" type="text" style="width: 100%" placeholder="Digite o percentual da comissão">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group bord">
                                        <label for="aplicaClienteBase" class="control-label">Aplica Cliente Base</label>
                                        <select class="form-control input-sm" id="aplicaClienteBase" name="aplicaClienteBase" type="text" style="width: 100%">
                                            <option value="1">Sim</option>
                                            <option value="0">Não</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <a class="btn btn-primary" id="adicionarItemTabela" style="margin-top: 22px;">Adicionar</a>
                                    </div>
                                </div>
                            </div>
                            <table class="table-responsive table-bordered table" id="tabelaItensCadCampanha" style="width: 100%;">
                                <thead>
                                    <tr class="tableheader">
                                        <th>Valor Meta</th>
                                        <th>Percentual Comissão</th>
                                        <th>Aplica Cliente Base</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="cenariosDaCampanha" style="display: none !important;">
							<div class="form-group">
								<div class="row">
                                    <div class="col-md-6 form-group bord">
										<label for="cenarioCampanha" class="control-label">Cenário:</label>
										<select class="form-control input-sm" id="cenarioCampanha" name="cenarioCampanha" type="text" style="width: 100%" placeholder="Selecione o Cenário">
                                        </select>
    								</div>
									<div class="col-md-6 form-group bord">
										<label for="valorFixo" class="control-label">Valor Fixo:</label>    
										<input class="form-control input-sm" id="valorFixo" name="valorFixo" type="text" style="width: 100%" placeholder="Digite o valor em R$">
    								</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <a class="btn btn-primary" id="adicionarCenarioTabela" style="float: right;">Adicionar</a>
                                    </div>
                                </div>
                            </div>
                            <table class="table-responsive table-bordered table" id="tabelaCenariosCadCampanha" style="width: 100%;">
                                <thead>
                                    <tr class="tableheader">
                                        <th>ID</th>
                                        <th>Cenário</th>
                                        <th>Valor Fixo</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-id='' type="submit" id="btnSalvarCadastroCampanha">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalItensDaCampanha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formItensDaCampanha">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?= lang("itens_campanha") ?></h3>
                </div>
                <div class="modal-body" style="overflow-x: scroll; max-width: 100%;">
                    <table class="table-responsive table-bordered table" id="tabelaItensDaCampanha">
                        <thead>
                            <tr class="tableheader">
                                <th>Valor Meta</th>
                                <th>Percentual Comissão</th>
                                <th>Aplica Cliente Base</th>
                                <th>Data de Cadastro</th>
                                <th>Data de Atualização</th>
                                <th>Status</th>
                                <th id="acoes">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalCenariosDaCampanha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Cenários da Campanha</h3>
            </div>
            <div class="modal-body" style="overflow-x: auto; max-width: 100%;">
                <table class="table-responsive table-bordered table" id="tabelaCenariosDaCampanha">
                    <thead>
                        <tr class="tableheader">
                            <th>Cenário</th>
                            <th>Valor Fixo</th>
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

<div id="modalAddItemCampanha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            	<form id="AddItemCampanha">
                	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
						<h3 class="modal-title" id="nome-modal-header"></h3>
                	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	</div>
                	<div class="modal-body scrollModal">
						<div class="col-md-12">
                            <div class="row" style="margin-bottom: 6px;">
                                <div class="col-md-6 form-group bord">
                                    <label>Valor Meta:</label>
                                    <input class="form-control" name="referencia" id="addItemValorMeta" type="text" placeholder="Digite o valor meta" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label>Percentual Comissão:</label>
                                    <input class="form-control" name="terminal" id="addItemPercentualComissao" type="text" placeholder="Digite o percentual da comissão" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label>Aplica Cliente Base:</label>
                                    <select class="form-control" name="cliente-base" id="addItemAplicaClienteBase" required>
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                                    </select>
                                </div>
                            </div>
						</div>
					</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <button class="btn btn-primary" data-id='' data-idItem='' data-status='' type="submit" id="btnSalvarAddItemCampanha" style="margin-right: 25px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalAddCenarioCampanha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            	<form id="AddCenarioCampanha">
                	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 class="modal-title" id="nome-modal-cenario-header"></h3>
                	</div>
                	<div class="modal-body scrollModal">
						<div class="col-md-12">
                            <div class="row" style="margin-bottom: 6px;">
                                <div class="col-md-6 form-group bord">
                                    <label for="cenarioCampanha" class="control-label">Cenário:</label>
                                    <select class="form-control" id="addCenario" name="cenarioCampanha" type="text" style="width: 100%" placeholder="Selecione o Cenário">
                                    </select>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label>Valor Fixo:</label>
                                    <input class="form-control input-sm" name="valorFixo" id="addCenarioValorFixo" type="text" placeholder="Digite o valor meta" required>
                                </div>
                            </div>
						</div>
					</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <button class="btn btn-primary" data-id='' data-idItem='' data-status='' type="submit" id="btnSalvarAddCenarioCampanha" style="margin-right: 25px;">Salvar</button>
                </div>
            </form>
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
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
<script>
    $(document).ready(function() {
        $('#valorMeta').mask("#.##0,00", {reverse: true});
        $('#valorFixo').mask("#.##0,00", {reverse: true});
        $('#addItemValorMeta').mask("#.##0,00", {reverse: true});
        $('#addCenarioValorFixo').mask("#.##0,00", {reverse: true});
    });

    $.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss' );
    $.fn.dataTable.moment('DD/MM/YYYY');

    var tabelaCampanha = $('#tabelaCampanha').DataTable({
            responsive: true,
            ordering: true,
            paging: true,            
            search: {
                smart: false
            },
            info: true,
            order: [6, 'desc'],
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
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCampanhas') ?>',
                type: 'GET',
                dataType: 'json',
                data: {idEmpresa: ''},
                success: function(data){
                    if (data.status === 200){
                        tabelaCampanha.clear().draw();
                        tabelaCampanha.rows.add(data.results).draw();
                    } else if (data.status == 404) { 
                        alert('Não foram encontradas campanhas!');
                        tabelaCampanha.clear().draw();
                    } else {
                        alert('Erro ao listar campanhas!');
                        tabelaCampanha.clear().draw();
                    }
                },
                error: function(){
                    alert('Erro ao buscar campanhas');
                    tabelaCampanha.clear().draw();
                }
            },
            columns: [
                { data: 'idCampanha',
                    visible: false},
                {data: 'razaoSocial'},
                { data: 'nomeCampanha'},
                { data: 'dataInicio',
                    render: function (data) {
                        const dataObj = new Date(data);
                        
                        return dataObj.toLocaleDateString();
                    }
                },
                { data: 'dataFim',
                    render: function (data) {
                        const dataObj = new Date(data);
                        
                        return dataObj.toLocaleDateString();
                    }
                },
                { data: 'dataCadastro',
                    render: function (data) {
                        const dataObj = new Date(data);
                        
                        return dataObj.toLocaleDateString();
                    }
                },
                { data: 'dataUpdate',
                    render: function (data) {
                        const dataObj = new Date(data);
                        
                        return dataObj.toLocaleDateString();
                    }
                },
                {
				data:{'idCampanha':'idCampanha', 'idEmpresa': 'idEmpresa','status': 'status', 'nomeCampanha': 'nomeCampanha', 'dataInicio': 'dataInicio', 'dataFim': 'dataFim', 'razaoSocial': 'razaoSocial'},
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn btn-primary"
						title="Editar Campanha"
						id="btnEditarCampanha"
                        onClick="javascript:editCampanha(this,'${data['idCampanha']}','${data['idEmpresa']}','${data['status']}','${data['nomeCampanha']}', '${data['dataInicio']}', '${data['dataFim']}', '${data['temCampanhaCenario']}')">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
                    <button
                        id="btnVisualizarItensDaCampanha"
                        class="btn btn-primary"
                        title="${data['temCampanhaCenario'] == '1' ? 'Visualizar Cenários da Campanha' : 'Visualizar Itens da Campanha'}"
                        onClick="javascript:visualizarItensCampanha(this,'${data['idCampanha']}', '${data['temCampanhaCenario']}', event)">
                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                    </button>
                    <button
                        id="btnAddItemCampanha"
                        class="btn btn-primary"
                        title="${data['temCampanhaCenario'] == '1' ? 'Adicionar Cenário à Campanha' : 'Adicionar Item à Campanha' }"
                        onClick="javascript:abrirAddItemCampanha(this,'${data['idCampanha']}','${data['nomeCampanha']}','${data['razaoSocial']}', '${data['temCampanhaCenario']}', event)">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    `;
                }
            }

        ]
    });

    let tabelaItensCadCampanha = $('#tabelaItensCadCampanha').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhum item adicionado",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords: "Nenhum resultado encontrado.",
            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próxima",
                previous: "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
        columns: [{
                data: "valorMeta",
                render: function(data) {
                    return parseFloat(data).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            },
            {
                data: 'percentualComissao',
                render: function(data) {
                    return data + '%';
                }
            },
            {
                data: 'aplicaClienteBase'
            },
            {
                data: {
                    'valorMeta': 'valorMeta'
                },
                orderable: false,
                render: function(data) {
                    return `
					<button 
                        id="btnExcluirItemTabela"
						class="btn fa fa-trash"
						title="Excluir Item"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirItemTabela(this, '${data['valorMeta']}' ,event)">
					`;
                }
            }
        ]
    })

    let tabelaCenariosCadCampanha = $('#tabelaCenariosCadCampanha').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhum Cenário adicionado",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords: "Nenhum resultado encontrado.",
            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próxima",
                previous: "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
        columns: [
            {
                data: 'id',
                visible: false,
            },
            {
                data: 'cenarioCampanha'
            },
            {
                data: "valorFixo",
                render: function(data) {
                    return parseFloat(data).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            },
            {
                orderable: false,
                render: function(data) {
                    return `
					<button
						class="btn fa fa-trash"
						title="Excluir Cenário"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirCenarioTabela(this)"
                    >
					`;
                }
            }
        ]
    })

    let tabelaItensDaCampanha = $('#tabelaItensDaCampanha').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhum item adicionado",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords: "Nenhum resultado encontrado.",
            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próxima",
                previous: "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
        columns: [{
                data: "valorMeta",
                render: function(data) {
                    return parseFloat(data).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            },
            {
                data: 'percentualComissao',
                render: function(data) {
                    return data + '%';
                }
            },
            {
                data: 'aplicaClienteBase',
                render: function(data) {
                    return data == 1 ? 'Sim' : 'Não';
                }
            },
            {
                data: 'dataCadastro',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: 'dataUpdate',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: 'status'
            },
            {
                data: {
                    'idCampanha': 'idCampanha',
                    'idItem': 'idItem',
                    'valorMeta': 'valorMeta',
                    'percentualComissao': 'percentualComissao',
                    'aplicaClienteBase': 'aplicaClienteBase',
                    'status': 'status'
                },
                orderable: false,
                render: function(data) {
                    return `
					<button 
						class="btn btn-primary"
						title="Editar Item Campanha"
						id="btnEditarItemCampanha"
                        onClick="javascript:abrirEditarItemCampanha(this,'${data['idCampanha']}','${data['idItem']}','${data['valorMeta']}','${data['percentualComissao']}','${data['aplicaClienteBase']}','${data['status']}', event)">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
                    <button
                        id="btnAlterarStatusItem"
                        class="btn fa fa-exchange"
                        title="${data['status'] === 'Ativo' ? 'Inativar Item' : 'Ativar Item'}"
                        style="width: 42px; height: 35px;margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        onClick="javascript:alterarStatusItem(this,'${data['idCampanha']}','${data['status']}','${data['idItem']}', event)">
                    </button>
                    `;
                }
            }

        ]
    });


    let tabelaCenariosDaCampanha = $('#tabelaCenariosDaCampanha').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhum item adicionado",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords: "Nenhum resultado encontrado.",
            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próxima",
                previous: "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
        columns: [
            {
                data: 'cenarioNome',
            },
            {
                data: "valorFixo",
                render: function(data) {
                    return parseFloat(data).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            },
            {
                data: 'dataCadastro',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: 'dataUpdate',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: 'status'
            },
            {
                data: {
                    'idCampanha': 'idCampanha',
                    'id': 'id',
                    'idCenarioVenda': 'idCenarioVenda',
                    'valorFixo': 'valorFixo',
                    'status': 'status'
                },
                orderable: false,
                render: function(data) {
                    return `
					<button 
						class="btn btn-primary"
						title="Editar Cenário Campanha"
                        onClick="javascript:abrirEditarCenarioCampanha(this,'${data['idCampanha']}','${data['id']}', '${data['idCenarioVenda']}', '${data['valorFixo']}','${data['status']}', event)">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
                    <button
                        class="btn fa fa-exchange"
                        title="${data['status'] === 'Ativo' ? 'Inativar Cenário' : 'Ativar Cenário'}"
                        style="width: 42px; height: 35px;margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        onClick="javascript:alterarStatusCenario(this,'${data['id']}', '${data['idCampanha']}','${data['status']}', event)">
                    </button>
                    `;
                }
            }

        ]
    });


    async function buscarCenarios() {
        let cenarios  = await $.ajax ({
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
            }
        });

        return cenarios;
    }

    let openAbas = true;

    $('#abrirModalInserir').click(async function() {
        $('#loading').show();
        $('#nomeHeaderModal').html('<?= lang("nova_campanha") ?>');
        $('#statusCampanha').attr('required', false);
        $('#divSelectEmpresaCad').attr('hidden', false);
        $('#selectEmpresaCad').attr('required', true);
        $('#divSelectEmpresaEdit').attr('hidden', true);
        $('#selectEmpresaEdit').attr('required', false);
        $('#tab-dadosGerais').click();
        $('#aplicaClienteBase').val(1);
        $('#tab-itensDaCampanha').show();
        $('#tab-cenariosDaCampanha').hide();
        $('#temCampanhaItem').prop('checked', true);
        $('#temCampanhaCenario').prop('checked', false);
        $('#valorFixo').val('');
        $('#cenarioCampanha').val('').trigger('change');
        openAbas = true;

        let cenario = await buscarCenarios();

        $('#cenarioCampanha').select2({
            data: cenario.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#cenarioCampanha').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#cenarioCampanha').val('').trigger('change');

        $('#loading').hide();
        $('#modalCadCampanha').modal('show');
    });

    $('#temCampanhaItem').on('change', function (e) {
        $('#temCampanhaCenario').trigger('click');
        if ($(this).is(':checked')) {
            if (openAbas) {
                $('#tab-itensDaCampanha').show();
            }
        } else {
            if (openAbas) {
                $('#tab-itensDaCampanha').hide();
            }
        }
    });

    $('#temCampanhaCenario').on('change', function (e) {
        $('#temCampanhaItem').trigger('click');
        if ($(this).is(':checked')) {
            if (openAbas) {
                $('#tab-cenariosDaCampanha').show();
            }
        } else {
            if (openAbas) {
                $('#tab-cenariosDaCampanha').hide();
            }
        }
    });


    $('#tab-dadosGerais').click(function(e) {
        $('#dadosGerais').show();
        $('#itensDaCampanha').hide();
        $('#cenariosDaCampanha').hide();
    });

    $('#tab-itensDaCampanha').click(function(e) {
        $('#itensDaCampanha').show();
        $('#dadosGerais').hide();
        $('#cenariosDaCampanha').hide();
    });

    $('#tab-cenariosDaCampanha').click(function(e) {
        $('#cenariosDaCampanha').show();
        $('#dadosGerais').hide();
        $('#itensDaCampanha').hide();
    });

    async function editCampanha(botao, id, idEmpresa, status, nome, dataInicio, dataFim, temCampanhaCenario) {
        btn = $(botao);
        $('#divSelectEmpresaEdit').attr('hidden', false);
        $('#selectEmpresaEdit').attr('required', true);
        $('#divSelectEmpresaCad').attr('hidden', true);
        $('#selectEmpresaCad').attr('required', false);

        btn.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
        dataInicio = dataInicio.split(' ')[0];
        dataFim = dataFim.split(' ')[0];
        dataInicio = dataInicio.split('/').reverse().join('-');
        dataFim = dataFim.split('/').reverse().join('-');

        if (status == 'Ativo') {
            status = 1;
        } else {
            status = 0;
        }
        
        if (temCampanhaCenario == '1') {
            $('#temCampanhaItem').prop('checked', false);
            $('#temCampanhaCenario').prop('checked', true);
        } else {
            $('#temCampanhaItem').prop('checked', true);
            $('#temCampanhaCenario').prop('checked', false);
        }
        
        let empresasEdit = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar empresas, tente novamente');
                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
            }
        });

        $('#selectEmpresaEdit').select2({
            data: empresasEdit.results,
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
            width: '100%'
        });

        $('#selectEmpresaEdit').on('select2:select', function(e) {
            var data = e.params.data;
        });

        $('#tab-itensDaCampanha').hide();
        $('#tab-cenariosDaCampanha').hide();
        $('#tab-dadosGerais').click();
        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        openAbas = false;
        $('#modalCadCampanha').modal('show');
        $('#divStatus').attr('hidden', false);
        $('#statusCampanha').css('display', 'block');
        $('#statusCampanha').attr('required', true);
        $('#nomeCampanha').val(nome);
        $('#selectEmpresaEdit').val(idEmpresa).trigger('change');
        let selectEmpresa = $('#selectEmpresaEdit option:selected').text();
        $('#nomeHeaderModal').html('<?= lang("editar_campanha") ?>' + ' - ' + selectEmpresa + ' - ' + nome);
        $('#statusCampanha').val(status);
        $('#dataInicio').val(dataInicio);
        $('#dataFim').val(dataFim);
        $('#btnSalvarCadastroCampanha').data('id', id);
    }

    $('#modalCadCampanha').on('hidden.bs.modal', function() {
        $('#nomeCampanha').val('');
        $('#selectEmpresaCad').val('').trigger('change');
        $('#dataInicio').val('');
        $('#dataFim').val('');
        $('#statusCampanha').val('');
        $('#statusCampanha').css('display', 'none');
        $('#divStatus').attr('hidden', true);
        $('#valorMeta').val('');
        $('#percentualComissao').val('');
        $('#aplicaClienteBase').val(1);
        $('#temCampanhaItem').prop('checked', true);
        $('#temCampanhaCenario').prop('checked', false);
        $('#valorFixo').val('');
        $('#cenarioCampanha').val('').trigger('change');
        tabelaItensCadCampanha.clear().draw();
        tabelaCenariosCadCampanha.clear().draw();
    });

    $('#btnSalvarCadastroCampanha').click(function(e) {
        e.preventDefault();

        botao = $(this);
        botao.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');

        var nomeCampanha = $('#nomeCampanha').val();
        var idEmpresaCad = $('#selectEmpresaCad').val();
        var idEmpresaEdit = $('#selectEmpresaEdit').val();
        var dataInicioEn = $('#dataInicio').val() + ' 00:00:00';
        var dataFimEn = $('#dataFim').val() + ' 23:59:59';
        var dataInicio = ($('#dataInicio').val()).split('-').reverse().join('/') + ' 00:00:00';
        var dataFim = ($('#dataFim').val()).split('-').reverse().join('/') + ' 23:59:59';
        var temCampanhaItem = $('#temCampanhaItem').is(':checked') ? 1 : 0;
        var temCampanhaCenario = $('#temCampanhaCenario').is(':checked') ? 1 : 0;

        if (temCampanhaItem && temCampanhaCenario) {
            alert('Você não pode escolher campanha com Cenário e Itens simutaneamente! Tente novamente escolhendo apenas um dos dois.')
        }

        var status = $('#statusCampanha').val();
        var idCampanha = $('#btnSalvarCadastroCampanha').data('id');
        var dadosTabelaInserir = tabelaItensCadCampanha.rows().data().toArray().map(function(item) {
            return {
                valorMeta: item.valorMeta,
                percentualComissao: item.percentualComissao,
                aplicaClienteBase: item.aplicaClienteBase == 'Sim' ? 1 : 0
            }
        });

        var dadosCenariosTabelaInserir = tabelaCenariosCadCampanha.rows().data().toArray().map(function(item) {
            return {
                valorFixo: item.valorFixo,
                idCenario: item.id,
            }
        });

        if (temCampanhaItem && tabelaItensCadCampanha.rows().data().toArray().length > 0){
            if (nomeCampanha == '' || idEmpresaCad == null || $('#dataInicio').val() == '' || $('#dataFim').val() == ''){
                alert('Preencha todos os campos!');
                botao.html('Salvar');
                botao.attr('disabled', false);
            }else if (new Date(dataInicioEn) > new Date(dataFimEn)){
                alert('Data de início não pode ser maior que a data de fim.');
                botao.html('Salvar');
                botao.attr('disabled', false);    
            }else{
                $.ajax({
                    url: `<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarCampanhaEItem') ?>`,
                    type: "POST",
                    dataType: "json",
                    data: {
                        idEmpresa: idEmpresaCad,
                        nome: nomeCampanha,
                        dataInicio: dataInicio,
                        dataFim: dataFim,
                        temCampanhaItem: temCampanhaItem,
                        temCampanhaCenario: temCampanhaCenario,
                        itens: dadosTabelaInserir,
                    },
                    success: function(response){
                        if (response.status == 200){
                            alert(response.dados.mensagem);
                            $("#modalCadCampanha").modal("hide");
                            tabelaCampanha.ajax.reload();
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }else if (response.status != '500' && 'mensagem' in response.dados) {
                            alert(response.dados.mensagem);
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        } else {
                            alert('Erro ao cadastrar campanha, tente novamente');
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }
                    },
                    error: function(error){
                        alert('Erro ao cadastrar campanha, tente novamente');
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }
                });
            }
        } else if (temCampanhaCenario && tabelaCenariosCadCampanha.rows().data().toArray().length > 0) {
            if (nomeCampanha == '' || idEmpresaCad == null || $('#dataInicio').val() == '' || $('#dataFim').val() == ''){
                alert('Preencha todos os campos!');
                botao.html('Salvar');
                botao.attr('disabled', false);
            }else if (new Date(dataInicioEn) > new Date(dataFimEn)){
                alert('Data de início não pode ser maior que a data de fim.');
                botao.html('Salvar');
                botao.attr('disabled', false);    
            }else{
                $.ajax({
                    url: `<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarCampanhaECenario') ?>`,
                    type: "POST",
                    dataType: "json",
                    data: {
                        idEmpresa: idEmpresaCad,
                        nome: nomeCampanha,
                        dataInicio: dataInicio,
                        dataFim: dataFim,
                        temCampanhaItem: temCampanhaItem,
                        temCampanhaCenario: temCampanhaCenario,
                        campanhaCenariosForm: dadosCenariosTabelaInserir,
                    },
                    success: function(response){
                        if (response.status == 200){
                            alert(response.dados.mensagem);
                            $("#modalCadCampanha").modal("hide");
                            tabelaCampanha.ajax.reload();
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        } else if (response.status != '500' && 'mensagem' in response.dados) {
                            alert(response.dados.mensagem);
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        } else {
                            alert('Erro ao cadastrar campanha, tente novamente');
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }
                    },
                    error: function(error){
                        alert('Erro ao cadastrar campanha, tente novamente');
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }
                });
            }
        } else { 
            if ($('#nomeHeaderModal').html().includes('<?=lang("nova_campanha")?>')){
                if (temCampanhaItem) {
                    alert('Adicone pelo menos um Item a tabela!');
                } else {
                    alert('Adicone pelo menos um Cenário a tabela!');
                }
                
                botao.html('Salvar');
                botao.attr('disabled', false);   
                return;

                if (nomeCampanha == '' || idEmpresaCad == null || $('#dataInicio').val() == '' || $('#dataFim').val() == ''){
                    alert('Preencha todos os campos!');
                    botao.html('Salvar');
                    botao.attr('disabled', false);
                }else if (new Date(dataInicioEn) >= new Date(dataFimEn)){
                    alert('Data de início não pode ser maior que a data de fim');
                    botao.html('Salvar');
                    botao.attr('disabled', false);    
                }else{
                    $.ajax({
                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarCampanha') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            nome: nomeCampanha,
                            idEmpresa: idEmpresaCad,
                            dataInicio: dataInicio,
                            temCampanhaItem: temCampanhaItem,
                            temCampanhaCenario: temCampanhaCenario,
                            dataFim: dataFim
                        },
                        success: function(data){
                            if (data.status === 200){
                                alert(data.dados.mensagem);
                                $('#modalCadCampanha').modal('hide');
                                tabelaCampanha.ajax.reload();
                                botao.html('Salvar');
                                botao.attr('disabled', false);
                            }else{
                                alert(data.dados.mensagem)
                                botao.html('Salvar');
                                botao.attr('disabled', false);
                            }
                        },
                        error: function(){
                            alert('Erro ao cadastrar campanha')
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }
                    });
                }
            }else{
                if (nomeCampanha == '' || idEmpresaEdit == null || $('#dataInicio').val() == '' || $('#dataFim').val() == ''){
                    alert('Preencha todos os campos!');
                    botao.html('Salvar');
                    botao.attr('disabled', false);
                }else if (new Date(dataInicioEn) > new Date(dataFimEn)){
                    alert('Data de início não pode ser maior que a data de fim');
                    botao.html('Salvar');
                    botao.attr('disabled', false);    
                }else{
                    if (status == 0){
                        if (!confirm("A inativação da campanha irá removê-la da lista de visualização. Deseja realmente inativá-la?")){
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                            return false;
                        }
                    }
                    
                    $.ajax({
                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarCampanha') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            idCampanha: idCampanha,
                            idEmpresa: idEmpresaEdit,
                            nome: nomeCampanha,
                            dataInicio: dataInicio,
                            dataFim: dataFim,
                            temCampanhaItem: temCampanhaItem,
                            temCampanhaCenario: temCampanhaCenario,
                            status: status
                        },
                        success: function(data){
                            if (data.status === 200){
                                alert(data.dados.mensagem);
                                $('#modalCadCampanha').modal('hide');
                                botao.html('Salvar');
                                botao.attr('disabled', false);
                                tabelaCampanha.ajax.reload();
                            }else{
                                if('dados' in data && data.dados && 'mensagem' in data.dados) {
                                    alert(data.dados.mensagem)
                                } else {
                                    alert('Erro ao editar campanha!');
                                }
                                
                                botao.html('Salvar');
                                botao.attr('disabled', false);
                            }
                        },
                        error: function(){
                            alert('Erro ao editar campanha!');
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }
                    });
                }
            }
        }
    });

    $('#selectEmpresaCad').select2({
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR"
    });

    $('#pesqnome').select2({
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR",
    });

    $("#selectEmpresaCad").empty()
    $("#selectEmpresaCad").append($('<option>', {
        value: 0,
        text: 'Selecione uma Opção'
    }).prop('disabled', true))

    $("#pesqnome").empty()
    $("#pesqnome").append($('<option>', {
        value: 0,
        text: 'Selecione uma Opção'
    }).prop('disabled', true))

    $.ajax({
        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data['results']) {
                data['results'].forEach(opcao => {
                    $("#selectEmpresaCad").append($('<option>', {
                        value: opcao['id'],
                        text: opcao['text']
                    }))
                    $("#pesqnome").append($('<option>', {
                        value: opcao['id'],
                        text: opcao['text']
                    }))
                });
                $("#selectEmpresaCad").select2("val", " ");
                $("#pesqnome").select2("val", " ");
            } else {
                alert("Erro ao buscar Empresas!");
            }
        },
        error: function(data){
            alert("Erro ao buscar Empresas!");
        }
    })


    $('#BtnLimparPesquisar').click(function(e) {
        e.preventDefault();
        $('#BtnLimparPesquisar').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Limpando...');
        $('#pesquisacampanhaEmpresa').attr('disabled', true)

        $('#pesqnome').select2("val"," ");
        $('#dataInicial').val("");
        $('#dataFinal').val("");

        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCampanhas') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idEmpresa: ''
            },
            success: function(data){
                if (data.status === 200){
                    tabelaCampanha.clear().draw();
                    tabelaCampanha.rows.add(data.results).draw();
                } else if (data.status == 404) { 
                    alert('Não foram encontradas campanhas!');
                    tabelaCampanha.clear().draw();
                } else {
                    alert('Erro ao listar campanhas.');
                    tabelaCampanha.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao listar campanhas.');
            },
            complete: function () {
                $('#BtnLimparPesquisar').attr('disabled', false).html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar');
                $('#pesquisacampanhaEmpresa').attr('disabled', false);
            }
        });
        
    });

    $('#pesquisacampanhaEmpresa').click(function(e){
        e.preventDefault();
        var idEmpresa = $('#pesqnome').val();
        var dataInicial = $('#dataInicial').val();
        var dataFinal = $('#dataFinal').val();

        if(!idEmpresa || idEmpresa == 0){
            alert("O campo Empresa é obrigatorio!");
            return;
        }

        if (dataInicial && !dataFinal) {
            alert("Para pesquisar por data digite também a Data Final!");
            return;
        }

        if (!dataInicial && dataFinal) {
            alert("Para pesquisar por data digite também a Data Inicial!");
            return;
        }

        if(new Date(dataInicial) >= new Date(dataFinal)){
            alert("A Data Final precisa ser maior que a Data Inicial!");
            return;
        }

        $('#pesquisacampanhaEmpresa').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');
        $('#BtnLimparPesquisar').attr('disabled', true);

        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCampanhas') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idEmpresa: idEmpresa,
                dataInicial: dataInicial,
                dataFinal: dataFinal
            },
            success: function(data) {
                if (data.status === 200) {
                    tabelaCampanha.clear().draw();
                    tabelaCampanha.rows.add(data.results).draw();
                } else if (data.status == 404) { 
                    alert('Dados não encontrados para os parâmetros informados!');
                    tabelaCampanha.clear().draw();
                } else {
                    alert('Erro ao pesquisar campanhas.');
                    tabelaCampanha.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao pesquisar campanhas.');
            },
            complete: function(){
                $('#pesquisacampanhaEmpresa').attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
                $('#BtnLimparPesquisar').attr('disabled', false);
            }
        });
    });


    $('#dataInicio').on('change', function(){
        var dataInicio = this.value;

        $('#dataFim').attr('min', dataInicio);
    
    });

    $('#dataFim').on('change', function(){
        var dataFim = this.value;

        $('#dataInicio').attr('max', dataFim);
    
    });
        
    function visualizarItensCampanha(botao, id, temCampanhaCenario, event){
        event.preventDefault();
        $('#acoes').css('width', '50px');
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        if (temCampanhaCenario == '1') {
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idCampanha: id
                },
                success: function(data) {
                    if (data.status === 200) {
                        tabelaCenariosDaCampanha.clear().draw();
                        tabelaCenariosDaCampanha.rows.add(data.results).draw();
                        $('#modalCenariosDaCampanha').modal('show');
                        btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>')
                    } else if (data.status === 404) {
                        tabelaCenariosDaCampanha.clear().draw();
                        $('#modalCenariosDaCampanha').modal('show');
                        alert('Essa campanha não possui cenários.')
                        btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>')
                    } else {
                        alert('Erro ao visualizar cenários da campanha');
                        btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>');
                    }
                },
                error: function() {
                    alert('Erro ao visualizar cenários da campanha')
                    btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>');
                }
            });
        } else {
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarItensCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idCampanha: id
                },
                success: function(data) {
                    if (data.status === 200) {
                        tabelaItensDaCampanha.clear().draw();
                        tabelaItensDaCampanha.rows.add(data.results).draw();
                        $('#modalItensDaCampanha').modal('show');
                        btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>')
                    } else if (data.status === 404) {
                        tabelaItensDaCampanha.clear().draw();
                        $('#modalItensDaCampanha').modal('show');
                        alert('Essa campanha não possui Itens.')
                        btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>')
                    } else {
                        alert('Erro ao visualizar itens da campanha')
                        btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>');
                    }
                },
                error: function() {
                    alert('Erro ao visualizar itens da campanha')
                    btn.attr('disabled', false).html('<i class="fa fa-list-ul" aria-hidden="true"></i>');
                }
            });
        }
        
    }

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

    function excluirItemTabela(botao, valorMeta, event) {
        tabelaItensCadCampanha.row(botao.parentNode.parentNode).remove().draw();
    }

    $('#adicionarItemTabela').click(function() {

        valorMeta = formatValorInserir($('#valorMeta').val());
        percentualComissao = ($('#percentualComissao').val()).replace(',', '.').replace('%', '');
        aplicaClienteBase = $('#aplicaClienteBase option:selected').text();

        if (valorMeta === '' || percentualComissao === ''){
            alert('Preencha todos os campos!');
        } else if (isNaN(percentualComissao) || percentualComissao < 0 || percentualComissao > 100) {
            alert('Por favor, insira um valor de Percentual Comissão entre 0% e 100%.');
        }else{
            tabelaItensCadCampanha.rows.add([
                {valorMeta: valorMeta, percentualComissao: percentualComissao, aplicaClienteBase: aplicaClienteBase}
            ]).draw();

            $('#valorMeta').val('');
            $('#percentualComissao').val('');
            $('#aplicaClienteBase').val(1);
        }

    })

    function excluirCenarioTabela(botao) {
        tabelaCenariosCadCampanha.row(botao.parentNode.parentNode).remove().draw();
    }

    $('#adicionarCenarioTabela').click(function() {

        valorFixo = formatValorInserir($('#valorFixo').val());
        cenarioCampanha = $('#cenarioCampanha option:selected').text();
        id = $('#cenarioCampanha').val();


        if (valorFixo === '' || cenarioCampanha === '' || id === ''){
            alert('Preencha todos os campos!');
        } else{
            tabelaCenariosCadCampanha.rows.add([
                {valorFixo: valorFixo, cenarioCampanha: cenarioCampanha, id: id}
            ]).draw();

            $('#valorFixo').val('');
            $('#cenarioCampanha').val('').trigger('change');
        }

    })

    async function abrirAddItemCampanha(bota, id, nomeCampanha, razaoSocial, temCampanhaCenario, event) {
        event.preventDefault();
        if (temCampanhaCenario == '1') {
            $('#loading').show();

            let cenario = await buscarCenarios();

            $('#addCenario').select2({
                data: cenario.results,
                placeholder: "Selecione o cenário de venda",
                allowClear: true,
                language: "pt-BR",
                width: '100%',
                });
            
            $('#addCenario').on('select2:select', function (e) {
                var data = e.params.data;
            });

            $('#addCenario').val('').trigger('change');

            $('#loading').hide();
            $('#modalAddCenarioCampanha').modal('show');
            $('#btnSalvarAddCenarioCampanha').data('id', id);
            $('#nome-modal-cenario-header').html('Novo Cenário Campanha' + ' - ' + razaoSocial + ' - ' + nomeCampanha);
        } else {
            $('#modalAddItemCampanha').modal('show');
            $('#btnSalvarAddItemCampanha').data('id', id);
            $('#nome-modal-header').html('<?= lang("novo_item_campanha") ?>' + ' - ' + razaoSocial + ' - ' + nomeCampanha);
        }
    }

    $('#AddCenarioCampanha').submit(function(e) {
        e.preventDefault();
        var idCampanha = $('#btnSalvarAddCenarioCampanha').data('id');
        var idCenario = $('#addCenario').val();
        var valorFixo = formatValorInserir($('#addCenarioValorFixo').val());

        if (valorFixo == "" || idCenario == "") {
            alert('Por favor, preencha os valores obrigatórios!');
            return;
        }

        btn = $('#btnSalvarAddCenarioCampanha');

        if ($('#nome-modal-cenario-header').html().includes('Novo Cenário Campanha')) {
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarCenarioCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idCampanha: idCampanha,
                    valorFixo: valorFixo,
                    idCenario: idCenario
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert("Cenário salvo com sucesso!");
                        $('#modalAddCenarioCampanha').modal('hide');
                        btn.attr('disabled', false).html('Salvar');
                    } else if ("dados" in data && data.dados && 'mensagem' in data.dados && data.status != 500) {
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                    } else {
                        alert('Erro ao inserir cenário na campanha');
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(data) {
                    alert('Erro ao inserir cenário na campanha')
                    btn.attr('disabled', false).html('Salvar');
                }
            });
        } else {
            var status = $('#btnSalvarAddCenarioCampanha').data('status');
            var idItem =  $('#btnSalvarAddCenarioCampanha').data('idItem');

            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarCenarioCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: idItem,
                    idCampanha: idCampanha,
                    valorFixo: valorFixo,
                    idCenarioVenda: idCenario,
                    status: status
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        $('#modalAddCenarioCampanha').modal('hide');
                        btn.attr('disabled', false).html('Salvar');
                        atualizaTabelaCenariosCampanha(idCampanha);

                    } else if ("dados" in data && data.dados && 'mensagem' in data.dados && data.status != 500) {
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                    } else {
                        alert('Erro ao editar o cenário na campanha');
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(data) {
                    alert('Erro ao editar item na campanha')
                    btn.attr('disabled', false).html('Salvar');
                }
            });
        }

    })

    $('#AddItemCampanha').submit(function(e) {
        e.preventDefault();
        var idItem = $('#btnSalvarAddItemCampanha').data('idItem');
        var idCampanha = $('#btnSalvarAddItemCampanha').data('id');
        var valorMeta = formatValorInserir($('#addItemValorMeta').val());
        var percentualComissao = ($('#addItemPercentualComissao').val()).replace(',', '.').replace('%', '');
        var aplicaClienteBase = $('#addItemAplicaClienteBase').val();
        var status = $('#btnSalvarAddItemCampanha').data('status');
        if (status == "Ativo") {
            status = 1;
        } else {
            status = 0;
        }

        if (isNaN(percentualComissao) || percentualComissao < 0 || percentualComissao > 100) {
            alert('Por favor, insira um valor de Percentual Comissão entre 0% e 100%.');
            return;
        }

        btn = $('#btnSalvarAddItemCampanha');

        if ($('#nome-modal-header').html().includes('<?= lang("novo_item_campanha") ?>')) {
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarItemCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idCampanha: idCampanha,
                    valorMeta: valorMeta,
                    percentualComissao: percentualComissao,
                    aplicaClienteBase: aplicaClienteBase
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        $('#modalAddItemCampanha').modal('hide');
                        btn.attr('disabled', false).html('Salvar');
                    }else if ("dados" in data && data.dados && 'mensagem' in data.dados && data.status != 500) {
                        alert(data.dados.mensagem);
                        btn.attr('disabled', false).html('Salvar');
                    } else {
                        alert('Erro ao inserir item na campanha');
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(data) {
                    alert('Erro ao inserir item na campanha')
                    btn.attr('disabled', false).html('Salvar');
                }
            });
        } else {
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarItemCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idItem: idItem,
                    idCampanha: idCampanha,
                    valorMeta: valorMeta,
                    percentualComissao: percentualComissao,
                    aplicaClienteBase: aplicaClienteBase,
                    status: status
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        $('#modalAddItemCampanha').modal('hide');
                        btn.attr('disabled', false).html('Salvar');
                        atualizaTabelaItensCampanha(idCampanha);

                    } else if ("dados" in data && data.dados && 'mensagem' in data.dados && data.status != 500) {
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                    } else {
                        alert('Erro ao editar item na campanha');
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(data) {
                    alert('Erro ao editar item na campanha')
                    btn.attr('disabled', false).html('Salvar');
                }
            });
        }

    })

    $('#modalAddItemCampanha').on('hidden.bs.modal', function() {
        $('#addItemValorMeta').val('');
        $('#addItemPercentualComissao').val('');
        $('#addItemAplicaClienteBase').val(1);
    })

    $('#modalAddCenarioCampanha').on('hidden.bs.modal', function() {
        $('#addCenario').val('').trigger('change');
        $('#addCenarioValorFixo').val('');
    })

    function atualizaTabelaItensCampanha(id) {
        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarItensCampanha') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idCampanha: id
            },
            success: function(data) {
                if (data.status === 200) {
                    tabelaItensDaCampanha.clear().draw();
                    tabelaItensDaCampanha.rows.add(data.results).draw();
                } else {
                    alert(data.results.mensagem)
                }
            },
            error: function() {
                alert('Erro ao atualizar listagem de itens da campanha. Feche o modal e tente novamente.')
            }
        });
    }

    function atualizaTabelaCenariosCampanha(id) {
        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosCampanha') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idCampanha: id
            },
            success: function(data) {
                if (data.status === 200) {
                    tabelaCenariosDaCampanha.clear().draw();
                    tabelaCenariosDaCampanha.rows.add(data.results).draw();
                } else {
                    alert(data.results.mensagem)
                }
            },
            error: function() {
                alert('Erro ao atualizar listagem de cenários da campanha. Feche o modal e tente novamente.')
            }
        });
    }

    function alterarStatusItem(botao, id, status, idItem, event) {
        event.preventDefault();
        btn = $(botao);
        if (status == 'Ativo') {
            status = 0
        } else {
            status = 1
        }
        if (confirm('Deseja realmente alterar o status deste item?')) {
            btn.attr('disabled', true);
            btn.attr('class', 'btn');
            btn.html('<i class="fa fa-spinner fa-spin"></i>')

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/alterarStatusItemCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idItem: idItem,
                    status: status
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false);
                        btn.html('')
                        btn.attr('class', 'btn fa fa-exchange');
                        atualizaTabelaItensCampanha(id);
                    } else {
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false);
                    }
                    btn.html('')
                    btn.attr('class', 'btn fa fa-exchange');
                },
                error: function() {
                    alert('Erro ao alterar status do item')
                    btn.attr('disabled', false);
                    btn.html('')
                    btn.attr('class', 'btn fa fa-exchange');
                }
            });
        }
    }

    function abrirEditarItemCampanha(botao, idCampanha, idItem, valorMeta, percentualComissao, aplicaClienteBase, status, event) {
        event.preventDefault();

        valorMeta = parseFloat(valorMeta).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).replace('R$', '').trim();

        $('#addItemValorMeta').val(valorMeta).trigger('input');
        $('#addItemPercentualComissao').val(percentualComissao);
        $('#addItemAplicaClienteBase').val(aplicaClienteBase);
        $('#nome-modal-header').html('<?= lang("editar_item_campanha") ?>');
        $('#btnSalvarAddItemCampanha').data('id', idCampanha);
        $('#btnSalvarAddItemCampanha').data('idItem', idItem);
        $('#btnSalvarAddItemCampanha').data('status', status);

        $('#modalAddItemCampanha').modal('show');
    }

    async function abrirEditarCenarioCampanha(botao, idCampanha, id, idCenario, valorFixo, status, event) {
        event.preventDefault();

        $('#loading').show();

        let cenario = await buscarCenarios();

        $('#addCenario').select2({
            data: cenario.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#addCenario').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#addCenario').val('').trigger('change');

        valorFixo = parseFloat(valorFixo).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).replace('R$', '').trim();

        status = (status == 'Ativo' ? 1 : status == 'Inativo' ? 0 : status);
        
        $('#addCenarioValorFixo').val(valorFixo).trigger('input');
        $('#addCenario').val(idCenario).trigger('change');
        $('#nome-modal-cenario-header').html('Editar Cenário Campanha');
        $('#btnSalvarAddCenarioCampanha').data('id', idCampanha);
        $('#btnSalvarAddCenarioCampanha').data('idItem', id);
        $('#btnSalvarAddCenarioCampanha').data('status', status);

        $('#loading').hide();
        $('#modalAddCenarioCampanha').modal('show');
    }

    function alterarStatusCenario(botao, id, idCampanha, status, event) {
        event.preventDefault();

        btn = $(botao);

        if (status == 'Ativo') {
            status = 0
        } else {
            status = 1
        }

        if (confirm('Deseja realmente alterar o status deste cenário?')) {
            btn.attr('disabled', true);
            btn.attr('class', 'btn');
            btn.html('<i class="fa fa-spinner fa-spin"></i>')

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/alterarStatusCenarioCampanha') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert("Status do cenário aterado com sucesso!");
                        btn.attr('disabled', false);
                        btn.html('')
                        btn.attr('class', 'btn fa fa-exchange');
                        atualizaTabelaCenariosCampanha(idCampanha);
                    } else if ("dados" in data && data.dados && 'mensagem' in data.dados && data.status != 500) {
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false);
                    } else {
                        alert('Erro ao alterar status do cenário!');
                        btn.attr('disabled', false);
                    }
                    btn.html('')
                    btn.attr('class', 'btn fa fa-exchange');
                },
                error: function() {
                    alert('Erro ao alterar status do cenário!');
                    btn.attr('disabled', false);
                    btn.html('')
                    btn.attr('class', 'btn fa fa-exchange');
                }
            });
        }
    }

    function formatValorInserir(value) {
        value = value.replace('.', '');
        value = value.replace(',', '.');

        return value;

    }

    $('#percentualComissao').mask('000,00%', {reverse: true});
    $('#addItemPercentualComissao').mask('000,00%', {reverse: true});

    document.addEventListener('keydown', function(event) {
        var key = event.which || event.keyCode;
        if (key === 13) {
            event.preventDefault();
        }
    });
</script>
