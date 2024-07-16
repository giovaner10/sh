<?php

date_default_timezone_set('America/Recife');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servico extends CI_Controller {

	public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('ordem_servico');
		$this->load->model('contrato');
		$this->load->model('usuario_gestor');
		$this->load->model('log_veiculo');
		$this->load->model('equipamento');
		$this->load->model('instalador');
		$this->load->model('mapa_calor');
		$this->load->helper('date');
		$this->load->helper(array('form', 'url'));
		$this->load->library('upload');
		$this->load->library('pagination');
		$this->load->helper('download');
	}

	public function index() {

		$this->mapa_calor->registrar_acessos_url(site_url('/servico/index'));
		
		$para_view['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$para_view['titulo'] = lang('ordem_de_servicos');

		$this->load->view('new_views/fix/header', $para_view);
		$this->load->view('servicos/index');
		$this->load->view('fix/footer_NS');
	}

	public function load_os($number=false) {
		if ($number)
			$where = array('os.status' => $number);
		else
			$where = NULL;

		$draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $search = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)
        }

        $dados = array();
        $infos = $this->ordem_servico->get_ordens($number, $start, $limit, $search);
        $total_filter = $this->ordem_servico->countAll_filter($where, $search);
        foreach ($infos as $os) {
            // VERIFICA TIPO OS //
            switch ($os->tipo_os) {
                case 2:
                    $tipo = 'Manutenção';
                    break;
                case 3:
                    $tipo = 'Troca';
                    break;
                case 4:
                    $tipo = 'Retirada';
                    break;
                default:
                    $tipo = 'Instalação';
                    break;
            }
            // VERIFICA STATUS DA OS //
            switch ($os->status) {
                case 1:
                    $status = 'Fechada';
                    break;
                case 2:
                    $status = 'Estornada';
                    break;
                default:
                    $status = 'Cadastrado';
                    break;
            }

            if ($number == 3) {
                // SE LISTAR TODOS //
                $link = site_url('servico/imprimir_os/'.$os->id.'/'.$os->id_contrato.'/'.$os->tipo_os);
                $dados['data'][] = array(
                    $os->id,
                    $tipo,
                    $os->nome_cliente,
                    $os->id_contrato,
                    $os->placa,
					$os->data_cadastro,
					$os->nome_user,
                    $status,
                    '<a href="'.$link.'" target="_blank" title="Imprimir"><i class="fa fa-print"></i></a>'
                );
            } elseif ($number == 0) {
                // SE LISTAR OS ABERTAS //
                $link = site_url('servico/lista_equipamentos/'.$os->id);
                $dados['data'][] = array(
                    $os->id,
                    $tipo,
                    $os->nome_cliente,
                    $os->id_contrato,
                    $os->placa,
					$os->data_cadastro,
					$os->nome_user,
                    $status,
                    '<a class="btn-warning" href="'.$link.'" target="_blank" title="Administrar"><i class="fa fa-print"></i></a> <a class="btn-primary" href="'. site_url('servico/imprimir_os/'.$os->id.'/'.$os->id_contrato.'/'.$os->tipo_os) .'" target="_blank" title="Imprimir"><i class="fa fa-print"></i></a>'
                );
            } elseif ($number == 1) {
            	// SE LISTAR FECHADAS //
                $link = site_url('servico/visualizar_os/'.$os->id);
                $dados['data'][] = array(
                    $os->id,
                    $tipo,
                    $os->nome_cliente,
                    $os->id_contrato,
                    $os->placa,
					$os->data_cadastro,
					$os->nome_user,
                    $status,
                    '<a href="'.$link.'" target="_blank" title="Visualizar"><i class="icon-eye-open"></i></a>'
                );
            }
        }

        $dados['draw'] = $draw+1;
        $dados['recordsTotal'] = $this->ordem_servico->get_totAll($where);
        $dados['recordsFiltered'] = $total_filter;

        echo json_encode($dados);
	}
	
	public function ajax_load_os($number = false) {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		if ($number)
			$where = array('os.status' => $number);
		else
			$where = NULL;
		

		$ordens_de_servico = $this->ordem_servico->get_os_datatable($number);
		
		$data = array();
		foreach($ordens_de_servico as $os){
			switch ($os->tipo_os) {
                case 2:
                    $tipo = 'Manutenção';
                    break;
                case 3:
                    $tipo = 'Troca';
                    break;
                case 4:
                    $tipo = 'Retirada';
                    break;
                default:
                    $tipo = 'Instalação';
                    break;
            }
            // VERIFICA STATUS DA OS //
            switch ($os->status) {
                case 1:
                    $status = 'Fechada';
                    break;
                case 2:
                    $status = 'Estornada';
                    break;
                default:
                    $status = 'Aberta';
                    break;
            }
			$link_aux = "";
			if ($os->status == 1) {
				$link = site_url('servico/visualizar_os/'.$os->id);

				$link_aux = '<a href="'.$link.'" target="_blank" title="Visualizar"><i class="fa fa-eye"></i></a>';
			}else{
				$link = site_url('servico/lista_equipamentos/'.$os->id);
		
				$link_aux = '<a class="btn-warning" href="'.$link.'" target="_blank" title="Administrar"><i class="fa fa-edit"></i></a> <a class="btn-primary" href="'. site_url('servico/imprimir_os/'.$os->id.'/'.$os->id_contrato.'/'.$os->tipo_os) .'" target="_blank" title="Imprimir"><i class="fa fa-print"></i></a>';
			}

			$row = array();
			$row[] = $os->id;
			$row[] = $tipo;
			$row[] = $os->nome_cliente;
			$row[] = $os->id_contrato;
			$row[] = $os->placa;
			$row[] = $os->data_cadastro;
			$row[] = $os->nome_user;
			$row[] = $status;
			$row[] = $link_aux;
			
			$data[] = $row;

		}
		
		$json = array(
			"draw" => $this->input->post("draw"),
			"recordsTotal" => $this->ordem_servico->records_total($number),
			"recordsFiltered" => $this->ordem_servico->records_filtered($number),
			"data" => $data,
		);
		echo json_encode($json);
	}

	public function ajax_load_os_novo() {
		$number = $this->input->post('listarOS');
		$placa = $this->input->post('placa');
		$startRow = $this->input->post('startRow');  // início da página
		$endRow = $this->input->post('endRow'); // fim da página
	
		if ($number == "") {
			$number = false;
		}

		if ($placa == "") {
			$placa = false;
		}
	
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
	
		$ordens_de_servico = $this->ordem_servico->get_os_datatable_novo($number, $startRow, $endRow, $placa);
	
		$data = array();
		foreach ($ordens_de_servico as $os) {
			$tipo = $this->resolveTipoOs($os->tipo_os);
			$status = $this->resolveStatusOs($os->status);
			$actions = $this->generateActions($os);
	
			$row = array(
				'conta_id' => $os->id,
				'tipo' => $tipo,
				'nome_cliente' => $os->nome_cliente,
				'id_contrato' => $os->id_contrato,
				'placa' => $os->placa,
				'data_cadastro' => $os->data_cadastro,
				'nome_user' => $os->nome_user,
				'status' => $status,
				'actions' => $actions
			);
	
			$data[] = $row;
		}
	
		$json = array(
			"success" => true,
			"rows" => $data,
			"lastRow" => $this->ordem_servico->records_total($number, $placa),
		);
		echo json_encode($json);
	}
	
	private function resolveTipoOs($tipo) {
		switch ($tipo) {
			case 2: return 'Manutenção';
			case 3: return 'Troca';
			case 4: return 'Retirada';
			default: return 'Instalação';
		}
	}
	
	private function resolveStatusOs($status) {
		switch ($status) {
			case 1: return 'Fechada';
			case 2: return 'Estornada';
			default: return 'Aberta';
		}
	}
	
	private function generateActions($os) {
		if ($os->status == 1) {
			return array(
				'view' => site_url('servico/visualizar_os/' . $os->id)
			);
		} else {
			return array(
				'edit' => site_url('servico/lista_equipamentos/' . $os->id),
				'print' => site_url('servico/imprimir_os/' . $os->id . '/' . $os->id_contrato . '/' . $os->tipo_os)
			);
		}
	}

	public function os_cliente($page = 0, $id_cliente) {

		$config['base_url'] = site_url('servico/os_cliente');
		$config['total_rows'] = $this->ordem_servico->get_total_ordens(3);
		$config['per_page'] = $config['total_rows'];

		$this->pagination->initialize($config);
		$para_view['ordens'] = $this->ordem_servico->get_ordens_cliente($page, $config['per_page'], 2, $id_cliente);
		$para_view['titulo'] = 'Show Tecnologia';

		$this->load->view('servicos/os_cliente', $para_view);
	}

    public function os_cliente_new($id_cliente) {
        $dados = array();
        $os = $this->ordem_servico->get_ordens_cliente_new($id_cliente);
        $para_view['titulo'] = 'Show Tecnologia';
        if ($os){
            foreach ($os as $dado){
                $dados[] = array(
                    $dado->id,
                    $dado->tipo_os == 1 ? 'Instalacao' : ($dado->tipo_os == 2 ? 'Manutencao' : ($dado->tipo_os == 3 ? 'Troca' : 'Retirada')),
                    $dado->nome_cliente,
                    $dado->quantidade_equipamentos,
                    data_for_humans($dado->data_cadastro),
                    $dado->nome_usuario,
                    $dado->status == 0 ? 'Cadastrado' :  'Fechado',
                    '<a href="'.site_url('servico/imprimir_os/'.$dado->id.'/'.$dado->id_contrato.'/'.$dado->tipo_os).'" target="_blank" class="btn btn-primary" title="Imprimir"><i class=" fa fa-print"></i></a>'
                );
            }
        }
        echo json_encode(array('data' => $dados));
    }


	public function os_abertas() {
		$para_view['titulo'] = 'Show Tecnologia';
		$this->load->view('fix/header', $para_view);
		$this->load->view('servicos/os_abertas');
		$this->load->view('fix/footer');
	}

	public function os_fechadas() {
		$para_view['titulo'] = 'Show Tecnologia';
		$this->load->view('fix/header', $para_view);
		$this->load->view('servicos/os_fechadas');
		$this->load->view('fix/footer');
	}

	public function visualizar_os($os) {
		$this->auth->is_allowed('downloads_os');
		$caminho = $this->ordem_servico->visualizar_os($os);
		redirect(base_url('uploads/os/'.$caminho));
	}

	public function lista_equipamentos($id) {
		$para_view['titulo'] = 'Show Tecnologia';
		$os = $this->ordem_servico->get_os($id);
		if($os) {
			if($os[0]->data_cadastro > '2015-03-13 11:00:00')
				$para_view['os'] = $this->ordem_servico->get_ordem_id($id);
			else
				$para_view['os'] = $this->ordem_servico->get_ordem_id($id, $os[0]->data_cadastro);
		}

		$para_view['tecnicos'] = $this->instalador->get_lista_instaladores();
		$para_view['clientes'] = $this->ordem_servico->get_cliente_ordem_id($id);
		$para_view['equipamentos'] = $this->ordem_servico->get_equipamentos_fechar($id);

		$this->load->view('fix/header', $para_view);
		$this->load->view('servicos/lista_equipamentos');
		$this->load->view('fix/footer');
	}

	public function troca_tec($id_os) {
	    $id_tec = $this->input->post('tecnico');

	    if ($id_os && $id_tec) {
	        $update = $this->ordem_servico->trocaTecById($id_os, $id_tec);
	        if ($update) {
                $this->session->set_flashdata('sucesso', 'Substituição realizada com sucesso.');
            } else {
                $this->session->set_flashdata('erro', 'Não foi possível realizar a substituição dos técnicos.');
            }

            redirect('servico/lista_equipamentos/'.$id_os);
        }

        $this->session->set_flashdata('erro', 'Não foi possível realizar a substituição dos técnicos.');
        redirect('servico/lista_equipamentos/'.$id_os);
	}

	public function troca_placa($id_os) {
	    $placa = $this->input->post('placa');

	    if ($id_os && $placa) {
	        $update = $this->ordem_servico->trocaPlaca($id_os, $placa);
	        if ($update) {
                $this->session->set_flashdata('sucesso', 'Substituição realizada com sucesso.');
            } else {
                $this->session->set_flashdata('erro', 'Não foi possível realizar a substituição da placa.');
            }

            redirect('servico/lista_equipamentos/'.$id_os);
        }

        $this->session->set_flashdata('erro', 'Não foi possível realizar a substituição da placa.');
        redirect('servico/lista_equipamentos/'.$id_os);
    }

	public function instalacao_old($page = 0) {
		if($this->input->get()) {

			$para_view['titulo'] = 'Show Tecnologia';
			$para_view['contratos'] =  $this->ordem_servico->listar_pesquisa_contratos($this->input->get(), 0);

			$this->load->view('fix/header', $para_view);
			$this->load->view('servicos/instalacao');
			$this->load->view('fix/footer');

		} else {
			$config['base_url'] = site_url('servico/instalacao');
			$config['total_rows'] = $this->ordem_servico->get_total_contratos(0);
			$config['per_page'] = 15;

			$this->pagination->initialize($config);

			$para_view['titulo'] = 'Show Tecnologia';
			$para_view['contratos'] = $this->ordem_servico->get_contratos($page, $config['per_page'], 0);

			$this->load->view('fix/header', $para_view);
			$this->load->view('servicos/instalacao');
			$this->load->view('fix/footer');
		}
	}

	public function instalacao() {
		$para_view['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$para_view['titulo'] = 'OS - ' . lang('instalacao');

		$this->load->view('new_views/fix/header', $para_view);
		$this->load->view('servicos/instalacao_new');
		$this->load->view('fix/footer_NS');
	}

	public function get_contratos_instalacao() {
		$startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');
		$coluna = $this->input->post('coluna');
		$id = $this->input->post('id');
		$nome = $this->input->post('nome');

		$limit = $endRow - $startRow;
		$offset = $startRow;
		if ($coluna) {
			$pesquisa = array(
				"coluna" => $coluna,
				"palavra" => $id ? $id : $nome
			);
			$result =  $this->ordem_servico->get_contratos_paginado($limit, $offset, 0, $pesquisa);
		} else {
			$result =  $this->ordem_servico->get_contratos_paginado($limit, $offset, 0);
		}

		if ($result) {
			if ($result["success"]) {
				echo json_encode(
					array(
						"statusCode" => 200,
						"success" => true,
						"rows" => $result['rows'],
						"lastRow" => $result['lastRow'],
					)
				);
			} else {
				echo json_encode(
					array(
						"statusCode" => 404,
						"success" => false,
						"rows" => $result['rows'],
						"lastRow" => $result['lastRow'],
						"message" => 'Não foi possível encontrar contratos.'
					)
				);
			}
		} else {
			echo json_encode(
				array(
					"statusCode" => 500,
					"success" => false,
					"message" => 'Erro ao realizar a listagem'
				)
			);
		}
		
	}

	public function manutencao_troca_retirada() {
		$para_view['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$para_view['titulo'] = 'OS - ' . lang('manutencao_troca_retirada');

		$this->load->view('new_views/fix/header', $para_view);
		$this->load->view('servicos/manutencao_troca_retirada_new');
		$this->load->view('fix/footer_NS');
	}

	public function get_contratos_manutencao_troca_retirada() {
		$startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');
		$coluna = $this->input->post('coluna');
		$id = $this->input->post('id');
		$nome = $this->input->post('nome');

		$limit = $endRow - $startRow;
		$offset = $startRow;
		if ($coluna) {
			$pesquisa = array(
				"coluna" => $coluna,
				"palavra" => $id ? $id : $nome
			);
			$result =  $this->ordem_servico->get_contratos_paginado($limit, $offset, 1, $pesquisa);
		} else {
			$result =  $this->ordem_servico->get_contratos_paginado($limit, $offset, 1);
		}

		if ($result) {
			if ($result["success"]) {
				echo json_encode(
					array(
						"statusCode" => 200,
						"success" => true,
						"rows" => $result['rows'],
						"lastRow" => $result['lastRow'],
					)
				);
			} else {
				echo json_encode(
					array(
						"statusCode" => 404,
						"success" => false,
						"rows" => $result['rows'],
						"lastRow" => $result['lastRow'],
						"message" => 'Não foi possível encontrar contratos.'
					)
				);
			}
		} else {
			echo json_encode(
				array(
					"statusCode" => 500,
					"success" => false,
					"message" => 'Erro ao realizar a listagem'
				)
			);
		}
		
	}

	public function manutencao_troca_retirada_old($page = 0) {
		if($this->input->get()){

			$para_view['titulo'] = 'Show Tecnologia';
			$para_view['contratos'] =  $this->ordem_servico->listar_pesquisa_contratos($this->input->get(), 1);

			$this->load->view('fix/header', $para_view);
			$this->load->view('servicos/manutencao_troca_retirada');
			$this->load->view('fix/footer');

		}else{
			$config['base_url'] = site_url('servico/manutencao_troca_retirada');
			$config['total_rows'] = $this->ordem_servico->get_total_contratos(1);
			$config['per_page'] = 15;

			$this->pagination->initialize($config);

			$para_view['titulo'] = 'Show Tecnologia';
			$para_view['contratos'] = $this->ordem_servico->get_contratos($page, $config['per_page'], 1);

			$this->load->view('fix/header', $para_view);
			$this->load->view('servicos/manutencao_troca_retirada');
			$this->load->view('fix/footer');
		}
	}

	public function gerar_instalacao($id_contrato,$id_cliente,$quant_veiculos) {
		$calc = 99999999999;
		$valor = 9999999999;
		$inst = 0;
		$confirma_cidade = false;
		$cliente = $this->ordem_servico->get_cliente($id_cliente);
		$endereco_cliente = str_replace(" ", "+", $cliente[0]->endereco).'+'.$cliente[0]->numero.'+'.str_replace(" ", "+", $cliente[0]->bairro).'+'.$cliente[0]->cep;

		$instaladores = $this->ordem_servico->get_instaladores();
		$instalador = array(); $id_inst = array();
		if (count($instaladores) > 0) {
			foreach ($instaladores as $inst) {
				if ($inst->block == 0)
					$instalador[] = $inst->nome . " " . $inst->sobrenome; $id_inst[] = $inst->id;
			}
		}

		$para_view['instalador']   = json_encode($instalador);
		$para_view['id_inst']   = json_encode($id_inst);
		$para_view['titulo'] = 'Show Tecnologia';
		$para_view['id_contrato'] = $id_contrato;
		$para_view['dados_cliente'] = $cliente;
		$para_view['instaladores'] = $inst;
		$para_view['usuarios'] = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
		$para_view['placas'] = $this->contrato->listar_placas_os($id_contrato);
		$para_view['status_pg'] = 0;


		if (!$equip_os_instalacao = $this->ordem_servico->equipamentos_os_instalacao($id_contrato)) {
			$equip_os_instalacao = 0;
		}

		$para_view['max_veiculos'] = $quant_veiculos - $equip_os_instalacao;
		$para_view['equip_os_instalacao'] = $equip_os_instalacao;
		$para_view['quant_veiculos'] = $quant_veiculos;
		$this->mapa_calor->registrar_acessos_url(site_url('/servico'));
		$this->load->view('fix/header', $para_view);
		$this->load->view('servicos/gerar_instalacao');
		$this->load->view('fix/footer');
	}

	public function gerar_manutencao($id_contrato, $id_cliente, $quant_veiculos) {
		$calc = 99999999999;
		$valor = 9999999999;
		$inst = 0;
		$confirma_cidade = false;
		$cliente = $this->ordem_servico->get_cliente($id_cliente);
		$endereco_cliente = str_replace(" ", "+", $cliente[0]->endereco).'+'.$cliente[0]->numero.'+'.str_replace(" ", "+", $cliente[0]->bairro).'+'.$cliente[0]->cep;
		$instaladores = $this->ordem_servico->get_instaladores();
		$instalador = array(); $id_inst = array();
		if (count($instaladores) > 0){
			foreach ($instaladores as $inst){
				if ($inst->block == 0)
					$instalador[] = $inst->nome . " " . $inst->sobrenome; $id_inst[] = $inst->id;
			}
		}
		$para_view['instalador']   = json_encode($instalador);
		$para_view['id_inst']   = json_encode($id_inst);

		$para_view['titulo'] = 'Show Tecnologia';
		$para_view['id_contrato'] = $id_contrato;
		$para_view['quant_veiculos'] = $quant_veiculos;
		$para_view['dados_cliente'] = $cliente;
		$para_view['instaladores'] = $inst;
		//$para_view['equipamentos'] = $this->ordem_servico->get_equipamentos_disponiveis_json();
		$para_view['usuarios'] = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
		$para_view['placas'] = $this->contrato->listar_placas_os($id_contrato);

		$this->load->view('fix/header', $para_view);
		$this->load->view('servicos/gerar_manutencao');
		$this->load->view('fix/footer');
	}

	public function gerar_troca($id_contrato,$id_cliente,$quant_veiculos) {
		$calc = 99999999999;
		$valor = 9999999999;
		$inst = 0;
		$confirma_cidade = false;
		$cliente = $this->ordem_servico->get_cliente($id_cliente);
		$endereco_cliente = str_replace(" ", "+", $cliente[0]->endereco).'+'.$cliente[0]->numero.'+'.str_replace(" ", "+", $cliente[0]->bairro).'+'.$cliente[0]->cep;
		$instaladores = $this->ordem_servico->get_instaladores();
		$instalador = array(); $id_inst = array();
		if (count($instaladores) > 0){
			foreach ($instaladores as $inst){
				if ($inst->block == 0)
					$instalador[] = $inst->nome . " " . $inst->sobrenome; $id_inst[] = $inst->id;
			}
		}
		$para_view['instalador']   = json_encode($instalador);
		$para_view['id_inst']   = json_encode($id_inst);

		$para_view['titulo'] = 'Show Tecnologia';
		$para_view['id_contrato'] = $id_contrato;
		$para_view['titulo'] = 'Show Tecnologia';
		$para_view['quant_veiculos'] = $quant_veiculos;
		$para_view['dados_cliente'] = $cliente;
		$para_view['instaladores'] = $inst;
		$para_view['equipamentos'] = $this->ordem_servico->get_equipamentos_disponiveis_json();
		$para_view['usuarios'] = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
		$para_view['placas'] = $this->contrato->listar_placas_os($id_contrato);

		$this->load->view('fix/header', $para_view);
		$this->load->view('servicos/gerar_troca');
		$this->load->view('fix/footer');
	}

	public function gerar_retirada($id_contrato, $id_cliente, $quant_veiculos = 0) {
		//$inst = 0;		
		$cliente = $this->ordem_servico->get_cliente($id_cliente);
		//$instaladores = $this->ordem_servico->get_instaladores();
		//$instalador = array(); 
		//$id_inst = array();
		
		// if (count($instaladores) > 0){
		// 	foreach ($instaladores as $inst){
		// 		if ($inst->block == 0){
		// 			$instalador[] = $inst->nome . " " . $inst->sobrenome;
		// 			$id_inst[] = $inst->id;
		// 		}	
		// 	}
		// }
				
		//$para_view['instalador']   = json_encode($instalador);
		//$para_view['id_inst']   = json_encode($id_inst);

		$para_view['titulo'] = 'Show Tecnologia';
		$para_view['id_contrato'] = $id_contrato;
		$para_view['quant_veiculos'] = $quant_veiculos;
		$para_view['dados_cliente'] = $cliente;
		//$para_view['instaladores'] = $inst;
		//$para_view['equipamentos'] = $this->ordem_servico->get_equipamentos_disponiveis_json();
		$para_view['usuarios'] = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
		$para_view['placas'] = $this->contrato->listar_placas_os($id_contrato);
		//dd($para_view['equipamentos']);
		$this->load->view('fix/header4', $para_view);
		$this->load->view('servicos/gerar_retirada');
		$this->load->view('fix/footer4');
	}

	public function gerar_ordem_servico_velha($tipo, $id_contrato, $id_cliente, $quant_veiculos = 0) {

		if($tipo == 1){
			$para_view['subTitle'] = 'OS Instalação - Contrato ';
		}elseif($tipo == 2){
			$para_view['subTitle'] = 'OS Troca - Contrato ';
		}elseif($tipo == 3){
			$para_view['subTitle'] = 'OS Retirada - Contrato ';
		}elseif($tipo == 4){
			$para_view['subTitle'] = 'OS Manutenção - Contrato ';
		}		
		$cliente = $this->ordem_servico->get_cliente($id_cliente);
		$para_view['tipoOS'] = $tipo;
		$para_view['titulo'] = 'Show Tecnologia';
		$para_view['id_contrato'] = $id_contrato;
		$para_view['quant_veiculos'] = $quant_veiculos;
		$para_view['dados_cliente'] = $cliente;		
		$para_view['usuarios'] = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
		$para_view['placas'] = $this->contrato->listar_placas_os($id_contrato);
		
		$this->load->view('fix/header4', $para_view);
		$this->load->view('servicos/gerar_retirada');
		$this->load->view('fix/footer4');
	}

	public function gerar_ordem_servico($tipo, $id_contrato, $id_cliente, $quant_veiculos = 0) {

		if($tipo == 1){
			$para_view['subTitle'] = 'OS Instalação - Contrato ';
		}elseif($tipo == 2){
			$para_view['subTitle'] = 'OS Troca - Contrato ';
		}elseif($tipo == 3){
			$para_view['subTitle'] = 'OS Retirada - Contrato ';
		}elseif($tipo == 4){
			$para_view['subTitle'] = 'OS Manutenção - Contrato ';
		}		
		$cliente = $this->ordem_servico->get_cliente($id_cliente);
		$para_view['tipoOS'] = $tipo;
		$para_view['titulo'] = 'Show Tecnologia';
		$para_view['id_contrato'] = $id_contrato;
		$para_view['quant_veiculos'] = $quant_veiculos;
		$para_view['dados_cliente'] = $cliente;		
		$para_view['usuarios'] = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
		$para_view['placas'] = $this->contrato->listar_placas_os($id_contrato);

		$para_view['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		
		$this->load->view('new_views/fix/header', $para_view);
		$this->load->view('servicos/gerar_retirada_nova', $para_view);
		$this->load->view('fix/footer_NS');
	}

	public function getPlacasByContrato(){
		$like = NULL;
		$id_contrato = $this->input->get('contrato');
        if ($search = $this->input->get('q'))
            $like = array('placa' => $search);

        echo json_encode(array('results' => $this->contrato->listar_placas_contrato(array(), 0, 10, 'placa', 'asc', 'placa as text, placa as id', $like, $id_contrato)));
	}
	
	public function get_instaladores() {
		if (isset($_GET['term'])){
			$q = strtolower($_GET['term']);
			$this->instalador->get_json_instaladores($q);
		}
	}

	public function imprimir_os($id, $id_contrato, $tipo_os)
	{
		$para_view['titulo'] = 'Show Tecnologia';
		$os = $this->ordem_servico->get_os($id);
		if($os) {
			if($os[0]->data_cadastro > '2015-03-13 11:00:00')
				$para_view['ordens'] = $this->ordem_servico->get_ordem_id($id);
			else
				$para_view['ordens'] = $this->ordem_servico->get_ordem_id($id, $os[0]->data_cadastro);
		}
		$para_view['usuarios'] = $this->ordem_servico->get_usuario($os[0]->id_usuario);
		$para_view['clientes'] = $this->ordem_servico->get_cliente_ordem_id($id);
		$para_view['veiculos'] = $this->ordem_servico->get_equipamentos_os($id);

		if ($tipo_os == 1) {
			$para_view['contratos'] = $this->ordem_servico->contratos_id($id_contrato);
			$this->load->view('servicos/imprimir_os_inst' , $para_view);
		}else{
			$this->load->view('servicos/imprimir_os' , $para_view);
		}

	}

	public function instalado_modulos()
	{
		$acao = 1;
		$id_osequipamentos = $this->input->post('idosequipamentos');
		$id_os = $this->input->post('idos');
		$modulo = $this->input->post('modulo');
		$id_cliente = $this->input->post('cliente');
		$contrato = $this->input->post('contrato');
		$serial = $this->input->post('serial');
		$placa = trim($this->input->post('placa'));

		if (strlen($placa) == 8) {
			$nova_placa = strtoupper($placa);

			if ($resultado = $this->ordem_servico->verificar_placa($nova_placa, $contrato, $id_cliente, $modulo)) {

				if ($resultado['status'] == 'erro') {

					$this->session->set_flashdata('erro', $resultado['msg']);
					redirect('servico/lista_equipamentos/'.$id_os);

				}else{

					if($this->ordem_servico->instalado_modulos($id_osequipamentos,$id_os,$modulo,$id_cliente,
						$acao,$contrato,$nova_placa)){

						$this->session->set_flashdata('sucesso', 'A placa '.$nova_placa.' foi relacionada ao modulo '.$serial.'!');
							redirect('servico/lista_equipamentos/'.$id_os);

					}else{

						$this->session->set_flashdata('erro', 'ERRO - Erro ao adicionar a placa!');
						redirect('servico/lista_equipamentos/'.$id_os);
					}

				}

			} else {
				$this->session->set_flashdata('erro', 'ERRO - Erro ao adicionar a placa!');
				redirect('servico/lista_equipamentos/'.$id_os);
			}
		} else {
			$this->session->set_flashdata('erro', 'ERRO - Placa inválida!');
			redirect('servico/lista_equipamentos/'.$id_os);
		}


	}

	public function digitalizacao_os($idos) {
		$nome_arquivo = "";
		$numero_os = $this->input->post('numero_os');
		$cliente = $this->input->post('cliente');
		$arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;
		$id_os = $idos;

		//VERIFICA WEEKLY DA OS
		$verifica = $this->ordem_servico->verifyWeekly($id_os);
		$weekly = $verifica->weekly;
		if ($arquivo) {
			if ($dados = $this->upload()) {
				$nome_arquivo = $dados['file_name'];
				$arquivo_enviado = true;
			}

			if($arquivo && !$arquivo_enviado) {
				$this->session->set_flashdata('erro', 'Erro ao enviar o arquivo!');
				redirect('servico/lista_equipamentos/'.$id_os);
			}else if($this->ordem_servico->digitalizacao_os($numero_os,$cliente,$nome_arquivo, $weekly)){
				$this->session->set_flashdata('sucesso', 'A OS '.$numero_os.' foi fechada!');
				redirect('servico/index');
			}else{
				$this->session->set_flashdata('erro', 'OS não fechada');
				redirect('servico/lista_equipamentos/'.$id_os);
			}

		} else {
			$this->session->set_flashdata('erro', 'Erro ao enviar o arquivo!');
			redirect('servico/lista_equipamentos/'.$id_os);
		}
	}

	private function upload() {
		$config['upload_path'] = './uploads/os';
		$config['allowed_types'] = '*';
		$config['max_size']	= '0';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$config['encrypt_name']  = 'true';

		$this->upload->initialize($config);
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('arquivo')) {
			$data = $this->upload->data();
			return $data;
		}
		return false;
	}

	public function devolver_modulos_instalador($id_os,$modulo,$id_instalador,$serial)
	{
		$status = 4;

		if($this->ordem_servico->devolver_modulos($id_os,$modulo,$id_instalador,$status)){
			$this->session->set_flashdata('sucesso', 'O módulo '.$serial.' foi devolvido ao instalador!');
			redirect('servico/lista_equipamentos/'.$id_os);

		}else{
			$this->session->set_flashdata('erro', 'ERRO - Erro ao devolver o módulo ao instalador!');
			redirect('servico/lista_equipamentos/'.$id_os);
		}

	}

	public function devolver_modulos_empresa($id_os,$modulo,$id_instalador,$serial)
	{
		$status = 1;

		if($this->ordem_servico->devolver_modulos($id_os,$modulo,$id_instalador,$status)){
			$this->session->set_flashdata('sucesso', 'O módulo '.$serial.' foi devolvido a empresa!');
			redirect('servico/lista_equipamentos/'.$id_os);

		}else{
			$this->session->set_flashdata('erro', 'ERRO - Erro ao devolver o módulo a empresa!');
			redirect('servico/lista_equipamentos/'.$id_os);
		}

	}

	public function devolver_modulos_teste($id_os,$modulo,$id_instalador,$serial)
	{
		$status = 2;

		if($this->ordem_servico->devolver_modulos($id_os,$modulo,$id_instalador,$status)){
			$this->session->set_flashdata('sucesso', 'O módulo '.$serial.' foi devolvido a teste!');
			redirect('servico/lista_equipamentos/'.$id_os);

		}else{
			$this->session->set_flashdata('erro', 'ERRO - Erro ao devolver o módulo a teste!');
			redirect('servico/lista_equipamentos/'.$id_os);
		}

	}	

	public function liberar_equip() {
		$serial = $this->input->post('serial');

		if ($this->ordem_servico->liberar_equipamento($serial)) {
			die(json_encode(array('success' => true, 'mensagem' => "$serial Liberado com sucesso!")));
		}else{
			die(json_encode(array('success' => false, 'mensagem' => "Erro ao liberar o serial: $serial")));
		}

	}

	public function gerar_os_instalacao($id_cont, $id_cli) {
		$tipo_servico = "";
		if($this->input->post('bloqueio')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('panico')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('identificador')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico=intval($tipo_servico);
		//$this->load->model('instalador');
		$usuario_email = $this->auth->get_login('admin', 'email');
		$data_solicitacao = $this->input->post('data_solicitacao');
		$data_inicial = $this->input->post('data_inicial');
		$data_final = $this->input->post('data_final');
		//$veiculo = $this->input->post('veiculo');
		$quant_veic =  1; # SÓ É PERMITIDO REALIZAR O VINCULO DE 1 VEICULO POR OS
		//$nameTec = $this->input->post('instalador');
		//$idTec = $this->instalador->busca_id($nameTec);
		$placa = $this->input->post('placa');
		$serial = $this->input->post('serial');
		$idTec = $this->input->post('instalador');
		$veiculo = array("placa"=> $placa, "serial"=>$serial);

		for ($i = 0; $i < $quant_veic; $i++) {
			
			// $placa = $veiculo['placa'];
			// $serial = $veiculo['serial'];
			$count = $i;

			if(!$this->equipamento->get_equipamentos($serial)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, serial <b>'.$serial.'</b> inválido ou não cadastrado.', 'serial' => false)));
			}elseif(!$this->contrato->listar_pesquisa_placas($placa, $id_cont)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, placa <b>'.$placa.'</b> inválida ou não pertencente ao contrato <b>'.$id_cont.'</b>.', 'serial' => false)));
			}elseif($this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic, $count) && $this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic, $count) ){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> e o serial <b>'.$serial.'</b> mais de uma vez.', 'serial' => false)));
			}elseif($this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic, $count)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> mais de uma vez.', 'serial' => false)));
			}elseif($this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic, $count)) {
                die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou o serial <b>' . $serial . '</b> mais de uma vez.', 'serial' => false)));
            }elseif (!$this->verifica_logistica($serial, $placa)) {
                die(json_encode(array('success' => false, 'mensagem' => 'Ops, lançamento no logistica não localizado, tente novamente mais tarde.', 'serial' => false)));
			}elseif($status = $this->ordem_servico->get_status_equipamentos($serial)){
				switch ($status) {
					case 0:
						die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>BLOQUEADO</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 2:
						die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM TESTE</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 3:
						die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM TRÂNSITO - OS</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 4:
						die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM TRÂNSITO - INSTALADOR</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 5:
						die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM USO</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 6:
						die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM MANUTENÇÂO</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
				}

			}

		}

		$dados_os = array(
			'id_cliente' => $id_cli,
			'id_contrato' => $id_cont,
			'solicitante' => $this->input->post('solicitante'),
			'data_solicitacao' => dh_for_unix($data_solicitacao, false),
			'contato' => $this->input->post('contato'),
			'telefone' => $this->input->post('telefone'),
			'endereco_destino' => $this->input->post('endereco_destino'),
			'tipo_os' => 1,
			'quantidade_equipamentos' => $quant_veic,
			'data_inicial' => dh_for_unix($data_inicial, false),
			'hora_inicial' => $this->input->post('hora_inicial'),
			'data_final' => dh_for_unix($data_final, false),
			'hora_final' => $this->input->post('hora_final'),
			'id_instalador' => $idTec,
			'observacoes' => $this->input->post('observacoes'),
			'status' => 0,
			'data_cadastro' => date('Y-m-d H:i:s'),
			'id_usuario' => $this->input->post('usuario'),
			'tipo_servico'=>$tipo_servico,
			'id_user' => $this->auth->get_login_dados('user')
		);

		if($id_os = $this->ordem_servico->gerar_os_instalacao($dados_os, $veiculo)){
			$acao = array(
				'data_criacao' => date('Y-m-d H:i:s'),
				'usuario' => $usuario_email,
				'placa' => $placa,
				'acao' => 'O usuário '.$usuario_email.' gerou uma OS de Instalação'
			);
			$ret = $this->log_veiculo->add($acao);
			die(json_encode(array('success' => true, 'mensagem' => 'OS de Instalação gerada com sucesso!',
			 'id_os' => $id_os, 'id_contrato' => $id_cont, 'tipo_os' => 1, 'serial' => false)));
		}else{
			die(json_encode(array('success' => false, 'mensagem' => 'ERRO - OS não foi gerada!')));
		}

	}

	function verifica_logistica($serial, $placa) {
	    return $this->db->where(array('log.placaOrdem' => $placa, 'cad.serial' => $serial))
            ->join('showtecsystem.cad_equipamentos as cad', 'cad.id = log.equipamento', 'INNER')
            ->get('showtecsystem.cad_logistica as log')->row();
    }

	public function gerar_os_manutencao($id_cont, $id_cli) {
		$tipo_servico = "";
		if($this->input->post('bloqueio')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('panico')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('identificador')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico=intval($tipo_servico);
		$usuario_email = $this->auth->get_login('admin', 'email');
		$data_solicitacao = $this->input->post('data_solicitacao');
		$data_inicial = $this->input->post('data_inicial');
		$data_final = $this->input->post('data_final');
		//$veiculo = $this->input->post('veiculo');
		$quant_veic =  1; # SÓ É PERMITIDO REALIZAR O VINCULO DE 1 VEICULO POR OS
		//$nameTec = $this->input->post('instalador');
		//$idTec = $this->instalador->busca_id($nameTec);
		$serial_ret = $this->input->post('serial_retirado');

		$placa = $this->input->post('placa');
		$serial = $this->input->post('serial');
		$idTec = $this->input->post('instalador');
		$veiculo = array("placa"=> $placa, "serial"=>$serial);

		for ($i = 0; $i < $quant_veic; $i++) {
			// $placa = $veiculo['placa'.$i];
			// $serial = $veiculo['serial'.$i];

			if(!$this->equipamento->get_equipamentos($serial)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, serial <b>'.$serial.'</b> inválido ou não cadastrado.', 'serial' => false)));

			}elseif(!$this->contrato->listar_pesquisa_placas($placa, $id_cont)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, placa <b>'.$placa.'</b> inválida ou não pertencente ao contrato <b>'.$id_cont.'</b>.', 'serial' => false)));

			}elseif( $this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic) && $this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic) ){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> e o serial <b>'.$serial.'</b> mais de uma vez.', 'serial' => false)));

			}elseif($this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> mais de uma vez.', 'serial' => false)));

			}elseif($this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou o serial <b>'.$serial.'</b> mais de uma vez.', 'serial' => false)));

			}elseif($status = $this->ordem_servico->get_status_equipamentos($serial)){

				switch ($status) {
					case 0: die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>BLOQUEADO</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 2: die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM TESTE</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 3: die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM TRÂNSITO - OS</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 4: die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM TRÂNSITO - INSTALADOR</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 5: die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM USO</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
					case 6: die(json_encode(array('success' => false,
						'mensagem' => 'Ops, o serial <b>'.$serial.'</b> encontra-se <b>EM MANUTENÇÂO</b>, para utiliza-lo click em <b>liberar</b>, e depois em enviar.',
						'serial' => $serial))); break;
				}

			}

		}


		$dados_os = array(
			'id_cliente' => $id_cli,
			'id_contrato' => $id_cont,
			'solicitante' => $this->input->post('solicitante'),
			'data_solicitacao' => dh_for_unix($data_solicitacao, false),
			'contato' => $this->input->post('contato'),
			'telefone' => $this->input->post('telefone'),
			'endereco_destino' => $this->input->post('endereco_destino'),
			'tipo_os' => 2,
			'quantidade_equipamentos' => $quant_veic,
			'data_inicial' => dh_for_unix($data_inicial, false),
			'hora_inicial' => $this->input->post('hora_inicial'),
			'data_final' => dh_for_unix($data_final, false),
			'hora_final' => $this->input->post('hora_final'),
			'id_instalador' => $idTec,
			'observacoes' => $this->input->post('observacoes'),
			'status' => 0,
			'data_cadastro' => date('Y-m-d H:i:s'),
			'id_usuario' => $this->input->post('usuario'),
			'tipo_servico' => $tipo_servico,
			'id_user' => $this->auth->get_login_dados('user')
		);

		if($id_os = $this->ordem_servico->gerar_os_manutencao($dados_os, $veiculo, $serial_ret)){
			$acao = array(
				'data_criacao' => date('Y-m-d H:i:s'),
				'usuario' => $usuario_email,
				'placa' => $placa,
				'acao' => 'O usuário '.$usuario_email.' gerou uma OS de Manutenção'
			);
			$ret = $this->log_veiculo->add($acao);
			die(json_encode(array('success' => true, 'mensagem' => 'OS de Manutenção gerada com sucesso!',
			 'id_os' => $id_os, 'id_contrato' => $id_cont, 'tipo_os' => 2, 'serial' => false)));
		} else {
			die(json_encode(array('success' => false, 'mensagem' => 'ERRO - OS não foi gerada!')));
		}

	}

	public function gerar_os_troca($id_cont, $id_cli)
	{
		$tipo_servico = "";
		if($this->input->post('bloqueio')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('panico')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('identificador')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico=intval($tipo_servico);
		$usuario_email = $this->auth->get_login('admin', 'email');
		$data_solicitacao = $this->input->post('data_solicitacao');
		$data_inicial = $this->input->post('data_inicial');
		$data_final = $this->input->post('data_final');
		//$veiculo = $this->input->post('veiculo');
		$quant_veic =  1; # SÓ É PERMITIDO UM VEICULO POR OS
		//$nameTec = $this->input->post('instalador');
		//$idTec = $this->instalador->busca_id($nameTec);
		$placa = $this->input->post('placa');
		$serial = $this->input->post('serial');
		$idTec = $this->input->post('instalador');
		$veiculo = array("placa"=> $placa, "serial"=>$serial);

		for ($i = 0; $i <= $quant_veic-1; $i++) {

			// if ($quant_veic == 1) {
			// 		$placa = $veiculo['placa0'];
			// 		$serial = $veiculo['serial0'];
			// }else{
			// 	$placa = $veiculo['placa'.$i];
			// $serial = $veiculo['serial'.$i];
			// }

			if(!$this->equipamento->get_equipamentos($serial)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, serial <b>'.$serial.'</b> inválido ou não cadastrado.', 'serial' => false)));

			}elseif(!$this->contrato->listar_pesquisa_placas($placa, $id_cont)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, placa <b>'.$placa.'</b> inválida ou não pertencente ao contrato <b>'.$id_cont.'</b>.', 'serial' => false)));

			}elseif( $this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic) && $this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic) ){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> e o serial <b>'.$serial.'</b> mais de uma vez.', 'serial' => false)));

			}elseif($this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> mais de uma vez.', 'serial' => false)));

			}elseif($this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou o serial <b>'.$serial.'</b> mais de uma vez.', 'serial' => false)));

			}

		}

		$dados_os = array(
			'id_cliente' => $id_cli,
			'id_contrato' => $id_cont,
			'solicitante' => $this->input->post('solicitante'),
			'data_solicitacao' => dh_for_unix($data_solicitacao, false),
			'contato' => $this->input->post('contato'),
			'telefone' => $this->input->post('telefone'),
			'endereco_destino' => $this->input->post('endereco_destino'),
			'tipo_os' => 3,
			'quantidade_equipamentos' => $quant_veic,
			'data_inicial' => dh_for_unix($data_inicial, false),
			'hora_inicial' => $this->input->post('hora_inicial'),
			'data_final' => dh_for_unix($data_final, false),
			'hora_final' => $this->input->post('hora_final'),
			'id_instalador' => $idTec,
			'observacoes' => $this->input->post('observacoes'),
			'status' => 0,
			'data_cadastro' => date('Y-m-d H:i:s'),
			'id_usuario' => $this->input->post('usuario'),
			'tipo_servico' => $tipo_servico,
			'id_user' => $this->auth->get_login_dados('user')

		);

		if($id_os = $this->ordem_servico->gerar_os_troca($dados_os, $veiculo)){
			$acao = array(
				'data_criacao' => date('Y-m-d H:i:s'),
				'usuario' => $usuario_email,
				'placa' => $placa,
				'acao' => 'O usuário '.$usuario_email.' gerou uma OS de Troca'
			);
			$ret = $this->log_veiculo->add($acao);
			die(json_encode(array('success' => true, 'mensagem' => 'OS de Troca gerada com sucesso!',
			 'id_os' => $id_os, 'id_contrato' => $id_cont, 'tipo_os' => 2, 'serial' => false)));
		}else{
			die(json_encode(array('success' => false, 'mensagem' => 'ERRO - OS não foi gerada!')));
		}

	}

	public function gerar_os_retirada($id_cont, $id_cli){	
		$tipo_servico = "";
		if($this->input->post('bloqueio')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('panico')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico.='0';
		if($this->input->post('identificador')){
			$tipo_servico.='2';
		}
		else{
			$tipo_servico.='1';
		}
		$tipo_servico=intval($tipo_servico);
		$usuario_email = $this->auth->get_login('admin', 'email');
		$data_solicitacao = $this->input->post('data_solicitacao');
		$data_inicial = $this->input->post('data_inicial');
		$data_final = $this->input->post('data_final');
		//$veiculo = $this->input->post('veiculo');
		$placa = $this->input->post('placa');
		$serial = $this->input->post('serial');
		$quant_veic =  1; # SÓ É PERMITIDO REALIZAR O VINCULO DE 1 VEICULO POR OS
		//$nameTec = $this->input->post('instalador');
		//$idTec = $this->instalador->busca_id($nameTec);
		$idTec = $this->input->post('instalador');
		$veiculo = array("placa"=> $placa, "serial"=>$serial);
		
		for ($i = 0; $i <= $quant_veic-1; $i++) {
			// if ($quant_veic == 1) {
			// 		$placa = $veiculo['placa0'];
			// 		$serial = $veiculo['serial0'];
			// } else {
			// 	$placa = $veiculo['placa'.$i];
			// 	$serial = $veiculo['serial'.$i];
			// }

			if(!$this->equipamento->get_equipamentos($serial)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, serial <b>'.$serial.'</b> inválido ou não cadastrado.', 'serial' => false)));

			}elseif(!$this->contrato->listar_pesquisa_placas($placa, $id_cont)){
				die(json_encode(array('success' => false, 'mensagem' => 'Ops, placa <b>'.$placa.'</b> inválida ou não pertencente ao contrato <b>'.$id_cont.'</b>.', 'serial' => false)));

			}
			
			//elseif( $this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic) && $this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic) ){
			// 	die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> e o serial <b>'.$serial.'</b> mais de uma vez.', 'serial' => false)));

			// }elseif($this->ordem_servico->ocorrencia_placa($veiculo, $placa, $quant_veic)){
			// 	die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou a placa <b>'.$placa.'</b> mais de uma vez.', 'serial' => false)));

			// }elseif($this->ordem_servico->ocorrencia_serial($veiculo, $serial, $quant_veic)){
			// 	die(json_encode(array('success' => false, 'mensagem' => 'Ops, você digitou o serial <b>'.$serial.'</b> mais de uma vez.', 'serial' => false)));

			// }

		}

		$dados_os = array(
			'id_cliente' => $id_cli,
			'id_contrato' => $id_cont,
			'solicitante' => $this->input->post('solicitante'),
			'data_solicitacao' => dh_for_unix($data_solicitacao, false),
			'contato' => $this->input->post('contato'),
			'telefone' => $this->input->post('telefone'),
			'endereco_destino' => $this->input->post('endereco_destino'),
			'tipo_os' => 4,
			'quantidade_equipamentos' => $quant_veic,
			'data_inicial' => dh_for_unix($data_inicial, false),
			'hora_inicial' => $this->input->post('hora_inicial'),
			'data_final' => dh_for_unix($data_final, false),
			'hora_final' => $this->input->post('hora_final'),
			'id_instalador' => $idTec,
			'observacoes' => $this->input->post('observacoes'),
			'status' => 0,
			'data_cadastro' => date('Y-m-d H:i:s'),
			'id_usuario' => $this->input->post('usuario'),
			'tipo_servico' => $tipo_servico,
			'id_user' => $this->auth->get_login_dados('user')

		);

		if($id_os = $this->ordem_servico->gerar_os_retirada($dados_os, $veiculo)){
			$acao = array(
				'data_criacao' => date('Y-m-d H:i:s'),
				'usuario' => $usuario_email,
				'placa' => $placa,
				'acao' => 'O usuário '.$usuario_email.' gerou uma OS de Retirada'
			);
			$ret = $this->log_veiculo->add($acao);
			die(json_encode(array('success' => true, 'mensagem' => 'OS de Retirada gerada com sucesso!',
			 'id_os' => $id_os, 'id_contrato' => $id_cont, 'tipo_os' => 2, 'serial' => false)));
		}else{
			die(json_encode(array('success' => false, 'mensagem' => 'ERRO - OS não foi gerada!')));
		}

	}

	public function add_status_instalador() {
		$contrato = $this->input->post('contrato');
		$id_cliente = $this->input->post('id_cliente');
		$quant_veiculos = $this->input->post('quant_veiculos');
		$status = $this->input->post('gerar');
		$dados['instalador'] = $this->input->post('instalador');
		$dados['contrato'] = $this->input->post('contrato');
		$dados['status'] = $this->input->post('status');
		$dados['msg'] = $this->input->post('msg');
		$dados['data_criacao'] = date('Y-m-d H:i:s');
		$retorno = $this->ordem_servico->add_status_instalador($dados);
		if($retorno)
			redirect(site_url("servico/".$status."/$contrato/$id_cliente/$quant_veiculos"));
		else
			echo "Erro! Tente novamente";
	}

	public function saveRating(){

		$data['id_os'] = $this->input->post('id_os');
		$data['id_instalador'] = $this->input->post('id_tec');
		$data['nota'] = $this->input->post('nota');

		$this->ordem_servico->saveRatingTec($data);
	}

	public function estorno_os($id_os, $id_cliente) {
		$equipamentos = $this->ordem_servico->get_equipamentos_os($id_os);
		$result = $this->ordem_servico->estornar_os($id_os, $id_cliente, $equipamentos);

		if ($result)
			$this->session->set_flashdata('sucesso', 'OS estornada com sucesso!');
		else
			$this->session->set_flashdata('erro', 'Não foi possivel estornar a OS.');
        redirect('servico/lista_equipamentos/'.$id_os);
	}

// +++++++++++++++++++++++ jerônimo	gabriel init ++++++++++++++++++++++++++++
	// métodos desenvolvidos
	// OS do tipo instalação
	function instalacaoOS($id_contrato, $id_cliente, $qtd_veiculos) {
		$dados['titulo']       = 'Show Tecnologia';
		$dados['id_cliente']   = $id_cliente;
		$dados['id_contrato']  = $id_contrato;
		$dados['qtdCar']       = $qtd_veiculos;
		$dados['boards']       = $this->ordem_servico->listBoardsContract($id_contrato, $id_cliente);
		$dados['seriais']      = $this->ordem_servico->listEqp();
		$dados['instaladores'] = $this->instalador->getListInstallers();
		$this->load->view('fix/header', $dados);
		$this->load->view('servicos/instalacaoOS');
		$this->load->view('fix/footer');
	}

	function validateBoard() {
		$data = array();
		$this->load->model('veiculo', 'car');
		$board       = $this->input->get('board');
		$id_cliente  = $this->input->get('id_cliente');
		$id_contrato = $this->input->get('id_contrato');
		if ( !$this->car->isExists($board, $id_contrato, $id_cliente) ) $data[] = 'A placa <strong>'.$board.'</strong> não pertencente ao contrato ou pode está inativa!';
		return json_encode($data);
	}

	function validateSerial() {
		$data   = array();
		$serial = $this->input->get('serial');
		if ( !$this->equipamento->isExists($serial) )
			$data[] = 'O serial <strong>'.$serial.'</strong> é inválido ou não está cadastrado!';
		else {
			$return = $this->equipamento->status($serail);
			if ($return) $data[] = $return;
		}
		return json_encode($data);
	}

	function saveInstalacaoOS() {
		echo $this->ordem_servico->save( $this->input->post() );
	}

	public function os_rlt(){

		$dados['titulo'] = lang('dashboard');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

		$infos['dados'] = $this->ordem_servico->get_ordens(3);
		$dados['tec'] = $this->instalador->getId_Instalador();
		$tipo;
		$status;
		$abertas = 0;
		$fechadas = 0;
		$manutencao = 0;
		$troca = 0;
		$retirada = 0;
		$instalacao = 0;
		$os_paga = 0;
		$os_n_paga = 0;
		foreach ($infos['dados'] as $os) {
            // VERIFICA TIPO OS //
			switch ($os->tipo_os) {
				CASE 2:
				$tipo = 'Manutenção';
				break;
				CASE 3:
				$tipo = 'Troca';
				break;
				CASE 4:
				$tipo = 'Retirada';
				break;
				DEFAULT:
				$tipo = 'Instalação';
				break;
			}
            // VERIFICA STATUS DA OS //
			switch ($os->status) {
				CASE 1:
				$status = 'Fechada';
				break;
				CASE 2:
				$status = 'Estornada';
				break;
				DEFAULT:
				$status = 'Cadastrado';
				break;
			}
			if ($os->tipo_os == 2) {
				$manutencao++;
			}
			elseif ($os->tipo_os == 3) {
				$troca++;
			}
			elseif ($os->tipo_os == 4) {
				$retirada++;
			}else{
				$instalacao++;
			}
			if ($os->status == 0) {
				$abertas++;
			}else{
				$fechadas++;
			}
			if ($os->status_pg == 0) {
				$os_paga++;
			}else{
				$os_n_paga++;
			}
		}

		$array[] = [

			'manutencao' => $manutencao,
			'troca' => $troca,
			'retirada' => $retirada,
			'instalacao' => $instalacao,
			'abertas' => $abertas,
			'fechadas' => $fechadas,
			'os_paga' => $os_paga,
			'os_n_paga' => $os_n_paga

		];
		$dados['os'] = json_encode($array);
		$dados['tec'] = json_encode($dados['tec']);
		

		$this->mapa_calor->registrar_acessos_url(site_url('/servico/os_rlt'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('servicos/rlt_os', $dados);
		$this->load->view('fix/footer_NS');
	}

	function get_os_for_cliente(){
		$id_cliente = $this->input->get('id_cliente');
		if ($id_cliente) {
			$ordemServ = $this->ordem_servico->getAjaxListOS($id_cliente, 0, 10);
			if ($ordemServ) {
				foreach ($ordemServ as $key => $os) {
					$data['results'][] = array(
						'id' => $os->id,
						'text' => $os->id.' - '.data_for_humans($os->data_solicitacao)
					);
				}

				echo json_encode($data);
			}else {
				return false;
			}
		}else {
			return false;
		}


	}

	// ++++++++++++ end +++++++++++++++
}
