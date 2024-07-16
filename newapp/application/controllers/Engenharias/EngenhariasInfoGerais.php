<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class EngenhariasInfoGerais extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('mapa_calor');
	}

	public function index()
    {
		$dados['titulo'] = lang('engenharia');
		$this->mapa_calor->registrar_acessos_url(site_url('/Engenharias/EngenhariasInfoGerais'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('engenharia/informacao_geral/index');
		$this->load->view('fix/footer_NS');
	}
}