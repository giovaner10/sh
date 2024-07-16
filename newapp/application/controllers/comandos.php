<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Comandos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('veiculo');
		$this->load->model('equipamento');
		$this->auth->is_logged('admin');
		$this->load->model('auth');
	}

	/*
	 * Função que envia comando inicialmente continental
	 * @param String $serial
	 */
	public function envioAntigo($serial = false, $code = false)
	{
		$equipamento = $this->equipamento->get_prefixo($serial);
		$dados['msg'] = '';
		$id_user = $this->auth->get_login('admin', 'user');
		if ($equipamento > 0) {
			$dados['email'] = $this->auth->get_login('admin', 'email');
			$dados['serial'] = $serial;
			$dados['veiculos'] = $this->veiculo->get_veiculo_gestor($code);
			$dados['equipamento'] = $equipamento[0]->equipamento;
			$dados['lista_comandos'] = $this->equipamento->get_comando($equipamento[0]->id, $serial);
			$dados['comandos'] = $this->equipamento->get_lista_comandos(array('id' => $serial));
		}

		$dados['titulo'] = 'Show Tecnologia';
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('comandos/view');
		$this->load->view('fix/footer_NS');
	}

	public function envioAnt2($serial = false, $code = false)
	{
		$equipamento = $this->equipamento->obter_prefixo($serial);
		$equipamento = json_decode(json_encode($equipamento), true);
		$dados['msg'] = '';
		$id_user = $this->auth->get_login('admin', 'user');

		if (!empty($equipamento) && is_array($equipamento)) {
			$email = $this->auth->get_login('admin', 'email');
			$veiculos = $this->veiculo->get_veiculo_gestor($code);
			foreach ($veiculos as &$veiculo) {
				$veiculo->email = $email;
				$veiculo->equipamento = isset($equipamento[0]['equipamento']) ? $equipamento[0]['equipamento'] : 'Não informado';
				$veiculo->serial = $serial;
			}

			$dados['veiculos'] = $veiculos;
			$dados['lista_comandos'] = $this->equipamento->get_comando($equipamento["id"], $serial);
			$dados['comandos'] = $this->equipamento->get_lista_comandos(array('id' => $serial));
		} else {
			$dados['veiculos'] = [];
			$dados['lista_comandos'] = [];
			//$dados['comandos'] = [];
		}

		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$dados['titulo'] = 'Show Tecnologia';
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('comandos/viewNew');
		$this->load->view('fix/footer_NS');
	}

	public function envio($serial = false, $code = false)
	{
		$dados['serial'] = $serial;
		$dados['code'] = $code;
		$dados['msg'] = '';
		$dados['titulo'] = lang('comandos_enviados');
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('comandos/viewNew', $dados);
		$this->load->view('fix/footer_NS');
	}


	public function carregarDados($serial = false, $code = false)
	{
		$equipamento = $this->equipamento->obter_prefixo($serial);
		$equipamento = json_decode(json_encode($equipamento), true);
		$dados['msg'] = '';

		if (!empty($equipamento) && is_array($equipamento)) {
			$email = $this->auth->get_login('admin', 'email');
			$veiculos = $this->veiculo->get_veiculo_gestor($code);
			foreach ($veiculos as &$veiculo) {
				$veiculo->email = $email;
				$veiculo->equipamento = isset($equipamento[0]['equipamento']) ? $equipamento[0]['equipamento'] : 'Não informado';
				$veiculo->serial = $serial;
			}

			$dados['veiculos'] = $veiculos;
			$dados['lista_comandos'] = $this->equipamento->get_comando($equipamento["id"], $serial);
			$dados['comandos'] = $this->equipamento->get_lista_comandos(array('id' => $serial));
		} else {
			$dados['veiculos'] = [];
			$dados['lista_comandos'] = [];
			$dados['comandos'] = [];
		}

		echo json_encode($dados);
	}



	public function envio_comando()
	{
		$comando = $this->input->post('comando');
		$serial = $this->input->post('serial');
		$id_user = $this->auth->get_login('admin', 'user');

		$ret = $this->equipamento->put_comando($serial, $comando, $id_user);

		$msg = false;

		if ($ret == 1)
			$msg = true;

		echo json_encode($msg);
	}

	public function envio_csv($serial = false, $code = false)
	{
		$equipamento = $this->equipamento->get_prefixo($serial);
		$dados['msg'] = '';
		if ($equipamento > 0) {
			$dados['enviados'] = $this->equipamento->get_comandos_enviados($serial);
			$dados['veiculos'] = $this->veiculo->get_veiculo_gestor($code);
			$dados['equipamento'] = $equipamento[0]->equipamento;
			$dados['lista_comandos'] = $this->equipamento->get_comando($equipamento[0]->id, $serial);
			$dados['comandos'] = $this->equipamento->get_lista_comandos(array('id' => $serial));
		}

		$dados['titulo'] = 'Show Tecnologia';

		$this->load->view('fix/header', $dados);
		$this->load->view('comandos/view');
		$this->load->view('fix/footer');
	}

	public function correcao_curva()
	{

		$where = "serial LIKE '8%'";
		$veiculos = $this->veiculo->get_veiculo_gestor($where, 999999, 'placa');

		if (count($veiculos)) {
			foreach ($veiculos as $veic) {
				$ret = $this->equipamento->put_comando($veic->serial, 40);
				if ($ret = 1) {
					$dados['msg'] = 'Solicitado com sucesso..';
				} else {
					$dados['msg'] = 'Não gravado com sucesso..';
				}
			}
		}
	}

	/**DEVELOPER ANDRÉ GOUVEIA**/
	public function view()
	{

		$dados['titulo'] = "ShowTecnologia";
		$seriais = $this->equipamento->listar_equipamentos();
		$lista_seriais = array();

		$pkCount = (is_array($seriais) ? count($seriais) : 0);

		if ($pkCount > 0) {
			foreach ($seriais as $serial) {
				$lista_seriais[] = $serial->serial;
			}
		}
		$dados['seriais'] = json_encode($lista_seriais);
		$this->load->view('fix/header4', $dados);
		$this->load->view('comandos/envio_sms');
		$this->load->view('fix/footer4');

	}
}