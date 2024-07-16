<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

ini_set('display_errors', 1);

class CadastroDeClientes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->helper('util_omnicom_helper');
    }


    // Paginas
	public function index()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/Omnicom/CadastroDeClientes'));

		$dados['titulo'] = lang('omnicom');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $_SESSION['menu_omnicom'] = 'CadastroDeClientes';
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('Omnicom/CadastroDeClientes');
		$this->load->view('fix/footer_NS');
	}

    public function listarTecnologias(){
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $idTecnologia = (int) $this->input->post('idTecnologia');
        $idCliente = (int) $this->input->post('idCliente');

        $startRow++;

        $dados = get_listarTecnologiasPaginado($startRow, $endRow, $idTecnologia, $idCliente);
        
        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['tecnologiasDTO'],
                "lastRow" => $dados['resultado']['qtdTotalTecnologias']
            ));
        } elseif ($dados['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['mensagem'],
            ));
        }
    }

    public function cadastrarCliente(){
        $POSTFIELDS = [
            'idCadastroClientes' => $this->input->post('idCliente'),
            'idTecnologias' => $this->input->post('idTecnologias'),
            'usuario' => $this->input->post('usuario'),
            'senha' => $this->input->post('senhaUsuario'),
            'tipoUrl' => $this->input->post('tipoUrl'),
            'url' => $this->input->post('url'),
        ];

        $dados = post_cadastrarUsuario($POSTFIELDS);
        echo json_encode($dados);
    }

    public function buscarTecnologiaById(){
        $id = (int) $this->input->post('id');

        $dados = get_buscarTecnologiaById($id);

        echo json_encode($dados);
    }

    public function atualizarTecnologia(){
        $POSTFIELDS = [
            'id' => $this->input->post('id'),
            'idCadastroClientes' => $this->input->post('idCliente'),
            'idTecnologias' => $this->input->post('idTecnologias'),
            'usuario' => $this->input->post('usuario'),
            'senha' => $this->input->post('senhaUsuario'),
            'tipoUrl' => $this->input->post('tipoUrl'),
            'url' => $this->input->post('url'),
            'status' => $this->input->post('status')
        ];

        $dados = put_atualizarTecnologia($POSTFIELDS);

        echo json_encode($dados);
    }

    public function deletarTecnologia(){
        $POSTFIELDS = [
            'id' => $this->input->post('id'),
            'status' => $this->input->post('status')
        ];

        $dados = patch_deletarTecnologia($POSTFIELDS);

        echo json_encode($dados);
    }

    public function clientesSelect2() {
        $itemInicio = $this->input->post('itemInicio');
        $itemFim = $this->input->post('itemFim');
        $nome = $this->input->post('searchTerm');
        $id = $this->input->post('id');
        
        $dados = get_clientes($itemInicio, $itemFim, $nome, $id);
        echo json_encode($dados);
    }
}
