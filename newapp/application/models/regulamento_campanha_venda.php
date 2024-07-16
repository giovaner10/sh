<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Regulamento_Campanha_Venda extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('arquivo');
	}

    public function get()
    {
	    return $this->db
            ->select('rcv.id, rcv.descricao, a.pasta, a.path, a.file')
            ->where(['rcv.status' => 'ativo', 'a.status' => 'ativo'])
            ->join('arquivos as a', 'rcv.id_arquivos = a.id', 'inner')
            ->get('regulamento_campanha_venda rcv')
	        ->result();
	}

	public function getPorId($id)
	{
	    $query = $this->db->get_where("regulamento_campanha_venda", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionar($descricao, $arquivoNome, $arquivo)
    {
        $pasta = "regulamentos_campanha_vendas";

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

        if (!$this->db->insert("regulamento_campanha_venda", $dadosDicaVenda))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editar($dicaVendaId, $descricao, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "regulamentos_campanha_vendas";

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

        if (!$this->db->update("regulamento_campanha_venda",
                $dadosDicaVenda, ["id" => $dicaVendaId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function inativar($id)
    {
        if (!$this->db->update("regulamento_campanha_venda",
                ["status" => "inativo"], ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}