<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configuracao extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function save_message($message){
		
		$this->db->insert('mensagem_notificacao', $message);
		
		return $this->db->insert_id();
	}
	
	public function update_message($id_msg, $message){
		
		$this->db->update('mensagem_notificacao', $message, array('id_msg' => $id_msg));
		
		return $this->db->affected_rows();
		
	}
	
	public function get_message($where){
		
		$message = $this->db->get_where('mensagem_notificacao', $where);
		
		return $message->row(0);
	}
	
	public function find_message ($where = array(), $offset, $limit, $c_order = 'id_msg', $order = 'DESC'){
		
		$messages = $this->db->order_by($c_order, $order)
							 ->get_where('mensagem_notificacao', $where, $limit, $offset);
		
		return $messages->result();
	}
	
	public function has_found_messages ($where = array()){
		
		$this->db->from('mensagem_notificacao')->where($where);
		
		return $this->db->count_all_results();
	}
	
}