// colunas_instaladores
const checkboxInstal = {};

// colunas_forn
const checkboxFornecedores = {};
const checkboxFornecedoresTed = {};

// colunas_titulos
const checkboxTitulo = {};

// colunas_guia
const checkboxGuia = {};



let colunas_instaladores = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_fornecedores[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxInstal[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxInstal[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
      });
      return input;
    }
  },
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      return `
      <input type="checkbox" name="id_contas_instaladores[]" value="${d.conta_id}"></input>
      `;
    }
  },
  {
    headerName: "ID Conta",
    field: "conta_id",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Vencimento",
    field: "data_vencimento",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Fornecedor",
    field: "fornecedor",
    cellRenderer: function (params) {
      d = params.data;
      return `
      <span title="CPF/CPNJ: ${d.cpf}, Banco: ${d.banco}, Agência: ${d.agencia}, Conta: ${d.conta}">${d.fornecedor}</span>
      `;
    }
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "900px",
  },
];



let colunas_forn = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_fornecedores[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxFornecedores[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxFornecedores[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
      });
      return input;
    }
  },
  {
    headerName: "Descontar TED",
    cellRenderer: function (params) {

      if(checkboxFornecedoresTed[params.data.conta_id] == undefined){
        checkboxFornecedoresTed[params.data.conta_id] = !(params.data.banco == "001");
      }

      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'descontar_id_contas_fornecedores[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxFornecedoresTed[params.data.conta_id];
      input.disabled  = params.data.banco == "001";


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxFornecedoresTed[params.data.conta_id] = !checkboxFornecedoresTed[params.data.conta_id]; 
      });
      return input;
    }
  },
  {
    headerName: "ID Conta",
    field: "conta_id",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Vencimento",
    field: "data_vencimento",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Fornecedor",
    field: "fornecedor",
    cellRenderer: function (params) {
      d = params.data;
      return `
      <span title="CPF/CPNJ: ${d.cpf}, Banco: ${d.banco}, Agência: ${d.agencia}, Conta: ${d.conta}">${d.fornecedor}</span>
      `;
    }
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "200px",
  },
];



let colunas_titulos = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_titulos[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxTitulo[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxTitulo[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
      });
      return input;
    }
  },
  {
    headerName: "ID Conta",
    field: "conta_id",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Fornecedor",
    field: "fornecedor",
    cellRenderer: function (params) {
      d = params.data;
      return `
      <span title="Código de barras: ${d.codigo_barra}">${d.fornecedor}</span>
      `;
    }
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "900px",
  },
];

let colunas_guias = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_guia[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxGuia[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxGuia[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
      });
      return input;
    }
  },
  {
    headerName: "ID Conta",
    field: "conta_id",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Fornecedor",
    field: "fornecedor",
    cellRenderer: function (params) {
      d = params.data;
      return `
      <span title="Código de barras: ${d.codigo_barra}">${d.fornecedor}</span>
      `;
    }
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "900px",
  },
];
