<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retorno extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->model('fatura');
		$this->load->model('cliente');		
	}

	public function gravar_retorno($dados){
		$retorno = false;
		$this->db->insert('retorno_pagamento', $dados);
		if($this->db->affected_rows() > 0)
			$retorno = true;

		return $retorno;
	}

	public function processar($pasta, $arquivo){

		$full_path = $pasta.'/'.$arquivo;
		$qtd_itens = 0;

		if(file_exists($full_path)){
			
			$lendo = @fopen($full_path,"r");
			if (!$lendo){

				throw new Exception('Erro: Não foi possível processar o arquivo. Por favor tente novamente.');
			}

			$i = 0;
			$ii = 0;
			$pagamento = array();
			while (!feof($lendo)){
				
				$i++;
				$linha = fgets($lendo,9999);
				$t_u_segmento = substr($linha,13,1);//Segmento T ou U
				$t_tipo_reg = substr($linha,7,1);//Tipo de Registro
				
				if($t_u_segmento == 'A'){
					$pagamento=array(
						'banco_retorno'=>substr($linha,20,3),
						'arquivo_retorno'=>$arquivo,
						'valorpago_retorno'=>floatval(substr($linha,162,15))/100,
						'datapagto_retorno'=>substr(substr($linha,154,8),4,4).'-'.substr(substr($linha,154,8),2,2).'-'.substr(substr($linha,154,8),0,2),
						'dataexec_retorno'=>date("Y-m-d H:i:s"),
						'statusexec_retorno'=>'ok',
						'operacao'=>'D',
						'contas_numero_retorno'=>intval(substr($linha,73,20)),
						'agencia'=>substr($linha,23,6),
						'conta'=>substr($linha,29,13),
						'titular'=>substr($linha,43,30),
						'status_pagamento'=>substr($linha,230,10)
					);
				}

				if($t_u_segmento == 'B'){
					$pagamento['cpf_cnpj']=substr($linha,18,14);
					if($pagamento['valorpago_retorno']&&$this->validar_item_retorno_pagamento(array('datapagto_retorno'=>$pagamento['datapagto_retorno'],'contas_numero_retorno'=>$pagamento['contas_numero_retorno'],'status_pagamento'=>$pagamento['status_pagamento']))){
						if (!$this->gravar_retorno($pagamento)){
							throw new Exception('Não foi possível gravar no banco de dados.');
						}
					}
				}

				if($t_u_segmento == 'J' && substr($linha,17,2)!='52'){
					$pagamento = array();
					$pagamento=array(
						'banco_retorno'=>'001',
						'arquivo_retorno'=>$arquivo,
						'valorpago_retorno'=>floatval(substr($linha,152,15))/100,
						'datapagto_retorno'=>substr(substr($linha,144,8),4,4).'-'.substr(substr($linha,144,8),2,2).'-'.substr(substr($linha,144,8),0,2),
						'dataexec_retorno'=>date("Y-m-d H:i:s"),
						'statusexec_retorno'=>'ok',
						'operacao'=>'D',
						'contas_numero_retorno'=>intval(substr($linha,182,20)),
						'titular'=>substr($linha,61,30),
						'status_pagamento'=>substr($linha,230,10)
					);
					if($pagamento['valorpago_retorno']&&$this->validar_item_retorno_pagamento(array('datapagto_retorno'=>$pagamento['datapagto_retorno'],'contas_numero_retorno'=>$pagamento['contas_numero_retorno'],'status_pagamento'=>$pagamento['status_pagamento']))){
						if (!$this->gravar_retorno($pagamento)){
							throw new Exception('Não foi possível gravar no banco de dados.');
						}
					}
				}
				if($t_u_segmento == 'O'){
					$pagamento = array();
					$pagamento=array(
						'banco_retorno'=>'001',
						'arquivo_retorno'=>$arquivo,
						'valorpago_retorno'=>floatval(substr($linha,107,15))/100,
						'datapagto_retorno'=>substr(substr($linha,99,8),4,4).'-'.substr(substr($linha,99,8),2,2).'-'.substr(substr($linha,99,8),0,2),
						'dataexec_retorno'=>date("Y-m-d H:i:s"),
						'statusexec_retorno'=>'ok',
						'operacao'=>'D',
						'contas_numero_retorno'=>intval(substr($linha,122,20)),
						'titular'=>substr($linha,61,30),
						'status_pagamento'=>substr($linha,230,10)
					);
					if($pagamento['valorpago_retorno']&&$this->validar_item_retorno_pagamento(array('datapagto_retorno'=>$pagamento['datapagto_retorno'],'contas_numero_retorno'=>$pagamento['contas_numero_retorno'],'status_pagamento'=>$pagamento['status_pagamento']))){
						if (!$this->gravar_retorno($pagamento)){
							throw new Exception('Não foi possível gravar no banco de dados.');
						}
					}
				}
				if($t_u_segmento == 'T'){
					$t_cod_banco = substr($linha,0,3);//Código do banco na compensação
					$t_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
					$t_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
					$t_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
					$t_cod_conv_banco = substr($linha,23,6);//Código do convênio no banco - Código fornecido pela CAIXA, através da agência de relacionamento do cliente. Deve ser preenchido com o código do Cedente (6 posições).
					$t_n_banco_sac = substr($linha,32,3);//Numero do banco de sacados
					$t_mod_nosso_n = substr($linha,39,2);//Modalidade nosso número
					$t_id_titulo_banco = substr($linha,41,15);//Identificação do titulo no banco - Número adotado pelo Banco Cedente para identificar o Título.
					$t_cod_carteira = substr($linha,57,1);//Código da carteira - Código adotado pela FEBRABAN, para identificar a característica dos títulos. 1=Cobrança Simples, 3=Cobrança Caucionada, 4=Cobrança Descontada
					$t_num_doc_cob = substr($linha,58,11);//Número do documento de cobrança - Número utilizado e controlado pelo Cliente, para identificar o título de cobrança.
					$t_dt_vencimento = substr($linha,73,8);//Data de vencimento do titulo - Data de vencimento do título de cobrança.
					$t_v_nominal = substr($linha,81,13);//Valor nominal do titulo - Valor original do Título. Quando o valor for expresso em moeda corrente, utilizar 2 casas decimais.
					$t_cod_banco2 = substr($linha,96,3);//Código do banco
					$t_cod_ag_receb = substr($linha,99,5);//Codigo da agencia cobr/receb - Código adotado pelo Banco responsável pela cobrança, para identificar o estabelecimento bancário responsável pela cobrança do título.
					$t_dv_ag_receb = substr($linha,104,1);//Dígito verificador da agencia cobr/receb
					$t_id_titulo_empresa = substr($linha,105,25);//identificação do título na empresa - Campo destinado para uso da Empresa Cedente para identificação do Título. Informar o Número do Documento - Seu Número.
					$t_cod_moeda = substr($linha,130,2);//Código da moeda
					$t_tip_inscricao = substr($linha,132,1);//Tipo de inscrição - Código que identifica o tipo de inscrição da Empresa ou Pessoa Física perante uma Instituição governamental: 0=Não informado, 1=CPF, 2=CGC / CNPJ, 9=Outros.
					$t_num_inscricao = substr($linha,133,15);//Número de inscrição - Número de inscrição da Empresa (CNPJ) ou Pessoa Física (CPF).
					$t_nome = substr($linha,148,40);//Nome - Nome que identifica a entidade, pessoa física ou jurídica, Cedente original do título de cobrança.
					$t_v_tarifa_custas = substr($linha,198,13);//Valor da tarifa/custas
					$t_id_rejeicoes = substr($linha,213,10);//Identificação para rejeições, tarifas, custas, liquidação e baixas
					$numero = substr($t_id_titulo_banco,3,10);

				}

				if($t_u_segmento == 'U'){
					$t_id_titulo_banco;
					$u_cod_banco = substr($linha,0,3);//Código do banco na compensação
					$u_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
					$u_tipo_reg = substr($linha,7,1);//Tipo de Registro - Código adotado pela FEBRABAN para identificar o tipo de registro: 0=Header de Arquivo, 1=Header de Lote, 3=Detalhe, 5=Trailer de Lote, 9=Trailer de Arquivo.
					$u_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
					$u_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
					$u_juros_multa = substr($linha,17,15);//Jurus / Multa / Encargos - Valor dos acréscimos efetuados no título de cobrança, expresso em moeda corrente.
					$u_desconto = substr($linha,32,15);//Valor do desconto concedido - Valor dos descontos efetuados no título de cobrança, expresso em moeda corrente.
					$u_abatimento = substr($linha,47,15);//Valor do abat. concedido/cancel. - Valor dos abatimentos efetuados ou cancelados no título de cobrança, expresso em moeda corrente.
					$u_iof = substr($linha,62,15);//Valor do IOF recolhido - Valor do IOF - Imposto sobre Operações Financeiras - recolhido sobre o Título, expresso em moeda corrente.
					$u_v_pago = substr($linha,77,15);//Valor pago pelo sacado - Valor do pagamento efetuado pelo Sacado referente ao título de cobrança, expresso em moeda corrente.
					$u_v_liquido = substr($linha,92,15);//Valor liquido a ser creditado - Valor efetivo a ser creditado referente ao Título, expresso em moeda corrente.
					$u_v_despesas = substr($linha,107,15);//Valor de outras despesas - Valor de despesas referente a Custas Cartorárias, se houver.
					$u_v_creditos = substr($linha,122,15);//Valor de outros creditos - Valor efetivo de créditos referente ao título de cobrança, expresso em moeda corrente.
					$u_dt_ocorencia = substr(substr($linha,137,8),4,4).'-'.substr(substr($linha,137,8),2,2).'-'.substr(substr($linha,137,8),0,2);//Data da ocorrência - Data do evento que afeta o estado do título de cobrança.
					$u_dt_efetivacao = substr($linha,145,8);//Data da efetivação do credito - Data de efetivação do crédito referente ao pagamento do título de cobrança.
					$u_dt_debito = substr($linha,157,8);//Data do débito da tarifa
					$u_cod_sacado = substr($linha,167,15);//Código do sacado no banco
					$u_cod_banco_comp = substr($linha,210,3);//Cód. Banco Correspondente compens - Código fornecido pelo Banco Central para identificação na Câmara de Compensação, do Banco ao qual será repassada a Cobrança do Título.
					$u_nn_banco = substr($linha,213,20);//Nosso Nº banco correspondente - Código fornecido pelo Banco Correspondente para identificação do Título de Cobrança. Deixar branco (Somente para troca de arquivos entre Bancos).

					$u_juros_multa = substr($u_juros_multa,0,13).'.'.substr($u_juros_multa,13,2);
					$u_desconto = substr($u_desconto,0,13).'.'.substr($u_desconto,13,2);
					$u_abatimento = substr($u_abatimento,0,13).'.'.substr($u_abatimento,13,2);
					$u_iof = substr($u_iof,0,13).'.'.substr($u_iof,13,2);
					$u_v_pago = substr($u_v_pago,0,13).'.'.substr($u_v_pago,13,2);
					$u_v_liquido = substr($u_v_liquido,0,13).'.'.substr($u_v_liquido,13,2);
					$u_v_despesas = substr($u_v_despesas,0,13).'.'.substr($u_v_despesas,13,2);
					$u_v_creditos = substr($u_v_creditos,0,13).'.'.substr($u_v_creditos,13,2);

					$hoje = date('Y-m-d H:i:s');

					$numero = intval($numero);
					$ii++;

					$d_item_retorno = array('arquivo_retorno' => $arquivo, 'fatnumero_retorno' => $numero,
						'banco_retorno' => $u_cod_banco_comp, 'valorpago_retorno' => $u_v_pago,
						'datapagto_retorno' => $u_dt_ocorencia,
						'jurospago_retorno' => $u_juros_multa,
						'dataexec_retorno' => $hoje);
						
					$verifica_item = array('fatnumero_retorno' => $numero, 'datapagto_retorno' => $u_dt_ocorencia, 'valorpago_retorno' => floatval($u_v_pago));
				
					if(floatval($u_v_pago)){
						if ($this->validar_item_retorno($verifica_item)){
							
							if (!$this->gravar_retorno($d_item_retorno))
								throw new Exception('Não foi possível gravar no banco de dados.');
							
							$qtd_itens++;
						}else{
							
							$item = $this->get_item_retorno($verifica_item);
							if (count($item) > 0){
								/*
								if (!$this->atualizar_item_retorno($item->id_retorno, $d_item_retorno))
									throw new Exception('Não foi possível atualizar no banco de dados.');
								*/
								$qtd_itens++;
							}
						}
					}
				}
			}
			fclose($lendo);
		}
		else{
			throw new Exception('O arquivo não foi localizado no servidor. Tente enviar novamente.');
		}

		//return $qtd_itens;
	}
			
		public function get_item_retorno($where){
			$retor_item = array();
			$item = $this->db->get_where('retorno_pagamento', $where);
			if($item->num_rows() > 0){
				foreach ($item->result() as $it_ret);
				$retor_item = $it_ret;
			}

			return $retor_item;
		}

		public function atualizar_item_retorno($id_item, $d_item){

			$atualiza = false;
			$this->db->update('retorno_pagamento', $d_item, array('id_retorno' => $id_item));
			if($this->db->affected_rows() > 0)
				$atualiza = true;

			return $atualiza;

		}

		public function validar_item_retorno($where){

			$validacao = false;
			if($where['valorpago_retorno']){
				$item_retorno = $this->db->get_where('retorno_pagamento', $where)->num_rows();
				if ($item_retorno == 0)
					$validacao = true;
			}
			return $validacao;
		}

		public function validar_item_retorno_pagamento($where){

			$validacao = false;
			$item_retorno = $this->db->get_where('retorno_pagamento', $where)->num_rows();
			if ($item_retorno == 0)
				$validacao = true;
			return $validacao;
		}

		public function listar($where, $paginacao = 0, $limite = 999999, $c_order = 'retorno_pagamento.id_retorno', 
			$order = 'ASC'){

			$query = $this->db->where($where)
			->order_by($c_order, $order)
			->get('retorno_pagamento', $limite, $paginacao);

			return $query->result();

		}

		public function baixa_manual($dados,$json){
			$json=(array)$json;
			if(isset($dados['financeiro'][0]->nome)){
				$data = array(
					'statusexec_retorno'=>'ok',
					'msgstatusexec_retorno'=>'fatnumero_retorno='.$dados['financeiro'][0]->Id
				);
				$this->db->where('id_retorno', $dados['retorno'][0]->id_retorno);
				$this->db->update('showtecsystem.retorno_pagamento',$data);
				unset($json['id_retorno']);
				unset($json['msgstatusexec_retorno']);
				$json['statusexec_retorno']="ok";
				$json['dataexec_retorno']=date('Y-m-d H:i:s');
				$this->db->insert('showtecsystem.retorno_pagamento',$json);
			}	
		}

		public function processar_sicredi($pasta, $arquivo){

			$full_path = $pasta.'/'.$arquivo;
			$qtd_itens = 0;

			if(file_exists($full_path)){

				$lendo = @fopen($full_path,"r");
				if (!$lendo){

					throw new Exception('Erro: Não foi possível processar o arquivo. Por favor tente novamente.');
				}


				$faturas_baixar = array();
				$i = 0;
				$ii = 0;

				$fatura_baixada = 0;

				while (!feof($lendo)){

					$i++;
					$linha = fgets($lendo,9999);
					$t_ini_detalhe = substr($linha, 0, 1);
					$liquidado = substr($linha, 108, 2);

					if ($t_ini_detalhe == 1 && $liquidado == "06"){
						$numero_fatura = substr($linha, 50, 5);
						$valor_pago = intval(substr($linha, 253, 11)).'.'.substr($linha, 264, 2);
						$data_pagamento = substr($linha, 114, 2).'-'.substr($linha, 112, 2).'-'.substr($linha, 110, 2);
						$new_data = new DateTime($data_pagamento);
						$data_pagamento = $new_data->format('Y-m-d');
																		

						$hoje = date('Y-m-d H:i:s');
						
						$ii++;

						$d_item_retorno = array('arquivo_retorno' => $arquivo, 'fatnumero_retorno' => $numero_fatura,
							'banco_retorno' => '748', 'valorpago_retorno' => $valor_pago,
							'datapagto_retorno' => $data_pagamento,
							'jurospago_retorno' => '',
							'dataexec_retorno' => $hoje);

						$verifica_item = array('fatnumero_retorno' => $numero_fatura, 'datapagto_retorno' => $data_pagamento, 'valorpago_retorno' => floatval($valor_pago));
						if(floatval($valor_pago)){
							if ($this->validar_item_retorno($verifica_item)){
								
								if (!$this->gravar_retorno($d_item_retorno))
									throw new Exception('Não foi possível gravar no banco de dados.');
								$qtd_itens++;
							}else{
								$item = $this->get_item_retorno($verifica_item);
								if (count($item) > 0){
									$qtd_itens++;
								}
							}
						}
					}

				}
				fclose($lendo);
			}else{
				throw new Exception('O arquivo não foi localizado no servidor. Tente enviar novamente.');
			}
			
		}	

}