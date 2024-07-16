var dispositivo = '';

async function criarDispositivoTwilio() {
	try {
		if (!tokenTwilio) {
			tokenTwilio = await obterToken()
			sessionStorage.setItem('tokenTwilio', tokenTwilio)
		}
	
		dispositivo = new Twilio.Device(tokenTwilio, {
			answerOnBridge: true,
			enableRingingState: true,
			codecPreferences: ['opus', 'pcmu'],
		});
	
		if (dispositivo) {
			await dispositivo.register();
	
			dispositivo.on('tokenWillExpire', async () => {
				await atualizarToken();
			})
			
			dispositivo.on('error', async (error) => {
				if (error.code === 20104) await atualizarToken(); // Token expirado
				if (error.code === 20101) await atualizarToken(); // Token invalido
				else console.error(`Twilio.Device Error: ${error.message}`);
			})
	
			dispositivo.on('incoming', async (chamada) => {
				novaChamada = new Object();
				protocolo = gerarProtocolo();
	
				alterarTextoCamposDeProtocolo(`${lang['protocolo']} : ` + protocolo);
	
				$(popupDiscador).hide();
				$(popupChamada).hide();
				$(headerTeclado).hide();
				$(divRecebendoChamada).show();
	
				modalDiscador.modal({
					show: true,
					backdrop: false
				})
	
				let nomeContato = chamada.parameters.From;
				let callSid = chamada.parameters.CallSid;
	
				if (chamada?.customParameters.get('nomeContatante')) {
					nomeContato = chamada?.customParameters.get('nomeContatante');
				}
	
				if (chamada?.customParameters.get('callSid')) {
					callSid = chamada?.customParameters.get('callSid');
				}
	
				identificador.text(nomeContato);
				novaChamada.sid = callSid;
				novaChamada.efetuadaPor = chamada.parameters.From.toString();
				novaChamada.recebidaPor = "+" + chamada.parameters.To.toString();
				novaChamada.protocolo = protocolo;
	
				if (chamada?.customParameters.get('numeroDestino')) {
					novaChamada.recebidaPor = chamada?.customParameters.get('numeroDestino');
				}
	
				statusChamada.text('Recebendo ligação...');
	
				botaoAtender.on('click', async () => {
					chamada.accept();
	
					$(popupDiscador).hide();
					$(divRecebendoChamada).hide();
					$(headerTeclado).hide();
					$(popupChamada).show();
					novaChamada.status = "completada";
	
          novaChamada.dataHoraInicio = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');
				})
	
				btnRejeitar.on('click', async () => {
					chamada.reject();
					novaChamada.status = "recusada";
				})
	
				btnEncerrar.on('click', async () => {
					statusChamada.text('Encerrando...');
					chamada.disconnect();
					if (typeof nomeContato !== 'string') nomeContato.text('');
				})
	
				chamada.on('accept', () => {
					cronometro();
				})
	
				chamada.on('disconnect', async () => {
					numeroDestino.value = '';
					statusChamada.text('');
					if (typeof nomeContato !== 'string') nomeContato.text('');
	
          novaChamada.dataHoraFim = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss')
					if (!novaChamada?.sid) novaChamada.sid = chamada.parameters.CallSid;
					discadorEncerrandoChamada();
					await inserirChamada();
				})
	
				chamada.on('cancel', async () => {
					numeroDestino.value = '';
					statusChamada.text('');
	
					novaChamada.status = "sem_resposta";
					discadorEncerrandoChamada();
					await inserirChamada();
				})
	
				chamada.on('reject', async () => {
	
					numeroDestino.value = '';
					statusChamada.text('');
	
					novaChamada.status = "recusada";
					discadorEncerrandoChamada();
					await inserirChamada();
				})
			})
	
		}
	}
	catch (error) {
		atualizarToken();
		console.error('error', 'Ocorreu um erro na inicialização do discador. error', error);
		showAlert('error', 'Ocorreu um erro na inicialização do discador, tente novamente em alguns instantes.');
	}
}

async function efetuarChamada() {
	const numeroOrigem = await buscarNumeroOrigem();
	if (numeroOrigem) {
		const ddi = "+" + inputDdi.s.dialCode.toString();
		const numeroFormatado = tratarNumeroComMascara(numeroDestino.value.toString());
		const numeroDestinoDiscador = ddi + numeroFormatado;

		const call = await dispositivo.connect({
			params: { To: numeroDestinoDiscador },
			record: 'true'
		})

		if (call.status() === 'connecting') {
			discadorConectandoChamada();
		}

		call.on('ringing', async () => {
			let nomeContato = numeroDestinoDiscador.toString();
			discadorChamando(nomeContato);

			novaChamada.dataHoraInicio = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');
			novaChamada.status = "sem_resposta";
			novaChamada.efetuadaPor = numeroOrigem;
			novaChamada.recebidaPor = numeroDestinoDiscador;
			novaChamada.protocolo = protocolo;
		})

		call.on('accept', () => {
			statusChamada.text('Chamada aceita.');
			novaChamada.status = "completada";
			novaChamada.dataHoraInicio = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');
			cronometro();
		})

		call.on('reject', () => {
			novaChamada.status = "sem_resposta";
		})

		call.on('disconnect', async (evento) => {
			discadorEncerrandoChamada();

			numeroDestino.value = '';
			statusChamada.text('');
			if (typeof nomeContato !== 'string') nomeContato.text('');
			
			novaChamada.dataHoraFim = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');
			novaChamada.sid = evento.parameters.CallSid;
			
			zerarCronometro();
			await inserirChamada();
		})

		call.on('cancel', () => {
			numeroDestino.value = '';
			statusChamada.text('');
			novaChamada.status = "sem_resposta";
		})

		call.on('error', () => {
			call.disconnect();
			novaChamada.status = "com_falha";
		})

		btnEncerrar.on('click', () => {
			statusChamada.text('Encerrando...');
			call.disconnect();
		})
	}
	else {
		showAlert('error', 'Não foi possível obter o número de origem para efetuar a chamada.');
		discadorEncerrandoChamada();
	}
}

async function obterToken() {
	try {
		const { data } = await axios.get(`${SITE_URL}/AtendimentoOmnilink/CanalVoz/obterToken`);
		return data?.token || '';
	}
	catch (error) {
		throw new Error()
	}
}

async function atualizarToken() {
	try {
		const token = await obterToken();
		if (token.length) {
			sessionStorage.setItem('tokenTwilio', token);
			if (dispositivo) dispositivo.updateToken(token);
		}	
	} 
	catch (error) {
		throw new Error();
	}
}

async function buscarNumeroOrigem() {
	return LINHA_ATIVA_CANAL_VOZ;
}
