<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consulta_perfil_profissional extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
    * RETORNA CONSULTA DE CPF 
    */
    public function get_consulta_cpf($condicao, $colunas='*'){
        return $this->db->select($colunas)
        ->where($condicao)
        ->get('showtecsystem.consulta_cpf')->row();
    }

    /*
    * RETORNA CONSULTA DE ANTECEDENTES CRIMINAIS 
    */
    public function get_consulta_antecedentes($condicao, $colunas = '*')
    {
        return $this->db->select($colunas)
            ->where($condicao)
            ->get('showtecsystem.consulta_antecedentes')->row();
    }

    /*
    * RETORNA CONSULTA MANDOS DE PRISAO 
    */
    public function get_consulta_mandados($condicao, $colunas = '*')
    {
        return $this->db->select($colunas)
            ->where($condicao)
            ->get('showtecsystem.consulta_mandados')->row();
    }

    /*
    * RETORNA CONSULTA DE CNH 
    */
    public function get_consulta_cnh($condicao, $colunas = '*')
    {
        return $this->db->select($colunas)
            ->where($condicao)
            ->get('showtecsystem.consulta_cnh')->row();
    }

    /*
    * RETORNA CONSULTA DE VEICULO 
    */
    public function get_consulta_veiculo($condicao, $colunas = '*')
    {
        return $this->db->select($colunas)
            ->where($condicao)
            ->get('showtecsystem.consulta_veiculo')->row();
    }
    
    /*
    * RETORNA A RELACAO DE CONSULTAS DO PERFIL
    */
    public function get_relacao_consulta($condicao, $colunas='*'){
        return $this->db->select($colunas)
        ->where($condicao)
        ->get('showtecsystem.perfis_profissionais_consultas')->row();
    }

    /*
    * RETORNA LISTA DE CONSULTAS DE MANDADOS
    */
    public function list_consulta_mandados($condicao, $colunas='*'){
        return $this->db->select($colunas)
        ->join('showtecsystem.relacao_consulta_mandados as r', 'r.id_consulta_mandados = m.id', 'right')
        ->where($condicao)
        ->get('showtecsystem.consulta_mandados as m')->result();
    }

    /*
    * RETORNA LISTA DE CONSULTAS DE MANDADOS
    */
    public function list_consulta_processos($condicao, $colunas='*'){
        return $this->db->select($colunas)
        ->join('showtecsystem.relacao_consulta_processos as r', 'r.id_consulta_processos = m.id', 'right')
        ->where($condicao)
        ->get('showtecsystem.consulta_processos as m')->result();
    }

    /*
    * RECEBE OS DADOS DA RELACAO DO PERFIL COM A CONSULTA
    */
    public function get_relacao_perfil_consulta($condicao, $colunas = '*')
    {
        return $this->db->select($colunas)
            ->where($condicao)
            ->order_by('id', 'desc')
            ->get('showtecsystem.perfis_profissionais_consultas')->row();
    }

     /*
    * GET MANDADOS DE PRISAO JOIN RELACAO
    */
    public function get_consulta_mandados_relacao($condicao, $colunas='*')
    {
        return $this->db->select($colunas)
            ->join('showtecsystem.consulta_mandados as c', 'c.id = rc.id_consulta_mandados', 'left')
            ->where($condicao)
            ->order_by('rc.id', 'desc')
            ->get('showtecsystem.relacao_consulta_mandados as rc')->result();
    }

    /*
    * GET PROCESSOS JOIN RELACAO
    */
    public function get_consulta_processos_relacao($condicao, $colunas='*')
    {
        return $this->db->select($colunas)
            ->join('showtecsystem.consulta_processos as c', 'c.id = rc.id_consulta_processos', 'left')
            ->where($condicao)
            ->order_by('rc.id', 'desc')
            ->get('showtecsystem.relacao_consulta_processos as rc')->result();
    }

    /*
    * RETORNA AS PARTES NOS PROCESSOS
    */
    public function get_partes_por_ids_processos($ids_processos, $colunas = '*')
    {
        return $this->db->select($colunas)
            ->where_in('id_consulta_processos', $ids_processos)
            ->get('showtecsystem.partes_processo')->result();
    }

    /*
    * GET DEBITOS FINANCEIROS JOIN RELACAO
    */
    public function get_consulta_debitos_relacao($condicao, $colunas='*')
    {
        return $this->db->select($colunas)
            ->join('showtecsystem.consulta_debitos as c', 'c.id = rc.id_consulta_debitos', 'left')
            ->where($condicao)
            ->order_by('rc.id', 'desc')
            ->get('showtecsystem.relacao_consulta_debitos as rc')->result();
    }

    /*
    * RETORNA LISTA DE CONSULTAS DE MANDADOS
    */
    public function list_consulta_debitos($condicao, $colunas='*'){
        return $this->db->select($colunas)
        ->join('showtecsystem.relacao_consulta_debitos as r', 'r.id_consulta_debitos = m.id', 'right')
        ->where($condicao)
        ->get('showtecsystem.consulta_debitos as m')->result();
    }


    /*
    * Retorna os dados de uma consulta de cpf apartir do id da relacao da consulta
    */
    public function get_consulta_cpf_by_ids_relacao($ids_consultas, $colunas='*')
    {
        return $this->db->select($colunas)
            ->join('showtecsystem.consulta_cpf as cpf', 'cpf.id = rcpf.id_consulta_cpf', 'left')
            ->where_in('rcpf.id', $ids_consultas)
            ->get('showtecsystem.perfis_profissionais_consultas as rcpf')->result();
    }


}
