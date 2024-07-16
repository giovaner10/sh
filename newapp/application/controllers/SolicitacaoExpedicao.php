<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class SolicitacaoExpedicao extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->helper('util_helper');
		$this->load->model('mapa_calor');
	}

	public function index()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('solicitacao_expedicao');
        $dados['emailUsuario'] = $this->auth->get_login_dados('email');

		$this->mapa_calor->registrar_acessos_url(site_url('/SolicitacaoExpedicao'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/SolicitacaoExpedicao', $dados);
		$this->load->view('fix/footer_NS');   
	}

    public function cadastrarSolicitacao(){
		$solicitacao = $this->input->post(); 
		$form = $solicitacao['form'];
		$equipamentos = $solicitacao['equipamentos'];
		$veiculos = $solicitacao['veiculos'];
		
		$dataFinal = str_replace("-", "/", $form['data']);
		$form['data']  = date('d/m/Y', strtotime($dataFinal));
		$resultado = cadastrarSolicitacao($form, $equipamentos, $veiculos );
		echo json_encode($resultado);
    }

    public function listarSolicitacoes(){
		$resultado = listarSolicitacoes();
		echo json_encode($resultado);
    }

	public function listarExpedicaoSelect2(){
		$retorno = array('pagination' =>[
            'more' => false
        ]);

        $retorno += (listarSolicitacoes_select2());

        foreach ($retorno['results'] as $key => $value) {
            $retorno['results'][$key] = array(
                'id' => $value['id'],
                'text' => $value['id']
            );
        }

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }
	}

	public function listarSolicitacoesById(){
		$id = $this->input->post('id');

		$resultado = listarSolicitacoesById($id);
		echo json_encode($resultado);
    }

	public function buscarSolicitacoes(){
		$idSolicitacao = $this->input->post('idSolicitacao');
		$contrato =  $this->input->post('contrato');
		$email =  $this->input->post('email');

		if ($this->input->post('idCliente') == '') {
			$idCliente = $this->input->post('nomeCliente');
		} else {
			$idCliente = $this->input->post('idCliente');
		}

		if ($this->input->post('cpf') == '') {
			$documento = $this->input->post('cnpj');
		} else {
			$documento = $this->input->post('cpf');
		}

		$params = array(
			'idSolicitacao' => $idSolicitacao,
			'idCliente'     => $idCliente,
			'documento'     => $documento,
			'contrato'      => $contrato,
			'email'         => $email
		);

		$resultado = listarSolicitacoesExpedicaoBySECliente($params);

		echo json_encode($resultado);
	}

	public function listar_veiculos(){
        $this->load->model('cliente');
        $this->auth->is_logged('admin');

		$id_cliente = $this->input->get('id_cliente'); 
		$texto = $this->input->get('q'); 
		$code = $this->input->get('code'); 

		$dados = array();
		$dados['id_cliente'] = $id_cliente;
		$dados['texto'] = $texto;
		$dados['code'] = $code;

		$resposta = [
			'results' => [],
            'pagination' => [
				'more' => false,
			]
		];

		if(!$id_cliente){
			echo json_encode($resposta);
		}

        $veiculos =  $this->cliente->get_veiculos($dados);

		foreach ($veiculos as $key => $veiculo) {
			$resposta['results'][] = array(
				'id' => $veiculo->code,
				'text' => $veiculo->veiculo." (Placa - " .$veiculo->placa .")",
				'placa' => $veiculo->placa,
				'code_cliente' => $veiculo->code_cliente,
				'serial' => $veiculo->serial,
				'veiculo' => $veiculo->veiculo,
				'observacoes' => $veiculo->obs_descricao,
			);
		}

		echo json_encode($resposta);
	} 

	public function listarInsumos(){
        $insumosLista =  get_buscarInsumos();

		$insumos = array();
		$insumos['Sirene'] = array();
		$insumos['Leitor'] = array();
		$insumos['IButton'] = array();
		$insumos['Rele 12V'] = array();
		$insumos['Rele 24V'] = array();
		$insumos['Pânico'] = array();
		$insumos['Outros'] = array();
		
		if($insumosLista['status'] == 200 ){
			foreach ($insumosLista['results'] as $key => $insumo) {
	
				if(strpos($insumo['referencia'], 'Sirene') !== false){
					$insumos['Sirene'][] = $insumo;
				}else if(strpos($insumo['referencia'], 'Leitor') !== false){
					$insumos['Leitor'][] = $insumo;
				}else if(strpos($insumo['referencia'], 'IButton') !== false){
					$insumos['IButton'][] = $insumo;
				}else if(strpos($insumo['referencia'], 'Rele 12V') !== false){
					$insumos['Rele 12V'][] = $insumo;
				}else if(strpos($insumo['referencia'], 'Rele 24V') !== false){
					$insumos['Rele 24V'][] = $insumo;
				}else if(strpos($insumo['referencia'], 'Pânico') !== false){
					$insumos['Pânico'][] = $insumo;
				}else{
					$insumos['Outros'][] = $insumo;
				}
			}

			$insumosLista['results'] = $insumos;
		}

		echo json_encode($insumosLista);
	} 
}

