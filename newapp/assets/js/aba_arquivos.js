$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#tableFiles')) {
        return ;
    }
    // TABELA DE TICKETS
    tableArquivos = $('#tableFiles').DataTable({
        responsive: true,
        columns: [
            { data: 'nome_arquivos' },
            { data: 'link' },
            { data: 'descricao' },
            {
                data: 'id',
                render: function(data, type, row, meta){
                    return `<a href="${row['link']}" download class="btn btn-mini btn-primary" title="Baixar arquivo"><i class="fa fa-download"></i></a>
                            <a class="deleteFile btn btn-mini btn-danger" data-id="${row['id']}" title="Deletar"><i class="fa fa-trash"></i></a>`;
                }
            }
        ],
        columnDefs: [
            {
                targets: '_all',
                className: 'dt-center'
            }
        ],
        dom: 'Bfrtip',
        language: langDatatable,
        paging: true,
        searching: true,
        info: false,
        processing: true,

        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF'
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                },
                title: lang.tick,
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR'
            }
        ],
        language: lang.datatable,
    });
    $('.dt-buttons').css('margin-top', '10px');
});