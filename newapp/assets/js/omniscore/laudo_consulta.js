var fontes_nomes = {
    'cpf': lang.receita_federal,
    'debitos': 'QUOD',
    'cnh': 'DENATRAM',
    'antecedentes': lang.policia_federal,
    'processos': lang.tribunais_justica,
    'mandados': "CNJ",
    'veiculo': 'DENATRAM',
    'carreta': 'DENATRAM',
    'segunda_carreta': 'DENATRAM'
};

var consultas_nomes = {
    'cpf': lang.situacao_cadastral_cpf,
    'debitos': lang.debitos_financeiros,
    'cnh': lang.situacao_cnh,
    'antecedentes': lang.antecedentes_criminais,
    'processos': lang.processos,
    'mandados': lang.banco_mandados_prisao,
    'veiculo': lang.situacao_veiculo,
    'carreta': lang.situacao_carreta,
    'segunda_carreta': lang.situacao_segunda_carreta
};



//GET IMAGE URL TO BASE64
function toDataURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        var reader = new FileReader();
        reader.onloadend = function () {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}


function resetaStatusLaudo() {
    $('#pagina_laudo').css('display', 'none');
    $('#print-content').css('display', 'none');
    //limpa os comprovantes para caso tenha uma segunda geracao do laudo nao duplica-los
    $('#comprovantes').html('');
    $('#resultado_resumo_mandados').html('');
    $('#resultado_resumo_processos').html('');
    $('#resultado_resumo_debitos').html('');

    $('#div_alertas_laudo').css('display', 'none');
    $('#alertas_laudo').text('');

    $('#alertaStatusFonte').removeClass('alert-danger').addClass('alert-success');
    $('#iconAlertaStatusFonte').removeClass('fa-minus-circle').addClass('fa-exclamation-circle');
    $('#fontes_indisponiveis_laudo').html('<strong>' + lang.msg_fontes_indisponiveis2 + '</strong>');

    $('#alertaStatusConsulta').removeClass('alert-danger').addClass('alert-success');
    $('#iconAlertaStatusConsulta').removeClass('fa-minus-circle').addClass('fa-exclamation-circle');
    $('#fontes_irregulares_laudo').html('<strong>' + lang.msg_fontes_irregulares2 + '</strong>');

    $('.logoCliente').removeAttr('src');
}


$(document).on('click', '#btn_gerar_laudo', function (e) {
    e.preventDefault();
    let botao = $(this);

    //Reseta os campos a seus padroes
    $('#resumo_laudo_veiculo').css('display', 'none');

    $.ajax({
        url: site_url + '/PerfisProfissionais/carrega_dados_laudo/' + id_log,
        dataType: "json",
        beforeSend: function () {
            resetaStatusLaudo();
            botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.gerando_laudo);
        },
        success: function (data) {
            let profissional = data.profissional;
            let consultas = data.consultas;
            let consultas_indisponiveis = data.consultas_indisponiveis;
            let consultas_irregulares = data.consultas_irregulares;
            let consultas_nao_avaliadas = data.consultas_nao_avaliadas;
            let consultas_regulares = data.consultas_regulares;
            let comprovantes = data.comprovantes;
            let protocolo = data.protocolo;
            let score = data.score;
            let ponto_corte = data.ponto_corte;
            let idConsulta = data.id_consulta;
            let data_consulta = data.data_consulta;
            let dados_consultas = data.dados_consultas;
            let cliente = data.cliente;
            let consta_obito = data.consta_obito;
            let tipo_profissional = data.tipo_profissional;

            //PLOTA OS DADOS DO PROFISSIONAL
            $('#titulo_laudo').text(lang.laudo_consulta.toUpperCase());
            $('#cpf_laudo').text(profissional.cpf);
            $('.nome_laudo').text(profissional.nome);
            $('#rg_laudo').text(profissional.rg);

            let logo = cliente.logotipo ? cliente.logotipo : base_url + 'media/img/logo_omniscore.png';
            $('.logoCliente').attr('src', logo);

            toDataURL(logo, function (data) {
                logoOmniscore = data;
            });

            if (tipo_profissional === 'funcionario') {
                $('#tipo_profissional_laudo').text(lang.funcionario);
            }
            else if (tipo_profissional === 'agregado') {
                $('#tipo_profissional_laudo').text(lang.funcionario_agregado);
            }
            else if (tipo_profissional === 'motorista') {
                $('#tipo_profissional_laudo').text(lang.funcionario_motorista);
            }
            else if (tipo_profissional === 'autonomo') {
                $('#tipo_profissional_laudo').text(lang.funcionario_autonomo);
            }

            if (score) {
                if (consta_obito === 'sim') {
                    $('#div_alertas_laudo').css('display', 'block');
                    $('#alertas_laudo').text(lang.cpf_consta_obito);
                    $('#ponto_corte_laudo').text(lang.nao_indicado.toUpperCase()).removeClass('label-success').addClass('label-danger');
                    score = '100';
                }
                else {
                    if (parseFloat(score) < parseFloat(ponto_corte)) {
                        $('#ponto_corte_laudo').text(lang.nao_indicado.toUpperCase()).removeClass('label-success').addClass('label-danger');
                    }
                    else {
                        $('#ponto_corte_laudo').text(lang.indicado.toUpperCase()).removeClass('label-danger').addClass('label-success');
                    }
                }

                $('#score_laudo').text(score + ' / 1000');
                $('.protocolo_laudo').text(protocolo);
            }

            $('#qtdConsultas_laudo').text(consultas.length);
            
            let qrCode = GeraQRCode('consulta_' + idConsulta, '125', '125');
            $('#qrcode_laudo').html(qrCode);
            $('#data_consulta_laudo').text(data_consulta);

            //CARREGA A MENSAGEM DAS FONTES INDIPONIVEIS
            if (consultas_indisponiveis.length > 0) {
                var temp_ind = [];
                consultas_indisponiveis.forEach(f_ind => {
                    if (temp_ind.indexOf(fontes_nomes[f_ind]) === -1)
                        temp_ind.push(fontes_nomes[f_ind]);
                });

                $('#alertaStatusFonte').removeClass('alert-success').addClass('alert-danger');
                $('#iconAlertaStatusFonte').removeClass('fa-exclamation-circle').addClass('fa-minus-circle');
                $('#fontes_indisponiveis_laudo').html('<strong>' + temp_ind.join(', ') + '.</strong>');
            }

            //CARREGA A MENSAGEM DAS FONTES IRREGULARES
            if (consultas_irregulares.length > 0) {
                var temp_ind = [];

                consultas_irregulares.forEach(f_ind => {
                    if (temp_ind.indexOf(fontes_nomes[f_ind]) === -1)
                        temp_ind.push(fontes_nomes[f_ind]);
                });

                $('#alertaStatusConsulta').removeClass('alert-success').addClass('alert-danger');
                $('#iconAlertaStatusConsulta').removeClass('fa-exclamation-circle').addClass('fa-minus-circle');
                $('#fontes_irregulares_laudo').html('<strong>' + temp_ind.join(', ') + '.</strong>');
            }


            //PLOTA OS INDICADORES
            var indicadores = "";
            var fontesConsultadas = [];

            //Mensagens para quando a consulta nao possui mandados e processos
            let msg_consulta_regular = {
                'mandados': lang.msg_documento_nao_possui_mandados,
                'processos': lang.msg_documento_nao_possui_processos,
                'debitos': lang.msg_documento_nao_possui_debitos
            };

            consultas.forEach(item => {
                var status = '<span class="statusIndicadores"><strong><i class="fa fa-minus-circle"></i> ' + lang.indisponivel.toUpperCase() + '</strong></span>';
                var status_resumo = '<label class="label" style="color:#333; font-size:14px;"><strong><i class="fa fa-minus-circle"></i> ' + lang.indisponivel.toUpperCase() + '</strong></label>';
                if (consultas_regulares.indexOf(item) !== -1) {
                    status = '<span class="statusIndicadores" style="color: #35bf35;"><strong><i class="fa fa-check-circle"></i> ' + lang.regular.toUpperCase() + '</strong></span>';
                    status_resumo = '<label class="label" style="color: #35bf35; font-size:14px;"><strong><i class="fa fa-check-circle"></i> ' + lang.regular.toUpperCase() + '</strong></label>';
                }
                else if (consultas_irregulares.indexOf(item) !== -1) { 
                    status = '<span class="statusIndicadores" style="color: #d9534f;"><strong><i class="fa fa-times-circle"></i> ' + lang.irregular.toUpperCase() + '</strong></span>';
                    status_resumo = '<label class="label" style="color: #d9534f; font-size:14px;"><strong><i class="fa fa-times-circle"></i> ' + lang.irregular.toUpperCase() + '</strong></label>';
                }
                else if (consultas_nao_avaliadas.indexOf(item) !== -1) {
                    status = '<span class="statusIndicadores" style="color: #555555;"><strong><i class="fa fa-circle"></i> ' + lang.nao_avaliado.toUpperCase() + '</strong></span>';
                    status_resumo = '<label class="label" style="color: #555555; font-size:14px;"><strong><i class="fa fa-circle"></i> ' + lang.nao_avaliado.toUpperCase() + '</strong></label>';
                }

                indicadores += `<div class="col-md-6 spaceElement indicadores">
                                            <div><strong>${consultas_nomes[item]}</strong></div>
                                            <span>${fontes_nomes[item].toUpperCase()}</span> ${status}
                                        </div>`;

                if (fontesConsultadas.indexOf(fontes_nomes[item]) === -1)
                    fontesConsultadas.push(fontes_nomes[item]);

                //PLOTA OS RESUMOS DE CADA CONSULTA
                $(`#status_${item}`).html(status_resumo);

                //Reseta o padao para os dados que seram mostrados das consultas
                $(`#div_resultado_laudo_${item}`).css('display', 'block');
                $(`#div_msg_laudo_${item}`).css('display', 'none');

                //plota os dados
                let multiConsultas = ['mandados', 'processos', 'debitos'];
                if (consultas_regulares.indexOf(item) !== -1 || consultas_irregulares.indexOf(item) !== -1) {
                    //Se tem veiculo e tem dados para serem apresentados, entao quebra a pagina
                    if (item == 'veiculo') $('.quebra_pagina_veiculo').addClass('quebra-pagina');

                    if (multiConsultas.indexOf(item) !== -1) {
                        if (consultas_irregulares.indexOf(item) !== -1) {
                            var consultas = dados_consultas[item];

                            if (item === 'debitos') {
                                $(`#resultado_resumo_debitos`).append(
                                    `<p>${lang.consta_pendecia_total} ${consultas.total.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}</p>`
                                );

                                let dados_consultas = consultas.dados;
                                dados_consultas.forEach(elem => {
                                    let data = new Date(elem.data_ocorrencia);

                                    //ADICIONA OS VALORES DOS CAMPOS
                                    $(`#resultado_resumo_debitos`).append(
                                        `<p>
                                            <strong>${lang.empresa} </strong> ${elem.empresa}           <strong>${lang.valor}: </strong> ${parseFloat(elem.valor).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}           <strong>${lang.data_ocorrencia}: </strong> ${data.toLocaleString()}
                                        </p>`
                                    );
                                });
                            }
                            else if (item === 'processos') {
                                $(`#resultado_resumo_processos`).append(
                                    `<p>${lang.consta_processo_tatal} ${consultas.total} ${lang.processos.toLowerCase()}</p>`
                                );

                                let dados_consultas = consultas.dados;
                                dados_consultas.forEach(elem => {
                                    let data = new Date(elem.data_publicacao);

                                    //ADICIONA OS VALORES DOS CAMPOS
                                    $(`#resultado_resumo_processos`).append(
                                        `<p>
                                            <strong>${lang.processo}:</strong> ${elem.numero}          <strong>${lang.instancia}:</strong> ${elem.tribunal_instancia}ª          <strong>${lang.tribunal}:</strong> ${elem.tribunal_nome}          <strong>${lang.data_publicacao}:</strong> ${data.toLocaleString()}
                                        </p>`
                                    );
                                });
                            }
                            else if (item === 'mandados') {
                                $(`#resultado_resumo_mandados`).append(
                                    `<p>${lang.consta_mandado_total} ${consultas.total} ${lang.mandados.toLowerCase()}</p>`
                                );

                                let dados_consultas = consultas.dados;
                                dados_consultas.forEach(elem => {
                                    let data = new Date(elem.data_expedicao);

                                    //ADICIONA OS VALORES DOS CAMPOS
                                    $(`#resultado_resumo_mandados`).append(
                                        `<p>
                                                    <strong>${lang.mandado}:</strong> ${elem.numero_processo}          <strong>${lang.orgao_emissor}:</strong> ${elem.nome_orgao}          <strong>${lang.data_expedicao}:</strong> ${data.toLocaleString()}
                                                </p>`
                                    );
                                });
                            }

                        }
                        else {
                            var consultas_debitos = dados_consultas[item];
                            if (item === 'debitos' && consultas_debitos.total > 0) {
                                $(`#resultado_resumo_debitos`).append(
                                    `<p>${lang.consta_pendecia_total} ${consultas_debitos.total.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}</p>`
                                );

                                let dados_debitos = consultas_debitos.dados;
                                dados_debitos.forEach(elem => {

                                    let data = new Date(elem.data_ocorrencia);
                                    //ADICIONA OS VALORES DOS CAMPOS
                                    $(`#resultado_resumo_debitos`).append(
                                        `<p>
                                            <strong>${lang.empresa} </strong> ${elem.empresa}           <strong>${lang.valor}: </strong> ${parseFloat(elem.valor).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}           <strong>${lang.data_ocorrencia}: </strong> ${data.toLocaleString()}
                                        </p>`
                                    );
                                });
                            }
                            else {
                                $(`#msg_laudo_${item}`).text(msg_consulta_regular[item]);
                                $(`#div_resultado_laudo_${item}`).css('display', 'none');
                                $(`#div_msg_laudo_${item}`).css('display', 'block');
                            }

                        }
                    }
                    else {
                        var consulta = dados_consultas[item];
                        Object.keys(consulta).forEach(elem => {
                            //ADICIONA OS VALORES DOS CAMPOS
                            $(`#${elem}_laudo_${item}`).text(consulta[elem]);
                        });
                    }
                }
                else {
                    $(`#div_resultado_laudo_${item}`).css('display', 'none');
                    $(`#div_msg_laudo_${item}`).css('display', 'block');

                    if (consultas_nao_avaliadas.indexOf(item) !== -1) $(`#msg_laudo_${item}`).text(lang.msg_documento_nao_encontrado);
                    else $(`#msg_laudo_${item}`).text(lang.fontes_indisponiveis);
                }

                //Mostra apenas os resumos de consultas realizadas
                $(`#resumo_laudo_${item}`).css('display', 'block');

            });

            $('#indicadores_laudo').html(indicadores);
            $('#fontesConsultadas_laudo').html(fontesConsultadas.join(', '));

        
            //PLOTA OS COMPROVANTES
            let qtd_comprovantes = Object.keys(comprovantes).length;
            if (qtd_comprovantes) {
                Object.keys(comprovantes).forEach((item, i) => {
                    let arquivo = `<div class="quebra-pagina"></div>
                                    <h4 style="text-align:center; margin-top: 20px;">${consultas_nomes[item]} - ${fontes_nomes[item]}</h4>
                                    <div style="text-align:center; margin-bottom: 20px;">
                                        <div id="comprovante_laudo_${i}"></div>
                                        <canvas id="canva_laudo_${i}" width="800" heigth="800"></canvas>
                                    </div>`;

                    $('#comprovantes').html($('#comprovantes').html() + arquivo);
                    plota_comprovante(comprovantes[item] + '?' + Math.random(), i);
                });
            }
            else {
                $('#comprovantes').html(
                    `<div class="quebra-pagina"></div>
                    <div style="text-align:center; margin-top: 20px;"><b> ${ lang.nao_existem_comprovantes_consultas }</b></div>`
                );
            }

            //CONVERTE O HTML EM PDF
            setTimeout(() => {
                exporta_pdf(protocolo, cliente.nome, cliente.cpf_cnpj);
            }, 2000);

        },
        error: function () {
            alert(lang.falha_na_requisicao);
            $('#pagina_laudo').css('display', 'none');
            $('#print-content').css('display', 'none');
            $('#btn_gerar_laudo').attr('disabled', false).html(lang.laudo_detalhado);
        }
    });

});

/**
 * Converte um texto para imagem base64
*/
function marcaDaguaText(text) {
    var canvas = document.createElement("canvas");
    var fontSize = 14;
    canvas.setAttribute('height', fontSize + 10);
    var context = canvas.getContext('2d');
    context.fillStyle = 'rgba(0, 0, 0, 0.10)';
    context.font = fontSize + 'px sans-serif';
    context.fillText(text, 0, fontSize);

    return canvas.toDataURL("image/png");
}

//CONVERTE O HTML PARA PDF E O EXPORTA
function exporta_pdf(protocolo, nome_cliente, cpf_cnpj) {
    
    $('#pagina_laudo').css('display', 'block');
    $('#print-content').css('display', 'block');

    let element = document.getElementById('print-content');

    let opt = {
        filename: lang.laudo_consulta,
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
                pdf.text(lang.laudo_consulta, pdf.internal.pageSize.getWidth() - 40, 10);
                pdf.text(data_atual, pdf.internal.pageSize.getWidth() - 40, 13);
                pdf.addImage(logoOmniscore, "JPG", 12, 7, 35, 8);
                pdf.text('___________________________________________________________________________________________________________________________________________', 10, 16);

                //monta o footer
                pdf.text('___________________________________________________________________________________________________________________________________________', 10, pdf.internal.pageSize.getHeight() - 20);
                pdf.text(lang.emitido_exclusivamente_para.toUpperCase() + ' ' + nome_cliente.toUpperCase() + ' - ' + cpf_cnpj, 10, pdf.internal.pageSize.getHeight() - 14);
                pdf.text(empresa, 10, pdf.internal.pageSize.getHeight() - 11);
                pdf.text(lang.protocolo + ': ' + protocolo, 10, pdf.internal.pageSize.getHeight() - 8);
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
        $('#pagina_laudo').css('display', 'none');
        $('#print-content').css('display', 'none'); 
        $('#btn_gerar_laudo').attr('disabled', false).html(lang.laudo_detalhado);
    }, 3000);
    
}


//RENDERIZA A PAGINA
function showPDF(pdf_url, canvas, numero_comprovante) {
    PDFJS.getDocument({ url: pdf_url }).then(function (pdf_doc) {
        doc = pdf_doc;

        // Show the first page
        showPage(1, canvas, numero_comprovante);
    }).catch(function (error) {
        alert(error.message);
    });
}


//APRENSENTA A IMAGEM (PDF/PNG)
function showPage(page_no, canvas, numero_comprovante) {

    // Fetch the page
    doc.getPage(page_no).then(function (page) {
        // Como a tela tem uma largura fixa, precisamos definir a escala da janela de visualização de acordo
        var scale_required = 1.3438;

        // Obtenha a janela de visualização da página na escala necessária
        var viewport = page.getViewport(scale_required);   

        var scale = 5;
        var w = 1000;
        var h = 1000;
        canvas.width = w * scale;
        canvas.height = h * scale;
        canvas.style.width = w + 'px';
        canvas.style.height = h + 'px';
        var ctx = canvas.getContext('2d');
        ctx.scale(scale, scale);
        

        var renderContext = {
            canvasContext: ctx,
            viewport: viewport,
            transform: [1, 0, 0, 1, -30, 0],
        };

        // Renderizar o conteúdo da página na tela
        page.render(renderContext).then(function () {
            var img_canvas = convertCanvasToImage(canvas);
            $("#canva_laudo_" + numero_comprovante).css('display', 'none');
            $("#comprovante_laudo_" + numero_comprovante).html(`<img src="${img_canvas.src}" alt="${lang.comprovante + ' ' + numero_comprovante}" style="width: 880px;"  />`);
        });

    });
}

//CONVERTE O CANVAS PARA IMAGEM
function convertCanvasToImage(canvas) {
    var image = new Image();
    image.src = canvas.toDataURL("image/jpg");
    return image;
}

//CRIA UMA STREAM DE FILE
async function createFile(arq, canvas, numero_comprovante) {
    let response = await fetch(arq);
    let data = await response.blob();
    let metadata = {
        type: 'image/jpg'
    };
    let file = new File([data], 'comprovante_' + numero_comprovante + '.jpg', metadata);
    showPDF(URL.createObjectURL(file), canvas, numero_comprovante);
}

//FAZ A CHAMADA PARA A API, CARREGA O BOLETO-PDF
function plota_comprovante(file, numero_comprovante) {
    var canvas = $('#canva_laudo_' + numero_comprovante).get(0);
    createFile(file, canvas, numero_comprovante);

}