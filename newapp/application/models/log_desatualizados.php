<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_desatualizados extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function insert($log){
		
		$insert = false;
		
		$this->db->insert('systems.envio_sms', $log);
		if ($this->db->affected_rows())
			$insert = $this->db->insert_id();
		
		return $insert;
		
	}
	
	public function find ($where){
		
		$logs = $this->db->get_where('systems.envio_sms', $where)->result();
		
		return $logs;
	}
	
	public function update($id_log, $dados){
		
		$update = false;
		
		$this->db->update('systems.envio_sms', $dados, array('id_log' => $id_log));
		if ($this->db->affected_rows())
			$update = $id_log;
		
		return $update;
	}
	
	

}