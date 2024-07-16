const tableDocsInstalador = "#tableDocsInstalador";
const paginationSelectDocsInstalador = "#paginationSelectDocsInstalador";
const getDocumentosInstalador = Router + "/getDocumentosInstalador";
const insertDocument = Router + "/insertDocument";
const updateDocument = Router + "/updateDocument";
const getDocumento = Router + "/getDocumentoServerSide";

var agGridTableDocuments;

class Document {
    constructor(installerId) {
        this.installerId = installerId;
    }

    handleDocument(documentoId = null) {
        const documentFileInput = document.getElementById("arquivoItens");
        const documentTitleInput = document.getElementById("tituloDoc");
        const installerId = this.installerId;
    
        const file = documentFileInput.files[0];
        const documentTitle = documentTitleInput.value;
    
        if (!file && !documentTitle) {
            showAlert("warning", "Você precisa inserir os dados do documento!");
            return;
        }
    
        if (!documentoId) {
            if (!file) {
                showAlert("warning", "Insira um documento para ser enviado e tente novamente!");
                return;
            }
            if (!documentTitle) {
                showAlert("warning", "Adicione um título e tente novamente!");
                return;
            }
        }
    
        if (file) {
            const extension = file.name.split(".").pop().toLowerCase();
            const validExtensions = ["jpg", "jpeg", "png"];
            if (!validExtensions.includes(extension)) {
                showAlert("warning", "Tipo de arquivo inválido. Selecione um arquivo .jpg, .jpeg ou .png.");
                return;
            }
        }
    
        function readFileAsBase64(file) {
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
        }
    
        readFileAsBase64(file).then((base64Data) => {
            let requestParams = new FormData();
            requestParams.append("nomeDocumento", documentTitle);
            if (base64Data) {
                requestParams.append("documento", base64Data);
            }

            if(documentoId){
                requestParams.append("idDocumento", documentoId);
            } else {
                requestParams.append("installerId", installerId);
            }
    
            const sendButton = $("#btnSendDocumento");
            sendButton.attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>');
    
            const apiUrl = documentoId ? updateDocument : insertDocument;
    
            fetch(apiUrl, {
                method: "POST",
                body: requestParams,
            })
            .then(async (response) => {
                const dataResponse = await response.json();
    
                if (dataResponse.success) {
                    this.document();
                    document.getElementById("documentoInstaladorForm").reset();
                    showAlert("success", `Documento ${documentoId ? "atualizado" : "inserido"} com sucesso!`);
                    $("#modalEnviarDocumentoInstalador").modal("hide");

                } else if (dataResponse.message === "doc_existente") {
                    showAlert("warning", "Já existe um documento com o mesmo nome para este instalador.");
                } else {
                    showAlert("error", dataResponse.message || "Erro interno do servidor. Entre em contato com o Suporte Técnico");
                }
                sendButton.attr("disabled", false).html("Enviar");
            })
            .catch((error) => {
                showAlert("error", "Ocorreu um erro durante a requisição! Tente novamente..."  + error.message);
                sendButton.attr("disabled", false).html("Enviar");
            });
        }).catch((error) => {
            console.log(error);
            showAlert("error", "Falha ao ler o arquivo: " + error.message);
        });
    }

    editarDocumento(idDocumento, titulo){
        $(".titleDocumento").text("Editar Documento");
        $("label[for='tituloDoc'] span.text-danger").remove();
        $("label[for='arquivoItens'] span.text-danger").remove();
        $("#tituloDoc").val(titulo);
        $("#modalEnviarDocumentoInstalador").modal("show");
        $("#btnSendDocumento")
            .off()
            .on("click", () => this.handleDocument(idDocumento));
    }

    static async abrirVisualizarDocumento(documentId) {
        let requestParams = new FormData();
        requestParams.append("documentId", documentId);
    
        $(".modalLoadingMessageAct").show();
    
        await fetch(getDocumento, {
            method: "POST",
            cache: "no-cache",
            body: requestParams,
        })
            .then((response) => {
                if (!response.ok) {
                    showAlert("warning", "Documento não encontrado!");
                    return;
                }
                return response.json();
            })
            .then((data) => {
                $("#nomeDocumento").html(`${data.nomeDocumento}`);

                $("#documentDownloadBtn").off().on("click", () => {
                    ImageViewer.downloadImage(`${data.nomeDocumento}`)
                    $("#documentDownloadBtn").blur();
                });
    
                if (data["arquivoBase64"] != null) {
                    let urlImg = "data:image/png;base64," + data["arquivoBase64"];
                    if (data["status"] == 0) {
                        $("#div-img").show();
                        $("#div-img-mensagem").hide();
                        ImageViewer.preloadAndApplyWatermark(
                            `${urlImg}`,
                            BaseURL + "arq/usuarios/shownetoverlayV2.png",
                            "imgDocumentoInstalador",
                            userDataOverlay
                        );
                    } else {
                        $("#div-img").hide();
                        $("#div-img-mensagem").show();
                    }
                }
            })
            .catch((error) => {
                showAlert("error",error.message);
                $(".modalLoadingMessageAct").hide();
            });
    
        $("#modalVisualizarDocumentoInstalador").modal();
        setTimeout(() => {
            $(".modalLoadingMessageAct").hide();
        }, 200);
    }
    
    document() {
        agGridTableDocuments = new AgGridTable(
            tableDocsInstalador,
            paginationSelectDocsInstalador,
            getDocumentosInstalador,
            true,
            (key, item) => {
                if (item == null || item === "") {
                    item = "Não informado";
                }

                if (key === "dataCriacao") {
                    var date = new Date(item);
                    item = date.toLocaleDateString();
                }

                return item;
            },
            {
                installerId: Number(this.installerId),
            }
        );

        var agGridDiv = document.querySelector(tableDocsInstalador);
        agGridDiv.style.setProperty("height", "445px");

        agGridTableDocuments.updateColumnDefs([
            {
                headerName: "ID",
                field: "id",
                filter: true,
                width: 80,
            },
            {
                headerName: "Nome Documento",
                field: "nomeDocumento",
                minWidth: 290,
                flex: 0.8,
            },
            {
                headerName: "Data de Criação",
                field: "dataCriacao",
                width: 150,
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
            
                    var varAleatorio =
                        (firstRandom * secRandom).toFixed(0) +
                        "-" +
                        (thirdRandom * firstRandom).toFixed(0) +
                        "-" +
                        (secRandom * thirdRandom).toFixed(0);
            
                    let data = options.data;
            
                    let dropdownId =
                        "dropdown-menu-" + data.id + "docInstaller" + varAleatorio;
                    let buttonId =
                        "dropdownMenuButton_" + data.id + "docInstaller" + varAleatorio;
            
                    let result = `
                    <div class="dropdown dropdown-table-docs-installer" style="position: relative;">
                            <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}"
                                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL +
                        "media/img/new_icons/icon_acoes.svg"
                        }" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-funcionarios" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="#" class="btnEditarDocFunc" data-id="${data.id}" data-nome="${data.nomeDocumento}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:Document.abrirVisualizarDocumento('${data.id}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
                            </div>
                    </div>`;
            
                    setTimeout(() => {
                        document.querySelectorAll('.btnEditarDocFunc').forEach((element) => {
                            element.addEventListener('click', (e) => {
                                e.preventDefault();
                                const docId = element.getAttribute('data-id');
                                const docNome = element.getAttribute('data-nome');
                                
                                this.editarDocumento(docId, docNome);
                            });
                        });
                    }, 0);
            
                    return result;
                }.bind(this),
            }
            
            
        ]);
    }

    init() {
        new ActionButton(tableDocsInstalador, ".dropdown-table-docs-installer");

        this.document();

        var sendFunction = () => this.handleDocument();
        $("#btnSendDocumento")
                .off()
                .on("click", (e) => { 
                    e.preventDefault();
                    sendFunction();
                });

        $(document).on("click", "#btnModalAddDocumento", function () {
            $("#modalEnviarDocumentoInstalador").modal("show");
        });

        $('#modalEnviarDocumentoInstalador').on('hide.bs.modal', function (e) {
            document.getElementById("documentoInstaladorForm").reset();
            $("#titleDocInst").html("Adicionar Documento");
        });
    }
}