const gridDiv = document.querySelector("#grid-filas");
let larguraDaTela = window.innerWidth;

function monitorarLarguraTela() {
  larguraDaTela = window.innerWidth;
}
window.addEventListener('resize', monitorarLarguraTela);
monitorarLarguraTela();

// Renderiza a coluna de ações
function renderizarColunaAcoes(dados) {
	const { data } = dados;
	
	let botaoVincularAgentes = '';
	let botaoEditarFila = '';
	let botaoRemoverFila = '';

  if (permissaoVincularAgentesNaFila) {
    botaoVincularAgentes = /* HTML */`
			<a class="dropdown-item dropdown-item-acoes vincular-agente" style="cursor: pointer; text-decoration: none !important; width: 100%; display: block;" 
				data-id="${data.id}">
				Vincular Agentes
			</a>`;
	}

	if (permissaoEditarFilas) {
		botaoEditarFila = /* HTML */`
			<a class="dropdown-item dropdown-item-acoes editar-fila" style="cursor: pointer; text-decoration: none !important; width: 100%; display: block;" 
				data-id="${data.id}">
				${lang.editar}
			</a>`;
	}

	if (permissaoRemoverFilas) {
		botaoRemoverFila = /* HTML */`
        <a class="dropdown-item dropdown-item-acoes remover-fila" style="cursor: pointer; text-decoration: none !important; width: 100%; display: block;"
          data-id="${data.id}">
          ${lang.remover}
        </a>
      `;
	}

	return /* HTML */`
    <div class="dropdown dropdown_${data.id}">
      <button
        class="btn btn-light btn-sm"
        type="button"
        id="dropdownMenuButton_${data.id}"
        row_id="${data.id}"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
        style="margin-top:-6px; width:35px"
      >
        <img
          src="${BASE_URL + 'media/img/new_icons/icon_acoes.svg'}"
          alt="${lang.acoes}"
        />
      </button>

      <div class="dropdown-menu dropdown-menu-right shadow"aria-labelledby="dropdownMenuButton_${data.id}">
        ${botaoVincularAgentes}
        ${botaoEditarFila}
        ${botaoRemoverFila}
      </div>
    </div>`;
}

// Colunas da AgGrid
function columnDefs() {

  let defs = [
    { field: "id", headerName: "ID", flex: 1, minWidth: 100, sort: 'desc' },
    { field: "nome", headerName: "Nome", flex: 1, minWidth: 200 },
    { field: "descricao", headerName: "Descrição", flex: 1, minWidth: 200 },
    { field: "diaInicial", headerName: "Dia Inicial", flex: 1, minWidth: 100 },
    { field: "diaFinal", headerName: "Dia Final", flex: 1, minWidth: 100 },
    { field: "horarioInicial", headerName: "Horário Inicial", flex: 1, minWidth: 100 },
    { field: "horarioFinal", headerName: "Horário Final", flex: 1, minWidth: 100 },
    {
      field: "dataCadastro", headerName: "Data de Cadastro", flex: 1, minWidth: 180,
      cellRenderer: ({ data: { dataCadastro } }) => {
        return dataCadastro ? dayjs(dataCadastro).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      },
      valueGetter: ({ data: { dataCadastro } }) => {
        return dataCadastro ? dayjs(dataCadastro).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      }
    },
    {
      field: "dataModificacao", headerName: "Data de Modificação", flex: 1, minWidth: 180,
      cellRenderer: ({ data: { dataModificacao } }) => {
        return dataModificacao ? dayjs(dataModificacao).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      },
      valueGetter: ({ data: { dataModificacao } }) => {
        return dataModificacao ? dayjs(dataModificacao).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      }
    },
  ];

  if (permissaoEditarFilas || permissaoRemoverFilas || permissaoVincularAgentesNaFila) {
		defs.push({
			field: "acoes",
			headerName: lang.acoes,
			width: 85,
			cellRenderer: renderizarColunaAcoes,
			cellClass: "actions-button-cell",
			pinned: larguraDaTela <= 390 ? '' : 'right',
			sortable: false,
			sort: false,
			resizable: false,
			pdfExportOptions: { skipColumn: true },
		});
	}

  return defs;
}

const gridOptions = {
  localeText: AG_GRID_TRANSLATE_PT_BR,
  columnDefs: columnDefs(),
  rowData: [],
  animateRows: true,
	sortingOrder: ['desc', 'asc'],
	accentedSort: true,
  defaultColDef: {
		flex: 1,
		editable: false,
		resizable: true,
		sortable: true,
		suppressMenu: true,
  },
  getRowId: (params) => params.data.id,
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
        },
      },
    ],
    defaultToolPanel: false,
  },
  overlayLoadingTemplate: mensagemCarregamentoAgGrid,
  overlayNoRowsTemplate: mensagemAgGridSemDados,
  pagination: true,
  paginationPageSize: 10,
};

new agGrid.Grid(gridDiv, gridOptions);
carregarGridFilas();

function carregarGridFilas() {
  const url = `${SITE_URL}/AtendimentoOmnilink/Filas/listar`;

  gridOptions.api.showLoadingOverlay();
  axios
    .get(url)
    .then((response) => {
      const data = response.data;
      gridOptions.api.setRowData(data);
    })
    .catch((error) => {
      console.error(error);
      gridOptions.api.setRowData([]);
			showAlert('error', lang.erro_inesperado);
    })
    .finally(() => {
      gridOptions.api.hideOverlay();
    });
}

function resetarCamposModalFila() {
	$('#titulo-modal-fila').text('Cadastrar Fila');
	$('#btn-salvar-fila').attr('data-acao', 'cadastrar');
	$('#btn-salvar-fila')
		.removeAttr('data-id')

	$('#form-fila').trigger('reset');

	// Remover as mensagens de erro
	removerErrorFormulario('form-fila');
}

// Salvar Fornecedor
async function salvarFila() {
	const botao = $('#btn-salvar-fila');
	const acao = botao.attr('data-acao');
  const id = botao.attr('data-id');
	const formData = new FormData($("#form-fila")[0]);

	let url = `${SITE_URL}/AtendimentoOmnilink/Filas/cadastrar`;
	if (acao === 'editar') url = `${SITE_URL}/AtendimentoOmnilink/Filas/editar/${id}`;

	botao
		.prop('disabled', true)
		.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;
			if (data.status == 1) {
				if (acao === 'editar') {
					//atualizar registro na agGrid
          const node = buscarNodeNaAgGrid(id);
					const registro = {
						id: id,
						nome: formData.get('nome'),
						descricao: formData.get('descricao'),
            diaInicial: formData.get('dia_inicial'),
            diaFinal: formData.get('dia_final'),
            horarioInicial: formData.get('horario_inicial'),
            horarioFinal: formData.get('horario_final'),
						dataCadastro: node.data.dataCadastro,
						dataModificacao: dayjs().format('YYYY-MM-DD HH:mm:ss'),
					};

          atualizarRegistroNaAgGrid(node.rowIndex, registro);
				}
				else if (acao === 'cadastrar') {
					//novo registro na agGrid
					const novoRegistro = {
						id: data.idNovaFila,
						nome: formData.get('nome'),
						descricao: formData.get('descricao'),
            diaInicial: formData.get('dia_inicial'),
            diaFinal: formData.get('dia_final'),
            horarioInicial: formData.get('horario_inicial'),
            horarioFinal: formData.get('horario_final'),
						dataCadastro: dayjs().format('YYYY-MM-DD HH:mm:ss'),
						dataModificacao: ''
					};

					adicionarRegistroNaAgGrid([novoRegistro]);
				}

				showAlert('success', data.mensagem);
				$('#modal-cadastrar-editar-fila').modal('hide');
			}
			else {
				showAlert('error', data.mensagem);
			}
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		})
		.finally(() => {
			botao.prop('disabled', false).html('Salvar');
		});
} 