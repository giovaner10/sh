<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class levantamentoPedidos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->helper('util_helper');
        $this->load->model('mapa_calor');
    }

    public function index()
    {
        $this->auth->is_allowed('vis_levantamento_pedidos');

        $this->mapa_calor->registrar_acessos_url(site_url('/levantamentoPedidos'));

        $dados['titulo'] = lang('levantamento');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('levantamentoPedidos/index.php');
        $this->load->view('fix/footer_NS');
    }

    // Pedidos Gerados
    public function pedidosGerados()
    {
        $this->load->view('levantamentoPedidos/pedidosGerados');
    }

    public function pedidosGeradosComNFGerada()
    {
        $this->load->view('levantamentoPedidos/pedidosGeradosComNFGerada');
    }

    public function pedidosGeradosNFAmarraBI()
    {
        $this->load->view('levantamentoPedidos/pedidosGeradosNFAmarraBI');
    }

    public function pedidosGeradosComNFAmarraBiExpedicao()
    {
        $this->load->view('levantamentoPedidos/pedidosGeradosComNFAmarraBiExpedicao');
    }

    public function pedidosGeradosComNFAmarraBiRomaneio()
    {
        $this->load->view('levantamentoPedidos/pedidosGeradosComNFAmarraBiRomaneio');
    }

    public function kanbanPedidos()
    {
        $this->load->view('levantamentoPedidos/kanbanPedidos');
    }

    public function listarPedidosGerados()
    {
        $retorno = (get_listarPedidosGerados());

        echo json_encode($retorno);
    }

    public function listaUltimos100PedidosGerados()
    {
        $retorno = get_listaUltimos100PedidosGerados();

        echo json_encode($retorno);
    }

    public function listarPedidosGeradosByNumPedidoOrAFOrDate()
    {
        $numPedido = $this->input->post('numero_pedido');
        $numAF = $this->input->post('af');
        $d_inicio = $this->input->post('dataInicial');
        $d_fim = $this->input->post('dataFinal');

        $retorno = null;

        if ($d_inicio && $d_fim) {
            $dataInicial = date('d/m/Y', strtotime(str_replace("-", "/", $d_inicio)));
            $dataFinal = date('d/m/Y', strtotime(str_replace("-", "/", $d_fim)));

            $response = get_listarPedidosGeradosByNumPedidoOrAFOrDate($numPedido, $numAF, $dataInicial, $dataFinal);

            $retorno = $response; 
        } else {
            $response = get_listarPedidosGeradosByNumPedidoOrAFOrDate($numPedido, $numAF, $d_inicio, $d_fim);

            $retorno = $response;
        }

        echo json_encode($retorno);
    }

    public function listarPedidosGeradosComNF()
    {
        $retorno = json_decode(get_listarPedidosGeradosComNF());

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function listar100UltimosPedidosGeradosComNF()
    {
        $retorno = json_decode(get_listar100UltimosPedidosGeradosComNF());

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function listarPedidosGeradosComNFByNumPedidoOrAFOrDate()
    {   
    $numeroPedido = $this->input->post('numero_pedido');
    $notaFiscal = $this->input->post('nota_fiscal');
    $d_inicio = $this->input->post('dataInicial');
    $d_fim = $this->input->post('dataFinal');

    $retorno = null;

    if ($d_inicio && $d_fim) {
        $dataEmissaoInicial = date('d/m/Y', strtotime(str_replace("-", "/", $d_inicio)));
        $dataEmissaoFinal = date('d/m/Y', strtotime(str_replace("-", "/", $d_fim)));

        $response = get_listarPedidosGeradosComNFByNumPedidoOrAFOrDate($numeroPedido, $notaFiscal, $dataEmissaoInicial, $dataEmissaoFinal);

        $retorno = json_decode($response);
    } else {
        $response = get_listarPedidosGeradosComNFByNumPedidoOrAFOrDate($numeroPedido, $notaFiscal, $d_inicio, $d_fim);

        $retorno = json_decode($response);
    }

        return $retorno;
    }

    public function listarPedidosGeradosNFAmarraBI()
    {
        $retorno = (get_listarPedidosGeradosNFAmarraBI());

        foreach ($retorno['results'] as &$result) {
            $result['dataCreated'] = (substr($result['dataCreated'], 0, 10));
            $result['dataUpdated'] = (substr($result['dataUpdated'], 0, 10));
        }

        echo json_encode($retorno);
    }
    

    public function buscarPedidosGeradosNFAmarraBI()
    {
        $numPedido = $this->input->post('numPedido');
        $numDocumento = $this->input->post('numDocumento');
        $numCliente = $this->input->post('numCliente');

        $retorno = (get_buscarPedidosGeradosNFAmarraBI($numPedido, $numDocumento, $numCliente));

        if ($retorno['status'] == '200') {
            foreach ($retorno['results'] as &$result) {
                $result['dataCreated'] = (substr($result['dataCreated'], 0, 10));
                $result['dataUpdated'] = (substr($result['dataUpdated'], 0, 10));
            }
        }

        echo json_encode($retorno);
    }

    public function listarPedidosGeradosComNFBiExpedicao()
    {
        $retornoArray = get_listarPedidosGeradosComNFBiExpedicao();
        $retornoString = json_encode($retornoArray);

        $retorno = json_decode($retornoString);

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function buscarPedidosGeradosNFAmarraBIExpedicao()
    {
        $numPedido = $this->input->post('numPedido');
        $notaFiscal = $this->input->post('notaFiscal');
        $retorno = (get_buscarPedidosGeradosNFAmarraBIExpedicao($numPedido, $notaFiscal));
        echo json_encode($retorno);
    }


    public function listarPedidosGeradosComNFBiRomaneio()
    {
        $retornoArray = get_listarPedidosGeradosComNFBiRomaneio();
        $retornoString = json_encode($retornoArray);

        $retorno = json_decode($retornoString);

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function buscarPedidosGeradosNFAmarraBIRomaneio()
    {
        $numPedido = $this->input->post('numPedido');
        $numPedido2 = $this->input->post('numPedido2');
        $notaFiscal = $this->input->post('notaFiscal');
        $retorno = (get_buscarPedidosGeradosNFAmarraBIRomaneio($numPedido, $numPedido2, $notaFiscal));
        echo json_encode($retorno);
    }
}
