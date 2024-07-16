<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

ini_set('display_errors', 1);

class Firmware extends CI_Controller
{
    public $enderecosConsultados = [];

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->helper('util_firmware_helper');
    }

    // Paginas
    public function index()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Firmware/Firmware'));

        $_SESSION['menu_firmware'] = 'historicoEnvio';

        $dados['titulo'] = lang('firmware_lista');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('firmware/firmware');
        $this->load->view('fix/footer_NS');
    }

    public function Dashboard()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Firmware/Firmware/Dashboard'));

        $dados['titulo'] = lang('firmware_dashboard');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('firmware/dashboard');
        $this->load->view('fix/footer_NS');
    }

    // FILTROS

    public function buscarFirmwareCadastrados()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $descricao = $this->input->post('descricao');
        $versao = $this->input->post('versao');


        $startRow++;

        $dados = get_cadastrosPaginado($startRow, $endRow, $descricao, $versao);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['firmwares'],
                "lastRow" => $dados['resultado']['qtdTotalFirmware']
            ));
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Firmwares não eontrado!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['mensagem'],
            ));
        }
    }

    public function buscarTecnologiasCadastradas()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $nome = $this->input->post('nome');

        $startRow++;

        $dados = get_tecnologias($startRow, $endRow, $nome);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['tecnologias'],
                "lastRow" => $dados['resultado']['qtdTotalTecnologias']
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

    public function buscarModelosByIdTecnologia()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $idTecnologia = (int) $this->input->post('idTecnologia');

        $startRow++;

        $dados = get_tecnologiasById($startRow, $endRow, $idTecnologia);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['modelos'],
                "lastRow" => $dados['resultado']['qtdTotalModelos']
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

    public function cadastrarRegra()
    {
        $POSTFIELDS = json_decode(file_get_contents('php://input'), true);
        $dados = post_cadastrarRegra($POSTFIELDS);

        echo json_encode($dados);
    }

    public function cadastrarAssociacao()
    {
        $POSTFIELDS = array(
            'idFirmwareInicial' => (int) $this->input->post('idFirmwareInicial'),
            'idFirmwareProximo' => (int) $this->input->post('idFirmwareProximo'),
        );

        $dados = post_cadastrarAssociacao($POSTFIELDS);

        echo json_encode($dados);
    }
    public function atualizarRegra()
    {
        $POSTFIELDS = json_decode(file_get_contents('php://input'), true);
        $dados = put_atualizarRegra($POSTFIELDS);

        echo json_encode($dados);
    }

    public function buscarRegrasCadastradas()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $idCliente = (int) $this->input->post('idCliente');
        $descricao = $this->input->post('descricao');

        $startRow++;

        $dados = get_RegrasCadastradas($startRow, $endRow, $idCliente, $descricao);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['RegrasDeEnvio'],
                "lastRow" => $dados['resultado']['qtdRegraEnviada']
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
    public function buscarRegrasCadastradasById()
    {
        $id = (int) $this->input->post('id');

        $dados = get_RegrasCadastradasById($id);
        echo json_encode($dados);
    }

    public function deletarRegra()
    {
        $POSTFIELDS = array(
            'id' => (int) $this->input->post('id'),
            'status' => (int)$this->input->post('status')
        );

        $dados = patch_alterarStatusRegra($POSTFIELDS);
        echo json_encode($dados);
    }

    public function modeloSeletc2()
    {
        $idTecnologia = (int) $this->input->post('idTecnologia');

        $dados = get_ModelosByTecnologia($idTecnologia);
        echo json_encode($dados);
    }

    public function tecnologiasSelect2()
    {
        $dados = get_tecnologiasSelect();
        echo json_encode($dados);
    }

    public function clientesSelect2()
    {
        $itemInicio = $this->input->post('itemInicio');
        $itemFim = $this->input->post('itemFim');
        $nome = $this->input->post('searchTerm');
        $id = $this->input->post('id');

        $dados = get_clientes($itemInicio, $itemFim, $nome, $id);
        echo json_encode($dados);
    }

    public function detalhesFirmware()
    {
        $idFirmware = (int) $this->input->post('idFirmware');

        $dados = get_detalhesFirmware($idFirmware);
        echo json_encode($dados);
    }

    public function buscarHistoricoEnvio()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $cliente = $this->input->post('cliente');
        $serial = $this->input->post('serial');
        $dataHoraEnvioInicio = $this->input->post('dataHoraEnvioInicio');
        $dataHoraEnvioFim = $this->input->post('dataHoraEnvioFim');

        $startRow++;

        $dados = get_historicoEnvio($startRow, $endRow, $cliente, $serial, $dataHoraEnvioInicio, $dataHoraEnvioFim);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['envioFirmware'],
                "lastRow" => $dados['resultado']['qtdTotalEnvioFirmware']
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

    public function detalhesEnvio()
    {
        $id = (int) $this->input->post('id');
        $dados = get_detalhesHistorico($id);
        echo json_encode($dados);
    }

    public function buscarFirmwaresParaAssociar()
    {
        $id = (int) $this->input->post('id');

        $dados = get_firmwaresParaAssociar($id);
        echo json_encode($dados);
    }

    public function buscarIdAssociado()
    {
        $id = (int) $this->input->post('id');

        $dados = get_idFirmwareAssociado($id);
        echo json_encode($dados);
    }

    //REQUISIÇÕES:

    // CRUD FIRMWARE
    public function cadastrarFirmware()
    {

        $POSTFIELDS = array(
            'versao' => $this->input->post('versao'),
            'liberacao' => $this->input->post('liberacao'),
            'idHardware' => (int)$this->input->post('idHardware'),
            'idModelo' => (int)$this->input->post('idModelo'),
            'descricao' => $this->input->post('descricao'),
            'nomeArquivoFirmware' => $this->input->post('nomeArquivoFirmware'),
            'arquivoFirware' => $this->input->post('arquivoFirmware'),
            'nomeReleaseNotes' => $this->input->post('nomeReleaseNotes'),
            'releaseNotes' => $this->input->post('releaseNotes')
        );


        $dados = post_cadastrarFirmware($POSTFIELDS);
        echo json_encode($dados);
    }

    public function atualizarFirmware()
    {

        $POSTFIELDS = array(
            'id' => $this->input->post('id'),
            'versao' => $this->input->post('versao'),
            'liberacao' => $this->input->post('liberacao'),
            'idHardware' => (int)$this->input->post('idHardware'),
            'idModelo' => (int)$this->input->post('idModelo'),
            'descricao' => $this->input->post('descricao'),
            'nomeArquivoFirmware' => $this->input->post('nomeArquivoFirmware'),
            'arquivoFirware' => $this->input->post('arquivoFirmware'),
            'nomeReleaseNotes' => $this->input->post('nomeReleaseNotes'),
            'releaseNotes' => $this->input->post('releaseNotes')
        );


        $dados = put_atualizarFirmware($POSTFIELDS);
        echo json_encode($dados);
    }

    public function deletarFirmware()
    {
        $POSTFIELDS = array(
            'id' => (int) $this->input->post('id'),
            'status' => (int)$this->input->post('status')
        );

        $dados = patch_alterarStatusFirmware($POSTFIELDS);
        echo json_encode($dados);
    }

    public function inativarAssociacao()
    {
        $POSTFIELDS = array(
            'idFirmwareInicial' => (int) $this->input->post('idFirmwareInicial'),
            'status' => (int)$this->input->post('status')
        );

        $dados = patch_inativarAssociacao($POSTFIELDS);
        echo json_encode($dados);
    }

    // CRUD MODELOS

    public function cadastrarModelo()
    {
        $POSTFIELDS = array(
            'nome' => $this->input->post('nome'),
            'idTecnologia' => (int) $this->input->post('idTecnologia'),
        );

        $dados = post_cadastrarModelo($POSTFIELDS);
        echo json_encode($dados);
    }

    public function deletarModelo()
    {
        $POSTFIELDS = array(
            'nome' => $this->input->post('nome'),
            'idTecnologia' => (int) $this->input->post('idTecnologia'),
        );

        $dados = post_cadastrarModelo($POSTFIELDS);
        echo json_encode($dados);
    }

    public function editarModeloCadastrado()
    {
        $POSTFIELDS = array(
            'id' => (int) $this->input->post('id'),
            'nome' => $this->input->post('nome'),
        );

        $dados = put_editarModelo($POSTFIELDS);
        echo json_encode($dados);
    }

    public function alterarStatusModelo()
    {
        $POSTFIELDS = array(
            'id' => (int) $this->input->post('id'),
            'status' => (int)$this->input->post('status')
        );

        $dados = patch_alterarStatus($POSTFIELDS);
        echo json_encode($dados);
    }

    // CRUD TECNOLOGIA
    public function cadastrarTecnologiaLote()
    {
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);

        $dados = post_cadastrarTecnologia($dadosRecebidos);
        echo json_encode($dados);
    }

    public function deletarTecnologia()
    {
        $POSTFIELDS = array(
            'id' => $this->input->post('id'),
            'status' => (int) $this->input->post('status')
        );

        $dados = patch_excluirTecnologia($POSTFIELDS);

        echo json_encode($dados);
    }

    public function editarTecnologia()
    {
        $POSTFIELDS = array(
            'id' => (int) $this->input->post('id'),
            'nome' => $this->input->post('nome'),
        );

        $dados = post_editarTecnologia($POSTFIELDS);
        echo json_encode($dados);
    }

    // REQUISIÇÕES DO DASHBOARD


    public function getMetricas()
    {
        $results = array();

        $dados = get_SeriaisDesatualizados();
        if ($dados['status'] == '200') {
            $results['totalSeriaisDesatualizados'] = $dados['resultado']['contagem'];
        }

        $dados = get_ClientesDesatualizados();
        if ($dados['status'] == '200') {
            $results['totalSeriaisVersaoAnterior'] = $dados['resultado']['qtdTotal'];
        }

        $dados = get_FirmwaresCadastrados();
        if ($dados['status'] == '200') {
            $results['totalFirmwareCadastrado'] = $dados['resultado']['quantidade'];
        }

        $dados = get_SeriaisAtualizados();
        if ($dados['status'] == '200') {
            $results['totalSeriaisAtualizados'] = $dados['resultado']['quantidade'];
        }

        $dados = get_atualizacaoDesabilitada();
        if ($dados['status'] == '200') {
            $results['totalAtualizacaoDesabilitada'] = $dados['resultado']['quantidade'];
        }

        $dados = get_regraDia();
        if ($dados['status'] == '200') {
            $results['totalRegraDia'] = $dados['resultado']['quantidade'];
        }

        $dados = get_regraHora();
        if ($dados['status'] == '200') {
            $results['totalRegraHora'] = $dados['resultado']['quantidade'];
        }

        echo json_encode($results);
    }

    function getDadosCharts()
    {
        $results = array();

        $dados = get_cadastroFirmware30Dias();
        if ($dados['status'] == '200') {
            $results['firmwareCadastrados30Dias'] = $dados['resultado']['qtdTotal'];
        }

        $dados = get_cadastroFirmware60Dias();
        if ($dados['status'] == '200') {
            $results['firmwareCadastrados60Dias'] = $dados['resultado']['qtdTotal'];
        }

        $dados = get_cadastroFirmware90Dias();
        if ($dados['status'] == '200') {
            $results['firmwareCadastrados90Dias'] = $dados['resultado']['qtdTotal'];
        }

        $dados = get_AtualizacoesFirmwares7Dias();
        if ($dados['status'] == '200') {
            $results['atualizacao7Dias'] = $dados['resultado']['qtdTotal'];
        }

        $dados = get_AtualizacoesFirmwares15Dias();
        if ($dados['status'] == '200') {
            $results['atualizacao15Dias'] = $dados['resultado']['qtdTotal'];
        }

        $dados = get_AtualizacoesFirmwares30Dias();
        if ($dados['status'] == '200') {
            $results['atualizacao30Dias'] = $dados['resultado']['qtdTotal'];
        }

        echo json_encode($results);
    }

    function getFirmware30Dias()
    {

        $dados = get_cadastroFirmware30Dias();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['cadastroFirmware'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getFirmware60Dias()
    {

        $dados = get_cadastroFirmware60Dias();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['cadastroFirmware'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getFirmware90Dias()
    {

        $dados = get_cadastroFirmware90Dias();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['cadastroFirmware'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getRegraAtualizacoesDesabilitadas()
    {

        $dados = get_RegraAtualizacoesDesabilitadas();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['clientesAtualizacoesDesabilitadas'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getRegraDia()
    {

        $dados = get_RegraDiaEspecifico();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['clientesAtualizacoesDiasEspecificos'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getRegraHorario()
    {

        $dados = get_RegraHorarioEspecifico();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['clientesAtualizacoesHorarioEspecificos'],
                "lastRow" => $dados['resultado']['qtdTotal']
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


    function getSeriaisDesatualizados()
    {

        $dados = get_SeriaisDesatualizadosPaginado();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['seriaisDesatualizados'],
                "lastRow" => $dados['resultado']['contagem']
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

    function getAtualizados7Dias()
    {

        $dados = get_AtualizacoesFirmwares7Dias();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['historicoEnvioFirmwareDTO'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getAtualizados15Dias()
    {

        $dados = get_AtualizacoesFirmwares15Dias();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['historicoEnvioFirmwareDTO'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getAtualizados30Dias()
    {

        $dados = get_AtualizacoesFirmwares30Dias();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['historicoEnvioFirmwareDTO'],
                "lastRow" => $dados['resultado']['qtdTotal']
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
    function getSeriaisAtualizados()
    {
        $itemInicio = (int) $this->input->post('startRow');
        $itemFim = (int) $this->input->post('endRow');

        $itemInicio++;

        $dados = get_seriaisAtualizadoPaginado($itemInicio, $itemFim);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['seriaisAtualizados'],
                "lastRow" => $dados['resultado']['qtdTotal']
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

    function getClientesDesatualizados()
    {

        $dados = get_ClientesDesatualizados();

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['clientesDesatualizados'],
                "lastRow" => $dados['resultado']['qtdTotal']
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
}
