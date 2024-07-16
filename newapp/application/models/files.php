<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Files extends CI_Model {
	private $db_rastreamento = '';

    public function __construct() {
        parent::__construct();
    }

    /**
	*
	* Pega os arquivos
	*
	**/
	public function get($select = '*', $where = null, $order = null, $sort = null, $limit = null, $offset = null)
	{
		if($select) {
			$this->db->select($select);
		}

		$query = $this->db->from('systems.upload_arquivos');

		if ($where) {
			$this->db->where($where);
		}

		if ($order) {
			$this->db->order_by($order, $sort ? $sort : 'asc');
		}

		if ($limit) {
			$this->db->limit($limit, $offset ? $offset : null);
		}

		return $query->get()->result();
	}

    function fileSave($dados) {
        $this->db->insert('systems.upload_arquivos', $dados);
        return $this->db->insert_id();
    }

    function fileUpdate($id, $dados) {
		$this->db->where('id', $id);
		$this->db->update('systems.upload_arquivos', $dados);
	}

}