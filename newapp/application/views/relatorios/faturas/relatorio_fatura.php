<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<!-- SELECT2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<!-- Traduções -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/pt-BR.js"></script>
<!---------------->

<style type="text/css">
	.select2-container--default .select2-selection--multiple .select2-selection__choice {
		border: none !important;
		margin-top: 8px !important;
		margin-bottom: 5px !important;
	}

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

	.dataTables_wrapper .dataTables_processing div {
		display: inline-block;
		vertical-align: center;
		font-size: 68px;
		height: 100%;
		top: 0;
	}

	th,
	td.wordWrap {
		max-width: 100px;
		word-wrap: break-word;
		text-align: center;
	}

	.checkbox label {
		font-weight: 700;
	}

	.select-container .select-selection--single {
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

	.select-selection--multiple .select-search__field {
		width: 100% !important;
	}

	.bold-text {
		font-weight: bold;
	}
</style>

<h3>Relatório Faturas</h3>

<div class="div-caminho-menus-pais">
	<a href="<?= site_url('Homes') ?>">Home</a> >
	<?= lang('relatorios') ?> >
	<?= lang('financeiro') ?></a> >
	<?= lang('faturas') ?>
</div>

<hr>

<button class="btn btn-info" data-toggle="collapse" data-target="#demo" id="col"><i class="fa fa-cogs"></i> Parâmetros</button>

<div class="container-fluid my-1">
	<div class="col-sm-12">
		<br>
		<br>
		<div id="demo" class="collapse">
			<form action="#" id="formRel" method="POST" accept-charset="utf-8">
				<div class="alert alert-info col-md-12" style="margin-bottom: 50px;">

					<div class="row">
						<div class="form-group col-md-12">
							<strong>Selecione o(s) cliente(s):</strong>
							<select class="js-example-basic-multiple" name="cliente[]" multiple="multiple" autocomplete="off" style="width: 100%;">
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-2">
							<strong>Data Início:</strong>
							<input type="date" name="dt_ini" class="form-control" autocomplete="off" id="dp1" value="<?php echo $this->input->post('dt_ini') ?>" required />
						</div>
						<div class="form-group col-md-2">
							<strong>Data Fim:</strong>
							<input type="date" name="dt_fim" class="form-control" autocomplete="off" id="dp2" value="<?php echo $this->input->post('dt_fim') ?>" required />
						</div>
						<div class="form-group col-md-3">
							<strong>Vendedor:</strong>
							<select id="vendedor" name="vendedor" class="form-control" style="width: 100%;">
								<option value="todos" <?= $this->input->post() ? ($_POST['vendedor'] == 'todos' ? 'selected' : '') : '' ?>>Nenhum(a)</option>
								<?php foreach ($vendedores as $vend) : ?>
									<option value="<?= $vend->id ?>" <?= $this->input->post() ? ($_POST['vendedor'] == $vend->id ? 'selected' : '') : '' ?>><?= strtoupper($vend->nome) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group col-md-2">
							<strong>Empresa:</strong>
							<select id="emp" name="emp" class="form-control" style="width: 100%;">
								<option value="todos">Todas</option>
								<option value="TRACKER">SHOW TECNOLOGIA</option>
								<option value="NORIO">SIGA-ME RASTREAMENTO</option>
								<option value="SIMM2M">SIMM2M</option>
								<option value="EUA">SHOW TECHNOLOGY EUA</option>
								<option value="SHOW_CURITIBA"> SHOW CURITIBA </option>
							</select>
						</div>
						<div class="form-group col-md-1">
							<strong>UF:</strong>
							<select id="uf" name="uf" style="width: 100%;" class="form-control">
								<option value="">UF</option>
								<option value="AC-BR">AC - BR</option>
								<option value="AL-BR">AL - BR</option>
								<option value="AP-BR">AP - BR</option>
								<option value="AM-BR">AM - BR</option>
								<option value="BA-BR">BA - BR</option>
								<option value="CE-BR">CE - BR</option>
								<option value="DF-BR">DF - BR</option>
								<option value="ES-BR">ES - BR</option>
								<option value="GO-BR">GO - BR</option>
								<option value="MA-BR">MA - BR</option>
								<option value="MT-BR">MT - BR</option>
								<option value="MS-BR">MS - BR</option>
								<option value="MG-BR">MG - BR</option>
								<option value="PA-BR">PA - BR</option>
								<option value="PB-BR">PB - BR</option>
								<option value="PR-BR">PR - BR</option>
								<option value="PE-BR">PE - BR</option>
								<option value="PI-BR">PI - BR</option>
								<option value="RJ-BR">RJ - BR</option>
								<option value="RN-BR">RN - BR</option>
								<option value="RS-BR">RS - BR</option>
								<option value="RO-BR">RO - BR</option>
								<option value="RR-BR">RR - BR</option>
								<option value="SC-BR">SC - BR</option>
								<option value="SP-BR">SP - BR</option>
								<option value="SE-BR">SE - BR</option>
								<option value="TO-BR">TO - BR</option>
								<option value="AK-EUA">AK - EUA</option>
								<option value="AL-EUA">AL - EUA</option>
								<option value="AR-EUA">AR - EUA</option>
								<option value="AZ-EUA">AZ - EUA</option>
								<option value="CA-EUA">CA - EUA</option>
								<option value="CO-EUA">CO - EUA</option>
								<option value="CT-EUA">CT - EUA</option>
								<option value="DE-EUA">DE - EUA</option>
								<option value="FL-EUA">FL - EUA</option>
								<option value="GA-EUA">GA - EUA</option>
								<option value="HI-EUA">HI - EUA</option>
								<option value="IA-EUA">IA - EUA</option>
								<option value="ID-EUA">ID - EUA</option>
								<option value="IL-EUA">IL - EUA</option>
								<option value="IN-EUA">IN - EUA</option>
								<option value="KS-EUA">KS - EUA</option>
								<option value="KY-EUA">KY - EUA</option>
								<option value="LA-EUA">LA - EUA</option>
								<option value="MA-EUA">MA - EUA</option>
								<option value="MD-EUA">MD - EUA</option>
								<option value="ME-EUA">ME - EUA</option>
								<option value="MI-EUA">MI - EUA</option>
								<option value="MN-EUA">MN - EUA</option>
								<option value="MO-EUA">MO - EUA</option>
								<option value="MS-EUA">MS - EUA</option>
								<option value="MT-EUA">MT - EUA</option>
								<option value="NC-EUA">NC - EUA</option>
								<option value="ND-EUA">ND - EUA</option>
								<option value="NE-EUA">NE - EUA</option>
								<option value="NH-EUA">NH - EUA</option>
								<option value="NJ-EUA">NJ - EUA</option>
								<option value="NM-EUA">NM - EUA</option>
								<option value="NV-EUA">NV - EUA</option>
								<option value="NY-EUA">NY - EUA</option>
								<option value="OH-EUA">OH - EUA</option>
								<option value="OK-EUA">OK - EUA</option>
								<option value="OR-EUA">OR - EUA</option>
								<option value="PA-EUA">PA - EUA</option>
								<option value="RI-EUA">RI - EUA</option>
								<option value="SC-EUA">SC - EUA</option>
								<option value="SD-EUA">SD - EUA</option>
								<option value="TN-EUA">TN - EUA</option>
								<option value="TX-EUA">TX - EUA</option>
								<option value="UT-EUA">UT - EUA</option>
								<option value="VA-EUA">VA - EUA</option>
								<option value="VT-EUA">VT - EUA</option>
								<option value="WI-EUA">WI - EUA</option>
								<option value="WA-EUA">WA - EUA</option>
								<option value="WI-EUA">WI - EUA</option>
								<option value="WV-EUA">WV - EUA</option>
								<option value="WY-EUA">WY - EUA</option>
							</select>
						</div>
						<div class="form-group col-md-2">
							<strong>Órgão:</strong>
							<select name="orgao" id="orgao" style="width: 100%;" class="form-control">
								<option value="">Orgão</option>
								<option value="privado">Privado</option>
								<option value="publico">Público</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-2">
							<strong>Tipo de Atividade</strong>
							<select name="tipoAtividade" id="tipoAtividade" style="width: 100%;" class="form-control">
								<option value="" selected disabled>Tipo de Atividade</option>
								<option value="">Todos</option>
								<option value="1">Atividade de Monitoramento</option>
								<option value="2">Serviços Técnicos</option>
								<option value="3">Aluguel de Outras Máquinas e Equipamentos</option>
								<option value="4">Suporte técnico, manutenção e outros serviços em tecnologia da informação</option>
								<option value="5">Desenvolvimento e licenciamento de programas de computador customizáveis</option>
								<option value="6">Serviços combinados de escritório e apoio administrativo</option>
								<option value="0">Outros</option>
							</select>
						</div>
						<div class="form-group col-md-2">
							<strong>Parcerio/Cliente:</strong>
							<select name="informacoes" id="informacoes" style="width: 100%;" class="form-control">
								<option value="all" selected>Todos</option>
								<option value="cliente">Clientes</option>
								<option value="parceiro">Parceiros</option>
							</select>
						</div>
						<div class="form-group col-md-10" style="margin-top: 25px;">
							<strong>Tipo de órgão:</strong>
							<input type="radio" name="tipo_pessoa[]" value="all"> Todos
							<input type="radio" name="tipo_pessoa[]" value="pessoaFisica"> Físico
							<input type="radio" name="tipo_pessoa[]" value="pessoaJuridica"> Jurídico

							<strong style="margin-left: 20px;"> Status:</strong>
							<input type="checkbox" value="0" name="status_fatura[]"> Pendente
							<input type="checkbox" value="1" name="status_fatura[]"> Pago
							<input type="checkbox" value="3" name="status_fatura[]"> Cancelado


							<strong style="margin-left: 20px;">Agrupar por Cliente:</strong>
							<input type="checkbox" value="1" name="agrupar" title="Agrupar por cliente">
						</div>
					</div>
					<div class="row">
						<div class="pull-right" style="margin-right: 15px;">
							<button type="submit" class="btn btn-primary gerar_rel">
								Gerar
							</button>
						</div>
					</div>
				</div>
			</form>
			<div class="clearfix"></div>
		</div>

		<div class="legend" style="margin-bottom: 10px !important;">
			<h5 class="bold-text">Total Faturado: <span id="valor_total">R$ 0,00</span> | Total Taxa(s): <span id="valor_taxa">R$ 0,00</span> | Total Pago: <span id="valor_pago">R$ 0,00</span> | Valor Líquido: <span id="valor_liquido">R$ 0,00</span></h5>
		</div>

		<table id="table" class="table display table-bordered table-hover" style="width: 100%;">
			<thead>
				<th class="span1">Cód Fatura</th>
				<th class="span4">Cliente</th>
				<th class="span3">CNPJ/CPF</th>
				<th class="span2">Status do Cli.</th>
				<th class="span2">Tipo do Cli.</th>
				<th class="span4">Prestadora</th>
				<th class="span2">Data de Venc.</th>
				<th class="span2">Data de Emissão</th>
				<th class="span2">Valor Fatura</th>
				<th class="span2">Taxas e Juros</th>
				<th class="span2">Nº Nota Fiscal</th>
				<th class="span2">Mês de Referência</th>
				<th class="span2">Inicío do P.</th>
				<th class="span2">Fim do P.</th>
				<th class="span2">Data Pag.</th>
				<th class="span2">Valor Pago.</th>
				<th class="span2">Forma Pag.</th>
				<th class="span2">Fim do Contrato</th>
				<th class="span2">Ger.</th>
				<th class="span2">Status</th>
				<th class="span2">Atividade de Serviço</th>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
			</tfoot>
		</table>
	</div>
</div>
<script type="text/javascript">
	jQuery(function($) {
		var table;

		table = $('#table').DataTable({
			dom: 'Bfrtip',
			responsive: true,
			language: lang.datatable,
			columnDefs: [{
				render: $.fn.dataTable.render.number('.', ',', 2),
				targets: [4, 5, 8, 15]
			}],
			// columnsDefs: [
			// 	{ type: 'currency', targets: [4, 5, 11] }
			// ],
			fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
				if (aData[15] == '0,00') {
					$('td', nRow).css('background-color', '#FFCDD2'); // Pagamento não realizado
				} else if (aData[15] != '0,00') {
					$('td', nRow).css('background-color', '#ceffce'); // Pagamento realizado
				}
			},
			buttons: [{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					className: 'btn btn-primary',
					text: '<i class="fa fa-file-pdf"></i> PDF',
					customize: function(doc) {
						const titulo = 'Valor Total: R$ ' + $('#valor_total').text() + ' | Valor Taxa(s): ' + $('#valor_taxa').text() + ' | Valor Pago: ' + $('#valor_pago').text() + ' | Valor Liquido: ' + $('#valor_liquido').text() + ' | Período: ' + $('input[name=dt_ini]').val() + ' à ' + $('input[name=dt_fim]').val();

						pdfTemplate(doc, titulo, 'A4');
					}
				},
				{
					extend: 'excelHtml5',
					messageTop: function() {
						return 'Valor Total: R$ ' + $('#valor_total').text() + ' | Valor Taxa(s): ' + $('#valor_taxa').text() + ' | Valor Pago: ' + $('#valor_pago').text() + ' | Valor Liquido: ' + $('#valor_liquido').text() + ' | Período: ' + $('input[name=dt_ini]').val() + ' à ' + $('input[name=dt_fim]').val()
					},
					orientation: customPageExport('table', 'orientation'),
					pageSize: 'A2',
					className: 'btn btn-primary',
					text: '<i class="fa fa-file-excel"></i> EXCEL',
					exportOptions: {
						format: {
							body: function(data, row, column, node) {
								if (column === 8 || column === 15) {
									if (data)
										return data.replace('.', ';').replace(',', '.').replace(';', ','); //deixa no formato 1,254.21
								}
								return data;
							}
						}
					}
				},
				{
					extend: 'csvHtml5',
					className: 'btn btn-primary',
					text: '<i class="fa fa-file-csv"></i> CSV',
					messageTop: function() {
						return 'Valor Total: R$ ' + $('#valor_total').text() + ' | Valor Taxa(s): ' + $('#valor_taxa').text() + ' | Valor Pago: ' + $('#valor_pago').text() + ' | Valor Liquido: ' + $('#valor_liquido').text() + ' | Período: ' + $('input[name=dt_ini]').val() + ' à ' + $('input[name=dt_fim]').val()
					},
					orientation: customPageExport('table', 'orientation'),
					pageSize: 'A2'
				}
			]
		});

		$('.js-example-basic-multiple').select2({
			ajax: {
				url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
				dataType: 'json',
			},
			language: "pt-BR"
		});

		$('select[name=vendedor]').select2({
			language: "pt-BR",
		});
		$('select[name="vendedor"]').next('.select2-container').find('.select2-selection').css('height', '33px');

		var dataInicio = '';
		var dataFim = '';
		$('form#formRel').submit(function(event) {
			dataInicio = ($('input[name=dt_ini]').val()).split('-').reverse().join('/');
			dataFim = ($('input[name=dt_fim]').val()).split('-').reverse().join('/');

			if (table) {
				table.destroy();
			}

			table = $('#table').DataTable({
				dom: 'Bfrtip',
				responsive: true,
				language: {
					loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
					searchPlaceholder: 'Pesquisar',
					emptyTable: "Nenhum registro encontrado",
					info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
					infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
					zeroRecords: "Nenhum registro encontrado.",
					paginate: {
						first: "Primeira",
						last: "Última",
						next: "Próxima",
						previous: "Anterior"
					},
				},
				columnDefs: [{
					render: $.fn.dataTable.render.number('.', ',', 2),
					targets: [4, 5, 8, 15]
				}],
				// columnsDefs: [
				// 	{ type: 'currency', targets: [4, 5, 11] }
				// ],
				fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
					if (aData[15] == '0,00') {
						$('td', nRow).css('background-color', '#FFCDD2'); // Pagamento não realizado
					} else if (aData[15] != '0,00') {
						$('td', nRow).css('background-color', '#ceffce'); // Pagamento realizado
					}
				},
				buttons: [{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A2',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-pdf"></i> PDF',
						customize: function(doc) {
							const titulo = 'Valor Total: R$ ' + $('#valor_total').text() + ' | Valor Taxa(s): ' + $('#valor_taxa').text() + ' \n Valor Pago: ' + $('#valor_pago').text() + ' | Valor Liquido: ' + $('#valor_liquido').text() + ' \n Período: ' + dataInicio + ' à ' + dataFim;

							pdfTemplate(doc, titulo, 'A2', [40, '*', '*', 50, 50, '*', 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, '*'], ' ');
							doc.styles.tableBodyEven.alignment = 'left';
							doc.styles.tableBodyOdd.alignment = 'left';

						}
					},
					{
						extend: 'excelHtml5',
						messageTop: function() {
							return 'Valor Total: R$ ' + $('#valor_total').text() + ' | Valor Taxa(s): ' + $('#valor_taxa').text() + ' | Valor Pago: ' + $('#valor_pago').text() + ' | Valor Liquido: ' + $('#valor_liquido').text() + ' | Período: ' + dataInicio + ' à ' + dataFim
						},
						orientation: customPageExport('table', 'orientation'),
						pageSize: 'A2',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-excel"></i> EXCEL',
						exportOptions: {
							format: {
								body: function(data, row, column, node) {
									if (column === 8 || column === 15) {
										if (data)
											return data.replace('.', ';').replace(',', '.').replace(';', ','); //deixa no formato 1,254.21
									}
									return data;
								}
							}
						}
					},
					{
						extend: 'csvHtml5',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-csv"></i> CSV',
						messageTop: function() {
							return 'Valor Total: R$ ' + $('#valor_total').text() + ' | Valor Taxa(s): ' + $('#valor_taxa').text() + ' | Valor Pago: ' + $('#valor_pago').text() + ' | Valor Liquido: ' + $('#valor_liquido').text() + ' | Período: ' + dataInicio + ' à ' + dataFim
						},
						orientation: customPageExport('table', 'orientation'),
						pageSize: 'A2'
					},
				]
			});
			// Cancela redirecionamento do FORM
			event.preventDefault();

			let orgao = $('#orgao').val();
			let vendedor = $('#vendedor').val();
			let uf = $('#uf').val();
			let prestadora = $('#emp').val();

			if (vendedor === 'todos' && prestadora == 'todos' && orgao == '' && uf == '') {
				table.clear().draw();
				alert('Não é permitido gerar esse relatório sem nenhum filtro. (Orgão, Vendedor, Prestadora ou Estado');

			} else {
				// Inativa button submit do form
				$('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');

				let data_ini = $('#dp1').val();
				data_ini = data_ini.split('-').reverse().join('/');
				let dataFim = $('#dp2').val();
				dataFim = dataFim.split('-').reverse().join('/');

				let total_fatura = 0.0;
				let total_taxa = 0.0;
				let total_pago = 0.0;
				let total_liquido = 0.0;

				table.clear();
				var filter = $(this).serialize() + '&dataInicio=' + data_ini + '&dataFim=' + dataFim;
				$.ajax({
					url: '<?= site_url('relatorios/ajaxRelFaturas') ?>',
					type: 'POST',
					dataType: 'json',
					data: filter,
					success: function(callback) {
						console.log(callback)


						if (callback.status == 'OK') {
							table.rows.add(callback.tbody);
							table.columns.adjust().draw();
							table.columns.adjust().responsive.recalc();

							// Atualiza valores totais
							total_fatura += parseFloat(callback.tfooter.total_fatura);
							total_taxa += parseFloat(callback.tfooter.total_taxa);
							total_pago += parseFloat(callback.tfooter.total_pago);
							total_liquido += parseFloat(callback.tfooter.total_liquido);

							// Atualiza valores totais
							$('#valor_total').text('R$ ' + total_fatura.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
							$('#valor_taxa').text('R$ ' + total_taxa.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
							$('#valor_pago').text('R$ ' + total_pago.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
							$('#valor_liquido').text('R$ ' + total_liquido.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
						} else {
							alert(callback.msg);
							table.clear().draw();
							return;
						}
					},
					error: function() {
						alert('Não foi possível gerar o relatório. Tente novamente mais tarde!');
					},
					complete: function() {
						$('.gerar_rel').removeAttr('disabled').html('Gerar');
					}
				});
			}
		});
	});
</script>