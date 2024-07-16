
function resetaStatusLaudoVitimologia() {
    $('#pagina_laudo_vitimismo').css('display', 'none');
    $('#print-content_vitimismo').css('display', 'none');
    $('.detalhes_laudo_processos').css('display', 'none');    

    $('#alertaStatusFonteVitimologia').removeClass('alert-danger').removeClass('alert-warning').addClass('alert-success');
    $('#iconAlertaStatusFonteVitimologia').removeClass('fa-minus-circle').addClass('fa-exclamation-circle');
    $('#situacao_orgao_laudo_vitimismo').html('<strong>' + lang.msg_fontes_disponiveis + '</strong>');

    $('#alertaStatusConsultaVitimologia').removeClass('alert-danger').addClass('alert-success');
    $('#iconAlertaStatusConsultaVitimologia').removeClass('fa-minus-circle').addClass('fa-exclamation-circle');
    $('#situacao_consulta_laudo_vitimismo').html('<strong>' + lang.msg_documento_nao_possui_processos + '</strong>');

    $(`#resultado_detalhes_processos`).html('');

    $('.logoCliente').removeAttr('src');
}

/**
 * Gera o laudo da consulta
*/
$(document).on('click', '.gerarLaudoVitimismo', function (e) {
    e.stopPropagation();
    
    let botao = $(this);

    if (navigator.userAgent.search("Firefox") >= 0) {
        alert(lang.msg_preferencia_browser);
        return false;
    }

    $.ajax({
        url: site_url + '/PerfisProfissionais/carrega_laudo_vitimismo/' + id_log,
        dataType: "json",
        beforeSend: function () {
            resetaStatusLaudoVitimologia();
            botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.gerando_laudo);
        },
        success: function (data) {
            let profissional = data.profissional;
            let protocolo = data.protocolo;
            let idConsulta = data.id_consulta;
            let data_consulta = data.data_consulta;
            let cliente = data.cliente;
            let processos = data.processos;
            let tipo_profissional = data.tipo_profissional;

            //PLOTA OS DADOS DO PROFISSIONAL
            $('#titulo_laudo_vitimismo').text(lang.laudo_vitimismo.toUpperCase());
            $('#cpf_laudo_vitimismo').text(profissional.cpf);
            $('.nome_laudo_vitimismo').text(profissional.nome);
            $('#rg_laudo_vitimismo').text(profissional.rg);

            let logo = cliente.logotipo ? cliente.logotipo : base_url + 'media/img/logo_omniscore.png';
            $('.logoCliente').attr('src', logo);

            toDataURL(logo, function (data) {
                logoOmniscore = data;
            });

            if (tipo_profissional === 'funcionario') {
                $('#tipo_profissional_laudo_vitimismo').text(lang.funcionario);                        
            } 
            else if (tipo_profissional === 'agregado') {
                $('#tipo_profissional_laudo_vitimismo').text(lang.funcionario_agregado);
            }
            else if (tipo_profissional === 'motorista') {
                $('#tipo_profissional_laudo_vitimismo').text(lang.funcionario_motorista);
            }
            else if (tipo_profissional === 'autonomo') {
                $('#tipo_profissional_laudo_vitimismo').text(lang.funcionario_autonomo);
            }        

        
            let qrCode = GeraQRCode('consulta_' + idConsulta, '125', '125');
            $('#qrcode_laudo_vitimismo').html(qrCode);
            $('#data_consulta_laudo_vitimismo').text(data_consulta);                       

            //Plota status das fontes de consulta
            let status_sucesso = ['1', '2', '5'];     //SUCESSO
            let status_nao_avaliado = ['3', '4', '6', '7']; //TODO PROBLEMA POR INCONSISTENCIA/AUSENCIA DE DADOS                    

            //plota os detalhes das consultas
            if (Object.keys(processos).length > 1 || Object.keys(processos).length && processos[0].numero) {                
                if (status_sucesso.indexOf(processos[0].status_consulta) !== -1) {
                    $('#alertaStatusFonteVitimologia').removeClass('alert-danger').removeClass('alert-warning').addClass('alert-success');
                    $('#iconAlertaStatusFonteVitimologia').removeClass('fa-minus-circle').addClass('fa-exclamation-circle');
                    $('#situacao_orgao_laudo_vitimismo').html('<strong>' + lang.msg_fontes_disponiveis + '</strong>');
                }
                else if (status_nao_avaliado.indexOf(processos[0].status_consulta) !== -1) {
                    $('#alertaStatusFonteVitimologia').removeClass('alert-danger').removeClass('alert-success').addClass('alert-warning');
                    $('#iconAlertaStatusFonteVitimologia').removeClass('fa-exclamation-circle').addClass('fa-minus-circle');
                    $('#situacao_orgao_laudo_vitimismo').html('<strong>' + lang.msg_dados_inconsistente_consultas + '</strong>');
                }
                else {
                    $('#alertaStatusFonteVitimologia').removeClass('alert-warning').removeClass('alert-success').addClass('alert-danger');
                    $('#iconAlertaStatusFonteVitimologia').removeClass('fa-exclamation-circle').addClass('fa-minus-circle');
                    $('#situacao_orgao_laudo_vitimismo').html('<strong>' + lang.msg_fontes_vitimismo_indisponiveis + '</strong>');
                }

                $('.detalhes_laudo_processos').css('display', 'block');
                
                $('#alertaStatusConsultaVitimologia').removeClass('alert-success').addClass('alert-danger');
                $('#iconAlertaStatusConsultaVitimologia').removeClass('fa-exclamation-circle').addClass('fa-minus-circle');
                $('#situacao_consulta_laudo_vitimismo').html(`<strong> ${Object.keys(processos).length} ${lang.msg_documento_possui_processos} </strong>`);
                
                
                Object.keys(processos).forEach((item, i) => {

                    if (processos[item].numero) {
                    
                        let data = new Date(processos[item].data_publicacao);
                        //ADICIONA OS VALORES DOS CAMPOS
                        $(`#resultado_detalhes_processos`).append(
                            `<div class="panel panel-default bordaPainel" style="margin-top:15px;">                            
                                <div class="bordaPainelBottom" role="tab" style="height: 40px;">
                                    <div class="col-md-8" style="padding-top: 10px!important;">
                                        <h6 class="panel-title">
                                            <span>${lang.processo} - ${capitalize(processos[item].numero)}</span> <span style="float: right;">${processos[item]?.tribunal_tipo || ''}</span>
                                        </h6>
                                    </div>
                                    <div class="col-md-4" style="text-align: right;"></div>
                                </div>
                                <div class="panel-collapse collapse show" role="tabpanel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.tribunal}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${processos[item].tribunal_nome}</p>
                                                        </div>                                                            
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.instancia}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${capitalize(processos[item].tribunal_instancia)}ª</p>
                                                        </div>                                                            
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.distrito}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${capitalize(processos[item].tribunal_distrito)}</p>
                                                        </div>                                                            
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.uf}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${processos[item].estado}</p>
                                                        </div>                                                            
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.tipo}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${capitalize(processos[item].tipo)}</p>
                                                        </div>                                                            
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.assunto}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${capitalize(processos[item].assunto)}</p>
                                                        </div>                                                            
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.situacao}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${capitalize(processos[item].situacao)}</p>
                                                        </div>                                                            
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-4">
                                                            <strong><p>${lang.data_publicacao}</p></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>${data.toLocaleString()}</p>
                                                        </div>                                                            
                                                    </div>
                                                    
                                                </div>                                                                                       
                                            </div>                                                
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        );
                    }
                }); 
                
            }

            //CONVERTE O HTML EM PDF
            setTimeout(() => {
                exporta_pdf_vitimismo(protocolo, cliente.nome, cliente.cpf_cnpj, botao);
            }, 2000);

        },
        error: function () {
            alert(lang.falha_na_requisicao);
            $('#pagina_laudo_vitimismo').css('display', 'none');
            $('#print-content_vitimismo').css('display', 'none');
            botao.html(lang.laudo_vitimismo).attr('disabled', false);
        }
    });        
})

//CONVERTE O HTML PARA PDF E O EXPORTA
function exporta_pdf_vitimismo(protocolo, nome_cliente, cpf_cnpj, botao) {
    
    $('#pagina_laudo_vitimismo').css('display', 'block');
    $('#print-content_vitimismo').css('display', 'block');

    let element = document.getElementById('print-content_vitimismo');

    let opt = {
        filename: lang.laudo_vitimismo,
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
                pdf.text(lang.laudo_vitimismo, pdf.internal.pageSize.getWidth() - 40, 10);
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
        $('#pagina_laudo_vitimismo').css('display', 'none');
        $('#print-content_vitimismo').css('display', 'none');
        botao.html(lang.laudo_vitimismo).attr('disabled', false);
    }, 4000);

}


//Torna maiuscula a primeira letra de uma string
function capitalize(str) {
    if (typeof str !== 'string') return '';
    return `${str.charAt(0).toUpperCase()}${str.substring(1).toLowerCase()}`;
}