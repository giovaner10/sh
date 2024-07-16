<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Cotacoes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');

		$this->load->model('portal_compras/cotacao');

		$this->upload_path = 'uploads/portal_compras/cotacoes/anexos/';
		$this->file_name_anexo = '';
	}

	public function buscarPeloFornecedor() {
		$termoConsulta = $this->input->get('term');
		
		// Valida o $nomeProduto
		if (empty($termoConsulta) || !is_string($termoConsulta)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Termo de consulta inválido.']));
		}

		// Decodifica e deixa apenas numeros e letras
		$termoConsulta = urldecode($termoConsulta);
		$termoConsulta = preg_replace('/[^a-zA-Z0-9\s]/', '', $termoConsulta);
	
		$dados = [];
		$cotacoes = $this->cotacao->buscarPeloFornecedor( 
			'id, fornecedor, valor_total as valorTotal, condicao_pagamento as condicaoPagamento, forma_pagamento as formaPagamento, produtos', 
			$termoConsulta
		);

		if (!empty($cotacoes)) {
			foreach ($cotacoes as $cotacao) {				
				$dados[] = (object)[
					'id' => $cotacao->id,
					'fornecedor' => !empty($cotacao->fornecedor) ? json_decode($cotacao->fornecedor) : [],
					'valorTotal' => $cotacao->valorTotal,
					'condicaoPagamento' => $cotacao->condicaoPagamento,
					'formaPagamento' => $cotacao->formaPagamento,
					'produtos' => !empty($cotacao->produtos) ? json_decode($cotacao->produtos) : []
				];
			}
		}

		exit(json_encode($dados));
	}
	
	public function valida_anexo_cotacao() {

		if (empty($_FILES['anexo_cotacao']['name'])) {
			return TRUE;
		}

		if (!is_dir($this->upload_path)) {
			mkdir($this->upload_path, 0777, TRUE);
		}

		// sanitariza o nome do arquivo
		$file_name_anexo_cotacao = removerAcentos($_FILES['anexo_cotacao']['name']); // remove acentos
		$file_name_anexo_cotacao = str_replace(' ', '_', $file_name_anexo_cotacao); // substitui espaços por underline
		$this->file_name_anexo = preg_replace(['/[^A-Za-z0-9._\?]/'], '', $file_name_anexo_cotacao); // remove caracteres especiais

		$config['upload_path'] = $this->upload_path;
		$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx|eml';
		$config['max_size'] = 5120; // 5MB
		$config['file_name'] = $this->file_name_anexo;

		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('anexo_cotacao')) {
			$this->form_validation->set_message('valida_anexo_cotacao', 'Anexo Cotação: ' . strip_tags($this->upload->display_errors()));
			return FALSE;
		}
		
		return TRUE;
	}

	public function valida_especie() {
		$tipos = ['sped' ,'nfe' ,'cte' ,'nfs' ,'nf' ,'outro'];
		$tipoEspecie = $this->input->post('tipo_especie');
		if(!in_array($tipoEspecie, $tipos)) {
			$this->form_validation->set_message('valida_especie', 'Tipo Espécie precisa ser um da lista: sped ,nfe ,cte ,nfs ,nf ,outro');
			return FALSE;
		}

		return TRUE;
	}

	public function valida_forma_pagamento() {
		$tipos = ['pix', 'boleto', 'ted', 'cartao_credito', 'deposito'];
		$tipoFormaPagamento = $this->input->post('forma_pagamento');
		
		if(!in_array($tipoFormaPagamento, $tipos)) {
			$this->form_validation->set_message('valida_forma_pagamento', 'Forma Pagamento precisa ser um da lista: pix, boleto, ted, cartao_credito, deposito');
			return FALSE;
		}

		return TRUE;
	}

	public function cadastrar() {
		$dados = $this->input->post();

		// Validação dos campos
		$this->form_validation->set_rules('fornecedor', 'Fornecedor', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('forma_pagamento', 'Forma de Pagamento', 'required|callback_valida_forma_pagamento');
		$this->form_validation->set_rules('condicao_pagamento', 'Condição de Pagamento', 'trim|required|min_length[3]|max_length[120]');
		$this->form_validation->set_rules('valor_total', 'Valor Total', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('tipo_especie', 'Tipo Especie', 'required|callback_valida_especie');
		$this->form_validation->set_rules('anexo_cotacao', 'Anexo Cotação', 'callback_valida_anexo_cotacao');
		$this->form_validation->set_rules('produtos', 'Produtos', 'required');

		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}
		
		$idUsuario = $this->auth->get_login_dados('user');
		$pathAnexo = $this->file_name_anexo ? $this->upload_path . $this->file_name_anexo : NULL;

		$this->load->model('portal_compras/fornecedor');
		$fornecedor = $this->fornecedor->buscar($dados['fornecedor'], 'id, loja, nome, codigo, documento');
		
		// Cadastra a solicitacao
		$novaCotacao = [
			'fornecedor' => json_encode([
				'id' => $fornecedor->id,
				'loja' => $fornecedor->loja,
				'nome' => $fornecedor->nome,
				'codigo' => $fornecedor->codigo,
				'documento' => $fornecedor->documento,
			]),
			'produtos' => $dados['produtos'],
			'valor_total' => $dados['valor_total'],
			'condicao_pagamento' => $dados['condicao_pagamento'],
			'forma_pagamento' => $dados['forma_pagamento'],
			'path_anexo' => $pathAnexo,
			'id_usuario' => $idUsuario,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
			'tipo_especie' => $dados['tipo_especie'],
		];

		$idNovaCotacao = $this->cotacao->cadastrar($novaCotacao);
		if (empty ($idNovaCotacao)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao cadastrar a cotação, tente novamente.']));
		}

		exit (json_encode([
			'status' => '1',
			'mensagem' => 'Cotação cadastrada com sucesso.',
			'cotacao' => [
				'id' => $idNovaCotacao,
				'fornecedor' => [
					'id' => $fornecedor->id,
					'loja' => $fornecedor->loja,
					'nome' => $fornecedor->nome,
					'codigo' => $fornecedor->codigo,
					'documento' => $fornecedor->documento,
				],
				'valorTotal' => $dados['valor_total'],
				'condicaoPagamento' => $dados['condicao_pagamento'],
				'formaPagamento' => $dados['forma_pagamento'],
			]
		]));
	}

	public function buscar($idCotacao) {
		if (empty($idCotacao) || !is_numeric($idCotacao)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'ID da cotação inválido.']));
		}

		$cotacao = $this->cotacao->buscar($idCotacao);
		if (empty($cotacao)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Cotação não encontrada.']));
		}

		$cotacao = [
			'id' => $cotacao->id,
			'fornecedor' => json_decode($cotacao->fornecedor),
			'valorTotal' => $cotacao->valor_total,
			'condicaoPagamento' => $cotacao->condicao_pagamento,
			'formaPagamento' => $cotacao->forma_pagamento,
			'produtos' => json_decode($cotacao->produtos),
			'datahoraCadastro' => $cotacao->datahora_cadastro,
			'pathAnexo' => $cotacao->path_anexo,
			'tipoEspecie' => $cotacao->tipo_especie,
		];

		exit(json_encode(['status' => '1', 'cotacao' => $cotacao]));
	}

	public function editar($idCotacao) {
		$dados = $this->input->post();

		if (empty($idCotacao) || !is_numeric($idCotacao)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'ID da cotação inválido.']));
		}

		// Validação dos campos
		$this->form_validation->set_rules('fornecedor', 'Fornecedor', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('forma_pagamento', 'Forma de Pagamento', 'trim|required|in_list[pix,boleto,ted,cartao_credito,deposito]');
		$this->form_validation->set_rules('condicao_pagamento', 'Condição de Pagamento', 'trim|required|min_length[3]|max_length[120]');
		$this->form_validation->set_rules('valor_total', 'Valor Total', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('produtos', 'Produtos', 'required');
		if(!empty($_FILES['anexo_cotacao']['name'])) $this->form_validation->set_rules('anexo_cotacao', 'Anexo Cotação', 'callback_valida_anexo_cotacao');

		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		$idUsuario = $this->auth->get_login_dados('user');
		$pathAnexo = $this->file_name_anexo ? $this->upload_path . $this->file_name_anexo : NULL;

		$this->load->model('portal_compras/fornecedor');
		$fornecedor = $this->fornecedor->buscar($dados['fornecedor'], 'id, loja, nome, codigo, documento');
		
		// Cadastra a solicitacao
		$dadosCotacao = [
			'fornecedor' => json_encode([
				'id' => $fornecedor->id,
				'loja' => $fornecedor->loja,
				'nome' => $fornecedor->nome,
				'codigo' => $fornecedor->codigo,
				'documento' => $fornecedor->documento,
			]),
			'produtos' => $dados['produtos'],
			'valor_total' => $dados['valor_total'],
			'condicao_pagamento' => $dados['condicao_pagamento'],
			'forma_pagamento' => $dados['forma_pagamento'],
			'id_usuario' => $idUsuario,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		];

		if (!empty($_FILES['anexo_cotacao']) && $pathAnexo) $dadosSolicitacao['path_anexo'] = $pathAnexo;

		if (!$this->cotacao->editar($idCotacao, $dadosCotacao)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao editar a cotação, tente novamente.']));
		}

		exit (json_encode([
			'status' => '1',
			'mensagem' => 'Cotação editada com sucesso.',
			'cotacao' => [
				'id' => $idCotacao,
				'fornecedor' => [
					'id' => $fornecedor->id,
					'loja' => $fornecedor->loja,
					'nome' => $fornecedor->nome,
					'codigo' => $fornecedor->codigo,
					'documento' => $fornecedor->documento,
				],
				'valorTotal' => $dados['valor_total'],
				'condicaoPagamento' => $dados['condicao_pagamento'],
				'formaPagamento' => $dados['forma_pagamento'],
			]
		]));
	}

}