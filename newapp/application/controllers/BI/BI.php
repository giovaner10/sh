<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BI extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->library('form_validation');
        $this->load->database();
    }

    public function index()
    {
        $data['titulo'] = 'BI';
        $link = $this->input->get('link');

        $menu_nome = $this->input->get('menu_nome');

        $menu_pai = $this->menuPai($menu_nome);

        if (!empty($link)) {

            $this->session->set_userdata('iframe_link', $link);
        } elseif (!$this->session->userdata('iframe_link')) {

            $this->session->set_userdata('iframe_link', '');
        }

        $data['link'] = $this->session->userdata('iframe_link');

        $data['menu_nome'] = $menu_nome;
        $data['menu_pai'] = $menu_pai;

        $this->mapa_calor->registrar_acessos_url(site_url('/BI/BI'));

        $this->load->view('new_views/fix/header', $data);
        $this->load->view('BI/BI', $data);
        $this->load->view('fix/footer_NS');
    }

    public function menuPai($menu_nome)
    {
        if (
            $menu_nome == 'abertura_na_bi' ||
            $menu_nome == 'gestao_agendamento_bi' ||
            $menu_nome == 'painel_atendimento_bi' ||
            $menu_nome == 'produtividade_bi' ||
            $menu_nome == 'monitoria_bi' ||
            $menu_nome == 'treinamento_bi' ||
            $menu_nome == 'telemetria_can_bi' ||
            $menu_nome == 'segmentacao_clientes_bi' ||
            $menu_nome == 'painel_indisponibilidade_bi'
        ) {
            return $menu_pai = 'agendamento_ativacao_bi';
        } elseif (
            $menu_nome == 'chatbot_bi' ||
            $menu_nome == 'chatbot_crm_bi' ||
            $menu_nome == 'desempenho_bi' ||
            $menu_nome == 'homologacao_bi' ||
            $menu_nome == 'painel_operacoes_bi' ||
            $menu_nome == 'painel_indisponibilidade' ||
            $menu_nome == 'segmentacao_clientes' ||
            $menu_nome == 'telemetria_can'
        ) {
            return $menu_pai = 'atendimento_suporte_bi';
        } elseif ($menu_nome == 'gestao_command_center_bi') {
            return $menu_pai = 'command_center_bi';
        } elseif (
            $menu_nome == 'consulta_af_e_rede' ||
            $menu_nome == 'listagem_de_dashboards'
        ) {
            return $menu_pai = 'dashboards';
        } elseif ($menu_nome == 'acompanhamento_af_bi') {
            return $menu_pai = 'demais_areas_bi';
        } elseif (
            $menu_nome == 'atendimentos_cidade_bi' ||
            $menu_nome == 'atividade_servico_bi' ||
            $menu_nome == 'prestadores_bi' ||
            $menu_nome == 'resultados_operacoes_bi'
        ) {
            return $menu_pai = 'operacoes_bi';
        } elseif (
            $menu_nome == 'analitico_clientes_bi' ||
            $menu_nome == 'churn_bi' ||
            $menu_nome == 'gestao_retencao_bi'
        ) {
            return $menu_pai = 'retencao_bi';
        } elseif ($menu_nome == 'telecom_bi') {
            return $menu_pai = 'telecom_bi';
        }
    }
}
