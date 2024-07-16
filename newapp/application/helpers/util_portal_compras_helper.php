<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function incluirPedidoCompraNoERP($codigoEmpresa, $codigoFilial, $dados) {
	try {
		# Cria instância do CI
		$CI =& get_instance();
	
		$url = $CI->config->item('url_erp_soap') . '/ws/WSCLI_PEDIDOSDECOMPRA.apw';
		$usuario = $CI->config->item('usuario_erp_soap');
		$senha = $CI->config->item('senha_erp_soap');
	
		$produtos = '';
	
		if (isset($dados['produtos']) && is_array($dados['produtos'])) {
			foreach ($dados['produtos'] as $produto) {
				$produtos .= '<wsc:ITENSPC>
						<wsc:CCONTABIL></wsc:CCONTABIL>
						<wsc:CCUSTO>'. $produto['centroCusto'] .'</wsc:CCUSTO>
						<wsc:OBSERVACAO>'. $produto['observacao'] .'</wsc:OBSERVACAO>
						<wsc:PRODUTO>'. $produto['codigo'] .'</wsc:PRODUTO>
						<wsc:QUANTIDADE>'. $produto['quantidade'] .'</wsc:QUANTIDADE>
						<wsc:VALOR>'. $produto['valor'] .'</wsc:VALOR>
					</wsc:ITENSPC>';
			}
		}
	
		// Dados da requisição SOAP
		$xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
			<soapenv:Header/>
			<soapenv:Body>
				<wsc:INCLUIRPCOMPRA>
					 <wsc:OLOGIN>
							<wsc:CSENHA>'. $senha .'</wsc:CSENHA>
							<wsc:CUSUARIO>'. $usuario .'</wsc:CUSUARIO>
					 </wsc:OLOGIN>
					 <wsc:CODEMP>'. $codigoEmpresa .'</wsc:CODEMP>
					 <wsc:CODFIL>'. $codigoFilial .'</wsc:CODFIL>
					 <wsc:INCLUIRPCOMPRA>
							<wsc:CODIGOFORCEDOR>'. $dados['codigoFornecedor'] .'</wsc:CODIGOFORCEDOR>
							<wsc:CONDPAGAMENTO>'. $dados['condicaoPagamento'] .'</wsc:CONDPAGAMENTO>
							<wsc:LOJAFORNECEDOR>'. $dados['lojaFornecedor'] .'</wsc:LOJAFORNECEDOR>
							<wsc:PCOMPRAITEM>
								'. $produtos .'
							</wsc:PCOMPRAITEM>
					 </wsc:INCLUIRPCOMPRA>
				</wsc:INCLUIRPCOMPRA>
		 </soapenv:Body>
		</soapenv:Envelope>';
	
		// Configuração da requisição cURL
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $xml,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER => [
				'Content-Type: text/xml; charset=utf-8',
				'SOAPAction: INCLUIRPCOMPRA',
			]
		]);
	
		// Executando a requisição e obtendo a resposta
		$response = curl_exec($curl);
		curl_close($curl);
	
		$xmlString = str_replace("soap:", "", $response);
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);

		if ($xml === false) {
			throw new Exception('Erro ao processar a resposta do ERP.');
		}
		
		$xmlResult = $xml->Body->INCLUIRPCOMPRARESPONSE->INCLUIRPCOMPRARESULT;
		$results = json_decode(json_encode($xmlResult), TRUE);
	
		return $results;
	}
	catch (Exception $e) {
		$retorno = ['CLSTATUS' => 'false', 'CLERRO' => $e->getMessage()];
		return [ 'OSTATUS' => $retorno ];
	}
}

function incluirPreNotaNoERP($codigoEmpresa, $codigoFilial, $dados) {
	try {
		# Cria instância do CI
		$CI =& get_instance();

		$url = $CI->config->item('url_erp_soap') . '/ws/WSCLI_PRENOTA.apw';
		$usuario = $CI->config->item('usuario_erp_soap');
		$senha = $CI->config->item('senha_erp_soap');

		// Dados da requisição SOAP
		$xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
			<soapenv:Header/>
			<soapenv:Body>
				<wsc:INLUIRPRENOTA>
					<wsc:OLOGIN>
							<wsc:CSENHA>'. $senha .'</wsc:CSENHA>
							<wsc:CUSUARIO>'. $usuario .'</wsc:CUSUARIO>
					</wsc:OLOGIN>
					<wsc:CODEMP>'. $codigoEmpresa .'</wsc:CODEMP>
					<wsc:CODFIL>'. $codigoFilial .'</wsc:CODFIL>
					<wsc:DADOSPRENF>
							<wsc:EMISSAO>'. $dados['data_emissao'] .'</wsc:EMISSAO>
							<wsc:NOTA>'. $dados['numero'] .'</wsc:NOTA>
							<wsc:PEDIDO>'. $dados['numero_pedido'] .'</wsc:PEDIDO>
							<wsc:SERIE>'. $dados['serie'] .'</wsc:SERIE>
							<wsc:SPECIE>'. $dados['especie'] .'</wsc:SPECIE>
							<wsc:VENCTO>'. $dados['data_vencimento'] .'</wsc:VENCTO>
					</wsc:DADOSPRENF>
				</wsc:INLUIRPRENOTA>
		</soapenv:Body>
		</soapenv:Envelope>';

		// Configuração da requisição cURL
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $xml,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER => [
				'Content-Type: text/xml; charset=utf-8',
				'SOAPAction: INLUIRPRENOTA',
			]
		]);

		// Executando a requisição e obtendo a resposta
		$response = curl_exec($curl);
		curl_close($curl);

		$xmlString = str_replace("soap:", "", $response);
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);

		if (empty($xml)) {
			throw new Exception('Erro ao processar a resposta do ERP.');
		}
		
		$xmlResult = $xml->Body->INLUIRPRENOTARESPONSE->INLUIRPRENOTARESULT;
		$results = json_decode(json_encode($xmlResult), TRUE);

		return $results;
	}
	catch (\Exception $e) {
		return [
			'CLSTATUS' => 'false', 
			'CLERRO' => $e->getMessage()
		];
	}
}