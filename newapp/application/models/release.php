<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Release extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getReleases()
    {
	    $query = $this->db->select("c.id, c.release_note, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'release_notes' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.release_notes as c");
	    
	    return $query->result();
	}

	public function getRelease($id)
	{
	    $query = $this->db->get_where("release_notes", ["id" => $id]);
	    
	    return $query->result()[0];
	}

	public function adicionarRelease($release, $arquivoNome, $arquivo, $usuarioId, $data)
    {
        $pasta = "release_notes";

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $release,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosRelease = array(
            "release_note" => $release,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $data
        );

        if (!$this->db->insert("showtecsystem.release_notes", $dadosRelease))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarRelease($releaseId, $release, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "release_notes";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosRelease = array(
                "release_note" => $release,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo da release note
            $arquivoId = $this->getRelease($releaseId)->id_arquivo;

            # Get arquivo antigo
            $arquivoAntigo = $this->arquivo->getArquivos(['id' => $arquivoId])[0];

            # Deleta arquivo antigo (Servidor)
            if (file_exists($arquivoAntigo->path))
                unlink($arquivoAntigo->path);

            # Deleta arquivo antigo (BD)
            $this->arquivo->excluir($arquivoAntigo->id);

            # Adiciona novo arquivo
            $arquivoNovoId = $this->arquivo->adicionar([
                "file" => $arquivoNome,
                "descricao" => $release,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosRelease = array(
                "release_note" => $release,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.release_notes",
                $dadosRelease, ["id" => $releaseId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirRelease($releaseId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.release_notes",
                ["status" => "inativo"], ["id" => $releaseId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function getReleaseArquivo($releaseTitulo, $data)
    {
        $query = $this->db->select("a.file")
            ->where("c.release_note = '$releaseTitulo' AND c.data = '$data' AND c.status = 'ativo' AND a.pasta = 'release_notes'")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.release_notes as c");

        $resultado = $query->result()[0];
        if ($resultado){
            return $resultado->file;
        }
        else
            return false;
        }
}