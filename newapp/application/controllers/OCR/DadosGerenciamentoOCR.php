<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

ini_set('display_errors', 1);

class DadosGerenciamentoOCR extends CI_Controller
{
    public $enderecosConsultados = [];
    
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
		$this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR'));

		$dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        if (!isset($_SESSION['menu_ocr'])) {
            $_SESSION['menu_ocr'] = 'DadosGerenciamentoOCR';
        }
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/DadosGerenciamento');
		$this->load->view('fix/footer_NS');   
	}

    public function addSession($tela, $aba) {
        $_SESSION[$tela] = $aba;
    }

    public function Cadastros()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR/Cadastros'));
        $dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        if (!isset($_SESSION['cadastro_ocr'])) {
            $_SESSION['cadastro_ocr'] = 'AlertasEmail';
        }

        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/Cadastros');
		$this->load->view('fix/footer_NS');   
    }

    public function LeituraDePlacas()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR/LeituraDePlacas'));
        $_SESSION['menu_ocr'] = 'DadosGerenciamentoOCR';

		$dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/DadosGerenciamento');
		$this->load->view('fix/footer_NS');   
	}
    
	public function Blacklist()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR/Blacklist'));
        $_SESSION['cadastro_ocr'] = 'Blacklist';

		$dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/Cadastros');
		$this->load->view('fix/footer_NS');   
	}

    public function AlertasEmail()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR/AlertasEmail'));
        $_SESSION['cadastro_ocr'] = 'AlertasEmail';

		$dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/Cadastros');
		$this->load->view('fix/footer_NS');   
	}

    public function EventosPlacas()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR/EventosPlacas'));
        $_SESSION['menu_ocr'] = 'EventosPlacas';

		$dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/DadosGerenciamento');
		$this->load->view('fix/footer_NS');   
	}

    public function Whitelist()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR/Whitelist'));
        $_SESSION['cadastro_ocr'] = 'Whitelist';

		$dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/Cadastros');
		$this->load->view('fix/footer_NS');   
	}

    public function ProcessamentoLote()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/OCR/DadosGerenciamentoOCR/ProcessamentoLote'));
        $dados['titulo'] = lang('ocr');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $_SESSION['cadastro_ocr'] = 'ProcessamentoLote';

        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('OCR/Cadastros');
		$this->load->view('fix/footer_NS');   
    }

    //Buscas
    public function buscarDados(){

        $dataInicial =  $this->input->post('dataInicial');
        $dataFinal =  $this->input->post('dataFinal');
        $placa =  $this->input->post('placa');
        $umMes = new DateInterval('P1M');
        $umMes->invert = 1;

        if(!$dataInicial){
            $dataInicial = new DateTime();
            $dataInicial->add($umMes);
            $dataInicial = $dataInicial->format('d/m/Y');
        }else{
            $dataInicial = str_replace("-", "/", $dataInicial);
			$dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if(!$dataFinal){
            $dataFinal = date('d/m/Y');
        }else{
            $dataFinal = str_replace("-", "/", $dataFinal);
			$dataFinal = date('d/m/Y', strtotime($dataFinal));
        }
        

        $dados = get_DadosGerenciamento($placa, $dataInicial, $dataFinal);

        echo json_encode($dados);
    }

    public function buscarDadosTop50(){

        $dados = get_DadosGerenciamentoTop50();

        echo json_encode($dados);
    }

    public function buscarBlackList(){
        $placa =  $this->input->post('placa');
        $cliente =  $this->input->post('cliente');
        
        $dados = get_BlackList($placa, $cliente);
        echo json_encode($dados);
    }

    public function buscarBlackListTop50(){
        $dados = get_BlackListTop50();
        echo json_encode($dados);
    }

    public function buscarBlacklistServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $placa = $this->input->post('placa');
        $cliente = $this->input->post('cliente');
        $status = $this->input->post('status');

        $startRow++;

        $dados = get_BlacklistPaginated($placa, $cliente, $status, $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['blackLists'],
                "lastRow" => $dados['resultado']['qtdTotalBlackLists']
            ));
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Cliente ou placa não encontrados!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['mensagem'],
            ));
        }
        
    }

    public function buscarWhitelist(){
        $placa =  $this->input->post('placa');
        $cliente =  $this->input->post('cliente');
        
        $dados = get_Whitelist($placa, $cliente);
        foreach ($dados['resultado'] as &$resultado) {
            $resultado['razaoSocial'] = $resultado['razao_social'];
            $resultado['tipoOcorrencia'] = $resultado['tipo_ocorrencia'];
        }
        echo json_encode($dados);
    }

    public function buscarWhitelistTop50(){
        $dados = get_WhitelistTop50();
        echo json_encode($dados);
    }


    public function buscarEventosPlacas(){
        $dados = get_EventosPlacas();

        echo json_encode($dados);
    }

    public function filtrarEventosPlacas(){
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');
        $placa = $this->input->post('placa');
        
        if ($dataInicial) {
            $dataInicial = $dataInicial.'%2000:00:00';

        }

        if ($dataFinal) {
            $dataFinal = $dataFinal.'%2023:59:59';
        }

        $dados = get_EventosPlacasByPlacaData($placa, $dataInicial, $dataFinal);

        echo json_encode($dados);
    }

    public function atualizarStatusEvento(){
        $POSTFIELDS = array(
            'id' => $this->input->post('id'),
            'status' => $this->input->post('status'),
            'motivo' => $this->input->post('motivo'),
        );

        $dados = patch_StatusEventosMatchOCR($POSTFIELDS);

        echo json_encode($dados);
    }

    public function buscarDadosGerenciamentoByID(){
        $id =  $this->input->post('id');
        $dados = get_DadosGerenciamentoByID($id);

        echo json_encode($dados);
    }

    public function buscarBlacklistByID(){
        $this->load->model('usuario');
        $id =  $this->input->post('id');
        $dados = get_BlackListByID($id);
        if($dados['status'] == 200){
            $user =  $this->usuario->get_usuarioByID($dados['resultado']['id_usuario_importacao']);
            $dados['resultado']['nomeUser'] = $user[0]->nome;
        }

        echo json_encode($dados);
    }

    public function buscarWhitelistByID(){
        $this->load->model('usuario');
        $id =  $this->input->post('id');
        $dados = get_WhiteListByID($id);
        if($dados['status'] == 200){
            $user =  $this->usuario->get_usuarioByID($dados['resultado'][0]['idUsuarioImportacao']);
            $dados['resultado'][0]['nomeUser'] = $user[0]->nome;
        }

        echo json_encode($dados);
    }

    public function buscarAlertasEmailByID(){
        $id =  $this->input->post('id');
        $dados = get_AlertasEmailByID($id);
        echo json_encode($dados);
    }

    public function buscaEventosPlacasByID(){
        $id =  $this->input->post('id');
        $dados = get_EventosPlacasByID($id);

        echo json_encode($dados);
    }

    public function getUserId() {
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;
        echo json_encode(
            array('id' => $id_user)
        );
    }

    public function buscarWhitelistServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $placa = $this->input->post('placa');
        $idCliente = $this->input->post('idCliente');
        $status = $this->input->post('status');

        $startRow++;

        $dados = get_WhitelistPaginated($placa, $idCliente, $status, $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['whiteList'],
                "lastRow" => $dados['resultado']['qtdTotalEventos']
            ));
        } else if ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Cliente ou placa não encontrados!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
        
    }

    public function buscarPlacasWhitelistByAlerta() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $idAlerta = $this->input->post('idAlerta');

        $startRow++;

        $dados = get_WhitelistByAlertaEmail($idAlerta, $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['whiteList'],
                "lastRow" => $dados['resultado']['qtdTotalEventos']
            ));
        } else if ($dados['status'] == '404') {
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

    public function buscarPlacasBlacklistByAlerta() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $idAlerta = $this->input->post('idAlerta');

        $dados = get_BlacklistByAlertaEmail($idAlerta, $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['itensAssociados'],
                "lastRow" => $dados['resultado']['qtdTotalEventos']
            ));
        } else if ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'] = 'Este alerta não possui placas Hot list associadas',
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'] = 'Erro na solicitação',
            ));
        }
        
    }
    
    public function buscarDadosServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $placa = $this->input->post('placa');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');
        $tipoMatch = $this->input->post('tipo');

        $startRow++;

        if ($dataInicial) {
            $dataInicial = str_replace("-", "/", $dataInicial);
			$dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if ($dataFinal) {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $dados = get_DadosGerenciamentoPaginated($placa, $tipoMatch, $dataInicial, $dataFinal, $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['eventosTracker'],
                "lastRow" => $dados['resultado']['qtdTotalEventos']
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
        
    }

    public function buscarProcessamentoServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $status = $this->input->post('statusProcessamento');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');

        $startRow++;

        if ($dataInicial) {
            $dataInicial = str_replace("-", "/", $dataInicial);
			$dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if ($dataFinal) {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $dados = get_ProcessamentoPaginated($status, $dataInicial, $dataFinal, $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['acoes'],
                "lastRow" => $dados['resultado']['qtdTotalEventos']
            ));
        } else {
            echo json_encode(array(
                "status" => $dados['status'],
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
        
    }

    public function buscarAlertasEmailServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $email =  $this->input->post('email');
        $idCliente =  $this->input->post('cliente');

        $startRow++;

        $dados = get_AlertasEmailPaginated($email, $idCliente,  $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['alertaEmails'],
                "lastRow" => $dados['resultado']['qtdTotalAlertaEmails']
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => ($dados['resultado']['mensagem'] ? $dados['resultado']['mensagem'] : 'Falha ao buscar alertas de email. Verifique os campos e tente novamente'),
            ));
        }
    }

    public function buscarDadosEventosPlacasServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $rowGroupCols = $this->input->post('rowGroupCols');
        $groupKeys = $this->input->post('groupKeys');
        $placa = $this->input->post('placa');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');
        $tipoMatch = (int) $this->input->post('tipoMatch');
 
        $colunas = array(
            'id' => 'id',
            'serial' => 'serial',
            'placaLida' => 'placa_lida',
            'filePath' => 'file_path',
            'fileStartTime' => 'file_s_time',
            'fileEndTime' => 'file_e_time',
            'longitude' => 'longitude',
            'latitude' => 'latitude',
            'dhCadEvento' => 'dhcad_evento',
            'status' => 'status',
            'tipoMatch' => 'tipo_match',
            'bestPlate' => 'best_plate',
            'modelo' => 'modelo',
            'marca' => 'marca',
            'idCliente' => 'id_cliente',
            'endereco' => 'endereco'
        );
 
        $agrupar = [];
        $condicao = [];
 
        if ($rowGroupCols && count($rowGroupCols) > 0){
            $i = 0;
            foreach($rowGroupCols as $col) {
                $coluna = $colunas[$col['id']];
                if ($groupKeys && isset($groupKeys[$i])) {
                    if ($coluna == 'status') {
                        if ($groupKeys[$i] == 'Inserido') {
                            $condicao[] = 0;
                        } else if ($groupKeys[$i] == 'Visualizado') {
                            $condicao[] = 1;
                        } else if ($groupKeys[$i] == 'Tratado') {
                            $condicao[] = 2;
                        } else if ($groupKeys[$i] == 'Em Tratativa') {
                            $condicao[] = 3;
                        } else if ($groupKeys[$i] == 'Finalizado Evento Real') {
                            $condicao[] = 4;
                        } else if ($groupKeys[$i] == 'Finalizado Evento Falso') {
                            $condicao[] = 5;
                        } else {
                            $condicao[] = '';
                        }
                    } else if ($coluna == 'best_plate') {
                        if ($groupKeys[$i] == 'Melhor Placa') {
                            $condicao[] = 1;
                        } else {
                            $condicao[] = 0;
                        }
                    } else if ($coluna == 'tipo_match') {
                        if ($groupKeys[$i] == 'Hot List') {
                            $condicao[] = 1;
                        } else if ($groupKeys[$i] == 'Cold List') {
                            $condicao[] = 2;
                        } else {
                            $condicao[] = '';
                        }
                    } else {
                        if ($groupKeys[$i] == "" || $groupKeys[$i] == null || $groupKeys[$i] == 'Inexistente') {
                            $condicao[] = 'null';
                        } else {
                            $condicao[] = $groupKeys[$i];
                        }
                    }
                    
                }
                $agrupar[] = $coluna;
                $i++;
            }
        }
        
        foreach ($condicao as &$value) {
            $value = str_replace(' ', '%20', $value);
        }
 
        $diff = count($agrupar) - count($condicao);
        if (count($condicao) == 0 && count($agrupar) > 0) {
            $agrupar = [$agrupar[0]];
        } else if ($diff > 1) {
            $agrupar = [$agrupar[$diff-1]];
            $condicao = [];
        }
 
        $startRow++;
 
        if ($dataInicial) {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }
 
        if ($dataFinal) {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }
 
        $dados = get_EventosPlacasPaginatedAndGrupped($placa, $dataInicial, $dataFinal, $startRow, $endRow, implode(',', $condicao), implode(',', $agrupar), $tipoMatch);
 
        if ($dados['status'] == '200') {
            $eventos = array();
            $qtdEventos = $dados['resultado']['qtdTotalEventosMatch'];
            if (count($agrupar) > count($condicao)) {
                foreach ($dados['resultado']['eventosMatch'] as $row) {
                    $newRow = array();
                    $add = true;
                    foreach ($rowGroupCols as $col) {
                        $newRow[$col['field']] = $row[$col['field']];
                        if ($col['field'] == 'idCliente') {
                            $newRow['nome'] = $row['nome'];
                        }
                        if ($newRow[$col['field']] === '') {
                            $add = false;
                            $qtdEventos--;
                        }
                    }
                    if ($add) {
                        $eventos[] = $newRow;
                    }
                }
            } else {
                $eventos = $dados['resultado']['eventosMatch'];
            }
 
            echo json_encode(array(
                "success" => true,
                "rows" => $eventos,
                "lastRow" => $qtdEventos
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
        
    }

    //Posts
    public function adicionarBlacklist(){

        $POSTFIELDS = array(
            'id_cliente' => $this->input->post('seguradoraBlacklist'),
            'id_usuario_importacao' => $this->input->post('usuarioBlacklist'),
            'placa' => $this->input->post('placaBlacklist'),
            'chassi' => $this->input->post('chassiBlacklist'),
            'modelo' => $this->input->post('modeloBlacklist'),
            'marca' => $this->input->post('marcaBlacklist'),
            'cor' => $this->input->post('corBlacklist'),
            'tipo_ocorrencia' => is_numeric($this->input->post('ocorrenciaBlacklist')) ? $this->input->post('ocorrenciaBlacklist') : ''
        );
        
        $dados = post_BlackList($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarWhitelist(){

        $POSTFIELDS = array(
            'idCliente' => $this->input->post('seguradoraWhitelist'),
            'idUsuarioImportacao' => $this->input->post('usuarioWhitelist'),
            'placa' => $this->input->post('placaWhitelist'),
            'chassi' => $this->input->post('chassiWhitelist'),
            'modelo' => $this->input->post('modeloWhitelist'),
            'marca' => $this->input->post('marcaWhitelist'),
            'cor' => $this->input->post('corWhitelist'),
            'tipoOcorrencia' => is_numeric($this->input->post('ocorrenciaWhitelist')) ? $this->input->post('ocorrenciaWhitelist') : ''
        );

        
        $dados = post_Whitelist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarAlertasEmail() {
        $POSTFIELDS = array(
            'id_cliente' => $this->input->post('id_cliente'),
            'integra_css' => $this->input->post('integra_css'),
            'notifica_email' => $this->input->post('notifica_email'),
            'notifica_tela_alerta' => $this->input->post('notifica_tela_alerta'),
            'emails' => $this->input->post('emails')
        );

        $dados = post_AlertasEmail($POSTFIELDS);
        echo json_encode($dados);
    }
    
    public function adicionarImportacaoBlacklist(){

        $listaImportacaoBlacklist = json_decode($this->input->post('ListaImportacao'), true);

        $POSTFIELDS = array(
            'lista' => $listaImportacaoBlacklist
        );
        
        $dados = post_ImportarBlackList($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarAssociacaoAllBlacklist() {
        $POSTFIELDS = array(
            'idAlerta' => $this->input->post('idAlerta'),
            'idCliente' => $this->input->post('idCliente')
        );
        $dados = post_AssociarAllBlacklist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function desassociarAllBlacklist() {
        $POSTFIELDS = array(
            'idAlerta' => $this->input->post('idAlerta'),
            'idCliente' => $this->input->post('idCliente')
        );
        $dados = patch_DesassociarAllBlacklist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarAssociacaoBlacklist() {
        $POSTFIELDS = array(
            'idAlertaEmail' => $this->input->post('id_alerta_email'),
            'listaIdBlacklists' => $this->input->post('ids_blacklist')
        );

        $dados = post_AssociarBlackLists($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarAssociacaoLoteBlacklist() {
        $POSTFIELDS = array(
            'idCliente' => $this->input->post('id_cliente'),
            'idAlerta' => $this->input->post('id_alerta_email'),
            'listaPlacas' => implode(',', $this->input->post('ids_blacklist'))
        );

        $dados = post_AssociarLoteBlacklist($POSTFIELDS);
        echo json_encode($dados);
    }

    function desassociarLoteBlacklist() {
        $POSTFIELDS = array(
            'idCliente' => $this->input->post('id_cliente'),
            'idAlerta' => $this->input->post('id_alerta_email'),
            'listaPlacas' => implode(',', $this->input->post('id_blacklist'))
        );
        $dados = post_DesassociarLoteBlacklist($POSTFIELDS);
        echo json_encode($dados);
    }

    function desassociarLoteWhitelist() {
        $POSTFIELDS = array(
            'idCliente' => $this->input->post('id_cliente'),
            'idAlerta' => $this->input->post('id_alerta_email'),
            'listaPlacas' => implode(',', $this->input->post('id_whitelist'))
        );

        $dados = post_DesassociarLoteWhitelist($POSTFIELDS);

        echo json_encode($dados);
    }
    
    public function desassociarAllWhitelist() {
        $POSTFIELDS = array(
            'idAlerta' => $this->input->post('idAlerta'),
            'idCliente' => $this->input->post('idCliente')
        );
        $dados = patch_DesassociarAllWhitelist($POSTFIELDS);
        echo json_encode($dados);
    }
    
    //Patch
    public function desassociarBlacklistLote() {
        $POSTFIELDS = array(
            'idAlerta' => $this->input->post('idAlerta'),
            'idCliente' => $this->input->post('idCliente')
        );
        $dados = patch_DesassociarBlacklistLote($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarImportacaoWhitelist(){

        $listaImportacaoWhitelist = json_decode($this->input->post('ListaImportacaoWhitelist'), true);

        $POSTFIELDS = array(
            'lista' => $listaImportacaoWhitelist
        );
        
        $dados = post_ImportarWhitelist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarAssociacaoWhitelist() {
        $POSTFIELDS = array(
            'idAlertaEmail' => $this->input->post('id_alerta_email'),
            'listaIdWhitelists' => $this->input->post('ids_whitelist')
        );

        $dados = post_AssociarWhitelist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarAssociacaoLoteWhitelist() {
        $POSTFIELDS = array(
            'idCliente' => $this->input->post('id_cliente'),
            'idAlerta' => $this->input->post('id_alerta_email'),
            'listaPlacas' => implode(',', $this->input->post('ids_whitelist'))
        );

        $dados = post_AssociarLoteWhitelist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarAssociacaoAllWhitelist() {
        $POSTFIELDS = array(
            'idAlerta' => $this->input->post('idAlerta'),
            'idCliente' => $this->input->post('idCliente')
        );

        $dados = post_AssociarAllWhitelist($POSTFIELDS);
        echo json_encode($dados);
    }

    
    //Patch
    public function desassociarWhitelistLote() {
        $POSTFIELDS = array(
            'idAlerta' => $this->input->post('idAlerta'),
            'idCliente' => $this->input->post('idCliente')
        );

        $dados = patch_DesassociarWhitelistLote($POSTFIELDS);
        echo json_encode($dados);
    }

    public function atualizarBlacklist(){

        $POSTFIELDS = array(
            'id' => $this->input->post('idBlacklist'),
            'id_usuario_importacao' => $this->input->post('usuarioBlacklist'),
            'placa' => $this->input->post('placaBlacklist'),
            'chassi' => $this->input->post('chassiBlacklist'),
            'modelo' => $this->input->post('modeloBlacklist'),
            'marca' => $this->input->post('marcaBlacklist'),
            'cor' => $this->input->post('corBlacklist'),
            'tipo_ocorrencia' => is_numeric($this->input->post('ocorrenciaBlacklist')) ? $this->input->post('ocorrenciaBlacklist') : ''
        );
        
        $dados = patch_BlackList($POSTFIELDS);
        echo json_encode($dados);
    }

    public function atualizarWhitelist(){

        $POSTFIELDS = array(
            'id' => $this->input->post('idWhitelist'),
            'id_usuario_importacao' => $this->input->post('usuarioWhitelist'),
            'placa' => $this->input->post('placaWhitelist'),
            'chassi' => $this->input->post('chassiWhitelist'),
            'modelo' => $this->input->post('modeloWhitelist'),
            'marca' => $this->input->post('marcaWhitelist'),
            'cor' => $this->input->post('corWhitelist'),
            'tipoOcorrencia' => is_numeric($this->input->post('ocorrenciaWhitelist')) ? $this->input->post('ocorrenciaWhitelist') : ''
        );

        //print_r($POSTFIELDS[7]);
        
        $dados = patch_WhiteList($POSTFIELDS);
        echo json_encode($dados);
    }

    public function atualizarAlertasEmail() {
        $POSTFIELDS = array(
            'id' => $this->input->post('id'),
            'id_cliente' => $this->input->post('id_cliente'),
            'integra_css' => $this->input->post('integra_css'),
            'notifica_email' => $this->input->post('notifica_email'),
            'notifica_tela_alerta' => $this->input->post('notifica_tela_alerta'),
            'emails' => $this->input->post('emails')
        );

        $dados = patch_AlertasEmail($POSTFIELDS);
        echo json_encode($dados);
    }

    public function removerAssociacaoBlacklist() {
        $idAlertaEmail = $this->input->post('id_alerta_email');
        $idBlackList = $this->input->post('id_blacklist');
        $dados = patch_removerAssociacaoBlacklist($idAlertaEmail, $idBlackList );
        echo json_encode($dados);
    }

    public function removerAssociacaoWhitelist() {
        $idAlertaEmail = $this->input->post('id_alerta_email');
        $idWhitelist = $this->input->post('id_whitelist');
        $dados = patch_removerAssociacaoWhitelist($idAlertaEmail, $idWhitelist );
        echo json_encode($dados);
    }

    public function mudarStatusImportacao(){
        
        $lista = json_decode($this->input->post('ListaImportacaoWhitelist'), true);

        $whitelists = array();

        foreach ($lista as $item) {
            $whitelists[] = array(
                "idCliente" => intval($item['idCliente']),
                "placa" => $item['placa'],
                "status" => intval($item['status']),
                "motivo" => isset($item['motivo']) ? $item['motivo'] : ''
            );
        }

        $POSTFIELDS = array(
            'coldList' => $whitelists
        );
        
        $dados = patch_mudarStatusImportarWhitelist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function removerImportacaoWhitelist(){
        $lista = json_decode($this->input->post('ListaImportacaoWhitelist'), true);
        $listaPlacas = array();
        $idCliente = 0;
        foreach ($lista as $item) {
            $idCliente = intval($item['idCliente']);
            $listaPlacas[] = (isset($item['placa']) ? $item['placa'] : '') . '_' . (isset($item['motivo']) ? $item['motivo'] : '');
        }
        $POSTFIELDS = array(
            'idCliente' => $idCliente,
            'listaPlacas' => implode(',', $listaPlacas),
        );
        
        $dados = post_removerImportarWhitelist($POSTFIELDS);
        echo json_encode($dados);
        
    }

    public function mudarStatusImportacaoHotlist(){
        
        $lista = json_decode($this->input->post('ListaImportacao'), true);

        $hotlists = array();

        foreach ($lista as $item) {
            $hotlists[] = array(
                "idCliente" => intval($item['idCliente']),
                "placa" => $item['placa'],
                "status" => intval($item['status']),
                "motivo" => isset($item['motivo']) ? $item['motivo'] : ''
            );
        }

        $POSTFIELDS = array(
            'hotList' => $hotlists
        );
        
        $dados = patch_mudarStatusImportarHotlist($POSTFIELDS);
        echo json_encode($dados);
    }

    public function removerImportacaoHotlist(){
        
        $lista = json_decode($this->input->post('ListaImportacao'), true);

        $listaPlacas = array();
        $idCliente = 0;

        foreach ($lista as $item) {
            $idCliente = intval($item['idCliente']);
            $listaPlacas[] = (isset($item['placa']) ? $item['placa'] : '') . '_' . (isset($item['motivo']) ? $item['motivo'] : '');
        }

        $POSTFIELDS = array(
            'idCliente' => $idCliente,
            'listaPlacas' => implode(',', $listaPlacas),
        );
        
        $dados = post_removerImportacaoHotlist($POSTFIELDS);
        echo json_encode($dados);
    }

    //Delete
    public function deletarBlacklist(){
        $POSTFIELDS = array(
            'id' => $this->input->post('id'),
            'status' => '0'
        );
        
        $dados = patch_statusBlackList($POSTFIELDS);
        echo json_encode($dados);
    }

    //Delete
    public function deletarWhitelist(){
        $POSTFIELDS = array(
            'id' => $this->input->post('id'),
            'status' => '0'
        );
        
        $dados = patch_statusWhiteList($POSTFIELDS);
        echo json_encode($dados);
    }

    public function deletarAlertasEmail(){
        $id =  $this->input->post('id');
        $dados = patch_removerAlertasEmail($id);
        echo json_encode($dados);
    }

    public function formatar_cpf($cpf) {
        $cpfFormated = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpfFormated) != 11) {
            return $cpf; 
        }

        return substr($cpfFormated, 0, 3) . '.' . substr($cpfFormated, 3, 3) . '.' . substr($cpfFormated, 6, 3) . '-' . substr($cpfFormated, 9);
    }
    
    public function formatar_cnpj($cnpj) {
        $cnpjFormated = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpjFormated) != 14) {
            return $cnpj; 
        }

        return substr($cnpjFormated, 0, 2) . '.' . substr($cnpjFormated, 2, 3) . '.' . substr($cnpjFormated, 5, 3) . '/' . substr($cnpjFormated, 8, 4) . '-' . substr($cnpjFormated, 12);
    }

    //Utilitarios
    public function buscar_clientes_ocr(){
        $q = $this->input->get('q');
        $clientes = get_Clientes($q ? urlencode($q) : '');
        $clientesFormatado = array();

        if($clientes['status'] == 200 && count($clientes['resultado']) > 0){
            foreach ($clientes['resultado'] as $key => $cliente) {
                $cliente['text'] = $cliente['nome']." (" .$cliente['razaoSocial'] .")"  . ($cliente['cnpj'] ? " - ".$this->formatar_cnpj($cliente['cnpj']) : ($cliente['cpf'] ? " - ".$this->formatar_cpf($cliente['cpf']) : ''));
                $clientesFormatado[] = $cliente;
            }
            $clientes['resultado'] = $clientesFormatado;
        }
        
        echo json_encode(
            array(
                'status'  => $clientes['status'],
                'results' => $clientesFormatado
            )
        );
    }

    public function buscar_placas_blacklist() {
        $cliente = $this->input->post('id_cliente');

        $dados = get_BlackList('', $cliente, '', '');
        $placas = array();

        if($dados['status'] == 200 && count($dados['resultado']) > 0) {
            foreach($dados['resultado'] as $key => $blacklist) {
                $blacklist['text'] = $blacklist['placa'];
                $blacklist['id'] = $blacklist['placa'];
                $placas[] = $blacklist;
            }
            $dados['resultado'] = $placas;
        }

        echo json_encode($dados);
    }

    public function buscar_placas_whitelist() {
        $cliente = $this->input->post('id_cliente');
        $placa = '';

        $dados = get_Whitelist($placa, $cliente);
        $placas = array();

        if($dados['status'] == 200 && count($dados['resultado']) > 0) {
            foreach($dados['resultado'] as $key => $whitelist) {
                $whitelist['text'] = $whitelist['placa'];
                $whitelist['id'] = $whitelist['placa'];
                $placas[] = $whitelist;
            }
            $dados['resultado'] = $placas;
        }

        echo json_encode($dados);
    }

    public function buscar_placas_blacklist_associadas() {
        $id = $this->input->post('id');

        $dados = get_BlackListAssociadas($id);
        $placas = array();

        if($dados['status'] == 200 && count($dados['resultado']) > 0) {
            foreach($dados['resultado'] as $key => $blacklist) {
                $blacklist['text'] = $blacklist['placa'];
                $blacklist['id'] = $blacklist['placa'];
                $placas[] = $blacklist;
            }
            $dados['resultado'] = $placas;
        }

        echo json_encode($dados);
    }

    public function buscar_placas_whitelists_associadas() {
        $id = $this->input->post('id');

        $dados = get_WhitelistsAssociadas($id);
        $placas = array();

        if($dados['status'] == 200 && count($dados['resultado']) > 0) {
            foreach($dados['resultado'] as $key => $whitelist) {
                $whitelist['text'] = $whitelist['placa'];
                $whitelist['id'] = $whitelist['placa'];
                $placas[] = $whitelist;
            }
            $dados['resultado'] = $placas;
        }

        echo json_encode($dados);
    }

    public function buscar_cliente(){
        $this->load->model('cliente');

        $search = $this->input->get('q');
        $tipoBusca = $this->input->get('tipoBusca');
        $BuscarTodos = $this->input->get('BuscarTodos');

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        if($BuscarTodos){
            $clientes = $this->cliente->getClientesExpedicao($search, $tipoBusca, false);
        }else{
            $clientes = $this->cliente->getClientesExpedicao($search, $tipoBusca);
        }
        if(count($clientes) > 0){
            foreach ($clientes as $key => $cliente) {
                //ocorreram alguns casos de o cpf está com poucos caracteres e bugava a verificação
                //necessário colocar uma verificação de caracteres 

                $resposta['results'][] = array(
                    'id' => $cliente['id'],
                    'text' => $cliente['nome']." (" .$cliente['razao_social'] .")",
                    'cep' => $cliente['cep'],
                    'endereco' => $cliente['endereco'],
                    'uf' => $cliente['uf'],
                    'bairro' => $cliente['bairro'],
                    'cidade' => $cliente['cidade'],
                    'orgao' => $cliente['orgao'],
                    'status' => $cliente['status'],
                );
            }
            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }
    }

    public function buscar_usuario(){
        $this->load->model('usuario');

        $search = $this->input->get('q');
        $BuscarTodos = $this->input->get('BuscarTodos');

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        if($BuscarTodos){
            $usuarios = $this->usuario->get_usuarios($search, false);
        }else{
            $usuarios = $this->usuario->get_usuarios($search);
        }

        if(count($usuarios) > 0){
            foreach ($usuarios as $key => $usuario) {
                //ocorreram alguns casos de o cpf está com poucos caracteres e bugava a verificação
                //necessário colocar uma verificação de caracteres 

                $resposta['results'][] = array(
                    'id' => $usuario->id,
                    'text' => $usuario->id." - ".$usuario->nome
                );
            }
            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }
    }

    public function downloadModeloItens() {
        $caminho_arquivo = base_url('uploads/ocr/planilha_importacao.xlsx');

        $response = [
            'status' => 200,
            'mensagem' => $caminho_arquivo
        ];

        echo json_encode($response);
    }

    public function downloadModeloItensRemocao() {
        $caminho_arquivo = base_url('uploads/ocr/planilha_remocao.xlsx');

        $response = [
            'status' => 200,
            'mensagem' => $caminho_arquivo
        ];

        echo json_encode($response);
    }

}