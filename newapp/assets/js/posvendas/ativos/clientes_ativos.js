$(document).ready(async function () {
  let clienteAtual = 0;
  let clientesCarregados = [];
  let idCarregados = [];

  $("#pesquisacliente").click(async function (event) {
    event.preventDefault();
    const id = $("#clientesSelect").val();

    if (clienteAtual == id) {
      showAlert("warning", "Dados Já carregados para esse cliente!");
      return;
    }

    if (id) {
      clienteAtual = id;
      showAlert("success", "Buscando informações do cliente.");
      let clienteVerificado = addClienteResponse(clienteAtual);

      if (clienteVerificado) {
        preencherCamposPerfil(clienteVerificado);
        return;
      }

      await buscarClienteCompleto(clienteAtual);
      return;
    }

    showAlert("warning", "Por favor, selecione um cliente.");
  });

  $("#BtnLimparFiltro").click(async function (event) {
    event.preventDefault();
    clienteAtual = 0;
    $(".pos-venda-container-sec").hide();
    $("#clientesSelect").val(null).trigger("change");
  });

  async function buscarClienteCompleto(id) {
    pesquisarAcoesBotoes();
    $(".pos-venda-container-sec").hide();
    await $.ajax({
      url: Router + "/buscar_cliente_completo",
      type: "POST",
      dataType: "json",
      data: {
        id: id,
      },
      success: function (response) {
        preencherCamposPerfil(response.resultado);
      },
      error: function (xhr, status, error) {
        showAlert("warning", "Falha ao carregar dados do cliente.");
      },
    });
  }

  function addClienteResponse(id) {
    if (idCarregados.includes(id)) {
      return clientesCarregados.find((clientesArray) => clientesArray.id == id);
    }
    idCarregados.push(id);
  }

  function preencherCamposPerfil(arr) {
    $(".pos-venda-container-sec").hide();
    cliente_selecionado_atual = arr;
    loadTabContent("tabAtividadesServico");

    $.each(arr, function (key, value) {
      var campo = $("#" + key);
      if (campo.length) {
        campo.text(value ? value : "(Não informado)");
      }
    });

    $.each(arr.clienteAuxiliarModel, function (key, value) {
      var campo = $("#" + key);
      if (campo.length) {
        campo.text(value ? value : "(Não informado)");
      }
    });

    clientesCarregados.push(arr);
    fimPesquisa();

    $(".pos-venda-container-sec").show();
  }

  function pesquisarAcoesBotoes() {
    $("#pesquisacliente").html(
      '<i class="fa fa-spinner fa-spin"></i> Pesquisando'
    );
    $("#pesquisacliente").prop("disabled", true);
    $("#BtnLimparFiltro").prop("disabled", true);
  }

  function fimPesquisa() {
    $("#pesquisacliente").html('<i class="fa fa-search"></i> Pesquisar');
    $("#pesquisacliente").prop("disabled", false);
    $("#BtnLimparFiltro").prop("disabled", false);
    $(".pos-venda-container-sec").show();
  }

  await carregar_clientes_ativos_select();
});
