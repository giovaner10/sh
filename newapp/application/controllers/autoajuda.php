<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class autoajuda extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->auth->is_logged('admin');
        $this->auth->is_allowed('vis_autoajuda');
        $this->url_api_autoajuda = config_item('url_api_autoajuda');
    }

    public function index()
    {
        $dados["titulo"] = lang('autoajuda');
        $dados["load"] = ["jquery-ui", "bootstrap-multiselect"];
        $dados["url_api_autoajuda"] = $this->url_api_autoajuda;

        $this->load->view("new_views/fix/header", $dados);
        $this->load->view("servicos/autoajuda", $dados);
        $this->load->view("fix/footer_NS");
    }
}
