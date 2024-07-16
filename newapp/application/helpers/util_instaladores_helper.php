<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use LDAP\Result;

if (!function_exists('dd')) {
	function dd($data)
	{
		exit('<pre>' . print_r($data) . '</pre>');
	}
}

function getDocumentosPaginado($startRow, $endRow, $idInstaller)
{
    $url = 'infobip/documentosInstaladores/listarDocumentosInstaladores?itemInicio=' . $startRow . '&itemFim=' . $endRow;

    if (isset($idInstaller) && $idInstaller) {
        $url .= '&idInstalador=' . $idInstaller;
    }

    $result = to_get($url);

    return $result;
}

function getAllServiceOrderServerSideRoute($startRow, $endRow, $idInstaller)
{
    $url = 'instaladores/listarOrdemServico?itemInicio=' . $startRow . '&itemFim=' . $endRow;

    if (isset($idInstaller) && $idInstaller) {
        $url .= '&idInstalador=' . $idInstaller;
    }

    $result = to_get($url);

    return $result;
}

function getAllServiceOrderValuesServerSideRoute($idInstaller)
{
    $url = 'instaladores/listarValoresInstalador?idInstalador=' . $idInstaller;

    $result = to_get($url);

    return $result;
}

function getDocumentoByIdRoute($documentId)
{
	$url = "infobip/documentosInstaladores/listarDocumentoInstaladorPorId?idDocumento=$documentId";

	$result = to_get($url);

	return $result;
}


function insertDocumentoInstaladorRoute($POSTFIELDS)
{
	$url = "infobip/documentosInstaladores/cadastrarDocumentoInstalador";

	$result = to_post($url, $POSTFIELDS);

	return $result;
}

function atualizarDocumentoInstaladorRoute($POSTFIELDS)
{
	$url = "infobip/documentosInstaladores/editarDocumentoInstalador";

	$result = to_put($url, $POSTFIELDS);

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

function to_get_mock($url)
{
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
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
