
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CategoriasM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id_categoria=NULL)
    {
        if ($id_categoria != NULL):
            $this->db->where('id', $id_categoria);
            $this->db->limit(1);

            $query = $this->db->get("categoria");
            return $query->row();

        endif;
    }

    public function getCat()
    {
        $query = $this->db->select('*')->from('categoria')->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return null;
    }


    public function addCategoria($dados, $id){

        $query = $this->db->select('*')->from('categoria')->where("id = '{$id}'")->get();

        if($query->num_rows()== false){
            $this->db->insert('categoria', $dados);
            return true;
        }else{
            return false;
        }

    }

    public function add_categoria($dados) {
        if(!isset($dados))
            return false;
        $this->db->insert('categoria', $dados);
    }

    public function editar_cadastro($idsub, $dados = NUlL, $id = NULL)
    {
        if ($dados != NULL && $id != NULL && $idsub != NULL):

            $this->db->update('categoria', $dados, array('id'=>$id));
            $this->db->update('subcategoria', $dados, array('id_sub'=>$idsub));

        endif;
    }

}