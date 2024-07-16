<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoramento extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('ticket');
		$this->load->model('mapa_calor');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
	}

	public function gateways() {
		$dados['titulo'] = 'SHOWNET';
		$this->load->view('fix/header', $dados);
		$this->load->view('monitoramento/gateways');
		$this->load->view('fix/footer');
	}

	function updateDateTicket() {
		$this->ticket->insert();
	}

	function search() {
		if ( $this->input->get('init') && $this->input->get('end') )
			echo json_encode( $this->ticket->search( $this->input->get('init'), $this->input->get('end') ) );
		else if ( $this->input->get('init') )
			echo json_encode( $this->ticket->search( $this->input->get('init') ) );
		else
			echo json_encode( $this->ticket->search() );
	}

	function amount() {
		$dados['vector'] = $this->ticket->amount();
		echo $dados['vector'] ? json_encode($dados['vector']) : null;
	}

	public function tickets() {
		$dados['titulo'] = lang('tickets');
		$dados['load'] = array('ag-grid', 'select2', 'mask');

		$this->mapa_calor->registrar_acessos_url(site_url('/monitoramento/tickets'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('monitoramento/new_tickets');
		$this->load->view('fix/footer_NS');
	}

	public function tickets_old() {
		$dados['titulo'] = 'SHOWNET';
		$dados['url']    = base_url() . 'index.php/monitoramento/ticketsData';
		$this->mapa_calor->registrar_acessos_url(site_url('/monitoramento/tickets'));
		$this->load->view('fix/header', $dados);
		$this->load->view('monitoramento/tickets');
		$this->load->view('fix/footer');
	}

	public function ajax_read_log_calamp() {
		$log = FCPATH.APPPATH."logs/gateway.txt"; 
		$i=0 ;
		$lines=1 ;
		$fp = fopen($log,"r") ;
		if(is_resource($fp)){
			fseek($fp,0,SEEK_END) ;
			$a = ftell($fp) ;
			while($i <= $lines){
				if(fgetc($fp) == "\n"){
					echo (fgets($fp));
					$i++ ;
				}
				fseek($fp,$a) ;
				$a-- ;
			}
		}
	}
}

/* End of file monitoramento.php */
/* Location: ./application/controllers/monitoramento.php */