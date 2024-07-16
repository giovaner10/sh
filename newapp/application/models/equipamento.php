<?php

date_default_timezone_set('America/Recife');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Equipamento extends CI_Model {
    private $db_rastreamento;

    public function __construct() {
        parent::__construct();

        $this->load->model('veiculo');
        $this->load->model('comando');
        $this->load->helper('file');
        $this->load->model('usuario');
        $this->db_rastreamento = $this->load->database('rastreamento', TRUE);
    }

    public function atualiza_fwTrz($post) {
        if (is_array($post) && !empty($post)) {
            $insert = array(
                'cmd_eqp'           => $post['serial'],
                'cmd_comando'       => '1ANKL_V3.42.bin',
                'cmd_cadastro'      => date('Y-m-d H:i:s'),
                'id_usuario'        => $this->auth->get_login_dados('user'),
                'origem_comando'    => '1',
                'descricao_comando' => 'FW : '. $post['atual'] .' >>> 342',
                'cmd_subid'         => 10
            );
            $this->db_rastreamento->insert('rastreamento.comandos_enviados', $insert);

            return array('status' => true, 'new_fw' => '342');
        }

        return array('status' => false);
    }

    public function getComandosAtualiza($serial) {
        $retorno = array();
        $comandos = $this->db_rastreamento->limit(10)->order_by('cmd_id', 'desc')->get_where('rastreamento.comandos_enviados', array(
            'origem_comando'    => '1',
            'cmd_subid'         => 10,
            'cmd_eqp'           => $serial
        ))->result();

        if (is_array($comandos) && !empty($comandos)) {
            $usuarios = $this->db->get('showtecsystem.usuario')->result_array();

            foreach ($comandos as $c) {
                $chave = array_search($c->id_usuario, array_column($usuarios, 'id'));
                $usuario = is_numeric($chave) ? $usuarios[$chave]['login'] : ' - ';

                $retorno[] = array(
                    'comando'       => $c->descricao_comando,
                    'usuario'       => $usuario,
                    'data_envio'    => dh_for_humans($c->cmd_cadastro),
                    'data_processa' => $c->cmd_envio ? dh_for_humans($c->cmd_envio) : ' - ',
                    'data_confirma' => $c->cmd_confirmacao ? dh_for_humans($c->cmd_confirmacao) : ' - '
                );
            }
        }

        return $retorno;
    }

    public function get_response($serial){
        $query = $this->db->where('serial', $serial)
            ->order_by('data','asc')
            ->get('systems.MAXTRACK_ENVIAR');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    function get_equipamentos_tot() {
        $this->db->select('cad.serial, cad.id as id, veic.placa, cad.status');
        $this->db->join('systems.cadastro_veiculo as veic', 'veic.serial = cad.serial', 'LEFT');
        return $this->db->get('showtecsystem.cad_equipamentos as cad')->result();
    }

    public function get_comandos_enviados($serial) {
        $enviados = array();
        if ((substr($serial, 0, 3) == 'CAL')) {
            $query = $this->db_rastreamento->where('serial', $serial)
                ->get('rastreamento.comando_calamp');
            if ($query->num_rows() > 0) {
                return $query->result();
            }
        } else if(substr($serial, 0, 2) == '12' || substr($serial, 0, 2) == '13' || substr($serial, 0, 2) == '83' || substr($serial, 0, 2) == '82' || substr($serial, 0, 2) == '81' || substr($serial, 0, 2) == '64') {
            $enviados = $this->get_response($serial);
            if ($enviados) {
                foreach ($enviados as $key => $enviado) {
                    $seriais[] = $enviado->id_comando;
                }
                $respostas = $this->db_rastreamento->where_in('id_command', $seriais)->get('rastreamento.command_response')->result();
                foreach ($enviados as $key => $enviado) {
                    $i=0;
                    foreach ($respostas as $key => $resposta) {
                        if ($enviado->id_comando == $resposta->id_command) {
                            $enviado->respostas[$i] = $resposta;
                            $i++;
                        }
                    }
                }
            }
        } else {
            $query = $this->db_rastreamento->where('cmd_eqp', $serial)
                ->get('rastreamento.comandos_enviados');
            if ($query->num_rows() > 0) {
                return $query->result();
            }
        }

        return $enviados;
    }

    /*
     * Função que consulta o equipamento pelo prefixo (SCO,ZV, AQ, 205, etc)
     * Tabela de consulta systems.equipamentos
     * @param String $serial
     * @return Array $equipamentos
     */

    public function get_prefixo($serial) {
        $query = $this->db->where('prefixo', substr($serial, 0, 2))
            ->order_by('equipamento')
            ->get('systems.equipamentos as equ');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function obter_prefixo($serial) {
        $query = $this->db->select('id, equipamento, prefixo')
                          ->from('systems.equipamentos as equ')
                          ->order_by('equipamento')
                          ->get();
    
        if ($query->num_rows() == 0) {
            return null; 
        }
    
        $equipamentos = $query->result();
    
        foreach ($equipamentos as $equipamento) {
            if (strpos($serial, $equipamento->prefixo) === 0) {
                return $equipamento;
            }
        }
    
        return null;
    }
    
    
    
    
    
    /*
     * Função que consulta os comando do equipamento
     * Passado o ID do equipamento para buscar comandos conforme seu modelo
     *
     * @param String $id
     * @return Array $equipamentos
     */

     public function get_comando_old($id, $serial = '') {
        $query = $this->db->where('id', $id)
                          ->order_by('equipamento')
                          ->get('systems.equipamentos as equ');
    
        $responde = $query->result();
    
        if (!empty($responde) && isset($responde[0])) {
            $modelo = $responde[0]->equipamento;
    
            switch ($modelo) {
                case 'Continental':
                    $cmd = $this->get_comandos_continental($responde[0]->id);
                    break;
                case 'Zenite':
                    $cmd = 'Zenite';
                    break;
                case 'Quanta':
                    $cmd = 'Quanta';
                    break;
                case 'Svias':
                    $cmd = 'Svias';
                    break;
                case 'CalAmp':
                    $cmd = $this->get_comandos_calamp($responde[0]->id);
                    break;
                case 'Suntech':
                    $cmd = $this->get_comandos_suntech();
                    break;
                case 'QuecLink':
                    $cmd = $this->get_comandos_queclink();
                    break;
                case 'E3':
                    $cmd = $this->get_comandos_e3();
                    break;
                case 'MultiPortal':
                    $cmd = $this->get_comandos_multiPortal();
                    break;
                case 'Teltonika':
                    $cmd = $this->get_comandos_teltonika();
                    break;
                case 'Omnilink':
                    $cmd = $this->get_comandos_ankl($serial);
                    break;
                default:
                    $cmd = $this->get_comandos_maxtrack($serial);
                    break;
            }
        } else {
            // Handle case where no data is found
            $cmd = []; // Return an empty array or handle as appropriate
        }
    
        return $cmd;
    }

    public function get_comando($id, $serial = '') {
        try {
            $query = $this->db->where('id', $id)->get('systems.equipamentos as equ');
            $responde = $query->result();
            
            if (!empty($responde)) {
                $modelo = $responde[0]->equipamento;
                $cmd = $this->get_commands_by_model($modelo, $responde[0]->id, $serial);
            } else {
                $cmd = []; // Return an empty array if no data is found
            }
        } catch (Exception $e) {
            // Handle exception
            $cmd = [];
        }
        
        return $cmd;
    }
    
    private function get_commands_by_model($modelo, $id, $serial) {
        switch ($modelo) {
            case 'Continental':
                return $this->get_comandos_continental($id);
            case 'Zenite':
                return 'Zenite';
            case 'Quanta':
                return 'Quanta';
            case 'Svias':
                return 'Svias';
            case 'CalAmp':
                return $this->get_comandos_calamp($id);
            case 'Suntech':
                return $this->get_comandos_suntech();
            case 'QuecLink':
                return $this->get_comandos_queclink();
            case 'E3':
                return $this->get_comandos_e3();
            case 'MultiPortal':
                return $this->get_comandos_multiPortal();
            case 'Teltonika':
                return $this->get_comandos_teltonika();
            case 'Omnilink':
                return $this->get_comandos_ankl($serial);
            default:
                return $this->get_comandos_maxtrack($serial);
        }
    }
    
    

    /*
     * Função que consulta os comando enviados para o equipamento
     * @param Array $where
     * @return Array $requisicoes
     */

    public function get_lista_comandos($where) {
        $dados = array();

         $tabela = 'rastreamento.comandos_enviados';
        $dados = $this->db_rastreamento->select('cmd_comando as nome, cmd_cadastro as datahora_criacao, cmd_envio as datahora_envio, cmd_confirmacao as datahora_confirmacao, cmd_texe as tentativa, id_usuario, cmd_resp as resposta')
            ->where('cmd_eqp', $where['id'])
            ->order_by('cmd_cadastro', 'desc')
            ->get($tabela)->result();

        //verifica se a consulsta retornou algo
        if(count($dados) > 0) {
            for ($i=0; $i < count($dados); $i++) {

                if($dados[$i]->nome == "AT+GTOUT=gv55,2,,,0,0,0,,,,1,,,,,,,0001$"){
                    $dados[$i]->nome = 'BLOQUEIO';
                } elseif ($dados[$i]->nome == "AT+GTOUT=gv55,0,,,0,0,0,,,,1,,,,,,,0001$") {
                    $dados[$i]->nome = 'DESBLOQUEIO';
                }
                if($dados[$i]->datahora_confirmacao != ""){
                    $dados[$i]->status = 4;
                } elseif ($dados[$i]->datahora_envio != "" and is_null($dados[$i]->datahora_confirmacao)) {
                    $dados[$i]->status = 1;
                } elseif (is_null($dados[$i]->datahora_envio) and is_null($dados[$i]->datahora_confirmacao)){
                    $dados[$i]->status = 0;
                }
                if($dados[$i]->id_usuario == 1 or $dados[$i]->id_usuario == null or $dados[$i]->id_usuario == ""){
                    $dados[$i]->nome_usuario = 'Usuario não identificado';
                }
                if ($dados[$i]->id_usuario != 1) {
                    $usuario = $this->usuario->getUser($dados[$i]->id_usuario,'nome');
                    $dados[$i]->nome_usuario = isset($usuario[0]->nome) ? $usuario[0]->nome : 'Usuario não identificado';
                }
            }
        }else{
            //caso não tenha retorno, procurar na tabela da calamp, que possui uma tabela específica para os comandos
            $dados = $this->db_rastreamento->select('comando.nome,dt_gravacao as datahora_criacao, dt_envio as datahora_envio,dt_recebimento as datahora_confirmacao,cmd_exe as tentativa, resposta')
                ->join('rastreamento.gtw_comandos_lista as comando', 'comando.code = rastreamento.comando_calamp.id_comando', 'inner')
                ->where('serial', $where['id'])
                ->order_by('dt_gravacao', 'desc')
                ->get('rastreamento.comando_calamp')->result();
                
            if(count($dados) > 0) {
                for ($i=0; $i < count($dados); $i++) {
    
                    if($dados[$i]->datahora_confirmacao != ""){
                        $dados[$i]->status = 4;
                    } elseif ($dados[$i]->datahora_envio != "" and is_null($dados[$i]->datahora_confirmacao)) {
                        $dados[$i]->status = 1;
                    } elseif (is_null($dados[$i]->datahora_envio) and is_null($dados[$i]->datahora_confirmacao)){
                        $dados[$i]->status = 0;
                    }
                    if($dados[$i]->id_usuario == 1 or $dados[$i]->id_usuario == null or $dados[$i]->id_usuario == ""){
                        $dados[$i]->nome_usuario = 'Usuario não identificado';
                    }
                    if ($dados[$i]->id_usuario != 1) {
                        $dados[$i]->nome_usuario = $this->usuario->getUser($dados[$i]->id_usuario,'nome')[0]->nome; 
                    }
                }
            }
        }

        return $dados;

    }

    public function get_lista_comandos_new($where) {
        $dados = array();

         $tabela = 'rastreamento.comandos_enviados';
        $dados = $this->db_rastreamento->select('cmd_comando as nome, cmd_cadastro as datahora_criacao, cmd_envio as datahora_envio, cmd_confirmacao as datahora_confirmacao, cmd_texe as tentativa, id_usuario, cmd_resp as resposta')
            ->where('cmd_eqp', $where['id'])
            ->order_by('cmd_cadastro', 'desc')
            ->get($tabela)->result();

        //verifica se a consulsta retornou algo
        if(count($dados) > 0) {
            for ($i=0; $i < count($dados); $i++) {

                if($dados[$i]->nome == "AT+GTOUT=gv55,2,,,0,0,0,,,,1,,,,,,,0001$"){
                    $dados[$i]->nome = 'BLOQUEIO';
                } elseif ($dados[$i]->nome == "AT+GTOUT=gv55,0,,,0,0,0,,,,1,,,,,,,0001$") {
                    $dados[$i]->nome = 'DESBLOQUEIO';
                }
                if($dados[$i]->datahora_confirmacao != ""){
                    $dados[$i]->status = 4;
                } elseif ($dados[$i]->datahora_envio != "" and is_null($dados[$i]->datahora_confirmacao)) {
                    $dados[$i]->status = 1;
                } elseif (is_null($dados[$i]->datahora_envio) and is_null($dados[$i]->datahora_confirmacao)){
                    $dados[$i]->status = 0;
                }
                if($dados[$i]->id_usuario == 1 or $dados[$i]->id_usuario == null or $dados[$i]->id_usuario == ""){
                    $dados[$i]->nome_usuario = 'Usuario não identificado';
                }
                if ($dados[$i]->id_usuario != 1) {
                    $usuario = $this->usuario->getUser($dados[$i]->id_usuario,'nome');
                    $dados[$i]->nome_usuario = isset($usuario[0]->nome) ? $usuario[0]->nome : 'Usuario não identificado';
                }
            }
        }else{
            //caso não tenha retorno, procurar na tabela da calamp, que possui uma tabela específica para os comandos
            $dados = $this->db_rastreamento->select('comando.nome,dt_gravacao as datahora_criacao, dt_envio as datahora_envio,dt_recebimento as datahora_confirmacao,cmd_exe as tentativa, resposta')
                ->join('rastreamento.gtw_comandos_lista as comando', 'comando.code = rastreamento.comando_calamp.id_comando', 'inner')
                ->where('serial', $where['id'])
                ->order_by('dt_gravacao', 'desc')
                ->get('rastreamento.comando_calamp')->result();
                
            if(count($dados) > 0) {
                for ($i=0; $i < count($dados); $i++) {
    
                    if($dados[$i]->datahora_confirmacao != ""){
                        $dados[$i]->status = 4;
                    } elseif ($dados[$i]->datahora_envio != "" and is_null($dados[$i]->datahora_confirmacao)) {
                        $dados[$i]->status = 1;
                    } elseif (is_null($dados[$i]->datahora_envio) and is_null($dados[$i]->datahora_confirmacao)){
                        $dados[$i]->status = 0;
                    }
                    if($dados[$i]->id_usuario == 1 or $dados[$i]->id_usuario == null or $dados[$i]->id_usuario == ""){
                        $dados[$i]->nome_usuario = 'Usuario não identificado';
                    }
                    if ($dados[$i]->id_usuario != 1) {
                        $dados[$i]->nome_usuario = $this->usuario->getUser($dados[$i]->id_usuario,'nome')[0]->nome; 
                    }
                }
            }
        }

        return $dados;

    }

    /* Buscar lista de comandos conforme equipamentos
     *
     * é preciso que retornem as seguintes colunas
     *
     * code: chave primaria de auto incremento
     * nome: nome do comando
     * descricao: descricção do comando
     * valor: comando a ser enviado
     * categoria: se hoouver
     * equipamento: id do equipamento na tabela 'equipamentos' referente ao comando
     *
     */

    public function get_comandos_continental($id) {
        $query = $this->db->where(array('equipamento' => $id))
            ->order_by('nome')
            ->get('systems.gtw_comandos_lista as cmd');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function get_comandos_ankl($serial) {  
        $cmd = array();
        $cmddd = substr($serial, 0, 4);
        if(substr($serial, 0, 3) == 'OM7'){
            //
            array_push($cmd, (object) array('nome' => 'CONFIGURAR INTERVALO DE POSIÇÃO AUTOMÁTICA', 'code' => 'CAPIX'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR INTERVALO DE POSIÇÃO SATELITAL', 'code' => 'CAPIS'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR TEMPERATURA', 'code' => 'TECFG'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR RASTREADOR', 'code' => 'TCONF'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR SEGURANÇA ELETRÔNICA', 'code' => 'ESCFG'));
            array_push($cmd, (object) array('nome' => 'ENVIAR MENSAGEM DE TEXTO', 'code' => 'TXMSG'));
            array_push($cmd, (object) array('nome' => 'LACRAR MOTOR', 'code' => 'EGNLK'));
            array_push($cmd, (object) array('nome' => 'LIGAR SIRENE', 'code' => 'SIRON'));
            array_push($cmd, (object) array('nome' => 'BLOQUEAR VEÍCULO', 'code' => 'LOCK'));
            array_push($cmd, (object) array('nome' => 'DESBLOQUEAR VEÍCULO', 'code' => 'ULOCK'));
            array_push($cmd, (object) array('nome' => 'DELACRAR BAÚ', 'code' => 'BULK'));
            array_push($cmd, (object) array('nome' => 'DELACRAR MOTOR', 'code' => 'EULK'));
            array_push($cmd, (object) array('nome' => 'DELACRAR SIRENE', 'code' => 'SIROF'));
            array_push($cmd, (object) array('nome' => 'DESTRAVAR BAÚ', 'code' => 'BOXOP'));
            array_push($cmd, (object) array('nome' => 'DESTRAVAR PORTAS', 'code' => 'UDOOR'));
            array_push($cmd, (object) array('nome' => 'EMITIR BIP DE SIRENE', 'code' => 'BIP'));
            array_push($cmd, (object) array('nome' => 'FECHAR RESERVA', 'code' => 'CBOOK'));
            array_push($cmd, (object) array('nome' => 'LACRAR BAÚ', 'code' => 'BLK'));
            array_push($cmd, (object) array('nome' => 'LIBERAR TRAVA DA 5 RODA', 'code' => '5WUKR'));
            array_push($cmd, (object) array('nome' => 'SOLICITAR POSIÇÃO AVULSA', 'code' => 'SPPOS'));
            array_push($cmd, (object) array('nome' => 'SOLICITAR TEMPERATURA AVULSA', 'code' => 'REQTP'));
            array_push($cmd, (object) array('nome' => 'TESTAR COMICAÇÃO COM SATÉLITE', 'code' => 'TSTSC'));
            array_push($cmd, (object) array('nome' => 'TRAVAR BAÚ', 'code' => 'BOXCL'));
            array_push($cmd, (object) array('nome' => 'TRAVAR PORTAS', 'code' => 'DOOR'));
        }
        if(substr($serial, 0, 4) == 'OM14'){
            array_push($cmd, (object) array('nome' => 'ENVIAR COMANDO RESET', 'code' => 'RESET'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR VALOR HODÔMETRO', 'code' => 'ODSET'));
            array_push($cmd, (object) array('nome' => 'BLOQUEAR VEÍCULO', 'code' => 'LOCK'));
            array_push($cmd, (object) array('nome' => 'ENVIAR CONFIGURAR TEMPO PARA AGUARDAR VISADA GPS', 'code' => 'CGSTW'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR INTERVALO DE POSIÇÃO AUTOMÁTICA', 'code' => 'CAPIN'));
            array_push($cmd, (object) array('nome' => 'DESBLOQUEAR O VEÍCULO', 'code' => 'ULOCK'));
            array_push($cmd, (object) array('nome' => 'DESABILITA ANTIFURTO', 'code' => 'ATDIS'));
            array_push($cmd, (object) array('nome' => 'SOLICITAR POSIÇÃO AVULSA', 'code' => 'SPPOS'));
            array_push($cmd, (object) array('nome' => 'HABILITAR ANTIFURTO', 'code' => 'ATENA'));
            array_push($cmd, (object) array('nome' => 'COMANDO HABILITAR EMISSÃO RF', 'code' => 'ENBRF'));
            array_push($cmd, (object) array('nome' => 'COMANDO DESABILITAR EMISSÃO RF', 'code' => 'DSBRF'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR MODO VIOLAÇÃO', 'code' => 'CEVMD'));
            array_push($cmd, (object) array('nome' => 'CONFIGURAR INTERVALO POSIÇÃO EM SLEEP', 'code' => 'CPOSL'));
            array_push($cmd, (object) array('nome' => 'ATIVAR MODO VIOLAÇÃO', 'code' => 'FEVMD'));
        }
        if (substr($serial, 0, 3) == 'OM5'){
            array_push($cmd, (object) array('nome' => 'Bloquear Veículo', 'code' => 'LOCK'));
            array_push($cmd, (object) array('nome' => 'Desbloquear o Veículo', 'code' => 'ULOCK'));
            array_push($cmd, (object) array('nome' => 'Comando Habilitar Emissão RF', 'code' => 'ENBRF'));
            array_push($cmd, (object) array('nome' => 'Comando Desabilitar Emissão RF', 'code' => 'DSBRF'));
        }
        else{
            array_push($cmd, (object) array('nome' => 'ACORDAR RASTREADOR', 'code' => 'ACOR'));
            array_push($cmd, (object) array('nome' => 'ATIVAR MODO ITERATIVO', 'code' => 'ATMI'));
            array_push($cmd, (object) array('nome' => 'ATIVAR MODO RASTREIO', 'code' => 'ATMR'));
            array_push($cmd, (object) array('nome' => 'AUTORIZAR', 'code' => 'AUTH'));
            array_push($cmd, (object) array('nome' => 'BLOQUEAR RASTREADOR', 'code' => 'BLOC'));
            array_push($cmd, (object) array('nome' => 'DESATIVAR RASTREADOR', 'code' => 'DSAT'));
            array_push($cmd, (object) array('nome' => 'DESBLOQUEAR RASTREADOR', 'code' => 'DEBR'));
            array_push($cmd, (object) array('nome' => 'DESLACRAR BAU', 'code' => 'DSLB'));
            array_push($cmd, (object) array('nome' => 'DESLACRAR CABINE', 'code' => 'DSLC'));
            array_push($cmd, (object) array('nome' => 'DESLACRAR CARRETA', 'code' => 'DSLR'));
            array_push($cmd, (object) array('nome' => 'DESLACRAR MOTOR', 'code' => 'DSLM'));
            array_push($cmd, (object) array('nome' => 'ENVIAR BIP', 'code' => 'ENVB'));
            array_push($cmd, (object) array('nome' => 'LACRAR BAU', 'code' => 'LACB'));
            array_push($cmd, (object) array('nome' => 'LACRAR CABINE', 'code' => 'LACC'));
            array_push($cmd, (object) array('nome' => 'LACRAR CARRETA', 'code' => 'LACR'));
            array_push($cmd, (object) array('nome' => 'LACRAR MOTOR', 'code' => 'LACM'));
            array_push($cmd, (object) array('nome' => 'LIBERAR 5 RODA', 'code' => '5WUKR'));
            array_push($cmd, (object) array('nome' => 'OBTER HISTORICO', 'code' => 'OBTH'));
            array_push($cmd, (object) array('nome' => 'SOLICITAR POSICAO AVULSA ', 'code' => 'PPOS'));
            array_push($cmd, (object) array('nome' => 'SOLICITAR TEMPERATURA AVULSA', 'code' => 'TMPA'));
            array_push($cmd, (object) array('nome' => 'LIBERAR FPS', 'code' => 'LIBFP'));
            array_push($cmd, (object) array('nome' => 'INIBIR FPS', 'code' => 'INIF'));
            array_push($cmd, (object) array('nome' => 'INIBIR APAGAR FPS', 'code' => 'INIDELF'));
            
        }
        
        return $cmd;

        
    }

    public function get_comandos_teltonika() {
        $cmd = array();
        array_push($cmd, (object) array('nome' => 'ATIVAR ANTIFURTO', 'code' => 'HABANTIFURTO'));
        array_push($cmd, (object) array('nome' => 'DESATIVAR ANTIFURTO', 'code' => 'DESANTIFURTO'));
        array_push($cmd, (object) array('nome' => 'BLOQUEAR', 'code' => 'BLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'DESBLOQUEAR', 'code' => 'DESBLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'REBOOT', 'code' => 'REBOOT'));

        return $cmd;
    }

    public function get_comandos_multiPortal() {
        $cmd = array();
        array_push($cmd, (object) array('nome' => 'ATIVAR ANTIFURTO', 'code' => 'HABANTIFURTO'));
        array_push($cmd, (object) array('nome' => 'DESATIVAR ANTIFURTO', 'code' => 'DESANTIFURTO'));
        array_push($cmd, (object) array('nome' => 'BLOQUEAR', 'code' => 'BLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'DESBLOQUEAR', 'code' => 'DESBLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'ATIVAR RF', 'code' => 'ATIVARRF'));
        array_push($cmd, (object) array('nome' => 'DESATIVAR RF', 'code' => 'DESATIVARRF'));

        return $cmd;
    }

    public function get_comandos_maxtrack($serial) {

        /*
            Verifica se o equipamento é um maxtrack 700.
        */
        if(substr($serial, 0, 2) == '64' || substr($serial, 0, 2) == '65' ) {

            $this->db->select('cmd.xml as code, descricao nome');
            $this->db->where('code', 16);
            $this->db->or_where('code', 17);
            $this->db->or_where('code', 26);
            $query = $this->db->get('systems.MAXTRACK_COMANDOS as cmd');

            if ($query->num_rows() > 0)
                return $query->result();
            return false;

        }else{

            $query = $this->db->get('systems.COMANDOS_MAXTRACK');
            if($query->num_rows() > 0)
                return $query->result();
            return false;
        }
    }

    public function get_comandos_calamp($id) {
        $query = $this->db->where(array('equipamento' => $id))
            ->order_by('nome')
            ->get('systems.gtw_comandos_lista as cmd');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    /*
     * Função que retorna os comandos disponíveis do suntech.
     * @return Array $cmd
     */
    function get_comandos_suntech() {
        $cmd = array();
        array_push($cmd, (object) array('nome' => 'BLOQUEAR', 'code' => 'BLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'DESBLOQUEAR', 'code' => 'DESBLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'DPA', 'code' => 'CONFDPA'));
        array_push($cmd, (object) array('nome' => 'ATIVAR ANTIFURTO', 'code' => 'HABANTIFURTO'));
        array_push($cmd, (object) array('nome' => 'DESATIVAR ANTIFURTO', 'code' => 'DESANTIFURTO'));
        array_push($cmd, (object) array('nome' => 'REBOOT', 'code' => 'REBOOT'));
        return $cmd;
    }

    /*
     * Função que retorna os comandos disponíveis do suntech.
     * @return Array $cmd
     */
    function get_comandos_e3() {
        $cmd = array();
        array_push($cmd, (object) array('nome' => 'BLOQUEAR', 'code' => 'BLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'DESBLOQUEAR', 'code' => 'DESBLOQUEIO'));
        return $cmd;
    }

    /*
     * Função que retorna os comandos disponíveis do QuecLink.
     * @return Array $cmd
     */
    public function get_comandos_queclink(){

        $cmd = array();
        array_push($cmd, (object) array('nome' => 'BLOQUEAR', 'code' => 'BLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'DESBLOQUEAR', 'code' => 'DESBLOQUEIO'));
        array_push($cmd, (object) array('nome' => 'REBOOT', 'code' => 'AT+GTRTO=gv55,3,,,,,,0001$'));

        return $cmd;
    }

    public function update_requisicao_calamp() {

        // quando não exite o registro na tabela bloqueio_calamp considera que foi enviado
        $query = $this->db->query("update systems.requisicoes set status = '4', tentativa='1' where code not in (select id from systems.bloqueio_calamp)");
        // enquento estiver o registro na tabela bloqueio_calamp considere que ainda não foi enviado
        $query = $this->db->query("update systems.requisicoes set status = '1' where code in (select id from systems.bloqueio_calamp)");
    }

    /*
     * Função que envia comando
     * @param String $serial
     * @param String $cmd
     * @return 1 se tudo ocorre certo
     */

    public function put_comando($serial, $cmd, $id_user=null) {
         
        $resulta = $this->obter_prefixo($serial);

        if($resulta == null){
            return false;
        }

        $modelo = $resulta->equipamento;

        switch ($modelo) {
            case 'Continental': $cmd = $this->put_comandos($serial, $cmd, 'systems.gtw_requisicoes', 'systems.gtw_comandos_enviados', $id_user);
                break;
            case 'Zenite': $cmd = 'Zenite';
                break;
            case 'Quanta': $cmd = 'Quanta';
                break;
            case 'Teltonika': $cmd = $this->put_comandos_teltonika($serial, $cmd, $id_user); // Manda comando para o Teltonika;;
                break;
            case 'Svias': $cmd = 'Svias';
                break;
            case 'CalAmp': $cmd = $this->put_comandos_calamp($serial, $cmd, $id_user); // Manda comando para o Calamp.
                break;
            case 'Suntech': $cmd = $this->put_comandos_suntech($serial, $cmd,$id_user ); // Manda comando para o suntech.
                break;
            case 'MTC700': $cmd = $this->put_comandos_mtc700($serial, $cmd, $id_user); // Manda comando para o MTC700.
                break;
            case 'QuecLink': $cmd = $this->put_comandos_queclink($serial, $cmd, $id_user); // Manda comando para o QuecLink.
                break;
            case 'E3': $cmd = $this->put_comandos_e3($serial, $cmd); // Manda comando para o QuecLink.
                break;
            case 'MultiPortal': $cmd = $this->put_comandos_multiPortal($serial, $cmd, $id_user ); // Manda comando para o QuecLink.
                break;
            case "Omnilink": $cmd = $this->put_comandos_omnilink($serial, $cmd, $id_user); // Manda comando para o Omnilink.
                break;
            default: $cmd = $this->put_comandos_mxt($serial, $cmd, $id_user); // Manda comando para o Maxtrack;
                break;
        }
        return $cmd;
    }

    /*
     * Função para envio de comando para equipamentos E3
     * o gateway utiliza a tabela rastreamento.comando_e3.
     * @param string serial -> serial do equipamento.
     * @param string $cmd -> Comando a ser enviado. ('BLOQUEIO' ou 'DESBLOQUEIO')
     */
    public function put_comandos_e3($serial, $cmd){
        $data_criacao = date('Y-m-d H:i:s');
        $dados_enviar = array(
            'cmd_eqp' => $serial,
            'cmd_comando' => $cmd,
            'cmd_cadastro' => $data_criacao
        );
        $resposta_cmd = $this->db_rastreamento->insert('rastreamento.comando_e3', $dados_enviar);

        return $resposta_cmd;
    }


    /*
     * Função que grava o comando no banco
     * @param string serial -> serial do equipamento
     * @param string cmd    -> comando a ser enviado
     * @param Rtabela       -> tabela de requisição
     *                  a tabela requisição contem datas, tentativas e status
     * @param Ctabela       -> tabela de comandos enviados
     *                  a tabela comandos enviados contem id do comando e id da requisição
     */

    public function put_comandos($serial, $cmd, $Rtabela, $Ctabela , $id_user=0) {
        $data_criacao = date('Y-m-d H:i:s');
        $dados_requisicao = array(
            'id' => $serial,
            'datahora_criacao' => $data_criacao,
            'datahora_atualizacao' => $data_criacao,
            'tentativa' => '0',
            'status' => '0',
            'erro_codigo' => '0',
            'mensagem' => '0'
        );

        $resposta_cmd = $this->db->insert($Rtabela, $dados_requisicao);
        #$cmd_id = $this->db->insert_id();
        $query = $this->db->query('SELECT LAST_INSERT_ID()');
        $row = $query->row_array();
        $cmd_id = $row['LAST_INSERT_ID()'];
        $ret = $this->put_enviar($cmd_id, $cmd, $Ctabela, $id_user);
        return $cmd_id;
    }

    /*
     * função para envio do comando CalAmp
     * o gateway utiliza a tabela bloqueio_calamp como
     *      status 1 bloqueia
     *      status 2 desbloqueia
     *      deleta registro quando envia o comando
     */

    public function put_comandos_calamp($serial, $cmd, $id_user=0) {

        if ($cmd <= 16) {
            $this->db_rastreamento->insert('rastreamento.comando_calamp', array('serial' => $serial,'tipo'=> 1, 'hexa' => '', 'id_usuario' => $id_user));
            $ax = $this->db_rastreamento->insert_id();
        } else if($cmd == 17) {
            $this->db_rastreamento->insert('rastreamento.comando_calamp', array('serial' => $serial,'tipo'=> 2, 'hexa' => '','id_usuario' => $id_user));
            $ax = $this->db_rastreamento->insert_id();
        }

        $ocorrencias = array("c", "a", "l", "C", "A", "L");
        $ser = str_replace($ocorrencias, "", $serial);

        $query = $this->db->where('code', $cmd)->get('systems.gtw_comandos_lista');
        $responde = $query->result();

        $cmd_id = $this->put_comandos($serial, $cmd, 'systems.requisicoes', 'systems.comandos_enviados', $id_user);

        if ($responde) {
            $dados_requisicao = array(
                'serial' => $ser,
                'status' => $responde[0]->valor,
                'id_comando' => $cmd_id,
                'id_usuario' => $id_user
            );

            $axc = $this->db_rastreamento->where('code', $ax)->update('rastreamento.comando_calamp', array('id_comando' => $cmd));
            #printf($axc);die();

            $resposta_cmd = $this->db->insert('systems.bloqueio_calamp', $dados_requisicao);
            $cmd_id = $this->db->insert_id();

            return $resposta_cmd;
        }

        return true;
    }

    /*
     * Função para envio de comando para equipamentos Suntech
     * o gateway utiliza a tabela rastreamento.comando_suntech.
     * @param string serial -> serial do equipamento.
     * @param string $cmd -> Comando a ser enviado. ('BLOQUEIO' ou 'DESBLOQUEIO')
     */
    public function put_comandos_suntech($serial, $cmd, $id_user=0){
        $data_criacao = date('Y-m-d H:i:s');
        $dados_enviar = array(
            'cmd_eqp' => $serial,
            'cmd_comando' => $cmd,
            'cmd_cadastro' => $data_criacao,
            'id_usuario' => $id_user
        );
        $resposta_cmd = $this->db_rastreamento->insert('rastreamento.comandos_enviados', $dados_enviar);

        return $resposta_cmd;
    }

    public function put_comandos_omnilink($serial, $cmd, $id_user){

        $data_criacao = date('Y-m-d H:i:s');
        //$serial = substr($serial,2);
        $body = array(
            'serial' => $serial,
            'cmd' => $cmd,
            'parametros' => [],
            'id_usuario' => $id_user
            
            
        );
        // $resposta_cmd = $this->db_rastreamento->insert('rastreamento.comandos_enviados', $dados_enviar);
        $this->load->helper('api_helper');
        $data = API_Helper::post('comandos/enviar-shownet',$body );
        return json_encode($data);
    }

    /*
     * Função para envio de comando para equipamentos Teltonika
     * o gateway utiliza a tabela rastreamento.comando_teltonika.
     * @param string serial -> serial do equipamento.
     * @param string $cmd -> Comando a ser enviado. ('BLOQUEIO' ou 'DESBLOQUEIO')
     */
    public function put_comandos_teltonika($serial, $cmd, $id_user=0){
        $data_criacao = date('Y-m-d H:i:s');
        $dados_enviar = array(
            'cmd_eqp' => $serial,
            'cmd_comando' => $cmd,
            'cmd_cadastro' => $data_criacao,
            'id_usuario' => $id_user
        );
        $resposta_cmd = $this->db_rastreamento->insert('rastreamento.comandos_enviados', $dados_enviar);

        return $resposta_cmd;
    }

    /*
     * Função para envio de comando para equipamentos QuecLink
     * o gateway utiliza a tabela rastreamento.comando_quicklink.
     * @param string serial -> serial do equipamento.
     * @param string $cmd -> Comando a ser enviado.
     * 	BLOQUEIO => AT+GTOUT=gv55,1,,,0,0,0,,,,1,,,,,,,0001$
     * 	DESBLOQUEIO => AT+GTOUT=gv55,0,,,0,0,0,,,,1,,,,,,,0001$
     *  odometro => 'AT+GTCFG=gv55,gv55,gv55n,1,'.{$valor}.',,,7F,2,0,18EF,,1,0,300,0,,0,1,1C,0,0003$'
     */
    public function put_comandos_queclink($serial, $cmd, $id_user=0){
        $data_criacao = date('Y-m-d H:i:s');
        $dados_enviar = array(
            'cmd_eqp' => $serial,
            'cmd_comando' => $cmd,
            'cmd_cadastro' => $data_criacao,
            'id_usuario' => $id_user
        );
        $resposta_cmd = $this->db_rastreamento->insert('rastreamento.comandos_enviados', $dados_enviar);
        return $resposta_cmd;
    }

    public function put_comandos_multiPortal($serial, $cmd, $id_user=0){
        $data_criacao = date('Y-m-d H:i:s');
        if ($cmd == 'HABANTIFURTO') {
            # Chama metodo de criação de comando comandoHabantifurtoRST para insert
            $dados_enviar = $this->comandoHabantifurtoRST($serial, $data_criacao);
        } elseif ($cmd == 'DESANTIFURTO') {
            # Chama metodo de criação de comando comandoDesantifurtoRST para insert
            $dados_enviar = $this->comandoDesantifurtoRST($serial, $data_criacao);
        } else {
            # Cria comando padrão pra insert
            $dados_enviar[] = array(
                'cmd_eqp' => $serial,
                'cmd_comando' => $cmd,
                'cmd_cadastro' => $data_criacao,
                'id_usuario' => $id_user
            );
        }

        $resposta_cmd = $this->db_rastreamento->insert_batch('rastreamento.comandos_enviados', $dados_enviar);
        return $resposta_cmd;
    }

    /** Função Monta Comando de Habilitar Antifurto */
    private function comandoHabantifurtoRST($serial, $data_criacao)
    {
        return array(
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_SAIDAS_MOTORISTA:0;16;1',
                'cmd_cadastro' => $data_criacao
            ),
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_IDMOTORISTA_GERAL:40',
                'cmd_cadastro' => $data_criacao
            ),
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_IDMOTORISTA_BUZZER:116;5',
                'cmd_cadastro' => $data_criacao
            ),
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_IDMOTORISTA_BLOQUEIO:95;30',
                'cmd_cadastro' => $data_criacao
            ),
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_IDMOTORISTA_SEMCADASTRO:26;2',
                'cmd_cadastro' => $data_criacao
            ),
        );
    }

    /** Função Monta Comando de Desativar Antifurto */
    private function comandoDesantifurtoRST($serial, $data_criacao)
    {
        return array(
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_IDMOTORISTA_BUZZER:0;0',
                'cmd_cadastro' => $data_criacao
            ),
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_IDMOTORISTA_BLOQUEIO:0;0',
                'cmd_cadastro' => $data_criacao
            ),
            array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'CONFIG_IDMOTORISTA_SEMCADASTRO:0;0',
                'cmd_cadastro' => $data_criacao
            )
        );
    }

    /*
     * Função para envio de comando para equipamentos Maxtrack
     * o gateway utiliza a tabela rastreamento.comando_mxt.
     * @param string serial -> serial do equipamento.
     * @param string $cmd -> Comando a ser enviado. ('ODOMETRO=123456')
     */
    public function put_comandos_mxt($serial, $cmd, $id_user=0){

        $data_criacao = date('Y-m-d H:i:s');
        $dados_enviar = array(
            'cmd_eqp' => $serial,
            'cmd_comando' => $cmd,
            'cmd_cadastro' => $data_criacao,
            'id_usuario' => $id_user
        );

        $resposta_cmd = $this->db_rastreamento->insert('rastreamento.comandos_enviados', $dados_enviar);

        return $resposta_cmd;
    }

    /*
     * Função para envio de comando para equipamentos MTC700
     * o gateway utiliza a tabela rastreamento.comando_mxt.
     * @param string serial -> serial do equipamento.
     * @param string $cmd -> Comando a ser enviado. ('ODOMETRO=123456')
     */
    public function put_comandos_mtc700($serial, $cmd, $id_user=0){
        $data_criacao = date('Y-m-d H:i:s');
        $dados_enviar = array(
            'cmd_eqp' => $serial,
            'cmd_comando' => $cmd,
            'cmd_cadastro' => $data_criacao,
            'cmd_id_user' => $id_user
        );

        $resposta_cmd = $this->db_rastreamento->insert('rastreamento.comandos_enviados', $dados_enviar);

        return $resposta_cmd;
    }

    /*
     * Função que envia comando para continental parte 2 chamada feita aparti do put_comando
     * @param String $id
     * @param String $cmd
     * @return 1 se tudo ocorre certo
     */
    public function put_enviar($id, $cmd, $tabela, $id_user) {

        $data_criacao = date('Y-m-d H:i:s');
        $dados_enviar = array(
            'gtw_requisicoes_code' => $id,
            'gtw_comandos_lista_code' => $cmd,
            'datahora_criacao' => $data_criacao,
            'id_usuario' => $id_user
        );

        $resposta_cmd = $this->db->insert($tabela, $dados_enviar);
        #$cmd_id = $this->db->insert_id();

        return $resposta_cmd;
    }

    function continental($serial, $value) {
        $this->db_rastreamento->insert('rastreamento.comandos_enviados',
            array('cmd_eqp' => $serial , 'cmd_comando' => 'ODOMETRO='.$value, 'cmd_cadastro' => date('Y-m-d H:i:s') )
        );
    }

    public function envia_comando($serial, $placa, $cmd, $valor = null) {

        $comand = $this->get_cmd_mxt(array('code' => $cmd));
        $comando = $comand->xml;
        $xml = str_replace(chr(13), '', $comando);

        $protocolo = $this->get_protocolo_equipamento($serial);
        $id_comando = uniqid();

        $xml = str_replace('{protocolo}', $protocolo, $xml);
        $xml = str_replace('{serial}', $serial, $xml);
        $xml = str_replace('{id_comando}', $id_comando, $xml);
        $xml = str_replace('{valor}', $valor, $xml);
        $xml = str_replace('{tentativa}', '15', $xml);
        $xml = str_replace('{tempo}', date('Y-m-d 23:59:59'), $xml);

        $dados_xml['COMANDOS'] = $xml;
        $dados_xml['serial'] = $serial;
        $dados_xml['placa'] = $placa;
        $dados_xml['data'] = date('Y-m-d h:i:s');
        $dados_xml['id_comando'] = $id_comando;
        $dados_xml['code'] = $id_comando;
        $this->inserir_xml($dados_xml);

        $comando  = '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n";
        $comando .= '<COMMANDS>' . "\n";
        $comando .= $xml . "\n";
        $comando .= '</COMMANDS>' . "\n";

        $path = 'gateway/commands_mxt/';
        $file = 'C_'.$protocolo.'_'.$serial.'_'.$id_comando.'.cmd';

        $gravou_file = write_file($path.$file, $comando, 'w');

        if ($gravou_file) {
            $arquivo = '/home/show/http/sistema/newapp/gateway/commands_mxt/'.$file;
            $this->enviar_file_para_gateway($arquivo, $file);
            return true;
        }else{
            return false;
        }
    }

    public function send($comando, $serial, $id_comando, $data, $descricao){

        $this->db->insert('systems.MAXTRACK_ENVIAR', array('COMANDOS' => $comando, 'serial' => $serial, 'flag' => 1,'id_comando' => $id_comando,'code'=>$id_comando,'data'=>$data,'comando'=>$descricao));
        if($this->db->affected_rows() > 0)
            return true;

        return false;

    }

    public function enviar_comando_maxtrack($serial, $cmd, $valor = null) {

        /*
            Verifica se o equipamento é um maxtrack 700.
        */
        if(substr($serial, 0, 2) == '64' || substr($serial, 0, 2) == '65' ) {

            if ($cmd == 29) {
                $valor = 10000;
            }

            if ($cmd[0] == 's') {
                $code = explode("s", $cmd);
                $comand = $this->comando->get_xml($code[1]);
                $comando = $comand[0]->xml;
            }else{
                $comand = $this->get_cmd_mxt(array('code' => $cmd));
                $comando = $comand->xml;
            }
            $xml = str_replace(chr(13), '', $comando);
            $type = 51;
            $timeout = date('Y-m-d H:i:s', strtotime("+30 days"));
            $protocolo = $this->get_protocolo_equipamento($serial);
            $type = 6;

            $id_comando = uniqid();
            $xml = str_replace('{type}', $type, $xml);
            $xml = str_replace('{protocolo}', $protocolo, $xml);
            $xml = str_replace('{serial}', $serial, $xml);
            $xml = str_replace('{id_comando}', $id_comando, $xml);
            $xml = str_replace('{valor}', $valor, $xml);
            $xml = str_replace('{tentativa}', '15', $xml);
            $xml = str_replace('{tempo}',$timeout , $xml);

            //$dados['COMANDOS'] = str_replace('{id_comando}', $serial, $xml);

            $comando  = '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n";
            $comando .= '<COMMANDS>' . "\n";
            $comando .= $xml . "\n";
            $comando .= '</COMMANDS>' . "\n";
            $path = 'gateway/commands_mxt/';
            $file = 'C_'.$protocolo.'_'.$serial.'_'.$id_comando.'.cmd';

            write_file($path.$file, $comando, 'w');

            $arquivo = '/home/show/http/sistema/newapp/gateway/commands_mxt/'.$file;
            $this->enviar_file_para_gateway700($arquivo, $file);

            if ($cmd[0] == 's') {
                $code = explode("s", $cmd);
                $result = $this->comando->get_nome($code[1]);
                $this->send($comando,$serial,$id_comando,date('Y-m-d H:i:s'),$result[0]->nome);
            }else{
                $this->send($comando,$serial,$id_comando,date('Y-m-d H:i:s'),$comand->descricao);
            }

        }else {

            if($cmd == 'BLOQUEIO' or $cmd == 'DESBLOQUEIO') {

                $cmd == 'BLOQUEIO' ? $cmd_xml = 16 : $cmd_xml = 17;

                if ($cmd_xml == 29) {
                    $valor = 10000;
                }

                if ($cmd_xml[0] == 's') {
                    $code = explode("s", $cmd_xml);
                    $comand = $this->comando->get_xml($code[1]);
                    $comando = $comand[0]->xml;
                }else{
                    $comand = $this->get_cmd_mxt(array('code' => $cmd_xml));
                    $comando = $comand->xml;
                }
                $xml = str_replace(chr(13), '', $comando);
                $type = 51;
                $timeout = date('Y-m-d H:i:s', strtotime("+30 days"));
                $protocolo = $this->get_protocolo_equipamento($serial);

                $id_comando = uniqid();
                $xml = str_replace('{type}', $type, $xml);
                $xml = str_replace('{protocolo}', $protocolo, $xml);
                $xml = str_replace('{serial}', $serial, $xml);
                $xml = str_replace('{id_comando}', $id_comando, $xml);
                $xml = str_replace('{valor}', $valor, $xml);
                $xml = str_replace('{tentativa}', '15', $xml);
                $xml = str_replace('{tempo}',$timeout , $xml);

                $comando  = '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n";
                $comando .= '<COMMANDS>' . "\n";
                $comando .= $xml . "\n";
                $comando .= '</COMMANDS>' . "\n";
                $path = 'gateway/commands_mxt/';
                $file = 'C_'.$protocolo.'_'.$serial.'_'.$id_comando.'.cmd';

                write_file($path.$file, $comando, 'w');

                $arquivo = '/home/show/http/sistema/newapp/gateway/commands_mxt/'.$file;

                $this->enviar_file_para_gateway($arquivo, $file);

                if ($cmd_xml[0] == 's') {
                    $code = explode("s", $cmd_xml);
                    $result = $this->comando->get_nome($code[1]);
                    $this->send($comando,$serial,$id_comando,date('Y-m-d H:i:s'),$result[0]->nome);
                }else{
                    $this->send($comando,$serial,$id_comando,date('Y-m-d H:i:s'),$comand->descricao);
                }
            }

            $dados_enviar = array('cmd_eqp' => $serial,'cmd_comando' => $cmd,'cmd_cadastro' => date('Y-m-d H:i:s'));
            return $this->db_rastreamento->insert('rastreamento.comandos_enviados', $dados_enviar);
        }
    }

    public function enviar_file_para_gateway700($full_file, $file_name)
    {
        $this->load->library('ftp');
        $remote_path = '/var/www/html/gtw_mxt/gateway/commands/';
        $config['hostname'] = '192.99.110.183';
        $config['username'] = 'ftp-max';
        $config['password'] = 'ftp5498';
        $config['debug']    = TRUE;

        $this->ftp->connect($config);

        $this->ftp->upload($full_file, $remote_path.$file_name, 'ascii', 0775);

        $this->ftp->close();
    }

    // Função em desuso. (Verificar para retirar)
    public function enviar_file_para_gateway($full_file, $file_name)
    {

        $this->load->library('ftp');

        $remote_path = '/var/www/html/gestor/gateway/commands/';

        $config['hostname'] = '192.99.106.12';
        $config['username'] = 'ftp-max';
        $config['password'] = 'ftp5498';
        $config['debug']    = TRUE;

        $this->ftp->connect($config);

        $this->ftp->upload($full_file, $remote_path.$file_name, 'ascii', 0775);

        $this->ftp->close();

    }

    /**
     * Resgata o serial de um equipamento de um determinado veículo
     *
     * @param array $where - Condição para resgatar o serial
     *
     * @return mixed - Os dados do veículo ou false
     **/
    private function get_serial_veiculo($where)
    {
        $this->db->select('serial, placa, veiculo');
        $this->db->order_by('placa');
        $query = $this->db->get_where('systems.cadastro_veiculo', $where);

        if ($query->num_rows()) {
            return $query->row();
        }

        return false;
    }

    private function get_seriais_veiculo($where)
    {

        $this->db->select('serial, placa, veiculo');
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get('systems.cadastro_veiculo');

        if ($query->num_rows()) {
            return $query->row();
        }

        return array();

    }

    private function get_sequencia()
    {
        $arquivo = 'gateway/gwt_maxtrack.seq';

        $sequencia = read_file($arquivo) + 1;

        write_file($arquivo, $sequencia, 'w');

        return $sequencia;
    }

    private function inserir_xml($dados) {
        $dados['flag'] = 9;
        $this->db->insert('systems.MAXTRACK_ENVIAR', $dados);
        $id = $this->db->insert_id();
        $dados['COMANDOS'] = str_replace('{id_comando}', $id, $dados['COMANDOS']);
        $dados['flag'] = 1;
        if ($this->db->affected_rows())
            return $dados['COMANDOS'];
        return false;
    }

    /**
     * Retorna o protocolo de envio de comando do respectivo equipamento
     *
     * @param string $serial - Serial do equipamento
     *
     * @return integer - Número do protocolo
     **/
    private function get_protocolo_equipamento($serial)
    {
        if (substr($serial, 0, 2) == '12' || substr($serial, 0, 2) == '13' || substr($serial, 0, 2) == '83' || substr($serial, 0, 2) == '82' || substr($serial, 0, 2) == '81') {
            return 166;
        } else if (substr($serial, 0, 2) == '07') {
            return 45;
        } else if (substr($serial, 0, 2) == '14') {
            return 166;
        } else if (substr($serial, 0, 2) == '64' || substr($serial, 0, 2) == '65') {
            return 1;
        } else if (substr($serial, 0, 2) == '51' || substr($serial, 0, 2) == '52') {
            return 166;
        } else {
            return 166;
        }
    }

    public function get_cmd_mxt($where) {
        $query = $this->db->get_where('systems.MAXTRACK_COMANDOS', $where);
        if($query->num_rows())
            return $query->row();
        return array();
    }

    public function listar_eqp($where=array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC', $select = '*', $like = NULL) {

        $this->db->select($select);
        if ($like)
            $this->db->like($like);

         return $this->db->group_by($campo_ordem)->get('showtecsystem.cad_equipamentos', $limite, $paginacao)->result();
    }

    public function get_equipamentos($ser) {
        if ($ser) {
            $query = $this->db->query("SELECT serial FROM showtecsystem.cad_equipamentos WHERE serial = '$ser'");
            if ($query->num_rows())
                return $query->result();
        } else {
            $query = $this->db->query("SELECT serial FROM showtecsystem.cad_equipamentos GROUP BY serial");
            if ($query->num_rows()) {
                foreach ($query->result() as $equipamento)
                    $equipamentos[] = $equipamento->serial;
                return json_encode($equipamentos);
            }
        }
        return false;
    }

    public function verificar_equipamento($serial, $placa) {
        $this->db->select('serial, placa');
        $this->db->where('serial', $serial);
        $this->db->where('placa !=', $placa);
        $this->db->where('placa !=', '');
        $query = $this->db->get('systems.cadastro_veiculo');
        return $query->num_rows() ? true : false ;
    }

    public function get_equipamentos_desvincular($serial) {
        $placas = false;
        $this->db->select('serial, placa');
        $this->db->where('serial', $serial);
        $query = $this->db->get('systems.cadastro_veiculo');
        if ($query->num_rows()) {
            $veiculos = $query->result();
            foreach ($veiculos as $key => $veic) {
                $placas[] = $veic->placa;
            }
        }
        return $placas;
    }

    public function listar($where=array(), $start = 0, $limit = 10, $camp_ord = 'id', $orde = 'desc', $select = '*', $like=NULL) {

        $this->db->select($select);
        if ($where)
            $this->db->where($where);
        if ($like)
            $this->db->like($like);
        $this->db->order_by($camp_ord, $orde);
        $this->db->limit($limit, $start);
        return $this->db->get('showtecsystem.cad_equipamentos')->result();
    }

    public function listar_equipamentos_disponiveis($start = 0, $limit = 10, $camp_ord = 'id', $ordem = 'desc', $like=NULL) {
        $sql = 'SELECT eqp.serial as text, eqp.serial as id
              FROM showtecsystem.cad_equipamentos eqp
              where eqp.serial like "%'.$like.'%" and not exists (select 1 from showtecsystem.contratos_veiculos c where c.equipamento = eqp.serial and c.status = "ativo")
              ORDER BY '.$camp_ord.' '.$ordem.'
              LIMIT '.$start.', '.$limit.';';

        return $this->db->query($sql)->result();
    }

    public function listar_pesquisa_equipamento($pesquisa) {
        $this->db->select('eq.id, eq.serial, eq.marca, eq.modelo, eq.data_cadastro, eq.ccid as firstccid, chip.numero, chip.ccid, clie.nome, log.dataEnvio, log.dataRecebimento, env.nome as envPara');
        $this->db->from('showtecsystem.cad_equipamentos as eq');
        $this->db->join('showtecsystem.cad_chips as chip', 'eq.id_chip = chip.id', 'left');
        $this->db->join('systems.cadastro_veiculo as veic', 'eq.serial = veic.serial', 'left');
        $this->db->join('showtecsystem.cad_clientes as clie', 'veic.id_usuario = clie.id', 'left');
        $this->db->join('showtecsystem.cad_logistica as log', 'eq.id = log.equipamento', 'left');
        $this->db->join('showtecsystem.cad_clientes as env', 'log.cliente = env.id', 'left');
        $this->db->like($pesquisa['coluna'], $pesquisa['palavra']);
        $query = $this->db->get();
        if ($query->num_rows())
            return $query->result();
        return false;
    }

    private function getChipByIdChip($id) {
        return $this->db->get_where('showtecsystem.cad_chips', array('id' => $id))->row();
    }

    private function getLinhaByCcid($ccid) {
        $dados = $this->db->order_by('data_insert', 'desc')->limit(1)->get_where('showtecsystem.detalhamento_conta', array('ccid' => $ccid))->row();
        if ($dados)
            return "{$dados->ddd}"."{$dados->linha}";
        return '';
    }

    /*
    * GET EQUIPAMENTOS SERVE-SIDE
    */
    public function getEquipamentosServerSide($select='*', $order=false, $start=0, $limit=999999, $search=false, $filtro='', $draw=1, $temChip=false, $temLinha=false, $qtdTotal=false) {
        $colunas = array('id', 'serial', 'modelo', 'data_cadastro');

        if (!$qtdTotal) $this->db->select($select);

        if($search) {
            if ($filtro == 'data_cadastro') {
                $this->db->where(array('data_cadastro >=' => $search.' 00:00:00', 'data_cadastro <=' => $search.' 23:59:59'));

            }elseif ($filtro == 'ccid1') {
                if($temChip) $this->db->where('id_chip', $temChip);

            }elseif ($filtro == 'ccid2' && $temChip) {
                if($temChip) $this->db->where('id_chip_2', $temChip);

            }elseif ($filtro == 'linha1' && $temLinha) {
                if($temLinha) $this->db->where('id_chip', $temLinha);

            }elseif ($filtro == 'linha2' && $temLinha) {
                if($temLinha) $this->db->where('id_chip_2', $temLinha);

            }else {
                $this->db->where($filtro, $search);
            }
        }

        if ($order) $this->db->order_by($colunas[$order['column']], $order['dir']);
        else $this->db->order_by('id', 'DESC');

        if ($qtdTotal) $query = $this->db->count_all_results('showtecsystem.cad_equipamentos');
        else $query = $this->db->get('showtecsystem.cad_equipamentos', $limit, $start);

		return $query;
    }

    /*
    * LISTA EVENTOS PANICOS
    */
    public function listEquipamentosServerSide($select='*', $order=false, $start=0, $limit=999999, $search=false, $filtro='', $draw=1, $temChip=false, $temLinha=false) {
        $dados = array();
	    $query = $this->getEquipamentosServerSide($select, $order, $start, $limit, $search, $filtro, $draw, $temChip, $temLinha);
        $queryQtdTotal = $this->getEquipamentosServerSide('id', $order, $start, $limit, $search, $filtro, $draw, $temChip, $temLinha, true);

        if($query->num_rows() > 0){
			$dados['equipamentos'] = $query->result(); # Lista de eventos
			$dados['recordsTotal'] = $queryQtdTotal; # Total de registros
            $dados['recordsFiltered'] = $dados['recordsTotal']; # atribui o mesmo valor do recordsTotal ao recordsFiltered para ter todas as paginas na datatable
	        $dados['draw'] = $draw++; # Draw do datatable

			return $dados;
		}

		return false;
    }

    public function listar_equipamentos($start=0, $limit=10, $search = NULL, $draw = 1) {
        if ($search && is_string($search)) {
            $sql = 'SELECT eqp.id_chip, eqp.id_chip_2, eqp.id as codigo, eqp.serial, eqp.modelo, eqp.data_cadastro as dataDeCadastro, chip.numero as linha, chip_2.numero as linha_2, eqp.ccid, eqp.status, chip_manual.numero, chip_manual.ccid as ccid_manual, chip_manual_2.numero as numero_2, chip_manual_2.ccid as ccid_manual_2
              FROM showtecsystem.cad_equipamentos AS eqp
              LEFT JOIN showtecsystem.cad_chips AS chip ON eqp.ccid = chip.ccid and eqp.id_chip is not null
              LEFT JOIN showtecsystem.cad_chips AS chip_2 ON eqp.ccid = chip_2.ccid and eqp.id_chip_2 is not null
              LEFT JOIN showtecsystem.cad_chips AS chip_manual ON eqp.id_chip = chip_manual.id
              LEFT JOIN showtecsystem.cad_chips AS chip_manual_2 ON eqp.id_chip_2 = chip_manual_2.id
              WHERE chip.ccid like "%'.$search.'%" OR chip_2.ccid like "%'.$search.'%" OR eqp.serial like "%'.$search.'%"
              GROUP BY eqp.serial
              ORDER BY eqp.id DESC';
        } else {
            $sql = 'SELECT eqp.id_chip, eqp.id_chip_2, eqp.id as codigo, eqp.serial, eqp.modelo, eqp.data_cadastro as dataDeCadastro, chip.numero as linha, chip_2.numero as linha_2, eqp.ccid, eqp.status, chip_manual.numero, chip_manual.ccid as ccid_manual, chip_manual_2.numero as numero_2, chip_manual_2.ccid as ccid_manual_2
              FROM showtecsystem.cad_equipamentos AS eqp
              LEFT JOIN showtecsystem.cad_chips AS chip ON eqp.ccid = chip.ccid
              LEFT JOIN showtecsystem.cad_chips AS chip_2 ON eqp.ccid = chip_2.ccid
              LEFT JOIN showtecsystem.cad_chips AS chip_manual ON eqp.id_chip = chip_manual.id
              LEFT JOIN showtecsystem.cad_chips AS chip_manual_2 ON eqp.id_chip_2 = chip_manual_2.id
              GROUP BY eqp.serial
              ORDER BY eqp.id DESC';
        }

        $data['recordsTotal'] = 115729; # Total de registros
        $data['recordsFiltered'] = $this->db->query($sql.';')->num_rows; # Total de registros Filtrados
        $equipamentos = $this->db->query($sql.' LIMIT '.$start.', '.$limit.';')->result(); # Dados do cadastro

        $data['draw'] = $draw+1; # Draw do datatable
        $data['data'] = array(); # Cria Array de registros
        for ($i=0;$i<count($equipamentos);$i++) {
            $equipamento = array();
            if ($equipamentos[$i]->ccid) {
                $equipamento['info'] = '<b>AUTO<b/>';
            } elseif ($equipamentos[$i]->id_chip || $equipamentos[$i]->id_chip_2 || $equipamentos[$i]->linha || $equipamentos[$i]->linha_2) {
                $equipamento['info'] = '<b>MANUAL<b/>';
            } else {
                $equipamento['info'] = '<b>SEM VINCULO<b/>';
            }

            if ($equipamentos[$i]->status == 2) {
                $equipamento['status'] = "<span class='label label-warning'> Enviado ao Cliente </span>";
            } elseif ($equipamentos[$i]->status == 3) {
                $equipamento['status'] = "<span class='label label-default'> Disponivel no Cliente </span>";
            } elseif ($equipamentos[$i]->status == 4) {
                $equipamento['status'] = "<span class='label label-success'> Instalado </span>";
            } else {
                $equipamento['status'] = "<span class='label label-info'> Em Estoque </span>";
            }

            if ($equipamentos[$i]->ccid && $equipamentos[$i]->linha) {
                $equipamento['linha'] = $equipamentos[$i]->linha;
                $equipamento['ccid'] = $equipamentos[$i]->ccid;
            } if ($equipamentos[$i]->ccid_manual && $equipamentos[$i]->numero) {
                $equipamento['linha'] = $equipamentos[$i]->numero;
                $equipamento['ccid'] = $equipamentos[$i]->ccid_manual;
            } else {
                $equipamento['linha'] = '';
                $equipamento['ccid'] = '';
            }

            if ($equipamentos[$i]->ccid && $equipamentos[$i]->linha_2) {
                $equipamento['linha_2'] = $equipamentos[$i]->linha_2;
                $equipamento['ccid_2'] = $equipamentos[$i]->ccid;
            }else if ($equipamentos[$i]->ccid_manual_2 && $equipamentos[$i]->numero_2) {
                $equipamento['linha_2'] = $equipamentos[$i]->numero_2;
                $equipamento['ccid_2'] = $equipamentos[$i]->ccid_manual_2;
            } else {
                $equipamento['linha_2'] = '';
                $equipamento['ccid_2'] = '';
            }

            $equipamento['editar'] = '<a class="btn btn-primary" data-toggle="modal" id="btn_editar"
            href="#" data-id="'.$equipamentos[$i]->codigo.'" onclick="change_equipamento(this)" data-id_linha_1="'.$equipamentos[$i]->id_chip.'" data-linha_1="'.$equipamento['linha'].'" data-id_linha_2="'.$equipamentos[$i]->id_chip_2.'" data-linha_2="'.$equipamento['linha_2'].'"
            data-target="#editar_equipamento" title="Editar Equipamento"> <i class="fa fa-pencil-square"></i></a>';

            $equipamento['posição'] = ' <a data-url="'.site_url('equipamentos/posicao/'.$equipamentos[$i]->serial).'"
                class="btn btn-primary visualiza_posicao" data-toggle="modal"
                data-target="#modal_posicao" title="Posição Equipamento"> <i class="fa fa-map-marker"></i>
            </a>';

            $data['data'][] = array(
                $equipamentos[$i]->codigo,
                $equipamentos[$i]->serial,
                $equipamentos[$i]->modelo,
                dh_mktime_for_humans($equipamentos[$i]->dataDeCadastro),
                $equipamento['linha'],
                $equipamento['ccid'],
                $equipamento['linha_2'],
                $equipamento['ccid_2'],
                $equipamento['info'],
                $equipamento['status'],
                $equipamento['editar'],
                $equipamento['posição']
            );
        }

        return (object) $data;
    }

    public function equipamento_id($id) {
        $equipamentos = false;
        $this->db->where('id', $id);
        $query = $this->db->get('showtecsystem.cad_equipamentos');
        if ($query->num_rows())
            $equipamentos = $query->result();
        return $equipamentos;
    }

    public function get_total_equipamentos() {
        $query = $this->db->get('showtecsystem.cad_equipamentos');
        return $query->num_rows();
    }

    public function get_resposta($serial) {
        @$this->db_rastreamento->where('ID', $serial);
        return $this->db_rastreamento->get('rastreamento.last_track')->row();

    }

    public function adicionar_equipamento($equipamento) {
        $dados = array(
            'serial' => $equipamento['serial'],
            'marca' => $equipamento['marca'],
            'modelo' => $equipamento['modelo'],
            'data_cadastro' => date('Y-m-d H:i:s')
        );
        if (isset($equipamento['id_chip']) || isset($equipamento['id_chip_2'])) {
            $this->db->insert('showtecsystem.cad_equipamentos', $dados);
            return $this->editar_equipamento(
                $this->db->insert_id(),
                isset($equipamento['id_chip']) ? $equipamento['id_chip'] : null,
                isset($equipamento['id_chip_2']) ? $equipamento['id_chip_2'] : null);
        } else
            return $this->db->insert('showtecsystem.cad_equipamentos', $dados);
    }

    public function editar_equipamento($id_equipamento, $id_chip = null, $id_chip_2 = null)	{
        if($id_chip || $id_chip_2) {
            $dados = array( 'id_equipamento' => $id_equipamento, 'status' => '1');
            if($this->db->where('id', $id_chip)->update('showtecsystem.cad_chips', $dados) && $this->db->where('id', $id_chip_2)->update('showtecsystem.cad_chips', $dados)) {
                $dados = array( 'id_chip' => $id_chip, 'id_chip_2' => $id_chip_2);
                $this->db->where('id', $id_equipamento)->update('showtecsystem.cad_equipamentos', $dados);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function disvincular_equipamento($id_equipamento) {
        $dados = array('id_equipamento' => '');
        if($this->db->where('id_equipamento', $id_equipamento)->update('showtecsystem.cad_chips', $dados)) {
            $dados = array('id_chip' => '');
            $this->db->where('id', $id_equipamento)->update('showtecsystem.cad_equipamentos', $dados);
            return true;
        }else{
            return false;
        }
    }

    public function get_id_chip($linha)	{
        $this->db->where('numero', $linha);
        $query = $this->db->get('showtecsystem.cad_chips');
        if ($query->num_rows()) {
            foreach ($query->result() as $key ) {
                return $key->id;
            }
        }
        return false;
    }

    function getIdSerial($serial){
        $query = $this->db->select('id')->where('serial', $serial)->get('showtecsystem.cad_equipamentos');
        if ($query->num_rows()) {
            foreach ($query->result() as $key) {
                $key->id;
            }
        }
        return FALSE;
    }

    public function get_numberSerial($numSerial){
        $q = $this->db->select('serial')->where('id', $numSerial)->get('showtecsystem.cad_equipamentos');# [0]->serial;
        return $q->num_rows ? $q->result()[0]->serial : null ;
    }

    public function get_numberSerial_recall($numSerial){
        $q = $this->db->select('serial')->where('id', $numSerial)->get('showtecsystem.cad_equipamentos');#->result()[0]->serial;
        return $q->num_rows ? $q->result()[0]->serial : null ;
    }

    public function get_equipamento($coluna, $chave) {
        $this->db->where($coluna, $chave);
        $query = $this->db->get('cad_equipamentos');
        if ($query->num_rows()) {
            return $query->result();
        }
        return FALSE;
    }

    ## busca serial select 2
    public function get_equipamento_serial($like) {
        $this->db->select('id, serial');
        $this->db->like('serial', $like);
        $this->db->where('status', '1');
        $query = $this->db->get('showtecsystem.cad_equipamentos',0,20);
        if ($query->num_rows()) {
            return $query->result();
        }
        return FALSE;
    }

    public function saveCmdSuntech($serial, $limiteVel){

        if ($limiteVel != "") {
            $data = array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'EXVELOCIDADE='.$limiteVel,
                'cmd_cadastro' => date('Y-m-d H:i:s', time())
            );
        }else{
            $data = array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'EXVELOCIDADE=80',
                'cmd_cadastro' => date('Y-m-d H:i:s',time())
            );
        }
        return $this->db_rastreamento->insert('comando_suntech', $data);

    }
    public function saveCmdMaxtrack($serial, $limiteVel){

        if ($limiteVel != ""){
            $data = array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'EXVELOCIDADE='.$limiteVel,
                'cmd_cadastro' => date('Y-m-d H:i:s',time())
            );
        }else{
            $data = array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'EXVELOCIDADE=80',
                'cmd_cadastro' => date('Y-m-d H:i:s',time())
            );
        }
        return $this->db_rastreamento->insert('comando_mxt', $data);

    }

    public function saveCmdQuicklink($serial, $limiteVel){

        if ($limiteVel != ""){
            $data = array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'AT+GTSPD=gv55,3,0,'.$limiteVel.',1,300,0,0,0,0,,,,,,,,,,,,0000$',
                'cmd_cadastro' => date('Y-m-d H:i:s',time())
            );
        }else{
            $data = array(
                'cmd_eqp' => $serial,
                'cmd_comando' => 'AT+GTSPD=gv55,3,0,80,1,300,0,0,0,0,,,,,,,,,,,,0000$',
                'cmd_cadastro' => date('Y-m-d H:i:s',time())
            );
        }

        return $this->db_rastreamento->insert('comando_quicklink', $data);

    }

    public function getParados($dtIni, $dtFim) {
        $dados = $seriais = array();
        $parados = $this->db_rastreamento->get_where('rastreamento.last_track', array('DATA >=' => $dtIni, 'DATA <=' => $dtFim))->result();

        if ($parados) {
            foreach ($parados as $parado) {
                $seriais[] = $parado->ID;
                $datas[$parado->ID] = $parado->DATA;
            }

            $dados = $this->db->select('e.serial, e.ccid, c.numero, clie.nome')
                ->join('showtecsystem.cad_chips as c', 'e.ccid = c.ccid', 'left')
                ->join('systems.cadastro_veiculo as veic', 'e.serial = veic.serial', 'left')
                ->join('showtecsystem.cad_clientes as clie', 'veic.id_usuario = clie.id', 'left')
                ->where_in('e.serial', $seriais)
                ->where('e.ccid is not null')
                ->get('showtecsystem.cad_equipamentos as e')
                ->result();

            if ($dados) {
                foreach ($dados as $d) {
                    $d->data = $datas[$d->serial] ? $datas[$d->serial] : '';
                }
            }

            return $dados;
        } else {
            return array();
        }
    }

    function instalado($eqp) {
        if ($eqp->placa) {
            $contrato = $this->db->get_where('showtecsystem.contratos_veiculos', array('placa' => $eqp->placa, 'status' => 'ativo'))->result();

            if ($contrato) {
                if ($eqp->status != '4')
                    $this->db->where('id', $eqp->id)->update('showtecsystem.cad_equipamentos', array('status' => '4'));
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function enviado($eqp) {
        $sql = "SELECT * FROM showtecsystem.cad_logistica WHERE statusOS = '4' AND equipamento = '{$eqp->id}' OR statusOS = '6' AND equipamento = '{$eqp->id}';";
        $log = $this->db->query($sql)->result();

        if ($log) {
            if ($eqp->status != '2') {}
                $this->db->where('id', $eqp->id)->update('showtecsystem.cad_equipamentos', array('status' => '2'));
            return true;
        } else {
            return false;
        }
    }

    function cliente($eqp) {
        $sql = "SELECT * FROM showtecsystem.cad_logistica WHERE statusOS = '7' AND equipamento = '{$eqp->id}' OR statusOS = '8' AND equipamento = '{$eqp->id}';";
        $log = $this->db->query($sql)->result();

        if ($log) {
            if ($eqp->status != '3')
                $this->db->where('id', $eqp->id)->update('showtecsystem.cad_equipamentos', array('status' => '3'));
            return true;
        } else {
            return false;
        }
    }

    function estoque($eqp) {
        if ($eqp->placa) {
            return false;
        } else {
            $this->db->where('id', $eqp->id)->update('showtecsystem.cad_equipamentos', array('status' => '1'));
            return true;
        }
    }

    public function count_estoque() {
        # 'est_atual' => Estoque atual, 'est_env' => Equip enviados aos cliente, 'est_clie' => Estoque em Clientes, 'est_dis' => Estoque disponivel em clientes

        $estoque = array(
            'est_atual' => $this->db->where('status', 1)->count_all_results('showtecsystem.cad_equipamentos'),
            'est_env' => $this->db->where('status', 2)->count_all_results('showtecsystem.cad_equipamentos'),
            'est_clie' => $this->db->where('status', 4)->count_all_results('showtecsystem.cad_equipamentos'),
            'est_dis' => $this->db->where('status', 3)->count_all_results('showtecsystem.cad_equipamentos')
        );

        return $estoque;
    }

    public function qntd_eqp_byMarca() {
        $this->db->select('marca');
        $this->db->distinct();
        $marcas = $this->db->get('showtecsystem.cad_equipamentos')->result();

        foreach ($marcas as $marca) {
            # METODO SALVA A QUANTIDADE DE EQUIPAMENTOS POR MARCA
            $this->db->where('marca', $marca->marca);
            $marca->qntd = $this->db->count_all_results('showtecsystem.cad_equipamentos');

            # METODO BUSCA OS MODELOS DE EQUIP DA MARCA
            $this->db->select('modelo');
            $this->db->distinct();
            $this->db->where('marca', $marca->marca);
            $marca->modelos = $this->db->get('showtecsystem.cad_equipamentos')->result();

            # BUSCA QUANTADIDADE POR MODELO DA MARCA
            foreach ($marca->modelos as $modelo) {
                $this->db->where('modelo', $modelo->modelo);
                $modelo->qntd = $this->db->count_all_results('showtecsystem.cad_equipamentos');
            }
        }

        return $marcas;
    }

    public function eqp_ativos() {
        $this->db->select('marca');
        $this->db->distinct();
        $marcas = $this->db->get('showtecsystem.cad_equipamentos')->result();

        # METODO SALVA A QUANTIDADE DE EQUIPAMENTOS ATIVOS POR MARCA
        foreach ($marcas as $marca) {
            $this->db->select('veic.placa');
            $this->db->distinct('eqp.serial, cad_veic.placa, veic.placa');
            $this->db->from('showtecsystem.cad_equipamentos as eqp');
            $this->db->join('systems.cadastro_veiculo as cad_veic', 'eqp.serial = cad_veic.serial', 'INNER');
            $this->db->join('showtecsystem.contratos_veiculos as veic', 'veic.placa = cad_veic.placa', 'INNER');
            $this->db->where('eqp.marca', $marca->marca);
            $this->db->where('veic.status', 'ativo');
            $this->db->where('eqp.serial !=', '');
            $marca->ativos = $this->db->get()->num_rows();

            # METODO BUSCA OS MODELOS DE EQUIP DA MARCA
            $this->db->select('modelo');
            $this->db->distinct();
            $this->db->where('marca', $marca->marca);
            $marca->modelos = $this->db->get('showtecsystem.cad_equipamentos')->result();

            foreach ($marca->modelos as $modelo) {
                # METODO BUSCA A QUANTIDADE DE EQUIPAMENTOS ATIVOS POR MODELO
                $this->db->select('veic.placa');
                $this->db->distinct('eqp.serial, cad_veic.placa, veic.placa');
                $this->db->from('showtecsystem.cad_equipamentos as eqp');
                $this->db->join('systems.cadastro_veiculo as cad_veic', 'eqp.serial = cad_veic.serial', 'INNER');
                $this->db->join('showtecsystem.contratos_veiculos as veic', 'veic.placa = cad_veic.placa', 'INNER');
                $this->db->where('eqp.modelo', $modelo->modelo);
                $this->db->where('veic.status', 'ativo');
                $this->db->where('eqp.serial !=', '');
                $modelo->ativos = $this->db->get()->num_rows();
            }
        }

        return $marcas;
    }

    public function atualizaStatus_equip($id_equipamento, $status) {
        $insert = array('status' => $status);
        $this->db->where('id', $id_equipamento);
        $update = $this->db->update('showtecsystem.cad_equipamentos', $insert);

        return $update;
    }

    public function pesquisar_eqp_iscas($where = [], $start = 0, $limit = 10, $camp_ord = 'id', $orde = 'desc', $select = '*', $like = null)
    {
        $this->db->select($select);

        if ($where)
            $this->db->where($where);
        if ($like)
            $this->db->like('serial', 'I', 'after');
            $this->db->like($like);
            $this->db->or_like('serial', 'R', 'after');
            $this->db->like($like);
        $this->db->order_by($camp_ord, $orde);
        $this->db->limit($limit, $start);
        return $this->db->get('showtecsystem.cad_equipamentos')->result();
    }


    /*
     * Função que consulta lista informacoes de equipamentos/veiculos levando em consideracao os dias de sua atividade
     */
    public function getEquipamentosPeloCliente($id_cliente) {
        $query = $this->db->select('eq.id, eq.marca, eq.modelo, eq.serial, veic.placa, veic.prefixo_veiculo,
                                    veic.modelo as modelo_veiculo, eq.modelo as modelo_equipamento, eq.marca as fabricante,
                                    veic.regime_veiculo as tipo_frota, con.valor_mensal, con.valor_instalacao')
            ->join('systems.cadastro_veiculo as veic', 'eq.serial = veic.serial', 'LEFT')
            ->join('showtecsystem.contratos as con', 'con.id = veic.contrato_veiculo', 'LEFT')
            ->where(array('veic.id_usuario' => $id_cliente, 'eq.serial !=' => ''))
            ->get('showtecsystem.cad_equipamentos as eq');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
     * RETORNA LISTA DE INFORMACOES DE MONITORADOS
     */
    public function getMonitoradosPeloCliente($id_cliente) {
        $query = $this->db->select('tzl.placa, tzl.id_contrato, det.id as id_det, det.nome, con.valor_mensal')
            ->join('systems.detentos as det', 'det.id = SUBSTRING(tzl.placa, 4)', 'LEFT')
            ->join('showtecsystem.contratos as con', 'con.id = tzl.id_contrato', 'LEFT')
            ->where(array('tzl.status' => 'ativo', 'det.status' => 2, 'con.tipo_proposta' => 4))
            ->where_in('con.status', array('0','1','2'))
            ->get('showtecsystem.contratos_veiculos as tzl');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
     * RETORNA LISTA DE INFORMACOES DE MONITORADOS
     */
    public function getDetentos($select='*', $where=array()) {
        $query = $this->db->select($select)->where($where)->get('systems.detentos');

        if ($query->num_rows() > 0) return $query->result();
        return false;
    }

    /*
     * Função que consulta lista informacoes de equipamentos/veiculos levando em consideracao os dias de sua atividade
     */
    public function getLogUsoTornozeleiras($ids_detentos) {
        $query = $this->db->select('id_detento, tipo, data_cadastro')
            ->where_in('tipo', array('0','1'))
            ->where_in('id_detento', $ids_detentos )
            ->order_by('id', 'asc')
            ->get('showtecsystem.log_uso_tornozeleiras');

        if ($query->num_rows() > 0) return $query->result();
        return false;
    }


}
