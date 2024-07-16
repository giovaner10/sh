// Geral

function exportarArquivo(tipo, gridOptions, menu = 'RelatorioLeituraDePlacasOCR', titulo) {
    let colunas = [];
    if (menu === 'RelatorioLeituraDePlacasOCR') {
        colunas = ['serial', 'placa_lida', 'tipo_match', 'marca', 'modelo', 'best_plate', 'file_s_time', 'file_e_time', 'enredeco'];
    } else if (menu === 'RelatorioDeHotListOCR') {
        colunas = ['placa', 'chassi','marca', 'modelo' , 'cor', 'tipo_ocorrencia', 'nome', 'razaoSocial']
    } else if (menu === 'RelatorioDeAlertasOCR') {
        colunas = ['emails', 'nomeCliente', 'integraCss', 'notificaEmail', 'notificaTelaAlerta']
    } else if (menu === 'RelatorioAlertasDeteccaoPlacasOCR') {
        colunas = ['status', 'tipo_match', 'placa_lida','marca', 'modelo', 'best_plate', 'file_s_time', 'file_e_time', 'latidude', 'longitude', 'serial', 'cliente', 'endereco']
    } else if (menu === 'RelatorioDeColdListOCR') {
        colunas = ['placa', 'chassi', 'marca', 'modelo', 'cor', 'tipoOcorrencia', 'nome', 'razaoSocial']
    } else if (menu === 'RelatorioProcessamentoLoteOCR') {
        colunas = ['id_cliente', 'tipo_list', 'id_cadastro_alerta', 'acao', 'status', 'data_cadastro', 'data_execucao', 'falhas', 'sucessos']
    }

    switch (tipo) {
        case 'csv':
            fileName = menu + '.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: colunas
            });
            break;
        case 'excel':
            fileName = menu + '.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: colunas
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                titulo
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
        PDF_LOGO: BaseURL + 'media/img/new_icons/ceabs.png',
        PDF_HEADER_LOGO: 'ceabs',
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

// Dados Gerenciamento 
$(document).ready(function() {
    var dropdown = $('#opcoes_exportacao');

    $('#dropdownMenuButton').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButton') {
            dropdown.hide();
        }
    });
});

function preencherExportacoes(gridOptions) {
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
            exportarArquivo(opcao, gridOptions, 'RelatorioLeituraDePlacasOCR', 'Relatório de Leitura de Placas OCR');
        });

        formularioExportacoes.appendChild(div);
    });
}

// Blacklist
$(document).ready(function() {
    var dropdown = $('#opcoes_exportacao_blacklist');

    $('#dropdownMenuButtonBlacklist').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonBlacklist') {
            dropdown.hide();
        }
    });
});

function preencherExportacoesBlacklist(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_blacklist');
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
            exportarArquivo(opcao, gridOptions, 'RelatorioDeHotListOCR', 'Relatório de Hot List OCR');
        });

        formularioExportacoes.appendChild(div);
    });
}

// Alertas Email
$(document).ready(function() {
    var dropdown = $('#opcoes_exportacao_alertas_email');

    $('#dropdownMenuButtonAlertasEmail').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonAlertasEmail') {
            dropdown.hide();
        }
    });
});

function preencherExportacoesAlertasEmail(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_alertas_email');
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
            exportarArquivo(opcao, gridOptions, 'RelatorioDeAlertasOCR', 'Relatório de Alertas OCR');
        });

        formularioExportacoes.appendChild(div);
    });
}

// Eventos Placas
$(document).ready(function() {
    var dropdown = $('#opcoes_exportacao_eventos_placas');

    $('#dropdownMenuButtonEventosPlacas').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonEventosPlacas') {
            dropdown.hide();
        }
    });
});

function preencherExportacoesEventosPlacas(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_eventos_placas');
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
            exportarArquivo(opcao, gridOptions, 'RelatorioAlertasDeteccaoPlacasOCR', 'Relatório de Alertas de Detecção de Placas OCR');
        });

        formularioExportacoes.appendChild(div);
    });
}

// Whitelist
$(document).ready(function() {
    var dropdown = $('#opcoes_exportacao_whitelist');

    $('#dropdownMenuButtonWhitelist').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonWhitelist') {
            dropdown.hide();
        }
    });
});

function preencherExportacoesWhitelist(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_whitelist');
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
            exportarArquivo(opcao, gridOptions, 'RelatorioDeColdListOCR', 'Relatório de Cold List OCR');
        });

        formularioExportacoes.appendChild(div);
    });
}

// Processamento Lote
$(document).ready(function() {
    var dropdown = $('#opcoes_exportacao_processamento');

    $('#dropdownMenuButtonProcessamento').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonProcessamento') {
            dropdown.hide();
        }
    });
});


function preencherExportacoesProcessamento(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_processamento');
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
            exportarArquivo(opcao, gridOptions, 'RelatorioProcessamentoLoteOCR', 'Relatório de Processamento em Lote OCR');
        });

        formularioExportacoes.appendChild(div);
    });
}