function tableToPDF(title) {
    var pdf = new jsPDF('p', 'pt', 'a2');
    source = $('#exportar_tabela')[0];

    specialElementHandlers = {
        '#bypassme': function (element, renderer) {
            return true
        }
    };

    margins = {
        top: 180,
        bottom: 30,
        left: 20,
        width: 800
    };

    pdf.fromHTML(
    source,
    margins.left,
    margins.top, {
        'width': margins.width,
        'elementHandlers': specialElementHandlers
    },

    function (dispose) {
        pdf.save(title+'.pdf');
    }, margins);

}