<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class tipoMovimento extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
	}

	public function tipoMovimento()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('tipo_movimento');

        $this->mapa_calor->registrar_acessos_url(site_url('/tipoMovimento/TipoMovimento'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/tipoMovimento');
		$this->load->view('fix/footer_NS');   
	}
   
        public function buscarTipoMovimento(){   
            $nome =  $this->input->post('idEmpresa');
    
            $CI =& get_instance();
    
            # URL configurada para a API
            $request = $CI->config->item('url_api_shownet_rest').'logistica/listarTipoMovimento?idEmpresa=';
    
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
                    $result[] = array(
                        'id' => $value['id'],
                        'nomeEmpresa' => $value['nomeEmpresa'],
                        'idEmpresa' => $value['idEmpresa'],
                        'nomeTipoMovimento' => $value['nomeTipoMovimento'],
                        'dataCadastro' => $value['dataCadastro'],
                        'dataAtualizacao' => $value['dataUpdate'],
                        'status' => $value['status'],
                    );
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

    public function editarTipoMovimento(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/editarCadastroTipoMovimento';

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
                'idTMovimento' => $nome['idTMovimento'],
                'nome' => $nome['nome'],
                'status' => $nome['status'],
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


    
    public function inserirTipoMovimento(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/cadastrarTipoMovimento';

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
                'idEmpresa' => $nome['idEmpresa'],
                'nome' => $nome['nome'],
            )),
        CURLOPT_HTTPHEADER => $headers
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

    public function listarTiposdeMovimento(){   

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_token_logistica').'logistica/listarTiposMovimentos';

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
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $headers,
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $result = array();

        if ($statusCode === 200){
            foreach ($resultado as $value) {
                $result[] = array(
                    'id' => $value['id'],
                    'nomeEmpresa' => $value['nomeEmpresa'],
                    'idEmpresa' => $value['idEmpresa'],
                    'nomeTipoMovimento' => $value['nomeTipoMovimento'],
                    'dataCadastro' => $value['dataCadastro'],
                    'dataAtualizacao' => $value['dataUpdate'],
                    'status' => $value['status'],
                );
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
}