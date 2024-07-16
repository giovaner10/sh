<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Parceria_Categoria extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get()
    {
        return $this->db
            ->get_where("showtecsystem.parcerias_categorias", ["status" => "ativo"])
            ->result();
    }

    public function getCategoria($id)
    {
        return $this->db->get_where("showtecsystem.parcerias_categorias", ["id" => $id])
            ->result()[0];
    }

    public function adicionar($dados)
    {
        $this->realocaOrdens($dados["ordem"]);

        if (!$this->db->insert("showtecsystem.parcerias_categorias", $dados))
            throw new Exception(lang("mensagem_erro"));

        return $this->db->insert_id();
    }

    public function editar($dados, $id)
    {
        $this->realocaOrdens($dados["ordem"]);

        if (!$this->db->update("showtecsystem.parcerias_categorias", $dados, ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }

    public function excluir($id)
    {
        if (!$this->db->update(
                "showtecsystem.parcerias_categorias", ["status" => "inativo"], ["id" => $id])
            )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function realocaOrdens($ordem)
    {
        # Conta ordens iguais
        $ordensRepetidasQtd = $this->db
            ->from("showtecsystem.parcerias_categorias")
            ->where(["ordem" => $ordem])
            ->count_all_results();

        # Realoca a ordem de exibição das categorias
        if ($ordensRepetidasQtd > 0)
        {
            $this->db
                ->set('ordem', 'ordem + 1', FALSE)
                ->where("ordem >= $ordem")
                ->update("showtecsystem.parcerias_categorias");
        }
    }
}