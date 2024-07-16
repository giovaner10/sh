<?php

date_default_timezone_set('America/Recife');

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Mapa_Calor extends CI_Model {
    function __construct() {
		parent::__construct();
        $this->load->helper("util_helper");
	}

    public function registrar_acessos_url($url_acessada) {
        $id_user_logado = $this->auth->get_login_dados('user');
		$id_user_logado = (int) $id_user_logado;

        register_user_access($id_user_logado, $this->auth->get_login_dados('email'), $url_acessada, date('Y-m-d H:i:s'));
    }
}