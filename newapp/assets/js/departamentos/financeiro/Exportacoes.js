// Geral
function exportarArquivo(tipo, gridOptions,menu, titulo) {
    let colunas;
    if (menu === "relatorioFatuamento") {
        colunas = ["id", "nome", "documento", "natureza_cliente", "orgao", "prestadora", "emissao", "vencimento", "veiculos", "nf", "valor", "mes_ref", "fatura", "atividade", "cofins", "csll", "irpj", "iss", "pis", "status",]
    }

    switch (tipo) {
        case 'csv':
            fileName = menu + '.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: colunas,
            });
            break;
        case 'excel':
            fileName = menu + '.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: colunas,
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                titulo,
                colunas
            );
            pdfMake.createPdf(definicoesDocumento).download(menu + '.pdf');
            break;

    }
}

function printParams(pageSize) {
    return {
        PDF_HEADER_COLOR: "#ffffff",
        PDF_INNER_BORDER_COLOR: "#dde2eb",
        PDF_OUTER_BORDER_COLOR: "#babfc7",
        PDF_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
        PDF_HEADER_LOGO: 'omnilink',
        PDF_ODD_BKG_COLOR: "#fff",
        PDF_EVEN_BKG_COLOR: "#F3F3F3",
        PDF_PAGE_ORITENTATION: "landscape",
        PDF_WITH_FOOTER_PAGE_COUNT: true,
        PDF_HEADER_HEIGHT: 25,
        PDF_ROW_HEIGHT: 25,
        PDF_WITH_CELL_FORMATTING: true,
        PDF_WITH_COLUMNS_AS_LINKS: false,
        PDF_SELECTED_ROWS_ONLY: false,
        PDF_PAGE_SIZE: pageSize,
    }
}

$(document).ready(function() {
    function setupDropdown(buttonId, dropdownId) {
        var dropdown = $(dropdownId);

        $(buttonId).click(function(event) {
            event.stopPropagation();

            if (dropdown.is(':visible')) {
                dropdown.hide();
            } else {
                $(".dropdown-menu").hide();
                dropdown.show();
            }
        });

        $(document).click(function(event) {
            if (!dropdown.is(event.target) && event.target.id !== buttonId.substring(1) && dropdown.has(event.target).length === 0) {
                dropdown.hide();
            }
        });
    }

    setupDropdown('#dropdownMenuButton', '#opcoes_exportacao');
});



function preencherExportacoesRelatorio(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

    formularioExportacoes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcao_exportacao');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivo(opcao, gridOptions,'relatorioFatuamento', 'Relatório de Faturamento');
        });

        formularioExportacoes.appendChild(div);
    });
}

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);

    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }

    $(".dropdown-menu").hide();

    dropdown.show();
    var posDropdown = dropdown.height() + 4;

    var dropdownItems = $('#' + dropdownId + ' .dropdown-item-acoes');
    var alturaDrop = 0;
    for (var i = 0; i <= dropdownItems.length; i++) {
        alturaDrop += dropdownItems.height();
    }

    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)) {
            dropdown.css('top', `-${alturaDrop - 60}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(alturaDrop - 50) - (diferenca + diferenca * 0.5)}px`);
        }
    }

    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });
}