let tab = 'aba-solicitacao';
let valorTotalSolicitacao = 0.0;
let possuiAnexoSolicitacao = false;
let abaSolicitacao = $('#li-aba-solicitacao');
let abaProdutos = $('#li-aba-produtos');
let abaCotacoes = $('#li-aba-cotacoes');
let botaoEtapaAnterior = $('.prev-step');
let botaoEtapaSeguinte = $('.next-step');

$(document).ready(function () {
  $('select').select2({
    placeholder: 'Selecione uma opção',
    allowClear: true,
    width: '100%',
    language: 'pt-BR',
  });

  // gerencia qual aba será exibida de acordo com a ação do usuário
  gerenciarAcoes(acao);
});

function ativarTab(idTab) {
  $('.nav-tabs a[href="#' + idTab + '"]').tab("show");
}

function disabilitarTab(idTab) {
  $('.nav-tabs a[href="#' + idTab + '"]').addClass("disabled");
}

function habilitarTab(idTab) {
  $('.nav-tabs a[href="#' + idTab + '"]').removeClass("disabled");
}

function gerenciarAcoes(acao) {
  switch (acao) {
		case 'adicionar_produto_cotacao':
			configurarCamposParaAdicionarProdutoCotacao();
			buscarDadosFormulario();
			break;

    case 'adicionar_cotacao':
      configurarCamposParaAdicionarCotacao();
      buscarDadosFormulario();
      break;

    case 'selecionar_cotacao':
      configurarCamposParaSelecionarCotacao();
      buscarDadosFormulario();
      break;

    case 'editar':
      configurarCamposParaEdicarSolicitacao();
      buscarDadosFormulario();
      break;

    default:
			configurarCamposParaCadastrarSolicitacao();
      break;
  }

  ativarTab(tab);
}

function configurarCamposParaCadastrarSolicitacao() {
	tab = 'aba-solicitacao';

	$('#titulo-cadatrar-editar').text(tipoSolicitacao && tipoSolicitacao === 'requisicao'
		? `Cadastrar Requisição`
		: `Cadastrar Pedido`);
	
	abaSolicitacao.show();
	abaProdutos.show();
	if (tipoSolicitacao === 'requisicao') abaCotacoes.hide();
	else abaCotacoes.show();

	botaoEtapaAnterior
		.attr('disabled', true)
		.css('display', 'none');

	if (cadastroPedido) {
		$('#span-total-solicitacao').css('display', 'block');
	}
}

function configurarCamposParaEdicarSolicitacao() {
  tab = 'aba-solicitacao';

	$('#titulo-cadatrar-editar').text(tipoSolicitacao && tipoSolicitacao === 'requisicao' 
		? `Editar Requisição #${idSolicitacao}` 
		: `Editar Pedido #${idSolicitacao}`);

	abaSolicitacao.show();
	abaProdutos.show();
	if (tipoSolicitacao === 'requisicao') abaCotacoes.hide();
	else abaCotacoes.show();

	botaoEtapaAnterior
		.attr('disabled', true)
		.css('display', 'none');

	if (cadastroPedido) {
		$('#span-total-solicitacao').css('display', 'block');
	}
}

function configurarCamposParaAdicionarProdutoCotacao() {
	tab = 'aba-produtos';
	$('#titulo-cadatrar-editar').text(`Adicionar Produto e Cotação (Solicitação #${idSolicitacao})`);

	abaSolicitacao.hide();
	abaProdutos.show();
	abaCotacoes.show();

	botaoEtapaAnterior
		.attr('disabled', true)
		.css('display', 'none');
}

function configurarCamposParaAdicionarCotacao() {
  tab = 'aba-cotacoes';
	$('#titulo-cadatrar-editar').text(`Adicionar Cotação (Solicitação #${idSolicitacao})`);

	botaoEtapaSeguinte
    .attr('id', 'btn-salvar-cotacoes')
    .removeClass('btn-primary')
    .addClass('btn-success')
    .html('Salvar');

	abaSolicitacao.hide();
	abaProdutos.hide();
	abaCotacoes.hide();

	botaoEtapaAnterior
		.attr('disabled', true)
		.css('display', 'none');
}

function configurarCamposParaSelecionarCotacao() {
  tab = 'aba-cotacoes';
  $('#titulo-cadatrar-editar').text(
		`Selecionar Cotação (Solicitação #${idSolicitacao})`
  );

  $('#div-mensagem-aba-cotacao').html('');
  $('#div-adicionar-cotacao').css('display', 'none');

	botaoEtapaSeguinte
    .attr('id', 'btn-salvar-cotacao-selecionada')
    .removeClass('btn-primary')
    .addClass('btn-success')
    .html('Salvar');

	abaSolicitacao.hide();
	abaProdutos.hide();
	abaCotacoes.hide();

	botaoEtapaAnterior
		.attr('disabled', true)
		.css('display', 'none');
}

// Gerencia os steps do formulário
$(document).on('click', '.prev-step', function () {
	if (tab === 'aba-produtos') tab = 'aba-solicitacao';
	else if (tab === 'aba-cotacoes') tab = 'aba-produtos';

  ativarTab(tab);

	if (tab === 'aba-solicitacao') {
		botaoEtapaAnterior
			.attr('disabled', true)
			.css('display', 'none');
	}
  
  botaoEtapaSeguinte
    .removeAttr('id')
    .removeClass('btn-success')
    .addClass('btn-primary')
		.addClass('next-step')
    .html('Próximo');

	
	// gerenciamento dos botoes de anterior e proximo quando a ação é adicionar produto e cotação pelo setor de compras
	if (acao === 'adicionar_produto_cotacao') {
		if (tab === 'aba-produtos') {
			botaoEtapaAnterior
				.attr('disabled', true)
				.css('display', 'none');
		} 
		else {
			botaoEtapaAnterior
				.removeAttr("disabled")
				.css('display', 'block');
		}
	}
});

$(document).on('click', '.next-step', function () {
	if (tab === 'aba-solicitacao') {
		// Verificar se o formulário está válido
		const valido = $('#form-solicitacao').valid();
		if (valido) {
			tab = 'aba-produtos';
			ativarTab(tab);

			if (!cadastroPedido) {
				botaoEtapaSeguinte
					.attr('id', 'btn-salvar-solicitacao')
					.removeClass('btn-primary')
					.addClass('btn-success')
					.html('Salvar');
			}
		}
	}
	else if (tab === 'aba-produtos') {
		if (acao === 'adicionar_produto_cotacao' || cadastroPedido) {
			// Pega os dados dos produtos
			const produtos = gridProdutosOptions.api.getModel().rowsToDisplay.map((row) => {
				return {
					codigo: row.data.codigo,
					descricao: row.data.descricao,
					quantidade: parseInt(row.data.quantidade) || 0,
					valorUnitario: parseFloat(row.data.valorUnitario) || 0,
					valorTotal: parseFloat(row.data.valorTotal) || 0,
				};
			});

			if (produtos.length === 0) {
				showAlert('error', 'Adicione ao menos um produto na solicitação');
				return;
			}

			tab = "aba-cotacoes";
			ativarTab(tab);

			botaoEtapaSeguinte
				.attr('id', acao === 'adicionar_produto_cotacao' ? 'btn-salvar-produtos-cotacoes' : 'btn-salvar-solicitacao')
				.removeClass('btn-primary')
				.addClass('btn-success')
				.html('Salvar');
		}
		else {
			botaoEtapaSeguinte
				.attr('id', 'btn-salvar-solicitacao')
				.removeClass('btn-primary')
				.addClass('btn-success')
				.html('Salvar');
		}
	}
	
	if (!['selecionar_cotacao', 'adicionar_cotacao'].includes(acao)) {
		botaoEtapaAnterior
			.removeAttr("disabled")
			.css('display', 'block');	
	}

});

// Salvar solicitacao
$(document).on("click", "#btn-salvar-solicitacao", async function (e) {
	e.preventDefault();

	const botao = $(this);
	const possuiProduto = !$("#produto-nao-encontrado").is(":checked");

	//Pega os dados da solicitacao
	const formData = new FormData($("#form-solicitacao")[0]);
	formData.set("capex", formData.get("capex") === "on" ? "sim" : "nao");
	formData.set("rateio", formData.get("rateio") === "on" ? "sim" : "nao");
	formData.set("centro_custo", $("#centro_custo").val());
	formData.set("tipo", tipoSolicitacao);

	// Pega os dados dos produtos
	let produtos = [];
	if (cadastroPedido || (cadastroRequisicao && possuiProduto)) {
		produtos = gridProdutosOptions.api.getModel().rowsToDisplay.map((row) => {
			return {
				codigo: row.data.codigo,
				descricao: row.data.descricao,
				quantidade: parseInt(row.data.quantidade) || 0,
				valorUnitario: parseFloat(row.data.valorUnitario) || 0,
				valorTotal: parseFloat(row.data.valorTotal) || 0,
			};
		});

		if (produtos.length === 0) {
			showAlert('error', 'Adicione ao menos um produto na solicitação');
			return;
		}
	}

	// Pegar os dados das cotacoes
	if (cadastroPedido) {
		const dadosCotacoes = buscaDadosEValidaCotacoes();
		if (!dadosCotacoes) return;

		const { idCotacao, idsCotacoes } = dadosCotacoes;
		formData.set('id_cotacao', idCotacao);
		formData.set("ids_cotacoes", JSON.stringify(idsCotacoes));
		formData.set("motivo_selecao_cotacao", $('#motivo_selecao_cotacao').val() || '');
		formData.set('motivo_cotacao', $('#motivo_cotacao').val() || '');
	}

	formData.set("produtos", JSON.stringify(produtos));

	let url = `${SITE_URL}/PortalCompras/Solicitacoes/salvarDados`;
	if (acao === "editar") {
		formData.set('possuiAnexoSolicitacao', possuiAnexoSolicitacao ? 'sim' : 'nao')
		url = `${SITE_URL}/PortalCompras/Solicitacoes/editarDados/${idSolicitacao}`;
	}

	botao
		.prop("disabled", true)
		.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;

			if (data.status == 1) {
				if (acao === "cadastrar") {
					// Reseta os campos
					resetarCampos();
					showAlert("success", data.mensagem);

					botao
						.removeAttr("id")
						.removeAttr("disabled")
						.removeClass("btn-success")
						.addClass("btn-primary")
						.html("Próximo");
				}
				else {
					// Redireciona para a página de solicitações
					showAlert("success", "Solicitação editada com sucesso, redirecionando...");
					setTimeout(() => {
						window.location.href = `${SITE_URL}/PortalCompras/Solicitacoes/index/requisicao/${idSolicitacao}`;
					}, 2000);
				}
			}
			else {
				showAlert("error", data.mensagem);
				botao.removeAttr("disabled").html("Salvar");
			}
		})
		.catch((error) => {
			console.error(error);
			showAlert("error", lang.erro_inesperado);
			botao.removeAttr("disabled").html("Salvar");
		})
		.finally(() => {
			$('#motivo_selecao_cotacao').val('');
			$('#motivo_cotacao').val('');
		});
});

function buscaDadosEValidaCotacoes() {
	let idCotacao = 0;
	let idsCotacoes = [];
	let quantidadeSelecionados = 0;
	let valorTotalCotacaoSelecionada = 0;
	const valoresCotacoes = [];

	gridCotacoesOptions.api.getModel().rowsToDisplay?.forEach(row => {
		valoresCotacoes.push(row.data.valorTotal);
		idsCotacoes.push(row.data.id);

		if (row.selected) {
			idCotacao = row.data.id;
			quantidadeSelecionados += 1;
			valorTotalCotacaoSelecionada = parseFloat(row.data.valorTotal);
		}
	});

	if (acao && ['cadastrar', 'editar', 'adicionar_cotacao', 'adicionar_produto_cotacao'].includes(acao)) {
		//validacoes de quantidades de contacoes cadastradas
		if (idsCotacoes.length === 0) {
			showAlert('error', 'Adicione ao menos uma cotação');
			return;
		}
	
		const motivoCotacao = $('#motivo_cotacao').val();
		if (idsCotacoes.length <= 2 && !motivoCotacao) {
			$('#modal-salvar-motivo-cotacao').modal('show');
			return;
		}
	}

	if (acao && ['cadastrar', 'selecionar_cotacao'].includes(acao)) {
		// validacoes da cotacao selecionada
		if (quantidadeSelecionados < 1 || !idCotacao) {
			showAlert('error', 'Selecione uma cotação');
			return;
		}
	
		if (quantidadeSelecionados > 1) {
			showAlert('error', 'Selecione apenas uma cotação');
			return;
		}

		let cotacaoMenorValor = true;
		valoresCotacoes.forEach(valor => {
			if (valorTotalCotacaoSelecionada > parseFloat(valor)) cotacaoMenorValor = false;
		});
	
		const motivoSelecaoCotacao = $('#motivo_selecao_cotacao').val();
	
		if (!cotacaoMenorValor && !motivoSelecaoCotacao) {
			$('#modal-motivo-selecao-cotacao').modal('show');
			return;
		}
	}

	return { idsCotacoes, idCotacao };
}

function resetarCampos() {
  limparCamposSolicitacao();
  limparCamposProdutos();
  limparCamposCotacoes();

  $('#total-solicitacao').text('R$ 0,00');

  tab = 'aba-solicitacao';
  ativarTab(tab);

	botaoEtapaAnterior
		.attr("disabled", true)
		.css("display", "none");

	$("#produto-nao-encontrado").prop("checked", false);
	$(".campos-add-produtos").css("display", "block");
}

