<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comandos_isca extends CI_Controller {
    public function __construct(){
		parent::__construct();
		$this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('iscas/comandos_iscas','comandos');
        $this->load->model('iscas/iscas');
        $this->load->model('mapa_calor');
        $this->load->helper('util_iscas_helper');

        // $this->load->model('cliente');
    }
    
    
    public function envio_comandos(){
        // pr($this->session->userdata('log_admin'));die();
        
        if($this->auth->is_allowed_block('cad_comandos')){
            $dados['titulo'] = "Envio de Comandos";
            $dados['comandos'] = $this->get_comandos_suntech();
            $dados['load'] = array('ag-grid', 'select2', 'mask');
        }
        $this->mapa_calor->registrar_acessos_url(site_url('/iscas/comandos_isca/envio_comandos'));
		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('iscas/envio_comando_iscas');
		$this->load->view('fix/footer_NS');
    }
    
    /** Envia o comando para um serial de acordo com a configuração escolhida
     * @param String Serial do dispositivo que quer enviar o comando
     * @param Array Dados com configurações referentes ao comando
    * @return  String Resposta de sucesso ou não ao enviar o comando
    */
    private function enviarComandoAoSerial($serial, $dados, $dadosLogin) {
        if (is_string($serial)) {
            $serialSemPrefixo = ltrim($serial, "I");
        } else {
            $serialSemPrefixo = $serial;
        }

        $resposta_comando = null;

        if($dados['tipoComando'] == 'CONFIG_CONEXAO'){
            $stringComando = $this->comandoConfigurarConexaoVeicular($dados, $serialSemPrefixo);
            $descricao_comando = 'Configurar Conexão';
        }
        elseif($dados['tipoComando'] == 'REDE_COLAB_TEMP'){
            $stringComando = $this->comandoRedeColaborativaTemporaria($dados, $serialSemPrefixo);
            $descricao_comando = 'Rede Colaborativa / Temperatura';
        }
        elseif($dados['tipoComando'] == 'PARAM_ENVIO') {
            $stringComando = $this->comandoParametrosEnvioRedeColaborativa($dados);
            $descricao_comando = 'Parâmetros de Envio - Rede Colaborativa';
        }
        elseif($dados['tipoComando'] == 'START_REDE_COLAB'){
            $stringComando = 'INICIAR_EMERGENCIA'; 
            $descricao_comando = 'Iniciar Emergência';
        }
        elseif($dados['tipoComando'] == 'STOP_REDE_COLAB'){
            $stringComando = 'PARAR_EMERGENCIA'; 
            $descricao_comando = 'Parar Emergência';
        }
        elseif($dados['tipoComando'] == 'SOLICITAR_ICCID'){
            $stringComando = 'CCID';
            $descricao_comando = 'Solicitar ICCID';
        }
        elseif($dados['tipoComando'] == 'SOLICITAR_CONFIG'){
            $stringComando = "ST410CMD;{$serialSemPrefixo};02;Preset";
            $descricao_comando = 'Solicitar Configuração';
        }
        elseif($dados['tipoComando'] == 'SOLICITAR_POSICAO'){
            $stringComando = "ST410CMD;{$serialSemPrefixo};02;StatusReq";
            $descricao_comando = 'Solicitar Posição';
        }
        elseif($dados['tipoComando'] == 'SOLICITAR_VERCAO_FIRMWARE'){
            $stringComando = "ST410CMD;{$serialSemPrefixo};02;ReqVer";
            $descricao_comando = 'Solicitar Versão Firmware';
        }
       
        $insert = array(
            'cmd_eqp' => $serial,
            'cmd_comando' => $stringComando,
            'descricao_comando' => $descricao_comando,
            'id_usuario' => $dadosLogin['user'],
            'origem_comando' => '1',
        );

        // pr($insert);die();
        
        $resposta = $this->comandos->enviarComandoIsca($insert);
        
        return $resposta;
    }

    function get_comandos_suntech() {
        $cmd = array(
            ['nome' => 'Bloquear', 'code' => 'BLOQUEIO'],
            ['nome' => 'Desbloquear', 'code' => 'DESBLOQUEIO'],
            ['nome' => 'Habilitar Antifurto', 'code' => 'HABANTIFURTO'],
            ['nome' => 'Desabilitar Antifurto', 'code' => 'DESANTIFURTO'],
            ['nome' => 'DPA', 'code' => 'CONFDPA'],
        );

        return $cmd;
    }


    function ajax_envio_comandos_massa() {
        // Dados enviados do comando
        $dados = $this->input->post();
        $selectAll = $this->input->post('selectAll');
        $clienteId = $this->input->post('cliente');
        
        $dadosLogin = $this->session->userdata('log_admin');

        $seriaisCliente = $this->iscasPorCliente($clienteId);

        $serials = $selectAll == "true" ? $seriaisCliente : $this->input->post('serial');

        $serials = array_unique($serials);

        foreach($serials as $serial) {

            if (in_array($serial, $seriaisCliente)) {

                $resposta = $this->enviarComandoAoSerial($serial, $dados, $dadosLogin);

                // $resposta = 1;
                if ($resposta == 1) {
                    $respostas[] = array('serial' => $serial, 'status' => true, 'msg' => 'Comando enviado com sucesso.');
                } else {
                    $respostas[] = array('serial' => $serial, 'status' => false, 'msg' => 'Erro ao enviar o comando.');
                }

            } else {
                $respostas[] = array('serial' => $serial, 'status' => false, 'msg' => 'Serial não pode ser associado ao cliente.');
            }
            
        }

        echo json_encode($respostas);
    }

    private function iscasPorCliente($idCliente)
    {
        $lista = [];

        if($idCliente)
        {
            $where['id_cliente'] = $idCliente;
            $lista = $this->iscas->getIscas($where);

            $results = [];
            foreach($lista as $isca) {
                $results[] = $isca['serial'];
            }

            return $results;
        }
    }

    function ajax_envio_comando(){

        // Dados enviados do comando
        $dados = $this->input->post();
        // pr($dados);die();
        $dadosLogin = $this->session->userdata('log_admin');

        $serial = $this->input->post('serial');
        
        $resposta = $this->enviarComandoAoSerial($serial, $dados, $dadosLogin);
        
        // $resposta = 1;
        if ($resposta == 1) {
            
            echo json_encode(array('status' => true, 'msg' => 'Comando enviado com sucesso.'));
            
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Erro ao enviar o comando.'));

        }
    

    }

    /**
     * @param Array Dados enviados na requisição
     * @return String String do comando
     */
    function comandoConfigurarConexaoVeicular($dados, $serial){
        

        $serialSemPrefixo = $serial;
        $auth = $dados['auth'];
        $apn = $dados['apn'];
        $user_id = $dados['user_id'];
        $password = $dados['password'];
        $server_ip_1 = $dados['server_ip_1'];
        $server_port_1 = $dados['server_port_1'];
        $server_ip_2 = $dados['server_ip_2'];
        $server_port_2 = $dados['server_port_2'];
        
        
        return "ST410NTW;{$serialSemPrefixo};02;{$auth};{$apn};{$user_id};{$password};{$server_ip_1};{$server_port_1};{$server_ip_2};{$server_port_2};;;";
        
    }

    function comandoRedeColaborativaTemporaria($dados, $serial){

        $ligaDesliga = $dados['ligaDesliga'];
        $larguraBanda = $dados['larguraBanda'];
        $canalRF = $dados['canalRF'];
        $sensorTemperatura = $dados['sensorTemperatura'];
        $serialSemPrefixo = $serial;
        

        return "ST400NPT;{$serialSemPrefixo};02;1;1;{$ligaDesliga};{$larguraBanda};{$canalRF};{$sensorTemperatura};0;;;;;";
        
    }

    function comandoParametrosEnvioRedeColaborativa($dados){
        $intervaloRX = $dados['intervaloRX'];
        $periodoRX = $dados['periodoRXNormal'];
        $intervaloGPRSNormal = $dados['intervaloGPRSNormal'];
        $intervaloTX = $dados['intervaloTX'];
        $numeroTransm = $dados['numeroTransm'];
        $intervaloGPRSEmerg = $dados['intervaloGPRSEmerg'];
        $periodoRXEmerg = $dados['periodoRXEmerg'];
        $tempoSleep = $dados['tempoSleep'];
        $tempoJammer = $dados['tempoJammer']; // Deve sempre ser 0 segundo instruçoes do card 7OLP3zVU        

        return "CONFIGTEMPOS:{$intervaloRX},{$periodoRX},{$intervaloGPRSNormal},{$intervaloTX},{$numeroTransm},{$intervaloGPRSEmerg},{$periodoRXEmerg},{$tempoSleep},0";        
    }
    
    public function index(){

        $dados['titulo'] = "Relatório de Comandos";
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/iscas/comandos_isca'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/iscas/relatorioComandosIsca');
        $this->load->view('fix/footer_NS');
        
    }

    public function relatorioComandos(){

        $itemInicio = (int) $this->input->post('itemInicio');
        $itemFim = (int) $this->input->post('itemFim');
        $dataInicio = $this->input->post('dataInicio');
        $dataFim = $this->input->post('dataFim');
        $horaInicio = $this->input->post('horaInicio');
        $horaFim = $this->input->post('horaFim');
        $serial =  (int)$this->input->post('serial');
        $status = $this->input->post('status');
       

        $itemInicio++;

        $dados = get_Comandos_paginado($itemInicio, $itemFim,$dataInicio, $dataFim, $horaInicio, $horaFim, $serial, $status);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['comandos'],
                "lastRow" => $dados['resultado']['qtdTotalComandos']
            ));
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Dados não encontrados!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }
}

