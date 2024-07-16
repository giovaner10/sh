<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(0);
class Equipamentos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('equipamento');
        $this->load->model('veiculo');
        $this->load->model('linha');
        $this->load->model('mapa_calor');
    }

    public function index() {
        $this->listar_equipamentos();
    }

    /*
    * Lista equipamentos para select2
    */
    function ajaxListSelect() {
        $like = NULL;
        if ($search = $this->input->get('q'))
            $like = array('serial' => $search);

        echo json_encode(array('results' => $this->equipamento->listar(array(), 0, 10, 'id', 'asc', 'serial as text, serial as id', $like)));
    }

    /*
    * Lista equipamentos para select2
    */
    function ajaxListSelect2() {
        $like = NULL;
        if ($search = $this->input->get('q'))
            // $like = array('serial' => $search);
            $like = $search;

        echo json_encode(array('results' => $this->equipamento->listar_equipamentos_disponiveis(0, 10, 'id', 'asc', $like)));
    }

    /*
    * Lista cadastro de equipamentos - DataTable
    */
    public function listar_equipamentos() {
        $dados['count_estoque'] = $this->equipamento->count_estoque();
        $dados['titulo'] = lang('listagem_equipamentos').' - '.lang('show_tecnologia');
        $dados['load'] = array('buttons_html5', 'xls', 'datatable_responsive');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        
        $this->mapa_calor->registrar_acessos_url(site_url('/equipamentos/listar_equipamentos'));
        
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('equipamentos/cadastro');
        $this->load->view('fix/footer_NS');
    }

    /*
    * Lista cadastro de equipamentos - DataTable
    */
    public function loadEquipamentos(){
        $dados = $this->input->post();
		// $order = $dados['order'][0] ? $dados['order'][0] : false;
        $order = false;
		$draw = $dados['draw'] ? $dados['draw'] : 1;
		$start = $dados['start'] ? $dados['start'] : 0;
		$limit = $dados['length'] ? $dados['length'] : 10;
		$filtro = $dados['filtroEquipamentos'] ? $dados['filtroEquipamentos'] : false;
		$search = $dados['searchTableEquipamentos'] ? $dados['searchTableEquipamentos'] : false;

        $temChip = false;
        if($filtro && in_array($filtro, array('ccid1', 'ccid2'))) {
            $temChip = $this->linha->getLinhasPorCCIDs(array($search), $select='id');
            if ($temChip) {
                $temChip = $temChip[0]->id;
            }else {
                exit(json_encode(array('status' => false, 'data' => array(), 'draw' => intval($draw)+1, 'recordsTotal' => 0, 'recordsFiltered' => 0)));
            }
        }

        $temLinha=false;
        if($filtro && in_array($filtro, array('linha1', 'linha2'))) {
            $temLinha = $this->linha->getLinhasPorNUMEROs(array($search), $select='id');
            if($temLinha) {
                $temLinha = $temLinha[0]->id;
            }else {
                exit(json_encode(array('status' => false, 'data' => array(), 'draw' => intval($draw)+1, 'recordsTotal' => 0, 'recordsFiltered' => 0)));
            }
        }

		$retorno = array();

		$listEquipamentos = $this->equipamento->listEquipamentosServerSide(
			'id, serial, modelo, data_cadastro, ccid, id_chip, id_chip_2, status',
			$order, $start, $limit, $search, $filtro, $draw, $temChip, $temLinha
		);

        if($listEquipamentos['equipamentos']) {
            $ccids = array();
            $ids_chip = array();
            $ids_chip_2 = array();

			foreach ($listEquipamentos['equipamentos'] as $equipament) {
				if($equipament->ccid) $ccids[] = $equipament->ccid;
				if($equipament->id_chip) $ids_chip[] = $equipament->id_chip;
				if($equipament->id_chip_2) $ids_chip_2[] = $equipament->id_chip_2;
			}
            $linhasAll = array();
            $linhas2All = array();
            $chipsAll = array();
            $chips2All = array();

            if ($ccids) {
                $linhas = $this->linha->getLinhasPorCCIDs($ccids, 'ccid, numero as linha');
                if ($linhas) {
                    foreach ($linhas as $l) {
                        $linhasAll[$l->ccid] = $l->linha;
                        $linhas2All[$l->ccid] = $l->linha;
                    }
                }
            }

            // if ($ccids) {
            //     $linhas = $this->linha->getLinhasPorCCIDs($ccids, 'ccid, numero as linha');
            //     if ($linhas) {
            //         foreach ($linhas as $l) {
            //             $linhas2All[$l->ccid] = $l->linha;
            //         }
            //     }
            // }

            if ($ids_chip) {
                $chips = $this->linha->getLinhasPorIDs($ids_chip, 'id as id_chip, numero, ccid as ccid_manual');
                if ($chips) {
                    foreach ($chips as $c) {
                        $chipsAll[$c->id_chip] = array('numero' => $c->numero, 'ccid_manual' => $c->ccid_manual);
                    }
                }
            }

            if ($ids_chip_2) {
                $chips2 = $this->linha->getLinhasPorIDs($ids_chip_2, 'id as id_chip, numero as numero, ccid as ccid_manual');
                if ($chips2) {
                    foreach ($chips2 as $c) {
                        $chips2All[$c->id_chip] = array('numero' => $c->numero, 'ccid_manual' => $c->ccid_manual);
                    }
                }
            }

			foreach ($listEquipamentos['equipamentos'] as $equipamento) {

                //CONSTROI O CAMPO INFO
                $info = '';
                if ($equipamento->ccid) $info = 'AUTO';
                elseif ($equipamento->id_chip || $equipamento->id_chip_2 || isset($linhasAll[$equipamento->ccid]) || isset($linhas2All[$equipamento->ccid])) $info = 'MANUAL';
                else $info = 'SEM VINCULO';

                //CONSTROI O CAMPO STATUS
                $status = '';
                switch ($equipamento->status) {
                    case '2':
                        $status =  'Enviado ao Cliente';
                        break;
                    case '3':
                        $status = 'Disponivel no Cliente';
                        break;
                    case '4':
                        $status = 'Instalado';
                        break;
                    default:
                        $status = 'Em Estoque';
                        break;
                }

                //CONSTROI OS CAMPOS DE LINHA E CCID
                $linha = ''; $ccid = '';
                if ($equipamento->ccid && isset($linhasAll[$equipamento->ccid])) {
                    $linha = $linhasAll[$equipamento->ccid];
                    $ccid = $equipamentos->ccid;

                } elseif (isset($chipsAll[$equipamento->id_chip]['ccid_manual']) && isset($chipsAll[$equipamento->id_chip]['numero'])) {
                    $linha = $chipsAll[$equipamento->id_chip]['numero'];
                    $ccid = $chipsAll[$equipamento->id_chip]['ccid_manual'];
                }

                //CONSTROI OS CAMPOS DE LINHA_2 E CCID_2
                $linha_2 = ''; $ccid_2 = '';
                if ($equipamento->ccid && isset($linhas2All[$equipamento->id_chip_2])) {
                    $linha_2 = $linhas2All[$equipamento->chip_2];
                    $ccid_2 = $equipamentos->ccid;

                } elseif (isset($chips2All[$equipamento->id_chip_2]['ccid_manual']) && isset($chips2All[$equipamento->id_chip_2]['numero'])) {
                    $linha_2 = $chips2All[$equipamento->id_chip_2]['numero'];
                    $ccid_2 = $chips2All[$equipamento->id_chip_2]['ccid_manual'];
                }

                //CONSTROI BOTOES DE EDICAO E POSICAO
                $btnEditar = '<a class="btn btn-primary" data-toggle="modal" id="btn_editar"
                                href="#" data-id="'.$equipamento->id.'" onclick="change_equipamento(this)" data-id_linha_1="'.$equipamento->id_chip.'" data-linha_1="'.$linha.'" data-id_linha_2="'.$equipamento->id_chip_2.'" data-linha_2="'.$linha_2.'"
                                data-target="#editar_equipamento" title="Editar Equipamento"> <i class="fa fa-pencil-square"></i>
                            </a>';

                $btnPosicao = site_url('equipamentos/posicao/'.$equipamento->serial);

				$retorno[] = array(
					'id' => $equipamento->id,
					'serial' => $equipamento->serial,
					'modelo' => $equipamento->modelo,
					'data_cadastro' => date('d/m/Y H:i:s', strtotime($equipamento->data_cadastro)),
					'linha1' => $linha,
					'ccid1' => $ccid,
					'linha2' => $linha_2,
					'ccid2' => $ccid_2,
					'info' => $info,
					'status' => $status,
					'editar' => $btnEditar,
					'posicao' => $btnPosicao,
				);
			}
			echo json_encode(array('draw' => intval($listEquipamentos['draw']), 'recordsTotal' =>  intval($listEquipamentos['recordsTotal']), 'recordsFiltered' =>  intval($listEquipamentos['recordsFiltered']), 'data'=> $retorno) );

		}else{
			echo json_encode(array('status' => false, 'data' => array(), 'draw' => intval($draw)+1, 'recordsTotal' => 0, 'recordsFiltered' => 0));
		}
    }

    public function atualizar_fw_trz() {
        exit(json_encode($this->equipamento->atualiza_fwTrz($this->input->post())));
    }

    public function posicao($serial) {
        $dados['posicao'] = false;
        if ($equipamento = $this->equipamento->get_resposta($serial)) {
            $latitude = isset($equipamento->X) ? $equipamento->X : '';
            $longitude = isset($equipamento->Y) ? $equipamento->Y : '';
            
            if (isset($equipamento->ENDERECO) && $equipamento->ENDERECO !== '' && !is_null($equipamento->ENDERECO))
                $endereco = $equipamento->ENDERECO;
            elseif ($equipamento->ENDERECO == '' || is_null($equipamento->ENDERECO))
                $endereco = pegar_endereco_referencias($latitude, $longitude);
            else
                $endereco = 'Endereço não encontrado';

            $comandos = $this->equipamento->getComandosAtualiza($serial);
            // 0 = sem sinal , 1 gprs ativo , 2 comunicando via satelite
            $dados = array(
                'data' => isset($equipamento->DATA) ? $equipamento->DATA : '',
                'hodometro' => isset($equipamento->ODOMETER) ? number_format(intval($equipamento->ODOMETER) / 1000, 2, '.', '') : '0',
                'data_sys' => isset($equipamento->DATASYS) ? $equipamento->DATASYS : '',
                'serial' => isset($equipamento->ID) ? $equipamento->ID : '',
                'fw' => isset($equipamento->FWVER) ? $equipamento->FWVER : '0',
                'cinta' => isset($equipamento->IN3) ? $equipamento->IN3 : '0',
                'case' => isset($equipamento->IN8) ? $equipamento->IN8 : '0',
                'operadora' => isset($equipamento->operadora) ? $equipamento->operadora : ' - ',
                'sinalGSM' => isset($equipamento->sinalGSM) ? $equipamento->sinalGSM : '0',
                'carregador' => $equipamento->IN2 == 1 ? 'Conectado' : 'Desconectado',
                'ignicao' => isset($equipamento->IGNITION) ? $equipamento->IGNITION : '0',
                'gps' => isset($equipamento->GPRS) ? $equipamento->GPS : '0',
                'bateria' => isset($equipamento->VOLTAGE) ? $equipamento->VOLTAGE : '0',
                'gprs' => isset($equipamento->GPRS) ? $equipamento->GPRS : '0',
                'velocidade' => isset($equipamento->VEL) ? $equipamento->VEL : '0',
                'endereco' => $endereco,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'comandos' => $comandos
            );
            $dados['posicao'] = true;

            if(substr($equipamento->ID, 0, 1) == "I"){
                $this->load->model('iscas/iscas');
                $isca = $this->iscas->getIscas(['serial' => $equipamento->ID]);
                $porcentagemBateria = $this->getPorcentagemBateriaIsca($isca[0]['modelo'],$equipamento->VOLTAGE);
                if(isset($porcentagemBateria)) $dados['porcentagemBateria'] = $porcentagemBateria;
            }
        }

        $dados['serial'] = $serial;
        $this->load->view('equipamentos/posicao', $dados);
    }

    private function getPorcentagemBateriaIsca($modelo,$voltagem){

		if(isset($modelo) && isset($voltagem)){
			
			if(floatval($voltagem) > 4.5){
				return 100;
			}else if(floatval($voltagem) < 3.1){
				return 0;
			}
			
			$voltagem = strval($voltagem);
			$voltagem = substr($voltagem, 0, 3);
			$modelo = substr($modelo, 0, 5);
		
			$porcentagemDeModelos = array(
				"ST400" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 100,
					"4.2" => 100,
					"4.1" => 97,
					"4.0" => 86,
					"4" => 86,
					"3.9" => 74,
					"3.8" => 61,
					"3.7" => 32,
					"3.6" => 8,
					"3.5" => 4,
					"3.4" => 2,
					"3.3" => 1,
					"3.2" => 0,
					"3.1" => 0,
				),
				"ST420" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 97,
					"4.2" => 90,
					"4.1" => 86,
					"4.0" => 78,
					"4" => 78,
					"3.9" => 65,
					"3.8" => 40,
					"3.7" => 16,
					"3.6" => 5,
					"3.5" => 0,
					"3.4" => 0,
					"3.3" => 0,
					"3.2" => 0,
					"3.1" => 0,
				),
				"ST440" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 100,
					"4.2" => 100,
					"4.1" => 100,
					"4.0" => 100,
					"4" => 100,
					"3.9" => 100,
					"3.8" => 100,
					"3.7" => 100,
					"3.6" => 100,
					"3.5" => 100,
					"3.4" => 20,
					"3.3" => 6,
					"3.2" => 2,
					"3.1" => 1,
				),
				"ST480" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 100,
					"4.2" => 100,
					"4.1" => 98,
					"4.0" => 96,
					"4" => 96,
					"3.9" => 84,
					"3.8" => 69,
					"3.7" => 47,
					"3.6" => 17,
					"3.5" => 11,
					"3.4" => 4,
					"3.3" => 2,
					"3.2" => 1,
					"3.1" => 0,
				),
				"ST410" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 100,
					"4.2" => 100,
					"4.1" => 97,
					"4.0" => 86,
					"4" => 86,
					"3.9" => 74,
					"3.8" => 61,
					"3.7" => 32,
					"3.6" => 8,
					"3.5" => 4,
					"3.4" => 2,
					"3.3" => 1,
					"3.2" => 0,
					"3.1" => 0,
				),
				"ST390" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 100,
					"4.2" => 100,
					"4.1" => 100,
					"4.0" => 99,
					"4" => 99,
					"3.9" => 75,
					"3.8" => 53,
					"3.7" => 18,
					"3.6" => 3,
					"3.5" => 2,
					"3.4" => 1,
					"3.3" => 0,
					"3.2" => 0,
					"3.1" => 0,
				),
				"ST419" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 100,
					"4.2" => 100,
					"4.1" => 97,
					"4.0" => 86,
					"4" => 86,
					"3.9" => 74,
					"3.8" => 61,
					"3.7" => 32,
					"3.6" => 8,
					"3.5" => 4,
					"3.4" => 2,
					"3.3" => 1,
					"3.2" => 0,
					"3.1" => 0,
				),
				"ST449" => array(
					"4.5" => 100,
					"4.4" => 100,
					"4.3" => 100,
					"4.2" => 100,
					"4.1" => 97,
					"4.0" => 86,
					"4" => 86,
					"3.9" => 74,
					"3.8" => 61,
					"3.7" => 32,
					"3.6" => 8,
					"3.5" => 4,
					"3.4" => 2,
					"3.3" => 1,
					"3.2" => 0,
					"3.1" => 0,
				),
			);
            
            if(isset($porcentagemDeModelos[$modelo]) && isset($porcentagemDeModelos[$modelo][$voltagem])){
                return $porcentagemDeModelos[$modelo][$voltagem];
            } else {
                return null;
            } 
		} else{
			return null;
		}

	}

    public function cadastrar()	{
        $linhas = $this->linha->total_linhas();

        $j_linhas = array();
        if (count($linhas) > 0) {
            foreach ($linhas as $linha) {
                $j_linhas[] = $linha->numero;
            }
        }
        $dados['j_linhas'] = json_encode($j_linhas);
        $this->load->view('equipamentos/cadastrar', $dados);
    }

    public function editar($id_equipamento, $numero = false) {
        $linhas = $this->linha->total_linhas();
        if (count($linhas) > 0) {
            foreach ($linhas as $linha) {
                $j_linhas[] = $linha->numero;
            }
        }
        $dados['j_linhas'] = json_encode($j_linhas);
        $dados['id_equipamento'] = $id_equipamento;
        $dados['numero'] = $numero;
        $this->load->view('equipamentos/editar', $dados);
    }

    public function desvincular($serial)
    {
        $this->load->model('equipamento');
    
        $equipamento = $this->equipamento->editar_equipamento($serial);
    
        if ($equipamento) {
            // Desvincular o equipamento usando o ID
            if ($this->equipamento->disvincular_equipamento($equipamento['id'])) {
                echo json_encode(['success' => true, 'message' => 'Equipamento desvinculado com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao desvincular o equipamento.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Equipamento não encontrado.']);
        }
    }
    

    public function adicionar()	{
        $id_linha1 = $this->input->post('linha1');
        $id_linha2 = $this->input->post('linha2');

        if ($equipamento = $this->input->post('equipamento')) {
            if ($this->equipamento->get_equipamentos($equipamento['serial'])) {
                // $this->session->set_flashdata('erro', 'Equipamento '.$equipamento['serial'].' já cadastrado, informe outro equipamento');
                $this->listar_equipamentos();
                echo json_encode(['success' => false, 'message' => 'Equipamento '.$equipamento['serial'].' já cadastrado, informe outro equipamento']);
                exit();

            }else{
                if (!$id_linha1 && !$id_linha2) {
                    if ($this->equipamento->adicionar_equipamento($equipamento)) {
                        //$this->session->set_flashdata('sucesso', 'Equipamento '.$equipamento['serial'].' cadastrado com sucesso');
                        $this->listar_equipamentos();
                        echo json_encode(['success' => true, 'message' => 'Equipamento '.$equipamento['serial'].' cadastrado com sucesso']);
                        exit();

                    }else{
                        //$this->session->set_flashdata('erro', 'Erro ao cadastrar equipamento');
                        $this->listar_equipamentos();
                        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar equipamento']);
                        exit();

                    }
                }else if($id_linha1 == $id_linha2){
                    //$this->session->set_flashdata('erro', 'Informe linhas diferentes para cadastrar o equipamento');
                    $this->listar_equipamentos();
                    echo json_encode(['success' => false, 'message' => 'Informe linhas diferentes para cadastrar o equipamento']);
                    exit();
                }else{
                    $chip1 = $this->linha->validar_chip(array('id' => $id_linha1));
                    $chip2 = $this->linha->validar_chip(array('id' => $id_linha2));

                    if ($chip1 || $chip2) {
                        if ($chip1) {
                            $equip1 = $chip1[0]->id_equipamento;
                            if ($equip1 != '0' && $equip1 != '' ) {
                                //$this->session->set_flashdata('erro', 'Linha(s) já cadastrada(s) em outro equipamento');
                                $this->listar_equipamentos();
                                echo json_encode(['success' => false, 'message' => 'Linha(s) já cadastrada(s) em outro equipamento']);
                                exit();

                            }else{
                                $equipamento['id_chip'] = $chip1[0]->id;
                            }
                        }

                        if ($chip2) {
                            $equip2 = $chip2[0]->id_equipamento;
                            if ($equip2 != '0' && $equip2 != '') {
                                //$this->session->set_flashdata('erro', 'Linha(s) já cadastrada(s) em outro equipamento');
                                $this->listar_equipamentos();
                                echo json_encode(['success' => false, 'message' => 'Linha(s) já cadastrada(s) em outro equipamento']);
                                exit();

                            }else{
                                $equipamento['id_chip_2'] = $chip2[0]->id;
                            }
                        }

                        if ($this->equipamento->adicionar_equipamento($equipamento)) {
                            //$this->session->set_flashdata('sucesso', 'Equipamento '.$equipamento['serial'].' cadastrado com sucesso');
                            $this->listar_equipamentos();
                            echo json_encode(['success' => true, 'message' => 'Equipamento '.$equipamento['serial'].' cadastrado com sucesso']);
                            exit();

                        }else{
                            //$this->session->set_flashdata('erro', 'Erro ao cadastrar equipamento');
                            $this->listar_equipamentos();
                            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar equipamento']);
                            exit();

                        }

                    }else{
                        //$this->session->set_flashdata('erro', 'Linha(s) não cadastrada(s) ou inexistente(s), favor informar uma linha cadastrada');
                        $this->listar_equipamentos();
                        echo json_encode(['success' => false, 'message' => 'Linha(s) não cadastrada(s) ou inexistente(s), favor informar uma linha cadastrada']);
                        exit();

                    }
                }
            }
        }else{
            //$this->session->set_flashdata('erro', 'Atenção - Erro ao cadastrar equipamento');
            $this->listar_equipamentos();
            echo json_encode(['success' => false, 'message' => 'Atenção - Erro ao cadastrar equipamento']);
            exit();

        }
    }

    public function adicionarLote()
    {
        if($this->input->post() && $this->input->post('equipamentos')) {
            $eqps = json_decode($this->input->post('equipamentos'));

            $falhas = [];
            foreach($eqps as $eqp) {
                $dados = [];
                $dados['serial'] = $eqp->Serial;
                $dados['marca'] = $eqp->Marca;
                $dados['modelo'] = $eqp->Modelo;

                if($this->equipamento->get_equipamentos($dados['serial'])) {
                    $falhas[] = [
                        'serial' => $dados['serial'],
                        'msg' => 'Serial do equipamento já existe.'
                    ];
                    continue;
                }

                // Se os chips estiverem definidos porém forem iguais não cadastrar
                if(isset($eqp->Linha1) && isset($eqp->Linha2) && $eqp->Linha1 == $eqp->Linha2) {
                    $falhas[] = [
                        'serial' => $dados['serial'],
                        'msg' => 'Linhas 1 e 2 não podem ser iguais.'
                    ];
                } else {
                    if(isset($eqp->Linha1) && isset($eqp->Linha2)) {
                        $chip1 = $this->linha->validar_chip(array('id' => $eqp->Linha1));
                        $chip2 = $this->linha->validar_chip(array('id' => $eqp->Linha2));

                        if($chip1 || $chip2) { // Se pelo menos 1 dos chips é válido cadastrar o equipamento com ele desde que o chip não pertença a outro equipamento
                            if ($chip1) {
                                $equip1 = $chip1[0]->id_equipamento;
                                if ($equip1 != '0' && $equip1 != '' ) {
                                    $falhas[] = [
                                        'serial' => $dados['serial'],
                                        'msg' => 'Linha 1 já cadastrada em outro equipamento.'
                                    ];
                                    continue; // Se a linha já estiver em outro equipamento não cadastrar o equipamento
                                }else{
                                    $dados['id_chip'] = $eqp->Linha1;
                                }
                            }

                            if ($chip2) {
                                $equip2 = $chip2[0]->id_equipamento;
                                if ($equip2 != '0' && $equip2 != '') {
                                    $falhas[] = [
                                        'serial' => $dados['serial'],
                                        'msg' => 'Linha 2 já cadastrada em outro equipamento.'
                                    ];
                                    continue; // Se a linha já estiver em outro equipamento não cadastrar o equipamento
                                }else{
                                    $dados['id_chip_2'] = $eqp->Linha2;
                                }
                            }

                        } else {
                            $falhas[] = [
                                'serial' => $dados['serial'],
                                'msg' => 'Linha(s) não cadastrada(s) ou inexistente(s), favor informar linha(s) cadastrada(s).'
                            ];
                            continue; // Se nenhuma linha for valida, nao cadastrar
                        }
                    }

                    $result = $this->equipamento->adicionar_equipamento($dados);
                    if(!$result) {
                        $falhas[] = [
                            'serial' => $dados['serial'],
                            'msg' => 'Erro ao cadastrar equipamento.'
                        ];
                    }
                }
            }

            echo json_encode([
                'status' => true,
                'msg' => count($falhas) == count($eqps) ? 'Não foi possível cadastrar nenhum equipamento.' : 'Equipamentos adicionados com sucesso.',
                'falhas' => $falhas ? json_encode($falhas) : ''
            ]);

        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Você deve preencher todos os campos, tente novamente.',
                'falhas' => ''
            ]);
        }

    }

    public function editando() {
        $id_equipamento = $this->input->post('id_equipamento');
        $id_linha1 = $this->input->post('linha1');
        $id_linha2 = $this->input->post('linha2');

        if($id_linha1 && $id_linha2 && $id_linha1 == $id_linha2){
            $this->listar_equipamentos();
            echo json_encode(['success' => false, 'message' => 'Informe linhas diferentes para salvar o equipamento']);
            exit();
        }else if ($id_linha1 || $id_linha2) {
            $chip1 = $this->linha->validar_chip(array('id' => $id_linha1));
            $chip2 = $this->linha->validar_chip(array('id' => $id_linha2));

            $update_linha = $this->linha->desvinculaChipEqp($id_equipamento);

            if ($chip1) {
                $equip1 = $chip1[0]->id_equipamento;
                if ($equip1 != '0' && $equip1 != '' && $equip1 != $id_equipamento) {
                    $this->listar_equipamentos();
                    echo json_encode(['success' => false, 'message' => 'Linha já cadastrada em outro equipamento']);
                    exit();
                } else { # ATUALIZA AS TABELAS CAD_CHIPS E CAD_EQUIPAMENTOS
                    $update_eqp1 = $this->linha->atualizaLinha_equip($id_equipamento, $id_linha1, $this->linha->buscaCcid_byLinha($id_linha1));
                    $update_linha = $this->linha->atualizaEquip_linha($id_equipamento, $id_linha1);
                }
            }else{
                $update_eqp1 = $this->linha->atualizaLinha_equip($id_equipamento, 0, 0);
            }

            if ($chip2) {
                $equip2 = $chip2[0]->id_equipamento;
                if ($equip2 != '0' && $equip2 != '' && $equip2 != $id_equipamento) {
                    $this->listar_equipamentos();
                    echo json_encode(['success' => false, 'message' => 'Linha já cadastrada em outro equipamento']);
                    exit();
                }else { # ATUALIZA AS TABELAS CAD_CHIPS E CAD_EQUIPAMENTOS
                    $update_eqp2 = $this->linha->atualiza_chip2_equip($id_equipamento, $id_linha2, $this->linha->buscaCcid_byLinha($id_linha2));
                    $update_linha = $this->linha->atualizaEquip_linha($id_equipamento, $id_linha2);
                }
            }else{
                $update_eqp2 = $this->linha->atualiza_chip2_equip($id_equipamento, 0, 0);
            }
        } else {
            $update_eqp1 = $this->linha->atualizaLinha_equip($id_equipamento, 0, 0);
            $update_eqp2 = $this->linha->atualiza_chip2_equip($id_equipamento, 0, 0);
            $update_linha = $this->linha->desvinculaChipEqp($id_equipamento);
        }

        if ($update_eqp1 && $update_eqp2 && $update_linha) {
            $this->listar_equipamentos();
            echo json_encode(['success' => true, 'message' => 'Linha(s) atualizadas(s) com sucesso!']);
            exit();
        } else {
            $this->listar_equipamentos();
            echo json_encode(['success' => true, 'message' => 'Erro ao tentar atualizar a(s) linha(s).']);
            exit();
        }

    }

    public function equipamentos_parado(){
        $dados['titulo'] = "Showtecnologia";
        $this->mapa_calor->registrar_acessos_url(site_url('/equipamentos/equipamentos_parado'));
        $this->load->view('fix/header', $dados);
        $this->load->view('equipamentos/relatorio_parado');
        $this->load->view('fix/footer');
    }

    public function get_eqp_parado(){
        $data = array();
        $dtIni = $this->input->get('dtIni');
        $dtFim = $this->input->get('dtFim');
        $dados_eqp = $this->equipamento->getParados($dtIni, $dtFim);
        $i = 1;
        foreach ($dados_eqp as $key => $val){
            $numero = $val->numero;
            $nome = $val->nome;

            if ($val->nome == null) {$nome = "----------";}
            if ($val->numero == null){$numero = "----------";}
            $data[] = array(
                'id' => $i++,
                'serial' => $val->serial,
                'data' => dh_mktime_for_humans($val->data),
                'ccid' => $val->ccid,
                'número' => $numero,
                'cliente' => $nome
            );
        }
        echo json_encode($data);
    }

    // Desenvolvidor por Saulo Mendes //
    public function dashboard_eqp() {
        // $dados = array(
        //     'titulo' => 'Showtecnologia',
        //     'eqp_quantidades' => $this->equipamento->qntd_eqp_byMarca(),
        //     'eqp_status' => $this->equipamento->eqp_ativos()
        // );

        $dados = array(
            'titulo' => 'Showtecnologia',
            'eqp_quantidades' => array(),
            'eqp_status' => array()
        );

        /* PRECISO PEGAR A QUANTIDADE DE EQUIPAMENTOS POR MARCA, POR MODELO DE CADA MARCA, QUANTIDADE DE EQUIPAMENTOS ENVIADOS, DISPONIVEIS E INSTALADO DE CADA MARCA E DE CADA MODELO */
        $this->load->view('fix/header4');
        $this->load->view('equipamentos/dashboard', $dados);
        $this->load->view('fix/footer4');
    }
    // FIM //

    function conferencia() {
        $equipamentos = $this->equipamento->get_equipamentos_tot();

        foreach ($equipamentos as $eqp) {
            if ($this->equipamento->instalado($eqp))
                echo $eqp->serial." Instalado. <br/>";
            elseif ($this->equipamento->enviado($eqp))
                echo $eqp->serial." Enviado ao cliente. <br/>";
            elseif ($this->equipamento->cliente($eqp))
                echo $eqp->serial." Em cliente. <br/>";
            elseif ($this->equipamento->estoque($eqp))
                echo $eqp->serial." Em estoque. <br/>";
            else
                echo $eqp->serial." Não definido. <br/>";

        }
    }

    function pesquisaEquipamentos()
    {
        $pesquisa = []; # Pesquisa serial
        if ($this->input->get()) {
            $pesquisa = [
                'serial' => $this->input->get('q')
            ];
        }

        echo json_encode([
            'results' => $this->equipamento->pesquisar_eqp_iscas([], 0, 10, 'id', 'asc', 'serial as text, serial as id', $pesquisa)
        ]);
    }
}
