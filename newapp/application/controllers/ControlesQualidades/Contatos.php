<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Contatos extends CI_Controller
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
		$dados['titulo'] = lang('contatos');
		$this->mapa_calor->registrar_acessos_url(site_url('/ControlesQualidades/Contratos'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('controle_qualidade/contatos');
		$this->load->view('fix/footer_NS');
	}
}