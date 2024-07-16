<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class RegistrosDeChamadas extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('atendimento_omnilink/historico_chamadas', 'chamada');
		
		$this->auth->is_logged('admin');


	}

	public function listar() {
		$this->auth->is_allowed('vis_registro_chamadas_atendimento_omnilink');
		$dados = [];

		// Busca todos os registros de chamadas
		$chamadas = $this->chamada->listar();

		if (!empty($chamadas)) {
				foreach ($chamadas as $chamada) {

						$dados[] = [
								'id' => (int) $chamada -> id,
								'sid' => $chamada -> sid,
								'protocolo' => $chamada -> protocolo,
								'efetuadaPor' => $chamada -> efetuada_por,
								'recebidaPor' => $chamada -> recebida_por,
								'dataHoraInicio' => $chamada -> datahora_inicio,
								'dataHoraFim' => $chamada -> datahora_fim,
								'duracao' => $chamada -> duracao,
								'download' => $chamada -> caminho_arquivo,
						];
				}    
		}

		exit(json_encode($dados));
	}

	public function index() {
		$this->auth->is_allowed('vis_registro_chamadas_atendimento_omnilink');
		$dados = [
			'titulo' => lang('registro_chamadas'),
			'load' => ['ag-grid', 'ag-grid-helpers', 'css-new-style', 'validate-form', 'select2'],
		];

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('atendimento_omnilink/registro_chamadas/index');
		$this->load->view('fix/footer_NS');
	}

	public function download($url) {
		$this->auth->is_allowed('vis_registro_chamadas_atendimento_omnilink');
		$this->load->helper('api_televendas');

		$endpoint = sprintf('/twilio/get-recording-files?hash=%s', $url);
		$resposta = API_Televendas_Helper::get($endpoint);
		exit($resposta);
	}

}