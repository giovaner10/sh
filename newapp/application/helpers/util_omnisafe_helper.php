<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use LDAP\Result;

if ( ! function_exists('dd') )
{
    function dd($data)
    {
        exit('<pre>' . print_r($data) . '</pre>');
    }	
}

function get_buscarPerfilById($id){
	return to_get('configOmnisafe/configPerfil/listarPerfilPorId?idPerfil=' . $id);
}

function get_buscarPerfilByParametros($idCliente){
	return to_get('configOmnisafe/configPerfil/listarPerfisPorParametros?idCliente=' . $idCliente);
}

function get_buscarPerfis($idCliente, $serial, $nomePerfil, $startRow, $endRow){
	$url = 'configOmnisafe/configPerfil/listarPerfisPorParametrosPaginado?itemInicio='. $startRow .'&itemFim=' . $endRow;
	if(isset($serial) && $serial){
		$url .= '&serial=' . $serial;
	}

	if (isset($idCliente) && $idCliente){
		$url .= '&idCliente=' . $idCliente;
	}

	if (isset($nomePerfil) && $nomePerfil){
		$url .= '&nomePerfil=' . urlencode($nomePerfil);
	}
	$result = to_get($url);
	return $result;
}

function get_buscarSerial($idCliente){
	return to_get('configOmnisafe/contratosVeiculos/listarContratosVeiculosPorCliente?idCliente=' .$idCliente);
}

function post_cadastrarPerfil($POSTFIELDS){
	return to_post('configOmnisafe/configPerfil/cadastrarConfigPerfil', $POSTFIELDS);
}

function put_editarPerfil($POSTFIELDS){
	return to_put('configOmnisafe/configPerfil/editarConfigPerfil', $POSTFIELDS);
}

function patch_removerPerfil($POSTFIELDS){
	return to_patch('configOmnisafe/configPerfil/alterarStatusConfigPerfil', $POSTFIELDS);
}

function get_buscarConfigPower($startRow, $endRow, $idCliente, $idPerfil, $serial){
	$url = 'configOmnisafe/configPower/listarConfigsPowerPorParametrosPaginado?itemInicio=' . $startRow .'&itemFim=' . $endRow;
	
	if(isset($serial) && $serial){
		$url .= '&serial=' . $serial;
	}
	
	if (isset($idCliente) && $idCliente){
		$url .= '&idCliente=' . $idCliente;
	}
	
	if (isset($idPerfil) && $idPerfil){
		$url .= '&idPerfil=' . $idPerfil;
	}
	
	return to_get($url);
}

function post_cadastrarConfigPower($POSTFIELDS){
	return to_post('configOmnisafe/configPower/cadastrarConfigPowerListaSchedule', $POSTFIELDS);
}

function get_buscarConfigPowerById($id){
	return to_get('configOmnisafe/configPower/listarConfigPowerPorId?idPower=' . $id);
}

function get_buscarPowerSchedules($id){
	return to_get('configOmnisafe/configPowerSchedule/listarConfigPowerScheduleByIdPower?idPower=' . $id);
}

function patch_removerConfigPower($POSTFIELDS){
	return to_patch('configOmnisafe/configPower/alterarStatusConfigPower', $POSTFIELDS);
}

function put_editarConfigPower($POSTFIELDS){
	return to_put('configOmnisafe/configPower/editarConfigPower', $POSTFIELDS);
}

function patch_alterarStatusConfigPowerSchedule($POSTFIELDS){
	return to_patch('configOmnisafe/configPowerSchedule/alterarStatusConfigPowerSchedule', $POSTFIELDS);
}

function put_editarPowerSchedulesLista($POSTFIELDS){
	return to_put('configOmnisafe/configPowerSchedule/editarListaConfigPowerSchedule', $POSTFIELDS);
}

function post_cadastrarScheduleLista($POSTFIELDS){
	return to_post('configOmnisafe/configPowerSchedule/cadastrarListaConfigPowerSchedule', $POSTFIELDS);
}

function get_buscarHistoricoComandos($idCliente, $serial, $idPerfil, $startRow, $endRow, $statusEnvio, $statusRecebimento){
	$url = 'configOmnisafe/historicoEnvio/listarHistoricoEnvioPorParametrosPaginado?itemInicio='. $startRow .'&itemFim=' . $endRow;
	if(isset($serial) && $serial){
		$url .= '&serial=' . $serial;
	}

	if (isset($idCliente) && $idCliente){
		$url .= '&idCliente=' . $idCliente;
	}

	if (isset($idPerfil) && $idPerfil){
		$url .= '&idPerfil=' . $idPerfil;
	}
	if ($statusEnvio == 0 && $statusEnvio != ""){
		$url .= '&statusEnvio=0';
	}
	if ($statusEnvio == 1){
		$url .= '&statusEnvio=1';
	}
	if ($statusEnvio == 2){
		$url .= '&statusEnvio=2';
	}
	if ($statusEnvio == 3){
		$url .= '&statusEnvio=3';
	}
	if ($statusEnvio == 4){
		$url .= '&statusEnvio=4';
	}
	if ($statusRecebimento == 0 && $statusRecebimento != ""){
		$url .= '&statusRecebimento=0';
	}
	if ($statusRecebimento == 1){
		$url .= '&statusRecebimento=1';
	}
	if ($statusRecebimento == 2){
		$url .= '&statusRecebimento=2';
	}
	$result = to_get($url);
	return $result;
}

function post_cadastrarComandoPorCliente($POSTFIELDS){
	return to_post('configOmnisafe/historicoEnvio/cadastrarHistoricoConfigPredefinidaPorCliente', $POSTFIELDS);
}

function post_cadastroComandoPredefinido($POSTFIELDS){
	return to_post('configOmnisafe/historicoEnvio/cadastrarHistoricoConfigPredefinida', $POSTFIELDS);
}

function get_buscarUltimaConfiguracao($idCliente, $serial, $idPerfil, $startRow, $endRow, $statusEnvio, $statusRecebimento){
	$url = 'configOmnisafe/ultimaConfigEnviada/listarUltimaConfigEnviadaByParametrosPaginado?itemInicio='. $startRow .'&itemFim=' . $endRow;
	if(isset($serial) && $serial){
		$url .= '&serial=' . $serial;
	}

	if (isset($idCliente) && $idCliente){
		$url .= '&idCliente=' . $idCliente;
	}

	if (isset($idPerfil) && $idPerfil){
		$url .= '&idPerfil=' . $idPerfil;
	}
	if ($statusEnvio == 0 && $statusEnvio != ""){
		$url .= '&statusEnvio=0';
	}
	if ($statusEnvio == 1){
		$url .= '&statusEnvio=1';
	}
	if ($statusEnvio == 2){
		$url .= '&statusEnvio=2';
	}
	if ($statusEnvio == 3){
		$url .= '&statusEnvio=3';
	}
	if ($statusEnvio == 4){
		$url .= '&statusEnvio=4';
	}
	if ($statusRecebimento == 0 && $statusRecebimento != ""){
		$url .= '&statusRecebimento=0';
	}
	if ($statusRecebimento == 1){
		$url .= '&statusRecebimento=1';
	}
	if ($statusRecebimento == 2){
		$url .= '&statusRecebimento=2';
	}
	$result = to_get($url);
	return $result;
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