<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapa_Calor extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->helper('util_helper');
        $this->load->model('usuario');
    }

    public function index()
    {
        $this->auth->is_allowed_block('vis_mapa_calor');
        $dados['titulo'] = lang('mapa_calor');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('mapa_calor/mapa_calor2');
        $this->load->view('fix/footer_NS');
    }

    public function listarMapaCalor()
    {
        $data = get_listarMapaCalor();
        $result = $data['results'];

        $dadosMapaCount = [];
        foreach ($result as $valor) {
            $url = $valor['urlAcessada'];
            $dadosMapaCount[$url] = [
                'urlAcessada' => end(explode('/', $url)),
                'count' => $valor['qtdAcessos'],
                'link' => $url
            ];
        }

        $dadosMapaLista = array_values($dadosMapaCount);

        echo json_encode($dadosMapaLista);
    }

    public function listarMapaCalorByUserOrData()
    {
        $login = $this->input->post('user');
        $id_user = null;

        if ($login != null) {
            $user = $this->usuario->getUserByLogin($login);
            $id_user = $user[0]->id;
        }

        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');
        $periodo = $this->input->post('periodo');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');

        if ($mes) {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "01/$month/$year";

            list($day, $month, $year) = explode('/', $dataInicial);
            $dataFormatada = "$year-$month-$day";

            $dataFinal = date('d/m/Y', strtotime('last day of this month', strtotime($dataFormatada)));
        } elseif ($ano) {
            $dataInicial = "01/01/$ano";
            $dataFinal = "31/12/$ano";
        } elseif ($periodo) {
            switch ($periodo) {
                case '7days':
                    $dataInicial = date('d/m/Y', strtotime('-7 days'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '1mes':
                    $dataInicial = date('d/m/Y', strtotime('first day of -1 month'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '3mes':
                    $dataInicial = date('d/m/Y', strtotime('-3 months'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '6mes':
                    $dataInicial = date('d/m/Y', strtotime('-6 months'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '12mes':
                    $dataInicial = date('d/m/Y', strtotime('-1 year'));
                    $dataFinal = date('d/m/Y');
                    break;
            }
        } else {
            if ($dataInicial && $dataFinal) {
                $dataInicial = str_replace("-", "/", $dataInicial);
                $dataInicial = date('d/m/Y', strtotime($dataInicial));
                $dataFinal = str_replace("-", "/", $dataFinal);
                $dataFinal = date('d/m/Y', strtotime($dataFinal));
            }
        }

        $dados = get_mapaCalorByUserOrData($dataInicial, $dataFinal, $id_user);
        $result = $dados['results'];
        $status = $dados['status'];


        $dadosMapaCount = [];

        if ($status == 200) {
            
            foreach ($result as $valor) {
                $url = $valor['urlAcessada'];
                $dadosMapaCount[$url] = [
                    'urlAcessada' => end(explode('/', $url)),
                    'count' => $valor['qtdAcessos'],
                    'link' => $url
                ];
            }
        }else{
            $dadosMapaCount = [];
        }

        $dadosMapaLista = array_values($dadosMapaCount);

        echo json_encode($dadosMapaLista);
    }
}
