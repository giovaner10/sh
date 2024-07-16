<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Relatorio_coordenadas extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->helper('api_helper');
		$this->load->model('mapa_calor');
	}


	public function index()
	{
	}

	// public function getCoordenadasDia($placa, $code)
	// {
	// 	$dataInicial = date('d/m/Y');
	// 	$dataFinal = new DateTime();

	// 	$dataFinal->modify('+1 day');
	// 	$dataFinal = $dataFinal->format('d/m/Y');
	// 	$placas = [$placa];
	// 	$idCliente = $code;

	// 	$data = get_listarRastreamentoVeiculos($dataInicial, $dataFinal, $placas, $idCliente);

	// 	$data = json_decode($data, true);
	// 	$data['dados'] = json_encode($data['dados']);

	// 	if ($placa == 'null') {
	// 		$data['erro'] = 'Não é possível exibir as coordenadas, pois o veículo não tem placa.';
	// 	}

	// 	$titulo = 'Relatório de Coordenadas Diário';
	// 	$dados = array('titulo' => $titulo);

	// 	$this->load->view('new_views/fix/header', $dados);
	// 	$this->load->view('relatorios/relarorio_coordenadas', $data);
	// 	$this->load->view('fix/footer_NS');
	// }

	// public function getCoordenadasSemana($placa, $code)
	// {
	// 	$dataInicial = date('d/m/Y', strtotime('-6 day'));
	// 	$dataFinal = new DateTime();

	// 	//garante contagem de 7 dias
	// 	$dataFinal->modify('+1 day');

	// 	$dataFinal = $dataFinal->format('d/m/Y');
	// 	$placas = [$placa];
	// 	$idCliente = $code;

	// 	$data = get_listarRastreamentoVeiculos($dataInicial, $dataFinal, $placas, $idCliente);

	// 	$data = json_decode($data, true);
	// 	$data['dados'] = json_encode($data['dados']);

	// 	if ($placa == 'null') {
	// 		$data['erro'] = 'Não é possível exibir as coordenadas, pois o veículo não tem placa.';
	// 	}

	// 	$titulo = 'Relatório de Coordenadas Semanal';
	// 	$dados = array('titulo' => $titulo);

	// 	$this->load->view('new_views/fix/header', $dados);
	// 	$this->load->view('relatorios/relarorio_coordenadas', $data);
	// 	$this->load->view('fix/footer_NS');
	// }

	public function getCoordenadasSemana($placa, $code)
	{

		$dados['placa'] =  $placa;
		$dados['code'] =  $code;


		$dados['titulo'] =  'Coordenadas - Semanal';
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$dados['porDia'] = false;
		$this->mapa_calor->registrar_acessos_url(site_url('/relatorio_coordenadas/getCoordenadasSemana'));


		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('relatorios/veiculos_diponiveis/coordenadas_veiculo', $dados);
		$this->load->view('fix/footer_NS');
	}


	public function getCoordenadasDia($placa, $code)
	{
		$dados['placa'] =  $placa;
		$dados['code'] =  $code;


		$dados['titulo'] =  'Coordenadas - Diário';
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$dados['porDia'] = true;
		$this->mapa_calor->registrar_acessos_url(site_url('/relatorio_coordenadas/getCoordenadasDia'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('relatorios/veiculos_diponiveis/coordenadas_veiculo', $dados);
		$this->load->view('fix/footer_NS');
	}




	public function getCoordenadasPorTipo($placa, $code, $tipoRelatorio)
	{
		$data = null;

		if ($tipoRelatorio == 'semanal') {
			$dataInicial = date('d/m/Y', strtotime('-6 day'));
			$dataFinal = new DateTime();

			// garante contagem de 7 dias
			$dataFinal->modify('+1 day');

			$dataFinal = $dataFinal->format('d/m/Y');
			$placas = [$placa];
			$idCliente = $code;

			$data = get_listarRastreamentoVeiculos($dataInicial, $dataFinal, $placas, $idCliente);
		} else {
			$dataInicial = date('d/m/Y');
			$dataFinal = new DateTime();

			$dataFinal->modify('+1 day');
			$dataFinal = $dataFinal->format('d/m/Y');
			$placas = [$placa];
			$idCliente = $code;

			$data = get_listarRastreamentoVeiculos($dataInicial, $dataFinal, $placas, $idCliente);
		}

		$arrayDados = json_decode($data, true);
		
		if ($arrayDados['status'] == 404) {
			echo json_encode(array());
		} else {
			echo json_encode($arrayDados['dados']);
		}
	}
}
