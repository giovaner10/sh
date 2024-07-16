<?php

// use function GuzzleHttp\json_decode;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cadastros extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('fatura');
        $this->load->model('contrato');
        $this->load->model('cliente');
        $this->load->model('cadastro');
        $this->load->model('veiculo');
        $this->load->model('logistica');
        $this->load->model('equipamento');
        $this->load->model('usuario_gestor');
        $this->load->model('parlacom');
        $this->load->model('log_veiculo');
        $this->load->model('arquivo');
        $this->load->model('ashownett');
        $this->load->model('log_shownet');
        $this->load->model('mapa_calor');
        $this->load->helper('produtos_helper');
    }

    public function index()
    {
        $dados['titulo'] = 'Show Tecnologia';
        $this->load->view('fix/header', $dados);
        $this->load->view('cadastros/index');
        $this->load->view('fix/footer');
    }

    /**
     * Função carrega view de cadastro de produtos e permissoes
     * @author Lucas Henrique
     */
    public function cadastro_produtos()
    {
        $this->auth->is_allowed('cad_permissoes');

        $dados['modulos'] = ['Atendimento', 'Cadastro', 'Comando', 'Configuração', 'Monitorados', 'Relatório', 'Notificação', 'Meu Omnilink'];
        $dados['tecnologias'] = ['Omnilink', 'Omniweb', 'Omnifrota', 'OmniSafe', 'Omnijornada'];
        $dados['titulo'] = lang('permissoes_gestor');

        $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/cadastro_produtos'));

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('cadastros/cad_permissao_produtos_new');
        $this->load->view('fix/footer_NS');
    }

    /**
     * Função que carrega permissões
     * @author Lucas Henrique
     * @method POST
     */
    public function buscarPermissoes()
    {
        $descricao = $this->input->post('descricao');
        $codigo = $this->input->post('codigo');
        $modulo = $this->input->post('modulo');
        $tecnologia = $this->input->post('tecnologia');
        $status = $this->input->post('status');

        $result = getPermissoesProdutos($descricao, $codigo, $tecnologia, $modulo, $status);

        echo json_encode($result);
    }

    /**
     * Função que carrega produtos
     * @author Lucas Henrique
     * @method POST
     */
    public function buscarProdutos()
    {
        $nome = $this->input->post('nome');
        $descricao = $this->input->post('descricao');
        $codProduto = $this->input->post('codProduto');
        $status = $this->input->post('status');

        $result = getProdutos($nome, $descricao, $codProduto, $status);

        echo json_encode($result);
    }

    /**
     * Função que altera status da permissão
     * @author Lucas Henrique
     * @method POST
     */
    public function alterarStatusPermissao()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $POSTFIELDS = array(
            'id' => $id,
            'status' => $status
        );

        $result = patchPermissao($POSTFIELDS);

        echo json_encode($result);
    }

    /**
     * Função que cadastra uma permissão
     * @author Lucas Henrique
     * @method POST
     */
    public function cadastrarPermissao()
    {
        $descricao = $this->input->post('descricao');
        $modulo = $this->input->post('modulo');
        $tecnologia = $this->input->post('tecnologia');

        if ($descricao && $modulo && $tecnologia) {
            //Tratamento do nome do modulo
            $prefixo = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $modulo);
            $prefixo = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $prefixo); //remove caracteres especiais e acentuacao			

            //Tratamento do nome da tercnologia
            $sufixo = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $tecnologia);
            $sufixo = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $sufixo); //remove caracteres especiais e acentuacao			

            //Tratamento do nome da permissao
            $nomePerm = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $descricao);
            $nomePerm = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $nomePerm); //remove caracteres especiais e acentuacao

            $cod_permissao = strtolower($prefixo . '_' . $nomePerm . '_' . $sufixo);
        } else {
            $cod_permissao = "";
        }

        $POSTFIELDS = array(
            "descricao" => $descricao,
            "codPermissao" => $cod_permissao,
            "tecnologia" => $tecnologia,
            "modulo" => $modulo
        );

        $result = cadastrarPermissao($POSTFIELDS);

        echo json_encode($result);
    }

    /**
     * Função que edita uma permissão
     * @author Lucas Henrique
     * @method POST
     */
    public function editarPermissao()
    {
        $id = $this->input->post('id');
        $descricao = $this->input->post('descricao');
        $cod_permissao = $this->input->post('cod_permissao');
        $modulo = $this->input->post('modulo');
        $tecnologia = $this->input->post('tecnologia');

        $POSTFIELDS = array(
            "id" => $id,
            "descricao" => $descricao,
            "codPermissao" => $cod_permissao,
            "tecnologia" => $tecnologia,
            "modulo" => $modulo
        );

        $result = atualizarPermissao($POSTFIELDS);

        echo json_encode($result);
    }

    /**
     * Função que cadastra um produto
     * @author Lucas Henrique
     * @method POST
     */
    public function cadastrarProduto()
    {
        $nome = $this->input->post('nome');
        $descricao = $this->input->post('descricao');
        $id_licenca = $this->input->post('id_licenca');

        if ($nome) {

            // Gera codigo do produto
            $codigo_produto = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $nome);
            $codigo_produto = preg_replace(array('/[ ]/', '/[^A-Za-z0-9]/'), array('', ''), $codigo_produto); //remove caracteres especisias e acentuacao
            $codigo_produto = strtolower($codigo_produto);
        } else {
            $cod_permissao = "";
        }

        $POSTFIELDS = array(
            "nome" => $nome,
            "descricao" => $descricao,
            "tecnologia" => $tecnologia,
            "idLicenca" => $id_licenca
        );

        $result = putCadastrarProduto($POSTFIELDS);

        echo json_encode($result);
    }

    /**
     * Função que altera status do produto
     * @author Lucas Henrique
     * @method POST
     */
    public function alterarStatusProduto()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $POSTFIELDS = array(
            'id' => $id,
            'status' => $status
        );

        $result = patchProduto($POSTFIELDS);

        echo json_encode($result);
    }

    /**
     * Função carrega view de cadastro de produtos e permissoes
     * @author Eberson Santos
     */
    public function cadastro_produtos_old()
    {
        $dados['modulos'] = ['Atendimento', 'Cadastro', 'Comando', 'Configuração', 'Monitorados', 'Relatório', 'Notificação', 'Meu Omnilink'];
        $dados['tecnologias'] = ['Omnilink', 'Omniweb', 'Omnifrota'];
        $dados['titulo'] = 'Cadastro de Permissões';
        $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/cadastro_produtos'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('cadastros/cad_produtos_new');
        $this->load->view('fix/footer_NS');
    }

    /**
     * Função retorna produtos para o dataTable
     * @author Eberson
     */
    public function ajax_produtos()
    {
        $retorno['data'] = [];
        $dados = $this->cadastro->get_produtos();

        if ($dados && is_array($dados)) {
            foreach ($dados as $d) {
                $disabled = $d->status == '1' ? '' : 'disabled';
                $btn_edit = '<button class="btn btn-mini btn-primary editarProduto btn-block" ' . $disabled . ' data-id="' . $d->id . '" style="width: 80px; text-align: -webkit-center;" title="Editar">Editar</button> ';
                $btn_status = $d->status == '1' ? '<a class="btn btn-mini btn-danger btn_status_produto btn-block" data-id_produto="' . $d->id . '" style="width: 80px; text-align: -webkit-center;" data-status="0">Inativar</a>' : '<a class="btn btn-mini btn-success btn_status_produto btn-block" data-id_produto="' . $d->id . '" style="width: 80px; text-align: -webkit-center;" data-status="1">Ativar</a>';

                $retorno['data'][] = array(
                    $d->id,
                    $d->nome,
                    $d->descricao,
                    $d->codigo_produto,
                    date('d/m/Y H:i:s', strtotime($d->data_cadastro)),
                    $d->status == '0' ? '<span class="label label-danger">Inativo</span>' : '<span class="label label-success">Ativo</span>',
                    '<div class="text-center">' . $btn_edit . $btn_status . '</div>',
                    $d->id_licenca
                );
            }
        }

        echo json_encode($retorno);
    }

    /**
     * Função de cadastro dos produtos
     * @author Saulo Mendes
     *
     * @param Array $produtos = Array de modulos do produto
     * @param String $descricao = Descricao do Produto
     * @param String $nome = Nome do Produto
     */
    public function add_produto()
    {

        $nome = $this->input->post('produto_nome');
        $descricao = $this->input->post('produto_descricao');
        $id_licenca = $this->input->post('id_licenca');
        $permissoes = $this->input->post('produto_permissoes');
        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        if ($nome && is_string($nome) && $permissoes && is_string($permissoes)) {
            $produto = $this->cadastro->get_produtos('*', array(
                'nome' => $nome,
            ));

            if ($produto) {
                exit(json_encode(array('status' => false, 'msg' => 'Nome do produto fornecido já está cadastrado!')));
            }

            // Gera codigo do produto
            $codigo_produto = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $nome);
            $codigo_produto = preg_replace(array('/[ ]/', '/[^A-Za-z0-9]/'), array('', ''), $codigo_produto); //remove caracteres especisias e acentuacao
            $codigo_produto = strtolower($codigo_produto);

            // Cria dados para insert
            $insert = array(
                'nome' => $nome,
                'descricao' => $descricao,
                'id_licenca' => !empty($id_licenca) ? $id_licenca : null,
                'codigo_produto' => $codigo_produto,
                'data_cadastro' => date('Y-m-d H:i:s'),
                'status' => '1'
            );

            $id_produto = $this->cadastro->add_produto($insert);
            if ($id_produto) {

                $this->log_shownet->gravar_log($id_user, 'cad_produtos', $id_produto, 'criar', 'null', $insert);

                $permissoes = explode(',', $permissoes);

                foreach ($permissoes as $id_permissao) {
                    $insert_batch[] = array(
                        'id_produto' => $id_produto,
                        'id_permissao' => $id_permissao,
                    );
                }

                $this->cadastro->add_produto_permissao_lote($insert_batch);
                $this->log_shownet->gravar_log($id_user, 'cad_produto_permissao', $id_produto, 'criar', 'null', array_column_custom($insert_batch, 'id_permissao'));

                exit(json_encode(array('status' => true, 'msg' => 'Registro cadastrado com sucesso.')));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível cadastrar o produto. Tente novamente mais tarde!')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
     * Função consulta planos de um produto
     * @author Eberson Santos
     * @method GET
     *
     * @param INT $id = ID do produto
     * @return OBJECT = Planos de um produto
     */
    public function get_planos_produto()
    {
        $id_produto = $this->input->get('id_produto');
        if ($id_produto && is_numeric($id_produto)) {
            $planos = $this->cadastro->get_planos_produto($id_produto);

            if ($planos) {
                exit(json_encode(array('status' => true, 'planos' => $planos)));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Nenhum plano está ativo para este produto.')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Produto informado não encontrado.')));
        }
    }

    /**
     * Função consulta permissoes de um produto
     * @author Eberson Santos
     * @method GET
     *
     * @param INT $id = ID do produto
     * @return OBJECT = Permissoes de um produto
     */
    public function get_permissoes_produto()
    {
        $id_produto = $this->input->get('id_produto');
        if ($id_produto && is_numeric($id_produto)) {
            $permissoes = $this->cadastro->get_permissoes_produto($id_produto);

            if ($permissoes) {
                exit(json_encode(array('status' => true, 'permissoes' => $permissoes)));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Nenhuma permissão está ativa para este produto.')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Produto informado não encontrado.')));
        }
    }

    /**
     * Função edita dados do produto
     * @author Eberson Santos
     * @method POST
     *
     * @param ARRAY $permissoes = Array de Permissoes do Produto
     * @param STRING $nome = Nome do Produto
     * @param STRING $descricao = Descrição do Produto
     */
    public function edit_produtos()
    {
        $dados = $this->input->post();
        if (empty($dados)) {
            exit(json_encode(array('status' => false, 'mensagem' => lang('erro_parametros'))));
        }

        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        $id_produto = $dados['id_produto_edit'];

        $ids_permissoes_novas = $dados['produto_permissoes_edit'];
        $nome = $dados['produto_nome_edit'];
        $descricao = $dados['produto_descricao_edit'];
        $id_licenca = $this->input->post('id_licenca_edit');

        $produto = $this->cadastro->get_produtos('id', [
            'nome' => $nome,
            'id <>' => $id_produto
        ]);

        if ($produto) exit(json_encode(array('status' => false, 'mensagem' => lang('nome_produto_ja_cadastrado'))));

        $dados_novos_produto = [
            'nome' => $nome,
            'descricao' => $descricao,
            'id_licenca' => !empty($id_licenca) ? $id_licenca : null,
        ];

        //pegar dados antigos do produto.
        $produtobd = $this->cadastro->get_produto(array('id' => $id_produto));

        $dados_produto_antigo = array(
            'nome'       => $produtobd->nome,
            'descricao'  => $produtobd->descricao,
            'id_licenca' => $produtobd->id_licenca
        );

        $produto_atualizado = $this->cadastro->edit_produto($id_produto, $dados_novos_produto);
        if (!$produto_atualizado) {
            exit(json_encode(array('status' => false, 'mensagem' => lang('erro_edicao_produto'))));
        }

        $this->log_shownet->gravar_log($id_user, 'cad_produtos', $id_produto, 'atualizar', $dados_produto_antigo, $dados_novos_produto);

        $ids_permissoes_novas = $ids_permissoes_novas ? explode(',', $ids_permissoes_novas) : [];

        $minhas_permissoes_produto = $this->cadastro->get_permissoes_produto($id_produto, 'id_permissao, cod_permissao');
        $ids_permissoes_produto = array_column_custom($minhas_permissoes_produto, 'id_permissao');
        $permissoes_produto = array_column_custom($minhas_permissoes_produto, 'cod_permissao');

        $permissoes_para_remocao = [];
        $permissoes_para_adicionar = [];

        if (!empty($ids_permissoes_produto)) {
            foreach ($ids_permissoes_produto as $id_permissao) {
                if (!in_array($id_permissao, $ids_permissoes_novas)) {
                    $permissoes_para_remocao[] = $id_permissao;
                }
            }
        }

        if (!empty($ids_permissoes_novas)) {
            foreach ($ids_permissoes_novas as $id_permissao_nova) {
                if (!in_array($id_permissao_nova, $ids_permissoes_produto)) {
                    $permissoes_para_adicionar[] = [
                        'id_produto' => $id_produto,
                        'id_permissao' => $id_permissao_nova,
                    ];
                }
            }
        }
        if (!empty($permissoes_para_remocao)) {
            $this->cadastro->delete_produto_permissao($id_produto, $permissoes_para_remocao);
            $permissoes_produto_apos_deletar = $this->cadastro->get_permissoes_produto($id_produto, 'id_permissao, cod_permissao');
            $this->log_shownet->gravar_log($id_user, 'cad_produto_permissao', $id_produto, 'deletar', $permissoes_para_remocao, array_column_custom($permissoes_produto_apos_deletar, 'id_permissao'));
        }

        if (!empty($permissoes_para_adicionar)) {
            $permissoes_produto_antes_add = $this->cadastro->get_permissoes_produto($id_produto, 'id_permissao, cod_permissao');
            $this->cadastro->add_produto_permissao_lote($permissoes_para_adicionar);
            $permissoesAtualizadas = array_merge($permissoes_produto_antes_add, $permissoes_para_adicionar);
            $this->log_shownet->gravar_log($id_user, 'cad_produto_permissao', $id_produto, 'atualizar', array_column_custom($permissoes_produto_antes_add, 'id_permissao'),  array_column_custom($permissoesAtualizadas, 'id_permissao'));
        }

        //atualizar as permissoes de todos os clientes que possuem este produto
        $clientes_produto = $this->cadastro->buscar_clientes_pelo_produto($id_produto, 'id, permissoes');
        $novas_permissoes = [];
        if (!empty($ids_permissoes_novas)) {
            $novas_permissoes = $this->cadastro->get_permissoes_por_ids($ids_permissoes_novas, 'cod_permissao');
            $novas_permissoes = array_column_custom($novas_permissoes, 'cod_permissao');
        }

        $this->atualizar_permissoes_clientes_usuarios($clientes_produto, $permissoes_produto, $novas_permissoes);

        exit(json_encode(array('status' => true, 'mensagem' => lang('produto_editado_com_sucesso'))));
    }

    private function atualizar_permissoes_clientes_usuarios($clientes, $permissoes_produto, $novas_permissoes)
    {
        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        if (!empty($clientes)) {
            $novas_permissoes_cliente_lote = $ids_clientes = $dados_novos_usuarios_lote = [];
            $usurios_por_cliente = [];

            $ids_clientes = array_column_custom($clientes, 'id');
            $ids_clientes = implode(',', $ids_clientes);
            //busca todos os usuarios admin/master dos clientes que possuem o produto
            $usuariosAgrupados = $this->usuario_gestor->busca_usuarios_superiores_de_clientes($ids_clientes, $colunas = '*');
            if (!empty($usuariosAgrupados)) {
                foreach ($usuariosAgrupados as $key => $usuarios_cliente) {
                    $usurios_por_cliente[$usuarios_cliente->id_cliente] = !empty($usuarios_cliente->usuarios) ? explode(',', $usuarios_cliente->usuarios) : [];
                }
            }

            foreach ($clientes as $cliente) {
                $permissoes = !empty($cliente->permissoes) ? json_decode($cliente->permissoes) : [];
                $permissoes = array_diff($permissoes, $permissoes_produto);
                $permissoes = array_merge($permissoes, $novas_permissoes);
                $permissoesJson = json_encode($permissoes);

                $novas_permissoes_cliente_lote[] = [
                    'id' => $cliente->id,
                    'permissoes' => $permissoesJson,
                ];

                $this->log_shownet->gravar_log(
                    $id_user,
                    'cad_clientes',
                    $cliente->id,
                    'atualizar',
                    $permissoes,
                    $permissoesJson
                );

                $ids_usuarios = isset($usurios_por_cliente[$cliente->id]) ? $usurios_por_cliente[$cliente->id] : [];
                if (!empty($ids_usuarios)) {
                    foreach ($ids_usuarios as $id_usuario) {
                        $dados_novos_usuarios_lote[] = [
                            'code' => $id_usuario,
                            'permissoes' => $permissoesJson,
                        ];

                        $this->log_shownet->gravar_log(
                            $id_user,
                            'usuario_gestor',
                            $id_usuario,
                            'atualizar',
                            'null',
                            $permissoesJson
                        );
                    }
                }
            }

            if (!empty($novas_permissoes_cliente_lote)) {
                //atualiza as permissoes dos clientes
                $this->cliente->atualizar_clientes_lote($novas_permissoes_cliente_lote);
                //Atualiza as permissoes dos usuarios master/admin dos clientes
                if (!empty($dados_novos_usuarios_lote)) {
                    $this->usuario_gestor->atualizar_usuarios_lote($dados_novos_usuarios_lote);
                }
            }

            return true;
        }
        return false;
    }


    /**
     * Função altera status do produto
     * @author Eberson Santos
     * @method POST
     *
     * @param INT $id_produto = id do Produto
     * @param INT $status = Status do Produto
     */
    public function status_produto()
    {
        $id_produto = $this->input->post('id_produto');
        $status = $this->input->post('status');
        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        if ($id_produto && isset($status)) {

            //pegar dados antigos do produto.
            $produtobd = $this->cadastro->get_produto(array('id' => $id_produto));

            $dados_produto_antigo = array(
                'status'        => $produtobd->status
            );

            $retorno = $this->cadastro->edit_produto($id_produto, array('status' => $status));

            $dados_produto_formatados = array('status' => $status);
            $this->log_shownet->gravar_log($id_user, 'cad_produtos', $id_produto, 'atualizar', $dados_produto_antigo, $dados_produto_formatados);

            if ($retorno) {
                exit(json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.')));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível atualizar o produto. Tente novamente mais tarde!')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
     * Função carrega view de cadastro de planos
     *
     * @author Saulo Mendes
     */
    public function cad_planos()
    {
        $this->load->view('fix/header4', array(
            'titulo' => 'Cadastro de Planos'
        ));
        $this->load->view('cadastros/cad_planos');
        $this->load->view('fix/footer4');
    }

    /**
     * Função retorna permissões por cliente para o datatable
     * @author Saulo Mendes
     * @method GET
     * @param INT $id = ID_CLIENTE
     * @return JSON_ENCODE(Array())
     */
    public function ajax_permissoes_cliente($id)
    {
        $retorno['data'] = array();
        if (is_numeric($id)) {
            $permissoes_cliente = $this->cadastro->getPermissoesCliente($id);
            $permissoes_cliente = json_decode($permissoes_cliente);

            if (!empty($permissoes_cliente)) {
                $permissoes = $this->cadastro->getPermissoes(false, $permissoes_cliente);

                if (is_array($permissoes) && !empty($permissoes)) {
                    foreach ($permissoes as $p) {
                        $retorno['data'][] = array(
                            '<input type="checkbox" name="permissoes[]" value="' . $p->cod_permissao . '" />',
                            $p->descricao,
                        );
                    }
                }
            }
        }

        echo json_encode($retorno);
    }

    /**
     * Função consulta permissões de um usuário
     * @author Saulo Mendes
     * @method GET
     * @param INT $id = ID_USER
     * @return JSON(array)
     */
    public function ajax_permissoes_usuario()
    {
        $permissao = $this->cadastro->getPermissaoUser($this->input->post('id'));
        exit($permissao ? $permissao->permissoes : json_encode([]));
    }

    /**
     * Função retorna planos para o dataTable
     * @author Saulo Mendes
     */
    public function ajax_planos($short = false)
    {
        $retorno['data'] = [];
        if ($short) {
            $dados = $this->cadastro->getPlanos('*', array('status' => '1'));
        } else {
            $dados = $this->cadastro->getPlanos();
        }

        if ($dados && is_array($dados)) {

            if ($short) {
                foreach ($dados as $plano) {
                    $retorno['data'][] = array(
                        '<input type="checkbox" name="planos[]" value="' . $plano->id . '" />',
                        $plano->nome,
                    );
                }
            } else {
                foreach ($dados as $d) {
                    $disabled = $d->status == '1' ? '' : 'disabled';
                    $btn_edit = '<button class="btn btn-mini btn-primary editarPlano" ' . $disabled . ' data-id="' . $d->id . '" title="Editar">Editar</button> ';
                    $btn_status = $d->status == '1' ? '<a class="btn btn-mini btn-danger btn_status" data-id_plano="' . $d->id . '" data-status="0">Inativar</a>' : '<a class="btn btn-mini btn-success btn_status" data-id_plano="' . $d->id . '" data-status="1">Ativar</a>';

                    $retorno['data'][] = array(
                        $d->id,
                        $d->nome,
                        $d->descricao,
                        date('d/m/Y H:i:s', strtotime($d->createdAt)),
                        $d->editavel == '0' ? '<span class="label label-danger">Não</span>' : '<span class="label label-success">Sim</span>',
                        $d->status == '0' ? '<span class="label label-danger">Inativo</span>' : '<span class="label label-success">Ativo</span>',
                        $btn_edit . $btn_status
                    );
                }
            }
        }

        echo json_encode($retorno);
    }

    /**
     * Função edita dados do plano
     * @author Saulo Mendes
     * @method POST

     *
     * @param ARRAY $permissoes
     *            = Array de Permissões do Plano
     * @param STRING $nome
     *            = Nome do Plano
     * @param STRING $observacoes
     *            = Observações do Plano
     * @param STRING $status
     *            = 0/1 (Opcional)

     * @param ARRAY $modulos = Array de Módulos do Plano
     * @param STRING $nome = Nome do Plano
     * @param STRING $descricao = Descrição do Plano
     */

    public function edit_plano()
    {
        $id_plano = $this->input->post('id_plano_edit');
        $modulos = $this->input->post('modulos');
        $nome = $this->input->post('plano_nome_edit');
        $descricao = $this->input->post('plano_descricao_edit');
        $editavel = $this->input->post('plano_editavel_edit');

        if ($id_plano && $nome && is_string($nome) && $modulos && is_array($modulos)) {

            // Cria dados para update
            $update = array(
                'nome' => $nome,
                'descricao' => $descricao,
                'editavel' => $editavel,
                'updatedAt' => date('Y-m-d H:i:s'),
            );

            $retorno = $this->cadastro->editPlano($id_plano, $update);

            if ($retorno) {
                $modulos_plano_old = $this->cadastro->get_modulos_plano(array('id_plano' => $id_plano));

                foreach ($modulos_plano_old as $modulo) {
                    if (($key = array_search($modulo->id_modulo, $modulos)) !== false) {
                        $plano_modulo = $this->cadastro->get_plano_modulo($id_plano, $modulo->id_modulo);
                        $this->cadastro->edit_plano_modulo($plano_modulo->id, array('status' => '1'));
                        unset($modulos[$key]);
                    } else {
                        $plano_modulo = $this->cadastro->get_plano_modulo($id_plano, $modulo->id_modulo);
                        $this->cadastro->edit_plano_modulo($plano_modulo->id, array('status' => '0'));
                    }
                }

                $insert_batch = [];
                foreach ($modulos as $id_modulo) {
                    $insert_batch[] = array(
                        'id_plano' => $id_plano,
                        'id_modulo' => $id_modulo,
                    );
                }
                if ($insert_batch) {
                    $this->cadastro->add_plano_modulo_lote($insert_batch);
                }

                exit(json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.')));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível atualizar o plano. Tente novamente mais tarde!')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
     * Função altera status do plano
     * @author Eberson Santos
     * @method POST
     * @param INT $id_plano = id do Plano
     * @param INT $status = Status do Plano
     */
    public function status_plano()
    {
        $id_plano = $this->input->post('id_plano');
        $status = $this->input->post('status');

        if ($id_plano && isset($status)) {
            $retorno = $this->cadastro->editPlano($id_plano, array('status' => $status));

            if ($retorno) {
                exit(json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.')));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível atualizar o plano. Tente novamente mais tarde!')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
     * Função consulta dados de um plano
     * @author Saulo Mendes
     * @method GET
     * @param INT $id = ID do plano
     * @return OBJECT = Registro do plano
     */

    public function getPlano()
    {
        $id = $this->input->get('id');
        if ($id && is_numeric($id)) {
            $plano = $this->cadastro->getPlanos('*', array('id' => $id), null, 'row');
            echo json_encode($plano);
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Parâmetro ID não encontrado.'));
        }
    }

    /**
     * Função consulta modulos de um plano
     * @author Eberson Santos
     * @method GET
     *
     * @param INT $id = ID do plano
     * @return OBJECT = Modulos do plano
     */
    public function get_modulos_plano()
    {
        $id_plano = $this->input->get('id_plano');
        if ($id_plano && is_numeric($id_plano)) {
            $where = array('id_plano' => $id_plano, 'status' => '1');
            $modulos = $this->cadastro->get_modulos_plano($where);

            if ($modulos) {
                exit(json_encode(array('status' => true, 'modulos' => $modulos)));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Nenhum módulo está ativo para este plano.')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Plano informado não encontrado.')));
        }
    }

    /**
     * Função de cadastro dos planos
     * @author Saulo Mendes
     *
     * @param Array $modulos = Array de modulos do plano
     * @param String $descricao = Descricao do Plano
     * @param String $nome = Nome do Plano
     */
    public function add_plano()
    {
        $modulos = $this->input->post('modulos');
        $nome = $this->input->post('plano_nome');
        $descricao = $this->input->post('plano_descricao');
        $editavel = $this->input->post('plano_editavel');

        if ($nome && is_string($nome) && $modulos && is_array($modulos)) {

            $plano = $this->cadastro->getPlanos('*', array(
                'nome' => $nome,
            ));

            if ($plano) {
                exit(json_encode(array('status' => false, 'msg' => 'Nome do plano fornecido já está cadastrado!')));
            } else {
                // Cria dados para insert
                $insert = array(
                    'nome' => $nome,
                    'descricao' => $descricao,
                    'createdAt' => date('Y-m-d H:i:s'),
                    'updatedAt' => date('Y-m-d H:i:s'),
                    'status' => '1',
                    'editavel' => $editavel
                );

                $id_plano = $this->cadastro->add_plano($insert);
                if ($id_plano) {
                    foreach ($modulos as $id_modulo) {
                        $insert_batch[] = array(
                            'id_plano' => $id_plano,
                            'id_modulo' => $id_modulo,
                        );
                    }

                    $this->cadastro->add_plano_modulo_lote($insert_batch);

                    exit(json_encode(array('status' => true, 'msg' => 'Registro cadastrado com sucesso.')));
                } else {
                    exit(json_encode(array('status' => false, 'msg' => 'Não foi possível cadastrar o plano. Tente novamente mais tarde!')));
                }
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
     * Função carrega view de cadastro de permissões
     * @author Saulo Mendes
     */
    public function cad_permissoes()
    {
        $this->load->view('fix/header', array('titulo' => 'Cadastro de Permissões'));
        $this->load->view('cadastros/cad_permissoes');
        $this->load->view('fix/footer');
    }

    /**
     * Função retorna permissões para o dataTable
     * @author Saulo Mendes
     */
    public function ajax_permissoes($short = false)
    {
        $retorno['data'] = [];

        if ($short) {
            $permissoes = $this->cadastro->getPermissoes(array('status' => '1'));
        } else {
            $permissoes = $this->cadastro->getPermissoes();
        }

        if (is_array($permissoes) && !empty($permissoes)) {
            if ($short) {
                foreach ($permissoes as $p) {
                    $retorno['data'][] = array(
                        '<input type="checkbox" name="permissoes[]" class="produto_permissoes" value="' . $p->id . '" />',
                        $p->descricao,
                        $p->modulo,
                        $p->tecnologia,
                    );
                }
            } else {
                foreach ($permissoes as $p) {
                    $disabled = $p->status == '1' ? '' : 'disabled';
                    $btn_edit = '<button class="btn btn-mini btn-primary editarPermissao btn-block" ' . $disabled . ' data-id="' . $p->id . '"  style="width: 80px; text-align: -webkit-center;" title="Editar">Editar</button> ';
                    $btn_status = $p->status == '1' ? '<a class="btn btn-mini btn-danger btn_status_permissao btn-block" data-id_permissao="' . $p->id . '" style="width: 80px; text-align: -webkit-center;" data-status="0">Inativar</a>' : '<a class="btn btn-mini btn-success btn_status_permissao btn-block" data-id_permissao="' . $p->id . '" style="width: 80px; text-align: -webkit-center;" data-status="1">Ativar</a>';

                    $retorno['data'][] = array(
                        $p->id,
                        $p->descricao,
                        $p->cod_permissao,
                        $p->modulo,
                        $p->tecnologia,
                        $p->status == '0' ? '<span class="label label-danger">Inativo</span>' : '<span class="label label-success">Ativo</span>',
                        $btn_edit . $btn_status
                    );
                }
            }
        }

        echo json_encode($retorno);
    }

    /**
     * Função adiciona uma nova permissão
     * @author Saulo Mendes
     * @param STRING $descricao = Descrição informativa
     * @param STRING $modulo = Módulo (cadastro, relatorio etc)
     * @param STRING $tecnologia = Tecnologia (omnilink, omnicarga etc)
     *
     * @return Array = (status = TRUE/FALSE)
     */
    public function add_permissao()
    {
        $descricao = $this->input->post('descricao');
        $modulo = $this->input->post('modulo');
        $tecnologia = $this->input->post('tecnologia');

        if ($descricao && $modulo && $tecnologia) {
            //Tratamento do nome do modulo
            $prefixo = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $modulo);
            $prefixo = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $prefixo); //remove caracteres especisias e acentuacao			

            //Tratamento do nome da tercnologia
            $sufixo = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $tecnologia);
            $sufixo = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $sufixo); //remove caracteres especisias e acentuacao			

            //Tratamento do nome da permissao
            $nomePerm = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $descricao);
            $nomePerm = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $nomePerm); //remove caracteres especisias e acentuacao

            $cod_permissao = strtolower($prefixo . '_' . $nomePerm . '_' . $sufixo);

            $id_permissao = $this->cadastro->add_permissao(array(
                'cod_permissao' => $cod_permissao,
                'descricao' => $descricao,
                'modulo' => $modulo,
                'tecnologia' => $tecnologia,
            ));

            if ($id_permissao) {
                $dados = array(
                    $id_permissao,
                    $descricao,
                    $cod_permissao,
                    $modulo,
                    $tecnologia,
                    '<span class="label label-success">Ativo</span>',
                    '<button class="btn btn-mini btn-primary editarPermissao btn-block" data-id="' . $id_permissao . '"  style="width: 80px; text-align: -webkit-center;" title="Editar">Editar</button> ' . '<a class="btn btn-mini btn-danger btn_status_permissao btn-block" data-id_permissao="' . $id_permissao . '" style="width: 80px; text-align: -webkit-center;" data-status="0">Inativar</a>'
                );

                exit(json_encode(array('status' => true, 'dados' => $dados)));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível cadastrar a permissão no momento. Tente novamente mais tarde!')));
            }
        }

        exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
    }

    public function edit_permissao()
    {
        $id_permissao = $this->input->post('id_permissao');
        $cod_permissao = $this->input->post('cod_permissao');
        $descricao = $this->input->post('descricao');
        $modulo = $this->input->post('modulo');
        $tecnologia = $this->input->post('tecnologia');

        if ($id_permissao && $cod_permissao && $descricao && $modulo && $tecnologia) {

            $update = $this->cadastro->edit_permissao($id_permissao, array(
                'descricao' => $descricao,
                'modulo' => $modulo,
                'tecnologia' => $tecnologia,
            ));

            if ($update) {
                $dados = array(
                    $id_permissao,
                    $descricao,
                    $cod_permissao,
                    $modulo,
                    $tecnologia,
                    '<span class="label label-success">Ativo</span>',
                    '<button class="btn btn-mini btn-primary btn-block editarPermissao" data-id="' . $id_permissao . '"  style="width: 80px; text-align: -webkit-center;" title="Editar">Editar</button> ' . '<a class="btn btn-mini btn-danger btn_status_permissao btn-block" data-id_permissao="' . $id_permissao . '" style="width: 80px; text-align: -webkit-center;" data-status="0">Inativar</a>'
                );

                exit(json_encode(array('status' => true, 'dados' => $dados)));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível atualizar a permissão no momento. Tente novamente mais tarde!')));
            }
        }

        exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
    }

    /**
     * Função altera status da permissao
     * @author Eberson Santos
     * @method POST
     *
     * @param INT $id_permissao = id da Permissao
     * @param INT $status = Status da Permissao
     */
    public function status_permissao()
    {
        $id_permissao = $this->input->post('id_permissao');
        $status = $this->input->post('status');

        if ($id_permissao && isset($status)) {
            $retorno = $this->cadastro->edit_permissao($id_permissao, array('status' => $status));

            if ($retorno) {
                exit(json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.')));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível atualizar a permissão. Tente novamente mais tarde!')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    public function ajax_modulos($short = false)
    {
        $retorno['data'] = [];
        $modulos = $this->cadastro->getModulos();

        if (is_array($modulos) && !empty($modulos)) {
            if ($short) {
                foreach ($modulos as $modulo) {
                    if ($modulo->status == '1') {
                        $retorno['data'][] = array(
                            '<input type="checkbox" name="modulos[]" value="' . $modulo->id . '" />',
                            $modulo->nome,
                        );
                    }
                }
            } else {
                foreach ($modulos as $modulo) {
                    $disabled = $modulo->status == '1' ? '' : 'disabled';
                    $btn_edit = '<button class="btn btn-mini btn-primary editarModulo" ' . $disabled . ' data-id="' . $modulo->id . '" title="Editar">Editar</button> ';
                    $btn_status = $modulo->status == '1' ? '<a class="btn btn-mini btn-danger btn_status_modulo" data-id_modulo="' . $modulo->id . '" data-status="0">Inativar</a>' : '<a class="btn btn-mini btn-success btn_status_modulo" data-id_modulo="' . $modulo->id . '" data-status="1">Ativar</a>';

                    $retorno['data'][] = array(
                        $modulo->id,
                        $modulo->nome,
                        $modulo->data_cad,
                        $modulo->status == '0' ? '<span class="label label-danger">Inativo</span>' : '<span class="label label-success">Ativo</span>',
                        $btn_edit . $btn_status
                    );
                }
            }
        }

        exit(json_encode($retorno));
    }

    public function add_modulo()
    {
        $permissoes = $this->input->post('permissoes');
        $nome = $this->input->post('modulo_nome');

        if ($nome && is_string($nome) && $permissoes && is_array($permissoes)) {

            $modulo = $this->cadastro->getModulos('*', array(
                'nome' => $nome,
            ));

            if ($modulo) {
                exit(json_encode(array('status' => false, 'msg' => 'Nome do módulo fornecido já está cadastrado!')));
            } else {
                // Cria dados para insert
                $insert = array(
                    'nome' => $nome,
                    'status' => '1',
                    'data_cad' => date('Y-m-d H:i:s')
                );

                $id_modulo = $this->cadastro->add_modulo($insert);
                if ($id_modulo) {
                    foreach ($permissoes as $id_permissao) {
                        $insert_batch[] = array(
                            'id_modulo' => $id_modulo,
                            'id_permissao' => $id_permissao,
                        );
                    }

                    $this->cadastro->add_modulo_permissao_lote($insert_batch);

                    exit(json_encode(array('status' => true, 'msg' => 'Registro cadastrado com sucesso.', 'id_modulo' => $id_modulo, 'nome_modulo' => $nome)));
                } else {
                    exit(json_encode(array('status' => false, 'msg' => 'Não foi possível cadastrar o módulo. Tente novamente mais tarde!')));
                }
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    public function edit_modulo()
    {
        $id_modulo = $this->input->post('id_modulo_edit');
        $permissoes = $this->input->post('permissoes');
        $nome = $this->input->post('modulo_nome_edit');

        if ($id_modulo && $nome && is_string($nome) && $permissoes && is_array($permissoes)) {

            // Cria dados para update
            $update = array(
                'nome' => $nome,
            );

            $retorno = $this->cadastro->editModulo($id_modulo, $update);

            if ($retorno) {
                $permissoes_modulo_old = $this->cadastro->get_old_permissoes_modulo($id_modulo);

                foreach ($permissoes_modulo_old as $permissao) {
                    if (($key = array_search($permissao->id_permissao, $permissoes)) !== false) {
                        $modulo_permissao = $this->cadastro->get_modulo_permissao($id_modulo, $permissao->id_permissao);
                        $this->cadastro->edit_modulo_permissao($modulo_permissao->id, array('status' => '1'));
                        unset($permissoes[$key]);
                    } else {
                        $modulo_permissao = $this->cadastro->get_modulo_permissao($id_modulo, $permissao->id_permissao);
                        $this->cadastro->edit_modulo_permissao($modulo_permissao->id, array('status' => '0'));
                    }
                }

                $insert_batch = [];
                foreach ($permissoes as $id_permissao) {
                    $insert_batch[] = array(
                        'id_modulo' => $id_modulo,
                        'id_permissao' => $id_permissao,
                    );
                }
                if ($insert_batch) {
                    $this->cadastro->add_modulo_permissao_lote($insert_batch);
                }

                exit(json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.')));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível atualizar o módulo. Tente novamente mais tarde!')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
     * Função consulta permissoes de um modulo
     * @author Eberson Santos
     * @method GET
     *
     * @param INT $id = ID do modulo
     * @return OBJECT = Permissoes do Modulo
     */
    public function get_permissoes_modulo()
    {
        $id_modulo = $this->input->get('id_modulo');
        if ($id_modulo && is_numeric($id_modulo)) {
            $permissoes = $this->cadastro->get_permissoes_modulo($id_modulo);

            if ($permissoes) {
                exit(json_encode(array('status' => true, 'permissoes' => $permissoes)));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Nenhuma permissão está ativa para este módulo.')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Módulo informado não encontrado.')));
        }
    }

    /**
     * Função altera status do modulo
     * @author Eberson Santos
     * @method POST
     *
     * @param INT $id_modulo = id do modulo
     * @param INT $status = Status do modulo
     */
    public function status_modulo()
    {
        $id_modulo = $this->input->post('id_modulo');
        $status = $this->input->post('status');

        if ($id_modulo && isset($status)) {
            $retorno = $this->cadastro->editModulo($id_modulo, array('status' => $status));

            if ($retorno) {
                exit(json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.')));
            } else {
                exit(json_encode(array('status' => false, 'msg' => 'Não foi possível atualizar o módulo. Tente novamente mais tarde!')));
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    private function sanitizeString($str)
    {
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        $str = preg_replace('/[^a-z0-9]/i', '_', $str);
        $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
        return $str;
    }

    // API DE CONSULTA DE CNPJ
    public function consulta_cnpj($cnpj)
    {
        echo file_get_contents('http://receitaws.com.br/v1/cnpj/' . $cnpj);
    }

    /*
    * TELA DE MONITORAMENTO DAS SOLICITAÇÕES DE EQUIPAMENTOS
    */
    public function solic_eqp()
    {
        $dados['titulo'] = 'Show Tecnologia';
        $dados['solicitacoes'] = $this->logistica->get_all_solicitacoes();

        $this->load->view('fix/header', $dados);
        $this->load->view('gerencia_equip/lista_solicitacoes');
        $this->load->view('fix/footer');
    }

    public function cnpj()
    {
        $n1 = rand(1, 9);
        $n2 = rand(1, 9);
        $n3 = rand(1, 9);
        $n4 = rand(1, 9);
        $n5 = rand(1, 9);
        $n6 = rand(1, 9);
        $n7 = rand(1, 9);
        $n8 = rand(1, 9);
        $n9 = 0;
        $n10 = 0;
        $n11 = 0;
        $n12 = 1;

        $d1 = ($n12 * 2) + ($n11 * 3) + ($n10 * 4) + ($n9 * 5) + ($n8 * 6) + ($n7 * 7) + ($n6 * 8) + ($n5 * 9) + ($n4 * 2) + ($n3 * 3) + ($n2 * 4) + ($n1 * 5);

        $d1 = 11 - (round($d1 - (floor($d1 / 11) * 11)));

        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = ($d1 * 2) + ($n12 * 3) + ($n11 * 4) + ($n10 * 5) + ($n9 * 6) + ($n8 * 7) + ($n7 * 8) + ($n6 * 9) + ($n5 * 2) + ($n4 * 3) + ($n3 * 4) + ($n2 * 5) + ($n1 * 6);

        $d2 = 11 - (round($d2 - (floor($d2 / 11) * 11)));

        if ($d2 >= 10) {
            $d2 = 0;
        }

        $retorno = $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $n10 . $n11 . $n12 . $d1 . $d2;

        return $retorno;
    }

    public function cadastrar_cliente()
    {
        $cliente = $this->input->post('cliente');
        $cartao = $this->input->post('cartao');
        $endereco = $this->input->post('endereco');
        $email = $this->input->post('email');
        $telefone = $this->input->post('telefone');
        $cliente_pessoa = $cliente['pessoa'];

        // Endereço
        $ocorrencia_cep = 0;
        $ocorrencia_rua = 0;
        $ocorrencia_numero_end = 0;
        $ocorrencia_bairro = 0;
        $ocorrencia_cidade = 0;
        $ocorrencia_uf = 0;

        // Email
        $ocorrencia_email = 0;

        // Telefone
        $ocorrencia_ddd = 0;
        $ocorrencia_numero_tel = 0;

        $mensagem = "";

        // GMT
        if (
            !isset($cliente['gmt']) || empty($cliente['gmt'])
            || strlen($cliente['gmt']) > 3
            || strlen($cliente['gmt']) < 2
        ) {
            if ($mensagem != "") {
                $mensagem = $mensagem . ", GMT em Dados";
            } else {
                $mensagem = "GMT em Dados";
            }
        }

        if (isset($cliente['opentech'])) {
            $cliente['opentech'] = 1;
        } else {
            $cliente['opentech'] = 0;
        }

        if (!isset($cliente['nome']) || empty($cliente['nome'])) {
            if ($mensagem != "") {
                $mensagem = $mensagem . ", Nome em Dados";
            } else {
                $mensagem = "Nome em Dados";
            }
        }

        if ($cliente_pessoa == 1) {

            /*
             * if (!isset($cliente['rg']) || empty($cliente['rg'])) {
             * if ($mensagem != "") {
             * $mensagem = $mensagem . ", Número Identidade em Dados";
             * } else {
             * $mensagem = "Número Identidade em Dados";
             * }
             * }
             *
             * if (!isset($cliente['rg_orgao']) || empty($cliente['rg_orgao'])) {
             * if ($mensagem != "") {
             * $mensagem = $mensagem . ", Orgão Expeditor em Dados";
             * } else {
             * $mensagem = "Orgão Expeditor em Dados";
             * }
             * }
             */

            if (
                !isset($cliente['cpf']) || empty($cliente['cpf'])
                || strlen(preg_replace('/[^\d]/', '', $cliente['cpf'])) == 0
            ) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cpf em Dados";
                } else {
                    $mensagem = "Cpf em Dados";
                }
            }

            if (
                !isset($cliente['data_nascimento']) || empty($cliente['data_nascimento'])
                || strlen(preg_replace('/[^\d]/', '', $cliente['data_nascimento'])) == 0
            ) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Data de Nascimento em Dados";
                } else {
                    $mensagem = "Data de Nascimento em Dados";
                }
            }
        } else {
            if (!isset($cliente['razao_social']) || empty($cliente['razao_social'])) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Razão Social em Dados";
                } else {
                    $mensagem = "Razão Social em Dados";
                }
            }

            if (
                !isset($cliente['cnpj']) || empty($cliente['cnpj'])
                || strlen(preg_replace('/[^\d]/', '', $cliente['cnpj'])) == 0
            ) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cnpj em Dados";
                } else {
                    $mensagem = "Cnpj em Dados";
                }
            }
        }
        // Endereço
        $quant_endereco = count($endereco);

        for ($i = 0; $i < $quant_endereco; $i++) {

            if (
                !isset($endereco[$i]['cep']) && $ocorrencia_cep == 0 || empty($endereco[$i]['cep']) && $ocorrencia_cep == 0
                || strlen(preg_replace('/[^\d]/', '', $endereco[$i]['cep'])) == 0 && $ocorrencia_cep == 0
            ) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cep em Endereços";
                    $ocorrencia_cep += 1;
                } else {
                    $mensagem = "Cep em Endereços";
                    $ocorrencia_cep += 1;
                }
            }

            if (!isset($endereco[$i]['rua']) && $ocorrencia_rua == 0 || empty($endereco[$i]['rua']) && $ocorrencia_rua == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Rua em Endereços";
                    $ocorrencia_rua += 1;
                } else {
                    $mensagem = "Rua em Endereços";
                    $ocorrencia_rua += 1;
                }
            }

            if (!isset($endereco[$i]['numero']) && $ocorrencia_numero_end == 0 || empty($endereco[$i]['numero']) && $ocorrencia_numero_end == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Número em Endereços";
                    $ocorrencia_numero_end += 1;
                } else {
                    $mensagem = "Número em Endereços";
                    $ocorrencia_numero_end += 1;
                }
            }

            if (!isset($endereco[$i]['pais'])) {
                if (!isset($endereco[$i]['bairro']) && $ocorrencia_bairro == 0 || empty($endereco[$i]['bairro']) && $ocorrencia_bairro == 0) {
                    if ($mensagem != "") {
                        $mensagem = $mensagem . ", Bairro em Endereços";
                        $ocorrencia_bairro += 1;
                    } else {
                        $mensagem = "Bairro em Endereços";
                        $ocorrencia_bairro += 1;
                    }
                }
            }

            if (!isset($endereco[$i]['uf']) && $ocorrencia_uf == 0 || empty($endereco[$i]['uf']) && $ocorrencia_uf == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Uf em Endereços";
                    $ocorrencia_uf += 1;
                } else {
                    $mensagem = "Uf em Endereços";
                    $ocorrencia_uf += 1;
                }
            }

            if (!isset($endereco[$i]['cidade']) && $ocorrencia_cidade == 0 || empty($endereco[$i]['cidade']) && $ocorrencia_cidade == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cidade em Endereços";
                    $ocorrencia_cidade += 1;
                } else {
                    $mensagem = "Cidade em Endereços";
                    $ocorrencia_cidade += 1;
                }
            }
        }
        // Contatos - Email
        $quant_email = count($email);

        for ($i = 0; $i < $quant_email; $i++) {
            if (!isset($email[$i]['emails']) && $ocorrencia_email == 0 || empty($email[$i]['emails']) && $ocorrencia_email == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", E-mail em Contatos";
                    $ocorrencia_email += 1;
                } else {
                    $mensagem = "E-mail em Contatos";
                    $ocorrencia_email += 1;
                }
            }
        }
        // Contatos - Telefone
        $quant_telefone = count($telefone);

        for ($i = 0; $i < $quant_telefone; $i++) {

            if (!isset($telefone[$i]['ddd']) && $ocorrencia_ddd == 0 || empty($telefone[$i]['ddd']) && $ocorrencia_ddd == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", DDD em Contatos";
                    $ocorrencia_ddd += 1;
                } else {
                    $mensagem = "DDD em Contatos";
                    $ocorrencia_ddd += 1;
                }
            }

            if (
                !isset($telefone[$i]['numero']) && $ocorrencia_numero_tel == 0 || empty($telefone[$i]['numero']) && $ocorrencia_numero_tel == 0
                || strlen(preg_replace('/[^\d]/', '', $telefone[$i]['numero'])) == 0 && $ocorrencia_numero_tel == 0
            ) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Telefone em Contatos";
                    $ocorrencia_numero_tel += 1;
                } else {
                    $mensagem = "Telefone em Contatos";
                    $ocorrencia_numero_tel += 1;
                }
            }
        }

        if (!empty($mensagem)) {
            die(json_encode(array(
                'mensagem' => '<div class="alert alert-error"><p>Faltou Informar: <b>' . $mensagem . '</b></p></div>'
            )));
        } else {
            $retorno = $this->cadastro->cadastrar_cliente($cliente, $cartao, $endereco, $email, $telefone);
            if ($retorno['status'] == 1) {
                die(json_encode(array(
                    'mensagem' => '<div class="alert alert-error"><p><b>Cliente já cadastrado com o nome: ' . $retorno['nome_cliente'] . '</b></p></div>'
                )));
            } else if ($retorno['status'] == 2) {
                if (isset($cliente['consultor']) && $cliente['consultor']) {
                    $userParla = $this->parlacom->login('jailson', 'jcn2417');

                    $cadastrado = $this->parlacom->cadastrarUsuario(array(
                        'phone' => '8332714060',
                        'zip' => '58040490',
                        'state' => 'Paraiba',
                        'city' => 'Guarabira',
                        'address1' => 'Av. Rui Barbosa, 104',
                        'lastname' => 'Show Tecnologia',
                        'firstname' => 'Show Prestadora de Serviços do Brasil Ltda',
                        'email' => 'eduardo@showtecnologia.com',
                        'password' => 'show_' . $retorno['id_cliente'],
                        'login' => 'show_' . $retorno['id_cliente'],
                        'owner' => $cliente['consultor'],
                        'salesrep' => $cliente['consultor'],
                        'userid' => $this->cnpj()
                    ), $userParla->session);
                }

                // CADASTRA GRUPO MASTER DO USUARIO
                $this->cadastro->cad_grupo_byMaster($retorno['id_cliente']);

                die(json_encode(array('mensagem' => '<div class="alert alert-sucess"><p>Cliente <b>' . $retorno['nome_cliente'] . '</b> cadastrado com sucesso</p></div>')));
            } else {
                die(json_encode(array('mensagem' => '<div class="alert alert-error"><p><b>Cliente não cadastrado</b></p></div>')));
            }
        }
    }

    function atualizar_endereco()
    {
        $id_cliente = $this->input->post('idCliente');
        $dados = $this->input->post('endereco');
        if ($dados) {
            $retorno = false;
            foreach ($dados as $key => $dado) {
                $endereco = array(
                    'rua' => $dado['rua'],
                    'numero' => $dado['numero'],
                    'bairro' => $dado['bairro'],
                    'complemento' => $dado['complemento'],
                    'cep' => $dado['cep'],
                    'cidade' => $dado['cidade'],
                    'uf' => $dado['uf'],
                    'latitude' => $dado['latitude'],
                    'longitude' => $dado['longitude'],
                    'data_modificado' => date('Y-m-d'),
                    'hora_modificado' => date('H:i:s'),
                    'data_criado' => date('Y-m-d'),
                    'hora_criado' => date('H:i:s'),
                    'cliente_id' => $id_cliente
                );

                if (array_key_exists('id', $dado)) {
                    if ($key == 0) {
                        $flag = 1;
                        $retorno = $this->cadastro->atualizar_endereco($id_cliente, $endereco, $dado['id'], $flag);
                    } else {
                        $retorno = $this->cadastro->atualizar_endereco($id_cliente, $endereco, $dado['id']);
                    }
                } else {
                    if ($key == 0) {
                        $flag = 1;
                        $retorno = $this->cadastro->atualizar_endereco($id_cliente, $endereco, $dado['id'], false, $flag);
                    } else {
                        $retorno = $this->cadastro->atualizar_endereco($id_cliente, $endereco);
                    }
                }
            }
            if ($retorno) {
                echo json_encode(array('msg' => 'Endereço atualizado com sucesso!', 'status' => 'OK'));
            } else {
                echo json_encode(array('msg' => 'Ocorreu um erro ao atualizar o endereço. Tente novamente!', 'status' => false));
            }
        }
    }

    function atualizar_dados()
    {
        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        $cliente = $this->input->post();
        $retorno = false;
        $datanascimento = implode('-', array_reverse(explode('/', $cliente['data_nascimento'])));
        $datanascimento = empty($datanascimento) ? null : $datanascimento;

        if ($cliente) {
            if ($cliente['cpf'] != '' || $cliente['cpf'] != null) {
                $clientebd = $this->cliente->get(array('id' => $cliente['idCliente']));
                $dados = array(
                    'nome' => $cliente['nome'],
                    'cpf' => $cliente['cpf'],
                    'identidade' => $cliente['identidade'],
                    'orgaoexp' => $cliente['rg_orgao'],
                    'informacoes' => ($this->input->post('empresa') && $this->auth->is_allowed_block('edi_empresa_dados_cliente')) ? $this->input->post('empresa') : $clientebd->informacoes,
                    'data_nascimento' => $datanascimento,
                    //                    'consultor_m2m' => $cliente['consultor'],
                    'id_vendedor' => $cliente['id_vendedor'] ? $cliente['id_vendedor'] : '',
                    'opentech' => empty($cliente['opentech']) ? 0 : 1,
                    'velocidade_via' => empty($cliente['excessoVia']) ? 0 : 1,
                    'habilita_evt_personalizado' => empty($cliente['habilita_evt_personalizado']) ? 0 : 1,

                    'orgao' => $cliente['orgao'],
                    'gmt' => $cliente['gmt'],
                );

                //pegando dados antigos do cliente
                $dados_clientes_antigo = array(
                    'nome'                 => $clientebd->nome,
                    'cpf'                 => $clientebd->cpf,
                    'identidade'         => $clientebd->identidade,
                    'orgaoexp'             => $clientebd->orgaoexp,
                    'informacoes'         => $clientebd->informacoes,
                    'data_nascimento'     => $clientebd->data_nascimento,
                    'id_vendedor'         => $clientebd->id_vendedor,
                    'opentech'             => $clientebd->opentech,
                    'velocidade_via'     => $clientebd->velocidade_via,
                    'habilita_evt_personalizado'     => $clientebd->habilita_evt_personalizado,
                    'orgao'             => $clientebd->orgao,
                    'gmt'                 => $clientebd->gmt
                );


                $retorno = $this->cadastro->atualizar_dados($cliente['idCliente'], $dados);
                if ($retorno) {
                    $dados_novos_formatados = $dados;
                    $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente['idCliente'], 'atualizar', $dados_clientes_antigo, $dados_novos_formatados);

                    if ($dados_novos_formatados['informacoes'] != $dados_clientes_antigo['informacoes']) {
                        $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente['idCliente'], 'atualizar', 'empresa=' . $dados_clientes_antigo['informacoes'], 'empresa=' . $dados_novos_formatados['informacoes']);
                    }
                }
            } else {
                $clientebd = $this->cliente->get(array('id' => $cliente['idCliente']));

                $dados = array(
                    'nome' => $cliente['nome'],
                    'cnpj' => $cliente['cnpj'],
                    'inscricao_estadual' => $cliente['ie'],
                    'razao_social' => $cliente['razao_social'],
                    'informacoes' => ($this->input->post('empresa') && $this->auth->is_allowed_block('edi_empresa_dados_cliente')) ? $this->input->post('empresa') : $clientebd->informacoes,
                    //                    'consultor_m2m' => $cliente['consultor'],
                    'id_vendedor' => $cliente['id_vendedor'] ? $cliente['id_vendedor'] : '',
                    'opentech' => empty($cliente['opentech']) ? 0 : 1,
                    'velocidade_via' => empty($cliente['excessoVia']) ? 0 : 1,
                    'habilita_evt_personalizado' => empty($cliente['habilita_evt_personalizado']) ? 0 : 1,
                    'orgao' => $cliente['orgao'],
                    'gmt' => $cliente['gmt']
                );

                //pegando dados antigos do cliente
                $dados_clientes_antigo = array(
                    'nome'                     => $clientebd->nome,
                    'cnpj'                     => $clientebd->cnpj,
                    'inscricao_estadual'     => $clientebd->inscricao_estadual,
                    'razao_social'             => $clientebd->razao_social,
                    'informacoes'             => $clientebd->informacoes,
                    'id_vendedor'             => $clientebd->id_vendedor,
                    'opentech'                 => $clientebd->opentech,
                    'velocidade_via'         => $clientebd->velocidade_via,
                    'habilita_evt_personalizado'     => $clientebd->habilita_evt_personalizado,
                    'orgao'                 => $clientebd->orgao,
                    'gmt'                     => $clientebd->gmt
                );

                $retorno = $this->cadastro->atualizar_dados($cliente['idCliente'], $dados);
                if ($retorno) {

                    $dados_novos_formatados = $dados;
                    $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente['idCliente'], 'atualizar', $dados_clientes_antigo, $dados_novos_formatados);

                    if ($dados_novos_formatados['informacoes'] != $dados_clientes_antigo['informacoes']) {
                        $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente['idCliente'], 'atualizar', 'empresa=' . $dados_clientes_antigo['informacoes'], 'empresa=' . $dados_novos_formatados['informacoes']);
                    }
                }
            }

            # Atualiza o fuso-horário dos contratos do cliente de acordo com o campo 'GMT' no formulário
            $retornoGmt = $this->contrato->updateContratoVeiculoPorCliente(
                ['fuso_horario' => $cliente['gmt']],
                $cliente['idCliente']
            );

            if ($retornoGmt) {
                $contratobd = $this->contrato->getContratosVeiculos(array('id_cliente' => $cliente['idCliente']));
                $dados_contratos_antigo = array(
                    'fuso_horario'             => empty($contratobd->fuso_horario) ? "-3" :  $contratobd->fuso_horario
                );
                $dados_contrato_formatados = $cliente['gmt'];
                $this->log_shownet->gravar_log($id_user, 'contratos_veiculos', $cliente['idCliente'], 'atualizar', $dados_contratos_antigo, $dados_contrato_formatados);
            }
        }

        if ($retorno && $retornoGmt)
            echo json_encode(array('msg' => 'Dados atualizados com sucesso!', 'status' => 'OK'));
        else
            echo json_encode(array('msg' => 'Ocorreu um erro ao atualizar os dados do cliente. Tente novamente!', 'status' => false));
    }

    function atualizar_cartao()
    {
        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        $id_cliente = $this->input->post('idCliente');


        $dados = $this->input->post('cartao');
        $retorno = false;
        if ($dados) {
            foreach ($dados as $dado) {
                $cartao = array(
                    'numero' => $dado['numero'],
                    'vencimento' => $dado['vencimento'],
                    'codigo' => $dado['codigo'],
                    'nome' => $dado['nome'],
                    'bandeira' => $dado['bandeira'],
                );

                if (array_key_exists('id', $dado)) {
                    $cartao['data_modificado'] = date('Y-m-d');
                    $cartao['hora_modificado'] = date('H:i:s');

                    //pegar dados antigos do cartão.
                    $cartaobd = $this->cadastro->getDadosCartao(array('id' => $dado['id']));

                    $dados_cartao_antigo = array(
                        'numero'        => $cartaobd->numero,
                        'vencimento'    => $cartaobd->vencimento,
                        'codigo'        => $cartaobd->codigo,
                        'nome'          => $cartaobd->nome,
                        'bandeira'      => $cartaobd->bandeira,
                    );


                    $retorno = $this->cadastro->atualizar_cartao($cartao, $dado['id']);

                    $dados_cartao_formatados = $cartao;
                    $this->log_shownet->gravar_log($id_user, 'clientes_cartoes', $dado['id'], 'atualizar', $dados_cartao_antigo, $dados_cartao_formatados);
                } else {
                    $cartao['data_criado'] = date('Y-m-d');
                    $cartao['hora_criado'] = date('H:i:s');
                    $cartao['cliente_id'] = $id_cliente;

                    $retorno = $this->cadastro->atualizar_cartao($cartao);
                    $atualization_id = $this->db->insert_id();

                    $dados_cartao_antigo = "null";
                    $dados_cartao_formatados = $cartao;
                    $this->log_shownet->gravar_log($id_user, 'clientes_cartoes', $atualization_id, 'criar', $dados_cartao_antigo, $dados_cartao_formatados);
                }
            }
            if ($retorno) {
                echo json_encode(array('msg' => 'Cartão atualizado com sucesso!', 'status' => 'OK'));
            } else {
                echo json_encode(array('msg' => 'Ocorreu um erro ao atualizar o cartão. Tente novamente!', 'status' => false));
            }
        }
    }



    function atualizar_impostos()
    {
        $cliente_id = $this->input->post('idCliente');
        $retorno = false;
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        if ($this->input->post()) {
            $impostos = array(
                'cod_servico' => $this->input->post('cod_servico'),
                'descriminacao_servico' => $this->input->post('descriminacao_servico'),
                'IRPJ' => $this->input->post('irpj'),
                'Cont_Social'   => $this->input->post('csll'),
                'PIS'    => $this->input->post('pis'),
                'COFINS'  => $this->input->post('cofins'),
                'ISS'    => $this->input->post('iss'),
            );

            //pegar dados antigos do imposto.
            $impostobd = $this->cadastro->getDadosImpostos(array('id' => $cliente_id));

            $dados_imposto_antigo = array(
                'cod_servico'           => $impostobd->cod_servico,
                'descriminacao_servico' => $impostobd->descriminacao_servico,
                'IRPJ'                  => $impostobd->IRPJ,
                'Cont_Social'           => $impostobd->Cont_Social,
                'PIS'                   => $impostobd->PIS,
                'COFINS'                => $impostobd->COFINS,
                'ISS'                   => $impostobd->ISS
            );
            $dados_imposto_formatados = $impostos;

            $retorno = $this->cadastro->atualizar_impostos($cliente_id, $impostos);
            $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente_id, 'atualizar', $dados_imposto_antigo, $dados_imposto_formatados);
        }

        if ($retorno) {
            echo json_encode(array('dados' => $impostos, 'msg' => 'Impostos atualizados com sucesso!', 'status' => 'OK'));
        } else {
            echo json_encode(array('msg' => 'Ocorreu um erro ao atualizar os impostos. Tente novamente!', 'status' => false));
        }
    }

    function atualizar_permissoes()
    {
        $cliente_id = $this->input->post('idCliente');
        $retorno = false;

        if ($this->input->post()) {
            $id_produto = $this->input->post('id_produto');

            // $permissoes = $this->input->post('permissoes') ? array_values(array_unique($this->input->post('permissoes'))) : null;

            $permissoes = $this->cadastro->get_permissoes_produto($id_produto, 'cod_permissao');
            $permissoes = array_map(function ($o) {
                return $o->cod_permissao;
            }, $permissoes);

            $dados = array(
                'id_produto' => $this->input->post('id_produto'),
                'permissoes' => $permissoes ? json_encode($permissoes) : null,
                'observacoes' => $this->input->post('observacoes')
            );

            $retorno = $this->cadastro->atualizar_permissoes($cliente_id, $dados);
        }
        if ($retorno) {
            echo json_encode(array('msg' => 'Permissões atualizadas com sucesso!', 'status' => 'OK'));
        } else {
            echo json_encode(array('msg' => 'Ocorreu um erro ao atualizar as permissões. Tente novamente!', 'status' => false));
        }
    }

    function integracao_linker()
    {
        $cliente_id = $this->input->post('idCliente');
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        if ($this->input->post()) {
            $dados = array('usuario_linker' => $this->input->post('usuario_linker'), 'senha_linker' => $this->input->post('senha_linker'));

            //pegar dados antigos da integracao.
            $integracaobd = $this->cadastro->getDadosIntegracaoLinker(array('id' => $cliente_id));

            $dados_integracao_antigo = array(
                'usuario_linker' => $integracaobd->usuario_linker,
                'senha_linker'   => $integracaobd->senha_linker
            );
            $dados_integracao_formatados = $dados;


            $retorno = $this->cadastro->integracao_linker($cliente_id, $dados);

            if ($retorno) {
                $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente_id, 'atualizar', $dados_integracao_antigo, $dados_integracao_formatados);
                echo json_encode(array('status' => true, 'msg' => 'Usuário e senha de integração linker atualizados!'));
            } else {
                echo json_encode(array('status' => false, 'msg' => 'Ocorreu um erro ao atualizar, tente novamente!'));
            }
        }
    }

    /**
     * Esta função faz com que seja habilitado a troca periódica de senha para o cliente.
     * Onde os usuários pertencentes a ele deve atualizar a senha a cada 60 dias
     */
    function forcar_atualizacao_senha()
    {
        $cliente_id = $this->input->post('idCliente');
        $forcarTrocaSenha = $this->input->post('forcarTrocaSenha');
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        $dados = array(
            'troca_periodica_senhas' => $forcarTrocaSenha
        );

        //pegar dados antigos da integracao.
        $atualizaSenhabd = $this->cadastro->getDadosForcarAtualizacaoSenha(array('id' => $cliente_id));
        $dados_atualizacao_antigo = array(
            'troca_periodica_senhas' => $atualizaSenhabd->troca_periodica_senhas
        );
        $dados_atualizacao_formatados = $dados;

        $retorno = $this->cadastro->forcar_atualizacao_senha($cliente_id, $dados);

        if ($retorno) {
            $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente_id, 'atualizar', $dados_atualizacao_antigo, $dados_atualizacao_formatados);
            echo json_encode(array('status' => true, 'msg' => 'Troca periódica de senhas atualizada!'));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Ocorreu um erro ao atualizar, tente novamente!'));
        }
    }

    public function atualizar_logotipo_cliente()
    {

        if ($_FILES['logotipo']['size'] == 0) {
            exit(json_encode(
                array(
                    'status' => false,
                    'message' => lang('parametro_enviar_imagem')
                )
            ));
        }

        if ($_FILES['logotipo']['error'] == 1) {
            exit(json_encode(
                array(
                    'status' => false,
                    'message' => lang('arquivo_excede_tamanho')
                )
            ));
        }

        $id_cliente = $this->input->post('idCliente');

        $resposta = array(
            'status' => false,
            'message' => lang('falha_salvar_logotipo')
        );

        if ($path = $this->salvaLogotipoCliente()) {
            $dados = array('logotipo' => $path);
            if ($this->cliente->atualizar($id_cliente, $dados)) {
                //para registro de log
                $id_user = $this->auth->get_login_dados('user');
                $id_user = (int) $id_user;

                //pegar dados antigos do cliente.
                $clientebd = $this->cliente->get(array('id' => $id_cliente));

                if ($clientebd) {
                    $dados_cliente_antigo = array(
                        'logotipo'       => $clientebd->logotipo
                    );
                }

                $this->log_shownet->gravar_log($id_user, 'cad_clientes', $id_cliente, 'atualizar', $dados_cliente_antigo ? $dados_cliente_antigo : 'null', $dados);

                $resposta = array(
                    'status' => "Success",
                    'message' => lang('sucesso_salvar_logotipo'),
                    'logotipo' => base_url($path)
                );
            }
        }

        exit(json_encode($resposta));
    }

    /**
     * Salva a logo do cliente na pasta
     */
    public function salvaLogotipoCliente()
    {
        $arquivo = $_FILES['logotipo'];
        $arquivoFotoNome = $arquivo['name'];
        $arquivoFotoTmpNome = $arquivo['tmp_name'];
        $tipo_arquivo = explode('/', $arquivo['type'])[1];

        //Cria o diretório (0777 é padrão do mkdir)
        $dir = 'uploads/logotipo_clientes';
        if (!file_exists($dir)) mkdir($dir, 0777, true);

        //Cria o caminho com o id do usuário
        $file = $dir . '/' . $arquivoFotoNome;

        //Salva o arquivo
        if (move_uploaded_file($arquivoFotoTmpNome, $file)) return $file;
        else return false;
    }

    public function atualizar_cliente($cliente_id)
    {
        $this->load->model('usuario');

        if (!empty($_FILES['image']) && $_FILES['image']) {
            uploadFTP($_FILES['image'], 'particao_ftp/uploads/logo_clientes', $cliente_id, 'logo', $cliente_id);
        }

        $taxes = array(
            'irpj'   => $this->input->post('irpj')   ? $this->input->post('irpj')   : 0,
            'csll'   => $this->input->post('csll')   ? $this->input->post('csll')   : 0,
            'pis'    => $this->input->post('pis')    ? $this->input->post('pis')    : 0,
            'cofins'  => $this->input->post('cofins')  ? $this->input->post('cofins')  : 0,
            'iss'    => $this->input->post('iss')    ? $this->input->post('iss')    : 0,
        );

        $this->fatura->updateForTaxe($taxes, $cliente_id);
        $cliente = $this->input->post('cliente');
        $cliente['id_produto'] = $this->input->post('id_produto');

        $cliente['planos_nomes'] = json_decode($this->input->post('planos_nomes'));

        $permissoes = $this->input->post('permissoes') ? array_values(array_unique($this->input->post('permissoes'))) : null;
        $cliente['permissoes'] = $permissoes ? json_encode($permissoes) : null;

        $cliente['observacoes'] = $this->input->post('observacoes');
        $cartao = $this->input->post('cartao');
        $endereco = $this->input->post('endereco');
        $email = $this->input->post('email');
        $telefone = $this->input->post('telefone');
        $consultor = $this->input->post('consultor');
        $vendedor = $this->input->post('id_vendedor');
        $irpj = $this->input->post('irpj');
        $cont_social = $this->input->post('csll');
        $pis = $this->input->post('pis');
        $cofins = $this->input->post('cofins');
        $iss = $this->input->post('iss');
        $ip_central = $this->input->post('ip_central');
        $porta_central = $this->input->post('porta_central');
        $opentech = 0;
        $excessoVia = 0;

        if ($this->input->post('opentech'))
            $opentech = 1;
        if ($this->input->post('excessoVia'))
            $excessoVia = 1;

        if ($this->input->post('emailAtendente'))
            $this->usuario->vincularUsuarioComClienteSim2m($this->input->post('emailAtendente'), $this->input->post('id_cliente'));

        if ($this->input->post('emailsVendedor'))
            $this->usuario->vincularVendedorComClienteSim2m($this->input->post('emailsVendedor'), $this->input->post('id_cliente'));

        $retorno = $this->cadastro->atualizar_cliente($cliente_id, $cliente, $cartao, $endereco, $email, $telefone, $consultor, $vendedor, $irpj, $cont_social, $pis, $cofins, $iss, $opentech, $excessoVia, $ip_central, $porta_central);

        if ($retorno['status'] == 1) {
            die(json_encode(array(
                'mensagem' => '<div class="alert alert-sucess"><p>Dados do cliente <b>' . $retorno['nome_cliente'] . '</b> atualizados com sucesso</p></div>'
            )));
        } else {
            die(json_encode(array(
                'mensagem' => '<div class="alert alert-error"><p>Dados do cliente <b>' . $retorno['nome_cliente'] . '</b> não atualizados</p></div>'
            )));
        }
    }

    public function veiculos_anterior($page = 0, $per_page = 20)
    {
        if ($this->input->get()) {
            $coluna = $this->input->get('coluna');
            $palavra = $this->input->get('palavra');
            $config['per_page'] = 9999999;
            $dados['per_page'] = 9999999;
        } else {
            $coluna = array();
            $palavra = array();
            $config['per_page'] = $per_page;
            $dados['per_page'] = $per_page;
        }

        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate" style="float: right; margin-top: -25px;"><ul class="pagination selected">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="selected"><a style="color: #27a9e3"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primeira';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['base_url'] = site_url('cadastros/veiculos/');
        $config['reuse_query_string'] = FALSE;

        $config['total_rows'] = 99999; // Atualize esta parte para o valor correto

        $this->pagination->initialize($config);

        $dados['clientes'] = $this->cliente->get_veiculos_gestor($coluna, $palavra, $page, $config['per_page']);
        $dados['dados'] = $dados;
        $dados['page'] = $page;
        $dados['config'] = $config;
        $dados['total_rows'] = $config['total_rows'];
        $dados['per_page'] = $config['per_page']; // Passa a variável $per_page para a view

        $dados['encontrou_resultados'] = count($dados['clientes']) > 0;


        $dados['titulo'] = 'Show Tecnologia';
        $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/veiculos'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('veiculos/cadastro');
        $this->load->view('fix/footer_NS');
    }

    public function listagem_v()
    {

        $funcionarios = $this->cliente->get_veiculos_gestor(array(), array(), 1, 100);

        $data = [];
        $x = 0;
        // 'code, placa, id_usuario, code_cliente, serial, veiculo'
        foreach ($funcionarios as $funcionario) {
            $data[$x] =
                [
                    "codigo" => $funcionario->code,
                    "placa" => $funcionario->placa,
                    "cliente" => ucwords(strtolower($funcionario->cliente)),
                    "code_cliente" => $funcionario->code_cliente,
                    "id_usuario" => $funcionario->id_usuario,
                    "serial" => $funcionario->serial,
                    "veiculo" => $funcionario->veiculo,
                ];
            $x++;
        }

        echo json_encode($data);
    }

    public function listagem_busca()
    {
        $coluna = $this->input->post('coluna');
        $palavra = $this->input->post('palavra');

        $funcionarios = $this->cliente->get_veiculos_gestor($coluna, $palavra, 1,5000);

        $data = [];
        $x = 0;
        foreach ($funcionarios as $funcionario) {
            $data[$x] =
                [
                    "codigo" => $funcionario->code,
                    "placa" => $funcionario->placa,
                    "cliente" => ucwords(strtolower($funcionario->cliente)),
                    "code_cliente" => $funcionario->code_cliente,
                    "id_usuario" => $funcionario->id_usuario,
                    "serial" => $funcionario->serial,
                    "veiculo" => $funcionario->veiculo,
                ];
            $x++;
        }

        echo json_encode($data);
    }

    /**
     * Função carrega view de veiculos
     * @author Lucas Henrique
     */
    public function veiculos() {
        $this->auth->is_allowed('cad_veiculos');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $dados['titulo'] = 'Veículos';
        $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/veiculos'));

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('veiculos/cadastronv');
        $this->load->view('fix/footer_NS');
    }

    /**
     * Função carrega listagem de veiculos
     * @author Lucas Henrique
     */
    public function listarVeiculos()
    {
        try {
            $startRow = $this->input->post('startRow');
            $endRow = $this->input->post('endRow');
            $coluna = $this->input->post('coluna');
            $valor = $this->input->post('valor');

            $limit = $endRow - $startRow;
            $offset = $startRow;

            $funcionarios = $this->cliente->get_veiculos_gestor_new($coluna, $valor, $offset, $limit);
            $qtd_funcionarios = $this->cliente->count_veiculos_gestor($coluna, $valor);

            if ($funcionarios && $qtd_funcionarios) {
                $data = [];
                $x = 0;

                foreach ($funcionarios as $funcionario) {
                    $data[$x] =
                        [
                            "codigo" => $funcionario->code,
                            "placa" => $funcionario->placa,
                            "cliente" => ucwords(strtolower($funcionario->cliente)),
                            "code_cliente" => $funcionario->code_cliente,
                            "id_usuario" => $funcionario->id_usuario,
                            "serial" => $funcionario->serial,
                            "veiculo" => $funcionario->veiculo,
                        ];
                    $x++;
                }

                echo json_encode(array(
                    "success" => true,
                    "status" => 200,
                    "rows" => $data,
                    "lastRow" => $qtd_funcionarios
                ));
            } else {
                echo json_encode(array(
                    "success" => false,
                    "status" => 404,
                    "message" => 'Dados não encontrados para os parâmetros informados'
                ));
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                "success" => false,
                "status" => 500,
                "message" => 'Não foi possível realizar a listagem de veículos'
            ));
        }
    }


    public function veiculos_old($page = 0, $per_page = 20)
    {
        if ($this->input->get()) {
            $coluna = $this->input->get('coluna');
            $palavra = $this->input->get('palavra');
            $config['per_page'] = 9999999;
            $dados['per_page'] = 9999999;
        } else {
            $coluna = array();
            $palavra = array();
            $config['per_page'] = $per_page;
            $dados['per_page'] = $per_page;
        }
        $idUser = $this->auth->get_login_dados('user');
		$permissoes = $this->auth->listar_permissoes_usuario($idUser);

        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate" style="float: right; margin-top: -25px;"><ul class="pagination selected">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="selected"><a style="color: #27a9e3"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primeira';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['base_url'] = site_url('cadastros/veiculos/');
        $config['reuse_query_string'] = FALSE;

        $config['total_rows'] = 99999; // Atualize esta parte para o valor correto

        $this->pagination->initialize($config);

        $dados['clientes'] = $this->cliente->get_veiculos_gestor($coluna, $palavra, $page, $config['per_page']);
        $dados['dados'] = $dados;
        $dados['page'] = $page;
        $dados['config'] = $config;
        $dados['total_rows'] = $config['total_rows'];
        $dados['per_page'] = $config['per_page']; // Passa a variável $per_page para a view

        $dados['encontrou_resultados'] = count($dados['clientes']) > 0;

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $dados['titulo'] = 'Show Tecnologia';
        if(in_array('cad_veiculos', $permissoes)){
            $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/veiculos'));
            $this->load->view('new_views/fix/header', $dados);
            $this->load->view('veiculos/cadastronv');
            $this->load->view('fix/footer_NS');
        }else{
            $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/veiculos'));
            $this->load->view('new_views/fix/header', $dados);
            $this->load->view('erros/403');
            $this->load->view('fix/footer_NS');
        }
  
    }
    // public function loadVeiculos(){
    //     $coluna = $this->input->get('coluna');
    //     $palavra = $this->input->get('palavra');
    //     $data['resultados'] = $this->cliente->get_veiculos_gestor($coluna, $palavra);
    //     echo($data['resultados']);
    //     $this->load->view('veiculos/cadastro', $data);
    // }



    public function view_veiculo($code)
    {
        $dados['veiculo'] = $this->veiculo->get_veiculos_view($code);
        $dados['veiculo']->data_instalacao = $this->veiculo->get_data_instalacao($dados['veiculo']->placa);
        $dados['usuarios'] = $this->usuario->get_usuario_gestor($code);
        $dados['titulo'] = 'Show Tecnologia';
        $dados['code'] = $code;

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('veiculos/view_veiculo');
        $this->load->view('fix/footer_NS');
    }

    public function view_veiculo_editar($code)
    {
        $dados['veiculo'] = $this->veiculo->get_veiculos_view($code);
        $dados['veiculo']->data_instalacao = $this->veiculo->get_data_instalacao($dados['veiculo']->placa);
        $dados['usuarios'] = $this->usuario->get_usuario_gestor($code);
        $dados['titulo'] = 'Show Tecnologia';
        $dados['code'] = $code;

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('veiculos/view_veiculo_editar');
        $this->load->view('fix/footer_NS');
    }


    public function atualizar_veiculo($code)
    {
        $dados['veiculo'] = $this->veiculo->get_veiculos_view($code);

        if ($this->input->post()) {
            $usuario_email = $this->auth->get_login('admin', 'email');

            if (($this->input->post('hodometro')) != $dados['veiculo']->hodometro && $this->input->post('hodometro') != '') {
                $this->set_hodometro($this->input->post('serial'), $this->input->post('hodometro'));
            }

            if (($this->input->post('horimetro')) != $dados['veiculo']->horimetro && $this->input->post('horimetro') != '') {
                $this->set_horimetro($this->input->post('serial'), $this->input->post('horimetro'));
            }

            $edit = $this->veiculo->atualizar(array(
                'code' => $code
            ), $this->input->post(), $usuario_email);

            if ($edit) {
                $acao = array(
                    'data_criacao' => date('Y-m-d H:i:s'),
                    'usuario' => $usuario_email,
                    'placa' => $this->input->post("placa"),
                    'acao' => 'O usuário ' . $usuario_email . ' atualizou o veículo de placa ' . $this->input->post("placa")
                );
                $ret = $this->log_veiculo->add($acao);

                echo json_encode(
                    array(
                        'status' => 'success',
                        'mensagem' => 'Veiculo atualizado com sucesso!'
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'status' => 'false',
                        'mensagem' => 'Não foi possível atualizar o veículo!'
                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    'status' => 'false',
                    'mensagem' => 'Formulário não enviado!'
                )
            );
        }
    }

    public function atualizar_veiculo_ns($code)
    {
        $dados['veiculo'] = $this->veiculo->get_veiculos_view($code);

        if ($this->input->post()) {
            $usuario_email = $this->auth->get_login('admin', 'email');

            // if (($this->input->post('hodometro')) != $dados['veiculo']->hodometro && $this->input->post('hodometro') != '') {
            //     $this->set_hodometro($this->input->post('serial'), $this->input->post('hodometro'));
            // }

            // if (($this->input->post('horimetro')) != $dados['veiculo']->horimetro && $this->input->post('horimetro') != '') {
            //     $this->set_horimetro($this->input->post('serial'), $this->input->post('horimetro'));
            // }

            $edit = $this->veiculo->atualizar(array(
                'code' => $code
            ), $this->input->post(), $usuario_email);

            if ($edit) {
                $acao = array(
                    'data_criacao' => date('Y-m-d H:i:s'),
                    'usuario' => $usuario_email,
                    'placa' => $this->input->post("placa"),
                    'acao' => 'O usuário ' . $usuario_email . ' atualizou o veículo de placa ' . $this->input->post("placa")
                );
                $ret = $this->log_veiculo->add($acao);

                echo json_encode(
                    array(
                        'status' => 'success',
                        'mensagem' => 'Veiculo atualizado com sucesso!'
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'status' => 'false',
                        'mensagem' => 'Não foi possível atualizar o veículo!'
                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    'status' => 'false',
                    'mensagem' => 'Formulário não enviado!'
                )
            );
        }
    }

    public function cadastro_veiculo($code)
    {
        $dados['veiculo'] = $this->veiculo->get_veiculos_view($code);
        $dados['veiculo']->data_instalacao = $this->veiculo->get_data_instalacao($dados['veiculo']->placa);
        $dados['usuarios'] = $this->usuario->get_usuario_gestor($code);
        $dados['code'] = $code;

        $this->load->view('veiculos/view_veiculo', $dados);
    }

    public function cadastro_veiculo_editar($code)
    {
        $dados['veiculo'] = $this->veiculo->get_veiculos_view($code);
        $dados['veiculo']->data_instalacao = $this->veiculo->get_data_instalacao($dados['veiculo']->placa);
        $dados['code'] = $code;

        $this->load->view('veiculos/view_veiculo_editar', $dados);
    }

    private function set_hodometro($serial, $valor)
    {
        $metros = ($valor * 1000); // TRANSFORMA KM EM METROS
        if (substr($serial, 0, 3) == "205" || substr($serial, 0, 2) == "90") {
            $this->equipamento->put_comandos_suntech($serial, 'ODOMETRO=' . $metros);
        } elseif (substr($serial, 0, 3) == 'SCO') {
            $this->equipamento->continental($serial, $valor);
        } elseif (substr($serial, 0, 2) == 'QL') {
            $cmd = 'AT+GTCFG=gv55,gv55,gv55n,1,' . $valor . ',,,7F,2,0,18EF,,1,0,300,0,,0,1,1C,0,0003$';
            $this->equipamento->put_comandos_queclink($serial, $cmd);
        } elseif (substr($serial, 0, 3) == 'QRT') {
            $this->equipamento->put_comandos_quanta($serial, 'ODOMETRO=' . $valor);
        } else {
            $this->equipamento->put_comandos_mxt($serial, 'ODOMETRO=' . $valor);
        }
    }

    private function set_horimetro($serial, $valor)
    {
        $minutos = ($valor * 60); // TRANSFORMA HORAS EM MINUTOS
        if (substr($serial, 0, 3) == "205" || substr($serial, 0, 2) == "90") {
            $this->equipamento->put_comandos_suntech($serial, 'HORIMETRO=' . $minutos);
        } elseif (substr($serial, 0, 3) == 'SCO') {
            $this->equipamento->continental($serial, $minutos);
        } elseif (substr($serial, 0, 2) == 'QL') {
            $cmd = 'AT+GTCFG=gv55,gv55,gv55n,1,' . $valor . ',,,7F,2,0,18EF,,1,0,300,0,,0,1,1C,0,0003$';
            $this->equipamento->put_comandos_queclink($serial, $cmd);
        } elseif (substr($serial, 0, 3) == 'QRT') {
            $this->equipamento->put_comandos_quanta($serial, 'HORIMETRO=' . $minutos);
        } else {
            $this->equipamento->put_comandos_mxt($serial, 'HORIMETRO=' . $minutos);
        }
    }

    /*
     * Chamada da tela de enviar as linha para mikrotik
     * Luciano - 27/07/2014
     */
    public function linhas()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/linhas/cadastro_linhas'));
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de linhas';
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('servicos/cad_linhas');
        $this->load->view('fix/footer_NS');
    }

    public function listar_banners()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Banners';
        $dados['banners'] = $this->arquivo->getArquivo("pasta = 'banners'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/banners/lista_banners');
        $this->load->view('fix/footer4');
    }

    public function banners()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Banners';
        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/banners/cad_banners');
        $this->load->view('fix/footer4');
    }

    public function enviar_banner()
    {
        $this->load->model('send_filetxt');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->send_filetxt->envia_http($this->input->post());
        }
    }

    public function cad_banners()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/banners');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'banners') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse banner já foi cadastrado!</div>');
                    redirect('cadastros/banners');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/banners', 'jpg|png|jpeg')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoBanner($descricao, $nome_arquivo, $path);
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Banner cadastrado com sucesso!</div>');
                        redirect('cadastros/banners');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Banner!</div>');
                        redirect('cadastros/banners');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/banners');
            }
        }
    }

    public function editar_banners($id_banner)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Folhetos';
        $dados['dados_banner'] = $this->ashownett->get("id = '$id_banner'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_banner']))
            $this->load->view('rh/banners/edit_banners');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_banners($id_banner)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/banners', 'jpg|png|jpeg')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_banner);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'banners', $id_banner);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Banner alterado com sucesso!</div>');
                redirect("cadastros/editar_banners/$id_banner");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Banner!</div>');
                redirect("cadastros/editar_banners/$id_banner");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'banners', $id_banner);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_banners/$id_banner");
        }
    }

    private function uploadApresentacao($pasta)
    {
        $config['upload_path'] = $pasta;
        $config['allowed_types'] = 'pptx|ppt|pdf|doc|docx|xlsx|xls|png|jpg';
        $config['max_size'] = '0';
        $config['max_width'] = '0';
        $config['max_height'] = '0';
        $config['encrypt_name'] = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            $data = $this->upload->data();
            return $data;
        } else {
            $error = array(
                'error' => $this->upload->display_errors()
            );
            return false;
        }
    }

    private function uploadArquivos($pasta, $extencao = null)
    {
        $extencaoaux = 'pptx|ppt|pdf|doc|docx|xlsx|xls|XLSL|XLSB|txt|png|jpg';
        if ($extencao != null) {
            $extencaoaux = $extencao;
        }
        //die(json_encode(array($extencaoaux, $extencao)));
        $config['upload_path'] = $pasta;
        $config['allowed_types'] = $extencaoaux;
        $config['max_size'] = '0';
        $config['max_width'] = '0';
        $config['max_height'] = '0';
        $config['encrypt_name'] = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('arquivo')) {
            $data = $this->upload->data();
            return $data;
        } else {
            $error = array(
                'error' => $this->upload->display_errors()

            );
            //die(json_encode($error));
            return false;
        }
    }

    public function excluir_banner($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Banner deletado com sucesso!";
        }
    }

    public function listar_comunicados()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Comunicados';
        $dados['comunicados'] = $this->arquivo->getComunicados();

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/comunicados/lista_comunicados');
        $this->load->view('fix/footer4');
    }

    public function comunicados()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Comunicados';
        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/comunicados/cad_comunicados');
        $this->load->view('fix/footer4');
    }

    public function enviar_comunicado()
    {
        $this->load->model('send_filetxt');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->send_filetxt->envia_http($this->input->post());
        }
    }

    public function cad_comunicado()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;
        $usuario = $this->auth->get_login_dados();
        $id = $usuario['user'];

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe o comunicado!</div>');
            redirect('cadastros/comunicados');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroComunicado($descricao) == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse comunicado já foi cadastrado!</div>');
                    redirect('cadastros/comunicados');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/comunicados', 'pdf')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->cadComunicado($descricao, $nome_arquivo, $path, $id);
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Comunicado cadastrado com sucesso!</div>');
                        redirect('cadastros/comunicados');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Comunicado, permitido apenas arquivos com extens&atilde;o pdf!</div>');
                        redirect('cadastros/comunicados');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/comunicados');
            }
        }
    }

    public function excluirComunicado($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        $this->db->where('id', $id);
        $this->db->delete('arquivos');

        $this->db->where('id_arquivo', $id);

        if ($this->db->delete('cad_comunicados')) {
            echo "Comunicado deletado com sucesso!";
        }
    }

    public function ViewEditarComunicado($id_comunicado)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Cominucado';
        $dados['comunicados'] = $this->cadastro->getComunicado(array(
            'id' => $id_comunicado
        ));

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['comunicados']))
            $this->load->view('rh/comunicados/edit_comunicados');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_comunicado($id_comunicado)
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $ativo = $this->input->post('ativo');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;
        $usuario = $this->auth->get_login_dados();
        $id = $usuario['user'];

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe o comunicado!</div>');
            redirect('cadastros/comunicados');
        } else {

            if ($arquivo) {

                if ($dados = $this->uploadArquivos('./uploads/comunicados', 'pdf')) {
                    $nome_arquivo = $dados['file_name'];
                    $path = $dados['full_path'];
                    $arquivo_enviado = true;
                }

                if ($arquivo_enviado) {
                    $retorno = $this->cadastro->editComunicado($descricao, $nome_arquivo, $path, $id, $id_comunicado, $ativo);
                    // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                    $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Comunicado alterado com sucesso!</div>');
                    redirect('cadastros/comunicados');
                } else {
                    // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Comunicado, permitido apenas arquivos com extens&atilde;o pdf!</div>');
                    redirect('cadastros/comunicados');
                }
            } else {
                $retorno = $this->cadastro->editComunicado($descricao, '', '', $id, $id_comunicado, $ativo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Comunicado alterado com sucesso!</div>');
                redirect("cadastros/ViewEditarComunicado/$id_comunicado");
            }
        }
    }

    public function listar_sobreaempresa()
    {
        $dados['msg'] = '';
        $dados['titulo'] = lang('cadastro_sobre_a_empresa');
        $dados['sobre'] = $this->ashownett->getDados('cad_sobre_empresa');

        $this->load->view('fix/header-new', $dados);
        $this->load->view('ashownet/sobreaempresa/lista_sobre');
        $this->load->view('fix/footer_new');
    }

    public function ViewEditarSobre($id)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Sobre a empresa';
        $dados['sobre'] = $this->ashownett->getDadosEdit('cad_sobre_empresa', array(
            'id' => $id
        ));

        $this->load->view('fix/header-new', $dados);
        if (!empty($dados['sobre']))
            $this->load->view('ashownet/sobreaempresa/edit_sobre');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer_new');
    }

    public function edit_sobre($id)
    {
        $titulo = $this->input->post('titulo');
        $descricao = $this->input->post('sobre');
        $missao = $this->input->post('missao');
        $visao = $this->input->post('visao');
        $valores = $this->input->post('valores');

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe algo sobre a empresa!</div>');
            // redirect('cadastros/comunicados');
        } else {

            $retorno = $this->ashownett->editDados($titulo, $descricao, $missao, $visao, $valores, $id, 'cad_sobre_empresa');
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucesso!</div>');
            redirect("cadastros/ViewEditarSobre/$id");
        }
    }

    /*
    * CADASTRA UM NOVO CONTATO CORPORATIVO
    */
    public function cadContatoCorporativo()
    {
        $dados = $this->input->post();

        if ($dados['tipo'] == "MATRIZ" || $dados['tipo'] == "FILIAIS") {
            $dados['loja'] = $dados['tipo'];
            unset($dados['tipo']);
        }

        $cadastrou = $this->cadastro->cadContatoCorporativo($dados);
        if ($cadastrou) {
            echo json_encode(array('success' => true, 'msg' => lang('cadastro_contato_corporativo_sucesso')));
        } else {
            echo json_encode(array('success' => false, 'msg' => lang('cadastro_contato_corporativo_falhou')));
        }
    }

    /*
    * LISTA OS DADOS DE UM CONTATO CORPORATIVO
    */
    public function listDadosContatoCorporativo()
    {
        $id = $this->input->post('id');
        $tipo = $this->input->post('tipo');
        $select = '*';

        if ($tipo == "Atendimento a Clientes" || $tipo == "Projetos Dedicados") $select = 'id, titulo, descricao';
        else $select = 'id, cidade, uf, complemento, endereco, numero, bairro, cep, complemento, telefone, cnpj';

        $contato = $this->cadastro->getContatoCorporativo($select, array('id' => $id))[0];
        if ($contato && count($contato) > 0)
            echo json_encode(array('success' => true, 'retorno' => $contato));
        else
            echo json_encode(array('success' => false, 'msg' => lang('contato_nao_possivel_carregar')));
    }

    /*
    * EDITA OS DADOS DE UM CONTATO CORPORATIVO
    */
    public function editContatoCorporativo($id)
    {
        $dados = $this->input->post();

        if ($this->cadastro->editContatoCorporativo($id, $dados))
            echo json_encode(array('success' => true, 'msg' => lang('contato_alterado')));
        else
            echo json_encode(array('success' => false, 'msg' => lang('contato_nao_alterado')));
    }

    /*
    * EXCLUI UM CONTATO CORPORATIVO
    */
    public function excluir_contatos_corporativos($id)
    {
        if ($this->cadastro->excluirContatoCorporativoById($id))
            echo json_encode(array('success' => true, 'msg' => lang('contato_corporativo_excluido')));
        else
            echo json_encode(array('success' => false, 'msg' => lang('contato_corporativo_nao_excluido')));
    }

    public function listar_folhetos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Folhetos';
        $dados['folhetos'] = $this->arquivo->getArquivo("pasta = 'folhetos'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/folhetos/lista_folhetos');
        $this->load->view('fix/footer4');
    }

    public function folhetos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Folhetos';
        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/folhetos/cad_folhetos');
        $this->load->view('fix/footer4');
    }

    public function excluir_folheto($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();
        //die(json_encode(array($file)));
        $caminho = './uploads/folhetos/' . $file->file;
        //unlink("$file->path");
        unlink($caminho);

        if ($this->arquivo->excluirArquivoById($id)) {
            echo json_encode(array('success' => true, 'msg' => "Folheto excluido com sucesso"));
        } else {
            echo json_encode(array('success' => false, 'msg' => "Error ao excluir folheto"));
        }
    }

    public function cad_folhetos()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('ashownet/empresa_folhetos');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'folhetos') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse folheto já foi cadastrado!</div>');
                    redirect('ashownet/empresa_folhetos');
                } else {

                    if ($dados = $this->uploadArquivos('./uploads/folhetos', 'pdf')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoFolheto($descricao, $nome_arquivo, $path);
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Folheto cadastrado com sucesso!</div>');
                        redirect('ashownet/empresa_folhetos');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Folheto!</div>');
                        redirect('ashownet/empresa_folhetos');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('ashownet/empresa_folhetos');
            }
        }
    }

    public function editar_folheto($id_folheto)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Folhetos';
        $dados['dados_folheto'] = $this->ashownett->get("id = '$id_folheto'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_folheto']))
            $this->load->view('rh/folhetos/edit_folhetos');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_folheto($id_folheto)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/folhetos', 'pdf')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_folheto);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'folhetos', $id_folheto);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Folheto alterado com sucesso!</div>');
                redirect("cadastros/editar_folheto/$id_folheto");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Folheto!</div>');
                redirect("cadastros/editar_folheto/$id_folheto");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'folhetos', $id_folheto);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_folheto/$id_folheto");
        }
    }

    public function listar_apresentacoesById()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Apresentações';
        $dados['apresentacoes'] = $this->arquivo->getArquivoApresentacao("pasta = 'apresentacoes'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/apresentacoes/lista_apresentacoes');
        $this->load->view('fix/footer4');
    }

    public function listar_apresentacoes()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Apresentações';
        $dados['apresentacoes'] = $this->arquivo->getApresentacao();

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/apresentacoes/lista_apresentacoes');
        $this->load->view('fix/footer4');
    }

    public function apresentacao()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Apresentação';

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/apresentacoes/cad_apresentacoes');
        $this->load->view('fix/footer4');
    }

    public function cad_apresentacao()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/apresentacao');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'cad_apresentacao', '') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa apresentação já foi cadastrada!</div>');
                    redirect('cadastros/apresentacao');
                } else {
                    $dataAtutal = date("Y-m-d H:i:s");
                    $dadosApresentacao = array(
                        'descricao' => $descricao,
                        'data_criacao' => $dataAtutal
                    );

                    $this->db->insert('showtecsystem.cad_apresentacao', $dadosApresentacao);
                    $last_id = $this->db->insert_id();

                    $data = [];

                    $count = count($_FILES['arquivo']['name']);
                    $b = 1;
                    for ($i = 0; $i < $count; $i++) {

                        if (!empty($_FILES['arquivo']['name'][$i])) {

                            $_FILES['file']['name'] = $_FILES['arquivo']['name'][$i];
                            $_FILES['file']['type'] = $_FILES['arquivo']['type'][$i];
                            $_FILES['file']['tmp_name'] = $_FILES['arquivo']['tmp_name'][$i];
                            $_FILES['file']['error'] = $_FILES['arquivo']['error'][$i];
                            $_FILES['file']['size'] = $_FILES['arquivo']['size'][$i];

                            if ($dados = $this->uploadApresentacao('./uploads/apresentacoes')) {
                                $nome_arquivo = $dados['file_name'];
                                $path = $dados['full_path'];
                                $arquivo_enviado = true;
                            }

                            $this->cadastro->digitalizacaoApresentacao($last_id, $descricao, $nome_arquivo, $path, $b, 'cad_apresentacao_arquivos');
                        }
                        $b++;
                    }

                    if ($arquivo_enviado) {
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Apresentação cadastrada com sucesso!</div>');
                        redirect('cadastros/apresentacao');
                    } else {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar apresentação!</div>');
                        redirect('cadastros/apresentacao');
                    }
                }
            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/apresentacao');
            }
        }
    }

    public function excluir_apresentacao($id)
    {

        $query = $this->db->query("SELECT id_apresentacao, path FROM cad_apresentacao_arquivos
        WHERE id_apresentacao = '$id'");

        $query = $this->db->query("SELECT id_apresentacao, path FROM cad_apresentacao_arquivos
        WHERE id_apresentacao ='$id'");

        foreach ($query->result_array() as $row) {
            if ($row['path'] != "") {
                unlink("$row[path]");
            }
        }

        if ($this->ashownett->excluirApresentacaoById($id, 'cad_apresentacao')) {
            echo "Apresentação deletada com sucesso!";
        }
    }

    public function excluir_apresentacao_imagem($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('cad_apresentacao_arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoApresentacaoById($id)) {
            echo "Imagem deletada com sucesso!";
        }
    }

    public function editar_apresentacao($id)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Apresentação';
        $dados['apresentacao'] = $this->cadastro->getApresentacao('cad_apresentacao', array(
            'id' => $id
        ));
        $dados['apresentacoes'] = $this->arquivo->getApresentacaoEditar("cad_apresentacao_arquivos", "id_apresentacao = '$id'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['apresentacao']))
            $this->load->view('rh/apresentacoes/edit_apresentacao');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_apresentacao($id)
    {
        $descricao = $this->input->post('descricao');

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe uma descrição!</div>');
            redirect("cadastros/editar_apresentacao/$id");
        } else {
            $retorno = $this->cadastro->editApresentacao($descricao, $id, 'cad_apresentacao');
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Apresentação alterada com sucesso!</div>');
            redirect("cadastros/editar_apresentacao/$id");
        }
    }

    public function politicas_formularios()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Políticas e Formalários';
        $dados['assuntos'] = $this->ashownett->getAssuntos('cad_assuntos');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/politicaseformularios/cad_pf');
        $this->load->view('fix/footer4');
    }

    public function cad_assuntos()
    {
        $assunto = $this->input->post('assunto');

        if ($this->cadastro->verificaCadastroAssunto($assunto, 'cad_assuntos') == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse assunto já foi cadastrado!</div>');
            redirect('cadastros/politicas_formularios');
        } else {

            $retorno = $this->cadastro->cadAssunto($assunto, 'cad_assuntos');
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Assunto cadastrado com sucesso!</div>');
            redirect('cadastros/politicas_formularios');
        }
    }

    public function cad_politicas_formularios()
    {
        $nome_arquivo = "";
        $assunto = $this->input->post('assunto');
        $tipo = $this->input->post('tipo');
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {

            if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'politica_formulario') == FALSE) {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa informação já foi cadastrada!</div>');
                redirect('cadastros/politicas_formularios');
            } else {
                if ($dados = $this->uploadArquivos('./uploads/politica_formulario', 'jpg|jpeg|png|gif|pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                    $nome_arquivo = $dados['file_name'];
                    $path = $dados['full_path'];
                    $arquivo_enviado = true;
                }

                if ($arquivo_enviado) {
                    $retorno = $this->cadastro->cadPoliticaFormulario($descricao, $nome_arquivo, $path, $assunto, $tipo);
                    // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                    $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Informação cadastrada com sucesso!</div>');
                    redirect('cadastros/politicas_formularios');
                } else {
                    // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Informação!</div>');
                    redirect('cadastros/politicas_formularios');
                }
            }
        } else {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
            redirect('cadastros/politicas_formularios');
        }
    }

    public function editar_assunto($id_assunto)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar assunto';
        $dados['assunto'] = $this->ashownett->getAssuntosEditar(array(
            'id' => $id_assunto
        ));

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['assunto']))
            $this->load->view('ashownet/politicaseformularios/edit_assunto');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_assunto($id_assunto)
    {
        $assunto = $this->input->post('assunto');
        $ativo = $this->input->post('ativo');

        $retorno = $this->cadastro->editAssunto($assunto, $id_assunto, $ativo, 'cad_assuntos');
        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Assunto alterado com sucesso!</div>');
        redirect("cadastros/editar_assunto/$id_assunto");
    }

    public function editar_informacao($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Formulários e Informações';
        $dados['informacao'] = $this->ashownett->getInformacaoEditar(array(
            'id' => $id_informacao
        ), 'cad_formularios_informacoes');

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['informacao']))
            $this->load->view('ashownet/politicaseformularios/edit_informacao');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_informacao($id_informacao)
    {
        $assunto = $this->input->post('assunto');
        $tipo = $this->input->post('tipo');
        $descricao = $this->input->post('descricao');
        $ativo = $this->input->post('ativo');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/politica_formulario', 'jpg|jpeg|png|gif|pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $query = $this->db->query("SELECT path, a.id FROM cad_formularios_informacoes i, arquivos a WHERE i.id_arquivos=a.`id`
                AND i.id = '$id_informacao'");

                foreach ($query->result_array() as $row) {
                    $this->arquivo->excluirArquivoById($row['id']);
                    unlink("$row[path]");
                }

                $retorno = $this->cadastro->editPoliticaFormulario($descricao, $nome_arquivo, $path, $assunto, $tipo, $id_informacao, $ativo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Informação alterada com sucesso!</div>');
                redirect("cadastros/editar_informacao/$id_informacao");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Informação!</div>');
                redirect("cadastros/editar_informacao/$id_informacao");
            }
        } else {
            $retorno = $this->cadastro->editInformacao($assunto, $tipo, $descricao, $ativo, $id_informacao);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Informação alterada com sucesso!</div>');
            redirect("cadastros/editar_informacao/$id_informacao");
        }
    }

    public function listar_produtos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Produtos';
        $dados['produtos'] = $this->cadastro->getProdutos();

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/produtos/lista_produtos');
        $this->load->view('fix/footer4');
    }

    public function produtos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Produtos';
        $dados['assuntos'] = $this->ashownett->getAssuntosProdutos();

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/produtos/cad_produtos');
        $this->load->view('fix/footer4');
    }

    public function excluir_produto($id)
    {
        // $query_produtos_info = $this->db->query("SELECT id, id_assunto FROM cad_produtos_informacoes WHERE id_assunto ='$id'");

        // foreach ($query_produtos_info->result_array() as $row) {

        //     $query_arquivo = $this->db->query("SELECT * FROM cad_produto_informacoes_arquivos WHERE id_produto ='$row[id]'");

        //     foreach ($query_arquivo->result_array() as $rowArquivo) {

        //         if($rowArquivo['path'] != ""){
        //             $nome_arquivo_old = basename($rowArquivo['path']);
        //             $caminho = './uploads/produtos/'. $nome_arquivo_old;
        //             unlink($caminho);
        //         }

        //         $this->cadastro->excluirProdutoArquivosById($rowArquivo['id_assunto']);
        //     }
        // }

        // if ($this->cadastro->excluirProdutoById($id)) {
        //     echo json_encode(array('success' => true, 'msg' =>"Produto deletado com sucesso!"));
        // }

        $arquivos = $this->ashownett->arqsToDeleteProduto($id);


        foreach ($arquivos as $arqs) {
            $caminho = './uploads/produtos/' . $arqs['file'];

            unlink($caminho);
        }
        $retorno = $this->ashownett->deleteProduto($id);
        if ($retorno["success"]) {
            echo json_encode(array('success' => true, 'msg' => "Produto excluido com sucesso"));
        } else {
            echo json_encode(array('success' => false, 'msg' => "Error ao excluir produto"));
        }
    }
    public function excluir_arq_produto($id)
    {
        // $this->db->where('id', $id);
        // $file = $this->db->get('arquivos', 1)->row();
        $retorno = $this->ashownett->deleteArqProduto($id);
        //die(json_encode(array($retorno)));

        if ($retorno["success"]) {
            $caminho = './uploads/produtos/' . $retorno["file"];
            //unlink("$file->path");
            unlink($caminho);
            echo json_encode(array('success' => true, 'msg' => "Arquivo excluido com sucesso"));
        } else {
            echo json_encode(array('success' => false, 'msg' => "Error ao excluir arquivo"));
        }
    }

    public function excluir_assuntos($id)
    {
        $verifica_assuntos = $this->db->query("SELECT * FROM cad_produto_informacoes_arquivos a, cad_assunto_produtos p
        WHERE p.id=a.id_assunto AND id_assunto = '$id'");

        if ($verifica_assuntos->num_rows() > 0) {
            echo "Desculpe, existem produtos já relacionados ao assunto selecionado, impossível de excluir!";
        } else {
            if ($this->cadastro->excluirById($id, "cad_assunto_produtos")) {
                echo "Assunto deletado com sucesso!";
            }
        }
    }

    public function excluir_produto_editar($id)
    {
        $query_produtos_info = $this->db->query("SELECT id, id_assunto FROM cad_produtos_informacoes WHERE id ='$id'");

        foreach ($query_produtos_info->result_array() as $row) {

            $query_arquivo = $this->db->query("SELECT * FROM cad_produto_informacoes_arquivos WHERE id_produto ='$row[id]'");

            foreach ($query_arquivo->result_array() as $rowArquivo) {

                unlink("$rowArquivo[path]");
                $this->cadastro->excluirProdutoArquivosEditarById($rowArquivo['id']);
            }

            $this->cadastro->excluirProdutoEditarById($id);
        }

        if ($this->cadastro->excluirProdutoById($id)) {
            echo "Produto deletado com sucesso!";
        }
    }

    public function cad_produtos()
    {
        $nome_arquivo = "";
        $assunto = $this->input->post('assunto');
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {

            if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'produtos') == FALSE) {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa descrição já foi cadastrada!</div>');
                redirect('cadastros/produtos');
            } else {

                $dadosProdutos = array(
                    'id_assunto' => $assunto,
                    'descricao' => $descricao,
                    'data_criacao' => date('Y-m-d H:i:s')
                );

                $this->db->insert('showtecsystem.cad_produtos_informacoes', $dadosProdutos);
                $last_id = $this->db->insert_id();

                $count = count($_FILES['arquivo']['name']);
                $b = 1;
                for ($i = 0; $i < $count; $i++) {

                    if (!empty($_FILES['arquivo']['name'][$i])) {

                        $_FILES['file']['name'] = $_FILES['arquivo']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['arquivo']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['arquivo']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['arquivo']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['arquivo']['size'][$i];

                        if ($dados = $this->uploadApresentacao('./uploads/produtos')) {
                            $nome_arquivo = $dados['file_name'];
                            $path = $dados['full_path'];
                            $arquivo_enviado = true;
                        }

                        $this->cadastro->cadProdutoFormulario($last_id, $assunto, $nome_arquivo, $path);
                    }
                    $b++;
                }

                if ($arquivo_enviado) {
                    $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Cadastrado realizado com sucesso!</div>');
                    redirect('cadastros/produtos');
                } else {
                    // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Produto!</div>');
                    redirect('cadastros/produtos');
                }
            }
        } else {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
            redirect('cadastros/produtos');
        }
    }

    public function cad_assuntos_produtos()
    {
        $assunto = $this->input->post('assunto');

        if ($this->cadastro->verificaCadastroAssuntoProduto($assunto) == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse assunto já foi cadastrado!</div>');
            redirect('cadastros/produtos');
        } else {

            $retorno = $this->cadastro->cadAssuntoProduto($assunto);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Assunto cadastrado com sucesso!</div>');
            redirect('cadastros/produtos');
        }
    }

    public function editar_produto($id_produto)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Produto';
        $dados['produtos'] = $this->cadastro->getProdutoEditar($id_produto);

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['produtos']))
            $this->load->view('rh/produtos/edit_produtos');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_produto($id_produto)
    {
        $idproduto = $this->input->post('idproduto');
        $assunto = $this->input->post('assunto');
        $descricao = $this->input->post('descricao');
        $ativo = $this->input->post('ativo');

        for ($i = 0; $i < count($idproduto); $i++) {

            $retorno = $this->cadastro->editInformacaoProduto($assunto, $descricao[$i], $ativo, $idproduto[$i]);
        }
        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Produto alterado com sucesso!</div>');
        redirect("cadastros/editar_produto/$id_produto");
    }

    public function edit_produto_assunto($id_produto)
    {
        $assunto = $this->input->post('assunto');
        $idassunto = $this->input->post('idassunto');

        $retorno = $this->cadastro->editInformacaoProdutoAssunto($assunto, $id_produto);
        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Assunto alterado com sucesso!</div>');
        redirect("cadastros/editar_produto/$idassunto");
    }

    public function listar_engenharia_suporte()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Suporte Técnico';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'engenharia_suporte'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/engenharia_suporte/lista_suporte');
        $this->load->view('fix/footer4');
    }

    public function engenharia_suporte()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Suporte Técnico';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/engenharia_suporte/cad_suporte');
        $this->load->view('fix/footer4');
    }

    public function cad_engenharia_suporte()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/engenharia_suporte');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'engenharia_suporte') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/engenharia_suporte');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/engenharia_suporte', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'engenharia_suporte');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/engenharia_suporte');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/engenharia_suporte');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/engenharia_suporte');
            }
        }
    }

    public function excluir_engenharia_suporte($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_engenharia_suporte($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Formulários e Informações';
        $dados['dados_suporte'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_suporte']))
            $this->load->view('ashownet/engenharia_suporte/edit_suporte');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_engenharia_suporte($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/engenharia_suporte', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'engenharia_suporte', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_engenharia_suporte/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_engenharia_suporte/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'engenharia_suporte', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_engenharia_suporte/$id_arquivo");
        }
    }

    public function listar_engenharia_teste_homologacao()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Teste e Homologação';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'engenharia_teste_homologacao'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/engenharia_teste_homologacao/lista_teste_homologacao');
        $this->load->view('fix/footer4');
    }

    public function engenharia_teste_homologacao()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Teste e Homologação';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/engenharia_teste_homologacao/cad_teste_homologacao');
        $this->load->view('fix/footer4');
    }

    public function cad_engenharia_teste_homologacao()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/engenharia_teste_homologacao');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'engenharia_teste_homologacao') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/engenharia_teste_homologacao');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/engenharia_teste_homologacao', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'engenharia_teste_homologacao');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/engenharia_teste_homologacao');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/engenharia_teste_homologacao');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/engenharia_teste_homologacao');
            }
        }
    }

    public function excluir_engenharia_teste_homologacao($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_engenharia_teste_homologacao($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Teste e Homologação';
        $dados['dados_teste_homologacao'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_teste_homologacao']))
            $this->load->view('ashownet/engenharia_teste_homologacao/edit_teste_homologacao');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_engenharia_teste_homologacao($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/engenharia_teste_homologacao', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'engenharia_teste_homologacao', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_engenharia_teste_homologacao/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_engenharia_teste_homologacao/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'engenharia_suporte', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_engenharia_teste_homologacao/$id_arquivo");
        }
    }

    public function listar_espaco_ti()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Espaço TI';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'espaco_ti'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/espaco_ti/lista_espaco_ti');
        $this->load->view('fix/footer4');
    }

    public function espaco_ti()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Espaço TI';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/espaco_ti/cad_espaco_ti');
        $this->load->view('fix/footer4');
    }

    public function cad_espaco_ti()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/espaco_ti');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'engenharia_suporte') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/espaco_ti');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/espaco_ti', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'espaco_ti');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/espaco_ti');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/espaco_ti');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/espaco_ti');
            }
        }
    }

    public function excluir_espaco_ti($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_espaco_ti($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Espaço TI';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/espaco_ti/edit_espaco_ti');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_espaco_ti($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/espaco_ti', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'espaco_ti', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_espaco_ti/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_espaco_ti/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'espaco_ti', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_espaco_ti/$id_arquivo");
        }
    }

    public function listar_marketing_briefing()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Marketing Briefing';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'marketing_briefing'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/marketing/briefing/lista_briefing');
        $this->load->view('fix/footer4');
    }

    public function marketing_briefing()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Marketing Briefing';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/marketing/briefing/cad_briefing');
        $this->load->view('fix/footer4');
    }

    public function cad_marketing_briefing()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/marketing_briefing');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'engenharia_suporte') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/marketing_briefing');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/marketing_briefing', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'marketing_briefing');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/marketing_briefing');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/marketing_briefing');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/marketing_briefing');
            }
        }
    }

    public function excluir_marketing_briefing($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_marketing_briefing($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Marketing Briefing';
        $dados['dados_marketing_briefing'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_marketing_briefing']))
            $this->load->view('ashownet/marketing/briefing/edit_briefing');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_marketing_briefing($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/marketing_briefing', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'marketing_briefing', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_marketing_briefing/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_marketing_briefing/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'marketing_briefing', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_marketing_briefing/$id_arquivo");
        }
    }

    public function marketing_campanhas()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Marketing Campanhas';
        $dados['assuntos'] = $this->ashownett->getAssuntos('cad_assunto_campanhas');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/marketing/campanhas/cad_campanhas');
        $this->load->view('fix/footer4');
    }

    public function cad_marketing_campanhas()
    {
        $nome_arquivo = "";
        $assunto = $this->input->post('assunto');
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {

            if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'marketing_campanhas') == FALSE) {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa informação já foi cadastrada!</div>');
                redirect('cadastros/marketing_campanhas');
            } else {
                if ($dados = $this->uploadArquivos('./uploads/marketing_campanhas', 'jpg|jpeg|png|gif|pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                    $nome_arquivo = $dados['file_name'];
                    $path = $dados['full_path'];
                    $arquivo_enviado = true;
                }

                if ($arquivo_enviado) {
                    $retorno = $this->cadastro->cadMarketingFormulario($descricao, $nome_arquivo, $path, $assunto, 'marketing_campanhas');
                    // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                    $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Informação cadastrada com sucesso!</div>');
                    redirect('cadastros/marketing_campanhas');
                } else {
                    // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Informação!</div>');
                    redirect('cadastros/marketing_campanhas');
                }
            }
        } else {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
            redirect('cadastros/marketing_campanhas');
        }
    }

    public function cad_assuntos_marketing_campanhas()
    {
        $assunto = $this->input->post('assunto');

        if ($this->cadastro->verificaCadastroAssunto($assunto, 'cad_assunto_campanhas') == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse assunto já foi cadastrado!</div>');
            redirect('cadastros/marketing_campanhas');
        } else {

            $retorno = $this->cadastro->cadAssunto($assunto, 'cad_assunto_campanhas');
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Assunto cadastrado com sucesso!</div>');
            redirect('cadastros/marketing_campanhas');
        }
    }

    public function editar_assunto_marketing_campanhas($id_assunto)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar assunto';
        $dados['assunto'] = $this->ashownett->getAssuntosEditarCampanhas(array(
            'id' => $id_assunto
        ));

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['assunto']))
            $this->load->view('ashownet/marketing/campanhas/edit_assunto');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_assunto_marketing_campanhas($id_assunto)
    {
        $assunto = $this->input->post('assunto');
        $ativo = $this->input->post('ativo');

        $retorno = $this->cadastro->editAssunto($assunto, $id_assunto, $ativo, 'cad_assunto_campanhas');
        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Assunto alterado com sucesso!</div>');
        redirect("cadastros/editar_assunto_marketing_campanhas/$id_assunto");
    }

    public function editar_informacao_marketing_campanhas($id_informacao)
    {
        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Marketing Campanhas';
        $dados['informacao'] = $this->ashownett->getInformacaoEditar(array(
            'id' => $id_informacao
        ), 'cad_marketing_campanhas');

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['informacao']))
            $this->load->view('ashownet/marketing/campanhas/edit_informacao');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_informacao_marketing_campanhas($id_informacao)
    {
        $assunto = $this->input->post('assunto');
        $descricao = $this->input->post('descricao');
        $ativo = $this->input->post('ativo');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/marketing_campanhas', 'jpg|jpeg|png|gif|pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $query = $this->db->query("SELECT path, a.id FROM cad_marketing_campanhas i, arquivos a WHERE i.id_arquivo=a.`id`
                AND i.id = '$id_informacao'");

                foreach ($query->result_array() as $row) {
                    $this->arquivo->excluirArquivoById($row['id']);
                    unlink("$row[path]");
                }

                $retorno = $this->cadastro->editMarketingFormulario($descricao, $nome_arquivo, $path, $assunto, 'marketing_campanhas', $id_informacao);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Campanha alterada com sucesso!</div>');
                redirect("cadastros/editar_informacao_marketing_campanhas/$id_informacao");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Campanha!</div>');
                redirect("cadastros/editar_informacao_marketing_campanhas/$id_informacao");
            }
        } else {
            $retorno = $this->cadastro->editMarketingFormulario($descricao, $nome_arquivo, $path, $assunto, $ativo, 'marketing_campanhas', $id_informacao);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Campanha alterada com sucesso!</div>');
            redirect("cadastros/editar_informacao_marketing_campanhas/$id_informacao");
        }
    }

    public function listar_apresentacoes_comerciais()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Apresentações Comerciais';
        $dados['apresentacoes'] = $this->ashownett->getApresentacao('cad_apresentacao_comerciais');
        $this->mapa_calor->registrar_acessos_url(site_url('/cadastros/listar_apresentacoes_comerciais'));
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/apresentacoes_comerciais/lista_apresentacoes');
        $this->load->view('fix/footer4');
    }

    public function apresentacao_comercial()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Apresentações Comerciais';

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/apresentacoes_comerciais/cad_apresentacoes');
        $this->load->view('fix/footer4');
    }

    public function cad_apresentacao_comercial()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/apresentacao_comercial');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'cad_apresentacao_comerciais', '') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa apresentação já foi cadastrada!</div>');
                    redirect('cadastros/apresentacao_comercial');
                } else {
                    $dataAtutal = date("Y-m-d H:i:s");
                    $dadosApresentacao = array(
                        'descricao' => $descricao,
                        'data_criacao' => $dataAtutal
                    );

                    $this->db->insert('showtecsystem.cad_apresentacao_comerciais', $dadosApresentacao);
                    $last_id = $this->db->insert_id();

                    $data = [];

                    $count = count($_FILES['arquivo']['name']);
                    $b = 1;
                    for ($i = 0; $i < $count; $i++) {

                        if (!empty($_FILES['arquivo']['name'][$i])) {

                            $_FILES['file']['name'] = $_FILES['arquivo']['name'][$i];
                            $_FILES['file']['type'] = $_FILES['arquivo']['type'][$i];
                            $_FILES['file']['tmp_name'] = $_FILES['arquivo']['tmp_name'][$i];
                            $_FILES['file']['error'] = $_FILES['arquivo']['error'][$i];
                            $_FILES['file']['size'] = $_FILES['arquivo']['size'][$i];

                            if ($dados = $this->uploadApresentacao('./uploads/apresentacoes_comerciais')) {
                                $nome_arquivo = $dados['file_name'];
                                $path = $dados['full_path'];
                                $arquivo_enviado = true;
                            }

                            $this->cadastro->digitalizacaoApresentacao($last_id, $descricao, $nome_arquivo, $path, $b, 'cad_apresentacao_comerciais_arquivos');
                        }
                        $b++;
                    }

                    if ($arquivo_enviado) {
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Apresentação cadastrada com sucesso!</div>');
                        redirect('cadastros/apresentacao_comercial');
                    } else {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar apresentaçãoo!</div>');
                        redirect('cadastros/apresentacao_comercial');
                    }
                }
            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/apresentacao_comercial');
            }
        }
    }

    public function excluir_apresentacao_comercial($id)
    {
        $query = $this->db->query("SELECT id_apresentacao, path FROM cad_apresentacao_comerciais_arquivos
        WHERE id_apresentacao ='$id'");

        foreach ($query->result_array() as $row) {
            unlink("$row[path]");
        }

        if ($this->ashownett->excluirApresentacaoById($id, 'cad_apresentacao_comerciais')) {
            echo "Apresentação deletada com sucesso!";
        }
    }

    public function editar_apresentacao_comercial($id)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Apresentação';
        $dados['apresentacao'] = $this->cadastro->getApresentacao('cad_apresentacao_comerciais', array(
            'id' => $id
        ));
        $dados['apresentacoes'] = $this->arquivo->getApresentacaoEditar("cad_apresentacao_comerciais_arquivos", "id_apresentacao = '$id'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['apresentacao']))
            $this->load->view('ashownet/apresentacoes_comerciais/edit_apresentacao');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_apresentacao_comercial($id)
    {
        $descricao = $this->input->post('descricao');

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe uma descrição!</div>');
            redirect("cadastros/editar_apresentacao_comercial/$id");
        } else {
            $retorno = $this->cadastro->editApresentacao($descricao, $id, 'cad_apresentacao_comerciais');
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Apresentação alterada com sucesso!</div>');
            redirect("cadastros/editar_apresentacao_comercial/$id");
        }
    }

    public function excluir_apresentacao_imagem_comerciais($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('cad_apresentacao_comerciais_arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoApresentacaoById($id)) {
            echo "Imagem deletada com sucesso!";
        }
    }

    public function listar_comite_guerra()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Comitê de Guerra';
        $dados['apresentacoes'] = $this->ashownett->getApresentacao('cad_comite_guerra');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/comite_guerra/lista_apresentacoes');
        $this->load->view('fix/footer4');
    }

    public function comite_guerra()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Comitê de Guerra';

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/comite_guerra/cad_apresentacoes');
        $this->load->view('fix/footer4');
    }

    public function cad_comite_guerra()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/apresentacao_comercial');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'cad_comite_guerra', '') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa apresentação já foi cadastrada!</div>');
                    redirect('cadastros/comite_guerra');
                } else {
                    $dataAtutal = date("Y-m-d H:i:s");
                    $dadosApresentacao = array(
                        'descricao' => $descricao,
                        'data_criacao' => $dataAtutal
                    );

                    $this->db->insert('showtecsystem.cad_comite_guerra', $dadosApresentacao);
                    $last_id = $this->db->insert_id();

                    $data = [];

                    $count = count($_FILES['arquivo']['name']);
                    $b = 1;
                    for ($i = 0; $i < $count; $i++) {

                        if (!empty($_FILES['arquivo']['name'][$i])) {

                            $_FILES['file']['name'] = $_FILES['arquivo']['name'][$i];
                            $_FILES['file']['type'] = $_FILES['arquivo']['type'][$i];
                            $_FILES['file']['tmp_name'] = $_FILES['arquivo']['tmp_name'][$i];
                            $_FILES['file']['error'] = $_FILES['arquivo']['error'][$i];
                            $_FILES['file']['size'] = $_FILES['arquivo']['size'][$i];

                            if ($dados = $this->uploadApresentacao('./uploads/comite_guerra')) {
                                $nome_arquivo = $dados['file_name'];
                                $path = $dados['full_path'];
                                $arquivo_enviado = true;
                            }

                            $this->cadastro->digitalizacaoApresentacao($last_id, $descricao, $nome_arquivo, $path, $b, 'cad_comite_guerra_arquivos');
                        }
                        $b++;
                    }

                    if ($arquivo_enviado) {
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Apresentação cadastrada com sucesso!</div>');
                        redirect('cadastros/comite_guerra');
                    } else {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar apresentaçãoo!</div>');
                        redirect('cadastros/comite_guerra');
                    }
                }
            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/comite_guerra');
            }
        }
    }

    public function editar_comite_guerra($id)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Comitê de Guerra';
        $dados['apresentacao'] = $this->cadastro->getApresentacao('cad_comite_guerra', array(
            'id' => $id
        ));
        $dados['apresentacoes'] = $this->arquivo->getApresentacaoEditar("cad_comite_guerra_arquivos", "id_apresentacao = '$id'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['apresentacao']))
            $this->load->view('ashownet/comite_guerra/edit_apresentacao');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_comite_guerra($id)
    {
        $descricao = $this->input->post('descricao');

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe uma descrição!</div>');
            redirect("cadastros/editar_comite_guerra/$id");
        } else {
            $retorno = $this->cadastro->editApresentacao($descricao, $id, 'cad_comite_guerra');
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Apresentação alterada com sucesso!</div>');
            redirect("cadastros/editar_comite_guerra/$id");
        }
    }

    public function excluir_comite_guerra($id)
    {
        $query = $this->db->query("SELECT id_apresentacao, path FROM cad_comite_guerra_arquivos
        WHERE id_apresentacao ='$id'");

        foreach ($query->result_array() as $row) {
            unlink("$row[path]");
        }

        if ($this->ashownett->excluirApresentacaoById($id, 'cad_comite_guerra')) {
            echo "Apresentação deletada com sucesso!";
        }
    }

    public function excluir_comite_guerra_imagens($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('cad_comite_guerra_arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoApresentacaoById($id)) {
            echo "Imagem deletada com sucesso!";
        }
    }

    public function listar_televendas_comunicados()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Televendas Comunicados';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'televendas_comunicados'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/televendas_comunicados/lista_comunicados');
        $this->load->view('fix/footer4');
    }

    public function televendas_comunicados()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Televendas Comunicados';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/televendas_comunicados/cad_comunicados');
        $this->load->view('fix/footer4');
    }

    public function cad_televendas_comunicados()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/televendas_comunicados');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'televendas_comunicados') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/televendas_comunicados');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/televendas_comunicados', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'televendas_comunicados');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/televendas_comunicados');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/televendas_comunicados');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/televendas_comunicados');
            }
        }
    }

    public function excluir_televendas_comunicados($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_televendas_comunicados($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Televendas_comunicados';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/televendas_comunicados/edit_comunicados');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_televendas_comunicados($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/televendas_comunicados', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'televendas_comunicados', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_televendas_comunicados/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_televendas_comunicados/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'televendas_comunicados', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_televendas_comunicados/$id_arquivo");
        }
    }

    public function listar_propostas_comerciais()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Propostas Comerciais';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'propostas_comerciais'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/propostas_comerciais/lista_propostas');
        $this->load->view('fix/footer4');
    }

    public function propostas_comerciais()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Propostas Comerciais';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/propostas_comerciais/cad_propostas');
        $this->load->view('fix/footer4');
    }

    public function cad_propostas_comerciais()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/propostas_comerciais');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'propostas_comerciais') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/propostas_comerciais');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/propostas_comerciais', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'propostas_comerciais');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/propostas_comerciais');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/propostas_comerciais');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/propostas_comerciais');
            }
        }
    }

    public function excluir_propostas_comerciais($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_propostas_comerciais($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Propostas Comerciais';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/propostas_comerciais/edit_propostas');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_propostas_comerciais($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/propostas_comerciais', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'propostas_comerciais', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_propostas_comerciais/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_propostas_comerciais/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'propostas_comerciais', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_propostas_comerciais/$id_arquivo");
        }
    }

    public function listar_politicas_procedimentos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Política e Procedimentos';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'politicas_procedimentos'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/politicas_procedimentos/lista_procedimentos');
        $this->load->view('fix/footer4');
    }

    public function politicas_procedimentos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Política e Procedimentos';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/politicas_procedimentos/cad_procedimentos');
        $this->load->view('fix/footer4');
    }

    public function cad_politicas_procedimentos()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/politicas_procedimentos');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'politicas_procedimentos') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/politicas_procedimentos');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/politicas_procedimentos', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'politicas_procedimentos');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/politicas_procedimentos');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/politicas_procedimentos');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/politicas_procedimentos');
            }
        }
    }

    public function excluir_politicas_procedimentos($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_politicas_procedimentos($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Política e Procedimentos';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/politicas_procedimentos/edit_procedimentos');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_politicas_procedimentos($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/politicas_procedimentos', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'politicas_procedimentos', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_politicas_procedimentos/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_politicas_procedimentos/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'politicas_procedimentos', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_politicas_procedimentos/$id_arquivo");
        }
    }

    public function listar_guia_produtos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Guia de Produtos';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'guia_produtos'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/guia_produtos/lista_produtos');
        $this->load->view('fix/footer4');
    }

    public function guia_produtos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Guia de Produtos';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/guia_produtos/cad_produtos');
        $this->load->view('fix/footer4');
    }

    public function cad_guia_produtos()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/guia_produtos');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'guia_produtos') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/guia_produtos');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/guia_produtos', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'guia_produtos');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/guia_produtos');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/guia_produtos');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/guia_produtos');
            }
        }
    }

    public function excluir_guia_produtos($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_guia_produtos($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Guia de Produtos';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/guia_produtos/edit_produtos');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_guia_produtos($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/guia_produtos', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'guia_produtos', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_guia_produtos/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_guia_produtos/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'guia_produtos', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_guia_produtos/$id_arquivo");
        }
    }

    public function listar_precos_acessorios()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Preços e Acessórios';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'precos_acessorios'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/precos_acessorios/lista_precos_acessorios');
        $this->load->view('fix/footer4');
    }

    public function precos_acessorios()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Guia de Produtos';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/precos_acessorios/cad_precos_acessorios');
        $this->load->view('fix/footer4');
    }

    public function cad_precos_acessorios()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/precos_acessorios');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'precos_acessorios') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/precos_acessorios');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/precos_acessorios', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'precos_acessorios');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/precos_acessorios');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/precos_acessorios');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/precos_acessorios');
            }
        }
    }

    public function excluir_precos_acessorios($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_precos_acessorios($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Preços e Acessórios';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/precos_acessorios/edit_precos_acessorios');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_precos_acessorios($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/precos_acessorios', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'precos_acessorios', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_precos_acessorios/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_precos_acessorios/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'precos_acessorios', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_precos_acessorios/$id_arquivo");
        }
    }

    public function listar_inteligencia_mercado()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Inteligência de Mercado';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'inteligencia_mercado'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/inteligencia_mercado/lista_inteligencia_mercado');
        $this->load->view('fix/footer4');
    }

    public function inteligencia_mercado()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Inteligência de Mercado';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/inteligencia_mercado/cad_inteligencia_mercado');
        $this->load->view('fix/footer4');
    }

    public function cad_inteligencia_mercado()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/inteligencia_mercado');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'inteligencia_mercado') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/inteligencia_mercado');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/inteligencia_mercado', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'inteligencia_mercado');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/inteligencia_mercado');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/inteligencia_mercado');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/inteligencia_mercado');
            }
        }
    }

    public function excluir_inteligencia_mercado($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_inteligencia_mercado($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Inteligência de Mercado';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/inteligencia_mercado/edit_inteligencia_mercado');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_inteligencia_mercado($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/inteligencia_mercado', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'inteligencia_mercado', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_inteligencia_mercado/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_inteligencia_mercado/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'inteligencia_mercado', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_inteligencia_mercado/$id_arquivo");
        }
    }

    public function listar_governanca_corporativa()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Governança Corporativa';
        $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'governanca_corporativa'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/governanca_corporativa/lista_governanca_corporativa');
        $this->load->view('fix/footer4');
    }

    public function governanca_corporativa()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Governança Corporativa';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/governanca_corporativa/cad_governanca_corporativa');
        $this->load->view('fix/footer4');
    }

    public function cad_governanca_corporativa()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/governanca_corporativa');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'governanca_corporativa') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/governanca_corporativa');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/governanca_corporativa', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivo($descricao, $nome_arquivo, $path, 'governanca_corporativa');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/governanca_corporativa');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivo!</div>');
                        redirect('cadastros/governanca_corporativa');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/governanca_corporativa');
            }
        }
    }

    public function excluir_governanca_corporativa($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function editar_governanca_corporativa($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Governança Corporativa';
        $dados['dados_espaco_ti'] = $this->ashownett->get("id = '$id_informacao'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_espaco_ti']))
            $this->load->view('ashownet/governanca_corporativa/edit_governanca_corporativa');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_governanca_corporativa($id_arquivo)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/governanca_corporativa', 'pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'governanca_corporativa', $id_arquivo);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_governanca_corporativa/$id_arquivo");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_governanca_corporativa/$id_arquivo");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'governanca_corporativa', $id_arquivo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_governanca_corporativa/$id_arquivo");
        }
    }

    public function listar_gente_gestao()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Espaço Gente & Gestão';
        $dados['lista_dados'] = $this->ashownett->getDados('cad_gente_gestao');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/lista_gente_gestao');
        $this->load->view('fix/footer4');
    }

    public function editar_gente_gestao($id)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Espaço Gente & Gestão';
        $dados['dados_gente_gestao'] = $this->ashownett->getDadosEdit('cad_gente_gestao', array(
            'id' => $id
        ));

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_gente_gestao']))
            $this->load->view('ashownet/gente_gestao/edit_gente_gestao');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_gente_gestao($id)
    {
        $descricao = $this->input->post('sobre');

        $retorno = $this->ashownett->editDadosGenteGestao($descricao, $id);
        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucesso!</div>');
        redirect("cadastros/editar_gente_gestao/$id");
    }

    public function listar_treinamentos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Desenvolvimento Organizacional';
        $dados['lista_dados'] = $this->arquivo->getArquivo("pasta = 'gente_gestao/desenv_organizagional/treinamentos'");
        $dados['parcerias'] = $this->arquivo->getParceria();

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/treinamentos/listar');
        $this->load->view('fix/footer4');
    }

    public function treinamentos()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Desenvolvimento Organizacional';
        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/treinamentos/cadastro');
        $this->load->view('fix/footer4');
    }

    public function cad_treinamentos()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $tipo = $this->input->post('tipo');
        $link = $this->input->post('link');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/treinamentos');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'gente_gestao/desenv_organizacional/treinamentos') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/treinamentos');
                } else {
                    if ($dados = $this->uploadArquivos('./uploads/gente_gestao/desenv_organizagional/treinamentos', 'jpg|png|jpeg')) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoArquivoTreinamento($descricao, $tipo, $link, $nome_arquivo, $path, 'gente_gestao/desenv_organizagional/treinamentos');
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Aquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/treinamentos');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Aquivooo!</div>');
                        redirect('cadastros/treinamentos');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/treinamentos');
            }
        }
    }

    public function editar_treinamentos($id)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Desenvolvimento Organizacional Treinamentos';
        $dados['dados_treinamentos'] = $this->ashownett->get("id = '$id'");

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_treinamentos']))
            $this->load->view('ashownet/gente_gestao/desenv_organizacional/treinamentos/editar');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_treinamentos($id)
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $tipo = $this->input->post('tipo');
        $link = $this->input->post('link');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/gente_gestao/desenv_organizagional/treinamentos', 'jpg|png|jpeg')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormularioTreinamento($descricao, $tipo, $link, $nome_arquivo, $path, 'gente_gestao/desenv_organizagional/treinamentos', $id);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_treinamentos/$id");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Arquivo!</div>');
                redirect("cadastros/editar_treinamentos/$id");
            }
        } else {
            $retorno = $this->cadastro->editArquivoFormularioTreinamento($descricao, $tipo, $link, $nome_arquivo, $path, 'gente_gestao/desenv_organizagional/treinamentos', $id);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_treinamentos/$id");
        }
    }

    public function excluir_treinamentos($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function parcerias()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro Parcerias';
        $dados['parcerias'] = $this->arquivo->getParceria();

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/parcerias/cad_parcerias');
        $this->load->view('fix/footer4');
    }

    public function cad_parcerias()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/parcerias');
        } else {

            if ($arquivo) {

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'cad_parcerias', '') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa parceria já foi cadastrada!</div>');
                    redirect('cadastros/parcerias');
                } else {
                    $dataAtutal = date("Y-m-d H:i:s");
                    $dadosApresentacao = array(
                        'descricao' => $descricao,
                        'data_criacao' => $dataAtutal
                    );

                    $this->db->insert('showtecsystem.cad_parcerias', $dadosApresentacao);
                    $last_id = $this->db->insert_id();

                    $data = [];

                    $count = count($_FILES['arquivo']['name']);
                    $b = 1;
                    for ($i = 0; $i < $count; $i++) {

                        if (!empty($_FILES['arquivo']['name'][$i])) {

                            $_FILES['file']['name'] = $_FILES['arquivo']['name'][$i];
                            $_FILES['file']['type'] = $_FILES['arquivo']['type'][$i];
                            $_FILES['file']['tmp_name'] = $_FILES['arquivo']['tmp_name'][$i];
                            $_FILES['file']['error'] = $_FILES['arquivo']['error'][$i];
                            $_FILES['file']['size'] = $_FILES['arquivo']['size'][$i];

                            if ($dados = $this->uploadApresentacao('./uploads/gente_gestao/desenv_organizagional/parcerias')) {
                                $nome_arquivo = $dados['file_name'];
                                $path = $dados['full_path'];
                                $arquivo_enviado = true;
                            }

                            $this->cadastro->digitalizacaoApresentacao($last_id, $descricao, $nome_arquivo, $path, $b, 'cad_parcerias_arquivos');
                        }
                        $b++;
                    }

                    if ($arquivo_enviado) {
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Parceria cadastrada com sucesso!</div>');
                        redirect('cadastros/parcerias');
                    } else {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar parceria!</div>');
                        redirect('cadastros/parcerias');
                    }
                }
            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/parcerias');
            }
        }
    }

    public function excluirParcerias($id)
    {
        $query = $this->db->query("SELECT id_apresentacao, path FROM cad_parcerias_arquivos
        WHERE id_apresentacao ='$id'");

        foreach ($query->result_array() as $row) {
            unlink("$row[path]");
        }

        if ($this->ashownett->excluirApresentacaoById($id, 'cad_parcerias')) {
            echo "Parceria deletada com sucesso!";
        }
    }

    public function politicas_formulariosrh()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Políticas e Formalários';
        $dados['dados'] = $this->ashownett->getApresentacao('cad_politicas_formularios');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/politicas_formularios/listar_pf');
        $this->load->view('fix/footer4');
    }

    public function cad_politicas_formulariosrh()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Políticas e Formalários';

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/politicas_formularios/cad_pf');
        $this->load->view('fix/footer4');
    }

    public function inseri_politicas_formulariosrh()
    {
        $nome_arquivo = "";
        $assunto = $this->input->post('assunto');
        $tipo = $this->input->post('tipo');
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {

            if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'politica_formulario') == FALSE) {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa informação já foi cadastrada!</div>');
                redirect('cadastros/cad_politicas_formulariosrh');
            } else {
                if ($dados = $this->uploadArquivos('./uploads/gente_gestao/desenv_organizagional/politica_formulario', 'jpg|jpeg|png|gif|pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                    $nome_arquivo = $dados['file_name'];
                    $path = $dados['full_path'];
                    $arquivo_enviado = true;
                }

                if ($arquivo_enviado) {
                    $retorno = $this->cadastro->cadPoliticaFormularioRh($descricao, $nome_arquivo, $path, $tipo);
                    // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                    $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Informação cadastrada com sucesso!</div>');
                    redirect('cadastros/cad_politicas_formulariosrh');
                } else {
                    // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Informação!</div>');
                    redirect('cadastros/cad_politicas_formulariosrh');
                }
            }
        } else {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
            redirect('cadastros/cad_politicas_formulariosrh');
        }
    }

    public function excluir_politica_formulario($id)
    {
        $query_produtos_info = $this->db->query("SELECT id, id_arquivo FROM cad_politicas_formularios WHERE id ='$id'");

        foreach ($query_produtos_info->result_array() as $row) {

            $query_arquivo = $this->db->query("SELECT id, path FROM arquivos WHERE id ='$row[id_arquivo]'");

            foreach ($query_arquivo->result_array() as $rowArquivo) {
                unlink("$rowArquivo[path]");

                $this->arquivo->excluirArquivoById($rowArquivo['id']);
            }
        }

        if ($this->cadastro->excluirPfById($id)) {
            echo "Dados deletados com sucesso!";
        }
    }

    public function editar_informacao_formulario($id_informacao)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Informações e Formulários';
        $dados['informacao'] = $this->ashownett->getInformacaoEditar(array(
            'id' => $id_informacao
        ), 'cad_politicas_formularios');

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['informacao']))
            $this->load->view('ashownet/gente_gestao/desenv_organizacional/politicas_formularios/edit_pf');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_informacao_formulario($id_informacao)
    {
        $tipo = $this->input->post('tipo');
        $descricao = $this->input->post('descricao');
        $ativo = $this->input->post('ativo');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/gente_gestao/desenv_organizagional/politica_formulario', 'jpg|jpeg|png|gif|pptx|ppt|pdf|doc|docx|xlsx|xls')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $query = $this->db->query("SELECT path, a.id FROM cad_politicas_formularios i, arquivos a WHERE i.id_arquivo=a.`id`
                AND i.id = '$id_informacao'");

                foreach ($query->result_array() as $row) {
                    $this->arquivo->excluirArquivoById($row['id']);
                    unlink("$row[path]");
                }

                $retorno = $this->cadastro->editPoliticaFormularioRh($descricao, $nome_arquivo, $path, $tipo, $id_informacao, $ativo);
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Informação alterada com sucesso!</div>');
                redirect("cadastros/editar_informacao_formulario/$id_informacao");
            } else {

                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterada Informação!</div>');
                redirect("cadastros/editar_informacao_formulario/$id_informacao");
            }
        } else {
            $retorno = $this->cadastro->editPoliticaFormularioRh($descricao, $nome_arquivo, $path, $tipo, $id_informacao, $ativo);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Informação alterada com sucesso!</div>');
            redirect("cadastros/editar_informacao_formulario/$id_informacao");
        }
    }

    public function listar_plano_de_voo()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Plano de Voo';
        $dados['dados'] = $this->ashownett->getDados('plano_de_voo_questionario');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/plano_de_voo/lista_plano_de_voo');
        $this->load->view('fix/footer4');
    }

    public function plano_de_voo()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Plano de Voo';
        // $dados['banners'] = $this->arquivo->getArquivo("pasta = 'banners'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/plano_de_voo/cad_plano_de_voo');
        $this->load->view('fix/footer4');
    }

    public function cad_plano_de_voo()
    {
        $nome = $this->input->post('nome');
        $descricao = $this->input->post('descricao');

        if ($nome == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe o nome do plano!</div>');
            redirect('cadastros/plano_de_voo');
        } else {

            if ($this->cadastro->verificaCadastro('nome', $nome, 'plano_de_voo_questionario', '') == FALSE) {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse plano de voo já foi cadastrado!</div>');
                redirect('cadastros/plano_de_voo');
            } else {

                $resposta = $this->cadastro->cadPlanoVoo($nome, $descricao);

                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Plano de voo cadastrado com sucesso!</div>');
                redirect('cadastros/plano_de_voo');
            }
        }
    }

    public function editar_plano_de_voo($id_plano)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Plano Voo';
        $dados['dados_plano'] = $this->ashownett->getDadosEdit('plano_de_voo_questionario', array(
            'id' => $id_plano
        ));

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_plano']))
            $this->load->view('ashownet/gente_gestao/desenv_organizacional/plano_de_voo/edit_plano_de_voo');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_plano_de_voo($id_plano)
    {
        $nome = $this->input->post('nome');
        $descricao = $this->input->post('descricao');

        $retorno = $this->cadastro->editPlanoVoo($nome, $descricao, $id_plano);
        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucesso!</div>');
        redirect("cadastros/editar_plano_de_voo/$id_plano");
    }

    public function view_tbl_area($id_plano)
    {
        $dados['dados'] = array(
            $id_plano
        );

        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/tblArea", $dados);
    }

    public function save_area()
    {
        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/saveArea");
    }

    public function delete_area($id_plano)
    {
        $dados['dados'] = array(
            $id_plano
        );
        // var_dump($dados);
        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/deletaArea", $dados);
    }

    public function altera_area($id, $idchecklist)
    {
        $dados['dados'] = array(
            $id,
            $idchecklist
        );

        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/alteraArea", $dados);
    }

    public function save_edit_area()
    {
        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/editArea");
    }

    public function view_itens_area($id, $idchecklist)
    {
        $dados['dados'] = array(
            $id,
            $idchecklist
        );

        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/inseriItensArea", $dados);
    }

    public function save_itens_area()
    {
        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/saveItens");
    }

    public function view_update_item($id, $idchecklist)
    {
        $dados['dados'] = array(
            $id,
            $idchecklist
        );

        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/editItem", $dados);
    }

    public function update_item()
    {
        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/updateItens");
    }

    public function deletar_pergunta($id)
    {
        $dados['dados'] = array(
            $id
        );
        // var_dump($dados);
        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/deletaItem", $dados);
    }

    public function save_respostas($questionario, $funcionario, $pergunta, $resposta)
    {
        $dados['dados'] = array(
            $questionario,
            $funcionario,
            $pergunta,
            $resposta
        );

        $this->load->view("ashownet/gente_gestao/desenv_organizacional/plano_de_voo/saveRespostas", $dados);
    }

    public function listar_correcao_irrf()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Correção IRRF';
        $dados['dados'] = $this->ashownett->getDados('cad_correcao_irrf');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/adm_pessoal/correcao_irrf/listar');
        $this->load->view('fix/footer4');
    }

    public function correcao_irrf()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de Correção IRRF';

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/adm_pessoal/correcao_irrf/cadastro');
        $this->load->view('fix/footer4');
    }

    public function cad_coreccao_irrf()
    {
        $titulo = $this->input->post('titulo');
        $descricao = $this->input->post('sobre');

        if ($descricao == "") {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe algo sobre a descição do IRRF!</div>');
        } else {

            if ($this->cadastro->verificaCadastro('titulo', $titulo, 'cad_correcao_irrf') == FALSE) {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe essa corre~]ao já foi cadastrada!</div>');
                redirect('cadastros/correcao_irrf');
            } else {

                $retorno = $this->cadastro->cadCorrecaoIrrf($titulo, $descricao);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados cadastrados com sucesso!</div>');
                redirect("cadastros/correcao_irrf");
            }
        }
    }

    public function excluir_correcao_irrf($id)
    {
        if ($this->cadastro->excluirCorrecaoIrrfById($id)) {
            echo "Comunicado deletado com sucesso!";
        }
    }

    public function editar_correcao_irrf($id)
    {
        $dados['titulo'] = 'Editar Correção Irrf';
        $dados['dados'] = $this->ashownett->getDadosEdit('cad_correcao_irrf', array(
            'id' => $id
        ));

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados']))
            $this->load->view('ashownet/gente_gestao/adm_pessoal/correcao_irrf/editar');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_correcao_irrf($id)
    {
        $titulo = $this->input->post('titulo');
        $descricao = $this->input->post('sobre');

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe algo sobre a correção!</div>');
            redirect('cadastros/editar_correcao_irrf');
        } else {

            $retorno = $this->ashownett->editDadosCorrecaoIrrf($titulo, $descricao, $id);
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucesso!</div>');
            redirect("cadastros/editar_correcao_irrf/$id");
        }
    }

    public function cad_formulario_adp()
    {
        $dados = $this->input->post();

        $dadosFuncionario = array(
            'id_usuario' => $dados['id_usuario'],
            'nome' => $dados['nome'],
            'sexo' => $dados['sexo'],
            'dtNasc' => $dados['dtNasc'],
            'estado_civil' => $dados['estado_civil'],
            'escolaridade' => $dados['escolaridade'],
            'naturalidade' => $dados['naturalidade'],
            'nacionalidade' => $dados['nacionalidade'],
            'pai' => $dados['pai'],
            'mae' => $dados['mae'],
            'cpf' => $dados['cpf'],
            'rg' => $dados['rg'],
            'data_nascimento' => $dados['data_nascimento'],
            'rg_orgao' => $dados['rg_orgao'],
            'rg_uf' => $dados['rg_uf'],
            'rg_exp' => $dados['rg_exp'],
            'reservista' => $dados['reservista'],
            'pis' => $dados['pis'],
            'pis_exp' => $dados['pis_exp'],
            'cnh' => $dados['cnh'],
            'cnh_val' => $dados['cnh_val'],
            'cnh_exp' => $dados['cnh_exp'],
            'cnh_org' => $dados['cnh_org'],
            'cnh_uf' => $dados['cnh_uf'],
            'cnh_prim' => $dados['cnh_prim'],
            'ctps' => $dados['ctps'],
            'ctpf_serie' => $dados['ctpf_serie'],
            'ctpf_uf' => $dados['ctpf_uf'],
            'ctps_exp' => $dados['ctps_exp'],
            'titulo_eleitor' => $dados['titulo_eleitor'],
            'titulo_zona' => $dados['titulo_zona'],
            'titulo_secao' => $dados['titulo_secao'],
            'titulo_municipio' => $dados['titulo_municipio'],
            'cep' => $dados['cep'],
            'end_tipo' => $dados['end_tipo'],
            'endereco' => $dados['endereco'],
            'end_num' => $dados['end_num'],
            'end_compl' => $dados['end_compl'],
            'bairro' => $dados['bairro'],
            'cidade' => $dados['cidade'],
            'estado' => $dados['estado'],
            'tel_resid' => $dados['tel_resid'],
            'tel_cel' => $dados['tel_cel'],
            'tel_emerg' => $dados['tel_emerg'],
            'emerg_contato' => $dados['emerg_contato'],
            'email_partic' => $dados['email_partic'],
            'email_corp' => $dados['email_corp'],
            'raca' => 'Albino',
            'cns' => $dados['cns'],
            'ppd' => $dados['ppd'],
            'aposentado' => $dados['aposentado'],
            'filhos' => $dados['filhos'],
            'gestante' => $dados['gestante'],
            'dependentes' => $dados['dependentes']
        );

        $cpf = $dados['cpf'];
        $id = $dados['id'];

        // var_dump($dados);

        if ($this->cadastro->verificaCadastro('cpf', "$cpf", 'cad_colaborador') == FALSE) {
            $update = $this->cadastro->update_usuario($dadosFuncionario, $id);

            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados atualizados com sucesso!</div>');
        } else {
            $insert = $this->cadastro->add_usuario($dadosFuncionario);

            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados atualizados com sucesso!</div>');
        }

        if ($dados['dependentes'] != 0) {

            for ($i = 1; $i <= 1; $i++) {

                if ($dados['dependentes'] != "") {

                    $dadosFuncionarioDepen = array(
                        'id_funcionario' => $dados['id_usuario'],
                        'dep_nome' => $dados["dep_nome$i"],
                        'dep_estado_civil' => $dados["dep_estado_civil$i"],
                        'dep_cpf' => $dados["dep_cpf$i"],
                        'dep_parentesco' => $dados["dep_parentesco$i"],
                        'dep_sexo' => $dados["dep_sexo$i"],
                        'dep_dtNasc' => $dados["dep_dtNasc$i"],
                        'dep_mae' => $dados["dep_mae$i"],
                        'dep_dtCasam' => $dados["dep_dtCasam$i"],
                        'dep_naturalidade' => $dados["dep_naturalidade$i"],
                        'dep_raca' => $dados["dep_raca$i"],
                        'dep_ppd' => $dados["dep_ppd$i"],
                        'dep_cns' => $dados["dep_cns$i"],
                        'dep_cartorio' => $dados["dep_cartorio$i"],
                        'dep_registro' => $dados["dep_registro$i"],
                        'dep_declar_vivo' => $dados["dep_declar_vivo$i"],
                        'dep_irrf' => $dados["dep_irrf$i"]
                    );

                    // var_dump($dadosFuncionarioDepen);

                    $idDep = $dados["dep_id$i"];
                    $cpfDep = $dados["dep_cpf$i"];

                    if ($this->cadastro->verificaCadastro('dep_cpf', "$cpfDep", 'cad_colaborador_dependentes') == FALSE) {
                        $update = $this->cadastro->update_dependente($dadosFuncionarioDepen, $idDep);
                    } else {
                        $insert = $this->cadastro->add_dependente($dadosFuncionarioDepen);
                    }

                    $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados atualizados com sucesso!</div>');
                }
            }
        }
        redirect('ashownet/formulario_adp');
    }

    public function excluir_dependente($id)
    {
        if ($this->cadastro->excluirDependenteById($id)) {
            echo "Dependente removido com sucesso!";
        }
    }

    public function listar_docs_pendentes()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Documentos Pendentes';
        $dados['lista_dados'] = $this->cadastro->getDocumentosPendentes();

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/adm_pessoal/docs_pendentes/listar');
        $this->load->view('fix/footer4');
    }

    public function docs_pendentes()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Documentos Pendentes';
        $dados['usuarios'] = $this->ashownett->getDadosUsuarios();

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/adm_pessoal/docs_pendentes/cadastro');
        $this->load->view('fix/footer4');
    }

    public function cad_docs_pendentes()
    {
        $funcionario = $this->input->post('funcionario');
        $residencia = $this->input->post('residencia');
        $cpf = $this->input->post('cpf');
        $rg = $this->input->post('rg');
        $banco = $this->input->post('banco');
        $prazo = $this->input->post('prazo');

        if ($this->cadastro->verificaCadastro('id_funcionario', $funcionario, 'cad_docs_pendentes') == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Documentação já solicitada!</div>');
            redirect('cadastros/docs_pendentes');
        } else {

            $retorno = $this->cadastro->cadDocumentosPendentes($funcionario, $residencia, $cpf, $rg, $banco, $prazo);
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados cadastrados com sucesso!</div>');
            redirect("cadastros/docs_pendentes");
        }
    }

    public function editar_docs_pendentes($id)
    {
        $dados['titulo'] = 'Editar Documentos Pendentes';
        $dados['dados'] = $this->ashownett->getDadosEdit('cad_docs_pendentes', array(
            'id' => $id
        ));
        $dados['usuarios'] = $this->ashownett->getDadosUsuarios();

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados']))
            $this->load->view('ashownet/gente_gestao/adm_pessoal/docs_pendentes/editar');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_docs_pendentes($id)
    {
        $funcionario = $this->input->post('funcionario');
        $residencia = $this->input->post('residencia');
        $cpf = $this->input->post('cpf');
        $rg = $this->input->post('rg');
        $banco = $this->input->post('banco');
        $prazo = $this->input->post('prazo');
        $recebido = $this->input->post('recebido');

        $this->cadastro->editDocumentosPendentes($funcionario, $residencia, $cpf, $rg, $banco, $prazo, $recebido, $id);
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados atualizados com sucesso!</div>');
        redirect("cadastros/editar_docs_pendentes/$id");
    }

    public function cad_doc_pendentes_funcionario($id)
    {
        $nome_arquivo = "";
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {

            if ($this->cadastro->verificaCadastroArquivoDocsPendente($id) == FALSE) {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Arquivos já enviados, aguarde retorno do RH</div>');
                redirect("ashownet/docs_pendentes/$id");
            } else {

                $data = [];

                $count = count($_FILES['arquivo']['name']);

                for ($i = 0; $i < $count; $i++) {

                    if (!empty($_FILES['arquivo']['name'][$i])) {

                        $_FILES['file']['name'] = $_FILES['arquivo']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['arquivo']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['arquivo']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['arquivo']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['arquivo']['size'][$i];

                        if ($dados = $this->uploadApresentacao('./uploads/docs_pendentes')) {
                            $nome_arquivo = $dados['file_name'];
                            $path = $dados['full_path'];
                            $arquivo_enviado = true;
                        }

                        $this->cadastro->digitalizacaoDocsPendentes($id, $nome_arquivo, $path);
                    }
                }

                if ($arquivo_enviado) {
                    $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivos enviados com sucesso!</div>');
                    redirect("ashownet/docs_pendentes/$id");
                } else {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao enviar arquivos</div>');
                    redirect("ashownet/docs_pendentes/$id");
                }
            }
        } else {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
            redirect("ashownet/docs_pendentes/$id");
        }
    }

    public function excluir_docs_pendentes($id)
    {
        $query = $this->db->query("SELECT * FROM cad_docs_pendente_arquivos WHERE id_funcionario ='$id'");

        foreach ($query->result_array() as $row) {
            unlink("$row[path]");
        }

        if ($this->cadastro->excluirDocspendById($id)) {
            echo "Solicitação deletada com sucesso!";
        }
    }

    public function listar_desconto_coparticipacao()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Desconto de Coparticipação';
        $dados['lista_dados'] = $this->cadastro->getDescontosCoparticipacao();

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/adm_pessoal/desconto_coparticipacao/listar');
        $this->load->view('fix/footer4');
    }

    public function desconto_coparticipacao()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Desconto de Coparticipação';
        $dados['usuarios'] = $this->ashownett->getDadosUsuarios();

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/adm_pessoal/desconto_coparticipacao/cadastro');
        $this->load->view('fix/footer4');
    }

    public function lista_dependentes()
    {
        $dados['titulo'] = 'Desconto de Coparticipação';
        $dados['lista_dados'] = $this->cadastro->getDocumentosPendentes();

        $this->load->view('ashownet/gente_gestao/adm_pessoal/desconto_coparticipacao/dependentes');
    }

    public function cad_desconto_coparticipacao()
    {
        $dados = $this->input->post();

        //var_dump($dados);
        $mes = explode("-", $dados['mescompetencia']);

        var_dump($dados);

        if ($this->cadastro->verificaCadastroDescontoCoparticipacao('id_funcionario', $dados['funcionario'], 'MONTH(mes_competencia)', $mes[1], 'cad_desconto_coparticipacao') == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Esse desconto de Coparticipação foi cadastrado!</div>');
            redirect('cadastros/desconto_coparticipacao');
        } else {
            for ($i = 0; $i < count($dados['valcoparticiacaodepen']); $i++) {
                // echo 'ID '.$dados['iddependente'][$i].'Valor: '.$dados['valcoparticiacaodepen'][$i].'<br>';
                $this->cadastro->cadDescontoCoparticipacao($dados['funcionario'], $dados['iddependente'][$i], $dados['valcoparticipacao'], $dados['valcoparticiacaodepen'][$i], $dados['mescompetencia']);
            }

            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados cadastrados com sucesso!</div>');
            redirect('cadastros/desconto_coparticipacao');
        }
    }

    public function excluir_desconto_coparticipacao($id)
    {
        if ($this->cadastro->excluirById($id, 'cad_desconto_coparticipacao')) {
            echo "Desconto de Coparticipação deletado com sucesso!";
        }
    }

    public function listar_atividades()
    {
        $dados['titulo'] = 'Treinamentos';
        $dados['lista_dados'] = $this->ashownett->listaDadosAtividades('cad_atividades');

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/atividades/listar');
        $this->load->view('fix/footer4');
    }

    public function atividades()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'cadastro de Atividades';
        $dados['usuarios'] = $this->ashownett->getDadosUsuarios();

        $this->load->view('fix/header4', $dados);
        $this->load->view('ashownet/gente_gestao/desenv_organizacional/atividades/cadastro');
        $this->load->view('fix/footer4');
    }

    public function cad_atividades()
    {
        $funcionario = $this->input->post('funcionario');
        $curso = $this->input->post('curso');
        $tipo = $this->input->post('tipo');
        $data_inicio = $this->input->post('inicio');
        $data_fim = $this->input->post('fim');
        $cargahr = $this->input->post('cargahr');
        $status = $this->input->post('status');

        if ($this->cadastro->verificaCadastroDescontoCoparticipacao('curso', "'$curso'", 'id_funcionario', $funcionario, 'cad_atividades') == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Esse curso já foi cadastrado para o funcionário selecionado!</div>');
            redirect('cadastros/atividades');
        } else {
            $retorno = $this->cadastro->cadAtividades($funcionario, $curso, $tipo, $data_inicio, $data_fim, $cargahr, $status);

            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Atividade cadastrada com sucesso!</div>');
            redirect('cadastros/atividades');
        }
    }

    public function excluir_atividade($id)
    {
        if ($this->cadastro->excluirById($id, 'cad_atividades')) {
            echo "Atividade deletada com sucesso!";
        }
    }

    public function editar_atividade($id)
    {
        $dados['titulo'] = 'Editar Atividade';
        $dados['listar_dados'] = $this->ashownett->getDadosEdit('cad_atividades', array(
            'id' => $id
        ));

        $dados['usuarios'] = $this->ashownett->getDadosUsuarios();

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['listar_dados']))
            $this->load->view('ashownet/gente_gestao/desenv_organizacional/atividades/editar');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_atividade($id)
    {
        $funcionario = $this->input->post('funcionario');
        $curso = $this->input->post('curso');
        $tipo = $this->input->post('tipo');
        $data_inicio = $this->input->post('inicio');
        $data_fim = $this->input->post('fim');
        $cargahr = $this->input->post('cargahr');
        $status = $this->input->post('status');

        $retorno = $this->cadastro->editAtividades($funcionario, $curso, $tipo, $data_inicio, $data_fim, $cargahr, $status, $id);

        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Atividade alterada com sucesso!</div>');
        redirect("cadastros/editar_atividade/$id");
    }

    public function listar_aniversariantes()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Aniversariantes';
        $dados['aniversariantes'] = $this->cadastro->getAniversariantes();

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/aniversariantes/lista_aniversariantes');
        $this->load->view('fix/footer4');
    }

    public function aniversariantes()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de aniversariantes';

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/aniversariantes/cad_aniversariantes');
        $this->load->view('fix/footer4');
    }

    public function cad_aniversariante()
    {

        $nome = $this->input->post('nome');
        $cpf = $this->input->post('cpf');
        $email = $this->input->post('email');
        $empresa = $this->input->post('empresa');
        $cargo = $this->input->post('cargo');
        $datanasc = $this->input->post('datanascimento');

        if ($this->cadastro->verificaCadastroAniversariante($cpf) == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe o CPF informado já foi cadastrado!</div>');
            redirect('cadastros/aniversariantes');
        } else {
            $this->cadastro->cadAniversariantes($nome, $cpf, $email, $empresa, $cargo, $datanasc);
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados cadastrados com sucesso!</div>');
            redirect("cadastros/aniversariantes");
        }
    }

    public function excluir_aniversariante($id)
    {
        if ($this->cadastro->excluirById($id, 'cad_aniversariantes')) {
            echo "Cadastro deletado com sucesso!";
        }
    }

    public function editar_aniversariantes($id)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar Aniversariantes';
        $dados['aniversariantes'] = $this->cadastro->getApresentacao('cad_aniversariantes', array(
            'id' => $id
        ));

        $this->load->view('fix/header4', $dados);
        if (!empty($dados['aniversariantes']))
            $this->load->view('rh/aniversariantes/edit_aniversariantes');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_aniversariante($id)
    {
        $nome = $this->input->post('nome');
        $email = $this->input->post('email');
        $empresa = $this->input->post('empresa');
        $cargo = $this->input->post('cargo');
        $datanasc = $this->input->post('datanascimento');
        $ativo = $this->input->post('ativo');

        $this->cadastro->updateAniversariantes($nome, $email, $empresa, $cargo, $datanasc, $ativo, $id);
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados atualizados com sucesso!</div>');
    }

    public function smtpc()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Configurações de E-mail';
        $dados['config_stmp'] = $this->cadastro->getConfigSmtp();

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/aniversariantes/smtp');
        $this->load->view('fix/footer4');
    }

    public function smtp_atualizar()
    {
        $servidor = $this->input->post('smtp_host');
        $porta = $this->input->post('smtp_port');
        $email = $this->input->post('smtp_username');
        $senha = $this->input->post('smtp_password');
        $titulo = $this->input->post('smtp_fromname');
        $copia = $this->input->post('smtp_bcc');

        $this->cadastro->updateSmtp($servidor, $porta, $email, $senha, $titulo, $copia);
        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados atualizados com sucesso!</div>');
        redirect("cadastros/smtpc");
    }

    public function smtp_test()
    {
        $dados = $this->input->post();

        $query = $this->db->query("SELECT * FROM smtp");
        $row_smtp = $query->row();

        /*if ($query->num_rows() > 0)
        {}else
        {
            echo "Configuração incompleta, verifique os campos!";
        }*/

        require_once "application/helpers/phpmailer/class.phpmailer.php";

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->WordWrap = 80;
        $mail->IsHTML(true);
        $mail->Port = $dados['smtp_port'];
        $mail->Host = $dados['smtp_host'];
        $mail->Username = $dados['smtp_username'];
        $mail->Password = $dados['smtp_password'];
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->From = $dados['smtp_username'];
        $mail->FromName = utf8_decode($dados['smtp_fromname']);
        $mail->Subject = "Teste Envio e-mail";
        if (strlen($row_smtp->smtp_bcc) >= 1) {
            $mail->AddBCC($dados['smtp_bcc']);
        }
        $mail->AddAddress($dados['smtp_username']);
        $mail->AddReplyTo($dados['smtp_bcc']);
        $mail->Body = "Teste de Conexão E-mail";
        if ($mail->Send()) {
            echo 0;
        } else {
            echo "Erro: $mail->ErrorInfo <br/> Prováveis causas: <br> - E-mail, Senha, Porta ou Servidor SMTP incorretos.";
        }
    }

    public function smtp_emails_nivers_do_dia()
    {
        $query = $this->db->query("SELECT * FROM smtp");
        $row_smtp = $query->row();

        if ($query->num_rows() > 0) {

            require_once "application/helpers/phpmailer/class.phpmailer.php";

            $dataMes = date('m');
            $dataDia = date('d');
            $dataAtual = date('Y-m-d');

            switch (date("m")) {
                case "01":
                    $mes = "JANEIRO";
                    break;
                case "02":
                    $mes = "FEVEREIRO";
                    break;
                case "03":
                    $mes = "MARÇO";
                    break;
                case "04":
                    $mes = "ABRIL";
                    break;
                case "05":
                    $mes = "MAIO";
                    break;
                case "06":
                    $mes = "JUNHO";
                    break;
                case "07":
                    $mes = "JULHO";
                    break;
                case "08":
                    $mes = "AGOSTO";
                    break;
                case "09":
                    $mes = "SETEMBRO";
                    break;
                case "10":
                    $mes = "OUTUBRO";
                    break;
                case "11":
                    $mes = "NOVEMBRO";
                    break;
                case "12":
                    $mes = "DEZEMBRO";
                    break;
            }

            $queryNiver = $this->db->query("SELECT * FROM cad_aniversariantes WHERE MONTH(data_nasc) = '$dataMes' AND DAY(data_nasc) = '$dataDia' AND ativo = '1'");

            if ($queryNiver->num_rows() > 0) {

                $queryNiverEnviados = $this->db->query("SELECT * FROM cad_aniversariantes WHERE data_envio = '$dataAtual'");

                if ($queryNiverEnviados->num_rows() > 0) {
                    echo "Emais diários já enviados!";
                } else {

                    foreach ($queryNiver->result_array() as $rowNivers) {

                        $mensagem = '
                        <table border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
                        <tbody>
                        <tr>
                        <td valign="top" style="padding:0cm 0cm 0cm 0cm">
                        <div align="center">
                        <table border="0" cellspacing="0" cellpadding="0" width="0" style="width:450.0pt">
                        <tbody>
                        <tr style="height:88.5pt">
                        <td style="padding:.75pt .75pt .75pt .75pt;height:88.5pt">
                        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:30.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#939598">' . strtoupper($rowNivers['empresa']) . '<u></u><u></u></span></p>
                        </td>
                        <td style="padding:.75pt .75pt .75pt .75pt;height:88.5pt">
                        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:18.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#939598">ANIVERSARIANTE DO DIA<u></u><u></u></span></p>
                        </td>
                        </tr>
                        <tr>
                        <td colspan="2" valign="top" style="padding:.75pt .75pt .75pt .75pt">
                        <table border="0" cellspacing="0" cellpadding="0" style="background:white">
                        <tbody>
                        <tr style="height:122.25pt">
                        <td width="199" style="width:150.0pt;background:#ffae00;padding:0cm 0cm 0cm 0cm;height:122.25pt">
                        <div align="center">
                        <table border="0" cellspacing="0" cellpadding="0" style="background:#ffae00">
                        <tbody>
                        <tr>
                        <td style="padding:0cm 0cm 0cm 0cm">
                        <p class="MsoNormal"><strong><span style="font-size:60.0pt;font-family:&quot;Arial&quot;,sans-serif;color:white">' . $dataDia . '</span></strong><span style="font-size:60.0pt;font-family:&quot;Arial&quot;,sans-serif;color:white"><u></u><u></u></span></p>
                        </td>
                        </tr>
                        <tr>
                        <td style="padding:0cm 0cm 0cm 0cm">
                        <p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Arial&quot;,sans-serif;color:white">DE ' . utf8_decode($mes) . '</span></strong><span style="font-size:10.5pt;font-family:&quot;Arial&quot;,sans-serif;color:white"><u></u><u></u></span></p>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                        </td>
                        <td width="399" style="width:300.0pt;background:#ffecc2;padding:0cm 0cm 0cm 0cm;height:122.25pt">
                        <div align="center">
                        <table border="0" cellspacing="0" cellpadding="0" style="background:#ffecc2">
                        <tbody>
                        <tr>
                        <td style="padding:0cm 0cm 0cm 0cm">
                        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:45.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#ee7b00">PARABÉNS!<u></u><u></u></span></p>
                        </td>
                        </tr>
                        <tr>
                        <td style="padding:0cm 0cm 0cm 0cm">
                        <p class="MsoNormal" align="center" style="text-align:center"><strong><span style="font-size:10.5pt;font-family:&quot;Arial&quot;,sans-serif;color:#ee7b00">A ' . strtoupper($rowNivers['empresa']) . ' DESEJA UM FELIZ ANIVERSÁRIO PARA:</span></strong><span style="font-size:10.5pt;font-family:&quot;Arial&quot;,sans-serif;color:#ee7b00"><u></u><u></u></span></p>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        </td>
                        </tr>
                        <tr style="height:3.75pt">
                        <td colspan="2" style="padding:.75pt .75pt .75pt .75pt;height:3.75pt">
                        <p class="MsoNormal">&nbsp;<u></u><u></u></p>
                        </td>
                        </tr>
                        <tr>
                        <td width="600" colspan="2" style="width:396.75pt;padding:.75pt .75pt .75pt .75pt">
                        <div align="center">
                        <table border="0" cellspacing="0" cellpadding="0" width="0" style="width:396.75pt">
                        <tbody>
                        <tr>
                        <td style="padding:2.25pt 2.25pt 2.25pt 2.25pt">
                        <p class="MsoNormal" align="center" style="text-align:center"><strong><span style="font-size:10.5pt;font-family:&quot;Arial&quot;,sans-serif">' . strtoupper($rowNivers['nome']) . '</span></strong><span style="font-size:10.5pt;font-family:&quot;Arial&quot;,sans-serif"><u></u><u></u></span></p>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                        </td>
                        </tr>
                        <tr style="height:3.75pt">
                        <td colspan="2" style="padding:.75pt .75pt .75pt .75pt;height:3.75pt">
                        <p class="MsoNormal">&nbsp;<u></u><u></u></p>
                        </td>
                        </tr>
                        <tr style="height:88.5pt">
                        <td colspan="2" style="padding:.75pt .75pt .75pt .75pt;height:88.5pt">
                        <p class="MsoNormal" align="center" style="text-align:center"><strong><span style="font-family:&quot;Arial&quot;,sans-serif;color:#ee7b00">Lembre-se de cumprimentar seus colegas de trabalho neste dia especial!</span></strong><span style="font-family:&quot;Arial&quot;,sans-serif;color:#ee7b00"><u></u><u></u></span></p>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        ';

                        $mail = new PHPMailer();
                        $mail->IsSMTP();
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = "ssl";
                        $mail->WordWrap = 80;
                        $mail->IsHTML(true);
                        $mail->Port = $row_smtp->smtp_port;
                        $mail->Host = $row_smtp->smtp_host;
                        $mail->Username = $row_smtp->smtp_username;
                        $mail->Password = $row_smtp->smtp_password;
                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        );

                        $mail->From = strtoupper($rowNivers['empresa']);
                        $mail->FromName = strtoupper($rowNivers['empresa']);
                        $mail->Subject = utf8_decode("A $rowNivers[empresa] tem uma menssagem especial para você!");
                        $mail->AddAddress($rowNivers['email']);

                        $mail->Body = "$mensagem";
                        if ($mail->Send()) {
                            $updateNiver = $this->db->query("UPDATE cad_aniversariantes SET data_envio = NOW() WHERE id = '$rowNivers[id]'");

                            echo 0;
                        } else {
                            echo "Erro: $mail->ErrorInfo <br/> Prováveis causas: <br> - E-mail, Senha, Porta ou Servidor SMTP incorretos.";
                        }
                    }
                }
            } else {
                echo "Não há aniversáriantes na data de hoje!";
            }
        } else {
            echo "Favor configurar o servidor de e-mail";
        }
    }

    public function listar_iso()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Arquivos Controle de Qualidade';
        $dados['arq_iso'] = $this->arquivo->getArquivo("pasta = 'iso'");

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('rh/iso/lista_iso');
        $this->load->view('fix/footer_NS');
    }

    public function excluir_iso($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo json_encode(array('success' => 'true', 'message' =>  'Arquivo deletado com sucesso!'));
            exit();
        }
    }

    public function cad_iso()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            $arquivo_enviado = false;

            if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'iso') == FALSE) {
                echo json_encode(array('success' => 'false', 'message' => 'Desculpe esse arquivo já foi cadastrado!'));
            } else {
                $dados = $this->uploadArquivos('./uploads/iso');

                if ($dados) {
                    $nome_arquivo = $dados['file_name'];
                    $path = $dados['full_path'];
                    $arquivo_enviado = true;
                }

                if ($arquivo_enviado) {
                    $retorno = $this->cadastro->digitalizacaoIso($descricao, $nome_arquivo, $path);
                    echo json_encode(array('success' => 'true', 'message' => 'Arquivo cadastrado com sucesso!'));

                } else {
                    echo json_encode(array('success' => 'false', 'message' => 'Erro ao cadastrar arquivo!'));
                }
            }
        } else {
            echo json_encode(array('success' => 'false', 'message' => 'Processo não realizado!'));
        }
    }

    public function editar_iso($id_arquivo_iso)
    {
        $dados = $this->ashownett->get("id = '$id_arquivo_iso'");

        if (!empty($dados)){
            echo json_encode(array('success' => 'true', 'data' => $dados));
        } else {
            echo json_encode(array('success' => 'false', 'message' => 'Arquivo não encontrado.'));
        }

    }

    public function edit_iso($id_arquivo_iso)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            $dados = $this->uploadArquivos('./uploads/iso');
            
            if ($dados) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo_iso);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'iso', $id_arquivo_iso);
                echo json_encode(array('success' => 'true', 'message' => 'Arquivo alterado com sucesso!'));
            } else {
                echo json_encode(array('success' => 'false', 'message' => 'Erro ao alterar arquivo!'));

            }
        } else {
            if ($dados = $this->uploadArquivos('./uploads/iso')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            } else {
                $nome_arquivo = false;
                $path = false;
            }
            $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'iso', $id_arquivo_iso);
            echo json_encode(array('success' => 'true', 'message' => 'Dados alterados com sucesso!'));
        }
    }



    public function listar_comercial()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de arquivos - Comercial';
        $dados['arq_comercial'] = $this->arquivo->getArquivo("pasta = 'comercial'");

        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/comercial/lista_comercial');
        $this->load->view('fix/footer4');
    }

    public function comercial()
    {
        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de arquivos - Comercial';
        $this->load->view('fix/header4', $dados);
        $this->load->view('rh/comercial/cad_comercial');
        $this->load->view('fix/footer4');
    }

    public function excluir_comercial($id)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('arquivos', 1)->row();

        unlink("$file->path");

        if ($this->arquivo->excluirArquivoById($id)) {
            echo "Arquivo deletado com sucesso!";
        }
    }

    public function cad_comercial()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($descricao == "") {
            // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
            $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Informe descrição!</div>');
            redirect('cadastros/iso');
        } else {

            if ($arquivo) {
                $arquivo_enviado = false;

                if ($this->cadastro->verificaCadastroArquivo($descricao, 'arquivos', 'comercial') == FALSE) {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
                    redirect('cadastros/comercial');
                } else {
                    $dados = $this->uploadArquivos('./uploads/comercial');
                    // die(json_encode($dados));
                    if ($dados) {
                        $nome_arquivo = $dados['file_name'];
                        $path = $dados['full_path'];
                        $arquivo_enviado = true;
                    }

                    if ($arquivo_enviado) {
                        $retorno = $this->cadastro->digitalizacaoComercial($descricao, $nome_arquivo, $path);
                        // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                        $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo cadastrado com sucesso!</div>');
                        redirect('cadastros/comercial');
                    } else {
                        // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                        $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao cadastrar Arquivo!</div>');
                        redirect('cadastros/comercial');
                    }
                }
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Processo não realizado!</div>');
                redirect('cadastros/comercial');
            }
        }
    }

    public function editar_comercial($id_arquivo_comercial)
    {

        // $this->auth->is_allowed('usuarios_update');
        $dados['titulo'] = 'Editar arquivos';
        $dados['dados_arquivo_comercial'] = $this->ashownett->get("id = '$id_arquivo_comercial'");
        //$dados['dados_folheto'] = $this->ashownett->get("id = '$id_arquivo_iso'");


        $this->load->view('fix/header4', $dados);
        if (!empty($dados['dados_arquivo_comercial']))
            $this->load->view('rh/comercial/edit_comercial');
        else
            $this->load->view('erros/403');
        $this->load->view('fix/footer4');
    }

    public function edit_comercial($id_arquivo_comercial)
    {
        $descricao = $this->input->post('descricao');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        if ($arquivo) {
            if ($dados = $this->uploadArquivos('./uploads/comercial')) {
                $nome_arquivo = $dados['file_name'];
                $path = $dados['full_path'];
                $arquivo_enviado = true;
            }

            if ($arquivo_enviado) {

                $this->db->where('id', $id_arquivo_comercial);
                $file = $this->db->get('arquivos', 1)->row();

                unlink("$file->path");

                $retorno = $this->cadastro->editArquivoFormulario($descricao, $nome_arquivo, $path, 'comercial', $id_arquivo_comercial);
                // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Arquivo alterado com sucesso!</div>');
                redirect("cadastros/editar_comercial/$id_arquivo_comercial");
            } else {
                // die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Erro ao cadastrar Banner!</div>')));
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Erro ao alterar arquivo!</div>');
                redirect("cadastros/editar_comercial/$id_arquivo_comercial");
            }
        } else {
            // if ($dados = $this->uploadArquivos('./uploads/comercial')) {
            //     $nome_arquivo = $dados['file_name'];
            //     $path = $dados['full_path'];
            //     $arquivo_enviado = true;
            // }
            //die(json_encode((array($descricao, $nome_arquivo, $path, $id_arquivo_comercial))));
            $retorno = $this->cadastro->editArquivoFormularioComercial($descricao,  $id_arquivo_comercial);
            // die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Banner cadastrado com sucesso!</div>', 'retorno',$retorno)));
            $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Dados alterados com sucessoo!</div>');
            redirect("cadastros/editar_comercial/$id_arquivo_comercial");
        }
    }

    //salva o arquivo no diretorio
    private function upload_cliente($upload_path = 'uploads/logotipos/')
    {
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg';
        $config['max_size']    = '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('logotipo'))
            return $this->upload->data();
        else
            return false;
    }

    public function modificarAcessoOmniGr()
    {
        $this->load->model('log_shownet');
        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        try {
            $dados = $this->input->post();

            //pegar dados antigos do cliente.
            $clientebd = $this->cliente->get(array('id' => $dados['id_cliente']));
            $dados_cliente_antigo = array(
                'acesso_omnigr'       => $clientebd->acesso_omnigr,
            );

            $dados_cliente_novo = array('acesso_omnigr' => $dados['acesso']);
            $query = $this->db->QUERY("UPDATE cad_clientes SET acesso_omnigr = '{$dados['acesso']}' WHERE id = '{$dados['id_cliente']}'");
            $this->log_shownet->gravar_log($id_user, 'cad_clientes', $dados['id_cliente'], 'atualizar', $dados_cliente_antigo, $dados_cliente_novo);
            echo json_encode(array('success' => true, 'mensagem' => 'Acesso alterado com sucesso!'));
        } catch (Exception $e) {
            return $e;
        }
    }

    public function habilitarAcessoOCR()
    {
        $this->load->model('log_shownet');

        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        try {
            $dados = $this->input->post();

            //pegar dados antigos do cliente.
            $clientebd = $this->cliente->get(array('id' => $dados['id_cliente']));
            $dados_cliente_antigo = array(
                'habilita_ocr'       => $clientebd->habilita_ocr,
            );

            $dados_cliente_novo = array('habilita_ocr' => $dados['acesso']);
            $this->db->QUERY("UPDATE cad_clientes SET habilita_ocr = '{$dados['acesso']}' WHERE id = '{$dados['id_cliente']}'");
            $this->log_shownet->gravar_log($id_user, 'cad_clientes', $dados['id_cliente'], 'atualizar', $dados_cliente_antigo, $dados_cliente_novo);
            echo json_encode(array('success' => true, 'mensagem' => 'Acesso alterado com sucesso!'));
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Atualiza os produtos e as permissões do cliente
     * @param post
     * @return json
     */
    function atualizar_produtos_cliente()
    {
        //para registro de log
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        $dados = $this->input->post();
        if (empty($dados)) {
            exit(['status' => false, 'mensagem' => lang('parametros_invalidos')]);
        }

        $id_cliente = $dados['id_cliente'];
        $ids_produtos = $dados['ids_produtos'];

        $permissoes = $this->cadastro->get_permissoes_produtos($ids_produtos, 'permissao.cod_permissao');
        $permissoes = array_column_custom($permissoes, 'cod_permissao');

        $dados_cliente = [
            'ids_produtos' => $dados['ids_produtos'] ? json_encode($dados['ids_produtos']) : null,
            'permissoes' => $permissoes ? json_encode($permissoes) : null,
            'observacoes' => $dados['observacoes']
        ];

        $clientebd = $this->cliente->get_cliente($id_cliente);
        if ($clientebd) {
            $dados_clientes_antigo = array(
                'ids_produtos'     => $clientebd->ids_produtos,
                'permissoes'     => $clientebd->permissoes,
                'observacoes'     => $clientebd->observacoes
            );
        }

        //Atualiza os dados do cliente
        if ($this->cliente->atualizar_cliente($id_cliente, $dados_cliente)) {

            //registra o log de atualização do cliente
            $dados_novos_formatados = $dados_cliente;
            $this->log_shownet->gravar_log($id_user, 'cad_clientes', $id_cliente, 'atualizar', $dados_clientes_antigo, $dados_novos_formatados);

            //Atualiza as permissões dos usuários admins e masters do cliente
            $this->load->model('usuario_gestor');
            $this->usuario_gestor->atualizar_usuarios_pelo_cliente(
                $id_cliente,
                ['permissoes' => $permissoes ? json_encode($permissoes) : null]
            );
            //listar customizado, pois o atualizar_usuarios_pelo_cliente seleciona somente admin e master
            $clientebdGestor = $this->usuario_gestor->listarCustom(array(
                'id_cliente' => $id_cliente,
                'tipo_usuario' => array('administrador', 'master')
            ));

            $dados_clientebdGestor_antigo = json_encode($clientebdGestor);
            $this->log_shownet->gravar_log($id_user, 'usuario_gestor', $id_cliente, 'atualizar', $dados_clientebdGestor_antigo, ['permissoes' => $permissoes ? json_encode($permissoes) : null]);

            exit(json_encode(['status' => true, 'mensagem' => lang('produtos_vinculados_sucesso')]));
        }
        exit(json_encode(['status' => false, 'mensagem' => lang('vinculacao_produtos_erro')]));
    }
}
