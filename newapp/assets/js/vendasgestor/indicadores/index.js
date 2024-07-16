$(document).ready(function () {
	// Inicializa o tooltip
	$('[data-toggle="tooltip"]').tooltip();

	//INSTANCIA A TABELA 
	tabelaIndicadores = $('#tabelaIndicadores').DataTable({
		dom: 'Bfrtip',
		responsive: true,
		order: false,
		columns: [
			{ data: 'indicador' },
			{ data: 'tipo' },
			{ data: 'valor' }
		],
		buttons: [
			{
				filename: filenameGenerator(lang.indicadores),
				extend: 'pdfHtml5',
				orientation: 'landscape',
				pageSize: 'LEGAL',
				className: 'btn btn-outline-primary',
				text: 'PDF',
				customize: function (doc, tes) {
					let titulo = lang.indicadores;
					// Personaliza a página do PDF
					pdfTemplate(doc, titulo)
				}
			},
			{
				title: lang.indicadores,
				extend: 'excelHtml5',
				orientation: 'landscape',
				pageSize: 'LEGAL',
				className: 'btn btn-outline-primary',
				text: 'EXCEL'
			},
			{
				title: lang.indicadores,
				extend: 'csvHtml5',
				orientation: 'landscape',
				pageSize: 'LEGAL',
				className: 'btn btn-outline-primary',
				text: 'CSV'
			},
			{
				filename: filenameGenerator(lang.indicadores),
				extend: 'print',
				orientation: 'landscape',
				pageSize: 'LEGAL',
				className: 'btn btn-outline-primary',
				text: lang.imprimir.toUpperCase(),
				customize: function (win) {
					let titulo = lang.indicadores;
					// Personaliza a página Impressa
					printTemplate(win, titulo);
				}
			}
		],
		language: lang.datatable
	});

});

/**
 * Busca os dados de interações e vendas do gestor e monta os indicadores na tabela
 */ 
$('#formFiltrarIndicadores').on('submit', async function (e) {
	e.preventDefault();

	const filtro = new FormData($("#formFiltrarIndicadores")[0]);
	const dados = {
		dataInicio: filtro.get('data_inicial') + ' 00:00:00',
		dataFim: filtro.get('data_final') + ' 23:59:59',
	}
	
	let botao = $('#btnFormFiltrarIndicadores');
	botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.gerando);

	efeitoBuscarDadosDatatable('tabelaIndicadores');

	const uri = `shownet/vendasgestor/interacoes/quantitativos`;
	return await axiosNode.get(uri, { params: dados })
		.then(async resposta => {
			const data = resposta?.data;
			if (data?.status === 1) {
				const dados = data?.dados;
				const indicadores = await montrarIndicadores(dados);

				// Carrega os anuncios na tabela
				tabelaIndicadores.clear();
				tabelaIndicadores.rows.add(indicadores);
				tabelaIndicadores.draw();
			}
			else {
				tabelaIndicadores.clear();
				tabelaIndicadores.draw();
				alert(lang.operacao_nao_finalizada);
			}
		})
		.catch((error) => {
			tabelaIndicadores.clear();
			tabelaIndicadores.draw();

			const data = error?.response?.data;
			const mensagemErro = data?.erro?.details[0]?.message || data?.erro?.mensagem || data?.mensagem || lang.operacao_nao_finalizada;
			alert(mensagemErro);
		})
		.finally(() => {
			botao.attr('disabled', false).html(lang.gerar);
		});

});

async function montrarIndicadores(dados) {
	let indicadores = [];

	if (Object.keys(dados).length) {
		indicadores = [
			{ 
				indicador: `${lang.indice_rejeicao} <a href="#" data-toggle="tooltip" data-placement="right" title="${lang.formula_indice_rejeicao}"><icon class="fa fa-info-circle"><icon></a>`,
				tipo: lang.interacao, 
				valor: dados.quantidadeInteracoesRejeicao 
			},
			{ 
				indicador: `${lang.indice_abandono} <a href="#" data-toggle="tooltip" data-placement="right" title="${lang.formula_indice_abandono}"><icon class="fa fa-info-circle"><icon></a>`, 
				tipo: lang.interacao, 
				valor: dados.quantidadeInteracoesAbandono 
			},
			{ 
				indicador: `${lang.indice_conversao} <a href="#" data-toggle="tooltip" data-placement="right" title="${lang.formula_indice_conversao}"><icon class="fa fa-info-circle"><icon></a>`, 
				tipo: lang.interacao, 
				valor: dados.quantidadeInteracoesConversao 
			},
			{ 
				indicador: `${lang.total_visualizacoes} <a href="#" data-toggle="tooltip" data-placement="right" title="${lang.msg_total_visualizacao_anuncios}"><icon class="fa fa-info-circle"><icon></a>`, 
				tipo: lang.interacao, 
				valor: dados.quantidadeVisualizaramAnuncio 
			},
			{
				indicador: `${lang.total_vendas} <a href="#" data-toggle="tooltip" data-placement="right" title="${lang.formula_total_vendas}"><icon class="fa fa-info-circle"><icon></a>`,
				tipo: lang.vendas,
				valor: dados.quantidadeVendas
			},
			{ 
				indicador: `${lang.total_vendas_aceitas} <a href="#" data-toggle="tooltip" data-placement="right" title="${lang.formula_total_vendas_aceitas}"><icon class="fa fa-info-circle"><icon></a>`, 
				tipo: lang.vendas, 
				valor: dados.quantidadeVendasAceitas 
			},
			{ 
				indicador: `${lang.total_vendas_recusadas} <a href="#" data-toggle="tooltip" data-placement="right" title="${lang.formula_total_vendas_recusadas}"><icon class="fa fa-info-circle"><icon></a>`, 
				tipo: lang.vendas, 
				valor: dados.quantidadeVendasrRecusadas 
			}
		];
	}

	return indicadores;
}