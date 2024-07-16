<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Posto extends CI_Controller {

	function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('posto_m', 'posto');
	}

	function getPosto() {
		echo $this->posto->getPosto($this->input->get('id'));
	}
}