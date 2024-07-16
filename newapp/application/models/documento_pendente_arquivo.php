<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Documento_Pendente_Arquivo extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function adicionar($documentosPendentesId, $funcionarioId, $arquivoNome, $path)
    {
	    $pasta = "docs_pendentes";
        $dataAtual = date("Y-m-d H:i:s");

        $dados = [
            'id_cad_docs_pendentes' => $documentosPendentesId,
            'id_usuario' => $funcionarioId,
            'file' => $arquivoNome,
            'pasta' => $pasta,
            'path' => $path,
            'data_envio' => $dataAtual
		];

        if (!$this->db->insert('showtecsystem.cad_docs_pendente_arquivos', $dados))
			throw new Exception(lang("mensagem_erro"));

		if (!$this->db->update(
				'showtecsystem.cad_docs_pendentes', # tab
				['status_documentos' =>  'documentos enviados'], # set doc enviado
				['id_usuario' => $funcionarioId] # where
			))
			throw new Exception(lang("mensagem_erro"));
	}

	public function get($documentosPendentesId)
	{
		$query = $this->db
			->select("arquivos.*, usuario.nome AS funcionario")
        	->where([
				"arquivos.status" => "ativo",
				"id_cad_docs_pendentes" => $documentosPendentesId
			])
        	->join('showtecsystem.usuario as usuario', 'usuario.id = arquivos.id_usuario', 'inner')
        	->get('showtecsystem.cad_docs_pendente_arquivos as arquivos');

        return $query->result();
	}

	public function excluir($documentoPendentesId)
	{
		# Get documentos_pendente_arquivo por id
		$documentosPendentesArquivos = $this->get($documentoPendentesId);

		# Extraio documentos_pendente_arquivo id
		$ids = [];
		foreach ($documentosPendentesArquivos as $documento)
			$ids[] = $documento->id;

		# Tem arquivos
		if (count($ids) > 0)
		{
			# Where IN($ids - documentos_pendente_arquivo)
			$this->db->where_in("id", $ids);

			if (!$this->db->update('showtecsystem.cad_docs_pendente_arquivos', ["status" => "inativo"]))
				throw new Exception(lang("mensagem_erro"));
		}
	}
}