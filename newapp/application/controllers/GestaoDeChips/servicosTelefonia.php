<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class servicosTelefonia extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
	}

	public function index()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('servicosTelefonia');
        $this->mapa_calor->registrar_acessos_url(site_url('/GestaoDeChips/servicosTelefonia'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('gestaoDeChips/servicosTelefonia');
		$this->load->view('fix/footer_NS');   
	}
    
    public function selectFornecedores(){
        $empresa = $this->input->post('idEmpresa');

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarFornecedores?idEmpresa=';

        $token = $CI->config->item('token_api_shownet_rest');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $request.$empresa,
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
                    'text' => $value['razaoSocial']
                    
                );
            }

        }else{
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

	public function buscarServicos(){
        $nome = $this->input->post('idFornecedor');

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'servicosTelefonia/listarServicosTelefonia?idFornecedor=';

        $token = $CI->config->item('token_api_shownet_rest');

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
                    'nome' => $value['nome'],
                    'servicoOperadora' => $value['servicoOperadora'],
                    'idFornecedor' => $value['idFornecedor'],
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

    public function editarServicos(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'servicosTelefonia/editarServicoTelefonia';

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
                'idServico' => $nome['idServico'],
                'nome' => $nome['nome'],
                'codServico' => $nome['codServico'],
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

    public function inserirFornecedor(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API

        $request = $CI->config->item('url_api_shownet_rest').'servicosTelefonia/cadastrarServicoTelefonia';

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
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                'nome' => $nome['nome'],
                'servicoOperadora' => $nome['servicoOperadora'],
                'idFornecedor' => $nome['idFornecedor'],
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

    public function alterarStatusServico(){
        $nome =  $this->input->post();

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'servicosTelefonia/alterarStatusServicoTelefonia';

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
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode(array(
                'idServico' => $nome['idServico'],
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

}