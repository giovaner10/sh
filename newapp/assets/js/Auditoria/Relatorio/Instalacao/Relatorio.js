const tableId = "#table";
const paginationSelect = "#paginationSelect";
const getListarRelatorioTecnicos =
	Router + "/listarUltimosCemRelatorioTecnicos";

let agGridTable;



class Relatorio {
	static abaRelatorio() {
		agGridTable = new AgGridTable(
			document.getElementById("wrapper"),
			tableId,
			paginationSelect,
			getListarRelatorioTecnicos,
			false
		);

		agGridTable.updateColumnDefs([
			{
				headerName: "Nome do Técnico",
				field: "nomeTecnico",
				filter: true,
				minWidth: 220,
				flex: 1,
			},
			{
				headerName: "Ação",
				field: "acao",
				width: 120,
				minWidth: 90,
				cellRenderer: function (params) {
					var acao = params.data.acao;

					switch (acao) {
						case "OPEN":
							acao =
								'<span class="badge badge-light">Abertura</span>';
							break;
						case "CLOSE":
							acao =
								'<span class="badge badge-danger">Fechamento</span>';
							break;
						case "ATTEMPT":
							acao =
								'<span class="badge badge-warning">Tentativa</span>';
							break;
						case "SUCCESS":
							acao =
								'<span class="badge badge-primary">Sucesso</span>';
							break;
						case "CANCEL":
							acao =
								'<span class="badge badge-canceled">Cancelado</span>';
							break;
						default:
							acao =
								'<span class="badge badge-secondary">Indefinido</span>';
							break;
					}

					return acao;
				},
			},
			{
				headerName: "Descrição",
				field: "descricao",
				minWidth: 220,
				flex: 1,
			},
			{
				headerName: "Quantidade",
				field: "quantidade",
				width: 115,
			},
		]);
	}

	static abaDashboard() {
		
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
						$("#conteudo")
							.removeClass("col-md-9")
							.addClass("col-md-12");
					});
				} else {
					$(".img-expandir").attr("src", buttonHide);
					$("#conteudo")
						.removeClass("col-md-12")
						.addClass("col-md-9");
					setTimeout(() => {
						$(".col-md-3").fadeIn(250);
					}, 510);
				}
			}

			Relatorio.abaRelatorio();
		});

		let searchOptions = {
			nomeTecnico: null,
			dataInicio: null,
			dataFim: null,
		};

		$(".search-button").click(async function (e) {
			e.preventDefault();

			if ($("#selectPesqTecnico").val()) {
				searchOptions.nomeTecnico = $("#selectPesqTecnico").val();
			} else if ($("#dataInicial").val()) {
				searchOptions.nomeTecnico = $("#dataInicial").val();
			} else if ($("#dataFinal").val()) {
				searchOptions.nomeTecnico = $("#dataFinal").val();
			}

			const datasource = await agGridTable.getMockData(
				searchOptions,
				() => {}
			);
			agGridTable.gridOptions.api.setServerSideDatasource(datasource);
		});

		$(".clear-search-button").click(async function () {
			const datasource = await agGridTable.getMockData({}, () => {});
			agGridTable.gridOptions.api.setServerSideDatasource(datasource);
		});

		$("#Relatorio-tab").on("click", function () {
			if (!$(this).hasClass("selected")) {
				$(this).addClass("selected");
				$("#Dashboard-tab").removeClass("selected");
				$(".card-dashboard-instalacao").hide();
				$(".card-relatorio-instalacao").show();
			}
		});

		$("#Dashboard-tab").on("click", function () {
			if (!$(this).hasClass("selected")) {
				$(this).addClass("selected");
				$("#Relatorio-tab").removeClass("selected");
				$(".card-relatorio-instalacao").hide();
				$(".card-dashboard-instalacao").show();
			}
		});
	}
}

Relatorio.init();
