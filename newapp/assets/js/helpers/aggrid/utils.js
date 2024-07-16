menuAberto = false;

// Expandir grid  
function expandirGrid() {
	menuAberto = !menuAberto;
	let buttonHide = `${BASE_URL}/assets/images/icon-filter-hide.svg`;
	let buttonShow = `${BASE_URL}/assets/images/icon-filter-show.svg`;
	if (menuAberto) {
		$(".img-expandir").attr("src", buttonShow);
		$("#menu-lateral").hide();
		$("#conteudo").removeClass("col-md-9");
		$("#conteudo").addClass("col-md-12");
	} else {
		$(".img-expandir").attr("src", buttonHide);
		$("#menu-lateral").show();
		$("#conteudo").css("margin-left", "0px");
		$("#conteudo").removeClass("col-md-12");
		$("#conteudo").addClass("col-md-9");
	}
}

const mensagemCarregamentoAgGrid = /*html*/`
  <span class="ag-overlay-loading-center" style="border-radius: 12px; border:none; padding: 10px;">
    <div clas="mt-2">${lang.carregando}</span>
  </span>`;


const mensagemAgGridSemDados = /*html*/`<span class="ag-overlay-loading-center" style="border-radius: 12px; border:none; padding: 10px;">Sem registros</span>`;


function selecionarQuantidadePorPagina(elemento) {
	var valor = $(elemento).val();
	gridOptions.api.paginationSetPageSize(Number(valor));
}

function pesquisarNaAggrid(elemento) {
	var valor = $(elemento).val();
	gridOptions.api.setQuickFilter(valor);
}

/**
 * Funcao responsavel por buscar um node de registro na ag grid
 * idRow eh o indice do registro que sera buscado
 * Exemplo de uso:
 * var node = buscarNodeNaAgGrid(0);
 * var data = node.data;
 */
function buscarNodeNaAgGrid(idRow) {
  return gridOptions.api.getRowNode(idRow);
}

/*
* Funcao responsavel por add um novo registro na ag grid
* dadosNovos eh um array de objetos com os dados do novo registro
exemplo: 
 dadosNovos = [{
		code: code,
		nome_usuario: nome,
		usuario: email
}]
*/
function adicionarRegistroNaAgGrid(dadosNovos, colunaOrdenacao = 'id', ordernacao = 'desc') {
	gridOptions.api.applyTransaction({ add: dadosNovos});
	//ordena a grid
	gridOptions.columnApi.applyColumnState({ state: [{ colId: colunaOrdenacao, sort: ordernacao }] });
}

/**
* funcao interna responsavel por atualizar o registro na grid
* dadosAtualizados eh um objeto com os dados atualizados do registro
* exemplo: 
*  dadosAtualizados = {
* 		code: code,
* 		nome_usuario: nome,
* 		usuario: email
* }
*/
function atualizarRegistroNaAgGrid(idRow, dadosAtualizados) {
	var displayModel = gridOptions.api.getModel();
	let rowNode = displayModel.rowsToDisplay[idRow];
	rowNode.setData(dadosAtualizados);
}

/**
 * Funcao responsavel por remover um registro na ag grid
 * idRow eh o indice do registro que sera removido
 */
function removerRegistroNaAgGrid(idRow) {
	var displayModel = gridOptions.api.getModel();
	let rowNode = displayModel.rowsToDisplay[idRow];
	gridOptions.api.applyTransaction({ remove: [rowNode.data] });
}


/** 
 * Funcao responsavel por criar uma mascara para uma celula da ag grid
 * Esta funcao deve ser chamada no momento que clicar na celula para editar
*/
function maskedCellEditor() {
	let input;
	const mascaras = {
		moeda: {
			mask: function (input) {
				$(input).mask("#.##0,00", {
					reverse: true
				});
			}
		}
	};

	return {
		init(params) {
			// cria a celula
			input = document.createElement('input');
			input.value = params.value;
			const mascara = params.mascara;
			if (mascara) {
				// aplica a masca
				mascaras?.[mascara]?.mask(input);
			}
		},
		getGui() {
			return input;
		},
		afterGuiAttached() {
			input.focus();
			input.select();
		},
		getValue() {
			return input.value;
		},
		destroy() { },
		isPopup() {
			return false;
		}
	};
}