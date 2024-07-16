<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use LDAP\Result;

if ( ! function_exists('dd') )
{
    function dd($data)
    {
        exit('<pre>' . print_r($data) . '</pre>');
    }	
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

function get_produtosTop100($habilitaNull){	
	$result = to_get('produto/listarProdutos?habilitaNull='.$habilitaNull);
	return $result;
}

function post_produto($dados){
    $result = to_post('produto/cadastrarProduto', $dados);
    return $result;
}

function get_produtoById($id, $habilitaNull){
    $result = to_get('produto/listarProdutosByIdAndNome?id='.$id.'&habilitaNull='.$habilitaNull);
    return $result;
}

function get_produtoByIdOrName($id, $nome, $habilitaNull){
    $result = to_get('produto/listarProdutosByIdAndNome?id='.$id.'&nome='.$nome.'&habilitaNull='.$habilitaNull);
    return $result;
}

function get_produtoComposicaoById($id){
    $result = to_get('composicaoProposta/listarCompPropVendaPorIdPrincipal?idProdutoPrincipal='.$id);
    return $result;
}

function post_cadastrarComposicaoProduto($dados){
	$result = to_post('composicaoProposta/cadastrarComposicaoPropostaVenda', $dados);
	return $result;
}

function put_produto($dados){
    $result = to_put('produto/atualizarProduto', $dados);
    return $result;
}

function patch_alterarStatusProduto($dados){
	$result = to_patch('produto/alterarStatusProduto', $dados);
	return $result;
}

function patch_alterarStatusComposicao($dados){
	$result = to_patch('composicaoProposta/editarComposicaoPropostaVenda', $dados);
	return $result;
}

function get_vendedoresPropostas(){
	$result = to_get('vendedor/listarVendedor');
	return $result;
}

function post_proposta($dados){
    $result = to_post('propostas/vendas/cadastrarProposta', $dados);
    return $result;
}

function get_propostasTop100(){
	$result = to_get('propostas/vendas/listarPropostas');
	return $result;
}

function get_propostaByDocumentoVendedorData($documento, $idVendedor, $dataInicial, $dataFinal){
	$result = to_get('propostas/vendas/listarPropostaByFields?'.
		'cpfCnpj='.$documento.'&'.
		'idVendedor='.$idVendedor.'&'.
		'dataInicial='.$dataInicial.'&'.
		'dataFinal='.$dataFinal
	);
	return $result;
}

function get_propostaById($id){
	$result = to_get('propostas/vendas/listarPropostaById?id='.$id);
	return $result;
}

function get_autorizacaoByIdProposta($idProposta){
	$result = to_get('autorizacao/listarAutorizacaoById?idProposta='.$idProposta);
	return $result;
}

function put_proposta($dados){
	$result = to_put('propostas/vendas/atualizarProposta', $dados);
	return $result;
}

function patch_alterarStatusProposta($dados){
	$result = to_patch('propostas/vendas/alterarStatusProposta', $dados);
	return $result;
}

function post_itensProposta($dados){
	$result = to_post('propostaItem/cadastrarPropostaItem', $dados);
	return $result;
}

function post_itensOportunidade($dados){
	$result = to_post('oportunidadeItem/cadastrarOportunidadeItens', $dados);
	return $result;
}


function get_itensPropostaByIdProposta($idProposta){
	$result = to_get('propostaItem/listarPropostaItemByIdProposta?idProposta='.$idProposta);
	return $result;
}

function get_itemPropostaById($id){
	$result = to_get('propostaItem/listarPropostaItemById?id='.$id);
	return $result;
}

function get_oportunidadeById($id){
	$result = to_get('oportunidades/listarOportunidadeById?id='.$id);
	return $result;
}

function get_itensOportunidadeByIdOportunidade($idOportunidade){
	$result = to_get('oportunidadeItem/listarItemByIdOportunidade?idOportunidade='.$idOportunidade);
	return $result;
}

function get_itemOportunidadeById($id){
	$result = to_get('oportunidadeItem/listarOportunidadeItemById?id='.$id);
	return $result;
}
function put_itemProposta($dados){
	$result = to_put('propostaItem/atualizarPropostaItem', $dados);
	return $result;
}

function put_itemOportunidade($dados){
	$result = to_put('oportunidadeItem/atualizarOportunidadeItens', $dados);
	return $result;
}

function patch_alterarStatusItemProposta($dados){
	$result = to_patch('propostaItem/alterarStatusPropostaItem', $dados);
	return $result;
}

function patch_alterarStatusItemOportunidade($dados){
	$result = to_patch('oportunidadeItem/alterarStatusOportunidadeItens', $dados);
	return $result;
}

function post_propostaEndereco($dados){
	$result = to_post('propostas/vendas/cadastrarPropostaEndereco', $dados);
	return $result;
}

function put_propostaEndereco($dados){
	$result = to_put('propostas/vendas/atualizarPropostaEndereco', $dados);
	return $result;
}

function put_oportunidade($dados){
	$result = to_put('oportunidades/editarOportunidade', $dados);
	return $result;
}
function get_autorizacaoFaturamentoByParams($idProposta, $cnpj, $idVendedor){
	$result = to_get('autorizacao/listarAutorizacaoByFields?'.
		'idProposta='.$idProposta.'&'.
		'cpfCnpj='.$cnpj.'&'.
		'idVendedor='.$idVendedor
	);
	return $result;
}

function get_autorizacaoAllPagination($pageNumber, $pageSize){
	$result = to_get('autorizacao/listarAutorizacoes?itemInicio='.$pageNumber.'&itemFim='.$pageSize);
	return $result;
}

function get_enderecoClienteByIdCliente($idCliente){
	$result = to_get('clienteVendas/listarEnderecoPrincipalCliente?idCliente='.$idCliente);
	return $result;
}

function get_clientesByParamsPagination($idCliente, $nomeCliente, $documento, $itemInicio, $itemFim){
	$result = to_get('clienteVendas/listarClientesPorParametrosPaginado?'.
		'idCliente='.$idCliente.'&'.
		'nome='.$nomeCliente.'&'.
		'documento='.$documento.'&'.
		'itemInicio='.$itemInicio.'&'.
		'itemFim='.$itemFim
	);
	return $result;
}

function post_cadastrarCliente($dados){
	$result = to_post('clienteVendas/cadastrarCliente', $dados);
	return $result;
}

function post_enviarEmailAutorizacao($idProposta){
	$result = to_post('autorizacao/enviarEmail', array('idProposta' => $idProposta));
	return $result;
}

function get_buscarVendedorByEmail($email){
	$result = to_get('vendedorVendas/listarVendedorByEmail?email=' . $email);
	return $result;
}

function get_buscarSugestaoByIdProposta($idProposta){
	$result = to_get('propostaSugestao/listarSugestaoByIdProposta?idProposta=' . $idProposta);
	return $result;
}

function get_buscarOportunidadesByParamsPaginado($dataInicial, $dataFinal, $idVendedor, $documentoCliente, $itemInicio, $itemFim){
	$result = to_get('oportunidades/listarOportunidadesByFields?'.
		'dataInicial='.$dataInicial.'&'.
		'dataFinal='.$dataFinal.'&'.
		'idVendedor='.$idVendedor.'&'.
		'documentoCliente='.$documentoCliente.'&'.
		'itemInicio='.$itemInicio.'&'.
		'itemFim='.$itemFim
	);
	return $result;
}

function post_oportunidade($dados){
	$result = to_post('oportunidades/cadastrarOportunidade', $dados);
	return $result;
}

function patch_alterarStatusOportunidade($dados){
	$result = to_patch('oportunidades/alterarNovoStatusOportunidade', $dados);
	return $result;
}
function get_autorizacoes() {
	$result = to_get('autorizacao/listarAutorizacoesKanban');
	return $result;
}