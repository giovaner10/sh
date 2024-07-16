//Cargos
const listCargos = Router + "/ajaxListCargos";

// Ferias
const aplicarFeriasFuncionario = Router + "/aplicarFeriasFuncionario";
const cadAttFuncionariosLote = Router + "/cadAttFuncionariosLote";

// Funcionario
const addFuncionario = Router + "/addFuncionario";
const editFuncionario = Router + "/editFuncionario";
const loadFuncionarios = Router + "/loadFuncionariosServerSide";
const mudarStatusFuncionario = Router + "/mudarStatusFuncionario";
const getFuncionario = Router + "/ajaxGetFuncionario";
const getFuncionariosByName = Router + "/loadFuncionariosByNameServerSide";

// Documentos Funcionario
const getDocumentosFuncionario = Router + "/getDocumentosFuncionario";
const getDocumento = Router + "/getDocumento";
const insertDocumento = Router + "/insertDocumento";
const atualizarDocumento = Router + "/atualizarDocumento";

// Tipos de Documento
const getAllDocumentTypes = Router + "/getAllDocumentTypes";

// Associação de Documento
const getAllDocumentAssociations = Router + "/getAllDocumentAssociations";
const updateAssociacaoDocumento = Router + "/updateAssociacaoDocumento";
const insertAssociacaoDocumento = Router + "/insertAssociacaoDocumento";

//Conta Bancária
const addContaBancaria = Router + "/addContaBancaria";
const editContaBancaria = Router + "/editContaBancaria";
const listContasBancariasFuncionario = Router + "/listContasBancariasFuncionario";
const tornaContaBancariaPrincipal = Router + "/tornaContaBancariaPrincipal";

const tableContasBancariasList = "#tableContasBancariasList";
const paginationSelectContasBancarias = "#paginationSelectContasBancarias";
const listContasBancariasFuncionarioServerSide = Router + "/listContasBancariasFuncionarioServerSide";

// Demissão
const inserirDadosDemissao = Router + "/inserir_Dados_De_Demissao";
const buscarDemissao = Router + "/buscarDemissao";
const demitirFuncionario = Router + "/demitirFuncionario";

var localeText = AG_GRID_LOCALE_PT_BR;
