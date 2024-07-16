<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class FormaPagamento extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('util_helper');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('cliente');
        $this->load->model('mapa_calor');
    }

    public function index($c_order = false, $order = false, $pagina = false) {
        $dados['titulo'] = 'Faturas';
        $this->mapa_calor->registrar_acessos_url(site_url('/formapagamento'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('forma_pagamento/index');
        $this->load->view('fix/footer_NS');
    }

    public function BuscarFormas(){
        $formas = buscarFormasPagamento();
        echo json_encode($formas);
    }
    
    public function BuscarEmpresas(){
        
        $nome = $this->input->get('q') ? $this->input->get('q') : '';

        $nome = str_replace(' ', '%20', $nome);

        $empresas = buscarEmpresas($nome);
        echo $empresas;
    }
    
    public function BuscarClientes(){
        $search = $this->input->get('q');
        $tipoBusca = $this->input->get('tipoBusca');
        $clientes = $this->cliente->getClientes($search, $tipoBusca);

        if(count($clientes) > 0){
            foreach ($clientes as $key => $cliente) {

                $resposta['results'][] = array(
                    'id' => $cliente['id'],
                    'text' => $cliente['nome']." (" .$cliente['razao_social'] .")",
                    'cep' => $cliente['cep'],
                    'endereco' => $cliente['endereco'],
                    'uf' => $cliente['uf'],
                    'bairro' => $cliente['bairro'],
                    'cidade' => $cliente['cidade']
                );
            }
            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }
    }

    public function adicionarForma(){
        
        $empresa = $this->input->post('empresa');
        $cliente = $this->input->post('cliente');
        $prazo = $this->input->post('prazo');

        $forma = adicionarFormaPagamento($empresa, $cliente, $prazo);
        
        echo json_encode($forma);
    }

    public function editarForma(){
        
        $id = $this->input->post('id');
        $empresa = $this->input->post('empresa');
        $cliente = $this->input->post('cliente');
        $prazo = $this->input->post('prazo');
        $status = $this->input->post('status');

        $forma = editarFormaPagamento($id, $empresa, $cliente, $prazo, $status);
        echo json_encode($forma);
    }

    public function alterarStatus(){
        
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $forma = editarStatusFormaPagamento($id, $status);
        echo $forma;
    }
}
