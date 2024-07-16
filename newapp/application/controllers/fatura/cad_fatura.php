<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class cad_fatura extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->model('mapa_calor');
	}

	public function index()
	{
		$this->auth->is_allowed('cadastros_shownet');

		$this->mapa_calor->registrar_acessos_url(site_url('/fatura/cad_fatura'));

		$dados = array(
			'titulo' => 'Cadastro de Faturas',
			'load' => array('buttons_html5', 'datatable_responsive', 'xls'),
		);

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('gestaoDeChips/fatura');
		$this->load->view('fix/footer_NS');
	}


	//Editar faturas
	public function editarFaturas()
	{
		$dados = $this->input->post();

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest') . 'fatura/editarFatura';

		$token = $CI->config->item('token_api_shownet_rest');

    	$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
    	$headers[] = 'Authorization: Bearer '.$token;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $request,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POSTFIELDS => json_encode(array(
                'idFatura' => $dados['idFatura'],
                'idOperadora' => $dados['idOperadora'],
                'mesReferencia' => $dados['mesReferencia'],
                'dataInicio' => $dados['dataInicio'],
                'dataFim' => $dados['dataFim'],
                'valor' => $dados['valor'],
                'vencimento' => $dados['vencimento'],
                'numeroConta' => $dados['numeroConta'],
                'status' => $dados['status'],
            )),
			CURLOPT_HTTPHEADER => $headers,
		));

		if (curl_error($curl))  throw new Exception(curl_error($curl));

		$resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);



        curl_close($curl);

        echo json_encode(
            array(
                'status' => $statusCode,
                'dados'       => $resultado,
            )
        );
	}

	//cadastrar fatura
	public function cadastrarFaturas()
	{
		$dados = $this->input->post();
		$CI = &get_instance();
		$request = $CI->config->item('url_api_shownet_rest') . 'fatura/cadastrarFatura';
			
		$token = $CI->config->item('token_api_shownet_rest');

    	$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
    	$headers[] = 'Authorization: Bearer '.$token;
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $request,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($dados),
			CURLOPT_HTTPHEADER => $headers,
		));

		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		echo json_encode(
			array(
				'status' => $statusCode,
				'dados'  => $resultado,
			)
		);
	}
	//listar faturas
	public function listarFaturas()
	{
		$idOperadora =  $this->input->post('idOperadora');

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest') . 'fatura/listarFaturas?idOperadora=';

		$token = $CI->config->item('token_api_shownet_rest');

    	$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
    	$headers[] = 'Authorization: Bearer '.$token;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $request . $idOperadora,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => $headers,
		));

		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$result = array();

		if ($statusCode === 200) {
			foreach ($resultado as $value) {
				if ($value['status'] == 'Ativo'){				
					$result[] = array(
						"id" => $value['id'],
						"idOperadora" => $value['idOperadora'],
						"mesReferencia" => $value['mesReferencia'],
						"dataInicio" => $value['dataInicio'],
						"dataFim" => $value['dataFim'],
						"valorTotal" => $value['valorTotal'],
						"vencimento" => $value['vencimento'],
						"numeroConta" => $value['numeroConta'],
						"dataCadastro" => $value['dataCadastro'],
						"dataUpdate" => $value['dataUpdate'],
						"status" => $value['status'],
					);
				}
			}
		} else {
			$result = $resultado;
		}

		curl_close($curl);

		echo json_encode(
			array(
				'status' => $statusCode,
				'dados'  => $result,
			)
		);
	}

	public function listarServicos(){
		$this->load->helper('util_helper');

		$retorno = (get_listarServicos());

		if (!empty($retorno)){
			echo json_encode($retorno);
		}else{
			return false;
		}
	}

	public function listarItensFatura(){
		$this->load->helper('util_helper');

		$idFatura = $this->input->post('idFatura');

		$retorno = (get_listarItensFatura($idFatura));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function alterarStatusItemFatura(){
		$body = $this->input->post();
		
		$retorno = (to_alterarStatusItemFatura($body));
			
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function listarLinhas(){
        $this->load->helper('util_helper');
		
		$idOperadora = $this->input->post('idOperadora');

		$retorno = (get_listarLinhas($idOperadora, true));

        $result = array();

        foreach ($retorno as $key => $value) {
			$text = str_replace('.0','', $value['linha']);
			$text = formataTelefone($text);
            $result [] = array(
                'id' => $value['id'],
                'text' => $text,
            );
        }

		if (!empty($result)){
			echo json_encode($result);
		}else{
			return false;
		}
	}

	public function cadastrarItemFatura(){
		$this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_cadastrarItemFatura($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function editarItemFatura(){
		$body = $this->input->post();
	
		$retorno = (to_atualizaCadItem($body));
		
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function cadastrarFaturaEItem(){
		$this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_cadastrarFaturaEItens($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function listarFaturasMesReferencia(){

		$idOperadora = $this->input->post('idOperadora');
		$mesReferencia = $this->input->post('mesReferencia');

		$retorno = (get_listarFaturasMesReferencia($idOperadora, $mesReferencia));

		echo json_encode($retorno);
	}

	
}

function formataTelefone($number){
	if(strpos($number, '(') !== false)
		return $number;
	if(strlen($number) > 11){
		$number="+".substr($number,0,2)." (".substr($number,2,2).") ".substr($number,4,-4)."-".substr($number,-4);
	}else{
		$number="(".substr($number,0,2).") ".substr($number,2,-4)."-".substr($number,-4);
	}

    return $number;
}
