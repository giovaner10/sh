<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crm_assuntos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('crm_assunto');
        $this->load->model('auth');
    }

    public function listar_assuntos(){
        $assuntos = $this->crm_assunto->list_assuntos();
		if ($assuntos) {
            echo json_encode( array('status' =>'OK', 'results' => $assuntos ));
		}else {
            return false;
        }
    }

    public function get_assunto($id_assunto){
        $assunto = $this->crm_assunto->get_assunto();
		if ($assunto) {
            echo json_encode( array('status' =>'OK', 'results' => $assunto ));
		}else {
            return false;
        }
    }

}
