<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Proposta_Comercial extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('arquivo');
	}

    public function get()
    {
	    return $this->db
            ->select('p.id, p.descricao, a.pasta, a.path, a.file')
            ->where(['p.status' => 'ativo', 'a.status' => 'ativo'])
            ->join('arquivos as a', 'p.id_arquivos = a.id', 'inner')
            ->get('proposta_comercial p')
	        ->result();
	}

	public function getPorId($id)
	{
	    $query = $this->db->get_where("proposta_comercial", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionar($descricao, $arquivoNome, $arquivo)
    {
        $pasta = "propostas_comerciais";

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $descricao,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosProposta = array(
            "descricao" => $descricao,
            "id_arquivos" => $arquivoId
        );

        if (!$this->db->insert("proposta_comercial", $dadosProposta))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editar($propostaId, $descricao, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "propostas_comerciais";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosProposta = [
                "descricao" => $descricao
            ];
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo da proposta
            $arquivoId = $this->getPorId($propostaId)->id_arquivos;

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

            $dadosProposta = array(
                "descricao" => $descricao,
                "id_arquivos" => $arquivoNovoId
            );
        }

        if (!$this->db->update("proposta_comercial",
                $dadosProposta, ["id" => $propostaId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluir($id)
    {
        if (!$this->db->update("proposta_comercial",
                ["status" => "inativo"], ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}