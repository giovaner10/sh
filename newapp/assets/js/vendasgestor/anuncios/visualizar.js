$(document).on('click', '.visualizarAnuncio', async function (e) {
    e.preventDefault();

    var botao = $(this);
    const htmlBotao = botao.html();

    // //PEGA OS DADOS DA LINHA CLICADA
    const linhaClicada = tabelaAnuncios.row($(this).closest('tr'));
    const idAnuncio = linhaClicada.data().id;
    
    limparModalVisualizacaoAnuncio();
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    
    const uri = `shownet/vendasgestor/anuncios/${idAnuncio}/visualizar`;
    await axiosNode.get(uri)
        .then(async resposta => {
            const anuncio = resposta.data?.dados;
            await preencherModalVisualizacaoAnuncio(anuncio);
            $('#modalVisualizarAnuncio').modal('show');
        })
        .catch((erro) => {
            const resposta = erro?.response?.data || erro?.data?.dados;
            const mensagemErro = resposta.erro?.details?.[0]?.message || resposta?.erro?.mensagem || resposta?.mensagem || lang.operacao_nao_finalizada;
            alert(mensagemErro);
        })
        .finally(() => {
            botao.html(htmlBotao).attr('disabled', false);
        });
});

async function preencherModalVisualizacaoAnuncio(anuncio) {
    if (anuncio) {
        $('#vis_titulo').text(anuncio.titulo);
        $('#vis_descricao').text(anuncio.descricao);
        $('#vis_dataInicio').text(anuncio.dataInicio ? dayjs(anuncio.dataInicio).format('DD/MM/YYYY HH:mm:ss') : '');
        $('#vis_dataFim').text(anuncio.dataFim ? dayjs(anuncio.dataFim).format('DD/MM/YYYY HH:mm:ss') : '');
        $('#vis_produto').text(anuncio.nomeProduto);

        
        if (anuncio.caminhoMidia.indexOf("mp4") != -1) {
            $('#vis_previaMidiaVideo').removeClass('hide');
            $('#vis_previaMidiaVideo').attr('src', anuncio.caminhoMidia);
            $('#vis_previaMidiaImagem').addClass('hide');
        }
        else {
            $('#vis_previaMidiaImagem').attr('src', anuncio.caminhoMidia);
        }
    }
}

function limparModalVisualizacaoAnuncio() {
    $('#vis_titulo').text('');
    $('#vis_descricao').text('');
    $('#vis_dataInicio').text('');
    $('#vis_dataFim').text('');
    $('#vis_produto').text('');

    $('#vis_previaMidiaImagem').attr('src', imgPadraoUpload120x150).removeClass('hide');
    $('#vis_previaMidiaVideo').addClass('hide').attr('src', '');
}