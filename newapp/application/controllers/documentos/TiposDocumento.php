<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TiposDocumento extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('departamento');
		$this->load->model('auth');
		$this->load->helper('funcionarios_helper');
		$this->load->model('mapa_calor');
	}

	public function index()
	{
		$this->auth->is_allowed('usuarios_visualiza');
		$dados['titulo'] = lang('documentos') . ' - ' . lang('show_tecnologia');
		$dados['load'] = array('buttons_html5', 'datatable_responsive', 'xls', 'ag-grid', 'select2', 'mask', 'XLSX');

		// get departamentos
		$dados['departamentos'] = $this->departamento->getDepartamentos();

		$this->mapa_calor->registrar_acessos_url(site_url('/documentos'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('documentos/documentos');
		$this->load->view('fix/footer_NS');
	}

	public function getAllDocumentTypeServerSide()
	{
		$startRow = (int) $this->input->post('startRow', TRUE) ?: 0;
		$endRow = (int) $this->input->post('endRow', TRUE) ?: 10;

		$startRow++;

		$searchOptionsRaw = $this->input->post('searchOptions', TRUE);
		$searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

		$nome = null;
		if (isset($searchOptions['nomeDocumento'])) {
			$nome = trim($searchOptions['nomeDocumento']);
		}

		$response = get_listarDocumentosPaginado($startRow, $endRow, $nome);

		if ($response['status'] == '200') {
			echo json_encode(array(
				"success" => true,
				"rows" => $response['resultado']['tiposDocumentos'],
				"lastRow" => $response['resultado']['qtdTotalTiposDocumentos']
			));
		} else if ($response['status'] == '404') {
			echo json_encode(array(
				"success" => false,
				"message" => $response['resultado']['mensagem'],
			));
		} else {
			echo json_encode(array(
				"success" => false,
				"message" => $response['resultado']['mensagem'],
			));
		}
	}

	public function excluirTipoDocumento()
	{ {
			$POSTFIELDS = array(
				'id' => (int) $this->input->post('id'),
				'status' => (int)$this->input->post('status')
			);

			$dados = patch_alterarStatusTipoDocumento($POSTFIELDS);
			echo json_encode($dados);
		}
	}

    public function cadastrarTipoDocumento()
    {
        $POSTFIELDS = array(
            'nome' => $this->input->post('nome'),
            'obrigatorio' => (int) $this->input->post('obrigatorio'),
            'temValidacao' => (int) $this->input->post('temValidacao'),
        );

        $dados = post_cadastrarTipoDocumento($POSTFIELDS);
        echo json_encode($dados);
    }
	
	public function editarTipoDocumento()
    {
        $POSTFIELDS = array(
			'id' => (int) $this->input->post('id'),
            'nome' => $this->input->post('nome'),
            'obrigatorio' => (int) $this->input->post('obrigatorio'),
            'temValidacao' => (int) $this->input->post('temValidacao'),
        );

        $dados = put_editarTipoDocumento($POSTFIELDS);
        echo json_encode($dados);
    }
}
