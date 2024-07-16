<?php

date_default_timezone_set('America/Recife');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tarefa extends CI_Model {
    private $db_rastreamento;

    public function __construct() {
        parent::__construct();

        $this->db_rastreamento = $this->load->database('showtecsystem');
    }

    public function cad_atividade($dados) {
        $retorno = $this->db->insert('atividade', $dados);
        return $retorno;
    }

    public function get_atividades($pag_ini, $pag_fim) {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('atividade', $pag_fim, $pag_ini);

        return $query->result();;
    }

    public function get_atividade($id_ativ) {
        $query = $this->db->where('id', $id_ativ)->get('atividade')->result();
        return $query;
    }

    public function get_dev_atividades($id) {
        $this->db->where('id_desenvolvedor', $id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('atividade');

        return $query->result();
    }

    public function get_tot_atividades() {
        $query = $this->db->count_all('atividade');
        return $query;
    }

    public function get_nome_desenvolvedor($id) {
        $query = $this->db->select('nome')->where('id',$id)->get('usuario')->result();
        if ($query) {
            $result = $query[0]->nome;
            return $result;
        } else {
            return false;
        }
        
    }

    public function get_nome_status($id) {
        $query = $this->db->select('descricao')->where('id',$id)->get('status_atividades')->result();
        if ($query) {
            $result = $query[0]->descricao;
            return $result;
        } else {
            return false;
        } 
    }

    public function get_id_dev($dev) {
        $query = $this->db->select('id')->where('nome', $dev)->get('usuario')->result();
        return $query[0];
    }

    public function list_devs() {
        $this->db->select('nome, id');
        $this->db->where('funcao', 'dev');
        $query = $this->db->get('usuario')->result();

        return $query;
    }

    /* --->>> Função não utilizada, agora é pelo cadastro dos usuarios do shownet <<<----

    public function add_dev($dev) {
        // Verifica se já existe cadastro //
        $verifica = $this->get_id_dev($dev['nome']);

        if (!$verifica) {
            $retorno = $this->db->insert('cad_desenvolvedor', $dev);
            return $retorno;
        } else {
            return false;
        }
    }*/

    public function iniciar_ativ($id) {
        $dados = array('inicio' => date('Y-m-d H:i:s'), 'id_status' => 5);
        $retorno = $this->db->update('atividade', $dados, "id = $id");
        return $retorno;
    }

    public function finalizar_ativ($id) {
        $dados = array('fim' => date('Y-m-d H:i:s'), 'id_status' => 6);
        $retorno = $this->db->update('atividade', $dados, "id = $id");
        return $retorno;
    }

    public function cancela_atividade($id, $status) {
        if ($status != 6 && $status != 10) {
            $dados = array('id_status' => 10);
            $retorno = $this->db->update('atividade', $dados, "id = $id");
            return $retorno;
        } else {
            return false;
        }  
    }

    public function get_grafic_andamento($id) {
        $sql = "SELECT id FROM atividade
        WHERE prazo >= date_sub(now(), interval 30 day)
        AND id_desenvolvedor = $id
        AND id_status = 5;";
        $query = $this->db->query($sql)->num_rows;

        return $query;
    }

    public function get_grafic_pendente($id) {
        $sql = "SELECT id FROM atividade
        WHERE prazo >= date_sub(now(), interval 30 day)
        AND id_desenvolvedor = $id
        AND id_status = 4;";
        $query = $this->db->query($sql)->num_rows();
        
        return $query;
    }

    public function get_grafic_cancelado($id) {
        $sql = "SELECT id FROM atividade
        WHERE prazo >= date_sub(now(), interval 30 day)
        AND id_desenvolvedor = $id
        AND id_status = 10;";
        $query = $this->db->query($sql)->num_rows();

        return $query;
    }
    public function get_grafic_concluido_d($id) {
        $sql = "SELECT id FROM atividade
        WHERE prazo >= date_sub(now(), interval 30 day)
        AND id_desenvolvedor = $id
        AND id_status = 6;";
        $query = $this->db->query($sql)->num_rows();

        return $query;
    }

    public function get_grafic_concluido_f($id) {
        $sql = "SELECT id FROM atividade
        WHERE prazo >= date_sub(now(), interval 30 day)
        AND id_desenvolvedor = $id
        AND id_status = 6;";
        $query = $this->db->query($sql)->num_rows();

        return $query;
    }

    public function transferir($id_ativ, $id_dev) {
        $validacao = $this->get_atividade($id_ativ);
        if ($validacao[0]->id_desenvolvedor != $id_dev) {

            $dados = array('id_desenvolvedor' => $id_dev, 'inicio' => NULL, 'id_status' => 4);
            $retorno = $this->db->update('atividade', $dados, "id = $id_ativ");
            return true;

        } else {
            return false;
        }
    }
}