<?php if (!defined('BASEPATH'))exit('No direct script access allowed');


class Cad_acessorios extends CI_Controller
{

    public function _construct()
    {

        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->library('form_validation');
        $this->load->model('cadastro_acessorios', 'acessorioM');
        $this->load->database();

    }

    public function index()
    {

        $this->load->model('cadastro_acessorios', 'acessorioM');

        $data['titulo'] = 'Show Tecnologia';
        $data['acessorios'] = $this->acessorioM->getAcessorio();
        $this->load->view('fix/header');
        $this->load->view('cadastroAcessorios/cadastro_acessorios_view', $data);
        $this->load->view('fix/footer');
    }

    public function inserir_acessorios()
    {

        $this->load->model('cadastro_acessorios', 'acessorioM');

        $this->form_validation->set_rules('marca', 'marca');
        $this->form_validation->set_rules('modelo', 'modelo');
        $this->form_validation->set_rules('descricao', 'descricao');
        $this->form_validation->set_rules('id_categoria', 'id_categoria');
        $this->form_validation->set_rules('id_fornecedor', 'id_fornecedor');
        $this->form_validation->set_rules('id_NF_entrada', 'id_NF_entrada');
        $this->form_validation->set_rules('estoque_minimo', 'estoque_minimo');
        $this->form_validation->set_rules('estoque_maximo', 'estoque_maximo');
        $this->form_validation->set_rules('estoque_atual', 'estoque_atual');

        if ($this->form_validation->run()) {

            $dados['marca'] = $this->input->post('marca');
            $dados['modelo'] = $this->input->post('modelo');
            $dados['descricao'] = $this->input->post('descricao');
            $dados['id_categoria'] = $this->input->post('id_categoria');
            $dados['id_fornecedor'] = $this->input->post('id_fornecedor');
            $dados['id_NF_entrada'] = $this->input->post('id_NF_entrada');
            $dados['estoque_minimo'] = $this->input->post('estoque_minimo');
            $dados['estoque_maximo'] = $this->input->post('estoque_maximo');
            $dados['estoque_atual'] = $this->input->post('estoque_atual');


            if($this->input->post('id_acessorio') != NULL) {

                $this->acessorioM->editar_cadastro($dados, $this->input->post('id_acessorio'));
                $this->session->set_flashdata('editado', '<div class="alert alert-success" role="alert">Acessório editado com sucesso! </div>');
                redirect('cad_acessorios/index');
            }
            else{

                $this->acessorioM->add_acessorio($dados);
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Acessório cadastrado com sucesso! </div>');
                redirect('cad_acessorios/index');
            }
        } else {

            $this->session->set_flashdata('dados', '<div class="alert alert-success" role="alert">Preencha todos os campos corretamente ! </div>');
            redirect('cad_acessorios/index');
        }

    }

    public function editar_acessorio($id_acessorio)
    {
        $this->load->model('cadastro_acessorios', 'acessorioM');

        if ($id_acessorio == NULL) {

            redirect('/');

        }

        $query = $this->acessorioM->get_by_id($id_acessorio);

        if ($query == NULL) {

            redirect('/');
        }

        $dados['acessorios'] = $query;

        $this->load->view('fix/header');
        $this->load->view('cadastroAcessorios/editar_acview', $dados);
        $this->load->view('fix/footer');

    }

}