/**
 * Insere inputs do formulário
 * @param {Object} inputs 
 * @param {Object} selects 
 */
 function inserirDadosCamposFormulario(inputs, selects){
	// Preenche os inputs
	for(key in inputs) {
		$(`#${key}`).val(inputs[key]);
	}
	// Preenche os selects
	for(key in selects){
		if(selects[key].id){
			$(`#${key}`).html(`<option value="${selects[key].id}">${selects[key].text ? selects[key].text : "-"}</option>`).trigger('change');
		}
	}
}

/**
 * Salva formulário da aba atual no modal de item de contrato
 */
// Função que retorna os dados do formulário de cadastro de acordo com o id informado
function getDadosForm(idForm) {
	let dadosForm = $(`#${idForm}`).serializeArray();
	let dados = {};
	for (let c in dadosForm) {
		dados[dadosForm[c].name] = dadosForm[c].value;
	}
	return dados;
}


/**
 * Função que limpa os inputs do modal de item de contrato
 * @param {String} idForm - ID do formulário
 */
 function limpaInputsModal(idForm){
	$(`#${idForm}`).find('select,input,textarea').each(function(){
		$(this).val('').trigger('change');
	});		
}

/**
 * Função que realiza uma requisição post
 * @param {String} url - url da requisição
 * @param {Object} dados - parâmetros da requisição
 */
async function postRequest(url, dados){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: url,
			type: "POST",
			dataType: 'json',
			data: dados,
			success: function(callback){
				resolve(callback);
			},
			error: function(error){
				reject(error);
			}
		});
	});
}

/**
 * Função que realiza uma requisição delete
 * @param {String} url - url da requisição
 * @param {Object} dados - parâmetros da requisição
 */
 async function deleteRequest(url){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: url,
			type: "DELETE",
			success: function(callback){
				resolve(callback)
			},
			error: function(error){
				reject(error);	
			}
		});
	});
}

$("#formAtividadeDeServico").submit(async event => {
    event.preventDefault();
    let dados = getDadosForm('formAtividadeDeServico');
    let btn = $("#btnSalvarAtividadeDeServico");
	let htmlBtn = btn.html();
    const idAtividadeDeServico = $('#na_activityid').val();
	btn.attr('disabled',true).html(iconSpinner + " Salvando");
    if(idAtividadeDeServico && idAtividadeDeServico != ""){// EDITA NA
		const url = `${URL_PAINEL_OMNILINK}/ajax_editar_atividade_de_servico/${idAtividadeDeServico}`;
		salvar_auditoria(url, 'update',auditoria.valor_antigo_atividade_servico, dados)
			.then(async () => {
				await postRequest(url, dados)
				.then(callback => {
					btn.attr('disabled',false).html(htmlBtn);
					if(callback.status){
						alert("Atividade de serviço atualizada com sucesso!");
						let dado = callback.data;
						dado['acoes'] = getButtonsActionTableAtividadesDeServico(dado.Id, dado.contract, dado.incident, dado.Status);
						valor_antigo_atividade_servico = null;
						tableAtividadesDeServico.row(`#${dado.Id}`).data(dado).draw();
						$('#modalAtividadeDeServico').modal('hide');
					}

				})
				.catch(error => {
					btn.attr('disabled',false).html(htmlBtn);
					console.error(error);
				});
			})
			.catch(error => {
				btn.attr('disabled',false).html(htmlBtn);
			});
		
		
	}else{// CADASTRA NA
		const url = `${URL_PAINEL_OMNILINK}/ajax_adicionar_atividade_de_servico`;
		dados['idCliente'] = $("#Cliente").val();
		dados['clientEntity'] = clientEntity;	
		await postRequest(url, dados)
			.then(callback => {
				btn.attr('disabled',false).html(htmlBtn);
				
			})
			.catch(error => {
				btn.attr('disabled',false).html(htmlBtn);
				
			});
	}
});

$("#formAlterarDatasAtividadeDeServico").submit(async event => {
	event.preventDefault();
	
    let formData = getDadosForm('formAlterarDatasAtividadeDeServico');
    let dados = {
		na_scheduledend: formData.na_alterar_data_scheduledend,
		na_scheduledstart: formData.na_alterar_data_scheduledstart
	};
    let btn = $("#btnSalvarAlterarDatasAtividadeDeServico");
	let htmlBtn = btn.html();
	btn.attr('disabled',true).html(`${iconSpinner} Salvando`);
    const idAtividadeDeServico = $('#formAlterarDatasAtividadeDeServico').find('#na_alterar_data_activityid').val();
	const url = `${URL_PAINEL_OMNILINK}/ajax_editar_atividade_de_servico/${idAtividadeDeServico}`;
	salvar_auditoria(url, 'update',auditoria.valor_antigo_atividade_servico, dados)
		.then(async () => {
			await postRequest(url, dados)
			.then(callback => {
				btn.attr('disabled',false).html(htmlBtn);
				if(callback.status){
					alert("Atividade de serviço atualizada com sucesso!");
					let dado = callback.data;
					dado['acoes'] = getButtonsActionTableAtividadesDeServico(dado.Id, dado.contract, dado.incident, dado.Status);
					valor_antigo_atividade_servico = null;
					tableAtividadesDeServico.row(`#${dado.Id}`).data(dado).draw();
					$('#modalAlterarDatasAtividadeDeServico').modal('hide');
				}

			})
			.catch(error => {
				btn.attr('disabled',false).html(htmlBtn);
				alert("Erro ao alterar datas da atividade de serviço!");
				console.error(error);
			});
		})
		.catch(error => {
			btn.attr('disabled',false).html(htmlBtn);
		});
})