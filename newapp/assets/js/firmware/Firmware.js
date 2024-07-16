var localeText = AG_GRID_LOCALE_PT_BR;
var addTecnologias = [];
let idTecnologiaEditar;
let idTecnologiaDetalhe;
let idModeloEditar;
let idRegraEdicao;
let idFirmwareEdicao;
let idAssociacao;
let firmwareAssociacao;
let intervalReferences = [];

$(document).ready(function () {
    disabledButtons();
    preencherSelectsHardware();
    showLoadingPesquisarButton();
    getClientesSelect2();
    preencherProgramacao([])
    $("#idAssociacao").select2({})

    $('#addRegra').on('hide.bs.modal', function () {
        getClientesSelect2();
        preencherProgramacao([])
    })

    $('#associarFirmware').on('hide.bs.modal', function () {
        $("#firmwareAssociadoExistente").val("");
    })

    $('#visualizarEnvio').on('hide.bs.modal', function () {
        getClientesSelect2();
    })

    //ATUALIZAÇÃO AUTOMÁTICA
    var dropdown = $('#opcoes_atualizar');

    $('#dropdownMenuButtonAtualizar').click(function () {
        dropdown.toggle();
    });

    $(document).click(function (event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonAtualizar') {
            dropdown.hide();
        }
    });

    $('#stopInterval').on('click', function () {
        stopIntervals();
        $('#dropdownMenuButtonLabel').text('Atualizar');
    })
    
    $('#30seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => atualizarAgGridHistorico(), 1000 * 30);
        intervalReferences.push(myInterval);
        $('#dropdownMenuButtonLabel').text('30 Segundos');

    });

    $('#90seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => atualizarAgGridHistorico(), 1000 * 90);
        intervalReferences.push(myInterval);
        $('#dropdownMenuButtonLabel').text('90 Segundos');
    });

    $('#180seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => atualizarAgGridHistorico(), 1000 * 180);
        intervalReferences.push(myInterval);
        $('#dropdownMenuButtonLabel').text('180 Segundos');
    });

    const dataInicialBusca = document.getElementById('dataInicialBusca');
    const dataFinalBusca = document.getElementById('dataFinalBusca');

    dataInicialBusca.addEventListener('input', function() {
        this.blur();
    });

    dataFinalBusca.addEventListener('input', function() {
        this.blur();
    });


    $("#menu-tecnologias-cadastrados").addClass("#menu-historico-envio");
    $(".card-dados-tecnologias").hide();
    $(".card-dados-firmware").hide();
    $(".card-dados-regras").hide();
    $(".nomeTecnologia").hide();
    $(".nomeRegra").hide();
    $(".descricaoFirmwareBusca").hide();
    $(".modeloFirmwareBusca").hide();
    atualizarAgGridHistorico();
            

    $("#menu-firmware-cadastrados").on("click", function () {
        if (!$(this).hasClass("selected")) {
            disabledButtons();
            showLoadingPesquisarButton();
            $(this).addClass("selected");
            $("#menu-tecnologias-cadastradas").removeClass("selected");
            $("#menu-regras-envio").removeClass("selected");
            $("#menu-historico-envio").removeClass("selected");
            $(".card-dados-firmware").show();
            $(".card-dados-tecnologias").hide();
            $(".card-dados-regras").hide();
            $(".card-historico-envio").hide();
            $(".nomeTecnologia").hide();
            $(".nomeRegra").hide();
            $(".clienteBusca").hide();
            $(".serial").hide();
            $(".dataInicioBusca").hide();
            $(".dataFinalBusca").hide();
            $(".descricaoFirmwareBusca").show();
            $(".modeloFirmwareBusca").show();
            atualizarAgGridFirmware();
            $("#formBusca").trigger('reset');
            $("#clienteBusca").val(null).trigger("change");
            stopIntervals();
            $('#dropdownMenuButtonLabel').text('Atualizar');
        }
    });

    $("#menu-tecnologias-cadastradas").on("click", function () {
        if (!$(this).hasClass("selected")) {
            disabledButtons();
            showLoadingPesquisarButton();
            $(this).addClass("selected");
            $("#menu-firmware-cadastrados").removeClass("selected");
            $("#menu-regras-envio").removeClass("selected");
            $("#menu-historico-envio").removeClass("selected");
            $(".card-dados-tecnologias").show();
            $(".card-dados-firmware").hide();
            $(".card-dados-regras").hide();
            $(".card-historico-envio").hide();
            $(".nomeRegra").hide();
            $(".nomeTecnologia").show();
            $(".clienteBusca").hide();
            $(".serial").hide();
            $(".dataInicioBusca").hide();
            $(".dataFinalBusca").hide();
            $(".descricaoFirmwareBusca").hide();
            $(".modeloFirmwareBusca").hide();
            atualizarAgGridTecnologias();
            $("#formBusca").trigger('reset');
            $("#clienteBusca").val(null).trigger("change");
            stopIntervals();
            $('#dropdownMenuButtonLabel').text('Atualizar');
        }
    });

    $("#menu-regras-envio").on("click", function () {
        if (!$(this).hasClass("selected")) {
            disabledButtons();
            showLoadingPesquisarButton();
            $(this).addClass("selected");
            $("#menu-firmware-cadastrados").removeClass("selected");
            $("#menu-tecnologias-cadastradas").removeClass("selected");
            $("#menu-historico-envio").removeClass("selected");
            $(".card-dados-tecnologias").hide();
            $(".card-dados-firmware").hide();
            $(".card-dados-regras").show();
            $(".card-historico-envio").hide();
            $(".nomeTecnologia").hide();
            $(".clienteBusca").show();
            $(".nomeRegra").show();
            $(".serial").hide();
            $(".dataInicioBusca").hide();
            $(".dataFinalBusca").hide();
            $(".descricaoFirmwareBusca").hide();
            $(".modeloFirmwareBusca").hide();
            atualizarAgGridRegras([]);
            $("#formBusca").trigger('reset');
            $("#clienteBusca").val(null).trigger("change");
            stopIntervals();
            $('#dropdownMenuButtonLabel').text('Atualizar');
        }
    });


    $("#menu-historico-envio").on("click", function () {
        if (!$(this).hasClass("selected")) {
            disabledButtons();
            showLoadingPesquisarButton();
            $(this).addClass("selected");
            $("#menu-firmware-cadastrados").removeClass("selected");
            $("#menu-tecnologias-cadastradas").removeClass("selected");
            $("#menu-regras-envio").removeClass("selected");
            $(".card-dados-tecnologias").hide();
            $(".card-dados-firmware").hide();
            $(".card-dados-regras").hide();
            $(".card-historico-envio").show();
            $(".nomeTecnologia").hide();
            $(".nomeRegra").hide();
            $(".clienteBusca").show();
            $(".serial").show();
            $(".dataInicioBusca").show();
            $(".dataFinalBusca").show();
            $(".descricaoFirmwareBusca").hide();
            $(".modeloFirmwareBusca").hide();
            atualizarAgGridHistorico();
            $("#formBusca").trigger('reset');
            $("#clienteBusca").val(null).trigger("change");
            stopIntervals();
            $('#dropdownMenuButtonLabel').text('Atualizar');
        }
    });

    $("#nomeTecnologiaCadastro").on("input", function () {
        var inputVal = $(this).val().trim();
        $("#botao-adicionar-tecnologia-arquivo").prop(
            "disabled",
            inputVal === null || inputVal === ""
        );
    });

    $("#nomeTecnologiaCadastro").keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
        }
    });

    $("#formBusca").on("submit", function (e) {
        e.preventDefault();
    
        if ($("#menu-firmware-cadastrados").hasClass("selected")) {
            let descricaoFirmware = $("#descricaoFirmwareBusca").val();
            let versao = $("#modeloFirmwareBusca").val();
            if (descricaoFirmware != "" || versao != "") {
                disabledButtons();
                showLoadingPesquisarButton();
                atualizarAgGridFirmware({
                    descricao: descricaoFirmware,
                    versao: versao,
                });
            } else {
                $("#BtnPesquisar").blur();
                showAlert("warning", "É necessário informar Descrição ou Versão.");
            }
    
        } else if ($("#menu-tecnologias-cadastradas").hasClass("selected")) {
    
            let nomeTecnologia = $("#nomeTecnologia").val();
            if (nomeTecnologia != "" && nomeTecnologia != null) {
                disabledButtons();
                showLoadingPesquisarButton();
                atualizarAgGridTecnologias({ nome: nomeTecnologia });
            } else {
                showAlert("warning", "É necessário informar o nome do Hardware.");
            }
    
        } else if ($("#menu-regras-envio").hasClass("selected")) {
    
            let cliente = $("#clienteBusca").val();
            let descricao = $("#nomeRegra").val();
            if (cliente != "" || descricao != '') {
                disabledButtons();
                showLoadingPesquisarButton();
                atualizarAgGridRegras({ idCliente: cliente, descricao: descricao });
            } else {
                showAlert("warning", "É necessário informar Cliente ou Descrição da Exeção.");
            }
    
        } else if ($("#menu-historico-envio").hasClass("selected")) {
    
            if ($("#clienteBusca, #serial, #dataInicialBusca, #dataFinalBusca").filter(function () { return this.value != ''; }).length > 0) {
                let cliente = $("#clienteBusca option:selected").text();
                let serial = $("#serial").val();
                let dataInicial = $("#dataInicialBusca").val();
                let dataFinal = $("#dataFinalBusca").val();
                let dataInicialFormatada = dataInicial ? dataInicial.split('T')[0].split('-').reverse().join('/') + ' ' + dataInicial.split('T')[1].substring(0, 5) : '';
                let dataFinalFormatada = dataFinal ? dataFinal.split('T')[0].split('-').reverse().join('/') + ' ' + dataFinal.split('T')[1].substring(0, 5) : '';
    
                if (dataInicial != '' || dataFinal != '') {
                    if (dataInicial && dataFinal) {
                        let dataInicioObj = new Date(dataInicial);
                        let dataFinalObj = new Date(dataFinal);
                        let dataAtual = new Date();
    
                        if (dataFinalObj > dataAtual) {
                            showAlert("warning", "Data final não pode ser maior que a data atual.");
                            $("#BtnPesquisar").blur();
                            return;
                        }
    
                        if (dataInicioObj > dataFinalObj) {
                            showAlert("warning", "Data inicial não pode ser maior que a data final.");
                            $("#BtnPesquisar").blur();
                            return;
                        }
                    } else {
                        showAlert("warning", "É necessário informar a data inicial e a data final.");
                        $("#BtnPesquisar").blur();
                        return;
                    }
                }
    
                disabledButtons();
                showLoadingPesquisarButton();
                atualizarAgGridHistorico({ cliente: cliente, serial: serial, dataHoraEnvioInicio: dataInicialFormatada, dataHoraEnvioFim: dataFinalFormatada });
            } else {
                showAlert("warning", "É necessário fornecer os dados para busca.");
            }
        }
        $("#BtnPesquisar").blur();
    });
    


    $("#BtnLimpar").on("click", function (e) {
        e.preventDefault();
        if ($("#menu-tecnologias-cadastradas").hasClass("selected")) {
            if ($("#nomeTecnologia").val() != "") {
                disabledButtons();
                showLoadingPesquisarButton();
                $("#nomeTecnologia").val("");
                atualizarAgGridTecnologias();
            }
        } else if ($("#menu-firmware-cadastrados").hasClass("selected")) {
            if (
                $("#descricaoFirmwareBusca").val() != "" ||
                $("#modeloFirmwareBusca").val() != ""
            ) {
                disabledButtons();
                showLoadingPesquisarButton();
                $("#descricaoFirmwareBusca").val("");
                $("#modeloFirmwareBusca").val("");
                atualizarAgGridFirmware([]);
            }
        } else if ($("#menu-regras-envio").hasClass("selected")) {
            if (
                $("#clienteBusca").val() != "" ||
                $("#nomeRegra").val() != ""
            ) {
                disabledButtons();
                showLoadingPesquisarButton();
                $("#clienteBusca").val("").trigger('change');
                $("#nomeRegra").val("");
                atualizarAgGridRegras();
            }
        } else if ($("#menu-historico-envio").hasClass("selected")) {
            if ($("#clienteBusca").val() != "" || $("#serial").val() != "" || $("#dataInicialBusca").val() != "" || $("#dataFinalBusca").val() != "") {
                disabledButtons();
                showLoadingPesquisarButton();
                $("#clienteBusca").val("").trigger('change');
                $("#serial").val("");
                $("#dataInicialBusca").val("");
                $("#dataFinalBusca").val("");
                atualizarAgGridHistorico();
            }
        }
    });

    $(".btn-expandir").on("click", function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $("#BtnAdicionarFirmware").on("click", function () {
        let flag = $("#titleAddFirmware").text().includes("Cadastrar") ? true : false;

        if (flag) {
            if ($('#inputAssociacaoFirmwareContainer').length > 0) {
                $('#inputAssociacaoFirmwareContainer').remove();
            }
        }

        $("#addFirmware").show();
    });

    $("#addFirmware").on("hidden.bs.modal", function (e) {
        $("#titleAddFirmware").text("Cadastrar Firmware");
        $("#dadosFirmwareModal").text("Dados para Cadastro");
        $(this).find("input, textarea, select").val("");
        $("#hardwareFirmware").val("").trigger("change");
        $("#modeloFirmware")
            .next(".select2-container")
            .find(".select2-selection__placeholder")
            .text("Selecione o Hardware...");
    });

    searchOptions = {
        descricao: $("#descricaoFirmwareBusca").val(),
        modelo: $("#modeloFirmwareBusca").val(),
    };

    $("#btnSalvarCadastro").click(function () {
        let flag = $("#titleAddFirmware").text().includes("Cadastrar") ? true : false;
        let dataLiberacao = new Date($("#liberacaoFirmware").val());
        let dataAtual = new Date();
    
        if (dataLiberacao > dataAtual) {
            showAlert("warning", "Data de Liberação não pode ser maior que a data e hora atual.");
            $("#btnSalvarCadastro").blur();
            return;
        }
    
        if (!validarArquivos()) {
            return false;
        }
    
        let arquivoFirmware = $("#arquivoFirmware").prop("files")[0];
        let releaseNotes = $("#releaseNotes").prop("files")[0];
    
        let DadosFirmware = {
            versao: $("#versaoFirmware").val(),
            liberacao: $("#liberacaoFirmware").val(),
            descricao: $("#descricaoFirmware").val(),
            idHardware: $("#hardwareFirmware").val(),
            idModelo: $("#modeloFirmware").val(),
            nomeArquivoFirmware: arquivoFirmware ? arquivoFirmware.name : null,
            arquivoFirmware: null,
            nomeReleaseNotes: releaseNotes ? releaseNotes.name : null,
            releaseNotes: null,
        };
    
        if (!validarCampos(DadosFirmware)) {
            $("#btnSalvarCadastro").blur();
            return;
        }
    
        if ($("#arquivoFirmware").val() == null || $("#arquivoFirmware").val() == ''){
            showAlert("warning", "O Arquivo Firmware não foi anexado.");
            $("#btnSalvarCadastro").blur();
            return;
        }
    
        if ($("#releaseNotes").val() == null || $("#releaseNotes").val() == '' ) {
            showAlert("warning", "A Release Notes não foi anexada.");
            $("#btnSalvarCadastro").blur();
            return;
        }
    
        convertFileToBase64(arquivoFirmware, function (base64Arquivo) {
            DadosFirmware.arquivoFirmware = base64Arquivo;
    
            convertFileToBase64(releaseNotes, function (base64ReleaseNotes) {
                DadosFirmware.releaseNotes = base64ReleaseNotes;
    
                if (flag) {
                    cadastrarFirmware(DadosFirmware);
                } else {
                    DadosFirmware.id = idFirmwareEdicao;
                    atualizarFirmware(DadosFirmware);
                }
            });
        });
    
        $("#btnSalvarCadastro").blur();
    });

  $("#btnSalvarCadastroTecnologia").click(function () {
    if ($("#titleAddTecnologia").text().includes("Editar")) {
      alterarDadosTecnologia($("#nomeTecnologiaCadastro").val());
    }
    if ($("#titleAddTecnologia").text().includes("Cadastrar")) {
      if (addTecnologias.length > 0) {
        cadastrarTecnologiaEmLote(addTecnologias);
      } else {
        showAlert("warning", "É necessário inserir ao menos um Hardware na tabela")
        $("#btnSalvarCadastroTecnologia").blur();
      }
    }
  });

  $("#btnCadastroTecnologia").on("click", function () {
    preencherTecnologiasLote([]);
    $("#titleAddTecnologia").html("Cadastrar Hardware");
    $("#nomeTecnologiaCadastro")
      .val("")
      .attr("placeholder", "Digite o nome do Hardware");
    $("#botao-adicionar-tecnologia-arquivo").show();
    $("#limparTabelaItens").show();
    $("#botao-adicionar-tecnologia-arquivo").prop("disabled", true);
    $("#addTecnologia").modal("show");
    $("#divNomeTecnologia").removeClass("col-md-12");
    $("#divNomeTecnologia").addClass("col-md-8");
  });

    $("#botao-adicionar-tecnologia-arquivo").on("click", function () {
        adicionarTecnologia($("#nomeTecnologiaCadastro").val());
        $("#nomeTecnologiaCadastro").val("");
    });

    $("#addTecnologia").on("hidden.bs.modal", function () {
        addTecnologias = [];
    });

    $("#limparTabelaItens").on("click", function () {
        addTecnologias = [];
        preencherTecnologiasLote(addTecnologias);
    });

    $("#btnCadastroModelo").on("click", function () {
        $("#addModelo").modal("show");
        $("#titleAddModelo").text("Cadastrar Modelo");
        $("#descricaoModelo").val("");
        $("#inputNomeModelo").addClass("col-md-6");
        $("#inputNomeModelo").removeClass("col-md-12");
        $(".hardware").show();
        preencherSelectsHardware();
    });

    $("#hardwareFirmware")
        .off()
        .on("change", function () {
            var selectedValue = $(this).val();

            if (selectedValue !== "") {
                getModelosSelect2(selectedValue);
            } else {
                $("#modeloFirmware").prop("disabled", true).val("").trigger("change");
                $("#modeloFirmware")
                    .next(".select2-container")
                    .find(".select2-selection__placeholder")
                    .text("Selecione o Hardware...");
            }
        });

        $("#formAddModelo").on("submit", function (e) {
            e.preventDefault();
            let flag = $("#titleAddModelo").text().includes("Cadastrar");
            if (flag) {
                if ($("#tecnologiaModelo").val() === "") {
                    showAlert("error","Por favor, selecione uma opção para o campo 'Hardware'");
                    $("#btnSalvarCadastroModelo").blur();
                    return; 
                }
                let body = {
                    nome: $("#descricaoModelo").val(),
                    idTecnologia: $("#tecnologiaModelo").val()
                };
                cadastrarModelo(body);
            } else {
                let body = {
                    nome: $("#descricaoModelo").val(),
                    id: idModeloEditar
                };
                editarModeloCadastrado(body);
                $("#btnSalvarCadastroModelo").blur();
            }
        });        


    $("#btnAddRegras").on("click", function () {
        $("#addRegra").modal("show");
        $("#clienteRegra").val('').trigger('change');
        $("#titleAddRegra").text("Cadastrar Exceção");
        $("#dadosRegraModal").text("Dados Para Cadastrados");
        $("#descricaoRegra").val('');
    });

    $("#diaAtualizacao").select2({
        placeholder: "Selecione os dias",
        data: [
            { id: "1", text: "Segunda-Feira" },
            { id: "2", text: "Terça-Feira" },
            { id: "3", text: "Quarta-Feira" },
            { id: "4", text: "Quinta-Feira" },
            { id: "5", text: "Sexta-Feira" },
            { id: "6", text: "Sábado" },
            { id: "7", text: "Domingo" },
        ],
    });

    $("#formAssociarFirmware").on("submit", function (e) {
        e.preventDefault();
        let flag = $("#titleAssociacao").text().includes("Associar");
        if (flag) {
            let flag2 = $("#firmwareAssociadoExistente").val() === '';
            if (!flag2) {
                showAlert("warning", "É necessário dessasociar o firmware para prosseguir com a ação.");
                $("#btnSalvarAssociacao").blur();
                return;
            }

            let idAssociar = $("#idAssociacao").val();
            associarFirmware(idAssociar);
            $("#btnSalvarAssociacao").blur();
        }
    });


  $("#btnSalvarAssociacao").on("click", function (e) {
    if ($("#idAssociacao").val() == "" || $("#idAssociacao").val() == null) {
      showAlert("warning", "Por favor, selecione o firmware para associar.");
      $("#btnSalvarAssociacao").blur();
      return;
    }
  });

    $("#addRegra").on("hidden.bs.modal", function () {
        $("#enviarAtualizacao, #atualizarDia, #atualizarHora").prop("checked", false);

        $("#diaAtualizacao").val("1").trigger("change");

        $("#formAddRegras").trigger("reset");

        $("#clienteRegra").val("").trigger("change");

        $(".diaAtualizacao, .horaInicio, .horaFim, .diaHoraInicio, .diaHoraFim, .btnTabela, #tagHr, .wrapperProgramacao").hide().prop("required", false);

        $("#enviarAtualizacao, #atualizarDia, #atualizarHora").prop("disabled", false);
        

    });


    let enviarAtualizacao = $("#enviarAtualizacao");
    let atualizarDia = $("#atualizarDia");
    let atualizarHora = $("#atualizarHora");
    $(".diaAtualizacao, .horaInicio, .horaFim, .diaHoraInicio, .diaHoraFim, .btnTabela, #tagHr, .wrapperProgramacao").hide().val("").prop("required", false);

    enviarAtualizacao.change(function () {

        if ($(this).prop("checked")) {

            atualizarDia.prop("disabled", true).prop("checked", false);
            atualizarHora.prop("disabled", true).prop("checked", false);
            $(".diaAtualizacao, .horaInicio, .horaFim, .diaHoraInicio, .diaHoraFim, .btnTabela, #tagHr, .wrapperProgramacao").hide().val("").prop("required", false);

        } else {

            atualizarDia.prop("disabled", false).prop("checked", false);
            atualizarHora.prop("disabled", false).prop("checked", false);
            $(".diaAtualizacao, .horaInicio, .horaFim, .diaHoraInicio, .diaHoraFim, .btnTabela, #tagHr, .wrapperProgramacao").hide().val("").prop("required", false);
        }
    });

    atualizarDia.change(function () {
        if ($(this).prop("checked")) {
            atualizarHora.prop("disabled", true).prop("checked", false);
            enviarAtualizacao.prop("disabled", true).prop("checked", false);
            $(".horaInicio, .horaFim").hide().val("").prop("required", false);
            $(".diaAtualizacao, .diaHoraInicio, .diaHoraFim, .btnTabela, #tagHr, .wrapperProgramacao").show();
            $(".diaAtualizacao").prop("required", true);

        } else {
            atualizarHora.prop("disabled", false).prop("checked", false);
            enviarAtualizacao.prop("disabled", false).prop("checked", false);
            $(".diaAtualizacao, .horaInicio, .horaFim, .diaHoraInicio, .diaHoraFim, .btnTabela, #tagHr, .wrapperProgramacao").hide().val("").prop("required", false);
        }
    });

    atualizarHora.change(function () {
        if ($(this).prop("checked")) {
            atualizarDia.prop("disabled", true).prop("checked", false);
            enviarAtualizacao.prop("disabled", true).prop("checked", false);
            $(".diaAtualizacao, .diaHoraInicio, .diaHoraFim, .btnTabela, .wrapperProgramacao").hide().val("").prop("required", false);
            $(".horaInicio, .horaFim, #tagHr").show().prop("required", true);
        } else {
            atualizarDia.prop("disabled", false).prop("checked", false);
            enviarAtualizacao.prop("disabled", false).prop("checked", false);
            $(".diaAtualizacao, .horaInicio, .horaFim, .diaHoraInicio, .diaHoraFim, .btnTabela, #tagHr, .wrapperProgramacao").hide().val("").prop("required", false);

        }
    });

    $("#btnAddTabela").on("click", function () {
        let dia = $('#diaAtualizacao').val();
        let horaInicio = $("#diaAtualizarHoraInicio").val() || "00:00";
        let horaFim = $("#diaAtualizarHoraFim").val() || "23:59";

        let dados = {
            index: agGridProgamacao.gridOptions.api.getModel().getRowCount() + 1,
            diaEnvio: dia,
            horarioInicio: horaInicio,
            horarioFinal: horaFim,
            status: "Ativo"
        }

        if (dia != null && dia != "") {
            if (horaInicio < horaFim || (horaInicio === "00:00" && horaFim === "00:00")) {
                let dadosExistem = agGridProgamacao.gridOptions.api.getModel().rowsToDisplay.some(row => {
                    return row.data.diaEnvio === dia && row.data.horarioInicio === horaInicio && row.data.horarioFinal === horaFim;
                });
                if (!dadosExistem) {
                    agGridProgamacao.gridOptions.api.applyTransaction({ add: [dados] });
                } else {
                    showAlert("error", "Já existe uma programação cadastrada com esses horários para este dia.")
                }
            } else {
                showAlert("error", "O horário de início não pode ser maior ou igual ao horário final.")
            }
        } else {
            showAlert("error", "É necessário informar o dia.")
        }
    });

    $("#btnLimparTabela").on("click", function () {
        Swal.fire({
            title: "Atenção!",
            text: "Deseja realmente limpar a tabela?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim",
            cancelButtonText: "Não"    
          }).then((result) => {
            if (result.isConfirmed) {
                $("#diaAtualizacao").val("1")
                $("#diaAtualizarHoraInicio").val("")
                $("#diaAtualizarHoraFim").val("")
                preencherProgramacao([]);
            }})
    });

    $("#btnSalvarRegra").click(function (e) {
        e.preventDefault();
        if($("#clienteRegra").val() == '' || $("#clienteRegra").val() == null){
            showAlert("error", "O Campo Cliente é obrigatório.")
            $("#btnSalvarRegra").blur()
            return
        }
        if($("#descricaoRegra").val() == '' || $("#descricaoRegra").val() == null){
            showAlert("error", "O Campo Descrição é obrigatório.")
            $("#btnSalvarRegra").blur()
            return
        }
        Swal.fire({
            title: "Atenção!",
            text: "Os dados anteriores serão sobreescritos, deseja realmente salvar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar",
            cancelButtonText: "Cancelar"    
        }).then((result) => {
            if (result.isConfirmed) {

                let flag = $("#titleAddRegra").text().includes("Cadastrar") ? true : false

                let dadosProgramacao = agGridProgamacao.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
                    return {
                        diaEnvio: dado.data.diaEnvio,
                        horarioInicio: dado.data.horarioInicio,
                        horarioFinal: dado.data.horarioFinal,
                    };
                });

                let dados = {
                    id_cliente: $("#clienteRegra").val(),
                    descricao: $("#descricaoRegra").val(),
                    desabilitaAtualizacao: $("#enviarAtualizacao").prop('checked') ? 1 : 0,
                    habilitaDiaProgramado: $("#atualizarDia").prop('checked') ? 1 : 0,
                    habilitaHorarioProgramado: $("#atualizarHora").prop('checked') ? 1 : 0,
                    horarioInicio: $("#horaAtualizacao").val() || "",
                    horarioFim: $("#horaAtualizacaoFim").val() || "",
                    regraProgramada: dadosProgramacao
                }
                
                if (!flag) {
                    dados.id = idRegraEdicao;
                    if ($("#enviarAtualizacao").prop('checked') || $("#atualizarDia").prop('checked') || $("#atualizarHora").prop('checked')) {
                        if ($("#atualizarHora").prop('checked')) {
                            if (($("#horaAtualizacao").val() != "" && $("#horaAtualizacaoFim").val() != "")) {
                                dados.regraProgramada = []
                                alterarRegra(dados)
                                $("#btnSalvarRegra").blur()
                                return
                            } else {
                                showAlert("error", "É necessário informar as horas de inicio e fim dos eventos.")
                                $("#btnSalvarRegra").blur()
                                return
                            }
                        } else if ($("#atualizarDia").prop('checked')) {
                            if (dadosProgramacao.length > 0) {
                                dados.horarioInicio = "";
                                dados.horarioFim = "";
                                alterarRegra(dados)
                                $("#btnSalvarRegra").blur()
                                return
                            } else {
                                showAlert("warning", "É necessário Adicionar ao menos um evento na tabela.")
                                $("#btnSalvarRegra").blur()
                                return
                            }
                        }
                        dados.horarioInicio = "";
                        dados.horarioFim = "";
                        dados.regraProgramada = []
                        alterarRegra(dados)
                    } else {
                        showAlert("warning", "É necessário marcar o tipo de exceção a ser cadastrada.")
                        $("#btnSalvarRegra").blur()
                        return
                    }
                } else {
                    if ($("#enviarAtualizacao").prop('checked') || $("#atualizarDia").prop('checked') || $("#atualizarHora").prop('checked')) {
                        if ($("#atualizarHora").prop('checked')) {
                            if (($("#horaAtualizacao").val() != "" && $("#horaAtualizacaoFim").val() != "")) {
                                dados.regraProgramada = []
                                cadastrarRegra(dados)
                                $("#btnSalvarRegra").blur()
                                return
                            } else {
                                showAlert("warning", "É necessário informar as horas de inicio e fim dos eventos.")
                                $("#btnSalvarRegra").blur()
                                return
                            }
                        } else if ($("#atualizarDia").prop('checked')) {
                            if (dadosProgramacao.length > 0) {
                                dados.horarioInicio = "";
                                dados.horarioFim = "";
                                dadosProgramacao=[]
                                cadastrarRegra(dados)
                                $("#btnSalvarRegra").blur()
                                return
                            } else {
                                showAlert("warning", "É necessário Adicionar ao menos um evento na tabela.")
                                $("#btnSalvarRegra").blur()
                                return
                            }
                        }
                        dados.horarioInicio = "";
                        dados.horarioFim = "";
                        dadosProgramacao = []
                        cadastrarRegra(dados)

                    } else {
                        showAlert("warning", "É necessário marcar o tipo de exceção a ser cadastrada.")
                        $("#btnSalvarRegra").blur()
                        return
                    }
                }
            }
        })
    })

});

//REQUISIÇÕES

function cadastrarFirmware(dados) {
    disabledButtons();
    $('#btnSalvarCadastro').html('<i class="fa fa-spinner fa-spin"></i> Carregando...')

    let route = Router + '/cadastrarFirmware';
    $.ajax({
        url: route,
        type: 'POST',
        data: dados,
        dataType: "json",
        success: function (response) {
            if (response.status == 200) {
                showAlert("success", "Firmware cadastrado com sucesso!")
                atualizarAgGridFirmware();
                $('#addFirmware').modal('hide');
            } else if (response.status == 400) {
                let errorMessage = response.resultado && response.resultado.errors ? response.resultado.errors.join('\n') : response.resultado.mensagem;
                showAlert("error", errorMessage)
            } else if (response.status == 404) {
                showAlert("error", response.resultado.mensagem)
            } else {
                showAlert("error", "Erro ao cadastrar firmware, contate o suporte técnico.")
                $('#addFirmware').modal('hide');
            }
            enableButtons();
            $('#btnSalvarCadastro').html('Salvar');

        },
        error: function () {
            showAlert("error", "Erro na solicitação ao servidor.")
            enableButtons();
            $('#btnSalvarCadastro').html('Salvar');

        }
    })
}

function atualizarFirmware(dados) {
    disabledButtons();
    showLoadingPesquisarButton();
    $('#btnSalvarCadastro').html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
    let route = Router + '/atualizarFirmware';
    $.ajax({
        url: route,
        type: 'POST',
        data: dados,
        dataType: "json",
        success: function (response) {
            if (response.status == 200) {
                showAlert("success", "Firmware Atualizado com sucesso!")
                let descricaoFirmware = $('#descricaoFirmwareBusca').val()
                let versao = $('#modeloFirmwareBusca').val()
                atualizarAgGridFirmware({ descricao: descricaoFirmware, versao: versao })
                $('#addFirmware').modal('hide');
            } else if (response.status == 400) {
                let errorMessage = response.resultado && response.resultado.errors ? response.resultado.errors.join('\n') : response.resultado.mensagem;
                showAlert("error", errorMessage)
            } else if (response.status == 404) {
                showAlert("error", response.resultado.mensagem)
            } else {
                showAlert("error", "Erro ao Editar firmware, contate o suporte técnico.")
                $('#addFirmware').modal('hide');
            }
            $('#btnSalvarCadastro').html('Salvar');
            enableButtons();
            resetPesquisarButton();
        },
        error: function () {
            showAlert("error", "Erro na solicitação ao servidor")
            enableButtons();
            resetPesquisarButton();
            $('#btnSalvarCadastro').html('Salvar');

        }
    })
}

function excluirFirmware(id, status) {
        Swal.fire({
            title: "Atenção!",
            text: "Deseja realmente alterar o status ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar",
            cancelButtonText: "Cancelar"    
          }).then((result) => {
            if (result.isConfirmed) {
                ShowLoadingScreen();
                disabledButtons();
                showLoadingPesquisarButton();
                let route = Router + '/deletarFirmware'
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response['status'] == 200) {
                        showAlert("success", response['resultado']['mensagem'])
                        } else if (response['status'] == 400) {
                            showAlert("error", response['resultado']['mensagem'])
                        } else if (response['status'] == 404) {
                            showAlert("error", response['resultado']['mensagem'])
                        } else {
                            showAlert("error", "Erro ao atualizar o firmware, contate o suporte técnico.")
                        }
                        enableButtons();
                        resetPesquisarButton();
                        let descricaoFirmware = $('#descricaoFirmwareBusca').val()
                        let versao = $('#modeloFirmwareBusca').val()
                        atualizarAgGridFirmware({ descricao: descricaoFirmware, versao: versao })
                        HideLoadingScreen()
                    },
                    error: function (error) {
                        showAlert("error", "Erro na solicitação ao servidor")
                        enableButtons();
                        resetPesquisarButton();
                        HideLoadingScreen()
                    },
            
                });
            }            
          });
}

function getModelosSelect2(idTecnologia) {
    $('#modeloFirmware').prop('disabled', true);

    $("#modeloFirmware").select2({
        placeholder: "Buscando modelos...",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });


    let route = Router + '/modeloSeletc2';

    return $.ajax({
        cache: false,
        url: route,
        type: "POST",
        data: { idTecnologia: idTecnologia },
        dataType: "json",
        success: function (data) {
            if (data.status == 200) {
                $("#modeloFirmware").prop('disabled', false);
                $('#modeloFirmware').empty().append('<option value="">Selecione o Modelo</option>');

                data.resultado.forEach(item => {
                    $('#modeloFirmware').append(`<option value="${item.id}">${item.nome}</option>`);
                });

                $("#modeloFirmware").select2({
                    placeholder: "Selecione o Modelo...",
                    allowClear: true,
                    language: "pt-BR",
                    width: 'resolve',
                    height: '32px',
                });
            } else {
                $("#modeloFirmware").prop('disabled', true);
                $('#modeloFirmware').empty().append(`<option value="" selected>Não há modelos cadastrados.</option>`);
                $("#modeloFirmware").select2({
                    placeholder: "Não há modelos cadastrados.",
                    allowClear: true,
                    language: "pt-BR",
                    width: 'resolve',
                    height: '32px',
                });
                HideLoadingScreen();
            }
        }
    });
}

async function getClientesSelect3(id) {
    ShowLoadingScreen();
    await $.ajax({
        url: Router + '/clientesSelect2',
        type: "POST",
        dataType: "json",
        data: {
            id: id
        },
        success: function (data) {
            $("#clienteRegra, #clienteEnvio").empty();

            $("#clienteRegra, #clienteEnvio").append('<option value="">Selecione um cliente</option>');

            data.resultado.clientesDTO.forEach(function (item) {
                var option = $('<option>');
                option.val(item.id);
                option.text(`${item.nome} (${item.cnpj ? item.cnpj : (item.cpf || '')})`);
                $("#clienteRegra, #clienteEnvio").append(option);
            });
            HideLoadingScreen();
        },
        error: function (xhr, status, error) {
            showAlert("error", "Erro na solicitação ao servidor")
            HideLoadingScreen();
        }
    });
}

function getClientesSelect2(id) {
    $("#clienteBusca, #clienteRegra, #clienteEnvio").select2({
        placeholder: "Digite o nome do cliente",
        allowClear: true,
        language: {
            inputTooShort: function () {
                return "Digite pelo menos 4 caracteres";
            },
            noResults: function () {
                return "Cliente não encontrado";
            },
            searching: function () {
                return "Buscando...";
            }
        },
        width: 'resolve',
        height: '32px',
        minimumInputLength: 4,
        ajax: {
            cache: false,
            url: Router + '/clientesSelect2',
            type: "POST",
            delay: 1000,
            data: function (params) {
                var query = {
                    itemInicio: 1,
                    itemFim: 100,
                };

                if (id) {
                    query.id = id;
                }

                if (params.term && params.term.length >= 4) {
                    query.searchTerm = params.term;
                }

                return query;
            },
            dataType: "json",
            beforeSend: function() {
                $("#clienteBusca, #clienteRegra, #clienteEnvio").each(function() {
                    $(this).empty().append('<option></option>').trigger('change');
                });
            },
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: data.resultado.clientesDTO.map(item => {
                            return {
                                id: item.id,
                                text: `${item.nome} (${(item.cnpj !== null && item.cnpj !== '') ? item.cnpj : (item.cpf === null || item.cpf === '') ? '' : item.cpf})`
                            };
                        }),
                        pagination: {
                            more: false
                        }
                    };
                } else {
                    return {
                        results: []
                    };
                }
            }
        }
    });
}




function cadastrarModelo(dados) {
    disabledButtons();
    let route = Router + '/cadastrarModelo'

    body = {
        nome: dados.nome,
        idTecnologia: dados.idTecnologia,
    }

    $.ajax({
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        success: function (response) {
            if (response['status'] == 200) {
                showAlert("success", response['resultado']['mensagem'])
            } else if (response['status'] == 400) {
                showAlert("error", response['resultado']['mensagem'])
            } else if (response['status'] == 404) {
                showAlert("error", response['resultado']['mensagem'])
            } else {
                showAlert("error", "Erro ao cadastrar modelo, contate o suporte técnico.")
            }
            $('#descricaoModelo').val("");
            $('#tecnologiaModelo').val("");
            $('#addModelo').modal("hide");

            enableButtons();
            atualizarAgGridDetalhes();
        },
        error: function (error) {
            showAlert("error", "Erro na solicitação ao servidor")
            $('#descricaoModelo').val("");
            $('#tecnologiaModelo').val("");
            enableButtons();
        },

    });
}

function editarModeloCadastrado(dados) {
    disabledButtons();
    let route = Router + '/editarModeloCadastrado'

    body = {
        id: dados.id,
        nome: dados.nome
    }

    $.ajax({
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        success: function (response) {
            if (response['status'] == 200) {
                showAlert("success", response['resultado']['mensagem'])
            } else if (response['status'] == 400) {
                showAlert("error", response['resultado']['mensagem'])
            } else if (response['status'] == 404) {
                showAlert("error", response['resultado']['mensagem'])
            } else {
                showAlert("error", "Erro ao Editar modelo, contate o suporte técnico.")
            }
            $('#addModelo').modal('hide');
            enableButtons();
            atualizarAgGridDetalhes({ idTecnologia: idTecnologiaDetalhe });
        },
        error: function (error) {
            showAlert("error", "Erro na solicitação ao servidor.")
            enableButtons();
        },

    });
}

function excluirTecnologia(id, status){
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente alterar o status ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen()
            disabledButtons();
            showLoadingPesquisarButton();
            let route = Router + '/deletarTecnologia'
            $.ajax({
                url: route,
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                dataType: 'json',
                success: function (response) {
                    if (response['status'] == 200) {
                        showAlert("success", response['resultado']['mensagem'])
                    } else if (response['status'] == 400) {
                        showAlert("error", response['resultado']['mensagem'])
                    } else if(response['status'] == 404) {
                        showAlert("error", response['resultado']['mensagem'])
                    }else{
                        showAlert("error", "Erro ao atualizar o Hardware, contate o suporte técnico.")
                    }
                    HideLoadingScreen();
                    enableButtons();
                    resetPesquisarButton();
                    atualizarAgGridTecnologias();
                    preencherSelectsHardware();
                },
                error: function (error) {
                    HideLoadingScreen();
                    showAlert("error", "Erro na solicitação ao servidor.")
                    enableButtons();
                    resetPesquisarButton();
                },
                
            });
        }}
    )
}

function editarTecnologia(id, nomeTecnologia) {
    idTecnologiaEditar = id;
    $('#titleAddTecnologia').html(`Editar Hardware - ${nomeTecnologia.length > 20 ? nomeTecnologia.substring(0, 20) + '...' : nomeTecnologia}`);
    $('#nomeTecnologiaCadastro').val(nomeTecnologia);
    $('#divNomeTecnologia').removeClass('col-md-8');
    $('#divNomeTecnologia').addClass('col-md-12');
    $('#botao-adicionar-tecnologia-arquivo').hide();
    $('#limparTabelaItens').hide();
    $('#addTecnologia').modal('show');
    stopAgGRIDTecnologiasLote()
    $('#nomeTecnologiaCadastro').on('keydown', function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

}

function alterarDadosTecnologia(nome) {
    disabledButtons();
        showLoadingPesquisarButton();
        let route = Router + '/editarTecnologia'
   
        $.ajax({
            url: route,
            type: 'POST',
            data: {
                id: idTecnologiaEditar,
                nome: nome
            },
            dataType: 'json',
            success: function (response) {
                if (response['status'] == 200) {
                    showAlert("success", response['resultado']['mensagem'])
                    atualizarAgGridTecnologias();
                    $('#addTecnologia').modal('hide');
                } else if (response['status'] == 400) {
                    let errorMessage = response['resultado'] && response['resultado']['errors'] ? response['resultado']['errors'].join('\n'): response['resultado']['mensagem'];                    
                    showAlert("error", errorMessage)
                } else if(response['status'] == 404) {
                    showAlert("error", response['resultado']['mensagem'])
                }else{
                    showAlert("error", "Erro ao Editar Hardware, contate o suporte técnico.")
                    $('#addTecnologia').modal('hide');
                }
                enableButtons();
                resetPesquisarButton();
            },
            error: function (error) {
                showAlert("error", "Erro na solicitação ao servidor")
                enableButtons();
                resetPesquisarButton();
            },
            
        });
}

function preencherSelectsHardware() {

    $("#tecnologiaModelo, #hardwareFirmware").prop('disabled', true).html('<option value="" selected>Carregando Hardwares...</option>');

    let route = Router + '/tecnologiasSelect2';

    $.ajax({
        cache: false,
        url: route,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                $("#tecnologiaModelo, #hardwareFirmware").prop('disabled', false);

                $('#tecnologiaModelo, #hardwareFirmware').empty().append('<option value="">Selecione o Hardware</option>');

                data.resultado.forEach(item => {
                    $('#tecnologiaModelo, #hardwareFirmware').append(`<option value="${item.id}">${item.nome}</option>`);
                });

                $("#tecnologiaModelo, #hardwareFirmware").select2({
                    placeholder: "Selecione o Hardware...",
                    allowClear: true,
                    language: "pt-BR",
                    width: 'resolve',
                    height: '32px',
                    width: '100%',

                });
            } else {
                $('#tecnologiaModelo, #hardwareFirmware').empty();
                $('#tecnologiaModelo, #hardwareFirmware').append(`<option value="" selected>Hardwares não encontradas.</option>`);
            }
        }
    });
}

function editarModelo(idModelo, nomeModelo) {
    idModeloEditar = idModelo;
    $('#titleAddModelo').html(`Editar Modelo - ${nomeModelo.length > 20 ? nomeModelo.substring(0, 20) + '...' : nomeModelo}`);
    $('#descricaoModelo').val(nomeModelo);
    $('#inputNomeModelo').removeClass('col-md-6');
    $('#inputNomeModelo').addClass('col-md-12');
    $('.hardware').hide();
    $('#addModelo').modal('show');
    $('#descricaoModelo').on('keydown.blockEnter');
}

function cadastrarTecnologiaEmLote(dados) {
    disabledButtons();
    showLoadingPesquisarButton();

        let route = Router + '/cadastrarTecnologiaLote'
   
        $.ajax({
            url: route,
            type: 'POST',
            data: JSON.stringify(dados),
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    showAlert("success", data.resultado.mensagem)
                    atualizarAgGridTecnologias();
                    preencherSelectsHardware();
                    $('#addTecnologia').modal('hide');
                    addTecnologias = []
                    preencherTecnologiasLote(addTecnologias);
                } else if (data.status == 400) {
                    const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                    showAlert("error", errorMessage)
                } else if(data.status == 404) {
                    showAlert("error", data.resultado.mensagem)
                }else{
                    showAlert("error", "Erro ao Editar Hardware, contate o suporte técnico.")
                    $('#addTecnologia').modal('hide');
                }
            },
            error: function (error) {
                showAlert("error", "Erro na solicitação ao servidor")
                enableButtons();
                resetPesquisarButton();
            },
            
        });
}

function cadastrarRegra(dados) {
    disabledButtons();
    ShowLoadingScreen();

    let route = Router + '/cadastrarRegra'

    $.ajax({
        url: route,
        type: 'POST',
        data: JSON.stringify(dados),
        contentType: 'application/json',
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                showAlert("success", "Cadastro de exceção realizado com sucesso!")
                regraProgramada = []
                preencherProgramacao(regraProgramada);
            } else if (data.status == 400) {
                const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                showAlert("error", errorMessage)
            } else if (data.status == 404) {
                showAlert("error", data.resultado.mensagem)
            } else {
                showAlert("error", "Erro ao cadastrar exceção, contate o suporte técnico.")
            }
            $('#addRegra').modal('hide');
            enableButtons();
            HideLoadingScreen();
            atualizarAgGridRegras()

        },
        error: function () {
            showAlert("error", "Erro na solicitação ao servidor. Contate o suporte técnico.");
            enableButtons();
            resetPesquisarButton();
            HideLoadingScreen();
        },

    });

}

function alterarRegra(dados) {
    disabledButtons();
    ShowLoadingScreen();

    let route = Router + '/atualizarRegra'

    $.ajax({
        url: route,
        type: 'POST',
        data: JSON.stringify(dados),
        contentType: 'application/json',
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                showAlert("success", "Alteração de exceção realizado com sucesso!")
                regraProgramada = []
                preencherProgramacao(regraProgramada);
            } else if (data.status == 400) {
                const errorMessage = data.resultado.errors ? data.resultado.errors[0] : data.resultado.mensagem;
                showAlert("error", errorMessage)
            } else if (data.status == 404) {
                showAlert("error", data.resultado.mensagem)
            } else {
                showAlert("error", "Erro ao Alterar Exceção, contate o suporte técnico.");
            }
            $('#addRegra').modal('hide');
            enableButtons();
            HideLoadingScreen();
            atualizarAgGridRegras()
        },
        error: function () {
            showAlert("error", "Erro na solicitação ao servidor. Contate o suporte técnico.");
            enableButtons();
            resetPesquisarButton();
            HideLoadingScreen();

        },

    });

}


function editarRegras(id) {
    ShowLoadingScreen()
    return new Promise((resolve, reject) => {

        let route = Router + '/buscarRegrasCadastradasById';
        $.ajax({
            url: route,
            method: 'POST',
            data: { id: id },
            success: function (data) {
                HideLoadingScreen()
                resolve(data)
            },
            error: function () {
                HideLoadingScreen()
                showAlert("error", "Erro ao receber dados, contate o suporte técnico");
                reject()
            }
        });
    })

}

async function preencherModalEdicao(id) {
    idRegraEdicao = id;
    let data = await editarRegras(id)
    data = JSON.parse(data)
    await getClientesSelect3(data.resultado.idCliente);
    $("#clienteRegra").val(data.resultado.idCliente).trigger('change');
    $("#addRegra").modal("show");
    $("#titleAddRegra").text("Editar Exceção");
    $("#dadosRegraModal").text("Dados Cadastrados");
    $("#descricaoRegra").val(data.resultado.descricao);

    if (data.resultado.desabilitaAtualizacao === 1) {
        $("#enviarAtualizacao").prop('checked', true).trigger('change');
    } else if (data.resultado.habilitaDiaProgramado === 1) {
        $("#atualizarDia").prop('checked', true).trigger('change');;
        let programacao = (data.resultado.programacao.map((item, index) => ({
            ...item,
            index: index + 1,
            diaEnvio: item.diaEnvio,
            horarioInicio: item.horarioInicioEnvio,
            horarioFinal: item.horarioFinalEnvio
        })));

        preencherProgramacao(programacao)

        $("#addRegra").modal("show");
    } else if (data.resultado.habilitaHorarioProgramado === 1) {
        $("#atualizarHora").prop('checked', true).trigger('change');;
        $("#horaAtualizacao").val(data.resultado.horarioInicioEnvio)
        $("#horaAtualizacaoFim").val(data.resultado.horarioFinalEnvio)
    }
}

function buscarDados(id) {
    ShowLoadingScreen()
    return new Promise((resolve, reject) => {

        let route = Router + '/detalhesEnvio';
        $.ajax({
            url: route,
            method: 'POST',
            data: { id: id },
            success: function (data) {
                HideLoadingScreen()
                resolve(data)


            },
            error: function () {
                HideLoadingScreen()
                reject("Erro ao receber dados, contate o suporte técnico")
            }
        });
    })

}

async function visualizarHistorico(id) {
    let data = await buscarDados(id)
    data = JSON.parse(data)
    var retornoApi = JSON.parse(data.resultado[0].retornoApi);
    var idComando = retornoApi.data[0].ID;

    let cliente = await getClientesSelect3(data.resultado[0].idCliente)

    $("#clienteEnvio").prop('disabled', true).val(data.resultado[0].idCliente).trigger('change');
    $("#serialEnvio").prop('disabled', true).val(data.resultado[0].serial);
    $("#statusEnvioModal").prop('disabled', true).val(data.resultado[0].statusEnvio);
    $("#statusModal").prop('disabled', true).val(data.resultado[0].status);
    $("#horaEnvio").prop('disabled', true).val(data.resultado[0].horaEnvio)
    $("#idComando").prop('disabled', true).val(idComando)
    $("#visualizarEnvio").modal("show");
}

function excluirRegras(id, status) {
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente alterar o status ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            disabledButtons();
            showLoadingPesquisarButton();
        
                let route = Router + '/deletarRegra'
           
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response['status'] == 200) {
                            showAlert("success", response['resultado']['mensagem'])
                        } else if (response['status'] == 400) {
                            showAlert("error", response['resultado']['mensagem']);
                        } else if(response['status'] == 404) {
                            showAlert("error", response['resultado']['mensagem'])
                        }else{
                            showAlert("error", "Erro ao atualizar o Hardware, contate o suporte técnico.");
                        }
                        enableButtons();
                        HideLoadingScreen();
                        resetPesquisarButton();
                        atualizarAgGridRegras();
                        
                    },
                    error: function (error) {
                        showAlert("error", "Erro na solicitação ao servidor");
                        enableButtons();
                        resetPesquisarButton();
                        HideLoadingScreen();
                    },
                });
        }}
    )
    
}

// AG-GRID

var agGridFirmware;
function atualizarAgGridFirmware(options) {
    stopAgGRIDFirmware();
    disabledButtons();
    showLoadingPesquisarButton();
    function geServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + "/buscarFirmwareCadastrados";
                $.ajax({
                    cache: false,
                    url: route,
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        descricao: options ? options.descricao : "",
                        versao: options ? options.versao : "",
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                    if (chave === 'dataLiberacao') {
                                        let dataHora = dados[i][chave].split(' ');
                                        let data = dataHora[0].split('-').reverse().join('/');
                                        let hora = dataHora[1].substring(0, 5);
                                        dados[i][chave] = `${data} ${hora}`;
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();

                        } else {
                            showAlert("error", "Erro na solicitação ao servidor");
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();

                        }
                        enableButtons(),
                            resetPesquisarButton()
                    },
                    error: function (error) {
                        showAlert("error", "Erro na solicitação ao servidor");
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0
                        });
                        gridOptions.api.showNoRowsOverlay();
                        enableButtons(),
                            resetPesquisarButton()
                    },
                });

            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID Firmware',
                field: 'id',
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Versão',
                field: 'versao',
                width: 150,
                chartDataType: 'series'
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                chartDataType: 'series',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Hardware',
                field: 'nomeHardware',
                width: 180,
                suppressSizeToFit: true,
                chartDataType: 'category'
            },
            {
                headerName: 'Modelo',
                field: 'nomeModelo',
                width: 180,
                chartDataType: 'series'
            },
            {
                headerName: 'Data Liberação',
                field: 'dataLiberacao',
                chartDataType: 'series',
                width: 180,
                suppressSizeToFit: true
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 80,
                chartDataType: 'category',
                cellRenderer: function (options) {
                    let data = options.data['status'];
                    if (data == 'Ativo') {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `
                    }
                }
            },
            {
                headerName: 'Release Notes',
                field: 'fileReleaseNotesName',
                flex: 1,
                minWidth: 80,
                chartDataType: 'category',
                cellRenderer: function (options) {
                    let data = options.data['file_release_notes_name'];
                    if (data != '') {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `
                    }
                }
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
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
                    let tableId = "tableFirmware";
                    let dropdownId = "dropdown-menu-firmware" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButtonFirmware_" + data.id + varAleatorioIdBotao;
                    let acao = data.status === "Ativo" ? "Inativar" : "Ativar";
                    let status = data.status === "Ativo" ? 0 : 1;
            
                    let dropdownItems = '';
            
                    if (data.status === "Ativo") {
                        dropdownItems += `
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:editarFirmware('${data.id}','${data.idHardware}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:downloadRelease('${data.id}')" style="cursor: pointer; color: black;">Baixar Release</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:downloadArquivoFirmware('${data.id}')" style="cursor: pointer; color: black;">Baixar Firmware</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirModalAssociacao('${data.id}', '${data.versao}')" style="cursor: pointer; color: black;">Associar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:desassociarFirmware('${data.id}')" style="cursor: pointer; color: black;">Desassociar</a>
                            </div>`;
                    }
            
                    dropdownItems += `
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:excluirFirmware('${data.id}', '${status}')" style="cursor: pointer; color: black;">${acao}</a>
                        </div>`;
            
                    return `
                        <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                ${dropdownItems}
                            </div>
                        </div>`;
                }
            }  
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayNoRowsTemplate: localeText.noRowsToShow,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            if (data) {
                if ('id' in data && 'idHardware' in data && data.status === 'Ativo') {
                    editarFirmware(data.id, data.idHardware);
                }        
            }
        }
    };

    $('#select-quantidade-por-pagina-dados').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableFirmware');
    gridDiv.style.setProperty('height', '519px');

    agGridFirmware = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = geServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesFirmware(gridOptions);
}

function stopAgGRIDFirmware() {
    var gridDiv = document.querySelector('#tableFirmware');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperFirmware');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableFirmware" class="ag-theme-alpine my-grid-firmware"></div>';
    }
}

var agGridRegrasEnvio;
function atualizarAgGridRegras(options) {
  stopAgGRIDRegras();
  disabledButtons();
  showLoadingPesquisarButton();
  function geServerSideDados() {
    return {
      getRows: (params) => {
        var route = Router + "/buscarRegrasCadastradas";
        $.ajax({
          cache: false,
          url: route,
          type: "POST",
          data: {
            startRow: params.request.startRow,
            endRow: params.request.endRow,
            idCliente: options ? options.idCliente : "",
            descricao: options ? options.descricao : "",
          },
          dataType: "json",
          async: true,
          success: function (data) {
            if (data && data.success) {
              var dados = data.rows;
              for (let i = 0; i < dados.length; i++) {
                for (let chave in dados[i]) {
                  if (dados[i][chave] === null) {
                    dados[i][chave] = "";
                  }
                }
              }
              params.success({
                rowData: dados,
                rowCount: data.lastRow,
              });
            } else if (data && data.message) {
              params.failCallback();
              params.success({
                rowData: [],
                rowCount: 0,
              });
              gridOptions.api.showNoRowsOverlay();
            } else {
              showAlert("error", "Erro na solicitação ao servidor");
              params.failCallback();
              params.success({
                rowData: [],
                rowCount: 0,
              });
              gridOptions.api.showNoRowsOverlay();
            }
            enableButtons(), resetPesquisarButton();
          },
          error: function (error) {
            showAlert("error", "Erro na solicitação ao servidor");
            params.failCallback();
            params.success({
              rowData: [],
              rowCount: 0,
            });
            enableButtons(), resetPesquisarButton();
          },
        });
      },
    };
  }

  const gridOptions = {
    columnDefs: [
      {
        headerName: "ID",
        field: "id",
        chartDataType: "category",
        width: 80,
        suppressSizeToFit: true,
      },
      {
        headerName: "Descrição",
        field: "descricao",
        width: 250,
        chartDataType: "series",
      },
      {
        headerName: "Cadastrado Por",
        field: "nomeUsuario",
        flex: 1,
        minWidth: 120,
        chartDataType: "series",
      },
      {
        headerName: "Atualizações Desabilitadas",
        field: "desabilitaAtualizacao",
        chartDataType: "category",
        width: 200,
        suppressSizeToFit: true,
        cellRenderer: function (options) {
          let data = options.data["desabilitaAtualizacao"];
          if (data === 1) {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
          } else {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `;
          }
        },
      },
      {
        headerName: "Dias Específicos",
        field: "habilitaDiaProgramado",
        chartDataType: "category",
        width: 150,
        suppressSizeToFit: true,
        cellRenderer: function (options) {
          let data = options.data["habilitaDiaProgramado"];
          if (data === 1) {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
          } else {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `;
          }
        },
      },
      {
        headerName: "Horas Específicas",
        field: "habilitaHorarioProgramado",
        chartDataType: "category",
        width: 150,
        suppressSizeToFit: true,
        cellRenderer: function (options) {
          let data = options.data["habilitaHorarioProgramado"];
          if (data === 1) {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
          } else {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `;
          }
        },
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
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
          } else {
            return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `;
          }
        },
      },
      {
        headerName: "Ações",
        width: 80,
        pinned: "right",
        cellClass: "actions-button-cell",
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
            let tableId = "tableRegras";
            let dropdownId =
                "dropdown-menu-regras" + data.id + varAleatorioIdBotao;
            let buttonId =
                "dropdownMenuButtonRegras_" + data.id + varAleatorioIdBotao;
            let acao = data.status === "Ativo" ? "Inativar" : "Ativar";
            let status = data.status === "Ativo" ? 0 : 1;
    
            let editOption = '';
            if (data.status === "Ativo") {
                editOption = `
                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                        <a href="javascript:preencherModalEdicao(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                    </div>`;
            }
    
            return `
                <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-regras" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        ${editOption}
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:excluirRegras('${data.id}', '${status}')" style="cursor: pointer; color: black;">${acao}</a>
                        </div>
                    </div>
                </div>`;
        },
    }    
    ],
    defaultColDef: {
      editable: false,
      sortable: false,
      filter: false,
      resizable: true,
      suppressMenu: true,
    },
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
            width: 100,
          },
        },
      ],
      defaultToolPanel: false,
    },
    enableRangeSelection: true,
    enableCharts: true,
    pagination: true,
    domLayout: "normal",
    cacheBlockSize: 50,
    paginationPageSize: parseInt(
      $("#select-quantidade-por-pagina-regras").val()
    ),
    localeText: localeText,
    rowModelType: "serverSide",
    serverSideStoreType: "partial",
    overlayNoRowsTemplate: localeText.noRowsToShow,

    onRowDoubleClicked: function (params) {
      let data = "data" in params ? params.data : null;
      if (data) {
        if ("id" in data && data.status === 'Ativo') {
          preencherModalEdicao(data.id);
        }
      }
    },
  };

  $("#select-quantidade-por-pagina-regras").change(function () {
    var selectedValue = $("#select-quantidade-por-pagina-regras").val();
    gridOptions.api.paginationSetPageSize(Number(selectedValue));
  });

  var gridDiv = document.querySelector("#tableRegras");
  gridDiv.style.setProperty("height", "519px");

  agGridRegrasEnvio = new agGrid.Grid(gridDiv, gridOptions);
  let datasource = geServerSideDados();
  gridOptions.api.setServerSideDatasource(datasource);

  preencherExportacoesRegras(gridOptions);
}

function stopAgGRIDRegras() {
    var gridDiv = document.querySelector('#tableRegras');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperRegras');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableRegras" class="ag-theme-alpine my-grid-regras"></div>';
    }
}

var agGridHistoricoEnvio;
function atualizarAgGridHistorico(options) {
    stopAgGRIDHistorico();
    function geServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + "/buscarHistoricoEnvio";
                $.ajax({
                    cache: false,
                    url: route,
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        cliente: options && $("#clienteBusca").val() != '' ? removeAfterParenthesis(options.cliente) : "",
                        serial: options ? options.serial : "",
                        dataHoraEnvioInicio: options ? options.dataHoraEnvioInicio : "",
                        dataHoraEnvioFim: options ? options.dataHoraEnvioFim : "",
                    },
                    dataType: "json",
                    async: true,
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();

                        } else {
                            showAlert("error", "Erro na solicitação ao servidor");
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();

                        }
                        enableButtons(),
                            resetPesquisarButton()
                    },
                    error: function (error) {
                        showAlert("error", "Erro na solicitação ao servidor");
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        enableButtons(),
                            resetPesquisarButton()
                    },
                });

            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                width: 80,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                width: 200,
                chartDataType: 'series'
            },
            {
                headerName: 'Serial',
                field: 'serial',
                width: 150,
                chartDataType: 'series'
            },
            {
                headerName: 'Hora de Envio',
                field: 'horaEnvio',
                width: 200,
                chartDataType: 'series',
                cellRenderer: function (params) {
                    if (params.value) {
                        const dateTime = new Date(params.value);
                        const formattedDateTime = `${dateTime.getDate()}/${dateTime.getMonth() + 1}/${dateTime.getFullYear()} ${dateTime.getHours()}:${dateTime.getMinutes()}:${dateTime.getSeconds()}`;
                        return formattedDateTime;
                    }
                    return '-';
                }
            },
            {
                headerName: 'Versão',
                field: 'versao',
                width: 150,
                chartDataType: 'series'
            },
            {
                headerName: 'Status de Envio',
                field: 'statusEnvio',
                flex:1,
                minWidth: 250,
                chartDataType: 'series'
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 80,
                chartDataType: 'category',
                cellRenderer: function (options) {
                    let data = options.data['status'];
                    if (data == 'Ativo') {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `
                    }
                }
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
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
                    let tableId = "tableHistorico";
                    let dropdownId = "dropdown-menu-hisotico" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButtonHistorico_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-regras" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:visualizarHistorico(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                            </div>
                        </div>
                    </div>`;
                }
            },

        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-historico').val()),
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayNoRowsTemplate: localeText.noRowsToShow,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            if (data) {
                if ('id' in data) {
                    visualizarHistorico(data.id)
                }
            }
        }
    };

    $('#select-quantidade-por-pagina-historico').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-historico').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableHistorico');
    gridDiv.style.setProperty('height', '519px');

    agGridHistoricoEnvio = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = geServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesHistorico(gridOptions);
}

function stopAgGRIDHistorico() {
    var gridDiv = document.querySelector('#tableHistorico');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperHistorico');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableHistorico" class="ag-theme-alpine my-grid-historico"></div>';
    }
}


var agGridTecnologias;
function atualizarAgGridTecnologias(options) {
    stopAgGRIDTecnologias();
    disabledButtons();
    showLoadingPesquisarButton();

    function geServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + "/buscarTecnologiasCadastradas";
                $.ajax({
                    cache: false,
                    url: route,
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        nome: options ? options.nome : "",
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success && response.rows && response.rows.length > 0) {
                            var dados = response.rows.map(row => {
                                for (let key in row) {
                                    if (row[key] === "" || row[key] === null) {
                                        row[key] = "-";
                                    }
                                }
                                return row;
                            });
                            params.successCallback(dados, response.lastRow);
                        } else {
                            gridOptions.api.showNoRowsOverlay();
                            params.successCallback([], 0);
                        }
                        enableButtons();
                        resetPesquisarButton();
                    },
                    error: function (error) {
                        console.error("Erro na solicitação ao servidor:", error);
                        gridOptions.api.showNoRowsOverlay();
                        params.failCallback();
                        enableButtons();
                        resetPesquisarButton();
                    }
                });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: "ID",
                field: "id",
                chartDataType: "category",
                width: 80,
                suppressSizeToFit: true,
            },
            {
                headerName: "Nome",
                field: "nome",
                flex: 1,
                chartDataType: "series",
            },
            {
                headerName: "Status",
                field: "status",
                chartDataType: "series",
                width: 80,
                cellRenderer: function (options) {
                    let data = options.data["status"];
                    if (data == "Ativo") {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `;
                    }
                },
            },
            {
                headerName: "Data de Cadastro",
                field: "dataCadastro",
                chartDataType: "series",
                width: 200,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
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
                    let tableId = "tableTecnologia";
                    let dropdownId =
                        "dropdown-menu-detalhes-tecnologia" +
                        data.id +
                        varAleatorioIdBotao;
                    let buttonId =
                        "dropdownMenuButtonDetalhes_" + data.id + varAleatorioIdBotao;
                    let acao = data.status === "Ativo" ? "Inativar" : "Ativar";
                    let status = data.status === "Ativo" ? 0 : 1;
            
                    let dropdownItems = '';
            
                    if (data.status === "Ativo") {
                        dropdownItems += `
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:preencherModal('${data.id}', '${data.nome}')" style="cursor: pointer; color: black;">Visualizar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:editarTecnologia('${data.id}', '${data.nome}')" style="cursor: pointer; color: black;">Editar</a>
                            </div>`;
                    }
            
                    dropdownItems += `
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:excluirTecnologia('${data.id}', '${status}')" style="cursor: pointer; color: black;">${acao}</a>
                        </div>`;
            
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-detalhes-tecnologia" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                ${dropdownItems}
                            </div>
                        </div>`;
                },
            }            
        ],
        overlayNoRowsTemplate:
            '<span style="padding: 10px; border: 2px solid #444; background: lightgray;">Dados não encontrados!</span>',
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
        },
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
                        width: 100,
                    },
                },
            ],
            defaultToolPanel: false,
        },
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: "normal",
        cacheBlockSize: 50,
        paginationPageSize: parseInt(
            $("#select-quantidade-por-pagina-dados-tecnologia").val()
        ),
        localeText: localeText,
        rowModelType: "serverSide",
        serverSideStoreType: "partial",
        overlayNoRowsTemplate: localeText.noRowsToShow,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            if (data) {
                if ('id' in data && 'nome' in data) {
                    editarTecnologia(data.id, data.nome)
                }
            }
        }
    };

    $('#select-quantidade-por-pagina-dados-tecnologia').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados-tecnologia').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableTecnologia');
    gridDiv.style.setProperty('height', '519px');

    agGridTecnologias = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = geServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesTecnologias(gridOptions);
}

function stopAgGRIDTecnologias() {
    var gridDiv = document.querySelector('#tableTecnologia');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperTecnologia');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableTecnologia" class="ag-theme-alpine my-grid-tecnologia"></div>';
    }
}

var agGridDetalhes
function atualizarAgGridDetalhes(options) {
    stopAgGRIDDetalhes();
    disabledButtons();
    function geServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + "/buscarModelosByIdTecnologia";
                $.ajax({
                    cache: false,
                    url: route,
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idTecnologia: options ? options.idTecnologia : "",
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success && response.rows && response.rows.length > 0) {
                            var dados = response.rows.map(row => {
                                for (let key in row) {
                                    if (row[key] === "" || row[key] === null) {
                                        row[key] = "-";
                                    }
                                }
                                return row;
                            });
                            params.successCallback(dados, response.lastRow);
                        } else if (!response.success || !response.rows || response.rows.length === 0) {
                            gridOptions.api.showNoRowsOverlay();
                            params.successCallback([], 0);
                        }
                        enableButtons();
                    },
                    error: function (error) {
                        showAlert("error", "Erro na solicitação, consulte o suporte Técnico.");
                        gridOptions.api.showNoRowsOverlay();
                        params.successCallback([], 0);
                        enableButtons();
                    }
                });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID Versão',
                field: 'id',
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Nome',
                field: 'nome',
                flex: 1,
                chartDataType: 'series'
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                chartDataType: 'series',
                width: 220,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                },
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 120,
                chartDataType: 'series',
                cellRenderer: function (options) {
                    let data = options.data["status"];
                    if (data == "Ativo") {
                        return `
                                <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                                `;
                    } else {
                        return `
                                <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                                `;
                    }
                },
            },
            {
                headerName: 'Ação',
                pinned: 'right',
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
                    let tableId = "tableDetalhesTecnologia";
                    let dropdownId = "dropdown-menu-detalhes-modelo" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButtonDetalhes_" + data.id + varAleatorioIdBotao;
                    let statusTexto = data.status == 'Ativo' ? 'Inativar' : 'Ativar';

                    return `
            <div class="dropdown">
                <button onclick="javascript:abrirDropdown('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-detalhes-tecnologia" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                        <a href="javascript:editarModelo('${data.id}', '${data.nome}')" style="cursor: pointer; color: black;">Editar</a>
                    </div>
                    <div class="dropdown-item dropdown-item-acoes acoes-status" style="cursor: pointer;">
                    <a href="javascript:alterarStatus('${data.id}', '${statusTexto}','${idTecnologiaDetalhe}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">${statusTexto}</a>
                    </div>
                </div>
            </div>`;
                }
            },
        ],
        overlayNoRowsTemplate: '<span style="padding: 10px; border: 2px solid #444; background: lightgray;">Dados não encontrados!</span>',
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        cacheBlockSize: 50,
        paginationPageSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayNoRowsTemplate: localeText.noRowsToShow,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            if (data) {
                if ('id' in data && 'nome' in data) {
                    editarModelo(data.id, data.nome)
                }
            }
        }
    };


    var gridDiv = document.querySelector('#tableDetalhesTecnologia');
    gridDiv.style.setProperty('height', '519px');

    agGridTecnologias = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = geServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesDetalhes(gridOptions);
}

function stopAgGRIDDetalhes() {
    var gridDiv = document.querySelector('#tableDetalhesTecnologia');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperDetalhesTecnologia');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableDetalhesTecnologia" class="ag-theme-alpine my-grid-detalhes-tecnologia"></div>';
    }
}

var agGridTecnologiasLote;
function preencherTecnologiasLote(dadosInserir) {
    stopAgGRIDTecnologiasLote();

    dadosInserir = dadosInserir.map((item) => ({
        ...item,
    }));

    const columnDefs = [
        {
            headerName: "Nome",
            field: "nome",
            flex: 1,
            sortable: true,
        },
        {
            headerName: 'Ações',
            pinned: 'right',
            width: 100,
            cellClass: "actions-button-cell",
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
                let tableId = "tableAddTecnologia";
                let dropdownId = "dropdown-menu-detalhes-modelo" + data.id + varAleatorioIdBotao;
                let buttonId = "dropdownMenuButtonDetalhes_" + data.id + varAleatorioIdBotao;

                return `
            <div class="dropdown">
                <button onclick="javascript:abrirDropdown('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-modal-addTecnologia" id="${dropdownId}" aria-labelledby="${buttonId}">
                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                        <a href="javascript:removerTecnologia('${data.nome}')" style="cursor: pointer; color: black;">Remover</a>
                    </div>
                </div>
            </div>`;
            }
        }
    ];


    const gridOptions = {
        columnDefs: columnDefs,
        rowData: dadosInserir,
        domLayout: 'normal',
        pagination: true,
        paginationPageSize: 50,
        rowHeight: 45,
        defaultColDef: {
            resizable: true,
            filter: true,
        },
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableAddTecnologia');
    gridDiv.style.height = '420px';

    agGridTecnologiasLote = new agGrid.Grid(gridDiv, gridOptions);

    seriaisConsulta = agGridTecnologiasLote.gridOptions.api.getModel().rowsToDisplay.map(node => node.data.serial);
}

function stopAgGRIDTecnologiasLote() {
    var gridDiv = document.querySelector('#tableAddTecnologia');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperAddTecnologia');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableAddTecnologia" class="ag-theme-alpine my-grid-addTecnologia"></div>';
    }
}

var agGridProgamacao;
function preencherProgramacao(dados) {
    stopAgGridProgramacao();
    const columnDefs = [
        {
            headerName: "Item",
            field: "index",
            width: 100,
            sortable: true,
        },
        {
            headerName: "Dia Atualização",
            field: "diaEnvio",
            width: 200,
            sortable: true,
            cellRenderer: function (options) {
                const value = parseInt(options.value, 10);
                switch (value) {
                    case 1:
                        return "Segunda-Feira";
                    case 2:
                        return "Terça-Feira";
                    case 3:
                        return "Quarta-Feira";
                    case 4:
                        return "Quinta-Feira";
                    case 5:
                        return "Sexta-Feira";
                    case 6:
                        return "Sábado";
                    case 7:
                        return "Domingo";
                    default:
                        return "-";
                }
            },

        },

        {
            headerName: "Hora Inicial",
            field: "horarioInicio",
            width: 120,
            sortable: true,
        },
        {
            headerName: "Hora Final",
            field: "horarioFinal",
            width: 120,
            sortable: true,
        },
        {
            headerName: "Status",
            field: "status",
            flex: 1,
            sortable: true,
        },
        {
            headerName: 'Ações',
            pinned: 'right',
            width: 100,
            cellClass: "actions-button-cell",
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


                let idLinha = options.node.rowIndex;
                let data = options.data;
                let tableId = "tableProgramacao";
                let dropdownId = "dropdown-menu-detalhes-modelo" + data.index + varAleatorioIdBotao;
                let buttonId = "dropdownMenuButtonDetalhes_" + data.index + varAleatorioIdBotao;

                return `
            <div class="dropdown">
                <button onclick="javascript:abrirDropdown('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-modal-addProgramacao" id="${dropdownId}" aria-labelledby="${buttonId}">
                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                        <a href="javascript:removerProgramacao(this, ${idLinha})" style="cursor: pointer; color: black;">Remover</a>
                    </div>
                </div>
            </div>`;
            }
        }
    ];


    const gridOptions = {
        columnDefs: columnDefs,
        rowData: dados,
        domLayout: 'normal',
        pagination: true,
        paginationPageSize: 50,
        rowHeight: 45,
        defaultColDef: {
            resizable: true,
            filter: true,
        },
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableProgramacao');
    gridDiv.style.height = '350px';
    agGridProgamacao = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
}

function stopAgGridProgramacao() {
    var gridDiv = document.querySelector('#tableProgramacao');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperProgramacao');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableProgramacao"  class="ag-theme-alpine my-grid-detalhes-programacao"></div>';
    }
}

// UTILITÁRIOS
async function preencherModal(id, nomeTecnologia) {
    idTecnologiaDetalhe = id;
    $('#detalhesTecnologia').modal('show');
    $('#modalDetalhesNome').val(nomeTecnologia);
    atualizarAgGridDetalhes({ idTecnologia: id });
}

function editarFirmware(id, idHardware) {
    idFirmwareEdicao = id;
    buscarDetalhesFirmware(id, idHardware);
    $("#titleAddFirmware").text("Editar Firmware");
    $("#dadosFirmwareModal").text("Dados do Firmware");
}

async function buscarDetalhesFirmware(id, idHardware) {
    ShowLoadingScreen();
    
    await buscarDadosAssociacaoFirmware(id);
    
    $("#hardwareFirmware").val(idHardware).trigger('change');

    var route = Router + "/detalhesFirmware";

    $.ajax({
        cache: false,
        url: route,
        type: "POST",
        data: { idFirmware: id },
        dataType: "json",
        success: async function (response) {
            if (response.status == 200) {
                var firmware = response.resultado[0];
                var interval = setInterval(function () {
                    $("#modeloFirmware")
                        .val(firmware.idHardwareModelo)
                        .trigger("change");
                    if ($("#modeloFirmware").val() == firmware.idHardwareModelo) {
                        clearInterval(interval);
                        $("#versaoFirmware").val(firmware.versao);
                        $("#liberacaoFirmware").val(firmware.dataLiberacao);
                        $("#descricaoFirmware").val(firmware.descricao);

                        var fileFirmwareName = firmware.fileFirmwareName;
                        var fileReleaseNotesName = firmware.fileReleaseNotesName;
                        var fileFirmware64 = firmware.fileFirmware64;
                        var fileRelease64 = firmware.fileRelease64;

                        var fileFirmwareData = atob(fileFirmware64);
                        var fileReleaseData = atob(fileRelease64);

                        var fileFirmwareBlob = new Blob([fileFirmwareData]);
                        var fileReleaseBlob = new Blob([fileReleaseData], { type: 'text/plain' });

                        var fileFirmwareFileList = new DataTransfer();
                        var fileFirmware = new File([fileFirmwareBlob], fileFirmwareName, { type: 'application/octet-stream' });
                        fileFirmwareFileList.items.add(fileFirmware);

                        var fileReleaseFileList = new DataTransfer();
                        var fileRelease = new File([fileReleaseBlob], fileReleaseNotesName, { type: "application/pdf"});
                        fileReleaseFileList.items.add(fileRelease);

                        $("#arquivoFirmware").prop("files", fileFirmwareFileList.files);
                        $("#releaseNotes").prop("files", fileReleaseFileList.files);

                        $('#addFirmware').modal('show');
                        enableButtons();
                        resetPesquisarButton();
                        HideLoadingScreen();
                    }
                }, 1000);
            } else {
                showAlert("error", "Erro ao buscar detalhes do firmware.");
                enableButtons();
                resetPesquisarButton();
                HideLoadingScreen();
            }
        },
        error: function (error) {
            showAlert("error", "Erro na solicitação, consulte o suporte técnico.");
            enableButtons();
            resetPesquisarButton();
            HideLoadingScreen();
        }
    });
}

function downloadRelease(id) {
    var route = Router + "/detalhesFirmware";
    ShowLoadingScreen();
    $.ajax({
        cache: false,
        url: route,
        type: "POST",
        data: { idFirmware: id },
        dataType: "json",
        success: async function (response) {
            HideLoadingScreen();
            if (response.status == 200) {
                var firmware = response.resultado[0];
                var fileRelease64 = firmware.fileRelease64;
                var fileReleaseNotesName = firmware.fileReleaseNotesName;

                // Decode the base64 string
                var byteCharacters = atob(fileRelease64);
                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var fileReleaseBlob = new Blob([byteArray], { type: 'application/pdf' });
                var fileReleaseUrl = URL.createObjectURL(fileReleaseBlob);

                var $linkRelease = $("<a>")
                    .attr("href", fileReleaseUrl)
                    .attr("download", fileReleaseNotesName)
                    .hide()
                    .appendTo("body");

                $linkRelease[0].click();
                $linkRelease.remove();
            } else {
                showAlert("error", "Erro ao baixar a release.");
            }
        },
        error: function (error) {
            HideLoadingScreen();
            showAlert("error", "Erro na solicitação, consulte o suporte técnico.");
        }
    });
}


function downloadArquivoFirmware(id) {
    var route = Router + "/detalhesFirmware";
    ShowLoadingScreen();

    $.ajax({
        cache: false,
        url: route,
        type: "POST",
        data: { idFirmware: id },
        dataType: "json",
        success: async function (response) {
            if (response.status == 200) {
                var firmware = response.resultado[0];
                var fileFirmware64 = firmware.fileFirmware64;
                var fileFirmwareName = firmware.fileFirmwareName;
                var fileFirmwareData = atob(fileFirmware64);
                var fileFirmwareBlob = new Blob([fileFirmwareData], { type: 'application/octet-stream' });
                var fileFirmwareUrl = URL.createObjectURL(fileFirmwareBlob);

                var $linkFirmware = $("<a>")
                    .attr("href", fileFirmwareUrl)
                    .attr("download", fileFirmwareName)
                    .hide()
                    .appendTo("body");

                $linkFirmware[0].click();
                $linkFirmware.remove();
            } else {
                showAlert("error", "Erro ao baixar o firmware.");
            }
            HideLoadingScreen()
        },
        error: function (error) {
            showAlert("error", "Erro na solicitação, consulte o suporte técnico.");
            HideLoadingScreen()
        }
    });
}

let menuAberto = false;
function expandirGrid() {
    menuAberto = !menuAberto;

    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

    if (menuAberto) {
        $('.img-expandir').attr("src", buttonShow);
        $('#filtroBusca').hide();
        $('.menu-interno').hide();
        $('#conteudo').removeClass('col-md-9');
        $('#conteudo').addClass('col-md-12');
    } else {
        $('.img-expandir').attr("src", buttonHide);
        $('#filtroBusca').show();
        $('.menu-interno').show();
        $('#conteudo').css('margin-left', '0px');
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
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

function formatDateTime(date) {
    if (!date || typeof date !== 'string') {
        return "";
    }

    const parts = date.split(' ');
    const dateParts = parts[0] ? parts[0].split('-') : null;
    if (!dateParts || dateParts.length !== 3) {
        return "";
    }

    const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    const timePart = parts.length > 1 ? ` ${parts[1]}` : "";

    return formattedDate + timePart;
}

function validarArquivos() {
    var arquivoFirmware = document.getElementById('arquivoFirmware').files[0];
    var releaseNotes = document.getElementById('releaseNotes').files[0];
    var mensagemErro = '';

    if (arquivoFirmware) {
        if (arquivoFirmware.type !== 'application/octet-stream') {
            mensagemErro += 'Arquivo firmware deve ser do tipo bin.\n';
        }
    }

    if (releaseNotes) {
        if (releaseNotes.type !== 'application/pdf') {
            mensagemErro += 'Release notes deve ser do tipo PDF.\n';
        }
    }

    if (arquivoFirmware && arquivoFirmware.size === 0) {
        mensagemErro += 'Arquivo firmware não pode estar vazio.\n';
    }

    if (releaseNotes && releaseNotes.size === 0) {
        mensagemErro += 'Arquivo de release notes não pode estar vazio.\n';
    }

    if (mensagemErro !== '') {
        showAlert("error", mensagemErro);
        return false;
    }

    return true;
}

function adicionarTecnologia(nome) {
    if (nome.length === 0) {
        showAlert("warning", "É necessário preencher o campo nome.");
        return;
    }

    if (addTecnologias.some(item => item.nome === nome)) {
        showAlert("warning", "Hardware já informada.");
        return;
    }

    addTecnologias.push({ nome: nome });
    preencherTecnologiasLote(addTecnologias);
}

function removerTecnologia(nome) {
    var rowDataToRemove = null;

    agGridTecnologiasLote.gridOptions.api.forEachNode(function (node) {
        if (node.data.nome === nome) {
            rowDataToRemove = node.data;
        }
    });

    if (rowDataToRemove) {
        agGridTecnologiasLote.gridOptions.api.applyTransaction({ remove: [rowDataToRemove] });
        addTecnologias = addTecnologias.filter(item => item.nome !== nome);
        agGridTecnologiasLote.gridOptions.api.refreshCells({ force: true });
    }
}

function alterarStatus(id, status, idTecnologia) {
    let statusFormatado = status == 'Ativar' ? '1' : '0';
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente alterar o status ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            let route = Router + '/alterarStatusModelo'
            $.ajax({
                url: route,
                data: {
                    id: id,
                    status: statusFormatado
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['status'] == 200) {
                        showAlert("success", "Status alterado com sucesso!")
                    } else if (response['status'] == 400) {
                        showAlert("error", response['resultado']['mensagem']);
                    } else {
                        showAlert("error", response['resultado']['mensagem']);
                    }
                    atualizarAgGridDetalhes({ idTecnologia: idTecnologia });
                    HideLoadingScreen();
                },
                error: function (error) {
                    showAlert("error", "Erro na solicitação ao servidor");
                    HideLoadingScreen();
                }
            })
          
            
        }
      });
}

function convertFileToBase64(file, callback) {
    let reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function (event) {
        const base64String = event.target.result;

        var formatedBase64 = () => {
            const index = base64String.indexOf(",");
            if (index === -1) {
                return [base64String];
            }
            return base64String.substring(index + 1);
        };
        callback(formatedBase64());
    };
    reader.onerror = function (error) {
        console.error("Error: ", error);
        callback(null);
    };
}

function removerProgramacao(botao, idRowIndex) {
    let id = idRowIndex;
    agGridProgamacao.gridOptions.api.applyTransaction({ remove: [agGridProgamacao.gridOptions.api.getDisplayedRowAtIndex(id).data] });
}

function validarCampos(dados) {
    const mensagensErro = {
        versao: "O campo 'Versão' é obrigatório.",
        liberacao: "O campo 'Data de Liberação' é obrigatório.",
        descricao: "O campo 'Descrição' é obrigatório.",
        idHardware: "O campo 'Hardware' é obrigatório.",
        idModelo: "O campo 'Modelo' é obrigatório."
    };

    for (let key in dados) {
        if (mensagensErro.hasOwnProperty(key) && (dados[key] === null || dados[key] === "" || dados[key] === undefined)) {
            showAlert("warning", mensagensErro[key]);
            return false;
        }
    }

    return true;
}


function buscarDadosAssociar(id) {
    ShowLoadingScreen()
    return new Promise((resolve, reject) => {
        
        let route = Router + '/buscarFirmwaresParaAssociar';
        $.ajax({
            url: route,
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (data) {

                if(data.status === 200){
                    HideLoadingScreen()
                    resolve(data)
                }else if(data.status === 400){
                    let mensagem =  data.resultado.mensagem;
                    if(mensagem.includes("Cadastro de Firmware não encontrado para os parâmetros informados.")){
                        showAlert("warning", "Não exite outros firmwares para associar ao firmware selecionado.")
                    }else{
                        showAlert("warning", mensagem)
                    }
                    HideLoadingScreen()
                }else if(data.status === 404){
                    showAlert("warning", data.resultado.mensagem)
                    HideLoadingScreen()
                }else{
                    showAlert("warning", "Erro ao receber os dados, contate o suporte técnico.")
                    HideLoadingScreen()
                }
            },
            error: function () {
                HideLoadingScreen()
                showAlert("error", "Erro ao receber os dados, contate o suporte técnico.")
            }
        });
    })
}

async function abrirModalAssociacao(id, versao) {
    firmwareAssociacao = id;
    let data = await buscarDadosAssociar(id);
    let associado;
    try {
        associado = await buscarAssociado(id);
    } catch (error) {
        showAlert("error", error);
    }

    $('#titleAssociacao').text(`Associar Firmware - ${versao}`);
    let select = $('#idAssociacao'); 
    select.empty();
    select.append(`<option value="" selected disabled>Selecione o firmware que deseja associar.</option>`)
    data.resultado.forEach(item => {
        let optionText = `${item.versao} - ${item.descricao}`;
        select.append(new Option(optionText, item.id));
    });

    select.prop('disabled', false);

    // Seleciona o valor do associado se existir
    if (associado) {
        select.val(associado);
        let firmwareAssociado = select.find('option:selected').text();
        $("#firmwareAssociadoExistente").val(firmwareAssociado);
    }
    
    $('#associarFirmware').modal('show');
}

function associarFirmware(idFirmware) {
  Swal.fire({
    title: "Atenção!",
    text: "Deseja realmente associar o firmware ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#007BFF",
    cancelButtonColor: "#d33",
    confirmButtonText: "Continuar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      disabledButtons();
      ShowLoadingScreen();
      let route = Router + "/cadastrarAssociacao";

      body = {
        idFirmwareInicial: firmwareAssociacao,
        idFirmwareProximo: idFirmware,
      };

      $.ajax({
        url: route,
        type: "POST",
        data: body,
        dataType: "json",
        success: function (response) {
          if (response["status"] == 200) {
            showAlert("success", "Firmware associado com sucesso.");
          } else if (response["status"] == 400) {
            showAlert("error", response["resultado"]["mensagem"]);
          } else if (response["status"] == 404) {
            showAlert("error", response["resultado"]["mensagem"]);
          } else {
            showAlert(
              "error",
              "Erro ao associar firmware, contate o suporte técnico."
            );
          }
          $("#associarFirmware").modal("hide");
          HideLoadingScreen();
          enableButtons();
        },
        error: function (error) {
          showAlert("error", "Erro na solicitação ao servidor");
          $("#associarFirmware").modal("hide");
          HideLoadingScreen();
          enableButtons();
        },
      });
    }
  });
}
        

function buscarAssociado(id) {
    return new Promise((resolve) => {
        disabledButtons();
        ShowLoadingScreen();
        let route = Router + '/buscarIdAssociado';

        $.ajax({
            url: route,
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response['status'] == 200) {
                    let idAssociado = response.resultado.idFirmwareAssociado;
                    resolve(idAssociado);
                } else {
                    resolve()
                }
                HideLoadingScreen();
                enableButtons();
            },
            error: function (error) {
                showAlert("error", "Erro na solicitação ao servidor")
                HideLoadingScreen();
                enableButtons();
            },
        });
    });
}

function desassociarFirmware(id){
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente dessasociar o firmware ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            let route = Router + '/inativarAssociacao'
            $.ajax({
                url: route,
                data: {
                    idFirmwareInicial: id,
                    status: 0
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['status'] == 200) {
                        showAlert("success", "Desassociação realizada com sucesso!")
                    } else if (response['status'] == 400) {
                        showAlert("error", response['resultado']['mensagem']);
                    } else {
                        showAlert("error", response['resultado']['mensagem']);
                    }
                    atualizarAgGridFirmware();
                    HideLoadingScreen();
                },
                error: function (error) {
                    showAlert("error", "Erro na solicitação ao servidor");
                    HideLoadingScreen();
                }
            })
          
            
        }
      });
}

async function buscarDadosAssociacaoFirmware(id) {
    let idFirmwareAssociado = await buscarAssociado(id);
    ShowLoadingScreen();
    console.log(idFirmwareAssociado);

    let route = Router + '/detalhesFirmware';

    let isCadastrar = $('#titleAddFirmware').text().includes('Cadastrar');
    if (isCadastrar) {
        if ($('#inputAssociacaoFirmwareContainer').length > 0) {
            $('#inputAssociacaoFirmwareContainer').remove();
        }
        return;
    }

    if (idFirmwareAssociado) {
        $.ajax({
            url: route,
            type: 'POST',
            data: { idFirmware: idFirmwareAssociado },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    if (response.resultado && response.resultado[0] && response.resultado[0].versao && response.resultado[0].descricao) {
                        let versaoDescricao = `${response.resultado[0].versao} - ${response.resultado[0].descricao}`;

                        if ($('#inputAssociacaoFirmware').length === 0) {
                            let inputHtml = `
                                <div class="form-group col-md-12" id="inputAssociacaoFirmwareContainer">
                                    <label for="firmeAssociadoContainer">Firmware associado:</label>
                                    <input type="text" class="form-control" id="inputAssociacaoFirmware" value="${versaoDescricao}" readonly>
                                </div>
                            `;
                            $('#modeloFirmware').closest('.form-group').after(inputHtml);
                        } else {
                            $('#inputAssociacaoFirmware').val(versaoDescricao);
                        }
                    }
                } else {
                    if ($('#inputAssociacaoFirmware').length === 0) {
                        let inputHtml = `
                            <div class="form-group col-md-12" id="inputAssociacaoFirmwareContainer">
                                <label for="firmeAssociadoContainer">Firmware associado:</label>
                                <input type="text" class="form-control" id="inputAssociacaoFirmware" value="O Firmware não está associado a nenhum outro." readonly>
                            </div>
                        `;
                        $('#modeloFirmware').closest('.form-group').after(inputHtml);
                    } else {
                        $('#inputAssociacaoFirmware').val("O Firmware não está associado a nenhum outro.");
                    }
                }
            },
            error: function() {
                if ($('#inputAssociacaoFirmwareContainer').length > 0) {
                    $('#inputAssociacaoFirmwareContainer').remove();
                }
            }
        });
    } else {
        if ($('#inputAssociacaoFirmware').length === 0) {
            let inputHtml = `
                <div class="form-group col-md-12" id="inputAssociacaoFirmwareContainer">
                    <label for="firmeAssociadoContainer">Firmware associado:</label>
                    <input type="text" class="form-control" id="inputAssociacaoFirmware" value="O Firmware não está associado a nenhum outro." readonly>
                </div>
            `;
            $('#modeloFirmware').closest('.form-group').after(inputHtml);
        } else {
            $('#inputAssociacaoFirmware').val("O Firmware não está associado a nenhum outro.");
        }
    }
}

function removeAfterParenthesis(input) {
    return input.replace(/\s*\(.*$/, '');
}


// VISUALIZAÇÕES

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function disabledButtons() {
    $('.btn').attr('disabled', true);
}
function enableButtons() {
    $('.btn').attr('disabled', false);
}

function clearDate(event) {
    event.target.value = '';
}

function stopIntervals(){
    intervalReferences.forEach(interval => clearInterval(interval));
    intervalReferences = [];
}