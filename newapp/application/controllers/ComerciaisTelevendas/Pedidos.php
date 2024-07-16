<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));
class Pedidos extends CI_Controller
{
    public function __construct()
    {
      parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->model('cliente_crm');
		$this->load->model('usuario');
		$this->load->model('log_shownet');
		$this->load->model('mapa_calor');
		$this->load->library('form_validation');
        $this->load->database();
    }

    public function index()
    {
		// verifica se o usuário está logado na api shownet e se não estiver tenta logar, caso não consiga redireciona para a tela de login
		$this->auth->is_logged_api_shownet();

		if (isset($_SESSION['customerId'])) {
			$customerId = $_SESSION['customerId'];  
			$dados['oportunidadeCliente'] 	= ($this->cliente_crm->pegarOportunidade_cliente($customerId)) ? $this->cliente_crm->pegarOportunidade_cliente($customerId) : null ;     
		}
		
		$_SESSION['emailUsuario'] = $this->auth->get_login_dados('email');
		$emailUsuario = $_SESSION['emailUsuario'];
		$dados['oportunidadeVendedor'] 	= ($this->cliente_crm->pegarOportunidade_vendedor($emailUsuario)) ? $this->cliente_crm->pegarOportunidade_vendedor($emailUsuario) : null ;

		$this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/Pedidos'));
		
        $dados['titulo'] = lang('pedidos');		
		$dados['tokenApiTelevendas'] = $this->auth->get_login_dados('tokenApiTelevendas');
		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('comercial_televenda/pedido/index');
        $this->load->view('fix/footer_NS');
    }
	public function getOportunidadeVendedor(){
		$emailUsuario = $_SESSION['emailUsuario'];

		echo json_encode($this->cliente_crm->pegarOportunidade_vendedor($emailUsuario) ? $this->cliente_crm->pegarOportunidade_vendedor($emailUsuario) : array());
	}
	public function getOportunidadeCliente(){
		if (isset($_SESSION['customerId'])) {
			$customerId = $_SESSION['customerId'];

			echo json_encode($this->cliente_crm->pegarOportunidade_cliente($customerId) ? $this->cliente_crm->pegarOportunidade_cliente($customerId) : array());
		}
		else{
			echo json_encode(array());
		}
	}
	
	public function getOportunidadeClienteKanban(){
		$documento = $this->input->post('documento');
		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');

		if ($this->cliente_crm->valida_cpf($documento) || $this->cliente_crm->valida_cnpj($documento) ) {
			$_SESSION['ClienteKanbanDoc'] = $documento;

			$retorno = $this->cliente_crm->pegarCliente_CRM($documento);

			if ($retorno->Status == 200) {			
				$customerId = $retorno->customers->customerId;

				if(!empty($dataInicial) && !empty($dataInicial)){
					$oportunidades = $this->cliente_crm->pegarOportunidade_cliente_data($customerId, $dataInicial, $dataFinal);
				}else{
					$oportunidades = $this->cliente_crm->pegarOportunidade_cliente($customerId);
				}
				echo json_encode( $oportunidades ? $oportunidades : array());
			} 
			else {				
				echo json_encode(array());			
			}
		} else {
			echo json_encode(array());
		}	
	}

	public function getVendedores(){
		$vendedores = $this->cliente_crm->pegarVendadores();

		if(!$vendedores){
			$vendedores = array();
		}



		echo json_encode($vendedores);
	}

	public function integrarClientePeloPedido(){
		$documento = $this->input->post('documento');
		$cotacao = $this->input->post('cotacao'); 

		$clienteERP = $this->resumoClienteCRM($documento);
		
		if($clienteERP['endereco_principal'] == null || $clienteERP['endereco_cobranca'] == null || $clienteERP['endereco_entrega'] == null ){
			echo json_encode(array('status' => 'erro', 'msg' => 'Cliente não possui todos os endereços cadastrados no ERP', 'dados' => array($documento, $cotacao)));
			return;
		}else if($clienteERP['ddd_telefone'] == null || $clienteERP['ddd_telefone2'] == null || $clienteERP['telefone'] == null || $clienteERP['telefone2'] == null ){
			echo json_encode(array('status' => 'erro', 'msg' => 'Cliente não possui todos os telefoness cadastrados no ERP', 'dados' => array($documento, $cotacao)));
			return;
		} else {

			$data = $this->DadosIntegrarClienteERP($clienteERP);
			$resultadoIntegracao = IntegrarClienteERP($data);
			
			if($resultadoIntegracao['Status'] == 200){
				
				$result = $this->gerarPedidoCRM($cotacao);
				echo json_encode(array('status' => '200', 'Message' => $result->Message));
			} else {
				echo json_encode(array('status' => 'erro', 'resultado' => $resultadoIntegracao));
			}
		}
	}


	public function integrarClienteERP($dados = false){
		$data = $this->DadosIntegrarClienteERP($dados);
		$resultado = IntegrarClienteERP($data);
		
		if ($resultado !== null) {
			echo json_encode($resultado);
		} else {
			$errorResponse = array("Status" => 400, "Message" => "Falha ao integrar com o CRM");
			echo json_encode($errorResponse);
		}
	}
	

	public function atualizarClienteERP($dados = false){
		//para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;
				
		$data = $this->DadosIntegrarClienteERP($dados);
		$dadosAntigos = $this->resumoClienteCRM($data['documento']);

		if (!$dadosAntigos) {
			$dadosAntigos = 'null';
		}
		
		$resultado = IntegrarClienteERP($data);
				
		if($resultado['Status'] === 200){
			$this->log_shownet->gravar_log($id_user, 'Atualizando cliente CRM', "documento:".$data['documento'], 'atualizar', $dadosAntigos, $data);
			$resultado['Message'] = "Cliente Atualizado com Sucesso";
		}else{
			$this->log_shownet->gravar_log($id_user, 'Erro ao atualizar cliente CRM', "documento:".$data['documento'], 'atualizar', $dadosAntigos, $data);
		}
		echo json_encode($resultado);
	}

	public function DadosIntegrarClienteERP($dados = false){
		if (!$dados){
			$data = $this->input->post();	
		} else {
			$data = $dados;	
		}

		$curl = curl_init();

		if (!$dados){
			$endereco_principal = array(
				'cep' => $data['cep_principal'] ? $data['cep_principal'] : null,
				'complemento' => $data['complemento_principal'] ? $data['complemento_principal'] : null,
				'rua' => $data['rua_principal'] ? $data['rua_principal'] : null,
				'bairro' => $data['bairro_principal'] ? $data['bairro_principal'] : null,
				'cidade' => $data['cidade_principal'] ? $data['cidade_principal'] : null,
				'estado' => $data['estado_principal'] ? $data['estado_principal'] : null,
			);
			$endereco_cobranca = array(
				'cep' => $data['cep_cobranca'] ? $data['cep_cobranca'] : null,
				'complemento' => $data['complemento_cobranca'] ? $data['complemento_cobranca'] : null,
				'rua' => $data['rua_cobranca'] ? $data['rua_cobranca'] : null,
				'bairro' => $data['bairro_cobranca'] ? $data['bairro_cobranca'] : null,
				'cidade' => $data['cidade_cobranca'] ? $data['cidade_cobranca'] : null,
				'estado' => $data['estado_cobranca'] ? $data['estado_cobranca'] : null,
			);
			$endereco_entrega = array(
				'cep' => $data['cep_entrega'] ? $data['cep_entrega'] : null,
				'complemento' => $data['complemento_entrega'] ? $data['complemento_entrega'] : null,
				'rua' => $data['rua_entrega'] ? $data['rua_entrega'] : null,
				'bairro' => $data['bairro_entrega'] ? $data['bairro_entrega'] : null,
				'cidade' => $data['cidade_entrega'] ? $data['cidade_entrega'] : null,
				'estado' => $data['estado_entrega'] ? $data['estado_entrega'] : null,
			);
		

			unset($data['cep_principal']);
			unset($data['cep_cobranca']);
			unset($data['cep_entrega']);
			unset($data['complemento_principal']);
			unset($data['complemento_cobranca']);
			unset($data['complemento_entrega']);

			$data['endereco_principal'] = $endereco_principal;
			$data['endereco_cobranca'] = $endereco_cobranca;
			$data['endereco_entrega'] = $endereco_entrega;
		}

		$data['dataNascimento'] = isset($data['dataNascimento']) ? date('d/m/Y', strtotime($data['dataNascimento'])) : null;

		return $data;
	}
	  
	public function getClienteCRM()
	{
		$documento = $this->input->post('documento');
				
		if ($this->cliente_crm->valida_cpf($documento) || $this->cliente_crm->valida_cnpj($documento) ) {
			$retorno = $this->cliente_crm->pegarCliente_CRM($documento);

			$documentoTratado = preg_replace('/[^0-9]/', '', $documento);
			$_SESSION['documentoTratado'] = $documentoTratado;
			$_SESSION['documento'] = $documento;
									
			if ($retorno->Status == 200) {				
				
				if ($retorno->customers->nome){
					$cliente =  $retorno->customers->nome;
				}
				else{
					if ($retorno->customers->nomeFantasia){
						$cliente =  $retorno->customers->nomeFantasia;
					} 					
				}

				$loginVendedor = json_encode($retorno->customers->loginVendedor);
				
				$_SESSION['customerId'] = $retorno->customers->customerId;

				$this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Cliente '.$cliente.' - documento: '.$documento.' já encontra-se cadastrado(a)</div>');

				redirect('ComerciaisTelevendas/Pedidos');



				
			} 
			else {				
				echo  "<script>alert('Cliente não cadastrado! Vamos lhe direcionar para a página de cadastro.');</script>";
				echo "<script> javascript:window.location='../ComerciaisTelevendas/Cadastro_Cliente';</script>";					
			}
		} else {
			$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Por favor, digite um documento válido.</div>');
			
			redirect('ComerciaisTelevendas/Pedidos');
		}	

	}

	public function ajax_getClienteCRM()
	{
		$documento = $this->input->post('documento');
		$retorno = $this->cliente_crm->pegarCliente_CRM($documento);

		$documentoTratado = preg_replace('/[^0-9]/', '', $documento);
		$_SESSION['documentoTratado'] = $documentoTratado;
		$_SESSION['documento'] = $documento;
								
		if ($retorno->Status == 200) {			
			echo json_encode(
				array(
					'status' => 200,
					'clienteCadastrado' => true,
					'dados'  => $retorno
				)
			);
		} 
		else {					
			echo json_encode(
				array(
					'status' => 200,
					'clienteCadastrado' => false
				)
			);			
		}
	}

	public function cadastrarCliente()
	{
		$documento = $this->input->post('documento');

		if ($this->cliente_crm->valida_cpf($documento) || $this->cliente_crm->valida_cnpj($documento) ) {
			$documentoTratado = preg_replace('/[^0-9]/', '', $documento);
			$_SESSION['documentoTratado'] = $documentoTratado;
			$_SESSION['documento'] = $documento;

			echo true;		
		}else{
			echo false;		
		}
	}


	public function resumoClienteCRM($param = false){
		if (!$param){
			$documento = $this->input->post('documento');
			if(!$documento){
				echo json_encode(
					array(
						'status' => "404",
						'dados'  => "Erro ao adicionar documento do cliente"
					)
				);
			}
		} else {
			$documento = $param;
		}
		
		if (!isset($documento) && empty($documento)) {
			echo json_encode(
				array(
					'status' => 400,
					'dados'  => 'Documento não informado'
				)
			);
			return;
		}

		$CI =& get_instance();

		$request = $CI->config->item('base_url_api_crmintegration').'/crmintegration/api/customers/ResumoClienteERP?Token=84DBDF86-D5D2-43E1-94E3-DA8FF570A767&parametro='.$documento;
		
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
		));

		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($statusCode == 200 && $resultado['Message']['dataNascimento'] != null){
			$data = $resultado['Message']['dataNascimento'];
			$data = explode('/', $data);
			$resultado['Message']['dataNascimento'] = $data[2].'-'.$data[1].'-'.$data[0];
		}

        curl_close($curl);

		if($param){
			return $resultado['Message'];
		}

        echo json_encode(
            array(
                'status' => $statusCode,
                'dados'  => $resultado
            )
        );

}

	public function pegarURLConfigurometro()
	{					
		$idVeiculo = $this->input->post('idVeiculo');

		$retorno = $this->cliente_crm->get_configurometroCRM($idVeiculo);		
		if ($retorno) {
			echo $retorno;
		}
		else{
			return false;
		}			
	}

	public function addClienteCRM()
	{
		$this->load->helper('util_helper');

		$dados['nome'] 			 = $this->input->post('nome');
		$dados['sobrenome'] 	 = $this->input->post('sobrenome');
		$dados['nomeFantasia'] 	 = $this->input->post('nomeFantasia');
		$cpf 			 		 = $this->input->post('cpf');
		$cnpj 		 			 = $this->input->post('cnpj');
		$dados['rg'] 			 = $this->input->post('rg');
		$dataNascimento			 = $this->input->post('dataNascimento');
		$dados['ddd'] 			 = $this->input->post('ddd');
		$dados['telefone'] 		 = $this->input->post('telefone');
		$dados['email'] 		 = $this->input->post('email');
		$dados['endereco'] 		 = $this->input->post('endereco');
		$dados['loginVendedor']  = $this->input->post('loginVendedor');
		$dados['cep']			 = $this->input->post('cep');
		$dados['rua']			 = $this->input->post('rua');
		$dados['bairro']		 = $this->input->post('bairro');
		$dados['cidade']		 = $this->input->post('cidade');
		$dados['estado']		 = $this->input->post('estado');
		$dados['complemento']	 = $this->input->post('complemento');
		$tipoCliente	 		 = $this->input->post('tipoCliente');

		//tratamento dos dados conforme o crm
		$dados['cnpjTratado'] = preg_replace('/[^0-9]/', '', $cnpj);
		$dados['cpfTratado']  = preg_replace('/[^0-9]/', '', $cpf);
		
		$diaNasc = str_replace("-", "/", $dataNascimento);
    	$dados['dataNascimento'] = date('d/m/Y', strtotime($diaNasc));

		if ($tipoCliente == 1) {
			$dados['tipoCliente'] = "PF";
		}
		else{
			$dados['tipoCliente'] = "PJ";
		}
		
		$inserir_cliente_crm = $this->cliente_crm->adicionarCliente_CRM($dados);		

		if($inserir_cliente_crm->Status == 200 ){ 
			if ($dados['nome']) {
				$cliente = $dados['nome'];
			}
			else {				
				$cliente = $dados['nomeFantasia'];				
			}

			//para registro de log
			$id_user = $this->auth->get_login_dados('user');
			$id_user = (int) $id_user;
		
			$documento = $cpf;

			if(!isset($documento)){
				$documento = $cnpj;
			}

			$this->log_shownet->gravar_log($id_user, 'adicionando cliente CRM', $documento, 'criar', 'null', $dados);

			foreach ($_FILES as $key => $file) {	
					
				$file_name =$file['name'];
				$file_ext = strtolower(end(explode('.',$file_name)));

				$file_iniNome = strtolower(explode('.',$file_name)[0]);
					
				$file_tmp= $file['tmp_name'];
				$file_type= $file['type'];
					
				$data = file_get_contents( $file_tmp );
				$base64 = base64_encode($data);

				$body = array(
					'Documento' => $documento,
					'customerid' => "",
					'Assunto' => $file_iniNome,
					'Descricao' => "Anexo na criação do cliente",
					'NomeArquivo' => $file_iniNome,
					'Mimetype' => $file_type,
					'Extensao' => $file_ext,
					'DocumentBase64' => $base64
				);
				
				$retorno = json_decode(to_enviarAnexoCliente($body));    
				
				
			} 								
			$_SESSION['active'] = 'oportunidades';			
			$_SESSION['origem'] = 'cadastroCliente';
			echo json_encode(array("status" => '200', "msg" => '<div class="alert alert-success" role="alert">Cliente '.$cliente.' cadastrado com sucesso! </div>'));	
		}
		else{
			$msg = "";
			if ($inserir_cliente_crm->Status == 400) {
				// $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao cadastrar: '.$inserir_cliente_crm->Message.' </div>');			
				$msg = '<div class="alert alert-danger" role="alert">Falha ao cadastrar: '.$inserir_cliente_crm->Message.' </div>';
			}
			else{
				// $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao cadastrar cliente!</div>');				
				$msg = '<div class="alert alert-danger" role="alert">Falha ao cadastrar cliente!</div>';
				
			}
			echo json_encode(array("status" => '400', "msg" => $msg));		
		} 		
	}
	
	// public function integrarClienteERP($dados = false){
	// 	$data = $this->DadosIntegrarClienteERP($dados);
	// 	$resultado = IntegrarClienteERP($data);
	// 	echo json_encode($resultado);
	// }
	public function addClienteModal()
	{
		$this->load->helper('util_helper');

		$dados['nome'] 			 = $this->input->post('name');
		$dados['sobrenome'] 	 = $this->input->post('sobrenome') ? $this->input->post('sobrenome') : null;
		$dados['nomeFantasia'] 	 = $this->input->post('nome_fantasia');
		$documento 			 	 = $this->input->post('documento');
		$dataNascimento			 = $this->input->post('dataNascimento');
		$dados['ddd'] 			 = $this->input->post('ddd_telefone');
		$dados['telefone'] 		 = $this->input->post('telefone');
		$dados['email'] 		 = $this->input->post('email');
		$dados['endereco'] 		 = $this->input->post('endereco');
		$dados['loginVendedor']  = $this->input->post('loginVendedor');
		$dados['cep']			 = $this->input->post('cep_principal');
		$dados['rua']			 = $this->input->post('rua_principal');
		$dados['bairro']		 = $this->input->post('bairro_principal');
		$dados['cidade']		 = $this->input->post('cidade_principal');
		$dados['estado']		 = $this->input->post('estado_principal');
		$dados['complemento']	 = $this->input->post('complemento_principal');
		$dados['rg'] = null;		
		$dados['dataNascimento'] = null;
		
		if($dataNascimento){
			$diaNasc = str_replace("-", "/", $dataNascimento);
			$dados['dataNascimento'] = date('d/m/Y', strtotime($diaNasc));
		}
		
		$dados['cnpjTratado'] = null;
		$dados['cpfTratado']  = null;

		if(strlen($documento) > 14){
			$dados['tipoCliente'] = "PJ";
			$dados['cnpjTratado'] = preg_replace('/[^0-9]/', '', $documento);
		}else{
			$dados['tipoCliente'] = "PF";
			$dados['cpfTratado']  = preg_replace('/[^0-9]/', '', $documento);
		}
		
		$inserir_cliente_crm = $this->cliente_crm->adicionarCliente_CRM($dados);		

		if($inserir_cliente_crm->Status == 200 ){ 
			if ($dados['nome']) {
				$cliente = $dados['nome'];
			}
			else {				
				$cliente = $dados['nomeFantasia'];				
			}

			//para registro de log
			$id_user = $this->auth->get_login_dados('user');
			$id_user = (int) $id_user;

			try {
				$this->log_shownet->gravar_log($id_user, 'adicionando cliente CRM', $documento, 'criar', 'null', $dados);
			} catch (Exception $e) {
			}	


			foreach ($_FILES as $key => $file) {	
					
				$file_name =$file['name'];
				$file_ext = strtolower(end(explode('.',$file_name)));

				$file_iniNome = strtolower(explode('.',$file_name)[0]);
					
				$file_tmp= $file['tmp_name'];
				$file_type= $file['type'];
					
				$data = file_get_contents( $file_tmp );
				$base64 = base64_encode($data);

				$body = array(
					'Documento' => $documento,
					'customerid' => "",
					'Assunto' => $file_iniNome,
					'Descricao' => "Anexo na criação do cliente",
					'NomeArquivo' => $file_iniNome,
					'Mimetype' => $file_type,
					'Extensao' => $file_ext,
					'DocumentBase64' => $base64
				);
				
				$retorno = json_decode(to_enviarAnexoCliente($body));    
			} 								
			$_SESSION['active'] = 'oportunidades';			
			$_SESSION['origem'] = 'cadastroCliente';
			echo json_encode(array("status" => '200', "msg" => 'Cliente '.$cliente.' cadastrado com sucesso!'));	
		}
		else{
			$msg = "";
			if ($inserir_cliente_crm->Status == 400) {
				// $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao cadastrar: '.$inserir_cliente_crm->Message.' </div>');			
				$msg = 'Falha ao cadastrar: '.$inserir_cliente_crm->Message;
			}
			else{
				// $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao cadastrar cliente!</div>');				
				$msg = 'Falha ao cadastrar cliente!';
				
			}
			echo json_encode(array("status" => '400', "msg" => $msg));		
		} 		
	}

	public function setaActive(){

		$_SESSION['active'] = $this->input->get('value'); 
	}

	public function pegar_cenarioVendaCRM()
	{					
		$retorno = $this->cliente_crm->pegar_cenarioVenda();		
		if ($retorno) {
			echo $retorno;
		}
		else{
			return false;
		}			
	}

	public function pegar_tipoPagamentoCRM()
	{				
		$retorno = $this->cliente_crm->pegar_tipoPagamento();		
		if ($retorno) {
			echo $retorno;
		}
		else{
			return false;
		}	
	}

	public function pegar_condicaoPagamentoCRM()
	{
		$value = $this->input->get('value');				
		$retorno = $this->cliente_crm->pegar_condicaoPagamento($value);
		$jsonreturn = json_decode($retorno);		

		foreach ($jsonreturn as $datavalue) {			
			echo '<option value='.$datavalue->tz_condicao_pagamentoid.'>'.$datavalue->tz_name.'</option>';
		 } 			
	}

	public function pegar_tipoVeiculoCRM()
	{				
		$retorno = $this->cliente_crm->pegar_tipoVeiculo();
		if ($retorno) {
			echo $retorno;
		}
		else{
			return false;
		}
	}

	public function pegar_idProdutoCRM()
	{			
		$retorno = $this->cliente_crm->pegar_idProduto();		
		if ($retorno) {			
			echo $retorno;			
		}
		else{
			return false;
		}	
	}

	public function pegar_planoSatelitalCRM()	{	
		$value = $this->input->get('value');		

		$retorno = $this->cliente_crm->pegar_planoSatelital($value);
		$jsonreturn = json_decode($retorno);

		if ($jsonreturn) {
			/* foreach ($jsonreturn as $datavalue) {			
				echo '<option value='.$datavalue->tz_plano_satelitalid.'>'.$datavalue->tz_name.'</option>';
			} */
			echo $retorno;
		}
		else{
			return false;
		}	
	}

	public function pegar_armazensCRM() {
    $this->load->helper('util_helper');
    $retorno = json_decode(get_armazens());

    $armazens = array();

    foreach ($retorno->value as $armazem) {
        $armazens[] = array(
            'tz_armazemid' => $armazem->tz_armazemid,
            'tz_name' => $armazem->tz_name
        );
    }

    echo json_encode($armazens);
	}

	public function pegarOportunidade_vendedorCRM()
	{			
		$emailUsuario = $this->input->post('emailUsuario');
		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');

		if(!empty($emailUsuario)){
			unset($_SESSION['ClienteKanbanDoc']);
		}

		if (empty($emailUsuario)) {
			$emailUsuario = $_SESSION['emailUsuario']; 
			unset($_SESSION['documento']);
		}

		if(!empty($dataInicial) && !empty($dataFinal)){
			$retorno = $this->cliente_crm->pegarOportunidade_vendedor_data($emailUsuario, $dataInicial,  $dataFinal);	
		}else{
			$retorno = $this->cliente_crm->pegarOportunidade_vendedor($emailUsuario);  
		}		

		if ($retorno) {			
			echo json_encode($retorno);
		}
		else{
			return false;
		}	
	}

	public function pegarOportunidade_vendedor_dataCRM()
	{			
		$emailUsuario = $this->input->post('emailUsuario');
		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		
		$retorno = $this->cliente_crm->pegarOportunidade_vendedor_data($emailUsuario, $dataInicial,  $dataFinal);		

		if ($retorno) {			
			echo json_encode($retorno);
		}
		else{
			return false;
		}	
	}

	
	public function pegarOportunidade_cliente_dataCRM()
	{			
		$customerId = "";

		if (isset($_SESSION['customerId'])) {
			$customerId = $_SESSION['customerId'];  
			$dataInicial = $this->input->post('dataInicial');
			$dataFinal = $this->input->post('dataFinal');
			
			$retorno = $this->cliente_crm->pegarOportunidade_cliente_data($customerId, $dataInicial,  $dataFinal);		

			if ($retorno) {			
				echo json_encode($retorno);
			}
			else{
				return false;
			}	
		} else{
			return false;
		}	
		
	}

	public function pegarOportunidade_clienteCRM()
	{		
		$documento = $this->input->post('documento');
		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');

		if(!empty($documento)){
			$customerId = $this->buscarCustomerId($documento);
			$_SESSION['customerId'] = $customerId;  

			$documentoTratado = preg_replace('/[^0-9]/', '', $documento);
			$_SESSION['documentoTratado'] = $documentoTratado;
			$_SESSION['documento'] = $documento;

			if(!empty($dataInicial) && !empty($dataFinal)){
				$retorno = $this->cliente_crm->pegarOportunidade_cliente_data($customerId, $dataInicial,  $dataFinal);	
			}else{
				$retorno = $this->cliente_crm->pegarOportunidade_cliente($customerId);  
			}

			if ($retorno) {			
				echo json_encode($retorno);
			}
			else{
				return false;
			}

		} else if ((isset($_SESSION['documento']))) {
			$documento = $_SESSION['documento'];
			$customerId = $this->buscarCustomerId($documento);
			$_SESSION['customerId'] = $customerId;  

			$documentoTratado = preg_replace('/[^0-9]/', '', $documento);
			$_SESSION['documentoTratado'] = $documentoTratado;
			$_SESSION['documento'] = $documento;
			
			$retorno = $this->cliente_crm->pegarOportunidade_cliente($customerId);

			if ($retorno) {			
				echo json_encode($retorno);
			}
			else{
				return false;
			}
			
		} else if (isset($_SESSION['customerId'])) {
			$customerId = $_SESSION['customerId'];  
			$retorno = $this->cliente_crm->pegarOportunidade_cliente($customerId);  
			if ($retorno) {			
				echo json_encode($retorno);
			}
			else{
				return false;
			}
		} else{
			return false;
		}		
	}

	public function buscarCustomerId($documento)
	{				
		if ($this->cliente_crm->valida_cpf($documento) || $this->cliente_crm->valida_cnpj($documento) ) {
			$retorno = $this->cliente_crm->pegarCliente_CRM($documento);
			if ($retorno->Status == 200) {			
				$customerId = $retorno->customers->customerId;
				return $customerId;
			} 
		} 
		return null;
	}

	public function createOportunidade()
	{		
		$dadoscreate['documentoCliente'] 		= $this->input->post('documentoCliente');
		$dadoscreate['userNameVendedor']		= $this->input->post('userNameVendedor');
		$dadoscreate['qtdVeiculos'] 			= $this->input->post('qtdVeiculos');
		$dadoscreate['tempoContrato'] 			= $this->input->post('tempoContrato');
		$dadoscreate['ID_Produto'] 				= $this->input->post('modalidadeVenda') == "8" ? '' : $this->input->post('ID_Produto');
		$dadoscreate['cenario_venda'] 			= $this->input->post('cenario_venda');
		$dadoscreate['tipo_pagamento'] 			= $this->input->post('tipo_pagamento');
		$dadoscreate['condicao_pagamento']		= $this->input->post('condicao_pagamento');
		$dadoscreate['tipoVeiculo']				= $this->input->post('tipoVeiculo');
		$dadoscreate['planoSatelital']			= $this->input->post('modalidadeVenda') == "8" ||  $this->input->post('modalidadeVenda') == "9" ? '' : $this->input->post('planoSatelital');
		$dadoscreate['origem']					= $this->input->post('origem');
		$dadoscreate['contratoNovo']			= $this->input->post('modalidadeVenda') == '1' || $this->input->post('modalidadeVenda') == '9' ? true : false;
		$dadoscreate['modalidadeVenda']			= $this->input->post('modalidadeVenda');
		$dadoscreate['clientRetiraArmazem']		= $this->input->post('clientRetiraArmazem');

		if ($this->input->post('clientRetiraArmazem') == '1') { //se cliente retira no armazem
			$dadoscreate['armazem'] 				= $this->input->post('armazem');
			$dadoscreate['responsavelRetirada'] 	= $this->input->post('responsavelRetirada');
			$dadoscreate['cpfResponsavelRetirada'] 	= $this->input->post('cpfResponsavelRetirada');
		}

			  
		if(!$this->validar_documento($dadoscreate['documentoCliente'])){
			$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Documento Inválido!</div>');				
			redirect('ComerciaisTelevendas/Pedidos');
			return;
		}
		
		if (strlen($dadoscreate['documentoCliente'])>14) {
			$dadoscreate['tipoCliente'] = "PJ";
		}
		else {
			$dadoscreate['tipoCliente'] = "PF";
		}

		$retorno = $this->cliente_crm->pegarCliente_CRM($dadoscreate['documentoCliente']);
		
		if ($retorno->Status == 200) {	
			$inserir_oportunidade_crm = $this->cliente_crm->adicionarOportunidade_CRM($dadoscreate);
			

			$_SESSION['active'] = 'oportunidades';

			if($inserir_oportunidade_crm->Status == 200 ){
			
				$user = $this->auth->get_login_dados('email');
				$idUsuario = '';
				foreach ($this->usuario->get("login ='$user'") as $key => $value) {
					if ($key == 'id'):
						$idUsuario = $value;
					endif;
				}

				$idCotacao = $inserir_oportunidade_crm->idCotacao;
				$dataAtual = new DateTime();
				$dataAtual = $dataAtual->format('Y-m-d H:i:s');

				$this->cliente_crm->salvarCotacao($idUsuario, $idCotacao, $dataAtual);
				//para registro de log
				$id_user = $this->auth->get_login_dados('user');
				$id_user = (int) $id_user;
				$this->log_shownet->gravar_log($id_user, 'Criando oportunidade CRM', $idCotacao, 'criar', 'null', $dadoscreate);
				
				$this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Oportunidade criada com Sucesso! </div>');			
				redirect('ComerciaisTelevendas/Pedidos');
			}
			else{
				if ($inserir_oportunidade_crm->Status == 400) {
					$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao cadastrar: '.$inserir_oportunidade_crm->Message.' </div>');				
					redirect('ComerciaisTelevendas/Pedidos');
				}
				else{
					$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao cadastrar oportunidade.</div>');				
					redirect('ComerciaisTelevendas/Pedidos');
				}
				
			} 
		}
		else{
			$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Cliente ainda não cadastrado. Favor cadastrar cliente antes de adicionar oportunidade.</div>');				
			redirect('ComerciaisTelevendas/Pedidos');
			
		}	

	}

	public function EditarOportunidade()
	{		
		$dadosedit['cotacaoId'] 								  = $this->input->post('idCotacao');
		$dadosedit['qtdVeiculos'] 								  = $this->input->post('qtdVeiculos');
		$dadosedit['tempoContrato'] 							  = $this->input->post('tempoContrato');
		$dadosedit['ID_Produto'] 								  = $this->input->post('modalidadeVenda') == "8" ? '' : $this->input->post('ID_Produto');
		$dadosedit['cenario_venda'] 							  = $this->input->post('cenario_venda');
		$dadosedit['tipo_pagamento'] 							  = $this->input->post('tipo_pagamento');
		$dadosedit['condicao_pagamento']						  = $this->input->post('condicao_pagamento');
		$dadosedit['tipoVeiculo']								  = $this->input->post('tipoVeiculo');
		$dadosedit['planoSatelital']							  = $this->input->post('modalidadeVenda') == "8" ? '' : $this->input->post('planoSatelital');
		$dadosedit['inicioVigencia']							  = $this->input->post('inicioVigencia');
		$dadosedit['terminoVigencia']							  = $this->input->post('terminoVigencia');
		$dadosedit['modalidadeVenda']							  = $this->input->post('modalidadeVenda');
		$dadosedit['userNameVendedor']							  = $this->input->post('userNameVendedor');
		$dadosedit['signatario']['signatario_software'] 		  = $this->input->post('nomeClienteSoftwareOportunidade');
		$dadosedit['signatario']['email_signatario_software'] 	  = $this->input->post('emailClienteSoftwareOportunidade');
		$dadosedit['signatario']['documento_signatario_software'] = $this->input->post('documentoClienteSoftwareOportunidade');
		$dadosedit['signatario']['signatario_hardware'] 		  = $this->input->post('nomeClienteHardwareOportunidade');
		$dadosedit['signatario']['email_signatario_hardware'] 	  = $this->input->post('emailClienteHardwareOportunidade');
		$dadosedit['signatario']['documento_signatario_hardware'] = $this->input->post('documentoClienteHardwareOportunidade');
		$dadosedit['clientRetiraArmazem'] 					  	  = $this->input->post('clientRetiraArmazem');
		$dadosedit['armazem'] 					 				  = $this->input->post('clientRetiraArmazem') == '1' ? $this->input->post('armazem') : '';
		$dadosedit['responsavelRetirada'] 						  = $this->input->post('clientRetiraArmazem') == '1' ? $this->input->post('responsavelRetirada') : '';
		$dadosedit['cpfResponsavelRetirada'] 	 				  = $this->input->post('clientRetiraArmazem') == '1' ? $this->input->post('cpfResponsavelRetirada') : '';

		$editarOportunidade_CRM = $this->cliente_crm->editarOportunidade_CRM($dadosedit);

		$_SESSION['active'] = 'oportunidades';

		if($editarOportunidade_CRM->Status == 200 ){
			//para registro de log
			$id_user = $this->auth->get_login_dados('user');
			$id_user = (int) $id_user;
			$this->log_shownet->gravar_log($id_user, 'Atualizando oportunidade CRM', $dadosedit['cotacaoId'] , 'atualizar', 'null', $dadosedit);
			
			$this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Oportunidade atualizada com Sucesso! </div>');			
			redirect('ComerciaisTelevendas/Pedidos');
		}
		else{
			if ($editarOportunidade_CRM->Status == 400) {
				$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao Atualizar: '.$editarOportunidade_CRM->Message.' </div>');				
				redirect('ComerciaisTelevendas/Pedidos');
			}
			else{
				$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Falha ao Atualizar oportunidade.</div>');				
				redirect('ComerciaisTelevendas/Pedidos');
			}
			
		} 

	}

	public function gerarPedidoCRM($cotacao = false) 
	{
		// verifica se o usuário está logado na api shownet - televendas
		$this->auth->is_logged_api_shownet();

		if(!$cotacao){
			$idCotacao = $this->input->post('idCotacao');			
		} else {
			$idCotacao = $cotacao;
		}

		$loginUsuario = $this->auth->get_login_dados('email');

		$retorno = $this->cliente_crm->gerarPedido($idCotacao, $loginUsuario);
					
		//para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;

		$this->log_shownet->gravar_log($id_user, 'Gerar Pedido CRM', $idCotacao, 'criar', 'null', 'Pedido gerado');
		
		if ($retorno) {		
			return $retorno;			
		} 
		else {
			return false;
		} 
	}

	public function AjaxGerarPedidoCRM($cotacao = false)
	{
		$retorno = $this->gerarPedidoCRM($cotacao);
		
		if ($retorno) {
			echo json_encode($retorno);
		} 
		else {
			echo json_encode(false);
		} 
	}


	public function gerarPedidoAjax($cotacao = false)
	{
		if(!$cotacao){
			$idCotacao = $this->input->post('idCotacao');			
		} else {
			$idCotacao = $cotacao;
		}

		$loginUsuario = $this->auth->get_login_dados('email');
		$retorno = $this->cliente_crm->gerarPedido($idCotacao, $loginUsuario);
					
		//para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;

		$this->log_shownet->gravar_log($id_user, 'Gerar Pedido CRM', $idCotacao, 'criar', 'null', 'Pedido gerado');
		
		if ($retorno) {		
			echo json_encode($retorno);
		} 
		else {
			echo json_encode(false);
		} 
	}

	public function enviarAssinaturaCRM()
	{
		//para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;
		$loginUsuario = $this->auth->get_login_dados('email');

		$idCotacao = $this->input->post('idCotacao');			
		$retorno = $this->cliente_crm->enviarAssinatura($idCotacao, $loginUsuario);

		$dados = json_decode($retorno);	
		if ($dados) {
			$this->log_shownet->gravar_log($id_user, 'CRM', $idCotacao, 'acao', '', 'enviar para assinatura');
			return $dados->Message;			
		} 
		else {
			return false;
		} 
		
	}

	public function ganharCRM()
	{
		$idCotacao = $this->input->post('idCotacao');			
		$retorno = $this->cliente_crm->ganhar($idCotacao);
		$dados = json_decode($retorno);	
		
		if ($dados) {
					
			return $dados->Message;			
		} 
		else {
			return false;
		} 
	}

	public function solicitarStatus()
	{
		$idCotacao = $this->input->post('idCotacao');			
		$retorno = $this->cliente_crm->status($idCotacao);
		if ($retorno) {
					
			return $retorno;			
		} 
		else {
			return false;
		} 
	}
	
	

	public function buscarHistoricoAf(){

		$this->load->model('painel_omnilink','painelOmnilink');
		$this->load->helper('sac_crm_helper');
        $this->load->model('auth');
        $this->sac = new SacCrmHelper();
        $this->timeZone = new DateTimeZone('America/Recife');

        $resposta = $this->input->post('idAF');
		
		$idAF = str_replace('"', '', $resposta);
		
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
                 <entity name="tz_status_af">
                   <attribute name="tz_observacoes" />
                   <attribute name="tz_data_evento" />
                   <filter>
                     <condition attribute="tz_afid" operator="eq" value="{idAf}" />
                   </filter>
                </entity>
                </fetch>';
        //faz a substituição dos caracteres no XML
        $xml = str_replace('{idAf}', $idAF, $xml);
        //faz a requisição
		$requisicaoAf = $this->sac->buscar('tz_status_afs', http_build_query(['fetchXml' => $xml]));					
				
        if($requisicaoAf['code'] === 200){
            $dadosAf = $requisicaoAf['value'];
            //preenche o array com os dados necessários
            foreach($dadosAf as $af){
                $retornoAf[] = array(
                    'observacoes'      => $af['tz_observacoes'] ? $af['tz_observacoes'] : "-",
                    'dataEvento'        => $af['tz_data_evento'] ? date_format(date_create($af['tz_data_evento'])->setTimezone($this->timeZone), 'd/m/Y H:i:s') : "-"
                );
				
            }
			// Adiciona um log para depuração
			$response = array(
				'status' => 200,
				'data' => $retornoAf
			);			
			echo json_encode($response);

        }else{
            $response = array(
				'status' => 200,
				'data' => $requisicaoAf
			);
			echo json_encode($response);
        }
    }

	public function resumoCotacao(){
		$this->load->helper('util_helper');
		
		$idCotacao = $this->input->post('idCotacao');
		$retorno = json_decode(getResumoCotacao($idCotacao));

		if (!empty($retorno)){
			echo json_encode($retorno);
			return true;
		}else{
			return false;
		}

	}

	public function produtosComposicao(){
		$this->load->helper('util_helper');
		
		$nomeProduto = $this->input->post('nomeProduto');
		$numeroProduto = $this->input->post('numeroProduto');

		$retorno = (listarProdutosComposicao($nomeProduto, $numeroProduto));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}

	}

	public function getDocumentosCotacao(){
		$idCotacao = $this->input->post('idCotacao');

		$documentos = to_getDocumentosCotacao($idCotacao);

		if($documentos){
			echo json_encode(array('status' => $documentos['status'], 'data' => $documentos['data']));
			return;
		} else {
			return false;
		}
	}

	
	public function getObservacaoCotacao(){
		$idCotacao = $this->input->post('idCotacao');

		$observacoes = to_getObservacaoCotacao($idCotacao);

		if($observacoes){
			echo json_encode(array('status' => $observacoes['status'], 'data' => $observacoes['data']));
			return;
		} else {
			return false;
		}
	}

	public function cadastrarObservacao(){
		$this->load->helper('util_helper');
	
		$idCotacao = $this->input->post('cotacao_id');
		$Assunto = $this->input->post('assunto');
		$Descricao = $this->input->post('observacao');
		$userNameVendedor = $this->auth->get_login_dados('email');

		$body = array(
			"idCotacao" => $idCotacao,
			"userNameVendedor" => $userNameVendedor,
			"Assunto" => $Assunto,
			"Descricao" => $Descricao,
		);
					
		$retorno = to_cadastrarObservacaoCotacao($body);
			
		echo json_encode($retorno) ;
	}

	public function addSubItemC(){
		$this->load->helper('util_helper');
	
		$idCotacao = $this->input->post('idCotacao');
		$idProduto = $this->input->post('idProduto');
		$tipo = $this->input->post('tipo');
		$loginUsuario = $this->auth->get_login_dados('email');
		$quantidade = $this->input->post('quantidade');
					
		$retorno = addSubitemComposicao($idCotacao, $idProduto, $tipo, $loginUsuario, $quantidade);
			
		if (!empty($retorno)){			
			return $retorno;
		}else{
			return false;
		}
	}

	public function buscarNumeroAf(){
		$this->load->model('painel_omnilink','painelOmnilink');
		$this->load->helper('sac_crm_helper');
        $this->load->model('auth');
        $this->sac = new SacCrmHelper();
        $this->timeZone = new DateTimeZone('America/Recife');

        $resposta = $this->input->post('idAF');
		
		$idAF = str_replace('"', '', $resposta);
		
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
					<entity name="tz_af">
					<attribute name="tz_numero_af" />
					<filter>
						<condition attribute="tz_afid" operator="eq" value="{idAf}" />
					</filter>
					</entity>
				</fetch>';

        //faz a substituição dos caracteres no XML
        $xml = str_replace('{idAf}', $idAF, $xml);
        //faz a requisição
		$requisicaoAf = $this->sac->buscar('tz_afs', http_build_query(['fetchXml' => $xml]));				
				
        if($requisicaoAf['code'] === 200){
            $dadosAf = $requisicaoAf['value'];
			
            //preenche o array com os dados necessários
            foreach($dadosAf as $af){
				
                $retornoAf= $af['tz_numero_af'] ? $af['tz_numero_af'] : "-";
				
            }
			// Adiciona um log para depuração
			$response = array(
				'status' => 200,
				'data' => $retornoAf
			);			
			echo json_encode($response);

        }else{
            $response = array(
				'status' => 200,
				'data' => $requisicaoAf
			);
			echo json_encode($response);
        }
	}
	
	public function addArquivoClienteCRM()
	{
		$this->load->helper('util_helper');

		$customerid = $this->input->post('customerid');
		$documento = $this->input->post('documento');

		$file = $_FILES['Arquivo'];

		$file_name =$file['name'];
		$file_ext = strtolower(end(explode('.',$file_name)));

		$file_iniNome = strtolower(explode('.',$file_name)[0]);
		
		$file_tmp= $file['tmp_name'];
		$file_type= $file['type'];
		
		$data = file_get_contents( $file_tmp );
		$base64 = base64_encode($data);
		$body = array(
			'customerid' => $customerid,
			'Documento' => $documento,
			'Assunto' => $file_iniNome,
			'Descricao' => "Anexo do cliente",
			'NomeArquivo' => $file_iniNome,
			'Mimetype' => $file_type,
			'Extensao' => $file_ext,
			'DocumentBase64' => $base64
		);
		
		
		$retorno = to_enviarAnexoCliente($body);

		echo $retorno;
	}
	public function addArquivoCotacaoCRM()
	{
		$this->load->helper('util_helper');

		$cotacaoid = $this->input->post('cotacao_id');
		$assunto = $this->input->post('assunto');
		$loginUsuario = $this->auth->get_login_dados('email');

		$body = [
			'idCotacao' => $cotacaoid,
			'Assunto' => $assunto,
            'Descricao' => "Anexo da cotação"
        ];

        if (!empty($_FILES['Arquivo']) && !empty($_FILES['Arquivo']['name'])) {
            $file = $_FILES['Arquivo'];
            $file_name = $file['name'];
            $file_ext = strtolower(end(explode('.',$file_name)));
            $file_iniNome = strtolower(explode('.',$file_name)[0]);
            $file_tmp= $file['tmp_name'];
            $file_type= $file['type'];
            
            $data = file_get_contents( $file_tmp );
            $base64 = base64_encode($data);
            
            
            $body['NomeArquivo'] = $file_iniNome;
			$body['mimetype'] = $file_type;
			$body['Extensao'] = $file_ext;
            $body['DocumentBase64'] = $base64;
        }
		
		$retorno = to_enviarAnexoCotacao($body, $loginUsuario);

		echo $retorno;
	}
	
	public function solicitarArquivos(){
		$this->load->helper('util_helper');

		$documento = $this->input->get('documento');

		$retorno = get_anexosCustomer($documento);
		echo $retorno;
	}

	public function atualizaCartaoCredito(){
		$this->load->helper('util_helper');

		$Bandeira = $this->input->post('Bandeira');
		$Numero_Cartao = $this->input->post('Numero_Cartao');
		$Cod_Seguranca = $this->input->post('Cod_Seguranca');
		$Nome_Impresso_Cartao = $this->input->post('Nome_Impresso_Cartao');
		$Validade_Cartao_Mes = $this->input->post('Validade_Cartao_Mes');
		$Validade_Cartao_Ano = $this->input->post('Validade_Cartao_Ano');
		$id_Cotacao = $this->input->post('id_Cotacao');
		$loginUsuario = $this->auth->get_login_dados('email');


		$body = array(
			'Bandeira' => $Bandeira,
			'Numero_Cartao' => $Numero_Cartao,
			'Cod_Seguranca' => $Cod_Seguranca,
			'Nome_Impresso_Cartao' => $Nome_Impresso_Cartao,
			'Validade_Cartao_Mes' => $Validade_Cartao_Mes,
			'Validade_Cartao_Ano' => $Validade_Cartao_Ano,
			'id_Cotacao' => $id_Cotacao
		);

		$retorno = atualizaCartaoDeCredito($body, $loginUsuario);

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}
	public function getVeiculoCotId(){
		$this->load->helper('util_helper');

		$idCotacao = $this->input->post('idCotacao');

		$retorno = (get_tipoVeiculoCotacaoId($idCotacao));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}


	}

	public function validarCartaoCredito() {

		$numero = $this->input->post('numero');
		// Remova espaços e traços do número do cartão 
		$numero = str_replace(array(' ', '-'), '', $numero); 
   
		// Verifique se o número é composto apenas por dígitos e tem pelo menos 13 dígitos 
		if (!ctype_digit($numero) || strlen($numero) < 13) { 
		   return false; 
	   } 
		// Execute o algoritmo de Luhn para validar o número do cartão 
		$soma = 0; 
		$comprimento = strlen($numero);
   
		for ($i = 0; $i < $comprimento; $i++) {
			$digito = (int) $numero[$comprimento - $i - 1]; 
			   if ($i % 2 == 1) {
					$digito *= 2; 
					if ($digito > 9) {
						$digito -= 9; 
				   } 
			   } 
			   $soma += $digito; 
	   }

	   	if ($soma % 10 == 0) {
			$result = 0;
		} else {
			$result = 1;
		}

		echo $result;
	
	}

	public function addDescontoCotacao(){
		$idItem = $this->input->post('idItem');
		$tipoSubItem = $this->input->post('tipoSubItem');
		$desconto = $this->input->post('desconto');
		$loginUsuario = $this->auth->get_login_dados('email');
			
		$retorno = to_addDescontoCotacao($idItem, $tipoSubItem, $desconto, $loginUsuario);

		if (!empty($retorno)){
			
			return $retorno;
		}else{
			return false;
		}
	}
    
	public function downloadResumoCotacao(){
		$this->load->helper('util_helper');
		$this->load->helper('my_pdf_helper');
        $this->load->model('sender');
		
		$resumo = $this->template_resumo_cotacao();
		$html_data = $resumo['html'];
		$email = $resumo['email'];

		//para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;
		
        $sender = $this->sender->sendEmailAPI("Resumo de Cotação", $html_data, $email);
		$this->log_shownet->gravar_log($id_user, 'envio de email com cotação', $email, 'criar', 'null', $html_data);

		echo $sender;
	}

	public function enviarResumoCotacao(){
		$this->load->helper('util_helper');
		$this->load->helper('my_pdf_helper');
        $this->load->model('sender');
		
		$resumo = $this->template_resumo_cotacao();
		$html_data = $resumo['html'];
		$email = $resumo['email'];

		//para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;
		
        $sender = $this->sender->sendEmailAPI("Resumo de Cotação", $html_data, $email);
		$this->log_shownet->gravar_log($id_user, 'envio de email com cotação', $email, 'criar', 'null', $html_data);

		echo $sender;
	}

    public function template_resumo_cotacao(){

		$idCotacao = $this->input->post('idCotacao');

        $dados['titulo'] = lang('pedidos');		
        $dados['idCotacao'] = $idCotacao;

		$dados['resumo'] = array();

		$email = "";

		$retorno = json_decode(getResumoCotacao($idCotacao), true);
		if(isset($retorno)){
			$dados['resumo'] = $retorno['resumo'];

			if($retorno['resumo']["email_signatario_software"]){
				$email = $retorno['resumo']["email_signatario_software"];
			}
		}

		$html = $this->load->view('comercial_televenda/pedido/template_resumo_cotacao', $dados, TRUE);
		return array(
			'email' => $email,
			'html' => $html
		);		
    }

	public function template_pdf_cotacao(){

		require_once("../tcpdf/examples/dompdf-master/dompdf_config.inc.php");

		$idCotacao = $this->input->get('idCotacao');

        $dados['titulo'] = lang('pedidos');		
        $dados['idCotacao'] = $idCotacao;

		$dados['resumo'] = array();

		$email = "";

		$retorno = json_decode(getResumoCotacao($idCotacao), true);
		if(isset($retorno)){
			$dados['resumo'] = $retorno['resumo'];

			if($retorno['resumo']["email_signatario_software"]){
				$email = $retorno['resumo']["email_signatario_software"];
			}
		}

		$html = $this->load->view('comercial_televenda/pedido/template_pdf_cotacao', $dados, TRUE);
		
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper(DOMPDF_DEFAULT_PAPER_SIZE, "portrait");
		$dompdf->render();
		$dompdf->stream("template_pdf_cotacao.pdf", array("Attachment" => false));

		echo json_encode(array("status" => 200));
    }

	public function template_pdf_cotacao_html(){

		$idCotacao = $this->input->get('idCotacao');

        $dados['titulo'] = lang('pedidos');		
        $dados['idCotacao'] = $idCotacao;

		$dados['resumo'] = array();

		$email = "";

		$retorno = json_decode(getResumoCotacao($idCotacao), true);

		if(isset($retorno)){
			$dados['resumo'] = $retorno['resumo'];

			if($retorno['resumo']["email_signatario_software"]){
				$email = $retorno['resumo']["email_signatario_software"];
			}

			$html = $this->load->view('comercial_televenda/pedido/template_pdf_cotacao', $dados, TRUE);

			echo json_encode(array("status" => 200, "result" => $html));
		} else {
			return false;
		}
		
    }

	public function removerDocumentos(){
		
		$idDocumento = $this->input->post('idDocumento');

		$retorno = json_decode(to_removeDocumento($idDocumento));

		if ($retorno){
			return $retorno;
		}else{
			return false;
		}
	
	}

	
	public function downloadArquivo(){
		$this->load->helper('util_helper');

		$IdAnnotationn = $this->input->get('idAnnotation');

		$retorno = get_downloadAnexo($IdAnnotationn);
		
		$dados = json_decode($retorno);
		$filebytes = $dados -> fileContent -> filebytes;
		
		$decoded = base64_decode($filebytes);
		$file = $dados-> fileContent -> filename;
		file_put_contents($file, $decoded);

		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);

			if(file_exists($file)){
				unlink($file); // aqui apaga
			}
			exit;
		}
	}

	public function removerSubItem(){

		$idItem = $this->input->post('idItem');
		$tipoSubItem = $this->input->post('tipoSubItem');

		if ($tipoSubItem == 'hardware'){
			$retorno = json_decode(to_removeSubItem('produto_afs', $idItem));
		}else if($tipoSubItem == 'licenca'){
			$retorno = json_decode(to_removeSubItem('licencaafs', $idItem));
		}else if($tipoSubItem == 'servico'){
			$retorno = json_decode(to_removeSubItem('servicosafs', $idItem));
		}

		if ($retorno){
			return $retorno;
		}else{
			return false;
		}
	}

	private function validar_cnpj($cnpj) {
        if (strlen($cnpj) != 14) return false;
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;	
    
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
    
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;
    
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
    
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    private function validar_cpf($cpf) {
        if (strlen($cpf) != 11) return false;
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function validar_documento($documento) {
		$documentoFormatado = preg_replace('/[^0-9]/', '', $documento);
		
		if(strlen($documento) > 14 ){
			return $this->validar_cnpj($documentoFormatado);
		}

        return $this->validar_cpf($documentoFormatado);
    }

	public function validar_documento_ajax() {
		$documento = $this->input->post('documento');

		$valido = $this->validar_documento($documento);
		echo json_encode(array("valido" => $valido ));
    }

	public function clientesPorVendedorTelevendas(){

		$emailVendedor = $this->input->get('emailVendedor');

		$retorno = (to_getClientesVendedorTelevendas($emailVendedor));

		echo json_encode($retorno);
	}

	public function getItensContratoVenda(){
		$idTipoVeiculo = $this->input->post('idTipoVeiculo');

		$retorno = get_itensContratoVenda($idTipoVeiculo);

		if($retorno){
			echo json_encode(array('status' => $retorno['status'], 'data' => $retorno['data']));
			return;
		} else {
			return false;
		}
	}

	public function getContratosRelacionar(){
		$idCliente = $this->input->get('idCliente');

		$retorno = get_contratosRelacionar($idCliente);

		if($retorno){
			echo json_encode(array('status' => $retorno['status'], 'data' => $retorno['data']));
			return;
		} else {
			return false;
		}
	}

	public function associarDissociarContratosComposicaoCotacao(){
		$dados = $this->input->post();

		$retorno = to_associarDissociarContratosComposicaoCotacao($dados);

		echo json_encode($retorno);
	}

	public function listarDetalhamentoFrota(){

		$idTipoVeiculo = $this->input->post('idTipoVeiculo');

		$retorno = (get_detalhamentoFrota($idTipoVeiculo));

		echo json_encode($retorno);
	
	
	}

	public function removerDetalhamentoFrota(){

		$idDetalhamentoFrota = $this->input->post('idDetalhamentoFrota');

		$retorno = (delete_detalhamentoFrota($idDetalhamentoFrota));

		echo json_encode($retorno);
	
	
	}

	public function addDetalhamentoFrota(){
		
		$dados = $this->input->post();

		$retorno = to_addDetalhamentoFrota($dados);

		echo json_encode($retorno);
		
	}
	
	public function revisarCotacao(){
		$idCotacao = $this->input->post('idCotacao');
		$loginUsuario = $this->auth->get_login_dados('email');

		$retorno = (to_revisarCotacao($idCotacao, $loginUsuario));

		echo json_encode($retorno);
	}

	public function listarRazaoValidacao(){
		$idCotacao = $this->input->post('idCotacao');
		$retorno = (to_listarRazaoValidacao($idCotacao));

		echo json_encode($retorno);
	}

	public function listarDadosCliente(){
		$idCliente = $this->input->post('idCliente');
		$tipoCliente = $this->input->post('tipoCliente');

		if ($tipoCliente == 'PF'){
			$retorno = (to_listarDadosClientePF($idCliente));

			if (!$retorno['fullname'] && !$retorno['emailaddress1'] && !$retorno['zatix_cpf']){
				$dadosCliente = array(
					'status' => 400
				);
			}else{
				$dadosCliente = array(
					'nome' => $retorno['fullname'],
					'email' => $retorno['emailaddress1'],
					'documento' => $retorno['zatix_cpf'],
					'status' => 200
				);
			}

			if (!$retorno['tz_nome_signatario_mei'] && !$retorno['tz_email_signatario_mei'] && !$retorno['tz_cpf_cnpj_signatario_mei']){
				$dadosSignatario = array(
					'status' => 400
				);
			}else{
				$dadosSignatario = array(
					'nome' => $retorno['tz_nome_signatario_mei'],
					'email' => $retorno['tz_email_signatario_mei'],
					'documento' => $retorno['tz_cpf_cnpj_signatario_mei'],
					'status' => 200
				);
			}
		}else{
			$retorno = (to_listarDadosClientePJ($idCliente));

			if (!$retorno['name'] && !$retorno['emailaddress1'] && !$retorno['zatix_cnpj']){
				$dadosCliente = array(
					'status' => 400
				);
			}else{
				$dadosCliente = array(
					'nome' => $retorno['name'],
					'email' => $retorno['emailaddress1'],
					'documento' => $retorno['zatix_cnpj'],
					'status' => 200
				);
			}

			if (!$retorno['tz_nome_signatario_mei'] && !$retorno['tz_email_signatario_mei'] && !$retorno['tz_cpf_cnpj_signatario_mei']){
				$dadosSignatario = array(
					'status' => 400
				);
			}else{
				$dadosSignatario = array(
					'nome' => $retorno['tz_nome_signatario_mei'],
					'email' => $retorno['tz_email_signatario_mei'],
					'documento' => $retorno['tz_cpf_cnpj_signatario_mei'],
					'status' => 200
				);
			}
		}

		echo json_encode(array('cliente' => $dadosCliente, 'signatario' => $dadosSignatario));
	}

	public function acaoBotaoCotacao(){
		$idCotacao = $this->input->post('idCotacao');
		$userNameVendedor = $this->auth->get_login_dados('email');
		$action = $this->input->post('action');
		$motivoPerda = $this->input->post('motivoPerda');
		$idCompetidor = $this->input->post('idCompetidor');

		if ($action == 6){
			$dados = array(
				'idCotacao' => $idCotacao,
				'userNameVendedor' => $userNameVendedor,
				'action' => $action,
				'motivoPerda' => $motivoPerda,
				'idCompetidor' => $idCompetidor
			);
		}else{
			$dados = array(
				'idCotacao' => $idCotacao,
				'userNameVendedor' => $userNameVendedor,
				'action' => $action
			);
		}

		$retorno = (json_decode(to_botaoCotacaoAcao($dados)));

		echo json_encode($retorno);
	}

	public function listarCompetidores(){
		$retorno = (json_decode(get_competidores()));
		$competidores = $retorno->value;

		$dadosListar = array();

		foreach ($competidores as $key => $dados) {
			$dadosListar[] = array(
				'id' => $dados->competitorid,
				'text' => $dados->name
			);
		}

		echo json_encode($dadosListar);
	}

	public function listarPlataformas(){
		$retorno = (json_decode(get_plataformas()));
		$plataformas = $retorno->value;

		$dadosListar = array();
		
		foreach ($plataformas as $key => $dados) {
			$dadosListar[] = array(
				'id' => $dados->tz_plataformaid,
				'text' => $dados->tz_name
			);
		}

		echo json_encode($dadosListar);
	}

	public function listarCenariosDeVendaPorPlataforma(){
		$idPlataforma = $this->input->post('idPlataforma');
		
		$retorno = (json_decode(get_cenariosDdeVEndaByPlataforma($idPlataforma)));
		$cenarios = $retorno->value;
		
		$dadosListar = array();

		foreach ($cenarios as $key => $dados) {
			$dadosListar[] = array(
				'id' => $dados->tz_cenario_vendaid,
				'text' => $dados->tz_name
			);
		}
		echo json_encode($dadosListar);
	}

	public function consultarAvaliacaoCliente($idCotacao) {
		$this->load->helper('api_televendas');
		//Buscar os dados da avaliacao do cliente pelo id da cotacao
		$resposta = API_Televendas_Helper::get('/adv/find-profile/' . $idCotacao);
		exit($resposta);
	}

}