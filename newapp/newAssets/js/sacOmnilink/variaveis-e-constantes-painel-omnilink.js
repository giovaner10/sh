/**
 * NESTE ARQUIVO ESTÃO DECLARADAS AS VARIÁVEIS E CONSTANTES UTILIZADAS NA VIEW 'painel-omnilink'
 */
let contratos = {
    ativos: [],
    aguardando: [],
    cancelados: [],
    suspensos: [],
}
let atividadesServico = {
    abertas: [],
    fechadas: [],
    canceladas: [],
    agendadas: []
}

// ------- DEPRECATED --------
let contratosAtivos = [], contratosAguardando = [], contratosCancelados = [], contratosSuspensos = [];
let atividadesServicoAbertas = [], atividadesServicoFechadas = [], atividadesServicoCanceladas = [], atividadesServicoAgendadas = [];
const iconSearch = '<i class="fa fa-search" aria-hidden="true"></i>';
const iconSpinner = '<i class="fa fa-spinner fa-spin"></i>';
const iconResolverOcorrencia = '<i class="fa fa-check-circle-o" aria-hidden="true"></i>';
// ------- END DEPRECATED --------

let clientEntity = null;
var tabAssociados = false;

// Icone Resolver Ocorrência

const ICONS = {
    search: '<i class="fa fa-search" aria-hidden="true"></i>',
    spinner: '<i class="fa fa-spinner fa-spin"></i>',
    resolverOcorrencia: '<i class="fa fa-check-circle-o" aria-hidden="true"></i>'
}

// constante que guarda os status dos itens de contrato
const LEGENDA_STATUS_ITEM_CONTRATO = {
	1: 'Ativo',
	2: 'Aguardando Ativação',
	3: 'Cancelado',
	4: 'Suspenso',
};
// constante que guarda o status do item de contrato para ser utilizado na filtragem
const STATUS_ITEM_CONTRATO = {
    'ativos' : 1,
    'aguardando' : 2,
    'cancelados' : 3,
    'suspensos' : 4,
};

// OBJETO UTILIZADO PARA SALVAR AUDITORIA
let auditoria = {
	valor_antigo_ocorrencia: null, valor_novo_ocorrencia: null, //AUDITORIA OCORRENCIA
	valor_antigo_cliente: null, valor_novo_cliente: null, // AUDITORIA CLIENTE
	valor_antigo_status_ocorrencia: null, valor_novo_status_ocorrencia: null,// AUDITORIA STATUS OCORRENCIA
	valor_antigo_providencia: null, valor_novo_providencia: null,// AUDITORIA PROVIDENCIA
	valor_antigo_item_contrato: null, valor_novo_item_contrato: null,// AUDITORIA ITEM DE CONTRATO
	valor_antigo_alteracao_contrato: null, valor_novo_alteracao_contrato: null,// AUDITORIA ALTERACAO DE CONTRATO
	valor_antigo_servico_contratado: null, valor_novo_servico_contratado: null,// AUDITORIA SERVICOS CONTRATADOS
	valor_antigo_base_instalada: null, valor_novo_base_instalada: null,// AUDITORIA BASE INSTALADA 
    valor_antigo_atividade_servico: null, valor_novo_atividade_servico: null,

}

// Array utilizado para construir os selects de sensores e atuadores do modal de base instalada
const SELECTS_SENSORES_ATUADORES_BASE_INSTALADA = [
    {
        name: "base_instalada_tz_ignicao",
        label: "Ignição",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_bloqueio_solenoide",
        label: "Bloqueio Solenoide",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_boto_panico",
        label: "Botão de Pânico",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_bloqueio_eletronico",
        label: "Bloqueio Eletrônico",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_painel",
        label: "Painel",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_trava_bau_traseira",
        label: "Trava Baú Traseira",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_portas_cabine",
        label: "Portas Cabine",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_trava_bau_lateral",
        label: "Trava Baú Lateral",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_bau_traseiro",
        label: "Baú Traseiro",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_trava_bau_intermediria",
        label: "Trava Baú Intermediária",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_bau_lateral",
        label: "Baú Lateral",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_trava_quinta_roda",
        label: "Trava Quinta Roda",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_bau_intermediario",
        label: "Baú Intermediário",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_teclado",
        label: "Teclado Compacto",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_engate_aspiral",
        label: "Engate Espiral",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_teclado_multimidia",
        label: "Teclado Multimídia",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_engate_eletronico",
        label: "Engate Eletrônico",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_bateria_backup",
        label: "Bateria Backup",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_temperatura",
        label: "Temperatura",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_telemetria",
        label: "Telemetria",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_sirene",
        label: "Sirene",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_painel_read_switch",
        label: "Painel Read Switch",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_setas_pulsantes",
        label: "Setas Pulsantes",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_setas_continuas",
        label: "Setas Contínuas",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_painel_micro_switch",
        label: "Painel Micro Switch",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_tipo_trava_bau",
        label: "Tipo de Trava Baú",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            1 : 'Solenoide',
            2 : 'Motorizada',
            3 : 'Inteligente'
        }
    },
    {
        name: "base_instalada_tz_imobilizador_bt5",
        label: "Imobilizador BT5",
        required: false,
        disabled: true,
        containerSize: 'col-md-6',
        options: {
            'false' : 'Não',
            'true' : 'Sim',
        }
    },
    {
        name: "base_instalada_tz_sensor_configuravel1_1",
        label: "Sensor Config. 1",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            1 : 'Início Mistura',
            2 : 'Sensor de Carreta Bi-Trem',
            3 : 'Sensor de Motorista',
            4 : 'Sensor de Painel',
            5 : 'Sensor de Para-brisa',
            6 : 'Sensor de Grade Janela',
            7 : 'Sensor de Baú Carretinha',
            8 : 'Sensor de Impacto',
            9 : 'Início Descarga',
            10 : 'Fim Descarga'
        }
    },
    {
        name: "base_instalada_tz_sensor_configuravel2_1",
        label: "Sensor Config. 2",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            1 : 'Início Mistura',
            2 : 'Sensor de Carreta Bi-Trem',
            3 : 'Sensor de Motorista',
            4 : 'Sensor de Painel',
            5 : 'Sensor de Para-brisa',
            6 : 'Sensor de Grade Janela',
            7 : 'Sensor de Baú Carretinha',
            8 : 'Sensor de Impacto',
            9 : 'Início Descarga',
            10 : 'Fim Descarga'
        }
    },
    {
        name: "base_instalada_tz_sensor_configuravel3_1",
        label: "Sensor Config. 3",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            1 : 'Início Mistura',
            2 : 'Sensor de Carreta Bi-Trem',
            3 : 'Sensor de Motorista',
            4 : 'Sensor de Painel',
            5 : 'Sensor de Para-brisa',
            6 : 'Sensor de Grade Janela',
            7 : 'Sensor de Baú Carretinha',
            8 : 'Sensor de Impacto',
            9 : 'Início Descarga',
            10 : 'Fim Descarga'
        }
    },
    {
        name: "base_instalada_tz_sensor_configuravel4_1",
        label: "Sensor Config. 4",
        required: false,
        disabled: false,
        containerSize: 'col-md-6',
        options: {
            1 : 'Início Mistura',
            2 : 'Sensor de Carreta Bi-Trem',
            3 : 'Sensor de Motorista',
            4 : 'Sensor de Painel',
            5 : 'Sensor de Para-brisa',
            6 : 'Sensor de Grade Janela',
            7 : 'Sensor de Baú Carretinha',
            8 : 'Sensor de Impacto',
            9 : 'Início Descarga',
            10 : 'Fim Descarga'
        }
    }
];

// Array utilizado para construir os selects de cliente do modal de base instalada
const SELECTS_CLIENTES_BASE_INSTALADA = [
    {
        name: "base_instalada_tz_cliente_pjid",
        class: "accounts",
        label: "Cliente PJ (Contrato)",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
    {
        name: "base_instalada_tz_cliente_pfid",
        class: "contacts",
        label: "Cliente PF (Contrato)",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
    {
        name: "base_instalada_tz_cliente_pj_matrizid",
        class: "accounts",
        label: "Cliente PJ (Matriz)",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
    {
        name: "base_instalada_tz_cliente_pf_matrizid",
        class: "contacts",
        label: "Cliente PF (Matriz)",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
    {
        name: "base_instalada_tz_cliente_pj_instaladoid",
        class: "accounts",
        label: "Cliente PJ (Instalado)",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
    {
        name: "base_instalada_tz_cliente_pf_instaladoid",
        class: "contacts",
        label: "Cliente PF (Instalado)",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
    {
        name: "base_instalada_tz_cliente_anterior_pj",
        class: "accounts",
        label: "Cliente Anterior PJ",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
    {
        name: "base_instalada_tz_cliente_anterior_pf",
        class: "contacts",
        label: "Cliente Anterior PF",
        required: false,
        disabled: false,
        containerSize: 'col-md-6'
    },
];


/**
 * Arquivo onde está definido as funções do painel Omnilink
 */

 let atividadesDeServico = {
	0 : [],
	1 : [], 
	2 : [], 
	3 : [],
    4 : [] 
}
/**
 * Objeto que guarda statuscode e statecode das atividades de serviço
 */
const statusAtividadesDeServico = {
	0 : [1,2], // abertas
	1 : [8,419400000,419400001,419400002,419400003,419400004,419400005,419400006,419400007,419400008],// fechadas
	2 : [419400010,9,10,419400009],// canceladas
	3 : [3,4,6,7],// canceladas
}
const legendasStateCodeAtividadeDeServico = {
	0: "Aberto",
	1: "Fechado",
	2: "Cancelado",
	3: "Agendado"
}
/**
 * Descrição da razão do status da atividade de serviço
 */
const legendaStatusCodeAtividadesDeServico = {
	// Aberto
	1: 'Solicitado',
	2: 'Provisório',
	// Fechado
	8: 'Completo - Serviço Realizado',
	419400000: 'Completo - No Show Cliente (Falta ou Atraso do Cliente)',
	419400001: 'Completo - Orçamento Não Aprovado',
	419400002: 'Incompleto - No Show Logística (Falta de Peças com Visita)',
	419400003: 'Incompleto - No Show Logística (Falta de Peças sem Visita)',
	419400004: 'Incompleto - No Show Técnico',
	419400005: 'Incompleto - DOA (Peça Recebida com Defeito)',
	419400006: 'On Going - Orçamento Não Aprovado (Prazo 45 minutos)',
	419400007: 'Incompleto - Mudança Agendamento (Zatix)',
	419400008: 'Erro de Agendamento (Zatix)',
	// Cancelada
	419400010: 'Cancelamento Portal do Cliente',
	9: 'Veículo Indisponível',
	10: 'Problema Solucionado Pelo Cliente',
	419400009: '*** NÃO UTILIZAR *** Fechamento - Admnistrativo (N.A não finalizada pelo técnico)',
	// Agendado
	3: 'Pendente',
	4: 'Reservado',
	6: 'Aguardando aprovação orçamento',
	7: 'Chegou',
};
// Objeto que controla o botão e status da tabela da atividade de serviço
var ControleTableStatusAtividadeDeServico = {
	btnActive: null,
	statusAnterior: null,
}