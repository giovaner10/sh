<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Folheto extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('arquivo');
	}

    public function get()
    {
	    return $this->db
            ->select('folheto.id, folheto.descricao, arquivos.pasta, arquivos.path, arquivos.file')
            ->where(['folheto.status' => 'ativo', 'arquivos.status' => 'ativo'])
            ->join('arquivos', 'folheto.id_arquivos = arquivos.id', 'inner')
            ->get('folheto folheto')
	        ->result();
	}

	public function getPorId($id)
	{
	    $query = $this->db->get_where("folheto", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionar($descricao, $arquivoNome, $arquivo)
    {
        $pasta = "folhetos";

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $descricao,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosFolheto = array(
            "descricao" => $descricao,
            "id_arquivos" => $arquivoId
        );

        if (!$this->db->insert("folheto", $dadosFolheto))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editar($folhetoId, $descricao, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "folhetos";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosFolheto = [
                "descricao" => $descricao
            ];
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do folheto
            $arquivoId = $this->getPorId($folhetoId)->id_arquivos;

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

            $dadosFolheto = array(
                "descricao" => $descricao,
                "id_arquivos" => $arquivoNovoId
            );
        }

        if (!$this->db->update("folheto",
                $dadosFolheto, ["id" => $folhetoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluir($id)
    {
        if (!$this->db->update("folheto",
                ["status" => "inativo"], ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}