<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cadastro_acessorios extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //recupera o produto através do id
    public function get_by_id($id_acessorio=NULL)
    {
        if ($id_acessorio != NULL):
            $this->db->where('id_acessorio', $id_acessorio);
            $this->db->limit(1);
            $query = $this->db->get("cadastrar_acessorios");
            return $query->row();
        endif;
    }

    public function getAcessorio()
    {

        $query = $this->db->select('*')->from('cadastrar_acessorios')->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return null;
    }

    public function add_acessorio($dados) {
        if(!isset($dados))
            return false;
        $this->db->insert('cadastrar_acessorios', $dados);
    }

    public function editar_cadastro($dados = NUlL, $id_acessorio = NULL)
    {
        if ($dados != NULL && $id_acessorio != NULL):

            $this->db->update('cadastrar_acessorios', $dados, array('id_acessorio'=>$id_acessorio));

        endif;
    }

}