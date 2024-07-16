<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Instalador extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->db_rastreamento = $this->load->database('rastreamento', TRUE);
	}

	public function add($dados)
	{
		if (!$this->isValidEmail($dados['email'])) {

			$this->db->insert('showtecsystem.instaladores', $dados);
			if ($this->db->affected_rows() > 0) {
				$id_instalador = $this->db->insert_id();
				if ($id_instalador) {
					$dados_banco = array(
						'banco' => $dados['banco'],
						'agencia' => $dados['agencia'],
						'operacao' => $dados['operacao'],
						'conta' => $dados['conta'],
						'tipo' => $dados['tipo_conta'],
						'cpf' => $dados['cpf_conta'],
						'titular' => $dados['titular_conta'],
					);
					$this->add_contaBank($dados_banco, $id_instalador);
				}
				return true;
			} else {
				throw new Exception('Não foi possível gravar no banco de dados. Tente novamente.');
			}
		} else {
			return false;
		}
		return true;
	}

	public function atualizar($dados, $id)
	{
		$this->db->where('id', $id);
		$resultado = $this->db->update('showtecsystem.instaladores', $dados);
		if ($resultado)
			return true;
		return false;
	}

	public function isValidEmail($email)
	{
		$exist = $this->db->from('showtecsystem.instaladores')->where(array('email' => $email))->count_all_results();
		if ($exist == 0)
			return false;
		return true;
	}

	/*
	 * PESQUISA INSTALADORES COM FILTRO - SELECT2 AJAX
	 */
	public function InstaladorListSelect($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC', $select = '*', $like = NULL)
	{

		$this->db->select($select);
		if ($like)
			$this->db->like($like);

		return $this->db->where($where)->order_by($campo_ordem, $ordem)->get('showtecsystem.instaladores', $limite, $paginacao)->result();
	}

	// public function isValidCPF($cpf) {
	// 	$exist = $this->db->from('showtecsystem.instaladores')->where(array('cpf' => $cpf))->count_all_results();
	// 	if($exist == 0)
	// 		return false;
	// 	return true;
	// }

	// public function get_cidades($sigla){
	// 	$query = $this->db->select('nome, estado')
	// 	->where('estado', $sigla)
	// 	->where('pais', 'BRA')
	// 	->order_by('nome')
	// 	->get('showtecsystem.tb_cidade');
	// 	if($query->num_rows() > 0){
	// 		return $query->result();
	// 	}
	// 	return false;

	// }

	public function get_valores()
	{
		$query = $this->db->select('AVG(valor_retirada) as retirada, AVG(valor_instalacao) as instalacao, AVG(valor_manutencao) as manutencao, AVG(valor_desloc_km) as desloc')
			->get('showtecsystem.instaladores');
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_with_last_track($where, $select = '*')
	{

		$veiculos = [];
		$seriais = $this->db_rastreamento->select($select)
			->where($where)
			->get('last_track')
			->result();

		foreach ($seriais as $serial) {
			$arr[] = $serial->ID;
			$check = [];
		}

		if (count($seriais) > 0) {

			$veiculo = $this->db->select('*')
				->distinct()
				->where_in('serial', $arr)
				->where('CNPJ_ !=', '')
				->get('systems.cadastro_veiculo')
				->result();


			foreach ($seriais as $serial) {
				foreach ($veiculo as $key => $valor) {
					if ($serial->ID == $valor->serial && !in_array($serial->ID, $check)) {
						$check[] = $serial->ID;
						$veiculos[] = $valor;
					}
				}
			}
		}

		return $veiculo;
	}

	public function get($where)
	{
		$instalador = false;
		$query = $this->db->get_where('showtecsystem.instaladores', $where);
		if ($query->num_rows() > 0)
			foreach ($query->result() as $instalador)
				;

		return $instalador;
	}

	public function get_instaladores($limit, $offset)
	{

		$this->db->limit($offset, $limit);
		$this->db->order_by('id', 'asc');

		$query = $this->db->get('showtecsystem.instaladores');

		if ($query->num_rows()) {

			return $query->result();
		}

		return false;
	}

	public function get_total_instaladores()
	{
		$query = $this->db->get('showtecsystem.instaladores');
		return $query->num_rows();
	}

	public function get_json_instaladores($q)
	{

		$query = $this->db->select('*')
			->like('nome', $q)
			->or_like('sobrenome', $q)
			->get('showtecsystem.instaladores');

		if ($query->num_rows > 0) {
			foreach ($query->result_array() as $row) {
				$instalador['label'] = htmlentities(stripslashes($row['nome'] . " " . $row['sobrenome']));
				$instalador['value'] = htmlentities(stripslashes($row['id']));
				$instaladores[] = $instalador;
			}
			echo json_encode($instaladores);
		} else {

			return $msg = "Sem instaladores Cadastrados";
		}
	}

	public function listar_pesquisa_instaladores($pesquisa)
	{

		$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);

		$query = $this->db->get('showtecsystem.instaladores');

		if ($query->num_rows()) {

			return $query->result();
		}
		return false;
	}

	public function update_contas($dados)
	{
		return $this->db->insert_batch('cad_contasbank', $dados);
	}

	public function get_primaryBank($instalador)
	{
		$principal = $this->db->get_where('cad_contasbank', array('id_retorno' => $instalador, 'cad_retorno' => 'instalador', 'status' => '1'))->row();

		if ($principal) {
			return $principal;
		} else {
			$secundaria = $this->db->get_where('cad_contasbank', array('id_retorno' => $instalador, 'cad_retorno' => 'instalador'))->row();

			if ($secundaria)
				return $secundaria;
			else
				return false;
		}
	}

	public function getListOp()
	{
		return $this->db->select('id')->order_by('id', 'desc')->limit(1)->get('showtecsystem.ordem_pagamento')->result();
	}

	public function get_services($where)
	{
		//pr($where);die;
		$query = $this->db->order_by('id', 'desc')->get_where('showtecsystem.servico_op', array('id_instalador' => $where, 'status' => 0));
		return $query->result();
	}

	public function get_servicesPagos($where)
	{
		//pr($where);die;
		$query = $this->db->order_by('id', 'desc')->get_where('showtecsystem.listServiceOp', array('id_instalador' => $where));
		return $query->result();
	}

	public function get_dataInstalador($id)
	{
		return $this->db->select('*')->where('id', $id)->get('showtecsystem.instaladores')->result();
	}

	public function getId_Instalador()
	{
		return $this->db->select('*')->get('showtecsystem.instaladores')->result();
	}

	public function get_all_installer()
	{
		return $this->db->select('*')->get('showtecsystem.instaladores')->result();
	}

	public function get_all_installer_details($startRow = 0, $endRow = 10, $whereSearch = null)
	{
		$offset = $startRow;
		$limit = $endRow - $startRow;

		$this->db->select(
			'
			i.id, 
			i.nome, 
			i.sobrenome, 
			i.cidade, 
			i.estado, 
			i.telefone, 
			i.celular, 
			i.email, 
			i.block, 
			i.valor_instalacao, 
			i.valor_manutencao, 
			i.valor_retirada, 
			i.valor_desloc_km,
			i.email, 
			i.senha,
			AVG(r.nota) AS avgNota, 
			COUNT(o.id) AS qtdDeOs'
		);
		$this->db->from('showtecsystem.instaladores i');
		$this->db->join('showtecsystem.rating_tec r', 'r.id_instalador = i.id', 'left');
		$this->db->join('showtecsystem.os o', 'o.id_instalador = i.id AND o.status = 1', 'left');

		if ($whereSearch !== null) {
			if (isset($whereSearch['status_pg'])) {
				$this->db->where('o.status_pg', $whereSearch['status_pg']);
			}
			if (isset($whereSearch['nomeCompleto'])) {
				$this->db->like("CONCAT(TRIM(i.nome), ' ', COALESCE(TRIM(i.sobrenome), ''))", $whereSearch['nomeCompleto'], 'both');
			}
			if (isset($whereSearch['nome'])) {
				$this->db->like('i.nome', $whereSearch['nome'], 'both');
			}
			if (isset($whereSearch['sobrenome'])) {
				$this->db->like('i.sobrenome', $whereSearch['sobrenome'], 'both');
			}
			if (isset($whereSearch['cidade'])) {
				$this->db->like('i.cidade', $whereSearch['cidade'], 'both');
			}
			if (isset($whereSearch['estado'])) {
				$this->db->where('i.estado', $whereSearch['estado']);
			}
		}

		$this->db->group_by('i.id');
		$this->db->order_by('i.id', 'ASC');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}

	public function get_all_installer_count($whereSearch = null)
	{
		$this->db->select('COUNT(DISTINCT i.id) AS total');
		$this->db->from('showtecsystem.instaladores i');

		if ($whereSearch !== null && is_array($whereSearch)) {
			if (isset($whereSearch['status_pg'])) {
				$this->db->join('showtecsystem.rating_tec r', 'r.id_instalador = i.id', 'left');
				$this->db->join('showtecsystem.os o', 'o.id_instalador = i.id AND o.status = 1', 'left');
				$this->db->where('o.status_pg', $whereSearch['status_pg']);
			}
			if (isset($whereSearch['nomeCompleto'])) {
				$this->db->like("CONCAT(TRIM(i.nome), ' ', COALESCE(TRIM(i.sobrenome), ''))", $whereSearch['nomeCompleto'], 'both');
			}
			if (isset($whereSearch['nome'])) {
				$this->db->like('i.nome', $whereSearch['nome'], 'both');
			}
			if (isset($whereSearch['sobrenome'])) {
				$this->db->like('i.sobrenome', $whereSearch['sobrenome'], 'both');
			}
			if (isset($whereSearch['cidade'])) {
				$this->db->like('i.cidade', $whereSearch['cidade'], 'both');
			}
			if (isset($whereSearch['estado'])) {
				$this->db->where('i.estado', $whereSearch['estado']);
			}
		}

		$result = $this->db->get()->row();
		return $result->total;
	}

	public function get_all_installer_login($whereSearch = null)
	{
		$this->db->select(
			'
			i.id, 
			i.email, 
			i.senha'
		);
		$this->db->from('showtecsystem.instaladores i');
		$this->db->join('showtecsystem.rating_tec r', 'r.id_instalador = i.id', 'inner');
		$this->db->join('showtecsystem.os o', 'o.id_instalador = i.id AND o.status = 1', 'inner');

		$this->db->where('i.id', $whereSearch['id']);

		$this->db->group_by('i.id');
		return $this->db->get()->result();
	}

	public function get_install()
	{
		$query = $this->db->get('showtecsystem.instaladores');
		return $query->result();
	}

	public function get_banks($nome = null)
	{
		$this->db->select(
			'
			b.id, 
			b.codigo, 
			b.nome'
		);
		$this->db->from('showtecsystem.cad_bancos b');

		if (!empty($nome)) {
			$this->db->like('b.nome', $nome, 'both');
		}
		return $this->db->get()->result();
	}

	public function get_bank_by_code($code = null)
	{
		return $this->db->get_where('showtecsystem.cad_bancos b', array('codigo' => $code))->row();
	}

	public function getInstallerByEmail($email = null)
	{
		return $this->db->get_where('showtecsystem.instaladores', array('email' => $email))->row();
	}
	public function getInstallerParameters($parameters = [])
	{
		if (empty($parameters)) {
			return null;
		}

		$this->db->where($parameters);
		return $this->db->get('showtecsystem.instaladores')->row();
	}


	public function add_service($data)
	{
		$this->db->insert('servico_op', $data);
		//return $this->db->insert_id();
	}

	public function get_contaById($id)
	{
		return $this->db->get_where('cad_contasbank', array('id' => $id))->row();
	}

	public function update_bank_account($dados_banco = [], $cpf, $installer_id)
    {
        // Verificar se os parâmetros são válidos
        if (empty($cpf) || empty($installer_id)) {
            return false;
        }

        // Atualizar cad_contasbank
        $this->db->set($dados_banco);
        $this->db->where('cpf', $cpf);
        $this->db->where('id_retorno', $installer_id);
        $this->db->where('cad_retorno', 'instalador');
        $resultado = $this->db->update('showtecsystem.cad_contasbank');

        return $resultado ? true : false;
    }

    public function update_installer_account($dados_instalador = [], $installer_id)
    {
        // Atualizar instaladores
        $this->db->set($dados_instalador);
        $this->db->where('id', $installer_id);
        $resultado = $this->db->update('showtecsystem.instaladores');

        return $resultado ? true : false;
    }

	public function salvarOP($data)
	{
		$this->db->insert('ordem_pagamento', $data);
	}

	public function get_contasById($id)
	{
		return $this->db->get_where('cad_contasbank', array('id_retorno' => $id, 'cad_retorno' => 'instalador'))->result();
	}

	public function update_conta($dados, $id)
	{
		return $this->db->where('id', $id)->update('cad_contasbank', $dados);
	}

	public function upgrade_contabank($id, $cs)
	{
		if ($cs && count($cs) > 0)
			$this->db->where_in('id', $cs)->update('cad_contasbank', array('status' => '0'));
		return $this->db->where('id', $id)->update('cad_contasbank', array('status' => '1'));
	}

	public function add_contaBank($dados, $id)
	{
		$dados['cad_retorno'] = 'instalador';
		$dados['data_cad'] = date('Y-m-d H:i:s');
		$dados['id_retorno'] = $id;
		$dados['status'] = '1';
		$this->db->where(array('id_retorno' => $id, 'cad_retorno' => 'instalador'))->update('cad_contasbank', array('status' => '0'));
		$verific = $this->db->get_where('cad_contasbank', $dados)->result();
		if (!$verific) {
			return $this->db->insert('cad_contasbank', $dados);
		} else {
			return false;
		}
	}

	public function addInstaller($dados)
	{
		if (!$this->isValidEmail($dados['email'])) {
			$this->db->insert('showtecsystem.instaladores', $dados);
			if ($this->db->affected_rows() > 0) {
				return $this->db->insert_id();
			} else {
				throw new Exception('Não foi possível gravar no banco de dados. Tente novamente.');
			}
		} else {
			return false;
		}
	}

	public function addBankAccount($dados)
	{
		$this->db->where(array('id_retorno' => $dados['id_retorno'], 'cad_retorno' => 'instalador'))->update('cad_contasbank', array('status' => '0'));
		$verific = $this->db->get_where('cad_contasbank', array('id_retorno' => $dados['id_retorno'], 'cad_retorno' => 'instalador', 'banco' => $dados['banco'], 'agencia' => $dados['agencia'], 'conta' => $dados['conta']))->result();
		if (empty($verific)) {
			return $this->db->insert('cad_contasbank', $dados);
		} else {
			return false;
		}
	}

	public function updateBankAccount($dados)
	{
		$this->db->where(array('id_retorno' => $dados['id_retorno'], 'cad_retorno' => 'instalador'))->update('cad_contasbank', array('status' => '0'));
		$this->db->insert('cad_contasbank', $dados);
	}

	public function getByIdBankAccount($id)
	{
		return $this->db->get_where('cad_contasbank', array('id' => $id))->row();
	}


	public function updateData($dados)
	{
		return $this->db->where('id', $dados['id'])->update('showtecsystem.instaladores', $dados);
	}

	public function del_servico($dados)
	{
		$this->db->where('id', $dados['id'])->update('showtecsystem.servico_op', $dados);
	}

	public function bloquearTec($data)
	{
		//pr($data['block']);die;
		if ($data['block'] == 0) {
			$block = 1;
		} else {
			$block = 0;
		}
		return $this->db->where('id', $data['id'])->update('showtecsystem.instaladores', array('block' => $block));
	}

	public function busca_id($nome_tec)
	{
		$sql = "SELECT id FROM showtecsystem.instaladores where concat(nome,' ', sobrenome) = '" . $nome_tec . "'";
		$result = $this->db->query($sql)->result()[0]->id;
		return $result;
	}

	public function get_instalador($id)
	{
		//pesquisa um instalador por um id específico.
		$this->db->where('id', $id);
		$query = $this->db->get('instaladores');
		if ($query->num_rows()) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_lista_instaladores()
	{
		$query = $this->db->get('showtecsystem.instaladores');
		return $query->result();
	}

	function getListInstallers()
	{
		$data = array();
		$sql = "SELECT id, nome FROM showtecsystem.instaladores;";
		$list = $this->db->query($sql)->result();
		foreach ($list as $key => $v)
			$data[] = $v->nome . '_' . $v->id;
		return json_encode($data);
	}
}
