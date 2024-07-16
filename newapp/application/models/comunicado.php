<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Comunicado extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getComunicados()
    {
	    $query = $this->db->select("c.id, c.comunicado, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'comunicados' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.cad_comunicados as c");
	    
	    return $query->result();
	}

	public function getComunicado($id)
	{
	    $query = $this->db->get_where("cad_comunicados", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionarComunicado($comunicado, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "comunicados";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $comunicado,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosComunicado = array(
            "comunicado" => $comunicado,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.cad_comunicados", $dadosComunicado))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarComunicado($comunicadoId, $comunicado, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "comunicados";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosComunicado = array(
                "comunicado" => $comunicado,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do comunicado
            $arquivoId = $this->getComunicado($comunicadoId)->id_arquivo;

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
                "descricao" => $comunicado,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosComunicado = array(
                "comunicado" => $comunicado,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.cad_comunicados",
                $dadosComunicado, ["id" => $comunicadoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirComunicado($comunicadoId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.cad_comunicados",
                ["status" => "inativo"], ["id" => $comunicadoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}