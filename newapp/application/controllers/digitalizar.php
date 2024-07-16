<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class digitalizar extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//$this->auth->is_logged('admin');
		$this->load->model('digitaliza');
		$this->load->library('upload');
		//$this->auth->is_allowed('contas_visualiza');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
	}
		public function index() {
		$dados['titulo'] = "digitalizar";
		$dados['msg'] = '';
		$dados['arquivos'] = $this->digitaliza->get_all();
		
		$this->load->view('fix/header', $dados);
        $this->load->view('view_digitalizar');
        $this->load->view('fix/footer');
	}

	public function remove($id){
		//$this->auth->is_allowed('cadastros_digita');
		$id_abastecimento = array('id' => $id);

		if($this->digitaliza->remove($id_abastecimento)) {

			redirect('digitalizar/index');
		}
		redirect('digitalizar/index');		
	}

	public function add() {
		$data['valor'] = str_replace(',', '.', str_replace('.', '', $this->input->post('valor')));
		$data['data_vencimento'] = data_for_unix($this->input->post('data_vencimento'));
		$data['fornecedor'] = $this->input->post('fornecedor');
		$data['descricao'] = $this->input->post('descricao');
		$data['status'] = 0;
		exit(json_encode($this->conta->add($data)));
	}

	public function get() {
		exit(json_encode($this->conta->get($this->input->post())));
	}

	public function update() {
		$dados['titulo'] = "Contas";

		if($this->input->post('valor_pago') && $this->input->post('data_pagamento')) {
			$data['valor_pago'] = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_pago')));
			$data['data_pagamento'] = data_for_unix($this->input->post('data_pagamento'));
			$data['status'] = 1;
		}else{
			$data['status'] = 3;
		}

		$data['updated'] = 1;
		$data['id'] = $this->input->post('id');
		exit(json_encode($this->conta->update($data)));
	}

	public function digitalizar() {
		$data['arquivos'] = $this->conta->get_files();
		

		$this->load->view('view_digitalizar', $data);
	}

	public function view_file($path) {	
		redirect('https://gestor.showtecnologia.com:85/sistema/newapp/uploads/digitalizacoes/'.$path);
	}

	public function digitalizacao() {
		$nome_arquivo = "";
		$descricao = $this->input->post('descricao');
		$arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;
		$arquivo_enviado = false;	

		if ($descricao == "") {
			die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
		}else{
			if ($arquivo) {
				if ($dados = $this->upload()) {
					$nome_arquivo = $dados['file_name'];
					$arquivo_enviado = true;
					//$arquivo_enviado = false;
				}
				if($arquivo_enviado) {
					$retorno = $this->digitaliza->digitalizacao($descricao, $nome_arquivo);
					die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Processo realizado com sucesso!</div>', 'registro' => $retorno)));
				}else{
			    	die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizadokkkkk!</div>')));
			    }
			}else{
	            die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizadoooo!</div>')));
			}	    
		}
	}

	private function upload()
	{
		$config['upload_path'] = './uploads/digitalizacoes';
		$config['allowed_types'] = 'pdf';
		$config['max_size']	= '0';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$config['encrypt_name']  = 'true';
		$this->upload->initialize($config);
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('arquivo')) {
			$data = $this->upload->data();
			return $data;  
		}else{	
			$error = array('error' => $this->upload->display_errors());
			return false;
		}
	}
	
}