<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use LDAP\Result;

if ( ! function_exists('dd') )
{
    function dd($data)
    {
        exit('<pre>' . print_r($data) . '</pre>');
    }	
}

// GET
function get_Autorizadores(){	
	$result = to_get('aprovadores/listarAprovadoresNome');
	return $result;
}

function get_AutorizadoresPendentes(){	
	$result = to_get('aprovadores/listarSituacaoAprovadoresNome');
	return $result;
}

function get_DadosAutorizadoresPaginated($startRow, $endRow){	
	$result = to_get('aprovadores/listarAprovadores?itemInicio='.$startRow .'&itemFim='.$endRow);
	return $result;
}

function get_DadosAutorizadoresByUser($usuario){	
	$result = to_get('aprovadores/listarAprovadoresByFields?codigo='.$usuario);
	return $result;
}

function get_DadosAutorizadoresByFilterPaginated($startRow, $endRow, $aprovador, $dataInicio, $dataFim){
		$result = to_get('aprovadores/listarSituacaoAprovadorByFields?itemInicio='.$startRow.'&itemFim='.$endRow.'&codigoAprovador='.$aprovador.'&dataInicial='.$dataInicio.'&dataFinal='.$dataFim);
		return $result;
}

function get_DadosPedidoPaginated($startRow, $endRow, $aprovador, $dataInicio, $dataFim) {    
    $url = 'aprovadores/listarSituacaoAprovadorByFields?itemInicio=' . $startRow . '&itemFim=' . $endRow . '&codigoAprovador=' . $aprovador;

    if ($dataInicio != '') {
        $url .= '&dataInicial=' . $dataInicio;
    }
    if ($dataFim != '') {
        $url .= '&dataFinal=' . $dataFim;
    }
    $result = to_get($url);
    return $result;
}


function get_codAprovador($login){	
	$result = to_get('aprovadores/listarAssociacaoByIdUsuario?idUsuario='.$login);
	return $result;
}

function get_usuario($usuario){	
	$result = to_get('usuario/listarUsuariosByNome?nome='.$usuario);
	return $result;
}

function get_DadosPedidoById($filial, $pedido){	
	$result = to_get('levantamentoPedidos/listarDetalhePedidosByFilialAndPedido?filial='.$filial.'&pedido='.$pedido);
	return $result;
}


function post_AssociarAprovovador ($POSTFIELDS){
	$result = to_post('aprovadores/cadastrarAssociacaoUsuarioAprovador', $POSTFIELDS); 
	return $result;
}
	
function get_AutorizadoresSelect($nome){	
	$result = to_get('aprovadores/listarAprovadoresByFields?nome='.$nome);
	return $result;
}

//Post
function post_Pedido($POSTFIELDS){	
	$result = to_post('aprovadores/aprovarPedidoCompra', $POSTFIELDS); 
	return $result;
}

//UTILITÁRIOS

function to_get($url){
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest').$url;

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

function to_post($url, $POSTFIELDS){
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