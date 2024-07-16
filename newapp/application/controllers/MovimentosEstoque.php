<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class MovimentosEstoque extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('auth');
        $this->auth->is_logged('admin');
        $this->load->helper('download');
        $this->load->model('mapa_calor');
	}

    public function buscarSetores(){
        $nome = $this->input->post()['idEmpresa'];

        $nome = str_replace(' ', '%20', $nome);

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
        
        curl_close($curl);
        echo json_encode(
            $resultado
        );
    }

    public function listarTipoMovimento(){
        $nome =  $this->input->post('idEmpresa');
         
        $nome = str_replace(' ', '%20', $nome);

        $CI =& get_instance();

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

            echo json_encode($resultado);
    }

    public function listarTransportadores(){
        $nome =  $this->input->post('idEmpresa');

        $nome = str_replace(' ', '%20', $nome);

        $CI =& get_instance();
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
            $statusCode = curl_getinfo($curl);

            echo json_encode($resultado);
    }

    public function buscar_cliente(){
        $this->load->model('cliente');


        $search = $this->input->get('q');
        $tipoBusca = $this->input->get('tipoBusca');
        $BuscarTodos = $this->input->get('BuscarTodos');

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        if($BuscarTodos){
            $clientes = $this->cliente->getClientesExpedicao($search, $tipoBusca, false);
        }else{
            $clientes = $this->cliente->getClientesExpedicao($search, $tipoBusca);
        }

        if(count($clientes) > 0){
            foreach ($clientes as $key => $cliente) {
                //ocorreram alguns casos de o cpf está com poucos caracteres e bugava a verificação
                //necessário colocar uma verificação de caracteres 

                $resposta['results'][] = array(
                    'id' => $cliente['id'],
                    'text' => $cliente['nome']." (" .$cliente['razao_social'] .")",
                    'cep' => $cliente['cep'],
                    'endereco' => $cliente['endereco'],
                    'uf' => $cliente['uf'],
                    'bairro' => $cliente['bairro'],
                    'cidade' => $cliente['cidade'],
                    'orgao' => $cliente['orgao'],
                    'status' => $cliente['status'],
                );
            }
            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }



    }

	public function index()
    {
		$dados['titulo'] = lang('movimentosEstoque');
        $this->mapa_calor->registrar_acessos_url(site_url('/MovimentosEstoque'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/movimentosEstoque/movimentosEstoque');
		$this->load->view('fix/footer_NS');
	}

    public function downloadModeloItens() {
        $caminho_arquivo = base_url('uploads/movimentosEstoque/planilha_itens_movimento.xlsx');

        $response = [
            'status' => 200,
            'mensagem' => $caminho_arquivo
        ];

        echo json_encode($response);
    }

    public function excluirMovimento(){
        $id = $this->input->post('id');

        $CI =& get_instance();
        $request = $CI->config->item('url_api_shownet_rest').'logistica/alterarStatusMovimentoExpedicao';

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
	    $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL =>  $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
        "idMovimento": '.$id.',
        "status": 0
        }',
        CURLOPT_HTTPHEADER => $headers,
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if($statusCode === 200){
            echo json_encode(array(
                "status" => 200,
                "id" => $id
            ));
        }else {
            echo json_encode(array(
                "status" => $statusCode,
                "mensagem" => $resultado
            ));
        }

    }

    public function updateOrCreate(){
        $input = $this->input->post();

        // echo json_encode(array( "action" => $input['action'], "id" => $input['id']));
        // return;

        $CI =& get_instance();

        $dataAtual = date("d/m/Y");  // Definindo a data atual no formato "d/m/Y"

        if(isset($input['action']) && $input['action'] != '' && $input['action'] == "store"){
            $request = $CI->config->item('url_api_shownet_rest')."logistica/cadastrarMovimentoExpedicao";
            $data = [
                "responsavel"           => $input["responsavel"],
                "dataMovimento"         => $dataAtual,
                "idCliente"             => $input["idCliente"],
                "idEmpresa"             => $input["empresa"],
                "tipoEmpresa"           => $input["tipo_orgao"],
                "cep"                   => $input["cep"],
                "regiao"                => $input["regiao"],
                "rua"                   => $input["endereco"],
                "uf"                    => $input["uf"],
                "bairro"                => $input["bairro"],
                "cidade"                => $input["cidade"],
                "idSetor"               => $input["setor"],
                "idTipoMovimento"       => $input["tipo_servico"],
                "qutVolumes"            => $input["qtde_volumes"],
                "observacao"            => $input["observacao"],
                "idTransportador"       => $input["transportador"]
            ];
        } else {
            $request = $CI->config->item('url_api_shownet_rest')."logistica/editarMovimentoExpedicao";
            $data = [
                "id"                    => $input["id"],
                "responsavel"           => $input["responsavel"],
                "idSetor"               => $input["setor"],
                "idTipoMovimento"       => $input["tipo_servico"],
                "qutVolumes"            => $input["qtde_volumes"],
                "observacao"            => $input["observacao"],
                "idTransportador"       => $input["transportador"],
                "status"                => $input["status"]

            ];
        }

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
	    $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $input['action'] == "store" ? 'POST' : 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ));
          
        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($statusCode == 200) {
            $dados = array(
                'status' => 200,
                'dados' => $resultado
            );
        } else {
            $dados = array(
                'status' => $statusCode,
                'dados' => $resultado
            );
        }

        echo json_encode($dados);
    }
    
    public function listarMovimentos(){
        $input = $this->input->post();
        if(!isset($input['idEmpresa']) && $input['idEmpresa'] == '' ){
            $input['idEmpresa'] = '';
        }

        if(@!isset($input['dataInicial']) && @$input['dataInicial'] == '' ){
            $input['dataInicial'] = '';
        } else {
            $input['dataInicial'] = date_format(date_create($input['dataInicial']), 'd/m/Y');
        }

        if(@!isset($input['dataFinal']) && @$input['dataFinal'] == '' ){
            $input['dataFinal'] = '';
        } else {
            $input['dataFinal'] = date_format(date_create($input['dataFinal']), 'd/m/Y');
        }        
        
        $CI =& get_instance();

        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarMovimentosExpedicao?idEmpresa='.$input['idEmpresa'].'&dataInicial='.$input['dataInicial'].'&dataFinal='.$input['dataFinal'];

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

        curl_close($curl);

        if ($statusCode == 200) {
            $dados = array(
                'status' => 200,
                'dados' => $resultado
            );
        } else {
            $dados = array(
                'status' => $statusCode,
                'dados' => $resultado
            );
        }

        echo json_encode($dados);
    }

    public function listarTodasEmpresas(){
        $CI =& get_instance();

        $this->load->model('usuario');

        $request = $CI->config->item('url_api_shownet_rest').'logistica/listarEmpresas?nome=';
        
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

        curl_close($curl);

        echo json_encode($resultado);
    }

    public function getIdEmpresa(){
        $nome = $this->input->get('q') ? $this->input->get('q') : '';

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
    
        curl_close($curl);

        echo json_encode(
            $resultado[0]['id']
        );
    }

	public function buscarEmpresas(){
        $nome = $this->input->get('q') ? $this->input->get('q') : '';

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
                'results'       => $result,
                'pagination'    => [
                    'more'      => false,
                ]
            )
        );
    }

    public function cadastrarMovimentoEItem(){
        $this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_cadastrarMovimentoEItens($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function listarItensMovimento(){
		

		$idMovimento = $this->input->post('idMovimento');

		$retorno = get_listarItensMovimento($idMovimento);

        echo json_encode($retorno);

	}

    public function cadastrarItemMovimento(){

		$body = $this->input->post();

		$retorno = to_cadastrarItemMovimento($body);

        echo json_encode($retorno);		
	}

    public function editarItemMovimento(){

		$body = $this->input->post();

		$retorno = to_atualizaItemMovimento($body);

        echo json_encode($retorno);
	}

    public function removerItemMovimento(){

        $idItem = $this->input->post('idItem');

        $retorno = to_removerItemMovimento($idItem);

        echo json_encode($retorno);

    }

    public function buscarSeriais(){
        $nome = $this->input->get('q');

        $nome = str_replace(' ', '%20', $nome);

        $retorno = (get_buscarSeriais($nome));

        echo json_encode($retorno);
    }
}