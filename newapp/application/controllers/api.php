<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller
{

    protected $callback;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('fatura');
        $this->load->model('contrato');
        $this->load->model('token');
        $this->load->model('cliente');
        $this->load->model('usuario_gestor');
        $this->load->model('sender');
        $this->load->model('parlacom');
        $this->load->library('email');
    }

    public function exe_gruposMaster()
    {
        pr('Processo inicializado;</br>');
        $duplicados = $this->usuario_gestor->getDuplicadosGrupos();
        
        foreach ($duplicados as $d) {
            $duplicados_cliente = $this->usuario_gestor->getGruposCliente($d->id_cliente);
            $master = current($duplicados_cliente);
            
            $array_usuarios = $array_iscas = $array_placas = [];
            $key = false;
            foreach ($duplicados_cliente as $d_c) {
                $array_usuarios = array_merge($array_usuarios, $this->usuario_gestor->getUsuariosGrupo($d_c['id']));
                $array_placas = array_merge($array_placas, $this->usuario_gestor->getPlacasGrupo($d_c['id']));
                $array_iscas = array_merge($array_iscas, $this->usuario_gestor->getIscasGrupo($d_c['id']));

                if (is_numeric($key) && $d_c['id'] > $key) $this->usuario_gestor->deleteGroup($d_c['id']);
                else $key = $d_c['id'];
            }

            $result_usuarios = array_unique($array_usuarios, SORT_REGULAR);
            $result_iscas = array_unique($array_iscas, SORT_REGULAR);
            $result_placas = array_unique($array_placas, SORT_REGULAR);

            if ( !empty($result_usuarios) ) {
                foreach ($result_usuarios as $u) {
                    if ($master['id'] != $u['id_group'] )
                        $this->usuario_gestor->insertUserMaster($u, $master);
                }
            }

            if ( !empty($result_placas) ) {
                foreach ($result_placas as $u) {
                    if ($master['id'] != $u['groupon'] )
                        $this->usuario_gestor->insertPlacasMaster($u, $master);
                }
            }

            if ( !empty($result_iscas) ) {
                foreach ($result_iscas as $u) {
                    if ($master['id'] != $u['id_group'] )
                        $this->usuario_gestor->insertIscasMaster($u, $master);
                }
            }
        }

        exit('Processo finalizado;');
    }

    public function listar_chips_cliente()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $token = $this->input->post('token');
            $id_cliente = $this->input->post('id_cliente');

            $chips = $this->contrato->listar_chips("id_cliente_sim2m = $id_cliente");
            $cliente = $this->cliente->get(['id' => $id_cliente]);

            if ($cliente) {

                $login = str_replace(
                    array('.', '-', '/'),
                    '',
                    $cliente->cnpj ? $cliente->cnpj : $cliente->cpf
                );

                $user = $this->parlacom->login('simm2m', 'simm2m@2016');

                $servicos = [];

                $vivo = $this->parlacom->servicos($login, '2', $user->session);

                if ($vivo) {

                    foreach ($vivo as $index => $servico) {

                        $saldo = str_replace(',', '', $servico->saldo);
                        $restante = ($saldo * 100) / $servico->mb;
                        $consumido = 100 - $restante;

                        $vivo[$index]->operadora = 'Vivo';
                        $vivo[$index]->consumo = round($consumido, 2);
                        $vivo[$index]->restante = round($restante, 2);
                        $vivo[$index]->status_label = (int) $servico->trafego > 0 ? 'ativo' : 'inativo';
                        $vivo[$index]->status = (int) $servico->trafego > 0 ? 1 : 0;

                        $servicos['vivo'][] = $vivo[$index];
                    }
                }

                $claro = $this->parlacom->servicos($login, '3', $user->session);

                if ($claro) {

                    foreach ($claro as $index => $servico) {

                        $saldo = str_replace(',', '', $servico->saldo);
                        $restante = ($saldo * 100) / $servico->mb;
                        $consumido = 100 - $restante;

                        $claro[$index]->operadora = 'Claro';
                        $claro[$index]->consumo = round($consumido, 2);
                        $claro[$index]->restante = round($restante, 2);
                        $claro[$index]->status_label = (int) $servico->trafego > 0 ? 'ativo' : 'inativo';
                        $claro[$index]->status = (int) $servico->trafego > 0 ? 1 : 0;

                        $servicos['claro'][] = $claro[$index];
                    }
                }

                $vodafone = $this->parlacom->servicos($login, '19', $user->session);
                if ($vodafone) {

                    foreach ($vodafone as $index => $servico) {

                        $saldo = str_replace(',', '', $servico->saldo);
                        $restante = ($saldo * 100) / $servico->mb;
                        $consumido = 100 - $restante;

                        $vodafone[$index]->operadora = 'Vodafone';
                        $vodafone[$index]->consumo = round($consumido, 2);
                        $vodafone[$index]->restante = round($restante, 2);
                        $vodafone[$index]->status_label = (int) $servico->trafego > 0 ? 'ativo' : 'inativo';
                        $vodafone[$index]->status = (int) $servico->trafego > 0 ? 1 : 0;

                        $servicos['vodafone'][] = $vodafone[$index];
                    }
                }

                // $servicos['claro'] = $claro ? $claro : [];
                //
                // $servicos['vodafone'] = $vodafone ? $vodafone : [];


                // $servicos = array_merge($servicosVivo, $servicosClaro, $servicosVodafone);

                json_response($servicos);
                // if (count($servicos)) {
                // }

            }

            json_response('Chips not found', 400);
        }
    }

    public function gerar_fatura_mes()
    {

        if ($this->input->post()) {

            $ano = $this->input->post('ano');
            $id_cliente = $this->input->post('cli');
            $ins = $this->input->post('ins');
            $instru1 = $this->input->post('instrucoes1');
            $mes = $this->input->post('mes');
            $hoje = date("Y-m-d");

            $where = $id_cliente != '' ? array('id_cliente' => $id_cliente) : '';
            $contratos = $this->contrato->listar($where);
            if ($contratos) {
                foreach ($contratos as $contrato) {
                    $vencimento = $ano . '-' . $mes . '-' . $contrato->vencimento;
                    $dia = $mes == '02' && $contrato->vencimento > 28 ? 28 : $contrato->vencimento;
                    $faturas = $this->fatura->listar('id_contrato = ' . $contrato->id . '
							AND (data_vencimento = "' . $ano . '-' . $mes . '-' . $dia . '"
							OR data_vencimento = "' . $contrato->data_prestacao . '"
							OR data_vencimento = "' . $contrato->primeira_mensalidade . '")');
                    // indica quais faturas precisam ser geradas. Ex.: mensalidade, adesão
                    $gera_fatura = array();
                    if ($faturas) {
                        foreach ($faturas as $fatura) {
                            // confirma fatura adesão
                            if ($fatura->data_vencimento != $contrato->data_prestacao) {
                                $gera_fatura[$contrato->id][] = 'adesao';
                            }
                            // confirma fatura mensalidade
                            if ($fatura->data_vencimento != $vencimento) {
                                $gera_fatura[$contrato->id][] = 'mensalidade';
                            }
                            // confirma fatura 1ª mensalidade
                            if ($fatura->data_vencimento != $contrato->primeira_mensalidade) {
                                $gera_fatura[$contrato->id][] = 'first_mensal';
                            }
                        }
                    } else {

                        if ($hoje <= $contrato->primeira_mensalidade && substr($contrato->primeira_mensalidade, 6, -3) == $mes) {
                            $gera_fatura[$contrato->id][] = 'first_mensal';
                        }
                        if (substr($contrato->data_prestacao, 6, -3) == $mes) {
                            $gera_fatura[$contrato->id][] = 'adesao';
                        }
                    }

                    if (count($gera_fatura) > 0) {
                        foreach ($gera_fatura as $contrato => $faturas) {
                            echo $contrato;
                            foreach ($faturas as $fatura) {
                                if ($fatura == 'adesao') {
                                    $vencimento = $contrato->data_prestacao;
                                    $valor_boleto = $contrato->valor_prestacao;
                                }

                                // dados da fatura a ser gerada
                                $dados_db = array(
                                    'descricao' => $descricao,
                                    'id_contrato' => $contrato->id,
                                    'id_cliente' => $contrato->id_cliente,
                                    'data_prestacao' => $vencimento,
                                    'boleto_vencimento' => $vencimento,
                                    'valor_prestacao' => $contrato->data_prestacao,
                                    'valor_instalacao' => $contrato->valor_instalacao,
                                    'quantidade' => $dados_fatura['qtd'],
                                    'valor_unitario' => $valor_unit,
                                    'valor_total' => $valor_total,
                                    'data_emissao' => $hoje,
                                    'boleto' => $contrato->boleto,
                                    'instrucoes1' => $dados['instrucoes1']
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    /*
     * adiciona um usuário ao cliente
    * @params Int $id_cliente
    * @params String $nome
    * @params String $usuario
    * @params String $senha
    * @params String $tipo_usuario
    * @params String $CNPJ_
    * @params Hash	 $token
    * @return json(success: boolean, msg: string)
    */
    public function add_usuario()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $request = $_SERVER['REMOTE_ADDR'];
            $token = $this->input->post('token');
            $usuario = $this->input->post('usuario');
            $senha = $this->input->post('senha');
            $tipo = $this->input->post('tipo_usuario');
            $nome = $this->input->post('nome');
            $cnpj_ = $this->input->post('cnpj_');
            $id_cliente = $this->input->post('id_cliente');

            if ($this->token->validar($token, $request)) {

                $data = array(
                    'nome_usuario' => $nome,
                    'email_usuario' => $usuario,
                    'senha_usuario' => $senha,
                    'status_usuario' => $tipo
                );

                $emails = array($usuario);

                $mensagem = $this->parser->parse('template/emails/new_user_gestor', $data, true);

                $d_bd = array(
                    'id_cliente' => $id_cliente, 'usuario' => $usuario, 'senha' => $senha,
                    'nome_usuario' => $nome, 'tipo_usuario' => $tipo
                );

                try {

                    $this->usuario_gestor->add($d_bd);
                    $this->callback = array('success' => true, 'msg' => 'Usuário cadastrado com sucesso. Uma mensagem
							foi enviada para o email cadastrado com os dados de acesso.');

                    $envio = $this->sender->enviar_email(
                        'suporte@showtecnologia.com',
                        'Show Tecnologia',
                        $emails,
                        'Dados de acesso Show Tecnologia',
                        $mensagem
                    );
                } catch (Exception $e) {

                    $this->callback = array('success' => false, 'msg' => $e->getMessage());
                }
            } else {

                $this->callback = array('success' => false, 'msg' => 'Token inválido!');
            }

            echo json_encode($this->callback);
        }
    }

    /*
     * reset da senha do usuario
    * @params String $email
    * @params Hash	 $token
    * @return json(success: boolean, msg: string)
    */
    public function reset_senha_usuario()
    {

        header('Access-Control-Allow-Origin: *');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $request = $_SERVER['REMOTE_ADDR'];
            $token = $this->input->post('token');
            $email = $this->input->post('email');
            $nova_senha = $this->gerador_senha();

            if ($this->token->validar($token, $request)) {

                $usuario = $this->usuario_gestor->get(array('usuario' => $email));


                if ($usuario->status_usuario == 'ativo') {


                    if (count($usuario) > 0 && $this->usuario_gestor->atualizar_conta(
                        $usuario->code,
                        array('senha' => md5($nova_senha))
                    )) {

                        $data = array(
                            'nome_usuario' => $usuario->nome_usuario,
                            'email_usuario' => $usuario->usuario,
                            'senha_usuario' => $nova_senha
                        );

                        $emails = array($usuario->usuario);
                        $cc = array('suporte@showtecnologia.com');
                        $mensagem = $this->parser->parse('template/emails/reset_senha_user', $data, true);

                        $dados_email = array(
                            'cnpj' => $usuario->CNPJ_,
                            'item_envio' => 'reset_senha',
                            'conteudo_envio' => $mensagem,
                            'assunto_envio' => 'Lembrete de senha',
                            'emails_envio' => implode(',', $emails)
                        );


                        $envio = $this->sender->enviar_email(
                            'suporte@showtecnologia.com',
                            'Show Tecnologia',
                            $emails,
                            'Redefinição de Senha',
                            $mensagem,
                            '',
                            false,
                            false
                        );

                        if ($envio) {
                            $this->callback = array('success' => true, 'msg' => 'Enviamos para o email
											informado um lembrete da sua senha.');
                        } else {
                            $this->callback = array(
                                'success' => false,
                                'msg' => 'Não foi possível enviar o email. Por favor tente novamente.'
                            );
                        }
                    }
                } elseif ($usuario->status_usuario == 'inativo') {
                    $this->callback = array('success' => false, 'msg' => 'Usuário bloqueado. Infelizmente não será possível alterar sua senha.');
                } else {
                    $this->callback = array('success' => false, 'msg' => 'O email informado não é válido');
                }
            } else {
                $this->callback = array('success' => false, 'msg' => 'Token inválido!');
            }
        } else {
            $this->callback = array('success' => false, 'msg' => 'Acesso negado!');
        }

        echo json_encode($this->callback);
    }

    /*
     * reset da senha do usuario
    * @params String $email
    * @params Hash	 $token
    * @return json(success: boolean, msg: string)
    */
    public function reset_senha_usuario_app($token, $email)
    {

        //header('Access-Control-Allow-Origin: *');

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $request = $_SERVER['REMOTE_ADDR'];
            //$token = $this->input->get('token');
            //$email = $this->input->get('email');
            $nova_senha = $this->gerador_senha();


            if ($this->token->validar($token, $request)) {

                $usuario = $this->usuario_gestor->get(array('usuario' => $email));
                if (count($usuario) > 0 && $this->usuario_gestor->atualizar_conta(
                    $usuario->code,
                    array('senha' => md5($nova_senha))
                )) {

                    $data = array(
                        'nome_usuario' => $usuario->nome_usuario,
                        'email_usuario' => $usuario->usuario,
                        'senha_usuario' => $nova_senha
                    );

                    $emails = array($usuario->usuario);

                    $cc = array('suporte@showtecnologia.com');

                    $mensagem = $this->parser->parse('template/emails/reset_senha_user', $data, true);
                    /*
                    $envio = $this->sender->enviar_email('suporte@showtecnologia.com', 'Show Tecnologia', $emails,
                            'Lembrete de senha', $mensagem, $cc);
                    */

                    $dados_email = array(
                        'cnpj' => $usuario->CNPJ_,
                        'item_envio' => 'reset_senha',
                        'conteudo_envio' => $mensagem,
                        'assunto_envio' => 'Lembrete de senha',
                        'emails_envio' => implode(',', $emails)
                    );

                    $this->db->insert('systems.envio_cron', $dados_email);
                    if ($this->db->affected_rows()) {
                        $this->callback = array('success' => true, 'msg' => 'Enviamos para o email
										informado um lembrete da sua senha.');
                    } else {
                        $this->callback = array(
                            'success' => false,
                            'msg' => 'Não foi possível enviar o email. Por favor tente novamente.'
                        );
                    }
                } else {
                    $this->callback = array('success' => false, 'msg' => 'O email informado não é válido');
                }
            } else {
                $this->callback = array('success' => false, 'msg' => 'Token inválido!');
            }
        } else {
            $this->callback = array('success' => false, 'msg' => 'Acesso negado!');
        }

        echo json_encode($this->callback);
    }

    /*
     * login gestor
    * @params String $email
    * @params String $senha
    * @params Hash	 $token
    * @return json(success: boolean, usuario: {id_cliente, id_usuario, nome }, msg: string)
    */
    public function logar_gestor()
    {
        header('Access-Control-Allow-Origin: *');

        $request = $_SERVER['REMOTE_ADDR'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->input->post('lang') == "en") {
                $this->lang->load('en', 'english');
            } else {
                $this->lang->load('pt', 'portuguese');
            }
            $token = $this->input->post('token');
            $email = $this->input->post('email');
            $senha = md5($this->input->post('senha'));

            if ($this->token->validar($token, $request)) {

                $usuario = $this->usuario_gestor->get(array('usuario' => $email, 'senha' => $senha));
                if (count($usuario) > 0) {

                    if ($usuario->status_usuario == 'ativo' || $usuario->status_usuario == 'parcial') {

                        $permissoes = $this->usuario_gestor->permissoes(['codigo' => $usuario->CNPJ_]);

                        if ($permissoes) {
                            $permissoes = $permissoes;
                        } else {
                            $permissoes = $usuario->permissoes;
                        }

                        $this->callback = array(
                            'success' => true, 'usuario' =>
                            array(
                                'id_cliente' => $usuario->id_cliente,
                                'id_usuario' => $usuario->code,
                                'nome_usuario' => $usuario->nome_usuario,
                                'usuario' => $usuario->usuario,
                                'CNPJ_' => $usuario->CNPJ_,
                                'tipo_usuario' => $usuario->tipo_usuario,
                                'status_usuario' => $usuario->status_usuario,
                                'token_usuario' => $usuario->token,
                                'fatura_aberta' => $usuario->fatura_aberta,
                                'permissoes' => $permissoes,
                                'base' => isset($usuario->base) ? $usuario->base : ''
                            )
                        );
                    } elseif ($usuario->status_usuario == 'inativo') {

                        $this->callback = array('success' => false, 'msg' => lang('usuario_bloqueado') . ' 02');
                    }
                } else {

                    $this->callback = array('success' => false, 'msg' => lang('usuario_senha_invalido'));
                }
            } else {

                $this->callback = array('success' => false, 'msg' => lang('token_invalido'));
            }
        } else {

            $this->callback = array('success' => false, 'msg' => lang('acesso_negado'));
        }

        echo json_encode($this->callback);
    }


    /*
     * Listar faturas em aberto e próxima fatura a vencer
    * @params String $token
    * @params String $id_cliente
    * @return Json {success: boolean, faturas: {Array pendentes, Array proximas}
    */
    public function listar_faturas_cliente()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $fats = array();

            $token = $this->input->post('token');
            $id_cliente = $this->input->post('id_cliente');

            $hoje = date('Y-m-d');
            $tax = 0; //$this->cliente->getTaxUs($id_cliente);
            $faturas_aberto = $this->fatura->listar(
                'cad_faturas.numero >= "30000" and
                cad_faturas.id_cliente = "' . $id_cliente . '" and
                cad_faturas.data_vencimento <= "' . $hoje . '" and
                cad_faturas.status != "1" and cad_faturas.status != "3" and cad_faturas.status != "4"',
                0,
                100,
                'cad_faturas.data_vencimento',
                'DESC'
            );

            $prox_fatura = $this->fatura->listar(
                'cad_faturas.numero >= "30000" and
                cad_faturas.id_cliente = "' . $id_cliente . '" and
                cad_faturas.data_vencimento >= "' . $hoje . '" and
                cad_faturas.status != "1" and cad_faturas.status != "3" and cad_faturas.status != "4"',
                0,
                1,
                'cad_faturas.data_vencimento',
                'ASC'
            );

            if ($faturas_aberto) {

                foreach ($faturas_aberto as $fatura) {

                    $atraso = diff_entre_datas($fatura->data_vencimento, $hoje);
                    $numero = '2852865';
                    for ($i = 10 - strlen($fatura->Id); $i > 0; $i--) {
                        $numero .= '0';
                    }
                    $numero .= $fatura->Id;

                    if ($atraso > 0)
                        $link_fatura = 'https://www63.bb.com.br/portalbb/boleto/boletos/hc21e,802,3322,10343.bbx';
                    else
                        $link_fatura = 'https://gestor.showtecnologia.com/sistema/newapp/index.php/cliente/faturas/view_email/' . base64_encode($fatura->numero_fatura);

                    $fats['pendentes'][] = array(
                        'numero' => $fatura->numero_fatura,
                        'data_vencimento' => $fatura->data_vencimento,
                        'valor' => $fatura->valor_total,
                        'atraso' => $atraso, 'link' => $link_fatura, 'status' => $fatura->status, 'nossonumero' => $numero
                    );
                }
            } else { }

            if ($prox_fatura) {

                foreach ($prox_fatura as $fat_prox) {

                    $link_fatura = 'https://gestor.showtecnologia.com/sistema/newapp/index.php/cliente/faturas/view_email/' . base64_encode($fat_prox->numero_fatura);
                    $fats['proximas'][] = array(
                        'numero' => $fat_prox->numero_fatura,
                        'data_vencimento' => $fat_prox->data_vencimento,
                        'valor' => $fat_prox->valor_total,
                        'link' => $link_fatura
                    );
                }
            }

            $this->callback = array('success' => true, 'faturas' => $fats, 'tax' => $tax);
        } else {

            $this->callback = array('success' => false, 'msg' => 'Acesso negado!');
        }

        echo json_encode($this->callback);
    }

    /*
     * logar na conta usuário automaticamente
    * @params String $email
    * @params String $senha
    */
    public function logar_usuario_gestor()
    {

        $request = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $token = $this->input->post('token');
            $email = $this->input->post('email');
            $senha = $this->input->post('senha');

            if ($this->token->validar($token, $request)) {

                $usuario = $this->usuario_gestor->get(array('usuario' => $email, 'senha' => $senha));
                if (isset($usuario) > 0) {

                    $permissoes = $this->usuario_gestor->permissoes(['codigo' => $usuario->CNPJ_]);

                    if ($permissoes) {
                        $permissoes = $permissoes;
                    } else {
                        $permissoes = $usuario->permissoes;
                    }

                    $this->callback = array(
                        'success' => true, 'usuario' =>
                        array(
                            'id_cliente' => $usuario->id_cliente,
                            'id_usuario' => $usuario->code,
                            'nome_usuario' => $usuario->nome_usuario,
                            'usuario' => $usuario->usuario,
                            'CNPJ_' => $usuario->CNPJ_,
                            'tipo_usuario' => $usuario->tipo_usuario,
                            'fatura_aberta' => $usuario->fatura_aberta,
                            'status_usuario' => $usuario->status_usuario,
                            'token_usuario' => $usuario->token,
                            'permissoes' => $permissoes,
                            'base' => isset($usuario->base) ? $usuario->base : ''
                        )
                    );
                } else {

                    $this->callback = array('success' => false, 'msg' => 'Usuário ou senha inválidos!');
                }
            } else {

                $this->callback = array('success' => false, 'msg' => 'Token Inválido!');
            }
        } else {

            $this->callback = array('success' => false, 'msg' => 'Acesso negado!');
        }

        echo json_encode($this->callback);
    }



    private function gerador_senha()
    {

        $nova_senha = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 5)), 0, 10);
        return $nova_senha;
    }

    /*
    * @return int $codigo {0 = cliente inexistente, 1 = fatura em atraso 30 dias, 2 = OK}
    *
    */
    public function validar_bloqueio_uso($cli)
    {

        $this->load->model('cliente');

        //if ($this->input->post()){

        $codigo = 2;
        $hoje = date('Y-m-d');

        $dt_inicio = '2014-02-28';

        $id_cliente = $cli; //$this->input->post('id_cliente');

        $cliente = $this->cliente->get(array('id' => $id_cliente));

        if ($cliente) {

            $faturas = $this->fatura->listar(
                array(
                    'cad_faturas.id_cliente' => $cliente->id,
                    'cad_faturas.data_vencimento <' => $hoje,
                    'cad_faturas.status !=' => 1
                ),
                0,
                1,
                'cad_faturas.data_vencimento',
                'ASC'
            );

            if ($faturas) {

                foreach ($faturas as $fatura);

                if (($fatura->data_vencimento > $dt_inicio) && diff_entre_datas($fatura->data_vencimento, $hoje) > 30)
                    $codigo = 1;
            }
        } else {

            $codigo = 0;
        }

        echo $codigo;


        //}
    }

    public function gateways()
    {
        $this->load->model('gateway');
        $gateways = $this->gateway->get_parados();
        echo json_encode($gateways);
    }

    public function getHtmlElmar($cod_empresa, $chave)
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "http://guarabiranfe.elmarinformatica.com.br/emissor/inc/notas_pesquisar.ajax.php?txtNumeroNota=&txtCodigoVerificacao={$chave}&txtTomadorCPF=&txtTomadorNome=&hdcodempresa={$cod_empresa}&btLimpar=Limpar&hdPagina=1&hdPrimeiro=&&hdNota=1");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
        echo $output;
    }

    public function get_arquivo_remessa()
    {
        /* Header */
        $texto_retorno = "0"; //Identificação do registro header
        $texto_retorno .= "1"; //Identificação do arquivo remessa
        $texto_retorno .= "REMESSA"; //Literal remessa
        $texto_retorno .= "01"; //Código do serviço de cobrança
        $texto_retorno .= "COBRANCA"; //Literal cobrança
        for ($i = 0; $i < 7; $i++) { //Completando string
            $texto_retorno .= " ";
        }
        $texto_retorno .= "17536"; //Código do beneficiário
        $texto_retorno .= "09338999000158"; //CPF/CGC do beneficiário
        for ($i = 0; $i < 31; $i++) { //Filler
            $texto_retorno .= " ";
        }
        $texto_retorno .= "748"; //Número do Sicredi
        $texto_retorno .= "SICREDI"; //Literal Sicredi
        for ($i = 0; $i < 8; $i++) { //Completando string
            $texto_retorno .= " ";
        }
        $texto_retorno .= date('Ymd'); //Data de gravação do arquivo
        for ($i = 0; $i < 8; $i++) { //Filler
            $texto_retorno .= " ";
        }
        $texto_retorno .= "0000001"; //Número da remessa
        for ($i = 0; $i < 273; $i++) { //Filler
            $texto_retorno .= " ";
        }
        $texto_retorno .= "2.00"; //Versão do sistema
        $texto_retorno .= "000001\n"; //Número seqüencial do registro

        /* Header */

        /* Body */

        $faturas = $this->fatura->listar("cad_faturas.data_emissao = '2018-05-02'", 0, 999999, 'data_vencimento', 'ASC');

        foreach ($faturas as $fatura) {
            if ($fatura->informacoes == "NORIO") {
                continue;
            }
            $fatura->valor_total -= $fatura->valor_total * ($fatura->iss / 100);
            $fatura->valor_total -= $fatura->valor_total * ($fatura->pis / 100);
            $fatura->valor_total -= $fatura->valor_total * ($fatura->cofins / 100);
            $fatura->valor_total -= $fatura->valor_total * ($fatura->csll / 100);
            $fatura->valor_total -= $fatura->valor_total * ($fatura->irpj / 100);

            $data['valor_cobrado'] = $fatura->valor_total;
            $data['dias_de_prazo_para_pagamento'] = 5;
            $data['taxa_boleto'] = 0;
            $data['juros_mes'] = 2;
            $data['data_venc'] = $fatura->dataatualizado_fatura == NULL ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura);
            $data['nosso_numero'] = $fatura->numero_fatura;
            $data['data_processamento'] = data_for_humans($fatura->data_emissao);
            $data['valor_boleto'] = $fatura->valor_total;
            $data['sacado'] = $fatura->nome . ' - ' . $fatura->cnpj;
            $data['endereco1'] = $fatura->endereco . ', ' . $fatura->numero . ' - ' . $fatura->bairro;
            $data['endereco2'] = $fatura->cidade . ' / ' . $fatura->uf;
            $data['numero_documento'] = $fatura->numero_fatura;
            $data['data_documento'] = data_for_humans($fatura->data_emissao);
            $data['data_vencimento'] = $fatura->dataatualizado_fatura == NULL ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura);
            $dias_de_prazo_para_pagamento = $data['dias_de_prazo_para_pagamento'];
            $taxa_boleto = $data['taxa_boleto'];
            $juros_mes = $data['juros_mes'];
            $dadosboleto["juros"] = (($data['valor_cobrado'] * $juros_mes) / 100) / 30;

            $data_venc = $data['data_venc']; //date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
            $valor_cobrado = $data['valor_cobrado']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
            $valor_cobrado = str_replace(",", ".", $valor_cobrado);
            $valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');


            $dadosboleto["inicio_nosso_numero"] = date("y");  // Ano da geração do título ex: 07 para 2007
            $dadosboleto["nosso_numero"] = $data['nosso_numero'];
            $dadosboleto["numero_documento"] = $data['numero_documento']; // Num do pedido ou do documento
            $dadosboleto["data_vencimento"] = $data['data_vencimento']; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
            $dadosboleto["data_documento"] = $data['data_documento']; // date("d/m/Y"); // Data de emissão do Boleto
            $dadosboleto["data_processamento"] = $data['data_processamento']; //date("d/m/Y"); // Data de processamento do boleto (opcional)
            $dadosboleto["valor_boleto"] = number_format($data['valor_boleto'] + $taxa_boleto, 2, ',', ''); //$valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
            // DADOS DO SEU CLIENTE
            $dadosboleto["sacado"]    = $data['sacado']; //"Nome do seu Cliente";
            $dadosboleto["endereco1"] = $data['endereco1']; //"Endereço do seu Cliente";
            $dadosboleto["endereco2"] = $data['endereco2']; //"Cidade - Estado -  CEP: 00000-000";

            // INFORMACOES PARA O CLIENTE
            $dadosboleto["demonstrativo1"] = "Pagamento referente fatura: " . $data['numero_documento'];
            $dadosboleto["demonstrativo2"] = "<br>"; //. number_format($taxa_boleto, 2, ',', '');
            $dadosboleto["demonstrativo3"] = "2a. via - http://www.showtecnologia.com/financeiro";

            // INSTRUÇÕES PARA O CAIXA
            $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento e juros de 0,33% ao dia";
            $dadosboleto["instrucoes2"] = "- Receber até " . $dias_de_prazo_para_pagamento . " dias após o vencimento";
            $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: 4020-2472";
            $dadosboleto["instrucoes4"] = "- Email: financeiro@showtecnologia.com";

            // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
            $dadosboleto["quantidade"] = "";
            $dadosboleto["valor_unitario"] = "";
            $dadosboleto["aceite"] = "N";
            $dadosboleto["especie"] = "R$";
            $dadosboleto["especie_doc"] = "DM";


            // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


            // DADOS DA SUA CONTA - SICREDI
            $dadosboleto["agencia"] = "2201";   // Num da agencia (4 digitos), sem Digito Verificador
            $dadosboleto["conta"] = "17536";  // Num da conta (5 digitos), sem Digito Verificador
            $dadosboleto["conta_dv"] = "6";   // Digito Verificador do Num da conta

            // DADOS PERSONALIZADOS - SICREDI
            $dadosboleto["posto"] = "01";      // Código do posto da cooperativa de crédito
            $dadosboleto["byte_idt"] = "2";    // Byte de identificação do cedente do bloqueto utilizado para compor o nosso número.
            // 1 - Idtf emitente: Cooperativa | 2 a 9 - Idtf emitente: Cedente
            $dadosboleto["carteira"] = "A";   // Código da Carteira: A (Simples)

            // SEUS DADOS
            $dadosboleto["identificacao"] = "SHOW PRESTADORA DE SERVICOS DO BRASIL LTDA";
            $dadosboleto["cpf_cnpj"] = "09.338.999/0001-58";
            $dadosboleto["endereco"] = "Av. Ruy Barbosa, 104 - Centro";
            $dadosboleto["cidade_uf"] = "Guarabira - PB - CEP: 58.200-000";
            $dadosboleto["cedente"] = "SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - CNPJ/CPF 09.338.999/0001-58 Av. Rui Barbosa, 104 - Centro - Guarabira - PB";

            $object = $this->boleto_sicredi($data);
            $this->load->helper("include/funcoes_sicredi.php");

            $texto_retorno .= "1"; //Identificação do registro detalhe. 001 a 001
            $texto_retorno .= "A"; //Tipo de cobrança. 002 a 002
            $texto_retorno .= "A"; //Tipo de carteira. 003 a 003
            $texto_retorno .= "A"; //Tipo de Impressão. 004 a 004
            for ($i = 0; $i < 12; $i++) { //Filler. 005 a 016
                $texto_retorno .= " ";
            }
            $texto_retorno .= "A"; //Tipo de moeda. 017 a 017
            $texto_retorno .= "A"; //Tipo de desconto. 018 a 018
            $texto_retorno .= "A"; //Tipo de juros. 019 a 019
            for ($i = 0; $i < 28; $i++) { //Filler. 020 a 047
                $texto_retorno .= " ";
            }
            //var_dump($dadosboleto["nosso_numero"]);die;
            $texto_retorno .= start_boleto($dadosboleto); //date("y").'34'.$object['nosso_numero']; //Nosso número Sicredi. 048 a 056
            for ($i = 0; $i < 6; $i++) { //Filler. 057 a 062
                $texto_retorno .= " ";
            }
            $texto_retorno .= date("Ymd"); //Data da Instrução. 063 a 070
            $texto_retorno .= " "; //Campo alterado, quando instrução “31”. 071 a 071
            $texto_retorno .= "N"; //Postagem do título. 072 a 072
            for ($i = 0; $i < 1; $i++) { //Filler. 073 a 073
                $texto_retorno .= " ";
            }
            $texto_retorno .= "B"; //Emissão do boleto. 074 a 074
            $texto_retorno .= "00"; //Número da parcela do carnê. 075 a 076
            $texto_retorno .= "00"; //Número total de parcelas do carnê. 077 a 078
            for ($i = 0; $i < 4; $i++) { //Filler. 079 a 082
                $texto_retorno .= " ";
            }
            $texto_retorno .= "0000000000"; //Valor de desconto por dia de antecipação. 083 a 092
            $texto_retorno .= "    "; //% multa por pagamento em atraso. 093 a 096
            for ($i = 0; $i < 12; $i++) { //Filler. 097 a 108
                $texto_retorno .= " ";
            }
            $texto_retorno .= "01"; //Instrução. 109 a 110

            for ($i = strlen($object['nosso_numero']); $i < 10; $i++) { //Seu número. 111 a 120
                $texto_retorno .= "0";
            }
            $texto_retorno .= $object['nosso_numero']; //Seu número. 111 a 120
            $vencimento = explode('-', $fatura->data_vencimento);
            $texto_retorno .= $vencimento[2] . $vencimento[1] . $vencimento[0][2] . $vencimento[0][3]; //Data de vencimento. 121 a 126

            $valor = str_replace(",", "", str_replace(".", "", $object['valor_boleto']));

            for ($i = strlen($valor); $i < 13; $i++) { //Valor do título. 127 a 139
                $texto_retorno .= "0";
            }
            $texto_retorno .= $valor; //Valor do título. 127 a 139

            for ($i = 0; $i < 9; $i++) { //Filler. 140 a 148
                $texto_retorno .= " ";
            }
            $emissao = explode('-', $fatura->data_emissao);
            $texto_retorno .= "A"; //Espécie de documento. 149 a 149
            $texto_retorno .= "S"; //Aceite do título. 150 a 150
            $texto_retorno .= $emissao[2] . $emissao[1] . $emissao[0][2] . $emissao[0][3]; //Data de emissão. 151 a 156
            $texto_retorno .= "00"; //Instrução de protesto automático  . 157 a 158
            $texto_retorno .= "05"; //Número de dias p/protesto automático. 159 a 160
            for ($i = 0; $i < 13; $i++) { //Valor/% de juros por dia de atraso. 161 a 173
                $texto_retorno .= "0";
            }
            $texto_retorno .= $vencimento[2] . $vencimento[1] . $vencimento[0][2] . $vencimento[0][3]; //Data limite p/concessão de desconto. 174 a 179
            for ($i = 0; $i < 13; $i++) { //Valor/% do desconto. 180 a 192
                $texto_retorno .= "0";
            }
            for ($i = 0; $i < 13; $i++) { //Filler. 193 a 205
                $texto_retorno .= "0";
            }
            for ($i = 0; $i < 13; $i++) { //Valor do abatimento. 206 a 218
                $texto_retorno .= "0";
            }
            $texto_retorno .= "2"; //Tipo de pessoa do pagador: PF ou PJ. 219 a 219
            for ($i = 0; $i < 1; $i++) { //Filler. 220 a 220
                $texto_retorno .= "0";
            }
            if (isset($fatura->cpf)) {
                $texto_retorno .=  "000" . str_replace("-", "", str_replace(".", "", $fatura->cpf)); //CPF/CNPJ do Pagador. 221 a 234
            } else {
                $texto_retorno .=  str_replace("/", "", str_replace("-", "", str_replace(".", "", $fatura->cnpj))); //CPF/CNPJ do Pagador. 221 a 234
            }

            for ($i = strlen($fatura->nome); $i < 40; $i++) {
                $texto_retorno .= " ";
            }

            $texto_retorno .= substr(strtoupper($this->sanitizeString($fatura->nome)), 0, 40); //Nome do pagador. 235 a 274

            for ($i = strlen($fatura->endereco); $i < 40; $i++) { //Endereço do pagador. 275 a 314
                $texto_retorno .= " ";
            }
            $texto_retorno .=  substr(strtoupper($this->sanitizeString($fatura->endereco)), 0, 40); //Endereço do pagador. 275 a 314

            for ($i = 0; $i < 5; $i++) { //Código do pagador na cooperativa beneficiário. 315 a 319
                $texto_retorno .= "0";
            }
            for ($i = 0; $i < 6; $i++) { //Filler. 320 a 325
                $texto_retorno .= "0";
            }
            for ($i = 0; $i < 1; $i++) { //Filler. 326 a 326
                $texto_retorno .= " ";
            }
            $texto_retorno .= str_replace("-", "", $fatura->cep); //CEP do pagador. 327 a 334
            for ($i = 0; $i < 5; $i++) { //Código do Pagador junto ao cliente. 335 a 339
                $texto_retorno .= "0";
            }
            for ($i = 0; $i < 14; $i++) { //CPF/CNPJ do Sacador Avalista. 340 a 353
                $texto_retorno .= " ";
            }
            for ($i = 0; $i < 41; $i++) { //Nome do Sacador Avalista. 354 a 394
                $texto_retorno .= " ";
            }
            $texto_retorno .= "000002"; //Número sequencial do registro. 395 a 400
            $texto_retorno .= "\n"; //Fim da linha
            //var_dump($fatura);var_dump($object);die;
        }
        /* Body */
        echo $texto_retorno;
    }
    function boleto_sicredi($dados)
    {
        // DADOS DO BOLETO PARA O SEU CLIENTE
        $dias_de_prazo_para_pagamento = $dados['dias_de_prazo_para_pagamento'];
        $taxa_boleto = $dados['taxa_boleto'];
        $juros_mes = $dados['juros_mes'];
        $dadosboleto["juros"] = (($dados['valor_cobrado'] * $juros_mes) / 100) / 30;

        $data_venc = $dados['data_venc']; //date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
        $valor_cobrado = $dados['valor_cobrado']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".", $valor_cobrado);
        $valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');


        $dadosboleto["inicio_nosso_numero"] = date("y");  // Ano da geração do título ex: 07 para 2007
        $dadosboleto["nosso_numero"] = $dados['nosso_numero'];
        $dadosboleto["numero_documento"] = $dados['numero_documento']; // Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $dados['data_vencimento']; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = $dados['data_documento']; // date("d/m/Y"); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = $dados['data_processamento']; //date("d/m/Y"); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = number_format($dados['valor_boleto'] + $taxa_boleto, 2, ',', ''); //$valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
        // DADOS DO SEU CLIENTE
        $dadosboleto["sacado"]    = $dados['sacado']; //"Nome do seu Cliente";
        $dadosboleto["endereco1"] = $dados['endereco1']; //"Endereço do seu Cliente";
        $dadosboleto["endereco2"] = $dados['endereco2']; //"Cidade - Estado -  CEP: 00000-000";

        // INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo1"] = "Pagamento referente fatura: " . $dados['numero_documento'];
        $dadosboleto["demonstrativo2"] = "<br>"; //. number_format($taxa_boleto, 2, ',', '');
        $dadosboleto["demonstrativo3"] = "2a. via - http://www.showtecnologia.com/financeiro";

        // INSTRUÇÕES PARA O CAIXA
        $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento e juros de 0,33% ao dia";
        $dadosboleto["instrucoes2"] = "- Receber até " . $dias_de_prazo_para_pagamento . " dias após o vencimento";
        $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: 4020-2472";
        $dadosboleto["instrucoes4"] = "- Email: financeiro@showtecnologia.com";

        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "N";
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "DM";


        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


        // DADOS DA SUA CONTA - SICREDI
        $dadosboleto["agencia"] = "2201";   // Num da agencia (4 digitos), sem Digito Verificador
        $dadosboleto["conta"] = "17536";  // Num da conta (5 digitos), sem Digito Verificador
        $dadosboleto["conta_dv"] = "6";   // Digito Verificador do Num da conta

        // DADOS PERSONALIZADOS - SICREDI
        $dadosboleto["posto"] = "01";      // Código do posto da cooperativa de crédito
        $dadosboleto["byte_idt"] = "2";    // Byte de identificação do cedente do bloqueto utilizado para compor o nosso número.
        // 1 - Idtf emitente: Cooperativa | 2 a 9 - Idtf emitente: Cedente
        $dadosboleto["carteira"] = "A";   // Código da Carteira: A (Simples)

        // SEUS DADOS
        $dadosboleto["identificacao"] = "SHOW PRESTADORA DE SERVICOS DO BRASIL LTDA";
        $dadosboleto["cpf_cnpj"] = "09.338.999/0001-58";
        $dadosboleto["endereco"] = "Av. Ruy Barbosa, 104 - Centro";
        $dadosboleto["cidade_uf"] = "Guarabira - PB - CEP: 58.200-000";
        $dadosboleto["cedente"] = "SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - CNPJ/CPF 09.338.999/0001-58 Av. Rui Barbosa, 104 - Centro - Guarabira - PB";
        return $dadosboleto;
    }
    function sanitizeString($str)
    {
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        $str = preg_replace('/[^a-z0-9]/i', '_', $str);
        $str = preg_replace('/_+/', ' ', $str); // ideia do Bacco :)
        return $str;
    }
    public function arquivo_remessa_bb()
    {
        $quantidade_linha = 0;
        /***********************************************************************************************/
        /* Header do arquivo */
        $texto_retorno = "001"; //Código do Banco na Compensação  1 3 3
        $texto_retorno .= "0000"; //Lote de Serviço  4 7 4
        $texto_retorno .= "0"; //Tipo de Registro  8 8 1
        for ($i = 0; $i < 9; $i++) { //Uso Exclusivo FEBRABAN/CNAB 9 17 9
            $texto_retorno .= " ";
        }
        $texto_retorno .= "2"; //CNPJ 18 18 1
        $texto_retorno .= "09338999000158"; //CNPJ 19 32 14
        //$texto_retorno .= "09338999000158123456"; //Código do Convênio no Banco 33 52 20
        $texto_retorno .= "000164544"; //Convênio de pagamento  33 41 9
        $texto_retorno .= "0126"; //Código 42 45 4
        for ($i = 0; $i < 5; $i++) { //Uso Reservado do Banco  46 50 5
            $texto_retorno .= " ";
        }
        for ($i = 0; $i < 2; $i++) { //Arquivo de teste 51 52 2
            $texto_retorno .= " ";
        }
        $texto_retorno .= "00200"; //Agência Mantenedora da Conta 53 57 5
        $texto_retorno .= "3"; //Dígito Verificador da Agência 58 58 1
        $texto_retorno .= "000000039321"; //Número da Conta 59 70 12
        $texto_retorno .= "5"; //Dígito Verificador da conta  71 71 1
        $texto_retorno .= "0"; //Dígito Verificador da Ag/Conta  72 72 1
        $texto_retorno .= "NORIO MOMOI ME                "; //Número da Empresa 73 102 30
        $texto_retorno .= "BANCO DO BRASIL S/A           "; //Nome do Banco  103 132 30
        for ($i = 0; $i < 10; $i++) { //Uso Exclusivo FEBRABAN/CNAB 133 142 10
            $texto_retorno .= " ";
        }
        $texto_retorno .= "1"; //Código Remessa / Retorno 143 143 1
        $texto_retorno .= date('dmY'); //Data de Geração do Arquivo DDMMAAAA 144 151 8
        $texto_retorno .= "000000"; //Hora da Geração do Arquivo HHMMSS 152 157 6
        $texto_retorno .= "000001"; //Número Sequencial do Arquivo 158 163 6
        $texto_retorno .= "050"; //Nº Versão do Layout do Arquivo  164 166 3
        //$texto_retorno .= "     "; //Densidade de Gravação do Arquivo  167 171 5
        for ($i = 0; $i < 74; $i++) { //Para Uso Reservado do Banco  172 191 19
            $texto_retorno .= " ";
        }
        /*for ($i = 0; $i < 32; $i++) { //Para Uso Reservado da Empresa  192 211 20 + 12(faltando na documentação)
            $texto_retorno .= " ";
        }
        for ($i = 0; $i < 3; $i++) { //Identificação cobrança sem papel 223 225 3
            $texto_retorno .= " ";
        }
        $texto_retorno .= "000"; //Uso exclusivo das VANS 226 228 3
        $texto_retorno .= "00"; //Tipo de Serviço 229 230 2
        $texto_retorno .= "0000000000\n"; //Códigos de Ocorrências 231 240 10*/
        $texto_retorno .= "\n";
        /* Header do arquivo */
        /***********************************************************************************************/

        /* Header do lote */
        $texto_retorno .= "001"; //Código do Banco na Compensação 1 3
        $texto_retorno .= "0001"; //Lote de Serviço 4 7
        $texto_retorno .= "1"; //Tipo de Registro 8 8
        $texto_retorno .= "C"; //Tipo da Operação 9 9
        $texto_retorno .= "30"; //Tipo do Serviço 10 11
        $texto_retorno .= "01"; //Forma de Lançamento 12 13
        $texto_retorno .= "031"; //Nº da Versão do Layout do Lote 14 16 3
        $texto_retorno .= " "; //Uso Exclusivo da FEBRABAN/CNAB 17 17
        $texto_retorno .= "2"; //Tipo de Inscrição da Empresa 18 18
        $texto_retorno .= "21698912000159"; //Número de Inscrição da Empresa 19 32
        $texto_retorno .= "000160992"; //Nº do Convênio 33 41
        $texto_retorno .= "0126"; //Código 42 45
        $texto_retorno .= "     "; //Uso Reservado do Banco 46 50
        $texto_retorno .= "  "; //Número da Conta Corrente 59 70
        $texto_retorno .= "00200";
        $texto_retorno .= "3";
        $texto_retorno .= "000000039321";
        $texto_retorno .= "5";
        $texto_retorno .= "0";
        $texto_retorno .= "NORIO MOMOI ME                ";
        $texto_retorno .= "                                        ";
        $texto_retorno .= "                              ";
        $texto_retorno .= "     ";
        $texto_retorno .= "               ";
        $texto_retorno .= "                    ";
        $texto_retorno .= "     ";
        $texto_retorno .= "   ";
        $texto_retorno .= "  ";
        $texto_retorno .= "        ";
        $texto_retorno .= "V.PAG10136\n";
        /***********************************************************************************************/
        $cnpj_cpf = str_replace("/", "", str_replace("-", "", str_replace(".", "", "01036267431")));
        $cnpj_cpf_14 = "";
        for ($i = 14 - strlen($cnpj_cpf); $i > 0; $i--) {
            $cnpj_cpf_14 .= "0";
        }
        $cnpj_cpf_14 .= $cnpj_cpf;
        $nome = substr("EDUARDO LEITE CRUZ LACET", 0, 30);
        for ($i = strlen($nome); $i < 30; $i++) {
            $nome .= " ";
        }
        $pf_pj = "1";
        $banco = "001";

        $ag = "200";
        $ag_5 = "";
        for ($i = 5 - strlen($ag); $i > 0; $i--) {
            $ag_5 .= "0";
        }
        $ag_5 .= $ag;

        $conta = "10933";
        $conta_12 = "";
        for ($i = 12 - strlen($conta); $i > 0; $i--) {
            $conta_12 .= "0";
        }
        $conta_12 .= $conta;

        $digito_corrente = "9";
        $digito_agencia = "3";
        $data_pagamento = "09052018";

        $valor = "1000";
        $valor_15 = "";
        for ($i = 15 - strlen($valor); $i > 0; $i--) {
            $valor_15 .= "0";
        }

        $valor_15 .= $valor;
        /* Header do lote */
        $texto_retorno .= "001"; //Código no Banco da Compensação 1 3 3
        $texto_retorno .= "0001"; //Lote de Serviço 4 7 4
        $texto_retorno .= "3"; //Tipo de Registro 8 8 1
        $texto_retorno .= "00001"; //Nº Seqüencial do Registro no Lote 9 13 5
        $texto_retorno .= "A"; //Código de Segmento no Reg. Detalhe 14 14 1
        $texto_retorno .= "0"; //Tipo de Movimento 15 15 1
        $texto_retorno .= "00"; //Código da Instrução p/ Movimento 16 17 2
        $texto_retorno .= "000"; //Código da Câmara Centralizadora 18 20 3
        $texto_retorno .= $banco; //Código do Banco Favorecido 21 23 3
        $texto_retorno .= $ag_5; //Ag. Mantenedora da Conta Favorec. 24 28 5
        $texto_retorno .= $digito_agencia; //Dígito Verificador da Agência 29 29 1
        $texto_retorno .= $conta_12; //Número da Conta Corrente 30 41 12
        $texto_retorno .= $digito_corrente; //Dígito Verificador da Conta Corren. 42 42 1
        $texto_retorno .= " "; //Dígito Verificador Agência/Conta 43 43 1
        $texto_retorno .= $nome;
        $texto_retorno .= "00000000000000000001"; //N° do Docto Atribuído pela Empresa 74 93 20
        $texto_retorno .= $data_pagamento; //Data do Pagamento 94 101 8
        $texto_retorno .= "BRL"; //Tipo da Moeda 102 104 3
        $texto_retorno .= "000000000000000"; //Quantidade da Moeda 105 119 10
        $texto_retorno .= $valor_15; //Valor do Pagamento 120 134 13 2
        for ($i = 0; $i < 20; $i++) { //N° do Docto Atribuído pelo Banco 135 154 20
            $texto_retorno .= " ";
        }
        $texto_retorno .= $data_pagamento; //Data Real da Efetivação do Pagto 155 162 8
        $texto_retorno .= $valor_15; //Valor Real da Efetivação do Pagto 163 177 13
        $texto_retorno .= "                                        "; //Outras Informações 178 217 40
        $texto_retorno .= "  "; //Compl. Tipo Serviço 218 219 2
        $texto_retorno .= "     "; //Código Finalidade da TED 220 224 5
        $texto_retorno .= "  "; //Complemente de Finalidade Pagto 225 226 2
        $texto_retorno .= "   "; //Uso Exclusivo Febraban 227 229 3
        $texto_retorno .= "0"; //Aviso ao Fornecedor 230 230 1
        $texto_retorno .= "          \n"; //Código das Ocorrências p/ Retorno 231 240 10

        $texto_retorno .= "001";
        $texto_retorno .= "0001";
        $texto_retorno .= "3";
        $texto_retorno .= "00001";
        $texto_retorno .= "B";
        $texto_retorno .= "   ";
        $texto_retorno .= $pf_pj;
        $texto_retorno .= $cnpj_cpf_14;
        $texto_retorno .= "                                                                                               ";
        $texto_retorno .= $data_pagamento;
        $texto_retorno .= $valor_15;
        $texto_retorno .= "                                                                                          \n";


        $texto_retorno .= "001";
        $texto_retorno .= "0001";
        $texto_retorno .= "5";
        $texto_retorno .= "         ";
        $texto_retorno .= "000001";
        $texto_retorno .= "0" . $valor_15 . "00";
        $texto_retorno .= "                  "; //
        $texto_retorno .= "      "; //Número Aviso de Débito 60 65
        $texto_retorno .= "                                                                                                                                                                     "; //Uso Exclusivo FEBRABAN/CNAB 66 230
        $texto_retorno .= "          \n"; //Códigos das Ocorrências para Retorno 231 240 10

        /***********************************************************************************************/
        $cnpj_cpf = str_replace("/", "", str_replace("-", "", str_replace(".", "", "01036267431")));
        $cnpj_cpf_14 = "";
        for ($i = 14 - strlen($cnpj_cpf); $i > 0; $i--) {
            $cnpj_cpf_14 .= "0";
        }
        $cnpj_cpf_14 .= $cnpj_cpf;
        $nome = substr("EDUARDO LEITE CRUZ LACET", 0, 30);
        for ($i = strlen($nome); $i < 30; $i++) {
            $nome .= " ";
        }
        $pf_pj = "1";
        $banco = "001";

        $ag = "200";
        $ag_5 = "";
        for ($i = 5 - strlen($ag); $i > 0; $i--) {
            $ag_5 .= "0";
        }
        $ag_5 .= $ag;

        $conta = "10933";
        $conta_12 = "";
        for ($i = 12 - strlen($conta); $i > 0; $i--) {
            $conta_12 .= "0";
        }
        $conta_12 .= $conta;

        $digito_corrente = "9";
        $digito_agencia = "3";
        $data_pagamento = "09052018";

        $valor = "1000";
        $valor_15 = "";
        for ($i = 15 - strlen($valor); $i > 0; $i--) {
            $valor_15 .= "0";
        }

        $valor_15 .= $valor;
        /* Header do lote */
        $texto_retorno .= "001"; //Código no Banco da Compensação 1 3 3
        $texto_retorno .= "0001"; //Lote de Serviço 4 7 4
        $texto_retorno .= "3"; //Tipo de Registro 8 8 1
        $texto_retorno .= "00001"; //Nº Seqüencial do Registro no Lote 9 13 5
        $texto_retorno .= "A"; //Código de Segmento no Reg. Detalhe 14 14 1
        $texto_retorno .= "0"; //Tipo de Movimento 15 15 1
        $texto_retorno .= "00"; //Código da Instrução p/ Movimento 16 17 2
        $texto_retorno .= "000"; //Código da Câmara Centralizadora 18 20 3
        $texto_retorno .= $banco; //Código do Banco Favorecido 21 23 3
        $texto_retorno .= $ag_5; //Ag. Mantenedora da Conta Favorec. 24 28 5
        $texto_retorno .= $digito_agencia; //Dígito Verificador da Agência 29 29 1
        $texto_retorno .= $conta_12; //Número da Conta Corrente 30 41 12
        $texto_retorno .= $digito_corrente; //Dígito Verificador da Conta Corren. 42 42 1
        $texto_retorno .= " "; //Dígito Verificador Agência/Conta 43 43 1
        $texto_retorno .= $nome;
        $texto_retorno .= "00000000000000000001"; //N° do Docto Atribuído pela Empresa 74 93 20
        $texto_retorno .= $data_pagamento; //Data do Pagamento 94 101 8
        $texto_retorno .= "BRL"; //Tipo da Moeda 102 104 3
        $texto_retorno .= "000000000000000"; //Quantidade da Moeda 105 119 10
        $texto_retorno .= $valor_15; //Valor do Pagamento 120 134 13 2
        for ($i = 0; $i < 20; $i++) { //N° do Docto Atribuído pelo Banco 135 154 20
            $texto_retorno .= " ";
        }
        $texto_retorno .= $data_pagamento; //Data Real da Efetivação do Pagto 155 162 8
        $texto_retorno .= $valor_15; //Valor Real da Efetivação do Pagto 163 177 13
        $texto_retorno .= "                                        "; //Outras Informações 178 217 40
        $texto_retorno .= "  "; //Compl. Tipo Serviço 218 219 2
        $texto_retorno .= "     "; //Código Finalidade da TED 220 224 5
        $texto_retorno .= "  "; //Complemente de Finalidade Pagto 225 226 2
        $texto_retorno .= "   "; //Uso Exclusivo Febraban 227 229 3
        $texto_retorno .= "0"; //Aviso ao Fornecedor 230 230 1
        $texto_retorno .= "          \n"; //Código das Ocorrências p/ Retorno 231 240 10

        $texto_retorno .= "001";
        $texto_retorno .= "0001";
        $texto_retorno .= "3";
        $texto_retorno .= "00001";
        $texto_retorno .= "B";
        $texto_retorno .= "   ";
        $texto_retorno .= $pf_pj;
        $texto_retorno .= $cnpj_cpf_14;
        $texto_retorno .= "                                                                                               ";
        $texto_retorno .= $data_pagamento;
        $texto_retorno .= $valor_15;
        $texto_retorno .= "                                                                                          \n";
        $quantidade_linha += 2;

        $texto_retorno .= "001";
        $texto_retorno .= "0001";
        $texto_retorno .= "5";
        $texto_retorno .= "         ";
        $texto_retorno .= "00000" . $quantidade_linha;
        $texto_retorno .= "000" . $valor_15;
        $texto_retorno .= "                  "; //
        $texto_retorno .= "      "; //Número Aviso de Débito 60 65
        $texto_retorno .= "                                                                                                                                                                     "; //Uso Exclusivo FEBRABAN/CNAB 66 230
        $texto_retorno .= "          \n"; //Códigos das Ocorrências para Retorno 231 240 10

        /***********************************************************************************************/

        $texto_retorno .= "001"; //Código do Banco na Compensação 1 3
        $texto_retorno .= "9999"; //Lote de Serviço 4 7
        $texto_retorno .= "9"; //Tipo de Registro 8 8
        $texto_retorno .= "         "; //Uso Exclusivo BANCO 9 17
        $texto_retorno .= "000001"; //Quantidade de Lotes do Arquivo 18 23
        $texto_retorno .= "000006"; //Quantidade de Registros do Arquivo 24 29
        for ($i = 0; $i < 211; $i++) { //Qtde de Contas p/ Conc. (Lotes) 30 35 Uso Exclusivo BANCO 36 240 205
            $texto_retorno .= " ";
        }
        echo $texto_retorno . "\n";
    }
    public function get_remessa_salario()
    {
        $this->load->helper('remessa');
        $this->load->model('conta');
        $contas = $this->conta->get_contas($this->input->post('id_contas_instaladores'));
        if (count($contas)) {
            $this->load->helper('file');

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            for ($i = 6 - strlen($contador); $i > 0; $i--) {
                $n_arquivo .= "0";
            }
            $n_arquivo .= $contador;
            if ($this->input->get('empresa') == 1)
                $remessa = remessa_salario($contas, $contador, '09338999000158', '2', '164544', '200', '3', "28629", 'X', 'SHOW PRESTADORA DE SERVICOS DO');
            else
                $remessa = remessa_salario($contas, $contador);

            $file = 'uploads/remessas/PAG_160992_' . $n_arquivo . '.rem';
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa($contas, date('Y-m-d H:i:s'), $file);
                $this->load->helper('download');
                //force_download('PAG_160992_'.$n_arquivo.'.rem', $remessa);
                echo json_encode(array('nome' => 'PAG_160992_' . $n_arquivo . '.rem', 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }

        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }
    public function get_remessa_instaladores()
    {
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');

        $idInst = $this->load->model('id_contas_instaladores');
        $idTed = $this->load->model('descontar_id_contas_instaladores');


        $contas = $this->conta->get_contas_instaladores($this->input->post('id_contas_instaladores'));
        if (count($contas)) {
            if ($this->input->post('descontar_id_contas_instaladores')) {
                foreach ($this->input->post('descontar_id_contas_instaladores') as $descontar) {
                    $desconto[$descontar] = true;
                }
                foreach ($contas['ted'] as $conta) {
                    if (isset($desconto[intval($conta->conta_id)])) {
                        $conta->valor = number_format(floatval($conta->valor) - 9.7, 2, '.', '');
                    }
                }
            }
            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;

            if ($this->input->get('empresa') == 1)
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_tecnico'), '09338999000158');
            else
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_tecnico'), '21698912000159', '2', '160992', '200', '3', '39321', '5', 'NORIO MOMOI ME');

            $file = "uploads/remessas/PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa_instaladores($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }
    public function get_remessa_fornecedores()
    {
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');
        $contas = $this->conta->get_contas_fornecedores($this->input->post('id_contas_fornecedores'));
        if (count($contas)) {
            if ($this->input->post('descontar_id_contas_fornecedores')) {
                foreach ($this->input->post('descontar_id_contas_fornecedores') as $descontar) {
                    $desconto[$descontar] = true;
                }
                foreach ($contas['ted'] as $conta) {
                    if (isset($desconto[intval($conta->conta_id)])) {
                        $conta->valor = number_format(floatval($conta->valor) - 9.7, 2, '.', '');
                    }
                }
            }

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;

            if ($this->input->get('empresa') == 1)
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_fornecedor'), '09338999000158');
            else
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_fornecedor'), '21698912000159', '2', '160992', '200', '3', '39321', '5', 'NORIO MOMOI ME');

            $file = "uploads/remessas/PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa_instaladores($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }

    public function get_salarios_pendentes()
    {
        $this->load->model('conta');
        echo json_encode($this->conta->get_contas_salarios_pendentes($this->input->get('empresa')));
    }
    public function get_instaladores_pendentes()
    {
        $this->load->model('conta');
        echo json_encode($this->conta->get_contas_instaladores_pendentes($this->input->get('empresa')));
    }
    public function get_fornecedores_pendentes()
    {
        $this->load->model('conta');
        echo json_encode($this->conta->get_contas_fornecedore_pendentes($this->input->get('empresa')));
    }
    public function get_titulo_pendentes()
    {
        $this->load->model('conta');
        echo json_encode($this->conta->get_contas_titulo_pendentes($this->input->get('empresa')));
    }
    public function get_guia_pendentes()
    {
        $this->load->model('conta');
        echo json_encode($this->conta->get_contas_guia_pendentes($this->input->get('empresa')));
    }
    public function get_remessa_erros()
    {
        $this->load->model('conta');
        echo json_encode($this->conta->get_remessas_erros($this->input->get('empresa')));
    }

    public function boleto_bb($fatura)
    {
        $this->load->helper('file');
        $this->load->library('ftp');
        $file = "uploads/faturas/fatura_" . $fatura . ".pdf";

        if (!file_exists($file)) {

            $data = $this->input->post();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://mpag.bb.com.br/site/mpag/");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                http_build_query($data)
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            // receive server response ...
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1800);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //nao usar essa linha de codigo em produção
            //$erro = curl_error($ch);
            $server_output = curl_exec($ch);
            //echo $server_output;
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpcode == 403) {
                echo json_encode(array('sucess' => false, 'msg' => 'Erro de autenticação com a api externa.'));
                die;
            }

            if (strstr(htmlentities($server_output), 'C008-000') || $httpcode == 500) {
                $data['tpPagamento'] = '21';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://mpag.bb.com.br/site/mpag/");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt(
                    $ch,
                    CURLOPT_POSTFIELDS,
                    http_build_query($data)
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

                // receive server response ...
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1800);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     //nao usar essa linha de codigo em produção
                $server_output = curl_exec($ch);
                //$erro = curl_error($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if (strstr(htmlentities($server_output), 'C009-000') || $httpcode == 500) {
                    echo "Erro ao imprimir fatura #" . $fatura;
                    die;
                }
            }

            $matches = array();
            $msnRetorno = strip_tags(trim(str_replace('\'', '"', htmlspecialchars($server_output))));

            if (!$server_output || strpos($msnRetorno, 'font face=&quot;arial&quot; color=&quot;red&quot;&gt;') !== false) {
                //SE DEU ERRO NA GERACAO POR CAUSA DE DADOS INVALIDOS
                preg_match_all("/(font face=&quot;arial&quot; color=&quot;red&quot;&gt;)(.*)(&lt;\/font)/", $msnRetorno, $matches);
                if (count($matches)>0) {
                    echo json_encode(array('success' => false, 'msg' => $matches[2] ? $matches[2][0] : 'Erro de autenticação com a api externa.' ));
                }
            }else {
                //SE NAO RETORNOU MENSAGEM DE ERRO, SALVA O PDF E RETORNA SEU CAMINHO
                write_file($file, $server_output);
                echo json_encode(array('success' => true, 'file' => $file ));
            }

        //SE O PDF JA EXISTE
        }else {
            //RETORNA SEU CAMINHO
            echo json_encode(array('success' => true, 'file' => $file ));
        }
    }

    public function get_remessa_boleto()
    {
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');
        $contas = $this->conta->get_contas_boleto($this->input->post('id_contas_titulos'), $this->input->post('id_contas_guia'), $this->input->get('guia'));
        //var_dump($contas);die;
        if (count($contas)) {

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;
            if ($this->input->get('guia')) {
                $remessa = remessa_boleto_guia($contas, $contador, $this->input->post('data_pagamento_guia'), '09338999000158');
            } else {
                $remessa = remessa_boleto($contas, $contador, $this->input->post('data_pagamento_titulo'), '09338999000158');
            }
            $file = "uploads/remessas/CLT.GUI.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "CLT.GUI.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
                //echo $remessa;
                //$this->load->helper('download');
                //force_download("CLT.GUI.164544.".date('dmY').".".$n_arquivo.".REM", $remessa);
            } else {
                echo 'Erro, Erro_Remessa_Busca';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada, Erro_Remessa_Busca';
        }
    }

    //Gabriel Bandeira
    public function resumoVeiculosDisponiveis($c_order = false, $order = false, $pagina = false)
    {
        $this->load->model('veiculo');
        if ($this->input->get('di') && $this->input->get('df')) {
            $veiculos_disponiveis = $this->veiculo->resumo_disponiveis(data_for_unix($this->input->get('di')), data_for_unix($this->input->get('df')), $this->input->get('id_cliente'));
            $dados['disponiveis'] = $veiculos_disponiveis['result'];
            $dados['graficos'] = $veiculos_disponiveis['graficos'];
            $dados['result'] = true;
        } else {
            $dados['result'] = false;
        }
        $dados['clientes'] = $this->cliente->listar(array());
        $dados['nome_cliente'] = "";
        $dados['titulo'] = 'Veículos Disponíveis';
        $dados['ocultar_filtro'] = true;
        $this->load->view('relatorios/veiculos_diponiveis/resumo_relatorio_veiculos_disponiveis', $dados);
    }
    public function boleto_enviado()
    {
        $faturas = $this->input->post('faturas');
        foreach ($faturas as $id_fatura) {
            $fatura = $this->fatura->get("cad_faturas.Id = {$id_fatura}");
            if ($fatura->status_fatura == '2') { }
            $this->fatura->atualizar_fatura($id_fatura, array('status' => '0'));
        }
        echo "1";
    }

    public function retornaUrlTokenNode(){
        $retorno = (to_urlTokenNode());

        return $retorno;
    }


    public function get_remessa_instaladores_nova(){
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');

        $contas = $this->conta->get_contas_instaladores($this->input->post('checkboxRemessa'));
        if (count($contas)) {
            if ($this->input->post('chavesTed')) {
                foreach ($this->input->post('chavesTed') as $descontar) {
                    $desconto[$descontar] = true;
                }
                foreach ($contas['ted'] as $conta) {
                    if (isset($desconto[intval($conta->conta_id)])) {
                        $conta->valor = number_format(floatval($conta->valor) - 9.7, 2, '.', '');
                    }
                }
            }
            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;

            if ($this->input->get('empresa') == 1)
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_tecnico'), '09338999000158');
            else
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_tecnico'), '21698912000159', '2', '160992', '200', '3', '39321', '5', 'NORIO MOMOI ME');

            $file = "uploads/remessas/PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa_instaladores($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }

    public function get_remessa_salario_novo()
    {
        $this->load->helper('remessa');
        $this->load->model('conta');
        $contas = $this->conta->get_contas($this->input->post('salariosCheck'));
        if (count($contas)) {
            $this->load->helper('file');

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            for ($i = 6 - strlen($contador); $i > 0; $i--) {
                $n_arquivo .= "0";
            }
            $n_arquivo .= $contador;
            if ($this->input->get('empresa') == 1)
                $remessa = remessa_salario($contas, $contador, '09338999000158', '2', '164544', '200', '3', "28629", 'X', 'SHOW PRESTADORA DE SERVICOS DO');
            else
                $remessa = remessa_salario($contas, $contador);

            $file = 'uploads/remessas/PAG_160992_' . $n_arquivo . '.rem';
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa($contas, date('Y-m-d H:i:s'), $file);
                $this->load->helper('download');
                //force_download('PAG_160992_'.$n_arquivo.'.rem', $remessa);
                echo json_encode(array('nome' => 'PAG_160992_' . $n_arquivo . '.rem', 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }

        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }

    public function get_remessa_fornecedores_nova()
    {
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');

        $contas = $this->conta->get_contas_fornecedores($this->input->post('chaveRemessaFornecedor'));
        if (count($contas)) {
            if ($this->input->post('chaveRemessaFornecedorTed')) {
                foreach ($this->input->post('chaveRemessaFornecedorTed') as $descontar) {
                    $desconto[$descontar] = true;
                }
                foreach ($contas['ted'] as $conta) {
                    if (isset($desconto[intval($conta->conta_id)])) {
                        $conta->valor = number_format(floatval($conta->valor) - 9.7, 2, '.', '');
                    }
                }
            }

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;

            if ($this->input->get('empresa') == 1)
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_fornecedor'), '09338999000158');
            else
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_fornecedor'), '21698912000159', '2', '160992', '200', '3', '39321', '5', 'NORIO MOMOI ME');

            $file = "uploads/remessas/PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa_instaladores($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }

    public function get_remessa_boleto_nova()
    {
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');

        $abaaaaaaaaa = $this->input->post('chaveGuiaAba');

        $contas = $this->conta->get_contas_boleto($this->input->post('chaveTituloAba'), $this->input->post('chaveGuiaAba'), $this->input->get('guia'));
        //var_dump($contas);die;
        if (count($contas)) {

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;
            if ($this->input->get('guia')) {
                $remessa = remessa_boleto_guia($contas, $contador, $this->input->post('data_pagamento_guia'), '09338999000158');
            } else {
                $remessa = remessa_boleto($contas, $contador, $this->input->post('data_pagamento_titulo'), '09338999000158');
            }
            $file = "uploads/remessas/CLT.GUI.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "CLT.GUI.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
                //echo $remessa;
                //$this->load->helper('download');
                //force_download("CLT.GUI.164544.".date('dmY').".".$n_arquivo.".REM", $remessa);
            } else {
                echo 'Erro, Erro_Remessa_Busca';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada, Erro_Remessa_Busca';
        }
    }


    public function get_remessa_fornecedores_norio()
    {
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');
        $contas = $this->conta->get_contas_fornecedores($this->input->post('chaveFornecedor'));
        if (count($contas)) {
            if ($this->input->post('chaveFornecedorTed')) {
                foreach ($this->input->post('chaveFornecedorTed') as $descontar) {
                    $desconto[$descontar] = true;
                }
                foreach ($contas['ted'] as $conta) {
                    if (isset($desconto[intval($conta->conta_id)])) {
                        $conta->valor = number_format(floatval($conta->valor) - 9.7, 2, '.', '');
                    }
                }
            }

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;

            if ($this->input->get('empresa') == 1)
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_fornecedor'), '09338999000158');
            else
                $remessa = remessa_instalador($contas, $contador, $this->input->post('data_pagamento_fornecedor'), '21698912000159', '2', '160992', '200', '3', '39321', '5', 'NORIO MOMOI ME');

            $file = "uploads/remessas/PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa_instaladores($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "PAG.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }

    public function get_remessa_boleto_norio()
    {
        $this->load->helper('file');
        $this->load->helper('remessa');
        $this->load->model('conta');
        $contas = $this->conta->get_contas_boleto($this->input->post('chaveTitulo'), $this->input->post('chaveGuia'), $this->input->get('guia'));
        //var_dump($contas);die;
        if (count($contas)) {

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            $n_arquivo .= $contador;
            if ($this->input->get('guia')) {
                $remessa = remessa_boleto_guia($contas, $contador, $this->input->post('data_pagamento_guia'), '09338999000158');
            } else {
                $remessa = remessa_boleto($contas, $contador, $this->input->post('data_pagamento_titulo'), '09338999000158');
            }
            $file = "uploads/remessas/CLT.GUI.164544." . date('dmY') . "." . $n_arquivo . ".REM";
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa($contas, date('Y-m-d H:i:s'), $file);
                echo json_encode(array('nome' => "CLT.GUI.164544." . date('dmY') . "." . $n_arquivo . ".REM", 'arquivo' => $remessa));
                //echo $remessa;
                //$this->load->helper('download');
                //force_download("CLT.GUI.164544.".date('dmY').".".$n_arquivo.".REM", $remessa);
            } else {
                echo 'Erro, Erro_Remessa_Busca';
            }
        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada, Erro_Remessa_Busca';
        }
    }

    public function get_remessa_salario_norio()
    {
        $this->load->helper('remessa');
        $this->load->model('conta');
        $contas = $this->conta->get_contas($this->input->post('chaveSalario'));
        if (count($contas)) {
            $this->load->helper('file');

            $contador = strval(count(glob('uploads/remessas/{*}', GLOB_BRACE)) + 1);
            $n_arquivo = "";
            for ($i = 6 - strlen($contador); $i > 0; $i--) {
                $n_arquivo .= "0";
            }
            $n_arquivo .= $contador;
            if ($this->input->get('empresa') == 1)
                $remessa = remessa_salario($contas, $contador, '09338999000158', '2', '164544', '200', '3', "28629", 'X', 'SHOW PRESTADORA DE SERVICOS DO');
            else
                $remessa = remessa_salario($contas, $contador);

            $file = 'uploads/remessas/PAG_160992_' . $n_arquivo . '.rem';
            if (write_file($file, $remessa)) {
                $this->conta->update_remessa($contas, date('Y-m-d H:i:s'), $file);
                $this->load->helper('download');
                //force_download('PAG_160992_'.$n_arquivo.'.rem', $remessa);
                echo json_encode(array('nome' => 'PAG_160992_' . $n_arquivo . '.rem', 'arquivo' => $remessa));
            } else {
                echo 'Erro';
            }

        } else {
            echo 'Erro, nenhuma conta com pendência no arquivo de remessa encontrada';
        }
    }
}
