<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_licitacao extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /*
    * INSERE TERMO DE ADESAO SIMM2M
    */
    public function insertTermoSim($dados)
    {
        $this->db->insert('systems.termo_adesao_sim', $dados);
        return $this->db->insert_id();
    }

    /*
    * INSERE TERMO DE ADESAO SHOW
    */
    public function insertTermoShow($dados)
    {
        $insert = $this->db->insert('showtecsystem.termo_adesao', $dados);
        return $this->db->insert_id();
    }

    function verificaTermoSim($cnpj_cpf)
    {
        return $this->db->select('id')->get_where('systems.termo_adesao_sim', array('cnpj_cpf' => $cnpj_cpf))->result();
    }

    function verificaTermoShow($cnpj_cpf)
    {
        return $this->db->select('id')->get_where('showtecsystem.termo_adesao', array('cnpj_cpf' => $cnpj_cpf))->result();
    }

    public function getTermos($empresa, $executivo)
    {
        $dados = [];
        $aditivo = '';
        if ($empresa == '2') {
            if (!$this->auth->is_allowed_block('admin_termo')) $this->db->where('id_user', $executivo);
            $query_sim = $this->db->select('id, razao_social, cnpj_cpf, aditivo')->order_by('id', 'desc')->get('systems.termo_adesao_sim')->result();
            foreach ($query_sim as $key => $val) {
                if ($this->auth->is_allowed_block('edit_termo')) $editar = '<button id="btn_editTermo_sim" data-id=' . $val->id . ' class="btn btn-mini btn-info"><i class="fa fa-edit"></i></button>';
                else $editar = '';
                if ($this->auth->is_allowed_block('print_termo')) $print = '<button id="btn_getTermo_sim" data-id=' . $val->id . ' class="btn btn-mini btn-info"><i class="fa fa-print"></i></button>';
                else $print = '';
                if ($this->auth->is_allowed_block('add_termo') && $val->aditivo == 0)
                    $aditivo = '<button id="btn_add_aditivo_sim" data-id=' . $val->id . ' class="btn btn-mini btn-info"><i class="fa fa-print"></i></button>';
                else
                    $aditivo = '<span class="label label-secundary">Aditivo</span>';

                $dados[] = array(
                    'id' => $val->id,
                    'razãoSocial' => $val->razao_social,
                    'cpf/cnpj' => $val->cnpj_cpf,
                    'prestadora' => 'SimM2m',
                    'admin' => $print . ' | ' . $editar . ' | ' . $aditivo
                );
            }
        } else {
            if (!$this->auth->is_allowed_block('admin_termo')) $this->db->where('id_user', $executivo);
            $query_show = $this->db->select('id, razao_social, cnpj_cpf, aditivo')->order_by('id', 'desc')->get('showtecsystem.termo_adesao')->result();
            foreach ($query_show as $key => $val) {
                if ($this->auth->is_allowed_block('edit_termo')) $editar = '<button id="btn_editTermo" data-id=' . $val->id . ' class="btn btn-mini btn-info"><i class="fa fa-edit"></i></button>';
                else $editar = '';
                if ($this->auth->is_allowed_block('print_termo')) $print = '<button id="btn_getTermo" data-id=' . $val->id . ' class="btn btn-mini btn-info"><i class="fa fa-print"></i></button>';
                else $print = '';
                if ($this->auth->is_allowed_block('add_termo') && $val->aditivo == 0)
                    $aditivo = '<button id="btn_add_aditivo" data-id=' . $val->id . ' class="btn btn-mini btn-info"><i class="fa fa-print"></i></button>';
                else
                    $aditivo = '<span class="label label-secundary">Aditivo</span>';


                $dados[] = array(
                    'id' => $val->id,
                    'razãoSocial' => $val->razao_social,
                    'cpf/cnpj' => $val->cnpj_cpf,
                    'prestadora' => 'ShowTecnologia',
                    'admin' => $print . ' | ' . $editar . ' | ' . $aditivo
                );
            }
        }

        echo json_encode($dados);
    }

    function getTermoById($id, $empresa)
    {
        if ($empresa == '2')
            return $this->db->get_where('systems.termo_adesao_sim', array('id' => $id))->row();
        else
            return $this->db->get_where('showtecsystem.termo_adesao', array('id' => $id))->row();
    }

    public function getTermoPrint($id, $m2m = false)
    {
        if (!$m2m)
            return $this->db->where('id', $id)->get('showtecsystem.termo_adesao')->result_array()[0];
        else
            return $this->db->where('id', $id)->get('systems.termo_adesao_sim')->result_array()[0];
    }

    public function getLicitacoes()
    {
        return $this->db->order_by('data_licitacao', 'desc')->where('status', '1')->get('systems.licitacoes')->result();
    }

    public function getDashLicitacoes()
    {
        $data = array();

        // Grafico quantidade de veiculos
        $query = $this->db->select('situacao_final,sum(qtd_veiculos) as qtd')->where('status', '1')->group_by('situacao_final')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $situacao = "";
            if ($linha->situacao_final == '0') {
                $situacao = 'Aguardando';
            } elseif ($linha->situacao_final == '1') {
                $situacao = 'Contrato Assinado';
            } elseif ($linha->situacao_final == '2') {
                $situacao = 'Perdido';
            } elseif ($linha->situacao_final == '3') {
                $situacao = 'Suspenso';
            } elseif ($linha->situacao_final == '4') {
                $situacao = 'Em andamento';
            } elseif ($linha->situacao_final == '5') {
                $situacao = 'Em período de teste';
            } else {
                $situacao = 'Não definido';
            }
            $data['grafico_veiculos'][] = array('situacao_final' => $situacao, 'qtd' => $linha->qtd);
        }

        // Grafico quantidade de licitacoes
        $query = $this->db->select('situacao_final,count(*) as qtd')->where('status', '1')->group_by('situacao_final')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $situacao = "";
            if ($linha->situacao_final == '0') {
                $situacao = 'Aguardando';
            } elseif ($linha->situacao_final == '1') {
                $situacao = 'Contrato Assinado';
            } elseif ($linha->situacao_final == '2') {
                $situacao = 'Perdido';
            } elseif ($linha->situacao_final == '3') {
                $situacao = 'Suspenso';
            } elseif ($linha->situacao_final == '4') {
                $situacao = 'Em andamento';
            } elseif ($linha->situacao_final == '5') {
                $situacao = 'Em período de teste';
            } else {
                $situacao = 'Não definido';
            }
            $data['grafico_licitacao'][] = array('situacao_final' => $situacao, 'qtd' => $linha->qtd);
        }

        // Grafico valores
        $query = $this->db->select('situacao_final,sum(valor_global_arremate) as qtd,sum(qtd_veiculos) as qtd_veiculos,count(*) as contador')->where('status', '1')->group_by('situacao_final')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $situacao = "";
            if ($linha->situacao_final == '0') {
                $situacao = 'Aguardando';
            } elseif ($linha->situacao_final == '1') {
                $situacao = 'Contrato Assinado';
            } elseif ($linha->situacao_final == '2') {
                $situacao = 'Perdido';
            } elseif ($linha->situacao_final == '3') {
                $situacao = 'Suspenso';
            } elseif ($linha->situacao_final == '4') {
                $situacao = 'Em andamento';
            } elseif ($linha->situacao_final == '5') {
                $situacao = 'Em período de teste';
            } else {
                $situacao = 'Não definido';
            }
            $data['grafico_valor'][] = array('situacao_final' => $situacao, 'qtd' => $linha->qtd, 'qtd_veiculos' => $linha->qtd_veiculos, 'contador' => $linha->contador);
        }

        // Quantidade total
        $data['qtd_total'] = $this->db->select('count(*) as qtd')->where('status', '1')->get('systems.licitacoes')->row()->qtd;

        // Quantidade por tipo
        $query = $this->db->select('tipo, count(*) as qtd')->where('status', '1')->group_by('tipo')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $tipo = "";
            if ($linha->tipo == '0') {
                $tipo = 'Presencial';
            } elseif ($linha->tipo == '1') {
                $tipo = 'Eletrônico';
            } elseif ($linha->tipo == '2') {
                $tipo = 'Carona';
            }
            $data['qtd_tipo'][] = array('tipo' => $tipo, 'qtd' => $linha->qtd);
        }

        // Quantidade por esfera
        $query = $this->db->select('esfera, count(*) as qtd')->where('status', '1')->group_by('esfera')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $esfera = "";
            if ($linha->esfera == '0') {
                $esfera = 'Federal';
            } elseif ($linha->esfera == '1') {
                $esfera = 'Estadual';
            } elseif ($linha->esfera == '2') {
                $esfera = 'Municipal';
            }
            $data['qtd_esfera'][] = array('esfera' => $esfera, 'qtd' => $linha->qtd);
        }
        return $data;
    }

    public function getGraficoVeiculos()
    {
        $data = array();

        // Grafico quantidade de veiculos
        $query = $this->db->select('situacao_final,sum(qtd_veiculos) as qtd')->where('status', '1')->group_by('situacao_final')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $situacao = "";
            if ($linha->situacao_final == '0') {
                $situacao = 'Aguardando';
            } elseif ($linha->situacao_final == '1') {
                $situacao = 'Contrato Assinado';
            } elseif ($linha->situacao_final == '2') {
                $situacao = 'Perdido';
            } elseif ($linha->situacao_final == '3') {
                $situacao = 'Suspenso';
            } elseif ($linha->situacao_final == '4') {
                $situacao = 'Em andamento';
            } elseif ($linha->situacao_final == '5') {
                $situacao = 'Em período de teste';
            } else {
                $situacao = 'Não definido';
            }
            $data['grafico_veiculos'][] = array('situacao_final' => $situacao, 'qtd' => $linha->qtd);
        }

        return $data;
    }

    public function getGraficoLicitacoes()
    {
        $data = array();

        // Grafico quantidade de licitacoes
        $query = $this->db->select('situacao_final,count(*) as qtd')->where('status', '1')->group_by('situacao_final')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $situacao = "";
            if ($linha->situacao_final == '0') {
                $situacao = 'Aguardando';
            } elseif ($linha->situacao_final == '1') {
                $situacao = 'Contrato Assinado';
            } elseif ($linha->situacao_final == '2') {
                $situacao = 'Perdido';
            } elseif ($linha->situacao_final == '3') {
                $situacao = 'Suspenso';
            } elseif ($linha->situacao_final == '4') {
                $situacao = 'Em andamento';
            } elseif ($linha->situacao_final == '5') {
                $situacao = 'Em período de teste';
            } else {
                $situacao = 'Não definido';
            }
            $data['grafico_licitacao'][] = array('situacao_final' => $situacao, 'qtd' => $linha->qtd);
        }

        return $data;
    }

    public function getGraficoValores()
    {
        $data = array();

        // Grafico valores
        $query = $this->db->select('situacao_final,sum(valor_global_arremate) as qtd,sum(qtd_veiculos) as qtd_veiculos,count(*) as contador')->where('status', '1')->group_by('situacao_final')->get('systems.licitacoes')->result();
        foreach ($query as $linha) {
            $situacao = "";
            if ($linha->situacao_final == '0') {
                $situacao = 'Aguardando';
            } elseif ($linha->situacao_final == '1') {
                $situacao = 'Contrato Assinado';
            } elseif ($linha->situacao_final == '2') {
                $situacao = 'Perdido';
            } elseif ($linha->situacao_final == '3') {
                $situacao = 'Suspenso';
            } elseif ($linha->situacao_final == '4') {
                $situacao = 'Em andamento';
            } elseif ($linha->situacao_final == '5') {
                $situacao = 'Em período de teste';
            } else {
                $situacao = 'Não definido';
            }
            $data['grafico_valor'][] = array('situacao_final' => $situacao, 'qtd' => $linha->qtd, 'qtd_veiculos' => $linha->qtd_veiculos, 'contador' => $linha->contador);
        }

        return $data;
    }

    public function addLicitacoes($data)
    {
        $this->db->insert('systems.licitacoes', $data);
        return 1;
    }

    public function insertLicitacoes($data)
    {
        return $this->db->insert('systems.licitacoes', $data);
    }

    public function getLicitacao($id)
    {
        return $this->db->where('id', $id)->get('systems.licitacoes')->row();
    }
    public function updateLicitacoes($id, $data)
    {
        $this->db->where('id', $id)->update('systems.licitacoes', $data);
        return 1;
    }

    public function listTermosShow($executivo)
    {
        if (!$this->auth->is_allowed_block('admin_termo')) $this->db->where('id_user', $executivo);
        $query = $this->db->select('id, razao_social, cnpj_cpf, aditivo_de')
            ->where('aditivo_de IS NULL')
            ->order_by('id', 'desc')
            ->get('showtecsystem.termo_adesao');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function listTermosShowCuritiba($executivo)
    {
        if (!$this->auth->is_allowed_block('admin_termo')) $this->db->where('id_user', $executivo);
        $query = $this->db->select('id, razao_social, cnpj_cpf, aditivo_de')
            ->where('aditivo_de IS NULL')
            ->where('empresa', 3)
            ->order_by('id', 'desc')
            ->get('showtecsystem.termo_adesao');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function listTermosSim($executivo)
    {
        if (!$this->auth->is_allowed_block('admin_termo')) $this->db->where('id_user', $executivo);
        $query = $this->db->select('id, razao_social, cnpj_cpf, aditivo_de')
            ->where('aditivo_de IS NULL')
            ->order_by('id', 'desc')
            ->get('systems.termo_adesao_sim');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    //ATUALIZA TERMO SHOW
    public function atulizarTermoShow($id_termo, $dados)
    {
        $this->db->update('showtecsystem.termo_adesao', $dados, array('id' => $id_termo));
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    //ATUALIZA TERMO SIMM2M
    public function atulizarTermoSim($id_termo, $dados)
    {
        $this->db->update('systems.termo_adesao_sim', $dados, array('id' => $id_termo));
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    //RETORNA OS ADITIVOS DE UM TERMO
    function getAditivoById($id_termo, $empresa, $select = '*')
    {
        if ($empresa == '2')
            return $this->db->select($select)->get_where('systems.termo_adesao_sim', array('aditivo_de' => $id_termo))->result();
        else
            return $this->db->select($select)->get_where('showtecsystem.termo_adesao', array('aditivo_de' => $id_termo))->result();
    }
}
