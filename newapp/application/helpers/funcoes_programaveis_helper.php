<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_funcoes_programaveis_by_cliente($idCliente) {
    $result = to_get('funcoesProgramaveis/listarFuncoesByIdCliente?idCliente='.$idCliente);
    return $result;
}

function get_grupos_funcoes_programaveis_by_cliente($idCliente) {
    $result = to_get('funcoesProgramaveis/listarFuncoeProgramaveisByIdCliente?idCliente='.$idCliente);
    return $result;
}

function get_all_funcoes_programaveis() {
    $result = to_get('funcoesProgramaveis/listarFuncoes');
	return $result;
}

function get_all_grupo_funcoes_programaveis() {
    $result = to_get('funcoesProgramaveis/listarGrupoFuncoes');
	return $result;
}

function post_associar_funcao_programavel($POSTFIELDS) {
    $result = to_post('funcoesProgramaveis/cadastrarAssociacaoFuncao', $POSTFIELDS);
	return $result;
}

function post_associar_Grupo_funcao_programavel($POSTFIELDS) {
    $result = to_post('funcoesProgramaveis/cadastrarAssociacaoGrupoFP', $POSTFIELDS);
	return $result;
}

function post_desassociar_funcao_programavel($POSTFIELDS) {
    $result = to_patch('funcoesProgramaveis/atualizarStatusAssociacao', $POSTFIELDS);
	return $result;
}

function post_desassociar_grupo_funcao_programavel($POSTFIELDS) {
    $result = to_patch('funcoesProgramaveis/atualizarStatusAssociacaoGF', $POSTFIELDS);
	return $result;
}


function to_put($url, $POSTFIELDS)
{
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest') . $url;

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer ' . $token;

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

function to_patch($url, $POSTFIELDS)
{
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest') . $url;

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer ' . $token;

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

function to_post($url, $POSTFIELDS)
{
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest') . $url;

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer ' . $token;

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

function to_get($url)
{
	$CI = &get_instance();

	$request = $CI->config->item('url_api_shownet_rest') . $url;

	$token = $CI->config->item('token_api_shownet_rest');

	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer ' . $token;

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