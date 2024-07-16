/**
 * Neste arquivo está a instanciação dos selects server-side
 */

/**
 * @param {String} url - entidade do crm que será realizada a busca
 * @param {String} id - id que será selecionado 
 * @param {String} name - nome que será selecionado
 * @param {Integer} munimumInputLength - número mínimo de caracteres que deverá ser digitado para ser feita a busca
 * @return {Object} - Ex.: { ajax: {} }
 * 
 */
 function getConfigBuscaServerSideSelect2(url, id, name, placeholder, minimumInputLength = 3){
	return {
		ajax: {
			url: `${URL_PAINEL_OMNILINK}/ajax_search_select/${url}`,
			dataType: 'json',
			delay: 2000,
			data: function(params){
				return {
					q: params.term,
					id: id, 
					name: name
				}
			}
		},
		language: {
			inputTooShort: function() {
				return 'Digite ao menos 3 caracteres para realizar a busca.';
			},
			searching: function() {
				return "Buscando...";
			}
		},
		width: '100%',
		placeholder: placeholder,
		allowClear: true,
		minimumInputLength: minimumInputLength,
		language: "pt-BR"
	}
}

$("#na_tz_item_contratoid").select2(getConfigBuscaServerSideSelect2(
	'tz_item_contrato_vendas', 'tz_item_contrato_vendaid', 'tz_name', 'Selecione o item de contrato de venda'
));
$("#na_serviceid").select2(getConfigBuscaServerSideSelect2(
	'services', 'serviceid', 'name', 'Selecione o serviço'
));

// Informações de vendas
$("#na_tz_rastreadorid").select2(getConfigBuscaServerSideSelect2(
	'products', 'productid','name', "Selecione o rastreador"
));
$("#na_tz_planoid").select2(getConfigBuscaServerSideSelect2(
	'product', 'productid', 'name', "Selecione o plano"
));
$("#na_tz_veiculo_contratoid").select2(getConfigBuscaServerSideSelect2(
	'tz_veiculos', 'tz_veiculoid', 'tz_placa', 'Selecione o veículo contratado'
));
$("#na_tz_frota_afid").select2(getConfigBuscaServerSideSelect2(
	'tz_frotaafs', 'tz_frotaafid', 'tz_name', 'Selecione a frota'
));
$("#na_tz_plataformaid").select2(getConfigBuscaServerSideSelect2(
	'tz_plataformas', 'tz_plataformaid', 'tz_name', 'Selecione a plataforma'
));
$("#na_tz_cenario_vendaid").select2(getConfigBuscaServerSideSelect2(
	'tz_cenario_vendas', 'tz_cenario_vendaid', 'tz_name', 'Selecione o cenário de vendas'
));

$("#na_tz_marca_vendaid").select2(getConfigBuscaServerSideSelect2(
	'tz_marcas', 'tz_marcaid', 'tz_name', 'Selecione a marca'
));
$("#na_tz_modelo_vendaid").select2(getConfigBuscaServerSideSelect2(
	'tz_modelos', 'tz_modeloid', 'tz_name', 'Selecione o modelo'
));
$("#na_tz_tipo_veiculo_vendaid").select2(getConfigBuscaServerSideSelect2(
	'tz_tipo_veiculos', 'tz_tipo_veiculoid', 'tz_name', 'Selecione o tipo de veículo'
));

/**
 * Busca informações do cep. Essa busca é diferente das outras pois retorna também
 * informações de bairro, rua, estado e cidade, que serão setados após o 
 * cep ser selecionado
 */
$("#na_tz_endereco_cepid").select2({
	ajax: {
		url: `${URL_PAINEL_OMNILINK}/ajax_buscar_cep`,
		dataType: 'json',
		delay: 2000,
		data: function(params){
			return {
				q: params.term,
			}
		}
	},
	width: '100%',
	placeholder: 'Selecione o CEP',
	allowClear: true,
	minimumInputLength: 3,
});
// Seta inputs de bairro, rua, estado e cidade ao selecionar cep
$("#na_tz_endereco_cepid").change(function(){
	const data = $(this).select2('data')[0];
	if(data){
		// seta bairro
		$("#na_tz_endereco_bairro").val(data.bairro ? data.bairro : '');
		// seta rua
		$("#na_tz_endereco_rua").val(data.logradouro ? data.logradouro : '');
		// seta estado
		if(data.estado_id){
			$("#na_tz_endereco_estadoid").html(
				`<option value="${data.estado_id}">${data.estado}</option>`
			).trigger('change');
		}
		// seta cidade
		if(data.cidade_id){
			$("#na_tz_endereco_cidadeid").html(
				`<option value="${data.cidade_id}">${data.cidade}</option>`
			).trigger('change');
		}
	}
	
	
});
$("#na_tz_endereco_estadoid").select2(getConfigBuscaServerSideSelect2(
	'tz_estados', 'tz_estadoid', 'tz_name', 'Selecione o estado'
));
$("#na_tz_endereco_cidadeid").select2(getConfigBuscaServerSideSelect2(
	'tz_cidades', 'tz_cidadeid', 'tz_name', 'Selecione a cidade'
));
$("#na_tz_emails_envio_agendamentoid").select2(getConfigBuscaServerSideSelect2(
	'tz_grupo_emails_clientes', 'tz_grupo_emails_clienteid', 'tz_name', 'Selecione o grupo de email'
));
$("#na_tz_emails_envio_orcamentoid").select2(getConfigBuscaServerSideSelect2(
	'tz_grupo_emails_clientes', 'tz_grupo_emails_clienteid', 'tz_name', 'Selecione o grupo de email'
));
$("#na_tz_cliente_pj_pagar_osid").select2(getConfigBuscaServerSideSelect2(
	'accounts', 'accountid', 'name', 'Selecione o cliente PJ'
));
$("#na_tz_cliente_pf_pagar_osid").select2(getConfigBuscaServerSideSelect2(
	'contacts', 'contactid', 'fullname', 'Selecione o cliente PF'
));

$("#na_siteid").select2(getConfigBuscaServerSideSelect2(
	'sites', 'siteid', 'name', 'Selecione o site'
));
$("#na_tz_motivo_agendamento_tardioid").select2(getConfigBuscaServerSideSelect2(
	'tz_motivo_agendamento_tardios', 'tz_motivo_agendamento_tardioid', 'tz_name', 'Selecione o motivo de agendamento tardio'
));
$("#na_resources").select2(getConfigBuscaServerSideSelect2(
	'resources', 'resourceid', 'name', 'Selecione o recurso'
));
$("#na_tz_bloqueio_agendaid").select2(getConfigBuscaServerSideSelect2(
	'tz_bloqueio_agendas', 'tz_bloqueio_agendaid', 'tz_name', 'Selecione a reserva de agenda'
));
$("#na_regardingobjectid").select2(getConfigBuscaServerSideSelect2(
	'tz_contrato_vendas', 'tz_contrato_vendaid', 'tz_name', 'Selecione o contrato de venda'
));

function gerarSelect2FormCliente(idInput, url ){
	$(idInput).select2({
		ajax: {
		url: `${URL_PAINEL_OMNILINK}/` + url,
		dataType: 'json',
		delay: 2000,
		data: function(params){
			return {
				q: params.term,
			}
		}
		},
		width: '100%',
		allowClear: true,
		minimumInputLength: 3,
		language: "pt-BR"
	});

	$(idInput).change(function(){
		const data = $(this).select2('data')[0];

		if(data){
			if(data.id){
				$(idInput).val(data.id);
			}     
		}
	});
}

$("#input-buscar-ocorrencia").select2(getConfigBuscaServerSideSelect2(
	'incidents', 'ticketnumber', 'ticketnumber', 'Selecione o número da ocorrência'
))
