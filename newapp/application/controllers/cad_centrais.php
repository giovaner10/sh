<?php if (!defined('BASEPATH'))exit('No direct script access allowed');


class Cad_centrais extends CI_Controller
{

    public function _construct()
    {

        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->library('form_validation');
        $this->load->model('cadastro_centrais', 'centraisM');
        $this->load->database();
    }

    public function index() //método que chama para o menu
    {
        $this->load->model('cadastro_centrais', 'centraisM');
        $this->load->model('mapa_calor');

        $data['titulo'] = lang('cadastro_centrais');
        $data['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        // $data['centrais'] = $this->centraisM->getCentrais();
        $this->mapa_calor->registrar_acessos_url(site_url('/cad_centrais'));
        $this->load->view('new_views/fix/header', $data);
        $this->load->view('cadastroCentrais/cadastro_centrais_view', $data);
        $this->load->view('fix/footer_NS');      
       
    }

    public function inserir_centrais()
    {
        $this->load->model('cadastro_centrais', 'centraisM');
        $this->load->helper('util_helper');

        $dados['nome'] = $this->input->post('nome');
        $dados['ip'] = $this->input->post('ip');
        $dados['porta'] = $this->input->post('porta');
        $dados['ativa'] = $this->input->post('ativa');
        $dados['cnpj'] = $this->input->post('cnpj');
        $dados['id_central_mhs'] = $this->input->post('id_central_mhs');
        $dados['cliente'] = $this->input->post('cliente');

        if ($this->input->post('id') != NULL) {
            $this->centraisM->editar_cadastro($dados, $this->input->post('id'));
            echo json_encode(array(
                "success" => true,
                "message" => 'Central editada com sucesso!',
            ));
            exit;
        } else {

            //adiciona no banco do webservice do MHS                    
            $inserir_MHS = $this->centraisM->inserir_centrais_MHS($dados);
            //se não tiver erro no retorno
            if ($inserir_MHS->CodigoErro == 0) {

                $central_mhs = $inserir_MHS->idCentral;
                $dados['id_central_mhs'] = $central_mhs;

                //adiciona no banco `showtecsystem`.`cadastro_centrais` 
                if ($this->centraisM->add_central($dados) == true) {
                    echo json_encode(array(
                        "success" => true,
                        "message" => 'Central cadastrada com sucesso!',
                    ));
                    exit;
                } else {
                    echo json_encode(array(
                        "success" => false,
                        "message" => 'Já existe uma ID Central MHS cadadastrada!',
                    ));
                    exit;
                }
            } else {
                $mensagem = $inserir_MHS->Mensagem;
                echo json_encode(array(
                    "success" => false,
                    "message" => $mensagem . 'no Webservice',
                ));
            }
        }
    }
    

     public function ajaxListCentrais($centrais = null)
    {
        $this->load->model('cadastro_centrais', 'centraisM');

		if (!$this->input->is_ajax_request()){
			exit(lang("nenhum_acesso_direto_script_permitido"));
        }
        else{
            $centraisMHS = $this->centraisM->getCentrais();
        }
		

        $data = [];
		$x = 0;
		
		foreach($centraisMHS as $for) {
            $data[] = [
                'nome' => trim(ucwords(strtolower($for->nome))), # ex: Nome Sobrenome
                'ip' => trim($for->ip),
                'porta' => trim($for->porta),
                'ativa' => trim($for->ativa),
                'cnpj' => trim($for->cnpj),
                'id_central_mhs' => trim($for->id_central_mhs),
                'cliente' => trim($for->cliente),
            ];
        }

		echo json_encode($data);
        exit;
	}



    public function editar_centrais($id)
    {
        $this->load->model('cadastro_centrais', 'centraisM');

        if ($id == NULL) {
            redirect('/');
        }

        $query = $this->centraisM->get_by_id($id);

        if ($query == NULL) {

            redirect('/');
        }

        $dados['centrais'] = $query;

        $this->load->view('fix/header_NS', $dados);
        $this->load->view('cadastro_centrais/editar_cadastro_centrais', $dados);
        $this->load->view('fix/footer_NS');   

    }

}