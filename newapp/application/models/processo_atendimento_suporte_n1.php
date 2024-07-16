<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processo_atendimento_suporte_n1 extends CI_Model {

    public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getSuportes()
    {
	    $query = $this->db->select("c.id, c.titulo_suporte, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'processos_atendimento_suporte_n1' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.processos_atendimento_suporte_n1 as c");
	    
	    return $query->result();
	}

    public function getSuporte($id)
	{
	    $query = $this->db->get_where("processos_atendimento_suporte_n1", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionarSuporte($suporte, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "processos_atendimento_suporte_n1";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $suporte,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosSuporte = array(
            "titulo_suporte" => $suporte,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.processos_atendimento_suporte_n1", $dadosSuporte))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarSuporte($suporteId, $suporte, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "processos_atendimento_suporte_n1";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosSuporte = array(
                "titulo_suporte" => $suporte,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do suporte
            $arquivoId = $this->getSuporte($suporteId)->id_arquivo;

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
                "descricao" => $suporte,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosSuporte = array(
                "titulo_suporte" => $suporte,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.processos_atendimento_suporte_n1",
                $dadosSuporte, ["id" => $suporteId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirSuporte($suporteId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.processos_atendimento_suporte_n1",
                ["status" => "inativo"], ["id" => $suporteId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

}