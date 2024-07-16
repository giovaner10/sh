<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

ini_set('display_errors', 1);

class Omnisafe extends CI_Controller
{
    public $enderecosConsultados = [];
    
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
		$this->load->helper('util_omnisafe_helper');
	}

	public function index()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/Omnisafe/Omnisafe'));

		$dados['titulo'] = lang('configurador_omnisafe');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $dados['idUser'] = $this->auth->get_login_dados('user');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('omnisafe/ConfiguradorOmnisafe');
		$this->load->view('fix/footer_NS');

	}

    public function listarPerfis() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $serial = $this->input->post('serial');
        $nomePerfil = $this->input->post('nomePerfil');
        $idCliente = $this->input->post('idCliente');

        $startRow++;

        if(isset($nomePerfil) || isset($serial)){
            $nomePerfil = str_replace(' ', '+', trim($nomePerfil));
            $serial = str_replace(' ', '+', trim($serial));
        }

        $dados = get_buscarPerfis($idCliente, $serial, $nomePerfil, $startRow, $endRow);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['configsPerfilDTO'],
                "lastRow" => $dados['resultado']['qtdTotalConfigPerfil']
            ));
        } else if ($dados['status'] == 404) {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
        
    }

    public function buscarPerfilById() {
        $id = (int) $this->input->post('idPerfil');

        $dados = get_buscarPerfilById($id);
        echo json_encode($dados);
    }
    
    public function buscarPerfilByParametros() {
        $idCliente = (int) $this->input->post('idCliente');
        
        $dados = get_buscarPerfilByParametros($idCliente);
        echo json_encode($dados);
    }
    
    public function cadastrarPerfil() {
        $POSTFIELDS = array(
            'serial' => $this->input->post('serial'),
            'idCliente' => $this->input->post('idCliente'),
            'nomePerfil' => $this->input->post('nomePerfil')
        );
        
        $dados = post_cadastrarPerfil($POSTFIELDS);
        echo json_encode($dados);
    }
    
    public function editarPerfil(){
        $POSTFIELDS = array(
            'idPerfil' => $this->input->post('idPerfil'),
            'serial' => $this->input->post('serial'),
            'idCliente' => $this->input->post('idCliente'),
            'nomePerfil' => $this->input->post('nomePerfil')
        );
        
        $dados = put_editarPerfil($POSTFIELDS);
        echo json_encode($dados);
    }
    
    public function deletarPerfil() {
        $POSTFIELDS = array(
            'idPerfil' => $this->input->post('id'),
            'status' => 0
        );
        
        $dados = patch_removerPerfil($POSTFIELDS);
        echo json_encode($dados);
    }
    
    public function buscarSerial(){
        $idCliente = $this->input->get('idCliente');
        $dados = get_buscarSerial($idCliente);
        $serial = array();
        
        if($dados['status'] == 200 && count($dados['resultado']) > 0){
            foreach ($dados['resultado'] as $key => $serial) {
                $serial['text'] = $serial['equipamento'];
                $serialFormatado[] = $serial;
            }
            $serial['resultado'] = $serialFormatado;
        }
        
        echo json_encode($dados);
    }
    
    public function buscarConfigPower(){
        $startRow = $this->input->post('startRow');
        $endRow = $this->input->post('endRow');
        $idCliente = $this->input->post('idCliente');
        $idPerfil = $this->input->post('idPerfil');
        $serial = $this->input->post('serial');
        
        $startRow++;

        if(isset($serial)){
            $serial = str_replace(' ', '+', trim($serial));
        }
        
        $dados = get_buscarConfigPower($startRow, $endRow, $idCliente, $idPerfil, $serial);
        
        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['configsPowerDTO'],
                "lastRow" => $dados['resultado']['qtdTotalConfigPower']
            ));
        } else if ($dados['status'] == 404) {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }
    
    public function cadastrarConfigPower(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $dados = post_cadastrarConfigPower($dadosRecebidos);
        
        echo json_encode($dados);
    }

    public function buscarConfigPowerById() {
        $id = (int) $this->input->post('idPower');

        $dados = get_buscarConfigPowerById($id);
        echo json_encode($dados);
    }
    
    public function deletarConfigPower() {
        $POSTFIELDS = array(
            'idPower' => $this->input->post('id'),
            'status' => 0
        );
        
        $dados = patch_removerConfigPower($POSTFIELDS);
        echo json_encode($dados);
    }
    
    public function editarConfigPower(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        
        $dados = put_editarConfigPower($dadosRecebidos);
        
        echo json_encode($dados);
    }
    
    public function editarPowerSchedulesLista(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $dados = put_editarPowerSchedulesLista($dadosRecebidos);
        
        echo json_encode($dados);
    }

    public function cadastrarPowerSchedulesLista(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $dados = post_cadastrarScheduleLista($dadosRecebidos);
        
        echo json_encode($dados);
    }

    public function buscarPowerSchedules() {
        $id = (int) $this->input->post('idPowerConfig');
    
        $dados = get_buscarPowerSchedules($id);
        echo json_encode($dados);
    }

    public function alterarStatusConfigPowerSchedule() {
        $POSTFIELDS = [
            'idSchedule' => (int) $this->input->post('id'),
            'status' => (int) $this->input->post('status')
        ];
        
        $dados = patch_alterarStatusConfigPowerSchedule($POSTFIELDS);
        echo json_encode($dados);
    }

    public function buscarHistoricoComandos() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $serial = $this->input->post('serial');
        $idPerfil = $this->input->post('idPerfil');
        $idCliente = $this->input->post('idCliente');
        $statusEnvio = $this->input->post('statusEnvioBusca');
        $statusRecebimento = $this->input->post('statusRecebimentoBusca');

        $startRow++;

        $dados = get_buscarHistoricoComandos($idCliente, $serial, $idPerfil, $startRow, $endRow, $statusEnvio, $statusRecebimento);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['historicosEnvioDTO'],
                "lastRow" => $dados['resultado']['qtdTotalHistoricosEnvio']
            ));
        } else if ($dados['status'] == 404) {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }

    public function cadastrarComandoPorCliente(){
        $POSTFIELDS = array(
            'idCliente' => $this->input->post('idCliente'),
            'idPerfil' => $this->input->post('idPerfil'),
            'numConfig' => $this->input->post('numConfig')
        );

        $dados = post_cadastrarComandoPorCliente($POSTFIELDS);
        echo json_encode($dados);
    }

    public function cadastrarComandoPredefinido(){
        $POSTFIELDS = array(
            'idCliente' => $this->input->post('idCliente'),
            'idPerfil' => $this->input->post('idPerfil'),
            'serial' => $this->input->post('serial'),
            'numConfig' => $this->input->post('numConfig')
        );

        $dados = post_cadastroComandoPredefinido($POSTFIELDS);
        
        echo json_encode($dados);
    }

    public function buscarUltimaConfiguracao() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $serial = $this->input->post('serial');
        $idPerfil = $this->input->post('idPerfil');
        $idCliente = $this->input->post('idCliente');
        $statusEnvio = $this->input->post('statusEnvioBusca');
        $statusRecebimento = $this->input->post('statusRecebimentoBusca');

        $startRow++;

        $dados = get_buscarUltimaConfiguracao($idCliente, $serial, $idPerfil, $startRow, $endRow, $statusEnvio, $statusRecebimento);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['historicosEnvioDTO'],
                "lastRow" => $dados['resultado']['qtdTotalHistoricosEnvio']
            ));
        } else if ($dados['status'] == 404) {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }

    public function clientesSelect2() {
        $itemInicio = $this->input->post('itemInicio');
        $itemFim = $this->input->post('itemFim');
        $nome = $this->input->post('searchTerm');
        $id = $this->input->post('id');

        $dados = get_clientesGerais($itemInicio, $itemFim, $nome, $id);

        echo json_encode($dados);
    }
}