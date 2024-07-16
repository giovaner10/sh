<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Documento_Pendente extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("documento_pendente_arquivo", "documentoPendenteArquivo");
	}

    public function get($where = [])
    {
		$where["status"] = "ativo";
		
	    $query = $this->db->get_where("showtecsystem.cad_docs_pendentes", $where);
	    
	    return $query->result();
	}

	public function getDocumentosPendentesUsuarios()
	{
        $query = $this->db
			->select('documento.*, usuario.nome AS funcionario')
        	->where(["documento.status" => "ativo", "recebido" => "nao"])
        	->join('showtecsystem.usuario as usuario', 'usuario.id = documento.id_usuario', 'inner')
        	->get('showtecsystem.cad_docs_pendentes as documento');

        $documentos = $query->result();

		# Popula a descricao de documentos e status
		$retorno = [];
		$x = 0;
		foreach ($documentos as $documento)
		{
			$retorno[$x] = $documento;
			$retorno[$x]->documentos = "";

			if($documento->residencia == 'sim')
		        $retorno[$x]->documentos .= " ".lang("comprovante_residencia").",";
		    if($documento->cpf == 'sim')
				$retorno[$x]->documentos .= " CPF,";
		    if($documento->rg == 'sim')
				$retorno[$x]->documentos .= " RG,";
		    if($documento->banco == 'sim')
				$retorno[$x]->documentos .= " ".lang("comprovante_dados_bancarios").",";
		    if($documento->status_documentos == 'pendente')
				$retorno[$x]->status_documentos = lang("pendente");
		    else
				$retorno[$x]->status_documentos = lang("documentos_enviados");

			# remove a ultima vírgula
			if ($retorno[$x]->documentos)
			{
				$retorno[$x]->documentos = substr(
					$retorno[$x]->documentos, 0, strlen($retorno[$x]->documentos) -1
				);
			}
			$x++;
		}

		return $retorno;
    }

	public function getDocumentoPendente($id)
	{
	    return $this->db->get_where("showtecsystem.cad_docs_pendentes", ["id" => $id])->result()[0];
	}

	public function verficarSeExiste($funcionarioId)
	{
		$query = $this->db->get_where(
			"showtecsystem.cad_docs_pendentes",
			[
				"id_usuario" => $funcionarioId,
				"recebido" => "nao",
				"status" => "ativo"
			]
		);

		if($query->num_rows() > 0)
        	return true;
		else
			return false;
	}

	public function adicionar($dados)
	{
	    if (!$this->db->insert("showtecsystem.cad_docs_pendentes", $dados))
			throw new Exception(lang("mensagem_erro"));

		return $this->db->insert_id();
	}

	public function editar($id, $dados)
	{
	    if (!$this->db->update('showtecsystem.cad_docs_pendentes', $dados, ['id' => $id]))
			throw new Exception(lang("mensagem_erro"));
	}

	public function excluir($id)
	{
		# Inativa arquivos
		$this->documentoPendenteArquivo->excluir($id);

		if (!$this->db->update('showtecsystem.cad_docs_pendentes', ["status" => "inativo"], ["id" => $id]))
			throw new Exception(lang("mensagem_erro"));
	}
}