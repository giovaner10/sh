<?php
date_default_timezone_set('America/Recife');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ticket extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function getEmail($id) {
        return $this->db->select('nome_usuario, assunto, departamento')->where('id', $id)->get('systems.show_tickets')->result()[0];
    }

    function openTicket($id) {
        $query = $this->db->where('ticket', $id)->get('systems.ticket_open');
        if (!$query->num_rows())
            $this->db->insert('systems.ticket_open', array('date' => date('Y-m-d'), 'ticket' => $id, 'time' => '00:00:00'));
    }

    function responseTicket($id) {
        $query = $this->db->where('ticket', $id)->get('systems.ticket_response');
        if (!$query->num_rows())
            $this->db->insert('systems.ticket_response', array('date' => date('Y-m-d'), 'ticket' => $id));
    }

    function closeTicket($id) {
        $query = $this->db->where('ticket', $id)->get('systems.ticket_close');
        if (!$query->num_rows())
            $this->db->insert('systems.ticket_close', array('date' => date('Y-m-d'), 'ticket' => $id, 'time' => '00:00:00'));
    }

    function insert() {
        $open = $this->db->get_where('systems.show_tickets', array('status' => 1, 'data_abertura like' => date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y"))).'%') )->num_rows();
        $progress = $this->db->get_where('systems.show_tickets', array('status' => 2, 'data_abertura like' => date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y"))).'%') )->num_rows();
        $close = $this->db->get_where('systems.show_tickets', array('status' => 3, 'data_abertura like' => date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y"))).'%') )->num_rows();
        $this->db->insert('systems.ticket_grafic', array('date' => date('Y-m-d'), 'open' => $open, 'progress' => $progress, 'close' => $close, 'time' => date('H:i:s')));
    }

    private function get() {
        return $this->db->get_where('systems.ticket_grafic', array('date >' => date('Y-m-d H', strtotime('-1 hour'))))->result();
    }

    function amount() {
        return array(
            'support'	 => $this->db->get_where('systems.show_tickets', array('departamento' => 'Suporte') )->num_rows(),
            'commercial' => $this->db->get_where('systems.show_tickets', array('departamento' => 'Comercial') )->num_rows(),
            'financial'  => $this->db->get_where('systems.show_tickets', array('departamento' => 'Financeiro') )->num_rows(),
        );
    }

    function search($init=false, $end=false) {
        $data = array();
        if ($init && $end) {
            $data['open'] = $this->db->where(array('date >= ' => date('Y-m-d', strtotime(str_replace('/', '-', $init))), 'date <= ' => date('Y-m-d', strtotime(str_replace('/', '-', $end)))))->get('systems.ticket_open')->num_rows();
            $data['progress'] = $this->db->where(array('date >= ' => date('Y-m-d', strtotime(str_replace('/', '-', $init))), 'date <= ' => date('Y-m-d', strtotime(str_replace('/', '-', $end)))))->get('systems.ticket_response')->num_rows();
            $data['close'] = $this->db->where(array('date >= ' => date('Y-m-d', strtotime(str_replace('/', '-', $init))), 'date <= ' => date('Y-m-d', strtotime(str_replace('/', '-', $end)))))->get('systems.ticket_close')->num_rows();
        } else if ($init) {
            $data['open'] = $this->db->where('date', date('Y-m-d', strtotime(str_replace('/', '-', $init))))->get('systems.ticket_open')->num_rows();
            $data['progress'] = $this->db->where('date', date('Y-m-d', strtotime(str_replace('/', '-', $init))))->get('systems.ticket_response')->num_rows();
            $data['close'] = $this->db->where('date', date('Y-m-d', strtotime(str_replace('/', '-', $init))))->get('systems.ticket_close')->num_rows();
        } else {
            $data['open'] = $this->db->where('date', date('Y-m-d'))->get('systems.ticket_open')->num_rows();
            $data['progress'] = $this->db->where('date', date('Y-m-d'))->get('systems.ticket_response')->num_rows();
            $data['close'] = $this->db->where('date', date('Y-m-d'))->get('systems.ticket_close')->num_rows();
        }
        return $data;
    }

    public function abrir_ticket_manutencao($dados)	{
        if ($this->db->insert('systems.show_tickets', $dados))
            return $this->db->insert_id();
        return null;
    }
//    public function salvar_resposta($mensagem, $data_resposta, $id, $arquivo, $status_atual, $nome_usuario, $email_trello) {
    public function salvar_resposta($mensagem, $data_resposta, $id, $arquivo, $status_atual, $nome_usuario, $email_trello) {
        $nova_mensagem = false;
        $status = 2;
        $tickets = $this->get_mensagem($id);
        foreach ($tickets as $ticket) {
            $interacao_ant = $ticket->ultima_interacao;
        }
        $dados3 = array(
            'interacao_anterior' => $interacao_ant
        );
        $this->db->where('id', $id);
        $this->db->update('systems.show_tickets', $dados3);
        $dados = array(
            'resposta' => $mensagem,
            'data_resposta' => $data_resposta,
            'id_user' => 425,
            'id_ticket' => $id,
            'arquivo' => $arquivo,
            'nome_usuario' => $nome_usuario
        );

        $dados2 = array(
			'ultima_interacao' => $data_resposta,
			'status' => $status,
			'coment_trello' => $email_trello,
			'status_anterior' => $status_atual,
		);

        $salvou = $this->db->insert('systems.tickets_mensagens', $dados);
        if (!$salvou) {
            return false;
        }else {
            $nova_mensagem = $this->db->insert_id();
        }
        $this->db->where('id', $id);
        $salvou = $this->db->update('systems.show_tickets', $dados2);
        if (!$salvou) {
            return false;
        }

        return $nova_mensagem;
    }

    /** Retorna a lista de tickets de um determinado cliente */
    public function getTicketsClient($select = '*', $limit = '999', $idCliente = null, $idTicket) {
        // Retorna array vazio, caso não tenha sido fornecido idCliente
        if (!is_numeric($idCliente)) return [];

        // Recupera dados
        return $this->db->select($select)->limit($limit)->order_by('id', 'DESC')->get_where('systems.show_tickets', [ 'id_cliente' => $idCliente, 'id !=' => $idTicket ])->result();
    }

    public function get_total_tickets() {
        $query = $this->db->get('systems.show_tickets');
        return $query->num_rows();
    }

    public function get_tickets($where_in=NULL, $start=0, $limit=10, $search=NULL, $prest=NULL, $id_cliente=NULL, $departamento=NULL, $tag = NULL) {
        $this->db->select('ticket.*, cliente.informacoes as empresa, cliente.nome as cliente_nome');
        $this->db->join('showtecsystem.cad_clientes as cliente', 'cliente.id = ticket.id_cliente', 'inner');

        if ($tag) {
            $this->db->join('systems.tags_tickets as tt', 'tt.id_ticket = ticket.id', 'inner');
            $this->db->where('tt.nome', $tag);
        }

        if (!$prest)
            $this->db->where_in('cliente.informacoes', array('TRACKER', 'SIM2M', 'NORIO', 'EUA', 'SIGAMY', 'SIMM2M'));
        elseif ($prest == 'omnilink')
            $this->db->where_in('cliente.informacoes', array('OMNILINK', 'EMBARCADORES'));

        if ($id_cliente)
            $this->db->where_in('ticket.id_cliente', $id_cliente);

        if ($departamento)
            $this->db->where_in('ticket.departamento', $departamento);

        if ($where_in)
            $this->db->where_in('ticket.status', $where_in);
        if ($search && is_numeric($search))
            $this->db->like('ticket.id', $search);
        elseif ($search && is_string($search))
            $this->db->like('ticket.cliente', $search);

        $this->db->order_by('ticket.status', 'asc');
        $this->db->order_by('ticket.ultima_interacao', 'desc');
        $this->db->limit($limit,$start);
        return $this->db->get('systems.show_tickets as ticket')->result();
    }

    /*
    * Retorna quantidade de registros da query
    */
    public function countAll_filter($where_in=NULL, $search=NULL) {
        if ($where_in)
            $this->db->where_in('ticket.status', $where_in);
        if ($search && is_numeric($search))
            $this->db->like('ticket.id', $search);
        elseif ($search && is_string($search))
            $this->db->like('ticket.cliente', $search);

        $this->db->from('systems.show_tickets as ticket');
        return $this->db->count_all_results();
    }

    /*
    * Retorna total de ordens de servicos
    */
    public function get_totAll($where_in=NULL)
    {
        if ($where_in)
            $this->db->where_in('ticket.status', $where_in);
        $this->db->from('systems.show_tickets as ticket');
        return $this->db->count_all_results();
    }

    public function listar_pesquisa_ticket($pesquisa, $status) {
        $data_inicial = $pesquisa['data_inicial'];
        $data_final = $pesquisa['data_final'];
        if ($status == 'abertos')
            $this->db->where('status !=', 3);
        if($status == 'fechados')
            $this->db->where('status', 3);
        if ($data_inicial && $data_final) {
            if ($this->validar_datas($data_inicial, $data_final)) {
                $data_ini = data_for_unix($data_inicial);
                $data_fim = data_for_unix($data_final);
                $this->db->where('ultima_interacao >=', $data_ini);
                $this->db->where('ultima_interacao <=', $data_fim);
            }
        }
        if ($pesquisa['palavra'] != '') {
            $palavra = $pesquisa['palavra'];
            $caracter = '@';
            if(strpos($palavra, $caracter) == true)
                $coluna = 'nome_usuario';
            else
                $coluna = 'cliente';
            $this->db->like($coluna, $palavra);
        }
        $this->db->order_by('status', 'asc');
        $this->db->order_by('ultima_interacao', 'desc');
        $query = $this->db->get('systems.show_tickets');
        if ($query->num_rows())
            return $query->result();
        return false;
    }

    public function listar_pesquisa_ticketNum($pesquisa) {
        $this->db->where('id', $pesquisa['palavra']);
        $query = $this->db->get('systems.show_tickets');
        if ($query->num_rows())
            return $query->result();
        return false;
    }

    public function get_total_tickets_abertos() {
        $this->db->where('status !=', 3);
        $query = $this->db->get('systems.show_tickets');
        return $query->num_rows();
    }

    public function get_tickets_abertos($limit, $offset) {
        $query = $this->db->select('ticket.*, cliente.informacoes as empresa')
            ->from('systems.show_tickets as ticket')
            ->join('showtecsystem.cad_clientes as cliente', 'cliente.id = ticket.id_cliente', 'inner')
            ->where('ticket.status !=', 3)
            ->order_by('ticket.status', 'asc')
            ->limit($offset,$limit)
            ->get();
        if ($query->num_rows())
            return $query->result();
        return false;
    }

    public function get_total_tickets_fechados() {
        $this->db->where('status', 3);
        $query = $this->db->get('systems.show_tickets');
        return $query->num_rows();
    }

    public function get_tickets_fechados($limit,$offset) {
        $query = $this->db->select('ticket.*, cliente.informacoes as empresa')
            ->from('systems.show_tickets as ticket')
            ->join('showtecsystem.cad_clientes as cliente', 'cliente.id = ticket.id_cliente', 'inner')
            ->where('ticket.status', 3)
            ->order_by('ticket.status', 'asc')
            ->limit($offset,$limit)
            ->get();
        if ($query->num_rows())
            return $query->result();
        return false;
    }

    public function get_mensagem($id) {
        // $this->conn = $this->load->database('rastreamento', TRUE);
        $this->db->order_by("data_abertura", "ASC");
        $this->db->where('id', $id);
        $tickets = $this->db->get('systems.show_tickets')->result();

        foreach ($tickets as $key => $ticket)
        {
            if ($ticket->arquivo)
            {
                $this->db->select('link');
                $this->db->where('id_referencia', $ticket->id);
                $result = $this->db->get('systems.upload_arquivos')->result();

                if (!empty($result)) {
                    $ticket->arquivo = $result[0]->link;
                }
            }
        }

        return $tickets;
    }

    public function get_total_mensagens($idticket) {
        $this->db->where('id_ticket',$idticket);
        $query = $this->db->get('systems.tickets_mensagens');
        return $query->num_rows();
    }

    public function get_resposta($id) {
        $this->db->order_by("data_resposta", "ASC");
        $this->db->where('id_ticket',$id);

        $query = $this->db->get('systems.tickets_mensagens');
        if ($query->num_rows()) {
            $tickets = $query->result();
            foreach ($tickets as $id => $ticket) {
                $ticket->usuario =  @$this->get_usuario($ticket->id_user);
                if (is_numeric($ticket->arquivo))
                    $ticket->arquivo = $this->db->select('link')->where('id', $ticket->arquivo)->get('systems.upload_arquivos')->result()[0]->link;
            }
            return $tickets;
        }
        return false;
    }

    public function get_usuario($id) {
        $query = $this->db->select('nome_usuario')->where('code', $id)->limit(1)->get('showtecsystem.usuario_gestor');
        return $query->row()->nome_usuario;
    }

    public function fechar_t($id_ticket, $status_atual, $data_fechamento) {
        $status = 3;
        $dados = array(
            'data_fechamento' => $data_fechamento,
            'status_anterior' => $status_atual,
            'status' => $status
        );
        $this->db->where('id', $id_ticket);
        $resposta = $this->db->update('systems.show_tickets', $dados);
        if ($resposta)
            return true;
        else
            return false;
    }

    public function reabrir_t($id_ticket, $status_anterior) {
        $dados = array(
            'status' => $status_anterior
        );
        $this->db->where('id', $id_ticket);
        $resposta = $this->db->update('systems.show_tickets', $dados);
        if ($resposta)
            return true;
        else
            return false;
    }

    public function tempo_espera($id,$status) {
        if ($status == 1) {
            $tickets = $this->get_mensagem($id);
            foreach ($tickets as $ticket)
                $ultima_interacao = $ticket->ultima_interacao;
            $inicio = dh_for_humans($ultima_interacao);
            $fim = dh_for_humans(date('Y-m-d H:i:s'));
            $inicio = date_create_from_format('d/m/Y H:i:s', $inicio);
            $fim = date_create_from_format('d/m/Y H:i:s', $fim);
            $intervalo = $inicio->diff($fim);
            $mes = $intervalo->format('%M');
            $dia = $intervalo->format('%D');
            $hora = $intervalo->format('%H');
            $minuto = $intervalo->format('%I');
            $segundo = $intervalo->format('%S');
            $total_dias = $intervalo->format('%a');
            if ($mes == '00') {
                if ($dia == '00' && $hora != '00') {
                    $mensagem = $hora.'h e '.$minuto.'m';
                }
                if ($dia == '00' && $hora == '00' && $minuto != '00') {
                    $mensagem = $minuto.'m e '.$segundo.'s';
                }
                if ($dia == '00' && $hora == '00' && $minuto == '00') {
                    $mensagem = $segundo.' segundos';
                }
                if ($dia != '00'){
                    $mensagem = $dia.'d e '.$hora.'h';
                }
            } else {
                $mensagem = $mes.' mes(es) e '.$dia.' dia(s) ';
            }
            if ($total_dias < 1) {
                $msg = 'Aguardando há '.$mensagem;
                return $msg;
            } else {
                $msg = 'Aguardando há '.$mensagem;
                return $msg;
            }
        } else if ($status == 2) {
            $tickets = $this->get_mensagem($id);
            foreach ($tickets as $ticket){
                $ultima_interacao = $ticket->ultima_interacao;
                $interacao_ant = $ticket->interacao_anterior;
            }
            $inicio = dh_for_humans($interacao_ant);
            $fim = dh_for_humans($ultima_interacao);
            $inicio = date_create_from_format('d/m/Y H:i:s', $inicio);
            $fim = date_create_from_format('d/m/Y H:i:s', $fim);

            $intervalo = $inicio->diff($fim);

            $mes = $intervalo->format('%M');
            $dia = $intervalo->format('%D');
            $hora = $intervalo->format('%H');
            $minuto = $intervalo->format('%I');
            $segundo = $intervalo->format('%S');
            $total_dias = $intervalo->format('%a');


            if ($mes == '00') {

                if ($dia == '00' && $hora != '00') {
                    $mensagem = $hora.'h e '.$minuto.'m';
                }
                if ($dia == '00' && $hora == '00' && $minuto != '00') {
                    $mensagem = $minuto.'m e '.$segundo.'s';
                }
                if ($dia == '00' && $hora == '00' && $minuto == '00') {
                    $mensagem = $segundo.' segundos';
                }
                if ($dia != '00'){
                    $mensagem = $dia.'d e '.$hora.'h';
                }

            }else{
                $mensagem = $mes.' mes(es) e '.$dia.' dia(s) ';
            }

            $msg = 'Respondido em '.$mensagem;
            return $msg;

        } else if ($status == 3) {
            $tickets = $this->get_mensagem($id);
            foreach ($tickets as $ticket)
                $ultima_interacao = $ticket->ultima_interacao;
            $inicio = dh_for_humans($ultima_interacao);
            $fim = dh_for_humans(date('Y-m-d H:i:s'));
            $inicio = date_create_from_format('d/m/Y H:i:s', $inicio);
            $fim = date_create_from_format('d/m/Y H:i:s', $fim);
            $intervalo = $inicio->diff($fim);
            $mes = $intervalo->format('%M');
            $dia = $intervalo->format('%D');
            $hora = $intervalo->format('%H');
            $minuto = $intervalo->format('%I');
            $segundo = $intervalo->format('%S');
            $total_dias = $intervalo->format('%a');
            if ($mes == '00') {
                if ($dia == '00' && $hora != '00') {
                    $mensagem = $hora.'h e '.$minuto.'m';
                }
                if ($dia == '00' && $hora == '00' && $minuto != '00') {
                    $mensagem = $minuto.'m e '.$segundo.'s';
                }
                if ($dia == '00' && $hora == '00' && $minuto == '00') {
                    $mensagem = $segundo.' segundos';
                }
                if ($dia != '00'){
                    $mensagem = $dia.'d e '.$hora.'h';
                }
            } else {
                $mensagem = $mes.' mes(es) e '.$dia.' dia(s) ';
            }
            $msg = 'Concluído';
            return $msg;
        }
    }

    public function enviar_email($departamento,$assunto2,$nome_user,$data_abertura,$mensagem2) {
        $cabecario = "Ticket gerado.";
        $assunto_email = "TICKET GERADO - SHOW TECNOLOGIA";
        $depart = $departamento;
        $assunto = $assunto2;
        $nome_usuario = $nome_user;
        $data = $data_abertura;
        $mensagem = $mensagem2;
        $email= "";

        if ($depart == "Suporte"){
            $email = "suporte@showtecnologia.com,".$nome_usuario;

        }else if ($depart == "Comercial") {
            $email = "cristiane@showtecnologia.com,".$nome_usuario;

        }else if ($depart == "Financeiro") {
            $email = "financeiro@showtecnologia.com,".$nome_usuario;

        }else{
            $email = "suporte@showtecnologia.com,".$nome_usuario;
        }

        $this->db->select_max('id');
        $query = $this->db->get('systems.show_tickets');

        if ($query->num_rows()) {
            $tickets = $query->result();
            foreach ($tickets as $ticket){
                $id = $ticket->id;
            }
        } else {
            $id = "#";
        }
        $this->emails($id,$mensagem,$data,$email,$nome_usuario,$assunto,$cabecario,$assunto_email);
    }

    public function enviar_email_resposta($id, $mensagem, $data_resposta2) {
        $cabecario = "Ticket respondido.";
        $assunto_email = "TICKET RESPONDIDO - SHOW TECNOLOGIA";
        $data = $data_resposta2;
        $email= "";
        $tickets = $this->get_mensagem($id);
        foreach ($tickets as $ticket) {
            $assunto = $ticket->assunto;
            $departamento = $ticket->departamento;
            $usuario = $ticket->nome_usuario;
            $cliente = $ticket->cliente;
        }
        if ($departamento == "Suporte")
            $email = "suporte@showtecnologia.com,".$usuario;
        else if ($departamento == "Comercial")
            $email = "cristiane@showtecnologia.com,".$usuario;
        else if ($departamento == "Financeiro")
            $email = "financeiro@showtecnologia.com,".$usuario;
        else
            $email = "suporte@showtecnologia.com,".$usuario;
        $usuario = $departamento;
        $this->emails($id, $mensagem, $data, $email, $usuario, $assunto, $cabecario, $assunto_email, $cliente);
    }

    private function emails($id2, $mensagem2, $data2, $email2, $nome_usuario2, $assunto2, $cabecario2, $assunto_email2, $cliente2) {

        $id = $id2;
        $mensagem = $mensagem2;
        $data = dh_for_humans($data2);
        $email = $email2;
        $nome_usuario = $nome_usuario2;
        $assunto = $assunto2;
        $cabecario = $cabecario2;
        $assunto_email = $assunto_email2;
        $cliente = $cliente2;

        $quant_msg = $this->get_total_mensagens($id);
        $cont = 0;
        $tickets = $this->get_mensagem($id);
        $tickets2 = $this->get_resposta($id);

        $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title></title>

		<style type="text/css">
		  @media only screen and (max-width: 480px) {
			body, table, td, p, a, li, blockquote {
			  -webkit-text-size-adjust: none !important;
			}
			body{
			  width: 100% !important;
			  min-width: 100% !important;
			}
			td[id=bodyCell] {
			  padding: 10px !important;
			}
			table[class=kmTextContentContainer] {
			  width: 100% !important;
			}
			table[class=kmBoxedTextContentContainer] {
			  width: 100% !important;
			}
			td[class=kmImageContent] {
			  padding-left: 0 !important;
			  padding-right: 0 !important;
			}
			img[class=kmImage] {
			  width:100% !important;
			}
			table[class=kmSplitContentLeftContentContainer],
			table[class=kmSplitContentRightContentContainer],
			table[class=kmColumnContainer] {
			  width:100% !important;
			}
			table[class=kmSplitContentLeftContentContainer] td[class=kmTextContent],
			table[class=kmSplitContentRightContentContainer] td[class=kmTextContent],
			table[class="kmColumnContainer"] td[class=kmTextContent] {
			  padding-top:9px !important;
			}
			td[class="rowContainer kmFloatLeft"],
			td[class="rowContainer kmFloatLeft firstColumn"],
			td[class="rowContainer kmFloatLeft lastColumn"] {
			  float:left;
			  clear: both;
			  width: 100% !important;
			}
			table[id=templateContainer],
			table[class=templateRow],
			table[id=templateHeader],
			table[id=templateBody],
			table[id=templateFooter] {
			  max-width:600px !important;
			  width:100% !important;
			}

			  h1 {
				font-size:24px !important;
				line-height:100% !important;
			  }


			  h2 {
				font-size:20px !important;
				line-height:100% !important;
			  }


			  h3 {
				font-size:18px !important;
				line-height:100% !important;
			  }


			  h4 {
				font-size:16px !important;
				line-height:100% !important;
			  }


			  td[class=rowContainer] td[class=kmTextContent] {
				font-size:18px !important;
				line-height:100% !important;
				padding-right:18px !important;
				padding-left:18px !important;
			  }


			  td[class=headerContainer] td[class=kmTextContent] {
				font-size:18px !important;
				line-height:100% !important;
				padding-right:18px !important;
				padding-left:18px !important;
			  }


			  td[class=bodyContainer] td[class=kmTextContent] {
				font-size:18px !important;
				line-height:100% !important;
				padding-right:18px !important;
				padding-left:18px !important;
			  }


			  td[class=footerContent] {
				font-size:18px !important;
				line-height:100% !important;
			  }

			 td[class=footerContent] a {
				display:block !important;
			 }

		  }
		</style>

		</head>
		<body style="margin: 0; padding: 0; background-color: #c7c7c7">
		<center>
		<table align="center" border="0" cellpadding="0" cellspacing="0" id="bodyTable" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; background-color: #c7c7c7; height: 100%; margin: 0; width: 100%">
		<tbody>
		<tr>
		<td align="center" id="bodyCell" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top: 50px; padding-left: 20px; padding-bottom: 20px; padding-right: 20px; border-top: 0; height: 100%; margin: 0; width: 100%">
		<table border="0" cellpadding="0" cellspacing="0" id="templateContainer" width="600" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border: 1px solid #aaa; background-color: #f4f4f4">
		<tbody>
		<tr>
		<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<table border="0" cellpadding="0" cellspacing="0" class="templateRow" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="rowContainer kmFloatLeft" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		<tr>
		<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<table border="0" cellpadding="0" cellspacing="0" class="templateRow" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="rowContainer kmFloatLeft" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<table border="0" cellpadding="0" cellspacing="0" class="kmImageBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmImageBlockOuter">
		<tr>
		<td class="kmImageBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding:9px;" valign="top">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmImageContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmImageContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; padding-top:0px;padding-bottom:0;padding-left:9px;padding-right:9px;text-align: center;">
		<img align="center" alt="Show Tecnologia" class="kmImage" src="https://d3k81ch9hvuctc.cloudfront.net/company%2F9TqzGS%2Fimages%2Flogo-show.png" width="510" height="185" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; max-width: 100%; padding-bottom: 0; display: inline; vertical-align: bottom" />
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="kmDividerBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmDividerBlockOuter">
		<tr>
		<td class="kmDividerBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:18px;padding-bottom:18px;padding-left:18px;padding-right:18px;">
		<table class="kmDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border-top-width:1px;border-top-style:solid;border-top-color:#ccc;">
		<tbody>
		<tr><td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0"><span></span></td></tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmTextBlockOuter">
		<tr>
		<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; ">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
		<p style="margin: 0; padding-bottom: 1em"> </p>
		<h3 style="margin: 0; padding-bottom: 0"><strong>'.$cabecario.'</strong></h3>
		</tr>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">

			<table border="1" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border: 1px solid #ccc;background: #fff;border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
			  <thead>
				<tr>
				  <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Nº do ticket</th>
				  <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Assunto</th>
				  <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Cliente</th>
				  <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Data</th>
				</tr>
			  </thead>
			  <tbody class="kmTextBlockOuter">
				<tr>
				<td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.$id.'</td>
				<td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.$assunto.'</td>
				<td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.$cliente.'</td>
				<td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.$data.'</td>
				</tr>
			</tbody>
			</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmTextBlockOuter">
		<tr>
		<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:20px;padding-bottom:20px;">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">';

        if ($tickets) {
            foreach ($tickets as $ticket){
                if ($ticket->suporte == 'sim') {
                    $msg .= '<div>';
                    $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;"><strong>'.$ticket->departamento.':</strong></p>';
                    $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;">Olá '.$ticket->usuario.', estaremos analisando a sua solicitação, o seu contato é muito importante
						para nós, o mais breve possível estaremos lhe contactando. Obrigado.</p>';
                    $msg .= '</div>';
                } else {
                    $msg .= '<div>';
                    $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;"><strong>'.$ticket->usuario.':</strong></p>';
                    $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;">'.$ticket->mensagem.'</p>';
                    $msg .= '</div>';

                    $msg .= '<div>';
                    $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;"><strong>'.$ticket->departamento.':</strong></p>';
                    $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;">Olá '.$ticket->usuario.', estaremos analisando a sua solicitação, o seu contato é muito importante
						para nós, o mais breve possível estaremos lhe contactando. Obrigado.</p>';
                    $msg .= '</div>';
                }
                if ($tickets2) {
                    foreach ($tickets2 as $ticket2) {

                        $cont += 1;

                        if ($cont == $quant_msg) {

                            if ($ticket2->id_user != 425) {

                                $msg .= '<div>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #0000FF;"><strong>'.$ticket->usuario.':</strong></p>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #0000FF;">'.$ticket2->resposta.'</p>';
                                $msg .= '</div>';

                            } else {

                                $msg .= '<div>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #0000FF;"><strong>'.$ticket->departamento.':</strong></p>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #0000FF;">'.$ticket2->resposta.'</p>';
                                $msg .= '</div>';

                            }

                        } else {

                            if ($ticket2->id_user != 425){

                                $msg .= '<div>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;"><strong>'.$ticket->usuario.':</strong></p>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;">'.$ticket2->resposta.'</p>';
                                $msg .= '</div>';

                            } else {

                                $msg .= '<div>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;"><strong>'.$ticket->departamento.':</strong></p>';
                                $msg .= '<p style="margin: 0; padding-bottom: 0; color: #000000;">'.$ticket2->resposta.'</p>';
                                $msg .= '</div>';
                            }
                        }
                    }
                }
            }
        }
        $msg .= '<p style="margin: 0; padding-bottom: 1em"> </p>
		<p style="margin: 0; padding-bottom: 1em"> </p>
		<p style="margin: 0; padding-bottom: 0">Para responder ou consultar o histórico deste atendimento, clique no link abaixo:</p>
		<p style="margin: 0; padding-bottom: 0"><a href="https://gestor.showtecnologia.com/gestor/index.php/webdesk/ticket/'.$id.'">Ticket '.$id.'</a></p>
		<p style="margin: 0; padding-bottom: 1em"> </p>
		<p style="margin: 0; padding-bottom: 1em"><strong>Atenciosamente,</strong></p>
		<p style="margin: 0; padding-bottom: 0"><strong>Show Tecnologia</strong></p>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="kmDividerBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmDividerBlockOuter">
		<tr>
		<td class="kmDividerBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:18px;padding-bottom:18px;padding-left:18px;padding-right:18px;">
		<table class="kmDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border-top-width:1px;border-top-style:solid;border-top-color:#ccc;">
		<tbody>
		<tr><td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0"><span></span></td></tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmTextBlockOuter">
		<tr>
		<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:20px;padding-bottom:20px;">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
		<p style="margin: 0; padding-bottom: 1em; text-align: center;"><span style="font-size: 9px;"><b>Este é um email automático, por favor não responda. Caso tenha alguma dúvida sobre o conteúdo deste email entre em contato através do email suporte@showtecnologia.com ou pelo telefone 83 3271.4060</b></span></p>
		<p style="margin: 0; padding-bottom: 0"> </p>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</center>
		</body>
		</html>';

        $dados = array(
            'assunto_envio' => $assunto_email,
            'conteudo_envio' => $msg,
            'emails_envio' => $email,
        );

        $resposta = $this->db->insert('systems.envio_cron', $dados);
        if ($resposta)
            return true;
        else
            return false;
    }

    public function validar_datas($data_ini, $data_fim) {
        if ($data_ini > $data_fim)
            return false;
        else
            return true;
    }

    function numberOfTicketsPerMonth($id) {
        $data   = array();
        $users  = array();
        $time   = array();
        $months = array();
        $months['Janerio'] = $months['Fevereiro'] = $months['Março'] = 0;
        $months['Abril'] = $months['Maio'] = $months['Junho'] = 0;
        $months['Julho'] = $months['Agosto'] = $months['Setembro'] = 0;
        $months['Outubro'] = $months['Novembro'] = $months['Dezembro'] = 0;
        $querys = $this->db->select('id, data_abertura, data_fechamento, usuario')->where('id_cliente', $id)->get('systems.show_tickets')->result();
        foreach ($querys as $key => $v) {
            $sql = "SELECT COUNT(usuario) AS count FROM systems.show_tickets WHERE usuario = '{$v->usuario}';";
            $amount = $this->db->query($sql)->result()[0]->count;
            if (!array_key_exists($v->usuario, $users))
                $users[$v->usuario] = array('amount' => $amount);
            switch (substr($v->data_abertura, 5, 2)) {
                case '01':
                    $months['Janerio']++;
                    break;
                case '02':
                    $months['Fevereiro']++;
                    break;
                case '03':
                    $months['Março']++;
                    break;
                case '04':
                    $months['Abril']++;
                    break;
                case '05':
                    $months['Maio']++;
                    break;
                case '06':
                    $months['Junho']++;
                    break;
                case '07':
                    $months['Julho']++;
                    break;
                case '08':
                    $months['Agosto']++;
                    break;
                case '09':
                    $months['Setembro']++;
                    break;
                case '10':
                    $months['Outubro']++;
                    break;
                case '11':
                    $months['Novembro']++;
                    break;
                case '12':
                    $months['Dezembro']++;
                    break;
            }
            $time[$v->id] = diff_entre_datas_sintatico($v->data_abertura, $v->data_fechamento, 'horas');
        }
        // $diff = $this->getDiff($id);
        function my_sort($a, $b) {
            if ($a == $b) return 0;
            return ($a > $b) ? -1 : 1 ;
        }
        uasort($time, 'my_sort');
        uasort($users, 'my_sort');
        uasort($months, 'my_sort');
        $data['time']   = $time;
        $data['users']  = key($users);
        $data['months'] = key($months);
        $data['valor'] = array_shift($months);
        // pr($data); die;
        // $data['status'] = $diff[0]->diff >= 90 ? 'Inadimplente' : 'Adimplente' ;
        return $data;
    }

    public function getDiff($id) {
        $sql  = 'SELECT DATEDIFF(now(), data_vencimento) AS diff FROM showtecsystem.cad_faturas WHERE id_cliente = '.$id.' AND status IN (0, 2) ORDER BY data_vencimento DESC;';
        return $this->db->query($sql)->result();
    }

    public function getRanking() {
        $sql = "SELECT count(*) AS 'num', id_cliente, cliente FROM systems.show_tickets where id_cliente > 0 GROUP BY id_cliente ORDER BY num DESC;";
        return $this->db->query($sql)->result();
    }

    // ======== DADOS RATING ATENDIMENTOS ÚLTIMOS 30 DIAS========= \\
    public function get_ratings(){
        $mesAnterior = date('Y-m-d H:i:s', strtotime('-30 days'));
        $exc = $this->db->select('id_avaliacao')->where('data_avaliacao >= ', $mesAnterior)->where('nota', 5)->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
        $oti = $this->db->select('id_avaliacao')->where('data_avaliacao >= ', $mesAnterior)->where('nota', 4)->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
        $bom = $this->db->select('id_avaliacao')->where('data_avaliacao >= ', $mesAnterior)->where('nota', 3)->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
        $rui = $this->db->select('id_avaliacao')->where('data_avaliacao >= ', $mesAnterior)->where('nota', 2)->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
        $pes = $this->db->select('id_avaliacao')->where('data_avaliacao >= ', $mesAnterior)->where('nota', 1)->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
        $dados = array(
            'e' => $exc,
            'o' => $oti,
            'b' => $bom,
            'r' => $rui,
            'p' => $pes
        );
        return json_encode($dados);
    }

    // ======== DADOS RATING ATENDIMENTOS ========= \\
    public function get_ratings_mes(){
        $pos = $this->db->select('id_avaliacao')->where('MONTH(data_avaliacao)', date('m'))->where_in('nota', [5, 4, 3])->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
        $neg = $this->db->select('id_avaliacao')->where('MONTH(data_avaliacao)', date('m'))->where_in('nota', [2, 1])->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
        $dados = array(
            'p' => $pos,
            'n' => $neg
        );
        return json_encode($dados);
    }

    /** Retorna dados de um ticket */
    public function getDataTicket($idTicket) {
        // Retorna vázio caso não seja passado ticket
        if (!$idTicket && !is_numeric($idTicket)) return [];

        $ticket = $this->db->select('st.*, st.mensagem as resposta, st.data_abertura as data_resposta, ua.link')
            ->join('systems.upload_arquivos as ua', 'ua.id_referencia = st.id', 'left')
            ->get_where('systems.show_tickets as st', [ 'st.id' => $idTicket ])
            ->result_array();

        if (is_array($ticket) && !empty($ticket)) {
            $mensagens = $this->db
                ->select('tm.*, tm.id_user as id_usuario, ua.link')
                ->join('systems.upload_arquivos as ua', 'ua.id = tm.arquivo', 'left')
                ->order_by('id', 'ASC')
                ->get_where('systems.tickets_mensagens as tm', [ 'tm.id_ticket' => $idTicket ])
                ->result_array();

            if (is_array($mensagens) && !empty($mensagens))
                $ticket = array_merge($ticket, $mensagens);
        }

        return $ticket;
    }

    public function get_user_raking(){
        $rank = $this->db->select('count(a.nota), sum(a.nota), (sum(a.nota) / count(a.nota)) as media , u.usuario, a.id_usuario')
            ->group_by('u.usuario')
            ->order_by('(sum(a.nota) / count(a.nota))', 'asc')
            ->order_by('count(a.nota)', 'asc')
            ->limit(5)
            ->join('showtecsystem.usuario_gestor as u', 'a.id_usuario = u.code')
            ->get('showtecsystem.avaliacoes_atendimentos as a')
            ->result();
//        pr($rank);die;
        $ranking = [];
        foreach ($rank as $key => $user){
            $exc = $this->db->select('nota')->where(array('id_usuario' => $user->id_usuario, 'nota' => 5))->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
            $oti = $this->db->select('nota')->where(array('id_usuario' => $user->id_usuario, 'nota' => 4))->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
            $bom = $this->db->select('nota')->where(array('id_usuario' => $user->id_usuario, 'nota' => 3))->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
            $rui = $this->db->select('nota')->where(array('id_usuario' => $user->id_usuario, 'nota' => 2))->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
            $pes = $this->db->select('nota')->where(array('id_usuario' => $user->id_usuario, 'nota' => 1))->get('showtecsystem.avaliacoes_atendimentos')->num_rows();
            $ranking[] = array(
                '#' => ($key + 1).'º',
                'nome' => $user->usuario,
                'p' => $pes,
                'r' => $rui,
                'b' => $bom,
                'o' => $oti,
                'e' => $exc,
                'média' => $user->media
            );
        }
        return json_encode($ranking);
    }

    public function get_tickets_new(){
        $query = $this->db->select('id, assunto, nome_usuario, cliente, data_abertura as data, status')->order_by('id', 'desc')->limit(5)->get('systems.show_tickets')->result();
        $dados = [];
        $status = null;
        foreach ($query as $ticket){
            switch ($ticket->status){
                case 1:
                    $status = '<label class="badge badge-warning">Aguardando</label>';
                    break;
                case 2:
                    $status = '<label class="badge badge-info">Respondido</label>';
                    break;
                case 3:
                    $status = '<label class="badge badge-success">Concluido</label>';
                    break;
            }
            $dados[] = array(
                'cliente' => $ticket->cliente,
                'usuário' => $ticket->nome_usuario,
                'assunto' => $ticket->assunto,
                'data' => dh_mktime_for_humans($ticket->data),
                'status' => $status,
                'last_id' => $ticket->id
            );
        }
        return json_encode($dados);
    }

    public function lastIdTicket(){

        return $this->db->select('id')->order_by('id', 'desc')->limit(1)->get('systems.show_tickets')->result()[0]->id;

    }

    function fileSave($dados) {
        $this->db->insert('systems.upload_arquivos', $dados);
        return $this->db->insert_id();
    }

    public function criar_ticket($dados) {
		if ($this->db->insert('systems.show_tickets', $dados))
			return $this->db->insert_id();
		return false;
	}

    public function get_cliente($cliente) {
		return $this->db->select('nome, id, cpf, cnpj')->limit(1)->get_where('showtecsystem.cad_clientes', array('id' => $cliente))->row();
	}

    public function set_nomeResponsavel($nome_responsavel, $ticketID){
        $this->db->set('responsavel', $nome_responsavel);
        $this->db->where('id', $ticketID);
        return $this->db->update('systems.show_tickets');
    }

    // GET TICKETS DO CLIENTE //
	public function getAjaxListTicketId($id_cliente) {
		$query = $this->db->select('id')
            ->where('id_cliente', $id_cliente)
            ->order_by('id', 'DESC')
            ->get('systems.show_tickets', 10, 0);

        if($query->num_rows()){
            return $query->result();
        }
        return false;
	}

    /*
    * LISTA TODOS OS TICKETS DO CLIENTE
    */
	public function getAjaxListTicketClient($id_cliente, $select='*') {
		$query = $this->db->select($select)
            ->where('id_cliente', $id_cliente)
            ->order_by('id', 'DESC')
            ->get('systems.show_tickets');

        if($query->num_rows()){
            return $query->result();
        }
        return false;
	}

    /*
    * LISTA TODOS OS TICKETS DO CLIENTE
    */
    public function getRespostaId($id_resposta) {
        $query = $this->db->select('*')
        ->where('id', $id_resposta)
        ->get('systems.tickets_mensagens');
        if ($query->num_rows()) {
            $resposta = $query->row();
                $resposta->usuario =  $this->get_usuario($resposta->id_user);
                if ($resposta->arquivo)
                    $resposta->arquivo = $this->db->select('link')->where('id', $resposta->arquivo)->get('systems.upload_arquivos')->result()[0]->link;

            return $resposta;
        }
        return false;
    }

    /** Realiza update de um ticket */
    public function updateTicket($id_ticket, $dados) {
        $this->db->update('systems.show_tickets', $dados, array('id' => $id_ticket));
        if($this->db->affected_rows() > 0)
            return true;
        return false;
    }

    /** Realiza inserção de uma nova mensagem */
    public function insertMessage($data, $returnId = false) {
        $insert = $this->db->insert('systems.tickets_mensagens', $data);
        return $returnId ? $this->db->insert_id() : $insert;
    }

    function getCountItensContratoAtivos($idCliente) {
        return $this->db->where([ 'id_cliente' => $idCliente, 'status' => 'ativo' ])->from('showtecsystem.contratos_veiculos')->count_all_results();
    }

    function getCountProdutosAtivos($idCliente) {
        $ids = [];
        $produtos = $this->db->select('ids_produtos')->get_where('showtecsystem.cad_clientes', [ 'id' => $idCliente ])->row();

        if ($produtos && $produtos->ids_produtos) {
            $ids = json_decode($produtos->ids_produtos);
        }

        return count($ids);
    }

    function getCountTicketsAbertos($idCliente) {
        return $this->db->where([ 'id_cliente' => $idCliente, 'status !=' => '3' ])->from('systems.show_tickets')->count_all_results();
    }

    function getCountTagsTicket($idTicket) {
        return $this->db->get_where('systems.tags_tickets', [ 'status' => '1', 'id_ticket' => $idTicket ])->result();
    }

    function updateTag($where, $update) {
        return $this->db->update('systems.tags_tickets', $update, $where);
    }

    function insertTagTicket($data) {
        $get = $this->db->get_where('systems.tags_tickets', [ 'nome' => $data['nome'], 'id_ticket' => $data['id_ticket'] ])->row();
        
        if ($get) return $this->db->update('systems.tags_tickets', [ 'status' => '1' ], [ 'id' => $get->id ]);
        else return $this->db->insert('systems.tags_tickets', $data);
    }
}
