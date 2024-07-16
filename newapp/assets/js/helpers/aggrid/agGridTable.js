var localeText = AG_GRID_LOCALE_PT_BR;

class AgGridTable {
	constructor(tableId, paginationSelect, initialRoute, isPaginated, dataHandlerCallback, searchOptions = {}) {
		this.tableId = tableId;
		this.paginationSelect = paginationSelect;
		this.route = initialRoute;
		this.isPaginated = isPaginated;
		this.dataHandlerCallback = dataHandlerCallback;
		this.searchOptions = searchOptions;
		this.gridOptions = null;
		this.successFlag = null;
		this.initAgGrid();
	}

	getSuccess(){
		return this.successFlag;
	}

	static showLoadingSearchButton() {
		$(".search-button")
			.html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
			.attr("disabled", true);
		$(".clear-search-button").attr("disabled", true);
	}

	static resetSearchButton() {
		$(".search-button")
			.html('<i class="fa fa-search"></i> Pesquisar')
			.attr("disabled", false);
		$(".clear-search-button").attr("disabled", false);
	}

	async refreshAgGrid(searchOptions, ...funcs){
		let datasource = await this.getServerSideData(
			searchOptions,
			...funcs
		);

		if (!this.isPaginated) {
			datasource = await this.getMockData(
				searchOptions
			);
		}
		this.gridOptions.api.setServerSideDatasource(datasource);
		

		return datasource;
	}

	async getServerSideData(searchOptions, ...funcs) {
		return {
			getRows: async (params) => {
				AgGridTable.showLoadingSearchButton();
				let requestParams = new FormData();
				requestParams.append("startRow", params.request.startRow);
				requestParams.append("endRow", params.request.endRow);
				requestParams.append("searchOptions", JSON.stringify(searchOptions));

				fetch(this.route, {
					method: "POST",
					cache: "no-cache",
					body: requestParams
				})
				.then((response) => {
					if (!response.ok) {
						throw new Error(
							"Erro na rede ao buscar dados. Tente novamente."
						);
					}
					return response.json();
				})
				.then((data) => {
					if (data && data.success) {
						var rowsData = data.rows;
						if (Array.isArray(funcs)) {
							funcs.forEach(func => {
								if (typeof func === "function") {
									func(rowsData);
								}
							});
						}

						rowsData.forEach((item, index) => {
							Object.keys(item).forEach((key) => {
								rowsData[index][key] = this.dataHandlerCallback(
									key,
									item[key]
								);
							});
						});
							
						params.success({
							rowData: rowsData,
							rowCount: Number(data.lastRow),
						});

						this.successFlag = true;
					} else {
						this.gridOptions.api.showNoRowsOverlay();
						params.failCallback();
						params.success({
							rowData: [],
							rowCount: 0,
						});
						this.successFlag = false;
					}
					AgGridTable.resetSearchButton();
				})
				.catch((error) => {
					console.error("Erro ao obter dados:", error);
					this.gridOptions.api.showNoRowsOverlay();
					params.failCallback();
					AgGridTable.resetSearchButton();
				});
			},
		};
	}


	async getMockData(searchOptions, dataHandlerCallback) {
		return {
			getRows: async (params) => {
				AgGridTable.showLoadingSearchButton();

				try {
					const response = await fetch(this.route, {
						method: "POST",
						cache: "no-cache",
						headers: {
							"Content-Type": "application/json",
						},
					});

					if (!response.ok) {
						throw new Error(
							"Erro na rede ao buscar dados. Tente novamente."
						);
					}

					const data = await response.json();
					console.log(data);

					const ezMock = await EzMock.createObj(data);
					const responseMock = await ezMock.paginatedEndpointMock(
						params.request.startRow,
						params.request.endRow,
						searchOptions
					);

					if (responseMock.success) {
						var rowsData = responseMock.rows;
						rowsData.forEach((item, index) => {
							Object.keys(item).forEach((key) => {
								rowsData[index][key] = this.dataHandlerCallback(
									key,
									item[key]
								);
							});
						});
						params.success({
							rowData: rowsData,
							rowCount: responseMock.lastRow,
						});
					} else {
						this.gridOptions.api.showNoRowsOverlay();
						params.failCallback();
					}
					AgGridTable.resetSearchButton();
				} catch (error) {
					console.error("Erro ao obter dados:", error);
					this.gridOptions.api.showNoRowsOverlay();
					params.failCallback();
					AgGridTable.resetSearchButton();
				}
			},
		};
	}

	initAgGrid() {
		this.gridOptions = {
			columnDefs: [],
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
			paginationPageSize: parseInt($(this.paginationSelect).val()) || 10,
			localeText: localeText,
			rowModelType: "serverSide",
			serverSideStoreType: "partial",
			cacheBlockSize: 50,
			onGridReady: this.onGridReady.bind(this),
		};
		var gridDiv = document.querySelector(this.tableId);
		gridDiv.innerHTML = "";
		new agGrid.Grid(gridDiv, this.gridOptions);
		gridDiv.style.setProperty("height", "527px");

		$(this.paginationSelect).change(() => {
			const selectedValue = $(this.paginationSelect).val();
			this.gridOptions.api.paginationSetPageSize(Number(selectedValue));
		});
	}


	async onGridReady(params) {
		const datasource = this.isPaginated
			? await this.getServerSideData(this.searchOptions, () => { })
			: await this.getMockData(this.searchOptions, () => { });
		this.gridOptions.api.setServerSideDatasource(datasource);
		this.addEventListeners();
	}

	addEventListeners() {
		$(this.paginationSelect).change((event) => {
			const selectedValue = $(event.target).val();
			this.gridOptions.api.paginationSetPageSize(Number(selectedValue));
		});
	}

	updateColumnDefs(newDefs) {
		this.gridOptions.api.setColumnDefs(newDefs);
	}

	setRoute(newRoute) {
		this.route = newRoute;
	}
}
