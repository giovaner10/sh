<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Vendasgestor extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('mapa_calor');
	}

	public function anuncios() {
		$dados = [
			'titulo' => lang('anuncios'),
			'load' => ['select2']
		];
		$this->mapa_calor->registrar_acessos_url(site_url('/vendasgestor/anuncios'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('vendasgestor/anuncios/index');
		$this->load->view('vendasgestor/anuncios/modais/cadastrar_editar');
		$this->load->view('vendasgestor/anuncios/modais/visualizar');
		$this->load->view('fix/footer_NS');
	}

	public function indicadores() {
		$dados = [
			'titulo' => lang('indicadores')
		];
		$this->mapa_calor->registrar_acessos_url(site_url('/vendasgestor/indicadores'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('vendasgestor/indicadores/index');
		$this->load->view('fix/footer_NS');
	}

	public function listar_licencas_produtos() {
		$this->load->model('vendagestor');
		$licencas = $this->vendagestor->listar_licencas_produtos();
		echo json_encode($licencas);
	}

}

