<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class OperacoesInfoGerais extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('mapa_calor');
	}

	public function index()
    {
		$dados['titulo'] = lang('operacoes');
		$this->mapa_calor->registrar_acessos_url(site_url('/Operacoes/OperacoesInfoGerais'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('operacao/informacao_geral/index');
		$this->load->view('fix/footer_NS');
	}
}