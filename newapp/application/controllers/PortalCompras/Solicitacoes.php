<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Solicitacoes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');

		$this->load->model('usuario');
		$this->load->model('portal_compras/solicitacao');
		$this->load->model('portal_compras/log_solicitacao', 'log');
		$this->load->model('portal_compras/email');


		$this->upload_path_solicitacao = 'uploads/portal_compras/solicitacoes/anexos/';
		$this->file_name_solicitacao = '';
		$this->upload_path_rateio = 'uploads/portal_compras/solicitacoes/anexos_rateio/';
		$this->file_name_rateio = '';

		//Busca os dados do usuario logado
		$this->idUsuario = $this->auth->get_login_dados('user');
		$usuario = $this->usuario->getUser($this->idUsuario, 'id, funcao_portal as funcaoPortal, login as email')[0];
		$this->funcaoUsuario = !empty($usuario) ? $usuario->funcaoPortal : null;
		$this->emailUsuario = !empty($usuario) ? $usuario->email : null;
	}

	public function index($tipoSolicitacao = 'requisicao', $id = null) {
		$this->auth->is_allowed('vis_solicitacoes_portal_compras');

		if($this->funcaoUsuario === 'area_fiscal') $tipoSolicitacao = 'pedido';

		$dados = [
			'titulo' => lang('portal_compras'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'validate-form', 'select2'],
			'idSolicitacao' => $id ? $id : null,
			'funcaoUsuario' => $this->funcaoUsuario,
			'idUsuario' => $this->idUsuario,
			'tipoSolicitacao' => $tipoSolicitacao,
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('portal_compras/solicitacoes/modal_detalhe_situacao');
		$this->load->view('portal_compras/solicitacoes/modal_nota_fiscal');
		$this->load->view('portal_compras/solicitacoes/modal_boleto');
		$this->load->view('portal_compras/solicitacoes/index');
		$this->load->view('fix/footer_NS');
	}

	public function listar($tipoSolicitacao = 'requisicao') {
		$dados = [];
		$statusRequisicaoPedido = ['aguardando_produto_cotacao', 'aguardando_cotacao', 'aguardando_confirmacao_cotacao', 'aguardando_aprovacao'];
		$statusPedidos = ['aprovado', 'reprovado', 'aguardando_pre_nota', 'aguardando_fiscal', 'aguardando_boleto', 'finalizado'];

		// Busca apenas as solicitacoes ativas
		$solicitacoes = $this->solicitacao->listar( 'solicitacao.*, usuario.nome as nome_usuario',  ['solicitacao.status' => 'ativo'], true );
		
		if (!empty($solicitacoes)) {
			$this->load->model('portal_compras/cotacao');
			$idsCotacoes = array_column_custom($solicitacoes, 'id_cotacoes');
			$cotacoes = $this->cotacao->buscarPorIds($idsCotacoes, 'id, fornecedor,tipo_especie');

			$dadosCotacoes = [];
			if (!empty($cotacoes)) {
				foreach ($cotacoes as $cotacao) {
					$dadosCotacoes[$cotacao->id] = $cotacao;
				}
			};

			// Busca os dados dos centros de custo
			$this->load->model('portal_compras/centro_custo');
			$idsCentroCusto = array_column_custom($solicitacoes, 'id_centro_custo');
			$centrosCusto = $this->centro_custo->listarPorIds($idsCentroCusto, 'id, codigo, descricao');
			
			$dadosCentrosCustos = [];
			if (!empty($centrosCusto)) {
				foreach ($centrosCusto as $centroCusto) {
					$dadosCentrosCustos[$centroCusto->id] = $centroCusto;
				}
			}

			foreach ($solicitacoes as $solicitacao) {
				$aprovadores = !empty($solicitacao->acao_aprovadores) ? json_decode($solicitacao->acao_aprovadores) : [];

				$idAprovadores = [];
				if(!empty($aprovadores->diretor)) $idAprovadores[] = $aprovadores->diretor->id;
				if(!empty($aprovadores->cfo)) $idAprovadores[] = $aprovadores->cfo->id;
				if(!empty($aprovadores->ceo)) $idAprovadores[] = $aprovadores->ceo->id;

				// verifica se sera carrega as requisicoes ou os pedidos
				if ($tipoSolicitacao === 'requisicao' && !in_array($solicitacao->situacao, $statusRequisicaoPedido)) continue;
				if ($tipoSolicitacao === 'pedido' && !in_array($solicitacao->situacao, $statusPedidos)) continue;

				// Verifica se o usuario logado é aprovador ou area de compras e lista as solicitacoes de acordo com a funcao
				if($this->funcaoUsuario === 'aprovador' && (in_array($solicitacao->situacao, ['aguardando_produto_cotacao', 'aguardando_cotacao', 'aguardando_confirmacao_cotacao'])  || !in_array($this->idUsuario, $idAprovadores))) continue;

				// Busca a quantidade de produtos da solicitacao
				$quantidadeProdutos = 0;
				$produtos = !empty($solicitacao->produtos) ? json_decode($solicitacao->produtos) : [];
				if (!empty($produtos)) {
					foreach ($produtos as $produto) {
						$quantidadeProdutos += $produto->quantidade;
					}
				}
			
				$minhaCotacao = !empty($dadosCotacoes[$solicitacao->id_cotacoes]) ? $dadosCotacoes[$solicitacao->id_cotacoes] : null;
				$fornecedor = !empty($minhaCotacao->fornecedor) ? json_decode($minhaCotacao->fornecedor) : null;
				$centroDeCusto = !empty($dadosCentrosCustos[$solicitacao->id_centro_custo]) ? $dadosCentrosCustos[$solicitacao->id_centro_custo] : null;
				
				$dados[] = [
					'id' => (int)$solicitacao->id,
					'nomeUsuario' => $solicitacao->nome_usuario,
					'centroCusto' => !empty($centroDeCusto) ? $centroDeCusto->codigo .' - ' . $centroDeCusto->descricao : null,
					'quantidadeProdutos' => $quantidadeProdutos,
					'dataCadastro' => $solicitacao->data_cadastro,
					'situacao' => $solicitacao->situacao,
					'aprovadores' => $aprovadores,
					'idUsuario' => $solicitacao->id_usuario,
					'fornecedor' => $fornecedor,
					'numeroPedido' => $solicitacao->codigo_pedido_erp,
					'tipoSolicitacao' => $solicitacao->tipo,
					'idUsuarioComprasAtuante' => $solicitacao->id_usuario_compras,
					'tipoEspecie' => !empty($minhaCotacao) ? $minhaCotacao->tipo_especie : "",
				];
			};	
		};

		exit(json_encode($dados));
	}

	public function remover($idSolicitacao) {
		// Valida o $idSolicitacao
		$this->auth->is_allowed('rem_solicitacoes_portal_compras');
		if (!is_numeric($idSolicitacao) || $idSolicitacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id inválido!']));

		if (!$this->solicitacao->alterarStatus($idSolicitacao, 'inativo')) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao remover a solicitacao!']));
		}

		// Salva o log da ação
		$this->log->cadastrar([
			'acao' => 'remover',
			'id_usuario' => $this->idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);
		
		exit(json_encode(['status' => '1', 'mensagem' => 'Solicitação removida com sucesso!']));
	}

	public function cadastrar($tipoSolicitacao = 'requisicao') {
		$this->auth->is_allowed('cad_solicitacoes_portal_compras');
		$dados = [
			'titulo' => lang('portal_compras'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'validate-form', 'select2'],
			'acao' => 'cadastrar',
			'funcaoUsuario' => $this->funcaoUsuario,
			'tipoSolicitacao' => $tipoSolicitacao,
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_cadastrar_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_motivo_selecao_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_motivo_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/index');
		$this->load->view('fix/footer_NS');
	}

	public function valida_anexo() {

		if (empty ($_FILES['anexo']['name'])) {
			return TRUE;
		}

		if (!is_dir($this->upload_path_solicitacao)) {
			mkdir($this->upload_path_solicitacao, 0777, TRUE);
		}

		// sanitariza o nome do arquivo
		$file_name_anexo_solicitacao = removerAcentos($_FILES['anexo']['name']); // remove acentos
		$file_name_anexo_solicitacao = str_replace(' ', '_', $file_name_anexo_solicitacao); // substitui espaços por underline
		$this->file_name_solicitacao = preg_replace(['/[^A-Za-z0-9._\?]/'], '', $file_name_anexo_solicitacao); // remove caracteres especiais

		$config['upload_path'] = $this->upload_path_solicitacao;
		$config['allowed_types'] = 'pdf|jpg|jpeg|png';
		$config['max_size'] = 5120; // 5MB
		$config['file_name'] = $this->file_name_solicitacao;

		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('anexo')) {
			$this->form_validation->set_message('valida_anexo', 'Anexo Solicitação: ' . strip_tags($this->upload->display_errors()));
			return FALSE;
		}
		
		return TRUE;
	}

	public function valida_anexo_rateio() {
		if (empty ($_FILES['anexo_rateio']['name'])) {
			$this->form_validation->set_message('valida_anexo_rateio', 'O campo Anexo Rateio é obrigatório.');
			return FALSE;
		}

		if (!is_dir($this->upload_path_rateio)) {
			mkdir($this->upload_path_rateio, 0777, TRUE);
		}

		// sanitariza o nome do arquivo
		$file_name_anexo_rateio = removerAcentos($_FILES['anexo_rateio']['name']); // remove acentos
		$file_name_anexo_rateio = str_replace(' ', '_', $file_name_anexo_rateio); // substitui espaços por underline
		$this->file_name_rateio = preg_replace(['/[^A-Za-z0-9._\?]/'], '', $file_name_anexo_rateio); // remove caracteres especiais

		$config['upload_path'] = $this->upload_path_rateio;
		$config['allowed_types'] = 'xls|xlsx|pdf'; // apenas pdf e excel
		$config['max_size'] = 5120; // 5MB
		$config['file_name'] = $this->file_name_rateio;

		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('anexo_rateio')) {
			$this->form_validation->set_message('valida_anexo_rateio', 'Anexo Rateio: ' . strip_tags($this->upload->display_errors()));
			return FALSE;
		}

		return TRUE;
	}

	public function salvarDados() {
		$dados = $this->input->post();

		// Validação dos campos
		$this->form_validation->set_rules('filial', 'Filial', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('capex', 'CAPEX', 'trim|required|in_list[sim,nao]');
		$this->form_validation->set_rules('rateio', 'Rateio', 'trim|required|in_list[sim,nao]');
		$this->form_validation->set_rules('centro_custo', 'Centro de Custo', 'trim|required|min_length[4]|max_length[120]');
		$this->form_validation->set_rules('tipo_requisicao', 'Tipo de Requisição', 'trim|required|in_list[contrato, nao_recorrente]');
		$this->form_validation->set_rules('empresa', 'Departamento', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('motivo_compra', 'Motivo da Compra', 'trim|required|min_length[20]|max_length[500]');
		$this->form_validation->set_rules('tipo', 'Tipo', 'trim|required|in_list[requisicao,pedido]');
		$this->form_validation->set_rules('anexo', 'Anexo Solicitação', 'callback_valida_anexo');
		$this->form_validation->set_rules('produtos', 'Produtos', 'required');
		if ($dados['rateio'] == 'sim') $this->form_validation->set_rules('anexo_rateio', 'Anexo Rateio', 'callback_valida_anexo_rateio');
		if ($dados['tipo'] === 'pedido') {
			$this->form_validation->set_rules('id_cotacao', 'Cotação Selecionada', 'required|numeric|greater_than[0]');
			$this->form_validation->set_rules('ids_cotacoes', 'Cotações', 'required');
			$this->form_validation->set_rules('motivo_selecao_cotacao', 'Motivo da Cotação', 'trim|min_length[5]|max_length[240]');
			$this->form_validation->set_rules('motivo_cotacao', 'Motivo da Cotação', 'trim|min_length[5]|max_length[240]');
		}
		
		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		$pathAnexo = $this->file_name_solicitacao ? $this->upload_path_solicitacao . $this->file_name_solicitacao : '';
		$pathAnexoRateio = $this->file_name_rateio ? $this->upload_path_rateio . $this->file_name_rateio : '';
		$produtos = !empty($dados['produtos']) ? json_decode($dados['produtos']) : [];

		// Se a solicitacao for de pedido, entao usa os produtos que estao na cotacao
		$valorTotalSolicitacao = 0;
		$aprovadores = [];

		if ($dados['tipo'] == 'pedido' && !empty($dados['id_cotacao'])) {
			$this->load->model('portal_compras/cotacao');
			$cotacao = $this->cotacao->buscar($dados['id_cotacao'], 'id, valor_total as valorTotal, produtos');

			$produtosCotacao = !empty($cotacao->produtos) ? json_decode($cotacao->produtos) : [];
			if (!empty($produtosCotacao)) $produtos = $produtosCotacao;

			$valorTotalSolicitacao = $cotacao->valorTotal;

			$aprovadores = $this->montrarObjetoAprovadores((int)$dados['centro_custo'], (float)$valorTotalSolicitacao);
			if (empty($aprovadores['diretor'])) {
				exit (json_encode(['status' => '-1', 'mensagem' => 'Não foi possível encontrar um diretor para o centro de custo informado. Por favor, vá até a tela de configuração e vincule um diretor.']));
			}
		}

		$situacao = '';
		if($dados['tipo'] === 'pedido') $situacao = 'aguardando_aprovacao';
		else if($dados['tipo'] === 'requisicao') {
			if (empty($produtos)) $situacao = 'aguardando_produto_cotacao';
			else $situacao = 'aguardando_cotacao';
		}

		$this->load->model('portal_compras/centro_custo');
		$centroCusto = $this->centro_custo->buscarPeloCodigo($dados['centro_custo'], 'id');

		// Cadastra a solicitacao
		$novaSolicitacao = [
			'tipo' => $dados['tipo'],
			'valor_total' => $valorTotalSolicitacao,
			'produtos' => json_encode($produtos),
			'motivo_compra' => $dados['motivo_compra'],
			'tipo_requisicao' => $dados['tipo_requisicao'],
			'capex' => $dados['capex'],
			'rateio' => $dados['rateio'],
			'anexo_rateio' => $pathAnexoRateio ? $pathAnexoRateio : NULL,
			'anexo_solicitacao' => $pathAnexo ? $pathAnexo : NULL,
			'situacao' => $situacao,
			'id_usuario' => $this->idUsuario,
			'acao_aprovadores' => !empty($aprovadores) ? json_encode($aprovadores) : NULL,
			'motivo_cotacao' => !empty($dados['motivo_cotacao']) ? $dados['motivo_cotacao'] : NULL,
			'id_cotacoes' => !empty($dados['id_cotacao']) ? $dados['id_cotacao'] : NULL,
			'id_filiais' => $dados['filial'],
			'id_centro_custo' => !empty($centroCusto) ? $centroCusto->id : NULL,
			'id_empresas' => $dados['empresa'],
			'data_cadastro' => date('Y-m-d H:i:s'),
		];

		$idSolicitacao = $this->solicitacao->cadastrar($novaSolicitacao);
		if (empty ($idSolicitacao)) {
			$mensagemErro = 'Erro ao cadastrar a requisição, tente novamente mais tarde.';
			if ($dados['tipo'] === 'pedido') $mensagemErro = 'Erro ao cadastrar o pedido, tente novamente mais tarde.';
			exit (json_encode(['status' => '-1', 'mensagem' => $mensagemErro]));
		}

		// Vincula as sugestoes de cotações a solicitacao
		$idsCotacoes = !empty($dados['ids_cotacoes']) ? json_decode($dados['ids_cotacoes']) : [];
		$solicitacoesCotacoes = [];
		if (!empty($idsCotacoes)) {
			foreach ($idsCotacoes as $idCotacao) {
				$solicitacoesCotacoes[] = [
					'id_solicitacoes' => $idSolicitacao,
					'id_cotacoes' => $idCotacao,
				];
			}

			if (!$this->solicitacao->vincularCotacoes($solicitacoesCotacoes)) {
				exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao vincular as cotações à solicitação, tente novamente.']));
			}
		}

		// Atualiza a cotacao informando o motivo da selecao
		if (!empty($dados['id_cotacao']) && !empty($dados['motivo_selecao_cotacao'])) {
			$this->cotacao->editar($dados['id_cotacao'], ['motivo_selecao_cotacao' => $dados['motivo_selecao_cotacao']]);
		}

		// Envia o email de acordo com a situação da solicitação
		if ($situacao === 'aguardando_aprovacao') {
			// Envia o email para o solicitante
			$this->email->send($idSolicitacao, 'solicitante');
			
			// Envia o email para os aprovadores
			$this->email->send($idSolicitacao, 'aprovador');
		} 
		else if (in_array($situacao, ['aguardando_produto_cotacao', 'aguardando_cotacao'])) {
			// Envia o email para os area_compras
			$this->email->send($idSolicitacao, 'area_compras');
		}

		// Salva o log da ação
		$this->log->cadastrar([
			'acao' => 'cadastrar',
			'id_usuario' => $this->idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);

		$mensagemSucesso = 'Requisição cadastrada com sucesso.';
		if ($dados['tipo'] === 'pedido') $mensagemSucesso = 'Pedido cadastrado com sucesso.';

		exit (json_encode([
			'status' => '1',
			'mensagem' => $mensagemSucesso,
		]));
	}

	private function montrarObjetoAprovadores($codigoCentroCusto, $valorTotalSolicitacao) {
		$this->load->model('portal_compras/configuracao');

		$configuracao = $this->configuracao->buscar();
		$alcadaAprovacao  = json_decode($configuracao->alcada_de_aprovacao);
		$centrosCustoAprovacao = json_decode($configuracao->centros_de_custo);
		$aprovadores_ceo_cfo = json_decode($configuracao->aprovadores);
	
		$aprovadores = [];
		$idsDiretores = [];

		if (!empty($centrosCustoAprovacao)) {
			foreach ($centrosCustoAprovacao as $centroCustoAprovacao) {
				$diretor = explode('-', $centroCustoAprovacao->diretor);

				if ($centroCustoAprovacao->id == $codigoCentroCusto) {
					$aprovadores['diretor'] = [
						'id' => trim($diretor[0]),
						'resultado' => 'aguardando',
					];

					$idsDiretores[] = trim($diretor[0]);
					continue;
				}
			}
		}

		if ($valorTotalSolicitacao > (float)$alcadaAprovacao->diretor_max) {
			$aprovadores['cfo'] = [
				'id' => trim($aprovadores_ceo_cfo->cfo),
				'resultado' => 'aguardando',
			];

			$idsDiretores[] = trim($aprovadores_ceo_cfo->cfo);
		}

		if ($valorTotalSolicitacao > (float) $alcadaAprovacao->cfo_max) {
			$aprovadores['ceo'] =  [
				'id' => trim($aprovadores_ceo_cfo->ceo),
				'resultado' => 'aguardando',
			];

			$idsDiretores[] = trim($aprovadores_ceo_cfo->ceo);
		}

		//Busca os dados dos diretores
		if(!empty($idsDiretores)){
			$this->load->model('usuario');
			$diretores = $this->usuario->listDadosUsersShownet($idsDiretores, 'id, nome, login as email');
			if (!empty($diretores)) {
				foreach ($diretores as $diretor) {
					if (!empty($aprovadores['diretor']) && $aprovadores['diretor']['id'] == $diretor->id) {
						$aprovadores['diretor']['email'] = $diretor->email;
						$aprovadores['diretor']['nome'] = $diretor->nome;
					}
	
					if (!empty($aprovadores['cfo']) && $aprovadores['cfo']['id'] == $diretor->id) {
						$aprovadores['cfo']['email'] = $diretor->email;
						$aprovadores['cfo']['nome'] = $diretor->nome;
					}
	
					if (!empty($aprovadores['ceo']) && $aprovadores['ceo']['id'] == $diretor->id) {
						$aprovadores['ceo']['email'] = $diretor->email;
						$aprovadores['ceo']['nome'] = $diretor->nome;
					}
				}
			}
		}

		return $aprovadores;
	}

	public function editar($id, $tipoSolicitacao = 'requisicao') {
		$this->auth->is_allowed('edi_solicitacoes_portal_compras');

		// Valida o $id
		if (empty($id) || !is_numeric($id) || $id <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id inválido!']));

		$dados = [
			'titulo' => lang('portal_compras'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'validate-form', 'select2'],
			'idSolicitacao' => $id,
			'acao' => 'editar',
			'funcaoUsuario' => $this->funcaoUsuario,
			'tipoSolicitacao' => $tipoSolicitacao,
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_cadastrar_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_motivo_selecao_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_motivo_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/index');
		$this->load->view('fix/footer_NS');
	}

	public function buscar($id) {
		// Valida o $id
		if (empty($id) || !is_numeric($id) || $id <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id inválido!']));

		$solicitacao = $this->solicitacao->buscar($id);
		if (empty($solicitacao)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Solicitação não encontrada!']));
		}

		// Busca os dados dos centros de custo
		$this->load->model('portal_compras/centro_custo');
		$centroCusto = $this->centro_custo->buscar($solicitacao->id_centro_custo, 'id, codigo, descricao');

		// Busca os dados das filiais
		$this->load->model('portal_compras/empresa');
		$empresa = $this->empresa->buscar($solicitacao->id_empresas, 'id, codigo, nome');

		// Busca os dados das filiais
		$this->load->model('portal_compras/filial');
		$filial = $this->filial->buscar($solicitacao->id_filiais, 'id, codigo, nome');

		$produtos = !empty($solicitacao->produtos) ? json_decode($solicitacao->produtos) : [];
		$cotacoes = $this->solicitacao->buscarCotacoesPorSolicitacao($id);
		$cotacaoFinal = NULL;

		$dadosCotacoes = [];
		if (!empty($cotacoes)) {
			foreach ($cotacoes as $cotacao) {
				$cotacaoFormatada = [
					'id' => $cotacao->id,
					'fornecedor' => !empty($cotacao->fornecedor) ? json_decode($cotacao->fornecedor) : null,
					'valorTotal' => $cotacao->valor_total,
					'condicaoPagamento' => $cotacao->condicao_pagamento,
					'formaPagamento' => $cotacao->forma_pagamento,
					'pathAnexo' => $cotacao->path_anexo,
					'produtos' => $cotacao->produtos,
					'tipoEspecie' => $cotacao->tipo_especie,
					'motivoSelecaoCotacao' => $cotacao->motivo_selecao_cotacao,
				];

				$dadosCotacoes[] = $cotacaoFormatada;

				if ($cotacao->id == $solicitacao->id_cotacoes) {
					$cotacaoFinal = $cotacaoFormatada;
				}
			}
		}

		$dadosSolicitacao = [
			'id' => $solicitacao->id,
			'tipo' => $solicitacao->tipo,
			'empresa' => !empty($empresa) ? $empresa : null,
			'filial' => !empty($filial) ? $filial : null,
			'valorTotal' => $solicitacao->valor_total,
			'centroCusto' => !empty($centroCusto) ? $centroCusto : null,
			'motivoCompra' => $solicitacao->motivo_compra,
			'motivoCotacao' => $solicitacao->motivo_cotacao,
			'tipoRequisicao' => $solicitacao->tipo_requisicao,
			'capex' => $solicitacao->capex,
			'rateio' => $solicitacao->rateio,
			'anexoRateio' => $solicitacao->anexo_rateio,
			'anexoSolicitacao' => $solicitacao->anexo_solicitacao,
			'dataCadastro' => $solicitacao->data_cadastro,
			'situacao' => $solicitacao->situacao,
			'cotacao' => $cotacaoFinal,
			'aprovadores' => !empty($solicitacao->acao_aprovadores) ? json_decode($solicitacao->acao_aprovadores) : [],
		];

		$dados = [
			'solicitacao' => $dadosSolicitacao,
			'produtos' => $produtos,
			'cotacoes' => $dadosCotacoes,
		];

		exit(json_encode(['status' => '1', 'dados' => $dados]));
	}

	public function editarDados($idSolicitacao) {
		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id inválido!']));

		$dados = $this->input->post();

		// Validação dos campos
		$this->form_validation->set_rules('capex', 'CAPEX', 'trim|required|in_list[sim,nao]');
		$this->form_validation->set_rules('rateio', 'Rateio', 'trim|required|in_list[sim,nao]');
		$this->form_validation->set_rules('tipo_requisicao', 'Tipo de Requisição', 'trim|required|in_list[contrato, nao_recorrente]');
		$this->form_validation->set_rules('empresa', 'Departamento', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('motivo_compra', 'Motivo da Compra', 'trim|required|min_length[20]|max_length[500]');
		$this->form_validation->set_rules('filial', 'Filial', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('centro_custo', 'Centro de Custo', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('anexo', 'Anexo Solicitação', 'callback_valida_anexo');
		$this->form_validation->set_rules('tipo', 'Tipo', 'trim|required|in_list[requisicao,pedido]');
		$this->form_validation->set_rules('produtos', 'Produtos', 'required');
		if ($dados['rateio'] === 'sim' && !empty($_FILES['anexo_rateio'])) {
			$this->form_validation->set_rules('anexo_rateio', 'Anexo Rateio', 'callback_valida_anexo_rateio');
		}
		if ($dados['tipo'] == 'pedido') {
			$this->form_validation->set_rules('id_cotacao', 'Cotação Selecionada', 'required|numeric|greater_than[0]');
			$this->form_validation->set_rules('idsCotacoes', 'Cotações', 'required');
			$this->form_validation->set_rules('motivo_selecao_cotacao', 'Motivo da Cotação', 'trim|min_length[5]|max_length[240]');
			$this->form_validation->set_rules('motivo_cotacao', 'Motivo da Cotação', 'trim|min_length[5]|max_length[240]');
		}
		
		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		// Upload dos anexos
		$pathAnexo = $this->file_name_solicitacao ? $this->upload_path_solicitacao . $this->file_name_solicitacao : '';
		$pathAnexoRateio = $this->file_name_rateio ? $this->upload_path_rateio . $this->file_name_rateio : '';
		$produtos = !empty($dados['produtos']) ? json_decode($dados['produtos']) : [];

		// Se a solicitacao for de pedido, entao usa os produtos que estao na cotacao
		$valorTotalSolicitacao = 0;
		$aprovadores = [];

		if ($dados['tipo'] === 'pedido' && !empty($dados['id_cotacao'])) {
			$this->load->model('portal_compras/cotacao');
			$cotacao = $this->cotacao->buscar($dados['id_cotacao'], 'id, valor_total as valorTotal, produtos');

			$produtosCotacao = !empty($cotacao->produtos) ? json_decode($cotacao->produtos) : [];
			if (!empty($produtosCotacao)) $produtos = $produtosCotacao;

			$valorTotalSolicitacao = $cotacao->valorTotal;

			// Monta o objeto de aprovadores para a solicitacao
			$aprovadores = $this->montrarObjetoAprovadores((int)$dados['centro_custo'], (float)$valorTotalSolicitacao);
			if (empty($aprovadores['diretor'])) {
				exit(json_encode(['status' => '-1', 'mensagem' => 'Não foi possível encontrar um diretor para o centro de custo informado. Por favor, vá até a tela de configuração e vincule um diretor.']));
			}
		}

		$situacao = '';
		if($dados['tipo'] === 'pedido') $situacao = 'aguardando_aprovacao';
		else if($dados['tipo'] === 'requisicao') {
			if (empty($produtos)) $situacao = 'aguardando_produto_cotacao';
			else $situacao = 'aguardando_cotacao';
		}

		$this->load->model('portal_compras/centro_custo');
		$centroCusto = $this->centro_custo->buscarPeloCodigo((int)$dados['centro_custo'], 'id');
		
		// Cadastra a solicitacao
		$dadosSolicitacao = [
			'valor_total' => $valorTotalSolicitacao,
			'produtos' => $dados['produtos'],
			'motivo_compra' => $dados['motivo_compra'],
			'tipo_requisicao' => $dados['tipo_requisicao'],
			'capex' => $dados['capex'],
			'rateio' => $dados['rateio'],
			'situacao' => $situacao,
			'id_usuario' => $this->idUsuario,
			'acao_aprovadores' => !empty($aprovadores) ? json_encode($aprovadores) : NULL,
			'motivo_cotacao' => !empty($dados['motivo_cotacao']) ? $dados['motivo_cotacao'] : NULL,
			'id_cotacoes' => !empty($dados['id_cotacao']) ? $dados['id_cotacao'] : NULL,
			'id_filiais' => $dados['filial'],
			'id_centro_custo' => !empty($centroCusto) ? $centroCusto->id : NULL,
			'id_empresas' => $dados['empresa'],
			'data_modificacao' => date('Y-m-d H:i:s'),
		];

		if ($dados['possuiAnexoSolicitacao'] == 'sim' && !empty($_FILES['anexo']) && $pathAnexo) $dadosSolicitacao['anexo_solicitacao'] = $pathAnexo;
		else if ($dados['possuiAnexoSolicitacao'] == 'nao') $dadosSolicitacao['anexo_solicitacao'] = NULL;

		if ($dados['rateio'] === 'sim' && !empty($_FILES['anexo_rateio']) && $pathAnexoRateio) $dadosSolicitacao['anexo_rateio'] = $pathAnexoRateio;
		else if ($dados['rateio'] === 'nao') $dadosSolicitacao['anexo_rateio'] = NULL;

		if (!$this->solicitacao->editar($idSolicitacao, $dadosSolicitacao)) {
			$mensagemErro = 'Erro ao editar a requisição, tente novamente mais tarde.';
			if ($dados['tipo'] === 'pedido') $mensagemErro = 'Erro ao editar o pedido, tente novamente mais tarde.';

			exit (json_encode(['status' => '-1', 'mensagem' => $mensagemErro]));
		}

		// Remove os vinculos de cotações
		$this->solicitacao->removerVinculosCotacoes($idSolicitacao);

		// Vincula as sugestoes de cotações a solicitacao
		$idsCotacoes = !empty($dados['ids_cotacoes']) ? json_decode($dados['ids_cotacoes']) : [];
		if (!empty($idsCotacoes)) {
			$solicitacoesCotacoes = [];
			foreach ($idsCotacoes as $idCotacao) {
				$solicitacoesCotacoes[] = [
					'id_solicitacoes' => $idSolicitacao,
					'id_cotacoes' => $idCotacao,
				];
			}

			if (!$this->solicitacao->vincularCotacoes($solicitacoesCotacoes)) {
				exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao vincular as cotações à solicitação, tente novamente.']));
			}
		}

		// Atualiza a cotacao informando o motivo da selecao
		if (!empty($dados['id_cotacao']) && !empty($dados['motivo_selecao_cotacao'])) {
			$this->cotacao->editar($dados['id_cotacao'], ['motivo_selecao_cotacao' => $dados['motivo_selecao_cotacao']]);
		}
		
		// Salva o log da acao
		$this->log->cadastrar([
			'acao' => 'editar',
			'id_usuario' => $this->idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);

		$mensagemSucesso = 'Requisição editada com sucesso.';
		if ($dados['tipo'] === 'pedido') $mensagemSucesso = 'Pedido editado com sucesso.';

		exit (json_encode(['status' => '1', 'mensagem' => $mensagemSucesso]));
	}

	public function acaoSobreProdutoECotacao($idSolicitacao, $acao) {
		//Verifica se o usuario tem acesso a pagina
		$possuiAcesso = in_array($this->funcaoUsuario, ['solicitante', 'area_compras']) && (
				($this->funcaoUsuario === 'solicitante' &&  $acao === 'selecionar_cotacao')
				|| ($this->funcaoUsuario === 'area_compras' &&  in_array($acao, ['adicionar_produto_cotacao', 'adicionar_cotacao']))
			);

		if (!$possuiAcesso) redirect('erros/erro_403');

		// Valida o $idSolicitacao
		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id da solicitação inválido!']));

		$solicitacao = $this->solicitacao->buscar($idSolicitacao, 'id, valor_total as valorTotalSolicitacao, id_usuario_compras as idUsuarioCompras');
		$valorTotalSolicitacao = $solicitacao->valorTotalSolicitacao;

		// vincula a solicitação ao usuario da area de compras
		if ($acao === 'adicionar_cotacao' && $this->funcaoUsuario === 'area_compras' && $this->idUsuario != $solicitacao->idUsuarioCompras) {
			if(!$this->solicitacao->editar($idSolicitacao, ['id_usuario_compras' => $this->idUsuario])) {
				exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao vincular a solicitação ao usuário logado, tente novamente, mais tarde.']));
			}
		}

		$dados = [
			'titulo' => lang('portal_compras'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'validate-form', 'select2'],
			'idSolicitacao' => $idSolicitacao,
			'valorTotalSolicitacao' => $valorTotalSolicitacao,
			'acao' => $acao,
			'funcaoUsuario' => $this->funcaoUsuario,
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_cadastrar_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_motivo_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_motivo_selecao_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/index');
		$this->load->view('fix/footer_NS');
	}

	public function adicionarCotacao($idSolicitacao) {
		// Valida o $idSolicitacao
		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Id da solicitação inválido!']));
		}

		$dados = [
			'titulo' => lang('portal_compras'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'validate-form', 'select2'],
			'idSolicitacao' => $idSolicitacao,
			'acao' => 'adicionar_cotacao'
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/index');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_cadastrar_cotacao');
		$this->load->view('portal_compras/solicitacoes/cadastrar_editar/modal_motivo_cotacao');
		$this->load->view('fix/footer_NS');
	}

	public function adicionarCotacoes($idSolicitacao) {
		$dados = $this->input->post();

		// Valida o $idSolicitacao
		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id da solicitação, inválido!']));

		$idsCotacoes = !empty($dados['ids_cotacoes']) ? json_decode($dados['ids_cotacoes']) : [];
		if (empty($idsCotacoes)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Selecione ao menos uma cotação para adicionar à solicitação.']));
		}

		$this->load->model('portal_compras/cotacao');
		$cotacoes = $this->cotacao->buscarPorIds($idsCotacoes, 'id, valor_total as valorTotal, produtos');

		$solicitacoesCotacoes = [];
		foreach ($idsCotacoes as $idCotacao) {
			$solicitacoesCotacoes[] = [
				'id_solicitacoes' => $idSolicitacao,
				'id_cotacoes' => $idCotacao,
			];
		}

		if (!$this->solicitacao->vincularCotacoes($solicitacoesCotacoes)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao adicionar as cotações à solicitação, tente novamente.']));
		}

		// Atualiza a solicitacao
		$dadosSolicitacao = [
			'situacao' => 'aguardando_confirmacao_cotacao',
			'motivo_cotacao' => !empty($dados['motivo_cotacao']) ? $dados['motivo_cotacao'] : NULL
		];
		
		if (count($idsCotacoes) == 1) {
			$dadosSolicitacao['situacao'] = 'aguardando_aprovacao';
			$dadosSolicitacao['produtos'] = $cotacoes[0]->produtos;
			$dadosSolicitacao['valor_total'] = $cotacoes[0]->valorTotal;
			$dadosSolicitacao['id_cotacoes'] = $idsCotacoes[0];

			$solicitacao = $this->solicitacao->buscar($idSolicitacao, 'id, id_centro_custo as idCentroCusto');

			$this->load->model('portal_compras/centro_custo');
			$centroCusto = $this->centro_custo->buscar($solicitacao->idCentroCusto, 'id, codigo');

			// Monta o objeto de aprovadores para a solicitacao
			$aprovadores = $this->montrarObjetoAprovadores((int)$centroCusto->codigo, (float)$cotacoes[0]->valorTotal);
			$dadosSolicitacao['acao_aprovadores'] = !empty($aprovadores) ? json_encode($aprovadores) : NULL;
		}

		if (!$this->solicitacao->editar($idSolicitacao, $dadosSolicitacao)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao adicionar as cotações à solicitação, tente novamente mais tarde.']));
		}

		// Envia o email de acordo com a situação da solicitação
		if ($dadosSolicitacao['situacao'] === 'aguardando_aprovacao') {
			// Envia o email para o solicitante
			$this->email->send($idSolicitacao, 'solicitante');

			// Envia o email para os aprovadores
			$this->email->send($idSolicitacao, 'aprovador');
		} 
		else if ($dadosSolicitacao['situacao'] === 'aguardando_confirmacao_cotacao') {
			// Envia o email para os area_compras
			$this->email->send($idSolicitacao, 'solicitante');
		}

		// Salva o log da ação
		$this->log->cadastrar([
			'acao' => 'adicionar_cotacao',
			'id_usuario' => $this->idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);

		exit (json_encode([ 'status' => '1', 'mensagem' => 'Cotações vinculadas com sucesso.' ]));
	}

	public function adicionarProdutosECotacoes($idSolicitacao) {
		$dados = $this->input->post();

		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Id da solicitação inválido!']));
		}

		$produtos = !empty($dados['produtos']) ? json_decode($dados['produtos']) : [];
		if (empty($produtos)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Adicione ao menos um produto para a solicitação.']));
		}

		$idsCotacoes = !empty($dados['ids_cotacoes']) ? json_decode($dados['ids_cotacoes']) : [];
		if (empty($idsCotacoes)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Selecione ao menos uma cotação para adicionar à solicitação.']));
		}

		$this->load->model('portal_compras/cotacao');
		$cotacoes = $this->cotacao->buscarPorIds($idsCotacoes, 'id, valor_total as valorTotal, produtos');

		$solicitacoesCotacoes = [];
		foreach ($idsCotacoes as $idCotacao) {
			$solicitacoesCotacoes[] = [
				'id_solicitacoes' => $idSolicitacao,
				'id_cotacoes' => $idCotacao,
			];
		}

		if (!$this->solicitacao->vincularCotacoes($solicitacoesCotacoes)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao adicionar as cotações à solicitação, tente novamente.']));
		}

		// Atualiza a solicitacao
		$dadosSolicitacao = [
			'situacao' => 'aguardando_confirmacao_cotacao',
			'produtos' => json_encode($produtos),
			'motivo_cotacao' => !empty($dados['motivo_cotacao']) ? $dados['motivo_cotacao'] : NULL,
		];
		
		if (count($idsCotacoes) == 1) {
			$dadosSolicitacao['situacao'] = 'aguardando_aprovacao';
			$dadosSolicitacao['produtos'] = $cotacoes[0]->produtos;
			$dadosSolicitacao['valor_total'] = $cotacoes[0]->valorTotal;
			$dadosSolicitacao['id_cotacoes'] = $idsCotacoes[0];

			$solicitacao = $this->solicitacao->buscar($idSolicitacao, 'id, id_centro_custo as idCentroCusto');

			$this->load->model('portal_compras/centro_custo');
			$centroCusto = $this->centro_custo->buscar($solicitacao->idCentroCusto, 'id, codigo');

			// Monta o objeto de aprovadores para a solicitacao
			$aprovadores = $this->montrarObjetoAprovadores((int)$centroCusto->codigo, (float)$cotacoes[0]->valorTotal);
			$dadosSolicitacao['acao_aprovadores'] = !empty($aprovadores) ? json_encode($aprovadores) : NULL;
		}

		if (!$this->solicitacao->editar($idSolicitacao, $dadosSolicitacao)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao adicionar as cotações à solicitação, tente novamente mais tarde.']));
		}

		// Envia o email de acordo com a situação da solicitação
		if ($dadosSolicitacao['situacao'] === 'aguardando_aprovacao') {
			// Envia o email para o solicitante
			$this->email->send($idSolicitacao, 'solicitante');

			// Envia o email para os aprovadores
			$this->email->send($idSolicitacao, 'aprovador');
		} 
		else if ($dadosSolicitacao['situacao'] === 'aguardando_confirmacao_cotacao') {
			// Envia o email para os area_compras
			$this->email->send($idSolicitacao, 'solicitante');
		}

		// Salva o log da ação
		$this->log->cadastrar([
			'acao' => 'adicionar_produto_cotacao',
			'id_usuario' => $this->idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);

		exit (json_encode([ 'status' => '1', 'mensagem' => 'Produtos e Cotações vinculados com sucesso.' ]));


	}

	public function buscarCotacoes($idSolicitacao) {
		// Valida o $idSolicitacao
		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id da solicitação inválido!']));

		$dados = [];
		$cotacoes = $this->solicitacao->buscarCotacoesPorSolicitacao($idSolicitacao);
		
		if (!empty($cotacoes)) {
			foreach ($cotacoes as $cotacao) {
				$dados[] = [
					'id' => $cotacao->id,
					'fornecedor' => !empty($cotacao->fornecedor) ? json_decode($cotacao->fornecedor) : null,
					'valorTotal' => $cotacao->valor_total,
					'formaPagamento' => $cotacao->forma_pagamento,
					'condicaoPagamento' => $cotacao->condicao_pagamento,
					'produtos' => !empty($cotacao->produtos) ? json_decode($cotacao->produtos) : null,
				];
			}
		}

		exit(json_encode(['status' => '1', 'cotacoes' => $dados]));
	}

	public function setarCotacao($idSolicitacao, $idCotacao) {
		$dados = $this->input->post();

		// Valida os parametros
		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id da solicitação inválido!']));
		if (empty($idCotacao) || !is_numeric($idCotacao) || $idCotacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id da cotação inválido!']));

		$this->form_validation->set_rules('motivo_selecao_cotacao', 'Motivo da seleçaõ da cotação', 'trim|min_length[5]|max_length[240]');
		
		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		$this->load->model('portal_compras/cotacao');
		$cotacao = $this->cotacao->buscar($idCotacao, 'id, valor_total as valorTotal, produtos');
		if (empty($cotacao)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Cotação não encontrada!']));
		}

		$solicitacao = $this->solicitacao->buscar($idSolicitacao, 'id, id_centro_custo as idCentroCusto');
		if (empty($solicitacao)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Solicitação não encontrada!']));
		}

		$this->load->model('portal_compras/centro_custo');
		$centroCusto = $this->centro_custo->buscar($solicitacao->idCentroCusto, 'id, codigo');

		// Monta o objeto de aprovadores para a solicitacao
		$aprovadores = $this->montrarObjetoAprovadores((int)$centroCusto->codigo, (float)$cotacao->valorTotal);

		$dadosCotacao = [
			'motivo_selecao_cotacao' => !empty($dados['motivo_selecao_cotacao']) ? $dados['motivo_selecao_cotacao'] : NULL,
		];

		if (!$this->cotacao->editar($idCotacao, $dadosCotacao)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao salvar o motivo da seleção da cotação, tente novamente mais tarde.']));
		}

		// atualiza a solicitacao
		$dadosSolicitacao = [
			'situacao' => 'aguardando_aprovacao',
			'produtos' => $cotacao->produtos,
			'valor_total' => $cotacao->valorTotal,
			'acao_aprovadores' => !empty($aprovadores) ? json_encode($aprovadores) : NULL,
			'id_cotacoes' => $idCotacao,
		];

		if (!$this->solicitacao->editar($idSolicitacao, $dadosSolicitacao)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao selecionar a cotação para a solicitação, tente novamente mais tarde.']));
		}

		// Envia o email para os aprovadores
		$this->email->send($idSolicitacao, 'aprovador');

		// Salva o log da ação
		$this->log->cadastrar([
			'acao' => 'selecionar_cotacao',
			'id_usuario' => $this->idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);

		exit (json_encode([ 'status' => '1', 'mensagem' => 'Cotação selecionada com sucesso.' ]));
	}

	public function visualizar($idSolicitacao) {
		$this->auth->is_allowed('vis_solicitacoes_portal_compras');

		// Valida o $idSolicitacao
		if (empty($idSolicitacao) || !is_numeric($idSolicitacao) || $idSolicitacao <= 0) exit(json_encode(['status' => '-1', 'mensagem' => 'Id inválido!']));

		$dados = [
			'titulo' => lang('portal_compras'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style'],
			'idSolicitacao' => $idSolicitacao,
			'funcaoUsuario' => $this->funcaoUsuario,
			'idUsuario' => $this->idUsuario,
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('portal_compras/solicitacoes/visualizar/modal_aprovacao');
		$this->load->view('portal_compras/solicitacoes/visualizar/index');
		$this->load->view('fix/footer_NS');
	}

	public function aprovacao() {
		$dados = $this->input->post();

		// Validação dos campos
		$this->form_validation->set_rules('id_solicitacao', 'Centro de Custo', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('acao', 'Ação', 'trim|required|in_list[devolvido,aprovado,reprovado]');
		if ($dados['acao'] !== 'aprovado') $this->form_validation->set_rules('motivo', 'Motivo', 'trim|required|min_length[5]|max_length[500]');

		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		$idSolicitacao = $dados['id_solicitacao'];
		$solicitacao = $this->solicitacao->buscar($idSolicitacao);

		$acaoAprovadores = !empty($solicitacao->acao_aprovadores) ? json_decode($solicitacao->acao_aprovadores, true) : [];
		if (empty($acaoAprovadores)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Não foi possível identificar os aprovadores da solicitação.']));
		}

		$todosResponderam = true;
		$resultadoSolicitacao = 'aprovado';

		foreach ($acaoAprovadores as $key => $aprovador) {
			if($aprovador['id'] == $this->idUsuario) {
				$acaoAprovadores[$key]['email'] = $this->emailUsuario;
				$acaoAprovadores[$key]['resultado'] = $dados['acao'];
				$acaoAprovadores[$key]['motivo'] = $dados['motivo'];
				$acaoAprovadores[$key]['dataHora'] = date('Y-m-d H:i:s');
			}

			if ($acaoAprovadores[$key]['resultado'] === 'aguardando' ) {
				$todosResponderam = false;
			}

			if ($acaoAprovadores[$key]['resultado'] === 'reprovado') {
				$resultadoSolicitacao = 'reprovado';
			}
		}

		$situacao = 'aguardando_aprovacao';
		if ($resultadoSolicitacao === 'reprovado') $situacao = 'reprovado';
		else if($todosResponderam && $resultadoSolicitacao === 'aprovado' ) $situacao = 'aguardando_pre_nota';

		$dadosSolicitacao = [
			'acao_aprovadores' => json_encode($acaoAprovadores),
			'situacao' => $situacao,
		];

		if (in_array($situacao, ['aguardando_pre_nota', 'aprovado'])) {
			// Requisita o ERP para incluir o pedido
			$cadastroPedido = $this->cadastrarPedidoCompraNoERP($idSolicitacao);
			if ($cadastroPedido['status'] === '-1') {
				exit(json_encode(['status' => '-1', 'mensagem' => $cadastroPedido['mensagem']]));
			}
		}

		if (!$this->solicitacao->editar($idSolicitacao, $dadosSolicitacao)) {
			$mensagem = 'Erro ao confirmar a ação, tente novamente mais tarde.';
			if($dados['acao'] === 'aprovado') $mensagem = 'Erro ao aprovar a solicitação, tente novamente mais tarde.';
			if($dados['acao'] === 'reprovado') $mensagem = 'Erro ao reprovar a solicitação, tente novamente mais tarde.';

			exit (json_encode(['status' => '-1', 'mensagem' => $mensagem]));
		}

		if (!$todosResponderam && $resultadoSolicitacao === 'aprovado') {
			// Envia o email para o proximo aprovador
			$this->email->send($idSolicitacao, 'aprovador');
		}


		if ($todosResponderam || $resultadoSolicitacao === 'reprovado') {
			// Envia o email para o solicitante
			$this->email->send($idSolicitacao, 'solicitante');
		}

		// Salva o log da ação
		$this->log->cadastrar([
			'acao' => $dados['acao'] === 'aprovado' ? 'aprovar' : 'reprovar',
			'id_usuario' => $this->idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);

		$mensagem = 'Ação confirmada com sucesso.';
		if($dados['acao'] === 'aprovado') $mensagem = 'Solicitação aprovada com sucesso.';
		if($dados['acao'] === 'reprovado') $mensagem = 'Solicitação reprovada com sucesso.';

		exit (json_encode([ 'status' => '1', 'mensagem' => $mensagem ]));
	}

	private function cadastrarPedidoCompraNoERP($idSolicitacao) {
		$codigoEmpresa = '01';
		$solicitacao = $this->solicitacao->buscar($idSolicitacao);

		$this->load->model('portal_compras/filial');
		$filial = $this->filial->buscar($solicitacao->id_filiais, 'id, codigo, nome');

		$this->load->model('portal_compras/centro_custo');
		$centroCusto = $this->centro_custo->buscar($solicitacao->id_centro_custo);

		$this->load->model('portal_compras/cotacao');
		$cotacao = $this->cotacao->buscar($solicitacao->id_cotacoes);
		$fornecedor = json_decode($cotacao->fornecedor);
		$condicaoPagamento = explode(' - ', $cotacao->condicao_pagamento)[0];
		
		$produtosDaSolicitacao = json_decode($solicitacao->produtos);
		if (empty($produtosDaSolicitacao)) {
			return ['status' => '-1', 'mensagem' => 'Não foi possível identificar os produtos da solicitação.'];
		}

		$produtos = [];
		foreach ($produtosDaSolicitacao as $produto) {
			$produtos[] = [
				'codigo' => $produto->codigo,
				'quantidade' => $produto->quantidade,
				'valor' => $produto->valorUnitario,
				'centroCusto' => $centroCusto->codigo,
				'observacao' => 'Compra realizada via Portal de Compras. Solicitação: '. $solicitacao->id,
			];
		}

		$novoPedido = [
			'codigoFornecedor' => trim($fornecedor->codigo),
			'lojaFornecedor' => trim($fornecedor->loja),
			'condicaoPagamento' => trim($condicaoPagamento),
			'produtos' => $produtos,
		];

		// Requisita o ERP para incluir o pedido
		$this->load->helper('util_portal_compras_helper');
		$resposta = incluirPedidoCompraNoERP($codigoEmpresa, $filial->codigo, $novoPedido);
		if ($resposta['OSTATUS']['CLSTATUS'] == 'false') {
			return [
				'status' => '-1', 
				'mensagem' => 'Não foi possível incluir o pedido no ERP, tente novamente mais tarde.'
			];
		}

		$dadosSolicitacao = [
			'codigo_pedido_erp' => $resposta['PEDIDO'],
		];

		if (!$this->solicitacao->editar($idSolicitacao, $dadosSolicitacao)) {
			return ['status' => '-1', 'mensagem' => 'Não foi possível atualizar o código do pedido no ERP para a solicitação.'];
		}

		return ['status' => '1', 'mensagem' => 'Pedido incluído no ERP com sucesso.'];
	}
	
}

																																																																																												