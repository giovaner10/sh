$(document).ready(() => {
    
    $(document).on('change', '#midia-anuncio', function (e) {
        e.preventDefault();
        previsualizarMidiaAnuncio();
    });

    //CARREGA A TABELA COM OS DADOS FILTRADOS
    $('#formularioCadastrarEditarAnuncio').submit(function (e) {
        e.preventDefault();
        requisitarCadastrarEditarAnuncio();
    });

    construirOptionsSelectProdutos();

    $(document).on('change', '#dataInicio', function (e) {
        e.preventDefault();
        controlarMinMaxPeriodoAnuncio();
    });

    $(document).on('change', '#dataFim', function (e) {
        e.preventDefault();
        controlarMinMaxPeriodoAnuncio();
    });
});

function controlarMinMaxPeriodoAnuncio() {
    const dataInicio = $('#dataInicio')
        .attr('min', dayjs()
        .format('YYYY-MM-DD HH:mm'));

    const dataFim = $('#dataFim');

    dataInicio.attr('min', dayjs().format('YYYY-MM-DD HH:mm'))
        .attr('max', dataFim.val());

    dataFim.attr('min', dataInicio.val());
}

async function construirOptionsSelectProdutos() {
  let options = `<option value="" selected></option>`;
  produtos = await requisitarListaProdutos();
  if (produtos.length) {
    produtos.forEach(produto => {
        options += /*html*/`
            <option value="${produto.id}">${produto.nome}</option>
        `;
    });
  }

  $('#selectProdutos').html(options).attr('disabled', false);
  $('#selectProdutos').select2({
        width: '100%',
        placeholder: lang.selecione_produto,
        language: idioma
    });

}

async function requisitarListaProdutos() {
    const uri = `shownet/produtos/listar?status=1`; 
    return await axiosNode.get(uri)
        .then(resposta => {
            return resposta?.data?.dados || [];
        })
        .catch((error) => {
            const data = error?.response?.data;
            const mensagemErro = data?.erro?.details[0]?.message || data?.erro?.mensagem || data?.mensagem || lang.operacao_nao_finalizada;
            console.log(mensagemErro);

            return [];
        })
}

async function requisitarCadastrarEditarAnuncio() {
    var botao = $('#btnSalvarAnuncio');
    
    const formData = new FormData($('#formularioCadastrarEditarAnuncio')[0]);
    formData.set('dataInicio', formData.get('dataInicio').replace('T', ' ') + ':59');
    formData.set('dataFim', formData.get('dataFim').replace('T', ' ') + ':59');
    
    const acao = botao.attr('data-acao');
    const idAnuncio = botao.attr('data-idAnuncio');
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
    
    let uri = `shownet/vendasgestor/anuncios/cadastrar`;
    if (acao === 'editar') {
        uri = `shownet/vendasgestor/anuncios/${idAnuncio}/editar`;
    }
    await axiosNodeFormData.post(uri, formData)
        .then(async resposta => {
            let dados = resposta?.data.dados;
            const nomeProduto = $("#selectProdutos option:selected").text();

            if (acao === 'editar') {
                let dadosLinha = linhaClicada.data();

                dadosLinha = {
                    ...dadosLinha,
                    titulo: formData.get('titulo'),
                    descricao: formData.get('descricao'),
                    dataInicio: formData.get('dataInicio'),
                    dataFim: formData.get('dataFim'),
                    nomeProduto: nomeProduto,
                }
                
                //Atualiza o status do anuncio na tabela
                linhaClicada.data(dadosLinha).draw(false);

                alert(dados.mensagem);
            }
            else {
                const novoAnuncio = {
                    id: dados.id,
                    titulo: formData.get('titulo'),
                    descricao: formData.get('descricao'),
                    nomeProduto: nomeProduto,
                    dataInicio: formData.get('dataInicio'),
                    dataFim: formData.get('dataFim'),
                    dataCadastro: dayjs().format('YYYY-MM-DD HH:mm:ss'),
                    status: 'ativo',
                }
                //Adiciona o anuncio na tabela
                tabelaAnuncios.rows.add([novoAnuncio]);
                tabelaAnuncios.draw();

                alert(lang.anuncio_cadastrado_sucesso);
                resetarModalCadastrarEditarAnuncio();
                $('#modalCadastrarEditarAnuncio').modal('hide');
            }
        })
        .catch((erro) => {
            const resposta = erro?.response?.data || erro?.data?.dados;
            const mensagemErro = resposta.erro?.details?.[0]?.message || resposta?.erro?.mensagem || resposta?.mensagem || lang.operacao_nao_finalizada;
            alert(mensagemErro);
        })
        .finally(() => {
            botao.html(lang.salvar).attr('disabled', false);
        })
}

function resetarModalCadastrarEditarAnuncio() {
    $("#formularioCadastrarEditarAnuncio")[0].reset();
    
    $('#previa-midia-imagem').removeClass('hide');
    $('#previa-midia-imagem').attr('src', imgPadraoUpload120x150);
    $('#previa-midia-video').addClass('hide').attr('src', '');
    $("#selectProdutos").val('').trigger('change');
    $('#midia-anuncio').val('');

    $('#btnSalvarAnuncio').attr('data-acao', 'cadastrar');
    $('#btnSalvarAnuncio').removeAttr('data-idAnuncio');

    $('#tituloModalCadastrarEditarAnuncio').text(lang.cadastrar_anuncio);
}

/**
 * Carrega uma pre-visualizacao da imagem selecionada para a foto do cpf do profissional
*/
function previsualizarMidiaAnuncio() {
    $('#previa-midia-imagem').removeClass('hide').attr('src', '');
    $('#previa-midia-video').addClass('hide').attr('src', '');

    let uploadField = document.querySelector("#midia-anuncio");
    if (uploadField) {
        const extensoes_suportadas = ['image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];
        if (uploadField?.files?.[0]?.size > (1024 * 1024 * 5)) {  //limita o tamanho da midia em 5mb
            alert(lang.limite_tam_arq_5m);
            uploadField.value = "";
        }
        else if (extensoes_suportadas.indexOf(uploadField?.files?.[0]?.type) === -1) {
            alert(lang.msg_formatos_suportados_imagem_video);
            uploadField.value = "";
        }
        else {
            if (uploadField?.files?.[0]?.type === 'video/mp4') {
                $('#previa-midia-imagem').addClass('hide');
                $('#previa-midia-video').removeClass('hide')
                    .attr('src', window.URL.createObjectURL(uploadField.files[0]));
            }
            else {
                $('#previa-midia-imagem').attr('src', window.URL.createObjectURL(uploadField.files[0]));
            }

        }
    }
}


// Edita um anuncio
$(document).on('click', '.editarAnuncio', async function (e) {
    e.preventDefault();

    var botao = $(this);
    const htmlBotao = botao.html();

    // //PEGA OS DADOS DA LINHA CLICADA
    linhaClicada = tabelaAnuncios.row($(this).closest('tr'));
    const idAnuncio = linhaClicada.data().id;

    resetarModalCadastrarEditarAnuncio();
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    const uri = `shownet/vendasgestor/anuncios/${idAnuncio}/visualizar`;
    await axiosNode.get(uri)
        .then(async resposta => {
            const anuncio = resposta.data?.dados;
            await preencherModalCadastrarEdicarAnuncio(anuncio);
            $('#modalCadastrarEditarAnuncio').modal('show');
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

async function preencherModalCadastrarEdicarAnuncio(anuncio) {
    if (anuncio) {
        $('#titulo').val(anuncio.titulo);
        $('#descricao').val(anuncio.descricao);
        $('#dataInicio').val(anuncio.dataInicio ? dayjs(anuncio.dataInicio).format('YYYY-MM-DD HH:mm') : '');
        $('#dataFim').val(anuncio.dataFim ? dayjs(anuncio.dataFim).format('YYYY-MM-DD HH:mm') : '');
        $('#selectProdutos').val(anuncio.idProduto).trigger('change');


        if (anuncio.caminhoMidia.indexOf("mp4") != -1) {
            $('#previa-midia-video').removeClass('hide');
            $('#previa-midia-video').attr('src', anuncio.caminhoMidia);
            $('#previa-midia-imagem').addClass('hide');
        }
        else {
            $('#previa-midia-imagem').attr('src', anuncio.caminhoMidia);
        }

        $('#btnSalvarAnuncio').attr('data-acao', 'editar');
        $('#btnSalvarAnuncio').attr('data-idAnuncio', anuncio.id);
        
        $('#tituloModalCadastrarEditarAnuncio').text(lang.editar_anuncio);
    }
}
