<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gestao extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('cliente');
        $this->load->model('veiculo');
        $this->load->model('relatorio');
        $this->load->model('envio_fatura');
        $this->load->model('usuario');
        $this->load->model('contrato');
        $this->load->model('fatura');
        $this->load->model('equipamento');
        $this->load->model('mapa_calor');
        $this->load->helper('util_helper');
        $this->load->library('form_validation');
    }


    public function index()
    {
        $this->auth->is_allowed('rel_financeiro_faturas');

        $usuario = $this->usuario->getUser_posVenda(
            $this->auth->get_login_dados('user')
        )[0];

        $dados['usuario'] = $usuario;

        $dados['titulo'] = 'Pós-Venda';

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/PosVenda/Gestao'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pos_venda/dashboard');
        $this->load->view('fix/footer_NS');
    }


    public function clientes_ativos()
    {
        $this->auth->is_allowed('rel_financeiro_faturas');

        $usuario_logado = $this->auth->get_logged_user_id();

        $dados['vendedores'] = $this->usuario->all();

        $dados['titulo'] = 'Clientes Ativos';

        $dados['usuario_logado'] = $usuario_logado;

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_relatorio'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pos_venda/clientes_ativos', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function contratos()
    {
        $this->auth->is_allowed('rel_financeiro_faturas');

        $usuario_logado = $this->auth->get_logged_user_id();

        $dados['vendedores'] = $this->usuario->all();

        $dados['titulo'] = 'Contratos Ativos';

        $dados['usuario_logado'] = $usuario_logado;

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/PosVenda/Gestao/contratos'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pos_venda/contratos', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function agendamento_instalacao()
    {
        $this->auth->is_allowed('rel_financeiro_faturas');

        $usuario_logado = $this->auth->get_logged_user_id();

        $dados['vendedores'] = $this->usuario->all();

        $dados['titulo'] = 'Agendamento Instalação';

        $dados['usuario_logado'] = $usuario_logado;

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/PosVenda/Gestao/agendamento_instalacao'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pos_venda/agendamento_instalacao', $dados);
        $this->load->view('fix/footer_NS');
    }


    public function analista_suporte()
    {
        $this->auth->is_allowed('rel_financeiro_faturas');

        $usuario_logado = $this->auth->get_logged_user_id();

        $dados['vendedores'] = $this->usuario->all();

        $dados['titulo'] = 'Analistas de Suporte';

        $dados['usuario_logado'] = $usuario_logado;

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_relatorio'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pos_venda/analista_suporte', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function getTabContent()
    {

        $tabName = $this->input->get('tabName');

        switch ($tabName) {
            case 'tabOcorrencias':
                $dadosOcorrencia['usuario_logado'] = $this->auth->get_logged_user_id();
                $this->load->view('pos_venda/ativos/tabOcorrencias', $dadosOcorrencia);
                break;
            case 'tabAtividadesServico':
                $this->load->view('pos_venda/ativos/tabAtividadesServico');
                break;
            case 'tabProvidencias':
                $this->load->view('pos_venda/ativos/tabProvidencias');
                break;
            case 'tabBaseInstalada':
                $this->load->view('pos_venda/ativos/tabBaseInstalada');
                break;
            case 'tabUsuario':
                $this->load->view('pos_venda/ativos/tabUsuario');
                break;
            case 'tabVeiculos':
                $this->load->view('pos_venda/ativos/tabVeiculos');
                break;
            case 'tabVeiculosEspelhados':
                $this->load->view('pos_venda/ativos/tabVeiculosEspelhados');
                break;
            case 'tabEquipamentos':
                $this->load->view('pos_venda/ativos/tabEquipamentos');
                break;
            default:
                show_404();
                break;
        }
    }


    public function tickets()
    {
        // $this->auth->is_allowed('rel_financeiro_faturas');

        $usuario_logado = $this->auth->get_logged_user_id();

        $dados['vendedores'] = $this->usuario->all();

        $dados['titulo'] = 'Gerenciador de Tickets';

        $dados['usuario_logado'] = $usuario_logado;

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_relatorio'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pos_venda/tickets', $dados);
        $this->load->view('fix/footer_NS');
    }


    public function carregar_clientes_ativos()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('dashboard/posVendas/contarClientesAtivos?analista=' . $nome);

        echo json_encode($contagem);
    }


    public function carregar_clientes_saver()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('dashboard/posVendas/contarClientesServer?analista=' . $nome);

        echo json_encode($contagem);
    }


    public function carregar_clientes_gestor()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('dashboard/posVendas/contarClientesGestor?analista=' . $nome);

        echo json_encode($contagem);
    }


    public function contratos_ativos()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('contratos/ativos/listarContratosPaginado?analista=' . $nome);

        echo json_encode($contagem);
    }


    public function contratos_ativos_com_comunicacao()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('contratos/ativos/listarContratoComunicando?analista=' . $nome);

        echo json_encode($contagem);
    }


    public function contratos_ativos_sem_comunicacao()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('contratos/ativos/listarContratoSemComunicacao?analista=' . $nome);

        echo json_encode($contagem);
    }


    public function tickets_abertos()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('dashboard/posVendas/contarTicketsAbertos?analista=' . $nome);

        echo json_encode($contagem);
    }


    public function tickets_abertos_dentro_sla()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('' . $nome);

        echo json_encode($contagem);
    }


    public function tickets_abertos_fora_sla()
    {
        $nome = urlencode($this->input->post('nome'));

        $contagem = $this->to_get('' . $nome);

        echo json_encode($contagem);
    }


    public function listar_clientes_por_analista()
    {
        $nome = urlencode($this->input->post('nome'));

        $listagem = $this->to_get('dashboard/posVendas/listarClientePeloAnalista?analista=' . $nome);

        echo json_encode($listagem);
    }

    public function listar_contratos()
    {
        $nome = urlencode($this->input->post('nome'));

        $listagem = $this->to_get('contratos/ativos/listarContratosAtivosPaginado?itemInicio=1&itemFim=' . $nome);

        echo json_encode($listagem);
    }

    public function listar_agendamento()
    {
        $nome = urlencode($this->input->post('nome'));

        $listagem = $this->to_get('infobip/getConversationsByPeriodNameByAnalista?dataInicial=14/02/2021&dataFinal=20/06/2024' . $nome);

        echo json_encode($listagem);
    }


    public function listar_agendamento_server_side()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $startRow++;

        $status = $this->input->post('status');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');

        $tresMeses = new DateInterval('P3M');
        $tresMeses->invert = 1;

        if (!$dataInicial) {
            $dataInicial = new DateTime();
            $dataInicial->add($tresMeses);
            $dataInicial = $dataInicial->format('d/m/Y');
        } else {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if (!$dataFinal) {
            $dataFinal = date('d/m/Y');
        } else {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $url = "infobip/getConversationsByPeriodNameByAnalista?itemInicio=$startRow&itemFim=$endRow";


        if (isset($dataInicial) && $dataInicial) {
            $url .= '&dataInicial=' . $dataInicial;
        }

        if (isset($dataFinal) && $dataFinal) {
            $url .= '&dataFinal=' . $dataFinal;
        }

        if (isset($status) && $status) {
            $url .= '&status=' . $status;
        }

        $response = isset($status) && $status != null ? listarAgendamentosPosVendaByStatusPaginated($status, $startRow, $endRow) : $this->to_get($url);

        if ($response['status'] == '200') {
            echo json_encode(
                array(
                    "success" => true,
                    "rows" => $response['resultado']['listaConversationNameDTO'],
                    "lastRow" => $response['resultado']['qtdTotalEventos']
                )
            );
        } else if ($response['status'] == '404') {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => $response['resultado']['mensagem'],
                    "status" => $response['status']
                )
            );
        } else {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => $response['resultado']['mensagem'],
                )
            );
        }
    }


    public function listar_contratos_server_side()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $cliente = $this->input->post('cliente');
        $serial = $this->input->post('serial');
        $status = $this->input->post('status');

        $url = "contratos/ativos/listarContratosAtivosPaginado?itemInicio=$startRow&itemFim=$endRow";

        if (isset($cliente) && $cliente) {
            $url .= '&cliente=' . urlencode($cliente);
        }

        if (isset($serial) && $serial) {
            $url .= '&serial=' . $serial;
        }

        if (isset($status) && $status) {
            $url .= '&status=' . $status;
        }

        $response = $this->to_get($url);

        if ($response['status'] == '200') {
            echo json_encode(
                array(
                    "success" => true,
                    "rows" => $response['resultado']['contratos'],
                    "lastRow" => $response['resultado']['qtdTotalContratos']
                )
            );
        } else if ($response['status'] == '404') {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => $response['resultado']['mensagem'],
                )
            );
        } else {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => $response['resultado']['mensagem'],
                )
            );
        }
    }
    public function buscar_analista_id()
    {
        $id = urlencode($this->input->get('id'));

        $listagem = $this->to_get('analistaSuporte/listarAnalistaSuporteById?id=' . $id);

        echo json_encode($listagem);
    }


    public function listar_analistas_suporte()
    {

        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $url = "analistaSuporte/listarAnalistaSuportePag?itemInicio=$startRow&itemFim=$endRow";

        $nome = $this->input->post('nome');

        if (isset($nome) && $nome) {
            $url .= '&nomeCompleto=' . urlencode($nome);
        }

        $response = $this->to_get($url);

        if ($response['status'] == '200') {
            echo json_encode(
                array(
                    "success" => true,
                    "rows" => $response['resultado']['analistaSuporte'],
                    "lastRow" => $response['resultado']['quantAnalistaSuporte']
                )
            );
        } else if ($response['status'] == '404') {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => $response['resultado']['mensagem'],
                )
            );
        } else {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => $response['resultado']['mensagem'],
                )
            );
        }
    }

    public function listar_tickets()
    {
        $usuarioLogado = $this->input->post('usuarioLogado');

        $idCliente = $this->input->post('idCliente');

        if ($idCliente) {
            $listaClientes = $this->to_get('tickets/listarTicketsPag?analista=' . $usuarioLogado . '&idCliente=' . $idCliente);
        } else {
            $listaClientes = $this->to_get('tickets/listarTicketsPag?analista=' . $usuarioLogado);
        }

        echo json_encode($listaClientes);
    }


    public function inativar_analistas_suporte()
    {

        $id = $this->input->get('id');

        $data = array(
            "id" => $id,
            "status" => 0
        );

        $statusCliente = $this->to_patch('analistaSuporte/atualizarStatusAnalistaSuporte', $data);

        echo json_encode($statusCliente);
    }


    /// cliente completo

    public function buscar_cliente_completo()
    {
        $id = urlencode($this->input->post('id'));

        $listagem = $this->to_get('cliente/listarClienteCompletoById?idCliente=' . $id);

        echo json_encode($listagem);
    }
    ////


    public function criar_analista_suporte()
    {
        // Validações dos dados
        $this->form_validation->set_rules('idCrm', 'CRM', 'required');
        $this->form_validation->set_rules('nomeUsuario', 'Nome de Usuário', 'required');
        $this->form_validation->set_rules('emailPrimario', 'Email Primário', 'required|valid_email');
        $this->form_validation->set_rules('telefoneCelular', 'Telefone Celular', 'required');
        $this->form_validation->set_rules('telefonePrincipal', 'Telefone Principal', 'required');
        $this->form_validation->set_rules('unidadeNegocios', 'Unidade de Negócios', 'required');
        $this->form_validation->set_rules('cargo', 'Cargo', 'required');
        $this->form_validation->set_rules('departamento', 'Departamento', 'required');
        $this->form_validation->set_rules('aprovadorDescontoReembolso', 'Aprovador de Desconto/Reembolso', 'required');
        $this->form_validation->set_rules('endereco', 'Endereço', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $this->form_validation->set_rules('estado', 'Estado', 'required');

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(
                array(
                    'status' => 500,
                    'resultado' => validation_errors()
                )
            );
        } else {
            $data = [
                'idCrm' => $this->input->post('idCrm') ?: '',
                'nomeUsuario' => $this->input->post('nomeUsuario') ?: '',
                'usuarioSistema' => $this->input->post('usuarioSistema') ?: '',
                'nomeCompleto' => $this->input->post('nomeCompleto') ?: '',
                'apelido' => $this->input->post('apelido') ?: '',
                'titulo' => $this->input->post('titulo') ?: '',
                'emailPrimario' => $this->input->post('emailPrimario') ?: '',
                'email2' => $this->input->post('email2') ?: '',
                'emailAlertaMovel' => $this->input->post('emailAlertaMovel') ?: '',
                'emailYammer' => $this->input->post('emailYammer') ?: '',
                'telefoneCelular' => $this->input->post('telefoneCelular') ?: '',
                'telefonePrincipal' => $this->input->post('telefonePrincipal') ?: '',
                'statusEmailPrincipal' => $this->input->post('statusEmailPrincipal') ?: 1,
                'statusConvite' => $this->input->post('statusConvite') ?: 0,
                'unidadeNegocios' => $this->input->post('unidadeNegocios') ?: '',
                'cargo' => $this->input->post('cargo') ?: '',
                'departamento' => $this->input->post('departamento') ?: '',
                'aprovadorDescontoReembolso' => $this->input->post('aprovadorDescontoReembolso') ?: '',
                'caixaCorreio' => $this->input->post('caixaCorreio') ?: '',
                'endereco' => $this->input->post('endereco') ?: '',
                'cidade' => $this->input->post('cidade') ?: '',
                'estado' => $this->input->post('estado') ?: '',
                'taxaCambio' => $this->input->post('taxaCambio') ?: 5.5,
                'vendedor' => $this->input->post('vendedor') ?: '',
                'gerente' => $this->input->post('gerente') ?: ''
            ];
        }

        $edicao = $this->input->get('edicao');

        if ($edicao == 'sim') {
            $data['id'] = $this->input->get('idEdicao');
            $response = $this->to_put('analistaSuporte/atualizarAnalistaSuporte', $data);
        } else {
            $response = $this->to_post('analistaSuporte/cadastrarAnalistaSuporte', $data);
        }
        echo json_encode($response);
    }

    function to_get($url)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $request,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $headers,
            )
        );

        if (curl_error($curl))
            throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

    function to_patch($url, $POSTFIELDS)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        $body = json_encode($POSTFIELDS);

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $request,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_POSTFIELDS => json_encode($POSTFIELDS),
                CURLOPT_HTTPHEADER => $headers,
            )
        );

        if (curl_error($curl))
            throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

    function to_put($url, $POSTFIELDS)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $request,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($POSTFIELDS),
                CURLOPT_HTTPHEADER => $headers,
            )
        );

        if (curl_error($curl))
            throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

    function to_post($url, $POSTFIELDS)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $request,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($POSTFIELDS),
                CURLOPT_HTTPHEADER => $headers,
            )
        );

        if (curl_error($curl))
            throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }
}
