let tableContasBancarias,
	linhaTabela,
	pageTable,
	indexTable,
	linhaTabelaContas,
	pageTableContas,
	indexTableContas;
let id_func = false;

$(document).ready(function () {
	//INICIALIZA SELECT2 MODAL DEMISSÃO
	$('#tipoDemissao').select2({
		allowClear: true,
		placeholder: 'Selecione uma opção'
	})

	$('#recontratarFuturamente').select2({
		allowClear: true,
		placeholder: 'Selecione uma opção'
	})

	$("#Modal-Demissao").on("hide.bs.modal", function () {
		$("#tipoDemissao").val(null).trigger('change');
		$("#dataDemissao").val("");
		$("#motivoDemissao").val("");
		$("#demitirnome").text('');
		$("#recontratarFuturamente").val(null).trigger('change');
		$("#data-id").val("");
	});

	$(".btn-expandir").on("click", function (e) {
		e.preventDefault();
		expandirGrid();
	});

	var searchOptions = {
		name: null,
		email: null,
	};

	atualizarAgGridFuncionarios();
	funcionariosBusca();

	function funcionariosBusca() {
		const select = $("#funcionarioBusca");
		select.select2({
			ajax: {
				url: getFuncionariosByName, // Substitua pela URL real do seu endpoint
				type: "POST",
				dataType: "json",
				delay: 250,
				data: function (params) {
					return {
						nome: params.term || "",
					};
				},
				processResults: function (data, params) {
					return {
						results: data.map((row) => ({
							id: row.nome,
							text: row.nome,
						})),

					};
				},
			},
			placeholder: "Selecione um funcionário",
			allowClear: true,
			minimumInputLength: 3,
			width: "100%",
			language: {
				inputTooShort: function (args) {
					var remainingChars = args.minimum - args.input.length;
					return "Insira " + remainingChars + " ou mais caracteres";
				},
				searching: function () {
					return "Carregando...";
				},
			},
		});
	}

	$("#BtnPesquisar").on("click", async (e) => {
		e.preventDefault();

		if (!$("#funcionarioBusca").val() && !$("#emailBusca").val()) {
			showAlert("warning", "Insira algum valor para fazer a busca!");
			return;
		}

		if ($("#funcionarioBusca").val()) {
			searchOptions.nome = $("#funcionarioBusca").val();
		} else {
			searchOptions.nome = "";
		}

		if ($("#emailBusca").val()) {
			if ($("#emailBusca").val().indexOf(" ") >= 0) {
				showAlert("warning", 
					"O campo email deve ser preenchido apenas com letras e números."
				);
				return;
			}

			searchOptions.email = $("#emailBusca").val();
		}

		showLoadingPesquisarButton();
		await atualizarAgGridFuncionarios(searchOptions);
	});

	$("#BtnLimparFiltro").on("click", async (e) => {
		e.preventDefault();

		let btn = $(this);

		$("#formBusca")[0].reset();
		$("#funcionarioBusca").html(
			'<option value="" disabled selected>Selecione um funcionário</option>'
		);

		searchOptions = {
			nome: null,
			email: null,
		};
		showLoadingLimparPesquisarButton();
		await atualizarAgGridFuncionarios(searchOptions);
	});

	//INICIALIZA A TABELA DE CONTAS BANCARIAS
	tableContasBancarias = $("#tableContasBancarias").DataTable({
		paging: true,
		responsive: true,
		order: [0, "desc"],
		columnDefs: [
			{
				className: "dt-center",
				targets: "_all",
			},
			{
				type: "date-uk",
				targets: 8,
			},
		],
		columns: [
			{
				data: "id",
			},
			{
				data: "titular",
			},
			{
				data: "cpf",
			},
			{
				data: "banco",
			},
			{
				data: "agencia",
			},
			{
				data: "conta",
			},
			{
				data: "operacao",
			},
			{
				data: "tipo",
			},
			{
				data: "data_cadastro",
			},
			{
				data: "status",
			},
			{
				data: "admin",
			},
		],
		language: langDatatable,
	});

	//CARREGA AS OPTIONS DO SELECT DE CARGO
	$.ajax({
		url: listCargos,
		dataType: "json",
		success: function (data) {
			if (data != null) {
				var cargos = data.cargos;
				var selectbox = $("#cargo");
				$.each(cargos, function (i, d) {
					$("<option>")
						.val(d.id)
						.text(d.descricao)
						.appendTo(selectbox);
				});
			}
		},
	});

	$("#modalEnviarDocumentoFuncionario").on('hide.bs.modal', function () {
		$(".titleDocumento").text('Adicionar Documento');
		$("label[for='tituloDoc']").html('Título do Documento <span class="text-danger">*</span>');
		$("label[for='arquivoItens']").html('Anexo do Documento <span class="text-danger">*</span>');
		$("#tituloDoc").val('');
		$("#arquivoItens").val(null);
	})
});

$(document).on("click", ".addFerias", function () {
	$("#divAddFerias").css("display", "block");
	$("#divRemoveFerias").css("display", "none");
	$("#data_saida_ferias").attr("disabled", false).attr("required", true);
	$("#data_retorno_ferias").attr("disabled", false).attr("required", true);
});

$(document).on("click", ".removeFerias", function () {
	$("#divAddFerias").css("display", "none");
	$("#divRemoveFerias").css("display", "block");
	$("#data_saida_ferias").attr("disabled", true).attr("required", false);
	$("#data_retorno_ferias").attr("disabled", true).attr("required", false);
});

// máscaras
$('#cpf').mask('000.000.000-00');
$('#cep').mask('00.000-000');
$('#telefone').mask('(00) 00000-0000');
$('#salario').mask('0.000.000.000,00', { reverse: true });
$('#tempo_logado').mask('00:00:00');
$('#inicio_job').mask('00:00:00');
$('#fim_job').mask('00:00:00');
$('#intervalo_job').mask('00:00:00');
$('#num_contrato').on('input', function () {
	// Remove qualquer caractere que não seja número
	$(this).val($(this).val().replace(/[^\d]/g, ''));
});
$('#pis').mask('000.00000.00-0');


//tabela demissoes

var tabelaDemissoes = $("#tabelaDemissoes").DataTable({
	paging: true,
	responsive: true,
	order: [0, "desc"],
	pageLength: 4,

	columns: [
		{
			data: "tipoDemissao",
			render: function (data) {
				if (data === 0) {
					return "Sem justa causa";
				} else if (data === 1) {
					return "Justa Causa";
				} else if (data === 2) {
					return "Pedido de demissão";
				} else if (data === 3) {
					return "Termino de Contrato de experiência";
				} else if (data === 4) {
					return "Tempo Determinado";
				} else {
					return data;
				}
			},
		},
		{
			data: "recontratarFuturamente",
			render: function (data) {
				if (data === 0) {
					return "Não";
				} else if (data === 1) {
					return "SIM";
				} else {
					return data;
				}
			},
		},
		{
			data: "motivoDemissao",
		},
		{
			data: "dataDemissao",
			render: function (data) {
				return new Date(data)
					.toJSON()
					.slice(0, 10)
					.split("-")
					.reverse()
					.join("/");
			},
		},
		{
			data: "status",
		},
	],
	language: langDatatable,
});

// ATIVA/INATIVA UM FUNCIONARIO
$(document).on("click", ".ativarInativar", function () {
	let botao = $(this);
	let id = botao.attr("data-id");
	let acao = botao.attr("data-acao");
	let status_bloqueio = botao.attr("data-status_bloqueio");

	var url = mudarStatusFuncionario;

	$.ajax({
		url: url,
		type: "POST",
		dataType: "json",
		data: {
			id: id,
			acao: acao,
		},
		beforeSend: function () {
			// Desabilita button e inicia spinner
			botao
				.attr("disabled", true)
				.html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function (callback) {
			if (callback.success) {
				//status para ferias/demissao do usuario
				var label_status_bloqueio = "";
				switch (status_bloqueio) {
					case "1":
						label_status_bloqueio =
							'<span class="badge badge-warning">' +
							lang.ferias +
							"</span>";
						break;
					case "2":
						label_status_bloqueio =
							'<span class="badge badge-danger">' +
							lang.demitido +
							"</span>";
						break;
				}

				if (acao == "inativar") {
					$(".status_" + id).html(
						'<span class="badge badge-default">' +
						lang.inativo +
						"</span><br>" +
						label_status_bloqueio
					);
					botao
						.removeClass("btn-default")
						.addClass("btn-success")
						.attr("data-acao", "ativar")
						.attr("title", lang.ativar);

					//seta os datas-status para serem usados ao setar ferias ou demitir o funcionario
					$(".ferias_" + id).attr("data-status", "0");
					$(".demitir_" + id).attr("data-status", "0");
				} else {
					$(".status_" + id).html(
						'<span class="badge badge-success">' +
						lang.ativo +
						"</span><br>" +
						label_status_bloqueio
					);
					botao
						.removeClass("btn-success")
						.addClass("btn-default")
						.attr("data-acao", "inativar")
						.attr("title", lang.inativar);

					//seta os datas-status para serem usados ao setar ferias ou demitir o funcionario
					$(".ferias_" + id).attr("data-status", "1");
					$(".demitir_" + id).attr("data-status", "1");
				}
			} else {
				$("#msgFuncionarios").html(
					'<div class="alert alert-danger">' + callback.msg + "</div>"
				);
				$(".funcionarios-alert").css("display", "block");
			}
		},
		error: (error) => {
			$("#msgFuncionarios").html(
				'<div class="alert alert-danger">' +
				lang.operacao_nao_finalizada +
				"</div>"
			);
			$(".funcionarios-alert").css("display", "block");
		},
		complete: function (callback) {
			botao
				.attr("disabled", false)
				.html('<i class="fa fa-check" style="font-size:20px"></i>');
		},
	});
});

//inserir dados de demissão
$("#btnDemitirFunc").click((event) => {
	var idUsuario = $("#data-id").val();
	var tipoDemissao = $("#tipoDemissao").val();
	var recontratarFuturamente = $("#recontratarFuturamente").val();
	var dataDemissao = new Date($("#dataDemissao").val())
		.toJSON()
		.slice(0, 10)
		.split("-")
		.reverse()
		.join("/");
	var motivoDemissao = $("#motivoDemissao").val();

	$.ajax({
		url: inserirDadosDemissao,
		type: "POST",
		data: {
			idUsuario: idUsuario,
			tipoDemissao: tipoDemissao,
			recontratarFuturamente: recontratarFuturamente,
			dataDemissao: dataDemissao,
			motivoDemissao: motivoDemissao,
		},

		dataType: "json",
		success: function (data) {
			if (data.status == 201) {
				$("#Modal-Demissao").modal("hide");
				$("#tipoDemissao").val("");
				$("#dataDemissao").val("");
				$("#motivoDemissao").val("");
				$("#demitirnome").text('');
				$("#recontratarFuturamente").val("");
				$("#data-id").val("");
				showAlert("success", data.dados.mensagem);
			} else if (data.status == 400) {
				showAlert("error", 
					"Falta ao inseir dados. Por favor, tente novamente mais tarde."
				);
			} else {
				showAlert("error", data.dados.mensagem);
			}
		},
		error: (error) => {
			showAlert("error", 
				"Falha ao inserir dados. Tente novamente mais tarde ou contate nosso administrador."
			);
		},
	});
});

//Form Demitir
$("#formDemissaoFunc").submit(function (e) {
	e.preventDefault();

	let id = $("#data-id").val();
	let status = $("#data-status").val();
	let acao = $("#data-acao").val();
	let botao = $(".demitir_" + id);

	if (acao == "demitir") {
		$("#Modal-Demissao").modal("hide");
	} else if (acao == "readimitir") {
		$("#Modal-Readmissao").modal("hide");
	}

	var url = demitirFuncionario;

	$.ajax({
		url: url,
		type: "POST",
		dataType: "json",
		data: {
			id: id,
			acao: acao,
		},
		cache: false,

		beforeSend: function () {
			$(".dropdown-item-acoes").attr("disabled", true);
		},
		success: function (callback) {
			if (callback.success) {
				//status do usuario/funcionario

				var label_status = "";
				switch (status) {
					case "0":
						label_status =
							'<span class="badge badge-default">' +
							lang.inativo +
							"</span>";
						break;
					case "1":
						label_status =
							'<span class="badge badge-success">' +
							lang.ativo +
							"</span>";
						break;
					case "2":
						label_status =
							'<span class="badge badge-important">' +
							lang.bloqueado +
							"</span>";
						break;
					case "3":
						label_status =
							'<span class="badge badge-danger">' +
							lang.cancelado +
							"</span>";
						break;
				}

				if (acao == "demitir") {
					$(".status_" + id).html(
						label_status +
						'<br><span class="badge badge-danger">' +
						lang.demitido +
						"</span>"
					);

					botao
						.removeClass("btn-danger")
						.addClass("btn-default")
						.attr("data-acao", "readimitir")
						.attr("title", lang.readimitir);

					//muda data do botao de ativar/inativar
					$(".ativarInativar_" + id).attr(
						"data-status_bloqueio",
						"2"
					);
				} else {
					$(".status_" + id).html(label_status);
					botao
						.removeClass("btn-default")
						.addClass("btn-danger")
						.attr("data-acao", "demitir")
						.attr("title", lang.demitir);

					//muda data do botao de ativar/inativar
					$(".ativarInativar_" + id).attr(
						"data-status_bloqueio",
						"0"
					);
				}
			} else {
				$("#msgFuncionarios").html(
					'<div class="alert alert-danger">' + callback.msg + "</div>"
				);
				$(".funcionarios-alert").css("display", "block");
			}
		},
		error: (error) => {
			$("#msgFuncionarios").html(
				'<div class="alert alert-danger">' +
				lang.operacao_nao_finalizada +
				"</div>"
			);
			$(".funcionarios-alert").css("display", "block");
		},
		complete: function (callback) {
			$(".dropdown-item-acoes").attr("disabled", false);

			atualizarAgGridFuncionarios();
		},
	});
});

//Form readmição
$("#formReadmissaoFunc").submit(function (e) {
	e.preventDefault();

	let id = $("#rdata-id").val();
	let status = $("#rdata-status").val();
	let acao = $("#rdata-acao").val();

	let botao = $(".demitir_" + id);

	if (acao == "demitir") {
		$("#Modal-Demissao").modal("hide");
	} else if (acao == "readimitir") {
		$("#Modal-Readmissao").modal("hide");
	}

	var url = demitirFuncionario;

	$.ajax({
		url: url,
		type: "POST",
		dataType: "json",
		data: {
			id: id,
			acao: acao,
		},
		cache: false,

		beforeSend: function () {
			$(".dropdown-item-acoes").attr("disabled", true);
		},
		success: function (callback) {
			if (callback.success) {
				//status do usuario/funcionario
				var label_status = "";
				switch (status) {
					case "0":
						label_status =
							'<span class="badge badge-default">' +
							lang.inativo +
							"</span>";
						break;
					case "1":
						label_status =
							'<span class="badge badge-success">' +
							lang.ativo +
							"</span>";
						break;
					case "2":
						label_status =
							'<span class="badge badge-important">' +
							lang.bloqueado +
							"</span>";
						break;
					case "3":
						label_status =
							'<span class="badge badge-danger">' +
							lang.cancelado +
							"</span>";
						break;
				}

				if (acao == "demitir") {
					$(".status_" + id).html(
						label_status +
						'<br><span class="badge badge-danger">' +
						lang.demitido +
						"</span>"
					);

					botao
						.removeClass("btn-danger")
						.addClass("btn-default")
						.attr("data-acao", "readimitir")
						.attr("title", lang.readimitir);

					//muda data do botao de ativar/inativar
					$(".ativarInativar_" + id).attr(
						"data-status_bloqueio",
						"2"
					);
				} else {
					$(".status_" + id).html(label_status);
					botao
						.removeClass("btn-default")
						.addClass("btn-danger")
						.attr("data-acao", "demitir")
						.attr("title", lang.demitir);

					//muda data do botao de ativar/inativar
					$(".ativarInativar_" + id).attr(
						"data-status_bloqueio",
						"0"
					);
				}
			} else {
				$("#msgFuncionarios").html(
					'<div class="alert alert-danger">' + callback.msg + "</div>"
				);
				$(".funcionarios-alert").css("display", "block");
			}
		},
		error: (error) => {
			$("#msgFuncionarios").html(
				'<div class="alert alert-danger">' +
				lang.operacao_nao_finalizada +
				"</div>"
			);
			$(".funcionarios-alert").css("display", "block");
		},
		complete: function (callback) {
			$(".dropdown-item-acoes").attr("disabled", false);

			atualizarAgGridFuncionarios();
		},
	});
});

//CADASTRA UM NOVO FUNCIONARIO
$("#formFeriasFunc").submit(function (e) {
	e.preventDefault();

	if (compararData()) {

		let botao = $("#btnFeriasFunc");
		let id = botao.attr("data-id");
		let status = botao.attr("data-status");
		let data_saida = $("#data_saida_ferias").val();
		let data_retorno = $("#data_retorno_ferias").val();
		let acao = $("input[type='radio'][name=acaoFerias]:checked").val();
		var dataForm = $(this).serialize() + "&id=" + id;

		var url = aplicarFeriasFuncionario;

		$.ajax({
			url: url,
			type: "POST",
			dataType: "json",
			data: dataForm,
			beforeSend: function () {
				// Desabilita button e inicia spinner
				botao
					.attr("disabled", false)
					.html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
			},
			success: function (callback) {
				if (callback.success) {
					//status do usuario/funcionario
					var label_status = "";
					switch (status) {
						case "0":
							label_status =
								'<span class="badge badge-default">' +
								lang.inativo +
								"</span>";
							break;
						case "1":
							label_status =
								'<span class="badge badge-success">' +
								lang.ativo +
								"</span>";
							break;
						case "2":
							label_status =
								'<span class="badge badge-important">' +
								lang.bloqueado +
								"</span>";
							break;
						case "3":
							label_status =
								'<span class="badge badge-danger">' +
								lang.cancelado +
								"</span>";
							break;
					}

					if (acao == "aplicar_ferias") {
						$(".status_" + id).html(
							label_status +
							'<br><span class="badge badge-warning">' +
							lang.ferias +
							"</span>"
						);
						$(".ferias_" + id)
							.attr("data-data_saida", data_saida)
							.attr("data-data_retorno", data_retorno);

						//muda data do botao de ativar/inativar
						$(".ativarInativar_" + id).attr(
							"data-status_bloqueio",
							"1"
						);
					} else {
						$(".status_" + id).html(label_status);
						$(".ferias_" + id)
							.attr("data-data_saida", "")
							.attr("data-data_retorno", "");

						//muda data do botao de ativar/inativar
						$(".ativarInativar_" + id).attr(
							"data-status_bloqueio",
							"0"
						);
					}
					showAlert('success', callback.msg)
				} else {
					showAlert('error', callback.msg)
				}
			},
			error: (error) => {
				showAlert('error', lang.operacao_nao_finalizada)
			},
			complete: function (callback) {
				botao.attr("disabled", false).html(lang.salvar);
				$('#modalFeriasFunc').modal('hide');
				atualizarAgGridFuncionarios();
			},
		});
	}
});

// MOSTRA/ESCONDE DIV QUE CONTEM O FORMULARIO DE CADASTRAR UMA NOVA CONTA BRANCARIA
$(document).on("click", ".addContaBancaria", function (e) {
	e.preventDefault();

	$("#modalAddContaBancaria").modal("show");
});

// ABRE MODAL E LIMPA SEUS DADOS
$(document).on("click", "#btnModalNovoFuncionario", function () {
	resetModalCadFuncionario();
	$("#modalCadFuncionario").modal();
});

// ABRE MODAL E LIMPA SEUS DADOS
$(document).on("click", "#btnModalNovoFuncionarioLote", function () {
	resetModalCadFuncionarioLote();

	$("#modalCadFuncionarioLote").modal();
});

//CADASTRA/EDITA UM NOVO FUNCIONARIO
$("#formNovoFuncionarioEtapa1").submit(function (e) {
	e.preventDefault();

	if (!$('#nome').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo Nome é obrigatório!');
		return;
	}

	if (!$('#nacionalidade').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo Nacionalidade é obrigatório!');
		return;
	}

	if (!$('#naturalidade').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo Naturalidade é obrigatório!');
		return;
	}

	var dataNascimento = $('#data_nasc').val();

	if (!dataNascimento) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo Data de Nascimento é obrigatório!');
		return;
	}

	var dataObj = new Date(dataNascimento);

	if (isNaN(dataObj.getTime())) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'Por favor, insira uma Data de Nascimento válida!');
		return;
	}

	if (!$('#cpf').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo CPF é obrigatório!');
		return;
	}

	if (!validarCPF($('#cpf').val())) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'Digite um CPF válido!');
		return;
	}

	if (!$('#rg').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo RG é obrigatório!');
		return;
	}

	if (!$('#emissor_rg').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo Orgão Emissor é obrigatório!');
		return;
	}

	var dataEmissao = $('#data_emissor').val();

	if (!dataEmissao) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo Data de Emissão é obrigatório!');
		return;
	}

	var dataObj = new Date(dataEmissao);

	if (isNaN(dataObj.getTime())) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'Por favor, insira uma Data de Emissão válida!');
		return;
	}

	if (!$('#sexo').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo Sexo é obrigatório!');
		return;
	}

	if (!$('#UF').val()) {
		$('#aba1-tab').trigger('click');
		showAlert("warning", 'O campo UF é obrigatório!');
		return;
	}

	if (!$('#ocupacao').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo Ocupação é obrigatório!');
		return;
	}

	var dataAdmissao = $('#data_admissao').val();

	if (!dataAdmissao) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo Data de Admissão é obrigatório!');
		return;
	}

	var dataObj = new Date(dataAdmissao);

	if (isNaN(dataObj.getTime())) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'Por favor, insira uma Data de Admissão válida!');
		return;
	}

	if (!$('#num_contrato').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo Número do Contrato é obrigatório!');
		return;
	}

	if (!$('#id_departamentos').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo Departamento é obrigatório!');
		return;
	}

	if (!$('#pis').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo PIS é obrigatório!');
		return;
	}

	if (!$('#salario').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo Salário é obrigatório!');
		return;
	}

	if (!$('#city_job').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo Cidade é obrigatório!');
		return;
	}

	if (!$('#ctps').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo CTPS é obrigatório!');
		return;
	}

	if (!$('#tempo_logado').val()) {
		$('#aba2-tab').trigger('click');
		showAlert("warning", 'O campo Carga Horária (Semanal) é obrigatório!');
		return;
	}

	if (!$('#loginFunc').val()) {
		$('#aba3-tab').trigger('click');
		showAlert("warning", 'O campo Email é obrigatório!');
		return;
	}

	if (!validarEmail($('#loginFunc').val())) {
		$('#aba3-tab').trigger('click');
		showAlert("warning", 'Digite um e-mail válido!');
		return;
	}

	if (!$('#cargo').val()) {
		$('#aba3-tab').trigger('click');
		showAlert("warning", 'O campo Cargo é obrigatório!');
		return;
	}

	let botao = $("#btnNovoFuncionario3");
	let acao = botao.attr("data-acao");
	let textoBotao = botao.text();
	let url = addFuncionario;
	var dataForm = $("#formNovoFuncionarioEtapa1").serialize();

	if (acao == "editar") {
		url = `${editFuncionario}/${id_func}`;
	}

	$.ajax({
		url: url,
		type: "POST",
		dataType: "json",
		data: dataForm,
		beforeSend: function () {
			// Desabilita button e inicia spinner

			if (acao == "editar") {
				botao
					.attr("disabled", true)
					.html(
						'<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando
					);
			} else {
				botao
					.attr("disabled", true)
					.html(
						'<i class="fa fa-spinner fa-spin"></i> ' +
						lang.cadastrando
					);
			}
		},
		success: function (callback) {
			if (callback.success) {
				if (acao == "novo") {
					atualizartableFuncionarios();
				} else {
					atualizartableFuncionarios();
				}
				showAlert("success", callback.msg);
				$("#modalCadFuncionario").modal('hide');
			} else {
				showAlert("error", callback.msg);
			}
		},
		error: function () {
			showAlert("error", lang.operacao_nao_finalizada);
		},
		complete: function () {
			$(".novoFuncionario-alert").css("display", "block");
			botao.attr("disabled", false).html(textoBotao);
			//vai para o topo do modal para mostrar a mensagem ao usuario
			$("#modalCadFuncionario").animate(
				{
					scrollTop: 0,
				},
				"slow"
			);
		},
	});
});

/*
 * CADASTRA FUNCIONARIOS EM LOTE
 */
$(document).on("click", "#cadFuncionariosLote", function () {
	var botao = $(this);

	$.ajax({
		url: cadAttFuncionariosLote,
		type: "POST",
		dataType: "json",
		data: {
			funcionarios: JSON.stringify(excelRows),
		},
		beforeSend: function (callback) {
			botao
				.html('<i class="fa fa-spinner fa-spin"></i> Salvando...')
				.attr("disabled", true);
		},
		success: function (callback) {
			if (callback.success) {
				$("#msnMultiFuncionarios").html(
					'<div class="alert alert-success"><p>' +
					callback.msg +
					"</p></div>"
				);
				tableFuncionarios.ajax.reload(null, false);
			} else {
				$("#msnMultiFuncionarios").html(
					'<div class="alert alert-danger"><p>' +
					callback.msg +
					"</p></div>"
				);
			}
		},
		error: function (callback) {
			$("#msnMultiFuncionarios").html(
				'<div class="alert alert-danger"><p>' +
				lang.operacao_nao_finalizada +
				"</p></div>"
			);
		},
		complete: function (callback) {
			//MOSTRA A MENSAGEM DE RETORNO
			$(".multiFuncionarioAlert").css("display", "block");
			//vai para o topo do modal para mostrar a mensagem ao usuario
			$("#modalCadFuncionarioLote").animate(
				{
					scrollTop: 0,
				},
				"slow"
			);

			botao.html("Salvar").attr("disabled", false);
		},
	});
});

/*
 * VALIDA O FORMATO ARQUIVO .XLSX/.XLS
 */
$("#fileUpload").change(function () {
	var fileUpload = document.getElementById("fileUpload");
	$("#dvExcel").text("");
	$(".multiFuncionarioAlert").css("display", "none");

	//VERIFICA SE UM ARQUIVO EXCEL.
	var regex = /^(.)+(.xls|.xlsx)$/;
	if (regex.test(fileUpload.value.toLowerCase())) {
		if (typeof FileReader != "undefined") {
			var reader = new FileReader();

			//For Browsers other than IE.
			if (reader.readAsBinaryString) {
				reader.onload = function (e) {
					ProcessExcel(e.target.result);
				};
				reader.readAsBinaryString(fileUpload.files[0]);
			} else {
				//For IE Browser.
				reader.onload = function (e) {
					var data = "";
					var bytes = new Uint8Array(e.target.result);
					for (var i = 0; i < bytes.byteLength; i++) {
						data += String.fromCharCode(bytes[i]);
					}
					ProcessExcel(data);
				};
				reader.readAsArrayBuffer(fileUpload.files[0]);
			}
		} else {
			//MOSTRA A MENSAGEM DE RETORNO
			$(".multiFuncionarioAlert").css("display", "block");
			$("#msnMultiFuncionarios").html(
				'<div class="alert alert-danger"><p><b>O browser não suporta HTML5.</b></p></div>'
			);
		}
	} else {
		//MOSTRA A MENSAGEM DE RETORNO
		$(".multiFuncionarioAlert").css("display", "block");
		$("#msnMultiFuncionarios").html(
			'<div class="alert alert-danger"><p><b>O browser não suporta HTML5.</b></p></div>'
		);
		// Limpa os campos
		$("#fileUpload").val(null);
		$('input[type="text"]').val("");
		$("#dvExcel").text("");
	}
	$("#cadFuncionariosLote").attr("disabled", false);
});

// Tabela Listagem Funcionários
function showLoadingPesquisarButton() {
	$("#BtnPesquisar")
		.html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
		.attr("disabled", true);
}

function resetPesquisarButton() {
	$("#BtnPesquisar")
		.html('<i class="fa fa-search"></i> Pesquisar')
		.attr("disabled", false);
}

function showLoadingLimparPesquisarButton() {
	$("#BtnLimparFiltro")
		.html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
		.attr("disabled", true);
}

function resetLimparPesquisarButton() {
	$("#BtnLimparFiltro")
		.html('<i class="fa fa-leaf"></i> Limpar')
		.attr("disabled", false);
}

function formatDateTime(date) {
	if (!date || typeof date !== 'string') {
		return "";
	}

	const parts = date.split(' ');
	const datePart = parts[0];
	const timePart = parts[1] ? ` ${parts[1]}` : "";

	const dateParts = datePart.split('-');
	if (dateParts.length !== 3) {
		return "";
	}

	const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;

	return formattedDate + timePart;
}

function formatDate(date) {
	dates = date.split(" ");
	dateCalendar = dates[0].split("-");
	return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0];
}

function atualizartableFuncionarios() {
	let searchOptions = {
		nome: $('#funcionarioBusca').val(),
		email: $('#emailBusca').val()
	}

	atualizarAgGridFuncionarios(searchOptions);

}

var AgGridFuncionarios;
async function atualizarAgGridFuncionarios(options) {
	stopAgGRIDFuncionarios();

	function getServerSideDados() {
		return {
			getRows: (params) => {
				var route = loadFuncionarios;

				$.ajax({
					cache: false,
					url: route,
					type: "POST",
					data: {
						startRow: params.request.startRow,
						endRow: params.request.endRow,
						nome: options ? options.nome : "",
						email: options ? options.email : "",
					},
					dataType: "json",
					async: true,
					success: function (data) {
						if (data && data.success) {
							var dados = data.rows;
							for (let i = 0; i < dados.length; i++) {
								for (let chave in dados[i]) {
									if (
										dados[i][chave] === null ||
										dados[i][chave] === ""
									) {
										dados[i][chave] = "Não informado";
									}

									if (
										chave === "dataNasc" &&
										dados[i][chave] != "Não informado"
									) {
										dados[i][chave] = formatDate(
											dados[i][chave]
										);
									}
								}
							}
							params.success({
								rowData: dados,
								rowCount: data.lastRow,
							});
						} else if (data && data.message) {
							params.failCallback();
							params.success({
								rowData: [],
								rowCount: 0,
							});
							onBtShowNoRows();
						} else {
							showAlert("error", "Erro na solicitação ao servidor");
							params.failCallback();
							params.success({
								rowData: [],
								rowCount: 0,
							});
						}
						if (options) {
							resetLimparPesquisarButton();
							resetPesquisarButton();
						}
					},
					error: function (error) {
						showAlert("error", "Erro na solicitação ao servidor");
						params.failCallback();
						params.success({
							rowData: [],
							rowCount: 0,
						});
						if (options) {
							resetLimparPesquisarButton();
							resetPesquisarButton();
						}
					},
				});
				if (!options) {
					resetLimparPesquisarButton();
					resetPesquisarButton();
				}
			},
		};
	}

	const gridOptions = {
		columnDefs: [
			{
				headerName: "ID",
				field: "id",
				chartDataType: "category",
				width: 80,
				enableRowGroup: true,
				suppressSizeToFit: true,
			},
			{
				headerName: "Nome",
				field: "nome",
				chartDataType: "category",
				enableRowGroup: true,
				minWidth: 220,
				flex: 1,
				cellRenderer: "agGroupCellRenderer",
			},
			{
				headerName: "Email",
				field: "login",
				chartDataType: "category",
				enableRowGroup: true,
				minWidth: 220,
				flex: 1,
			},
			{
				headerName: "Ocupação",
				field: "ocupacao",
				enableRowGroup: true,
				chartDataType: "category",
				width: 240,
				suppressSizeToFit: true,
			},
			{
				headerName: "Empresa",
				field: "empresa",
				enableRowGroup: true,
				chartDataType: "category",
				width: 240,
				suppressSizeToFit: true,
			},
			{
				headerName: "Telefone",
				field: "telefone",
				enableRowGroup: true,
				chartDataType: "category",
				width: 150,
				suppressSizeToFit: true,
			},
			{
				headerName: "Data de Nascimento",
				field: "dataNasc",
				enableRowGroup: true,
				chartDataType: "category",
				width: 200,
				suppressSizeToFit: true,
			},
			{
				headerName: "Status",
				field: "status",
				enableRowGroup: true,
				autoHeight: true,
				chartDataType: "category",
				width: 125,
				cellRenderer: function (params) {
					var backStatus = "";
					var backStatusBloqueio = "";
					var status = params.data.status; // status geral
					var statusBloqueio = params.data.statusBloqueio; // status específico de bloqueio
					var idFuncionario = params.data.id; // ID do funcionário

					// Lógica para status geral
					switch (status) {
						case "INATIVO":
							backStatus =
								'<span class="badge badge-secondary">Inativo</span>';
							break;
						case "ATIVO":
							backStatus =
								'<span class="badge badge-primary">Ativo</span>';
							break;
						case "FERIAS":
							backStatus =
								'<span class="badge badge-success">Férias</span>';
							break;
						case "DEMITIDO":
							backStatus =
								'<span class="badge badge-danger">Demitido</span>';
							break;
						default:
							backStatus = "";
					}

					// Lógica para status de bloqueio (você pode ajustar conforme os valores que espera receber)
					switch (statusBloqueio) {
						case 1: // Considerando 1 para 'Em Férias'
							backStatusBloqueio =
								'<span class="badge badge-info">Em Férias</span>';
							break;
						case 2: // Considerando 2 para 'Demitido'
							backStatusBloqueio =
								'<span class="badge badge-danger">Demitido</span>';
							break;
						// Adicione mais casos conforme necessário
					}

					// Retorna a combinação dos dois status com um <br> entre eles para separar visualmente
					return `<div style="display: flex; flex-direction: column; align-items: center; justify-items: center; width: 78px; height: auto; margin: 5px; gap: 5px;" class="status_${idFuncionario}">${backStatus}${backStatusBloqueio}</div>`;
				},
			},
			{
				headerName: "Ações",
				width: 80,
				pinned: "right",
				cellClass: "actions-button-cell",
				cellRenderer: function (options) {
					var firstRandom = Math.random() * 10;
					var secRandom = Math.random() * 10;
					var thirdRandom = Math.random() * 10;

					var varAleatorio =
						(firstRandom * secRandom).toFixed(0) +
						"-" +
						(thirdRandom * firstRandom).toFixed(0) +
						"-" +
						(secRandom * thirdRandom).toFixed(0);

					let data = options.data;
					let tableId = "tableFuncionarios";
					let dropdownId =
						"dropdown-menu-" + data.id + "func" + varAleatorio;
					let buttonId =
						"dropdownMenuButton_" + data.id + "func" + varAleatorio;

					return `
                    <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle"  ondblclick="event.preventDefault(); event.stopPropagation();" type="button" id="${buttonId}" row_id="${data.id_evento
						}" nome="${data.placa_lida}" id="${data.id_evento
						}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL +
						"media/img/new_icons/icon_acoes.svg"
						}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-funcionarios" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                ${getActionButtons(data)}
                            </div>
                    </div>`;
				},
			},
		],
		onFirstDataRendered: onFirstDataRendered,
		defaultColDef: {
			editable: false,
			sortable: false,
			filter: false,
			resizable: true,
			suppressMenu: true,
		},
		sideBar: {
			toolPanels: [
				{
					id: 'columns',
					labelDefault: 'Colunas',
					iconKey: 'columns',
					toolPanel: 'agColumnsToolPanel',
					toolPanelParams: {
						suppressRowGroups: false,
						suppressValues: true,
						suppressPivots: true,
						suppressPivotMode: true,
						suppressColumnFilter: false,
						suppressColumnSelectAll: false,
						suppressColumnExpandAll: true,
					},
				},
			],
			defaultToolPanel: false,
		},
		enableRangeSelection: true,
		enableCharts: true,
		pagination: true,
		paginateChildRows: true,
		domLayout: "normal",
		paginationPageSize: parseInt(
			$("#select-quantidade-por-pagina-funcionarios").val()
		),
		localeText: localeText,
		rowModelType: "serverSide",
		serverSideStoreType: "partial",
		onRowDoubleClicked: function (params) {
			let data = 'data' in params ? params.data : null;
			if (data) {
				if ('id' in data) {
					editFuncionarioModal(data.id)
				}
			}
		},
		overlayNoRowsTemplate:
			"<p>Nenhum dado encontrado para os parâmetros informados!</p>",
	};

	function onBtShowNoRows() {
		gridOptions.api.showNoRowsOverlay();
	}

	function onFirstDataRendered(params) {
		setTimeout(() => {
			const rowNode = params.api.getDisplayedRowAtIndex(1);
			if (rowNode) {
				rowNode.setExpanded(true);
			}
		}, 500); // Ajuste o delay conforme necessário
	}

	var gridDiv = document.querySelector("#tableFuncionarios");
	AgGridFuncionarios = new agGrid.Grid(gridDiv, gridOptions);
	let datasource = getServerSideDados();
	gridOptions.api.setServerSideDatasource(datasource);

	$("#select-quantidade-por-pagina-funcionarios").change(function () {
		var selectedValue = $(
			"#select-quantidade-por-pagina-funcionarios"
		).val();
		gridOptions.api.paginationSetPageSize(Number(selectedValue));
	});

	var gridDiv = document.querySelector("#tableFuncionarios");
	gridDiv.style.setProperty("height", "527px");

	function handleDataChange(event) {
		$("#loadingMessageFunc").show();

		let paginaAtual = Number(event.api.paginationGetCurrentPage());
		let tamanhoPagina = Number(event.api.paginationGetPageSize());

		const filteredData = [];
		event.api.forEachNode((node) => {
			if (node.displayed) {
				filteredData.push(node.data);
			}
		});
		const startIndex = paginaAtual * tamanhoPagina;
		const endIndex = startIndex + tamanhoPagina;

		const pageData = filteredData.slice(startIndex, endIndex);

		var dados = [];

		pageData.forEach((data) => {
			if (data) {
				dados.push(data);
			}
		});

		$("#loadingMessageFunc").hide();
	}

	gridOptions.api.addEventListener("paginationChanged", handleDataChange);
	preencherExportacoes(gridOptions);
}

function showStatusFuncionario(params) {
	let backStatus = "";
	let backStatusBloqueio = "";
	const status = data.status;
	const statusBloqueio = data.statusBloqueio;
	const idFuncionario = data.idFuncionario;

	switch (status) {
		case 0:
			backStatus =
				'<span class="badge badge-default">' + "Inativo" + "</span>";
			break;
		case 1:
			backStatus =
				'<span class="badge badge-success">' + "Ativo" + "</span>";
			break;
		case 2:
			backStatus =
				'<span class="badge badge-important">' +
				"Bloqueado" +
				"</span>";
			break;
		case 3:
			backStatus =
				'<span class="badge badge-danger">' + "Cancelado" + "</span>";
			break;
	}

	switch (statusBloqueio) {
		case 1:
			backStatusBloqueio =
				'<span class="badge badge-warning">' + "Férias" + "</span>";
			break;
		case 2:
			backStatusBloqueio =
				'<span class="badge badge-danger">' + "Demitido" + "</span>";
			break;
	}

	return (
		'<span class="badge status_' +
		idFuncionario +
		'">' +
		backStatus +
		"<br>" +
		backStatusBloqueio +
		"</span>"
	);
}

function ativarInativarUsuario(id, acao, status_bloqueio) {
	$("#btnConfirmarAcaoUsuario")
		.html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
		.attr("disabled", true);
	$.ajax({
		url: mudarStatusFuncionario,
		type: "POST",
		dataType: "json",
		data: {
			id: id,
			acao: acao,
		},

		success: function (callback) {
			if (callback.success) {
				var label_status_bloqueio = "";
				switch (status_bloqueio) {
					case "1":
						label_status_bloqueio =
							'<span class="badge badge-warning">' +
							lang.ferias +
							"</span>";
						break;
					case "2":
						label_status_bloqueio =
							'<span class="badge badge-danger">' +
							lang.demitido +
							"</span>";
						break;
				}

				if (acao == "inativar") {
					$(".status_" + id).html(
						'<span class="badge badge-default">' +
						lang.inativo +
						"</span><br>" +
						label_status_bloqueio
					);

					//seta os datas-status para serem usados ao setar ferias ou demitir o funcionario
					$(".ferias_" + id).attr("data-status", "0");
					$(".demitir_" + id).attr("data-status", "0");
				} else {
					$(".status_" + id).html(
						'<span class="badge badge-success">' +
						lang.ativo +
						"</span><br>" +
						label_status_bloqueio
					);

					//seta os datas-status para serem usados ao setar ferias ou demitir o funcionario
					$(".ferias_" + id).attr("data-status", "1");
					$(".demitir_" + id).attr("data-status", "1");
				}
			} else {
				$("#msgFuncionarios").html(
					'<div class="alert alert-danger">' + callback.msg + "</div>"
				);
				$(".funcionarios-alert").css("display", "block");
			}
		},
		error: (error) => {
			$("#msgFuncionarios").html(
				'<div class="alert alert-danger">' +
				lang.operacao_nao_finalizada +
				"</div>"
			);
			$(".funcionarios-alert").css("display", "block");
		},
		complete: function (callback) {
			$("#btnConfirmarAcaoUsuario")
				.html("Confirmar")
				.attr("disabled", false);
			$("#modalConfirmacaoMudancaStatus").modal("hide");
			atualizarAgGridFuncionarios();
		},
	});
}

function permitirFerias(id, status, dataSaida, dataRetorno) {
	//limpa o modal
	resetModalFerias();

	if (dataSaida && dataSaida != "0000-00-00")
		$("#data_saida_ferias").val(dataSaida);
	if (dataRetorno && dataRetorno != "0000-00-00")
		$("#data_retorno_ferias").val(dataRetorno);

	$("#btnFeriasFunc").attr("data-id", id).attr("data-status", status);
	$("#modalFeriasFunc").modal();
}

function demitirReadimitir(idUsuario, acao, status) {
	$.ajax({
		url: getFuncionario,
		type: "POST",
		dataType: "JSON",
		data: {
			id_func: idUsuario,
		},
		beforeSend: function () {
			$(".dropdown-item-acoes").attr("disabled", true);
		},
		success: function (callback) {
			if (callback.success) {
				//PEGA OS CAMPOS DO FORMULARIO
				var funcionario = callback.retorno;

				let readmitirnome = document.querySelector("#readmitirnome");
				readmitirnome.innerHTML = funcionario["nome"];

				let demitirnome = document.querySelector("#demitirnome");
				demitirnome.innerHTML = funcionario["nome"];
			}
		},
		complete: function (callback) {
			$(".dropdown-item-acoes").attr("disabled", false);
		},
	});

	if (acao == "demitir") {
		$("#data-id").val(idUsuario);
		$("#data-status").val(status);
		$("#data-acao").val(acao);
		$("#Modal-Demissao").modal("show");
	} else if (acao == "readimitir") {
		$("#rdata-id").val(idUsuario);
		$("#rdata-status").val(status);
		$("#rdata-acao").val(acao);
		$("#Modal-Readmissao").modal("show");
	}
}

function stopAgGRIDDemissoes() {
    var gridDiv = document.querySelector("#agGridDemissoes");
    if (gridDiv && gridDiv.__agComponent) {
        gridDiv.__agComponent.gridOptions.api.destroy();
    }

    var wrapper = document.querySelector(".wrapperDemissoes");
    if (wrapper) {
        wrapper.innerHTML = '<div id="agGridDemissoes" class="ag-theme-alpine"></div>';
    }
}

function configurarAgGridDemissoes(dados) {
    stopAgGRIDDemissoes();

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Tipo de Demissão',
                field: 'tipoDemissao',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Recontratar Futuramente',
                field: 'recontratarFuturamente',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    return params.value === 1 ? 'Sim' : 'Não';
                }
            },
            {
                headerName: 'Motivo da Demissão',
                field: 'motivoDemissao',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true,
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 10,
    };

    var gridDiv = document.querySelector('#agGridDemissoes');
    gridDiv.style.setProperty('height', '210px');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
}

async function detalhesFuncionario(idUsuario) {
    $(".modalLoadingMessageAct").show();
    $(".modalLoadingMessageAct").show();

    await $.ajax({
        url: buscarDemissao,
        type: "POST",
        data: {
            idUsuario: idUsuario,
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 200) {
                let rawData = data.dados;
                let mapped = rawData.map(item => {
                    return {
                        tipoDemissao: item.tipoDemissao || "Não informado",
                        recontratarFuturamente: item.recontratarFuturamente != null ? item.recontratarFuturamente : "Não informado",
                        motivoDemissao: item.motivoDemissao || "Não informado",
                        dataCadastro: item.dataCadastro || "Não informado",
                        status: item.status || "Não informado"
                    };
                });
                configurarAgGridDemissoes(mapped);
            } else {
                configurarAgGridDemissoes([]);
            }
        },
        error: function (erro) {
            console.error(erro);
        },
    });

    $.ajax({
        url: getFuncionario,
        type: "POST",
        dataType: "JSON",
        data: {
            id_func: idUsuario,
        },
        beforeSend: function () {
            $(".dropdown-item-acoes").attr("disabled", true);
        },
        success: function (callback) {
            if (callback.success) {
                let funcionario = callback.retorno;

                funcionario = {
                    nome: funcionario.nome || "Não informado",
                    formacao: funcionario.formacao || "Não informado",
                    UF: funcionario.UF || "Não informado",
                    deficiencia: funcionario.deficiencia || "Não informado",
                    empresa: funcionario.empresa || "Não informado",
                    login: funcionario.login || "Não informado",
                    civil: funcionario.civil || "Não informado",
                    chefia_imediata: funcionario.chefia_imediata || "Não informado"
                };

                document.querySelector("#idUsuario").innerHTML = idUsuario;
                document.querySelector("#detalhe-nome").innerHTML = funcionario.nome;
                document.querySelector("#detalhe-formacao").innerHTML = funcionario.formacao;
                document.querySelector("#detalhe-uf").innerHTML = funcionario.UF;
                document.querySelector("#detalhe-deficiencia").innerHTML = funcionario.deficiencia;
                document.querySelector("#detalhe-empresa").innerHTML = funcionario.empresa;
                document.querySelector("#detalhe-email").innerHTML = funcionario.login;
                document.querySelector("#detalhe-civil").innerHTML = funcionario.civil;
                document.querySelector("#detalhe-chefia").innerHTML = funcionario.chefia_imediata;

                $("#Modal-Detalhes").modal("show");
            }
        },
        complete: function (callback) {
            $(".dropdown-item-acoes").attr("disabled", false);
            $(".modalLoadingMessageAct").hide();
        },
    });
}


function editFuncionarioModal(idFunc) {
	$(".modalLoadingMessageAct").show();
	//LIMPA OS DADOS(LIXO) DO FORMULARIO
	resetModalCadFuncionario();

	$.ajax({
		url: getFuncionario,
		type: "POST",
		dataType: "JSON",
		data: {
			id_func: idFunc,
		},
		beforeSend: function () {
			$(".dropdown-item-acoes").attr("disabled", true);
		},
		success: function (callback) {
			if (callback.success) {
				id_func = idFunc;

				//PEGA OS CAMPOS DO FORMULARIO
				var funcionario = callback.retorno;
				var chavesSelects = [
					"civil",
					"formacao",
					"deficiencia",
					"uf",
					"empresa",
					"cargo",
					"sexo"
				];

				Object.keys(funcionario).forEach((item) => {
					//ADICIONA OS VALORES DOS CAMPOS
					if (item == "login") {
						$("#loginFunc").val(funcionario[item] ? funcionario[item] : '');
					} else if (chavesSelects.indexOf(item) != -1) {
						$("#" + item)
							.val(funcionario[item])
							.prop("selected", true);
					} else {
						$("#" + item).val(funcionario[item] ? funcionario[item] : '');
						$('#' + item).trigger('input');
					}

				});

				//CONFIGURA BOTAO PARA DIRECIONAR O FORMARIO PARA EDICAO EM VEZ DE CRIA UM NOVO FUNCIONARIO
				$("#btnNovoFuncionario3")
					.attr("data-acao", "editar")
					.attr("data-id", idFunc)
					.text(lang.salvar);
				//ALTERA O TITULO DO MODAL
				$("#tituloCadFuncionario").html(
					"Editar Funcionário #" + idFunc
				);
				//ABRE O MODAL
				$("#modalCadFuncionario").modal();
			} else {
				showAlert("error", callback.msg);
			}
		},
		error: function (data) {
			showAlert("error", lang.operacao_nao_finalizada);
		},
		complete: function (callback) {
			$(".dropdown-item-acoes").attr("disabled", false);
			$(".modalLoadingMessageAct").hide();
		},
	});
}

async function contasBancarias(id, nome) {
	//RESETA AS INFORMACOES NO MODAL
	resetModalCadContas();

	$("#tituloCadContaBancaria").text(lang.contas_bancarias_de + " " + nome);

	$("#btnCadContaBancaria").attr("data-id_funcionario", id);
	$("#btnCadContaBancaria").html("Cadastrar");

	await agGridContasBancarias(id);

	$("#modalCadContaBancaria").modal();
}

function getActionButtons(data) {
    let buttons = [];

    if (permissaoUsuario[0] == 1) {
        var ativarInativar = data.status == "ATIVO" ? "inativar" : "ativar";
        var nomeFuncionario = data.nome;
        var idFuncionario = data.id;
        var statusBloqueio = data.statusBloqueio;

        buttons.push({
            text: data.status == "ATIVO" ? "Inativar" : "Ativar",
            html: `
                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                    <a href="javascript:abrirModalConfirmacao('${ativarInativar}', '${nomeFuncionario}', '${idFuncionario}', '${statusBloqueio}')" 
                    style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">${data.status == "ATIVO" ? "Inativar" : "Ativar"
            }</a>
                </div>`
        });
    }

    buttons.push({
        text: "Contas Bancárias",
        html: `
            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                <a href="javascript:contasBancarias('${data.id}', '${data.nome
        }')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Contas Bancárias</a>
            </div>`
    });

    if (permissaoUsuario[2] == 1) {
        let acao = data.statusBloqueio == 2 ? "readimitir" : "demitir";
        buttons.push({
            text: data.statusBloqueio == 2 ? "Readmitir" : "Demitir",
            html: `
                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                    <a href="javascript:demitirReadimitir('${data.id}', '${acao}', '${data.status
            }')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">${data.statusBloqueio == 2 ? "Readmitir" : "Demitir"
            }</a>
                </div>`
        });
    }

    if (permissaoUsuario[3] == 1) {
        buttons.push({
            text: "Detalhes",
            html: `
                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                    <a href="javascript:detalhesFuncionario('${data.id}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Detalhes</a>
                </div>`
        });
    }

    buttons.push({
        text: "Editar",
        html: `
            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                <a href="javascript:editFuncionarioModal('${data.id
            }')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
            </div>`
    });

    if (permissaoUsuario[1] == 1) {
        buttons.push({
            text: "Férias",
            html: `
                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                    <a href="javascript:permitirFerias('${data.id}', '${data.status}', '${data.dataSaidaFerias}', '${data.dataRetornoFerias}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Férias</a>
                </div>`
        });
    }

    buttons.push({
        text: "Gerir Documentos",
        html: `
            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                <a href="javascript:abrirGerenciamentoDocumentos('${data.cpf}', '${data.id
            }')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Gerir Documentos</a>
            </div>
            <a class="dropdown-item dropdown-item-acoes" href="${SiteURL + "/contratos/contrato_trabalho/" + data.id
            }" target="_blank" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">
                ${lang.imprimir_contrato}
            </a>`
    });

    // Ordenar os botões alfabeticamente
    buttons.sort((a, b) => a.text.localeCompare(b.text));

    // Mapear os botões ordenados para suas representações em HTML
    const buttonsHTML = buttons.map(button => button.html);

    // Concatenar os botões em uma única string
    return buttonsHTML.join("");
}

function abrirDropdown(dropdownId, buttonId, tableId) {
	var dropdown = $("#" + dropdownId);

	if (dropdown.is(":visible")) {
		dropdown.hide();
		return;
	}

	$(".dropdown-menu").hide();

	dropdown.show();
	var posDropdown = dropdown.height() + 4;

	var dropdownItems = $("#" + dropdownId + " .dropdown-item-acoes");
	var alturaDrop = 0;
	for (var i = 0; i <= dropdownItems.length; i++) {
		alturaDrop += dropdownItems.height();
	}

	if (alturaDrop >= 235) {
		$(".dropdown-menu-acoes").css("overflow-y", "scroll");
	}

	var posBordaTabela = $("#" + tableId + " .ag-body-viewport")
		.get(0)
		.getBoundingClientRect().bottom;
	var posBordaTabelaTop = $("#" + tableId + " .ag-header")
		.get(0)
		.getBoundingClientRect().bottom;

	var posButton = $("#" + buttonId)
		.get(0)
		.getBoundingClientRect().bottom;
	var posButtonTop = $("#" + buttonId)
		.get(0)
		.getBoundingClientRect().top;

	if (posDropdown > posBordaTabela - posButton) {
		if (posDropdown < posButtonTop - posBordaTabelaTop) {
			dropdown.css("top", `-${alturaDrop - 150}px`);
		} else {
			let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
			dropdown.css("top", `-${alturaDrop / 2 - diferenca}px`);
		}
	}

	$(document).on("click", function (event) {
		if (!dropdown.is(event.target) && !$("#" + buttonId).is(event.target)) {
			dropdown.hide();
		}
	});
}

function stopAgGRIDFuncionarios() {
	var gridDiv = document.querySelector("#tableFuncionarios");
	if (gridDiv && gridDiv.api) {
		gridDiv.api.destroy();
	}

	var wrapper = document.querySelector(".wrapperFuncionarios");
	if (wrapper) {
		wrapper.innerHTML =
			'<div id="tableFuncionarios" class="ag-theme-alpine my-grid-funcionarios"></div>';
	}
}

let menuAberto = false;

function expandirGrid() {
	menuAberto = !menuAberto;
	let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
	let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

	if (menuAberto) {
		$(".img-expandir").attr("src", buttonShow);
		$(".col-md-3").fadeOut(250, function () {
			$("#conteudo").removeClass("col-md-9").addClass("col-md-12");
		});
	} else {
		$(".img-expandir").attr("src", buttonHide);
		$("#conteudo").removeClass("col-md-12").addClass("col-md-9");
		setTimeout(() => {
			$(".col-md-3").fadeIn(250);
		}, 510);
	}
}

/*
 * PROCESSA OS DADOS DO ARQUIVO .XLSX/.XLS
 */
function ProcessExcel(data) {
	//Read the Excel File data.
	var workbook = XLSX.read(data, {
		type: "binary",
		dateNF: "dd/mm/yyyy",
	});

	//Fetch the name of First Sheet.
	var firstSheet = workbook.SheetNames[0];

	//Read all rows from First Sheet into an JSON array.
	excelRows = XLSX.utils.sheet_to_row_object_array(
		workbook.Sheets[firstSheet]
	);
	var chaves = [
		"login",
		"nome",
		"ocupacao",
		"departamento",
		"chefia_imediata",
		"diretoria",
		"unidade",
		"data_admissao",
		"data_nasc",
		"cpf",
		"empresa",
	];
	var chavesObject = Object.keys(excelRows[0]);
	var temTodosOsCampos = true;
	chaves.forEach((chave) => {
		if (chavesObject.indexOf(chave) == -1) temTodosOsCampos = false;
	});

	if (excelRows.length && temTodosOsCampos) {
		//Create a HTML Table element.
		var table = document.createElement("table");
		table.border = "1";

		//Add the header row.
		var row = table.insertRow(-1);

		//Add the header cells.
		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.login;
		row.appendChild(headerCell);

		var headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.nome;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.ocupacao;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.departamento;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.chefia_imediata;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.diretoria;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.unidade;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.data_admissao;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.data_nasc;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.cpf;
		row.appendChild(headerCell);

		headerCell = document.createElement("TH");
		headerCell.innerHTML = lang.empresa;
		row.appendChild(headerCell);

		//Add the data rows from Excel file.
		var qtdFuncionarios = excelRows.length <= 10 ? excelRows.length : 10;
		for (var i = 0; i < qtdFuncionarios; i++) {
			//ADICIONA A LINHA
			var row = table.insertRow(-1);

			//ADICIONA AS CELULAS/COLUNAS
			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].login && excelRows[i].login !== "undefined"
					? excelRows[i].login
					: inconcistenciaDadosTabela("Campo login é obrigatório!");

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].nome && excelRows[i].nome !== "undefined"
					? excelRows[i].nome
					: inconcistenciaDadosTabela("Campo nome é obrigatório!");

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].ocupacao && excelRows[i].ocupacao !== "undefined"
					? excelRows[i].ocupacao
					: inconcistenciaDadosTabela(
						"Campo ocupação é obrigatório!"
					);

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].departamento &&
					excelRows[i].departamento !== "undefined"
					? excelRows[i].departamento
					: inconcistenciaDadosTabela(
						"Campo departamento é obrigatório!"
					);

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].chefia_imediata &&
					excelRows[i].chefia_imediata !== "undefined"
					? excelRows[i].chefia_imediata
					: "";

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].diretoria && excelRows[i].diretoria !== "undefined"
					? excelRows[i].diretoria
					: "";

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].unidade && excelRows[i].unidade !== "undefined"
					? excelRows[i].unidade
					: "";

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].data_admissao &&
					excelRows[i].data_admissao !== "undefined"
					? excelRows[i].data_admissao
					: inconcistenciaDadosTabela(
						"Campo Data de Admissão é obrigatório!"
					);

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].data_nasc && excelRows[i].data_nasc !== "undefined"
					? excelRows[i].data_nasc
					: inconcistenciaDadosTabela(
						"Campo Data de Nascimento é obrigatório!"
					);

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].cpf && excelRows[i].cpf !== "undefined"
					? excelRows[i].cpf
					: inconcistenciaDadosTabela("Campo CPF é obrigatório!");

			cell = row.insertCell(-1);
			cell.innerHTML =
				excelRows[i].empresa && excelRows[i].empresa !== "undefined"
					? excelRows[i].empresa
					: inconcistenciaDadosTabela("Campo Empresa é obrigatório!");
		}

		var dvExcel = document.getElementById("dvExcel");
		dvExcel.innerHTML = "";
		dvExcel.appendChild(table);
		//formata a tabela
		$("#dvExcel table").css("width", "100%");
		$("#dvExcel table tr th")
			.css("padding", "1px 2px")
			.css("text-align", "center");
		$("#dvExcel table tr td")
			.css("padding", "1px 2px")
			.css("text-align", "center");
	} else {
		inconcistenciaDadosTabela(
			"Dados inconsistentes, Verifique que os nomes das colunas: login, nome, ocupacao, departamento, chefia_imediata, diretoria, unidade, data_admissao, data_nasc, cpf, empresa. não podem ter espaços em branco no início e fim de cada palavra."
		);
	}
}

//RETORNA MENSAGEM ERRO E LIMPA TABELA
function inconcistenciaDadosTabela(msg) {
	document.getElementById("dvExcel").innerHTML = msg;
	$("#cadFuncionariosLote").attr("disabled", true);
	$("#fileUpload").val(null);
	$('input[type="text"]').val("");
}

//RESETA MODAL DE FERIAS
function resetModalFerias() {
	$("#formFeriasFunc")[0].reset();
	$("#divAddFerias").css("display", "block");
	$("#divRemoveFerias").css("display", "none");
	$("#data_saida_ferias").attr("disabled", false).attr("required", true);
	$("#data_retorno_ferias").attr("disabled", false).attr("required", true);

	//limpa as mensagens
	$("#msgFeriasFunc").html("");
}

//RESETA OS DADOS DO MODAL CADASTRO DE FUNCIONARIO
function resetModalCadFuncionario() {
	// muda para primeira aba
	$('#aba1-tab').trigger('click');

	//seta o titulo para 'cadastro'
	$("#tituloCadFuncionario").text(lang.cadastrar_funcionario);
	$("#btnNovoFuncionario3").attr("data-acao", "novo");

	//reseta os campos dos forms
	$("#formNovoFuncionarioEtapa1")[0].reset();

	//reseta o id_funcionario
	id_func = false;

	//reseta o campo de forca da senha
	$(".forca_senha")
		.css("width", "0%")
		.html("<span>" + lang.fraca_s + "</span>");
}

//RESETA OS DADOS DO MODAL CADASTRO DE FUNCIONARIOS EM LOTE
function resetModalCadFuncionarioLote() {
	//limpa os dados
	$("#dvExcel").text("");
	$("#fileUpload").val(null);
	$('input[type="text"]').val("");
	$("#msnMultiFuncionarios").html("");
	$(".multiFuncionarioAlert").css("display", "none");
}

//RESETA OS DADOS DO MODAL CADASTRO DE CONTA
function resetModalCadContas() {
	//limpa a div de mensagem
	$("#msgCadContas").html("");
	$(".cadContas-alert").css("display", "none");

	//reseta os campos do form e o esconde
	$("#formCadContaBancaria")[0].reset();
	$("#divCadContabancaria").css("display", "none");

	//reseta o botao 'Nova Conta'
	$(".addContaBancaria")
		.attr("data-acao", "mostrarForm")
		.removeClass("btn-danger")
		.addClass("btn-primary")
		.html('<i class="fa fa-plus icon-white"></i> ' + lang.nova_conta);
}

//RESETA OS DADOS DO MODAL CADASTRO DE FUNCIONARIO
function resetFormEditContas() {
	//limpa a div de mensagem
	$(".editContas-alert").css("display", "none");

	linhaTabelaContas = "";
}

//VALIDA CPF
function validarCPF(cpf) {
	cpf = cpf.replace(/[^\d]+/g, '');
	if (cpf.length !== 11 || !!cpf.match(/(\d)\1{10}/)) return false;

	var soma = 0;

	for (var i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
	var resto = (soma * 10) % 11;
	if ((resto === 10) || (resto === 11)) resto = 0;
	if (resto !== parseInt(cpf.charAt(9))) return false;

	soma = 0;
	for (i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
	resto = (soma * 10) % 11;
	if ((resto === 10) || (resto === 11)) resto = 0;
	if (resto !== parseInt(cpf.charAt(10))) return false;

	return true;
}

//VALIDA E-MAIL
function validarEmail(email) {
	var regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	return regex.test(email);
}

//MOSTRA A FORÇA DA SENHA
$(".senha").on("keyup", function () {
	var senha = $(".senha").val();
	var forca = 0;
	var mostra = $(".forca_senha");

	if (senha.length >= 4 && senha.length <= 7) forca += 10;
	else if (senha.length > 7) forca += 15;

	if (senha.match(/[a-z]+/)) forca += 15;
	if (senha.match(/[A-Z]+/)) forca += 20;
	if (senha.match(/[0-9]+/)) forca += 20;
	if (senha.match(/[^A-Za-z0-9_]/)) forca += 30;

	return mostra_res(forca, mostra);
});

function mostra_res(forca, mostra) {
	if (forca < 30) {
		$(mostra)
			.css("width", forca + "%")
			.html("<span>" + lang.fraca_s + "</span>");
	} else if (forca >= 30 && forca < 60) {
		$(mostra)
			.css("width", forca + "%")
			.html("<span>" + lang.media_s + "</span>");
	} else if (forca >= 60 && forca < 85) {
		$(mostra)
			.css("width", forca + "%")
			.html("<span>" + lang.forte_s + "</span>");
	} else {
		$(mostra)
			.css("width", "100%")
			.html("<span>" + lang.excelente_s + "<n/span>");
	}
}

$(document).on("click", ".novosFuncionariosLote", function () {
	//limpa os dados
	$("#dvExcel").text("");
	$("#fileUpload").val(null);
	$('input[type="text"]').val("");
	$(".multiChipsAlert").css("display", "none");
	$("#modalFuncionarioLote").modal();
});

// Confirmação de ação de inativar:
function abrirModalConfirmacao(
	acao,
	funcionario,
	idFuncionario,
	statusBloqueio
) {
	let mensagem = `Tem certeza que deseja ${acao.toUpperCase()} o funcionário ${funcionario.toUpperCase()} ?`;

	$("#mensagemConfirmacao").html(mensagem);

	$("#btnConfirmarAcaoUsuario")
		.off("click")
		.one("click", function (event) {
			event.stopImmediatePropagation();
			ativarInativarUsuario(idFuncionario, acao, statusBloqueio);
		});

	$("#modalConfirmacaoMudancaStatus").modal("show");
}

// EXPORTAÇÃO DA TEBELA FUNCIONÁRIOS
function exportarArquivo(tipo, gridOptions, titulo) {
	var colunas = [
		"id",
		"nome",
		"telefone",
		"dataNasc",
		"ocupacao",
		"empresa",
		"status",
	];

	switch (tipo) {
		case "csv":
			fileName = titulo + ".csv";
			gridOptions.api.exportDataAsCsv({
				fileName: fileName,
				columnKeys: colunas,
			});
			break;
		case "excel":
			fileName = titulo + ".xlsx";
			gridOptions.api.exportDataAsExcel({
				fileName: fileName,
				columnKeys: colunas,
			});
			break;
		case "pdf":
			let definicoesDocumento = getCustomDocDefinition(
				printParams("A4"),
				gridOptions.api,
				gridOptions.columnApi,
				"",
				titulo,
				colunas
			);
			pdfMake.createPdf(definicoesDocumento).download(titulo + ".pdf");
			break;
	}
}

function printParams(pageSize) {
	return {
		PDF_HEADER_COLOR: "#ffffff",
		PDF_INNER_BORDER_COLOR: "#dde2eb",
		PDF_OUTER_BORDER_COLOR: "#babfc7",
		PDF_LOGO: BaseURL + "media/img/new_icons/omnilink.png",
		PDF_HEADER_LOGO: "omnilink",
		PDF_ODD_BKG_COLOR: "#fff",
		PDF_EVEN_BKG_COLOR: "#F3F3F3",
		PDF_PAGE_ORITENTATION: "landscape",
		PDF_WITH_FOOTER_PAGE_COUNT: true,
		PDF_HEADER_HEIGHT: 25,
		PDF_ROW_HEIGHT: 25,
		PDF_WITH_CELL_FORMATTING: true,
		PDF_WITH_COLUMNS_AS_LINKS: false,
		PDF_SELECTED_ROWS_ONLY: false,
		PDF_PAGE_SIZE: pageSize,
		PDF_BODY_FONT_SIZE: 10
	};
}

$(document).ready(function () {
	var dropdown = $("#opcoes_exportacao");

	$("#dropdownMenuButton").click(function () {
		dropdown.toggle();
	});

	$(document).click(function (event) {
		if (
			!dropdown.is(event.target) &&
			event.target.id !== "dropdownMenuButton"
		) {
			dropdown.hide();
		}
	});

	$('#cpfCnpj').inputmask({
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true,
		'numericInput': true
    });

	$('#cpfCnpj').attr('maxlength', 18);

	$('#conta').inputmask('9999999999999999999-9', {
        'placeholder': '',
        'numericInput': true
    });

    $('#conta').attr('maxlength', 13);

	$('#banco').select2({});
	$('#tipo_conta').select2({});
});

function preencherExportacoes(gridOptions) {
	const formularioExportacoes = document.getElementById("opcoes_exportacao");
	const opcoes = ["csv", "excel", "pdf"];

	let buttonCSV = BaseURL + "/media/img/new_icons/csv.png";
	let buttonEXCEL = BaseURL + "media/img/new_icons/excel.png";
	let buttonPDF = BaseURL + "media/img/new_icons/pdf.png";

	formularioExportacoes.innerHTML = "";

	opcoes.forEach((opcao) => {
		let button = "";
		let texto = "";
		switch (opcao) {
			case "csv":
				button = buttonCSV;
				texto = "CSV";
				margin = "-5px";
				break;
			case "excel":
				button = buttonEXCEL;
				texto = "Excel";
				margin = "0px";
				break;
			case "pdf":
				button = buttonPDF;
				texto = "PDF";
				margin = "0px";
				break;
		}

		let div = document.createElement("div");
		div.classList.add("dropdown-item");
		div.classList.add("opcao_exportacao");
		div.setAttribute("data-tipo", opcao);
		div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px; color: #1C69AD !important; font-family: 'Mont SemiBold';" title="Exportar no formato ${texto}">${texto}</label>
    `;

		div.style.height = "30px";

		div.style.marginTop = margin;

		div.style.borderRadius = "1px";

		div.style.transition = "background-color 0.3s ease";

		div.addEventListener("mouseover", function () {
			div.style.backgroundColor = "#f0f0f0";
		});

		div.addEventListener("mouseout", function () {
			div.style.backgroundColor = "";
		});

		div.style.border = "1px solid #ccc";

		div.addEventListener("click", function (event) {
			event.preventDefault();
			exportarArquivo(opcao, gridOptions, "Relatório de Funcionários");
		});

		formularioExportacoes.appendChild(div);
	});
}

function agGridContasBancarias(id) {
	var agGridTableContasBancarias;

	agGridTableContasBancarias = new AgGridTable(
		tableContasBancariasList,
		paginationSelectContasBancarias,
		listContasBancariasFuncionarioServerSide,
		true,
		(key, item) => {
			if (item == null || item === "") {
				item = "Não informado";
			}

			if (key === "data_cadastro") {
				var date = new Date(item);
				item = date.toLocaleDateString();
			}

			return item;
		},
		{
			id_funcionario: Number(id),
		}
	);

	agGridTableContasBancarias.updateColumnDefs([
		{
			headerName: "ID",
			field: "id",
			filter: true,
			width: 80,
		},
		{
			headerName: "Titular",
			field: "titular",
			minWidth: 290,
			flex: 0.8,
		},
		{
			headerName: "CPF/CNPJ",
			field: "cpf",
			width: 160,
			cellClass: "actions-button-cell",
			cellRenderer: (options) => {
				let item = options.data.cpf;
				
				if(item.length <= 11) {
					item = item.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
				} else {
					item = item.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
				}

				return item;
			}
		},
		{
			headerName: "Banco",
			field: "banco",
			width: 82,
		},
		{
			headerName: "Agência",
			field: "agencia",
			width: 100,
		},
		{
			headerName: "Conta",
			field: "conta",
			width: 150,
		},
		{
			headerName: "Status",
			field: "status",
			width: 120,
			cellClass: "actions-button-cell",
			cellRenderer: function (options) {
				var data = options.data;
				let backStatus;
				switch (data.status) {
					case "0":
						backStatus = `<span class="badge badge-info status_conta status_conta_${data.id_retorno}">Secundária</span>`;
						break;
					case "1":
						backStatus = `<span class="badge badge-success alt status_conta status_conta_${data.id_retorno}">Principal</span>`;
						break;
					default:
						backStatus = `<span class="badge badge-important status_conta status_conta_${data.id_retorno}">Cancelada</span>`;
						break;
				}
				return backStatus;
			}
		},
		{
			headerName: "Operação",
			field: "operacao",
			width: 100,
		},
		{
			headerName: "Tipo",
			field: "tipo",
			width: 100,
		},
		{
			headerName: "Data de Cadastro",
			field: "data_cadastro",
			width: 150,
		},
		{
			headerName: "Ações",
			width: 80,
			pinned: "right",
			cellClass: "actions-button-cell",
			cellRenderer: function (options) {
				var firstRandom = Math.random() * 10;
				var secRandom = Math.random() * 10;
				var thirdRandom = Math.random() * 10;

				var varAleatorio =
					(firstRandom * secRandom).toFixed(0) +
					"-" +
					(thirdRandom * firstRandom).toFixed(0) +
					"-" +
					(secRandom * thirdRandom).toFixed(0);

				let data = options.data;
				let tableId = "tableContasBancariasList";
				let dropdownId =
					"dropdown-menu-" + data.id + "contaBancaria" + varAleatorio;
				let buttonId =
					"dropdownMenuButton_" + data.id + "contaBancaria" + varAleatorio;

				let principal = '';

				if(data.status == 0){
					principal = `<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
						<a data-id_funcionario="${data.id_retorno}" data-id_conta="${data.id}" class="tornarPrincipal" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Tornar Principal</a>
					</div>`;
				}

				let result = `
				<div class="dropdown" style="position: relative;">
						<button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}"
 								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
							<img src="${BaseURL +
					"media/img/new_icons/icon_acoes.svg"
					}" alt="Ações">
						</button>
						<div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-funcionarios" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
							<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
								<a href="javascript:handleBankAccount('${data.id}')" class="btnEditContaBancaria btnEditContaBancaria_${data.id}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
							</div>
							${principal}
						</div>
				</div>`;

				return result;
			},
		},
	]);
	

	$("#formCadContaBancaria").submit(function (e) {
		e.preventDefault();

		let cpf = $("#cpfCnpj").val();
		const charsToRemove = ["_",".", "/", "-"];
		cpf = cpf.replaceAll(new RegExp(`[${charsToRemove.join('')}]`, 'g'), '')

		if(cpf.length < 11) {
			showAlert("warning", "O campo de CPF/CNPJ precisa ter ao menos 11 digitos.");
			return;
		}

		let conta = $("#conta").val();
		conta = conta.replaceAll(new RegExp(`[${["_", "-"].join('')}]`, 'g'), '');

		if(conta.length < 4) {
			showAlert("warning", "O campo Conta precisa ter ao menos 4 digitos.");
			return;
		}

		if(!$("#oper").val()) {
			showAlert("warning", "O campo Operação não pode estar vazio!");
			return;
		}

		if(!$("#titular").val()) {
			showAlert("warning", "O campo Titular da Conta não pode estar vazio!");
			return;
		}

		let validatedForm = {
			titular: $("#titular").val(),
			cpf: Number(cpf),
			banco: $("#banco").val(),
			agencia: $("#agencia").val(),
			conta: Number(conta),
			tipo: $("#tipo_conta").val(),
			operacao: $("#oper").val(),
		}

		let botao = $("#btnCadContaBancaria");
		let formAction = $("#formActionURL").val();
		let status = $(".alert-conta").length ? 1 : 0;
		var dataForm = serializeObject(validatedForm) + "&status=" + status + "&id_retorno=" + id + "&cad_retorno=usuario";
	
		$.ajax({
			url: `${Router}/${formAction}`,
			type: "POST",
			dataType: "json",
			data: dataForm,
			beforeSend: function () {
				botao.attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>');
			},
			success: function (callback) {
				if (callback.success) {
					let actionMsg = "cadastrada";
					if(callback.update) {
						actionMsg = "atualizada";
					}
					agGridTableContasBancarias.refreshAgGrid({
						id_funcionario: id
					});

					showAlert("success", `Conta ${actionMsg} com sucesso!`);
					$("#formCadContaBancaria")[0].reset();
					$('#modalAddContaBancaria').modal('hide');
				} else if(!callback.success && callback.cod) {
					showAlert("warning", "Nenhum dado foi alterado!");
				} else {
					showAlert("error", "Não foi possível completar a requisição: " + callback.msg);
				}
			},
			complete: function () {
				botao.attr("disabled", false).html("Cadastrar");
			},
		});
	});

	$(document).on("click", ".tornarPrincipal", function (e) {
		let botao = $(this);
		let id_conta = botao.attr("data-id_conta");
		let id_funcionario = botao.attr("data-id_funcionario");
		let url = tornaContaBancariaPrincipal;
	
		$.ajax({
			type: "post",
			url: url,
			dataType: "json",
			data: {
				id_conta: id_conta,
				id_funcionario: id_funcionario,
			},
			beforeSend: function () {
				$(".modalLoadingMessageAct").show();
			},
			success: function (callback) {
				if (callback.success) {
					agGridTableContasBancarias.refreshAgGrid({id_funcionario: id_funcionario});
					showAlert("success", "Conta definida como principal!");
					$(".modalLoadingMessageAct").hide();
				} else {
					showAlert("error", "Não foi possível alterar o status da conta.");
					$(".modalLoadingMessageAct").hide();			
				}
			},
			error: function () {
				showAlert("error", "Não foi possível alterar o status da conta.");
				$(".modalLoadingMessageAct").hide();
			},
			complete: function () {
				$(".modalLoadingMessageAct").hide();
			},
		});
	});
	
}

function serializeObject(obj) {
    let queryString = '';
    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            if (queryString.length > 0) {
                queryString += '&';
            }
            let value = typeof obj[key] === 'number' ? String(obj[key]) : obj[key];
            queryString += encodeURIComponent(key) + '=' + encodeURIComponent(value);
        }
    }
    return queryString;
}

async function handleBankAccount(idConta = null) {
    let formAction = idConta ? `editContaBancaria/${idConta}` : "addContaBancaria";
    $("#formActionURL").val(formAction);
    $("#btnCadContaBancaria").html(idConta ? 'Atualizar' : 'Cadastrar');
    $("#title-conta-bancaria-modal").html(idConta ? 'Atualizar Conta Bancária' : 'Cadastrar Conta Bancária');

    if (idConta) {
        await $.ajax({
            url: `${Router}/getUserBankAccount/${idConta}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $(".modalLoadingMessageAct").show();
            },
            success: function(data) {
                let items = data.result;
                if (items != null) {
                    $('#titular').val(items.titular);
                    $('#cpfCnpj').val(items.cpf);
                    $('#banco').val(items.banco);
                    $('#agencia').val(items.agencia);
                    $('#conta').val(items.conta);
                    $('#tipo_conta').val(items.tipo);
                    $('#oper').val(items.operacao);
                } else {
                    showAlert("error", 'Dados não encontrados.');
                    $('#modalAddContaBancaria').modal('hide');
                    return;
                }
            },
            complete: () => $(".modalLoadingMessageAct").hide()
        });
    }

    $('#modalAddContaBancaria').modal('show');
}

$('#modalAddContaBancaria').on('hide.bs.modal', function (e) {
    $("#formCadContaBancaria")[0].reset();
    $("#formActionURL").val("addContaBancaria");
    $("#title-conta-bancaria-modal").html('Cadastrar Conta Bancária');
	$("#btnCadContaBancaria").html('Cadastrar');
});

function compararData() {
	var startDate = $('#data_saida_ferias').val();
	var endDate = $('#data_retorno_ferias').val();

	if (!startDate || !endDate) {
		showAlert('warning', 'Por favor, selecione ambas as datas.')
		return false;
	}

	var start = new Date(startDate);
	var end = new Date(endDate);

	if (start > end) {
		showAlert('warning', 'A data de saída não pode ser superior a data de retorno.')
		return false;
	}

	return true;
}


