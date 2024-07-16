<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SubcategoriasM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_by_idSub($id_sub=NULL){
        if ($id_sub != NULL):
            $this->db->where('id_sub', $id_sub);
            $this->db->limit(1);

            $query = $this->db->get("subcategoria");
            return $query->row();

        endif;
    }

    public function getCatsub()
    {
        $query = $this->db->select('*')->from('subcategoria')->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return null;
    }

    public function addSubcategoria($dados, $id_sub){

        $query = $this->db->select('*')->from('subcategoria')->where("id_sub = '{$id_sub}'")->get();

        if($query->num_rows()== false){
            $this->db->insert('subcategoria', $dados);
            return true;
        }else{
            return false;
        }

    }

    public function add_subcategoria($dados) {
        if(!isset($dados))
            return false;
        $this->db->insert('subcategoria', $dados);
    }

}