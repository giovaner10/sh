<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Extract extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('extract_m', 'extrato');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
	}
	
	/**
	 * TELA INICIAL DE PAGAMENTOS
	*/
	public function index(){        
        $this->auth->is_allowed('vis_visualizarperfisdeprofissionais');
        $dados['titulo'] = lang('pagamentos') . ' - ' .lang('nome_aplicacao');
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('faturas/baixa_extrato/index');
        $this->load->view('fix/footer_NS');
    }

	/**
	 * ADICIONA OS NOVOS EXTRATOS AO BANCO - POR MEIO DE LEITURA DO ARQUIVO
	*/
	public function adicionar_extrato () {
		$config['upload_path'] = 'application/upload/';
		$config['allowed_types'] = 'bbt|gif|jpg|png';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$this->upload->set_allowed_types('*');

		if ($this->upload->do_upload('file')) {
			$file = $this->upload->data();
			$this->readSave($file['full_path'], $this->input->post('empresa'));
			exit(json_encode( array('success' => true, 'msg' => lang('sucesso_cadastro_extrato')) ));
		}
		exit(json_encode( array('success' => false, 'msg' => lang('falha_cadastro_extrato')) ));
	}	


	/**
	 * LE O ARQUIVO DE BAIXAS DE PAGAMENTOS E SALVA OS DADOS NO BANCO
	*/
	private function readSave($path, $empresa) {
		$this->load->model('extract_m', 'ext');
		$tipo_arquivo = substr($path,-3);
		$file = fopen($path, 'r');
		//Bradesco
		if($tipo_arquivo=="CSV"||$tipo_arquivo=="csv"){
			$line="";
			while (!feof($file)) {
				$line = $line.(fgets($file));
			}
			$separando_linha = preg_split('/\r\n|\r|\n/',$line);
			if((explode('","',$separando_linha[0])[1])=='Time'){
				explode('","',$separando_linha[0]);
				$array=false;
				foreach($separando_linha as $key=>$linha){
					if($key){
						$linha = explode('","',$linha);
						
						if($linha[0]){
							if($linha[4]=="Website Payment"||$linha[4]=="Subscription Payment"){
								$data_explode = explode('/',$linha[0]);
								$data_explode[0][0]="";
								if(strlen($data_explode[0])==2){
									$data_explode[0]='0'.$data_explode[0][1];
								}
								else{
									$data_explode[0]=$data_explode[0][1].$data_explode[0][2];
								}
								if(strlen($data_explode[1])==1){
									$data_explode[1]='0'.$data_explode[1];
								}
								
								$array=array(
									"agencia"=>"-",
									"conta"=>"Paypal",
									"data"=>$data_explode[2].'-'.$data_explode[0].'-'.$data_explode[1],
									"historico"=>$linha[15],
									"cod_verific"=>$linha[12],
									"c_d"=>'C',
									"valor"=>$linha[7],
									"empresa"=>$empresa,
									"data_insert"=>date("Y-m-d H:m:s"),
									"banco"=>"3"
								);
								$this->ext->save($array);
							}
						}
					}
				}
			}else{
				$array=false;
				$agencia=false;
				$conta=false;
				foreach($separando_linha as $linha){
					$linha = explode(';',$linha);
					if(strlen($linha[0])==14){
						$agencia=explode(' | ',$linha[0])[0];
						$conta=explode(' | ',$linha[0])[1];
					}
					elseif(count($linha)==6 && strlen($linha[0])>9 && $linha[1]!='SALDO ANTERIOR' && $linha[1]!='Lançamento'){
						$valor = 0;
						$c_d = "";
						if($linha[3]){
							$valor=$linha[3];
							$c_d = "C";
						}
						else{
							$valor=$linha[4]*-1;
							$c_d = "D";   
						}
						$array=array(
							"agencia"=>$agencia,
							"conta"=>$conta,
							"data"=>data_for_unix($linha[0]),
							"historico"=>$linha[1],
							"cod_verific"=>$linha[2],
							"c_d"=>$c_d,
							"valor"=>(float)str_replace(',','.',str_replace('.','',$valor)),
							"empresa"=>$empresa,
							"data_insert"=>date("Y-m-d H:m:s"),
							"banco"=>"2"
						);
						$this->ext->save($array);
					}
				}
			}
		}
		elseif($tipo_arquivo=="txt"||$tipo_arquivo=="TXT"){//Caixa
			$line="";
			while (!feof($file)) {
				$line = $line.(fgets($file));
			}
			$separando_linha = preg_split('/\r\n|\r|\n/',$line);
			$array=false;
			foreach($separando_linha as $linha){
				$linha = explode(';',$linha);
				if(count($linha)==6 && $linha[0]!='"Conta"'){
					$linha=str_replace('"',"",$linha);
					$array=array(
						"agencia"=>substr($linha[0], 0, 8),
						"conta"=>substr($linha[0], 8, 16),
						"data"=>(substr($linha[1],0,4).'-'.$linha[1][4].$linha[1][5].'-'.substr($linha[1],6,8)),
						"cod_verific"=>$linha[2],
						"historico"=>$linha[3],
						"valor"=>(float)$linha[4],
						"c_d"=>$linha[5],
						"empresa"=>"0",
						"data_insert"=>date("Y-m-d H:m:s"),
						"banco" => "1"
					);
					$this->ext->save($array);
				}
			}
		}
		elseif($tipo_arquivo=="bbt"||$tipo_arquivo=="BBT"){//Banco do brasil
			while (!feof($file)) {
				$line = fgets($file);
				$v = explode(';', $line);
				if (count($v) <= 1) break;
				if (trim($v[8]) != '999' && trim($v[8]) != '000') {
					$agencia = trim($v[0]);
					$conta = substr($v[1], 6, 6);
					$categoria = trim($v[8]);
					$densidade = trim($v[6]);
					$n1 = substr($v[10], -2, 2);
					$n2 = substr($v[10], 0, 15);
					$n = $n2.'.'.$n1;
					$n = number_format($n, 2, '.', '');
					$d = substr($v[3], 0, 2);
					$m = substr($v[3], 2, 2);
					$y = substr($v[3], 4, 4);
					$date = $y.'/'.$m.'/'.$d;
					$h = trim($v[9]) . ' - ' . trim($v[12]);
					$text = preg_replace( array('/[^A-Za-z0-9\-]/'), ' ' , $h);

					$this->ext->save(array('data_insert' => date('Y-m-d H:i:s'), 'cod_categoria' => $categoria, 'densidade_grav' => $densidade, 'agencia' => $agencia, 'conta' => $conta, 'data' => $date, 'c_d' => $v[11], 'historico' => $text, 'valor' => $n, 'empresa' => $empresa, 'cod_verific' => $v[7], 'banco' => "0"));
				}
			}
		}
		fclose($file);
	}



	/**
	 * LISTA OS EXTRATOS/PAGAMENTOS
	*/
	function get() {
		$html = '';
		$list = array();
		$id = $this->input->post('id');
		$empresa = $this->input->post('empresa');
		$banco = $this->input->post('banco');

		$data = $this->extrato->get($empresa, $banco, $id);
		foreach ($data as $v) {
			$ax = explode('-', $v->data);
			$data = date('d/m/Y', strtotime($v->data));
			if ($v->id_fatura) {
				$html = $v->id_fatura;
			} 

			else {
				$placeholder = '';

				if ('S A L D O - ' == $v->historico) $html = '';
				else if ($v->c_d == 'D') $placeholder = 'código do pagamento';
				else $placeholder = 'código da fatura';					
				
				$html = '<div class="input-prepend">
							<span data-date="'.$data.'" data-id="'.$v->id.'" data-type="'.$v->c_d.'" data-empresa="'.$empresa.'" class="vinc btn btn-primary input-group-addon add-on" data-value="'.number_format($v->valor, 2, ',', '.').'" id="basic-addon1" style="color: #fff;">Salvar</span>
							<input id="vinc'.$v->id.'" type="text" name="fatura" class="form-control" placeholder="'.$placeholder.'" aria-describedby="basic-addon1">
						</div>';	

			}

			$usuario = $v->usuario ? explode('@', $v->usuario)[0] : '';

			

			$list[] = array(
					'id'        => $v->id,
					'conta_movimentacao' => $v->agencia ? $v->agencia.' - '.$v->conta : '02003 - 28629X',
                    'data'      => '<span style="display:none;">'.$v->data.'</span>' . $data,
					'categoria' => $v->cod_categoria ? $v->cod_categoria : ' - ',
					'historico' => $v->historico,
					'codigo_transacao' => $v->cod_verific ? $v->cod_verific : ' - ',
					'valor'     => number_format($v->valor, 2, ',', '.'),
					'tipo'      => $v->c_d,
					'densidade' => $v->densidade_grav ? $v->densidade_grav : ' - ',
					'fatura'    => $html,
                    'data_baixa' => $v->data_baixa ? date('d/m/Y H:i:s', strtotime($v->data_baixa)) : ' - ',
                    'usuario' => $usuario,
					'empresa' => $empresa,
					'status' => $v->data_baixa ? 'Fechada' : 'Aberta'
				);
		}

		echo json_encode($list);

	}




	//Vincula uma fatura a um extrato
	function saveIdFatura() {
		$dados = $this->input->post();
        $usuario_email = $this->auth->get_login('admin', 'email');
		if ($dados['idf']){
			$save = $this->extrato->saveIdFatura($dados['idf'], $dados['id'], $dados['type'], $dados['value'], $dados['date'], $usuario_email, $dados['empresa']);
			if (in_array($save, [1, '1']))
				exit(json_encode( array('success' => true, 'msg' => 'Fatura vinculada com sucesso!') ));
		
			exit(json_encode( array('success' => false, 'msg' => $save) ));
		}
		else {
			exit(json_encode( array('success' => false, 'msg' => 'Informe o número da fatura') ));
		}
	}

	function vincularFatura() {
		$usuario_email = $this->auth->get_login('admin', 'email');
		if($this->input->post('vinculacao')){
			exit( json_encode($this->extrato->vincularFatura($this->input->post(),$usuario_email)) );
		}
		else{
			exit( json_encode(lang('erro_params')) );
		}
	}

	/**
	 * DESVINCULA UMA CONTA DE UM EXTRATO DE PAGAMENTO
	*/
	function desvincularConta() {
		$usuario_email = $this->auth->get_login('admin', 'email');
		if( $this->extrato->desvincularConta($this->input->post('id'),$this->input->post('id_conta'), $usuario_email))
			exit(json_encode( array('success' => true, 'msg' => 'Desvinculação realizada com sucesso!') ));
		
		exit(json_encode( array('success' => false, 'msg' => 'Falha ao desvincular, tente novamente mais tarde!') ));
	}

	/**
	 * DESVINCULA UMA CONTA DE UM EXTRATO DE PAGAMENTO
	*/
	function desvincularFatura() {
		$usuario_email = $this->auth->get_login('admin', 'email');

		if( $this->extrato->desvincularFatura($this->input->post('id'),$this->input->post('id_fatura'), $usuario_email))
			exit(json_encode( array('success' => true, 'msg' => 'Desvinculação realizada com sucesso!') ));
		
		exit(json_encode( array('success' => false, 'msg' => 'Falha ao desvincular, tente novamente mais tarde!') ));
	}

	
	function corrigirTabela() {
        $this->load->model('extract_m', 'ext');
	    $registros = $this->ext->getCorrigir();

	    foreach ($registros as $reg) {
	        $conf = $this->ext->getConfere(array('data' => $reg->data, 'valor' => $reg->valor, 'cod_verific' => $reg->cod_verific, 'historico' => $reg->historico));
	        if (count($conf) > 1) {
                for ($i=0; $i < count($conf) -1; $i++) {
                    if ($i > 0) {
                        $teste = $this->ext->deleteDados($conf[$i]->id);
                        if ($teste) echo $conf[$i]->id." apagado! </br>";
                    }
                }
            }
        }
        echo "Acabou.";
    }

	/**
	 * ADICIONA OS NOVOS EXTRATOS AO BANCO - POR MEIO DE LEITURA DO ARQUIVO
	*/
	public function vincular_multi_pagamentos () {
		$this->load->model('fatura');

		$dados = $this->input->post();
		$fatura = $this->fatura->get(array('id' => $dados['fatura_id']), true, 'Id, valor_total, iss, cofins, irpj, csll, pis');

		if (!empty($fatura)) {
			$usuario_email = $this->auth->get_login('admin', 'email');
			
			$total_desconto = round( $fatura->valor_total - ($fatura->valor_total * ($fatura->iss / 100)) - ($fatura->valor_total * ($fatura->cofins / 100)) - ($fatura->valor_total * ($fatura->irpj / 100)) - ($fatura->valor_total * ($fatura->csll / 100)) - ($fatura->valor_total * ($fatura->pis / 100) ), 2);
			$extratos_ids = explode(',', $dados['extratos']);

			//Busca os valores dos extratos
			$extratos = $this->extrato->getByIds($extratos_ids, 'id, valor, data');
			if (!empty($extratos)) {

				$valor_extratos = 0.0;
				
				foreach ($extratos as $key => $extrato) {
					$valor_extratos += $extrato->valor;
					$extrato->usuario = $usuario_email;
					$extrato->id_fatura = $fatura->Id;
					$extrato->data_baixa = date('Y-m-d');
					$extratos[$key] = (array)$extrato;
				}

				$attFatura = array(
					'status_fn' => 'Pago', 
					'status' => 1,					
					'data_pagto' => $extratos[0]['data'],
					'valor_pago' => $valor_extratos,
					'retorno_fn' => 'EXTRATOS-' . implode(',', $extratos_ids)
				);	

				if ( in_array( round( floatval( $valor_extratos ), 2), [ round( floatval( $fatura->valor_total ), 2 ), round( floatval( $total_desconto ), 2 )] ) ) {
					//Atualiza a fatura para paga
					$atualizou_fatura = $this->fatura->atualizar_fatura($fatura->Id, $attFatura);
					//Atualiza o extrato
					$atualizou_extratos = $this->extrato->update_batch_extratos($extratos);

					if ( $atualizou_fatura && $atualizou_extratos )
						exit(json_encode(array('success' => true, 'msg' => lang('sucesso_vinculacao'))));
					else
						exit(json_encode(array('success' => false, 'msg' => lang('falha_vinculacao_fatura_extratos'))));
				}
				else{
					exit(json_encode( array('success' => false, 'msg' => lang('valores_divergentes')) ));
				}	
			}	
		}

		exit(json_encode( array('success' => false, 'msg' => lang('fatura_nao_encontrada')) ));
	}


}
