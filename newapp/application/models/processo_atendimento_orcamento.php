<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processo_atendimento_orcamento extends CI_Model {

    public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getOrcamentos()
    {
	    $query = $this->db->select("c.id, c.titulo_orcamento, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'processos_atendimento_orcamento' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.processos_atendimento_orcamento as c");
	    
	    return $query->result();
	}

    public function getOrcamento($id)
	{
	    $query = $this->db->get_where("processos_atendimento_orcamento", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionarOrcamento($orcamento, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "processos_atendimento_orcamento";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $orcamento,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosOrcamento = array(
            "titulo_orcamento" => $orcamento,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.processos_atendimento_orcamento", $dadosOrcamento))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarOrcamento($orcamentoId, $orcamento, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "processos_atendimento_orcamento";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosOrcamento = array(
                "titulo_orcamento" => $orcamento,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do orcamento
            $arquivoId = $this->getOrcamento($orcamentoId)->id_arquivo;

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
                "descricao" => $orcamento,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosOrcamento = array(
                "titulo_orcamento" => $orcamento,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.processos_atendimento_orcamento",
                $dadosOrcamento, ["id" => $orcamentoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirOrcamento($orcamentoId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.processos_atendimento_orcamento",
                ["status" => "inativo"], ["id" => $orcamentoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

}