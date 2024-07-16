var AgGridSms;
async function atualizarAgGridSms(options) {
	stopAgGRIDSms();

	function getServerSideData() {
		let requestParams = new FormData();
		requestParams.append("idConversation", options);

		return fetch(routePegarSMS, {
			method: "POST",
			cache: "no-cache",
			body: requestParams,
		})
			.then((response) => {
				if (!response.ok) {
					throw new Error(
						"Erro na rede ao buscar SMS. Tente novamente."
					);
				}
				return response.json();
			})
			.then((data) => {
				if (data.status === 200) {
					$("#tab-sms").show();

					var processedData = data.dados;
					for (let i = 0; i < processedData.length; i++) {
						for (let chave in processedData[i]) {
							if (processedData[i][chave] === null) {
								processedData[i][chave] = "";
							}
							if (chave === "telefoneInstalador") {
								processedData[i][chave] = formatNumber(
									processedData[i][chave]
								);
							}

							if (chave === "createdAt") {
								processedData[i][chave] = new Date(
									processedData[i][chave]
								).toLocaleString();
							}

							if (chave == "acao") {
								switch (processedData[i][chave]) {
									case "OPEN":
										processedData[i][chave] =
											'<span class="badge badge-light">Abertura</span>';
										break;
									case "CLOSE":
										processedData[i][chave] =
											'<span class="badge badge-danger">Fechamento</span>';
										break;
									case "ATTEMPT":
										processedData[i][chave] =
											'<span class="badge badge-warning">Tentativa</span>';
										break;
									case "SUCCESS":
										processedData[i][chave] =
											'<span class="badge badge-success">Sucesso</span>';
										break;
									default:
										processedData[i][chave] =
											'<span class="badge badge-secondary">Indefinido</span>';
										break;
								}
							}
						}
					}

					return processedData;
				}
			})
			.catch((error) => {
				alert(error.message);
				document.getElementById("loading").style.display = "none";
			});
	}

	const gridOptions = {
		columnDefs: [
			{
				headerName: "Nome",
				field: "name",
				chartDataType: "series",
				resizable: true,
				width: 310,
			},
			{
				headerName: "Ação",
				chartDataType: "category",
				resizable: true,
				width: 130,
				cellRenderer: (options) => `${options.data["acao"]}`,
			},
			{
				headerName: "Telefone Instalador",
				field: "telefoneInstalador",
				chartDataType: "category",
				resizable: true,
				width: 180,
			},
			{
				headerName: "Instalador ID",
				field: "idInstalador",
				chartDataType: "category",
				resizable: true,
				width: 300,
			},
			{
				headerName: "Data de Envio",
				field: "createdAt",
				chartDataType: "category",
				resizable: true,
				width: 130,
			},
			{
				headerName: "Ações",
				width: 80,
				pinned: "right",
				resizable: false,
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
					let tableId = "tableSms";
					let dropdownId = "dropdown-menu-" + varAleatorioIdBotao;
					let buttonId = "dropdownMenuButton_" + varAleatorioIdBotao;

					return `
                    <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${
									BaseURL +
									"media/img/new_icons/icon_acoes.svg"
								}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:visualizarAuditTrack(this,'${
										data["idConversation"]
									}','${data["idInstalador"]}', '${
						data["confirmation"]
					}')" 
								style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
                                </div>
                            </div>
                    </div>`;
				},
			},
		],
		defaultColDef: {
			editable: false,
			sortable: false,
			filter: false,
			resizable: true,
			suppressMenu: true,
		},
		statusBar: {
			statusPanels: [
				{
					statusPanel: "agTotalRowCountComponent",
					align: "right",
				},
				{
					statusPanel: "agFilteredRowCountComponent",
				},
				{
					statusPanel: "agSelectedRowCountComponent",
				},
				{
					statusPanel: "agAggregationComponent",
				},
			],
		},
		popupParent: document.body,
		enableRangeSelection: true,
		enableCharts: true,
		domLayout: "normal",
		pagination: true,
		paginationPageSize: 10,
		localeText: localeText,
	};

	var gridDiv = document.querySelector("#tableSms");

	AgGridSms = new agGrid.Grid(gridDiv, gridOptions);
    gridDiv.style.setProperty("height", "300px");

	let datasource = await getServerSideData();
	gridOptions.api.setRowData(datasource);

	gridOptions.quickFilterText = "";

	document
		.querySelector("#search-input-sms")
		.addEventListener("input", function (e) {
			e.preventDefault();
			var searchInput = document.querySelector("#search-input-sms");
			gridOptions.api.setQuickFilter(searchInput.value);
		});
}

function stopAgGRIDSms() {
	var gridDiv = document.querySelector("#tableSms");
	if (gridDiv && gridDiv.api) {
		gridDiv.api.destroy();
	}

	var wrapper = document.querySelector(".wrapperSms");
	if (wrapper) {
		wrapper.innerHTML =
			'<div id="tableSms" class="ag-theme-alpine my-grid-sms"></div>';
	}
}

var AgGridAuditTrack;
async function atualizarAgGridAuditTrack(options) {
	stopAgGRIDAuditTrack();

	function getServerSideData() {
		let requestParams = new FormData();
		requestParams.append("idConversation", options.idConversation);
		requestParams.append("idInstalador", options.idInstalador);

		return fetch(routePegarAuditTrack, {
			method: "POST",
			cache: "no-cache",
			body: requestParams,
		})
			.then((response) => {
				if (!response.ok) {
					throw new Error(
						"Erro na rede ao buscar SMS. Tente novamente."
					);
				}
				return response.json();
			})
			.then((data) => {
				if (data.status === 200) {
					$("#tab-sms").show();

					var processedData = data.dados;
					for (let i = 0; i < processedData.length; i++) {
						for (let chave in processedData[i]) {
							if (processedData[i][chave] === null) {
								processedData[i][chave] = "";
							}

							if (chave === "createdAt") {
								processedData[i][chave] = new Date(
									processedData[i][chave]
								).toLocaleString();
							}

							if (chave == "acao") {
								switch (processedData[i][chave]) {
									case "OPEN":
										processedData[i][chave] =
											'<span class="badge badge-light">Abertura</span>';
										break;
									case "CLOSE":
										processedData[i][chave] =
											'<span class="badge badge-danger">Fechamento</span>';
										break;
									case "ATTEMPT":
										processedData[i][chave] =
											'<span class="badge badge-warning">Tentativa</span>';
										break;
									case "SUCCESS":
										processedData[i][chave] =
											'<span class="badge badge-success">Sucesso</span>';
										break;
									default:
										processedData[i][chave] =
											'<span class="badge badge-secondary">Indefinido</span>';
										break;
								}
								//processedData[i][chave] =
							}
						}
					}

					return data.dados;
				}
			})
			.catch((error) => {
				alert(error.message);
				document.getElementById("loading").style.display = "none";
			});
	}

	const gridOptions = {
		columnDefs: [
			{
				headerName: "Instalador ID",
				field: "idInstalador",
				chartDataType: "series",
				resizable: true,
				width: 310,
			},
			{
				headerName: "Ação",
				chartDataType: "category",
				resizable: true,
				width: 130,
				cellRenderer: (options) => `${options.data["acao"]}`,
			},
			{
				headerName: "Descrição",
				field: "description",
				chartDataType: "category",
				resizable: true,
				width: 400,
			},
			{
				headerName: "Data de Envio",
				field: "createdAt",
				chartDataType: "category",
				resizable: true,
				width: 200,
			},
		],
		defaultColDef: {
			editable: false,
			sortable: false,
			filter: false,
			resizable: true,
			suppressMenu: true,
		},
		statusBar: {
			statusPanels: [
				{
					statusPanel: "agTotalRowCountComponent",
					align: "right",
				},
				{
					statusPanel: "agFilteredRowCountComponent",
				},
				{
					statusPanel: "agSelectedRowCountComponent",
				},
				{
					statusPanel: "agAggregationComponent",
				},
			],
		},
		popupParent: document.body,
		enableRangeSelection: true,
		enableCharts: true,
		domLayout: "autoHeight",
		pagination: true,
		paginationPageSize: 10,
		localeText: localeText,
        overlayLoadingTemplate:
            '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>',
	};

	var gridDiv = document.querySelector("#tableAuditTrack");

	AgGridAuditTrack = new agGrid.Grid(gridDiv, gridOptions);

	let datasource = await getServerSideData();
	gridOptions.api.setRowData(datasource);

	gridOptions.quickFilterText = "";

	document
		.querySelector("#search-input-audit")
		.addEventListener("input", function (e) {
			e.preventDefault();
			var searchInput = document.querySelector("#search-input-audit");
			gridOptions.api.setQuickFilter(searchInput.value);
		});
}

function stopAgGRIDAuditTrack() {
	var gridDiv = document.querySelector("#tableAuditTrack");
	if (gridDiv && gridDiv.api) {
		gridDiv.api.destroy();
	}

	var wrapper = document.querySelector(".wrapperAuditTrack");
	if (wrapper) {
		wrapper.innerHTML =
			'<div id="tableAuditTrack" class="ag-theme-alpine my-grid-audit-track"></div>';
	}
}

async function visualizarConversa(idConversation, clientName) {
	$("#modalConversa").modal("show");
	$("#search-input-sms").val("");

	document.getElementById("conversationIdSpan").textContent = idConversation;
	document.getElementById("clientNameSpan").textContent = clientName;
	document.getElementById("loading").style.display = "block";
	$("#listaMensagens").html("");

	$("#tab-conversas").click();
	$("#tab-agenda").hide();
	$("#tab-audit_track").hide();
	$("#tab-sms").hide();
	$("#alertMensagens").hide();

	await $.ajax({
		url: routePegarAgendamento,
		type: "POST",
		data: {
			idConversation: idConversation,
		},
		dataType: "json",
		success: function (data) {
			if (data.status === 200) {
				let conversa = data.dados[0]["conversations"];
				let conversaJson = JSON.parse(conversa);

				let listaMensagens = $("#listaMensagens");
				if (conversaJson["requestError"]) {
					$("#alertMensagens").show();
					return;
				}

				conversaJson["messages"].forEach((message, index, messages) => {
					let text = message["content"];

					let number = message["from"].replace(/\D/g, "");
					number = number.replace(/(\d{4})(\d)/, "$1) $2");
					number = number.replace(/(\d{2})(\d)/, "+$1 ($2");
					number = number.replace(/(\d)(\d{4})$/, "$1-$2");
					let date = new Date(message["createdAt"]).toLocaleString();

					let firstOrLast =
						message["direction"] === "INBOUND"
							? "middle-in-group"
							: "middle-in-group-received";
					if (
						index === 0 ||
						message["direction"] !==
							messages[index - 1]["direction"]
					) {
						firstOrLast =
							message["direction"] === "INBOUND"
								? "first-in-group"
								: "first-in-group-received";
					} else if (
						index === messages.length - 1 ||
						message["direction"] !==
							messages[index + 1]["direction"]
					) {
						firstOrLast =
							message["direction"] === "INBOUND"
								? "last-in-group"
								: "last-in-group-received";
					}

					if (text["interactive"]) {

						let buttons = "";
						text["interactive"]["action"]["buttons"].forEach(
							(button) => {
								buttons += `<span class="badge badge-pill ${
									message["direction"] === "INBOUND"
										? "badge-light"
										: "badge-primary"
								}">${button["title"]}</span>  `;
							}
						);

						text = text["interactive"]["body"]["text"].replace(
							/\n/g,
							"<br>"
						);

						if (message["direction"] == "INBOUND") {
							listaMensagens.append(`
                                <li class="li-mensagem-user"> 
                                    <div class="message sent ${firstOrLast}">
                                        <div class="messageHeader">
                                            <span>${number}</span>
                                            <div class="messageIcon">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-inline: 1px;" height="12px" width="10.5px" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#216fff" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="messageBody" style="text-align: end;">
                                            <p style="color: #F0F0F0;">${text}</p>
                                            <div class="decisionMenu"> 
                                                ${buttons}
                                            </div>
                                        </div>
                                        <div class="messageFooter" style="text-align: start;">
                                            <span>${date}</span>
                                        </div>
                                    </div>
                                </li>`);
						} else {
							listaMensagens.append(`
                                <li class="li-mensagem-bot">
                                    <div class="message received ${firstOrLast}">
                                        <div class="messageHeader">
                                            <div class="messageIcon">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="12px" width="15px" viewBox="0 0 640 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path fill="#ffffff" d="M320 0c17.7 0 32 14.3 32 32V96H472c39.8 0 72 32.2 72 72V440c0 39.8-32.2 72-72 72H168c-39.8 0-72-32.2-72-72V168c0-39.8 32.2-72 72-72H288V32c0-17.7 14.3-32 32-32zM208 384c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H208zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H304zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H400zM264 256a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm152 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80zM48 224H64V416H48c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48zm544 0c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H576V224h16z"/>
                                                </svg>
                                            </div>
                                            <span>${number}</span>
                                        </div>
                                        <div class="messageBody" style="text-align: start;">
                                            <p style="color: #202020;">${text}</p>
                                            <div> 
                                                ${buttons}
                                            </div>
                                        </div>
                                        <div class="messageFooter" style="text-align: end;">
                                            <span>${date}</span>
                                        </div>
                                    </div>
                                </li>`);
						}
					} else {
						if (text["text"]) {
							text = text["text"].replace(/\n/g, "<br>");
						} else if (text["title"]) {
							text = text["title"].replace(/\n/g, "<br>");
						}

						if (message["direction"] == "INBOUND") {
							listaMensagens.append(`
                                <li class="li-mensagem-user"> 
                                    <div class="message sent ${firstOrLast}">
                                        <div class="messageHeader">
                                            <span>${number}</span>
                                            <div class="messageIcon">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-inline: 1px;" height="12px" width="10.5px" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#216fff" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="messageBody" style="text-align: end;">
                                            <p style="color: #F0F0F0;">${text}</p>
                                        </div>
                                        <div class="messageFooter" style="text-align: start;">
                                            <span>${date}</span>
                                        </div>
                                    </div>
                                </li>`);
						} else {
							listaMensagens.append(`
                                <li class="li-mensagem-bot">
                                    <div class="message received ${firstOrLast}">
                                        <div class="messageHeader">
                                            <div class="messageIcon">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="12px" width="15px" viewBox="0 0 640 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path fill="#ffffff" d="M320 0c17.7 0 32 14.3 32 32V96H472c39.8 0 72 32.2 72 72V440c0 39.8-32.2 72-72 72H168c-39.8 0-72-32.2-72-72V168c0-39.8 32.2-72 72-72H288V32c0-17.7 14.3-32 32-32zM208 384c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H208zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H304zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H400zM264 256a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm152 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80zM48 224H64V416H48c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48zm544 0c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H576V224h16z"/>
                                                </svg>
                                            </div>
                                            <span>${number}</span>
                                        </div>
                                        <div class="messageBody" style="text-align: start;">
                                            <p style="color: #202020;">${text}</p>
                                        </div>
                                        <div class="messageFooter" style="text-align: end;">
                                            <span>${date}</span>
                                        </div>
                                    </div>
                                </li>`);
						}
					}
				});
			} else {
				alert("Nenhuma conversa encontrada.");
			}
		},
		error: function () {
			alert("Erro ao buscar os agendamentos. Tente novamente.");
			document.getElementById("loading").style.display = "none";
		},
	});

	await $.ajax({
		url: routePegarAgenda,
		type: "POST",
		data: {
			idConversation: idConversation,
		},
		dataType: "json",
		success: function (data) {
			if (data.status === 200) {
				$("#tab-agenda").show();
				let agendamentos = data.dados;

				let agendamentosOrdenado = agendamentos.sort(function (a, b) {
					if (a["instantSolicitacao"] < b["instantSolicitacao"])
						return -1;
					if (a["instantSolicitacao"] < b["instantSolicitacao"])
						return 1;
					return 0;
				});

				let AgendamentoPrincipal = agendamentosOrdenado[0];

				let number = AgendamentoPrincipal["numeroUsado"].replace(
					/\D/g,
					""
				);
				number = number.replace(/(\d{4})(\d)/, "$1) $2");
				number = number.replace(/(\d{2})(\d)/, "+$1 ($2");
				number = number.replace(/(\d)(\d{4})$/, "$1-$2");

				let cep = AgendamentoPrincipal["cep"].replace(/\D/g, "");
				cep = cep.replace(/(\d{5})(\d)/, "$1-$2");

				$("#ClienteAgendamento").val(AgendamentoPrincipal["cliente"]);
				$("#CelularAgendamento").val(number);
				$("#EmailAgendamento").val(AgendamentoPrincipal["email"]);

				$("#DocumentoAgendamento").val(AgendamentoPrincipal["cpfCnpj"]);
				$("#SerialAgendamento").val(AgendamentoPrincipal["serial"]);
				$("#QtdAgendamento").val(
					AgendamentoPrincipal["qtdEquipamentos"]
				);

				$("#CepAgendamento").val(cep);
				$("#LogradouroAgendamento").val(
					AgendamentoPrincipal["logradouro"]
				);
				$("#NumeroAgendamento").val(AgendamentoPrincipal["numero"]);

				$("#ComplementoAgendamento").val(
					AgendamentoPrincipal["complemento"]
				);
				$("#BairroAgendamento").val(AgendamentoPrincipal["bairro"]);
				$("#CidadeAgendamento").val(AgendamentoPrincipal["cidade"]);

				let dataHoraInstalacao =
					AgendamentoPrincipal["dataHoraInstalacao"];
				if (dataHoraInstalacao && dataHoraInstalacao.includes("|")) {
					dataHoraInstalacao = dataHoraInstalacao.split("|");
					dataHoraInstalacao =
						dataHoraInstalacao[dataHoraInstalacao.length - 1];
				}
				$("#EstadoAgendamento").val(AgendamentoPrincipal["estado"]);
				$("#DataSolicitacaoAgendamento").val(
					new Date(
						AgendamentoPrincipal["instantSolicitacao"]
					).toLocaleString()
				);
				$("#DataHoraInstalacaoAgendamento").val(
					new Date(dataHoraInstalacao).toLocaleString()
				);

				$("#Status").val(AgendamentoPrincipal["status"]);

				let InstaladorAgendamento =
					AgendamentoPrincipal["telefoneInstalador"];

				if (InstaladorAgendamento != null) {
					InstaladorAgendamento = formatarNumeros(
						InstaladorAgendamento
					);
				}

				$("#NAAgendamento").val(
					AgendamentoPrincipal["idNa"]
						? AgendamentoPrincipal["idNa"]
						: ""
				);
				$("#InstaladorAgendamento").val(
					AgendamentoPrincipal["nomeInstalador"]
						? AgendamentoPrincipal["nomeInstalador"]
						: ""
				);
				$("#TelefoneInstaladadorAgendamento").val(
					InstaladorAgendamento ? InstaladorAgendamento : ""
				);
			}
		},
		error: function () {
			alert("Erro ao buscar a agenda. Tente novamente.");
		},
	});

	function formatarNumeros(numeros) {
		if (numeros.length == 13) {
			return formatacaoRegexNumero(numeros);
		} else if (numeros.length == 26) {
			telefone1 = formatacaoRegexNumero(numeros.substring(0, 13));
			telefone2 = formatacaoRegexNumero(numeros.substring(13, 26));
			return telefone1 + ", " + telefone2;
		} else if (numeros.length == 39) {
			telefone1 = formatacaoRegexNumero(numeros.substring(0, 13));
			telefone2 = formatacaoRegexNumero(numeros.substring(13, 26));
			telefone3 = formatacaoRegexNumero(numeros.substring(26, 39));
			return telefone1 + ", " + telefone2 + " e " + telefone3;
		}
	}

	function formatacaoRegexNumero(numero) {
		numero = numero.replace(/\D/g, "");
		numero = numero.replace(/(\d{4})(\d)/, "$1) $2");
		numero = numero.replace(/(\d{2})(\d)/, "+$1 ($2");
		numero = numero.replace(/(\d)(\d{4})$/, "$1-$2");
		return numero;
	}

	await atualizarAgGridSms(idConversation);

	document.getElementById("loading").style.display = "none";
}

function formatNumber(data) {
	if (data && data.length == 11) {
		data = "55" + data;
	}
	let number = data.replace(/\D/g, "");
	number = number.replace(/(\d{4})(\d)/, "$1) $2");
	number = number.replace(/(\d{2})(\d)/, "+$1 ($2");
	number = number.replace(/(\d)(\d{4})$/, "$1-$2");

	return number;
}

function visualizarAuditTrack(
	botao,
	idConversation,
	idInstalador,
	confirmation
) {
	if (confirmation == 0) {
		alert("Não há dados para serem exibidos.");
		return;
	} else {
		$("#modalAuditTrack").modal({
			focus: true,
			show: true,
		});

		$("#search-input-audit").val("");

		atualizarAgGridAuditTrack({
			idConversation: idConversation,
			idInstalador: idInstalador,
		});
	}
}
