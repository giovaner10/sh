function exportarArquivo(tipo, gridOptions, titulo) {
  switch (tipo) {
    case "csv":
      fileName = `${titulo}.csv`;
      gridOptions.api.exportDataAsCsv({
        fileName: fileName,
        columnKeys: gridOptions.columnApi,
      });
      break;
    case "excel":
      fileName = `${titulo}.xlsx`;
      gridOptions.api.exportDataAsExcel({
        fileName: fileName,
        columnKeys: gridOptions.columnApi,
      });
      break;
    case "pdf":
      let definicoesDocumento = getDocDefinition(
        printParams("A4"),
        gridOptions.api,
        gridOptions.columnApi,
        "",
        titulo,
        ["nome", "cargo", "empresa", "email"]
      );
      pdfMake.createPdf(definicoesDocumento).download(`${titulo}.pdf`);
      break;
  }
}

function printParams(pageSize) {
  return {
    PDF_HEADER_COLOR: "#ffffff",
    PDF_INNER_BORDER_COLOR: "#dde2eb",
    PDF_OUTER_BORDER_COLOR: "#babfc7",
    PDF_LOGO: BaseURL + "media/img/new_icons/omnilink.png",
    PDF_HEADER_LOGO: "omnilink",
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

// Dados Gerenciamento

function preencherExportacoes(gridOptions) {
  const formularioExportacoes = document.getElementById("opcoes_exportacao");
  const opcoes = ["csv", "pdf", "excel"];

  let buttonCSV = BaseURL + "/media/img/new_icons/csv.png";
  let buttonEXCEL = BaseURL + "media/img/new_icons/excel.png";
  let buttonPDF = BaseURL + "media/img/new_icons/pdf.png";

  formularioExportacoes.innerHTML = "";

  opcoes.forEach((opcao) => {
    let button = "";
    let texto = "";
    switch (opcao) {
      case "csv":
        button = buttonCSV;
        texto = "CSV";
        margin = "-5px";
        break;
      case "excel":
        button = buttonEXCEL;
        texto = "Excel";
        margin = "0px";
        break;
      case "pdf":
        button = buttonPDF;
        texto = "PDF";
        margin = "0px";
        break;
    }

    let div = document.createElement("div");
    div.classList.add("dropdown-item");
    div.classList.add("opcao_exportacao");
    div.setAttribute("data-tipo", opcao);
    div.innerHTML = `
          <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
          <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
      `;

    div.style.height = "30px";

    div.style.marginTop = margin;

    div.style.borderRadius = "1px";

    div.style.transition = "background-color 0.3s ease";

    div.addEventListener("mouseover", function () {
      div.style.backgroundColor = "#f0f0f0";
    });

    div.addEventListener("mouseout", function () {
      div.style.backgroundColor = "";
    });

    div.style.border = "1px solid #ccc";

    div.addEventListener("click", function (event) {
      event.preventDefault();
      exportarArquivo(opcao, gridOptions, "RelatorioContatos");
    });

    formularioExportacoes.appendChild(div);
  });
}
