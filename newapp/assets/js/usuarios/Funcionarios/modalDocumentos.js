var funcionarioIdRecuperacao;
var funcionarioCpfRecuperacao;
var imgUrlFlag;
var documentFileName;

$("#modalEnviarDocumentoFuncionario").ready(function() {
	const select = $("#tipoDoc");
	select.select2({
		ajax: {
			url: getAllDocumentTypes,
			type: "POST",
			dataType: "json",
			delay: 250,
			data: function (params) {
				return {
					title: params.term || null,
				};
			},
			processResults: function (data, params) {
				
				if (!data.success) {
					return {
						results: []
					};
				} else {
					let rawData = data.result;
					return {
						results: rawData.map((row) => {
							return {
								id: row.id,
								text: row.nome,
							};
						}),
					};
				}
			},
		},
		placeholder: "Selecione um tipo de documento",
		allowClear: true,
		width: "100%",
		language: {
			searching: function () {
				return "Carregando...";
			},
			noResults: function(){
				return "Tipo de documento não encontrado";
			}
		},
	});
});

$(document).on("click", "#btnModalAddDocumento", function () {
	$("#modalEnviarDocumentoFuncionario").modal();

	$("#btnSendDocumento")
		.off()
		.on("click", () => handleDocumentoFuncionario('insert'));
});


$('#modalEnviarDocumentoFuncionario').on('hide.bs.modal', function (e) {
    document.getElementById("documentoFuncionarioForm").reset();
	$("#tipoDoc").val(null).trigger('change');
});

async function editarDocumento(id, titulo, idDocumento, nomeTipoDocumento, idTipoDocumento) {
	let documentTypeSelect = $('#tipoDoc');

	var option = new Option(nomeTipoDocumento, idTipoDocumento, true, true);
    documentTypeSelect.append(option).trigger('change');

	const requestParams = new FormData();
	requestParams.append("documentId", idDocumento);

	const response = await fetch(getDocumento, {
		method: "POST",
		cache: "no-cache",
		body: requestParams
	});

	if (!response.ok) {
		throw new Error("Documento não encontrado!");
	}

	const data = await response.json();

	let arquivoBase64 = data.documento;
	let nomeArquivo = data.nomeDocumento;

	let byteCharacters = atob(arquivoBase64);
	let byteNumbers = new Array(byteCharacters.length);
	for (let i = 0; i < byteCharacters.length; i++) {
		byteNumbers[i] = byteCharacters.charCodeAt(i);
	}
	let byteArray = new Uint8Array(byteNumbers);
	let arquivoBlob = new Blob([byteArray], { type: 'application/pdf' });
	let arquivoFileList = new DataTransfer();
	let arquivoFile = new File([arquivoBlob], nomeArquivo, { type: 'application/pdf' });
	arquivoFileList.items.add(arquivoFile);

	$("#arquivoItens").prop("files", arquivoFileList.files);

	$(".titleDocumento").text("Editar Documento");
	$("label[for='tituloDoc'] span.text-danger").remove();
	$("label[for='arquivoItens'] span.text-danger").remove();
	$("#tituloDoc").val(titulo);
	$("#modalEnviarDocumentoFuncionario").modal("show");
	$("#btnSendDocumento")
		.off()
		.on("click", () => handleDocumentoFuncionario('update', id, idDocumento));
}


var AgGridDocumentosFuncionario;
async function atualizarAgGridDocumentosFuncionario(funcId) {
	stopAgGRIDDocumentosFuncionario();

	function getServerSideDados() {
		return {
			getRows: (params) => {
				$.ajax({
					cache: false,
					url: getAllDocumentAssociations,
					type: "POST",
					data: {
						startRow: params.request.startRow,
						endRow: params.request.endRow,
						employeeId: funcId ? funcId : "",
					},
					dataType: "json",
					async: true,
					success: function (data) {
						if (data && data.success) {
							var dados = data.rows;
							for (let i = 0; i < dados.length; i++) {
								for (let chave in dados[i]) {
									if (
										dados[i][chave] === null ||
										dados[i][chave] === ""
									) {
										dados[i][chave] = "Não informado";
									}

									if (
										chave === "dataCadastro" &&
										dados[i][chave] != "Não informado"
									) {
										dados[i][chave] = formatDate(
											dados[i][chave]
										);
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
							onBtShowNoRows();
						} else {
							showAlert("error","Erro na solicitação ao servidor");
							params.failCallback();
							params.success({
								rowData: [],
								rowCount: 0,
							});
						}
					},
					error: function (error) {
						showAlert("error","Erro na solicitação ao servidor");
						params.failCallback();
						params.success({
							rowData: [],
							rowCount: 0,
						});
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
				enableRowGroup: true,
				suppressSizeToFit: true,
				cellStyle: { fontWeight: "normal" },
			},
			{
				headerName: "Tipo de Documento",
				field: "nomeTipoDocumento",
				enableRowGroup: true,
				chartDataType: "category",
				width: 240,
				suppressSizeToFit: true,
				cellStyle: { fontWeight: "normal" }
			},
			{
				headerName: "Data de Criação",
				field: "dataCadastro",
				enableRowGroup: true,
				chartDataType: "category",
				flex: 1,
				suppressSizeToFit: true,
				cellStyle: { fontWeight: "normal" }
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

					var varAleatorioDoc =
						(firstRandom * secRandom).toFixed(0) +
						"-" +
						(thirdRandom * firstRandom).toFixed(0) +
						"-" +
						(secRandom * thirdRandom).toFixed(0);

					let data = options.data;
					let tableId = "tableDocumentosFuncionario";
					let dropdownId =
						"dropdown-menu-" +
						data.id +
						"document" +
						varAleatorioDoc;
					let buttonId =
						"dropdownMenuButton_" +
						data.id +
						"document" +
						varAleatorioDoc;

					return `
                    <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id
						}" nome="${data.placa_lida}" id="${data.id
						}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL +
						"media/img/new_icons/icon_acoes.svg"
						}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-documentos-funcionario" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:abrirVisualizarDocumentoPDF('${data.idDocumentosFuncionario
						}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
								<div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:editarDocumento(
										'${data.id}',
										'${data.nomeDocumentoFuncionario}', 
										'${data.idDocumentosFuncionario}',
										'${data.nomeTipoDocumento}',
										'${data.idTipoDocumento}'
									)" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
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
		enableRangeSelection: true,
		enableCharts: true,
		pagination: true,
		paginateChildRows: true,
		domLayout: "normal",
		paginationPageSize: parseInt(
			$("#select-quantidade-por-pagina-documentos-funcionario").val()
		),
		localeText: localeText,
		rowModelType: "serverSide",
		serverSideStoreType: "partial",
		overlayNoRowsTemplate:
			"<p>Este funcionário não tem documentos cadastrados!</p>",
	};

	function onBtShowNoRows() {
		gridOptions.api.showNoRowsOverlay();
	}

	var gridDiv = document.querySelector("#tableDocumentosFuncionario");
	AgGridDocumentosFuncionario = new agGrid.Grid(gridDiv, gridOptions);
	let datasource = getServerSideDados();
	gridOptions.api.setServerSideDatasource(datasource);

	$("#select-quantidade-por-pagina-documentos-funcionario").change(
		function () {
			var selectedValue = $(
				"#select-quantidade-por-pagina-documentos-funcionario"
			).val();
			gridOptions.api.paginationSetPageSize(Number(selectedValue));
		}
	);

	var gridDiv = document.querySelector("#tableDocumentosFuncionario");
	gridDiv.style.setProperty("height", "445px");

	function handleDataChange(event) {
		$("#loadingMessageFunc").show();

		let paginaAtual = Number(event.api.paginationGetCurrentPage());
		let tamanhoPagina = Number(event.api.paginationGetPageSize());

		const filteredData = [];
		event.api.forEachNode((node) => {
			if (node.displayed) {
				filteredData.push(node.data);
			}
		});
		const startIndex = paginaAtual * tamanhoPagina;
		const endIndex = startIndex + tamanhoPagina;

		const pageData = filteredData.slice(startIndex, endIndex);

		var dados = [];

		pageData.forEach((data) => {
			if (data) {
				dados.push(data);
			}
		});

		$("#loadingMessageFunc").hide();
	}

	gridOptions.api.addEventListener("paginationChanged", handleDataChange);
}

function stopAgGRIDDocumentosFuncionario() {
	var gridDiv = document.querySelector("#tableDocumentosFuncionario");
	if (gridDiv && gridDiv.api) {
		gridDiv.api.destroy();
	}

	var wrapper = document.querySelector(".wrapperDocumentosFuncionario");
	if (wrapper) {
		wrapper.innerHTML =
			'<div id="tableDocumentosFuncionario" class="ag-theme-alpine my-grid-documentos-funcionario"></div>';
	}
}

function abrirGerenciamentoDocumentos(cpf, id) {
	atualizarAgGridDocumentosFuncionario(id);

	funcionarioIdRecuperacao = id;
	funcionarioCpfRecuperacao = cpf;

	$("#modalGerenciamentoDeDocumentoDoFuncionario").modal();
}

async function abrirVisualizarDocumentoPDF(documentId) {
	const requestParams = new FormData();
	requestParams.append("documentId", documentId);

	$(".modalLoadingMessageAct").show();

	try {
		const response = await fetch(getDocumento, {
			method: "POST",
			cache: "no-cache",
			body: requestParams,
		});

		if (!response.ok) {
			throw new Error("Documento não encontrado!");
		}

		const data = await response.json();

		$("#nomeDocumento").html(data.nomeDocumento);
		documentFileName = data.nomeDocumento;

		if (data.documento != null) {
			const base64Data = data.documento;

			if (!base64Data.startsWith("JVBERi0")) {
				console.log("O documento fornecido não é um PDF válido.");
			}

			const urlPdf = "data:application/pdf;base64," + base64Data;

			if (data.status == 0) {
				$("#div-img-mensagem").hide();
				PDFViewer.preloadAndApplyWatermark(
					urlPdf,
					`${BaseURL}arq/usuarios/shownetoverlayV2.png`,
					"pdfContainer",
					userDataOverlay
				);
			} else {
				$("#div-img-mensagem").show();
			}
		} else {
			console.log("O conteúdo do documento é nulo.");
		}
	} catch (error) {
		console.error("Error in abrirVisualizarDocumentoPDF:", error);
		showAlert("error", error.message);
		$(".modalLoadingMessageAct").hide();
	}

	$("#modalVisualizarDocumentoFuncionario").addClass('show');
	setTimeout(() => {
		$(".modalLoadingMessageAct").hide();
	}, 200);
}


$(document).ready(function () {
	$("#documentDownloadBtn").off().on("click", () => {
		PDFViewer.downloadPDF("pdfContainer", "downloaded.pdf");
		$("#documentDownloadBtn").blur();
	});

	$(".custom-close, .btn-secondary").on("click", function () {
		$("#modalVisualizarDocumentoFuncionario").removeClass('show');
	});
});

function handleDocumentoFuncionario(action, id = null, idDocumento = null) {
	const documentFileInput = document.getElementById("arquivoItens");
	const file = documentFileInput.files[0];

	if (!file && action === 'insert') {
		showAlert("warning", "Insira um documento para ser enviado e tente novamente!");
		return;
	}

    const documentTypeInput = document.getElementById("tipoDoc");

	if($('#tipoDoc').val() == null){
		showAlert("warning", "Você precisa inserir o tipo do documento!");
		return;
	};

    if (!documentFileInput.files[0] && !documentTypeInput.value) {
        showAlert("warning", "Você precisa inserir os dados do documento!");
        return;
    }

    if (file) {
        const extension = file.name.split(".").pop().toLowerCase();
        const validExtensions = ["pdf"];
        if (!validExtensions.includes(extension)) {
            showAlert("warning", "Tipo de arquivo inválido. Selecione um arquivo .pdf ");
            return;
        }

		const tamanhoArquivoEmBytes = 2097152;

		if (file.size > tamanhoArquivoEmBytes) {
			showAlert("warning", "O arquivo não pode ter mais de 2 megabytes!");
			return;
		}
    }


    let requestParams = new FormData();
    if (action === 'update' && id) {
        requestParams.append("id", id);
        requestParams.append("idDocumento", idDocumento);
    } 

	const now = new Date();

	const day = String(now.getDate()).padStart(2, '0');
	const month = String(now.getMonth() + 1).padStart(2, '0');
	const year = now.getFullYear();
	const hours = String(now.getHours()).padStart(2, '0');
	const minutes = String(now.getMinutes()).padStart(2, '0');
	const seconds = String(now.getSeconds()).padStart(2, '0');

	const formattedDate = `${day}${month}${year}${hours}${minutes}${seconds}`;

	const nomeDocFormatado = $('#tipoDoc').select2('data')[0].text + funcionarioCpfRecuperacao + formattedDate;
	
	requestParams.append("funcionarioId", funcionarioIdRecuperacao);
    requestParams.append("nomeDocumento", nomeDocFormatado);
    requestParams.append("tipoDocumentoId", documentTypeInput.value);

    const readFileAsBase64 = (file) => {
        return new Promise((resolve, reject) => {
            if (!file) {
                resolve(null);
            } else {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const base64String = event.target.result;
                    const index = base64String.indexOf(",");
                    const base64 = index === -1 ? base64String : base64String.substring(index + 1);
                    resolve(base64);
                };
                reader.onerror = function (error) {
                    reject(error);
                };
                reader.readAsDataURL(file);
            }
        });
    };

    readFileAsBase64(file).then((base64Data) => {
		requestParams.append("documento", base64Data);
	
		const sendButton = $("#btnSendDocumento");
		sendButton.attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>');
	
		const url = action === 'update' ? updateAssociacaoDocumento : insertAssociacaoDocumento;
	
		fetch(url, {
			method: "POST",
			body: requestParams,
		})
		.then(async (response) => {
			let dataResponse = await response.json();
			
			if (dataResponse.status === 200) {
				atualizarAgGridDocumentosFuncionario(funcionarioIdRecuperacao);
				showAlert("success", `Documento ${action === 'update' ? 'atualizado' : 'inserido'} com sucesso!`);
			} 
			else if (dataResponse.mensagem === "doc_existente") {
				showAlert("error", "Um documento com mesmo tipo já foi adicionado!");
			} 
			else if (dataResponse.status === 400) {
				const errorMessage = dataResponse.resultado && dataResponse.resultado.errors
					? dataResponse.resultado.errors[0]
					: dataResponse.resultado.mensagem;
				showAlert("error", errorMessage);
			} 
			else if (dataResponse.status === 404) {
				showAlert("warning", "Dados não encontrados.");
			} 
			else {
				showAlert("error", "Erro interno do servidor. Entre em contato com o Suporte Técnico");
			}
			
			$("#modalEnviarDocumentoFuncionario").modal("hide");
		}).catch((error) => {
			console.error(error);
			showAlert("error", "Ocorreu um erro durante a requisição! Tente novamente...");
		}).finally(() => {
			sendButton.attr("disabled", false).html("Enviar");
		});
	});
	
}
