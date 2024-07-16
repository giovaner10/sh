$(document).on('click', '.alterarStatus', async function (e) {
    e.preventDefault();

    var botao = $(this);
    const htmlBotao = botao.html();

    // //PEGA OS DADOS DA LINHA CLICADA
    linhaClicada = tabelaAnuncios.row($(this).closest('tr'));
    let dadosLinha = linhaClicada.data();
    const idAnuncio = dadosLinha.id;
    
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    
    const uri = `shownet/vendasgestor/anuncios/${idAnuncio}/alterarStatus`;
    await axiosNode.patch(uri, {})
        .then(async resposta => {
            //Atualiza o status do anuncio na tabela
            const dados = resposta.data?.dados;
            dadosLinha.status = dadosLinha.status === 'ativo' ? 'inativo' : 'ativo';
            linhaClicada.data(dadosLinha).draw(false);

            alert(dados.mensagem);
        })
        .catch((erro) => {
            const resposta = erro?.response?.data || erro?.data?.dados;
            const mensagemErro = resposta.erro?.details?.[0]?.message || resposta?.erro?.mensagem || resposta?.mensagem || lang.operacao_nao_finalizada;
            alert(mensagemErro);
        })
        .finally(() => {
            botao.html(htmlBotao).attr('disabled', false);
        })
});