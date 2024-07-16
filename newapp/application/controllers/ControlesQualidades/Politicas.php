<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Politicas extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('ashownett');
		$this->load->model('mapa_calor');
	}

	public function index()
    {
		$dados['titulo'] = lang('politicas');
		$this->mapa_calor->registrar_acessos_url(site_url('/ControlesQualidades/Politicas'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('controle_qualidade/politicas');
		$this->load->view('fix/footer_NS');
	}
}