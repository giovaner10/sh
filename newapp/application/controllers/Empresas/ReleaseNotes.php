<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class ReleaseNotes extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('release');
		$this->load->model('mapa_calor');
		$this->load->model('usuario');
		$this->load->model('sender');
		$this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();
		$this->load->helper('util_releasenotes_helper');

		# Models in model
		$this->load->model("arquivo");

		# Vars
		$this->extensoesPermitidasRelease = "pdf";
	}

	public function index()
    {
		$dados['titulo'] = lang('release_notes');
		//$dados["categoriasParcerias"] = $this->buscarParceriasPorCaterias();

		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $_SESSION['menu_ocr'] = 'DadosGerenciamentoOCR';
		$dados['idUser'] = $this->auth->get_login_dados('user');
		$this->mapa_calor->registrar_acessos_url(site_url('/Empresas/ReleaseNotes'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('empresa/release_notes');
		$this->load->view('fix/footer_NS');
	}

	public function listarReleases(){
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $releaseNote = $this->input->post('releaseNote');
        $idUsuario = (int) $this->input->post('idUsuario');
        $dataInicio = $this->input->post('dataInicio');
        $dataFim = $this->input->post('dataFim');

        $startRow++;

        $dados = get_listarReleases($startRow, $endRow, $releaseNote, $idUsuario, $dataInicio, $dataFim);
        
        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['qtdTotal'],
                "lastRow" => $dados['resultado']['objetosDTO']
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

	public function listarReleaseById(){
        $idRelease = $this->input->post('idRelease');

        $dados = get_listarReleaseById($idRelease);
        
        echo json_encode($dados);
    }

	public function cadastrarRelease(){
		$POSTFIELDS = [
			'idUsuario' => (int) $this->input->post('idUsuario'),
			'nomeRelease' => $this->input->post('nomeRelease'),
			'nomeArquivo' => $this->input->post('nomeArquivo'),
			'arquivoBase64' => $this->input->post('arquivoBase64')
		];

		$dados = post_cadastrarRelease($POSTFIELDS);
		echo json_encode($dados);
	}

	public function editarReleaseNote(){
		$POSTFIELDS = [
			'idUsuario' => (int) $this->input->post('idUsuario'),
			'idRelease' => (int) $this->input->post('idRelease'),
			'nomeRelease' => $this->input->post('nomeRelease'),
			'nomeArquivo' => $this->input->post('nomeArquivo'),
			'arquivoBase64' => $this->input->post('arquivoBase64')
		];
			
		$dados = put_editarRelease($POSTFIELDS);
		echo json_encode($dados);
	}

	public function inativarRelease(){
		$POSTFIELDS = [
			'idRelease' => (int) $this->input->post('idRelease')
		];
		

		$dados = patch_inativarRelease($POSTFIELDS);
		echo json_encode($dados);
	}
}