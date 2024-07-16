<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processo_atendimento_retencao extends CI_Model {

    public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getRetencaos()
    {
	    $query = $this->db->select("c.id, c.titulo_retencao, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'processos_atendimento_retencao' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.processos_atendimento_retencao as c");
	    
	    return $query->result();
	}

    public function getRetencao($id)
	{
	    $query = $this->db->get_where("processos_atendimento_retencao", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionarRetencao($retencao, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "processos_atendimento_retencao";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $retencao,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosRetencao = array(
            "titulo_retencao" => $retencao,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.processos_atendimento_retencao", $dadosRetencao))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarRetencao($retencaoId, $retencao, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "processos_atendimento_retencao";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosRetencao = array(
                "titulo_retencao" => $retencao,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do retencao
            $arquivoId = $this->getRetencao($retencaoId)->id_arquivo;

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
                "descricao" => $retencao,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosRetencao = array(
                "titulo_retencao" => $retencao,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.processos_atendimento_retencao",
                $dadosRetencao, ["id" => $retencaoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirRetencao($retencaoId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.processos_atendimento_retencao",
                ["status" => "inativo"], ["id" => $retencaoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

}