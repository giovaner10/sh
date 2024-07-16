<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class omnisign extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}
	
	/** Função adiciona um novo registro */
	public function addFile($registro)
	{
		$insert = $this->db->insert('omnisign.file_signature', $registro);
		return $insert ? $this->db->insert_id() : false;
	}

	/** Função retorna lista de destinatarios */
	public function getDestinatarios($where, $whereIn = false)
	{
		if (is_array($whereIn) && !empty($whereIn))
			$this->db->where_in($whereIn);

		return is_array($where) && !empty($where) ? $this->db->get_where('omnisign.signature_recipients', $where)->result() : [];		
	}
	
	/** Função adiciona destinatarios vinculados a um determinado Registro/Arquivo */
	public function addDestinatarios($destinatarios)
	{
		return is_array($destinatarios) && !empty($destinatarios) ? $this->db->insert_batch('omnisign.signature_recipients', $destinatarios) : false;
	}

	/** Função atualiza um destinatario */
	public function updateSignature($idSign, $update)
	{
		return is_numeric($idSign) && is_array($update) ? $this->db->update('omnisign.signature_recipients', $update, ['id' => $idSign]) : false;
	}

	/** Função atualiza um omnisign/documento */
	public function updateFile($idFile, $update)
	{
		return is_numeric($idFile) && is_array($update) ? $this->db->update('omnisign.file_signature', $update, ['id' => $idFile]) : false;
	}

	/** Função remove destinatario */
	public function deleteSignature($where)
	{
		return is_array($where) && !empty($where) ? $this->db->delete('omnisign.signature_recipients', $where) : false;
	}

	/** Função retorna lista de arquivos cadastrados */
	public function getDataFiles($where, $whereIn = false)
	{
		if (is_array($whereIn))
			$this->db->where_in($whereIn);

		return is_array($where) && !empty($where) ? $this->db->get_where('omnisign.file_signature', $where)->result() : [];
	}

	/** Função retorna lista de arquivos não assinados do usuário */
	public function getDataFilesSignature($whereIn)
	{
		$this->db->where_in('status', ['0', '1']);
		return is_array($whereIn) && !empty($whereIn) ? $this->db->where_in('id', $whereIn)->get('omnisign.file_signature')->result() : [];
	}

}