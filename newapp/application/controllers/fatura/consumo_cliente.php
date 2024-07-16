<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumo_cliente extends CI_Controller {

	public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('auth');
	}

	public function gerar_consumo_diario(){

		$this->load->model('cliente');
		$this->load->model('veiculo');
		$this->load->model('usuario_gestor');

		$clientes = $this->cliente->listar(array('id' => 2089));

		if(count($clientes) > 0){
			foreach ($clientes as $cliente){

				$usr_cliente = $this->usuario_gestor->listar(array('id_cliente' => $cliente->id, 
															'status_usuario' => 'ativo'));

				if (count($usr_cliente)){
					foreach ($usr_cliente as  $usr) {
						$veiculos = $this->veiculo->listar_com_contrato($usr->id_cliente);
					}
					echo '<pre>';
					print_r($veiculos);
				}

				/*
				$contratos = $this->contrato->listar("ctr.id_cliente = {$cliente->id} AND
													  ctr.status IN (1,2)", 0, 9999999);
				if (count($contratos)){

				}
				*/
			}
		}
	}


}