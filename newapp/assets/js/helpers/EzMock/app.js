class EzMock {
	constructor(dataPromise) {
		this.dataPromise = dataPromise;
	}

	static async create(dataUrl, type = "object") {
		var json = dataUrl;
		if (type == "link") {
			const response = await fetch(dataUrl);
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			json = await response.json();
			json = json.data;
		}
		return new EzMock(Promise.resolve(json));
	}

	async paginatedEndpointMock(startRow = 1, endRow = 25, params = null) {
		const data = await this.dataPromise;
		const lastRow = data.length - 1;
		let chunk = data.slice(startRow, endRow);

		if (params !== null) {
			chunk = chunk.filter((item) =>
				Object.keys(params).every(
					(paramKey) => item[paramKey] === params[paramKey]
				)
			);
		}

		const response = {
			success: true,
			rows: chunk,
			lastRow: lastRow,
		};
		return response;
	}

	static createObj(dataUrl) {
		return new EzMock(Promise.resolve(dataUrl));
	}
}
