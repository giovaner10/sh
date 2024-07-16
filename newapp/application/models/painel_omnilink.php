<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Painel_omnilink extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('auth');
    }
    /**
     * Salva auditoria do sac
     */
    public function salvar_auditoria($dados)
    {
        try {
            $id_usuario = $this->auth->get_login_dados('user');
            $this->db->insert('integracao_crm_shownet.auditoria_sac', array(
                'id_usuario_shownet' => $id_usuario,
                'url_api' => $dados['url_api'],
                'clause' => $dados['clause'],
                'data_cadastro' => date('Y-m-d H:i:s'),
                'cpf_cnpj' => $dados['cpf_cpnj_cliente']
            ));

            $id_auditoria = $this->db->insert_id();
            if (isset($id_auditoria)) {
                $resposta = $this->salvar_campos_auditoria($id_auditoria, $dados['valores_antigos'], $dados['valores_novos']);
                return $resposta;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Salva os campos que foram alterados
     */
    public function salvar_campos_auditoria($id_auditoria, $valores_antigos, $valores_novos)
    {
        try {
            $campos = array_keys($valores_novos);

            foreach ($campos as $key => $campo) {
                if (isset($valores_antigos) && isset($valores_antigos[$campo])) {
                    if ($valores_antigos[$campo] != $valores_novos[$campo]) {
                        $this->db->insert('integracao_crm_shownet.auditoria_campos_sac', array(
                            'id_auditoria' => $id_auditoria,
                            'campo' => $campo,
                            'valor_antigo' => isset($valores_antigos[$campo]) ? $valores_antigos[$campo] : null,
                            'valor_novo' => $valores_novos[$campo],
                        ));
                    }
                } else {
                    $this->db->insert('integracao_crm_shownet.auditoria_campos_sac', array(
                        'id_auditoria' => $id_auditoria,
                        'campo' => $campo,
                        'valor_antigo' => isset($valores_antigos[$campo]) ? $valores_antigos[$campo] : null,
                        'valor_novo' => $valores_novos[$campo],
                    ));
                }
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Retorna os dados de auditoria do SAC
     */
    public function get_auditoria($where = null, $limit = null, $offset = null, $order = 'desc')
    {
        try {
            $this->db->select('auditoria.*, campos.id as id_campo,campos.campo, campos.valor_antigo, campos.valor_novo, user.nome as nome_usuario, user.login as email_usuario')->from('integracao_crm_shownet.auditoria_sac as auditoria')->join('integracao_crm_shownet.auditoria_campos_sac as campos', 'auditoria.id = campos.id_auditoria', 'left')->join('showtecsystem.usuario as user', 'auditoria.id_usuario_shownet = user.id');

            // Clausura Where
            if (isset($where)) $this->db->where($where);
            // Limite
            if (isset($limit)) $this->db->limit($limit);
            // Offset
            if (isset($offset)) $this->db->offset($offset);

            $this->db->order_by('auditoria.id', $order);

            $resposta = $this->db->get()->result_array();

            if (isset($resposta) && count($resposta) > 0) return $resposta;
            else return array();
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
    public function count_dados_auditoria()
    {
        $this->db->select('count(*) as total')->from('integracao_crm_shownet.auditoria_sac as auditoria')->join('integracao_crm_shownet.auditoria_campos_sac as campos', 'auditoria.id = campos.id_auditoria', 'left');

        $resposta = $this->db->get()->result();
        if (isset($resposta) && $resposta > 0) return $resposta[0]->total;
        else return 0;
    }

    /**
     * Salva no banco o usuário do shownet que fez o cadastro da ocorrência
     */
    public function salvar_cadastro_ocorrencia($incidentid)
    {
        if (isset($incidentid)) {
            $this->db->insert('integracao_crm_shownet.ocorrencia_x_user_sac', array(
                'incidentid' => $incidentid,
                'id_usuario_cadastro' => $this->auth->get_login_dados('user'),
                'clause' => 'insert',
                'data_cadastro' => date('Y-m-d H:i:s')
            ));
            $id = $this->db->insert_id();
            if (isset($id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Busca o usuário que realizou o cadastro da ocorrência
     */
    public function buscar_cadastro_ocorrencia($incidentid)
    {
        $this->db->select('anotacao_usuario.*, usuario_cad.login as login_usuario_cadastro, usuario_cad.nome as nome_usuario_cadastro, usuario_upd.login as login_usuario_update, usuario_upd.nome as nome_usuario_update')->from('integracao_crm_shownet.ocorrencia_x_user_sac as anotacao_usuario')->join('showtecsystem.usuario as usuario_cad', 'anotacao_usuario.id_usuario_cadastro = usuario_cad.id', 'left')->join('showtecsystem.usuario as usuario_upd', 'anotacao_usuario.id_usuario_update = usuario_upd.id', 'left')->where('incidentid', $incidentid);

        $resposta = $this->db->get()->result();
        if (count($resposta) > 0) {
            return $resposta[0];
        } else {
            return null;
        }
    }
    public function buscar_cliente_nome($search)
    {
        $clientes = $this->db->select('nome, cnpj, cpf, razao_social')->from('showtecsystem.cad_clientes')->like('nome', $search)->or_like('razao_social', $search)->get()->result_array();

        return $clientes;
    }

    public function buscar_cliente_usuario($search)
    {
        $search = $this->db->escape_str($search);
        $this->db->select('nome_usuario, usuario, CNPJ_, cpf');
        $this->db->from('showtecsystem.usuario_gestor');
        $this->db->like('nome_usuario', $search, 'both');
        $this->db->or_like('usuario', $search, 'both');
        $clientes = $this->db->get()->result_array();
        return $clientes;
    }

    public function buscar_cliente_id($search)
    {
        $clientes = $this->db->select('nome, cnpj, cpf, razao_social')
            ->from('showtecsystem.cad_clientes')
            ->where('id', $search)
            ->get()
            ->result_array();

        return $clientes;
    }
    /**
     * caso possua registro, atualiza com o id do usuário que fez o update,
     * caso contrário, cadastra um novo registro e seta o usuário que fez a modificação
     */
    public function atualizar_cadastro_ocorrencia($incidentid)
    {
        $incident = $this->db->select('*')->from('integracao_crm_shownet.ocorrencia_x_user_sac')->where('incidentid', $incidentid)->get()->result();

        $dados = array(
            'id_usuario_update' => $this->auth->get_login_dados('user'),
            'data_update' => date('Y-m-d H:i:s')
        );
        // atualiza cadastro de ocorrência
        if (count($incident) > 0) {
            $this->db->where('incidentid', $incidentid)->update('integracao_crm_shownet.ocorrencia_x_user_sac', $dados);

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else { // cadastra nova ocorrência
            $this->db->insert('integracao_crm_shownet.ocorrencia_x_user_sac', array(
                'incidentid' => $incidentid,
                'id_usuario_update' => $this->auth->get_login_dados('user'),
                'clause' => 'update',
                'data_update' => date('Y-m-d H:i:s')
            ));
            $id = $this->db->insert_id();
            if (isset($id)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Salva usuário que cadastrou anotação
     */
    public function salvar_user_anotacao($annotationid)
    {
        if (isset($annotationid)) {
            $this->db->insert('integracao_crm_shownet.anotacao_x_user_sac', array(
                'annotation_id' => $annotationid,
                'user_id' => $this->auth->get_login_dados('user')
            ));
            $id = $this->db->insert_id();
            return $id;
        }
        return false;
    }

    /**
     * Busca auditorio dos tickets
     */
    public function getAuditoriaIncident($incidentid)
    {
        return $this->db->query("
            SELECT oco_user.*,
                (SELECT nome FROM showtecsystem.usuario WHERE id = oco_user.id_usuario_cadastro) as usuario_cadastro,
                (SELECT nome FROM showtecsystem.usuario WHERE id = oco_user.id_usuario_update) as usuario_update
            FROM integracao_crm_shownet.ocorrencia_x_user_sac as oco_user
            WHERE incidentid = '$incidentid';
        ")->row();
    }

    /**
     * Busca o usuário que inseriu anotação
     */
    public function find_user_annotation($annotationid)
    {
        $resposta = $this->db->select('nome')->from('integracao_crm_shownet.anotacao_x_user_sac as anotacao_usuario')->join('showtecsystem.usuario as usuario_cad', 'anotacao_usuario.user_id = usuario_cad.id', 'left')->where('annotation_id', $annotationid)->get()->result();

        if (count($resposta) > 0) {
            return $resposta[0];
        }
        return null;
    }
}
