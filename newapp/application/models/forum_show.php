<?php

date_default_timezone_set('America/Recife');

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forum_show extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get($where)
	{
		$query = $this->db->select('f.*,u.nome')->join('showtecsystem.usuario u ','u.id=f.id_user')->get('systems.forum f');

		if ($query->num_rows()){
			$result = $query->result();
			foreach($result as $linha){
				$linha->data=dh_for_humans($linha->data);
			}
			return $result;
		}
		return false;
	}

	public function insert($data)
	{
		$this->db->insert('systems.forum',$data);
		return true;
	}
}
