<?php if (!defined('BASEPATH'))exit('No direct script access allowed');


class Categorias extends CI_Controller
{

    public function _construct()
    {

        parent::__construct();

        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->library('form_validation');
        $this->load->model('categoriasM', 'catM');
        $this->load->model('subcategoriasM', 'subcatM');
        $this->load->database();
    }

    public function index()
    {

        $this->load->model('categoriasM', 'catM');
        $this->load->model('subcategoriasM', 'subcatM');

        $data['titulo'] = 'Show Tecnologia';

        $data['categorias'] = $this->catM->getCat();

        $this->load->view('fix/header');
        $this->load->view('categorias/categoria_acview', $data);
        $this->load->view('fix/footer');
    }

    public function cad_sub($id_categoria){


        $this->load->model('categoriasM', 'catM');
        $this->load->model('subcategoriasM', 'subcatM');

        if ($id_categoria == NULL) {

            redirect('/');

        }

        $query = $this->catM->get_by_id($id_categoria);

        if ($query == NULL) {

            redirect('/');
        }

        $dados['categorias'] = $query;

        $this->load->view('fix/header');
        $this->load->view('categorias/cat_view',$dados);
        $this->load->view('fix/footer');

    }

    public function inserir_categorias()
    {
        $this->load->model('categoriasM', 'catM');
        $this->load->model('subcategoriasM', 'subcatM');

        $this->form_validation->set_rules('nome', 'nome');
        $this->form_validation->set_rules('nome_sub', 'nome_sub');

        if ($this->form_validation->run()) {

            $dados['nome'] = $this->input->post('nome');
            $dados['nome_sub'] = $this->input->post('nome_sub');

            if($this->input->post('id') != NULL) {

                $this->catM->editar_cadastro($dados, $this->input->post('id'));
                $this->session->set_flashdata('editado', '<div class="alert alert-success" role="alert">Categoria e subcategorias editadas com sucesso! </div>');
                redirect('categorias/index');
            }

            elseif ($this->input->post('nome') == NULL){

                $this->catM->addCategoria($dados);
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Categoria cadastrada com sucesso! </div>');
                redirect('categorias/index');
            }
            elseif ($this->input->post('nome_sub') == NULL){

                $this->subcatM->addSubcategoria($dados);
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Subcategorias cadastrada com sucesso! </div>');
                redirect('categorias/index');
            }

        } else {

            $this->session->set_flashdata('dados', '<div class="alert alert-success" role="alert">Preencha todos os campos corretamente ! </div>');
            redirect('categorias/index');
        }

    }

    public function editar_categoria($id_categoria)
    {
        $this->load->model('categoriasM', 'catM');
        $this->load->model('subcategoriasM', 'subcatM');

        if ($id_categoria == NULL) {

            redirect('/');

        }

        $query = $this->catM->get_by_id($id_categoria);

        if ($query == NULL) {

            redirect('/');
        }

        $dados['categorias'] = $query;

        $this->load->view('fix/header');
        $this->load->view('categorias/edita_categoria_view', $dados);
        $this->load->view('fix/footer');

    }

    public function cadastrarsub(){

        $this->load->model('subcategoriasM', 'subcatM');

        $this->form_validation->set_rules('id_categoria', 'id_categoria', 'required');
        $this->form_validation->set_rules('nome_sub', 'nome_sub', 'required');

        if ($this->form_validation->run()) {

           $dados['id_categoria'] = $this->input->post('id_categoria');
           $dados['nome_sub'] = $this->input->post('nome_sub');

           if ($this->input->post('id') != NULL) {
              
              $this->subcatM->add_subcategoria($dados,$this->input->post('id'));
              $this->session->set_flashdata('editado_sub', '<div class="alert alert-success" role="alert">Fornecedor editado com sucesso ! </div>');
           }else{

            $this->subcatM->add_subcategoria($dados);
            $this->session->set_flashdata('sucesso_sub', '<div class="alert alert-success" role="alert">Fornecedor cadastrado com sucesso ! </div>');

           }

            
       }else{

        $this->session->set_flashdata('dados', '<div class="alert alert-success" role="alert">Preencha todos os campos corretamente ! </div>');


       }
   }

}