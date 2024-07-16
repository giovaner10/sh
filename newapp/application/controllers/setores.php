<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class setores extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
	}

	public function setor()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('setores');
        $this->mapa_calor->registrar_acessos_url(site_url('/setores/setor'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/setor');
		$this->load->view('fix/footer_NS');   
	}
    
	public function buscarEmpresas(){
        $nome =  $this->input->get('q');

        $nome = str_replace(' ', '%20', $nome);

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarEmpresas?nome=';

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
        foreach ($resultado as $key => $value) {
            $result[] = array(
                'id' => $value['id'],
                'text' => $value['razaoSocial'],
                'status' => $value['status'],
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

    public function buscarRegistros() {
        
        $registro = $this->input->get('q', '');
        
        $CI =& get_instance();

        $token = $CI->config->item('token_api_shownet_rest');

        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarFornecedores';

        if ($registro !== '') {
            $registroNumerico = preg_replace('/\D/', '', $registro);
            $request = $CI->config->item('url_api_shownet_rest') . 'logistica/listarFornecedoresByEmpresaRazaoSocialDocumento?idEmpresa=&razaoSocial=&cpfCnpj=' . $registroNumerico;
        }
    
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
        foreach ($resultado as $key => $value) {
            $result[] = array(
                'id' => $value['id'],
                'text' => $value['registro'],
                'fornecedor' => $value['razaoSocial'],
                'status' => $value['status'],
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

    public function buscarFornecedores(){
        $entrada = $this->input->get('q', '');

        $CI =& get_instance();

        $token = $CI->config->item('token_api_shownet_rest');

        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarTodosFornecedores';

        if ($entrada !== '') {
            $fornecedor = str_replace(' ', '%20', $entrada);
            $request = $CI->config->item('url_api_shownet_rest') . 'logistica/listarFornecedoresByEmpresaRazaoSocialDocumento?idEmpresa=&razaoSocial=' . $fornecedor . '&cpfCnpj=';
        }

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
        foreach ($resultado as $key => $value) {
            $result[] = array(
                'id' => $value['id'],
                'text' => $value['razaoSocial'],
                'status' => $value['status'],
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

	public function buscarEmpresas_ajax(){
		$this->load->helper('util_helper');
        $result = buscarEmpresas();
        echo $result;
    }

    public function buscarSetores(){
        $nome =  $this->input->post('idEmpresa');

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarSetores?idEmpresa=';

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
                    'idEmpresa' => $value['idEmpresa'],
                    'nomeEmpresa' => $value['nomeEmpresa'],
                    'nomeSetor' => $value['nomeSetor'],
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

    public function inserirSetor(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/cadastrarSetor';

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
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode(array(
                'idSetor' => $nome['idSetor'],
                'nomeSetor' => $nome['nomeSetor'],
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