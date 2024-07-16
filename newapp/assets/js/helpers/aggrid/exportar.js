async function gerarRelatorio(tipo, nomeArquivo, titulo = "", colunasASeremRemovidas = "") {
  let columnKeys = gridOptions.columnApi;

  if (tipo !== "pdf" && colunasASeremRemovidas) {
    const arrayDeColunasASeremRemovidas = colunasASeremRemovidas.split(",");
    columnKeys = gridOptions.columnApi.getAllColumns();
    columnKeys = columnKeys.filter((column) => !arrayDeColunasASeremRemovidas.includes(column.colId));
  }

  switch (tipo) {
    case "csv":
      gridOptions.api.exportDataAsCsv({
        sheetName: nomeArquivo,
        fileName: `${nomeArquivo}.csv`,
        columnKeys,
      });
      break;
    case "excel":
      gridOptions.api.exportDataAsExcel({
        sheetName: nomeArquivo,
        fileName: `${nomeArquivo}.xlsx`,
        columnKeys,
      });
      break;
    case "pdf":
      let definicoesDocumento = getDocDefinition(
        printParams("A4"),
        gridOptions.api,
        columnKeys,
        nomeArquivo,
        titulo,
      );
      pdfMake
        .createPdf(definicoesDocumento)
        .download(`${nomeArquivo}.pdf`);
      break;
  }
}


function printParams(pageSize) {
	return {
		PDF_HEADER_COLOR: "#ffffff",
		PDF_INNER_BORDER_COLOR: "#dde2eb",
		PDF_OUTER_BORDER_COLOR: "#babfc7",
		PDF_LOGO: BASE_URL + "media/img/new_icons/shownet.png",
		PDF_HEADER_LOGO: "shownet",
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
	};
}
