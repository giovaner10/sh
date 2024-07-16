<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processo_atendimento_agendamento extends CI_Model {

    public function __construct()
	{
		parent::__construct();
        $this->load->model("arquivo");
	}

    public function getAgendamentos()
    {
	    $query = $this->db->select("c.id, c.titulo_agendamento, c.data, a.pasta, a.path, a.file, c.id_arquivo")
            ->where("pasta = 'processos_atendimento_agendamento' AND c.status = 'ativo'")
            ->order_by("c.id", "DESC")
            ->join("showtecsystem.arquivos as a", "c.id_arquivo = a.id", "inner")
            ->get("showtecsystem.processos_atendimento_agendamento as c");
	    
	    return $query->result();
	}

    public function getAgendamento($id)
	{
	    $query = $this->db->get_where("processos_atendimento_agendamento", ["id" => $id]);
	    
	    return $query->result()[0];
	}

    public function adicionarAgendamento($agendamento, $arquivoNome, $arquivo, $usuarioId)
    {
        $pasta = "processos_atendimento_agendamento";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            "file" => $arquivoNome,
            "descricao" => $agendamento,
            "pasta" => $pasta,
            "ndoc" => "",
            "path" => $arquivo
        ]);

        $dadosAgendamento = array(
            "titulo_agendamento" => $agendamento,
            "id_arquivo" => $arquivoId,
            "id_usuario" => $usuarioId,
            "data" => $dataAtual
        );

        if (!$this->db->insert("showtecsystem.processos_atendimento_agendamento", $dadosAgendamento))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarAgendamento($agendamentoId, $agendamento, $usuarioId, $arquivo = "", $arquivoNome = "")
    {
        $pasta = "processos_atendimento_agendamento";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosAgendamento = array(
                "titulo_agendamento" => $agendamento,
                "id_usuario" => $usuarioId
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do agendamento
            $arquivoId = $this->getAgendamento($agendamentoId)->id_arquivo;

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
                "descricao" => $agendamento,
                "pasta" => $pasta,
                "ndoc" => "",
                "path" => $arquivo
            ]);

            $dadosAgendamento = array(
                "titulo_agendamento" => $agendamento,
                "id_arquivo" => $arquivoNovoId,
                "id_usuario" => $usuarioId
            );
        }

        if (!$this->db->update("showtecsystem.processos_atendimento_agendamento",
                $dadosAgendamento, ["id" => $agendamentoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluirAgendamento($agendamentoId)
    {
        # Inativa o comunicado
        if (!$this->db->update("showtecsystem.processos_atendimento_agendamento",
                ["status" => "inativo"], ["id" => $agendamentoId])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

}