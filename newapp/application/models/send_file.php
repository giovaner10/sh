<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Send_file extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('retorno');
	}
	
	public function envia_http($post){
		$config['upload_path'] = $post['path'];
		$config['allowed_types'] = 'ret|crt|RET';
		$config['max_size'] = '100000';
		$config['remove_spaces'] = false;
		$config['encrypt_name'] = false;
		$config['overwrite'] = true;
		$this->upload->initialize($config);
		if($this->upload->do_upload('arquivo')) {
			$file = $this->upload->data();
			try {

				if ($file['file_ext'] == '.ret' || $file['file_ext'] == '.RET') {
					$this->retorno->processar($post['path'], $file['file_name']);	
				} else if ($file['file_ext'] == '.crt' || $file['file_ext'] == '.CRT') {
					$this->retorno->processar_sicredi($post['path'], $file['file_name']);	
				}
				$this->session->set_flashdata('file_retorno', $file['file_name']);
				return 1;
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$file = $this->upload->data();
			echo $this->upload->display_errors();
		}
	}
}