<?php if (!defined('BASEPATH'))
	exit(lang("nenhum_acesso_direto_script_permitido"));

class ContatosCorporativos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('usuario');
		$this->load->model('auth');
		$this->load->model('mapa_calor');
	}

	public function index()
	{
		$dados['titulo'] = lang('contatos_corporativos');
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

		$this->mapa_calor->registrar_acessos_url(site_url('/Empresas/ContatosCorporativos'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('empresa/contato_corporativo/ContatoCorporativoView');
		$this->load->view('fix/footer_NS');
	}

	public function listContatosController()
	{
		if (!$this->input->is_ajax_request())
			exit(lang("nenhum_acesso_direto_script_permitido"));

		$filtros['status'] = 1;

		$filtros['id_departamentos'] = 1;

		$funcionarios = $this->usuario->listar($filtros);

		$data = [];
		$x = 0;

		foreach ($funcionarios as $funcionario) {
			$data[$x] =
				[
					"nome" => ucwords(strtolower($funcionario->nome)),
					"cargo" => ucwords(strtolower($funcionario->ocupacao)),
					"empresa" => ucwords(strtolower($funcionario->empresa)),
					"email" => $funcionario->login,
				];
			$x++;
		}

		echo json_encode($data);

	}
}
