<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Extract_m extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}

	function save($data) {
		$ax = $this->db->select('id')->where(array('cod_verific' => $data['cod_verific'], 'valor' => $data['valor'], 'data' => $data['data'], 'empresa' => $data['empresa']))->get('showtecsystem.extract')->result();
		if (!$ax) $this->db->insert('showtecsystem.extract', $data);
	}

	/**
	 * RETORNA OS EXTRATOS E SEUS LOGS
	*/
	function get($empresa, $banco, $id=false) {
		$this->db->select('e.*, el.data as data_vinculacao, el.usuario as usuario_vinculacao, el.alteracao');
		$this->db->join('showtecsystem.extract_log as el', 'el.extrato = e.id','left');
		if ($id) {
			$this->db->where('e.id', $id);
		}
		elseif ($banco) {
			$banco = $banco-1;
			$this->db->where(array('e.empresa' => $empresa, 'e.banco' => $banco));
		}
		else { 
			$this->db->where('e.empresa', $empresa);
		}
		$this->db->order_by('e.data', 'desc');
		$result = $this->db->get('showtecsystem.extract as e')->result();

		if (!empty($result)) {
			foreach($result as $linha){
				if(!$linha->id_fatura&&$linha->alteracao){
					$linha->id_fatura=$linha->alteracao;
					$linha->data_baixa=$linha->data_vinculacao;
					$linha->usuario=$linha->usuario_vinculacao;
				}
			}			
		}
		return $result;
	}
	

	function saveIdFatura($idf, $id, $type, $value, $date, $email,$empresa=false) {
		$date = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
		$sql  = "SELECT id FROM showtecsystem.extract WHERE id_fatura = {$idf} AND c_d = '{$type}';";
		$ax   = $this->db->query($sql)->result();
		if (!count($ax)) {
			if ($type == 'C') {
				$sql    = "SELECT valor_total AS value,iss,irpj,csll,pis,cofins FROM showtecsystem.cad_faturas WHERE id = {$idf};";
				$value1 = $this->db->query($sql)->result();
				if (count($value1)) {
					$valor_com_descontos = round($value1[0]->value-($value1[0]->value * ($value1[0]->iss / 100))-($value1[0]->value * ($value1[0]->cofins / 100))-($value1[0]->value * ($value1[0]->irpj / 100))-($value1[0]->value * ($value1[0]->csll / 100))-($value1[0]->value * ($value1[0]->pis / 100)),2);
					if (round($value1[0]->value,2) == round($value,2) || round($valor_com_descontos,2) == round($value,2)) {
						$sql = "UPDATE showtecsystem.cad_faturas SET status_fn = 'Pago', status = 1, valor_pago = {$value}, data_pagto = '{$date}', retorno_fn = 'EXTRATO-{$id}' WHERE id = {$idf};";
						$this->db->query($sql);
						return $this->db->update('showtecsystem.extract',
                            array('id_fatura' => $idf, 'usuario' => $email, 'data_baixa' => date('Y-m-d H:i:s')), array('id' => $id));
					} else {
						return " Valores divergentes.";
					}
				} else {
					return " Não foi encontrado nenhum valor na fatura.";
				}
			} else {
				$sql    = "SELECT valor AS value FROM showtecsystem.cad_contas WHERE id = {$idf};";
				$value1 = $this->db->query($sql)->result();
				if (count($value1)) {
					if (round($value1[0]->value,2) == round($value,2)) {
						$sql   = "UPDATE showtecsystem.cad_contas SET status = 1, valor_pago = {$value}, data_pagamento = '{$date}' WHERE id = {$idf};";
						$this->db->query($sql);
						return $this->db->update('showtecsystem.extract',
                            array('id_fatura' => $idf, 'usuario' => $email, 'data_baixa' => date('Y-m-d H:i:s')), array('id' => $id));
					} else {
						return " Valores divergentes.";
					}
				} else {
					return " Não foi encontrado nenhum valor na conta.";
				}
			}
		} else {
			return " O id {$idf} está duplicado.";
		}
		return "erro não dectado";
	}

	function vincularFatura($data, $email) {
		$extrato = $this->db->where('id',$data['id'])->get('showtecsystem.extract')->row();
		$total = 0;
		$total_desconto = 0;
		if($extrato->c_d=='D'){
			return "Função apenas para faturas";
		}
		else{
			$baixas = $this->db->where_in('id',$data['vinculacao'])->get('showtecsystem.cad_faturas')->result();
			if (!count($baixas)) {
				return " Nenhuma conta encontrada.";
			}
			$ids = "";
			
			foreach($baixas as $baixa){
				$total += $baixa->valor_total;
				$ids.=$baixa->Id.",";
				$total_desconto +=round($baixa->valor_total-($baixa->valor_total * ($baixa->iss / 100))-($baixa->valor_total * ($baixa->cofins / 100))-($baixa->valor_total * ($baixa->irpj / 100))-($baixa->valor_total * ($baixa->csll / 100))-($baixa->valor_total * ($baixa->pis / 100)),2);
			}
			if(round(floatval($extrato->valor),2)==round(floatval($total),2)){
				foreach($baixas as $baixa){
					$sql = "UPDATE showtecsystem.cad_faturas SET status_fn = 'Pago', status = 1, valor_pago = {$baixa->valor_total}, data_pagto = '{$extrato->data}', retorno_fn = 'EXTRATO-{$extrato->id}' WHERE id = {$baixa->Id};";
					$this->db->query($sql);					
					$this->insert_vinculacao_extrato(array('extrato'=>$extrato->id,'fatura'=>$baixa->Id,'STATUS'=>'1'));
				}
				$this->insert_log_extrato(array('extrato'=>$extrato->id,'data'=>date('Y-m-d H:i:s'),'usuario'=>$email,'alteracao'=>'Vinculou os id\'s '.$ids,'STATUS'=>'1'));

				return "Concluído com sucesso";
			}
			elseif(round(floatval($extrato->valor),2)==round(floatval($total_desconto),2)){
				foreach($baixas as $baixa){
					$valor_baixa = round($baixa->valor_total-($baixa->valor_total * ($baixa->iss / 100))-($baixa->valor_total * ($baixa->cofins / 100))-($baixa->valor_total * ($baixa->irpj / 100))-($baixa->valor_total * ($baixa->csll / 100))-($baixa->valor_total * ($baixa->pis / 100)),2);
					$sql = "UPDATE showtecsystem.cad_faturas SET status_fn = 'Pago', status = 1, valor_pago = {$valor_baixa}, data_pagto = '{$extrato->data}', retorno_fn = 'EXTRATO-{$extrato->id}' WHERE id = {$baixa->Id};";
					$this->db->query($sql);
					$this->insert_vinculacao_extrato(array('extrato'=>$extrato->id,'fatura'=>$baixa->Id,'STATUS'=>'1'));
				}
				$this->insert_log_extrato(array('extrato'=>$extrato->id,'data'=>date('Y-m-d H:i:s'),'usuario'=>$email,'alteracao'=>'Vinculou os id\'s '.$ids,'STATUS'=>'1'));

				return "Concluído com sucesso";
			}
			else{
				return " Valores divergentes.";
			}
		}
	}

	/**
	 * DESVINCULA UMA FATURA DE UM DE EXTRATO
	*/
	function desvincularFatura($id_extrato, $id_fatura, $email) {
		$this->db->update('showtecsystem.cad_faturas', array('status_fn' => '', 'status' => 0, 'valor_pago' => null, 'data_pagto' => null), array('Id' => $id_fatura));
		$this->db->update('showtecsystem.extract', array('id_fatura' => null, 'usuario' => $email, 'data_baixa' => date('Y-m-d H:i:s')), array('id' => $id_extrato));
		return $this->db->affected_rows();
	}

	/**
	 * DESVINCULA UMA CONTA DE UM DE EXTRATO
	*/
	function desvincularConta($id_extrato, $id_conta, $email) {
		$this->insert_log_extrato(array('extrato'=>$id_extrato, 'data'=>date('Y-m-d H:i:s'), 'usuario'=>$email,'alteracao'=>'Desvinculou o id '.$id_conta, 'STATUS'=>'1'));
		$this->db->update('showtecsystem.cad_contas', array('status' => 0, 'valor_pago' => null, 'data_pagamento' => null), array('id' => $id_conta));
		$this->db->update('showtecsystem.extract', array('id_fatura' => null, 'usuario' => $email, 'data_baixa' => null), array('id' => $id_extrato));
		return $this->db->affected_rows();		
	}

	public function insert_log_extrato($data){
		return $this->db->insert('showtecsystem.extract_log',$data);
	}
	
	public function insert_vinculacao_extrato($data){
		return $this->db->insert('showtecsystem.extract_vinculacao',$data);
	}

	public function getCorrigir() {
	    return $this->db->select('data, c_d, historico, valor, empresa, cod_verific')->get('showtecsystem.extract')->result();
    }

    public function getConfere($dados) {
	    return $this->db->get_where('showtecsystem.extract', $dados)->result();
    }

    public function deleteDados($id) {
	    return $this->db->delete('showtecsystem.extract', array('id' => $id));
    }

	/**
	 * GET DADOS EXTRATOS POR LISTA DE IDS
	*/
	public function getByIds($extratos_ids, $colunas='*') {
		return $this->db->select($colunas)->where_in('id', $extratos_ids)->order_by('data', 'asc')->get('showtecsystem.extract')->result();
	}

	/**
	 * ATUALZIA OS EXTRATOS POR UPDATE_BATCH
	*/
	public function update_batch_extratos($dados, $identificador='id') {
		@$this->db->update_batch('showtecsystem.extract', $dados, $identificador);
		return $this->db->affected_rows();
	}

}
