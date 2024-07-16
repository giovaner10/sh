<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Boleto extends CI_Controller {

  public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');

    $this->upload_path = 'uploads/portal_compras/solicitacoes/anexos_boleto/';
		$this->file_name_anexo = '';
	}

  public function valida_anexo() {
    if (empty($_FILES['anexo']['name'])) {
			return [
        'status' => '-1', 
        'mensagem' => 'Campo anexo é obrigatório'
      ];
		}

		if (!is_dir($this->upload_path)) {
			mkdir($this->upload_path, 0777, TRUE);
		}

		// sanitariza o nome do arquivo
		$file_name = removerAcentos($_FILES['anexo']['name']); // remove acentos
		$file_name = str_replace(' ', '_', $file_name); // substitui espaços por underline
		$this->file_name_anexo = preg_replace(['/[^A-Za-z0-9._\?]/'], '', $file_name); // remove caracteres especiais

		$config['upload_path'] = $this->upload_path;
		$config['allowed_types'] = 'pdf';
		$config['max_size'] = 5120; // 5MB
		$config['file_name'] = $this->file_name_anexo;

		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('anexo')) {
			return [
        'status' => '-1', 
        'mensagem' => strip_tags($this->upload->display_errors())
      ];
		}
		
		return [
      'status' => '1',
      'mensagem' => 'Sucesso'
    ];
	}


  public function incluir($idSolicitacao) {
    $validaAnexo = $this->valida_anexo();
    if ($validaAnexo['status'] == '-1') {
      exit (json_encode([
        'status' => '-1', 
        'mensagem' => $validaAnexo.mensagem
      ]));
    }
    
    $pathAnexo = $this->file_name_anexo ? $this->upload_path . $this->file_name_anexo : NULL;
    $boleto = [
      'anexo_boleto' => $pathAnexo,
      'situacao' => 'finalizado'
    ];

    $idUsuario = $this->auth->get_login_dados('user');
    
    $this->load->model('portal_compras/log_solicitacao', 'log');
		$this->log->cadastrar([
			'acao' => 'adicionar_boleto',
			'id_usuario' => $idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);
      
    $this->load->model('portal_compras/solicitacao');
    $solicitacaoAtualizada = $this->solicitacao->editar($idSolicitacao, $boleto);
    if (empty($solicitacaoAtualizada)) {
      exit (json_encode([
        'status' => '-1', 
        'mensagem' => 'Erro ao incluir boleto, tente novamente.'
      ]));
    }

    exit (json_encode([
      'status' => '1',
      'mensagem' => 'Boleto incluido com sucesso!',
    ]));
  }
}