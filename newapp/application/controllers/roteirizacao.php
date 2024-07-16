<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roteirizacao extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->auth->is_logged('admin');
        $this->load->model('auth');
    }

    public function index(){

        $dados['titulo'] = "ShowTecnologia";

        $this->load->view('fix/header', $dados);
        $this->load->view('roteiriza/index');
        $this->load->view('fix/footer');

    }

    public function ready(){

        $config['upload_path']          = 'application/upload/';
        $config['allowed_types']        = '*';
        $config['max_size']             = 800;
        $config['file_ext_tolower']     = true;
        $config['encrypt_name']         = false;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->set_allowed_types('*');



        if (!$this->upload->do_upload('xml')) {
            echo $this->upload->display_errors('', '');
        } else {
            $nameXML = $this->upload->data()['file_name'];
            $file_xml = 'application/upload/'.$nameXML;
            $xml = simplexml_load_file($file_xml);

            $dados = array();

            foreach($xml->NFe as $nfe) {

                $emissor = $nfe[0]->infNFe->emit;
                $destinatario = $nfe[0]->infNFe->dest;

                $dados['Emissor'] = array(

                    'cnpj' => $emissor->CNPJ->asXML(),
                    'nome' => $emissor->xNome->asXML(),
                    'rua'  => $emissor->enderEmit->xLgr->asXML(),
                    'bairro' => $emissor->enderEmit->xBairro->asXML(),
                    'municipio' => $emissor->enderEmit->xMun->asXML(),
                    'uf' => $emissor->enderEmit->UF->asXML(),
                    'cep' => $emissor->enderEmit->CEP->asXML(),
                    'país' => $emissor->enderEmit->xPais->asXML()
                );

                $dados['Destinatario'] = array(

                    'cpf' => $destinatario->CPF->asXML(),
                    'nome' => $destinatario->xNome->asXML(),
                    'rua' => $destinatario->enderDest->xLgr->asXML(),
                    'numero' => $destinatario->enderDest->nro->asXML(),
                    'complemento' => $destinatario->enderDest->xCpl->asXML(),
                    'bairro' => $destinatario->enderDest->xBairro->asXML(),
                    'municipio' => $destinatario->enderDest->xMun->asXML(),
                    'uf' => $destinatario->enderDest->UF->asXML(),
                    'cep' => $destinatario->enderDest->CEP->asXML(),
                    'país' => $destinatario->enderDest->xPais->asXML(),
                    'fone' => $destinatario->enderDest->fone->asXML()
                );

            }

            echo json_encode($dados);

        }



    }

}