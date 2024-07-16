<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Configuracoes extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('portal_compras/configuracao');
		$this->load->library('form_validation');

		$this->auth->is_logged('admin');
	}

	public function index() {
		$this->auth->is_allowed('edi_configuracoes_portal_compras');

		$idUsuario = $this->auth->get_login_dados('user');
		$usuario = $this->usuario->getUser($idUsuario, 'id, funcao_portal as funcaoPortal')[0];
		$funcaoUsuario = !empty($usuario) ? $usuario->funcaoPortal : null;

		$dados = [
			'titulo' => lang('portal_compras'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'mask', 'validate-form', 'select2'],
			'funcaoUsuario' => $funcaoUsuario,
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('portal_compras/configuracoes/index');
		$this->load->view('fix/footer_NS');
	}

	public function buscar() {
		$configuracao = [];
		// Busca a configuração
		$dadosConfig = $this->configuracao->buscar();

		if (empty($dadosConfig)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Configuração não encontrada.']));
		}

		$configAlcadaAprovacao = json_decode($dadosConfig->alcada_de_aprovacao);

		$alcadaDeAprovacao = [
			'diretor_min' => number_format($configAlcadaAprovacao->diretor_min, 2, ',', '.'),
			'diretor_max' => number_format($configAlcadaAprovacao->diretor_max, 2, ',', '.'),
			'cfo_min' => number_format($configAlcadaAprovacao->cfo_min, 2, ',', '.'),
			'cfo_max' => number_format($configAlcadaAprovacao->cfo_max, 2, ',', '.'),
			'ceo_min' => number_format($configAlcadaAprovacao->ceo_min, 2, ',', '.')
		];

		$centrosDeCusto = !empty($dadosConfig->centros_de_custo) ? json_decode($dadosConfig->centros_de_custo) : [];
		$aprovadores = !empty($dadosConfig->aprovadores) ? json_decode($dadosConfig->aprovadores) : [];

		$configuracao = [
			'alcadaDeAprovacao' => $alcadaDeAprovacao,
			'centrosDeCusto' => $centrosDeCusto,
			'aprovadores' => $aprovadores,
		];


		exit(json_encode(['status' => '1', 'configuracao' => $configuracao]));
	}

	public function editar() {
		$dados = $this->input->post();

		// Validação dos campos
		$this->form_validation->set_rules('diretor_min', 'Valor Mínimo Diretor', 'required|numeric');
		$this->form_validation->set_rules('diretor_max', 'Valor Máximo Diretor', 'required|numeric');
		$this->form_validation->set_rules('cfo_min', 'Valor Mínimo CFO', 'required|numeric');
		$this->form_validation->set_rules('cfo_max', 'Valor Máximo CFO', 'required|numeric');
		$this->form_validation->set_rules('ceo_min', 'Valor Mínimo CEO', 'required|numeric');
		$this->form_validation->set_rules('centros_custo', 'Centros de Custo', 'required');
		$this->form_validation->set_rules('ceo_aprovador', 'CEO' , 'required|numeric');
		$this->form_validation->set_rules('cfo_aprovador', 'CFO', 'required|numeric');

		if ($this->form_validation->run() == FALSE) {
			exit(json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		if ($dados['diretor_max'] <= $dados['diretor_min']) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Valor máximo do Diretor inferior ou igual ao seu valor mínimo.']));
		}

		if ($dados['cfo_max'] <= $dados['cfo_min']) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Valor máximo do CFO inferior ou igual ao seu valor mínimo.']));
		}

		if ($dados['cfo_min'] <= $dados['diretor_max']) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Valor mínimo do CFO inferior ou igual ao valor máximo do Diretor.']));
		}

		if ($dados['ceo_min'] <= $dados['cfo_max']) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Valor mínimo do CEO inferior ou igual ao valor máximo do CFO.']));
		}

		$dadosCentrosDeCusto = json_decode($dados['centros_custo']);

		if (empty($dadosCentrosDeCusto)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Adicione o responsável para ao menos um centro de custo.']));
		}

		$alcadaDeAprovacao = [
			'diretor_min' => $dados['diretor_min'],
			'diretor_max' => $dados['diretor_max'],
			'cfo_min' => $dados['cfo_min'],
			'cfo_max' => $dados['cfo_max'],
			'ceo_min' => $dados['ceo_min']
		];

		$configuracao = [
			'alcada_de_aprovacao' => json_encode($alcadaDeAprovacao),
			'centros_de_custo' => $dados['centros_custo'],
			'aprovadores' => json_encode([
				'cfo' => $dados['cfo_aprovador'],
				'ceo' => $dados['ceo_aprovador'],
			])
		];

		if (!$this->configuracao->editar(1, $configuracao)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Erro ao salvar a configuração.']));
		}

		exit(json_encode(['status' => '1', 'mensagem' => 'Configuração salva com sucesso.']));
	}
}