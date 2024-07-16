<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Login extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function getLogin($email) {
		$system = $this->load->database('default', true);
		$log = $system->select('usuario_email, data, horario_login')->where('usuario_email', $email)
				->where('data', date('Y-m-d'))
				->order_by('horario_login', 'DESC')
				->limit(1,1)
				->get('showtecsystem.horario_acesso')->result()[0];
	    if (!$log) {
	        $log = $system->select('usuario_email, data, horario_login')->where('usuario_email', $email)
				->where('data', date('Y-m-d', strtotime('-1 day')))
				->order_by('horario_login', 'DESC')
				->limit(1,1)
				->get('showtecsystem.horario_acesso')->result()[0];
	    }
		$name = $system->select('nome')->where('login', $log->usuario_email)->get('showtecsystem.usuario')->result()[0]->nome;
		return array('name' => $name, 'email' => $log->usuario_email, 'date' => date('d/m/Y', strtotime($log->data)), 'hour' => $log->horario_login );
	}	
}
