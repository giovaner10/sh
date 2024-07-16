<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Cadastro_centrais extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    //pegar centrais cadastradas ## show-479
    public function getCentrais()
    {

        $query = $this->db->select('*')->from('showtecsystem.cadastro_centrais')->get();
        if ($query->num_rows() > 0) {
          
             return $query->result();            
        }
        return null;
    }

    
    //criar cadastro da central ## show-479
    public function add_central($dados) {
        if(!isset($dados)){
            return false;
        }
        else{
            
            //verificar se já existe a central MHS
            //informar que não pode campo duplicado

            $query = $this->db->select('*')->from('showtecsystem.cadastro_centrais')->where("id_central_mhs = '{$dados['id_central_mhs']}'")->get();
            if ($query->num_rows() > 0) {
                return false;            
            }
            else {
                $retorno = $this->db->insert('showtecsystem.cadastro_centrais', $dados);
                
                if ($retorno){
                    return true;                    
                } 
                else {
                    return false;
                }  
            }        
        }        
    }

    public function inserir_centrais_MHS($dados){
        error_reporting(0);
        $this->load->helper('util_helper');

        $Usuario = $dados['cliente'];        
        $Nome    = $dados['nome']; 
        $IP      = $dados['ip']; 
        $Porta   = $dados['porta']; 
        $CNPJ    = $dados['cnpj'];        
        
        $response = to_IncluiCentralCliente($Usuario, $Nome, $IP, $Porta, $CNPJ);
        $retorno = json_decode($response);
        
        if ($retorno) {
            
            return $retorno;
        } 
        else {
            return false;
        }	        
       	
    }


     public function editar_cadastro_centrais($dados = NUlL, $id = NULL)
        {
            if ($dados != NULL && $id != NULL):
                $this->db->update('cadastrar_centrais', $dados, array('id'=>$id));
            endif;
        }

}