<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Filas extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->auth->is_allowed('atendimento_omnilink');

		$this->load->library('form_validation');
		$this->load->model('atendimento_omnilink/fila');
	}


	public function index(){
		$this->auth->is_allowed('vis_filas_atendimento_omnilink');
		$dados = [
			'titulo' => lang('atendimento_omnilink'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'mask', 'validate-form', 'select2']
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('atendimento_omnilink/filas/modalCadastrarEditar');
		$this->load->view('atendimento_omnilink/filas/modalVincularAgentes');
		$this->load->view('atendimento_omnilink/filas/index');
		$this->load->view('fix/footer_NS');
	}

	public function listar() {
		$filas = [];
		// Busca apenas as filas ativas
		$dados = $this->fila->listar(
			['status' => 'ativo'],
			'id, 
			nome, 
			descricao, 
			dia_inicial as diaInicial,
			dia_final as diaFinal,
			horario_inicial as horarioInicial,
			horario_final as horarioFinal,  
			datahora_cadastro as dataCadastro, 
			datahora_modificacao as dataModificacao'
		);

		if (!empty($dados)) {
			foreach ($dados as $dado) {
				$filas[] = [
					'id' => (int)$dado->id,
					'nome' => $dado->nome,
					'descricao' => $dado->descricao,
					'diaInicial' => $dado->diaInicial,
					'diaFinal' => $dado->diaFinal,
					'horarioInicial' => $dado->horarioInicial,
					'horarioFinal' => $dado->horarioFinal,
					'dataCadastro' => $dado->dataCadastro,
					'dataModificacao' => $dado->dataModificacao
				];
			}
		}

		exit(json_encode($filas));
	}

	public function buscar($id) {
		$fila = [];

		if (empty($id) || !is_numeric($id) || $id <= 0) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'ID da fila inválido.']));
		}

		// Busca apenas as filas ativas
		$fila = $this->fila->buscar( $id, 
			'id, 
			nome, 
			descricao, 
			dia_inicial as diaInicial,
			dia_final as diaFinal,
			horario_inicial as horarioInicial,
			horario_final as horarioFinal,
			datahora_cadastro as dataCadastro, 
			datahora_modificacao as dataModificacao, 
			numeros, 
			usuarios' );
		if (empty($fila)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Fila não encontrada.']));
		}
		

		exit(json_encode([
			'status' => '1',
			'fila' => [
				'id' => (int)$fila->id,
				'nome' => $fila->nome,
				'descricao' => $fila->descricao,
				'diaInicial' => $fila->diaInicial,
				'diaFinal' => $fila->diaFinal,
				'horarioInicial' => $fila->horarioInicial,
				'horarioFinal' => $fila->horarioFinal,
				'dataCadastro' => $fila->dataCadastro,
				'dataModificacao' => $fila->dataModificacao,
				'numeros' => !empty($fila->numeros) ? json_decode($fila->numeros) : [],
				'usuarios' => !empty($fila->usuarios ) ? json_decode($fila->usuarios) : []
			]
		]));
	}

	public function check_valid_day($day) {
    $allowed_days = array(
        'Segunda-feira',
        'Terça-feira',
        'Quarta-feira',
        'Quinta-feira',
        'Sexta-feira',
        'Sábado',
        'Domingo'
    );

    // Verifica se o dia final está presente nos dias permitidos
    if (in_array($day, $allowed_days)) {
        return TRUE;
    } else {
        $this->form_validation->set_message('check_valid_day', 'O campo {field} deve ser um dos dias da semana.');
        return FALSE;
    }
}

	public function cadastrar() {
		$this->auth->is_allowed('cad_filas_atendimento_omnilink');

		$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[5]|max_length[60]');
		$this->form_validation->set_rules('descricao', 'Descrição', 'max_length[240]');
		$this->form_validation->set_rules('dia_inicial', 'Dia Inicial', 'required');
		$this->form_validation->set_rules('dia_final', 'Dia Final', 'required');
		$this->form_validation->set_rules('horario_inicial', 'Horario Inicial', 'required');
		$this->form_validation->set_rules('horario_final', 'Horario Final', 'required');

		if ($this->form_validation->run() === FALSE) {
			exit(json_encode(['status' => 'error', 'message' => validation_errors()]));
		}

		$dados = $this->input->post();

		$idUsuario = $this->auth->get_login_dados('user');
		// Verifica se a fila existe
		$existeFila = $this->fila->listar(['nome' => $dados['nome'], 'status' => 'ativo']);
		if (!empty ($existeFila)) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Já existe uma fila com esse nome.']));
		}

		// Verifica se o dia inicial é válido
		if (!$this->check_valid_day($dados['dia_inicial'])) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Dia inicial inválido.']));
		}

		// Verifica se o dia final é válido
		if (!$this->check_valid_day($dados['dia_final'])) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Dia final inválido.']));
		}

		$novaFila = [
			'nome' => $this->input->post('nome'),
			'descricao' => $this->input->post('descricao'),
			'dia_inicial' => $this->input->post('dia_inicial'),
			'dia_final' => $this->input->post('dia_final'),
			'horario_inicial' => $this->input->post('horario_inicial'),
			'horario_final' => $this->input->post('horario_final'),
			'datahora_cadastro' => date('Y-m-d H:i:s'),
			'id_usuario' => $idUsuario
		];
		
		$idNovaFila = $this->fila->cadastrar($novaFila);
		if(empty($idNovaFila)){
			exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao cadastrar fila.']));
		}

		exit(json_encode([
			'status' => '1', 
			'idNovaFila' => $idNovaFila, 
			'mensagem' => 'Fila cadastrada com sucesso.'
		]));
	}

	public function editar($id) {
		$this->auth->is_allowed('edi_filas_atendimento_omnilink');

		if (empty($id) || !is_numeric($id) || $id <= 0) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'ID da fila inválido.']));
		}

		$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[5]|max_length[60]');
		$this->form_validation->set_rules('descricao', 'Descrição', 'max_length[240]');
		$this->form_validation->set_rules('dia_inicial', 'Dia Inicial', 'required');
		$this->form_validation->set_rules('dia_final', 'Dia Final', 'required');
		$this->form_validation->set_rules('horario_inicial', 'Horario Inicial', 'required');
		$this->form_validation->set_rules('horario_final', 'Horario Final', 'required');

		if ($this->form_validation->run() === FALSE) {
			exit(json_encode(['status' => 'error', 'message' => validation_errors()]));
		}

		$dados = $this->input->post();

		// Verifica se a fila existe
		$existeFila = $this->fila->listar([ 'nome' => $dados['nome'], 'status' => 'ativo', 'id <>' => $id]);
		if(!empty($existeFila)){
			exit(json_encode(['status' => '-1', 'mensagem' => 'Já existe uma fila com esse nome.']));
		}

		$dadosAtualizados = [
			'nome' => $this->input->post('nome'),
			'descricao' => $this->input->post('descricao'),
			'dia_inicial' => $this->input->post('dia_inicial'),
			'dia_final' => $this->input->post('dia_final'),
			'horario_inicial' => $this->input->post('horario_inicial'),
			'horario_final' => $this->input->post('horario_final'),
			'datahora_modificacao' => date('Y-m-d H:i:s')
		];

		$atualizou = $this->fila->editar($id, $dadosAtualizados);
		if(!$atualizou){
			exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao editar fila.']));
		}

		exit(json_encode([
			'status' => '1', 
			'idFila' => $id, 
			'mensagem' => 'Fila editada com sucesso.'
		]));
	}

	public function remover($id) {
		// Valida o $id
		if (empty($id) || !is_numeric($id) || $id <= 0) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Id inválido.']));
		}

		if (!$this->fila->alterarStatus($id, 'inativo')) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Erro ao remover a fila.']));
		}

		exit (json_encode(['status' => '1', 'mensagem' => 'Fila removida com sucesso.']));
	}

	public function listarNumeros() {
		try {
			$this->load->helper('api_televendas_helper');
			$resposta = API_Televendas_Helper::get('/twilio/list-numbers');	
			exit($resposta);
		}
		catch (Exception $e) {
			exit(json_encode([
				'status' => '-1',
				'mensagem' => 'Erro ao listar números.'
			]));
		}
  }

	public function vincularAgentes() {
		$this->auth->is_allowed('cad_vincular_agentes_atendimento_omnilink');

		$this->form_validation->set_rules('idFila', 'Id Fila', 'required|numeric|is_natural_no_zero');
		$this->form_validation->set_rules('agentes', 'Agentes', 'required|min_length[1]');
		$this->form_validation->set_rules('numeros', 'Telefones', 'required|min_length[1]');

		if ($this->form_validation->run() === FALSE) {
			exit(json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}
		
		$dados = $this->input->post();

		// Verifica se a fila existe
		$existeFila = $this->fila->listar(['id' => $dados['idFila'], 'status' => 'ativo']);
		if (empty($existeFila)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Fila não encontrada.']));
		}

		$agentes = explode(',', $dados['agentes']);
		$numeros = explode(',', $dados['numeros']);

		$novoVinculo = [
			'usuarios' => json_encode($agentes), // Os agentes são os usuários do sistema
			'numeros' => json_encode($numeros)
		];

		$idNovoVinculo = $this->fila->editar($dados['idFila'], $novoVinculo);
		if (empty($idNovoVinculo)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao tentar vincular.']));
		}

		exit(json_encode([
			'status' => '1',
			'mensagem' => 'Vinculo realizado com sucesso.'
		]));
	}

	public function clientes() {
		$dados['titulo'] = "Fila de clientes";
    $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

    $_SESSION['menu_atendimento'] = 'FilaDeClientes';
    $this->load->view('new_views/fix/header', $dados);
		$this->load->view('atendimento_omnilink/fila_de_clientes');
		$this->load->view('fix/footer_NS');
	}

}