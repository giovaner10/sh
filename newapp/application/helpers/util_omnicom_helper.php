<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use LDAP\Result;

if ( ! function_exists('dd') )
{
    function dd($data)
    {
        exit('<pre>' . print_r($data) . '</pre>');
    }	
}

function get_listarTecnologiasPaginado($startRow, $endRow, $idTecnologia, $idCliente){
	$url = 'tecnologia/listarTecnologiaByIdsPaginada?itemInicio='.$startRow.'&itemFim='.$endRow;

	if (isset($idTecnologia) && $idTecnologia){
		$url.= '&idTecnologia=' . $idTecnologia;
	}
	if (isset($idCliente) && $idCliente){
		$url.= '&idCliente=' . $idCliente;
	}
	
	$result = to_get($url);

	return $result;
}

function post_cadastrarUsuario($POSTFIELDS){
	return to_post('tecnologia/cadastrarUsuarioTecnologia', $POSTFIELDS);
}

function get_buscarTecnologiaById($id){
	return to_get('tecnologia/buscarTecnologiaById?id='.$id);
}

function get_clientes($itemInicio, $itemFim, $nome, $id){
    $url = ('clienteVendas/listarClientesPorParametrosPaginado?itemInicio='.$itemInicio . '&itemFim='. $itemFim);

	if (isset($nome) && $nome){
		$url.= '&nome=' . urlencode($nome);
	}

	if (isset($id) && $id){
		$url = ('clienteVendas/listarClientesPorParametrosPaginado?idCliente='.$id);
	}

    return to_get($url);
}

function put_atualizarTecnologia($POSTFIELDS){
	return to_put('tecnologia/atualizarTecnologia', $POSTFIELDS);
}

function patch_deletarTecnologia($POSTFIELDS){
	return to_patch('tecnologia/atualizarStatusTecnologia', $POSTFIELDS);
}

function to_put($url, $POSTFIELDS){
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
		CURLOPT_CUSTOMREQUEST => 'PUT',
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

function to_patch($url, $POSTFIELDS){
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