<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Dashboard extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
		$this->load->helper('util_ocr_helper');
	}

    // Paginas
	public function index()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/OCR/Dashboard'));

		$dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/Dashboard');
		$this->load->view('fix/footer_NS');   
	}

    public function getMetricas() {
        $results = array();

        $dados = get_contagemPlacasClassificadasBlackList();
        if ($dados['status'] == '200') {
            $results['contagemHotList'] = $dados['resultado']['contagemBlackList'];
        }

        $dados = get_contagemPlacasClassificadasWhiteList();
        if ($dados['status'] == '200') {
            $results['contagemColdList'] = $dados['resultado']['contagemWhiteList'];
        }

        $dados = get_contagemVeiculosEngajados();
        if ($dados['status'] == '200') {
            $results['contagemVeiculosEngajados'] = $dados['resultado']['contagemEventoTrackerOCR'];
        }

        $dados = get_contagemPlacasComAlertas();
        if ($dados['status'] == '200') {
            $results['contagemPlacasComAlertas'] = $dados['resultado']['contagemPlacasWhiteList'] + $dados['resultado']['contagemPlacasBlackList'];
        }

        $primeiro_dia = new DateTime('first day of this month');
        $dia_atual = new DateTime('today');

        $primeiro_dia_formatado = $primeiro_dia->format('d/m/Y');
        $dia_atual_formatado = $dia_atual->format('d/m/Y');

        $dados = get_contagemPlacasLidasMês($primeiro_dia_formatado, $dia_atual_formatado);
        if ($dados['status'] == '200') {
            $results['contagemPlacasLidasMes'] = $dados['resultado']['contagemPlacasLidasMes'];
        }

        echo json_encode($results);

    }

    function buscarAlertasColdListPorPeriodo() {
        $periodo = $this->input->post('periodo');

        $dados = get_AlertasColdListPorPeriodo($periodo);

        echo json_encode($dados);
    }

    function buscarAlertasHotListPorPeriodo() {
        $periodo = $this->input->post('periodo');

        $dados = get_AlertasHotListPorPeriodo($periodo);

        echo json_encode($dados);
    }

	public function buscarEventosHotList(){
        $dados = get_EventosHotListTop50();

        echo json_encode($dados);
    }

    public function buscarEventosHotListByPlate(){
        $options = $this->input->post('options');
        $placa = isset($options['placa']) ? $options['placa'] : null;

        $dados = get_EventosHotListByPlate($placa);

        echo json_encode($dados);
    }

    public function buscarPlacaEventosHotListByPlate(){
        $placa = $this->input->post('placa');

        $dados = get_PlacaEventosHotList($placa);

        echo json_encode($dados);
    }

	public function buscarEventosColdList(){

        $dados = get_EventosColdListTop50();

        if ($dados['status'] == '200') {
            foreach ($dados['resultado'] as &$row) {
                $row['quantidadeEventos'] = $row['contagem'];
            }
        }

        echo json_encode($dados);
    }

    public function buscarEventosColdListByPlate(){
        $options = $this->input->post('options');
        $placa = isset($options['placa']) ? $options['placa'] : null;

        $dados = get_EventosColdListByPlate($placa);

        echo json_encode($dados);
    }

    public function buscarPlacaEventosColdListByPlate(){
        $placa = $this->input->post('placa');

        $dados = get_PlacaEventosColdList($placa);

        echo json_encode($dados);
    }

    public function buscarVeiculosMonitorados(){
        $dados = get_VeiculosMonitoradosTop50();

        echo json_encode($dados);
    }

    public function buscarVeiculosMonitoradosByPlate(){
        $options = $this->input->post('options');
        $placa = isset($options['placa']) ? $options['placa'] : null;

        $dados = get_VeiculosMonitoradosByPlate($placa);

        echo json_encode($dados);
    }
    public function buscarPlacaVeiculosMonitoradosByPlate(){
        $placa = $this->input->post('placa');

        $dados = get_PlacaVeiculosMonitorados($placa);

        echo json_encode($dados);
    }

	
	public function buscarAlertasPlacasTop50(){
        $dados = get_EventosPlacasAlertasTop50();

        echo json_encode($dados);
    }

    public function buscarPlacasAlertasByPlate(){
        $options = $this->input->post('options');
        $placa = isset($options['placa']) ? $options['placa'] : null;

        $dados = get_EventosPlacasAlertasByPlate($placa);

        echo json_encode($dados);
    }
    public function buscarAlertasByPlate(){
        $placa = $this->input->post('placa');

        $dados = get_PlacaEventosPlacasAlertas($placa);

        echo json_encode($dados);
    }

	public function buscarAlertasPlacasMensalTop50(){
        $dados = get_EventosPlacasAlertasMensalTop50();

        echo json_encode($dados);
    }

    public function buscarPlacasAlertasMensalByPlate(){
        $options = $this->input->post('options');
        $placa = isset($options['placa']) ? $options['placa'] : null;

        $dados = get_EventosPlacasAlertasMensalByPlate($placa);

        echo json_encode($dados);
    }

    public function buscarAlertasMensalByPlate(){
        $placa = $this->input->post('placa');

        $dados = get_PlacaEventosPlacasAlertasMensal($placa);

        echo json_encode($dados);
    }
	

    function buscarDadosEventosPlacasServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $placa = $this->input->post('placa');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');
        $tipoMatch = (int) $this->input->post('tipoMatch');

        $startRow++;

        $dados = get_EventosPlacasPaginated($placa, $startRow, $endRow, $dataInicial, $dataFinal, $tipoMatch);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['eventosMatch'],
                "lastRow" => $dados['resultado']['qtdTotalEventos']
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }

    }

    public function buscaEventosPlacasByID(){
        $id =  $this->input->post('id');
        $dados = get_EventosPlacasByID($id);

        echo json_encode($dados);
    }
}