<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Documentacoes extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->auth->is_logged('admin');
        // autenticacao
        $this->load->model('Documentacao');
        $this->load->helper('date');
        $this->load->model('auth');
    }

    public function index() {

        $dados['titulo'] = 'documentações';


        $consulta['x'] = array('query' => $this->Documentacao->get_documentacao());

        $this->load->view('fix/header', $dados);
        $this->load->view('documentacoes/home', $consulta);
        $this->load->view('fix/footer');
    }

    public function cadastrar() {

        $dados['titulo'] = 'Documentações';
        $this->load->view('fix/header', $dados);
        $this->load->view('documentacoes/cadastrar');
        $this->load->view('fix/footer');
    }

    public function excluir() {

        $id = $this->input->post("id");

        $this->Documentacao->excluir_documentacao($id);

        $dados['titulo'] = 'documentações';

        $consulta['x'] = array('query' => $this->Documentacao->get_documentacao());

        if ($this->Documentacao->excluir_documentacao($id) === true) {
            $dados['del'] = "<div class='alert alert-success' role='alert'>Documentação excluida com sucesso!</div>";
        } else {

            $dados['del'] = "<div class='alert alert-danger' role='alert'>ERRO ao excluir documentação. </div>";
        }
        $this->load->view('fix/header', $dados);
        $this->load->view('documentacoes/home', $consulta);
        $this->load->view('fix/footer');
    }

    public function upload_file() {

        $config["upload_path"] = "./uploads/documentacoes/";
        $config["allowed_types"] = "txt|docx|doc|zip|pdf|rar|xlsx|xls|pptx|ppt";
        $config['max_size'] = '90000';

        $this->load->library("upload", $config);

        if ($this->input->post("upload") === false) {

            echo "FORMULARIO UPLOAD NÃO CARREGADO!";
            return;
        }

        if (!$this->upload->do_upload("file")) {
            $dados['titulo'] = 'documentações';

            $error = $this->upload->display_errors();

            $error = "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
            $dados['error'] = $error;
            $this->load->view('fix/header', $dados);


            $this->load->view('documentacoes/cadastrar');
            $this->load->view('fix/footer');
        } else {

            // recebe os dados
            $nome_documentacao = $this->input->post("nome_documento");
            $file_ = $_FILES['file']['name'];
            $file = str_replace(" ", "_", $file_);


            $dt_ven = $this->input->post("data_vencimento");
            $data_vencimento = date("Y-m-d", strtotime($dt_ven));

            $destinatario_email = $this->input->post("destinatario_email");

            $numeracao_controle = $this->input->post("numeracao_controle");
            $data_insercao = $this->input->post("data_insercao");
            $usuario_logado = $this->input->post("usuario_logado");

            // adiciona no banco
            $this->Documentacao->add_documentacao($nome_documentacao, $file, $destinatario_email, $data_vencimento, $numeracao_controle, $data_insercao, $usuario_logado);


            $dados['titulo'] = 'documenta&ccedil;&atilde;oes';
            $dados['data'] = array('query' => $this->Documentacao->get_documentacao());
            $data['z'] = array('upload_data' => $this->upload->data());

            // ENVIA EMAIL



            $to = $destinatario_email;
            $bcc = $destinatario_email;
            $from_email = $usuario_logado;
            $from_nome = "Show Tecnologia";
            $subject = $nome_documentacao;
            
            $m_topo = "Olá. <br> Seguem os dados vindos da show tecnologia para seu interesse.";
            $link = '<a href=' . base_url("uploads/documentacoes") . "/" . $file . ">$file</a>";
            $m_link = "<p>Clique no link ao lado para visualizar: " . $link . "</p>";
            $m_rodape = "<br><br><br> Att.<br> Show Tecnologia";
            $message = $m_topo.$m_link.$m_rodape;
            
            if ($bcc) {
                // manda para o model documentacao
                $this->Documentacao->enviar_email(
                        //    $to,
                        $bcc, $from_nome, $from_email, $subject, $message
                );
            }

            $data['sucesso_upload'] = "";
            $data['nome_documento'] = $nome_documentacao;

            $this->load->view('fix/header', $dados);
            $this->load->view('documentacoes/home', $data);
            $this->load->view('fix/footer');
        }
    }

}
