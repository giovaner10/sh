<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use LDAP\Result;

if (!function_exists('dd')) {
	function dd($data)
	{
		exit('<pre>' . print_r($data) . '</pre>');
	}
}

function get_listarUsuariosPaginado($startRow, $endRow, $nome, $login)
{
	$url = 'usuario/listarUsuariosPaginado?itemInicio=' . $startRow . '&itemFim=' . $endRow;

	if (isset($nome) && $nome) {
		$url .= '&nome=' . urlencode($nome);
	}
	if (isset($login) && $login) {
		$url .= '&login=' . $login;
	}

	$result = to_get($url);

	return $result;
}

function get_listarDocumentosPaginado($startRow, $endRow, $nome)
{
	$url = 'rh/tipoDocumentos/listarTipoDocumentoPag?itemInicio=' . $startRow . '&itemFim=' . $endRow;

	if (isset($nome) && $nome) {
		$url .= '&nome=' . urlencode($nome);
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
function getDocumentAssociationsRoute($idFuncionario, $itemInicio, $itemFim)
{
	$url = "rh/associacaoFuncionario/listarAssociacaoFuncionarioPaginadoDetalhado?itemInicio=$itemInicio&itemFim=$itemFim&idFuncionario=$idFuncionario";

	$result = to_get($url);

	return $result;
}

function getAllDocumentTypesRoute($nome)
{
	$url = 'rh/tipoDocumentos/listarTipoDocumentoSemPag';

	if (isset($nome) && $nome) {
		$url .= '?nome=' . urlencode($nome);
	}

	$result = to_get($url);

	return $result;
}

function insertDocumentoFuncionarioRoute($POSTFIELDS)
{
	$url = "rh/documentos/inserirDocumentoFuncionarioDetalhes";

	$result = to_post($url, $POSTFIELDS);

	return $result;
}

function insertAssociacaoDocumentoFuncionarioRoute($POSTFIELDS)
{
	$url = "rh/associacaoFuncionario/cadastrarAssociacaoFuncionario";

	$result = to_post($url, $POSTFIELDS);

	return $result;
}

function updateAssociacaoDocumentoFuncionarioRoute($POSTFIELDS)
{
	$url = "rh/associacaoFuncionario/atualizarAssociacaoFuncionario";

	$result = to_put($url, $POSTFIELDS);

	return $result;
}

function atualizarDocumentoFuncionarioRoute($POSTFIELDS)
{
	$url = "rh/documentos/editarDocumentoFuncionario";

	$result = to_patch($url, $POSTFIELDS);

	return $result;
}

function patch_alterarStatusTipoDocumento ($POSTFIELDS){
	$url = "rh/tipoDocumentos/atualizarStatusTipoDocumento";

	$result = to_patch($url, $POSTFIELDS);

	return $result;
}

function post_cadastrarTipoDocumento($POSTFIELDS){
	return to_post('rh/tipoDocumentos/cadastrarTipoDocumento', $POSTFIELDS);
}

function put_editarTipoDocumento($POSTFIELDS){
	return to_put('rh/tipoDocumentos/atualizarTipoDocumento', $POSTFIELDS);
}



// REQUISIÇÕES - PERMISSÕES FUNCIONÁRIOS
function get_permissoesFuncionarios($nome, $codPermissao, $status) {
	$url = 'permissoes/listarPermissoesFuncionario?nome='.$nome.'&codPermissao='.$codPermissao.'&status='.$status;
	$result = to_get($url);
	return $result;
}

function get_buscarPermissoesCargo($id) {
	$url = 'permissoes/buscarPermissoesCargo?id='.$id;
	$result = to_get($url);
	return $result;
}

function get_cargosFuncionarios($nome) {
	$url = 'permissoes/listarPermissoesCargo?nome='.$nome;
	$result = to_get($url);
	return $result;
}

function patch_atualizarStatusPermissoes($POSTFIELDS){
	$url = "permissoes/atualizarStatusPermissoes";
	$result = to_patch($url, $POSTFIELDS);
	return $result;
}

function patch_atualizarStatusCargo($POSTFIELDS){
	$url = "permissoes/atualizarStatusCargo";
	$result = to_patch($url, $POSTFIELDS);
	return $result;
}

function post_cadastrarPermissao($POSTFIELDS){
	$url = "permissoes/cadastrarPermissao";
	$result = to_post($url, $POSTFIELDS);
	return $result;
}

function post_cadastrarCargo($POSTFIELDS){
	$url = "permissoes/cadastrarCargo";
	$result = to_post($url, $POSTFIELDS);
	return $result;
}

function put_atualizarCargo($POSTFIELDS){
	$url = "permissoes/atualizarCargo";
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
