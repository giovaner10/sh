<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Send_filetxt extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload');
        $this->load->model('linha');
    }

    public function envia_http($post) {
        $config['upload_path'] = $post['path'];
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '10000';
        $config['remove_spaces'] = false;
        $config['encrypt_name'] = false;
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('arquivo')) {
            $file = $this->upload->data();
            try {
                $this->linha->processar($post['path'], $file['file_name']);
                $this->session->set_flashdata('file_linha', $file['file_name']);
                echo '1';
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $file = $this->upload->data();
            echo $this->upload->display_errors();
        }
    }

}
