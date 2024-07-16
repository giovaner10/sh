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
	.bord{
        border-left: 3px solid #03A9F4;
    }
	.sorting_disabled{
		width: auto !important;
	}

	.top.length-selector {
        margin-top: 20px;
    }
</style>

<h3><?=lang("Faturas")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('telecom')?> >
	<?=lang('Faturas')?>
</div>
<hr>
<br>
<div class="col-md-12 alert alert-info">
	<div class ="col-md-12">
		<h5>Forneça as seguintes informações para a pesquisa:</h5>
		<br>
		<div class="row">
			<div class="col-md-2">
				<label for="selectoperadoras">Operadora:</label>
				<select class="form-control input-sm" id="selectoperadoras" name="nome" type="text" style="width: 100%">
					<option value="">Buscando...</option>
				</select>
			</div>
			<div class="col-md-2">
				<label>Mês de Referência:</label>
				<select class="form-control input-sm" id="selectMesReferencia" name="nome" type="text" style="width: 100%">
					<option value="" selected>Últimos 30 Dias</option>
					<option value="01/01/">Janeiro</option>
					<option value="01/02/">Fevereiro</option>
					<option value="01/03/">Março</option>
					<option value="01/04/">Abril</option>
					<option value="01/05/">Maio</option>
					<option value="01/06/">Junho</option>
					<option value="01/07/">Julho</option>
					<option value="01/08/">Agosto</option>
					<option value="01/09/">Setembro</option>
					<option value="01/10/">Outubro</option>
					<option value="01/11/">Novembro</option>
					<option value="01/12/">Dezembro</option>
				</select>
			</div>
			<div class="col-md-2" id="divAnoReferencia" hidden>
				<label>Ano Referência:</label>
				<input id="anoReferencia" class="form-control input-sm" type="text" maxlength="4" oninput="validarAno(this)" placeholder="Ex: 2023">
			</div>
			<div class="col-sm-4">
				<button class="btn btn-primary" id="btnBusca" type="submit" style="margin-top: 23px;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
				<button class="btn btn-primary" id="BtnLimpar" type="button" style="margin-top: 23px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
			</div>
		</div>
	</div>
</div>
<br>

<div class="col-md-12">
	<a id="abrirModalCadastrar" class="btn btn-primary"><?=lang("nova_fatura")?></a>
	<table id="tabela_faturas" class="table table-bordered table-hover responsive" style="width:100%;">
		<thead>
			<tr>
				<th>Operadora</th>
				<th>Conta</th>
				<th>Data Início</th>
				<th>Data Fim</th>
				<th>Valor</th>
				<th>Vencimento</th>
				<th>Data de Cadastro</th>
				<th>Data de Atualização</th>
				<th>Ações</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<!-- Modal cadastrar fatura -->
<div id="modalCadFatura" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadFatura">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("nova_fatura")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
							<ul class="nav nav-tabs" style="margin-bottom: 10px;">
                                <li class="nav-item">
                                    <a id = "tab-dadosGerais" href="" data-toggle="tab" class="nav-link active">Dados Gerais</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-itensDaFatura" href="" data-toggle="tab" class="nav-link">Itens da Fatura</a>
                                </li>
                            </ul>
                            <div id="dadosGerais" style="display: block; padding: 0 10px">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
											<label>Mês de Referência:</label>
											<select id="selectCadMesReferencia" name="selectCadMesReferencia" class="form-control input-sm" required>
												<option value="" disabled selected>Selecione o mês</option>
												<option value="01">Janeiro</option>
												<option value="02">Fevereiro</option>
												<option value="03">Março</option>
												<option value="04">Abril</option>
												<option value="05">Maio</option>
												<option value="06">Junho</option>
												<option value="07">Julho</option>
												<option value="08">Agosto</option>
												<option value="09">Setembro</option>
												<option value="10">Outubro</option>
												<option value="11">Novembro</option>
												<option value="12">Dezembro</option>
											</select>
                                        </div>
										<div class="col-md-6 form-group bord">
											<label>Data Inicial:</label>
											<input id="cadDataInicio" class="form-control input-sm" type="date" required>
										</div>
										<div class="col-md-6 form-group bord">
											<label>Data Final:</label>
											<input id="cadDataFim" class="form-control input-sm" type="date" required>
										</div>
										<div class="col-md-6 form-group bord">
											<label>Vencimento:</label>
											<input id="CadVencimento" class="form-control input-sm" type="date" required>
										</div>
										<div class="col-md-6 form-group bord">
											<label>Operadora:</label>
											<select id="cadSelectModalOperadoras" name="cadSelectModalOperadoras" class="form-control" style="width: 100%"required><option value="">Buscando...</option></select>
										</div>
										<div class="col-md-6 form-group bord">
											<label>Conta:</label>
											<input id="CadNumeroConta" class="form-control input-sm" type="text" placeholder="Digite a conta" required>
										</div>
                                    </div>
                                </div>
                            </div>
							<div id="itensDeFatura" style="display: none !important;padding: 0 10px">
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 form-group bord">
    		    						    <label>Serviço:</label>    
    		    						    <select class="form-control input-sm" id="selectServicoCad" name="nome" type="text" style="width: 100%"><option value="0" selected="selected" disabled>Selecione o serviço</option></select>
    									</div>
										<div class="col-md-6 form-group bord">
    		    						    <label>Linha:</label>    
    		    						    <select class="form-control input-sm" id="numeroLinhaCad" name="linha" type="text" style="width: 100%"></select>
    									</div>
									</div>
									<div class="row">
										<div class="col-md-6 form-group bord">
											<label>Valor:</label>    
											<input class="form-control input-sm" id="valorItemCad" name="nome" type="text" style="width: 100%" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor em R$">
    									</div>
										<div class="col-md-6 form-group">
											<a class="btn btn-primary" id="adicionarItemTabela" style="margin-top: 23px;">Adicionar</a>
    									</div>
									</div>
								</div>
								<table class="table-responsive table-bordered table" id="tabelaItensCadastro" style="width: 100%;">
                		    		<thead>
                		    		    <tr class="tableheader">
										<th>IdServiço</th>
                		    		    <th>Serviço</th>
                		    		    <th>Linha</th>
                		    		    <th>Valor</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <a class="btn btn-primary" id="btnCadastrarFatura" style="margin-right: 10px;">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalAddItemFatura" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            	<form name="modalAddItemFatura">
                	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3><?=lang("adicionar_item_fatura")?> - <span class="operadoraLabel"></span> - <span class="codigoLabel"></span></h3>
                	</div>
                	<div class="modal-body scrollModal">
						<div class="col-md-12">
                	    	<div class="row">
								<div class="col-md-6 form-group bord">
    		    				    <label>Serviço:</label>    
    		    				    <select class="form-control input-sm" id="selectServico" name="nome" type="text" style="width: 100%"><option value="0" selected="selected" disabled>Selecione o serviço</option></select>
    							</div>
								<div class="col-md-6 form-group bord">
    		    				    <label>Linha:</label>    
    		    				    <select class="form-control input-sm" id="selectLinha" name="nome" type="text" style="width: 100%"><option value="0" selected disabled>Buscando...</option></select>
    							</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group bord">
									<label>Valor:</label>    
									<input class="form-control input-sm" id="valorItem" name="nome" type="text" style="width: 100%" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor em R$">
    							</div>
								<div class="col-md-6 form-group" hidden>
									<label>Id Fatura</label>    
									<input class="form-control input-sm" id="idFaturaInput" name="nome" type="text" style="width: 100%">
    							</div>                                
                	    	</div>
						</div>
					</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <a class="btn btn-primary" id="btnSalvarItemFatura" style="margin-right: 15px;">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal editar Fatura -->
<div id="modalEditFatura" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form name="formEditarFatura">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("editar_fatura")?> - <span class="operadoraLabel"></span> - <span class="codigoLabel"></span></h3>
                </div>
                <div id="bodyModalEditarFatura" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
							<ul class="nav nav-tabs" style="margin-bottom: 10px;">
                                <li class="nav-item">
                                    <a id = "tab-dadosGeraisEdit" href="" data-toggle="tab" class="nav-link active">Dados Gerais</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-itensDaFaturaEdit" href="" data-toggle="tab" class="nav-link">Itens da Fatura</a>
                                </li>
                            </ul>
                            <div id="dadosGeraisEdit" style="display: block;padding: 0 10px">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
											<label>Mês de Referência:</label>
											<select id="selectEditMesReferencia" class="form-control input-sm">
												<option value="" selected disabled>Selecione um mês</option>
												<option value="01">Janeiro</option>
												<option value="02">Fevereiro</option>
												<option value="03">Março</option>
												<option value="04">Abril</option>
												<option value="05">Maio</option>
												<option value="06">Junho</option>
												<option value="07">Julho</option>
												<option value="08">Agosto</option>
												<option value="09">Setembro</option>
												<option value="10">Outubro</option>
												<option value="11">Novembro</option>
												<option value="12">Dezembro</option>
											</select>
                                        </div>
                                        <div class="col-md-6 form-group bord">
											<label>Data Inicial:</label>
											<input id="editDataInicio" class="form-control input-sm" type="date">
                                        </div>
                                        <div class="col-md-6 form-group bord">
											<label>Data final:</label>
											<input id="editDataFim" class="form-control input-sm" type="date">
                                        </div>
                                        <div class="col-md-6 form-group bord">
											<label>Vencimento:</label>
											<input id="editVencimento" class="form-control input-sm" type="date">
                                        </div>
                                        <div class="col-md-6 form-group bord">
											<label>Operadora:</label>
											<select id="editSelectOperadora" name="editSelectModalOperadoras" class="form-control input-sm" style="width: 100%"required><option value="">Buscando operadora desta fatura...</option></select>
                                        </div>
                                        <div class="col-md-6 form-group bord">
											<label>Conta:</label>
											<input id="editNumeroConta" class="form-control input-sm" type="text">
                                        </div>
                                        <div class="col-md-6 form-group bord">
											<label>Valor Total:</label>
											<input id="editValorTotal" class="form-control input-sm" type="text" onkeyup="formatarMoeda(this.id)" disabled>
                                        </div>
										<div class="col-md-6 form-group bord">
											<label>Status:</label>
											<select id="editStatus" class="form-control input-sm">
												<option value="0">Inativo</option>
												<option value="1">Ativo</option>
											</select>
										</div>
										<div class="col-md-6 form-group" hidden>
											<label>Id</label>
											<input id="editIdFatura" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div id="itensDeFaturaEdit" style="display: none !important;">
								<a id="btnAbrirAddItemEdit" class="btn btn-primary">Adicionar Item</a>
								<table class="table-responsive table-bordered table" id="tabelaItensDeFatura" style="width: 100%;">
                		    		<thead>
                		    		    <tr class="tableheader">
                		    		    <th>Serviço</th>
                		    		    <th>Linha</th>
                		    		    <th>Valor</th>
                		    		    <th>Data de Cadastro</th>
                		    		    <th>Data da Atualização</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <a class="btn btn-primary" id="btnSalvarEditFatura" style="margin-right: 10px;">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditItemFatura" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
    		<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        		<h3><?=lang("editar_item_fatura")?> - <span class="operadoraLabel"></span> - <span class="codigoLabel"></span></h3>
    		</div>
    		<div class="modal-body scrollModal">
				<div class="row">
                    <div class="col-md-12">
                        <div class="tab-content" style="padding: 0 10px;">
							<div class="row">
    							<div class="col-md-6 form-group bord">
    		    				    <label>Serviço:</label>    
    		    				    <select class="form-control input-sm" id="selectServicoEditItem" name="nome" type="text" style="width: 100%"></select>
    							</div>
								<div class="col-md-6 form-group bord">
    		    				    <label>Linha:</label>    
    		    				    <select class="form-control input-sm" id="selectNumeroLinhaEditItem" name="nome" type="text" style="width: 100%"><option value="0" selected disabled>Buscando...</option></select>
    							</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group bord">
									<label>Valor:</label>    
									<input class="form-control input-sm" id="valorItemEditItem" name="nome" type="text" style="width: 100%" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor em R$">
    							</div>
								<div class="col-md-6 form-group bord">
									<label>Status: </label>    
									<select id="statusEditItem" class="form-control input-sm">
										<option value="0">Inativo</option>
										<option value="1">Ativo</option>
									</select>
    							</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group" hidden>
									<label>idItem:</label>    
									<input class="form-control input-sm" id="idItemEditItem" name="nome" type="text" style="width: 100%">
    							</div>
								<div class="col-md-6 form-group" hidden>
									<label>IdFatura:</label>    
									<input class="form-control input-sm" id="idFaturaEditItem" name="nome" type="text" style="width: 100%">
    							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
    			<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
    			<a class="btn btn-primary" id="btnSalvarEditItem" style="margin-right: 10px;">Salvar</a>
    		</div>
		</div>
  	</div>
</div>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script>
	let tabelafaturas = $('#tabela_faturas').DataTable({
		responsive: true,
		ordering: true,
		paging: true,
		searching: true,
		info: true,
		language: lang.datatable,
		deferRender: true,
		lengthChange: false,
		order: [1, 'desc'],
		dom: '<"top length-selector"l><"top button-section"B>frtip',
		columns: [{
				//data: "idOperadora"
				data: function(row) {
					// Seleciona o elemento `select` do documento
					var selectElement = document.getElementById("selectoperadoras");
					// Seleciona o texto do elemento `option` selecionado
					var selectedText = selectElement.options[selectElement.selectedIndex].text;
					// Retorna o texto selecionado para ser exibido na coluna
					return selectedText;
				}
			},
			{
				data: "numeroConta"
			},
			{
				data: "dataInicio"
			},
			{
				data: "dataFim"
			},
			{
				data: "valorTotal",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
			{
				data: "vencimento"
			},
			{
				data: "dataCadastro",
				render: function(data) {
					return new Date(data).toLocaleDateString();
				}
			},
			{
				data: "dataUpdate",
				render: function(data) {
					return new Date(data).toLocaleDateString();
				}
			},
			{
				data:{ 'idOperadora': 'idOperadora' },
				orderable: true,
				render: function (data) {
					var selectElement = document.getElementById("selectoperadoras");
					var selectedText = selectElement.options[selectElement.selectedIndex].text;

					return `
					<button 
                        id="btnEditarFatura"
						class="btn btn-primary"
						title="Editar Fatura"
						style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
                        onClick="javascript:abrirEditarFatura(this, '${data['id']}', '${data['idOperadora']}', '${data['mesReferencia']}', '${data['dataInicio']}', '${data['dataFim']}', '${data['vencimento']}', '${data['numeroConta']}', '${data['valorTotal']}', '${data['status']}', '${selectedText}', event)">
                        <i class="fa fa-pencil" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
					</button>
					<button 
                        id="btnAddItemFatura"
						class="btn btn-primary"
						title="Adicionar Item de Fatura"
						style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
                        onClick="javascript:abrirAddItemFatura(this, '${data['id']}', '${data['idOperadora']}', '${data['numeroConta']}', '${selectedText}', event)">
                        <i class="fa fa-plus" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
					</button>
					`;
				}
			}	

		],
		buttons: [
			{
				filename: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura_${operadora}_${mesReferencia}_${anoReferencia}`;
				},
				title: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura ${operadora} - ${mesReferencia} de ${anoReferencia}`;
				},
				extend: 'excelHtml5',
                exportOptions: {
                        columns: ':visible:not(:last-child)'
                    },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
			{
				filename: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura_${operadora}_${mesReferencia}_${anoReferencia}`;
				},
				title: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura ${operadora} - ${mesReferencia} de ${anoReferencia}`;
				},
				extend: 'pdfHtml5',
				exportOptions: {
                        columns: ':visible:not(:last-child)'
                    },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
				customize: function (doc, tes) {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					var titulo = `Fatura ${operadora} - ${mesReferencia} de ${anoReferencia}`;
					pdfTemplateIsolated(doc, titulo);
					}
            	},
				{
				filename: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura_${operadora}_${mesReferencia}_${anoReferencia}`;
				},
				title: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura ${operadora} - ${mesReferencia} de ${anoReferencia}`;
				},
				extend: 'csvHtml5',
				exportOptions: {
                        columns: ':visible:not(:last-child)'
                    },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
			{
				filename: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura_${operadora}_${mesReferencia}_${anoReferencia}`;
				},
				title: function () {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					return `Fatura ${operadora} - ${mesReferencia} de ${anoReferencia}`;
				},
				extend: 'print',
                exportOptions: {
                        columns: ':visible:not(:last-child)'
                    },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function (win) {
					var operadora = $('#selectoperadoras option:selected').text();
					var mesReferencia = $('#selectMesReferencia option:selected').text();
					var anoReferencia = $('#anoReferencia').val();
					var titulo = `Fatura ${operadora} - ${mesReferencia} de ${anoReferencia}`;
					printTemplateImpressao(win, titulo);
				}
            }
        ],
	});

	let tabelaItensDeFatura = $('#tabelaItensDeFatura').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: lang.datatable,
		deferRender: true,
		lengthChange: false,
		columns: [
			{ data: 'nomeServico'},
			{ 
				data: 'numeroLinha',
				render: function(data) {
					let number = data;
					number = number.replace(/\D/g,'')
					number = number.replace(/(\d{2})(\d)/,"($1) $2")
					number = number.replace(/(\d)(\d{4})$/,"$1-$2")
					return number;
				}
			},
			{
				data: "valor",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
			{ data: 'dataCadastro', render: function(data) {
					return new Date(data).toLocaleDateString();
				}
			},
			{ data: 'dataUpdate',
				render: function(data) {
					return new Date(data).toLocaleDateString();
				}},
			{
				data:{ 'idServico': 'idServico' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnEditarItemFatura"
						class="btn btn-primary"
						title="Editar Fatura"
						style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
                        onClick="javascript:abrirEditarItemFatura(this, '${data['id']}', '${data['idFatura']}', '${data['idServico']}', '${data['numeroLinha']}', '${data['valor']}', '${data['status']}',event)">
                        <i class="fa fa-pencil" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
					</button>
					<button
                        class="btn fa fa-exchange"
                        title="Alterar Status Item"
                        style="width: 38px; height: 34px; margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        id="btnAlterarStatusItemFatura"
                        onClick="javascript:alterarStatusItemFatura(this,'${data['id']}', '${data['status']}', '${data['idFatura']}', event)">
                    </button>
					`;
				}
			}
		]

	})

	let tabelaItensCadastro = $('#tabelaItensCadastro').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum item adicionado",
            infoEmpty:          "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
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
			{ data: 'idServico',
				visible: false},
			{ data: 'nomeServico'},
			{ data: 'numeroLinha'},
			{
				data: "valor",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
			{
				data:{ 'idServico': 'idServico' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnExcluirItemTabela"
						class="btn fa fa-trash"
						title="Editar Fatura"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirItemTabela(this, '${data['numeroLinha']}' ,event)">
					`;
				}
			}
		],
	})

	$(document).ready(async function(){
		$('#selectoperadoras').attr('disabled', true);
		$('#cadSelectModalOperadoras').attr('disabled', true);
		$('#editSelectOperadora').attr('disabled', true);

    	    let operadoras = await $.ajax ({
    	                        url: '<?= site_url('GestaoDeChips/linhas/listarOperadoras') ?>',
    	                        dataType: 'json',
    	                        type: 'GET',  
    	                        success: function(data){
    	                            return data;
    	                        }           
    	                    })

    	    $('#selectoperadoras').select2({
    	            data: operadoras,
    	            placeholder: "Selecione a operadora",
    	            allowClear: true,
    	            language: "pt-BR",
			
    	    });

			$('#cadSelectModalOperadoras').select2({
    	            data: operadoras,
    	            placeholder: "Selecione a operadora",
    	            allowClear: true,
    	            language: "pt-BR",
			
    	    });

			$('#editSelectOperadora').select2({
		            data: operadoras,
		            placeholder: "",
		            allowClear: true,
		            language: "pt-BR",
			
		    });


    	    $('#selectoperadoras').on('select2:select', function (e) {
    	        var data = e.params.data;
    	    });

			$('#cadSelectModalOperadoras').on('select2:select', function (e) {
    	        var data = e.params.data;
    	    });

			$('#editSelectOperadora').on('select2:select', function (e) {
		        var data = e.params.data;
		    });

    	    $('#selectoperadoras').find('option').get(0).remove();
    	    $('#selectoperadoras').prepend('<option value="0" selected="selected" disabled>Selecione a operadora</option>');
    	    $('#selectoperadoras').attr('disabled', false);
    	    $('#cadSelectModalOperadoras').find('option').get(0).remove();
    	    $('#cadSelectModalOperadoras').prepend('<option value="0" selected="selected" disabled>Selecione a operadora</option>');
    	    $('#cadSelectModalOperadoras').attr('disabled', false);
			$('#editSelectOperadora').attr('disabled', false);
	});

	$('#btnBusca').click(function(e) {
		e.preventDefault();
		var mesReferencia = (($('#selectMesReferencia').val() == "") ? "" : $('#selectMesReferencia').val() + $('#anoReferencia').val());

		if ($('#selectoperadoras').val() == null) {
			alert('Selecione uma operadora!');
			return false;
		}else if ($('#selectMesReferencia').val() != "" && $('#anoReferencia').val() == "") {
			alert('Digite um ano de referência!');
			return false;
		}else{
			let botao = $('#btnBusca');
			let idOperadora = $('#selectoperadoras').val();
			botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');

			$.ajax({
				url: `<?= site_url('fatura/cad_fatura/listarFaturasMesReferencia') ?>`,
				type: "POST",
				dataType: 'json',
				data: {
					idOperadora: idOperadora,
					mesReferencia: mesReferencia
				},
				success: function(response) {
					if (response.status === 200) {
						tabelafaturas.clear();
						tabelafaturas.rows.add(response.results).draw();
					}else{
						if (response?.results?.mensagem) {
							tabelafaturas.clear().draw();
							alert('Não há faturas para o período especificado');
						}else{
							tabelafaturas.clear().draw();
							alert('Não há faturas para o período especificado');
						}
					}
				},
				error: function(response) {
					tabelafaturas.clear().draw();
					alert('Não há faturas para o período especificado');
				},

				complete: function() {
					botao.attr('disabled', false).html('<i class="fa fa-search"></i> Pesquisar');
				}
			});
		}
	});

	$('#BtnLimpar').click(function() {
            var table = $('#tabela_faturas').DataTable();
            table.clear().draw();
            $('#selectoperadoras').val(null).trigger('change');
			$('#selectMesReferencia').val(null).trigger('change');
			$('#anoReferencia').val('');
        });
	
	$("#abrirModalCadastrar").click(function() {
		$("#tab-dadosGerais").click();
		$("#modalCadFatura").modal("show");
	});

	$('#tab-dadosGerais').click(function (e){
		$('#dadosGerais').show();
		$('#itensDeFatura').hide();
	});

	$('#tab-itensDaFatura').click(function (e){
		$('#itensDeFatura').show();
		$('#dadosGerais').hide();
	});

	$(document).ready(async function(){
		var idOperadora = $('#cadSelectModalOperadoras').val();
		$("#numeroLinhaCad").empty()
    	$("#numeroLinhaCad").append(`<option value="0">Aguardando seleção da operadora...</option>`)
    	$("#numeroLinhaCad").select2({
    		width: '100%',
    		placeholder: "Aguardando seleção da operadora...",
    		allowClear: true
    	})

        $('#numeroLinhaCad').attr('disabled', true);

		$('#cadSelectModalOperadoras').change(async function (){
			$('#numeroLinhaCad').prepend('<option value="0" selected="selected" disabled>Buscando...</option>');
			$('#numeroLinhaCad').attr('disabled', true);

			$.ajax ({
				url: '<?= site_url('fatura/cad_fatura/listarLinhas') ?>',
				dataType: 'json',
				type: 'POST',  
				data: {idOperadora: $('#cadSelectModalOperadoras').val()},
				success: function(data){
						$('#numeroLinhaCad').empty();
						$('#numeroLinhaCad').prepend('<option value="0" selected="selected" disabled>Selecione a linha</option>');
						$('#numeroLinhaCad').select2({
							data: data,
							placeholder: "Selecione a linha",
							allowClear: true,
							language: "pt-BR",
						});
						$('#numeroLinhaCad').attr('disabled', false);
				},
				error: function(data){
					$('#numeroLinhaCad').empty();
					$('#numeroLinhaCad').prepend('<option value="0" selected="selected" disabled>Selecione a linha</option>');
					$('#numeroLinhaCad').select2({
						data: data,
						placeholder: "Selecione a linha",
						allowClear: true,
						language: "pt-BR",
					});
					alert('Não possuem linhas cadastradas para esta operadora');
					$('#numeroLinhaCad').attr('disabled', false);
				}
			})
			})
		})

	async function abrirAddItemFatura(botao, idFatura, idOperadora, numeroConta, operadora, event){
		event.preventDefault();
		let btn = $(botao);
		btn.html('<i class="fa fa-spinner fa-spin"></i>');
		btn.attr('disabled', true);

		$('#idFaturaInput').val(idFatura);
		$('.operadoraLabel').text(operadora);
		$('.codigoLabel').text(numeroConta);

		$('#selectLinha').empty();
		try{
			let linhas = await $.ajax ({
				url: '<?= site_url('fatura/cad_fatura/listarLinhas') ?>',
				dataType: 'json',
				type: 'POST',  
				data: {idOperadora: idOperadora},
				success: function(data){
					return data;
				}
			})
			$('#selectLinha').select2({
        		data: linhas,
        		placeholder: "Selecione a linha",
        		allowClear: true,
        		language: "pt-BR",
        	});

        	$('#selectLinha').on('select2:select', function (e) {
        	    var data = e.params.data;
        	});

        	$('#selectLinha').prepend('<option value="0" selected="selected" disabled>Selecione a linha</option>');
			$('#modalAddItemFatura').modal('show');

			btn.html('<i class="fa fa-plus"></i>');
			btn.attr('disabled', false);
		}catch(error){
			alert('Esta operadora não possui linhas cadastradas');
			btn.html('<i class="fa fa-plus"></i>');
			btn.attr('disabled', false);
		}
	}

	$(document).ready(async function (){

		let servicos = await $.ajax ({
			url: '<?= site_url('fatura/cad_fatura/listarServicos') ?>',
			dataType: 'json',
			type: 'GET',  
			success: function(data){
				return data;
			},
		})

		$('#selectServicoCad').select2({
			data: servicos,
			placeholder: "Selecione o serviço",
			allowClear: true,
			language: "pt-BR",
		});

		$('#selectServicoCad').on('select2:select', function (e) {
    		var data = e.params.data;
    	});

		$('#selectServico').select2({
			data: servicos,
			placeholder: "Selecione o serviço",
			allowClear: true,
			language: "pt-BR",
		});

		$('#selectServico').on('select2:select', function (e) {
    		var data = e.params.data;
    	});

		$('#selectServicoEditItem').select2({
			data: servicos,
			placeholder: "Selecione o serviço",
			allowClear: true,
			language: "pt-BR",
		});

		$('#selectServicoEditItem').on('select2:select', function (e) {
    		var data = e.params.data;
    	});
	});

	$('#adicionarItemTabela').click(function (){

		idServico = $('#selectServicoCad').val();
		nomeServico = $('#selectServicoCad option:selected').text();
		numeroLinha = $('#numeroLinhaCad option:selected').text();
		valor = formatValorInserir($('#valorItemCad').val());
		if (idServico == null || numeroLinha == null || valor == ''){
			alert('Preencha todos os campos');
		}else{
			tabelaItensCadastro.rows.add([
				{idServico: idServico, nomeServico: nomeServico, numeroLinha: numeroLinha, valor: valor}
			]).draw();
			
			$('#numeroLinhaCad option:selected').remove();
			$('#numeroLinhaCad').val(null).trigger('change');
			$('#valorItemCad').val('');
			$('#selectServicoCad').val(null).trigger('change');
		}
	})

	//Cadastrar Fatura 

	$("#btnCadastrarFatura").click(function(e) {
		e.preventDefault();
		
		botao = $(this);
		botao.html('<i class="fa fa-spinner fa-spin"></i>');
		botao.attr('disabled', true);

		var dataFim = $('#cadDataFim').val();
		var dataInicio = $('#cadDataInicio').val();
		var idOperadora = $('#cadSelectModalOperadoras').val();
		var mesReferencia = document.querySelector('#selectCadMesReferencia').value;
		var vencimento = $('#CadVencimento').val();
		var numeroConta = document.querySelector('#CadNumeroConta').value;

		if (idOperadora == null || dataFim == '' || dataInicio == '' || mesReferencia == '' || vencimento == '' || numeroConta == '') {
			alert('Preencha todos os campos');
			botao.html('Salvar');
			botao.attr('disabled', false);
		}else if (dataFim < dataInicio) {
			alert('Data final não pode ser menor que a data inicial');
			botao.html('Salvar');
			botao.attr('disabled', false);
		}else if (vencimento < dataFim) {
			alert('Data de vencimento não pode ser menor que a data final');
			botao.html('Salvar');
			botao.attr('disabled', false);
		}else{
			dataReferencia = '01/'+ mesReferencia +'/'+ dataInicio.substr(0,4);
			dataInicioInserir = transformardataparainserir(dataInicio);
			dataFimInserir = transformardataparainserir(dataFim);
			dataVencimentoInserir = transformardataparainserir(vencimento);
			dadosTabela = tabelaItensCadastro.rows().data().toArray();
			dadosTabelaInserir = dadosTabela.map(function (obj) {
			return {
				idServico: obj.idServico,
				numeroLinha: obj.numeroLinha,
				valor: obj.valor
			}
			})

			if (tabelaItensCadastro.rows().data().toArray().length > 0) {
				$.ajax({
					url: `<?= site_url('fatura/cad_fatura/cadastrarFaturaEItem') ?>`,
					type: "POST",
					dataType: "json",
					data: {
						idOperadora: idOperadora,
						mesReferencia: dataReferencia,
						dataInicio: dataInicioInserir,
						dataFim: dataFimInserir,
						vencimento: dataVencimentoInserir,
						numeroConta: numeroConta,
						itens: dadosTabelaInserir,
					},
					success: function(response){
						if (response.status == 201 || response.status == 200){
							alert(response.dados.mensagem);
							$('#selectCadMesReferencia').val('').trigger('change');
							$('#cadDataInicio').val('');
							$('#cadDataFim').val('');
							$('#CadVencimento').val('');
							$('#cadSelectModalOperadoras').val('').trigger('change');
							$('#CadNumeroConta').val('');
							tabelaItensCadastro.clear().draw();
							$("#modalCadFatura").modal("hide");
						}else if (response.status === 400 || response.status === 404) {
							alert(response.dados.mensagem ? response.dados.mensagem : 'Erro ao cadastrar fatura e itens. Verifique os campos e tente novamente.');
						}else{
							alert('Erro ao cadastrar fatura e itens. Tente novamente!');
						}
					},
					error: function(error){
						alert('Erro ao cadastrar fatura. Tente novamente!');
						botao.html('Salvar');
						botao.attr('disabled', false);
					},
					complete: function(){
						botao.html('Salvar');
						botao.attr('disabled', false);
					}
				});

			}else{
				$.ajax({
					url: `<?= site_url('fatura/cad_fatura/cadastrarFaturas') ?>`,
					type: "POST",
					dataType: "json",
					data: {
						idOperadora: idOperadora,
						mesReferencia: dataReferencia,
						dataInicio: dataInicioInserir,
						dataFim: dataFimInserir,
						vencimento: dataVencimentoInserir,
						numeroConta: numeroConta,
					},
					success: function(response) {
						if (response.status === 201 || response.status === 200) {
							alert(response.dados.mensagem);
							$("#modalCadFatura").modal("hide");
							$('#selectCadMesReferencia').val('').trigger('change');
							$('#cadDataInicio').val('');
							$('#cadDataFim').val('');
							$('#CadVencimento').val('');
							$('#cadSelectModalOperadoras').val('').trigger('change');
							$('#CadNumeroConta').val('');
						}else if (response.status === 400 || response.status === 404) {
							alert(response.dados.mensagem ? response.dados.mensagem : 'Erro ao cadastrar fatura. Verifique os campos e tente novamente.');
						}else{
							alert('Erro ao cadastrar fatura. Tente novamente!');
						}
					},
					error: function(err) {
						alert('Erro ao cadastrar fatura. Tente novamente!');
						botao.html('Salvar');
						botao.attr('disabled', false);
					},
					complete: function(){
						botao.html('Salvar');
						botao.attr('disabled', false);
					}
				});
			}
		}
	});

	function excluirItemTabela(botao, numeroLinha, event){
		$('#numeroLinhaCad').append(`<option value="${numeroLinha}">${numeroLinha}</option>`);
		tabelaItensCadastro.row(botao.parentNode.parentNode).remove().draw();
	}
	$('#tab-dadosGeraisEdit').click(function (e){
		$('#dadosGeraisEdit').show();
		$('#itensDeFaturaEdit').hide();
		$('#btnSalvarEditFatura').show();
	});
	$('#tab-itensDaFaturaEdit').click(function (e){
		$('#itensDeFaturaEdit').show();
		$('#dadosGeraisEdit').hide();
		$('#btnSalvarEditFatura').hide();

	});
	
	function abrirEditarFatura(botao, idFatura, idOperadora, mesReferencia, dataInicio, dataFim, vencimento, numeroConta, valorTotal, status, operadora, event){
		event.preventDefault();
		$("#editIdFatura").val(idFatura);
		mesReferencia = mesReferencia.split('/')[1].slice(0,2);
		$("#selectEditMesReferencia").val(mesReferencia);
		dataInicio = dataInicio.split('/').reverse().join('-');
		dataFim = dataFim.split('/').reverse().join('-');
		vencimento = vencimento.split('/').reverse().join('-');
		$("#editDataInicio").val(dataInicio)
		$("#editDataFim").val(dataFim);
		$("#editVencimento").val(vencimento);
		$("#editSelectOperadora").val(idOperadora).trigger("change");
		$("#editNumeroConta").val(numeroConta);
		$("#editValorTotal").val(valorTotal).trigger("change");
		if (status == "Ativo"){
			status = 1;
		}else{
			status = 0;
		}
		$("#idFaturaInput").val(idFatura);
		$("#editStatus").val(status).trigger("change");
		$("#tab-dadosGeraisEdit").click();
		
		$('.operadoraLabel').text(operadora);
		$('.codigoLabel').text(numeroConta);

		$.ajax({
            url: '<?= site_url('fatura/cad_fatura/listarItensFatura') ?>',
            type: 'POST',
            data: {idFatura: $("#editIdFatura").val()},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaItensDeFatura.clear().draw();
                    tabelaItensDeFatura.rows.add(data.results).draw();
                }else{
					tabelaItensDeFatura.clear().draw();
				}
            },
			error: function(err){
				alert('Erro ao listar itens da fatura. Tente novamente.');
				tabelaItensDeFatura.clear().draw();
			}
		});
		$("#modalEditFatura").modal("show");
	}

	$('#btnAbrirAddItemEdit').click(async function (e){
		btn = $(this);
		btn.html('<i class="fa fa-spinner fa-spin"></i>');
		btn.attr('disabled', true);
		$('#selectLinha').empty();
		try{
			let linhas = await $.ajax ({
				url: '<?= site_url('fatura/cad_fatura/listarLinhas') ?>',
				dataType: 'json',
				type: 'POST',  
				data: {idOperadora: $('#editSelectOperadora').val()},
				success: function(data){
					return data;
				},
			})

			$('#selectLinha').select2({
        		data: linhas,
        		placeholder: "Selecione a linha",
        		allowClear: true,
        		language: "pt-BR",
        	});

        	$('#selectLinha').on('select2:select', function (e) {
        	    var data = e.params.data;
        	});

        	$('#selectLinha').prepend('<option value="0" selected="selected" disabled>Selecione a linha</option>');
			$('#modalAddItemFatura').modal('show');

			btn.html('Adicionar Item');
			btn.attr('disabled', false);
		}catch(error){
			alert('Esta operadora não possui linhas cadastradas');
			btn.html('Adicionar Item');
			btn.attr('disabled', false);
		}
	});
	
	$("#btnSalvarEditFatura").click(function(e) {
		e.preventDefault();
		botao = $(this);
		botao.html('<i class="fa fa-spinner fa-spin"></i>');
		botao.attr('disabled', true);

		var idFatura = $('#editIdFatura').val();
		var dataInicio = $('#editDataInicio').val();
		var dataFim = $('#editDataFim').val();
		var idOperadora = $('#editSelectOperadora').val();
		var mesReferencia = document.querySelector('#selectEditMesReferencia').value;
		var dataReferencia = '01/'+ mesReferencia +'/'+ dataInicio.substr(0,4);
		var valor = formatValorInserir(document.querySelector('#editValorTotal').value);
		var vencimento = $('#editVencimento').val();
		var numeroConta = document.querySelector('#editNumeroConta').value;
		var status = $('#editStatus').val();

		if (idOperadora == null || dataFim == '' || dataInicio == '' || mesReferencia == '' || valor == '' || vencimento == '' || numeroConta == '') {
			alert('Verifique os dados e tente novamente.');
			botao.html('Salvar');
			botao.attr('disabled', false);
		}else if (dataFim < dataInicio) {
			alert('Data final não pode ser menor que a data inicial');
			botao.html('Salvar');
			botao.attr('disabled', false);
		}else if (vencimento < dataFim) {
			alert('Data de vencimento não pode ser menor que a data final');
			botao.html('Salvar');
			botao.attr('disabled', false);
		}else{
			var dataReferencia = '01/'+ mesReferencia +'/'+ dataInicio.substr(0,4);
			var dataInicioInserir = transformardataparainserir(dataInicio);
			var dataFimInserir = transformardataparainserir(dataFim);
			var dataVencimentoInserir = transformardataparainserir(vencimento);

			$.ajax({
				url: `<?= site_url('fatura/cad_fatura/editarFaturas') ?>`,
				type: "POST",
				dataType: "json",
				data: {
					idFatura: idFatura,
					idOperadora: idOperadora,
					mesReferencia: dataReferencia,
					dataInicio: dataInicioInserir,
					dataFim: dataFimInserir,
					vencimento: dataVencimentoInserir,
					numeroConta: numeroConta,
					valor: valor,
					status: status
				},
				success: function(response) {
					if (response.status === 200) {
						alert(response.dados.mensagem);
						$("#modalEditFatura").modal("hide");
						$('#selectEditMesReferencia').val('').trigger('change');
						$('#editDataInicio').val('');
						$('#editDataFim').val('');
						$('#editVencimento').val('');
						$('#editSelectOperadora').val('').trigger('change');
						$('#editNumeroConta').val('');
						$('#editValorTotal').val('');
						botao.html('Salvar');
						botao.attr('disabled', false);
						if (idOperadora == $('#selectoperadoras').val()) {
							$('#btnBusca').click();
						}
					}else if (response.status === 400 || response.status === 404) {
						alert(response.dados.mensagem ? response.dados.mensagem : 'Erro ao editar fatura. Verifique os campos e tente novamente.');
					}else{
						alert('Erro ao editar fatura. Tente novamente!');
					}
				},
				error: function(err) {
					alert('Erro ao editar fatura, tente novamente.');
					botao.html('Salvar');
					botao.attr('disabled', false);
				},
				complete: function(){
					botao.html('Salvar');
					botao.attr('disabled', false);
				}
			});
		}
	});

	function alterarStatusItemFatura(botao, id, status, idFatura, event){
		event.preventDefault()
		if(confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')){
            if(status == 'Ativo'){
                status = 0
            }else{
                status = 1
            }
            $.ajax({
                url: '<?= site_url('fatura/cad_fatura/alterarStatusItemFatura') ?>',
                type: 'POST',
                data: { idItem: id,
                        status: status
                        },
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $.ajax({
                            url: '<?= site_url('fatura/cad_fatura/listarItensFatura') ?>',
                            dataType: 'json',
                            type: 'POST',
                            data: {idFatura: idFatura},
                            success: function(data){
								if (data.status === 200){
                                	tabelaItensDeFatura.clear().draw();
                                	tabelaItensDeFatura.rows.add(data.results).draw();
								}else{
                                	tabelaItensDeFatura.clear().draw();
								}
                            },
							error: function(err){
								alert('Erro ao listar itens da fatura. Tente novamente.');
								tabelaItensDeFatura.clear().draw();
							}
                        })
                    }else{
                        alert(data.dados.mensagem ? data.dados.mensagem : 'Erro ao alterar status do item. Tente novamente.')
                    }
                },
				error: function(err){
					alert('Erro ao alterar status do item. Tente novamente.')
				}
            })
        }else{
            return false;
        }
	}

	async function abrirEditarItemFatura(botao, id, idFatura, idServico, numeroLinha, valor, status, event){
		event.preventDefault();
		btn = $(botao);
		btn.html('<i class="fa fa-spinner fa-spin"></i>');
		btn.attr('disabled', true);
		$('#selectServicoEditItem').val(idServico).trigger('change');
		$('#selectNumeroLinhaEditItem').empty();
		$('#valorItemEditItem').val(valor);
		if (status == 'Ativo') {
			$('#statusEditItem').val('1').trigger('change');
		}else{
			$('#statusEditItem').val('0').trigger('change');
		}

			let linhas = await $.ajax ({
        	                    url: '<?= site_url('fatura/cad_fatura/listarLinhas') ?>',
        	                    dataType: 'json',
        	                    type: 'POST',  
        	                    data: {idOperadora: $('#editSelectOperadora').val()},
								success: function(data){
									return data;
								},
        	                })

			$('#selectNumeroLinhaEditItem').select2({
        		data: linhas,
        		placeholder: "Selecione a linha",
        		allowClear: true,
        		language: "pt-BR",
        	});

        	$('#selectNumeroLinhaEditItem').on('select2:select', function (e) {
        	    var data = e.params.data;
        	});

			$('#selectNumeroLinhaEditItem option').filter(function() {
			   return $(this).text().indexOf(numeroLinha) !== -1;
			}).prop('selected', true).change();

			btn.html('<i class="fa fa-pencil"></i>');
			btn.attr('disabled', false);
		
		$('#idItemEditItem').val(id);
		$('#idFaturaEditItem').val(idFatura);
		$('#modalEditItemFatura').modal('show');
	}

	$('#btnSalvarItemFatura').click(function (){
		var botao = $(this);

		idFatura = $('#idFaturaInput').val();
		idServico = $('#selectServico').val();
		numeroLinha = $('#selectLinha option:selected').text();
		valor = formatValorInserir($('#valorItem').val());

		if (numeroLinha == null || valor == '' || idServico == null) {
			alert('Preencha todos os campos');
		}else{
			botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
			botao.attr('disabled', true);
			$.ajax({
				url: '<?= site_url('fatura/cad_fatura/cadastrarItemFatura') ?>',
                dataType: 'json',
                type: 'POST',
                data: {	idFatura: idFatura,
						idServico: idServico,
						numeroLinha: numeroLinha,
						valor: valor
						},
                success: function(data){
                    if (data.status == 200 || data.status == 201) {
						if(data.dados?.mensagem){
							alert(data.dados.mensagem);
						}else{
							alert('Cadastrado com sucesso');
						}
						$('#selectServico').val('').trigger('change');
						$('#selectLinha').val('').trigger('change');
						$('#valorItem').val('');
						$('#modalAddItemFatura').modal('hide');
						if ($('#modalEditFatura').css('display') == 'block') {
							$.ajax({
                            	url: '<?= site_url('fatura/cad_fatura/listarItensFatura') ?>',
                            	dataType: 'json',
                            	type: 'POST',
                            	data: {idFatura: idFatura},
                            	success: function(data){
                            	    if (data.status === 200){
                            	    	tabelaItensDeFatura.clear().draw();
                            	    	tabelaItensDeFatura.rows.add(data.results).draw();
									}else{
                            	    	tabelaItensDeFatura.clear().draw();
									}
                            	},
								error: function(err){
								    alert('Erro ao listar itens da fatura. Tente novamente.');
								    tabelaItensDeFatura.clear().draw();
								}
                        	})
						}else{
							return false;
						}
                    } else if (data.status === 400 || data.status === 404) {
						alert(data.dados && data.dados.mensagem ? data.dados.mensagem : 'Erro ao cadastrar item de fatura. Verifique os campos e tente novamente.');
					}else{
						alert('Erro ao cadastrar item de fatura. Tente novamente!');
					}

                },
				error: function(err){
					alert('Erro ao cadastrar item de fatura, tente novamente.');
					botao.html('Salvar');
					botao.attr('disabled', false);
				},
				complete: function(){
					botao.html('Salvar');
					botao.attr('disabled', false);
				}
            })
		}
	})

	$('#btnSalvarEditItem').click(function (){
		var botao = $(this);
		botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
		botao.attr('disabled', true);
		idItem = $('#idItemEditItem').val();
		idFatura = $('#idFaturaEditItem').val();
		idServico = $('#selectServicoEditItem').val();
		numeroLinha = $('#selectNumeroLinhaEditItem option:selected').text();
		valor = formatValorInserir($('#valorItemEditItem').val());
		status = $('#statusEditItem').val();

		if (/* idServico == null ||  */numeroLinha == null || valor == '' || status == '') {
			alert('Preencha todos os campos');
			botao.html('Salvar');
			botao.attr('disabled', false);
		}else{
			$.ajax({
				url: '<?= site_url('fatura/cad_fatura/editarItemFatura') ?>',
				dataType: 'json',
				type: 'POST',
				data: {	idItem: idItem,
						idFatura: idFatura,
						idServico: idServico,
						numeroLinha: numeroLinha,
						valor: valor,
						status: status
						},
				success: function(data){
					if (data.status == 200) {
						if(data.dados?.mensagem){
							alert(data.dados.mensagem);
						}else{
							alert('Cadastrado com sucesso');
						}
						$('#idItemEditItem').val('');
						$('#idFaturaEditItem').val('');
						$('#selectServicoEditItem').val('').trigger('change');
						$('#selectNumeroLinhaEditItem').val('').trigger('change');
						$('#valorItemEditItem').val('');
						$('#modalEditItemFatura').modal('hide');
						botao.html('Salvar');
						botao.attr('disabled', false);
						$.ajax({
                            url: '<?= site_url('fatura/cad_fatura/listarItensFatura') ?>',
                            dataType: 'json',
                            type: 'POST',
                            data: {idFatura: idFatura},
                            success: function(data){
                                if (data.status === 200){
                                	tabelaItensDeFatura.clear().draw();
                                	tabelaItensDeFatura.rows.add(data.results).draw();
								}else{
                                	tabelaItensDeFatura.clear().draw();
								}
                            },
							error: function(err){
								alert('Erro ao listar itens da fatura. Tente novamente.');
								tabelaItensDeFatura.clear().draw();
							}
						})
					}else if (data.status === 400 || data.status === 404) {
						alert(data.dados && data.dados.mensagem ? data.dados.mensagem : 'Erro ao editar item de fatura. Verifique os campos e tente novamente.');
					}else{
						alert('Erro ao editar item de fatura. Tente novamente!');
					}

				},
				error: function(err){
					alert('Erro ao editar item de fatura, tente novamente.');
					botao.html('Salvar');
					botao.attr('disabled', false);
				},
				complete: function(){
					botao.html('Salvar');
					botao.attr('disabled', false);
				}
			})
		}

	});

	function transformardataparainserir(localData) {
		var divisor = "-";
		var partesData = localData.split(divisor);
		var novaData = partesData[2] + "/" + partesData[1] + "/" + partesData[0];
		return novaData;
	}

	function formatValorInserir(value) {
		value = value.replace('.', '');
		value = value.replace(',', '.');

		return value;
		
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

	function validarAno(input) {
  		input.value = input.value.replace(/[^0-9]/g, '');

  		if (!/^\d+$/.test(input.value)) {
  		  input.value = '';
  		  return;
  		}	
	}

	$('#selectMesReferencia').change(function (){
		if ($('#selectMesReferencia').val() != "") {
			$('#divAnoReferencia').show();
		}else{
			$('#divAnoReferencia').hide();

		}
	});

    $(document).on('hidden.bs.modal', function (event) {
        if($('.modal:visible').length){
            $('body').addClass('modal-open')
        }
    });

</script>