const gridHistoricoDiv = document.querySelector("#grid-historico");

const acaoHistorico = {
  cadastrar: 'Cadastrou a solicitação',
  remover: 'Removeu a solicitação',
  editar: 'Editou a solicitação',
  adicionar_cotacao: 'Adicionou cotação à solicitação',
  adicionar_produto_cotacao: 'Adicionou produto e cotação à solicitação',
  selecionar_cotacao: 'Selecionou cotação',
  aprovar: 'Aprovou a solicitação',
  reprovar: 'Reprovou a solicitação',
  comentar: 'Comentou na solicitação',
  adicionar_boleto: 'Adicionou boleto à solicitação',
  adicionar_pre_nota: 'Adicionou pré-nota a solicitação'
}


// Colunas da AgGrid de Historico
function columnDefsGridHistorico() {

  let defs = [
    { field: "id", headerName: "Id", width: 60, sort: 'desc' },
    {
      field: "usuario", headerName: "Usuário", flex: 1, minWidth: 250,
      cellRenderer: ({ data: { nomeUsuario, emailUsuario } }) => { 
        return /* HTML */`
        <div class="">
          <h6 style="line-height: .5em;">${nomeUsuario}</h6>
          <h6 style="line-height: .15em; font-weight:normal">${emailUsuario}</h6>
        </div>`;
      },
      valueGetter: ({ data: { nomeUsuario, emailUsuario } }) => { 
        return /* HTML */`
        <div class="">
          <h6 style="line-height: .5em;">${nomeUsuario}</h6>
          <h6 style="line-height: .15em; font-weight:normal">${emailUsuario}</h6>
        </div>`;
      },
    },
    { field: "funcaoUsuario", headerName: "Função do Usuário", flex: 1, minWidth: 180,
      cellRenderer: ({ data: { funcaoUsuario } }) => { return funcao[funcaoUsuario] || 'Nenhuma função encontrada'},
      valueGetter: ({ data: { funcaoUsuario } }) => { return funcao[funcaoUsuario] || 'Nenhuma função encontrada'}
    },
    {
      field: "acao", headerName: "Ação Realizada", flex: 1, minWidth: 180,
      cellRenderer: ({ data: { acao } }) => { return acaoHistorico[acao] || 'Nenhuma ação encontrada'},
      valueGetter: ({ data: { acao } }) => { return acaoHistorico[acao] || 'Nenhuma ação encontrada' }
    },
    {
      field: "datahoraCadastro", headerName: "Data da Ação", flex: 1, minWidth: 180,
      cellRenderer: ({ data: { datahoraCadastro } }) => {
        return datahoraCadastro ? dayjs(datahoraCadastro).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      },
      valueGetter: ({ data: { datahoraCadastro } }) => {
        return datahoraCadastro ? dayjs(datahoraCadastro).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      }
    },
  ];

  return defs;
}

const gridHistoricoOptions = {
  localeText: AG_GRID_TRANSLATE_PT_BR,
  columnDefs: columnDefsGridHistorico(),
  rowData: [],
  animateRows: true,
  defaultColDef: {
    editable: false,
    resizable: true,
    sortable: false,
    suppressMenu: true,
  },
  getRowId: (params) => params.data.id,
  overlayLoadingTemplate: mensagemCarregamentoAgGrid,
  overlayNoRowsTemplate: mensagemAgGridSemDados,
  pagination: false,
};

new agGrid.Grid(gridHistoricoDiv, gridHistoricoOptions);

async function buscarHistoricoDaSolicitacao() {
  const url = `${SITE_URL}/PortalCompras/LogSolicitacoes/listarParaSolicitacao/${idSolicitacao}`;
  return await axios.get(url)
    .then(response => {
      const data = response.data;

      if (data.status != '1') {
        return showAlert('error', data.mensagem);
      }

      const logs = data.logs;
      gridHistoricoOptions.api.setRowData(logs);
    })
    .catch(error => {
      gridHistoricoOptions.api.setRowData([]);
      console.error(error);
      showAlert('error', 'Não foi possível carregar o histórico. Tente novamente em alguns minutos!');
    });
}