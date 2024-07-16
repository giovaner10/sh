<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		//$this->auth->is_logged('admin');
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->helper('instalador_helper');
		$this->load->model('arquivo');
	}

	public function index()
	{
		$this->auth->is_logged('admin');

		$dados = array(
			'titulo' => lang('show_tecnologia'),
			'banners' => $this->arquivo->getArquivo("pasta = 'banners'"),
			'comunicados' => $this->arquivo->getComunicados()
		);

		var_dump($this->auth->is_allowed);
		$this->load->view('fix/header-new', $dados);
		$this->load->view('fix/home');
		$this->load->view('fix/footer_new');
	}

	public function instalador()
	{
		$this->auth->is_logged('instalador', 'instaladores/entrar');
		$this->load->model('instalador');
		if ($this->input->post()) {
			$retorno = $this->instalador->atualizar($this->input->post());
			if ($retorno) {
				$dados['retorno'] = true;
			} else {
				$dados['retorno'] = false;
			}
		}
		//$host = $_SERVER['HTTP_HOST'];
		//echo $host; die;

		$this->lang->load('pt', 'portuguese');
		$dados['pais'] = 'BRA';

		$dados['titulo'] = 'SHOWNET';
		$dados['instalador'] = true;
		$dados['valores'] = $this->instalador->get_valores();
		$dados['instalador'] = $this->instalador->get(array('email' => $this->auth->get_login('instalador', 'email')));
		$token = $dados['instalador']->token;
		$dados['ordens'] = json_decode(get_os($token, "-1"));
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

		foreach ($dados['ordens'] as $key) {
			switch ($key->tipo_os) {
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
			switch ($key->status) {
				case 1:
					$status = 'Fechada';
					break;
				case 2:
					$status = 'Fechada';
					break;
				default:
					$status = 'Em aberto';
					break;
			}

			if ($key->tipo_os == 2) {
				$manutencao++;
			} elseif ($key->tipo_os == 3) {
				$troca++;
			} elseif ($key->tipo_os == 4) {
				$retirada++;
			} else {
				$instalacao++;
			}

			if ($key->status == 0) {
				$abertas++;
			} else {
				$fechadas++;
			}

			if ($key->status_pg == 0) {
				$os_paga++;
			} else {
				$os_n_paga++;
			}

			$array[] = [

				'id' => $key->id,
				'id_cliente' => $key->id_cliente,
				'id_contrato' => $key->id_contrato,
				'solicitante' => $key->solicitante,
				'data_solicitacao' => $key->data_solicitacao,
				'contato' => $key->contato,
				'telefone' => $key->telefone,
				'endereco_destino' => $key->endereco_destino,
				'tipo_os' => $tipo,
				'quantidade_equipamentos' => $key->quantidade_equipamentos,
				'equipamentos_usados' => $key->equipamentos_usados,
				'equipamentos_devolvidos' => $key->equipamentos_devolvidos,
				'data_inicial' => $key->data_inicial,
				'data_final' => $key->data_final,
				'hora_final' => $key->hora_final,
				'id_instalador' => $key->id_instalador,
				'observacoes' => $key->observacoes,
				'arquivo' => $key->arquivo,
				'assinatura' => $key->assinatura,
				'status' => $status,
				'data_cadastro' => $key->data_cadastro,
				'data_fechamento' => $key->data_fechamento,
				'data_assinada' => $key->data_assinada,
				'id_usuario' => $key->id_usuario,
				'status_pg' => $key->status_pg,
				'nome_cliente' => $key->nome_cliente,
				'placa' => $key->placa,
				'serial' => $key->serial,
				'marca' => $key->marca,
				'abertas' => $abertas,
				'fechadas' => $fechadas,
				'manutencao' => $manutencao,
				'troca' => $troca,
				'retirada' => $retirada,
				'instalacao' => $instalacao,
				'os_paga' => $os_paga,
				'os_n_paga' => $os_n_paga,
				'bt_st' => $key->status

			];
		}




		(object)$dados['ord'] = $array;

		$this->load->view('fix/headertec', $dados);
		$this->load->view('instaladores/viewtec/viewtec');
	}

	public function instalador2()
	{
		$this->auth->is_logged('instalador', 'instaladores/entrar');
		$this->load->model('instalador');
		if ($this->input->post()) {
			$retorno = $this->instalador->atualizar($this->input->post());
			if ($retorno)
				$dados['retorno'] = true;
			else
				$dados['retorno'] = false;
		}

		//$host = $_SERVER['HTTP_HOST'];
		//echo $host; die;

		$this->lang->load('en', 'english');
		$dados['pais'] = 'USA';

		$dados['titulo'] = 'SHOWNET';
		$dados['instalador'] = true;
		$dados['valores'] = $this->instalador->get_valores();
		$dados['instalador'] = $this->instalador->get(array('email' => $this->auth->get_login('instalador', 'email')));

		$this->load->view('fix/header', $dados);
		$this->load->view('instaladores/index');
		$this->load->view('fix/footer', $dados);
	}

	public function representante()
	{
		$this->auth->is_logged('representante', 'representantes/entrar');
		$this->load->model('representante');
		if ($this->input->post()) {
			$retorno = $this->representante->atualizar($this->input->post());
			if ($retorno)
				$dados['retorno'] = true;
			else
				$dados['retorno'] = false;
		}

		//$host = $_SERVER['HTTP_HOST'];
		//echo $host; die;

		$this->lang->load('pt', 'portuguese');
		$dados['pais'] = 'BRA';

		$dados['titulo'] = 'SHOWNET';
		$dados['representante'] = true;
		$dados['representante'] = $this->representante->get(array('email' => $this->auth->get_login('representante', 'email')));

		$this->load->view('fix/header', $dados);
		$this->load->view('representantes/index');
		$this->load->view('fix/footer', $dados);
	}


	public function representante2()
	{
		$this->auth->is_logged('representante', 'representantes/entrar');
		$this->load->model('representante');
		if ($this->input->post()) {
			$retorno = $this->representante->atualizar($this->input->post());
			if ($retorno)
				$dados['retorno'] = true;
			else
				$dados['retorno'] = false;
		}

		//$host = $_SERVER['HTTP_HOST'];
		//echo $host; die;

		$this->lang->load('en', 'english');
		$dados['pais'] = 'USA';

		$dados['titulo'] = 'SHOWNET';
		$dados['representante'] = true;
		$dados['representante'] = $this->representante->get(array('email' => $this->auth->get_login('representante', 'email')));

		$this->load->view('fix/header', $dados);
		$this->load->view('representantes/index');
		$this->load->view('fix/footer', $dados);
	}


	public function mapa_eq($serial)
	{

		$dados['eqp'] = json_decode(get_equipamento($serial));

		$ignition;
		$panico;
		$bloqueio;
		$gprs;
		$gps;

		foreach ($dados['eqp'] as $key) {
			switch ($key->IGNITION) {
				case 0:
					$ignition = 'Desligada';
					break;
				case 1:
					$ignition = 'Ligada';
					break;
				default:
					break;
			}

			switch ($key->IN1) {
				case 0:
					$panico = 'Desligado';
					break;
				case 1:
					$panico = 'Ligado';
				default:
					break;
			}

			switch ($key->OUT1) {
				case 0:
					$bloqueio = 'Desligado';
					break;
				case 1:
					$bloqueio = 'Ligado';
					break;
				default:
					break;
			}
			switch ($key->GPRS) {
				case 0:
					$gprs = 'Desligado';
					break;
				case 1:
					$gprs = 'Ligado';
					break;
				default:
					break;
			}
			switch ($key->GPS) {
				case 0:
					$gps = 'Desligado';
					break;
				case 1:
					$gps = 'Ligado';
					break;
				default:
					break;
			}

			$array[] = [

				'data' => (new DateTime($key->DATA))->format('d/m/Y H:i:s'),
				'velocidade' => $key->VEL,
				'ignicao' => $ignition,
				'panico' => $panico,
				'bloqueio' => $bloqueio,
				'gprs' => $gprs,
				'gps' => $gps,
				'voltagem' => $key->VOLTAGE,
				'odometro' => $key->ODOMETER,
				'rpm' => $key->RPM,

			];
		}

		(object)$dados['info_eq'] = $array;

		$this->load->view('fix/headertec');
		$this->load->view('instaladores/viewtec/mapa_equipamento', $dados);
	}

	function viewArquivo()
	{
		$fname = $this->uri->segment(3);
		print_r($fname);
		$tofile = base_url("uploads/comunicados/" . $fname);
		header('Content-Type: application/pdf');
		@readfile("$tofile");
	}
}
