<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contrato extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function getMonitorContratos($draw = 1, $search = NULL, $start = 0, $limit = 10) {
		$data['data'] = array();
		$data['draw'] = $draw+1;

		if ($search && is_numeric($search)) {
			$sql = "SELECT cont.*, vend.nome as vendedor, date_add(cont.data_contrato, INTERVAL cont.meses Month) as fim, cont.dataFim_aditivo as fim_aditivo, clie.nome, datediff(CURDATE(), date_add(cont.data_contrato, INTERVAL cont.meses Month)) as diferenca, datediff(CURDATE(), cont.dataFim_aditivo) as diferenca_aditivo
                  FROM showtecsystem.contratos as cont
                  LEFT JOIN showtecsystem.cad_clientes as clie ON cont.id_cliente = clie.id
                  LEFT JOIN showtecsystem.usuario as vend ON cont.id_vendedor = vend.id
                  WHERE date_add(cont.data_contrato, INTERVAL cont.meses Month) <= CURDATE()+90
                    AND date_add(cont.data_contrato, INTERVAL cont.meses Month) >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                    AND cont.dataFim_aditivo is null
                    AND cont.id like '%".$search."%'
                  OR cont.dataFim_aditivo <= CURDATE()+90
                    AND cont.dataFim_aditivo >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                    AND cont.id like '%".$search."%'
                  ORDER BY date_add(cont.data_contrato, INTERVAL cont.meses Month) DESC";

			$data['recordsTotal'] = $this->db->like('id', $search)->count_all_results('showtecsystem.contratos'); # Total de registros
		} elseif ($search) {
			$sql = "SELECT cont.*, vend.nome as vendedor, date_add(cont.data_contrato, INTERVAL cont.meses Month) as fim, cont.dataFim_aditivo as fim_aditivo, clie.nome, datediff(CURDATE(), date_add(cont.data_contrato, INTERVAL cont.meses Month)) as diferenca, datediff(CURDATE(), cont.dataFim_aditivo) as diferenca_aditivo
                  FROM showtecsystem.contratos as cont
                  LEFT JOIN showtecsystem.cad_clientes as clie ON cont.id_cliente = clie.id
                  LEFT JOIN showtecsystem.usuario as vend ON cont.id_vendedor = vend.id
                  WHERE date_add(cont.data_contrato, INTERVAL cont.meses Month) <= CURDATE()+90
                    AND date_add(cont.data_contrato, INTERVAL cont.meses Month) >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                    AND cont.dataFim_aditivo is null
                    AND clie.nome like '%".$search."%'
                  OR cont.dataFim_aditivo <= CURDATE()+90
                    AND cont.dataFim_aditivo >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                    AND clie.nome like '%".$search."%'
                  OR date_add(cont.data_contrato, INTERVAL cont.meses Month) <= CURDATE()+90
                    AND date_add(cont.data_contrato, INTERVAL cont.meses Month) >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                    AND cont.dataFim_aditivo is null
                    AND vend.nome like '%".$search."%'
                  OR cont.dataFim_aditivo <= CURDATE()+90
                    AND cont.dataFim_aditivo >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                    AND vend.nome like '%".$search."%'
                  ORDER BY date_add(cont.data_contrato, INTERVAL cont.meses Month) DESC";

			$data['recordsTotal'] = $this->db->like('nome', $search)->count_all_results('showtecsystem.cad_clientes'); # Total de registros
		} else {
			$sql = "SELECT cont.*, vend.nome as vendedor, date_add(cont.data_contrato, INTERVAL cont.meses Month) as fim, cont.dataFim_aditivo as fim_aditivo, clie.nome, datediff(CURDATE(), date_add(cont.data_contrato, INTERVAL cont.meses Month)) as diferenca, datediff(CURDATE(), cont.dataFim_aditivo) as diferenca_aditivo
                  FROM showtecsystem.contratos as cont
                  LEFT JOIN showtecsystem.cad_clientes as clie ON cont.id_cliente = clie.id
                  LEFT JOIN showtecsystem.usuario as vend ON cont.id_vendedor = vend.id
                  WHERE date_add(cont.data_contrato, INTERVAL cont.meses Month) <= CURDATE()+90
                    AND date_add(cont.data_contrato, INTERVAL cont.meses Month) >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                    AND cont.dataFim_aditivo is null
                  OR cont.dataFim_aditivo <= CURDATE()+90
                    AND cont.dataFim_aditivo >= '2018-01-01'
                    AND cont.status = 2
                    AND cont.notification = 0
                  ORDER BY date_add(cont.data_contrato, INTERVAL cont.meses Month) DESC";

			$data['recordsTotal'] = $this->db->query($sql.';')->num_rows;
		}

        $data['recordsFiltered'] = $this->db->query($sql.';')->num_rows; # Total de registros Filtrados
		$contratos = $this->db->query($sql.' LIMIT '.$start.', '.$limit.';')->result(); # Lista de Clientes
		if ($contratos) {
			foreach ($contratos as $contrato) {
				if (!empty($contrato->diferenca_aditivo)) {
                    if ($contrato->diferenca_aditivo <= 0) {
                        $diferenca = "<span class='label label-small label-warning'>".abs($contrato->diferenca_aditivo)." Restante(s)</span>";
                    } else {
                        $diferenca = "<span class='label label-small label-danger' style='background-color: #CD201F'>".abs($contrato->diferenca_aditivo)." Passado(s)</span>";
                    }
                } else {
                    if ($contrato->diferenca <= 0) {
                        $diferenca = "<span class='label label-small label-warning'>".abs($contrato->diferenca)." Restante(s)</span>";
                    } else {
                        $diferenca = "<span class='label label-small label-danger' style='background-color: #CD201F'>".abs($contrato->diferenca)." Passado(s)</span>";
                    }
                }

				$data['data'][] = array(
					$contrato->id,
					$contrato->nome,
					$contrato->vendedor,
					dh_for_humans($contrato->data_contrato),
					dh_for_humans($contrato->fim),
					$contrato->meses,
					$diferenca,
					'<a class="btn btn-primary" target="_blank" href="'.site_url('clientes/view').'/'.$contrato->id_cliente.'"><i class="fa fa-eye"></i></a>',
					'<a class="btn btn-danger" href="'.site_url('monitor/monitor_contratos').'/'.$contrato->id.'"><i class="fa fa-trash"></i></a>'
				);
			}
		}
	    return $data;
    }

	public function getContratosMonitorados($start = 0, $limit = 10, $cliente = false, $vendedor = false, $contrato = false, $groupBy = false) {
		try {
			$data['data'] = array();
			if ($groupBy) {
				$sql = "SELECT clie.nome as cliente, vend.nome as vendedor
				FROM showtecsystem.contratos as cont
				LEFT JOIN showtecsystem.usuario as vend ON cont.id_vendedor = vend.id
				LEFT JOIN showtecsystem.cad_clientes as clie ON cont.id_cliente = clie.id
				WHERE date_add(cont.data_contrato, INTERVAL cont.meses Month) <= CURDATE()+90
					AND date_add(cont.data_contrato, INTERVAL cont.meses Month) >= '2018-01-01'
					AND cont.status = 2
					AND cont.notification = 0
					AND cont.dataFim_aditivo is null" . 
					($cliente ? (($cliente == 'Indefinido' || $cliente == 'indefinido') ? " AND clie.nome = ''"  : " AND clie.nome LIKE ". "'%{$cliente}%'") : "") .
					($vendedor ? " AND vend.nome LIKE ". "'%{$vendedor}%'" : "") .
					($contrato ? " AND cont.id = ".$contrato : "") .
				" OR cont.dataFim_aditivo <= CURDATE()+90
					AND cont.dataFim_aditivo >= '2018-01-01'
					AND cont.status = 2
					AND cont.notification = 0" . 
					($cliente ? (($cliente == 'Indefinido' || $cliente == 'indefinido') ? " AND clie.nome = ''"  : " AND clie.nome LIKE ". "'%{$cliente}%'") : "") .
					($vendedor ? " AND vend.nome LIKE ". "'%{$vendedor}%'" : "") .
					($contrato ? " AND cont.id = ".$contrato : "") .
					($groupBy ? " GROUP BY ". $groupBy : "") .
				" ORDER BY date_add(cont.data_contrato, INTERVAL cont.meses Month) DESC";

				$contratos = $this->db->query($sql.' LIMIT '.$start.', '.$limit.';')->result(); # Lista de Clientes

				if ($contratos) {
					foreach ($contratos as $contrato) {
						$data['data'][] = array(
							'id' => null,
							'id_cliente' => null,
							'cliente' => $contrato->cliente ? $contrato->cliente : 'Indefinido',
							'vendedor' => null,
							'inicio_contrato' => null,
							'fim_contrato' => null,
							'vigencia' => null,
							'cronograma' => null
						);
					}

				} else {
					$data['message'] = 'Nenhum contrato encontrado';
				}
				
			} else {
				$sql = "SELECT cont.*, vend.nome as vendedor, date_add(cont.data_contrato, INTERVAL cont.meses Month) as fim, cont.dataFim_aditivo as fim_aditivo, clie.nome as cliente, datediff(CURDATE(), date_add(cont.data_contrato, INTERVAL cont.meses Month)) as diferenca, datediff(CURDATE(), cont.dataFim_aditivo) as diferenca_aditivo
				FROM showtecsystem.contratos as cont
				LEFT JOIN showtecsystem.usuario as vend ON cont.id_vendedor = vend.id
				LEFT JOIN showtecsystem.cad_clientes as clie ON cont.id_cliente = clie.id
				WHERE date_add(cont.data_contrato, INTERVAL cont.meses Month) <= CURDATE()+90
					AND date_add(cont.data_contrato, INTERVAL cont.meses Month) >= '2018-01-01'
					AND cont.status = 2
					AND cont.notification = 0
					AND cont.dataFim_aditivo is null" . 
					($cliente ? (($cliente == 'Indefinido' || $cliente == 'indefinido') ? " AND clie.nome = ''"  : " AND clie.nome LIKE ". "'%{$cliente}%'") : "") .
					($vendedor ? " AND vend.nome LIKE ". "'%{$vendedor}%'" : "") .
					($contrato ? " AND cont.id = ".$contrato : "") .
				" OR cont.dataFim_aditivo <= CURDATE()+90
					AND cont.dataFim_aditivo >= '2018-01-01'
					AND cont.status = 2
					AND cont.notification = 0" . 
					($cliente ? (($cliente == 'Indefinido' || $cliente == 'indefinido') ? " AND clie.nome = ''"  : " AND clie.nome LIKE ". "'%{$cliente}%'") : "") .
					($vendedor ? " AND vend.nome LIKE ". "'%{$vendedor}%'" : "") .
					($contrato ? " AND cont.id = ".$contrato : "") .
				" ORDER BY date_add(cont.data_contrato, INTERVAL cont.meses Month) DESC";

				$contratos = $this->db->query($sql.' LIMIT '.$start.', '.$limit.';')->result(); # Lista de Clientes
				if ($contratos) {
					foreach ($contratos as $contrato) {
						$data['data'][] = array(
							'id' => $contrato->id,
							'id_cliente' => $contrato->id_cliente,
							'cliente' => $contrato->cliente ? $contrato->cliente : 'Indefinido',
							'vendedor' => $contrato->vendedor,
							'inicio_contrato' => dh_for_humans($contrato->data_contrato),
							'fim_contrato' => dh_for_humans($contrato->fim),
							'vigencia' => $contrato->meses,
							'cronograma' => !empty($contrato->diferenca_aditivo) ? $contrato->diferenca_aditivo : $contrato->diferenca
						);
					}

				} else {
					$data['message'] = 'Nenhum contrato encontrado';
				}
			}

			$data['recordsTotal'] = $this->db->query($sql.';')->num_rows;

			$data['success'] = true;

		} catch (\Exception $e) {
			$data['success'] = false;
			$data['message'] = 'Erro ao realizar listagem! Entre em contato com o suporte.';
		}

		return $data;
	    
    }

    public function insertMonthlyPointByClient($id_cliente, $pontos) {
	    return $this->db->insert('showroutes.carteira_clientes', array('id_cliente' => $id_cliente, 'pontos' => $pontos));
    }

    public function desativaNotification($id_cont) {
	    return $this->db->update('showtecsystem.contratos', array('notification' => 1), array('id' => $id_cont));
    }

	public function get($where, $select='*') {
		$this->db->select($select);
		$query = $this->db->get_where('contratos', $where);
		if($query->num_rows() > 0) {
			foreach($query->result() as $contratos);
			return $contratos;
		}
		return false;
	}

	public function separar_secretaria($id_contrato, $status){
		$this->db->where('id', $id_contrato)->update('showtecsystem.contratos', array('secretaria' => $status));
		if($this->db->affected_rows() > 0) {
			if($status){
				$this->db->where('id_contrato', $id_contrato);
				$query = $this->db->get('showtecsystem.contratos_secretaria');
				if ($query->num_rows()){
					return true;
				}
				else{
					$this->db->insert('showtecsystem.contratos_secretaria', array('nome'=>'MASTER','status'=>'1','id_contrato'=>$id_contrato));
					if($this->db->affected_rows() > 0) {
						$id_secretaria = $this->db->insert_id();
						$this->db->where('id_contrato',$id_contrato)->update('showtecsystem.cad_contasbank',array('id_contrato_secretaria'=>$id_secretaria));
						return true;
					}
				}
				return false;
			}
		}
	}

	public function get_contratos($id_contrato, $select='*') {
		$query = $this->db->select($select)
				->where('id', $id_contrato)
				->get('showtecsystem.contratos');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function get_contrato_status($id_contrato) {
		$query = $this->db->select('status')
				->where('id', $id_contrato)
				->get('showtecsystem.contratos');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function listar($where, $paginacao = 0, $limite = 9999999, $campo_ordem = 'ctr.id', $ordem = 'DESC', $select = 'ctr.*, cli.nome, vend.nome as nome_vend', $join=true) {
		$this->db->select($select);
			if ($join) {
				$this->db->join('cad_clientes as cli', 'cli.id = ctr.id_cliente');
				$this->db->join('usuario as vend', 'vend.id = ctr.id_vendedor', 'left');
			}
			$this->db->where($where);
			$this->db->order_by($campo_ordem, $ordem);

		$query = $this->db->get('contratos as ctr', $limite, $paginacao);
		return $query->result();
	}

    public function listarTable($where,  $campo_ordem = 'ctr.id', $ordem = 'DESC', $select = 'ctr.*, cli.nome, vend.nome as nome_vend', $join_cliente=true)
    {
        $this->db->select($select);
			if ($join_cliente)
				$this->db->join('cad_clientes as cli', 'cli.id = ctr.id_cliente');

            $this->db->join('usuario as vend', 'vend.id = ctr.id_vendedor', 'left');
            $this->db->where($where);
            $this->db->order_by($campo_ordem, $ordem);

        $query = $this->db->get('contratos as ctr');
        return $query->result();
    }

	/*
	* LISTAGEM DE CONTRATOS POR SERVE-SIDE
	*/
	public function list_contratos_serveside($where, $select = 'con.*, vend.nome', $start=0, $limit=10, $search = NULL, $draw = 1, $tipo_busca = 'id_contrato')
	{
		$num_rows = 0;

		//se foi foito uma busca na tabela
        if ($search)
		{
			if ($tipo_busca == "id_contrato") { //busca pelo id_contrato
				$this->db->select($select)
					->join('showtecsystem.usuario as vend', 'vend.id = con.id_vendedor', 'left')
					->like('con.id', $search)
					->where($where)
					->group_by('con.id')
					->order_by('con.id', 'DESC');

				//here we use the clone command to create a shallow copy of the object and run the count method on this copy
				$tempdb = clone $this->db;
				$num_rows = $tempdb->from('showtecsystem.contratos as con')->count_all_results();
				
				$query = $this->db->get('showtecsystem.contratos as con', $limit, $start);
			}
			
			$where_query = array('con.id_cliente' => $where['id_cliente']); 
			if(isset($where['tipo_proposta'])){
				$where_query['tipo_proposta'] = $where['tipo_proposta'];
			}

			if($tipo_busca == "placa" || $tipo_busca == "tornozeleira"){ //busca por placa ou tornozeleira
				$search_column = '';
				switch ($tipo_busca) {
					case 'placa':
						$search_column = 'con_v.placa';
						break;
					case 'tornozeleira':
						$search_column = 'con_v.equipamento';
						break;
				}

				$this->db->select($select)
					->join('showtecsystem.contratos as con', 'con.id = con_v.id_contrato', 'inner')
					->join('showtecsystem.usuario as vend', 'vend.id = con.id_vendedor', 'left')
					->like($search_column, $search)
					->where($where_query)
					->group_by('con.id')
					->order_by('con.id', 'DESC');

				//here we use the clone command to create a shallow copy of the object and run the count method on this copy
				$tempdb = clone $this->db;
				$num_rows = $tempdb->from('showtecsystem.contratos_veiculos as con_v')->count_all_results();

				$query = $this->db->get('showtecsystem.contratos_veiculos as con_v', $limit, $start);
			}  else if($tipo_busca == "isca"){ //busca por isca				
				
				$this->db->select($select)
					->join('showtecsystem.contratos as con', 'con.id = cad_iscas.id_contrato', 'inner')
					->join('showtecsystem.usuario as vend', 'vend.id = con.id_vendedor', 'left')
					->like('cad_iscas.serial', $search)
					->where($where_query)
					->group_by('con.id')
					->order_by('con.id', 'DESC');

				//here we use the clone command to create a shallow copy of the object and run the count method on this copy
				$tempdb = clone $this->db;
				$num_rows = $tempdb->from('showtecsystem.cad_iscas')->count_all_results();
				
				$query = $this->db->get('showtecsystem.cad_iscas', $limit, $start);
			}  else if($tipo_busca == "suprimento"){ //busca por suprimento
				
				$this->db->select($select)
					->join('showtecsystem.contratos_suprimentos as con_s', 'con_s.id_suprimento = sup.id', 'inner')
					->join('showtecsystem.contratos as con', 'con.id = con_s.id_contrato', 'inner')
					->join('showtecsystem.usuario as vend', 'vend.id = con.id_vendedor', 'left')
					->like('sup.serial', $search)
					->where($where_query)
					->group_by('con.id')
					->order_by('con.id', 'DESC');

				//here we use the clone command to create a shallow copy of the object and run the count method on this copy
				$tempdb = clone $this->db;
				$num_rows = $tempdb->from('showtecsystem.cad_suprimentos as sup')->count_all_results();
				
				$query = $this->db->get('showtecsystem.cad_suprimentos as sup', $limit, $start);
			} else if($tipo_busca == "simcard"){ //busca por simcard
				
				$this->db->select($select)
					->join('showtecsystem.contratos as con', 'con.id = cad_chips.id_contrato_sim2m', 'inner')
					->join('showtecsystem.usuario as vend', 'vend.id = con.id_vendedor', 'left')
					->like('cad_chips.numero', $search)
					->where($where_query)
					->group_by('con.id')
					->order_by('con.id', 'DESC');

				//here we use the clone command to create a shallow copy of the object and run the count method on this copy
				$tempdb = clone $this->db;
				$num_rows = $tempdb->from('showtecsystem.cad_chips')->count_all_results();
				
				$query = $this->db->get('showtecsystem.cad_chips', $limit, $start);
			}
        } else {
			//retorna todos os contratos do cliente
			$this->db->select($select)
			->join('showtecsystem.usuario as vend', 'vend.id = con.id_vendedor', 'left')
			->where($where)
			->order_by('con.id', 'DESC');

			//here we use the clone command to create a shallow copy of the object and run the count method on this copy
			$tempdb = clone $this->db;
			$num_rows = $tempdb->from('showtecsystem.contratos as con')->count_all_results();

			$query = $this->db->get('showtecsystem.contratos as con', $limit, $start);
        }
		
		if($query->num_rows() >= 0){
			$dados['contratos'] = $query->result(); # Lista de contratos
			$dados['recordsTotal'] = $this->db->where($where)->count_all_results('showtecsystem.contratos'); # Total de registros
			$dados['recordsFiltered'] = $num_rows; # Total de registros Filtrados
	        $dados['draw'] = $draw; # Draw do datatable
			return $dados;
		}

		return false;
    }

	public function update_vendedor($contrato, $vendedor) {
	    return $this->db->where('id', $contrato)->update('showtecsystem.contratos', array('id_vendedor' => $vendedor));
	}

	public function update_trz($update, $id) {

	    return $this->db->update('showtecsystem.contratos_veiculos', $update, array('id' => $id));
	}

	public function update_sup($update, $id) {
	    return $this->db->update('showtecsystem.contratos_suprimentos', $update, array('id' => $id));
    }

	public function update_dataFim_aditivo($contrato, $dataFim_aditivo) {
	    return $this->db->where('id', $contrato)->update('showtecsystem.contratos', array('dataFim_aditivo' => $dataFim_aditivo));
	}

	public function listar1($where, $where_in, $paginacao = 0, $limite = 9999999, $campo_ordem = 'ctr.id', $ordem = 'DESC', $select = 'ctr.*, cli.nome') {
		$this->db->select($select)
				 ->join('cad_clientes as cli', 'cli.id = ctr.id_cliente')
				 ->join('cad_vendedores as vend', 'vend.id = ctr.id_vendedor', 'left')
				 ->where($where)
				 ->order_by($campo_ordem, $ordem);
		if(is_array($where_in))
			$this->db->where_in('ctr.status', $where_in);
		$query = $this->db->get('contratos as ctr', $limite, $paginacao);
		return $query->result();
	}

	public function total_lista($where = array()) {
		return $this->db->from('contratos')->where($where)->count_all_results();
	}

	public function listar_placas($where, $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC') {
		$query = $this->db->order_by($campo_ordem, $ordem)->get_where('contratos_veiculos', $where, $limite, $paginacao);
		return $query->result();
	}

	public function listar_suprimentos($where, $paginacao = 0, $limite = 9999999, $campo_ordem = 'id_con_sup', $ordem = 'DESC') {

		$query = $this->db->select('con.id as id_con_sup, id_contrato, id_cliente, id_suprimento, con.status as status_con_sup, con.data_cadastro as data_cadastro_con_sup, serial, descricao, tipo')
				 ->join('cad_suprimentos as sup', 'con.id_suprimento = sup.id', 'left')
				 ->where($where)
				 ->order_by($campo_ordem, $ordem)
				 ->get('contratos_suprimentos as con', $limite, $paginacao);

		return $query->result();
	}

	public function listar_iscas($where, $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC') {
		$query = $this->db->order_by($campo_ordem, $ordem)->get_where('showtecsystem.cad_iscas', $where, $limite, $paginacao);
		return $query->result();
	}

	public function get_iscas($where, $campo_ordem = 'id', $ordem = 'DESC') {
		$query = $this->db->order_by($campo_ordem, $ordem)->get_where('showtecsystem.cad_iscas', $where);
		return $query->result();
	}

	public function listar_chips($where, $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC') {
		$query = $this->db->order_by($campo_ordem, $ordem)->get_where('showtecsystem.cad_chips', $where, $limite, $paginacao);
		return $query->result();
	}

	public function listar_placas_os($id_contrato) {
		$this->db->where('id_contrato', $id_contrato);
		$query = $this->db->get('showtecsystem.contratos_veiculos');
		if ($query->num_rows()) {
			foreach ($query->result() as $pla)
				$placas[] = $pla->placa;
			return json_encode($placas);
		}
		return false;
	}

	public function listar_pesquisa_placas($placa, $id_contrato) {
		$this->db->like('placa', $placa);
		$this->db->where('id_contrato', $id_contrato);
		$query = $this->db->get('showtecsystem.contratos_veiculos');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function listar_placas_contrato($where=array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC', $select = '*', $like = NULL, $id_contrato = NULL) {

		$this->db->select($select);
		$this->db->where('id_contrato', $id_contrato);
        if ($like)
            $this->db->like($like);

         return $this->db->group_by($campo_ordem)->get('showtecsystem.contratos_veiculos', $limite, $paginacao)->result();
    }

	public function qtd_placas_ativas_contratos($id_contrato) {
		$this->db->select('status');
		$this->db->where('id_contrato', $id_contrato);
		$this->db->where('status', 'ativo');
	
		$qtd = $this->db->get('showtecsystem.contratos_veiculos')->num_rows();
	
		return $qtd;
	}

	public function listar_pesquisa_iscas($serial, $id_contrato) {
		$this->db->like('serial', $serial);
		$this->db->where('id_contrato', $id_contrato);
		$query = $this->db->get('showtecsystem.cad_iscas');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function listar_pesquisa_chips($chip, $id_contrato) {
		$this->db->like('numero', $chip);
		$this->db->where('id_contrato_sim2m', $id_contrato);
		$query = $this->db->get('showtecsystem.cad_chips');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

    public function listar_pesquisa_tornozeleiras($tornozeleira, $id_contrato) {
        $this->db->like('equipamento', $tornozeleira);
        $this->db->where('id_contrato', $id_contrato);
        $query = $this->db->get('showtecsystem.contratos_veiculos');
        if ($query->num_rows())
            return $query->result();
        return false;
    }

	public function total_lista_chips($where = array()) {
		return $this->db->from('cad_chips')->where($where)->count_all_results();
	}

	public function total_lista_placas($where = array()) {
		return $this->db->from('contratos_veiculos')->where($where)->count_all_results();
	}

	/*
	* LISTA TODAS AS PLACAS DO CLIENTE
	*/
	public function placas_cliente($id_cliente) {
		$query = $this->db->select('id_contrato')
		->where( array('id_cliente' => $id_cliente, 'status' => 'ativo') )
		->get('showtecsystem.contratos_veiculos');

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	/*
	* LISTA TODAS AS ISCAS DO CLIENTE
	*/
	public function iscas_cliente($id_cliente) {
		$query = $this->db->select('id_contrato')
		->where( array('id_cliente' => $id_cliente, 'status' => 1) )
		->get('showtecsystem.cad_iscas');

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	/*
	* LISTA TODAS AS LINHAS DO CLIENTE
	*/
	public function linhas_cliente($id_cliente) {
		$query = $this->db->select('id_contrato_sim2m')
		->where( array('id_cliente_sim2m' => $id_cliente, 'status' => 1) )
		->get('showtecsystem.cad_chips');

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function total_lista_iscas($where = array()) {
		return $this->db->from('cad_iscas')->where($where)->count_all_results();
	}



	public function inserir_placa($veiculo) {
		$query = $this->get_contratos($veiculo['id_contrato'], 'id, status, quantidade_veiculos, id_cliente');
        $veiculo['placa'] = str_replace(" ", "", trim(strtoupper($veiculo['placa'])));

		if(is_array($query) && count($query) > 0) {
			$contrato = $query[0];
			$total_placas = $this->total_lista_placas('id_contrato = '.$contrato->id .' and status in ("cadastrado", "ativo" )');

			if( in_array($contrato->status, array('0','1','2')) ) {

				if($total_placas < $contrato->quantidade_veiculos ) {

					$confirma_placa = $this->verifica_placa($veiculo);
					if(!$confirma_placa)
					{
						// busca fuso-horario do cliente para replicar no contrato_veiculo
						$cliente = $this->cliente->get_clientes($contrato->id_cliente)[0];
						$veiculo['fuso_horario'] = $cliente->gmt;

						$insert = $this->db->insert('contratos_veiculos', $veiculo);
					} else {
						if ($veiculo['id_contrato'] == $confirma_placa)
							throw new Exception('Esta placa já está cadastrada no contrato atual');
						else
							throw new Exception('Esta placa já está ativa no contrato '.$confirma_placa);
					}
				} else {
					throw new Exception('O cadastro já atingiu o limite de veículos ativos especificado em contrato.');
				}
			} else {
				throw new Exception('O contrato deve está ativo para adicionar um veículo.');
			}
		} else {
			throw new Exception('Um contrato é obrigatório para cadastrar um veículo.');
		}
	}

	public function inserir_veiculo($veiculo) {
		$contrato = $this->get(array('id' => $veiculo['id_contrato']));
        $veiculo['placa'] = str_replace(" ", "", trim(strtoupper($veiculo['placa'])));

		if(count($contrato) > 0) {
			$total_veiculos = $this->total_lista_placas(array('id_contrato' => $contrato->id, 'status' => 'ativo'));

			if($contrato->status == 0 || $contrato->status == 1 || $contrato->status == 2) {

				if($total_veiculos < $contrato->quantidade_veiculos ) {

					$confirma_placa = $this->verifica_placa($veiculo);
					if(!$confirma_placa)
					{
						// busca fuso-horario do cliente para replicar no contrato_veiculo
						$cliente = $this->cliente->get_clientes($contrato->id_cliente)[0];
						$veiculo['fuso_horario'] = $cliente->gmt;

						$insert = $this->db->insert('contratos_veiculos', $veiculo);
					} else {
						throw new Exception('Esta placa já está ativa no contrato '.$confirma_placa);
					}
				} else {
					throw new Exception('O cadastro já atingiu o limite de veículos ativos especificado em contrato.');
				}
			} else {
				throw new Exception('O contrato deve está ativo para adicionar um veículo.');
			}
		} else {
			throw new Exception('Um contrato é obrigatório para cadastrar um veículo.');
		}
	}

	public function inserir_isca($dados){
		$equipamento_existe = $this->db->get_where('showtecsystem.cad_equipamentos', ['serial' => $dados['serial']]);
		$id_cliente = $dados['id_cliente'];
		
		if($equipamento_existe && $equipamento_existe->result()) {
			$query = $this->db->select(array('id','id_contrato', 'status', 'serial'))->get_where('showtecsystem.cad_iscas', array('serial' => $dados['serial']));
			// $query = $this->db->select('id_contrato')->get_where('showtecsystem.cad_iscas', array('serial' => $dados['serial']));

			if ($query->num_rows() == 0) {
				$this->db->insert('showtecsystem.cad_iscas', $dados);
				$id_isca = $this->db->insert_id();
				$this->db->where('id', $dados['id_contrato'])->update('showtecsystem.contratos', array('status' => 2));

				$this->vinculaIscaGrupoMaster($id_isca, $id_cliente);
				
	
			}else{
				$result = $query->result();
				if(isset($result[0]->id_contrato)){
					throw new Exception('409: O serial '.$result[0]->serial.' já está cadastrado e '.($result[0]->status ? 'ativo' : 'inativo').' no contrato #'.$result[0]->id_contrato);
				}else{
					throw new Exception('409: O serial '.$result[0]->serial.' já está cadastrado e '.($result[0]->status ? 'ativo' : 'inativo').' em estoque');
				}
			}
	
			return $id_isca;
		} else {
			throw new Exception('404: O serial '.$dados['serial'].' não pode ser cadastrado, pois não existe.');
		}
		
		return null;
	}

	/**
	 * Vincula isca ao grupo master do cliente
	 */
	public function vinculaIscaGrupoMaster($id_isca, $id_cliente){
		// Id do grupo MASTER do cliente
		$id_grupo_master = $this->db->select('id')->where(array('id_cliente' => $id_cliente, 'nome' => 'MASTER'))->get('showtecsystem.cadastro_grupo')->result();

		//checando se há grupo master ou criando um se necessário
		if(isset($id_grupo_master[0])){
			$id_grupo_master = $id_grupo_master[0]->id;
		}else{
			$dados = array(
                'nome' => 'MASTER',
                'id_cliente' => $id_cliente,
                'status' => 1
            );

            $this->db->insert('showtecsystem.cadastro_grupo', $dados);
			$id_grupo_master = $this->db->insert_id();
		}

		// Vincula isca ao grupo MASTER do usuário
		$isca_x_group = $this->db->get_where('showtecsystem.isca_x_group', array('id_isca' => $id_isca, 'id_group' => $id_grupo_master))->row();
		if($isca_x_group){
			if( $isca_x_group->status == 0 ){
				$this->db->update('showtecsystem.isca_x_group', array('status' => 1), array('id_isca' => $id_isca, 'id_group' => $id_grupo_master));
			}
		}
		else{
			$this->db->insert('showtecsystem.isca_x_group', array('id_isca'=>$id_isca, 'id_group' => $id_grupo_master, 'status' => 1));
		}
	}

	public function get_cad_iscas_contrato($id_contrato)
	{
		$query = 'SELECT showtecsystem.cad_iscas.serial
							FROM showtecsystem.cad_iscas
							WHERE showtecsystem.cad_iscas.id_contrato = "'.$id_contrato.'"';
		$quant = $this->db->query($query);

		return $quant->num_rows();
	}

	public function get_quant_iscas_contrato($id_contrato)
	{
		$query = 'SELECT showtecsystem.contratos.quantidade_veiculos
							FROM showtecsystem.contratos
							WHERE showtecsystem.contratos.id = "'.$id_contrato.'"';
		$quant = $this->db->query($query);

		if($quant->row()) {
			return $quant->row()->quantidade_veiculos;
		}
		return '';
	}

	public function inserir_suprimento($dados){
		$query = $this->db->select('id_contrato')->get_where('showtecsystem.contratos_suprimentos', array('id_suprimento' => $dados['id_suprimento'], 'status' => 'ativo'));

		if ($query->num_rows() == 0) {
			$this->db->insert('showtecsystem.contratos_suprimentos', $dados);
			$id_suprimento = $this->db->insert_id();
		}else{
			$result = $query->result();
			throw new Exception('Este suprimento já está ativo no contrato #'.$result[0]->id_contrato);
		}

		return $id_suprimento;

	}

    public function inserir_tornozeleira($equipamento) {
        $contrato = $this->get(array('id' => $equipamento['id_contrato']));

        if($contrato){
            $total_eqp = $this->total_lista_placas(array('id_contrato' => $contrato->id, 'status' => 'ativo'));

            if($contrato->status == 0 || $contrato->status == 1 || $contrato->status == 2) {

                if($total_eqp < $contrato->quantidade_veiculos ) {

                    $confirma_placa = $this->verifica_tornozeleira($equipamento);
					if(!$confirma_placa)
					{
						// busca fuso-horario do cliente para replicar no contrato_veiculo
						$cliente = $this->cliente->get_clientes($contrato->id_cliente)[0];
						$equipamento['fuso_horario'] = $cliente->gmt;
						
                        $this->db->insert('contratos_veiculos', $equipamento);
						$insert = $this->db->insert_id();
                        if ($insert) {
                            $this->db->update('showtecsystem.contratos', array('status' => 1), array('id' => $equipamento['id_contrato']));
                            $this->db->update('showtecsystem.cad_clientes', array('status' => 1), array('id' => $equipamento['id_cliente']));

							return $insert;
                        } else {
                            throw new Exception('Erro ao tentar inserir o cadastro.');
                        }
                    } else {
						$update = $this->db->update('showtecsystem.contratos_veiculos', [
							'status' => $equipamento['status']
						], [
							'id_contrato' => $equipamento['id_contrato'],
							'equipamento' => $equipamento['equipamento']
						]);
						if($update) {
							return $equipamento['equipamento'];
						} else {
							throw new Exception('Esta tornozeleira já está ativa no contrato '.$confirma_placa);
						}
                    }
                } else {
                    throw new Exception('O cadastro já atingiu o limite de equipamentos ativos especificado em contrato.');
                }
            } else {
                throw new Exception('O contrato deve está ativo para adicionar uma tornozeleira.');
            }
        } else {
            throw new Exception('Um contrato é obrigatório para cadastrar uma tornozeleira.');
        }
    }

	public function get_veiculo($where) {
		$veiculo = array();
		$query = $this->db->get_where('contratos_veiculos', $where);
		if($query->num_rows() > 0)
			foreach($query->result() as $veiculo);
		return $veiculo;
	}

	public function get_isca($where) {
		$isca = array();
		$query = $this->db->get_where('showtecsystem.cad_iscas', $where);
		if($query->num_rows() > 0)
			foreach($query->result() as $isca);
		return $isca;
	}

	private function verifica_placa($veiculo) {
		$contratos = false;
		$verifica = $this->db->select('id_contrato')->get_where('showtecsystem.contratos_veiculos', array('placa' => $veiculo['placa'], 'id_contrato' => $veiculo['id_contrato']))->result();

		if(empty($verifica) || count($verifica) <= 0) {
            $query = $this->db->select('id_contrato')
                ->get_where('showtecsystem.contratos_veiculos', 'placa = "'.$veiculo['placa'].'" and status in ("ativo", "cadastrado" )' );

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $contrato) {
                    $ctr[] = $contrato->id_contrato;
                }
                $contratos = implode(',', $ctr);
            }
        } else {
		    $contratos = $verifica[0]->id_contrato;
        }

		return $contratos;
	}

	public function verifica_status_tornozeleira($serial, $status){
	    return $this->db->select('id_contrato')->get_where('showtecsystem.contratos_veiculos', array('equipamento' => $serial, 'status' => 'ativo'))->result();
	}

	public function verifica_status_suprimento($id_sup){
	    return $this->db->select('id_contrato')->get_where('showtecsystem.contratos_suprimentos', array('id_suprimento' => $id_sup, 'status' => 'ativo'))->result();
    }

	public function verifica_tornozeleira($equipamento, $status = false) {
        $contratos = false;
        $verifica = $this->db->select('id_contrato')->get_where('showtecsystem.contratos_veiculos', array('equipamento' => $equipamento['equipamento'], 'id_contrato' => $equipamento['id_contrato']))->result();
		//Verifica se já esta cadastrado no contrato do cliente
		if (count($verifica) > 0) {
			foreach ($verifica as $key => $contrato) {
				$ctr[] = $contrato->id_contrato;
			}
			$contratos = implode(', ', $ctr);
			return $contratos;
		}
		//Verifica se já esta cadastrado e ativo no banco
		$query = $this->db->select('id_contrato')
			->get_where('showtecsystem.contratos_veiculos', array('equipamento' => $equipamento['equipamento'], 'status' => 'ativo'))->result();

		if (count($query) > 0){
			foreach ($query as $key => $contrato) {
				$ctr[] = $contrato->id_contrato;
			}
			$contratos = implode(',', $ctr);
			return $contratos;
		}
        return $contratos;
    }

    private function verifica_equipamento($equipamento) {
        $contratos = false;
        $verifica = $this->db->select('id_contrato')->get_where('showtecsystem.contratos_veiculos', array('equipamento' => $equipamento['equipamento'], 'id_contrato' => $equipamento['id_contrato']))->result();

		if(empty($verifica) || count($verifica) <= 0) {
            $query = $this->db->select('id_contrato')
                ->get_where('showtecsystem.contratos_veiculos', array('equipamento' => $equipamento['equipamento'], 'status' => 'ativo'));

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $contrato) {
                    $ctr[] = $contrato->id_contrato;
                }
                $contratos = implode(',', $ctr);
            }
        } else {
            $contratos = true;
        }
        return $contratos;
    }

    public function listaPlacasAtivas() {
	    return $this->db->get_where('showtecsystem.contratos_veiculos', array('status' => 'ativo', 'id_cliente' => '2468'));
    }

    public function ativaNoGrupo($placa) {
	    return $this->db->where('placa', $placa)->where('groupon', '650')->update('showtecsystem.veic_x_group', array('status' => 0));
	}
	public function vincular_secretaria($id,$id_secretaria){
		$this->db->update('contratos_veiculos', array('id_contrato_secretaria' => $id_secretaria), array('id' => $id));
		return 1;
	}

	public function atualiza_status_isca($id_isca, $status){
		$this->db->where('id', $id_isca);
		$this->db->update('showtecsystem.cad_iscas', array('status' => $status));
	}

	public function atualiza_status_placa($id_placa, $status, $email) {
        $veic = $this->get_veiculo(array('id' => $id_placa));
        if ($veic) {
            if($status == 'ativo') {
                $id_status = '3';
                $contrato = $this->get(array('id' => $veic->id_contrato));
                $total_veiculos = $this->verifica_qtd_veiculos_ativos($contrato->id);
                if($total_veiculos < $contrato->quantidade_veiculos){
                    $this->db->update('contratos_veiculos', array('status' => $status, 'data_inativa' => null), array('id' => $id_placa));
                } else {
                    throw new Exception('Contrato já atingiu o limite de veículos ativos. Este veículo não foi ativado!');
                }
            } else {
                $id_status = '4';
                $this->db->update('contratos_veiculos', array('status' => $status, 'data_inativa' => date('Y-m-d H:i:s')), array('id' => $id_placa));
            }

			$veiculo = $this->veiculo->getVeiculo_byPlaca($veic->placa);
			if($veiculo){
				$log = array(
					'acao' => $id_status,
					'data' => date('Y-m-d H:i:s'),
					'antes' => json_encode($veic),
					'depois' => json_encode($veic),
					'motivo' => 'Atualização status veículo',
					'observacao' => '',
					'usuario' => $email,
					'veiculo' => $veiculo->code,
					'placa_antes' => $veiculo->placa,
					'placa_depois' => $veiculo->placa,
					'serial_antes' => $veiculo->serial,
					'serial_depois' => $veiculo->serial
				);
				$this->veiculo->criar_log($log);
			}
			else{
				$log = array(
					'acao' => $id_status,
					'data' => date('Y-m-d H:i:s'),
					'antes' => json_encode($veiculo),
					'depois' => json_encode($veiculo),
					'motivo' => 'Atualização status veículo',
					'observacao' => '',
					'usuario' => $email,
					'veiculo' => '0',
					'placa_antes' => $veic->placa,
					'placa_depois' => $veic->placa,
					'serial_antes' => '0',
					'serial_depois' => '0'
				);
				$this->veiculo->criar_log($log);
			}
        } else {
            throw new Exception('Veículo não possui serial cadastrado, verifique e tente novamente.');
        }
	}

	public function verifica_qtd_veiculos_ativos($id_contrato) {
		$total = $this->db->from('contratos_veiculos')->where(array('id_contrato' => $id_contrato, 'status' => 'ativo'))
						  ->count_all_results();
		return $total;
	}

	public function totais($where) {
		$totais = array();
		$query_total_veiculos = $this->db->select('count(contratos_veiculos.id) as total')
            ->where($where)
            ->where_in('contratos.status', array(1, 2))
            ->join('showtecsystem.contratos_veiculos', 'contratos.id = contratos_veiculos.id_contrato AND contratos_veiculos.status = "ativo"')
            ->get('showtecsystem.contratos');

		$totais['veiculos'] = $query_total_veiculos->row()->total;

		$query_mensalidades = $this->db->select('quantidade_veiculos, valor_mensal,valor_instalacao')
						  ->where($where)
						  ->where_in('contratos.status', array(1, 2))
						  ->get('showtecsystem.contratos');

		$total_mensalidades = 0;

		foreach ($query_mensalidades->result() as $mensalidade) {
			$total_mensalidades += $mensalidade->quantidade_veiculos * $mensalidade->valor_mensal;
		}

		$totais['mensalidades'] = $total_mensalidades;

		$total_instalacao = 0;

		foreach ($query_mensalidades->result() as $mensalidade) {
			$total_instalacao += $mensalidade->quantidade_veiculos * $mensalidade->valor_instalacao;
		}

		$totais['instalacao'] = $total_instalacao;

		$query_total_ativos = $this->db->select('count(contratos.id) as total')
						  ->where($where)
						  ->where_in('contratos.status', array(1, 2))
						  ->get('showtecsystem.contratos');

		$totais['ativos'] = $query_total_ativos->row()->total;
		return $totais;
	}

	public function relatorio_cliente($dados) {
		$relatorio = array();
		$where = array(
				'ctr.data_contrato >=' => $dados['dt_ini'],
				'ctr.data_contrato <=' => $dados['dt_fim'],
			);
		if(is_array($dados['status'])) {
			$where_in = $dados['status'];
		} elseif($dados['status'] == 'all') {
			$where_in = array(0, 1, 2, 3, 4, 5, 6);
		} elseif($dados['status'] == 1) {
			$where_in = array(1, 2);
		} else {
			$where['ctr.status'] = $dados['status'];
			$where_in = false;
		}

		if (isset($dados['empresa'])) {
            if ($dados['empresa'] != 'TODOS')
                $where['cli.informacoes'] = $dados['empresa'];
        }

		if (isset($dados['uf']) && isset($dados['cidade'])) {
            if ($dados['uf'] != '')
                $where['cli.uf'] = $dados['uf'];

            if ($dados['cidade'] != '')
                $where['cli.cidade'] = $dados['cidade'];
        }

		if (!empty($dados['cliente'])) {
			$this->load->model('cliente');
			$cliente = $this->cliente->get(array('nome' => $dados['cliente']));
			if (count($cliente))
				$where['ctr.id_cliente'] = $cliente->id;
		}

		$contratos = $this->listar1($where, $where_in, 0, 9999999, 'ctr.data_contrato', 'DESC', 'ctr.*, cli.nome cliente, vend.nome vendedor');

		if (count($contratos))
			$relatorio = $this->agrupar_contrato_cliente($contratos);
		return $relatorio;
	}

	private function agrupar_contrato_cliente ($contratos) {
		$contrato_agrupado = array();
		foreach ($contratos as $ctr)
			$contrato_agrupado[$ctr->cliente][] = $ctr;
		return $contrato_agrupado;
	}

	public function get_arqui_contratos($prefixo) {
		$this->db->where('ndoc', $prefixo);
		$this->db->where('pasta', 'contratos');
		$query = $this->db->get('showtecsystem.contrato_arquivo');
		if ($query->num_rows()) {
			$retornos = $query->result();
			foreach ($retornos as $rt) {
				if(strlen( substr($rt->file, -strlen($rt->file), 4)) == 8)
					array_shift($retornos);
			}
			return $retornos;
		}
		return false;
	}

	public function get_cliente_contratos($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('showtecsystem.contratos');
		if ($query->num_rows()) {
			$contratos = $query->result();
			foreach ($contratos as $contrato)
				$idcliente = $contrato->id_cliente;
			return $idcliente;
		}
		return false;
	}

	public function get_total_arquivos($idcontrato) {
		$this->db->where('ndoc',$idcontrato);
		$this->db->where('pasta','contratos');
		return $this->db->get('showtecsystem.contrato_arquivo')->num_rows();
	}

	public function digitalizacao_contrato($contrato,$descricao,$nome_arquivo) {
		$pasta = "contratos";
		$dados = array(
			'file' => $nome_arquivo,
			'descricao' => $descricao,
			'pasta' => $pasta,
			'ndoc' => $contrato

		);
		$resposta = $this->db->insert('showtecsystem.contrato_arquivo',$dados);
		if ($resposta)
			return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo);
		else
			return false;
	}

    public function get_users() {
        $this->db->where('status', 1)->order_by('nome', 'asc');
        $query = $this->db->get('showtecsystem.usuario');
        if ($query->num_rows())
            return $query->result();
        return false;
    }

	public function cadastrar_contrato($dados) {
		// VERIFICA SE CONTRATO JÁ ESTA CADASTRADO
		$this->db->where($dados);
		$resultado = $this->db->count_all_results('showtecsystem.contratos');

		if ($resultado == 0) {
			// GRAVA CONTRATO
			$resposta = $this->db->insert('showtecsystem.contratos',$dados);
			// ATIVA STATUS DO CLIENTE
			$atualiza_clie = $this->cliente->atualiza_status_cliente($dados['id_cliente'], 1);

			return $resposta;
		}

		return FALSE;

	}

	public function verificar_equipamentos_em_uso($id_contrato, $tipo_proposta) {
		/*$this->db->select('cad.*');
		$this->db->from('showtecsystem.cad_equipamentos as cad_equipamentos');
		$this->db->join('showtecsystem.contratos_veiculos as contratos_veiculos', 'contratos_veiculos.placa = cad_equipamentos.placa');
		$this->db->where('contratos_veiculos.id_contrato', $id_contrato);
		$this->db->where('cad_equipamentos.status', 5);
		*/
		if ($tipo_proposta == 1 ) {
			$query = $this->db->where(array('id_contrato_sim2m' => $id_contrato, 'status' => '1' ))
			->get('showtecsystem.cad_chips');
			if ($query->num_rows())
				return $query->result();

		}elseif ($tipo_proposta == 6 ) {
			$query = $this->db->where(array('id_contrato' => $id_contrato, 'status' => '1' ))
			->get('showtecsystem.cad_iscas');
			if ($query->num_rows())
				return $query->result();
		}else{
			$query = $this->db->where(array('id_contrato' => $id_contrato, 'status' => 'ativo' ))
			->get('showtecsystem.contratos_veiculos');
			if ($query->num_rows())
				return $query->result();
		}
		return false;
	}

	function close_contract($id_contrato) {
		$this->db->select('cad_equipamentos.*');
		$this->db->from('showtecsystem.cad_equipamentos as cad_equipamentos');
		$this->db->join('showtecsystem.contratos_veiculos as contratos_veiculos', 'contratos_veiculos.placa = cad_equipamentos.placa');
		$this->db->where('contratos_veiculos.id_contrato', $id_contrato);
		$this->db->where('cad_equipamentos.status', 5);
		$query = $this->db->get()->result();
		foreach ($query as $value) {
			$this->db->where('id', $value->id);
			$this->db->update('showtecsystem.cad_equipamentos', array('status' => '', 'placa' => ''))->result();
		}
	}

	public function verificar_contrato_cancelado($id_contrato) {
		$contratos = $this->get_contratos($id_contrato);
		foreach ($contratos as $contrato)
			$status = $contrato->status;
		if ($status == 3 || $status == 6)
			return true;
		else
			return false;
	}

	public function cancelar_contrato($id_contrato, $tipoCancelamento)	{
		$dados = array(	'status' => $tipoCancelamento);
		$this->db->where('id', $id_contrato);
		$resposta = $this->db->update('showtecsystem.contratos', $dados);
		if ($resposta) {
			$atualiza = $this->cliente->atualiza_status_cliente($id_contrato, 0); # 0 BLOQUEIA O CLIENTE
			return true;
		} else {
			return false;
		}
	}

	public function ativar_contrato($id_contrato)	{
		$dados = array('status' => 2);
		$this->db->where('id', $id_contrato);
		$resposta = $this->db->update('showtecsystem.contratos', $dados);
		if ($resposta) {
			$id_cliente = $this->cliente->atualiza_status_cliente($id_contrato, 1); # 1 ATIVA O CLIENTE
			return true;
		} else {
			return false;
		}
	}

	public function verificar_placa_serial($placa) {
		$this->db->select('serial');
		$this->db->distinct();
		$this->db->where('placa', $placa);
		$query = $this->db->get('systems.cadastro_veiculo');
		return $query->num_rows() ? $query->result() : false;
	}

	public function validar_vinculo($serial, $usu, $placa) {
		$usuario = false;
		$this->db->from('systems.cadastro_veiculo as veiculo');
		$this->db->join('showtecsystem.contratos_veiculos as contrato', 'contrato.placa = veiculo.placa');
		$this->db->where('contrato.status', 'ativo');
		$this->db->where('veiculo.serial', $serial);
		$total = $this->db->count_all_results();
		if ($total)
			$usuario = true;
		return $usuario;
	}

	public function countConsumo($id_cliente){
	    return $this->db->select('id')->where(array('id_cliente' => $id_cliente, 'consumo_fatura' => 1))->get('showtecsystem.contratos')->num_rows();
    }

    public function get_contratos_disponibilidade() {
	    return $this->db->select('id, id_cliente')->where(array('status' => 2, 'consumo_fatura' => 1))->get('contratos')->result();
    }

    public function getCountSimCards($idContrato) {
       return $this->db->where('id_contrato_sim2m', $idContrato)->get('showtecsystem.cad_chips')->num_rows();
    }

	/*
	* ATUALIZA O CONTRATO PARA CONTABILIZAR TAXA DE BOLETO
	*/
	public function updateTaxaBoleto($id, $status) {
	    return $this->db->update('showtecsystem.contratos', array('boleto' => $status), array('id' => $id));
    }

	/*
	* ATUALIZA O CONTRATO PARA CONSUMO DE FATURA (0,1)
	*/
	public function updateConsumoFatura($id, $status) {
	    return $this->db->update('showtecsystem.contratos', array('consumo_fatura' => $status, 'data_init_consumo' => date('Y-m-d')), array('id' => $id));
    }

	public function list_contratos_veic_join( $select='*', $where=array() ) {
		$query = $this->db->select($select)
				->join('showtecsystem.contratos c', 'c.id = cv.id_contrato')
				->where($where)
				->get('showtecsystem.contratos_veiculos cv');

		if($query->num_rows() > 0)
			return $query->result();

		return false;
	}

	//LISTA OS CONTRATOS ATIVOS E SEUS CLIENTES
	public function listContratosJoinClientes($select='*', $where) {
		# 1 = OS, 2 = ATIVOS, 4 = TESTE, 5 = BLOQUEADO
        $status = array(1, 2, 4, 5);
		$query = $this->db->select($select)
				->join('showtecsystem.cad_clientes cc', 'cc.id = c.id_cliente')
				->where_in('c.status', $status)
				->where($where)
				->get('showtecsystem.contratos c');

		if($query->num_rows()){
			return $query->result();
        }
		return false;
	}

	// public function get_contrato_veiculo($id_veic, $select = '*') {
	// 	$query = $this->db->select($select)
	// 			 ->where('code', $id_veic)
	// 			 ->order_by($campo_ordem, $ordem)
	// 			 ->get('showtecsystem.contratos');
	// 	return $query->row();
	// }

	/**
	 * Retorna o número de clientes ativos que possui o contrato de iscas
	 */
	public function get_contratos_ativos_iscas(){

		$sql = "SELECT count(distinct(cli.id)) as num FROM showtecsystem.contratos as con
		join showtecsystem.cad_clientes as cli on cli.id = con.id_cliente
		where con.tipo_proposta = 6 and con.status in (0,1,2) and cli.status = 1
		order by cli.id desc";

		$query = $this->db->query($sql.';')->result()[0]->num;
		return $query;

	}

	/*
    * RETORNA OS VALORES MENSAIS DEFINIDOS EM UM GRUPO DE CONTRATOS
    */
	public function listValores($contratos) {
		$query = $this->db->select('id, valor_mensal')
				->where_in('id', $contratos)
				->get('showtecsystem.contratos');

		if($query->num_rows() > 0)
			return $query->result();

		return false;
	}

	/*
    * RETORNA O QUANTITATIVO DE CONTRATO NO 'ANO/MES'
    */
	public function getQuantitativoContratos($di, $df, $prestadora) {
		$sql = "SELECT DATE_FORMAT(c.data_contrato, '%m/%Y') as data,
				count(distinct(c.id)) as qtdContratos
				FROM showtecsystem.contratos as c
				JOIN showtecsystem.cad_clientes as cc ON cc.id = c.id_cliente
				WHERE c.data_contrato >= '$di'
				AND c.data_contrato <= '$df'
				AND cc.informacoes in $prestadora
				AND c.tipo_proposta not in (1,4,6)
				GROUP BY DATE_FORMAT(c.data_contrato, '%Y/%m')";

		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->result();

		return false;
	}

	/*
    * RETORNA O QUANTITATIVO DE VEICULOS NO 'ANO/MES'
    */
	public function getQuantitativoVeiculos($di, $df, $prestadora) {
		$sql = "SELECT DATE_FORMAT(v.data_instalacao, '%m/%Y') as data,
				GROUP_CONCAT( v.status SEPARATOR ',' ) as status,
				count(v.code) as qtdVeiculos
				FROM systems.cadastro_veiculo as v
				INNER JOIN showtecsystem.cad_clientes as cc ON cc.id = v.id_usuario
				WHERE v.id_usuario in (
					SELECT c.id_cliente
				    FROM showtecsystem.contratos as c
				    WHERE c.tipo_proposta not in (1,4,6)
				)
				AND v.data_instalacao >= '$di'
				AND v.data_instalacao <= '$df'
				AND cc.informacoes in $prestadora
				GROUP BY DATE_FORMAT(v.data_instalacao, '%Y/%m')";

		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->result();

		return false;
	}

	/*
	* ATUALIZA O TIPO DO FATURAMENTO DO CONTRATO NO BANCO
	*/
	public function updateTipoFaturamento($id_contrato, $tipoConsumo) {
		$this->db->where('id', $id_contrato)->update('showtecsystem.contratos', array('consumo_fatura' => $tipoConsumo));
		if ($this->db->affected_rows() > 0) {
			return true;
		}
	    return false;
	}

	
	public function updateContratoVeiculoPorCliente($update, $idCliente)
	{
	    return $this->db->update('showtecsystem.contratos_veiculos', $update, array('id_cliente' => $idCliente));
	}

	public function getContratosVeiculos($where) {
		return $this->db->get_where('showtecsystem.contratos_veiculos', $where)->row();
	}

	/*
    * RETORNA OS VALORES MENSAIS DEFINIDOS EM UM GRUPO DE CONTRATOS
    */
	public function getContratosByIds($select='*', $id_contratos) {
		$query = $this->db->select($select)
				->where_in('id', $id_contratos)
				->get('showtecsystem.contratos');

		return $query->num_rows() > 0 ? $query->result() : false;
	}

	/*
    * PESQUISA CONTRATOS COM FILTRO - SELECT2 AJAX
    */
    public function listarContratosFilter($id_cliente, $search) {
		$this->db->select('id, status');
		if ($search) {
			$this->db->like('id', $search);
		}
		// Adicionar condição para excluir contratos com status '3', cancelados
		$this->db->where('status !=', 3);


    	return $this->db->where('id_cliente', $id_cliente)->order_by('id', 'asc')->get('showtecsystem.contratos')->result();
    }

}
