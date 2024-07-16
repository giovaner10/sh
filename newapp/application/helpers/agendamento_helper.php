<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use LDAP\Result;

if (!function_exists('dd')) {
	function dd($data)
	{
		exit('<pre>' . print_r($data) . '</pre>');
	}
}

function downloadReportFile($type, $dataInicio, $dataFim)
{
    $url = "infobipAgendamentoManutencao/getReportQtdMotivoRejeicaoAceiteByTecnico?tipoArquivo=$type";

	if(isset($dataInicio) && isset($dataFim)){
		$url .= "&dataInicial=$dataInicio&dataFinal=$dataFim";
	}

	if(isset($nomeTecnico)){
		$url .= "&nomeTecnico=$nomeTecnico";
	}

    $CI = &get_instance();

    $request = $CI->config->item('url_api_shownet_rest') . $url;

    $token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Authorization: Bearer ' . $token;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);

    if (curl_error($curl)) {
        echo 'Erro ao baixar o relatório.';
        exit();
    }

    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($statusCode !== 200) {
        echo 'Erro ao baixar o relatório: ' . $statusCode;
        exit();
    }

    if ($type === 'pdf') {
        $contentType = 'application/pdf';
        $contentDisposition = 'attachment';
        $fileExtension = 'pdf';
    } elseif ($type === 'xlsx') {
        $contentType = 'application/octet-stream';
        $contentDisposition = 'form-data; name="filename"; filename="relatorio_motivo_rejeicao'.$dataInicio.'-'.$dataFim.'.xlsx"';
        $fileExtension = 'xlsx';
    } else {
        echo 'Tipo de arquivo não suportado.';
        exit();
    }

    header("Content-Type: $contentType");

    header("Content-Disposition: $contentDisposition");

    echo $response;

    curl_close($curl);

    exit();
}

function downloadReportFileInstalacao($type, $dataInicio, $dataFim, $nomeTecnico)
{
    $url = "infobip/getReportQtdMotivoRejeicaoAceiteByTecnico?tipoArquivo=$type";

	if(isset($dataInicio) && isset($dataFim)){
		$url .= "&dataInicial=$dataInicio&dataFinal=$dataFim";
	}

	if(isset($nomeTecnico)){
		$url .= "&nomeTecnico=$nomeTecnico";
	}

    $CI = &get_instance();

    $request = $CI->config->item('url_api_shownet_rest') . $url;

    $token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Authorization: Bearer ' . $token;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);

    if (curl_error($curl)) {
        echo 'Erro ao baixar o relatório.';
        exit();
    }

    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($statusCode !== 200) {
        echo 'Erro ao baixar o relatório: ' . $statusCode;
        exit();
    }

    if ($type === 'pdf') {
        $contentType = 'application/pdf';
        $contentDisposition = 'attachment';
        $fileExtension = 'pdf';
    } elseif ($type === 'xlsx') {
        $contentType = 'application/octet-stream';
        $contentDisposition = 'form-data; name="filename"; filename="relatorio_motivo_rejeicao'.$dataInicio.'-'.$dataFim.'.xlsx"';
        $fileExtension = 'xlsx';
    } else {
        echo 'Tipo de arquivo não suportado.';
        exit();
    }

    header("Content-Type: $contentType");

    header("Content-Disposition: $contentDisposition");

    echo $response;

    curl_close($curl);

    exit();
}

function downloadReportFileInstallationDetailed($type, $dataInicio, $dataFim, $nomeTecnico)
{
    $url = "infobip/getReportMotivoRejeicaoAceiteByTecnicoDetalhe?tipoArquivo=$type";

	if(isset($dataInicio) && isset($dataFim)){
		$url .= "&dataInicial=$dataInicio&dataFinal=$dataFim";
	}

	if(isset($nomeTecnico)){
		$url .= "&nomeTecnico=$nomeTecnico";
	}

    $CI = &get_instance();

    $request = $CI->config->item('url_api_shownet_rest') . $url;

    $token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Authorization: Bearer ' . $token;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);

    if (curl_error($curl)) {
        echo 'Erro ao baixar o relatório.';
        exit();
    }

    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($statusCode !== 200) {
        echo 'Erro ao baixar o relatório: ' . $statusCode;
        exit();
    }

    if ($type === 'pdf') {
        $contentType = 'application/pdf';
        $contentDisposition = 'attachment';
        $fileExtension = 'pdf';
    } elseif ($type === 'xlsx') {
        $contentType = 'application/octet-stream';
        $contentDisposition = 'form-data; name="filename"; filename="relatorio_motivo_rejeicao'.$dataInicio.'-'.$dataFim.'.xlsx"';
        $fileExtension = 'xlsx';
    } else {
        echo 'Tipo de arquivo não suportado.';
        exit();
    }

    header("Content-Type: $contentType");

    header("Content-Disposition: $contentDisposition");

    echo $response;

    curl_close($curl);

    exit();
}

function downloadReportFileMaintenanceDetailed($type, $dataInicio, $dataFim, $nomeTecnico)
{
    $url = "infobipAgendamentoManutencao/getReportMotivoRejeicaoAceiteByTecnicoDetalhe?tipoArquivo=$type";

	if(isset($dataInicio) && isset($dataFim)){
		$url .= "&dataInicial=$dataInicio&dataFinal=$dataFim";
	}

	if(isset($nomeTecnico)){
		$url .= "&nomeTecnico=$nomeTecnico";
	}

    $CI = &get_instance();

    $request = $CI->config->item('url_api_shownet_rest') . $url;

    $token = $CI->config->item('token_api_shownet_rest');

    $headers[] = 'Authorization: Bearer ' . $token;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);

    if (curl_error($curl)) {
        echo 'Erro ao baixar o relatório.';
        exit();
    }

    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($statusCode !== 200) {
        echo 'Erro ao baixar o relatório: ' . $statusCode;
        exit();
    }

    if ($type === 'pdf') {
        $contentType = 'application/pdf';
        $contentDisposition = 'attachment';
        $fileExtension = 'pdf';
    } elseif ($type === 'xlsx') {
        $contentType = 'application/octet-stream';
        $contentDisposition = 'form-data; name="filename"; filename="relatorio_motivo_rejeicao'.$dataInicio.'-'.$dataFim.'.xlsx"';
        $fileExtension = 'xlsx';
    } else {
        echo 'Tipo de arquivo não suportado.';
        exit();
    }

    header("Content-Type: $contentType");

    header("Content-Disposition: $contentDisposition");

    echo $response;

    curl_close($curl);

    exit();
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
