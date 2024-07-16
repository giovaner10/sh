const tabDiscador = $("#tab-discador");
const tabContatos = $("#tab-contatos");
const liDiscador = $("#li-discador");
const liContatos = $("#li-contatos");

const botaoFecharDiscador = $('#fechar-discador');
const btnBackspaceContato = $('#btn-backspace-contatos');
const btnAddContato = $('#btn-add-contato');

const btnCancelarContato = $("#botao-cancelar-contato");

const inputNome = $("#contato-nome");
const inputEmail = $("#contato-email");
const inputEmpresa = $("#contato-empresa");

const inputNumeroTelefoneContatos = document.getElementById('numero-telefone-criar-editar-contato');

const removeClasseError = () => {
  inputNome.removeClass('error');
  inputEmail.removeClass('error');
  inputEmpresa.removeClass('error');
  inputNumeroTelefoneContatos.classList.remove('error');
};

const zeraInputs = () => {
  inputNome.val('');
  inputEmail.val('');
  inputEmpresa.val('');
  inputNumeroTelefoneContatos.value = '';
};

$(document).ready(() => {
  const porcentagemWidthComponente = "95%";

  inputDdiContatos = carregarBandeirasDdi(inputNumeroTelefoneContatos, porcentagemWidthComponente);

	adptarMascaraOnChangePais(inputNumeroTelefoneContatos, inputDdiContatos);

  tabContatos.click(() => {
    $('#botao-salvar-contato').removeAttr('data-id');
    $('#botao-salvar-contato').attr('data-acao', 'cadastrar');
    $('#titulo-modal-contato').text('Criar Contato');
    ativarGuia('guia-agenda');

    removeClasseError();
    zeraInputs();
  });

  tabDiscador.click(() => {
    $('#botao-salvar-contato').removeAttr('data-id');
    $('#botao-salvar-contato').attr('data-acao', 'cadastrar');
    $('#titulo-modal-contato').text('Criar Contato');
    ativarGuia('teclado');

    removeClasseError();
    zeraInputs();
  });

});

btnAddContato.click(() => {
  $('#botao-salvar-contato').removeAttr('data-id');
  $('#botao-salvar-contato').attr('data-acao', 'cadastrar');
  $('#titulo-modal-contato').text('Criar Contato');

	ativarGuia('guia-criar-editar-contato');
  const numDiscador = numeroDestino.value.replace(/\D/g, '');

  if(numDiscador.length > 0) {
    inputNumeroTelefoneContatos.value = numDiscador;
    adicionarMascaraContato();
  }
  
  ativarGuia('guia-criar-editar-contato');

  ativarNavLinkContatos();
});

btnCancelarContato.click(() => {
  ativarGuia('guia-agenda');

  ativarNavLinkContatos();

  removeClasseError();
  zeraInputs();

  $('#botao-salvar-contato').removeAttr('data-id');
  $('#botao-salvar-contato').attr('data-acao', 'cadastrar');
  $('#titulo-modal-contato').text('Criar Contato');
});

botaoFecharDiscador.click(() => {
	ativarGuia('teclado');

  numeroDestino.value = '';
  zeraInputs();
  removeClasseError();
  ativarNavLinkDiscador();
});

const verificarInputNome = () => {
  const nome = inputNome.val();

  // Adiciona a Classe error aos inpunts vazios
  if (nome.trim() === '') {
    inputNome.addClass('error');
    showAlert('error', 'Nome do contato é necessário')
    return false;
  } else {
    inputNome.removeClass('error');
  }

  if (nome.length <= 2) {
    inputNome.addClass('error');
    showAlert('error', 'Nome do contato deve ter no mínimo 3 caracteres')
    return false;
  } else {
    inputNome.removeClass('error');
  }

  return true;
};

const verificarInputEmail = () => {
  const email = inputEmail.val();
  const emailValido = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email);

  if (!emailValido && email.trim() !== ''){
    inputEmail.addClass('error');
    showAlert('error', 'Digite um email válido')
    return false;
  } else {
    inputEmail.removeClass('error');
  }

  return true;
}

const verificarInputEmpresa = () => {
  const empresa = inputEmpresa.val();

  if (empresa.trim() === '') {
    inputEmpresa.addClass('error');
    showAlert('error', 'Nome da Empresa é necessário')
    return false;
  } else {
    inputEmpresa.removeClass('error');
  }

  if (empresa.length <= 2) {
    inputEmpresa.addClass('error');
    showAlert('error', 'Nome da Empresa deve ter no mínimo 3 caracteres')
    return false;
  } else {
    inputEmpresa.removeClass('error');
  }

  return true;
};

const verificarInputTelefone = () => {
  const telefone = inputNumeroTelefoneContatos.value.replace(/\D/g, '');

  if (telefone.length < 10) {
    inputNumeroTelefoneContatos.classList.add('error');
    showAlert('error', 'Digite um número de telefone válido')
    return false;
  } else {
    inputNumeroTelefoneContatos.classList.remove('error');
  }

  return true;
};

inputNumeroTelefoneContatos.addEventListener('paste', function(e) {
  e.preventDefault(); // Impede a operação padrão de colar
});

// Função para adicionar a máscara ao digitar no teclado do criar/editar contato
inputNumeroTelefoneContatos.addEventListener('keydown', function(e) {
  if (isNaN(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' || e.key === ' ') {
		e.preventDefault(); // Previne a entrada da tecla não desejada
		return;
	}
	const numSemCaracteresEspeciais = inputNumeroTelefoneContatos.value.replace(/\D/g, '').length;
	if(e.key === 'Backspace') {
		if(numSemCaracteresEspeciais >= 9) {
			setTimeout(function() {
				adicionarMascaraContato();
			}, 0);
		}
	} else {
		setTimeout(function() {
			adicionarMascaraContato();
		}, 0);
	}
});

// Funcao para apagar digitos do criar/editar contato
btnBackspaceContato.on('click', () => {
	inputNumeroTelefoneContatos.value = inputNumeroTelefoneContatos.value.substring(0, inputNumeroTelefoneContatos.value.length - 1)
	if(inputNumeroTelefoneContatos.value.length > 10) {
		adicionarMascaraContato()
	}
});

const adicionarMascaraContato = () => {
	const num = inputNumeroTelefoneContatos.value.replace(/\D/g, '');
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
			inputNumeroTelefoneContatos.value = formattedNum;
		} 
	} else {
			let formattedNum = '(' + num.substring(0, 2);

				formattedNum += ') ' + num.substring(2, 7);
	

				formattedNum += '-' + num.substring(7, 11);
	
        inputNumeroTelefoneContatos.value = formattedNum;
	}
}

$(document).on('click', '#botao-salvar-contato', function (e) {
	e.preventDefault();

  
  verificarInputNome();
  verificarInputEmail();
  verificarInputEmpresa();
  verificarInputTelefone();
  
  const inputsComErro = $('.error');

  // Verifica se todos os campos necessários estão preenchidos corretamente antes de salvar
  if (inputsComErro.length > 0){
    return
  } 

  salvarContato();
});

//Salvar Contato
async function salvarContato() {
	const btnSalvarContato = $("#botao-salvar-contato");
	const acao = btnSalvarContato.attr('data-acao');
  const id = btnSalvarContato.attr('data-id');

  //Editando o telefone para o formato +55 (11) 99999-9999
  const ddi = "+" + inputDdi.s.dialCode.toString();
  const telefone = inputNumeroTelefoneContatos.value.replace(/\D/g, '');

  if (telefone.length > 0 && acao === 'cadastrar') {
    const telefoneFormatado = `${ddi}${telefone}`;
    inputNumeroTelefoneContatos.value = telefoneFormatado;
  }

  if(acao === 'editar' && telefone.length > 0) {
    const telefoneFormatado = `${ddi}${telefone}`;
    inputNumeroTelefoneContatos.value = telefoneFormatado;
  }

  const formData = new FormData($("#form-criar-editar-contato")[0]);

	let url = `${SITE_URL}/AtendimentoOmnilink/CanalVoz/cadastrarContato`;
	if (acao === 'editar') url = `${SITE_URL}/AtendimentoOmnilink/CanalVoz/editarContato/${id}`;

	btnSalvarContato
		.prop('disabled', true)
		.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;
			if (data.status == 1) {
				if (acao === 'editar') {
					//atualizar registro na agGrid
          const node = gridOptions2.api.getRowNode(id);
					const registro = {
						id: id,
						nome: formData.get('nome'),
            email: formData.get('email'),
            empresa: formData.get('empresa'),
            telefone: `${ddi}${telefone}`,
						dataCadastro: node.data.dataCadastro,
						dataModificacao: dayjs().format('YYYY-MM-DD HH:mm:ss'),
					};

          atualizarRegistroNaAgGridContatos(node.rowIndex, registro);
          carregarGridContatos();
          zeraInputs();
				}
				else if (acao === 'cadastrar') {
					//novo registro na agGrid
					const novoRegistro = {
						id: data.idNovoContato,
						nome: formData.get('nome'),
            email: formData.get('email'),
            empresa: formData.get('empresa'),
            telefone: `${ddi}${telefone}`,
						dataCadastro: dayjs().format('YYYY-MM-DD HH:mm:ss'),
						dataModificacao: ''
					};

					adicionarRegistroNaAgGridContatos([novoRegistro]);
          zeraInputs();
				}

				showAlert('success', data.mensagem);
        zeraInputs();
				ativarGuia('guia-agenda');
			}
			else {
				showAlert('error', data.mensagem);
			}
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		})
		.finally(() => {
			btnSalvarContato.prop('disabled', false).html('Salvar');
      numeroDestino.value = '';
      inputNumeroTelefoneContatos.value = '';
		});
} 

$(document).on('click', '.editar-contato', function (e) {
  ativarNavLinkContatos();

	e.preventDefault();
	zeraInputs();
	
	const botao = $(this);
	const id = botao.attr('data-id');

	botao
		.attr('disabled', true)
		.html('Carregando...');

    
  const data = gridOptions2.api.getRowNode(id).data;
  const telefoneSemDdi = data.telefone.replace(/^\+\d{2}/, '');
	// Preenche os campos do modal
	inputNome.val(data.nome);
  inputEmail.val(data.email);
  inputEmpresa.val(data.empresa);
  inputNumeroTelefoneContatos.value = telefoneSemDdi;

  adicionarMascaraContato();

	// Configura o modal para editar
  $('#botao-salvar-contato').attr('data-id', id);

	$('#titulo-modal-contato').text('Editar Contato');
	$('#botao-salvar-contato').attr('data-acao', 'editar');

	ativarGuia('guia-criar-editar-contato');

	botao
		.attr('disabled', false)
		.html('Editar');
});

function atualizarRegistroNaAgGridContatos(idRow, dadosAtualizados) {
	let displayModel = gridOptions2.api.getModel();
	let rowNode = displayModel.rowsToDisplay[idRow];
	rowNode.setData(dadosAtualizados);
}


function adicionarRegistroNaAgGridContatos(dadosNovos, colunaOrdenacao='id', ordernacao='desc') {
  gridOptions2.api.applyTransaction({ add: dadosNovos, });
  //ordena a grid
  gridOptions2.columnApi.applyColumnState({ state: [{ colId: colunaOrdenacao, sort: ordernacao }], });
}
