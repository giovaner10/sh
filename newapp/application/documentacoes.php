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

            if ($destinatario_email) {
                // chama a funcao enviar email
                $this->enviar_email();
            }


            $data['sucesso_upload'] = "";
            $data['nome_documento'] = $nome_documentacao;

            $this->load->view('fix/header', $dados);
            $this->load->view('documentacoes/home', $data);
            $this->load->view('fix/footer');
        }
    }

    public function enviar_email() {
        $config = array('charset' => 'iso-8859-1',
            'X-Sender' => 'contato@showtecnologia.com',
            'mailtype' => 'text',
            'newline' => '\r\n',
            'wordwrap' => TRUE,
        );   
        
     

        $this->load->library('email', $config);

        
        
    $this->email->from('user@gmail.com', 'sender name');
    $this->email->to('age-nor-neto@hotmail.com');
    $this->email->cc('agenorlcneto@gmail.com'); 
    $this->email->subject('Your Subject');
    $this->email->message('Your Message');
    if ($this->email->send())
        echo "<br><br><br>Mail Sent!";
    else
        echo "<br><br><br>There is error in sending mail!"; 
        
/*
        //      $email_destinatario = $destinatario_email;
        $this->email->from('contato@showtecnologia.com', 'Agenor Neto');
        $this->email->to('age-nor-neto@hotmail.com');
//     $this->email->cc('age-nor-neto@hotmail.com');
        // $this->email->cc('agenorlcneto@gmail.com');

        $this->email->subject('TEstando email');
        $this->email->message('Testing the email class.');

        $this->email->send();

        if (!$this->email->send()) {

            echo "<br><br><br>";
           show_error($this->email->print_debugger());
        } else {

            echo "<br><br><br>";
            echo $this->email->print_debugger();
        }
        */
        


        /*     $this->load->library('email');

          $email_destinatario = $destinatario_email;
          $link = "<a href=" . base_url("uploads/documentacoes/$file>Download</a>");
          $link = "<p>Baixar nova documenta&ccedil;&atilde;o: " . $link . "</p>";
          echo "<p>";
          echo $usuario_logado."-".$nome_documentacao;
          echo "<br>Email destinatario: ".$email_destinatario;
          echo "<br>Link: ".$link;
          //     $this->email->from($usuario_logado, $nome_documentacao);
          //     $this->email->to($email_destinatario);
          //      $this->email->bcc($email_destinatario);
          //     $this->email->subject($nome_documentacao);
          //     $this->email->message($link);
          //    $this->email->send();

          echo "</p>";
         */
    }

}
