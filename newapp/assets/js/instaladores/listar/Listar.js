const tableId = "#table";
const paginationSelect = "#paginationSelect";
const paginationSelectServiceOrder = "#paginationSelectServiceOrder";

var serviceOrderValuesChart = null;

const getListarRelatorioTecnicos = Router + "/getAllInstaladoresServerSide";
const serviceOrderTable = "#tableServiceOrder";
const getListServiceOrder = Router + "/getAllServiceOrderServerSideNew";
const getServiceOrderValues = Router + "/getServiceOrderValuesServerSide";
const getBanks = Router + "/getAllBanks";
const inserirInstalador = Router + "/inserir_instalador_new";

var defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;

let searchOptions = {};

var agGridTable;
var agGridServiceOrder;

class Listar {
	static async stealthLogin(email, password) {
		$("#loading").show();

		var formData = new FormData();
		formData.append("login", email);
		formData.append("senha", password);

		var requestOptions = {
			method: "POST",
			body: formData,
			redirect: "follow",
		};

		await fetch(`${Router}/autlog`, requestOptions)
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					window.open(`${BaseURL}home/instalador`, "_blank");
				} else {
					showAlert("error", "Erro ao logar");
				}
				$("#response").html("");
				$("#loading").hide();
			})
			.catch((error) =>
				console.log(
					"Não consegui me conectar a esta conta! Por favor, recarregue a página e tente novamente..."
				)
			);
	}

	static blockTechnician(id, block) {
		$("#loading").show();
		var formData = new FormData();
		formData.append("id", id);
		formData.append("block", block);

		var requestOptions = {
			method: "POST",
			body: formData,
		};

		fetch("blockTec", requestOptions)
			.then((response) => {
				if (response.ok) {
					$("#modalConfirmacaoBloqueioInstalador").modal("hide");
					agGridTable.refreshAgGrid(searchOptions);
				} else {
					throw new Error("Erro ao bloquear técnico");
				}
				$("#loading").hide();
			})
			.catch((error) =>
				console.error("Erro ao fazer a requisição:", error)
			);
	}

	static async serviceOrderModal(id) {
		$("#loading").show();

		await $.ajax({
			url: getServiceOrderValues,
			type: "POST",
			dataType: "json",
			data: { id: id },
			beforeSend: function (data) {},
			success: function (response) {
				if (response) {
					var dadosObjetos = [
						{ tipo: "Manutenção", valor: response.valorManutencao },
						{ tipo: "Instalação", valor: response.valorInstalacao },
						{ tipo: "Retirada", valor: response.valorRetirada },
						{
							tipo: "Deslocamento",
							valor: response.valorDeslocamento,
						},
					];

					if (serviceOrderValuesChart) {
						serviceOrderValuesChart.destroy();
					}

					const options = {
						container: document.getElementById("chartContainer"),
						autoSize: true,
						data: dadosObjetos,
						series: [
							{
								type: "bar",
								xKey: "tipo",
								yKey: "valor",
								label: {
									fontSize: 12,
									fontWeight: "bold",
								},
								tooltip: {
									renderer: function (params) {
										return (
											'<div class="ag-chart-tooltip-title" style="background-color:' +
											params.color +
											'; font-weight: 900; font-size: 11px;">' +
											params.datum[params.xKey] +
											"</div>" +
											'<div class="ag-chart-tooltip-content">' +
											"Valor: R$ " +
											params.datum[params.yKey].toFixed(
												2
											) +
											"</div>"
										);
									},
								},
							},
						],
						axes: [
							{
								type: "category",
								position: "bottom",
								label: {
									rotation: 0,
								},
							},
							{
								type: "number",
								position: "left",
								label: {
									fontSize: 12,
									fontWeight: "bold",
								},
							},
						],
						legend: {
							enabled: false,
						},
						padding: {
							top: 20,
							right: 20,
							bottom: 20,
							left: 20,
						},
						overlays: {
							noData: {
								text: "Não há dados disponíveis.",
							},
						},
						background: {
							fill: "transparent",
						},
					};

					serviceOrderValuesChart = agCharts.AgCharts.create(options);
					agCharts.AgCharts.update(serviceOrderValuesChart, options);
				}
			},
			error: function (xhr, status, error) {
				console.error("Erro ao buscar os dados: ", error);
			},
		});

		const agGridServiceOrder = new AgGridTable(
			serviceOrderTable,
			paginationSelectServiceOrder,
			getListServiceOrder,
			true,
			(key, item) => {
				if (item == null || item === "") {
					item = "Não informado";
				}
				return item;
			},
			{ id: id }
		);

		agGridServiceOrder.updateColumnDefs([
			{
				headerName: "ID",
				field: "id",
				filter: true,
				width: 55,
			},
			{
				headerName: "Nome Cliente",
				width: 350,
				field: "nome_cliente",
			},
			{
				headerName: "Endereço",
				field: "endereco_destino",
				width: 500,
			},
			{
				headerName: "Solicitante",
				field: "solicitante",
				width: 280,
			},
			{
				headerName: "Tipo",
				field: "tipo_os",
				width: 160,
			},
			{
				headerName: "Status",
				field: "status",
				width: 130,
			},
		]);

		$("#loading").hide();

		$("#modal-service-order").modal();
	}

	static abrirModalConfirmacaoBlock(
		installerIdTemp,
		block,
		action,
		installer
	) {
		let mensagem = `Tem certeza que deseja ${action.toUpperCase()} o instalador ${installer.toUpperCase()} ?`;

		$("#mensagemConfirmacao").html(mensagem);

		$("#btnConfirmarAcaoUsuario")
			.off("click")
			.one("click", function (event) {
				event.stopImmediatePropagation();
				Listar.blockTechnician(installerIdTemp, block);
			});

		$("#modalConfirmacaoBloqueioInstalador").modal("show");
	}

	static documentHandler(instIdDoc) {
		new Document(instIdDoc).init();

		$("#modalGerenciamentoDeDocumentoDoInstalador").modal("show");
	}

	static editarContaBancaria(cpf, installer) {
		$(document).ready(async function () {
			function removeChars(value, chars) {
				const regex = new RegExp(`[${chars.join("")}]`, "g");
				return value.replace(regex, "");
			}

			$(".letters").mask("Z", {
				translation: {
					Z: {
						pattern: /[a-zA-Z áéíóúâêôãõç]/,
						recursive: true,
					},
				},
			});

			$("#newAgency").mask("000000000");
			$("#newOperation").mask("000");
			$("#newAccountNumber").mask("000000000");
			$("#newCPFHolder").mask("000.000.000-00");
			$("#newCNPJHolder").mask("00.000.000/0000-00");

			$("#newDivCNPJHolder").hide();
			$("#newRadioCPFHolder").click(function () {
				$("#newRadioCNPJHolder")[0].checked = false;
				$("#newDivCPFHolder").fadeIn();
				$("#newDivCNPJHolder").hide();
			});
			$("#newRadioCNPJHolder").click(function () {
				$("#newRadioCPFHolder")[0].checked = false;
				$("#newDivCNPJHolder").fadeIn();
				$("#newDivCPFHolder").hide();
			});

			var newCPFHolder = document.getElementById("newCPFHolder");
			var newCNPJHolder = document.getElementById("newCNPJHolder");

			newCPFHolder.addEventListener("keyup", function () {
				newCNPJHolder.disabled = newCPFHolder.value.trim().length > 0;
			});

			newCNPJHolder.addEventListener("keyup", function () {
				newCPFHolder.disabled = newCNPJHolder.value.trim().length > 0;
			});

			const select = $("#newBank");
			select.select2({
				ajax: {
					url: getBanks,
					type: "POST",
					dataType: "json",
					delay: 250,
					data: function (params) {
						return {
							nome: params.term || null,
						};
					},
					processResults: function (data) {
						return {
							results: data.map((row) => ({
								id: row.codigo,
								text: row.nome,
							})),
						};
					},
				},
				placeholder: "Selecione um banco",
				allowClear: true,
				width: "100%",
				language: {
					searching: function () {
						return "Carregando...";
					},
				},
			});

			function validateInput(input, type) {
				if (type === "cpf" && input) {
					return /^\d{11}$/.test(input);
				}
				if (type === "cnpj" && input) {
					return /^\d{14}$/.test(input);
				}
				if (type === "numeric" && input) {
					return /^\d+$/.test(input);
				}
				return input && input.trim().length > 0;
			}

			$("#editarContaConfirmarBtn").off("click");

			$("#editarContaConfirmarBtn").click(async (e) => {
				e.preventDefault();

				const sendButton = $("#editarContaConfirmarBtn");
				sendButton
					.attr("disabled", true)
					.html('<i class="fa fa-spinner fa-spin"></i>');

				const data = {
					cpf: document.getElementById("cCPF").value,
					banco: document.getElementById("newBank").value,
					agencia: document.getElementById("newAgency").value,
					operacao: document.getElementById("newOperation").value,
					conta: document.getElementById("newAccountNumber").value,
					tipo_conta: document.getElementById("newAccountType").value,
					cpf_conta: document.getElementById("newCPFHolder").value,
					cnpj_conta: document.getElementById("newCNPJHolder").value,
					titular_conta:
						document.getElementById("newAccountHolder").value,
				};

				const isCPF =
					document.getElementById("newRadioCPFHolder").checked;
				data.cpf_conta = isCPF
					? removeChars(data.cpf_conta, [".", "-"])
					: null;
				data.cnpj_conta = !isCPF
					? removeChars(data.cnpj_conta, [".", "/", "-"])
					: null;

				if (!validateInput(data.banco, "text")) {
					showAlert("warning", "Banco é obrigatório.");
					return;
				}
				if (!validateInput(data.agencia, "numeric")) {
					showAlert("warning", "Agência deve ser um número.");
					return;
				}
				if (!validateInput(data.conta, "numeric")) {
					showAlert("warning", "Conta deve ser um número.");
					return;
				}
				if (!validateInput(data.tipo_conta, "text")) {
					showAlert("warning", "Tipo de conta é obrigatório.");
					return;
				}
				if (isCPF && !validateInput(data.cpf_conta, "cpf")) {
					showAlert("warning", "CPF inválido.");
					return;
				}
				if (!isCPF && !validateInput(data.cnpj_conta, "cnpj")) {
					showAlert("warning", "CNPJ inválido.");
					return;
				}
				if (!validateInput(data.titular_conta, "text")) {
					showAlert("warning", "Titular da conta é obrigatório.");
					return;
				}

				try {
					const response = await fetch(
						"update_conta_new?cpf=" +
							cpf +
							"&installer_id=" +
							installer,
						{
							method: "POST",
							headers: {
								"Content-Type": "application/json",
							},
							body: JSON.stringify({
								banco: data.banco,
								agencia: data.agencia,
								operacao: data.operacao,
								conta: data.conta,
								tipo_conta: data.tipo_conta,
								cpf_conta: data.cpf_conta,
								cnpj_conta: data.cnpj_conta,
								titular_conta: data.titular_conta,
							}),
						}
					);

					const result = await response.json();

					if (result.status === "success") {
						showAlert("success", "Conta editada com sucesso!");
					} else {
						showAlert("error", "Erro: " + result.message);
					}
					sendButton.attr("disabled", false).html("Salvar");
					$("#modalEditarContaInstalador").modal("hide");
				} catch (error) {
					console.error("Erro ao processar a requisição:", error);
					showAlert(
						"error",
						"Erro ao processar a requisição: " + error.message
					);
					sendButton.attr("disabled", false).html("Salvar");
				}
			});

			$("#modalEditarContaInstalador").modal("show");
		});
	}

	static cadastroInstalador(id = null) {
		$(document).ready(async function () {
			$(".letras").mask("Z", {
				translation: {
					Z: {
						pattern: /[a-zA-Z áéíóúâêôãõç]/,
						recursive: true,
					},
				},
			});
			$("#cCEP").mask("00000-000");
			$("#cNumero").mask("000000000");
			$("#cConta").mask("000000000");
			$("#cAgencia").mask("000000000");
			$("#cOper").mask("000");
			$("#cTelefone").mask("(00) 0000-0000");
			$("#cCelular").mask("(00) 00000-0000");
			$("#cPis").mask("000.00000.00-0");
			$("#cRg").mask("99999999999999");
			$("#cCPF").mask("000.000.000-00");
			$("#cCNPJ").mask("00.000.000/0000-00");
			$("#cCPF_titular").mask("000.000.000-00");
			$("#cCNPJ_titular").mask("00.000.000/0000-00");

			function addPrefix(value) {
				if (!value.startsWith("R$ ")) {
					return "R$ " + value;
				}
				return value;
			}

			function removePrefix(value) {
				return value.replace(/^R\$ /, "");
			}

			$(".money").mask("000.000.000.000.000,00", {
				reverse: true,
				onKeyPress: function (value, e, field, options) {
					field.val(addPrefix(removePrefix(value)));
				},
				onBlur: function (e) {
					let value = $(this).val();
					if (value && !value.startsWith("R$")) {
						$(this).val(addPrefix(value));
					}
				},
			});

			$(".money").each(function () {
				let value = $(this).val();
				$(this).val(addPrefix(value));
			});

			$("#divCNPJ").hide();
			$("#radioCPF").click(function () {
				$("#divCPF").fadeIn();
				$("#divCNPJ").hide();
			});
			$("#radioCNPJ").click(function () {
				$("#divCNPJ").fadeIn();
				$("#divCPF").hide();
			});

			$("#divCNPJ_titular").hide();
			$("#radioCPF_titular").click(function () {
				$("#radioCNPJ_titular")[0].checked = false;
				$("#divCPF_titular").fadeIn();
				$("#divCNPJ_titular").hide();
			});
			$("#radioCNPJ_titular").click(function () {
				$("#radioCPF_titular")[0].checked = false;
				$("#divCNPJ_titular").fadeIn();
				$("#divCPF_titular").hide();
			});

			var cCPF = document.getElementById("cCPF");
			var cCNPJ = document.getElementById("cCNPJ");
			var cCPF_titular = document.getElementById("cCPF_titular");
			var cCNPJ_titular = document.getElementById("cCNPJ_titular");

			cCPF.addEventListener("keyup", function () {
				cCNPJ.disabled = cCPF.value.trim().length > 0;
			});

			cCNPJ.addEventListener("keyup", function () {
				cCPF.disabled = cCNPJ.value.trim().length > 0;
			});

			cCPF_titular.addEventListener("keyup", function () {
				cCNPJ_titular.disabled = cCPF_titular.value.trim().length > 0;
			});

			cCNPJ_titular.addEventListener("keyup", function () {
				cCPF_titular.disabled = cCNPJ_titular.value.trim().length > 0;
			});

			$("#senhaWrapper").show();
			$("#cSenhaWrapper").show();

			const select = $("#cBanco");
			select.select2({
				ajax: {
					url: getBanks,
					type: "POST",
					dataType: "json",
					delay: 250,
					data: function (params) {
						return {
							nome: params.term || null,
						};
					},
					processResults: function (data) {
						return {
							results: data.map((row) => ({
								id: row.codigo,
								text: row.nome,
							})),
						};
					},
				},
				placeholder: "Selecione um banco",
				allowClear: true,
				width: "100%",
				language: {
					searching: function () {
						return "Carregando...";
					},
				},
			});

			function isLegalAge(birthDate, legalAge = 18) {
				const currentDate = new Date();
				const birthDateObj = new Date(birthDate);

				let age =
					currentDate.getFullYear() - birthDateObj.getFullYear();
				const monthDifference =
					currentDate.getMonth() - birthDateObj.getMonth();
				if (
					monthDifference < 0 ||
					(monthDifference === 0 &&
						currentDate.getDate() < birthDateObj.getDate())
				) {
					age--;
				}

				return age >= legalAge;
			}

			async function preencherFormulario(data) {
				function formatCPF(value) {
					if (!value) return "";
					return value.replace(
						/(\d{3})(\d{3})(\d{3})(\d{2})/,
						"$1.$2.$3-$4"
					);
				}

				function formatCNPJ(value) {
					if (!value) return "";
					return value.replace(
						/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/,
						"$1.$2.$3/$4-$5"
					);
				}

				function formatTelefone(value) {
					if (!value) return "";
					return value.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
				}

				function formatCelular(value) {
					if (!value) return "";
					return value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
				}

				function formatPIS(value) {
					if (!value) return "";
					return value.replace(
						/(\d{3})(\d{5})(\d{2})(\d{1})/,
						"$1.$2.$3-$4"
					);
				}

				function formatMoney(value) {
					if (!value) return "0,00";
					return parseFloat(value)
						.toFixed(2)
						.replace(".", ",")
						.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
				}

				$("#cNome").val(data.nome || "");
				$("#cSobrenome").val(data.sobrenome || "");
				document.getElementById("data_nascimento").value =
					data.data_nascimento
						? data.data_nascimento.split(" ")[0]
						: "";
				$("#cEmail").val(data.email || "");
				$("#cTelefone").val(formatTelefone(data.telefone));
				$("#cCelular").val(formatCelular(data.celular));
				$("#cCEP").val(data.cep || "");
				$("#cEndereco").val(data.endereco || "");
				$("#cNumero").val(data.numero || "");
				$("#cBairro").val(data.bairro || "");
				$("#cEstado").val(data.estado || "");
				$("#cCidade").val(data.cidade || "");
				$("#vInstalacao").val(
					`R$ ${formatMoney(data.valor_instalacao)}`
				);
				$("#vManutencao").val(
					`R$ ${formatMoney(data.valor_manutencao)}`
				);
				$("#vRetirada").val(`R$ ${formatMoney(data.valor_retirada)}`);
				$("#vDeslocamento").val(
					`R$ ${formatMoney(data.valor_desloc_km)}`
				);
				$("#tt_conta").val(data.titular_conta || "");
				$("#cCPF").val(formatCPF(data.cpf));
				$("#cCNPJ").val(formatCNPJ(data.cnpj));
				$("#cRg").val(data.rg || "");
				$("#cPis").val(formatPIS(data.pis));

				var selectElement = $("#cEstadoCivil");
				selectElement
					.find("option")
					.filter(function () {
						return $(this).val() == data.estado_civil;
					})
					.prop("selected", true);

				await fetch("getBankByCode?code=" + (data.banco || ""))
					.then((response) => {
						$("#loading").hide();
						if (!response.ok) {
							showAlert("error", "Banco não encontrado!");
							return;
						}
						return response.json();
					})
					.then((bankData) => {
						if (bankData) {
							const option = new Option(
								bankData.nome,
								bankData.codigo,
								true,
								true
							);
							$("#cBanco").append(option).trigger("change");
							const optionEdit = new Option(
								bankData.nome,
								bankData.codigo,
								true,
								true
							);
							$("#newBank").append(optionEdit).trigger("change");
						}
					});

				$("#cAgencia").val(data.agencia || "");
				$("#cConta").val(data.conta || "");
				$("#cOper").val(data.operacao || "");
				$("#cTipoConta").val(data.tipo_conta || "");
				if (data.cpf_conta) {
					$("#cCPF_titular").val(formatCPF(data.cpf_conta));
					$("#radioCPF_titular")
						.prop("checked", true)
						.trigger("click");
				} else if (data.cnpj_conta) {
					$("#cCNPJ_titular").val(formatCNPJ(data.cnpj_conta));
					$("#radioCNPJ_titular")
						.prop("checked", true)
						.trigger("click");
				}

				$("#newAccountHolder").val(data.titular_conta || "");
				$("#newAgency").val(data.agencia || "");
				$("#newOperation").val(data.operacao || "");
				$("#newAccountNumber").val(data.conta || "");
				$("#newAccountType").val(data.tipo_conta || "");
				if (data.cpf_conta) {
					$("#newCPFHolder").val(formatCPF(data.cpf_conta));
					$("#newRadioCPFHolder")
						.prop("checked", true)
						.trigger("click");
				} else if (data.cnpj_conta) {
					$("#newCNPJHolder").val(formatCNPJ(data.cnpj_conta));
					$("#newRadioCNPJHolder")
						.prop("checked", true)
						.trigger("click");
				}

				$("#cCPF").mask("000.000.000-00");
				$("#cCNPJ").mask("00.000.000/0000-00");
				$("#cCPF_titular").mask("000.000.000-00");
				$("#cCNPJ_titular").mask("00.000.000/0000-00");
				$("#cCEP").mask("00000-000");
				$("#cNumero").mask("000000000");
				$("#cConta").mask("000000000");
				$("#cAgencia").mask("000000000");
				$("#cOper").mask("000");
				$("#cTelefone").mask("(00) 0000-0000");
				$("#cCelular").mask("(00) 00000-0000");
				$("#cPis").mask("000.00000.00-0");
				$("#cRg").mask("99999999999999");

				$(".money").mask("000.000.000.000.000,00", {
					reverse: true,
					onKeyPress: function (value, e, field, options) {
						field.val(addPrefix(removePrefix(value)));
					},
					onBlur: function (e) {
						let value = $(this).val();
						if (value && !value.startsWith("R$")) {
							$(this).val(addPrefix(value));
						}
					},
				});
			}

			async function carregarDadosInstalador(id) {
				$("#loading").show();
				try {
					const response = await fetch("editar_install?id=" + id);
					if (!response.ok) {
						$("#loading").hide();
						showAlert(
							"error",
							"Dados do instalador não encontrados!"
						);
						return null;
					}
					const data = await response.json();
					$("#loading").hide();
					return data[0];
				} catch (error) {
					$("#loading").hide();
					showAlert(
						"error",
						"Erro ao carregar os dados do instalador!"
					);
					return null;
				}
			}

			if (id) {
				$("#cadastrarInstaladorTitulo").html("Editar instalador");
				$("#senhaWrapper").hide();
				$("#cSenhaWrapper").hide();
				$("#loading").show();
				$("#editarContaBancariaWrapper").show();

				const inputs = document.querySelectorAll(
					"#tab6 input, #tab6 select"
				);
				inputs.forEach(function (element) {
					element.disabled = true;
				});

				const dadosInstalador = await carregarDadosInstalador(id);
				if (dadosInstalador) {
					document
						.getElementById("editarContaBancariaBtn")
						.addEventListener("click", function (event) {
							event.preventDefault();
							Listar.editarContaBancaria(
								dadosInstalador.cpf_conta,
								id
							);
							var selectElement =
								document.getElementById("cEstadoCivil");
							if (!selectElement.value) {
								selectElement.focus();
							}
						});

					$("#modalEditarContaInstalador").on(
						"hidden.bs.modal",
						function (e) {
							recarregarModalEditarInstalador(id);
						}
					);

					preencherFormulario(dadosInstalador);
				}
			}

			function recarregarModalEditarInstalador(id) {
				carregarDadosInstalador(id).then((dadosInstalador) => {
					if (dadosInstalador) {
						preencherFormulario(dadosInstalador);
					}
				});
			}
			$("#btnSalvarDadosInstalador").off("click");

			$("#btnSalvarDadosInstalador").click(function (e) {
				e.preventDefault();

				function removeChars(value, chars) {
					const regex = new RegExp(`[${chars.join("")}]`, "g");
					return value.replace(regex, "");
				}

				function formatDecimal(value) {
					return value.replace(/\./g, "").replace(",", ".");
				}

				let conta = $("#cConta").val();
				conta = removeChars(conta, ["_", "-"]);

				if (conta.length < 4) {
					showAlert(
						"warning",
						"O campo Conta precisa ter ao menos 4 dígitos."
					);
					return;
				}

				var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (
					$("#cEmail").val() &&
					!emailRegex.test($("#cEmail").val())
				) {
					showAlert(
						"warning",
						"O campo 'Email' não está em um formato válido! Exemplo: seuemail@provedor.dominio"
					);
					return;
				}

				if (!isLegalAge($("#data_nascimento").val())) {
					showAlert(
						"warning",
						"A pessoa precisa ser maior de idade."
					);
					return;
				}

				if (!$("#cOper").val()) {
					showAlert(
						"warning",
						"O campo Operação não pode estar vazio!"
					);
					return;
				}

				if (!$("#tt_conta").val()) {
					showAlert(
						"warning",
						"O campo Titular da Conta não pode estar vazio!"
					);
					return;
				}

				let requiredFields = [
					"cNome",
					"cSobrenome",
					"data_nascimento",
					"cEmail",
					"cTelefone",
					"cCEP",
					"cEndereco",
					"cNumero",
					"cBairro",
					"cEstado",
					"cCidade",
					"vInstalacao",
					"vManutencao",
					"vRetirada",
					"vDeslocamento",
					"cBanco",
					"cAgencia",
					"cTipoConta",
				];
				for (let field of requiredFields) {
					if (!$(`#${field}`).val()) {
						showAlert(
							"warning",
							`O campo ${field
								.replace("c", "")
								.replace("v", "")
								.replace("_", " ")} não pode estar vazio!`
						);
						return;
					}
				}

				let formData = {
					id: id || null,
					nome: $("#cNome").val() || null,
					sobrenome: $("#cSobrenome").val() || null,
					data_nascimento: $("#data_nascimento").val() || null,
					email: $("#cEmail").val() || null,
					senha: $("#cSenha").val() || null,
					rsenha: $("#ccSenha").val() || null,
					telefone: removeChars($("#cTelefone").val() || "", [
						"(",
						")",
						" ",
						"-",
					]),
					celular: removeChars($("#cCelular").val() || "", [
						"(",
						")",
						" ",
						"-",
					]),
					cep: $("#cCEP").val() || null,
					endereco: $("#cEndereco").val() || null,
					numero: $("#cNumero").val() || null,
					bairro: $("#cBairro").val() || null,
					estado: $("#cEstado").val() || null,
					cidade: $("#cCidade").val() || null,
					valor_instalacao: removeChars(
						formatDecimal($("#vInstalacao").val() || ""),
						["R", "$", " ", "-"]
					),

					valor_manutencao: removeChars(
						formatDecimal($("#vManutencao").val() || ""),
						["R", "$", " ", "-"]
					),
					valor_retirada: removeChars(
						formatDecimal($("#vRetirada").val() || ""),
						["R", "$", " ", "-"]
					),
					valor_desloc_km: removeChars(
						formatDecimal($("#vDeslocamento").val() || ""),
						["R", "$", " ", "-"]
					),
					titular_conta: $("#tt_conta").val() || null,
					cpf: $("#cCPF").val()
						? removeChars($("#cCPF").val(), [".", "-"])
						: null,
					cnpj: $("#cCNPJ").val()
						? removeChars($("#cCNPJ").val(), [".", "/", "-"])
						: null,
					rg: $("#cRg").val() || null,
					pis: removeChars($("#cPis").val() || "", [".", "-"]),
					estado_civil: $("#cEstadoCivil").val() || null,
					banco: $("#cBanco").val() || null,
					agencia: $("#cAgencia").val() || null,
					conta: conta ? Number(conta) : null,
					operacao: $("#cOper").val() || null,
					tipo_conta: $("#cTipoConta").val() || null,
					radioDoc: $("#radioCPF").val()
						? $("#radioCPF").val()
						: $("#radioCNPJ").val(),
				};

				if ($("#radioCPF_titular").is(":checked")) {
					formData.cpf_conta = removeChars(
						$("#cCPF_titular").val() || "",
						[".", "-"]
					);
				}
				if ($("#radioCNPJ_titular").is(":checked")) {
					formData.cpf_conta = removeChars(
						$("#cCNPJ_titular").val() || "",
						[".", "/", "-"]
					);
				}

				let botao = $("#btnCadInstalador");

				$.ajax({
					url: inserirInstalador,
					type: "POST",
					dataType: "json",
					contentType: "application/json",
					data: JSON.stringify(formData),
					beforeSend: function () {
						botao
							.attr("disabled", true)
							.html('<i class="fa fa-spinner fa-spin"></i> ');
					},
					success: function (callback) {
						if (callback.status === "success") {
							showAlert(
								"success",
								id
									? "Instalador editado com sucesso!"
									: "Instalador cadastrado com sucesso!"
							);
							$("#signup")[0].reset();
							$("#btnCadInstalador").html("Cadastrar");
							$("#modalCadastroInstalador").modal("hide");
						} else {
							showAlert(
								"error",
								"Não foi possível completar a requisição: " +
									callback.message
							);
						}
					},
					complete: function () {
						botao.attr("disabled", false).html("Cadastrar");
						agGridTable.refreshAgGrid(searchOptions);
					},
				});
			});

			$("#modalCadastroInstalador").modal("show");
		});
	}

	static async Instalador() {
		$(document).ready(function () {
			$("#statusPayment").select2({
				placeholder: "Selecione uma opção",
				allowClear: true,
			});

			$("#estado").select2({
				placeholder: "Selecione um estado",
				allowClear: true,
			});
		});

		$(".search-button").click(async function (e) {
			e.preventDefault();
			searchOptions = {
				statusPagamento: $("#statusPayment").val() || null,
				nomeCompleto: $("#nomeCompleto").val() || null,
				cidade: $("#cidade").val() || null,
				estado: $("#estado").val() || null,
			};

			$(".search-button").blur();
			if (
				searchOptions.statusPagamento ||
				searchOptions.nomeCompleto ||
				searchOptions.cidade ||
				searchOptions.estado
			) {
				let datasource = await agGridTable.getServerSideData(
					searchOptions
				);
				agGridTable.gridOptions.api.setServerSideDatasource(datasource);
			} else {
				showAlert(
					"warning",
					"Preencha algum campo para realizar a pesquisa."
				);
			}
		});

		$(".clear-search-button").click(async function (e) {
			e.preventDefault();

			$("#formBusca").each(function () {
				this.reset();
			});

			$("#statusPayment").val(null).trigger("change");
			$("#estado").val(null).trigger("change");

			const datasource = await agGridTable.getServerSideData({});
			agGridTable.gridOptions.api.setServerSideDatasource(datasource);
		});

		agGridTable = new AgGridTable(
			tableId,
			paginationSelect,
			getListarRelatorioTecnicos,
			true,
			(key, item) => {
				if (item == null || item === "") {
					item = "Não informado";
				}

				if (key == "nota") {
					item = Number(item).toFixed(1);
				}

				return item;
			}
		);

		function currencyFormatter(value) {
			const formatter = new Intl.NumberFormat("pt-BR", {
				style: "currency",
				currency: "BRL",
			});
			return formatter.format(value);
		}

		agGridTable.updateColumnDefs([
			{
				headerName: "ID",
				field: "id",
				filter: true,
				width: 80,
			},
			{
				headerName: "Nome",
				width: 320,
				cellRenderer: (item) => {
					var data = item.data;

					return `<a style="color: #0074e8;" href='ordens_pagamento?id=${data.id}'><i class="fa fa-paper-plane"></i></a>  ${data.nome}`;
				},
			},
			{
				headerName: "Nota",
				field: "nota",
				filter: true,
				width: 130,
				cellRenderer: (item) => {
					let stars;
					const nota = item.data.nota;

					switch (true) {
						case nota == 0:
							stars =
								"<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota >= 0.1 && nota <= 0.9:
							stars =
								"<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota == 1:
							stars =
								"<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota >= 1.1 && nota <= 1.9:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota == 2:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota >= 2.1 && nota <= 2.9:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota == 3:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota >= 3.1 && nota <= 3.9:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota == 4:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-o'></i>";
							break;
						case nota >= 4.1 && nota <= 4.9:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i>";
							break;
						case nota == 5:
							stars =
								"<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i>";
							break;
						default:
							stars =
								"<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
					}

					return `<span class="badge badge-primary">${stars} ${nota}</span>`;
				},
			},
			{
				headerName: "Email",
				field: "email",
				minWidth: 260,
				flex: 0.8,
			},
			{
				headerName: "Cidade",
				field: "cidade",
				width: 160,
			},
			{
				headerName: "Estado",
				field: "estado",
				width: 80,
			},
			{
				headerName: "Telefone",
				field: "telefone",
				width: 150,
				cellRenderer: (item) => {
					let rowTelefone = item.data.telefone;

					let telefone =
						rowTelefone != "Não informado"
							? rowTelefone.replace(/\D/g, "").replaceAll(" ", "")
							: "";

					if (telefone) {
						if (telefone.length == 11) {
							telefone = telefone.replace(
								/(\d{2})(\d{5})(\d{4})/,
								"($1) $2-$3"
							);
						} else if (telefone.length == 10) {
							telefone = telefone.replace(
								/(\d{2})(\d{4})(\d{4})/,
								"($1) $2-$3"
							);
						} else {
							telefone = rowTelefone;
						}
					} else {
						telefone = "Não informado";
					}

					return telefone;
				},
			},
			{
				headerName: "Quantidade de OS",
				field: "qtdDeOs",
				width: 170,
				cellRenderer: (item) => {
					return `<span class='badge badge-warning'>${item.data.qtdDeOs} à pagar</span>`;
				},
			},
			{
				headerName: "Valor Instalação",
				field: "valorInstalacao",
				width: 160,
				cellRenderer: (item) => {
					var value = currencyFormatter(item.data.valorInstalacao);

					return `<span class='badge badge-money'>${value}</span>`;
				},
			},
			{
				headerName: "Valor Manutenção",
				field: "valorManutencao",
				width: 160,
				cellRenderer: (item) => {
					var value = currencyFormatter(item.data.valorManutencao);

					return `<span class='badge badge-money'>${value}</span>`;
				},
			},
			{
				headerName: "Valor Retirada",
				field: "valorRetirada",
				width: 160,
				cellRenderer: (item) => {
					var value = currencyFormatter(item.data.valorRetirada);

					return `<span class='badge badge-money'>${value}</span>`;
				},
			},
			{
				headerName: "Valor Deslocamento",
				field: "valorDeslocamento",
				width: 160,
				cellRenderer: (item) => {
					var value = currencyFormatter(item.data.valorDeslocamento);

					return `<span class='badge badge-money'>${value}</span>`;
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
					let dropdownId =
						"dropdown-menu-" + data.id + "func" + varAleatorio;
					let buttonId =
						"dropdownMenuButton_" + data.id + "func" + varAleatorio;

					var acao = data.block == 1 ? "Bloquear" : "Desbloquear";

					return `
                    <div class="dropdown dropdown-table" style="position: relative;">
                            <button  class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${
									BaseURL +
									"media/img/new_icons/icon_acoes.svg"
								}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-instaladores" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
								<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
									<a href="javascript:Listar.cadastroInstalador('${
										data.id
									}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
								</div>
								<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
									<a href="javascript:Listar.abrirModalConfirmacaoBlock('${data.id}', '${
						data.block
					}', '${acao}', '${
						data.nome
					}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">${acao}</a>
								</div>
								<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
									<a href="javascript:Listar.documentHandler('${
										data.id
									}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Gerir Documentos</a>
								</div>
								
								<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
									<a href="javascript:Listar.serviceOrderModal('${
										data.id
									}')" title='Logar na conta do Usuário' style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Ordens de Serviço</a>
								</div>
                            </div>
                    </div>`;
				},
			},
		]);

		preencherExportacoes(
			agGridTable.gridOptions,
			"Relatório de Instaladores"
		);
	}

	static init() {
		document.addEventListener("DOMContentLoaded", function () {
			const tabs = document.querySelectorAll(
				"#serviceOrderTabs .nav-link"
			);
			const tabContents = document.querySelectorAll(".custom-tab-pane");

			tabs.forEach((tab) => {
				tab.addEventListener("click", function (e) {
					e.preventDefault();
					const target = this.getAttribute("data-target");

					tabContents.forEach((content) => {
						content.style.display = "none";
					});

					tabs.forEach((tab) => {
						tab.classList.remove("active-tab");
					});

					document.querySelector(target).style.display = "block";

					this.classList.add("active-tab");
				});
			});

			$("#modal-service-order").on("show.bs.modal", function () {
				document.querySelector("#serviceOrderTabs .nav-link").click();
			});
		});

		$(document).ready(() => {
			$(".btn-expandir").on("click", function (e) {
				e.preventDefault();
				expandirGrid();
			});

			let menuAberto = false;

			function expandirGrid() {
				menuAberto = !menuAberto;
				let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
				let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

				if (menuAberto) {
					$(".img-expandir").attr("src", buttonShow);
					$(".col-md-3").fadeOut(250, function () {
						$("#conteudo")
							.removeClass("col-md-9")
							.addClass("col-md-12");
					});
				} else {
					$(".img-expandir").attr("src", buttonHide);
					$("#conteudo")
						.removeClass("col-md-12")
						.addClass("col-md-9");
					setTimeout(() => {
						$(".col-md-3").fadeIn(250);
					}, 510);
				}
			}

			Listar.Instalador();
		});

		$("#modalCadastroInstalador").on("hide.bs.modal", function (e) {
			document.getElementById("signup").reset();
			$("#cBanco").val(null).trigger("change");
			$("#newBank").val(null).trigger("change");
			$("#editarContaBancariaWrapper").hide();
			$("#cadastrarInstaladorTitulo").html("Cadastrar instalador");

			const inputs = document.querySelectorAll(
				"#tab6 input, #tab6 select"
			);
			inputs.forEach(function (element) {
				element.disabled = false;
			});

			document.querySelectorAll(".nav-tabs li").forEach(function (tab) {
				tab.classList.remove("active");
			});
			document
				.querySelectorAll(".tab-content .tab-pane")
				.forEach(function (content) {
					content.classList.remove("active");
				});
			document
				.querySelector('.nav-tabs li a[href="#tab1"]')
				.parentElement.classList.add("active");
			document.querySelector("#tab1").classList.add("active");
		});

		document
			.getElementById("cadastrarInstaladorBtn")
			.addEventListener("click", () => Listar.cadastroInstalador());

		$(document).on("shown.bs.dropdown", ".dropdown-table", function () {
			const dropdownId = $(this).find(".dropdown-menu").attr("id");
			const buttonId = $(this).find(".btn-dropdown").attr("id");
			abrirDropdown(dropdownId, buttonId, "table");
		});

		function abrirDropdown(dropdownId, buttonId, tableId) {
			var dropdown = $("#" + dropdownId);

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
					dropdown.css("top", `-${alturaDrop - 50}px`);
				} else {
					let diferenca =
						posDropdown - (posButtonTop - posBordaTabelaTop);
					dropdown.css("top", `-${alturaDrop / 2 - diferenca}px`);
				}
			}
		}
	}
}

Listar.init();




