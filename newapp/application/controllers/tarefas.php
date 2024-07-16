<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tarefas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('tarefa');
    }

    public function index() {
        $dados['titulo'] = "Showtecnologia";
        $itens_pag = 100;
        $total_atividades = $this->tarefa->get_tot_atividades();
        $tot_pag = ceil($total_atividades / $itens_pag);
        $id_user = $this->session->userdata['log_admin']['user'];
        $nome_dev = $this->session->userdata['log_admin']['nome'];

        if ($id_user != '101' && $id_user != '2') {
        // Dados para o Grafico //
            $detalhamento = array(
                'andamento' => $this->tarefa->get_grafic_andamento($id_user),
                'cancelado' => $this->tarefa->get_grafic_cancelado($id_user),
                'pendente' => $this->tarefa->get_grafic_pendente($id_user),
                'concluido_d' => $this->tarefa->get_grafic_concluido_d($id_user),
                'concluido_f' => $this->tarefa->get_grafic_concluido_f($id_user)
            );

            $dados = array(
                'atividades' => $this->tarefa->get_dev_atividades($id_user),
                'devs' => $this->tarefa->list_devs(),
                'detalhamento' => $detalhamento,
                'id_user' => $id_user
            );
        
        // CONTROLE DA PAGINAÇÃO //
        } else {
            $devs = $this->tarefa->list_devs();
            foreach ($devs as $dev) {
                $id_dev = $this->tarefa->get_id_dev($dev->nome);
                $detalhamento[] = array(
                    'nome_dev' => $dev->nome,
                    'andamento' => $this->tarefa->get_grafic_andamento($id_dev->id),
                    'cancelado' => $this->tarefa->get_grafic_cancelado($id_dev->id),
                    'pendente' => $this->tarefa->get_grafic_pendente($id_dev->id),
                    'concluido_d' => $this->tarefa->get_grafic_concluido_d($id_dev->id),
                    'concluido_f' => $this->tarefa->get_grafic_concluido_f($id_dev->id)
                );
            }
            if (isset($_GET['id_dev'])) {
                $dados = array(
                    'atividades' => $this->tarefa->get_dev_atividades($_GET['id_dev']),
                    'devs' => $this->tarefa->list_devs(),
                    'detalhamento' => $detalhamento,
                    'id_user' => $id_user
                );
                
            } elseif (isset($_GET['pag'])) {
                $dados = array('atividades' => $this->tarefa->get_atividades($_GET['pag'],100), 'devs' => $devs, 'tot_pag' => $tot_pag, 'id_user' => $id_user, 'detalhamento' => $detalhamento);
            
            } else {
                $dados = array('atividades' => $this->tarefa->get_atividades(0,100), 'devs' => $devs, 'tot_pag' => $tot_pag, 'id_user' => $id_user, 'detalhamento' => $detalhamento);
            }
        }

        // INSERI O NOME DO DESENVOLVEDOR E STATUS NA ATIVIDADE //
        foreach ($dados['atividades'] as $atividade) {
            $atividade->nome_desenvolvedor = $this->tarefa->get_nome_desenvolvedor($atividade->id_desenvolvedor);
            $atividade->nome_status = $this->tarefa->get_nome_status($atividade->id_status);
        }

        $this->load->view('fix/header', $dados);
        $this->load->view('atividades/tarefas_desempenho', $dados['atividades'], $dados['devs']);
        $this->load->view('fix/footer');
    }

    public function cadastrar_atividade() {
        // Verificando se algo foi passado via POST //
        if (isset($_POST)) {
            
            // Pega o ID do Desenvolvedor //
            $id_dev = $this->tarefa->get_id_dev($_POST['resp']);

            // Criando array de dados a serem gravados //
            $dados = array(
            'nome_atividade' => $_POST['atividade'],
            'resumo' => $_POST['resumo'],
            'id_desenvolvedor' => $id_dev,
            'prazo' => $_POST['prazo'] . " 18:00:00",
            'id_status' => 4
            );

            // Verifica se o prazo é maior ou igual a data atual //
            if ($dados['prazo'] != '' && $dados['prazo'] >= date('Y-m-d')){
                $retorno = $this->tarefa->cad_atividade($dados);

                // Verifica se foi inserido no banco //
                if ($retorno) {
                    $this->session->set_flashdata('sucesso', 'Informações gravadas com sucesso');
                    redirect('tarefas/index');
                } else {
                    $this->session->set_flashdata('erro', 'As informações não foram gravadas, favor tentar novamente mais tarde!');
                    redirect('tarefas/index');
                }
            } else {
                $this->session->set_flashdata('erro', 'O prazo não pode ser menor do que a data atual');
                redirect('tarefas/index');
            }

        } 
    }

    /* -------> Metodo comentado, pois não será necessario cadastrar novos desenvolvedores <-----------
                        As atividades serão vinculadas ao usuario do shownet

    public function cad_desenvolvedor(){
        if (!empty($_POST)) {
            $dev = array('nome' => $_POST['name_dev']);
            $retorno = $this->tarefa->add_dev($dev);
            if ($retorno) {
                $this->session->set_flashdata('sucesso', 'Desenvolvedor gravado com sucesso!');
                redirect('tarefas/index');
            } else {
                $this->session->set_flashdata('erro', 'Desenvolvedor já cadastrado!');
                redirect('tarefas/index');
            }

        } else {
            $this->session->set_flashdata('erro', 'Verifique o campo NOME e tente novamente mais tarde!');
            redirect('tarefas/index');
        }
    }*/

    public function ini_atividade() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $retorno = $this->tarefa->iniciar_ativ($id);

            if (!$retorno) {
                $this->session->set_flashdata('erro', 'Não foi possível iniciar a atividade!');
                redirect('tarefas/index');
            } else {
                redirect('tarefas/index');
            }
            
        } else {
            $this->session->set_flashdata('erro', 'Não foi passado nenhum parametro');
            redirect('tarefas/index');
        }
    }

    public function fim_atividade() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $retorno = $this->tarefa->finalizar_ativ($id);

            if (!$retorno) {
                $this->session->set_flashdata('erro', 'Não foi possível iniciar a atividade!');
                redirect('tarefas/index');
            } else {
                redirect('tarefas/index');
            }
        } else {
            $this->session->set_flashdata('erro', 'Não foi passado nenhum parametro');
            redirect('tarefas/index');
        }
    }

    public function cancelar_atividade() {
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $id = $_GET['id'];
            $retorno = $this->tarefa->cancela_atividade($id, $status);
        
            if ($retorno) {
                $this->session->set_flashdata('sucesso', 'Atividade Cancelada com Sucesso!');
                redirect('tarefas/index');
            } else {
                $this->session->set_flashdata('erro', 'Sua atividade já se encontra concluida ou cancelada!');
                redirect('tarefas/index');
            }
        } else {
            $this->session->set_flashdata('erro', 'Não foi encontrado a atividade selecionada');
            redirect('tarefas/index');
        }
    }

    public function transferir_atividade(){
        if ($_POST['id_atividade']) {
            if ($_POST['resp']) {
                $id = $this->tarefa->get_id_dev($_POST['resp']);
                $id_dev = $id[0]->id;
                $id_ativ = $_POST['id_atividade'];
                $transferencia = $this->tarefa->transferir($id_ativ, $id_dev);

                if ($transferencia) {
                    $this->session->set_flashdata('sucesso', 'Atividade transferida com sucesso!');
                    redirect('tarefas/index');
                } else {
                    $this->session->set_flashdata('erro', 'A atividade já se encontra com o destinatário, favor informar outro colaborador!');
                    redirect('tarefas/index');
                }
            } else {
                $this->session->set_flashdata('erro', 'Por favor selecione o destinatário da atividade.');
                redirect('tarefas/index');
            }
        }  
    }

}
