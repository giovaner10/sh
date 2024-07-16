<?php

if (! defined('BASEPATH')) exit('No direct script access allowed');

class contatos_colaboradores extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('instalador_helper');
        $this->load->model('arquivo');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
    }

    public function index()
    {
        $this->auth->is_logged('admin');
        $this->load->model('fatura');
        $dados['titulo'] = 'SHOWNET';
        $dados['colaboradores'] = $this->usuario->listar();

        $this->load->view('fix/header4', $dados);
        $this->load->view('contatosColaboradores/colaboradores');
        $this->load->view('fix/footer4');
    }
    
    public function getColaboradores(){
        echo json_encode($this->usuario->getColaboradores());
    }
}