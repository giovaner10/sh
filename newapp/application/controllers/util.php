<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Util extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function tab_actived($name_tab, $tab, $default = '#usuarios') {
		
		$tab = $this->session->userdata($name_tab);
		if($tab) {
			echo $tab;
		} else {
			echo $default;
		}
	}
	
	public function tab_active($name_tab, $tab) {
		$this->session->set_userdata($name_tab, $tab);
	}
}