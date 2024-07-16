<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Dica_Venda extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('arquivo');
	}

    public function get()
    {
	    return $this->db
            ->select('dv.id, dv.descricao, a.pasta, a.path, a.file')
            ->where(['dv.status' => 'ativo', 'a.status' => 'ativo'])
            ->join('arquivos as a', 'dv.id_arquivos = a.id', 'inner')
            ->get('dica_venda dv')
	        ->result();
	}

	public function getPorId($id)
	{
	    $query = $this->db->get_where("dica_venda", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionar($descricao, $arquivoNome, $arquivo)
    {
        $pasta = "dicas_vendas";

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $descricao,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosDicaVenda = array(
            "descricao" => $descricao,
            "id_arquivos" => $arquivoId
        );

        if (!$this->db->insert("dica_venda", $dadosDicaVenda))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editar($dicaVendaId, $descricao, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "dicas_vendas";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosDicaVenda = [
                "descricao" => $descricao
            ];
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo da dica de venda
            $arquivoId = $this->getPorId($dicaVendaId)->id_arquivos;

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

            $dadosDicaVenda = array(
                "descricao" => $descricao,
                "id_arquivos" => $arquivoNovoId
            );
        }

        if (!$this->db->update("dica_venda",
                $dadosDicaVenda, ["id" => $dicaVendaId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function inativar($id)
    {
        if (!$this->db->update("dica_venda",
                ["status" => "inativo"], ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}