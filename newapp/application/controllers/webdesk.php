<?php

date_default_timezone_set('America/Recife');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webdesk extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('ticket');
        $this->load->model('template_emails');
        $this->load->model('sender');
        $this->load->model('usuario');
        $this->load->model('usuario_gestor');
        $this->load->model('cliente');
        $this->load->model('veiculo');
        $this->load->model('log_veiculo');
        $this->load->model('template_emails');
        $this->load->model('files');
        $this->load->model('mapa_calor');
        $this->load->model('crm_assunto');
        $this->load->model('mapa_calor');
        $this->load->helper('date');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('download');
        $this->load->library('upload');
        $this->load->library('pagination');
        $this->load->helper('util_webdesk_helper');
    }

    public function index() {
        $this->auth->is_allowed('visualizar_tickets');
        $this->mapa_calor->registrar_acessos_url(site_url('/webdesk'));
        $dados['titulo'] = 'Gerenciador de Tickets';
        $dados['load'] = array('ag-grid', 'ag-grid-helpers', 'select2','css-new-style', 'mask', 'XLSX');
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('webdesk/index');
        $this->load->view('fix/footer_NS');
    }

    /** Função requisita resposta da IA */
    public function ajaxRequestConecto()
    {
        $assistentId = $this->config->item('assistentId');
        $tokenIA = $this->config->item('tokenIA');
        $message = $this->input->post('text');
        $idThread = $this->input->post('idThread');
        $retorno = 'Que pena que não posso ser útil nesse assunto específico agora. 😞 Por favor, veja se há algo mais que eu possa fazer por você ou retorne mais tarde para que possamos tentar resolver isso juntos!';

        // Cria Thread, caso não tenha sido informada
        if (!$idThread) $idThread = $this->createThread($tokenIA);

        if ($idThread) {
            // Adiciona Mensagem a Thread
            $idMessage = $this->sendMessageIA($tokenIA, $idThread, $message);
            if ($idMessage) {
                // Executa Thread
                $this->executeThread($assistentId, $idThread, $tokenIA);

                // Inicializa analise da resposta
                $retorno = $this->pollingResponseIA($tokenIA, $idThread);
            }
        }
        
        exit(json_encode([ 'status' => true, 'resposta' => $retorno, 'idThread' => $idThread ]));

    }

    private function pollingResponseIA($apiKey, $idThread)
    {
        $finished = false;
        $attempts = 0;
        $maxAttempts = 10;  // Número máximo de tentativas para evitar loop infinito
        $interval = 5;  // Intervalo em segundos entre as tentativas
        $retorno = 'Que pena que não posso ser útil nesse assunto específico agora. 😞 Por favor, veja se há algo mais que eu possa fazer por você ou retorne mais tarde para que possamos tentar resolver isso juntos!';

        while (!$finished && $attempts < $maxAttempts) {
            sleep($interval);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.openai.com/v1/threads/$idThread/messages",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer $apiKey",
                    "OpenAI-Beta: assistants=v2"
                ],
            ]);

            // Desativa a verificação de certificado SSL - Apenas para desenvolvimento!
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                $finished = true;  // Encerra em caso de erro
            } else {
                $messages = json_decode($response, true);
                foreach ($messages['data'] as $message) {
                    if ($message['role'] == 'assistant' && !empty($message['content'])) {
                        $retorno = isset($message['content'][0]['text']['value']) ? preg_replace("/【.*?】/", "", $message['content'][0]['text']['value']) : '';
                        $finished = true;  // Encerra se encontrar resposta do assistente
                        break;
                    }
                }
            }

            $attempts++;
        }

        return $retorno;
    }

    private function executeThread($assistentId, $idThread, $apiKey)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.openai.com/v1/threads/$idThread/runs",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "assistant_id" => $assistentId
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $apiKey",
                "OpenAI-Beta: assistants=v2"
            ],
        ]);

        // Desativa a verificação de certificado SSL - Apenas para desenvolvimento!
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($curl);

        return $response;
    }

    private function sendMessageIA($apiKey, $idThread, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.openai.com/v1/threads/$idThread/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "role" => "user",
                "content" => $message
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $apiKey",
                "OpenAI-Beta: assistants=v2"
            ],
        ]);
        
        // Desativa a verificação de certificado SSL - Apenas para desenvolvimento!
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($curl);
        
        return $response;
        
    }

    private function createThread($apiKey)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.openai.com/v1/threads",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $apiKey",
                "OpenAI-Beta: assistants=v2"
            ],
        ]);

        // Desativa a verificação de certificado SSL - Apenas para desenvolvimento!
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($curl);
        $responseData = json_decode($response, true);

        return isset($responseData['id']) ? $responseData['id'] : null;
    }

    public function tickets() {
        $this->auth->is_allowed('visualizar_tickets');
        $this->mapa_calor->registrar_acessos_url(site_url('/webdesk'));
        $dados['titulo'] = 'Show Tecnologia';
        $this->load->view('fix/header', $dados);
        $this->load->view('webdesk/index_old');
        $this->load->view('fix/footer');
    }

    public function buscarTicketsNovo() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $cliente = $this->input->post('cliente');
        $departamento = $this->input->post('departamento');
        $status = $this->input->post('status');
        $tipoEmpresa = $this->input->post('tipoEmpresa');
        $tag = $this->input->post('tag');
        $dataInicio = $this->input->post('dataInicio');
        $dataFim = $this->input->post('dataFim');

        $startRow++;

        $dados = get_listarTickets($startRow, $endRow, $cliente, $departamento, $status, $tipoEmpresa, $tag, $dataInicio, $dataFim);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['tickets'],
                "lastRow" => $dados['resultado']['qtdRetornos']
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


public function buscarTickets() {
    $status = (int) $this->input->post('status');
    $prestadora = $this->input->post('prestadora') ? $this->input->post('prestadora') : NULL;
    $id_cliente = $this->input->post('cliente') ? $this->input->post('cliente') : NULL;
    $departamento = $this->input->post('departamento');
    $tag = $this->input->post('tag');

    $departamento = (!empty($departamento) && $departamento !== 'todas') ? $departamento : NULL;

    $where_in = null;
    if ($status == 1) {
        $where_in = array(1, 2);
    } elseif ($status == 3) {
        $where_in = array(3);
    }

    $dados = array('dados' => array(), 'status' => 200);

    $tickets = $this->ticket->get_tickets($where_in, 0, 50, NULL, $prestadora, $id_cliente, $departamento, $tag);
    //dd($tickets);
    foreach ($tickets as $ticket) {
        $diffe = $this->ticket->getDiff($ticket->id_cliente);
        $ticket->situacao = 'Adimplente';
        if (!empty($diffe)) {
            rsort($diffe);
            if ($diffe[0]->diff >= 1) {
                $ticket->situacao = 'Inadimplente';
            }
        }
        //Mapeamento de prioridade
        $prioridades = array(
            1 => "Baixa",
            2 => "Média",
            3 => "Alta"
        );

        // Mapeamento de empresas
        $empresas = array(
            'TRACKER' => 'SHOW TECNOLOGIA',
            'SIMM2M' => 'SIMM2M',
            'EUA' => 'SHOW TECHNOLOGY',
            'OMNILINK' => 'OMNILINK',
            'NORIO' => 'SIGA-ME'
        );
        $prestadora = isset($empresas[$ticket->empresa]) ? $empresas[$ticket->empresa] : '';
        $prioridade = isset($prioridades[$ticket->prioridade]) ? $prioridades[$ticket->prioridade] : 'Sem classificação';

        $val_ticket = $ticket->ticketnumber_crm ? $ticket->ticketnumber_crm : $ticket->id;

        $dados['dados'][] = array(
            'id' => $val_ticket,
            'cliente' => $ticket->cliente,
            'prioridade' => $prioridade,
            'canal' => 'TICKET',
            'situacao' => $ticket->situacao,
            'nome_usuario' => $ticket->nome_usuario,
            'usuario' => $ticket->usuario,
            'departamento' => $ticket->departamento,
            'assunto' => $ticket->assunto,
            'ultima_interacao' => $ticket->ultima_interacao,
            'prestadora' => $prestadora,
            'status' => $this->ticket->tempo_espera($ticket->id, $ticket->status),
        );
    }

    echo json_encode($dados);
}

    public function buscarClientes() {
        $data = array('results' => array());

        if ($search = $this->input->get('term')) { # Se usuário realizar busca no select
            $clientes = $this->cliente->listarClientesFilter($search); # Filtra clientes
        } else { # Se não vir filtro
            $clientes = $this->cliente->listar(array(), 0, 50); # Lista 50 registros
        }

        if ($clientes) {
            foreach ($clientes as $key => $cliente) {
                $data['results'][] = array(
                    'id' => $cliente->id,
                    'text' => $cliente->id.' - '.$cliente->nome
                );
            }
        }

        echo json_encode($data);
    }

    public function load_tickets($number=NULL, $prest=NULL) {

        if ($number == 1)
            $where_in = array(1, 2);
        elseif ($number == 3)
            $where_in = array(3);
        else
            $where_in = NULL;

        $draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $search = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)
        }

        $dados['data'] = array();
        $tickets = $this->ticket->get_tickets($where_in, $start, $limit, $search, $prest);
        $total_filter = $this->ticket->countAll_filter($where_in, $search);

        $diffe[] = array();
        foreach ($tickets as $ticket){
            $diffe = $this->ticket->getDiff($ticket->id_cliente);
            $ticket->situacao = '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
            if (isset($diffe[0])) {
                rsort($diffe);
                if ($diffe[0]->diff >= 1)
                    $ticket->situacao = '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a>';
            }
            $prestadora = '';
            switch ($ticket->empresa) {
                case 'TRACKER':
                    $prestadora = 'SHOW TECNOLOGIA';
                    break;
                case 'SIMM2M':
                $prestadora = 'SIMM2M';
                break;
                case 'EUA':
                $prestadora = 'SHOW TECHNOLOGY';
                break;
                case 'OMNILINK':
                $prestadora = 'OMNILINK';
                break;
                case 'NORIO':
                $prestadora = 'SIGA-ME';
                break;
            }

            $val_ticket = $ticket->id;

            if($ticket->ticketnumber_crm != "")
                $val_ticket = $ticket->ticketnumber_crm;

            $dados['data'][] = array(
                $val_ticket,
                $ticket->cliente,
                $ticket->situacao,
                $ticket->nome_usuario,
                $ticket->usuario,
                $ticket->departamento,
                $ticket->assunto,
                dh_for_humans($ticket->ultima_interacao),
                $prestadora,
                $this->ticket->tempo_espera($ticket->id, $ticket->status),
                "<a href='" . site_url('webdesk/ticket/'.$ticket->id) . "' class='btn btn-mini btn-primary' title='Visualizar'><i class='fa fa-eye'></i></a>"
            );
        }

        $dados['draw'] = $draw+1;
        $dados['recordsTotal'] = $this->ticket->get_totAll($where_in);
        $dados['recordsFiltered'] = $total_filter;

        echo json_encode($dados);
    }

    public function abertos($page = 0) {
        $this->auth->is_allowed('visualizar_tickets');
        $lista = array();
        $usuarios = $this->usuario_gestor->listar(array('status_usuario !=' => ''));
        if (count($usuarios)) {
            foreach ($usuarios as $usu) {
                $lista[] = $usu->usuario;
            }
        }
        $clientes = $this->cliente->all();
        if (count($clientes)) {
            foreach ($clientes as $cli) {
                $lista[] = $cli->nome;
            }
        }
        $para_view['lista_pesquisa'] = json_encode($lista);
        if($this->input->get()) {
            if ($this->input->get('tipoPes') == 0){
                $para_view['tickets'] = $this->ticket->listar_pesquisa_ticketNum($this->input->get());
                $diffe[] = array();
                foreach ($para_view['tickets'] as $ticket){
                    $diffe = $this->ticket->getDiff($ticket->id_cliente);
                    $para_view['situ'] = '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
                    if (isset($diffe[0])) {
                        rsort($diffe);
                        if ($diffe[0]->diff >= 1) {
                            $para_view['situ'] = '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
                        }
                    }
                    $situacao['situ'][] = $para_view['situ'];
                }
            }else{
                $para_view['tickets'] =  $this->ticket->listar_pesquisa_ticket($this->input->get(), 'abertos');
                $diffe[] = array();
                foreach ($para_view['tickets'] as $ticket){
                    $diffe = $this->ticket->getDiff($ticket->id_cliente);
                    $para_view['situ'] = '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
                    if (isset($diffe[0])) {
                        rsort($diffe);
                        if ($diffe[0]->diff >= 1) {
                            $para_view['situ'] = '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
                        }
                    }
                    $situacao['situ'][] = $para_view['situ'];
                }
            }
            $para_view['titulo'] = 'Show Tecnologia';
            $this->load->view('fix/header', $para_view);
            $this->load->view('webdesk/index', $situacao);
            $this->load->view('fix/footer');
        } else {
            $config['base_url'] = site_url('webdesk/abertos');
            $config['total_rows'] = $this->ticket->get_total_tickets_abertos();
            $config['per_page'] = 10;
            $this->pagination->initialize($config);
            $para_view['titulo'] = 'Show Tecnologia';
            $para_view['tickets'] = $this->ticket->get_tickets_abertos($page, $config['per_page']);
            $diffe[] = array();
            foreach ($para_view['tickets'] as $ticket){
                $diffe = $this->ticket->getDiff($ticket->id_cliente);
                $para_view['situ'] = '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
                if (isset($diffe[0])) {
                    rsort($diffe);
                    if ($diffe[0]->diff >= 1) {
                        $para_view['situ'] = '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
                    }
                }
                $situacao['situ'][] = $para_view['situ'];
            }
            $this->load->view('fix/header', $para_view);
            $this->load->view('webdesk/abertos', $situacao);
            $this->load->view('fix/footer');
        }
    }

    public function fechados($page = 0) {
        $this->auth->is_allowed('visualizar_tickets');
        $lista = array();
        $usuarios = $this->usuario_gestor->listar(array('status_usuario !=' => ''));
        if (count($usuarios)) {
            foreach ($usuarios as $usu) {
                $lista[] = $usu->usuario;
            }
        }
        $clientes = $this->cliente->all();
        if (count($clientes)) {
            foreach ($clientes as $cli) {
                $lista[] = $cli->nome;
            }
        }
        $para_view['lista_pesquisa'] = json_encode($lista);
        if($this->input->get()) {
            if ($this->input->get('tipoPes') == 0){
                $para_view['tickets'] = $this->ticket->listar_pesquisa_ticketNum($this->input->get());
                $diffe[] = array();
                foreach ($para_view['tickets'] as $ticket){
                    $diffe = $this->ticket->getDiff($ticket->id_cliente);
                    $para_view['situ'] = '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
                    if (isset($diffe[0])) {
                        rsort($diffe);
                        if ($diffe[0]->diff >= 1) {
                            $para_view['situ'] = '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
                        }
                    }
                    $situacao['situ'][] = $para_view['situ'];
                }
            }else{
                $para_view['tickets'] =  $this->ticket->listar_pesquisa_ticket($this->input->get(), 'fechados');
                $diffe[] = array();
                foreach ($para_view['tickets'] as $ticket){
                    $diffe = $this->ticket->getDiff($ticket->id_cliente);
                    $para_view['situ'] = '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
                    if (isset($diffe[0])) {
                        rsort($diffe);
                        if ($diffe[0]->diff >= 1) {
                            $para_view['situ'] = '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
                        }
                    }
                    $situacao['situ'][] = $para_view['situ'];
                }
            }
            $para_view['titulo'] = 'Show Tecnologia';
            $this->load->view('fix/header', $para_view);
            $this->load->view('webdesk/index', $situacao);
            $this->load->view('fix/footer');
        } else {
            $config['base_url'] = site_url('webdesk/fechados');
            $config['total_rows'] = $this->ticket->get_total_tickets_fechados();
            $config['per_page'] = 10;
            $this->pagination->initialize($config);
            $para_view['titulo'] = 'Show Tecnologia';
            $para_view['tickets'] = $this->ticket->get_tickets_fechados($page, $config['per_page']);
            $diffe[] = array();
            foreach ($para_view['tickets'] as $ticket){
                $diffe = $this->ticket->getDiff($ticket->id_cliente);
                $para_view['situ'] = '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
                if (isset($diffe[0])) {
                    rsort($diffe);
                    if ($diffe[0]->diff >= 1) {
                        $para_view['situ'] = '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
                    }
                }
                $situacao['situ'][] = $para_view['situ'];
            }
            $this->load->view('fix/header', $para_view);
            $this->load->view('webdesk/fechados', $situacao);
            $this->load->view('fix/footer');
        }
    }

    public function abrir() {
        $with['clientes'] = $this->cliente->all();
        $this->load->view('webdesk/abrir', $with);
    }

	public function novo_ticket_m2m() {
		$this->auth->is_logged('gestor');
		$para_view['titulo'] = 'SIM2M';
		$para_view['assuntos'] = $this->ticket->get_assuntos();
		$para_view['placas'] = $this->veiculo->get_placas_usuario($this->auth->get_login('id_user'));
		$this->load->view('fix/header-simm2m' , $para_view);
		$this->load->view('webdesk/novo-ticket-m2m');
		$this->load->view('fix/footer-sim');
	}

    public function new_ticket() {
        $referencia = $this->input->post('referencia');
        $cliente = $this->ticket->get_cliente($this->input->post('id_cliente'));
        $placa = $this->input->post('placa');
        $id_user = $this->input->post('id_usuario');
        $nome_usuario = $this->input->post('nome_usuario');
        $prioridade = $this->input->post('prioridade');
        $usuario = $this->input->post('usuario');
        $descricao = $this->input->post('descricao');
        $assunto = $this->input->post('assunto');
		$departamento = $this->input->post('departamento');
        $subTitulo = getSubtitulo($referencia);
        $target_dir = $target_file = $fileType = $filename = '';

        $dados = array(
            'assunto' => $assunto,
            'departamento' => $departamento,
            'mensagem' => $descricao,
            'data_abertura' => date('Y-m-d H:i:s'),
            'ultima_interacao' => date('Y-m-d H:i:s'),
            'id_usuario' => $id_user,
            'status' => 1,
            'status_anterior' => 1,
            'nome_usuario' => $usuario,  //por motivos de uso antigo, o campo usuario recebe o nome do usuario
            'prioridade' => $prioridade,
            'usuario' => $nome_usuario,  //por motivos de uso antigo, o campo nome_usuario recebe o email do usuario
            'cliente' => $cliente->nome,
            'id_cliente' => $cliente->id,
            'placa' => $placa,
            'coment_trello' => '',
            'data_fechamento' => '1970-01-01 00:00:00',
            'interacao_anterior' => '1970-01-01 00:00:00',
        );

        if ($id_ticket = $this->ticket->criar_ticket($dados)) {
            if($_FILES['arquivo']['size'] != 0){
                $target_dir = "uploads/tickets/";
                $fileType = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
                $filename = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_FILENAME));
                $name = preg_replace('/[^A-Za-z0-9]/', '', $filename) . '.' . $fileType;

                if (in_array($fileType, ['png', 'pdf', 'jpg', 'jpeg'])) {
                    move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_dir.$name);

                    $id_file = $this->files->fileSave(array(
                        'id_cliente' => $cliente->id,
                        'nome_arquivos' => $name,
                        'caminho' => $target_dir,
                        'link' => $this->config->item('base_url_gestor') . $target_dir . $name,
                        'id_referencia' => $id_ticket,
                        'tipo' => 'ticket'
                    ));
                    //Removido a variavel $retorno pois não estava definida e estava quebrando no front.
                    $this->ticket->updateTicket($id_ticket, array(
                        'arquivo' => $id_file,
                        'ticketnumber_crm' => "",
                    ));
                }
            }

            $dados['status'] = $this->ticket->tempo_espera($id_ticket, '1');
            $dados['visualizar'] = "<a class='text-center' href='" . site_url('webdesk/ticket/' . $id_ticket) . "' title='" . lang('visualizar') . "'><i class='material-icons'>remove_red_eye</i></a>";
            $dados['ultima_interacao'] = dh_for_humans($dados['ultima_interacao']);

            exit(json_encode(
                array(
                    'success' => true,
                    'mensagem' => 'Ticket cadastrado com sucesso!',
                    'dados'=> $dados,
                    'idTicket'=>$id_ticket,
                    'prestadora'=>$subTitulo
                )
            ));
        } else {
            exit(json_encode(
                array(
                    'success' => false,
                    'mensagem' => 'Não foi possível criar o ticket. Tente novamente mais tarde!'
                )
            ));
        }
	}

    public function get_grupo_economico($cnpjCliente){
        try {
            $this->load->helper('sac_crm_helper');

            $this->sac = new SacCrmHelper();

            $baseCNPJ = explode('/',$cnpjCliente)[0].'/';
            
            $entity = "accounts";
            $api_request_parameters = array(
                '$select' => 'zatix_cnpj',
                '$filter'=>"(startswith(zatix_cnpj, '{$baseCNPJ}') eq true) and (zatix_cnpj  ne '{$cnpjCliente}')"
            );
            $grupoEconomico = $this->sac->get($entity, $api_request_parameters);
            
            if($grupoEconomico->code == 200){
                return $grupoEconomico->data->value;
            }else{
                return array();
            }
        } catch (\Throwable $th) {
            return array();
        }
    }

    public function verificar_providencias($id_cliente){
        try {

            $this->load->helper('sac_crm_helper');

            $this->sac = new SacCrmHelper();

            $api_request_parameters = array(
                '$select' => 'tz_name',
                '$filter' => "(_tz_accountid_value eq {$id_cliente})",
                '$top' => 1
            );

            //faz a requisição 
            $providencias = $this->sac->get('tz_providenciases', $api_request_parameters);
            $checa_providencia = $providencias->data;            
            
            if(count($checa_providencia->value) == 0){
                $api_request_parameters = array(
                    '$select' => 'tz_name',
                    '$filter' => "(_tz_contactid_value eq {$id_cliente})",
                    '$top' => 1
                );   

                //faz a requisição
                $providencias = $this->sac->get('tz_providenciases', $api_request_parameters);
            }

            isset($providencias->data->value[0]) ? $retorno = true : $retorno = false; 
            return $retorno;

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function ajax_get_cliente_param($document){
        try {
            $this->load->helper('sac_crm_helper');

            $this->sac = new SacCrmHelper();

            //pega "somente" os campos necessários
            $select = 'zatix_codigocliente,zatix_loja,zatix_nomefantasia,tz_possui_particularidade ,websiteurl, customertypecode
                ,emailaddress1,emailaddress3,tz_emailaddress4,tz_emailaf,emailaddress2,tz_email_alerta_cerca, tz_dddtelefoneprincipal
                    ,tz_ddd_telefone1,tz_telefone1,tz_ddd_outro_telefone,tz_ddd_telefone_celular,tz_dddtelefonecelular,tz_dddfax
            ,fax,_tz_cep_principalid_value,address1_line1,address1_line2,address1_line3,_tz_cidade_principalid_value,address1_city
                ,_tz_estado_principalid_value,address1_stateorprovince,address2_addressid,zatix_atendimentoriveiculo,zatix_comunicacaochip
                    ,zatix_comunicacaosatelital,zatix_emissaopv,zatix_bloqueiototal,tz_desbloqueioportal,address2_line1,address2_line2,address2_line3,address2_city
            ,address2_stateorprovince,_tz_cep_entregaid_value,zatix_enderecoentregacep,zatix_enderecoentrega,zatix_enderecoentregacomplemento
                ,zatix_enderecoentregabairro,zatix_enderecoentregamunicipio,zatix_enderecoentregaestado,_tz_segmentacao_cliente_value
                    ,tz_segmentacao_manual,zatix_nomevendedor,_tz_canal_vendaid_value,tz_codigo_cliente_show,tz_codigo_cliente_graber
            ,tz_possui_providencia,address1_postalcode,_tz_vendedorid_value,tz_envio_sustentavel,zatix_gerenciadorarisconome,tz_gerenciadora_risco, statecode';
                
            $expand = 'tz_segmentacao_cliente($select=tz_name),tz_consultor_pos_vendas($select=systemuserid,fullname)
                ,tz_forma_cobrancaid($select=tz_forma_cobrancaid,tz_name),tz_vendedorid($select=tz_name), tz_cidade_principalid($select=tz_name), tz_estado_principalid($select=tz_uf)';

            // Procura cliente pessoa física
            if(strlen($document) == 14){
                $entity = "contacts";
                //adiciona campos específicos de cliente PF
                $select .= ",contactid, zatix_cpf,firstname,lastname,tz_ddd_telefone_principal,_tz_cep_cobranaid_value
                    ,tz_dddtelefoneresidencial";

                $api_request_parameters = array(
                    '$select' => $select,
                    '$filter'=>"zatix_cpf eq '{$document}'",
                    '$expand' => $expand
                );
            }else{// procura cliente pessoa jurídica
                $entity = "accounts";
                //adiciona campos específicos de cliente PJ
                $select .= ",accountid, zatix_cnpj,name,zatix_inscricaoestadual,zatix_inscricaomunicipal,tz_ddd_principal,
                    _tz_cep_cobrancaid_value,tz_nome_responsavel,tz_cargo_responsavel,tz_tipo_cliente,tz_dddtelefoneoutros
                        ,tz__data_desbloqueio_portal";

                $api_request_parameters = array(
                    '$select' => $select,
                    '$filter'=>"zatix_cnpj eq '{$document}'",
                    '$expand' => $expand . ',primarycontactid,parentaccountid($select=name,accountid)'
                );
            }

            $cliente = $this->sac->get($entity, $api_request_parameters);
                        
            $cliente->entity = $entity;

            $resposta = array();
            
            if($cliente->code == 200 && !empty($cliente->data->value)){
                $value = (object) $cliente->data->value[0];
                $id = isset($value->accountid) ? $value->accountid : $value->contactid;
                
                if(!isset($value->address1_postalcode)){
                    $cep = $this->sac->get('tz_ceps',array(
                        '$select' => 'tz_cep1',
                        '$filter' => "tz_cepid eq ". $value->_tz_cep_principalid_value
                    ));
                }

                $resposta = array(
                    'Id' => $id,
                    'codeERP' => $value->zatix_codigocliente,
                    'storeERP' => $value->zatix_loja,
                    'fantasyName' => $value->zatix_nomefantasia,
                    'nomeResponsavel' => $value->tz_nome_responsavel,
                    'cargoResponsavel' => $value->tz_cargo_responsavel,
                    'particularidade' => $value->tz_possui_particularidade ? 1 : 0,
                    'gerenciadoraDeRisco' => $value->tz_gerenciadora_risco  ? 1 : 0,
                    'gerenciadoraRiscoNome' => $value->zatix_gerenciadorarisconome,
                    'envioSustentavel' => intval($value->tz_envio_sustentavel), //0 = NULL 1 = SIM 2 = NAO
                    'document' => isset($value->zatix_cnpj) ? $value->zatix_cnpj : $value->zatix_cpf,
                    'site' => $value->websiteurl,
                    'customertypecode' => $value->customertypecode,
                    'email' => $value->emailaddress1,
                    'emailTelemetria' => $value->emailaddress3,
                    'emailNovo' => $value->tz_emailaddress4,
                    'emailAF' => $value->tz_emailaf,
                    'emailLinker' => $value->emailaddress2,
                    'emailAlertaCerca' => $value->tz_email_alerta_cerca,
                    'telephone' => $value->tz_dddtelefoneprincipal,
                    'ddd2' => $value->tz_ddd_telefone1,
                    'telephone2' => $value->tz_telefone1,
                    'ddd3' => $value->tz_ddd_outro_telefone,
                    'dddCell' => $value->tz_ddd_telefone_celular,
                    'cellPhone' => $value->tz_dddtelefonecelular,
                    'dddfax' => $value->tz_dddfax,
                    'fax' => $value->fax,
                    'statusCadastro' => $value->statecode,
                    // CONTA PRIMARIA
                    'contaPrimaria' => array(
                        'id' => $value->accountid,
                        'nome' => $value->name,
                    ),
                    // Endereço principal
                    'address1_addressid' => $value->address1_postalcode,
                    'postalCode' => array(
                        'Id' => $value->_tz_cep_principalid_value,
                        "Name" => $value->address1_postalcode !== null ?  $value->address1_postalcode : $cep->data->value[0]->tz_cep1,
                    ),
                    'address' => $value->address1_line1,
                    'addressComplement' => $value->address1_line2,
                    'district' => $value->address1_line3,
                    'city' => array(
                        "Id" => $value->_tz_cidade_principalid_value,
                        "Name" => $value->tz_cidade_principalid->tz_name,
                        "Uf" => $value->tz_estado_principalid->tz_uf
                    ),
                    'stateOrProvince' => array(
                        'Id' => $value->_tz_estado_principalid_value,
                        'Name' => $value->address1_stateorprovince
                    ),
                    // Endereço cobrança
                    'address2_addressid' => $value->address2_addressid,

                    // Status Financeiro //
                    'status_atendimentoriveiculo'       => $value->zatix_atendimentoriveiculo,
                    'status_comunicacaochip'            => $value->zatix_comunicacaochip,
                    'status_comunicacaosatelital'       => $value->zatix_comunicacaosatelital,
                    'status_data_desbloqueio_portal'    => $value->tz__data_desbloqueio_portal,
                    'status_emissaopv'                  => $value->zatix_emissaopv,
                    'status_bloqueiototal'              => $value->zatix_bloqueiototal,
                    'status_desbloqueioportal'          => $value->tz_desbloqueioportal,

                    'billingaddress' => $value->address2_line1,
                    'billingAddressComplement' => $value->address2_line2,
                    'billingDistrict' => $value->address2_line3,
                    'billingCity' => $value->address2_city,

                    'billingStateorprovince' => array(
                        'Id' => '',
                        'Name' => $value->address2_stateorprovince,
                    ),
                    
                    // Endereço Entrega
                    'deliveryPostalCode' => array(
                        'Id' => $value->_tz_cep_entregaid_value,
                        'Name' => $value->zatix_enderecoentregacep
                    ),
                    'deliveryAddress' => $value->zatix_enderecoentrega,
                    'deliveryAddressComplement' => $value->zatix_enderecoentregacomplemento,
                    'deliveryDistrict' => $value->zatix_enderecoentregabairro,
                    'deliveryCity' => $value->zatix_enderecoentregamunicipio,
                    'deliveryStateorprovince' => array(
                        'Id' => $value->_tz_cep_entregaid_value,
                        'Name' => $value->zatix_enderecoentregaestado,
                    ),
                    'idSegmentation' => $value->_tz_segmentacao_cliente_value,
                    'segmentacaoManual' => $value->tz_segmentacao_manual ? 1 : 0,
                    'segmentation' => isset($value->tz_segmentacao_cliente->tz_name) ? $value->tz_segmentacao_cliente->tz_name : '',
                    'seller' => array(
                        'Id' => $value->_tz_vendedorid_value,
                        'Nome' => $value->tz_vendedorid->tz_name,
                    ),
                    'primarycontactid' => isset($value->primarycontactid) ? $value->primarycontactid : null,
                    'salesChannel' => array(
                        'Id' => $value->_tz_canal_vendaid_value,
                        'Nome' => $value->_tz_canal_vendaid_value
                    ),
                    'codigoClienteShow' => $value->tz_codigo_cliente_show,
                    'codigoClienteGraber' => $value->tz_codigo_cliente_graber,
                    'tipoCliente' => $value->tz_tipo_cliente,
                    //ANALISTA DE SUPORTE
                    'analistaSuporte' => array(
                        "id" => isset($value->tz_consultor_pos_vendas->systemuserid) ? $value->tz_consultor_pos_vendas->systemuserid : '',
                        "nome" => isset($value->tz_consultor_pos_vendas->fullname) ? $value->tz_consultor_pos_vendas->fullname : ''
                    )
                );

                if($entity == "accounts"){// Pessoa Jurídica
                    $resposta['name'] = $value->name;
                    $resposta['stateRegistration'] = $value->zatix_inscricaoestadual;
                    $resposta['inscricaoMunicipal'] = $value->zatix_inscricaomunicipal;
                    $resposta['ddd'] = $value->tz_ddd_principal;
                    $resposta['billingPostalCode'] = array(
                        'Id' => $value->_tz_cep_cobrancaid_value,
                        'Name' => $value->address1_postalcode,
                    );
                    $resposta['telephone3'] = $value->tz_dddtelefoneoutros;

                    $resposta['grupoEconomico'] = $this->get_grupo_economico($document);
                }else{//Pessoa Física
                    $resposta['firstname'] = $value->firstname;
                    $resposta['lastname'] = $value->lastname;
                    $resposta['ddd'] = $value->tz_ddd_telefone_principal;
                    $resposta['billingPostalCode'] = array(
                        'Id' => $value->_tz_cep_cobranaid_value,
                        'Name' => $value->address1_postalcode,
                    );
                    $resposta['telephone3'] = $value->tz_dddtelefoneresidencial;
                }
                //função para verificar se existe alguma providência pendente nesse cliente
                $resposta['providencia'] = $this->verificar_providencias($id);
                $resposta['entity'] =  $cliente->entity;
                if( isset($_SESSION['clientes'])){
                    if(count($_SESSION['clientes']) <= 5){
                        if (!in_array($resposta, $_SESSION['clientes'])){
                            array_push($_SESSION['clientes'], $resposta);
                        }
                    } else {
                        $_SESSION['clientes'][0] = $resposta;
                    }
                    
                } else {
                    $_SESSION['clientes'] = array();
                    array_push($_SESSION['clientes'], $resposta);
                }
                
                if(!$this->auth->is_allowed_block('out_usuarioceabs')){
                    return json_encode(array(
                        'code' => $cliente->code,
                        'customers' => $resposta
                    ));
                } else {
                    if(strlen($document) == 14){
                        $filtroPjPf = '<condition attribute="tz_cliente_pfid" operator="eq" uitype="contact" value="'.$resposta['Id'].'" />';
                    }else{
                        $filtroPjPf = '<condition attribute="tz_cliente_pjid" operator="eq" uitype="account" value="'.$resposta['Id'].'" />';
                    }
                    $xml = '<fetch>
                        <entity name="tz_item_contrato_venda">
                            <attribute name="tz_name" />
                            <attribute name="tz_plataformaid" />
                            <filter>
                                '.$filtroPjPf.'
                            </filter>
                            <link-entity name="tz_produto_servico_contratado" from="tz_codigo_item_contratoid" to="tz_item_contrato_vendaid" alias="tz_produto">
                                <attribute name="tz_produtoid" />
                            </link-entity>
                        </entity>
                    </fetch>';
                    
                    $api_request_parameters = array('fetchXml' => $xml);
                    $response = $this->sac->get('tz_item_contrato_vendas',$api_request_parameters);
                    $ceabs = $response->data;
                    $ceabs = $ceabs->value;
                    $boolLiberamento = false;
                    //array contendo as plataformas que necessitam serem verificadas para realizar a liberação dos dados dos cliente ou não 5,6,7
                    $arrayPlataformas = ['63006545-60b4-e911-95e6-005056ba64fc', '7e40dbc5-967f-e611-91a8-005056800012', '22c0f390-a00e-e711-b382-005056800012', 'c5951d7a-d384-e211-88b0-005056800011', '4f28b01a-936d-e211-a070-005056800011', '1eceac39-917f-e611-91a8-005056800012', '37376bd3-47cb-e211-9a6e-005056800011', '6adf5f1b-29bf-e611-80d7-005056800012', '87282141-25cf-e911-95e6-005056ba64fc'];

                    foreach ($ceabs as $item){
                        if($item->{'_tz_plataformaid_value'} == $arrayPlataformas[5] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[6] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[7]){
                            $boolLiberamento = true;
                            break;
                        }

                        if ($item->{'_tz_plataformaid_value'} == $arrayPlataformas[0] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[1] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[2] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[3] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[4] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[8]){
                            if($item->{'tz_produto_x002e_tz_produtoid'} == "29BC7BD8-61E1-EA11-B889-005056BA183F"){
                                $boolLiberamento = true;
                                break;
                            }
                        }
                    }

                    return json_encode(array(
                        'code' => $cliente->code,
                        'customers' => $resposta,
                        'ceabs' => $boolLiberamento
                    ));
                }
                
            }else{
                return json_encode(array('code' => $cliente->code, 'customers' => 'error'));
            }
        } catch (\Throwable $th) {
            throw $th;
        }   
    }

    public function ultimosTicketsUsuario($id_usuario) {
        return $this->db->select('id, id_usuario, assunto, ultima_interacao')->where('id_usuario', $id_usuario)->order_by('id', 'desc')->limit(10)->get('systems.show_tickets')->result();
    }

    /** Função para exibição da nova página de visualizações de tickets */
    public function ticket($idTicket = NULL)
    {
        $dataView = [
            'titulo' => 'Atendimento - Grupo Show Tecnologia',
            'idTicket' => $idTicket,
            'load' => ['ag-grid']
        ];

        $this->load->view('new_views/fix/header', $dataView);
        $this->load->view('webdesk/conversa_2');
        $this->load->view('fix/footer_NS');
    }

    /** Função retorna dados do cliente */
    function getDataClient($idCliente)
    {
        $data = $this->cliente->get(['id' => $idCliente]);

        exit(json_encode([
            'status' => !empty($data) ? true : false,
            'data' => $data
        ]));
    }

    /** Função executa troca de categoria do ticket */
    function updateCategoriaTicket()
    {
        $idTicket = $this->input->post('id');
        $newCategoria = $this->input->post('categoria');

        if (is_numeric($idTicket) && is_string($newCategoria) && $newCategoria != '') {
            // Realiza update da categoria no ticket
            $up = $this->ticket->updateTicket($idTicket, ['departamento' => $newCategoria]);

            if ($up) {
                $dataInsert = [
                    'resposta' => 'O chamado foi transferido para categoria "'.$newCategoria.'".',
                    'data_resposta' => date('Y-m-d H:i:s'),
                    'nome_usuario' => $this->auth->get_login_dados('nome'),
                    'id_ticket' => $idTicket,
                    'id_user' => '425',
                    'status' => '0',
                    'tipo' => 'privada',
                    'status' => 5
                ];

                $this->ticket->insertMessage($dataInsert);
            }

            exit(json_encode([
                'status' => $up,
                'data' => isset($dataInsert) ? $dataInsert : []
            ]));
        }

        exit(json_encode([ 'status' => false ]));
    }

    /** Função envia nova mensagem ao chamado */
    public function sendMessage()
    {
        $message = $this->input->post('message');
        $tipo = $this->input->post('type');
        $idTicket = $this->input->post('id');
        $idCliente = $this->input->post('id_cliente');
        $arquivo = $_FILES;
        
        // Verifica se dados básicos foram passados
        if ($message && is_numeric($idTicket) && is_numeric($idCliente)) {
            // Inicia tratamento de arquivo, caso tenha sido enviado
            if(isset($arquivo['file']) && $arquivo['file']['size'] != 0) {
                $target_dir = "uploads/tickets"; // Pasta padrão de armazenamento
                $target_file = $target_dir . basename($arquivo["file"]["name"]);
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $filename = basename($arquivo["file"]["name"]);
                $path = "uploads/tickets/";
                $name = $idTicket.$filename;
    
                // Realiza upload do arquivo
                move_uploaded_file($arquivo["file"]["tmp_name"], $path.$name);
                
                // Dados do arquivo a ser inserido
                $insertA = [
                    'id_cliente' => $idCliente,
                    'nome_arquivos' => $name,
                    'caminho' => $path,
                    'link' => $this->config->item('base_url_gestor').$path.$name,
                    'data_cadastro' => date('Y-m-d H:i:s'),
                    'id_referencia' => $idTicket,
                    'tipo' => 'ticket'
                ];

                // Persiste dados do arquivo no banco
                $idArquivo = $this->ticket->fileSave($insertA);
            }

            // Dados da mensagem a ser inserida
            $dataInsert = [
                'resposta' => $message,
                'arquivo' => isset($idArquivo) ? $idArquivo : NULL,
                'nome_usuario' => $this->auth->get_login_dados('nome'),
                'data_resposta' => date('Y-m-d H:i:s'),
                'id_user' => '425',
                'id_ticket' => $idTicket,
                'status' => '0',
                'tipo' => $tipo
            ];
            
            // Persiste mensagem no banco de dados
            $insertM = $this->ticket->insertMessage($dataInsert);

            // Junta ao retorno link do arquivo
            $dataInsert['link'] = isset($insertA) ? $insertA['link'] : NULL;

            exit(json_encode([
                'status' => $insertM ? true : false,
                'data' => $dataInsert,
                'message' => !$insertM ? 'Não foi possível enviar a mensagem. Tente novamente em alguns minutos!' : 'Mensagem enviada com sucesso!'
            ]));
        }

        exit(json_encode([
            'status' => false,
            'message' => 'Não foi possível enviar a mensagem. Tente novamente em alguns minutos!'
        ]));
    }

    /** Função retorna ultimos 10 tickets do cliente */
    function getTicketsClient($idCliente, $idTicket)
    {
        $data = $this->ticket->getTicketsClient('id, assunto, status, data_abertura', 10, $idCliente, $idTicket);
        
        exit(json_encode([
            'status' => !empty($data) ? true : false,
            'data' => $data
        ]));
    }

    /** Função ajax retorna mensagens do ticket */
    function getDataTicket($idTicket)
    {
        $ticket = $this->ticket->getDataTicket($idTicket);
        
        exit(json_encode([
            'status' => !empty($ticket) ? true : false,
            'data' => $ticket
        ]));
    }

    public function enviar_resposta($id_ticket, $status) {
        $this->load->model('sender');
        $target_dir = $target_file = $fileType = $filename = "";
        $id_arquivo = null;
        $nome_usuario = "Suporte";
        $status_atual = $status;
        $mensagem = $this->input->post('resposta');
        $coment_trello = $this->input->post('coment_trello');
        $data_resposta = date('Y-m-d H:i:s');
        $id_cliente = $this->input->post('id_cliente');
        $dadosTicket = $this->ticket->getEmail($id_ticket);
        $nameArq = NULL;

        if($_FILES['arquivo']['size'] != 0){
			$target_dir = "uploads/tickets/";
			$target_file = $target_dir . basename($_FILES["arquivo"]["name"]);
			$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$filename = basename( $_FILES["arquivo"]["name"]);
		}

        if($_FILES['arquivo']['size'] != 0 && !in_array($fileType, array('pdf', 'jpg', 'png', 'jpeg')) ) {
            $this->session->set_flashdata('erro', lang('err_formato_arquivo'));

        }elseif ($mensagem == "") {
            $this->session->set_flashdata('erro', lang('err_falta_mensagem'));

        } else {
            $id_file = null;
            if($_FILES['arquivo']['size'] != 0){
				$path = "uploads/tickets/";
				$nameArq = $id_ticket.$filename;
				move_uploaded_file($_FILES["arquivo"]["tmp_name"], $path.$nameArq);

				$id_file = $this->files->fileSave(
                        array(
                            'id_cliente' => $id_cliente,
        					'nome_arquivos' => $nameArq,
        					'caminho' => $path,
        					'link' => $this->config->item('base_url_gestor').$path.$nameArq,
        					'id_referencia' => $id_ticket,
        					'tipo' => 'ticket'
            			)
                    );
			}
            $salvouResposta = $this->ticket->salvar_resposta($mensagem, $data_resposta, $id_ticket, $id_file, $status_atual, $nome_usuario, $coment_trello);
            if ($salvouResposta) {
                //SALVA QUEM RESPONDEU
                $this->ticket->set_nomeResponsavel($nome_usuario, $id_ticket);
                //SETA QUE A MENSAGEM FOI RESPONDIDA
                $this->ticket->responseTicket($id_ticket);
                //ENVIA A RESPOSTA PARA O USUARIO POR EMAIL
                $this->sender->sendEmail(
                    'suporte@showtecnologia.com',
                    $dadosTicket->nome_usuario,
                    'Ticket #' . $id_ticket . ' - ' . $dadosTicket->assunto,
                    $mensagem
                );

                if ($coment_trello != '') {
                    //ENVIA A RESPOSTA PARA O TRELLO
                    $this->sender->sendEmail(
                        'suporte@showtecnologia.com',
                        $coment_trello,
                        $nome_usuario . ' - ' . date('Y-m-d H:i:s'),
                        empty($nameArq) ? $mensagem : $mensagem. '<br/>Anexo -> '.base_url().'/uploads/tickets/'.$nameArq
                    );
                }

            }else {
                $this->session->set_flashdata('erro', lang('err_tente_mais_tarde'));
            }
        }

        redirect('webdesk/ticket/'.$id_ticket);
    }

    private function do_upload() {
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = '*';
        $config['max_size']	= '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = 'true';

        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('arquivo'))
            return $this->upload->data();
        else
            return array('error' => $this->upload->display_errors());
    }


    public function fechar_ticket($id_ticket,$status) {
        $status_atual = $status;
        $id = $id_ticket;
        $data_fechamento = date('y-m-d H:i:s');
        $usuario_email = $this->auth->get_login('admin', 'email');
        $this->ticket->closeTicket($id_ticket);
        $this->ticket->fechar_t($id, $status_atual, $data_fechamento);
        $acao = array(
            'data_criacao' => date('Y-m-d H:i:s'),
            'usuario' => $usuario_email,
            'acao' => 'O usuário '.$usuario_email.' fechou o ticket '.$id_ticket
        );
        $ret = $this->log_veiculo->add($acao);
        redirect('webdesk/ticket/'.$id_ticket);
    }

    public function reabrir_ticket($id_ticket,$status_anterior) {
        $status_atual = $status_anterior;
        $id = $id_ticket;
        $this->ticket->reabrir_t($id, $status_atual);
        redirect('webdesk/ticket/'.$id_ticket);
    }

    public function teste_template() {
        $mensagem = $this->template_emails->email_abertura_ticker_suporte();
        $this->sender->enviar_email('erickamaral@gmail.com', 'Show Tecnologia', 'erickamaral@gmail.com', 'Veículos Desatualizados', $mensagem, array('erickamaral@gmail.com'));
    }

    public function ranking_old() {
        $para_view['titulo'] = 'Show Tecnologia - Tickets Ranking';
        $this->load->view('fix/header' , $para_view);
        $this->load->view('webdesk/ranking');
        $this->load->view('fix/footer');
    }

    public function ranking() {
        $this->mapa_calor->registrar_acessos_url(site_url('/webdesk/ranking'));

        $dados['titulo'] = lang('ranking_tickets');
        $dados['load'] = array('ag-grid', 'select2', 'mask');

        $this->load->view('new_views/fix/header' , $dados);
        $this->load->view('webdesk/new_ranking');
        $this->load->view('fix/footer_NS');
    }

    public function getRanking() {
        ##início##
        $posicoes = $this->ticket->getRanking();
        foreach ($posicoes as $posicao) {
            $diffe = $this->ticket->getDiff($posicao->id_cliente);
            // pr($diffe[0]->diff); die;
            $bot = 'valor=1 <a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> ';
            if (isset($diffe[0])) {
                rsort($diffe);
                if ($diffe[0]->diff >= 90) {
                    $bot = 'valor=0 <a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ';
                }
            }
            $dados[] = array('num' => $posicao->num,
                'id_cliente' =>$posicao->id_cliente,
                'cliente' => $posicao->cliente,
                'bot' => $bot
            );
        }
        echo json_encode($dados);
        ##fimal##
        // echo json_encode($this->ticket->getRanking());
    }

    public function buscarDadosServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $cliente = $this->input->post('cliente');

        $startRow++;

        $dados = get_DadosTicketsPaginated($cliente, $startRow, $endRow);

        if ($dados['status'] == '200') {
            $i = $startRow;
            foreach ($dados['resultado']['objetosDTO'] as &$dado) {
                $dado['posicao'] = $i;
                $i++;
            }
            echo json_encode(array(
                "success" => true,
                "statusCode" => $dados['status'],
                "rows" => $dados['resultado']['objetosDTO'],
                "lastRow" => $dados['resultado']['qtdTotal']
            ));
        } elseif ($dados['status'] == '404' || $dados['status'] == '400') {
            echo json_encode(array(
                "success" => false,
                "statusCode" => $dados['status'],
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "statusCode" => $dados['status'],
                "message" => $dados['mensagem'],
            ));
        }
        
    }

    public function getTicketDetails($id_cliente) {

        $dados = listarInfoTicketsByCliente($id_cliente);

        echo json_encode($dados);
    }

    public function getRicketsInfors() {
        $id = $this->input->get('id_cliente');
        echo json_encode($this->ticket->numberOfTicketsPerMonth($id));
    }
    #select count(*) as 'num', id, id_cliente, cliente from systems.show_tickets where id_cliente > 0 group by id_cliente order by num desc;

    public function view_dash(){
        $this->session->sess_expiration = 6000*6000*300;
        $this->session->sess_expire_on_close = FALSE;
        $this->mapa_calor->registrar_acessos_url(site_url('/webdesk/view_dash'));
        $dados['titulo'] = "ShowTecnologia";
        $this->load->view('fix/header', $dados);
        $this->load->view('webdesk/dashboard');
        $this->load->view('fix/footer');
    }

    // ANDRÉ GOUVEIA ---> FUNCTION LAST 30 DAYS RATING
    public function get_data_dash(){
        echo $this->ticket->get_ratings();
    }

    //---> FUNCTION MONTH NOW RATING
    public function get_rating_mes(){
        echo $this->ticket->get_ratings_mes();
    }

    //---> FUNCTION USER RATING
    public function get_ranking_user(){
        echo $this->ticket->get_user_raking();
    }

    //---> FUNCTION NEW TICKETS
    public function get_new_tickets(){
        echo $this->ticket->get_tickets_new();
    }

    //---> FUNCTION VERIFICA NOVOS TICKETS
    public function diff_id(){
        $id = $this->input->get('id');
        $newId = $this->ticket->lastIdTicket();
        if ($id != $newId){
            echo json_encode(array('success' => true, 'msg' => "Novo Ticket!"));
        }else{
            echo json_encode(array('success' => false, 'msg' => "Não há novos tickets"));
        }
    }

    function get_ajax_ticket($id_cliente){
		if ($id_cliente) {
			$tickets = $this->ticket->getAjaxListTicketId($id_cliente);
			if ($tickets) {
				foreach ($tickets as $key => $ticket) {
					$data['results'][] = array(
						'id' => $ticket->id
					);
				}

				echo json_encode($data);
			}else {
				return false;
			}
		}else {
			return false;
		}
	}

    /*
    * LISTA TODOS OS TICKETS DE UM CLIENTE
    */
    public function listarTicketsCliente() {

        $id_cliente = $this->input->post('id_cliente');
        if ($id_cliente) {
            $tickets = $this->ticket->getAjaxListTicketClient($id_cliente, 'id, nome_usuario, usuario, departamento, assunto, responsavel, ultima_interacao, status, placa');
            if ($tickets) {
                $dados = array();
                foreach ($tickets as $ticket){
                    $id_ticket = isset($ticket->ticketnumber_crm) && $ticket->ticketnumber_crm != "" ? $ticket->ticketnumber_crm : $ticket->id;

                    $dados[] = array(
                        'id' => $id_ticket,
                        'nome_usuario' => $ticket->nome_usuario,
                        'usuario' => $ticket->usuario,
                        'placa' => $ticket->placa,
                        'departamento' => $ticket->departamento,
                        'assunto' => $ticket->assunto,
                        'responsavel' => isset($ticket->responsavel) ? $ticket->responsavel : '',
                        'ultima_interacao' => dh_for_humans($ticket->ultima_interacao),
                        'status' => $this->ticket->tempo_espera($ticket->id, $ticket->status)
                    );
                }
                echo json_encode(array('status' => true, 'data' => $dados));

            }else {
                echo json_encode(array('status' => false, 'data' => array() ));
            }
        }else {
            echo json_encode(array('status' => false, 'msg' => lang('informar_cliente')));
        }
    }
    /*
    * LISTA AS MENSAGENS DE UM TICKET
    */
    public function listaMensagensTicket($id_ticket) {
        $this->load->view('webdesk/conversa-new');
    }

    public function constroiViewMensagensTicket($msg, $nome, $ticket, $link='', $background_color='', $avatar='avatar', $tipUsuario='cliente'){
        $mensagem = '';
        if ($msg === 'aberturaMsn') {
            $mensagem = '
                <div style="background-color: #f5f5f5; border:1px solid #D0D0D0 ;">
                    <div class="media" style="margin:20px 10px 10px 10px;">
                        <div class="col-xs-2">
                            <div class="media-body">
                                <p>'.dh_for_humans($ticket->data_abertura).'</p>
                            </div>
                        </div>
                        <div class="col-xs-8">
                            <div class="media-body">
                                <h5 class="media-heading">'.$ticket->usuario . ' - ' . $ticket->nome_usuario.'</h5>
                                <p>'.$ticket->mensagem.'</p>'.$link.'
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="pull-right">
                                <img class="media-object" src="'.base_url('media').'/img/avatar.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="background-color: #FFFFFF; border:1px solid #D0D0D0; margin-top: 10px;">
                    <div class="media" style="margin:20px 10px 10px 10px;">
                        <div class="col-xs-2">
                            <div class="pull-left">
                                <img class="media-object" src="'.base_url('media').'/img/avatar2.png">
                            </div>
                        </div>
                        <div class="col-xs-8">
                            <div class="media-body">
                                <h5 class="media-heading">'.$ticket->departamento.'</h5>
                                <p>Olá '.$ticket->usuario .', '.lang('msg_automatica').'</p>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="media-body">
                                <p>'.dh_for_humans($ticket->data_abertura).'</p>
                            </div>
                        </div>
                    </div>
                </div>';
        }else {
            $men = isset($ticket->mensagem) ? $ticket->mensagem : $ticket->resposta;
            $data = isset($ticket->data_abertura) ? dh_for_humans($ticket->data_abertura) : dh_for_humans($ticket->data_resposta);
            $nome_pessoa = $nome == 'departamento' ? $ticket->departamento : $ticket->usuario.' - '.$ticket->nome_usuario;
            if ($tipUsuario == 'funcionario') {
                $mensagem = '
                    <div style="background-color: '.$background_color.'; border:1px solid #D0D0D0; margin-top: 10px;">
                        <div class="media" style="margin:20px 10px 10px 10px;">
                            <div class="col-xs-2">
                                <div class="pull-left">
                                    <img class="media-object" src="'.base_url('media').'/img/'.$avatar.'.png">
                                </div>
                            </div>
                            <div class="col-xs-8">
                                <div class="media-body">
                                    <h5 class="media-heading">'.$nome_pessoa.'</h5>
                                    <p>'.$men.'</p>'.$link.'
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="media-body">
                                    <p>'.$data.'</p>
                                </div>
                            </div>
                        </div>
                    </div>';
            }else {
                $mensagem = '
                    <div style="background-color: '.$background_color.'; border:1px solid #D0D0D0; margin-top: 10px;">
                        <div class="media" style="margin:20px 10px 10px 10px;">
                            <div class="col-xs-2">
                                <div class="media-body">
                                    <p>'.$data.'</p>
                                </div>
                            </div>
                            <div class="col-xs-8">
                                <div class="media-body">
                                    <h5 class="media-heading">'.$nome_pessoa.'</h5>
                                    <p>'.$men.'</p>'.$link.'
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="pull-left">
                                    <img class="media-object" src="'.base_url('media').'/img/'.$avatar.'.png">
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
        return $mensagem;
    }

    /*
    * CARREGA AS MENSAGENS DE UM TICKET
    */
    public function ajaxLoadTicket($id_ticket, $prestadora) {
        $dados = array();

        $ticket = $this->ticket->get_mensagem($id_ticket)[0];
        if ($ticket) {
            $dados['status'] = $ticket->status;
            $dados['status_anterior'] = $ticket->status_anterior;
            $dados['coment_trello'] = $ticket->coment_trello;
            $dados['id'] = $ticket->id;

            $item = $prestadora == 'SIMM2M' ? lang('chip') : lang('placa');
            if ($ticket->ticketnumber_crm===null)
                $dados['assunto'] = '<h4>'.$ticket->placa == '' ? '' :  $item.': '.$ticket->placa.' - '.lang('assunto').': '.$ticket->assunto.'</h4>';
            else
                $dados['assunto'] = '<h4>'.lang('ocorrencia').' '.$ticket->ticketnumber_crm .''. $ticket->placa == '' ? '' :  $item.': '.$ticket->placa.' - '.lang('assunto').': '.$ticket->assunto.'</h4>';

            $dados['dataAbertura'] = lang('em').' '.dh_for_humans($ticket->data_abertura);
            $link = $ticket->arquivo && $ticket->arquivo != "" ? '<a href="'.$ticket->arquivo.'" target="_blank"><i class="fa fa-file"></i> '.lang('visualizar_anexo').'</a>' : '';

            //CONSTROI AS CAIXAS DAS MENSAGENS
            if ($ticket->suporte == 'sim')
                $dados['mensagens'][] = array('msn' => $this->constroiViewMensagensTicket('msn', 'departamento', $ticket, $link, '#ffffff', 'avatar2', 'funcionario'));
            else
                $dados['mensagens'][] = array('msn' => $this->constroiViewMensagensTicket('aberturaMsn', 'nome', $ticket, $link));

            $respostas = $this->ticket->get_resposta($id_ticket);
            if ($respostas) {
                foreach ($respostas as $resposta) {
                    $link = $resposta->arquivo && $resposta->arquivo != "" ? '<a href="'.$resposta->arquivo.'" target="_blank"><i class="fa fa-file"></i> '.lang('visualizar_anexo').'</a>' : '';

                    if ($resposta->id_user != 425){
                        $dados['mensagens'][] = array('msn' => $this->constroiViewMensagensTicket('msn', 'nome', $resposta, $link, '#f5f5f5', 'avatar', 'cliente'));
                    }
                    else{
                        $resposta->departamento = $ticket->departamento;
                        $dados['mensagens'][] = array('msn' => $this->constroiViewMensagensTicket('msn', 'departamento', $resposta, $link, '#ffffff', 'avatar2', 'funcionario'));
                    }
                }
            }
            echo json_encode(array('status' => true, 'dados' => $dados));

        }else {
            echo json_encode(array('status' => false, 'mensagem' => lang('cliente_do_ticket_nao_encontrado')));
        }
    }

    //SALVAR RESPOSTA DO TICKET
    public function ajaxEnviarResposta($id_ticket, $status) {
        $this->load->model('sender');
        $target_dir = $target_file = $fileType = $filename = "";
        $id_arquivo = null;
        $nome_usuario = "Suporte";
        $status_atual = $status;
        $mensagem = $this->input->post('resposta');
        $id_cliente = $this->input->post('id_cliente');
        $coment_trello = $this->input->post('coment_trello');
        $data_resposta = date('Y-m-d H:i:s');
        $dadosTicket = $this->ticket->getEmail($id_ticket);
        $nameArq = NULL;

        if($_FILES['arquivo']['size'] != 0){
			$target_dir = "uploads/tickets/";
			$target_file = $target_dir . basename($_FILES["arquivo"]["name"]);
			$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$filename = basename( $_FILES["arquivo"]["name"]);
		}

        if($_FILES['arquivo']['size'] != 0 && !in_array($fileType, array('pdf', 'jpg', 'png', 'jpeg')) ) {
			echo json_encode(array('success' => false, 'mensagem' => lang('err_formato_arquivo')));

        }elseif ($mensagem == "") {
            echo json_encode(array('status' => false, 'mensagem' => lang('err_falta_mensagem')));

        } else {
            $id_file = null;
            if($_FILES['arquivo']['size'] != 0){
				$path = "uploads/tickets/";
				$nameArq = $id_ticket.$filename;
				move_uploaded_file($_FILES["arquivo"]["tmp_name"], $path.$nameArq);

				$id_file = $this->files->fileSave(
                        array(
                            'id_cliente' => $id_cliente,
        					'nome_arquivos' => $nameArq,
        					'caminho' => $path,
        					'link' => $this->config->item('base_url_gestor').$path.$nameArq,
        					'id_referencia' => $id_ticket,
        					'tipo' => 'ticket'
            			)
                    );
			}
            $salvouResposta = $this->ticket->salvar_resposta($mensagem, $data_resposta, $id_ticket, $id_file, $status_atual, $nome_usuario, $coment_trello);
            if ($salvouResposta) {
                //SALVA QUEM RESPONDEU
                $this->ticket->set_nomeResponsavel($nome_usuario, $id_ticket);
                //SETA QUE A MENSAGEM FOI RESPONDIDA
                $this->ticket->responseTicket($id_ticket);
                //ENVIA A RESPOSTA PARA O USUARIO POR EMAIL
                $this->sender->sendEmail(
                    'suporte@showtecnologia.com',
                    $dadosTicket->nome_usuario,
                    'Ticket #' . $id_ticket . ' - ' . $dadosTicket->assunto,
                    $mensagem
                );

                if ($coment_trello != '') {
                    //ENVIA A RESPOSTA PARA O TRELLO
                    $this->sender->sendEmail(
                        'suporte@showtecnologia.com',
                        $coment_trello,
                        $nome_usuario . ' - ' . date('Y-m-d H:i:s'),
                        empty($nameArq) ? $mensagem : $mensagem. '<br/>Anexo -> '.base_url().'/uploads/tickets/'.$nameArq
                    );
                }

                $resposta = (object)$this->ticket->getRespostaId($salvouResposta);
                $resposta->departamento = $dadosTicket->departamento;
                $link = $resposta->arquivo && $resposta->arquivo != "" ? '<a href="'.$resposta->arquivo.'" target="_blank"><i class="fa fa-file"></i> '.lang('visualizar_anexo').'</a>' : '';
                $dados['msn'] = $this->constroiViewMensagensTicket('msn', 'departamento', $resposta, $link, '#ffffff', 'avatar2', 'funcionario');
                echo json_encode(array('status' => true, 'dados' => $dados));

            }else {
                echo json_encode(array('status' => false, 'mensagem' => lang('err_tente_mais_tarde')));
            }
        }
    }

    /*
    * FECHAR UM TICKET
    */
    public function ajaxFecharTicket($id_ticket, $status) {
        $id = $id_ticket;
        $data_fechamento = date('y-m-d H:i:s');
        $usuario_email = $this->auth->get_login('admin', 'email');
        $this->ticket->closeTicket($id_ticket);
        $fechou = $this->ticket->fechar_t($id, $status, $data_fechamento);
        if ($fechou) {
            $acao = array(
                'data_criacao' => date('Y-m-d H:i:s'),
                'usuario' => $usuario_email,
                'acao' => 'O usuário '.$usuario_email.' fechou o ticket '.$id_ticket
            );
            $ret = $this->log_veiculo->add($acao);
            echo json_encode(array('success' => true, 'msg' => lang('succ_ticket_fechado')));

        }else {
            echo json_encode(array('success' => false, 'msg' => lang('err_fechar_ticket')));
        }
    }

    /*
    * REABRIR UM TICKET
    */
    public function ajaxReabrirTicket($id_ticket, $status_anterior) {
        if( $this->ticket->reabrir_t($id_ticket, $status_anterior))
            echo json_encode(array('success' => true, 'msg' => lang('succ_reabrir_ticket')));
        else
            echo json_encode(array('success' => false, 'msg' => lang('err_reabrir_ticket')));
    }

    /** Função resposável por retornar os totais para exibição no painel de atendimento (chamados) */
    public function getTotaisAtendimento()
    {
        $idCliente = $this->input->post('idCliente');
        $idTicket = $this->input->post('idTicket');

        if (!$idCliente || !$idTicket) exit(json_encode([ 'status' => false, 'data' => [] ]));

        $retorno = [
            'itens' => $this->ticket->getCountItensContratoAtivos($idCliente),
            'produtos' => $this->ticket->getCountProdutosAtivos($idCliente),
            'tickets' => $this->ticket->getCountTicketsAbertos($idCliente),
            'tags' => $this->ticket->getCountTagsTicket($idTicket)
        ];

        exit(json_encode($retorno));
    }

    /** Função vincula Tag ao Ticket */
    public function addTagTicket()
    {
        $idTicket = $this->input->post('id_ticket');
        $tag = $this->input->post('nome');

        if (!is_numeric($idTicket) && !is_string($tag)) exit(json_encode([ 'status' => false, 'message' => 'Parâmetros não enviado.' ]));

        $insert = $this->ticket->insertTagTicket([
            'nome' => $tag,
            'id_ticket' => $idTicket,
            'status' => '1'
        ]);

        exit(json_encode([
            'status' => $insert,
            'message' => $insert ? 'Tag vinculada com sucesso.' : 'Não foi possível vincular a tag ao Ticket. Tente novamente em alguns minutos!'
        ]));
    }

    /** Função responsável pela reabertura ou encerramento do chamado */
    function acaoTicket()
    {
        $idTicket = $this->input->post('id');
        $status = $this->input->post('status');

        if (!is_numeric($idTicket) || !is_numeric($status)) exit(json_encode([ 'status' => false, 'message' => 'Parâmetros inválidos' ]));

        switch ($status) {
            case 3:
                $up = $this->ticket->fechar_t($idTicket, 1, date('Y-m-d H:i:s'));
                break;
            default:
                $up = $this->ticket->reabrir_t($idTicket, 3);
                break;
        }

        // Se update tiver sucesso envia grava mensagem informativa
        if ($up) {
            $dataM = [
                'resposta' => "O chamado foi ". ($status == 3 ? 'encerrado' : 'reaberto') .".",
                'data_resposta' => date('Y-m-d H:i:s'),
                'nome_usuario' => $this->auth->get_login_dados('nome'),
                'id_ticket' => $idTicket,
                'id_user' => '425',
                'status' => '0',
                'tipo' => 'privada',
                'status' => 5
            ];

            $this->ticket->insertMessage($dataM);
        }

        exit(json_encode([
            'status' => $up,
            'message' => $up ? 'Ação realizada com sucesso.' : 'Não foi possível realizar a ação. Tente novamente em alguns minutos!',
            'data' => isset($dataM) ? $dataM : []
        ]));
    }

    /** Função remove Tag vinculada ao ticket */
    public function removeTagTicket()
    {
        $idTicket = $this->input->post('id_ticket');
        $tag = $this->input->post('nome');

        if (!is_numeric($idTicket) && !is_string($tag)) exit(json_encode([ 'status' => false, 'message' => 'Parâmetros não enviado.' ]));

        $up = $this->ticket->updateTag([ 'nome' => $tag, 'id_ticket' => $idTicket ], [ 'status' => '0' ]);
        
        exit(json_encode([
            'status' => $up,
            'message' => $up ? 'Tag removida com sucesso.' : 'Não foi possível remover a tag. Tente novamente em alguns minutos!'
        ]));
    }

    public function listarCaminhoDoUsuario($sidCall)
    {     
        $this->auth->is_allowed('vis_caminho_usuario_atendimento_omnilink');
        $this->load->helper('api_televendas');

        if (!$sidCall) exit(json_encode([ 'status' => false, 'message' => 'Parâmetros inválidos' ]));

        // Busca o token da Twilio
        $resposta = API_Televendas_Helper::get('/atendimento-omnilink/call/capture-user-path-ivr/' . $sidCall);
        exit($resposta);
    }

    public function listarLigacoesEmFila()
    {     
        $this->auth->is_allowed('vis_ligacoes_filas_atendimento_omnilink');
        $this->load->helper('api_televendas');

        // Busca o token da Twilio
        $resposta = API_Televendas_Helper::post('/atendimento-omnilink/call/calls-in-queue');
        exit($resposta);
    }

}
