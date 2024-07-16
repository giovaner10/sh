<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Veiculo extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('util_helper');
        $this->rastreamento = $this->load->database('rastreamento', TRUE);
    }

    public function get_veiculo($where, $limite = 9999999, $group_by = false)
    {

        $this->db->where($where);
        $this->db->limit($limite);
        if ($group_by)
            $this->db->group_by($group_by);

        $query = $this->db->get('systems.cadastro_veiculo');

        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }

    public function get_veiculos_view($code)
    {
        $this->db->where('code', $code);
        $query = $this->db->get('systems.cadastro_veiculo');

        if ($query->num_rows() > 0) {
            $resultado = $query->row();

            $query = $this->rastreamento->select('ODOMETER, HOURMETER')->where(array('ID' => $resultado->serial))->get('rastreamento.last_track');

            if ($query->num_rows()) {
                $resultado->hodometro = intval($query->row()->ODOMETER / 1000);
                $resultado->horimetro = intval($query->row()->HOURMETER / 60);
            } else {
                $resultado->hodometro = '';
                $resultado->horimetro = '';
            }

            return $resultado;
        }

        return array();
    }

    public function contrato_veiculo($placa)
    {

        $this->db->select('id_contrato');
        $this->db->where('placa', $placa);
        $this->db->where('status', 'ativo');
        $query_contratos_veiculos = $this->db->get('showtecsystem.contratos_veiculos');

        if ($query_contratos_veiculos->num_rows()) {
            // $id = $query_contratos_veiculos->row()->id_contrato;
            $ids = '';

            foreach ($query_contratos_veiculos->result() as $contrato_veiculo) {

                $this->db->where('id', $contrato_veiculo->id_contrato);
                $this->db->where_in('status', array(1, 2));
                $query_contratos = $this->db->get('showtecsystem.contratos');

                if ($query_contratos->num_rows()) {
                    $ids[] = $contrato_veiculo->id_contrato;
                }
            }

            return is_array($ids) ? implode(', ', $ids) : $ids;
        }

        return null;
    }

    public function total_veiculos_contratos($id_cliente)
    {

        $subqry = "SELECT id FROM showtecsystem.contratos WHERE status = 1 OR status = 2";

        $query = $this->db->select('veic.id, veic.placa, veic.id_contrato')
            ->where('veic.id_cliente', $id_cliente)
            ->where("veic.id_contrato IN ({$subqry})")
            ->where('veic.status', 'ativo')
            ->get('showtecsystem.contratos_veiculos as veic');

        return $query->num_rows();
    }

    public function listar_veiculos_gestor($id_cliente, $paginacao = 0, $limite = 9999999, $campo_ordem = 'veic.code', $ordem = 'DESC')
    {
        $retorno = array();

        $this->db->select('*');
        $this->db->where(array('id_usuario' => $id_cliente, 'status' => 1));
        $this->db->order_by('placa');
        $this->db->group_by(array('placa'));
        $this->db->limit($limite, $paginacao);
        $query_veiculos = $this->db->get('systems.cadastro_veiculo');

        foreach ($query_veiculos->result() as $veiculo) {
            $veiculo->contrato = $this->contrato_veiculo($veiculo->placa);
            if ($veiculo->contrato != '') {
                $retorno[] = $veiculo;
            }
        }

        return $retorno;
    }

    public function log_ultima_atualizacao($code)
    {
        $this->db->select('data');
        $this->db->where("veiculo", $code);
        $this->db->order_by('id', 'desc');
        $retorno = $this->db->get('showtec.usuarios_acoes')->result();
        return $retorno;
    }
    public function total_lista_veiculos_gestor($id_cliente)
    {
        $resultado = array();
        // BUSCA ID GRUPO MASTER DO CLIENTE
        $id_group = $this->db->select('id')
            ->where('id_cliente', $id_cliente)
            ->where('nome', 'MASTER')
            ->get('showtecsystem.cadastro_grupo')
            ->row();

        if ($id_group) {
            // CONTA PLACAS NO GRUPO MASTER DO CLIENTE
            $resultado = $this->db->select('placa')
                ->group_by('placa')
                ->get_where('showtecsystem.veic_x_group', array('groupon' => $id_group->id, 'status' => 1))
                ->result();
        }

        return count($resultado);
    }

    /*public function listar_gestor($id_cliente, $paginacao = 0, $limite = 9999999, $campo_ordem = 'veic.code', $ordem = 'DESC') {

        $subqry = "SELECT id FROM showtecsystem.contratos WHERE status = 1 OR status = 2";

        $query = $this->db->select('veic.id, veic.placa, veic.id_contrato')
                          ->where('veic.id_cliente', $id_cliente)
                          ->where("veic.id_contrato IN ({$subqry})")
                          ->where('veic.status', 'ativo')
                          ->limit($limite, $paginacao)
                          ->get('showtecsystem.contratos_veiculos as veic');

        return $query->result();
    }*/

    public function listar_gestor($where, $paginacao = 0, $limite = 9999999, $campo_ordem = 'veic.code', $ordem = 'DESC')
    {

        $this->db->select('code, CNPJ_');
        $this->db->where($where);
        $query_usuarios = $this->db->get('showtecsystem.usuario_gestor');

        if ($query_usuarios->num_rows()) {
            $usuarios = $query_usuarios->result();

            foreach ($usuarios as $usuario) {
                $this->db->or_where('CNPJ_', $usuario->CNPJ_);
            }

            $this->db->select('code, veiculo, placa, serial');
            $this->db->group_by(array('veiculo', 'placa', 'serial'));
            $this->db->limit($limite, $paginacao);
            $query_veiculos = $this->db->get('systems.cadastro_veiculo');

            foreach ($query_veiculos->result() as $veiculo) {
                $veiculo->contrato = $this->contrato_veiculo($veiculo->placa);
                $retorno[] = $veiculo;
            }

            return $retorno;
        }

        return false;
    }

    public function total_lista_gestor($where)
    {

        $this->db->select('code, CNPJ_');
        $this->db->where($where);
        $query_usuarios = $this->db->get('showtecsystem.usuario_gestor');

        if ($query_usuarios->num_rows()) {
            $usuarios = $query_usuarios->result();

            foreach ($usuarios as $usuario) {
                $this->db->or_where('CNPJ_', $usuario->CNPJ_);
            }

            $this->db->select('code, veiculo, placa, serial');
            $this->db->group_by(array('veiculo', 'placa', 'serial'));
            $query_veiculos = $this->db->get('systems.cadastro_veiculo');

            return $query_veiculos->num_rows();
        }

        return 0;
    }

    public function filtrar_gestor($id_cliente, $like, $paginacao = 0, $limite = 9999999, $campo_ordem = 'veic.code', $ordem = 'DESC')
    {

        $subq = "SELECT CNPJ_ FROM showtecsystem.usuario_gestor WHERE id_cliente = {$id_cliente} AND status_usuario = 'ativo'";

        $this->db->select('placa, code, veiculo, serial');
        $this->db->where("CNPJ_ IN ({$subq})");
        $this->db->like('placa', $like);
        $this->db->order_by('placa');
        $this->db->group_by(array('placa'));
        $this->db->limit($limite, $paginacao);
        $query_veiculos = $this->db->get('systems.cadastro_veiculo');

        foreach ($query_veiculos->result() as $veiculo) {
            $veiculo->contrato = $this->contrato_veiculo($veiculo->placa);
            $retorno[] = $veiculo;
        }

        return $retorno;
    }

    /*public function filtrar_gestor($where, $like, $paginacao = 0, $limite = 9999999, $campo_ordem = 'veic.code', $ordem = 'DESC') {

        $subq = "(SELECT CNPJ_ FROM usuario_gestor WHERE {$where})";

        $query = $this->db->select('veic.*, veic_cont.id_contrato contrato')
                ->join('contratos_veiculos as veic_cont', 'veic_cont.placa = veic.placa', 'left')
                ->where("veic.CNPJ_ IN ({$subq})")
                ->like('veic.placa', $like, 'after')
                ->order_by($campo_ordem, $ordem)
                ->get('systems.cadastro_veiculo as veic', $limite, $paginacao);

        return $query->result();
    }*/

    public function total_filtro_gestor($id_cliente, $like)
    {

        $subq = "SELECT CNPJ_ FROM showtecsystem.usuario_gestor WHERE id_cliente = {$id_cliente} AND status_usuario = 'ativo'";

        $this->db->select('placa, code, veiculo, serial');
        $this->db->where("CNPJ_ IN ({$subq})");
        $this->db->like('placa', $like);
        $this->db->order_by('placa');
        $this->db->group_by(array('placa'));
        $query_veiculos = $this->db->get('systems.cadastro_veiculo');

        return $query_veiculos->num_rows();
    }

    /*public function total_filtro_gestor($where, $like) {

        $subq = "(SELECT CNPJ_ FROM usuario_gestor WHERE {$where})";

        $total = $this->db->select('veic.*, veic_cont.id_contrato contrato')
                ->join('contratos_veiculos as veic_cont', 'veic_cont.placa = veic.placa', 'left')
                ->where("veic.CNPJ_ IN ({$subq})")
                ->like('veic.placa', $like)
                ->from('systems.cadastro_veiculo as veic')
                ->count_all_results();

        return $total;
    }*/

    public function total_veiculos_contrato($where)
    {

        $total = 0;

        $this->db->select('CNPJ_');
        $this->db->where($where);
        $query_usuarios = $this->db->get('showtecsystem.usuario_gestor');

        if ($query_usuarios->num_rows()) {
            $usuarios = $query_usuarios->result();

            foreach ($usuarios as $usuario) {
                $this->db->or_where('CNPJ_', $usuario->CNPJ_);
            }

            $this->db->select('placa');
            $this->db->group_by(array('veiculo', 'placa', 'serial'));
            $query_veiculos = $this->db->get('systems.cadastro_veiculo');

            foreach ($query_veiculos->result() as $veiculo) {
                $this->db->select('id_contrato');
                $this->db->where('placa', $veiculo->placa);
                $query_contratos_veiculos = $this->db->get('showtecsystem.contratos_veiculos');
                $total += $query_contratos_veiculos->num_rows() ? 1 : 0;
            }
        }

        return $total;

        // $contratos = "(SELECT id FROM contratos WHERE {$where} AND status IN (1,2))";
        // $total = $this->db->from('contratos_veiculos as veic')
        // 				  ->where("veic.id_contrato IN ({$contratos}) AND veic.status = 'ativo'")
        // 				  ->count_all_results();
        // return $total;
    }

    // -----------------------------------------------------------------------------------------
    // DEVELOPER: ERICK AMARAL
    // -----------------------------------------------------------------------------------------

    /**
     * Verifica e atualiza o status dos veículos desatualizados.
     *
     * @return boolean
     * */
    public function checkout()
    {
        $hoje = new DateTime();

        $query_veiculos = $this->db
            ->select('v.veiculo as nome, v.placa, v.serial, r.DATA as data')
            ->from('systems.cadastro_veiculo v')
            ->join('systems.resposta r', 'v.serial = r.ID')
            ->where(array('placa !=' => '', 'serial !=' => ''))
            ->group_by(array('placa', 'serial'))
            ->get();

        if ($query_veiculos->num_rows()) {

            $veiculos = $query_veiculos->result();

            foreach ($veiculos as $veiculo) {

                $status = $hoje->diff(new DateTime($veiculo->data))->d >= 2 ? '2' : '1';

                $query_status = $this->db
                    ->where(array('placa' => $veiculo->placa, 'serial' => $veiculo->serial))
                    ->get('showtec.veiculos_status');

                if ($query_status->num_rows()) {

                    $anterior = $query_status->row();

                    if ($status === '1') {

                        $atualizar = array(
                            'status' => '1',
                            'enviado' => '0',
                            'enviado_data' => '',
                            'ultima_atualizacao' => $veiculo->data,
                            'data_manutencao' => '',
                            'data_correcao' => '',
                            'observacoes' => '',
                        );

                        $this->db->where('id', $anterior->id)->update('showtec.veiculos_status', $atualizar);
                    } else {

                        if ($anterior->status === '3') {

                            $data_manutencao = new DateTime($anterior->data_manutencao);

                            if ($data_manutencao < $hoje) {

                                $atualizar = array(
                                    'status' => '2',
                                    'enviado' => '0',
                                    'enviado_data' => '',
                                    'ultima_atualizacao' => $veiculo->data,
                                    'data_manutencao' => '',
                                    'data_correcao' => '',
                                    'observacoes' => '',
                                );

                                $this->db->where('id', $anterior->id)->update('showtec.veiculos_status', $atualizar);
                            }
                        }
                    }
                } else {

                    $inserir = array(
                        'veiculo' => $veiculo->nome,
                        'placa' => $veiculo->placa,
                        'serial' => $veiculo->serial,
                        'status' => $status,
                        'ultima_atualizacao' => $veiculo->data,
                    );

                    $this->db->insert('showtec.veiculos_status', $inserir);
                }
            }
        }

        return true;
    }

    /**
     * Resgata os veículos que estiverem desatualizados.
     *
     * @return mixed
     * */
    public function get_desatualizados($like = null, $limite = null, $offset = null)
    {
        $this->db->where_in('status', array('2', '3'));

        if ($like) {
            $this->db->like($like);
        }

        if (is_numeric($limite) && is_numeric($offset)) {
            $this->db->limit($offset, $limite);
        }

        $this->db->group_by(array('placa', 'serial'));
        $this->db->order_by('ultima_atualizacao', 'asc');

        $query = $this->db->get('showtec.veiculos_status');

        if ($query->num_rows()) {
            return $query->result();
        }

        return false;
    }

    /**
     * Resgata os veículos que estiverem desatualizados para enviar email.
     *
     * @return mixed
     * */
    public function get_desatualizados_enviar($cnpj)
    {
        $desatualizados = array();

        $query = $this->db->select('placa, serial')->where('CNPJ_', $cnpj)->group_by(array('placa', 'serial'))->get('systems.cadastro_veiculo');

        if ($query->num_rows()) {

            $veiculos = $query->result();

            foreach ($veiculos as $id => $veiculo) {

                //$query = $this->dev->where(array('status' => '2', 'placa' => $veiculo->placa, 'serial' => $veiculo->serial))->group_by(array('placa', 'serial'))->get('veiculos_status');
                $query = $this->db->where(array('status' => '2', 'placa' => $veiculo->placa, 'serial' => $veiculo->serial))->group_by(array('placa', 'serial'))->get('veiculos_status');

                if ($query->num_rows()) {
                    $desatualizados[] = $query->row();
                }
            }
        }

        return $desatualizados;
    }

    /**
     * Pega o usuário e o cliente ao qual este veículo está registrado.
     *
     * @param array $where
     * @return mixed
     * */
    public function find_usuario_cliente($where)
    {
        $query = $this->db->select('u.usuario')
            ->from('systems.cadastro_veiculo as v')
            ->join('showtecsystem.usuario_gestor as u', 'v.CNPJ_ = u.CNPJ_')
            ->where($where)
            ->get();

        if ($query->num_rows()) {
            return $query->result();
        }

        return false;
    }

    /**
     * Salva os dados da manutenção
     *
     * @param array $data
     * @return mixed
     * */
    public function salva_manutencao($data)
    {
        $atualizar = array(
            'status' => '3',
            'data_manutencao' => data_for_unix($data['data']) . ' ' . $data['hora'],
            'observacoes' => $data['observacoes'],
        );

        return $this->db->where('id', $data['id'])->update('showtec.veiculos_status', $atualizar);
    }

    /**
     * Atualiza o status do veículo desatualizado enviado para o email do usuario
     *
     * @param int $id
     * @return mixed
     * */
    public function atualizar_status_enviado($id)
    {
        $atualizar = array(
            'enviado' => '1',
            'enviado_data' => date('Y-m-d H:i:s'),
        );

        return $this->db->where('id', $id)->update('showtec.veiculos_status', $atualizar);
    }

    /**
     * Testa conexão banco de dados
     *
     * @return conexão
     * */
    public function test_db()
    {
        //return $this->dev;
        return $this->db;
    }

    /* Função para buscar informações do veiculo no GESTOR
     * Cliente SHOWNET + VEICULO GESTOR
     *
     * Luciano
     *
     */

    public function get_veiculo_gestor($code)
    {
        $this->db->where('code', $code);
        $veiculos = $this->db->get('systems.cadastro_veiculo')->result();

        if ($veiculos && count($veiculos)) {
            foreach ($veiculos as $indice => $veiculo) {
                $this->db->where('CNPJ_', $veiculo->CNPJ_);
                $this->db->limit(1);

                $result = $this->db->get('showtecsystem.usuario_gestor')->row();

                if ($result) {
                    $veiculo->usuario = $result->usuario;
                } else {
                    $veiculo->usuario = '';
                }
            }
        }

        return $veiculos;
    }

    private function verifica_duplicidade($placa)
    {
        $query = $this->db->get_where('systems.cadastro_veiculo', array('placa' => $placa))->row();

        if ($query)
            return $query->id;
        return false;
    }

    public function cadastrar_veiculo($dados)
    {
        $usuario = $this->auth->get_login('admin', 'email');
        $duplic_placa = $this->verifica_duplicidade($dados['placa']);

        if ($duplic_placa) $this->db->update('systems.cadastro_veiculo', $dados, array('id' => $duplic_placa));
        else $this->db->insert('systems.cadastro_veiculo', $dados);

        if ($this->db->affected_rows() > 0) {
            $veiculo = $this->get_veiculo(array('code' => $this->db->insert_id()));
            if ($veiculo) {
                $linha = $veiculo[0];
                $registro = json_encode($linha);
                $acao = array(
                    'acao' => '0',
                    'data' => date('Y-m-d H:i:s'),
                    'antes' => $registro,
                    'depois' => '',
                    'usuario' => $usuario,
                    'veiculo' => $linha->code,
                    'motivo' => 'Cadastro de veículo',
                    'observacao' => '',
                    'placa_antes' => $linha->placa,
                    'serial_antes' => $linha->serial
                );
                $this->criar_log($acao);

                return true;
            }
        }
        return false;
    }

    public function atualizar($where, $dados, $usuario = '')
    {
        try {
            $dados['vencimento'] = $dados['vencimento'];
            $dados['data_instalacao'] = $dados['data_instalacao'];
            $motivo = $dados['motivo'];
            $observacao = $dados['observacao'];
            // $this->db->where('ID', $dados['serial'])->update('systems.resposta', array('ODOMETER' => $dados['hodometro']));

            unset($dados['motivo']);
            unset($dados['observacao']);
            unset($dados['hodometro']);
            unset($dados['horimetro']);

            $linha_antes = $this->get_veiculo($where, 1);

            $antes = json_encode($linha_antes[0]);
            $this->atualizar_data_instalacao($dados);

            $this->db->update('systems.cadastro_veiculo', $dados, $where);

            $linha_depois = $this->get_veiculo($where, 1);
            $depois = json_encode($linha_depois[0]);
            $acao = array(
                'acao' => '1',
                'data' => date('Y-m-d H:i:s'),
                'antes' => $antes,
                'depois' => $depois,
                'usuario' => $usuario,
                'veiculo' => $where['code'],
                'motivo' => $motivo,
                'observacao' => $observacao,
                'placa_antes' => substr($linha_antes[0]->placa, 0, 15) ? substr($linha_antes[0]->placa, 0, 15) : '',
                'serial_antes' => substr($linha_antes[0]->serial, 0, 15) ? substr($linha_antes[0]->serial, 0, 15) : '',
                'placa_depois' => substr($linha_depois[0]->placa, 0, 15) ? substr($linha_depois[0]->placa, 0, 15) : '',
                'serial_depois' => substr($linha_depois[0]->serial, 0, 15) ? substr($linha_depois[0]->serial, 0, 15) : ''
            );
            $this->criar_log($acao);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function atualizar_veiculo($placa, $serial, $desvincular = false, $status = '1', $infVeiculo = array(), $id_cliente = null)
    {
        $dados = array();
        if ($id_cliente && is_numeric($id_cliente)) {
            $up = '0';

            if ($status == '1') {
                $up = '1';
            }

            // LISTA ID de GRUPOS DO CLIENTE
            $ids = $this->db->select('id')->get_where('showtecsystem.cadastro_grupo', array('id_cliente' => $id_cliente))->result();
            if ($ids) {
                foreach ($ids as $id) {
                    $grupos[] = $id->id;
                }
            } else {
                $grupos = array();
            }

            // ATUALIZA STATUS DO CADASTRO DO VEICULO
            $this->db->where('placa', $placa);
            $this->db->update('systems.cadastro_veiculo', array('status' => $up));

            // VERIFICA E VINCULA PLACA AO MASTER
            $this->vincularVeic_GroupMaster($placa, $id_cliente);

            // ATUALIZA STATUS DO VINCULO DA PLACA COM GROUPS
            if ($grupos && !empty($grupos)) {
                $this->db->where('placa', $placa);
                $this->db->where_in('groupon', $grupos);
                $this->db->update('showtecsystem.veic_x_group', array('status' => $up));
            }
        }

        $usuario = $this->auth->get_login('admin', 'email');

        if ($infVeiculo && count($infVeiculo) > 0)
            $dados = $infVeiculo;

        $dados['status'] = $up;

        if ($desvincular) {
            $dados['serial'] = '';
            $veiculos_antes = $this->get_veiculo(array('placa' => $placa, 'serial' => $serial));
        } else {
            $dados['serial'] = $serial;
            $veiculos_antes = $this->get_veiculo(array('placa' => $placa));
        }

        if ($veiculos_antes) {
            foreach ($veiculos_antes as $veiculo_antes) {
                $antes = json_encode($veiculo_antes);
                $acao = array(
                    'acao' => '1',
                    'data' => date('Y-m-d H:i:s'),
                    'antes' => $antes,
                    'depois' => '',
                    'usuario' => $usuario,
                    'veiculo' => $veiculo_antes->code,
                    'motivo' => 'Atualização veículo',
                    'observacao' => '',
                    'placa_antes' => $veiculo_antes->placa,
                    'serial_antes' => $veiculo_antes->serial
                );
                $id = $this->criar_log($acao);
                $this->atualizar_log($dados, $placa, $id, $veiculo_antes->code);
            }
            return true;
        }
        return false;
    }

    public function remover_usuario_veiculo($usu, $placa)
    {

        $usuario = $this->auth->get_login('admin', 'email');

        $dados = array(
            'CNPJ_' => ''
        );

        $veiculos_antes = $this->get_veiculo(array('CNPJ_' => $usu, 'placa' => $placa));

        if ($veiculos_antes) {

            foreach ($veiculos_antes as $veiculo_antes) {

                $antes = json_encode($veiculo_antes);

                $acao = array(
                    'acao' => '1',
                    'data' => date('Y-m-d H:i:s'),
                    'antes' => $antes,
                    'depois' => '',
                    'usuario' => $usuario,
                    'veiculo' => $veiculo_antes->code,
                    'motivo' => 'Atualização veículo',
                    'observacao' => '',
                    'placa_antes' => $veiculo_antes->placa,
                    'serial_antes' => $veiculo_antes->serial
                );

                $id = $this->criar_log($acao);
                $this->atualizar_log($dados, $placa, $id, $veiculo_antes->code);
            }

            return true;
        }

        return false;
    }

    public function criar_log($acao)
    {

        $this->db->insert('showtec.usuarios_acoes', $acao);

        return $this->db->insert_id();
    }

    public function atualizar_log($dados, $placa, $id, $code)
    {

        $log_atualizado = false;
        //ATUALIZA O VEICULO (STATUS E SERIAL)
        $this->db->where('code', $code);
        $this->db->update('systems.cadastro_veiculo', $dados);

        $veiculo_depois = $this->get_veiculo(array('code' => $code), 1);
        if ($veiculo_depois) {
            $depois = json_encode($veiculo_depois[0]);
            $acao = array(
                'depois' => $depois,
                'placa_depois' => $veiculo_depois[0]->placa,
                'serial_depois' => $veiculo_depois[0]->serial
            );
            $this->db->where('id', $id);
            $log_atualizado = $this->db->update('showtec.usuarios_acoes', $acao);
        }
        return $log_atualizado;
    }

    //ATUALIZA DADOS DO VEICULO
    public function update_veiculo($placa, $serial, $dados)
    {
        $this->db->where('placa', $placa);
        $this->db->where('serial', $serial);
        $this->db->update('systems.cadastro_veiculo', $dados);
        return $this->db->affected_rows();
    }

    //ATUALIZA DADOS DE UMA PLACA
    public function update_placa($placa, $id_cliente, $dados)
    {
        $this->db->where('placa', $placa);
        $this->db->where('id_cliente', $id_cliente);
        $this->db->update('showtecsystem.contratos_veiculos', $dados);
        return $this->db->affected_rows();
    }

    // Atualiza o status (para instalado) no logistica //
    public function update_inst($placa)
    {
        $log = array('dataInstalacao' => date('Y-m-d H:i'), 'statusOS' => 5);
        $this->db->where('placaOrdem', $placa);
        $this->db->where('statusOS !=', 5);
        $this->db->update('showtecsystem.cad_logistica', $log);
    }

    public function get_resposta($serial)
    {

        $dados = false;

        $this->db->select('ID, DATA, Y, X, VEL, IGNITION, GPS, GPRS');
        $this->db->where('ID', $serial);

        $dados = $this->db->get('systems.last_track')->row(0);

        return $dados;
    }

    public function get_logs_search($dados)
    {
        if ($dados['coluna'] == 'veiculo' || $dados['coluna'] == 'placa' || $dados['coluna'] == 'serial' || $dados['coluna'] == 'cnpj') {
            $this->db->or_like('antes', $dados['palavra']);
            $this->db->or_like('depois', $dados['palavra']);
        } else {
            $this->db->or_like($dados['coluna'], $dados['palavra']);
        }

        $query = $this->db->get('showtec.usuarios_acoes');

        if ($query->num_rows()) {
            return $query->result();
        }

        return false;
    }

    public function total_log()
    {
        return $this->db->count_all('showtec.usuarios_acoes');
    }

    public function get_logs($paginacao, $limite, $ord = 'desc', $select = '*', $where = array())
    {
        $query = $this->db->select($select)
            ->order_by('id', $ord)
            ->limit($limite, $paginacao)
            ->where($where)
            ->get('showtec.usuarios_acoes');

        if ($query->num_rows()) {
            return $query->result();
        }

        return false;
    }

    public function get_cad_logs($paginacao, $limite, $ord = 'desc', $select = '*', $where = array(), $dados = null)
    {
        try {

            if ($dados) {
                if ($dados['coluna'] == 'veiculo' || $dados['coluna'] == 'placa' || $dados['coluna'] == 'serial') {
                    $this->db->or_like('antes', $dados['palavra']);
                    $this->db->or_like('depois', $dados['palavra']);
                } else {
                    $this->db->or_like($dados['coluna'], $dados['palavra']);
                }

                $this->db->select('COUNT(*) as total');
                $this->db->where($where);

                $count_query = $this->db->get('showtec.usuarios_acoes');

                $total_rows = $count_query->row()->total;

                $query = $this->db->select($select)
                    ->order_by('id', $ord)
                    ->limit($limite, $paginacao)
                    ->where($where)
                    ->or_like('antes', $dados['palavra'])
                    ->or_like('depois', $dados['palavra'])
                    ->get('showtec.usuarios_acoes');
            } else {
                $this->db->select('COUNT(*) as total');
                $this->db->where($where);

                $count_query = $this->db->get('showtec.usuarios_acoes');

                $total_rows = $count_query->row()->total;

                $query = $this->db->select($select)
                    ->order_by('id', $ord)
                    ->limit($limite, $paginacao)
                    ->where($where)
                    ->get('showtec.usuarios_acoes');
            }


            if ($query->num_rows()) {
                return array(
                    "sucess" => true,
                    "itens" => $query->result(),
                    "qtdItens" => $total_rows
                );
            } else {
                return array(
                    "sucess" => false,
                    "mensagem" => "Dados não encontrados para o(s) parâmetro(s) informado(s)!",
                );
            }
        } catch (Exception $e) {
            return false;
        }
    }


    public function get_cad_logs_ajustado($paginacao, $limite, $ord = 'desc', $select = '*', $where = array(), $coluna = false)
    {
        try {
            if ($coluna) {
                $query = $this->db->select($select)
                    ->like('serial_antes', $coluna, 'both')
                    ->or_like('serial_depois', $coluna, 'both')
                    ->order_by('id', $ord)
                    ->limit($limite, $paginacao)
                    ->get('showtec.usuarios_acoes');
            } else {
                $query = $this->db->select($select)
                    ->order_by('id', $ord)
                    ->limit($limite, $paginacao)
                    ->where($where)
                    ->get('showtec.usuarios_acoes');
            }

            if ($query->num_rows()) {
                return array(
                    "sucess" => true,
                    "itens" => $query->result(),
                );
            } else {
                return array(
                    "sucess" => false,
                    "mensagem" => "Dados não encontrados para o(s) parâmetro(s) informado(s)!",
                );
            }
        } catch (Exception $e) {
            return false;
        }
    }


    public function find_full_with_track(
        $where,
        $limit = 9999999,
        $offset = 0,
        $select = '*',
        $column_order = 'cv.ultima_atualizacao',
        $order = 'DESC'
    ) {
        $veiculos = $this->db->select($select)
            ->where($where, null, false)
            ->join('usuario_gestor as ug', 'ug.CNPJ_ = cv.CNPJ_')
            ->join('cad_clientes as cc', 'cc.id = ug.id_cliente')
            ->order_by($column_order, $order)
            ->get('systems.cadastro_veiculo as cv', $limit, $offset)
            ->result();

        return $veiculos;
    }

    public function have_found_full($where = array())
    {
        $total = $this->db->select('cv.code')
            ->from('systems.cadastro_veiculo as cv')
            ->where($where, null, false)
            ->get();

        return $total->num_rows();
    }

    public function get_data_instalacao($placa)
    {
        $this->db->where('placa', $placa);
        $this->db->where('data_instalacao !=', '');
        $this->db->where('data_instalacao !=', '0000-00-00');
        $this->db->limit(1);

        $query = $this->db->get('systems.cadastro_veiculo');

        if ($query->num_rows()) {
            $data_instalacao = $query->row()->data_instalacao;
        } else {
            $data_instalacao = '';
        }

        return $data_instalacao;
    }

    public function atualizar_data_instalacao($dados)
    {
        $editar['data_instalacao'] = $dados['data_instalacao'];

        if ($dados['serial'] == '' || $dados['placa'] == '') {
            return true;
        }

        $this->db->where('serial', $dados['serial']);
        $this->db->where('placa', $dados['placa']);

        if ($this->db->update('systems.cadastro_veiculo', $editar)) {
            return true;
        }

        return false;
    }

    public function relatorioPanico($id_cliente = false, $inicio, $final, $select = '*')
    {
        if ($id_cliente && is_numeric($id_cliente)) {
            @$query = $this->rastreamento->select($select)
                ->join('rastreamento.eventos_tratados as tt', 'tt.id_evento = t.id_evento', 'left')
                ->where(array(
                    't.time_gps >=' => $inicio . ' 00:00:01',
                    't.time_gps <=' => $final . ' 23:59:59',
                    't.id_cliente' => $id_cliente,
                    't.cod_evento' => 9,
                    't.evento' => 2
                ))->get('rastreamento.evento_tracker as t');
            return $query->num_rows() > 0 ? $query->result() : false;
        }
    }

    public function getNameUserShownet($id)
    {
        $retorno = $this->db->get_where('showtecsystem.usuario', array('id' => $id))->row();

        if ($retorno) {
            return $retorno->login;
        } else {
            return '';
        }
    }

    public function load_panico($where_panico = array())
    {
        $where_in = $list_clie = array();
        $retorno['data'] = array();
        if (isset($_SESSION['list_clientes'])) {
            $where = $_SESSION['list_clientes'];
        } else {
            $clientes = $this->db->select('nome, id')->get_where('showtecsystem.cad_clientes', array('informacoes' => 'OMNILINK'))->result();
            foreach ($clientes as $c) {
                $list_clie[$c->id] = $c->nome;
                $where_in[] = $c->id;
            }

            $_SESSION['list_clientes'] = $list_clie;
        }

        //$this->rastreamento->where_in('id_cliente', $where_in);
        $panicos = $this->rastreamento->get_where('rastreamento.evento_tracker', array('cod_evento' => 9, 'evento' => 1))->result();

        if ($panicos) {
            foreach ($panicos as $p) {
                $retorno['data'][] = array(
                    $p->placa,
                    date('d/m/Y H:i:s', strtotime($p->time_gps)),
                    $p->serial,
                    $p->lat,
                    $p->lng,
                    $p->velocidade . ' Km/h',
                    $p->endereco ? $p->endereco : 'Não localizado',
                    isset($list_clie[$p->id_cliente]) ? $list_clie[$p->id_cliente] : 'Cliente não vinculado',
                    '<button class="btn click_remove btn-success btn-mini" data-serial="' . $p->serial . '" data-id="' . $p->id_evento . '">Reomver Alerta</button>'
                );
            }
        }

        return $retorno;
    }

    public function finishPanico($id, $serial, $observacao = null)
    {
        @$this->rastreamento->insert('rastreamento.comandos_enviados', array('cmd_eqp' => $serial, 'cmd_comando' => 'PANICO0', 'cmd_cadastro' => date('Y-m-d H:i:s')));
        @$this->rastreamento->insert('rastreamento.eventos_tratados', array('id_evento' => $id, 'data_tratamento' => date('Y-m-d H:i:s'), 'id_user' => $this->auth->get_login_dados('user'), 'observacao' => $observacao, 'tratado_no' => 'shownet'));
        return @$this->rastreamento->update('rastreamento.evento_tracker', array('evento' => 2, 'dhcad_evento' => date('Y-m-d H:i:s'), 'id_cad_regra' => $this->auth->get_login_dados('user')), array('id_evento' => $id));
    }

    public function listar_com_contrato($id_cliente)
    {

        $query = $this->db->query("SELECT veic.placa, veic.CNPJ_, ctr.valor_mensal, ctr.id
                                    FROM systems.cadastro_veiculo AS veic
                                    JOIN showtecsystem.contratos_veiculos AS ctrv ON ctrv.placa = veic.placa
                                    INNER JOIN showtecsystem.contratos AS ctr ON ctr.id = ctrv.id_contrato
                                    WHERE veic.placa IS NOT NULL AND veic.placa != '' AND veic.CNPJ_ != ''
                                    AND ctr.id = {$id_cliente}
                                    AND ctrv.`status` = 'ativo'");

        return $query->result();
    }

    public function get_numberPlaca($numPlaca)
    {
        return $this->db->select('placa')->where('code ', $numPlaca)->get('systems.cadastro_veiculo')->result()[0]->placa;
    }

    // +++++++++++++++++++++++ jerônimo gabriel init ++++++++++++++++++++++++++++
    // métodos desenvolvidos
    function getIdBoard($board)
    {
        return $this->db->select('code')->where('placa', $board)->get('systems.cadastro_veiculo')->result()[0]->code;
    }

    function isExists($board, $id_contrato, $id_cliente)
    {
        $sql = "SELECT
                    id
                FROM
                    showtecsystem.contratos_veiculos
                WHERE
                    id_contrato = {$id_contrato}
                    AND placa = '{$board}'
                    AND id_cliente = {$id_cliente}
                    AND status = 'ativo';";
        return $this->db->query($sql)->num_rows();
    }

    public function getVeiculo_byPlaca($placa)
    {
        return $this->db->get_where('systems.cadastro_veiculo', array('placa' => $placa))->row();
    }

    /*
    * RETORNA O VEICULO DO CLIENTE
    */
    public function getVeiculo_byPlacaCliente($placa, $id_cliente = false)
    {
        $this->db->where('placa', $placa);
        if ($id_cliente) {
            $this->db->where('id_usuario', $id_cliente);
        }
        $query = $this->db->get('systems.cadastro_veiculo');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    /*
    * MUDA STATUS DO VEICULO PARA 0 "DESATIVADO"
    */
    public function atualizaStatus_Veiculo($placa, $status, $id_cliente, $contrato)
    {
        $up = 0;
        if ($status == 'ativo') $up = 1;

        // LISTA ID de GRUPOS DO CLIENTE
        $ids = $this->db->select('id')->get_where('showtecsystem.cadastro_grupo', array('id_cliente' => $id_cliente))->result();
        if ($ids) {
            foreach ($ids as $id) {
                $grupos[] = $id->id;
            }
        } else {
            $grupos = array();
        }

        // ATUALIZA STATUS DO CADASTRO DO VEICULO
        $this->db->where('placa', $placa);
        $this->db->update('systems.cadastro_veiculo', array('status' => $up, 'id_usuario' => $id_cliente, 'contrato_veiculo' => $contrato));

        // VERIFICA E VINCULA PLACA AO MASTER
        $this->vincularVeic_GroupMaster($placa, $id_cliente);

        // ATUALIZA STATUS DO VINCULO DA PLACA COM GROUPS
        if ($grupos && !empty($grupos)) {
            $this->db->where('placa', $placa);
            $this->db->where_in('groupon', $grupos);
            return $this->db->update('showtecsystem.veic_x_group', array('status' => $up));
        }
    }

    public function vincularVeic_GroupMaster($placa, $id_cliente)
    {
        $grupo = $this->db->select('id')
            ->get_where('showtecsystem.cadastro_grupo', array('id_cliente' => $id_cliente, 'status' => 1, 'nome' => 'MASTER'))
            ->row();

        if ($grupo) {
            // VERIFICA SE O VEICULO JÁ SE ENCONTRA VINCULADO AO GRUPO MASTER DO CLIENTE
            $verifica = $this->db->get_where('showtecsystem.veic_x_group', array('groupon' => $grupo->id, 'placa' => $placa))->row();

            if (!$verifica) {
                $dados = array(
                    'placa' => $placa,
                    'groupon' => $grupo->id,
                    'status' => '1'
                );

                $insert = $this->db->insert('showtecsystem.veic_x_group', $dados);

                return $insert;
            }
        }

        return false;
    }

    /*
    * RETORNA REGISTROS PARA UMA DATA ESPECÍFICA DA TABELA DISPONIBILIDADE_VEICULO
    */
    //$data_input é no formato 'Y-m-d'
    public function getVeiculoDisponiveis($select = '*', $data_input = false, $init = 0, $limit = 10)
    {
        //verifica se ja foi gerado para a data passada
        $dv = $this->db->select($select)
            ->group_by('date(datahora)')
            ->where('date(datahora) =', $data_input)
            ->get('systems.disponibilidade_veiculo dv', $limit, $init);

        if ($dv->num_rows() > 0) {
            return $dv->result();
        }
        return false;
    }

    /*
    * RETORNA OS GRUPOS MASTER ATIVOS
    */
    public function getGrupos($select = '*', $where = false)
    {
        $grupos = $this->db->select($select)
            ->where($where)
            ->get('showtecsystem.cadastro_grupo');

        if ($grupos->num_rows() > 0) {
            return $grupos->result();
        }
        return false;
    }

    /*
    * RETORNA OS RASTREAMENTOS REFERENTE A DATA
    */
    //$data tem formato 'Y-m-d'
    public function getRastreamento($select = '*', $data = false)
    {
        if (!$data)
            $data = date('Y-m-d');

        $query = $this->rastreamento->select($select)
            ->where('date(DATA)', $data)
            ->order_by('DATA', 'asc')
            ->get('rastreamento.last_track');

        if ($query->num_rows() > 0)
            return $query->result();

        return false;
    }

    /*
    *  SALVA DADOS DE VEICULOS RASTREADO, PARA USO POSTERIOR
    */
    public function setVeiculoDisponiveis($insert)
    {
        //insere dados de rastreio no banco
        return $this->db->insert_batch('systems.disponibilidade_veiculo', $insert);
    }

    /*
    * RETORNA OS VEICULOS COM SEUS GRUPOS
    */
    public function getVeiculoGrupos($grupos)
    {
        //o sql retorna as placas do grupo, [id_grupo]->[placa1;placa2;placa3...]
        $sql_vg = "SELECT groupon, GROUP_CONCAT( placa SEPARATOR ';' ) as placas
                FROM showtecsystem.veic_x_group
                WHERE groupon in ($grupos) and status = 1
                GROUP BY groupon";
        $g_v = $this->db->query($sql_vg);

        if ($g_v->num_rows() > 0) {
            return $g_v->result();
        }
        return false;
    }

    /*
    * RETORNA OS DADOS DE UM GRUPO DE PLACAS
    */
    public function listInfoPlacas($placas)
    {
        //A PESQUISA RETORNA OS DADOS DAS PLACAS NO FORMATO:,[placa]->[dado1,dado2,dado3...]
        $query = $this->db->select('placa, id_contrato, id_cliente')
            // ->join('showtecsystem.usuario_gestor as gestor','gestor.id_cliente = veic.id_cliente')
            ->where_in('placa', $placas)
            ->where('status', 'ativo')
            ->order_by('veic.placa', 'ASC')
            ->get('showtecsystem.contratos_veiculos as veic');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
    * RETORNA OS GRUPOS COM SEUS USUARIOS
    */
    //o sql retorna os usuarios do grupo, [id_grupo]->[user1;user2;user3...]
    public function getGruposUsers($grupos)
    {
        //pega os grupos dos usuários, filtrando pelos grupos ativos
        $sql_ug = "SELECT id_group, GROUP_CONCAT( id_user SEPARATOR ';' ) as users
                FROM showtecsystem.user_x_group
                WHERE id_group in ($grupos) and status = 1
                GROUP BY id_group";
        $g_u = $this->db->query($sql_ug);

        if ($g_u->num_rows() > 0) {
            return $g_u->result();
        }
        return false;
    }

    public function veiculos_disponiveis($di, $df, $opcoes = false, $cliente = false, $filtro = false)
    {
        //pega os grupos dos veículos
        $this->db->select('c.valor_mensal,cc.nome,dv.*');
        $this->db->where('date(dv.datahora) >=', $di);
        $this->db->where('date(dv.datahora) <=', $df);

        if ($opcoes == 'veic_desatualizado') {
            $this->db->where('date(datahora) > date(ultima_comunicacao)');
        }
        if ($opcoes == 'veic_sem_serial') {
            $this->db->where('serial', '');
        }
        if ($opcoes == 'veic_sem_contrato') {
            $this->db->where('id_contrato', 0);
        }
        if ($opcoes == 'valor_diario') {
            $this->db->where('valor_diario', 0);
        }
        if ($filtro) {
            $this->db->where("(dv.placa = '" . $filtro . "' || dv.serial = '" . $filtro . "' || (dv.id_contrato = '" . $filtro . "' and dv.id_contrato != 0))");
        }
        if ($cliente) {
            $this->db->where("cc.id", $cliente);
        }
        $this->db->order_by('datahora', 'desc');

        $this->db->join('showtecsystem.cad_clientes cc', 'cc.id=dv.id_cliente');
        $this->db->join('showtecsystem.contratos c', 'c.id=dv.id_contrato and dv.id_contrato!=0', 'left');

        $disponiveis = $this->db->get('systems.disponibilidade_veiculo dv');
        $res = $disponiveis->result();

        return $res;
    }

    /*
    * LISTA AS DATAS DA OCORRENCIA DAQUELA PLACA
    */
    public function datasVeiculosDisponiveis($di, $df, $placa)
    {
        $query = $this->db->select('datahora')
            ->where('date(datahora) >=', $di)
            ->where('date(datahora) <=', $df)
            ->where('placa', $placa)
            ->order_by('datahora', 'asc')
            ->get('systems.disponibilidade_veiculo');
        if ($query->num_rows() > 0)
            return $query->result();

        return false;
    }

    public function list_disponibilidade_veiculo($di, $df, $select = '*', $filtro_cliente = false, $filtro_contrato = false, $filtro_contratoGrupo = false)
    {
        //pega os grupos dos veículos
        $this->db->select($select);
        $this->db->where('date(datahora) >=', $di);
        $this->db->where('date(datahora) <=', $df);

        if ($filtro_cliente) {
            $this->db->where("id_cliente", $filtro_cliente);
        }
        if ($filtro_contrato) {
            $this->db->where("id_contrato", $filtro_contrato);
        }
        if ($filtro_contratoGrupo) {
            $this->db->where_in("id_contrato", $filtro_contratoGrupo);
        }
        $query = $this->db->get('systems.disponibilidade_veiculo');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }


    //Gabriel Bandeira
    // public function disponiveis($di,$df,$filtro,$offset,$filtro_cliente){
    //     $retorno['num_rows'] = 0;
    //     $retorno['valor_total'] = 0;
    //
    //     $this->db->select('count(*) as contador,sum(dv.valor_diario) as valor_total');
    //     $this->db->where('date(dv.datahora) >=',$di);
    //     $this->db->where('date(dv.datahora) <=',$df);
    //     if($this->session->userdata('filtro_desatualizado_relatorio_veiculos_disponiveis')){
    //         $this->db->where('date(datahora) > date(ultima_comunicacao)');
    //     }
    //     if($this->session->userdata('filtro_serial_relatorio_veiculos_disponiveis')){
    //         $this->db->where('serial','');
    //     }
    //     if($this->session->userdata('filtro_contrato_relatorio_veiculos_disponiveis')){
    //         $this->db->where('id_contrato',0);
    //     }
    //     if($this->session->userdata('filtro_valor_relatorio_veiculos_disponiveis')){
    //         $this->db->where('valor_diario',0);
    //     }
    //     if($filtro){
    //         $this->db->where("(dv.placa = '".$filtro."' || dv.serial = '".$filtro."' || (dv.id_contrato = '".$filtro."' and dv.id_contrato != 0))");
    //     }
    //     if($filtro_cliente){
    //         $this->db->where("cc.id",$filtro_cliente);
    //     }
    //     $this->db->order_by('datahora','desc');
    //     $this->db->join('showtecsystem.cad_clientes cc','cc.id=dv.id_cliente');
    //     //$this->db->group_by('dv.placa');
    //     //$this->db->join('showtecsystem.contratos c','c.id=dv.id_contrato and dv.id_contrato!=0');
    //     $disponiveis = $this->db->get('systems.disponibilidade_veiculo dv');
    //     $retorno = false;
    //     if($disponiveis->num_rows()>0){
    //         $disponiveis=$disponiveis->row();
    //         $retorno['num_rows'] = $disponiveis->contador;
    //         $retorno['valor_total'] = $disponiveis->valor_total;
    //     }
    //
    //
    //     //pega os grupos dos veículos
    //     $this->db->select('c.valor_mensal,cc.nome,dv.*');
    //     $this->db->where('date(dv.datahora) >=',$di);
    //     $this->db->where('date(dv.datahora) <=',$df);
    //     if($this->session->userdata('filtro_desatualizado_relatorio_veiculos_disponiveis')){
    //         $this->db->where('date(datahora) > date(ultima_comunicacao)');
    //     }
    //     if($this->session->userdata('filtro_serial_relatorio_veiculos_disponiveis')){
    //         $this->db->where('serial','');
    //     }
    //     if($this->session->userdata('filtro_contrato_relatorio_veiculos_disponiveis')){
    //         $this->db->where('id_contrato',0);
    //     }
    //     if($this->session->userdata('filtro_valor_relatorio_veiculos_disponiveis')){
    //         $this->db->where('valor_diario',0);
    //     }
    //     if($filtro){
    //         $this->db->where("(dv.placa = '".$filtro."' || dv.serial = '".$filtro."' || (dv.id_contrato = '".$filtro."' and dv.id_contrato != 0))");
    //     }
    //     if($filtro_cliente){
    //         $this->db->where("cc.id",$filtro_cliente);
    //     }
    //     $this->db->order_by('datahora','desc');
    //     //$this->db->group_by('dv.placa');
    //     $this->db->join('showtecsystem.cad_clientes cc','cc.id=dv.id_cliente');
    //     $this->db->join('showtecsystem.contratos c','c.id=dv.id_contrato and dv.id_contrato!=0','left');
    //     if($offset==-1){
    //         $disponiveis = $this->db->get('systems.disponibilidade_veiculo dv');
    //     }
    //     else{
    //         $disponiveis = $this->db->get('systems.disponibilidade_veiculo dv',10,$offset);
    //     }
    //     $retorno['result']=array();
    //     $res=$disponiveis->result();
    //     $datas_veic=array();
    //     foreach($res as $key=>$linha){
    //         if(!isset($datas_veic[explode(' ',$linha->datahora)[0]][$linha->placa])){
    //             $datas_veic[explode(' ',$linha->datahora)[0]][$linha->placa]=true;
    //             $retorno['result'][]=$linha;
    //             unset($res[$key]);
    //         }
    //     }
    //     return $retorno;
    // }

    //Gabriel Bandeira
    public function resumo_disponiveis($di, $df, $filtro_cliente, $filtro_contrato = false)
    {
        //pega os grupos dos veículos
        $this->db->select('c.valor_mensal, c.status, cc.nome, dv.*');
        $this->db->where('date(dv.datahora) >=', $di);
        $this->db->where('date(dv.datahora) <=', $df);

        if ($filtro_cliente) {
            $this->db->where("cc.id", $filtro_cliente);
        }
        if ($filtro_contrato) {
            $this->db->where("dv.id_contrato", $filtro_contrato);
        }
        $this->db->join('showtecsystem.cad_clientes cc', 'cc.id=dv.id_cliente');
        $this->db->join('showtecsystem.contratos c', 'c.id=dv.id_contrato and dv.id_contrato!=0', 'left');
        $disponiveis = $this->db->get('systems.disponibilidade_veiculo dv');
        $placas = $disponiveis->result();

        $datas_veic = array();
        $index_placa = array();

        foreach ($placas as $linha) {
            if (isset($datas_veic[explode(' ', $linha->datahora)[0]][$linha->placa])) {
                unset($linha);
            } else {
                $datas_veic[explode(' ', $linha->datahora)[0]][$linha->placa] = true;
                if (!isset($index_placa[$linha->placa])) {
                    $index_placa[$linha->placa] = $linha;
                    $index_placa[$linha->placa]->valor_total = 0;
                    $index_placa[$linha->placa]->qtd_dias = 0;
                }
                $index_placa[$linha->placa]->valor_total += $linha->valor_diario;
                $index_placa[$linha->placa]->qtd_dias++;
                $index_placa[$linha->placa]->valor_total_arredondado = round($index_placa[$linha->placa]->valor_total, 2);
            }
        }
        ksort($datas_veic);

        $retorno['result'] = $index_placa;

        return $retorno;
    }

    //Gabriel Bandeira
    public function usuariosDisponiveis($id)
    {
        $this->db->where('id', $id);
        $disponiveis = $this->db->get('systems.disponibilidade_veiculo dv')->row();
        $this->db->select('nome_usuario,usuario');
        $this->db->where_in('code   ', explode(';', $disponiveis->usuarios));
        return $this->db->get('showtecsystem.usuario_gestor')->result();
    }

    public function dashboardDisponiveis($di, $df, $cliente)
    {
        $this->db->select('datahora,sum(valor_diario) as valor_diario,count(*) as contador');
        $this->db->group_by('date(datahora)');
        $this->db->order_by('datahora');
        $this->db->where('date(datahora)>=', $di);
        $this->db->where('date(datahora)<=', $df);
        if ($cliente) {
            $this->db->join('showtecsystem.cad_clientes cc', 'cc.id=dv.id_cliente');
            $this->db->where('cc.id', $cliente);
        }

        $return['vd'] = $this->db->get('systems.disponibilidade_veiculo dv')->result();
        $this->db->select('c.id,c.quantidade_veiculos,(c.quantidade_veiculos * c.valor_mensal) as valor_mensal');
        $this->db->group_by('c.id');
        $this->db->where('dv.id_contrato !=', '0');
        $this->db->where('date(dv.datahora)>=', $di);
        $this->db->where('date(dv.datahora)<=', $df);
        if ($cliente) {
            $this->db->join('showtecsystem.cad_clientes cc', 'cc.id=dv.id_cliente');
            $this->db->where('cc.id', $cliente);
        }
        $this->db->join('showtecsystem.contratos c', 'c.id=dv.id_contrato');
        $contratos = $this->db->get('systems.disponibilidade_veiculo dv')->result();
        $return['contratos'] = [];
        foreach ($contratos as $contrato) {
            $return['contratos'][$contrato->id] = $contrato;
        }
        $contratos = false;

        $this->db->select('c.id as id_contrato,date(dv.datahora) as data');
        $this->db->order_by('data');
        $this->db->group_by('c.id,data');
        $this->db->where('c.id !=', '0');
        $this->db->where('date(datahora)>=', $di);
        $this->db->where('date(datahora)<=', $df);
        if ($cliente) {
            $this->db->join('showtecsystem.cad_clientes cc', 'cc.id=dv.id_cliente');
            $this->db->where('cc.id', $cliente);
        }
        $this->db->join('showtecsystem.contratos c', 'c.id=dv.id_contrato');
        $contratos_dia = $this->db->get('systems.disponibilidade_veiculo dv')->result();
        $return['contratos_dia'] = [];
        foreach ($contratos_dia as $dia) {
            $data_explode = explode('-', $dia->data);
            $return['contratos_dia'][$data_explode[2] . '/' . $data_explode[1] . '/' . $data_explode[0]][] = $dia->id_contrato;
        }
        $contratos_dia = false;
        return $return;
    }
    public function tabelaDashboardDisponiveis($data)
    {
        $db_rastreamento = '';
        $db_rastreamento = $this->load->database('rastreamento', TRUE);
        $data_unix = data_for_unix($data);
        $data_anterior = date('Y-m-d', strtotime("-1 days", strtotime(date($data_unix))));
        $return['veic_entrou'] = $this->db->query('SELECT count(*) as contador,dv.id_cliente,cc.nome FROM systems.disponibilidade_veiculo dv join showtecsystem.cad_clientes cc on cc.id = dv.id_cliente where date(dv.datahora) ="' . $data_unix . '" and dv.placa not in(SELECT placa FROM systems.disponibilidade_veiculo where date(datahora) ="' . $data_anterior . '") group by dv.id_cliente;')->result();
        $return['veic_saiu'] = $this->db->query('SELECT count(*) as contador,dv.id_cliente,cc.nome FROM systems.disponibilidade_veiculo dv join showtecsystem.cad_clientes cc on cc.id = dv.id_cliente where date(dv.datahora) ="' . $data_anterior . '" and dv.placa not in(SELECT placa FROM systems.disponibilidade_veiculo where date(datahora) ="' . $data_unix . '") group by dv.id_cliente;')->result();
        $return['cliente_ranking'] = $this->db->query('SELECT nome,sum(valor_diario) as valor FROM systems.disponibilidade_veiculo dv join showtecsystem.cad_clientes cc on cc.id = dv.id_cliente where date(datahora) ="' . $data_unix . '" group by id_cliente order by valor desc;')->result();
        $return['veic_contrato_real'] = $this->db->query('select *,sum(diferenca) as diferenca_total from (SELECT d.id_cliente,cliente.nome,d.id_contrato,count(*)-c.quantidade_veiculos as diferenca FROM systems.disponibilidade_veiculo d join showtecsystem.contratos c on d.id_contrato = c.id join showtecsystem.cad_clientes cliente on d.id_cliente = cliente.id where date(datahora)="' . $data_unix . '" group by id_contrato)as t1 group by t1.id_cliente;')->result();
        $seriais = $this->db->query('SELECT serial FROM systems.disponibilidade_veiculo where date(datahora)="' . $data_unix . '" and serial !="";')->result();
        $list_serial = [];
        foreach ($seriais as $serial) {
            $list_serial[] = $serial->serial;
        }
        $rastreamento = $db_rastreamento->select('ID as serial,ID_OBJECT_TRACKER as placa')
            ->where_not_in('ID', $list_serial)
            ->group_by('ID')
            ->get('rastreamento.historico_' . str_replace('-', '_', $data_unix))
            ->result();
        $list_serial = [];
        $serial_historico = [];
        $serial_list = [];
        foreach ($rastreamento as $veic) {
            $serial_historico[] = $veic->serial;
            $serial_list[$veic->serial] = $veic;
        }
        $this->db->where_in('serial', $serial_historico);
        $seriais_equipamentos = $this->db->get('showtecsystem.cad_equipamentos')->result();
        $serial_historico = [];
        foreach ($seriais_equipamentos as $equipamento) {
            $serial_list[$equipamento->serial]->status = $equipamento->status;
            $return['veic_sem_vinculo'][] = $serial_list[$equipamento->serial];
        }
        return $return;
    }
    public function alteracoesVeiculosDisponiveis($data, $cliente, $op)
    {
        $data_unix = data_for_unix($data);
        $data_anterior = date('Y-m-d', strtotime("-1 days", strtotime(date($data_unix))));
        if ($op) {
            $return['veic'] = $this->db->query('SELECT dv.placa,dv.serial,dv.id_cliente,cc.nome,u.usuario FROM systems.disponibilidade_veiculo dv left join showtec.usuarios_acoes u on (u.placa_antes = dv.placa or u.placa_depois = dv.placa) and date(u.data)="' . $data_unix . '" join showtecsystem.cad_clientes cc on cc.id = dv.id_cliente where dv.id_cliente = ' . $cliente . ' and date(dv.datahora) ="' . $data_unix . '" and dv.placa not in(SELECT placa FROM systems.disponibilidade_veiculo where dv.id_cliente = ' . $cliente . ' and date(datahora) ="' . $data_anterior . '") group by dv.placa;')->result();
        } else {
            $return['veic'] = $this->db->query('SELECT dv.placa,dv.serial,dv.id_cliente,cc.nome,u.usuario FROM systems.disponibilidade_veiculo dv left join showtec.usuarios_acoes u on (u.placa_antes = dv.placa or u.placa_depois = dv.placa) and date(u.data)="' . $data_unix . '" join showtecsystem.cad_clientes cc on cc.id = dv.id_cliente where dv.id_cliente = ' . $cliente . ' and date(dv.datahora) ="' . $data_anterior . '" and dv.placa not in(SELECT placa FROM systems.disponibilidade_veiculo where dv.id_cliente = ' . $cliente . ' and date(datahora) ="' . $data_unix . '") group by dv.placa;')->result();
        }
        return $return;
    }

    public function dateRunCronDisponiveis($di, $df)
    {
        $this->db->select('date(datahora) as data');
        $this->db->group_by('date(datahora)');
        $this->db->where('date(datahora)>=', $di);
        $this->db->where('date(datahora)<=', $df);
        return $this->db->get('systems.disponibilidade_veiculo dv')->result();
    }

    public function correcao_lastTrack()
    {
        $db_rastreamento;
        $this->db_rastreamento = $this->load->database('rastreamento', TRUE);

        $seriais = $this->db_rastreamento->select('ID')->get_where('rastreamento.last_track', array('DATA' => '2018-07-13 00:10:57'))->result();

        foreach ($seriais as $s) {
            $serial[] = $s->ID;
        }

        for ($i = date('Y-m-d', strtotime('2018-05-13')); $i >= date('Y-m-d', strtotime('2018-03-13')); $i = date('Y-m-d', strtotime('-1 day', strtotime($i)))) {
            $result = $this->db_rastreamento->where_in('ID', $serial)->group_by('ID')->order_by('DATA', 'DESC')->get_where('rastreamento.historico_' . date('Y_m_d', strtotime($i)))->result();

            if ($result) {
                for ($i = 0; $i < count($result); $i++) {
                    $p = $result[$i]->ID;
                    unset($result[$i]->ID);
                    unset($result[$i]->CODE);
                    unset($result[$i]->UQ_info_lido);

                    if ($this->db_rastreamento->where('ID', $p)->update('rastreamento.last_track', $result[$i])) {
                        $chave = array_search($p, $serial);
                        if ($chave) {
                            unset($serial[$chave]);
                        }
                    }
                }
            }
        }
    }

    /*Augusto relatorio placas ativas*/
    public function get_veiculos_ativos($id_cliente)
    {
        $query = $this->db->query("SELECT status, placa FROM showtecsystem.contratos_veiculos where id_cliente = $id_cliente and (status = 'ativo' or status = '');");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_veiculos_inativos($id_cliente)
    {
        $query = $this->db->query("SELECT status, placa FROM showtecsystem.contratos_veiculos where id_cliente = $id_cliente and status = 'inativo';");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_status_veiculo()
    {
        $query = $this->db->query("SELECT cad_clientes.id, cad_clientes.nome, contratos_veiculos.status FROM showtecsystem.contratos_veiculos LEFT JOIN showtecsystem.cad_clientes ON cad_clientes.id=contratos_veiculos.id_cliente ORDER BY cad_clientes.id ASC;");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_rel_status_veiculo()
    {
        $query = $this->db->query("
            select 
                cc.id, 
                cc.nome, 
                sum(
                    CASE cv.status 
                    WHEN 'ativo'
                    THEN 1 ELSE 0 END 
                ) qtAtivos,
                sum(
                    CASE cv.status 
                    WHEN 'inativo'
                    THEN 1 ELSE 0 END 
                ) qtInativos
            FROM showtecsystem.contratos_veiculos  cv
            LEFT JOIN showtecsystem.cad_clientes cc ON cc.id = cv.id_cliente 
            GROUP BY cc.id, cc.nome
            ORDER BY cc.id ASC;
        ");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function veiculosAtivosCliente($id_cliente)
    {
        $this->db->select('placa');
        $this->db->where(array('id_usuario' => $id_cliente, 'status' => 1, 'serial !=' => ''));
        $query = $this->db->get('systems.cadastro_veiculo');

        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }

    /**
     * TOTAL DE PLACAS-VEICULOS (RELACAO) ATIVOS NO CONTRATO
     */
    public function total_relacao_placas_veiculos_ativos_contrato($where = array())
    {
        $tipos_propostas = array(1, 4, 6);
        return $this->db->from("showtecsystem.contratos as c")
            ->join('showtecsystem.contratos_veiculos as p', 'c.id = p.id_contrato', 'inner')
            ->join('systems.cadastro_veiculo as v', 'v.placa = p.placa', 'inner')
            ->where_not_in('c.tipo_proposta',  $tipos_propostas)
            ->where($where)
            ->where('p.status = "ativo" and v.serial <> "" and v.serial is not null')
            ->count_all_results();
    }

    /*
    * LISTA AS PLACAS DE UM CONTRATO
    */
    public function listar_placas_contrato($id_cliente, $id_contrato, $select = 'placa')
    {
        $query = $this->db->select($select)
            ->where(array('id_cliente' => $id_cliente, 'id_contrato' => $id_contrato))
            ->get('showtecsystem.contratos_veiculos');

        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }

    public function listar_veiculos_cliente($id_cliente, $select = '*')
    {
        $this->db->select($select);
        $this->db->where('id_usuario', $id_cliente);
        $query = $this->db->get('systems.cadastro_veiculo');

        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }

    public function cadastrar_veiculo_lote($id_contrato, $id_cliente, $dados)
    {
        $contrato = $this->contrato->get_contratos($id_contrato);
        $placas = $this->contrato->listar_placas(array('id_contrato' => $id_contrato, 'status' => 'ativo'));
        $table = array();

        if (($contrato[0]->quantidade_veiculos - count($placas)) >= count($dados['contrato_veic'])) {
            $usuario = $this->auth->get_login('admin', 'email');
            foreach ($dados['contrato_veic'] as $key => $veiculo) {

                if (
                    !$this->verifica_duplicidade_placa_contrato(array('placa' => $veiculo['placa'], 'id_contrato' => $id_contrato)) &&
                    !$this->verifica_duplicidade_placa_contrato(array('placa' => $veiculo['placa'], 'status' => 'ativo'))
                ) {
                    //insere na tabela de contratos_veiculos
                    $this->db->insert('showtecsystem.contratos_veiculos', $dados['contrato_veic'][$key]);
                    $id_veic_contrat = $this->db->insert_id();

                    if (!$this->verifica_duplicidade_placa_cadastro($veiculo['placa'])) {
                        $this->db->insert('systems.cadastro_veiculo', $dados['cadastro_veic'][$key]);
                        $veic = $this->get_veiculo(array('code' => $this->db->insert_id()));
                        if ($veic) {
                            $linha = $veic[0];
                            $registro = json_encode($veic[0]);
                            $acao = array(
                                'acao' => '0',
                                'data' => date('Y-m-d H:i:s'),
                                'antes' => $registro,
                                'depois' => '',
                                'usuario' => $usuario,
                                'veiculo' => $linha->code,
                                'motivo' => 'Cadastro de veículo',
                                'observacao' => '',
                                'placa_antes' => $linha->placa,
                                'serial_antes' => $linha->serial
                            );
                            $this->criar_log($acao); //insere log
                        }
                    }
                    $this->vincularVeic_GroupMaster($veiculo['placa'], $id_cliente);  //insere no grupo master

                    $table[] = array(
                        'id' => $id_veic_contrat,
                        'placa' => $veiculo['placa'],
                        'status' => $veiculo['status']
                    );
                }
            }
        }

        if ($table) {
            return $table;
        }
        return false;
    }

    public function verifica_duplicidade_placa_contrato($where)
    {
        $query = $this->db->get_where('showtecsystem.contratos_veiculos', $where);

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function verifica_duplicidade_placa_cadastro($placa)
    {
        $query = $this->db->get_where('systems.cadastro_veiculo ', array('placa' => $placa));

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function list_placas_usuario($id_usuario)
    {
        $query = $this->db->select('veic.placa, gestor.id_cliente, gestor.usuario, gestor.nome_usuario')
            ->join('showtecsystem.usuario_gestor as gestor', 'gestor.id_cliente = veic.id_cliente')
            ->where(array('gestor.code' => $id_usuario, 'veic.status' => 'ativo'))
            ->order_by('veic.placa', 'ASC')
            ->get('showtecsystem.contratos_veiculos as veic');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getCnpjById($idCliente)
    {
        $query = $this->db->get_where('showtecsystem.cad_clientes', array('id' => $idCliente))->row();
        if ($query->cpf) {
            return $query->cpf;
        } else {
            return $query->cnpj;
        }
    }

    public function getSerialByPlaca($placa, $select = '*')
    {
        $this->db->select($select);
        $this->db->where('placa', $placa);
        $query = $this->db->get('systems.cadastro_veiculo');

        if ($query->num_rows()) {
            return $query->row();
        }
        return false;
    }

    public function load_monitorados($data = '2020-01-01 00:00:01')
    {
        return $this->rastreamento->like('ID', 'W', 'after')->where(array(
            'DATASYS >' => $data,
            'ID_OBJECT_TRACKER <>' => "-"
        ))->where('ID_OBJECT_TRACKER !=', '')->get('rastreamento.last_track')->result();

        die(pr($this->rastreamento->last_query()));
    }

    public function getMonitorados()
    {
        return $this->db->where_in('status', array(1, 2))
            ->where('serial is not null', null, false)
            ->get('systems.detentos')->result();
    }

    /*
     * Função que consulta lista informacoes de equipamentos/veiculos levando em consideracao os dias de sua atividade
     */
    public function getGruposVeiculosPorPlacas($select = '*', $placas)
    {
        $query = $this->db->select($select)
            ->join('showtecsystem.veic_x_group AS veic_g', 'veic_g.groupon = g.id', 'LEFT')
            ->where(array('veic_g.status' => 1))
            ->where_in('veic_g.placa', $placas)
            ->get('showtecsystem.grupos AS g');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
     * Função que consulta os grupos em que o veiculo esta vinculado
    */

    public function getGruposVeiculos($placa)
    {
        $query = $this->db->select("veic_g.espelhamento, cad_grupo.nome AS nome_grupo, cad_grupo.id_cliente, cad_clientes.nome AS nome_cliente")
            ->join('showtecsystem.veic_x_group AS veic_g', 'veic_g.groupon = cad_grupo.id', 'LEFT')
            ->join('showtecsystem.cad_clientes', 'cad_clientes.id = cad_grupo.id_cliente', 'LEFT')
            ->where(array('veic_g.status' => 1))
            ->where(array('veic_g.placa' => $placa))
            ->get('showtecsystem.cadastro_grupo AS cad_grupo');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
    * RETORNA OS VEICULOS DE UM CLIENTE
    */
    public function getPlacasCliente($select = '*', $where = array())
    {
        $query = $this->db->select($select)->where($where)->get('showtecsystem.contratos_veiculos');
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    /*
    * GET EVENTOS PANICOS
    */
    public function getEventosPanicoServerSide($select = '*', $order = false, $start = 0, $limit = 999999, $search = false, $filtro = '', $draw = 1, $qtdTotal = false)
    {
        $colunas = array('id_evento', 'placa', 'time_gps', 'serial', 'lat', 'lgn', 'velocidade', 'endereco', 'id_cliente');

        if (!$qtdTotal) $this->rastreamento->select($select);

        if ($search) {
            if ($filtro == 'time_gps') $this->rastreamento->where(array('time_gps >=' => $search . ' 00:00:00', 'time_gps <=' => $search . ' 23:59:59'));
            else $this->rastreamento->where($filtro, $search);
        }

        $this->rastreamento->where(array('cod_evento' => 9, 'evento' => 1));

        if ($order) $this->rastreamento->order_by($colunas[$order['column']], $order['dir']);
        else $this->rastreamento->order_by('id', 'DESC');

        if ($qtdTotal) $query = $this->rastreamento->count_all_results('rastreamento.evento_tracker');
        else $query = $this->rastreamento->get('rastreamento.evento_tracker', $limit, $start);

        return $query;
    }

    /*
    * LISTA EVENTOS PANICOS
    */
    public function listEventosPanicoServerSide($select = '*', $order = false, $start = 0, $limit = 999999, $search = false, $filtro = '', $draw = 1)
    {
        $dados = array();
        $query = @$this->getEventosPanicoServerSide($select, $order, $start, $limit, $search, $filtro, $draw);
        $queryQtdTotal = @$this->getEventosPanicoServerSide('id_evento', $order, $start, $limit, $search, $filtro, $draw, true);

        if ($query->num_rows() > 0) {
            $dados['eventos'] = $query->result(); # Lista de eventos
            $dados['recordsTotal'] = $queryQtdTotal; # Total de registros
            $dados['recordsFiltered'] = $dados['recordsTotal']; # atribui o mesmo valor do recordsTotal ao recordsFiltered para que tivesse todas as paginas na datatable
            $dados['draw'] = $draw++; # Draw do datatable

            return $dados;
        }

        return false;
    }

    /**
     * PEGA DADOS DE UM EVENTO TRACKER
     */
    public function getEventoTracker($condicao = [], $colunas = "*")
    {
        return @$this->rastreamento->select($colunas)->where($condicao)->get('rastreamento.evento_tracker')->row();
    }

    /**
     * ATUALIZA UM EVENTO TRACKER
     */
    public function update_evento_tracker($id, $dados)
    {
        @$this->rastreamento->update('rastreamento.evento_tracker', $dados, array('id_evento' => $id));
        if ($this->rastreamento->affected_rows() > 0) return true;
        return false;
    }
}
