<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class fornecedores extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->model('cadastrar_fornecedor');
		$this->load->model('log_shownet');

	}

	public function index()
    {
        
		$dados['titulo'] = lang('fornecedores');
        $this->mapa_calor->registrar_acessos_url(site_url('/GestaoDeChips/fornecedores'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('gestaoDeChips/fornecedor');
		$this->load->view('fix/footer_NS');   
	}
    
    public function listarAllFornecedores() {
        $CI =& get_instance();
        
        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarFornecedores';
        
        $token = $CI->config->item('token_api_shownet_rest');
  
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
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer '. $token
            ),
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
                    'nomeFornecedor' => $value['razaoSocial'],
                    'cpfCnpjFornecedor' => $value['registro'],
                    'cidade' => $value['cidade'],
                    'cep' => $value['cep'],
                    'rua' => $value['endereco'],
                    'bairro' => $value['bairro'],
                    'estado' => $value['uf'],
                    'complemento' => $value['complemento'],
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

    
	public function buscarFornecedores() {
         $CI =& get_instance();
        
        $tipo = $this->input->post('tipo');
        
        switch ($tipo) {
            case 'empresa':
                $valor = $this->input->post('idEmpresa');
                $request = $CI->config->item('url_api_shownet_rest') . 'logistica/listarFornecedoresByEmpresaRazaoSocialDocumento?idEmpresa=' . $valor . '&razaoSocial=&cpfCnpj=';
                break;
            case 'registro':
                $valor = $this->input->post('registro');
                $request = $CI->config->item('url_api_shownet_rest') . 'logistica/listarFornecedoresByEmpresaRazaoSocialDocumento?idEmpresa=&razaoSocial=&cpfCnpj=' . $valor;
                break;
            case 'fornecedor':
                $valor = $this->input->post('fornecedor');
                $valor = urlencode($this->input->post('fornecedor'));
                $request = $CI->config->item('url_api_shownet_rest') . 'logistica/listarFornecedoresByEmpresaRazaoSocialDocumento?idEmpresa=&razaoSocial=' . $valor . '&cpfCnpj=';
                break;
        }
    
        $token = $CI->config->item('token_api_shownet_rest');
    
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
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer '. $token
            ),
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
                    'nomeFornecedor' => $value['razaoSocial'],
                    'cpfCnpjFornecedor' => $value['registro'],
                    'cidade' => $value['cidade'],
                    'cep' => $value['cep'],
                    'rua' => $value['endereco'],
                    'bairro' => $value['bairro'],
                    'estado' => $value['uf'],
                    'complemento' => $value['complemento'],
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

    private function getToken(){
        @session_start();
        
        if (!isset($_SESSION['tokenLogistica']) && !isset($_SESSION['validadeLogistica'])) {
        
            $this->load->model('usuario');
        
            $user = $this->auth->get_login_dados('email');
            $senha = '';
            foreach ($this->usuario->get("login ='$user'") as $key => $value) {
                if ($key == 'senha'):
                    $senha = $value;
                endif;
            }
            //aquisição do token necessário para requisitar dados na api
            $token = getTokenLogistica($user, $senha);
            //salvando token na sessão
            $_SESSION['tokenLogistica'] = $token;
            $_SESSION['validadeLogistica'] = date("d/m/y H:i:s",strtotime(" + 30 minutes"));
            return $token;
        } else {
            if($_SESSION['validadeLogistica'] > date('d/m/y H:i:s')){
                $token = $_SESSION['tokenLogistica'];
                return $token;
            } else {
                unset($_SESSION['tokenLogistica']);
                unset($_SESSION['validadeLogistica']);
                return $this->getToken();
            }
        }
    }

    public function editarFornecedores(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/editarCadastroFornecedor';
        
        $token = $CI->config->item('token_api_shownet_rest');

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
                'idFornecedor' => $nome['idFornecedor'],
                'nome' => $nome['nome'],
                'registro' => $nome['registro'],
                'rua' => $nome['rua'],               
                'uf' => $nome['uf'],
            )),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '. $token,
            'Content-Type: application/json'
        ),
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

    public function inserirFornecedor(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API

        $request = $CI->config->item('url_api_shownet_rest').'logistica/cadastrarFornecedor';

        $token = $this->getToken();
        
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
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '. $token
            
        ),
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

    public function alterarStatusFornecedor(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/alterarStatusCadastroFornecedor';

        $token = $this->getToken();
        
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
                'idFornecedor' => $nome['idFornecedor'],
                'status' => $nome['status'],
            )),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '. $token,
            'Content-Type: application/json'
            
        ),
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

    public function listarEmpresas() {
        $CI =& get_instance();
        
        $nome =  $this->input->get('q');
        $nome = str_replace(' ', '%20', $nome);

        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarEmpresas?nome='.$nome;

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

            
        if($resultado){
            foreach ($resultado as $key => $value) {
                $result[] = array(
                    'id' => $value['id'],
                    'text' => $value['razaoSocial'],
                    'status' => $value['status'],
                );
            }
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