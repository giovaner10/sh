<?php

date_default_timezone_set('America/Recife');

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Log_shownet extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		
	}
    /*
    Parâmetros:
    $id_usuario: id do usuário que realizou a ação
    $tabela_alterada: tabela que foi alterada
    $id_registro_alterado: id do registro que foi alterado
    $evento: evento que foi realizado (criar, atualizar, deletar)
    $valor_anterior: array valores anteriores dos registros
    $valor_novo: array valores novos dos registros
    */
    public function gravar_log($id_usuario,$tabela_alterada,$id_registro_alterado,$evento,$valor_anterior,$valor_novo) {
        $valor_novo_formatado = "";
        $valor_anterior_formatado = "";
        //verifica se valor anterior e valor novo são arrays
        if(is_array($valor_novo)){
            //formata os dados para salvar como string no log 
            foreach ($valor_novo as $key => $value) {
                if(is_array($value)){
                    $value = json_encode($value);
                }
                $valor_novo_formatado .= $key.": ".$value.", ";
            }
            $valor_novo = $valor_novo_formatado;    
        }
        if(is_array($valor_anterior)){
            //formata os dados para salvar como string no log 
            foreach ($valor_anterior as $key => $value) {
                if(is_array($value)){
                    $value = json_encode($value);
                }
                $valor_anterior_formatado .= $key.": ".$value.", ";
            }
            $valor_anterior = $valor_anterior_formatado;    
        }


        $dados = array(
            'id_usuario' => $id_usuario,
            'tabela_alterada' => $tabela_alterada,
            'id_registro_alterado' => $id_registro_alterado,
            'evento' => $evento,
            'valor_anterior' => $valor_anterior,
            'valor_novo' => $valor_novo,
        );
        return $this->db->insert('showtecsystem.auditoria_shownet', $dados);
    }

    /*
    Parâmetros:
    $limit: limite de registros que serão retornados
    $tabelas: array ou string com as tabelas que serão consultadas
    $id_usuario: id do usuário que realizou a ação
    $id_registro: id do registro que foi alterado
    $data_inicial: data inicial para consulta
    $data_final: data final para consulta
    */
    public function carregar_dados_log($tabelas, $id_usuario=null, $id_registro=null, $data_inicial=null, $data_final=null, $limit=null, $offset=null) {
        $this->db->select('auditoria_shownet.*, usuario.nome');
        $this->db->from('showtecsystem.auditoria_shownet');
        $this->db->join('showtecsystem.usuario', 'usuario.id = auditoria_shownet.id_usuario');
        if($id_usuario != null){
            $this->db->where('auditoria_shownet.id_usuario', $id_usuario);
        }
        if($id_registro != null){
            $this->db->where('auditoria_shownet.id_registro_alterado', $id_registro);
        }
        if($data_inicial != null){
            $this->db->where('auditoria_shownet.datahora >=', $data_inicial);
        }
        if($data_final != null){
            $this->db->where('auditoria_shownet.datahora <=', $data_final);
        }
        if(is_array($tabelas)){
            $this->db->where_in('auditoria_shownet.tabela_alterada', $tabelas);
        }else{
            $this->db->where('auditoria_shownet.tabela_alterada', $tabelas);
        }
        if($limit != null){
            $this->db->limit($limit);
        }
        if($offset != null){
            $this->db->offset($offset);
        }
        $this->db->order_by('auditoria_shownet.datahora', 'desc');
        return $this->db->get()->result();
    }

    public function obter_dados_log($tabelas, $id_usuario=null, $id_registro=null, $evento=null, $data_inicial=null, $data_final=null, $limit=null, $offset=null) {
        $this->db->select('auditoria_shownet.*, usuario.nome');
        $this->db->from('showtecsystem.auditoria_shownet');
        $this->db->join('showtecsystem.usuario', 'usuario.id = auditoria_shownet.id_usuario');
        if($id_usuario != null){
            $this->db->where('auditoria_shownet.id_usuario', $id_usuario);
        }
        if($id_registro != null){
            $this->db->where('auditoria_shownet.id_registro_alterado', $id_registro);
        }
        if($evento != null){
            $this->db->where('auditoria_shownet.evento', $evento);
        }
        if($data_inicial != null){
            $this->db->where('auditoria_shownet.datahora >=', $data_inicial);
        }
        if($data_final != null){
            $this->db->where('auditoria_shownet.datahora <=', $data_final);
        }
        if(is_array($tabelas)){
            $this->db->where_in('auditoria_shownet.tabela_alterada', $tabelas);
        }else{
            $this->db->where('auditoria_shownet.tabela_alterada', $tabelas);
        }
        if($limit != null){
            $this->db->limit($limit);
        }
        if($offset != null){
            $this->db->offset($offset);
        }
        $this->db->order_by('auditoria_shownet.datahora', 'desc');
        return $this->db->get()->result();
    }

    public function contar_dados_log($tabelas, $id_usuario=null, $id_registro=null, $evento=null, $data_inicial=null, $data_final=null) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('showtecsystem.auditoria_shownet');
        $this->db->join('showtecsystem.usuario', 'usuario.id = auditoria_shownet.id_usuario');
        if($id_usuario != null){
            $this->db->where('auditoria_shownet.id_usuario', $id_usuario);
        }
        if($id_registro != null){
            $this->db->where('auditoria_shownet.id_registro_alterado', $id_registro);
        }
        if($evento != null){
            $this->db->where('auditoria_shownet.evento', $evento);
        }
        if($data_inicial != null){
            $this->db->where('auditoria_shownet.datahora >=', $data_inicial);
        }
        if($data_final != null){
            $this->db->where('auditoria_shownet.datahora <=', $data_final);
        }
        if(is_array($tabelas)){
            $this->db->where_in('auditoria_shownet.tabela_alterada', $tabelas);
        }else{
            $this->db->where('auditoria_shownet.tabela_alterada', $tabelas);
        }
        $result = $this->db->get()->row();
        return $result->total;
    }

    public function carregar_tabelas_log() {
        $this->db->select('*');
        $this->db->from('showtecsystem.tabelas_auditoria_shownet');
        return $this->db->get()->result();
    }
}


