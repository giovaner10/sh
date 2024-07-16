<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agenda extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function save ($agendamento){
		
		$agenda = false;
		
		if ($this->is_scheduled($agendamento['placa'], $agendamento['prefixo'], 
								$agendamento['data_agenda'])){
			
			throw new Exception('Já existe um agendamento pendente para o veículo informado.');
			
		}else{
			
			$this->db->insert('agenda', $agendamento);
			
			if ($this->db->affected_rows())
				$agenda = $this->db->insert_id();
			
		}
			
		return $agenda;
	}
	
	public function get ($where){
		
		$agendamento = $this->db->get_where('agenda', $where)->row(0);
		
		return $agendamento;
	}
	
	private function is_scheduled ($placa, $prefixo){
		
		$scheduled = false;
		$hoje = date('Y-m-d');
				
		$agenda = $this->db->from('agenda')
						   ->where('data_agenda >=', $hoje)
						   ->where('placa', $placa)
						   ->or_where('prefixo', $prefixo)
						   ->get()->row(0);
		
		if (count($agenda))
			$scheduled = $agenda;
		
		return $scheduled;
						   
	}
	
	public function find ($where, $offset, $limit = 15, $select = '*', $c_order = 'id_agenda', $order = 'DESC' ){
		
		$agenda = $this->db->select($select)
						   ->where($where)
						   ->order_by($c_order, $order)
						   ->get_where('agenda', $where, $limit, $offset)
						   ->result();
		
		return $agenda;
	}
	
	public function has_found ($where = array()){
		
		$total = $this->db->from('agenda')
						  ->where($where)
						  ->count_all_results();
		
		return $total;
	}
	
}