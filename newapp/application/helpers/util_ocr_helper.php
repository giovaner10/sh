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
function get_DadosGerenciamento($placa, $dataInicial, $dataFinal){	
	$result = to_get('ocr/eventos/listarEventosOCR?placaLida='.$placa.'&dataInicio='.$dataInicial.'&dataFim='.$dataFinal);
	return $result;
}

function get_DadosGerenciamentoPaginated($placa, $tipoMatch, $dataInicial, $dataFinal, $itemInicio, $itemFim){	
	$result = to_get('ocr/eventos/listarEventosByParametrosPaginacao?placaLida='.$placa.'&dataInicio='.$dataInicial.'&dataFim='.$dataFinal.'&itemInicio='.$itemInicio.'&itemFim='.$itemFim.'&tipoMatch='.$tipoMatch);
	return $result;
}

function get_BlacklistPaginated($placaSearch, $clienteSearch, $status, $itemInicio, $itemFim){	
	$url = 'ocr/blacklist/listarBlackListByParametrosPaginacao?itemInicio='.$itemInicio.'&itemFim='.$itemFim;

	if (isset($placaSearch) && $placaSearch){
		$url.= '&placa='.str_replace(' ', '%20', $placaSearch);
	}
	if (isset($clienteSearch) && $clienteSearch){
		$url.= '&idCliente='.$clienteSearch;
	}
	if (isset($status) && $status){
		$url.= '&status='.$status;
	}

	$result = to_get($url);

	return $result;
}

function get_DadosGerenciamentoTop50(){	
	$result = to_get('ocr/eventos/listarTop50EventosOCR');
	return $result;
}

function get_DadosGerenciamentoByID($id){	
	$result = to_get('ocr/eventos/listarEventosByIdOCR?id='.$id);
	return $result;
}

function get_BlackList($placa, $clienteID){	
	$url = 'ocr/blacklist/listarBlackListOCR?';
	if((isset($placa) && $placa) && isset($clienteID) && $clienteID)
		$url.= 'placa='.$placa.'&idCliente='.$clienteID;
	else if(isset($placa) && $placa)
		$url.= 'placa='.$placa;
	else if(isset($clienteID) && $clienteID)
		$url.= 'idCliente='.$clienteID;
	$result = to_get($url);
	return $result;
}

function get_BlackListTop50(){	
	$url = 'ocr/blacklist/listarTop50BlackList';
	$result = to_get($url);
	return $result;
}

function get_BlackListAssociadas($id){
	$result = to_get('ocr/blacklist/listarBlackListsAssociadasEmail?idAlertaEmail='.$id);
	return $result;
}

function get_WhitelistsAssociadas($id){
	$result = to_get('ocr/whitelist/listarWhiteListsAssociadasEmail?idAlertaEmail='.$id);
	return $result;
}

function get_BlackListByID($id){	
	$result = to_get('ocr/blacklist/listarBlackListByIdOCR?id='.$id);
	return $result;
}

function get_WhiteListByID($id){	
	$result = to_get('ocr/whitelist/listarWhiteListOCRById?id='.$id);
	return $result;
}

function get_Whitelist($placa, $clienteID){	
	$url = 'ocr/whitelist/listarWhiteListByPlacaAndId?id_cliente='.$clienteID.'&placa='.$placa;
	$result = to_get($url);
	return $result;
}

function get_WhitelistPaginated($placa, $idCliente, $status, $startRow, $endRow){	
	$url = 'ocr/whitelist/listarWhiteListPagincao?itemInicio='. $startRow .'&itemFim=' . $endRow.'&placa='.str_replace(' ', '%20', $placa).'&idCliente='.$idCliente;
	if (isset($status) && $status){
		$url.= '&status='.$status;
	}
	$result = to_get($url);
	return $result;
}

function get_WhitelistByAlertaEmail($idAlerta, $startRow, $endRow){	
	$url = 'ocr/whitelist/listarWhiteListByAlertaEmailPaginado?idAlerta='. $idAlerta .'&itemInicio=' . $startRow.'&itemFim='.$endRow;
	$result = to_get($url);
	return $result;
}

function get_BlacklistByAlertaEmail($idAlerta, $startRow, $endRow){	
	$url = 'ocr/cadastroJoinBlackAlerta/listarPlacasAssociadasAlertaEmail?idCadastroAlertaEmail='. $idAlerta .'&itemInicio=' . $startRow.'&itemFim='.$endRow;
	$result = to_get($url);
	return $result;
}

function get_WhitelistTop50(){	
	$url = 'ocr/whitelist/listarTop50WhiteList';
	$result = to_get($url);
	return $result;
}

function get_AlertasEmail($email, $clienteID){	
	$url = 'ocr/cadastroAlertaEmail/listarCadastroAlertaEmailOCR?idCliente='.$clienteID;
	if(isset($email) && $email)
		$url.= '&email='.$email;
	$result = to_get($url);
	return $result;
}

function get_AlertasEmailPaginated($email, $idCliente, $startRow, $endRow){	
	$url = 'ocr/cadastroAlertaEmail/listarAlertaEmailByParametrosPaginacao?itemInicio='. $startRow .'&itemFim=' . $endRow;
	if(isset($email) && $email){
		$url .= '&email=' . $email;
	}

	if (isset($idCliente) && $idCliente){
		$url .= '&idCliente=' . $idCliente;
	}
	$result = to_get($url);
	return $result;
}

function get_AlertasEmailByID($id){	
	$result = to_get('ocr/cadastroAlertaEmail/buscarCadastroAlertaEmailOCRPorId?idAlertaEmail='.$id);
	return $result;
}

function get_EventosPlacas() {
	$result = to_get('ocr/match/listarTop50EventosMatch');
	return $result;
}

function get_EventosPlacasByID($id){	
	$result = to_get('ocr/match/listarEventosByIdOCR?id='.$id);
	return $result;
}

function get_EventosPlacasByPlacaData($placa, $dataInicial, $dataFinal) {
	$result = to_get('ocr/match/listarEventosMatchByPlacaData?placa='.$placa.'&dataInicio='.$dataInicial.'&dataFim='.$dataFinal);
	return $result;
}

function get_EventosPlacasPaginatedAndGrupped($placa, $dataInicial, $dataFinal, $startRow, $endRow, $condicao, $agrupar, $tipoMatch){
	$result = to_get('ocr/match/listarEventosMatchByParametrosAgrupamentoPaginacao?placaLida='.$placa.'&tipoMatch='.$tipoMatch.'&dataInicio='.$dataInicial.'&dataFim='.$dataFinal.'&itemInicio='.$startRow.'&itemFim='.$endRow.'&condicao='.$condicao.'&agrupar='.$agrupar);
    return $result;
}

function get_EventosPlacasPaginated($placa, $startRow, $endRow, $dataInicial, $dataFinal, $tipoMatch){
	$result = to_get('ocr/match/listarEventosMatchPaginado?placaLida='.$placa.'&itemInicio='.$startRow.'&itemFim='.$endRow.'&dataInicio='.$dataInicial.'&dataFim='.$dataFinal.'&tipoMatch='.$tipoMatch);
    return $result;
}

function patch_StatusEventosMatchOCR($POSTFIELDS) {
	$result = to_patch('ocr/match/atualizarStatusEventoMatchOCR', $POSTFIELDS);
	return $result;
}

function get_Clientes($nome){	
	$result = to_get('ocr/clientes/listarClientesOCR?nomeCliente='.$nome);
	return $result;
}

function get_contagemPlacasClassificadasBlackList() {
	$result = to_get('ocr/blacklist/contarPlacasClassificadasBlackList');
	return $result;
}

function get_contagemVeiculosEngajados() {
	$result = to_get('ocr/eventos/contarVeiculosEngajados');
	return $result;
}

function get_contagemPlacasClassificadasWhiteList() {
	$result = to_get('ocr/whitelist/contarPlacasClassificadasWhiteList');
	return $result;
}

function get_contagemPlacasLidasMês($dataInicial, $dataFinal) {
	$result = to_get('ocr/match/contarPlacasLidasMes?dataInicio='.$dataInicial.'&dataFim='.$dataFinal);
	return $result;
}

function get_contagemPlacasComAlertas() {
	$result = to_get('ocr/match/contarPlacasAssociadasAlertas');
	return $result;
}

function get_AlertasColdListPorPeriodo($periodo) {
	$result = to_get('ocr/match/ContarPlacasPorPeriodoColdList?periodo='.$periodo);
	return $result;
}

function get_AlertasHotListPorPeriodo($periodo) {
	$result = to_get('ocr/match/ContarPlacasPorPeriodoHotList?periodo='.$periodo);
	return $result;
}

function get_EventosHotListTop50(){	
	$result = to_get('ocr/match/listarTop50EventosMatchHotListAgrupado');
	return $result;
}

function get_EventosHotListByPlate($placa){	
	$result = to_get('ocr/match/ListarEventosHotListAgrupadosByPlaca?placaLida='.$placa);
	return $result;
}

function get_PlacaEventosHotList($placa){	
	$result = to_get('ocr/blacklist/listarBlackListByPlaca?placa='.$placa);
	return $result;
}

function get_EventosColdListTop50(){	
	$result = to_get('ocr/whitelist/listarTop50WhiteListMatch');
	return $result;
}

function get_EventosColdListByPlate($placa){	
	$result = to_get('ocr/match/ListarEventosColdListAgrupadosByPlaca?placaLida='.$placa);
	return $result;
}

function get_PlacaEventosColdList($placa){	
	$result = to_get('ocr/whitelist/listarWhiteListByPlaca?placa='.$placa);
	return $result;
}

function get_VeiculosMonitoradosTop50(){	
	$result = to_get('ocr/eventos/listarTop50EventosAgrupados');
	return $result;
}

function get_VeiculosMonitoradosByPlate($placa){	
	$result = to_get('ocr/eventos/ListarEventosAgrupadosByPlaca?placaLida='.$placa);
	return $result;
}

function get_PlacaVeiculosMonitorados($placa){	
	$result = to_get('ocr/eventos/listarEventosPorPlacaExata?placaLida='.$placa);
	return $result;
}

function get_EventosPlacasAlertasTop50(){	
	$result = to_get('ocr/match/listarTop50EventoDeDeteccao');
	return $result;
}
function get_EventosPlacasAlertasByPlate($placa){	
	$result = to_get('ocr/match/listarEventosAgrupadosByPlaca?placaLida='.$placa);
	return $result;
}
function get_PlacaEventosPlacasAlertas($placa){	
	$result = to_get('ocr/match/listarEventosMatchPorPlacaExata?placaLida='.$placa);
	return $result;
}

function get_EventosPlacasAlertasMensalTop50(){	
	$result = to_get('ocr/match/listarTop50EventoDeDeteccaoMes');
	return $result;
}

function get_EventosPlacasAlertasMensalByPlate($placa){	
	$result = to_get('ocr/match/ListarEventosMesAgrupadosByPlaca?placaLida='.$placa);
	return $result;
}

function get_PlacaEventosPlacasAlertasMensal($placa){	
	$result = to_get('ocr/match/listarPlacasExataMes?placaLida='.$placa);
	return $result;
}

function get_ProcessamentoPaginated($status, $dataInicial, $dataFinal, $itemInicio, $itemFim){	
	$result = to_get('ocr/acoes/listarAcoesByStatusAndData?status='.$status.'&dataInicio='.$dataInicial.'&dataFim='.$dataFinal.'&itemInicio='.$itemInicio.'&itemFim='.$itemFim);
	return $result;
}

//Post
function post_BlackList($POSTFIELDS){	
	$result = to_post('ocr/blacklist/criarBlackListOCR', $POSTFIELDS);
	return $result;
}

function post_Whitelist($POSTFIELDS){	
	$result = to_post('ocr/whitelist/criarWhiteListOCR', $POSTFIELDS);
	return $result;
}

function post_AlertasEmail($POSTFIELDS){	
	$result = to_post('ocr/cadastroAlertaEmail/criarCadastroAlertaEmailOCR', $POSTFIELDS);
	return $result;
}

function post_ImportarBlackList($POSTFIELDS){	
	$result = to_post('ocr/blacklist/importarBlackListsOCR', $POSTFIELDS);
	return $result;
}

function post_AssociarBlackLists($POSTFIELDS){
	$result = to_post('ocr/cadastroJoinBlackAlerta/criarCadastroJoinBlackListAlerta', $POSTFIELDS);
	return $result;
}

function post_ImportarWhitelist($POSTFIELDS){	
	$result = to_post('ocr/whitelist/importarWhiteListsOCR', $POSTFIELDS);
	return $result;
}

function post_AssociarWhitelist($POSTFIELDS){
	$result = to_post('ocr/cadastroJoinWhiteAlerta/criarCadastroJoinWhiteListAlerta', $POSTFIELDS);
	return $result;
}

function post_AssociarAllWhitelist($POSTFIELDS){
	$result = to_post('ocr/cadastroJoinAllWhiteAlerta/associarAllSeguradoraAlertaEmail', $POSTFIELDS);
	return $result;
}

function post_AssociarLoteWhitelist($POSTFIELDS){
	$result = to_post('ocr/whitelist/associarLoteWhitelist', $POSTFIELDS);
	return $result;
}

function post_AssociarAllBlacklist($POSTFIELDS){
	$result = to_post('ocr/cadastroJoinAllBlackAlerta/associarAllSeguradoraAlertaEmail', $POSTFIELDS);
	return $result;
}

function patch_DesassociarAllBlacklist($POSTFIELDS){
	$result = to_patch('ocr/cadastroJoinAllBlackAlerta/desassociarAllSeguradoraAlertaEmail', $POSTFIELDS);
	return $result;
}

function post_DesassociarLoteWhitelist($POSTFIELDS){
	$result = to_post('ocr/whitelist/desassociarLoteWhitelistByIdAlerta', $POSTFIELDS);
	return $result;
}

function patch_DesassociarAllWhitelist($POSTFIELDS){
	$result = to_patch('ocr/cadastroJoinAllWhiteAlerta/desassociarAllSeguradoraAlertaEmail', $POSTFIELDS);
	return $result;
}

function post_AssociarBlacklistLote($POSTFIELDS){
	$result = to_post('ocr/cadastroJoinBlackAlerta/associarSeguradoraAlertaEmail', $POSTFIELDS);
	return $result;
}

function post_DesassociarLoteBlacklist($POSTFIELDS){
	$result = to_post('ocr/blacklist/desassociarLoteBlacklistByIdAlerta', $POSTFIELDS);
	return $result;
}

function post_AssociarLoteBlacklist($POSTFIELDS){
	$result = to_post('ocr/blacklist/associarLoteBlacklist', $POSTFIELDS);
	return $result;
}

//Patch
function patch_DesassociarBlacklistLote($POSTFIELDS){	
	$result = to_patch('ocr/cadastroJoinBlackAlerta/desassociarSeguradoraAlertaEmail', $POSTFIELDS);
	return $result;
}


//Patch
function patch_DesassociarWhitelistLote($POSTFIELDS){	
	$result = to_patch('ocr/cadastroJoinWhiteAlerta/desassociarSeguradoraAlertaEmail', $POSTFIELDS);
	return $result;
}

function patch_BlackList($POSTFIELDS){	
	$result = to_patch('ocr/blacklist/atualizarBlackListOCR', $POSTFIELDS);
	return $result;
}

//Patch
function patch_WhiteList($POSTFIELDS){	
	$result = to_patch('ocr/whitelist/atualizarWhitelist', $POSTFIELDS);
	return $result;
}

function patch_statusBlackList($POSTFIELDS){	
	$result = to_patch('ocr/blacklist/atualizarStatusBlackListOCR', $POSTFIELDS);
	return $result;
}

function patch_statusWhiteList($POSTFIELDS){	
	$result = to_patch('ocr/whitelist/atualizarStatusWhiteListOCR', $POSTFIELDS);
	return $result;
}

function patch_AlertasEmail($POSTFIELDS){	
	$result = to_patch('ocr/cadastroAlertaEmail/atualizarCadastroAlertaEmailOCR', $POSTFIELDS);
	return $result;
}

function patch_removerAlertasEmail($id){
	$result = to_patch('ocr/cadastroAlertaEmail/excluirCadastroAlertaEmailOCR?idAlertaEmail='.$id, []);
	return $result;
}

function patch_removerAssociacaoBlacklist($idAlertaEmail, $idBlackList){
	$result = to_patch('ocr/cadastroJoinBlackAlerta/removerCadastroJoinBlackListAlerta?idAlertaEmail='.$idAlertaEmail.'&idBlackList='.$idBlackList, []);
	return $result;
}

function patch_removerAssociacaoWhitelist($idAlertaEmail, $idWhitelist){
	$result = to_patch('ocr/cadastroJoinWhiteAlerta/removerCadastroJoinWhiteListAlerta?idAlertaEmail='.$idAlertaEmail.'&idWhiteList='.$idWhitelist, []);
	return $result;
}

function patch_mudarStatusImportarWhitelist($POSTFIELDS){	
	$result = to_patch('ocr/whitelist/atualizarListaStatusWhiteListOCR', $POSTFIELDS);
	return $result;
}

function post_removerImportarWhitelist($POSTFIELDS){	
	$result = to_post('ocr/whitelist/excluirLoteWhitelist', $POSTFIELDS);
	return $result;
}

function patch_mudarStatusImportarHotlist($POSTFIELDS){	
	$result = to_patch('ocr/blacklist/atualizarListaStatusHotListOCR', $POSTFIELDS);
	return $result;
}

function post_removerImportacaoHotlist($POSTFIELDS){	
	$result = to_post('ocr/blacklist/excluirLoteBlacklist', $POSTFIELDS);
	return $result;
}

// Utils

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

// OSM
function to_get_reference($latitude, $longitude) {
	$url = 'nominatim/reverse?lat='.$latitude.'&lon='.$longitude.'&format=json&addressdetails=1&accept-language=pt-BR&zoom=18';
	
	$CI = &get_instance();

	$request = $CI->config->item('url_api_osm').$url;

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $request,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET'
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