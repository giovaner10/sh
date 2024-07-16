<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Representante extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->db_rastreamento = $this->load->database('rastreamento', TRUE);
	}
	
	public function add($dados) {
		if(!$this->isValidEmail($dados['email']) && !$this->isValidCPF($dados['cpf'])) {
			$this->db->insert('showtecsystem.representantes', $dados);
			if($this->db->affected_rows() > 0)
				return true;
			else
				throw new Exception('Não foi possível gravar no banco de dados. Tente novamente.');
		}else{
			return false;
		}
		return true;
	}

	public function atualizar($dados) {
        $this->db->where('id', $dados['id']);
        $resultado = $this->db->update('showtecsystem.representantes', $dados);
        if($resultado)
        	return true;
        return false;
    }

	public function isValidEmail($email) {
		$exist = $this->db->from('showtecsystem.representantes')
								 ->where(array('email' => $email))
								 ->count_all_results();
		
		if($exist == 0)
			return false;

	return true;

	}

	public function isValidCPF($cpf) {
		$exist = $this->db->from('showtecsystem.representantes')
								 ->where(array('cpf' => $cpf))
								 ->count_all_results();
		
		if($exist == 0)
			return false;

	return true;

	}

	public function get_cidades($sigla,$pais){
		$query = $this->db->select('nome, estado')
				->where('estado', $sigla)
				->where('pais', $pais)
				->order_by('nome')
				->get('showtecsystem.tb_cidade');
		if($query->num_rows() > 0){
			return $query->result();
		}
		return false;

	}


	public function get($where) {
		$representante = false;
		$query = $this->db->get_where('showtecsystem.representantes', $where);
		
		if($query->num_rows() > 0)
			foreach($query->result() as $representante);
				
		return $representante;

	}

	public function get_representantes($limit, $offset){

		$this->db->limit($offset,$limit);
		$this->db->order_by('id', 'asc');

		$query = $this->db->get('showtecsystem.representantes');

		if ($query->num_rows()) {
			
			return $query->result();

		}

		return false;
	}

	public function get_pesquisa_representantes($limit, $offset, $pesquisa = null){
		try {
			if ($pesquisa) {
				if($pesquisa['coluna'] == 'nome') {
					$this->db->like("CONCAT(TRIM(nome), ' ', COALESCE(TRIM(sobrenome), ''))", $pesquisa['palavra']);
				} else {
					$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);
				}
			}

			$this->db->limit($limit, $offset);
			$this->db->order_by('id', 'asc');
			$this->db->select("*, CONCAT(TRIM(nome), ' ', COALESCE(TRIM(sobrenome), '')) AS nome_completo", false);
			$query = $this->db->get('showtecsystem.representantes');

			if ($query->num_rows()) {
				$lastRow = $this->get_total_pesquisa_representantes($pesquisa);
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

	public function get_total_pesquisa_representantes($pesquisa = null) {
		try {
			if ($pesquisa) {
				if($pesquisa['coluna'] == 'nome') {
					$this->db->like("CONCAT(TRIM(nome), ' ', COALESCE(TRIM(sobrenome), ''))", $pesquisa['palavra']);
				} else {
					$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);
				}
			}
			$query = $this->db->get('showtecsystem.representantes');
			return $query->num_rows();
		} catch (Exception $e) {
			return false;
		}
	}

	public function get_total_representantes() {
		$query = $this->db->get('showtecsystem.representantes');
		return $query->num_rows();
	}



	public function get_json_representantes($q){
		
		$query = $this->db->select('*')
						  ->like('nome', $q)
						  ->or_like('sobrenome',$q)
						  ->get('showtecsystem.representantes');
						 
		if($query->num_rows > 0){
			foreach ($query->result_array() as $row) {
				$representante['label']= htmlentities(stripslashes($row['nome']." ".$row['sobrenome']));
				$representante['value']= htmlentities(stripslashes($row['id']));
				$representante[]=$representante;
							
			}
			echo json_encode($representantes);
		}

		else{

			return $msg="Sem Representantes Cadastrados";
			
		}
		
	}
	
	public function listar_pesquisa_representantes($pesquisa){

		$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);
		
		$query = $this->db->get('showtecsystem.representantes');

		if ($query->num_rows()) {
		
			return $query->result();
		}
		return false;
		
	}


	public function get_files($prefixo) {	
		$this->db->or_where('ndoc', $prefixo);
		$this->db->or_where('ndoc', 000);
		$this->db->where('pasta', 'representantes');
		$query = $this->db->get('showtecsystem.arquivos_rep');

		if ($query->num_rows()) 
			return $query->result();
		return false;
	}


	public function digitalizacao($contrato, $descricao, $nome_arquivo) {
		$pasta = "representantes";
		$dados = array(
			'file' => $nome_arquivo,
			'descricao' => $descricao,
			'pasta' => $pasta,
			'ndoc' => $contrato
			
		);

		$resposta = $this->db->insert('showtecsystem.arquivos_rep', $dados);

		if ($resposta) {
			
			return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo);
		}else{
		    return false;
		}

	}


	public function get_representante($id){

		$this->db->where('id', $id);
		$query = $this->db->get('showtecsystem.representantes');

		if ($query->num_rows()) {
			return $query->result();
		}

		return false;
	}

}