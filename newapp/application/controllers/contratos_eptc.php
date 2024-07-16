<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contratos_eptc extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('contrato_eptc');
		$this->load->model('contrato');
		$this->load->model('mapa_calor');
		$this->load->helper('date');
		$this->load->helper('contrato');
		$this->load->helper(array('form', 'url'));
		$this->load->library('upload');
		$this->load->library('pagination');
		$this->load->helper('download');
        $this->auth->is_logged('admin');
        $this->load->model('auth');

	}

	public function listar_contratos_old($page = 0)
	{	
		$this->auth->is_allowed('cad_contratos_eptc');

		if($this->input->get()){			
			$para_view['contratos'] =  $this->contrato_eptc->listar_pesquisa_contratos($this->input->get());
		}else{
		    
			$config['base_url'] = site_url('contratos_eptc/listar_contratos');
			$config['total_rows'] = $this->contrato_eptc->get_total_contratos();
			$config['per_page'] = 40;

			$this->pagination->initialize($config);

			$para_view['contratos'] = $this->contrato_eptc->get_contratos($page, $config['per_page']);

		}

		$para_view['titulo'] = 'Show Tecnologia';

		$this->mapa_calor->registrar_acessos_url(site_url('/contratos_eptc/listar_contratos'));

		$this->load->view('fix/header4', $para_view);
		$this->load->view('eptc/index');
		$this->load->view('fix/footer4');
		
	}

	public function listar_contratos()
	{
		$this->auth->is_allowed('cad_contratos_eptc');

		$dados['titulo'] = lang('auditoriashownet');
		$dados['load'] = array('ag-grid', 'select2', 'mask');

		$this->mapa_calor->registrar_acessos_url(site_url('/contratos_eptc/listar_contratos'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('eptc/new_index');
		$this->load->view('fix/footer_NS');
	}

	public function get_contratos_paginated()
	{
		try {
			$startRow = (int) $this->input->post('startRow');
			$endRow = (int) $this->input->post('endRow');
			$coluna = $this->input->post('coluna');
			$valor = $this->input->post('valor');

			$limit = $endRow - $startRow;
			$offset = $startRow;

			if ($coluna) {
				$rows = $this->contrato_eptc->get_contratos_paginated($limit, $offset, $coluna, $valor);
				$lastRow = (int) $this->contrato_eptc->get_numero_contratos($coluna, $valor);
			} else {
				$rows = $this->contrato_eptc->get_contratos_paginated($limit, $offset);
				$lastRow = (int) $this->contrato_eptc->get_numero_contratos();
			}

			if ($lastRow > 0) {
				echo json_encode(array(
					"statusCode" => 200,
					"success" => true,
					"rows" => $rows,
					"lastRow" => $lastRow
				));
			} else {
				echo json_encode(array(
					"statusCode" => 404,
					"success" => false,
					"message" => 'Dados não encontrados para os parâmetros informados.',
				));
			}
		} catch (Exception $e) {
			echo json_encode(array(
				"statusCode" => 500,
				"success" => false,
				"message" => 'Erro ao listar a contratos.',
			));
		}
	}

	public function get_arquivos_digi_contrato_eptc($prefixo)
	{
		try {
			$result = $this->contrato->get_arqui_contratos($prefixo);
			echo json_encode(
				array(
					'success' => true,
					'dados' => $result
				)
			);
		} catch (Exception $e) {
			echo json_encode(
				array(
					'success' => false,
					'mensagem' => 'Erro ao listar arquivos do contrato.'
				)
			);
		}
	}


	public function imprimir_contrato($id_contrato)
	{		
		$dados['contratos'] = $this->contrato_eptc->get_contrato($id_contrato);
		$dados['data_impressao'] = date('Y-m-d');
		
		$this->load->view('eptc/imprimir_contrato', $dados);			
	}

	public function digi_contrato_eptc($prefixo)
	{
		$para_view['arquivos'] = $this->contrato->get_arqui_contratos($prefixo);
		$para_view['id_contrato'] = $prefixo;

		$this->load->view('clientes/digi_contrato_eptc', $para_view);
	}
	
}