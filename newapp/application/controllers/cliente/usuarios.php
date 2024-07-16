<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct(){
		
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('sender');
		$this->load->model('cliente');
		$this->load->model('usuario_gestor');
		
	}
	
	public function index(){
		
	}
	
	public function ativar($token){
		
		$usuario = $this->usuario_gestor->get(array('token_usuario' => $token));
		if (count($usuario) > 0){
			
			$this->usuario_gestor->atualizar_conta($usuario->code, array('token' => '', 'status_usuario' => 'ativo'));
			
		}
		
		redirect('https://gestor.showtecnologia.com/gestor/index.php/login');
	}
	
}