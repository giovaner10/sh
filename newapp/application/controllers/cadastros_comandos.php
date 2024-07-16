<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cadastros_comandos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->model('arquivo');
		$this->load->model('comando');
		$this->load->model('cadastro_csv');
		$this->load->model('mapa_calor');
	}

	public function index()
	{
		$dados['titulo'] = lang('comandos');
		$dados['msg'] = false;

		$this->mapa_calor->registrar_acessos_url(site_url('/cadastros_comandos'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('comandos/cadastro_comando');
		$this->load->view('fix/footer_NS');
		$this->load->model('arquivo');
	}

	public function enviarComando()
	{
		if ($this->input->post('serial') && $this->input->post('comando')) {
			$this->load->model('equipamento');

			if ($this->equipamento->put_comando($this->input->post('serial'), $this->input->post('comando'))) {
				$comando = $this->input->post('comando');
				$serial = $this->input->post('serial');
				echo json_encode(array('success' => 'true', 'message' => 'Solicitado com sucesso.', 'data' => array('comando' => $comando, 'serial' => $serial)));
			
			} else {
				echo json_encode(array('success' => 'false', 'message' => 'Comando não gravado, o prefixo do serial não existe.'));
			
			}
		}
	}

	public function salvar()
	{
		if ($_FILES) {

			$path = './uploads/comandos/';
			$config['upload_path'] = $path ;

			if ($_FILES['arquivos']['type'] == 'text/xml'){
						$config['allowed_types'] = '*';
			}
			
			$config['max_size']	= '10000';
			$config['file_name'] = time() . '_' .$this->nome_arquivo($_FILES['arquivos']['name']);

			$this->upload->initialize($config);

			if ($this->upload->do_upload('arquivos')) {
				
				$dados = $this->input->post();
				$arquivo = $config['file_name'];
				$dados['xml'] = file_get_contents($path.$arquivo);
				if($this->comando->add_command($dados)){
					echo json_encode(array('success' => true, 'msg' => 'Comando salvo com sucesso.'));
				}else{
					echo json_encode(array('success' => false, 'msg' => 'Não foi possível salvar o comando'));
				}							
			}else{
				echo json_encode(array('success' => false, 'msg' => $this->upload->display_errors()));

			}
			
		}
	}

	public function salvar_csv()
	{
		if ($_FILES) {
			$path = './uploads/comandos/';
			$config['upload_path'] = $path;
			if ($_FILES['arquivos']['type'] == 'text/csv' || $_FILES['arquivos']['type'] == 'text/csvx' ){
				$config['allowed_types'] = '*';
			}
			
			$config['max_size']	= '10000';
			$config['file_name'] = time() . '_' .$this->nome_arquivo($_FILES['arquivos']['name']);
			$this->upload->initialize($config);
			$arquivo = $config['file_name'];
			$now =  date('Y-m-d H:i:s');
			if ($this->upload->do_upload('arquivos')) {
				$infos_file = $this->read_csv($path.$arquivo);
				$dados_file = array();
				if(is_file($path.$arquivo)){
					for($i = 1; $i < count($infos_file); $i++){
						
						$dados_file['serial'][]= utf8_decode($infos_file[$i][0]);
						$dados_file['modelo'][]= utf8_decode($infos_file[$i][1]);
						$dados_file['marca'][]= utf8_decode($infos_file[$i][2]);
						$dados_file['linha'][]= utf8_decode($infos_file[$i][3]);
						$dados_file['ccid'][]= utf8_decode($infos_file[$i][4]);
						$dados_file['operadora'][]= utf8_decode($infos_file[$i][5]);				
						
					}
					@unlink($path.$arquivo);					
				}				
				if($this->cadastro_csv->add_eqp($dados_file)){
					echo json_encode(array('success' => true, 'msg' => 'Comando salvo com sucesso.'));
				}else{
					echo json_encode(array('success' => false, 'msg' => 'Não foi possível salvar o comando'));
				}							
			}else{
				echo json_encode(array('success' => false, 'msg' => $this->upload->display_errors()));
			}			
		}
	}

	protected function read_csv ($file){

		$file = fopen($file, 'r');

		$file_content = array();

		while (!feof($file)) {
			$file_content[] = fgetcsv($file);
		}
		return $file_content;
	}
	
	private function nome_arquivo($string) {
		$from = array('À','Á','Ã','Â','à','á','â','ã','É','È','Ê','è','é','ê','Ò','Ó','Õ','Ô','ò','ó','ô','õ','Ú','Ù','Û','ú','ù','û','Ç','ç','Ñ','ñ','Í','Ì','ì','í','~','´','`','^',' ');
		$to =   array('A','A','A','A','a','a','a','a','E','E','E','e','e','e','O','O','O','O','o','o','o','o','U','U','U','u','u','u','C','c','N','n','I','I','i','i','_','_','_','_','_');
        $string = str_replace($from, $to, $string);
		return strtolower($string);
	}

	public function listar($page = 0) {
		$this->to_view['registros'] = $this->comando->get_all();
		$this->load->view('comandos/listar', $this->to_view);
	}

	public function apagar() {
		if ($this->input->get('id')) {
			if ($this->comando->remove_command(array('code'=>$this->input->get('id')))) {
				exit(json_encode(array('success' => true)));
			} else {
				exit(json_encode(array('success' => false)));
			}
		}
	}
}