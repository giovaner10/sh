$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#table_centrais')) {
        return tableCentrais.destroy();
    }
    tableCentrais = $('#table_centrais').DataTable({
        paging: true,
        searching: true,
        responsive: true,
        processing: true,
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        ordering: false,
        lengthChange: false,
    });

    $(document).on('click', '.desativarCentral', function () {
        var button = $(this);
        var id = button.data('id');
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>')
        $.ajax({
            url: site_url + '/clientes/desativar_central',
            data: {id: id},
            type: 'post',
            dataType: 'json',
            success: function (callback) {
                if (callback === true){
                    tableCentrais.ajax.reload(null, false);
                    button.removeClass('btn-success').addClass('btn-danger').removeAttr('disabled').html('<i class="fa fa-ban"></i>')
                }
            }
        })
        return false;
    })

    $(document).on('click', '.ativarCentral', function () {
        var button = $(this);
        var id = button.data('id');
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>')
        $.ajax({
            url: site_url + '/clientes/ativar_central',
            data: {id: id},
            type: 'post',
            dataType: 'json',
            success: function (callback) {
                if (callback === true){
                    tableCentrais.ajax.reload(null, false);
                   button.removeClass('btn-danger').addClass('btn-success').removeAttr('disabled').html('<i class="fa fa-check"></i>');
                }
            },
        })
        return false;
    })
})