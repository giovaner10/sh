
function limparModalAvaliacaoCliente() {
	// Limpar os textos do resultado da avaliação
	$('#resultado-avaliacao-cliente').text('');
	$('#descricao-resultado-avaliacao-cliente').text('');

	// Limpar os textos da consulta
	$('#div-dados-consultados-avaliacao-clientes').html('');

	$('#loadingAvaliacaoCliente').removeClass('hide');
	$('#conteudo-avaliacao-cliente').addClass('hide');
	
	$('#divSemConteudoAvaliacao').removeClass('hide');
	$('#divConteudoAvaliacao').addClass('hide');
}

async function consultarAvaliacaoCliente(idCotacao) {
	// Consultar a avaliação do cliente
	return await fetch(`${SITE_URL}/ComerciaisTelevendas/Pedidos/consultarAvaliacaoCliente/${idCotacao}`, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json'
		}
	})
		.then(response => response.json())
		.catch(error => console.log('Error:', error));
}

async function carregarDadosAvaliacaoCliente(idCotacao) {

	// Limpar o modal
	limparModalAvaliacaoCliente();

	// Consultar a avaliação do cliente
	var resposta = await consultarAvaliacaoCliente(idCotacao);

	$('#loadingAvaliacaoCliente').addClass('hide');
	$('#conteudo-avaliacao-cliente').removeClass('hide');

	if (resposta.message) {
		console.error(resposta.message);
		return;
	}

	if (resposta.evaluation.status === 'pendente') {
		return;
	}

	$('#divSemConteudoAvaliacao').addClass('hide');
	$('#divConteudoAvaliacao').removeClass('hide');

	// Setar o resultado
	let resultado = 'Não há resultado para a avaliação.';
	if (resposta.evaluation?.resultado) {
		resultado = resposta.evaluation?.resultado == 'aprovado' ? 'Aprovado' : 'Reprovado';
	}
	$('#resultado-avaliacao-cliente').text(`${ resultado } (Consultado no Omniscore)`);
	$('#data-avaliacao-cliente').text(`${resposta.profile?.dataConsulta ? dayjs(resposta.profile?.dataConsulta).format('DD/MM/YYYY HH:mm:ss') : 'Não informado'}`);

	// Setar os dados consultados
	let htmlDadosConsulta = /*html*/`<div class="col-md-6 form-group bord">
		<span>O perfil do cliente ainda está sendo avaliado, Não há dados para exibir no momento.</span>
	</div>`;

	// Verificar se o cliente é pessoa física ou jurídica
	if (resposta.profile?.tipoPerfil == 'pessoa_fisica') {
		const dadosConsulta = resposta.profile?.consultas?.consultaCpf;
		const cpf = dadosConsulta?.cpf ? criarMascara(dadosConsulta?.cpf, '###.###.###-##') : 'Não informado';
		htmlDadosConsulta = /*html*/`
		<div class="col-md-6 form-group bord">
			<label>CPF: </label>
			<span>${cpf}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Nome: </label>
			<span>${dadosConsulta?.nome || 'Não informado'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Nome da Mãe: </label>
			<span>${dadosConsulta?.nomeMae || 'Não informado'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Menor de Idade?: </label>
			<span>${dadosConsulta?.menorDeIdade ? 'Sim' : 'Não'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Consta Óbito?: </label>
			<span>${dadosConsulta?.constaObito ? 'Sim' : 'Não'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Mensagem da fonte: </label>
			<span>${dadosConsulta?.mensagem || 'Não informado'}</span>
		</div>`;
	} else {
		const dadosConsulta = resposta.profile?.consultas?.consultaCnpj;
		const cnpj = dadosConsulta?.cnpj ? criarMascara(dadosConsulta?.cnpj, '##.###.###/####-##') : 'Não informado';
		htmlDadosConsulta = /*html*/`
		<div class="col-md-6 form-group bord">
			<label>CNPJ: </label>
			<span>${cnpj}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Inscrição estadual: </label>
			<span>${dadosConsulta?.inscricaoEstadual || 'Não informado'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Nome: </label>
			<span>${dadosConsulta?.nome || 'Não informado'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Nome fantasia: </label>
			<span>${dadosConsulta?.nomeFantasia || 'Não informado'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Situação: </label>
			<span>${dadosConsulta?.situacao || 'Não informado'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Data de abertura: </label>
			<span>${dadosConsulta?.dataAbertura || 'Não informado'}</span>
		</div>
		<div class="col-md-6 form-group bord">
			<label>Mensagem da fonte: </label>
			<span>${dadosConsulta?.mensagem || 'Não informado'}</span>
		</div>`;
	}

	// Setar os dados consultados
	$('#div-dados-consultados-avaliacao-clientes').html(htmlDadosConsulta);
	
	$('#loadingAvaliacaoCliente').addClass('hide');
	$('#conteudo-avaliacao-cliente').removeClass('hide');
	
}