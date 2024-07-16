<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Gateways extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
	}

	public function gtw()
    {
        redirect('https://gestor.showtecnologia.com/gtw/gateway/');
    }
}