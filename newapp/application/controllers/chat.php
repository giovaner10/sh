<?php if (!defined('BASEPATH'))exit('No direct script access allowed');


class Chat extends CI_Controller
{

    public function _construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->database();
        $this->load->model('auth');      

    }

    public function view() {

        $dados['titulo'] = "ShowTecnologia";
        $dados['emailUser'] = $this->auth->get_login('admin', 'email');
        $this->load->view('fix/header', $dados);
        $this->load->view('chat/index');
        $this->load->view('fix/footer');

    }

    public function usuario() {
        echo json_encode($this->auth->get_login('admin', 'email'));
    }

    public function save() {
        $this->load->model('mchat');
        $dados['user'] = $this->input->post('user');
        $dados['mensagem'] = $this->input->post('msg');
        $dados['dateTime'] = date('Y-m-d H:i:s');   
        if ($dados) {
            $this->mchat->save($dados);
        }

        
    }




}