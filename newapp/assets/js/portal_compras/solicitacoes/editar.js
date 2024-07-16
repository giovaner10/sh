// Carrega os dados da solicitação
function buscarDadosFormulario() {
	$('#overlay').css('display', 'block');

	// Busca os dados da solicitação
	const url = `${SITE_URL}/PortalCompras/Solicitacoes/buscar/${idSolicitacao}`;
	axios
		.get(url)
		.then((response) => {
			const data = response.data;
			popularFormulario(data);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		})
		.finally(() => {
			$('#overlay').css('display', 'none');
		});
}

function popularFormulario(data) {
  // Preenche os campos da solicitação
  const solicitacao = data.dados.solicitacao;
  $('#total-solicitacao').text(formatarParaMoedaBRL(solicitacao.valorTotal));
	valorTotalSolicitacao = parseFloat(solicitacao.valorTotal);

	const { filial, centroCusto } = solicitacao;
	$("#filial").val(filial.id).trigger('change');
	$("#centro_custo").val(centroCusto.codigo).trigger('change');
  $('#tipo_requisicao').val(solicitacao.tipoRequisicao).trigger('change');
  $('#motivo_compra').val(solicitacao.motivoCompra);

  $('#capex').prop('checked', solicitacao.capex == 'sim');
  $('#rateio').prop('checked', solicitacao.rateio == 'sim');
  
  if (solicitacao.rateio == 'sim' && solicitacao.anexoRateio) {
		const nomeArquivoRateio = solicitacao.anexoRateio.split('/').pop();
    let icon = solicitacao.anexoRateio.includes('.pdf') ? 'fa-file-pdf-o' : 'fa-file-excel-o';
    $('#viewAnexo-rateio')
      .removeAttr('disabled')
			.html(`<p class="truncade link-upload-valid"><i class="fa ${icon}"></i>  ${nomeArquivoRateio}</p>`);
		
		$('#visualizar-anexo-rateio').attr('href', BASE_URL + solicitacao.anexoRateio)
			.show();
  }

  if (solicitacao.anexoSolicitacao) {
		possuiAnexoSolicitacao = true;

		const nomeArquivo = solicitacao.anexoSolicitacao.split('/').pop();
    let icon = solicitacao.anexoSolicitacao.includes('.pdf') ? 'fa-file-pdf-o' : 'fa-file-image-o';
		$('#viewAnexo').html(`<p class="truncade link-upload-valid"><i class="fa ${icon}"></i>  ${nomeArquivo}</p>`);

		$('#visualizar-anexo-solicitacao').attr('href', BASE_URL + solicitacao.anexoSolicitacao)
			.show();
  }

  // Preenche os produtos
  const produtos = data.dados.produtos;
	if (produtos.length > 0) {
		produtos.forEach((produto) => {
			gridProdutosOptions.api.updateRowData({ add: [produto] });

			// Adiciona o produto na lista de produtos adicionados
			produtosBuscados[produto.codigo] = {
				codigo: produto.codigo,
				descricao: produto.descricao,
			};
		});

		meusProdutos = produtos;
		
		$('#produto-nao-encontrado').prop('checked', false);
		if(acao === 'editar') {
			$('.campos-add-produtos').css('display', 'block');
		}
	}
	else {
		$('#produto-nao-encontrado').prop('checked', true);
		if (acao === 'editar') {
			$('.campos-add-produtos').css('display', 'none');
		}
	}

  // Preenche as cotações
  const cotacoes = data.dados.cotacoes;
  if (cotacoes.length > 0) {
    cotacoes.forEach((cotacao) => {
      const { id, fornecedor, valorTotal, formaPagamento, condicaoPagamento } = cotacao;
      const documentoFornecedor = fornecedor.documento.length == 11
        ? criarMascara(fornecedor.documento, '###.###.###-##')
        : criarMascara(fornecedor.documento, '##.###.###/####-##');
    
      const minhaCotacao = {
				id: id,
        fornecedor: `${documentoFornecedor} - ${fornecedor.nome}`,
        valorTotal: valorTotal,
        formaPagamento: formaPagamento,
        condicaoPagamento: condicaoPagamento,
      };
    
      gridCotacoesOptions.api.updateRowData({ add: [minhaCotacao] });
    });
  }
}