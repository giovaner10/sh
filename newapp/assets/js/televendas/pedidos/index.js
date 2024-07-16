var tableArquivoForm;
const localeText = AG_GRID_LOCALE_PT_BR;
const gridDiv = document.querySelector("#gridClientes");
let DadosAgGrid = [];

const templateCarregamento = `
        <span class="ag-overlay-loading-center" style="border-radius: 12px; border:none; padding: 10px;">
            <div clas="mt-2">Carregando...</span>
        </span>
    `;

const templateSemDados = `<span class="ag-overlay-loading-center" style="border-radius: 12px; border:none; padding: 10px;">Sem registros</span>`;

const columnDefs = [
  { field: "razaoSocial", headerName: lang.razaoSocial, flex: 1, width: 200 },
  { field: "nome", headerName: lang.nome, flex: 1, width: 200 },
  { field: "documento", headerName: lang.documento, flex: 1, width: 200 },
  {
    field: "dataNascimento",
    headerName: lang.data_nasc,
    flex: 1,
    width: 250,
    filter: "agDateColumnFilter",
    filterParams: { comparator: comparadorDeData },
    cellRenderer: renderizarDataFormatada,
  },
  { field: "email", headerName: lang.email, flex: 1, width: 200 },
  {
    field: "acao",
    headerName: lang.acao,
    cellClass: "actions-button-cell",
    cellRenderer: renderizarAcoes,
    width: 80,
    pinned: "right",
    pdfExportOptions: { skipColumn: true },
  },
];

function comparadorDeData(filtroLocalDateAtMidnight, valorCelula) {
  const partesData = valorCelula.split("-");
  const dataCelula = new Date(partesData[0], partesData[1] - 1, partesData[2]);

  if (filtroLocalDateAtMidnight.getTime() === dataCelula.getTime()) {
    return 0;
  } else if (dataCelula < filtroLocalDateAtMidnight) {
    return -1;
  } else {
    return 1;
  }
}

function renderizarDataFormatada(params) {
  if (params.value) {
    const partesData = params.value.split("-");
    const dataFormatada = `${partesData[2]}/${partesData[1]}/${partesData[0]}`;

    return dataFormatada;
  } else {
    return "Não informado";
  }
}

function renderizarAcoes(params) {
  let opcoesHtml = `
    <div class="actions p-3 d-flex justify-content-center align-items-center">
      <a id="editar-${params.data.id}" onclick="editCliente(this, '${params.data.documento
    }', event)">
        <img src="${baseUrl + "assets/css/icon/src/pencil.svg"}" />
      </a>
    </div>
  `;
  return opcoesHtml;
}

const gridOptions = {
  localeText: localeText,
  columnDefs: columnDefs,
  rowData: [],
  defaultColDef: {
    flex: 1,
  },
  defaultColDef: {
    flex: 1,
    editable: false,
    resizable: true,
    sortable: true,
    suppressMenu: true,
  },
  getRowId: (params) => params.data.id,
  sideBar: {
    toolPanels: [
      {
        id: "columns",
        labelDefault: "Colunas",
        iconKey: "columns",
        toolPanel: "agColumnsToolPanel",
        toolPanelParams: {
          suppressRowGroups: true,
          suppressValues: true,
          suppressPivots: true,
          suppressPivotMode: true,
          suppressColumnFilter: false,
          suppressColumnSelectAll: false,
          suppressColumnExpandAll: true,
        },
      },
    ],
    defaultToolPanel: false,
  },
  overlayLoadingTemplate: templateCarregamento,
  overlayNoRowsTemplate: templateSemDados,
  pagination: true,
  paginationPageSize: 50,
};

const gridClientes = new agGrid.Grid(gridDiv, gridOptions);
preencherExportacoes(gridOptions);

document
  .querySelector("#select-quantidade-por-pagina-clientes-televendas")
  .addEventListener("change", function () {
    var selectedValue = document.querySelector(
      "#select-quantidade-por-pagina-clientes-televendas"
    ).value;
    gridOptions.api.paginationSetPageSize(Number(selectedValue));
  });

$(".btn-expandir").on("click", function (e) {
  e.preventDefault();
  expandirGrid();
});

let menuAberto = false;

function expandirGrid() {
  menuAberto = !menuAberto;

  let buttonHide = `${baseUrl}/assets/images/icon-filter-hide.svg`;
  let buttonShow = `${baseUrl}/assets/images/icon-filter-show.svg`;

  if (menuAberto) {
    $(".img-expandir").attr("src", buttonShow);
    $("#filtroBusca").hide();
    $(".menu-interno").hide();
    $("#conteudo").removeClass("col-md-9");
    $("#conteudo").addClass("col-md-12");
  } else {
    $(".img-expandir").attr("src", buttonHide);
    $("#filtroBusca").show();
    $(".menu-interno").show();
    $("#conteudo").css("margin-left", "0px");
    $("#conteudo").removeClass("col-md-12");
    $("#conteudo").addClass("col-md-9");
  }
}
async function listarClientes() {
  gridOptions.api.showLoadingOverlay();
  const url = `${BASE_URL_API_TELEVENDAS}/client/clients-by-seller/${dadosVendedor.user}`
  axiosTelevendas.get(url)
    .then(response => {
      DadosAgGrid = response.data;
      gridOptions.api.hideOverlay();
      gridOptions.api.setRowData(DadosAgGrid);
    }).catch((error) => {
      const { response } = error;
      gridOptions.api.setRowData([]);
      alert(lang.erro_inesperado);
    })
    .finally(() => {
      gridOptions.api.hideOverlay();
    });
}

$(document).ready(function () {
  listarClientes();
});

function pesquisarClientes() {
  const pesquisa = document.getElementById("pesquisarClientes").value;
  gridOptions.api.setQuickFilter(pesquisa);
}

function preencherExportacoes(gridOptions) {
  const formularioExportacoes = document.getElementById("opcoes_exportacao");
  const opcoes = ["csv", "excel", "pdf"];

  let buttonCSV = baseUrl + "/media/img/new_icons/csv.png";
  let buttonEXCEL = baseUrl + "media/img/new_icons/excel.png";
  let buttonPDF = baseUrl + "media/img/new_icons/pdf.png";

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
      exportarArquivo(opcao, gridOptions);
    });

    formularioExportacoes.appendChild(div);
  });
}

document.addEventListener("click", function (event) {
  const dropdown = document.getElementById("opcoes_exportacao");
  const dropdownButton = document.getElementById("dropdownMenuButton");

  if (!dropdown.contains(event.target) && event.target !== dropdownButton) {
    dropdown.style.display = "none";
  }
});

document
  .getElementById("dropdownMenuButton")
  .addEventListener("click", function () {
    const dropdown = document.getElementById("opcoes_exportacao");

    if (dropdown.style.display === "none" || dropdown.style.display === "") {
      dropdown.style.display = "block";
    } else {
      dropdown.style.display = "none";
    }
  });

const opcoesExportacaoItems = document.querySelectorAll(".opcao_exportacao");
opcoesExportacaoItems.forEach(function (item) {
  item.addEventListener("click", function () {
    document.getElementById("opcoes_exportacao").style.display = "none";
  });
});

function exportarArquivo(tipo, gridOptions) {
  let colunas = ["razaoSocial", "nome", "documento", "dataNascimento", "email"];

  switch (tipo) {
    case "csv":
      fileName = "RelatorioClientesTelevendas.csv";
      gridOptions.api.exportDataAsCsv({
        fileName: fileName,
        columnKeys: colunas,
      });
      break;
    case "excel":
      fileName = "RelatorioClientesTelevendas.xlsx";
      gridOptions.api.exportDataAsExcel({
        fileName: fileName,
        columnKeys: colunas,
      });
      break;
    case "pdf":
      let dadosExportacao = prepararDadosExportacaoRelatorio();

      let definicoesDocumento = getDocDefinition(
        printParams("A4"),
        gridOptions.api,
        gridOptions.columnApi,
        dadosExportacao.informacoes,
        dadosExportacao.rodape
      );
      pdfMake
        .createPdf(definicoesDocumento)
        .download(dadosExportacao.nomeArquivo);
      break;
  }
}

function prepararDadosExportacaoRelatorio() {
  let informacoes = DadosAgGrid.map((dado) => ({
    razaoSocial: dado?.razaoSocial,
    nome: dado?.nome,
    documento: dado?.cnpj ?? dado?.cpf,
    dataNascimento: dado?.dataNascimento,
    email: dado?.email,
  }));

  let rodape = `Relatório Clientes Televendas`;
  let nomeArquivo = `RelatorioClientesTelevendas.pdf`;

  return {
    informacoes,
    nomeArquivo,
    rodape,
  };
}

function printParams(pageSize) {
  return {
    PDF_HEADER_COLOR: "#ffffff",
    PDF_INNER_BORDER_COLOR: "#dde2eb",
    PDF_OUTER_BORDER_COLOR: "#babfc7",
    PDF_LOGO:
      baseUrl +
      "media/img/new_icons/" + ("shownet") + ".png",
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

function limparCampos() {
  $("#formCadastroPF").trigger("reset");
  $("#formCadastroPJ").trigger("reset");

  $('#cnhFile').val('');
  $('label[for="cnhFile"]').text(lang.CNH);
  $('#comprovanteResidenciaFile').val('');
  $('label[for="comprovanteResidenciaFile"]').text(lang.comprovante_residencia);
  $('#documentoVeiculoFile').val('');
  $('label[for="documentoVeiculoFile"]').text(lang.documento_veiculo);
}

function abrirModalCriarCliente() {
  $("#modalCadastroCliente").modal("show");
  limparCampos();
}

function toggleForm(tipoCliente) {
  let formCadastroPF = document.getElementById("formCadastroPF");
  let formCadastroPJ = document.getElementById("formCadastroPJ");

  if (tipoCliente === "pf") {
    formCadastroPF.style.display = "block";
    formCadastroPJ.style.display = "none";
  } else if (tipoCliente === "pj") {
    formCadastroPF.style.display = "none";
    formCadastroPJ.style.display = "block";
  }
}

document
  .querySelectorAll('input[name="tipoCliente"]')
  .forEach(function (radio) {
    radio.addEventListener("change", function () {
      toggleForm(this.value);
    });
  });

toggleForm(document.querySelector('input[name="tipoCliente"]:checked').value);

function getDadosForm(idForm) {
  let dadosForm = $(`#${idForm}`).serializeArray();
  let dados = {};

  for (let c in dadosForm) {
    dados[dadosForm[c].name] = dadosForm[c].value;
  }

  return dados;
}

function removerArquivo(id) {
  $(`#${id}`).val('');
  if (id === 'cnhFile') {
    $(`label[for="${id}"]`).text(lang.CNH);
  } else if (id === 'comprovanteResidenciaFile') {
    $(`label[for="${id}"]`).text(lang.comprovante_residencia);
  } else if (id === 'documentoVeiculoFile') {
    $(`label[for="${id}"]`).text(lang.documento_veiculo);
  }
}

$("#cep-principal-form-pf").on("blur", function (e) {
  if (this.value) {
    axios.get(`https://viacep.com.br/ws/${this.value}/json`)
      .then(response => {
        const endereco = response.data;
        if (!("erro" in endereco)) {
          $("#bairro-principal-form-pf").val(endereco.bairro);
          $("#cidade-principal-form-pf").val(endereco.localidade);
          $("#estado-principal-form-pf").val(endereco.uf);
          $("#rua-principal-form-pf").val(endereco.logradouro);
          $("#complemento-principal-form-pf").val(endereco.complemento);
        }
      })
      .catch(error => {
        alert("Erro ao buscar CEP");
      });
  } else {
    $("#bairro-principal-form-pf").val("");
    $("#cidade-principal-form-pf").val("");
    $("#estado-principal-form-pf").val("");
    $("#rua-principal-form-pf").val("");
    $("#complemento-principal-form-pf").val("");
  }
});

$("#cep-cobranca-form-pf").on("blur", function (e) {
  if (this.value) {
    axios.get(`https://viacep.com.br/ws/${this.value}/json`)
      .then(response => {
        const endereco = response.data;
        if (!("erro" in endereco)) {
          $("#bairro-cobranca-form-pf").val(endereco.bairro);
          $("#cidade-cobranca-form-pf").val(endereco.localidade);
          $("#estado-cobranca-form-pf").val(endereco.uf);
          $("#rua-cobranca-form-pf").val(endereco.logradouro);
          $("#complemento-cobranca-form-pf").val(endereco.complemento);
        }
      })
      .catch(error => {
        alert("Erro ao buscar CEP");
      });
  } else {
    $("#bairro-cobranca-form-pf").val("");
    $("#cidade-cobranca-form-pf").val("");
    $("#estado-cobranca-form-pf").val("");
    $("#rua-cobranca-form-pf").val("");
    $("#complemento-cobranca-form-pf").val("");
  }
});

$("#cep-entrega-form-pf").on("blur", function (e) {
  if (this.value) {
    axios.get(`https://viacep.com.br/ws/${this.value}/json`)
      .then(response => {
        const endereco = response.data;
        if (!("erro" in endereco)) {
          $("#bairro-entrega-form-pf").val(endereco.bairro);
          $("#cidade-entrega-form-pf").val(endereco.localidade);
          $("#estado-entrega-form-pf").val(endereco.uf);
          $("#rua-entrega-form-pf").val(endereco.logradouro);
          $("#complemento-entrega-form-pf").val(endereco.complemento);
        }
      })
      .catch(error => {
        alert("Erro ao buscar CEP");
      });
  } else {
    $("#bairro-entrega-form-pf").val("");
    $("#cidade-entrega-form-pf").val("");
    $("#estado-entrega-form-pf").val("");
    $("#rua-entrega-form-pf").val("");
    $("#complemento-entrega-form-pf").val("");
  }
});

$("#cep-principal-form-pj").on("blur", function (e) {
  if (this.value) {
    axios.get(`https://viacep.com.br/ws/${this.value}/json`)
      .then(response => {
        const endereco = response.data;
        if (!("erro" in endereco)) {
          $("#bairro-principal-form-pj").val(endereco.bairro);
          $("#cidade-principal-form-pj").val(endereco.localidade);
          $("#estado-principal-form-pj").val(endereco.uf);
          $("#rua-principal-form-pj").val(endereco.logradouro);
          $("#complemento-principal-form-pj").val(endereco.complemento);
        }
      })
      .catch(error => {
        alert("Erro ao buscar CEP");
      });
  } else {
    $("#bairro-principal-form-pj").val("");
    $("#cidade-principal-form-pj").val("");
    $("#estado-principal-form-pj").val("");
    $("#rua-principal-form-pj").val("");
    $("#complemento-principal-form-pj").val("");
  }
});

$("#cep-cobranca-form-pj").on("blur", function (e) {
  if (this.value) {
    axios.get(`https://viacep.com.br/ws/${this.value}/json`)
      .then(response => {
        const endereco = response.data;
        if (!("erro" in endereco)) {
          $("#bairro-cobranca-form-pj").val(endereco.bairro);
          $("#cidade-cobranca-form-pj").val(endereco.localidade);
          $("#estado-cobranca-form-pj").val(endereco.uf);
          $("#rua-cobranca-form-pj").val(endereco.logradouro);
          $("#complemento-cobranca-form-pj").val(endereco.complemento);
        }
      })
      .catch(error => {
        alert("Erro ao buscar CEP");
      });
  } else {
    $("#bairro-cobranca-form-pj").val("");
    $("#cidade-cobranca-form-pj").val("");
    $("#estado-cobranca-form-pj").val("");
    $("#rua-cobranca-form-pj").val("");
    $("#complemento-cobranca-form-pj").val("");
  }
});

$("#cep-entrega-form-pj").on("blur", function (e) {
  if (this.value) {
    axios.get(`https://viacep.com.br/ws/${this.value}/json`)
      .then(response => {
        const endereco = response.data;
        if (!("erro" in endereco)) {
          $("#bairro-entrega-form-pj").val(endereco.bairro);
          $("#cidade-entrega-form-pj").val(endereco.localidade);
          $("#estado-entrega-form-pj").val(endereco.uf);
          $("#rua-entrega-form-pj").val(endereco.logradouro);
          $("#complemento-entrega-form-pj").val(endereco.complemento);
        }
      })
      .catch(error => {
        alert("Erro ao buscar CEP");
      });
  } else {
    $("#bairro-entrega-form-pj").val("");
    $("#cidade-entrega-form-pj").val("");
    $("#estado-entrega-form-pj").val("");
    $("#rua-entrega-form-pj").val("");
    $("#complemento-entrega-form-pj").val("");
  }
});

$("input[type=file]").change(function () {
  var t = $(this).val();
  var labelText = "Arquivo : " + t.substr(12, t.length);
  $(this).prev("label").text(labelText);
});

$("#AddArquivoForm").on("click", function () {
  let arquivo = $("#filesForm")[0];
  let result = arquivo.files;
  for (i = 0; i < result.length; i++) {
    let file = result[i];
    tableArquivoForm.row
      .add([
        '<input type="file" style="visibility:hidden;" name="ListaArquivosForm" id="arquivo' +
        contArquivos +
        '"/>' +
        file.name,
        "<a class='remove btn btn-danger'>Remover <i class='fa fa-trash'></i></button>",
      ])
      .draw(false);

    var dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    $("#arquivo" + contArquivos)[0].files = dataTransfer.files;
    contArquivos++;
  }
  $("#filesForm").val("");
  $("#filesForm").prev("label").text("Selecione um Arquivo");
});

$("#tableArquivosForm tbody").on("click", ".remove", function () {
  tableArquivoForm.row($(this).parents("tr")).remove().draw();
});

var tableArquivoForm;

tableArquivoForm = $("#tableArquivosForm").DataTable({
  responsive: false,
  ordering: false,
  paging: false,
  searching: false,
  info: true,
  language: lang.datatable,
  deferRender: false,
  lengthChange: false,
});
var contArquivos = 0;

$("#AddArquivoForm").on("click", function () {
  let arquivo = $("#filesForm")[0];
  let result = arquivo.files;

  for (i = 0; i < result.length; i++) {
    let file = result[i];
    tableArquivoForm.row
      .add([
        '<input type="file" style="visibility:hidden;" name="ListaArquivosForm" id="arquivo' +
        contArquivos +
        '"/>' +
        file.name,
        "<a class='remove btn btn-danger'>Remover <i class='fa fa-trash'></i></button>",
      ])
      .draw(false);

    var dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    $("#arquivo" + contArquivos)[0].files = dataTransfer.files;
    contArquivos++;
  }

  $("#filesForm").val("");
  $("#filesForm").prev("label").text("Selecione um Arquivo");
});

$("#tableArquivosForm tbody").on("click", ".remove", function () {
  tableArquivoForm.row($(this).parents("tr")).remove().draw();
});

$("input[type=file]").change(function () {
  var t = $(this).val();
  var labelText = "Arquivo : " + t.substr(12, t.length);
  $(this).prev("label").text(labelText);
});

function formatarBody(data) {
  const pj = data.cnpj ? true : false;
  if (pj) {
    return {
      nome: data.razaoSocial,
      nomeFantasia: data.nome,
      cnpj: data.cnpj,
      email: data.email,
      idVendedor: dadosVendedor.user,
      telefone: {
        ddd: data.ddd,
        numero: data.telefone
      },
      endereco: {
        cep: data.cep,
        rua: data.rua,
        numero: data.numero,
        complemento: data.complemento,
        bairro: data.bairro,
        cidade: data.cidade,
        uf: data.uf
      }
    }
  } else {
    return {
      nome: data.nome + ' ' + data.sobrenome,
      cpf: data.cpf,
      email: data.email,
      idVendedor: dadosVendedor.user,
      telefone: {
        ddd: data.ddd,
        numero: data.telefone
      },
      endereco: {
        cep: data.cep,
        rua: data.rua,
        numero: data.numero,
        complemento: data.complemento,
        bairro: data.bairro,
        cidade: data.cidade,
        uf: data.uf
      }
    }
  }
}

$('input[type="file"]').change(function (e) {
  let langRecarregada;
  if (this.name === 'cnh') {
    langRecarregada = lang.CNH;
  } else if (this.name === 'comprovante_residencia') {
    langRecarregada = lang.comprovante_residencia;
  } else {
    langRecarregada = lang.documento_veiculo;
  }
  //limitar o tamanho do arquivo até 2MB
  if (this.files[0].size > 2097152) {
    alert("O arquivo deve ter no máximo 2MB");
    $(this).val('');
    $('label[for="' + this.id + '"]').text(langRecarregada);
    e.preventDefault();
  }
});

$("#submit-form").on("click", async function (event) {
  event.preventDefault();
  $(this).attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

  $('.feedback-alert').html('');

  let data = {};

  let form = '';

  const isFormPF = $("#formCadastroPF").is(":visible");

  if (isFormPF) {
    form = $('#formCadastroPF');
  } else {
    form = $('#formCadastroPJ');
  }

  if (form[0].checkValidity()) {
    $('#submit-form').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

    const formElements = form[0].elements;
    for (let i = 0; i < formElements.length; i++) {
      const input = formElements[i];
      if (input.name) {
        data[input.name] = input.value;
      }
    }

    const dataFormatada = formatarBody(data);

    const formData = new FormData();

    formData.append('data', JSON.stringify(dataFormatada));
    formData.append('cnh', $('#cnhFile')[0].files[0]);
    formData.append('comprovante_residencia', $('#comprovanteResidenciaFile')[0].files[0]);
    formData.append('doc_veiculo', $('#documentoVeiculoFile')[0].files[0]);

    axiosTelevendas.post(`${BASE_URL_API_TELEVENDAS}/client/create`, formData)
      .then(response => {
        alert('Cliente cadastrado com sucesso!');
        $('#modalCadastroCliente').modal('hide');
        gridOptions.api.applyTransaction({ add: [response.data] });
      })
      .catch(error => {
        alert('Erro ao cadastrar');
      })
      .finally(() => {
        $('#submit-form').attr('disabled', false).html('Cadastrar Cliente');
      });
  }
  else {
    alert("Preencha todos os campos obrigatórios!");
    $('#submit-form').attr('disabled', false).html('Cadastrar Cliente');
  }
});

