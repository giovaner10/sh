var tableInstalacoes = $('#tabelaRelatorioQuantidadeInstalacoes').DataTable({
    ordering: false,
    searching: true,
    dom: 'lrtip',
    info: false,
    responsive: true,
    processing: false,
    lengthChange: false,
    columnDefs: [{
        "className": "dt-center",
        "targets": "_all"
    }],
    columns: [
        {data: 'id'},
        {data: 'idCliente'},
        {data: 'veiculo'},
        {data: 'dataInstalacao'},
        {data: 'placa'},
        {data: 'prefixo'},
        {data: 'taxi', render: function(data) { return data ? 'Sim' : 'Não'; }},
        {data: 'serial'},
        {data: 'marca'},
        {data: 'modelo'}
    ],
    language: lang.datatable
});

var resultadoOriginal = [];

function carregarTabela(id_cliente) {
    $.ajax({
        url: relatorio_placas_contrato_url + "/" + id_cliente,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.data && response.data.status === 200) {
                resultadoOriginal = response.data.results;
                tableInstalacoes.clear().draw();
                tableInstalacoes.rows.add(resultadoOriginal).draw();
            } else {
                tableInstalacoes.clear().draw();
            }
        },
        error: function() {
            tableInstalacoes.clear().draw();
        }
    });
}

function filtrarDados(dataInicial, dataFinal) {
    var fim = dataFinal ? new Date(dataFinal) : new Date();
    var inicio = dataInicial ? new Date(dataInicial) : new Date(fim.getFullYear(), fim.getMonth() - 3, fim.getDate());
    
    var dadosFiltrados = resultadoOriginal.filter(function(item) {
        var dataInstalacao = new Date(item.dataInstalacao.split('/').reverse().join('-'));
        return dataInstalacao >= inicio && dataInstalacao <= fim;
    });
    tableInstalacoes.clear().draw();
    tableInstalacoes.rows.add(dadosFiltrados).draw();
}

$(document).ready(function() {
    $('#pesquisaclienteRelatorio').on('click', function() {
        var dataInicial = $('#pesqDataInicialClienteRelatorio').val();
        var dataFinal = $('#pesqDataFinalClienteRelatorio').val();

        if(!dataInicial || !dataFinal){
            showAlert("warning", 'Preencha os campos de data para filtrar.');
            return;
        }

        if(new Date(dataInicial) > new Date(dataFinal)){
            showAlert("warning", 'A data inicial não pode ser maior que a data final.');
            return;
        }
        
        filtrarDados(dataInicial, dataFinal);
    });
});
