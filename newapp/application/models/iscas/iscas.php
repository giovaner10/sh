<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Iscas extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_iscas_sem_vinculo($select = false, $start = 0, $limit = 999999, $filtro = false, $search = false, $order = false, $orderDir = false)
	{

		if ($select) {
			$this->db->select($select);
		} else {
			$this->db->select('id, serial, descricao, modelo, marca, data_cadastro, data_expiracao, status');
		}

		if ($order && $orderDir) {
			$this->db->order_by($order, $orderDir);
		}

		if ($filtro && $search) {
			if ($filtro == 'data_cadastro' || $filtro == 'data_expiracao') {
				$this->db->where(array($filtro . ' >= ' => $search . ' 00:00:01', $filtro . ' <= ' => $search . ' 23:59:59'));
			} else {
				$this->db->like($filtro, $search);
			}
		}

		$iscas = $this->db->where('id_cliente', null)->get('showtecsystem.cad_iscas', $limit, $start)->result();

		$qtdTotal = $this->db->select('id')->where('id_cliente', null)->count_all_results('showtecsystem.cad_iscas');

		$qtdFiltrado = $qtdTotal;
		if ($filtro && $search) {
			$this->db->select('id');

			if ($filtro == 'data_cadastro' || $filtro == 'data_expiracao') {
				$this->db->where(array($filtro . ' >= ' => $search . ' 00:00:01', $filtro . ' <= ' => $search . ' 23:59:59'));
			} else {
				$this->db->like($filtro, $search);
			}

			$qtdFiltrado = $this->db->where('id_cliente', null)->count_all_results('showtecsystem.cad_iscas');
		}

		return array('dados' => $iscas, 'qtd_total' => $qtdTotal, 'qtd_filtrado' => $qtdFiltrado);
	}

	/**
	 * Retorna isca para cadastro de agendamento
	 */
	public function get_isca_agendamento($serial, $tipo)
	{

		if ($tipo == "instalacao") {
			$query = $this->db->select('id, serial as text')->like('serial', $serial)->where('id_cliente ', null)->where('status', 1)->get('showtecsystem.cad_iscas')->result();
		} else {
			$query = $this->db->select('id, serial as text')->like('serial', $serial)->where('status', 1)->get('showtecsystem.cad_iscas')->result();
		}
		return $query;
	}
	/**
	 * Retorna instalador para cadastro de agendamento
	 */
	public function get_instalador($instalador)
	{
		$query = $this->db->select('id, nome as text')->like('nome', $instalador)->get('showtecsystem.instaladores')->result();
		return $query;
	}
	/**
	 * Retorna Clientes para cadastro de agendamento
	 */
	public function get_cliente($cliente)
	{
		$query = $this->db->select('id, nome as text')->like('nome', $cliente)->get('showtecsystem.cad_clientes')->result();
		return $query;
	}
	/**
	 * Insere o cadastro de iscas
	 */
	public function cad_agendamento($insert)
	{
		$retorno = $this->db->insert('showtecsystem.os_iscas', $insert);
		if ($retorno == 1) {
			$last_agend_id = $this->db->insert_id();
			$last_agend = $this->db->select('os_iscas.*, cad_iscas.serial')->join('showtecsystem.cad_iscas as cad_iscas', 'cad_iscas.id = os_iscas.isca_id')->where('os_iscas.id', $last_agend_id)->get('showtecsystem.os_iscas')->result_array()[0];
			return $last_agend;
		} else {
			return [];
		}
	}
	/**
	 * Insere o cadastro de iscas
	 */
	public function update_agendamento($id, $update)
	{

		$retorno = $this->db->where('id', $id)->update('showtecsystem.os_iscas', $update);
		if ($retorno == 1) {
			$agend = $this->get_agendamento_by_id($id);
			return array('status' => true, 'msg' => "Agendamento atualizado com sucesso.", 'agend' => $agend);
		} else {
			return array('status' => false, 'msg' => "Agendamento não encontrado.");;
		}
	}

	public function get_agendamentos($start_date, $end_date)
	{

		$agendamentos = $this->db->select('*')->where("data_agendamento BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'")->where("status", 0)->get('showtecsystem.os_iscas')->result_array();

		return $agendamentos;
	}
	public function get_agendamentos_por_dia($dia)
	{
		$start_date = $dia . ' 00:00:00';
		$end_date = $dia . ' 23:59:59';
		$query = $this->db->select('os_iscas.*, cad_iscas.serial')->join('showtecsystem.cad_iscas as cad_iscas', 'cad_iscas.id = os_iscas.isca_id')->where("data_agendamento BETWEEN '{$start_date}' AND '{$end_date}'")->get('showtecsystem.os_iscas')->result_array();
		return $query;
	}
	public function get_isca($serial)
	{

		$query = $this->db->select('serial as id, serial as text')->like('serial', $serial)->get('showtecsystem.cad_iscas')->result();

		return $query;
	}

	public function get_agendamento_by_id($id)
	{
		$query = $this->db->select('os_iscas.*, cad_iscas.serial,cad_clientes.nome as nome_cliente, instaladores.nome as nome_instalador')->from('showtecsystem.os_iscas')->join('showtecsystem.cad_iscas as cad_iscas', 'cad_iscas.id = os_iscas.isca_id')->join('showtecsystem.cad_clientes', 'cad_clientes.id = os_iscas.cliente_id')->join('showtecsystem.instaladores', 'instaladores.id = os_iscas.instalador_id')->where('os_iscas.id', $id)->get()->result_array()[0];
		return $query;
	}

	public function confirmar_agendamento($id_agendamento, $update)
	{

		$query = $this->db->where('id', $id_agendamento)->update('showtecsystem.os_iscas', $update);


		if ($query == 1) {
			$agend = $this->db->select('*')->where('id', $id_agendamento)->get('showtecsystem.os_iscas')->result_array()[0];
			return array('status' => true, 'msg' => "Agendamento confirmado com sucesso.", 'agendamento' => $agend);
		} else {
			return array('status' => false, 'msg' => "Erro ao confirmar agendamento.");
		}
	}
	public function get_iscas_vinculadas($select = false, $start = 0, $limit = 999999, $filtro = false, $search = false, $order = false, $orderDir = false)
	{

		if ($select) {
			$this->db->select($select);
		} else {
			$this->db->select('cad_iscas.id, cad_iscas.id_contrato, cad_iscas.serial, cad_iscas.descricao, cad_iscas.modelo, cad_iscas.marca, cad_iscas.data_cadastro, cad_iscas.data_expiracao, cad_iscas.status, cad_iscas.id_cliente, cad_clientes.nome');
		}

		if ($order && $orderDir) {
			$this->db->order_by($order, $orderDir);
		}

		if ($filtro && $search) {
			if ($filtro == 'cad_iscas.data_cadastro' || $filtro == 'cad_iscas.data_expiracao') {
				$this->db->where(array($filtro . ' >= ' => $search . ' 00:00:01', $filtro . ' <= ' => $search . ' 23:59:59'));
				
			} else {
				if ($filtro == 'cad_iscas.status') {
					if ($search === 'ativa') {
						$search = 1;
					} else if ($search === 'inativa') {
						$search = 0;
					}
				}
				$this->db->like($filtro, $search);
			}
		}

		$iscas = $this->db->join('cad_clientes', 'cad_clientes.id = cad_iscas.id_cliente')->get('showtecsystem.cad_iscas', $limit, $start)->result();

		$qtdTotal = $this->db->select('cad_iscas.id')->join('cad_clientes', 'cad_clientes.id = cad_iscas.id_cliente')->count_all_results('showtecsystem.cad_iscas');

		if ($filtro == 'cad_iscas.status') {
			if ($search === 1) {
				$search = 'ativa';
			} else if ($search === 0) {
				$search = 'inativa';
			}
		}

		if ($filtro && $search) {
			$this->db->select('id');

			if ($filtro == 'cad_iscas.data_cadastro' || $filtro == 'cad_iscas.data_expiracao') {
				$this->db->where(array($filtro . ' >= ' => $search . ' 00:00:01', $filtro . ' <= ' => $search . ' 23:59:59'));
			} else {
				if ($filtro == 'cad_iscas.status') {
					if ($search === 'ativa') {
						$search = 1;
					} else if ($search === 'inativa') {
						$search = 0;
					}
				}
				$this->db->like($filtro, $search);
			}

			$qtdTotal = $this->db->join('cad_clientes', 'cad_clientes.id = cad_iscas.id_cliente')->count_all_results('showtecsystem.cad_iscas');
		}

		return array('dados' => $iscas, 'qtd_total' => $qtdTotal);
	}

	public function get_isca_by_id($id_isca)
	{

		$query = $this->db->select('iscas.*, cad_clientes.nome,cad_clientes.cnpj, contratos.data_contrato')->from('showtecsystem.cad_iscas as iscas')->where('iscas.id', $id_isca)->join('cad_clientes', 'cad_clientes.id = iscas.id_cliente')->join('contratos', 'contratos.id = iscas.id_contrato')->get();

		if ($query->num_rows() > 0)
			return $query->result_array()[0];
		else
			return [];
	}

	public function getDadosContratoClienteByCnpj($cnpj)
	{
		$query = $this->db->select(
			'clientes.id, clientes.nome, clientes.cnpj, clientes.cpf, 
				clientes.endereco, clientes.numero, clientes.bairro, clientes.cidade, 
				contratos.id as id_contrato, contratos.tipo_proposta, 
				contratos.quantidade_veiculos'
		)->from('showtecsystem.cad_clientes as clientes')->where('clientes.cnpj', $cnpj)->where(array('contratos.tipo_proposta' => 6, 'contratos.status' => 2))-> //equipamento isca, contrato ativo
			join('contratos', 'contratos.id_cliente = clientes.id')->get();

		if ($query->num_rows() > 0)
			return $query->result_array();
		else
			return [];
	}

	public function getDadosContratoClienteByCpf($cpf)
	{
		$query = $this->db->select(
			'clientes.id, clientes.nome, clientes.cnpj, clientes.cpf, 
				clientes.endereco, clientes.numero, clientes.bairro, clientes.cidade, 
				contratos.id as id_contrato, contratos.tipo_proposta, 
				contratos.quantidade_veiculos'
		)->from('showtecsystem.cad_clientes as clientes')->where('clientes.cpf', $cpf)->where(array('contratos.tipo_proposta' => 6, 'contratos.status' => 2))-> //equipamento isca, contrato ativo
			join('contratos', 'contratos.id_cliente = clientes.id')->get();

		if ($query->num_rows() > 0)
			return $query->result_array();
		else
			return [];
	}

	public function getDadosContratoClienteByIdCliente($id_cliente)
	{
		$query = $this->db->select(
			'clientes.id, clientes.nome, clientes.cnpj, clientes.cpf, 
				clientes.endereco, clientes.numero, clientes.bairro, clientes.cidade, 
				contratos.id as id_contrato, contratos.tipo_proposta, 
				contratos.quantidade_veiculos'
		)->from('showtecsystem.cad_clientes as clientes')->where('clientes.id', $id_cliente)->where(array('contratos.tipo_proposta' => 6, 'contratos.status' => 2))-> //equipamento isca, contrato ativo
			join('contratos', 'contratos.id_cliente = clientes.id')->get();

		if ($query->num_rows() > 0)
			return $query->result_array();
		else
			return [];
	}

	public function getDadosContratoClienteByContrato($id_contrato)
	{
		$query = $this->db->select(
			'clientes.id, clientes.nome, clientes.cnpj, clientes.cpf, 
				clientes.endereco, clientes.numero, clientes.bairro, clientes.cidade, 
				contratos.id as id_contrato, contratos.tipo_proposta, 
				contratos.quantidade_veiculos'
		)->from('showtecsystem.contratos as contratos')->where('contratos.id', $id_contrato)->where(array('contratos.tipo_proposta' => 6, 'contratos.status' => 2))-> //equipamento isca, contrato ativo
			join('cad_clientes as clientes', 'clientes.id = contratos.id_cliente')->get();

		if ($query->num_rows() > 0)
			return $query->result_array();
		else
			return [];
	}

	public function getIscasAtivasByIdCliente($where)
	{
		$query = $this->db->select('*')->where($where)->get('showtecsystem.cad_iscas');

		if ($query->num_rows() > 0)
			return $query->result_array();
		else
			return [];
	}

	public function getIscasAtivasByContrato($id_contrato)
	{
		$query = $this->db->select('serial')->where(array('id_contrato' => $id_contrato, 'status' => 1))->get('showtecsystem.cad_iscas');

		if ($query->num_rows() > 0)
			return $query->result_array();
		else
			return [];
	}

	public function getUsuariosIscas()
	{
		$query = $this->db->select(
			'usuario.*'
		)->from('showtecsystem.usuario_gestor as usuario')->join('usuario_isca', 'usuario_isca.id_usuario = usuario.code')->get();

		if ($query->num_rows() > 0)
			return $query->result_array();
		else
			return [];
	}
	public function getIscas($where)
	{
		// pr($where);die();
		// Caso seja selecionado todas as iscas
		if (!isset($where['id_cliente']) || !isset($where['id_cliente !='])) {
			// Seleciona as iscas em estoque

			$queryEstoque = $this->db->select('*')->where($where)->where('id_cliente', null)->get('showtecsystem.cad_iscas')->result_array();

			if (isset($where['status'])) {
				$tempStatus = $where['status'];
				unset($where['status']);
				$where['iscas.status'] = $tempStatus;
			}
			if (isset($where['data_cadastro >=']) && isset($where['data_cadastro >='])) {
				$tempInicioCadastro = $where['data_cadastro >='];
				$tempFimCadastro = $where['data_cadastro <='];
				unset($where['data_cadastro >=']);
				unset($where['data_cadastro <=']);
				$where['iscas.data_cadastro >='] = $tempInicioCadastro;
				$where['iscas.data_cadastro <='] = $tempFimCadastro;
			}
			// Seleciona as iscas vinculadas com um join na tabela de clientes
			$queryVinculadas = $this->db->select('iscas.*, cad_clientes.nome')->from('showtecsystem.cad_iscas as iscas')->where($where)->join('cad_clientes', 'cad_clientes.id = iscas.id_cliente')->get()->result_array();

			$query = array_merge($queryEstoque, $queryVinculadas);
			return $query;
		} else { // caso contrário
			// Retorna as iscas em Estoque
			if ($where['id_cliente'] == null) {
				$query = $this->db->select('*')->where($where)->get('showtecsystem.cad_iscas')->result_array();
				return $query;
			} else { //Retorna as iscas vinculadas
				// Muda a chave do status no array where
				if (isset($where['status'])) {
					$tempStatus = $where['status'];
					unset($where['status']);
					$where['iscas.status'] = $tempStatus;
				}
				// Muda a chave da data no array where
				if (isset($where['data_cadastro >=']) && isset($where['data_cadastro <='])) {
					$tempInicioCadastro = $where['data_cadastro >='];
					$tempFimCadastro = $where['data_cadastro <='];
					unset($where['data_cadastro >=']);
					unset($where['data_cadastro <=']);
					$where['iscas.data_cadastro >='] = $tempInicioCadastro;
					$where['iscas.data_cadastro <='] = $tempFimCadastro;
				}
				$query = $this->db->select('iscas.*, cad_clientes.nome')->from('showtecsystem.cad_iscas as iscas')->where($where)->join('cad_clientes', 'cad_clientes.id = iscas.id_cliente')->get()->result_array();
				return $query;
			}
		}
	}

	/**
	 * insere isca quando não possui o serial cadastrado
	 * @param Array $isca
	 * @return Array resposta
	 */
	public function insert_isca($isca)
	{
		$getIsca = $this->db->select('*')->where('serial', $isca['serial'])->get('showtecsystem.cad_iscas')->result();
		if (count($getIsca) > 0) {
			return array('status' => false, 'msg' => 'Isca com serial "' . $isca['serial'] . '" já está cadastrada.');
		} else {
			$retorno = $this->db->insert('showtecsystem.cad_iscas', $isca);
			if ($retorno) {
				return array('status' => true, 'msg' => 'Isca cadastrada com sucesso!');
			} else {
				return array('status' => false, 'msg' => 'Erro ao cadastrar isca');
			}
		}
	}

	public function migrarIsca($id_isca, $id_cliente, $id_contrato)
	{

		$isca = $this->db->where('id', $id_isca)->update('showtecsystem.cad_iscas', array(
			'placa' => '',
			'status' => 1,
			'id_cliente' => $id_cliente,
			'id_contrato' => $id_contrato
		));
		return $isca;
	}

	public function desvincularIsca($id_isca)
	{
		$query = $this->db->select('*')->where('id', $id_isca)->get('showtecsystem.cad_iscas');

		if ($query->result() > 0) {
			$this->db->where('id', $id_isca)->update('showtecsystem.cad_iscas', array(
				'placa' => '',
				'status' => 0,
				'id_cliente' => null,
				'id_contrato' => null
			));

			// Insere registro da desvinculação da isca
			$this->db->insert('showtecsystem.historico_desvinculacao_isca', array(
				'id_isca' => $id_isca,
				'data_desvinculacao' => date("Y-m-d h:i:s")
			));

			return true;
		} else {
			return false;
		}
	}

	public function ativarDesativarIsca($id_isca)
	{

		$query = $this->db->select('*')->where('id', $id_isca)->get('showtecsystem.cad_iscas');


		if ($query->result() > 0) {
			if ($query->result()[0]->status == 0)
				$status = 1;
			else
				$status = 0;

			$this->db->where('id', $id_isca)->update('showtecsystem.cad_iscas', array(
				'status' => $status,
			));

			return array(
				'status' => true,
				'msg' => $status == 1 ? 'Isca ativada com sucesso!' : 'Isca desativada com sucesso!'
			);
		} else {
			return array(
				'status' => false,
				'msg' => 'Isca não encontrada'
			);
		}
	}

	public function load_clientes_hasIscas()
	{
		$this->db->select('showtecsystem.cad_clientes.nome, showtecsystem.cad_clientes.id, showtecsystem.cad_clientes.cpf, showtecsystem.cad_clientes.cnpj');
		$this->db->distinct();
		$this->db->from('showtecsystem.cad_clientes');
		$this->db->join('showtecsystem.contratos', 'showtecsystem.contratos.id_cliente = showtecsystem.cad_clientes.id');
		$this->db->where('showtecsystem.contratos.tipo_proposta', 6);
		$query = $this->db->where_in('showtecsystem.contratos.status', array(0, 1, 2))->get()->result_array();

		if ($query) {
			return $query;
		} else {
			return false;
		}
	}
	public function getInfoIsca($id_isca)
	{
		$query = $this->db->select('*')->from('showtecsystem.cad_iscas')->where('id', $id_isca)->get();

		if ($query->num_rows() > 0)
			return $query->result_array()[0];
		else
			return [];
	}

	public function updateIsca($id_isca, $update)
	{
		$retorno = $this->db->where('id', $id_isca)->update('showtecsystem.cad_iscas', $update);
		if ($retorno == 1) {
			$isca = $this->getInfoIsca($id_isca);
			return array('status' => true, 'msg' => "Isca atualizada com sucesso!", 'isca' => $isca);
		} else {
			return array('status' => false, 'msg' => "Isca não encontrada.");
		}
	}

	public function alterarDataExpiracao($iscas, $data)
	{
		$retorno = $this->db->where_in('id', $iscas)->update('showtecsystem.cad_iscas', ['data_expiracao' => $data]);

		if ($retorno) {
			return array('status' => true, 'message' => "Data de Expiração alterada com sucesso!");
		} else {
			return array('status' => false, 'message' => "Erro ao alterar data de expiração!");
		}
	}

	public function save_auditoria_iscas($dados)
	{
		$this->db->insert('showtecsystem.auditoria_iscas', $dados);
	}

	public function get_cad_isca_by_id($id_isca, $select = false)
	{
		if ($select) {
			$query = $this->db->select($select)->from('showtecsystem.cad_iscas')->where('id', $id_isca)->get();
		} else {
			$query = $this->db->select('serial')->from('showtecsystem.cad_iscas')->where('id', $id_isca)->get();
		}

		if ($query->num_rows() > 0)
			return $query->result_array()[0];
		else
			return [];
	}

	public function listar($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC', $select = '*', $like = NULL)
	{

		$this->db->select($select);
		if ($like)
			$this->db->like($like);

		return $this->db->where($where)->order_by($campo_ordem, $ordem)->get('cad_iscas', $limite, $paginacao)->result();
	}
}
