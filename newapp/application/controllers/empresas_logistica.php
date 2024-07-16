<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class empresas_logistica extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->auth->is_logged('admin');
	}

	public function empresa()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('empresas');
        $this->mapa_calor->registrar_acessos_url(site_url('/empresas_logistica/empresa'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/empresa');
		$this->load->view('fix/footer_NS');   
	}
    
    public function buscarEmpresas(){
        $nome =  $this->input->get('q');

       // $nome = str_replace(' ', '%20', $nome);

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarEmpresas?nome=';  
                     
            
            //aquisição do token necessário para requisitar dados na api
            //$token = getTokenLogistica($user, $senha);
            
        //AJUSTE FUTURO, pois é paleativo para funcionamento em produção
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

        
            foreach ($resultado as $value) {
                $result[] = array(
                    'id' => $value['id'],
                    'nomeEmpresa' => $value['razaoSocial'],
                    'cpfCnpj' => $value['registro'],
                    'cep' => $value['cep'],
                    'rua' => $value['endereco'],
                    'bairro' => $value['bairro'],
                    'cidade' => $value['cidade'],
                    'estado' => $value['uf'],
                    'complemento' => $value['complemento'],
                    'dataCadastro' => $value['dataCadastro'],
                    'dataAtualizacao' => $value['dataUpdate'],
                    'status' => $value['status']
                );
            }
                
        curl_close($curl);

        echo json_encode(
            array(
                'status' => $statusCode,
                'results'       => $result,
                'pagination'    => [
                    'more'      => false,
                ]
            )
        );
    }
    public function inserirEmpresa(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/cadastrarEmpresa';

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
                'registro' => $nome['registro'],
                'cep' => $nome['cep'],
                'rua' => $nome['rua'],
                'bairro' => $nome['bairro'],
                'cidade' => $nome['cidade'],
                'uf' => $nome['uf'],
                'complemento' => $nome['complemento'],
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
    public function editarSetor(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/editarCadastroSetor';

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
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode(array(
                'idSetor' => $nome['idSetor'],
                'nome' => $nome['nome'],
                'status' => $nome['status'],
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

    public function editarEmpresa(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/editarCadastroEmpresa';

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
                'bairro' => $nome['bairro'],
                'cep' => $nome['cep'],
                'cidade' => $nome['cidade'],
                'complemento' => $nome['complemento'],
                'idEmpresa' => $nome['idEmpresa'],
                'nome' => $nome['nome'],
                'registro' => $nome['registro'],
                'rua' => $nome['rua'],
                'uf' => $nome['uf'],
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

    public function alterarStatusEmpresa(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/alterarStatusCadastroEmpresa';

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
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode(array(
                'idEmpresa' => $nome['idEmpresa'],
                'status' => $nome['status'],
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


}