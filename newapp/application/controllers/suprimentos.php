<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Suprimentos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('suprimento');
        $this->load->model('mapa_calor');
    }

    /*
    * CARREGA VIEW LIST SUPRIMENTOS
    */
    public function listar() {
        $dados['titulo'] = 'Suprimentos - '.lang('show_tecnologia');
        $this->mapa_calor->registrar_acessos_url(site_url('/suprimentos'));
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('suprimentos/listar');
        $this->load->view('fix/footer_NS');
    }

    /**
    * Função retorna cintas para o dataTable
    * @author Eberson Santos
    */
    public function ajax_cintas()
    {
        $retorno['data'] = [];

        $cintas = $this->suprimento->getSuprimentos(array('tipo'  => 1));

        foreach($cintas as $c) {
            $retorno['data'][] = array(
                'id' => $c->id,
                'serial' => $c->serial,
                'descricao' => $c->descricao,
                'data_cadastro' => $c->data_cadastro,
            );
        }

        echo json_encode($retorno);
    }

    /**
    * Função de cadastro das cintas
    * @author Eberson Santos
    *
    * @param String $serial = Serial da cinta
    * @param String $descricao = Descricao da cinta
    */
    public function add_cinta()
    {
        $serial = $this->input->post('cinta_serial');
        $descricao = $this->input->post('cinta_descricao');

        if ($serial && is_string($serial)) {

            $cinta = $this->suprimento->getSuprimentos(array('serial' => $serial, 'tipo' => 1));

            if($cinta){
                exit(json_encode(array('status' => false, 'msg' => 'Este serial já se encontra cadastrado!')));
            }else{

                $insert = array(
                    'serial' => $serial,
                    'descricao' => $descricao,
                    'tipo' => '1',
                );

                $id_suprimento = $this->suprimento->addSuprimento($insert);
                if ($id_suprimento) {
                    exit(json_encode(array('status' => true, 'msg' => 'Registro cadastrado com sucesso.')));
                } else {
                    exit(json_encode(array('status' => false, 'msg' => 'Não foi possível cadastrar a cinta. Tente novamente mais tarde!')));
                }
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
    * Função retorna powerbanks para o dataTable
    * @author Eberson Santos
    */
    public function ajax_powerbanks()
    {
        $retorno['data'] = [];

        $powerbanks = $this->suprimento->getSuprimentos(array('tipo'  => 2));

        foreach($powerbanks as $c) {
            $retorno['data'][] = array(
                'id' => $c->id,
                'serial' => $c->serial,
                'descricao' => $c->descricao,
                'data_cadastro' => $c->data_cadastro,
            );
        }

        echo json_encode($retorno);
    }

    /**
    * Função de cadastro dos powerbanks
    * @author Eberson Santos
    *
    * @param String $serial = Serial do powerbank
    * @param String $descricao = Descricao do powerbank
    */
    public function add_powerbank()
    {
        $serial = $this->input->post('powerbank_serial');
        $descricao = $this->input->post('powerbank_descricao');

        if ($serial && is_string($serial)) {

            $powerbank = $this->suprimento->getSuprimentos(array('serial' => $serial, 'tipo' => 2));

            if($powerbank){
                exit(json_encode(array('status' => false, 'msg' => 'Este serial já se encontra cadastrado!')));
            }else{

                $insert = array(
                    'serial' => $serial,
                    'descricao' => $descricao,
                    'tipo' => '2',
                );

                $id_suprimento = $this->suprimento->addSuprimento($insert);
                if ($id_suprimento) {
                    exit(json_encode(array('status' => true, 'msg' => 'Registro cadastrado com sucesso.')));
                } else {
                    exit(json_encode(array('status' => false, 'msg' => 'Não foi possível cadastrar o powerbank. Tente novamente mais tarde!')));
                }
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /**
    * Função retorna carregadores para o dataTable
    * @author Eberson Santos
    */
    public function ajax_carregadores()
    {
        $retorno['data'] = [];

        $carregadores = $this->suprimento->getSuprimentos(array('tipo'  => 3));

        foreach($carregadores as $c) {
            $retorno['data'][] = array(
                'id' => $c->id,
                'serial' => $c->serial,
                'descricao' => $c->descricao,
                'data_cadastro' => $c->data_cadastro,
            );
        }

        echo json_encode($retorno);
    }

    /**
    * Função de cadastro dos carregadores
    * @author Eberson Santos
    *
    * @param String $serial = Serial do carregador
    * @param String $descricao = Descricao do carregador
    */
    public function add_carregador()
    {
        $serial = $this->input->post('carregador_serial');
        $descricao = $this->input->post('carregador_descricao');

        if ($serial && is_string($serial)) {

            $carregador = $this->suprimento->getSuprimentos(array('serial' => $serial, 'tipo' => 3));

            if($carregador){
                exit(json_encode(array('status' => false, 'msg' => 'Este serial já se encontra cadastrado!')));
            }else{

                $insert = array(
                    'serial' => $serial,
                    'descricao' => $descricao,
                    'tipo' => '3',
                );

                $id_suprimento = $this->suprimento->addSuprimento($insert);
                if ($id_suprimento) {
                    exit(json_encode(array('status' => true, 'msg' => 'Registro cadastrado com sucesso.')));
                } else {
                    exit(json_encode(array('status' => false, 'msg' => 'Não foi possível cadastrar o carregador. Tente novamente mais tarde!')));
                }
            }
        } else {
            exit(json_encode(array('status' => false, 'msg' => 'Verifique os dados fornecidos e tente novamente!')));
        }
    }

    /*
    * Lista suprimentos para select2
    */
    function ajaxListSelect() {
        $like = NULL;
        if ($search = $this->input->get('q')) $like = $search;        
        echo json_encode(array('results' => $this->suprimento->listar_suprimentos_disponiveis(0, 10, 'id', 'asc', $like)));
    }
}
