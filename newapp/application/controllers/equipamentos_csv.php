<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equipamentos_csv extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->model('arquivo');
		$this->load->model('comando');
	}

	public function index()	{
		$this->load->view('equipamentos/cadastro_csv');
		$this->load->model('arquivo');
		$this->to_view['titulo'] = 'Cadastro';
	}

	public function salvar() {
		if ($_FILES) {
			$path = './uploads/comandos/';
			$config['upload_path'] = $path ;

			if ($_FILES['arquivos']['type'] == 'text/xml')
				$config['allowed_types'] = '*';

			$config['max_size']	= '10000';
			$config['file_name'] = time() . '_' .$this->nome_arquivo($_FILES['arquivos']['name']);

			$this->upload->initialize($config);

			if ($this->upload->do_upload('arquivos')) {
				
				$dados = $this->input->post();
				$arquivo = $config['file_name'];
				$dados['xml'] = file_get_contents($path.$arquivo);
				if($this->comando->add_command($dados))
					echo json_encode(array('success' => true, 'msg' => 'Comando salvo com sucesso.'));
				else
					echo json_encode(array('success' => false, 'msg' => 'Não foi possível salvar o comando'));
			} else {
				echo json_encode(array('success' => false, 'msg' => $this->upload->display_errors()));
			}
		}
	}
	
	private function nome_arquivo($string) {
		$from = array('À','Á','Ã','Â','à','á','â','ã','É','È','Ê','è','é','ê','Ò','Ó','Õ','Ô','ò','ó','ô','õ','Ú','Ù','Û','ú','ù','û','Ç','ç','Ñ','ñ','Í','Ì','ì','í','~','´','`','^',' ');
		$to =   array('A','A','A','A','a','a','a','a','E','E','E','e','e','e','O','O','O','O','o','o','o','o','U','U','U','u','u','u','C','c','N','n','I','I','i','i','_','_','_','_','_');
        $string = str_replace($from, $to, $string);
		return strtolower($string);
	}

}