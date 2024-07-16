<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class monitoramento_iscas_model extends CI_Model {

	// Array de colunas pesquisaveis
	protected $column_search = [
		"cad_iscas.id", "serial", "descricao", "marca", "nome", "placa"
	];

	// Array de colunas pesquisaveis em comandos
	protected $column_search_cmd = [
		"cad_iscas.id", "marca", "nome", "placa"
	];

	// Array de colunas pesquisaveis em dispositivos
	protected $column_search_disp = [
		"id", "serial", "descricao", "marca", "nome", "placa"
	];
	
	// Array de colunas ordenaveis
	protected $column_order = [
		"cad_iscas.id", "serial", "descricao", "marca", "nome", "placa" //, "id_object_tracker", "data", "datasys", "y", "x", "", "ignition", "vel", "", "rpm", "status"
	];

	// Array de colunas ordenaveis
	protected $column_order_rast = [
		6 => "data",
		7 => "datasys"
	];

	// Array de filtros permitidos
	protected $modos_permitidos = [
		"1", "2", "3", "4", "5", "6", "R", "B", "D"
	];

	public function __construct() {
		parent::__construct();
	}

	// Consultar as iscas ativas
	private function consultarCadIscas()
	{
		$this->db->select('showtecsystem.cad_iscas.id, showtecsystem.cad_iscas.serial, showtecsystem.cad_iscas.marca, showtecsystem.cad_clientes.nome, showtecsystem.cad_iscas.modelo, showtecsystem.cad_iscas.descricao, showtecsystem.cad_iscas.placa');
		$this->db->from('showtecsystem.cad_iscas');
		$this->db->join('showtecsystem.cad_clientes', 'showtecsystem.cad_clientes.id = showtecsystem.cad_iscas.id_cliente', 'left');
		$this->db->where('showtecsystem.cad_iscas.status', 1);
		$this->db->where('showtecsystem.cad_iscas.serial IS NOT NULL');
		$this->db->where('showtecsystem.cad_iscas.serial !=', '');
	}

	// Consultar em rastreamento e fazer join com cad_iscas
	private function consultarRastreamento($cad_iscas, $filtro)
	{
		$order_column = NULL;
		$order_dir = NULL;
		$order = $this->input->post("order");
		if (isset($order)) {
			$order_column = $order[0]["column"];
			$order_dir = $order[0]["dir"];
		}

		$this->rastreamento = $this->load->database('rastreamento', TRUE);

		$this->rastreamento->select('rastreamento.last_track.ID, rastreamento.last_track.id_object_tracker, rastreamento.last_track.data, rastreamento.last_track.datasys,rastreamento.last_track.y, rastreamento.last_track.x, rastreamento.last_track.GPS, rastreamento.last_track.GPRS,
																	rastreamento.last_track.ignition, rastreamento.last_track.vel,rastreamento.last_track.rpm, rastreamento.last_track.status, rastreamento.last_track.VOLTAGE, rastreamento.last_track.ENDERECO, rastreamento.last_track.IN6');
		$this->rastreamento->from('rastreamento.last_track');
		@$this->rastreamento->where_in('rastreamento.last_track.ID', array_map(function($o){ return $o->serial; }, $cad_iscas));
		
		if($filtro) {
			if(in_array($filtro, $this->modos_permitidos)) {
				@$this->rastreamento->where("rastreamento.last_track.status", $filtro);
			}
		}
		
		if (isset($order) && isset($this->column_order_rast[$order_column])) {
			$this->rastreamento->order_by($this->column_order_rast[$order_column], $order_dir);
		}

		$query = $this->rastreamento->get();
		
		$results = [];
		if($query) {
			$last_tracks = $query->result();

			if (isset($order) && isset($this->column_order_rast[$order_column])) {
				$new_cad_iscas = []; 
				foreach ($cad_iscas as $isca) {
					$new_cad_iscas[$isca->serial] = $isca;
				}

				foreach($last_tracks as $last_track) {
					if(isset($new_cad_iscas[$last_track->ID]) ) {
						$cad_isca = $new_cad_iscas[$last_track->ID];
	
						$cad_isca->id_object_tracker = $last_track->id_object_tracker;
						$cad_isca->data = $last_track->data;
						$cad_isca->datasys = $last_track->datasys;
						$cad_isca->y = $last_track->y;
						$cad_isca->x = $last_track->x;
						$cad_isca->ignition = $last_track->ignition;
						$cad_isca->vel = $last_track->vel;
						$cad_isca->rpm = $last_track->rpm;
						$cad_isca->status = $last_track->status;
						$cad_isca->VOLTAGE = $last_track->VOLTAGE;
						$cad_isca->ENDERECO = $last_track->ENDERECO;
						$cad_isca->GPS = $last_track->GPS;
						$cad_isca->GPRS = $last_track->GPRS;
						$cad_isca->IN6 = $last_track->IN6;
						$results[] = $cad_isca;
					}
				}
			}else{
				$new_last_tracks = []; 
				foreach ($last_tracks as $track) {
					$new_last_tracks[$track->ID] = $track;
				}	

				foreach($cad_iscas as $cad_isca) {
					if(isset($new_last_tracks[$cad_isca->serial]) ) {
						$last_track = $new_last_tracks[$cad_isca->serial];
				
						$cad_isca->id_object_tracker = $last_track->id_object_tracker;
						$cad_isca->data = $last_track->data;
						$cad_isca->datasys = $last_track->datasys;
						$cad_isca->y = $last_track->y;
						$cad_isca->x = $last_track->x;
						$cad_isca->ignition = $last_track->ignition;
						$cad_isca->vel = $last_track->vel;
						$cad_isca->rpm = $last_track->rpm;
						$cad_isca->status = $last_track->status;
						$cad_isca->VOLTAGE = $last_track->VOLTAGE;
						$cad_isca->ENDERECO = $last_track->ENDERECO;
						$cad_isca->GPS = $last_track->GPS;
						$cad_isca->GPRS = $last_track->GPRS;
						$results[] = $cad_isca;
					}
				}
			}
		}

		return $results;
	}

	// Consulta sem filtros ou pesquisa
	private function consultaIscasAtivas() {
		$this->consultarCadIscas();
		$cad_iscas = $this->db->get();

		$iscas_rastreamento = [];
		if($cad_iscas && $cad_iscas->num_rows() > 0) {
			$iscas_rastreamento = $this->consultarRastreamento($cad_iscas->result(), "");
		}

		return $iscas_rastreamento;
	}
    
	// Consultas com ordem e pesquisa em cad_iscas
	private function consultaIscasAtivasComFiltros() {

		$search = NULL;
		if ($this->input->post("search")) {
			$search = $this->input->post("search")["value"];
		}

		$order_column = NULL;
		$order_dir = NULL;
		$order = $this->input->post("order");
		if (isset($order)) {
			$order_column = $order[0]["column"];
			$order_dir = $order[0]["dir"];
		}

		$this->consultarCadIscas();
		if (isset($search) && $search != "") {
			$first = TRUE;
			foreach ($this->column_search as $field) {
				if ($first) {
					$this->db->like($field, $search);
					$first = FALSE;
				} else {
					$this->db->or_like($field, $search);
				}
			}
		}

		if (isset($order) && isset($this->column_order[$order_column])) {
			$this->db->order_by($this->column_order[$order_column], $order_dir);
		}
	}

	public function get_datatable() {
		$filtro = $this->input->post("filters");
		
		$this->consultaIscasAtivasComFiltros();

		$cad_iscas = $this->db->get();
		
		$iscas_rastreamento = [];
		if($cad_iscas && $cad_iscas->num_rows() > 0) {
			$iscas_rastreamento = $this->consultarRastreamento($cad_iscas->result(), $filtro);
		}

		return $iscas_rastreamento;
	}

	public function get_datatable_dispositivos($id_usuario) {
		$search = NULL;
		if ($this->input->post("search")) {
			$search = $this->input->post("search")["value"];
		}

		$sql = 'SELECT iscas.*, d.id_isca, d.id_usuario, clientes.nome FROM showtecsystem.monitoramento_dispositivo_isca_x_usuario AS d
						JOIN showtecsystem.cad_iscas AS iscas
						ON iscas.id = d.id_isca
						LEFT JOIN showtecsystem.cad_clientes as clientes
						ON iscas.id_cliente = clientes.id
						WHERE d.id_usuario = "'.$id_usuario.'"';

		// $this->db->select('iscas.*')->
		// 	from('showtecsystem.monitoramento_dispositivo_isca_x_usuario as d')->
		// 	join('showtecsystem.cad_iscas as iscas','iscas.id = d.id_isca')->
		// 	where('d.id_usuario',$id_usuario);
			
		if (isset($search) && $search != "") {
			$sql = 'SELECT * FROM ('.$sql.') AS disp_user';
			$first = TRUE;
			foreach ($this->column_search_disp as $field) {
				if ($first) {
					$sql = $sql." WHERE disp_user.".$field." LIKE '%".$search."%'";
					// $this->db->like($field, $search);
					$first = FALSE;
				} else {
					$sql = $sql." OR disp_user.".$field." LIKE '%".$search."%'";
					// $this->db->or_like($field, $search);
				}
			}
		}

		$cad_iscas = $this->db->query($sql);
		
		$iscas_rastreamento = [];
		if($cad_iscas && $cad_iscas->num_rows() > 0) {
			$iscas_rastreamento = $this->consultarRastreamento($cad_iscas->result(), "");
		}
		// pr($iscas_rastreamento);die();
		return $iscas_rastreamento;
	}
	
	public function records_total() {
		$query = $this->consultaIscasAtivas();

		return count($query);
	}
	
	public function get_comandos_monitoramento($seriais, $filtro = null)
	{	
		$query = [];
		if(count($seriais) > 0) {
			$this->rastreamento = $this->load->database('rastreamento', TRUE);
			@$this->rastreamento->select('*')->
				from('rastreamento.comandos_enviados')->
				where_in('cmd_eqp',$seriais);

			if($filtro) {
				@$this->rastreamento->like("descricao_comando", $filtro);
			}

			@$this->rastreamento->order_by('cmd_envio','DESC');

			$query = $this->rastreamento->get()->result_array();
		}

		return $query;
	}
	public function iscas_ativas_clientes(){
	
		$search = NULL;
		if ($this->input->post("search")) {
			$search = $this->input->post("search")["value"];
		}

		$this->db->select('cad_iscas.*, cad_clientes.nome')
			->from('showtecsystem.cad_iscas')
			->join('cad_clientes', 'cad_clientes.id = cad_iscas.id_cliente')
			->where('cad_iscas.status',1);

		if (isset($search) && $search != "") {
			$first = TRUE;
			foreach ($this->column_search_cmd as $field) {
				if ($first) {
					$this->db->like($field, $search);
					$first = FALSE;
				} else {
					$this->db->or_like($field, $search);
				}
			}
		}

		$query = $this->db->get()->result_array();

		return $query;
	}

	public function get_dispositivo_by_serial($serial){

		$query = $this->db->select('id, serial as text')->
			like('serial',$serial)->get('showtecsystem.cad_iscas')->result();

		return $query;
	}

	private function inserir_dispositivos_user($ids_iscas, $id_user) {
		try {
			$iscas_inseridas = [];
			$iscas_nao_inseridas = [];

			foreach ($ids_iscas as $key => $isca) {				
				$dados = [
					'id_isca' => $isca['id'], 
					'id_usuario' => $id_user
				];
				$get_isca = $this->db->select('*')->
					where('id_isca',$dados['id_isca'])->
					where('id_usuario',$dados['id_usuario'])->
					get('showtecsystem.monitoramento_dispositivo_isca_x_usuario')->result_array();
				
				if(count($get_isca) == 0){
					$this->db->insert('showtecsystem.monitoramento_dispositivo_isca_x_usuario', $dados);
					array_push($iscas_inseridas, $isca['serial']);
				}else{
					array_push($iscas_nao_inseridas, $isca['serial']);
				}
			}

			return ([
				"status"=>true,
				"msg"=> lang("disp_inseridos_sucesso"),
				"iscas_inseridas"=> $iscas_inseridas,
				"iscas_nao_inseridas"=> $iscas_nao_inseridas,
			]);
		} catch (\Throwable $th) {
			return (["status" => false, "msg" => lang("disp_inseridos_erro"), "error"=>$th]);
		}	
	}

	public function inserir_dispositivos($insert, $tipo, $id_user){
		$ids_iscas = $this->db->select('id,serial')->where_in('id',$insert)->get('showtecsystem.cad_iscas')->result_array();
		
		return $this->inserir_dispositivos_user($ids_iscas, $id_user);	
	}

	public function inserir_dispositivos_cliente($id_user, $id_cliente){
		// seleciona os ids das iscas de um cliente
		$ids_iscas = $this->db->select('id,serial')->where('id_cliente',$id_cliente)->get('showtecsystem.cad_iscas')->result_array();

		return $this->inserir_dispositivos_user($ids_iscas, $id_user);
	}

	public function remover_dispositivos($id_isca,$id_user){
		$query = $this->db->select('*')->where('id_isca',$id_isca)->get('showtecsystem.monitoramento_dispositivo_isca_x_usuario')->result_array();

		if(count($query) > 0){
			$this->db->where('id_isca',$id_isca)->where('id_usuario',$id_user)->delete('showtecsystem.monitoramento_dispositivo_isca_x_usuario');
			return (["status" => true, "msg" => lang("disp_removido_sucesso")]);
		}else{
			return (["status" => false, "msg" => lang("disp_nao_encontrado")]);
		}
	}

	public function limpar_grid_dispositivos($id_user){
		$query = $this->db->select('*')->where('id_usuario',$id_user)->get('showtecsystem.monitoramento_dispositivo_isca_x_usuario')->result_array();
		if(count($query) > 0){
			$this->db->where('id_usuario',$id_user)->delete('showtecsystem.monitoramento_dispositivo_isca_x_usuario');
			return (["status" => true, "msg" => lang("disps_removidos_sucesso")]);
		}else{
			return (["status" => false, "msg" => lang("disps_removidos_erro")]);
		}
	}

}