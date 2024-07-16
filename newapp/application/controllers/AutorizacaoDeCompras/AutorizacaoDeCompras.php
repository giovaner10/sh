<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));
class AutorizacaoDeCompras extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->helper('util_autorizacao_compras_helper');
    }
    // Paginas
    public function index()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('AutorizacaoDeCompras/AutorizacaoDeCompras'));
        $dados['titulo'] = lang('autorizacoes_pendentes');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX', 'ag-charts');
        $dados['idUser'] = $this->auth->get_login_dados('user');
        $dados['nomeUsuario'] = $this->auth->get_login_dados('nome');
        $dados['email'] = $this->auth->get_login_dados('email');





        $_SESSION['menu_autorizacao'] = 'AutorizadoresPendentes';




        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('AutorizacaoDeCompras/Pendencias');
        $this->load->view('fix/footer_NS');
    }

    public function AutorizadoresCadastrados()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('AutorizacaoDeCompras/AutorizacaoDeCompras/AutorizadoresCadastrados'));
        $dados['titulo'] = lang('autorizadores_cadastrados');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX', 'ag-charts');
        $dados['idUser'] = $this->auth->get_login_dados('user');


        $_SESSION['menu_autorizacao'] = 'AutorizadoresCadastrados';


        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('AutorizacaoDeCompras/AutorizadoresCadastrados');
        $this->load->view('fix/footer_NS');
    }

    // Requisições para a tela AUTORIZADORES CADASTRADADOS
    // Buscas:
    public function buscarDadosAutorizadores()
    {
        $startRow =  (int)$this->input->post('startRow');
        $endRow =  (int)$this->input->post('endRow');

        $startRow++;

        $dados = get_DadosAutorizadoresPaginated($startRow, $endRow);
        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['aprovadores'],
                "lastRow" => $dados['resultado']['qtdTotalAprovadores']
            ));
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Dados não encontrados!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
            ));
        }
    }

    public function buscarDadosAutorizadoresByUser()
    {
        $usuario_int = $this->input->post('usuario');
        $usuario = sprintf('%06d', $usuario_int);

        $dados = get_DadosAutorizadoresByUser($usuario);

        if ($dados['status'] == '200') {
            echo json_encode($dados['resultado']);
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Dados não encontrados!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['mensagem'],
            ));
        }
    }

    public function buscarAutorizadores()
    {
        $dados = get_Autorizadores();
        echo json_encode($dados);
    }

    // Requisições para a tela AUTORIZAÇÕES PENDENTES

    public function buscarCodAprovador()
    {
        $login = $this->input->post('login');

        $dados = get_codAprovador($login);
        echo json_encode($dados);
    }


    public function buscarUsuario()
    {
        $usuario = $this->input->post('usuario');
    
        $dados = get_usuario($usuario);
        echo json_encode(['resultado' => $dados]);
    }


    public function buscarDadosPedidos()
    {
        $startRow =  (int)$this->input->post('startRow');
        $endRow =  (int)$this->input->post('endRow');
        $aprovador = $this->input->post('aprovador');
        $dataInicio =  $this->input->post('dataInicio');
        $dataFim =  $this->input->post('dataFim');

        $startRow++;

        $dados = get_DadosPedidoPaginated($startRow, $endRow, $aprovador, $dataInicio, $dataFim);
        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['sugestoesAprovadores'],
                "lastRow" => $dados['resultado']['qtdSituacaoAprovadores']
            ));
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Dados não encontrados!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
            ));
        }
    }

    public function buscarDadosPedidoById()
    {
        $filial = $this->input->post('filial');
        $pedido =  $this->input->post('pedido');

        $dados = get_DadosPedidoById($filial, $pedido);
        echo json_encode($dados);
    }

    public function buscarDadosPendentesByFilter()
    {
        $startRow =  (int)$this->input->post('startRow');
        $endRow =  (int)$this->input->post('endRow');
        $aprovador = $this->input->post('aprovador');
        $dataInicio =  $this->input->post('dataInicio');
        $dataFim =  $this->input->post('dataFim');

        $startRow++;

        $dados = get_DadosAutorizadoresByFilterPaginated($startRow, $endRow, $aprovador, $dataInicio, $dataFim);
        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['sugestoesAprovadores'],
                "lastRow" => $dados['resultado']['qtdSituacaoAprovadores']
            ));
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Dados não encontrados!",
            ));
        } else {
            echo json_encode(array(
                "success" => false,
            ));
        }
    }

    public function enviarAprovacaoPedido()
    {
        $Filial = $this->input->post('Filial');
        $Pedido = $this->input->post('Pedido');
        $Nivel = $this->input->post('Nivel');
        $Status = $this->input->post('Status');
        $Motivo = $this->input->post('Motivo');

        $POSTFIELDS = array(
            'Filial' => $Filial,
            'Pedido' => strtoupper($Pedido),
            'Nivel' =>  $Nivel,
            'Status' => strtoupper($Status),
            'Motivo' => $Motivo
        );

        $dados = post_Pedido($POSTFIELDS);
        echo json_encode($dados);
    }

    public function associarAprovador()
    {
        $idAprovador = (int) $this->input->post('idAprovador');
        $idUsuario = (int) $this->input->post('idUsuario');

        $POSTFIELDS = array(
            'idAprovador' => $idAprovador,
            'idUsuario' => $idUsuario
        );

        $dados = post_AssociarAprovovador($POSTFIELDS);
        echo json_encode($dados);
    }

    public function buscarAutorizadoresSelect() {
        $nome = $this->input->post('searchTerm');
        
        $dados = get_AutorizadoresSelect($nome);
        echo json_encode($dados);
    }
}
