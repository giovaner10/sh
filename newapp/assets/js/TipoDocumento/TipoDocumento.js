const tableId = "#table";
const paginationSelect = "#paginationSelect";
const getListarRelatorioTecnicos = Router + "/getAllDocumentTypeServerSide";
const deletarDocumento = Router + "/excluirTipoDocumento";
const insertDocumento = Router + "/cadastrarTipoDocumento";
const editarDoc = Router + "/editarTipoDocumento";
var idTipoDocumento;
var statusTipoDocumento;
var idTipoDoc;

let searchOptions = {};
var agGridTable;

class TipoDocumento {
  static async modalCadastrar() {
    const documentTypeTitle = document.getElementById("nomeTipoDocumento");

    let validacao = $("#temValidacaoCheck").prop("checked") ? 1 : 0;
    let obrigatorio = $("#obrigatorioCheck").prop("checked") ? 1 : 0;
    let nomeTipoDocumento = $("#nomeTipoDocumento").val();

    if (!nomeTipoDocumento || $("#nomeTipoDocumento").val().length === 0) {
      TipoDocumento.exibirAlerta("warning", "Atenção!", "Você precisa inserir um título para o tipo de documento!");

      $("btnSalvarTipoDocumento").blur();
      return;
    }

    const requestParams = {
      nome: documentTypeTitle.value,
      obrigatorio: obrigatorio,
      temValidacao: validacao,
    };

    TipoDocumento.ShowLoadingScreen();

    $.ajax({
      url: insertDocumento,
      method: "POST",
      data: requestParams,
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          TipoDocumento.exibirAlerta("success", "Sucesso!", "Documento cadastrado com sucesso!");
          agGridTable.refreshAgGrid(searchOptions);
        } else if (response.status === 400) {
          TipoDocumento.exibirAlerta("warning", "Atenção!", `Não foi possível cadastrar: ${response.resultado.mensagem}`);
        }
        $("#cadastroTipoDocumento").modal("hide");
        TipoDocumento.HideLoadingScreen();
      },
      error: function () {
        TipoDocumento.exibirAlerta("warning", "Atenção!", `Não foi possível cadastrar o tipo de Documento, entre em contato com o suporte técnico.`);
        TipoDocumento.HideLoadingScreen();
        $("#cadastroTipoDocumento").modal("hide");
      },
    });
  }
  static async enviarTipoDocumentoEditado() {
    TipoDocumento.ShowLoadingScreen();

    const documentTypeTitle = document.getElementById("nomeTipoDocumento");
    let idDocumento = idTipoDoc;
    let validacao = $("#temValidacaoCheckEdit").prop("checked") ? 1 : 0;
    let obrigatorio = $("#obrigatorioCheckEdit").prop("checked") ? 1 : 0;
    let nomeTipoDocumento = $("#nomeTipoDocumentoEdit").val();

    if (!nomeTipoDocumento || $("#nomeTipoDocumentoEdit").val().length === 0) {
      TipoDocumento.exibirAlerta("warning", "Atenção!", "Você precisa inserir um título para o tipo de documento!");
      $("btnSalvarTipoDocumentoEdit").blur();
      return;
    }

    const requestParams = {
      id: idDocumento,
      nome: nomeTipoDocumento,
      obrigatorio: obrigatorio,
      temValidacao: validacao
    };

    $.ajax({
      url: editarDoc,
      method: "POST",
      data: requestParams,
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          TipoDocumento.exibirAlerta("success", "Sucesso!", "Documento alterado com sucesso!");
          agGridTable.refreshAgGrid(searchOptions);
        } else if (response.status === 404) {
          TipoDocumento.exibirAlerta("warning", "Atenção!", `Já existe um tipo de documento com este nome!`);
        } else if (response.status === 400) {
          TipoDocumento.exibirAlerta("warning", "Atenção!", `Não foi possível cadastrar: ${response.resultado.mensagem}`);
        }
        $("#edicaoTipoDocumento").modal("hide");
        TipoDocumento.HideLoadingScreen();
      },
      error: function () {
        TipoDocumento.exibirAlerta("warning", "Atenção!", "Não foi possível cadastrar o tipo de Documento, entre em contato com o suporte técnico.");
        TipoDocumento.HideLoadingScreen();
        $("#edicaoTipoDocumento").modal("hide");
      },
    });
  }

  static async editarTipoDocumento(id, nome, obrigatorio, temValidacao) {
    idTipoDoc = id;
    $("#nomeTipoDocumentoEdit").val(nome);
    if (obrigatorio == 1) {
      $("#obrigatorioCheckEdit").prop("checked", true)
    } else {
      $("#obrigatorioCheckEdit").prop("unchecked")
    }

    if (temValidacao == 1) {
      $("#temValidacaoCheckEdit").prop("checked", true)
    } else {
      $("#temValidacaoCheckEdit").prop("unchecked")
    }
    $("#edicaoTipoDocumento").modal("show");
  }

  static async excluirModal(id, status) {
    idTipoDocumento = id;
    statusTipoDocumento = status;
    $("#modalConfirmacaoMudancaStatus").modal("show");
  }

  static abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);
    var posDropdown = dropdown.height() + 10;
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
      if (posDropdown < (posButtonTop - posBordaTabelaTop)) {
        dropdown.css('top', `-${25}px`);
      } else {
        let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
        dropdown.css('top', `-${(posDropdown - 60) - (diferenca)}px`);
      }
    }
  }

  static exibirAlerta(icon, title, text) {
    Swal.fire({
      position: 'top-start',
      icon: icon,
      title: title,
      text: text,
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    });
  }

  static ShowLoadingScreen() {
    $("#loading").show();
  }

  static HideLoadingScreen() {
    $("#loading").hide();
  }

  static async inativarDocumento(id, status) {
    TipoDocumento.ShowLoadingScreen();
    $.ajax({
      url: deletarDocumento,
      type: "POST",
      data: {
        id: id,
        status: status,
      },
      dataType: "json",
      success: function (response) {
        if (response["status"] == 200) {
          TipoDocumento.exibirAlerta("success", "Sucesso!", response["resultado"]["mensagem"]);
        } else if (response["status"] == 400) {
          TipoDocumento.exibirAlerta("warning", "Atenção!", response["resultado"]["mensagem"]);
        } else if (response["status"] == 404) {
          TipoDocumento.exibirAlerta("warning", "Atenção!", response["resultado"]["mensagem"]);
        } else {
          TipoDocumento.exibirAlerta("warning", "Atenção!", "Erro ao alterar o status do tipo de documento, contate o suporte técnico.");
        }
        TipoDocumento.HideLoadingScreen();
        agGridTable.refreshAgGrid({});
      },
      error: function (error) {
        TipoDocumento.exibirAlerta("warning", "Atenção!", "Erro na solicitação ao servidor");
        TipoDocumento.HideLoadingScreen();
      },
    });
  }

  static async Management() {
    $(".search-button").click(async function (e) {
      e.preventDefault();
      searchOptions = {
        nomeDocumento: $("#nomeDocumento").val() || null,
      };

      $(".search-button").blur();

      if (!$("#nomeDocumento").val()) {
        TipoDocumento.exibirAlerta("warning", "Atenção!", "Você precisa digitar um nome para filtrar!");
        return;
      }
      agGridTable.refreshAgGrid(searchOptions);
    });

    $(".clear-search-button").click(async function (e) {
      e.preventDefault();

      $("#formBusca").each(function () {
        this.reset();
      });

      $("#nomeDocumento").val(null).trigger("change");

      searchOptions = {};
      
      agGridTable.refreshAgGrid(searchOptions);
    });

    $("#btnCadastroTipoDocumento").click(async function (e) {
      e.preventDefault();

      $("#cadastroTipoDocumento").modal("show");
    });

    agGridTable = new AgGridTable(
      tableId,
      paginationSelect,
      getListarRelatorioTecnicos,
      true,
      (key, item) => {
        if (item == null || item === "") {
          item = "Não informado";
        }

        if (key === "dataCadastro") {
          var date = new Date(item);
          item = date.toLocaleDateString();
        }

        return item;
      }
    );

    agGridTable.updateColumnDefs([
      {
        headerName: "ID",
        field: "id",
        filter: true,
        width: 100,
      },
      {
        headerName: "Nome",
        field: "nome",
        flex: 0.6,
        minWidth: 80,
      },
      {
        headerName: "Obrigatório",
        field: "obrigatorio",
        filter: true,
        width: 125,
        cellRenderer: (item) => {
          const obrigatorio = item.data.obrigatorio;

          return `${obrigatorio == 1 ? "Sim" : "Não"}`;
        },
      },
      {
        headerName: "Tem Validação",
        field: "temValidacao",
        width: 145,
        cellRenderer: (item) => {
          const temValidacao = item.data.temValidacao;

          return `${temValidacao == 1 ? "Sim" : "Não"}`;
        },
      },
      {
        headerName: "Data de Criação",
        field: "dataCadastro",
        width: 200,
      },
      {
        headerName: "Status",
        field: "status",
        width: 80,
        chartDataType: "category",
        cellRenderer: function (options) {
          let data = options.data["status"];
          if (data == "Ativo") {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
          } else {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `;
          }
        },
      },
      {
        headerName: "Ação",
        pinned: "right",
        width: 80,
        cellClass: "actions-button-cell",
        sortable: false,
        cellRenderer: function (options) {
          var firstRandom = Math.random() * 10;
          var secRandom = Math.random() * 10;
          var thirdRandom = Math.random() * 10;

          var varAleatorioIdBotao =
            (firstRandom * secRandom).toFixed(0) +
            "-" +
            (thirdRandom * firstRandom).toFixed(0) +
            "-" +
            (secRandom * thirdRandom).toFixed(0);

          let data = options.data;
          let tableId = "tableDocumentos";
          let dropdownId = "dropdown-menu-tipo-documento" + data.id + varAleatorioIdBotao;
          let buttonId = "dropdownMenuButtonTipoDocumento_" + data.id + varAleatorioIdBotao;
          let acao = data.status === "Ativo" ? "Inativar" : "Ativar";
          let status = data.status === "Ativo" ? 0 : 1;

          return `
                    <div class="dropdown dropdown-table" style="position: relative;">
                    <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                    <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"
            }" alt="Ações">
                </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-regras" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:TipoDocumento.excluirModal(${data.id}, ${status})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">${acao}</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:TipoDocumento.editarTipoDocumento('${data.id}', '${data.nome}', '${data.obrigatorio}', '${data.temValidacao}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                            </div>
                        </div>
                    </div>`;
        },
      }
    ]);

    preencherExportacoes(
      agGridTable.gridOptions,
      "Relatório de Tipos de Documento"
    );
  }

  static init() {
    $(document).ready(() => {
      $(".btn-expandir").on("click", function (e) {
        e.preventDefault();
        expandirGrid();
      });

      let menuAberto = false;

      function expandirGrid() {
        menuAberto = !menuAberto;
        let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
        let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

        if (menuAberto) {
          $(".img-expandir").attr("src", buttonShow);
          $(".col-md-3").fadeOut(250, function () {
            $("#conteudo").removeClass("col-md-9").addClass("col-md-12");
          });
        } else {
          $(".img-expandir").attr("src", buttonHide);
          $("#conteudo").removeClass("col-md-12").addClass("col-md-9");
          setTimeout(() => {
            $(".col-md-3").fadeIn(250);
          }, 510);
        }
      }

      $("#btnConfirmarAcaoUsuario").click(function () {
        var id = idTipoDocumento;
        var status = statusTipoDocumento;
        TipoDocumento.inativarDocumento(id, status);
        $("#modalConfirmacaoMudancaStatus").modal("hide");
      });

      $("#btnSalvarTipoDocumento").click(function () {
        var id = idTipoDocumento;
        var status = statusTipoDocumento;
        TipoDocumento.modalCadastrar(id, status);
        $("#modalConfirmacaoMudancaStatus").modal("hide");
      });

      $("#btnSalvarTipoDocumentoEdit").click(function () {
        TipoDocumento.enviarTipoDocumentoEditado();
      });

      $("#edicaoTipoDocumento").on('hide.bs.modal', function () {
        $("#formEditarDocumento").trigger('reset')
      })

      $("#cadastroTipoDocumento").on('hide.bs.modal', function () {
        $("#formAlertasEmail").trigger('reset')
      })

      $(document).on('shown.bs.dropdown', '.dropdown-table', function () {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = "table";
        TipoDocumento.abrirDropdown(dropdownId, buttonId, tableId);
      });

      TipoDocumento.Management();
    });
  }
}

TipoDocumento.init();
