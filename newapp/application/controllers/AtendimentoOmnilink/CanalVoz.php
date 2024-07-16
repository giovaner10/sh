<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class CanalVoz extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->auth->is_logged('admin');
		$this->auth->is_logged_api_shownet();

		$this->load->model('atendimento_omnilink/contatos');
	}

	public function obterToken() {
		$this->load->helper('api_televendas');
		$idUsuario = $this->auth->get_login_dados('user');

		// Busca o token da Twilio
		$resposta = API_Televendas_Helper::get('/twilio/get-token/' . (string)$idUsuario);
		exit($resposta);
	}

	public function obterTokenAtendimento() {
		$this->load->helper('api_televendas');
		$idUsuario = $this->auth->get_login_dados('user');

		// Busca o token da Twilio
		$resposta = API_Televendas_Helper::get('/twilio/get-token-atendimento/' . (string)$idUsuario.'-atendimento');
		exit($resposta);
	}

	public function salvarHistoricoChamada() {
		$this->load->helper('api_televendas');
		$dados = $this->input->post();
		$idUsuario = $this->auth->get_login_dados('user');
		$usuario = $this->auth->get_login_dados('email');

		$dadosChamda = array(
			'idUsuario' => (int)$idUsuario,
			'sid' => $dados['sid'],
			'status' => $dados['status'],
			'efetuadaPor' => $usuario,
			'recebidaPor' => $dados['recebidaPor'],
			'dataHoraInicio' => $dados['dataHoraInicio'],
			'dataHoraFim' => $dados['dataHoraFim'],
			'protocolo' => $dados['protocolo']
		);

		// Busca o token da Twilio
		$resposta = API_Televendas_Helper::post('/atendimento-omnilink/call/record-history', $dadosChamda);
		exit($resposta);
	}

	public function listarContatos() {
		$this->auth->is_allowed('vis_contatos_atendimento_omnilink');
		
		try {
			$dados = [];
			$contatos = $this->contatos->listar(
				['status' => 'ativo'],
				['id', 'nome', 'email', 'empresa', 'telefone']
			);

			if (!empty($contatos)) {
				foreach ($contatos as $contato) {

						$dados[] = [
								'id' => (int) $contato -> id,
								'nome' => $contato -> nome,
								'email' => $contato -> email,
								'empresa' => $contato -> empresa,
								'telefone' => $contato -> telefone,
						];
				}    
		}
			exit(json_encode($contatos));
		} catch (Exception $e) {
			echo json_encode(['error' => $e->getMessage()]);
		}
	}

	public function cadastrarContato() {
		$this->auth->is_allowed('cad_contatos_atendimento_omnilink');

		$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|max_length[60]');
		$this->form_validation->set_rules('email', 'Email', 'max_length[240]');
		$this->form_validation->set_rules('empresa', 'Empresa', 'required|min_length[3]|max_length[60]');
		$this->form_validation->set_rules('telefone', 'Telefone', 'required|min_length[5]|max_length[15]');

		if ($this->form_validation->run() === FALSE) {
			exit(json_encode(['status' => 'error', 'message' => validation_errors()]));
		}

		$dados = $this->input->post();

		$idUsuario = $this->auth->get_login_dados('user');
		// Verifica se o contato já existe
		$existeContato = $this->contatos->listar(['telefone' => $dados['telefone'], 'status' => 'ativo']);
		if (!empty ($existeContato)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Já existe um Contato com esse número.']));
		}

		$novoContato = [
			'nome' => $this->input->post('nome'),
			'email' => $this->input->post('email'),
			'empresa' => $this->input->post('empresa'),
			'telefone' => $this->input->post('telefone'),
			'datahora_cadastro' => date('Y-m-d H:i:s'),
			'id_usuario' => $idUsuario
		];

		$idNovoContato = $this->contatos->cadastrar($novoContato);
		if(empty($idNovoContato)){
			exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao cadastrar contato.']));
		}

		exit(json_encode([
			'status' => '1', 
			'idNovoContato' => $idNovoContato, 
			'mensagem' => 'Contato cadastrado com sucesso.'
		]));
	}

	public function editarContato($id) {
		$this->auth->is_allowed('edi_contatos_atendimento_omnilink');
		
		if (empty($id) || !is_numeric($id) || $id <= 0) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'ID do Contato inválido.']));
		}

		$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|max_length[60]');
		$this->form_validation->set_rules('email', 'Email', 'max_length[240]');
		$this->form_validation->set_rules('empresa', 'Empresa', 'required|min_length[3]|max_length[60]');
		$this->form_validation->set_rules('telefone', 'Telefone', 'required|min_length[5]|max_length[15]');

		if ($this->form_validation->run() === FALSE) {
			exit(json_encode(['status' => 'error', 'message' => validation_errors()]));
		}

		$dados = $this->input->post();

		$contato = $this->contatos->listar(['id <>' => $id, 'status' => 'ativo']);
		if (empty($contato)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Contato não encontrado.']));
		}

		$dadosAtualizados = [
			'nome' => $this->input->post('nome'),
			'email' => $this->input->post('email'),
			'empresa' => $this->input->post('empresa'),
			'telefone' => $this->input->post('telefone'),
			'datahora_modificacao' => date('Y-m-d H:i:s')
		];

		$atualizou = $this->contatos->editar($id, $dadosAtualizados);
		if(!$atualizou){
			exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao editar fila.']));
		}

		exit(json_encode([
			'status' => '1', 
			'idContato' => $id, 
			'mensagem' => 'Contato atualizado com sucesso.'
		]));
	}

	public function excluirContato($id) {
		// Valida o $id
		if (empty($id) || !is_numeric($id) || $id <= 0) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Id inválido.']));
		}

		// Verifica se o contato existe
		$contato = $this->contatos->listar(['id <>' => $id, 'status' => 'ativo']);
		if (empty($contato)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Contato não encontrado.']));
		}

		if (!$this->contatos->alterarStatus($id, 'inativo')) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao eo excluir contato.']));
		}

		exit (json_encode(['status' => '1', 'mensagem' => 'Contato excluido com sucesso.']));
	}
}