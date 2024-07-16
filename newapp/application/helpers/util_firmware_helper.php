<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use LDAP\Result;

if ( ! function_exists('dd') )
{
    function dd($data)
    {
        exit('<pre>' . print_r($data) . '</pre>');
    }
}

function get_cadastrosPaginado($startRow, $endRow, $descricao, $versao){
	$url = 'firmware/documentos/listarFirmwarePaginada?itemInicio='.$startRow.'&itemFim='.$endRow; 
	if (isset($descricao) ){
		$url.= '&descricao='.urlencode($descricao);
	}
	if (isset($versao)){
		$url.= '&versao='.urlencode($versao);
	}

	$result = to_get($url);

	return $result;
}

function get_tecnologiasSelect(){
	$result = to_get('firmware/listarTecnologiasAtivasSemParametro');
	return $result;
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

function get_RegrasCadastradas($startRow, $endRow, $idCliente, $descricao){
	$url = 'RegraEnviada/listarRegraEnviadaPaginada?itemInicio='.$startRow.'&itemFim='.$endRow;
	if($idCliente != ''){
		$url.= '&idCliente='. $idCliente;
	}
	if($descricao != ''){
		$url.= '&descricao='.urlencode($descricao);
	}
	return to_get($url);
}

function get_RegrasCadastradasById($id){
	$result = to_get('RegraEnviada/listarRegraEnviadaById?id='.$id);
	return $result;
}

function get_firmwaresParaAssociar($id){
	$result = to_get('cadastroFirmware/associacao/listarFirmwareParaAssociacao?idFirmware='.$id);
	return $result;
}

function get_idFirmwareAssociado($id){
	$result = to_get('cadastroFirmware/associacao/buscarAssociacaoPorIdFirmware?idFirmware='.$id);
	return $result;
}

function get_tecnologias($startRow, $endRow, $nome){
    $url = 'firmware/listarTecnologiasByNome?itemInicio='. $startRow.'&itemFim=' . $endRow;
    if ($nome !== null && $nome !== "") {
        $url .= '&nome=' .urlencode($nome);
    }
    $result = to_get($url); 
    return $result;
}


function get_tecnologiasById($startRow, $endRow, $id){
    $url = 'firmware/listarModeloTecnologiaByIdsPaginada?idTecnologia='.$id.'&itemInicio=' .$startRow.'&itemFim=' . $endRow;
    $result = to_get($url); 
    return $result;
}

function get_ModelosByTecnologia($idTecnologia){
	$url='tecnologiaModelo/listarModeloAtivosByIdTecnologia?idTecnologia='. $idTecnologia;
	return to_get($url);
}

function get_detalhesFirmware ($idTecnologia){
    return to_get('firmware/documentos/listarFirmwareById?id='.$idTecnologia);
}

function get_historicoEnvio($startRow, $endRow, $cliente, $serial, $dataHoraEnvioInicio, $dataHoraEnvioFim) {
    $url = 'envioFirmware/listarEnvioFirmwarePaginado?itemInicio=' . $startRow . '&itemFim=' . $endRow;

    if ($serial != '') {
        $url .= '&serial=' . $serial;
    }
    if ($cliente != ''){
        $url .= '&cliente=' . urlencode($cliente);
    }
    if ($dataHoraEnvioInicio != ''){
        $url .= '&dataHoraEnvioInicio=' . urlencode($dataHoraEnvioInicio);
    }
    if ($dataHoraEnvioFim != ''){
        $url .= '&dataHoraEnvioFim=' . urlencode($dataHoraEnvioFim);
    }
	
    return to_get($url);
}

function get_detalhesHistorico ($id){
    return to_get('envioFirmware/listarEnvioFirmwareById?id='.$id);
}


function post_cadastrarModelo($POSTFIELDS){
	return to_post('tecnologiaModelo/cadastrarTecnologiaModelo', $POSTFIELDS);
}

function put_editarModelo($POSTFIELDS){
	return to_put('tecnologiaModelo/atualizarTecnologiaModelo', $POSTFIELDS);
}

function patch_alterarStatus($POSTFIELDS){
	return to_patch('tecnologiaModelo/atualizarStatusTecnologiaModelo', $POSTFIELDS);
}

function post_cadastrarTecnologia($POSTFIELDS){
	return to_post('firmware/cadastrarTecnologiasLote', $POSTFIELDS);
}

function patch_excluirTecnologia($POSTFIELDS){
	return to_patch('firmware/atualizarStatusTecnologias', $POSTFIELDS);
}

function post_editarTecnologia($POSTFIELDS){
	return to_put('firmware/atualizarTecnologias', $POSTFIELDS);
}

function post_cadastrarFirmware($POSTFIELDS){
	return to_post('firmware/documentos/cadastrarFirmware', $POSTFIELDS);
}

function post_cadastrarRegra($POSTFIELDS){
	return to_post('RegraEnviada/cadastrarRegraEnviada', $POSTFIELDS);
}

function post_cadastrarAssociacao($POSTFIELDS){
	return to_post('cadastroFirmware/associacao/associarFirmware', $POSTFIELDS);
}

function put_atualizarRegra($POSTFIELDS){
	return to_put('RegraEnviada/atualizarRegraEnviada', $POSTFIELDS);
}

function put_atualizarFirmware($POSTFIELDS){
	return to_put('firmware/documentos/atualizarFirmware', $POSTFIELDS);
}

function patch_alterarStatusRegra($POSTFIELDS){
	return to_patch('RegraEnviada/atualizarStatusRegraEnviada', $POSTFIELDS);
}

function patch_alterarStatusFirmware($POSTFIELDS){
	return to_patch('firmware/documentos/atualizarStatusFirmware', $POSTFIELDS);
}

function patch_inativarAssociacao($POSTFIELDS){
	return to_patch('cadastroFirmware/associacao/atualizarStatusAssociacao', $POSTFIELDS);
}

//DASHBOARD:
function get_FirmwaresCadastrados () {
	$result = to_get('firmware/documentos/contarFirmwaresCadastrados');
	return $result;
}

function get_SeriaisAtualizados () {
	$result = to_get('envioFirmware/buscarQuantidadeSeriaisAtualizados');
	return $result;
}

function get_SeriaisDesatualizados () {
	$result = to_get('RegraEnviada/listarSeriaisDesatualizados');
	return $result;
}

function get_atualizacaoDesabilitada () {
	$result = to_get('RegraEnviada/contarAtualizacaoDesabilitada');
	return $result;
}

function get_regraDia () {
	$result = to_get('RegraEnviada/contarAtualizacaoPorDia');
	return $result;
}

function get_regraHora () {
	$result = to_get('RegraEnviada/contarAtualizacaoPorHorario');
	return $result;
}

function get_versaoAnterior () {
	$result = to_get('firmware/atualizacao/contarSeriaisVersoesDivergentes');
	return $result;
}

function get_cadastroFirmware30Dias () {
	$result = to_get('firmware/documentos/listarCadastroSuperior30');
	return $result;
}

function get_cadastroFirmware60Dias () {
	$result = to_get('firmware/documentos/listarCadastroSuperior60');
	return $result;
}

function get_cadastroFirmware90Dias () {
	$result = to_get('firmware/documentos/listarCadastroSuperior90');
	return $result;
}

function get_RegraAtualizacoesDesabilitadas () {
	$result = to_get('RegraEnviada/listarAtualizacaoDesabilitada');
	return $result;
}

function get_RegraDiaEspecifico () {
	$result = to_get('RegraEnviada/listarAtualizacaoDiasEspecificos');
	return $result;
}

function get_RegraHorarioEspecifico () {
	$result = to_get('RegraEnviada/listarAtualizacaoHorarioEspecificos');
	return $result;
}

function get_SeriaisDesatualizadosPaginado() {
	$result = to_get('RegraEnviada/listarSeriaisDesatualizados');
	return $result;
}

function get_AtualizacoesFirmwares7Dias () {
	$result = to_get('firmware/atualizacao/listarHistoricoEnvioUltimos7Dias');
	return $result;
}

function get_AtualizacoesFirmwares15Dias () {
	$result = to_get('firmware/atualizacao/listarHistoricoEnvioUltimos15Dias');
	return $result;
}

function get_AtualizacoesFirmwares30Dias () {
	$result = to_get('firmware/atualizacao/listarHistoricoEnvioUltimos30Dias');
	return $result;
}

function get_ClientesDesatualizados () {
	$result = to_get('firmware/atualizacao/clientesDesatualizados');
	return $result;
}

function get_seriaisAtualizados7Dias () {
	$result = to_get('firmware/atualizacao/contarSeriaisAtualizados07Dias');
	return $result;
}

function get_seriaisAtualizados15Dias () {
	$result = to_get('envioFirmware/contarSeriaisAtualizados15Dias');
	return $result;
}

function get_seriaisAtualizados30Dias () {
	$result = to_get('envioFirmware/contarSeriaisAtualizados30Dias');
	return $result;
}

function get_seriaisAtualizadoPaginado ($itemInicio, $itemFim) {
	$result = to_get('envioFirmware/seriaisAtualizados?itemInicio='.$itemInicio.'&itemFim='.$itemFim);
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