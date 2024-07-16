
//remessas de salarios
const checboxSalarios = {};

//remessas salarios instaladores sem erros
const checkboxRemessa = {};
const checkboxRemessaTed = {};

//remessas salarios instaladores com erros 
const checkboxRemessaErros = {};
const checkboxRemessaTedErros = {};


//remessas salarios instaladores com erros 
const fornecedoresAbaInput = {};
const fornecedoresAbaInputTed = {};

// aba de titulos
const titulosAbaInput = {};

// aba de guias
const guiasAbaInput = {};



let remessasdeSalarios = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_instaladores[]'; 
      input.value = d.conta_id; 
      input.checked = checboxSalarios[params.data.conta_id] || false;  

      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checboxSalarios[params.data.conta_id] = e.target.checked; 
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
    headerName: "Funcionário",
    field: "titular",
    chartDataType: "category",
    width: "350px",
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "900px",
  },
];

let remessainstaladoresComErros = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_instaladores[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxRemessaErros[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxRemessaErros[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
      });
      return input;
    }
  },
  {
    headerName: "Descontar TED",
    cellRenderer: function (params) {

      if(checkboxRemessaTedErros[params.data.conta_id] == undefined){
        checkboxRemessaTedErros[params.data.conta_id] = !(params.data.banco == "001");
      }

      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'descontar_id_contas_instaladores[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxRemessaTedErros[params.data.conta_id];
      input.disabled  = params.data.banco == "001";


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxRemessaTedErros[params.data.conta_id] = !checkboxRemessaTedErros[params.data.conta_id]; 
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
      <span title="CPF/CPNJ: ${d.cpf}, Banco: ${d.banco}, Agência: ${d.agencia}, Conta: ${d.conta}">${d.fornecedor}</span>
      `;
    }
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Banco",
    field: "banco",
    chartDataType: "category",
    width: "900px",
  },
];


let remessainstaladores = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_instaladores[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxRemessa[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxRemessa[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
      });
      return input;
    }
  },
  {
    headerName: "Descontar TED",
    cellRenderer: function (params) {

      if(checkboxRemessaTed[params.data.conta_id] == undefined){
        checkboxRemessaTed[params.data.conta_id] = !(params.data.banco == "001");
      }

      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'descontar_id_contas_instaladores[]'; 
      input.value = d.conta_id; 
      input.checked = checkboxRemessaTed[params.data.conta_id];
      input.disabled  = params.data.banco == "001";


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        checkboxRemessaTed[params.data.conta_id] = !checkboxRemessaTed[params.data.conta_id]; 
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
      <span title="CPF/CPNJ: ${d.cpf}, Banco: ${d.banco}, Agência: ${d.agencia}, Conta: ${d.conta}">${d.fornecedor}</span>
      `;
    }
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Banco",
    field: "banco",
    chartDataType: "category",
    width: "900px",
  },
];

let remessasFornecedores = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_fornecedores[]'; 
      input.value = d.conta_id; 
      input.checked = fornecedoresAbaInput[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        fornecedoresAbaInput[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
      });
      return input;
    }
  },
  {
    headerName: "Descontar TED",
    cellRenderer: function (params) {


      if(fornecedoresAbaInputTed[params.data.conta_id] == undefined){
        fornecedoresAbaInputTed[params.data.conta_id] = !(params.data.banco == "001");
      }
  
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'descontar_id_contas_fornecedores[]'; 
      input.value = d.conta_id; 
      input.checked = fornecedoresAbaInputTed[params.data.conta_id];
      input.disabled  = params.data.banco == "001";


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        fornecedoresAbaInputTed[params.data.conta_id] = !fornecedoresAbaInputTed[params.data.conta_id]; 
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
      <span title="CPF/CPNJ: ${d.cpf}, Banco: ${d.banco}, Agência: ${d.agencia}, Conta: ${d.conta}">${d.fornecedor}</span>
      `;
    }
  },
  {
    headerName: "Valor",
    field: "valor",
    chartDataType: "category",
    width: "300px",
  },
  {
    headerName: "Desconto",
    field: "banco",
    chartDataType: "category",
    width: "900px",
  },
];

remessasTitulos = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_titulos[]'; 
      input.value = d.conta_id; 
      input.checked = titulosAbaInput[params.data.conta_id] || false;  // Uso do conta_id para verificar o estado


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        titulosAbaInput[params.data.conta_id] = e.target.checked;  // Atualiza o estado usando conta_id
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

remessasGuias = [
  {
    headerName: "Selecionar",
    cellRenderer: function (params) {
      d = params.data;
      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'id_contas_guia[]'; 
      input.value = d.conta_id; 
      input.checked = guiasAbaInput[params.data.conta_id] || false;  


      input.addEventListener('click', (e) => {
        e.stopPropagation();
        guiasAbaInput[params.data.conta_id] = e.target.checked;  
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


