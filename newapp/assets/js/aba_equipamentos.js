$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#equipamentos-disponiveis')) {
        return tableEqpDisponiveis.destroy();
    }
    tableEqpDisponiveis = $('#equipamentos-disponiveis').DataTable({
        paging: true,
        searching: true,
        responsive: true,
        processing: true,
        language: lang.datatable,
        ordering: false,
        lengthChange: false,
        columns: [
            {data: 'serial'},
            {data: 'numero'},
            {data: 'ccid'},
            {data: 'operadora'},
            {data: 'dataEnvio'},
            {data: 'dataRecebimento'},
            {
                data: 'dataRecebimento',
                render: function (data) {
                    if(data != ''){
                        return 'Disponível no cliente'
                    }else{
                        return 'Encaminhado ao cliente'
                    }
                }
            }
        ]
    });

    if ($.fn.DataTable.isDataTable('#equipamentos-retirados')) {
        return tableEqpRetirados.destroy();
    }
    tableEqpRetirados = $('#equipamentos-retirados').DataTable({
        paging: true,
        searching: true,
        responsive: true,
        processing: true,
        language: lang.datatable,
        ordering: false,
        lengthChange: false,
        columns: [
            {data: 'serial'},
            {data: 'placa'},
            {data: 'data_retirada'},
            {data: 'dataRecebimento',
                render: function (data) {
                    if(data != ''){
                        return 'Recebido'
                    }else{
                        return 'Entrega pendente'
                    }
                }
            }
        ]
    });

    if ($.fn.DataTable.isDataTable('#equipamentos-uso')) {
        return tableEqpUso.destroy();
    }
    tableEqpUso = $('#equipamentos-uso').DataTable({
        paging: true,
        searching: true,
        responsive: true,
        processing: true,
        language: lang.datatable,
        ordering: false,
        lengthChange: false,
        columns: [
            {data: 'placa'},
            {data: 'serial'},
            {data: 'numero'},
            {data: 'ccid'},
            {data: 'operadora'},
            {data: 'data_cadastro'},
        ]
    });

});