<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Compras extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->auth->is_logged('admin');
        $this->load->model('auth');

    }

    public function index()
    {

        $dados['titulo'] = 'Compras';


       // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/home');
        $this->load->view('fix/footer-new');
    }

    public function fornecedores()
    {

        $dados['titulo'] = 'Fornecedores';


        // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/fornecedores');
        $this->load->view('fix/footer-new');
    }
    public function cad_produtos()
    {

        $dados['titulo'] = 'Cadastrar Produtos';


        // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/cad_produtos');
        $this->load->view('fix/footer-new');
    }
    public function produtos()
    {

        $dados['titulo'] = 'Cadastrar Produtos';


        // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/produtos');
        $this->load->view('fix/footer-new');
    }
    public function cotacao()
    {

        $dados['titulo'] = 'Cotações Recentes';


        // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/cotacao');
        $this->load->view('fix/footer-new');
    }
    public function cad_cotacao()
    {

        $dados['titulo'] = 'Nova Cotação';


        // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/cad_cotacao');
        $this->load->view('fix/footer-new');
    }
    public function cad_fornecedor()
    {

        $dados['titulo'] = 'Cadastrar Fornecedor';


        // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/cad_fornecedor');
        $this->load->view('fix/footer-new');
    }
    public function cotacao_aprovada()
    {

        $dados['titulo'] = 'Cotações Aprovadas';


        // $consulta['x'] = array('query' => $this->Compras->get_compras());

        $this->load->view('fix/header-left', $dados);
        $this->load->view('compras/cotacao_aprovada');
        $this->load->view('fix/footer-new');
    }
}