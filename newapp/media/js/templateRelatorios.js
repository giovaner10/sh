
const COLOR_BLUE = '#086aaf';
const FONTSIZE_INFO_LANDSCAPE = 12;
const FONTSIZE_INFO_PORTRAIT = 10;
const FONTSIZE_NOMERELATORIO = 16;
const FONTSIZE_DATA = 14;
const WIDTH_ICON_LANDSCAPE = 12;
const WIDTH_ICON_PORTRAIT = 10;
// data atual
let date = new Date();
var jsDate = date.toLocaleDateString();
/**
 * Função que personaliza o relatório PDF exportado pelo gestor
 * @param {Object} doc - Objeto que contem informações sobre a página que está sendo gerada
 * @param {String} titulo - String que contém o nome do relatório
 * 
 */

function pdfTemplate(doc, titulo, pagesize = 'A4', widths = false, date = false) {
    // Remove primeira linha da exportação e substitui pelo cabeçalho
    doc.content.splice(0, 1, customHeader(doc.pageOrientation, titulo, pagesize, date));

    // Ajusta tamanho da tabela
    if (widths) {
        if (typeof doc.content[1].table !== 'undefined') doc.content[1].table.widths = widths;        

    } else {
        if (typeof doc.content[1].table !== 'undefined') doc.content[1].table.widths = '*';        
    }


    // Ajusta Body com alinhamento centro
    doc.styles.tableBodyEven = { alignment: 'center' };
    doc.styles.tableBodyOdd = { alignment: 'center', fillColor: '#f3f3f3', };

    // Estilo header tabela
    doc.content[1].layout = 'headerLineOnly';
    doc.styles.tableHeader = {
        fillColor: 'white',
        color: 'black',
        bold: true,
        fontSize: 11,
        width: '*',
        alignment: 'center'
    }
}


function pdfTemplateIsolated(doc, titulo, pagesize = 'A4', widths = false, columnAlignments = [], textoFooter = '', headerFontSize = 11, bodyFontSize = 10) {
    // Remove primeira linha da exportação e substitui pelo cabeçalho
    doc.content.splice(0, 1, customHeaderOmni(doc.pageOrientation, titulo, pagesize));
    // Ajusta tamanho da tabela
    if (widths) {
        if (typeof doc.content[1].table !== 'undefined') doc.content[1].table.widths = widths;        

    } else {
        if (typeof doc.content[1].table !== 'undefined') doc.content[1].table.widths = '*';        
    }


    if (columnAlignments.length > 0 && typeof doc.content[1].table !== 'undefined') {
        doc.content[1].table.body.forEach(row => {
            for (let i = 0; i < row.length; i++) {
                if (columnAlignments[i]) {
                    row[i].alignment = columnAlignments[i];
                }
            }
        });
    } else {
        doc.styles.tableBodyEven = { 
            alignment: 'center',
            fontSize: bodyFontSize
        };
        doc.styles.tableBodyOdd = { 
            alignment: 'center', 
            fillColor: '#f3f3f3',
            fontSize: bodyFontSize
        };
    }

    // Estilo header tabela
    doc.content[1].layout = 'headerLineOnly';
    doc.styles.tableHeader = {
        fillColor: 'white',
        color: 'black',
        bold: true,
        fontSize: headerFontSize,
        width: '*',
        alignment: 'center'
    }
    
    // Footer
    if (textoFooter) {
        doc.footer = function (currentPage, pageCount) {
            if (currentPage === pageCount) {
                return createFooter(textoFooter);
            }
            return null;
        };
    }
}   

function pdfTemplateIsolatedFornecedor(doc, titulo, pagesize = 'A4') {
    // Remove a primeira linha da exportação e substitui pelo cabeçalho
    doc.content.splice(0, 1, customHeaderOmni(doc.pageOrientation, titulo, pagesize));

    if (typeof doc.content[1].table !== 'undefined') {
        const totalColumns = 12; 
        const columnWidth = '*'; 

        doc.content[1].table.widths = Array(totalColumns).fill(columnWidth);
    }

    doc.defaultStyle.fontSize = 9;

    doc.styles.tableBodyEven = { alignment: 'center' };
    doc.styles.tableBodyOdd = { alignment: 'center', fillColor: '#f3f3f3' };

    // Estilo do cabeçalho da tabela
    doc.content[1].layout = 'headerLineOnly';
    doc.styles.tableHeader = {
        fillColor: 'white',
        color: 'black',
        bold: true,
        fontSize: 10,
        alignment: 'center'
    }
}

/**
 * Função que personaliza o relatório impresso pelo gestor
 * @param {Object} win - Objeto que contem informações sobre a página que está sendo gerada
 * @param {String} chave_auth - Chave de autenticação do relatório
 * @param {String} titulo - String que contém o nome do relatório
 * 
 */
function printTemplate(win, titulo, date = jsDate) {
    $(win.document.body).find('h1').remove();
    $(win.document.body).find('div').remove();

    // Adiciona Data Geração Relatório
    $(win.document.body).prepend('<div style="color: #FFFFFF !important; text-align: right; position:absolute;right:0;top:45px; margin-right: 20px; font-size:20px">' + date + '</div>')
    // Adiciona Logo
    $(win.document.body).prepend('<img src="' + URL_LOGO + '" style="height:55px; width:180px; margin-left:30px; margin-top:-55px; margin-bottom: 10px">')
    // Adiciona Wave
    $(win.document.body).prepend('<img src="' + URL_WAVE + '">').css('width', '100%')
    // Titulo Relatório
    $(win.document.body).prepend('<h1></h1>');
    $(win.document.body).find('h1').html('<div style="color: #FFFFFF !important">' + titulo + '</div>')
        .css('text-align', 'right')
        .css('position', 'absolute')
        .css('top', '0')
        .css('right', '0')
        .css('margin-right', '20px')
        .css('font-size', '24px');

    $(win.document.body).find('table').addClass('compact');
}


function printTemplateOmni(win, titulo) {
    $(win.document.body).find('h1').remove();
    $(win.document.body).find('div').remove();

    // Adiciona Data Geração Relatório
    $(win.document.body).prepend('<div style="color: #FFFFFF !important; text-align: right; position:absolute;right:0;top:45px; margin-right: 20px; font-size:20px">' + jsDate + '</div>')
    // Adiciona Logo
    $(win.document.body).prepend('<img src="' + URL_LOGOOMNI + '" style="height:55px; width:180px; margin-left:30px; margin-top:-55px; margin-bottom: 10px">')
    // Adiciona Wave
    $(win.document.body).prepend('<img src="' + URL_WAVE + '">').css('width', '100%')
    // Titulo Relatório
    $(win.document.body).prepend('<h1></h1>');
    $(win.document.body).find('h1').html('<div style="color: #FFFFFF !important">' + titulo + '</div>')
        .css('text-align', 'right')
        .css('position', 'absolute')
        .css('top', '0')
        .css('right', '0')
        .css('margin-right', '20px')
        .css('font-size', '24px');

    $(win.document.body).find('table').addClass('compact');
}

function printTemplateImpressao(win, titulo) {
    $(win.document.body).find('h1').remove();
    $(win.document.body).find('div').remove();

    $(win.document.body).prepend('<div style="color: #FFFFFF !important; text-align: right; position:absolute;right:0;top:45px; margin-right: 20px; font-size:20px"></div>')
    // Adiciona Logo
    $(win.document.body).prepend('<img src="' + URL_LOGOOMNI + '" style="height:55px; width:180px; margin-left:30px; margin-top:-55px; margin-bottom: 10px">')
    // Adiciona Wave
    $(win.document.body).prepend('<img src="' + URL_WAVE + '">').css('width', '100%')
    // Titulo Relatório
    $(win.document.body).prepend('<h1></h1>');
    $(win.document.body).find('h1').html('<div style="color: #FFFFFF !important">' + titulo + '</div>')
        .css('text-align', 'right')
        .css('position', 'absolute')
        .css('top', '0')
        .css('right', '0')
        .css('margin-right', '20px')
        .css('font-size', '24px');

    $(win.document.body).find('table').addClass('compact');
}

function printTemplateImpressaoVeiculos(win, titulo) {
    $(win.document.body).find('h1').remove();
    $(win.document.body).find('div').remove();

    $(win.document.body).prepend('<div style="color: #FFFFFF !important; text-align: right; position:absolute;right:0;top:45px; margin-right: 20px; font-size:20px"></div>')
    // Adiciona Logo
    $(win.document.body).prepend('<img src="' + URL_LOGOOMNI + '" style="height:55px; width:180px; margin-left:30px; margin-top:-55px; margin-bottom: 10px">')
    // Adiciona Wave
    $(win.document.body).prepend('<img src="' + URL_WAVE + '">').css('width', '100%')
    // Titulo Relatório
    $(win.document.body).prepend('<h1></h1>');
    $(win.document.body).find('h1').html('<div style="color: #FFFFFF !important">' + titulo + '</div>')
        .css('text-align', 'right')
        .css('position', 'absolute')
        .css('top', '0')
        .css('right', '0')
        .css('margin-right', '20px')
        .css('font-size', '15px');

    $(win.document.body).find('table').addClass('compact');
}

function printTemplateImpressaoFornecedores(win, titulo) {
    $(win.document.body).find('h1').remove();
    $(win.document.body).find('div').remove();

    $(win.document.body).prepend('<div style="color: #FFFFFF !important; text-align: right; position:absolute;right:0;top:45px; margin-right: 20px; font-size:20px"></div>')
    // Adiciona Logo
    $(win.document.body).prepend('<img src="' + URL_LOGOOMNI + '" style="height:55px; width:180px; margin-left:30px; margin-top:-55px; margin-bottom: 10px">')
    // Adiciona Wave
    $(win.document.body).prepend('<img src="' + URL_WAVE + '">').css('width', '100%')
    // Titulo Relatório
    $(win.document.body).prepend('<h1></h1>');
    $(win.document.body).find('h1').html('<div style="color: #FFFFFF !important">' + titulo + '</div>')
        .css('text-align', 'right')
        .css('position', 'absolute')
        .css('top', '0')
        .css('right', '0')
        .css('margin-right', '20px')
        .css('font-size', '24px');

    $(win.document.body).find('table').addClass('compact');
    $(win.document.head).append('<style>@page { size: landscape; }</style>');
}

function printTemplateOmniComissoes(win, titulo) {
    $(win.document.body).find('h1').remove();
    $(win.document.body).find('div').remove();

    // Adiciona Data Geração Relatório
    $(win.document.body).prepend('<div style="color: #FFFFFF !important; text-align: right; position:absolute;right:0;top:65px; margin-right: 20px; font-size:16px">' + jsDate + '</div>')
    // Adiciona Logo
    $(win.document.body).prepend('<img src="' + URL_LOGOOMNI + '" style="height:55px; width:180px; margin-left:30px; margin-top:-55px; margin-bottom: 10px">')
    // Adiciona Wave
    $(win.document.body).prepend('<img src="' + URL_WAVE + '">').css('width', '100%')
    // Titulo Relatório
    $(win.document.body).prepend('<h1></h1>');
    $(win.document.body).find('h1').html('<div style="color: #FFFFFF !important">' + titulo + '</div>')
        .css('text-align', 'right')
        .css('position', 'absolute')
        .css('top', '0')
        .css('right', '0')
        .css('margin-right', '20px')
        .css('font-size', '14px');

    $(win.document.body).find('table').addClass('compact');
}

function printTemplateFatura(win, titulo) {
    $(win.document.body).find('h1').remove();
    $(win.document.body).find('div').remove();

    // Adiciona Logo
    $(win.document.body).prepend('<img src="' + URL_LOGO + '" style="height:55px; width:180px; margin-left:30px; margin-top:-55px; margin-bottom: 10px">')
    // Adiciona Wave
    $(win.document.body).prepend('<img src="' + URL_WAVE + '">').css('width', '100%')
    // Titulo Relatório
    $(win.document.body).prepend('<h1></h1>');
    $(win.document.body).find('h1').html('<div style="color: #FFFFFF !important">' + titulo + '</div>')
        .css('text-align', 'right')
        .css('position', 'absolute')
        .css('top', '0')
        .css('right', '0')
        .css('margin-right', '20px')
        .css('font-size', '24px');

    $(win.document.body).find('table').addClass('compact');
     $(win.document.body).find('table').css({
        'width': '100%',
        'border-collapse': 'collapse',
        'font-size': '12px',
    });

    $(win.document.body).find('th, td').css({
        'padding': '5px',
        'word-break': 'break-word',
    });
}

// Função que personaliza e gera o PDF a partir do grid
function getDocDefinition(printParams, gridApi, columnApi, infoHeader, titulo, columns) {
    const {
        PDF_HEADER_COLOR,
        PDF_INNER_BORDER_COLOR,
        PDF_OUTER_BORDER_COLOR,
        PDF_ODD_BKG_COLOR,
        PDF_EVEN_BKG_COLOR,
        PDF_HEADER_HEIGHT,
        PDF_ROW_HEIGHT,
        PDF_PAGE_ORITENTATION,
        PDF_WITH_CELL_FORMATTING,
        PDF_WITH_COLUMNS_AS_LINKS,
        PDF_SELECTED_ROWS_ONLY,
        PDF_WITH_HEADER_IMAGE,
        PDF_WITH_FOOTER_PAGE_COUNT,
        PDF_LOGO,
        PDF_HEADER_LOGO,
        PDF_PAGE_SIZE,
        PDF_FONT_SIZE,
    } = printParams;

    let logo = logoGestor;

    if (PDF_HEADER_LOGO == 'omnilink') {
        logo = logoOmni
    } else if (PDF_HEADER_LOGO == 'ceabs') {
        logo = logoCeabs
    }


    return (function() {
        const columnGroupsToExport = getColumnGroupsToExport();

        const columnsToExport = getColumnsToExport(columns);

        const widths = getExportedColumnsWidths(columnsToExport);

        const rowsToExport = getRowsToExport(columnsToExport);

        const body = columnGroupsToExport ? [columnGroupsToExport, columnsToExport, ...rowsToExport] : [columnsToExport, ...rowsToExport];

        const headerRows = columnGroupsToExport ? 2 : 1;

        let image_width_relatorio;

        switch (printParams) {
            case 'A2':
                image_width_relatorio = 2010
                break;
            case 'A3':
                image_width_relatorio = 1510
                break;
            default:
                image_width_relatorio = 1010
                break;
        }

        var header = [
            {
                columns: [
                    {
                        image: wave,
                        width: image_width_relatorio,
                        margin: [-10, -35]
                    }
                ]
            },
            {
                columns: [
                    {
                        image: logo,
                        width: 180,
                        margin: [80, -15, 0, 40],
                        height: 55
                    },
                    {
                        alignment: 'right',
                        text: titulo,
                        color: 'white',
                        fontSize: PDF_FONT_SIZE ? PDF_FONT_SIZE : FONTSIZE_NOMERELATORIO,
                        bold: true,
                        margin: [20, -20]
                    },
                ],
            },
        ];

        new Date().toLocaleString()

        const pageMargins = [
            10,
            90,
            10,
            PDF_WITH_FOOTER_PAGE_COUNT ? 40 : 10
        ];

        const heights = rowIndex =>
            rowIndex < headerRows ? PDF_HEADER_HEIGHT : PDF_ROW_HEIGHT;

        const fillColor = (rowIndex, node, columnIndex) => {
            if (rowIndex < node.table.headerRows) {
                return PDF_HEADER_COLOR;
            }
            return rowIndex % 2 === 0 ? PDF_ODD_BKG_COLOR : PDF_EVEN_BKG_COLOR;
        };

        const hLineWidth = (i, node) =>
            i === 0 || i === node.table.body.length ? 1 : 1;

        const vLineWidth = (i, node) =>
            i === 0 || i === node.table.widths.length ? 1 : 0;

        const hLineColor = (i, node) =>
            i === 0 || i === node.table.body.length ?
            PDF_OUTER_BORDER_COLOR :
            PDF_INNER_BORDER_COLOR;

        const vLineColor = (i, node) =>
            i === 0 || i === node.table.widths.length ?
            PDF_OUTER_BORDER_COLOR :
            PDF_INNER_BORDER_COLOR;

        const docDefintiion = {
            pageOrientation: PDF_PAGE_ORITENTATION,
            pageSize: PDF_PAGE_SIZE,
            header,
            content: [{
                style: "myTable",
                table: {
                    headerRows,
                    widths,
                    body,
                },
                layout: {
                    fillColor,
                    hLineWidth,
                    vLineWidth,
                    hLineColor,
                    vLineColor
                }
            }],
            images: {
                "logo": logoOmni,
                "wave": wave
            },
            styles: {
                myTable: {
                    margin: [0, 0, 0, 0]
                },
                tableHeader: {
                    bold: true,
                    margin: [0, PDF_HEADER_HEIGHT / 3, 0, 0]
                },
                subheader: {
                    fontSize: 8,
                    bold: true
                },
                subheaderTop: {
                    fontSize: 8,
                    bold: true,
                    color: '#fff'
                }
            },
            defaultStyle: {
                fontSize: 6,
            },
            pageMargins,
            footer: {
                alignment: 'right',
                text: (new Date().toLocaleString()).replace(',', ' '), 
                bold: false, 
                fontSize: 12,
                margin: [20, 10]
            }
        };

        return docDefintiion;
    })();
    
    function getColumnGroupsToExport() {
        let displayedColumnGroups = columnApi.getAllDisplayedColumnGroups();

        let isColumnGrouping = displayedColumnGroups.some(col =>
            col.hasOwnProperty("children")
        );

        if (!isColumnGrouping) {
            return null;
        }

        let columnGroupsToExport = [];

        displayedColumnGroups.forEach(colGroup => {
            let isColSpanning = colGroup.children.length > 1;
            let numberOfEmptyHeaderCellsToAdd = 0;

            if (isColSpanning) {
                let headerCell = createHeaderCell(colGroup);
                columnGroupsToExport.push(headerCell);
                // subtract 1 as the column group counts as a header
                numberOfEmptyHeaderCellsToAdd--;
            }

            // add an empty header cell now for every column being spanned
            colGroup.displayedChildren.forEach(childCol => {
                let pdfExportOptions = getPdfExportOptions(childCol.getColId());
                if (!pdfExportOptions || !pdfExportOptions.skipColumn) {
                    numberOfEmptyHeaderCellsToAdd++;
                }
            });

            for (let i = 0; i < numberOfEmptyHeaderCellsToAdd; i++) {
                columnGroupsToExport.push({});
            }
        });

        return columnGroupsToExport;
    }

    function getColumnsToExport(columns) {
        let columnsToExport = [];

        if (columns) {
            columnApi.getAllColumns().forEach(col => {
                if (columns.includes(col.getColId())){
                    let pdfExportOptions = getPdfExportOptions(col.getColId());
                    if ((pdfExportOptions && pdfExportOptions.skipColumn) || (col.getColId() == 0)) {
                        return;
                    }
                    let headerCell = createHeaderCell(col);
                    columnsToExport.push(headerCell);
                }
                
            });
        } else {
            columnApi.getAllDisplayedColumns().forEach(col => {
                let pdfExportOptions = getPdfExportOptions(col.getColId());
                if ((pdfExportOptions && pdfExportOptions.skipColumn) || (col.getColId() == 0)) {
                    return;
                }
                let headerCell = createHeaderCell(col);
                columnsToExport.push(headerCell);
            });
        }

        return columnsToExport;
    }

    function getRowsToExport(columnsToExport) {
        let rowsToExport = [];

        var rowModelType = gridApi.getModel().getType();

        if (rowModelType === 'clientSide') {
            // Client-side Row Model
            gridApi.forEachNodeAfterFilterAndSort(node => {
                if (PDF_SELECTED_ROWS_ONLY && !node.isSelected()) {
                    return;
                }
                if (node.group) return;
                let rowToExport = columnsToExport.map(({
                    colId
                }) => {
                    let cellValue = gridApi.getValue(colId, node);
                    let tableCell = createTableCell(cellValue, colId);
                    return tableCell;
                });
                rowsToExport.push(rowToExport);
            });
        } else {
            gridApi.forEachNode(node => {
                if (PDF_SELECTED_ROWS_ONLY && !node.isSelected()) {
                    return;
                }
                if (node.group) return;
                let rowToExport = columnsToExport.map(({ colId }) => {
                    let cellValue = gridApi.getValue(colId, node);
                    let tableCell = createTableCell(cellValue, colId);
                    return tableCell;
                });
                rowsToExport.push(rowToExport);
            });
        }

        return rowsToExport;
    }

    function getExportedColumnsWidths(columnsToExport) {
        return columnsToExport.map(() => 100 / columnsToExport.length + "%");
    }

    function createHeaderCell(col) {
        let headerCell = {};

        let isColGroup = col.hasOwnProperty("children");

        if (isColGroup) {
            headerCell.text = col.originalColumnGroup.colGroupDef.headerName;
            headerCell.colSpan = col.children.length;
            headerCell.colId = col.groupId;
        } else {
            let headerName = col.colDef.headerName;

            if (col.sort) {
                headerName += ` (${col.sort})`;
            }
            if (col.filterActive) {
                headerName += ` [FILTERING]`;
            }

            headerCell.text = headerName;
            headerCell.colId = col.getColId();
        }

        headerCell["style"] = "tableHeader";

        return headerCell;
    }

    function createTableCell(cellValue, colId) {
        const tableCell = {
            text: cellValue !== undefined ? cellValue : "",
            style: "tableCell"
        };

        const pdfExportOptions = getPdfExportOptions(colId);

        if (pdfExportOptions) {
            const {
                styles,
                createURL
            } = pdfExportOptions;

            if (PDF_WITH_CELL_FORMATTING && styles) {
                Object.entries(styles).forEach(
                    ([key, value]) => (tableCell[key] = value)
                );
            }

            if (PDF_WITH_COLUMNS_AS_LINKS && createURL) {
                tableCell["link"] = createURL(cellValue);
                tableCell["color"] = "blue";
                tableCell["decoration"] = "underline";
            }
        }

        return tableCell;
    }

    function getPdfExportOptions(colId) {
        let col = columnApi.getColumn(colId);
        return col.colDef.pdfExportOptions;
    }
}


function getCustomDocDefinition(printParams, gridApi, columnApi, infoHeader, titulo, columns) {
    const {
        PDF_HEADER_COLOR,
        PDF_INNER_BORDER_COLOR,
        PDF_OUTER_BORDER_COLOR,
        PDF_ODD_BKG_COLOR,
        PDF_EVEN_BKG_COLOR,
        PDF_HEADER_HEIGHT,
        PDF_ROW_HEIGHT,
        PDF_PAGE_ORITENTATION,
        PDF_WITH_CELL_FORMATTING,
        PDF_WITH_COLUMNS_AS_LINKS,
        PDF_SELECTED_ROWS_ONLY,
        PDF_WITH_HEADER_IMAGE,
        PDF_WITH_FOOTER_PAGE_COUNT,
        PDF_LOGO,
        PDF_HEADER_LOGO,
        PDF_PAGE_SIZE,
        PDF_FONT_SIZE,
        PDF_BODY_FONT_SIZE,
    } = printParams;

    let logo = logoGestor;

    if (PDF_HEADER_LOGO == 'omnilink') {
        logo = logoOmni
    } else if (PDF_HEADER_LOGO == 'ceabs') {
        logo = logoCeabs
    }


    return (function() {
        const columnGroupsToExport = getColumnGroupsToExport();

        const columnsToExport = getColumnsToExport(columns);

        const widths = getExportedColumnsWidths(columnsToExport);

        const rowsToExport = getRowsToExport(columnsToExport);

        const body = columnGroupsToExport ? [columnGroupsToExport, columnsToExport, ...rowsToExport] : [columnsToExport, ...rowsToExport];

        const headerRows = columnGroupsToExport ? 2 : 1;

        let image_width_relatorio;

        switch (printParams) {
            case 'A2':
                image_width_relatorio = 2010
                break;
            case 'A3':
                image_width_relatorio = 1510
                break;
            default:
                image_width_relatorio = 1010
                break;
        }

        var header = [
            {
                columns: [
                    {
                        image: wave,
                        width: image_width_relatorio,
                        margin: [-10, -35]
                    }
                ]
            },
            {
                columns: [
                    {
                        image: logo,
                        width: 180,
                        margin: [80, -15, 0, 40],
                        height: 55
                    },
                    {
                        alignment: 'right',
                        text: titulo,
                        color: 'white',
                        fontSize: PDF_FONT_SIZE ? PDF_FONT_SIZE : FONTSIZE_NOMERELATORIO,
                        bold: true,
                        margin: [20, -20]
                    },
                ],
            },
        ];

        new Date().toLocaleString()

        const pageMargins = [
            10,
            90,
            10,
            PDF_WITH_FOOTER_PAGE_COUNT ? 40 : 10
        ];

        const heights = rowIndex =>
            rowIndex < headerRows ? PDF_HEADER_HEIGHT : PDF_ROW_HEIGHT;

        const fillColor = (rowIndex, node, columnIndex) => {
            if (rowIndex < node.table.headerRows) {
                return PDF_HEADER_COLOR;
            }
            return rowIndex % 2 === 0 ? PDF_ODD_BKG_COLOR : PDF_EVEN_BKG_COLOR;
        };

        const hLineWidth = (i, node) =>
            i === 0 || i === node.table.body.length ? 1 : 1;

        const vLineWidth = (i, node) =>
            i === 0 || i === node.table.widths.length ? 1 : 0;

        const hLineColor = (i, node) =>
            i === 0 || i === node.table.body.length ?
            PDF_OUTER_BORDER_COLOR :
            PDF_INNER_BORDER_COLOR;

        const vLineColor = (i, node) =>
            i === 0 || i === node.table.widths.length ?
            PDF_OUTER_BORDER_COLOR :
            PDF_INNER_BORDER_COLOR;

        const docDefintiion = {
            pageOrientation: PDF_PAGE_ORITENTATION,
            pageSize: PDF_PAGE_SIZE,
            header,
            content: [{
                style: "myTable",
                table: {
                    headerRows,
                    widths,
                    body,
                },
                layout: {
                    fillColor,
                    hLineWidth,
                    vLineWidth,
                    hLineColor,
                    vLineColor
                }
            }],
            images: {
                "logo": logoOmni,
                "wave": wave
            },
            styles: {
                myTable: {
                    margin: [0, 0, 0, 0]
                },
                tableHeader: {
                    bold: true,
                    margin: [0, PDF_HEADER_HEIGHT / 3, 0, 0]
                },
                subheader: {
                    fontSize: 8,
                    bold: true
                },
                subheaderTop: {
                    fontSize: 8,
                    bold: true,
                    color: '#fff'
                }
            },
            defaultStyle: {
                fontSize: PDF_BODY_FONT_SIZE != undefined && PDF_BODY_FONT_SIZE != null ? PDF_BODY_FONT_SIZE : 6,
            },
            pageMargins,
            footer: {
                alignment: 'right',
                text: (new Date().toLocaleString()).replace(',', ' '), 
                bold: false, 
                fontSize: 12,
                margin: [20, 10]
            }
        };

        return docDefintiion;
    })();
    
    function getColumnGroupsToExport() {
        let displayedColumnGroups = columnApi.getAllDisplayedColumnGroups();

        let isColumnGrouping = displayedColumnGroups.some(col =>
            col.hasOwnProperty("children")
        );

        if (!isColumnGrouping) {
            return null;
        }

        let columnGroupsToExport = [];

        displayedColumnGroups.forEach(colGroup => {
            let isColSpanning = colGroup.children.length > 1;
            let numberOfEmptyHeaderCellsToAdd = 0;

            if (isColSpanning) {
                let headerCell = createHeaderCell(colGroup);
                columnGroupsToExport.push(headerCell);
                // subtract 1 as the column group counts as a header
                numberOfEmptyHeaderCellsToAdd--;
            }

            // add an empty header cell now for every column being spanned
            colGroup.displayedChildren.forEach(childCol => {
                let pdfExportOptions = getPdfExportOptions(childCol.getColId());
                if (!pdfExportOptions || !pdfExportOptions.skipColumn) {
                    numberOfEmptyHeaderCellsToAdd++;
                }
            });

            for (let i = 0; i < numberOfEmptyHeaderCellsToAdd; i++) {
                columnGroupsToExport.push({});
            }
        });

        return columnGroupsToExport;
    }

    function getColumnsToExport(columns) {
        let columnsToExport = [];

        if (columns) {
            columnApi.getAllColumns().forEach(col => {
                if (columns.includes(col.getColId())){
                    let pdfExportOptions = getPdfExportOptions(col.getColId());
                    if ((pdfExportOptions && pdfExportOptions.skipColumn) || (col.getColId() == 0)) {
                        return;
                    }
                    let headerCell = createHeaderCell(col);
                    columnsToExport.push(headerCell);
                }
                
            });
        } else {
            columnApi.getAllDisplayedColumns().forEach(col => {
                let pdfExportOptions = getPdfExportOptions(col.getColId());
                if ((pdfExportOptions && pdfExportOptions.skipColumn) || (col.getColId() == 0)) {
                    return;
                }
                let headerCell = createHeaderCell(col);
                columnsToExport.push(headerCell);
            });
        }

        return columnsToExport;
    }

    function getRowsToExport(columnsToExport) {
        let rowsToExport = [];

        var rowModelType = gridApi.getModel().getType();

        if (rowModelType === 'clientSide') {
            // Client-side Row Model
            gridApi.forEachNodeAfterFilterAndSort(node => {
                if (PDF_SELECTED_ROWS_ONLY && !node.isSelected()) {
                    return;
                }
                if (node.group) return;
                let rowToExport = columnsToExport.map(({
                    colId
                }) => {
                    let cellValue = gridApi.getValue(colId, node);
                    let tableCell = createTableCell(cellValue, colId);
                    return tableCell;
                });
                rowsToExport.push(rowToExport);
            });
        } else {
            gridApi.forEachNode(node => {
                if (PDF_SELECTED_ROWS_ONLY && !node.isSelected()) {
                    return;
                }
                if (node.group) return;
                let rowToExport = columnsToExport.map(({ colId }) => {
                    let cellValue = gridApi.getValue(colId, node);
                    let tableCell = createTableCell(cellValue, colId);
                    return tableCell;
                });
                rowsToExport.push(rowToExport);
            });
        }

        return rowsToExport;
    }

    function getExportedColumnsWidths(columnsToExport) {
        return columnsToExport.map(() => 100 / columnsToExport.length + "%");
    }

    function createHeaderCell(col) {
        let headerCell = {};

        let isColGroup = col.hasOwnProperty("children");

        if (isColGroup) {
            headerCell.text = col.originalColumnGroup.colGroupDef.headerName;
            headerCell.colSpan = col.children.length;
            headerCell.colId = col.groupId;
        } else {
            let headerName = col.colDef.headerName;

            if (col.sort) {
                headerName += ` (${col.sort})`;
            }
            if (col.filterActive) {
                headerName += ` [FILTERING]`;
            }

            headerCell.text = headerName;
            headerCell.colId = col.getColId();
        }

        headerCell["style"] = "tableHeader";

        return headerCell;
    }

    function createTableCell(cellValue, colId) {
        const tableCell = {
            text: cellValue !== undefined ? cellValue : "",
            style: "tableCell"
        };

        const pdfExportOptions = getPdfExportOptions(colId);

        if (pdfExportOptions) {
            const {
                styles,
                createURL
            } = pdfExportOptions;

            if (PDF_WITH_CELL_FORMATTING && styles) {
                Object.entries(styles).forEach(
                    ([key, value]) => (tableCell[key] = value)
                );
            }

            if (PDF_WITH_COLUMNS_AS_LINKS && createURL) {
                tableCell["link"] = createURL(cellValue);
                tableCell["color"] = "blue";
                tableCell["decoration"] = "underline";
            }
        }

        return tableCell;
    }

    function getPdfExportOptions(colId) {
        let col = columnApi.getColumn(colId);
        return col.colDef.pdfExportOptions;
    }
}

function getCustomDocDefinitionColumn0(printParams, gridApi, columnApi, infoHeader, titulo, columns) {
    const {
        PDF_HEADER_COLOR,
        PDF_INNER_BORDER_COLOR,
        PDF_OUTER_BORDER_COLOR,
        PDF_ODD_BKG_COLOR,
        PDF_EVEN_BKG_COLOR,
        PDF_HEADER_HEIGHT,
        PDF_ROW_HEIGHT,
        PDF_PAGE_ORITENTATION,
        PDF_WITH_CELL_FORMATTING,
        PDF_WITH_COLUMNS_AS_LINKS,
        PDF_SELECTED_ROWS_ONLY,
        PDF_WITH_HEADER_IMAGE,
        PDF_WITH_FOOTER_PAGE_COUNT,
        PDF_LOGO,
        PDF_HEADER_LOGO,
        PDF_PAGE_SIZE,
        PDF_FONT_SIZE,
        PDF_BODY_FONT_SIZE,
    } = printParams;

    let logo = logoGestor;

    if (PDF_HEADER_LOGO == 'omnilink') {
        logo = logoOmni
    } else if (PDF_HEADER_LOGO == 'ceabs') {
        logo = logoCeabs
    }


    return (function() {
        const columnGroupsToExport = getColumnGroupsToExport();

        const columnsToExport = getColumnsToExport(columns);

        const widths = getExportedColumnsWidths(columnsToExport);

        const rowsToExport = getRowsToExport(columnsToExport);

        const body = columnGroupsToExport ? [columnGroupsToExport, columnsToExport, ...rowsToExport] : [columnsToExport, ...rowsToExport];

        const headerRows = columnGroupsToExport ? 2 : 1;

        let image_width_relatorio;

        switch (printParams) {
            case 'A2':
                image_width_relatorio = 2010
                break;
            case 'A3':
                image_width_relatorio = 1510
                break;
            default:
                image_width_relatorio = 1010
                break;
        }

        var header = [
            {
                columns: [
                    {
                        image: wave,
                        width: image_width_relatorio,
                        margin: [-10, -35]
                    }
                ]
            },
            {
                columns: [
                    {
                        image: logo,
                        width: 180,
                        margin: [80, -15, 0, 40],
                        height: 55
                    },
                    {
                        alignment: 'right',
                        text: titulo,
                        color: 'white',
                        fontSize: PDF_FONT_SIZE ? PDF_FONT_SIZE : FONTSIZE_NOMERELATORIO,
                        bold: true,
                        margin: [20, -20]
                    },
                ],
            },
        ];

        new Date().toLocaleString()

        const pageMargins = [
            10,
            90,
            10,
            PDF_WITH_FOOTER_PAGE_COUNT ? 40 : 10
        ];

        const heights = rowIndex =>
            rowIndex < headerRows ? PDF_HEADER_HEIGHT : PDF_ROW_HEIGHT;

        const fillColor = (rowIndex, node, columnIndex) => {
            if (rowIndex < node.table.headerRows) {
                return PDF_HEADER_COLOR;
            }
            return rowIndex % 2 === 0 ? PDF_ODD_BKG_COLOR : PDF_EVEN_BKG_COLOR;
        };

        const hLineWidth = (i, node) =>
            i === 0 || i === node.table.body.length ? 1 : 1;

        const vLineWidth = (i, node) =>
            i === 0 || i === node.table.widths.length ? 1 : 0;

        const hLineColor = (i, node) =>
            i === 0 || i === node.table.body.length ?
            PDF_OUTER_BORDER_COLOR :
            PDF_INNER_BORDER_COLOR;

        const vLineColor = (i, node) =>
            i === 0 || i === node.table.widths.length ?
            PDF_OUTER_BORDER_COLOR :
            PDF_INNER_BORDER_COLOR;

        const docDefintiion = {
            pageOrientation: PDF_PAGE_ORITENTATION,
            pageSize: PDF_PAGE_SIZE,
            header,
            content: [{
                style: "myTable",
                table: {
                    headerRows,
                    widths,
                    body,
                },
                layout: {
                    fillColor,
                    hLineWidth,
                    vLineWidth,
                    hLineColor,
                    vLineColor
                }
            }],
            images: {
                "logo": logoOmni,
                "wave": wave
            },
            styles: {
                myTable: {
                    margin: [0, 0, 0, 0]
                },
                tableHeader: {
                    bold: true,
                    margin: [0, PDF_HEADER_HEIGHT / 3, 0, 0]
                },
                subheader: {
                    fontSize: 8,
                    bold: true
                },
                subheaderTop: {
                    fontSize: 8,
                    bold: true,
                    color: '#fff'
                }
            },
            defaultStyle: {
                fontSize: PDF_BODY_FONT_SIZE != undefined && PDF_BODY_FONT_SIZE != null ? PDF_BODY_FONT_SIZE : 6,
            },
            pageMargins,
            footer: {
                alignment: 'right',
                text: (new Date().toLocaleString()).replace(',', ' '), 
                bold: false, 
                fontSize: 12,
                margin: [20, 10]
            }
        };

        return docDefintiion;
    })();
    
    function getColumnGroupsToExport() {
        let displayedColumnGroups = columnApi.getAllDisplayedColumnGroups();

        let isColumnGrouping = displayedColumnGroups.some(col =>
            col.hasOwnProperty("children")
        );

        if (!isColumnGrouping) {
            return null;
        }

        let columnGroupsToExport = [];

        displayedColumnGroups.forEach(colGroup => {
            let isColSpanning = colGroup.children.length > 1;
            let numberOfEmptyHeaderCellsToAdd = 0;

            if (isColSpanning) {
                let headerCell = createHeaderCell(colGroup);
                columnGroupsToExport.push(headerCell);
                // subtract 1 as the column group counts as a header
                numberOfEmptyHeaderCellsToAdd--;
            }

            // add an empty header cell now for every column being spanned
            colGroup.displayedChildren.forEach(childCol => {
                let pdfExportOptions = getPdfExportOptions(childCol.getColId());
                if (!pdfExportOptions || !pdfExportOptions.skipColumn) {
                    numberOfEmptyHeaderCellsToAdd++;
                }
            });

            for (let i = 0; i < numberOfEmptyHeaderCellsToAdd; i++) {
                columnGroupsToExport.push({});
            }
        });

        return columnGroupsToExport;
    }

    function getColumnsToExport(columns) {
        let columnsToExport = [];

        if (columns) {
            columnApi.getAllColumns().forEach(col => {
                if (columns.includes(col.getColId())){
                    let pdfExportOptions = getPdfExportOptions(col.getColId());
                    if ((pdfExportOptions && pdfExportOptions.skipColumn)) {
                        return;
                    }
                    let headerCell = createHeaderCell(col);
                    columnsToExport.push(headerCell);
                }
                
            });
        } else {
            columnApi.getAllDisplayedColumns().forEach(col => {
                let pdfExportOptions = getPdfExportOptions(col.getColId());
                if ((pdfExportOptions && pdfExportOptions.skipColumn)) {
                    return;
                }
                let headerCell = createHeaderCell(col);
                columnsToExport.push(headerCell);
            });
        }

        return columnsToExport;
    }

    function getRowsToExport(columnsToExport) {
        let rowsToExport = [];

        var rowModelType = gridApi.getModel().getType();

        if (rowModelType === 'clientSide') {
            // Client-side Row Model
            gridApi.forEachNodeAfterFilterAndSort(node => {
                if (PDF_SELECTED_ROWS_ONLY && !node.isSelected()) {
                    return;
                }
                if (node.group) return;
                let rowToExport = columnsToExport.map(({
                    colId
                }) => {
                    let cellValue = gridApi.getValue(colId, node);
                    let tableCell = createTableCell(cellValue, colId);
                    return tableCell;
                });
                rowsToExport.push(rowToExport);
            });
        } else {
            gridApi.forEachNode(node => {
                if (PDF_SELECTED_ROWS_ONLY && !node.isSelected()) {
                    return;
                }
                if (node.group) return;
                let rowToExport = columnsToExport.map(({ colId }) => {
                    let cellValue = gridApi.getValue(colId, node);
                    let tableCell = createTableCell(cellValue, colId);
                    return tableCell;
                });
                rowsToExport.push(rowToExport);
            });
        }

        return rowsToExport;
    }

    function getExportedColumnsWidths(columnsToExport) {
        return columnsToExport.map(() => 100 / columnsToExport.length + "%");
    }

    function createHeaderCell(col) {
        let headerCell = {};

        let isColGroup = col.hasOwnProperty("children");

        if (isColGroup) {
            headerCell.text = col.originalColumnGroup.colGroupDef.headerName;
            headerCell.colSpan = col.children.length;
            headerCell.colId = col.groupId;
        } else {
            let headerName = col.colDef.headerName;

            if (col.sort) {
                headerName += ` (${col.sort})`;
            }
            if (col.filterActive) {
                headerName += ` [FILTERING]`;
            }

            headerCell.text = headerName;
            headerCell.colId = col.getColId();
        }

        headerCell["style"] = "tableHeader";

        return headerCell;
    }

    function createTableCell(cellValue, colId) {
        const tableCell = {
            text: cellValue !== undefined ? cellValue : "",
            style: "tableCell"
        };

        const pdfExportOptions = getPdfExportOptions(colId);

        if (pdfExportOptions) {
            const {
                styles,
                createURL
            } = pdfExportOptions;

            if (PDF_WITH_CELL_FORMATTING && styles) {
                Object.entries(styles).forEach(
                    ([key, value]) => (tableCell[key] = value)
                );
            }

            if (PDF_WITH_COLUMNS_AS_LINKS && createURL) {
                tableCell["link"] = createURL(cellValue);
                tableCell["color"] = "blue";
                tableCell["decoration"] = "underline";
            }
        }

        return tableCell;
    }

    function getPdfExportOptions(colId) {
        let col = columnApi.getColumn(colId);
        return col.colDef.pdfExportOptions;
    }
}



/**
 * Função que personaliza o header da página para pdf
 * @param {String} orientation - String que informa a orientação da página (portrait ou landscape)
 * @param {String} titulo - String que contém o nome do relatório
 */
function customHeader(orientation, titulo, pagesize, date = false) {

    let image_width;

    switch (pagesize) {
        case 'A2':
            image_width = 2010
            break;
        case 'A3':
            image_width = 1510
            break;
        default:
            image_width = 1010
            break;
    }

    // se a página estiver na horizontal
    if (orientation == "landscape") {
        var header = [
            // Imagem onda azul no topo da página
            {
                columns: [
                    {
                        image: wave,
                        width: image_width,
                        margin: [-40, -50]
                    }
                ]
            },
            // Logo e nome do relatório
            {
                columns: [
                    {
                        // image: logo,
                        image: logoGestor,
                        width: 180,
                        margin: [0, -5, 0, 40],
                        height: 55
                    },
                    {
                        alignment: 'right',
                        // italics: true,
                        text: titulo,
                        color: 'white',
                        fontSize: FONTSIZE_NOMERELATORIO,
                        bold: true,
                        margin: [20, -20]
                    },
                ],
            },
            // Data Geração do Relatório
            {
                columns: [
                    {
                        alignment: 'right',
                        text: date ? date : jsDate,
                        color: 'white',
                        fontSize: FONTSIZE_DATA,
                        //margin: [20,-35]
                        margin: [20, -85]
                    }]
            },

        ];

        return header;

    } else { // caso a página esteja na vertical (portrait)

        var header = [
            // imagem onda azul no topo da página
            {
                columns: [
                    {
                        image: wave,
                        width: 650,
                        margin: [-40, -40]
                    }
                ]
            },
            // Logo e nome do relatório
            {
                columns: [
                    {
                        // image: logo,
                        image: logoGestor,
                        width: 120,
                        margin: [0, -10]

                    },
                    {
                        alignment: 'right',
                        // italics: true,
                        text: titulo,
                        color: 'white',
                        fontSize: FONTSIZE_NOMERELATORIO,
                        bold: true,
                        margin: [10, -20]
                    },
                ],
            },
            // Data Geração do Relatório
            {
                columns: [
                    {
                        alignment: 'right',
                        text: date ? date : jsDate,
                        color: 'white',
                        fontSize: FONTSIZE_DATA,
                        margin: [10, -25]
                    }]
            }
        ];

        return header;

    }
}


function customHeaderOmni(orientation, titulo, pagesize) {

    let image_width;

    switch (pagesize) {
        case 'A2':
            image_width = 2010
            break;
        case 'A3':
            image_width = 1510
            break;
        default:
            image_width = 1010
            break;
    }

    // se a página estiver na horizontal
    if (orientation == "landscape") {
        var header = [
            // Imagem onda azul no topo da página
            {
                columns: [
                    {
                        image: wave,
                        width: image_width,
                        margin: [-40, -50]
                    }
                ]
            },
            // Logo e nome do relatório
            {
                columns: [
                    {
                        // image: logo,
                        image: logoOmni,
                        width: 180,
                        margin: [0, -5, 0, 40],
                        height: 55
                    },
                    {
                        alignment: 'right',
                        // italics: true,
                        text: titulo,
                        color: 'white',
                        fontSize: FONTSIZE_NOMERELATORIO,
                        bold: true,
                        margin: [20, -20]
                    },
                ],
            },
        ];

        return header;

    } else { // caso a página esteja na vertical (portrait)

        var header = [
            // imagem onda azul no topo da página
            {
                columns: [
                    {
                        image: wave,
                        width: 650,
                        margin: [-40, -40]
                    }
                ]
            },
            // Logo e nome do relatório
            {
                columns: [
                    {
                        // image: logo,
                        image: logoOmni,
                        width: 120,
                        margin: [0, -10]

                    },
                    {
                        alignment: 'right',
                        // italics: true,
                        text: titulo,
                        color: 'white',
                        fontSize: FONTSIZE_NOMERELATORIO,
                        bold: true,
                        margin: [10, -20]
                    },
                ],
            },
        ];

        return header;

    }
}

function createFooter(texto) {
    return {
        text: texto,
        alignment: 'right',
        fontSize: FONTSIZE_NOMERELATORIO,
        color: 'black',
        margin: [40, -20],
        bold: true
    };
}

function pdfTemplateICalculoComissao(doc, titulo, pagesize = 'A4', widths = false, columnAlignments = [], headerFontSize = 11, bodyFontSize = 10) {
    // Remove primeira linha da exportação e substitui pelo cabeçalho
    doc.content.splice(0, 1, customHeaderOmni(doc.pageOrientation, titulo, pagesize));
    // Ajusta tamanho da tabela
    if (widths) {
        if (typeof doc.content[1].table !== 'undefined') doc.content[1].table.widths = widths;        

    } else {
        if (typeof doc.content[1].table !== 'undefined') doc.content[1].table.widths = '*';        
    }


    if (columnAlignments.length > 0 && typeof doc.content[1].table !== 'undefined') {
        doc.content[1].table.body.forEach(row => {
            for (let i = 0; i < row.length; i++) {
                if (columnAlignments[i]) {
                    row[i].alignment = columnAlignments[i];
                }
            }
        });
    } else {
        doc.styles.tableBodyEven = { 
            alignment: 'center',
            fontSize: bodyFontSize
        };
        doc.styles.tableBodyOdd = { 
            alignment: 'center', 
            fillColor: '#f3f3f3',
            fontSize: bodyFontSize
        };
    }

    // Estilo header tabela
    doc.content[1].layout = 'headerLineOnly';
    doc.styles.tableHeader = {
        fillColor: 'white',
        color: 'black',
        bold: true,
        fontSize: headerFontSize,
        width: '*',
        alignment: 'center'
    }
    
    const cabecalho = doc.content[1].table.body.shift();

    let devolucoes = [];
    let vendas = [];
    let totalHWDevolucoes = 0;
    let totalComissoesDevolucoes = 0;
    let totalHWVendas = 0;
    let totalComissoesVendas = 0;

    doc.content[1].table.body.forEach(row => {
        if (row.length > 4 && row[4] && row[4].text) {
            let dadosHw = row[4].text.trim();
            if (dadosHw.startsWith('-R$')) {
                devolucoes.push(row);
                totalHWDevolucoes += parseFloat(row[4].text.replace('-R$', '').replaceAll('.', '').replace(',', '.').trim());
                totalComissoesDevolucoes += parseFloat(row[6].text.replace('-R$', '').replaceAll('.', '').replace(',', '.').trim());
            } else if (dadosHw.startsWith('R$')) {
                vendas.push(row);
                totalHWVendas += parseFloat(row[4].text.replace('R$', '').replaceAll('.', '').replace(',', '.').trim());
                totalComissoesVendas += parseFloat(row[6].text.replace('R$', '').replaceAll('.', '').replace(',', '.').trim());
            }
        }
    });

    doc.content[1].table.body = [];

    doc.content[1].table.body.push(cabecalho);

    if (vendas.length > 0) {
        doc.content[1].table.body.push([{ text: 'Vendas', colSpan: 7, bold: true, fontSize: 14, fillColor: '#f3f3f3',alignment:     'center' }]);

        doc.content[1].table.body.push(...vendas);

        doc.content[1].table.body.push([
            { text: `Total:`, bold: true, fontSize: 13, alignment: 'left' }, '', '', '', { text: `${formatarMoeda(totalHWVendas)}`, bold:   true, fontSize: 13, alignment: 'right' },
        '', { text: `${formatarMoeda(totalComissoesVendas)}`, bold: true, fontSize: 13, alignment: 'right' }
        ]);
    }

    if (devolucoes.length > 0) {
        doc.content[1].table.body.push([{ text: 'Devoluções', colSpan: 7, bold: true, fontSize: 14, fillColor:'#f3f3f3',   alignment:   'center' }]);

        doc.content[1].table.body.push(...devolucoes);

        doc.content[1].table.body.push([
            { text: `Total:`, bold: true, fontSize: 13, alignment: 'left' }, '', '', '', { text: `-${formatarMoeda(totalHWDevolucoes)}`,    bold: true, fontSize: 13, alignment: 'right' },
            '', { text: `-${formatarMoeda(totalComissoesDevolucoes)}`, bold: true, fontSize: 13, alignment: 'right' }
        ]);
    }
}  

function formatarMoeda(valor) {
    valor = parseFloat(valor.toFixed(2));
    var valorString = valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    valorString = 'R$ ' + valorString;
  
    return valorString;
}
// Imagens Base64 usadas para renderizar o PDF
const logoOmni = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAoUAAAD/CAYAAACHD6xaAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAACwCSURBVHhe7d1fbtxGtsdxUuo8jwf2fY4DxAHuUzwrsL2C2AuIHa0gyQoSryDOCvxvAXZWMPEK4jwNEAeI8zwORvd5WuLlj01K7Ta7SRbrkIfk9wPUSPJEEkUWqw5P/WECAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAdJWWHwEAaPJ9+TG2J3l5u/kUM3Y9L19tPo3Oqm4CAIAdV/KSGZWbecH8KXCru/59yz/zggiOyo8AAH9+yEtdJ9i3vMhLV7fLj7Gd5uX15tNO/pOXur+tb7mbF9i4VX6M7VX5ET0RFAKAX1aBWEgnapXNCwkINQypzKWFkONBO57qEGoQFAKAX546UU9ZHqvzoqwlcxtt6JoRyDvHQpPpq240PTmrVD7PS90NuN0Aq/HbLgD8UJbQaq5USNuv4VqLTv1eXl5uPm1Nw+rfbD6NSseh40F8WmDyePNpVOq7Ptl8CiyHGmPNddFEXXUUf+Slbj5Mn6Kfq8ZWN+92gAlgeAp66u7TvuWXvHSl9qDuZ8UoIW2N2qq6n9W3sILVjqf5scAkKQjUjaRGvO5msC76vfr9VkM1APZTVqXuvuxbQrI1aovqflbfouxjiLqfFaNYzeGEXT9GII9ZU6OkRttqZV1o0Q2tDKLVnBAA77MYDVAJ2SfOU5ZHD6l1PytGoX2zU3e+YxQCecyOGiINFVl1AjGLglU9mdF4AnZ0f9XdfzFKSObf03Ctgtq6n9W3qP2FDQVudec8RgFmQw2/GkVvWcE2RcdsMdEbgL9OtO7nxCghWR5Pw+pox9P8WBzAljTjUTCoJ9Pv8jLFrJuOuZrvyJxDIC6rIbGfy49dWN7fIVuJWB3Pr+VHxGe1nRFb0WDy1NhPYZi4S1HWMGSeEoB6DNfuV/ezYhSrQBx2fR79DiaryqzVVey5FIZfgDisppSEvMLN0yITb8PqaOZtfiwwOlVcq+X43ooa+ikOhwNeeNsT0KrtCpmTrExn3c/qW5SZhQ2r7YxUEBlzCu0pva0GZylPNGoA9PcSGAJhrNqK0Fe4WR1PyHwwvanJAnPT7FjVn5D5sWhAUGhLT8IaUl1agKRGQENOALqzmpQf0olazrPztOiFRSZ2PL0zGw0ICu0oGFxyYKQMKYEh0J2nwMdTllAP11av3yRTaMdTHQJGYbWP1hRLyMR2AD5YtWUsSlsGBYR11z9GsXpAWDQyhfEpO8Yy+UtLHD4H5oLhWvRhVX80NzZkfiwaEBTGpWCQt3y8TwEhw8jA9OjeterUWSSwDCwMwmJZ7p81h8LGsMC0sCcg+rLazihkE3a0kJYf0Y+eqLVj+5jDpEql6+lJwzL6qO0ntumJ/+O8qKG3evo/RJmBO5tPAUyAOl69hjM22oLlUABnQfWHbDPcsnolVVPRWw80NNs1yNMEXX2f1VsT9hWyhcB0aCP6uvu4b2E6yTKQacYiaQ5hXaW1LArm9BTfNzOp77dq+OtKyGutAIyD99WiD6u+UUPSgEvKuA2dbbN4jdyQgS3bCAD+qY2pu39jlDGmr2B4VgkHtjMyxJzCflTph9yH79u8PNp8Gp2e3oe42R7mZaqThBXQ7gtqx57fcujY2L5hQ8HIvgcqzcFlReMltWsWmX2d579vPkUkh6bljHnvK9NskQQ4ycuTzaeAH5bzJXaLspFDzMfTXJ+63x+zqKGYCgURCmDbzhnVsEbIHM8Q6rT1u7rMZ9V/q+9ZwobiugbKgCuw6TIMWl3DpW+6rnpfd376FtVBhNu+77uMUum/1/1gEaTVIdOMxenSGfcpuvGHvAms5hFtl7YNkxq/uu8PLW2oMVOH2Pc8KLiIHVjovOmcxJiyoJ8RY17qrtjBRJcgojo/seqwfo7FOarjrRO1at90Pruymt7SpW7VfX+f0uU8qB2JORSrv9s6yaBjrvvdMUoo3Qcx2s5DRT9/qMAbjgyZJRw6YzHE36ZGvo2YHVNTB1AFg7EbDf3evo2Evt/qdWOxg8OY10ylzRCm6mzMTnO36BxZ34dWnaiOPUTs+6AqIefRqu53mS5T9/19SpugTFN6LB/SY7RN+6hNqfudfUtTO76P2jerPROronuGLOZCxe749pW2wVNs1jdP27lKMc+zMkj76DxbdYIqoUGFGrLY2dJ9Rdc8RoMW+944lFHR+bEKGOqKfles4HmXp05UgULdz4pRQoIQq/aobftq8aB86Dzo9w0xYqOitsliNbhVH3moPTjE8qGxKqyqXyh1nHUVInZpGzhZUOWuO6ZYRQ1eG3XfG1rqgjJ18EMF+CpdGo0hO4aqxHjSrfu5fcq+YFr/bhnI7ysKUCwCQ0+dqM5t3c/qW3S9utK5rvtZMUqbbJ1YBIV1hn7I2S6xAxqrezPk4drqgWu7jJXAgQND3LS6oawyEm1YNsRVaRL7GHafzIeYX1JX2jRqamDqvneIonPSJzCs+5l9yu6xjNlxVsUiMFxCJxqStbQIyKrSVuzzUXceVM+HfgjcLbECQ/0tdT8/RumaabZ6wNkuao+wUEMESyohDXls1hm0pqf0mJ2BOtxt1pnQQ0XHcqhhGzvgUWk6xn2sMyq6/6ynNrQtMTsCneu63xGjhFxHT1lLDwFq7Ckcu3VH7ZHVQ0HX0tQut2HVvrYdYaqo7luf15AHHdeOyo9oZ4hgTfvdvdx8OqpX5UcrTZmWkM5sn+3959Rgjflkp7973/xGHVesp/U+dIwh5yjmNZPt/dWqTEqfLGZMuk6xhoys/ibtCRiyR12MwKBOyF6en5cfY+uyJ2Xs6/Nn+VGq9qipPRyKpi31PRYP10x/Q4y/5RAdz73Np1gq6+yZilWD3JVF1me7NGUNYmYIqt9l9QQbUnavszqGuv9uzNL1ISh2Vqd6Clen7CWTsl10TDE6HQ/ZsIrOdd3PilFCzpXVkGqXh6/Yda+69z21R9ul70OzVTa/qc/YZt2eqk7EfgjGxKhBq6scMYunVLQqfN0xxipNN3jMAFyNsGVnF1K2G16roKBvUePeRewVfjovXgPCqhxa1d6W1cNml060YhWodB36E8s2t232z+IY1LZ6DQir0ifgqft5MUrbhIn1nGy1R23rD2ZsiJu4baUfSt0xxipNHVbMDIEaOI+BhTqcISZC9yldGr/YWR0FXJ4DQhUdX0gGbFvdz41RQtoTqy2QQnZT0PHX/awYpS2LY/AeEKqEZgvHvmaWv78qun5A9CzIbumalRmC1dCNSlNWtO57Qor+BqtMTN+iJ1rvQU+XTFjd9/cp3s9NVfrMLbTMYIcEq56yllYZ9Ka2Z9uYOwGMXUKyhVbnq03/OMTDf597fRJYaNJeyFN3Fz+WHz0Z60XqMc+1GgrraxdKAVffLJO1tufO4hx7PzeVB+XHEFbDULp3tdCkK6t7JWSRya3yY2xdFix8XH5copCFlWNeM+uFJU/y8mjz6XwRFLajhtu6g/Kw4tgL5mv40bbu95mDNHU6R6F/v4eVmhXLh6eQoNCqHeiys8KS26Ivyo9djHXNNNxtea1Uf082n84bQWE71pkmBYQhT/VzZdVRIkybxnbp1yx0uyqrjuzX8mMXVscSEqAqyLZ6EO9yPNZtv2dd/3ZdL6uHw0PXTHP8LOf56XcvZusZgsJ2rFLilZ/Kj0tyaGh6yVknj9pcD8un9CkIDYo9Ddd6ylpa1acuezcuvU5Ll/ppGUDvq0O6Rn230DlE9UUB4WKSNgSF7Vg3DkscOt7ewHXXkp/OPWoTFC79moU8yFieM0/DtVPNWhIUdjsHVudrX11WZrLLoqGuFAjeyctYc+tHQVDYjmXmSo0UQ8eXaIinh2sWFuBZnbeQzJx4Oh6r0Zku8wmZxtJtCH/oa2a9sOTbvITeS5NFUNjMOgMS8kQ/lDGyP0MHGFpRpuGBT/KSluXv5b/p/xtbNcH5H3mpjk9FT7BaCefhgWKsoFAN9sO86FxsXz8Vfa3z5vn+8jRcu5SsZZdzM1a91siR6q7q9XadVtG/qc4Plb3qEugNec20c4NlnVVA6KH9h0PWG40O8T7lUHXHG6vs27fM+vVEVdFTZtu5cmPsl6d9udo0enpStnqtVFWa9piz2vB4X9GQUZcOQf/tENewa6dodd1C9lLzsCdgReex7mfFKF0yS3Xfb1VUP3UNuhzfEPdd2+tnec1222nrPtlyjiJmwKqxrIrl0HQflje5yr5O3TrAUenaaVqfi92ihrhL56D/1nKj8aagcIhrpqKOs88qX+vAsGvmou5nxChdj0OsNufvsvl5xarT1z3S1pD3vO6f0H7Aun9qGxQOdc2s7+OQh5hZWezw8f88/u3mtWdv2jRY1puXep3E2iUoiUk3vSUNuXbdgFTDF0MtBtLv6rraTf+thpTGYn3NROdFQ2eh10HfP+Y52hUSuLXlabg2ZJGJh2H1Ieq0aIhSU0NC+wEFhR6mSAyxabX6JMt5hFXbu2iLDAqvPvvtq+w4bftEYJnJ8zzfybLTkrpG0Pp36nxrrkiIIbYNqla7hcwTtKxLh47H+pqJ6orOS5dOvY6XOZhiFXSEnCN1slbtXMjxeAhQrbchEwWEMTZEflp+HNMQ10zDulb1VO3Corae2WdxQeHVZ78/TpNUlevhu/s3QgOEWLxmCWWMDKnl07lu9j4N8BDXSvUxtFGyPL5DHbt1RiV2Y+3lQcwq6Aj5+yyvYUhQaPWg0eXcWNfrajFJDB62NLM6X9U1U0bUav692hY9dHrujwezmKDwyuN/Xb/29M0vaZLdTdLkTh4Qth1CtMyEHNqrb2xjdBSWT+d6t7Tnm16N3xRXu1lv26GOMySw2CdkOLOtLvXL6v4K+fs8BGEVLwGq5XGonsQKCEVBTayHphCWfaTqkILB74qvbCxy65l9FhEUXn3++93V8UqTeZP0LLvz7ssbIY3Vkmg4ybJR3NeAWf1O/b6u8wiHpqDVq0MdjmWHoAyIhyxIW22DQm/DtVYPY132BKxYtQG6Nm0DJ8s6LQoIYwdxsX9eF1bnS3VZ9cFyNTBbz+yYfVB47emb79Mse5Fkydv1+erOv08+8/RE4DU4tW4U6zoLy45SgcWYjWYTdVieg59994zlNZOxp3dYsXzgCmnfrI7HU4Dapa21vD46Dot23+o+bBPYW40WqF1UQKh2xoKCQe/JgsHNNii88viPK9eevXmRpMl3WZI+effgxj9OTz7xHBh48kX50UpdZ2HZEMfIwlkeX4yJ4pbB2T6W50QNdtvMWxdWc2W7BEBWD10hwYbqjVWn6ylA9bLIxNMK+FisrpnuE6ufrXsl5hD+bMwyKNR2M6ujtVYXay7Co7/ufxp68a0zZl5Z/911nYXV71RgEdI57bLqOCXG8IVVUHgo0LCsJ1adp9V56vLA6elNJladrs5HSFBvdTxdzo1lIGKRJbQ6Xmk6Xt1PVveU5cPK4ree2Wd2QeG1529uF9vNpMnNLMlOHKwwnho1MFY3uezrLKyezmMNy1oOkcTIiI0R7FidE12zGOekjlUH2mX+nNUxhCwysTqWkADV8iGjbTBmOSXCausYy/a66T60DEgtqD1j65kDZhUUav/BJNu8DUIB4V/3P2MCaXcPyo9W9jXOVo1LrP0FrRreWEGr1fEdCjSsOnGrPSF1jqyyD22DWB2D1bUKyUItYZGJhyyhghCr/sgyMJtbUMjWMw1mExRu7T94qi1nCAiD6XVFluo6C8vOOtZwjVXjF9J51rHq3Pd1qJbXzGrRjWUH1jbwsAw6Qjo7q8A+5L7zMKxudT4sF5JZnbc219By/mVsmpISksFelMkHhdWCkjTJFMycTmzLGatONZTOofUx1V0bq44yVj3wMKzVxOoc7mtELa+Z1dDOnIPCkM7Ow/nYZnWfdXnw8pQ5bcvqvLW5hpZtY2xTCmBHM+mgUAHh1oKSIiCMvOWMdZrZslEOYT10rPNZd328N8SWHXmMAMgqa3co+2R1Tiw7T6t61iWw91TXra6h6kzXeq36azWs3qVPsDonsR7+dllm7JtermDZf8XsxysKYKcUxI5iskHhxQrjtKiYFgGhWAeFfys/ejDEDbNvCMWqcYlVH8YcnmnD6rodOj4PAVZXHuqZp7ruYbi24iHAGOPhqq8xz5vV79b5slodbPlmlFmYZFCogLBaYZx/aRUQDsHyhu5qiJtl3+q7MYKaLqyuU6xXrll17mMsMrHqPC0zKm2vo+UxeArEQuq1hzbA6nxY9k2WfUjTubN6MNT5Ujtg8YCoemZV12ZhckHhRUC4aVyHCAgts4VegsIhbhSdx7rrZHUO9PtiDM2K987C6vj2NcqW18zqfrO819peR6t7LDQTZXU8IZ25h6yl92ksdSwDsyZW91R1vqy28CFbeMCkgsKdgDDJ0vRkgAyhVScl+jssO6u2LN8tWdl3g1v9/bHqhWVH7v0Y9x2f92tWx0NQ6GkaguX58HQ8XbKW1OtLbY7Z+ndrB5FYD/bb1F5atZmTN5mg8IOAUPsQfvmp5TL/iuUNLVokM6bv82I1wXvbvi2CrJ50Yw3NDh1wdWXVMB/KtI4xXN2XhzmQHgKfiqd6rTbdqg3qcjxW5yQkSG5jzCkRlkHV9vmK8YrSOmQL95hEULgbEOYR4cMB9yG07KjEesXvIbqxh7g5FLzvy7hadZSxGmKrAGgKK6P3mVrnKR6CIE/nzcNwbcXqvHTJxo/xcNWX1TFL03mzuma758uqn9fxW/0Nk+Y+KPwwQ5g+effghrJbQwlp5LrQ094YlVPn88XmU3OHnva8B4VjBF1djJG1835Odll2nk3bdlTG7MDrWB1PyEO0h/o0tTotlnWqqf0c6qFCQaLViCDZwhqug8KaDOHrs7Pjod9lrEpq9aRXGbpy6nxenldbalz2NTBWwXCshljnx2pYy3vQOsY1m2NGpWJ1DKGZKE9BkIcFHiwyudTmGlrVn7rzZTWETLawhtug8Mrjf11/LyDMG771+fre6ckn1gFanVgd+D6qmEPOLdTCEsuOcpteLbSPp46pjreOvI5Vo7avznu/ZnWsshrStm1YwnCthLSVHurUFOv1WMesB+Uh54CqTqnNtEC2cIfLoHDzppKVhjYvMllaaXx68r9WFaOJ1Qv6tylQs7rRKjqfv+RlqABUN/OhTsL707lV5xmroxijUxhjuLovDx2+VV0KqeueAiC1eVYjFl2Ox+qchATJbeh4rc5b071oda5k3/kiWzgQl0Hh1ptKKo8GWmm8zxC/Wzf4e4FwZDqfyrxa3tC7TsqP+1gdS6ygy3sANEbQavU7rTpPGeM87fJU1z2sxK5YnRdl4tsmETzUj64s2/Gm4x6jLlttTyNkC7e4CwqvPvv98XsBYZa8Xp+tDg1BDkGVcYjVzvq7FbjFzhh+k5ehA0Jds0ONsoLfIYcgQlidr1gB0NBB6xSu2S7LOt82uLfMRHgKxEIedqyOpUt98nAMXY05JWKMhwr1wVbJGd2flvfopLgKCq89e/NNmmRflV8W0vPsZKR5hLusdlffpQZKQ7zvnYdAquj6WT/kxSoDWUfBYNMKcauGOFbApeDHKgCKdYxDd2ber1kdq2OWtp3+0NfpEG+BvYcpJN6nsdQZs05ZBVBNDxVWQ8hCtrDkJii89vyNKpqCl22PHL3TWB2XZee1TQ235hhWwWGXgE4Nvr5H3zt0drDS5mXmVg1LrIbYU0e+j9Ux7qvnVtfM8h4fM6NSsTqGkPNm2R6EHI9VnerSVk/hXt811r04Zv3R/291TnU+rc7ppLgICrWwJMk+2DPv1MGw8a6hj0c3oILD/+RFAZ6ybwr4qgpcFf17FUT+UX5uefMeoi2D2ty4Vk/nsRoN7x2FVQN26Pi8z7Gs4+E6Wh1DyHmzqjchD8yWbVTb68OUiPc11Smr363RwDbni2yhMRdB4epo/cECiyzJvnUybLxNDV9I4xeDGnNVWgV8ChC3i/5dwaJlY9GG5nw82nzayOpYYzXE3oeUrM7fofrtKaBoa4zgeZenur6ErKWmr7TtO8a4j/qyOmZpuo5jP8xbLjhRW2HVXkzG6EGh5hEm6fsXIg8I3w74Gruuht48eyp0UzetNq7oybzLkHhbaixibVvkqSOvM3TWbgrXbJdlA982S2d5DCGBh9XxhDzseAhQPZ2PtqzOmzTVKat2scv5sowNFp8tHDUo1AbV+Ye6i+Bt2HibGhzPxzcGdep38jKXp3OrAEhiBYVDB63eg+Q6VscsbY/bKugIqeve6rXV9ekyrO59GkudMe9FD+2A5RCy7lfLBzn3Rg0KV0crDYXuDht7zhJWNIfP8qafEgWCWljSJaVv1RCHzLGqY9XwxQpadc8M3Thb/b65ZlQqSxiuDc32egiYPQQ5XVmdt6ZjtgyWupwv1TXLvYMXnS0cLSi8+uy3r3aHjSXN0qG2fumrayA0R1WGsGsD6D3o8h4AjXH+rAL5WNesjocO3+oYQh6APJyPitWxSNs6ZZU5VbsYEiS3YRmYNdUpq98dcr4s4wT9nZbn2bVRgkKtNk6TdHf7mcL6fO09S1ipAqKlBobqCP5RfuzK6oYLOZY63oeUxjh/3q9ZnbGDIAUcCjwshJw3q3od8rDjoT55CpLbsgymm47bU9ZbmUKrwFsWmy0cJShcHa31ho26J7SXI77fOIQqc9vFFXOiGzI0ILZsiGMF6N47C6vGeV+mwOp86F63eqiyfNJvm6UbswOv46leewgwvE9jqWM5JaLp3FnVn9DzxdxCA4MHhcXikrQ+Cs+S7Kfy0ynpEyBNkRbZ9Bk69x5w6fgshpR0vmI98Fidw33DbkP/vhisjlna1jWrTiXkvFnVawk9HgtdAgzq9aWmBzRlvK2y3qHny3pUcZHZwsGDwuPjj/ae6LOzM8ubyZKOe+6BoRoNDRc3vb6uCYtM+rEakjwUtA6dmYzBMqPS9lp6moYwVjCxj9XxdDk3HoawuxrrvFn9XgltG1XvLAPDRWYLBw0KlSXcfbdxRauOJzZ0vEs31Sd5mWpge4iyg6HzB3d5D7q8B0BjdAp0npe6HLPVMYTM4fMwXFux7GjbtgOW9cMqOWB53praJw/3Ux3rhamLyxYOGhSujla1AaGkSWrZQQxFjYEyhtrg2qphGJKGxhXoKjsY6+/x2rhUrI4vVtBq1TEcCjS8n5NdyqaOXc+sVrZKSF23Oh8hDzse2gAPx9CV1TFL073odfGdjtvynKu9tQzGO9Ei3eK1wIaGHT5Ok6/Lzz6UmQ4lDU2velNmzXIvJUu60RTcau5gzOyt1c0VM7iwOsZYDdfQGR/v56OOZefZtp2yOgY9nIXck57uPasAo8uxsMjkfU33o1X9ibFNl+WCExk9W/g/j3+7efXZ74+Pj89qd22JabCgsNiX8MCTc5ZmUx46rqO/R0GVgquYQYsVdTaan6HMoNUxe386tzy+WJnWoYM0q3NieU9YnSNpW9c81XWrYxFPx9MlIJtivbY6ZvVVh9onb/Vnl5IvsdrXOmpPLNuUvRQ3XXv65p/ZcfqLvv7r/qcnpyefWP6twwWFaZJ+UX5aK03TuQWFFTUSCrIUbCnoMr2gAXRDaVsdHZ8+Wl6HpS4yidHwidWQpOrkvuvufY5lnTEzKhWruu5tT8Cu7Znqr9Uq1i73mfd7vc5Yx+w9KFQdnM1KZGUFrz1780Ne/pPHTY/Ll3x8q4Bw81/YGiQoLMfA726+Wix1urqof8+LMohjBYjVDTTGsVg1LrGezr0HQGOcP8uAworVeWrKqGwbqwOvM/SUg0M8BBhWdXqq2e+m9snDcH8T6yFknX+za6AYqcwK/lJmBau9nE+zJDt5d/+GpqQNIi0/mrr6/Pe7aZa9KL+slyZ33n15w/Km8kqNpCqbGm59HrPRVAemhlJFN77O75gZWe+Nsc69RSZO5z9G0K0Mi0WWRXViX73wfs3qWB1zdT+1YXUMIXXJql4fqjf76DhitnGVLtfGwzF0ZXXM0lSnPNWfQ6zuuUrs4y2ygufHR1+nSaak2e45Pk3Psjv/PvnMqk7VGiQoVCo0/6DId7/lBoV1qpuwLgiontp0E+8+4W1XWs4lAACOKCt4fPzfu2mWfp3HPfWBfpa8Ts+zk6EDQhkmKHz65pe9f3yFoBAAAMzQtedvbmdZ+mDfXs0X8oBwfb66Y72gZJ+hMoVZ+el+WfLw3YMbfd+WAQAAMDq9sGN1vLqbJdnXaZI2Tv3JkvTJUAtK9jFfaKLouPz0oCxNPy4/BQAAmCSto7j27M2LPCD8I//yhzYBYe7R2AGhDLt59QFplllNogUAADCjrODFVjKbhbWtd1wpVxjrTWijMx8+vvb0zff5b2m1x8/6bPX3scbRAQAA2mq1aOSw0/z77nlaT+EmUyg6ueWnAAAA7mh4WK+dWx2vqw2mgwJCbTnjbYGtq6Cw6a0nAAAAQ6uGh68+++0PDQ83riI+RCuMz1afjLHlTBNXQWHurk58+TkAAMAott80Ui4a+ablopFDXo655UwTb0FhsjpahUffAAAAPVyuHu41PPwBbTnz7v6Ne57XTrhaaFI6VVqVBScAAGAIeuVcdpw+yD9VYir6a/20wviv+589Kb90yz5TmB58p2KdK6uj9eFX4gEAAPRQzhP8RvME84Dwl/yfFHvEDghPpxIQin2mUJtXZ8k/yy9bW5+tPzk9+d+oL58GAADLtbWNzIM8Amr1co0eihXGHheU7GOeKVyv10GB3epo9bj8FAAAIFjNNjK2AWGxwnj9jykFhOLn3cc1ppRyBQAAfmikMsvSB2mSaQ/k6PME91JA6HiF8SHDBIVP3/wzMCo/VaTNMDIAAGhSLRjJ8kAwwvYxnWmFsYd3GIcaKijsugL50oQjbgAAYEsLRlbHq7t5IPj1GIHghSx5+O7Bje/LryZpkKCwjNy1sifI1CNvAAAQTxUI5gGCFoxE2Uewj7lMdxskKJTi1TD9IvhH7+7f+Lb8HAAALMjAK4fbOs2P5Z63dxiHGiwo7DWEXGLhCQAAy3ERCCbpF/mXWjDiRh6TvD06S+5NbYXxIYMFhWWqV+8O7IXAEACA+fIcCF6Y6XqHwYJC0R5BaZL1frcxgSEAAPMxiUDw0sv12epkjgtgBw0KQ99uUmsGq3wAAFiqiQWChbkvfB00KJQeexZ+gFXJAABMxxQDwcoSRikHDwpjzS28kCU/r89X99jHEAAAf6YcCJZOszQ9+evLT1+WX8/W4EGhXHv25of8wzebr/qb4wogAACmSgmg4+Pj2xMOBCun6Vl2ZynxxShBoZ4aVsdrZQtjvovwNA8Ov2UBCgAAw/O2oXRvxQrj9b0lvWp3lKBQrj7//W6aZS/KL6PRPMOzs+NvGU4GAMBW8cayo1SZwC9mEQhWFjo1bbSgUGIPI1c0nJym6clcdhgHAMCL8tW1D/K+VvMEx3vXsJElL2IdNSgshpGP1lqNbPV08Wh9tnpI1hAAgHAa3cujpS/SPBDMv4w59cuXhW93N2pQKOUTh/YuNKlkZA0BAOhmBiuGO8vjhcW/GGP0oFCs5hfuIGsIAMAeZZJGL5mYx0KR9ha1wvgQF0GhXH3221f5E8nj8ksrrFAGAKBUvmnsi7nOD2yi0US2tLvkJiiUWO9GbpQlPydHyUOGlAEAS7IzLKy3i813fmCTYsuZ1R1GEC+5CgplsMAwt9m+5r8Pl7QHEQBgWRY8LLwX29fVcxcUypCBYSFLHuZPC4+oHACAqSuygauzYlg4Sc71VpHFDQsfoH7+ybv7N77dfIltLoNCGTwwVEXJkh8JDgEAU7OVDdQm0hoWxpZiJ5Ik/XF9tnpCH7+f26BQRggMheAQAOAa2cBWTrMkfXl0dv4jC0nacR0UyrVnb/TGE735ZGgEhwAAN4ps4OaVcrfIBh6QJa+zNPvx7Oyjl/Tf3bgPCmWg7Wr2ITgEAAzuyuN/XT8+Ps6Dv6Nb6dzfJNKf+ucn6Vn2lKxguEkEhVLOlzB780kbrFYGAFgqX+ZwK+9wbuc9NCuFm73Mkuwn9h+OYzJBoeipaXW0euHgRnmZH8OP7HMIAOiDIeHuLheNrF+SpIlrUkGhbDbePPthhAUoHyrnLfCEAgBo42KVsILApW8e3Q2LRgYwuaCwUs4z1AKU0W+o4qklS58y7xAAsG17XiCrhIO8zNL06V9ffvqy/BqGJhsUSpl2f5z/FW7mXWjeYZpmTxlaBoDlIQiMgNXDo5l0UCgaTl4dr7/LP9XWNW4w5wEA5o8gMI7LEbf1E/rM8Uw+KKxce/5Gm3i+yD/1OD+D1VEAMAMEgfGUyZOXbCPjx2yCQnG1CKVeMVGW4WUAmAaCwOjoBx2bVVBYUdYwy7LHnm9enpAAwJ/t1cF5O32TIDCKIhDMI46fWDDi2yyDQvE617AOASIAjKOYenRebA2jfQK1aJEtYuIgEJyg2QaFlXKF8g/5X6qb3j0CRACwUQwFrz66yRtDzBAITtzsg8KKp30N21KAmCRHP3ODAUB35QJEBX4MBdshEJyRxQSFUgwpH62/yf9qDStPjfZq+jlv2H5i7yYAeN9OFvBm3s5PYnRooggEZ2pRQWFFjcfqeKWsod43OU1ZoqHln9Lz7CXDzACWpHjAX61vFnMB0+RzsoD2mNq0DIsMCivlBOPvZvBEuXlqS85fkUUEMDfVMHDezn2eZpmygMwFHALJh8VZdFBYmcIWNp3oRk6Tn7M0fUVqH8CUaHHg+XGizN/neVvGYpDhqc94xdu4lomgcIsWo+QfvpvdMESWaIPQV8lR8jObhQLwYmshyMfFR+YBjoGRJlwgKKwx2+CwQpAIYGAMAftRzQ/Mr8FP9AHYRlB4wOyDw8pWkLher17zpAgg1MUq4PMi8GMRiB8MC6MRQWELiwkOK9WcxCT79ezs7GcaEAB1GP71S9nAap/bs/Vx3o7zsI9mBIUdXH3++9386ffrBTZ8p0WgSDYRWKSLxR9Z8WB8K0uz62T/HNKoTx4EpmfZz6wWRgiCwgDF0/E8trIJVs5J2QSKafKaeSnA9BXB3+roejX0m9/o1/OPzP1zqpobyE4TiIWgsIdi7szxR9+lSabhZWyGnd/mH38lowj4VG38nGXZdTJ/k6P2tHyzFVN7EB9BYQTV6/PyhvUBDev7iifZLFXD9So7Sl8frc/fMqwB2FPWL1ulV/TWjyxNP07zILDM+k3m/e/IlQsB2UAaQyAojEyLUvIg6EF+ZplwfUiWvM47qtd5R/XnJqu4fstTL9DNduCXtzl/y++rm2T9Jo6XD2BEBIVGNhOzj75Ok0zvV+bJvK3NRGktbPlVmcX8/J0yXxFLdTHUm6RXynl+BH5zsxUEskoYYyMoNKZG/fj4v3fTLNWqZSZsB7oYhk7zBjRL/k/ZxXSdnTKcgqkrFq6Jsn0bt4r/ZbRhltSWFVvFFG8QYV4gfCEoHFAx1HOcPsg/1cIUsoeRVAFj/qSdf8z+rDKMLHTB2Krh3YtFHWWmL/+/rvCQuAwEgZgSgsKRFHseZpkCRA0vw9Jmj8XTi6AxzYNIfU6mEYGqYd3iCwV5WXIlr1+bxRxClm+5GA7GhBEUjqzoXI7XX+UNiRankDkYy07gWPzbUbHqLyF4XIbtQG9ru5aEYA8HlauDNUJBEIipIyh0RPsero5WX7G1jV9bW+zo7tnMb9S/l9nH4p8JIkdX3Eur1eU9VGbzik+3g7z8P+VhDB0U+wTmdanYi5VFcJgbgkKnqvmHeRBylwBx4sos5OaL3FYwWdBqa/3blqUGlh8Ec5XLRRiFncBOCO4QX37v5nUtvw/PXx2dJa952MPcERROAAEitlTvoT7kVflxHJeLKfbbBHAstoInvOMdi0dQODFlgHg7b7yYgwgAoRQAakFIkv1KFhDYICicsM27l49vp0n6Rf4lq5gBoEYxFzjRMHDyKu/1XjMXEKhHUDgj2uYmb/3yAPFcgSLDzAAW5yIALBeDMAwMtEdQOFNbw8xf5FeZbTQAzA4BIBAXQeFClJtl38obT704n7mIAKZlMwfwLQEgYIegcIGq9zEnydEthpoBuKMNoTfbNP2pj8wBBIZBUIiLBSsKEtMsu5nXCjKJAMxtD/9u3gjy39e8GxgYD0EhPlBkEldnt8vhZgWJzEkE0MdmD8A0ea0tYPT2H7J/gD8EhWjl2vM3t4u3SqTJ53mjfpMhZwA1LoK//PNi6Je5f8B0EBQiSDHkvProZnqeaaj5Vl6TyCYCC1EM+27eAa59/4rXNBL8AdNHUIhotA3O+XGiLOLn5bAzrzIDpmyz4EPZv2LOX5pkpwz7AvNFUAhTuxnFLM2uM/QMOLId+KXZW833I+sHLBNBIUZRzFFUNjFJPi4+klUErFTz/Aj8ABxEUAg3tOp5tVrf3A4WySwCLSjbt/Gq+N+jzdcM9QLogqAQk6DMYpakVzQMnaXpx2mWXSe7iIXYZPo2CPoAmCEoxOQVQ9Gy2TLnb2Wm8UoZNAKeXQZ82sYlS/6vGt5N19npv08+q4JBADBHUIhZq4akqyxj+c+3iv8l0wg7F8FepgAvy/4s/rXM8K3X67e8uQOANwSFWDytkF6tVpt5i8o25i6GqIU9GJHb2pvvvUCv2qpFnzOcC2DKCAqBli4WwuTeyzxeDllvEER6tz1H7/1MnpTZPGGFLoAlISgEDG0HkpJl2fU021pNvRtQbjAfst57wdyWzeKLSvmGjfKrAhk8AGhGUAhMxHvD3Dt25ky2kyZ680yMOZXvB2VNaoK2bcy3AwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAvkuT/AeBbCdT1XNQ3AAAAAElFTkSuQmCC'
const logoGestor = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAArYAAADtCAYAAABK1TbxAAAKN2lDQ1BzUkdCIElFQzYxOTY2LTIuMQAAeJydlndUU9kWh8+9N71QkhCKlNBraFICSA29SJEuKjEJEErAkAAiNkRUcERRkaYIMijggKNDkbEiioUBUbHrBBlE1HFwFBuWSWStGd+8ee/Nm98f935rn73P3Wfvfda6AJD8gwXCTFgJgAyhWBTh58WIjYtnYAcBDPAAA2wA4HCzs0IW+EYCmQJ82IxsmRP4F726DiD5+yrTP4zBAP+flLlZIjEAUJiM5/L42VwZF8k4PVecJbdPyZi2NE3OMErOIlmCMlaTc/IsW3z2mWUPOfMyhDwZy3PO4mXw5Nwn4405Er6MkWAZF+cI+LkyviZjg3RJhkDGb+SxGXxONgAoktwu5nNTZGwtY5IoMoIt43kA4EjJX/DSL1jMzxPLD8XOzFouEiSniBkmXFOGjZMTi+HPz03ni8XMMA43jSPiMdiZGVkc4XIAZs/8WRR5bRmyIjvYODk4MG0tbb4o1H9d/JuS93aWXoR/7hlEH/jD9ld+mQ0AsKZltdn6h21pFQBd6wFQu/2HzWAvAIqyvnUOfXEeunxeUsTiLGcrq9zcXEsBn2spL+jv+p8Of0NffM9Svt3v5WF485M4knQxQ143bmZ6pkTEyM7icPkM5p+H+B8H/nUeFhH8JL6IL5RFRMumTCBMlrVbyBOIBZlChkD4n5r4D8P+pNm5lona+BHQllgCpSEaQH4eACgqESAJe2Qr0O99C8ZHA/nNi9GZmJ37z4L+fVe4TP7IFiR/jmNHRDK4ElHO7Jr8WgI0IABFQAPqQBvoAxPABLbAEbgAD+ADAkEoiARxYDHgghSQAUQgFxSAtaAYlIKtYCeoBnWgETSDNnAYdIFj4DQ4By6By2AE3AFSMA6egCnwCsxAEISFyBAVUod0IEPIHLKFWJAb5AMFQxFQHJQIJUNCSAIVQOugUqgcqobqoWboW+godBq6AA1Dt6BRaBL6FXoHIzAJpsFasBFsBbNgTzgIjoQXwcnwMjgfLoK3wJVwA3wQ7oRPw5fgEVgKP4GnEYAQETqiizARFsJGQpF4JAkRIauQEqQCaUDakB6kH7mKSJGnyFsUBkVFMVBMlAvKHxWF4qKWoVahNqOqUQdQnag+1FXUKGoK9RFNRmuizdHO6AB0LDoZnYsuRlegm9Ad6LPoEfQ4+hUGg6FjjDGOGH9MHCYVswKzGbMb0445hRnGjGGmsVisOtYc64oNxXKwYmwxtgp7EHsSewU7jn2DI+J0cLY4X1w8TogrxFXgWnAncFdwE7gZvBLeEO+MD8Xz8MvxZfhGfA9+CD+OnyEoE4wJroRIQiphLaGS0EY4S7hLeEEkEvWITsRwooC4hlhJPEQ8TxwlviVRSGYkNimBJCFtIe0nnSLdIr0gk8lGZA9yPFlM3kJuJp8h3ye/UaAqWCoEKPAUVivUKHQqXFF4pohXNFT0VFysmK9YoXhEcUjxqRJeyUiJrcRRWqVUo3RU6YbStDJV2UY5VDlDebNyi/IF5UcULMWI4kPhUYoo+yhnKGNUhKpPZVO51HXURupZ6jgNQzOmBdBSaaW0b2iDtCkVioqdSrRKnkqNynEVKR2hG9ED6On0Mvph+nX6O1UtVU9Vvuom1TbVK6qv1eaoeajx1UrU2tVG1N6pM9R91NPUt6l3qd/TQGmYaYRr5Grs0Tir8XQObY7LHO6ckjmH59zWhDXNNCM0V2ju0xzQnNbS1vLTytKq0jqj9VSbru2hnaq9Q/uE9qQOVcdNR6CzQ+ekzmOGCsOTkc6oZPQxpnQ1df11Jbr1uoO6M3rGelF6hXrtevf0Cfos/ST9Hfq9+lMGOgYhBgUGrQa3DfGGLMMUw12G/YavjYyNYow2GHUZPTJWMw4wzjduNb5rQjZxN1lm0mByzRRjyjJNM91tetkMNrM3SzGrMRsyh80dzAXmu82HLdAWThZCiwaLG0wS05OZw2xljlrSLYMtCy27LJ9ZGVjFW22z6rf6aG1vnW7daH3HhmITaFNo02Pzq62ZLde2xvbaXPJc37mr53bPfW5nbse322N3055qH2K/wb7X/oODo4PIoc1h0tHAMdGx1vEGi8YKY21mnXdCO3k5rXY65vTW2cFZ7HzY+RcXpkuaS4vLo3nG8/jzGueNueq5clzrXaVuDLdEt71uUnddd457g/sDD30PnkeTx4SnqWeq50HPZ17WXiKvDq/XbGf2SvYpb8Tbz7vEe9CH4hPlU+1z31fPN9m31XfKz95vhd8pf7R/kP82/xsBWgHcgOaAqUDHwJWBfUGkoAVB1UEPgs2CRcE9IXBIYMj2kLvzDecL53eFgtCA0O2h98KMw5aFfR+OCQ8Lrwl/GGETURDRv4C6YMmClgWvIr0iyyLvRJlESaJ6oxWjE6Kbo1/HeMeUx0hjrWJXxl6K04gTxHXHY+Oj45vipxf6LNy5cDzBPqE44foi40V5iy4s1licvvj4EsUlnCVHEtGJMYktie85oZwGzvTSgKW1S6e4bO4u7hOeB28Hb5Lvyi/nTyS5JpUnPUp2Td6ePJninlKR8lTAFlQLnqf6p9alvk4LTduf9ik9Jr09A5eRmHFUSBGmCfsytTPzMoezzLOKs6TLnJftXDYlChI1ZUPZi7K7xTTZz9SAxESyXjKa45ZTk/MmNzr3SJ5ynjBvYLnZ8k3LJ/J9879egVrBXdFboFuwtmB0pefK+lXQqqWrelfrry5aPb7Gb82BtYS1aWt/KLQuLC98uS5mXU+RVtGaorH1futbixWKRcU3NrhsqNuI2ijYOLhp7qaqTR9LeCUXS61LK0rfb+ZuvviVzVeVX33akrRlsMyhbM9WzFbh1uvb3LcdKFcuzy8f2x6yvXMHY0fJjpc7l+y8UGFXUbeLsEuyS1oZXNldZVC1tep9dUr1SI1XTXutZu2m2te7ebuv7PHY01anVVda926vYO/Ner/6zgajhop9mH05+x42Rjf2f836urlJo6m06cN+4X7pgYgDfc2Ozc0tmi1lrXCrpHXyYMLBy994f9Pdxmyrb6e3lx4ChySHHn+b+O31w0GHe4+wjrR9Z/hdbQe1o6QT6lzeOdWV0iXtjusePhp4tLfHpafje8vv9x/TPVZzXOV42QnCiaITn07mn5w+lXXq6enk02O9S3rvnIk9c60vvG/wbNDZ8+d8z53p9+w/ed71/LELzheOXmRd7LrkcKlzwH6g4wf7HzoGHQY7hxyHui87Xe4Znjd84or7ldNXva+euxZw7dLI/JHh61HXb95IuCG9ybv56Fb6ree3c27P3FlzF3235J7SvYr7mvcbfjT9sV3qID0+6j068GDBgztj3LEnP2X/9H686CH5YcWEzkTzI9tHxyZ9Jy8/Xvh4/EnWk5mnxT8r/1z7zOTZd794/DIwFTs1/lz0/NOvm1+ov9j/0u5l73TY9P1XGa9mXpe8UX9z4C3rbf+7mHcTM7nvse8rP5h+6PkY9PHup4xPn34D94Tz+49wZioAAAAJcEhZcwAALiMAAC4jAXilP3YAACAASURBVHic7J0JYFTV1cfPeW8meyABi0BQkbrvda/kq9YkgIhLW6ytrdWqWFQgi9XuWtpqW1tJwlYUq6J1wWKrljWLWgVcWnetSy2gsrmxQ7aZd75z30xCQmZN7p0lOT948968eXPuzcyb9/733nPP8RARCIIgCIIgCEK640l2BQRBEARBEARBByJsBSNg+fIBYOMgIBjAp9kAIBoAFuUAWRmAlMHPM4EQAdEC3snvcHh/K+/jxWnl/Xv4+S7w80K4A5y2bbDn021092XNyf7bBEEQBEFITUTYCnHDojUT0D4U0DocEA5mkXoAC9GD+KUiXoa4i+3NChzc/iYMPMHgTsS9r+09KLCptG77c7v9mEyAjBGAVQ3bWOhuZNG7gV//gMv+iNfrgPzvQyu+T3PLPjH6xwuCIAiCkLKIsBUigtfWD4EM60QWkifyM17D0SxaD4HO5w5ieAP6a1TAdSjg9VFdykZWwCylsbJxK7/+Hu95C4DeYBH8b2hqepnmTdiTwEoKgiAIgpAERNgKHeDZ0z1wzOgTwKYzAK3RvOt0yLIODL6arGr5eGlikeoP1kNVJDO4dK8UQiE/nhZYgj3AOdk+rGp4HQhW8vN/wk7naZo/Zkui/gBBEARBEBKDCNt+DFrTLag4/QQAu5SflsIJxWfwOjdBInYnL++yYH2XBecHvHzEzz8Ecj4DP2yBFl7+99wOevJmX+i6WwgXPZIFgwfmg0UDwIMDgaxCrvogtjmI18olYjAfuR9v78frIbz+Bu+7AvLtHBa6/+Yyl3BZS2H2mJfIcSQ8iCAIgiCkOSJs+xnupC60x7MyPA8qipWgHWK4SCUY/8fLy7z1Cq9fAaftLZg1fkN0MTkmvNHAe5uCS1x+ta6PsN8pBMtbAIgFUL7sWBb5b5JzsxOPHUEQBEEQUgsRtv0AnFQ3CHLtiWDBN8D2nsW7MgwW18oC9t+AtJLXq6CpeTXNm/BZt6Nqk6chqXZcC682B5cA1WOTVh9BEARBEPQgwraPglcsyIKBIy4AhEsg3x4H5sQs8b+3uZw6cKgefNueodkTdxkqSxAEQRAEISwibPsYWNVwJD9eDQUjvsdPBxkqRkUYaAAHlkBr8xKae+4GQ+UIgiAIgiDEjAjbPoA7Cax89ARArORnZxkq5hMAeozF7OOwadtTtHBik6FyBEEQBEEQeoQI2zTGdTcoGPF9qChmQQuHGijic17+Co7/Edi04xkWs34DZQiCIAiCIGhBhG0aghcvyobhAyexqL0RAtm+dNLGyz/A79wLm7evYDHbqtm+IAiCIAiCEUTYphE4eb4Xsg6+EooKb+KnwzSbXwNAdwA1LaDq8z7WbFsQBEEQBME4ImzTBKxq+DrkjLqVNw/XaJb4/wpeZkLN6hUSx1UQBEEQhHRGhG2Kg9MajwWbagHxqxrNqlizD4DPfzvNGvOWu2dGmUbzgiAIgiAIiUeEbYriZgizvb/hb+gafqbre1KRDO6EVha0s8d8pMmmIAiCIAhCSiDCNgXBqvqJLGprQN/EsGYguhPQ/zuaMXaTJpuCIAiCIAgphQjbFAKrVgzjr+RPANYFmkyq8Fz3Q1vrL2nWOR9osikIgiAIgpCSiLBNEbCq4XIgzwxAKNRk8knwQQXNLHlDkz1BEARBEISURoRtksHKf+wPmPNn3jqXRa0O1oFDVVRT+nct1gRBEARBENIEEbZJBCsbzmdRO583h2gwpxIp/AH2NN1K8ybs0WBPEARBEAQhrRBhmwTwsvpcGIwzAPFqTSafB8eZRDVlb2qyJwiCIAiCkHaIsE0wWF53Kgy2/8Kbh2ow1wTg/BxqVtdIcgVBEARBEPo7ImwTBFrTLSgf/WOw7emg5XOnl8FH36GZZe9IcgVBEARBEAQRtgkBpywdChXF9/NmqQZzKg1uNWzY9hNaOLFVgz1BEARBEIQ+gQhbw2Bl41jIyLwP9EwQ+4yXy2lG6RINtgRBEARBEPoUImwNgZPneyHn4N8A4g/5qaXB5PPQ3DyR5p67QYMtQRAEQRCEPocIWwNg1YqRkDPqYd48TZPJu8DfNoVFbYsme4IgCIIgCH0OEbaawYqG8WB5lD/tIA3mWoGgkqpL5mqwJQiCIAiC0KcRYasRrGz8MVh4C+hwPSDYCuBcSNVlz/S+ZoIgCIIgCH0fEbYawIsXZcDwwvmA8D1NJtcB0niaUfa2JnuCIAiCIAh9HhG2vQSveiIfigoX8eYYLQYJXgH0nUszxm7SYk8QBEEQBKGfIMK2F+CkukEwIHc5b56iyeSTsHP3hXTX+Ts12RMEQRAEQeg3iLDtIXht/RDIt+t483g9FmkZbNvwdbr7smY99gQhOeDZ0z1w1NFZ4MvKgkxPFvitLPBgFlhOBvjRA5adARjcRrT43Lf4ucUrBwh8YJMP/NQGtrUTWp3PYe649eQ4lOy/SxAEQUh9RNj2ACxf/gXI8jby5jF6LNJjsGHbxbTwMskkJiQFPqcHgI2DwO8pBBsGAjgFvLcAiAYAWAMAKZ9FZx7v420YwPvzeZ3Lz3P47V2XE4oD15WMoHG7vRQrzDYGDsKO535++gFvvAkZ1mtQXn8Xb39o8M8XBEEQ+ggibOPEdT/I9zSAPlG7CPasvYQWTmrTY08QAuDFi2wYmjcM0DqQnx0IljWCd+/PAnUoi8ihwe3BvD0YbG+m+6bOwtM10q42sZPw7Ly/R2zhZWNwWc912MjC+QPw4zrw+9fBpzs+lHTRgiAIQk8QYRsHeFl9Lgy2VTrb47QYJFoMTSxq54moFXqG29DKgUPAsg/hE+oQFpy8hoN4ORCKCot47e3+pjDbvWMXL58EFtrMhjfz+a2ef8qilbetzdDKArZp/UZxtxEEQRBMIcI2RtwUuYMPVtEPTtdk8knYvuEiultErRAZLF+eCWgfChYcwQLxCN51JAvSw4Dgi5BvF3Y60kTxeyDgBqAW1cPKItXZxEVtdMVqm59F7I7NNHviLhOFC4IgCEI8iLCNlZxR8/hxnCZrz0Hr1guk50roTCAecj4LV88xLByP5V1HuYvtPRjanQT097aqhtVaFsnvA9D7vF7DAvoj8LGQbW1eR/MmfKalFEEQBEFIACJsYwCrGn/Bqys0mXsX2naeJz1c/RssXz6CteqXwMJjXRFLeBwUFR4KoVwHeo+KKLCBl3d46x1Aegf88F/w4fvw9soP6cmbfQbKFARBEISEI8I2ClhVPxHAmq7J3MfQAuNpzoWfa7InpDhoTbdgyhmHgQUnsIj9Eu85kXefALZ3v64HaiowMBHrdd56HRz6Dwvmd6EF36Z5JdtDv6FEU8GCIAiCkHxE2EYAqxpOYFG7APTIjt0sOs6jOSVrNNgSUhSsWjESHM+pLGRPYZV5GlQU8zkE+QaKUm4sb7qZ6sB5kxX067DT/zrNH7PFQFmCIAiCkBaIsA0DVj1eAJCrJovlaDBHLD4up+qyf2mwJaQIeN3SQsj0ns6Nn9P4Gz6Fmz8sZj1faI+UpXEy1y62/yqfRi+zSRayvL1n7VsSTUMQBEEQuiLCNgRoWQjldfcD4he1GCS4hUXtIi22hKTgnhNTlx4JtucMfvZl3nUGZGYeDu3qVV9AAiVWXwOiF/n8ewkc50XYtP1tWjjRr60EQRAEQeijiLANRUXdT1mpTNBk7XGoXXkzVIsvYzqBkxfnQE6m6o0dzS2TM6C8/jQWr4XR3xk3H7L91SxknwMHX4SdG16VaBmCIAiC0DNE2O4DVjSUgIW6Jou9B/6275Fzs6PJnmAI1/WEsosBrf8DwmLIyT4ZOpLCosbQWvQKG1sNDjwH1Laaaset12JZEARBEAQRtp0JpMu11WQxO+rB0WkGv/+bLFx2aLAlaAbLl38BbPtM3jqLn/4fQJ6KHRvwjtXnVtDEywtA9Aw4+Cxsc56jBWW7tVkXBEEQBKELImw7k2/dwY9FWmw5UE61Y17TYkvoNYFGC34FCL8KiGeD7T0a9Kfq2gVAK4HgWTb9DGzc+iItnNiquQxBEARBEMIgwjYIVjVczo8T9Vijh6mm9E49toSegJfV50IhnskitpTl61ch3z6Od1uapazyhX0eiJ7iEp8UISsIgiAIyUWELYPT6g8Gj1WrydyHALuv0WRLiBG8eJENQwecBJbFQhbLYLB1BnT4yGrDz42Wl4CgARx8CjZvXcVCtklzGYIgCIIg9JB+L2zdME4V9Xfz5gAN5tQksctoxgXbNNgSohBMhjAGLCqD4YUlhqIWrGMxW8dCthH8OxtplmSNEwRBEIRUpd8LWyivv4ofz9JkbQbNKHlaky1hH7B8eSafsmeCjefw03G8fURgupe2qAWK3a5rAcJyaGurp1nnvKfNsiAIgiAIRunXwharVgwDtG/TpIrehW3rf6HDkLAXFrNfBMujhOxYsL1f5XWugWLe4WU5+GkZgO+fVDuuxUAZgiAIgiAYpl8LWyDPLNa0BRosOWzsSgms33tw8nwv5Iz8P946102SYXsPM1BMExCoXtnFLGSX0Yyx6wyUIQiCIAhCgum3whYrGi8AC76hxRjBXKouXaXFVj/EDcWVh+MBrfMgZ9RY3jXQQDGbeVkMRP+ApuYGmjdhj4EyBEEQBEFIIv1S2OLFi7KhqLBGk7kNsHP3TzXZ6jfgtPojwIPnuz2z+baKYGDiXHyVhewT4DhLYNZz/5YMcIIgCILQt+mXwhaKCm7kx5FabDlQRXedv1OLrT6MG31iat1pYOMF/PRC8FhHGCjGz8sqAPo7+Ohxmlm2tuOV2jEGihMEQRAEIZXod8IWpy47CLwZN2oyV0c1JY9ostXncP1lM0edCRZcABX1F/KuEQaKaQaiBkB4HJrpCZpb9omBMgRBEARBSAP6nbAFT8Yf+DFHg6U2IN80DXb6FHjFgiwYWDQGECdC9qgJhmLLqqQIy4DgUXDaFlPtuB0GyhAEQRAEIc3oV8IWyxtUDNSLNJmbQ9Vj39VkK63ByYtzICdrHBB+AwpGnMe78gMvaC1mNy9LwGEx69u6lGZP3KXVuiAIgiAIaU+/EbbBDGO/12TuM2hp+ZUmW2kJTlmUB57C8W5kiZzsc0HFl9UrZBVNALSEBfPD0NS0TCIZCIIgCIIQiX4jbKFihZq0dJoWW0TTac74rVpspRGBaBIDVXzZiyGjUInZbAPFtPLnW8cf8kLY2fS4TMwTBEEQBCFW+oWwZUFmQ1Hhb/RYo//Bxm136rGV+rgTwLJGlQDCt/kz/Bq0uxnoxc+f65O8fgRaWh/tj40GQRAEQRB6T78QtlBUcCk/Hq3J2i9o4cRWTbZSErSmW1BR/BXevBhyRk3k9X5mSqKXeXkAwHmIZozdZKYMQRAEQRD6C31e2AZStI66WZO516Bm1UKYUarJXGqBlSuOA7S/w6L2EjATmouhD4DgAXB8D1DtuP+YKUMQBEEQhP5Inxe2LGpVb+1IPcacX/a17FVYvnwE2B4lZL8D6DnOUDEqHNci8NN9MGvVs33tMxQEQRAEITXo08I26Fv7Iy3GCF6B2rGPw4z012QsZgeA7VUuBt/h9Vm8tgwUoz6oJ4GcBdDU8reOiAa1fbO3WxAEQRCE5NOnhS0ML1Ti7TAttoh+TY5DWmwlAddvdmrx2SxhL2Mx+w0wE9FA8V9uBNzH6/uouuRDQ2UIcYKTF+8HmRmjgHA4WDgUEIbx7v35lVxQCUsI8nhfHm9ndH83WeoMCrGfGy/o8G/DAUQfBBozaglsEy+otoPHdRwf3IdqH7Ty9h6u1x5AXgPu4nd9DhZ9xod8Bmivh9qV66WXXzBBIDviAfuD37Mf/y4K+LQt4POS19C+zuXzMoePVL+NPD7X1ba6dmbyfv6toPq9ZEHgd+PlxQb3vtrxm1H3WLUO/i7afwsdv5VWdyFe0M2iyGtUz9t/D/zbIBWzexfvV9sqSswWXm9js9vctR+3gL9ps4RDFIQAfVbYBuPW/kSTuXdh5qrHoSb9ehtxWv3BYOPlUFF8OT890FAxfMGFReD474JZ41amcwOgL+CGZRta8H98Oz2TBePJfMP8EmRnfyHym3ryYnA/hnkd993ATivsemDnfVb7PjvwvLy4GSsa1/Gut/k8+xc49Ay8ueoFevJmX6Ra9wY8e7oHDjspA1r9GZCVkwFeXvu8GWA7gcVvZ4DFa4fXSuC421ZgG3lNas2ix1FrCGw3+f5M89JzkqQ7+pXrzXc/Cw96+JriYSnm6fg8fLxt8T6LPw+/o/5eD//dHvfvt9Tn4t5r+Dkv7mfEC6l96jh8ihvB/zJa96F5h3PdTuRnR/Jv4hAu/xDeHgHZo9TvAveOWYUavOp0riKG3h/pPbHs7/iJhPiNYIjysdNvyv2ZZANWNrDYRXV+reXlPT4H3+XP+FXW069Q7biWCJWNCk59bDBYeZd2e8HqEOqxWAk0gruym2pK7+9N3UyAU+oO4fP85PjeZPHJ48Q3+smNfKopeziu9ySAQDSkkd/o+jfxtkPBbW60Yadt1ZDD4NqF106wM8Q9ztq7n3An1Zb8KaZ6BFwlJ/LWWfxZjWBbfn6/mqfTAP6dj9KsCz8P9b4+K2xZ1I7nx+O12CLnD+nUY+RmAsvOnsjXvO+DxzoTdOcA64Be5pPsz9AED9C8ku3urtq0+Zj6FK4QO260yvp2CQwvVOd+IG20oW8+wWTx33EEr4/g9ddYVPEvu/hzrGh4lM+/eXyRfEVXQVjZ+ACvvsX2AxfijnENK9iXbQUWV0zYwTUGttsFubtqF+mdvgA/ztVVz4QzrJBvcrCwy77On0fnO4nd6d5u73MCdnxG2OncpAv0VtadCHs4V+p8FlJn8++hGMAdjQi+2PHQxwj2NAMcyct49xx0/0xvC5/XKgLNYj4JH+1Rxsw2D0I2VocoM84qdju+mbXTX1KuM8Rr/4Ifvxf/G+P06kO+gl286K+0cKI//rIMknXwBP6uHgo86fQ3WeEadth9bYU7jn4brXj+TDKgqPAWsL1TQY2OdJhxz+lTebkIrPzb+Ly+BWrL/rjv+dN3hS1QuZaLF8FGcPx/6b0h8+C0hpP5RnI15GRfzE8HmCmFtrmuBo5zN9WOec1MGUKs4BULsmDg8KtZiP2Qnx6Q7PokkMF84b2af+KTsLLhcfC1VdCscz7QYFc1hk34nG+g+WO2GLCbGJCONyYG/fSGDjNuNkRvweW8dSXf2k4I7OyLAjZulDD4Mn8YvHhu4Qbhi7x9O8xcuSjWDhuaN+EzFhHKDUJ3HPMsmLpUhZP8VLPdHuOKquEF5yeo8YMwxKMaIyF7HpMGwhWGLO+BVqqJWPTkxoHcGOVGGBQHdzXzGfgUv/IuN1RVz7C6Ro/mZSDX8zaoqDsVz57+7c4jeH1S2GJVA7dYUZffQE1vh3FM4p4E2fAd3rwKPPglg0U9zyfXHbCn+RHx5UoNsLzxXBg4YjZoi/qRlqgm/IXg8Z6FFY3fppqS5T02pIbfskfp8cnvzpuG7CYI1DP61Z2dMHvsOpjZ85Ee1/VmeOH1LGqvD/ZaCpFAPJUfF0J58QtYvvyK2MMu0lp+s4HIOV4VWjJlhC0MLSyDQM93YqCcQkghYYuTVwyDbM84I8YJ5tPcsk/Clh2Ioa9GzYqDx98DTtuP+Bztcn4EXCyt2Xz1H8/PJsLxo5XN69pf75PClj+MKfwH62hubecP9Q4NdrSDFXVngGVfCTmgemdzDRWzm5eHwHHmUU3ZS4bKEOKEb0aZYHn/CBaf50IQFjQIT2Bl43lUXbKiRya8BylR69VbryAEWnolk4gZYUvwVm+Gofk6+FUYVnAPbx7UN90LjHIaX0dexMqGS6i69ImoRxOu5Y/YgLB1lLDV5k7Ua5AuSui55LULE1dYDGTb3wcz2rAFqO2PEY8oP0OFHj038IR+zeflTVjV8HusahwS2EV+/m5egNmr/8zPzmMRfC+vL+XG2rV83IM0o3QVGKp8UnF7MHOwB74xIbmHWwo7NNnqNYHeWbrUHYK17GMNFvUWn0F/gj34lw7fWSElCJwD3sd486xk1yUFUaL0Ibx2ybE099wNcb/bYx+jv0rtOGnbY+tOHPLkG0rY0rOebHdycHndLwDtmyDo6Sz0iFz+NP+KFQ3jqaa0MeKR6E5KM4Bl6tyKm+D8lK8ntFCHUkbYunM1ji++xohxogWsp9ZHroD10+DWKqgZc3MgvCqq7+MQCEQGUXNHroSK0QeziP0pXrHgahg4opjPzYP5OBUsYIJ6c58TtpBFl0MgNEtvcfiES4nJHoGMYJ5rIAe+q+lvC4XyT+FWuzMHasY+lXLO/ELAhzCroI63Tk12XVKYQsjM+h2oVny8EB1t0CczbYUtYK4pNwSG4v5c3CgH5fV38qYpP8D+hopi8SCLuqOVL23YowjWGOnItChlhC1kZyoRpduPODIWpoywZVGrQoGa+D580Ia/j3QATqs/AjzWke4Totu6aZANWwtgWMEo/rze46PV9f2ndPdlzdwoq+XzV/ntluJVT+TTXefv7HvC1tJ1saM6bsH+V4+t+HEd2IcN/DpY1jUsar9isKhP+SSazyXe0RF3tg8koehruDfz4YWLeFNEbXS+jZWNP4s7jjLi0Ybq44dN29M3fbRtmRO2Tg8mjg0rqAURtboZAtlZKhJAedgj0L/WTOc4po6wBeuyhBdJqdNjy0wzZPdhmlOyJuIRHtg7YraFuo8eDB44mBtXBwefbd37gjrWbXFlwoDMQ3n9cp8Stli14kT+dDT5AOFsPXbiLPXaJUWQmXk1FBVezU+HGizqVb6pzIQdGx5SrR6D5Qg6GF74S34cm+xqpAnq7qt8z/8Q5/tMCdv/0cKJTYZsm4foeGM92b74erKxonEq1+W66EcK8YNX4uTGm8K6n/lgraEx3pQQtjil7gDw2mcnoeSUELZY2XgKr84wYNoBpy1qiC9wcL9gPJrdtKBsd7fXs6yPg1s7wE8/7NjfCp+4KVIUfltF2OhrrgieyzUZWgM1K5fBjBJN5qKD5Q2n8+24HLKy1FCAmQksbjYoWgpI1TSj7ElDZQiawcr60wAsXclG+guqERCzsA2ETRtxiKG6pK8bggKNRUT4ONIM6W7VmNJwAngx3saKEDu5kOVcyOsFIV/dgetgEKjhYd2tnJQQtuCxLwczof4igynTY2uqt/bvMUXewI7IEDnK1zlE9KXf8dn3Az4ui7XS3uuGTXuTDyG5NvqMsA0G9L1EizGiPyciIUNHdg/LqgAbTzNYlErFeC/42mpp1jnvGSxH0EzQBWE+yASZeDlVTTCK2Vc8p0glgDDzGadxRIRgCLQjDZmPWfC7YYDKi9XvINNQXQQXS/XmhBS2qhcNKxuVoNhfc6FFmu3FTfA6e1WSih+UpHI7CIwUZ33TgGkCn+/WmI50fG+B7fbpIeRkf5XXS7q8vmHrz6Go4F1++R5efsl7LnT37+1lbwXyu+6jfUbY8h+sZsMN1mDJB364V4OdsARnGU+CnFFqSM1ka3UzLzNhp/+OtA4O358ZXqh8CU1GwOir5MPkfwzndWzREWw62lyCvvSNiADZBypRa0pMxi74pxUr0RFfilMhflT67cgoP0ndwjYXJ9UNSuo9aliBGuExlXI+CingipCRqXprMwxYXkazxr4cy4GqVxerGlXHmwq7eANa1tLuE8i2PQhFhWoS2gQ33e7mXZ/w82BPMz3ZHsWq7whbbnNpMrSUZpZu1GSrC26aR7QrwZuvZvTlmCgjCLdq6I/g992fysklhMgE49XenOx6pC2ZGarRGJuwNTdxTPVZpK+wJft4cyE9YxP8QTeRm0zVQujC4SrkU+csTvugQn59WXupeW5khCR2vuDVySs7ua4IKpIA5OeY+fv9FFtvbQcq3a7qkYUzobxOud91eT8tnNiKlQ0L+Hp9A3gyJrGoVZ0XARcyP/yu/bg+IWzxsvpcGGydq8WY37lLi51OYNUKlcrwh4Ae1XVuzoeHYDWg8weoWf1EIlwpBMOgRzWAkj5Ml7YQxjGCg6Zi2DbDx9uTFl2l95jINBUk1ogIA4quBPkdJAovHHWKmoCzOeSrBGuNNHTIVsL2dQOWo+L2/FlePfqhZzVIbo9tfq4aDTGRae1pmhlImBAzG7bdz2JVZVItZfF6C1Y1HgAtLafSnPEdURCouvRG/s5m8nc2k8/FrwV20p+ptvSf7cf0CWELg1ENI+jIvrUJ3li9DKCs14YCPmGjJ/DWDSxQiqO/o8eorvrF/HgbVZesdPfM6H39hRQAMXzoHSE6GMc1AY1FRHibFk70G7JtHqTjDbloOODfEXVCSSARQ738DhKJ11ZZnkILW3TWmumbSWLIL8v7A0imFkpiuC83IcNxo8uNRD1x4JZ436KulTip7mLIt1X2SOV6NBkyM7+LlY31fBlSI9GWm97b9iof3IDrBMFScHxdIqX0DWFLeIGWay/BfRGGYGLCHT62PZdCRfH1/PQIDbUKh+qRXQR+/61UO+Y1g+UIScCNkmEZ60XsHxDFNBnMHfEZZI00U4c0dkNwMRURgdbS7Im7oh5WvqKUHw81UwchJL4IDUI/rDUzxTI5SRrcSefDCyclo+y9lUhij+2xoy/i8g8yYPkFqi1p6Mkbla81fy9f4e/lNtZ1qtGR19Ez21Xo7VaJHGDjtlv27TxIe2HrzmYsKtQxjED8i76nx/Vw3SH4S7C8StAO11CfcPi4pg+C3/ktzSx7x2A5QjJBTHyg8L4GYmw9pQNRTZAy4yKE6RwRYcUwyPYMMWKcMDbBT3iFOR9fISQeT3jp6nPWgG3gp4JJ6rEdXjgR9E+Gi5f8KH7N5rDweiN2HSfu3trOBON+T8XrGqshAy7ha8CZ/HwYuDqNPuJ1HVDTQ1R93seh3p/2whaK8lUmJg3REGglVY99N9534eTGgZBN18Fgq4KffsHgRbiV63gPtOBtUTN4CGlNOTZXzwAAIABJREFUMPRMYvOV90Ucii3xCIK5nnF/GkdEyPIYTKUbvSfbTSHtLTzfYB2EkDjhles7z6+H44uVANOsHZKWVjcVkn0gHHaS8nENn87YRKHT6s/mRspJ2g0TvAazxi6G2t5P8wlqnd8El5hJf2ELHj3ZmAjnxXN4IGRX3jTIUUGN0YTjdTssaOFuPll+S9Wl8aUIFdKTYQNUCmUzPWX9CaToQ93ucQYjIjiUvsLWnH9tbD3ZAVFrMnqMEIoIDULVq4iVjeo+NEpvoYnvscWK+pMALROZtuInK0u5IyRU2IJl/ciIXWStEmv8cEP0AWELpb22oGZ6Nq35K0D0TGM4ZelQyMi8Hrz5k0H5fpijze2hbWu7lWad84HBcoRUA61zEljaej7/X+Fz7b8s8P4HDvC5Rp+D07YF2vxbINPTCi0+H8CmNmjNsCF//wzYY3kg28oCassD9A7g4wfwRXI/vqDtx7YGsyBSqaCH8/Zw/mMO5P1qqC/xA8o+a2v0g0AJOEMxbGkbzR7zkQHDCcJYxjH+bvwxCH46PxmnTb8Hcd+MT/uiQn5pFrZJyD6GlqlMW/GDlNAkDVje+CWwNMyS7867sHHrIgN24yKtha1KuwY52af0ygjBfwB9l9K8SW0Ry7q2fghkWTewqL0WzPYiqHos4Cv/LTRj7DqD5QgpC44zXMBKFrAPAjnLaWbZ2jjep87N2Ib3O+H6nw+gkXzDPBRsS02oVD6tKozUUWAmKHgAnz+k/1WIGhrqsY3RjzR1MSVsW6Dtg4gZEIMZz0z/DoRQOLA74utmQn7lK7c+mleyXbvlEGDlP7ixnaMr9n3vISuxE8iQbgQToRAc+l0qRIFJa2ELWVkqUHRPb4w7+Gy6BTZuq1FBf8MdxOJ5PxbP17OonQJme2gdrs8j/HgT1ZSmcdxLoTe4DahMy5TP52oWs9OopuwlQ/ZDolJx8uqt4NIBXrwoG/YvPA1s139qtOZi/bB15yfRDsLy5QPA8h6guewAaRwRwf1uhhceZsY6vRutIwGyDlIhEgeaKV+ISKsVLVGCmTkeXr/qtU2IsAXIVrPtUyc9cwJDfuG0+oPBtiYaML0OWtY+YMBu3KS3sLXg9B68i1sT9GegppvCzahT4HVLCyEjs4pFrRquGNDzSsYA0WJwnJ9L2C4BMlAJPP0taYK74PWV1yRl5m0YgjNfn8bKRhWYXbew/TimngMHj+briJnx7jSOiAD7F6hebDP3B4rlc7Gj+4WZQc14UZknP+KKfgyEm/l7/IzrvF25lvCyi49oBstq5n0+bijy8VZwloxjqcC7/BT5fV5+nwccyuBjVecLf5aOl4/NdPchZLivq/3krjMBMYftZ4OaDK3ceFClFjU6fyMUbXBn2Q6YF2nij6FYth43ScNbUY/rJcEQX5NNlxMXVgJDfgUiIZj4bf8haoM1QaS3sCUWtnHdkmgZX1RupJqysD0pbnq5ATlVkJlZCcZ7DOhZvgD+lKpLV5otR0gjTCTzeDXVRG1X+EavX1vGlhbbts1NHIsxZWxKYpucOBaDiwZqmDsRG+o8Wc5idRX46WX4dNu7wQZX0nGTU0xdeiRY3q/zve46/kyGGi+U4POoE38Q4nFfigMnMX62wwd+EwKho1KHBPXYYvnyL/D59H0DpjfB9vV3G7DbI9Jb2AKeGNNhBC/xifMjqiltDGspkFjhGhiQ+1NQYbvM8jbX58csaJ8wXI6QfvTOZzwUfmd66opaBXoNGI1twqXJiAi70jgiAlnHG5u35UTusQ2EUAT9YYg6o0ISId0EG7ctSQWfwFAEBabKzvYfvOqJWsjLuZPP128ZLja6X3oLC1sTg/iUqMgIVuplsktUkgb0TAUjc4Todrr7srjnX5gibYVtcDJXtEQIa/gC9guoLXsoXCs0kPr2jEu4FfMrfnqw/pp2YRNf1H8Jr6+8O7WFhpAMAudi8Zc0m90As1c/ATNTOM0yqaFZzSqK6H8xHmnKn3mDyqBjyHYiOM6YZac1suDPgrPA5L2JYBY0r7k+VYZNY4HuOn8nXx++A9OKc7nBcZ6xgpCijnTQ3LJPsLJRhdLTPOfEvLDleqsRsZM1mlQRJHQIRePCNhgX2kTc3s+hbdsdBuz2mLQVtnzxi3RD+pSbu7+BjdvnuRPDqkP7C2FFw3ioGP1b3jJ3EQ+wi2+0f4At3KpxJ9Iky31MSGmuHX046J+guJScm3sfKdskaKLHFmMVtqZ6bNO2t9YdAi+vM+WKsB3mnPshzIpwSqIrbE3xINWUTEvHa7D6HWP58msBvSp2u6loIrG58ARCfh2rt+gEJGkgKtfYiPaxwX/yCasjPKP5HlvPwKv50URYsZqY0mMnkPQVtmAdHmLnTl5mwI7dt6sWbrh3cqvtFL54/gEsPNNc/VzU1fteaG35Gc0ev9lwWUK64zXQe0gQMaxSSkDuRBrdRqMKW5xUNwjybFO+dmkrbOG6JQcanLT0VgzB2034mSs+42vxFEO2EwLVjlvP96/HefMiMwUkUdgaTqvLn9uBXMaF+izSsxB7QyCKKSOCs4PghLlKA6Z3wPZdsw3Y7RXpK2wJDu10M1TO/nNgT9Pvad6EsNk78NolRZCZdSu/71IwHfmbYDX4qZxmlv7baDlC34HwaAMCL1e3Rf04D3JD9cWu+4gbhRi9p5nCHOff9WrU9+YY661V1U/fiAger7nEDBTZ7zg4XHqCodJrac742JJ2pDb1YErYAsSW3dJILFvDPbZE17Gw1ad5HPwrWHSEFltoePLY0MLvgokkGERz6O4Ltmm320vSV9gCHAgq3SzRXdDScivNPXdDuAMDMRkLboCsrBv5qdkbfaDF+xOoLbs/2WnlhDQD3YQFum2ej9b0X6eyOwLVlD2cnJJtU/61ivTtsSU0N3EsWgg070AVm9zEfckPbS13GbCbeHy+N8Fj6NZNMQpboLX6+4awQDVsTAxru0liBlmTNJr0gbXnbwDZmrKXmZs85s7dmFZ8gwHTe4B81Qbs9pr0FbZIy6HVqYyUsjLgK1b/bSgq/B0/NROEfS+tamYgtG271f1hhvHrFYQIHKrfJJ4I00b/kjdu0m87zTGWSpdF1Kbt/zFhOCGgsYxj/MlEC/WFptwQVvUZdzDC9eZsU2zC1lTIL2ug6lV8R7vdQlSjtBrFI9WrOPhY0bhL0yXEXI/ttNEXcB319Cx35U6qHfepAbu9Jm2FLc0ojdj6xvK6U6GivpY3e5LEIc7KQCP4nSk0s0z/D1LoT3zRiFXEX2Bl40lAzk2JzjqW0pgL9fUB5Hoz8YrHQwdFys90wL+r5y1fX5YDrZ/3/P27cx349K3w7z++2KArws7IPbaEccYmjxGH/m7AanKwnWZ+MGN7O4TtKOoC0RoTGVkBLe3CNtjBpde32oH7A8ad3ZqSVeSpNNKGInX82IDNFmhp/qMBu1pIW2EbDpz62GDw5t0Ktn0VGEmP0oVNLGp/SNUlDxouR+jjBDPd5RssYjxf4cezwFU9Ziv4zvQC35zehuaWNTRvwh6D5aYyplwRRsHAvMi+nL1JDa9iSGT3wl1OpZ0ZbqpjNCKbaNaFn4d7MShA9MdxVlj+ZUbsJgOn2QHLROhn+CyY/jo6vu3rwGuik9GAn+2UFV8FvdFPdrCoe9zdcqxd2lSGNVJ9oFHTgMcDVtR9FdA+VadNF6IFkdw/k02fEbauH0nF6CvAm/9bfrqf4eL8ymkamvAmmleSoNzWQp/G8kSLyayLYwILgtvjkp0NwZiUaphWDSvxjY1UulAVbLuFj+nsJ27xed/9Mo7u5C0Vl9lxFyKfO6ELKbhfvU57grZ3gYM7+Jht/L7NYOFGaFqzKdExRbHyH/sD5Ji+TgidiZZK97olyhXHhFpaT9Vj3zVgN0lk6g4JGED1wsZ66OyJu/i6oUTYEK11sAxERrAtvb21BI90dAYg6PMHznS0C1v+40301vqgDX9vwK42+oSwxaoVJ0JF8RxIhNsB0OtAeBVVl/7LfFlCvwFx/ySWrm6UhwQXCIjeMEfGMvzYcUznYzvts/Z5LXsU8U1SiWqVLYwFCL3N+vh1aKKXaN7YTbH+EXHhzzra1GiuEI4omdhs72mGCn7SkN3kgBkDDFmOWdgGUX62eoWt5h5bN8QXwPk6bYJD93ZsEzfWdXlkOLbWRh1W1J8EaI3RadOF4CGaUxLvuZJQ0lrYYtXjBUC5twB6fgDGnI46aOEv9DfQtPb36ZSxRkgTLByY7CokEXVrGBJcTgk85Z9ztntjep9/d08D0TLwb6vTNmPassyF+hJCE63HFlH/kKnCoaeM2E0Wln8/M7e7mJOaBCBay9+Z5saI5h5boslcR50f1nswe8xqmNnuok67tE1AJc0hv9D6kVZ7ARzw0W8N2NVK2gpbrKq/EChvDp9TiRjCXcVn3SSqLn07HTPWCGkAokn/2nTmEP6N84JXgVXYhJUNT/BvsZqqy17olVVzERGEcFDUEGgnGinXh88YsZssCIcbOXUpzh5bjLuHN5Y6aBO2WL48EyzvlbrsBbmnSxhP1ChsUV+SBpy67DDwZHxDl70OiP5Gs5UOSm3STti6vnGYM5Obrd9MwH1pD5DzE6hdPTuV44AKfQAi28gs476F6sO9mJdvYkXjX6HVmary1vfMFJqMYSt0x4HW5rAh0PDiRTYMLzQRjWF9qg+bxg1ahrLlRc/W1/VwA0kaUGMSActWSSx0ukr4oMm3oMseB3Zr6zy3NMay9XhVzH7dk+eJG1W3arZphLQStljZ+D0WtTN4c3ACinse2lovo1nnvAfVZQkoTujX8CVDOhBjBvnfNyHTKsYpDefS7NLoWca6IT22CeZ/EaNv7D9QTRzTnzyH6J/abSYdGmnk3G3xx5t+20Qs28EqoRItnNjUa0tkXaf3Y6LF3Xz+0b9Lm34kPcI2mGH1Uh229mEZ1Za8YsCudtJC2GLVipFc1Tv4JNXvCN0d5Uv7S9i49Q/84/InoDxBUNfG2MLsCJ0ZDl5sxMoVZ8Qz6z144S8wWTGhG5HdEBC/ZKbYPuaGoEAYZcDq9rgnarbhWsgwUJNhBarX9r+9MRGcOKV3MrmD3WPn77H2gK4YFagpIkhGZpV61GKrM+S/RbtNQ6S8sMWqhqu4mqqXNgE+iPQ6+PC7NLMkffO8C+mJnz4HW3oQe8Agvj48jJPnnxrzpE5vpkwcSzgU+ZpqgRlhS20rjdhNKqg/kQtR/OHQ3l75IRxfrML8adYRTq+FLYtavSG+ANbD5q3Lu+3NUQkatM1N67WwxUl1gyDPvlpHZbpCT1HNmNX67ZohZYUtTlk6FLwZ87klPyEBxamB4DmwfcMNdPdlzQkoTxC6YmFsGX+EUJwA2QernO23x3Q00jHihpBwoqXSPc5AmVtg1vi3obbvTI/AixdlwPBCAz22GLewpSdv9mFlo0rvO1JvXaxe+dni5MX7QXb2t3TVJgDdHWoEl2rHtfBnoPb3Xt2Shh7bXPs6AG19yHshSJveWkVKCls+Ub4BGZnzwHyiBcXn4MCVVFPyeALKEoTQvL5qLRxfrPzKspNdlfQEr8fy5bPVjSb6oRLqK+H4o/TYAhxroNTnusxg7wt8oUD11uq/byP1NI2tmpg3UmNNoNchv7KyrlCPeuri4oCv7e4Iryvf8d6PKPfSFQEvq8+FQda0XtdjXwiep5rSRu12DZJSwjYQlzZvFn/B301IgURPQUvLpamcGk7oHwR7P17izaTkOu0DDAOwlQ/+P2I4VoRtYmmGN1e/DxB6Em5w+NRA2EZKm6HTmLHhSCN2/RQ2YkVkaK320Q/seZKGYHSNa3RWh3VCHc0654MIR+gRtr11RRgEKrSZ/s5ActIiEkJnUkbYYmX9VwDz7uffyIEJKE6l/fwNbNz2K5kgJqQMRMsBUYRtT7Gs8yCKsEXLQiivPypBNRICvK0abmFfzbFM9NaqG/JzRuwmEzTSs62SWESLMRwaEyG/etNjO7zgXNDdg0wwP8oR4aN9xEePhS1Onu+FrIOrtH8XBK/BrLGL082dJ+nC1m1hFRX+gu84Pwfz2cMUn/IF7ztUXVafgLIEIXZa/Q9ApudXoD/+YD+BomdBmrzsIEjIRFShg2iJGRBMxBR2+Dr/kgG7yQWN+CLvhrnPrYHZPQg6hGgg5Fcv0uoS6g3xRbAZWtZGGwXSFdGm5wkaskd9mx8P0lSPztyaju48SRW2btidooKHePP/ElIgwUvga/1GlGEFQUgKNHfsOqxs+GsgCYEQP3gkWtOtiMlUvB5xQ0g0GC2VLujvQSd4j2rH7dBuN/mYELb/6XECIp9vLXh0y4ie9dgGs23pDTqPsCBqtBWiPZqS6+SobGkxzRPoRHAUykT63Hdh09ZHDdg1TtKELVbWl0FW1l9Ab2aQ8BDcA9vXXytRD4SUpsX/Y8j0jAfpVewJXrj69P15HT4ep6TSTTx+J1pEhCP0F0p9rrcWJzcOhGzQH+orasSKCHha1xiQEV/oicADO+Na0PvjdqAV7ox6FKIuVwT1W1HuCJvjes/UFSpylP7GoUO/S1dXzYQLW9WjAhWjlevBLyAxrgf8xTg3UnXZjASUJQi9wu21LW+4HCz8K4hLQvx4UQ3nRRC2KD22iQb90SIimBC2L+u3mWQy3Fi/+ltlRK/1+L21F3wC5fVqKF5n1jgEn7cIAhEXYnuDGxEAL9P88dTHmI5Zn7CFjPiFrWXdqK/8DtZBy9oHDNhNCAkVtu7s1/Li+3lzfIKK3M6i9ts0o2xZgsoThF5DtaV/w8p6vkhbKtNNZrLrk1bYGCXMj8SwTSy0jWrHrQ/3arAX0kBEBKuHs/xTGJtOMnLuIvU4Taryv8TKxnWgO9JIhpukIWZhC4PgW/yH6M0m6HfmxXScPlcEVmROXBPIcOqKL4PHo3/CsQO3xZzwJgVJmLDFqoYTIN9W/hom0gGGYj34YDzNLJMsYkLaQdVlf8EpDW/yL/QOvmiemuz6pA3+8BMd3NGi8mIz4ZKEMGDkYe5s5wgjAxMIfU/YEl8H9OtaBxz/q70z4Yb80jwSEm+SBktviC+ADfDm6sXhQtTtg7506GTHFxnB9pjwrd0EO9ffY8BuwkiIsMWq+ol84t0LeocrIvEG+NtY1EbqKVicAznZN0JN2fR0nPUn9H1odumraFmnQ3ndeWqqJe8qhcS476QxGN4v79ovq0a1JMBILJE7FggPMyDWdkJt2UdQnV4hiqKCcIoBq//r9SQ7Qv0hvyj2yAhYXncqWPZJesuHuyKGqOtSAY0+tlbsPbY4peFI8OJ52spuh5w/pvtcJKPCNjBbr+6XQX/aRI3//RNg14VUe8G2sPWqaBwHOVlqmOEguHL5TFCpFwUhBQk2up5Qi5sqMjOrDCw8g5+fyMthkJjsfOmD5WwP+5rHFv/axBMt1NchBsr8sK91VgRSzGcerN+yBl9kojXahuI7iCMygmVXaC7cB9R2VxzH6xO2ZMXeY+uBn4H+4Y7PwLc9+oS5FMeYsMWLF2VDRd19vDXRVBkhWAIbtl5ECyc2hayTcjAfbNXwqXBVh87Ot9QsahG2QspD8yZ8xquHgosLTlmUBzjgIL6x7A+WxSKX1OSpAfxKDm9n82me524T5PC2GjHhhdTrAwLHuXnFc5LyB5lg867Pw78o/rUJx4k2cQxNCNuwI3Vpi51xhhG7hP/utQ0TsWwRYhK2eO2KkZDpuUhr2QRLIvmFdz9eo49t4PodFZxWfwTY1rc0FdqZWpo9cZcBuwnFiLDFyn/sD0WFj/Hm6Sbsh+ERFrWXsqhtDVmnivpjWNQ+wptdfez8brixtxNQP0HQTvAi9FZw6RFu1hr/QfmQZ+cBOQNc4UuWErx5YGFeQAi7IjjX3Qe8j4Li2BXOlLePUM7Q8Kf1hM/DNWpdJCJC4vH5IvfYEh2iv7cP+l6KdAvNxHon/wu9toG+tQakRGw9tpl2JegvPLZJY+3odEUAjK3H1jYSVWo7bN81W7PNpKD9bAwESc5ZzpsGhk3CQHAfbNx6RbiYa1jZcBFYlnKG7u7ja2GR6eoJQioTnP26BTSNXODFizJgwIA88LL49eJAQHskl3IG3wC+wy+b/L1FmUUtMWwTzHqaM35rxCMQD9VeKsFO7TaTT4kBmz5oae19vN+dLWshP/HCFqc+Nhg8+VdqLnctzFxZBzVxfNwEu7VdVjB6Wt2gb62BJD40h+4O78KZTmg9G7Gy/jTwZiyGxPr9PRRZ1Db+nC+eKk1p6FMPjaShE4R+S3DUpLNQVnEyH8fy5TeB5b2Ft683UzCEHRLFs6d74Pjiw82US8/ydSS8b2+vQUd5WxuyrXz0zjdimihiby1et7QQMjLjmwUeW8HxBfZPcdwRUMgxkHaYXqd5E3rd20h3nb+T77PKTUrnfX9/NZIUMeSUna8m1OqekH5n3FnY0NmjzdWVogtbFrUmemt3g+Or0WwzaWgTtnxij+VrpArnlajIB+ok+Cu8tvJ79OTN3USteyM7ofhPfMO5KoqVAw3VThCETgQzCf2QrxUqbum3DRQRvsf2qNGqZ9BMTOBWmkhzyz4xYtswOHXZQeDJMCNso6XStWwzo3qIfWriGFD2eP4sTQSw7b0bQjuBCWQ6ha0F3hHqOvFBqBfdOTzDC6doLE/RCi3O3XG/y8E92qZwRemxxfLlR4HlNZFyfT5fnz81YDcpaBG2bjgvtFSWigT61tEyaFr7nVAhOdxJYscXK3/aGBJBkPTYCgnFbXSNHBn9t7c714FP34qt9+Dp6f60mQnu898CHtuAsKX3wr5kG3ND+CRdRa0LevSn4txrPLJ/rcdjRtgSGegFTiJopBHIOKu1mUJ3tERvvG30KneEkMIWhhVcxo9DtJYHsKhHv2WtPrZRemzRo3prdUdCaIGW5j9qtplUei1ssaLh+2BZ8yGh8TXpRficLqIF3Ycp3OGtwZlLePPLsdky4OMlCJE4bnQNXwyvi3rcQF6Gx5hU5ri6r/Pj33tVrwRBs8a8hZWNyg8yX6thJ0IPobmJY5HFW6pjgbmEFdEmjgGNNJNJCxOVBMg4OK1hONj4VSPGfb5ntdkyEcvWDh3yCy9eZMPwQv3uTH6a26P36XRFiCBsg72139RV0F7oXpp7bp+acNkrYYsVjVeDhWoGYQJnZNAH0Np6AS0Y3y3bhxvrLzNjBW8eF4fBkWqyS7hoCoKgHUQDYgLDRwNITVQAcJ3C1g8fbwsfGcKUsKWeR6NICRBN9dj6YffmyNm/CA42c+egk6P6Z6YLFpqY9a/4kGadE7o3tGfoD/kFYZI0DB34NX7UHSbuVZpZuqpH7yTSN3ksUo+t5bkJ9PfW+ljQ/16zzaTT4x8MVjVewx/xHEjsNONd4MPzaPb4zd3rs2IkZGTWQ/wnvAf2H6ha+O9oqaEgROcI7RYdSJtMMW7Wv+zsmOI1xsH7EUN9ARiYfOOS3j22YKKR5fJ+9OxFaGh+AxZA5shxvPEPM/YTA1auOBzQM9WQ+We0WkNnrX7NFSZJg2X9UHNBqpH1px6/12ftAa+2mmTjFQuy9v3t4NS6o8Fj643XG+BBmllmoFGSXHokbLGq4Tp+nAWJFbXEN+8raWZJt+FGrg9fnD11EGvsu33xuEJDhK1gHJzcOBCyYbh2w+RLn5ngWRmngX7XpdfDveCGHxteaMblyKH07rHdN663LoiiJGaAmIPw9wjLmo6T5y9P115bLF8+ACzvw2BqwiOBPjcEheNfw5+5VpOh0upiecOZYOFpmgvaBlvpgR6/Hdv2aJ1elFekGv0bu+yzbRO9tQ600e8020wJ4ha2WFVfxZ+vcjRObEBIgjlUU/JI9/o0nMCPqqe25zMyCZXrwmO9qJ0gxEYmHWnGr9DWfdEzB5roeaDw8Tj3L1RhvkwM5xLs2p22whYnrxgG2R5TE61i6ck2J2wBvgTZo+Zxo+bqcKEgUxWsbDyQRe3fePMEc6XQU1rNtXz0IX/e6nPW2GAN0WNr4Q367HdwHy0o6+baGDOW1sljKrKf+k12CFs3uRRa+jO4Ev2NZpf2yeRUcV3ssarxJ/wt3mqqMhF4A7av73ZCB0VtI2/2clgT4/HJFYSeY+FhZgy72cFSHpxUNwjy7O9qN0wQPjWo5Rytv7PDZX1aBzTPss1FRKDIob7UcCsMHGE63vkVMLxwJJYvn0q14yL7+6YAeG39EMjAawCxCgJZ/MxA9AHVlP5Xq8l5k9qwomE9111flKF9evSDw/ExRDqKCwLw92zSWDt7rD1uvkVdeOyujU20TPTWEhAmQ8slhJiFLYtaFWbiVwbrEo42AN/l3XxO1A0y31Y+VL331UM4vtc2BCEWiA4wkEJUDb3qnkxhhlx3SE1vNAQ1UYl8/wr/smXGv5bS3A0BDUZEcNoi99gOHDEEEjPqdzZY3jewsqERHFjKZ8rT4Fv7Viq4KATdko5liXE6n0wlkGmdDYkImYnQYMYuKl9NneEzh7m911ucz91ng+zwiZZ6zpNUPfbdXlnIcXZr7ah23B5bwKueyIfc7PP52v4NfcY7WEq1Ja8YsJsSxCRsWdQqB/ZkiFrVrpjBJ97L3fbn2ypckq6hrC+qMGFR0z8KQm9BOMCQXTVUNcuIbU3gtMaz+PqvO6i64g2qHbcjfMGmYthGidOa8hiLiNAEn+z+X8QjfL79wGPCOyQkFv+tZfxY5vZ7eUe1sWBay/eWdXyD2cKCbCs3Una6kUWQmlgAt4CFKkpOGzjUFtjmtcOy2AIfv8/nZoMjlQ0Og3GmyWI7FosSdaJl8nFZ7hotlbBoAL9HBe/bj09DJej3B5VyPjsYhxU7HhKE67pnAiVsz9JoT6nFD2CQQS8rp4chvjqhEs/w+aTPDcOy/sb2EPJzzYVQJX+f7a1VRL2yYGV9Gf84qxNRmRCsh7atvwnz2kkay0HwZJzC6zqNNgWCPXg2AAAgAElEQVQhBGhG2AJ8BSsafgYzx9yaiokacOqKL7OQUZkJ9V+so06EMRXqK817bE1NHAP4T1S/VstOZNr1fVFz2A/jq/5hHYKyYxQFuw76WiH2d+zqLEaD2+Fc3RM7IyUSDjhtTxqyHT7zX2qyHt5Y9QRAqQ5bys9W10iU4RYfPUU1Y/Ql50hBIn6AbrpFb8ZDkNDkC52h6TR74q4wrzVrvVpYqLKmiLAVTGNK2Kob7W9gWt35WNlwP7RBI/jWvp+sIVc3EsGQ3JHcYDyZn36dRe2FYO460hihHir1ppmA/ZTuob7AVI9t9IgIFvat7GDpAsGLxlKnOs5a7ZERTEI0L1Tm0h6iU9iaheCWZFfBNGGFLVrTLagoXsCbgxNYn86sgVdX3RuhNRU+fWbPiDFTmSD0iqFGraPbQDvV7ZPyjmrFysaNfCHbzG3A7XxFawomcgjXm2bxsR41fgEQXLo/D9y5ENQQrBN8jxUcirV5Ww29FrCYHAKGZmztQxtQW/gZ3kPyVa+kCUHtQGtzyk9ICgdOfWwwePJ1pyQNQtEFv0PZe3tDhYSBZC62L1I6xUNtgVaar9Ge3sgIpiB4nmpKw3YE9BXC99hOG638as9MXFW6UROxNUXOi4A6I4vAaJWqL91CwwhpR0ECy1ITUUbyuT0y8DQGIbHvIdGed+zD8K+b5dmI/rW2bSiVLq2leRPS42YWCivHXESEmHyPMdtc+UJYHJ+5sJbNLGzT5VsleITmln2i0WLPw4UlEvT3+d5aRUhhi1PqDoAMO5xvayJo5vbPfRGPwKaVAHk64+YNhKJ8FR2h+0Q1QdBAcFhcX44agXEej/iysVS6aT5xDG1zERH8sSRnoIxUcjztH9DrRsOe3XnOZiivVyNCqS9vyT9brz3aYyTajU4IXoOZ45ZAtZPsmhgndI9thq1yB+uMzBYntJjmlW6PeMSMC7ZhVcNrfHE8UV+59ldAhK1gilyvmSxC/RcHWlofjXyIoYgImPYTx0z12G6hmaUbox6FmPRwW/2QB00aV5NW3WgT5s4tXbxAtWNe1GoRNSdpMENKTiw2QTdhi9MaTgYPfisZlelUi2UxHvg0LxqFLYzhpUajPUHYi52dRjMr0oKnaO65GyIfgmZi2DppP3HMUCrdGCaOucdRa8r3cPUt2qDJH3kUVA9pIGwdvb21AVJd2L4LM1cugpqSZNcjIXTvsfXgLyHZY0RtrStjOs5PT4PtZmnRBJ6pMuLsmwxCELSQuaMVQCaDa8OhP0d6GacsygNvoc6A8XtJe1cEU+IjholjgeN2Jfs207+gv9G8sZsSUFCqTyD7GDZuf0S71VR3RSDnt+Tc3Pd9EIJ0EbZuTmLL0p2yLl6aowb3bsfe86xmP9scyB9RzGszmVmE/s3ct/ZAebHmfOr9FBXpYfO2yG4I9gAl3kzcbdrg4629y1aURLB8+QCwvEVmjMfgX6tw4BP5FSQSSkwsenLWAqbwwBTRnbRwYqsBy6k8eWwdNK8z6oaSanTtsbWsayH5zehPYo1MEPCzbVRp4U7WVroN54IIW8EAqsWMlY2fQSDzkNA7Zka9QaFlKCIC/NfQzTExWO7EMTPXeX/MPdmp3rPXl1hB1WUvJKYoTOXvtQ1aW+4wYjmVfWyJfp8KKaQTSYewVUPwMHDEt5Iua1U6xnggeopPKn3CluACfqzUZk8QuqJGI0TY9o7PoRliSYVpxr8WIL0njjl4pKEIwwStMQrb2as/gPLinZAuQe3TF4db1D9LWGnkrNEahlMr9LfoPvk9JlWF7UYg3z3JrkSi2dtjO6DobBa1yXcApDgvdIRPcr1v0FY+wsFYueI4qh77ujabgtCBGqrFM5Jdi/TG+RXNK4sYNcXFVKivmP1IUxTL2OSej2heSfTvBdpHLxqe5y+pzFBdBBeaTzVlLyWuPGdtynpa+fy1xmynqo8tObdT7biWZFcj0ewVtojnJLEee0EYipfV59KCsth8VpqbnoGcbDXZK0tfHeyv8aMIW8EA+Aw//CDZtUhjXoXXVs8FiEkPmRG2EhEhHLH517ZDsISv9yJsTUH0AezY/eOEFlk7bgdWNn4OyctYGhqVcWvW2OeM2U9NV4TPYCuYcb1IcTr72I5OWi26YsEgOInXz8RysMr+g1WN6tgx+qqAF/PDdH32BCFIE9/Ms0G1oCWmbfy0sKi8Ipb87njF4wUwMG+EkVpY/vR2RQA01WMbn+DHpocBcm6DQIY8QS9tLLa+S3dfsC0JZSs/29QStmg4jCfB7hRw4+wKUU3MHYR9DFfYqlSyUFSYOrHn0FI9pjEJ2wC0jN+kUdjCkVhedzzVjnlNo00hSQQyfuUfSNVjkz6TXQ3VYmXjQ7x5ebLrknYQVVBt6SsxHZuXa2riWDNs3Pm+IdvGwcmLcyA7e6QZ605cwpaqz/sYKxruZQF2tZn69FuIfys/oJrS2MJm6kcJW33zXnrPR/DaykcBDMZwRWcPGHJc7yHboRlNxOtNCwI9tsMLVeiX1OlBIrgMy5ffHDEHfGda8Amuvd5wJpb9bX4UYZtmuI20YQOPBMs6lZ+ewifTqdxoc8APqXPzbIVfQwaoUYHUTz2ZMtAf+EY9L+bDLVP+tfBOrFFbUpLM7MPB1B3YiTHUV2fI93NAr+rI+IL+CvVLHP4erucGYDInDK1JYtndIZodyyhPr3BwT2rpWpoTLXtrXyYgbB0YnFL+3moSm+VVE8J+EcvhNKdkDVY1qovqsRrr8F0WST9L65tYHwctC+Ga+oPBCyfzRYVFLCgRqzLRqXTQKnVgI69+DTWrnkil4NTu+VrRcANg/21Rx8ntUDvmR3HlOEdDqXTjHW5POZyjDOlaFg7OO/G+iWrHfYrljZdwlZaAuCT0lj0saq9iUftQcquhJpCljMrbDW2t842Xklo+trvB8fXrDKpBH1u/N+VmMiL8iC94f6PaktiGHon+zieXPmELUATDCtXEhuUabQq9AKc1DOfTVA1xncTnx6lQUa/E7L6+XK+ypF0IfmchzSwLxFSckXrzU6imdA6L2wP5nL0x2XVJYZr5JllB1WV3xCVqXSQiQkjQMjVx7L2ezr7ma3wDVjZcxJVTgixHc736CfQ6+JxLaNaY5Pt/O7g2ZXQt0b00Z/xW4+WklivCnarBmOxKJJOAsE2t1kY7XhYxj+GUpafR7PGbox7tcx4Br32T1hpY8H0QYZsUuohYwJNZyJ4EHgwV/1Upnpf5AvYP8NMjLGbj7jVKFixuf4SV9Wv4RJsBckPfl+eAnKuppqynQtJQDFsn+cKhN5hKpUu968mm6tIncGrdqeCx1RD6KZpq1R9gAUO/gtdWzTM+3B4rmDLJN3zg0O0JKYkoVSaPtfB98I/JrkSyCQjb3bQxRcNkHwgZmfUscsbSzNKNkQ5ULVWsalQ3HZ09NReysB4ak7AWegxWNh4ISCcD4Zf46YkRRGw7KrB7PS9LoLVlaTp/P6o3kv/+ZXxh/BU3MC8B1aDr37zBgvZWmDl2ITkO9cQATl68H2Rnm0mC0ZLmPbYERxq5AWOcob5C4F7Dremnw9Qzvg2W9UPedYKGmvVV+HdCc6G5+T4VGQigNNn12cumbR/A8MJUSB3+YMeonWl81p6UuHIT3BNNK/UHXGFL88dsYVGoxMHQJNcnFMeAjSuxvO5rUaMUED3I4uAWjWVnQEbGlbzWabPf4k7sGpp3OFg2i1freAjcuE7gm+J+rj9k+Buuw1/uq6BSHfuRBW3bs30p6DRVl3zIq8u5AfdTsOC7/EF8jT8L1WuV7BtDovjUdSUi5y/8G3/W3VPTC5fozCxTE8d2wrxzPoC5KeOuHRf8+8tgwXGIEeP++CIihCPoC/+AWrCi/iQA6zzeHtvPfg+hULHanwc1b6CNHqXZpW8nu0LhUOmmubH+T948O4nVcPic/G3CSsO2PSngIu4Dx7kt2ZVIBfbGsSV4li8eFyWxLuFBOBhs+zmsaKygmpI7wx7na3sAvBm/Aa2zRvBqPHv671NmmCdNwEl1gyDfPo7vVMfxzekY/kaOhaJC3o5xyJ1gLSDVg4ON0Nz0JM2b8JnZGiefYEtbXZhuc+OwDsg7HdSwrDsRCg7lZRR/sgVJrWTvUTN1/8vLO+DQS+D4n4E5z7+qdXIf0cf8OFWbvQ5wS097kVOCoXlZ/JnvzdJokaOcA0MfzPuJInwn6n2dXqfdz+qqZofJQMYstfwSr3oiH/JzuTFMJ/C14QhAPADUiF5gUb+JlHFw7AXqHrOF/8b1vObGLq51k4EQvQ67NrxJd1/WnOwKxgo31kuwvPFLfN0v5qfDeY/F2z4gbON1M3+Hcd5P1fsx9u+YnE0JdUtratsEud7KjufI9QUrSn2j/U3KRpTXqdPrROsS1kOd4nTKPOY8zl9EagrbANl86boDKxu4Bd90lYqBuO8BNOucD7DKbSmepbHcA+G44q/z+hGNNvsM7g0nN+tIbngcwxero1wRRngci9rhgQNivRbRB/y+f/L7n4VW52maPSZtY4XqIBhYfTns4+OtsvJBPg0D29qPL4rqhj6Ajx7gCl4kbjSgl7+HLN7O4M8zg28iqhvB6rIQ7fOlqC9p332dXw7zOmEr228FFcCMoDVwwyJeo9qnetRVcPDP+bVPwO/jxVpPc8s+6WZn1th4PpqoBG9oaeNrnSiC4RPTcrY03XW+cj9aGVy6gNZ0C678cgFkwiA+7weAx5PDAl41oPNYvOfwqcsLry3MYksZHb8RIBVuz8vnq7oPBhZS63bB0X7Od9nuJOjdRoEacm+DgCj1BX4H/BsAVPuaQP0OiH8XSCxKiZ9bvNAu8POCasE9fL3bBV7aArPG70zrhtM+BCd+xzb5O80Jnp9p+dvqi+wVthu2/w2KClMvFd6+IE4AyHkVq1Z8nWaESJFHzp/5OnSW1jItqIJ+LmyDfotH8MX4CBY0RwC4vnrHwIBc1XMS6CHH4ENs/eXreHmavzBuiPif5u9ynYFq9zmCmWTeDy6C0O8J9vZvCS7pS216urgIQqrRIWxp4cQmrGysYVHy62RWKEaGctWX4bSGo7o5Sm/c/igML5zpxsLVx2lY1TCaZpSu0mgz5cDJjQMhx/dFcDyHsIA9hPcof7xD+bM8EnKygw2emIVrZ/x8hr0GBCtZFK+C1uZVNPfcDXprLwiCIAhCf8fT5dkWpxoG41WsXA5KUn3iYSDYwHWFX3Xe6Qr0qsYFvFmhtzj8CT9M0GszseAVC7Igb/hIsKyR/EnxWn3PvAbkBUZBDgxxTwl30K1XbsqqV/EFIFoJDnBjwPd8zFnkBEEQBEEQekgXYauGObGi4UoWNnWQDs74iN9Ha/pvuk08aWv9E3gzykFv6qHxyhk+5oQRCcTNwHX1E4MhK3MokDUUbBoGBMpFoIg/oxH8zfLC64IRKm1l8DNp/2i0fERruLzVXM4LgP7V8Orzr8tkO0EQBEEQEo1n3x1UU9qIlY0/Y72TuFAZPWckVJxxFq+f7LyTZp3zHlY1rGDRNk5jWQg2/JzX39BoM3RBZ0/3wBFnDOJvZ5A7Qchy9gO0h/Bfth+4PtA4hIXkEK4R74OhUFGvYnZ2iqK3r7uA1sCVe7jslwFJCdnnoLXt+e5xZPVOBhIEQRAEQYiFbsJWQdUlv2Nxq4RTZajXUwqyLoN9hK2LH6pZiOoUtoqvqdiKwTA03XDjRO4/cBR4nByg4MxcG/LcGblqZi5CLlc4n8VqvjuTnTDPXSMOAHd2OwtZtZxQnNfVcnv4xk4CNTFZTpRv7Ftc2IvgwL/Aohfh1VVvSm+sIAiCIAipSEhh61Jbdj2U1+1k0aU3Ta1uECbi1MeqaNaFn3fZP2tMPVTUq2w4x2otzUIVJ/eckK8WbiXwFCzmj/WLrvC020f9O/egdnIBwM7Pk44KM7OWH18CpH+7YrZ1679p9sRdXQ9LoQw3giAIgiAInQgrbIPx9G7Gisa3wIL5EOhRTEVywJt/La+7RHNQ9cfK+tsArfv1FofjsLzhTKot/ee+r9C8SW1YVX8zH/MXvWUaQWW7+jeQCpLPa2fXS90aB4KQRuDkxTmQ6RkU9xs379pECyf6tdVjyqI8sPPiS6Qxa/yGvhTDVIgPvLZ+CHj93VNXtdmtIWM/CwkHpzUMB/R1n3vkd3zpnNa9LxK+xzYI1ZQ8glOXvQDejLsgdbvrpvBN7fZAzuxOvLb6YTi++Fdu5jKd2PgHtKzTQt6IalY/BBWjb+SfwXFay+w5amLdGjclLcErQPgyUNtLVDvu02RXTBC0kp11DSD+Mc53OZC7OxcCgfb14C24Ic6Rru18LTGWUQ6rVozkS/1EvgaM5t//YXw9VOI/m68Hu3lbhd17nrfvp+qSfxmrQ3nd8QBWdsgXnV3/bW9U4+T5Xsg8+KRux2ze9pqKeGOkbkpUZlkT+DNQmbIO5c+pkPdmBj8fvk7Sf8CPT8KslU9qzZDXmSx8kU+c7tGILFjKj+caKTMMWNWgsrudD27WQ1QZD1XozPbPQyVceQeIngRr98M0w00kY75Oys1vWMG5XB+VYlndW4cFXPugieuzibdf4hPpIZpR1t0tUUf5qrGaUbiev6PuQ6w2qHj6Zxgp99olReDNPCDsAba/lWaMfdlE2elMVGGrUBm9eFWGFfXfCgzF4xcN1ytehrg3NYDbO+9UvqBYWf97vjrM01zeKVC+4ju87tYzqy58WF7/cxa/T2guMxZUykWVs51FLL3G39OrsHP3a8GsKILQt8EeXZc2aE9VinB4fG+gd7WW314NNy41sND3XA7utX6fSaUIA8FNd6oEDEzBqsbbaUbJDSGN9RbbejpsOmgr/zp+nOtuZ4w6IigUulLkVeJKq7DF65YWQmbG71jUXq5K7uYutvf5BVynn0BF8Rt8D7yEasre1FqP8uWZYHtDixeE/+ksK2I9ptXzZ2/N5t9RSUgPufbzRaVHR7wIIO8WrGi4lGpKlxqtV1X92VBUeAdvHhLi5YFcn6G8/hKfSFdhZeN9ULvy+9obIN7CIyGc3yBxw8cUWZmzudgLwx/gURlYhxorP02JSdi2wz/oh7lF/SjkjLqUn6oL4BFmqtUDEG/Ay+rnBTMz7WXj9nv4R/FTCOQU11iedSuX9/du5YFKJVj2D75JqIvzl7WW2akIXj5yc4gDvM7rN4D8r8MbL7wnE7uEfkxPhK0B4YBxClvULmzdHqbswqfAveHH9hZefojlDfVUW1qntS7ly7/Awi1Sj/Soji0PHRpCP3yqu2cQpywdCpmZT/NmPN/VsWBZDTip7iiaP0ZfljPbo/7+0OE1yUlIhkG3l9aD6nyJZ+RgEFi4CK9rPIbmlKwxUq/yxlIW20o4e6Me7L4BvgflZ9RDiE6n3lWEjg4/Hwbf0lpWu1WVrn5AbrQJ8CkzSSeViEvYKpQfKa/uRsu6B6YtPwvQvpw/2q+DysudXPaHQdbVvK7uvJMWTmzFisZb+LJxh+byDoDB+DNe/zTkq376Cdj4dK9LIdjIn+9/3FYhwdv8A/sPtLa9QXPGb+1+sO4gEIKQTpCa/LjY3XTjN8MJYQ5cwr+noBsRah26RGu6BRXFh4V5WTV2u98EnWCddeItVC5Y4USt+ttD3xAtPJsftQpb/lAOjfw67W2QkJvpsCsE/9VaH0VGxt0Qn6htZ3/It77H6xptdfHTIR0TjfeF0LiwxYsX2VBUqIRgOFEb/nxRLi2ZpDq6pmuvl3JLyTn4Lt6KTdR2vNH6KugWtoBHh33JISPCFvJzldtFVpSjRNiGIG5h207Qv1S18J7CyYuvgZzMMXxVVF8Er2GErgrGyQ+xfPlcqh3X0mVv85p7IGfUD0Glh9UKXo/T6u+jmWXv7PuKmlyGVY0rILagrjv4HXwB44sY8ZrgfVfEtuDbNK9ku946C0LfhGaUTm7fxsqG61nchhK2n9KMEnMZBK87VQ0p54R8jeB2qi551FjZQVioZLNQmRT6VXoMNmy7GIoKfqv66bq/GTK1V8iywgn9dvb22CJ2PxZJq7B1eycBQ0e2cRPNOL/k14vD+0lHEDk9wbJCDbEH8LeZ77EdWlDCj2H+JloA2zZMhoFFY/jzeDz0IYYylWaNGs+PoWy3Avi4UWIX8Xexsnt9oPskvN5CcFRYCdnWYkbYIl0Ug24VYRuCHgvbzgQnbT0WXACnLjsMvN4z+IRXkwBO4I9eOXubj6qAyv/H/hZvLehav0ltWNn4C379Yc0lZiifJLSsstAzmn0/BfLkc7nqtd1cky1cyc283sStvA/Bcj6AZut/MutVEDSDGE4smPVZtLzhewF9/m4NYCMMLTgVwo2gETS6o1iVjflhbokbtNcnVC9sF3CvsIUQrggE7+mtD44LP6pM82hGWT2W1zWDbYebANimtT5Ah4TRJ374ZPc6vWWFwIazwr7mwOPKBx2r6tsiaCgzE8gs+GqYV96lGWPX8Tl8eMLO4bCuCLSN5p6rvbwY3RDcQ3WX3RfQImz3RWX+4pVa7lXP3ZSvU5eOAvTwiej+iL8YnOjBLS7X8Vll0NKTwteyVE/F/7d3LmBSVFceP6d6egYGVEaMEiA+0A34iDFR4wuf8+CxLhqDcb8YY6JmJbymp+PnbsznAxNN1DhPUBL8+CQb1zVxEzEoAzPtQgKKGmPyZYNPBFGBaHQIIo+Z7jp7bnXNANP3Fs1wq3oe58dXVHdXdd0z3ber/nXuuecsynm9ofKXkGhRBSfOstJOJwjlfNxv8aOF3Tf5sxXPs9qeIAh5oIa3Ned8NRoSJjFjfG2Gb2QjiZcEJ8CjiF0TXU7UbnfDmAiD+xspG4o1vz2K6v7lb4Z97YYiIJ1i1AMZNxvv7Gg8x50QrLdrj/Em7G11E2K1Lb0BJxk3UdqP/w6IG8cQQkW8tulULwd87obOPqq3m9xXbJrhi0z9HB3CcCaO5ReGoBBhqyEUYdsd35u5DgzeEi+Wpvi4T4FDh7MKHsZnF15ih/IJqJRPIoP4q8uuVawNUnE2FQvxY2+mbxFvK/L+FsyucdYTw7vnZPXy2iZbv8v7/x5sdwaCn2By2VIWsputHlcQhJ5BLBb0M7vD9dgSjTNcjDfkhEiFBdJJxlNce0f2QowGUeBk7F+oMY8QMCweg9XNOyEWz53hbV04BYjW2M6sd1gXEtGJbQ+yfra/IpobIXOscRq2bPdtCJoQ6YaS1YO/A5PgzgpXUx9Gx24fHjLInBFBFzNvg/zCEFRfFGGrIRJhuz/8CWmb/CW8dmorVmNN6lHuCl+zemBUef6KVEqxy6weVxCEA8bLeTmqzOBhCdtrahAAZD/zgRFzPOBHKpG8lxGguERXyOITqF+zEWrzmRaQH95oXaIlj7kNzhi2WeedJGjfavs7M4nW9/fKvmAWtg5ZE7bZCVJjDH2Vwp84lm3flE1kfZfHmGis/oaNSaP1EBvlnIL4IUdpNxIEjToQdLRZ9dhyJ85nBMRec9XNh/INXn4zwZFE2GroFcI2UjB9E//ZKuH1YZaPPAWTqeuotjwnJEEQhAj59DA14SSm3YZuuB5bUw5bpGjia722DJ6uTkFQHDdtf9V6/s9pv1V5cofsdz9SoWmUWySDYHNuWe+eo0IeAEv15/59PLEBISXvbbXXhwYddywYr8PhZ0SAwUer+GZ91gHaO7+y8fP4GOZWbYZGy3UrnFJzeETazfZj/Q3cOzb7iwcGhPZQCBkRYjFTGILKWdtN7KOdEM5+xoATtipcAGtabuP+0BDC4etxdsv/UmOl3RgsQRDyxzFOxlGplUITC34s3ijtRjcaj22gp6tzCHe3swGK3Wtytrr4jnWDBg3KLxMNwhg+J+dOwEXbw/6DgmJFvbb89Fdj9DvRBqtxr0F9FSiC4gyxgBCDbJ/1PYgjDTu9FkopaIzpY8DVxL33t73hjcqMLLsXureMrv0+HJTDNhNGDlvnSs2Ln3gpPxG7/7bFY6thwAlbj03/mMc/im8G5HnsKYdAkfMITltwoR9eIQhC1Cjvn/50vy3UUtJDB6vha0N+WLoAk6l9xYELL1N9uT6FUk8J8nT5w6Z+Mv1QEurnsN+MCF3oixTYzmEbGDvrhxiMGHosgDHtmWWhHZDqy01HEGMbWEzEn0gXkOkjpKp53BNM/XjdXjcWPw6l7RyMIyBt1FhhNXzSv4nIjQUiWMl9Vxc+JMJWw4AUtvzDyGB163SI4WqwlY1hD+dA6XEqWbW+cIMgCOFinGUecswiOkHer6tzXnK8pPZ2ha3Z06UG0cMr/WlEW0lMs5t3M6LLP2pX2BJ81uwg9UVrLBaQdxctpx6jEwyxqy58/Lfwbz4woHqo25mezh1rvkyGNRJhnAAZaR/2haap3LF9W8xhCK286HJTi7DVMCCFrYIaKtZgTaqBu0WN/aPjv/OxV1Jd+TL7xxYEIRCi47VigTDk+Foad0DXGde1H3dr9nQx6eiFbT4ZEbL7fRp0uXfRjc5jm3GzotXlfUw6znpGBGOqr3dV/li7bWkx34x1dIrWgBs2Cit2HPU3aBT1zVmR+j2ZysJFFYagbkpbWa19V7NFhK2GAStsPTa1fR9GlanqJj0prRiEw93tEZy19HRqmvS25WMLghCEyWOLYc8yDxzWzYWcELxdRk/XNmia/B40WJ7ks19zUOchVX93989K7ZVbxCeTsZ1ayyRs9+QZxoCMCGR56B17aaovNcy+p3BQQL/OWO/DOC11GJSCPlYdaR3Obj1Du80hovrKl6waEwvI8evaFdnGMASALTC38v8g0aKb9CjCVsOAFrb02NSdmFz2Lf4YVG5b/SzqnjMc4sWP43WLzo/ozlsQBjzozHEgMf447UYKfTKOSST8HP686vqcl5+5PW3fBHPuz1Am+QRZkv0udJOwlkJ+zgSrw/H+pDBTaqu3u/IMB3l10669VF9Ze47Vb40g1Vd2ouER+nGLlfEAABS6SURBVI3w2l6PTeEKLmz62H5xhhI6EUy5xYhKoMh5Ub8NVbyrXhD3mICJY2R54pgxDIFScOUv1RiCLruICFsNA1rYKqh2wnOYbL2f+8fNIRz+DBg2+me8/kYIxxYEoTszvqTi4fQTf9AJTdj6Ik4viBBeDUfEdmsmyNMV+RAukzhb5WfNvVATNPNnksjjCButOgWyk8J0cbzKqL0Fq0nY7oAHJr4Lcy15vY8oO8ZoD0WQ6is+1BxfC+CFGPj92hROslE5h6zbZfaSZgAcswMqjJhXCEj1FUtbDkUwhCGo+NojStVohk7ESrovDQNe2Hpk0reBE68MIUuC4hqsSa2luvKIZnAKwgAmXmTyyPHvvCM8sZAVcYP1G0OaOd6drKdLv82/6GOy5XIg5/Tc7dSsCthYtYccXUaEnbCp7Xcwqkypw/1clMmuNzBoUpgfO4vTlpRC6eDRhr3esOr1LgpI9RVBcYbA0BnyPbYzz1Xi21DaNbTczIYJkLQBEPWjMVnsFmbIYhLZH9msNBoQhqC2fhGKi012iMdWgwhbUBPJJu7GROtV/KP5A+jivA4WhLsw2fo6Xzh+bf3YgiDswXVOMMilndD0/CZoyK+gzwFDRWONl5hMOpriDIHxgJ0VkpwZbGeFZo+XrdujH9J/MxsClnqXH+srbnViO9VX0KSwzny5g+Mq5lW/l+2JYzE034S5EXhsgcYGCGs/1Vdgpo9wbtiMlfNQ9WFz1g/XbhUwfwREf5NjewTEnA1BMctY9U2ErRYRtj5UX/EGi89p3E/+K4TD84kSf4GJ5RVUX/VsCMcXBEHh0PGGc/1b1qtq7Q3ROMPFR4UgRJBoH4JK6fLnkgmq1sRW2i8Nyt+Dbgi7U6wq4bY/YWtXSAZPCvPbCihYYL1YhNFjS/APN4I8w0Ee27QvbAMzfYQjbNHgJVVi0u34OcSKdvJOU3PfZ7kPl6bZjiL9H4+2MyI4X+3hG0XYahBhuxdUW/Eoi9uLuK/8WwiHHwxObDHOWnoeNU2yPdNXEAQPY/qkcMWlYxQJG7omJYUNGjMifAL1azYi7BoGOFRXQaod2jfY/3x0xRm6vLBqqB0v2c8Rokv1lU5nz8mB4jeiVF+qjPCiyk/stqXFFGObhi3bfY9xUKYP135GhGtbhsBw5xjD5rX8W1qLydQw7dadu+0KW3LMN4pgb+KYH4ZQ1dO327KjPyHCtjvvbU3AqGFneHEt9jkC4sUtOHP5eJpbZb/0nyAIhuHd0GMWDRkRQotD1GHydL2qvNWYXGYaxn0jlEqJuhy2e7ye+Qhp2zPuTaJ1J8x74R1ommRKT5bFJcvFGeAEbVsYfqovnLYgDqVjDGWDYX1XdS+iscZh8DTa79tlntg2fQPZGFr9qMMHNP/Sv9s1xjFPHLMZ9uDEpoA+DOF9/mN/lH3o3ZR9R7OPCFsNImy74cV/zVx+ORTHVEoRU831g+FoPvZyvku7INTynoIwwEDHQUi06IVt2MUZiC/I+rjASCaO4czHh0JxmX5ov3O2uMkDFULGBF845U70yWT2DkUIogN2rd9gz57ASWFvdoWpBHlsd1hM9WVOhebZY6sdI4OPVm3Htdv2ydVr9Nh+DHOrNkOj5egexxgnTtDR9gomF5tGHeyH0iCYhW263V4oAhqyIRAsprqKem+XRGoiOCJs80WErQblTcWa1FTuMikwpoc5KMaBE1+G315eQQuqPgrh+IIw8Ji+RN2I5lavUrjheWz9ocRPGzZHkxHBGWb2dHUKVzSJBrI/mzw+Wona3OtLJp0VtpR5EzDw8rPBqhc5/0lhJmH7gdVzdfV4JbJNk4UimDgWEEvs34z5/VonIhWvhZMX2dhHN9LcqdsxuewcwxvDSGdnEtkf0tzJW2w0EBiGgHuV20YqNv281U1SqPMH+iAibA1QXfkqFrczuS/9LJQGVGqxQ5wWFreVIm4FwQLFxeZZ5pAOz2NLRUoM6a86biaaUAQnIHUUdBamwNP0my0nmlfEi3UTx7Z1CYKPnHUwHJQwMhltOZ41SMhlPZQ4bckRUDr48EjsQfcEY7azKFJ9IZi8xcqAbBorLDoz4Ah/smtQZ9OauOysMX4fLtL3YcupvjzPMAw1ePgjCUPYDlvfTe0xyCxs4cqTxWvbDRG2AbC4XYDJlBqOqA6nBfyiJ26nLZlgPz5IEAYYLos7R3uO74C/PP82QEipvmIBM8djsR9isnVHzuu7dt9AD/zze9ZsQMecExaxkW/Sv2fI053mz22VNTu6oH/K+Uz2St+lJkfxuVWJXJOn2/LEMfqMOccvbvfWgwdfaHw/WU6HZk5Lp8y8ifvMdd1e3E215ZfbMwAz5k14B/eXK/i39Dnz+91H7dmyd9ugKxuruID7y3O8PlVvjuWMCO7gk8xZli3eCJrCEACW71ucBPVhI4oP/sodOzdJxEBGhO3+qF+VhOrxI/kHZ+qABwmL29LBK3B2axU1VmwKpw1BGAA4xrygb4db+Sso1ydcoBFUGYjH7N7IZtznoMhRYkVXmWkEmzBC+z6CH4dy3tF53rBbwQWCdbyPXtjazmFLzq6AaES++UhN5+2mYXcCN/OfVu3BIA87fknzot1wkTSs4Ku/yWM+lF89N+DdC6m28hmr9nShRhdQl2dZaZWzjW/rTGdnC4yZ42vJTqqvwKIMtFcYgsKlYsNNO8BnR4rHthsibPeDN5u4uvka7oCf4qcXhdTMyVCEK3FGagLNK48gf6Eg9EdMqb4KlBHBCFlPAUaNletZnM3hh3fm+ZbdfPG8I7SKiLqMCN29sNnZ/+P1B3DtCtt0eyvEi03CX/nmTBPLQI3dUUPVC1btMaelM2G1D1Nj+V8w2cr9Be84gLepmOd74b22223asm8LbhPf9F0Nplj5LMpzq25ij/SeEbRRnb0qYB6mXLreNkve4WwYgq78dxrSHz+1b5uOea5PW5kI226IsM0DrzJZcvGX+bf2O34aMDxzUJzAXXw1VqcmU0O5/SpAgtDvMRZnCDcjAhrzgZreEErcLdWW/wATqRdZpiX4qRpW18XubQCiX0G6Yx41TXo7DDt8dJOwuolVL5et/t2deWUtoXKH++EY90D+M8nVUPCdUF/1Y6i1PDcHjX1VD9mfUEa1FXMw0boKHJzNT1VOYZ2YJP73Cpv6JK8f5Buhjbbt2Kexpqq/YnXzWRCL38tPJ8G+gcivsg21sKltIYwq+wk/T/ivh1BKl042V2XbZScUwRyGsJqaLv9w330DYmxHDBVh2w0RtnlCtZdtxdmtE/kTY3EbUArx4BgBMViBNS1Tqa6yJaQ2BKFfwhfqswrTbrk+7q8AUH15M6+a8arHY3zBO5YvnkcB4hBedkB7+zpbs7n3a0dtxbF57HMXr+4K3xq/vbry+/jc+jx/FjX+cHeQkPsNpOkBL0zDtqgF72//vPWD9gCqr1ATlFJe+jGVqcHNjOA+cwiL3QxkMm2Q2bZOZSOI1KaGicojeinOeLoM4kUnghMrho729d1uxGr8JRwbait6WjDhANoovyz/fSvm8WpeiOb0K0TYHgDqJIczl18M8dhKPvHl5mi0w6F8YnmaT8AzWdz+NKQ2BEHox9BjU9Ww+zqIqpxvH4HPqWrU7XfZPLJnHw0ujgSKlUIM0oUScr0BP13URn/pFdC8yW28khL0wgEjwvYA8XLczlrK4ja+ku/6TaX/DpYiFrfzMZkaB++13eRfpARBEAQL+EJug78IgtCPEGHbA9SQCM5IXQIlsIKffibEphIwquwUnPXEv+bE3AiCIAiCIAj7IMK2h6jsBSxuL4ISWh5izK2iAooOeRGTrVdQbUU4SbEFQRAEQRD6ASJsDwJP3CaXnQ8Qa2ZxG94EEi+eF5/DRKqa6svDqYQmCIIgCILQxxFhe5BQ7YTNOOPpi6CkROWdM9WxtsEgcOCnWJO6ENyO71DDxG0htiX0QrwJL7PP/QLVV75UaFsEQRAEoTciwtYCavYmXttSCcPxVyw/JoXaGMLXIBY/F5OtX6faitWhtiX0GrzUN4nzHuGHf+BFhK0gCIIgaBBhawmv9vklc6bA589rAMTpITd3LEudlVjTeg+46TttVzESehdYkxoPJSWL+OEoyHTMKrQ9giAIgtBbEWFrEb8e/QxMtr7BckRVRtGVb7RFjAX0LRCLT8FEyzdleLr/gdctGgTDRv8AEJKQrcAzm29iJC+pIAiCIBgQYRsCVFtRj8mUKh/5KC+HhNzcKeA4a7i9evjQvUN5jkNuT4gATCy/mEXtg/xwbPYV+hH3q6aCGiUIgiAIvRwRtiFBteVP4azl50A89j/QJU5CQ32PN8FwvBJrls+kuqolIbcnhARObzkSSpz7wIldA9ni4C4Q3Ux1FfcX2jZBEARB6O2IsA0Raqr6K97w5Jlw6JCH+OlXw28RjwGM/RaTrUuhoyNBTZNeD79NwQZe2MFhoxMwyPkeqLLKWd5nWXst1Vc0F9I2QRAEQegriLANGXpoyse8ugprWlYDOvfx4+LwW8VJEC8ux2SqEXbvvtuvuS30QtBxEGYvuwqGjf4ReJMCO6EnYBfdSA9Uvl8o2wRBEAShryHCNiKorrIRq1tfgBj8IuRKZZ0oAX0TlJRch8mWu2Drpgdo4bW7ImhXyBNMpCZCdcvdgPCFvV7exr2lmmorHi6UXYIgCILQVxFhGyHUULEGb3jyC3DokHp+el1EzR4O4NwPw0YnMNF6N2zeupAem9oeUduCBr7RuIT/vwMcPL/bpmXQ0X4jNU16uyCGCYIgCEIfR4RtxPihCdezyFzCwkaVxz0ioqY/w+09CCPLbua274Vt7z0sHtxowerWKv4ObgV0xnfb9Hcg+C7Vlf+8IIYJgiAIQj9BhG2BoPqK32By2Rr+Cuby0ysiaxjhOEAWuIeNvhWTqTrYAQtofvk/Imt/gIFXPR6DUYd9GcC5GWJ4Zu4e9Ajs2JWg+Zf+PXrrBEEQBKF/IcK2gFDthM28+gomW68AwiYWnSMjazzb1n1QCkrgLoTd0ETzyt+KrP1+Dl7bMgSGO9+AUWWquMIJml3eBcp8R1KzCYIgCII9RNj2Aqi24teYXPwMwJB7WBJ9G7L5S6NCpZZKQAnMxppUM0DmQWh47mlyb3cjtKHfgNXNx0OsaDoMRxVDPUyzSwYIHgS34/vUMHFb1PYJgiAIQn9GhG0vgWov28qrGzGxfBE4sQZ+fEbEJjgspycDxCZDYvxGrGl9GDL0MDVWro/Yjj6Hn4P2Cv78rodY/GIw35isAUjPoLoJf4zSPkEQBEEYKIiw7WVQfdWz6Mw5i8XlN/npXbyMKIAZRwPibVCEt2JNajWo0sBux6+oYeIHBbClV+Lln61edj5/TlezqL2SpWxZwO5bwKVboLHqYXJdisxIQRAEQRhgiLDthfhhAAuxuvlxcOK3sGiazc8HF8AU5H9qBv94iMUbMJlaAUSLwU0/wSL33QLYU1D4hsOB2eecDY7zFUgs/4pX6c3bYHzLTiC4Hzra7qG5U7dDvUR3CIIgCEKYiLDtxfgxmP+Bs1sbIQbfB8QbIJLKZVpUX6lgGypY5DayyP0zi9ynwXWbYcu25/trblxMLh4G7tAqcGASJMZPhPw86GlefgHtmdtobtU7IZsoCIIgCIKPCNs+ADVWbOLVDJy19F6IF9/Gj78Bhf3ulI/yNBa5p0EsdguMKtuOydZVQLACyP09Ly+xKN9dQPt6DFY3H8of7dkQw4v5k78EYOjpLGpjeb7d5c/gvyHdPoeaJr0eqqGCIAiCIOQgwrYP4Vekup7F193gFCVZWH4LChOi0J2hLAknstzlRWnA2G6sSb3Ez19gofcyC90/wq4Nr9H8b3cU2tC98SZ9DR11Cv8KTuNnp/NLLGjjn+O1L2TzTk7RwX+nikO+hwX92nCsFQRBEARhf4iw7YOweFoHyoM7vWUOlDiz1OP9TF6KmhK251xen+tpQ3QASse0Y7L1VX62lkXg6/z6eiB6C8DZADvf2hyW6MVL5rBsPXs0tzOGn6nleP4Ex/F6HAwbzY8hfhCH387LQ/z31FFd+UZLJguCIAiC0ENE2PZh6IHK93l1K97w5L1wSOm1gDiNn59caLsMFLOYPJXXp3Y5QtF/UDrGxZrUFn60mbd9yEJRVeH6iP9CFWP8CT//hPdVXtE0ONTuKWWX+64XIkAl/Hopby/l9WG8/+GAdDivj+T9RsJp4z8F0D2U4CDTBBOLcqT5sLt9Ac2b3HZwBxMEQRAEwRYibPsB9NCUj3k1Fx1nHiRaLuRXbmTxpsr0Fmqi2YHi+JXQspXXunQn7rPKrrHzHXtexH13D6m+hUpp0ApE82DT1qfosamZMBoRBEEQBKHniLDtR/g5UleoBae3HAmD4Oss8q7m5YsFNq0vs4GXRQDph6l2wobCmiIIgiAIQhAibPspfphCrVqwZtlYgNjXAOHqbIypEAhBG39Wv4EMPApNq56R8sKCIAiC0DcQYTsAoLoJr/HqdnScO2BWy2kQg8v4uVpOK7BpvQclZgGeAsg8Bpu2Le/Ky9tQXli7BEEQBEHIGxG2Awg/VOFlf7kDk8uOBXKmAOJEADwfvLRdAwiCtYC0BIiegj8/+yw9c3u60CYJgiAIgtBzRNgOYPyY0Ua14LQFcRh0zJmAsUsA4WIAL13XoELaFwJvAnhFJFaCm1mxb1ngyoIZJQiCIAiCHUTYCh5+Htln/eWHntAtPfpz4Ma+BA6eyXucAYAqlVi+VbgKDG1le/8ARC8A0ouQxhf8Cm6CIAiCIPRTRNgKWnyh+0d/ma9ew2lLSqF00GfBpXGAeCIgnOQVOgBQE9IKUQGN+N9mFq7r2I7XANxXwHXWsl1roaHqHT/0QhAEQRCEAYIIWyFvaP6lO3j1J3/pAh0HYfqSoyAeP4YF7yggGs3rEbxpOBAOZ6GpCicM4+VQXkpB5dcliPPryvvb2QfT3kKwi1/ndmgHv3eb53lFbOP1B/ycF3czOPAOdKTfhfc/eYcem7pTa2ydJDIQBEEQhIGGCFvhoPE9o1v8RRAEQRAEoSCIsBUEQRAEQRD6BSJsBUEQBEEQhH7B/wNfdqcmKZyZhgAAAABJRU5ErkJggg=='
const wave = 'data:image/png;base64,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'
const calendar = 'data:image/png;base64,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';
const cnpj = 'data:image/png;base64,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EAIAACQlCAEAAJIShAAAAEkJQgAAgKQEIQAAQFKCEAAAIClBCAAAkJQgBAAASEoQAgAAJCUIAQAAkhKEAAAASQlCAACApAQhAABAUoIQAAAgKUEIAACQlCAEAABIShACAAAkJQgBAACSEoQAAABJCUIAAICkBCEAAEBSghAAACApQQgAAJCUIAQAAEhKEAIAACQlCAEAAJIShAAAAEkJQgAAgKQEIQAAQFKCEAAAIClBCAAAkJQgBAAASEoQAgAAJCUIAQAAkhKEAAAASQlCAACApAQhAABAUoIQAAAgKUEIAACQlCAEAABIShACAAAkJQgBAACSEoQAAABJCUIAAICkBCEAAEBSghAAACApQQgAAJCUIAQAAEhKEAIAACQlCAEAAJIShAAAAEkJQgAAgKQEIQAAQFKCEAAAIClBCAAAkJQgBAAASEoQAgAAJCUIAQAAkhKEAAAASQlCAACApAQhAABAUoIQAAAgKUEIAACQlCAEAABIShACAAAkJQgBAACSEoQAAABJCUIAAICkBCEAAEBSghAAACApQQgAAJCUIAQAAEhKEAIAACQlCAEAAJIShAAAAEkJQgAAgKQEIQAAQFKCEAAAIClBCAAAkJQgBAAASEoQAgAAJCUIAQAAkhKEAAAASQlCAACApAQhAABAUoIQAAAgKUEIAACQlCAEAABIShACAAAkJQgBAACSEoQAAABJCUIAAICkBCEAAEBSghAAACApQQgAAJCUIAQAAEhKEAIAACQlCAEAAJIShAAAAEkJQgAAgKQEIQAAQFKCEAAAIClBCAAAkJQgBAAASEoQAgAAJCUIAQAAkhKEAAAASQlCAACApAQhAABAUoIQAAAgKUEIAACQlCAEAABIShACAAAkJQgBAACSEoQAAABJCUIAAICkBCEAAEBSghAAACApQQgAAJCUIAQAAEhKEAIAACQlCAEAAJIShAAAAEkJQgAAgKQEIQAAQFKCEAAAIClBCAAAkJQgBAAASEoQAgAAJCUIAQAAkhKEAAAASQlCAACApAQhAABAUoIQAAAgKUEIAACQlCAEAABIShACAAAkJQgBAACSEoQAAABJCUIAAICkBCEAAEBSghAAACApQQgAAJBUGczuWYyIidZDAAAA6NVSFxH7W68AAACgdwtdEYQAAAAZLXQ1YqH1CgAAAPpWFrqIuLn1DAAAAPpWv9VF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';
const friends = 'data:image/png;base64,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';
const user = 'data:image/png;base64,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AL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL2m8AIA0GsKLwAAvabwAgDQawovAAC9pvACANBrCi8AAL3WJFnsOgQAAEzIYpPkQNcpAABgQuabovACANBf801N5rtOAQAAk1HmmyTf7zoGAABMRr2/Scq9XccAAICJqOXepiT3dZ0DAAAmoTTj+5paqgkvAAC9VJP7mozzza6DAADARNTyjSYlX+s6BwAATEStdyq8AAD0VzO4s8livtp1DgAAmITSLNxRklqG2/d9L8lpXQcCAIAWfXe0a+sTmqTU1PLFrtMAAEDLvpAkTZKU1C90mwUAANpVki8mhwpvLfl8t3EAAKBdtZY/SQ4V3nGz+Mlu4wAAQLvqeOGPk6Qc+rMMt+/7dpItHWYCAIC23D3atfVJyaEJb1JqKfVTXSYCAIC21NSHTjA0h/8xHpePdRMHAABaVspHD//zocKb8eLvdxIGAABaVsbjPzj874cK7/yNl/2/JH/RSSIAAGhN+fJo96V3HP6rOfJSrfnw2gcCAID21NT/ceTfjyi8TVP3rm0cAABo16Bp3n/k3+WRl2sZbt93R5Jz1i4SAAC05o7RrkuekZR6+IPmkddLrakfXOtUAADQjrL3yLKbPKbwJjX5rQf/BwAAG8s449979GePKbwHdm37syReQgEAwAZT/+hQl32ExxTeQ/7ThNMAAEDLmvce9dOjfThamNqb5N6J5gEAgPbcO5o5cPPRLhx9wnvThfuT7J5kIgAAaNHOvOuKuaNdONaRhkyN6w1JjvoQAACsH2U0mG6OOaw9ZuH9wZ5td6fkv0wmFAAAtKPU+tsP/MeLv32s68csvElSDk79epL51lMBAEA75lPKbxzvhuMW3rmbLvxGSY76bTcAAOhaSblxbtcl3zzePcctvElSmubX4ywvAADrz/4mB4473U2WUHj333DxXyV5RyuRAACgLbW+/YFdV3zrRLedsPAmyWjm4G+k5BurTwUAAK34y9Hi9JKGsksqvHnXFXMZ51+sKhIAALSklPpLh94dceJ7l75sLcPt+/5nkpevMBcAALSgfny0a+vfS0pdyt1Lm/AmSUpNM7gqyQMrTAYAAKu1vw7KG5ZadpNlFd5kdMNFd5bkXy8/FwAArF5Jfm3+PVu/upxnllV4k2TuiX/6jiSfWO5zAACwSn88d8/Bdy/3oWWc4X3Y7PZ9T62pf5rk8St5HgAAlqMk99dxnj/as/Vry3122RPeJJnbdck3U/OmlTwLAADLVurVKym7yQoLb5KMdm99X0puWunzAACwFCXZNbdz2/tX+vyKC2+SjA7efV1q+eRq1gAAgGMrn54bzPziqlZYbYRD53k/m+RJq10LAAAeVr5VxoMXzu258K7VrLKqCW/y4Hnepml+Nn6fFwCA9syVurh1tWU3aaHwJsn+Gy7+XC31NUkW21gPAICT2riUcuXc7ks/3cZirRTeJJnfue3DtebNba0HAMDJqabsmNt5yW1trTdoa6EkWfzcBz4z9aLXLCZ5WZvrAgBwkqj5lfndW9/V5pKtFt4kWfjsB/5o6kWvmUnyU22vDQBAf9XUd83v3varba/beuFNkoXPfuAPp85/TUny05NYHwCAnin17fO7tv3SJJaeSOFNkoXPfuB/Tb/wtaOUvHxSewAA0AOlvn20c9s/m9jyk1r4sJlrbttRSt6dFr8gBwBALyzWlDfN77pk1yQ3mXjhTZJTrrntknHJf01yylrsBwDAuvfAuNQrD+zcdvukN1qTwpsks1ff9qLa5L/HG9kAAE5y5VtNFi/av+vSz67Fbmt2zGBuz9bPlPHUjyellR8QBgBgQ/pcyuKL16rsJmt8rnZuz4V3jQabfrqk/OZa7gsAwDpQctPonoMXjHZe+vW13bYjw+23XllSdtbk9K4yAAAweTX5XlPqNXM7t72/i/07K7xJMrz2lrNTy+8l5aVd5gAAYFLKp1MXrxztvvSOrhJ0+lNho52Xfn10z8LLSqm/nGR/l1kAAGjV/pK8dXTPgZd0WXaTjie8Rxpec8u5Kc2eJK/oOgsAAKvyiVrqVfM7t/1510GSdVR4H1TL7DW3v7aW+vYkT+k6DQAAy1DyjZL6tq7O6h7LOiu8h1z1oVOG04tvS61vjZdVAACsd/uT/PvRwtR/yE0Xrrtjquuz8B5y6o69WxbGU29JbX4hqcOu8wAAcISSA0l+pynNv9p/w8V/1XWcY1nXhfew2as+9CN1avGfJ3md4gsA0Lm5UvM7mRr/27n3XPqXXYc5kQ1ReA87dcfeLYsLm15fS/2FJGd1nQcA4CRzb0p9b1MGv7meJ7qPtqEK70PevHd2OJq6IqX8kyQv6ToOAEDP/e+k/OfRzIGb864r5roOs1wbs/AeYdN1H3x2U5vXpZYrkjyt6zwAAD1xZ1I+MG4WfvfADZd9ueswq7HhC+/Dapm99vYXjWu9vCQXJnlW14kAADaYr9Tkw804e+f2bP1M12Ha0qPC+0jDa245t5bBq5L6ipJckGRL15kAANaZu2vqJ1PKR0sZ/P7ohovu7DrQJPS28D7azDW3/+2S+pOl1BfW5LyanFeSM7rOBQCwRr6b1C+W0nyxjvP52ow/uV7ehDZpJ03hPZrZHbc8pS6Upyc5J6U8LcnZJdlck815+L9Bkukkp3YYFQDgaH6Q5GCShSTfSXJfSe6ryX1Jvp5a70wzuLM0C3dshJ8Pm5T/D+ht61SdcBMlAAAAAElFTkSuQmCC';
const logoCeabs = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA6IAAAGZCAYAAACT2ozAAAAACXBIWXMAAC4jAAAuIwF4pT92AAAgAElEQVR4nO3dsVIcSbov8OoT46NjXButRcRxxD6BGE8ezBMIGbKHfQKhJxhkYwieYMG68qZ5ggXnRsg6wj7GGZ6AG6n5eraHAaq6uiqzqvr3iyBmdkeCpjq7u/75fZk5+/X/XtxXMHwf997sH3ueAABg/P7DcwgAAEBOgigAAABZCaIAAABkJYgCAACQlSAKAABAVoIoAAAAWQmiAAAAZCWIAgAAkJUgCgAAQFaCKAAAAFkJogAAAGQliAIAAJCVIAoAAEBWgigAAABZCaIAAABkJYgCAACQlSAKAABAVoIoAAAAWQmiAAAAZCWIAgAAkJUgCgAAQFaCKAAAAFkJogAAAGQliAIAAJCVIAoAAEBWgigAAABZCaIAAABkJYgCAACQlSAKAABAVoIoAAAAWQmiAAAAZCWIAgAAkJUgCgAAQFaCKAAAAFkJogAAAGQliAIAAJCVIAoAAEBWgigAAABZCaIAAABkJYgCAACQlSAKAABAVoIoAAAAWQmiAAAAZCWIAgAAkJUgCgAAQFaCKAAAAFkJogAAAGQliAIAAJCVIAoAAEBWgigAAABZCaIAAABkJYgCAACQlSAKAABAVoIoAAAAWQmiAAAAZCWIAgAAkJUgCgAAQFaCKAAAAFkJogAAAGQliAIAAJCVIAoAAEBWgigAAABZCaIAAABkJYgCAACQlSAKAABAVj+43M+6q6rqesCPb5N82/QLAAAAUyGIPu96783+3pAfIAAAwNhozQUAACArQRQAAICsBFEAAACyEkQBAADIShAFAAAgK0EUAACArARRAAAAshJEAQAAyEoQBQAAICtBFAAAgKwEUQAAALISRAEAAMhKEAUAACArQRQAAICsBFEAAACyEkQBAADIShAFAAAgK0EUAACArARRAAAAshJEAQAAyEoQBQAAICtBFAAAgKwEUQAAALISRAEAAMhKEAUAACArQRQAAICsBFEAAACyEkQBAADIShAFAAAgK0EUAACArARRAAAAshJEAQAAyEoQBQAAICtBFAAAgKwEUQAAALISRAEAAMhKEAUAACArQRQAAICsBFEAAACyEkQBAADIShAFAAAgK0EUAACArARRAAAAshJEAQAAyEoQBQAAICtB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';