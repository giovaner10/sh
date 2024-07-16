<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use LDAP\Result;

if (!function_exists('dd')) {
	function dd($data)
	{
		exit('<pre>' . print_r($data) . '</pre>');
	}
}

function getAllDocumentTypesRoute($startRow, $endRow, $nome, $login)
{
	$url = 'usuario/listarUsuariosPaginado?itemInicio=' . $startRow . '&itemFim=' . $endRow;

	if (isset($nome) && $nome) {
		$url .= '&nome=' . $nome;
	}
	if (isset($login) && $login) {
		$url .= '&login=' . $login;
	}

	$result = to_get($url);

	return $result;
}

function getUsuarioByID($idUsuario)
{
	$url = 'usuario/listarUsuarioById?idUsuario=' . $idUsuario;

	$result = to_get($url);

	return $result;
}

function getUsuarioByName($nome)
{
	$nome = urlencode($nome);
	$url = 'usuario/listarUsuariosByNome?nome=' . $nome;

	$result = to_get($url);

	return $result;
}

function getDocumentoById($documentId)
{
	$url = "rh/documentos/buscarDocumentoFuncionarioById?id=$documentId";

	$result = to_get($url);

	return $result;
}

function getDocumentosFuncionarioRoute($cpf, $itemInicio, $itemFim)
{
	$url = "rh/documentos/listarDocumentosFuncionarios?itemInicio=$itemInicio&itemFim=$itemFim&cpf=$cpf";

	$result = to_get($url);

	return $result;
}

function insertDocumentoFuncionarioRoute($POSTFIELDS)
{
	$url = "rh/documentos/inserirDocumentoFuncionario";

	$result = to_post($url, $POSTFIELDS);

	return $result;
}

function atualizarDocumentoFuncionarioRoute($POSTFIELDS)
{
	$url = "rh/documentos/editarDocumentoFuncionario";

	$result = to_patch($url, $POSTFIELDS);

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
