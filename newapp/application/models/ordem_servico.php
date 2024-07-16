<?php

date_default_timezone_set('America/Recife');

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ordem_servico extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('log_shownet');
		$this->load->model('contrato');
	}

	public function get_ordem_id($id, $data = false)
	{

		$this->db->select('os.*,instaladores.nome as nome_instalador');
		$this->db->from('showtecsystem.os as os');
		if ($data)
			$this->db->join('showtecsystem.cad_instaladores as instaladores', 'instaladores.id = os.id_instalador');
		else
			$this->db->join('showtecsystem.instaladores as instaladores', 'instaladores.id = os.id_instalador');
		$this->db->where('os.id', $id);

		$query = $this->db->get();

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function get_os($id)
	{
		$this->db->select('*');
		$this->db->from('showtecsystem.os');
		$this->db->where('id', $id);
		$query = $this->db->get();

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function trocaTecById($id_os, $id_tec)
	{
		$this->db->where('id', $id_os);
		$update = $this->db->update('showtecsystem.os', array('id_instalador' => $id_tec));

		return $update;
	}
	public function trocaPlaca($id_os, $placa){
		$this->db->where('oe.id_os', $id_os);
		$update = $this->db->update('showtecsystem.os_equipamentos oe', array('placa' => $placa));
		return $update;
	}
	public function get_usuario($id)
	{
		$this->db->select('*');
		$this->db->from('showtecsystem.usuario_gestor');
		$this->db->where('code', $id);
		$query = $this->db->get();

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function get_total_ordens($status)
	{
		if ($status == 0) {
			$this->db->where('status', 0);
		}
		if ($status == 1) {
			$this->db->where('status', 1);
		}

		$query = $this->db->get('showtecsystem.os');
		return $query->num_rows();
	}

	public function get_ordens($status = false, $start = 0, $limit = 10, $search = NULL)
	{
		$this->db->select('os.*,clientes.nome as nome_cliente, veic.placa, user.nome as nome_user');
		$this->db->from('showtecsystem.os as os');
		$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
		$this->db->join('showtecsystem.os_equipamentos as veic', 'os.id = veic.id_os');
		$this->db->join('showtecsystem.usuario as user', 'os.id_user = user.id', 'left');
		if ($status == 0) $this->db->where('os.status', 0);
		if ($status == 1) $this->db->where_in('os.status', array(1, 2));

		if ($search && is_numeric($search)) $this->db->like('os.id', $search);
		elseif ($search && is_string($search)) {
			$this->db->like('clientes.nome', $search);
			$this->db->or_like('veic.placa', $search);
		}

		$this->db->limit($limit, $start);
		$this->db->order_by('os.id', 'desc');
		$query = $this->db->get();

		return $query->result();
	}
	
	/*
	* implementando get para datatable
	*/
	var $column_search = array("clientes.nome", "veic.placa", "os.id");

	public function get_os_datatable($status = false){
		$length = $this->input->post("length");
		$start = $this->input->post("start");
		$search = NULL;
		if ($this->input->post("search")) {
			$search = $this->input->post("search")["value"];
		}
		$this->db->select('os.*,clientes.nome as nome_cliente, veic.placa, user.nome as nome_user');
		$this->db->from('showtecsystem.os as os');
		$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
		$this->db->join('showtecsystem.os_equipamentos as veic', 'os.id = veic.id_os');
		$this->db->join('showtecsystem.usuario as user', 'os.id_user = user.id', 'left');
		if ($status == 0) $this->db->where('os.status', 0);
		if ($status == 1) $this->db->where_in('os.status', array(1, 2));
		//die(json_encode(array($search)));
		if (isset($search) && $search != "") {
			$first = TRUE;
			foreach ($this->column_search as $field) {
				if ($first) {
					//$this->db->group_start();
					$this->db->like($field, $search);
					$first = FALSE;
				} else {
					$this->db->or_like($field, $search);
				}
			}
			if (!$first) {
				//$this->db->group_end();
			}
		}
		if (isset($length) && $length != -1) {
			$this->db->limit($length, $start);
		}
		
		return $this->db->get()->result();
	}

	public function get_os_datatable_novo($number, $startRow, $endRow, $placa = false) {
        $this->db->select('DISTINCT os.id, os.*, clientes.nome as nome_cliente, veic.placa, user.nome as nome_user', false);
        $this->db->from('showtecsystem.os as os');
        $this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
        $this->db->join('showtecsystem.os_equipamentos as veic', 'os.id = veic.id_os');
        $this->db->join('showtecsystem.usuario as user', 'os.id_user = user.id', 'left');

        if ($number === "0") {
            $this->db->where('os.status', 0);
        } elseif ($number === "1") {
            $this->db->where_in('os.status', array(1, 2));
        }

		if ($placa != false){
			$this->db->where('veic.placa', $placa);
		}

        $length = $endRow - $startRow;
        $this->db->limit($length, $startRow);

        $query = $this->db->get();
        return $query->result();
    }

    public function records_total($status = false, $placa = false) {
        $this->db->select('DISTINCT os.id, os.*, clientes.nome as nome_cliente, veic.placa, user.nome as nome_user', false);
        $this->db->from('showtecsystem.os as os');
        $this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
        $this->db->join('showtecsystem.os_equipamentos as veic', 'os.id = veic.id_os');
        $this->db->join('showtecsystem.usuario as user', 'os.id_user = user.id', 'left');

        // Adiciona alias 'os' à coluna 'status'
        if ($status === "0") {
            $this->db->where('os.status', 0);
        } elseif ($status === "1") {
            $this->db->where_in('os.status', array(1, 2));
        }

		if ($placa != false){
			$this->db->where('veic.placa', $placa);
		}

        return $this->db->count_all_results();
    }

    public function records_filtered($status = false, $placa = false) {
        $search = NULL;
		if ($this->input->post("search")) {
			$search = $this->input->post("search")["value"];
		}
        
        $this->db->select('DISTINCT os.id, os.*, clientes.nome as nome_cliente, veic.placa, user.nome as nome_user', false);
        $this->db->from('showtecsystem.os as os');
        $this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
        $this->db->join('showtecsystem.os_equipamentos as veic', 'os.id = veic.id_os');
        $this->db->join('showtecsystem.usuario as user', 'os.id_user = user.id', 'left');
        
        // Adiciona alias 'os' à coluna 'status'
        if ($status === "0") {
            $this->db->where('os.status', 0);
        } elseif ($status === "1") {
            $this->db->where_in('os.status', array(1, 2));
        }

		if ($placa != false){
			$this->db->where('veic.placa', $placa);
		}

        // Aplicação de filtro de pesquisa
        if ($search) {
            $this->apply_search_filter($search);
        }

        return $this->db->count_all_results();
    }

    private function apply_search_filter($search) {
        $this->db->group_start();
        $first = true;
        foreach ($this->column_search as $field) {
            if ($first) {
                $this->db->like($field, $search);
                $first = false;
            } else {
                $this->db->or_like($field, $search);
            }
        }
        $this->db->group_end();
    }

	/*
	* Retorna quantidade de registros da query
	*/
	public function countAll_filter($where=NULL, $search=NULL)
	{
		$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
		$this->db->join('showtecsystem.os_equipamentos as veic', 'os.id = veic.id_os');
		$this->db->join('showtecsystem.usuario as user', 'os.id_user = user.id', 'left');
		if ($where) $this->db->where($where);

		if ($search && is_numeric($search)) $this->db->like('os.id', $search);
		elseif ($search && is_string($search)) {
			$this->db->like('clientes.nome', $search);
			$this->db->or_like('veic.placa', $search);

			if ($where) $this->db->where($where);
		}

		return $this->db->count_all_results('showtecsystem.os as os');
	}

	/*
	* Retorna total de ordens de servicos
	*/
	public function get_totAll($where=NULL)
	{
		if ($where)
			$this->db->where($where);
		return $this->db->count_all_results('showtecsystem.os');
	}

    public function get_ordens_cliente_new($id_cliente)
    {
        $this->db->select('os.id, os.tipo_os, os.quantidade_equipamentos, os.data_cadastro, os.status, os.id_contrato ,clientes.nome as nome_cliente, usuario.nome_usuario' );
        $this->db->from('showtecsystem.os as os');
        $this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
        $this->db->join('showtecsystem.usuario_gestor as usuario', 'usuario.code = os.id_usuario');
        $this->db->order_by('os.id', 'desc');
        $this->db->where('clientes.id', $id_cliente);
        $query = $this->db->get();

        if ($query->num_rows()){
            return $query->result();
        }
    }

	public function get_ordens_cliente($limit, $offset, $status, $id_cliente)
	{
		$this->db->select('os.*,clientes.nome as nome_cliente');
		$this->db->from('showtecsystem.os as os');
		$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
		if ($status == 0) {
			$this->db->where('os.status', 0);
		}
		if ($status == 1) {
			$this->db->where('os.status', 1);
		}

		$this->db->order_by('os.id', 'desc');
		$this->db->where('clientes.id', $id_cliente);

		if ($limit && $offset) {
			$this->db->limit($offset, $limit);
		}

		$query = $this->db->get();

		if ($query->num_rows()) {

			return $query->result();

		}

		return false;
	}

	public function visualizar_os($os)
	{
		$this->db->select('file');
		$this->db->where('pasta', 'OS');
		$this->db->where('ndoc', $os);
		$query = $this->db->get('showtecsystem.arquivos');
		if ($query->num_rows()) {
			foreach ($query->result() as $arquivo) {
				return $arquivo->file;
			}
		}
		return false;
	}

	public function get_total_contratos($status)
	{
		$this->db->or_where('status', 1);
		$this->db->or_where('status', 2);
		if ($status == 0) {
			$this->db->or_where('status', 0);
		}

		$query = $this->db->get('showtecsystem.contratos');
		return $query->num_rows();
	}

	public function get_contratos($limit, $offset, $status)
	{

		$this->db->select('contratos.*,clientes.nome as nome_cliente');
		$this->db->from('showtecsystem.contratos as contratos');
		$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = contratos.id_cliente');
		$this->db->where('contratos.status', $status);

		$this->db->or_where('contratos.status', 1);
		$this->db->or_where('contratos.status', 2);
		if ($status == 0) {
			$this->db->or_where('contratos.status', 0);
		}

		$this->db->order_by('contratos.id', 'desc');
		$this->db->limit($offset, $limit);
		$query = $this->db->get();

		if ($query->num_rows()) {

			return $query->result();

		}

		return false;
	}

	public function get_total_contratos_search($status = null, $pesquisa = null)
	{
		try {
			$this->db->select('COUNT(*) as total');
			$this->db->from('showtecsystem.contratos as contratos');
			$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = contratos.id_cliente');

			if ($pesquisa) {
				if ($pesquisa['coluna'] == "contratos.id") {
					$this->db->where($pesquisa['coluna'], $pesquisa['palavra']);
				} else {
					$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);
				}
			}

			if ($status == 0) {
				$stat = array('0', '1', '2', '7');
			} else {
				$stat = array('1', '2', '7');
			}

			$this->db->where_in('contratos.status', $stat);

			$query = $this->db->get();

			if ($query->num_rows()) {
				return $query->row()->total;
			} else {
				return 0;
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function get_contratos_paginado($limit, $offset, $status = null, $pesquisa = null)
	{

		try {

			$this->db->select('contratos.*,clientes.nome as nome');
			$this->db->from('showtecsystem.contratos as contratos');
			$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = contratos.id_cliente');

			if ($pesquisa) {
				if ($pesquisa['coluna'] == "contratos.id") {
					$this->db->where($pesquisa['coluna'], $pesquisa['palavra']);
				} else {
					$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);
				}
			}

			if ($status == 0) {
				$stat = array('0', '1', '2', '7');
			} else {
				$stat = array('1', '2', '7');
			}

			$this->db->where_in('contratos.status', $stat);
			
			$this->db->order_by('contratos.id', 'desc');
			$this->db->limit($limit, $offset);

			$query = $this->db->get();

			if ($query->num_rows()) {
				$lastRow = $this->get_total_contratos_search($status, $pesquisa);
				if ($lastRow !== false) {
					return array(
						"success" => true,
						"rows" => $query->result(),
						"lastRow" => (int) $lastRow
					);
				} else {
					return false;
				}
			} else {
				return array(
					"success" => false,
					"rows" => [],
					"lastRow" => 0
				);
			}
		} catch (Exception $e) {
			return false;
		}

	}

	public function contratos_id($id_contrato)
	{
		$this->db->where('id', $id_contrato);
		$query = $this->db->get('showtecsystem.contratos');

		if ($query->num_rows()) {

			return $query->result();

		}

		return false;
	}

	public function get_cliente($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('showtecsystem.cad_clientes');

		if ($query->num_rows()) {

			return $query->result();

		}

		return false;
	}

	public function listar_pesquisa_os($pesquisa, $status)
	{

		$lista_os = false;

		if ($pesquisa['coluna'] == "id") {

			$this->db->like('os.' . $pesquisa['coluna'], $pesquisa['palavra']);
			$this->db->select('os.*,clientes.nome as nome_cliente');
			$this->db->from('showtecsystem.os as os');
			$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
			if ($status == 0) {
				$this->db->where('os.status', 0);
			}
			if ($status == 1) {
				$this->db->where('os.status', 1);
			}

			$query = $this->db->get();

			if ($query->num_rows()) {

				return $query->result();
			}
			return false;

		} else {

			$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);

			$query = $this->db->get('showtecsystem.cad_clientes');

			if ($query->num_rows()) {

				$clientes = $query->result();

				foreach ($clientes as $cliente) {
					$this->db->select('os.*,clientes.nome as nome_cliente');
					$this->db->from('showtecsystem.os as os');
					$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = os.id_cliente');
					$this->db->where('os.id_cliente', $cliente->id);
					if ($status == 0) {
						$this->db->where('os.status', 0);
					}
					if ($status == 1) {
						$this->db->where('os.status', 1);
					}

					$query = $this->db->get();

					if ($query->num_rows()) {
						foreach ($query->result() as $os) {
							$lista_os[] = $os;
						}
					}
				}
				return $lista_os;
			}

		}

	}

	public function listar_pesquisa_contratos($pesquisa, $status)
	{

		$lista_contratos_novos = false;

		if ($pesquisa['coluna'] == "id") {

			$this->db->like('contratos.' . $pesquisa['coluna'], $pesquisa['palavra']);
			$this->db->select('contratos.*,clientes.nome as nome_cliente');
			$this->db->from('showtecsystem.contratos as contratos');
			$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = contratos.id_cliente');

			if ($status == 0) {
				$stat = array('0', '1', '2');
			} else {
				$stat = array('1', '2', '7');
			}

			$this->db->where_in('contratos.status', $stat);

			$query = $this->db->get();

			if ($query->num_rows()) {

				return $query->result();
			}
			return false;

		} else {

			$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);

			$query = $this->db->get('showtecsystem.cad_clientes');

			if ($query->num_rows()) {

				$clientes = $query->result();

				foreach ($clientes as $cliente) {
					$this->db->select('contratos.*,clientes.nome as nome_cliente');
					$this->db->from('showtecsystem.contratos as contratos');
					$this->db->join('showtecsystem.cad_clientes as clientes', 'clientes.id = contratos.id_cliente');

					if ($status == 0) {
						$stat = array('0', '1', '2');
					} else {
						$stat = array('1', '2');
					}

					$this->db->where_in('contratos.status', $stat);

					$this->db->where('contratos.id_cliente', $cliente->id);
					$query = $this->db->get();

					if ($query->num_rows()) {
						foreach ($query->result() as $contrato) {
							$lista_contratos_novos[] = $contrato;
						}
					}
				}
				return $lista_contratos_novos;
			}

		}

	}

	public function get_cliente_ordem_id($id)
	{

		$this->db->select('clientes.*');
		$this->db->from('showtecsystem.cad_clientes as clientes');
		$this->db->join('showtecsystem.os as os', 'os.id_cliente = clientes.id');
		$this->db->where('os.id', $id);

		$query = $this->db->get();

		if ($query->num_rows()) {

			return $query->result();

		}

		return false;
	}

	public function get_instaladores_ativos()
	{
		$this->db->where('status', 1);
		$query = $this->db->get('showtecsystem.cad_instaladores');

		if ($query->num_rows()) {

			return $query->result();

		}

		return false;
	}

	public function get_equipamentos_os($id)
	{
		$this->db->select('cad_equipamentos.serial,os_equipamentos.placa');
		$this->db->from('showtecsystem.cad_equipamentos as cad_equipamentos');
		$this->db->join(
			'showtecsystem.os_equipamentos as os_equipamentos',
			'os_equipamentos.id_equipamento = cad_equipamentos.id'
		);
		$this->db->where('os_equipamentos.id_os', $id);

		$query = $this->db->get();

		if ($query->num_rows()) {

			return $query->result();
		}
		return false;
	}

	public function get_equipamentos_fechar($id)
	{
		$this->db->select('cad_equipamentos.*,os_equipamentos.id as id_osequipamentos,
		    os_equipamentos.id_os as id_os_osequipamentos, os_equipamentos.placa as board');
		$this->db->from('showtecsystem.cad_equipamentos as cad_equipamentos');
		$this->db->join(
			'showtecsystem.os_equipamentos as os_equipamentos',
			'os_equipamentos.id_equipamento = cad_equipamentos.id'
		);
		$this->db->where('os_equipamentos.id_os', $id);

		$query = $this->db->get();

		if ($query->num_rows()) {

			return $query->result();
		}
		return false;
	}

	public function equipamentos_os_instalacao($id_contrato)
	{
		$this->db->select('quantidade_equipamentos');
		$this->db->where('id_contrato', $id_contrato);
		$this->db->where('tipo_os', 1);
		$query = $this->db->get('showtecsystem.os');

		if ($query->num_rows()) {

			$quant_equipamentos = $query->result();
			$quantidade_equipamentos = 0;

			foreach ($quant_equipamentos as $quant) {
				$quantidade_equipamentos = $quantidade_equipamentos + $quant->quantidade_equipamentos;
			}

			return $quantidade_equipamentos;
		}

		return false;
	}

	public function get_equipamentos_disponiveis()
	{

		$this->db->where('status', 1);
		$this->db->order_by('serial', 'desc');
		$query = $this->db->get('showtecsystem.cad_equipamentos');

		if ($query->num_rows()) {

			return $query->result();
		}
		return false;
	}

	public function get_equipamentos_serial($serial)
	{

		$this->db->where('serial', $serial);
		$query = $this->db->get('showtecsystem.cad_equipamentos');

		if ($query->num_rows()) {

			foreach ($query->result() as $equipamento) {
				$id_equipamento = $equipamento->id;
			}
			return $id_equipamento;

		}
		return false;
	}

	public function get_equipamentos_disponiveis_json()
	{
		$this->db->select('serial');
		$this->db->where('status', 1);
		$this->db->order_by('serial', 'desc');
		$query = $this->db->get('showtecsystem.cad_equipamentos',0,1000);

		if ($query->num_rows()) {
			foreach ($query->result() as $equipamento) {
				$equipamentos[] = $equipamento->serial;
			}
			return json_encode($equipamentos);
		}
		return false;
	}

	public function instalado_modulos($id_osequipamentos, $id_os, $modulo, $id_cliente, $acao, $contrato, $placa)
	{

		$status_cad_equipamentos = 4;

		$dados_cad_equipamentos = array(
			'status' => $status_cad_equipamentos,
			'placa' => $placa

		);

		$this->db->where('id', $modulo);
		$resultado = $this->db->update('showtecsystem.cad_equipamentos', $dados_cad_equipamentos);

		if ($resultado) {

			$status_contratos = 2;

			$dados_contratos = array(
				'status' => $status_contratos

			);

			$this->db->where('id', $contrato);
			$resultado3 = $this->db->update('showtecsystem.contratos', $dados_contratos);

			if ($resultado3) {


				$status_cad_equipamentos_instalador = 2;

				$dados_cad_equipamentos_instalador = array(
					'status' => $status_cad_equipamentos_instalador

				);

				$this->db->where('id_equipamento', $modulo);
				$resultado4 = $this->db->update('showtecsystem.cad_equipamentos_instalador', $dados_cad_equipamentos_instalador);

				if ($resultado4) {

					$cliente = $this->get_cliente($id_cliente);

					foreach ($clientes as $cliente) {
						$nome_cliente = $cliente->nome;
					}


					$status_cad_equipamentos_historico = 2;
					$ocorrencia_cad_equipamentos_historico = "Instalado OS [ " . $id_os . " ] Cliente [ " . $nome_cliente . " ]";

					$dados_cad_equipamentos_historico = array(
						'id_equipamento' => $modulo,
						'ocorrencia' => $ocorrencia_cad_equipamentos_historico,
						'status' => $status_cad_equipamentos_historico

					);

					$resultado5 = $this->db->insert('showtecsystem.cad_equipamentos_historico', $dados_cad_equipamentos_historico);

					if ($resultado5) {

						$dados_os_equipamentos = array(
							'status' => $acao

						);

						$this->db->where('id', $id_osequipamentos);
						$resultado6 = $this->db->update('showtecsystem.os_equipamentos', $dados_os_equipamentos);

						if ($resultado6) {

							return true;

						} else {
							return false;
						}

					} else {
						return false;
					}

				} else {
					return false;
				}

			} else {
				return false;
			}



		} else {
			return false;
		}

	}

	public function devolver_modulos($id_os, $modulo, $id_instalador, $status)
	{

		$placa_cad_equipamentos = "";

		$dados_cad_equipamentos = array(
			'status' => $status,
			'placa' => $placa_cad_equipamentos

		);

		$this->db->where('id', $modulo);
		$resultado = $this->db->update('showtecsystem.cad_equipamentos', $dados_cad_equipamentos);

		if ($resultado) {

			$status_os_equipamentos = 2;

			$dados_os_equipamentos = array(
				'status' => $status_os_equipamentos

			);

			$this->db->where('id_os', $id_os);
			$this->db->where('id_equipamento', $modulo);
			$resultado2 = $this->db->update('showtecsystem.os_equipamentos', $dados_os_equipamentos);

			if ($resultado2) {

				$st = 1;
				if ($status == 1 || $status == 2) {
					$st = 0;
				}

				$dados_cad_equipamentos_instalador = array(
					'status' => $st

				);

				$this->db->where('status', 2);
				$this->db->where('id_instalador', $id_instalador);
				$this->db->where('id_equipamento', $modulo);
				$resultado3 = $this->db->update('showtecsystem.cad_equipamentos_instalador', $dados_cad_equipamentos_instalador);

				if ($resultado3) {

					return true;
				} else {

					return false;
				}

			} else {

				return false;
			}

		} else {

			return false;
		}

	}

	public function gerar_os_instalacao($dados_os, $veiculo)
	{

		$gravar_os = $this->db->insert('showtecsystem.os', $dados_os);
		$id_os = $this->db->insert_id();
		$status_os_equipamentos = 0;
		$status_cad_equipamentos = 3;
		$status_cad_equipamentos_historico = 3;
		$status_cad_equipamentos_instalador = 2;
		$status_contratos = 1;
		$status_contratos_historico = 1;

		$contrato_info = $this->contrato->get_contrato_status($dados_os['id_contrato']);
        $contrato_status_antigo = $contrato_info[0]->status;

		if ($gravar_os) {

			$quant_veic = $dados_os['quantidade_equipamentos'];

			for ($i = 0; $i < $quant_veic; $i++) {
				if ($quant_veic == 1) {
					$placa = $veiculo['placa'];
					$serial = $veiculo['serial'];
				}

				$id_equipamento = $this->get_equipamentos_serial($serial);
				// Cadastra modulo
				$dados_os_equipamentos = array(

					'id_os' => $id_os,
					'id_equipamento' => $id_equipamento,
					'id_contrato' => $dados_os['id_contrato'],
					'placa' => $placa,
					'serial' => $serial,
					'status' => $status_os_equipamentos,
					'data_cadastro' => $dados_os['data_cadastro']

				);

				$this->db->insert('showtecsystem.os_equipamentos', $dados_os_equipamentos);

				$dados_cad_equipamentos = array(
					'status' => $status_cad_equipamentos
				);

				$this->db->where('id', $id_equipamento);
				$this->db->update('showtecsystem.cad_equipamentos', $dados_cad_equipamentos);

				$ocorrencia_cad_equipamentos_historico = "Enviado para instalacao - OS: " . $id_os;

				$dados_cad_equipamentos_historico = array(
					'id_equipamento' => $id_equipamento,
					'status' => $status_cad_equipamentos_historico,
					'ocorrencia' => $ocorrencia_cad_equipamentos_historico
				);

				$this->db->insert('showtecsystem.cad_equipamentos_historico', $dados_cad_equipamentos_historico);

				$dados_cad_equipamentos_instalador = array(
					'id_instalador' => $dados_os['id_instalador'],
					'id_equipamento' => $id_equipamento,
					'data_cadastro' => $dados_os['data_cadastro'],
					'status' => $status_cad_equipamentos_instalador
				);

				$this->db->insert('showtecsystem.cad_equipamentos_instalador', $dados_cad_equipamentos_instalador);

			}

			$dados_contratos = array(
				'status' => $status_contratos
			);

			$this->db->where('id', $dados_os['id_contrato']);
			$this->db->update('showtecsystem.contratos', $dados_contratos);

			$ocorrecia_contratos_historico = "Gerada a OS de Instalacao - " . $id_os;

			$dados_contratos_historico = array(
				'id_contrato' => $dados_os['id_contrato'],
				'status' => $status_contratos_historico,
				'ocorrencia' => $ocorrecia_contratos_historico

			);	

			$id_user = $this->auth->get_login_dados('user');
            $id_user = (int) $id_user;
            $this->log_shownet->gravar_log($id_user, 'contratos_historico', 0, 'criar', '', $dados_contratos_historico);
			$this->log_shownet->gravar_log($id_user, 'contratos', $dados_os['id_contrato'], 'atualizar', 'status: '.$contrato_status_antigo, $dados_contratos);

			$this->db->insert('showtecsystem.contratos_historico', $dados_contratos_historico);

			return $id_os;
		} else {
			return false;
		}
	}

	public function gerar_os_manutencao($dados_os, $veiculo, $serial_ret)
	{

		$gravar_os = $this->db->insert('showtecsystem.os', $dados_os);
		$id_os = $this->db->insert_id();
		$status_os_equipamentos = 0;
		$status_cad_equipamentos = 3;
		$status_cad_equipamentos_historico = 3;
		$status_cad_equipamentos_instalador = 2;
		$status_contratos = 1;
		$status_contratos_historico = 1;

		$contrato_info = $this->contrato->get_contrato_status($dados_os['id_contrato']);
        $contrato_status_antigo = $contrato_info[0]->status;

		if ($gravar_os) {

			$quant_veic = $dados_os['quantidade_equipamentos'];

			for ($i = 0; $i <= $quant_veic - 1; $i++) {
				if ($quant_veic == 1) {
					$placa = $veiculo['placa'];
					$serial = $veiculo['serial'];
				} else {
					$placa = $veiculo['placa' . $i];
					$serial = $veiculo['serial' . $i];
				}

				$id_equipamento = $this->get_equipamentos_serial($serial);

				// Cadastra modulo
				$dados_os_equipamentos = array(
					'id_os' => $id_os,
					'id_equipamento' => $id_equipamento,
					'id_contrato' => $dados_os['id_contrato'],
					'placa' => $placa,
					'serial' => $serial,
					'status' => $status_os_equipamentos,
					'data_cadastro' => $dados_os['data_cadastro'],
					'serial_retirado' => $serial_ret


				);

				$this->db->insert('showtecsystem.os_equipamentos', $dados_os_equipamentos);

				$dados_cad_equipamentos = array(
					'status' => $status_cad_equipamentos
				);

				$this->db->where('id', $id_equipamento);
				$this->db->update('showtecsystem.cad_equipamentos', $dados_cad_equipamentos);

				$ocorrencia_cad_equipamentos_historico = "Enviado para manutenção - OS: " . $id_os;

				$dados_cad_equipamentos_historico = array(
					'id_equipamento' => $id_equipamento,
					'status' => $status_cad_equipamentos_historico,
					'ocorrencia' => $ocorrencia_cad_equipamentos_historico


				);

				$this->db->insert('showtecsystem.cad_equipamentos_historico', $dados_cad_equipamentos_historico);

				$dados_cad_equipamentos_instalador = array(
					'status' => $status_cad_equipamentos_instalador
				);

				$this->db->where('status', 1);
				$this->db->where('id_equipamento', $id_equipamento);
				$this->db->update('showtecsystem.cad_equipamentos_instalador', $dados_cad_equipamentos_instalador);

			}


			$dados_contratos = array(
				'status' => $status_contratos
			);

			$this->db->where('id', $dados_os['id_contrato']);
			$this->db->update('showtecsystem.contratos', $dados_contratos);

			$ocorrecia_contratos_historico = "Gerada a OS de Manutenção - " . $id_os;

			$dados_contratos_historico = array(
				'id_contrato' => $dados_os['id_contrato'],
				'status' => $status_contratos_historico,
				'ocorrencia' => $ocorrecia_contratos_historico

			);

			$id_user = $this->auth->get_login_dados('user');
            $id_user = (int) $id_user;
            $this->log_shownet->gravar_log($id_user, 'contratos_historico', 0, 'criar', '', $dados_contratos_historico);
			$this->log_shownet->gravar_log($id_user, 'contratos', $dados_os['id_contrato'], 'atualizar', 'status: '.$contrato_status_antigo, $dados_contratos);

			$this->db->insert('showtecsystem.contratos_historico', $dados_contratos_historico);
			return $id_os;

		} else {

			return false;
		}

	}

	public function gerar_os_troca($dados_os, $veiculo)
	{

		$gravar_os = $this->db->insert('showtecsystem.os', $dados_os);
		$id_os = $this->db->insert_id();

		$status_os_equipamentos = 0;
		$status_cad_equipamentos = 3;
		$status_cad_equipamentos_historico = 3;
		$status_cad_equipamentos_instalador = 2;
		$status_contratos = 1;
		$status_contratos_historico = 1;

		$contrato_info = $this->contrato->get_contrato_status($dados_os['id_contrato']);
        $contrato_status_antigo = $contrato_info[0]->status;

		if ($gravar_os) {

			$quant_veic = $dados_os['quantidade_equipamentos'];

			for ($i = 0; $i <= $quant_veic - 1; $i++) {

				if ($quant_veic == 1) {
					$placa = $veiculo['placa'];
					$serial = $veiculo['serial'];
				} else {
					$placa = $veiculo['placa' . $i];
					$serial = $veiculo['serial' . $i];
				}

				$id_equipamento = $this->get_equipamentos_serial($serial);

				$dados_os_equipamentos = array(
					'id_os' => $id_os,
					'id_equipamento' => $id_equipamento,
					'id_contrato' => $dados_os['id_contrato'],
					'placa' => $placa,
					'serial' => $serial,
					'status' => $status_os_equipamentos,
					'data_cadastro' => $dados_os['data_cadastro']
				);

				$this->db->insert('showtecsystem.os_equipamentos', $dados_os_equipamentos);

				$dados_cad_equipamentos = array(
					'status' => $status_cad_equipamentos
				);

				$this->db->where('id', $id_equipamento);
				$this->db->update('showtecsystem.cad_equipamentos', $dados_cad_equipamentos);

				$ocorrencia_cad_equipamentos_historico = "Enviado para troca - OS: " . $id_os;

				$dados_cad_equipamentos_historico = array(
					'id_equipamento' => $id_equipamento,
					'status' => $status_cad_equipamentos_historico,
					'ocorrencia' => $ocorrencia_cad_equipamentos_historico
				);

				$this->db->insert('showtecsystem.cad_equipamentos_historico', $dados_cad_equipamentos_historico);

			}


			$dados_contratos = array(
				'status' => $status_contratos
			);

			$this->db->where('id', $dados_os['id_contrato']);
			$this->db->update('showtecsystem.contratos', $dados_contratos);

			$ocorrecia_contratos_historico = "Gerada a OS de Troca - " . $id_os;

			$dados_contratos_historico = array(
				'id_contrato' => $dados_os['id_contrato'],
				'status' => $status_contratos_historico,
				'ocorrencia' => $ocorrecia_contratos_historico

			);

			$id_user = $this->auth->get_login_dados('user');
            $id_user = (int) $id_user;
			$this->log_shownet->gravar_log($id_user, 'contratos_historico', 0, 'criar', '', $dados_contratos_historico);
			$this->log_shownet->gravar_log($id_user, 'contratos', $dados_os['id_contrato'], 'atualizar', 'status: '.$contrato_status_antigo, $dados_contratos);
			$this->db->insert('showtecsystem.contratos_historico', $dados_contratos_historico);
			return $id_os;

		} else {
			return false;
		}



	}

	public function gerar_os_retirada($dados_os, $veiculo)
	{

		$gravar_os = $this->db->insert('showtecsystem.os', $dados_os);
		$id_os = $this->db->insert_id();

		$status_os_equipamentos = 0;
		$status_cad_equipamentos = 3;
		$status_cad_equipamentos_historico = 3;
		$status_cad_equipamentos_instalador = 2;
		$status_contratos = 7; //Mantém o status como "em processo de retirada".
		$status_contratos_historico = 7; //Mantém o status como "em processo de retirada".
		$status_aguardando_OS = 1; //Mantém o status como "Aguardando OS".

		$contrato_info = $this->contrato->get_contrato_status($dados_os['id_contrato']);
        $contrato_status_antigo = $contrato_info[0]->status;

		//ID do usuário logado
		$id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

		if ($gravar_os) {

			$quant_veic = $dados_os['quantidade_equipamentos'];

			for ($i = 0; $i <= $quant_veic - 1; $i++) {

				if ($quant_veic == 1) {
					$placa = $veiculo['placa'];
					$serial = $veiculo['serial'];
				} else {
					$placa = $veiculo['placa' . $i];
					$serial = $veiculo['serial' . $i];
				}

				$id_equipamento = $this->get_equipamentos_serial($serial);

				$dados_os_equipamentos = array(
					'id_os' => $id_os,
					'id_equipamento' => $id_equipamento,
					'id_contrato' => $dados_os['id_contrato'],
					'placa' => $placa,
					'serial' => $serial,
					'status' => $status_os_equipamentos,
					'data_cadastro' => $dados_os['data_cadastro']
				);

				$this->db->insert('showtecsystem.os_equipamentos', $dados_os_equipamentos);

				$ocorrencia_cad_equipamentos_historico = "Solicitação de retirada - OS: " . $id_os;

				$dados_cad_equipamentos_historico = array(
					'id_equipamento' => $id_equipamento,
					'status' => $status_cad_equipamentos_historico,
					'ocorrencia' => $ocorrencia_cad_equipamentos_historico
				);

				$this->db->insert('showtecsystem.cad_equipamentos_historico', $dados_cad_equipamentos_historico);

			}

			$dados_contratos = array(
				'status' => $status_aguardando_OS
			);

			$this->db->where('id', $dados_os['id_contrato']);
			$this->db->update('showtecsystem.contratos', $dados_contratos);
			$this->log_shownet->gravar_log($id_user, 'contratos', $dados_os['id_contrato'], 'atualizar', 'status: '.$contrato_status_antigo, $dados_contratos);

			$ocorrecia_contratos_historico = "Gerada a OS de Retirada - " . $id_os;

			$dados_contratos_historico = array(
				'id_contrato' => $dados_os['id_contrato'],
				'status' => $status_contratos_historico,
				'ocorrencia' => $ocorrecia_contratos_historico

			);

			$id_user = $this->auth->get_login_dados('user');
            $id_user = (int) $id_user;
            $this->log_shownet->gravar_log($id_user, 'contratos_historico', 0, 'criar', '', $dados_contratos_historico);

			$this->db->insert('showtecsystem.contratos_historico', $dados_contratos_historico);
			return $id_os;

		} else {
			return false;
		}
	}

	public function digitalizacao_os($numero_os, $cliente, $nome_arquivo, $weekly)
	{
		$descricao = "OS " . $cliente;
		$pasta = "OS";
		$status_os = 1;

		$dados = array(
			'file' => $nome_arquivo,
			'descricao' => $descricao,
			'pasta' => $pasta,
			'ndoc' => $numero_os,
			'path' => '',
			'status' => 'ativo'

		);

		if ($weekly != 2) {
			$dados2 = array(
				'status' => $status_os,
				'weekly' => 1
			);
		} else {
			$dados2 = array(
				'status' => $status_os
			);
		}


		$resposta = $this->db->insert('showtecsystem.arquivos', $dados);
		$this->db->where('id', $numero_os);
		$resposta2 = $this->db->update('showtecsystem.os', $dados2);

		if ($resposta and $resposta2) {
			return true;
		} else {
			return false;
		}
	}

	public function estornar_os($numero_os, $id_cliente, $equipamentos)
	{
		$dados = array('status' => 2, 'data_fechamento' => date('Y-m-d H:i'));
		$this->db->where('id_cliente', $id_cliente);
		$this->db->where('id', $numero_os);
		$resposta = $this->db->update('showtecsystem.os', $dados);

		if ($resposta) {
			$log = array('statusOS' => 9, 'tipoRetorno' => 'estorno');

			foreach ($equipamentos as $equipamento) {
				$this->db->where('statusOS !=', 5);
				$this->db->where('placaOrdem', $equipamento->placa);
				$this->db->update('showtecsystem.cad_logistica', $log);
			}
			return true;

		} else {
			return false;
		}
	}

	public function verificar_placa($placa, $id_contrato, $id_cliente, $modulo)
	{
		$status_contrato = $this->verficar_status_contrato($id_contrato);

		if ($status_contrato == 3) {
			$resultado = array(
				'status' => 'erro',
				'msg' => 'Contrato ' . $id_contrato . ' está cancelado'
			);
			return $resultado;

		} else if ($status_contrato == 5) {
			$resultado = array(
				'status' => 'erro',
				'msg' => 'Contrato ' . $id_contrato . ' está bloqueado'
			);
			return $resultado;

		} else if ($status_contrato == 6) {
			$resultado = array(
				'status' => 'erro',
				'msg' => 'Contrato ' . $id_contrato . ' está encerrado'
			);
			return $resultado;

		} else {

			$resultado = $this->verificar_contratos_veiculos($placa, $id_contrato, $id_cliente);

			if ($resultado['status'] == 'ok') {
				$placa_resultado = $this->verificar_placa_cad_equipamentos($placa, $modulo);
				return $placa_resultado;

			} else {
				return $resultado;

			}

		}


	}

	// Verificar status de cada contrato
	public function verficar_status_contrato($contrato)
	{
		$this->db->where('id', $contrato);
		$query = $this->db->get('showtecsystem.contratos');

		if ($query->num_rows()) {
			foreach ($query->result() as $cont) {
				$status_contrato = $cont->status;
			}
		}

		return $status_contrato;

	}

	public function verificar_placa_cad_equipamentos($placa, $modulo)
	{
		$this->db->where('placa', $placa);
		$this->db->where('serial', $modulo);
		$this->db->where('status', 5);
		$query = $this->db->get('showtecsystem.cad_equipamentos');

		if ($query->num_rows()) {
			$resultado = array(
				'status' => 'erro',
				'msg' => 'Placa ' . $placa . ' em uso no serial ' . $modulo
			);
			return $resultado;

		} else {

			$this->db->where('serial', $placa);
			$this->db->where('status', 5);
			$query2 = $this->db->get('showtecsystem.cad_equipamentos');

			if ($query2->num_rows()) {
				$resultado = array(
					'status' => 'erro',
					'msg' => 'Placa ' . $placa . ' em uso em outro serial'
				);
				return $resultado;

			} else {
				$resultado = array(
					'status' => 'ok'
				);
				return $resultado;
			}

		}

	}


	public function verificar_contratos_veiculos($placa, $id_contrato, $id_cliente)
	{
		$this->db->where('placa', $placa);
		$query = $this->db->get('showtecsystem.contratos_veiculos');

		if ($query->num_rows()) {
			$this->db->where('status', 'ativo');
			$query2 = $this->db->get('showtecsystem.contratos_veiculos');

			if ($query2->num_rows()) {
				$this->db->where('id_contrato', $id_contrato);
				$query3 = $this->db->get('showtecsystem.contratos_veiculos');

				if ($query3->num_rows()) {
					$this->db->where('id_cliente', $id_cliente);
					$query4 = $this->db->get('showtecsystem.contratos_veiculos');

					if ($query4->num_rows()) {
						$resultado = array(
							'status' => 'ok'
						);
						return $resultado;

					} else {
						$resultado = array(
							'status' => 'erro',
							'msg' => 'Placa ' . $placa . ' não cadastrada para este cliente'
						);
						return $resultado;

					}


				} else {
					$resultado = array(
						'status' => 'erro',
						'msg' => 'Placa ' . $placa . ' não cadastrada no contrato ' . $id_contrato
					);
					return $resultado;

				}

			} else {
				$resultado = array(
					'status' => 'erro',
					'msg' => 'Placa ' . $placa . ' desativada'
				);
				return $resultado;

			}

		} else {
			$resultado = array(
				'status' => 'erro',
				'msg' => 'Placa ' . $placa . ' não cadastrada'
			);
			return $resultado;

		}

	}

	public function ocorrencia_placa($veiculo, $placa, $quant_veiculos, $count = 0)
	{
		$cont_placa = 0;
		if ($quant_veiculos > 1) {
			for ($i = $count + 1; $i < $quant_veiculos; $i++) {
				pr($placa);
				pr($veiculo['placa' . $i]);
				if ($placa == $veiculo['placa' . $i]) {
					return true;
				}
			}
		} else {
			return false;
		}
	}


	public function ocorrencia_serial($veiculo, $serial, $quant_veiculos, $count = 0)
	{
		$cont_serial = 0;
		if ($quant_veiculos > 1) {
			for ($i = $count + 1; $i < $quant_veiculos; $i++) {
				if ($serial == $veiculo['serial' . $i]) {
					return true;
				}
			}
		} else {
			return false;
		}
	}

	public function get_status_equipamentos($serial)
	{

		$this->db->where('serial', $serial);
		$query = $this->db->get('showtecsystem.cad_equipamentos');

		if ($query->num_rows()) {
			foreach ($query->result() as $equipamento) {
				$status = $equipamento->status;
			}
			return $status;
		}
		return false;
	}

	public function liberar_equipamento($serial)
	{
		$liberado = false;

		$dados = array(
			'status' => 1
		);

		$this->db->where('serial', $serial);
		$liberado = $this->db->update('showtecsystem.cad_equipamentos', $dados);

		return $liberado;
	}

	public function get_instaladores()
	{
		$query = $this->db->get('showtecsystem.instaladores');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function get_status_instalador($id_contrato, $id_instalador)
	{
		$this->db->where('contrato', $id_contrato);
		$this->db->where('instalador', $id_instalador);
		$query = $this->db->get('showtecsystem.status_instalador_os');

		if ($query->num_rows()) {
			return $query->result();
		}

		return false;
	}

	public function add_status_instalador($dados)
	{
		$this->db->insert('showtecsystem.status_instalador_os', $dados);
		if ($this->db->affected_rows() > 0)
			return true;
		return false;
	}

	public function getListOs($data)
	{
		$this->db->select('os.*,os_eqp.id_os as id_os, os_eqp.placa as placa, os_eqp.id_equipamento as id_eqp, os_eqp.serial, cont.valor_mensal');
		$this->db->order_by('id', 'desc');
		$this->db->from('showtecsystem.os as os');
		$this->db->join('showtecsystem.os_equipamentos as os_eqp', 'id_os = os.id');
		$this->db->join('showtecsystem.contratos as cont', 'cont.id = os.id_contrato', 'INNER');
		$this->db->where('os.id_instalador', $data['id']);
		$this->db->where('os.status', 1);
		$this->db->where('os.status_pg', 0);
		$query = $this->db->get();
		if ($query->num_rows()) {
		    $resultados = $query->result();

		    foreach ($resultados as $resultado) {
		        $resultado->rastreio = $this->get_rastreioByPlaca($resultado->placa, $resultado->id_cliente);
            }
			return $resultados;
		}
		return false;
	}

	private function get_rastreioByPlaca($placa, $cliente) {
	    $retorno = $this->db->select('inforTipo')->get_where('showtecsystem.cad_logistica', array('placaOrdem' => $placa, 'cliente' => $cliente))->row();

	    if ($retorno)
	        return $retorno->inforTipo;
	    else
	        return '0';
    }

	public function updateService($data)
	{
		$update = array(
			'status_pg' => '1',
		);
		return $this->db->where('id', $data['id'])->update('showtecsystem.os', $update);
	}

	public function saveServiceOp($data)
	{
		$this->db->insert('listServiceOp', $data);
	}

	public function getCountOs($id)
	{
		$query = $this->db->get_where('showtecsystem.os', array('id_instalador' => $id, 'status' => 1, 'status_pg' => 0));
		return $query->result();
	}

	public function saveRatingTec($data)
	{
		return $this->db->insert('showtecsystem.rating_tec', $data);
	}

	public function getRating($id)
	{
		return $this->db->select_avg('nota')->get_where('showtecsystem.rating_tec', array('id_instalador' => $id))->result();
	}

// +++++++++++++++++++++++ jerônimo	gabriel init ++++++++++++++++++++++++++++
	// métodos desenvolvidos
	private function getIdSerial($serial)
	{
		$sql = "SELECT
					id
				FROM
					showtecsystem.cad_equipamentos
				WHERE
					serial = {$serial};";
		$ax = $this->db->query($sql)->result();
		return $ax ? $ax[0]->id : 0;
	}

	function listBoardsContract($id_contrato, $id_cliente)
	{
		$data = array();
		$sql = "SELECT
					placa
				FROM
					showtecsystem.contratos_veiculos
				WHERE
					id_contrato = {$id_contrato} AND id_cliente = {$id_cliente};";
		$boards = $this->db->query($sql)->result();
		foreach ($boards as $key => $v)
			$data[] = $v->placa;
		return json_encode($data);
	}

	function listEqp()
	{
		$data = array();
		$sql = "SELECT
					serial
				FROM
					showtecsystem.cad_equipamentos
				WHERE
					placa = '' AND serial <> ''
						AND status = 1;";
		$seriais = $this->db->query($sql)->result();
		foreach ($seriais as $key => $v)
			$data[] = $v->serial;
		return json_encode($data);
	}

	function save($data)
	{
		$boards = $data['boards'];
		unset($data['boards']);
		$seriais = $data['seriais'];
		unset($data['seriais']);
		$date = date('Y-m-d', strtotime($data['data']));
		$d_init = date('Y-m-d', strtotime($data['d_init']));
		$d_end = date('Y-m-d', strtotime($data['d_end']));
		$id_insta = explode('_', $data['inst']);
		if (!$data['address']) {
			$sql = "SELECT
						endereco, numero, complemento, ponto_de_referencia AS referen, bairro, cidade, uf
					FROM
						showtecsystem.cad_clientes
					WHERE id = {$data['id_cliente']};";
			$address = $this->db->query($sql)->result();
			$data['address'] = $address[0]->endereco . ' ' . $address[0]->numero . ' ' . $address[0]->complemento . ' ' . $address[0]->referen . ' ' . $address[0]->bairro . ' ' . $address[0]->cidade . ' ' . $address[0]->uf;
		}
		$sql = "INSERT INTO showtecsystem.os (
							id_cliente,
							id_contrato,
							solicitante,
							data_solicitacao,
							contato,
							telefone,
							endereco_destino,
							tipo_os,
							quantidade_equipamentos,
							data_inicial,
							hora_inicial,
							data_final,
							hora_final,
							id_instalador,
							status
						)
						VALUES (
							{$data['id_cliente']},
							{$data['id_contrato']},
							{$data['solic']},
							{$date},
							{$data['email']},
							{$data['phone']},
							{$data['address']},
							1,
							{$data['qtd']},
							{$d_init},
							{$data['h_init']},
							{$d_end},
							{$data['h_end']},
							{$id_insta[1]},
							0
						);";
		$this->db->query($sql);
		$id_os = $this->db->insert_id();
		for ($i = 0; $i < $data['qtd']; $i++) {
			$info = array(
				'id_os' => $id_os,
				'id_equipamento' => $this->getIdSerial($seriais[$i]),
				'id_contrato' => $data['id_contrato'],
				'placa' => $boards[$i],
				'serial' => $seriais[$i],
				'status' => 0
			);
			$this->db->insert('showtecsystem.os_equipamentos', $info);
			$this->db->where('id', $info['id_equipamento'])->update('showtecsystem.cad_equipamentos', array('status' => 3));
			$inf = array(
				'id_equipamento' => $info['id_equipamento'],
				'status' => 3,
				'ocorrencia' => "Enviado para instalacao - OS: " . $id_os
			);
			$this->db->insert('showtecsystem.cad_equipamentos_historico', $inf);
			$in = array(
				'id_instalador' => $id_insta[1],
				'id_equipamento' => $info['id_equipamento'],
				'status' => 2
			);
			$this->db->insert('showtecsystem.cad_equipamentos_instalador', $in);
		}
		$this->db->where('id', $data['id_contrato'])->update('showtecsystem.contratos', array('status' => 1));
		$dados = array(
			'id_contrato' => $data['id_contrato'],
			'status' => 1,
			'ocorrencia' => "Gerada a OS de Instalacao - " . $id_os
		);
		return $this->db->insert('showtecsystem.contratos_historico', $dados);
	}
	// ++++++++++++ end +++++++++++++++

	public function updateWeekly()
	{
		$this->db->where('weekly', 1)
			->update('showtecsystem.os', array('weekly' => 2));
	}

	public function verifyWeekly($idOs)
	{
		return $this->db->select('weekly, status')
			->where('id', $idOs)
			->get('showtecsystem.os')->result()[0];
	}

	public function get_valores_ordens($id){
		$total = 0;
		$this->db->like('id_os', '"'.$id.'"');
		$os = $this->db->get('showtecsystem.cad_contas')->row();
		if ($os) {
			$valores = unserialize($os->valorServ);
			$ids_os = unserialize($os->id_os);

			foreach ($ids_os as $key => $value) {
				if ($value == $id) {
					$total += $valores[$key];
				}
			}
		}

		return $total;
	}

	// GET OS POR CLIENTE //
	function getAjaxListOS($id_cliente, $inicio = 0, $limite = 10 ) {

		$query = $this->db->select('id, data_solicitacao')
            ->where('os.id_cliente', $id_cliente)
            ->order_by('data_solicitacao', 'DESC')
            ->get('showtecsystem.os', $limite, $inicio);

        if($query->num_rows()){
            return $query->result();
        }
        return false;
	}

}
