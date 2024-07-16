<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contratos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('cliente');
        $this->load->model('auth');
        $this->load->model('contrato');
        $this->load->model('equipamento');
        $this->load->model('veiculo');
        $this->load->model('log_veiculo');
        $this->load->model('usuario_gestor');
        $this->load->library('upload');
        $this->load->library('pagination');
        $this->load->helper('download');
        $this->load->model('linha');
        $this->load->model('cadastro');
        $this->load->model('fatura');
        $this->load->model('suprimento');
        $this->load->model('iscas/iscas', 'isca');
    }

    public function label_btnContrato($status, $id_contrato, $id_cliente, $tipo_proposta)
    {
        switch ($status) {
            case 0:
                $statuslabel = "<span class='label label-success active" . $id_contrato . "'>Cadastrado</span>";
                $btnContrato = "<button data-status='ativo' onclick='render(this)' class='element active con" . $id_contrato . "' data-id_contrato='" . $id_contrato . "' data-toggle='modal' data-modal='#cancelarContrato' data-target='#myModal_cancelar_contrato' data-url='" . site_url('contratos/verificar_contrato/' . $status . '/' . $id_contrato . '/' . $id_cliente . '/' . $tipo_proposta) . "' title='Cancelar Contrato' >
                                    <img id='element_" . $id_contrato . "' src='" . versionFile('media/img/icons/contratos', 'contrato.svg') . "' alt='Desativar Contrato' class='ativado' style='height: 30px'/>
                                </button>";
                break;
            case 1:
                $statuslabel = "<span class='label label-success status" . $id_contrato . "'>Esperando OS</span>";
                $btnContrato = "<button data-status='ativo' onclick='render(this)' class='element active con" . $id_contrato . "' data-id_contrato='" . $id_contrato . "' data-toggle='modal' data-modal='#cancelarContrato' data-target='#myModal_cancelar_contrato' data-url='" . site_url('contratos/verificar_contrato/' . $status . '/' . $id_contrato . '/' . $id_cliente . '/' . $tipo_proposta) . "' title='Alterar Status' >
                                    <img id='element_" . $id_contrato . "' src='" . versionFile('media/img/icons/contratos', 'contrato.svg') . "' alt='Desativar Contrato' class='ativado' style='height: 30px'/>
                                </button>";
                break;
            case 2:
                $statuslabel = "<span class='label label-success status" . $id_contrato . "'>Ativo</span>";
                $btnContrato = "<button data-status='ativo' onclick='render(this)' class='element active con" . $id_contrato . "' data-id_contrato='" . $id_contrato . "' data-toggle='modal' data-modal='#cancelarContrato' data-target='#myModal_cancelar_contrato' data-url='" . site_url('contratos/verificar_contrato/' . $status . '/' . $id_contrato . '/' . $id_cliente . '/' . $tipo_proposta) . "' title='Alterar Status' >
                                    <img id='element_" . $id_contrato . "' src='" . versionFile('media/img/icons/contratos', 'contrato.svg') . "' alt='Desativar Contrato' class='ativado' style='height: 30px'/>
                                </button>";
                break;
            case 3:
                $statuslabel = "<span class='label label-default status" . $id_contrato . "'>Cancelado</span>";
                $btnContrato = "<button class='element active' disabled>
                                    <img src='" . versionFile('media/img/icons/contratos', 'contrato.svg') . "' alt='Contrato Desativado' title='Contrato Cancelado' class='desativado' style='height: 30px'/>
                                </button>";
                break;
            case 5:
                $statuslabel = "<span class='label label-default status" . $id_contrato . "'>Bloqueado</span>";
                $btnContrato = "<button class='element active' disabled>
                                    <img src='" . versionFile('media/img/icons/contratos', 'contrato.svg') . "' alt='Contrato Desativado' title='Contrato Cancelado' class='desativado' style='height: 30px'/>
                                </button>";
                break;
            case 6:
                $statuslabel = "<span class='label label-danger status" . $id_contrato . "'>Encerrado</span>";
                $btnContrato = "<button class='element active' disabled>
                                    <img src='" . versionFile('media/img/icons/contratos', 'contrato.svg') . "' alt='Contrato Desativado' title='Contrato Encerrado' class='desativado' style='height: 30px'/>
                                </button>";
                break;
            case 7:
                $statuslabel = "<span class='label label-warning status" . $id_contrato . "'>Em Processo de Retirada</span>";
                $btnContrato = "<button data-status='ativo' onclick='render(this)' class='element active con" . $id_contrato . "' data-id_contrato='" . $id_contrato . "' data-toggle='modal' data-modal='#cancelarContrato' data-target='#myModal_cancelar_contrato' data-url='" . site_url('contratos/verificar_contrato/' . $status . '/' . $id_contrato . '/' . $id_cliente . '/' . $tipo_proposta) . "' title='Desativar Contrato'>
                                    <img src='" . versionFile('media/img/icons/contratos', 'contrato.svg') . "' alt='Desativar Contrato' title='Cancelar Contrato' class='ativado' style='height: 30px'/>
                                </button>";
                break;
            default:
                $statuslabel = '<span class="label label-default">Outro</span>';
                break;
        }
        return array($statuslabel, $btnContrato);
    }

    public function btnTaxaBoleto($boleto, $id_contrato, $disabled)
    {
        if ($boleto == 1) {
            $button_boleto = "<button title='Desativar taxa de boleto' data-id_contrato='" . $id_contrato . "' class='element taxaBoleto' data-novo_status='0' " . $disabled . ">
                <img src='" . versionFile('media/img/icons/contratos', 'taxa_de_boleto.svg') . "' alt='Taxa de boleto ativada' class='ativado' style='height: 30px'/></button>";
        } else {
            $button_boleto = "<button title='Ativar taxa de boleto' data-id_contrato='" . $id_contrato . "' class='element taxaBoleto' data-novo_status='1' " . $disabled . ">
                <img src='" . versionFile('media/img/icons/contratos', 'taxa_de_boleto.svg') . "' alt='Taxa de boleto desativada' class='desativado' style='height: 30px'/> </button>";
        }
        return $button_boleto;
    }

    public function btnFaturaDisp($consumo_fatura, $id_contrato, $disabled)
    {
        if ($consumo_fatura == 1) {
            $button = "<button title='Desativar fatura por disp.' data-id_contrato='" . $id_contrato . "' class='element active consumoFatura' data-novo_status='0' " . $disabled . ">
                <img src='" . versionFile('media/img/icons/contratos', 'fatura.svg') . "' alt='Desativar fatura por disp.' class='ativado' style='height: 30px'/></button>";
        } else {
            $button = "<button title='Ativar fatura por disp' data-id_contrato='" . $id_contrato . "' class='element active consumoFatura' data-novo_status='1' " . $disabled . ">
                <img src='" . versionFile('media/img/icons/contratos', 'fatura.svg') . "' alt='Ativar fatura por disp.' class='desativado' style='height: 30px'/></button>";
        }
        return $button;
    }

    public function btnAcoes($tipo_proposta, $id_contrato, $id_cliente, $countChips = false, $disabled)
    {
        if ($tipo_proposta == 1) {
            $btnsAcao = "<div class='coluna'>
                                <button href='" . site_url('contratos/simCard/' . $id_cliente . '/' . $id_contrato . '/' . $tipo_proposta) . "' data-id_contrato='" . $id_contrato . "' class='chips element'  title='Sim Cards' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'visualizar_placas.svg') . "' alt='Sim Cards' class='ativado' style='height: 30px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button class='add element ativado' data-toggle='modal' data-target='#novo_chip' data-id_contrato='" . $id_contrato . "' title='Adicionar Chip' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'adicionar_placa.svg') . "' alt='Adicionar Chip' class='ativado' style='height: 30px'/>
                                </button>
                         </div>";
        } elseif ($tipo_proposta == 4) {
            $btnsAcao = "<div class='coluna'>
                                <button href='" . site_url('contratos/ajax_list_tornozeleiras/' . $id_cliente . '/' . $id_contrato) . "' data-id_contrato='" . $id_contrato . "' class='tornozeleiras element' title='Tornozeleiras no Contrato' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'visualizar_placas.svg') . "' alt='Tornozeleiras' class='ativado' style='height: 30px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button class='add element ativado' data-toggle='modal' onclick='limparSerial()' data-target='#nova_tornozeleira' data-id_contrato='" . $id_contrato . "' title='Adicionar Tornozeleira' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'adicionar_placa.svg') . "' alt='Adicionar Tornozeleira' class='ativado' style='height: 30px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button class='add element ativado' data-toggle='modal' onclick='limparSerialSuprimento()' data-target='#novo_suprimento' data-id_contrato='" . $id_contrato . "' title='Adicionar Suprimento' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'suprimentos.svg') . "' alt='Adicionar Suprimento' class='ativado' style='height: 30px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button href='" . site_url('contratos/ajax_list_suprimentos/' . $id_cliente . '/' . $id_contrato) . "' data-id_contrato='" . $id_contrato . "' class='suprimentos element' title='Suprimentos no Contrato' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'visualizar_suprimentos.svg') . "' alt='Suprimentos' class='ativado' style='height: 30px'/>
                                </button>
                         </div>";
        } elseif ($tipo_proposta == 6) {
            $btnsAcao = "<div class='coluna' style='margin: 0px;'>
                                <button href='" . site_url('contratos/ajax_get_iscas') . "' data-id_contrato='" . $id_contrato . "' class='iscas element'  title='Iscas no Contrato' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'iscas_no_contrato.svg') . "' alt='Iscas' style='height: 42px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button class='add element' data-toggle='modal' data-target='#nova_isca' data-id_contrato='" . $id_contrato . "' title='Adicionar Isca' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'adicionar_isca.svg') . "' alt='Adicionar Isca' style='height: 42px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button class='add_contrato_iscas element' data-toggle='modal' data-target='#nova_isca_lote' data-id_contrato='" . $id_contrato . "' data-id_cliente='" . $id_cliente . "' title='Adicionar Iscas em Lote' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'adicionar_multiplas_iscas.svg') . "' alt='Adicionar Isca em Lote' style='height: 42px'/>
                                </button>
                         </div>";
        } else {
            $btnsAcao = "<div class='coluna'>
                                <button data-id_contrato='" . $id_contrato . "' class='placas element' title='Placas no Contrato' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'visualizar_placas.svg') . "' alt='Placas' class='ativado' style='height: 30px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button class='add element ativado' data-toggle='modal' data-target='#nova_placa' data-id_contrato='" . $id_contrato . "' title='Adicionar Placa' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'adicionar_placa.svg') . "' alt='Adicionar Placa' class='ativado' style='height: 30px'/>
                                </button>
                         </div>
                         <div class='coluna'>
                                <button class='add_multi_placa element ativado' data-toggle='modal' data-target='#novas_placas' data-id_contrato='" . $id_contrato . "' title='Adicionar Multiplas Placas' " . $disabled . ">
                                    <img src='" . versionFile('media/img/icons/contratos', 'adicionar_multiplas_placas.svg') . "' alt='Adicionar Multiplas Placas' class='ativado' style='height: 30px'/>
                                </button>
                         </div>";
        }

        return $btnsAcao;
    }

    public function btnDigitalizar($qtde_arquivos, $id_contrato, $disabled)
    {
        if ($qtde_arquivos > 0) {
            $arquivo_btn = "<button class='btn digitalizar element digi_" . $id_contrato . "' data-id_contrato='" . $id_contrato . "' title='Digitalizar Contrato' " . $disabled . ">
                              <img src='" . versionFile('media/img/icons/contratos', 'digitalizados.svg') . "' alt='digitalizados' class='ativado' style='height: 30px' />
                            </button>";
        } else {
            $arquivo_btn = "<button type='button' data-id_contrato='" . $id_contrato . "' class='element digitalizar btn-atualizar-equipamento digi_" . $id_contrato . "' title='Digitalizar Contrato' " . $disabled . ">
                                <img src='" . versionFile('media/img/icons/contratos', 'digitalizados.svg') . "' alt='digitalizados' class='desativado' style='height: 30px' />
                            </button>";
        }
        return $arquivo_btn;
    }

    public function btnFimAditivo($dataFim_aditivo = false, $id_contrato, $disabled)
    {
        if ($dataFim_aditivo && $dataFim_aditivo != NULL && $dataFim_aditivo != '0000-00-00') {
            $dataFim_btn = "<button class='element modalDataFim dataFim" . $id_contrato . "' data-id_contrato='" . $id_contrato . "' data-fim_aditivo='" . $dataFim_aditivo . "' title='Fim do aditivo' " . $disabled . ">
                                <img src='" . versionFile('media/img/icons/contratos', 'fim_do_aditivo.svg') . "' alt='Fim do aditivo' class='ativado' style='height: 30px'/>
                            </button>";
        } else {
            $dataFim_btn = "<button class='element modalDataFim dataFim" . $id_contrato . "' data-id_contrato='" . $id_contrato . "' data-fim_aditivo='" . $dataFim_aditivo . "' title='Fim do aditivo' " . $disabled . ">
                                <img src='" . versionFile('media/img/icons/contratos', 'fim_do_aditivo.svg') . "' alt='Fim do aditivo' class='desativado' style='height: 30px'/>
                            </button>";
        }
        return $dataFim_btn;
    }

    public function btnTipoFaturamento($consumo_fatura, $id_contrato, $tipo_proposta)
    {
        $button = "<button title='" . lang('tipo_faturamento') . "' class='element active' disabled>
                        <img src='" . versionFile('media/img/icons/contratos', 'fatura.svg') . "' alt='" . lang('tipo_faturamento') . "' class='ativado' style='height: 30px'/>
                    </button>";

        if ($this->auth->is_allowed_block('edit_tipo_faturamento')) {
            $button = "<button title='" . lang('tipo_faturamento') . "' data-id_contrato='" . $id_contrato . "' data-consumo='" . $consumo_fatura . "' data-tipo_proposta='" . $tipo_proposta . "' class='element active tipFat_" . $id_contrato . " tipoFaturamento'>
                            <img src='" . versionFile('media/img/icons/contratos', 'fatura.svg') . "' alt='" . lang('tipo_faturamento') . "' class='ativado' style='height: 30px'/>
                        </button>";
        }


        return $button;
    }

    //lista os contratos do cliente com suas informações pertinentes
    public function ajax_load_contratos()
    {

        $dados = $this->input->post();
        $draw = $dados['draw'] ? $dados['draw'] : 1; # Draw Datatable
        $start = $dados['start'] ? $dados['start'] : 0; # Contador (Start request)
        $limit = $dados['length'] ? $dados['length'] : 10; # Limite de registros na consulta
        $search = $dados['search']['value'] ? $dados['search']['value'] : false; # Campo pesquisa
        $tipo_proposta = $dados['tipo_proposta'];
        $tipo_busca = $dados['tipo_busca'];

        if (isset($dados['id_cliente']) && $dados['id_cliente']) {
            $id_cliente = $dados['id_cliente'];

            $where = array('id_cliente' => $id_cliente);
            if (is_numeric($tipo_proposta)) {
                $where = array('id_cliente' => $id_cliente, 'tipo_proposta' => $tipo_proposta);
            }

            //carrega os contratos
            $retorno = $this->contrato->list_contratos_serveside(
                $where,
                'con.id, con.id_cliente, con.tipo_proposta, con.quantidade_veiculos, con.valor_mensal, con.valor_instalacao, con.status, con.dataFim_aditivo , vend.nome as nome_vend',
                $start,
                $limit,
                $search,
                $draw,
                $tipo_busca
            );

            if ($retorno['contratos']) {
                $dados = array();
                $instalacao = 0;

                foreach ($retorno['contratos'] as $contrato) {

                    $itens_ativos = 0;
                    if (isset($contrato->tipo_proposta) && !empty($contrato)) {
                        $disabled = "";  //diz se deixa clicável os botoes de administrar o contrato
                        $quantidade_veiculos = $contrato->quantidade_veiculos;

                        //mensalidade e adesao
                        $mensalidade = 'R$ ' . number_format($contrato->valor_mensal, 2, ',', '.');
                        $instalacao = 'R$ ' . number_format($contrato->valor_instalacao, 2, ',', '.');
                        if ($contrato->status == 3) {
                            $disabled = "";
                        } elseif (in_array($contrato->status, array(1, 2, 7))) {  //calculos para contratos ativos e em tramite de O.S.
                            if ($contrato->tipo_proposta == 1) {
                                $itens_ativos = $this->linha->linhas_ativas(array('id_cliente_sim2m' => $contrato->id_cliente, 'id_contrato_sim2m' => $contrato->id, 'status' => 1));
                            } elseif ($contrato->tipo_proposta == 4) {
                                $itens_ativos = $this->cadastro->total_tornozeleiras_ativas(array('c.id_cliente' => $id_cliente, 'id_contrato' => $contrato->id, 'v.status' => 'ativo', 'v.equipamento !=' => null, 'v.equipamento !=' => ''));
                            } elseif ($contrato->tipo_proposta == 6) {
                                $itens_ativos = $this->contrato->total_lista_iscas(array('id_cliente' => $contrato->id_cliente, 'id_contrato' => $contrato->id, 'status' => 1));
                            } else {
                                $itens_ativos = $this->veiculo->total_relacao_placas_veiculos_ativos_contrato(array('c.id_cliente' => $contrato->id_cliente, 'c.id' => $contrato->id));
                            }
                        }

                        if ($this->auth->is_allowed_block('edit_contrato')) {
                            $vendedor = "<a id='text_vendedor'" . $contrato->id . " onclick='edit_vendedor(" . $contrato->id . ")' class='modal_vendedor' style='cursor: pointer;' data-toggle='modal' data-target='#modalVendedor'>" . $contrato->nome_vend . "</a>";
                        } else {
                            $vendedor = $contrato->nome_vend;
                        }

                        $qtde_arquivos = $this->contrato->get_total_arquivos($contrato->id);
                        $arquivo_btn = $this->btnDigitalizar($qtde_arquivos, $contrato->id, $disabled);  //botao digitalizar
                        $consumo_fatura = $this->db->get_where('contratos', array('id' => $contrato->id))->row(0);
                        $buttonTipoFaturamento = $this->btnTipoFaturamento($consumo_fatura->consumo_fatura, $contrato->id, $contrato->tipo_proposta);
                        $button_taxa_boleto = $this->btnTaxaBoleto($consumo_fatura->boleto, $contrato->id, $disabled);
                        $Acoes = $this->btnAcoes($contrato->tipo_proposta, $contrato->id, $id_cliente, $contrato->quantidade_veiculos, $disabled);
                        $button_fim_aditivo = $this->btnFimAditivo($contrato->dataFim_aditivo, $contrato->id, $disabled);
                        $labelAndContrato = $this->label_btnContrato($contrato->status, $contrato->id, $id_cliente, $contrato->tipo_proposta);

                        $menu = "<div style='display: flex; align-items: center; flex-flow: row wrap'>
                                    <div class='coluna '>
                                        <a type='button' class='btn element ativado' href='" . site_url('contratos/imprimir_contrato/' . $contrato->id . '/' . $id_cliente . '/' . $contrato->tipo_proposta . '') . "' target='_blank' title='Imprimir Contrato'>
                                            <img src='" . versionFile('media/img/icons/contratos', 'impressao.svg') . "' alt='Imprimir Contrato' style='height: 30px' />
                                        </a>
                                    </div>
                                    <div class='coluna'>
                                       " . $labelAndContrato[1] . "
                                    </div>
                                    <div class='coluna'>
                                       " . $buttonTipoFaturamento . "
                                    </div>
                                    <div class='coluna'>
                                       " . $button_taxa_boleto . "
                                    </div>
                                    <div class='coluna'>
                                        " . $button_fim_aditivo . "
                                    </div>
                                        " . $Acoes . "
                                </div>";

                        $dados['data'][] = array(
                            'contrato' => $contrato->id,
                            'vendedor' => $vendedor,
                            'itens' => $quantidade_veiculos,
                            'itens_ativos' => $itens_ativos,
                            'valor_mensal' => $mensalidade,
                            'valor_instalacao' => $instalacao,
                            'dataFim_aditivo' => $contrato->dataFim_aditivo ? data_for_humans($contrato->dataFim_aditivo) : '',
                            'status' => $labelAndContrato[0],
                            'digitalizar' => $arquivo_btn,
                            'administrar' => $menu,
                            'tipo_proposta' => $contrato->tipo_proposta
                        );
                    }
                } //end foreach

                echo json_encode(array('draw' => intval($retorno['draw']), 'recordsTotal' =>  intval($retorno['recordsTotal']), 'recordsFiltered' =>  intval($retorno['recordsFiltered']), 'data' => $dados['data']));
            } else {
                echo json_encode(array('status' => false, 'data' => array(), 'recordsTotal' =>  intval($retorno['recordsTotal']), 'recordsFiltered' =>  intval($retorno['recordsFiltered']), 'draw' => intval($retorno['draw'])));
            }
        }
    }

    /*
    * SALVA O TIPO DE FATURAMENTO DO CONTRATO
    */
    public function salvarTipoFaturamento()
    {
        $tipoConsumo = $this->input->post('consumo_fatura');
        $id_contrato = $this->input->post('id_contrato');
        if ($this->contrato->updateTipoFaturamento($id_contrato, $tipoConsumo)) {
            $nomeConsumo = '';
            switch ($tipoConsumo) {
                case '1':
                    $tituloConsumo = lang('faturamento_consumo');
                    break;
                case '2':
                    $tituloConsumo = lang('faturamento_apartir_cadastro');
                    break;
                case '3':
                    $tituloConsumo = lang('faturamento_apartir_instalacao');
                    break;
                default:
                    $tituloConsumo = lang('faturamento_mensal');
                    break;
            }
            echo json_encode(array('success' => true, 'msg' => sprintf(lang('tipo_fatu_atualizado'), $tituloConsumo), 'consumo' => $tipoConsumo, 'tituloConsumo' => $tituloConsumo));
        } else {
            echo json_encode(array('success' => false, 'msg' => lang('tipo_fatu_nao_atualizado')));
        }
    }


    /*
    * CALCULA TOTAL DE ITENS PARA A VIEW CONTRATOS
    */
    public function total_itens_ativos()
    {
        $id_cliente = $this->input->post('id_cliente');
        // calcula total de itens ativos
        $itens_ativos = $this->linha->linhas_ativas(array('id_cliente_sim2m' => $id_cliente, 'id_contrato_sim2m !=' => null, 'id_contrato_sim2m !=' => '', 'status' => 1));                                                           //total de linhas ativas
        $itens_ativos += $this->contrato->total_lista_iscas(array('id_cliente' => $id_cliente, 'status' => 1));                     //total de iscas ativas
        $itens_ativos += $this->veiculo->total_relacao_placas_veiculos_ativos_contrato(array('p.id_cliente' => $id_cliente));       //total de placas/veiculos ativas
        $itens_ativos += $this->cadastro->total_tornozeleiras_ativas(array('v.id_cliente' => $id_cliente, 'v.status' => 'ativo', 'v.equipamento !=' => ''));

        //contratos dos clientes
        $contratos = $this->contrato->listarTable(
            array('id_cliente' => $id_cliente),
            'ctr.id',
            'DESC',
            'ctr.id, ctr.tipo_proposta, ctr.quantidade_veiculos, ctr.valor_mensal, ctr.valor_instalacao, ctr.status, ctr.dataFim_aditivo , vend.nome as nome_vend',
            false
        );

        if ($contratos) {
            $contratos_ativos = 0;
            $total_itens = 0;
            $total = 0;
            $total_instalacao = 0;
            $dados = array();

            foreach ($contratos as $contrato) {
                if (isset($contrato->tipo_proposta) && !empty($contrato)) {
                    $quantidade_veiculos = $contrato->quantidade_veiculos;

                    if ($contrato->status == 0) {   //total de itens para contratos cadastrados
                        $total_itens += $quantidade_veiculos;
                    } elseif (in_array($contrato->status, array(1, 2, 7))) {  //calculos para contratos ativos e em tramite de O.S.
                        $total_itens += $quantidade_veiculos;
                        $contratos_ativos++;
                        $total += $quantidade_veiculos * $contrato->valor_mensal;
                        $total_instalacao += $quantidade_veiculos * $contrato->valor_instalacao;
                    }
                }
            }
            $dados['data'] = array(
                'itens' => $total_itens,
                'itens_ativos' => $itens_ativos,
                'mensalidades' => number_format($total, 2, ',', '.'),
                'instalacao' => number_format($total_instalacao, 2, ',', '.'),
                'contratos_ativos' => $contratos_ativos
            );
            echo json_encode(array('status' => true, 'totais' => $dados['data']));
        } else {
            echo json_encode(array('status' => false, 'msn' => 'Neunhum contrato encontrado!'));
        }
    }

    public function update_vendedor()
    {
        if (isset($_POST)) {
            $update = $this->contrato->update_vendedor($_POST['id_contrato'], $_POST['id_vendedor']);

            if ($update) {
                echo json_encode(array('status' => 'OK', 'mensagem' => '<div class="alert alert-success"><p><b>Vendedor vinculado com sucesso.</b></p></div>'));
            } else {
                echo json_encode(array('status' => 'ERRO', 'mensagem' => '<div class="alert alert-error"><p><b>Não foi possivel vincular o vendedor ao contrato. tente novamente mais tarde.</b></p></div>'));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'mensagem' => '<div class="alert alert-error"><p><b>Nenhum contrato selecionado. tente novamente mais tarde.</b></p></div>'));
        }
    }

    public function update_dataFim_aditivo()
    {
        if (isset($_POST['id_contrato'])) {
            $update = $this->contrato->update_dataFim_aditivo($_POST['id_contrato'], $_POST['dataFim_aditivo']);

            if ($update) {
                echo json_encode(array('status' => 'OK', 'mensagem' => '<div class="alert alert-success"><p><b>Data atualizada com sucesso!</b></p></div>'));
            } else {
                echo json_encode(array('status' => 'ERRO', 'mensagem' => '<div class="alert alert-error"><p><b>Não foi possivel atualizar a data, tente novamente mais tarde.</b></p></div>'));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'mensagem' => '<div class="alert alert-error"><p><b>Nenhum contrato selecionado, tente novamente mais tarde.</b></p></div>'));
        }
    }

    public function serparar_secretaria()
    {
        $post = $this->input->post();
        if (isset($post['id_contrato'])) {
            $update = $this->contrato->separar_secretaria($post['id_contrato'], $post['status']);

            if ($update) {
                echo json_encode(array('status' => 'OK', 'mensagem' => 'Contrato atualizado com sucesso!'));
            } else {
                echo json_encode(array('status' => 'ERRO', 'mensagem' => 'Não foi possivel atualizar o contrato!'));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'mensagem' => 'Nenhum contrato selecionado. tente novamente mais tarde!'));
        }
    }

    public function ajax_get_iscas()
    {
        $id_contrato = $this->input->post('id_contrato');

        if ($id_contrato) {
            $table = array();
            $iscas = $this->contrato->get_iscas("id_contrato = $id_contrato");

            foreach ($iscas as $isca) {
                if ($isca->status == 1) {
                    $status = '<button type="button" onClick="ativar_inativar_isca(' . $isca->id . ',1)" id="ativo_' . $isca->id . '" class="btn btn-small status ativo_' . $isca->id . '  active btn-success" data-status="ativo" data-controller="' . site_url('contratos/ajax_atualiza_status_isca/' . $isca->id . '/1') . '" data-id="' . $isca->id . '">Ativo</button>
                               <button type="button" onClick="ativar_inativar_isca(' . $isca->id . ',0)" id="inativo_' . $isca->id . '" class="btn btn-small status inativo_' . $isca->id . ' " data-status="inativo" data-controller="' . site_url('contratos/ajax_atualiza_status_isca/' . $isca->id . '/0') . '" data-id="' . $isca->id . '">Inativo</button>';
                } else {
                    $status = '<button type="button" onClick="ativar_inativar_isca(' . $isca->id . ',1)" id="ativo_' . $isca->id . '" class="btn btn-small status ativo_' . $isca->id . ' " data-status="ativo" data-controller="' . site_url('contratos/ajax_atualiza_status_isca/' . $isca->id . '/1') . '" data-id="' . $isca->id . '" >Ativo</button>
                               <button type="button" onClick="ativar_inativar_isca(' . $isca->id . ',0)" id="inativo_' . $isca->id . '" class="btn btn-small status inativo_' . $isca->id . '  active btn-danger data-status="inativo" data-controller="' . site_url('contratos/ajax_atualiza_status_isca/' . $isca->id . '/0') . '" data-id="' . $isca->id . '">Inativo</button>';
                }

                $table[] = array(
                    $isca->id,
                    $isca->serial,
                    $isca->marca,
                    $isca->modelo,
                    $isca->data_cadastro,
                    '<div class="btn-group" id="buttons_radio_status_isca' . $isca->id . '" data-toggle="buttons-radio">' . $status . '</div>',
                    $isca->descricao,
                    $this->auth->is_allowed_block('edi_editariscas') ? '<a class="btn btn-primary" id="btnEditarIsca' . $isca->id . '" onClick="editarIsca(' . $isca->id . ',this)"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a>' : ''
                );
            }
            echo json_encode(array('status' => 'OK', 'table' => $table));
        } else {
            echo json_encode(array('status' => 'ERROR'));
        }
    }

    public function ajax_lista_placas()
    {
        $id_cliente = $this->input->get("id_cliente");
        $id_contrato = $this->input->get("id_contrato");
        $table = array();
        $veic = array();

        if ($id_cliente && $id_contrato) {
            $placas = $this->veiculo->listar_placas_contrato($id_cliente, $id_contrato, 'id, placa, status');
            $veiculos = $this->veiculo->veiculosAtivosCliente($id_cliente);
            if ($veiculos) {
                foreach ($veiculos as $key => $veiculo) {
                    $veic[] = $veiculo->placa;
                }
            }

            if ($placas) {
                foreach ($placas as $key => $placa) {
                    $status = $placa->status;
                    if ($status == 'ativo' && !in_array($placa->placa, $veic)) {
                        $status = 'inativo';
                    }

                    if ($placa->placa == "" || $placa->placa == null) {
                        $array = array('status' => $status);
                        $this->contrato->update_trz($array, $placa->id);
                    }

                    $btn_status = $label = '';

                    if ($status == 'cadastrado') {
                        $label = 'primary';
                        //cria botao para ativar o veiculo
                        $btn_status = '<button type="button" href_ativar="' . site_url('contratos/vincular_veiculo_new/' . $placa->id . '/' . $id_contrato . '/' . $id_cliente . '/' . $placa->placa . '') . '"
                                            href_inativar="' . site_url('contratos/ajax_inativar_veiculo/' . $placa->id . '/inativo/' . $placa->placa . '/' . $id_contrato . '/' . $id_cliente . '') . '"
                                            class="btn btn-small btn-primary ativar_placa" data-acao="cadastrar" data-placa_id="' . $placa->id . '">Ativar
                                        </button>
                                        <button type="button" class="btn btn-small btn-default inativar_placa btn_inativo_' . $placa->id . '" data-placa_id="' . $placa->id . '" href="' . site_url('contratos/ajax_inativar_veiculo/' . $placa->id . '/cadastrado/' . $placa->placa . '/' . $id_contrato . '/' . $id_cliente . '') . '">
                                            Inativar
                                         </button>';
                    } elseif ($status == 'ativo') {
                        $label = 'success';
                        //cria botao para ativar/desativar o veiculo
                        $btn_status = '<button type="button" href_ativar="' . site_url('contratos/vincular_veiculo_new/' . $placa->id . '/' . $id_contrato . '/' . $id_cliente . '/' . $placa->placa . '') . '" class="btn btn-primary btn-success ativar_placa btn_ativo_' . $placa->id . '"
                                                href_inativar="' . site_url('veiculos/posicao_veiculo/' . trim($placa->placa) . '/' . $id_cliente . '') . '" data-acao="editar" data-placa_id="' . $placa->id . '">
                                            Editar
                                        </button>
                                         <button type="button" class="btn btn-small btn-default inativar_placa btn_inativo_' . $placa->id . '" data-placa_id="' . $placa->id . '" href="' . site_url('contratos/ajax_inativar_veiculo/' . $placa->id . '/inativo/' . $placa->placa . '/' . $id_contrato . '/' . $id_cliente . '') . '">
                                            Inativar
                                         </button>';
                    } else {
                        $label = 'default';

                        //cria botao para ativar/desativar o veiculo
                        $btn_status = '<button type="button" data-acao="ativar" href_ativar="' . site_url('contratos/vincular_veiculo_new/' . $placa->id . '/' . $id_contrato . '/' . $id_cliente . '/' . $placa->placa . '') . '" class="btn btn-primary btn-success ativar_placa btn_ativo_' . $placa->id . '"
                                                 href_inativar="' . site_url('veiculos/posicao_veiculo/' . trim($placa->placa) . '/' . $id_cliente . '') . '" data-placa_id="' . $placa->id . '" >
                                            Ativar
                                        </button>
                                         <button type="button" class="btn btn-small btn-default inativar_placa btn_inativo_' . $placa->id . '" data-placa_id="' . $placa->id . '" href="' . site_url('contratos/ajax_inativar_veiculo/' . $placa->id . '/inativo/' . $placa->placa . '/' . $id_contrato . '/' . $id_cliente . '') . '" disabled >
                                            Inativar
                                        </button>';
                    }
                    //cria o label do status
                    $statusLabel = '<span class="label label-' . $label . ' status_' . $placa->id . '">' . $status . '</span>';

                    //cria botao de abrir a posicao do veiculo
                    $posic = '<button href="' . site_url('veiculos/posicao_veiculo/' . trim($placa->placa) . '/' . $id_cliente . '') . '" data-placa="' . $placa->placa . '" data-status="' . $status . '"
                                   class="btn btn-mini btn-default posicao btnPosicao_' . $placa->id . '" title="Posição Veículo">
                                       <i class="fa fa-plus"></i> Posição
                            </button>';

                    $table[] = array(
                        $placa->id,
                        $placa->placa,
                        $posic,
                        $statusLabel,
                        '<div class="btn-group btn_' . $placa->id . '" data-toggle="buttons-radio">' . $btn_status . '</div>'
                    );
                }
            }
            echo json_encode(array('status' => 'OK', 'table' => $table));
        } else {
            echo json_encode(array('status' => 'ERROR'));
        }
    }

    public function vincular_secretaria()
    {
        if ($this->input->post("id_veic_contrato") && $this->input->post("grupo")) {
            echo $this->contrato->vincular_secretaria($this->input->post("id_veic_contrato"), $this->input->post("grupo"));
        }
    }


    public function simCard($id_cliente, $id_contrato, $type = '', $pagina = false)
    {

        if ($id_cliente && $id_contrato) {
            $table = array();
            $chips = $this->contrato->listar_chips("id_contrato_sim2m = $id_contrato");

            foreach ($chips as $key => $chip) {
                if ($chip->status == 1) {
                    $status = '<button type="button" class="btn btn-small status ativo_' . $chip->id . ' active btn-success" data-status="ativo" data-controller="' . site_url('contratos/ajax_atualiza_status_chip/' . $chip->id . '/ativo/' . $id_cliente . '') . '" data-id="' . $chip->id . '">Ativo</button>
                               <button type="button" class="btn btn-small status inativo_' . $chip->id . '" data-status="inativo" data-controller="' . site_url('contratos/ajax_atualiza_status_chip/' . $chip->id . '/inativo/' . $id_cliente . '') . '" data-id="' . $chip->id . '">Inativo</button>';
                } else {
                    $status = '<button type="button" class="btn btn-small status ativo_' . $chip->id . '" data-status="ativo" data-controller="' . site_url('contratos/ajax_atualiza_status_chip/' . $chip->id . '/ativo/' . $id_cliente . '') . '" data-id="' . $chip->id . '">Ativo</button>
                               <button type="button" class="btn btn-small status inativo_' . $chip->id . ' active btn-danger data-status="inativo" data-controller="' . site_url('contratos/ajax_atualiza_status_chip/' . $chip->id . '/inativo/' . $id_cliente . '') . '" data-id="' . $chip->id . '">Inativo</button>';
                }

                $table[] = array(
                    $chip->id,
                    $chip->numero,
                    $chip->ccid,
                    '<div class="btn-group" data-toggle="buttons-radio">' . $status . '</div>'
                );
            }
            echo json_encode(array('status' => 'OK', 'table' => $table));
        } else {
            echo json_encode(array('status' => 'ERROR'));
        }
    }

    public function ajax_list_tornozeleiras()
    {
        $id_contrato =  $this->input->post('id_contrato');
        $id_cliente =  $this->input->post('id_cliente');

        if ($id_cliente && $id_contrato) {
            $table = array();
            $tornozeleiras =  $this->contrato->listar_placas(array('id_cliente' => $id_cliente, 'id_contrato' => $id_contrato));

            foreach ($tornozeleiras as $tornozeleira) {
                if ($tornozeleira->status == 'ativo') {
                    $status = '<button type="button" class="btn btn-small status ativo_' . $tornozeleira->id . ' active btn-success" data-status="ativo" data-id="' . $tornozeleira->id . '" data-controller="' . site_url('contratos/ajax_atualiza_status_trz/' . $tornozeleira->equipamento . '/' . $tornozeleira->id . '/' . $tornozeleira->id_cliente . '/ativo') . '">Ativo</button>
                               <button type="button" class="btn btn-small status inativo_' . $tornozeleira->id . '" data-status="inativo" data-id="' . $tornozeleira->id . '" data-controller="' . site_url('contratos/ajax_atualiza_status_trz/' . $tornozeleira->equipamento . '/' . $tornozeleira->id . '/' . $tornozeleira->id_cliente . '/inativo') . '">Inativo</button>';
                } else {
                    $status = '<button type="button" class="btn btn-small status ativo_' . $tornozeleira->id . '" data-status="ativo" data-id="' . $tornozeleira->id . '" data-controller="' . site_url('contratos/ajax_atualiza_status_trz/' . $tornozeleira->equipamento . '/' . $tornozeleira->id . '/' . $tornozeleira->id_cliente . '/ativo') . '">Ativo</button>
                               <button type="button" class="btn btn-small status inativo_' . $tornozeleira->id . ' active btn-danger data-status="inativo" data-id="' . $tornozeleira->id . '" data-controller="' . site_url('contratos/ajax_atualiza_status_trz/' . $tornozeleira->equipamento . '/' . $tornozeleira->id . '/' . $tornozeleira->id_cliente . '/inativo') . '">Inativo</button>';
                }

                $table[] = array(
                    $tornozeleira->id,
                    $tornozeleira->equipamento,
                    date('d/m/Y H:i:s', strtotime($tornozeleira->data_cadastro)),
                    '<div class="btn-group" data-toggle="buttons-radio">' . $status . '</div>'
                );
            }
            echo json_encode(array('status' => 'OK', 'table' => $table));
        } else {
            echo json_encode(array('status' => 'ERROR'));
        }
    }

    public function ajax_list_suprimentos($id_cliente, $id_contrato)
    {
        if ($id_cliente && $id_contrato) {
            $table = array();
            $suprimentos =  $this->contrato->listar_suprimentos(array('id_cliente' => $id_cliente, 'id_contrato' => $id_contrato));

            foreach ($suprimentos as $suprimento) {
                if ($suprimento->status_con_sup == 'ativo') {
                    $status = '<a class="btn btn-small btn-danger btn_status" data-id_sup="' . $suprimento->id_suprimento . '" data-id="' . $suprimento->id_con_sup . '" data-status="inativo" data-cliente="' . $suprimento->id_cliente . '">Inativar</a>';
                } else {
                    $status = '<a class="btn btn-small btn-success btn_status" data-id_sup="' . $suprimento->id_suprimento . '" data-id="' . $suprimento->id_con_sup . '" data-status="ativo" data-cliente="' . $suprimento->id_cliente . '">Ativar</a>';
                }

                $tipo = '';
                switch ($suprimento->tipo) {
                    case 1:
                        $tipo = 'Cinta';
                        break;
                    case 2:
                        $tipo = 'Powerbank';
                        break;
                    case 3:
                        $tipo = 'Carregador';
                        break;
                }

                $table[] = array(
                    $suprimento->id_con_sup,
                    $suprimento->serial,
                    $suprimento->descricao,
                    $tipo,
                    date('d/m/Y H:i:s', strtotime($suprimento->data_cadastro_con_sup)),
                    '<div class="btn-group" data-toggle="buttons-radio">' . $status . '</div>'
                );
            }
            echo json_encode(array('status' => 'OK', 'table' => $table));
        } else {
            echo json_encode(array('status' => 'ERROR'));
        }
    }

    public function vincular_veiculo($placa, $id_cliente, $id_contrato)
    {
        $dados['seriais'] = $this->contrato->verificar_placa_serial($placa);
        $dados['equipamentos'] = $this->equipamento->get_equipamentos($serial = false);
        $dados['usuarios'] = $this->usuario_gestor->find_by_placa($id_cliente);
        $dados['placa'] = $placa;
        $dados['id_cliente'] = $id_cliente;
        $dados['id_contrato'] = $id_contrato;
        $dados['dados_veic'] = $this->veiculo->getVeiculo_byPlaca($placa);
        $dados['grupos'] = $this->cadastro->get_grupo($id_cliente);
        $this->load->view('contratos/vincular_veiculo', $dados);
    }

    public function busca_equipamento_select2()
    {
        $like = NULL;
        if ($search = $this->input->get('q'))
            $like = array('serial' => $search);

        echo json_encode(array('results' => $this->equipamento->listar_eqp(array(), 0, 10, 'serial', 'asc', 'serial as text, serial as id', $like)));
    }

    public function validar_vinculo($placa, $id_usuario, $id_contrato)
    {

        $serial = $this->input->post('serial');
        $status = $this->input->post('status');
        $veiculo = $this->input->post('veiculo');
        $prefixo = $this->input->post('prefixo');
        $taxi = $this->input->post('taxi');
        $marca = $this->input->post('marca');
        $modelo = $this->input->post('modelo');
        $usuario_email = $this->auth->get_login('admin', 'email');
        $data_instalacao = $this->input->post('data_instalacao');
        $ser = $this->input->post('ser');

        if (!$ser)
            $ser = 0;

        $veiculos = false;
        $placas = false;

        if ($taxi == 1)
            $imagem = "taxi_eptc_10.png";
        else
            $imagem = "caminhao3.png";

        if ($serial == "") {
            $mensagem = '<div class="alert alert-info">Serial não informado, favor informar serial!</div>';
        } else {
            if ($this->equipamento->get_equipamentos($serial)) {
                //pr($status); die;
                if ($status == 'correcao') {
                    if ($this->veiculo->atualizar_veiculo($placa, $serial, false, "1", false, $id_usuario)) {
                        $acao = array(
                            'data_criacao' => date('Y-m-d H:i:s'),
                            'usuario' => $usuario_email,
                            'placa' => $placa,
                            'acao' => 'O usuário ' . $usuario_email . ' atualizou o serial para ' . $serial . ' do veículo ' . $placa
                        );
                        $ret = $this->log_veiculo->add($acao);

                        /*
                        if (substr($serial, 0, 3) == "205" || substr($serial, 0, 2) == "90") {
                            $this->equipamento->saveCmdSuntech($serial, "");
                        } elseif (substr($serial, 0, 2) == "QL") {
                            $this->equipamento->saveCmdQuicklink($serial, "");
                        } else {
                            $this->equipamento->saveCmdMaxtrack($serial, "");
                        } */

                        $this->veiculo->update_inst($placa);
                        $mensagem = '<div class="alert alert-success">Placa ' . $placa . ' atualizada para serial ' . $serial . '!</div>';

                        # ATUALIZA STATUS DO EQUIPAMENTO PARA INSTALADO
                        $id_equipamento = $this->equipamento->getIdSerial($serial);
                        if ($id_equipamento) {
                            $this->equipamento->atualizaStatus_equip($id_equipamento, 4);
                        }
                    } else {
                        $mensagem = '<div class="alert alert-info">Placa ' . $placa . ' não atualizada para serial ' . $serial . '!</div>';
                    }
                } else {
                    $usuario_code = $this->input->post('usuario');
                    $usu = $this->usuario_gestor->get(array('code' => $usuario_code));
                    //pr($ser); pr($serial); die;
                    if ($serial === $ser) {
                        if ($this->equipamento->verificar_equipamento($serial, $placa)) {
                            $placas =  $this->equipamento->get_equipamentos_desvincular($serial);

                            $mensagem = '<div class="alert alert-info"><b>Ops!</b> Serial <b>' . $serial . '</b> está vinculado a outra(s) placa(s).
                            Click em <b>Desvincular</b> para ir para página de correção! <br><center><a href="' . site_url("veiculos/desvincular_placas/" . implode(':', $placas) . "/" . $serial) . '" target="_blank">Click aqui para desvincular</center></a></div>';
                        } else {
                            if ($this->contrato->validar_vinculo($serial, $usu->CNPJ_, $placa)) {
                                $mensagem = '<div class="alert alert-info">Serial ' . $serial . ' ja cadastrado para usuário <b>' . $usu->usuario . '</b></div>';
                            } else {
                                $dados = array(
                                    'id_usuario' => $id_usuario, 'placa' => $placa, 'serial' => $serial, 'CNPJ_' => $usu->CNPJ_, 'veiculo' => $veiculo, 'prefixo_veiculo' => $prefixo, 'taxi' => $taxi, 'imagem' => $imagem, 'contrato_veiculo' => $id_contrato, 'data_instalacao' => $data_instalacao, 'marca' => $marca, 'modelo' => $modelo
                                );

                                if ($this->veiculo->cadastrar_veiculo($dados)) {
                                    $acao = array(
                                        'data_criacao' => date('Y-m-d H:i:s'),
                                        'usuario' => $usuario_email,
                                        'placa' => $placa,
                                        'acao' => 'Placa ' . $placa . ' e serial ' . $serial . ' cadastrado no usuário ' . $usu->usuario
                                    );
                                    $ret = $this->log_veiculo->add($acao);
                                    /*
                                    if (substr($serial, 0, 3) == "205" || substr($serial, 0, 2) == "90") {
                                        $this->equipamento->saveCmdSuntech($serial, "");
                                    } elseif (substr($serial, 0, 2) == "QL") {
                                        $this->equipamento->saveCmdQuicklink($serial, "");
                                    } else {
                                        //$this->equipamento->saveCmdMaxtrack($serial, "");
                                    }
                                    */

                                    $this->veiculo->update_inst($placa);
                                    $mensagem = '<div class="alert alert-success">Placa ' . $placa . ' e serial ' . $serial . ' cadastrado no usuário <b>' . $usu->usuario . '</b></div>';

                                    # ATUALIZA STATUS DO EQUIPAMENTO PARA INSTALADO
                                    $id_equipamento = $this->equipamento->getIdSerial($serial);
                                    if ($id_equipamento) {
                                        $this->equipamento->atualizaStatus_equip($id_equipamento, 4);
                                    }
                                } else {
                                    $mensagem = '<div class="alert alert-info">Placa ' . $placa . ' e serial ' . $serial . ' não cadastrado para o usuário <b>' . $usu->usuario . '</b></div>';
                                }
                            }
                        }
                    } else {
                        if ($this->equipamento->verificar_equipamento($serial, $placa)) {

                            $placas =  $this->equipamento->get_equipamentos_desvincular($serial);

                            $mensagem = '<div class="alert alert-info"><b>Ops!</b> Serial <b>' . $serial . '</b> está vinculado a outra(s) placa(s).
                            Click em <b>Desvincular</b> para ir para página de correção! <br><center><a href="' . site_url("veiculos/desvincular_placas/" . implode(':', $placas) . "/" . $serial) . '" target="_blank">Click aqui para desvincular</center></a></div>';
                        } else {
                            if ($this->contrato->validar_vinculo($serial, $usu->CNPJ_, $placa)) {
                                $mensagem = '<div class="alert alert-info">Serial ' . $serial . ' ja cadastrado para usuário <b>' . $usu->usuario . '</b></div>';
                            } else {
                                if ($status == 'novo') {

                                    $dados = array(
                                        'id_usuario' => $id_usuario, 'placa' => $placa, 'serial' => $serial, 'CNPJ_' => $usu->CNPJ_, 'veiculo' => $veiculo, 'prefixo_veiculo' => $prefixo, 'taxi' => $taxi, 'imagem' => $imagem, 'contrato_veiculo' => $id_contrato, 'data_instalacao' => $data_instalacao, 'marca' => $marca, 'modelo' => $modelo
                                    );

                                    if ($this->veiculo->cadastrar_veiculo($dados)) {
                                        $acao = array(
                                            'data_criacao' => date('Y-m-d H:i:s'),
                                            'usuario' => $usuario_email,
                                            'placa' => $placa,
                                            'acao' => 'Placa ' . $placa . ' e serial ' . $serial . ' cadastrado no usuário ' . $usu->usuario
                                        );
                                        $ret = $this->log_veiculo->add($acao);

                                        /*
                                        if (substr($serial, 0, 3) == "205" || substr($serial, 0, 2) == "90") {
                                            $this->equipamento->saveCmdSuntech($serial, "");
                                        } elseif (substr($serial, 0, 2) == "QL") {
                                            $this->equipamento->saveCmdQuicklink($serial, "");
                                        } else {
                                            //$this->equipamento->saveCmdMaxtrack($serial, "");
                                        }
                                        */

                                        $this->veiculo->update_inst($placa);

                                        $mensagem = '<div class="alert alert-success">Placa ' . $placa . ' e serial ' . $serial . ' cadastrado no usuário <b>' . $usu->usuario . '</b></div>';

                                        # ATUALIZA STATUS DO EQUIPAMENTO PARA INSTALADO
                                        $id_equipamento = $this->equipamento->getIdSerial($serial);
                                        if ($id_equipamento) {
                                            $this->equipamento->atualizaStatus_equip($id_equipamento, 4);
                                        }

                                        # ATIVA CONTRATO
                                        $status = $this->contrato->ativar_contrato($id_contrato);
                                    } else {
                                        $mensagem = '<div class="alert alert-info">Placa ' . $placa . ' e serial ' . $serial . '  não cadastrado para o usuário <b>' . $usu->usuario . '</b></div>';
                                    }
                                } elseif ($status == 'edicao') {

                                    if ($this->veiculo->atualizar_veiculo($placa, $serial, false, "1", false, $id_usuario)) {
                                        $acao = array(
                                            'data_criacao' => date('Y-m-d H:i:s'),
                                            'usuario' => $usuario_email,
                                            'placa' => $placa,
                                            'acao' => 'O usuário ' . $usuario_email . ' atualizou o veículo de placa ' . $placa
                                        );
                                        $ret = $this->log_veiculo->add($acao);
                                        /*
                                        if (substr($serial, 0, 3) == "205" || substr($serial, 0, 2) == "90") {
                                            $this->equipamento->saveCmdSuntech($serial, "");
                                        } elseif (substr($serial, 0, 2) == "QL") {
                                            $this->equipamento->saveCmdQuicklink($serial, "");
                                        } else {
                                            //$this->equipamento->saveCmdMaxtrack($serial, "");
                                        }
                                        */
                                        $this->veiculo->update_inst($placa);
                                        $mensagem = '<div class="alert alert-success">Placa ' . $placa . ' atualizado para serial ' . $serial . '!</div>';

                                        # ATUALIZA STATUS DO EQUIPAMENTO PARA INSTALADO
                                        $id_equipamento = $this->equipamento->getIdSerial($serial);
                                        if ($id_equipamento) {
                                            $this->equipamento->atualizaStatus_equip($id_equipamento, 4);
                                        }

                                        # ATIVA CONTRATO
                                        $this->contrato->ativar_contrato($id_contrato);
                                    } else {
                                        $mensagem = '<div class="alert alert-info">Placa ' . $placa . ' não atualizada para serial ' . $serial . '!</div>';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $mensagem = '<div class="alert alert-info">Serial ' . $serial . ' inválido ou não cadastrado!</div>';
            }
        }
        echo json_encode(array('success' => true, 'mensagem' => $mensagem, 'serial' => $serial, 'placas' => $placas));
    }


    public function vincular_veiculo_new($id_placa, $id_contrato, $id_cliente, $placa = null)
    {
        $dados['seriais'] = $placa ? $this->contrato->verificar_placa_serial($placa) : null;
        $dados['usuarios'] = $this->usuario_gestor->find_by_placa($id_cliente);
        $dados['placa'] = $placa;
        $dados['id_placa'] = $id_placa;
        $dados['id_cliente'] = $id_cliente;
        $dados['id_contrato'] = $id_contrato;
        $dados['dados_veic'] = $placa ? $this->veiculo->getVeiculo_byPlacaCliente($placa, $id_cliente) : null;
        // die(pr($dados['dados_veic']));
        $dados['grupos'] = $this->cadastro->get_grupo($id_cliente);
        $this->load->view('contratos/vincular_veiculo_new', $dados);
    }

    public function validar_vinculo_new($id_placa, $placa, $id_usuario, $id_contrato)
    {

        $serial = $this->input->post('serial');
        $status = $this->input->post('status');
        $veiculo = $this->input->post('veiculo');
        $prefixo = $this->input->post('prefixo');
        $taxi = $this->input->post('taxi');
        $marca = $this->input->post('marca');
        $modelo = $this->input->post('modelo');
        $usuario_email = $this->auth->get_login('admin', 'email');
        $data_instalacao = $this->input->post('data_instalacao');
        $ser = $this->input->post('ser');

        $contrato_info = $this->contrato->get_contrato_status($id_contrato);
        $contrato_status = $contrato_info[0]->status;

        if (!$ser)
            $ser = 0;

        $veiculos = $placas = false;

        if ($taxi == 1)
            $imagem = "taxi_eptc_10.png";
        else
            $imagem = "caminhao3.png";

        $mensagem = '';
        if ($serial == "") {
            $mensagem = '<div class="alert alert-danger">Serial não informado, favor informar serial!</div>';
            echo json_encode(array('success' => false, 'mensagem' => $mensagem));
            die;
        } else {
            if ($this->equipamento->get_equipamentos($serial)) {
                if ($status == 'correcao') {
                    $dados = array(
                        'code_cliente' => $id_usuario,
                        'id_usuario' => $id_usuario,
                        'placa' => $placa,
                        'serial' => $serial,
                        'veiculo' => $veiculo,
                        'prefixo_veiculo' => $prefixo,
                        'taxi' => $taxi,
                        'imagem' => $imagem,
                        'marca' => $marca,
                        'modelo' => $modelo
                    );
                    if ($this->veiculo->atualizar_veiculo($placa, $serial, false, "1", $dados, $id_usuario)) {
                        $acao = array(
                            'data_criacao' => date('Y-m-d H:i:s'),
                            'usuario' => $usuario_email,
                            'placa' => $placa,
                            'acao' => 'O usuário ' . $usuario_email . ' atualizou o serial para ' . $serial . ' do veículo ' . $placa
                        );
                        $ret = $this->log_veiculo->add($acao);
                        $this->veiculo->update_inst($placa);

                        # ATUALIZA STATUS DO EQUIPAMENTO PARA INSTALADO
                        $id_equipamento = $this->equipamento->getIdSerial($serial);
                        if ($id_equipamento) {
                            $this->equipamento->atualizaStatus_equip($id_equipamento, 4);
                        }

                        $mensagem = '<div class="alert alert-success">Placa ' . $placa . ' atualizada para serial ' . $serial . '!</div>';
                        echo json_encode(array('success' => true, 'mensagem' => $mensagem, 'operacao' => $status));
                        die;
                    } else {
                        $mensagem = '<div class="alert alert-danger">Placa ' . $placa . ' não atualizada para serial ' . $serial . '!</div>';
                        echo json_encode(array('success' => false, 'mensagem' => $mensagem));
                        die;
                    }
                } else {
                    $usuario_code = $this->input->post('usuario');
                    $usu = $this->usuario_gestor->get(array('code' => $usuario_code));

                    //SE O SERIAL PASSADO PELO USUARIO FOR O MESMO SERIAL DO VEICULO
                    if ($serial === $ser) {
                        //ATUALIZA OS DADOS DO VEICULO
                        $dados = array(
                            'code_cliente' => $id_usuario,
                            'id_usuario' => $id_usuario,
                            'placa' => $placa,
                            'serial' => $serial,
                            'veiculo' => $veiculo,
                            'prefixo_veiculo' => $prefixo,
                            'taxi' => $taxi,
                            'imagem' => $imagem,
                            'marca' => $marca,
                            'modelo' => $modelo
                        );
                        if ($this->veiculo->atualizar_veiculo($placa, $serial, false, 1, $dados, $id_usuario)) {
                            //CHAMA A FUNCAO DE ATUALIZACAO DO LOG DE ACOES DOS USUARIOS PARA VEICULOS
                            // $this->veiculo->update_veiculo($placa, $serial, $dados)

                            //ATUALIZA A TABELA 'CONTRATO_VEICULO'
                            $dados = array(
                                'prefixo_veiculo' => $prefixo,
                                'taxi' => $taxi,
                                'marca' => $marca,
                                'modelo' => $modelo
                            );
                            $this->veiculo->update_placa($placa, $id_usuario, $dados);

                            //SALVA A ACAO NO LOG DO VEICULO
                            $acao = array(
                                'data_criacao' => date('Y-m-d H:i:s'),
                                'usuario' => $usuario_email,
                                'placa' => $placa,
                                'acao' => 'O usuário ' . $usuario_email . ' atualizou os dados do veículo ' . $placa
                            );
                            $this->log_veiculo->add($acao);
                            $mensagem = '<div class="alert alert-success">Veículo ' . $placa . ' atualizado com sucesso!</div>';

                            echo json_encode(array('success' => true, 'mensagem' => $mensagem, 'operacao' => $status));
                            die;
                        } else {
                            $mensagem = '<div class="alert alert-danger">Veículo ' . $placa . ' não atualizado!</div>';
                            echo json_encode(array('success' => false, 'mensagem' => $mensagem));
                            die;
                        }
                    } else {
                        if ($this->equipamento->verificar_equipamento($serial, $placa)) {

                            $placas =  $this->equipamento->get_equipamentos_desvincular($serial);

                            $mensagem = '<div class="alert alert-danger"><b>Ops!</b> Serial <b>' . $serial . '</b> está vinculado a outra(s) placa(s).
                            Click em <b>Desvincular</b> para ir para página de correção! <br><center><a href="' . site_url("veiculos/desvincular_placas/" . implode(':', $placas) . "/" . $serial) . '" target="_blank">Click aqui para desvincular</center></a></div>';
                            echo json_encode(array('success' => false, 'mensagem' => $mensagem));
                            die;
                        } else {
                            if ($this->contrato->validar_vinculo($serial, $usu->CNPJ_, $placa)) {
                                $mensagem = '<div class="alert alert-danger">Serial ' . $serial . ' ja cadastrado para usuário <b>' . $usu->usuario . '</b></div>';
                                echo json_encode(array('success' => false, 'mensagem' => $mensagem));
                                die;
                            } else {
                                if ($status == 'novo') {

                                    $dados = array(
                                        'code_cliente' => $id_usuario,
                                        'id_usuario' => $id_usuario,
                                        'placa' => $placa,
                                        'serial' => $serial,
                                        'CNPJ_' => $usu->CNPJ_,
                                        'veiculo' => $veiculo,
                                        'prefixo_veiculo' => $prefixo,
                                        'taxi' => $taxi,
                                        'imagem' => $imagem,
                                        'contrato_veiculo' => $id_contrato,
                                        'data_instalacao' => $data_instalacao,
                                        'marca' => $marca,
                                        'modelo' => $modelo
                                    );

                                    if ($this->veiculo->cadastrar_veiculo($dados)) {
                                        //ativa a placa na tabela contratos_veiculos
                                        $usuario_email = $this->auth->get_login('admin', 'email');
                                        $this->contrato->atualiza_status_placa($id_placa, 'ativo', $usuario_email);

                                        //cria log para veiculos
                                        $acao = array(
                                            'data_criacao' => date('Y-m-d H:i:s'),
                                            'usuario' => $usuario_email,
                                            'placa' => $placa,
                                            'acao' => 'Placa ' . $placa . ' e serial ' . $serial . ' cadastrado no usuário ' . $usu->usuario
                                        );
                                        $ret = $this->log_veiculo->add($acao);
                                        $this->veiculo->update_inst($placa);

                                        # ATUALIZA STATUS DO EQUIPAMENTO PARA INSTALADO
                                        $id_equipamento = $this->equipamento->getIdSerial($serial);
                                        if ($id_equipamento) {
                                            $this->equipamento->atualizaStatus_equip($id_equipamento, 4);
                                        }

                                        # ATIVA CONTRATO
                                        $this->contrato->ativar_contrato($id_contrato);

                                        $id_user = $this->auth->get_login_dados('user');
                                        $id_user = (int) $id_user;
                                        $this->log_shownet->gravar_log($id_user, 'contratos_veiculos', 0, 'criar', '', $dados);
                                        $this->log_shownet->gravar_log($id_user, 'contratos', $id_contrato, 'atualizar', 'status: ' . $contrato_status, 'status: 2');

                                        $mensagem = '<div class="alert alert-success">Placa ' . $placa . ' e serial ' . $serial . ' cadastrado no usuário <b>' . $usu->usuario . '</b></div>';
                                        echo json_encode(array('success' => true, 'mensagem' => $mensagem, 'operacao' => $status));
                                        die;
                                    } else {
                                        $mensagem = '<div class="alert alert-danger">Placa ' . $placa . ' e serial ' . $serial . '  não cadastrado para o usuário <b>' . $usu->usuario . '</b></div>';
                                        echo json_encode(array('success' => false, 'mensagem' => $mensagem));
                                        die;
                                    }
                                } elseif ($status == 'edicao') {
                                    //ATUALIZA OS DADOS DO VEICULO
                                    $dados = array(
                                        'code_cliente' => $id_usuario,
                                        'id_usuario' => $id_usuario,
                                        'placa' => $placa,
                                        'serial' => $serial,
                                        'veiculo' => $veiculo,
                                        'prefixo_veiculo' => $prefixo,
                                        'taxi' => $taxi,
                                        'imagem' => $imagem,
                                        'marca' => $marca,
                                        'modelo' => $modelo
                                    );
                                    if ($this->veiculo->atualizar_veiculo($placa, $serial, false, "1", $dados, $id_usuario)) {
                                        //ativa a placa na tabela contratos_veiculos
                                        $usuario_email = $this->auth->get_login('admin', 'email');
                                        $this->contrato->atualiza_status_placa($id_placa, 'ativo', $usuario_email);

                                        $acao = array(
                                            'data_criacao' => date('Y-m-d H:i:s'),
                                            'usuario' => $usuario_email,
                                            'placa' => $placa,
                                            'acao' => 'O usuário ' . $usuario_email . ' atualizou o veículo de placa ' . $placa
                                        );
                                        $ret = $this->log_veiculo->add($acao);
                                        $this->veiculo->update_inst($placa);

                                        # ATUALIZA STATUS DO EQUIPAMENTO PARA INSTALADO
                                        $id_equipamento = $this->equipamento->getIdSerial($serial);
                                        if ($id_equipamento) {
                                            $this->equipamento->atualizaStatus_equip($id_equipamento, 4);
                                        }

                                        # ATIVA CONTRATO
                                        $this->contrato->ativar_contrato($id_contrato);

                                        $id_user = $this->auth->get_login_dados('user');
                                        $id_user = (int) $id_user;
                                        $this->log_shownet->gravar_log($id_user, 'contratos', $id_contrato, 'atualizar', 'status: ' . $contrato_status, 'status: 2');

                                        $mensagem = '<div class="alert alert-success">Placa ' . $placa . ' atualizado para serial ' . $serial . '!</div>';
                                        echo json_encode(array('success' => true, 'mensagem' => $mensagem, 'operacao' => $status));
                                        die;
                                    } else {
                                        $mensagem = '<div class="alert alert-danger">Placa ' . $placa . ' não atualizada para serial ' . $serial . '!</div>';
                                        echo json_encode(array('success' => false, 'mensagem' => $mensagem));
                                        die;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $mensagem = '<div class="alert alert-danger">Serial ' . $serial . ' inválido ou não cadastrado!</div>';
                echo json_encode(array('success' => false, 'mensagem' => $mensagem));
                die;
            }
        }
    }

    public function digi_contrato($idcontrato)
    {
        //        $para_view['arquivos'] = $this->contrato->get_arqui_contratos($idcontrato);
        //        $para_view['id_cliente'] = $this->contrato->get_cliente_contratos($idcontrato);
        $para_view['id_contrato'] = $idcontrato;
        $this->load->view('clientes/digi_contrato', $para_view);
    }

    //alterar o metodo de cima depois de concluir
    public function ajax_digi_contrato()
    {
        $idcontrato = $this->input->post('id');

        $dados['arquivos'] = $this->contrato->get_arqui_contratos($idcontrato);
        $dados['id_cliente'] = $this->contrato->get_cliente_contratos($idcontrato);
        $dados['id_contrato'] = $idcontrato;

        echo json_encode(array('data' => $dados['arquivos']));
    }

    public function verificar_contrato($status_contrato, $id_contrato, $id_cliente, $tipo_proposta)
    {

        $this->auth->is_allowed('faturas');
        $this->contrato->close_contract($id_contrato);
        $para_view['cancelado'] = $this->contrato->verificar_contrato_cancelado($id_contrato);
        $para_view['equipamentos'] = $this->contrato->verificar_equipamentos_em_uso($id_contrato, $tipo_proposta);
        $para_view['id_contrato'] = $id_contrato;
        $para_view['id_cliente'] = $id_cliente;
        $para_view['tipo_proposta'] = $tipo_proposta;
        $para_view['status_contrato'] = $status_contrato;

        $this->load->view('contratos/cancelar_contrato', $para_view);
    }

    public function cancelar_contrato()
    {
        $id_contrato = $this->input->post('id_contrato');
        $tipoCancelamento = $this->input->post('cancelar');
        $status_contrato_antigo = $this->input->post('status');
        if ($this->contrato->cancelar_contrato($id_contrato, $tipoCancelamento)) {
            $id_user = $this->auth->get_login_dados('user');
            $id_user = (int) $id_user;
            $this->log_shownet->gravar_log($id_user, 'contratos', $id_contrato, 'atualizar', 'status:' . $status_contrato_antigo, 'status:' . $tipoCancelamento);

            die(json_encode(array('mensagem' => '<div class="alert alert-success"><p>Contrato cancelado com sucesso!</p></div>')));
        } else {
            die(json_encode(array('mensagem' => '<div class="alert alert-error"><p>Contrato não cancelado!</p></div>')));
        }
    }

    public function ativar_contrato()
    {
        $id_contrato = $this->input->post('id_contrato');
        if ($this->contrato->ativar_contrato($id_contrato)) {
            echo ('<div class="alert alert-success"><p>Contrato ativado com sucesso!</p></div>');
        } else {
            echo ('<div class="alert alert-error"><p>Contrato não ativado!</p></div>');
        }
    }

    //ADICIONA UM CHIP A UM CONTRATO
    public function ajax_add_chip($id_contrato, $id_cliente)
    {
        $dados = $this->input->post();
        if ($dados) {
            $valida_campos = array(
                array('field' => 'ccid', 'label' => 'CCID', 'rules' => 'required')
            );
            $this->form_validation->set_rules($valida_campos);
            if (!$this->form_validation->run()) {
                echo json_encode(array('success' => false, 'msg' => validation_errors()));
            } else {
                try {
                    $array = array(
                        'coluna' => 'ccid',
                        'palavra' => $dados['ccid'],
                    );
                    $linha = $this->linha->pesquisa_linha($array);
                    if (isset($linha[0])) {
                        $ax = $this->input->post('status') == 'ativo' ? 1 : 0;
                        $array = array(
                            'status' => $ax,
                            'id_contrato_sim2m' => $id_contrato,
                            'id_cliente_sim2m' => $id_cliente,
                        );
                        $this->linha->update_linha($array, $linha[0]->id);
                        $this->contrato->ativar_contrato($id_contrato);
                        echo json_encode(array('success' => true, 'msg' => 'Adicionado com sucesso.', 'placas' => $linha));
                    } else {
                        echo json_encode(array('success' => false, 'msg' => 'Não foi encontrado informações sobre esta linha no sistema!'));
                    }
                } catch (Exception $e) {
                    echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
                }
            }
        }
    }

    //ADICIONA UMA PLACA A UM CONTRATO
    public function ajax_add_placa($id_contrato)
    {
        if ($dadosForm = $this->input->post()) {
            $dadosForm += array('data_cadastro' => date('Y-m-d H:i:s'));
            $placa = $dadosForm['placa'];
            $valida_campos = array(
                array('field' => 'placa', 'label' => 'Placa', 'rules' => 'required'),
                array('field' => 'status', 'label' => 'Status', 'rules' => 'required')
            );
            $this->form_validation->set_rules($valida_campos);
            if (!$this->form_validation->run()) {
                echo json_encode(array('success' => false, 'msg' => validation_errors()));
            } else {
                try {
                    $this->contrato->inserir_placa($dadosForm);
                    $id_veiculo = $this->db->insert_id();

                    // VINCULA VEICULO AO GRUPO MASTER
                    $this->veiculo->vincularVeic_GroupMaster($placa, $dadosForm['id_cliente']);

                    $usuario_email = $this->auth->get_login('admin', 'email');
                    $acao = array(
                        'data_criacao' => date('Y-m-d H:i:s'),
                        'usuario' => $usuario_email,
                        'placa' => $placa,
                        'acao' => 'A placa ' . $placa . ' foi adicionada ao contrato ' . $id_contrato . ' pelo usuário ' . $usuario_email
                    );
                    $ret = $this->log_veiculo->add($acao);
                    echo json_encode(array('success' => true, 'msg' => 'Veículo adicionado com sucesso'));
                } catch (Exception $e) {
                    echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
                }
            }
        }
    }

    //ADICIONA UMA TORNOZELEIRA A UM CONTRATO
    public function ajax_add_tornozeleira()
    {
        if ($this->input->post()) {
            if (!$this->input->post('equipamento')) {
                echo json_encode(array('success' => false, 'msg' => validation_errors()));
            } else {
                try {
                    $post = $this->input->post();
                    $post['data_cadastro'] = date('Y-m-d H:i:s');
                    $id_eqp = $this->contrato->inserir_tornozeleira($post);
                    $veic = $this->contrato->get_veiculo(array('id' => $id_eqp));

                    // VINCULA VEICULO AO GRUPO MASTER
                    $this->veiculo->vincularVeic_GroupMaster($this->input->post('equipamento'), $this->input->post('id_cliente'));
                    $usuario_email = $this->auth->get_login('admin', 'email');
                    $acao = array(
                        'data_criacao' => date('Y-m-d H:i:s'),
                        'usuario' => $usuario_email,
                        'placa' => isset($veic->equipamento) ? $veic->equipamento : '',
                        'acao' => 'A Tornozeleira ' . (isset($veic->equipamento) ? $veic->equipamento : '') . ' foi adicionada ao contrato ' . $this->input->post('id_contrato') . ' pelo usuário ' . $usuario_email
                    );

                    $this->log_veiculo->add($acao);
                    echo json_encode(array('success' => true, 'msg' => 'Tornozeleira adicionada com sucesso'));
                } catch (Exception $e) {
                    echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
                }
            }
        }
    }

    //ADICIONA UM SUPRIMENTO A UM CONTRATO
    public function ajax_add_suprimento()
    {
        if ($this->input->post()) {
            if (!$this->input->post('id_suprimento')) {
                echo json_encode(array('success' => false, 'msg' => validation_errors()));
            } else {
                try {
                    $post = $this->input->post();
                    $this->contrato->inserir_suprimento($post);
                    echo json_encode(array('success' => true, 'msg' => 'Suprimento adicionado com sucesso'));
                } catch (Exception $e) {
                    echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
                }
            }
        } else {
            echo json_encode(array('success' => false, 'msg' => 'Dados faltosos, tente novamente!'));
        }
    }

    //ADICIONA UMA ISCA A UM CONTRATO
    public function ajax_add_isca()
    {
        if ($this->input->post()) {
            if (!$this->input->post('serial')) {
                echo json_encode([
                    'success' => false,
                    'msg' => 'Selecione um serial.'
                ]);
            } else {
                try {
                    $post = $this->input->post();
                    $post['data_cadastro'] = date('Y-m-d H:i:s');

                    $excede_limite = $this->quant_iscas_contrato_excede(1, $post['id_contrato']);
                    if ($excede_limite != -1) {
                        echo json_encode([
                            'success' => false,
                            'msg' => 'Limite de iscas excedido' . ($excede_limite == 0 ? '.' : ', você ainda pode cadastrar ' . $excede_limite . ' iscas'),
                        ]);
                        return;
                    }

                    $this->contrato->inserir_isca($post);

                    $id_usuario = $this->auth->get_login('admin', 'user');

                    $auditoria = array(
                        'serial' => $post['serial'],
                        'id_cliente_atual' => $post['id_cliente'],
                        'id_contrato_atual' => $post['id_contrato'],
                        'id_usuario' => $id_usuario,
                        'operacao' => 'cadastro'
                    );

                    $this->isca->save_auditoria_iscas($auditoria);

                    echo json_encode([
                        'success' => true,
                        'msg' => 'Isca adicionada com sucesso'
                    ]);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'msg' => $e->getMessage()
                    ]);
                }
            }
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Dados faltosos, tente novamente!'
            ]);
        }
    }

    public function ajax_atualiza_status_placa($id_placa, $status, $placa, $contrato)
    {
        $id_cliente = $this->contrato->get_cliente_contratos($contrato);
        try {
            $usuario_email = $this->auth->get_login('admin', 'email');
            $this->veiculo->atualizaStatus_Veiculo($placa, $status, $id_cliente, $contrato);
            $this->contrato->atualiza_status_placa($id_placa, $status, $usuario_email);
            echo json_encode(array('success' => true, 'msg' => 'Status do registro ' . $id_placa . ' alterado com sucesso.'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
        }
    }

    /*
	* DESVINCULA VEICULO E INATIVA-O
	*/
    public function inativar_desvincular_veic($placa, $serial, $id_cliente = null)
    {
        $usuario_email = $this->auth->get_login('admin', 'email');

        //atualiza o veiculo removendo seu serial e mudando o status para 0-'inativo'
        if ($this->veiculo->atualizar_veiculo($placa, $serial, true, '0', false, $id_cliente)) {
            $acao = array(
                'data_criacao' => date('Y-m-d H:i:s'),
                'usuario' => $usuario_email,
                'placa' => $placa,
                'acao' => 'Serial ' . $serial . ' foi desvinculado a placa ' . $placa . ' pelo usuário ' . $usuario_email
            );
            $ret = $this->log_veiculo->add($acao);
            return true;
        }
        return false;
    }

    public function ajax_inativar_veiculo($id_placa, $status, $placa, $contrato, $id_cliente)
    {
        try {

            $usuario_email = $this->auth->get_login('admin', 'email');
            if ($status == 'cadastrado') {
                $serial = '';
            } else {
                $serial = $this->veiculo->getSerialByPlaca($placa, 'serial')->serial;
            }

            if ($serial) {
                //inativa e desvincula o veiculo
                $this->inativar_desvincular_veic($placa, $serial, $id_cliente);
                //atualiza o status da placa
                $this->contrato->atualiza_status_placa($id_placa, $status, $usuario_email);
                echo json_encode(array('success' => true, 'msg' => 'Placa ' . $placa . ' inativada com sucesso!'));
            } else if ($status == 'cadastrado') {
                //atualiza o status da placa
                $this->contrato->atualiza_status_placa($id_placa, 'inativo', $usuario_email);
                echo json_encode(array('success' => true, 'msg' => 'Placa ' . $placa . ' inativada com sucesso!',  'tipo' => true));
            } else {
                echo json_encode(array('success' => false, 'msg' => 'Não foi possível Inativar, pois a placa não possui serial associado!'));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
        }
    }

    //ADICIONA VARIAS ISCAS A UM CONTRATO
    public function ajax_add_iscas_lote()
    {
        if ($this->input->post()) {
            if (!$this->input->post('iscas')) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'Preencha a coluna de serial no documento de importação.',
                    'falhas' => '[]'
                ]);
            } else {
                $iscas = json_decode($this->input->post('iscas'));
                $descricao = $this->input->post('descricao');
                $id_contrato = $this->input->post('id_contrato');
                $id_cliente = $this->input->post('id_cliente');

                $excede_limite = $this->quant_iscas_contrato_excede(count($iscas), $id_contrato);
                if ($excede_limite != -1) {
                    $falhas = [];
                    foreach ($iscas as $isca) {
                        $falhas[] = [
                            'serial' => $isca->Serial,
                            'msg' => 'Limite excedido.'
                        ];
                    }

                    echo json_encode([
                        'status' => false,
                        'msg' => 'Limite de iscas excedido' . ($excede_limite == 0 ? '.' : ', você ainda pode cadastrar ' . $excede_limite . ' iscas'),
                        'falhas' => $falhas ? json_encode($falhas) : '[]',
                    ]);
                    return;
                }

                $falhas = [];
                foreach ($iscas as $isca) {
                    $dados = [];
                    $dados['serial'] = $isca->Serial;
                    $dados['modelo'] = $isca->Modelo;
                    $dados['marca'] = $isca->Marca;
                    $dados['placa'] = isset($isca->Placa) ? $isca->Placa : '';
                    $dados['status'] = $isca->Status == "ativo" ? 1 : 0;
                    $dados['id_contrato'] = $id_contrato;
                    $dados['id_cliente'] = $id_cliente;
                    $dados['descricao'] = $descricao;
                    $dados['data_cadastro'] = date('Y-m-d H:i:s');

                    try {
                        $this->contrato->inserir_isca($dados);

                        $id_usuario = $this->auth->get_login('admin', 'user');

                        $auditoria = array(
                            'serial' => $dados['serial'],
                            'id_cliente_atual' => $dados['id_cliente'],
                            'id_contrato_atual' => $dados['id_contrato'],
                            'id_usuario' => $id_usuario,
                            'operacao' => 'cadastro'
                        );

                        $this->isca->save_auditoria_iscas($auditoria);
                    } catch (Exception $e) {
                        $falhas[] = [
                            'serial' => $isca->Serial,
                            'msg' => $e->getMessage()
                        ];
                    }
                }

                echo json_encode([
                    'status' => true,
                    'msg' => count($falhas) < count($iscas) ? 'Iscas adicionadas com sucesso.' : '',
                    'falhas' => $falhas ? json_encode($falhas) : '[]'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Você deve preencher todos os campos, tente novamente.',
                'falhas' => '[]'
            ]);
        }
    }

    /* Verifica se a quantidade de iscas atual + quantidade a ser cadastrada
       excede o limite de iscas do contrato
       retorna x = -1, quando não excede ou contrato nao existe
       retorna x > 0, quando excede, x sendo o numero de espaços restantes para cadastro */
    public function quant_iscas_contrato_excede($count_iscas, $id_contrato)
    {
        $quant = intval($this->contrato->get_quant_iscas_contrato($id_contrato), 10);
        $num_iscas = $this->contrato->get_cad_iscas_contrato($id_contrato);

        if ($quant) {
            if (($count_iscas + $num_iscas) <= $quant) {
                return -1;
            }

            $restante = $quant - $num_iscas;
            return $restante;
        }
        return -1;
    }

    public function ajax_atualiza_status_isca($id_isca, $status)
    {
        try {
            $this->contrato->atualiza_status_isca($id_isca, $status);

            echo json_encode(array('success' => true, 'msg' => 'Status do registro ' . $id_isca . ' alterado com sucesso.'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
        }
    }

    public function atualiza_teste()
    {
        $lista = $this->contrato->listaPlacasAtivas()->result();
        foreach ($lista as $l) {
            $this->contrato->ativaNoGrupo($l->placa);
        }

        echo "finalizou";
    }

    public function ajax_atualiza_status_chip($id, $status, $id_cliente)
    {
        try {
            $array = array(
                'id_cliente_sim2m' => $status == 'ativo' ? $id_cliente : NULL,
                'status' => $status == 'ativo' ? 1 : 0
            );
            $this->linha->update_linha($array, $id);
            echo json_encode(array('success' => true, 'msg' => 'Status do registro ' . $id . ' alterado com sucesso.'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
        }
    }

    public function ajax_atualiza_status_trz($serial, $id, $id_cliente, $status)
    {

        try {
            //Verifica se a tornozeleira já está ativa
            $retorno = $this->contrato->verifica_status_tornozeleira($serial, 'ativo');
            $array = array('status' => $status);

            if ($retorno && ($status != 'inativo'))
                echo json_encode(array('success' => false, 'msg' => 'A tornozeleira já se encontra ativa no contrato ' . $retorno[0]->id_contrato . '.'));
            else {
                $desassocia = json_decode(from_relatorios_api([
                    'idCliente' => $id_cliente,
                    'equipamentos' => $serial
                ], "tornozeleiras/desassociarDeEstoqueLoteAdmin"));

                // Desassocia do contrato se ele foi desassociado do estoque, ou se ele estava em nenhum estoque.
                if ($desassocia->status == 1 || $desassocia->code == 400) {
                    $this->contrato->update_trz($array, $id);
                    echo json_encode(array('success' => true, 'msg' => 'Status do registro ' . $id . ' alterado com sucesso.'));
                } else {
                    echo json_encode(array('success' => false, 'msg' => 'Status do registro ' . $id . ' não alterado.'));
                }
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
        }
    }

    public function ajax_atualiza_status_sup($id_con_sup, $id_sup, $id_cliente, $status)
    {

        try {
            //Verifica se a tornozeleira já está ativa
            $retorno = $this->contrato->verifica_status_suprimento($id_sup, 'ativo');
            $array = array('status' => $status);

            if ($retorno && ($status != 'inativo'))
                echo json_encode(array('success' => false, 'msg' => 'O suprimento já se encontra ativo no contrato ' . $retorno[0]->id_contrato . '.'));
            else {
                $this->contrato->update_sup($array, $id_con_sup);
                from_relatorios_api([
                    'idCliente' => $id_cliente,
                    'equipamentos' => $id_sup,
                ], "tornozeleiras/desassociarDeEstoqueLoteSuprimentosAdmin");
                echo json_encode(array('success' => true, 'msg' => 'Status do registro ' . $id_con_sup . ' alterado com sucesso.'));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
        }
    }

    public function digitalizacao_contrato()
    {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $id_contrato = $this->input->post('id_contrato');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

        $arquivo_enviado = false;

        if ($descricao == "") {
            echo json_encode(array('status' => false, 'mensagem' => '<div class="alert alert-error">Informe a descrição!</div>'));
        } else {
            if ($arquivo) {
                if ($dados = $this->upload()) {
                    $nome_arquivo = $dados['file_name'];
                    $arquivo_enviado = true;
                }
                if ($arquivo_enviado) {
                    $retorno = $this->contrato->digitalizacao_contrato($id_contrato, $descricao, $nome_arquivo);
                    echo json_encode(array('status' => true, 'mensagem' => '<div class="alert alert-success">Processo realizado com sucesso!</div>', 'registro' => $retorno));
                } else {
                    echo json_encode(array('status' => false, 'mensagem' => '<div class="alert alert-error">' . $this->upload->display_errors() . '</div>'));
                }
            } else {
                echo json_encode(array('status' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>'));
            }
        }
    }

    private function upload()
    {
        $config['upload_path'] = './uploads/contratos';
        $config['allowed_types'] = 'pdf';
        $config['max_size']    = '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('arquivo'))
            return $this->upload->data();
        else
            return false;
    }


    public function cad_contrato_new()
    {
        $dados['titulo'] = 'Clientes';
        $dados['usuarios'] = $this->contrato->get_users();
        $dados['ajuste'] = false;
        if ($this->input->get()) {
            $dados['ajuste'] = $this->input->get('ajuste');
        }
        $this->load->view('contratos/cad_contrato_new', $dados);
    }

    public function gerar_termo_adesao($id_cliente)
    {
        try {
            $cliente = $this->cliente->get_clientes($id_cliente);

            if ($cliente && count($cliente) > 0) {
                $dados['cliente'] = $cliente[0];
                $html = $this->load->view('contratos/cad_termo_adesao', $dados, true);
                echo json_encode(array(
                    'status' => '200',
                    'html' => $html
                ));
            } else {
                echo json_encode(array(
                    'status' => '404',
                    'msg' => 'Não foi possível identificar o cliente. Atualize a página e tente novamente!'
                ));
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => '500',
                'msg' => 'Não foi possível abrir o modal. Tente novamente mais tarde ou entre em contato com o suporte!'
            ));
        }
    }

    public function gerar_termo_pdf(){
        $dados["termo"] = $this->input->post('termo');

        $this->load->view('clientes/termo_adesao_pdf', $dados);
    }

    public function termo_adesao_pdf_html(){


		$this->load->view('clientes/termo_adesao_pdf');
		
    }

    public function termo_adesao_pdf(){

		require_once("../tcpdf/examples/dompdf-master/dompdf_config.inc.php");

		
		$html = $this->load->view('clientes/termo_adesao_pdf', [], TRUE);
		
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper(DOMPDF_DEFAULT_PAPER_SIZE, "portrait");
		$dompdf->render();
		$dompdf->stream("template_pdf_cotacao.pdf", array("Attachment" => false));
    }


    public function cad_contratos($id_cliente, $ajuste = false)
    {
        $dados['titulo'] = 'Clientes';
        $dados['clientes'] = $this->cliente->get_clientes($id_cliente);
        $dados['usuarios'] = $this->contrato->get_users();
        if ($ajuste == '1') {
            $dados['ajuste'] = $ajuste;
        }

        $this->load->view('fix/header', $dados);
        $this->load->view('contratos/cad_contratos');
        $this->load->view('fix/footer');
    }

    public function imprimir_contrato($id_contrato, $id_cliente, $tipo_contrato)
    {
        $dados['contratos'] = $this->contrato->get_contratos($id_contrato);
        $dados['clientes'] = $this->cliente->get_clientes($id_cliente);
        if ($tipo_contrato == 2)
            $this->load->view('contratos/imprimir_contrato_telemetria', $dados);
        else if ($tipo_contrato == 3)
            $this->load->view('contratos/imprimir_norio', $dados);
        else
            $this->load->view('contratos/imprimir_contrato', $dados);
    }

    public function visualizar_contrato($caminho)
    {
        $this->auth->is_allowed('downloads_contratos');
        redirect(base_url('uploads/contratos/' . $caminho));
    }

    public function cadastrar_contrato()
    {
        $contrato = $this->input->post('contrato');
        $valor_mensal = str_replace('.', '', $contrato['mensal_por_veiculo']);
        $valor_instalacao = str_replace('.', '', $contrato['instalacao_por_veiculo']);
        $valor_prestacao = str_replace('.', '', $contrato['valor_parcela_instalacao']);
        $multa_valor = str_replace('.', '', $contrato['valor_multa']);
        $status = 0;

        if (isset($contrato['ajuste']))
            $status = 2;

        $dados = array(
            "id_cliente" => $contrato['cliente_id'],
            "id_vendedor" => $contrato['vendedor'],
            "tipo_proposta" => $contrato['tipo'],
            "quantidade_veiculos" => $contrato['numeros_veiculos'],
            "meses" => $contrato['meses_contrato'],
            "valor_mensal" => str_replace(',', '.', $valor_mensal),
            "valor_instalacao" => str_replace(',', '.', $valor_instalacao),
            "boleto" => $contrato['taxa_boleto'],
            "prestacoes" => $contrato['instalacao_parcelas'],
            "valor_prestacao" => str_replace(',', '.', $valor_prestacao),
            "data_prestacao" => $contrato['data_primeira_parcela'],
            "vencimento" => $contrato['dia_vencimento'],
            "primeira_mensalidade" => $contrato['primeira_mensalidade'],
            "data_contrato" => $contrato['data_contrato'],
            "status" => $status,
            "data_cadastro" => date('Y-m-d H:i:s'),
            "multa" => $contrato['multa_contrato'],
            "multa_valor" => str_replace(',', '.', $multa_valor)
        );

        if ($this->contrato->cadastrar_contrato($dados)) {
            $id_user = $this->auth->get_login_dados('user');
            $id_user = (int) $id_user;
            $this->log_shownet->gravar_log($id_user, 'contratos', 0, 'criar', '',  $dados);
            echo json_encode(array('mensagem' => 'Contrato cadastrado com sucesso', 'status' => 'OK'));
        } else {
            echo json_encode(array('mensagem' => 'Erro! Contrato já cadastrado.', 'status' => false));
        }
    }

    /*
    * ATIVA/DESATIVA COBRANÇA POR CONSUMO
    */
    public function faturar_consumo()
    {
        $status = $this->input->post('status');
        $id = $this->input->post('id');
        if ($this->contrato->updateConsumoFatura($id, $status)) {
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false, 'msn' => 'Erro! Tente mais tarde!'));
        }
    }

    /*
    * ATIVA/DESATIVA COBRANÇA DE TAXA DE BOLETO
    */
    public function taxa_boleto()
    {
        $status = $this->input->post('status');
        $id = $this->input->post('id');
        if ($this->contrato->updateTaxaBoleto($id, $status)) {
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false, 'msn' => 'Erro! Tente mais tarde!'));
        }
    }

    public function faturamento($id_cliente)
    {
        $this->load->model('cliente');

        $dados['cliente'] = $this->cliente->get(array('id' => $id_cliente));

        $data_ini = $this->input->post('dt_ini');
        $data_fim = $this->input->post('dt_fim');
        $dados['consumo'] = array();

        if ($data_ini && $data_fim) {

            $data_ini = data_for_unix($data_ini);
            $data_fim = data_for_unix($data_fim);

            $consumo = $this->db->query("SELECT DISTINCT fr.placa,
              (SELECT GROUP_CONCAT(DISTINCT(DATE(DATA))) FROM showtecsystem.fatura_consumo
              WHERE placa = fr.placa AND id_cliente = {$id_cliente}
              AND DATE(DATA) BETWEEN '{$data_ini}' AND '{$data_fim}' ) datas,
              (SELECT SUM(valor) FROM showtecsystem.fatura_consumo
              WHERE placa = fr.placa AND id_cliente = {$id_cliente}
              AND DATE(DATA) BETWEEN '{$data_ini}' AND '{$data_fim}' ) total,
              id_contrato
              FROM showtecsystem.`fatura_consumo` AS fr
              WHERE fr.id_cliente = {$id_cliente} AND DATE(fr.data) BETWEEN '{$data_ini}' AND '{$data_fim}'
              ORDER BY placa;");
            $dados['consumo'] = $consumo->result();

            if ($consumo->result()) {
                $dados['resumo']['total'] = 0;
                foreach ($dados['consumo'] as $veiculo) {
                    $dados['resumo']['contratos'][$veiculo->id_contrato] = $veiculo->id_contrato;
                    $dados['resumo']['veiculos'] = count($dados['consumo']);
                    $dados['resumo']['total'] += $veiculo->total;
                }
            }
        }
        $dados['titulo'] = 'Show Tecnologia';
        $this->load->view('fix/header', $dados);
        $this->load->view('clientes/faturamento_view');
        $this->load->view('fix/footer');
    }

    public function contrato_trabalho($id_funcionario)
    {
        if (isset($_POST)) {
            $this->load->model('usuario');
            $dados['titulo'] = 'Show Tecnologia';
            $dados['dados'] = $this->usuario->get(array('id' => $id_funcionario));
            $this->load->view('fix/header', $dados);
            $this->load->view('contratos/contrato_trabalho');
            $this->load->view('fix/footer');
        } else {
            $this->load->view('erros/403');
        }
    }

    public function show_status_contrato($status)
    {
        $back_status = 'erro nos params';

        switch ($status) {
            case 0:
                $back_status = '<span class="label">Cadastrado</span>';
                break;
            case 1:
                $back_status = '<span class="label label-warning">Em trânsito OS</span>';
                break;
            case 2:
                $back_status = '<span class="label label-success">Ativo</span>';
                break;
            case 3:
                $back_status = '<span class="label label-inverse">Cancelado</span>';
                break;
            case 4:
                $back_status = '<span class="label label-info">Em Teste</span>';
                break;
            case 5:
                $back_status = '<span class="label label-important">Bloqueado</span>';
                break;
            case 6:
                $back_status = '<span class="label">Encerrado</span>';
                break;
        }

        return $back_status;
    }

    /*
    * LISTA CONTRATOS DE UM CLIENTE
    */
    public function listaAjaxContratosCliente()
    {
        $id_cliente = $this->input->post('id_cliente');
        $contratos = $this->contrato->listar(array('id_cliente' => $id_cliente), 0, 9999999, 'ctr.id', 'DESC', 'ctr.id,');

        if ($contratos)
            echo json_encode(array('success' => true, 'contratos' => $contratos));
        else
            echo json_encode(array('success' => false, 'msg' => lang('cliente_nao_possui_contratos')));
    }

    /*
    * LISTA CONTRATOS DE CLIENTE - SELECT2 INTELIGENTE
    */
    public function listAjaxSelectContratos($id_cliente)
    {
        $data = array('results' => array());
        $search = $this->input->get('term');

        $contratos = $this->contrato->listarContratosFilter($id_cliente, $search);

        if (!empty($contratos)) {
            foreach ($contratos as $contrato) {
                $data['results'][] = array(
                    'id' => $contrato->id,
                    'text' => $contrato->id,
                    'text' => $contrato->id . ' - ' . $this->getStatusLabel($contrato->status)
                );
            }
        }

        echo json_encode($data);
    }

    public function getStatusLabel($status)
    {
        if ($status == 0) {
            return "Cadastrado";
        } else if ($status == 1) {
            return "Esperando OS";
        } else if ($status == 2) {
            return "Ativo";
        } else if ($status == 3) {
            return "Cancelado";
        } else if ($status == 5) {
            return "Bloqueado";
        } else if ($status == 6) {
            return "Encerrado";
        } else if ($status == 7) {
            return "Em Processo de Retirada";
        } else {
            return "Outro";
        }
    }
}
