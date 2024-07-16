<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use LDAP\Result;

if ( ! function_exists('dd') )
{
    function dd($data)
    {
        exit('<pre>' . print_r($data) . '</pre>');
    }
}

function post_seriaispcp($POSTFIELDS){	
	$result = to_postpcp('comandos/validarListaIscas', $POSTFIELDS);
	return $result;
}

function post_seriais_clientes($POSTFIELDS){	
	$result = to_postpcp('comandos/validarIscasClientes', $POSTFIELDS);
	return $result;
}

function post_enviarComando ($POSTFIELDS){	
	$result = to_postpcp('lora/enviarComandoLora', $POSTFIELDS);
	return $result;
}

function post_seriaiLora($POSTFIELDS){	
	$result = to_postpcp('lora/validarPosicaoIscaLoraClientes', $POSTFIELDS);
	return $result;
}


function get_historicoComandos($itemInicio,$itemFim, $serial, $dataInicio, $dataFim){
    $url = 'lora/listarComandosByIdTerminal?itemInicio='.$itemInicio.'&itemFim='.$itemFim;
	if ($dataInicio != "") {
        $url .= '&dataInicial='.$dataInicio;
    }
	if ($dataFim != "") {
        $url .= '&dataFinal='.$dataFim;
    }
    if ($serial != "") {
        $url .= '&idTerminal='.$serial;
    }
	
    $result = to_get($url);
    return $result;
}

function get_clientesPCP($itemInicio, $itemFim, $nome, $id){
    $url = ('clienteVendas/listarClientesPorParametrosPaginado?itemInicio='.$itemInicio . '&itemFim='. $itemFim);

	if (isset($nome) && $nome){
		$url.= '&nome=' . urlencode($nome);
	}

	if (isset($id) && $id){
		$url = ('clienteVendas/listarClientesPorParametrosPaginado?idCliente='.$id);
	}

    return to_get($url);
}


function get_associacoesLora($startRow, $endRow, $idCliente, $idLora, $serial, $status){
    $url = 'lora/listarCadastroAssociacao?itemInicio='.$startRow.'&itemFim='.$endRow;
	if ($status != "") {
        $url .= '&status='.$status;
    }
	if ($idCliente != "") {
        $url .= '&idCliente='.$idCliente;
    }
    if ($idLora != "") {
        $url .= '&idLora='.$idLora;
    }
    if ($serial != "") {
        $url .= '&serial='.$serial;
    }
	
    $result = to_get($url);
    return $result;
}

function patch_alterarStatusAssociacao ($POSTFIELDS){
	return to_patch('lora/alterarStatusAssociacaoLora', $POSTFIELDS);
}

function post_EditarAssociacao ($POSTFIELDS){
	return to_put('lora/atualizarAssociacaoLora', $POSTFIELDS);
}

function post_cadastrarAssociacao ($POSTFIELDS){	
	$result = to_postpcp('lora/cadastrarAssociacaoLora', $POSTFIELDS);
	return $result;
}

//UTILS:

function to_patch($url, $POSTFIELDS){
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

function to_postpcp($url, $POSTFIELDS){
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