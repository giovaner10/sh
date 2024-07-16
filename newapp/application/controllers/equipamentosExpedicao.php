<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class equipamentosExpedicao extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('auth');
        $this->auth->is_logged('admin');
		$this->load->helper('util_helper');
		$this->load->model('mapa_calor');
	}

	public function index()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('equipamentosExpedicao');
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$this->mapa_calor->registrar_acessos_url(site_url('/equipamentosExpedicao'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('logistica/equipamentosExpedicao');
		$this->load->view('fix/footer_NS');   
	}

	public function listarSeriais()
	{
		$retorno =  getlistarSeriaisAll();

        echo json_encode($retorno);

	}

}