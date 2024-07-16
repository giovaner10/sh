let device = '';

async function conectarChamada(btnConectarLigacao, id) {
	try {
		if (!tokenTwilioAtendimento) {
			tokenTwilioAtendimento = await getToken()
			sessionStorage.setItem('tokenTwilioAtendimento', tokenTwilioAtendimento)
		}

		device = new Twilio.Device(tokenTwilioAtendimento, {
			answerOnBridge: true,
			enableRingingState: true,
			codecPreferences: ['opus', 'pcmu'],
		});

		if (device) {
			await device.register();

			device.on('tokenWillExpire', async () => {
				await updateToken();
			})

			device.on('error', async (error) => {
				if (error.code === 20104) await updateToken(); // Token expirado
				if (error.code === 20101) await updateToken(); // Token invalido
				else console.error(`Twilio.Device Error: ${error.message}`);
			})

			const numeroOrigem = await buscarNumeroAtendimento();
			if (numeroOrigem) {
				const call = await device.connect({
					params: { To: sidDestino },
					record: 'true'
				})

				call.on('accept', () => {
					statusChamada.text('Chamada aceita.');
					novaChamada.status = "completada";
					$(divEncerrar).removeClass('hidden');
					$(divAtender).addClass('hidden');
					novaChamada.dataHoraInicio = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');
					cronometro();

					btnConectarLigacao.prop('disabled', true).css('opacity', '0.5');

					const node = gridOptionsListarLigacoes.api.getRowNode(id);
					removerRegistroNaAgGridListarLigações(node.rowIndex);
				})

				call.on('reject', () => {
					novaChamada.status = "sem_resposta";

					btnConectarLigacao.prop('disabled', false).css('opacity', '1');
				})

				call.on('disconnect', async (evento) => {
					discadorEncerrandoChamada();
					$(divEncerrar).addClass('hidden');
					numeroDestino.value = '';
					statusChamada.text('');
					if (typeof nomeContato !== 'string') nomeContato.text('');

					novaChamada.dataHoraFim = dayjs(new Date).format('YYYY-MM-DD HH:mm:ss');
					novaChamada.sid = evento.parameters.CallSid;

					//zerarCronometro();
					//await inserirChamada();
					btnConectarLigacao.prop('disabled', false).css('opacity', '1');
				})

				call.on('cancel', () => {
					numeroDestino.value = '';
					statusChamada.text('');
					novaChamada.status = "sem_resposta";

					btnConectarLigacao.prop('disabled', false).css('opacity', '1');
				})

				call.on('error', () => {
					call.disconnect();
					novaChamada.status = "com_falha";

					btnConectarLigacao.prop('disabled', false).css('opacity', '1');
				})

				botaoEncerrar.on('click', () => {
					call.disconnect();

					btnConectarLigacao.prop('disabled', false).css('opacity', '1');
				})
			}
			else {
				showAlert('error', 'Não foi possível se conectar a chamada.');
			}
		}
	} catch (error) {
		updateToken()
		console.error('error', 'Ocorreu um erro na inicialização do discador. error', error);
		showAlert('error', 'Não foi possível se conectar a chamada, tente novamente em alguns instantes.');
	}

	async function buscarNumeroAtendimento() {
		return LINHA_ATIVA_ATENDIMENTO;
	}

	async function getToken() {
		try {
			const { data } = await axios.get(`${SITE_URL}/AtendimentoOmnilink/CanalVoz/obterTokenAtendimento`);
			return data?.token || '';
		}
		catch (error) {
			throw new Error()
		}
	}

	async function updateToken() {
		try {
			const token = await getToken();
			if (token.length) {
				sessionStorage.setItem('tokenTwilioAtendimento', token);
				if (device) device.updateToken(token);
			}
		}
		catch (error) {
			throw new Error();
		}
	}
}
