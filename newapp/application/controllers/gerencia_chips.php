<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gerencia_chips extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');


    }

    public function index()
    {

        $dados['titulo'] = 'Gerenciamento de Chips';


        $this->load->view('fix/header3', $dados);
        $this->load->view('gerencia_chips/index');
        $this->load->view('fix/footer3');
    }

}