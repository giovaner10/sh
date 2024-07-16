<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class processo_ativacao extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

    public function getProcessos()
    {
	    $query = $this->db->select("c.id, c.processo, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'processo_notes' AND c.status = 1")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.processos_ativacao as c");
	    
	    return $query->result();
	}

	public function getProcesso($id)
	{
	    $query = $this->db->get_where("processos_ativacao", ["id" => $id]);
	    
	    return $query->result()[0];
	}

	public function adicionarProcesso($arquivoModel, $processo, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "processo_notes";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $arquivoModel->adicionar([
            "file" => $arquivoNome,
            "descricao" => $processo,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo,
            "status" => 'ativo'
        ]);       

        $dadosProcesso = array(
            "processo" => $processo,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.processos_ativacao", $dadosProcesso))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarProcesso($arquivoModel, $processoId, $processo, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "processo_notes";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosProcesso = array(
                "processo" => $processo,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do processo
            $arquivoId = $this->getProcesso($processoId)->id_arquivo;

            # Get arquivo antigo
            $arquivoAntigo = $arquivoModel->getArquivos(['id' => $arquivoId])[0];

            # Deleta arquivo antigo (Servidor)
            if (file_exists($arquivoAntigo->path))
                unlink($arquivoAntigo->path);

            # Deleta arquivo antigo (BD)
            $arquivoModel->excluir($arquivoAntigo->id);

            # Adiciona novo arquivo
            $arquivoNovoId = $arquivoModel->adicionar([
                "file" => $arquivoNome,
                "descricao" => $processo,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosProcesso = array(
                "processo" => $processo,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.processos_ativacao",
                $dadosProcesso, ["id" => $processoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirProcesso($processoId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.processos_ativacao",
            ["status" => "inativo"], ["id" => $processoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
    
}