const btnLigar = $('#btn-ligar');
const btnBackspace = $('#btn-backspace');
const btnEncerrar = $('.btn-encerrar-chamada');
const btnRejeitar = $('.btn-rejeitar-chamada');
const botaoAtender = $('#btn-atender-chamada');

const popupDiscador = $('#teclado');
const statusChamada = $('.status-chamada');
const popupChamada = $('#chamada-em-curso');
const identificador = $('.numero-identificador');
const nomeContato = $('.nome-identificador');
const divRecebendoChamada = $('#recebendo-chamada');
const statusGravacao = $('.status-gravacao');
const numeroDestino = document.getElementById('numero-telefone');
const modalDiscador = $('#modalDiscador');
const headerTeclado = $('.header-teclado');

var conexaoBrokerVivo = false;
var inputDdi = null

let novaChamada = {};
var protocolo = null;

$(document).ready(async () => {
	const porcentagemWidthComponente = "95%";

	inputDdi = carregarBandeirasDdi(numeroDestino, porcentagemWidthComponente)
	// inputDdiContatos = carregarBandeirasDdi(inputNumeroTelefone, porcentagemWidthComponente);
	
	adptarMascaraOnChangePais(numeroDestino, inputDdi);
	// adptarMascaraOnChangePais(inputNumeroTelefone, inputDdiContatos);
	
	$(modalDiscador).draggable();
	await criarDispositivoTwilio();

	document.getElementById("spinner-discador").style.display = "none";
	document.getElementById("img-btn-ligar").classList.remove("d-none");
	document.getElementById("btn-ligar").removeAttribute("disabled");
	document.getElementById("btn-ligar").style.cursor = "pointer";

})

function ativarGuia(nome) {
	const guias = [
			'guia-agenda',
			'guia-criar-editar-contato',
			'teclado',
			'chamada-em-curso',
	];

	guias.forEach((guia) => {
			if (guia === nome) {
					$(`#${guia}`).show();
			} else {
					$(`#${guia}`).hide();
			}
	});
}

function ativarNavLinkContatos() {
	tabDiscador.removeClass('active');
  liDiscador.removeClass('active');
  tabContatos.addClass('active');
  liContatos.addClass('active');
}

function ativarNavLinkDiscador() {
	tabDiscador.addClass('active');
	liDiscador.addClass('active');
	tabContatos.removeClass('active');
	liContatos.removeClass('active');
}

numeroDestino.addEventListener('paste', function(e) {
  e.preventDefault(); // Impede a operação padrão de colar
});

// Função para adicionar a máscara ao digitar no teclado
numeroDestino.addEventListener('keydown', function(e) {
	if (isNaN(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' || e.key === ' ') {
		e.preventDefault(); // Previne a entrada da tecla não desejada
		return;
	}
	const numSemCaracteresEspeciais = numeroDestino.value.replace(/\D/g, '').length;
	if(e.key === 'Backspace') {
		if(numSemCaracteresEspeciais >= 9) {
			setTimeout(function() {
				adicionarMascara();
			}, 0);
		}
	} else {
		setTimeout(function() {
			adicionarMascara();
		}, 0);
	}
});

$(modalDiscador).on('hide.bs.modal', function () {
	$(popupChamada).hide();
	$(divRecebendoChamada).hide();
	$(headerTeclado).show();
	$(popupDiscador).show();
	
	zerarCronometro();
});

$(document).on('click', '.btn-ligar', function (e) {
	e.stopPropagation();

	const { numero, nome, ddd, ddi } = $(this).data();
	ligar(ddi + ddd + numero, nome);
});


function ligar(numero, nome) {
	nomeContato.text(nome);
	if (numero) {
		numeroDestino.value = numero;
		$(btnLigar).trigger('click');
	} else {
		showAlert('warning', 'Sem informação.');
	}
}

function removerPlus(str) {
	if (str.charAt(0) === "+") return str.substring(1);
	return str;
}

async function inserirChamada() {
	if (!novaChamada.dataHoraInicio) novaChamada.dataHoraInicio = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');
	if (!novaChamada.dataHoraFim) novaChamada.dataHoraFim = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');

	return await $.ajax({
		url: `${SITE_URL}/atendimentoOmnilink/CanalVoz/salvarHistoricoChamada`,
		type: 'POST',
		dataType: 'json',
		data: novaChamada,
		error: function (error) {
			console.error("erro ao inserir chamada: ", error);
		},
		complete: function () {
			alterarTextoCamposDeProtocolo("");
			// Inserindo o protocolo no botao de copiar
			$('#btn-copiar-protocolo')
				.attr('data-protocolo', '')
				.addClass('hide');
		}
	});
}

// Funcao para efetuar chamada
btnLigar.on('click', criarChamada);

async function criarChamada() {
	if (!numeroDestino.value.length || numeroDestino.value.length < 10) {
		showAlert('warning', 'Número inválido, verifique o número e tente novamente.');
		return;
	}

	protocolo = gerarProtocolo();
	alterarTextoCamposDeProtocolo(`Protocolo : ` + protocolo);
	
	// Inserindo o protocolo no botao de copiar
	$('#btn-copiar-protocolo')
		.attr('data-protocolo', protocolo)
		.removeClass('hide');

	$(divRecebendoChamada).hide();
	$(popupDiscador).hide();
	$(headerTeclado).hide();
	$(popupChamada).show();

	if (dispositivo) {
		await efetuarChamada();
	}
}

// Funcao para apagar digitos
btnBackspace.on('click', () => {
	numeroDestino.value = numeroDestino.value.substring(0, numeroDestino.value.length - 1)
	if(numeroDestino.value.length > 10) {
		adicionarMascara()
	}
});

// Funcao para inserir digitos
const digitos = document.querySelectorAll(".digito");
for (let i = 0; i < digitos.length; i++) {

	const digito = $(digitos[i]).data('digito');
	const subdigito = $(digitos[i]).data('subdigito');

	const tempo = 1000;
	let inicio;

	$(digitos[i]).on('mousedown', () => {
		// adicionarMascara(numeroDestino.value);
		inicio = new Date().getTime();
	});

	$(digitos[i]).on('mouseleave', () => {
		inicio = 0;
	});

	$(digitos[i]).on('mouseup', () => {
		if (new Date().getTime() >= (inicio + tempo)) {
			numeroDestino.value += subdigito || '';
		} else {
			numeroDestino.value += digito;
		}
		setTimeout(function() {
			adicionarMascara();
		}, 0);
	});

}

const adicionarMascara = () => {
    const num = numeroDestino.value.replace(/\D/g, '');
		const numSemAtrasoDeDOM = num.length;

		if(numSemAtrasoDeDOM < 11) {
			if (numSemAtrasoDeDOM > 0) {
				let formattedNum = '(' + num.substring(0, 2);
	
				if (numSemAtrasoDeDOM > 2) {
					formattedNum += ') ' + num.substring(2, 6);
				}

				if (numSemAtrasoDeDOM > 3) {
					formattedNum += '-' + num.substring(6, 10);
				}
				numeroDestino.value = formattedNum;
			} 
		} else {
				let formattedNum = '(' + num.substring(0, 2);
	
					formattedNum += ') ' + num.substring(2, 7);
	

					formattedNum += '-' + num.substring(7, 11);
	
				numeroDestino.value = formattedNum;
		}
}

let hora = 0;
let minuto = 0;
let segundo = 0;
let millisegundo = 0;
let cron;

function cronometro() {
	cron = setInterval(() => { tempo(); }, 10);
}

function zerarCronometro() {
	hora = 0;
	minuto = 0;
	segundo = 0;
	millisegundo = 0;
	clearInterval(cron);
}

function tempo() {
	if ((millisegundo += 10) == 1000) {
		millisegundo = 0;
		segundo++;
	}
	if (segundo == 60) {
		segundo = 0;
		minuto++;
	}
	if (minuto == 60) {
		minuto = 0;
		hora++;
	}
	statusChamada.text(`${formataHora(hora)}:${formataHora(minuto)}:${formataHora(segundo)}`);
}

function formataHora(hora) {
	return hora >= 10 ? hora : `0${hora}`
}

function discadorConectandoChamada() {
	statusChamada.text('Conectando...')
}

function discadorChamando(numeroDestinatario) {
	identificador.text(numeroDestinatario);
	statusChamada.text('Chamando...');
}

function discadorEncerrandoChamada() {
	$(popupChamada).hide();
	$(divRecebendoChamada).hide();
	$(headerTeclado).show();
	$(popupDiscador).show();

	statusChamada.text('Encerrando...');
	identificador.text('')
	zerarCronometro();
}


async function finalizarChamada() {
	discadorEncerrandoChamada();
	novaChamada.dataHoraFim = dayjs(new Date()).format('YYYY-MM-DD HH:mm:ss');
	await inserirChamada();
	zerarCronometro();
}

function gerarProtocolo() {
	const dataAtual = dayjs().format('YYYYMMDDHHmmssSSS');
	return `${dataAtual}`;
}


function alterarTextoCamposDeProtocolo(texto) {
	const elementosDoProtocolo = document.getElementsByClassName("protocolo-chamada");

	for (elemento of elementosDoProtocolo) {
		elemento.innerText = texto;
	}
}


async function copiarProtocolo(elemento) {
	var botao = $(elemento);

	const valorProtocolo = botao.attr('data-protocolo');
	navigator.clipboard.writeText(valorProtocolo?.trim() || '');

	botao.html('<i class="fa fa-check"></i>');
	await sleep(2);
	botao.html('<i class="fa fa-copy"></i>');
}