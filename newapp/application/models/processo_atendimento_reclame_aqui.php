<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processo_atendimento_reclame_aqui extends CI_Model {

    public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getReclameAquis()
    {
	    $query = $this->db->select("c.id, c.titulo_reclame_aqui, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'processos_atendimento_reclame_aqui' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.processos_atendimento_reclame_aqui as c");
	    
	    return $query->result();
	}

    public function getReclameAqui($id)
	{
	    $query = $this->db->get_where("processos_atendimento_reclame_aqui", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionarReclameAqui($reclame_aqui, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "processos_atendimento_reclame_aqui";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $reclame_aqui,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosReclameAqui = array(
            "titulo_reclame_aqui" => $reclame_aqui,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.processos_atendimento_reclame_aqui", $dadosReclameAqui))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarReclameAqui($reclame_aquiId, $reclame_aqui, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "processos_atendimento_reclame_aqui";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosReclameAqui = array(
                "titulo_reclame_aqui" => $reclame_aqui,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do reclame_aqui
            $arquivoId = $this->getReclameAqui($reclame_aquiId)->id_arquivo;

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
                "descricao" => $reclame_aqui,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosReclameAqui = array(
                "titulo_reclame_aqui" => $reclame_aqui,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.processos_atendimento_reclame_aqui",
                $dadosReclameAqui, ["id" => $reclame_aquiId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirReclameAqui($reclame_aquiId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.processos_atendimento_reclame_aqui",
                ["status" => "inativo"], ["id" => $reclame_aquiId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

}