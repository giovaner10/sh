<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Erros extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->auth->is_logged('admin');
	}
	
	public function erro_403(){
		$dados['titulo'] = 'Show Tecnologia';

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('erros/403');
		$this->load->view('fix/footer_NS');   
		
		
	}
	
}