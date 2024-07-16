
/*
* Funcao: seleciona numero de linhas visualizadas por pagina
*/
function onPageSizeChanged(newPageSize) {
    var value = document.getElementById('page-size').value;
    gridOptions.api.paginationSetPageSize(Number(value));
}

/*
* Funcao: pesquisar na tabela
*/
function onFilterTextBoxChanged() {
    gridOptions.api.setQuickFilter(document.getElementById('filter-text-box').value);
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
function adicionarRegistroNaAgGrid(dadosNovos, colunaOrdenacao='id', ordernacao='desc') {
    gridOptions.api.applyTransaction({ add: dadosNovos, });
    //ordena a grid
    gridOptions.columnApi.applyColumnState({ state: [{ colId: colunaOrdenacao, sort: ordernacao }], });
}


/*
* funcao interna responsavel por atualizar o registro na grid
* dadosAtualizados eh um objeto com os dados atualizados do registro
exemplo: 
 dadosAtualizados = {
    code: code,
    nome_usuario: nome,
    usuario: email
}
*/
function atualizarRegistroNaAgGrid(idRow, dadosAtualizados) {
    var displayModel = gridOptions.api.getModel();
    let rowNode = displayModel.rowsToDisplay[idRow];
    rowNode.setData(dadosAtualizados);
}