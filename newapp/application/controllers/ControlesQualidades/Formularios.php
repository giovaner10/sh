<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Formularios extends CI_Controller
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
		$dados['titulo'] = lang('formularios');
		$this->mapa_calor->registrar_acessos_url(site_url('/ControlesQualidades/Formularios'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('politica_formulario/formularios');
		$this->load->view('fix/footer_NS');
	}
}