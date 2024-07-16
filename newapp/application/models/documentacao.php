<?php

date_default_timezone_set('America/Recife');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Documentacao extends CI_Model {

    public function __construct() {
        parent::__construct();


        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['bcc_batch_mode'] = true;
        $config['bcc_batch_size'] = 500;
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_user'] = 'suporteshowtec@gmail.com';
        $config['smtp_pass'] = '212907ecr';
        $config['smtp_port'] = '465';

        $this->email->initialize($config);

        $this->email->set_newline("\r\n");
    }

    public function enviar_email(/* to ,*/ $bcc, $from_nome, $from_email, $subject, $message) {

        $this->email->clear(TRUE);
//        $this->email->to($to);
         $this->email->bcc($bcc);
        $this->email->from($from_email, $from_nome);
        $this->email->subject($subject);
        $this->email->message($message);
        
        if ($this->email->send()) {
            return true;
//	     echo $this->email->print_debugger();
        }
          return false;
          
               
         /* 	    if ($anexo)
          $this->email->attach($anexo);
         */
    }

    public function get_documentacao() {

        $query = $this->db->get('showtecsystem.documentacoes');

        if ($query->num_rows()) {

            return $query->result();
        }
        return false;
    }

    public function add_documentacao($nome, $file, $destinatario_email, $data_vencimento, $numeracao, $data_insercao, $user_logado) {
        /*
          $insert['nome'] = $nome;
          $insert['data_vencimento'] = $data_vencimento;
          $insert['numeracao'] = $numeracao;
          $insert['data_insercao'] = $data_insercao;
          $insert['user_logado'] = $user_logado;
         */
        $insert = array(
            'nome' => $nome,
            'file' => $file,
            'destinatario_email' => $destinatario_email,
            'data_insercao' => $data_insercao,
            'usuario' => $user_logado,
            'iddocumentacoes' => $numeracao,
            'data_vencimento' => $data_vencimento
        );

        $this->db->insert('documentacoes', $insert);



        /*
          $this->db->insert('', $data);
          if($this->db->affected_rows() > 0)
          return 1;
          return 0;

          }
         */
    }

    public function excluir_documentacao($id) {

        $this->db->delete('documentacoes', array('iddocumentacoes' => $id));

        if ($this->db->affected_rows() > 0) {

            return false;
        } else {

            return true;
        }
    }

}
