<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Comentarios extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');

		$this->load->model('portal_compras/comentario');
		$this->load->model('portal_compras/log_solicitacao', 'log');

		$this->upload_path = 'uploads/portal_compras/solicitacoes/anexos_comentarios/';
		$this->file_name = '';
	}

	public function listarParaSolicitacao($idSolicitacao) {
		$dados = [];

		$comentarios = $this->comentario->listar( 
			'comentario.*, usuario.nome as nome_usuario',
			['comentario.id_solicitacoes' => $idSolicitacao], 
			true
		);

		if (!empty($comentarios)) {
			foreach ($comentarios as $comentario) {
				$dados[] = [
					'id' => (int)$comentario->id,
					'origem' => $comentario->origem,
					'mensagem' => $comentario->mensagem,
					'pathAnexo' => $comentario->path_anexo,
					'datahoraCadastro' => $comentario->datahora_cadastro,
					'nomeUsuario' => $comentario->nome_usuario,
				];
			}	
		}

		exit(json_encode(['status' => '1', 'comentarios' => $dados]));
	}

	public function valida_anexo() {
		if (empty ($_FILES['anexo']['name'])) {
			return TRUE;
		}

		if (!is_dir($this->upload_path)) {
			mkdir($this->upload_path, 0777, TRUE);
		}

		// sanitariza o nome do arquivo
		$file_name_anexo_solicitacao = removerAcentos($_FILES['anexo']['name']); // remove acentos
		$file_name_anexo_solicitacao = str_replace(' ', '_', $file_name_anexo_solicitacao); // substitui espaços por underline
		$this->file_name = preg_replace(['/[^A-Za-z0-9._\?]/'], '', $file_name_anexo_solicitacao); // remove caracteres especiais

		$config['upload_path'] = $this->upload_path;
		$config['allowed_types'] = 'pdf|jpg|jpeg|png';
		$config['max_size'] = 5120; // 5MB
		$config['file_name'] = $this->file_name;

		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('anexo')) {
			$this->form_validation->set_message('valida_anexo', 'Anexo: ' . strip_tags($this->upload->display_errors()));
			return FALSE;
		}
		
		return TRUE;
	}

	public function cadastrar() {
		$dados = $this->input->post();
		if (empty($dados)) exit (json_encode(['status' => '-1', 'mensagem' => 'Nenhum dado foi enviado, tenta novamente mais tarde.']));

		if(empty($dados['origem'])) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'É necessário que o usuário possua uma função no portal para enviar um comentário.']));
		}

		// Validação dos campos
		$this->form_validation->set_rules('mensagem', 'Mensagem', 'trim|required|min_length[2]|max_length[240]');
		$this->form_validation->set_rules('origem', 'Função', 'trim|required|in_list[solicitante,aprovador,area_compras,area_financeira,area_fiscal]');
		$this->form_validation->set_rules('id_solicitacao', 'Id da Solicitação', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('anexo', 'Anexo', 'callback_valida_anexo');
		
		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		$idUsuario = $this->auth->get_login_dados('user');
		$nomeUsuario = $this->auth->get_login_dados('nome');
		$idSolicitacao = $dados['id_solicitacao'];

		$pathAnexo = $this->file_name ? $this->upload_path . $this->file_name : '';
		
		// Cadastra a solicitacao
		$novoComentario = [
			'origem' => $dados['origem'],
			'mensagem' => $dados['mensagem'],
			'path_anexo' => $pathAnexo ? $pathAnexo : NULL,
			'id_usuario' => $idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		];

		$idComentario = $this->comentario->cadastrar($novoComentario);
		if (empty($idComentario)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao enviar o comentário, tente novamente mais tarde.']));
		}

		// Salva o log da ação
		$this->log->cadastrar([
			'acao' => 'comentar',
			'id_usuario' => $idUsuario,
			'id_solicitacoes' => $dados['id_solicitacao'],
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);
		
		// Envia o email para os usuarios que comentaram na solicitacao
		$this->load->model('portal_compras/email');
		$this->email->send($idSolicitacao, 'comentarios');

		exit (json_encode([
			'status' => '1',
			'mensagem' => 'Comentário enviado com sucesso.',
			'comentario' => [
				'id' => $idComentario,
				'origem' => $novoComentario['origem'],
				'mensagem' => $novoComentario['mensagem'],
				'pathAnexo' => $novoComentario['path_anexo'],
				'datahoraCadastro' => $novoComentario['datahora_cadastro'],
				'nomeUsuario' => $nomeUsuario,
			]
		]));
	}

}

