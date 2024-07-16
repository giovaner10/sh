<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil_Profissional extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
    * LISTA OS PERFIS DE POROFISSIONAIS 
    * (SE PASSAR O ID DE CLIENTE COMO ARGUMENTO, TRARÁ APENAS OS PERFIS VINCULADOS AO CLIENTE)
    */
    public function list_perfis($colunas='*', $where=[], $estrutura_retorno='object', $id_cliente=false, $tipo_profissional=false){
        $this->db->select($colunas);
        $this->db->join('showtecsystem.profissionais as p', 'p.id = pp.id_profissionais', 'left');
        if ($id_cliente) $this->db->where('p.id_cad_clientes', $id_cliente);
        if ($tipo_profissional) $this->db->where('p.tipo_profissional', $tipo_profissional);
        $this->db->where($where);
        $query = $this->db->get('showtecsystem.perfis_profissionais as pp');
        return $estrutura_retorno === 'object' ? $query->result() : $query->result_array();
    }

    /*
    * GET PERFIS DE PROFISSIONAIS SERVE-SIDE
    */
    public function getPerfisprofissionaisServerSide($select, $where, $order, $start, $limit, $search, $filtro, $draw, $qtdTotal) {
        $colunas = array('pp.id', 'p.nome', 'pp.data_cadastro', 'pp.status_analise', 'pp.ultima_analise');

        $this->db->select($select);
        $this->db->join('showtecsystem.profissionais as p', 'p.id = pp.id_profissionais', 'left');

        if($search) {
            if ($filtro == 'data_cadastro') {
                $this->db->where(array('date(pp.data_cadastro)' => $search ));
            } 
            else {
                $this->db->where('pp.'.$filtro, $search);
            }
        }

        $this->db->where($where);
        
        if ($order) $this->db->order_by($colunas[$order['column']], $order['dir']);
        else $this->db->order_by('pp.id', 'DESC');
        
        if ($qtdTotal) $query = $this->db->count_all_results('showtecsystem.perfis_profissionais as pp');
        else $query = $this->db->get('showtecsystem.perfis_profissionais as pp', $limit, $start);

		return $query;
    }

    /*
    * LISTA PERFIS DE PROFISSIONAIS EM SERVE-SIDE
    */
    public function listPerfisProfissionaisServerSide($select='*', $where=[], $order=false, $start=0, $limit=999999, $search=false, $filtro='', $draw=1, $estrutura_retorno='object') {
        $dados = array();
	    $query = $this->getPerfisprofissionaisServerSide($select, $where, $order, $start, $limit, $search, $filtro, $draw, false);
        $queryQtdTotal = $this->getPerfisprofissionaisServerSide('pp.id', $where, $order, $start, $limit, $search, $filtro, $draw, true);

        if($query->num_rows){
			$dados = array(
                'perfisProfissionais' => $estrutura_retorno === 'object' ? $query->result() : $query->result_array(), # Lista de eventos
                'recordsTotal' => $queryQtdTotal, # Total de registros
                'recordsFiltered' => $queryQtdTotal, # atribui o mesmo valor do recordsTotal ao recordsFiltered para ter todas as paginas na datatable
                'draw' => $draw++ # Draw do datatable
            );
		}

		return $dados;
    }

    /*
    * PESQUISA PROFISSIONAIS COM FILTRO - SELECT2 AJAX
    */
    public function listarProfissionaisFilter($search) {
        if ($search && is_numeric($search)) { # Se existir e for numerico, verifica pelo ID
            return $this->db->like('id', $search)->limit(10)->get('showtecsystem.profissionais')->result();
        } elseif ($search && is_string($search)) { # Se existir e for string, verifica pelo NOME
            return $this->db->like('nome', $search)->limit(10)->get('showtecsystem.profissionais')->result();
        } else { # Se nao for verdadeiro, retorna array vazio
            return array();
        }
    }

    /*
    * LISTA O PERFIL DO PROFISSIONAL
    */
    public function get_perfil($condicao, $colunas='*'){
        return $query = $this->db->select($colunas)
        ->where($condicao)
        ->get('showtecsystem.perfis_profissionais')->row();
    }

    /*
    * ATUALIZA UM PERFIL
    */
    public function update_perfil($id, $dados){
        $this->db->update('showtecsystem.perfis_profissionais', $dados, array('id' => $id));        
        return $this->db->affected_rows();
    }

    /*
    * LISTA O LOG
    */
    public function get_log($condicao, $colunas='*'){
        return $query = $this->db->select($colunas)
        ->where($condicao)
        ->get('showtecsystem.log_perfis_profissionais')->row();
    }
    
    /*
    * CADASTRA A ACAO DO USUARIO AO LOG DE PERFIS DE PEOFISSIONAIS  
    */
    public function add_log($dados)
    {
        $this->db->insert('showtecsystem.log_perfis_profissionais', $dados);
        return $this->db->insert_id();
    }

}
