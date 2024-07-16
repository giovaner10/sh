<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fornecedores extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('cliente');
        $this->load->model('usuario_gestor');
        $this->load->helper('text');
    }

    public function index($pagina = false){

        $this->auth->is_allowed('clientes_visualiza');

        $this->session->unset_userdata('filtro_cliente');

        grava_url(current_url());
        $paginacao = $pagina != false  ? $pagina : 0;

        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');

        $config['base_url'] = site_url().'/clientes/index/';
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->cliente->total_lista();

        $this->pagination->initialize($config);

        $dados['clientes'] = $this->cliente->listar(array(), $paginacao, 15);
        $dados['all_clientes'] = $this->cliente->listar(array(), 0, 99999);

        $j_clientes = array();
        if (count($dados['all_clientes']) > 0){
            foreach ($dados['all_clientes'] as $cli){
                $j_clientes[] = $cli->nome;
            }
        }
        $dados['j_clientes'] = json_encode($j_clientes);

        $dados['titulo'] = 'Clientes';

        $this->load->view('fix/header', $dados);
        $this->load->view('fornecedores/lista_fornecedores');
        $this->load->view('fix/footer');

    }

    public function view($id_cliente = false){

        $this->auth->is_allowed('clientes_visualiza');

        if ($id_cliente){
            $where = array('id' => $id_cliente);
        } elseif ($this->input->post('cliente')) {
            $cliente = $this->cliente->get(array('nome' => $this->input->post('cliente')));
            $where = array('id' => $cliente->id);
            $id_cliente = $cliente->id;
        }

        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');

        $dados['usuarios'] = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
        $dados['cliente'] = $this->cliente->get($where);

        $all_users = $this->usuario_gestor->listar(array());
        if(count($all_users) > 0){
            foreach ($all_users as $user){
                $dados['j_user'][] = $user->usuario;
            }
        }
        $all_clients = $this->cliente->listar(array());
        if(count($all_clients) > 0){
            foreach ($all_clients as $client){
                $dados['j_clientes'][] = $client->nome;
            }
        }

        $dados['d_filiais']['filiais'] = $this->cliente->listar(array('id_nivel' => $id_cliente));

        $dados['id_cliente'] = $id_cliente;
        $dados['titulo'] = 'Clientes';
        $dados['bloqueio'] = $this->auth->is_allowed_block('clientes_bloqueio');

        $this->load->view('fix/header', $dados);
        $this->load->view('clientes/view_cliente');
        $this->load->view('fix/footer');
    }



}
