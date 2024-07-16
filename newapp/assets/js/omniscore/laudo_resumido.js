

function resetaStatusLaudoResumido() {
    $('#pagina_laudo_resumido').css('display', 'none');
    $('#print-content_resumido').css('display', 'none');
    $('#resultadoLaudoResumido').text('');
    $('.logoCliente').removeAttr('src');
}

/**
 * Gera o laudo da consulta
*/
$(document).on('click', '#btn_gerar_laudo_resumido', function (e) {
    e.stopPropagation();
    
    let botao = $(this);

    if (navigator.userAgent.search("Firefox") >= 0) {
        alert(lang.msg_preferencia_browser);
        return false;
    }

    $.ajax({
        url: site_url + '/PerfisProfissionais/carrega_laudo_resumido/' + id_log,
        dataType: "json",
        beforeSend: function () {
            resetaStatusLaudoResumido();
            botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.gerando_laudo);
        },
        success: function (data) {
            let profissional = data.profissional;
            let protocolo = data.protocolo;
            let idConsulta = data.id_consulta;
            let cliente = data.cliente;
            let tipo_profissional = data.tipo_profissional;
            let score = data.score;
            let ponto_corte = data.ponto_corte;
            let data_consulta = data.data_consulta;
            let consta_obito = data.consta_obito;
            let nome_usuario = data.nome_usuario;
            let vencimento = data.vencimento;

            //PLOTA OS DADOS DO PROFISSIONAL
            $('#titulo_laudo_resumido').text(lang.laudo_resumido.toUpperCase());
            $('#cpf_laudo_resumido').text(profissional.cpf);
            $('.nome_laudo_resumido').text(profissional.nome);
            $('#rg_laudo_resumido').text(profissional.rg);

            let logo = cliente.logotipo ? cliente.logotipo : base_url + 'media/img/logo_omniscore.png';
            $('.logoCliente').attr('src', logo);

            toDataURL(logo, function (data) {
                logoOmniscore = data;
            });

            if (tipo_profissional === 'funcionario') {
                $('#tipo_profissional_laudo_resumido').text(lang.funcionario);
            }
            else if (tipo_profissional === 'agregado') {
                $('#tipo_profissional_laudo_resumido').text(lang.funcionario_agregado);
            }
            else if (tipo_profissional === 'motorista') {
                $('#tipo_profissional_laudo_resumido').text(lang.funcionario_motorista);
            }
            else if (tipo_profissional === 'autonomo') {
                $('#tipo_profissional_laudo_resumido').text(lang.funcionario_autonomo);
            }

            let qrCode = GeraQRCode('consulta_' + idConsulta, '125', '125');
            $('#qrcode_laudo_resumido').html(qrCode);
            $('.data_consulta_laudo_resumido').text(data_consulta);

            $('#protocolo_laudo_resumido').text(protocolo);
            $('#cliente_laudo_resumido').text(cliente.nome);
            $('#nomeUsuario_laudo_resumido').text(nome_usuario);
            $('#vencimento_laudo_resumido').text(vencimento);

            if (score) {
                if (consta_obito === 'sim') {
                    score = '100';
                    $('#resultadoLaudoResumido').text(lang.nao_indicado.toUpperCase());
                }
                else {
                    if (parseFloat(score) < parseFloat(ponto_corte)) {
                        $('#resultadoLaudoResumido').text(lang.nao_indicado.toUpperCase());
                    }
                    else {
                        $('#resultadoLaudoResumido').text(lang.indicado.toUpperCase());
                    }
                }
            }

            $('#score_laudo_resumido').text(score + ' / 1000');

            //CONVERTE O HTML EM PDF
            setTimeout(() => {
                exporta_pdf_resumido(protocolo, cliente.nome, cliente.cpf_cnpj, botao);
            }, 2000);

        },
        error: function () {
            alert(lang.falha_na_requisicao);
            $('#pagina_laudo_resumido').css('display', 'none');
            $('#print-content_resumido').css('display', 'none');
            botao.attr('disabled', false).html(lang.laudo_resumido);
        }
    });


})

//CONVERTE O HTML PARA PDF E O EXPORTA
function exporta_pdf_resumido(protocolo, nome_cliente, cpf_cnpj, botao) {
    
    $('#pagina_laudo_resumido').css('display', 'block');
    $('#print-content_resumido').css('display', 'block');

    let element = document.getElementById('print-content_resumido');

    let opt = {
        filename: lang.laudo_resumido,
        html2canvas: {
            scale: 5,
            dpi: 192,
            useCORS: true
        },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '.quebra-pagina' },
        image: { type: 'jpeg', quality: 0.9 },
        margin: [20, 10, 20, 10]
    };

    html2pdf().from(element).set(opt).toPdf().get('pdf').then(function (pdf) {
        var totalPages = pdf.internal.getNumberOfPages();

        var gestor = 'GESTOR';
        var site = 'https://gestor.showtecnologia.com';

        var empresa = gestor + '  -  ' + site;
        const data_atual = new Date().toLocaleString('pt-BR');

        //cria a imagem para servir de marca dagua
        let identCliente = nome_cliente + '\n' + cpf_cnpj;
        let imageBack = marcaDaguaText(identCliente.toUpperCase());

        for (let i = 1; i <= totalPages; i++) {
            pdf.setPage(i);
            pdf.setFontSize(7);

            //Se a pagina nao for a capa do laudo
            if (i != 1) {
                //monta o head
                pdf.text(lang.laudo_resumido, pdf.internal.pageSize.getWidth() - 40, 10);
                pdf.text(data_atual, pdf.internal.pageSize.getWidth() - 40, 13);
                pdf.addImage(logoOmniscore, "JPG", 12, 7, 35, 8);
                pdf.text('___________________________________________________________________________________________________________________________________________', 10, 16);

                //monta o footer
                pdf.text('___________________________________________________________________________________________________________________________________________', 10, pdf.internal.pageSize.getHeight() - 20);
                pdf.text(lang.emitido_exclusivamente_para.toUpperCase() + ' ' + nome_cliente.toUpperCase() + ' - ' + cpf_cnpj, 10, pdf.internal.pageSize.getHeight() - 14);
                pdf.text(empresa, 10, pdf.internal.pageSize.getHeight() - 11);
                pdf.text(lang.protocolo + ': '+ protocolo, 10, pdf.internal.pageSize.getHeight() - 8);
                pdf.text(lang.pagina + ' ' + i + ' ' + lang.de + ' ' + totalPages, pdf.internal.pageSize.getWidth() - 25, pdf.internal.pageSize.getHeight() - 8);

                //plota a marca dagua pela pagina
                let y = 20;
                while (y <= 260) {
                    let x = identCliente.length;
                    pdf.addImage(imageBack, "PNG", 10, y);
                    pdf.addImage(imageBack, "PNG", 2.5 * x + 10, y);
                    pdf.addImage(imageBack, "PNG", 5 * x + 10, y);
                    y = y + 10;
                }
            }
            else {
                pdf.text(site, pdf.internal.pageSize.getWidth() - 47, pdf.internal.pageSize.getHeight() - 10);
            }
        }

    }).save();

    setTimeout(() => {
        $('#pagina_laudo_resumido').css('display', 'none');
        $('#print-content_resumido').css('display', 'none');
        botao.html(lang.laudo_resumido).attr('disabled', false);
    }, 4000);

}