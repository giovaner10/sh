//VARIÁVEL GLOBAL COM TODOS OS DADOS DE TODOS OS CLIENTES DAS ABAS
var account = [];
var tableProvidencias;
var statusContratos = 0;
// Variável global para guardar a aba que está sendo trabalhada no momento
var abaSelecionada = "01";
var atualizarTabelaContratos;
var carregarQuantidadeContratos;
var camposFocus = []
var listaAbasPreenchidas = ["01"];
var dadosClienteNA = '';
var checaEditModalNA = ''

// Variável para guardar o status das Ocorrências à serem exibidas ao usuário
var incidentStateCode = 0;

//arrays para serem populados e peencher os select2 dos forms
var assuntos = [];
var tecnologias = [];
var vendedores = [];
var canaisVenda = [];
var segmentacoes = [];
// Variável global para guardar uma instância da tabela de Base Instalada
var instanciaTabelasBaseInstalada = [];
var instanciaTabelasCotacao = [];
var instanciaTabelaOcorrencia = [];
var instanciaTabelaProvidencias = [];
var instanciaTabelasFuncoesProgramaveis = [];
var idServicoDoContrato = null //variável para pegar o ID do serviço ao abrir modal de edição da NA
var serialNA = null
var cidadeNA = null //variável para alterar a cidade automaticamente no modal da NA
var anotacoesNA = []

let notepad;
let notepadNote;

var prevent = false;
var clienteShow;

function exibeDivDadosCliente(){
	// Esconde Dados Cliente
	$("#dados_cliente").fadeIn('slow');
}
function escondeDivDadosCliente() {
	// Esconde icone background
	$("#dados_cliente").fadeOut(function () {
		// Exibe dados view
		$("#icone_users").fadeIn('slow');
		limpar_dados_view();
	});
}

$(document).ready(function () {
	jQuery.datetimepicker.setLocale("pt-BR")
	$("#data-minima").datetimepicker({
		formatDate: 'd/m/Y H:i',
		step: 30,
		format: 'd/m/Y H:i'
	})

	$("#data-minima-2").datetimepicker({
		formatDate: 'dd/mm/yy H:i',
		step: 30,
		format: 'd/m/Y H:i'
	})

	$("#data-minima-3").datetimepicker({
		formatDate: 'dd/mm/yy H:i',
		step: 30,
		format: 'd/m/Y H:i'
	})

	$("#data-minima-4").datetimepicker({
		formatDate: 'dd/mm/yy H:i',
		step: 30,
		format: 'd/m/Y H:i'
	})

	$("#data-minima-5").datetimepicker({
		formatDate: 'dd/mm/yy H:i',
		step: 30,
		format: 'd/m/Y H:i'
	})

	preencherArraysSelect2();
	$('#cliente-' + abaSelecionada).addClass('active');
	var filter;
	var dadosClienteAlterar = [[], [], [], [], []];
	//variável para ver se já existe uma instancia da tabela de incidentes
	//essa variável serve para não bugar ao mudar as abas e carregar de novo a tabela
	instanciaTabelaProvidencias[0] = false;
	instanciaTabelaOcorrencia[0] = false;
	instanciaTabelasBaseInstalada[0] = false;
	instanciaTabelasCotacao[0] = false;
	instanciaTabelasFuncoesProgramaveis[0] = false;

	$(".selectPesquisar").select2();

	// Esconder a tabela de atividades de serviço enquanto os dados ainda não estão processados.
	$("#atividadesDeServicoContainer").css({ "display": "none" });

	// MASCARA CPF CNPJ
	var options = {
		onKeyPress: function (cpf, ev, el, op) {
			var masks = ['000.000.000-000', '00.000.000/0000-00'];
			$(".cpf_cpnj").mask((cpf.length > 14) ? masks[1] : masks[0], op);
		}
	}

	$(".cpf_cpnj").length > 11 ? $(".cpf_cpnj").mask('00.000.000/0000-00', options) : $(".cpf_cpnj").mask('000.000.000-00#', options);
	// Evento de click no botão de pesquisa
	$("#formPesquisa").submit(async event  =>  {
		event.preventDefault();

		// Sempre que uma nova pesquisa de cliente for feita, zera a lista de contratos
		// Para evitar um bug que fazia a lista de contratos aparecer no cliente errado
		contratos = [];

		// Esconde dados cliente ao pesquisar
		escondeDivDadosCliente()

		if ($('#pesquisaNome').is(":visible")) {
			let documento = $('#pesqNome').val()

			if (!documento || !documento.length) {
				showAlert("warning","Selecione um dos clientes");
				return false;
			}

			$("#pesquisa").attr("disabled", true).html(ICONS.spinner + " Procurando");
			buscarDados(documento);
		} 
		else if ($('#pesquisaId').is(":visible")) {
			let documento = $('#pesqId').val()

			if (!documento || !documento.length) {
				showAlert("warning","Selecione um dos clientes");
				return false;
			}
			console.log(documento)

			$("#pesquisa").attr("disabled", true).html(ICONS.spinner + " Procurando");
			buscarDados(documento);
		}
		else if ($('#pesquisaUsuario').is(":visible")) {
			let documento = $('#pesqUsuario').val()

			if (!documento || !documento.length) {
				showAlert("warning","Selecione um dos clientes");
				return false;
			}

			$("#pesquisa").attr("disabled", true).html(ICONS.spinner + " Procurando");
			buscarDados(documento);
		}
		else if ($('#pesquisaDoc').is(":visible")) {
			let documento = $("#pesqDoc").val();

			if (!documento || documento == "") {
				showAlert("warning","Informe o CPF ou CNPJ do cliente!");
				return false;
			}
			$("#pesquisa").attr("disabled", true).html(ICONS.spinner + " Procurando");

			buscarDados(documento);
		}

		let numeroAbas = account.filter(function (el) {
			return el != 0;
		}).length;

		//mostra o botão de adicionar abas caso não tenha chegado no limite máximo
		if (numeroAbas < 4) setTimeout(function () { $('#btn-adiconar-aba').css("display", "block"); }, 100);
	});

	async function preencherArraysSelect2() {
		try {
			buscarSegmentacoes();

			canaisVenda = await buscarCanaisVenda();

			tecnologias = await buscarTecnologias();

			assuntos = await buscarAssuntos();

		} catch (error) {
			showAlert("error","Ocorreu um erro ao fazer consultas primárias, a base de dados pode estar apresentando instabilidade.")
		}
	}

	/**
	 * Salva dados do cliente que está sendo exibido para auditoria
	 */
	function salvar_valor_antigo_cliente(dadosCliente) {
		if (dadosCliente) {
			auditoria.valor_antigo_cliente = {
				NomeFantasia_Sobrenome: dadosCliente.fantasyName,
				Cpf_Cnpj: dadosCliente.document,
				InscricaoEstadual: dadosCliente.stateRegistration,
				TipoRelacao: dadosCliente.customertypecode,
				Vendedor: dadosCliente.seller?.Id,
				CanalVenda: dadosCliente.salesChannel?.Id,
				Email: dadosCliente.email,
				enderecoPrincipal: dadosCliente.address,
				bairroPrincipal: dadosCliente.district,
				CepPrincipal: dadosCliente.postalCode?.Name,
				complementoPrincipal: dadosCliente.addressComplement,
				enderecoCobranca: dadosCliente.billingCity,
				bairroCobranca: dadosCliente.billingDistrict,
				CepCobranca: dadosCliente.billingPostalCode?.Name,
				complementoCobranca: dadosCliente.billingAddressComplement,
				enderecoEntrega: dadosCliente.deliveryCity,
				bairroEntrega: dadosCliente.deliveryDistrict,
				CepEntrega: dadosCliente.deliveryPostalCode?.Name,
				complementoEntrega: dadosCliente.deliveryAddressComplement,
				DDD: dadosCliente.ddd,
				Telefone: dadosCliente.telephone,
				DDD2: dadosCliente.ddd2,
				Telefone2: dadosCliente.telephone2,
				DDD3: dadosCliente.ddd3,
				Telefone3: dadosCliente.telephone3,
				DDDCel: dadosCliente.dddCell,
				Celular: dadosCliente.cellPhone,
				DDDFax: dadosCliente.dddfax,
				Fax: dadosCliente.dddfax,
				Id: dadosCliente.Id,
				ClassificacaoCliente: dadosCliente.tipoCliente,
				Segmentacao: dadosCliente.idSegmentation,
			}

			if (buscarDadosClienteAbaAtual()?.entity == 'contacts') {
				auditoria.valor_antigo_cliente.firstname = dadosCliente.firstname;
				auditoria.valor_antigo_cliente.lastname = dadosCliente.lastname;
			} else {
				auditoria.valor_antigo_cliente.Nome = dadosCliente.name;
			}
		}
	}

	// carrega lista de ocorrencias
	$('.nav_ocorrencias').on('click', function (e) {
		listarOcorrencias()

		//esta função força o carregamento dos valores da primeira aba das ocorrências (ativas)
		//caso seja selecionada a aba de canceladas por exemplo, a segunda tabela de ocorrências que for carregada 
		//irá carregar os valores respectivos da aba selecionada anteriormente
		setTimeout(function () {
			$("#active-incidents-link-" + abaSelecionada).trigger('click');
		}, 100);
	});
	$('.nav_grupo_email').on('click', function (e) {
		//limpa o combo box caso já tenha sido feita alguma consulta antes
		$(`#dropdown-grupos-email-${abaSelecionada} select`).empty();
		$(`#dropdown-grupos-email-${abaSelecionada} select`).append('<option>Buscando grupos...</option>');
		//faz a chamada de buscas no controller
		$.getJSON(`${URL_PAINEL_OMNILINK}/listarGruposDeEmail?idCliente=${buscarDadosClienteAbaAtual().Id}`, function (response) {
			$(`#dropdown-grupos-email-${abaSelecionada} select`).empty();
			if (response) {
				switch (response.status) {
					case -1:
						$(`#dropdown-grupos-email-${abaSelecionada} select`).append('<option>Nenhum grupo encontrado</option>');
						//retorna uma mensagem caso não haja nenhum grupo
						showAlert("warning",response.message)
						break;
					case 200:
						let gruposEmail = response.data

						//pega os grupos de e-mail e preeenche o combo box
						gruposEmail.forEach(function (grupo) {
							$(`#dropdown-grupos-email-${abaSelecionada} select`).append('<option value=' + grupo.id + '>' + grupo.nome + '</option>');
						});

						buscarGrupoDeEmail(gruposEmail[0].id)
						break;
					default:
						$(`#dropdown-grupos-email-${abaSelecionada} select`).append('<option>Nenhum grupo encontrado</option>');
						//retorna uma mensagem caso ocorra algum problema
						showAlert("warning",response.message)
						break;
				}

			}

		}).fail(error => {
			showAlert("error","Ocorreu um erro ao buscar os grupos de e-mails,tente novamente em alguns minutos.");
		});
	});


	// Ações de clique das Abas de Ocorrências
	$('.active-incidents').click(function (e) {
		atualizarTabelaOcorrencias(0);
	});

	$('.resolved-incidents').click(function (e) {
		atualizarTabelaOcorrencias(1);
	});

	$('.canceled-incidents').click(function (e) {
		atualizarTabelaOcorrencias(2);
	});

	// carrega visualização de valores por produto
	$(document).on('click', '.visualizar_valores', function (e) {

		$('#LIQUIDADO-' + abaSelecionada).html('<i class="fa fa-spinner fa-spin"></i>');
		$('#VENCIDO-' + abaSelecionada).html('<i class="fa fa-spinner fa-spin"></i>');
		$('#ABERTO-' + abaSelecionada).html('<i class="fa fa-spinner fa-spin"></i>');

		buscarVisaoDosProdutos(buscarDadosClienteAbaAtual()?.document)
			.then(dados => {
				// Renderiza dados da visão dos produtos
				renderizarDadosTabelaVisaoDosProdutos(dados);

				//pega a classe da aba selecionada e adiciona as classes de ocultar valores (para o clique,função a baixo)
				//e adiciona a classe ocultar_valores-x para conseguir trabalhar em cima apenas da classe por aba
				$('.visualizar_valores-' + abaSelecionada)
					.html('<i class="fa fa-eye-slash" style="font-size: 20px;color: darkgray;"></i>')
					.addClass('ocultar_valores ocultar_valores-' + abaSelecionada)
					.removeClass('visualizar_valores');
			})
			.catch(err => {
				$('#LIQUIDADO-' + abaSelecionada).html('R$ -');
				$('#VENCIDO-' + abaSelecionada).html('R$ -');
				$('#ABERTO-' + abaSelecionada).html('R$ -');
				console.error(err);
			});
	});

	// Oculta valores caso usuário click na opção de ocultar
	$(document).on('click', '.ocultar_valores', function (e) {
		$('.ocultar_valores-' + abaSelecionada)
			.html('<i class="fa fa-eye" style="font-size: 20px;color: darkgray;"></i>')
			.addClass('visualizar_valores')
			.removeClass('ocultar_valores');

		$('#LIQUIDADO-' + abaSelecionada).html('R$ -');
		$('#VENCIDO-' + abaSelecionada).html('R$ -');
		$('#ABERTO-' + abaSelecionada).html('R$ -');
	});

	// Carrega lista de Providencias
	$('.nav_providencias').on('click', function (e) {
		instanciarTabelaProvidencia(buscarDadosClienteAbaAtual()?.Id);
	});

	// Carrega lista de base instalada
	$('.nav_base_instalada').on('click', function (e) {
		const permissaoExcluir = permissoes['out_alterarInfoItensContratoOmnilink'];

		if (instanciaTabelasBaseInstalada.length < parseInt(abaSelecionada)) instanciaTabelasBaseInstalada[pegarIndiceDaAbaAtual()] = false;

		if (!instanciaTabelasBaseInstalada[pegarIndiceDaAbaAtual()]) {
			instanciaTabelasBaseInstalada[pegarIndiceDaAbaAtual()] = true;

			tableBaseInstalada = $("#tableBaseInstalada-" + abaSelecionada).DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				language: {
					...lang.datatable,
					sProcessing: '<STRONG id="processando-alert-providencia">Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>',
					searchPlaceholder: 'Pressione ENTER para pesquisar'
				},
				lengthChange: false,
				ordering: false,
				ajax: {
					url: `${URL_PAINEL_OMNILINK}/ajax_listar_bases_instaladas/${retornarTipoDeCliente()}/${buscarDadosClienteAbaAtual()?.Id}`,
					dataSrc: function (receivedData) { //verifica se os dados foram carregados

						if (receivedData) {
							switch (receivedData.status) {

								case 200:
									return receivedData.data; //retorna o que deve ser trabalhado no datatable
								case 0: //sem conexão com o CRM
									showAlert("warning",'Base de dados está apresentando instabilidade, tente novamente em alguns minutos')
									return [];
								default:
									showAlert("error",'Ocorreu um problema ao buscar as bases instaladas, tente novamente em instantes.')
									return [];
							}

						} else {
							showAlert("error",'Ocorreu um problema ao buscar as bases instaladas, tente novamente em instantes.')
							return [];
						}

					},
					error: function (xhr, error, thrown) {
						showAlert("error","Ocorreu um erro ao buscar as bases instaladas, tente novamente mais tarde.")
						return [];
					}
				},
				initComplete: function () {
					$('#tableBaseInstalada' + abaSelecionada + '_filter input')
						.off('.DT')
						.on('keyup.DT', function (e) {
							if (e.keyCode == 13) {
								$("#tableBaseInstalada-" + abaSelecionada).DataTable().search(this.value).draw();
							}
						});
				},
				columns: [
					{ data: 'nome' },
					{ data: 'placa_veiculo' },
					{ data: 'data_instalacao' },
					{ data: 'data_desinstalacao' },
					{ data: 'nome_produto' },
					{ data: 'numero_serie' },
					{
						data: 'id',
						orderable: false,
						render: function (data, type, row, meta) {
							return `
							<button 
								class="btn btn-primary"
								title="Editar Informações Base Instalada"
								style="width: 38px;"
								onclick="getInfoBaseInstalada(this,'${data}')">
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
							</button>
							<button
								class="btn btn-danger"
								title="Remover Base Instalada" 
								onclick="removerBaseInstalada(this, '${data}')"
								${permissaoExcluir ? '' : 'disabled'}>
								<i class="fa fa-trash" aria-hidden="true"></i>
							</button>
							<button data-toggle="modal" data-target="#modalMovidesk"
								class="btn btn-primary"
								title="Abrir Ticket Movidesk" 
								onclick="ticketPersonalizado(this, 'Base')">
								<i class="fa fa-ticket" aria-hidden="true"></i>
							</button>
							`;
						}
					}
				],
				dom: 'Blfrtip',
				buttons: [
					{
						filename: filenameGenerator("Relatório de Base Instalada"),
						extend: 'excelHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5]
						},

						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-excel-o"></i> EXCEL',
						messageTop: function () {
							return `Relatório de Bases Instaladas`;
						},

					},
					{
						filename: filenameGenerator("Relatório de Base Instalada"),
						extend: 'pdfHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-pdf-o"></i> PDF',
						customize: function (doc, tes) {

							var titulo = `Relatório de Base Instalada`;
							pdfTemplateIsolated(doc, titulo);
						}
					},
					{
						filename: filenameGenerator("Relatório de Base Instalada"),
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
						filename: filenameGenerator("Relatório de Base Instalada"),
						extend: 'print',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-print"></i> IMPRIMIR',
						customize: function (win) {
							titulo = `Relatório de Base Instalada`;
							printTemplateOmni(win, titulo);
						}
					}
				],
			});

			tableBaseInstalada.on('processing.dt', function () {
				// Centralizar na tela o Elemento que mostra o carregamento de
				// dados da tabela
				$('.dataTables_processing')[0].scrollIntoView({
					behavior: 'smooth',
					block: 'center',
				});
			});
		} else {
			$("#tableBaseInstalada-" + abaSelecionada).DataTable().ajax.reload();
		}
	});

	// Carrega lista de base instalada
	$('.nav_cotacao').on('click', function (e) {
		// const permissaoExcluir = permissoes['out_alterarInfoItensContratoOmnilink'];
		console.log(buscarDadosClienteAbaAtual())

		if (instanciaTabelasCotacao.length < parseInt(abaSelecionada)) instanciaTabelasCotacao[pegarIndiceDaAbaAtual()] = false;

		if (!instanciaTabelasCotacao[pegarIndiceDaAbaAtual()]) {
			instanciaTabelasCotacao[pegarIndiceDaAbaAtual()] = true;

			console.log("#tableCotacao-" + abaSelecionada)
			console.log($("#tableCotacao-" + abaSelecionada))
			tableCotacao = $("#tableCotacao-" + abaSelecionada).DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				language: {
					...lang.datatable,
					sProcessing: '<STRONG id="processando-alert-providencia">Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>',
					searchPlaceholder: 'Pressione ENTER para pesquisar'
				},
				lengthChange: false,
				ordering: false,
				ajax: {
					url: `${URL_PAINEL_OMNILINK}/ajax_cotacoes/${retornarTipoDeCliente()}/${buscarDadosClienteAbaAtual()?.Id}`,
					dataSrc: function (receivedData) { //verifica se os dados foram carregados

						if (receivedData) {
							switch (receivedData.status) {

								case 200:
									return receivedData.data; //retorna o que deve ser trabalhado no datatable
								case 0: //sem conexão com o CRM
									showAlert("warning",'Base de dados está apresentando instabilidade, tente novamente em alguns minutos')
									return [];
								default:
									showAlert("error",'Ocorreu um problema ao buscar as cotações, tente novamente em instantes.')
									return [];
							}

						} else {
							showAlert("error",'Ocorreu um problema ao buscar as cotações, tente novamente em instantes.')
							return [];
						}

					},
					error: function (xhr, error, thrown) {
						showAlert("error","Ocorreu um erro ao buscar as cotações, tente novamente mais tarde.")
						return [];
					}
				},
				initComplete: function () {
					$('#tableCotacao' + abaSelecionada + '_filter input')
						.off('.DT')
						.on('keyup.DT', function (e) {
							if (e.keyCode == 13) {
								$("#tableCotacao-" + abaSelecionada).DataTable().search(this.value).draw();
							}
						});

				},
				columns: [
					{ data: 'quotenumber' },
					{ data: 'createdon' },
					{ data: 'statecode' },
					{ data: 'tz_analise_credito' },
					{ data: 'tz_valor_total_licenca' },
					{ data: 'tz_valor_total_hardware' },
					{ data: 'effectivefrom' },
					{ data: 'effectiveto' },
					{
						data: 'id',
						orderable: false,
						render: function (data) {
							return `
							<button 
								class="btn btn-primary"
								title="Visualizar Cotação"
								style="width: auto; text-align: center;"
								data-statuscode="${data}"
								onclick="abrirModalResumoCotacao(this)">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</button>`;
						}
					}
				],
				dom: 'Blfrtip',
				buttons: [
					{
						filename: `Relatório de Cotações`,
						title: `Relatório de Cotações`,
						action: function (e, dt, node, config) {
							let self = this;
							let start = dt.page.info().start;
							let length = dt.page.len();

							dt.one('preXhr', function (e, settings, data) {
								data.start = 0;
								data.length = -1;

								dt.one('preDraw', function (e, settings) {
									$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, node, config);

									dt.one('preXhr', function (e, settings, data) {
										data.start = start;
										data.length = length;
									});

									setTimeout(dt.ajax.reload, 0);

									return false;
								});
							});

							dt.ajax.reload();
						},
						extend: 'excelHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-excel-o"></i> EXCEL'
					},
					{
						filename: `Relatório de Cotações`,
						extend: 'pdfHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-pdf-o"></i> PDF',
						customize: function (doc, tes) {
							titulo = `Relatório de Cotações`;
							pdfTemplateIsolated(doc, titulo);
						}
					},
					{
						filename: filenameGenerator("Relatório de Cotações"),
						extend: 'csvHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-code-o"></i> CSV'
					},
					{
						filename: `Relatório de Cotações`,
						extend: 'print',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-print"></i> IMPRIMIR',
						customize: function (win) {
							titulo = `Relatório de Cotações`;
							printTemplateOmni(win, titulo);
						}
					}
				],
			});

			tableCotacao.on('processing.dt', function () {
				// Centralizar na tela o Elemento que mostra o carregamento de
				// dados da tabela
				$('.dataTables_processing')[0].scrollIntoView({
					behavior: 'smooth',
					block: 'center',
				});
			});
		} else {
			$("#tableCotacao-" + abaSelecionada).DataTable().ajax.reload();
		}
	});

	// Carrega lista de base instalada
	$('.nav_funcoes').on('click', function (e) {

		if (instanciaTabelasFuncoesProgramaveis.length < parseInt(abaSelecionada)) instanciaTabelasFuncoesProgramaveis[pegarIndiceDaAbaAtual()] = false;

		if (!instanciaTabelasFuncoesProgramaveis[pegarIndiceDaAbaAtual()]) {
			instanciaTabelasFuncoesProgramaveis[pegarIndiceDaAbaAtual()] = true;

			tableFuncoesProgamaveis = $("#tableFuncoesProgamaveis-" + abaSelecionada).DataTable({
				processing: true,
				serverSide: false,
				responsive: true,
				language: {
					...lang.datatable,
					sProcessing: '<STRONG id="processando-alert-funcoes">Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>',
					searchPlaceholder: 'Pressione ENTER para pesquisar'
				},
				lengthChange: false,
				ordering: false,
				ajax: {
					url: `${URL_PAINEL_OMNILINK}/ajax_grupo_funcoes_programaveis_by_cliente/${buscarDadosClienteAbaAtual()?.codigoClienteShow}`,
					dataSrc: function (receivedData) { //verifica se os dados foram carregados

						if (receivedData) {
							switch (receivedData.status) {

								case 200:
									return receivedData.resultado; //retorna o que deve ser trabalhado no datatable
								case 404: // timeout
									showAlert("warning","Cliente não possui Grupo de funções programáveis associadas!");
									return [];
								case 504: // timeout
									showAlert("warning",'Base de dados está apresentando instabilidade, tente novamente em alguns minutos.')
									return [];
								default:
									showAlert("error",'Ocorreu um problema ao buscar os grupos de funções programáveis, tente novamente em instantes.')
									return [];
							}

						} else {
							showAlert("error",'Ocorreu um problema ao buscar os grupos de funções programáveis, tente novamente em instantes.')
							return [];
						}

					},
					error: function (xhr, error, thrown) {
						showAlert("error","Ocorreu um erro ao buscar os grupos de funções programáveis, tente novamente mais tarde.")
						return [];
					}
				},
				initComplete: function () {
					$('#tableFuncoesProgamaveis' + abaSelecionada + '_filter input')
						.off('.DT')
						.on('keyup.DT', function (e) {
							if (e.keyCode == 13) {
								$("#tableFuncoesProgamaveis-" + abaSelecionada).DataTable().search(this.value).draw();
							}
						});
				},
				columns: [
					{ data: 'id' },
					{ data: 'idGrupoSaver' },
					{ data: 'nome' },
					{
						data: 'id',
						orderable: false,
						render: function (data, type, row) {
							return `
							<button class="btn btn-danger" title="Remover Grupo Função Programável" style="width: 38px;" onclick="removerGrupoFuncaoProgramavel(this, '${data}')">
								<i class="fa fa-trash" aria-hidden="true"></i>
							</button>
							<button class="btn btn-primary" title="Visualizar Detalhes" onclick="exibirFuncoes('${row.idGrupoSaver}')">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</button>
							`;
						}
					}
				],
				dom: 'Blfrtip',
				buttons: [
				
					{
						filename: filenameGenerator(`Relatório Grupo de Funções Programáveis`),
						extend: 'excelHtml5',
						exportOptions: {
							columns: [0, 1, 2]
						},

						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-excel-o"></i> EXCEL',
						messageTop: function () {
							return `Relatório de Grupo de Funções Programáveis`;
						},

					},
					{
						filename: filenameGenerator("Relatório Grupo de Funções Programáveis"),
						extend: 'pdfHtml5',
						exportOptions: {
							columns: [0, 1, 2]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-pdf-o"></i> PDF',
						customize: function (doc, tes) {
							var titulo = `Relatório de Grupo de Funções Programáveis`;
							pdfTemplateIsolated(doc, titulo);
						}
					},
					{
						filename: filenameGenerator("Relatório Grupo de Funções Programáveis"),
						extend: 'csvHtml5',
						exportOptions: {
							columns: [0, 1, 2]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-code-o"></i> CSV'
					},
					{
						filename: filenameGenerator("Relatório Grupo de Funções Programáveis"),
						extend: 'print',
						exportOptions: {
							columns: [0, 1, 2]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-print"></i> IMPRIMIR',
						customize: function (win) {
							titulo = `Relatório de Grupo de Funções Programáveis`;
							printTemplateOmni(win, titulo);
						}
					}
				],
			});

			tableFuncoesProgamaveis.on('processing.dt', function () {
				// Centralizar na tela o Elemento que mostra o carregamento de
				// dados da tabela
				$('.dataTables_processing')[0].scrollIntoView({
					behavior: 'smooth',
					block: 'center',
				});
			});
		} else {
			$("#tableFuncoesProgamaveis-" + abaSelecionada).DataTable().ajax.reload();
		}
	});

	// Carrega lista de contratos
	$('.nav_contratos').on('click', function (e) {
		carregarQuantidadeContratos();
		carregarTabelaContratos();
	});

	// Carrega Atividades de Serviços
	$('.nav_atividades_servico').on('click', function (e) {
		instanciaTabelaNA = false;
		if (!atividadesDeServico[pegarIndiceDaAbaAtual()] === "undefinied") {

			if (atividadesDeServico[pegarIndiceDaAbaAtual()][0].length === 0) instanciaTabelaNA = true;
		} else {
			instanciaTabelaNA = true;
		}

		if (instanciaTabelaOcorrencia) {
			if ($(".alert-processamento").length) {

			} else {
				$(`#btn-cadastro-na-${abaSelecionada}`).css("display", "none")
				$('div .subtitulo').append('<div class="alert alert-info alert-processamento"><strong>Processando...</strong></br><small> Por favor, aguarde.</smal></div>');

				//Atividade de Serviço
				carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)
			}
		}
	});

	/**
	 * Função para chamar a função buscarDadosCliente e verificar se existe um cliente válido
	 * @param {String} filtro 
	 */
	async function buscarDados(filtro) {
		// Guarda ultima pesquisa em memoria para utilização futura
		filter = filtro;

		//faz uma promise com um timeout máximo para a resposta (120 segundos)
		let tempoLimiteBuscaCliente = new Promise(function (resolve) {
			setTimeout(resolve, 120000, "Limite de tempo de busca excedido, a base de dados está apresentando instabilidade.");
		});

		//pega a requisição do cliente buscarDadosCliente e a tempoLimiteBuscaCliente
		//retorna a consulta que tiver o resolve ou reject primeiro
		Promise.race([buscarDadosCliente(filtro), tempoLimiteBuscaCliente]).then(function (retornoPromise) {

			//verifica se a Promise buscarDadosCliente excedeu o limite
			//se a Promise tempoLimiteBuscaCliente retornou mais rápido, o retorno será uma srting
			if (typeof retornoPromise === "string") {
				//alerta do valor passado na Promise
				showAlert("success",retornoPromise)
				// LIMPA O CPF/CNPJ DO CLIENTE
				$("#documentoCliente").val('');
				//  retorna o status do botão
				$("#pesquisa").attr("disabled", false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar')

			} else {
				dadosCliente = retornoPromise;

				if (dadosCliente) {
					//verifica se os valores de "abertos,vencidos e liquidados" do último cliente estão abertas para acionar um clique para resetar os campos
					if ($('#abertos-' + abaSelecionada).hasClass('ocultar_valores')) $('#abertos-' + abaSelecionada).trigger("click");

					//verifica se o cliente possui ao menos uma providência
					if (dadosCliente.providencia) {
						showAlert("success",'Este cliente possui providência(s)!');
						$("#icon_alert_providencia-" + abaSelecionada).css('display', 'inline-block');
					} else {
						$("#icon_alert_providencia-" + abaSelecionada).css('display', 'none');
					}
					if (dadosCliente.entity === 'contacts') {
						if (dadosCliente.firstname === "" || !dadosCliente.firstname) {
							// Exibir mensagem de erro ou tomar alguma ação apropriada
							showAlert("warning","O cliente não possui um nome válido!");
						} else {
							nomeAbaCliente = (dadosCliente.firstname + " " + dadosCliente.lastname).slice(0, 13);
							nomeAbaCliente += " (PF)";
						}
					} else {
						if (!dadosCliente.name) {
							// Exibir mensagem de erro ou tomar alguma ação apropriada
							showAlert("warning","O cliente não possui um nome válido!");
						} else {
							nomeAbaCliente = dadosCliente.name.slice(0, 13) + " (PJ)";
						}
					}


					$('#nome-cliente-' + abaSelecionada).text(nomeAbaCliente);
					$('#nome-cliente-' + abaSelecionada).attr('title', dadosCliente?.document);

					//coloca o select2 nos vendedores
					$("#form-edit-cliente-" + abaSelecionada + " select[name=Vendedor]").select2({
						width: '100%',
						placeholder: "Selecione o vendedor",
						allowClear: true
					});

					//preenche os vendedores apenas se clicar na opção
					$("#form-edit-cliente-" + abaSelecionada + " select[name=Vendedor]").on("select2:opening", function (e) {
						//chama a função para preencher o select2
						popularSelectVendedores();
					});

					popularSelectCanais();
					popularSelectSegmentacao();
					//torna a variável na sessão false para dizer que é necessário carregar a tabela de providencias novamente
					instanciaTabelaProvidencias[pegarIndiceDaAbaAtual()] = false;

					//torna a variável na sessão false para dizer que é necessário carregar a tabela de ocorrências novamente
					instanciaTabelaOcorrencia[pegarIndiceDaAbaAtual()] = false;

					// Salva dados do cliente que será salvo na auditoria
					salvar_valor_antigo_cliente(dadosCliente);

					$("#pesquisa").attr("disabled", false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar')

					let data = {
						dadosCliente,
						dadosAtividadeDeServico: atividadesDeServico[0]
					}
					// Renderiza dados na view
					renderizarView(data);

					//necessário dar um trigger no click para que seja chamada a função para carregar os dados
					if ($("#nav_ocorrencias-" + abaSelecionada).hasClass("active")) {
						//torna a variável false para dizer que é necessário carregar a tabela de incidentes novamente
						instanciaTabelaOcorrencia[pegarIndiceDaAbaAtual()] = false;
						setTimeout(function () {
							$("#nav_ocorrencias-" + abaSelecionada).trigger('click');
						}, 100);

					} else if ($("#nav_providencias-" + abaSelecionada).hasClass("active")) {
						//a tabela já foi instanciada pelo menos uma vez, podendo chamar o clear e destroy
						$('#table_providencias-' + abaSelecionada).DataTable().clear();
						$('#table_providencias' + abaSelecionada).DataTable().destroy();
						//torna a variável false para dizer que é necessário carregar a tabela de incidentes novamente
						instanciaTableIncidentes = false;
						setTimeout(function () {
							$("#nav_providencias-" + abaSelecionada).trigger('click');
						}, 100);

					} else if ($("#nav_dados_cliente_tab-" + abaSelecionada).hasClass("active")) {
						//verifica se a aba (vendas AF) está ativa e clica nela novamente para recarregar
						if ($("#nav_vendas_af-" + abaSelecionada).hasClass("active")) setTimeout(function () { $("#nav_vendas_af-" + abaSelecionada).trigger('click') }, 100);
					}

					// Limpa tabelas de dados
					$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().clear().draw(null, false);

					if ($.fn.DataTable.isDataTable($('#tabelaContratos-' + abaSelecionada).DataTable())) {
						$('#tabelaContratos-' + abaSelecionada).DataTable().destroy();
						$('#tabelaContratos-' + abaSelecionada + ' tbody').html('');

						if ($('#nav_contratos-' + abaSelecionada).hasClass('active')) $("#nav_contratos-" + abaSelecionada).trigger('click');
					}
				}
			}
		}).catch(err => {
			showAlert("error",err);
			// limpa o valor antigo do cliente
			auditoria.valor_antigo_cliente = null;

			//  retorna o status do botão
			$("#pesquisa").attr("disabled", false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar')
		});
	}

	/**
	 * Função que recebe CPF ou CNPJ do cliente e retorna os dados do mesmo
	 * @param {String} filtro 
	 * @returns {Promise}
	 */
	async function buscarDadosCliente(document) {
		return new Promise((resolve, reject) => {
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_get_cliente/get?document=${document}`,
				type: 'GET',
				dataType: 'JSON',
				success: function(callback){
					try {		
						clienteShow = callback.customers.codigoClienteShow;		
						if(callback && callback.code==200 && callback.customers && callback.customers.Id !== null){
							if (typeof callback?.ceabs === 'undefined' || callback?.ceabs === true) {
								resolve(callback.customers);
							} else {
								reject('Você não tem permissão para acessar esse cliente.')
							}
						} else if (callback.code === 0) {
							reject("A base de dados está apresentando instabilidade, tente novamente em alguns minutos.");

						} else {
							reject("Não foi possível buscar os dados do cliente, verifique as informações e tente novamente.");
						}
					} catch (error) {
						reject("Não foi possível buscar os dados do cliente, verifique as informações e tente novamente.");
					}

				},
				error: error => {
					reject(error);
				}
			});
		});
	}

	function formatarCpfCnpj(cpfCnpjRedirecionado) {
		return cpfCnpjRedirecionado.split('"', '').length == 11 ? criarMascara(cpfCnpjRedirecionado, "###.###.###-##") : criarMascara(cpfCnpjRedirecionado, "##.###.###/####-##");
	}

	if (cpfCnpjRedirecionado) {
		const cpfCnpjFormatado = formatarCpfCnpj(String(cpfCnpjRedirecionado));
		$("#pesqDoc").val(cpfCnpjFormatado);
		$("#pesquisa").trigger("click");
	}

	async function buscarTecnologias() {
		return new Promise((resolve, reject) => $.ajax({
			url: `${URL_PAINEL_OMNILINK}/listar_tecnologias/`,
			success: (data) => resolve(data.data.value),
			error: (error) => reject(error),
		}));
	}

	async function buscarVendedores() {
		return new Promise((resolve, reject) => $.ajax({
			url: `${URL_PAINEL_OMNILINK}/ajax_get_vendedores`,
			success: (data) => resolve(data),
			error: (error) => reject(error),
		}));
	}

	async function buscarCanaisVenda() {
		$("#form-edit-cliente-" + abaSelecionada + " select[name=CanalVenda]").children().first().remove();
		return new Promise((resolve, reject) => $.ajax({
			url: `${URL_PAINEL_OMNILINK}/ajax_get_canais_vendas`,
			success: (data) => resolve(data),
			error: (error) => reject(error),
		}));
	}

	function buscarSegmentacoes() {
		$.getJSON(`${URL_PAINEL_OMNILINK}/listar_segmentacoes`, function (segmentacoesResposta) {
			if (segmentacoesResposta) segmentacoes = segmentacoesResposta
		});
	}

	async function buscarPlanos() {
		return new Promise((resolve, reject) => {
			$.getJSON(`${URL_PAINEL_OMNILINK}/ajax_get_planos`, function (data) {
				resolve(data);
			}).fail(error => {
				console.error(error);
				showAlert("error","Erro ao buscar os planos!");
				reject(error);
			});
		});
	}
	/**
	 * Retorna os contratos do cliente
	 * @param {String} document 
	 * @returns {Promise}
	 */
	async function buscarContratos(document) {
		return new Promise((resolve, reject) => {
			$.getJSON(`${URL_API}contract/ResumeContract?document=${document}`, function (data) {
				resolve(data);
			}).fail(error => {
				console.error(error);
				showAlert("error","Erro ao buscar contratos");
				reject(error);
			});
		});
	}

	carregarQuantidadeContratos = function () {
		$('#ContratosTodos-' + abaSelecionada).html('Todos - ...');
		$('#ContratosAtivos-' + abaSelecionada).html('Ativos - ...');
		$('#ContratosAguardando-' + abaSelecionada).html('Aguardando - ...');
		$('#ContratosCancelados-' + abaSelecionada).html('Cancelados - ...');
		$('#ContratosSuspensos-' + abaSelecionada).html('Suspensos - ...');

		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/ajax_listar_quantidade_contratos?tipo=${retornarTipoDeCliente()}&documento=${buscarDadosClienteAbaAtual().document}`,
		}).done(function (data) {
			$('#ContratosTodos-' + abaSelecionada).html('Todos - ' + (data.todos || '0'));
			$('#ContratosAtivos-' + abaSelecionada).html('Ativos - ' + (data.ativos || '0'));
			$('#ContratosAguardando-' + abaSelecionada).html('Aguardando  - ' + (data.aguardando || '0'));
			$('#ContratosCancelados-' + abaSelecionada).html('Cancelados - ' + (data.cancelados || '0'));
			$('#ContratosSuspensos-' + abaSelecionada).html('Suspensos - ' + (data.suspensos || '0'));
		});
	}

	function carregarTabelaContratos() {
		if (!$.fn.DataTable.isDataTable('#tabelaContratos-' + abaSelecionada)) {
			carregarQuantidadeContratos();
			$("#tabelaContratos-" + abaSelecionada).DataTable({
				responsive: true,
				lengthChange: false,
				ordering: false,
				processing: true,
				serverSide: true,
				language: {
					...lang.datatable,
					sProcessing: '<b>Processando... </b><i class="fa fa-spinner fa-spin" style="font-size:24px; color:#06a9f6;"></i>',
					searchPlaceholder: 'Pressione ENTER para pesquisar',
				},
				ajax: {
					url: `${URL_PAINEL_OMNILINK}/ajax_listar_contratos?tipo=${retornarTipoDeCliente()}&documento=${buscarDadosClienteAbaAtual().document}&status=${statusContratos}`,
					type: "GET",
					dataSrc: function (callback) { //verifica se os dados foram carregados

						if (callback) {
							switch (callback.status) {
								case 200:
									return callback.data;
								case 0://sem conexão ao CRM
									showAlert("warning",'Base de dados está apresentando instabilidade, tente novamente em alguns minutos')
									return [];
								default:
									showAlert("warning",'Ocorreu um problema ao buscar os contratos, tente novamente em instantes.')
									return [];
							}
						} else {
							showAlert("warning",'Ocorreu um problema ao buscar os contratos, tente novamente em instantes.')
							return [];
						}

					}
				},
				initComplete: function () {
					$('#tabelaContratos' + abaSelecionada + '_filter input')
						.off('.DT')
						.on('keyup.DT', function (e) {
							if (e.keyCode == 13) {
								$("#tabelaContratos-" + abaSelecionada).DataTable().search(this.value).draw();
							}
						});
				},
				columns: [
					{ data: "codigo" },
					{ data: "placa" },
					{ data: "serial" },
					{ data: "equipamento" },
					{ data: "plano" },
					{ data: "modalidade" },
					{ data: "codVenda" },
					{ data: "dataAtivacao" },
					{
						data: "status",
						render: listarStatusContrato,
					},
					{ data: "statusContrato" },
					{
						data: "id",
						render: function (data, type, row) {
							return listarAcoesContrato(data, row.serial)
						},
					}
				],
				dom: 'Blfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						autoFilter: true,
						className: 'btn btn-success',
						text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i>',
						titleAttr: 'Exportar para o Excel',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 9],
							modifier: {
								page: 'all',
							},
						},
						filename: function () {
							let status;

							switch (statusContratos) {
								case 0:
									status = 'Todos';
									break;
								case 1:
									status = 'Ativo';
									break;
								case 2:
									status = 'Aguardando Ativação';
									break;
								case 3:
									status = 'Cancelado';
									break;
								case 4:
									status = 'Suspenso';
									break;
							}

							retornarTipoDeCliente() === "pj" ? nomeCliente = buscarDadosClienteAbaAtual?.name : nomeCliente = buscarDadosClienteAbaAtual?.firstname
							return `Itens de Contrato (${status})`;
						},
						title: function () {
							let status;


							switch (statusContratos) {
								case 0:
									status = 'Todos';
									break;
								case 1:
									status = 'Ativo';
									break;
								case 2:
									status = 'Aguardando Ativação';
									break;
								case 3:
									status = 'Cancelado';
									break;
								case 4:
									status = 'Suspenso';
									break;
							}

							retornarTipoDeCliente() === "pj" ? nomeCliente = buscarDadosClienteAbaAtual?.name : nomeCliente = buscarDadosClienteAbaAtual?.firstname
							return `Itens de Contrato (${status})`;
						},
						action: function (e, dt, node, config) {
							let self = this;
							let start = dt.page.info().start;
							let length = dt.page.len();

							dt.one('preXhr', function (e, settings, data) {
								data.start = 0;
								data.length = -1;

								dt.one('preDraw', function (e, settings) {
									$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, node, config);

									dt.one('preXhr', function (e, settings, data) {
										data.start = start;
										data.length = length;
									});

									setTimeout(dt.ajax.reload, 0);

									return false;
								});
							});

							dt.ajax.reload();
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-excel-o"></i> EXCEL'
					},
					{
						filename: function () {
							let status;

							switch (statusContratos) {
								case 1:
									status = 'Ativo';
									break;
								case 2:
									status = 'Aguardando Ativação';
									break;
								case 3:
									status = 'Cancelado';
									break;
								case 4:
									status = 'Suspenso';
									break;
							}

							retornarTipoDeCliente() === "pj" ? nomeCliente = buscarDadosClienteAbaAtual?.name : nomeCliente = buscarDadosClienteAbaAtual?.firstname
							return `Itens de Contrato`;
						},
						title: function () {
							let status;


							switch (statusContratos) {
								case 1:
									status = 'Ativo';
									break;
								case 2:
									status = 'Aguardando Ativação';
									break;
								case 3:
									status = 'Cancelado';
									break;
								case 4:
									status = 'Suspenso';
									break;
							}

							retornarTipoDeCliente() === "pj" ? nomeCliente = buscarDadosClienteAbaAtual?.name : nomeCliente = buscarDadosClienteAbaAtual?.firstname
							return `Itens de Contrato`;
						},
						filename: function () {
							let status;

							switch (statusContratos) {
								case 1:
									status = 'Ativo';
									break;
								case 2:
									status = 'Aguardando Ativação';
									break;
								case 3:
									status = 'Cancelado';
									break;
								case 4:
									status = 'Suspenso';
									break;
							}

							retornarTipoDeCliente() === "pj" ? nomeCliente = buscarDadosClienteAbaAtual?.name : nomeCliente = buscarDadosClienteAbaAtual?.firstname
							return `Itens de Contrato`;
						},
						extend: 'pdfHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 9]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-pdf-o"></i> PDF',
						customize: function (doc, tes) {

							var titulo = `Relatório de Contratos`;
							pdfTemplateIsolated(doc, titulo);
						}
					},
					{
						filename: filenameGenerator("Relatório de Contratos"),
						extend: 'csvHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 9]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-file-code-o"></i> CSV'
					},
					{
						filename: filenameGenerator("Relatório de Contratos"),
						extend: 'print',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 9]
						},
						orientation: 'landscape',
						pageSize: 'LEGAL',
						className: 'btn btn-primary',
						text: '<i class="fa fa-print"></i> IMPRIMIR',
						customize: function (win) {
							titulo = `Relatório de Contratos`;
							printTemplateOmni(win, titulo);
						}
					}
				],
			});

			$('#tabelaContratos-' + abaSelecionada).DataTable().on('processing.dt', function () {
				// Centralizar na tela o Elemento que mostra o carregamento de
				// dados da tabela
				$('.dataTables_processing')[0].scrollIntoView({
					behavior: 'smooth',
					block: 'center',
				});
			});
		}
	}

	function listarStatusContrato(status) {
		return `
		<label class="switch" style='opacity: .6;' title="Item de Contrato ${status == 1 ? 'Ativo' : 'Inativo'}">
			<input class="switchStatusItemDeContrato" type="checkbox" ${status == 1 ? 'checked' : ''} disabled>
			<span class="slider"></span>
		</label>
		`;
	}

	function listarAcoesContrato(id, serial) {

		const isDisabled = permissoes['out_alterarInfoItensContratoOmnilink'] ? '' : 'disabled';
		const botaoModalEquipamento = serial === "-" ? `` :
			`<div class="btn-group btn-group-vertical" style="margin-bottom: 2px;">
			<button class="btn btn-primary" style="width: 46px;" title="Consultar informações do Chip" onclick="abrirModalComunicacaoChip(this,'${serial}', ${false})">
				<i class="fa fa-rss" aria-hidden="true" style="padding: 1px"></i>
			</button>
		</div> 
		<div class="btn-group btn-group-vertical" style="margin-bottom: 2px;">
			<button class="btn btn-primary" style="width: 46px; title="Consultar informações do Equipamento" onclick="abrirModalInformaçõesMHS('${serial}',this)">
				<i class="fa fa-file" aria-hidden="true" style="padding: 1px"></i>
			</button>
		</div>
		`

		return `
		<div class="row">
		<div class="col-sm-12">
		<div class="btn-toolbar">
			<div class="btn-group btn-group-vertical" style="margin-bottom: 2px;" >
				<button class="btn btn-primary" title="Visualizar Detalhes" onclick="exibirDadosDoContrato(this,'${id}')">
					<i class="fa fa-eye" aria-hidden="true"></i>
				</button>
			</div>
			<div class="btn-group btn-group-vertical" style="margin-bottom: 2px;">
				<button class="btn btn-primary" title="Editar Item de Contrato" onclick="getInfoEditItemDeContrato(this,'${id}')">
					<i class="fa fa-edit" aria-hidden="true"></i>
				</button>
			</div>
			<div class="btn-group btn-group-vertical" style="margin-bottom: 2px;">
				<button class="btn btn-danger" style="width: 46px;" title="Remover Item de Contrato" onclick="removerItemDeContrato(this,'${id}')" ${isDisabled}>
					<i class="fa fa-trash" aria-hidden="true" style="padding: 1px"></i>
				</button>
			</div>
			<div class="btn-group btn-group-vertical" style="margin-bottom: 2px;">
				<button class="btn btn-primary" title="Enviar ficha de ativação" onclick="EnviaFichaDeAtivacao(this,'${id}')">
					<i class="fa fa-paper-plane" aria-hidden="true"></i>
				</button>
			</div>
			` + botaoModalEquipamento + `
		</div>
		</div>
		</div>
		`;
	}

	atualizarTabelaContratos = function () {
		$('#tabelaContratos-' + abaSelecionada + ' tbody').html('');
		$('#tabelaContratos-' + abaSelecionada).DataTable().search('');
		$('#tabelaContratos-' + abaSelecionada).off('draw.dt').on('draw.dt', function () {
			// Este código será executado após o carregamento da DataTable
			$('#btnfiltroContratos').html('<i class="fa fa-filter" style="font-size: 16px;"></i> Filtrar').attr('disabled', false);
			$('#btnLimparfiltroContratos').html('<i class="fa fa-leaf" style="font-size: 16px;"></i> Limpar').attr('disabled', false);
		});
		$('#tabelaContratos-' + abaSelecionada).DataTable().ajax.url(
			`${URL_PAINEL_OMNILINK}/ajax_listar_contratos?tipo=${retornarTipoDeCliente()}&documento=${buscarDadosClienteAbaAtual().document}&status=${statusContratos}`
		).load();
	}

	/**
	 * Retorna os contratos do cliente
	 * @returns {Promise}
	 */
	async function buscarAssuntos() {
		return new Promise((resolve, reject) => {
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_get_assuntos`,
				type: 'GET',
				success: function (data) {
					data = JSON.parse(data);
					if (data.code == 200) {

						resolve(data.values);
					} else if (data.code == 0) {
						showAlert("warning","A base de dados está apresentando instabilidade, não foi possível buscar os assuntos.");
						reject(data);
					} else {
						showAlert("warning","Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
						reject(data);
					}
				},
				error: function (error) {
					showAlert("warning","Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
					reject(error);
				}
			});
		});
	}

	async function buscarVisaoDosProdutos(document) {
		return new Promise((resolve, reject) => {
			$.getJSON(`${URL_API}customers/getBilling?document=${document}`, function (data) {
				resolve(data);
			})
				.fail(error => {
					console.error(error);
					showAlert("warning","Erro ao buscar visão dos produtos!");
					reject(error);
				});
		});
	}

	/**
	 * Função que renderiza informações na view
	 * @param {Object} data 
	 */
	function renderizarView(data) {
		const { dadosCliente } = data;
		// Renderiza dados do cliente
		renderizarDadosCliente(dadosCliente);
		// Exibe dados Cliente
		exibeDivDadosCliente();

		// Limpa input documento
		$("#pesqDoc").val('');
		// Limpa input nome
		$("#pesqNome").val('').trigger('change');
	}



	async function ajaxCadatrarOcorrencia(data, retorno = true) {
		console.log(retorno)
		return new Promise((resolve, reject) => {
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/cadastrar_ocorrencias`,
				type: "POST",
				data: data,
				dataType: 'json',
				success: async function (callback) {
					if (callback.code == 201){
						
						if (retorno) {
							let flag = $(".myModalLabel").text().includes('Suporte') ? true : false;
							console.log($('#Assunto').find(":selected").text())
							if(flag){
								let route = Router + '/auditoriaCadastroOcorrencia';
								await $.ajax({
									url: route,
									type: 'POST',
									data: {
										idCliente: clienteShow,
										assunto: $('#Assunto').find(":selected").text()
									},
									dataType: 'json',
									success: function (response) {
										if (response.status != 200) {
											console.log(response.resultado);
										}
									},
									error: function (error) {
										reject(error.statusText || 'Erro ao realizar a requisição');
									}
								});
							}
							$('#btnSubmit').html('Salvar');
							$('#btnSubmit').prop('disabled', false);
							$('#form_ocorrencia')[0].reset();
							$("#modalOcorrencia").modal("hide");
							showAlert("success","Cadastro de ocorrência realizado com sucesso.");
							
							//função para recarregar a tabela
							recarregarOcorrencias();
							resolve(true);

						} else {
							resolve(callback.data.incidentid);
						}
					} else {
						$('#btnSubmit').html('Salvar');
						$('#btnSubmit').prop('disabled', false);
						if (callback.data.error.message) {
							alert(callback.data.error.message);
							console.error(callback.data.error.message);
						} else {
							showAlert("error",'Erro ao cadastrar ocorrência!');
						}
						//$('#form_ocorrencia')[0].reset();
						reject(false);
						$('#loading').hide();
					}
				},
				error: function (XMLHttpRequest, erro) {
					$('#btnSubmit').html('Salvar');
					$('#btnSubmit').prop('disabled', false);
					showAlert("error","Erro ao cadastrar ocorrência!");
					$("#modalOcorrencia").modal("hide");
					reject(false);
					$('#loading').hide();
				}
			});
		});
	}

	async function ajaxEditarOcorrencia(data) {
		return new Promise((resolve, reject) => {
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/editar_ocorrencias`,
				type: "POST",
				data: data,
				dataType: 'json',
				success: function (callback) {
					if (callback.code == 200) {
						$('#btnSubmitEdit').html('Salvar');
						$('#btnSubmitEdit').prop('disabled', false);
						showAlert("success","Edição de ocorrência realizado com sucesso.");
						$('#form_ocorrenciaEdit')[0].reset();
						$("#modalOcorrenciaEdit").modal("hide");
						recarregarOcorrencias();
						limparVariaveisFila();
						resolve(true);
					} else {
						$('#btnSubmitEdit').html('Salvar');
						$('#btnSubmitEdit').prop('disabled', false);
						console.error(callback.data.error.message);
						limparVariaveisFila();
						showAlert("error",'Erro ao editar ocorrência!');
						//$('#form_ocorrenciaEdit')[0].reset();
						reject(false);
					}
				},
				error: function (XMLHttpRequest, erro) {
					$('#btnSubmitEdit').html('Salvar');
					$('#btnSubmitEdit').prop('disabled', false);
					showAlert("error","Erro ao editar ocorrência");
					$("#modalOcorrenciaEdit").modal("hide");
					limparVariaveisFila();
					reject(false);
				}
			});
		});
	}


	/**
	 * Renderiza dados do cliente na view
	 * @param {Object} data 
	 */
	function renderizarDadosCliente(data) {
		//coloca as informações do cliente no array de accounts
		account[pegarIndiceDaAbaAtual()] = data
		if (document.getElementById('cliente-' + abaSelecionada).style.display == "none") $('#cliente-' + abaSelecionada).show();

		// PESSOA FÍSICA
		if (buscarDadosClienteAbaAtual()?.entity == 'contacts') {
			$("#NomeCliente-" + abaSelecionada).html(data?.firstname + " " + data?.lastname);
		} else {// PESSOA JURÍDICA
			$("#NomeCliente-" + abaSelecionada).html(data?.name);
		}

		$("#CNPJCliente-" + abaSelecionada).html(data?.document);
		$("#EnderecoCliente-" + abaSelecionada).html(data?.address ?? '');
		$("#BairroCliente-" + abaSelecionada).html(data?.district ?? '');
		$("#CidadeCliente-" + abaSelecionada).html(data?.city?.Name ?? '');
		$("#CEPCliente-" + abaSelecionada).html(data?.postalCode?.Name ?? '');
		$("#UFCliente-" + abaSelecionada).html(data?.city?.Uf ?? '');

		// Situação Financeira
		$('#selectAgendamentoVeic-' + abaSelecionada).val(data?.status_atendimentoriveiculo ? data?.status_atendimentoriveiculo.toString() : 'false');
		$('#selectComunicacaoChip-' + abaSelecionada).val(data?.status_comunicacaochip ? data?.status_comunicacaochip.toString() : 'false');
		$('#selectComunicacaoSatelital-' + abaSelecionada).val(data?.status_comunicacaosatelital ? data?.status_comunicacaosatelital.toString() : 'false');
		$("#statusDataDesbloqueio-" + abaSelecionada).val(data?.status_data_desbloqueio_portal ?? ' -- ');
		$('#selectEmissaoPV-' + abaSelecionada).val(data?.status_emissaopv ? data?.status_emissaopv.toString() : 'false');
		$('#selectBloqueioTotal-' + abaSelecionada).val(data?.status_bloqueiototal ? data?.status_bloqueiototal.toString() : 'false');
		$('#selectDesbloqueioPortal-' + abaSelecionada).val(data?.status_desbloqueioportal ? data?.status_desbloqueioportal.toString() : 'false');

		$("#NomeFantasiaCliente-" + abaSelecionada).html(data?.fantasyName);
		$("#TelefoneCliente-" + abaSelecionada).html(data?.telephone);
		$("#EmailCliente-" + abaSelecionada).html(data?.email);

		$("#SegmentacaoCliente-" + abaSelecionada).html(data?.segmentation ?? '');
		$("#SuporteCliente-" + abaSelecionada).html(data?.analistaSuporte ?? '');
		$("#CodigoClienteZatix-" + abaSelecionada).html(data?.codeERP ?? '');
		$("#Loja-" + abaSelecionada).html(data?.storeERP ?? '');
		$("#CodigoClienteGraber-" + abaSelecionada).html(data?.codigoClienteGraber ?? '');
		$("#CodigoClienteShow-" + abaSelecionada).html(data?.codigoClienteShow ?? '');

		$('#ver-mais-cliente').attr('disabled', false);
		let statusCadCli = (data?.statusCadastro == 0) ? 'ATIVO' : 'INATIVO';

		if (statusCadCli === 'ATIVO') {
			$('#StatusCliente-' + abaSelecionada).css({ 'color': 'green', 'font-weight': 'bold' });
		} else if (statusCadCli === 'INATIVO') {
			$('#StatusCliente-' + abaSelecionada).css({ 'color': 'red', 'font-weight': 'bold' });
		}

		$('#StatusCliente-' + abaSelecionada).html(statusCadCli);

		exibirInformacoesCadastroCliente(data);
		exibirInformacoesGrupoEconomico(data?.grupoEconomico ?? Array());

		//função para validar se algo foi alterado no form do cliente
		$('#form-edit-cliente-' + abaSelecionada + ' select').on('change', function (e) {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})

		$("#selectAtendimentoVeic-" + abaSelecionada).change(function () {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})

		$("#selectComunicacaoSatelital-" + abaSelecionada).change(function () {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})

		$("#selectComunicacaoChip-" + abaSelecionada).change(function () {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})

		$("#statusDataDesbloqueio-" + abaSelecionada).change(function () {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})

		$("#selectEmissaoPV-" + abaSelecionada).change(function () {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})

		$("#selectBloqueioTotal-" + abaSelecionada).change(function () {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})

		$("#selectDesbloqueioPortal-" + abaSelecionada).change(function () {
			dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(this)
		})


		$('#form-edit-cliente-' + abaSelecionada + ' select').on('focus', function (e) {
			if (!camposFocus[this.id]) camposFocus[this.id] = this.value
		})

		$('#form-edit-cliente-' + abaSelecionada + ' input').on('focus', function (e) {
			if (!camposFocus[this.id]) camposFocus[this.id] = this.value
		})

		//função para validar se algo foi alterado no form do cliente
		$('#form-edit-cliente-' + abaSelecionada + ' input').on('blur', function (e) {

			let valorInput = [this]
			let valorInicial = camposFocus[this.id]?.value

			// logica para a atualização de e-mails (grupo de e-mails)
			if (valorInput[0].name.includes("-") && valorInput[0].name.match(/email.*/)) {
				const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g

				if (valorInput[0].value && regex.test(valorInput[0].value)) {
					let camposAlterar = []
					camposAlterar[0] = {}
					camposAlterar[1] = {}

					emailFormatado = valorInput[0].name.slice(0, valorInput[0].name.length - 3)
					indice = emailFormatado.indexOf("l")

					camposAlterar[0].name = emailFormatado
					camposAlterar[0].value = this.value

					// formata o id do email para enviar para o backend
					camposAlterar[1] = $(`#id-email${emailFormatado.slice(indice + 1, emailFormatado.length)}-${abaSelecionada}`)[0]
					camposAlterar[1].name = camposAlterar[1].name.slice(0, camposAlterar[1].name.length - 3)

					if (valorInicial != camposAlterar[0].value)
						dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(...camposAlterar)
				} else {
					showAlert("error","E-mail inválido! O campo não será alterado.")
				}
			} else {
				if (valorInicial != valorInput[0].value)
					dadosClienteAlterar[pegarIndiceDaAbaAtual()].push(...valorInput)
			}
		});
	}

	/**
	 * Evento disparado ao alterar status da situação financeira
	 */
	$(document).on('click', '.avisoPagamento', function (e) {
		let button = $(this);

		if (confirm("Clique em 'OK' para confirmar o aviso de pagamento.")) {
			$('#loading').show();
			atualizaSituacaoFinanceira($(this));

		} else {
			button.html("Aviso de Pagamento");
		}
	});

	$(".tab_contratos_ativos").click(function () {
		if (statusContratos !== 1) statusContratos = 1
		atualizarTabelaContratos();
	});

	$(".tab_contratos_aguardando").click(function () {
		if (statusContratos !== 2) statusContratos = 2
		atualizarTabelaContratos();
	});

	$(".tab_contratos_cancelados").click(function () {
		if (statusContratos !== 3) statusContratos = 3
		atualizarTabelaContratos();
	});

	$(".tab_contratos_suspensos").click(function () {
		if (statusContratos !== 4) statusContratos = 4
		atualizarTabelaContratos();
	});

	$("#btnfiltroContratos").click(function () {
		$(this).html('<i class="fa fa-spinner fa-spin" style="font-size: 16px;"></i> Filtrando...').attr('disabled', true);
		let indice = $('#filtroContratos').val();
		indice = parseInt(indice);
		statusContratos = indice;
		atualizarTabelaContratos();

	});

	$("#btnLimparfiltroContratos").click(function () {
		$(this).html('<i class="fa fa-spinner fa-spin" style="font-size: 16px;"></i> Limpando...').attr('disabled', true);
		statusContratos = 0;
		$('#filtroContratos').val(0);
		atualizarTabelaContratos();
	});

	$(".tab_na_abertas").click(function () {
		renderizarDadosTabelaAtividadesDeServico(atividadesDeServico[pegarIndiceDaAbaAtual()][0]);
	});
	$(".tab_na_fechadas").click(function () {
		renderizarDadosTabelaAtividadesDeServico(atividadesDeServico[pegarIndiceDaAbaAtual()][1]);
	});
	$(".tab_na_canceladas").click(function () {
		renderizarDadosTabelaAtividadesDeServico(atividadesDeServico[pegarIndiceDaAbaAtual()][2]);
	});
	$(".tab_na_agendadas").click(function () {
		renderizarDadosTabelaAtividadesDeServico(atividadesDeServico[pegarIndiceDaAbaAtual()][3]);
	});

	$('#form_ocorrencia').submit(async function (e) {
		e.preventDefault();
		let data = {
			Cliente: buscarDadosClienteAbaAtual()?.Id,
			Assunto: $("#Assunto option:selected").val(),
			nomeAssunto: $("#Assunto option:selected").text(),
			TipoOcorrencia: $("#TipoOcorrencia option:selected").val(),
			OrigemOcorrencia: $("#OrigemOcorrencia option:selected").val(),
			Tecnologia: $("#Tecnologia option:selected").val(),
			Descricao: $("#Descricao").val(),
			isEmpresa: buscarDadosClienteAbaAtual().document.length > 14 ? true : false
		};

		auditoria.valor_novo_ocorrencia = data;

		// Envia O FormData através da requisição AJAX
		$('#btnSubmit').html(ICONS.spinner + ' Salvando');
		$('#btnSubmit').prop('disabled', true);

		salvar_auditoria(`${URL_PAINEL_OMNILINK}/cadastrar_ocorrencias`, 'insert', null, auditoria.valor_novo_ocorrencia)
			.then(async () => {
				await ajaxCadatrarOcorrencia(data);
			})
			.catch(error => {
				$('#btnSubmit').html('Salvar');
				$('#btnSubmit').prop('disabled', false);
				console.error(error);
			});

	});

	$('#form_ocorrenciaEdit').submit(async function (e) {
		e.preventDefault();
		let data = {
			Cliente: buscarDadosClienteAbaAtual()?.Id,
			Id: $("#IdEdit").val(),
			TicketNumber: $("#TicketNumberEdit").val(),
			Assunto: $("#AssuntoEdit option:selected").val(),
			TipoOcorrencia: $("#TipoOcorrenciaEdit option:selected").val(),
			OrigemOcorrencia: $("#OrigemOcorrenciaEdit option:selected").val(),
			Tecnologia: $("#TecnologiaEdit option:selected").val(),
			Descricao: $("#DescricaoEdit").val(),
			Fila: $("#filas").val(),
			NomeFila: $("#fila-nome").val(),
		};

		auditoria.valor_novo_ocorrencia = data;

		$('#btnSubmitEdit').html(ICONS.spinner + ' Salvando');
		$('#btnSubmitEdit').prop('disabled', true);
		salvar_auditoria(`${URL_PAINEL_OMNILINK}/editar_ocorrencias`, 'update', auditoria.valor_antigo_ocorrencia, auditoria.valor_novo_ocorrencia)
			.then(async () => {
				// Envia O FormData através da requisição AJAX
				await ajaxEditarOcorrencia(data);
			})
			.catch(error => {
				console.error(error);
			})

	});


	function renderizarDadosTabelaVisaoDosProdutos(data) {
		// Preenche com o default (0,00)
		$('#LIQUIDADO-' + abaSelecionada).html('R$ 0,00');
		$('#VENCIDO-' + abaSelecionada).html('R$ 0,00');
		$('#ABERTO-' + abaSelecionada).html('R$ 0,00');

		let dados = data && data.Billing ? data.Billing : [];
		if (dados) {
			dados.forEach(dado => {
				if (dado.status) {
					$("#" + dado.status + "-" + abaSelecionada)
						.html(converterParaReal(dado.totalSum));
				}
			});
		}

	}

	/* CLIENTE */
	$('#ver-mais-cliente').on('click', function () {
		$('#modal-cliente').data('tipo', 'editar');
		$('#salvar-cliente').html('Salvar');
	});

	$('#novo-cliente').on('click', function () {
		$('#modal-cliente').data('tipo', 'cadastrar');
		$('#salvar-cliente').html('Cadastrar');
	});

	/**
	 * Exibe informações de cadastro do cliente
	 * @param {Object} cliente 
	 */
	function exibirInformacoesCadastroCliente(cliente) {
		let formCliente = "#form-edit-cliente-" + abaSelecionada;
		// Dados Cliente
		$(formCliente + "input[type=text][name=Id]").val(buscarDadosClienteAbaAtual()?.Id ?? '');

		// PESSOA FÍSICA
		if (buscarDadosClienteAbaAtual()?.entity == 'contacts') {
			// Insere html dos inputs
			$("#row_contacts-" + abaSelecionada).html(`
				<div class="col-md-4 form-group">
					<label class='control-label'>Nome</label>
					<input class="form-control info-cliente" type="text" required maxlength="250" name="firstname" value="" disabled>
				</div>
				<div class="col-md-8 form-group">
					<label class='control-label'>Sobrenome</label>
					<input class="form-control info-cliente" type="text" required maxlength="250" name="lastname" value="" disabled>
				</div>
			`);
			$("#row_accounts").html('');

			$(formCliente + " input[type=text][name=firstname]").val(cliente?.firstname ?? '');
			$(formCliente + " input[type=text][name=lastname]").val(cliente?.lastname ?? '');
			$(formCliente + " input[type=text][name=Nome]").hide();
			$("#infoPJ").hide();
		} else {// PESSOA JURÍDICA
			// Insere html do input
			$("#row_accounts-" + abaSelecionada).html(`
				<div class="col-md-12 form-group">
					<label id="infoPJ" class='control-label'>Nome</label>
					<input class="form-control info-cliente" type="text" required maxlength="250" name="Nome" value="" disabled>
				</div>
			`);
			$("#row_contacts-" + abaSelecionada).html('');
			$(formCliente + " input[type=text][name=Nome]").val(cliente?.name ?? '');
		}
		//form cliente
		$(formCliente + " input[type=text][name=NomeFantasia_Sobrenome]").val(buscarDadosClienteAbaAtual()?.fantasyName ?? '');
		$(formCliente + " input[type=text][name=Cpf_Cnpj]").val(buscarDadosClienteAbaAtual()?.document ?? '');
		$(formCliente + " input[type=text][name=InscricaoEstadual]").val(buscarDadosClienteAbaAtual()?.stateRegistration ?? '');
		$(formCliente + " input[type=text][name=InscricaoMunicipal]").val(buscarDadosClienteAbaAtual()?.inscricaoMunicipal ?? '');
		$(formCliente + " select[name=TipoRelacao]").val(buscarDadosClienteAbaAtual()?.customertypecode ?? '');
		$(formCliente + " input[type=text][name=Email]").val(buscarDadosClienteAbaAtual()?.email ?? '');
		$(formCliente + " input[type=text][name=EmailTelemetria]").val(buscarDadosClienteAbaAtual()?.emailTelemetria ?? '');
		$(formCliente + "  input[type=text][name=EmailAF]").val(buscarDadosClienteAbaAtual()?.emailAF ?? '');
		$(formCliente + " input[type=text][name=EmailLinker]").val(buscarDadosClienteAbaAtual()?.emailLinker ?? '');
		$(formCliente + " input[type=text][name=EmailAlertaCerca]").val(buscarDadosClienteAbaAtual()?.emailAlertaCerca ?? '');
		$(formCliente + " input[type=text][name=site]").val(buscarDadosClienteAbaAtual()?.site ?? '');
		$(formCliente + " input[type=text][name=EmailNovo]").val(buscarDadosClienteAbaAtual()?.emailNovo ?? '');
		$(formCliente + " input[type=text][name=gerenciadoraRiscoNome]").val(buscarDadosClienteAbaAtual()?.gerenciadoraRiscoNome ?? '');

		// verifica se há uma conta primária para adicionar e selecionar ela no select
		if (buscarDadosClienteAbaAtual().contaPrimaria.id) {
			$(formCliente + " select[name=contaPrimaria]").append('<option value="' + buscarDadosClienteAbaAtual()?.contaPrimaria.id + '">' + buscarDadosClienteAbaAtual()?.contaPrimaria?.nome + '</option>');
			$(formCliente + " select[name=conta-primaria]").val(buscarDadosClienteAbaAtual()?.contaPrimaria.id ?? '');
		}

		$(formCliente + " select[name=analistaSuporte]").append('<option value="' + buscarDadosClienteAbaAtual()?.analistaSuporte.id + '">' + buscarDadosClienteAbaAtual()?.analistaSuporte.nome + '</option>');
		$(formCliente + " select[name=analistaSuporte]").val(buscarDadosClienteAbaAtual()?.analistaSuporte.id ?? '');

		$(formCliente + " select[name=formaCobranca]").append('<option value="' + buscarDadosClienteAbaAtual()?.formaCobranca.id + '">' + buscarDadosClienteAbaAtual()?.formaCobranca.nome + '</option>');
		$(formCliente + " select[name=formaCobranca]").val(buscarDadosClienteAbaAtual()?.formaCobranca.id ?? '');

		$(formCliente + " select[name=envioSustentavel]").val(buscarDadosClienteAbaAtual()?.envioSustentavel ?? '');
		$(formCliente + " select[name=gerenciadoraDeRisco]").val(buscarDadosClienteAbaAtual()?.gerenciadoraDeRisco ?? '');
		$(formCliente + " select[name=segmentacaoManual]").val(buscarDadosClienteAbaAtual()?.segmentacaoManual ?? '');
		$(formCliente + " select[name=particularidade]").val(buscarDadosClienteAbaAtual()?.particularidade ?? '');
		$(formCliente + " input[name=nomeResponsavel]").val(buscarDadosClienteAbaAtual()?.nomeResponsavel ?? '');
		$(formCliente + " input[name=cargoResponsavel]").val(buscarDadosClienteAbaAtual()?.cargoResponsavel ?? '');

		// Contato
		let telefone = '(' + (cliente.ddd ?? '') + ')' + (cliente.telephone ? cliente?.telephone.slice(0, 4) + '-' + cliente?.telephone.slice(4) : '');
		$(formCliente + " input[type=text][name=telefone]").val(telefone);

		let telefone2 = '(' + (cliente.ddd2 ?? '') + ')' + (cliente?.telephone2 ? cliente?.telephone2.slice(0, 4) + '-' + cliente?.telephone2.slice(4) : '');
		$(formCliente + " input[type=text][name=telefone2]").val(telefone2);

		let telefone3 = '(' + (cliente.ddd3 ?? '') + ')' + (cliente?.telephone3 ? cliente?.telephone3.slice(0, 4) + '-' + cliente?.telephone3.slice(4) : '');
		$(formCliente + " input[type=text][name=telefone3]").val(telefone3);

		let celular = '(' + (cliente.dddCell ?? '') + ')' + (cliente?.cellPhone ? cliente?.cellPhone.slice(0, 5) + '-' + cliente?.cellPhone.slice(5) : '');
		$(formCliente + " input[type=text][name=celular]").val(celular);

		let fax = '(' + (cliente.dddfax ?? '') + ')' + (cliente?.fax ? cliente?.fax.slice(0, 4) + '-' + cliente?.fax.slice(4) : '');
		$(formCliente + " input[type=text][name=fax]").val(fax);

		//verifica se possui um vendedor para preencher
		if (cliente?.seller?.Id) {
			$(formCliente + " select[name=Vendedor]").append('<option value="' + cliente?.seller?.Id + '">' + cliente?.seller?.Nome + '</option>');
			$(formCliente + " select[name=Vendedor]").val(cliente?.seller?.Id ?? '');
		}
		// SETA NOME DO VENDEDOR
		$("#VendedorCliente-" + abaSelecionada).html($(formCliente + " select[name=Vendedor] option:selected").text() ?? '');

		$(formCliente + " select[name=CanalVenda]").val(cliente?.salesChannel?.Id ?? '');
		$(formCliente + " select[name=ClassificacaoCliente]").val(cliente?.tipoCliente ?? '');
		$(formCliente + " select[name=Segmentacao]").val(cliente?.idSegmentation ?? '').trigger('change');
		$(formCliente + " select[name=Segmentacao]").val(cliente?.idSegmentation ?? '').trigger('change');

		// Endereço Principal
		$(formCliente + " input[type=text][name=CepPrincipal]").val(cliente?.postalCode?.Name ?? '');
		$(formCliente + " input[type=text][name=enderecoPrincipal]").val(cliente?.address ?? '');
		$(formCliente + " input[type=text][name=bairroPrincipal]").val(cliente?.district ?? '');
		$(formCliente + " input[type=text][name=complementoPrincipal]").val(cliente?.addressComplement ?? '');

		// Endereço Cobrança
		$(formCliente + " input[type=text][name=CepCobranca]").val(cliente?.billingPostalCode?.Name ?? '');
		$(formCliente + " input[type=text][name=enderecoCobranca]").val(cliente?.billingaddress ?? '');
		$(formCliente + " input[type=text][name=bairroCobranca]").val(cliente?.billingDistrict ?? '');
		$(formCliente + " input[type=text][name=complementoCobranca]").val(cliente?.billingAddressComplement ?? '');

		// Endereço Entrega
		$(formCliente + " input[type=text][name=CepEntrega]").val(cliente?.deliveryPostalCode?.Name ?? '');
		$(formCliente + " input[type=text][name=enderecoEntrega]").val(cliente?.deliveryAddress ?? '');
		$(formCliente + " input[type=text][name=bairroEntrega]").val(cliente?.deliveryDistrict ?? '');
		$(formCliente + " input[type=text][name=complementoEntrega]").val(cliente?.deliveryAddressComplement ?? '');

		$(formCliente + " select[name=Vendedor]").trigger('change');
		$(formCliente + " select[name=CanalVenda]").trigger('change');
	}

	/**
	 * Exibe informações do grupo econômico do cliente
	 * @param {Array} grupoEconomico
	 */
	function exibirInformacoesGrupoEconomico(grupoEconomico) {
		const lista = $('#listaGrupoEconomico');
		lista.html('');
		grupoEconomico.forEach(ge => {
			if (ge && ge.zatix_cnpj) {
				lista.append(`<a class="linkGrupoEconomico" onclick="buscarDadosGrupoEconomico('${ge.zatix_cnpj}')"><li>${ge.zatix_cnpj}</li></a>`);
			}
		});
	}

	/**
	 * Libera inputs para edição do cliente
	 */
	$(".btn_edit_cliente").click(function () {
		if (!tabAssociados) {
			$(this).attr("disabled", true);
			switchInputsClientes(false);
			$("#buttons_edit_cliente-" + abaSelecionada).show();
		} else {
			limparModalContatoAssociado();
			$('#info-cliente').html($('#NomeCliente').text() + ' - ' + $('input[name=Cpf_Cnpj]').val());
			$('#modal-contato-associado').modal('show');
		}
	});

	/**
	 * Habilita ou Desabilita os inputs e selects do formulário de editar cliente
	 */
	function switchInputsClientes(disable) {
		formClienteAbaSelecionada = "#form-edit-cliente-" + abaSelecionada;
		// Habilita inputs
		$(formClienteAbaSelecionada).find("input").attr('disabled', disable);
		// Habilita selects
		$(formClienteAbaSelecionada).find("select").attr('disabled', disable);
		// habilita e desabilita botão de permitir edição dos dados do cliente
		$("#btn_edit_cliente-" + abaSelecionada).attr("disabled", !disable);
		// Esconde botões de salvar e cancelar
		$("#buttons_edit_cliente-" + abaSelecionada).hide();

		$(".alterStatus").attr('disabled', disable);

		//verifica se o usuário possui a permissão para alterar esses campos
		if (!permissoes['edi_alterarcadastrodecliente']) {
			$(formClienteAbaSelecionada + " select[name=Segmentacao]").attr('disabled', true);
			$(formClienteAbaSelecionada + " select[name=analistaSuporte]").attr('disabled', true);
			$(formClienteAbaSelecionada + " select[name=formaCobranca]").attr('disabled', true);
			$(formClienteAbaSelecionada + " select[name=segmentacaoManual]").attr('disabled', true);
			$(formClienteAbaSelecionada + " select[name=particularidade]").attr('disabled', true);
			$(formClienteAbaSelecionada + " select[name=gerenciadoraDeRisco]").attr('disabled', true);

		}
		//função do arquivo selects-painel-omnilink.js
		gerarSelect2FormCliente(formClienteAbaSelecionada + " select[name=contaPrimaria]", "recuperarClientesCRM")
		gerarSelect2FormCliente("#analista-suporte-" + abaSelecionada, "buscarSystemUser")
		gerarSelect2FormCliente(formClienteAbaSelecionada + " select[name=formaCobranca]", "buscarFormasCobranca")

		//este campo NÃO deve ser alterado, se for alterado irá gerar diversos updates em cascata
		//podendo quebrar edições futuras e o próprio cadastro do cliente
		$(formClienteAbaSelecionada + " input[type=text][name=Nome]").attr('disabled', true);

		//não é recomendável alterar este campo, como se trata de uma informação que é atrelada ao CNPJ, em tese não deve ser alterada
		$(formClienteAbaSelecionada + " input[type=text][name=InscricaoEstadual]").attr('disabled', true)
		$(`#dropdown-grupos-email-${abaSelecionada} select`).attr("disabled", false)
	}

	/**
	 * Cancelar edição do cliente
	 */
	$(".cancelar-cliente").click(function () {

		switchInputsClientes(true, this.id.slice(-2));

		exibirInformacoesCadastroCliente(buscarDadosClienteAbaAtual());
	});

	/**
	 * Submit modal editar cliente
	 */
	$(".salvar-cliente").click(async function (e) {
		if (validarInputsCadastroCliente()) {
			e.preventDefault();
			let botao = $("#salvar-cliente-" + abaSelecionada);
			let htmlBotao = botao.html();
			botao.html(ICONS.spinner + ' ' + htmlBotao);
			botao.attr('disabled', true);

			if (dadosClienteAlterar[pegarIndiceDaAbaAtual()].length > 0) {
				let data = {};
				dadosClienteAlterar[pegarIndiceDaAbaAtual()].forEach(item => {
					if (item.name.match(/email.*/) && !data['id-emails'])
						data['id-emails'] = $(`#id-emails-${abaSelecionada}`).val()

					data[item.name] = item.value;
				});

				//TELEFONES
				if (data.telefone) {
					data.telefone = data.telefone.replace(/[ \-]/g, '');
					data.DDD = data.telefone.slice(data.telefone.indexOf('(') + 1, data.telefone.indexOf(')'));
					data.DDD = data.DDD.slice(-2);
					data.Telefone = data.telefone.slice(data.telefone.indexOf(')') + 1).replace('-', '');
					delete data.telefone;
				}

				if (data.telefone2) {
					data.telefone2 = data.telefone2.replace(/[ \-]/g, '');
					data.DDD2 = data.telefone2.slice(data.telefone2.indexOf('(') + 1, data.telefone2.indexOf(')'));
					data.DDD2 = data.DDD2.slice(-2);
					data.Telefone2 = data.telefone2.slice(data.telefone2.indexOf(')') + 1).replace('-', '');
				}
				delete data.telefone2;

				if (data.telefone3) {
					data.telefone3 = data.telefone3.replace(/[ \-]/g, '');
					data.DDD3 = data.telefone3.slice(data.telefone3.indexOf('(') + 1, data.telefone3.indexOf(')'));
					data.DDD3 = data.DDD3.slice(-2);
					data.Telefone3 = data.telefone3.slice(data.telefone3.indexOf(')') + 1).replace('-', '');
				}
				delete data.telefone3;

				if (data.celular) {
					data.celular = data.celular.replace(/[ \-]/g, '');
					data.DDDCel = data.celular.slice(data.celular.indexOf('(') + 1, data.celular.indexOf(')'));
					data.DDDCel = data.DDDCel.slice(-2);
					data.Celular = data.celular.slice(data.celular.indexOf(')') + 1).replace('-', '');
				}
				delete data.celular;

				if (data.fax) {
					data.fax = data.fax.replace(/[ \-]/g, '');
					data.DDDFax = data.fax.slice(data.fax.indexOf('(') + 1, data.fax.indexOf(')'));
					data.DDDFax = data.DDDFax.slice(-2);
					data.Fax = data.fax.slice(data.fax.indexOf(')') + 1).replace('-', '');
				}
				delete data.fax;

				if (data.CepPrincipal && data.CepPrincipal != "") data.CepPrincipal = data.CepPrincipal.replace(/[.-]/g, '');
				if (data.CepEntrega && data.CepEntrega != "") data.CepEntrega = data.CepEntrega.replace(/[.-]/g, '');
				if (data.CepCobranca && data.CepCobranca != "") data.CepCobranca = data.CepCobranca.replace(/[.-]/g, '');

				data.Id = buscarDadosClienteAbaAtual()?.Id;

				data.Cpf_Cnpj = buscarDadosClienteAbaAtual()?.document;

				// Guarda os novos dados do cliente para auditoria
				auditoria.valor_novo_cliente = data;
				let url = `${URL_PAINEL_OMNILINK}/ajax_atualizar_cliente`;
				salvar_auditoria(url, 'update', auditoria.valor_antigo_cliente, auditoria.valor_novo_cliente)
					.then(() => {
						$.ajax({
							url,
							type: 'POST',
							dataType: 'json',
							data,
							success: function (resposta) {
								if (resposta.code == 200) {
									showAlert("success",'Cliente atualizado com sucesso!');
									$('#modal-cliente').modal('hide');
									salvar_valor_antigo_cliente(resposta.value);
									switchInputsClientes(true);
								} else {
									switchInputsClientes(true);
									showAlert("error",resposta.error.error.message)
									exibirInformacoesCadastroCliente(buscarDadosClienteAbaAtual());
								}
							},
							error: function (err) {
								showAlert("error",`Falha ao atualizar cliente!`);
								switchInputsClientes(true);
								exibirInformacoesCadastroCliente(buscarDadosClienteAbaAtual());
							},
							complete: function () {
								botao.html(htmlBotao);
								botao.attr('disabled', false);
							},
						});
					})
					.catch(error => {
						console.error(error);
						botao.html(htmlBotao);
						botao.attr('disabled', false);
					});
			} else {
				showAlert("error","Nenhum campo foi alterado, as informações do cliente não foram modificadas.");
				botao.html(htmlBotao);
				botao.attr('disabled', false);
				switchInputsClientes(true);
			}

		} else {
			return false;
		}
	});

	/**
	 * Atualiza situação financeira do Cliente
	 * @returns 
	 */
	function atualizaSituacaoFinanceira(button) {
		// Cadastra a ocorrência de solicitação
		let dados_ocorrencia = {
			Cliente: buscarDadosClienteAbaAtual()?.Id,
			Assunto: 'a2e16db4-d155-eb11-b8fa-005056ba183f',
			TipoOcorrencia: 3,
			OrigemOcorrencia: 4,
			Tecnologia: '394e0389-76b8-e911-95e6-005056ba64fc',
			Descricao: 'Aviso de Pagamento (SHOWNET) - ',
			isEmpresa: retornarTipoDeCliente() === "pj" ? true : false,
			nomeAssunto: "DESBLOQUEIO SINAL - CHAT"
		};

		auditoria.valor_novo_ocorrencia = dados_ocorrencia;

		ajaxCadatrarOcorrencia(dados_ocorrencia, false).then((resposta) => {
			var incidentId = resposta;

			if (incidentId) {
				let data = {
					document: buscarDadosClienteAbaAtual().document.replace(/[^0-9]/g, '')
				}

				$.post(`${URL_PAINEL_OMNILINK}/avisoDePagamentoCRM`, data, function (callback) {
					if (callback.Status === 200) {
						let dados_close = {
							Id: incidentId,
							TipoResolucao: 5,
							Subject: 'Resolvido Automaticamente',
							Descricao: 'Shownet'
						}
						ajaxEncerarOcorrencia(dados_close);
						recarregarOcorrencias();
						showAlert("success",'Liberação realizada com sucesso.');

						$('#selectAtendimentoVeic').val('true');
						$('#selectComunicacaoChip').val('true');
						$('#selectComunicacaoSatelital').val('true');
						$('#selectEmissaoPV').val('true');
						$('#selectBloqueioTotal').val('false');
						$('#loading').hide();
					} else {
						showAlert("error",'Não foi possível realizar a liberação, por favor verifique os dados e tente novamente!');
						$('#loading').hide();
					}
					$('#loading').hide();
				}, "JSON");
			} else {
				$('#loading').hide();
			}
		});

	}

	/**
	 * Verifica se o formulário é válido
	 * @returns 
	 */
	function validarInputsCadastroCliente() {
		let isValid = true;
		$('#form-edit-cliente').find('select,input').filter('[required]').each(function () {
			if (!$(this).val() || $(this).val() == "") {
				findInvalidInputFormCadastroCliente($(this));
				isValid = false;
				return true; // sai do loop
			}
		});
		return isValid;
	}
	/**
	 * Seleciona input ou select required que está sem valor
	 * @param {*} element 
	 */
	function findInvalidInputFormCadastroCliente(element) {
		// remove a seleção da aba
		$(".tab-pane-cliente").removeClass('active');
		$(".nav-tab-cliente").removeClass('active');
		// elemento invalido
		const invalidInput = element;
		const inputName = invalidInput.attr('name');
		// aba onde o elemento inválido se encontra
		const tabpane = invalidInput.parents('.tab-pane');
		const idNavTab = invalidInput.parents('.tab-pane').data('nav-tab');
		// seleciona a aba do elemento inválido
		$(tabpane).addClass('active');
		$(`#${idNavTab}`).addClass('active');
		// exibe mensagem validade
		document.getElementsByName(inputName)[0].reportValidity();
	}

	//INICIALIZAÇÃO DOS SELETORES
	async function popularSelectVendedores() {
		selectVendedor = "#form-edit-cliente-" + abaSelecionada + " select[name=Vendedor]";
		let quantidadeDeOptions = $(selectVendedor + " option").length;
		//verifica se já foram adicionados os vendedores a esta aba
		if (quantidadeDeOptions === 1 || quantidadeDeOptions === 2) {//sempre inicia com 1 *carregando...* e possivelmente com 2 *vendedor do cliente*
			//pega o numero da aba atual e o seu respectivo form
			let seletorVendedores = $(selectVendedor);
			if (vendedores.length === 0) {
				await buscarVendedores().then(vendedoresResposta => {
					vendedores = vendedoresResposta;
					//fecha o select2 com o carregando para reabrir preenchdio
					seletorVendedores.select2('close');
				});
			}
			//remove o "carregando"
			$(selectVendedor).children().first().remove();

			//remove o vendedor do cliente que foi adicionado anteriormente para não ficar duplicado
			if (quantidadeDeOptions === 2) {
				$(selectVendedor).children().first().remove();
				//seleciona o vendedor novamente, agora com todos os vendedores 
			}

			JSON.parse(vendedores).values.forEach(vendedor => {
				seletorVendedores.append(new Option(vendedor.tz_name, vendedor.tz_vendedorid, false, false));
			});

			//da um timeout para reabrir o select2 preenchido
			setTimeout(function () { seletorVendedores.select2('open'); }, 50);
			//seleciona o vendedor do cliente
			if (buscarDadosClienteAbaAtual()?.seller.Id) $(selectVendedor).val(buscarDadosClienteAbaAtual()?.seller.Id);
		}

	}

	function popularSelectCanais() {
		let quantidadeDeOptions = $("#form-edit-cliente-" + abaSelecionada + " select[name=CanalVenda] option").length;
		//verifica se já foram adicionados os vendedores a esta aba
		if (quantidadeDeOptions <= 1) {//sempre inicia com 1 *carregando...*
			//pega o numero da aba atual e o seu respectivo form
			let seletorCanais = $("#form-edit-cliente-" + abaSelecionada + " select[name=CanalVenda]");
			seletorCanais.select2({
				width: '100%',
				placeholder: "Selecione o canal de venda",
				allowClear: true
			});
			canaisVenda = JSON.parse(canaisVenda);
			if (canaisVenda.values.length > 0) {
				canaisVenda.values.forEach(canal => {
					seletorCanais.append(new Option(canal.tz_name, canal.tz_canal_vendaid, false, false));
				});
			}
			if (!canaisVenda.values.length) seletorCanais.append(new Option('Nenhum canal encontrado', '', true, false));
			seletorCanais.trigger('change');
		}

	}

	function popularSelectSegmentacao() {
		let quantidadeDeOptions = $("#form-edit-cliente-" + abaSelecionada + " select[name=Segmentacao] option").length;
		$("#form-edit-cliente-" + abaSelecionada + " select[name=Segmentacao]").children().first().remove();

		//verifica se já foram adicionados os vendedores a esta aba
		if (quantidadeDeOptions <= 1) {//sempre inicia com 1 *carregando...*
			//pega o numero da aba atual e o seu respectivo form
			let seletorSegmentacoes = $("#form-edit-cliente-" + abaSelecionada + " select[name=Segmentacao]");
			seletorSegmentacoes.select2({
				width: '100%',
				placeholder: "Selecione a segmentação",
				allowClear: true
			});

			if (segmentacoes.data.value.length > 0) {
				segmentacoes.data.value.forEach(canal => {
					seletorSegmentacoes.append(new Option(canal.tz_name, canal.tz_segmentacao_clienteid, false, false));
				});
			}

			seletorSegmentacoes.val('');
			if (!segmentacoes.length) seletorSegmentacoes.append(new Option('Nenhum canal encontrado', '', true, false));
			seletorSegmentacoes.trigger('change');
		}

	}
	/* FIM CLIENTE */


	/** FATURAS */
	async function buscarFaturasCliente(id_cliente) {
		return new Promise((resolve, reject) => {
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_buscar_faturas_cliente/${id_cliente}`,
				type: 'GET',
				success: function (data) {
					resolve(data);
				},
				error: function (error) {
					reject(error);
				}
			})
		});
	}
	/** FIM FATURAS */

	$("#btn-busca-na").click(function () {
		let btn = $(this)
		let tipo = $("#tipo-busca-na").val()
		let valorBusca = $("#busca-na-modal").val()
		let resposta = ''
		let informacoesNA = ''

		if (valorBusca && valorBusca != "") {
			btn.html(ICONS.spinner + ' Buscando').attr('disabled', true)

			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/buscarIdContratoAtividadeServico?tipo=${tipo}&valor=${valorBusca}`,
				type: 'GET',
				success: async function (response) {
					if (response) {
						let retorno = JSON.parse(response)
						btn.html('Buscar').attr('disabled', false)
						if (retorno.code === 200) {
							$("#modal-busca-na").modal('hide')

							retorno = retorno.data
							informacoesNA = retorno[0]

							if (informacoesNA.statusDescription === 'Aberto') {
								let dadosCliente = await buscarDadosCliente(retorno['cliente'].documento);
								dadosClienteNA = dadosCliente;
								abrirModalNA(null, informacoesNA.Id, dadosCliente)
							} else {

								let dataMinima = retorno.dataMinimaAgendamento ?
									new Date(retorno.dataMinimaAgendamento).toLocaleString() : "-"

								let localAtendimento = retorno.localAtendimento === 1 ?
									"Ponto Fixo RAZ" : "Externo"

								$('#codigo-na-modal').html(informacoesNA.Code ? informacoesNA.Code : '-')
								$('#cliente-modal-na').html(
									`<a class="clienteDoContrato" onclick="buscarClientePorCpfCnpj('${retorno.cliente.documento}')">
										${retorno.cliente.nome}</a>`)
								$('#serial-modal-na').html(informacoesNA.trackerSerialNumberInstall ? informacoesNA.trackerSerialNumberInstall : '-')
								$('#servico-modal-na').html(informacoesNA.serviceName ? informacoesNA.serviceName : '-')
								$('#razao-modal-na').html(informacoesNA.valorStatusCode ? informacoesNA.valorStatusCode : '-')
								$('#complemento-modal-na').html(informacoesNA.serviceNameComplement ? informacoesNA.serviceNameComplement : '-')
								$('#fornecedor-modal-na').html(informacoesNA.provider ? informacoesNA.provider : '-')
								$('#assunto-modal-na').html(informacoesNA.subject ? informacoesNA.subject : '-')
								$('#inicio-modal-na').html(informacoesNA.scheduledstart ? informacoesNA.scheduledstart : '-')
								$('#fim-modal-na').html(informacoesNA.scheduledend ? informacoesNA.scheduledend : '-')
								$('#nome-solicitante-modal-na').html(retorno.nomeSolicitante ? retorno.nomeSolicitante : '-')
								$('#telefone-solicitante-modal-na').html(retorno.telefoneSolicitante ? retorno.telefoneSolicitante : '-')
								$('#item-contrato-modal-na').html(informacoesNA.contract?.Name ?? '-')
								$('#data-minima-modal-na').html(dataMinima)
								$('#recurso-modal-na').html(retorno.recurso?.nome ?? '-')
								$('#local-atendimento-modal-na').html(localAtendimento)

								if (retorno.localAtendimento === 2) {
									$("#rua-endereco-externo-modal-na").html(retorno.endereco?.rua ?? "-")
									$("#numero-endereco-externo-modal-na").html(retorno.endereco?.numero ?? "-")
									$("#endereco-externo-modal-na").show()
								} else {
									$("#endereco-externo-modal-na").hide()
								}

								if (retorno.anotacao) {
									$("#anotacao-modal-na").show()
									$("#titulo-modal-na").html(retorno.anotacao.subject)
									$("#descricao-modal-na").html(retorno.anotacao.notetext)

									arquivo = retorno.anotacao?.documentbody ?
										`<a download="${retorno.anotacao.filename}" href="${retorno.anotacao.documentbody}">Baixar Arquivo</a>` : "-"

									$("#arquivo-modal-na").html(arquivo)
								} else {
									$("#anotacao-modal-na").hide()
								}

								// adiciona o onclick nos botões para chamar a função de mostrar ocorrência e contrato
								$("#btn-contrato-modal-na").click(function () {
									abrirModalContratoAtividadesDeServico(this, informacoesNA.contract.Id)
								});

								$("#btn-ocorrencia-modal-na").click(function () {
									abrirModalOcorrenciasAtividadesDeServico(this, informacoesNA.incident.Id)
								});

								$("#btn-os-modal-na").click(function () {
									visualizarOS(this, informacoesNA.numeroOs)
								});

								$("#modal-resultado-busca-na").modal('show');
							}
						} else {
							showAlert("error","Ocorreu um erro ao buscar a NA, tente novamente.")
						}
					} else {
						showAlert("error","NA não encontrada! Verifique os campos e tente novamente.")
					}

					btn.html('Buscar').attr('disabled', false);
				},
				error: function (error) {
					btn.html('Buscar').attr('disabled', false);
					showAlert("error","Ocorreu um erro ao buscar a NA, tente novamente.");
				}
			});
		} else {
			showAlert("error","Digite o valor da busca no campo apropriado.")
		}

	});

	$("#btnBuscarContrato").click(function () {
		$('#btn-info-contrato').hide();
		let btn = $(this);
		let tipo = $("#tipoBuscaContrato").val();
		let valorBusca = null;
		if (tipo == 'serial') valorBusca = $("#serialBuscarContrato").val();
		else if (tipo == 'placa') valorBusca = $("#placaBuscarContrato").val();
		else valorBusca = $("#codigoBuscarContrato").val();

		document.getElementById("atividade-servico-content").style.display = "none";
		if (valorBusca && valorBusca != "") {
			btn.html(ICONS.spinner + ' Buscando').attr('disabled', true);
			limparModalBuscarContrato()
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_buscar_contrato_por_placa_serial/${valorBusca}`,
				type: 'GET',
				success: function (data) {
					btn.html('Buscar').attr('disabled', false);
					data = JSON.parse(data);
					if (data.code == 200) {
						let contrato = data.contrato;
						if (contrato) {
							// Reseta modal Item de Contrato
							controlarAbasModalItemDeContrato(false);
							// Reseta informações do item de contrato
							resetarInfoModalItemDeContrato();
							instanciarTabelaAtividadesServico(btn, contrato.id, '#rowInfoContrato')

							$("#idContratoModal").val(contrato.id);
							$("#codigoModalBuscarContrato").html(contrato.codigo);
							$("#canalVendaModalBuscarContrato").html(contrato.canal_venda);
							$("#cenarioVendaModalBuscarContrato").html(contrato.cenario_venda);
							$("#descricaoModalBuscarContrato").html(contrato.descricao);
							$("#numeroAfModalBuscarContrato").html(contrato.numeroAf);
							$("#placaModalBuscarContrato").html(contrato.placa);
							$("#planoModalBuscarContrato").html(contrato.plano);
							$("#rastreadorModalBuscarContrato").html(contrato.rastreador);
							$("#serialModalBuscarContrato").html(contrato.serial);
							$("#tecnologiaModalBuscarContrato").html(contrato.tecnologia);
							$("#valorLicencaBaseModalBuscarContrato").html(contrato.valorLicencaBase ? converterParaReal(contrato.valorLicencaBase) : '');
							$("#valorTotalServicosAtivosModalBuscarContrato").html(contrato.valorTotalServicosAtivos ? converterParaReal(contrato.valorTotalServicosAtivos) : '');
							$("#nomeClienteModalBuscarContrato").html(
								`<a class="clienteDoContrato" onclick="exibirClienteDoContrato('${contrato.cliente ? (contrato.cliente.cnpj ? contrato.cliente.cnpj : contrato.cliente.cpf) : null}')">${contrato.cliente?.nome}</a>`
							);
							$("#nomeFantasiaClienteModalBuscarContrato").html(contrato.cliente?.nomefantasia);
							$("#cnpjCpfClienteModalBuscarContrato").html(contrato.cliente ? (contrato.cliente.cnpj ? contrato.cliente.cnpj : contrato.cliente.cpf) : '');
							$("#emailClienteModalBuscarContrato").html(contrato.cliente?.email);

							$("#dataEntradaModalBuscarContrato").html(contrato.data_entrada ? contrato.data_entrada : '');
							$("#dataAtivacaoModalBuscarContrato").html(contrato.data_ativacao ? contrato.data_ativacao : '');
							$("#statusModalBuscarContrato").html(contrato.status ? contrato.status : '');
							$("#motivoAlteracaoModalBuscarContrato").html(contrato.motivoAlteracao ? contrato.motivoAlteracao : '');

							if (contrato?.os) {
								tableAtividadesDeServicoIC.clear().draw();
								tableAtividadesDeServicoIC.rows.add(contrato.os).draw();
							}
						} else {
							if (tipo == 'serial') showAlert("error",'Serial não encontrado!');
							else showAlert("error",'Contrato não encontrado!');
						}
					} else {
						showAlert("error","Erro ao buscar o contrato!");
					}

				},
				error: function (error) {
					btn.html('Buscar').attr('disabled', false);
					console.error(error);
					showAlert("error","Erro ao buscar o contrato!");
				}
			});
		} else {
			showAlert("error","Digite uma placa, um código ou um serial para fazer a busca!");
		}
	});
	function limparModalBuscarContrato() {
		$("#idContratoModal").html('');
		$("#codigoModalBuscarContrato").html('');
		$("#canalVendaModalBuscarContrato").html('');
		$("#cenarioVendaModalBuscarContrato").html('');
		$("#descricaoModalBuscarContrato").html('');
		$("#numeroAfModalBuscarContrato").html('');
		$("#placaModalBuscarContrato").html('');
		$("#planoModalBuscarContrato").html('');
		$("#rastreadorModalBuscarContrato").html('');
		$("#serialModalBuscarContrato").html('');
		$("#tecnologiaModalBuscarContrato").html('');
		$("#valorLicencaBaseModalBuscarContrato").html('');
		$("#valorTotalServicosAtivosModalBuscarContrato").html('');
		$("#nomeClienteModalBuscarContrato").html();
		$("#nomeFantasiaClienteModalBuscarContrato").html('');
		$("#cnpjCpfClienteModalBuscarContrato").html('');
		$("#emailClienteModalBuscarContrato").html('');

		$("#abas-ic").hide();
		$("#os-content").hide();
		$('#rowInfoContrato').hide();
	}


	$("#tipoBuscaContrato").change(function () {
		if ($(this).val() == 'serial') {
			$("#serialBuscarContrato").next(".select2-container").show();
			$("#placaBuscarContrato").next(".select2-container").hide();
			$("#placaBuscarContrato").val('').trigger('change');
			$("#codigoBuscarContrato").val('').css('display', 'none');
		} else if (this.value == 'placa') {
			$("#serialBuscarContrato").next(".select2-container").hide();
			$("#serialBuscarContrato").val('').trigger('change');
			$("#codigoBuscarContrato").val('').css('display', 'none');
			$("#placaBuscarContrato").next(".select2-container").show();
		} else {
			$("#serialBuscarContrato").next(".select2-container").hide();
			$("#serialBuscarContrato").val('').trigger('change');
			$("#placaBuscarContrato").next(".select2-container").hide();
			$("#placaBuscarContrato").val('').trigger('change');
			$("#codigoBuscarContrato").val('').css('display', 'block');
		}
	})

	$('.nav-tab-cliente').on('click', function (e) {
		if (e.currentTarget.id.includes('nav_contatos_relacionados')) {
			tabAssociados = true;
			$('#btn_edit_cliente-' + abaSelecionada).html('<i class="fa fa-plus" aria-hidden="true"></i>');
			carregarContatosAssociados();
		} else {
			tabAssociados = false;
			$('#btn_edit_cliente').html('<i class="fa fa-pencil" aria-hidden="true"></i>');
		}
	});

	var documentoAtual;
	var tabelaContatosAssociados = $('.tabela-contatos-associados')
		.DataTable({
			responsive: true,
			ordering: false,
			paging: true,
			searching: true,
			info: true,
			language: lang.datatable,
			lengthChange: false,
			columnDefs: [
				{ className: 'text-center', targets: [4] }
			],
			rowId: function (contato) {
				return contato.id;
			},
			columns: [
				{ 'data': 'nome' },
				{ 'data': 'funcao' },
				{
					'data': 'telefone',
					'render': (telefone) => {
						if (!telefone) return '';
						let mascarado = '(##)#########';
						for (d in telefone) mascarado = mascarado.replace('#', telefone[d]);
						mascarado = mascarado.replace('#', '');
						return mascarado.slice(0, -4) + '-' + mascarado.slice(-4);
					}
				},
				{ 'data': 'email' },
				{
					'data': 'id',
					'render': (data, type, row) => `<button style='border-radius: 4px !important;' class="btn btn-primary" title="Editar" 
					onclick="editarContatoAssociado('${row.id}','${row.nome}','${row.funcao}','${row.email}','${row.telefone}')">
					<i class="fa fa-pencil" aria-hidden="true"></i>
				</button> <button class="btn btn-danger" title="Excluir" 
				onclick="excluirContatoAssociado(this, '${row.id}')">
				<i class="fa fa-times" aria-hidden="true"></i>
			</button>`
				},
			]
		});


	function carregarContatosAssociados() {
		let documento = buscarDadosClienteAbaAtual()?.document.replace(/[^0-9]/g, '');
		if (documento && documentoAtual != documento) {
			$('#tabela-contatos-associados-' + abaSelecionada).DataTable().clear();
			let divTabela = $('#div-tabela-contatos-associados-' + abaSelecionada);
			let divLoading = $('#div-loading-contatos-associados-' + abaSelecionada);
			divLoading.show();
			divTabela.hide();

			$.ajax({
				url: 'PaineisOmnilink/ajax_listar_contatos_associados/' + documento,
				type: 'GET',
				dataType: 'json',
				success: function (resposta) {
					resposta = resposta ?? {};
					if (resposta.status == 1) {
						$('#tabela-contatos-associados-' + abaSelecionada).DataTable().rows.add(resposta.contatos ?? []).draw();
						documentoAtual = documento;
					} else {
						showAlert("error",'Falha ao carregar contatos associados');
						console.error(resposta);
					}
				},
				error: function (e) {
					showAlert("error",'Falha ao carregar contatos associados');
					if (e) console.error(e);
				},
				complete: function () {
					divLoading.hide();
					divTabela.show();
				}
			});
		}
	}

	$('.nav_vendas_af').on("click", function (e) {
		carregarTabelaVendas()
	});

	function carregarTabelaVendas() {
		instanciarTabelaVendasAf(buscarDadosClienteAbaAtual().Id, retornarTipoDeCliente())
	}


	$('#form-cadastro-contato-cliente').submit(function (e) {
		e.preventDefault();
		let dadosForm = $(this).serializeArray();
		let dados = {};
		for (let c in dadosForm) {
			dados[dadosForm[c].name] = dadosForm[c].value;
		}

		if (!dados.email && !dados.telefone) {
			showAlert("error",'Ao menos uma forma de contato deve ser cadastrada.');
			return false;
		}

		let botao = $('#button-salvar-contato-associado');
		let htmlBotao = botao.html();
		botao.html(ICONS.spinner + htmlBotao);
		botao.attr('disabled', true);

		dados.documento = $('input[name=Cpf_Cnpj]').val().replace(/[^0-9]/g, '');
		dados.telefone = dados.telefone.replace(/[^0-9]/g, '');

		let url = 'PaineisOmnilink/ajax_novo_contato_associado';
		let tipo = $('#modal-contato-associado').data('tipo');
		if (tipo == 'editar') {
			url = 'PaineisOmnilink/ajax_atualizar_contato_associado';
			dados.id = $('#modal-contato-associado').data('id');
		}

		$.ajax({
			url,
			type: 'POST',
			dataType: 'json',
			data: dados,
			success: function (resposta) {
				resposta = resposta || {};
				if (resposta.status == 1) {
					showAlert("success",'Sucesso');
					if (tipo == 'editar') {
						tabelaContatosAssociados.row('#' + dados.id).data(dados).draw(false);
					} else {
						$('#form-cadastro-contato-cliente').trigger('reset');
						dados.id = resposta.id;
						tabelaContatosAssociados.row.add(dados).draw(false);
					}
				} else {
					showAlert("error",resposta.mensagem || 'Falha');
					console.error(resposta);
				}
			},
			error: function (e) {
				showAlert("error",'Falha ao salvar contato associado');
				if (e) console.error(e);
			},
			complete: function () {
				botao.html(htmlBotao);
				botao.attr('disabled', false);
			}
		});
	});

	$('#modalEncerramentoOcorrencia').on('hide.bs.modal', function () {
		let botoesResolverOcorrencia = $(".resolve-incident-button");
		botoesResolverOcorrencia.attr('disabled', false);
		botoesResolverOcorrencia.html('<i class="fa fa-check-circle-o" aria-hidden="true"></i>');
		$('#selectIdTipoResolucao').val(0).trigger('change');
	});

	// BLOCO DE NOTAS

	// Elementos gráficos do Bloco de Notas
	notepad = $('.notepad');
	notepadNote = $('.notepad-control');

	let notepadMinHeight = 256;
	let notepadMinWidth = 256;

	// Restaurar Notas do LocalStorage
	notepadNote.val(sessionStorage.getItem('notepadNote-' + abaSelecionada));

	// Inicializar o Bloco de Notas como um elemento arrastável e redimensionável
	notepad
		.draggable({
			containment: 'window',
			cursor: 'move',
			scroll: false,
		})
		.resizable({
			containment: 'document',
			minHeight: notepadMinHeight,
			minWidth: notepadMinWidth,
		});

	// Força o Bloco de Notas a ter sempre o tamanho mínimo de 256x256
	notepad.on('resize', function (event, ui) {
		if (ui.size.height <= notepadMinHeight) ui.size.height = notepadMinHeight;
		if (ui.size.width <= notepadMinWidth) ui.size.width = notepadMinWidth;
	});

	// Salvar notas no Local Storage, quando o usuário começar a digitar
	notepadNote.on('keyup', function () {
		sessionStorage.setItem('notepadNote-' + abaSelecionada, notepadNote.val());
	});

	// Abrir o Bloco de Notas
	$('#notepadOpenButton').on('click', function () {
		notepad.slideDown({
			duration: 'fast',
		});
	});

	// Fechar o Bloco de Notas 
	$('.notepad-close-button').on('click', function () {
		notepad.slideUp({
			duration: 'fast',
		});
	});

	$("#estado-na").on("change", async event => {
		if (this.value !== "0") {
			$("#cidade-na").prepend(`<option selected disabled value="0">Buscando cidades do estado selecionado...</option>`)
			await buscarCidades().then(cidades => {

				$("#cidade-na").select2({
					width: '100%',
					placeholder: "Selecione uma cidade",
					allowClear: true
				})
				$('#cidade-na').empty()

				if (cidades.length) {
					$("#cidade-na").prepend(`<option selected disabled value="0">Selecione uma cidade</option>`)
					cidades.forEach(cidade => {
						$("#cidade-na").append(`<option value="${cidade.id}" name="${cidade.nome.replace(" ", "")}">${cidade.nome}</option>`)
					})

					if (cidadeNA) $("#cidade-na").find(`option[name=${cidadeNA}]`).attr("selected", true).change()
				} else {
					$("#cidade-na").prepend(`<option selected disabled value="0">Nenhuma cidade encontrada neste estado</option>`)
				}
			}).catch(err => { })
		}
	})

	if (clientes && clientes.length == 1) {
		escondeDivDadosCliente();
		$("#sel-pesquisa").val(0);
		$("#pesqDoc").val(clientes[0]?.document);
		$('#pesquisaDoc').show();
		$('#pesqDoc').removeAttr('disabled');
		$('#pesquisaNome').hide();
		$('#pesqNome').removeAttr('disabled', true);

		$("#formPesquisa").submit();
		$("#pesquisa").attr("disabled", true).html(ICONS.spinner + " Procurando");
		$("#pesquisa").removeAttr("disabled").html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
	} else if (clientes && clientes.length > 1) {
		let controle = 1
		clientes.forEach(async cliente => {
			if (controle >= 1) {
				let numeroAba = controle

				//verifica se a aba já existe
				if (listaAbasPreenchidas.includes("0" + (numeroAba).toString())) {
					for (i = listaAbasPreenchidas.length; i > 0; i--) {
						if (!listaAbasPreenchidas.includes("0" + i)) {
							numeroAba = i;
							break;
						}
					}
				}
				//mostra a próxima aba e a seleciona
				$('#nav_dados_cliente-' + "0" + (numeroAba)).css('display', 'block')
				// guarda o valor da aba selecionada no array
				listaAbasPreenchidas.push("0" + (numeroAba));

				//esconde o botão de adicionar novas abas
				$('#btn-adiconar-aba').css('display', 'none');

				//timeout para clicar na nova aba, necessário para o elemento ser carregado no dom
				setTimeout(() => {
					//esconde a aba do novo cliente
					$('#nome-cliente-' + "0" + (numeroAba)).trigger('click')
				}, 100);

				abaSelecionada = "0" + (numeroAba).toString();

				dadosCliente = cliente;

				if (dadosCliente) {
					//verifica se os valores de "abertos,vencidos e liquidados" do último cliente estão abertas para acionar um clique para resetar os campos
					if ($('#abertos-' + abaSelecionada).hasClass('ocultar_valores')) $('#abertos-' + abaSelecionada).trigger("click");

					//verifica se o cliente possui ao menos uma providência
					if (dadosCliente.providencia) {
						showAlert("success",'Este cliente possui providência(s)!');
						$("#icon_alert_providencia-" + abaSelecionada).css('display', 'inline-block');
					} else {
						$("#icon_alert_providencia-" + abaSelecionada).css('display', 'none');
					}
					if (dadosCliente.entity === 'contacts') {
						//verifica se há algum dado no primeiro nome e concatena com o último ou retorna s/nome
						dadosCliente?.firstname === "" || !dadosCliente?.firstname ? nomeAbaCliente = "S/ NOME" : nomeAbaCliente = (dadosCliente?.firstname + " " + dadosCliente?.lastname).slice(0, 13)
						nomeAbaCliente += " (PF)"
					} else {
						nomeAbaCliente = dadosCliente?.name.slice(0, 13) + " (PJ)";
					}

					$('#nome-cliente-' + abaSelecionada).text(nomeAbaCliente);
					$('#nome-cliente-' + abaSelecionada).attr('title', dadosCliente?.document);

					//coloca o select2 nos vendedores
					$("#form-edit-cliente-" + abaSelecionada + " select[name=Vendedor]").select2({
						width: '100%',
						placeholder: "Selecione o vendedor",
						allowClear: true
					});

					//preenche os vendedores apenas se clicar na opção
					$("#form-edit-cliente-" + abaSelecionada + " select[name=Vendedor]").on("select2:opening", function (e) {
						//chama a função para preencher o select2
						popularSelectVendedores();
					});

					popularSelectCanais();
					popularSelectSegmentacao();
					//torna a variável na sessão false para dizer que é necessário carregar a tabela de providencias novamente
					instanciaTabelaProvidencias[pegarIndiceDaAbaAtual()] = false;

					//torna a variável na sessão false para dizer que é necessário carregar a tabela de ocorrências novamente
					instanciaTabelaOcorrencia[pegarIndiceDaAbaAtual()] = false;

					// Salva dados do cliente que será salvo na auditoria
					salvar_valor_antigo_cliente(dadosCliente);

					$("#pesquisa").attr("disabled", false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar')

					let data = {
						dadosCliente,
						dadosAtividadeDeServico: atividadesDeServico[0]
					}
					// Renderiza dados na view
					renderizarView(data);

					//necessário dar um trigger no click para que seja chamada a função para carregar os dados
					if ($("#nav_ocorrencias-" + abaSelecionada).hasClass("active")) {
						//torna a variável false para dizer que é necessário carregar a tabela de incidentes novamente
						instanciaTabelaOcorrencia[pegarIndiceDaAbaAtual()] = false;
						setTimeout(function () {
							$("#nav_ocorrencias-" + abaSelecionada).trigger('click');
						}, 100);

					} else if ($("#nav_providencias-" + abaSelecionada).hasClass("active")) {
						//a tabela já foi instanciada pelo menos uma vez, podendo chamar o clear e destroy
						$('#table_providencias-' + abaSelecionada).DataTable().clear();
						$('#table_providencias' + abaSelecionada).DataTable().destroy();
						//torna a variável false para dizer que é necessário carregar a tabela de incidentes novamente
						instanciaTableIncidentes = false;
						setTimeout(function () {
							$("#nav_providencias-" + abaSelecionada).trigger('click');
						}, 100);

					} else if ($("#nav_dados_cliente_tab-" + abaSelecionada).hasClass("active")) {
						//verifica se a aba (vendas AF) está ativa e clica nela novamente para recarregar
						if ($("#nav_vendas_af-" + abaSelecionada).hasClass("active")) setTimeout(function () { $("#nav_vendas_af-" + abaSelecionada).trigger('click') }, 100);
					}

					// Limpa tabelas de dados
					$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().clear().draw(null, false);

					if ($.fn.DataTable.isDataTable($('#tabelaContratos-' + abaSelecionada).DataTable())) {
						$('#tabelaContratos-' + abaSelecionada).DataTable().destroy();
						$('#tabelaContratos-' + abaSelecionada + ' tbody').html('');

						if ($('#nav_contratos-' + abaSelecionada).hasClass('active')) $("#nav_contratos-" + abaSelecionada).trigger('click');
					}
				}
			}


			$("#cliente-0" + controle).removeAttr('style')
			controle += 1

		})
		if (clientes.length < 5) {
			$('#btn-adiconar-aba').css('display', 'block');
		}
	}

	$("#estado-na-2").on("change", async event => {
		if (this.value !== "0") {
			$("#cidade-na-2").prepend(`<option selected disabled value="0">Buscando cidades do estado selecionado...</option>`)
			await buscarCidades().then(cidades => {

				$("#cidade-na-2").select2({
					width: '100%',
					placeholder: "Selecione uma cidade",
					allowClear: true
				})
				$('#cidade-na-2').empty()

				if (cidades.length) {
					$("#cidade-na-2").prepend(`<option selected disabled value="0">Selecione uma cidade</option>`)
					cidades.forEach(cidade => {
						$("#cidade-na-2").append(`<option value="${cidade.id}" name="${cidade.nome.replace(" ", "")}">${cidade.nome}</option>`)
					})

					if (cidadeNA) $("#cidade-na-2").find(`option[name=${cidadeNA}]`).attr("selected", true).change()
				} else {
					$("#cidade-na-2").prepend(`<option selected disabled value="0">Nenhuma cidade encontrada neste estado</option>`)
				}
			}).catch(err => { })
		}
	})

	$("#estado-na-3").on("change", async event => {
		if (this.value !== "0") {
			$("#cidade-na-3").prepend(`<option selected disabled value="0">Buscando cidades do estado selecionado...</option>`)
			await buscarCidades().then(cidades => {

				$("#cidade-na-3").select2({
					width: '100%',
					placeholder: "Selecione uma cidade",
					allowClear: true
				})
				$('#cidade-na-3').empty()

				if (cidades.length) {
					$("#cidade-na-3").prepend(`<option selected disabled value="0">Selecione uma cidade</option>`)
					cidades.forEach(cidade => {
						$("#cidade-na-3").append(`<option value="${cidade.id}" name="${cidade.nome.replace(" ", "")}">${cidade.nome}</option>`)
					})

					if (cidadeNA) $("#cidade-na-3").find(`option[name=${cidadeNA}]`).attr("selected", true).change()
				} else {
					$("#cidade-na-3").prepend(`<option selected disabled value="0">Nenhuma cidade encontrada neste estado</option>`)
				}
			}).catch(err => { })
		}
	})

	$("#estado-na-4").on("change", async event => {
		if (this.value !== "0") {
			$("#cidade-na-4").prepend(`<option selected disabled value="0">Buscando cidades do estado selecionado...</option>`)
			await buscarCidades().then(cidades => {

				$("#cidade-na-4").select2({
					width: '100%',
					placeholder: "Selecione uma cidade",
					allowClear: true
				})
				$('#cidade-na-4').empty()

				if (cidades.length) {
					$("#cidade-na-4").prepend(`<option selected disabled value="0">Selecione uma cidade</option>`)
					cidades.forEach(cidade => {
						$("#cidade-na-4").append(`<option value="${cidade.id}" name="${cidade.nome.replace(" ", "")}">${cidade.nome}</option>`)
					})

					if (cidadeNA) $("#cidade-na-4").find(`option[name=${cidadeNA}]`).attr("selected", true).change()
				} else {
					$("#cidade-na-4").prepend(`<option selected disabled value="0">Nenhuma cidade encontrada neste estado</option>`)
				}
			}).catch(err => { })
		}
	})

	$("#estado-na-5").on("change", async event => {
		if (this.value !== "0") {
			$("#cidade-na-5").prepend(`<option selected disabled value="0">Buscando cidades do estado selecionado...</option>`)
			await buscarCidades().then(cidades => {

				$("#cidade-na-5").select2({
					width: '100%',
					placeholder: "Selecione uma cidade",
					allowClear: true
				})
				$('#cidade-na-5').empty()

				if (cidades.length) {
					$("#cidade-na-5").prepend(`<option selected disabled value="0">Selecione uma cidade</option>`)
					cidades.forEach(cidade => {
						$("#cidade-na-5").append(`<option value="${cidade.id}" name="${cidade.nome.replace(" ", "")}">${cidade.nome}</option>`)
					})

					if (cidadeNA) $("#cidade-na-5").find(`option[name=${cidadeNA}]`).attr("selected", true).change()
				} else {
					$("#cidade-na-5").prepend(`<option selected disabled value="0">Nenhuma cidade encontrada neste estado</option>`)
				}
			}).catch(err => { })
		}
	})

	$('#ocorrenciaSuporte').on('click', function () {
		$('.myModalLabel').text("Cadastrar Ocorrência como Suporte");
		$('#modalOcorrencia').modal("show");
		
	})

	$('#modalOcorrencia').on('hide.bs.modal', function(){
		$('.myModalLabel').text("Cadastrar Ocorrência");
	})

	$('#ocorrenciaSuporte').on('click', function () {
		$('.myModalLabel').text("Cadastrar Ocorrência como Suporte");
		$('#modalOcorrencia').modal("show");
		
	})

	$('#modalOcorrencia').on('hide.bs.modal', function(){
		$('.myModalLabel').text("Cadastrar Ocorrência");
	})
});


async function popularSelects() {
	tecnologias = await $.ajax({
		url: `${URL_PAINEL_OMNILINK}/listar_tecnologias/`,
		type: 'GET',
		success: function (data) {
			return data.data.value;
		},
		error: function (error) {
			showAlert("error","Ocorreu um problema ao buscar as tecnologias, a base de dados pode estar apresentando instabilidade.");
		}
	});

	assuntos = await $.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_get_assuntos`,
		type: 'GET',
		success: function (data) {
			data = JSON.parse(data);
			if (data.code == 200) {
				return (data.values);
			} else if (data.code == 0) {
				showAlert("error","A base de dados está apresentando instabilidade, não foi possível buscar os assuntos.");
			} else {
				showAlert("error","Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
			}
		},
		error: function (error) {
			showAlert("error","Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
		}
	});

	assuntos = JSON.parse(assuntos);
	if ($('#modalOcorrencia').is(":visible")) {
		tecnologias.data.value.forEach(element => {
			$("#Tecnologia").append('<option value="' + element.tz_tecnologiaid + '">' + element.tz_name + '</option>');
		});
		assuntos.values.forEach(element => {
			if (typeof element.visualizarSinistro != "undefined") {
				if (element.visualizarSinistro == true) {
					$("#Assunto").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
				}
			}
			else {
				$("#Assunto").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
			}
		});

	} else {
		tecnologias.data.value.forEach(element => {
			$("#TecnologiaEdit").append('<option value="' + element.tz_tecnologiaid + '">' + element.tz_name + '</option>');
		});
		assuntos.values.forEach(element => {
			if (typeof element.visualizarSinistro != "undefined") {
				if (element.visualizarSinistro == true) {
					$("#AssuntoEdit").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
				}
			}
			else {
				$("#AssuntoEdit").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
			}
		});

	}


	if (!permissoes['vis_visualizarsinistroagendamento']) {
		$("#Assunto").find("option:contains('SINISTRO')").remove()
	}
}

$("#modalOcorrencia").on('show.bs.modal', async function () {
	$('#Assunto').append(`<option selected disabled value="1">Buscando assuntos...</option>`)
	$('#Assunto').attr("disabled", true)
	$('#Tecnologia').append(`<option selected disabled value="1">Buscando tecnologias...</option>`)
	$('#Tecnologia').attr("disabled", true)
	await popularSelects();
	$('#Assunto').find('option').get(1).remove();
	$('#Assunto').attr("disabled", false)
	$('#Tecnologia').find('option').get(1).remove();
	$('#Tecnologia').attr("disabled", false)
	$('#Assunto').val(0).trigger('change');
	$('#Tecnologia').val(0).trigger('change');
});
//seleciona o cliente atual ao clicar em outra aba
$('.abas-cliente').on('click', function (e) {
	//muda o valor da variável com base no id da aba clicada
	abaSelecionada = e.currentTarget.id.slice(-2);
});

// ao clicar em outra aba, pega o conteudo dessa aba e coloca no bloco de notas
$('.abas-cliente').on('click', function (e) {
	let conteudoBlocoNotas = sessionStorage.getItem('notepadNote-' + abaSelecionada);
	notepadNote.val(conteudoBlocoNotas);
});


$('#btn-adiconar-aba').on('click', function (e) {
	// calcula o numero de abas existentes
	let numeroAba = account.filter(function (el) {
		return el != 0;
	}).length;

	numeroAba++;

	//verifica se a aba já existe
	if (listaAbasPreenchidas.includes("0" + (numeroAba).toString())) {
		for (i = listaAbasPreenchidas.length; i > 0; i--) {
			if (!listaAbasPreenchidas.includes("0" + i)) {
				numeroAba = i;
				break;
			}
		}
	}
	//mostra a próxima aba e a seleciona
	$('#nav_dados_cliente-' + "0" + (numeroAba)).css('display', 'block')
	// guarda o valor da aba selecionada no array
	listaAbasPreenchidas.push("0" + (numeroAba));

	//esconde o botão de adicionar novas abas
	$('#btn-adiconar-aba').css('display', 'none');

	//timeout para clicar na nova aba, necessário para o elemento ser carregado no dom
	setTimeout(() => {
		//esconde a aba do novo cliente
		$('#cliente-' + '0' + (numeroAba)).css('display', 'none');
		$('#nome-cliente-' + "0" + (numeroAba)).trigger('click')
	}, 100);
});

//funcao de remover abas
function removerAba(id) {

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_remover_cliente`,
		type: "POST",
		data: {
			account: account[id - 1]?.Id
		}
	})
	//se a aba a ser removida for a selecionada, seleciona a aba anterior
	if (id == abaSelecionada) {
		$('#nav_dados_cliente-' + "01").children().trigger('click')
	}

	//esconde a aba
	$('#nav_dados_cliente-' + "0" + (id)).css('display', 'none');
	// seta o valor da aba selecionada para 0
	account[id - 1] = 0;
	// remove o elemento da lista de abas preenchidas
	listaAbasPreenchidas = listaAbasPreenchidas.filter(function (value) {
		return value != "0" + id;
	});
	//se o numero de abas for menor que 5, mostra o botão de adicionar novas abas
	let numeroAbas = account.filter(function (el) {
		return el != 0;
	}).length;
	if (numeroAbas < 5) {
		$('#btn-adiconar-aba').css('display', 'block');
	}
	//limpa o conteudo da sessão de notas
	sessionStorage.setItem('notepadNote-' + "0" + id, "");

	limparCamposAbaRemovida(id);


}

function limparCamposAbaRemovida(id) {
	//limpa os campos da aba removida
	$('#nome-cliente-' + "0" + (id)).text("0" + id)
	$('#nome-cliente-' + "0" + (id)).attr('title', id)
	$("#CNPJCliente-" + "0" + (id)).text("")
	$("#EnderecoCliente-" + "0" + (id)).text("")
	$("#BairroCliente-" + "0" + (id)).text("")
	$("#CidadeCliente-" + "0" + (id)).text("")
	$("#CEPCliente-" + "0" + (id)).text("")
	$("#UFCliente-" + "0" + (id)).text("")
	$('#selectAgendamentoVeic-' + "0" + (id)).val('');
	$("#NomeFantasiaCliente-" + "0" + (id)).text("")
	$("#TelefoneCliente-" + "0" + (id)).text("")
	$("#EmailCliente-" + "0" + (id)).text("")
	$("#SegmentacaoCliente-" + "0" + (id)).text("");
	$("#SuporteCliente-" + "0" + (id)).text("");
	$("#CodigoClienteZatix-" + "0" + (id)).text("");
	$("#Loja-" + "0" + (id)).val('');
	$("#CodigoClienteGraber-" + "0" + (id)).text("");
	$("#CodigoClienteShow-" + "0" + (id)).text("");
	$("#VendedorCliente-" + "0" + (id)).text("");

	//Situação financeira
	$('#selectAtendimentoVeic-' + "0" + (id)).val('');
	$('#selectComunicacaoChip-' + "0" + (id)).val('');
	$('#selectComunicacaoSatelital-' + "0" + (id)).val('');
	$('#statusDataDesbloqueio-' + "0" + (id)).val('');
	$('#selectEmissaoPV-' + "0" + (id)).val('');
	$('#selectBloqueioTotal-' + "0" + (id)).val('');
	$('#selectDesbloqueioPortal-' + "0" + (id)).val('');

	$('#form-edit-cliente-' + "0" + (id)).trigger('reset');

	let abaAtual = "0" + id.toString();
	let formularioCliente = "#form-edit-cliente-" + abaAtual;
	$(formularioCliente + "input[type=text][name=Id]").val('')
	$("#row_contacts-" + abaAtual).html('')
	$(formularioCliente + " input[type=text][name=firstname]").val('')
	$(formularioCliente + " input[type=text][name=lastname]").val('')
	$("#row_accounts-" + abaAtual).html('')
	$(formularioCliente + " input[type=text][name=Nome]").val('')
	$(formularioCliente + " input[type=text][name=NomeFantasia_Sobrenome]").val('')
	$(formularioCliente + " input[type=text][name=Cpf_Cnpj]").val('')
	$(formularioCliente + " input[type=text][name=InscricaoEstadual]").val('')
	$(formularioCliente + " input[type=text][name=InscricaoMunicipal]").val('')
	$(formularioCliente + " select[name=TipoRelacao]").val('')
	$(formularioCliente + " input[type=text][name=Email]").val('')
	$(formularioCliente + " input[type=text][name=EmailTelemetria]").val('')
	$(formularioCliente + "  input[type=text][name=EmailAF]").val('')
	$(formularioCliente + " input[type=text][name=EmailLinker]").val('')
	$(formularioCliente + " input[type=text][name=EmailAlertaCerca]").val('')
	$(formularioCliente + " input[type=text][name=site]").val('')
	$(formularioCliente + " input[type=text][name=EmailNovo]").val('')
	$(formularioCliente + " input[type=text][name=gerenciadoraRiscoNome]").val('')
	$(formularioCliente + " select[name=contaPrimaria]").val('')
	$(formularioCliente + " select[name=conta-primaria]").val('')
	$(formularioCliente + " select[name=analistaSuporte]").val('')
	$(formularioCliente + " select[name=analistaSuporte]").val('')
	$(formularioCliente + " select[name=formaCobranca]").val('')
	$(formularioCliente + " select[name=formaCobranca]").val('')
	$(formularioCliente + " select[name=envioSustentavel]").val('')
	$(formularioCliente + " select[name=gerenciadoraDeRisco]").val('')
	$(formularioCliente + " select[name=segmentacaoManual]").val('')
	$(formularioCliente + " select[name=particularidade]").val('')
	$(formularioCliente + " input[name=nomeResponsavel]").val('')
	$(formularioCliente + " input[name=cargoResponsavel]").val('')
	$(formularioCliente + " input[type=text][name=telefone]").val('')
	$(formularioCliente + " input[type=text][name=telefone2]").val('')
	$(formularioCliente + " input[type=text][name=telefone3]").val('')
	$(formularioCliente + " input[type=text][name=celular]").val('')
	$(formularioCliente + " input[type=text][name=fax]").val('')
	$(formularioCliente + " select[name=Vendedor]").val('')
	$(formularioCliente + " select[name=Vendedor]").val('')
	$("#VendedorCliente-" + abaAtual).html('')

	$(formularioCliente + " select[name=CanalVenda]").val('')
	$(formularioCliente + " select[name=ClassificacaoCliente]").val('')
	$(formularioCliente + " select[name=Segmentacao]").val('')
	$(formularioCliente + " select[name=Segmentacao]").val('')

	// Endereço Principal
	$(formularioCliente + " input[type=text][name=CepPrincipal]").val('')
	$(formularioCliente + " input[type=text][name=enderecoPrincipal]").val('')
	$(formularioCliente + " input[type=text][name=bairroPrincipal]").val('')
	$(formularioCliente + " input[type=text][name=complementoPrincipal]").val('')

	// Endereço Cobrança
	$(formularioCliente + " input[type=text][name=CepCobranca]").val('')
	$(formularioCliente + " input[type=text][name=enderecoCobranca]").val('')
	$(formularioCliente + " input[type=text][name=bairroCobranca]").val('')
	$(formularioCliente + " input[type=text][name=complementoCobranca]").val('')

	// Endereço Entrega
	$(formularioCliente + " input[type=text][name=CepEntrega]").val('')
	$(formularioCliente + " input[type=text][name=enderecoEntrega]").val('')
	$(formularioCliente + " input[type=text][name=bairroEntrega]").val('')
	$(formularioCliente + " input[type=text][name=complementoEntrega]").val('')
	$('#listaGrupoEconomico').html('')

}

function renderizarNumerosAtividadesServico() {
	$("#ASAberta-" + abaSelecionada).html(atividadesDeServico[pegarIndiceDaAbaAtual()][0].length);
	$("#ASFechada-" + abaSelecionada).html(atividadesDeServico[pegarIndiceDaAbaAtual()][1].length);
	$("#ASCancelada-" + abaSelecionada).html(atividadesDeServico[pegarIndiceDaAbaAtual()][2].length);
	$("#ASAgendada-" + abaSelecionada).html(atividadesDeServico[pegarIndiceDaAbaAtual()][3].length);
}

function converterParaReal(valor) {
	try {
		return valor.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
	} catch (error) {
		console.error(error);
		return valor;
	}
}

/**
 * Função que requisita os contratos para api
 * status 1: Ativo, 2: Aguardando ativação, 3: cancelado, 4: suspenso, Null: Todos
 * @param {String} document - CPF ou CNPF do cliente
 * @param {*} status - Status do contrato
 * @returns {Promise}
 */
async function carregarOcorrencias(document) {
	return new Promise((resolve, reject) => {
		$.getJSON(`${URL_PAINEL_OMNILINK}/listar_ocorrencias?idCliente=${document}`, function (data) {
			resolve(data);
		})
			.fail(error => {
				console.error(error);
				showAlert("error","Erro ao buscar ocorrências do cliente!");
				reject(null);
			});
	})
}

function getButtonsAcoesItemDeContrato(idItemDeContrato) {
	const isDisabled = permissoes['out_alterarInfoItensContratoOmnilink'] ? '' : 'disabled';
	return `
	<button class="btn btn-primary" title="Visualizar Detalhes" onclick="exibirDadosDoContrato(this,'${idItemDeContrato}')"><i class="fa fa-eye" aria-hidden="true"></i></button>
	<button class="btn btn-primary btnEditItemDeContrato" title="Editar Item de Contrato" onclick="getInfoEditItemDeContrato(this,'${idItemDeContrato}')"><i class="fa fa-edit" aria-hidden="true"></i></button>
	<button class="btn btn-danger btnDeleteItemDeContrato" title="Remover Item de Contrato" onclick="removerItemDeContrato(this,'${idItemDeContrato}')" ${isDisabled}><i class="fa fa-trash" aria-hidden="true"></i></button>
	<button class="btn btn-primary btnEnviaFicha" title="Enviar ficha de ativação" onclick="EnviaFichaDeAtivacao(this,'${idItemDeContrato}')"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>`;
}
function getSwitchStatusItemDeContrato(idItemDeContrato, statecode) {
	const isDisabled = permissoes['out_alterarInfoItensContratoOmnilink'] ? '' : 'disabled';

	if (statecode == 0) {
		return `
			<label class="switch" title="Inativar Item de Contrato">
				<input class="switchStatusItemDeContrato" id="statusItemDeContrato_${idItemDeContrato}" type="checkbox" checked onclick="mudarStatusItemDeContrato('${idItemDeContrato}')" ${isDisabled}>
				<span class="slider"></span>
			</label>
		`;
	} else {
		return `
			<label class="switch" title="Ativar Item de Contrato">
				<input class="switchStatusItemDeContrato" id="statusItemDeContrato_${idItemDeContrato}" type="checkbox" onclick="mudarStatusItemDeContrato('${idItemDeContrato}')" ${isDisabled}>
				<span class="slider"></span>
			</label>
		`;
	}
}



/**
 * Remove spinners dos contratos
 */
function removerSpinnersContratos() {
	$("#spinnerAtivos").html("");
	$("#spinnerAguardando").html("");
	$("#spinnerCancelados").html("");
	$("#spinnerSuspensos").html("");
}

/**
 * Retorna status do contrato
 * @param {Integer} status 
 * @returns {String}
 */
function retornaTextoStatusContrato(status) {
	switch (status) {
		case 1:
			return "Ativos";
		case 2:
			return "Aguardando";
		case 3:
			return "Cancelados";
		case 4:
			return "Suspensos";

		default:
			return null;
	}
}

async function modalEditarOcorrencia(element) {
	$(element).attr('disabled', true).html(ICONS.spinner);
	$("#AssuntoEdit option:not([value='0'][disabled][selected])").remove();
	$("#TecnologiaEdit option:not([value='0'][disabled][selected])").remove();
	await popularSelects();
	let id = $(element).attr('data-id');
	let ocorrencia = await ajaxGetOcorrencia(id);

	if (ocorrencia) {
		let TicketNumber = ocorrencia.ticketnumber;
		let Assunto = ocorrencia._subjectid_value;
		let TipoOcorrencia = ocorrencia.casetypecode;
		let OrigemOcorrencia = ocorrencia.caseorigincode;
		let Tecnologia = ocorrencia._tz_tecnologia_value;
		let Descricao = ocorrencia.description;

		$('#IdEdit').val(id);
		$("#btn-anotacao-ocorrencia").attr("data-id", id);
		$("#btn-anotacao-ocorrencia").attr("ticket", TicketNumber);
		$('#TicketNumberEdit').val(TicketNumber);
		$('#TicketNumberEdit').prop("disabled", true);
		$('#AssuntoEdit').val(Assunto).trigger('change');
		$('#TipoOcorrenciaEdit').val(TipoOcorrencia).trigger('change');
		$('#OrigemOcorrenciaEdit').val(OrigemOcorrencia).trigger('change');
		$('#TecnologiaEdit').val(Tecnologia).trigger('change');
		$('#DescricaoEdit').val(Descricao);
		$(element).attr('disabled', false).html("<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-edit' aria-hidden='true'></i>");

		$("#modalOcorrenciaEdit").modal('show');

		//se a opção de alterar fila estiver ativa da última edição, 
		//é acionado um click no botão para ficar com o assunto alterável novamente
		if (document.getElementById("filas-row").style.display == "block") {
			$('#assunto-row').toggle("fast");
			$("#filas-row").toggle("fast");
			$('#filas').val("");
			$('#fila-nome').val("");
			$("#box-fila").prop("checked", false);
		}

		// guarda dados para auditoria
		auditoria.valor_antigo_ocorrencia = {
			Id: id,
			Cliente: buscarDadosClienteAbaAtual()?.Id,
			TicketNumber,
			// NomeUsuarioGestor,
			Assunto,
			TipoOcorrencia,
			OrigemOcorrencia,
			Descricao
		}
	} else {
		$(element).attr('disabled', false).html("<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-edit' aria-hidden='true'></i>");
		showAlert("error",'Não foi possível carregar os dados da ocorrencia');
	}
}

function ajaxGetOcorrencia(id) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/carregarOcorrencia/${id}`,
			success: (data) => resolve(data),
			error: (error) => reject(error),
		})
	});
}

function mostrarInformacoesDetalhadasContrado(button) {
	exibirDadosDoContrato(button, $('#idContratoModal').val())
}

/**
 * Exibe o detalhamento do contrato no modal
 * @param {String} idContrato 
 */
async function exibirDadosDoContrato(botao, idContrato) {
	let button = $(botao);
	//muda o texto de acordo com o botão clicado
	let textoBotao = button[0].id == "btn-info-contrato" ? "Mostrar Informações Detalhadas" : '<i class="fa fa-eye" aria-hidden="true"></i>';

	button.attr('disabled', true).html(ICONS.spinner);
	carregarDadosDoContrato(idContrato)
		.then(itens => {
			//verifica se o modal de busca separada dos contratos está amostra para esconder
			if ($("#modalBuscarContrato").data('bs.modal')?.isShown) $('#modalBuscarContrato').modal('hide')

			button.attr('disabled', false).html(textoBotao);

			if (!Array.isArray(itens)) {
				itens = [];
				showAlert("error",'Falha ao buscar itens do contrato!');
			}

			carregarDetalhesContrato(idContrato).then(detalhes => {
				let contrato = {
					detalhes: detalhes,
					itens
				}

				renderizarDetalhesDoContrato(contrato);
			});
		})
		.catch(error => {
			if ($("#modalBuscarContrato").data('bs.modal')?.isShown)
				$('#modalBuscarContrato').modal('hide')

			button.attr('disabled', false).html(textoBotao);
			console.error(error);
			showAlert("error","Erro ao buscar dados do contrato!");
		})
}

/**
 * Função que retorna o html do select para mudança do status do item de contrato
 * status 1: Ativo, 2: Aguardando ativação, 3: cancelado, 4: suspenso
 * @param {String} idContrato
 * @param {Integer} statusItem
 */
function getselectStatusItemContrato(idContrato, statusItem) {
	return `
		<select class="form-control" id="selectStatusItemContrato_${idContrato}">
			<option value="1" ${statusItem == 1 ? 'selected' : ''}>Ativo</option>
			<option value="2" ${statusItem == 2 ? 'selected' : ''}>Aguardando Ativação</option>
			<option value="3" ${statusItem == 3 ? 'selected' : ''}>Cancelado</option>
			<option value="4" ${statusItem == 4 ? 'selected' : ''}>Suspenso</option>
		</select>
	`;
}

function returnOptionsSelectProvidencias() {
	const providencias = $("#table_providencias").DataTable().rows().data().toArray();

	let options = '<option></option>';
	if (providencias && providencias.length > 0) {
		providencias.forEach(providencia => {
			options += `<option value="${providencia.tz_providenciasid}">${providencia.tz_name}</option>`
		});
	}
	return options;
}

/**
 * Faz requisição para o CRM retornar os detalhes de um Contrato.
 * @param {String} idContrato Id do Contrato.
 * @returns {Promise} Detalhes do Contrato.
 */
async function carregarDetalhesContrato(idContrato) {
	return new Promise((resolve, reject) => {
		$.getJSON(`PaineisOmnilink/ajax_contrato/${idContrato}`, function (data) {
			if (data.status == 1) {
				resolve(data.contratos[0]);
			} else {
				console.error(data);
				reject(null);
			}
		})
			.fail(error => {
				reject(error);
			});
	});
}

/**
 * Faz requisição para retornar os dados do contrato
 * @param {String} idContrato 
 * @returns {Promise}
 */
async function carregarDadosDoContrato(idContrato) {
	return new Promise((resolve, reject) => {
		$.getJSON(`PaineisOmnilink/ajax_itens_contrato/${idContrato}`, function (data) {
			if (data.status == 1) {
				resolve(data.itens);
			} else {
				console.error(data);
				reject(null);
			}
		})
			.fail(error => {
				reject(error);
			});
	});
}

/**
 * Renderiza no modal os dados do contrato
 * @param {Object} dados 
 */
function renderizarDetalhesDoContrato(contrato) {
	// Limpa tabela de itens do contrato
	tableItensDoContrato.clear();
	limparModalDetalhesDoContrato();
	let { itens, detalhes } = contrato;
	if (detalhes) {
		// Exibe detalhamento
		$("#tituloDetalhesDoContrato").html(detalhes.codigo ? '- ' + detalhes.codigo : '');
		$("#codeDetalhesDoContrato").html(detalhes.codigo ?? '-');
		$("#statusDetalhesDoContrato").html(LEGENDA_STATUS_ITEM_CONTRATO[detalhes.status] ?? '-');
		let dataAtivacao = new Date(detalhes.dataAtivacao).toLocaleDateString();
		$("#activationDateDetalhesDoContrato").html(detalhes.dataAtivacao ? dataAtivacao : '-');
		let dataTermino = new Date(detalhes.dataTermino).toLocaleDateString();
		$("#endDateDetalhesDoContrato").html(detalhes.dataTermino ? dataTermino : '-');
		$("#technologyDetalhesDoContrato").html(detalhes.tecnologia ?? '-');
		$("#trackerPlanDetalhesDoContrato").html(detalhes.plano ?? '-');
		$("#trackerSerialNumberDetalhesDoContrato").html(detalhes.serialEquipamento);
		$("#vehicleLicenseNumberDetalhesDoContrato").html(detalhes.placa ?? '-');
	}
	if (itens) {
		// Exibe itens do contrato
		itens.forEach(item => {
			if (item) {
				tableItensDoContrato.row.add({
					code: item.codigo ?? '-',
					amount: item.valor ? converterParaReal(item.valor) : '-',
					qtd: item.quantidade ?? '-',
					startDate: item.dataInicio ?? '',
					endDate: item.dataTermino ?? '',
					productClassificationName: item.classificacaoProduto ?? '-',
					revenueGroupName: item.grupoReceita ?? '-',
					serviceName: item.produto ?? '-',
					id: item.id,
				});
			}
		});
		tableItensDoContrato.draw();
	}


	$("#modalDetalhesDoContrato").modal('show');
}

function limparModalDetalhesDoContrato() {
	// Limpa Tabela
	tableItensDoContrato.clear().draw();
	$("#tituloDetalhesDoContrato").html('');
	$("#codeDetalhesDoContrato").html('-');
	$("#statusDetalhesDoContrato").html('-');
	$("#activationDateDetalhesDoContrato").html('-');
	$("#endDateDetalhesDoContrato").html('-');
	$("#technologyDetalhesDoContrato").html('-');
	$("#trackerPlanDetalhesDoContrato").html('-');
	$("#trackerSerialNumberDetalhesDoContrato").html('-');
	$("#vehicleLicenseNumberDetalhesDoContrato").html('-');
}

/**
 * Renderizar dados na tabela de Atividades de Serviço
 * @param {Object} atividadesDeServicos Lista com as Atividades de Serviços
 */
function renderizarDadosTabelaAtividadesDeServico(atividadesDeServicos) {
	$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().clear();

	if (atividadesDeServicos) {
		atividadesDeServicos.forEach(atividadeDeServico => {
			if (atividadeDeServico) {
				let { contract, incident } = atividadeDeServico;
				let linha = {
					Id: atividadeDeServico.Id,
					Code: atividadeDeServico.Code,
					trackerSerialNumberInstall: atividadeDeServico.trackerSerialNumberInstall,
					provider: atividadeDeServico.provider,
					serviceName: atividadeDeServico.serviceName,
					serviceNameComplement: atividadeDeServico.serviceNameComplement,
					subject: atividadeDeServico.subject,
					scheduledstart: atividadeDeServico.scheduledstart,
					scheduledend: atividadeDeServico.scheduledend,
					numeroOs: atividadeDeServico.numeroOs,
					StatusCode: legendaStatusCodeAtividadesDeServico[atividadeDeServico.StatusCode],
				}

				if (atividadeDeServico.acoes) {
					linha['acoes'] = atividadeDeServico.acoes;
				} else {
					linha['acoes'] = getButtonsActionTableAtividadesDeServico(atividadeDeServico.Id, contract, incident, atividadeDeServico.Status, atividadeDeServico.numeroOs);

				}

				$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row.add(linha);
			}
		});
	}
	$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().draw();


	$('#atividadesDeServicoContainer-' + abaSelecionada).css({ "display": "table" });

}

function retornaStutusNuloBaseInstalada(status) {

	switch (status) {
		default:
			return "null";
	}
}
function retornaTextoStatusAtividadesDeServico(status) {

	switch (status) {
		case 0:
			return "Aberta";
		case 1:
			return "Fechada";
		case 2:
			return "Cancelada";
		case 3:
			return "Agendada";

		default:
			return null;
	}
}

/**
 * Evento de envio do formulário de busca de Base Instalada
 */
$('#formularioBuscarBaseInstalada').submit(function (event) {
	let botaoBusca = $('#botaoBuscaBaseInstalada');
	let dados = $(this).serializeArray();

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_bases_instaladas_placa_serial`,
		method: 'POST',
		data: {
			'filtro': dados[0].value,
			'valor': dados[1].value
		},
		beforeSend: function () {
			$('#resultadosBuscaBaseInstalada').hide();

			botaoBusca.attr('disabled', true).html('Procurando ' + ICONS.spinner);
			tabelaBuscaBaseInstalada.clear().draw();
		}
	})
		.done(function (dados) {
			if (dados['error'] != '') {
				showAlert("error",'Erro ao buscar Base Instalada.');
				return;
			}

			if (dados['data'].length == 0) {
				showAlert("error",'Base Instalada não encontrada.');
				return;
			}

			renderizarResultadoBuscaBaseInstalada(dados['data']);
		})
		.fail(function () {
			showAlert("error",'Erro ao buscar Base Instalada.');
		})
		.always(function () {
			botaoBusca.attr('disabled', false).html('Procurar');
		});

	event.preventDefault();
});

/**
 * Renderiza os resultados na Tabela de Resultados de Busca de Base Instalada
 * 
 * @param {array} basesInstaladas Lista com os resultados para serem rendeizados
 */
function renderizarResultadoBuscaBaseInstalada(basesInstaladas) {
	tabelaBuscaBaseInstalada.rows.add(basesInstaladas).draw();
	tabelaBuscaBaseInstalada.columns.adjust().draw();

	$('#resultadosBuscaBaseInstalada').show();
}

/**
 * Limpar campo de pesquisa e tabela de resultados se o Modal de Buscar Base
 * Instalada seja fechado
 */
$('#modalBuscarBaseInstalada').on('hide.bs.modal', function () {
	$('#baseInstaladaBusca').val('');
	tabelaBuscaBaseInstalada.clear().draw();
	$('#resultadosBuscaBaseInstalada').hide();
});

/**
 * Adapta o campo de busca de acordo com o tipo selecionado pelo usuário no
 * formulário de busca de Base Instalada
 */
$('#baseInstaladaFiltro').on('change', function () {
	let campoBusca = $('#baseInstaladaBusca');
	let filtro = $(this).find(':selected').val();

	let dica = filtro == 'serial' ?
		'Digite o serial do equipamento' :
		'Digite a placa do veículo';

	campoBusca.attr('placeholder', dica);
	campoBusca.val('');
});

function exibirClienteBuscaBaseInstalada(document) {
	buscarClientePorCpfCnpj(document);
	$('#modalBuscarBaseInstalada').modal('hide');
}

// Button Base Instalada
function getButtonsAcoesBasesInstalada() {
	let buttons = '';

	if (dado.tz_base_instalada_clienteid) {
		buttons += `<a class="btn btn-primary" style="margin: 0 5px" title="Visualizar Contrato" 
					onclick="abrirModalContratoAtividadesDeServico(this, '${contract.Id}')">
						<i class="fa fa-file" aria-hidden="true"></i>
					<a>`;
	}

	return buttons;
}

function getButtonsActionTableAtividadesDeServico(idAtividadeServico, contract, incident, status, os) {

	let buttons = ``;

	// Botão de visualizar contrato
	if (contract && Object.keys(contract).length > 0 && contract.Id != undefined && contract.Id != "") {
		buttons += `<a class="btn btn-primary btn-body-datatable" style="margin: 0 10%; border-radius: 3px !important;" title="Visualizar Contrato" 
					onclick="abrirModalContratoAtividadesDeServico(this, '${contract.Id}' , '${idAtividadeServico}')">
						<i class="fa fa-file" aria-hidden="true" style="padding-top : 12%;"></i>
					<a>
					`;
	}
	// Botão de visualizar ocorrência
	if (incident && Object.keys(incident).length > 0 && incident.Id != undefined && incident.Id != "") {
		buttons += `<a class="btn btn-primary btn-body-datatable" style="margin: 0 10%; border-radius: 3px !important;" title="Visualizar Ocorrência"
					onclick="abrirModalOcorrenciasAtividadesDeServico(this, '${incident.Id}')">
						<i class="fa fa-ticket" aria-hidden="true" style="padding-top : 12%;"></i>
					<a>
					`;

	}
	// botão de editar e remover atividade de serviço
	if (status != undefined && status != 1 && status != 2) {
		buttons += `
			<a class="btn btn-primary btn-body-datatable" style="margin: 0 10%; border-radius: 3px !important;" id="btn_status_na_${idAtividadeServico}" title="Alterar Status da Atividade de Serviço" onclick="controleAlterarStatusAtividadeDeServico(this, '${idAtividadeServico}')">
				<i class="fa fa-exclamation" aria-hidden="true" style="padding-top : 12%;"></i>
			<a>
			<a class="btn btn-primary btn-body-datatable" style="margin: 0 10%; border-radius: 3px !important;" title="Editar Atividade de Serviço" onclick="getInfoDatasAtividadesDeServico(this, '${idAtividadeServico}')">
				<i class="fa fa-calendar" aria-hidden="true" style="padding-top : 12%;"></i>
			<a>
			<a class="btn btn-primary btn-body-datatable" style="margin: 0 10%; border-radius: 3px !important;" title="Alterar NA" onclick="abrirModalNA(this, '${idAtividadeServico}',null, '${os}')">
				<i class="fa fa-pencil" aria-hidden="true" style="padding-top : 12%;"></i>
			<a>
		`;
	}

	// Botão de visualizar info NA
	buttons += `<a class="btn btn-primary btn-body-datatable" style="margin: 0 10%; border-radius: 3px !important;" title="Visualizar Info da NA" 
		onclick="abrirModalInformacoesNA(this, '${idAtividadeServico}')">
		<i class="fa fa-info" aria-hidden="true" style="padding-top : 12%;"></i>
	<a>
	`;

	return buttons;
}

/**
 * Função que monta select para alterar status da atividade de serviço
 */
async function controleAlterarStatusAtividadeDeServico(button, idAtividadeServico) {

	let rowData = $("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${idAtividadeServico}`).data();
	let currentPage = $("#tableAtividadesDeServico-" + abaSelecionada).DataTable().page();
	let btn = $(button);
	let htmlBtn = btn.html();
	if (ControleTableStatusAtividadeDeServico.btnActive) { //realiza atualização do status
		if (confirm('Você tem certeza que deseja alterar o status da Atividade de Serviço?')) {
			btn.attr('disabled', true).html(iconSpinner);
			let statecode = $("#table_na_statuscode option:selected").parent().attr('state');
			let statuscode = $("#table_na_statuscode option:selected").val();
			let novosDados = { idAtividadeServico, statuscode, statecode };
			let statecodeAnterior = retornaAntigoStatecodeAtividadeDeServico(idAtividadeServico);
			// legendaStatusCodeAtividadesDeServico.
			let statuscodeAnterior = ControleTableStatusAtividadeDeServico.statusAnterior;
			let dadosAntigos = { statecodeAnterior, statuscodeAnterior };
			let response = await alterarStatusAtividadeDeServico(novosDados, dadosAntigos)
				.catch(error => {
					btn.attr('disabled', false).html(htmlBtn);
					showAlert("error",'Erro ao atualizar status da atividade de serviço!');
					console.error(error);
					// Atualiza a tabela
					$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${idAtividadeServico}`).data(rowData).page(currentPage).draw(false);
				});


			btn.attr('disabled', false).html(htmlBtn);
			// Remove id do botão ativo
			ControleTableStatusAtividadeDeServico.btnActive = null;
			if (response.status) {
				alert("Status da atividade de serviço alterado com sucesso!");
				rowData['StatusCode'] = response.data.statuscode;
				// Informa que o botão está desativado
				atualizarDadosAtividadeDeServico(
					rowData,
					response.data.statuscode,
					response.data.statecode
				);

			} else {
				showAlert("error",response.erro);
				rowData['StatusCode'] = ControleTableStatusAtividadeDeServico.statusAnterior;
				$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${idAtividadeServico}`).data(rowData).page(currentPage).draw(false);
			}

		} else {
			// Atualiza statuscode da tabela para o status guardado anteriormente
			rowData['StatusCode'] = ControleTableStatusAtividadeDeServico.statusAnterior;
			// Marca botão como desativado
			ControleTableStatusAtividadeDeServico.btnActive = null;
			$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${idAtividadeServico}`).data(rowData).page(currentPage).draw(false);
		}

		// Retorna o objeto ControleTableStatusAtividadeDeServico para null
		ControleTableStatusAtividadeDeServico.btnActive = null;
		ControleTableStatusAtividadeDeServico.statusAnterior = null;

		// Atualiza número de atividades de serviço
		renderizarNumerosAtividadesServico();


	} else {//monta select e adiciona na tabela
		// Guarda status anterior
		ControleTableStatusAtividadeDeServico.statusAnterior = rowData.StatusCode;

		// Monta select
		rowData['StatusCode'] = getSelectStatusAtividadeDeServico();
		ControleTableStatusAtividadeDeServico.btnActive = idAtividadeServico;
		$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${idAtividadeServico}`).data(rowData).page(currentPage).draw(false);
	}
}

/**
 * Controla o estado do botão de alterar status da atividade de serviço
 */
$(document).ready(function () {
	$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().on('draw', function () {
		if (ControleTableStatusAtividadeDeServico.btnActive) {
			$(`#btn_status_na_${ControleTableStatusAtividadeDeServico.btnActive}`).addClass('btnActive');
		} else {
			$("#tableAtividadesDeServico").find('a.btnActive').removeClass('btnActive');
		}
	});
});

/**
 * Função que realiza requisição para alterar status da atividade de serviço 
 * @param {String} idAtividadeDeServico 
 * @param {Integer} statuscode 
 * @param {Integer} statecode 
 * @return {Promise}
 */
async function alterarStatusAtividadeDeServico(novosDados, dadosAntigos) {
	return new Promise((resolve, reject) => {
		const url = `${URL_PAINEL_OMNILINK}/ajax_alterar_status_atividade_de_servico`;

		salvar_auditoria(url, 'update', dadosAntigos, novosDados)
			.then(async () => {
				postRequest(url, novosDados)
					.then(callback => {
						resolve(callback);
					})
					.catch(error => reject(error));
			})
			.catch(error => reject(error));
	});
}

/**
 * Função que atualiza dados das atividades de serviços que estão sendo
 * mantidos nos arrays para otimizar o carregamento
 */
function atualizarDadosAtividadeDeServico(atividade, newStatuscode, newStatecode) {

	// Atualiza objeto da atividade de servico
	atividade.Status = newStatecode;
	atividade.StatusCode = newStatuscode;
	const oldStatecode = retornaAntigoStatecodeAtividadeDeServico(atividade.Id);
	const currentPage = $("#tableAtividadesDeServico-" + abaSelecionada).DataTable().page();

	// A atividade de serviço continua no mesmo array. Nesse caso só será necessário atualizar os dados da atividade
	if (newStatecode == oldStatecode) {
		// Atualiza dados na tabela
		$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${atividade.Id}`).data(atividade).page(currentPage).draw(false);
	} else { // A atividade deverá ser transferida para o novo array correspondente ao seu statecode
		$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${atividade.Id}`).remove().page(currentPage).draw(false);
		transferirAtividadeDeServicoParaNovoArray(atividade, oldStatecode);
	}
}
/**
 * Função que retorna qual a chave do statuscode que a atividade de serviço está guardada
 * no objeto 'atividadesDeServico'. Caso não encontre a atividade de serviço, retorna -1
 * @param {String} idAtividadeDeServico
 * @return {Integer}
 */
function retornaAntigoStatecodeAtividadeDeServico(idAtividadeDeServico) {
	let indexAtividade = -1;
	let keysAtividadesDeServico = Object.keys(atividadesDeServico)
	keysAtividadesDeServico.forEach(key => {
		const arrayAtividades = atividadesDeServico[key];
		const atividadeFiltrada = arrayAtividades.filter(a => a.Id == idAtividadeDeServico).length;
		// Se achar a atividade de serviço, retorna o index do array 'keysAtividadesDeServico' que é igual ao statecode da atividade
		if (atividadeFiltrada > 0) {
			indexAtividade = Object.keys(atividadesDeServico).indexOf(key);
		}
	});

	return indexAtividade;
}


/**
 * Função que transfere atividade para novo array correspondente ao seu statecode no objeto 'atividadesDeServico'
 * @param {Object} atividade
 * @param {Integer} oldStatecode
 */
function transferirAtividadeDeServicoParaNovoArray(atividade, oldStatecode) {
	// retorna posição da atividade de serviço no array
	const getPosicao = (id, array) => {
		return array.findIndex(a => a.Id == id);
	}

	// Remove a atividade de servico do array que ela se encontra
	if (oldStatecode >= 0) {
		atividadesDeServico[pegarIndiceDaAbaAtual()][oldStatecode].splice(getPosicao(atividade.Id, atividadesDeServico[pegarIndiceDaAbaAtual()][oldStatecode]), 1);
	}

	// Adiciona atividade no novo array
	if (atividade.Status >= 0) {
		atividadesDeServico[pegarIndiceDaAbaAtual()][atividade.Status].push(atividade);
	}
}

/**
 * Função que monta o select do status da atividade de serviço
 * @returns {String}
 */
function getSelectStatusAtividadeDeServico() {
	let select = `<select class="form-control" id="table_na_statuscode" style="100px !important" name="table_na_statuscode">`;
	// Percorre as legendas do statecode para montar o optgroup
	for (let state in statusAtividadesDeServico) {
		let optgroup = `<optgroup label="${legendasStateCodeAtividadeDeServico[state]}" state="${state}">`;
		let statusCode = statusAtividadesDeServico[state];
		statusCode.forEach(status => {
			let legendaStatus = legendaStatusCodeAtividadesDeServico[status];
			optgroup += `<option value="${status}" title="${legendaStatus}" ${ControleTableStatusAtividadeDeServico.statusAnterior == legendaStatus ? 'selected' : ''}>${legendaStatus}</option>`;
		});

		optgroup += `</optgroup>`;
		select += optgroup;
	}

	select += `</select>`;

	return select;
}

/**
 * Função que busca informações da atividade de serviço para edição
 */
function getInfoAtividadesDeServico(button, idAtividadeServico) {
	let btn = $(button);
	let htmlBtn = btn.html();
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_info_atividade_servico/${idAtividadeServico}`,
		type: "GET",
		beforeSend: function () {
			btn.attr('disabled', true).html(iconSpinner);
		},
		success: function (callback) {
			btn.attr('disabled', false).html(htmlBtn);
			callback = JSON.parse(callback);
			if (callback.status) {
				let { inputs, selects } = callback.data;
				inserirDadosCamposFormulario(inputs, selects);
				// guarda dados para auditoria
				salvar_valor_antigo_atividade_servico(inputs, selects);
				$("#modalAtividadeDeServico").modal('show');
			}
		},
		error: function (erro) {
			console.error(erro);
			showAlert("error",'Erro ao buscar Atividade de Serviço!');
		}
	});
}

/**
 * Retorna informações de datas da atividade de serviço para ser alterado
 * @param {String} button 
 * @param {String} idAtividadeServico 
 */
function getInfoDatasAtividadesDeServico(button, idAtividadeServico) {
	let btn = $(button);
	let htmlBtn = btn.html();
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_info_datas_atividade_servico/${idAtividadeServico}`,
		type: "GET",
		beforeSend: function () {
			btn.attr('disabled', true).html(iconSpinner);
		},
		success: function (callback) {
			btn.attr('disabled', false).html(htmlBtn);
			callback = JSON.parse(callback);
			if (callback.status) {
				let { inputs } = callback.data;
				$("#formAlterarDatasAtividadeDeServico").find("#na_alterar_data_activityid").val(inputs.na_activityid);
				$("#formAlterarDatasAtividadeDeServico").find("#na_alterar_data_scheduledstart").val(inputs.na_scheduledstart);
				$("#formAlterarDatasAtividadeDeServico").find("#na_alterar_data_scheduledend").val(inputs.na_scheduledend);
				// guarda dados para auditoria
				salvar_valor_antigo_atividade_servico(inputs);

				$("#modalAlterarDatasAtividadeDeServico").modal('show');
			}
		},
		error: function (erro) {
			console.error(erro);
			showAlert("error",'Erro ao buscar Atividade de Serviço!');
		}
	});
}

function salvar_valor_antigo_atividade_servico(inputs, selects) {
	// atribui objeto ao valor antigo da atividade de servico
	auditoria.valor_antigo_atividade_servico = {};
	// Preenche os inputs
	for (key in inputs) {
		auditoria.valor_antigo_atividade_servico[key] = inputs[key];
	}
	// Preenche os selects
	for (key in selects) {
		if (selects[key].id) {
			auditoria.valor_antigo_atividade_servico[key] = selects[key].id;
		}
	}
}

// Limpa inputs ao fechar modal de atividades de serviço
$(document).on('hide.bs.modal', "#modalAtividadeDeServico", function () {
	limpaInputsModal('modalAtividadeDeServico')
})
/**
 * Função que remove atividade de serviço
 */
async function removerAtividadeDeServico(button, idAtividadeServico) {
	if (confirm("Você tem certeza que deseja remover a atividade de serviço?")) {
		let btn = $(button);
		let htmlBtn = btn.html();
		btn.attr('disabled', true).html(iconSpinner);
		const url = `${URL_PAINEL_OMNILINK}/ajax_remover_atividade_servico/${idAtividadeServico}`
		salvar_auditoria(url, 'delete', null, { idAtividadeServicoRemovida: idAtividadeServico })
			.then(async () => {
				deleteRequest(url)
					.then(callback => {
						btn.attr('disabled', false).html(htmlBtn);
						callback = JSON.parse(callback);
						if (callback.status) {
							alert(callback.msg);
							$("#tableAtividadesDeServico-" + abaSelecionada).DataTable().row(`#${idAtividadeServico}`).remove().draw();
							// atualiza número de atividades na aba selecionada
							let numAtividades = $("#tableAtividadesDeServico-" + abaSelecionada).DataTable().rows().data().length;
							$("#listaAtividadesDeServico>li.active>a>small").html(numAtividades);
						}
					})
					.catch(error => {
						btn.attr('disabled', false).html(htmlBtn);
						console.error(error);
						showAlert("error",'Erro ao remover atividade de serviço!');
					});
			})
			.catch(error => {
				console.error(error);
			});
	}
}

/**
 * Renderiza informações na tabela de ordens de serviço
 * @param {Object} dados 
 */
function renderizarDadosOrdensServico(dados) {

	tableOrdensServico.clear();
	if (dados) {
		// Adiciona dados ao datatable
		tableOrdensServico.rows.add(dados).draw();
	}
}

/**
 * Chama no back end a função de listar ordens de serviço
 * @param idAtividade
 */
function buscarOrdensServico(atividade_de_servico_id) {
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/listarOS`,
		type: 'GET',
		dataType: 'JSON',
		data: { atividade_de_servico_id },
		success: function (callback) {
			// renderiza dados da providência
			renderizarDadosOrdensServico(callback.dados);

			// Remove alert de carregamento
			$('div .subtitulo .alert').remove();
		},
		error: function (error) {
			showAlert("error","Ocorreu um erro ao buscar as ordens de serviço.")
		},
		complete: function () {
			$("#modalContratoAtividadesDeServico").modal("show");
		}
	})
}

/**
 * Chama no back end a função de listar itens da ordem de serviço
 * @param numeroOS
 * @param idOS
 */
function listarItensOS(numeroOS, idOS, busca = false) {
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/buscarItensOS`,
		type: 'GET',
		dataType: 'JSON',
		data: { idOS },
		success: function (callback) {

			if (callback.code === 200) {
				if ($("#modalBuscarOS").is(":visible")) {
					tableItensOSBusca.clear();
					let dados = callback.data;
					var tot = 0
					dados.forEach(dado => {
						tot += dado.tz_valor_total
					})
					$("#busca-valor-total-os").html(tot.toFixed(2) ?? "-")
					tableItensOSBusca.rows.add(dados).draw();
				} else {
					tableItensOS.clear();
					let dados = callback.data;
					var tot = 0
					dados.forEach(dado => {
						tot += dado.tz_valor_total
					})
					$("#valor-total-os").html(tot.toFixed(2) ?? "-")
					$("#valor-total-os-na").html(tot.toFixed(2) ?? "-")
					tableItensOS.rows.add(dados).draw();
				}
			}

			tableItensOS.clear();
			tableItensOSNA.clear()
			let dados = callback.data;
			// Adiciona dados ao datatable
			if (dados) {
				if (busca) {
					tableItensOS.rows.add(dados)
					tableItensOS.draw();
				} else {
					tableItensOSNA.rows.add(dados)
					tableItensOSNA.draw();
				}
			}
		},
		error: function (error) {
			showAlert("error","Ocorreu um ao listar os itens da OS.")
		},
		complete: function () {
			if (busca && !$("#modalBuscarOS").is(":visible")) {
				$("#modal-os").modal("show").css('overflow-y', 'auto');
			}
		}
	})
}

/**
 * Chama no back end a função de visualizar a ordem de serviço
 * @param {*} button 
 * @param numeroOS
 */
function visualizarOS(button, numeroOS) {

	if (button) {
		botao = $(button);
		valorAntigoBotao = botao.html();
		botao.html(ICONS.spinner + " Processando...")

		$('.ordem-servico').addClass("disable-div");
	}

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/buscarOS`,
		type: 'GET',
		dataType: 'JSON',
		data: { numeroOS },
		success: function (callback) {
			if (callback.status === 200) {
				let dadosOS = callback.data[0];

				//permite campos alteráveis apenas para as NA's abertas e agendadas
				if (dadosOS.statecodeAtividadeDeServico !== 0 && dadosOS.statecodeAtividadeDeServico !== 3) {
					verificarPermissoesOS(false)
					$("#dropdown-modal-os").hover(function () {
						$('#btn-modal-adicionar-item-os').css("display", "none")
					})
				} else {
					$("#dropdown-modal-os").hover(function () {
						$('#btn-modal-adicionar-item-os').css("display", "block")
					}, function () {
						$('#btn-modal-adicionar-item-os').css("display", "none")
					});

					verificarPermissoesOS(true)
				}

				listarItensOS(numeroOS, dadosOS.tz_ordem_servicoid)

				$("#header-os").html(`OS nº ${numeroOS}`)
				$("#numero-os").html(numeroOS)
				$('#id-os').val(dadosOS.tz_ordem_servicoid)
				$("#data-criacao-os").html(dadosOS.createdon)
				$("#data-modificacao-os").html(dadosOS.modifiedon)
				$("#tipo-servico-os").html(dadosOS.tz_tipo_servico)
				$("#modificado-por-os").html(dadosOS.modifiedby ?? "-")
				$("#valor-total-os").html(dadosOS.tz_valor_total ?? "-")
				$("#razao-status-os").val(dadosOS.statuscodevalue)
				$("#observacoes-os").val(dadosOS.tz_observacoes)

				listarItensOS(null, dadosOS.tz_ordem_servicoid, button ? true : false)
				if (button) {
					$("#header-os").html(`OS nº ${numeroOS}`)
					$("#numero-os").html(numeroOS)
					$('#id-os').val(dadosOS.tz_ordem_servicoid)
					$("#data-criacao-os").html(dadosOS.createdon)
					$("#data-modificacao-os").html(dadosOS.modifiedon)
					$("#tipo-servico-os").html(dadosOS.tz_tipo_servico)
					$("#modificado-por-os").html(dadosOS.modifiedby ?? "-")
					$("#valor-total-os").html(dadosOS.tz_valor_total ?? "-")
					$("#razao-status-os").val(dadosOS.statuscodevalue)
					$("#observacoes-os").val(dadosOS.tz_observacoes)
				} else {
					$("#header-os-na").html(`OS nº ${numeroOS}`)
					$("#numero-os-na").html(numeroOS)
					$('#id-os-na').val(dadosOS.tz_ordem_servicoid)
					$('#id-os').val(dadosOS.tz_ordem_servicoid)
					$("#data-criacao-os-na").html(dadosOS.createdon)
					$("#data-modificacao-os-na").html(dadosOS.modifiedon)
					$("#tipo-servico-os-na").html(dadosOS.tz_tipo_servico)
					$("#modificado-por-os-na").html(dadosOS.modifiedby ?? "-")
					$("#valor-total-os-na").html(dadosOS.tz_valor_total ?? "-")
					$("#razao-status-os-na").val(dadosOS.statuscodevalue)
					$("#observacoes-os-na").val(dadosOS.tz_observacoes)
				}
			}

		},
		error: function (error) {
			showAlert("error","Ocorreu um erro ao buscar a ordem de serviço.")
		},
		complete: function () {
			if (button) {
				botao = $(button);
				botao.html(valorAntigoBotao).attr('disabled', false)
				$('.ordem-servico').removeClass("disable-div");
			}
		}
	})
}

/**
 * Renderiza informações na tabela de itens da OS
 * @param {Object} dados 
 */
function renderizarDadosItensDeServico(dados) {

	tabelaItensDeServico.clear();
	if (dados) {
		// Adiciona dados ao datatable
		tabelaItensDeServico.rows.add(dados).draw();
	}
}


/**
 * Função que recebe o contexto do botão (this) e abre um modal exibindo as informações
 * do contrato da atividade de serviço selecionada
 * @param {*} button 
 */
function abrirModalContratoAtividadesDeServico(button, contratoId, idAtividadeServico) {
	let btn = $(button);
	let textoBotao = '<i class="fa fa-file" aria-hidden="true"></i>';

	btn.html(ICONS.spinner).attr('disabled', true);
	if (btn[0].id == "btn-contrato-modal-na")
		textoBotao = 'Visualizar Contrato';

	limpar_modal_contrato_atividade_servico();
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_item_contrato_atividade_servico/${contratoId}`,
		type: 'GET',
		success: function (data) {
			btn.html(textoBotao).attr('disabled', false);
			data = JSON.parse(data);

			if (data.code == 200) {
				let item = data.item;

				if (item.codigo) $("#tituloContratoModalAtividadesDeServico").html("- " + item.codigo)
				else $("#tituloContratoModalAtividadesDeServico").html('')

				$('#codeContratoNA').html(item.codigo ? item.codigo : '');
				$('#numeroAfNA').html(item.numero_af ? item.numero_af : '');
				$('#modeloTipoInformadoAtivacaoNA').html(item.codigo_item_contrato ? item.codigo_item_contrato : '');
				$('#codigoItemContratoNA').html(item.codigo_item_contrato ? item.codigo_item_contrato : '');
				$('#statusItemContratoNA').html(item.status_item_contrato ? item.status_item_contrato : '');
				$('#placaVeiculoNA').html(item.placa ? item.placa : '');
				$('#serialVeiculoNA').html(item.numero_serie_modulo_principal ? item.numero_serie_modulo_principal : '');
				$('#chassiVeiculoNA').html(item.chassi ? item.chassi : '');
				$('#renavamVeiculoNA').html(item.renavam ? item.renavam : '');
				$('#modeloVeiculoNA').html(item.modelo_tipo_ativacao ? item.modelo_tipo_ativacao : '');
				$('#tipoVeiculoNA').html(item.tipo_veiculo ? item.tipo_veiculo : '');
				$('#integraContratoVendaNA').html(item.integra_contrato ? item.integra_contrato : '');
				$('#reenviarApoliceGraberVendaNA').html(item.reenviar_apolice_graber ? item.reenviar_apolice_graber : '');
				$('#canalVendaNA').html(item.canal_venda ? item.canal_venda : '');
				$('#cenarioVendaNA').html(item.cenario_venda ? item.cenario_venda : '');
				$('#tecnologiaVendaNA').html(item.tecnologia ? item.tecnologia : '');
				$('#afVendaNA').html(item.af ? item.af : '');
				$('#valorLicencaBaseVendaNA').html(item.taxa_visita_base);
				$('#planoProdutoNA').html(item.plano_linker ? item.plano_linker : '');
				$('#rastreadorProdutoNA').html(item.rastreador ? item.rastreador : '');
				$('#valorDeslocamentoNA').html(item.valor_deslocamento_km_base ? item.valor_deslocamento_km_base : '');
				$('#taxaVisitaNA').html(item.taxa_visita_base ? item.taxa_visita_base : '');
				$('#dataAtivacaoNA').html(item.data_ativacao ? item.data_ativacao : '');
				$('#dataAniversarioComodatoNA').html(item.data_aniversario_contrato ? item.data_aniversario_contrato : '');
				$('#dataVencimentoComodatoNA').html(item.data_vencimento_comodato ? item.data_vencimento_comodato : '');
				$('#dataTerminoFidelidadeNA').html(item.data_termino_fidelidade ? item.data_termino_fidelidade : '');

				fecharModalBuscaNA();
				buscarOrdensServico(idAtividadeServico);

			} else {
				showAlert("error",'Erro ao buscar item do contrato!');
			}
		},
		error: function (error) {
			btn.html(textoBotao).attr('disabled', false);
			console.error(error);
			showAlert("error",'Erro ao buscar item do contrato!');

		}
	});

	function limpar_modal_contrato_atividade_servico() {

		$("#tituloContratoModalAtividadesDeServico").html('')

		$('#codeContratoNA').html('');
		$('#numeroAfNA').html('');
		$('#modeloTipoInformadoAtivacaoNA').html('');
		$('#codigoItemContratoNA').html('');
		$('#statusItemContratoNA').html('');
		$('#placaVeiculoNA').html('');
		$('#serialVeiculoNA').html('');
		$('#chassiVeiculoNA').html('');
		$('#renavamVeiculoNA').html('');
		$('#modeloVeiculoNA').html('');
		$('#tipoVeiculoNA').html('');
		$('#integraContratoVendaNA').html('');
		$('#reenviarApoliceGraberVendaNA').html('');
		$('#canalVendaNA').html('');
		$('#cenarioVendaNA').html('');
		$('#tecnologiaVendaNA').html('');
		$('#afVendaNA').html('');
		$('#valorLicencaBaseVendaNA').html('');
		$('#planoProdutoNA').html('');
		$('#rastreadorProdutoNA').html('');
		$('#valorDeslocamentoNA').html('');
		$('#taxaVisitaNA').html('');
		$('#dataAtivacaoNA').html('');
		$('#dataAniversarioComodatoNA').html('');
		$('#dataVencimentoComodatoNA').html('');
		$('#dataTerminoFidelidadeNA').html('');
	}
}
/**
 * Função que recebe o contexto do botão (this) e abre um modal exibindo as informações
 * da ocorrência da atividade de serviço selecionada
 * @param {*} button 
 */
function abrirModalOcorrenciasAtividadesDeServico(button, idOcorrencia) {
	let btn = $(button);
	let textoBotao = '<i class="fa fa-ticket" aria-hidden="true"></i>';

	btn.html(ICONS.spinner).attr('disabled', true);
	limparModalOcorrenciaAtividadeServico();

	if (btn[0].id == "btn-ocorrencia-modal-na")
		textoBotao = 'Visualizar Ocorrência';

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_ocorrencia_atividade_servico/${idOcorrencia}`,
		type: 'GET',
		success: function (data) {
			btn.html(textoBotao).attr('disabled', false);
			data = JSON.parse(data);
			if (data.code == 200) {

				fecharModalBuscaNA();
				let item = data.item;
				$('#ticketnumberOcorrenciaNA').html(item.ticketnumber);
				$('#filaAtualOcorrenciaNA').html(item.filaAtual);
				$('#dataCriacaoOcorrenciaNA').html(item.createdon);
				$('#tituloOcorrenciaNA').html(item.titulo);
				$('#descricaoOcorrenciaNA').html(item.descricao);

				$("#modalOcorrenciaAtividadesDeServico").modal("show");
			} else {
				showAlert("error",'Erro ao buscar ocorrência da atividade de serviço!');
			}
		},
		error: function (error) {
			btn.html(textoBotao).attr('disabled', false);
			showAlert("error",'Erro ao buscar ocorrência da atividade de serviço!')
		}
	});

}

function limparModalOcorrenciaAtividadeServico() {
	$('#ticketnumberOcorrenciaNA').html('');
	$('#filaAtualOcorrenciaNA').html('');
	$('#dataCriacaoOcorrenciaNA').html('');
	$('#tituloOcorrenciaNA').html('');
	$('#descricaoOcorrenciaNA').html('');
}

// Esconde modal de Atividades de serviço ao abrir o de contrato das atividades
$(document).on('show.bs.modal', "#modalContratoAtividadesDeServico", function () {
	$("#modalAtividadesDeServico").modal('hide');
})
// Exibe modal de Atividades de serviço ao abrir o de contrato das atividades
$(document).on('hide.bs.modal', "#modalContratoAtividadesDeServico", function () {
	$("#modalAtividadesDeServico").modal('show');
})
// Esconde modal de Atividades de serviço ao abrir o de ocorrências das atividades
$(document).on('show.bs.modal', "#modalOcorrenciaAtividadesDeServico", function () {
	$("#modalAtividadesDeServico").modal('hide');
})
// Exibe modal de Atividades de serviço ao abrir o de ocorrências das atividades
$(document).on('hide.bs.modal', "#modalOcorrenciaAtividadesDeServico", function () {
	$("#modalAtividadesDeServico").modal('show');
})

// Esconde modal de Contratos ao abrir modal de Detalhes do Contrato
$(document).on('show.bs.modal', "#modalDetalhesDoContrato", function () {
	$("#modalContratos").modal('hide');
});
// Exibe modal de Contratos ao fechar modal de Detalhes do Contrato
$(document).on('hide.bs.modal', "#modalDetalhesDoContrato", function () {
	$("#modalContratos").modal('show');
	limparModalDetalhesDoContrato();
});

// limpa modal de cadastrar ocorrências ao fechar
$('#modalOcorrencia').on('hide.bs.modal', function () {
	$("#Assunto option:not([value='0'][disabled][selected])").remove();
	$("#Assunto").val(0).trigger('change');
	$("#TipoOcorrencia").val(0).trigger('change');
	$("#OrigemOcorrencia").val(0).trigger('change');
	$("#Tecnologia option:not([value='0'][disabled][selected])").remove();
	$("#Tecnologia").val(0).trigger('change');
	$("#Descricao").val('Analista:' + '\n' + 'Assunto / Problema:' + '\n' + 'Nome/Telefone do solicitante:' + '\n' + 'Realizado:' + '\n' + 'Pendencia:' + '\n' + 'Observações:' + '\n' + 'ID da conversa:').trigger('change');
});

//MASCARAS DE INPUT
$(document).on('focus', '.cep', function () { $('.cep').mask('00.000-000'); });
$(document).on('focus', '.telefone', function () { $('.telefone').mask('(00)0000-0000'); });
$(document).on('focus', '.celular', function () { $('.celular').mask('(00)00000-0000'); });

var options = {
	onKeyPress: function (cpf, ev, el, op) {
		var masks = ['000.000.000-00', '00.000.000/0000-00'],
			mask = (cpf.length > 11) ? masks[1] : masks[0];
		el.mask(mask, op);
	}
};

$(document).on('input', '#inscricao-estadual-' + abaSelecionada, function () {
	$(this).val($(this).val().replace(/[^0-9]/g, ''))
})

/**
 * Função que audita os dados modificados pelo usuário
 */
async function salvar_auditoria(url_api, clause, valores_antigos, valores_novos) {
	// se a auditoria antiga for do tipo 'object' é necessário fazer a conversão para enviar os dados da forma correta
	if (valores_antigos && typeof (valores_antigos) === 'object') {
		valoresAuditoriaAntigo = [];
		Object.keys(valores_antigos).forEach(i => {
			valoresAuditoriaAntigo.push(i)
		});
	} else {
		valoresAuditoriaAntigo = valores_antigos;
	}
	return new Promise((resolve, reject) => {
		const cpf_cpnj_cliente = buscarDadosClienteAbaAtual()?.document.replace(/[^0-9]/g, '');
		$.ajax({
			url: URL_PAINEL_OMNILINK + '/ajax_salvar_auditoria',
			type: 'POST',
			dataType: 'json',
			data: { url_api, clause, valoresAuditoriaAntigo, valores_novos, cpf_cpnj_cliente },
			success: function (callback) {
				if (callback.status) {
					resolve(callback);
					limpar_dados_auditoria();
				} else {
					showAlert("error",'Erro ao salvar auditoria! Não foi possível completar a operação.');
					reject(callback);
				}
			},
			error: function (error) {
				console.error(error);
				showAlert("error",'Erro ao salvar auditoria! Não foi possível completar a operação.');
				reject(error);
			}

		});
	});
}

/**
 * Limpa variaveis utilizadas para salvar auditoria
 */
function limpar_dados_auditoria() {
	auditoria.valor_antigo_ocorrencia = auditoria.valor_novo_ocorrencia;
	auditoria.valor_novo_ocorrencia = null;
	auditoria.valor_antigo_cliente = null;
	auditoria.valor_novo_cliente = null;
}

function removeEventoSelect() {
	$('.statusOcorrencia').off('change');
}

function ajaxAlterarStatus(idIncident, novosStatus) {
	novosStatus['idIncident'] = idIncident;
	return new Promise((resolve, reject) => {
		novosStatus['idIncident'] = idIncident;
		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/alterarStatus`,
			type: 'POST',
			dataType: 'json',
			data: novosStatus,
			success: function (callback) {
				if (callback.code == 200) {
					alert('Status atualizado com sucesso.');
					resolve(true);
				} else {
					reject(false);
					showAlert("error",'Não foi possível atualizar o status, por favor tente mais tarde.');
				}
			},
			error: function (error) {
				console.error(error);
				showAlert("error",'Erro ao salvar auditoria! Não foi possível completar a operação.');
				reject(error);
			}
		});
	});
}
async function mudarStatusOcorrencia(button) {
	// Guarda a linha da tabela relacionada ao botão clicado
	let row = $(button).closest('tr');

	// Se a tabela estiver sendo exibida de forma responsiva,
	// esta verificação consegue fazer a linha correta ser selecionada
	if (row.hasClass('child')) {
		row = row.prev();
	}

	// Informaçãoes guardadas na linha da tabela
	let data = $("#tableIncidents-" + abaSelecionada).DataTable().row(row).data();

	// Guarda o valor atual do status da ocorrência
	let statuscode = data.cause.value;

	if ($('#select-status-row-' + data.id).length > 0 || $('#select-status-row2-' + data.id).length > 0) {
		// Confirmar se o usuário realmente deseja alterar o status da ocorrência
		if (confirm('Você tem certeza que deseja alterar o status?')) {

			$(button).html("<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-spinner fa-spin'></i>");
			$(button).prop('disabled', true);

			// Guarda o novo status
			auditoria.valor_novo_status_ocorrencia = {
				"razao_status": $(`#select-status-row-${data.id} option:selected`).val()
			};
			salvar_auditoria(`${URL_PAINEL_OMNILINK}/alterarStatus`, 'update', auditoria.valor_antigo_status_ocorrencia, auditoria.valor_novo_status_ocorrencia).then(async () => {
				let novosStatus = {
					razao_status: $('#select-status-row-' + data.id).val()
				};

				let responseAjax = await ajaxAlterarStatus(data.id, novosStatus);

				if (responseAjax) {
					$(button).prop('disabled', false);
					$(button).html("<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-check-circle-o' aria-hidden='true'></i>");

					atualizarTabelaOcorrencias(incidentStateCode, true);
				}

				$(button).prop('disabled', false);
				$(button).html("<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-check-circle-o' aria-hidden='true'></i>");
			}).catch(error => {
				$(button).html("<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-check-circle-o' aria-hidden='true'></i>");
				$(button).prop('disabled', false);
				console.error(error);
			});
		} else {
			$(`#select-status-row-${data.id}`).select2('destroy');
			// Retornar os dados da tabela aos anteriores caso o usuário não
			// deseje alterar o status da ocorrência
			data.cause.text = data.cause.status;
			data.changeIncidentStatusButtonStyle = 'background-color: #06a9f6 !important; border-color: #06a9f6 !important';

			data.cause = { status, ...data.cause };

			$("#tableIncidents-" + abaSelecionada).DataTable().row(row).data(data);

			// Redesenhar a tabela para atualizar as novas informaçãoes
			$("#tableIncidents-" + abaSelecionada).DataTable().responsive.redisplay();
		}
	} else {
		// Guardar status atual da ocorrência
		if (!data.cause.status) {
			data.cause.status = data.cause.text;
		}

		// Guardar select nos dados da linha
		data.cause.text = getSelectStatus(data.id);
		// Guardar estilo do botão de Alterar Status nos dados da linha
		data.changeIncidentStatusButtonStyle = 'background-color: #5cb85c !important; border-color: #5cb85c !important';

		// Atualizar dados da linha na tabela
		$("#tableIncidents-" + abaSelecionada).DataTable().row(row).data(data);

		// Inicializar o select e selecionar a opção atual do status
		$(`#select-status-row-${data.id}`).val(statuscode);
		$(`#select-status-row-${data.id}`).select2();

		// Redesenhar a tabela para atualizar as novas informaçãoes
		$("#tableIncidents-" + abaSelecionada).DataTable().responsive.redisplay();

		// Guarda o status antigo
		auditoria.valor_antigo_status_ocorrencia = {
			"razao_status": $(`#select-status-row-${data.id} option:selected`).val(),
		};
	}
}

function getSelectStatus2(elementId) {
	var selecStatus = `<select name="statusOcorrencia2" class="statusOcorrencia2" id='select-status-row2-${elementId}' class="form-control" style="width:100%">
							<option value="0">Ativa</option>
							<option value="1">Resolvida</option>
							<option value="2">Cancelada Cliente</option>
						</select>`;
	return selecStatus;
}

function getSelectStatus(incidentId) {
	let select = `<select id="select-status-row-${incidentId}" class="statusOcorrencia form-control" name="statusOcorrencia">
				      <option value="419400008">Não iniciado</option>
					  <option value="419400004">Novo Contrato</option>
					  <option value="3">Aguardando Cliente</option>
					  <option value="419400011">Aguardando Comercial</option>
					  <option value="419400009">Aguardando Operações</option>
					  <option value="419400012">Aguardando Outras Equipes</option>
					  <option value="419400010">Aguardando Peças</option>
					  <option value="419400005">Pendente Instalação / Manutenção</option>
					  <option value="1">Em Andamento</option>
					  <option value="2">Suspenso</option>
					  <option value="4">Pesquisando</option>
					  <option value="419400001">Enviado para operadora</option>
					  <option value="419400002">Inconsistência nas informações</option>
					  <option value="419400013">Falta de Informação</option>
					  <option value="419400014">Não resolvido</option>
					  <option value="419400007">Implantação concluída</option>
					  <option value="5">Problema Resolvido</option>
					  <option value="1000">Informações Fornecidas</option>
					  <option value="419400003">Ativação da(s) linha(s) concluída</option>
					  <option value="419400015">Resolvido com pendências do cliente</option>
					  <option value="2000">Mesclado</option>
					  <option value="6">Cancelada</option>
					  <option value="419400000">Cancelada - Logística Reversa</option>
					  <option value="419400016">Cancelado pelo cliente</option>
				  </select>
				  `;

	return select;
}

async function modalAnotacoes(button) {
	if (button) {
		let textoBotao = $(button).html()
		$(button).html(`<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class="fa fa-spinner fa-spin" aria-hidden="true"></i>`);
		$(button).prop('disabled', true);
		$('#modalCadastrarAnotacaCadastrooHeader').text("Cadastrar Anotação - " + $(button).attr('ticket'));
		let incidentId = $(button).attr('data-id');
		$('#nameModalAnotacoes').text("Anotações - " + $(button).attr('ticket'));
		await ajaxListarAnotacoes(incidentId).catch(err => console.error(err))
		$('#btnCadastrarAnotacao').attr('incidentId', incidentId);
		$('#finalizarOcorrencia').attr('incidentId', incidentId);

		$(button).prop('disabled', false);
		$(button).html(textoBotao);
	} else {
		$('#nameModalAnotacoes').text("Anotações - NA")
		carregarAnotacoesNA()
	}

	$('#modalAnotacoes').modal('show');
}

// Converte o arquivo em base64 para upload
const toBase64 = file => new Promise((resolve, reject) => {
	const reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = () => resolve(reader.result);
	reader.onerror = error => reject(error);
});

async function cadastrarAnotacoes() {
	let result, filename, mimetype = '';
	const file = document.querySelector('#anexoAnot').files[0];

	if (file) {
		filename = file.name;
		mimetype = file.type;
		result = await toBase64(file).catch(e => Error(e));
		result = result.toString().replace(/^data:(.*,)?/, '');
		if (result instanceof Error) {
			showAlert("error",'Error: ' + result.message);
			return;
		}
	}

	if ($('#nomeAnotacao').val() === "" && $('#descricaoAnotacao').val() === ""
		&& !result) {
		showAlert("error","Dados inválidos, preencha ao menos um campo")
		return
	}

	let data = {
		idAnotacao: $('#id-anotacao').val(),
		incidentId: $('#btnCadastrarAnotacao').attr('incidentId'),
		idNa: $('#btnCadastrarAnotacao').attr('idNa'),
		subject: $('#nomeAnotacao').val(),
		notext: $('#descricaoAnotacao').val(),
		anexo: result,
		fileName: filename,
		mimeType: mimetype
	};

	$('#btnCadastrarAnotacao').html('Salvar...');
	$('#btnCadastrarAnotacao').prop('disabled', true);

	if (!data.idAnotacao || data.idAnotacao === "") {
		await ajaxCadastrarAnotacoes(data)
	} else {
		await ajaxEditarAnotacoes(data).then(async e => {

			await salvar_auditoria(`${URL_PAINEL_OMNILINK}/editarAnotacoes`, 'update', null, data)
				.catch(e => { })
		})
			.catch(error => {
				$('#btnCadastrarAnotacao').html('Salvar');
				$('#btnCadastrarAnotacao').prop('disabled', false);
			});


	}
}

/**
 * Função para excluir a anotação
 * @param {button} button 	- Botão pressionado
 * @param {String} idAnotacao - id da anotação
 * @param {boolean} anotacaoNA - verifica se a anotação é de uma NA
 */
function excluirAnotacao(button, idAnotacao, anotacaoNA = false) {
	if (!idAnotacao) {
		showAlert("error",'Falha ao excluir a anotação, verifique os campos e tente novamente.');
		return;
	}
	let confirmacao = confirm('Deseja excluir a anotação?\nEssa operação não pode ser desfeita.');
	if (confirmacao) {
		botao = $(button);
		botao.attr('disabled', true).html(ICONS.spinner);

		$.ajax({
			url: `${URL_PAINEL_OMNILINK}` + '/excluirAnotacao',
			type: 'POST',
			dataType: 'json',
			data: { idAnotacao },
			success: function (resposta) {

				if (resposta.status === 204) {
					alert(resposta.message)

					if (anotacaoNA) {
						const indexAnotacaoApagada = anotacoesNA.findIndex(anotacao => anotacao.idAnotacao == idAnotacao)
						anotacaoNA = anotacoesNA.splice(indexAnotacaoApagada, 1)
						carregarAnotacoesNA()
					}
				} else {
					showAlert("error",resposta.mensage ?? 'Ocorreu um erro ao excluir a anotação.');
				}
			},
			error: function (e) {
				showAlert("error",e.message || "Ocorreu um erro ao excluir esta anotação.");
			},
			complete: function () {
				botao.html('<i class="fa fa-trash" aria-hidden="true"></i>');
				botao.attr('disabled', false);
			}
		});
	}
}

function ajaxCadastrarAnotacoes(data) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/criarAnotacoes`,
			type: "POST",
			data: data,
			dataType: 'json',
			success: async function (callback) {
				if (callback.code == 201) {
					alert('Anotação cadastrada com sucesso.');
					$('#btnCadastrarAnotacao').html('Salvar');
					$('#btnCadastrarAnotacao').prop('disabled', false);
					//mude o status do btnClose aqui alexsander
					data?.incidentId ? await ajaxListarAnotacoes(data.incidentId)
						: await atualizarAnotacoesNA(callback.data, true)

					await salvar_auditoria(`${URL_PAINEL_OMNILINK}/criarAnotacoes`, 'insert', null, data)
						.catch(error => {
							$('#btnCadastrarAnotacao').html('Salvar')
							$('#btnCadastrarAnotacao').prop('disabled', false)
							console.error(error);
						})

					$("#modalCadastrarAnotacao").modal("hide");
					resolve(true);
				} else {
					$('#btnCadastrarAnotacao').html('Salvar');
					$('#btnCadastrarAnotacao').prop('disabled', false);
					showAlert("error","Erro ao cadastrar anotação");
					reject(false);
				}
			},
			error: function (XMLHttpRequest, erro) {
				$('#btnCadastrarAnotacao').html('Salvar');
				$('#btnCadastrarAnotacao').prop('disabled', false);
				showAlert("error","Erro ao cadastrar anotação");
				reject(false);
			}
		});
	});
}

function ajaxEditarAnotacoes(data) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/editarAnotacoes`,
			type: "POST",
			data,
			dataType: 'json',
			success: async function (callback) {
				if (callback.status === 200) {
					alert(callback.message)
					$('#btnCadastrarAnotacao').html('Salvar')
					$('#btnCadastrarAnotacao').prop('disabled', false)

					data?.inincidentId ? await ajaxListarAnotacoes(data.incidentId)
						: await atualizarAnotacoesNA(data)

					$("#modalCadastrarAnotacao").modal("hide");
					resolve(true);
				} else {
					$('#btnCadastrarAnotacao').html('Salvar');
					$('#btnCadastrarAnotacao').prop('disabled', false);
					showAlert("error","Erro ao alterar anotação");
					reject(false);
				}
			},
			error: function (XMLHttpRequest, erro) {
				$('#btnCadastrarAnotacao').html('Salvar');
				$('#btnCadastrarAnotacao').prop('disabled', false);
				showAlert("error","Erro ao alterar anotação");
				reject(false);
			}
		});
	})
}

function editarAnotacoes() {
	let data = {
		subject: $('#nomeAnotacao').val(),
		notext: $('#descricaoAnotacao').val(),
	}
}

function ajaxListarAnotacoes(incidentId) {
	return new Promise((resolve, reject) => {
		$.getJSON(`${URL_PAINEL_OMNILINK}/listarAnotacoes?idIncident=${incidentId}`, function (incidentAnotations) {
			if (incidentAnotations.code && incidentAnotations.code == 200) {
				incidentAnotations = incidentAnotations.data;
				tableAnotacoes.clear();

				tableAnotacoes.rows.add(incidentAnotations);
				tableAnotacoes.columns.adjust().draw();

			} else {
				console.error("errorr");
				showAlert("error","Erro ao listar as anotações");
			}
			resolve(true);
		}).fail(error => {
			console.error(error);
			showAlert("error","Erro ao buscar ocorrências!");
			reject(error);
		});
	});

}

function clickButtonNovaAnotavao() {
	$('#modalAnotacoes').modal('hide');
	$('.paramAnotation').val('');
	$('#id-anotacao').val("")
	$('#modalCadastrarAnotacao').modal('show');
}

$(document).on('hide.bs.modal', '#modalCadastrarAnotacao', function () {
	$('#modalAnotacoes').modal('show');
});

function getInfoOcorrencia(ticket) {
	return new Promise((resolve, reject) => {
		$.getJSON(`${URL_PAINEL_OMNILINK}/ajax_info_ocorrencia/${ticket}`, function (data) {
			resolve(data);
		})
			.fail(err => {
				reject(err);
			});
	})
}

function getInformacoesOcorrencia(incidentId) {
	return new Promise((resolve, reject) => {
		$.getJSON(`${URL_PAINEL_OMNILINK}/ajax_informacoes_ocorrencia?incidentId=${incidentId}`, function (data) {
			resolve(data);
		}).fail(err => {
			reject(err);
		});
	});
}

async function modalInformacoesOcorrencia(button) {
	let incidentId = $(button).attr('data-id');

	$(button).html(`<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-spinner fa-spin'></i>`);
	$(button).prop('disabled', true);

	await getInformacoesOcorrencia(incidentId)
		.then(informacoesOcorrencia => {
			informacoesOcorrencia = informacoesOcorrencia.ocorrencia;

			$('.inforOcorrenciaLabel').text(' - ');
			$('#infoOcorrenciaAssunto').text(informacoesOcorrencia.assunto || " - ");
			$('#infoOcorrenciaAssuntoPrimario').text(informacoesOcorrencia.assunto_primario || " - ");
			$('#infoOcorrenciaDescricaoAssunto').text(informacoesOcorrencia.assunto_descricao || " - ");
			$('#infoOcorrenciaNumeroOcorrencia').text(informacoesOcorrencia.numero_ocorrencia || " - ");
			$('#infoOcorrenciaOrigemOcorrencia').text(informacoesOcorrencia.origem_ocorrencia || " - ");
			$('#infoOcorrenciaTipoOcorrencia').text(informacoesOcorrencia.tipo_ocorrencia || " - ");
			$('#infoOcorrenciaTipoAtendimento').text(informacoesOcorrencia.tipo_atendimento || " - ");
			$('#infoOcorrenciaTecnologia').text(informacoesOcorrencia.tecnologia || " - ");
			$('#infoOcorrenciaFilaAtual').text(informacoesOcorrencia.fila_atual || " - ");
			$('#infoOcorrenciaFilaAtendimento').text(informacoesOcorrencia.fila_atendimento || " - ");
			$('#infoOcorrenciaUltimaFila').text(informacoesOcorrencia.ultima_fila || " - ");
			$('#infoOcorrenciaProprietario').text(informacoesOcorrencia.proprietario || " - ");
			$('#infoOcorrenciaObservacao').text(informacoesOcorrencia.description || " - ");
			$('#infoOcorrenciaCriadoPor').text(informacoesOcorrencia.description || " - ");
			$('#infoOcorrenciaStatus').text(informacoesOcorrencia.status || " - ");
			$('#infoOcorrenciaRazaoStatus').text(informacoesOcorrencia.razao_status || " - ");
			$('#infoOcorrenciaDataCriacao').text(informacoesOcorrencia.data_criacao || " - ");
			$('#infoOcorrenciaDataModificacao').text(informacoesOcorrencia.data_modificacao || " - ");
			$('#infoOcorrenciaCriador').text(informacoesOcorrencia.criado_por || " - ");
			$('#infoOcorrenciaModificador').text(informacoesOcorrencia.modificado_por || " - ");

			//muda o texto de "modificado por" para cancelado/resolvido no modal de visualização.
			if (informacoesOcorrencia.status === "Resolvida") {
				$('#modificado-por-ocorrencia').html("Resolvido Por:")
			} else if (informacoesOcorrencia.status === "Cancelada") {
				$('#modificado-por-ocorrencia').html("Cancelado Por:")
			} else {
				$('#modificado-por-ocorrencia').html("Modificado Por:")
			}

			$('#modalInformacoesOcorrencia').modal('show');

			$(button).html(`<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class="fa fa-eye" aria-hidden="true"></i>`);
			$(button).prop('disabled', false);
		}).catch(err => {
			showAlert("error","Não foi possível carregar as informações da ocorrência.");
			$(button).html(`<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class="fa fa-eye" aria-hidden="true"></i>`);
			$(button).prop('disabled', false);
		});
}

async function clickButtonEncerrarOcorrencia(button, incidentId) {
	let htmlButton = $(button).html();
	$(button).html(ICONS.spinner);
	$(button).attr('disabled', true);

	await solicitarAtividadesAbertas(incidentId).then(response => {
		if (response.code == 200) {
			return response.atividades;
		} else {
			throw response.error;
		}
	}).then(async atividadesAberto => {

		$('#btnEncerrarOcorrencia').attr('incidentId', incidentId);
		// $('#btnEncerrarOcorrencia').attr('quantidadeAtividadesAbertas', atividadesAberto.length);
		$("#quantidadeAtividadesAbertas").val(atividadesAberto.length);
		$(".encerarOcorrencia").val("");
		$("#modalAnotacoes").modal('hide');
		$('#modalEncerramentoOcorrencia').modal('show');

		//verifica se existe ao menos uma atividade em aberto e desativa/ativa a opção de implantação concluída
		//seguindo a regra do CRM se houver alguma atividade aberta não é possível selecionar esta opção.
		atividadesAberto.length > 0 ? statusOption = true : statusOption = false
		$('#selectIdTipoResolucao option[value="419400007"]').prop('disabled', statusOption);
	}).catch(err => {
		$(button).html(htmlButton);
		$(button).attr('disabled', false);
		showAlert("error",`Não foi possível realizar o encerramento da ocorrência, por favor tente mais tarde`);
	})
}

async function eventoEncerrarOcorrencia(button) {
	$(button).html(`<i class='fa fa-spinner fa-spin'></i> Encerrando`);
	$(button).prop('disabled', true);

	let incidentId = $(button).attr('incidentId');
	let quantidadeAtividadesAbertas = $("#quantidadeAtividadesAbertas").val();

	let textConfirmacao = quantidadeAtividadesAbertas && quantidadeAtividadesAbertas > 0 ? "A atividade possui " + quantidadeAtividadesAbertas + " atividade(s) aberta(s), ao encerrar a ocorrência esta(s) atividade(s) será(ão) cancelada(s) ou excluída(s), deseja continuar ?" : "Deseja continuar com o encerramento da ocorrência?"

	let data = {
		'Id': incidentId,
		'TipoResolucao': $("#selectIdTipoResolucao").val(),
		'Subject': $("#resolucaoOcorrencia").val(),
		'Descricao': $("#descricaoEncerrarOcorrencia").val(),
		'TimeSpent': $("#periodoFaturavel").val()
	};


	if (confirm(textConfirmacao)) {
		await salvar_auditoria(`${URL_API}incident/closeIncident`, 'insert', null, data).then(async response => {
			await ajaxEncerarOcorrencia(data).then(async response => {
				if (response.Status == 200) {
					alert(response.Message);
					$("#quantidadeAtividadesAbertas").val(0)
					$("#modalEncerramentoOcorrencia").modal('hide');

					recarregarOcorrencias();
				} else {
					showAlert("error","Não foi possível encerrar a ocorrência.");
				}
				$(button).html('Encerrar');
				$(button).prop('disabled', false);
			}).catch(err => {
				showAlert("error","Não foi possível encerrar a ocorrência.");
				$("#modalEncerramentoOcorrencia").modal('hide');
				$(button).html('Encerrar');
				$(button).prop('disabled', false);
			});
		}).catch(err => {
			showAlert("error","Não foi possível encerrar a ocorrência.");
			$("#modalEncerramentoOcorrencia").modal('hide');
			$(button).html('Encerrar');
			$(button).prop('disabled', false);
		});
	} else {
		$(button).html('Encerrar');
		$(button).prop('disabled', false);
	}
}

async function eventoCancelarOcorrencia(button, incidentId) {
	$(button).html(`<i class='fa fa-spinner fa-spin'></i>`);
	$(button).prop('disabled', true);

	let quantidadeAtividadesAbertas = $("#quantidadeAtividadesAbertas").val();
	let textConfirmacao = quantidadeAtividadesAbertas && quantidadeAtividadesAbertas > 0 ? "A atividade possui " + quantidadeAtividadesAbertas + "atividades abertas, ao cancelar a ocorrência estas atividades serão canceladas ou excluídas, deseja continuar?" : "Deseja continuar com o cancelamento da ocorrência?";

	if (confirm(textConfirmacao)) {
		await salvar_auditoria(
			`${URL_PAINEL_OMNILINK}/cancelar_ocorrencia`,
			'update',
			{ statecode: 0 },
			{ statecode: 2 }
		)
			.then(async response => {
				$.ajax({
					url: `${URL_PAINEL_OMNILINK}/cancelar_ocorrencia`,
					type: "POST",
					dataType: 'json',
					data: {
						'incident_id': incidentId,
					},
					success: function (response) {
						if (response.status == 1) {
							alert("Ocorrência cancelada com sucesso.");
							atualizarTabelaOcorrencias(incidentStateCode, true);
						} else {
							showAlert("error","Não foi possível cancelar a ocorrência.");
							$(button).html(`<i class="fa fa-times"></i>`);
							$(button).prop('disabled', false);
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						showAlert("error","Ocorreu um erro ao cancelar a ocorrência. Tente novamente em alguns minutos.")
						$(button).html(`<i class="fa fa-times"></i>`);
						$(button).prop('disabled', false);
					}
				});

			}).catch(err => {
				showAlert("error","Não foi possível cancelar a ocorrência.");
				$(button).html(`<i class="fa fa-times"></i>`);
				$(button).prop('disabled', false);
			});
	} else {
		$(button).html(`<i class="fa fa-times"></i>`);
		$(button).prop('disabled', false);
	}
}

async function ajaxEncerarOcorrencia(data) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: `${URL_API}incident/closeIncident`,
			type: "PUT",
			dataType: 'json',
			data: data,
			success: function (response) {
				resolve(response);
			},
			error: function (XMLHttpRequest, erro) {
				reject(erro);
			}
		})
	})
}

async function solicitarAtividadesAbertas(incidentId) {
	return new Promise((resolve, reject) => {
		$.get(`${URL_PAINEL_OMNILINK}/solicitarAtividadesAbertas?incidentId=${incidentId}`, function (response) {
			resolve(response);
		}, "json").fail(err => {
			reject(err);
		});
	});
}



function valorInput(input) {
	let valor = '';

	switch (input.attr('type')) {
		case 'checkbox':
		case 'radio':
			valor = input.is(':checked');
			break;
		default:
			if (input.hasClass("mascara_cpf") || input.hasClass("mascara_cpf") || input.hasClass("mascara_cnpj") ||
				input.hasClass("mascara_porcetagem") || input.hasClass("mascara_telefone") || input.hasClass("mascara_cep")) {
				valor = removerMascara(input.val());
			} else {
				valor = input.val();
			}
			break;
	}

	return valor;
}

function inputsFormulario(classeFormulario) {
	const formulario = $(`.${classeFormulario}`);
	let tamanho = formulario.length;
	let inputs = {}

	for (let index = 0; index < tamanho; index++) {
		inputs[$(formulario[index]).attr('name')] = valorInput($(formulario[index]));
	}

	return inputs;
}


/**
	 * Limpa dados exibidos na view
	 */
function limpar_dados_view() {
	// Limpa dados Cliente
	limparDadosCliente();

	// Limpa dados do contrato
	limparDadosContratos();

	// Limpa dados atividade de serviço
	limparDadosAtividadeDeServico()

	// Limpa dados visão dos produtos
	limparDadosVisaoDosProdutos();

}

/**
	 * Limpa dados do cliente exibidos na view
	 */
function limparDadosCliente() {
	$("#NomeCliente").html("-");
	$("#CNPJCliente").html("-");
	$("#EnderecoCliente").html("-");
	$("#BairroCliente").html("-");
	$("#CidadeCliente").html("-");
	$("#UFCliente").html("-");
	$("#CEPCliente").html("-");

	$("#NomeFantasiaCliente").html("-");
	$("#TelefoneCliente").html("-");
	$("#EmailCliente").html("-");

	$("#VendedorCliente").html("-");
	$("#SuporteCliente").html("-");

	$('#ver-mais-cliente').attr('disabled', true);
}

/**
 * Limpa dados do contrato exibido na view
 */
function limparDadosContratos() {
	// Total de Itens Contrato
	$("#ContratosAtivos").html('0');
	$("#ContratosAguardando").html('0');
	$("#ContratosSuspenso").html('0');
	$("#ContratosCancelados").html('0');
}

/**
 * Limpa dados de atividade de serviço exibido na view
 */
function limparDadosAtividadeDeServico() {
	$("#ASAberta").html('0');
	$("#ASFechada").html('0');
	$("#ASAgendada").html('0');
	$("#ASCancelada").html('0');
}

/**
 * Limpa dados da visão dos produtos exibidos na view
 */
function limparDadosVisaoDosProdutos() {
	$("#ABERTO").html("R$ -");
	$("#VENCIDO").html("R$ -");
	$("#LIQUIDADO").html("R$ -");
}

/**
 * Busca dados do grupo econômico e exibe na view
 * @param {String} cnpj
 */
function buscarDadosGrupoEconomico(cnpj) {
	if (cnpj && cnpj != "") {
		buscarClientePorCpfCnpj(cnpj);
	}
}

/**
 * Busca dados do cliente que possui o contrato e exibe na view
 * @param {String} cpfCnpj 
 */
function exibirClienteDoContrato(cpfCnpj) {
	if (cpfCnpj && cpfCnpj != "") {
		buscarClientePorCpfCnpj(cpfCnpj);
		$("#modalBuscarContrato").modal('hide');
	}
}

/**
 * Buscar dados do Cliente relacionado à Ocorrência e exibir na view
 * @param {String} documento Documento do Cliente - CPF/CNPJ
 */
function exibirClienteDaOcorrencia(documento) {
	if (documento && documento != "") {
		buscarClientePorCpfCnpj(documento);
		$("#modalBuscarOcorrencia").modal('hide');
	}
}

/**
 * Função que limpa os dados do cliente da tela e realiza nova busca do cliente exibindo as informações retornadas na view
 * @param {String} cpfCnpj 
 */
function buscarClientePorCpfCnpj(cpfCnpj) {
	escondeDivDadosCliente();
	$("#sel-pesquisa").val(0);
	$("#pesqDoc").val(cpfCnpj);
	$('#pesquisaDoc').show();
	$('#pesqDoc').removeAttr('disabled');
	$('#pesquisaNome').hide();
	$('#pesqNome').removeAttr('disabled', true);

	$("#formPesquisa").submit();
	$("#pesquisa").attr("disabled", true).html(ICONS.spinner + " Procurando");

	fecharModalBuscaNA();

	setTimeout(function () {
		// remove a seleção da aba
		$(".tab-pane-cliente").removeClass('active');
		$(".nav-tab-cliente").removeClass('active');
		// seleciona a aba do elemento inválido
		$("#nav_cliente-" + abaSelecionada).addClass('active');
		$(`#tab_cliente-` + abaSelecionada).addClass('active');
	}, 500);

	//verifica se os valores de "abertos,vencidos e liquidados" do último cliente estão abertas para acionar um clique para resetar os campos
	if ($('#abertos-' + abaSelecionada).hasClass('ocultar_valores')) $('#abertos-' + abaSelecionada).trigger("click");

}

function editarContatoAssociado(id, nome, funcao, email, telefone) {
	$('#info-cliente').html($('#NomeCliente').text() + ' - ' + $('input[name=Cpf_Cnpj]').val());
	$('#modal-contato-associado').modal('show');
	$('#header-contato-associado').html('Editar contato associado');

	$('#modal-contato-associado').data('id', id);
	$('#modal-contato-associado').data('tipo', 'editar');
	$('#input-nome-contato-associado').val(nome);
	$('#input-funcao-contato-associado').val(funcao);
	$('#input-email-contato-associado').val(email);
	$('#input-telefone-contato-associado').val(telefone);
}

function limparModalContatoAssociado() {
	$('#header-contato-associado').html('Novo contato associado');
	$('#modal-contato-associado').data('tipo', 'novo');
	$('#form-cadastro-contato-cliente').trigger('reset');
}

function excluirContatoAssociado(botao, id) {
	if (!id) {
		showAlert("error",'Falha ao excluir contato');
		return;
	}
	let confirmacao = confirm('Excuir o contato?\nEssa operação não pode ser desfeita.');
	if (confirmacao) {
		botao = $(botao);
		htmlBotao = botao.html();
		botao.html(ICONS.spinner);

		$.ajax({
			url: 'PaineisOmnilink/ajax_excluir_contato_associado',
			type: 'POST',
			dataType: 'json',
			data: { id },
			success: function (resposta) {
				resposta = resposta || {};
				if (resposta.status == 1) {
					$('#tabela-contatos-associados').DataTable().row('#' + id).remove().draw(false);
				} else {
					showAlert("error",resposta.mensagem || 'Falha');

				}
			},
			error: function (e) {
				showAlert("error",'Falha na operação');
				if (e) console.error(e);
			},
			complete: function () {
				botao.html(htmlBotao);
				botao.attr('disabled', false);
			}
		});
	}
}
/**
 * Função que busca os dados da providÊncia e exibe o modal com os dados
 * @param {*} button - botão de update
 * @param {String} idProvidencia
 * 
 */
function visualizarProvidencia(button, idProvidencia) {
	montar_inputs_providencias('update');

	let btn = $(button);
	const htmlBtn = btn.html();
	btn.attr('disabled', true).html(ICONS.spinner);
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_providencia/${idProvidencia}`,
		type: 'GET',
		success: function (callback) {
			btn.attr('disabled', false).html(htmlBtn);
			callback = JSON.parse(callback);
			if (callback.status) {
				let providencia = callback.providencia;
				// Salva valor da providência para auditoria
				auditoria.valor_antigo_providencia = providencia;

				atualizar_form_providencia(providencia);
				$('#modalProvidencia').modal('show');
			}
		},
		error: function (error) {
			// Seta valor antigo como null
			auditoria.valor_antigo_providencia = null;
			btn.attr('disabled', false).html(htmlBtn);
			console.error(error);
			showAlert("error",'Erro ao buscar Providência!');
		}
	});
}

/**
 * Função que atualiza os inputs do formulário de providência
 * @param {Object} providencia
 */
function atualizar_form_providencia(providencia) {
	if (providencia && typeof (providencia) === 'object') {
		Object.keys(providencia).forEach(i => {
			// Adiciona título
			if (i == 'tz_name') $(`#providencia_${i}`).html(' - ' + providencia[i]);
			// Adiciona valores no input
			if (!i.includes(".")) $(`#providencia_${i}`).val(providencia[`${i}`]);
		});
	}
}

/**
 * Monta os inputs para cadastra ou editar uma providência
 * @param {String} tipo - Tipo pode ser insert ou update. Caso seja 'update' exibe o botão para edição
 */
function montar_inputs_providencias(tipo) {

	const pessoas = [
		'Primeira',
		'Segunda',
		'Terceira',
		'Quarta',
		'Quinta',
		'Sexta',
		'Sétima',
		'Oitava',
	];

	// Se o tipo do formulário for update, exibe o botão de edição e desabilita o botão de salvar
	if (tipo == 'update') {
		// $("#btnEditarProvidencia").show();
		// $("#btnSalvarProvidencia").attr('disabled', true);
	} else { //caso contrário, esconde botão de edição e habilita o botão de salvar
		// $("#btnEditarProvidencia").hide();
		// $("#btnSalvarProvidencia").attr('disabled', false);
	}
	let html = `
		<input type="hidden" id="providencia_tz_providenciasid" name="tz_providenciasid">
		<div class="row">
			<div class="col-md-6 form-group">
				<label class="control-label" for="perguntaProvidencia">Pergunta</label>
				<input class="form-control" type="text" id="providencia_tz_pergunta" name="tz_pergunta"disabled required>
			</div>
			<div class="col-md-6">
				<label class="control-label" for="respostaProvidencia">Resposta</label>
				<input class="form-control" type="text" id="providencia_tz_resposta" name="tz_resposta" disabled required>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<h5><b>Pessoas Autorizadas</b></h5>
			</div>
		</div>
	`;
	for (let i = 0; i < pessoas.length; i++) {
		let pessoa = pessoas[i];
		let numPessoa = i + 1;
		html += `
			<div class="row">
				<div class="col-md-12">
					<h5>${pessoa} Pessoa</h5>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 form-group">
					<label ${i == 0 ? 'class="control-label"' : ''} for="">Pessoa</label>
					<input class="form-control" type="text" id="providencia_tz_pessoa_${numPessoa}" name="tz_pessoa_${numPessoa}" disabled ${i == 0 ? 'required' : ''}>
				</div>
				<div class="col-md-2 form-group">
					<label ${i == 0 ? 'class="control-label"' : ''} for="">DDD</label>
					<input class="form-control ddd_providencia" type="text" id="providencia_tz_ddd1_pessoa${numPessoa}" name="tz_ddd1_pessoa${numPessoa}" disabled ${i == 0 ? 'required' : ''}>
				</div>
				<div class="col-md-4 form-group">
					<label ${i == 0 ? 'class="control-label"' : ''} for="">Telefone</label>
					<input class="form-control telefone_providencia" type="text" id="providencia_tz_telefone1_pessoa${numPessoa}" name="tz_telefone1_pessoa${numPessoa}" disabled ${i == 0 ? 'required' : ''}>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-2 form-group">
					<label for="">DDD</label>
					<input class="form-control ddd_providencia" type="text" id="providencia_tz_ddd2_pessoa${numPessoa}" name="tz_ddd2_pessoa${numPessoa}" disabled>
				</div>
				<div class="col-md-4 form-group">
					<label for="">Telefone</label>
					<input class="form-control telefone_providencia" type="text" id="providencia_tz_telefone2_pessoa${numPessoa}" name="tz_telefone2_pessoa${numPessoa}" disabled>
				</div>
			</div>
		`;

	}

	$("#infoPessoasProvidencia").html(html);
	// MASCARA DDD
	$(".ddd_providencia").mask('00');

	// MASCARA TELEFONE

	$(".telefone_providencia").on('keydown keypress input blur', function () {

		if ($(this).val().length < 9) {
			$(this).mask('0000-00009');
		} else {
			$(this).mask('00000-0000');
		}
	});

}

/**
 * Exclui uma providência
 */
function removerProvidencia(button, idProvidencia) {
	if (confirm('Você tem certeza que deseja escluir esta provicência?')) {
		let btn = $(button);
		const htmlButton = btn.html();
		const url = `${URL_PAINEL_OMNILINK}/ajax_remover_providencia/${idProvidencia}`;
		btn.attr('disabled', true).html(ICONS.spinner);
		salvar_auditoria(url, 'delete', null, { idProvidenciaRemovida: idProvidencia })
			.then(async () => {
				$.ajax({
					url: url,
					type: 'DELETE',
					success: function (callback) {
						btn.attr('disabled', false).html(htmlButton);
						callback = JSON.parse(callback);
						if (callback.status) {
							alert(callback.msg);
							// Remove Linha da tabela
							$('#table_providencias-' + abaSelecionada).DataTable().row("#row_providencia_" + idProvidencia).remove().draw();

						} else {
							showAlert("error",callback.erro);
						}
					},
					error: function (error) {
						btn.attr('disabled', false).html(htmlButton);
						console.error(error);
						showAlert("error",'Erro ao excluir Providência!');
					}
				});
			})
			.catch(error => {
				console.error(error);
			});
	}
}
/**
 * Função que habilita ou desabilita os inputs de providência
 * @param {Boolean} isDisabled
 */
function mudarPropriedadeDisabledFormProvidencia(isDisabled) {
	$("#formProvidencia").find("input").attr('disabled', isDisabled);
	$("#btnSalvarProvidencia").attr('disabled', isDisabled);
}

/**
 * Cria o formulário de providência, habilita os inputs e exibe o formulário
 */
function abrirModalCadastroProvidencia() {
	montar_inputs_providencias();
	mudarPropriedadeDisabledFormProvidencia(false);
	$('#modalProvidencia').modal('show');
}

// Ao clicar no botão de editar providência, habilita os inputs e o botão de salvar
$(document).ready(() => {
	$("#btnEditarProvidencia").click(function () {
		mudarPropriedadeDisabledFormProvidencia(false);
	});
})

/**
 * Quando o formulário de providências for submetido, verifica se possui o
 * id da providência. Caso possua, chama função para edição. Caso contrario
 * chama função de cadastro
 */
$(document).ready(() => {
	$("#formProvidencia").submit(async function (event) {
		event.preventDefault();
		let dadosForm = $(this).serializeArray();
		let data = {};
		dadosForm.forEach(element => {
			data[element.name] = element.value;
		});
		// Salva novo valor para auditoria
		auditoria.valor_novo_providencia = data;

		$("#btnSalvarProvidencia").attr('disabled', true).html(ICONS.spinner + ' Salvando');
		if (data.tz_providenciasid && data.tz_providenciasid != '') {
			salvar_auditoria(`${URL_PAINEL_OMNILINK}/ajax_editar_providencia`, 'update', auditoria.valor_antigo_providencia, auditoria.valor_novo_providencia)
				.then(async () => {
					editarProvidencia(data);
				})
				.catch(error => {
					console.error(error);
				});

		} else {
			salvar_auditoria(`${URL_PAINEL_OMNILINK}/ajax_cadastrar_providencia`, 'insert', null, auditoria.valor_novo_providencia)
				.then(async () => {
					cadastrarProvidencia(data);
				})
				.catch(error => {
					console.error(error);
				});
		}

	});
	/**
	 * Função que Cadastra uma providência
	 * @param {Object} data
	 */
	function cadastrarProvidencia(data) {
		if (data) {
			let btn = $("#btnSalvarProvidencia");
			// Salva entidade do cliente
			data.clientEntity = buscarDadosClienteAbaAtual()?.entity;
			// salva id do cliente
			data.idCliente = buscarDadosClienteAbaAtual()?.Id;
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_cadastrar_providencia`,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (callback) {
					btn.attr('disabled', false).html('Salvar');
					if (callback.status) {

						if (callback.status) {
							alert('Providência cadastrada com sucesso!');

							$("#table_providencias-" + abaSelecionada).DataTable().draw();

							// esconde modal
							$("#modalProvidencia").modal('hide');

							auditoria.valor_antigo_providencia = null;
							auditoria.valor_novo_providencia = null;
						} else {
							showAlert("error",'Erro ao cadastrar providência!');
						}
					}
				},
				error: function (error) {
					btn.attr('disabled', false).html('Salvar');
					console.error(error);
					showAlert("error",'Erro ao cadastrar a providência!');
				}
			});
		} else {
			showAlert("error","Não foi possível cadastrar a providência!");
		}
	}

	/**
	 * Função que atualiza uma providência
	 * @param {Object} data
	 */
	function editarProvidencia(data) {
		if (data && data.tz_providenciasid && data.tz_providenciasid != '') {
			let btn = $("#btnSalvarProvidencia");
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_editar_providencia`,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (callback) {
					btn.attr('disabled', false).html('Salvar');
					if (callback.status) {
						let providencia = callback.providencia;
						alert('Providência atualizada com sucesso!');
						// esconde modal
						$("#modalProvidencia").modal('hide');
						let row = {
							'tz_providenciasid': providencia.tz_providenciasid,
							'tz_name': providencia.tz_name,
							'createdon': providencia.createdon,
							'statecode': getSwitchStatusProvidencia(providencia.tz_providenciasid, providencia.statecode),
							'acao': providencia.acao
						}
						recarregarProvidencias();

						auditoria.valor_antigo_providencia = null;
						auditoria.valor_novo_providencia = null;
					} else {
						showAlert("error","Erro ao editar Providência!");
					}
				},
				error: function (error) {
					btn.attr('disabled', false).html('Salvar');
					console.error(error);
					alert('Erro ao atualizar a providência!');
				}
			});

		} else {
			alert("Não foi possível atualizar os dados da Providência!");
		}
	}
});

/**
 * Função que altera o status de uma providência
 * @param {String} idProvidencia
 * @param {Integer} newStateCode
 */
async function mudarStatusProvidencia(idProvidencia, newStateCode) {

	const url = `${URL_PAINEL_OMNILINK}/ajax_mudar_status_providencia`;
	let stateCode = newStateCode == 1 ? 0 : 1;

	salvar_auditoria(url, 'update', { statecode: stateCode }, { statecode: newStateCode, idProvidenciaAtualizada: idProvidencia })
		.then(async () => {
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: { idProvidencia, newStateCode },
				success: function (callback) {
					if (callback.status) {
						let { providencia } = callback;
						let row = {
							'tz_providenciasid': providencia.tz_providenciasid,
							'tz_name': providencia.tz_name,
							'createdon': providencia.createdon,
							'statecode': getSwitchStatusProvidencia(providencia.tz_providenciasid, providencia.statecode),
							'acao': providencia.acao
						}
					} else {
						alert("Erro ao mudar Status da Providência!");
						let row = tableProvidencias.row("#row_providencia_" + providencia.tz_providenciasid).data(row);
						let state = newStateCode == 0 ? 1 : 0;
						row.statecode = getSwitchStatusProvidencia(idProvidencia, state),
							recarregarProvidencias();

						$(`#statusProvidencia_${idProvidencia}`).attr('checked', state == 0 ? true : false);
					}
				},
				error: function (error) {
					console.error(error);
					alert("Erro ao mudar Status da Providência!");
					let row = tableProvidencias.row("#row_providencia_" + providencia.tz_providenciasid).data(row);
					let state = newStateCode == 0 ? 1 : 0;
					row.statecode = getSwitchStatusProvidencia(idProvidencia, state),
						recarregarProvidencias();

					$(`#statusProvidencia_${idProvidencia}`).attr('checked', state == 0 ? true : false);
				},
			});
		})
		.catch(error => {
			console.error(error);
		});
}

/**
 * Ao clicar no botão de cadastrar item de contrato
 * esconde as tabs do modal, exibindo somente o formulário de cadastro
 */
$(document).ready(() => {
	$(".btnAbrirModalCadastroItemDeContrato").click(function () {
		// Reseta as informações do item de contrato
		controlarAbasModalItemDeContrato(true);
		// Reseta informações do item de contrato
		resetarInfoModalItemDeContrato();

		$("#modalItemDeContrato").modal('show');

	});
});

/**
 * Retorna options do select de providências
 */

function getOptionsSelectProvidencias() {

	let data = $('#table_providencias-' + abaSelecionada).DataTable().rows().data().toArray();

	let options = `<option></option>`;
	data.forEach(element => {
		options += `<option value="${element.tz_providenciasid}">${element.tz_name}</option>`
	});

	return options;

}
/**
 * Retorna options do select de ocorrencias
 */

function getOptionsSelectOcorrencias() {


	let data = $('#tableIncidents-' + abaSelecionada).DataTable().rows().data().toArray();

	let options = `<option></option>`;
	data.forEach(element => {
		options += `<option value="${element.incidentid}">${element.ticketnumber}</option>`
	});

	return options;


}

/**
 * Controle do modal itens de contrato
 */
function controlarAbasModalItemDeContrato(escoderTabs) {
	// Seleciona a primeira Aba
	$(".nav_tab_modal_item_contrato").removeClass('active');
	$("#nav_item_contrato").addClass('active');

	$(".tab_pane_modal_item_contrato").removeClass('active');
	$("#tab_item_contrato").addClass('active');

	if (escoderTabs == true) {
		// Esconde header com as abas
		$("#header_tabs_item_contrato").css('display', 'none');
	} else {
		// Exibir header com as abas
		$("#header_tabs_item_contrato").css('display', 'block');
	}
}

// Reseta as informações do modal de item de contrato
function resetarInfoModalItemDeContrato() {

	// LIMPA INPUTS
	limpaInputsModal('formItemDeContrato');
	// SETA SELECT PROVIDÊNCIA
	$("#tz_providenciasid").html(returnOptionsSelectProvidencias()).trigger('change');

	// Limpa tabela alteração de contrato
	tableAlteracaoDeContrato.clear().draw();
	// Limpa tabela serviços contratados
	tableServicosContratados.clear().draw();


}

// Ao abrir modal de alteração de contrato, fecha modal de item de contrato
$(document).on('show.bs.modal', '#modalAlteracaoDeContrato', function () {
	$("#modalItemDeContrato").modal('hide');
});
// Ao fechar de alteração de contrato, abre modal de item de contrato
$(document).on('hide.bs.modal', '#modalAlteracaoDeContrato', function () {
	$("#modalItemDeContrato").modal('show');
});
// Ao abrir modal  de serviços contratados, fecha modal de item de contrato
$(document).on('show.bs.modal', '#modalServicosContratados', function () {
	$("#modalItemDeContrato").modal('hide');
});
// Ao fechar modal de serviços contratados, abre modal de item de contrato
$(document).on('hide.bs.modal', '#modalServicosContratados', function () {
	limpaInputsModal('formServicoContratado')
	//verifica, com base na classe, qual foi o modal que chamou a função de edição e seu modal, para reabrir o modal anterior a este
	$(this).hasClass('buscaEspecificaContrato') ? $("#modalBuscarContrato").modal('show').css('overflow-y', 'auto') : $("#modalItemDeContrato").modal('show').css('overflow-y', 'auto');
	//necessário o .css('overflow-y', 'auto') pois pela versão do bootstrap ele buga o scroll ao fechar e abrir outro modal
});

/**
 * Função que limpa os inputs do modal de item de contrato
 * @param {String} idForm - ID do formulário
 */
function limpaInputsModal(idForm) {
	$(`#${idForm}`).find('select,input,textarea').each(function () {
		$(this).val('').trigger('change');
	});
}

/**
 * Envia ficha de ativação
 * @param {*} button 
 * @param {*} idItemContrato 
 */
async function EnviaFichaDeAtivacao(button, idItemContrato) {
	var qtdEmailEnviados = 0;

	await $.getJSON(`${URL_PAINEL_OMNILINK}/listarGruposDeEmail?idCliente=${buscarDadosClienteAbaAtual().Id}`, async function (response) {

		$(`#dropdown-grupos-email-${abaSelecionada} select`).empty();

		if (response) {

			if (response.status === 200) {

				let gruposEmail = response.data


				let data = {

					idGrupo: gruposEmail[2].id
				}


				await $.post(`${URL_PAINEL_OMNILINK}/buscarGrupoDeEmail`, data, async function (response) {


					//verifica se a resposta está ok para converter

					if (response) {

						let resposta = JSON.parse(response); //Array.from

						const arrh = Object.entries(resposta).map(([key, value]) => ({ [key]: value }));

						let arr = Array(arrh[1]);

						arr.forEach(async (emails) => {

							let dataemails = {

								emails1: emails.data[0].email1,
								emails2: emails.data[0].email2,
								emails3: emails.data[0].email3,
								emails4: emails.data[0].email4,
								emails5: emails.data[0].email5,
								emails6: emails.data[0].email6,
								emails7: emails.data[0].email7,
								emails8: emails.data[0].email8,
								emails9: emails.data[0].email9,
								emails10: emails.data[0].email10
							}

							let btn = $(button);

							const htmlButton = btn.html();
							btn.attr('disabled', true).html(ICONS.spinner);


							// Busca dados do contrato
							await $.getJSON(`${URL_PAINEL_OMNILINK}/ajax_get_informacoes_item_de_contrato/${idItemContrato}`, dataemails, async function (callback_item) {
								var documento = buscarDadosClienteAbaAtual().document;

								var objEmails = Object.values(dataemails)
									.filter(function (email) { return email && email !== ""; })

								for (let i = 0; i < objEmails.length; i++) {

									let data = {

										Email: objEmails[i],
										Numserie: callback_item.data.inputs.tz_numero_serie_modulo_principal,
										Cpfcnpj: documento

									}

									let dadosPost = {
										numeroSerie: callback_item.data.inputs.tz_numero_serie_modulo_principal,
										documento,
										email: objEmails[i],
									}
									await $.getJSON(`${URL_PAINEL_OMNILINK}/ajax_get_base_instalada`, dadosPost, async function (callback) {
										if (callback?.baseInstalada) {

											dado = `<html>
						<head>
							<title>Ficha de Ativação</title>
							<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
							<meta name="msapplication-TileColor" content="#ffffff">	
						</head>
						<body>
								<div id="FichaAtivacao">
									<center>
										<table cellpadding="0" cellspacing="0" border="0" width="640">
											<tr>
												<td valign="top">
													<table cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td><img src="http://app.omnilink.com.br/mkt/2018/base_externa/header-omnilink.jpg" width="257" height="105" alt="" /></td>
															<td><img src="http://app.omnilink.com.br/mkt/2018/base_externa/header-wave.jpg" width="392" height="105" alt="" /></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td valign="top" height="400" style="background-color: #f1f2f2;">
													<br>
													<table cellspacing="0" cellpadding="0" style="width: 547px; margin-left: 50px; margin-right: 46px;">
														<tr>
															<td style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</td>
														</tr>
														<tr>
															<td style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
																<h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Importante, </strong></h2>
																<p align="center">
																	Para  a correta comunica&ccedil;&atilde;o do rastreador, necessário que o mesmo esteja cadastrado na Base Central e/ou em sua Gerenciadora de Risco.
																	Para o espelhamento de sinal, entra em contato com o Suporte Omnilink  nos telefones abaixo: <br>
																	<br>
																	<span style="font-weight: bold; font-size: 14px;">4003-6754</span> - Capitais e regiões metropolitanas<br>
																	<span style="font-weight: bold; font-size: 14px;">0800 882 1949</span> - Demais localidades a partir de telefones fixos.
																</p>
															</td>
														</tr>
														<tr>
						
															<td height="158" style="background-color: #fff; padding: 0px">
						
																<table width="433" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
																	<tbody>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Produto:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;" colspan="3">${callback.baseInstalada.Tecnologia}</td>
																			<td></td>
																			<td style="border-bottom: solid  1px #C7C7C7;"></td>
																			<td style="border-bottom: solid  1px #C7C7C7;"></td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Data</td>
																			<td style="border-bottom: solid  1px #C7C7C7;"> ${callback.baseInstalada.DataInstalacao}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Prestador:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Prestador}</td>
																		</tr>
																		<tr>
																			<td width="115" style="border-bottom: solid  1px #C7C7C7;">Cliente:</td>
																			<td width="84" style="border-bottom: solid  1px #C7C7C7;text-align:left;">${callback.baseInstalada.NomeCliente}</td>
																			<td width="22">&nbsp;</td>
																			<td width="102" style="border-bottom: solid  1px #C7C7C7;">Modelo:</td>
																			<td width="86" style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Modelo}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Marca:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Marca}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Ano:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Ano}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Cor:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Cor}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Número de Sérire:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.NumSerie}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Placa:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Placa}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Linha2:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Linha2}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Linha1:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Linha2}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Antena:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Antena}</td>
																		</tr>
																		<input id="AntenaID" name="AntenaID" type="hidden" value="" />
																		 <tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Modelo Antena:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;" id='Modelo'></td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Versão do Firmware:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.VersaoFirmware}</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Operadora1:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Operador1 == true ? callback.baseInstalada.Operador1 : 'S/O'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Operadora2:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Operador2 == true ? callback.baseInstalada.Operador2 : 'S/O'}</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td height="158" style="background-color: #fff; padding: 0px">
						
																<table width="475" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse;">
						
						
																	<tbody>
																		<tr>
																			<td colspan="5" bgcolor="#FFFFFF" style="text-align: center; font; font-weight: bold; font-size: 14px;">&nbsp;</td>
																		</tr>
																		<tr>
																			<td colspan="5" bgcolor="#DDDDDD" style="text-align: center; font; font-weight: bold; font-size: 14px;">Sensores e Atuadores</td>
																		</tr>
																		<tr>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Ignição:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Ignicao == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Sirene</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Sirene == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td width="199" style="border-bottom: solid  1px #C7C7C7;">Botão  de Pânico: </td>
																			<td width="37" style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BotaoPanico == true ? 'SIM' : 'NÃO'}</td>
																			<td width="16">&nbsp;</td>
																			<td width="162" style="border-bottom: solid  1px #C7C7C7;">Setas  Pulsantes: </td>
																			<td width="37" style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.SetasPulsantes == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Botão  Abertura Baú</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BotaoAberturaBau == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Setas  Contínuas: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.SetasContinuas == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Painel: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;"> ${callback.baseInstalada.Painel == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Bloqueio  Solenoide: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BloqueioSolenoide == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Painel  Read Switch: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.PainelReadSwift == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Bloqueio  Eletrônico : </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BloqueioEletronico == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Painel  Micro Switch:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.PainelMicroSwitch == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Tipo  Trava Baú:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.TipoTravaBau == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Portas  Cabine:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.PortasCabine == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Trava  Baú Traseira: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.TravaBauTraseira == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Baú  Traseiro:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BauTraseiro == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Trava  Baú Lateral: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.TravaBauLateral == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Baú  Lateral: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BauLateral == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Trava  Baú Intermediário: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.TravaBauIntermediaria == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Baú  Intermediário: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BauIntermediario == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Trava  Quinta Roda: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.TravaQuintaRoda == true ? 'SIM' : 'NÃO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Engate  Espiral: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.EngateAspiral == true ? 'SIM' : 'NÃO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Engate  Eletrônico : </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.EngateEletronico == true ? 'SIM' : 'NÂO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Trava  Quinta Roda Inteligente: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.TravaQuintaRodaInteligente == true ? 'SIM' : 'NÂO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Temperatura :</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.SensorTemperatura == true ? 'SIM' : 'NÂO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Bloqueador  Combustível Inteligente : </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BloqueCombuInteligente == true ? 'SIM' : 'NÂO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Bloqueador  CAN: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BloqueCombuInteligente == true ? 'SIM' : 'NÂO'}</td>
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Sensor Configurável 1: </td>
																			<td colspan="4" style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BloqueCombuInteligente == true ? 'SIM' : 'NÂO'}</td>
						
																		</tr>
																		<tr>
																			<td height="17" style="border-bottom: solid  1px #C7C7C7;">Sensor Configurável 2: </td>
																			<td colspan="4"  style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BloqueCombuInteligente == true ? 'SIM' : 'NÂO'}</td>
																		</tr>
																		<tr>

																			<td style="border-bottom: solid  1px #C7C7C7;">Sensor Configurável 3: </td>
																			<td colspan="4" style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.SensorConfig3}</td>
																		</tr>
																		<tr>
						
																			<td style="border-bottom: solid  1px #C7C7C7;">Sensor Configurável 4: </td>
																			<td colspan="4"  style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.SensorConfig4}</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td height="141" style="background-color: #fff; padding: 0px">
																<table width="475" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse;">
																	<tbody>
																		<tr>
																			<td colspan="5" bgcolor="#FFFFFF" style="text-align: center; font; font-weight: bold; font-size: 14px;">&nbsp;</td>
																		</tr>
																		<tr>
																			<td colspan="5" bgcolor="#DDDDDD" style="text-align: center; font; font-weight: bold; font-size: 14px;">Acessórios</td>
																		</tr>
																		<tr>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																			<td>&nbsp;</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Teclado  Compacto:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Teclado == true ? 'SIM' : 'NÂO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Teclado  Multimídia:</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.TecladoMultimidia == true ? 'SIM' : 'NÂO'}</td>
																		</tr>
																		<tr>
																			<td width="199" style="border-bottom: solid  1px #C7C7C7;">Bateria  Backup: </td>
																			<td width="37" style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.BateriaBackup == true ? 'SIM' : 'NÂO'}</td>
																			<td width="16">&nbsp;</td>
																			<td width="162" style="border-bottom: solid  1px #C7C7C7;">Telemetria: </td>
																			<td width="37" style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.Telemetria == true ? 'SIM' : 'NÂO'}</td>
																		</tr>
																		<tr>
																			<td style="border-bottom: solid  1px #C7C7C7;">Cofre  Eletrônico: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.CofreEletronico == true ? 'SIM' : 'NÂO'}</td>
																			<td>&nbsp;</td>
																			<td style="border-bottom: solid  1px #C7C7C7;">Conversor  Tac&oacute;grafo: </td>
																			<td style="border-bottom: solid  1px #C7C7C7;">${callback.baseInstalada.CofreEletronico == true ? 'SIM' : 'NÂO'}</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
						
													</table>
												</td>
											</tr>
											<!--
											<br />
											<br />
											</td>
											</tr>-->
											<tr>
												<td>
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td style="background-color: #2ea2d7">
																<img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-logo.jpg" alt="Omnilink" />
															</td>
															<td>
																<a href="http://omnilink.com.br/" title="">
																	<img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-site.jpg" alt="Acesse o site: www.omnilink.com.br" />
																</a>
															</td>
															<td>
																<img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-call.jpg" alt="Acesse o facebook" />
															</td>
															<td>
																<a href="https://www.facebook.com/omnilinktecnologia" title="">
																	<img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-fb.jpg" alt="Acesse o facebook" />
																</a>
															</td>
															<td>
																<a href="https://www.linkedin.com/company/omnilinkbr" title="">
																	<img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-lk.jpg" alt="Acesse o linkedin" />
																</a>
															</td>
														</tr>
													</table>
												</td>
											</tr>

										</table>
									</center>
								</div>
						</body>
						</html>`;

											var settings = {
												"url": `${URL_PAINEL_OMNILINK}/ajax_enviar_email`,
												"method": "POST",
												"timeout": 0,
												"dataType": 'json',
												"data": {
													"mensagem": dado,
													"assunto": "Ficha de Ativação",
													"destinatarios": data.Email
												},
											};

											await $.ajax(settings).done(function (response) {
												if (response.code == 200) {
													qtdEmailEnviados += 1;
												}
											}).fail(function (error) {
												console.error(error)
											});

											if (objEmails.length - 1 === i) {
												if (qtdEmailEnviados != objEmails.length) {
													alert('Ficha enviada para Grupo de E-mail, porém alguns e-mails não foram enviados.');
												} else {
													alert('Ficha enviada para Grupo de E-mail');
												}
												btn.attr('disabled', false).html(htmlButton);
											}

										} else {
											btn.attr('disabled', false).html(htmlButton);
										}
									}, 'JSON');
									//final do envio do email			
									// Volta o padrão do botão
								}
							}, 'JSON');
						});
					} else {
						alert("Ocorreu um erro ao buscar os e-mails, tente novamente.");
					}
				}).catch(error => {
					alert("Ocorreu um erro ao buscar os e-mails, tente novamente.");
				});

			}

		}
	});

}

/**
 * Busca informações do modal de Item de Contrato para realizar a edição.
 * @param {*} button 
 * @param {*} idItemContrato 
 */
function getInfoEditItemDeContrato(button, idItemContrato) {
	let btn = $(button);
	const htmlButton = btn.html();
	// Reseta modal Item de Contrato
	controlarAbasModalItemDeContrato(false);
	// Reseta informações do item de contrato
	resetarInfoModalItemDeContrato();

	instanciarTabelaAtividadesServico(btn, idItemContrato, "#modalItemDeContrato")
}

function habilitaComponentesItemDeContratoPorPermissao() {
	// IDS e CLASSES dos botões que necessitam de permissão para ser habilitado
	// ".btnEditItemDeContrato",
	// ".btnDeleteItemDeContrato",
	// ".switchStatusItemDeContrato",

	let btns = [
		"#btnSalvarItemDeContrato",
		"#btnAbrirModalCadastrarAlteracaoDeContrato",
		".btnEditAlteracaoContrato",
		".btnDeleteAlteracaoContrato",
		".btnEditServicoContratado",
		".btnDeleteServicoContratado"
	];
	btns.forEach(btn => {
		if (permissoes['out_alterarInfoItensContratoOmnilink']) {
			$(btn).attr('disabled', false);
		} else {
			$(btn).attr('disabled', true);
		}
	});
}

/**
 * Salva valor antigo do item ce 
 * @param {Object} inputs 
 * @param {Object} selects 
 */
function salvarValorAntigoItemContrato(inputs, selects) {

	auditoria.valor_antigo_item_contrato = {};
	if (inputs) {
		for (key in inputs) {
			if (inputs[key]) auditoria.valor_antigo_item_contrato[key] = inputs[key];
		}
	}
	if (selects) {
		for (key in selects) {
			if (selects[key]) {
				let select = selects[key];
				if (select.id) {
					auditoria.valor_antigo_item_contrato[key] = select.id;
				}
			}
		}
	}
}

function removerItemDeContrato(button, idItemContrato) {

	let btn = $(button);
	let htmlButton = btn.html();

	if (confirm("Você tem certeza que deseja excluir este item de contrato?")) {
		const url = `${URL_PAINEL_OMNILINK}/ajax_remover_item_contrato/${idItemContrato}`;
		btn.attr('disabled', true).html(ICONS.spinner);

		salvar_auditoria(url, 'delete', null, { idItemContratoRemovido: idItemContrato })
			.then(() => {
				$.ajax({
					url: url,
					type: 'DELETE',
					success: function (callback) {
						btn.attr('disabled', false).html(htmlButton);
						callback = typeof callback == 'string' ? JSON.parse(callback) : callback;

						if (callback.status) {
							carregarQuantidadeContratos();
							atualizarTabelaContratos();

							alert(callback.message);
						} else {
							alert(callback.erro);
						}
					},
					error: function (erro) {
						btn.attr('disabled', false).html(htmlButton);
						console.error(erro);
						alert("Erro ao remover item de contrato!");
					}
				});
			})
			.catch(error => {
				alert(error);
			});
	}
}

/**
	 * Atualiza contagem do total de itens de contratos que está sendo exibido nas abas
	 */
function atualizarContagemItensDeContrato() {
	$("#ContratosAtivos").html(contratos.ativos ? contratos.ativos.length : 0);
	$("#ContratosAguardando").html(contratos.aguardando ? contratos.aguardando.length : 0);
	$("#ContratosCancelados").html(contratos.cancelados ? contratos.cancelados.length : 0);
	$("#ContratosSuspenso").html(contratos.suspensos ? contratos.suspensos.length : 0);
}

/**
 * Função que remove item de contrato do objeto contratos para que seja mantido o controle de objetos que serão
 * exibidos na tela
 * @param {String} idItemDeContrato 
 */
function removerItemDeContratoDoObjetoContratos(idItemDeContrato) {
	// percorre o objeto contratos
	for (status in contratos) {
		// busca o index do objeto
		let index = contratos[status].findIndex(c => c.id == idItemDeContrato);
		// caso encontre o objeto, remove ele do array
		if (index != -1) {
			contratos[status].splice(index, 1);
		}
	}
}

/**
 * Função que realiza requisição para alterar status do item de contrato
 * @param {String} id 
 */
async function mudarStatusItemDeContrato(id) {
	if (confirm('Você tem certeza que deseja alterar o status do item de contrato?')) {
		//STATECODE == 0 -> ATIVO 
		//STATECODE == 1 -> INATIVO 
		const statecode = $(`#statusItemDeContrato_${id}`).prop('checked') ? 0 : 1;
		const stateCodeAnterior = statecode == 0 ? 1 : 0;
		let data = {
			tz_item_contrato_vendaid: id,
			statecode: statecode
		}

		salvar_auditoria(`${URL_PAINEL_OMNILINK}/ajax_atualizar_status_item_de_contrato`, 'update', { statecode: stateCodeAnterior }, { tz_item_contrato_vendaid: id, statecode: statecode }).then(async () => {
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_atualizar_status_item_de_contrato`,
				type: 'POST',
				data: data,
				success: function (callback) {
					callback = typeof callback == 'string' ? JSON.parse(callback) : callback;
					if (callback.status) {
						alert('Status alterado com sucesso!');
						updateStatusDataItemDeContrato(callback.data);

					} else {
						alert(callback.erro);
						if (statecode == 0) {
							$(`#statusItemDeContrato_${id}`).prop('checked', false);
						} else {
							$(`#statusItemDeContrato_${id}`).prop('checked', true);
						}
					}
				},
				error: function (error) {

				}
			});
		});
	}
}
/**
 * Função que atualiza o os dados do array que guarda os itens de contrato
 */
function updateStatusDataItemDeContrato(data) {
	// Atualiza o statecode do array que guarda os itens de contrato
	const updateArrayItens = (id, statecode, array) => {
		try {
			array.find(a => a.tz_item_contrato_vendaid == id)['statecode'] = statecode;
		} catch (error) {
			console.error(error);
		}
	}

	if (data) {
		let id = data.tz_item_contrato_vendaid;
		let statecode = data.statecode;
		switch (data.tz_status_item_contrato) {
			case 1: // Ativo
				updateArrayItens(id, statecode, contratos.ativos);
				break;
			case 2: //Aguardando
				updateArrayItens(id, statecode, contratos.aguardando);
				break;
			case 3: //Cancelado
				updateArrayItens(id, statecode, contratos.cancelados);
				break;
			case 4: //Suspenso
				updateArrayItens(id, statecode, contratos.suspensos);
				break;
			default:
				break;
		}
	}
}

/**
 * Salva formulário da aba atual no modal de item de contrato
 */
// Função que retorna os dados do formulário de cadastro de acordo com o id informado
function getDadosForm(idForm) {
	let dadosForm = $(`#${idForm}`).serializeArray();
	let dados = {};
	for (let c in dadosForm) {
		dados[dadosForm[c].name] = dadosForm[c].value;
	}

	return dados;
}

/**
 * Salva formulário da aba atual no modal de item de contrato
 */
// Função que retorna os dados do formulário de cadastro de acordo com o id informado
function blockDadosForm(idForm, numeroNA, idLabel, idDivLabel) {
	$("#" + idForm + " :input").attr("disabled", true);
	$("#" + idForm + " select").attr("disabled", true);
	$("#" + idForm + " textarea").attr("disabled", true);
	$("#" + idForm + " :button").attr("disabled", true);
	if (numeroNA != null) {
		$("#" + idLabel).html('Número da NA: ' + numeroNA);
		$("#" + idDivLabel).show();
	}
}

/**
 * Limpa modal de alteração de contrato
 */
function limparModalAlteracaoDeContrato() {
	limpaInputsModal("formAlteracaoDeContrato");
	$("#itemContratoAlteracaoContrato").html("");
}
/**
 * Função de retorna os dados da alteração de contrato para ser exibido
 */
function getInfoAlteracaoContrato(button, idAlteracao) {
	let btn = $(button);
	let htmlBtn = btn.html();
	// Limpa modal de alteração de contrato
	limparModalAlteracaoDeContrato();
	// Habilita o select de status da alteração.
	$("#alteracao_contrato_statuscode").attr('disabled', false);
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_get_alteracao_contrato/${idAlteracao}`,
		type: "GET",
		beforeSend: function () {
			btn.attr('disabled', true).html(ICONS.spinner);
		},
		success: function (callback) {

			btn.attr('disabled', false).html(htmlBtn);
			callback = JSON.parse(callback);

			if (callback.status) {
				let dados = callback.data;
				// salva campos para auditoria
				salvar_valor_antigo_alteracao_contrato(dados);

				// Adiciona inputs ao formulário de edição
				$("#div_detalhes_alteracao_contrato").html(
					getHtmlDetalhesAlteracaoContrato()
				);

				$("#alteracao_contrato_tz_providencias").html(getOptionsSelectProvidencias()).select2({
					width: '100%',
					placeholder: "Selecione a providência",
					allowClear: true,
				});

				$("#alteracao_contrato_tz_incidentid").html(getOptionsSelectOcorrencias()).select2({
					width: '100%',
					placeholder: "Selecione a providência",
					allowClear: true,
				});

				// Inicia selects
				$("#alteracao_contrato_tz_rastreadorid").select2(
					getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o rastreador")
				);

				$("#alteracao_contrato_tz_planoid").select2(
					getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o plano")
				);

				// SELETOR DE INDICE DE REAJUSTE
				$("#alteracao_contrato_tz_indice_reajusteid").select2(
					getConfigBuscaServerSideSelect2('tz_indice_reajustes', 'tz_indice_reajusteid', 'tz_name', "Selecione o índice de reajuste")
				);

				$("#alteracao_contrato_tz_motivo_cancelamentoid").select2(
					getConfigBuscaServerSideSelect2('tz_motivo_cancelamentos', 'tz_motivo_cancelamentoid', 'tz_name', "Selecione o motivo da alteração")
				);

				$("#alteracao_contrato_tz_veiculo_id").select2(
					getConfigBuscaServerSideSelect2('tz_veiculos', 'tz_veiculoid', 'tz_placa', "Selecione o veículo")
				);

				const inputs = callback.data.inputs;
				const selects = callback.data.selects;

				// Insere inputs/selects formulario
				inserirDadosCamposFormulario('formAlteracaoDeContrato', inputs, selects)
				// Salva item de contrato
				if (dados.item_contrato) {
					$("#itemContratoAlteracaoContrato").html(`
						<h3>Item de Contrato: ${dados.item_contrato}</h3>
					`);
				} else {
					$("#itemContratoAlteracaoContrato").html("");
				}

				// Salva valor da taxa
				$("#alteracao_contrato_tz_valortaxa").val('');
				if (dados.tz_valortaxa) {
					$("#alteracao_contrato_tz_valortaxa").val(dados.tz_valortaxa);
				}
				// Exibe Modal
				$("#modalAlteracaoDeContrato").modal('show');
			}
		},
		error: function (error) {
			btn.attr('disabled', false).html(htmlBtn);
			console.error(error);
			alert("Erro ao buscar Alteração de Contrato");
		}
	});
}
/*
 * Função que busca as informações sobre a base instalada para realizar a edição
 */
function getInfoBaseInstalada(button, idBaseInstalada) {
	let btn = $(button);
	let htmlBtn = btn.html();

	btn.attr('disabled', true).html(ICONS.spinner);
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_get_info_base_instalada/${idBaseInstalada}`,
		type: 'GET',
		dataType: 'json',
		success: function (callback) {
			btn.attr('disabled', false).html(htmlBtn);
			callback = typeof callback == 'string' ? JSON.parse(callback) : callback;

			if (callback.status) {
				// Exibe div de sensores e atuadores
				$("#div_row_sensores_atuadores").show();
				// Cria selects de Sensores e Atuadores
				criarSelects(SELECTS_SENSORES_ATUADORES_BASE_INSTALADA, 'selects_sensores_atuadores_base_estalada');
				// Insere inputs e selects
				let inputs = callback.data.inputs;
				let selects = callback.data.selects;
				auditoria.valor_antigo_base_instalada = {};
				for (key in inputs) {
					$(`#formBaseInstalada`).find(`#${key}`).val(inputs[key]);
					auditoria.valor_antigo_base_instalada[key] = inputs[key]; // salva valor antigo do input para auditoria 
				}
				// Preenche os selects
				for (key in selects) {
					if (selects[key].id) {
						$(`#formBaseInstalada`).find(`#${key}`).html(`<option value="${selects[key].id}">${selects[key].text ? selects[key].text : "-"}</option>`).trigger('change');
						auditoria.valor_antigo_base_instalada[key] = selects[key].id; // salva valor antigo do select para auditoria
					}
				}

				// Exibe modal de base instalada
				$("#modalBaseInstalada").modal('show');
			} else {
				console.error(callback.erro);
				alert("Erro ao buscar base instalada do cliente!");
			}
		},
		error: function (erro) {
			btn.attr('disabled', false).html(htmlBtn);
			console.error(erro);
			alert("Erro ao buscar base instalada do cliente!");
		}
	});
}

/**
 * Insere inputs do formulário
 * @param {Object} inputs 
 * @param {Object} selects 
 */
function inserirDadosCamposFormulario(idForm, inputs, selects) {
	// Preenche os inputs
	for (key in inputs) {
		$(`#${idForm}`).find(`#${key}`).val(inputs[key]);
	}
	// Preenche os selects
	for (key in selects) {
		if (selects[key].id) {
			$(`#${idForm}`).find(`#${key}`).html(`<option value="${selects[key].id}">${selects[key].text ? selects[key].text : "-"}</option>`).trigger('change');
		}
	}
}

/**
 * Alerta sobre o valor da taxa caso a isenção for "NÃO"
 */
$("#alteracao_contrato_tz_isencaodetaxa").change(function () {
	let valor = $(this).val();
	let valorTaxa = $("#alteracao_contrato_tz_valortaxa").val();
	if (valor == false) {
		if (valorTaxa && valorTaxa != '') {
			alert('Atencao, a reativação deste cliente acarretara a cobranca de uma taxa no valor de R$ ' + valorTaxa);
		}
	}
})

/**
 * Salva valores antigos da alteração de contrato para auditoria
 * @param {Object} dados
 */
function salvar_valor_antigo_alteracao_contrato(dados) {
	let { inputs, selects } = dados;
	auditoria.valor_antigo_alteracao_contrato = {};
	if (inputs) {
		Object.keys(inputs).forEach(key => {
			auditoria.valor_antigo_alteracao_contrato[key] = inputs[key];
		});
	}
	if (selects) {
		Object.keys(selects).forEach(key => {
			auditoria.valor_antigo_alteracao_contrato[key] = selects[key].id;
		});
	}
}
/**
 * Função que retorna o hrml do formulário para edição de alteração de contrato
 */
function getHtmlDetalhesAlteracaoContrato() {
	return `
			<div class="row">
				<div class="col-md-12">
					<h4>Detalhes</h4>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Veículo</label>
					<select class="form-control" name="alteracao_contrato_tz_veiculo_id" id="alteracao_contrato_tz_veiculo_id"></select>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Prazo para cancelamento</label>
					<input type="number" min="0" class="form-control" id="alteracao_contrato_tz_prazo_cancelamento" id="alteracao_contrato_tz_prazo_cancelamento" disabled>
				</div>
				<div class="col-md-6">
					<label>Motivo do Cancelamento</label>
					<select class="form-control" name="alteracao_contrato_tz_motivo_cancelamentoid" id="alteracao_contrato_tz_motivo_cancelamentoid"></select>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<label>Data da última comunicação</label>
					<input type="datetime-local" class="form-control" id="alteracao_contrato_tz_data_ultima_comunicacao" name="alteracao_contrato_tz_data_ultima_comunicacao" max="2999-12-31T23:59:59">
				</div>
				<div class="col-md-6">
					<label>Concorrente</label>
					<select class="form-control select2_tz_concorrenteid" name="alteracao_contrato_tz_concorrenteid" id="alteracao_contrato_tz_concorrenteid" disabled></select>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label class="control-label">Número de Série</label>
					<input type="text" class="form-control" id="alteracao_contrato_tz_numero_serie" name="alteracao_contrato_tz_numero_serie" required>
				</div>
				<div class="col-md-6">
					<label>Número de Série da Antena Satelital</label>
					<input type="text" class="form-control" id="alteracao_contrato_tz_numero_serie_antena_satelital" name="alteracao_contrato_tz_numero_serie_antena_satelital">
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label class="control-label">Rastreador</label>
					<select class="form-control" name="alteracao_contrato_tz_rastreadorid" id="alteracao_contrato_tz_rastreadorid" required></select>
				</div>
				<div class="col-md-6">
					<label>Modelo/Tipo Informado</label>
					<input type="text" class="form-control" name="alteracao_contrato_tz_modelo_tipo_ativacao" id="alteracao_contrato_tz_modelo_tipo_ativacao" />
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label class="control-label">Plano</label>
					<select class="form-control" name="alteracao_contrato_tz_planoid" id="alteracao_contrato_tz_planoid" required></select>
				</div>
				<div class="col-md-6">
					<label>Data de Término da Demostração</label>
					<input type="date" class="form-control" name="alteracao_contrato_tz_data_termino_demonstrao" id="alteracao_contrato_tz_data_termino_demonstrao" disabled max="2999-12-31">
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Data de Início da Suspensão</label>
					<input type="date" class="form-control" name="alteracao_contrato_tz_data_inicio_suspensao" id="alteracao_contrato_tz_data_inicio_suspensao" disabled max="2999-12-31">
				</div>
				<div class="col-md-6">
					<label>Data de Término da Suspensão</label>
					<input type="date" class="form-control" name="alteracao_contrato_tz_data_termino_suspenso" id="alteracao_contrato_tz_data_termino_suspenso" disabled max="2999-12-31">
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Periodo Suspensão</label>
					<select class="form-control" name="alteracao_contrato_tz_periodosuspensao" id="alteracao_contrato_tz_periodosuspensao">
						<option value=""></option>
						<option value="1">30 dias</option>
						<option value="2">60 dias</option>
					</select>
				</div>
				<div class="col-md-6">
					<label>Data Térmido do Comodato</label>
					<input class="form-control" type="date" name="alteracao_contrato_tz_data_termino_comodato" id="alteracao_contrato_tz_data_termino_comodato" disabled max="2999-12-31">
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Data da Ativação</label>
					<input type="date" class="form-control" id="alteracao_contrato_tz_data_ativacao" name="alteracao_contrato_tz_data_ativacao" disabled max="2999-12-31">
				</div>
				<div class="col-md-6">
					<label>Data de Término da Garantia</label>
					<input type="date" class="form-control" id="alteracao_contrato_tz_data_termino_garantia" name="alteracao_contrato_tz_data_termino_garantia" max="2999-12-31">
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Data de Início da Garantia</label>
					<input type="date" class="form-control" name="alteracao_contrato_tz_data_inicio_garantia" id="alteracao_contrato_tz_data_inicio_garantia" max="2999-12-31">
				</div>
				<div class="col-md-6">
					<label>Data de Aniversário</label>
					<input type="date" class="form-control" name="alteracao_contrato_tz_data_aniversario" id="alteracao_contrato_tz_data_aniversario" max="2999-12-31">
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Indice de Reajuste</label>
					<select class="form-control" name="alteracao_contrato_tz_indice_reajusteid" id="alteracao_contrato_tz_indice_reajusteid"></select>
				</div>
				<div class="col-md-6">
					<label>Isenção de taxa</label>
					<select class="form-control" name="alteracao_contrato_tz_isencaodetaxa" id="alteracao_contrato_tz_isencaodetaxa">
						<option value=""></option>
						<option value="true">Sim</option>
						<option value="false">Não</option>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label>Providências</label>
					<select class="form-control select2_tz_providencias" name="alteracao_contrato_tz_providencias" id="alteracao_contrato_tz_providencias"></select>
				</div>
			</div>
	`;
}
/**
 * Função que remove alteração de contrato
 */
function removerAlteracaoDeContrato(button, idAlteracao) {
	let btn = $(button);
	let htmlBtn = btn.html();
	if (confirm("Você tem certeza que deseja remover a alteração de contrato?")) {
		const url = `${URL_PAINEL_OMNILINK}/ajax_remover_alteracao_contrato/${idAlteracao}`;

		btn.attr('disabled', true).html(ICONS.spinner);

		salvar_auditoria(url, 'delete', null, { idAlteracaoRemovida: idAlteracao })
			.then(async () => {
				deleteRequest(url)
					.then(callback => {
						btn.attr('disabled', false).html(htmlBtn);
						callback = typeof callback == "string" ? JSON.parse(callback) : callback;

						if (callback.status) {
							alert(callback.msg);
							tableAlteracaoDeContrato.row("#row_alteracao_contrato_" + idAlteracao).remove().draw();
						} else {
							alert(callback.erro);
						}
					})
					.catch(error => {
						btn.attr('disabled', false).html(htmlBtn);
						console.error(error);
						alert("Erro ao remover alteração de contrato!");
					});
			})
			.catch(error => { });
	}
}

/**
 * Função que busca informações do serviço contratado para edição
 */
function getInfoServicoContratado(button, idServico) {
	//pega a table para verificar a classe, e verificar se é o modal de busca específica
	//esta função foi reaproveitada para o botão de busca específica, sendo necessário adaptar alguns pontos
	let tabelaChamada = $(button.parentElement.parentElement.parentElement.parentElement);
	//se a tabela que chamou essa função for a de busca específica é necessário adicionar essa classe no modal de edição
	//isso irá servir para no hide do modal de edição fazer a reabertura do modal que chamou esta função
	if (tabelaChamada.hasClass('buscaEspecificaContrato')) {
		$("#modalServicosContratados").addClass("buscaEspecificaContrato");
	} else {
		$("#modalServicosContratados").removeClass("buscaEspecificaContrato");
	}

	let btn = $(button);
	let htmlBtn = btn.html();

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_servico_contratado/${idServico}`,
		type: "GET",
		beforeSend: function () {
			btn.attr('disabled', true).html(ICONS.spinner);
		},
		success: function (callback) {
			btn.attr('disabled', false).html(htmlBtn);
			callback = typeof callback == 'string' ? JSON.parse(callback) : callback;

			if (callback.status) {
				const inputs = callback.data.inputs;
				const selects = callback.data.selects;
				// Insere dados nos campos
				inserirDadosCamposFormulario('formServicoContratado', inputs, selects);
				// Salva Valor antigo
				salvar_valor_antigo_servico_contratado(callback.data);

				$("#modalServicosContratados").modal('show');
				$("#modalBuscarContrato").modal('hide');
			} else {
				alert("Erro ao buscar Serviço contratado!");
				console.error(callback.erro)
			}
		},
		error: function (erro) {
			btn.attr('disabled', false).html(htmlBtn);
			alert("Erro ao buscar Serviço contratado!");
			console.error(callback.erro)
		}
	});
}

/**
 * Salva dados so serviço contratado para auditoria
 */
function salvar_valor_antigo_servico_contratado(data) {
	auditoria.valor_antigo_servico_contratado = {};
	let { inputs, selects } = data;
	if (inputs) {
		Object.keys(inputs).forEach(key => {
			auditoria.valor_antigo_servico_contratado[key] = inputs[key];
		});
	}
	if (selects) {
		Object.keys(selects).forEach(key => {
			auditoria.valor_antigo_servico_contratado[key] = selects[key].id;
		});
	}

}

/**
 * Função que remove Serviço contratado
 */
function removerServicoContratado(button, idServico) {

	if (confirm("Você tem certeza que deseja remover o serviço contratado?")) {
		let btn = $(button);
		let htmlBtn = btn.html();
		const url = `${URL_PAINEL_OMNILINK}/ajax_remover_servico_contratado/${idServico}`
		btn.attr('disabled', true).html(ICONS.spinner);

		salvar_auditoria(url, 'delete', null, { idServicoContratadoRemovido: idServico })
			.then(async () => {
				deleteRequest(url)
					.then(callback => {
						btn.attr('disabled', false).html(htmlBtn);
						callback = typeof callback == "string" ? JSON.parse(callback) : callback;

						if (callback.status) {
							alert(callback.msg);
							tableServicosContratados.row("#row_servico_contratado_" + idServico).remove().draw();
						} else {
							alert(callback.erro);
						}
					})
					.catch(error => {
						btn.attr('disabled', false).html(htmlBtn);
						console.error(error);
						alert("Erro ao remover serviço contratado!");
					});
			})
			.catch(error => { });
	}
}
$(document).ready(function () {
	$("#btnAbrirModalCadastrarAlteracaoDeContrato").click(() => {
		limparModalAlteracaoDeContrato();
		$("#div_detalhes_alteracao_contrato").html("");

		$("#alteracao_contrato_tz_incidentid").html(getOptionsSelectOcorrencias()).select2({
			width: '100%',
			placeholder: "Selecione a ocorrência",
			allowClear: true,
		});

		// Desabilita o select de status da alteração.
		$("#alteracao_contrato_statuscode").attr('disabled', true);

		$("#modalAlteracaoDeContrato").modal('show');
	});
});

$(document).ready(function () {

	/**
	 * CADASTRO / EDIÇÃO ITEM DE CONTRATO
	 */
	$("#formItemDeContrato").submit(async function (event) {
		event.preventDefault();
		let button = $("#btnSalvarItemDeContrato");
		let htmlButton = button.html();
		let dados = getDadosForm('formItemDeContrato');

		button.attr('disabled', true).html(`${ICONS.spinner} Salvando`);

		/**
		 * EDITAR ITEM DE CONTRATO
		 */
		if (dados.id_item_de_contrato && dados.id_item_de_contrato != "") {
			// Salva id do item de contrato
			const id_item_de_contrato = dados.id_item_de_contrato;
			// Remove do objeto o id do item de contrato
			delete dados.id_item_de_contrato;
			const url = `${URL_PAINEL_OMNILINK}/ajax_editar_item_de_contrato/${id_item_de_contrato}`;
			// Salva novos dados para
			auditoria.valor_novo_item_contrato = dados;

			salvar_auditoria(url, 'update', auditoria.valor_antigo_item_contrato, auditoria.valor_novo_item_contrato)
				.then(async () => {

					dados = {
						valores_antigos: auditoria.valor_antigo_item_contrato,
						valores_novos: auditoria.valor_novo_item_contrato
					}

					postRequest(url, dados)
						.then(callback => {

							button.attr('disabled', false).html(htmlButton);
							callback = typeof callback == 'string' ? JSON.parse(callback) : callback;

							if (callback.status) {
								carregarQuantidadeContratos()
								atualizarTabelaContratos();

								alert('Item de Contrato editado com sucesso!');
								$("#modalItemDeContrato").modal('hide');
							} else {
								alert(callback.erro);
							}
						})
						.catch(error => {
							button.attr('disabled', false).html(htmlButton);
							console.error(error);

							alert('Erro ao editar Item de Contrato');
						});
				})
				.catch(error => {
					alert(error);
				});

		}

		/**
		 * CADASTRAR ITEM DE CONTRATO
		 */
		else {

			dados['idCliente'] = buscarDadosClienteAbaAtual()?.Id;
			dados['clientEntity'] = buscarDadosClienteAbaAtual()?.entity;
			const url = `${URL_PAINEL_OMNILINK}/ajax_adicionar_item_contrato`;
			// Salva objeto para auditoria
			auditoria.valor_novo_item_contrato = dados;
			// salva auditoria
			salvar_auditoria(url, 'insert', null, auditoria.valor_novo_item_contrato)
				.then(() => {
					// CADASTRA ITEM
					postRequest(url, dados)
						.then(callback => {
							button.attr('disabled', false).html(`Salvar`);
							if (callback.status) {
								carregarQuantidadeContratos();
								atualizarTabelaContratos();

								alert("Item de Contrato cadastrado com sucesso!");
								$("#modalItemDeContrato").modal('hide');
							} else {
								alert(callback.erro);
							}
						})
						.catch(error => {
							button.attr('disabled', false).html(`Salvar`);
							console.error(error);
							alert('Erro ao cadastrar Item de Contrato');
						});
				}).catch(erro => {
					console.error(erro);
				});

		}
	});

	/**
	 * Função que controla a atualização do item de contrato ao realizar update
	 * @param {Object} itemDeContrato 
	 * @param {Integer} statusAntigo 
	 */
	function atualizarItemDeContrato(itemDeContrato, statusAntigo) {
		// adiciona switch de status
		itemDeContrato['state'] = getSwitchStatusItemDeContrato(itemDeContrato.id, itemDeContrato.statecode);
		// adiciona botões de ação
		itemDeContrato['acoes'] = getButtonsAcoesItemDeContrato(itemDeContrato.id);
		// atualiza objeto do item de contrato
		atualizarObjetoItemDeContrato(itemDeContrato, statusAntigo);
		// atualiza tabela com o item de contrato
		atualizarTabelaItemDeContrato(itemDeContrato);
		// Atualiza numero de itens
		$("#ContratosAtivos").html(contratos.ativos ? contratos.ativos.length : 0);
		$("#ContratosAguardando").html(contratos.aguardando ? contratos.aguardando.length : 0);
		$("#ContratosCancelados").html(contratos.cancelados ? contratos.cancelados.length : 0);
		$("#ContratosSuspenso").html(contratos.suspensos ? contratos.suspensos.length : 0);
	}

	/**
	 * Função que atualiza o objeto 'contrato' de acordo com o status do item de contrato.
	 * Caso o item atualizado possua um status diferente do status anterior, transfere o objeto atualizado
	 * para o array correspondente ao status no objeto 'contrato'
	 * @param {Object} itemDeContrato 
	 * @param {Integer} statusAntigo 
	 */
	function atualizarObjetoItemDeContrato(itemDeContrato) {
		const getKeyStatus = status => {
			let keyStatus = null;
			Object.keys(STATUS_ITEM_CONTRATO).forEach(key => {
				if (STATUS_ITEM_CONTRATO[key] == status) keyStatus = key;
			});
			return keyStatus;
		}
		// percorre o objeto 
		for (status in contratos) {
			// busca o index do item de contrato no array
			const indexItem = contratos[status].findIndex(c => c.id == itemDeContrato.id);
			// caso o index for maior ou igual a zero, o objeto foi encontrato.
			if (indexItem >= 0) {
				// caso o status anterior seja diferente do novo status, remove do objeto atual e adiciona no novo array
				if (contratos[status][indexItem] && contratos[status][indexItem].status != itemDeContrato.status) {
					// Remove o item do array
					contratos[status].splice(indexItem, 1);
					// pega a chave do novo status
					const keyNewStatus = getKeyStatus(itemDeContrato.status);
					// Insere o item no novo array
					if (keyNewStatus) {
						contratos[keyNewStatus].push(itemDeContrato);
					}
				}
				// faz a atualização do objeto
				contratos[status][indexItem] = itemDeContrato;
			}
		}
	}

	/**
	 * CADASTRO / EDIÇÃO ALTERAÇÃO DE CONTRATO
	 */
	$("#formAlteracaoDeContrato").submit(async function (event) {
		event.preventDefault();
		var data = getDadosForm('formAlteracaoDeContrato');
		let btn = $("#btnSalvarAlteracaoDeContrato");
		let htmlBtn = btn.html();

		btn.attr('disabled', true).html(ICONS.spinner + " Salvando");

		/**
		 * Editar dados da alteração de contrato
		 */
		if (data.id_alteracao_de_contrato && data.id_alteracao_de_contrato != "") {
			const url = `${URL_PAINEL_OMNILINK}/ajax_editar_alteracao_contrato/${data.id_alteracao_de_contrato}`;
			delete data.id_alteracao_de_contrato;
			// Salva valor novo para auditoria
			auditoria.valor_novo_alteracao_contrato = data;
			salvar_auditoria(url, 'update', auditoria.valor_antigo_alteracao_contrato, auditoria.valor_novo_alteracao_contrato)
				.then(async () => {
					postRequest(url, data)
						.then(callback => {
							btn.attr('disabled', false).html(htmlBtn);
							if (callback.status == 1) {

								auditoria.valor_antigo_alteracao_contrato = null;
								auditoria.valor_novo_alteracao_contrato = null;

								tableAlteracaoDeContrato.row(`#row_alteracao_contrato_${callback.data.tz_manutencao_contratoid}`).data(callback.data);
								tableAlteracaoDeContrato.draw();
								limpaInputsModal('formAlteracaoDeContrato');
								$("#div_detalhes_alteracao_contrato").html('');

								$("#modalAlteracaoDeContrato").modal('hide');

								// Alerta sobre o valor da taxa caso a mesma exista
								let valorTaxa = callback.data.tz_valortaxa
								if (valorTaxa && valorTaxa != '') {
									alert('Atencao, a reativação deste cliente acarretara a cobranca de uma taxa no valor de R$ ' + valorTaxa);
								}

								alert("Alteração de Contrato editado com sucesso!");

							} else {
								console.error(callback.erro);
								alert("Erro ao editar Alteração de Contrato!");
							}
						})
						.catch(error => {
							btn.attr('disabled', false).html(htmlBtn);
							console.error(error);
							alert("Erro ao editar Alteração de Contrato!");
						});
				})
				.catch(erro => { })
		} else { // Cadastrar dado de alteração de contrato

			const url = `${URL_PAINEL_OMNILINK}/ajax_cadastrar_alteracao_contrato`;
			delete data.id_alteracao_de_contrato;
			data['id_item_de_contrato'] = $("#id_item_de_contrato").val();

			// Salva objeto para auditoria
			auditoria.valor_novo_alteracao_contrato = data;
			salvar_auditoria(url, 'insert', null, auditoria.valor_novo_alteracao_contrato)
				.then(async () => {
					postRequest(url, data)
						.then(callback => {
							btn.attr('disabled', false).html(htmlBtn);
							callback = typeof callback == "string" ? JSON.parse(callback) : callback;

							if (callback.status) {
								// Seta valor novo da alteração de contrato como null
								auditoria.valor_novo_alteracao_contrato = null;

								tableAlteracaoDeContrato.row.add(callback.data).node().id = `row_alteracao_contrato_${callback.data.tz_manutencao_contratoid}`;
								tableAlteracaoDeContrato.draw();
								alert("Cadastro de alteração de contrato realizado.");
							} else {
								alert(callback.erro);
							}
							$("#modalAlteracaoDeContrato").modal('hide');
						})
						.catch(error => {
							btn.attr('disabled', false).html(htmlBtn);
							console.error(error);
							alert("Erro ao cadastrar alteração de contrato!");

						});
				})
				.catch(erro => {
					console.error(erro)
				});

		}

	});

	/**
	 * CADASTRO / EDIÇÃO SERVIÇO CONTRATADO
	 */
	$("#formServicoContratado").submit(async function (event) {
		event.preventDefault();
		var data = getDadosForm('formServicoContratado');
		data['id_item_de_contrato'] = $("#id_item_de_contrato").val();

		btn = $("#btnSalvarServicoContratado");
		htmlBtn = btn.html();
		btn.attr('disabled', true).html(ICONS.spinner + " Salvando");

		if (data.id_servico_contratado && data.id_servico_contratado != '') { //EDITAR
			// salva campo para auditoria
			auditoria.valor_novo_servico_contratado = data;

			let id_servico_contratado = data.id_servico_contratado;
			delete data.id_servico_contratado;
			const url = `${URL_PAINEL_OMNILINK}/ajax_editar_servico_contratado/${id_servico_contratado}`;

			salvar_auditoria(url, 'update', auditoria.valor_antigo_servico_contratado, auditoria.valor_novo_servico_contratado)
				.then(async () => {
					postRequest(url, data)
						.then(callback => {
							btn.attr('disabled', false).html(htmlBtn);
							callback = typeof callback == 'string' ? JSON.parse(callback) : callback;
							if (callback.status) {
								btn.attr('disabled', false).html(`Salvar`);
								tableServicosContratados.row(`#row_servico_contratado_${callback.data.tz_produto_servico_contratadoid}`).data(callback.data);
								tableServicosContratados.draw();
								alert('Serviço contratado editado com sucesso!');
								$("#modalServicosContratados").modal('hide');
							} else {
								alert(callback.erro);
							}

						})
						.catch(erro => {
							btn.attr('disabled', false).html(htmlBtn);
							console.error(erro);
							alert("Erro ao editar Serviço Contratado!");
						});
				});

		} else {// CADASTRAR
			const url = `${URL_PAINEL_OMNILINK}/ajax_adicionar_servico_contratado`;
			// Salva dados para auditoria
			auditoria.valor_novo_servico_contratado = data;

			data['idCliente'] = buscarDadosClienteAbaAtual()?.Id;
			data['clientEntity'] = buscarDadosClienteAbaAtual()?.entity;
			salvar_auditoria(url, 'insert', null, auditoria.valor_novo_servico_contratado)
				.then(() => {
					postRequest(url, data)
						.then(callback => {
							btn.attr('disabled', false).html(htmlBtn);
							if (callback.status) {
								tableServicosContratados.row.add(callback.data).node().id = `row_servico_contratado_${callback.data.tz_produto_servico_contratadoid}`;
								tableServicosContratados.draw();
								alert('Serviço contratado adicionado com sucesso!');

								$("#modalServicosContratados").modal('hide');
							} else {
								alert(callback.erro);
							}

						})
						.catch(erro => {
							btn.attr('disabled', false).html(htmlBtn);
							console.error(erro);
							alert('Erro ao cadastrar Serviço Contratado!');
						});
				})
				.catch(error => { });
		}
	});

	$("#distancia-total-na").change(function () {
		if ((parseFloat($("#distancia-total-na").val()) - parseFloat($("#distancia-bonificada-na").val())) >= 0) {
			$("#distancia-considerada-na").val(parseFloat($("#distancia-total-na").val()) - parseFloat($("#distancia-bonificada-na").val()));
			$("#valor-total-deslocamento-na").val($("#distancia-considerada-na").val() * parseFloat($("#valor-km-considerado-na").val()));
		} else {
			$("#distancia-considerada-na").val(0)
		}
	})

	$("#distancia-bonificada-na").change(function () {
		if ((parseFloat($("#distancia-total-na").val()) - parseFloat($("#distancia-bonificada-na").val())) >= 0) {
			$("#distancia-considerada-na").val(parseFloat($("#distancia-total-na").val()) - parseFloat($("#distancia-bonificada-na").val()));
			$("#valor-total-deslocamento-na").val($("#distancia-considerada-na").val() * parseFloat($("#valor-km-considerado-na").val()));
		} else {
			$("#distancia-considerada-na").val(0)
		}
	})

	$("#distancia-total-na-2").change(function () {
		if ((parseFloat($("#distancia-total-na-2").val()) - parseFloat($("#distancia-bonificada-na-2").val())) >= 0) {
			$("#distancia-considerada-na-2").val(parseFloat($("#distancia-total-na-2").val()) - parseFloat($("#distancia-bonificada-na-2").val()));
			$("#valor-total-deslocamento-na-2").val($("#distancia-considerada-na-2").val() * parseFloat($("#valor-km-considerado-na-2").val()));
		} else {
			$("#distancia-considerada-na-2").val(0)
		}
	})

	$("#distancia-bonificada-na-2").change(function () {
		if ((parseFloat($("#distancia-total-na-2").val()) - parseFloat($("#distancia-bonificada-na-2").val())) >= 0) {
			$("#distancia-considerada-na-2").val(parseFloat($("#distancia-total-na-2").val()) - parseFloat($("#distancia-bonificada-na-2").val()));
			$("#valor-total-deslocamento-na-2").val($("#distancia-considerada-na-2").val() * parseFloat($("#valor-km-considerado-na-2").val()));
		} else {
			$("#distancia-considerada-na-2").val(0)
		}
	})

	$("#distancia-total-na-3").change(function () {
		if ((parseFloat($("#distancia-total-na-3").val()) - parseFloat($("#distancia-bonificada-na-3").val())) >= 0) {
			$("#distancia-considerada-na-3").val(parseFloat($("#distancia-total-na-3").val()) - parseFloat($("#distancia-bonificada-na-3").val()));
			$("#valor-total-deslocamento-na-3").val($("#distancia-considerada-na-3").val() * parseFloat($("#valor-km-considerado-na-3").val()));
		} else {
			$("#distancia-considerada-na-3").val(0)
		}
	})

	$("#distancia-bonificada-na-3").change(function () {
		if ((parseFloat($("#distancia-total-na-3").val()) - parseFloat($("#distancia-bonificada-na-3").val())) >= 0) {
			$("#distancia-considerada-na-3").val(parseFloat($("#distancia-total-na-3").val()) - parseFloat($("#distancia-bonificada-na-3").val()));
			$("#valor-total-deslocamento-na-3").val($("#distancia-considerada-na-3").val() * parseFloat($("#valor-km-considerado-na-3").val()));
		} else {
			$("#distancia-considerada-na-3").val(0)
		}
	})

	$("#distancia-total-na-4").change(function () {
		if ((parseFloat($("#distancia-total-na-4").val()) - parseFloat($("#distancia-bonificada-na-4").val())) >= 0) {
			$("#distancia-considerada-na-4").val(parseFloat($("#distancia-total-na-4").val()) - parseFloat($("#distancia-bonificada-na-4").val()));
			$("#valor-total-deslocamento-na-4").val($("#distancia-considerada-na-4").val() * parseFloat($("#valor-km-considerado-na-4").val()));
		} else {
			$("#distancia-considerada-na-4").val(0)
		}
	})

	$("#distancia-bonificada-na-4").change(function () {
		if ((parseFloat($("#distancia-total-na-4").val()) - parseFloat($("#distancia-bonificada-na-4").val())) >= 0) {
			$("#distancia-considerada-na-4").val(parseFloat($("#distancia-total-na-4").val()) - parseFloat($("#distancia-bonificada-na-4").val()));
			$("#valor-total-deslocamento-na-4").val($("#distancia-considerada-na-4").val() * parseFloat($("#valor-km-considerado-na-4").val()));
		} else {
			$("#distancia-considerada-na-4").val(0)
		}
	})

	$("#distancia-total-na-5").change(function () {
		if ((parseFloat($("#distancia-total-na-5").val()) - parseFloat($("#distancia-bonificada-na-5").val())) >= 0) {
			$("#distancia-considerada-na-5").val(parseFloat($("#distancia-total-na-5").val()) - parseFloat($("#distancia-bonificada-na-5").val()));
			$("#valor-total-deslocamento-na-5").val($("#distancia-considerada-na-5").val() * parseFloat($("#valor-km-considerado-na-5").val()));
		} else {
			$("#distancia-considerada-na-5").val(0)
		}
	})

	$("#distancia-bonificada-na-5").change(function () {
		if ((parseFloat($("#distancia-total-na-5").val()) - parseFloat($("#distancia-bonificada-na-5").val())) >= 0) {
			$("#distancia-considerada-na-5").val(parseFloat($("#distancia-total-na-5").val()) - parseFloat($("#distancia-bonificada-na-5").val()));
			$("#valor-total-deslocamento-na-5").val($("#distancia-considerada-na-5").val() * parseFloat($("#valor-km-considerado-na-5").val()));
		} else {
			$("#distancia-considerada-na-5").val(0)
		}
	})

	$("#btnBuscarOS").click(function (event) {
		event.preventDefault();
		var data = $('#codigoBuscaOS').val();
		var url = `${URL_PAINEL_OMNILINK}/ajax_buscar_os`;
		btn = $("#btnBuscarOS");
		htmlBtn = btn.html();
		btn.attr('disabled', true).html(ICONS.spinner + " Buscando...")

		if (!data || data == "") {
			alert("É necessário informar uma OS para busca!");
			btn.removeAttr('disabled').html(htmlBtn);
			return
		}

		$.ajax({
			url: url,
			type: 'POST',
			data: {
				"numero": data
			},
			success: function (response) {
				response = JSON.parse(response)
				if (response.status === 200) {
					let dadosOS = response.data[0];

					//permite campos alteráveis apenas para as NA's abertas e agendadas
					if (dadosOS.statecodeAtividadeDeServico !== 0 && dadosOS.statecodeAtividadeDeServico !== 3) {
						verificarPermissoesOS(false)
						$("#dropdown-modal-os").hover(function () {
							$('#btn-modal-adicionar-item-os').css("display", "none")
						})
					} else {
						$("#dropdown-modal-os").hover(function () {
							$('#btn-modal-adicionar-item-os').css("display", "block")
						}, function () {
							$('#btn-modal-adicionar-item-os').css("display", "none")
						});

						verificarPermissoesOS(true)
					}

					listarItensOS(data, dadosOS.tz_ordem_servicoid, true)

					$("#busca-header-os").html(`&nbsp;&nbsp;&nbsp;OS nº ${data}`);
					$("#numero-os").html(data);
					$('#id-os').val(dadosOS.tz_ordem_servicoid);
					$('#busca-id-os').val(dadosOS.tz_ordem_servicoid);
					$("#busca-data-criacao-os").html(dadosOS.createdon);
					$("#busca-data-modificacao-os").html(dadosOS.modifiedon);
					$("#busca-tipo-servico-os").html(dadosOS.tz_tipo_servico);
					$("#busca-modificado-por-os").html(dadosOS.modifiedby ?? "-");
					$("#busca-valor-total-os").html(dadosOS.tz_valor_total ?? "-");
					$("#busca-razao-status-os").val(dadosOS.statuscodevalue);
					$("#busca-observacoes-os").val(dadosOS.tz_observacoes);

					$("#header-busca-os").attr('style', 'display: none')
					$("#divFormOS").removeAttr('style');
					btn.removeAttr('disabled').html(htmlBtn);
				}
			}
		})
	})

	$('#modalBuscarOS').on('hidden.bs.modal', function () {

		$("#busca-header-os").html('Buscar Ordem de Serviço');
		$("#busca-numero-os").html('');
		$('#busca-id-os').val('');
		$("#busca-data-criacao-os").html('');
		$("#busca-data-modificacao-os").html('');
		$("#busca-tipo-servico-os").html('');
		$("#busca-modificado-por-os").html('');
		$("#busca-valor-total-os").html('');
		$("#busca-razao-status-os").val('');
		$("#busca-observacoes-os").html('');

		$("#header-busca-os").removeAttr('style')
		$("#divFormOS").attr('style', 'display: none');
		$('#codigoBuscaOS').val('')

	})

	/**
	 * CADASTRO / EDIÇÃO DE ITEM DE OS
	 */
	$("#btn-adicionar-item-os").on("click", function (event) {
		event.preventDefault()
		var data = getDadosForm('form-item-os');

		btn = $("#btn-adicionar-item-os");
		htmlBtn = btn.html();
		idItemOS = $('#id-item-os').val();

		if ($("#produto-item-os").text().substring(0, 22) == "TARIFA DE DESLOCAMENTO") {
			if (data.quantidadeItensOS <= 50) {
				data.statusAprovacao = "2";
			}
		}

		if (!data.quantidadeItensOS || data.quantidadeItensOS == "" || parseInt(data.quantidadeItensOS) < 0) {
			alert("É necessário informar uma quantidade válida de produtos!");
			return;
		} else if (!data.produtoItemOS || data.produtoItemOS == "") {
			alert("É necessário selecionar um produto!");
			return;
		} else if (!data.statusAprovacao || data.statusAprovacao === "0") {
			alert("É necessário selecionar um status de aprovação válido!");
			return;
		}

		if (data.valorDesconto && !isNaN(data.valorDesconto)) {
			let percentualDesconto = parseFloat(data.percentualDesconto)
			if (percentualDesconto > 100 || percentualDesconto < 0.00) {
				alert("É necessário informar uma porcentagem válida de desconto!")
				return
			} else {
				if (data.motivoDescontoItensOS === "0") {
					alert("É necessário informar o motivo do desconto!")
					return
				}
			}
		}

		if (idItemOS && idItemOS != "") { // EDIÇÃO
			btn.attr('disabled', true).html(ICONS.spinner + " Editando...");

			url = `${URL_PAINEL_OMNILINK}/editarItemOS`;
			// Salva dados para auditoria
			auditoria.valor_novo_servico_contratado = data;

			salvar_auditoria(url, 'update', auditoria.valor_antigo_servico_contratado, auditoria.valor_novo_servico_contratado)
				.then(() => {
					postRequest(url, data)
						.then(callback => {
							btn.attr('disabled', false).html(htmlBtn);

							if (callback.status === 200) {
								alert(callback.message)
								$("#modal-item-os").modal("hide")
								if ($("#modalBuscarOS").is(":visible")) {
									idOS = $("#busca-id-os").val();
								} else {
									idOS = $('#id-os').val();
								}

								let modal = false;
								if ($("#modal-na").data('bs.modal')?.isShown) {
									modal = false;
								} else {
									modal = true;
								}
								listarItensOS(null, idOS, modal)

							} else {
								alert("Ocorreu um erro ao tentar editar o item da OS, tente novamente mais tarde.");
							}

						})
						.catch(erro => {
							btn.attr('disabled', false).html(htmlBtn);
							alert('Ocorreu um erro ao editar este item na OS, tente novamente mais tarde.');
						});
				})
				.catch(error => {
					alert("Ocorreu um erro ao editar o item da OS, tente novamente em alguns minutos.")
				});
		} else { //CADASTRO
			btn.attr('disabled', true).html(ICONS.spinner + " Salvando...");
			numeroOS = $('#numero-os').html()
			data['numeroOS'] = numeroOS;
			// Salva dados para auditoria
			auditoria.valor_novo_servico_contratado = data;

			url = `${URL_PAINEL_OMNILINK}/cadastrarItemOS`
			$.ajax({
				url,
				type: 'POST',
				dataType: 'JSON',
				data,
				success: function (callback) {
					btn.attr('disabled', false).html(htmlBtn);

					if (callback.status === 201) {
						alert(callback.message)
						salvar_auditoria(url, 'insert', null, auditoria.valor_novo_servico_contratado)
						$("#modal-item-os").modal("hide")
						if ($("#modalBuscarOS").is(":visible")) {
							idOS = $("#busca-id-os").val();
						} else {
							idOS = $("#id-os").val()
						}
						numeroOS = numeroOS

						let modal = false
						if ($("#modal-na").data('bs.modal')?.isShown) {
							modal = false;
						} else {
							modal = true;
						}
						listarItensOS(numeroOS, idOS, modal)

					} else {
						alert("Ocorreu um erro ao tentar cadastrar o item da OS, tente novamente mais tarde.")
					}

				},
				error: function (error) {
					btn.attr('disabled', false).html(htmlBtn);
					alert('Ocorreu um erro ao cadastrar este item na OS, tente novamente mais tarde.')
				}
			})
				.catch(error => {
					alert("Ocorreu um erro ao cadastrar o item da OS, tente novamente em alguns minutos.")
				})
		}
	})

	/**
	 * EDIÇÃO DE OS
	 */
	$("#form-os").submit(function (event) {
		event.preventDefault();
		var data = getDadosForm('form-os');
		data['idOS'] = $('#id-os').val();

		btn = $("#btn-submit-form-os");
		htmlBtn = btn.html();
		idItemOS = $('#id-item-os').val();

		btn.attr('disabled', true).html(ICONS.spinner + " Editando...");

		url = `${URL_PAINEL_OMNILINK}/editarOS`;
		// Salva dados para auditoria
		auditoria.valor_novo_servico_contratado = data;

		salvar_auditoria(url, 'update', auditoria.valor_antigo_servico_contratado, auditoria.valor_novo_servico_contratado)
			.then(() => {
				postRequest(url, data)
					.then(callback => {
						btn.attr('disabled', false).html(htmlBtn);

						if (callback.status === 200) {
							//atualiza lista das OS
							buscarOrdensServico(callback.idAtividadeDeServicoId);
							alert(callback.message)

						} else {
							alert(callback.message);
						}

					})
					.catch(erro => {
						btn.attr('disabled', false).html(htmlBtn);
						alert('Ocorreu um erro ao tentar alterar a OS, tente novamente mais tarde.');
					});
			})
			.catch(error => {
				alert("Ocorreu um erro ao tentar alterar a OS, tente novamente em alguns minutos.")
			});
	});

	/**
	 * EDIÇÃO DE OS PELA BUSCA
	 */
	$("#formBuscarOS").submit(function (event) {
		event.preventDefault();
		var data = getDadosForm('formBuscarOS');
		data['idOS'] = $('#busca-id-os').val();

		btn = $("#btn-submit-form-os-busca");
		htmlBtn = btn.html();
		idItemOS = $('#busca-id-os').val();

		btn.attr('disabled', true).html(ICONS.spinner + " Editando...");

		url = `${URL_PAINEL_OMNILINK}/editarOS`;
		// Salva dados para auditoria
		auditoria.valor_novo_servico_contratado = data;

		salvar_auditoria(url, 'update', auditoria.valor_antigo_servico_contratado, auditoria.valor_novo_servico_contratado)
			.then(() => {
				postRequest(url, data)
					.then(callback => {
						btn.attr('disabled', false).html(htmlBtn);

						if (callback.status === 200) {
							//atualiza lista das OS
							alert(callback.message)

						} else {
							alert(callback.message);
						}

					})
					.catch(erro => {
						btn.attr('disabled', false).html(htmlBtn);
						alert('Ocorreu um erro ao tentar alterar a OS, tente novamente mais tarde.');
					});
			})
			.catch(error => {
				alert("Ocorreu um erro ao tentar alterar a OS, tente novamente em alguns minutos.")
			});
	});


	/**
	 * Função que realiza uma requisição post
	 * @param {String} url - url da requisição
	 * @param {Object} dados - parâmetros da requisição
	   */
	async function postRequest(url, dados) {
		return new Promise((resolve, reject) => {
			$.ajax({
				url: url,
				type: "POST",
				dataType: 'json',
				data: dados,
				success: function (callback) {
					resolve(callback);
				},
				error: function (error) {
					reject(error);
				}
			});
		});
	}
});
/**
 * Função que realiza uma requisição delete
 * @param {String} url - url da requisição
 * @param {Object} dados - parâmetros da requisição
 */
async function deleteRequest(url) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: url,
			type: "DELETE",
			success: function (callback) {
				resolve(callback)
			},
			error: function (error) {
				reject(error);
			}
		});
	});
}
// AÇÕES REALIZADAS AO ABRIR MODAL DE BASE INSTALADA
$(document).on('show.bs.modal', "#modalBaseInstalada", function () {
	// Insere options de cliente PF ou PJ ao abrir modal de base instalada
	inserirSelectOptionsClienteBaseInstalada();
});
// AÇÕES REALIZADAS AO FECHAR MODAL DE BASE INSTALADA
$(document).on('hide.bs.modal', "#modalBaseInstalada", function () {
	// Limpa selects booleanos
	$("#selects_sensores_atuadores_base_estalada").html('');
	// Esconde div de sensores e atuadores
	$("#div_row_sensores_atuadores").hide();
});
/**
 * Insere nos selects de clientes no modal de base instalada, o cliente que
 * está sendo exibido na tela de acordo com a entidade dele
 */
function inserirSelectOptionsClienteBaseInstalada() {
	const nomeCliente = $("#form-edit-cliente-" + abaSelecionada).find('input[name=Nome]').val();
	const idCliente = buscarDadosClienteAbaAtual()?.Id;
	const option = `<option value="${idCliente}">${nomeCliente}</option>`;
	if (buscarDadosClienteAbaAtual()?.entity == "accounts") {
		//Desabilita selects de PF
		$("#base_instalada_tz_cliente_pfid").attr('disabled', true);
		$("#base_instalada_tz_cliente_pf_matrizid").attr('disabled', true);
		$("#base_instalada_tz_cliente_pf_instaladoid").attr('disabled', true);
		$("#base_instalada_tz_cliente_anterior_pf").attr('disabled', true);
		$("#base_instalada_tz_cliente_pjid").html(option).trigger('change.select2');
		$("#base_instalada_tz_cliente_pj_matrizid").html(option).trigger('change.select2');
		$("#base_instalada_tz_cliente_pj_instaladoid").html(option).trigger('change.select2');
		// tz_account_tz_base_instalada_cliente_ClienteAnterior
	} else {
		//Desabilita selects de PJ
		$("#base_instalada_tz_cliente_pjid").attr('disabled', true);
		$("#base_instalada_tz_cliente_pj_matrizid").attr('disabled', true);
		$("#base_instalada_tz_cliente_pj_instaladoid").attr('disabled', true);
		$("#base_instalada_tz_cliente_anterior_pj").attr('disabled', true);
		$("#base_instalada_tz_cliente_pfid").html(option).trigger('change.select2');
		$("#base_instalada_tz_cliente_pf_matrizid").html(option).trigger('change.select2');
		$("#base_instalada_tz_cliente_pf_instaladoid").html(option).trigger('change.select2');
	}

	// tz_contact_tz_base_instalada_cliente_ClienteAnterior
}

/**
 * Função que cria selects booleanos e insere em uma div 
 * @param {Array} attrs - Array de Objetos contendo as propriedades dos selects que serão criados. ex.: 
 *  [
 * 		{name: "select1", class: null, label: "Select 1", required: false, disabled: false, containerSize: "col-md-6", options: {'value': 'text'}},
 * 		{name: "select2", class: "select2", label: "Select 2", required: false, disabled: false, containerSize: "col-md-6", options: {'value': 'text'}}
 * 	]
 * @param {String} containerId - ID da div onde os selects serão adicionados
 */
function criarSelects(arraySelects, containerId) {

	let selects = "";
	arraySelects.forEach(props => {
		// abre tag div
		let select = `<div class="${props.containerSize ? props.containerSize : 'col-md-6'}">`;
		// Adiciona label
		if (props.label) select += `<label ${props.required ? 'class="control-label"' : ""}>${props.label}</label>`;
		// Abre tag select
		select += `<select class="form-control ${props.class ? props.class : ''}" name="${props.name}" id="${props.name}" ${props.required ? 'required' : ""} ${props.disabled ? 'disabled' : ""}>`;
		// Adiciona options
		select += "<option></option>";
		if (props.options) {
			Object.keys(props.options).forEach(keyOption => {
				const option = props.options[keyOption]
				select += `<option value="${keyOption}" title="${option}">${option}</option>`;
			});
		}
		// fecha tag select e tag div
		select += `</select></div>`;
		// Adiciona select criado aos selects que serão adicionados na tela inicial
		selects += select;
	});

	$(`#${containerId}`).html("").html(selects);
}

// LIMPA INPUTS AO FECHAR MODAL DE BASE INSTALADA
$(document).on('hide.bs.modal', '#modalBaseInstalada', function () {
	limpaInputsModal('formBaseInstalada');
	resetSelects2();
});

function resetSelects2() {
	//PF
	$("#base_instalada_tz_cliente_pfid").attr('disabled', false);
	$("#base_instalada_tz_cliente_pf_matrizid").attr('disabled', false);
	$("#base_instalada_tz_cliente_pf_instaladoid").attr('disabled', false);
	$("#base_instalada_tz_cliente_anterior_pf").attr('disabled', false);

	//PJ
	$("#base_instalada_tz_cliente_pjid").attr('disabled', false);
	$("#base_instalada_tz_cliente_pj_matrizid").attr('disabled', false);
	$("#base_instalada_tz_cliente_pj_instaladoid").attr('disabled', false);
	$("#base_instalada_tz_cliente_anterior_pj").attr('disabled', false);
}

/**
 * Remove base instalada do cliente
 * @param {*} button 
 * @param {*} idBaseInstalada 
 */
function removerBaseInstalada(button, idBaseInstalada) {
	if (permissoes['out_alterarInfoItensContratoOmnilink']) {
		if (confirm('Você tem certeza que deseja excluir a base instalada do cliente?')) {
			let btn = $(button);
			let htmlBtn = btn.html();
			btn.attr('disabled', true).html(ICONS.spinner);

			const url = `${URL_PAINEL_OMNILINK}/ajax_remover_base_instalada/${idBaseInstalada}`;

			salvar_auditoria(url, 'delete', null, { idBaseInstaladaRemovida: idBaseInstalada })
				.then(async () => {
					$.ajax({
						url: url,
						type: 'DELETE',
						success: function (callback) {
							btn.attr('disabled', false).html(htmlBtn);
							callback = typeof callback == 'string' ? JSON.parse(callback) : callback;

							if (callback.status) {
								$("#tableBaseInstalada-" + abaSelecionada).DataTable().ajax.reload(null, false);
								alert(callback.msg);
							} else {
								alert(callback.erro);
							}
						},
						error: function (error) {
							btn.attr('disabled', false).html(htmlBtn);

							alert("Erro ao remover base instalada!");
						},
					});
				})
				.catch(error => {
					alert("Erro desconhecido: " + error);
					btn.attr('disabled', false).html(htmlBtn);
				});
		}
	} else {
		alert('Você não têm permissões para remover Bases Instaladas.');
	}
}

$(document).ready(() => {
	$("#formBaseInstalada").submit(async event => {
		event.preventDefault();
		let data = getDadosForm("formBaseInstalada");
		let btn = $("#btnSubmitBaseInstalada");
		let htmlBtn = btn.html();
		btn.attr('disabled', true).html(`${ICONS.spinner} Salvando`);
		const idBaseInstalada = $("#base_instalada_tz_base_instalada_clienteid").val();

		if (data.base_instalada_tz_cliente_pjid === "undefined") {
			alert('É necessário informar o cliente.')
			btn.attr('disabled', false).html('Salvar');
			return;
		}

		if (data.base_instalada_tz_operadora1) {
			if (isNaN(parseInt(data.base_instalada_tz_operadora1))) {
				alert('O campo "Operadora" do chip 1 precisa ser um número.');
				btn.attr('disabled', false).html('Salvar');
				return;
			}
		}

		if (data.base_instalada_tz_operadora2) {
			if (isNaN(parseInt(data.base_instalada_tz_operadora2))) {
				alert('O campo "Operadora" do chip 2 precisa ser um número.');
				btn.attr('disabled', false).html('Salvar');
				return;
			}
		}

		if (data.base_instalada_tz_cor) {
			if (data.base_instalada_tz_cor.length > 100) {
				alert('O campo "Cor" do veículo deve ter no máximo 100 caracteres.');
				btn.attr('disabled', false).html('Salvar');
				return;
			}
		}

		if (idBaseInstalada && idBaseInstalada != "") { // EDIÇÃO
			auditoria.valor_novo_base_instalada = data;
			const url = `${URL_PAINEL_OMNILINK}/ajax_editar_base_instalada/${idBaseInstalada}`;
			salvar_auditoria(url, 'update', auditoria.valor_antigo_base_instalada, auditoria.valor_novo_base_instalada)
				.then(async () => {
					$.ajax({
						url: url,
						type: "POST",
						dataType: 'json',
						data: data,
						success: function (callback) {
							btn.attr('disabled', false).html(htmlBtn);
							callback = typeof callback == 'string' ? JSON.parse(callback) : callback;
							if (callback.status) {
								if (instanciaTabelasBaseInstalada[pegarIndiceDaAbaAtual()]) $("#tableBaseInstalada-" + abaSelecionada).DataTable().ajax.reload(null, false);
								if ("mensagem" in callback && callback.mensagem) {
									alert(`${callback.mensagem}`);
								} else {
									alert("Base instalada editada com sucesso!");
								}

								$('#modalBaseInstalada').modal("hide");

								auditoria.valor_antigo_base_instalada = null;
								auditoria.valor_novo_base_instalada = null;
							} else {
								if ("erro" in callback && callback.erro) {
									alert(`${callback.erro}`);
								} else {
									alert("Erro ao editar base instalada!");
								}
							}
						},
						error: function (error) {
							btn.attr('disabled', false).html(htmlBtn);
							alert("Erro ao editar base instalada!");
							console.error(error);
						}
					});
				})
				.catch(error => {
					btn.attr('disabled', false).html(htmlBtn);
				});
		} else {// CADASTRO
			const url = `${URL_PAINEL_OMNILINK}/ajax_cadastrar_base_instalada`;
			salvar_auditoria(url, 'insert', null, data)
				.then(async () => {
					$.ajax({
						url: url,
						type: "POST",
						dataType: 'json',
						data: data,
						success: function (callback) {
							btn.attr('disabled', false).html(htmlBtn);
							callback = typeof callback == 'string' ? JSON.parse(callback) : callback;
							if (callback.status) {
								$("#tableBaseInstalada-" + abaSelecionada).DataTable().ajax.reload();

								if ("mensagem" in callback && callback.mensagem) {
									alert(`${callback.mensagem}`);
								} else {
									alert("Base instalada editada com sucesso!");
								}

								$('#modalBaseInstalada').modal("hide");
							} else {
								if ("erro" in callback && callback.erro) {
									alert(`${callback.erro}`);
								} else {
									alert("Erro ao editar base instalada!");
								}
							}
						},
						error: function (error) {
							btn.attr('disabled', false).html(htmlBtn);
							alert("Erro ao cadastrar base instalada!");
							console.error(error);
						}
					});
				})
				.catch(error => {
					btn.attr('disabled', false).html(htmlBtn);
					console.error(error);
				});


		}
	})


});

//limpa o modal de busca ao fechar
$('#modalBuscarContrato').on('hidden.bs.modal', function () {
	$('#rowInfoContrato').hide();
	$('#serialBuscarContrato').val("").trigger('change');
	$('#placaBuscarContrato').val("").trigger('change');
	$('#btn-info-contrato').hide();
	//esconde o conteúdo da tabela de atividades de serviço
	$("#atividade-servico-content").hide();
	$("#os-content").hide();
	$("#abas-ic").hide();

})

/**
 * Função que limpa os inputs do modal de item de contrato
 * @param {String} idForm - ID do formulário
 */
function limpaInputsModal(idForm) {
	$(`#${idForm}`).find('select,input,textarea').each(function () {
		$(this).val('').trigger('change');
	});
}

function getDadosForm(idForm) {
	let dadosForm = $(`#${idForm}`).serializeArray();
	let data = {};
	dadosForm.forEach(element => {
		data[element.name] = element.value;
	});
	return data;
}

//função para limpar as variáveis da fila para que faça a lógica correta na hora da edição de ocorrências
function limparVariaveisFila() {
	$('#filas').val(null).trigger('change');
	$('#fila-nome').val("");
}

function recarregarOcorrencias() {
	listarTotaisOcorrencias();
	atualizarTabelaOcorrencias(incidentStateCode, true);
}

function recarregarProvidencias() {
	$('#table_providencias-' + abaSelecionada).DataTable().draw();
}

function buscarGrupoDeEmail(idGrupo) {
	//roda um for para mostrar ao usuário que estão sendo buscados os valores
	for (let i = 0; i <= 10; i++) {
		$(`#email${i}-${abaSelecionada}`).val('Carregando...');
		$(`#id-email${i}-${abaSelecionada}`).val(null);

	}
	//limpa o id dos emails
	$(`id-emails-${abaSelecionada}`).val('');

	let data = {
		idGrupo: idGrupo
	}

	//chama a função no controller
	$.post(`${URL_PAINEL_OMNILINK}/buscarGrupoDeEmail`, data, function (response) {


		//verifica se a resposta está ok para converter
		if (response) {
			let resposta = JSON.parse(response);

			if (resposta.status === 200) {
				//preenche os campos dos e-mails
				let emails = resposta.data[0];
				$(`#email1-${abaSelecionada}`).val(emails.email1);
				$(`#email2-${abaSelecionada}`).val(emails.email2);
				$(`#email3-${abaSelecionada}`).val(emails.email3);
				$(`#email4-${abaSelecionada}`).val(emails.email4);
				$(`#email5-${abaSelecionada}`).val(emails.email5);
				$(`#email6-${abaSelecionada}`).val(emails.email6);
				$(`#email7-${abaSelecionada}`).val(emails.email7);
				$(`#email8-${abaSelecionada}`).val(emails.email8);
				$(`#email9-${abaSelecionada}`).val(emails.email9);
				$(`#email10-${abaSelecionada}`).val(emails.email10);

				$(`#id-email1-${abaSelecionada}`).val(emails.idEmail1);
				$(`#id-email2-${abaSelecionada}`).val(emails.idEmail2);
				$(`#id-email3-${abaSelecionada}`).val(emails.idEmail3);
				$(`#id-email4-${abaSelecionada}`).val(emails.idEmail4);
				$(`#id-email5-${abaSelecionada}`).val(emails.idEmail5);
				$(`#id-email6-${abaSelecionada}`).val(emails.idEmail6);
				$(`#id-email7-${abaSelecionada}`).val(emails.idEmail7);
				$(`#id-email8-${abaSelecionada}`).val(emails.idEmail8);
				$(`#id-email9-${abaSelecionada}`).val(emails.idEmail9);
				$(`#id-email10-${abaSelecionada}`).val(emails.idEmail10);

				$(`#id-emails-${abaSelecionada}`).val(emails.idEmails);

			} else {
				//se não for status 200 limpar todos os campos
				for (let i = 0; i <= 10; i++) {
					$(`#email${i}-${abaSelecionada}`).val('');
				}
			}

		} else {
			alert("Ocorreu um erro ao buscar os e-mails, tente novamente.");
		}
	}).catch(error => {
		alert("Ocorreu um erro ao buscar os e-mails, tente novamente.");
	});
}

$('.dropdown-grupos-email').on('change', function () {
	buscarGrupoDeEmail($(`#dropdown-grupos-email-${abaSelecionada} select`).val())
});
function fecharModalBuscaNA() {
	if ($('#modal-resultado-busca-na').is(':visible')) $('#modal-resultado-busca-na').modal('hide');
}

/**
 * Função para excluir o item da ordem de serviço
 * @param {button} button 	- Botão pressionado
 * @param {String} idItemOS - id do item da OS
 * @param {String} idOS 	- id do item da OS
 */
function excluirItemOS(button, idItemOS, idOS) {
	if (!idItemOS) {
		alert('Falha ao excluir o item da OS, verifique os campos e tente novamente.');
		return;
	}

	let confirmacao = confirm('Excluir o item da OS?\nEssa operação não pode ser desfeita.');
	if (confirmacao) {
		botao = $(button);
		botao.attr('disabled', true).html(ICONS.spinner);

		$.ajax({
			url: `${URL_PAINEL_OMNILINK}` + '/excluirItemOS',
			type: 'POST',
			dataType: 'json',
			data: { idItemOS },
			success: function (resposta) {

				if (resposta.status === 204) {
					listarItensOS(null, idOS)
					alert(resposta.message);

				} else {
					alert(resposta.mensagem ?? 'Ocorreu um erro ao excluir o item da OS.');

				}
			},
			error: function (e) {
				alert(e.message);
			},
			complete: function () {
				botao.html('<i class="fa fa-trash" aria-hidden="true"></i>');
				botao.attr('disabled', false);
			}
		});
	}
}

/**
 * Função para editar o item da ordem de serviço
 * @param {button} button 	- Botão pressionado
 * @param {String} idItemOS - id do item da OS
 */
function editarItemOS(button, idItemOS) {
	if (!idItemOS) {
		alert('Ocorreu um problema ao alterar o item da OS, verifique os campos e tente novamente.');
		return;
	}

	botao = $(button);
	botao.attr('disabled', true).html(ICONS.spinner);

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}` + '/buscarItensOS',
		type: 'GET',
		dataType: 'json',
		data: { idItemOS },
		success: function (resposta) {

			if (resposta.code === 200) {
				dadosItemOS = resposta.data;

				$('#quantidade-itens-os').val(dadosItemOS.tz_quantidade)
				$('#status-aprovacao').val(dadosItemOS.tz_status_aprovacao)
				$('#id-item-os').val(dadosItemOS.tz_item_ordem_servicoid) //variável hidden para fazer a validação de que é um edit

				$("#produto-item-os").append('<option value="' + dadosItemOS.tz_itemid + '">' + dadosItemOS.tz_itemname + '</option>')
				$('#produto-item-os').val(dadosItemOS.tz_itemid)
				$('#valor-unitario-item-os').val(dadosItemOS.tz_valor_unitario)
				$('#valor-desconto-itens-os').val(dadosItemOS.tz_valor_desconto ?? 0)
				$('#percentual-desconto-item-os').val(dadosItemOS.tz_percentual_desconto ?? 0)
				$('#motivo-desconto-item-os').val(dadosItemOS.tz_motivo_desconto ?? 0)
				if (dadosItemOS.tz_motivo_desconto && dadosItemOS.tz_motivo_desconto != null) {
					$("#motivo-desconto-item-os").trigger("change")
				}
				$('#valor-total-itens-os').val(dadosItemOS.tz_valor_total)
				$("#modificado-por-item-os").html(`(Modificado por: ${dadosItemOS.modifiedby ?? "-"})`)
				$("#aprovador-itens-os").append('<option value="' + dadosItemOS.tz_aprovador_desconto_id + '">' + dadosItemOS.tz_aprovador_desconto + '</option>')
				$('#aprovador-itens-os').val(dadosItemOS.tz_aprovador_desconto_id)
				if (dadosItemOS.tz_motivo_desconto == 3 || dadosItemOS.tz_motivo_desconto == 6) {
					$("#div-aprovador-itens-os").show();
				} else {
					$("#div-aprovador-itens-os").hide();
				}


				$('#modal-item-os').modal("show")
			} else {
				alert(resposta.message)
			}
		},
		error: function (e) {
			alert(e.message)
		},
		complete: function () {
			botao.html('<i class="fa fa-pencil" aria-hidden="true"></i>')
			botao.attr('disabled', false)
		}
	});
}

$("#motivo-desconto-item-os").change(function (event) {
	if ($("#motivo-desconto-item-os").val() == 3 || $("#motivo-desconto-item-os").val() == 6) {
		$("#div-aprovador-itens-os").show();
	} else {
		$("#div-aprovador-itens-os").hide();
	}
});

/**
 * Função para editar o item da ordem de serviço
 * @param {button} button 	- Botão pressionado
 * @param {String} idAnotacao - id da anotação
 */
function editarAnotacao(button, idAnotacao) {
	if (!idAnotacao) {
		alert('Ocorreu um problema ao alterar a anotação, verifique os campos e tente novamente.')
		return
	}

	botao = $(button);
	botao.attr('disabled', true).html(ICONS.spinner);

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}` + '/buscar_anotacao',
		type: 'POST',
		dataType: 'json',
		data: { id: idAnotacao },
		success: function (resposta) {

			if (resposta.code === 200) {
				dadosAnotacao = resposta.data[0]

				$('#nomeAnotacao').val(dadosAnotacao.subject)
				$('#descricaoAnotacao').val(dadosAnotacao.notetext)
				$('#id-anotacao').val(idAnotacao) //variável hidden para fazer a validação de que é um edit

				$('#modalCadastrarAnotacao').modal("show")

			} else {
				alert(resposta.message);
			}
		},
		error: function (e) {
			alert(e.message);
		},
		complete: function () {
			botao.html('<i class="fa fa-pencil" aria-hidden="true"></i>');
			botao.attr('disabled', false);
		}
	});
}

/**
 * Função para limpar os campos do item da OS (necessário caso haja update seguido de cadastro)
 */
function limparCamposCadastroItemOS() {
	$("#form-item-os")[0].reset()
	$('#produto-item-os').empty()
	$('#aprovador-item-os').empty()
	$('#div-aprovador-itens-os').hide()
	$('#id-item-os').val("")
	$("#modificado-por-item-os").html(``)
	habilitarCamposCadastroItemOS()
}

/**
 * Função para desabilitar ou permitir alteração de itens
 */
function verificarPermissoesOS(statusOS) {
	if (!permissoes['edi_alteracaoos'] || !statusOS) {
		$('#btn-submit-form-os').attr("disabled", true);
		$('#btn-modal-adicionar-item-os').attr("disabled", true);
		$('#quantidade-itens-os').attr("disabled", true);
		$('#status-aprovacao').attr("disabled", true);
		$('#produto-item-os').attr("disabled", true);
		$('#observacoes-os').attr("disabled", true);
	}
}

function habilitarCamposCadastroItemOS() {
	if (!permissoes['edi_alteracaoos']) {
		$('#btn-modal-adicionar-item-os').attr("disabled", false);
		$('#quantidade-itens-os').attr("disabled", false);
		$('#status-aprovacao').attr("disabled", false);
		$('#produto-item-os').attr("disabled", false);
	}
}

function instanciarTabelaAtividadesServico(button, idItemContrato, divTabela) {
	let textoInicialBotao = button.text();
	let btn = button;
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_get_informacoes_item_de_contrato/${idItemContrato}`,
		type: "GET",
		beforeSend: function () {

			btn[0].id === 'btnBuscarContrato' ? textoBotao = ICONS.spinner + ' Buscando' : textoBotao = ICONS.spinner;

			btn.attr('disabled', true).html(textoBotao);
		},
		success: function (callback) {

			textoInicialBotao.trim() === "" ? textoInicialBotao = '<i class="fa fa-edit" aria-hidden="true"></i>' : "";
			btn.attr('disabled', false).html(textoInicialBotao);

			let resposta = typeof callback == 'string' ? JSON.parse(callback) : callback;
			if (resposta.status) {
				if (resposta.data) {

					let { alteracoes_contrato, inputs, selects, servicos_contratados } = resposta.data;

					salvarValorAntigoItemContrato(inputs, selects);
					// Adiciona os valores nos inputs
					if (inputs) {
						for (key in inputs) {
							$("#formItemDeContrato").find(`#${key}`).val(inputs[key]);
						}
					}
					// Adiciona os valores nos selects
					if (selects) {
						for (key in selects) {
							if (selects[key]) {
								let select = selects[key];
								if (select.id) {
									$("#formItemDeContrato").find(`#${key}`).html(`
										<option value="${select.id}" selected>${select.text ? select.text : '(sem nome)'}</option>
									`);
								}
							}
						}
					}
					$("#formItemDeContrato").find('select').trigger('change');
					// Adiciona dados na tabela de alterações de contrato

					tableServicosContratados.clear();
					tableServicosContratadosBusca.clear();

					if (alteracoes_contrato && alteracoes_contrato.length > 0) {
						tableAlteracaoDeContrato.rows.add(alteracoes_contrato).draw();
					}
					// Adiciona dados na tabela de serviços contratados
					if (servicos_contratados && servicos_contratados.length > 0) {
						tableServicosContratados.rows.add(servicos_contratados).draw();
						tableServicosContratados.columns.adjust().draw()
					}

					habilitaComponentesItemDeContratoPorPermissao();

					if (divTabela === '#rowInfoContrato') {
						$('#abas-ic').show();
						$(divTabela).show();
						$("#os-ic").click();
					} else {
						$(divTabela).modal('show');
					}
				}
			}
		},
		error: function (error) {
			btn.attr('disabled', false).html(textoInicialBotao);
			alert("Erro ao buscar atividades de serviço.");
		}
	});
}

function abrirModalComunicacaoChip(botao, serial, pesquisaAvancada) {
	if (pesquisaAvancada) {
		$("#div-busca-chip").css("display", "block")
		$("#informacoes-chip").css("display", "none")
		$("#h3-busca-chip").html(`Buscar Chip`)
		serial = $("#input-buscar-chip").val()

		if (!$('#modal-comunicacao-chip').hasClass('in'))
			$("#modal-comunicacao-chip").modal("show")
	} else {
		$("#div-busca-chip").css("display", "none")
	}

	let promises = [];
	if (serial) {
		ShowLoadingScreen();
		let url = `${URL_API_COMUNICACAO_CHIP}api/chips/${serial}`

		let promiseChipComunicacao = $.ajax({
			url: `${URL_PAINEL_OMNILINK}/buscarChipsComunicacao`,
			type: 'POST',
			dataType: 'json',
			data: { url },
		}).then(function (resposta) {
			if (resposta.status === 200) {

				if (resposta.dados.length) {
					let equipamentos = resposta.dados.map(dado => {
						let data = dado?.data ? new Date(dado?.data).toLocaleString() : "-"
						let status = dado?.status === 0 ? "Ativo" : "Inativo"
						return {
							nomeTecnologia: dado?.nomeTecnologia ?? "-",
							nomeModelo: dado?.nomeModelo ?? "-",
							fone: `(${dado?.ddd}) ${dado?.fone}`,
							operadora: dado?.operadoraNome ?? "-",
							data,
							status,
							idOperadora: dado?.operadora,
							linha: dado?.ddd + dado?.fone,
							idEmpresa: dado?.idTecnologia,
						}
					})

					tabelaComunicacao.clear().draw();
					tabelaComunicacao.rows.add(equipamentos).draw()
					return true
				} else {
					tabelaComunicacao.clear().draw();
					return false
				}
			} else {
				tabelaComunicacao.clear().draw();
				return false
			}
		});

		promises.push(promiseChipComunicacao);

		//requisição ajax para buscar as informações da antena iridium e preencher a tabelaIridium
		let promiseBuscaInfoIridium = $.ajax({
			url: `${URL_PAINEL_OMNILINK}/buscarInfoIridium`,
			type: 'POST',
			dataType: 'json',
			data: {
				serial: serial
			},
		}).then(function (data) {
			if (data.status == 200) {
				if (data?.dados) {
					let imei = data.dados?.imei ?? "-"
					let estado = data.dados?.accountStatus ? data.dados?.accountStatus : "-"
					let criadoEm = new Date(data.dados?.createDate).toLocaleDateString() ?? "-"
					let atualizadoEm = new Date(data.dados?.updateDate).toLocaleDateString() ?? "-"

					let iridium = data.dados.deliveryDetails.map(dado => {
						return {
							imei: imei,
							estado: estado,
							criadoEm: criadoEm,
							atualizadoEm: atualizadoEm,
							destino: dado.destination ? dado.destination : "-",
							metodoDeEntrega: dado.deliveryMethod ? dado.deliveryMethod : "-",
							geoData: dado.geoDataFlag ? 'Ativo' : 'Inativo',
							moack: dado.moackFlag ? 'Ativo' : 'Inativo',
						}
					});

					tabelaIridium.clear()
					tabelaIridium.rows.add(iridium).draw()

					return true
				} else {
					tabelaIridium.clear().draw();
					return false
				}
			} else {
				tabelaIridium.clear().draw();
				return false
			}
		})

		promises.push(promiseBuscaInfoIridium);

		$.when(...promises).then(function (chipsComunicacao, infoIridium) {
			if (chipsComunicacao || infoIridium) {
				$("#informacoes-chip").css("display", "block")
				$("#h3-busca-chip").html(`Equipamento: ${serial}`)
				$("#h3-busca-chip").attr("serialEquipamento", serial);
				if (!$('#modal-comunicacao-chip').hasClass('in')) {
					$("#modal-comunicacao-chip").modal("show")
				}
			} else {
				alert("Nenhum equipamento encontrado com este serial.")
			}
		}).fail(function (error) {
			alert("Ocorreu um problema ao buscar os equipamentos. Tente novamente.")
		}).always(function () {
			HideLoadingScreen();
		})
	}
}

function abrirModalInformacoesNA(botao, idAtividadeServico) {
	if (idAtividadeServico) {
		botao = $(botao)
		let htmlBotao = botao.html()
		botao.attr("disabled", true).html(ICONS.spinner)

		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/buscarinfoNA`,
			type: 'POST',
			data: {
				'id': idAtividadeServico
			},
			success: function (resposta) {
				resposta = JSON.parse(resposta)
				if (resposta?.status === 200) {
					if (resposta?.data) {
						$("#infoNa_criadoPor").text(resposta?.data[0]?.criadoPor ?? '-')
						$("#infoNa_criadoEm").text(resposta?.data[0]?.criadoEm ?? '-')
						$("#infoNa_modificadoPor").text(resposta?.data[0]?.modificadoPor ?? '-')
						$("#infoNa_modificadoEm").text(resposta?.data[0]?.modificadoEm ?? '-')

						$("#h3-busca-na").text("Propriedades da Atividade de Serviço - " + resposta?.data[0]?.code)
						$("#modal-info-na").modal("show")
					}
				}
			},
			complete: function () {
				$(botao).attr("disabled", false).html(htmlBotao)
			}
		})
	}
}

async function abrirModalInformaçõesMHS(serial, botao) {
	if (serial) {
		ShowLoadingScreen();

		botao = $(botao)
		let htmlBotao = botao.html()
		botao.attr("disabled", true).html(ICONS.spinner)

		let url = `${URL_API_INFOMACOES_MHS}${serial}`

		await $.ajax({
			url: `${URL_PAINEL_OMNILINK}/buscarinformacoesMHS`,
			type: 'POST',
			dataType: 'json',
			data: { url },
			success: function (resposta) {
				if (resposta.status === 200) {
					if (resposta.dados) {
						let dados = resposta.dados
						let dataHoraCancelamento = dados?.dhCancelamento ? new Date(dados?.dhCancelamento).toLocaleString() : "-"
						let dataHoraSuspensao = dados?.dhSuspensao ? new Date(dados?.dhSuspensao).toLocaleString() : "-"
						let dataUltimaComunicacao = dados?.ultimaComunicacao[0]?.ultimaComunicacao ? new Date(dados?.ultimaComunicacao[0]?.ultimaComunicacao).toLocaleString() : "-"
						if (dados && dados?.idAntenna?.toLowerCase().includes('sky') || dados && dados?.nomeOperadora?.toLowerCase().includes('sky')) {
							document.querySelector('.titulo-mhs-antena').textContent = 'Antena Orbcomm';
						}

						var informacoes = {
							idEquipamento: dados?.idEquipamento ?? "-",
							dataHoraSuspensao,
							dataHoraCancelamento,
							idAntenna: dados?.idAntenna ?? "-",
							operadora: dados?.operadora ?? "-",
							nomeOperadora: dados?.nomeOperadora ?? "-",
							dataUltimaComunicacao,
							tipoUltimaComunicacao: dados?.ultimaComunicacao[0]?.tecnologia ?? "-",
							descricaoTipo: dados?.ultimaComunicacao[0]?.descricaoTipo ?? "-",
						}
						tabelaInformacoesMhs.clear()
						tabelaInformacoesMhs.rows.add([informacoes]).draw()

						$("#h3-modal-informações-mhs").html(`Equipamento : ${dados.idEquipamento}`)
					}
					else {
						alert("Nenhum equipamento encontrado com este serial");
					}

				} else {
					alert("Nenhum equipamento encontrado com este serial");
				}
			},
			error: function (e) {
				alert("Ocorreu um problema ao tentar buscar os equipamentos, tente novamente mais tarde.")
			},
			complete: function () {
				$(botao).attr("disabled", false).html(htmlBotao)
			}
		})

		await $.ajax({
			url: `${URL_PAINEL_OMNILINK}/buscarInfoIridium`,
			type: 'POST',
			dataType: 'json',
			data: {
				serial: serial
			},
		}).then(function (data) {
			if (data.status == 200) {
				if (data?.dados) {
					let imei = data.dados?.imei ?? "-"
					let estado = data.dados?.accountStatus ? data.dados?.accountStatus : "-"
					let criadoEm = new Date(data.dados?.createDate).toLocaleDateString() ?? "-"
					let atualizadoEm = new Date(data.dados?.updateDate).toLocaleDateString() ?? "-"

					let iridium = data.dados.deliveryDetails.map(dado => {
						return {
							imei: imei,
							estado: estado,
							criadoEm: criadoEm,
							atualizadoEm: atualizadoEm,
							destino: dado.destination ? dado.destination : "-",
							metodoDeEntrega: dado.deliveryMethod ? dado.deliveryMethod : "-",
							geoData: dado.geoDataFlag ? 'Ativo' : 'Inativo',
							moack: dado.moackFlag ? 'Ativo' : 'Inativo',
						}
					});

					tabelamhs.clear()
					tabelamhs.rows.add(iridium).draw()
					return true
				} else {
					tabelamhs.clear().draw();
					return false
				}
			} else {
				tabelamhs.clear().draw();
				return false
			}
		})
		$("#informações-mhs").css("display", "block");
		$("#modal-informações-mhs").modal("show");
		HideLoadingScreen();
	}
}


/**
 * Função que limpa os inputs do modal de item de contrato
 */
function retornarTipoDeCliente(documento = false) {
	if (documento) return documento.length > 14 ? "pj" : "pf";
	buscarDadosClienteAbaAtual()?.document.length > 14 ? tipoCliente = "pj" : tipoCliente = "pf";
	return tipoCliente;
}

/**
 * Função para pegar o indíce atual da aba selecionada
 */
function pegarIndiceDaAbaAtual() {
	return parseInt(abaSelecionada) - 1;
}

/**
 * Função para pegar o cliente da aba selecionada
 */
function buscarDadosClienteAbaAtual() {
	return account[pegarIndiceDaAbaAtual()];
}

$("#local-atendimento-na").on("change", function (e) {
	divEndereco = document.getElementById("endereco-na")
	divDistancia = document.getElementById("distancia-na")
	visibilidade = this.value === "1" ? "none" : "block"
	divEndereco.style.display = visibilidade
	divDistancia.style.display = visibilidade
})

$("#local-atendimento-na-2").on("change", function (e) {
	divEndereco = document.getElementById("endereco-na-2")
	divDistancia = document.getElementById("distancia-na-2")
	visibilidade = this.value === "1" ? "none" : "block"
	divEndereco.style.display = visibilidade
	divDistancia.style.display = visibilidade
})

$("#local-atendimento-na-3").on("change", function (e) {
	divEndereco = document.getElementById("endereco-na-3")
	divDistancia = document.getElementById("distancia-na-3")
	visibilidade = this.value === "1" ? "none" : "block"
	divEndereco.style.display = visibilidade
	divDistancia.style.display = visibilidade
})

$("#local-atendimento-na-4").on("change", function (e) {
	divEndereco = document.getElementById("endereco-na-4")
	divDistancia = document.getElementById("distancia-na-4")
	visibilidade = this.value === "1" ? "none" : "block"
	divEndereco.style.display = visibilidade
	divDistancia.style.display = visibilidade
})

$("#local-atendimento-na-5").on("change", function (e) {
	divEndereco = document.getElementById("endereco-na-5")
	divDistancia = document.getElementById("distancia-na-5")
	visibilidade = this.value === "1" ? "none" : "block"
	divEndereco.style.display = visibilidade
	divDistancia.style.display = visibilidade
})

function obterCoordenadas(cep) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/ajax_obter_coordenadas`,
			type: 'GET',
			dataType: 'json',
			data: { cep },
			success: function (resposta) {
				if (resposta.data) {
					resolve({ latitude: resposta.data.lat, longitude: resposta.data.lon })
				} else {
					reject("Não foi possível obter as coordenadas, os prestadores não foram ordenados")
				}

			},
			error: function (e) {
				reject("Não foi possível obter as coordenadas, os prestadores não foram ordenados")
			}
		})
	})
}
function calcularDistancia(coord11, coord12, coord21, coord22) {
	return new Promise((resolve, reject) => {

		const Lat1Rad = coord11 * (Math.PI / 180);
		const Long1Rad = coord12 * (Math.PI / 180);
		const Lat2Rad = coord21 * (Math.PI / 180);
		const Long2Rad = coord22 * (Math.PI / 180);

		const raioT = 6371.0 * 1000.0;

		// Diferença das coordenadas
		const dLat = Lat2Rad - Lat1Rad;
		const dLon = Long2Rad - Long1Rad;

		// Fórmula de Haversine
		const a =
			Math.sin(dLat / 2) ** 2 +
			Math.cos(Lat1Rad) * Math.cos(Lat2Rad) * Math.sin(dLon / 2) ** 2;
		const result = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
		const distancia = (raioT * result) * 1.21

		resolve((distancia / 1000).toFixed(2));
	})
}

function reordenaPrestadores(cep, idSelectPrestador) {
	let options = idSelectPrestador.find('option')
	let optionsLatELong = []
	let latELongTemp = []
	let i = 0
	let indice = 0

	obterCoordenadas(cep).then(coord1 => {
		return new Promise((resolve, reject) => {
			function obterDistancia() {
				if (options[i].getAttribute('data-latitude') != '-') {
					if (options[i].textContent == 'Selecione um prestador') {
						i++
						obterDistancia();
					} else {
						latELongTemp.push({
							latitude: options[i].getAttribute('data-latitude'),
							longitude: options[i].getAttribute('data-longitude')
						})
						indice = latELongTemp.length - 1
						calcularDistancia(coord1.latitude, coord1.longitude, latELongTemp[indice].latitude, latELongTemp[indice].longitude).then(distancia => {
							optionsLatELong.push({
								option: options[i],
								distance: distancia,
								latitude: latELongTemp[indice].latitude,
								longitude: latELongTemp[indice].longitude
							})
							options[i].remove()
							i++
							obterDistancia()
						}).catch(error => {
							reject(error);
							document.getElementById('loading').style.display = 'none';
						})
					}
				}
				else {
					resolve(optionsLatELong)
				}
			}
			obterDistancia()
		})
	}).then(optionsOrdenar => {
		return new Promise((resolve, reject) => {
			optionsOrdenar.sort(function (a, b) {
				return a.distance - b.distance;
			});
			resolve(optionsOrdenar)

		})
	}).then(optionsOrdenadas => {
		let select2Element = idSelectPrestador;
		document.querySelector('#loading').setAttribute('data-content', 'Calculando distância e ordenando prestadores...')
		document.getElementById('loading').style.display = 'block';

		setTimeout(() => {
			for (let j = optionsOrdenadas.length - 1; j >= 0; j--) {
				let option = optionsOrdenadas[j].option;
				let newText = ''
				if (option.text.includes('>')) {
					let indiceA = option.text.lastIndexOf('>');
					newText = option.text.slice(0, (indiceA)) + ` > Aprox ${optionsOrdenadas[j].distance} km`;
				} else {
					newText = option.text + ` > Aprox. ${optionsOrdenadas[j].distance} km`;
				}

				let newOption = new Option(newText, option.value);
				newOption.setAttribute('data-latitude', optionsOrdenadas[j].latitude);
				newOption.setAttribute('data-longitude', optionsOrdenadas[j].longitude);
				select2Element.prepend(newOption);
			}

			select2Element.trigger('change.select2');

			document.querySelector('#loading').setAttribute('data-content', 'Prestadores ordenados com sucesso!')

			setTimeout(function () {
				document.getElementById('loading').style.display = 'none';
			}, 3000);
		}, 3000);


	}).catch(error => {
		document.getElementById('loading').style.display = 'none';
		alert(error);

	});
}


$("#cep-na").on("blur", function (e) {
	try {
		$("#filtrar-na").prop('checked', false);

		$("#prestador-na").empty()
		$("#prestador-na").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})

		let cep = this.value.replace(".", "").replace("-", "")
		$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function (endereco) {
			if (!("erro" in endereco)) {
				$("#estado-na").find('option:selected').attr("selected", false)
				$("#estado-na").find(`option[name=${endereco.uf}]`).attr("selected", true).change()

				cidadeNA = endereco.localidade.toUpperCase().replace(" ", "")
				$("#bairro-na").val(endereco.bairro)
				$("#rua-na").val(endereco.logradouro)
				$("#bairro-na").val(endereco.bairro)

			} else {
				cidadeNA = null
			}
		})
	} catch (exception) {

	}
})

$("#cep-na-2").on("blur", function (e) {
	try {
		$("#filtrar-na-2").prop('checked', false);

		$("#prestador-na-2").empty()
		$("#prestador-na-2").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-2").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})

		let cep = this.value.replace(".", "").replace("-", "")
		$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function (endereco) {
			if (!("erro" in endereco)) {
				$("#estado-na-2").find('option:selected').attr("selected", false)
				$("#estado-na-2").find(`option[name=${endereco.uf}]`).attr("selected", true).change()

				cidadeNA = endereco.localidade.toUpperCase().replace(" ", "")
				$("#bairro-na-2").val(endereco.bairro)
				$("#rua-na-2").val(endereco.logradouro)
				$("#bairro-na-2").val(endereco.bairro)
			} else {
				cidadeNA = null
			}
		})
	} catch (exception) {

	}
})

$("#cep-na-3").on("blur", function (e) {
	try {
		$("#filtrar-na-3").prop('checked', false);

		$("#prestador-na-3").empty()
		$("#prestador-na-3").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-3").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})

		let cep = this.value.replace(".", "").replace("-", "")
		$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function (endereco) {
			if (!("erro" in endereco)) {
				$("#estado-na-3").find('option:selected').attr("selected", false)
				$("#estado-na-3").find(`option[name=${endereco.uf}]`).attr("selected", true).change()

				cidadeNA = endereco.localidade.toUpperCase().replace(" ", "")
				$("#bairro-na-3").val(endereco.bairro)
				$("#rua-na-3").val(endereco.logradouro)
				$("#bairro-na-3").val(endereco.bairro)
			} else {
				cidadeNA = null
			}
		})
	} catch (exception) {

	}
})

$("#cep-na-4").on("blur", function (e) {
	try {
		$("#filtrar-na-4").prop('checked', false);

		$("#prestador-na-4").empty()
		$("#prestador-na-4").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-4").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})

		let cep = this.value.replace(".", "").replace("-", "")
		$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function (endereco) {
			if (!("erro" in endereco)) {
				$("#estado-na-4").find('option:selected').attr("selected", false)
				$("#estado-na-4").find(`option[name=${endereco.uf}]`).attr("selected", true).change()

				cidadeNA = endereco.localidade.toUpperCase().replace(" ", "")
				$("#bairro-na-4").val(endereco.bairro)
				$("#rua-na-4").val(endereco.logradouro)
				$("#bairro-na-4").val(endereco.bairro)
			} else {
				cidadeNA = null
			}
		})
	} catch (exception) {

	}
})

$("#cep-na-5").on("blur", function (e) {
	try {
		$("#filtrar-na-5").prop('checked', false);

		$("#prestador-na-5").empty()
		$("#prestador-na-5").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-5").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})

		let cep = this.value.replace(".", "").replace("-", "")
		$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function (endereco) {
			if (!("erro" in endereco)) {
				$("#estado-na-5").find('option:selected').attr("selected", false)
				$("#estado-na-5").find(`option[name=${endereco.uf}]`).attr("selected", true).change()

				cidadeNA = endereco.localidade.toUpperCase().replace(" ", "")
				$("#bairro-na-5").val(endereco.bairro)
				$("#rua-na-5").val(endereco.logradouro)
				$("#bairro-na-5").val(endereco.bairro)
			} else {
				cidadeNA = null
			}
		})
	} catch (exception) {

	}
})

function alterarStatusNA(idNA) {
	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_alterar_status_atividade_de_servico`,
		type: 'POST',
		dataType: 'JSON',
		data: {
			idAtividadeServico: idNA,
			statuscode: 4,
			statecode: 3,
		},
		success: function (callback) {
			if (callback.status == 1) {
				return true
			} else {
				alert("Não foi possível mover a NA para agendadas com status reservado.")
			}
		},
		error: function (error) {
			alert('Não foi possível mover a NA para agendadas com status reservado.')
		}
	})
}
$("#form-cadastro-na").submit(async function (event) {
	event.preventDefault()
	var dadosForm = getDadosForm('form-cadastro-na')

	btn = $("#btn-adicionar-na")
	htmlBtn = btn.html()

	btnCadastraNa = $("#btn-cadastro-na-01")
	htmlBtnCadastraNa = btnCadastraNa.html()

	btnClose = $("#btn-fechar-modal-na")
	htmlBtnClose = btnClose.html()

	let idAtividadeDeServico = $("#id-na").val()

	if (!idAtividadeDeServico) { //cadastro
		if (dadosForm.telefoneSolicitante.length < 14) {
			alert("É necessário digitar um telefone válido!")
			return
		} else if (!dadosForm.itemDeContrato || dadosForm.itemDeContrato === "0") {
			alert("É necessário selecionar o item de contrato!")
			return
		} else if (!dadosForm.servico || dadosForm.servico === "0") {
			alert("É necessário selecionar um serviço!")
			return
		} else if (!dadosForm.prestador || dadosForm.prestador === "0") {
			alert("É necessário selecionar um prestador!")
			return
		} else if (!dadosForm.recurso || dadosForm.recurso === "0") {
			alert("É necessário selecionar um recurso!")
			return
		} else if (!dadosForm.dataMinima) {
			alert("É necessário selecionar uma data mínima de agendamento!")
			return
		}

		if (dadosForm.localAtendimento === "2") {
			if (!dadosForm.estado || dadosForm.estado === "") {
				alert("É necessário informar o estado!")
				return
			} else if (!dadosForm.cidade || dadosForm.cidade === "") {
				alert("É necessário informar a cidade!")
				return
			} else if (!dadosForm.bairro || dadosForm.bairro === "") {
				alert("É necessário informar a bairro!")
				return
			} else if (!dadosForm.rua || dadosForm.rua === "") {
				alert("É necessário informar a rua!")
				return
			} else if (!dadosForm.numero || dadosForm.numero === "") {
				alert("É necessário informar o número!")
				return
			}
		}

		let dataAg = $("#data-minima").datetimepicker("getValue");
		dataAg = dataAg - 10800000 // 3 horas em milisegundos

		let CustomerType = retornarTipoDeCliente() === "pj" ? 1 : 2
		data = {
			statusDescription: "Aberto",
			ResumoSolicitacao: dadosForm.resumoSolicitacao,
			NomeSolicitante: dadosForm.nomeSolicitante,
			TelefoneSolicitante: dadosForm.telefoneSolicitante,
			Cliente: buscarDadosClienteAbaAtual().Id,
			Customer: buscarDadosClienteAbaAtual().Id,
			CustomerType,
			TipoServico: 1, //fixo, tipo de serviço é sempre instalação
			NomeUsuarioGestor: EMAIL_USUARIO,
			serviceName: $('#servico-na').select2('data')[0].text,
			Prestador: dadosForm.prestador,
			provider: $('#recurso-na').select2('data')[0].text,
			Recurso: dadosForm.recurso,
			ItemContrato: dadosForm.itemDeContrato,
			Servico: dadosForm.servico,
			DataMinimaAgendamento: new Date(dataAg).toISOString(),
			LocalAtendimento: parseInt(dadosForm.localAtendimento),
			Complemento: dadosForm.complemento,
			HouveTrocaModulo: false,
			HouveTrocaAntena: false,
			ModeloTipoAtivacao: dadosForm.modeloTipo,
			trackerSerialNumber: dadosForm.serialItemDeContrato,
			ValorKmConsiderado: parseFloat(dadosForm.tz_valor_km_considerado_cliente),
			TaxaVisita: parseFloat(dadosForm.taxaVisita),
			ValorTotalDeslocamento: parseFloat(dadosForm.valorTotalDeslocamento),
			DistanciaTotal: parseFloat(dadosForm.distanciaTotal),
			DistanciaBonificada: parseFloat(dadosForm.distanciaBonificada),
			DistanciaConsiderada: parseFloat(dadosForm.distanciaConsiderada)
		}

		if (dadosForm.localAtendimento === "2") {
			data.Cep = dadosForm.cep
			data.Bairro = dadosForm.bairro
			data.Rua = dadosForm.rua
			data.Numero = dadosForm.numero
		}

		let arquivo = document.querySelector('#anexo-na')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-",
					Documentbody: arquivo,
					Filename: nomeArquivo
				}
			]
		} else if (dadosForm.anotacoes) {
			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-"
				},
			]
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Salvando...")

		// Salva dados para auditoria
		auditoria.valor_novo_atividade_servico = data

		delete auditoria.valor_novo_atividade_servico?.anotacoes
		url = `${URL_PAINEL_OMNILINK}/cadastrarNA`
		// passa a ocorrência
		if (dadosForm.idOcorrencia) data.incident = { "Id": dadosForm.idOcorrencia }
		let numeroNA = ''
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'JSON',
			data,
			success: async function (callback) {
				await $.ajax({
					url: `${URL_PAINEL_OMNILINK}/get_numeroNA`,
					type: 'POST',
					dataType: 'JSON',
					data: { activityid: callback.message.na },
				}).done(function (callback2) {
					if (callback2.data !== null) {
						numeroNA = callback2.data.tz_id_agendamento;
					} else {
						numeroNA = null;
					}
				}).fail(function (error) {
				})
				// a API do CRM retorna status 200 para tudo, infelizmente é necessário validar pela mensagem
				// 08/2022
				statusR = callback.status
				try {
					callback = JSON.parse(callback?.message)
				} catch {

				}

				if (statusR === 200 && callback?.message.msg === "NA Criada com sucesso") {
					alterarStatusNA(callback.message.na)
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)
					salvar_auditoria(url, 'insert', null, auditoria.valor_novo_atividade_servico)
						.catch(error => console.error(error))

					blockDadosForm("form-cadastro-na", numeroNA, "numeroNA-1", "divNumeroNA-1")
					alert("NA cadastrada com sucesso!" + (numeroNA ? "\nNúmero da NA: " + numeroNA : ""))
				} else {
					alert(callback.message)
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao cadastrar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', true).html(htmlBtn)
				btnClose.attr('disabled', false).html(htmlBtnClose)
			}
		})
	} else { //edição

		let arquivo = document.querySelector('#anexo-na')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			let mimeType = arquivo.type
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			dadosForm.anotacoes = [
				{
					idAnotacao: $("#id-anexo").val() || false,
					noteText: dadosForm.anotacoes || "-",
					documentBody: arquivo,
					fileName: nomeArquivo,
					mimeType
				}
			]

			if (!dadosForm.idAnexo)
				dadosForm.anotacoes[0].subject = `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`
		} else {
			if (dadosForm.idAnexo) {
				dadosForm.anotacoes = [
					{
						idAnotacao: $("#id-anexo").val(),
						noteText: dadosForm.anotacoes || "-"
					}
				]
			}
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Alterando...")
		url = `${URL_PAINEL_OMNILINK}/ajax_editar_atividade_de_servico`
		$.ajax({
			url,
			type: 'POST',
			dataType: 'JSON',
			data: dadosForm,
			success: function (callback) {
				if (callback.status === 200) {
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)

					alert("NA alterada com sucesso!")
					$("#modal-na").modal("hide")
				} else {
					alert(callback?.message ?? "Ocorreu um erro ao tentar atualizar a NA.")
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao alterar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', false).html(htmlBtn)
			}
		})
	}
})

$("#form-cadastro-na-2").submit(async function (event) {
	event.preventDefault()
	var dadosForm = getDadosForm('form-cadastro-na-2')

	btn = $("#btn-adicionar-na-2")
	htmlBtn = btn.html()

	btnClose = $("#btn-fechar-modal-na-2")
	htmlBtnClose = btnClose.html()

	let idAtividadeDeServico = $("#id-na-2").val()

	if (!idAtividadeDeServico) { //cadastro
		if (dadosForm.telefoneSolicitante.length < 14) {
			alert("É necessário digitar um telefone válido!")
			return
		} else if (!dadosForm.itemDeContrato2 || dadosForm.itemDeContrato2 === "0") {
			alert("É necessário selecionar o item de contrato!")
			return
		} else if (!dadosForm.servico || dadosForm.servico === "0") {
			alert("É necessário selecionar um serviço!")
			return
		} else if (!dadosForm.prestador || dadosForm.prestador === "0") {
			alert("É necessário selecionar um prestador!")
			return
		} else if (!dadosForm.recurso || dadosForm.recurso === "0") {
			alert("É necessário selecionar um recurso!")
			return
		} else if (!dadosForm.dataMinima) {
			alert("É necessário selecionar uma data mínima de agendamento!")
			return
		}

		if (dadosForm.localAtendimento === "2") {
			if (!dadosForm.estado || dadosForm.estado === "") {
				alert("É necessário informar o estado!")
				return
			} else if (!dadosForm.cidade || dadosForm.cidade === "") {
				alert("É necessário informar a cidade!")
				return
			} else if (!dadosForm.bairro || dadosForm.bairro === "") {
				alert("É necessário informar a bairro!")
				return
			} else if (!dadosForm.rua || dadosForm.rua === "") {
				alert("É necessário informar a rua!")
				return
			} else if (!dadosForm.numero || dadosForm.numero === "") {
				alert("É necessário informar o número!")
				return
			}
		}

		let dataAg = $("#data-minima-2").datetimepicker("getValue");
		dataAg = dataAg - 10800000 // 3 horas em milisegundos

		let CustomerType = retornarTipoDeCliente() === "pj" ? 1 : 2
		data = {
			statusDescription: "Aberto",
			ResumoSolicitacao: dadosForm.resumoSolicitacao,
			NomeSolicitante: dadosForm.nomeSolicitante,
			TelefoneSolicitante: dadosForm.telefoneSolicitante,
			Cliente: buscarDadosClienteAbaAtual().Id,
			Customer: buscarDadosClienteAbaAtual().Id,
			CustomerType,
			TipoServico: 1, //fixo, tipo de serviço é sempre instalação
			NomeUsuarioGestor: EMAIL_USUARIO,
			serviceName: $('#servico-na-2').select2('data')[0].text,
			Prestador: dadosForm.prestador,
			provider: $('#recurso-na-2').select2('data')[0].text,
			Recurso: dadosForm.recurso,
			ItemContrato: dadosForm.itemDeContrato2,
			Servico: dadosForm.servico,
			DataMinimaAgendamento: new Date(dataAg).toISOString(),
			LocalAtendimento: parseInt(dadosForm.localAtendimento),
			Complemento: dadosForm.complemento,
			HouveTrocaModulo: false,
			HouveTrocaAntena: false,
			ModeloTipoAtivacao: dadosForm.modeloTipo,
			trackerSerialNumber: dadosForm.serialItemDeContrato,
			ValorKmConsiderado: parseFloat(dadosForm.valorKmConsiderado),
			TaxaVisita: parseFloat(dadosForm.taxaVisita),
			ValorTotalDeslocamento: parseFloat(dadosForm.valorTotalDeslocamento),
			DistanciaTotal: parseFloat(dadosForm.distanciaTotal),
			DistanciaBonificada: parseFloat(dadosForm.distanciaBonificada),
			DistanciaConsiderada: parseFloat(dadosForm.distanciaConsiderada)
		}

		if (dadosForm.localAtendimento === "2") {
			data.Cep = dadosForm.cep
			data.Bairro = dadosForm.bairro
			data.Rua = dadosForm.rua
			data.Numero = dadosForm.numero
		}

		let arquivo = document.querySelector('#anexo-na-2')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-",
					Documentbody: arquivo,
					Filename: nomeArquivo
				}
			]
		} else if (dadosForm.anotacoes) {
			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-"
				},
			]
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Salvando...")

		// Salva dados para auditoria
		auditoria.valor_novo_atividade_servico = data

		delete auditoria.valor_novo_atividade_servico?.anotacoes
		url = `${URL_PAINEL_OMNILINK}/cadastrarNA`
		// passa a ocorrência
		if (dadosForm.idOcorrencia) data.incident = { "Id": dadosForm.idOcorrencia }
		let numeroNA = ''
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'JSON',
			data,
			success: async function (callback) {
				await $.ajax({
					url: `${URL_PAINEL_OMNILINK}/get_numeroNA`,
					type: 'POST',
					dataType: 'JSON',
					data: { activityid: callback.message.na },
				}).done(function (callback2) {
					if (callback2.data !== null) {
						numeroNA = callback2.data.tz_id_agendamento;
					} else {
						numeroNA = null;
					}
				}).fail(function (error) {
				})
				// a API do CRM retorna status 200 para tudo, infelizmente é necessário validar pela mensagem
				// 08/2022
				statusR = callback.status
				try {
					callback = JSON.parse(callback?.message)
				} catch {

				}


				if (statusR === 200 && callback?.message.msg === "NA Criada com sucesso") {
					alterarStatusNA(callback.message.na)
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)
					salvar_auditoria(url, 'insert', null, auditoria.valor_novo_atividade_servico)
						.catch(error => console.error(error))

					blockDadosForm('form-cadastro-na-2', numeroNA, "numeroNA-2", "divNumeroNA-2")
					alert("NA cadastrada com sucesso!" + (numeroNA ? "\nNúmero da NA: " + numeroNA : ""))
				} else {
					alert(callback.message)
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao cadastrar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', true).html(htmlBtn)
				btnClose.attr('disabled', false).html(htmlBtnClose)

			}
		})
	} else { //edição

		let arquivo = document.querySelector('#anexo-na-2')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			let mimeType = arquivo.type
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			dadosForm.anotacoes = [
				{
					idAnotacao: $("#id-anexo-2").val() || false,
					noteText: dadosForm.anotacoes || "-",
					documentBody: arquivo,
					fileName: nomeArquivo,
					mimeType
				}
			]

			if (!dadosForm.idAnexo)
				dadosForm.anotacoes[0].subject = `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`
		} else {
			if (dadosForm.idAnexo) {
				dadosForm.anotacoes = [
					{
						idAnotacao: $("#id-anexo-2").val(),
						noteText: dadosForm.anotacoes || "-"
					}
				]
			}
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Alterando...")
		url = `${URL_PAINEL_OMNILINK}/ajax_editar_atividade_de_servico`
		$.ajax({
			url,
			type: 'POST',
			dataType: 'JSON',
			data: dadosForm,
			success: function (callback) {
				if (callback.status === 200) {
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)

					alert("NA alterada com sucesso!")
					$("#modal-na").modal("hide")
				} else {
					alert(callback?.message ?? "Ocorreu um erro ao tentar atualizar a NA.")
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao alterar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', false).html(htmlBtn)
			}
		})
	}
})

$("#form-cadastro-na-3").submit(async function (event) {
	event.preventDefault()
	var dadosForm = getDadosForm('form-cadastro-na-3')

	btn = $("#btn-adicionar-na-3")
	htmlBtn = btn.html()

	btnClose = $("#btn-fechar-modal-na-3")
	htmlBtnClose = btnClose.html()

	let idAtividadeDeServico = $("#id-na-3").val()

	if (!idAtividadeDeServico) { //cadastro
		if (dadosForm.telefoneSolicitante.length < 14) {
			alert("É necessário digitar um telefone válido!")
			return
		} else if (!dadosForm.itemDeContrato3 || dadosForm.itemDeContrato3 === "0") {
			alert("É necessário selecionar o item de contrato!")
			return
		} else if (!dadosForm.servico || dadosForm.servico === "0") {
			alert("É necessário selecionar um serviço!")
			return
		} else if (!dadosForm.prestador || dadosForm.prestador === "0") {
			alert("É necessário selecionar um prestador!")
			return
		} else if (!dadosForm.recurso || dadosForm.recurso === "0") {
			alert("É necessário selecionar um recurso!")
			return
		} else if (!dadosForm.dataMinima) {
			alert("É necessário selecionar uma data mínima de agendamento!")
			return
		}

		if (dadosForm.localAtendimento === "2") {
			if (!dadosForm.estado || dadosForm.estado === "") {
				alert("É necessário informar o estado!")
				return
			} else if (!dadosForm.cidade || dadosForm.cidade === "") {
				alert("É necessário informar a cidade!")
				return
			} else if (!dadosForm.bairro || dadosForm.bairro === "") {
				alert("É necessário informar a bairro!")
				return
			} else if (!dadosForm.rua || dadosForm.rua === "") {
				alert("É necessário informar a rua!")
				return
			} else if (!dadosForm.numero || dadosForm.numero === "") {
				alert("É necessário informar o número!")
				return
			}
		}

		let dataAg = $("#data-minima").datetimepicker("getValue");
		dataAg = dataAg - 10800000 // 3 horas em milisegundos

		let CustomerType = retornarTipoDeCliente() === "pj" ? 1 : 2
		data = {
			statusDescription: "Aberto",
			ResumoSolicitacao: dadosForm.resumoSolicitacao,
			NomeSolicitante: dadosForm.nomeSolicitante,
			TelefoneSolicitante: dadosForm.telefoneSolicitante,
			Cliente: buscarDadosClienteAbaAtual().Id,
			Customer: buscarDadosClienteAbaAtual().Id,
			CustomerType,
			TipoServico: 1, //fixo, tipo de serviço é sempre instalação
			NomeUsuarioGestor: EMAIL_USUARIO,
			serviceName: $('#servico-na-3').select2('data')[0].text,
			Prestador: dadosForm.prestador,
			provider: $('#recurso-na-3').select2('data')[0].text,
			Recurso: dadosForm.recurso,
			ItemContrato: dadosForm.itemDeContrato3,
			Servico: dadosForm.servico,
			DataMinimaAgendamento: new Date(dataAg).toISOString(),
			LocalAtendimento: parseInt(dadosForm.localAtendimento),
			Complemento: dadosForm.complemento,
			HouveTrocaModulo: false,
			HouveTrocaAntena: false,
			ModeloTipoAtivacao: dadosForm.modeloTipo,
			trackerSerialNumber: dadosForm.serialItemDeContrato,
			ValorKmConsiderado: parseFloat(dadosForm.valorKmConsiderado),
			TaxaVisita: parseFloat(dadosForm.taxaVisita),
			ValorTotalDeslocamento: parseFloat(dadosForm.valorTotalDeslocamento),
			DistanciaTotal: parseFloat(dadosForm.distanciaTotal),
			DistanciaBonificada: parseFloat(dadosForm.distanciaBonificada),
			DistanciaConsiderada: parseFloat(dadosForm.distanciaConsiderada)
		}

		if (dadosForm.localAtendimento === "2") {
			data.Cep = dadosForm.cep
			data.Bairro = dadosForm.bairro
			data.Rua = dadosForm.rua
			data.Numero = dadosForm.numero
		}

		let arquivo = document.querySelector('#anexo-na-3')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-",
					Documentbody: arquivo,
					Filename: nomeArquivo
				}
			]
		} else if (dadosForm.anotacoes) {
			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-"
				},
			]
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Salvando...")

		// Salva dados para auditoria
		auditoria.valor_novo_atividade_servico = data

		delete auditoria.valor_novo_atividade_servico?.anotacoes
		url = `${URL_PAINEL_OMNILINK}/cadastrarNA`
		// passa a ocorrência
		if (dadosForm.idOcorrencia) data.incident = { "Id": dadosForm.idOcorrencia }
		let numeroNA = ''
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'JSON',
			data,
			success: async function (callback) {
				await $.ajax({
					url: `${URL_PAINEL_OMNILINK}/get_numeroNA`,
					type: 'POST',
					dataType: 'JSON',
					data: { activityid: callback.message.na },
				}).done(function (callback2) {
					if (callback2.data !== null) {
						numeroNA = callback2.data.tz_id_agendamento;
					} else {
						numeroNA = null;
					}
				}).fail(function (error) {
				});
				// a API do CRM retorna status 200 para tudo, infelizmente é necessário validar pela mensagem
				// 08/2022
				statusR = callback.status
				try {
					callback = JSON.parse(callback?.message)
				} catch {

				}


				if (statusR === 200 && callback?.message.msg === "NA Criada com sucesso") {
					alterarStatusNA(callback.message.na)
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)
					salvar_auditoria(url, 'insert', null, auditoria.valor_novo_atividade_servico)
						.catch(error => console.error(error))

					blockDadosForm('form-cadastro-na-3', numeroNA, "numeroNA-3", "divNumeroNA-3")
					alert("NA cadastrada com sucesso!" + (numeroNA ? "\nNúmero da NA: " + numeroNA : ""))
				} else {
					alert(callback.message)
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao cadastrar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', true).html(htmlBtn)
				btnClose.attr('disabled', false).html(htmlBtnClose)
			}
		})
	} else { //edição

		let arquivo = document.querySelector('#anexo-na-3')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			let mimeType = arquivo.type
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			dadosForm.anotacoes = [
				{
					idAnotacao: $("#id-anexo-3").val() || false,
					noteText: dadosForm.anotacoes || "-",
					documentBody: arquivo,
					fileName: nomeArquivo,
					mimeType
				}
			]

			if (!dadosForm.idAnexo)
				dadosForm.anotacoes[0].subject = `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`
		} else {
			if (dadosForm.idAnexo) {
				dadosForm.anotacoes = [
					{
						idAnotacao: $("#id-anexo-3").val(),
						noteText: dadosForm.anotacoes || "-"
					}
				]
			}
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Alterando...")
		url = `${URL_PAINEL_OMNILINK}/ajax_editar_atividade_de_servico`
		$.ajax({
			url,
			type: 'POST',
			dataType: 'JSON',
			data: dadosForm,
			success: function (callback) {
				if (callback.status === 200) {
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)

					alert("NA alterada com sucesso!")
					$("#modal-na").modal("hide")
				} else {
					alert(callback?.message ?? "Ocorreu um erro ao tentar atualizar a NA.")
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao alterar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', false).html(htmlBtn)
			}
		})
	}
})

$("#form-cadastro-na-4").submit(async function (event) {
	event.preventDefault()
	var dadosForm = getDadosForm('form-cadastro-na-4')

	btn = $("#btn-adicionar-na-4")
	htmlBtn = btn.html()


	btnClose = $("#btn-fechar-modal-na-4")
	htmlBtnClose = btnClose.html()

	let idAtividadeDeServico = $("#id-na-4").val()

	if (!idAtividadeDeServico) { //cadastro
		if (dadosForm.telefoneSolicitante.length < 14) {
			alert("É necessário digitar um telefone válido!")
			return
		} else if (!dadosForm.itemDeContrato4 || dadosForm.itemDeContrato4 === "0") {
			alert("É necessário selecionar o item de contrato!")
			return
		} else if (!dadosForm.servico || dadosForm.servico === "0") {
			alert("É necessário selecionar um serviço!")
			return
		} else if (!dadosForm.prestador || dadosForm.prestador === "0") {
			alert("É necessário selecionar um prestador!")
			return
		} else if (!dadosForm.recurso || dadosForm.recurso === "0") {
			alert("É necessário selecionar um recurso!")
			return
		} else if (!dadosForm.dataMinima) {
			alert("É necessário selecionar uma data mínima de agendamento!")
			return
		}

		if (dadosForm.localAtendimento === "2") {
			if (!dadosForm.estado || dadosForm.estado === "") {
				alert("É necessário informar o estado!")
				return
			} else if (!dadosForm.cidade || dadosForm.cidade === "") {
				alert("É necessário informar a cidade!")
				return
			} else if (!dadosForm.bairro || dadosForm.bairro === "") {
				alert("É necessário informar a bairro!")
				return
			} else if (!dadosForm.rua || dadosForm.rua === "") {
				alert("É necessário informar a rua!")
				return
			} else if (!dadosForm.numero || dadosForm.numero === "") {
				alert("É necessário informar o número!")
				return
			}
		}

		let dataAg = $("#data-minima").datetimepicker("getValue");
		dataAg = dataAg - 10800000 // 3 horas em milisegundos

		let CustomerType = retornarTipoDeCliente() === "pj" ? 1 : 2
		data = {
			statusDescription: "Aberto",
			ResumoSolicitacao: dadosForm.resumoSolicitacao,
			NomeSolicitante: dadosForm.nomeSolicitante,
			TelefoneSolicitante: dadosForm.telefoneSolicitante,
			Cliente: buscarDadosClienteAbaAtual().Id,
			Customer: buscarDadosClienteAbaAtual().Id,
			CustomerType,
			TipoServico: 1, //fixo, tipo de serviço é sempre instalação
			NomeUsuarioGestor: EMAIL_USUARIO,
			serviceName: $('#servico-na-4').select2('data')[0].text,
			Prestador: dadosForm.prestador,
			provider: $('#recurso-na-4').select2('data')[0].text,
			Recurso: dadosForm.recurso,
			ItemContrato: dadosForm.itemDeContrato4,
			Servico: dadosForm.servico,
			DataMinimaAgendamento: new Date(dataAg).toISOString(),
			LocalAtendimento: parseInt(dadosForm.localAtendimento),
			Complemento: dadosForm.complemento,
			HouveTrocaModulo: false,
			HouveTrocaAntena: false,
			ModeloTipoAtivacao: dadosForm.modeloTipo,
			trackerSerialNumber: dadosForm.serialItemDeContrato,
			ValorKmConsiderado: parseFloat(dadosForm.valorKmConsiderado),
			TaxaVisita: parseFloat(dadosForm.taxaVisita),
			ValorTotalDeslocamento: parseFloat(dadosForm.valorTotalDeslocamento),
			DistanciaTotal: parseFloat(dadosForm.distanciaTotal),
			DistanciaBonificada: parseFloat(dadosForm.distanciaBonificada),
			DistanciaConsiderada: parseFloat(dadosForm.distanciaConsiderada)
		}

		if (dadosForm.localAtendimento === "2") {
			data.Cep = dadosForm.cep
			data.Bairro = dadosForm.bairro
			data.Rua = dadosForm.rua
			data.Numero = dadosForm.numero
		}

		let arquivo = document.querySelector('#anexo-na-4')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-",
					Documentbody: arquivo,
					Filename: nomeArquivo
				}
			]
		} else if (dadosForm.anotacoes) {
			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-"
				},
			]
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Salvando...")

		// Salva dados para auditoria
		auditoria.valor_novo_atividade_servico = data

		delete auditoria.valor_novo_atividade_servico?.anotacoes
		url = `${URL_PAINEL_OMNILINK}/cadastrarNA`
		// passa a ocorrência
		if (dadosForm.idOcorrencia) data.incident = { "Id": dadosForm.idOcorrencia }
		let numeroNA = ''
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'JSON',
			data,
			success: async function (callback) {
				await $.ajax({
					url: `${URL_PAINEL_OMNILINK}/get_numeroNA`,
					type: 'POST',
					dataType: 'JSON',
					data: { activityid: callback.message.na },
				}).done(function (callback2) {
					if (callback2.data !== null) {
						numeroNA = callback2.data.tz_id_agendamento;
					} else {
						numeroNA = null;
					}
				}).fail(function (error) {
				});
				// a API do CRM retorna status 200 para tudo, infelizmente é necessário validar pela mensagem
				// 08/2022
				statusR = callback.status
				try {
					callback = JSON.parse(callback?.message)
				} catch {

				}


				if (statusR === 200 && callback?.message.msg === "NA Criada com sucesso") {
					alterarStatusNA(callback.message.na)
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)
					salvar_auditoria(url, 'insert', null, auditoria.valor_novo_atividade_servico)
						.catch(error => console.error(error))

					blockDadosForm('form-cadastro-na-4', numeroNA, "numeroNA-4", "divNumeroNA-4")
					alert("NA cadastrada com sucesso!" + (numeroNA ? "\nNúmero da NA: " + numeroNA : ""))
				} else {
					alert(callback.message)
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao cadastrar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', true).html(htmlBtn)
				btnClose.attr('disabled', false).html(htmlBtnClose)
			}
		})
	} else { //edição

		let arquivo = document.querySelector('#anexo-na-4')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			let mimeType = arquivo.type
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			dadosForm.anotacoes = [
				{
					idAnotacao: $("#id-anexo-4").val() || false,
					noteText: dadosForm.anotacoes || "-",
					documentBody: arquivo,
					fileName: nomeArquivo,
					mimeType
				}
			]

			if (!dadosForm.idAnexo)
				dadosForm.anotacoes[0].subject = `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`
		} else {
			if (dadosForm.idAnexo) {
				dadosForm.anotacoes = [
					{
						idAnotacao: $("#id-anexo-4").val(),
						noteText: dadosForm.anotacoes || "-"
					}
				]
			}
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Alterando...")
		url = `${URL_PAINEL_OMNILINK}/ajax_editar_atividade_de_servico`
		$.ajax({
			url,
			type: 'POST',
			dataType: 'JSON',
			data: dadosForm,
			success: function (callback) {
				if (callback.status === 200) {
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)

					alert("NA alterada com sucesso!")
					$("#modal-na").modal("hide")
				} else {
					alert(callback?.message ?? "Ocorreu um erro ao tentar atualizar a NA.")
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao alterar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', false).html(htmlBtn)
			}
		})
	}
})
$("#form-cadastro-na-5").submit(async function (event) {
	event.preventDefault()
	var dadosForm = getDadosForm('form-cadastro-na-5')

	btn = $("#btn-adicionar-na-5")
	htmlBtn = btn.html()

	btnClose = $("#btn-fechar-modal-na-5")
	htmlBtnClose = btnClose.html()

	let idAtividadeDeServico = $("#id-na-5").val()

	if (!idAtividadeDeServico) { //cadastro
		if (dadosForm.telefoneSolicitante.length < 14) {
			alert("É necessário digitar um telefone válido!")
			return
		} else if (!dadosForm.itemDeContrato5 || dadosForm.itemDeContrato5 === "0") {
			alert("É necessário selecionar o item de contrato!")
			return
		} else if (!dadosForm.servico || dadosForm.servico === "0") {
			alert("É necessário selecionar um serviço!")
			return
		} else if (!dadosForm.prestador || dadosForm.prestador === "0") {
			alert("É necessário selecionar um prestador!")
			return
		} else if (!dadosForm.recurso || dadosForm.recurso === "0") {
			alert("É necessário selecionar um recurso!")
			return
		} else if (!dadosForm.dataMinima) {
			alert("É necessário selecionar uma data mínima de agendamento!")
			return
		}

		if (dadosForm.localAtendimento === "2") {
			if (!dadosForm.estado || dadosForm.estado === "") {
				alert("É necessário informar o estado!")
				return
			} else if (!dadosForm.cidade || dadosForm.cidade === "") {
				alert("É necessário informar a cidade!")
				return
			} else if (!dadosForm.bairro || dadosForm.bairro === "") {
				alert("É necessário informar a bairro!")
				return
			} else if (!dadosForm.rua || dadosForm.rua === "") {
				alert("É necessário informar a rua!")
				return
			} else if (!dadosForm.numero || dadosForm.numero === "") {
				alert("É necessário informar o número!")
				return
			}
		}

		let dataAg = $("#data-minima").datetimepicker("getValue");
		dataAg = dataAg - 10800000 // 3 horas em milisegundos
		let CustomerType = retornarTipoDeCliente() === "pj" ? 1 : 2
		data = {
			statusDescription: "Aberto",
			ResumoSolicitacao: dadosForm.resumoSolicitacao,
			NomeSolicitante: dadosForm.nomeSolicitante,
			TelefoneSolicitante: dadosForm.telefoneSolicitante,
			Cliente: buscarDadosClienteAbaAtual().Id,
			Customer: buscarDadosClienteAbaAtual().Id,
			CustomerType,
			TipoServico: 1, //fixo, tipo de serviço é sempre instalação
			NomeUsuarioGestor: EMAIL_USUARIO,
			serviceName: $('#servico-na-5').select2('data')[0].text,
			Prestador: dadosForm.prestador,
			provider: $('#recurso-na-5').select2('data')[0].text,
			Recurso: dadosForm.recurso,
			ItemContrato: dadosForm.itemDeContrato5,
			Servico: dadosForm.servico,
			DataMinimaAgendamento: new Date(dataAg).toISOString(),
			LocalAtendimento: parseInt(dadosForm.localAtendimento),
			Complemento: dadosForm.complemento,
			HouveTrocaModulo: false,
			HouveTrocaAntena: false,
			ModeloTipoAtivacao: dadosForm.modeloTipo,
			trackerSerialNumber: dadosForm.serialItemDeContrato,
			ValorKmConsiderado: parseFloat(dadosForm.valorKmConsiderado),
			TaxaVisita: parseFloat(dadosForm.taxaVisita),
			ValorTotalDeslocamento: parseFloat(dadosForm.valorTotalDeslocamento),
			DistanciaTotal: parseFloat(dadosForm.distanciaTotal),
			DistanciaBonificada: parseFloat(dadosForm.distanciaBonificada),
			DistanciaConsiderada: parseFloat(dadosForm.distanciaConsiderada)
		}

		if (dadosForm.localAtendimento === "2") {
			data.Cep = dadosForm.cep
			data.Bairro = dadosForm.bairro
			data.Rua = dadosForm.rua
			data.Numero = dadosForm.numero
		}

		let arquivo = document.querySelector('#anexo-na-5')?.files[0]

		//pegar kms

		if (arquivo) {
			let nomeArquivo = arquivo.name
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-",
					Documentbody: arquivo,
					Filename: nomeArquivo
				}
			]
		} else if (dadosForm.anotacoes) {
			data.Anotacoes = [
				{
					Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
					Notetext: dadosForm.anotacoes || "-"
				},
			]
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Salvando...")

		// Salva dados para auditoria
		auditoria.valor_novo_atividade_servico = data

		delete auditoria.valor_novo_atividade_servico?.anotacoes
		url = `${URL_PAINEL_OMNILINK}/cadastrarNA`
		// passa a ocorrência
		if (dadosForm.idOcorrencia) data.incident = { "Id": dadosForm.idOcorrencia }
		let numeroNA = ''
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'JSON',
			data,
			success: async function (callback) {
				await $.ajax({
					url: `${URL_PAINEL_OMNILINK}/get_numeroNA`,
					type: 'POST',
					dataType: 'JSON',
					data: { activityid: callback.message.na },
				}).done(function (callback2) {
					if (callback2.data !== null) {
						numeroNA = callback2.data.tz_id_agendamento;
					} else {
						numeroNA = null;
					}
				}).fail(function (error) {
				});
				// a API do CRM retorna status 200 para tudo, infelizmente é necessário validar pela mensagem
				// 08/2022
				statusR = callback.status
				try {
					callback = JSON.parse(callback?.message)
				} catch {

				}


				if (statusR === 200 && callback?.message.msg === "NA Criada com sucesso") {
					alterarStatusNA(callback.message.na)
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)
					salvar_auditoria(url, 'insert', null, auditoria.valor_novo_atividade_servico)
						.catch(error => console.error(error))

					blockDadosForm('form-cadastro-na-5', numeroNA, "numeroNA-5", "divNumeroNA-5")
					alert("NA cadastrada com sucesso!" + (numeroNA ? "\nNúmero da NA: " + numeroNA : ""))
				} else {
					alert(callback.message)
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao cadastrar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', true).html(htmlBtn)
				btnClose.attr('disabled', false).html(htmlBtnClose)
			}
		})
	} else { //edição

		let arquivo = document.querySelector('#anexo-na-5')?.files[0]

		if (arquivo) {
			let nomeArquivo = arquivo.name
			let mimeType = arquivo.type
			arquivo = await toBase64(arquivo)
			arquivo = arquivo.toString().replace(/^data:(.*,)?/, '')

			dadosForm.anotacoes = [
				{
					idAnotacao: $("#id-anexo-5").val() || false,
					noteText: dadosForm.anotacoes || "-",
					documentBody: arquivo,
					fileName: nomeArquivo,
					mimeType
				}
			]

			if (!dadosForm.idAnexo)
				dadosForm.anotacoes[0].subject = `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`
		} else {
			if (dadosForm.idAnexo) {
				dadosForm.anotacoes = [
					{
						idAnotacao: $("#id-anexo-5").val(),
						noteText: dadosForm.anotacoes || "-"
					}
				]
			}
		}

		btn.attr('disabled', true).html(ICONS.spinner + " Alterando...")
		url = `${URL_PAINEL_OMNILINK}/ajax_editar_atividade_de_servico`
		$.ajax({
			url,
			type: 'POST',
			dataType: 'JSON',
			data: dadosForm,
			success: function (callback) {
				if (callback.status === 200) {
					carregarAtividadesDeServico(buscarDadosClienteAbaAtual().Id)

					alert("NA alterada com sucesso!")
					$("#modal-na").modal("hide")
				} else {
					alert(callback?.message ?? "Ocorreu um erro ao tentar atualizar a NA.")
				}
			},
			error: function (error) {
				alert('Ocorreu um erro ao alterar a NA, tente novamente mais tarde.')
			},
			complete: function () {
				btn.attr('disabled', false).html(htmlBtn)
			}
		})
	}
})

/**
 * @returns {Promise}
 */
async function buscarDadosSelectNa(path, retornoErro, tipoRequisicao, dadosPost = null) {
	if (!path) return null
	return new Promise((resolve, reject) => {
		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/${path}`,
			type: tipoRequisicao,
			data: dadosPost,
			success: function (callback) {
				callback = JSON.parse(callback)
				if (callback?.code === 200 || callback?.status === 200) {
					if (callback?.data)
						resolve(callback.data)
					else
						resolve(callback)
				} else {
					reject(undefined)
				}
			},
			error: function (error) {
				alert(retornoErro ?? "Ocorreu um erro ao buscar dados, tente novamente.")
				reject(error)
			}
		})
	})
}

/**
 * Retorna os contratos do cliente
 * @returns {Promise}
 */
async function buscarItensDeContrato(data) {
	path = "buscarItensDeContrato"
	erro = "Ocorreu um problema ao buscar os itens de contrato, " +
		"a base de dados pode estar apresentando instabilidade."
	return await buscarDadosSelectNa(path, erro, "POST", data)
}

/**
 * Retorna os serviços
 * @returns {Promise}
 */
async function buscarServicos(botao) {
	path = "buscarServicosDoContrato"
	erro = "Ocorreu um problema ao buscar os serviços, " +
		"a base de dados pode estar apresentando instabilidade."
	data = {
		"Id_item_contrato_venda": botao.val(),
		"tipo_servico": "1"
	}
	return await buscarDadosSelectNa(path, erro, "POST", data)
}

async function buscarSeriaisNA(select) {
	if (!select) {
		path = "buscarSeriaisNA"
		error = "Ocorreu um problema ao buscar os seriais, " +
			"a base de dados pode estar apresentando instabilidade."
		data = {
			"id_cliente": buscarDadosClienteAbaAtual().Id,
			"Id_item_contrato_venda": null
		}

		return await buscarDadosSelectNa(path, error, "POST", data);
	} else {
		path = "buscarSeriaisNA"
		error = "Ocorreu um problema ao buscar os seriais, " +
			"a base de dados pode estar apresentando instabilidade."
		data = {
			"id_cliente": buscarDadosClienteAbaAtual().Id,
			"Id_item_contrato_venda": select.val()
		}

		return await buscarDadosSelectNa(path, error, "POST", data);
	}
}

/**
 * Retorna os prestadores do cliente
 * @returns {Promise}
 */
async function buscarPrestadoresNA() {
	path = "buscarPrestadoresNA"
	erro = "Ocorreu um problema ao buscar os prestadores, " +
		"a base de dados pode estar apresentando instabilidade."
	return await buscarDadosSelectNa(path, erro, "POST")
}

async function buscarPrestadoresNALatLong() {
	path = "prestadoresPorLatituteLongitude"
	erro = "Ocorreu um problema ao buscar os prestadores, " +
		"a base de dados pode estar apresentando instabilidade."
	return await buscarDadosSelectNa(path, erro, "POST")
}

/**
 * Retorna os recursos do cliente
 * @returns {Promise}
 */
async function buscarRecursosNA(idPrestador = null) {
	if (idPrestador) {
		path = "buscarRecursosNA"
		erro = "Ocorreu um problema ao buscar os recursos, " +
			"a base de dados pode estar apresentando instabilidade."
		return await buscarDadosSelectNa(path, erro, "POST", { 'idPrestador': idPrestador })
	} else {
		path = "buscarRecursosNA"
		erro = "Ocorreu um problema ao buscar os recursos, " +
			"a base de dados pode estar apresentando instabilidade."
		return await buscarDadosSelectNa(path, erro, "POST")
	}
}

$("#a-modal-na-1").click(function (e) {
	e.preventDefault();

	$("#a-modal-na-1").attr('style', "color: #06a9f6 !important");
	$("#a-modal-na-2").attr('style', "color: #807f7f !important");
	$("#a-modal-na-3").attr('style', "color: #807f7f !important");
	$("#a-modal-na-4").attr('style', "color: #807f7f !important");
	$("#a-modal-na-5").attr('style', "color: #807f7f !important");

	$("#modal-na-1").show();
	$("#modal-na-2").hide();
	$("#modal-na-3").hide();
	$("#modal-na-4").hide();
	$("#modal-na-5").hide();
})

$("#a-modal-na-2").click(function (e) {
	e.preventDefault();

	$("#a-modal-na-2").attr('style', "color: #06a9f6 !important");
	$("#a-modal-na-1").attr('style', "color: #807f7f !important");
	$("#a-modal-na-3").attr('style', "color: #807f7f !important");
	$("#a-modal-na-4").attr('style', "color: #807f7f !important");
	$("#a-modal-na-5").attr('style', "color: #807f7f !important");

	$("#modal-na-1").hide();
	$("#modal-na-2").show();
	$("#modal-na-3").hide();
	$("#modal-na-4").hide();
	$("#modal-na-5").hide();
})

$("#a-modal-na-3").click(function (e) {
	e.preventDefault();

	$("#a-modal-na-3").attr('style', "color: #06a9f6 !important");
	$("#a-modal-na-2").attr('style', "color: #807f7f !important");
	$("#a-modal-na-1").attr('style', "color: #807f7f !important");
	$("#a-modal-na-4").attr('style', "color: #807f7f !important");
	$("#a-modal-na-5").attr('style', "color: #807f7f !important");

	$("#modal-na-1").hide();
	$("#modal-na-2").hide();
	$("#modal-na-3").show();
	$("#modal-na-4").hide();
	$("#modal-na-5").hide();
})

$("#a-modal-na-4").click(function (e) {
	e.preventDefault();

	$("#a-modal-na-4").attr('style', "color: #06a9f6 !important");
	$("#a-modal-na-2").attr('style', "color: #807f7f !important");
	$("#a-modal-na-3").attr('style', "color: #807f7f !important");
	$("#a-modal-na-1").attr('style', "color: #807f7f !important");
	$("#a-modal-na-5").attr('style', "color: #807f7f !important");

	$("#modal-na-1").hide();
	$("#modal-na-2").hide();
	$("#modal-na-3").hide();
	$("#modal-na-4").show();
	$("#modal-na-5").hide();
})

$("#a-modal-na-5").click(function (e) {
	e.preventDefault();

	$("#a-modal-na-5").attr('style', "color: #06a9f6 !important");
	$("#a-modal-na-2").attr('style', "color: #807f7f !important");
	$("#a-modal-na-3").attr('style', "color: #807f7f !important");
	$("#a-modal-na-4").attr('style', "color: #807f7f !important");
	$("#a-modal-na-1").attr('style', "color: #807f7f !important");

	$("#modal-na-1").hide();
	$("#modal-na-2").hide();
	$("#modal-na-3").hide();
	$("#modal-na-4").hide();
	$("#modal-na-5").show();
})


async function povoarSeriaisNA(botao) {
	let seriais = await buscarSeriaisNA(botao)

	if (botao) {
		let select = $("#serial-" + botao.attr("id")).selectize();

		select[0].selectize.destroy()
		$("#serial-" + botao.attr("id")).selectize({
			delimiter: ';',
			labelField: "numeroSerie",
			valueField: "id",
			searchField: "numeroSerie",
			options: seriais,
			render: {
				option: function (data) {
					return `<div class='option' id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</div>`;
				}
			},
			onChange: function (value) {
				opcao = $(this)[0].options[value]
				$("#" + value).attr('style', "padding-top: 5px !important")

				if (opcao && opcao?.id !== null) {
					if (botao.attr("id").slice(-1) == 'a') {
						if (!veioDoItemContrato) {
							$("#" + botao.attr("id")).val(opcao?.itemContrato)
							$("#" + botao.attr("id")).trigger("change")
							veioDoSerial = true
						} else {
							veioDoItemContrato = false
						}
					}

					if (botao.attr("id").slice(-1) == '2') {
						if (!veioDoItemContrato2) {
							$("#" + botao.attr("id")).val(opcao?.itemContrato)
							$("#" + botao.attr("id")).trigger("change")
							veioDoSerial2 = true
						} else {
							veioDoItemContrato2 = false
						}
					}

					if (botao.attr("id").slice(-1) == '3') {
						if (!veioDoItemContrato3) {
							$("#" + botao.attr("id")).val(opcao?.itemContrato)
							$("#" + botao.attr("id")).trigger("change")
							veioDoSerial3 = true
						} else {
							veioDoItemContrato3 = false
						}
					}

					if (botao.attr("id").slice(-1) == '4') {
						if (!veioDoItemContrato4) {
							$("#" + botao.attr("id")).val(opcao?.itemContrato)
							$("#" + botao.attr("id")).trigger("change")
							veioDoSerial4 = true
						} else {
							veioDoItemContrato4 = false
						}
					}

					if (botao.attr("id").slice(-1) == '5') {
						if (!veioDoItemContrato5) {
							$("#" + botao.attr("id")).val(opcao?.itemContrato)
							$("#" + botao.attr("id")).trigger("change")
							veioDoSerial5 = true
						} else {
							veioDoItemContrato5 = false
						}
					}
				}
			}
		});
		select[0].selectize.refreshOptions()
		return
	}

	if (seriais?.length) {
		if (seriais?.length > 1) {

			$("#serial-item-contrato-na").selectize({
				delimiter: ';',
				labelField: "numeroSerie",
				valueField: "id",
				searchField: "numeroSerie",
				options: seriais,
				render: {
					option: function (data) {
						return `<div class='option' id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</div>`;
					},
					item: function (data) {
						return `<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</option>`;
					}
				},
				onChange: function (value) {
					opcao = $(this)[0].options[value]

					if (opcao && opcao?.id !== null) {
						$("#" + value).attr('style', "padding-top: 5px !important")
						if (!veioDoItemContrato) {
							$("#item-contrato-na").val(opcao?.itemContrato)
							$("#item-contrato-na").trigger("change")
							veioDoSerial = true
						} else {
							veioDoItemContrato = false
						}
					}
				}
			});

			$("#serial-item-contrato-na-2").selectize({
				delimiter: ';',
				labelField: "numeroSerie",
				valueField: "id",
				searchField: "numeroSerie",
				options: seriais,
				render: {
					option: function (data) {
						return `<div class='option' id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</div>`;
					},
					item: function (data) {
						return `<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</option>`;
					}
				},
				onChange: function (value) {
					opcao = $(this)[0].options[value]

					if (opcao && opcao?.id !== null) {
						$("#" + value).attr('style', "padding-top: 5px !important")
						if (!veioDoItemContrato2) {
							$("#item-contrato-na-2").val(opcao?.itemContrato)
							$("#item-contrato-na-2").trigger("change")
							veioDoSerial2 = true
						} else {
							veioDoItemContrato2 = false
						}
					}
				}
			});

			$("#serial-item-contrato-na-3").selectize({
				delimiter: ';',
				labelField: "numeroSerie",
				valueField: "id",
				searchField: "numeroSerie",
				options: seriais,
				render: {
					option: function (data) {
						return `<div class='option' id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</div>`;
					},
					item: function (data) {
						return `<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</option>`;
					}
				},
				onChange: function (value) {
					opcao = $(this)[0].options[value]

					if (opcao && opcao?.id !== null) {
						$("#" + value).attr('style', "padding-top: 5px !important")
						if (!veioDoItemContrato3) {
							$("#item-contrato-na-3").val(opcao?.itemContrato)
							$("#item-contrato-na-3").trigger("change")
							veioDoSerial3 = true
						} else {
							veioDoItemContrato3 = false
						}
					}
				}
			});

			$("#serial-item-contrato-na-4").selectize({
				delimiter: ';',
				labelField: "numeroSerie",
				valueField: "id",
				searchField: "numeroSerie",
				options: seriais,
				render: {
					option: function (data) {
						return `<div class='option' id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</div>`;
					},
					item: function (data) {
						return `<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</option>`;
					}
				},
				onChange: function (value) {
					opcao = $(this)[0].options[value]

					if (opcao && opcao?.id !== null) {
						if (!veioDoItemContrato4) {
							$("#" + value).attr('style', "padding-top: 5px !important")
							$("#item-contrato-na-4").val(opcao?.itemContrato)
							$("#item-contrato-na-4").trigger("change")
							veioDoSerial4 = true
						} else {
							veioDoItemContrato4 = false
						}
					}
				}
			});

			$("#serial-item-contrato-na-5").selectize({
				delimiter: ';',
				labelField: "numeroSerie",
				valueField: "id",
				searchField: "numeroSerie",
				options: seriais,
				render: {
					option: function (data) {
						return `<div class='option' id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</div>`;
					},
					item: function (data) {
						return `<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}" >${data.numeroSerie}</option>`;
					}
				},
				onChange: function (value) {
					opcao = $(this)[0].options[value]

					if (opcao && opcao?.id !== null) {
						if (!veioDoItemContrato5) {
							$("#" + value).attr('style', "padding-top: 5px !important")
							$("#item-contrato-na-5").val(opcao?.itemContrato)
							$("#item-contrato-na-5").trigger("change")
							veioDoSerial5 = true
						} else {
							veioDoItemContrato5 = false
						}
					}
				}
			});

		} else if (seriais?.length == 1) {
			$("#serial-item-contrato-na").append(`<option id="${seriais[0].id}" data-value="${seriais[0].itemContrato}" value="${seriais[0].id}">${seriais[0].numeroSerie}</option>`)
			$("#serial-item-contrato-na").val(seriais[0].id)
			$("#serial-item-contrato-na").trigger("change")

			$("#serial-item-contrato-na-2").append(`<option id="${seriais[0].id}" data-value="${seriais[0].itemContrato}" value="${seriais[0].id}">${seriais[0].numeroSerie}</option>`)
			$("#serial-item-contrato-na-2").val(seriais[0].id)
			$("#serial-item-contrato-na-2").trigger("change")

			$("#serial-item-contrato-na-3").append(`<option id="${seriais[0].id}" data-value="${seriais[0].itemContrato}" value="${seriais[0].id}">${seriais[0].numeroSerie}</option>`)
			$("#serial-item-contrato-na-3").val(seriais[0].id)
			$("#serial-item-contrato-na-3").trigger("change")

			$("#serial-item-contrato-na-4").append(`<option id="${seriais[0].id}" data-value="${seriais[0].itemContrato}" value="${seriais[0].id}">${seriais[0].numeroSerie}</option>`)
			$("#serial-item-contrato-na-4").val(seriais[0].id)
			$("#serial-item-contrato-na-4").trigger("change")

			$("#serial-item-contrato-na-5").append(`<option id="${seriais[0].id}" data-value="${seriais[0].itemContrato}" value="${seriais[0].id}">${seriais[0].numeroSerie}</option>`)
			$("#serial-item-contrato-na-5").val(seriais[0].id)
			$("#serial-item-contrato-na-5").trigger("change")
		}
	} else {
		if (botao.attr('id') == "item-contrato-na") {
			$("#serial-item-contrato-na").find('option').get(0).remove()
			$("#serial-item-contrato-na").prepend(`<option value="0">Nenhum serial encontrado</option>`)
			alert("Nenhum serial encontrado para este item de contrato.")
		} else if (botao.attr('id') == "item-contrato-na-2") {
			$("#serial-item-contrato-na-2").find('option').get(0).remove()
			$("#serial-item-contrato-na-2").prepend(`<option value="0">Nenhum serial encontrado</option>`)
			alert("Nenhum serial encontrado para este item de contrato.")
		} else if (botao.attr('id') == "item-contrato-na-3") {
			$("#serial-item-contrato-na-3").find('option').get(0).remove()
			$("#serial-item-contrato-na-3").prepend(`<option value="0">Nenhum serial encontrado</option>`)
			alert("Nenhum serial encontrado para este item de contrato.")
		} else if (botao.attr('id') == "item-contrato-na-4") {
			$("#serial-item-contrato-na-4").find('option').get(0).remove()
			$("#serial-item-contrato-na-4").prepend(`<option value="0">Nenhum serial encontrado</option>`)
			alert("Nenhum serial encontrado para este item de contrato.")
		} else if (botao.attr('id') == "item-contrato-na-5") {
			$("#serial-item-contrato-na-5").find('option').get(0).remove()
			$("#serial-item-contrato-na-5").prepend(`<option value="0">Nenhum serial encontrado</option>`)
			alert("Nenhum serial encontrado para este item de contrato.")
		}
	}
}

async function povoarServicosNA(botao) {
	if (botao.attr('id') == "item-contrato-na") {
		$("#servico-na").attr('disabled', true)
		$("#servico-na").empty()
		$("#servico-na").append(`<option value="0">Buscando serviços para este item de contrato...</option>`)
		$("#servico-na").select2({
			width: '100%',
			placeholder: "Buscando serviços para este item de contrato...",
			allowClear: true
		})
	} else if (botao.attr('id') == "item-contrato-na-2") {
		$('#servico-na-2').attr('disabled', true)
		$("#servico-na-2").empty()
		$("#servico-na-2").append(`<option value="0">Buscando serviços para este item de contrato...</option>`)
		$("#servico-na-2").select2({
			width: '100%',
			placeholder: "Buscando serviços para este item de contrato...",
			allowClear: true
		})
	} else if (botao.attr('id') == "item-contrato-na-3") {
		$('#servico-na-3').attr('disabled', true)
		$("#servico-na-3").empty()
		$("#servico-na-3").append(`<option value="0">Buscando serviços para este item de contrato...</option>`)
		$("#servico-na-3").select2({
			width: '100%',
			placeholder: "Buscando serviços para este item de contrato...",
			allowClear: true
		})
	} else if (botao.attr('id') == "item-contrato-na-4") {
		$('#servico-na-4').attr('disabled', true)
		$("#servico-na-4").empty()
		$("#servico-na-4").append(`<option value="0">Buscando serviços para este item de contrato...</option>`)
		$("#servico-na-4").select2({
			width: '100%',
			placeholder: "Buscando serviços para este item de contrato...",
			allowClear: true
		})
	} else if (botao.attr('id') == "item-contrato-na-5") {
		$('#servico-na-5').attr('disabled', true)
		$("#servico-na-5").empty()
		$("#servico-na-5").append(`<option value="0">Buscando serviços para este item de contrato...</option>`)
		$("#servico-na-5").select2({
			width: '100%',
			placeholder: "Buscando serviços para este item de contrato...",
			allowClear: true
		})
	}


	let servicosAtivos = await buscarServicos(botao)

	// infelizmente a API retorna os resultados no campo "message"
	if (servicosAtivos) servicosAtivos = JSON.parse(servicosAtivos?.message)

	if (servicosAtivos?.length) {
		if (botao.attr('id') == "item-contrato-na") {
			$("#servico-na").empty()
			$("#servico-na").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			servicosAtivos.forEach(servico => {
				$("#servico-na").append(`<option value="${servico.serviceid}">${servico.name}</option>`)
			})
			$("#servico-na").attr('disabled', false)

		} else if (botao.attr('id') == "item-contrato-na-2") {
			$("#servico-na-2").empty()
			$("#servico-na-2").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			servicosAtivos.forEach(servico => {
				$("#servico-na-2").append(`<option value="${servico.serviceid}">${servico.name}</option>`)
			})
			$("#servico-na-2").attr('disabled', false)

		} else if (botao.attr('id') == "item-contrato-na-3") {
			$("#servico-na-3").empty()
			$("#servico-na-3").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			servicosAtivos.forEach(servico => {
				$("#servico-na-3").append(`<option value="${servico.serviceid}">${servico.name}</option>`)
			})
			$("#servico-na-3").attr('disabled', false)

		} else if (botao.attr('id') == "item-contrato-na-4") {
			$("#servico-na-4").empty()
			$("#servico-na-4").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			servicosAtivos.forEach(servico => {
				$("#servico-na-4").append(`<option value="${servico.serviceid}">${servico.name}</option>`)
			})
			$("#servico-na-4").attr('disabled', false)

		} else if (botao.attr('id') == "item-contrato-na-5") {
			$("#servico-na-5").empty()
			$("#servico-na-5").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			servicosAtivos.forEach(servico => {
				$("#servico-na-5").append(`<option value="${servico.serviceid}">${servico.name}</option>`)
			})
			$("#servico-na-5").attr('disabled', false)

		}
	} else {
		if (botao.attr('id') == "item-contrato-na") {
			$("#servico-na").find('option').get(0).remove()
			$("#servico-na").prepend(`<option selected disabled value="0">Nenhum serviço encontrado</option>`)
			alert("Este item de contrato não possui serviços ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-2") {
			$("#servico-na-2").find('option').get(0).remove()
			$("#servico-na-2").prepend(`<option selected disabled value="0">Nenhum serviço encontrado</option>`)
			alert("Este item de contrato não possui serviços ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-3") {
			$("#servico-na-3").find('option').get(0).remove()
			$("#servico-na-3").prepend(`<option selected disabled value="0">Nenhum serviço encontrado</option>`)
			alert("Este item de contrato não possui serviços ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-4") {
			$("#servico-na-4").find('option').get(0).remove()
			$("#servico-na-4").prepend(`<option selected disabled value="0">Nenhum serviço encontrado</option>`)
			alert("Este item de contrato não possui serviços ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-5") {
			$("#servico-na-5").find('option').get(0).remove()
			$("#servico-na-5").prepend(`<option selected disabled value="0">Nenhum serviço encontrado</option>`)
			alert("Este item de contrato não possui serviços ativos, selecione outro contrato.")
		}

	}
}


async function povoarNumeroSerieNA(botao) {

	if (botao.attr('id') == "item-contrato-na") {
		$("#serial-item-contrato-na").empty()
		$("#serial-item-contrato-na").append(`<option value="0">Buscando números de série para este item de contrato...</option>`)
		$("#serial-item-contrato-na").select2({
			width: '100%',
			placeholder: "Buscando números de série para este item de contrato...",
			allowClear: true
		})
	}
	else if (botao.attr('id') == "item-contrato-na-2") {
		$("#serial-item-contrato-na-2").empty()
		$("#serial-item-contrato-na-2").append(`<option value="0">Buscando números de série para este item de contrato...</option>`)
		$("#serial-item-contrato-na-2").select2({
			width: '100%',
			placeholder: "Buscando números de série para este item de contrato...",
			allowClear: true
		})
	} else if (botao.attr('id') == "item-contrato-na-3") {
		$("#serial-item-contrato-na-3").empty()
		$("#serial-item-contrato-na-3").append(`<option value="0">Buscando números de série para este item de contrato...</option>`)
		$("#serial-item-contrato-na-3").select2({
			width: '100%',
			placeholder: "Buscando números de série para este item de contrato...",
			allowClear: true
		})
	} else if (botao.attr('id') == "item-contrato-na-4") {
		$("#serial-item-contrato-na-4").empty()
		$("#serial-item-contrato-na-4").append(`<option value="0">Buscando números de série para este item de contrato...</option>`)
		$("#serial-item-contrato-na-4").select2({
			width: '100%',
			placeholder: "Buscando números de série para este item de contrato...",
			allowClear: true
		})
	} else if (botao.attr('id') == "item-contrato-na-5") {
		$("#serial-item-contrato-na-5").empty()
		$("#serial-item-contrato-na-5").append(`<option value="0">Buscando números de série para este item de contrato...</option>`)
		$("#serial-item-contrato-na-5").select2({
			width: '100%',
			placeholder: "Buscando números de série para este item de contrato...",
			allowClear: true
		})
	}


	let seriais = await buscarSeriaisNA(botao)

	if (seriais?.length) {
		if (botao.attr('id') == "item-contrato-na") {
			seriais.forEach(data => {
				$("#serial-item-contrato-na").append(`<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			})
			$("#serial-item-contrato-na").find('option').get(0).remove()
			$("#serial-item-contrato-na").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		}
		else if (botao.attr('id') == "item-contrato-na-2") {
			seriais.forEach(data => {
				$("#serial-item-contrato-na-2").append(`<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			})
			$("#serial-item-contrato-na-2").find('option').get(0).remove()
			$("#serial-item-contrato-na-2").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		} else if (botao.attr('id') == "item-contrato-na-3") {
			seriais.forEach(data => {
				$("#serial-item-contrato-na-3").append(`<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			})
			$("#serial-item-contrato-na-3").find('option').get(0).remove()
			$("#serial-item-contrato-na-3").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		} else if (botao.attr('id') == "item-contrato-na-4") {
			seriais.forEach(data => {
				$("#serial-item-contrato-na-4").append(`<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			})
			$("#serial-item-contrato-na-4").find('option').get(0).remove()
			$("#serial-item-contrato-na-4").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		} else if (botao.attr('id') == "item-contrato-na-5") {
			seriais.forEach(data => {
				$("#serial-item-contrato-na-5").append(`<option id="${data.id}" data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			})
			$("#serial-item-contrato-na-5").find('option').get(0).remove()
			$("#serial-item-contrato-na-5").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		}
	} else {
		if (botao.attr('id') == "item-contrato-na") {
			$("#serial-item-contrato-na").find('option').get(0).remove()
			$("#serial-item-contrato-na").prepend(`<option selected disabled value="0">Nenhum número de série encontrado</option>`)
			alert("Este item de contrato não possui número de série ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-2") {
			$("#serial-item-contrato-na-2").find('option').get(0).remove()
			$("#serial-item-contrato-na-2").prepend(`<option selected disabled value="0">Nenhum número de série encontrado</option>`)
			alert("Este item de contrato não possui número de série ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-3") {
			$("#serial-item-contrato-na-3").find('option').get(0).remove()
			$("#serial-item-contrato-na-3").prepend(`<option selected disabled value="0">Nenhum número de série encontrado</option>`)
			alert("Este item de contrato não possui número de série ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-4") {
			$("#serial-item-contrato-na-4").find('option').get(0).remove()
			$("#serial-item-contrato-na-4").prepend(`<option selected disabled value="0">Nenhum número de série encontrado</option>`)
			alert("Este item de contrato não possui número de série ativos, selecione outro contrato.")
		} else if (botao.attr('id') == "item-contrato-na-5") {
			$("#serial-item-contrato-na-5").find('option').get(0).remove()
			$("#serial-item-contrato-na-5").prepend(`<option selected disabled value="0">Nenhum número de série encontrado</option>`)
			alert("Este item de contrato não possui número de série ativos, selecione outro contrato.")
		}

	}
}

async function preencherNumeroSerieNA(botao) {

	$("#serial-item-contrato-na").empty()
	$("#serial-item-contrato-na").append(`<option value="0">Buscando números de série...</option>`)
	$("#serial-item-contrato-na").select2({
		width: '100%',
		placeholder: "Buscando números de série...",
		allowClear: true
	})

	$("#serial-item-contrato-na-2").empty()
	$("#serial-item-contrato-na-2").append(`<option value="0">Buscando números de série...</option>`)
	$("#serial-item-contrato-na-2").select2({
		width: '100%',
		placeholder: "Buscando números de série...",
		allowClear: true
	})

	$("#serial-item-contrato-na-3").empty()
	$("#serial-item-contrato-na-3").append(`<option value="0">Buscando números de série...</option>`)
	$("#serial-item-contrato-na-3").select2({
		width: '100%',
		placeholder: "Buscando números de série...",
		allowClear: true
	})

	$("#serial-item-contrato-na-4").empty()
	$("#serial-item-contrato-na-4").append(`<option value="0">Buscando números de série...</option>`)
	$("#serial-item-contrato-na-4").select2({
		width: '100%',
		placeholder: "Buscando números de série...",
		allowClear: true
	})

	$("#serial-item-contrato-na-5").empty()
	$("#serial-item-contrato-na-5").append(`<option value="0">Buscando números de série...</option>`)
	$("#serial-item-contrato-na-5").select2({
		width: '100%',
		placeholder: "Buscando números de série...",
		allowClear: true
	})

	let seriais = await buscarSeriaisNA()

	if (seriais.length) {
		seriais.forEach(data => {
			$("#serial-item-contrato-na").append(`<option data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			$("#serial-item-contrato-na-2").append(`<option data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			$("#serial-item-contrato-na-3").append(`<option data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			$("#serial-item-contrato-na-4").append(`<option data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
			$("#serial-item-contrato-na-5").append(`<option data-value="${data.itemContrato}" value="${data.id}">${data.numeroSerie}</option>`)
		})

		$("#serial-item-contrato-na").find('option').get(0).remove()
		$("#serial-item-contrato-na-2").find('option').get(0).remove()
		$("#serial-item-contrato-na-3").find('option').get(0).remove()
		$("#serial-item-contrato-na-4").find('option').get(0).remove()
		$("#serial-item-contrato-na-5").find('option').get(0).remove()

		$("#serial-item-contrato-na").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		$("#serial-item-contrato-na-2").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		$("#serial-item-contrato-na-3").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		$("#serial-item-contrato-na-4").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
		$("#serial-item-contrato-na-5").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
	} else {
		alert("Cliente não possui nenhum número de série ATIVO, não é possível continuar com o cadastro.")
		$("#modal-na").modal("hide")
	}
}

async function povoarItensDeContratoNA() {
	$("#item-contrato-na").empty()
	$("#item-contrato-na").append(`<option value="0">Buscando itens de contrato...</option>`)
	$("#item-contrato-na").select2({
		width: '100%',
		placeholder: "Buscando itens de contrato...",
		allowClear: true
	})

	$("#item-contrato-na-2").empty()
	$("#item-contrato-na-2").append(`<option value="0">Buscando itens de contrato...</option>`)
	$("#item-contrato-na-2").select2({
		width: '100%',
		placeholder: "Buscando itens de contrato...",
		allowClear: true
	})

	$("#item-contrato-na-3").empty()
	$("#item-contrato-na-3").append(`<option value="0">Buscando itens de contrato...</option>`)
	$("#item-contrato-na-3").select2({
		width: '100%',
		placeholder: "Buscando itens de contrato...",
		allowClear: true
	})

	$("#item-contrato-na-4").empty()
	$("#item-contrato-na-4").append(`<option value="0">Buscando itens de contrato...</option>`)
	$("#item-contrato-na-4").select2({
		width: '100%',
		placeholder: "Buscando itens de contrato...",
		allowClear: true
	})

	$("#item-contrato-na-5").empty()
	$("#item-contrato-na-5").append(`<option value="0">Buscando itens de contrato...</option>`)
	$("#item-contrato-na-5").select2({
		width: '100%',
		placeholder: "Buscando itens de contrato...",
		allowClear: true
	})

	let itensDeContrato = await buscarItensDeContrato(
		{
			id: dadosClienteNA?.Id ?? buscarDadosClienteAbaAtual()?.Id,
			documento: retornarTipoDeCliente(dadosClienteNA?.document)
		}
	)

	if (itensDeContrato.length) {
		itensDeContrato.forEach(item => {
			$("#item-contrato-na").append(`<option value="${item.id}">${item.nome}</option>`)
			$("#item-contrato-na-2").append(`<option value="${item.id}">${item.nome}</option>`)
			$("#item-contrato-na-3").append(`<option value="${item.id}">${item.nome}</option>`)
			$("#item-contrato-na-4").append(`<option value="${item.id}">${item.nome}</option>`)
			$("#item-contrato-na-5").append(`<option value="${item.id}">${item.nome}</option>`)
		})
		$("#item-contrato-na").find('option').get(0).remove()
		$("#item-contrato-na-2").find('option').get(0).remove()
		$("#item-contrato-na-3").find('option').get(0).remove()
		$("#item-contrato-na-4").find('option').get(0).remove()
		$("#item-contrato-na-5").find('option').get(0).remove()

		$("#item-contrato-na").prepend(`<option selected disabled value="0">Selecione um item de contrato</option>`)
		$("#item-contrato-na-2").prepend(`<option selected disabled value="0">Selecione um item de contrato</option>`)
		$("#item-contrato-na-3").prepend(`<option selected disabled value="0">Selecione um item de contrato</option>`)
		$("#item-contrato-na-4").prepend(`<option selected disabled value="0">Selecione um item de contrato</option>`)
		$("#item-contrato-na-5").prepend(`<option selected disabled value="0">Selecione um item de contrato</option>`)
	} else {
		alert("Cliente não possui nenhum item de contrato ATIVO, não é possível continuar com o cadastro.")
		$("#modal-na").modal("hide")
	}
}

var ListaPrestadores;
async function povoarPrestadoresNA() {
	let prestadores = await buscarPrestadoresNA()
	ListaPrestadores = prestadores;
	prestadores.forEach(prestador => {
		$("#prestador-na").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		$("#prestador-na-2").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		$("#prestador-na-3").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		$("#prestador-na-4").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		$("#prestador-na-5").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
	})

	$("#cep-na").prop("disabled", false);
	$("#cep-na-2").prop("disabled", false);
	$("#cep-na-3").prop("disabled", false);
	$("#cep-na-4").prop("disabled", false);
	$("#cep-na-5").prop("disabled", false);

	$("#filtrar-na").prop("disabled", false);
	$("#filtrar-na-2").prop("disabled", false);
	$("#filtrar-na-3").prop("disabled", false);
	$("#filtrar-na-4").prop("disabled", false);
	$("#filtrar-na-5").prop("disabled", false);
}

async function povoarRecursosNA(idPrestador = null, botao) {
	if (idPrestador) {
		let recursos = await buscarRecursosNA(idPrestador)

		if (botao.attr('id') == 'prestador-na') {
			recursos.forEach(recurso => {
				$("#recurso-na").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao.attr('id') == 'prestador-na-2') {
			recursos.forEach(recurso => {
				$("#recurso-na-2").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao.attr('id') == 'prestador-na-3') {
			recursos.forEach(recurso => {
				$("#recurso-na-3").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao.attr('id') == 'prestador-na-4') {
			recursos.forEach(recurso => {
				$("#recurso-na-4").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao.attr('id') == 'prestador-na-5') {
			recursos.forEach(recurso => {
				$("#recurso-na-5").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		}

	} else {
		let recursos = await buscarRecursosNA()

		if (botao?.attr('id') == 'prestador-na') {
			recursos.forEach(recurso => {
				$("#recurso-na").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao?.attr('id') == 'prestador-na-2') {
			recursos.forEach(recurso => {
				$("#recurso-na-2").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao?.attr('id') == 'prestador-na-3') {
			recursos.forEach(recurso => {
				$("#recurso-na-3").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao?.attr('id') == 'prestador-na-4') {
			recursos.forEach(recurso => {
				$("#recurso-na-4").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		} else if (botao?.attr('id') == 'prestador-na-5') {
			recursos.forEach(recurso => {
				$("#recurso-na-5").append(`<option value="${recurso.id}">${recurso.nome}</option>`)
			})
		}
	}

}

async function povoarSelectModalNA() {
	await povoarItensDeContratoNA()
	// await povoarNumeroSerieNA()
	await preencherNumeroSerieNA()


	if ($("#estado-na").children('option').length <= 1) await povoarEstados()

	if ($("#prestador-na").children('option').length <= 1) {
		await povoarPrestadoresNA().then(e => {
			$("#prestador-na").select2({
				width: '100%',
				placeholder: "Selecione o prestador",
				allowClear: true
			})
			$("#prestador-na").find('option').get(0).remove()
			$("#prestador-na").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)

			$("#prestador-na-2").select2({
				width: '100%',
				placeholder: "Selecione o prestador",
				allowClear: true
			})
			$("#prestador-na-2").find('option').get(0).remove()
			$("#prestador-na-2").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)

			$("#prestador-na-3").select2({
				width: '100%',
				placeholder: "Selecione o prestador",
				allowClear: true
			})
			$("#prestador-na-3").find('option').get(0).remove()
			$("#prestador-na-3").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)

			$("#prestador-na-4").select2({
				width: '100%',
				placeholder: "Selecione o prestador",
				allowClear: true
			})
			$("#prestador-na-4").find('option').get(0).remove()
			$("#prestador-na-4").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)

			$("#prestador-na-5").select2({
				width: '100%',
				placeholder: "Selecione o prestador",
				allowClear: true
			})
			$("#prestador-na-5").find('option').get(0).remove()
			$("#prestador-na-5").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		})
	} else {
		$("#cep-na").prop("disabled", false);
		$("#cep-na-2").prop("disabled", false);
		$("#cep-na-3").prop("disabled", false);
		$("#cep-na-4").prop("disabled", false);
		$("#cep-na-5").prop("disabled", false);

		$("#filtrar-na").prop("disabled", false);
		$("#filtrar-na-2").prop("disabled", false);
		$("#filtrar-na-3").prop("disabled", false);
		$("#filtrar-na-4").prop("disabled", false);
		$("#filtrar-na-5").prop("disabled", false);
	}

	if ($("#recurso-na").children('option').length <= 1) {
		await povoarRecursosNA().then(e => {
			$("#recurso-na").select2({
				width: '100%',
				placeholder: "Selecione o recurso",
				allowClear: true
			})
			try {
				$("#recurso-na").find('option').get(0).remove()
			} catch {
				recurso = $("#recurso-na").find('option').get(0)
				recurso.remove()
			}
			$("#recurso-na").prepend(`<option selected disabled value="0">Selecione um recurso</option>`)
		})
	}
}

$('#prestador-na').change(async function () {

	if ($("#recurso-na").children('option').length >= 1) {
		$("#recurso-na").empty()
		await povoarRecursosNA($(this).val(), $(this)).then(e => {
			$("#recurso-na").select2({
				width: '100%',
				placeholder: "Selecione o recurso",
				allowClear: true
			})

			if ($("#recurso-na").children('option').length == 1) {

				$("#recurso-na").find('option').get(0)

			} else {
				if (checaEditModalNA) {
					$("#recurso-na").val(checaEditModalNA)
					$("#recurso-na").trigger('change')
				} else {
					$("#recurso-na").prepend(`<option selected disabled value="0">Selecione um recurso</option>`)
				}
			}
			checaEditModalNA = ''
		})
	}

})

$('#prestador-na-2').change(async function () {

	if ($("#recurso-na-2").children('option').length >= 1) {
		$("#recurso-na-2").empty()
		await povoarRecursosNA($(this).val(), $(this)).then(e => {
			$("#recurso-na-2").select2({
				width: '100%',
				placeholder: "Selecione o recurso",
				allowClear: true
			})

			if ($("#recurso-na-2").children('option').length == 1) {

				$("#recurso-na-2").find('option').get(0)

			} else {
				if (checaEditModalNA) {
					$("#recurso-na-2").val(checaEditModalNA)
					$("#recurso-na-2").trigger('change')
				} else {
					$("#recurso-na-2").prepend(`<option selected disabled value="0">Selecione um recurso</option>`)
				}
			}
			checaEditModalNA = ''
		})
	}

})

$('#prestador-na-3').change(async function () {

	if ($("#recurso-na-3").children('option').length >= 1) {
		$("#recurso-na-3").empty()
		await povoarRecursosNA($(this).val(), $(this)).then(e => {
			$("#recurso-na-3").select2({
				width: '100%',
				placeholder: "Selecione o recurso",
				allowClear: true
			})

			if ($("#recurso-na-3").children('option').length == 1) {

				$("#recurso-na-3").find('option').get(0)

			} else {
				if (checaEditModalNA) {
					$("#recurso-na-3").val(checaEditModalNA)
					$("#recurso-na-3").trigger('change')
				} else {
					$("#recurso-na-3").prepend(`<option selected disabled value="0">Selecione um recurso</option>`)
				}
			}
			checaEditModalNA = ''
		})
	}

})

$('#prestador-na-4').change(async function () {

	if ($("#recurso-na-4").children('option').length >= 1) {
		$("#recurso-na-4").empty()
		await povoarRecursosNA($(this).val(), $(this)).then(e => {
			$("#recurso-na-4").select2({
				width: '100%',
				placeholder: "Selecione o recurso",
				allowClear: true
			})

			if ($("#recurso-na-4").children('option').length == 1) {

				$("#recurso-na-4").find('option').get(0)

			} else {
				if (checaEditModalNA) {
					$("#recurso-na-4").val(checaEditModalNA)
					$("#recurso-na-4").trigger('change')
				} else {
					$("#recurso-na-4").prepend(`<option selected disabled value="0">Selecione um recurso</option>`)
				}
			}
			checaEditModalNA = ''
		})
	}

})

$('#prestador-na-5').change(async function () {

	if ($("#recurso-na-5").children('option').length >= 1) {
		$("#recurso-na-5").empty()
		await povoarRecursosNA($(this).val(), $(this)).then(e => {
			$("#recurso-na-5").select2({
				width: '100%',
				placeholder: "Selecione o recurso",
				allowClear: true
			})

			if ($("#recurso-na-5").children('option').length == 1) {

				$("#recurso-na-5").find('option').get(0)

			} else {
				if (checaEditModalNA) {
					$("#recurso-na-5").val(checaEditModalNA)
					$("#recurso-na-5").trigger('change')
				} else {
					$("#recurso-na-5").prepend(`<option selected disabled value="0">Selecione um recurso</option>`)
				}
			}
			checaEditModalNA = ''
		})
	}

})

$('#filtrar-na').change(function () {
	let isChecked = $(this).prop("checked");
	if (isChecked) {
		let cep = $("#cep-na").val();
		if (!cep) {
			alert("Necessário preencher o CEP")
			$(this).prop("checked", false);
			return;
		}
		cep = cep.replace(".", "").replace("-", "")
		reordenaPrestadores(cep, $("#prestador-na"))
	} else {
		$("#prestador-na").empty()
		$("#prestador-na").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})
	}
})

$('#filtrar-na-2').change(function () {
	let isChecked = $(this).prop("checked");
	if (isChecked) {
		let cep = $("#cep-na-2").val();
		if (!cep) {
			alert("Necessário preencher o CEP")
			$(this).prop("checked", false);
			return;
		}
		cep = cep.replace(".", "").replace("-", "")
		reordenaPrestadores(cep, $("#prestador-na-2"))
	} else {
		$("#prestador-na-2").empty()
		$("#prestador-na-2").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-2").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})
	}
})

$('#filtrar-na-3').change(function () {
	let isChecked = $(this).prop("checked");
	if (isChecked) {
		let cep = $("#cep-na-3").val();
		if (!cep) {
			alert("Necessário preencher o CEP")
			$(this).prop("checked", false);
			return;
		}
		cep = cep.replace(".", "").replace("-", "")
		reordenaPrestadores(cep, $("#prestador-na-3"))
	} else {
		$("#prestador-na-3").empty()
		$("#prestador-na-3").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-3").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})
	}
})

$('#filtrar-na-4').change(function () {
	let isChecked = $(this).prop("checked");
	if (isChecked) {
		let cep = $("#cep-na-4").val();
		if (!cep) {
			alert("Necessário preencher o CEP")
			$(this).prop("checked", false);
			return;
		}
		cep = cep.replace(".", "").replace("-", "")
		reordenaPrestadores(cep, $("#prestador-na-4"))
	} else {
		$("#prestador-na-4").empty()
		$("#prestador-na-4").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-4").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})
	}
})

$('#filtrar-na-5').change(function () {
	let isChecked = $(this).prop("checked");
	if (isChecked) {
		let cep = $("#cep-na-5").val();
		if (!cep) {
			alert("Necessário preencher o CEP")
			$(this).prop("checked", false);
			return;
		}
		cep = cep.replace(".", "").replace("-", "")
		reordenaPrestadores(cep, $("#prestador-na-5"))
	} else {
		$("#prestador-na-5").empty()
		$("#prestador-na-5").prepend(`<option selected disabled value="0">Selecione um prestador</option>`)
		ListaPrestadores.forEach(prestador => {
			$("#prestador-na-5").append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		})
	}
})

async function povoarEstados() {
	let estados = await buscarEstados()

	$("#estado-na").select2({
		width: '100%',
		placeholder: "Selecione o estado",
		allowClear: true
	})

	if (estados.length) {
		$("#estado-na").find('option').get(0).remove()
		$("#estado-na").prepend(`<option selected disabled value="0">Selecione um estado</option>`)
		estados.forEach(estado => {
			$("#estado-na").append(`<option name="${estado.uf}" class="estado=na" value="${estado.id}">${estado.nome}</option>`)
		})

		$("#estado-na-2").find('option').get(0).remove()
		$("#estado-na-2").prepend(`<option selected disabled value="0">Selecione um estado</option>`)
		estados.forEach(estado => {
			$("#estado-na-2").append(`<option name="${estado.uf}" class="estado=na" value="${estado.id}">${estado.nome}</option>`)
		})

		$("#estado-na-3").find('option').get(0).remove()
		$("#estado-na-3").prepend(`<option selected disabled value="0">Selecione um estado</option>`)
		estados.forEach(estado => {
			$("#estado-na-3").append(`<option name="${estado.uf}" class="estado=na" value="${estado.id}">${estado.nome}</option>`)
		})

		$("#estado-na-4").find('option').get(0).remove()
		$("#estado-na-4").prepend(`<option selected disabled value="0">Selecione um estado</option>`)
		estados.forEach(estado => {
			$("#estado-na-4").append(`<option name="${estado.uf}" class="estado=na" value="${estado.id}">${estado.nome}</option>`)
		})

		$("#estado-na-5").find('option').get(0).remove()
		$("#estado-na-5").prepend(`<option selected disabled value="0">Selecione um estado</option>`)
		estados.forEach(estado => {
			$("#estado-na-5").append(`<option name="${estado.uf}" class="estado=na" value="${estado.id}">${estado.nome}</option>`)
		})
	}
}

/**
 * Recebe os dados de atividade de serviço e retorna as atividades separadas por tipo
 * @param {Object} dadosAtividadeDeServico 
 * @returns {Object}
 */
function filtrarAtividadesDeServicoPorStatus(dadosAtividadeDeServico) {
	let abertas = [], fechadas = [], canceladas = [], agendadas = [];

	if (dadosAtividadeDeServico && dadosAtividadeDeServico.data) {
		const incident = dadosAtividadeDeServico.data;
		abertas = incident.filter(dado => dado.Status == 0);
		fechadas = incident.filter(dado => dado.Status == 1);
		canceladas = incident.filter(dado => dado.Status == 2);
		agendadas = incident.filter(dado => dado.Status == 3);
	}

	return { abertas, fechadas, canceladas, agendadas };
}

/**
 * Carrega atividades de serviços pelo status
 * @param {String} document 
 * @param {Integer} status 
 * @returns 
 */
function carregarAtividadesDeServico(id_cliente) {
	tableAtividadesDeServico = $("#tableAtividadesDeServico").DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: lang.datatable,
		lengthChange: false,
		rowId: function (activity) {
			return activity.Id;
		},
		initComplete: function () {
			adicionarFiltroColunas(
				'tableAtividadesDeServico',
				$("#tableAtividadesDeServico").dataTable().api(),
				[0, 1, 2, 3, 4, 5, 6, 7, 8]
			);
			tableAtividadesDeServico.buttons().container().prependTo($('#tableAtividadesDeServico-' + abaSelecionada + '_filter'));
			$('.dt-buttons').css('display', 'inline-block');
			$('.dt-buttons').css('padding', '0 6px 6px 0');
		},
		columnDefs: [
			{ "width": "5%", "targets": [0] },
			{ "width": "10%", "targets": [1, 2, 3, 4, 5, 6, 7, 8] },
			{ "width": "250px", "targets": 9 }
		],
		columns: [
			{
				'data': 'Code',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'trackerSerialNumberInstall',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'provider',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'serviceName',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'serviceNameComplement',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'subject',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'scheduledstart',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'scheduledend',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'StatusCode', // Razão do Status
				'render': function (callback) {
					let retorno = ''
					if (typeof callback == 'string') {
						retorno = callback;
					} else {
						retorno = legendaStatusCodeAtividadesDeServico[callback];
					}
					return retorno;
				}
			},
			{
				'data': 'numeroOs',
				'render': function (callback) {
					return callback || ''
				}
			},
			{
				'data': 'acoes',
				'render': function (callback) {
					return callback || ''
				}
			},
		],
	});

	/**
	 * Controla o estado do botão de alterar status da atividade de serviço
	 */
	tableAtividadesDeServico.on('draw', function () {
		if (ControleTableStatusAtividadeDeServico.btnActive) {
			$(`#btn_status_na_${ControleTableStatusAtividadeDeServico.btnActive}`).addClass('btnActive');
		} else {
			$("#tableAtividadesDeServico").find('a.btnActive').removeClass('btnActive');
		}
	});

	$.ajax({
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_atividades_servico/${buscarDadosClienteAbaAtual().entity}/${id_cliente}`,
		type: 'GET',
		dataType: 'JSON',
		success: function (listaAS) {

			if (listaAS) {
				switch (listaAS.code) {
					case 200:
						let filtroAS = filtrarAtividadesDeServicoPorStatus(listaAS);
						if (filtroAS) {
							atividadesDeServico[pegarIndiceDaAbaAtual()][0] = filtroAS.abertas;
							atividadesDeServico[pegarIndiceDaAbaAtual()][1] = filtroAS.fechadas;
							atividadesDeServico[pegarIndiceDaAbaAtual()][2] = filtroAS.canceladas;
							atividadesDeServico[pegarIndiceDaAbaAtual()][3] = filtroAS.agendadas;
						}
						// renderiza numero de atividades de servico por status
						renderizarNumerosAtividadesServico();

						// renderiza dados das atividades de serviço
						renderizarDadosTabelaAtividadesDeServico(filtroAS.abertas);
						break;
					case 0:
						alert('Base de dados está apresentando instabilidade, tente novamente em alguns minutos')
						// Remove alert de carregamento
						$('div .subtitulo .alert').remove();
						return [];
					default:
						alert('Ocorreu um problema ao buscar as atividades de serviço, tente novamente em instantes.')
						return [];
				}
			} else {
				alert('Ocorreu um problema ao buscar as atividades de serviço, tente novamente em instantes.')
				return [];
			}
			$(`#btn-cadastro-na-${abaSelecionada}`).css("display", "block")

			// Remove alert de carregamento
			$('div .subtitulo .alert').remove();
		},
		error: function (error) {
			alert('Ocorreu um problema ao buscar as atividades de serviço, tente novamente em instantes.')
		}
	});
}

async function abrirModalNA(botao, idAtividadeDeServico = false, dadosCliente = false, os = false) {
	prevent = true;


	if (botao) {
		botao = $(botao)
		idOcorrencia = botao.attr('data-id')
		$("#id-ocorrencia-na").val(idOcorrencia)
		htmlBotao = botao.html()
		visibilidadeAviso = idOcorrencia || idAtividadeDeServico ? "none" : "block"
		$("#aviso-ocorrencia").css("display", visibilidadeAviso)
		botao.attr("disabled", true).html(ICONS.spinner)
	}
	let entidade = dadosCliente?.entity ?? buscarDadosClienteAbaAtual().entity
	let clienteId = dadosCliente?.Id ?? buscarDadosClienteAbaAtual().Id
	// edição
	if (idAtividadeDeServico) {
		$("#tipo-form-modal-na").html("Editar")
		$('#prestador-na').empty()
		$('#prestador-na').append(`<option value="0">Buscando prestadores...</option>`)
		await povoarSelectModalNA().then(async e => {
			$.ajax({
				url: `${URL_PAINEL_OMNILINK}/ajax_buscar_atividades_servico/
					${entidade}/${clienteId}/${idAtividadeDeServico}`,
				type: 'GET',
				dataType: 'JSON',
				success: async function (callback) {
					if (callback.code === 200) {
						callback = callback.data
						serialNA = null
						checaEditModalNA = callback?.recursoId
						dataMinima = new Date(callback.dataMinimaAgendamento).toLocaleDateString()
						idServicoDoContrato = callback[0].serviceId
						serialNA = callback[0].trackerSerialNumber
						endereco = callback.endereco
						auditoria.valor_antigo_atividade_servico = callback
						$("#id-na").val(idAtividadeDeServico)
						$("#nome-solicitante").val(callback.nomeSolicitante)
						$("#telefone-solicitante").val(callback.telefoneSolicitante)
						$("#resumo-solicitacao").val(callback[0].subject).attr("disabled", true)
						$("#item-contrato-na").val(callback[0].contract.Id).attr("disabled", true).change()
						$("#prestador-na").val(callback?.prestador?.id).change()
						$("#recurso-na").val(callback?.recursoId).attr("disabled", false)
						$("#recurso-na").trigger("change")
						$("#data-minima").val(dataMinima).attr("disabled", true)
						$("#bairro-na").val(endereco?.bairro).attr("disabled", true)
						$("#rua-na").val(endereco?.rua).attr("disabled", true)
						$("#numero-na").val(endereco?.numero).attr("disabled", true)
						$("#complemento-na").val(callback.complementoEndereco).attr("disabled", true)
						$("#servico-na").attr("disabled", true)
						$("#serial-item-contrato-na").attr("disabled", true)
						$("#troca-antena-na").prop('checked', callback[0].houveTrocaAntena).attr("disabled", true)
						$("#troca-modulo-na").prop('checked', callback[0].houveTrocaModulo).attr("disabled", true)
						$("#modelo-tipo-na").val(callback[0].tipoAtivacao).attr("disabled", true)
						$("#numero-rastreador-na").val(callback[0].numeroSerieRastreadorInstalado).attr("disabled", true)
						$("#valor-km-considerado-na").val(callback[0].valorKmConsiderado).attr("disabled", true)
						$("#distancia-bonificada-na").val(callback[0].distanciaBonificada).attr("disabled", true)
						$("#distancia-total-na").val(callback[0].distanciaTotal).attr("disabled", true)
						$("#distancia-considerada-na").val(callback[0].distanciaConsiderada).attr("disabled", true)
						$("#taxa-visita-na").val(callback[0].taxaVista).attr("disabled", true)
						$("#valor-total-deslocamento-na").val(callback[0].valorTotalDeslocamento).attr("disabled", true)
						$("#base-instalada-na").val(callback[0].baseInstaladaAntena).attr("disabled", true).change()
						$("#base-instalada-rastreador-na").val(callback[0].baseInstaladaRastreador).attr("disabled", true).change()

						$("#local-atendimento-na").val(callback.localAtendimento).attr("disabled", true).change()
						$(".endereco-modal-na").attr("disabled", true)

						anotacoesNA = []
						$('#btnCadastrarAnotacao').prop('incidentId', false)
						$('#btnCadastrarAnotacao').prop('idNa', false)

						if (callback?.anotacoes?.length) {
							$("#arquivo-download-na").show()
							$("#anotacao-na").hide()
							$("#arquivo-download-na").html(`Anotações`)
							$('#btnCadastrarAnotacao').attr('idNa', idAtividadeDeServico)
							$("#arquivo-download-na").click(async function () {
								await modalAnotacoes(false)
							})
							anotacoesNA = callback.anotacoes
						} else {
							$("#anotacao-na").show()
							$("#anotacoes-na").val("")
							$("#id-anexo").val("")
							$("#arquivo-download-na").hide()
						}
						if (os) visualizarOS(null, os);
						$("#tab-na").click()
						$("#modal-na").modal("show")
						$("#btn-adicionar-na").text("Salvar")
					}
				},
				complete: function () {
					if (botao) botao.attr("disabled", false).html(htmlBotao);
					$("#abas-na").show()
					dadosClienteNA = null;
				}
			})
		}).catch(err => {
			alert("Ocorreu um erro ao processar sua solicitação, tente novamente em instantes")
			if (botao) botao.attr("disabled", false).html(htmlBotao);
		})
	} else { //cadastro
		//aba 1
		$("#id-na").val(null)
		$("#tipo-form-modal-na").html("Cadastrar")
		$("#anotacao-na").show()
		alterarStatusDisabledForm("#form-cadastro-na", false)
		$("#resumo-solicitacao").attr("disabled", false)
		$("#resumo-solicitacao").removeAttr("disabled")
		$('#divNumeroNA-1').hide()
		$("#complemento-na").attr("disabled", false)
		$("#btn-adicionar-na").text("Salvar NA1")
		$("#arquivo-download-na").hide()
		$("#local-atendimento-na").val("2").change()
		$("#modal-na").modal("show")
		$("#btn-fechar-modal-na").attr("disabled", false)

		//aba 2
		$("#anotacao-na-2").show()
		alterarStatusDisabledForm("#form-cadastro-na-2", false)
		$("#resumo-solicitacao-2").attr("disabled", false)
		$("#resumo-solicitacao-2").removeAttr("disabled")
		$("#complemento-na-2").attr("disabled", false)
		$('#divNumeroNA-2').hide()
		$("#arquivo-download-na-2").hide()
		$("#local-atendimento-na-2").val("2").change()
		$("#btn-fechar-modal-na-2").attr("disabled", false)


		//aba 3
		$("#anotacao-na-3").show()
		alterarStatusDisabledForm("#form-cadastro-na-3", false)
		$("#resumo-solicitacao-3").attr("disabled", false)
		$("#resumo-solicitacao-3").removeAttr("disabled")
		$("#complemento-na-3").attr("disabled", false)
		$('#divNumeroNA-3').hide()
		$("#arquivo-download-na-3").hide()
		$("#local-atendimento-na-3").val("2").change()
		$("#btn-fechar-modal-na-3").attr("disabled", false)


		//aba 4
		$("#anotacao-na-4").show()
		alterarStatusDisabledForm("#form-cadastro-na-4", false)
		$("#resumo-solicitacao-4").attr("disabled", false)
		$("#resumo-solicitacao-4").removeAttr("disabled")

		$("#complemento-na-4").attr("disabled", false)
		$('#divNumeroNA-4').hide()
		$("#arquivo-download-na-4").hide()
		$("#local-atendimento-na-4").val("2").change()
		$("#btn-fechar-modal-na-4").attr("disabled", false)

		//aba 5
		$("#anotacao-na-5").show()
		limparFormNA()
		alterarStatusDisabledForm("#form-cadastro-na-5", false)
		$("#resumo-solicitacao-5").attr("disabled", false)
		$("#complemento-na-5").attr("disabled", false)
		$('#divNumeroNA-5').hide()
		$("#arquivo-download-na-5").hide()
		$("#local-atendimento-na-5").val("2").change()
		$("#btn-fechar-modal-na-5").attr("disabled", false)


		$("#cep-na").prop("disabled", true);
		$("#cep-na-2").prop("disabled", true);
		$("#cep-na-3").prop("disabled", true);
		$("#cep-na-4").prop("disabled", true);
		$("#cep-na-5").prop("disabled", true);

		$("#filtrar-na").prop("disabled", true);
		$("#filtrar-na-2").prop("disabled", true);
		$("#filtrar-na-3").prop("disabled", true);
		$("#filtrar-na-4").prop("disabled", true);
		$("#filtrar-na-5").prop("disabled", true);

		await povoarSelectModalNA()
		botao.attr("disabled", false).html(htmlBotao)
	}

}

var veioDoSerial = false
var veioDoItemContrato = false

var veioDoSerial2 = false
var veioDoItemContrato2 = false

var veioDoSerial3 = false
var veioDoItemContrato3 = false

var veioDoSerial4 = false
var veioDoItemContrato4 = false

var veioDoSerial5 = false
var veioDoItemContrato5 = false

$("#item-contrato-na").on("select2:unselect", async function (e) {
	$("#servico-na").empty()
	$("#servico-na").select2("destroy")

	$("#serial-item-contrato-na").empty()
	$("#serial-item-contrato-na").select2("destroy")

	$("#item-contrato-na").val(null)
})

$("#item-contrato-na-2").on("select2:unselect", async function (e) {
	$("#servico-na-2").empty()
	$("#servico-na-2").select2("destroy")

	$("#serial-item-contrato-na-2").empty()
	$("#serial-item-contrato-na-2").select2("destroy")
})

$("#item-contrato-na-3").on("select2:unselect", async function (e) {
	$("#servico-na-3").empty()
	$("#servico-na-3").select2("destroy")

	$("#serial-item-contrato-na-3").empty()
	$("#serial-item-contrato-na-3").select2("destroy")
})

$("#item-contrato-na-4").on("select2:unselect", async function (e) {
	$("#servico-na-4").empty()
	$("#servico-na-4").select2("destroy")

	$("#serial-item-contrato-na-4").empty()
	$("#serial-item-contrato-na-4").select2("destroy")
})

$("#item-contrato-na-5").on("select2:unselect", async function (e) {
	$("#servico-na-5").empty()
	$("#servico-na-5").select2("destroy")

	$("#serial-item-contrato-na-5").empty()
	$("#serial-item-contrato-na-5").select2("destroy")
})

$("#item-contrato-na").on("change", async function (e) {
	if (this.value !== "0" && this.value !== null) {
		if (!veioDoSerial) {
			await povoarNumeroSerieNA($(this)).then(e => {
				$("#serial-item-contrato-na").select2({
					width: '100%',
					placeholder: "Selecione o numero de serie",
					allowClear: true
				})
				$("#serial-item-contrato-na").find('option').get(0).remove()
				$("#serial-item-contrato-na").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
				if ($("#tipo-form-modal-na").text() != "Cadastrar") {
					if (serialNA) $("#serial-item-contrato-na").val(serialNA).attr("disabled", true).change()
				}
			})
		} else {
			veioDoSerial = false
		}

		await povoarServicosNA($(this)).then(e => {
			$("#servico-na").select2({
				width: '100%',
				placeholder: "Selecione o serviço",
				allowClear: true
			})
			$("#servico-na").find('option').get(0).remove()
			$("#servico-na").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			if ($("#tipo-form-modal-na").text() != "Cadastrar") {
				if (idServicoDoContrato) $("#servico-na").val(idServicoDoContrato).attr("disabled", true).change()
			}
		})
	}
})

$("#item-contrato-na-2").on("change", async function (e) {
	if (this.value !== "0" && this.value !== null) {
		if (!veioDoSerial2) {

			await povoarNumeroSerieNA($(this)).then(e => {
				$("#serial-item-contrato-na-2").select2({
					width: '100%',
					placeholder: "Selecione o numero de serie",
					allowClear: true
				})
				$("#serial-item-contrato-na-2").find('option').get(0).remove()
				$("#serial-item-contrato-na-2").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
				if ($("#tipo-form-modal-na").text() != "Cadastrar") {
					if (serialNA) $("#serial-item-contrato-na-2").val(serialNA).attr("disabled", true).change()
				}
			})

		} else {
			veioDoSerial2 = false
		}

		await povoarServicosNA($(this)).then(e => {
			$("#servico-na-2").select2({
				width: '100%',
				placeholder: "Selecione o serviço",
				allowClear: true
			})
			$("#servico-na-2").find('option').get(0).remove()
			$("#servico-na-2").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			if (idServicoDoContrato) $("#servico-na-2").val(idServicoDoContrato).attr("disabled", true).change()
		})
	}
})

$("#item-contrato-na-3").on("change", async function (e) {
	if (this.value !== "0") {
		if (!veioDoSerial3) {

			await povoarNumeroSerieNA($(this)).then(e => {
				$("#serial-item-contrato-na-3").select2({
					width: '100%',
					placeholder: "Selecione o numero de serie",
					allowClear: true
				})
				$("#serial-item-contrato-na-3").find('option').get(0).remove()
				$("#serial-item-contrato-na-3").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
				if ($("#tipo-form-modal-na").text() != "Cadastrar") {
					if (serialNA) $("#serial-item-contrato-na-3").val(serialNA).attr("disabled", true).change()
				}
			})
		} else {
			veioDoSerial3 = false
		}

		await povoarServicosNA($(this)).then(e => {
			$("#servico-na-3").select2({
				width: '100%',
				placeholder: "Selecione o serviço",
				allowClear: true
			})
			$("#servico-na-3").find('option').get(0).remove()
			$("#servico-na-3").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			if (idServicoDoContrato) $("#servico-na").val(idServicoDoContrato).attr("disabled", true).change()
		})
	}
})

$("#item-contrato-na-4").on("change", async function (e) {
	if (this.value !== "0") {
		if (!veioDoSerial4) {
			await povoarNumeroSerieNA($(this)).then(e => {
				$("#serial-item-contrato-na-4").select2({
					width: '100%',
					placeholder: "Selecione o numero de serie",
					allowClear: true
				})
				$("#serial-item-contrato-na-4").find('option').get(0).remove()
				$("#serial-item-contrato-na-4").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
				if ($("#tipo-form-modal-na").text() != "Cadastrar") {
					if (serialNA) $("#serial-item-contrato-na-4").val(serialNA).attr("disabled", true).change()
				}
			})
		} else {
			veioDoSerial4 = false
		}
		await povoarServicosNA($(this)).then(e => {
			$("#servico-na-4").select2({
				width: '100%',
				placeholder: "Selecione o serviço",
				allowClear: true
			})
			$("#servico-na-4").find('option').get(0).remove()
			$("#servico-na-4").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			if (idServicoDoContrato) $("#servico-na").val(idServicoDoContrato).attr("disabled", true).change()
		})
	}
})

$("#item-contrato-na-5").on("change", async function (e) {
	if (this.value !== "0") {
		if (!veioDoSerial5) {
			await povoarNumeroSerieNA($(this)).then(e => {
				$("#serial-item-contrato-na-5").select2({
					width: '100%',
					placeholder: "Selecione o numero de serie",
					allowClear: true
				})
				$("#serial-item-contrato-na-5").find('option').get(0).remove()
				$("#serial-item-contrato-na-5").prepend(`<option selected disabled value="0">Selecione um número de série</option>`)
				if ($("#tipo-form-modal-na").text() != "Cadastrar") {
					if (serialNA) $("#serial-item-contrato-na-5").val(serialNA).attr("disabled", true).change()
				}
			})
		} else {
			veioDoSerial5 = false
		}

		await povoarServicosNA($(this)).then(e => {
			$("#servico-na-5").select2({
				width: '100%',
				placeholder: "Selecione o serviço",
				allowClear: true
			})
			$("#servico-na-5").find('option').get(0).remove()
			$("#servico-na-5").prepend(`<option selected disabled value="0">Selecione um serviço</option>`)
			if (idServicoDoContrato) $("#servico-na").val(idServicoDoContrato).attr("disabled", true).change()
		})
	}
})

$("#serial-item-contrato-na").on("change", async function (e) {
	select = $(this)
	value = select.val()

	if (value !== "0" && value !== null) {
		if (!veioDoItemContrato) {
			veioDoSerial = true
			dataValue = $("#serial-item-contrato-na :selected").attr("data-value")

			$("#item-contrato-na").val(dataValue)
			$("#item-contrato-na").trigger("change")

		} else {
			veioDoItemContrato = false
		}
	}
})

$("#serial-item-contrato-na-2").on("change", async function (e) {
	select = $(this)
	value = select.val()

	if (value !== "0" && value !== null) {
		if (!veioDoItemContrato2) {
			veioDoSerial2 = true
			dataValue = $("#serial-item-contrato-na-2 :selected").attr("data-value")

			$("#item-contrato-na-2").val(dataValue)
			$("#item-contrato-na-2").trigger("change")

		} else {
			veioDoItemContrato2 = false
		}
	}
})

$("#serial-item-contrato-na-3").on("change", async function (e) {
	select = $(this)
	value = select.val()

	if (value !== "0" && value !== null) {
		if (!veioDoItemContrato3) {
			veioDoSerial3 = true
			dataValue = $("#serial-item-contrato-na-3 :selected").attr("data-value")

			$("#item-contrato-na-3").val(dataValue)
			$("#item-contrato-na-3").trigger("change")

		} else {
			veioDoItemContrato3 = false
		}
	}
})

$("#serial-item-contrato-na-4").on("change", async function (e) {
	select = $(this)
	value = select.val()

	if (value !== "0" && value !== null) {
		if (!veioDoItemContrato4) {
			veioDoSerial4 = true
			dataValue = $("#serial-item-contrato-na-4 :selected").attr("data-value")

			$("#item-contrato-na-4").val(dataValue)
			$("#item-contrato-na-4").trigger("change")

		} else {
			veioDoItemContrato4 = false
		}
	}
})

$("#serial-item-contrato-na-5").on("change", async function (e) {
	select = $(this)
	value = select.val()

	if (value !== "0" && value !== null) {
		if (!veioDoItemContrato5) {
			veioDoSerial5 = true
			dataValue = $("#serial-item-contrato-na-5 :selected").attr("data-value")

			$("#item-contrato-na-5").val(dataValue)
			$("#item-contrato-na-5").trigger("change")

		} else {
			veioDoItemContrato5 = false
		}
	}
})
function limparFormNA() {
	$('#form-cadastro-na').trigger("reset")
	$("#servico-na").val("0").change()
	$("#serial-item-contrato-na").val("0").change()
	$("#prestador-na").val("0").change()
	$("#recurso-na").val("0").change()

	$("#servico-na-2").val("0").change()
	$("#serial-item-contrato-na-2").val("0").change()
	$("#prestador-na-2").val("0").change()
	$("#recurso-na-2").val("0").change()

	$("#servico-na-3").val("0").change()
	$("#serial-item-contrato-na-3").val("0").change()
	$("#prestador-na-3").val("0").change()
	$("#recurso-na-3").val("0").change()

	$("#servico-na-4").val("0").change()
	$("#serial-item-contrato-na-4").val("0").change()
	$("#prestador-na-4").val("0").change()
	$("#recurso-na-4").val("0").change()

	$("#servico-na-5").val("0").change()
	$("#serial-item-contrato-na-5").val("0").change()
	$("#prestador-na-5").val("0").change()
	$("#recurso-na-5").val("0").change()

	$("#form-cadastro-na :input").attr("disabled", false);
	$("#form-cadastro-na select").attr("disabled", false);
	$("#form-cadastro-na textarea").attr("disabled", false);
	$("#form-cadastro-na :button").attr("disabled", false);

	$("#form-cadastro-na-2 :input").attr("disabled", false);
	$("#form-cadastro-na-2 select").attr("disabled", false);
	$("#form-cadastro-na-2 textarea").attr("disabled", false);
	$("#form-cadastro-na-2 :button").attr("disabled", false);

	$("#form-cadastro-na-3 :input").attr("disabled", false);
	$("#form-cadastro-na-3 select").attr("disabled", false);
	$("#form-cadastro-na-3 textarea").attr("disabled", false);
	$("#form-cadastro-na-3 :button").attr("disabled", false);

	$("#form-cadastro-na-4 :input").attr("disabled", false);
	$("#form-cadastro-na-4 select").attr("disabled", false);
	$("#form-cadastro-na-4 textarea").attr("disabled", false);
	$("#form-cadastro-na-4 :button").attr("disabled", false);

	$("#form-cadastro-na-5 :input").attr("disabled", false);
	$("#form-cadastro-na-5 select").attr("disabled", false);
	$("#form-cadastro-na-5 textarea").attr("disabled", false);
	$("#form-cadastro-na-5 :button").attr("disabled", false);

	$("#distancia-considerada-na").attr("readonly", true);
	$("#distancia-considerada-na-2").attr("readonly", true);
	$("#distancia-considerada-na-3").attr("readonly", true);
	$("#distancia-considerada-na-4").attr("readonly", true);
	$("#distancia-considerada-na-5").attr("readonly", true);

	$("#valor-km-considerado-na").attr("readonly", true);
	$("#valor-km-considerado-na-2").attr("readonly", true);
	$("#valor-km-considerado-na-3").attr("readonly", true);
	$("#valor-km-considerado-na-4").attr("readonly", true);
	$("#valor-km-considerado-na-5").attr("readonly", true);

	$("#valor-total-deslocamento-na").attr("readonly", true);
	$("#valor-total-deslocamento-na-2").attr("readonly", true);
	$("#valor-total-deslocamento-na-3").attr("readonly", true);
	$("#valor-total-deslocamento-na-4").attr("readonly", true);
	$("#valor-total-deslocamento-na-5").attr("readonly", true);

	$("#distancia-bonificada-na").attr("readonly", true);
	$("#distancia-bonificada-na-2").attr("readonly", true);
	$("#distancia-bonificada-na-3").attr("readonly", true);
	$("#distancia-bonificada-na-4").attr("readonly", true);
	$("#distancia-bonificada-na-5").attr("readonly", true);

}

async function buscarEstados() {
	let url = `${URL_PAINEL_OMNILINK}/buscarEstados`
	retorno = []

	await $.ajax({
		url,
		type: "POST",
		success: function (callback) {
			callback = JSON.parse(callback)
			if (callback.code === 200) retorno = callback.data
		}
	})

	return retorno
}

/**
 * Retorna as cidades do estado selecionado
 * @returns {Promise}
 */
async function buscarCidades() {
	path = "buscarCidades"
	erro = "Ocorreu um problema ao buscar as cidades deste estado "
	data = { idEstado: $("#estado-na option:selected").val() }

	return await buscarDadosSelectNa(path, erro, "POST", data)
}

async function buscarCidadesNaAvulsa() {
	path = "buscarCidades"
	erro = "Ocorreu um problema ao buscar as cidades deste estado "
	data = { idEstado: $("#estado-NaAvulsa option:selected").val() }

	return await buscarDadosSelectNa(path, erro, "POST", data)
}

/**
 * Altera o status de disabled de todos os inputs e selects do form
 */
function alterarStatusDisabledForm(form, statusDisabled) {
	form = $(form)

	$(form).find("input").attr('disabled', statusDisabled);
	$(form).find("select").attr('disabled', statusDisabled);
}
function calcularValorFinalItemOS() {
	let quantidade = parseInt($("#quantidade-itens-os").val())
	let valorDesconto = parseFloat($("#valor-desconto-itens-os").val()).toFixed(2)
	let valorUnitario = parseFloat($("#valor-unitario-item-os").val())

	let valorTotal = quantidade * valorUnitario

	if (!isNaN(valorDesconto)) valorTotal -= valorDesconto

	$("#valor-total-itens-os").val(valorTotal.toFixed(2))
}

function calcularDescontoItemOS(percentual = false) {
	let valorTotal = parseFloat($("#valor-unitario-item-os").val()) * parseInt($("#quantidade-itens-os").val())

	if (!percentual) {
		let valorPercentual = parseFloat($("#percentual-desconto-item-os").val())
		let percentualDesconto = (valorPercentual / 100) * valorTotal
		$("#valor-desconto-itens-os").val(percentualDesconto.toFixed(2))
	} else {
		let valorDesconto = parseFloat($("#valor-desconto-itens-os").val())
		let percentualDesconto = (valorDesconto * 100) / valorTotal
		$("#percentual-desconto-item-os").val(percentualDesconto.toFixed(2))
	}
	calcularValorFinalItemOS()
}

$(".altera-valor-total").on("change", e => {
	calcularValorFinalItemOS()
})

$("#valor-desconto-itens-os").on("change", function (e) {
	calcularDescontoItemOS(true)
})

$("#percentual-desconto-item-os").on("change", function (e) {
	calcularDescontoItemOS()
})

$("#produto-item-os").on("change", e => {
	$("#percentual-desconto-item-os").val("0").trigger("change")
})

function buscarAnotacao(idAnotacao) {
	let data = { id: idAnotacao }

	return $.ajax({
		url: `${URL_PAINEL_OMNILINK}/buscar_anotacao`,
		type: "POST",
		data
	})
}

function carregarAnotacoesNA() {
	tableAnotacoes.clear()
	tableAnotacoes.rows.add(anotacoesNA)
	tableAnotacoes.columns.adjust().draw()
}

async function atualizarAnotacoesNA(dadosAnotacao, novaAnotacao = false) {
	if (!novaAnotacao) {
		for (let i = 0; i < anotacoesNA.length; i++) {
			const anotacao = anotacoesNA[i]

			if (dadosAnotacao.idAnotacao === anotacao.idAnotacao) {
				let anotacaoAtual = JSON.parse(await buscarAnotacao(dadosAnotacao.idAnotacao))

				if (anotacaoAtual.code === 200) {
					anotacoesNA[i] = anotacaoAtual.data[0]
					carregarAnotacoesNA()
				}
				break
			}
		}
	} else {
		let anotacaoCadastrada = await formatarAnotacaoTabela(dadosAnotacao)
		anotacoesNA.push(anotacaoCadastrada)
		tableAnotacoes.row.add(anotacaoCadastrada)
		tableAnotacoes.columns.adjust().draw()
	}
}

async function formatarAnotacaoTabela(dadosAnotacao) {
	return {
		acoes: `
		<div class="btn btn-primary" onclick="editarAnotacao(this,'${dadosAnotacao.annotationid}')" title=Editar Anotação" style="text-align: center; margin: 1%;"><i class="fa fa-pencil" aria-hidden="true"></i></div>
		<div class="btn btn-danger" onclick="excluirAnotacao(this,'${dadosAnotacao.annotationid}')" title="Excluir anotação" style="text-align: center; margin: 1%;"><i class="fa fa-trash" aria-hidden="true"></i></div>`,
		anexo: ``,
		createdon: new Date(dadosAnotacao.createdon).toLocaleString(),
		criado_por: `(Shownet) ${NOME_USUARIO}`,
		idAnotacao: dadosAnotacao.annotationid,
		notetext: dadosAnotacao.notetext,
		subject: dadosAnotacao.subject
	}
}

function buscarBasesInstaladasCliente() {
	return {
		ajax: {
			url: `${URL_PAINEL_OMNILINK}/buscar_bases_instaladas`,
			dataType: 'json',
			delay: 2000,
			data: function (params) {
				return {
					q: params.term,
					id: buscarDadosClienteAbaAtual().Id
				}
			}
		},
		language: {
			inputTooShort: function () {
				return 'Digite ao menos 3 caracteres para realizar a busca.';
			},
			searching: function () {
				return "Buscando...";
			}
		},
		width: '100%',
		placeholder: "Selecione a base instalada",
		allowClear: true,
		minimumInputLength: 3,
		language: "pt-BR"
	}



}

function buscarHistoricoAf(botao, idAF) {
	let data = { idAF }
	botao = $(botao)
	let htmlBotao = botao.html();
	botao.html(ICONS.spinner);
	botao.attr('disabled', true);

	const requisicaoAjax = $.ajax({
		url: `${URL_PAINEL_OMNILINK}/buscarHistoricoAf`,
		type: "POST",
		data
	})
	requisicaoAjax.then(function (callback) {
		callback = JSON.parse(callback)
		if (callback.status === 200) {
			tableHistoricoStatusAF.clear()
			if (callback.data && callback.data?.length) {
				tableHistoricoStatusAF.rows.add(callback.data)
				tableHistoricoStatusAF.columns.adjust().draw()
				$('#modalHistoricoStatusAF').modal('show')
			} else {
				alert("Não há histórico de status para esta AF")
			}
		}
	})
		.done(function () {
			botao.html(htmlBotao);
			botao.attr('disabled', false);
		})
}

$("#limpar-dados-cliente").click(function () {
	$(this).attr("disabled", true).html(ICONS.spinner + " Limpando");
	// Limpa input documento
	$("#pesqDoc").val('');
	// Limpa input nome
	$("#pesqNome").val('').trigger('change');

	$("#pesqId").val('').trigger('change');

	$("#pesqUsuario").val('').trigger('change');


	escondeDivDadosCliente();

	setTimeout(function () {
		$("#limpar-dados-cliente").attr("disabled", false).html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar');
	}, 1000);
})

$("#btnDropdown").click(function (e) {
	e.preventDefault();
	if ($('#myDropdown').is(':visible')) {
		$('#myDropdown').hide();
	} else {
		$('#myDropdown').show();
	}
})

$("#btnDropdownOcorrencias").click(function (e){
	e.preventDefault();
	if($('#myDropdownOcorrencias').is(':visible')){
		$('#myDropdownOcorrencias').hide();
	} else {
		$('#myDropdownOcorrencias').show();
	}
})


$("#btnCadastrosDrop").click(function (e) {
	e.preventDefault();
	if ($('#cadastrosDropdown').is(':visible')) {
		$('#cadastrosDropdown').hide();
	} else {
		$('#cadastrosDropdown').show();
	}
})

$(document).on('click', function (e) {
	if (!e.target.matches('#btnDropdown') && !e.target.matches('#spanPesqAvancada')) {
		if ($('#myDropdown').is(':visible')) {
			$('#myDropdown').hide();
		}
    }
	
	if (!e.target.matches('#btnDropdownOcorrencias') && !e.target.matches('#spanOcorrencias')) {
		if($('#myDropdownOcorrencias').is(':visible')){
			$('#myDropdownOcorrencias').hide();
		}    
	}

	if (!e.target.matches('#btnCadastrosDrop') && !e.target.matches('#spanCadastrosDrop')) {
		if ($('#cadastrosDropdown').is(':visible')) {
			$('#cadastrosDropdown').hide();
		}
	}
});

$("#tab-os").click(function (event) {
	event.preventDefault();
	$("#os").show();
	$("#na").hide();
})

$("#tab-na").click(function (event) {
	event.preventDefault();
	$("#na").show();
	$("#os").hide();
})

$("#form-os-na").submit(function (event) {
	event.preventDefault();
	var data = getDadosForm('form-os-na');
	data['idOS'] = $('#id-os-na').val();
	btn = $("#btn-submit-form-os-na");
	htmlBtn = btn.html();
	idItemOS = $('#id-item-os-na').val();
	btn.attr('disabled', true).html(ICONS.spinner + " Editando...");
	url = `${URL_PAINEL_OMNILINK}/editarOS`;
	// Salva dados para auditoria
	auditoria.valor_novo_servico_contratado = data;
	salvar_auditoria(url, 'update', auditoria.valor_antigo_servico_contratado, auditoria.valor_novo_servico_contratado)
		.then(() => {
			postRequest(url, data)
				.then(callback => {
					btn.attr('disabled', false).html(htmlBtn);
					if (callback.status === 200) {
						//atualiza lista das OS
						alert(callback.message)
					} else {
						alert(callback.message);
					}

				})
				.catch(erro => {
					btn.attr('disabled', false).html(htmlBtn);
					alert('Ocorreu um erro ao tentar alterar a OS, tente novamente mais tarde.');
				});
		})
		.catch(error => {
			alert("Ocorreu um erro ao tentar alterar a OS, tente novamente em alguns minutos.")
		});
});

document.addEventListener('keydown', function (event) {
	var key = event.key || event.code;

	if (key === 'Enter' && prevent === true) {
		event.preventDefault();
	}
});

$('#modal-na').on('hidden.bs.modal', function (e) {
	prevent = false;
});

$('#prestador-NaAvulsa').select2({
	width: '100%',
	placeholder: "Selecione um prestador",
	allowClear: true
})

$('#estado-NaAvulsa').select2({
	width: '100%',
	placeholder: "Selecione o estado",
	allowClear: true
})

$('#modalCadNAAvulsa').on('shown.bs.modal', async function (e) {
	$('#cep-NaAvulsa').attr("disabled", true)
	$('#estado-NaAvulsa').empty()
	$('#estado-NaAvulsa').attr("disabled", true)
	$("#estado-NaAvulsa").append(`<option value="0" selected disabled>Buscando estados...</option>`)

	let prestadores = await buscarPrestadoresNA()
	ListaPrestadores = prestadores
	$("#prestador-NaAvulsa").attr("disabled", true)
	$("#prestador-NaAvulsa").empty()

	if (prestadores.length) {
		$("#prestador-NaAvulsa").empty()
		$("#prestador-NaAvulsa").append(`<option value="0" selected disabled>Selecione um prestador</option>`)
		prestadores.forEach(prestador => {
			$('#prestador-NaAvulsa').append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
		});

	} else {
		$("#prestador-NaAvulsa").empty()
		$("#prestador-NaAvulsa").append(`<option value="0" selected disabled>Nenhum prestador encontrado</option>`)
	}

	$("#prestador-NaAvulsa").attr("disabled", false)
	let estados = await buscarEstados()

	if (estados.length) {
		$("#estado-NaAvulsa").empty()
		$("#estado-NaAvulsa").append(`<option value="0" selected disabled>Selecione o estado</option>`)

		estados.forEach(estado => {
			$("#estado-NaAvulsa").append(`<option name="${estado.uf}" class="estado=na" value="${estado.id}">${estado.nome}</option>`)
		})

		$('#estado-NaAvulsa').attr("disabled", false)
		$('#cep-NaAvulsa').attr("disabled", false)
	}
});

$('#modalCadNAAvulsa').on('hidden.bs.modal', function (e) {
	var form = document.getElementById('formCadNAAvulsa');
	form.reset()
	$('#cliente-NaAvulsa').val("").trigger('change')
	$('#item-contrato-NaAvulsa').empty()
	$('#item-contrato-NaAvulsa').append(`<option value="0" selected disabled>Aguardando seleção de cliente</option>`)
	$('#item-contrato-NaAvulsa').attr("disabled", true)
	$('#numero-serie-NaAvulsa').empty()
	$('#numero-serie-NaAvulsa').append(`<option value="0" selected disabled>Aguardando seleção de cliente</option>`)
	$('#numero-serie-NaAvulsa').attr("disabled", true)
	$('#servico-NaAvulsa').empty()
	$('#servico-NaAvulsa').append(`<option value="0" selected disabled>Aguardando seleção de item de contrato</option>`)
	$('#servico-NaAvulsa').attr("disabled", true)
	$('#local-atendimento-NaAvulsa').val(2).trigger('change')
	$("#cep-NaAvulsa").attr("required", true)
	$('#estado-NaAvulsa').attr("required", true)
	$('#cidade-NaAvulsa').attr("required", true)
	$('#bairro-NaAvulsa').attr("required", true)
	$('#rua-NaAvulsa').attr("required", true)
	$('#numero-NaAvulsa').attr("required", true)
	$('#distancia-total-NaAvulsa').attr("required", true)
	$('#distancia-bonificada-NaAvulsa').attr("required", true)
	$('#cidade-NaAvulsa').empty();
	$('#cidade-NaAvulsa').attr("disabled", true)
	$('#prestador-NaAvulsa').empty();
	$('#prestador-NaAvulsa').attr("disabled", true)
	$('#recurso-NaAvulsa').empty();
	$('#recurso-NaAvulsa').attr("disabled", true)
	$('#recurso-NaAvulsa').append(`<option value="0" selected disabled>Aguardando seleção do prestador</option>`)

});

$('#cidade-NaAvulsa').select2({
	width: '100%',
	placeholder: "Aguardando escolha de estado ou cep...",
	allowClear: true
})

$('#estado-NaAvulsa').on('change', async function (e) {
	e.preventDefault();

	$('#cidade-NaAvulsa').empty()
	$('#cidade-NaAvulsa').append(`<option value="0" selected disabled>Buscando cidades...</option>`)
	await buscarCidadesNaAvulsa().then(cidades => {
		if (cidades.length) {
			$("#cidade-NaAvulsa").empty()
			$("#cidade-NaAvulsa").append(`<option value="0" selected disabled>Selecione a cidade</option>`)

			cidades.forEach(cidade => {
				$("#cidade-NaAvulsa").append(`<option value="${cidade.id}" name="${cidade.nome.replace(" ", "")}">${cidade.nome}</option>`)
			})

			if (cidadeNA) $("#cidade-NaAvulsa").find(`option[name=${cidadeNA}]`).attr("selected", true).change()

			$("#cidade-NaAvulsa").attr("disabled", false)
		} else {
			$("#cidade-NaAvulsa").empty()
			$("#cidade-NaAvulsa").append(`<option value="0" selected disabled>Nenhuma cidade encontrada</option>`)
		}
	}).catch(erro => {
	})
});

$('#cep-NaAvulsa').on('blur', async function (e) {
	let cep = this.value.replace(".", "").replace("-", "")

	if (cep.length === 8) {
		Swal.fire({
			customClass: {
				confirmButton: 'btn btn-primary',
				cancelButton: 'btn btn-danger'
			},
			title: 'Deseja ordenar os prestadores?',
			showCancelButton: true,
			confirmButtonText: 'Sim',
			cancelButtonText: 'Não',
			icon: 'question'
		}).then((result) => {
			if (result.isConfirmed) {
				try {
					preencheCamposEndereco(cep, cidadeNA)

				} catch (erro) {
					cidadeNA = null
				}

				if (cep) {
					reordenaPrestadores(cep, $("#prestador-NaAvulsa"))
				} else {
					alert("Necessário preencher o CEP para ordenar os prestadores.")
				}
			} else {
				try {
					preencheCamposEndereco(cep, cidadeNA)

				} catch (erro) {
					cidadeNA = null
				}

				$('#prestador-NaAvulsa').empty()
				$('#prestador-NaAvulsa').append(`<option value="0" selected disabled>Selecione um prestador</option>`)
				ListaPrestadores.forEach(prestador => {
					$('#prestador-NaAvulsa').append(`<option data-latitude="${prestador.latitude}" data-longitude="${prestador.longitude}" value="${prestador.id}">${prestador.nome}</option>`)
				});

			}
		});
	} else {
		alert("Verifique o CEP digitado e tente novamente.");
	}
});

function preencheCamposEndereco(cep, cidade) {

	$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function (endereco) {
		if (!("erro" in endereco)) {
			$("#estado-NaAvulsa").find('option:selected').attr("selected", false)
			$("#estado-NaAvulsa").find(`option[name=${endereco.uf}]`).attr("selected", true).change()

			cidadeNA = endereco.localidade.toUpperCase().replace(" ", "")
			$("#bairro-NaAvulsa").val(endereco.bairro)
			$("#rua-NaAvulsa").val(endereco.logradouro)
			$("#cidade-NaAvulsa").attr("disabled", false)
			$("#estado-NaAvulsa").attr("disabled", false)
		} else {
			cidadeNA = null
		}
	})
}
$("#local-atendimento-NaAvulsa").on("change", function (e) {
	if (this.value === "1") {
		$("#cep-NaAvulsa").attr("required", false)
		$('#estado-NaAvulsa').attr("required", false)
		$('#cidade-NaAvulsa').attr("required", false)
		$('#bairro-NaAvulsa').attr("required", false)
		$('#rua-NaAvulsa').attr("required", false)
		$('#numero-NaAvulsa').attr("required", false)
		$('#distancia-total-NaAvulsa').attr("required", false)
		$('#distancia-bonificada-NaAvulsa').attr("required", false)
	} else {
		$("#cep-NaAvulsa").attr("required", true)
		$('#estado-NaAvulsa').attr("required", true)
		$('#cidade-NaAvulsa').attr("required", true)
		$('#bairro-NaAvulsa').attr("required", true)
		$('#rua-NaAvulsa').attr("required", true)
		$('#numero-NaAvulsa').attr("required", true)
		$('#distancia-total-NaAvulsa').attr("required", true)
		$('#distancia-bonificada-NaAvulsa').attr("required", true)
	}

	divCep = document.getElementById("divCepNaAvulsa")
	divEndereco1 = document.getElementById("divEndereco1NaAvulsa")
	divEndereco2 = document.getElementById("divEndereco2NaAvulsa")
	divDistancia1 = document.getElementById("divDistancia1NaAvulsa")
	divDistancia2 = document.getElementById("divDistancia2NaAvulsa")
	visibilidade = this.value === "1" ? "none" : "block"
	divCep.style.display = visibilidade
	divEndereco1.style.display = visibilidade
	divEndereco2.style.display = visibilidade
	divDistancia1.style.display = visibilidade
	divDistancia2.style.display = visibilidade


})

$("#distancia-total-NaAvulsa").change(function () {
	if ((parseFloat($("#distancia-total-NaAvulsa").val()) - parseFloat($("#distancia-bonificada-NaAvulsa").val())) >= 0) {
		$("#distancia-considerada-NaAvulsa").val(parseFloat($("#distancia-total-NaAvulsa").val()) - parseFloat($("#distancia-bonificada-NaAvulsa").val()));
		$("#valor-total-deslocamento-NaAvulsa").val($("#distancia-considerada-NaAvulsa").val() * parseFloat($("#valor-km-considerado-NaAvulsa").val()));
	} else {
		$("#distancia-considerada-NaAvulsa").val(0)
	}
})

$("#distancia-bonificada-NaAvulsa").change(function () {
	if ((parseFloat($("#distancia-total-NaAvulsa").val()) - parseFloat($("#distancia-bonificada-NaAvulsa").val())) >= 0) {
		$("#distancia-considerada-NaAvulsa").val(parseFloat($("#distancia-total-NaAvulsa").val()) - parseFloat($("#distancia-bonificada-NaAvulsa").val()));
		$("#valor-total-deslocamento-NaAvulsa").val($("#distancia-considerada-NaAvulsa").val() * parseFloat($("#valor-km-considerado-NaAvulsa").val()));
	} else {
		$("#distancia-considerada-NaAvulsa").val(0)
	}
})

$('#cliente-NaAvulsa').on('select2:select', async function (e) {
	e.preventDefault();
	var documento = $('#cliente-NaAvulsa').val();

	$("#servico-NaAvulsa").attr('disabled', true);
	$("#servico-NaAvulsa").empty()
	$("#servico-NaAvulsa").append(`<option value="0">Aguardando seleção de item de contrato...</option>`)

	var idCliente = await retornaIdCliente(documento)
	var documentoVerificado = ''
	var tipoCliente = ''

	if (documento) {
		documentoVerificado = documento.replace(/[^0-9]/g, '');
		if (documentoVerificado.length === 11) {
			tipoCliente = 'pf'
		} else if (documentoVerificado.length === 14) {
			tipoCliente = 'pj'
		}

	} else {
		return null;
	}

	$("#item-contrato-NaAvulsa").attr('disabled', true);
	$("#item-contrato-NaAvulsa").empty()
	$("#item-contrato-NaAvulsa").append(`<option value="0">Buscando itens de contrato...</option>`)
	$("#item-contrato-NaAvulsa").select2({
		width: '100%',
		placeholder: "Selecione o item de contrato",
		allowClear: true
	})

	let itensDeContrato = await buscarItensDeContrato(
		{
			id: idCliente,
			documento: tipoCliente
		}
	)

	if (itensDeContrato.length) {
		$("#item-contrato-NaAvulsa").empty()
		$("#item-contrato-NaAvulsa").append(`<option value="0" selected disabled>Selecione o item de contrato</option>`)

		itensDeContrato.forEach(item => {
			$('#item-contrato-NaAvulsa').append(`<option value="${item.id}">${item.nome}</option>`)
		});

		$("#item-contrato-NaAvulsa").attr('disabled', false);
	} else {
		$("#item-contrato-NaAvulsa").empty()
		$("#item-contrato-NaAvulsa").append(`<option value="0" selected disabled>Nenhum item de contrato encontrado</option>`)
		alert("Nenhum item de contrato encontrado para este cliente.")
	}

	data = {
		"id_cliente": idCliente,
		"Id_item_contrato_venda": null
	}

	$("#numero-serie-NaAvulsa").attr('disabled', true);
	$("#numero-serie-NaAvulsa").empty()
	$("#numero-serie-NaAvulsa").append(`<option value="0">Buscando números de série...</option>`)
	$("#numero-serie-NaAvulsa").select2({
		width: '100%',
		placeholder: "Selecione o número de série",
		allowClear: true
	})



	let numeroSerie = await buscarDadosSelectNa("buscarSeriaisNA", "Ocorreu um erro ao buscar os seriais", "POST", data)

	if (numeroSerie.length) {
		$("#numero-serie-NaAvulsa").empty()
		$("#numero-serie-NaAvulsa").append(`<option value="0" selected disabled>Selecione o número de série</option>`)

		numeroSerie.forEach(item => {
			$('#numero-serie-NaAvulsa').append(`<option value="${item.id}" data-value="${item.itemContrato}">${item.numeroSerie}</option>`)
		});

		$('#numero-serie-NaAvulsa').attr('disabled', false);
	} else {
		$("#numero-serie-NaAvulsa").empty()
		$("#numero-serie-NaAvulsa").append(`<option value="0" selected disabled>Nenhum número de série encontrado</option>`)
		alert("Nenhum número de série encontrado para este cliente.")
	}

});

var veioDoNumeroSerie = false;

$("#item-contrato-NaAvulsa").on('select2:select', async function (e) {

	e.preventDefault();
	var servicos = ''

	$("#servico-NaAvulsa").select2({
		width: '100%',
		placeholder: "Selecione o serviço",
		allowClear: true
	})

	if (!veioDoNumeroSerie) {

		$("#numero-serie-NaAvulsa").attr('disabled', true);
		$("#numero-serie-NaAvulsa").empty()
		$("#numero-serie-NaAvulsa").append(`<option value="0">Buscando números de série...</option>`)

		$("#servico-NaAvulsa").attr('disabled', true);
		$("#servico-NaAvulsa").empty()
		$("#servico-NaAvulsa").append(`<option value="0">Buscando serviços...</option>`)

		//Preenche select serviços


		servicos = await buscarServicos($(this))
		servicos = JSON.parse(servicos.message)

		if (servicos.length) {
			$("#servico-NaAvulsa").empty()
			$("#servico-NaAvulsa").append(`<option value="0" selected disabled>Selecione o serviço</option>`)
			if (servicos.length) {
				servicos.forEach(item => {
					$('#servico-NaAvulsa').append(`<option value="${item.serviceid}">${item.name}</option>`)
				});
			}

			$("#servico-NaAvulsa").attr('disabled', false);
		} else {
			$("#servico-NaAvulsa").empty()
			$("#servico-NaAvulsa").append(`<option value="0" selected disabled>Nenhum serviço encontrado</option>`)
			alert("Nenhum serviço encontrado para este item de contrato.")
		}

		//Busca números de série e preenche select
		var documento = $('#cliente-NaAvulsa').val();

		var idCliente = await retornaIdCliente(documento)

		data = {
			"id_cliente": idCliente,
			"Id_item_contrato_venda": $(this).val()
		}

		let numeroSerie = await buscarDadosSelectNa("buscarSeriaisNA", "Ocorreu um erro ao buscar os seriais", "POST", data)

		if (numeroSerie.length) {
			$("#numero-serie-NaAvulsa").empty()
			$("#numero-serie-NaAvulsa").append(`<option value="0" selected disabled>Selecione o número de série</option>`)

			numeroSerie.forEach(item => {
				$('#numero-serie-NaAvulsa').append(`<option value="${item.id}" data-value="${item.itemContrato}">${item.numeroSerie}</option>`)
			});

			$('#numero-serie-NaAvulsa').attr('disabled', false);
		} else {

			$("#numero-serie-NaAvulsa").empty()
			$('#numero-serie-NaAvulsa').attr('disabled', false);
			$("#numero-serie-NaAvulsa").append(`<option value="" selected>Nenhum número de série encontrado</option>`)
			alert("Nenhum número de série encontrado para este item de contrato.")
		}
	} else {
		$("#servico-NaAvulsa").attr('disabled', true);
		$("#servico-NaAvulsa").empty()
		$("#servico-NaAvulsa").append(`<option value="0">Buscando serviços...</option>`)

		servicos = await buscarServicos($(this))
		servicos = JSON.parse(servicos.message)

		if (servicos.length) {
			$("#servico-NaAvulsa").empty()
			$("#servico-NaAvulsa").append(`<option value="0" selected disabled>Selecione o serviço</option>`)
			if (servicos.length) {
				servicos.forEach(item => {
					$('#servico-NaAvulsa').append(`<option value="${item.serviceid}">${item.name}</option>`)
				});
			}

			$("#servico-NaAvulsa").attr('disabled', false);
		} else {
			$("#servico-NaAvulsa").empty()
			$("#servico-NaAvulsa").append(`<option value="0" selected disabled>Nenhum serviço encontrado</option>`)
			alert("Nenhum serviço encontrado para este item de contrato.")
		}

		veioDoNumeroSerie = false;
	}
});

$("#numero-serie-NaAvulsa").on('select2:select', function (e) {
	e.preventDefault();
	veioDoNumeroSerie = true;
	$("#item-contrato-NaAvulsa").val($(this).find('option:selected').attr('data-value')).trigger('select2:select');
	$("#item-contrato-NaAvulsa").trigger('change');
});

$('#recarregarSeries').click(async function (e) {
	e.preventDefault();
	var idCliente = null;

	if ($('#cliente-NaAvulsa').val() !== null && $('#numero-serie-NaAvulsa').attr('disabled') !== "disabled") {
		idCliente = await retornaIdCliente($('#cliente-NaAvulsa').val())
		data = {
			"id_cliente": idCliente,
			"Id_item_contrato_venda": null
		}

		$("#numero-serie-NaAvulsa").attr('disabled', true);
		$("#numero-serie-NaAvulsa").empty()
		$("#numero-serie-NaAvulsa").append(`<option value="0">Buscando números de série...</option>`)
		$("#numero-serie-NaAvulsa").select2({
			width: '100%',
			placeholder: "Selecione o número de série",
			allowClear: true
		})



		let numeroSerie = await buscarDadosSelectNa("buscarSeriaisNA", "Ocorreu um erro ao buscar os seriais", "POST", data)

		if (numeroSerie.length) {
			$("#numero-serie-NaAvulsa").empty()
			$("#numero-serie-NaAvulsa").append(`<option value="0" selected disabled>Selecione o número de série</option>`)

			numeroSerie.forEach(item => {
				$('#numero-serie-NaAvulsa').append(`<option value="${item.id}" data-value="${item.itemContrato}">${item.numeroSerie}</option>`)
			});

			$('#numero-serie-NaAvulsa').attr('disabled', false);
		} else {
			$("#numero-serie-NaAvulsa").empty()
			$("#numero-serie-NaAvulsa").append(`<option value="" selected>Nenhum número de série encontrado</option>`)
			alert("Nenhum número de série encontrado para este cliente.")
		}
	}


});

$('#recurso-NaAvulsa').select2({
	width: '100%',
	placeholder: "Aguardando seleção do prestador...",
	allowClear: true
})

$("#estado-na").select2({
	width: '100%',
	placeholder: "Selecione o estado",
	allowClear: true
})

$('#prestador-NaAvulsa').on('select2:select', async function (e) {
	e.preventDefault();

	$("#recurso-NaAvulsa").attr('disabled', true);
	$("#recurso-NaAvulsa").empty()
	$("#recurso-NaAvulsa").append(`<option value="0">Buscando recursos...</option>`)

	let recursos = await buscarRecursosNA($(this).val())

	if (recursos.length) {
		$("#recurso-NaAvulsa").empty()
		$("#recurso-NaAvulsa").append(`<option value="0" selected disabled>Selecione o recurso</option>`)

		recursos.forEach(item => {
			$('#recurso-NaAvulsa').append(`<option value="${item.id}">${item.nome}</option>`)
		});

		$('#recurso-NaAvulsa').attr('disabled', false);
	} else {
		$("#recurso-NaAvulsa").empty()
		$("#recurso-NaAvulsa").append(`<option value="0" selected disabled>Nenhum recurso encontrado</option>`)
		alert("Nenhum recurso encontrado para este prestador.")
	}
});


async function retornaIdCliente(documento) {
	var documentoVerificado = '';
	var idCliente = null;
	if (documento) {
		await $.ajax({
			url: `${URL_PAINEL_OMNILINK}/retornaIdCliente`,
			type: "GET",
			data: { documento: documento },
			success: function (callback) {
				callback = JSON.parse(callback)
				if (callback.code === 200) {
					idCliente = callback.data
				} else {
					alert(callback.msg)
				}
			}
		})

	} else {
		return null;
	}

	return idCliente;
}

$('#formCadNAAvulsa').submit(async function (event) {
	event.preventDefault();
	btn = $("#btnSalvarCadastroNaAvulsa");
	btn.attr('disabled', true).html(ICONS.spinner + " Salvando...");

	var dadosForm = getDadosForm('formCadNAAvulsa');
	console.log(dadosForm['cliente-NaAvulsa'])
	var tipoCliente = ((dadosForm['cliente-NaAvulsa']).replace(/[^0-9]/g, '')).length === 11 ? 2 : 1
	var idCliente = await retornaIdCliente(dadosForm['cliente-NaAvulsa'])

	data = {
		statusDescription: "Aberto",
		ResumoSolicitacao: dadosForm['resumo-solicitacao-NaAvulsa'],
		NomeSolicitante: dadosForm['nome-solicitante-NaAvulsa'],
		TelefoneSolicitante: dadosForm['telefone-solicitante-NaAvulsa'],
		Cliente: idCliente,
		Customer: idCliente,
		CustomerType: tipoCliente,
		TipoServico: 1,
		NomeUsuarioGestor: EMAIL_USUARIO,
		serviceName: $('#servico-NaAvulsa').find('option:selected').text(),
		Prestador: dadosForm['prestador-NaAvulsa'],
		provider: $('#recurso-NaAvulsa').find('option:selected').text(),
		Recurso: dadosForm['recurso-NaAvulsa'],
		ItemContrato: dadosForm['item-contrato-NaAvulsa'],
		Servico: dadosForm['servico-NaAvulsa'],
		DataMinimaAgendamento: dadosForm['data-minima-NaAvulsa'] + ":00Z",
		LocalAtendimento: dadosForm['local-atendimento-NaAvulsa'],
		Complemento: dadosForm['complemento-NaAvulsa'],
		HouveTrocaModulo: false,
		HouveTrocaAntena: false,
		trackerSerialNumber: dadosForm['numero-serie-NaAvulsa']
	}

	if (dadosForm['local-atendimento-NaAvulsa'] === "2") {
		data['Cep'] = dadosForm['cep-NaAvulsa']
		data['Bairro'] = dadosForm['bairro-NaAvulsa']
		data['Rua'] = dadosForm['rua-NaAvulsa']
		data['Numero'] = dadosForm['numero-NaAvulsa']
		data['ValorKmConsiderado'] = parseFloat(dadosForm['valor-km-considerado-NaAvulsa'])
		data['TaxaVisita'] = parseFloat(dadosForm['taxa-visita-NaAvulsa'])
		data['ValorTotalDeslocamento'] = parseFloat(dadosForm['valor-total-deslocamento-NaAvulsa'])
		data['DistanciaTotal'] = parseFloat(dadosForm['distancia-total-NaAvulsa'])
		data['DistanciaBonificada'] = parseFloat(dadosForm['distancia-bonificada-NaAvulsa'])
		data['DistanciaConsiderada'] = parseFloat(dadosForm['distancia-considerada-NaAvulsa'])
	}

	let arquivo = document.querySelector('#anexo-NaAvulsa')?.files[0];

	if (arquivo) {
		let nomeArquivo = arquivo.name;
		arquivo = await toBase64(arquivo);
		arquivo = arquivo.toString().replace(/^data:(.*,)?/, '');

		data.Anotacoes = [
			{
				Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
				NoteText: dadosForm['anotacoes-NaAvulsa'] || "-",
				Documentbody: arquivo,
				FileName: nomeArquivo
			}
		]
	} else if (dadosForm['anotacoes-NaAvulsa']) {
		data.Anotacoes = [
			{
				Subject: `Anotação criada em ${new Date().toLocaleString()} por ${EMAIL_USUARIO} (ShowNet)`,
				NoteText: dadosForm['anotacoes-NaAvulsa'] || "-",
			}
		]
	}

	url = `${URL_PAINEL_OMNILINK}/cadastrarNA`;

	$.ajax({
		url: url,
		type: "POST",
		dataType: "json",
		data,
		success: function (callback) {
			if (callback.status === 200 && callback.message.msg === "NA Criada com sucesso") {
				alert("NA cadastrada com sucesso.");
				alterarStatusNA(callback.message.na)
				btn.attr('disabled', false).html("Salvar");
				$('#modalCadNAAvulsa').modal('hide');
			} else {
				alert(callback.message);
				btn.attr('disabled', false).html("Salvar");
			}
		},
		error: function (erro) {
			alert("Ocorreu um erro ao tentar cadastrar a NA, tente novamente mais tarde.")
			btn.attr('disabled', false).html("Salvar");
		},
		complete: function () {
			btn.attr('disabled', false).html("Salvar");
		}
	})

});
$('#telefone-solicitante-NaAvulsa').mask('(00) 00000-0000');
$('#cep-NaAvulsa').mask('00000-000');

function ShowLoadingScreen() {
	$('#loading').show()
}

function HideLoadingScreen() {
	$('#loading').hide()
}

$('#modal-comunicacao-chip').on('hidden.bs.modal', function (e) {
	tabelaComunicacao.clear().draw();
	tabelaIridium.clear().draw();
});

// Funções Porgramáveis
$(document).ready(function () {
	$('#btnModalFuncoesProgramaveis').off().on('click', function() {
		ShowLoadingScreen();
		$('#funcao_programavel_cliente_id').val(buscarDadosClienteAbaAtual()?.codigoClienteShow);
		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/ajax_all_grupo_funcoes_programaveis`,
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				if (data) {
					if (data.status == 200) {
						$("#funcao_programavel_id").select2({
							data: data.resultado,
							placeholder: "Digite o grupo de função programável...",
							allowClear: true,
							language: "pt-BR",
							width: 'resolve',
							height: '32px',
						});
						$("#funcao_programavel_id").val("").trigger("change");
						$("#modalFuncoesProgramaveis").modal('show');
					} else if (data.status == 404) {
						showAlert("error", "Não há grupos de funções programáveis para serem associadas!");
					} else {
						showAlert("error", "Não foi possível carregar os grupos de funções programáveis. Tente novamente mais tarde!");
					}
				} else {
					showAlert("error", "Não foi possível carregar os grupos de funções programáveis. Tente novamente mais tarde!");
				}
			},
			error: function(error, message) {
				showAlert("error", "Não foi possível carregar os grupos de funções programáveis. Tente novamente mais tarde!");
				HideLoadingScreen();
			},
			complete: function() {
				HideLoadingScreen();
			}
		});
	});

	$("#modalFuncoesProgramaveis").on("hide.bs.modal", function() {
		$('#funcao_programavel_cliente_id').val("");
		$("#funcao_programavel_id").val("").trigger("change");
		$("#funcao_programavel_descricao").val("");
	});

	$('#formFuncoesProgramaveis').on("submit", function(e) {
		e.preventDefault();

		let dataForm = {
			idFuncaoProgramavel: $("#funcao_programavel_id").val(),
			descricao: $("#funcao_programavel_descricao").val(),
			idCliente: $('#funcao_programavel_cliente_id').val()
		}

		$.ajax({
			url: `${URL_PAINEL_OMNILINK}/ajax_associar_grupo_funcao_programavel`,
			type: 'POST',
			data: dataForm,
			dataType: 'json',
			beforeSend: function() {
				$("#btnSubmitFuncaoProgramavel").html('<i class="fa fa-spinner fa-spin"></i> Salvando').attr("disabled", true);
			},
			success: function (data) {
				if (data) {
					if (data.status == 200) {
						$("#btnSubmitFuncaoProgramavel").html('Salvar').attr("disabled", false);
						$("#modalFuncoesProgramaveis").modal('hide');
						$("#tableFuncoesProgamaveis-" + abaSelecionada).DataTable().ajax.reload();
						showAlert("sucess", "Grupo de Função programável associada com sucesso!");
					} else if (data.status == 400 || data.status == 404) {
						if ('mensagem' in data.resultado) {
							showAlert("error", data.resultado.mensagem);
						} else {
							showAlert("error", "Não foi possível associar o grupo de função programável. Tente novamente mais tarde!");
						}
					} else {
						showAlert("error", "Não foi possível associar o grupo de função programável. Tente novamente mais tarde!");
					}
				} else {
					showAlert("error", "Não foi possível associar o grupo de função programável. Tente novamente mais tarde!");
				}
				$("#btnSubmitFuncaoProgramavel").html('Salvar').attr("disabled", false);
			},
			error: function() {
				showAlert("error", "Não foi possível associar o grupo de função programável. Tente novamente mais tarde!");
				$("#btnSubmitFuncaoProgramavel").html('Salvar').attr("disabled", false);
			},
			complete: function() {
				$("#btnSubmitFuncaoProgramavel").html('Salvar').attr("disabled", false);
			}
		})

	});
});

function removerGrupoFuncaoProgramavel(button, idFuncaoProgramavel) {
    let btn = $(button);
    let htmlBtn = btn.html();

    Swal.fire({
        title: "Atenção!",
        text: "Você tem certeza que deseja excluir o grupo de função programável do cliente?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_desassociar_grupo_funcao_programavel`,
                type: 'POST',
                data: {
                    id: idFuncaoProgramavel,
                    status: 0
                },
                dataType: 'json',
                beforeSend: function() {
                    btn.html(ICONS.spinner).attr("disabled", true);
                },
                success: function(data) {
                    if (data.status == 200) {
                        $("#modalFuncoesProgramaveis").modal('hide');
                        showAlert("success", "Grupo removido com sucesso!");
                        $("#tableFuncoesProgamaveis-" + abaSelecionada).DataTable().ajax.reload();
                    } else {
                        showAlert("error", data.resultado.mensagem || "Não foi possível remover o grupo. Tente novamente mais tarde!");
                    }
                },
                error: function() {
                    showAlert("error", "Não foi possível remover o grupo. Tente novamente mais tarde!");
                },
                complete: function() {
                    btn.html(htmlBtn).attr("disabled", false);
                }
            });
        }
    });
}


$('.select_usuario').select2({
	ajax: {
		url: RouterUsuariosGestor + '/get_ajax_usuarios_gestores',
		dataType: 'json'
	},
	placeholder: "Selecione o Usuario",
	allowClear: true,
	language: 'pt-BR',

});

$('#prioridade').select2({
	language: 'pt-BR',
})

$('#placa').select2({
	language: 'pt-BR',
})

$('#departamento').select2({
	language: 'pt-BR',
})

$('#btnTicketCliente').on('click', function(e){
	e.preventDefault();
	$('#novo_ticket').modal('show');
})

$('#novo_ticket').on('hide.bs.modal', function(e){
	$('#id_usuario').val('').trigger('change');
	$('#placa').val('').trigger('change');
	$('#assunto').val('');
	$('#departamento').val('').trigger('change');
	$('#prioridade').val('').trigger('change');
	$('#input_id_cliente').val('');
	$('#input_usuario').val('');
	$('#input_nome_usuario').val('');
	$('#descricao').val('');
	$('#arquivo').val('');
})

$(document).on('change', '.select_usuario', function () {
	var id_usuario = $(this).val();
	var str = "";
	console.log(id_usuario)
	if (id_usuario) {
		document.getElementById('placa').readonly = true;
		$.ajax({
			url: SITE_URL + '/veiculos/lista_placas_usuario/' + id_usuario,
			datatype: 'json',
			success: function (data) {
				var res = JSON.parse(data);
				if (res.status == 'OK') {
					$("#input_id_cliente")[0].value = res.results[0].id_cliente;
					$("#input_usuario")[0].value = res.results[0].usuario;
					$("#input_nome_usuario")[0].value = res.results[0].nome_usuario;
					str += '<option value="" disabled selected>Selecione uma Placa</option>';
					$(res.results).each(function (index, value) {
						str += '<option value=' + value.placa + '>' + value.placa + '</option>';
					})
					$('#placa').html(str);
					$('#placa').select2({
						language: 'pt-BR',

					})
				}
				document.getElementById('placa').readonly = false;
			},
			error: function (error) {
				document.getElementById('placa').readonly = false;
			},
		});
	}

});

$("#ContactForm").submit(function (e) {
	e.preventDefault();
	showLoadingSalvarButton()

	var formdata = new FormData($("#ContactForm")[0]);

	referencia = 'OMNILINK'

	formdata.append('referencia', referencia);
	
	$.ajax({
		cache: false,
		url: RouterWebdesk + "/new_ticket",
		type: 'POST',
		dataType: 'json',
		data: formdata,
		contentType: false,
		processData: false,
		success: function (callback) {
			if (callback.success == true) {
				$("#ContactForm").trigger('reset');
				$('#placa').val(null).trigger('change');

				showAlert('success','Ticket criado com sucesso!')

			} else {
				showAlert('error', callback.mensagem);
			}
			resetSalvarButton()
			$('#novo_ticket').modal('hide');

		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			showAlert('error', 'Não é possível salvar o ticket no momento. Tente novamente mais tarde!');
			resetSalvarButton()
			$('#novo_ticket').modal('hide');
		}
	});
	return false;
});

document.addEventListener('DOMContentLoaded', function() {
	const textarea = document.getElementById('descricao');
	const countdown = document.getElementById('content-countdown');
	const maxLength = 500;

	function updateCountdown() {
		const remaining = maxLength - textarea.value.length;
		countdown.textContent = remaining;
	}

	textarea.addEventListener('input', updateCountdown);

	updateCountdown();
});

function exibirFuncoes(idGrupo){
	$('#funcProgramaveis').modal('show');

	if ($.fn.DataTable.isDataTable("#tableFuncoesProgamaveisModal")) {
        $("#tableFuncoesProgamaveisModal").DataTable().clear().destroy();
        $("#tableFuncoesProgamaveisModal tbody").empty();
    }

	tableFuncoesProgamaveis = $("#tableFuncoesProgamaveisModal").DataTable({
		processing: true,
		serverSide: false,
		responsive: true,
		language: {
			...lang.datatable,
			sProcessing: '<STRONG id="processando-alert-funcoes">Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>'
		},
		lengthChange: false,
		ordering: false,
		ajax: {
			url: Router + '/buscarFuncoesByGrupoFp',
			data: {
				id: idGrupo
			},
			type: 'POST',
			dataType: 'json', 
			dataSrc: function (receivedData) { //verifica se os dados foram carregados

				if (receivedData) {
					switch (receivedData.status) {

						case 200:
							return receivedData.resultado; //retorna o que deve ser trabalhado no datatable
						case 404:
							showAlert("warning","Não há funções programáveis associadas a este grupo!");
							return [];
						case 504:
							showAlert("warning",'Base de dados está apresentando instabilidade, tente novamente em alguns minutos.')
							return [];
						default:
							showAlert("error",'Ocorreu um problema ao buscar as funções programáveis, tente novamente em instantes.')
							return [];
					}

				} else {
					showAlert("error",'Ocorreu um problema ao buscar as funções programáveis, tente novamente em instantes.')
					return [];
				}

			},
			error: function (xhr, error, thrown) {
				showAlert("error","Ocorreu um erro ao buscar as funções programáveis, tente novamente mais tarde.")
				return [];
			}
		},
		columns: [
			{ data: 'id' },
			{ data: 'descricao' },
			{ data: 'dataHoraAlteracao',
			  	render: function(data, type, row){
					return formatDateTime(data);
				}
			}
		],
		dom: 'lrtip',
		buttons: []
	});
}

function formatDateTime(date) {
    if (!date || typeof date !== 'string') {
        return "";
    }

    const parts = date.split(' ');
    const dateParts = parts[0] ? parts[0].split('-') : null;
    if (!dateParts || dateParts.length !== 3) {
        return "";
    }

    const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    const timePart = parts.length > 1 ? ` ${parts[1]}` : "";

    return formattedDate + timePart;
}

function ticketPersonalizado(element, type) {
    // Obter a tabela correspondente (baseado no ID do botão clicado)
    var tableId = $(element).closest('table').attr('id');
    var table = $('#' + tableId).DataTable();

    // Obter a linha mais próxima do botão clicado
    var row = $(element).closest('tr');

    // Obter os dados da linha
    var rowData = table.row(row).data();

	if(type == 'Base'){
		let str = "Produto: " + rowData.nome_produto + "\nNúmero de Série: " + rowData.numero_serie + " ";
		$('#assuntoMovidesk').val("Base Instalada - CPF/CNPJ: " + buscarDadosClienteAbaAtual()?.document);
		$('#mensagemMovidesk').val(str);
		//Seta serviço ao carregar select
		$(document).off('servicosPaiLoaded.ticketPersonalizado');
    	$(document).on('servicosPaiLoaded.ticketPersonalizado', function() {
			$('#servicoMovidesk').val(788489).trigger('change');
			$(document).off('servicosPaiLoaded.ticketPersonalizado');
    	});
		//Seta subserviço ao carregar select
		$(document).off('servicosFilhoLoaded.ticketPersonalizado');
    	$(document).on('servicosFilhoLoaded.ticketPersonalizado', function() {
			$('#subservicoMovidesk').val(788493).trigger('change');
			$('#urgenciaMovidesk').val(2).trigger('change');
			$(document).off('servicosFilhoLoaded.ticketPersonalizado');
    	});
		
	}else if(type == 'Chip'){
		let str = "Tecnologia: " + rowData.nomeTecnologia + "\nNome do Modelo: " + rowData.nomeModelo + "\nNúmero do Chip: " + rowData.fone + "\nSerial do Equipamento: " + $("#h3-busca-chip").attr("serialEquipamento");
		$('#assuntoMovidesk').val("RESET DE LINHA " + rowData.operadora +  " - CPF/CNPJ: " + buscarDadosClienteAbaAtual()?.document);
		$('#mensagemMovidesk').val(str);
		$(document).off('servicosPaiLoaded.ticketPersonalizado');
		$(document).on('servicosPaiLoaded.ticketPersonalizado', function() {
			$('#servicoMovidesk').val(845250).trigger('change');
			$(document).off('servicosPaiLoaded.ticketPersonalizado');
		});
		$('#urgenciaMovidesk').val(1).trigger('change');
	} else if(type == 'Iridium'){
		let str = "Imei: " + rowData.imei;
		$('#assuntoMovidesk').val("ANTENA - CPF/CNPJ: " + buscarDadosClienteAbaAtual()?.document);
		$('#mensagemMovidesk').val(str);
		$(document).off('servicosPaiLoaded.ticketPersonalizado');
		$(document).on('servicosPaiLoaded.ticketPersonalizado', function() {
			$('#servicoMovidesk').val(845250).trigger('change');
			$(document).off('servicosPaiLoaded.ticketPersonalizado');
		});
		$('#urgenciaMovidesk').val(1).trigger('change');
	}

	$('#ccMovidesk').val("lucas.cabral@omnilink.com.br"); 
	$('#clienteIdMovidesk').val(buscarDadosClienteAbaAtual()?.codigoClienteShow);
	$('#prestadoraMovidesk').val(1);
	console.log(buscarDadosClienteAbaAtual()?.codigoClienteShow)
}

function showLoadingSalvarButton() {
    $('#btnSalvarTicket').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButton() {
    $('#btnSalvarTicket').html('Salvar').attr('disabled', false);
}