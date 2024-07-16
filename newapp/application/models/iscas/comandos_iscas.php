<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comandos_iscas extends CI_Model 
{	
		private $rastreamento;

		function __construct(){
				parent::__construct();
				$this->rastreamento = $this->load->database('rastreamento', TRUE);
				$this->load->database();
				$this->load->helper('integracao_ws_helper');
				$this->load->database();
		}

		public function relatorioComandosIsca($where)
		{
				$select_comandos = @$this->rastreamento->select('comandos.*')
						->from('rastreamento.comandos_enviados as comandos')
						->like('cmd_eqp','I', 'after')
						->where($where)
						->get();

				$comandos_gestor = [];
				$comandos_shownet = [];
				foreach($select_comandos->result() as $comando) {
						if($comando->origem_comando == '0') {
								$comandos_gestor[] = $comando; 
						} else {
								$comandos_shownet[] = $comando;
						}
				}

				$users_gestor = $comandos_gestor ?
						$this->buscarUsuariosGestor( array_map(function($o) {
								return $o->id_usuario;
						}, $comandos_gestor)) : [];

				$users_shownet = $comandos_shownet ?
						$this->buscarUsuariosShownet( array_map(function($o) {
								return $o->id_usuario;
						}, $comandos_shownet)) : [];

				$results = [];
				if($comandos_shownet && $users_shownet) {
						foreach($users_shownet as $user) {
								foreach($comandos_shownet as $comando) {
										if($comando->id_usuario == $user->code) {
												$comando->usuario = $user->usuario;
												$results[] = $comando;
										}
								}
						}
				}

				if($comandos_gestor && $users_gestor) {
						foreach($users_gestor as $user) {
								foreach($comandos_gestor as $comando) {
										if($comando->id_usuario == $user->code) {
												$comando->usuario = $user->usuario;
												$results[] = $comando;
										}
								}
						}
				}

				$comandos = $this->buscarClienteIscasComando($results);
				
        return $comandos;
		}

		/*
		* Busca os usuarios do gestor de codigos iguais aos itens da array $codes
		*/
		private function buscarUsuariosGestor($codes)
		{
				$select = $this->db->select('usuario_gestor.usuario, usuario_gestor.code')
								->from('showtecsystem.usuario_gestor')
								->where_in('showtecsystem.usuario_gestor.code', $codes)
								->get();

				if($select) {
						return $select->result();
				}

				return [];
		}

		/*
		* Busca os usuarios do shownet de ids iguais aos itens da array $ids
		*/
		private function buscarUsuariosShownet($ids)
		{
				$select = $this->db->select('usuario.login as usuario, usuario.id as code')
								->from('showtecsystem.usuario')
								->where_in('showtecsystem.usuario.id', $ids)
								->get();

				if($select) {
						return $select->result();
				}

				return [];
		}

		private function buscarClienteIscasComando($comandos)
		{
				$query = 'SELECT iscas.serial, iscas.id_cliente, clientes.nome as cliente 
									FROM showtecsystem.cad_iscas as iscas
									JOIN showtecsystem.cad_clientes as clientes
									ON clientes.id = iscas.id_cliente';

				$select_iscas = $this->db->query($query);

				if($select_iscas && $comandos) {
						foreach($select_iscas->result() as $isca) {
								foreach($comandos as $comando) {
										if($isca->serial == $comando->cmd_eqp) {
												$comando->cliente = $isca->cliente;
										}
								}
						}
				}

				return $comandos;
		}
		
		public function comandosIsca($where)
		{
				$select_comandos = @$this->rastreamento->select('comandos.*')
						->from('rastreamento.comandos_enviados as comandos')
						->like('cmd_eqp','I', 'after')
						->where($where)
						->order_by('cmd_cadastro', 'desc')
						->get();

				if($select_comandos) {
					return $select_comandos->result();
				}

				return [];
		}

    public function enviarComandoIsca($insert){

        $resposta_cmd = @$this->rastreamento->insert('rastreamento.comandos_enviados', $insert);

        return $resposta_cmd;

    }

    public function enviarComandoIscaLote($insertbatch)
    {
        $resposta_cmd = @$this->rastreamento->insert_batch('rastreamento.comandos_enviados', $insertbatch);

        return $resposta_cmd;
	}
		
	public function buscarDadosIsca($serial)
	{
		$select = $this->db->select('ver_app, ccid, serial, id, apn,usuario,senha,ip1,porta1,ip2,porta2')
												->from('showtecsystem.cad_equipamentos')
												->where('serial', $serial)
												->get();
		if($select) {
			return $select->result();
		}

		return null;
	}

	public function cancelarComando($id, $now) {
		$dados = [
			'cmd_confirmacao' => $now,
		];

		@$this->rastreamento->update('rastreamento.comandos_enviados', $dados, ['cmd_id' => $id]);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
		
	public function getICCID($serial){
		return $this->db->select('ccid')->
			where('serial',$serial)->get('showtecsystem.cad_equipamentos')->result_array()[0]['ccid'];
	}
	public function getConfig($serial){
		return $this->db->select('apn,usuario,senha,ip1,porta1,ip2,porta2')->
			where('serial',$serial)->get('showtecsystem.cad_equipamentos')->result_array()[0];
	}
	
}
