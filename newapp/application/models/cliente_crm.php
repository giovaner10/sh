<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Cliente_CRM extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('util_helper');
       
	}

    public function pegarCliente_CRM($documento){           
        
	    $documentoTratado = preg_replace('/[^0-9]/', '', $documento);

        $dados = json_decode(from_clienteCRM($documentoTratado));         

        if ($dados) {            
            return $dados;
        } 
        else {
            return false;
        }	   
       	 

	}

    public function adicionarCliente_CRM($dados){          
        
        $retorno = json_decode(to_clienteCRM($dados));       
        if ($retorno) {            
            return $retorno;
        } 
        else {
            return false;
        }	   
       	 

	}


	public function pegar_idProduto(){  

        $retorno = get_idProdutoCRM();

		if ($retorno) {
			$jsonbfalse = json_decode($retorno);		
			$valuedata = $jsonbfalse->value;
			
			foreach($valuedata as $datavalue):
				$dados[] = array(
					'name' => $datavalue->name,
					'productnumber' => $datavalue->productnumber,
					'productid' => $datavalue->productid,
				);	
				
			endforeach;

			return json_encode($dados); 

		} else {
			return false;
		}      	             	   
        

	}

	public function pegar_cenarioVenda(){            
        
        $retorno = get_cenarioVendaCRM();

		
		$jsonbfalse = json_decode($retorno);		
		$valuedata = $jsonbfalse->value;
		
		foreach($valuedata as $datavalue):
			$dados[] = array(
				'tz_name' => $datavalue->tz_name,
				'tz_cenario_vendaid' => $datavalue->tz_cenario_vendaid,
			);	
			
		endforeach;	
		
		return json_encode($dados);        

	}

	public function pegar_tipoPagamento(){            
        
        $retorno = get_tipoPagamentoCRM();
		
		$jsonbfalse = json_decode($retorno);		
		$valuedata = $jsonbfalse->value;
		
		foreach($valuedata as $datavalue):
			$dados[] = array(
				'tz_name' => $datavalue->tz_name,
				'tz_tipo_pagamentoid' => $datavalue->tz_tipo_pagamentoid,
				'tz_codigo_erp' => $datavalue->tz_codigo_erp,

			);	
			
		endforeach;	
		
		return json_encode($dados);        

	}

	public function pegar_condicaoPagamento($value){            
        
        $retorno = get_condicaoPagamentoCRM($value);
		
		$jsonbfalse = json_decode($retorno);		
		$valuedata = $jsonbfalse->value;
		
		foreach($valuedata as $datavalue):
			$dados[] = array(
				'tz_name' => $datavalue->tz_name,
				'tz_condicao_pagamentoid' => $datavalue->tz_condicao_pagamentoid,
			);	
			
		endforeach;	
		
		return json_encode($dados);        

	}

	public function pegar_tipoVeiculo(){            
        
        $retorno = get_tipoVeiculoCRM();

		
		$jsonbfalse = json_decode($retorno);		
		$valuedata = $jsonbfalse->value;
		
		foreach($valuedata as $datavalue):
			$dados[] = array(
				'tz_name' => $datavalue->tz_name,
				'tz_tipo_veiculoid' => $datavalue->tz_tipo_veiculoid,
			);	
			
		endforeach;	
		
		return json_encode($dados);

	}

	public function pegar_planoSatelital($value){            
        
        $retorno = get_planoSatelitalCRM($value);

		
		$jsonbfalse = json_decode($retorno);
		$valuedata = (isset($jsonbfalse->value)) ? $jsonbfalse->value : false ;		
		
		$dados = array();

		if($valuedata){
			foreach($valuedata as $datavalue):
				$dados[] = array(
					'tz_name' => $datavalue->tz_name,
					'tz_plano_satelitalid' => $datavalue->tz_plano_satelitalid,
				);	
				
			endforeach;	
		}	
		return json_encode($dados);        
		
	}	

	public function adicionarOportunidade_CRM($dados){            
        
        $retorno = json_decode(to_oportunidadeCRM($dados));       
        if ($retorno) {            
            return $retorno;
        } 
        else {
            return false;
        }	   
       	 
	}

	public function editarOportunidade_CRM($dados){            
        
        $retorno = json_decode(to_edit_oportunidadeCRM($dados));       
        if ($retorno) {            
            return $retorno;
        } 
        else {
            return false;
        }	   
       	 
	}

	public function pegarOportunidade_vendedor($emailUsuario){   
				       
        $retorno = json_decode(get_oportunidadeVendedor($emailUsuario)); 
		
		if (!empty($retorno->value)) {   					
			$valuedata = $retorno->value;   
			 
			
            return $valuedata;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function pegarOportunidade_vendedor_data($emailUsuario, $dataInicial,  $dataFinal){   
				       
        $retorno = json_decode(get_oportunidadeVendedorPorData($emailUsuario, $dataInicial,  $dataFinal));  	
		if(!isset($retorno->value)){
            return false;
		}	

		if ($retorno->value) {   					
			$valuedata = $retorno->value;   
            return $valuedata;
        } 
        else {
            return false;
        }	  
       	 
	}

	
	public function pegarVendadores(){   
				       
        $retorno = json_decode(get_Vendedores()); 
		
		if (!empty($retorno->value)) {   					
			$valuedata = $retorno->value;   
			 
			
            return $valuedata;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function pegarVendadores_data($dataInicial,  $dataFinal){   
				       
        $retorno = json_decode(get_VendedoresPorData($emailUsuario, $dataInicial,  $dataFinal));  	
		if(!isset($retorno->value)){
            return false;
		}	

		if ($retorno->value) {   					
			$valuedata = $retorno->value;   
            return $valuedata;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function pegarOportunidade_cliente_data($customerId, $dataInicial,  $dataFinal){            
        
        $retorno = json_decode(get_oportunidadeClientePorData($customerId, $dataInicial,  $dataFinal));    

		if(!isset($retorno->value)){
            return false;
		}	

        if (!empty($retorno->value)) {
			$valuedata = $retorno->value;   		
									
            return $valuedata;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function pegarOportunidade_cliente($value){            
        
        $retorno = json_decode(get_oportunidadeCliente($value));    
		
        if (!empty($retorno->value)) {
			$valuedata = $retorno->value;   		
									
            return $valuedata;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function gerarPedido($idCotacao, $loginUsuario){            
        
        $retorno = json_decode(to_gerarPedido($idCotacao, $loginUsuario));    
		
        if (!empty($retorno)) {
            return $retorno;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function enviarAssinatura($idCotacao, $loginUsuario){            
        
        $retorno = json_decode(to_enviarAssinatura($idCotacao, $loginUsuario));    
		
        if (!empty($retorno)) {
            return $retorno;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function ganhar($idCotacao){            
        
        $retorno = json_decode(to_ganhar($idCotacao));    
		
        if (!empty($retorno)) {
            return $retorno;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function resumoCliente($idCotacao){            
        
        $retorno = json_decode(get_resumoCliente($idCotacao));    
		
        if (!empty($retorno)) {
            return $retorno;
        } 
        else {
            return false;
        }	  
       	 
	}

	public function get_configurometroCRM($idVeiculo){            
        
        $retorno = get_configurometro($idVeiculo);
		
        if (!empty($retorno)) {
            return $retorno;
        } 
        else {
            return false;
        }	  
       	 
	}


	public function status($value){            
        
        $retorno = get_afID($value);
		
		$jsonbfalse = json_decode($retorno);		
		$valuedata = $jsonbfalse->value;
				
		foreach($valuedata as $datavalue):
			$tz_afid = $datavalue->tz_afid;
		endforeach;	
				
		if ($tz_afid) {
			echo json_encode($tz_afid);
		}
		else{
			return false;
		}
		        

	}



    function valida_cnpj($cnpj) {
		// Deixa o CNPJ com apenas números
		$cnpj = preg_replace('/[^0-9]/', '', $cnpj);

		// Garante que o CNPJ é uma string
		$cnpj = (string) $cnpj;

		// O valor original
		$cnpj_original = $cnpj;

		// Captura os primeiros 12 números do CNPJ
		$primeiros_numeros_cnpj = substr($cnpj, 0, 12);

		/**
		 * Multiplicação do CNPJ
		 *
		 * @param string $cnpj Os digitos do CNPJ
		 * @param int $posicoes A posição que vai iniciar a regressão
		 * @return int O
		 *
		 */
		if (!function_exists('multiplica_cnpj')) {

			function multiplica_cnpj($cnpj, $posicao = 5) {
				// Variável para o cálculo
				$calculo = 0;

				// Laço para percorrer os item do cnpj
				for ($i = 0; $i < strlen($cnpj); $i++) {
					// Cálculo mais posição do CNPJ * a posição
					$calculo = $calculo + ( $cnpj[$i] * $posicao );

					// Decrementa a posição a cada volta do laço
					$posicao--;

					// Se a posição for menor que 2, ela se torna 9
					if ($posicao < 2) {
						$posicao = 9;
					}
				}
				// Retorna o cálculo
				return $calculo;
			}

		}

		// Faz o primeiro cálculo
		$primeiro_calculo = multiplica_cnpj($primeiros_numeros_cnpj);

		// Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
		// Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
		$primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 : 11 - ( $primeiro_calculo % 11 );

		// Concatena o primeiro dígito nos 12 primeiros números do CNPJ
		// Agora temos 13 números aqui
		$primeiros_numeros_cnpj .= $primeiro_digito;

		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
		$segundo_calculo = multiplica_cnpj($primeiros_numeros_cnpj, 6);
		$segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 : 11 - ( $segundo_calculo % 11 );

		// Concatena o segundo dígito ao CNPJ
		$cnpj = $primeiros_numeros_cnpj . $segundo_digito;

		// Verifica se o CNPJ gerado é idêntico ao enviado
		if ($cnpj === $cnpj_original) {
			return true;
		}
	}

	function valida_cpf( $cpf = false ) {
		// Exemplo de CPF: 025.462.884-23

		/**
		 * Multiplica dígitos vezes posições 
		 *
		 * @param string $digitos Os digitos desejados
		 * @param int $posicoes A posição que vai iniciar a regressão
		 * @param int $soma_digitos A soma das multiplicações entre posições e dígitos
		 * @return int Os dígitos enviados concatenados com o último dígito
		 *
		 */
		if ( ! function_exists('calc_digitos_posicoes') ) {
			function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
				// Faz a soma dos dígitos com a posição
				// Ex. para 10 posições: 
				//   0    2    5    4    6    2    8    8   4
				// x10   x9   x8   x7   x6   x5   x4   x3  x2
				//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
				for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
					$soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
					$posicoes--;
				}

				// Captura o resto da divisão entre $soma_digitos dividido por 11
				// Ex.: 196 % 11 = 9
				$soma_digitos = $soma_digitos % 11;

				// Verifica se $soma_digitos é menor que 2
				if ( $soma_digitos < 2 ) {
					// $soma_digitos agora será zero
					$soma_digitos = 0;
				} else {
					// Se for maior que 2, o resultado é 11 menos $soma_digitos
					// Ex.: 11 - 9 = 2
					// Nosso dígito procurado é 2
					$soma_digitos = 11 - $soma_digitos;
				}

				// Concatena mais um dígito aos primeiro nove dígitos
				// Ex.: 025462884 + 2 = 0254628842
				$cpf = $digitos . $soma_digitos;

				// Retorna
				return $cpf;
			}
		}

		// Verifica se o CPF foi enviado
		if ( ! $cpf ) {
			return false;
		}

		// Remove tudo que não é número do CPF
		// Ex.: 025.462.884-23 = 02546288423
		$cpf = preg_replace( '/[^0-9]/is', '', $cpf );

		// Verifica se o CPF tem 11 caracteres
		// Ex.: 02546288423 = 11 números
		if ( strlen( $cpf ) != 11 ) {
			return false;
		}   

		// Captura os 9 primeiros dígitos do CPF
		// Ex.: 02546288423 = 025462884
		$digitos = substr($cpf, 0, 9);

		// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
		$novo_cpf = calc_digitos_posicoes( $digitos );

		// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
		$novo_cpf = calc_digitos_posicoes( $novo_cpf, 11 );

		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
		if ( $novo_cpf === $cpf ) {
			// CPF válido
			return true;
		} else {
			// CPF inválido
			return false;
		}
	}

	public function salvarCotacao($idUsuario, $idCotacao, $dataAtual){
		$dados = array(
			'id_usuario' => $idUsuario,
			'id_cotacao' => $idCotacao,
			'data' => $dataAtual
		);
		
		$this->db->insert('quotes_televendas', $dados);
	}

	public function excluirCotacao($idUsuario, $idCotacao){
    $this->db->where('id_usuario', $idUsuario);
    $this->db->where('id_cotacao', $idCotacao);
    $this->db->delete('quotes_televendas');
	}
}