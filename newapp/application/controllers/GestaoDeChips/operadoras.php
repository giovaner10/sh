<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class operadoras extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('mapa_calor');
	}

	public function operadora()
    {
        $this->auth->is_allowed('cadastros_shownet');
		$dados['titulo'] = lang('operadoras');
		$this->mapa_calor->registrar_acessos_url(site_url('/GestaoDeChips/operadoras/operadora'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('gestaoDeChips/operadoras');
		$this->load->view('fix/footer_NS');   
	}

    public function listarOperadorasPorData(){
        $this->load->helper('util_helper');

		$dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');

		$retorno = (get_listarOperadorasPorData($dataInicial, $dataFinal));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function listarOperadoras(){
        $this->load->helper('util_helper');
		
		$retorno = (get_listarOperadoras());

        $result = array();

        foreach ($retorno as $key => $value) {
            $result [] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

		if (!empty($result)){
			echo json_encode($result);
		}else{
			return false;
		}
	}

	public function listarTodasOperadoras(){
        $this->load->helper('util_helper');
		
		$retorno = (get_listarOperadoras(True));

		if (!empty($retorno)){
			return $retorno;
		} else {
			return false;
		}
	}

	public function editarOperadora(){

		$body = $this->input->post();
	

		$retorno = (to_atualizaCadOperadora($body));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}
    
    

	public function cadastrarOperadora(){

		$dados = $this->input->post();

		$retorno = json_decode(to_cadastrarOperadora($dados));

		if ($retorno){
			return ($retorno);
		}else{
			return false;
		}


	}
    
}