<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Script_permissoes extends CI_Controller {

    public function __contruct(){
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('usuario');
    }
    public function index(){
        var_dump('Teste');
    }

}