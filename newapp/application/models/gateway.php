<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gateway extends CI_Model {

	private $db_rastreamento;

	public function __construct(){
		parent::__construct();
		$this->db_rastreamento = $this->load->database('rastreamento', TRUE);
	}

	/**
	* @author Guilherme
	* Verifica quais gateways estão parados a mais de 15 minutos
	**/

	public function get_parados() {
		$monitoramento = array();
		$now = json_decode(file_get_contents('http://api.geonames.org/timezoneJSON?formatted=true&lat=-30.02&lng=-51.10&username=showtecnologia&style=full'));
        $now = $now->time.':'.date('s');
		$downtime = 15;
		$date = date('Y-m-d', strtotime("{$now} +1 day"));

		$this->db_rastreamento->select('MAX(DATASYS) DATASYS')
			     			  ->like('ID', 'SCO', 'after')
			     			  ->where('DATASYS <', $date);
		$continental = $this->db_rastreamento->get('rastreamento.last_track');

		if($continental->num_rows() > 0) {
			$continental = strtotime($continental->row()->DATASYS);
			$monitoramento[] = array(
				'gateway' => 'Continental',
				'data' => date('d/m/Y H:i:s', $continental),
				'status' => round(abs($continental - strtotime($now)) / 60,2) > $downtime ? 'Parado' : 'Atualizado'
			);
		}

		$this->db_rastreamento->select('MAX(DATASYS) DATASYS')
			     			  ->like('ID', 'CAL', 'after')
			     			  ->where('DATASYS <', $date);
		$calamp = $this->db_rastreamento->get('rastreamento.last_track');

		if($calamp->num_rows() > 0) {
			$calamp = strtotime($calamp->row()->DATASYS);
			$monitoramento[] = array(
				'gateway' => 'Calamp',
				'data' => date('d/m/Y H:i:s', $calamp),
				'status' => round(abs($calamp - strtotime($now)) / 60,2) > $downtime ? 'Parado' : 'Atualizado'
			);
		}

		$this->db_rastreamento->select('MAX(DATASYS) DATASYS')
			     			  ->like('ID', '12', 'after')
			     			  ->or_like('ID', '82', 'after')
			     			  ->where('DATASYS <', $date);
		$maxtrack = $this->db_rastreamento->get('rastreamento.last_track');

		if($maxtrack->num_rows() > 0) {
			// $maxtrack = strtotime($maxtrack->row()->DATA);
			$maxtrack = strtotime($maxtrack->row()->DATASYS . ' -1 hour');
			$monitoramento[] = array(
				'gateway' => 'Maxtrack',
				'data' => date('d/m/Y H:i:s', $maxtrack),
				'status' => round(abs($maxtrack - strtotime($now)) / 60,2) > $downtime ? 'Parado' : 'Atualizado'
			);
		}

		return $monitoramento;
	}

}