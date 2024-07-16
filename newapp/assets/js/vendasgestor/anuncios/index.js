$(document).ready(function () {

    //INSTANCIA A TABELA 
    tabelaAnuncios = $('#tabelaAnuncios').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        order: [0, 'desc'],
        columns: [
            { data: 'id' },
            { data: 'titulo' },
            { data: 'descricao' },
            { data: 'nomeProduto' },
            {
                data: 'dataInicio',
                render: function (data, type, row, meta) {
                    return data ? dayjs(data).format('DD/MM/YYYY HH:mm:ss') : '';
                }
            },
            {
                data: 'dataFim',
                render: function (data, type, row, meta) {
                    return data ? dayjs(data).format('DD/MM/YYYY HH:mm:ss') : '';
                }
            },
            {
                data: 'dataCadastro',
                render: function (data, type, row, meta) {
                    return data ? dayjs(data).format('DD/MM/YYYY HH:mm:ss') : '';
                }
            },
            {
                data: 'status',
                render: function (data, type, row, meta) {
                    return data === 'ativo' ? lang.ativo : lang.inativo;
                }
            },
            {
                data: 'acoes',
                orderable: false,
                className: 'colunaAcoes',
                render: function (data, type, row, meta) {
                    return /*html*/`
                        <button 
                            class="btn btn-mini btn-primary visualizarAnuncio botoesAcao" 
                            title="${ lang.visualizar }" >
                            <i class="fa fa-eye"></i>
                        </button>
                        <button 
                            class="btn btn-mini btn-primary editarAnuncio botoesAcao" 
                            title="${ lang.editar }" 
                            data-acao="editar" >
                            <i class="fa fa-edit"></i>
                        </button>
                        <button 
                            class="btn btn-mini btn-primary alterarStatus botoesAcao" 
                            title="${ row.status === 'ativo' ? lang.inativar : lang.ativar }" >
                            <i class="fa ${ row.status === 'ativo' ? 'fa-remove' : 'fa-check' }"></i>
                        </button>`;
                }
            }
        ],
        buttons: [
            {
                filename: filenameGenerator(lang.anuncios),
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-outline-primary',
                text: 'PDF',
                customize: function (doc, tes) {
                    let titulo = lang.anuncios;
                    // Personaliza a página do PDF
                    pdfTemplate(doc, titulo)
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                title: lang.anuncios,
                extend: 'excelHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-outline-primary',
                text: 'EXCEL',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                title: lang.anuncios,
                extend: 'csvHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-outline-primary',
                text: 'CSV',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                filename: filenameGenerator(lang.anuncios),
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-outline-primary',
                text: lang.imprimir.toUpperCase(),
                customize: function (win) {
                    let titulo = lang.anuncios;
                    // Personaliza a página Impressa
                    printTemplate(win, titulo);
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            }
        ],
        language: lang.datatable
    });

    requisitarListaAnuncios();
});

/**
 * @param element elemento - elemento clicado
*/
async function requisitarListaAnuncios() {
    efeitoBuscarDadosDatatable('tabelaAnuncios');

    const uri = `shownet/vendasgestor/anuncios/listar/`; 
    return await axiosNode.get(uri)
        .then(resposta => {
            const data = resposta?.data;
            if (data?.status === 1) {
                // Carrega os anuncios na tabela
                tabelaAnuncios.clear();
                tabelaAnuncios.rows.add(data.dados);
                tabelaAnuncios.draw();
            }
            else {
                tabelaAnuncios.clear();
                tabelaAnuncios.draw();
                alert(lang.operacao_nao_finalizada);
            }
        })
        .catch((error) => {
            tabelaAnuncios.clear();
            tabelaAnuncios.draw();

            const data = error?.response?.data;
            const mensagemErro = data?.erro?.details[0]?.message || data?.erro?.mensagem || data?.mensagem || lang.operacao_nao_finalizada;
            alert(mensagemErro);
        })
}