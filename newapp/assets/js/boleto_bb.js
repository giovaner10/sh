
//RENDERIZA A PAGINA
function showPDF(pdf_url, canvas, numero_fatura) {
    PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
        doc = pdf_doc;

        // Show the first page
        showPage(1, canvas, numero_fatura);
    }).catch(function(error) {
        alert(error.message);
    });
}

//APRENSENTA A IMAGEM (PDF/PNG)
function showPage(page_no, canvas, numero_fatura) {
    // Fetch the page
    doc.getPage(page_no).then(function(page) {
        // Como a tela tem uma largura fixa, precisamos definir a escala da janela de visualização de acordo
        var scale_required = canvas.width / page.getViewport(1).width;

        // Obtenha a janela de visualização da página na escala necessária
        var viewport = page.getViewport(scale_required);

        // Definir altura da tela
        canvas.height = viewport.height;
        ctx = canvas.getContext('2d');

        var renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        // Renderizar o conteúdo da página na tela
        page.render(renderContext).then(function() {
            var img_canvas = convertCanvasToImage(canvas);
            $("#loading_bb_img_"+numero_fatura).html('<img src="'+img_canvas.src+'" alt="Boleto BB" class="portrait" />')
            .addClass('thumbBoletoImg');
        });

    });
}

//CONVERTE O CANVAS PARA IMAGEM
function convertCanvasToImage(canvas) {
    var image = new Image();
    image.src = canvas.toDataURL("image/png");
    return image;
}

//CRIA UMA STREAM DE FILE
async function createFile(arq, canvas, numero_fatura){
    let response = await fetch(arq);
    let data = await response.blob();
    let metadata = {
        type: 'image/png'
    };
    // let file = new File([data], "fatura_<?=$fatura->numero_fatura?>"+".png", metadata);
    let file = new File([data], "fatura_407753"+".png", metadata);
    showPDF(URL.createObjectURL(file), canvas, numero_fatura);
}

//FAZ A CHAMADA PARA A API, CARREGA O BOLETO-PDF
function geraBoleto(numero_fatura){
    var canvas = $('#boleto_bb_'+numero_fatura).get(0);
    $.ajax({
        type: "POST",
        url: site_url+'/api/boleto_bb/'+ numero_fatura,
        data: $('#form_bb_'+ numero_fatura).serialize(),
        success: function(retorno) {
            
            var data = JSON.parse(retorno);
            if (data.success) {
                createFile(base_url+''+data.file, canvas, numero_fatura);
                
                // $("#baixarFatura_"+numero_fatura).on( "click", function() {
                //     createFile(base_url+''+data.file, canvas, numero_fatura);
                // });
                
                $("#loading_bb_"+numero_fatura).html('<iframe id="boletoIframe" src="' + base_url + data.file + '#view=fit" width="500" height="700"></iframe>')
                .addClass('thumbBoleto');
            }else {
                alert("Não foi possível gerar o boleto de pagamento!");
                $("#loading_bb_"+numero_fatura).html(
                    '<div class="alert alert-danger">'+
                        '<h4>Atenção!</h4>'+
                        '<p>Os dados enviados apresentaram o(s) seguinte(s) problema(s):</p>'+
                        '<p><b>'+data.msg+'</b></p>'+
                    '</div>'
                );
            }

        }
    });
}
