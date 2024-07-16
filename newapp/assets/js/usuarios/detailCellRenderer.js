function _optionalChain(ops) {
	let lastAccessLHS = undefined;
	let value = ops[0];
	let i = 1;
	while (i < ops.length) {
		const op = ops[i];
		const fn = ops[i + 1];
		i += 2;
		if (
			(op === "optionalAccess" || op === "optionalCall") &&
			value == null
		) {
			return undefined;
		}
		if (op === "access" || op === "optionalAccess") {
			lastAccessLHS = value;
			value = fn(value);
		} else if (op === "call" || op === "optionalCall") {
			value = fn((...args) => value.call(lastAccessLHS, ...args));
			lastAccessLHS = undefined;
		}
	}
	return value;
}
class DetailCellRenderer {
	init(params) {
		this.params = params;
		var eTemp = document.createElement("div");
		eTemp.innerHTML = this.getTemplate(params.data);
		this.eGui = eTemp.firstElementChild;
		this.setupDetailGrid();
	}

	getTemplate(data) {
		const themeClass = "ag-theme-material";
		return `
            <div style="max-height: 260px !important; ">
                <h5 style="margin: 10px;">Dados individuais:</h5>
                <div class="${themeClass} full-width-grid" style="border: none;"></div>
            </div>`;
	}

	getGui() {
		return this.eGui;
	}

	refresh(params) {
		return false;
	}

	destroy() {
		var rowId = this.params.node.id;
		var masterGridApi = this.params.api;
		masterGridApi.removeDetailGridInfo(rowId);

		this.detailGridApi.destroy();
	}
}
