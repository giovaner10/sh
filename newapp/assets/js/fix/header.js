/**
 * Instancia do AXIOS para requisicoes a NodeJs
*/
var BASE_URL_NODE_INTERNA = ''
var TOKEN_SHOWNET = ''

$.ajax({
    url: document.location.origin + '/shownet/newapp/index.php/api/retornaUrlTokenNode',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        BASE_URL_NODE_INTERNA = data.url;
        TOKEN_SHOWNET = data.token;
    }
})

const axiosNode = axios.create({
    baseURL: BASE_URL_NODE_INTERNA,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Access-Control-Allow-Headers': 'x-access-token',
        'x-access-token': TOKEN_SHOWNET,
        'Access-Control-Allow-Origin': '*',
    }
});

/**
 * Instancia do AXIOS para requisicoes a NodeJs no tipo form-data
*/
const axiosNodeFormData = axios.create({
    baseURL: BASE_URL_NODE_INTERNA,
    headers: {
        'Content-Type': 'multipart/form-data',
        'Accept': 'application/json',
        'Access-Control-Allow-Headers': 'x-access-token',
        'x-access-token': TOKEN_SHOWNET,
        'Access-Control-Allow-Origin': '*',
    }
});

function efeitoBuscarDadosDatatable(idTabela) {
    $(`#${idTabela} > tbody`).html(
        `<tr class="odd">
            <td valign="top" colspan="12" class="dataTables_empty">${lang.carregando}</td>
        </tr>`
    );
}

/**
 * Instancia do AXIOS para consultas à Api televendas
*/
const axiosTelevendas = axios.create({
	baseURL: BASE_URL_API_TELEVENDAS,
	headers: {
		'Content-Type': 'application/json',
		'Accept': 'application/json',
		'Authorization': 'Bearer ' + TOKEN_URL_API_TELEVENDAS,
		'Access-Control-Allow-Origin': '*',
	},
});

/**
 * Funcao para aguardar um tempo
 * @param {number} tempo - Tempo em segundos para aguardar
 * @returns {Promise}
 * @example
 * await sleep(2);
**/
async function sleep(tempo) {
	return new Promise(resolve => setTimeout(resolve, tempo * 1000));
}

var tokenTwilio = sessionStorage.getItem('tokenTwilio');
var tokenTwilioAtendimento = sessionStorage.getItem('tokenTwilioAtendimento');
