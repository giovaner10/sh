<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processo_atendimento_key_account extends CI_Model {

    public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getKeyAccounts()
    {
	    $query = $this->db->select("c.id, c.titulo_key_account, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'processos_atendimento_key_account' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.processos_atendimento_key_account as c");
	    
	    return $query->result();
	}

    public function getKeyAccount($id)
	{
	    $query = $this->db->get_where("processos_atendimento_key_account", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionarKeyAccount($key_account, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "processos_atendimento_key_account";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $key_account,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosKeyAccount = array(
            "titulo_key_account" => $key_account,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.processos_atendimento_key_account", $dadosKeyAccount))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarKeyAccount($key_accountId, $key_account, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "processos_atendimento_key_account";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosKeyAccount = array(
                "titulo_key_account" => $key_account,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do key_account
            $arquivoId = $this->getKeyAccount($key_accountId)->id_arquivo;

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
                "descricao" => $key_account,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosKeyAccount = array(
                "titulo_key_account" => $key_account,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.processos_atendimento_key_account",
                $dadosKeyAccount, ["id" => $key_accountId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirKeyAccount($key_accountId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.processos_atendimento_key_account",
                ["status" => "inativo"], ["id" => $key_accountId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

}