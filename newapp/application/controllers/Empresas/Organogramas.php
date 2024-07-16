<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Organogramas extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('usuario');
	}

	public function index()
    {
        $dados['titulo'] = lang('organograma');

		$this->load->view('fix/header_NS', $dados);
		$this->load->view('empresa/organograma/index');
		$this->load->view('fix/footer_NS');
	}

}