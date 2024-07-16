<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Sobre extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('sobre_empresa', 'SobreEmpresa');
		$this->load->model('mapa_calor');
	}

	public function index()
    {
		$dados['titulo'] = lang('sobre');
		$dados['sobre'] = $this->SobreEmpresa->get()[0];
		$this->mapa_calor->registrar_acessos_url(site_url('/Empresas/Sobre'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('empresa/sobre/index');
		$this->load->view('fix/footer_NS');
	}
}