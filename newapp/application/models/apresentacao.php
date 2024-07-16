<?php if ( ! defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Apresentacao extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get()
    {
        return $this->db
            ->select('apresentacao.id, apresentacao.descricao, arquivos.file')
            ->where(['apresentacao.status' => 'ativo'])
            ->join('arquivos', 'apresentacao.id_arquivos = arquivos.id', 'inner')
            ->get('apresentacao')
            ->result();
    }

    public function getPorId($id)
	{
	    $query = $this->db->get_where("apresentacao", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionar($descricao, $arquivoNome, $arquivo)
    {
        $pasta = "apresentacoes";

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $descricao,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosApresentacao = array(
            "descricao" => $descricao,
            "id_arquivos" => $arquivoId
        );

        if (!$this->db->insert("apresentacao", $dadosApresentacao))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editar($apresentacaoId, $descricao, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "apresentacoes";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosApresentacao = [
                "descricao" => $descricao
            ];
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo da apresentação
            $arquivoId = $this->getPorId($apresentacaoId)->id_arquivos;

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
                "descricao" => $descricao,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosApresentacao = array(
                "descricao" => $descricao,
                "id_arquivos" => $arquivoNovoId
            );
        }

        if (!$this->db->update("apresentacao",
                $dadosApresentacao, ["id" => $apresentacaoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluir($id)
    {
        if (!$this->db->update("apresentacao",
                ["status" => "inativo"], ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}