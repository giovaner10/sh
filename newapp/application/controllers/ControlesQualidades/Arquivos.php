<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Arquivos extends CI_Controller
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
		$dados['titulo'] = lang('arquivos_iso');
        $dados['lista_dados'] = $this->ashownett->getArquivosIso();
		$this->mapa_calor->registrar_acessos_url(site_url('/ControlesQualidades/Contratos'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('ashownet/iso');
		$this->load->view('fix/footer_NS');
	}
}