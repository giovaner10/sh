<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Api_sim2m extends CI_Controller {

	public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('contrato');
		$this->load->model('cliente');
		$this->load->model('usuario_gestor');
		$this->load->model('parlacom');
        $this->load->model('auth');
	}

	public function bloqueio_chip() {
		if ($login = $this->login()) {
			$servicos = $this->servicos($login);
			if ($servicos) {
				$servico = $this->bloqueio($servicos, $login->session);
				if ($servico) {
					$operadoras = array(
						'2' => 'Vivo',
						'3' => 'Claro',
						'4' => 'Vodafone'
					);

					json_response(array(
						'ip' => $servico->ip,
						'numero' => $servico->pin,
						'ccid' => $servico->ccid,
						'ativo' => (int) $servico->trafego > 0 ? '1' : '0',
						'operadora' => $operadoras[
							$servico->operadora
						]
					), 200);
				}
				json_response('The number does not exist or can not be locked or unlocked', 400);
			}
			json_response('Services not found', 400);
		}
		json_response('Unauthorized', 400);
	}

	public function consumo_chip()
	{
		if ($login = $this->login()) {
			$servicos = $this->servicos($login);
			if ($servicos) {
				$servico = $this->consumo($servicos, $login->session);
				if ($servico) {
					$operadoras = array(
						'2' => 'Vivo',
						'3' => 'Claro',
						'4' => 'Vodafone'
					);

					$servico->consumo = str_replace(',', '', $servico->consumo);
					$servico->saldo = str_replace(',', '', $servico->saldo);

					$restante = ($servico->saldo * 100) / $servico->mb;
					$consumido = 100 - (($servico->saldo * 100) / $servico->mb);

					json_response(array(
						'saldo' => $servico->saldo,
						'plano' => $servico->mb,
						'plano_valor' => $servico->mensalidade,
						'reatante' => round($restante, 2) . '%',
						'consumido' => round($consumido, 2) . '%',
						'ip' => $servico->ip,
						'numero' => $servico->pin,
						'ccid' => $servico->ccid,
						'ativo' => (int) $servico->trafego > 0 ? '1' : '0',
						'operadora' => $operadoras[
							$servico->operadora
						]
					));
				}
				json_response('The number does not exist or can not be locked or unlocked', 400);
			}
			json_response('Services not found', 400);
		}
		json_response('Unauthorized', 400);
	}

	private function bloqueio($servicos, $session)
	{
		$status = $this->input->get('status');
		$numero = null;

		if ($this->input->get('numero')) {
			$numero = $this->input->get('numero');
		} else if($this->input->get('ccid')) {
			$numero = $this->input->get('ccid');
		}

		foreach ($servicos as $servico) {
			if ($numero && ($servico->pin == $numero || $servico->ccid == $numero)) {
				$status = (int) $status;
				$alternado = $this->parlacom->bloqueio(array(
					'session' => $session,
					'login' => $servico->login,
					'id' => $servico->id,
					'ccid' => $servico->ccid,
					'pin' => $servico->pin,
					'operadora' => $servico->operadora,
					'status' => $status ? '1024' : '0'
				));
				if ($alternado) {
					foreach ($this->parlacom->servicos($servico->login, $servico->operadora, $session) as $servico) {
						return $servico;
					}
				}
			}
		}
		return false;
	}

	private function consumo($servicos, $session)
	{
		$numero = null;

		if ($this->input->get('numero')) {
			$numero = $this->input->get('numero');
		} else if($this->input->get('ccid')) {
			$numero = $this->input->get('ccid');
		}

		foreach ($servicos as $servico) {
			if ($numero && ($servico->pin == $numero || $servico->ccid == $numero)) {
				return $servico;
			}
		}
		return false;
	}

	private function servicos($login)
	{
		if ($login->session) {

			$servicosVivo = $this->parlacom->servicos($login->login, '2', $login->session);
			$servicosClaro = $this->parlacom->servicos($login->login, '3', $login->session);
			$servicosVodafone = $this->parlacom->servicos($login->login, '4', $login->session);

			$servicos = array_merge($servicosVivo, $servicosClaro, $servicosVodafone);

			if (count($servicos)) {
				return $servicos;
			}

		}

		return null;

	}

	private function login()
	{
		if ($this->input->get('token')) {
			$usuario = $this->usuario_gestor->get(array(
				'token_usuario' => $this->input->get('token')
			));

		} elseif ($this->input->get('id_user')) {
			$usuario = $this->usuario_gestor->get(array(
				'code' => $this->input->get('id_user')
			));

		}else {
			$usuario = $this->usuario_gestor->get(array(
				'usuario' => $this->input->get('usuario'),
				'senha' => md5($this->input->get('senha'))
			));
		}

		if (count($usuario)) {

			$cliente = $this->cliente->get(array(
				'id' => $usuario->id_cliente
			));

			if ($cliente) {

				$user = $this->parlacom->login('jailson', 'jcn2417');
				if (!$user) {
					$user = $this->parlacom->login('simm2m', 'simm2m@2016');
				}

				$cliente->session = $user ? $user->session : null;

				$cliente->login = str_replace(array('.', '-', '/'), '',
					$cliente->cnpj ? $cliente->cnpj : $cliente->cpf
				);

				return $cliente;

			}

		}

		return false;

	}

}
