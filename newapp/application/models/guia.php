<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Guia extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('arquivo');
	}

    public function get()
    {
	    return $this->db
            ->select('guia.id, guia.descricao, arquivos.pasta, arquivos.path, arquivos.file')
            ->where(['guia.status' => 'ativo', 'arquivos.status' => 'ativo'])
            ->join('arquivos', 'guia.id_arquivos = arquivos.id', 'inner')
            ->get('guia')
	        ->result();
	}

	public function getPorId($id)
	{
	    $query = $this->db->get_where("guia", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionar($descricao, $arquivoNome, $arquivo)
    {
        $pasta = "guias";

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $descricao,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosGuia = array(
            "descricao" => $descricao,
            "id_arquivos" => $arquivoId
        );

        if (!$this->db->insert("guia", $dadosGuia))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editar($guiaId, $descricao, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "guias";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosGuia = [
                "descricao" => $descricao
            ];
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo da guia
            $arquivoId = $this->getPorId($guiaId)->id_arquivos;

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

            $dadosGuia = array(
                "descricao" => $descricao,
                "id_arquivos" => $arquivoNovoId
            );
        }

        if (!$this->db->update("guia",
                $dadosGuia, ["id" => $guiaId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluir($id)
    {
        if (!$this->db->update("guia",
                ["status" => "inativo"], ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}