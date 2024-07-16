<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Parceria extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function get($where = [], $order = null)
    {
        if (!$order)
            $order = "parcerias.id DESC";

        $where["arquivos.status"] = "ativo";
        $where["parcerias.status"] = "ativo";

        return $this->db
            ->select("parcerias.id, parcerias.descricao, categorias.id as id_categoria, categorias.nome as categoria, arquivos.file, arquivos.link")
            ->join("showtecsystem.parcerias_categorias as categorias", "parcerias.id_parcerias_categorias = categorias.id", "inner")
            ->join("showtecsystem.arquivos as arquivos", "parcerias.id_arquivos = arquivos.id", "inner")
	        ->where($where)
            ->order_by($order)
            ->get("showtecsystem.parcerias as parcerias")
            ->result();
    }

    public function getParceria($id)
    {
        return $this->db
            ->select("parcerias.id, parcerias.descricao, categorias.id as id_categoria, arquivos.link, arquivos.id as id_arquivo")
            ->join("showtecsystem.parcerias_categorias as categorias", "parcerias.id_parcerias_categorias = categorias.id", "inner")
            ->join("showtecsystem.arquivos as arquivos", "parcerias.id_arquivos = arquivos.id", "inner")
            ->where([
                "parcerias.id" => $id,
                "arquivos.status" => "ativo"
            ])
            ->get("showtecsystem.parcerias as parcerias")
            ->result()[0];
    }

    public function adicionar($dados)
    {
        # Insere arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $dados["arquivoNome"],
            "descricao" => $dados["descricao"],
            "pasta" => "gente_gestao/desenv_organizagional/parcerias",
            "ndoc" => "",
            "path" => $dados["arquivo"],
            "link" => $dados["link"]
        ]);

        # Insere parceria
        $parceria = [
            "id_arquivos" => $arquivoId,
            "id_parcerias_categorias" => $dados["categoriaId"],
            "descricao" => $dados["descricao"]
        ];

        if (!$this->db->insert("showtecsystem.parcerias", $parceria))
            throw new Exception(lang("mensagem_erro"));

        return $this->db->insert_id();
    }

    public function editar($dados, $parceriaId)
    {
        $pasta = "gente_gestao/desenv_organizagional/parcerias";

        # Sem mudança de arquivo
        if(!$dados["arquivoNome"])
        {
            $dadosParceria = [
                "id_parcerias_categorias" => $dados["categoriaId"],
                "descricao" => $dados["descricao"]
            ];

            # update link do arquivo
            $this->arquivo->editar(
                ["link" => $dados["link"]], // dados
                ["id" => $dados["arquivoId"]] // where
            );

        }
        # Com mudança de arquivo
        else
        {
            # Get arquivo antgio
            $arquivoAntigo = $this->arquivo->getArquivos(['id' => $dados["arquivoId"]])[0];
            
            # Deleta arquivo antigo (servidor)
            if(file_exists($arquivoAntigo->file))
                unlink($arquivoAntigo->path);

            # Deleta arquivo antigo (bd)
            $this->arquivo->excluir($arquivoAntigo->id);
        
            # Adiciona novo arquivo
            $arquivoId = $this->arquivo->adicionar([
                "file" => $dados["arquivoNome"],
                "descricao" => $dados["descricao"],
                "pasta" => $pasta,
                "ndoc" => '',
                "path" => $dados["arquivo"],
                "link" => $dados["link"]
            ]);

            $dadosParceria = array(
                "id_arquivos" => $arquivoId,
                "id_parcerias_categorias" => $dados["categoriaId"],
                "descricao" => $dados["descricao"]
            );
        }

        if (!$this->db->update('showtecsystem.parcerias',
                $dadosParceria, ['id' => $parceriaId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluir($id)
    {
        # Inativa a parceria
        if (!$this->db->update(
                "showtecsystem.parcerias", ["status" => "inativo"], ["id" => $id])
            )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}