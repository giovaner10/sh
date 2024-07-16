<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cliente extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ticket');
	}

	// +++++++++++++++++++++++ jerÃ´nimo gabriel init ++++++++++++++++++++++++++++
	// mÃ©todos desenvolvidos
	function getAllIdClientDay()
	{
		$dia = date('d');
		$sql = "SELECT
					id,
					fatura_consumo AS consumo
				FROM
					showtecsystem.cad_clientes
				WHERE
					status = 1;";
		//-- AND data_cadastro like '%-{$dia} %';
		return $this->db->query($sql)->result();
	}

	public function getClientesExpedicao($search, $tipoBusca, $isAtivo = true)
	{
		$this->db->select('nome, id, razao_social, cep, endereco, uf, bairro, cidade, orgao, status', 'cpf', 'cnpj')->from('showtecsystem.cad_clientes');

		if ($tipoBusca == 'cpfCnpj') {
			$this->db->where('cpf', $search)->or_where('cnpj', $search);
		} else if ($tipoBusca == 'nome') {
			$this->db->like('nome', $search);
		} else if ($tipoBusca == 'id') {
			$this->db->where('id', $search);
		}

		if ($isAtivo) {
			$this->db->where('status', 1);
		}

		$clientes = $this->db->get()->result_array();
		return $clientes;
	}

	public function getClientes($search, $tipoBusca)
	{
		if ($tipoBusca == 'cpfCnpj') {
			$clientes = $this->db->select('nome, id, razao_social, cep, endereco, uf, bairro, cidade', 'cpf', 'cnpj')->from('showtecsystem.cad_clientes')->where('cpf', $search)->or_where('cnpj', $search)->
				// where('status', 1)->
				get()->result_array();

			return $clientes;
		} else if ($tipoBusca == 'nome') {
			$clientes = $this->db->select('nome, id, razao_social, cep, endereco, uf, bairro, cidade', 'cpf', 'cnpj')->from('showtecsystem.cad_clientes')->like('nome', $search)->
				// where('status', 1)->
				get()->result_array();

			return $clientes;
		} else if ($tipoBusca == 'id') {
			$clientes = $this->db->select('nome, id, razao_social, cep, endereco, uf, bairro, cidade', 'cpf', 'cnpj')->from('showtecsystem.cad_clientes')->where('id', $search)->
				// where('status', 1)->
				get()->result_array();

			return $clientes;
		}
	}

	public function get_cliente_by_id($id)
	{
		$cliente = $this->db->select('id, cpf, cnpj')->from('showtecsystem.cad_clientes')->where('id', $id)->get()->row_array();
		return $cliente;
	}
	
	public function getDiff($id)
	{
		$sql  = 'SELECT DATEDIFF(NOW(), data_vencimento) AS diff FROM showtecsystem.cad_faturas WHERE id_cliente = ' . $id . ' AND status IN (0, 2) ORDER BY data_vencimento DESC;';
		return $this->db->query($sql)->result();
	}

	function getShow()
	{
		$sql = "SELECT cc.id, cc.nome AS cliente, cc.status, cc.data_cadastro AS cadastrado, cc.cad_site AS origem,cf.id as fatura,cc.orgao FROM showtecsystem.cad_clientes cc left join showtecsystem.cad_faturas cf on cc.id = cf.id_cliente and cf.data_vencimento <'" . date('Y-m-d', strtotime("-30 days", strtotime(date('Y-m-d')))) . "' and cf.status in (0,2) WHERE cc.informacoes = 'TRACKER' group by cc.id ORDER BY cadastrado DESC;";
		//$sql = "SELECT id, nome AS cliente, status, data_cadastro AS cadastrado, cad_site AS origem FROM showtecsystem.cad_clientes WHERE informacoes = 'TRACKER' ORDER BY cadastrado DESC;";
		$clients = $this->db->query($sql)->result();
		foreach ($clients as $key => $v) {
			$diffe = $this->getDiff($v->id);
			$v->situacao = '<a title="SituaÃ§Ã£o: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
			if (isset($diffe[0])) {
				rsort($diffe);
				if ($diffe[0]->diff >= 1) {
					$v->situacao = '<a class="blem" title="SituaÃ§Ã£o: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
				}
			}
			if ($v->status == 6)
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/desbloqueioParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Desbloqueio Inad.</a> ';
			else
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/bloqParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Block Inad.</a> ';

			switch ($v->status) {
				case 0:
					$v->status = '<span class="label label-important">Bloqueado</span>';
					break;
				case 1:
					$v->status = '<span class="label label-success">Ativo</span>';
					if ($v->fatura && $v->orgao == 'privado') {
						$v->status = '<span class="label label-important">Bloqueio AutomÃ¡tico</span>';
					}
					break;
				case 2:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 3:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 4:
					$v->status = '<span class="label label-warning">A reativar</span>';
					break;
				case 5:
					$v->status = '<span class="label label-important">Inativo</span>';
					break;
				case 6:
					$v->status = '<span class="label label-important">Bloqueio Parcial</span>';
					break;
			}
			$v->origem = $v->origem ? "<span style='color:green;'><i>Site</i></span>" : "<span style='color:blue;'><i>ShowNet</i></span>";
		}
		return json_encode($clients);
	}

	function getSimm2m()
	{
		$sql = "SELECT id, nome AS cliente, status, data_cadastro AS cadastrado, cad_site AS origem FROM showtecsystem.cad_clientes WHERE informacoes = 'SIMM2M' ORDER BY cadastrado DESC;";
		$clients = $this->db->query($sql)->result();
		foreach ($clients as $key => $v) {
			$diffe = $this->getDiff($v->id);
			$v->situacao = '<a title="SituaÃ§Ã£o: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
			if (isset($diffe[0])) {
				rsort($diffe);
				if ($diffe[0]->diff >= 1) {
					$v->situacao = '<a class="blem" title="SituaÃ§Ã£o: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
				}
			}
			if ($v->status == 6)
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/desbloqueioParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Desbloqueio Inad.</a> ';
			else
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/bloqParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Block Inad.</a> ';

			switch ($v->status) {
				case 0:
					$v->status = '<span class="label label-important">Bloqueado</span>';
					break;
				case 1:
					$v->status = '<span class="label label-success">Ativo</span>';
					break;
				case 2:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 3:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 4:
					$v->status = '<span class="label label-warning">A reativar</span>';
					break;
				case 5:
					$v->status = '<span class="label label-important">Inativo</span>';
					break;
				case 6:
					$v->status = '<span class="label label-important">Bloqueio Parcial</span>';
					break;
			}
			$v->origem = $v->origem ? "<span style='color:green;'><i>Site</i></span>" : "<span style='color:blue;'><i>ShowNet</i></span>";
		}
		return json_encode($clients);
	}

	// Busca dados de clientes SIGAMY
	function getSigamy()
	{
		$sql = "SELECT id, nome AS cliente, status, data_cadastro AS cadastrado, cad_site AS origem FROM showtecsystem.cad_clientes WHERE informacoes = 'SIGAMY' ORDER BY cadastrado DESC;";
		$clients = $this->db->query($sql)->result();
		foreach ($clients as $key => $v) {
			$diffe = $this->getDiff($v->id);
			$v->situacao = '<a title="SituaÃ§Ã£o: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
			if (isset($diffe[0])) {
				rsort($diffe);
				if ($diffe[0]->diff >= 1) {
					$v->situacao = '<a class="blem" title="SituaÃ§Ã£o: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
				}
			}
			if ($v->status == 6)
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/desbloqueioParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Desbloqueio Inad.</a> ';
			else
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/bloqParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Block Inad.</a> ';

			switch ($v->status) {
				case 0:
					$v->status = '<span class="label label-important">Bloqueado</span>';
					break;
				case 1:
					$v->status = '<span class="label label-success">Ativo</span>';
					break;
				case 2:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 3:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 4:
					$v->status = '<span class="label label-warning">A reativar</span>';
					break;
				case 5:
					$v->status = '<span class="label label-important">Inativo</span>';
					break;
				case 6:
					$v->status = '<span class="label label-important">Bloqueio Parcial</span>';
					break;
			}
			$v->origem = $v->origem ? "<span style='color:green;'><i>Site</i></span>" : "<span style='color:blue;'><i>ShowNet</i></span>";
		}
		return json_encode($clients);
	}

	function getNorio()
	{
		$sql = "SELECT id, nome AS cliente, status, data_cadastro AS cadastrado, cad_site AS origem FROM showtecsystem.cad_clientes WHERE informacoes = 'NORIO' ORDER BY cadastrado DESC;";
		$clients = $this->db->query($sql)->result();
		foreach ($clients as $key => $v) {
			$diffe = $this->getDiff($v->id);
			$v->situacao = '<a title="SituaÃ§Ã£o: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
			if (isset($diffe[0])) {
				rsort($diffe);
				if ($diffe[0]->diff >= 1) {
					$v->situacao = '<a class="blem" title="SituaÃ§Ã£o: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
				}
			}
			if ($v->status == 6)
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/desbloqueioParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Desbloqueio Inad.</a> ';
			else
				$v->administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a> <a id="blockParcial" href="' . site_url('clientes/bloqParcial') . '/' . $v->id . '" class="btn btn-warning btn-mini">Block Inad.</a> ';

			switch ($v->status) {
				case 0:
					$v->status = '<span class="label label-important">Bloqueado</span>';
					break;
				case 1:
					$v->status = '<span class="label label-success">Ativo</span>';
					break;
				case 2:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 3:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 4:
					$v->status = '<span class="label label-warning">A reativar</span>';
					break;
				case 5:
					$v->status = '<span class="label label-important">Inativo</span>';
					break;
				case 6:
					$v->status = '<span class="label label-important">Bloqueio Parcial</span>';
					break;
			}
			$v->origem = $v->origem ? "<span style='color:green;'><i>Site</i></span>" : "<span style='color:blue;'><i>ShowNet</i></span>";
		}
		return json_encode($clients);
	}

	public function lista_clientes($query = NULL)
	{
		if ($query) {
			$this->db->like('nome', $query);
			$this->db->or_like('cnpj', $query);
			$this->db->or_like('cpf', $query);
		}

		return $this->db->limit(100)->get('showtecsystem.cad_clientes')->result();
	}

	// GET CLIENTES //
	public function getAjaxListClients($start = 0, $limit = 10, $search = NULL, $draw = 1, $company = 'TRACKER')
	{
		$select = 'id, nome AS cliente, status, data_cadastro AS cadastrado';
		if ($search && is_numeric($search)) {
			$sql = $this->db->select($select)
				->where(array('informacoes' => $company, 'id' => $search))
				->order_by('cadastrado', 'DESC')
				->get('showtecsystem.cad_clientes', $limit, $start);
		} elseif ($search && is_string($search)) {
			if (strstr($search, ':')) {
				$pesquisa = substr(strstr($search, ':'), 1);
				$campo = strtolower(strstr($search, ':', true));
				if ($campo == 'cpf' || $campo == 'cnpj') {
					$sql = $this->db->select($select)
						->where(array('informacoes' => $company, $campo => $pesquisa))
						->order_by('cadastrado', 'DESC')
						->get('showtecsystem.cad_clientes', $limit, $start);
				} else {
					$sql = $this->db->select($select)
						->where('informacoes', $company)
						->like('nome', $pesquisa)
						->order_by('cadastrado', 'DESC')
						->get('showtecsystem.cad_clientes', $limit, $start);
				}
			} else {
				$sql = $this->db->select($select)
					->where('informacoes', $company)
					->like('nome', $search)
					->order_by('cadastrado', 'DESC')
					->get('showtecsystem.cad_clientes', $limit, $start);
			}
		} else {
			$sql = $this->db->select($select)
				->where('informacoes', $company)
				->order_by('cadastrado', 'DESC')
				->get('showtecsystem.cad_clientes', $limit, $start);
		}

		if ($sql->num_rows() > 0) {
			$clients = $sql->result(); # Lista de Clientes
			$data['recordsTotal'] = $this->db->where('informacoes', $company)->count_all_results('showtecsystem.cad_clientes'); # Total de registros
			$data['recordsFiltered'] = $sql->num_rows(); # Total de registros Filtrados
			$data['draw'] = $draw + 1; # Draw do datatable

			foreach ($clients as $key => $v) {
				$diffe = $this->getDiff($v->id);
				$situacao = '<a title="Situação: Adimplente"><center><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a></center> ';
				if (isset($diffe[0])) {
					rsort($diffe);
					if ($diffe[0]->diff >= 1) {
						$situacao = '<center><a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a></center> ';
					}
				}
				if ($v->status == 6) {
					$administrar = '<nobr><a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-sm btn-primary"><i class="fa fa-folder-open"></i> Abrir</a> ' .
						'<a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-sm btn-danger">Inativar</a> ' .
						'<a id="blockParcial" href="' . site_url('clientes/desbloqueioParcial') . '/' . $v->id . '" class="btn btn-warning btn-sm">Desbloqueio Inad.</a> ' .
						'<a href="" onclick="return false;" class="negPosit negPosit_' . $v->id . ' btn btn-default btn-sm" data-id_cliente="' . $v->id . '" data-acao="0" >Negativar</a></nobr>';
				} elseif ($v->status == 7) {
					$administrar = '<nobr><a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-sm btn-primary"><i class="fa fa-folder-open"></i> Abrir</a> ' .
						'<a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-sm btn-danger">Inativar</a> ' .
						'<a id="blockParcial" href="' . site_url('clientes/desbloqueioParcial') . '/' . $v->id . '" class="btn btn-warning btn-sm">Desbloqueio Inad.</a> ' .
						'<a href="" onclick="return false;" class="negPosit negPosit_' . $v->id . ' btn btn-default btn-sm" data-id_cliente="' . $v->id . '" data-acao="1" >Positivar</a></nobr>';
				} else {
					$administrar = '<nobr><a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-sm btn-primary"><i class="fa fa-folder-open"></i> Abrir</a> ' .
						'<a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-sm btn-danger">Inativar</a> ' .
						'<a id="blockParcial" href="' . site_url('clientes/bloqParcial') . '/' . $v->id . '" class="btn btn-warning btn-sm">Block Inad.</a> ' .
						'<a href="" onclick="return false;" class="negPosit negPosit_' . $v->id . ' btn btn-default btn-sm" data-id_cliente="' . $v->id . '" data-acao="0" >Negativar</a></nobr>';
				}
				switch ($v->status) {
					case 0:
						$status = '<span class="label label-danger">Bloqueado</span>';
						break;
					case 1:
						$status = '<span class="label label-success">Ativo</span>';
						break;
					case 2:
						$status = '<span class="label">Prospectado</span>';
						break;
					case 3:
						$status = '<span class="label">Prospectado</span>';
						break;
					case 4:
						$status = '<span class="label label-warning">A reativar</span>';
						break;
					case 5:
						$status = '<span class="label label-danger">Inativo</span>';
						break;
					case 6:
						$status = '<span class="label label-danger">Bloqueio Parcial</span>';
						break;
					case 7:
						$status = '<span class="label label-danger">Negativo</span>';
						break;
				}

				$origem = "<span style='color:blue;'><i>ShowNet</i></span>";

				$data['data'][] = array(
					$v->id,
					$v->cliente,
					$situacao,
					$v->cadastrado,
					$origem,
					'<div class="status_' . $v->id . '">' . $status . '</div>',
					$administrar
				);
			}
			return  $data;
		} else {
			return false;
		}
	}

	function clientePorCNPJ($cnpj, $select = '*')
	{
		$query = $this->db->select($select)
			->where('cnpj', $cnpj)
			->get('showtecsystem.cad_clientes');
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function clientePorCPF($cpf, $select = '*')
	{
		$query = $this->db->select($select)
			->where('cpf', $cpf)
			->get('showtecsystem.cad_clientes');
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	function clientePorNome($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('showtecsystem.cad_clientes');
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	function clientePorUsuario($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('showtecsystem.cad_clientes');
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	/**
	 * FunÃ§Ã£o monta retorno DataTable listagem de Embarcadores
	 **/
	function getAjaxListEmbarcadores($start = 0, $limit = 10, $search = NULL, $draw = 1)
	{
		$select = 'id, nome AS cliente, status, data_cadastro AS cadastrado';
		if ($search && is_numeric($search)) {
			$sql = $this->db->select($select)
				->where(array('informacoes' => 'EMBARCADORES', 'id' => $search))
				->order_by('cadastrado', 'DESC')
				->get('showtecsystem.cad_clientes', $limit, $start);
		} elseif ($search && is_string($search)) {
			if (strstr($search, ':')) {
				$pesquisa = substr(strstr($search, ':'), 1);
				$campo = strtolower(strstr($search, ':', true));
				if ($campo == 'cpf' || $campo == 'cnpj') {
					$sql = $this->db->select($select)
						->where(array('informacoes' => 'EMBARCADORES', $campo => $pesquisa))
						->order_by('cadastrado', 'DESC')
						->get('showtecsystem.cad_clientes', $limit, $start);
				} else {
					$sql =  $this->db->select($select)
						->where('informacoes', 'EMBARCADORES')
						->like('nome', $search)
						->order_by('cadastrado', 'DESC')
						->get('showtecsystem.cad_clientes', $limit, $start);
				}
			} else {
				$sql =  $this->db->select($select)
					->where('informacoes', 'EMBARCADORES')
					->like('nome', $search)
					->order_by('cadastrado', 'DESC')
					->get('showtecsystem.cad_clientes', $limit, $start);
			}
		} else {
			$sql =  $this->db->select($select)
				->where('informacoes', 'EMBARCADORES')
				->order_by('cadastrado', 'DESC')
				->get('showtecsystem.cad_clientes', $limit, $start);
		}

		if ($sql->num_rows() > 0) {
			$clients = $sql->result(); # Lista de Clientes

			$data['recordsTotal'] = $this->db->where('informacoes', 'EMBARCADORES')->count_all_results('showtecsystem.cad_clientes'); # Total de registros
			$data['recordsFiltered'] = $sql->num_rows(); # Total de registros Filtrados
			$data['draw'] = $draw + 1; # Draw do datatable

			foreach ($clients as $key => $v) {
				switch ($v->status) {
					case 0:
						$status = '<span class="label label-important">Bloqueado</span>';
						break;
					case 1:
						$status = '<span class="label label-success">Ativo</span>';
						break;
					case 2:
						$status = '<span class="label">Prospectado</span>';
						break;
					case 3:
						$status = '<span class="label label-success">Ativo</span>';
						break;
					case 4:
						$status = '<span class="label label-warning">A reativar</span>';
						break;
					case 5:
						$status = '<span class="label label-important">Inativo</span>';
						break;
					case 6:
						$status = '<span class="label label-important">Bloqueio Parcial</span>';
						break;
				}

				$administrar = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a>';

				$data['data'][] = array(
					$v->id,
					$v->cliente,
					$v->cadastrado,
					$status,
					$administrar
				);
			}
			return  $data;
		} else {
			return false;
		}
	}

	// END GET CLIENTES OMNILINK //

	function getEua()
	{
		$sql = "SELECT id, nome AS client, status, data_cadastro AS registered, cad_site AS source FROM showtecsystem.cad_clientes WHERE informacoes = 'EUA' ORDER BY registered DESC;";
		$clients = $this->db->query($sql)->result();
		foreach ($clients as $key => $v) {
			$diffe = $this->getDiff($v->id);
			$v->situation = '<a title="SituaÃ§Ã£o: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
			if (isset($diffe[0])) {
				rsort($diffe);
				if ($diffe[0]->diff >= 1) {
					$v->situation = '<a class="blem" title="SituaÃ§Ã£o: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
				}
			}
			switch ($v->status) {
				case 0:
					$v->status = '<span class="label label-important">Bloqueado</span>';
					break;
				case 1:
					$v->status = '<span class="label label-success">Ativo</span>';
					break;
				case 2:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 3:
					$v->status = '<span class="label">Prospectado</span>';
					break;
				case 4:
					$v->status = '<span class="label label-warning">A reativar</span>';
					break;
				case 5:
					$v->status = '<span class="label label-important">Inativo</span>';
					break;
			}
			$v->source = $v->source ? "<span style='color:green;'><i>Site</i></span>" : "<span style='color:blue;'><i>ShowNet</i></span>";
			$v->manage = '<a href="' . base_url() . 'index.php/clientes/view/' . $v->id . '" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i> Abrir</a> <a href="' . base_url() . 'index.php/clientes/inativar_clie/' . $v->id . '" class="btn btn-mini btn-danger">Inativar</a>';
		}
		return json_encode($clients);
	}
	// ++++++++++++ end +++++++++++++++

	public function listar($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC', $select = '*', $like = NULL)
	{
		$this->db->select($select);

		if (isset($like)) {
			$this->db->like($like);
		}

		if (isset($where) && count($where) > 0) {
			if (isset($where['status'])) {
				if (is_array($where['status'])) {
					$where_in = $where['status'];
				} elseif ($where['status'] == 'all') {
					$where_in = array(0, 1, 2, 3, 4, 5, 6);
				} elseif ($where['status'] == 1) {
					$where_in = array(1, 2);
				} else {
					$where['ctr.status'] = $where['status'];
					$where_in = false;
				}

				if (is_array($where_in))
					$this->db->where_in('status', $where_in);
			} else {
				$this->db->where($where);
			}
		}

		$this->db->order_by($campo_ordem, $ordem);
		$query = $this->db->get('showtecsystem.cad_clientes', $limite, $paginacao);

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function listarUsers($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id_cliente', $ordem = 'DESC', $select = '*', $like = NULL)
	{
		$this->db->select($select);
		$this->db->like($like);

		return $this->db->where($where)->order_by($campo_ordem, $ordem)->get('usuario_gestor', $limite, $paginacao)->result();
	}

	/*
    * PESQUISA CLIENTES COM FILTRO - SELECT2 AJAX
    */
	public function listarClientesFilter($search)
	{
		if ($search && is_numeric($search)) { # Se existir e for nÃºmerico, verifica pelo ID
			return $this->db->like('id', $search)->limit(50)->get('cad_clientes')->result();
		} elseif ($search && is_string($search)) { # Se existir e for string, verifica pelo NOME
			return $this->db->like('nome', $search)->limit(50)->get('cad_clientes')->result();
		} else { # Se nÃ£o for verdadeiro, retorna array vazio
			return array();
		}
	}

	public function autocomplet_ajax($like)
	{
		return $this->db->select('nome')->like('nome', $like)->get('cad_clientes')->result();
	}

	public function busca_cliente($where)
	{
		$query = $this->db->select('id, nome')->where($where)->order_by('id', 'DESC')->get('cad_clientes');

		if ($query->num_rows() > 0)
			return $query->result();
		return false;
	}

	public function busca_id($nome_cliente)
	{
		$veiculo =  $this->db->select('id')->where('nome ', $nome_cliente)->get('showtecsystem.cad_clientes')->result()[0]->id;

		return $veiculo;
	}

	public function all($select = '*')
	{
		$this->db->select($select);
		$query = $this->db->get('showtecsystem.cad_clientes');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function total_lista($where = array())
	{
		if (is_array($where) && !empty($where)) {
			$total = $this->db->where($where)
				->from('showtecsystem.cad_clientes')
				->count_all_results();
		} else {
			$total = $this->db->count_all('showtecsystem.cad_clientes');
		}

		return $total;
	}

	public function get($where)
	{
		return $this->db->get_where('cad_clientes', $where)->row();
	}

	public function getContratosAtivos($id_cliente)
	{

		$this->db->where('id_cliente', $id_cliente);
		$this->db->where('status', 'ativo');
		$query = $this->db->get('showtecsystem.contratos_veiculos');

		return $query->result();
	}

	function getPaisCliente($id_cliente)
	{
		return $this->db->select('informacoes')->get_where('cad_clientes', array('id' => $id_cliente))->row();
	}

	public function get_clientes($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('showtecsystem.cad_clientes');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function get_clientes_cartoes($id)
	{
		$this->db->where('cliente_id', $id);
		$query = $this->db->get('showtecsystem.clientes_cartoes');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function get_clientes_enderecos($id)
	{
		$this->db->where('cliente_id', $id);
		$query = $this->db->get('showtecsystem.clientes_enderecos');
		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	public function get_clientes_emails($id, $select = '*',  $setor = false)
	{

		if ($setor != false) {
			$query = $this->db->select($select)
				->where(array('cliente_id' => $id, 'setor' => $setor, 'lixo' => '0'))
				->get('showtecsystem.clientes_emails');

			if ($query->num_rows()) {
				return $query->row();
			}
		} else {
			$query = $this->db->select($select)
				->where(array('cliente_id' => $id, 'lixo' => '0'))
				->get('showtecsystem.clientes_emails');

			if ($query->num_rows()) {
				return $query->result();
			}
		}

		return false;
	}

	public function get_clientes_telefones($id, $select = '*')
	{
		$query = $this->db->select($select)
			->where(array('cliente_id' => $id, 'lixo' => '0'))
			->get('showtecsystem.clientes_telefones');

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	public function atualizar($id_cliente, $dados)
	{
		$this->db->update('cad_clientes', $dados, array('id' => $id_cliente));
		if ($this->db->affected_rows() > 0)
			return true;
		return false;
	}

	public function atualiza_status_cliente($id_cliente, $status)
	{
		$insert = array('status' => $status);
		$this->db->where('id', $id_cliente);
		$update = $this->db->update('showtecsystem.cad_clientes', $insert);

		return $update;
	}

	public function get_usuarios($id)
	{
		$query = $this->db
			->select('code, usuario')
			->where('id_cliente', $id)
			->get('showtecsystem.usuario_gestor');

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function verificaStatusClie($id_cliente, $status)
	{
		$query = $this->db->get_where('showtecsystem.cad_clientes', array('id' => $id_cliente))->result();

		if ($query) {
			if ($query[0]->status != $status) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	public function get_veiculos_gestor($coluna, $palavra, $paginacao = 0, $limite = 9999999)
	{
		$veiculos = false;
		if ($coluna && $palavra) {
			// Construct the query to fetch the row with the highest "Código"
			$this->db->select('code, placa, id_usuario, code_cliente, serial, veiculo');
			$this->db->where("$coluna LIKE '%$palavra%'");
			$this->db->order_by('code', 'DESC');
			$this->db->limit($limite, $paginacao);
			$query = $this->db->get('systems.cadastro_veiculo');
		} else {
			// Fetch all records if no search criteria is provided
			$this->db->select('code, placa, id_usuario, code_cliente, serial, veiculo');
			$this->db->order_by('code', 'DESC');
			$this->db->limit($limite, $paginacao);
			$query = $this->db->get('systems.cadastro_veiculo');
		}

		if ($query->num_rows() > 0) {
			$veiculos = $query->result();
			foreach ($veiculos as $indice => $veiculo) {
				if (isset($veiculo->code_cliente) && 	($veiculo->code_cliente) && $veiculo->code_cliente > 0) {
					$id_cliente = $veiculo->code_cliente;
				} else {
					$id_cliente = $veiculo->id_usuario;
				}

				$this->db->select('nome');
				$this->db->where('id', $id_cliente);
				$this->db->limit(1);
				$query_cliente = $this->db->get('showtecsystem.cad_clientes');
				if ($query_cliente->num_rows()) {
					$veiculo->cliente = $query_cliente->row()->nome;
				} else {
					$veiculo->cliente = '';
				}
			}
		}
		return $veiculos;
	}

	public function get_veiculos_gestor_new($coluna, $palavra, $paginacao = 0, $limite = 9999999) {
		$this->db->select('cv.code, cv.placa, cv.id_usuario, cv.code_cliente, cv.serial, cv.veiculo, 
                       COALESCE(cc1.nome, cc2.nome, "") as cliente', false);
		$this->db->from('systems.cadastro_veiculo cv');
		$this->db->join('showtecsystem.cad_clientes cc1', 'cc1.id = cv.code_cliente AND cv.code_cliente > 0', 'left');
		$this->db->join('showtecsystem.cad_clientes cc2', 'cc2.id = cv.id_usuario AND cv.code_cliente = 0', 'left');
		
		if ($coluna && $palavra) {
			if ($coluna == 'cliente') {
				$this->db->where('(cc1.nome LIKE "%' . $this->db->escape_like_str($palavra) . '%" OR cc2.nome LIKE "%' . $this->db->escape_like_str($palavra) . '%")');
			} else if ($coluna == 'code') {
				$this->db->where('code', $palavra);
			} else {
				$this->db->like($coluna, $palavra);
			}
		}
		
		$this->db->order_by('cv.code', 'DESC');
		$this->db->limit($limite, $paginacao);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function count_veiculos_gestor($coluna, $palavra)
	{
		$this->db->select('cv.code, cv.placa, cv.id_usuario, cv.code_cliente, cv.serial, cv.veiculo, 
                       COALESCE(cc1.nome, cc2.nome, "") as cliente', false);
		$this->db->from('systems.cadastro_veiculo cv');
		$this->db->join('showtecsystem.cad_clientes cc1', 'cc1.id = cv.code_cliente AND cv.code_cliente > 0', 'left');
		$this->db->join('showtecsystem.cad_clientes cc2', 'cc2.id = cv.id_usuario AND cv.code_cliente = 0', 'left');

		if ($coluna && $palavra) {
			if ($coluna == 'cliente') {
				$this->db->where('(cc1.nome LIKE "%' . $this->db->escape_like_str($palavra) . '%" OR cc2.nome LIKE "%' . $this->db->escape_like_str($palavra) . '%")');
			} else if ($coluna == 'code') {
				$this->db->where('code', $palavra);
			} else {
				$this->db->like($coluna, $palavra);
			}
		}
		
		$count = $this->db->count_all_results();
		
		return $count;
	}

	public function count_veiculos_gestor_old($coluna, $palavra)
	{
		$veiculos = false;
		if ($coluna && $palavra) {
			// Construct the query to fetch the row with the highest "Código"
			$this->db->select('code, placa, id_usuario, code_cliente, serial, veiculo');
			$this->db->where("$coluna LIKE '%$palavra%'");
			$this->db->order_by('code', 'DESC');
			$query = $this->db->get('systems.cadastro_veiculo');
		} else {
			// Fetch all records if no search criteria is provided
			$this->db->select('code, placa, id_usuario, code_cliente, serial, veiculo');
			$this->db->order_by('code', 'DESC');
			$query = $this->db->get('systems.cadastro_veiculo');
		}
		
		return $query->num_rows();
	}

	public function insertArray($dados, $id_estoque, $tipo)
	{
		$inserts = $atualizacoes = 0;
		$com_cadastros = $this->db->where_in('serial', $dados)->get('showtecsystem.cad_suprimentos')->result_array();
		$sem_cadastros = array_diff($dados, array_column($com_cadastros, 'serial'));

		switch ($tipo) {
			case 2:
				$descricao = 'PowerBank';
				break;
			case 3:
				$descricao = 'Carregador';
				break;
			default:
				$descricao = 'Cinta';
				break;
		}

		if (!empty($com_cadastros))
			$vinculos = $this->db->where_in('id_suprimento', array_column($com_cadastros, 'id'))->get('showtecsystem.suprimento_x_estoque')->result_array();
		else
			$vinculos = array();

		foreach ($com_cadastros as $s) {
			if ($this->db->where(array('id_suprimento' => $s['id'], 'id_contrato' => '293895'))->count_all_results('showtecsystem.contratos_suprimentos') > 0)
				$this->db->update('showtecsystem.contratos_suprimentos', array('status' => 'ativo', 'vinculacao' => 'disponivel'), array('id_suprimento' => $s['id'], 'id_contrato' => '293895'));
			else
				$this->db->insert('showtecsystem.contratos_suprimentos', array('id_contrato' => '293895', 'id_cliente' => '148168', 'id_suprimento' => $s['id'], 'data_cadastro' => date('Y-m-d H:i:s'), 'vinculacao' => 'disponivel'));

			if (in_array($s['id'], array_column($vinculos, 'id_suprimento'))) {
				$this->db->update('showtecsystem.suprimento_x_estoque', array('id_estoque' => $id_estoque), array('id_suprimento' => $s['id']));
				$atualizacoes++;
			} else {
				$this->db->insert('showtecsystem.suprimento_x_estoque', array('id_estoque' => $id_estoque, 'id_suprimento' => $s['id']));
				$inserts++;
			}
		}

		foreach ($sem_cadastros as $d) {
			$this->db->insert('showtecsystem.cad_suprimentos', array('serial' => $d, 'descricao' => $descricao, 'tipo' => $tipo, 'status' => 'ativo', 'data_cadastro' => date('Y-m-d H:i:s')));

			if ($id = $this->db->insert_id()) {
				$this->db->insert('showtecsystem.contratos_suprimentos', array('id_contrato' => '293895', 'id_cliente' => '148168', 'id_suprimento' => $d, 'status' => 'ativo', 'data_cadastro' => date('Y-m-d H:i:s'), 'vinculacao' => 'disponivel'));

				$this->db->insert('showtecsystem.suprimento_x_estoque', array('id_estoque' => $id_estoque, 'id_suprimento' => $id));
				$inserts++;
			}
		}

		return array('at' => $atualizacoes, 'is' => $inserts);
	}

	public function insertTrz($dados, $id_estoque)
	{
		$atualizacoes = $inserts = 0;
		$com_cadastros = $this->db->where_in('serial', $dados)->get('showtecsystem.cad_equipamentos')->result_array();
		$sem_cadastros = array_diff($dados, array_column($com_cadastros, 'serial'));
		$vinculos = $this->db->where_in('equipamento', array_column($com_cadastros, 'serial'))->get('showtecsystem.tornozeleira_x_estoque')->result_array();

		foreach ($com_cadastros as $s) {
			if (in_array($s['serial'], array_column($vinculos, 'equipamento'))) {
				if ($this->db->update('showtecsystem.tornozeleira_x_estoque', array('id_estoque' => $id_estoque), array('equipamento' => $s['serial'])))
					$atualizacoes++;
			} else {
				if ($this->db->insert('showtecsystem.tornozeleira_x_estoque', array('id_estoque' => $id_estoque, 'equipamento' => $s['serial'])))
					$inserts++;
			}
		}

		foreach ($sem_cadastros as $d) {
			die(pr($d));
			$this->db->insert(
				'showtecsystem.cad_equipamentos',
				array(
					'serial' => $d['serial'],
					'marca' => 'ZATIX',
					'modelo' => 'ANKL',
					'status' => '1',
					'data_cadastro' => date('Y-m-d H:i:s')
				)
			);

			$this->db->insert(
				'showtecsystem.contratos_veiculos',
				array(
					'id_contrato' => '293895',
					'id_cliente' => '148168',
					'equipamento' => $d['serial'],
					'status' => 'ativo',
					'data_cadastro' => date('Y-m-d H:i:s'),
					'uso_tornozeleira' => 'disponivel'
				)
			);

			if ($this->db->insert('showtecsystem.tornozeleira_x_estoque', array('id_estoque' => $id_estoque, 'equipamento' => $d['serial'])))
				$inserts++;
		}

		return array('at' => $atualizacoes, 'is' => $inserts);
	}

	public function get_total_veiculos_gestor($coluna, $palavra)
	{
		if ($coluna && is_string($coluna)) {
			$this->db->where($coluna, $palavra);
		}

		$this->db->group_by('serial');
		$this->db->group_by('placa');
		$query = $this->db->get('systems.cadastro_veiculo');

		if ($query->num_rows() > 0)
			return $query->num_rows();
	}

	public function get_nameClient($id_Client)
	{
		return $this->db->select('nome')->where('id', $id_Client)->get('showtecsystem.cad_clientes')->result()[0]->nome;
	}

	public function get_lista_clientes()
	{
		$query = $this->db->get('showtecsystem.cad_clientes');
		return $query->result();
	}

	public function list_consum()
	{
		$query = $this->db
			->where("(status = 1 OR status = 2)")
			->where('consumo_fatura', 1)
			->get('showtecsystem.contratos');
		return $query->num_rows() ? $query->result() : [];
	}

	public function list_by_day()
	{
		$query = $this->db->where(array('cli.status' => 2, 'cli.consumo_fatura' => 1))->get('showtecsystem.contratos as cli');
		return $query->num_rows() ? $query->result() : [];
	}

	public function getClienteVendedor($id)
	{
		$query = $this->db->select('nome')->where('id', $id)->get('showtecsystem.usuario');
		return $query->num_rows() ? $query->result()[0]->nome : '';
	}

	public function get_IdName_clientesAll($status = array(1, 2))
	{
		$this->db->select('id, nome');
		$this->db->where('status', 1);
		$clientes = $this->db->get('showtecsystem.cad_clientes')->result();

		// RETIRA CLIENTES SEM CONTRATOS
		$i = 0;
		foreach ($clientes as $cliente) {
			if ($status == 'todos') $status = array(1, 2);
			// BUSCA CONTRATO ATIVO
			$this->db->where('id_cliente', $cliente->id);
			$this->db->where_in('status', $status);
			$result = $this->db->count_all_results('showtecsystem.contratos');

			if ($result == 0) {
				unset($clientes[$i]);
				$i++;
			} else {
				$i++;
			}
		}

		return $clientes;
	}

	public function getIdName_cliente($name_cliente, $status = array(1, 2))
	{
		$this->db->select('clie.id, clie.nome');
		$this->db->from('showtecsystem.cad_clientes as clie');
		$this->db->join('showtecsystem.contratos as cont', 'clie.id = cont.id_cliente', 'INNER');
		$this->db->where('clie.nome', $name_cliente);
		$this->db->where_in('cont.status', $status);
		$this->db->group_by('clie.id');
		$query = $this->db->get()->result();

		return $query;
	}

	public function getTaxUs($id_cliente)
	{
		$this->db->where('id', $id_cliente);
		$cliente = $this->db->get('showtecsystem.cad_clientes')->row();
		if ($cliente->informacoes == "EUA") {
			$this->db->where('estado', $cliente->uf);
			$this->db->where('cidade', $cliente->cidade);
			$this->db->order_by('data', 'desc');
			$taxa = $this->db->get('systems.tax_usa');
			if ($taxa->num_rows() > 0) {
				$tax = $taxa->row();
				return $tax->taxa;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	public function insert_temp($insert)
	{
		return $this->db->insert_batch('showtecsystem.cad_equipamentos', $insert);
	}

	/**
	 * Consulta o nome do cliente por placa
	 * @param $board
	 * @return String
	 * @author Matthaus Nawan
	 */
	public function ClientNameByPlate($board)
	{

		$this->db->select('cliente.id, cliente.nome');
		$this->db->from('showtecsystem.cad_clientes as cliente');
		$this->db->join('showtecsystem.contratos_veiculos as contrato', 'contrato.id_cliente = cliente.id', 'INNER');
		$this->db->where('contrato.placa', $board);

		$query = $this->db->get()->result();
		if ($query) {
			return $query[0]->nome;
		}
		return 'NÃ£o Informado';
	}


	/**
	 * Consulta centrais do cliente
	 * @param $cnpj
	 * @return Array
	 * @author Matthaus Nawan
	 */
	public function CentraisByCnpj($cnpj)
	{

		$this->db->select('centrais.ip,centrais.porta,centrais.nome');
		$this->db->from('showtecsystem.cadastro_centrais as centrais');
		$this->db->where('centrais.cnpj', $cnpj);

		$query = $this->db->get()->result();
		if ($query) {
			return $query;
		}
		return;
	}

	// GET SECRETARIAS DO CLIENTE //
	function getAjaxListSecretaria($id_cliente)
	{
		$query = $this->db->select('*')
			->where('id_cliente', $id_cliente)
			->order_by('id', 'DESC')
			->get('showtecsystem.cadastro_grupo');

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	/*
	 * CADASTRA GRUPO NÃƒO MASTER
	 */
	public function add_secretaria($nome, $id_cliente)
	{
		// VERIFICA SE JÃ� EXISTE GRUPO P/ O CLIENTE
		$this->db->where(array('nome' => $nome, 'id_cliente' => $id_cliente));
		$query = $this->db->count_all_results('showtecsystem.cadastro_grupo');

		if (!$query && $query == 0) {
			// GRAVA GRUPO PARA O CLIENTE
			$dados = array(
				'nome' => $nome,
				'id_cliente' => $id_cliente,
				'status' => 1
			);

			return $this->db->insert('showtecsystem.cadastro_grupo', $dados);
		}

		return FALSE;
	}

	public function get_centrais()
	{
		$this->db->select('centrais.ip,centrais.porta,centrais.nome, centrais.id');
		$this->db->from('showtecsystem.cadastro_centrais as centrais');

		$query = $this->db->get()->result();
		if ($query) {
			return $query;
		}
		return false;
	}

	public function cliente_central($id_cliente, $id_central)
	{
		$query = $this->db->insert('showtecsystem.cliente_x_central', array('id_cliente' => $id_cliente, 'id_central' => $id_central, 'status' => 1));
		return $query;
	}

	public function log_cliente_central($array = [])
	{
		if (is_array($array) && !empty($array)) {
			$this->db->insert('showtecsystem.log_cliente_centrais', $array);
		}
	}

	public function verificar_cliente_central($id_cliente, $id_central)
	{
		$query = $this->db->get_where('showtecsystem.cliente_x_central', array('id_cliente' => $id_cliente, 'id_central' => $id_central))->num_rows();
		if ($query > 0) {
			return true;
		} else
			return false;
	}

	public function get_centrais_cliente($id_cliente)
	{
		$this->db->select('central.nome, central.ip, central.porta, cc.status, cc.id');
		$this->db->from('showtecsystem.cadastro_centrais as central');
		$this->db->join('showtecsystem.cliente_x_central as cc', 'cc.id_central = central.id');
		$this->db->where('cc.id_cliente', $id_cliente);
		$query = $this->db->get()->result();
		return $query;
	}

	public function desativar_central($id)
	{
		$this->db->where('id', $id);
		$retorno = $this->db->update('showtecsystem.cliente_x_central', array('status' => 0), array('id' => $id));
		return $retorno;
	}

	public function getRegistroVinculoCentral($id)
	{
		return $this->db->get_where('showtecsystem.cliente_x_central', ['id' => $id])->row();
	}

	public function ativar_central($id)
	{
		$this->db->where('id', $id);
		$retorno = $this->db->update('showtecsystem.cliente_x_central', array('status' => 1), array('id' => $id));
		return $retorno;
	}

	public function get_clientes_and_contratos($select, $where, $order = 'asc', $campo = 'clie.nome')
	{
		$this->db->select(
			$select .
				', cont.tipo_proposta as faturamento' .
				', cont.valor_mensal as valor_unit' .
				', cont.quantidade_veiculos * cont.valor_mensal valor_contratado' .
				', COUNT(cv.id_contrato) * cont.valor_mensal as valor_total' .
				', COUNT(cv.id_contrato) as num_veiculos_ativos' .
				', cont.quantidade_veiculos as num_veiculos_contrato'
		);
		$this->db->join('showtecsystem.contratos as cont', 'clie.id = cont.id_cliente', 'INNER');
		$this->db->join('showtecsystem.contratos_veiculos as cv', 'cv.id_contrato = cont.id AND cv.status = \'ativo\'', 'LEFT');
		$this->db->where($where);
		// $this->db->where_in('cont.status', array(1,2));
		$this->db->group_by('cont.id'); // Agrupa por contrato para contar o número de veículos ativos em cada contrato
		$this->db->order_by($campo, $order);

		$query = $this->db->get('showtecsystem.cad_clientes as clie');
		if ($query->num_rows()) {
			return $query->result();
		}
		return [];
	}

	public function digit_negativa_positiva($id_cliente, $descricao, $diretorio, $nome_arquivo, $acao, $tipo)
	{
		$dados = array(
			'id_usuario' => $this->auth->get_login('admin', 'user'),
			'tipo' => $tipo,
			'descricao' => $descricao,
			'acao' => $acao,
			'nome_arquivos' => $nome_arquivo,
			'caminho' => $diretorio,
			'link' => base_url('uploads/clientes/' . $nome_arquivo),
			'id_cliente' => $id_cliente

		);
		return $this->db->insert('systems.upload_arquivos', $dados);
	}

	public function get_arqui_clientes($id_cliente, $tipo)
	{
		$this->db->select('id, id_usuario, data_cadastro, descricao, caminho, nome_arquivos, acao, link');
		$this->db->where('id_cliente', $id_cliente);
		$this->db->where('tipo', $tipo);
		$query = $this->db->get('systems.upload_arquivos');
		if ($query->num_rows())
			return $query->result();

		return false;
	}

	//INSERE O CLIENTE NO BANCO
	public function insertCliente($dados)
	{
		$insert = $this->db->insert('showtecsystem.cad_clientes', $dados);
		return $this->db->insert_id();
	}

	/*
	* RETORNA AS INFORMACOES DOS CLIENTES
	*/
	//$ids_clientes eh array de id de clientes
	public function getClientesPorIds($select, $ids_clientes)
	{
		$query = $this->db->select($select)->where_in('id', $ids_clientes)->get('showtecsystem.cad_clientes');
		return $query->num_rows() > 0 ? $query->result() : false;
	}

	/*
    * GET EQUIPAMENTOS SERVE-SIDE
    */
	public function getBaseClientesServerSide($select = '*', $where = array(), $start = 0, $limit = 999999, $draw = 1, $filtros, $qtdTotal = false)
	{
		$colunas = array('id', 'serial', 'modelo', 'data_cadastro');

		$this->db->select($select);

		if ($filtros['cliente'] !== 'todos') $this->db->where('cli.id', $filtros['cliente']);
		if ($filtros['orgao'] !== 'todos') $this->db->where('cli.orgao', $filtros['orgao']);
		if ($filtros['empresa'] !== 'todas') $this->db->where('cli.informacoes', $filtros['empresa']);

		$this->db->where($where);
		$this->db->order_by('cli.id', 'DESC');

		if ($filtros['tipo_proposta'] == 'veiculos') {
			$this->db->join('showtecsystem.cad_clientes as cli', 'cli.id = veic.id_usuario', 'LEFT');
			$this->db->join('showtecsystem.contratos_veiculos as con_veic', 'con_veic.placa = veic.placa and con_veic.id_cliente = veic.id_usuario', 'LEFT');

			if ($qtdTotal) $query = $this->db->count_all_results('systems.cadastro_veiculo as veic');
			else $query = $this->db->get('systems.cadastro_veiculo as veic', $limit, $start);
		} elseif ($filtros['tipo_proposta'] == 'tornozeleiras') {
			$this->db->join('showtecsystem.cad_clientes as cli', 'cli.id = tor.id_cliente', 'LEFT');

			if ($qtdTotal) $query = $this->db->count_all_results('showtecsystem.contratos_veiculos as tor');
			else $query = $this->db->get('showtecsystem.contratos_veiculos as tor', $limit, $start);
		} elseif ($filtros['tipo_proposta'] == 'chips') {
			$this->db->join('showtecsystem.cad_clientes as cli', 'cli.id = chip.id_cliente_sim2m', 'LEFT');

			if ($qtdTotal) $query = $this->db->count_all_results('showtecsystem.cad_chips as chip');
			else $query = $this->db->get('showtecsystem.cad_chips as chip', $limit, $start);
		} elseif ($filtros['tipo_proposta'] == 'iscas') {
			$this->db->join('showtecsystem.cad_clientes as cli', 'cli.id = isc.id_cliente', 'LEFT');

			if ($qtdTotal) $query = $this->db->count_all_results('showtecsystem.cad_iscas as isc');
			else $query = $this->db->get('showtecsystem.cad_iscas as isc', $limit, $start);
		}

		return $query;
	}

	/*
    * LISTA EVENTOS PANICOS
    */
	public function listBaseClientesServerSide($select = '*', $where = array(), $start = 0, $limit = 999999, $draw = 1, $filtros)
	{
		$dados = array();
		$queryQtdTotal = $this->getBaseClientesServerSide('*', $where, $start, $limit, $draw, $filtros, true);

		if ($queryQtdTotal > 0) {
			if ($filtros['export']) {
				$limit = $queryQtdTotal;
			}
			$query = $this->getBaseClientesServerSide($select, $where, $start, $limit, $draw, $filtros);
			$dados['itens'] = $query->result(); # Lista de eventos
			$dados['recordsTotal'] = $queryQtdTotal; # Total de registros
			$dados['recordsFiltered'] = $dados['recordsTotal']; # atribui o mesmo valor do recordsTotal ao recordsFiltered para ter todas as paginas na datatable
			$dados['draw'] = $draw++; # Draw do datatable

			return $dados;
		}

		return false;
	}


	/*
    * LISTA EVENTOS PANICOS
    */
	public function listBaseClientesSintetizado($select = '*', $where = array(), $start = 0, $limit = 999999, $draw = 1, $filtros)
	{
		$dados = array();
		$queryQtdTotal = $this->getBaseClientesSintetizado($select, $where, $start, $limit, $draw, $filtros, true);
		$queryQtdTotal = $queryQtdTotal->num_rows;

		if ($queryQtdTotal > 0) {
			if ($filtros['export']) {
				$limit = $queryQtdTotal;
			}
			$query = $this->getBaseClientesSintetizado($select, $where, $start, $limit, $draw, $filtros);
			$dados['itens'] = $query->result(); # Lista de eventos
			$dados['recordsTotal'] = $queryQtdTotal; # Total de registros
			$dados['recordsFiltered'] = $dados['recordsTotal']; # atribui o mesmo valor do recordsTotal ao recordsFiltered para ter todas as paginas na datatable
			$dados['draw'] = $draw++; # Draw do datatable

			return $dados;
		}

		return false;
	}

	/*
    * GET EQUIPAMENTOS SERVE-SIDE
    */
	public function getBaseClientesSintetizado($select = '*', $where = array(), $start = 0, $limit = 999999, $draw = 1, $filtros, $qtdTotal = false)
	{
		$this->db->select($select);

		if ($filtros['cliente'] !== 'todos') $this->db->where('cli.id', $filtros['cliente']);
		if ($filtros['orgao'] !== 'todos') $this->db->where('cli.orgao', $filtros['orgao']);
		if ($filtros['empresa'] !== 'todas') $this->db->where('cli.informacoes', $filtros['empresa']);

		$this->db->where($where);
		$this->db->order_by('cli.id', 'DESC');

		$this->db->join('showtecsystem.contratos as con', 'con.id_cliente = cli.id');
		$this->db->join('showtecsystem.contratos_veiculos as con_veic', 'con_veic.id_cliente = cli.id', 'LEFT');
		$this->db->join('systems.cadastro_veiculo as veic', 'con_veic.placa = veic.placa and con_veic.id_cliente = veic.id_usuario', 'LEFT');

		$this->db->group_by("cli.id");

		if ($qtdTotal) $query = $this->db->get('showtecsystem.cad_clientes as cli');
		else $query = $this->db->get('showtecsystem.cad_clientes as cli', $limit, $start);

		return $query;
	}

	/*
	* LISTA OS PRODUTOS DOS 'IDS DE PRODUTOS' PASSADOS POR PARAMETRO 
	*/
	public function getProdutosByIds($select = '*', $id_produtos)
	{
		$this->db->select($select);
		$this->db->where_in('id', $id_produtos);
		$query = $this->db->get('showtecsystem.cad_produtos');
		return $query->num_rows() > 0 ? $query->result() : false;
	}

	/**
	 * @author Felipe Libório
	 * 
	 * Grava um novo contato relacionado a um cliente cadastrado no CRM
	 * 
	 * Pelo menos um meio de contato deve ser informado (telefone ou email)
	 * 
	 * $documento_cliente CPF ou CNPJ do cliente, apenas números
	 * $nome Nome do contato
	 * $funcao Função do contato
	 * [$email] E-mail do contato
	 * [$telefone] Telefone do contato
	 */
	public function novo_contato_associado($documento_cliente, $nome, $funcao, $email, $telefone)
	{
		if (!is_string($documento_cliente) || !strlen($documento_cliente)) {
			throw new Exception('Documento do cliente não informado');
		}

		if (strlen($documento_cliente) == 11) {
			if (!validar_cpf($documento_cliente)) throw new Exception('CPF inválido');
		} else if (strlen($documento_cliente) == 14) {
			if (!validar_cnpj($documento_cliente)) throw new Exception('CNPJ inválido');
		} else {
			throw new Exception('Documento inválido');
		}

		if (!is_string($nome) || !strlen($nome)) throw new Exception('Nome não informado');
		if (!is_string($funcao) || !strlen($funcao)) throw new Exception('Função não informada');

		if (
			(!is_string($email) || !strlen($email))
			&& (!is_string($telefone) || !strlen($telefone))
		) throw new Exception('Ao menos uma forma de contato deve ser informada');

		if (is_string($telefone) && strlen($telefone)) {
			if (strlen($telefone) < 10 || strlen($telefone) > 11) {
				throw new Exception('Telefone deve ter dez ou onze caracteres');
			}
		}

		$this->db->trans_start();
		$this->db->insert('showtecsystem.contatos_clientes_crm', [
			'documento_cliente'	=> $documento_cliente,
			'nome'				=> $nome,
			'funcao'			=> $funcao,
		]);

		if ($this->db->affected_rows() == 0) {
			$this->db->trans_rollback();
			throw new Exception('Falha ao inserir');
		}
		$id = $this->db->insert_id();

		if (strlen($email)) {
			$this->db->insert('showtecsystem.emails_contatos_clientes_crm', [
				'id_contatos_clientes_crm'	=> $id,
				'email'						=> $email,
			]);

			if ($this->db->affected_rows() == 0) {
				$this->db->trans_rollback();
				throw new Exception('Falha ao inserir email');
			}
		}

		if (strlen($telefone)) {
			$this->db->insert('showtecsystem.telefones_contatos_clientes_crm', [
				'id_contatos_clientes_crm'	=> $id,
				'telefone'					=> $telefone,
			]);

			if ($this->db->affected_rows() == 0) {
				$this->db->trans_rollback();
				throw new Exception('Falha ao inserir telefone');
			}
		}


		$this->db->trans_complete();
		if ($this->db->trans_status() == false) {
			throw new Exception('Falha na operação');
		} else {
			return $id;
		}
	}

	/**
	 * @author Felipe Libório
	 * 
	 * Lista os contatos relacionados a um cliente cadastrado no CRM
	 * 
	 * $documento_cliente CPF ou CNPJ do cliente, apenas números
	 */
	public function listar_contatos_associados($documento_cliente, $ativos = true)
	{
		if (!is_string($documento_cliente) || !strlen($documento_cliente)) {
			throw new Exception('Documento do cliente não informado');
		}

		if (strlen($documento_cliente) == 11) {
			if (!validar_cpf($documento_cliente)) throw new Exception('CPF inválido');
		} else if (strlen($documento_cliente) == 14) {
			if (!validar_cnpj($documento_cliente)) throw new Exception('CNPJ inválido');
		} else {
			throw new Exception('Documento inválido');
		}

		$where = ['documento_cliente' => $documento_cliente];
		if ($ativos) $where['status'] = 1;

		$contatos = $this->db->select('id,nome,funcao,status')
			->where($where)
			->get('showtecsystem.contatos_clientes_crm')
			->result();

		if (!count($contatos)) return $contatos;

		foreach ($contatos as $contato) {
			$telefones = $this->listar_telefones_contato_associado($contato->id, $ativos);
			$emails = $this->listar_emails_contato_associado($contato->id, $ativos);

			$contato->telefones = $telefones;
			$contato->emails = $emails;
		}

		return $contatos;
	}

	/**
	 * @author Felipe Libório
	 * 
	 * Modifica função e nome de um contato relacionado ao um cliente cadastrado 
	 * no CRM
	 * 
	 * Pelo menos uma alteração deve ser informada (nome ou função)
	 * 
	 * $id Id do contato
	 * $nome Alteração no nome, se houver
	 * $funcao Alteração na função, se houver
	 */
	public function atualizar_contato_associado($id, $nome, $funcao)
	{
		if (!is_numeric($id)) throw new Exception('Id inválido');

		if (is_string($nome) && !strlen($nome)) throw new Exception('Nome vazio');
		if (is_string($funcao) && !strlen($funcao)) throw new Exception('Função vazio');

		$alteracoes = [];
		if (is_string($nome)) $alteracoes['nome'] = $nome;
		if (is_string($funcao)) $alteracoes['funcao'] = $funcao;

		if (!count($alteracoes)) throw new Exception('Nenhuma alteração solicitada');

		$this->db->update('showtecsystem.contatos_clientes_crm', $alteracoes, ['id' => $id]);

		return $this->db->affected_rows();
	}

	/**
	 * @author Felipe Libório
	 * 
	 * Exclui um contato relacionado ao um cliente cadastrado no CRM
	 * 
	 * $id Id do contato
	 */
	public function excluir_contato_associado($id)
	{
		if (!is_numeric($id)) throw new Exception('Id inválido');
		$this->db->update('showtecsystem.contatos_clientes_crm', ['status' => 0], ['id' => $id]);
		return $this->db->affected_rows();
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id_contato Id do contato associado
	 * $telefone Novo telefone do contato
	 */
	public function adicionar_telefone_contato_associado($id_contato, $telefone)
	{
		if (!is_numeric($id_contato)) {
			throw new Exception('Id do contato inválido');
		}
		if (!is_string($telefone) || !strlen($telefone)) {
			throw new Exception('Email inválido');
		}

		$this->db->insert('showtecsystem.emails_contatos_clientes_crm', [
			'id_contatos_clientes_crm'	=> $id_contato,
			'telefone'					=> $telefone,
		]);

		$id = $this->db->insert_id();

		if ($this->db->affected_rows() == 0) throw new Exception('Falha ao adicionar email');
		return $id;
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id_contato Id do contato associado
	 * $ativos Trazer apenas ativos? (true, false)
	 */
	public function listar_telefones_contato_associado($id_contato, $ativos = true)
	{
		if (!is_numeric($id_contato)) {
			throw new Exception('Id do contato inválido');
		}

		$where = ['id_contatos_clientes_crm' => $id_contato];
		if ($ativos) $where['status'] = 1;

		return $this->db->select('*')
			->where($where)
			->get('showtecsystem.telefones_contatos_clientes_crm')
			->result();
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id Id do telefone na tabela showtecsystem.telefones_contatos_clientes_crm
	 * $telefone Novo telefone do contato
	 */
	public function atualizar_telefone_contato_associado($id, $telefone)
	{
		if (!is_numeric($id)) {
			throw new Exception('Id inválido');
		}
		if (!is_string($telefone) || strlen($telefone) < 10 || strlen($telefone) > 11) {
			throw new Exception('Telefone deve ter dez ou onze caracteres');
		}

		$this->db->update('showtecsystem.telefones_contatos_clientes_crm', array('telefone' => $telefone), array('id' => $id));
		return $this->db->affected_rows();
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id Id do telefone na tabela showtecsystem.telefones_contatos_clientes_crm
	 */
	public function excluir_telefone_contato_associado($id)
	{
		if (!is_numeric($id)) {
			throw new Exception('Id inválido');
		}

		$this->db->update('showtecsystem.telefones_contatos_clientes_crm', array('status' => 0), array('id' => $id));

		if ($this->db->affected_rows() == 0) {
			throw new Exception('Falha ao excluir telefone');
		}
		return 1;
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id_contato Id do contato associado
	 * $email Novo email do contato
	 */
	public function adicionar_email_contato_associado($id_contato, $email)
	{
		if (!is_numeric($id_contato)) {
			throw new Exception('Id do contato inválido');
		}
		if (!is_string($email) || !strlen($email)) {
			throw new Exception('Email inválido');
		}

		$this->db->insert('showtecsystem.emails_contatos_clientes_crm', [
			'id_contatos_clientes_crm'	=> $id_contato,
			'email'						=> $email,
		]);

		$id = $this->db->insert_id();

		if ($this->db->affected_rows() == 0) throw new Exception('Falha ao adicionar email');
		return $id;
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id_contato Id contato associado
	 * $ativos Trazer apenas ativos? (true ou false)
	 */
	public function listar_emails_contato_associado($id_contato, $ativos = 1)
	{
		if (!is_numeric($id_contato)) {
			throw new Exception('Id do contato inválido');
		}

		$where = ['id_contatos_clientes_crm' => $id_contato];
		if ($ativos) $where['status'] = 1;

		return $this->db->select('*')
			->where($where)
			->get('showtecsystem.emails_contatos_clientes_crm')
			->result();
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id Id do telefone na tabela showtecsystem.emails_contatos_clientes_crm
	 * $email Novo email do contato
	 */
	public function atualizar_email_contato_associado($id, $email)
	{
		if (!is_numeric($id)) {
			throw new Exception('Id inválido');
		}
		if (!is_string($email) || !strlen($email)) {
			throw new Exception('Email inválido');
		}

		$this->db->update('showtecsystem.emails_contatos_clientes_crm', array('email' => $email), array('id' => $id));
		return $this->db->affected_rows();
	}

	/**
	 * @author Felipe Libório
	 * 
	 * $id Id do email na tabela showtecsystem.emails_contatos_clientes_crm
	 */
	public function excluir_email_contato_associado($id)
	{
		if (!is_numeric($id)) {
			throw new Exception('Id inválido');
		}

		$this->db->update('showtecsystem.emails_contatos_clientes_crm', array('status' => 0), array('id' => $id));

		if ($this->db->affected_rows() == 0) {
			throw new Exception('Falha ao excluir email');
		}
		return 1;
	}

	/**
	 * @author Rento Silva
	 * 
	 * $id eh o Id do cliente
	 */
	public function get_cliente($id, $colunas = '*')
	{
		return $this->db->select($colunas)->where('id', $id)->get('showtecsystem.cad_clientes')->row();
	}

	/**
	 * @author Kyllder
	 */
	public function get_cidades($like)
	{
		$query = $this->db->query("SELECT DISTINCT cidade as text, cidade as id FROM showtecsystem.cad_clientes where cidade like '%" . $like . "%'");
		$result = $query->result_array();
		return $result;
	}


	/**
	 * @author Rento Silva
	 * 
	 */
	public function atualizar_cliente($id_cliente, $dados)
	{
		if ($this->db->update('cad_clientes', $dados, array('id' => $id_cliente))) {
			return true;
		}
		return false;
	}

	/**
	 * Atualiza clientes em lote
	 * @param array $dados - dados a serem atualizados
	 * @param string $campo - campo a ser usado como referencia
	 */
	public function atualizar_clientes_lote($dados, $campo = 'id')
	{
		if (is_array($dados) && !empty($dados)) {
			return @$this->db->update_batch('showtecsystem.cad_clientes', $dados, $campo);
		}
		return false;
	}

	public function listarClientesPlaca($start = 0, $limit = 10, $camp_ord = 'placa', $ordem = 'desc', $like = NULL)
	{
		$this->db->select('*')
			->from('systems.cadastro_veiculo cv');

		if ($like) {
			$this->db->like('cv.placa', $like);
		}

		$this->db->order_by($camp_ord, $ordem);
		$this->db->limit($limit, $start);

		return $this->db->get()->result();
	}

	public function get_clientesRel($select, $where, $order = 'asc', $campo = 'clie.nome')
	{

		$script = "select ";
		$script .= $select;
		$script .= ",Sum(cont.valor_instalacao) as valor_instalacao, 
				Sum(cont.valor_mensal) as valor_unit,
				Sum(cont.quantidade_veiculos) as num_veiculos_contrato,
				Sum(cont.quantidade_veiculos * cont.valor_mensal) as valor_contratado,
				Sum(cv.valor_total) as valor_total,
				Sum(cv.num_veiculos_ativos) as num_veiculos_ativos,
				COUNT(cont.id) as num_contratos
			from showtecsystem.cad_clientes as clie
			inner join showtecsystem.contratos as cont on clie.id = cont.id_cliente
			left join (
				select 
					cont.id as id_contrato,
					COUNT(cv.id_contrato) * cont.valor_mensal as valor_total,
					COUNT(cv.id_contrato) as num_veiculos_ativos
				from showtecsystem.contratos_veiculos as cv 
				inner join showtecsystem.contratos as cont on cv.id_contrato = cont.id
				where cv.status = 'ativo'
				GROUP BY cont.id
			)as cv on cv.id_contrato = cont.id";
		$whereScript = " Where 1 = 1";

		foreach ($where as $key => $w) {
			$whereScript .= " and ";
			$whereScript .= $key . " = '" . $w . "'";
		}

		$whereScript .= " GROUP BY clie.id";

		$query = $this->db->query($script . $whereScript);

		if ($query->num_rows()) {
			return $query->result();
		}
		return [];
	}

	public function get_veiculos($dados)
	{
		$veiculos = false;

		$palavra = $dados['texto'];
		$clienteID = $dados['id_cliente'];
		$code = $dados['code'];

		$this->db->select('code, placa, id_usuario, code_cliente, serial, veiculo, obs_descricao');
		$this->db->where('code_cliente', $clienteID);
		if ($code) {
			$this->db->where('code', $code);
		}
		if ($palavra) {
			$this->db->where("(veiculo LIKE '%$palavra%' OR placa LIKE '%$palavra%')", NULL, FALSE);
		}
		$this->db->order_by('code', 'DESC');
		$query = $this->db->get('systems.cadastro_veiculo');
		$veiculos = $query->result();

		return $veiculos;
	}

	public function get_veiculosALL($dados)
	{
		$veiculos = false;

		$palavra = $dados['texto'];
		$code = $dados['code'];

		$this->db->select('code, placa, id_usuario, code_cliente, serial, veiculo, obs_descricao');

		if ($code) {
			$this->db->where('code', $code);
		}
		if ($palavra) {
			$this->db->where("(veiculo LIKE '%$palavra%' OR placa LIKE '%$palavra%')", NULL, FALSE);
		}
		$this->db->order_by('code', 'DESC');
		$query = $this->db->get('systems.cadastro_veiculo');
		$veiculos = $query->result();

		return $veiculos;
	}

	public function listarClienteById($search)
	{
		if (is_numeric($search)) {
			return $this->db->select('id, nome')
				->where('id', $search)
				->get('showtecsystem.cad_clientes')
				->result();
		} else {
			return array();
		}
	}
}
