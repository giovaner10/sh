<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class transportadores extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
	}

	public function transportador()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('transportadores');
        $this->mapa_calor->registrar_acessos_url(site_url('/transportadores/transportador'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/transportador');
		$this->load->view('fix/footer_NS');   
	}
    
	public function buscarTransportadores(){
        $nome =  $this->input->post('idEmpresa');

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarTransportadoresById?idEmpresa=';

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
	    $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $request.$nome,
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

        if ($statusCode === 200){
            foreach ($resultado as $value) {
                if ($value['status'] == 'Ativo'){
                    $result[] = array(
                        'id' => $value['id'],
                        'nomeEmpresa' => $value['nomeEmpresa'],
                        'nomeTransportador' => $value['razaoSocial'],
                        'cpfCnpjTransportador' => $value['registro'],
                        'cidade' => $value['cidade'],
                        'cep' => $value['cep'],
                        'rua' => $value['endereco'],
                        'bairro' => $value['bairro'],
                        'estado' => $value['uf'],
                        'complemento' => $value['complemento'],
                        'dataCadastro' => $value['dataCadastro'],
                        'dataAtualizacao' => $value['dataUpdate'],
                        'status' => $value['status'],
                        'idEmpresa' => $value['idEmpresa'],
                    );
                }
            }

        }else{
            $result = $resultado;
        }
        
        

        curl_close($curl);

        echo json_encode(
            array(
                'status' => $statusCode,
                'dados'       => $result,
            )
        );
    }

    public function editarTransportador(){
        $dados = $this->input->post();

        $retorno = to_editarCadTransportadores($dados);

        echo json_encode($retorno);
    }

    public function inserirTransportador(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API

        $request = $CI->config->item('url_api_shownet_rest').'logistica/cadastrarTransportador';

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
            CURLOPT_POSTFIELDS => json_encode(array(
                'nome' => $nome['nome'],
                'registro' => $nome['cpfCnpj'],
                'cep' => $nome['cep'],
                'rua' => $nome['rua'],
                'bairro' => $nome['bairro'],
                'cidade' => $nome['cidade'],
                'uf' => $nome['uf'],
                'complemento' => $nome['complemento'],
                'idEmpresa' => $nome['idEmpresa'],
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

    public function alterarStatusTransportador(){
        $dados = $this->input->post();

        $retorno = to_alterarStatusTransportador($dados);

        echo json_encode($retorno);

    }

    public function listarTodosTransportadores(){

        $retorno = get_listarTransportadoresAll();

        echo json_encode($retorno);
    }



}