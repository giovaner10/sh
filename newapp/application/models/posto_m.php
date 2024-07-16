<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Posto_m extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function getPosto($id) {
		$sql = "SELECT id AS pk, nome AS name FROM showtecsystem.postos WHERE id_cliente = {$id};";
		return json_encode($this->db->query($sql)->result());
	}
}
