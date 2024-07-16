<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use LDAP\Result;

if ( ! function_exists('dd') )
{
    function dd($data)
    {
        exit('<pre>' . print_r($data) . '</pre>');
    }
}

function to_getDocumentosCotacao($idCotacao){

	$CI =& get_instance();

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $CI->config->item('base_url_api_crmintegration').'/crmintegration/api/contract/GetDocsCotacao?idCotacao='.$idCotacao.'&Token='.$CI->config->item('token_crm'),
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 100,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);

	curl_close($curl);

	$response = json_decode($response);

	if($response->Message){
		$message = $response->Message;
		foreach($message as $key => $value){
			$data[] = array('assunto' => $value->Subject, 'arquivo' => $value->filename, 'idAnnotation' => $value->idAnnotation);
		}
	} else {
		$data = array();
	}

	return array('status' => $response->Status, 'data' => $data);
}

function to_getObservacaoCotacao($idCotacao){

	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	$request = $CI->config->item('base_url_crm').'annotations?fetchXml=%3Cfetch%20version=%221.0%22%20output-format=%22xml-platform%22%20mapping=%22logical%22%20distinct=%22false%22%3E%3Centity%20name=%22annotation%22%3E%3Cattribute%20name=%22subject%22%20/%3E%3Cattribute%20name=%22notetext%22%20/%3E%3Cattribute%20name=%22annotationid%22%20/%3E%3Cattribute%20name=%22ownerid%22%20/%3E%3Cattribute%20name=%22modifiedby%22%20/%3E%3Cattribute%20name=%22isdocument%22%20/%3E%3Cattribute%20name=%22modifiedon%22%20/%3E%3Cattribute%20name=%22createdon%22%20/%3E%3Corder%20attribute=%22createdon%22%20descending=%22false%22%20/%3E%3Clink-entity%20name=%22quote%22%20from=%22quoteid%22%20to=%22objectid%22%20alias=%22an%22%3E%3Cfilter%20type=%22and%22%3E%3Ccondition%20attribute=%22quoteid%22%20operator=%22eq%22%20uitype=%22quote%22%20value=%22'.$idCotacao.'%22%20/%3E%3C/filter%3E%3C/link-entity%3E%3Clink-entity%20name=%22systemuser%22%20from=%22systemuserid%22%20to=%22owninguser%22%20visible=%22false%22%20link-type=%22outer%22%20alias=%22vendedor%22%3E%3Cattribute%20name=%22fullname%22%20/%3E%3C/link-entity%3E%3C/entity%3E%3C/fetch%3E';
		
	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = json_decode(curl_exec($curl));
	$data = array();
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	if(isset($response->value)){
		foreach ($response->value as $key => $value){
			$data[] = array(
				"subject" 					=> isset($value->subject) ? $value->subject : '',
				"notetext" 					=> isset($value->notetext) ? $value->notetext : '',
				"idAnnotation"				=> $value->annotationid,
				"createdon" 				=> isset($value->createdon) ? $value->createdon : '',
				"vendedor_x002e_fullname"	=> isset($value->vendedor_x002e_fullname) ? $value->vendedor_x002e_fullname : '',
			);
		}
	}
	curl_close($curl);
	
	return array(
		"status" => $statusCode,
		"data" => $data
	);		
}

function to_cadastrarObservacaoCotacao($body){
	$idCotacao = $body['idCotacao'];
	$userNameVendedor = $body['userNameVendedor'];
	$Assunto = $body['Assunto'];
	$Descricao = $body['Descricao'];

	$CI = &get_instance();
	
	$token_crm 	= $CI->config->item('token_crm');

	$request = $CI->config->item('base_url_api_crmintegration').'/crmintegration/api/contract/ObservacaoTelevenda?Token='.$token_crm;

	$curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{  
			"idCotacao": "'.$idCotacao.'",
			"create": true,  
			"observacaoId": "00000000-0000-0000-0000-000000000000",
			"userNameVendedor": "'.$userNameVendedor.'",  
			"Assunto": "'.$Assunto.'",  
			"Descricao": "'.$Descricao.'"  
		}',
		CURLOPT_HTTPHEADER => array(
		  'Authorization : Bearer '.$token_crm,
		  'Content-Type: application/json'
		),
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);

	$data = array();
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	if(isset($response->value)){
		$data = $response->value;
	}

	curl_close($curl);

	return array(
			'status' => $statusCode,
		"data" => $data
	);
}

function json_response($message = null, $code = 200)
{
    header_remove();
    http_response_code($code);

    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header('Content-Type: application/json');

    switch ($code) {
    	case 200:
    		header('Status: OK');
    		break;
    	case 300:
    		header('Status: Multiple Choices');
    		break;
    	case 400:
    		$status = 'Bad Request';
    		header('Status: Bad Request');
    		break;
    	case 500:
    		header('Status: Internal Server Error');
    		break;
    }

    $response = array(
    	'status' => $code < 300
    );

    if (is_string($message)) {
    	$response['message'] = $message;
    } else {
    	$response['data'] = $message;
    }

    exit(json_encode($response));
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function moneyForFloat($valor = 0) {
    if ($valor) {
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);

        return $valor;
    }

    return 0;
}

function is_activo($valor){
	$CI =& get_instance();
	if($CI->router->fetch_class() == $valor || $CI->router->fetch_method() == $valor)
		return 'active';
}

function set_selecionado($palavra, $busca, $retorno){

	if(is_array($busca)){
		return in_array($palavra, $busca) ? $retorno : '';
	}
	return $palavra == $busca ? $retorno : '';

}

function grava_url($url){
	$CI =& get_instance();
	$CI->session->set_userdata('url_anterior', $url);

}

function get_url(){
	$CI =& get_instance();
	return $CI->session->userdata('url_anterior');

}

function cpf_to_bd($cpf){

	return str_replace(array('-', '.'), '', $cpf);

}

function cnpj_to_bd($cnpj){

	return str_replace(array('-', '.', '/'), '', $cnpj);

}

/*
 * grava logs no banco de dados
*/
function grava_log($log){
	$CI =& get_instance();
	$CI->load->model('gerenciador/log');

	$CI->log->gravar($log);
}

function pegar_endereco($lat, $lng) { //Função em substituição.

	$endereco = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA93nIyimKDtPhoOmiBkRqVRtFG4fwmYnY&latlng='.$lat.','.$lng.'&sensor=false'));
	$result = $endereco->results[0];
	return $result->formatted_address;
}

/*
* Pega o endereço utilizando a API de referências.
*/
function pegar_endereco_referencias($lat, $lng, $format='1', $language='pt') {
    $key = "AIzaSyBYK_0JnaXcWej_b62el2v38Xb4sL1ctB4";
    if ($language === 'pt'){ $language = 'pt-BR'; }
    $content = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=".$language."&key=".$key);

    $retorno = json_decode($content)->results;
    return $retorno ? $retorno[0]->formatted_address : '';
}


function dh_to_br($data)
{
	$data_hora = explode(' ', $data);

	$data = explode('-', $data_hora[0]); // 0 - ano, 1 - mes, 2 - dia
	$hora = explode(':', $data_hora[1]); // 0 - hora, 1 - minuto, 2 - segundo

	return $data[2] . '/' . $data[1] . '/' . $data[0] . ' ' . $hora[0] . ':' . $hora[1] . ':' . $hora[2];
}

function cors() {
	header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS") {
        die();
    }
    return true;
}

function status_fatura($status, $vencimento, $tooltip = '',$remessa=false){
	$back_status = 'erro nos params';
	$hoje = date('Y-m-d');

	switch ($status){
		case 0 :
			if($remessa==1){
				$back_status = '<span class="label label-warning">Remessa gerada</span>';
				break;
			}
			else if($remessa==2){
				$back_status = '<span class="label label-success">Retorno carregado</span>';
				break;
			}
			if($hoje > $vencimento){
				$diff = strtotime($hoje) - strtotime($vencimento);
				$atraso = floor($diff/86400);
				$back_status = '<span class="label label-danger">Atrasado ('.$atraso.' dias)</span>';
			}else{
				$back_status = '<span class="label label-warning">A pagar</span>';
			}
			break;
		case 1 :
			$back_status = '<span class="label label-success">Pago</span>';
			break;
		case 2 :
			if($hoje > $vencimento){
				$diff = strtotime($hoje) - strtotime($vencimento);
				$atraso = floor($diff/86400);
				$back_status = '<span class="label label-danger">Atrasado ('.$atraso.' dias)</span>';
			}else{
				$back_status = '<span class="label label-info">Não enviado</span>';
			}
			break;
		case 3 :
			$back_status = '<span class="label label-default">Cancelado</span>';
            break;
        case 4 :
            $back_status = '<span class="label label-default">A Cancelar</span>';
	}

	return $back_status;
}


function status_contrato($status){
	$back_status = 'erro nos params';

	switch ($status){
		case 0 :
            $back_status = '<span class="label label-primary">Cadastrado</span>';
			break;
		case 1 :
            $back_status = '<span class="label label-info">OS</span>';
			break;
		case 2 :
			$back_status = '<span class="label label-success">Ativo</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-default">Cancelado</span>';
            break;
        case 4 :
            $back_status = '<span class="label label-dark">Teste</span>';
            break;
        case 5 :
            $back_status = '<span class="label label-warning">Bloqueado</span>';
            break;
        case 6:
            $back_status = '<span class="label label-danger">Encerrado</span>';
	}

	return $back_status;
}

//RETORNA O STATUS DA LINHA, DESCRITO E EM UM LABEL
function status_linha($status){
	$back_status = 'erro nos parâmetros';

	switch ($status){
		case 0 :
            $back_status = '<span class="label label-primary">'.lang('cadastrado').'</span>';
			break;
		case 1 :
            $back_status = '<span class="label label-info">'.lang('habilitado').'</span>';
			break;
		case 2 :
			$back_status = '<span class="label label-success">'.lang('ativo').'</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-secondary">'.lang('cancelado').'</span>';
            break;
        case 4 :
            $back_status = '<span class="label label-warning">'.lang('bloqueado').'</span>';
            break;
        case 5 :
            $back_status = '<span class="label label-dark">'.lang('suspenso').'</span>';
            break;
        case 6:
            $back_status = '<span class="label label-danger">'.lang('erro').'</span>';
	}

	return $back_status;
}

function label_nova_data($status_fatura, $dt_atualizado){
	if($dt_atualizado != '' && $status_fatura != 1){
		return '<span class="label label-warning">Venc. Atualizado</span>';
	}
}

/*
 * função para calcular juros por dia
* retorna o valor dos juros
*/
function calcula_juros($valor, $taxa, $num_dias){
	$juros = $valor * (2/100);
	$multa = ($valor * $taxa/100) * $num_dias;
	return round(($juros + $multa), 2);
}

function show_icon_order($p_order, $c_order, $order){

	$img = '';
	if($order == 'asc' && $p_order == $c_order){
		$img = '<i class="icon-chevron-up"></i>';
	}elseif($order == 'desc' && $p_order == $c_order){
		$img = '<i class="icon-chevron-down"></i>';
	}

	return $img;
}

function compara_valor_porcent($val1, $val2){

	$porcent = 0;

	if ($val1 > $val2){
		$val_maior = $val1;
		$val_menor = $val2;
	}else{
		$val_maior = $val2;
		$val_menor = $val1;
	}
	if(!$val_maior || !$val_menor){
		return 0;
	}
	$dif_val = $val_maior / $val_menor;
	if ($dif_val >= 1 && $dif_val < 2){
		$dif_val = round(($dif_val -1), 2);
	}

	if ($dif_val > 0)
		$porcent = round(($dif_val * 100), 2);

	return $porcent;


}

function show_status($status){
	$back_status = lang('erro_params');

	switch ($status){
		case 0 :
			$back_status = '<span class="label label-default">'.lang('inativo').'</span>';
			break;
		case 1 :
			$back_status = '<span class="label label-success">'.lang('ativo').'</span>';
			break;
		case 2 :
			$back_status = '<span class="label label-important">'.lang('bloqueado').'</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-danger">'.lang('cancelado').'</span>';
			break;

	}

	return $back_status;
}

function show_status_contrato($status){
	$back_status = 'erro nos params';

	switch ($status){
		case 0 :
			$back_status = '<span class="label">Cadastrado</span>';
			break;
		case 1 :
			$back_status = '<span class="label label-warning">Em trânsito OS</span>';
			break;
		case 2 :
			$back_status = '<span class="label label-success">Ativo</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-inverse">Cancelado</span>';
			break;
		case 4 :
			$back_status = '<span class="label label-info">Em Teste</span>';
			break;
		case 5 :
			$back_status = '<span class="label label-important">Bloqueado</span>';
			break;
		case 6 :
			$back_status = '<span class="label">Encerrado</span>';
			break;
	}

	return $back_status;
}

function show_status_cliente($status){
	$back_status = 'erro nos params';

	switch ($status){
		case 0 :
			$back_status = '<span class="label label-important">Bloqueado</span>';
			break;
		case 1 :
			$back_status = '<span class="label label-success">Ativo</span>';
			break;
		case 2 :
			$back_status = '<span class="label">Prospectado</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-info">Em teste</span>';
			break;
		case 4:
			$back_status = '<span class="label label-warning">A reativar</span>';
			break;

	}

	return $back_status;
}

function show_status_funcionario($status, $status_bloqueio, $id_funcionario){
	$back_status = '';
    $back_status_bloqueio = '';

    //status do usuario
	switch ($status){
		case 0 :
			$back_status = '<span class="label label-default">'.lang('inativo').'</span>';
			break;
		case 1 :
			$back_status = '<span class="label label-success">'.lang('ativo').'</span>';
			break;
		case 2 :
			$back_status = '<span class="label label-important">'.lang('bloqueado').'</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-danger">'.lang('cancelado').'</span>';
			break;

	}
    //status para ferias/demissao do usuario
	switch ($status_bloqueio){
		case 1 :
			$back_status_bloqueio = '<span class="label label-warning">'.lang('ferias').'</span>';
			break;
		case 2 :
			$back_status_bloqueio = '<span class="label label-danger">'.lang('demitido').'</span>';
			break;
	}

	return '<span class="status_'.$id_funcionario.'">'.$back_status.'<br>'.$back_status_bloqueio.'</span>';
}

//CONSTROI O LABEL DO STATUS DE CONTA BANCARIA DO FUNCIONARIO
function status_conta_bancaria($status, $id_funcionario){
	$back_status = lang('erro_params');

	switch ($status){
		case 0 :
			$back_status = '<span class="label label-info status_conta status_conta_'.$id_funcionario.'">'.lang('secundaria').'</span>';
			break;
		case 1 :
			$back_status = '<span class="label label-success alt status_conta status_conta_'.$id_funcionario.'">'.lang('principal').'</span>';
			break;
		default:
			$back_status = '<span class="label label-important status_conta status_conta_'.$id_funcionario.'">'.lang('cancelada').'</span>';
			break;
	}
	return $back_status;
}

function enviar_email ($assunto, $mensagem, $emails, $item_envio, $anexo = 'nao')
{
	$CI =& get_instance();
	$resposta = false;

	$email = array('assunto_envio' => $assunto, 'conteudo_envio' => $mensagem,
			'item_envio' => $item_envio, 'emails_envio' => $emails, 'anexo' => $anexo);

	$CI->db->insert('systems.envio_cron', $email);
	if ($CI->db->affected_rows())
		$resposta = true;

	return $resposta;
}


function pr($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function get_boleto_default(){
	$CI =& get_instance();

	$config = $CI->db->get('showtecsystem.fatura_config')->row(0);
	if ($config)
		return $config->emissor_boleto;

	return false;
}

function salvar_boleto_default($banco){
	$CI =& get_instance();

	$CI->db->update('showtecsystem.fatura_config', array('emissor_boleto' => $banco));
	if ($CI->db->affected_rows())
		echo 1;

	echo 0;
}

/*
* RETORNA OS DADOS DE TODOS OS VEICULOS DE UM CLIENTE
*/
function getCad_VeicByClie($id_cliente){
    
   
    $body = array('idCliente'  => $id_cliente );
    return json_decode(from_relatorios_api($body, "veiculos/getVeiculosClienteAdm"), true);
}

function getCadVeic($id_user, $placa, $token) {
    $context = stream_context_create(
        array(
            'http' => array(
                'method' => 'GET',
                'header' => "Content-Type: type=application/json\r\n"
                    . "Authorization: $token"
            )
        )
    );

    return @file_get_contents("https://gestor.showtecnologia.com/rest/index.php/api/search_plate?id_user={$id_user}&plate={$placa}", null, $context);
}

/**
 * @param $body
 * @param $url
 * @return bool|string
 * Função auxiliar para enviar parametros da consulta para api-relatorios
 * via POST
 * @author Matthaus Nawan
 */
function from_relatorios_api($body,$url)
{
	# Cria instância do CI
    $CI =& get_instance();
	if ($url == "email") {
		$token_api = $CI->config->item('token_email');
	}
	else{
		$token_api = $CI->config->item('token_acesso_node');
	}	

	
    # URL configurada para a API
    $request = $CI->config->item('base_url_relatorios') . $url;

    # Se houver token realiza a consulta
    if ($token_api && is_string($token_api))
	{
		$ch = curl_init();
	    //Para funcionar sem SSL
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	    curl_setopt_array($ch, [
	        CURLOPT_HTTPHEADER => array(
	        	"Content-Type: application/json",
	        	"x-access-token: ".$token_api
	        ),
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_POST => true,
	        CURLOPT_POSTFIELDS => json_encode($body),
	        CURLOPT_URL => $request
	    ]);

	    $data = curl_exec($ch);

	    if (curl_errno($ch)) {
	        echo 'Error:' . curl_error($ch);
	    }

	    curl_close($ch);

	    if ($data)
	        return $data;
	}

	return false;
}

/**
 * @param $body
 * @param $url
 * @return bool|string
 * Função auxiliar para enviar parametros da consulta para api-relatorios
 * via POST
 * @author Matthaus Nawan
 */
function from_relatorios_api_form_data($body, $url, $imagem = false, $token_api = false)
{
	# Cria instância do CI
    $CI =& get_instance();

    # URL configurada para a API
    $request = $CI->config->item('base_url_relatorios') . $url;

    # Se houver token realiza a consulta
    if ($token_api && is_string($token_api))
	{
		$ch = curl_init();

	    //Para funcionar sem SSL
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if($imagem){
			$body['anexo'] = new CURLFile($imagem["tmp_name"], $imagem["type"], $imagem["name"]);
		}

	    curl_setopt_array($ch, [
	        CURLOPT_HTTPHEADER => array(
	        	"x-access-token: ".$token_api
	        ),
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_POST => true,
	        CURLOPT_POSTFIELDS => $body,
	        CURLOPT_URL => $request
	    ]);

	    $data = curl_exec($ch);

	    if (curl_errno($ch)) {
	        echo 'Error:' . curl_error($ch);
	    }

	    curl_close($ch);

	    if ($data)
	        return $data;
	}

	return false;
}
// atualiza o nome do arquivo para forçar o carregamento pelo browser, resolvendo problema de cash
function versionFile($dir, $file) {
    $versaoAtual = '';
    clearstatcache();
    if(is_file($dir.'/'.$file)) {
        $versaoAtual .= base_url($dir).'/'.$file.'?'.filemtime(utf8_decode($dir.'/'.$file));
        return $versaoAtual;
    }
    return false;
}

function getTokenTicket(){
	$ch = curl_init();
	curl_setopt_array($ch, [
		CURLOPT_HTTPHEADER => array('content-type: application/x-www-form-urlencoded'),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => 'grant_type=password&username=11A75C962FEAB0C6E123944B95269349891BFDAB204CBCE5AEE900C7BC91E192&password=F72FF15DB56E61784FAD5CBEB43F8F7FEE7A3660B71593E8BC368C52E24A1FD8',
	    CURLOPT_URL => 'https://app.omnilink.com.br/apicrm/token'
	]);
	$retorno = json_decode(curl_exec($ch), true); # Decodifica retorno json
	//$erro = curl_error($ch); # Debugar erro na requisição
	curl_close ($ch);

	return $retorno["access_token"];
}

//funcao para gerar o token para acesso ao ApiSaver
function getTokenApiIridium($email, $senha){
	# Cria instância do CI
    $CI =& get_instance();

    # URL configurada para a API
    $request = $CI->config->item('url_apishownet').'autenticar';//completar url no config para acesso ao endpoint para geração de tokens

	//dados necessários para geração do token (são os dados usados no login do shownet)
	$dados = [
		'email' => $email,
		'senha' => $senha
	];

	//inicialização do objeto para requisição
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($dados),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	));
	
	//procurando por erros no objeto
	if (curl_error($ch))  throw new Exception(curl_error($ch));

	$retorno = json_decode(curl_exec($ch), true); # Decodifica retorno json

	//fechando objeto $ch
	curl_close ($ch);

	//retorno de função deve ser o token de acesso a API
	return $retorno['token'];
}

function getIdClientOmnilink($cnpj, $token){
	$ch = curl_init();
	//Para funcionar sem SSL
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt_array($ch, [
		CURLOPT_HTTPHEADER => array('Content-Type: application/json',
		'Authorization: Bearer '.$token->access_token),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => 'https://app.omnilink.com.br/apigateway/getcliente?CPF_CNPJ='.$cnpj
	]);
	$retorno = json_decode(curl_exec($ch), true); # Decodifica retorno json

	$erro = curl_error($ch); # Debugar erro na requisição
	curl_close ($ch);

	return $retorno["Cliente"]["ClienteID"];
}

// Envia ficha de ativação
function sendActivationTokenOmnilink($data, $token) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt_array($ch, [
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token
		),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => json_encode($data),
	    CURLOPT_URL => 'https://app.omnilink.com.br/apigateway/fichaativacaoemail'
	]);
	
	$retorno = json_decode(curl_exec($ch)); # Decodifica retorno json
	$erro = curl_error($ch); # Debugar erro na requisição

	curl_close ($ch);

	return empty($erro) ? $retorno : false;
}

//Get Token Gateway Omnilink
function GetTokenGatewayOmnilink(){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
	curl_setopt_array($ch, [
		CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://app.omnilink.com.br/apigateway/token?usuario=MTM3MzFlY2QyYmRlYjU2OTVjNDAwYzE0NDQwOTY4ZWM=&senha=ZmRhYjFjMTk4ZDY0MzI4YmYxODRjZmZlNjg2NGFhOTM='
	]);
	$token_om = json_decode( curl_exec($ch) );
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close ($ch);

	return $token_om;
}

function enviarTicketCrmOnilink($data) {
	// pr($data);die;
	// echo getTokenTicket();die;
	$ch = curl_init();

	//Para funcionar sem SSL
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt_array($ch, [
		CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Authorization: Bearer '.getTokenTicket()),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => json_encode($data),
	    CURLOPT_URL => 'https://app.omnilink.com.br/apicrm/api/incident/incidentcreate'
	]);
	$retorno = json_decode(curl_exec($ch)); # Decodifica retorno json
	//$erro = curl_error($ch); # Debugar erro na requisição
	curl_close ($ch);

	return $retorno;
}

function getSubtitulo($ref) {
	$string = 'Show Tecnologia';
	if ($ref && is_string($ref)) {
		switch ($ref) {
			case 'OMNILINK':
				$string = 'Omnilink Tecnologia';
				break;
			case 'NORIO':
				$string = 'Siga-Me';
				break;
			case 'SIMM2M':
				$string = 'SIMM2M';
				break;
		}
	}
	return $string;
}

function imageToBase64($src){
    //PEGA A IMAGEM E CONVERTE EM STRING
    $img = file_get_contents($src);

    //RETORNA A IMAGEM ENCODADA PARA BASE64
    return base64_encode($img);
}

//TRADUZ A DATA  EN/PT-BR
function traduzData($data, $idioma='pt-BR') {
    // idiomas podem ser: pt-BR, en
    $en = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
    $pt = ['DOMINGO', 'SEGUNDA-FEIRA', 'TERÇA-FEIRA', 'QUARTA-FEIRA', 'QUINTA-FEIRA', 'SEXTA-FEIRA', 'SÁBADO', 'JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'];
    if ($idioma == 'pt-BR')
        return ucfirst(strtolower(str_ireplace($en, $pt, strtoupper($data))));
    else
        return ucfirst(strtolower(str_ireplace($pt, $en, strtoupper($data))));

	return false;
}

/*
* RETORNA O NOME DA PRESTADORA
*/
function getNomePrestadora($ref) {
	$prestadora = '';
	if ($ref && is_string($ref)) {
        switch ($ref) {
            case 'TRACKER':
                $prestadora = 'SHOW TECNOLOGIA';
                break;
            case 'SIMM2M':
                $prestadora = 'SIMM2M';
                break;
            case 'EUA':
                $prestadora = 'SHOW TECHNOLOGY';
                break;
            case 'OMNILINK':
                $prestadora = 'OMNILINK';
                break;
            case 'EMBARCADORES':
                $prestadora = 'EMBARCADORES';
                break;
            case 'NORIO':
                $prestadora = 'SIGA-ME';
                break;
        }
	}
	return $prestadora;
}

/*
 *  RETORNA OS DADOS DE UMA TABELA EM FORMATO .XLS (EXCEL)
*/
//FORMATO DA TABELA (MATRIZ COM CHAVE => VALOR)
// Array
// (
//     [0] => Array
//         (
//             [cliente] => CAOA MONTADORA
//             [pf_pj] => Pessoa Jurídica
//             [prestadora] => OMNILINK
//         )

//     [1] => Array
//         (
//             [cliente] => CAOA MONTADORA
//             [pf_pj] => Pessoa Jurídica
//             [prestadora] => OMNILINK
//         )
// 		.....
// )

//A VARIAVEL $remove_coluna É UM ARRAY DAS CHAVES A SEREM REMOVIDAS
//A VARIAVEL $nome_arquivo É UMA STRING CONTENDO O NOME PARA O ARQUIVO A SER EXPORTADO
//A VARIAVEL $titulo É UMA STRING CONTENDO O NOME PARA O TITULO DENTRO DO ARQUIVO, OCUPANDO A PRIMEIRA LINHA DE TEXTO DO ARQUIVO
//A VARIAVEL $subTitulo É UMA STRING CONTENDO O NOME PARA O SUBTITULO DENTRO DO ARQUIVO, OCUPANDO A SEGUNDA LINHA DE TEXTO DO ARQUIVO
			
function tableToExcell($nome_arquivo, $colunas, $tabela = array(), $remove_coluna=[], $titulo=false, $subTitulo=false){
	$chaves = array_keys($tabela[0]);

	if (count($remove_coluna) > 0) {
		foreach ($remove_coluna as $r) {
			unset($chaves[$r]);
		}
	}
	$tamanhoTabela = count($chaves);
		
	if ($tamanhoTabela > 0) {		
		//AO CONVERTER PARA MAUISCULO OS ACENTOS SERAO CONVERTIDOS ADEQUADAMENTE
		mb_internal_encoding('UTF-8');
		
		//CONSTROI A TABELA
		$dadosXls = '<meta charset="UTF-8" /><table>';

		if ($titulo)
			$dadosXls .= '<tr><td colspan="'.$tamanhoTabela.'"><center><h3>'.mb_strtoupper($titulo).'</h3></center></td></tr>';
		
		if ($subTitulo)
			$dadosXls .= '<tr><td colspan="'.$tamanhoTabela.'"><h3>'.$subTitulo.'</h3></td></tr><tr></tr>';
				
		$dadosXls .= '<tr>';

		foreach ($colunas as $coluna) {
			$dadosXls .= '<th><b>'.$coluna.'</b></th>';
		}
		$dadosXls .= '</tr>';
		
		//varremos o array com o foreach para pegar os dados
		foreach($tabela as $linha){
			$dadosXls .= '<tr>';
			foreach ($chaves as $chave) {
				$dadosXls .= '<td>'.$linha[$chave].'</td>';
			}
			$dadosXls .= '</tr>';
		}
		$dadosXls .= "</table>";
		
		// Configurações header para forçar o download
        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel;charset=utf-8");	
        header ("Content-Disposition: attachment; filename=\"{$nome_arquivo}\"" );
		
		// Envia o conteúdo do arquivo
		return $dadosXls;
	}
}

/*
* DEVOLVE APENAS OS NUMEROS DA STRING PASSADA POR PARAMENTRO
*/
function apenasNumeros($string=''){
	return preg_replace('/[^0-9]/', '', $string);	
}

/*
* REMOVE CARASTERES ESPECIAIS DE UMA STRING
*/
function removeCaracteresEspeciais($string=''){
	return preg_replace('/[^A-Za-z0-9\?]/', '', $string);	
}

/*
* CRIA MASCARAS PARA VALORES (EX.: CPF, CNPJ, CEP...), BASTA PASSAR '#' ONDE SERA OS NUMERO
*/
function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++) {
        if($mask[$i] == '#') {
            if(isset($val[$k])) $maskared .= $val[$k++];
        } else {
            if(isset($mask[$i])) $maskared .= $mask[$i];
        }
    }
    return $maskared;
}


/**
 * https://gist.github.com/guisehn/3276302
 */
function validar_cnpj($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
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

/**
 * https://gist.github.com/rafael-neri/ab3e58803a08cb4def059fce4e3c0e40
 */
function validar_cpf($cpf) {
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
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

function tempoParaMinuto($tempo, $inteiro = true)
{
    $tempoArray = explode(':', $tempo);
    $minutos = ($tempoArray[0]*60) + ($tempoArray[1]) + ($tempoArray[2]/60);
 
	if ($inteiro)
    	return (int)$minutos;

    return $minutos;
}

/**
 * @author		Renato Silva
 * 
 * versao melhorada da funcao 'ARRAY_COLUM'
 * trabalha com (array de arrays) ou (array de objetos) e retorna um array com os dados da coluna
 * $obj_array pode ser um array de array ou array de objetos
 * $key eh a chave pela qual ira filtrar
*/
if ( ! function_exists('array_column_custom') )
{
	function array_column_custom($obj_array, $key) {
		return array_map(function($e) use ($key) {
			return is_object($e) ? $e->$key : $e[$key];
		}, $obj_array);
	}
}



/**
 * @author		Renato Silva
 * 
 * Retorna a string encryptada em openssl_encrypt com ecryptacao fixa
*/
if ( ! function_exists('openssl_encrypt_string') )
{
	function openssl_encrypt_string($string) {
		$ciphering = "AES-128-CTR";

		// Using OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length($ciphering);

		// Non-NULL Initialization Vector for encryption 
		$encryption_iv = '1101110011011121';

		// Storing the encryption key 
		$encryption_key = "show";

		// Using openssl_encrypt() function to encrypt the data 
		return openssl_encrypt($string, $ciphering, $encryption_key, $options=0, $encryption_iv);
	}
}

/**
 * @author		Renato Silva
 * 
 * Decrypta uma string anteriormente encryptada em openssl_encrypt com ecryptacao fixa
*/
if ( ! function_exists('openssl_decrypt_string') )
{
	function openssl_decrypt_string($string_encryptada) {
		$ciphering = "AES-128-CTR";

		// Using OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length($ciphering);

		// Non-NULL Initialization Vector for encryption 
		$encryption_iv = '1101110011011121';

		// Storing the encryption key 
		$encryption_key = "show";

		// Using openssl_encrypt() function to encrypt the data 
		return openssl_decrypt($string_encryptada, $ciphering, $encryption_key, $options=0, $encryption_iv);
	}
}

function gerartokenbaseInstalada(){

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://app.omnilink.com.br/apigateway/token?usuario=MTM3MzFlY2QyYmRlYjU2OTVjNDAwYzE0NDQwOTY4ZWM=&senha=ZmRhYjFjMTk4ZDY0MzI4YmYxODRjZmZlNjg2NGFhOTM=',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
		'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJNVE0zTXpGbFkyUXlZbVJsWWpVMk9UVmpOREF3WXpFME5EUXdPVFk0WldNPSIsImp0aSI6ImMzYTFiZjdjLTY2MDItNGU3OS04ZGQ4LWEwZTQ1ODdmOTdkNCIsImlhdCI6IjEvMjAvMjAyMyAxOjM5OjMyIFBNIiwibmJmIjoxNjc0MjIxOTcyLCJleHAiOjE2NzQyMzI3NzIsImlzcyI6Imh0dHBzOi8vd3d3Lm9tbmlsaW5rLmNvbS5iciIsImF1ZCI6Ik9tbmlsaW5rIn0.u_M7injbkbvy1GTPtZ1rHShzhfPehbsp2Hzpv0eYaH8'
	  ),
	));
	
	$response = curl_exec($curl);
	curl_close($curl);

	$json = json_decode($response);

	return $json->access_token;
	
}



function baseInstalada($NumSerie, $CnpjCpf, $Email){

	$token = gerartokenbaseInstalada();
	$CI =& get_instance();

	$request 	= $CI->config->item('base_url_api_crmintegration').'/apigateway/cliente/contrato/baseinstalada';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt_array($curl, array(
   	CURLOPT_URL => $request,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
        "NumSerie": "'.$NumSerie.'",
        "CPFCNPJ":"'.$CnpjCpf.'",
        "Email":"'.$Email.'"              

    }',

      CURLOPT_HTTPHEADER => array(
        'Authorization : Bearer '.$token,
        'Content-Type: application/json'
      ),

    ));

   

    $response = curl_exec($curl);
    curl_close($curl);	

    return json_encode($response);

    }

function from_nexxera_arquivos($final_date = null, $initial_date = null)
{
	# Cria instância do CI
    $CI =& get_instance();

	# configuração para a API
    $request 	= $CI->config->item('url_api_nexxera_arq')."?final_date=".$final_date."&initial_date=".$initial_date;
	$token_api 	= $CI->config->item('token_nexxera');
	$cookie_api = $CI->config->item('cookie_nexxera');
		
	
    # Se houver token realiza a consulta
    if ($token_api && is_string($token_api))
	{
		$ch = curl_init();

	    //Para funcionar sem SSL
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    			
		curl_setopt_array($ch, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(			
			'service-token:'. $token_api,
			'Cookie:'. $cookie_api
		),
		));

	    $response = curl_exec($ch);

		$data_from_api = json_decode($response);
			
	    if (curl_errno($ch)) {
	        echo 'Error:' . curl_error($ch);
	    }
						
		$nome_arquivo = array();			
				
		foreach ($data_from_api->result as $resultado) {			
			
			if ($resultado->sender == "OMNILINK.BB-COBRANCA-09338999000158") {
				array_push($nome_arquivo, $resultado->filename);
			}					
		}
		
		curl_close($ch);
	    return json_encode($nome_arquivo);		
	}
	return false;
}


#função para buscar arquivos de retorno no nexxera
function from_nexxera_urls($final_date = null, $initial_date = null)
{
	# Cria instância do CI
    $CI =& get_instance();

    # configuração para a API
	$request = $CI->config->item('url_api_nexxera_unread');
	$token_api	= $CI->config->item('token_nexxera');
    $cookie_api = $CI->config->item('cookie_nexxera');

	$data = from_nexxera_arquivos($final_date, $initial_date);
		
    # Se dados retornados da api
    if ($data)
	{
		$ch = curl_init();
	    //Para funcionar sem SSL
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt_array($ch, array(
			CURLOPT_URL => $request,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
			  'service-token: '.$token_api,
			  'Content-Type: application/json',
			  'Cookie: '.$cookie_api
			),
		));

	    $response = curl_exec($ch);

				
			
		curl_close($ch);

			
		return ($response);			

	}
	return false;
}
function getTokenLogistica($email, $senha){
	# Cria instância do CI
    $CI =& get_instance();

    # URL configurada para a API
    $request = $CI->config->item('url_api_shownet_rest').'autenticar';

	//dados necessários para geração do token (são os dados usados no login do shownet)
	$dados = [
		'email' => $email,
		'senha' => $senha
	];

	//inicialização do objeto para requisição
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($dados),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	));
	
	//procurando por erros no objeto
	if (curl_error($ch))  throw new Exception(curl_error($ch));

	$retorno = json_decode(curl_exec($ch), true); # Decodifica retorno json

	//fechando objeto $ch
	curl_close ($ch);

	//retorno de função deve ser o token de acesso a API
	return $retorno['token'];
}


function getTokenRH($email, $senha){
	# Cria instância do CI
    $CI =& get_instance();

    # URL configurada para a API
    $request = $CI->config->item('url_token_RH').'autenticar';

	//dados necessários para geração do token (são os dados usados no login do shownet)
	$dados = [
		'email' => $email,
		'senha' => $senha
	];

	//inicialização do objeto para requisição
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($dados),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	));
	
	//procurando por erros no objeto
	if (curl_error($ch))  throw new Exception(curl_error($ch));

	$retorno = json_decode(curl_exec($ch), true); # Decodifica retorno json

	//fechando objeto $ch
	curl_close ($ch);

	//retorno de função deve ser o token de acesso a API
	return $retorno['token'];
}



function from_clienteCRM($documento = null)
{
	# Cria instância do CI
    $CI =& get_instance();

	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/customers/GetClientesTelevendas?documento=".$documento."&Token=".$token_crm;
	
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

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;	   	
	
}

function IntegrarClienteERP($data)
{
	# Cria instância do CI
    $CI =& get_instance();
	
	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/customers/IntegrarClienteERP?Token=".$token_crm;
	
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
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
		));

	$response =  json_decode(curl_exec($curl), true);
	
	curl_close($curl);

	return $response;
}


function to_clienteCRM($dados = null)
{
	$nome 			 = $dados['nome'];			
	$sobrenome 	 	 = $dados['sobrenome'] ;	
	$cpfTratado		 = $dados['cpfTratado'] ;		
	$cnpjTratado	 = $dados['cnpjTratado']; 	
	$rg			 	 = $dados['rg'] ;			
	$dataNascimento  = $dados['dataNascimento'];
	$ddd			 = $dados['ddd'] ;			
	$telefone 		 = $dados['telefone']; 		
	$email		 	 = $dados['email'];	
	$tipoCliente  	 = $dados['tipoCliente'];		
	$cep			 = $dados['cep'];
	$rua			 = $dados['rua'];
	$bairro			 = $dados['bairro'];
	$cidade			 = $dados['cidade'];
	$estado			 = $dados['estado'];
	$complemento 	 = $dados['complemento'];
	$loginVendedor 	 = $dados['loginVendedor'];
	$nomeFantasia	 = $dados['nomeFantasia'];

	# Cria instância do CI
    $CI =& get_instance();

	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/customers/CriarClienteTelevendas?Token=".$token_crm;
	
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
	CURLOPT_POSTFIELDS =>'{
		"nome": "'.$nome.'",
		'. ($tipoCliente == "PF" ? '"sobrenome": "'.$sobrenome.'",': "" ).'
		"nomeFantasia": "'.$nomeFantasia.'",
		"cpf": "'.$cpfTratado.'",
		"cnpj": "'.$cnpjTratado.'",
		"rg": "'.$rg.'",
		"ddd": "'.$ddd.'",
		"telefone": "'.$telefone.'",
		"email": "'.$email.'",
		"tipoCLiente": "'.$tipoCliente.'",
		"endereco": {
			"cep": "'.$cep.'",
			"rua": "'.$rua.'", 
			"bairro": "'.$bairro.'", 
			"cidade": "'.$cidade.'", 
			"estado": "'.$estado.'", 
			"complemento": "'.$complemento.'"
		},
		"loginVendedor": "'.$loginVendedor.'"
			}',
		CURLOPT_HTTPHEADER => array(
		  'Content-Type: application/json'
		),
	  ));
		  

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;	   	
	
}

function get_idProdutoCRM()
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
    $request 	= $CI->config->item('base_url_crm').'products?$select=name,productid,productnumber&$filter=tz_disponivel_televendas%20eq%20true';
		
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'		
		]
	]);

	$response = curl_exec($curl);
	$responseInfo = curl_getinfo($curl);

	/* if ($responseInfo["http_code"]!=200 && $responseInfo["http_code"]!=201) {
		print_r($responseInfo);
		echo $response;
		} else {
		print_r(json_decode($response));
		} */

	curl_close($curl);

	return $response;	 	

}

function get_cenarioVendaCRM()
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
    $request 	= $CI->config->item('base_url_crm').'tz_cenario_vendas?$select=tz_codigo_erp,tz_name&$filter=tz_disponivel_televendas%20eq%20true&$orderby=tz_name%20asc';
		
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);	
	curl_close($curl);
	
	return $response;	 
			

}

function get_tipoPagamentoCRM()
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').'tz_tipo_pagamentos?$select=tz_codigo_erp,tz_name&$filter=tz_disponivel_televendas%20eq%20true';
		
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				

}


function get_condicaoPagamentoCRM($value = null)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').'tz_condicao_pagamentos?fetchXml=%3Cfetch%20version=%221.0%22%20output-format=%22xml-platform%22%20mapping=%22logical%22%20distinct=%22true%22%3E%0A%3Centity%20name=%22tz_condicao_pagamento%22%3E%0A%3Cattribute%20name=%22tz_condicao_pagamentoid%22%20/%3E%0A%3Cattribute%20name=%22tz_name%22%20/%3E%0A%3Cattribute%20name=%22tz_ordenador%22%20/%3E%0A%3Cattribute%20name=%22tz_quantidadeparcelas%22%20/%3E%0A%3Cattribute%20name=%22tz_codigo_erp%22%20/%3E%0A%3Corder%20attribute=%22tz_ordenador%22%20descending=%22true%22%20/%3E%0A%3Cfilter%20type=%22and%22%3E%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%0A%3Ccondition%20attribute=%22tz_disponivel_televendas%22%20operator=%22eq%22%20value=%221%22%20/%3E%0A%3C/filter%3E%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%C2%A0%0A%3Clink-entity%20name=%22tz_condicao_pagamento_tz_tipo_pagamento%22%20from=%22tz_condicao_pagamentoid%22%20to=%22tz_condicao_pagamentoid%22%20visible=%22false%22%20intersect=%22true%22%3E%0A%3Clink-entity%20name=%22tz_tipo_pagamento%22%20from=%22tz_tipo_pagamentoid%22%20to=%22tz_tipo_pagamentoid%22%20alias=%22ah%22%3E%0A%3Cfilter%20type=%22and%22%3E%0A%3Ccondition%20attribute=%22tz_codigo_erp%22%20operator=%22eq%22%20value=%22'.$value.'%22%20/%3E%20%3Ccondition%20attribute=%22tz_disponivel_televendas%22%20operator=%22eq%22%20value=%221%22%20/%3E%0A%3C/filter%3E%0A%3C/link-entity%3E%0A%3C/link-entity%3E%0A%3C/entity%3E%0A%3C/fetch%3E';
		
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				

}

function get_tipoVeiculoCRM()
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
    $request 	= $CI->config->item('base_url_crm').'tz_tipo_veiculos?$select=tz_codigo_erp,_tz_grupo_tipo_veiculoid_value,tz_name&$filter=statecode%20eq%200&$orderby=tz_name%20asc';
		
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);

		
	curl_close($curl);
	
	return $response;				

}

function get_planoSatelitalCRM($value = null)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').'tz_plano_satelitals?fetchXml=%3Cfetch%20version=%271.0%27%20output-format=%27xml-platform%27%20mapping=%27logical%27%20distinct=%27true%27%3E%3Centity%20name=%27tz_plano_satelital%27%3E%3Cattribute%20name=%27tz_plano_satelitalid%27%20/%3E%3Cattribute%20name=%27tz_name%27%20/%3E%3Cattribute%20name=%27createdon%27%20/%3E%3Corder%20attribute=%27tz_name%27%20descending=%27false%27%20/%3E%3Cfilter%20type=%27and%27%3E%3Ccondition%20attribute=%27statecode%27%20operator=%27eq%27%20value=%270%27%20/%3E%3Ccondition%20attribute=%22tz_disponivel_televendas%22%20operator=%22eq%22%20value=%221%22%20/%3E%3C/filter%3E%3Clink-entity%20name=%27tz_tecnologia%27%20from=%27tz_tecnologiaid%27%20to=%27tz_tecnologiaid%27%20alias=%27an%27%3E%3Clink-entity%20name=%27tz_familia_produtos%27%20from=%27tz_tecnologiaid%27%20to=%27tz_tecnologiaid%27%20alias=%27ao%27%3E%3Cfilter%20type=%27and%27%3E%3Ccondition%20attribute=%27tz_rastreadorid%27%20operator=%27eq%27%20uitype=%27product%27%20value=%27'.$value.'%27%20/%3E%3C/filter%3E%3C/link-entity%3E%3C/link-entity%3E%3C/entity%3E%3C/fetch%3E';
		
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				

}

function to_oportunidadeCRM($dadoscreate = null) {

    $body = [
        "documentoCliente" => "{$dadoscreate['documentoCliente']}",
        "tipoCliente" => "{$dadoscreate['tipoCliente']}",
        "origem" => "{$dadoscreate['origem']}",
        "qtdVeiculos" => "{$dadoscreate['qtdVeiculos']}",
        "ID_Produto" => "{$dadoscreate['ID_Produto']}",
        "tempoContrato" => "{$dadoscreate['tempoContrato']}",
        "cenario_venda" => "{$dadoscreate['cenario_venda']}",
        "tipo_pagamento" => "{$dadoscreate['tipo_pagamento']}",
        "condicao_pagamento" => "{$dadoscreate['condicao_pagamento']}",
        "tipoVeiculo" => "{$dadoscreate['tipoVeiculo']}",
        "planoSatelital" => "{$dadoscreate['planoSatelital']}",
		"adicionarOportunidade_CRM" => "{$dadoscreate['adicionarOportunidade_CRM']}",
        "userNameVendedor" => "{$dadoscreate['userNameVendedor']}",
        "contratoNovo" => "{$dadoscreate['contratoNovo']}",
        "modalidadeVenda" => "{$dadoscreate['modalidadeVenda']}",
		"clientRetiraArmazem" => "{$dadoscreate['clientRetiraArmazem']}",
    ];

	if ($dadoscreate['clientRetiraArmazem'] == '1') {
		$body["armazem"] = "{$dadoscreate['armazem']}";
		$body["responsavelRetirada"] = "{$dadoscreate['responsavelRetirada']}";
		$body["cpfResponsavelRetirada"] = "{$dadoscreate['cpfResponsavelRetirada']}";
	}

    if ($dadoscreate['modalidadeVenda'] === '9') {
        unset($body['tempoContrato']);
        unset($body['tipoVeiculo']);
        unset($body['planoSatelital']);
    }
    else if ($dadoscreate['modalidadeVenda'] === '8') {
        unset($body['ID_Produto']);
        unset($body['tipoVeiculo']);
        unset($body['planoSatelital']);
    }
    else if ($dadoscreate['modalidadeVenda'] === '6') {
        unset($body['ID_Produto']);
    }

	# Cria instância do CI
    $CI =& get_instance();

	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/CreateOportunidadeTelevendas?Token=".$token_crm;
	
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
        CURLOPT_POSTFIELDS => json_encode($body, true),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function to_edit_oportunidadeCRM($dadosedit = null)
{
	$cotacaoId			= $dadosedit['cotacaoId'];		
	$inicioVigencia		= $dadosedit['inicioVigencia'];		
	$terminoVigencia	= $dadosedit['terminoVigencia'];		
	$qtdVeiculos		= $dadosedit['qtdVeiculos'];		
	$tempoContrato		= $dadosedit['tempoContrato'];
	$ID_Produto			= $dadosedit['ID_Produto'];	
	$cenario_venda		= $dadosedit['cenario_venda'];	
	$tipo_pagamento		= $dadosedit['tipo_pagamento']; 	
	$condicao_pagamento	= $dadosedit['condicao_pagamento'];
	$tipoVeiculo		= $dadosedit['tipoVeiculo'];
	$planoSatelital		= $dadosedit['planoSatelital'];
	$modalidadeVenda 	= $dadosedit['modalidadeVenda'];
	$userNameVendedor 	= $dadosedit['userNameVendedor'];
	$signatarioSoftware = $dadosedit['signatario']['signatario_software'];
	$emailSignatarioSoftware = $dadosedit['signatario']['email_signatario_software'];
	$documentoSignatarioSoftware = $dadosedit['signatario']['documento_signatario_software'];
	$signatarioHardware = $dadosedit['signatario']['signatario_hardware'];
	$emailSignatarioHardware = $dadosedit['signatario']['email_signatario_hardware'];
	$documentoSignatarioHardware = $dadosedit['signatario']['documento_signatario_hardware'];
	
	if ($dadosedit['modalidadeVenda'] == '9'){
		$body = '{
            "cotacaoId": "'.$cotacaoId.'",
            "inicioVigencia": "'.$inicioVigencia.'",
            "terminoVigencia": "'.$terminoVigencia.'",
            "qtdVeiculos": "'.$qtdVeiculos.'",
            "ID_Produto": "'.$ID_Produto.'",
            "tempoContrato": "'.$tempoContrato.'",
            "cenario_venda": "'.$cenario_venda.'",
            "tipo_pagamento": "'.$tipo_pagamento.'",
            "condicao_pagamento": "'.$condicao_pagamento.'",
            "modalidadeVenda": "'.$modalidadeVenda.'",
            "userNameVendedor": "'.$userNameVendedor.'",
            "signatario": {
                "signatario_software": "'.$signatarioSoftware.'",
                "email_signatario_software": "'.$emailSignatarioSoftware.'",
                "documento_signatario_software": "'.$documentoSignatarioSoftware.'",
                "signatario_hardware": "'.$signatarioHardware.'",
                "email_signatario_hardware": "'.$emailSignatarioHardware.'",
                "documento_signatario_hardware": "'.$documentoSignatarioHardware.'"
            },
			"clientRetiraArmazem": "'. $dadosedit['clientRetiraArmazem'].'",
			"armazem": "'. $dadosedit['armazem'].'",
			"responsavelRetirada": "'. $dadosedit['responsavelRetirada'].'",
			"cpfResponsavelRetirada": "'. $dadosedit['cpfResponsavelRetirada'].'"
        }';
	}else if($dadosedit['modalidadeVenda'] == '8'){
		$body = '{
            "cotacaoId": "'.$cotacaoId.'",
            "inicioVigencia": "'.$inicioVigencia.'",
            "terminoVigencia": "'.$terminoVigencia.'",
            "qtdVeiculos": "'.$qtdVeiculos.'",
            "tempoContrato": "'.$tempoContrato.'",
            "cenario_venda": "'.$cenario_venda.'",
            "tipo_pagamento": "'.$tipo_pagamento.'",
            "condicao_pagamento": "'.$condicao_pagamento.'",
            "modalidadeVenda": "'.$modalidadeVenda.'",
            "userNameVendedor": "'.$userNameVendedor.'"	,
            "signatario": {
                "signatario_software": "'.$signatarioSoftware.'",
                "email_signatario_software": "'.$emailSignatarioSoftware.'",
                "documento_signatario_software": "'.$documentoSignatarioSoftware.'",
                "signatario_hardware": "'.$signatarioHardware.'",
                "email_signatario_hardware": "'.$emailSignatarioHardware.'",
                "documento_signatario_hardware": "'.$documentoSignatarioHardware.'"
            },
			"clientRetiraArmazem": "' . $dadosedit['clientRetiraArmazem'] . '",
			"armazem": "' . $dadosedit['armazem'] . '",
			"responsavelRetirada": "' . $dadosedit['responsavelRetirada'] . '",
			"cpfResponsavelRetirada": "' . $dadosedit['cpfResponsavelRetirada'] . '"
        }';
	}else if($dadosedit['modalidadeVenda'] == '6'){
		$body = '{
            "cotacaoId": "'.$cotacaoId.'",
            "inicioVigencia": "'.$inicioVigencia.'",
            "terminoVigencia": "'.$terminoVigencia.'",
            "qtdVeiculos": "'.$qtdVeiculos.'",
            "tempoContrato": "'.$tempoContrato.'",
            "cenario_venda": "'.$cenario_venda.'",
            "tipo_pagamento": "'.$tipo_pagamento.'",
            "condicao_pagamento": "'.$condicao_pagamento.'",
            "tipoVeiculo": "'.$tipoVeiculo.'",
            "planoSatelital": "'.$planoSatelital.'",
            "modalidadeVenda": "'.$modalidadeVenda.'",
            "userNameVendedor": "'.$userNameVendedor.'",
            "signatario": {
                "signatario_software": "'.$signatarioSoftware.'",
                "email_signatario_software": "'.$emailSignatarioSoftware.'",
                "documento_signatario_software": "'.$documentoSignatarioSoftware.'",
                "signatario_hardware": "'.$signatarioHardware.'",
                "email_signatario_hardware": "'.$emailSignatarioHardware.'",
                "documento_signatario_hardware": "'.$documentoSignatarioHardware.'"
            },
			"clientRetiraArmazem": "' . $dadosedit['clientRetiraArmazem'] . '",
			"armazem": "' . $dadosedit['armazem'] . '",
			"responsavelRetirada": "' . $dadosedit['responsavelRetirada'] . '",
			"cpfResponsavelRetirada": "' . $dadosedit['cpfResponsavelRetirada'] . '"
        }';
	}else{
		$body = '{
            "cotacaoId": "'.$cotacaoId.'",
            "inicioVigencia": "'.$inicioVigencia.'",
            "terminoVigencia": "'.$terminoVigencia.'",
            "qtdVeiculos": "'.$qtdVeiculos.'",
            "ID_Produto": "'.$ID_Produto.'",
            "tempoContrato": "'.$tempoContrato.'",
            "cenario_venda": "'.$cenario_venda.'",
            "tipo_pagamento": "'.$tipo_pagamento.'",
            "condicao_pagamento": "'.$condicao_pagamento.'",
            "tipoVeiculo": "'.$tipoVeiculo.'",
            "planoSatelital": "'.$planoSatelital.'",
            "modalidadeVenda": "'.$modalidadeVenda.'",
            "userNameVendedor": "'.$userNameVendedor.'",
            "signatario": {
                "signatario_software": "'.$signatarioSoftware.'",
                "email_signatario_software": "'.$emailSignatarioSoftware.'",
                "documento_signatario_software": "'.$documentoSignatarioSoftware.'",
                "signatario_hardware": "'.$signatarioHardware.'",
                "email_signatario_hardware": "'.$emailSignatarioHardware.'",
                "documento_signatario_hardware": "'.$documentoSignatarioHardware.'"
            },
			"clientRetiraArmazem": "' . $dadosedit['clientRetiraArmazem'] . '",
			"armazem": "' . $dadosedit['armazem'] . '",
			"responsavelRetirada": "' . $dadosedit['responsavelRetirada'] . '",
			"cpfResponsavelRetirada": "' . $dadosedit['cpfResponsavelRetirada'] . '"
        }';

	}
	# Cria instância do CI
    $CI =& get_instance();

	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/UpdateOportunidadeTelevendas?Token=".$token_crm;
			
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
	CURLOPT_POSTFIELDS => $body,
		CURLOPT_HTTPHEADER => array(
		  'Content-Type: application/json'
		),
	  ));
		  

	$response = curl_exec($curl);

	
	curl_close($curl);
	return $response;	   	
	
}

function get_oportunidadeVendedor($value = null)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').'quotes?fetchXml=%3Cfetch%20version%3D%221.0%22%20output-format%3D%22xml-platform%22%20mapping%3D%22logical%22%20distinct%3D%22false%22%3E%3Centity%20name%3D%22quote%22%3E%3Cattribute%20name%3D%22customerid%22%20%2F%3E%3Cattribute%20name%3D%22statecode%22%20%2F%3E%3Cattribute%20name%3D%22quoteid%22%20%2F%3E%3Cattribute%20name%3D%22createdon%22%20%2F%3E%3Cattribute%20name%3D%22statuscode%22%20%2F%3E%3Cattribute%20name%3D%22quotenumber%22%20%2F%3E%3Cattribute%20name%3D%22tz_docusign_status%22%20%2F%3E%3Cattribute%20name%3D%22effectiveto%22%20%2F%3E%3Cattribute%20name%3D%22effectivefrom%22%20%2F%3E%3Cattribute%20name%3D%22tz_valor_total_licenca%22%20%2F%3E%3Cattribute%20name%3D%22tz_valor_total_hardware%22%20%2F%3E%3Cattribute%20name%3D%22tz_analise_credito%22%20%2F%3E%3Corder%20attribute%3D%22createdon%22%20descending%3D%22true%22%20%2F%3E%3Cfilter%20type%3D%22and%22%3E%3Ccondition%20attribute%3D%22statuscode%22%20operator%3D%22ne%22%20value%3D%227%22%20%2F%3E%3Ccondition%20attribute%3D%22createdon%22%20operator%3D%22last-x-days%22%20value%3D%2230%22%20%2F%3E%3C%2Ffilter%3E%3Clink-entity%20name%3D%22contact%22%20from%3D%22contactid%22%20to%3D%22customerid%22%20visible%3D%22false%22%20link-type%3D%22outer%22%20alias%3D%22Cliente_PF%22%3E%3Cattribute%20name%3D%22fullname%22%20%2F%3E%3Cattribute%20name%3D%22zatix_codigocliente%22%20%2F%3E%3Cattribute%20name%3D%22contactid%22%20%2F%3E%3Cattribute%20name%3D%22zatix_cpf%22%20%2F%3E%3C%2Flink-entity%3E%3Clink-entity%20name%3D%22account%22%20from%3D%22accountid%22%20to%3D%22customerid%22%20visible%3D%22false%22%20link-type%3D%22outer%22%20alias%3D%22Cliente_PJ%22%3E%3Cattribute%20name%3D%22name%22%20%2F%3E%3Cattribute%20name%3D%22zatix_codigocliente%22%20%2F%3E%3Cattribute%20name%3D%22accountid%22%20%2F%3E%3Cattribute%20name%3D%22zatix_cnpj%22%20%2F%3E%3C%2Flink-entity%3E%3Clink-entity%20name%3D%22systemuser%22%20from%3D%22systemuserid%22%20to%3D%22owninguser%22%20alias%3D%22br%22%3E%3Cattribute%20name%3D%22fullname%22%20%2F%3E%3Cfilter%20type%3D%22and%22%3E%3Ccondition%20attribute%3D%22internalemailaddress%22%20operator%3D%22eq%22%20value%3D%22'.$value.'%22%20%2F%3E%3C%2Ffilter%3E%3C%2Flink-entity%3E%3C%2Fentity%3E%3C%2Ffetch%3E';

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				

}

function get_oportunidadeVendedorPorData($value, $dataInicial,  $dataFinal)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').'quotes?fetchXml=%3Cfetch%20version=%221.0%22%20output-format=%22xml-platform%22%20mapping=%22logical%22%20distinct=%22false%22%3E%20%3Centity%20name=%22quote%22%3E%20%3Cattribute%20name=%22customerid%22%20/%3E%20%3Cattribute%20name=%22statecode%22%20/%3E%3Cattribute%20name=%22quoteid%22%20/%3E%20%3Cattribute%20name=%22createdon%22%20/%3E%20%3Cattribute%20name=%22statuscode%22%20/%3E%20%3Cattribute%20name=%22quotenumber%22%20/%3E%20%3Cattribute%20name=%22tz_docusign_status%22%20/%3E%20%3Cattribute%20name=%22effectiveto%22%20/%3E%20%3Cattribute%20name=%22effectivefrom%22%20/%3E%20%3Cattribute%20name=%22tz_valor_total_licenca%22%20/%3E%20%3Cattribute%20name=%22tz_valor_total_hardware%22%20/%3E%20%3Cattribute%20name=%22tz_analise_credito%22%20/%3E%20%3Cattribute%20name=%22tz_resultado_analise_credito%22%20/%3E%20%3Corder%20attribute=%22createdon%22%20descending=%22false%22%20/%3E%20%3Cfilter%20type=%22and%22%3E%20%3Ccondition%20attribute=%22statuscode%22%20operator=%22ne%22%20value=%227%22%20/%3E%20%3Ccondition%20attribute=%22createdon%22%20operator=%22on-or-after%22%20value=%22'.$dataInicial.'%22%20/%3E%20%3Ccondition%20attribute=%22createdon%22%20operator=%22on-or-before%22%20value=%22'.$dataFinal.'%22%20/%3E%20%3C/filter%3E%20%3Clink-entity%20name=%22contact%22%20from=%22contactid%22%20to=%22customerid%22%20visible=%22false%22%20link-type=%22outer%22%20alias=%22Cliente_PF%22%3E%20%3Cattribute%20name=%22fullname%22%20/%3E%20%3Cattribute%20name=%22contactid%22%20/%3E%20%3C/link-entity%3E%20%3Clink-entity%20name=%22account%22%20from=%22accountid%22%20to=%22customerid%22%20visible=%22false%22%20link-type=%22outer%22%20alias=%22Cliente_PJ%22%3E%20%3Cattribute%20name=%22name%22%20/%3E%20%3Cattribute%20name=%22accountid%22%20/%3E%20%3C/link-entity%3E%20%3Clink-entity%20name=%22systemuser%22%20from=%22systemuserid%22%20to=%22owninguser%22%20alias=%22br%22%3E%20%3Cfilter%20type=%22and%22%3E%20%3Ccondition%20attribute=%22internalemailaddress%22%20operator=%22eq%22%20value=%22'.$value.'%22%20/%3E%20%3C/filter%3E%20%3C/link-entity%3';

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				

}

function get_oportunidadeCliente($value = null)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').'quotes?fetchXml=%3Cfetch%20version%3D%221.0%22%20output-format%3D%22xml-platform%22%20mapping%3D%22logical%22%20distinct%3D%22false%22%3E%0A%3Centity%20name%3D%22quote%22%3E%0A%3Cattribute%20name%3D%22customerid%22%20%2F%3E%0A%3Cattribute%20name%3D%22statecode%22%20%2F%3E%0A%3Cattribute%20name%3D%22quoteid%22%20%2F%3E%0A%3Cattribute%20name%3D%22createdon%22%20%2F%3E%0A%3Cattribute%20name%3D%22statuscode%22%20%2F%3E%0A%3Cattribute%20name%3D%22quotenumber%22%20%2F%3E%0A%3Cattribute%20name%3D%22tz_docusign_status%22%20%2F%3E%0A%3Cattribute%20name%3D%22effectiveto%22%20%2F%3E%0A%3Cattribute%20name%3D%22effectivefrom%22%20%2F%3E%0A%3Cattribute%20name%3D%22tz_valor_total_licenca%22%20%2F%3E%0A%3Cattribute%20name%3D%22tz_valor_total_hardware%22%20%2F%3E%0A%3Cattribute%20name%3D%22tz_analise_credito%22%20%2F%3E%0A%3Corder%20attribute%3D%22createdon%22%20descending%3D%22true%22%20%2F%3E%0A%3Cfilter%20type%3D%22and%22%3E%3Ccondition%20attribute%3D%22statuscode%22%20operator%3D%22ne%22%20value%3D%227%22%20%2F%3E%0A%20%3Cfilter%20type%3D%22and%22%3E%3Ccondition%20attribute%3D%22customerid%22%20operator%3D%22eq%22%20uitype%3D%22account%22%20value%3D%22%7B'.$value.'%7D%22%20%2F%3E%3Ccondition%20attribute%3D%22tz_docusign_status%22%20operator%3D%22not-null%22%20%2F%3E%3C%2Ffilter%3E%3Ccondition%20attribute%3D%22createdon%22%20operator%3D%22last-x-days%22%20value%3D%2230%22%20%2F%3E%0A%3C%2Ffilter%3E%0A%3Clink-entity%20name%3D%22contact%22%20from%3D%22contactid%22%20to%3D%22customerid%22%20visible%3D%22false%22%20link-type%3D%22outer%22%20alias%3D%22CLIENTE_PF%22%3E%0A%3Cattribute%20name%3D%22fullname%22%20%2F%3E%3Cattribute%20name%3D%22zatix_codigocliente%22%20%2F%3E%0A%3Cattribute%20name%3D%22contactid%22%20%2F%3E%0A%3C%2Flink-entity%3E%0A%3Clink-entity%20name%3D%22account%22%20from%3D%22accountid%22%20to%3D%22customerid%22%20visible%3D%22false%22%20link-type%3D%22outer%22%20alias%3D%22CLIENTE_PJ%22%3E%0A%3Cattribute%20name%3D%22name%22%20%2F%3E%3Cattribute%20name%3D%22zatix_codigocliente%22%20%2F%3E%0A%3Cattribute%20name%3D%22accountid%22%20%2F%3E%0A%3C%2Flink-entity%3E%0A%3Clink-entity%20name%3D%22systemuser%22%20from%3D%22systemuserid%22%20to%3D%22owninguser%22%3E%0A%3Cattribute%20name%3D%22fullname%22%20%2F%3E%0A%3C%2Flink-entity%3E%0A%3C%2Fentity%3E%0A%3C%2Ffetch%3E';
	
	$curl = curl_init();
	

	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
	// echo json_encode($response);
	curl_close($curl);
	
	return $response;				

}

function get_oportunidadeClientePorData($value, $dataInicial,  $dataFinal)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request    = $CI->config->item('base_url_crm').'quotes?fetchXml=%3Cfetch%20version%3D%221.0%22%20output-format%3D%22xml-platform%22%20mapping%3D%22logical%22%20distinct%3D%22false%22%3E%0A%20%20%3Centity%20name%3D%22quote%22%3E%0A%20%20%20%20%3Cattribute%20name%3D%22customerid%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22statecode%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22quoteid%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22createdon%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22statuscode%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22quotenumber%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_docusign_status%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22effectiveto%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22effectivefrom%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_valor_total_licenca%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_valor_total_hardware%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_analise_credito%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_resultado_analise_credito%22%20%2F%3E%0A%20%20%20%20%3Corder%20attribute%3D%22createdon%22%20descending%3D%22true%22%20%2F%3E%0A%20%20%20%20%3Cfilter%20type%3D%22and%22%3E%0A%20%20%20%20%20%20%3Cfilter%20type%3D%22and%22%3E%0A%20%20%20%20%20%20%20%20%3Ccondition%20attribute%3D%22customerid%22%20operator%3D%22eq%22%20uitype%3D%22account%22%20value%3D%22%7B'.$value.'%7D%22%20%2F%3E%0A%20%20%20%20%20%20%20%20%3Ccondition%20attribute%3D%22tz_docusign_status%22%20operator%3D%22not-null%22%20%2F%3E%0A%20%20%20%20%20%20%3C%2Ffilter%3E%0A%20%20%20%20%20%20%3Ccondition%20attribute%3D%22createdon%22%20operator%3D%22on-or-after%22%20value%3D%22'.$dataInicial.'%22%20%2F%3E%0A%20%20%20%20%20%20%3Ccondition%20attribute%3D%22createdon%22%20operator%3D%22on-or-before%22%20value%3D%22'.$dataFinal.'%22%20%2F%3E%0A%20%20%20%20%3C%2Ffilter%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22contact%22%20from%3D%22contactid%22%20to%3D%22customerid%22%20visible%3D%22false%22%20link-type%3D%22outer%22%20alias%3D%22CLIENTE_PF%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22fullname%22%20%2F%3E%0A%20%20%20%20%20%20%3Cattribute%20from%3D%22contactid%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22account%22%20from%3D%22accountid%22%20to%3D%22customerid%22%20visible%3D%22false%22%20link-type%3D%22outer%22%20alias%3D%22CLIENTE_PJ%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22name%22%20%2F%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22accountid%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22systemuser%22%20from%3D%22systemuserid%22%20to%3D%22owninguser%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22fullname%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%3C%2Fentity%3E%0A%3C%2Ffetch%3E';
	
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				

}

function get_Vendedores()
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').'/quotes?fetchXml=%3Cfetch%20version=%221.0%22%20output-format=%22xml-platform%22%20mapping=%22logical%22%20distinct=%22false%22%3E%20%3Centity%20name=%22quote%22%3E%20%3Cattribute%20name=%22customerid%22/%3E%20%3Cattribute%20name=%22statecode%22/%3E%20%3Cattribute%20name=%22quoteid%22/%3E%20%3Cattribute%20name=%22createdon%22/%3E%20%3Cattribute%20name=%22ownerid%22/%3E%20%3Cattribute%20name=%22statuscode%22/%3E%20%3Cattribute%20name=%22quotenumber%22/%3E%20%3Cattribute%20name=%22tz_docusign_status%22/%3E%20%3Cattribute%20name=%22effectiveto%22/%3E%20%3Cattribute%20name=%22effectivefrom%22/%3E%20%3Cattribute%20name=%22tz_valor_total_licenca%22/%3E%20%3Cattribute%20name=%22tz_valor_total_hardware%22/%3E%20%3Cattribute%20name=%22tz_analise_credito%22/%3E%20%3Corder%20attribute=%22createdon%22%20descending=%22true%22/%3E%20%3Cfilter%20type=%22and%22%3E%20%3Ccondition%20attribute=%22statuscode%22%20operator=%22ne%22%20value=%227%22/%3E%20%3Ccondition%20attribute=%22createdon%22%20operator=%22this-month%22%20/%3E%20%3C/filter%3E%20%3Clink-entity%20name=%22contact%22%20from=%22contactid%22%20to=%22customerid%22%20visible=%22false%22%20link-type=%22outer%22%20alias=%22Cliente_PF%22%3E%20%3Cattribute%20name=%22fullname%22/%3E%20%3Cattribute%20name=%22zatix_codigocliente%22/%3E%20%3C/link-entity%3E%20%3Clink-entity%20name=%22account%22%20from=%22accountid%22%20to=%22customerid%22%20visible=%22false%22%20link-type=%22outer%22%20alias=%22Cliente_PJ%22%3E%20%3Cattribute%20name=%22name%22/%3E%20%3Cattribute%20name=%22zatix_codigocliente%22/%3E%20%3C/link-entity%3E%20%3Clink-entity%20name=%22systemuser%22%20from=%22systemuserid%22%20to=%22owninguser%22%20alias=%22user_vendedor%22%3E%20%3Cattribute%20name=%22lastname%22/%3E%20%3Cattribute%20name=%22fullname%22/%3E%20%3Cattribute%20name=%22firstname%22/%3E%20%3Cfilter%20type=%22and%22/%3E%20%3C/link-entity%3E%20%3C/entity%3E%20%3C/fetch%3E';
	
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
	// echo json_encode($response);
	curl_close($curl);
	
	return $response;				

}

function get_VendedoresPorData($value, $dataInicial,  $dataFinal)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request    = $CI->config->item('base_url_crm').'quotes?fetchXml=%3Cfetch%20version=%221.0%22%20output-format=%22xml-platform%22%20mapping=%22logical%22%20distinct=%22false%22%3E%20%3Centity%20name=%22quote%22%3E%20%3Cattribute%20name=%22customerid%22%20/%3E%20%3Cattribute%20name=%22statecode%22%20/%3E%20%3Cattribute%20name=%22quoteid%22%20/%3E%20%3Cattribute%20name=%22createdon%22%20/%3E%20%3Cattribute%20name=%22statuscode%22%20/%3E%20%3Cattribute%20name=%22quotenumber%22%20/%3E%20%3Cattribute%20name=%22tz_docusign_status%22%20/%3E%20%3Cattribute%20name=%22effectiveto%22%20/%3E%20%3Cattribute%20name=%22effectivefrom%22%20/%3E%20%3Cattribute%20name=%22tz_valor_total_licenca%22%20/%3E%20%3Cattribute%20name=%22tz_valor_total_hardware%22%20/%3E%20%3Cattribute%20name=%22tz_analise_credito%22%20/%3E%20%3Cattribute%20name=%22tz_resultado_analise_credito%22%20/%3E%20%3Corder%20attribute=%22createdon%22%20descending=%22true%22%20/%3E%20%3Cfilter%20type=%22and%22%3E%20%3Cfilter%20type=%22and%22%3E%20%3Ccondition%20attribute=%22customerid%22%20operator=%22eq%22%20uitype=%22account%22%20value=%22'.$value.'%22%20/%3E%20%3Ccondition%20attribute=%22tz_docusign_status%22%20operator=%22not-null%22%20/%3E%20%3C/filter%3E%20%3Ccondition%20attribute=%22createdon%22%20operator=%22on-or-after%22%20value=%22'.$dataInicial.'%22%20/%3E%20%3Ccondition%20attribute=%22createdon%22%20operator=%22on-or-before%22%20value=%22'.$dataFinal.'%22%20/%3E%20%3C/filter%3E%20%3Clink-entity%20name=%22contact%22%20from=%22contactid%22%20to=%22customerid%22%20visible=%22false%22%20link-type=%22outer%22%20alias=%22CLIENTE_PF%22%3E%20%3Cattribute%20name=%22fullname%22%20/%3E%20%3C/link-entity%3E%20%3Clink-entity%20name=%22account%22%20from=%22accountid%22%20to=%22customerid%22%20visible=%22false%22%20link-type=%22outer%22%20alias=%22CLIENTE_PJ%22%3E%20%3Cattribute%20name=%22name%22%20/%3E%20%3C/link-entity%3E%20%3C/entity%3E%20%3C/fetch%3E';
	
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				

}

function to_gerarPedido($idCotacao = null, $loginUsuario)
{
	
	# Cria instância do CI
    $CI =& get_instance();
		
	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/GerarPedidoTelevendas?idCotacao=".$idCotacao."&Token=".$token_crm."&userNameVendedor=".$loginUsuario;

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Content-length: 0';

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 100,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_CUSTOMREQUEST => 'POST',
	  ));
	  
	  
	$response = curl_exec($curl);

	if (curl_errno($curl)) {
		$error_code = curl_errno($curl);

		// Verifica se o erro é de timeout
		if ($error_code == CURLE_OPERATION_TIMEOUTED) {
			// json string
			$response = '{"status": false, "Message": "Esgotado tempo limite de tentativa de conexão com o servidor. Por favor, tente mais tarde!"}';
		} else {
			// Mensagem de erro padrão para outros tipos de erro
			$response = false;
		}
	}
	  
	curl_close($curl);
				
	return $response;
}


function to_enviarAssinatura($idCotacao = null, $loginUsuario)
{
	
	# Cria instância do CI
    $CI =& get_instance();
		
	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/EnviarContratoAsinaturaDocusign?IdCotacao=".$idCotacao."&Token=".$token_crm."&userNameVendedor=".$loginUsuario;

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Content-length: 0';

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_CUSTOMREQUEST => 'POST',
	  ));
	  
	  $response = curl_exec($curl);
	  
	  curl_close($curl);

	  echo $response;				

}

function to_ganhar($idCotacao = null)
{
	
	# Cria instância do CI
    $CI =& get_instance();
		
	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/GanharCotacaoTelevendas?idCotacao=".$idCotacao."&Token=".$token_crm;	

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Content-length: 0';

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_CUSTOMREQUEST => 'POST',
	  ));
	  
	  $response = curl_exec($curl);
	  
	  curl_close($curl);

	  echo $response;				

}



function to_enviarAnexoCliente($body)
{
	$Documento = $body['Documento'];
	$customerid = $body['customerid'];
	$Assunto =  $body['Assunto'];
	$Descricao =  $body['Descricao'];
	$NomeArquivo =  $body['NomeArquivo'];
	$Mimetype =  $body['Mimetype'];
	$Extensao =  $body['Extensao'];
	$DocumentBase64 =  $body['DocumentBase64'];
	
	# Cria instância do CI
    $CI =& get_instance();
	$CI->load->model('log_shownet');	
	$token_crm 	= $CI->config->item('token_crm');

	//para registro de log
	$id_user = $CI->auth->get_login_dados('user');
	$id_user = (int) $id_user;

	# configuração para a API
    $request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/customers/CriarAnexoClienteTelevenda?Token=".$token_crm;	
	$curl = curl_init();

	$fields = '{
	"Assunto": "'.$Assunto.'",
	"Descricao": "'.$Descricao.'",
	"NomeArquivo": "'.$NomeArquivo.'",
	"Mimetype": "'.$Mimetype.'",
	"Extensao": "'.$Extensao.'",
	"DocumentBase64": "'.$DocumentBase64.'",';

	if(isset($customerid) && $customerid != ""){
		$fields .= '"customerid": "'.$customerid.'"';
		$usuario = $customerid;
	}else if(isset($Documento) && $Documento != ""){
		$fields .= '"Documento": "'.$Documento.'"';
		$usuario = $Documento;
	}
	$fields .= '}';

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Accept: application/json'
		),
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $fields
	));
	  
	$response = curl_exec($curl);
	
	curl_close($curl);
	//$CI->log_shownet->gravar_log($id_user, 'adicionando anexo cliente crm', $usuario, 'criar', 'null', $DocumentBase64);

	return $response;
}
function to_enviarAnexoCotacao($body, $loginUsuario)
{
	# Cria instância do CI
    $CI =& get_instance();
	$CI->load->model('log_shownet');
	$token_crm 	= $CI->config->item('token_crm');

	//para registro de log
	$id_user = $CI->auth->get_login_dados('user');
	$id_user = (int) $id_user;

	# configuração para a API
    $request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/CriarAnexoCotacaoTelevenda?Token=".$token_crm."&userNameVendedor=".$loginUsuario;	
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Accept: application/json'
		),
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($body)
	));
	  
	$response = curl_exec($curl);
	
	curl_close($curl);
	$idCotacao = $body['idCotacao'];
	$CI->log_shownet->gravar_log($id_user, 'adicionando anexo cotação', $idCotacao, 'criar', 'null', "arquivo de grande extensão");
	return $response;
}


//função para gerar senha aletatório para salvar na API do MHS
function getRandom($length){
        
	$str = 'abcdefghijklmnopqrstuvwzyz';
	$str1= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$str2= '0123456789';
	$shuffled = str_shuffle($str);
	$shuffled1 = str_shuffle($str1);
	$shuffled2 = str_shuffle($str2);
	$total = $shuffled.$shuffled1.$shuffled2;
	$shuffled3 = str_shuffle($total);
	$result= substr($shuffled3, 0, $length);

	return $result;

}


function to_IncluiCentralCliente($Usuario = null, $Nome = null, $IP = null, $Porta = null, $CNPJ = null)
{
  	# Cria instância do CI
	$CI = & get_instance();	
		
	$url = "http://10.8.0.18:8092/service2.asmx?op=IncluiCentralCliente";
	//$url = $CI->config->item('url_IncluiCentral');

	//chama a função para gerar senha aleatória
	$senha = getRandom(8);

	
    # Cria chamada SOAP
    $soap_request = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:web="http://microsoft.com/webservices/">
		<soap:Header/>
		<soap:Body>
		<web:IncluiCentralCliente>		  
			<web:Usuario>' . $Usuario . '</web:Usuario>		  
			<web:senha>' . $senha . '</web:senha>		  
			<web:Nome>' . $Nome . '</web:Nome>		  
			<web:IP>' . $IP . '</web:IP>
			<web:Porta>' . $Porta . '</web:Porta>
			<!--Optional:-->
			<web:IPPrincipal>'. $IP.'</web:IPPrincipal>
			<web:PortaPrincipal>'. $Porta .'</web:PortaPrincipal>		  
			<web:CNPJ>' . $CNPJ . '</web:CNPJ>
		</web:IncluiCentralCliente>
		</soap:Body>
      </soap:Envelope>';
    
    # Executa chamada via CURL
    $ch = curl_init($url);

	
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$soap_request");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $xmlData = str_replace("soap:", "", $output);
    libxml_use_internal_errors(true);
    $result = simplexml_load_string($xmlData, "SimpleXMLElement", LIBXML_NOCDATA);
    libxml_get_errors();
    $results = $result->Body->IncluiCentralClienteResponse->IncluiCentralClienteResult;
	
    return $results;
  
}

if (! function_exists('isJSON')) {
	function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true));
	}
}

function get_afID($idCotacao = null)
{
	
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API  
	$request 	= $CI->config->item('base_url_crm').'tz_afs?$select=tz_afid,tz_numero_af&$filter=_tz_cotacaoid_value%20eq%20'.$idCotacao;
	
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);

		
	curl_close($curl);
	
	return $response;				

}


function get_configurometro($idVeiculo = null){
	$CI =& get_instance();

	$request = $CI->config->item('base_url_api_crmintegration')."/crmisv/wsII/Paginas/configuracao.aspx?id=".$idVeiculo;	

	echo $request;
}


function getResumoCotacao($idCotacao){

	$CI =& get_instance();

	$token_crm = $CI->config->item('token_crm');

	$request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/GetResumoCotacao?idCotacao=".$idCotacao."&Token=".$token_crm;	

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

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}

function listarProdutosComposicao($nomeProduto, $numeroProduto){

	$CI =& get_instance();
	
	$request 	= $CI->config->item('base_url_crm').'products?';

	$params = array(
    '$select' => 'name,_pricelevelid_value,pricelevelid,productnumber', 
    '$filter' => "contains(name, '".addslashes($nomeProduto)."') and (_pricelevelid_value ne null) or contains(productnumber, '".addslashes($numeroProduto)."') and (_pricelevelid_value ne null)");
	
	$request .= http_build_query($params); 
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $request);
	curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
		"Content-Type: application/json",
		'OData-Version: 4.0',
		'Accept: application/json',
		'OData-MaxVersion: 4.0',
		'Prefer: return=representation',
		'If-Match: *'
	));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	// remove o certificado ssl (apenas para desenvolvimento)
	if(defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	}
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
	curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
	curl_setopt($ch, CURLOPT_USERPWD, $CI->config->item('username_crm').':'.$CI->config->item('password_crm'));
	// Set parametros

	$response = curl_exec($ch);
	$response = json_decode($response, true);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$error = curl_error($ch); 

	$result = array();

		foreach($response['value'] as $produto){
			$result[] = array(
				'id' => $produto['productid'],
				'text' => $produto['name'] . ' (' . $produto['productnumber'].')',
				'idProductNumber' => $produto['productnumber'],
			);
		}
	
	curl_close($ch);

	echo json_encode(
		array(
			'status' => $status_code,
			'results' => $result
		)
	);

	if($status_code === 200){
		return (object) array("code" => $status_code, "data" => $response, "error" => null);
	}else{
		return (object) array("code" => $status_code, "data" => null, "error" => $error); 
	}
}

function addSubitemComposicao($idCotacao, $produto, $tipo, $loginUsuario, $quantidade){
	$CI =& get_instance();

	$token_crm = $CI->config->item('token_crm');

	$qtdParamUrl = $quantidade ? "&quantidade=".$quantidade : "";
	$request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/AddSubItemComposicaoCotacao?idcotacao=".$idCotacao."&idProduct=".$produto."&tipoSubItem=".$tipo."&Token=".$token_crm."&userNameVendedor=".$loginUsuario.$qtdParamUrl;

	$curl = curl_init();

	$post_fields = '';
	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $post_fields,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	$CI->load->model('log_shownet');
	//para registro de log
	$id_user = $CI->auth->get_login_dados('user');
	$id_user = (int) $id_user;

	if($statusCode == 200){
		$CI->log_shownet->gravar_log($id_user, 'adicionando subitem cotacao', $idCotacao, 'criar', 'null', $produto);
	}

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado
		)
	);
}



function to_enviarAnexoClienteByCustomerId($body)
{
	$customerid = $body['customerid'];
	$Assunto =  $body['Assunto'];
	$Descricao =  $body['Descricao'];
	$NomeArquivo =  $body['NomeArquivo'];
	$Mimetype =  $body['Mimetype'];
	$Extensao =  $body['Extensao'];
	$filebytes =  $body['filebytes'];
	$DocumentBase64 =  $body['DocumentBase64'];
	
	# Cria instância do CI
    $CI =& get_instance();
		
	$token_crm 	= $CI->config->item('token_crm');
	# configuração para a API
    $request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/customers/CriarAnexoClienteTelevenda?Token=".$token_crm;	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Accept: application/json'
		),
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
			"customerid": "'.$customerid.'",
			"Assunto": "'.$Assunto.'",
			"Descricao": "'.$Descricao.'",
			"NomeArquivo": "'.$NomeArquivo.'",
			"Mimetype": "'.$Mimetype.'",
			"Extensao": "'.$Extensao.'",
			"filebytes": "'.$filebytes.'",
			"DocumentBase64": "'.$DocumentBase64.'"
		}'
	));
	  
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}


function get_anexosCustomer($parametro)
{
	# Cria instância do CI
    $CI =& get_instance();
		
	$token_crm 	= $CI->config->item('token_crm');
	# configuração para a API
    $request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/customers/GetDocsClientes?Token=".$token_crm."&parametro=".$parametro;	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		],
		CURLOPT_CUSTOMREQUEST => 'GET'
	));
	  
	$response = curl_exec($curl);
	// $resultado = json_decode(curl_exec($curl), true);
	
	curl_close($curl);
	return $response;
	// return $resultado;
}


function get_downloadAnexo($IdAnnotationn)
{
	# Cria instância do CI
    $CI =& get_instance();
		
	$token_crm 	= $CI->config->item('token_crm');
	# configuração para a API
    $request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/customers/BaixarDocumentoTelevendas?Token=".$token_crm."&IdAnnotationn=".$IdAnnotationn;	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		],
		CURLOPT_CUSTOMREQUEST => 'GET'
	));
	  
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function atualizaCartaoDeCredito($body, $loginUsuario){

		$Bandeira = $body['Bandeira'];
		$Numero_Cartao =  $body['Numero_Cartao'];
		$Cod_Seguranca =  $body['Cod_Seguranca'];
		$Nome_Impresso_Cartao =  $body['Nome_Impresso_Cartao'];
		$Validade_Cartao_Mes =  $body['Validade_Cartao_Mes'];
		$Validade_Cartao_Ano =  $body['Validade_Cartao_Ano'];
		$id_Cotacao =  $body['id_Cotacao'];

        $CI =& get_instance();
		$CI->load->model('log_shownet');
		
		//para registro de log
		$id_user = $CI->auth->get_login_dados('user');
		$id_user = (int) $id_user;

		$token_crm 	= $CI->config->item('token_crm');

        # URL configurada para a API
        $request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/AtualizarDadosCartaoCredito?token=".$token_crm."&userNameVendedor=".$loginUsuario;
        
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
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
            CURLOPT_POSTFIELDS =>'{
                "Bandeira": "'.$Bandeira.'",
				"Numero_Cartao": "'.$Numero_Cartao.'",
				"Cod_Seguranca": "'.$Cod_Seguranca.'",
				"Nome_Impresso_Cartao": "'.$Nome_Impresso_Cartao.'",
				"Validade_Cartao_Mes": "'.$Validade_Cartao_Mes.'",
				"Validade_Cartao_Ano": "'.$Validade_Cartao_Ano.'",
				"id_Cotacao": "'.$id_Cotacao.'",
			}'
        
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$CI->log_shownet->gravar_log($id_user, 'atualizar cartão CRM', $id_Cotacao, 'atualizar', 'null', $body);

        curl_close($curl);

        echo json_encode(
            array(
                'status' => $statusCode,
                'dados'       => $resultado,
            )
        );
}

function get_tipoVeiculoCotacaoId($idCotacao){
	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
    $request 	= $CI->config->item('base_url_crm').'tz_tipo_veiculo_cotacaos?$select=_tz_cotacaoid_value&$filter=_tz_cotacaoid_value%20eq%20'.$idCotacao.'';
		
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);


	if (curl_error($curl))  throw new Exception(curl_error($curl));
    $resultado = json_decode(curl_exec($curl), true);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results'       => $resultado,
			'pagination'    => [
				'more'      => false,
			]
		)
	);
}

function get_listarOperadorasPorData($dataInicial, $dataFinal){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."operadoras/listarOperadorasPorData?dataInicial=".$dataInicial."&dataFinal=".$dataFinal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'id' => $value['id'],
    	        'nome' => $value['nome'],
    	        'dataCadastro' => $value['dataCadastro'],
				'dataUpdate' => $value['dataUpdate'],
				'status' => $value['status'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	if ($dataInicial != '' && $dataFinal != ''){
		echo json_encode(
			array(
				'status' => $statusCode,
				'results' => $resultado

			)
		);
	}else{
		return $result;
	}
}

function get_listarOperadoras($all = false){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."operadoras/listarOperadoras";

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'id' => $value['id'],
    	        'nome' => $value['nome'],
    	        'dataCadastro' => $value['dataCadastro'],
				'dataUpdate' => $value['dataUpdate'],
				'status' => $value['status'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);
	
	if ($all == true){
		echo json_encode(
			array(
				'status' => $statusCode,
				'results' => $resultado

			)
		);
	}else{
		return $result;
	}
}


function get_resumoCliente($documento){

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
	# Cria instância do CI   		
	$token_crm 	= $CI->config->item('token_crm');

	$request = $CI->config->item('base_url_api_crmintegration').'/crmintegration/api/customers/ResumoClienteERP?Token='.$token_crm.'&Documento='.$documento;
	
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

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'dados'  => $resultado
		)
	);
}

function to_cadastrarOperadora($body){

	$nome = $body['nome'];

	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."operadoras/cadastrarInfoOperadora";

	$token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
    $headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => '{
		"nome": "'.$nome.'"
	}',
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

function to_atualizaCadOperadora($body){

	$idOperadora = $body['idOperadora'];
	$nome = $body['nome'];
	$status = $body['status'];

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."operadoras/atualizarCadastroOperadora";

	$token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
    $headers[] = 'Authorization: Bearer '.$token;
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'PATCH',
	CURLOPT_POSTFIELDS => '{
		"idOperadora": "'.$idOperadora.'",
		"nome": "'.$nome.'",
		"status": "'.$status.'"
	}',
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


	function to_addDescontoCotacao($idItem, $tipoSubItem, $desconto, $loginUsuario){
		$CI =& get_instance();
	

		$token_crm = $CI->config->item('token_crm');
	
		$request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/AddDescontoComposicaoCotacao?idItem=".$idItem."&tipoSubItem=".$tipoSubItem."&desconto=".$desconto."&Token=".$token_crm."&userNameVendedor=".$loginUsuario;
	
		$curl = curl_init();
	
		$post_fields = '';
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $post_fields,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
		//para registro de log
		$id_user = $CI->auth->get_login_dados('user');
		$id_user = (int) $id_user;

		if ($resultado) {
			$CI->log_shownet->gravar_log($id_user, 'desconto cotacao CRM', $idItem, 'criar', 'null', $desconto);
		}
		
	
		echo json_encode(
			array(
				'status' => $statusCode,
				'results' => $resultado
			)
		);
	}

function get_listarLinhas($idOperadora, $retorno = false){

	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."linhas/listarLinhasOperadoras?idOperadora=".$idOperadora;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'id' => $value['id'],
				'linha' => $value['linha'],
				'ultimoCcid' => $value['ultimoCcid'],
				'idOperadora' => $value['idOperadora'],
				'idEmpresa' => $value['idEmpresa'],
				'idFornecedor' => $value['idFornecedor'],
				'statusOperadora' => $value['statusOperadora'],
				'numeroConta' => $value['numeroConta'],
				'ultimoSerialEquipamento' => $value['ultimoSerialEquipamento'],
				'ultimoStatusCrm' => $value['ultimoStatusCrm'],
				'ultimaComunicacaoRadius' => $value['ultimaComunicacaoRadius'],
				'dataCadastro' => $value['dataCadastro'],
				'dataUpdate' => $value['dataUpdate'],
				'status' => $value['status'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	if (!$retorno){
		echo json_encode(
			array(
				'status' => $statusCode,
				'results' => $result
			)
		);
	}else{
		return $result;
	}
}	
function to_cadastrarLinha($body){

		$linha = $body['linha'];
		$ultimoCcid = $body['ultimoCcid'];
		$idOperadora = $body['idOperadora'];
		$idEmpresa = $body['idEmpresa'];
		$idFornecedor = $body['idFornecedor'];
		$statusOperadora = $body['statusOperadora'];
		$numeroConta = $body['numeroConta'];
		$ultimoSerialEquipamento = $body['ultimoSerialEquipamento'];

		$CI =& get_instance();


		$request = $CI->config->item('url_api_shownet_rest')."linhas/cadastrarLinha";

		$token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
	    $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
			"linha": "'.$linha.'",
			"ultimoCcid": "'.$ultimoCcid.'",
			"idOperadora": "'.$idOperadora.'",
			"idEmpresa": "'.$idEmpresa.'",
			"idFornecedor": "'.$idFornecedor.'",
			"statusOperadora": "'.$statusOperadora.'",
			"numeroConta": "'.$numeroConta.'",
			"ultimoSerialEquipamento": "'.$ultimoSerialEquipamento.'"			
		}',
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

function to_atualizaCadLinha($body){

	$idLinha = $body['idLinha'];
	$status = $body['status'];
	$linha = $body['linha'];
	$ultimoCcid = $body['ultimoCcid'];
	$idOperadora = $body['idOperadora'];
	$idEmpresa = $body['idEmpresa'];
	$idFornecedor = $body['idFornecedor'];
	$statusOperadora = $body['statusOperadora'];
	$numeroConta = $body['numeroConta'];
	$ultimoSerialEquipamento = $body['ultimoSerialEquipamento'];



	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."linhas/editarCadastroLinha";

	$token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
    $headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'PATCH',
	CURLOPT_POSTFIELDS => '{
		"idLinha": "'.$idLinha.'",
		"status": "'.$status.'",
		"linha": "'.$linha.'",
		"ultimoCcid": "'.$ultimoCcid.'",
		"idOperadora": "'.$idOperadora.'",
		"idEmpresa": "'.$idEmpresa.'",
		"idFornecedor": "'.$idFornecedor.'",
		"statusOperadora": "'.$statusOperadora.'",
		"numeroConta": "'.$numeroConta.'",
		"ultimoSerialEquipamento": "'.$ultimoSerialEquipamento.'"
	}',
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

function to_atualizaStatusLinha($body){

	$idLinha = $body['idLinha'];
	$status = $body['status'];
	
	$CI =& get_instance();
		
	$request = $CI->config->item('url_api_shownet_rest')."linhas/alterarStatusLinha";
	
	$token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
    $headers[] = 'Authorization: Bearer '.$token;
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'PATCH',
	CURLOPT_POSTFIELDS => '{
		"idLinha": "'.$idLinha.'",
		"status": "'.$status.'"
	}',
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

	function to_removeDocumento($idDocumento){
		# Cria instância do CI
    	$CI =& get_instance();

		$CI->load->model('log_shownet');
		//para registro de log
		$id_user = $CI->auth->get_login_dados('user');
		$id_user = (int) $id_user;


		$username 	= $CI->config->item('username_crm');
		$passwd		= $CI->config->item('password_crm');	

		# configuração para a API
    	$request = $CI->config->item('base_url_crm')."annotations(".$idDocumento.")";

		$curl = curl_init();

		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Content-length: 0';

		curl_setopt_array($curl, [
			CURLOPT_URL            => $request,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
			CURLOPT_USERPWD        => "{$username}:{$passwd}",
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_CUSTOMREQUEST => 'DELETE',
		]);
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$response = curl_exec($curl);
		$responseInfo = curl_getinfo($curl);


		curl_close($curl);

		$dadosAntigos = get_anexosCustomer($idDocumento) ? get_anexosCustomer($idDocumento) : to_getDocumentosCotacao($idDocumento) ;
		if ($dadosAntigos) {
			$CI->log_shownet->gravar_log($id_user, 'removendo anexos', $idDocumento, 'deletar', $dadosAntigos, 'null');
		}

		if ($responseInfo['http_code'] != 204) {
			
			return $response = json_decode($response, true);
		}else{
			echo json_encode(
				array(
					'status' => $responseInfo['http_code'],
					'dados'       => $response,
				)
			);

		}	

	}



function to_removeSubItem($tipoSubItemId, $idItem){
	# Cria instância do CI
    $CI =& get_instance();

	$CI->load->model('log_shownet');
	//para registro de log
	$id_user = $CI->auth->get_login_dados('user');
	$id_user = (int) $id_user;

	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
    $request = $CI->config->item('base_url_crm')."tz_".$tipoSubItemId."(".$idItem.")";

	$curl = curl_init();

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Content-length: 0';

	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_CUSTOMREQUEST => 'DELETE',
	]);

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$response = curl_exec($curl);
	$responseInfo = curl_getinfo($curl);
	
	curl_close($curl);

	$dadosAntigos = array(
		'tipoSubItemId' => $tipoSubItemId,
		'idItem'       => $idItem,
	);
	$CI->log_shownet->gravar_log($id_user, 'removendo subitem CRM', $idItem, 'deletar', $dadosAntigos, 'null');

	if ($responseInfo['http_code'] != 204) {
		
		return $response = json_decode($response, true);
	}else{
		echo json_encode(
			array(
				'status' => $responseInfo['http_code'],
				'dados'       => $response,
			)
		);
	}	
}

function to_urlTokenNode(){
		$CI =& get_instance();

    	$url = $CI->config->item('base_url_relatorios');
    	$token = $CI->config->item('token_acesso_node'); 
	
    	echo json_encode(array('url' => $url, 'token' => $token));
}

function get_listarServicos(){

	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."servicosTelefonia/listarTodosServicosTelefonia";

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'id' => $value['id'],
				'text' => $value['nome'],
				'servicoOperadora' => $value['servicoOperadora'],
				'idFornecedor' => $value['idFornecedor'],
				'dataCadastro' => $value['dataCadastro'],
				'dataUpdate' => $value['dataUpdate'],
				'status' => $value['status'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return $result;
}	

function get_listarItensFatura($idFatura){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."faturaItem/listarFaturaItens?idFatura=".$idFatura;

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

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado

		)
	);

}

function to_alterarStatusItemFatura($body){
	$idItem = $body['idItem'];
	$status = $body['status'];

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'faturaItem/alterarStatusFaturaItem';

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
		CURLOPT_POSTFIELDS => '{
			"idItem": "'.$idItem.'",
			"status": "'.$status.'"
		}',
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

function to_cadastrarItemFatura($body){

	$idFatura = $body['idFatura'];
	$idServico = $body['idServico'];
	$numeroLinha = $body['numeroLinha'];
	$valor = $body['valor'];

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."faturaItem/cadastrarFaturaItem";

	$token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
    $headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
			"idFatura": "'.$idFatura.'",
			"idServico": "'.$idServico.'",
			"numeroLinha": "'.$numeroLinha.'",
			"valor": "'.$valor.'"	
		}',
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

function to_atualizaCadItem($body){

	$idItem = $body['idItem'];
	$idFatura = $body['idFatura'];
	$idServico = $body['idServico'];
	$numeroLinha = $body['numeroLinha'];
	$valor = $body['valor'];
	$status = $body['status'];
	
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."faturaItem/editarFaturaItem";
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'PUT',
	CURLOPT_POSTFIELDS => '{
		"idItem": "'.$idItem.'",
		"idFatura": "'.$idFatura.'",
		"idServico": "'.$idServico.'",
		"numeroLinha": "'.$numeroLinha.'",
		"valor": "'.$valor.'",
		"status": "'.$status.'"
	}',
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

function to_cadastrarFaturaEItens($body){

	$idOperadora = $body['idOperadora'];
	$mesReferencia = $body['mesReferencia'];
	$dataInicio = $body['dataInicio'];
	$dataFim = $body['dataFim'];
	$vencimento = $body['vencimento'];
	$numeroConta = $body['numeroConta'];
	$itens = $body['itens'];

	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'fatura/cadastrarFaturaEItens';

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
		CURLOPT_POSTFIELDS => '{
			"idOperadora": "'.$idOperadora.'",
			"mesReferencia": "'.$mesReferencia.'",
			"dataInicio": "'.$dataInicio.'",
			"dataFim": "'.$dataFim.'",
			"vencimento": "'.$vencimento.'",
			"numeroConta": "'.$numeroConta.'",
			"itens": '.json_encode($itens).'
		}',
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

function buscarEmpresas($nome = ""){
	$CI = &get_instance();

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

	return json_encode(
		array(
			'status' => $statusCode,
			'results'       => $result,
			'pagination'    => [
				'more'      => false,
			]
		)
	);
}

function buscarFormasPagamento($idEmpresa = ""){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'formaPagamento/listarFormaPagamentos?idEmpresa='.$idEmpresa;

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

	return array(
		'status' => $statusCode,
		'results'       => $resultado,
		'pagination'    => [
			'more'      => false,
		]
	);
}

function adicionarFormaPagamento($empresa, $cliente, $prazo){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'formaPagamento/cadastrarFormaPagamento';

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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => '{
			"idEmpresa": "'.$empresa.'",
			"idCliente": "'.$cliente.'",
			"tempo": "'.$prazo.'"
		}',
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$result = array();
		
	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $resultado
	);
}

function editarFormaPagamento($id, $empresa, $cliente, $prazo, $status){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'formaPagamento/editarFormaPagamento';

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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => '{
			"idFormaPagamento": "'.$id.'",
			"idEmpresa": "'.$empresa.'",
			"idCliente": "'.$cliente.'",
			"tempo": "'.$prazo.'",
			"status": "'.$status.'"
		}',
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$result = array();
		
	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $resultado
	);
}

function editarStatusFormaPagamento($id, $status){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'formaPagamento/alterarStatusFormaPagamento';

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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => '{
			"idFormaPagamento": "'.$id.'",
			"status": "'.$status.'"
		}',
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$result = array();
		
	curl_close($curl);

	return json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado
		)
	);
}

	function to_cadastrarMovimentoEItens($body){

		$responsavel = $body['responsavel'];
		$dataMovimento = date("d/m/Y");
		$idEmpresa = $body['idEmpresa'];
		$tipoEmpresa = $body['tipoEmpresa'];
		$idCliente = $body['idCliente'];
		$idSetor = $body['idSetor'];
		$idTipoMovimento = $body['idTipoMovimento'];
		$idTransportador = $body['idTransportador'];
		$qutVolumes = $body['qutVolumes'];
		$observacao = $body['observacao'];
		$cep = $body['cep'];
		$rua = $body['rua'];
		$bairro = $body['bairro'];
		$cidade = $body['cidade'];
		$uf = $body['uf'];
		$regiao = $body['regiao'];
		$itens = $body['itens'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'logistica/cadastrarMovimentoExpedicaoEItens';

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
			CURLOPT_POSTFIELDS => '{
				"responsavel": "'.$responsavel.'",
				"dataMovimento": "'.$dataMovimento.'",
				"idEmpresa": "'.$idEmpresa.'",
                "tipoEmpresa": "'.$tipoEmpresa.'",
                "idCliente": "'.$idCliente.'",
                "idSetor": "'.$idSetor.'",
                "idTipoMovimento": "'.$idTipoMovimento.'",
                "idTransportador": "'.$idTransportador.'",
                "qutVolumes": "'.$qutVolumes.'",
                "observacao": "'.$observacao.'",
                "cep": "'.$cep.'",
                "rua": "'.$rua.'",
                "bairro": "'.$bairro.'",
                "cidade": "'.$cidade.'",
                "uf": "'.$uf.'",
                "regiao": "'.$regiao.'",			
				"itens": '.json_encode($itens).'
			}',
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

	function get_listarItensMovimento($idMovimento){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."movimentoItem/listarItensAtivos?idMovimento=".$idMovimento;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' => $value['id'],
						'idMovimentoExpedicao' => $value['idMovimentoExpedicao'],
						'referencia' => $value['referencia'],
						'idTerminal' => $value['idTerminal'],
						'qutUnitaria' => $value['qutUnitaria'],
						'qutTotal' => $value['qutTotal'],
						'valorUnitario' => $value['valorUnitario'],
						'valorTotal' => $value['valorTotal'],
						'movimento' => $value['movimento'],
						'dataCadastro' => $value['dataCadastro'],
						'dataUpdate' => $value['dataUpdate'],
						'status' => $value['status']
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'results' => $result
	
		);
	
	}

	function to_cadastrarItemMovimento($body){

		$idMovimento = $body['idMovimento'];
		$referencia = $body['referencia'];
		$idTerminal = $body['idTerminal'];
		$qutUnitaria = $body['qutUnitaria'];
		$valorUnitario = $body['valorUnitario'];
		$movimento = $body['movimento'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'movimentoItem/cadastrarMovimentoItem';

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
			CURLOPT_POSTFIELDS => '{
				"idMovimento": "'.$idMovimento.'",
				"referencia": "'.$referencia.'",
				"idTerminal": "'.$idTerminal.'",
				"qutUnitaria": "'.$qutUnitaria.'",
				"valorUnitario": "'.$valorUnitario.'",
				"movimento": "'.$movimento.'"
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return array(
				'status' => $statusCode,
				'dados'  => $resultado,
		);
	}
	function to_atualizaItemMovimento($body){

		$idItem = $body['idItem'];
		$idMovimento = $body['idMovimento'];
		$referencia = $body['referencia'];
		$idTerminal = $body['idTerminal'];
		$qutUnitaria = $body['qutUnitaria'];
		$valorUnitario = $body['valorUnitario'];
		$movimento = $body['movimento'];
		$status = $body['status'];
	
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."movimentoItem/editarMovimentoItem";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"idItem": "'.$idItem.'",
			"idMovimento": "'.$idMovimento.'",
			"referencia": "'.$referencia.'",
			"idTerminal": "'.$idTerminal.'",
			"qutUnitaria": "'.$qutUnitaria.'",
			"valorUnitario": "'.$valorUnitario.'",
			"movimento": "'.$movimento.'",
			"status": "'.$status.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function to_removerItemMovimento($idItem){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."movimentoItem/deletarMovimentoItem?idItem=".$idItem;
	
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
		CURLOPT_CUSTOMREQUEST => 'DELETE',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	
	}

	

	function to_cadastrarHistoricoLinha($body){

		$idLinha = $body['idLinha'];
		$dataInicio = $body['dataInicio'];
		$acao = $body['acao'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'linhaHistorico/cadastrarLinhaHistorico';

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
			CURLOPT_POSTFIELDS => '{
				"idLinha": "'.$idLinha.'",
				"dataInicio": "'.$dataInicio.'",
				"acao": "'.$acao.'"
			}',
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

	function get_listarHistoricoLinha($idLinha){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."linhaHistorico/listarLinhasHistorico?idLinha=".$idLinha;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' => $value['id'],
						'idLinha' => $value['idLinha'],
						'dataRegistro' => $value['dataRegistro'],
						'acao' => $value['acao'],
						'dataInicioAcao' => $value['dataInicioAcao'],
						'dataFimAcao' => $value['dataFimAcao'],
						'status' => $value['status'],
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
				'results' => $result
	
			)
		);
	
	}

	function to_atualizaStatusHistoricoLinha($body){

		$idLinhaHistorico = $body['idLinhaHistorico'];
		$status = $body['status'];
		
		$CI =& get_instance();
			
		$request = $CI->config->item('url_api_shownet_rest')."linhaHistorico/alterarStatusLinhaHistorico";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PATCH',
		CURLOPT_POSTFIELDS => '{
			"idLinhaHistorico": "'.$idLinhaHistorico.'",
			"status": "'.$status.'"
		}',
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

	function to_atualizaHistoricoLinha($body){

		$idLinhaHistorico = $body['idLinhaHistorico'];
		$dataInicio = $body['dataInicio'];
		$acao = $body['acao'];
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."linhaHistorico/editarLinhaHistorico";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"idLinhaHistorico": "'.$idLinhaHistorico.'",
			"dataInicio": "'.$dataInicio.'",
			"acao": "'.$acao.'"
		}',
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

	
	function get_listarGrupos($idCliente){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."grupo/listarGrupos?idCliente=".$idCliente;
	
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
	
		return  array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}
	
	function get_listar_relatorio_qtd_instalacoes($idCliente){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."contratos/listarContratosByIdCliente?idCliente=".$idCliente;
	
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
	
		return  array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}

	
	function cadastrarCompartilhamentoGrupo($body){

		$idCliente = $body['idCliente'];
		$idCentral = $body['idCentral'];
		$idGrupo = $body['idGrupo'];
		$userCadastro = $body['userCadastro'];
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."compartilhamentoGrupo/cadastrarCompartilhamentoGrupo";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();

		$idGrupos = json_encode($idGrupo);

		$body = '{
			"idCliente": "'.$idCliente.'",
			"idCentral": "'.$idCentral.'",
			"idGrupos": '.$idGrupos.',
			"userCadastro": "'.$userCadastro.'"
		}';
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
			"idCliente": "'.$idCliente.'",
			"idCentral": "'.$idCentral.'",
			"idGrupos": '.$idGrupos.',
			"userCadastro": "'.$userCadastro.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'dados'       => $resultado,
		);

	}

	function editarCompartilhamentoGrupo($body){

		$idCliente = $body['idCliente'];
		$idCentral = $body['idCentral'];
		$idGrupos = $body['idGrupos'];
		$userCadastro = $body['userCadastro'];
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."compartilhamentoGrupo/editarCompartilhamentoGrupo";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"idCliente": "'.$idCliente.'",
			"idCentral": "'.$idCentral.'",
			"idGrupos": '.$idGrupos.',
			"userCadastro": "'.$userCadastro.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'dados'       => $resultado,
		);
	}
	
	function get_centrais_cliente($idCliente){
	
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."compartilhamentoGrupo/listarCompartilhamentosGrupoCentral?idCliente=".$idCliente;
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado,
		);
	}

	function get_listarRastreamentoVeiculos($dataInicial, $dataFinal, $placas, $idCliente){
	
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."rastreamento/listarRastreamentoVeiculos?dataInicial=". $dataInicial ."&dataFinal=". $dataFinal ."&placas=". implode(', ', $placas) ."&idCliente=".$idCliente;
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
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
	
		return json_encode( 
			array(
				'status' => $statusCode,
				'dados'  => $resultado,
			)
		);
	}

	function get_listarUltimasCampanhasComissionamento(){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."comissionamento/listarUltimasCampanhasComissionamento";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'idCampanha' => $value['idCampanha'],
						'nomeCampanha' => $value['nomeCampanha'],
						'idEmpresa' => $value['idEmpresa'],
						'razaoSocial' => $value['razaoSocial'],
						'dataInicio' => $value['dataInicio'],
						'dataFim' => $value['dataFim'],
						'dataCadastro' => $value['dataCadastro'],
						'dataUpdate' => $value['dataUpdate'],
						'status' => $value['status'],
						"temCampanhaCenario" => $value['temCampanhaCenario'],
    					"temCampanhaItem" => $value['temCampanhaItem']
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
				'results' => $result
	
			)
		);
	
	}

	function get_listarCampanhasComissionamento($idEmpresa){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."comissionamento/listarCampanhasComissionamento?idEmpresa=". $idEmpresa;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'idCampanha' => $value['idCampanha'],
						'nomeCampanha' => $value['nomeCampanha'],
						'idEmpresa' => $value['idEmpresa'],
						'razaoSocial' => $value['razaoSocial'],
						'dataInicio' => $value['dataInicio'],
						'dataFim' => $value['dataFim'],
						'dataCadastro' => $value['dataCadastro'],
						'dataUpdate' => $value['dataUpdate'],
						'status' => $value['status'],
						"temCampanhaCenario" => $value['temCampanhaCenario'],
    					"temCampanhaItem" => $value['temCampanhaItem'],
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
				'results' => $result
	
			)
		);
	
	}
	
	function post_listarCampanhasComissionamento($idEmpresa, $dataInicial, $dataFinal){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."comissionamento/listarCampanhasComissionamentoByEmpresaData";
	
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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => 
			'{   
				"idEmpresa": "'. $idEmpresa .'",
				"dataInicio": "'. $dataInicial .'",
				"dataFim": "'. $dataFinal .'"
			}',
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		$result = array();
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'idCampanha' => $value['idCampanha'],
						'nomeCampanha' => $value['nomeCampanha'],
						'idEmpresa' => $value['idEmpresa'],
						'razaoSocial' => $value['razaoSocial'],
						'dataInicio' => $value['dataInicio'],
						'dataFim' => $value['dataFim'],
						'dataCadastro' => $value['dataCadastro'],
						'dataUpdate' => $value['dataUpdate'],
						'status' => $value['status'],
						"temCampanhaCenario" => $value['temCampanhaCenario'],
    					"temCampanhaItem" => $value['temCampanhaItem'],
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
				'results' => $result
	
			)
		);
	
	}

	function get_listarDevolucaoVendas(){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."devolucaoVendas/listarDevolucaoVendas";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'af' 					=> $value['af'],						
						'regional' 				=> $value['regional'],
						'quantidade'			=> $value['quantidade'],
						'valorVendas' 			=> $value['valorVendas'],
						'dataEmissao' 			=> $value['dataEmissao'],
						'cenario' 				=> $value['cenario'],
						'executivo' 			=> $value['executivo'],
						'dataCadastro' 			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'cliente' 				=> $value['cliente'],
						'proprietario' 			=> $value['proprietario'],
						'devolucao' 			=> $value['devolucao'],
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
				'results' => $result
	
			)
		);
	
	}

	function get_listarDevolucaoVendasTop100(){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."devolucaoVendas/listarCemUltimasDevolucaoVendas";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'af' 					=> $value['af'],						
						'regional' 				=> $value['regional'],
						'quantidade'			=> $value['quantidade'],
						'valorVendas' 			=> $value['valorVendas'],
						'dataEmissao' 			=> $value['dataEmissao'],
						'cenario' 				=> $value['cenario'],
						'executivo' 			=> $value['executivo'],
						'dataCadastro' 			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'cliente' 				=> $value['cliente'],
						'proprietario' 			=> $value['proprietario'],
						'devolucao' 			=> $value['devolucao'],
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
				'results' => $result
	
			)
		);
	
	}

	function get_DevolucaoVendas($id){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."devolucaoVendas/listarDevolucaoVendaById?id=".$id;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'af' 					=> $value['af'],
						'filial'				=> $value['filial'],
						'codProduto' 			=> $value['codProduto'],
						'codNf'					=> $value['codNf'],
						'descricaoProduto' 		=> $value['descricaoProduto'],
						'codCanal' 				=> $value['codCanal'],
						'descricaoCanal' 		=> $value['descricaoCanal'],
						'regional' 				=> $value['regional'],
						'descricaoClasseValor' 	=> $value['descricaoClasseValor'],
						'codigoBudget' 			=> $value['codigoBudget'],
						'descricaoBudget' 		=> $value['descricaoBudget'],
						'quantidade'			=> $value['quantidade'],
						'valorVendas' 			=> $value['valorVendas'],
						'nomeCliente' 			=> $value['nomeCliente'],
						'dataEmissao' 			=> $value['dataEmissao'],
						'ufCliente' 			=> $value['ufCliente'],
						'cidadeCliente' 		=> $value['cidadeCliente'],
						'codCliente' 			=> $value['codCliente'],
						'cnpj' 					=> $value['cnpj'],
						'segmentacao' 			=> $value['segmentacao'],
						'cenario' 				=> $value['cenario'],
						'bairro' 				=> $value['bairro'],
						'trade' 				=> $value['trade'],
						'codTratado' 			=> $value['codTratado'],
						'kit' 					=> $value['kit'],
						'qtd' 					=> $value['qtd'],
						'plano' 				=> $value['plano'],
						'nome' 					=> $value['nome'],
						'executivo' 			=> $value['executivo'],
						'base' 					=> $value['base'],
						'executivoProprietario' => $value['executivoProprietario'],
						'regionalProprietario' 	=> $value['regionalProprietario'],
						'quant' 				=> $value['quant'],
						'devolucao' 			=> $value['devolucao'],
						'tipo' 					=> $value['tipo'],
						'dataInicio' 			=> $value['dataInicio'],
						'dataFim' 				=> $value['dataFim'],
						'dataCadastro' 			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'proprietario'			=> $value['proprietario'],
						'loja'					=> $value['loja'],
						'cliente'				=> $value['cliente'],
						'municipio'				=> $value['municipio'],
						'codigoClasseValor'		=> $value['codigoClasseValor'],
						'tecnologia' 			=> $value['tecnologia'],
						'descricaoTecnologia' 	=> $value['descricaoTecnologia'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $result
		);
	
	}

	function put_DevolucaoVendas($devolucao){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."devolucaoVendas/atualizarDevolucaoVendas";
	
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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => 
			'{   
				"id": "'. $devolucao["id"] .'",
				"af": "'. $devolucao["af"] .'",
				"regional": "'. $devolucao["regional"] .'",
				"quantidade": "'. $devolucao["quantidade"] .'",
				"valorVendas": "'. $devolucao["valorVendas"] .'",
				"dataEmissao": "'. $devolucao["dataEmissao"] .'",
				"cenario": "'. $devolucao["cenario"] .'",
				"devolucao": "sim",
				"proprietario": "'. $devolucao["proprietario"] .'",
				"cliente": "'. $devolucao["cliente"] .'",
				"status": 1
			}',
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		$result = array();
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}

	function post_DevolucaoVendas($body){
		$af = $body["af"];
		$regional = $body["regional"];
		$quantidade = $body["quantidade"];
		$valorVendas = $body["valorVendas"];
		$dataEmissao = $body["dataEmissao"];
		$cenario = $body["cenario"];
		$proprietario = $body["proprietario"];
		$cliente = $body["cliente"];
		

		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest').'devolucaoVendas/cadastrarDevolucaoVendas';
	
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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => '{   
				"af": "'. $af .'",
				"regional": "'. $regional .'",
				"quantidade": "'. $quantidade .'",
				"valorVendas": "'. $valorVendas .'",
				"dataEmissao": "'. $dataEmissao .'",
				"cenario": "'. $cenario .'",
				"devolucao": "sim",
				"proprietario": "'. $proprietario .'",
				"cliente": "'. $cliente .'"
			}',
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}

	
	function get_listarVendasComissionadas($dataInicial = "", $dataFinal = ""){
	
		$CI =& get_instance();
	
		if($dataInicial){
            $dataInicial = str_replace("-", "/", $dataInicial);
			$dataInicial  = date('d/m/Y', strtotime($dataInicial));
		}

		if($dataFinal){
            $dataFinal = str_replace("-", "/", $dataFinal);
			$dataFinal  = date('d/m/Y', strtotime($dataFinal));
		}
	
		$request = $CI->config->item('url_api_shownet_rest')."relatorioPedidos/listarRelatorioPedidosPorPeriodo?dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
	
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => $request,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_TIMEOUT => 5000,
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
			array(
				'status' => $statusCode,
				'results' => $resultado
	
			)
		);
	
	}

	function get_VendasComissionadas($id){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."VendasComissionadas/VendaComissionadaById?id=".$id;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'af' 					=> $value['af'],
						'filial'				=> $value['filial'],
						'codProduto' 			=> $value['codProduto'],
						'codNf'					=> $value['codNf'],
						'descricaoProduto' 		=> $value['descricaoProduto'],
						'codCanal' 				=> $value['codCanal'],
						'descricaoCanal' 		=> $value['descricaoCanal'],
						'regional' 				=> $value['regional'],
						'descricaoClasseValor' 	=> $value['descricaoClasseValor'],
						'codigoBudget' 			=> $value['codigoBudget'],
						'descricaoBudget' 		=> $value['descricaoBudget'],
						'quantidade'			=> $value['quantidade'],
						'valorVendas' 			=> $value['valorVendas'],
						'nomeCliente' 			=> $value['nomeCliente'],
						'dataEmissao' 			=> $value['dataEmissao'],
						'ufCliente' 			=> $value['ufCliente'],
						'cidadeCliente' 		=> $value['cidadeCliente'],
						'codCliente' 			=> $value['codCliente'],
						'cnpj' 					=> $value['cnpj'],
						'segmentacao' 			=> $value['segmentacao'],
						'cenario' 				=> $value['cenario'],
						'bairro' 				=> $value['bairro'],
						'trade' 				=> $value['trade'],
						'codTratado' 			=> $value['codTratado'],
						'kit' 					=> $value['kit'],
						'qtd' 					=> $value['qtd'],
						'plano' 				=> $value['plano'],
						'nome' 					=> $value['nome'],
						'executivo' 			=> $value['executivo'],
						'base' 					=> $value['base'],
						'executivoProprietario' => $value['executivoProprietario'],
						'regionalProprietario' 	=> $value['regionalProprietario'],
						'quant' 				=> $value['quant'],
						'devolucao' 			=> $value['devolucao'],
						'tipo' 					=> $value['tipo'],
						'dataInicio' 			=> $value['dataInicio'],
						'dataFim' 				=> $value['dataFim'],
						'dataCadastro' 			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],

					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $result
		);
	
	}

	function put_VendasComissionadas($venda){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."VendasComissionadas/atualizarVendasComissionadas";
	
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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => 
			'{   
				"id": "'. $venda["id"] .'",
				"af": "'. $venda["af"] .'",
				"filial": "'. $venda["filial"] .'",
				"codProduto": "'. $venda["codProduto"] .'",
				"codNf": "'. $venda["codNf"] .'",
				"descricaoProduto": "'. $venda["descricaoProduto"] .'",
				"codCanal": "'. $venda["codCanal"] .'",
				"descricaoCanal": "'. $venda["descricaoCanal"] .'",
				"regional": "'. $venda["regional"] .'",
				"descricaoClasseValor": "'. $venda["descricaoClasseValor"] .'",
				"codigoBudget": "'. $venda["codigoBudget"] .'",
				"descricaoBudget": "'. $venda["descricaoBudget"] .'",
				"quantidade": "'. $venda["quantidade"] .'",
				"valorVendas": "'. $venda["valorVendas"] .'",
				"nomeCliente": "'. $venda["nomeCliente"] .'",
				"dataEmissao": "'. $venda["dataEmissao"] .'",
				"ufCliente": "'. $venda["ufCliente"] .'",
				"cidadeCliente": "'. $venda["cidadeCliente"] .'",
				"codCliente": "'. $venda["codCliente"] .'",
				"cnpj": "'. $venda["cnpj"] .'",
				"segmentacao": "'. $venda["segmentacao"] .'",
				"cenario": "'. $venda["cenario"] .'",
				"bairro": "'. $venda["bairro"] .'",
				"trade": "'. $venda["trade"] .'",
				"codTratado": "'. $venda["codTratado"] .'",
				"kit": "'. $venda["kit"] .'",
				"qtd": "'. $venda["qtd"] .'",
				"plano": "'. $venda["plano"] .'",
				"nome": "'. $venda["nome"] .'",
				"executivo": "'. $venda["executivo"] .'",
				"base": "'. $venda["base"] .'",
				"executivoProprietario": "'. $venda["executivoProprietario"] .'",
				"regionalProprietario": "'. $venda["regionalProprietario"] .'",
				"quant": "'. $venda["quant"] .'",
				"venda": "'. $venda["venda"] .'",
				"tipo": "'. $venda["tipo"] .'",
				"dataInicio": "'. $venda["dataInicio"] .'",
				"dataFim": "'. $venda["dataFim"] .'",
				"status": 1
			}',
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		$result = array();
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}

	function post_VendasComissionadas($venda){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."relatorioPedidos/cadastrarRelatorioPedido";
	
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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => 
			'{   
				"af": "'.$venda["af"] .'",
				"qtd": "'.$venda["qtd"] .'",
				"cliente": "'.$venda["cliente"] .'",
				"dataCriacao": "'.$venda["dataCriacao"] .'",
				"dataFechamento": "'.$venda["dataFechamento"] .'",
				"vendedor": "'.$venda["vendedor"] .'",
				"totalHw": "'.$venda["totalHw"] .'",
				"totalLu": "'.$venda["totalLu"] .'",
				"cenario": "'.$venda["cenario"] .'"
			}',

		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		$result = array();
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}

	function to_cadastrarCampanha($body){
		$idEmpresa = $body['idEmpresa'];
		$nome = $body['nome'];
		$dataInicio = $body['dataInicio'];
		$dataFim = $body['dataFim'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'comissionamento/cadastrarCampanhaComissionamento';

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
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => '{
				"idEmpresa": "'.$idEmpresa.'",
				"nome": "'.$nome.'",
				"dataInicio": "'.$dataInicio.'",
				"dataFim": "'.$dataFim.'"
			}',
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

	function to_editarCadCampanha($body){

		$idCampanha = $body['idCampanha'];
		$idEmpresa = $body['idEmpresa'];
		$nome = $body['nome'];
		$dataInicio = $body['dataInicio'];
		$dataFim = $body['dataFim'];
		$status = $body['status'];
		$temCampanhaCenario = $body['temCampanhaCenario'];
		$temCampanhaItem = $body['temCampanhaItem'];
		
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."comissionamento/editarCampanhaComissionamento";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"idCampanha": "'.$idCampanha.'",
			"idEmpresa": "'.$idEmpresa.'",
			"nome": "'.$nome.'",
			"dataInicio": "'.$dataInicio.'",
			"dataFim": "'.$dataFim.'",
			"status": "'.$status.'",
			"temCampanhaItem": '.$temCampanhaItem.',
			"temCampanhaCenario": '.$temCampanhaCenario.'
		}',
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

	function to_cadastrarCampanhaEItens($body){

		$idEmpresa = $body['idEmpresa'];
		$nome = $body['nome'];
		$dataInicio = $body['dataInicio'];
		$dataFim = $body['dataFim'];
		$itens = $body['itens'];
		$temCampanhaCenario = $body['temCampanhaCenario'];
		$temCampanhaItem = $body['temCampanhaItem'];
	
		$CI = &get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest').'comissionamento/cadastrarCampanhaComissionamentoEItens';
	
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
			CURLOPT_POSTFIELDS => '{
				"idEmpresa": "'.$idEmpresa.'",
				"nome": "'.$nome.'",
				"dataInicio": "'.$dataInicio.'",
				"dataFim": "'.$dataFim.'",
				"temCampanhaItem": "'.$temCampanhaItem.'",
				"temCampanhaCenario": "'.$temCampanhaCenario.'",
				"itens": '.json_encode($itens).'
			}',
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

	function get_listarItensCampanha($idCampanha){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."campanhaItem/listarCampanhaItens?idCampanha=".$idCampanha;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				$result[] = array(
					'idItem' => $value['idItem'],
					'idCampanha' => $value['idCampanha'],
					'valorMeta' => $value['valorMeta'],
					'percentualComissao' => $value['percentualComissao'],
					'aplicaClienteBase' => $value['aplicaClienteBase'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
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
				'results' => $result
	
			)
		);
	
	}

	function get_listarCenariosCampanha($idCampanha){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."campanhaCenario/listarCampanhaCenarioByIdCampanha?idCampanha=".$idCampanha;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				$result[] = array(
					'id' => $value['id'],
					'idCampanha' => $value['idCampanha'],
					'idCenarioVenda' => $value['idCenarioVenda'],
					'valorFixo' => $value['valorFixo'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status']
				);
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return
			array(
				'status' => $statusCode,
				'results' => $result
	
			);
	
	}

	function to_cadastrarCampanhaECenarios($body){

		$idEmpresa = $body['idEmpresa'];
		$nome = $body['nome'];
		$dataInicio = $body['dataInicio'];
		$dataFim = $body['dataFim'];
		$campanhaCenariosForm = $body['campanhaCenariosForm'];
		$temCampanhaCenario = $body['temCampanhaCenario'];
		$temCampanhaItem = $body['temCampanhaItem'];
	
		$CI = &get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest').'comissionamento/cadastrarCampanhaComCampanhaCenario';
	
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
			CURLOPT_POSTFIELDS => '{
				"idEmpresa": "'.$idEmpresa.'",
				"nome": "'.$nome.'",
				"dataInicio": "'.$dataInicio.'",
				"dataFim": "'.$dataFim.'",
				"temCampanhaItem": "'.$temCampanhaItem.'",
				"temCampanhaCenario": "'.$temCampanhaCenario.'",
				"campanhaCenariosForm": '.json_encode($campanhaCenariosForm).'
			}',
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

	function to_cadastrarCenarioCampanha($body){

		$idCampanha = $body['idCampanha'];
		$valorFixo = $body['valorFixo'];
		$idCenario = $body['idCenario'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'campanhaCenario/cadastrarCampanhaCenario';

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
			CURLOPT_POSTFIELDS => '{
				"idCampanha": "'.$idCampanha.'",
				"valorFixo": "'.$valorFixo.'",
				"idCenario": "'.$idCenario.'"

			}',
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

	function to_editarCenarioCampanha($body){

		$id = $body['id'];
		$idCampanha = $body['idCampanha'];
		$valorFixo = $body['valorFixo'];
		$idCenarioVenda = $body['idCenarioVenda'];
		$status = $body['status'];
	
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."campanhaCenario/editarCampanhaCenario";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"idCampanha": "'.$idCampanha.'",
			"valorFixo": "'.$valorFixo.'",
			"idCenarioVenda": "'.$idCenarioVenda.'",
			"status": "'.$status.'"
		}',
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

	function to_alterarStatusCenarioCampanha($body){
		$id = $body['id'];
		$status = $body['status'];
	
		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'campanhaCenario/alterarStatusCampanhaCenario';
	
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
			CURLOPT_POSTFIELDS => '{
				"id": "'.$id.'",
				"status": "'.$status.'"
			}',
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

	function to_cadastrarItemCampanha($body){

		$idCampanha = $body['idCampanha'];
		$valorMeta = $body['valorMeta'];
		$percentualComissao = $body['percentualComissao'];
		$aplicaClienteBase = $body['aplicaClienteBase'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'campanhaItem/cadastrarCampanhaItem';

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
			CURLOPT_POSTFIELDS => '{
				"idCampanha": "'.$idCampanha.'",
				"valorMeta": "'.$valorMeta.'",
				"percentualComissao": "'.$percentualComissao.'",
				"aplicaClienteBase": "'.$aplicaClienteBase.'"

			}',
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

	function to_alterarStatusItemCampanha($body){
		$idItem = $body['idItem'];
		$status = $body['status'];
	
		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'campanhaItem/alterarStatusCampanhaItem';
	
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
			CURLOPT_POSTFIELDS => '{
				"idItem": "'.$idItem.'",
				"status": "'.$status.'"
			}',
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

	function to_atualizaItemCampanha($body){

		$idItem = $body['idItem'];
		$idCampanha = $body['idCampanha'];
		$valorMeta = $body['valorMeta'];
		$percentualComissao = $body['percentualComissao'];
		$aplicaClienteBase = $body['aplicaClienteBase'];
		$status = $body['status'];
	
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."campanhaItem/editarCampanhaItem";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"idItem": "'.$idItem.'",
			"idCampanha": "'.$idCampanha.'",
			"valorMeta": "'.$valorMeta.'",
			"percentualComissao": "'.$percentualComissao.'",
			"aplicaClienteBase": "'.$aplicaClienteBase.'",
			"status": "'.$status.'"
		}',
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

	function get_listarRegionaisPorEmpresa($idEmpresa){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."regional/listarRegionalById?idEmpresa=".$idEmpresa;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' => $value['id'],
						'nome' => $value['nome'],
						'idEmpresa' => $value['idEmpresa'],
						'nomeEmpresa' => $value['nomeEmpresa'],
						'dataCadastro' => $value['dataCadastro'],
						'dataUpdate' => $value['dataUpdate'],
						'status' => $value['status'],
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
				'results' => $result
	
			)
		);
	
	}

	function to_cadastrarRegional($body){
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'regional/cadastrarRegional';

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
			CURLOPT_POSTFIELDS => '{
				"nome": "'.$nome.'",
				"idEmpresa": "'.$idEmpresa.'"
				
			}',
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

	function to_editarCadRegional($body){

		$id = $body['id'];
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];
		$status = $body['status'];
		
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."regional/editarRegional";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"nome": "'.$nome.'",
			"idEmpresa": "'.$idEmpresa.'",
			"status": "'.$status.'"
		}',
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

	function get_listarRegionaisAll($select = false){
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."regional/listarRegionais";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					if ($select == true){
						$result[] = array(
							'id' 					=> $value['id'],
							'text' 					=> $value['nome'],
							'idEmpresa'				=> $value['idEmpresa'],
							'nomeEmpresa' 			=> $value['nomeEmpresa'],
							'dataCadastro'			=> $value['dataCadastro'],
							'dataUpdate' 			=> $value['dataUpdate'],
							'status' 				=> $value['status'],
						);
					}else{
						$result[] = array(
							'id' 					=> $value['id'],
							'nome' 					=> $value['nome'],
							'idEmpresa'				=> $value['idEmpresa'],
							'nomeEmpresa' 			=> $value['nomeEmpresa'],
							'dataCadastro'			=> $value['dataCadastro'],
							'dataUpdate' 			=> $value['dataUpdate'],
							'status' 				=> $value['status'],
						);
					}
				}
			}
		}else{
			$result = $resultado;
		}
		
		curl_close($curl);
		if ($select == true){
			return json_encode(
				array(
					'status' => $statusCode,
					'results'       => $result,
					'pagination'    => [
						'more'      => false,
					]
				)
			);
		}
		echo json_encode(
			array(
				'status' => $statusCode,
				'results' => $result
	
			)
		);
	
	}


	function get_listarVendedores(){
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."vendedor/listarVendedor";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'nome' 					=> $value['nome'],
						'idEmpresa'				=> $value['idEmpresa'],
						'nomeEmpresa' 			=> $value['nomeEmpresa'],
						'idRegional' 			=> $value['idRegional'],
						'nomeRegional' 			=> $value['nomeRegional'],
						'idCargo'				=> $value['idCargo'],
						'nomeCargo' 			=> $value['nomeCargo'],
						'dataCadastro' 			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'status' 				=> $value['status'],
						'matricula' 			=> $value['matricula'],
						'salario' 				=> $value['salario'],
						'email' 				=> $value['email'],
						'garantia'				=> $value['garantia'],
						'codigoCentroResultado' => $value['codigoCentroResultado'],
						'nomeCentroResultado'	=> $value['nomeCentroResultado'],
						'dataAdmissao' 			=> $value['dataAdmissao'],
						'chefiaImediata' 		=> $value['chefiaImediata'],
						'valorGarantia' 		=> $value['valorGarantia'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'results' => $result
	
		);
	}

	function post_obterDevolucoesVendas($body){
		$cliente = $body['cliente'];
		$proprietario = $body['proprietario'];
		$af = $body['af'];
		$dataInicial = $body['dataInicial'];
		$dataFinal = $body['dataFinal'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'devolucaoVendas/obterDevolucoesVendas';

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
			CURLOPT_POSTFIELDS => '{
				"cliente": "'.$cliente.'",
				"proprietario": "'.$proprietario.'",
				"af": "'.$af.'",
				"dataInicial": "'.$dataInicial.'",
				"dataFinal": "'.$dataFinal.'"
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		echo json_encode(
			array(
				'status' => $statusCode,
				'results'  => $resultado,
			)
		);
	}

	function get_listarComissoesCalculadas(){
		$CI =& get_instance();
		
	
		$request = $CI->config->item('url_api_shownet_rest')."comissaoCalculada/listarComissoesCalculadas";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'idEmpresa' 			=> $value['idEmpresa'],
						'nomeEmpresa'			=> $value['nomeEmpresa'],
						'idVendedor' 			=> $value['idVendedor'],
						'nomeVendedor' 			=> $value['nomeVendedor'],
						'dataInicio'			=> $value['dataInicio'],
						'dataFim' 				=> $value['dataFim'],
						'valorTotalVendas' 		=> $value['valorTotalVendas'],
						'valorTotalDevolucoes' 	=> $value['valorTotalDevolucoes'],
						'teveCampanha' 			=> $value['teveCampanha'],
						'percentualMedio' 		=> $value['percentualMedio'],
						'dataCadastro'			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'status' 				=> $value['status'],
						'totalComissao' 		=> $value['totalComissao'],
						'cargo' 				=> $value['cargo'],
						'chefiaImediata' 		=> $value['chefiaImediata'],
						'nomeRegional'			=> $value['nomeRegional'],
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
				'results' => $result
	
			)
		);
	
	}

	function get_listarComissoesCalculadasPorEmpresa($idEmpresa){
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."comissaoCalculada/listarComissoesCalculadasByEmpresa?idEmpresa=".$idEmpresa;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'idEmpresa' 			=> $value['idEmpresa'],
						'nomeEmpresa'			=> $value['nomeEmpresa'],
						'idVendedor' 			=> $value['idVendedor'],
						'nomeVendedor' 			=> $value['nomeVendedor'],
						'dataInicio'			=> $value['dataInicio'],
						'dataFim' 				=> $value['dataFim'],
						'valorTotalVendas' 		=> $value['valorTotalVendas'],
						'valorTotalDevolucoes' 	=> $value['valorTotalDevolucoes'],
						'teveCampanha' 			=> $value['teveCampanha'],
						'percentualMedio' 		=> $value['percentualMedio'],
						'dataCadastro'			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'status' 				=> $value['status'],
						'totalComissao' 		=> $value['totalComissao'],
						'cargo' 				=> $value['cargo'],
						'chefiaImediata' 		=> $value['chefiaImediata'],
						'nomeRegional'			=> $value['nomeRegional']
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
				'results' => $result
	
			)
		);
	
	}
	function get_listarCargos(){


		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."cargo/listarCargos";
	
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
	
		if ($statusCode == 200){
			foreach ($resultado as $value){
				if ($value['status'] == 'Ativo'){
					$result[] = $value;
				}
			}
		}else{
			$result = $resultado;
		}

		return array(
			'status' => $statusCode,
			'results' => $result
		);	
	}
	function to_cadastrarVendedor($body){
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];
		$idRegional = $body['idRegional'];
		$idCargo = $body['idCargo'];
		$matricula = $body['matricula'];
		$salario = $body['salario'];
		$valorGarantia = $body['valorGarantia'];
		$email = $body['email'];
		$garantia = $body['garantia'];
		$codigoCentroResultado = $body['codigoCentroResultado'];
		$nomeCentroResultado = $body['nomeCentroResultado'];
		$dataAdmissao = $body['dataAdmissao'];
		$chefiaImediata = $body['chefiaImediata'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'vendedor/cadastrarVendedor';

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
			CURLOPT_POSTFIELDS => '{
				"nome": "'.$nome.'",
				"idEmpresa": "'.$idEmpresa.'",
				"idRegional": "'.$idRegional.'",
				"idCargo": "'.$idCargo.'",
				"matricula": "'.$matricula.'",
				"salario": "'.$salario.'",
				"valorGarantia": "'.$valorGarantia.'",
				"email": "'.$email.'",
				"garantia": "'.$garantia.'",
				"codigoCentroResultado": "'.$codigoCentroResultado.'",
				"nomeCentroResultado": "'.$nomeCentroResultado.'",
				"dataAdmissao": "'.$dataAdmissao.'",
				"chefiaImediata": "'.$chefiaImediata.'"
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return array(
				'status' => $statusCode,
				'dados'  => $resultado,
		);

	}

	function to_editarCadVendedor($body){

		$id = $body['id'];
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];
		$idRegional = $body['idRegional'];
		$idCargo = $body['idCargo'];
		$status = $body['status'];
		$matricula = $body['matricula'];
		$salario = $body['salario'];
		$valorGarantia = $body['valorGarantia'];
		$email = $body['email'];
		$garantia = $body['garantia'];
		$codigoCentroResultado = $body['codigoCentroResultado'];
		$nomeCentroResultado = $body['nomeCentroResultado'];
		$dataAdmissao = $body['dataAdmissao'];
		$chefiaImediata = $body['chefiaImediata'];
		
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."vendedor/atualizarVendedor";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"nome": "'.$nome.'",
			"idEmpresa": "'.$idEmpresa.'",
			"idRegional": "'.$idRegional.'",
			"idCargo": "'.$idCargo.'",
			"status": "'.$status.'",
			"matricula": "'.$matricula.'",
			"salario": "'.$salario.'",
			"valorGarantia": "'.$valorGarantia.'",
			"email": "'.$email.'",
			"garantia": "'.$garantia.'",
			"codigoCentroResultado": "'.$codigoCentroResultado.'",
			"nomeCentroResultado": "'.$nomeCentroResultado.'",
			"dataAdmissao": "'.$dataAdmissao.'",
			"chefiaImediata": "'.$chefiaImediata.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function get_listarVendedoresByNomeOrEmpresaOrCargoOrRegional($nome, $idEmpresa, $idCargo, $idRegional){
		$CI =& get_instance();
		
		$nome = urlencode($nome);
	
		$request = $CI->config->item('url_api_shownet_rest')."vendedor/listarVendedorByAnyone?nome=".$nome."&idEmpresa=".$idEmpresa."&idCargo=".$idCargo."&idRegional=".$idRegional;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'nome' 					=> $value['nome'],
						'idEmpresa'				=> $value['idEmpresa'],
						'nomeEmpresa' 			=> $value['nomeEmpresa'],
						'idRegional' 			=> $value['idRegional'],
						'nomeRegional' 			=> $value['nomeRegional'],
						'idCargo'				=> $value['idCargo'],
						'nomeCargo' 			=> $value['nomeCargo'],
						'dataCadastro' 			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'status' 				=> $value['status'],
						'matricula' 			=> $value['matricula'],
						'salario' 				=> $value['salario'],
						'email' 				=> $value['email'],
						'garantia'				=> $value['garantia'],
						'codigoCentroResultado' => $value['codigoCentroResultado'],
						'nomeCentroResultado'	=> $value['nomeCentroResultado'],
						'dataAdmissao' 			=> $value['dataAdmissao'],
						'chefiaImediata' 		=> $value['chefiaImediata'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'results' => $result
	
		);
	}

	function get_listarVendedorPorEmpresa($idEmpresa){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."vendedor/listarVendedorById?idEmpresa=".$idEmpresa;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' => $value['id'],
						'nome' => $value['nome'],
						'idEmpresa' => $value['idEmpresa'],
						'nomeEmpresa' => $value['nomeEmpresa'],
						'idRegional' => $value['idRegional'],
						'nomeRegional' => $value['nomeRegional'],
						'idCargo' => $value['idCargo'],
						'nomeCargo' => $value['nomeCargo'],
						'dataCadastro' => $value['dataCadastro'],
						'dataUpdate' => $value['dataUpdate'],
						'status' => $value['status'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'results' => $result
	
		);
	}

	function get_Cargo($id){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."cargo/listarCargoById?idCargo=".$id;
	
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
	
		return array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}
	
	function put_Cargo($cargo){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."cargo/editarCargo";
	
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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => 
			'{   
				"idCargo": "'. $cargo["id"] .'",
				"nome": "'. $cargo["nome"] .'",
				"idEmpresa": "'. $cargo["empresaid"] .'",
				"status": "'. $cargo["status"] .'"
			}',
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		$result = array();
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}

	function post_Cargo($cargo){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."cargo/cadastrarCargo";
	
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
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => 
			'{   
				"nome": "'. $cargo["nome"] .'",
				"idEmpresa": "'. $cargo["empresaid"] .'"
			}',
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		$result = array();
	
		curl_close($curl);
	
		return array(
			'status' => $statusCode,
			'results' => $resultado
		);
	
	}
	
	function get_listarCenariosDeVendas(){
	

		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."cenarioVenda/listarCenarioVenda";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'nome' 					=> $value['nome'],
						'idEmpresa'				=> $value['idEmpresa'],
						'nomeEmpresa' 			=> $value['nomeEmpresa'],
						'dataCadastro'			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'status' 				=> $value['status'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
		
		return array(
			'status' => $statusCode,
			'results' => $result
		);
	
	}

	function get_listarCenariosDeVendasPorEmpresa($idEmpresa){
	

		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."cenarioVenda/listarCenarioVendasById?idEmpresa=".$idEmpresa;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'nome' 					=> $value['nome'],
						'idEmpresa'				=> $value['idEmpresa'],
						'nomeEmpresa' 			=> $value['nomeEmpresa'],
						'dataCadastro'			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'status' 				=> $value['status'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
		
		return array(
			'status' => $statusCode,
			'results' => $result
		);
	
	}

	function to_cadastrarCenarioDeVenda($body){
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'cenarioVenda/cadastrarCenarioVenda';

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
			CURLOPT_POSTFIELDS => '{
				"nome": "'.$nome.'",
				"idEmpresa": "'.$idEmpresa.'"
				
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return array(
				'status' => $statusCode,
				'dados'  => $resultado,
		);
	}

	function to_editarCadCenarioDeVenda($body){

		$id = $body['id'];
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];
		$status = $body['status'];
		
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."cenarioVenda/atualizarCenarioVenda";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"nome": "'.$nome.'",
			"idEmpresa": "'.$idEmpresa.'",
			"status": "'.$status.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function get_listarConfCalculoComissao(){
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissao/listarConfigCalculoComissao";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 					=> $value['id'],
						'temMeta' 				=> $value['temMeta'],
						'temIndice'             => $value['temIndice'],
						'consideraProduto' 		=> $value['consideraProduto'],
						'consideraLicenca' 		=> $value['consideraLicenca'],
						'nomeConfig'			=> $value['nomeConfig'],
						'idEmpresa'				=> $value['idEmpresa'],
						'nomeEmpresa'			=> $value['nomeEmpresa'],
						'idRegional' 			=> $value['idRegional'],
						'nomeRegional' 			=> $value['nomeRegional'],
						'idCargo' 				=> $value['idCargo'],
						'nomeCargo' 			=> $value['nomeCargo'],
						'dataCadastro'			=> $value['dataCadastro'],
						'dataUpdate' 			=> $value['dataUpdate'],
						'status' 				=> $value['status'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		
		return array(
			'status' => $statusCode,
			'results' => $result
			);
	}

	function to_cadastrarConfigCalculoComissao($body){
		$temMeta = $body['temMeta'];
		$temIndice = $body['temIndice'];
		$consideraProduto = $body['consideraProduto'];
		$consideraLicenca = $body['consideraLicenca'];
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];
		$idRegional = $body['idRegional'];
		$idCargo = $body['idCargo'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissao/cadastrarConfigCalculoComissao';

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
			CURLOPT_POSTFIELDS => '{
				"temMeta": "'.$temMeta.'",
				"temIndice": "'.$temIndice.'",
				"consideraProduto": "'.$consideraProduto.'",
				"consideraLicenca": "'.$consideraLicenca.'",
				"nome": "'.$nome.'",
				"idEmpresa": "'.$idEmpresa.'",
				"idRegional": "'.$idRegional.'",
				"idCargo": "'.$idCargo.'"
				
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return	array(
				'status' => $statusCode,
				'dados'  => $resultado,
		);

	}

	function to_editarCadConfigCalculoComissao($body){
		$temMeta = $body['temMeta'];
		$temIndice = $body['temIndice'];
		$consideraProduto = $body['consideraProduto'];
		$consideraLicenca = $body['consideraLicenca'];
		$idConfig = $body['idConfig'];
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];
		$idRegional = $body['idRegional'];
		$idCargo = $body['idCargo'];
		$status = $body['status'];
		
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissao/editarConfigCalculoComissao";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"temMeta": "'.$temMeta.'",
			"temIndice": "'.$temIndice.'",
			"consideraProduto": "'.$consideraProduto.'",
			"consideraLicenca": "'.$consideraLicenca.'",
			"idConfig": "'.$idConfig.'",
			"nome": "'.$nome.'",
			"idEmpresa": "'.$idEmpresa.'",
			"idRegional": "'.$idRegional.'",
			"idCargo": "'.$idCargo.'",
			"status": "'.$status.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function to_cadastrarConfCalculoComissaoEItens($body){
		$temMeta = $body['temMeta'];
		$temIndice = $body['temIndice'];
		$consideraProduto = $body['consideraProduto'];
		$consideraLicenca = $body['consideraLicenca'];
		$nome = $body['nome'];
		$idEmpresa = $body['idEmpresa'];
		$idRegional = $body['idRegional'];
		$idCargo = $body['idCargo'];
		$itens = $body['itens'];
	
		$CI = &get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissao/cadastrarConfigCalculoComissaoEItens';
	
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
			CURLOPT_POSTFIELDS => '{
				"temMeta": "'.$temMeta.'",
				"temIndice": "'.$temIndice.'",
				"consideraProduto": "'.$consideraProduto.'",
				"consideraLicenca": "'.$consideraLicenca.'",
				"nome": "'.$nome.'",
				"idEmpresa": "'.$idEmpresa.'",
				"idRegional": "'.$idRegional.'",
				"idCargo": "'.$idCargo.'",
				"itens": '.json_encode($itens).'
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
	
		return	array(
				'status' => $statusCode,
				'dados'  => $resultado,
		);
		
	}

	function get_listarItensConfCalculoComissaoIdConfig($idConfig){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoItem/listarCalculoComissaoItemByConfigCalculoComissao?idConfig=".$idConfig;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				$result[] = array(
					'id' => $value['id'],
					'idConfigCalculoComissao' => $value['idConfigCalculoComissao'],
					'nomeCalculoComissao' => $value['nomeCalculoComissao'],
					'idCenarioVenda' => $value['idCenarioVenda'],
					'nomeCenarioVenda' => $value['nomeCenarioVenda'],
					'tipoCalculo' => $value['tipoCalculo'],
					'valorPercentual' => $value['valorPercentual'],
					'valorFixo' => $value['valorFixo'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status'],
				);
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'results' => $result
	
		);
	
	}

	function to_alterarStatusItemConfCalculoComissao($body){
		$idItem = $body['idItem'];
		$status = $body['status'];
	
		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoItem/alterarStatusConfigCalculoComissaoItem';
	
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
			CURLOPT_POSTFIELDS => '{
				"idItem": "'.$idItem.'",
				"status": "'.$status.'"
			}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	
	}

	function to_cadastrarItemConfCalculoComissao($body){

		$idConfigCalculoComissao = $body['idConfigCalculoComissao'];
		$idCenarioVenda = $body['idCenarioVenda'];
		$tipoCalculo = $body['tipoCalculo'];
		$valorPercentual = $body['valorPercentual'];
		$valorFixo = $body['valorFixo'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoItem/cadastrarConfigCalculoComissaoItem';

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
			CURLOPT_POSTFIELDS => '{
				"idConfigCalculoComissao": "'.$idConfigCalculoComissao.'",
				"idCenarioVenda": "'.$idCenarioVenda.'",
				"tipoCalculo": "'.$tipoCalculo.'",
				"valorPercentual": "'.$valorPercentual.'",
				"valorFixo": "'.$valorFixo.'"
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return	array(
				'status' => $statusCode,
				'dados'  => $resultado,
		);
	}

	function to_editarItemConfigCalculoComissao($body){

		$id = $body['id'];
		$idConfigCalculoComissao = $body['idConfigCalculoComissao'];
		$idCenarioVenda = $body['idCenarioVenda'];
		$tipoCalculo = $body['tipoCalculo'];
		$valorPercentual = $body['valorPercentual'];
		$valorFixo = $body['valorFixo'];
		$status = $body['status'];

		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoItem/atualizarCalculoComissaoItem";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"idConfigCalculoComissao": "'.$idConfigCalculoComissao.'",
			"idCenarioVenda": "'.$idCenarioVenda.'",
			"tipoCalculo": "'.$tipoCalculo.'",
			"valorPercentual": "'.$valorPercentual.'",
			"valorFixo": "'.$valorFixo.'",
			"status": "'.$status.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function get_listarTransportadoresAll(){
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."logistica/listarTransportadores";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 						=> $value['id'],
						'nomeTransportador' 		=> $value['razaoSocial'],
						'cpfCnpjTransportador'		=> $value['registro'],
						'cep' 						=> $value['cep'],
						'rua' 						=> $value['endereco'],
						'bairro' 					=> $value['bairro'],
						'cidade'					=> $value['cidade'],
						'estado' 					=> $value['uf'],
						'complemento' 				=> $value['complemento'],
						'dataCadastro' 				=> $value['dataCadastro'],
						'dataAtualizacao' 			=> $value['dataUpdate'],
						'status' 					=> $value['status'],
						'idEmpresa' 				=> $value['idEmpresa'],
						'nomeEmpresa' 				=> $value['nomeEmpresa'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'results' => $result
	
		);
	}

	function to_editarCadTransportadores($body){

		$idTransportador = $body['idTransportador'];
		$idEmpresa = $body['idEmpresa'];
		$nome = $body['nome'];
		$registro = $body['registro'];
		$cep = $body['cep'];
		$rua = $body['rua'];
		$bairro = $body['bairro'];
		$cidade = $body['cidade'];
		$uf = $body['uf'];
		$complemento = $body['complemento'];
		$status = $body['status'];

		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."logistica/editarCadastroTransportador";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"idTransportador": "'.$idTransportador.'",
			"idEmpresa": "'.$idEmpresa.'",
			"nome": "'.$nome.'",
			"registro": "'.$registro.'",
			"cep": "'.$cep.'",
			"rua": "'.$rua.'",
			"bairro": "'.$bairro.'",
			"cidade": "'.$cidade.'",
			"uf": "'.$uf.'",
			"complemento": "'.$complemento.'",
			"status": "'.$status.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function to_alterarStatusTransportador($body){
		$idTransportador = $body['idTransportador'];
		$status = $body['status'];
	
		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'logistica/alterarStatusCadastroTransportador';
	
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
			CURLOPT_POSTFIELDS => '{
				"idTransportador": "'.$idTransportador.'",
				"status": "'.$status.'"
			}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	
	}

	function get_VendedorPorNome($nome = ""){
		$CI = &get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest').'vendedor/listarVendedorByNome?nome=';
	
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
					'nome' => $value['nome'],
				);
			}
		}
			
		curl_close($curl);
	
		return json_encode(
			array(
				'status' => $statusCode,
				'results'       => $result,
				'pagination'    => [
					'more'      => false,
				]
			)
		);
	}



	function get_calcularComissao($idVendedor = null, $dataInicio = null,  $dataFim = null){
		$CI = &get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest').'televendas/calcularComissao?idVendedor='.$idVendedor."&dataInicio=".$dataInicio."&dataFim=".$dataFim;
		
	
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
			
		return array(
				'status' => $statusCode,
				'results' => $resultado
			)
		;
	}

	function get_buscarSeriais($nome){

        $CI =& get_instance();

        # URL configurada para a API
        $request = $CI->config->item('url_api_shownet_rest').'serial/listSerialByName?serial='.$nome;

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
        foreach ($resultado as $key => $value) {
            $result[] = array(
                'id' => $value['id'],
                'text' => $value['serial'],
                'marca' => $value['marca'],
				'modelo' => $value['modelo']
            );
        }

        curl_close($curl);


        return array(
                'status' => $statusCode,
                'results'       => $result,
                'pagination'    => [
                    'more'      => false,
                ]
		);
    }
	
function cadastrarSolicitacao($form, $equipamentos, $veiculos){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'solicitacaoExpedicao/cadastrarFichaSE';

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
	CURLOPT_POSTFIELDS => '
	{
		"idCliente": '.$form['clienteID'].',
		"tipoSolicitacao": '.$form['tipoSolicitacao'].',
		"solicitante": "'.$form['solicitante'].'",
		"data": "'.$form['data'].'",
		"enderecoEnvio": [
			{
				"logradouro": "'.$form['logradouro'].'",
				"bairro": "'.$form['bairro'].'",
				"cidade": "'.$form['cidade'].'",
				"estado": "'.$form['estado'].'",
				"cep": "'.$form['cep'].'",
				"complemento":"'.$form['complemento'].'"
			}
		],
		"equipamentos": '. json_encode($equipamentos). ',
		"veiculos": '.json_encode($veiculos).'
	}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results'  => $resultado
	);
}

function get_buscarInsumos(){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'insumos/listarInsumos';

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

	return array(
			'status' => $statusCode,
			'results'       => $resultado,
			'pagination'    => [
				'more'      => false,
			]
	);
}


function listarSolicitacoes(){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'solicitacaoExpedicao/listarCemUltimasSolicitacaoExpedicao';

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


	return array(
		'status' => $statusCode,
		'dados'       => $resultado
	);
}

function listarSolicitacoesExpedicaoBySECliente($data){
	$idSolicitacao = $data['idSolicitacao'];
	$idCliente = $data['idCliente'];
	$documento = $data['documento'];
	$contrato = $data['contrato'];
	$email = $data['email'];

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'solicitacaoExpedicao/listarSolicitacaoExpedicaoBySECliente?idSolicitacao=' . $idSolicitacao . '&idCliente=' . $idCliente . '&documento=' . $documento . '&contrato=' . $contrato . '&email=' . $email;

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


	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function listarSolicitacoes_select2(){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'solicitacaoExpedicao/listarSolicitacoesExpedicao';

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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ( $value['status'] == 'Ativo'){
					$result[] = array(
						'id' => $value['id']
					);
				}
			}
		}else{
			$result = $resultado;
		}

	curl_close($curl);


	return array(
		'status' => $statusCode,
		'results'       => $result
	);
}

function listarSolicitacoesById($id){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'solicitacaoExpedicao/listarSolicitacaoExpedicaoById?id='.$id;

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


	return array(
		'status' => $statusCode,
		'dados'       => $resultado
	);
}

	function to_getClientesVendedorTelevendas($emailVendedor){

		$CI =& get_instance();
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $CI->config->item('base_url_api_crmintegration').'/crmintegration/api/customers/GetClientesVendedorTelevendas?email_vendedor='.$emailVendedor.'&Token='.$CI->config->item('token_crm').'&qtdDias=0',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		));
	
		$response = json_decode(curl_exec($curl), true);

		$result = array();
		if ($response['Status'] == 200){
			foreach ($response['customers'] as $key => $value){
				$result[] = array(
					'nome' => $value['nome'] ? ($value['sobrenome'] ? ($value['nome'] . ' ' . $value['sobrenome']) : $value['nome']) : '',
					'nomeFantasia' => $value['nomeFantasia'] ? $value['nomeFantasia'] : '',
					'cpfCnpj' => $value['cpf'] ? $value['cpf'] : $value['cnpj'],
					'dataNascimento' => $value['dataNascimento'] ? $value['dataNascimento'] : '',
					'telefone' =>$value['telefone'] ? ($value['ddd'] ? '(' . $value['ddd'] . ') ' . $value['telefone'] : $value['telefone']) : '',
					'email' => $value['email'] ? $value['email'] : '',
					'loginVendedor' => $value['loginVendedor'] ? $value['loginVendedor'] : '',
				);
			}
		}else{
			$result = $response['customers'];
		}
	
		curl_close($curl);
	
		return array(
				'status' => $response['Status'],
				'results' => $result
	
		);
		
	}

	function salvar_auditoria($body){

		$id_user_logado = $body['id_user_logado'];
		$id = $body['id'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'auditoriaAcessoFurtivo/cadastrarAuditoriaAcessoFurtivo';

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
			CURLOPT_POSTFIELDS => '{
				"idUsuarioAcessado": "'.$id.'",
				"idUsuarioLogado": "'.$id_user_logado.'"
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return array(
			'status' => $statusCode,
			'dados'  => $resultado,
		);
	}

	function to_calcularComissaoVendedoresSelecionados($body){

		$dataInicio = $body['dataInicio'];
		$dataFim = $body['dataFim'];
		$idsVendedores = $body['idsVendedores'];
		
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."televendas/calcularComissaoVendedoresSelecionandos";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
			"dataInicio": "'.$dataInicio.'",
			"dataFim": "'.$dataFim.'",
			"idVendedores": '.json_encode($idsVendedores).'
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		
		return array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function get_listarItensComissaoCalculada($idComissaoCalculada){
	
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoItem/listarComissaoCalculadaById?idComissaoCalculada=".$idComissaoCalculada;
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				$result[] = array(
					'id' => $value['id'],
					'idComissaoCalculada' => $value['idComissaoCalculada'],
					'dataVenda' => $value['dataVenda'],
					'totalHw' => $value['valor'],
					'quantidade' => $value['quantidade'],
					'regra' => $value['valorFixo'] == "0.0" ? ($value['percentual'])."%" : $value['valorFixo'].",00",
					'comissao' => $value['comissao'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status'],
					'af' => $value['af'],
					'cliente' => $value['cliente'],
					'cenario' => $value['executivo']
				);
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'results' => $result
	
		);
	
	}

	function get_listarInsumosAll(){
		$CI =& get_instance();
	
	
		$request = $CI->config->item('url_api_shownet_rest')."insumos/listarInsumos";
	
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
		if ($statusCode == 200){
			foreach ($resultado as $key => $value){
				if ($value['status'] == 'Ativo'){
					$result[] = array(
						'id' 				=> $value['id'],
						'referencia' 		=> $value['referencia'],
						'nome'				=> $value['nome'],
						'unidadeEstoque' 	=> $value['unidadeEstoque'],
						'quantidadeEstoque'	=> $value['quantidadeEstoque'],
						'status' 			=> $value['status'],
						'dataCadastro'		=> $value['dataCadastro'],
						'dataUpdate' 		=> $value['dataUpdate'],
					);
				}
			}
		}else{
			$result = $resultado;
		}
	
		curl_close($curl);
	
		return array(
				'status' => $statusCode,
				'results' => $result
	
		);
	}

	function to_cadastrarInsumo($body){
		$referencia = $body['referencia'];
		$nome = $body['nome'];
		$unidadeEstoque = $body['unidade'];
		$quantidadeEstoque = $body['quantidade'];

		$CI = &get_instance();

		$request = $CI->config->item('url_api_shownet_rest').'insumos/cadastrarInsumo';

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
			CURLOPT_POSTFIELDS => '{
				"referencia": "'.$referencia.'",
				"nome": "'.$nome.'",
				"unidadeEstoque": "'.$unidadeEstoque.'",
				"quantidadeEstoque": "'.$quantidadeEstoque.'"
			}',
			CURLOPT_HTTPHEADER => $headers,
		));
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		
		return	array(
				'status' => $statusCode,
				'dados'  => $resultado,
		);

	}

	function to_editarCadInsumo($body){

		$idInsumo = $body['idInsumo'];
		$referencia = $body['referencia'];
		$nome = $body['nome'];
		$unidadeEstoque = $body['unidade'];
		$quantidadeEstoque = $body['quantidade'];
		
		$CI =& get_instance();
	
		$request = $CI->config->item('url_api_shownet_rest')."insumos/editarInsumo";
		
		$token = $CI->config->item('token_api_shownet_rest');
	
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';	   
		$headers[] = 'Authorization: Bearer '.$token;
	
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => '{
			"idInsumo": "'.$idInsumo.'",
			"referencia": "'.$referencia.'",
			"nome": "'.$nome.'",
			"unidadeEstoque": "'.$unidadeEstoque.'",
			"quantidadeEstoque": "'.$quantidadeEstoque.'",
			"status": 1
		}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	}

	function to_alterarStatusInsumo($body){
		$idInsumo = $body['idInsumo'];
	
		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'insumos/alterarStatusInsumo';
	
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
			CURLOPT_POSTFIELDS => '{
				"idInsumo": "'.$idInsumo.'",
				"status": 0
			}',
		CURLOPT_HTTPHEADER => $headers,
		));
	
		if (curl_error($curl))  throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		return	array(
				'status' => $statusCode,
				'dados'       => $resultado,
		);
	
	}

	function to_listarAgendamentos($dataInicial, $dataFinal){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getConversationsByPeriodName?dataInicial='.$dataInicial.'&dataFinal='.$dataFinal;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_listarAgendamentosPaginated($dataInicial, $dataFinal, $itemInicio, $itemFim){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getConversationsByPeriodNameNew?dataInicial='.$dataInicial.'&dataFinal='.$dataFinal.'&itemInicio='.$itemInicio.'&itemFim='.$itemFim;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_listarRelatorioPaginated($dataInicial, $dataFinal, $itemInicio, $itemFim, $tecnicoNome){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getSmsTechnicalReportByPeriodNameNew?itemInicio='.$itemInicio.'&itemFim='.$itemFim;

		if(isset($dataInicial) && isset($dataFinal)){
			$request .= "&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
		}

		if(isset($tecnicoNome)){
			$request .= "&nome=".$tecnicoNome;
		}
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_listarRelatorioDetalhadoPaginated($dataInicial, $dataFinal, $itemInicio, $itemFim, $tecnicoNome){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getMsgTechnicalReportByPeriodNameInstalacaoDetalhe?itemInicio='.$itemInicio.'&itemFim='.$itemFim;

		if(isset($dataInicial) && isset($dataFinal)){
			$request .= "&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
		}

		if(isset($tecnicoNome)){
			$request .= "&nome=".$tecnicoNome;
		}
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_listarRelatorioManutencaoDetalhadoPaginated($dataInicial, $dataFinal, $itemInicio, $itemFim, $tecnicoNome){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/getMsgTechnicalReportByPeriodNameManutencaoDetalhe?itemInicio='.$itemInicio.'&itemFim='.$itemFim;

		if(isset($dataInicial) && isset($dataFinal)){
			$request .= "&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
		}

		if(isset($tecnicoNome)){
			$request .= "&nome=".$tecnicoNome;
		}
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_listarTodosRelatorioPaginatedRoute($dataInicial, $dataFinal, $itemInicio, $itemFim, $tecnicoNome){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getMsgTechnicalReportByPeriodNameInstalacaoNew?itemInicio='.$itemInicio.'&itemFim='.$itemFim;

		if(isset($dataInicial) && isset($dataFinal)){
			$request .= "&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
		}

		if(isset($tecnicoNome)){
			$request .= "&nome=".$tecnicoNome;
		}
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}


	function to_listarRelatorioManutencaoPaginated($dataInicial, $dataFinal, $itemInicio, $itemFim, $tecnicoNome){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/getSmsTechnicalReportByPeriodNameManutencaoNew?itemInicio='.$itemInicio.'&itemFim='.$itemFim;

		if(isset($dataInicial) && isset($dataFinal)){
			$request .= "&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
		}

		if(isset($tecnicoNome)){
			$request .= "&nome=".$tecnicoNome;
		}
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}
	function to_listarRelatorioManutencaoConsolidadoPaginated($dataInicial, $dataFinal, $itemInicio, $itemFim, $tecnicoNome){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/getMsgTechnicalReportByPeriodNameManutencaoNew?itemInicio='.$itemInicio.'&itemFim='.$itemFim;

		if(isset($dataInicial) && isset($dataFinal)){
			$request .= "&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
		}

		if(isset($tecnicoNome)){
			$request .= "&nome=".$tecnicoNome;
		}
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_RecusaTecnicosInstalacao($dataInicial, $dataFinal){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getReportTecnicosRecusaByMotivos?dataInicial='.$dataInicial.'&dataFinal='.$dataFinal;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_RecusaTecnicosManutencao($dataInicial, $dataFinal){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/getReportTecnicosRecusaByMotivos?dataInicial='.$dataInicial.'&dataFinal='.$dataFinal;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_listarAgendamentosByConversation($idConversation){

		$CI =& get_instance();
	 
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/listarAgendamentoByIdConversaAndStatus?idConversa='.$idConversation;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}
	function to_listarAgendamentosByConversationPaginated($idConversation, $startRow, $endRow){

		$CI =& get_instance();
	 
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/listarAgendamentoByIdConversaAndStatusNew?idConversa='.$idConversation.'&itemInicio='.$startRow.'&itemFim='.$endRow;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_listarAgendamentosByStatus($status){

		$CI =& get_instance();
	 
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/listarAgendamentoByIdConversaAndStatus?status='.$status;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}
	function to_listarAgendamentosByStatusPaginated($status, $startRow, $endRow){

		$CI =& get_instance();
	 
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/listarAgendamentoByIdConversaAndStatusNew?status='.$status.'&itemInicio='.$startRow.'&itemFim='.$endRow;

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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function listarAgendamentosPosVendaByStatusPaginated($status, $startRow, $endRow){

		$CI =& get_instance();
	 
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/listarAgendamentoByIdConversaAndStatusNew?status='.$status.'&itemInicio='.$startRow.'&itemFim='.$endRow;

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
	
		return array(
			'status' => $statusCode,
			'resultado'  => $resultado
		);
	}

	function to_buscarAgendamento($idConversation){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getConversationsById?idConversation='.$idConversation;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_agenda_instalacao($idConversation){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getAgendaInstalacaoById?idConversa='.$idConversation;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_audit_tracker($idConversation, $idInstalador){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getAuditTrackerLinkInstallerById?idConversation='.$idConversation.'&idInstalador='.$idInstalador;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_audit_tracker_manutencao($idConversation, $idMantenedor){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'auditTrackerManutencao/listarAuditByIdConversaAndManutencao?idConversation='.$idConversation.'&idMantenedor='.$idMantenedor;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_sms_installers($idConversation){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'infobip/getSmsSendInstallersById?idConversation='.$idConversation;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

	function to_sms_manutencao($idConversation){

		$CI =& get_instance();
	
		# URL configurada para a API
		$request = $CI->config->item('url_api_shownet_rest').'msgSendManutencao/listarMsgSendByIdConversa?idConversation='.$idConversation;
	
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
	
		return array(
			'status' => $statusCode,
			'dados'  => $resultado
		);
	}

function listar_dados_post($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPost?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}

function listar_dados_post_by_data($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostPorData?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}

function listar_dados_post_by_period($dataInicial, $dataFinal, $terminal) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostByPeriod?dataInicial=".$dataInicial."&dataFinal=".$dataFinal.'&terminal='.$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return  array(
				'status' => $statusCode,
				'results' => $result
			);
}

function listar_dados_post_ctrl($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostCtrl?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}

function listar_dados_post_ctrl_by_data($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostCtrlPorData?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}

function listar_dados_post_ctrl_by_period($dataInicial, $dataFinal, $terminal) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostCtrlByPeriod?dataInicial=".$dataInicial."&dataFinal=".$dataFinal.'&terminal='.$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return  array(
				'status' => $statusCode,
				'results' => $result
			);
}

function listar_dados_post_iscas_by_data($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostIscasPorData?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}

function listar_dados_post_iscas_by_period($dataInicial, $dataFinal, $terminal) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostIscasByPeriod?dataInicial=".$dataInicial."&dataFinal=".$dataFinal.'&terminal='.$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return  array(
				'status' => $statusCode,
				'results' => $result
			);
}

function listar_dados_post_omnifrota_by_data($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostOmniFrotaPorData?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}


function listar_dados_post_omnifrota_by_period($dataInicial, $dataFinal, $terminal) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostOmniFrotaByPeriod?dataInicial=".$dataInicial."&dataFinal=".$dataFinal.'&terminal='.$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return  array(
				'status' => $statusCode,
				'results' => $result
			);
}

function listar_dados_post_omnisafe_by_data($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostOmniSafePorData?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}

function listar_dados_post_omnisafe_by_period($dataInicial, $dataFinal, $terminal) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostOmniSafeByPeriod?dataInicial=".$dataInicial."&dataFinal=".$dataFinal.'&terminal='.$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return  array(
				'status' => $statusCode,
				'results' => $result
			);
}

function listar_dados_post_omnisafe_ctrl_by_data($terminal){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostOmniSafeCtrlPorData?terminal=".$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $result

		)
	);

}

function listar_dados_post_omnisafe_ctrl_by_period($dataInicial, $dataFinal, $terminal) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."post/listarDadosPostOmniSafeCtrlByPeriod?dataInicial=".$dataInicial."&dataFinal=".$dataFinal.'&terminal='.$terminal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
				$result[] = array(
					'id' 					=> $value['id'],
					'dataHora' 			=> $value['dataHora'],
					'terminal'			=> $value['terminal'],
					'post' 			=> $value['post'],
					'codmsg' 			=> $value['codmsg'],
				);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return  array(
				'status' => $statusCode,
				'results' => $result
			);
}

function get_listarFaturasMesReferencia($idOperadora, $mesReferencia){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."fatura/listarCemUltimasFaturasByData?idOperadora=" .$idOperadora."&mesReferencia=".$mesReferencia;

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

	return array(
		'status' => $statusCode,
		'results' => $resultado
	);
}


function get_itensContratoVenda($idTipoVeiculo){
	
	# Cria instância do CI
    $CI =& get_instance();

	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	$request = $CI->config->item('base_url_crm').'tz_item_contrato_vendas?fetchXml=%3Cfetch%20version%3D%221.0%22%20output-format%3D%22xml-platform%22%20mapping%3D%22logical%22%20distinct%3D%22true%22%3E%3Centity%20name%3D%22tz_item_contrato_venda%22%3E%3Cattribute%20name%3D%22tz_name%22%20%2F%3E%3Cattribute%20name%3D%22tz_cliente_pjid%22%20%2F%3E%3Cattribute%20name%3D%22tz_cliente_pfid%22%20%2F%3E%3Cattribute%20name%3D%22tz_status_item_contrato%22%20%2F%3E%3Cattribute%20name%3D%22tz_veiculoid%22%20%2F%3E%3Cattribute%20name%3D%22tz_data_entrada%22%20%2F%3E%3Cattribute%20name%3D%22tz_data_ativacao%22%20%2F%3E%3Cattribute%20name%3D%22tz_rastreadorid%22%20%2F%3E%3Cattribute%20name%3D%22tz_numero_serie_modulo_principal%22%20%2F%3E%3Cattribute%20name%3D%22tz_embarcado_pj%22%20%2F%3E%3Cattribute%20name%3D%22tz_embarcado_pf%22%20%2F%3E%3Cattribute%20name%3D%22tz_item_contrato_vendaid%22%20%2F%3E%3Cattribute%20name%3D%22tz_plano_linkerid%22%20%2F%3E%3Corder%20attribute%3D%22tz_name%22%20descending%3D%22false%22%20%2F%3E%3Clink-entity%20name%3D%22tz_tipo_veiculo_cotacao_tz_item_contrato_v%22%20from%3D%22tz_item_contrato_vendaid%22%20to%3D%22tz_item_contrato_vendaid%22%20alias%3D%22ad%22%3E%3Cfilter%20type%3D%22and%22%3E%3Ccondition%20attribute%3D%22tz_tipo_veiculo_cotacaoid%22%20operator%3D%22eq%22%20uitype%3D%22tz_tipo_veiculo_cotacao%22%20value%3D%22%7B'.$idTipoVeiculo.'%7D%22%20%2F%3E%3C%2Ffilter%3E%3C%2Flink-entity%3E%3Clink-entity%20name%3D%22contact%22%20from%3D%22contactid%22%20to%3D%22tz_cliente_pfid%22%20link-type%3D%22outer%22%3E%3Cattribute%20name%3D%22yomifullname%22%20%2F%3E%3C%2Flink-entity%3E%3Clink-entity%20name%3D%22tz_veiculo%22%20from%3D%22tz_veiculoid%22%20to%3D%22tz_veiculoid%22%20link-type%3D%22outer%22%3E%3Cattribute%20name%3D%22tz_placa%22%20%2F%3E%3C%2Flink-entity%3E%3Clink-entity%20name%3D%22product%22%20from%3D%22productid%22%20to%3D%22tz_plano_linkerid%22%20link-type%3D%22outer%22%3E%3Cattribute%20name%3D%22name%22%20%2F%3E%3C%2Flink-entity%3E%3Clink-entity%20name%3D%22product%22%20from%3D%22productid%22%20to%3D%22tz_rastreadorid%22%20link-type%3D%22outer%22%3E%3Cattribute%20name%3D%22name%22%20%2F%3E%3C%2Flink-entity%3E%3Clink-entity%20name%3D%22account%22%20from%3D%22accountid%22%20to%3D%22tz_cliente_pjid%22%20link-type%3D%22outer%22%3E%3Cattribute%20name%3D%22zatix_nomefantasia%22%20%2F%3E%3Cattribute%20name%3D%22name%22%20%2F%3E%3C%2Flink-entity%3E%3C%2Fentity%3E%3C%2Ffetch%3E';
	
		
	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = json_decode(curl_exec($curl));
	$data = array();
	$dados = array();
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$statusItemDeContrato = '';
	
	if(isset($response->value)){
		$data = $response->value;
		foreach ($data as $key => $value) {
			if (isset($value->tz_status_item_contrato)){
				switch ($value->tz_status_item_contrato) {
					case 1:
						$statusItemDeContrato = 'Ativo';
						break;
					case 2:
						$statusItemDeContrato = 'Aguardando ativação';
						break;
					case 3:
						$statusItemDeContrato = 'Cancelado';
						break;
					case 4:
						$statusItemDeContrato = 'Suspenso';
						break;
					case 5:
						$statusItemDeContrato = 'Ativação 90 dias';
						break;
					default:
						$statusItemDeContrato = '';
						break;
				}
			}
			$dados[] = array(
				'nome' => $value->tz_name,
				'numSerieModuloPrincipal' => isset($value->tz_numero_serie_modulo_principal) ? $value->tz_numero_serie_modulo_principal : '',
				'statusItemContrato' => $statusItemDeContrato,
				'dataCriacao' => isset($value->tz_data_entrada) ? $value->tz_data_entrada : '',
				'cliente' => isset($value->contact2_x002e_yomifullname) ? $value->contact2_x002e_yomifullname : $value->account6_x002e_name,
				'veiculo' => isset($value->tz_veiculo3_x002e_tz_placa) ? $value->tz_veiculo3_x002e_tz_placa : '',
				'plano' => isset($value->product4_x002e_name) ? $value->product4_x002e_name : '',
				'rastreador' => isset($value->product5_x002e_name) ? $value->product5_x002e_name : '',
				'itemContratoVendaId' => $value->tz_item_contrato_vendaid,
			);
		}
	}
	curl_close($curl);
	
	return array(
		"status" => $statusCode,
		"data" => $dados
	);
}

function get_contratosRelacionar($idCliente){
	
	# Cria instância do CI
    $CI =& get_instance();

	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	$request = $CI->config->item('base_url_crm').'tz_item_contrato_vendas?fetchXml=%3Cfetch%20version%3D%221.0%22%20output-format%3D%22xml-platform%22%20mapping%3D%22logical%22%20distinct%3D%22false%22%3E%0A%20%20%3Centity%20name%3D%22tz_item_contrato_venda%22%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_name%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_cliente_pjid%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_cliente_pfid%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_status_item_contrato%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_veiculoid%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_data_entrada%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_data_ativacao%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_rastreadorid%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_numero_serie_modulo_principal%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_embarcado_pj%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_embarcado_pf%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_item_contrato_vendaid%22%20%2F%3E%0A%20%20%20%20%3Cattribute%20name%3D%22tz_plano_linkerid%22%20%2F%3E%0A%20%20%20%20%3Corder%20attribute%3D%22tz_name%22%20descending%3D%22false%22%20%2F%3E%0A%20%20%20%20%3Cfilter%20type%3D%22and%22%3E%0A%20%20%20%20%20%20%3Cfilter%20type%3D%22or%22%3E%0A%20%20%20%20%20%20%20%20%3Ccondition%20attribute%3D%22tz_cliente_pfid%22%20operator%3D%22eq%22%20uitype%3D%22contact%22%20value%3D%22%7B'.$idCliente.'%7D%22%20%2F%3E%0A%20%20%20%20%20%20%20%20%3Ccondition%20attribute%3D%22tz_cliente_pjid%22%20operator%3D%22eq%22%20uitype%3D%22account%22%20value%3D%22%7B'.$idCliente.'%7D%22%20%2F%3E%0A%20%20%20%20%20%20%3C%2Ffilter%3E%0A%20%20%20%20%3C%2Ffilter%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22contact%22%20from%3D%22contactid%22%20to%3D%22tz_cliente_pfid%22%20link-type%3D%22outer%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22yomifullname%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22tz_veiculo%22%20from%3D%22tz_veiculoid%22%20to%3D%22tz_veiculoid%22%20link-type%3D%22outer%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22tz_placa%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22product%22%20from%3D%22productid%22%20to%3D%22tz_plano_linkerid%22%20link-type%3D%22outer%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22name%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22product%22%20from%3D%22productid%22%20to%3D%22tz_rastreadorid%22%20link-type%3D%22outer%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22name%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%20%20%3Clink-entity%20name%3D%22account%22%20from%3D%22accountid%22%20to%3D%22tz_cliente_pjid%22%20link-type%3D%22outer%22%3E%0A%20%20%20%20%20%20%3Cattribute%20name%3D%22name%22%20%2F%3E%0A%20%20%20%20%3C%2Flink-entity%3E%0A%20%20%3C%2Fentity%3E%0A%3C%2Ffetch%3E';
	
		
	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = json_decode(curl_exec($curl));
	$data = array();
	$dados = array();
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$statusItemDeContrato = '';
	
	if(isset($response->value)){
		$data = $response->value;
		foreach ($data as $key => $value) {
			if (isset($value->tz_status_item_contrato)){
				switch ($value->tz_status_item_contrato) {
					case 1:
						$statusItemDeContrato = 'Ativo';
						break;
					case 2:
						$statusItemDeContrato = 'Aguardando ativação';
						break;
					case 3:
						$statusItemDeContrato = 'Cancelado';
						break;
					case 4:
						$statusItemDeContrato = 'Suspenso';
						break;
					case 5:
						$statusItemDeContrato = 'Ativação 90 dias';
						break;
					default:
						$statusItemDeContrato = '';
						break;
				}
			}
			$dados[] = array(
				'nome' => isset($value->tz_name) ? $value->tz_name : '',
				'itemContratoVendaId' => isset($value->tz_item_contrato_vendaid) ? $value->tz_item_contrato_vendaid : '',
				'numSerieModuloPrincipal' => isset($value->tz_numero_serie_modulo_principal) ? $value->tz_numero_serie_modulo_principal : '',
				'rastreador' => isset($value->product4_x002e_name) ? $value->product4_x002e_name : '',
				'plano' => isset($value->product3_x002e_name) ? $value->product3_x002e_name : '',
				'veiculo' => isset($value->tz_veiculo2_x002e_tz_placa) ? $value->tz_veiculo2_x002e_tz_placa : '',
				'dataCriacao' => isset($value->tz_data_entrada) ? $value->tz_data_entrada : '',
				'cliente' => isset($value->contact1_x002e_yomifullname) ? $value->contact1_x002e_yomifullname : $value->account5_x002e_name,
				'statusItemContrato' => $statusItemDeContrato,
			);
		}
	}
	curl_close($curl);
	
	return array(
		"status" => $statusCode,
		"data" => $dados
	);
}

function to_associarDissociarContratosComposicaoCotacao($body){
	$idCotacao = $body['idCotacao'];
	$associar = $body['associar'];

	if (is_array($body['contratos'])){
		$contratos = implode('","', $body['contratos']);
	}else{
		$contratos = $body['contratos'];
	}
	

	$CI = &get_instance();
	
	$token_crm 	= $CI->config->item('token_crm');

	$request = $CI->config->item('base_url_api_crmintegration').'/crmintegration/api/contract/AssociarDissociarContratosComposicaoCotacao?cotacao='.$idCotacao.'&associar='.$associar.'&Token='.$token_crm;

	$curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '[ 
			"'.$contratos.'"
		]',
		CURLOPT_HTTPHEADER => array(
		  'Authorization : Bearer '.$token_crm,
		  'Content-Type: application/json'
		),
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);

	$data = array();
	
	curl_close($curl);

	$data = $resultado;

	return array(
		'status' => $resultado['Status'],
		"data" => $data
	);
}


function get_listarLinhasByLinhaCcid($linha, $ccid){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."linhas/listarLinhasOperadorasByLinhaUltimoCcid?linha=".$linha."&ultimoCcid=".$ccid;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			if ($value['status'] == 'Ativo'){
				$result[] = array(
					'id' => $value['id'],
					'linha' => $value['linha'],
					'ultimoCcid' => $value['ultimoCcid'],
					'idOperadora' => $value['idOperadora'],
					'idEmpresa' => $value['idEmpresa'],
					'idFornecedor' => $value['idFornecedor'],
					'statusOperadora' => $value['statusOperadora'],
					'numeroConta' => $value['numeroConta'],
					'ultimoSerialEquipamento' => $value['ultimoSerialEquipamento'],
					'ultimoStatusCrm' => $value['ultimoStatusCrm'],
					'ultimaComunicacaoRadius' => $value['ultimaComunicacaoRadius'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status'],
				);
			}
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarRelatorioSmsPorPeriodo($dataInicial, $dataFinal){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."infobip/getSmsTechnicalReportByPeriod?dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"idTecnico" => $value['idTecnico'],
				"nomeTecnico" => $value['nomeTecnico'],
				"descricao" => $value['descricao'],
				"quantidade" => $value['quantidade'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarPedidosGerados(){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGerados";

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'filial' 	=> $value['filial'],
				'numero' 	=> $value['numero'],						
				'tipo' 		=> $value['tipo'],
				'af'		=> $value['af'],
				'dataEmissao' 	=> $value['dataEmissao'],
				'dataCreated' 	=> $value['dataCreated'],
				'dataUpdated' 	=> $value['dataUpdated'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);

}

function get_listarPedidosGeradosNFAmarraBI(){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosNFAmarraBILimited";

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'filial' 	    => $value['filial'],
				'numPedido' 	=> $value['numPedido'],						
				'numDocumento'  => $value['numDocumento'],
				'serie'		    => $value['serie'],
				'numCliente' 	=> $value['numCliente'],
				'numLoja'       => $value['numLoja'],
				'status'        => $value['status'],   
				'dataCreated' 	=> $value['dataCreated'],
				'dataUpdated' 	=> $value['dataUpdated'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);

}

function get_buscarPedidosGeradosNFAmarraBI($numPedido, $numDocumento, $numCliente) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosNFAmarraBIByFields?numPedido=".$numPedido."&numDocumento=".$numDocumento."&numCliente=".$numCliente;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'filial' 	    => $value['filial'],
				'numPedido' 	=> $value['numPedido'],						
				'numDocumento'  => $value['numDocumento'],
				'serie'		    => $value['serie'],
				'numCliente' 	=> $value['numCliente'],
				'numLoja'       => $value['numLoja'],
				'status'        => $value['status'],   
				'dataCreated' 	=> $value['dataCreated'],
				'dataUpdated' 	=> $value['dataUpdated'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarLinhasRecentes(){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."linhas/listarLinhasOperadorasRecentes";

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			if ($value['status'] == 'Ativo'){
				$result[] = array(
					'id' => $value['id'],
					'linha' => $value['linha'],
					'ultimoCcid' => $value['ultimoCcid'],
					'idOperadora' => $value['idOperadora'],
					'idEmpresa' => $value['idEmpresa'],
					'idFornecedor' => $value['idFornecedor'],
					'statusOperadora' => $value['statusOperadora'],
					'numeroConta' => $value['numeroConta'],
					'ultimoSerialEquipamento' => $value['ultimoSerialEquipamento'],
					'ultimoStatusCrm' => $value['ultimoStatusCrm'],
					'ultimaComunicacaoRadius' => $value['ultimaComunicacaoRadius'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status'],
				);
			}
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarLinhasByOperadoraCcid($idOperadora, $ccid){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."linhas/listarOperadoraCCID?idOperadora=".$idOperadora."&ultimoCCID=" .$ccid;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			if ($value['status'] == 'Ativo'){
				$result[] = array(
					'id' => $value['id'],
					'linha' => $value['linha'],
					'ultimoCcid' => $value['ultimoCcid'],
					'idOperadora' => $value['idOperadora'],
					'idEmpresa' => $value['idEmpresa'],
					'idFornecedor' => $value['idFornecedor'],
					'statusOperadora' => $value['statusOperadora'],
					'numeroConta' => $value['numeroConta'],
					'ultimoSerialEquipamento' => $value['ultimoSerialEquipamento'],
					'ultimoStatusCrm' => $value['ultimoStatusCrm'],
					'ultimaComunicacaoRadius' => $value['ultimaComunicacaoRadius'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status'],
				);
			}
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarPedidosGeradosComNF(){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosComNF";

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

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado

		)
	);

}

function get_listarPedidosGeradosComNFBiExpedicao(){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosNFAmarraBIExpedicaoLimited";

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

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado

		)
	);

}

function get_listarPedidosGeradosComNFBiRomaneio(){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosNFAmarraBIRomaneioLimited";

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

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado

		)
	);

}

function get_listarLinhasByOperadoraLinha($idOperadora, $linha){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."linhas/listarOperadoraLinha?idOperadora=".$idOperadora."&linha=" .$linha;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			if ($value['status'] == 'Ativo'){
				$result[] = array(
					'id' => $value['id'],
					'linha' => $value['linha'],
					'ultimoCcid' => $value['ultimoCcid'],
					'idOperadora' => $value['idOperadora'],
					'idEmpresa' => $value['idEmpresa'],
					'idFornecedor' => $value['idFornecedor'],
					'statusOperadora' => $value['statusOperadora'],
					'numeroConta' => $value['numeroConta'],
					'ultimoSerialEquipamento' => $value['ultimoSerialEquipamento'],
					'ultimoStatusCrm' => $value['ultimoStatusCrm'],
					'ultimaComunicacaoRadius' => $value['ultimaComunicacaoRadius'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status'],
				);
			}
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarUltimosCemSmsRelatorioTecnicos(){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."infobip/getUltimosCemSmsTechnicalReport";
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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"idTecnico" => $value['idTecnico'],
				"nomeTecnico" => $value['nomeTecnico'],
				"descricao" => $value['descricao'],
				"quantidade" => $value['quantidade'],
				"acao" => $value['acao']

			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarUltimosCemSmsRelatorioTecnicosManutencao(){
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."infobipAgendamentoManutencao/getUltimosCemSmsTechnicalReportManutencao";
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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"idTecnico" => $value['idTecnico'],
				"nomeTecnico" => $value['nomeTecnico'],
				"descricao" => $value['descricao'],
				"quantidade" => $value['quantidade'],
				"acao" => $value['acao']
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function to_listarTecnicos(){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'infobip/listarTecnicoByIdAndName';

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

	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"id" => $value['nome'],
				"text" => $value['nome'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'dados'  => $result
	);
}

function to_listarTecnicosManutencao(){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/listarTecnicosMantenedores';

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

	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"id" => $value['nome'],
				"text" => $value['nome'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'dados'  => $result
	);
}

function get_listarRelatorioSmsTecnicoEData($tecnico, $dataInicial, $dataFinal){
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."infobip/getSmsTechnicalReportByPeriodName?nome=".$tecnico."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"idTecnico" => $value['idTecnico'],
				"nomeTecnico" => $value['nomeTecnico'],
				"descricao" => $value['descricao'],
				"quantidade" => $value['quantidade'],
				"acao" => $value['acao']
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_listarRelatorioManutencaoSmsTecnicoEData($tecnico, $dataInicial, $dataFinal){
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."infobipAgendamentoManutencao/getSmsTechnicalReportByPeriodNameManutencao?nome=".$tecnico."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"idTecnico" => $value['idTecnico'],
				"nomeTecnico" => $value['nomeTecnico'],
				"descricao" => $value['descricao'],
				"quantidade" => $value['quantidade'],	
				"acao" => $value['acao']
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function register_user_access($id_usuario, $login_usuario, $url_acessada, $data_acesso) {
	
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."mapaCalor/cadastrarMapaCalor";

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';       
	$headers[] = 'Authorization: Bearer ' . $token;
	
	$curl = curl_init();

	$register = array(
		'idUsuarioLogado' => $id_usuario,
		'login_usuario' => $login_usuario,
		'urlAcessada' => $url_acessada,
		'data_acesso' => $data_acesso
	);

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($register),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);

	curl_close($curl);

	$data = $resultado;

	return array(
		"data" => $data
	);
}

function get_listarMapaCalor(){

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."mapaCalor/listarCemUltimosMapaCalor";
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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"idUsuarioLogado" => $value['idUsuarioLogado'],
				"urlAcessada" => $value['urlAcessada'],
				"loginUsuario" => $value['loginUsuario'],
				"qtdAcessos" => $value['qtdAcessos'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

    return array(
        'status' => $statusCode,
        'results' => $result
    );
}

function get_mapaCalorByUserOrData($dataInicial, $dataFinal, $idUsuario = null) {
    $CI =& get_instance();

	if ($idUsuario !== null) {
        $id_user = $idUsuario;

		$request = $CI->config->item('url_api_shownet_rest')."mapaCalor/listarMapaCalorByUsuarioPeriodo?idUsuario=".$id_user."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
		
    }else{
		$request = $CI->config->item('url_api_shownet_rest')."mapaCalor/listarMapaCalorByUsuarioPeriodo?idUsuario=".$idUsuario."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;
	}

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"idUsuarioLogado" => $value['idUsuarioLogado'],
				"urlAcessada" => $value['urlAcessada'],
				"loginUsuario" => $value['loginUsuario'],
				"qtdAcessos" => $value['qtdAcessos'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

    return array(
        'status' => $statusCode,
        'results' => $result
    );

}

function listarUsuariosAtivoMapa(){

    $CI =& get_instance();

    $request = $CI->config->item('url_api_shownet_rest')."mapaCalor/listarEmailUsuarios";
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
 
    if ($statusCode == 200){
        $emails = [];
        foreach ($resultado as $value) {
            if (!in_array($value['emailUsuario'], $emails)) {
                $emails[] = $value['emailUsuario'];
                $result[] = array("emailUsuario" => $value['emailUsuario']);
            }
        }

    }else{
        $result = $resultado;
    }

    curl_close($curl);

    return array(
        'status' => $statusCode,
        'results' => $result
    );

}



function get_detalhamentoFrota($idTipoVeiculo){
	
	# Cria instância do CI
    $CI =& get_instance();
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	
	$request = $CI->config->item('base_url_crm').'tz_detalhamento_frotas?fetchXml=%3Cfetch%20version%3D%221.0%22%20output-format%3D%22xml-platform%22%20mapping%3D%22logical%22%20distinct%3D%22false%22%3E%20%3Centity%20name%3D%22tz_detalhamento_frota%22%3E%20%20%3Cattribute%20name%3D%22tz_detalhamento_frotaid%22%20/%3E%20%20%3Cattribute%20name%3D%22tz_name%22%20/%3E%20%20%3Cattribute%20name%3D%22tz_numero_serie_rastreador%22%20/%3E%20%20%3Cattribute%20name%3D%22tz_numero_serie_antena_satelital%22%20/%3E%20%20%3Corder%20attribute%3D%22tz_name%22%20descending%3D%22false%22%20/%3E%20%20%3Cfilter%20type%3D%22and%22%3E%20%20%20%3Ccondition%20attribute%3D%22tz_tipo_veiculo_cotacaoid%22%20operator%3D%22eq%22%20uitype%3D%22tz_tipo_veiculo_cotacao%22%20value%3D%22%7B'.$idTipoVeiculo.'%7D%22%20/%3E%20%20%3C/filter%3E%20%3C/entity%3E%20%3C/fetch%3E';
	
		
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);
	$response = json_decode(curl_exec($curl));
	$data = array();
	$dados = array();
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$statusItemDeContrato = '';
	
	if(isset($response->value)){
		$data = $response->value;
		foreach ($data as $key => $value) {
			$dados[] = array(
				'tz_name' => isset($value->tz_name) ? $value->tz_name : '',
				'tz_detalhamento_frotaid' => isset($value->tz_detalhamento_frotaid) ? $value->tz_detalhamento_frotaid : '',
				'numeroSerieRastreador' => isset($value->tz_numero_serie_rastreador) ? $value->tz_numero_serie_rastreador : '',
				'numeroSerieAntenaSatelital' => isset($value->tz_numero_serie_antena_satelital) ? $value->tz_numero_serie_antena_satelital : '',
				'quantidade' => isset($value->tz_quantidade) ? $value->tz_quantidade : '',
			);
		}
	}
	curl_close($curl);
	
	return array(
		"status" => $statusCode,
		"data" => $dados
	);
}

function delete_detalhamentoFrota($idDetalhamentoFrota){
	
	# Cria instância do CI
    $CI =& get_instance();
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	
	$request = $CI->config->item('base_url_crm').'tz_detalhamento_frotas('.$idDetalhamentoFrota.')';
	
		
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_CUSTOMREQUEST => 'DELETE',
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);
	$response = json_decode(curl_exec($curl));
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	curl_close($curl);
	
	return array(
		"status" => $statusCode,
		"data" => $response
	);
}

function to_addDetalhamentoFrota($body){

	$composicao_cotacaoid = $body['idComposicaoCotacao'];
	$numeroSerieRastreador = $body['numeroSerieRastreador'];
	$numeroSerieAntenaSatelital = $body['numeroSerieAntenaSatelital'];
	$emailVendedor = $body['emailVendedor'];

	$CI = &get_instance();

	$token_crm 	= $CI->config->item('token_crm');
	$request = $CI->config->item('base_url_api_crmintegration').'/crmintegration/api/contract/AddDetalhamentoFrota?Token='.$token_crm;
	$curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
			"composicao_cotacaoid": "'.$composicao_cotacaoid.'",
			"tz_numero_serie_rastreador": "'.$numeroSerieRastreador.'",
			"tz_numero_serie_antena_satelital": "'.$numeroSerieAntenaSatelital.'",
			"userNameVendedor": "'.$emailVendedor.'"
		}',
		CURLOPT_HTTPHEADER => array(
		  'Authorization : Bearer '.$token_crm,
		  'Content-Type: application/json'
		),
	));
	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$data = array();
	
	curl_close($curl);
	$data = $resultado;
	return array(
		'status' => $resultado['Status'],
		"data" => $data
	);
}

function get_RelatorioVeiculosAtualizados($idCliente, $dataInicial, $dataFinal){
    $CI =& get_instance();
	
    $request = $CI->config->item('url_api_shownet_rest') . "relatorios/relatorioDiasRastreados";
    $token = $CI->config->item('token_api_shownet_rest');

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ];

    $curl = curl_init();

    $dados = [
        'idCliente' => $idCliente,
        'dataInicio' => $dataInicial,
        'dataFim' => $dataFinal,
    ];

    curl_setopt_array($curl, [
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
    ]);

    if (curl_error($curl)) throw new Exception(curl_error($curl));

    $resultado = json_decode(curl_exec($curl), true);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

	$dadosRetorno = array();

    if ($statusCode == 200){	
		$dia = data_for_unix($dataInicial);
        $dataFinalFormatada = data_for_unix($dataFinal);
        while ($dia <= $dataFinalFormatada)
        {
            $dias[] = $dia;
            $dia = date('Y-m-d', strtotime($dia . ' + 1 days'));
        }

        $intervalo = count($dias);

        foreach ($resultado as $key => $value)
        {
            $dadosRetorno[] = array(
               	"idContrato" => $value['idContrato'],
               	"placa" => $value['placa'],
				"contagemDias" => $value['contagemDias'],
				"contagemDiasSemAtt" => $intervalo - $value['contagemDias'],
               	"valorPeriodo" => $value['valorPeriodo'],
			);
		}
    }

	return array(
		'status' => $statusCode,
		'results' => $dadosRetorno,
	);
}


function getlistarSeriaisAll(){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'equipamentoExpedicao/listarEquipamentosShowTecsystem?pageNumber=1&pageSize=1000';

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
	foreach ($resultado as $key => $value) {
		$result[] = array(
			'id' => $value['id'],
			'marca' => $value['marca'],
			'modelo' => $value['modelo'],
			'serial' => $value['serial'],
		);
	}

	curl_close($curl);


	return array(
			'status' => $statusCode,
			'results'       => $result,
			'pagination'    => [
				'more'      => false,
			]
	);
}
/*
* RETORNA OS DADOS DE TODOS OS VEICULOS DE UM CLIENTE
*/
function get_listarVeiculosByCliente($id_cliente){
    $CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'veiculo/listarVeiculosByCliente';
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer '.$token;

	$field = array(
		'idCliente' => (int)$id_cliente,
	);

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
		CURLOPT_POSTFIELDS => json_encode($field),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$result = array();
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"code" => $value['code'],
				"veiculo" => $value['veiculo'],
				"placa" => $value['placa'],
				"serial" => $value['serial'],
				"prefixoVeiculo" => $value['prefixoVeiculo'],
				"contratos_veiculo" => $value['contratos_veiculo']
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_usuariosByNome($nome){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'usuario/listarUsuariosByNome?nome='.$nome;

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
				'text' => $value['nome'],
			);
		}
	}
		

	curl_close($curl);

	return json_encode(
		array(
			'status' => $statusCode,
			'results'       => $result,
			'pagination'    => [
				'more'      => false,
			]
		)
	);
}

function get_listarUsuariosAll(){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'usuario/listarUsuarios';

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
				'text' => $value['nome'],
			);
		}
	}
		
	curl_close($curl);

	return array(
				'status' => $statusCode,
				'results'       => $result,
				'pagination'    => [
					'more'      => false,
				]
			);
	
}

function to_AlterarCliente($body){
	$idUsuario = $body['idUsuario'];
	$idCliente = $body['idCliente'];

	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'usuario/alterarUsuarioCliente';

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
		CURLOPT_POSTFIELDS => '{
			"idUsuario": "'.$idUsuario.'",
			"idCliente": "'.$idCliente.'"
		}',
		CURLOPT_HTTPHEADER => $headers,
	));
	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	
	return	array(
		'status' => $statusCode,
		'dados'  => $resultado,
	);

}

function to_solicitarCalculoVendedoresSelecionados($body){

	$dataInicio = $body['dataInicio'];
	$dataFim = $body['dataFim'];
	$idVendedores = $body['idVendedores'];
	
	
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."solCalculoComissao/cadastrarSolCalculoComissao";
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => '{
		"dataInicio": "'.$dataInicio.'",
		"dataFim": "'.$dataFim.'",
		"idVendedores": '.json_encode($idVendedores).'
	}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	
	return array(
			'status' => $statusCode,
			'dados'       => $resultado,
	);
}

function get_listarSolicitacoesCalculoComissao(){
	$CI =& get_instance();
	

	$request = $CI->config->item('url_api_shownet_rest')."solCalculoComissao/listarSolCalculoComissao";

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

	
	return	array(
			'status' => $statusCode,
			'results' => $resultado

	);

}

function get_listarComissoesCalculadasBySolicitacao($idSolicitacao){
	$CI =& get_instance();
	

	$request = $CI->config->item('url_api_shownet_rest')."televendas/listarRelatoriosComissoesBySolicitacao?idSolicitacao=".$idSolicitacao;

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

	
	return	array(
			'status' => $statusCode,
			'results' => $resultado

	);

}

function get_buscarPedidosGeradosNFAmarraBIExpedicao($numPedido, $notaFiscal) {
	$CI =& get_instance();
	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosNFAmarraBIExpedicaoByFields?numPedido=".$numPedido."&notaFiscal=".$notaFiscal;
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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'filial' 	    => $value['filial'],
				'numPedido' 	=> $value['numPedido'],						
				'notaFiscal'  => $value['notaFiscal'],
				'serie'		    => $value['serie'],
				'romaneio' 	=> $value['romaneio'],
				'codigoTransacao'       => $value['codigoTransacao'],
				'nomeTransportadora'        => $value['nomeTransportadora'],  
				'dataExpedicao'	=> $value['dataExpedicao'], 
				'dataCreated' 	=> $value['dataCreated'],
				'dataUpdated' 	=> $value['dataUpdated'],
			);
		}
	}else{
		$result = $resultado;
	}
	curl_close($curl);
	return array(
		'status' => $statusCode,
		'results' => $result
	);
}

function get_buscarPedidosGeradosNFAmarraBIRomaneio($numPedido, $numPedido2, $notaFiscal) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosNFAmarraBIRomaneioByFields?numPedido=".$numPedido."&numPedido2=".$numPedido2."&notaFiscal=".$notaFiscal;
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
	return array(
		'status' => $statusCode,
		'results' => $resultado
	);
}

function to_revisarCotacao($idCotacao, $loginUsuario){

	$CI =& get_instance();

	$token_crm = $CI->config->item('token_crm');

	$request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/RevisarCotacaoTelevendas?idCotacao=".$idCotacao."&Token=".$token_crm."&userNameVendedor=".$loginUsuario;

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Content-length: 0';

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_HTTPHEADER => $headers,
	CURLOPT_CUSTOMREQUEST => 'POST',
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$response = json_decode(curl_exec($curl), true);


	curl_close($curl);
	return $response;
}


function to_listarFornecedores($idEmpresa){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'logistica/listarFornecedores?idEmpresa='.$idEmpresa;

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

	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				"id" => $value['id'],
				"idEmpresa" => $value['idEmpresa'],
				"nomeEmpresa" => $value['nomeEmpresa'],
				"razaoSocial" => $value['razaoSocial'],
				"registro" => $value['registro'],
				"cep" => $value['cep'],
				"endereco" => $value['endereco'],
				"bairro" => $value['bairro'],
				"cidade" => $value['cidade'],
				"uf" => $value['uf'],
				"complemento" => $value['complemento'],
				"dataCadastro" => $value['dataCadastro'],
				"dataUpdate" => $value['dataUpdate'],
				"status" => $value['status']
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'dados'  => $result
	);
}


function get_buscarRelatoriosTickets($dataInicial, $dataFinal, $mesAno){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'tickets/listarTicketsByDate?dataInicial='.$dataInicial."&dataFinal=".$dataFinal."&mesAno=".$mesAno;

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
	$concluidos = 0;
	$total = 0;

        
	if($statusCode == 200){
		foreach ($resultado as $key => $value) {
			$result[] = array(
				'id' => strval($value['id']),
				'cliente' => $value['cliente'],
				'data_abertura' => $value['dataAbertura'],
				'departamento' => $value['departamento'],
				'assunto' => $value['assunto'],
				'ultima_interacao' => $value['ultimaInteracao'],
				'status' => strval($value['status'])
			);

			if ($value['status'] == '3') {
				// Adiciona concluido
				$concluidos++;
			}

			$total++;
		}
	}
		
	curl_close($curl);

	return array(
				'status' => $statusCode,
				'resultados'       => $result,
				'concluidos' => $concluidos,
				'total' => $total
			);
	
}

function get_dadosTempoContrato($POSTFIELDS){
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'relatorios/custoContratoAdm';
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'resultado' => $resultado
	);
}

function to_testeComunicacaoChiop($serial){
	$valor_criar = 1;

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'testeComunicacao/criarTesteComunicacao';

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';       
	$headers[] = 'Authorization: Bearer ' . $token;

	$curl = curl_init();

	$dados = array(
		'idTerminal' => $serial,
		'testeCel1' => $valor_criar,
		'testeCel2' => $valor_criar,
		'testeSat' => $valor_criar,
	);

	curl_setopt_array($curl, [
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
	]);

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'dados'  => $resultado,
	);

}

function to_listarTesteComunicacaoChiop(){
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'testeComunicacao/listarTestesComunicacao';

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';       
	$headers[] = 'Authorization: Bearer ' . $token;

	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => $headers,
	]);

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'dados'  => $resultado,
	);

}

function get_listarMetaByidConfiguracao($idConfiguracao){
	$CI =& get_instance();
	

	$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoMeta/listarMetaByIdConfigCalculoComissao?idConfigCalculoComissao=".$idConfiguracao;

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

	
	return array(
			'status' => $statusCode,
			'results' => $resultado
		);

}

function to_cadastrarMetas($body){

	$idConfiguracao = $body['idConfigCalculoComissao'];
	$metas = $body['metas'];

	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoMeta/cadastrarConfigCalculoComissaoMetas';

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
		CURLOPT_POSTFIELDS => '{
			"idConfigCalculoComissao": "'.$idConfiguracao.'",
			"metas": '.json_encode($metas).'
		}',
		CURLOPT_HTTPHEADER => $headers,
	));
	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);


	return array(
			'status' => $statusCode,
			'dados'  => $resultado,
		);

}

function to_editarMeta($body){

	$id = $body['id'];
    $idConfigCalculoComissao = $body['idConfigCalculoComissao'];
    $idCenarioVenda = $body['idCenarioVenda'];
    $percentualMin = $body['percentualMin'];
    $percentualMax = $body['percentualMax'];
    $percentualComissaoProduto = $body['percentualComissaoProduto'];
    $percentualComissaoLicenca = $body['percentualComissaoLicenca'];
    $metaProduto = $body['metaProduto'];
    $metaLicenca = $body['metaLicenca'];
    $status = $body['status'];
 
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoMeta/atualizarConfigCalculoComissaoMeta";
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'PUT',
	CURLOPT_POSTFIELDS => '{
		"id": "'.$id.'",
		"idConfigCalculoComissao": "'.$idConfigCalculoComissao.'",
		"idCenarioVenda": "'.$idCenarioVenda.'",
		"percentualMin": "'.$percentualMin.'",
		"percentualMax": "'.$percentualMax.'",
		"percentualComissaoProduto": "'.$percentualComissaoProduto.'",
		"percentualComissaoLicenca": "'.$percentualComissaoLicenca.'",
		"metaProduto": "'.$metaProduto.'",
		"metaLicenca": "'.$metaLicenca.'",
		"status": "'.$status.'"
	}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
			'status' => $statusCode,
			'dados'       => $resultado,
	);
}

function to_alterarStatusMeta($body){
	$id = $body['id'];
	$status = $body['status'];

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoMeta/alterarStatusConfigCalculoComissaoMeta';

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
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"status": "'.$status.'"
		}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return	array(
			'status' => $statusCode,
			'dados'       => $resultado,
	);

}

function get_listarIndiceByidConfiguracao($idConfiguracao){
	$CI =& get_instance();
	

	$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoIndice/listarConfigCalculoComissaoIndiceByIdConfigCalculo?IdConfigCalculo=".$idConfiguracao;

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

	
	return array(
			'status' => $statusCode,
			'results' => $resultado
		);

}

function to_cadastrarIndices($body){

	$idConfiguracao = $body['idConfigCalculoComissao'];
	$indices = $body['indices'];

	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoIndice/cadastrarListaConfigCalculoComissaoIndices';

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
		CURLOPT_POSTFIELDS => '{
			"idConfigCalculoComissao": "'.$idConfiguracao.'",
			"indices": '.json_encode($indices).'
		}',
		CURLOPT_HTTPHEADER => $headers,
	));
	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);


	return array(
			'status' => $statusCode,
			'dados'  => $resultado,
		);

}

function to_editarIndice($body){

	$id = $body['id'];
    $idConfigCalculoComissao = $body['idConfigCalculoComissao'];
    $idCenarioVenda = $body['idCenarioVenda'];
    $tipoIndicador = $body['tipoIndicador'];
    $metaMinIndicador = $body['metaMinIndicador'];
    $metaMaxIndicador = $body['metaMaxIndicador'];
    $percentualLimiteSalario = $body['percentualLimiteSalario'];
    $periodicidade = $body['periodicidade'];
    $metaQuantidade = $body['metaQuantidade'];
	$metaValor = $body['metaValor'];
    $status = $body['status'];
 
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoIndice/editarConfigCalculoComissaoIndice";
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'PUT',
	CURLOPT_POSTFIELDS => '{
		"id": "'.$id.'",
		"idConfigCalculoComissao": "'.$idConfigCalculoComissao.'",
		"idCenarioVenda": "'.$idCenarioVenda.'",
		"tipoIndicador": "'.$tipoIndicador.'",
		"metaMinIndicador": "'.$metaMinIndicador.'",
		"metaMaxIndicador": "'.$metaMaxIndicador.'",
		"percentualLimiteSalario": "'.$percentualLimiteSalario.'",
		"periodicidade": "'.$periodicidade.'",
		"metaQuantidade": "'.$metaQuantidade.'",
		"metaValor": "'.$metaValor.'",
		"status": "'.$status.'"
	}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
			'status' => $statusCode,
			'dados'       => $resultado,
	);
}

function to_alterarStatusIndice($body){
	$id = $body['id'];
	$status = $body['status'];

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoIndice/alterarStatusConfigCalculoComissaoIndice';

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
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"status": "'.$status.'"
		}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return	array(
			'status' => $statusCode,
			'dados'       => $resultado,
	);

}
function to_cadastrarItensIndice($body){

	$idCalculoComissaoIndicador = $body['idCalculoComissaoIndicador'];
	$indiceItens = $body['indiceItens'];

	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoIndiceItens/cadastrarListaConfigCalculoComissaoIndiceItens';

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
		CURLOPT_POSTFIELDS => '{
			"idCalculoComissaoIndicador": "'.$idCalculoComissaoIndicador.'",
			"indiceItens": '.json_encode($indiceItens).'
		}',
		CURLOPT_HTTPHEADER => $headers,
	));
	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);


	return array(
			'status' => $statusCode,
			'dados'  => $resultado,
		);

}

function get_listarItensIndiceByIdIndice($idIndice){
	$CI =& get_instance();
	

	$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoIndiceItens/listarConfigCalculoComissaoIndiceItensByIndice?idIndice=".$idIndice;

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

	
	return array(
			'status' => $statusCode,
			'results' => $resultado
		);

}

function to_editarItemIndice($body){

	$idItem = $body['idItem'];
    $idCalculoComissaoIndicador = $body['idCalculoComissaoIndicador'];
    $tipoIndicador = $body['tipoIndicador'];
    $percMeta = $body['percMeta'];
    $percSalario = $body['percSalario'];
    $status = $body['status'];
 
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoIndiceItens/editarConfigCalculoComissaoIndiceItens";
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $request,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'PUT',
	CURLOPT_POSTFIELDS => '{
		"idItem": "'.$idItem.'",
		"idCalculoComissaoIndicador": "'.$idCalculoComissaoIndicador.'",
		"tipoIndicador": "'.$tipoIndicador.'",
		"percMeta": "'.$percMeta.'",
		"percSalario": "'.$percSalario.'",
		"status": "'.$status.'"
	}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
			'status' => $statusCode,
			'dados'       => $resultado,
	);
}

function to_alterarStatusItemIndice($body){
	$id = $body['id'];
	$status = $body['status'];

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'configCalculoComissaoIndiceItens/alterarStatusConfigCalculoComissaoIndiceItens';

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
		CURLOPT_POSTFIELDS => '{
			"id": "'.$id.'",
			"status": "'.$status.'"
		}',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return	array(
			'status' => $statusCode,
			'dados'       => $resultado,
	);
}

function to_listarRazaoValidacao($idCotacao){

	$CI =& get_instance();

	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	$request = $CI->config->item('base_url_crm').'tz_checklist_advs?$select=tz_descricao,tz_name,tz_observacao,tz_situacao&$filter=_tz_cotacaoid_value%20eq%20'. $idCotacao;

	$curl = curl_init();
 
    curl_setopt_array($curl, [
        CURLOPT_URL            => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
        CURLOPT_USERPWD        => "{$username}:{$passwd}",
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json'
        ]
    ]);

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$response = json_decode(curl_exec($curl), true);

	curl_close($curl);
	return $response;
	
}

function to_listarUltimosCemAgendamentosManutencao(){
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."infobipAgendamentoManutencao/listarTopCemAgendamentosManutencao";

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
	
	return array(
			'status' => $statusCode,
			'dados' => $resultado
		);

}

function to_listarAgendamentoManutencaoByPeriodName($dataInicial, $dataFinal){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'conversasManutencao/getConversationsManutencaoByPeriodName?dataInicial='.$dataInicial.'&dataFinal='.$dataFinal;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_listarAgendamentoManutencaoByPeriodNamePaginated($dataInicial, $dataFinal, $itemInicio, $itemFim){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'conversasManutencao/getConversationsManutecaoByPeriodNameNew?dataInicial='.$dataInicial.'&dataFinal='.$dataFinal.'&itemInicio='.$itemInicio.'&itemFim='.$itemFim;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_listarAgendamentoManutencaoByConversation($idConversation, $itemInicio, $itemFim){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'conversasManutencao/listarAgendamentoMByIdConversaAndStatusNew?id='.$idConversation.'&itemInicio='.$itemInicio.'&itemFim='.$itemFim;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_listarAgendamentoManutencaoByConversationPaginated($idConversation, $itemInicio, $itemFim){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/listarAgendamentoMByIdConversaAndStatusNew?id='.$idConversation.'&itemInicio='.$itemInicio.'&itemFim='.$itemFim;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_listarAgendamentoManutencaoByStatus($status){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'conversasManutencao/listarAgendamentoMByIdConversaAndStatus?status='.$status;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_listarAgendamentoManutencaoByStatusPaginated($status, $itemInicio, $itemFim){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/listarAgendamentoMByIdConversaAndStatusNew?status='.$status.'&itemInicio='.$itemInicio.'&itemFim='.$itemFim;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_listarDadosClientePJ($idCliente){

	$CI =& get_instance();

	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	$request = $CI->config->item('base_url_crm').'accounts('.$idCliente.')?$select=name,zatix_cnpj,emailaddress1,tz_nome_signatario_mei,tz_cpf_cnpj_signatario_mei,tz_email_signatario_mei';

	$curl = curl_init();
 
    curl_setopt_array($curl, [
        CURLOPT_URL            => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
        CURLOPT_USERPWD        => "{$username}:{$passwd}",
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json'
        ]
    ]);

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$response = json_decode(curl_exec($curl), true);

	curl_close($curl);
	return $response;
	
}

function to_listarDadosClientePF($idCliente){

	$CI =& get_instance();

	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	$request = $CI->config->item('base_url_crm').'contacts('.$idCliente.')?$select=fullname,zatix_cpf,emailaddress1,tz_nome_signatario_mei,tz_cpf_cnpj_signatario_mei,tz_email_signatario_mei';

	$curl = curl_init();
 
    curl_setopt_array($curl, [
        CURLOPT_URL            => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
        CURLOPT_USERPWD        => "{$username}:{$passwd}",
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json'
        ]
    ]);

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$response = json_decode(curl_exec($curl), true);

	curl_close($curl);
	return $response;
	
}

function to_buscarAgendamentoManutencao($idConversation){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/listarAgendamentosManutencaoByIdConversa?idConversa='.$idConversation;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_buscarConversationManutencao($idConversation){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'conversasManutencao/listarConversasById?id='.$idConversation;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_alterarStatusAgendamento($idAgendamento, $status){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'infobip/alterarStatusAgendaInstalacao';

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$body = array(
		'id' => $idAgendamento,
		'status' => $status
	);

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
		CURLOPT_POSTFIELDS => json_encode($body),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return	array(
		'status' => $statusCode,
		'dados'  => $resultado,
	);

}


function get_listarItensComissaoCalculadaTeveCampanha($idComissaoCalculada){
	
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."configCalculoComissaoItem/listarComissaoCalculadaItensCampanhaById?idComissaoCalculada=".$idComissaoCalculada;

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
	if ($statusCode == 200 && count($resultado['itens']) > 0){
		foreach ($resultado['itens'] as $key => $value){
			$result[] = array(
				'id' => $value['id'],
				'idComissaoCalculada' => $value['idComissaoCalculada'],
				'dataVenda' => $value['dataVenda'],
				'totalHw' => $value['valor'],
				'quantidade' => $value['quantidade'],
				'regra' => $value['valorFixo'] == "0.0" ? ($value['percentual'])."%" : $value['valorFixo'].",00",
				'comissao' => $value['comissao'],
				'dataCadastro' => $value['dataCadastro'],
				'dataUpdate' => $value['dataUpdate'],
				'status' => $value['status'],
				'af' => $value['af'],
				'cliente' => $value['cliente'],
				'cenario' => $value['executivo']
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);


	return	array(
			'status' => $statusCode,
			'results' => $result
	);

}

function to_listarAgendamentosManutencaoDashboard($dataInicial, $dataFinal){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'conversasManutencao/getConversationsManutencaoByPeriodName?dataInicial='.$dataInicial.'&dataFinal='.$dataFinal;

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

	return array(
		'status' => $statusCode,
		'dados'  => $resultado
	);
}

function to_botaoCotacaoAcao($body){

	# Cria instância do CI
    $CI =& get_instance();

	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request 	= $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/BotaoCotacao?Token=".$token_crm;
	
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
	CURLOPT_POSTFIELDS =>json_encode($body),
		CURLOPT_HTTPHEADER => array(
		  'Content-Type: application/json'
		),
	  ));
		  

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;	   	
	
}

function get_competidores(){
	$fetchXml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
				  <entity name="competitor">
				    <attribute name="name" />
				    <attribute name="websiteurl" />
				    <attribute name="competitorid" />
				    <order attribute="name" descending="false" />
				  </entity>
				</fetch>';

	$encodedFetchXml = urlencode($fetchXml);
	
	$url = "competitors?fetchXml={$encodedFetchXml}";

	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').$url;

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;				
}

function get_armazens() {
	$fetchXml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
			<entity name="tz_armazem">
				<attribute name="tz_armazemid"/>
				<attribute name="tz_name" />
				<attribute name="createdon" />
				<order attribute="tz_name" descending="false" />
				<filter type="and">
					<condition attribute="tz_padrao" operator="eq" value="0" />
					<condition attribute="statecode" operator="eq" value="0" />
				</filter>
			</entity>
		</fetch>
		';
	$encodedFetchXml = urlencode($fetchXml);
	$url = "tz_armazems?fetchXml={$encodedFetchXml}";

	# Cria instância do CI
	$CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').$url;

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;
}

function to_alterarStatusAgendamentoManutencao($idAgendamento, $status){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'infobipAgendamentoManutencao/alterarStatusAgendaManutencao';

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$body = array(
		'id' => $idAgendamento,
		'status' => $status
	);

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
		CURLOPT_POSTFIELDS => json_encode($body),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return	array(
		'status' => $statusCode,
		'dados'  => $resultado,
	);

}

function to_resetarChip($linha, $idOperadora, $idUsuarioSolicitacao){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').'resetLinha/cadastrarSolResetLinha';

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer '.$token;

	$body = array(
		'linha' => $linha,
		'idOperadora' => $idOperadora,
		'idUsuarioSolicitacao' => $idUsuarioSolicitacao
	);

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
		CURLOPT_POSTFIELDS => json_encode($body),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = curl_exec($curl);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return	array(
		'status' => $statusCode,
		'dados'  => $resultado,
	);

}

function to_atualizarLicencaContratoCliente($servicoContratadoId){
	# Cria instância do CI
    $CI =& get_instance();

	$token_crm 	= $CI->config->item('token_crm');

	# configuração para a API
    $request = $CI->config->item('base_url_api_crmintegration')."/crmintegration/api/contract/IntegraServicoContratado?servicoContratadoId=".$servicoContratadoId."&Token=".$token_crm;
	
	$headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Content-length: 0';
	
	$curl = curl_init();
	
	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER     => $headers,
		CURLOPT_CUSTOMREQUEST => 'POST'
	));
	  
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}


function get_listaUltimos100PedidosGerados(){
	
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosLimited";

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'filial' 	=> $value['filial'],
				'numero' 	=> $value['numero'],						
				'tipo' 		=> $value['tipo'],
				'af'		=> $value['af'],
				'dataEmissao' 	=> $value['dataEmissao'],
				'dataCreated' 	=> $value['dataCreated'],
				'dataUpdated' 	=> $value['dataUpdated'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);

}

function get_listar100UltimosPedidosGeradosComNF(){
	
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosComNFLimited";

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

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado

		)
	);

}

function get_listarPedidosGeradosByNumPedidoOrAFOrDate($numeroPedido, $af, $dataInicial, $dataFinal){
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosByFields?numeroPedido=".$numeroPedido."&af=".$af."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal;

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
	if ($statusCode == 200){
		foreach ($resultado as $key => $value){
			$result[] = array(
				'filial' 	=> $value['filial'],
				'numero' 	=> $value['numero'],						
				'tipo' 		=> $value['tipo'],
				'af'		=> $value['af'],
				'dataEmissao' 	=> $value['dataEmissao'],
				'dataCreated' 	=> $value['dataCreated'],
				'dataUpdated' 	=> $value['dataUpdated'],
			);
		}
	}else{
		$result = $resultado;
	}

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'results' => $result
	);

}

function get_listarPedidosGeradosComNFByNumPedidoOrAFOrDate($numeroPedido, $notaFiscal, $dataEmissaoInicial, $dataEmissaoFinal){
	
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."levantamentoPedidos/listarPedidosGeradosComNFByFields?numeroPedido=".$numeroPedido."&notaFiscal=".$notaFiscal."&dataEmissaoInicial=".$dataEmissaoInicial."&dataEmissaoFinal=".$dataEmissaoFinal;

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

	echo json_encode(
		array(
			'status' => $statusCode,
			'results' => $resultado

		)
	);

}

function get_plataformas(){
	$fetchXml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
				    <entity name="tz_plataforma">
				        <attribute name="tz_plataformaid" />
				        <attribute name="tz_name" />
				        <order attribute="tz_name" descending="false" />
				        <filter type="and">
				            <condition attribute="tz_disponivel_televendas" operator="eq" value="1" />
				        </filter>
				    </entity>
				</fetch>';

	$encodedFetchXml = urlencode($fetchXml);
	
	$url = "tz_plataformas?fetchXml={$encodedFetchXml}";

	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').$url;

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;							

}

function get_Iscas_Ultimo_dia($terminal){

	$CI =& get_instance();

	# URL configurada para a API
	$request = $CI->config->item('url_api_shownet_rest').'post/listarDadosPostIscasPorData?terminal='.$terminal;

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


	return array(
			'status' => $statusCode,
			'results'       => $resultado,
			'pagination'    => [
				'more'      => false,
			]
	);
}

function get_DadosIscasPaginated($itemInicio, $itemFim, $serial) {	
    $url = 'lastTrack/listarDadosLastTrackOneDay?idObjectTracker='. $serial.'&itemInicio='. $itemInicio.'&itemFim='.$itemFim;
    $result = to_get($url);
    return $result;
}

function get_cenariosDdeVEndaByPlataforma($idPlataforma){
	$fetchXml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
				    <entity name="tz_cenario_venda">
				        <attribute name="tz_cenario_vendaid" />
				        <attribute name="tz_name" />
				        <attribute name="createdon" />
				        <order attribute="tz_name" descending="false" />
				        <filter type="and">
				            <condition attribute="tz_plataformaid" operator="eq" uiname="Omnilink" uitype="tz_plataforma"
				                value="{'.$idPlataforma.'}" />
				            <condition attribute="tz_disponivel_televendas" operator="eq" value="1" />
				        </filter>
				    </entity>
				</fetch>';

	$encodedFetchXml = urlencode($fetchXml);
	
	$url = "tz_cenario_vendas?fetchXml={$encodedFetchXml}";

	# Cria instância do CI
    $CI =& get_instance();
	
	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	

	# configuração para a API
	$request 	= $CI->config->item('base_url_crm').$url;

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL            => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
		CURLOPT_USERPWD        => "{$username}:{$passwd}",
		CURLOPT_HTTPHEADER     => [
			'Accept: application/json'
		]
	]);

	$response = curl_exec($curl);
		
	curl_close($curl);
	
	return $response;							

}

function to_listarDadosClienteAviso($idCliente, $tipo){

	$CI =& get_instance();

	$username 	= $CI->config->item('username_crm');
	$passwd		= $CI->config->item('password_crm');	
	$tipoBusca = $tipo == "PJ" ? "accounts" : "contacts";
	$request = $CI->config->item('base_url_crm').''.$tipoBusca.'('.$idCliente.')?$select=tz_desbloqueioportal,zatix_atendimentoriveiculo,zatix_bloqueiototal,zatix_comunicacaochip,zatix_comunicacaosatelital,zatix_emissaopv';

	$curl = curl_init();
 
    curl_setopt_array($curl, [
        CURLOPT_URL            => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
        CURLOPT_USERPWD        => "{$username}:{$passwd}",
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json'
        ]
    ]);

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$response = json_decode(curl_exec($curl), true);

	curl_close($curl);
	return $response;
	
}

function get_listarDadosIridium($serial){
	
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."api/iridium/getSubscriberAccountById/".$serial;

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


	return array(
		'status' => $statusCode,
		'dados' => $resultado
	);
	

}

function get_UltimosAcessosWSTTPaginated($email, $dataInicial, $dataFinal, $itemInicio, $itemFim) {
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest')."wstt/acessos/listarUlitmosAcessosByEmail?email=".$email."&dataInicio=".$dataInicial."&dataFim=".$dataFinal."&itemInicio=".$itemInicio."&itemFim=".$itemFim;

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


	return array(
		'status' => $statusCode,
		'resultado' => $resultado
	);
}

if ( ! function_exists('removerAcentos') ) {
	function removerAcentos($string){
		$caracteres_sem_acento = array(
			'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Â'=>'Z', 'Â'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
			'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
			'Ï'=>'I', 'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
			'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
			'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
			'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'Å'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
			'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
			'Ä'=>'a', 'î'=>'i', 'â'=>'a', 'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T',
		);
		
		return strtr($string, $caracteres_sem_acento);
	}
}
// FATURAS / DÉBITOS - CLIENTES
function get_listarAnexosNFFatura($idFatura){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."fatura/buscarFaturasShowtecsystemPorId?id=" .$idFatura;

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

	return array(
		'status' => $statusCode,
		'results' => $resultado
	);
}

function post_inserirAnexoFatura($POSTFIELDS) {
	$url = 'anexos/inserirAnexoFatura';

	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest').$url;
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'resultado' => $resultado
	);
}

function post_atualizarAnexoFatura($POSTFIELDS) {
	$url = 'anexos/editarAnexoFatura';
	$CI =& get_instance();

	$request = $CI->config->item('url_api_shownet_rest').$url;
	
	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';	   
	$headers[] = 'Authorization: Bearer '.$token;

	$curl = curl_init();
	
	$body = json_encode($POSTFIELDS);

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PATCH',
		CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
		CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'resultado' => $resultado
	);
}


function delete_anexoNF($id){
	$CI =& get_instance();


	$request = $CI->config->item('url_api_shownet_rest')."anexos/excluirAnexoFatura?id=" .$id;

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
	CURLOPT_CUSTOMREQUEST => 'DELETE',
	CURLOPT_HTTPHEADER => $headers,
	));

	if (curl_error($curl))  throw new Exception(curl_error($curl));
	$resultado = json_decode(curl_exec($curl), true);
	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return array(
		'status' => $statusCode,
		'resultado' => $resultado
	);
}

function get_clientesGerais($itemInicio, $itemFim, $nome, $id){
    $url = ('clienteVendas/listarClientesPorParametrosPaginado?itemInicio='.$itemInicio . '&itemFim='. $itemFim);
	if (isset($nome) && $nome){
		$url.= '&nome=' . urlencode($nome);
	}
	if (isset($id) && $id){
		$url = ('clienteVendas/listarClientesPorParametrosPaginado?idCliente='.$id);
	}
    return to_get($url);
}