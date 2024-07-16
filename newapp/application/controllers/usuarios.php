<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Usuarios extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->library('upload');
		$this->load->model('usuario');
		$this->load->model('departamento');
		$this->load->model('auth');
		$this->load->helper('funcionarios_helper');
		$this->load->model('log_shownet');
		$this->load->model('mapa_calor');
	}

	/*
	 * LISTA OS FUNCIONARIOS
	 */
	public function index()
	{
		$this->auth->is_allowed('usuarios_visualiza');
		$dados['titulo'] = lang('funcionarios') . ' - ' . lang('show_tecnologia');
		$dados['load'] = array('buttons_html5', 'datatable_responsive', 'xls', 'ag-grid', 'select2', 'mask', 'XLSX');

		// get departamentos
		$dados['departamentos'] = $this->departamento->getDepartamentos();

		$this->mapa_calor->registrar_acessos_url(site_url('/usuarios'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('usuarios/index');
		$this->load->view('fix/footer_NS');
	}

	/*
	 * CARREGA OS DADOS DOS FUNCIONARIOS PARA A TABELA
	 */
	public function ajaxLoadFuncionarios()
	{
		$table = array();

		$funcionarios = $this->usuario->listarFuncionarios();
		if ($funcionarios) {
			foreach ($funcionarios as $funcionario) {
				$btnAtivarInativar = $btnDemicao = $btnFerias = $btnDetalhes = '';

				if ($this->auth->is_allowed_block('status_funcionario')) {
					if ($funcionario->status == 1) {
						$btnAtivarInativar = '<button data-id="' . $funcionario->id . '" data-status_bloqueio="' . $funcionario->status_bloqueio . '" data-acao="inativar" class="btn btn-default ativarInativar ativarInativar_' . $funcionario->id . '" title="' . lang('inativar') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-check" style= "font-size: 20px;"></i> </button>';
					} else {
						$btnAtivarInativar = '<button data-id="' . $funcionario->id . '" data-status_bloqueio="' . $funcionario->status_bloqueio . '" data-acao="ativar" class="btn btn-success ativarInativar ativarInativar_' . $funcionario->id . '" title="' . lang('ativar') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-check" style= "font-size: 20px;"></i> </button>';
					}
				}
				if ($this->auth->is_allowed_block('aplicar_ferias')) {
					$btnFerias = '<button data-id="' . $funcionario->id . '" data-status="' . $funcionario->status . '" data-data_saida="' . $funcionario->data_saida_ferias . '" data-data_retorno="' . $funcionario->data_retorno_ferias . '" class="btn btn-warning ferias ferias_' . $funcionario->id . '" title="' . lang('ferias') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-clock-o" style= "font-size: 20px;"></i> </button>';
				}
				if ($this->auth->is_allowed_block('demitir')) {
					if ($funcionario->status_bloqueio == 2) {
						$btnDemicao = '<button data-id="' . $funcionario->id . '" data-status="' . $funcionario->status . '" data-acao="readimitir" class="btn btn-default demitir demitir_' . $funcionario->id . '" title="' . lang('readimitir') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-lock" style= "font-size: 20px;"></i> </button>';
					} else {
						$btnDemicao = '<button data-id="' . $funcionario->id . '" data-status="' . $funcionario->status . '" data-acao="demitir" class="btn btn-danger demitir demitir_' . $funcionario->id . '" title="' . lang('demitir') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-lock" style= "font-size: 20px;"></i> </button>';
					}
				}
				if ($this->auth->is_allowed_block('vis_detalhes')) {
					$btnDetalhes = '<button data-id="' . $funcionario->id . '" data-status="' . $funcionario->status . '" data-nome="' . $funcionario->nome . '" class="btn btn-info detalhes detalhes_' . $funcionario->id . '" title="Detalhes da Demissão" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-info" style= "font-size: 20px;"></i> </button>';
				}

				$table['data'][] = array(
					'id' => $funcionario->id,
					'nome' => $funcionario->nome,
					'ocupacao' => $funcionario->ocupacao,
					'telefone' => $funcionario->telefone,
					'empresa' => substr($funcionario->empresa, 0, 15),
					'filial' => $funcionario->city_job,
					'data_nasc' => data_for_humans($funcionario->data_nasc),
					'status' => show_status_funcionario($funcionario->status, $funcionario->status_bloqueio, $funcionario->id),
					'admin' => '<button data-id="' . $funcionario->id . '" class="btn btn-info btnEditFuncionario" title="' . lang('editar') . '" style= "width: 46px; height: 36px; margin-top: 5px;">
									<i class="fa fa-edit icon-white"></i>
								</button>
								<button data-id="' . $funcionario->id . '" class="btn btn-primary listContasbancarias" title="' . lang('contas_bancarias') . '" style= "width: 46px; height: 36px; margin-top: 5px;">
									<i class="fa fa-credit-card icon-white"></i>
								</button>
								<a class="btn btn-info" href="' . site_url('contratos/contrato_trabalho') . '/' . $funcionario->id . '" target="_blank" title="' . lang('imprimir_contrato') . '" style= "width: 46px; height: 36px; margin-top: 5px;">
				                    <i class="fa fa-print"></i>
				                </a> ' . $btnAtivarInativar . ' ' . $btnFerias . ' ' . $btnDemicao . ' ' . $btnDetalhes
				);
			}
		}
		echo json_encode($table);
	}

	public function loadFuncionariosServerSide()
	{
		$startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');
		$nome = $this->input->post('nome');
		$email = $this->input->post('email');

		$startRow++;

		$response = get_listarUsuariosPaginado($startRow, $endRow, $nome, $email);

		if ($response['status'] == '200') {
			echo json_encode(
				array(
					"success" => true,
					"rows" => $response['resultado']['usuarios'],
					"lastRow" => $response['resultado']['qntRetornos']
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

	public function loadFuncionariosByNameServerSide()
	{
		$nome = $this->input->post('nome');

		$response = getUsuarioByName($nome);

		if ($response['status'] == '200') {
			echo json_encode($response['resultado']);
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

	public function getDocumentosFuncionario()
	{
		$startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');
		$cpf = $this->input->post('cpf');

		$startRow++;

		$response['status'] = '404';

		if ($cpf != "Não informado") {
			$response = getDocumentosFuncionarioRoute($cpf, $startRow, $endRow);
		}

		if ($response['status'] == '200') {
			echo json_encode(
				array(
					"success" => true,
					"rows" => $response['resultado']['documentos'],
					"lastRow" => $response['resultado']['qntRetornos']
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

	public function getDocumento()
	{
		$documentId = $this->input->post('documentId');

		$response = getDocumentoById($documentId);

		if ($response['status'] == '200') {
			echo json_encode($response['resultado']);
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

	public function insertDocumento()
	{
		//$dadosRecebidos = json_decode(file_get_contents('php://input'), true);
		$funcionarioId = $this->input->post('funcionarioId');
		$nomeDocumento = $this->input->post('nomeDocumento');
		$documento = $this->input->post('documento');

		$data = array(
			"idFuncionario" => $funcionarioId,
			"nomeDocumento" => $nomeDocumento,
			"documento" => $documento
		);

		//print_r($data);


		$retorno = insertDocumentoFuncionarioRoute($data);

		echo json_encode($retorno);
	}

	public function insertAssociacaoDocumento()
	{
		$funcionarioId = $this->input->post('funcionarioId');
		$nomeDocumento = $this->input->post('nomeDocumento');
		$documento = $this->input->post('documento');
		$tipoDocumentoId = $this->input->post('tipoDocumentoId');

		$data = array(
			"idFuncionario" => $funcionarioId,
			"nomeDocumento" => $nomeDocumento,
			"documento" => $documento
		);

		$retorno = insertDocumentoFuncionarioRoute($data);

		if (empty($retorno) || !isset($retorno["status"])) {
			echo json_encode([
				"status" => 500,
				"mensagem" => "Erro inesperado ao inserir documento."
			]);
			return;
		}

		if ($retorno["status"] == "201") {
			$data = array(
				"idFuncionario" => $funcionarioId,
				"idTipoDocumento" => $tipoDocumentoId,
				"idDocumentosFuncionario" => $retorno['resultado']['id']
			);

			$result = insertAssociacaoDocumentoFuncionarioRoute($data);

			if (!empty($result) && isset($result["status"]) && $result["status"] == "200") {
				echo json_encode($result);
			} else {
				echo json_encode([
					"status" => 500,
					"mensagem" => "Erro ao associar documento ao funcionário."
				]);
			}
		} elseif ($retorno["status"] == 400 && isset($retorno['resultado']["mensagem"]) && $retorno['resultado']["mensagem"] == "Já existe um arquivo como mesmo nome para o funcionario.") {
			echo json_encode([
				"status" => 400,
				"mensagem" => "doc_existente"
			]);
		} else {
			echo json_encode([
				"status" => 500,
				"mensagem" => "Erro ao inserir documento."
			]);
		}
	}


	public function updateAssociacaoDocumento()
	{
		$id = $this->input->post('id');
		$tipoDocumentoId = $this->input->post('tipoDocumentoId');
		$funcionarioId = $this->input->post('funcionarioId');
		$idDocumento = $this->input->post('idDocumento');
		$nomeDocumento = $this->input->post('nomeDocumento');
		$documento = $this->input->post('documento');

		$retornoAssociacao = array();
		$retorno = array();

		$dataDoc = array(
			"id" => $idDocumento
		);

		if (!empty($nomeDocumento) || !empty($documento)) {
			if (!empty($nomeDocumento)) {
				$dataDoc['nomeDocumento'] = $nomeDocumento;
			}
			if (!empty($documento)) {
				$dataDoc['documento'] = $documento;
			}

			$retorno = atualizarDocumentoFuncionarioRoute($dataDoc);
		}

		if (!empty($tipoDocumentoId)) {
			$retornoAssociacao = updateAssociacaoDocumentoFuncionarioRoute(array(
				"id" => $id,
				"idFuncionario" => $funcionarioId,
				"idDocumentosFuncionario" => $idDocumento,
				"idTipoDocumento" => $tipoDocumentoId
			));
		}

		if ((!empty($retorno) && $retorno["status"] == "200") ||
			(!empty($retornoAssociacao) && $retornoAssociacao["status"] == "200")
		) {
			echo json_encode(array(
				"success" => true,
				"status" => $retornoAssociacao["status"]
			));
		} else {
			echo json_encode(array("success" => false, "message" => "Falha na atualização"));
		}
	}

	public function atualizarDocumento()
	{
		$id = $this->input->post('id');
		$nomeDocumento = $this->input->post('nomeDocumento');
		$documento = $this->input->post('documento');

		if ($documento === '' || $documento === null || $documento === "null") {
			$data = array(
				"id" => $id,
				"nomeDocumento" => $nomeDocumento
			);
		} else {
			$data = array(
				"id" => $id,
				"nomeDocumento" => $nomeDocumento,
				"documento" => $documento
			);
		}

		$retorno = atualizarDocumentoFuncionarioRoute($data);

		echo json_encode($retorno);
	}

	public function getAllDocumentAssociations()
	{
		$startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');
		$employeeId = (int) $this->input->post('employeeId');

		$startRow++;

		$result = getDocumentAssociationsRoute($employeeId, $startRow, $endRow);

		if ($result['status'] == '200') {
			echo json_encode(array(
				"success" => true,
				"rows" => $result['resultado']['associacaoFuncionario'],
				"lastRow" => $result['resultado']['quantAssociacaoFuncionarios']
			));
		} else if ($result['status'] == '404') {
			echo json_encode(array(
				"success" => false,
				"message" => "Associação não encontrada!",
			));
		} else {
			echo json_encode(array(
				"success" => false,
				"message" => $result['resultado']['mensagem'],
			));
		}
	}

	public function getAllDocumentTypes()
	{
		$title = $this->input->post('title');

		$result = getAllDocumentTypesRoute($title);

		if ($result['status'] == '200') {
			echo json_encode(array(
				"success" => true,
				"result" => $result['resultado']['tiposDocumentos']
			));
		} else if ($result['status'] == '404') {
			echo json_encode(array(
				"success" => false,
				"status" => "404",
				"message" => "Tipo de documento não encontrado!",
			));
		} else {
			echo json_encode(array(
				"success" => false,
				"message" => $result['resultado']['mensagem'],
			));
		}
	}

	public function add()
	{
		$this->auth->is_allowed('usuarios_add');
		$dados['titulo'] = 'Novo Usuário';

		$this->load->view('fix/header4', $dados);
		$this->load->view('usuarios/add_usuario');
		$this->load->view('fix/footer4');
	}

	public function ativar($id_user)
	{
		$this->usuario->ativarUserById($id_user);
	}

	public function inativar($id_user)
	{
		$this->usuario->inativarUserById($id_user);
	}

	/*
	 * MUDA STATUS DO FUNCIONARIO
	 */
	public function mudarStatusFuncionario()
	{
		$id = (int) $this->input->post('id');
		$acao = $this->input->post('acao');

		$msg = lang('sucesso_ativar_funcionario');
		$dados = array('status' => '1');
		if ($acao == 'inativar') {
			$dados = array('status' => '0');
			$msg = lang('sucesso_inativar_funcionario');
		}

		$dados_antigos = $this->input->post('acao') == "inativar" ? array('status do funcionário' => 'ativo') : array('status do funcionário' => 'inativo');

		if ($this->usuario->updateUsuario($id, $dados)) {
			$campos_alterados = array(
				'status do funcionário' => $acao == 'inativar' ? 'inativo' : 'ativo'
			);
			$id_user = $this->auth->get_login_dados('user');
			$id_user = (int) $id_user;
			$result = $this->log_shownet->gravar_log($id_user, 'usuario', $id, 'atualizar', $dados_antigos, $campos_alterados);
			if ($result == false) {
				throw new Exception('O log das alterações não pode ser salvo');
			}
			echo json_encode(array('success' => true, 'msg' => $msg));
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('operacao_nao_finalizada')));
		}
	}

	/*
	 * DEMITIR / READIMITIR UM FUNCIONARIO
	 */
	public function demitirFuncionario()
	{
		$id = (int) $this->input->post('id');
		$acao = $this->input->post('acao');
		$dados_funcionario_antigo = $this->usuario->getFuncionario($id);

		$msg = lang('funcionario_demitido_sucesso');
		$dados = array(
			'status_bloqueio' => '2',
			'data_saida_ferias' => null,
			'data_retorno_ferias' => null
		);
		$dados_antigos = array(
			'status do funcionario' => $dados_funcionario_antigo->status_bloqueio,
			'data de saida de ferias' => $dados_funcionario_antigo->data_saida_ferias,
			'data de retorno de ferias' => $dados_funcionario_antigo->data_retorno_ferias
		);

		if ($acao == 'readimitir') {
			$dados['status_bloqueio'] = '0';
			$msg = lang('funcionario_readimitido_sucesso');
		}


		if ($this->usuario->updateUsuario($id, $dados)) {

			$id_user = $this->auth->get_login_dados('user');
			$id_user = (int) $id_user;
			$dados_novos_formatados = $this->usuario->formataDadosLog($dados);
			$result = $this->log_shownet->gravar_log($id_user, 'usuario', $id, 'atualizar', $dados_antigos, $dados_novos_formatados);
			echo json_encode(array('success' => true, 'msg' => $msg));
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('operacao_nao_finalizada')));
		}
	}

	/*
	 * DEMITIR / READIMITIR UM FUNCIONARIO
	 */
	public function aplicarFeriasFuncionario()
	{
		$id = $this->input->post('id');
		$acao = $this->input->post('acaoFerias');
		$data_saida_ferias = $this->input->post('data_saida_ferias');
		$data_retorno_ferias = $this->input->post('data_retorno_ferias');

		$dados_funcionario_antigo = $this->usuario->getFuncionario($id);
		$msg = lang('aplicado_ferias_funcionario');
		$dados = array(
			'status_bloqueio' => '1',
			'data_saida_ferias' => $data_saida_ferias,
			'data_retorno_ferias' => $data_retorno_ferias
		);

		$dados_antigos = array(
			'status do funcionario' => $dados_funcionario_antigo->status_bloqueio,
			'data de saida de ferias' => $dados_funcionario_antigo->data_saida_ferias,
			'data de retorno de ferias' => $dados_funcionario_antigo->data_retorno_ferias
		);

		if ($acao == 'remover_ferias') {
			$msg = lang('removido_ferias_funcionario');
			$dados = array(
				'status_bloqueio' => '0',
				'data_saida_ferias' => null,
				'data_retorno_ferias' => null
			);
		}

		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;
		$dados_novos_formatados = $this->usuario->formataDadosLog($dados);
		$result = $this->log_shownet->gravar_log($id_user, 'usuario', $id, 'atualizar', $dados_antigos, $dados_novos_formatados);
		if ($this->usuario->updateUsuario($id, $dados)) {
			echo json_encode(array('success' => true, 'msg' => $msg));
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('operacao_nao_finalizada')));
		}
	}

	public function add_usuario()
	{
		if ($this->input->post()) {
			if ($_POST['login'] && $_POST['senha']) {
				if (empty($_POST['nome']))
					die(json_encode(array('mensagem' => 'Campo nome vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['nacionalidade']))
					die(json_encode(array('mensagem' => 'Campo nacionalidade vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['naturalidade']))
					die(json_encode(array('mensagem' => 'Campo naturalidade vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['data_nasc']))
					die(json_encode(array('mensagem' => 'Campo data de nascimento vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['sexo']))
					die(json_encode(array('mensagem' => 'Campo sexo vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['cpf']))
					die(json_encode(array('mensagem' => 'Campo CPF vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['rg']))
					die(json_encode(array('mensagem' => 'Campo RG vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['emissor_rg']))
					die(json_encode(array('mensagem' => 'Campo orgão emissor vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['data_emissor']))
					die(json_encode(array('mensagem' => 'Campo data de emissão vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['ocupacao']))
					die(json_encode(array('mensagem' => 'Campo ocupação vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['data_admissao']))
					die(json_encode(array('mensagem' => 'Campo data de admissão vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['num_contrato']))
					die(json_encode(array('mensagem' => 'Campo número do contrato vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['pis']))
					die(json_encode(array('mensagem' => 'Campo PIS vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['salario']))
					die(json_encode(array('mensagem' => 'Campo salário vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['city_job']))
					die(json_encode(array('mensagem' => 'Campo cidade (Cidade Filial da Empresa) vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));
				elseif (empty($_POST['ctps']))
					die(json_encode(array('mensagem' => 'Campo carteira profissional (Carteira de trabalho) vazio! Preencha todos os campos obrigatórios!', 'status' => 'ERRO')));

				if (isset($_POST['permissoes']) && count($_POST['permissoes']) > 0)
					$_POST['permissoes'] = serialize($_POST['permissoes']);
				$_POST['senha'] = md5($_POST['senha']);
				$_POST['status'] = '0';
				$insert = $this->usuario->addUser($_POST);
				if ($insert)
					echo json_encode(array('mensagem' => 'Funcionário cadastrado com sucesso!', 'status' => 'OK', 'id_user' => $insert));
				else
					echo json_encode(array('mensagem' => 'Funcionário já possui cadastro!', 'status' => 'ERRO'));
			} else {
				echo json_encode(array('mensagem' => 'Campo login e senha vazio na aba de acessos! Preencha todos os campos obrigatórios!', 'status' => 'ERRO'));
			}
		} else {
			echo json_encode(array('mensagem' => 'Verifique as informações e tente novamente mais tarde!', 'status' => 'ERRO'));
		}
	}

	public function upgrade_contaById()
	{
		$id = $_POST['id'];

		$this->usuario->upgrade_contabank($id);
	}

	public function update_conta($id, $funcionario)
	{
		$dados = $this->input->post();
		$update = $this->usuario->update_conta($dados, $id);

		if ($update)
			$this->session->set_flashdata('sucesso', 'Conta editada com sucesso!');
		else
			$this->session->set_flashdata('erro', 'não foi possÃ­vel atualizar a conta.');

		redirect(site_url('usuarios/visualizar') . '/' . $funcionario);
	}

	public function cad_conta($id_user)
	{
		if ($this->input->post()) {
			$dados = $_POST;
			$dados['cad_retorno'] = 'usuario';
			$dados['id_retorno'] = $id_user;
			$dados['banco'] = substr($dados['banco'], 0, 3);
			$dados['status'] = '0';

			if (empty($dados['titular']))
				die(json_encode(array('mensagem' => 'O Campo TITULAR DA CONTA Ã© obrigatÃ³rio.', 'status' => 'ERRO')));
			elseif (empty($dados['banco']))
				die(json_encode(array('mensagem' => 'O Campo BANCO Ã© obrigatÃ³rio.', 'status' => 'ERRO')));
			elseif (empty($dados['agencia']))
				die(json_encode(array('mensagem' => 'O Campo AGENCIA Ã© obrigatÃ³rio.', 'status' => 'ERRO')));
			elseif (empty($dados['conta']))
				die(json_encode(array('mensagem' => 'O Campo CONTA Ã© obrigatÃ³rio.', 'status' => 'ERRO')));
			elseif (empty($dados['tipo']))
				die(json_encode(array('mensagem' => 'O Campo TIPO DA CONTA Ã© obrigatÃ³rio.', 'status' => 'ERRO')));
			elseif (empty($dados['cpf']))
				die(json_encode(array('mensagem' => 'O Campo CPF DO TITULAR Ã© obrigatÃ³rio.', 'status' => 'ERRO')));

			$insert = $this->usuario->cadConta($dados);

			if ($insert)
				echo json_encode(array('mensagem' => 'Cadastro realizado com sucesso!', 'status' => 'OK', 'retorno' => $insert));
			else
				echo json_encode(array('mensagem' => 'não foi possÃ­vel cadastrar a conta.', 'status' => 'ERRO'));
		} else {
			echo json_encode(array('mensagem' => 'Sistema temporariamente indisponÃ­vel.', 'status' => 'ERRO'));
		}
	}

	public function edit_usuario()
	{
		if ($this->input->post()) {
			if (isset($_POST['id']) && $_POST['id']) {
				$id = $_POST['id'];
				unset($_POST['id']);

				if (isset($_POST['permissoes']) && count($_POST['permissoes']) > 0)
					$_POST['permissoes'] = serialize($_POST['permissoes']);
				if ($_POST['senha'] && !empty($_POST['senha']))
					$_POST['senha'] = md5($_POST['senha']);
				else
					unset($_POST['senha']);

				$update = $this->usuario->editUser($id, $_POST);
				if ($update)
					echo json_encode(array('mensagem' => 'Cadastro atualizado com sucesso!', 'status' => 'OK'));
				else
					echo json_encode(array('mensagem' => 'não foi possÃ­vel atualizar o cadastro do usuário', 'status' => 'ERRO'));
			} else {
				echo json_encode(array('mensagem' => 'Nenhum usuário selecionado.', 'status' => 'ERRO'));
			}
		} else {
			echo json_encode(array('mensagem' => 'Verifique as informaÃ§Ãµes e tente novamente mais tarde!', 'status' => 'ERRO'));
		}
	}

	public function visualizar($id_usuario)
	{
		$this->auth->is_allowed('usuarios_update');
		$dados['titulo'] = 'Visualizar Usuários';
		$dados['usuario'] = $this->usuario->get(array('id' => $id_usuario));
		$dados['contas'] = $this->usuario->getContasUser($id_usuario);

		$this->load->view('fix/header4', $dados);
		$countUsers = (is_array($dados['usuario']) ? count($dados['usuario']) : 1);

		if ($dados['usuario'] && $countUsers > 0)
			$this->load->view('usuarios/edit_usuario');
		else
			$this->load->view('erros/403');
		$this->load->view('fix/footer4');
	}

	public function digitalizar($id)
	{
		$data['arquivos'] = $this->usuario->get_files($id);
		$data['id_usuario'] = $id;
		$this->load->view('usuarios/digitalizar_usuario', $data);
	}

	public function view_file($path)
	{
		redirect('https://gestor.showtecnologia.com:85/sistema/newapp/uploads/usuarios/' . $path);
	}

	private function upload()
	{
		$config['upload_path'] = './uploads/usuarios';
		$config['allowed_types'] = 'pdf|gif|jpg|png|jpeg';
		$config['max_size'] = '0';
		$config['max_width'] = '0';
		$config['max_height'] = '0';
		$config['encrypt_name'] = 'true';
		$this->upload->initialize($config);
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('arquivo')) {
			$data = $this->upload->data();
			return $data;
		} else {
			$error = array('error' => $this->upload->display_errors());
			return false;
		}
	}

	public function digitalizacao($usuario_id)
	{
		$nome_arquivo = "";
		$descricao = $this->input->post('descricao');
		$usuario = $usuario_id;
		$arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;

		if ($descricao == "" || $descricao == "Selecione um tipo") {
			die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descriÃ§Ã£o!</div>')));
		} else {
			if ($arquivo) {
				if ($dados = $this->upload()) {
					$nome_arquivo = $dados['file_name'];
					$arquivo_enviado = true;
				}
				if ($arquivo_enviado) {
					$retorno = $this->usuario->digitalizacao($usuario, $descricao, $nome_arquivo);
					die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Processo realizado com sucesso!</div>', 'registro' => $retorno)));
				} else {
					die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
				}
			} else {
				die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
			}
		}
	}

	public function listar_todos_usuarios()
	{
		$usuarios = $this->usuario->all();
		$results = array();
		if ($usuarios) {
			foreach ($usuarios as $key => $usuario) {
				$results[] = array(
					'id' => $usuario->id,
					'nome' => $usuario->nome

				);
			}
		}

		echo json_encode($results);
	}

	//SELECT2 LISTA NOMES DOS USUARIOS/FUNCIONARIOS
	public function listarNomesUsuarios()
	{
		$like = NULL;
		$dados = array();

		if ($search = $this->input->get('q'))
			$like = array('nome' => $search);

		$usuarios = $this->usuario->listNomeUsuarios($like);
		foreach ($usuarios as $key => $usuario) {
			$dados[] = array(
				'text' => $usuario->nome,
				'id' => $usuario->nome
			);
		}
		echo json_encode(array('results' => $dados));
	}

	public function aniversariantes()
	{
		$mes = date("m");
		$this->auth->is_allowed('usuarios_visualiza');
		$dados['titulo'] = 'Aniversariantes';
		$dados['aniversariantes'] = $this->usuario->listarAniversariantes("MONTH(data_nasc) = '$mes' AND ativo = '1'");

		$this->load->view('fix/header4', $dados);
		$this->load->view('usuarios/lista_nivers');
		$this->load->view('fix/footer4');
	}

	public function email_aniversariantes()
	{
		$dados['msg'] = '';
		$dados['titulo'] = 'Email Aniversáriantes';
		$this->load->view('usuarios/email_aniversariantes.php');
	}

	/**
	 * Função carrega view de listagem/cadastro de permissoes e cargos
	 * @author Renato Silva
	 */
	public function permissoesFuncionariosOld()
	{
		$dados['titulo'] = lang('permissoes_funcionarios');
		$dados['permissoes'] = $this->buscarPermissoes();
		$this->mapa_calor->registrar_acessos_url(site_url('/usuarios/permissoesFuncionarios'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('usuarios/permissoes', $dados);
		$this->load->view('fix/footer_NS');
	}

	public function permissoesFuncionarios()
	{
		$this->auth->is_allowed('cad_permissoes_funcionarios');

		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$dados['titulo'] = lang('permissoes_funcionarios');
		$dados['permissoes'] = $this->buscarPermissoes();
		$this->mapa_calor->registrar_acessos_url(site_url('/usuarios/permissoesFuncionarios'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('usuarios/permissoes_new', $dados);
		$this->load->view('fix/footer_NS');
	}

	public function buscarPermissoesPesquisa() {
		$nome = $this->input->post('nome') ?: '';
		$codPermissao = $this->input->post('codPermissao') ?: '';
		$status = $this->input->post('status') ?: '';

		$result = get_permissoesFuncionarios($nome, $codPermissao, $status);

		if ($result && $result['status'] == 200 && count($result['resultado']) > 0) {
			foreach ($result['resultado'] as &$r) {
				$r["status"] = $r["status"] == "1" ? 'Ativo' : 'Inativo';
			}
		}

		echo json_encode($result);
	}

	public function listaTodasOpcoesPermissoes()
	{
		$perms = array();
		$result = get_permissoesFuncionarios('', '', 1);

		if ($result['status'] == 200) {
			foreach ($result['resultado'] as $permissao) {
				$perms[] = '<option value="' . $permissao['codPermissao'] . '" class="adt" data-section="' . $permissao['modulo'] . '" >' . $permissao['descricao'] . '</option>';
			}
			echo json_encode(array(
				'success' => true, 
				'permissoes' => $perms
			));
		} else {
			echo json_encode(array(
				'success' => false, 
				'status' => $result['status'], 
				'mensagem' => isset($result['resultado']['mensagem']) ?: 'Erro ao listar permissões!'
			));
		}
		
	}

	public function listaOpcoesPermissoesCargo($id)
	{
		try {
			$perms = array();
			$result = get_permissoesFuncionarios('', '', 1);
			$resultCargo = get_buscarPermissoesCargo($id);

			if ($result['status'] == 200) {
				if ($resultCargo['status'] == 200) {
						$permissoesCargo = @unserialize($resultCargo['resultado']['permissoes']);
					if ($permissoesCargo === false && $resultCargo['resultado']['permissoes'] !== 'b:0;') {
						$permissoesCargo = array();
					}
					foreach ($result['resultado'] as $permissao) {
						$selected = in_array($permissao['codPermissao'], $permissoesCargo) ? 'selected' : '';
						$perms[] = '<option value="' . $permissao['codPermissao'] . '" class="adt" data-section="' . $permissao['modulo'] . '" ' . $selected . ' >' . $permissao['descricao'] . '</option>';
					}
					echo json_encode(array(
						'success' => true, 
						'permissoes' => $perms
					));
				} else {
					echo json_encode(array(
						'success' => false, 
						'status' => $resultCargo['status'], 
						'mensagem' => isset($resultCargo['resultado']['mensagem']) ?: 'Não foi possível listar as permissões'
					));
				}
			} else {
				echo json_encode(array(
					'success' => false, 
					'status' => $result['status'], 
					'mensagem' => isset($result['resultado']['mensagem']) ?: 'Não foi possível listar as permissões'
				));
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false, 
				'status' => $result['status'], 
				'mensagem' => 'Não foi possível listar as permissões'
			));
		}
	}

	public function buscarCargosPesquisa() {
		$nome = $this->input->post('nome') ?: '';

		$result = get_cargosFuncionarios($nome);

		if ($result && $result['status'] == 200 && count($result['resultado']) > 0) {
			foreach ($result['resultado'] as &$r) {
				$r["status"] = $r["status"] == "1" ? 'Ativo' : 'Inativo';
			}
		}

		echo json_encode($result);
	}

	public function cadastrarPermissao() {
		$descricao = $this->input->post('descricao');
		$prefixo = $this->input->post('prefixo');
		$modulo = $this->input->post('modulo');
		$nomePerm = $descricao;
		$nomePerm = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $nomePerm);
		$nomePerm = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $nomePerm); //remove caracteres especisias e acentuacao
		$codPermissao = $prefixo . '_' . strtolower($nomePerm);
		
		$POSTFIELDS = array(
			"descricao" => $descricao,
			"codPermissao" => $codPermissao,
			"modulo" => $modulo
		);

		$result = post_cadastrarPermissao($POSTFIELDS);

		echo json_encode($result);
	}

	public function cadastrarCargo()
	{

		$dados = $this->input->post();
		$permissoes = serialize($dados['permissoes']);
		$descricao = $dados['descricao'];

		$POSTFIELDS = array(
			"descricao" => $descricao,
			"permissao" => $permissoes
		);

		$result = post_cadastrarCargo($POSTFIELDS);

		echo json_encode($result);
	}

	public function atualizarCargo($id)
	{
		$dados = $this->input->post();
		$permissoes = serialize($dados['permissoes']);
		$descricao = $dados['descricao'];

		$POSTFIELDS = array(
			"id" => $id,
			"descricao" => $descricao,
			"permissao" => $permissoes
		);

		$result = put_atualizarCargo($POSTFIELDS);

		echo json_encode($result);
	}

	public function alterarStatusPermissao() {
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		$POSTFIELDS = array(
			"id" => $id,
			"status" => $status
		);

		$result = patch_atualizarStatusPermissoes($POSTFIELDS);

		echo json_encode($result);
	}

	public function alterarStatusCargo() {
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		$POSTFIELDS = array(
			"id" => $id,
			"status" => $status
		);

		$result = patch_atualizarStatusCargo($POSTFIELDS);
		
		echo json_encode($result);
	}

	public function buscarPermissoes()
	{

		$this->load->helper('api_helper');
		$resultado = API_Helper::getAPIShow('logistica/listarModulosAtivos?status=Ativo');
		if ($resultado['status'] === 200) {
			if (isset($resultado['data'])) {
				foreach ($resultado['data'] as $value) {
					$result[] = array(
						'id' => $value['id'],
						'nomeModulo' => $value['nomeModulo'],
						'lang' => $value['lang']
					);
				}
			}
		} else {
			$result = $resultado;
		}
		return $result;
	}

	/**
	 * Carrega os dados das datatables de permissoes
	 * @author Renato Silva
	 */
	public function ajaxPermissoes()
	{

		$table['data'] = array();
		$permissoes = $this->usuario->listPermissoes('id, descricao, cod_permissao, status');
		if ($permissoes) {
			foreach ($permissoes as $permissao) {
				$btnAcao = $status = '';
				if ($permissao->status == '1') {
					$status = '<span class="label label-success status_' . $permissao->id . '">' . lang('ativo') . '</span>';
					$btnAcao = '<button type="button" class="btn btn-small btn-success btn_status_permissao btn_permissao_ativo_' . $permissao->id . '" data-status="1" data-id="' . $permissao->id . '" disabled>' . lang('ativar') . '</button>
								<button type="button" class="btn btn-small btn-default btn_status_permissao btn_permissao_inativo_' . $permissao->id . '" data-status="0" data-id="' . $permissao->id . '">' . lang('inativar') . '</button>';
				} else {
					$status = '<span class="label label-default status_' . $permissao->id . '">' . lang('inativo') . '</span>';
					$btnAcao = '<button type="button" class="btn btn-small btn-success btn_status_permissao btn_permissao_ativo_' . $permissao->id . '" data-status="1" data-id="' . $permissao->id . '">' . lang('ativar') . '</button>
								<button type="button" class="btn btn-small btn-default btn_status_permissao btn_permissao_inativo_' . $permissao->id . '" data-status="0" data-id="' . $permissao->id . '" disabled>' . lang('inativar') . '</button>';
				}
				$table['data'][] = array(
					'id' => $permissao->id,
					'descricao' => $permissao->descricao,
					'cod_permissao' => $permissao->cod_permissao,
					'status' => $status,
					'acao' => $btnAcao
				);
			}
		}
		echo json_encode($table);
	}

	/**
	 * SALVA UMA NOVA PERMISSAO DE FUNCIONARIO
	 * @author Renato Silva
	 */
	public function addPermissao()
	{

		$dados = $this->input->post();
		if ($dados) {

			//TRATA A DESCRICAO/NOME E CRIA O COD_PERMISSAO
			$nomePerm = $dados['descricao'];
			$nomePerm = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $nomePerm);
			$nomePerm = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $nomePerm); //remove caracteres especisias e acentuacao
			$dados['cod_permissao'] = $dados['prefixo'] . '_' . strtolower($nomePerm);
			unset($dados['prefixo']);

			$id_permissao = $this->usuario->addPermissao($dados);
			if ($id_permissao) {
				$dados += array(
					'id' => $id_permissao,
					'status' => '<span class="label label-success status_' . $id_permissao . '">' . lang('ativo') . '</span>',
					'acao' => '<button type="button" class="btn btn-small btn-success btn_status_permissao btn_permissao_ativo_' . $id_permissao . '" data-status="1" data-id="' . $id_permissao . '" disabled>' . lang('ativar') . '</button>
							  <button type="button" class="btn btn-small btn-default btn_status_permissao btn_permissao_inativo_' . $id_permissao . '" data-status="0" data-id="' . $id_permissao . '">' . lang('inativar') . '</button>'
				);
				echo json_encode(array('success' => true, 'dados' => $dados, 'msg' => lang('sucesso_cadastro_permissao')));
			} else {
				echo json_encode(array('success' => false, 'msg' => lang('erro_cadastro_permissao')));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('dados_insuficientes')));
		}
	}

	/*
	 * LISTA AS PERMISSOES PARA SER SALVAS EM UM CARGO
	 */
	public function listaTodasPermissoes()
	{
		$perms = array();
		$permissoes = $this->usuario->todasPermissoesModulos();
		//PEGA AS PERMISSOES DO CARGO
		if ($permissoes) {
			foreach ($permissoes as $permissao) {
				$perms[] = '<option value="' . $permissao->cod_permissao . '" class="adt" data-section="' . $permissao->modulo . '" >' . $permissao->descricao . '</option>';
			}
		}
		echo json_encode(array('success' => true, 'permissoes' => $perms));
	}

	/*
	 * LISTA AS PERMISSOES DE UM CARGO
	 */
	public function listaPermissoesCargo($id_cargo)
	{
		$perms = array();
		//PEGA AS PERMISSOES DO CARGO
		$permissoesCargo = $this->usuario->getPermissoesCargo($id_cargo);
		$permissoesCargo = unserialize($permissoesCargo);

		//PEGA TODAS AS PERMISSOES E SELECIONA APENAS AS DO CARGO
		$todasPermissoes = $this->usuario->todasPermissoesModulos();
		if ($todasPermissoes) {
			foreach ($todasPermissoes as $permissao) {
				$selected = in_array($permissao->cod_permissao, $permissoesCargo) ? 'selected' : '';
				$perms[] = '<option value="' . $permissao->cod_permissao . '" class="adt" data-section="' . $permissao->modulo . '" ' . $selected . ' >' . $permissao->descricao . '</option>';
			}
		}
		echo json_encode(array('success' => true, 'permissoes' => $perms));
	}

	/**
	 * ATIVA OU INATIVA UMA PERMISSAO
	 * @author Renato Silva
	 */
	public function ativarInativarPermissao()
	{
		if ($this->input->post()) {

			$id = $this->input->post('id');
			$status = $this->input->post('status');
			$dados['status'] = $status;
			if ($this->usuario->updatePermissao($id, $dados)) {
				if ($status == 0) {
					echo json_encode(array('success' => true, 'msg' => lang('sucesso_inativacao_permissao')));
				} else {
					echo json_encode(array('success' => true, 'msg' => lang('sucesso_ativacao_permissao')));
				}
			} else {
				echo json_encode(array('success' => false, 'msg' => lang('erro_alterar_status_permissao')));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('dados_insuficientes')));
		}
	}


	/**
	 * CADASTRA NOVO CARGO
	 * @author Erick
	 */
	public function addCargo()
	{

		$dados = $this->input->post();
		$dados['permissoes'] = serialize($dados['permissoes']);

		$id_insert = $this->usuario->addCargoPermissao($dados);

		if ($id_insert) {
			$retorno = array(
				'id' => $id_insert,
				'descricao' => $dados['descricao'],
				'status' => '<span class="label label-success status_' . $id_insert . '">' . lang('ativo') . '</span>',
				'acao' => '<button type="button" class="btn btn-small btn-success btn_editar_cargo btn_cargo_ativo_' . $id_insert . '" data-status="1" data-id="' . $id_insert . '" data-descricao="' . $dados['descricao'] . '">' . lang('editar') . '</button>
							<button type="button" class="btn btn-small btn-default btn_status_cargo btn_cargo_inativo_' . $id_insert . '" data-status="0" data-id="' . $id_insert . '">' . lang('inativar') . '</button>'
			);
			echo json_encode(array('success' => true, 'dados' => $retorno, 'msg' => lang('sucesso_cadastro_cargo')));
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('erro_cadastro_cargo')));
		}
	}

	//EDITA UM CARGO
	public function editCargo($id_cargo)
	{
		$dados = $this->input->post();
		if ($dados) {
			$dados['permissoes'] = serialize($dados['permissoes']);
			//ATUALIZA O CARGO
			if ($this->usuario->updateCargo($id_cargo, $dados)) {
				//ATUALIZA AS PERMISSOES DE TODOS OS FUNCIONARIOS COM ESTE CARGO
				$this->usuario->updateFuncionarios(array('cargo' => $id_cargo), array('permissoes' => $dados['permissoes']));
				echo json_encode(array('success' => true, 'msg' => lang('sucesso_atualizar_cargo')));
			} else {
				echo json_encode(array('success' => false, 'msg' => lang('falha_atualizar_cargo')));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('dados_insuficientes')));
		}
	}

	/**
	 * Carrega os dados das datatables de permissoes
	 * @author Erick Leandro
	 */
	public function ajaxCargos()
	{

		$table['data'] = array();
		$cargos = $this->usuario->listCargos('id, descricao, status');
		if ($cargos) {
			foreach ($cargos as $cargo) {
				$btnAcao = $status = '';
				if ($cargo->status == '1') {
					$status = '<span class="label label-success status_' . $cargo->id . '">' . lang('ativo') . '</span>';
					$btnAcao = '<button type="button" class="btn btn-small btn-success btn_editar_cargo btn_cargo_ativo_' . $cargo->id . '" data-status="1" data-id="' . $cargo->id . '" data-descricao="' . $cargo->descricao . '">' . lang('editar') . '</button>
								<button type="button" class="btn btn-small btn-default btn_status_cargo btn_cargo_inativo_' . $cargo->id . '" data-status="0" data-id="' . $cargo->id . '">' . lang('inativar') . '</button>';
				} else {
					$status = '<span class="label label-default status_' . $cargo->id . '">' . lang('inativo') . '</span>';
					$btnAcao = '<button type="button" class="btn btn-small btn-success btn_status_cargo btn_cargo_ativo_' . $cargo->id . '" data-status="1" data-id="' . $cargo->id . '">' . lang('ativar') . '</button>
								<button type="button" class="btn btn-small btn-default btn_status_cargo btn_cargo_inativo_' . $cargo->id . '" data-status="0" data-id="' . $cargo->id . '" disabled>' . lang('inativar') . '</button>';
				}
				$table['data'][] = array(
					'id' => $cargo->id,
					'descricao' => $cargo->descricao,
					'status' => $status,
					'acao' => $btnAcao
				);
			}
		}
		echo json_encode($table);
	}

	public function ajax_get_cargo_data()
	{
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$json = array();
		$json["status"] = 1;
		$json["input"] = array();
		$cargo_id = $this->input->post("cargo_id");
		//die(json_encode(array($cargo_id)));
		$data = $this->usuario->get_data_cargo($cargo_id)->result_array()[0];
		//die(json_encode(array($pemissoes_json)));
		if (isset($data["id_cargo"])) {
			$json["input"]["id_cargo_edit"] = $data["id_cargo"];
		} else {
			$json["input"]["id_cargo_edit"] = $data["id"];
		}
		$json["input"]["cargo_nome_edit"] = $data["descricao"];
		// foreach($pemissoes_json as $pemissoes){
		// 	$json["checkbox"][$pemissoes] = $pemissoes;
		// }

		echo json_encode($json);
	}

	/**
	 * ATIVA OU INATIVA UMA CARGO
	 * @author Erick Leandro
	 */
	public function ativarInativarCargo()
	{
		if ($this->input->post()) {

			$id = $this->input->post('id');
			$status = $this->input->post('status');
			$dados['status'] = $status;
			if ($this->usuario->updateCargo($id, $dados)) {
				if ($status == 0) {
					echo json_encode(array('success' => true, 'msg' => lang('sucesso_inativacao_permissao')));
				} else {
					echo json_encode(array('success' => true, 'msg' => lang('sucesso_ativacao_permissao')));
				}
			} else {
				echo json_encode(array('success' => false, 'msg' => lang('erro_alterar_status_permissao')));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('dados_insuficientes')));
		}
	}

	/*
	 * LISTA TODOS OS CARGOS ATIVOS
	 */
	public function ajaxListCargos()
	{
		$cargos = $this->usuario->listCargos('id, descricao', array('status' => '1'));
		if ($cargos) {
			echo json_encode(array('success' => true, 'cargos' => $cargos));
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('nao_foram_encontrado_cargos')));
		}
	}

	/*
	 * CARREGA AS DE PERMISSOES DE UM CARGO
	 */
	public function listPermissoesCargo($id_cargo)
	{
		$permissoes = array();
		$modulos = $this->usuario->getModulosPorCargos($id_cargo);
		if ($modulos) {
			foreach ($modulos as $modulo) {
				$cods_permissoes = explode(',', $modulo->cods_permissoes);
				if ($cods_permissoes) {
					foreach ($cods_permissoes as $cod_permissao) {
						$permissoes[] = $cod_permissao;
					}
				}
			}
		}
		return $permissoes;
	}

	/*
	 * LISTA AS PERMISSOES DE UM CARGO
	 */
	public function listPermissoesFuncionarios()
	{


		$perms = array();
		$permsExtras = array();
		$id_cargo = $this->input->post('cargo');
		$id_func = $this->input->post('id_func');

		$permissoesFuncionario = array();
		if ($id_func) {
			//PEGA AS PERMISSOES DO FUNCIONARIO
			$permissoesFunc = $this->usuario->getPermissoesUsuario($id_func);
			$permissoesFuncionario = unserialize($permissoesFunc);
		}

		//PEGA AS PERMISSOES DO CARGO
		$permissoesCargo = $this->usuario->getPermissoesCargo($id_cargo);
		$permissoesCargo = unserialize($permissoesCargo);

		//PEGA TODAS AS PERMISSOES
		$todasPermissoes = $this->usuario->todasPermissoesModulos();
		if ($todasPermissoes) {
			foreach ($todasPermissoes as $permissao) {
				$selected = in_array($permissao->cod_permissao, $permissoesFuncionario) ? 'selected' : '';
				if ($permissoesCargo && in_array($permissao->cod_permissao, $permissoesCargo)) {
					$perms[] = '<option value="' . $permissao->cod_permissao . '" class="adt" data-section="' . $permissao->modulo . '" ' . $selected . ' >' . $permissao->descricao . '</option>';
				} else {
					$permsExtras[] = '<option value="' . $permissao->cod_permissao . '" class="adt" data-section="' . $permissao->modulo . '" ' . $selected . ' >' . $permissao->descricao . '</option>';
				}
			}
		}
		if (count($perms) > 0)
			echo json_encode(array('success' => true, 'permissoes' => $perms, 'permissoesExtras' => $permsExtras));
		else
			echo json_encode(array('success' => false, 'permissoesExtras' => $permsExtras));
	}

	/*
	 * LISTA TODOS OS MODULOS E PERMISSOES
	 */
	public function listContasBancariasFuncionario()
	{
		$id_func = $this->input->post('id_funcionario');
		$table = array();

		$contas = $this->usuario->getContasUser($id_func);
		if ($contas) {
			foreach ($contas as $conta) {

				$tornaPadrao = '';
				if ($conta->status == 0)
					$btnTornaPadrao = '<button data-id_funcionario="' . $conta->id_retorno . '" data-id_conta="' . $conta->id . '" class="btn btn-info tornarPrincipal" title="' . lang('tornar_principal') . '"><i class="fa fa-check"></i></button>';
				elseif ($conta->status == 1)
					$btnTornaPadrao = '<button data-id_funcionario="' . $conta->id_retorno . '" data-id_conta="' . $conta->id . '" class="btn btn-success tornarPrincipal" title="' . lang('conta_principal') . '" disabled><i class="fa fa-check"></i></button>';
				else
					$btnTornaPadrao = '<button class="btn btn-info" title="' . lang('conta_cancelada') . '"><i class="fa fa-check"></i></button>';


				$table[] = array(
					'id' => $conta->id,
					'titular' => $conta->titular,
					'cpf' => $conta->cpf,
					'banco' => $conta->banco,
					'agencia' => $conta->agencia,
					'conta' => $conta->conta,
					'operacao' => $conta->operacao,
					'tipo' => $conta->tipo == 'corrente' ? lang('corrente') : lang('poupanca'),
					'data_cadastro' => data_for_humans(explode(' ', $conta->data_cad)[0]),
					'status' => status_conta_bancaria($conta->status, $conta->id),
					'admin' => $btnTornaPadrao . ' ' .
						'<button data-id="' . $conta->id . '" class="btn btn-primary btnEditContaBancaria btnEditContaBancaria_' . $conta->id . '" title="' . lang('editar') . '">
									<i class="fa fa-edit icon-white"></i>
								</button>'
				);
			}
			echo json_encode(array('success' => true, 'contas' => $table));
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('funcionario_nao_possui_contas')));
		}
	}

	public function listContasBancariasFuncionarioServerSide()
	{
		$startRow = $this->input->post('startRow', TRUE) ?: 0;
		$endRow = $this->input->post('endRow', TRUE) ?: 10;

		$searchOptionsRaw = $this->input->post('searchOptions', TRUE);
		$searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

		$id_func = $searchOptions['id_funcionario'];

		$contas = $this->usuario->get_all_user_account_details($startRow, $endRow, $id_func);
		$total_contas = $this->usuario->get_all_user_account_count($id_func);

		if (!empty($contas)) {
			$table = array_map(function ($conta) {
				return [
					'id' => $conta->id,
					'titular' => $conta->titular,
					'cpf' => $conta->cpf,
					'banco' => $conta->banco,
					'agencia' => $conta->agencia,
					'conta' => $conta->conta,
					'operacao' => $conta->operacao,
					'tipo' => $conta->tipo == 'corrente' ? 'Corrente' : 'Poupança',
					'data_cadastro' => $conta->data_cad,
					'status' => $conta->status,
					'id_retorno' => $conta->id_retorno,
				];
			}, $contas);

			$response = [
				'success' => true,
				'rows' => $table,
				'lastRow' => $endRow < $total_contas ? $endRow : $total_contas
			];
		} else {
			$response = [
				'success' => false,
				'message' => 'O funcionário não possui contas.'
			];
		}

		echo json_encode($response);
	}

	/*
	 * CADASTRA UMA NOVA CONTA BANCARIA
	 */
	public function addContaBancaria()
	{
		$dados = $this->input->post();
		if ($dados) {
			if (empty($dados['titular']))
				die(json_encode(array('success' => false, 'msg' => lang('obrigatorio_campo_titular'))));
			elseif (empty($dados['banco']))
				die(json_encode(array('success' => false, 'msg' => lang('obrigatorio_campo_banco'))));
			elseif (empty($dados['agencia']))
				die(json_encode(array('success' => false, 'msg' => lang('obrigatorio_campo_agencia'))));
			elseif (empty($dados['conta']))
				die(json_encode(array('success' => false, 'msg' => lang('obrigatorio_campo_conta'))));
			elseif (empty($dados['tipo']))
				die(json_encode(array('success' => false, 'msg' => lang('obrigatorio_campo_tipo'))));
			elseif (empty($dados['cpf']))
				die(json_encode(array('success' => false, 'msg' => lang('obrigatorio_campo_cpf'))));

			$insert = $this->usuario->cadContaBancaria($dados);
			if ($insert) {
				//LOG
				$dados_novos = array(
					'titular' => $dados['titular'],
					'cpf' => $dados['cpf'],
					'banco' => $dados['banco'],
					'agencia' => $dados['agencia'],
					'conta' => $dados['conta'],
					'operacao' => $dados['operacao'] ? $dados['operacao'] : '',
					'tipo' => $dados['tipo'] == 'corrente' ? lang('corrente') : lang('poupanca'),
				);
				$id_user = $this->auth->get_login_dados('user');
				$id_user = (int) $id_user;

				$result = $this->log_shownet->gravar_log($id_user, 'cad_contasbank', $insert, 'criar', 'null', $dados_novos);
				if ($result == false) {
					throw new Exception('O log das alterações não pode ser salvo');
				}
				echo json_encode(array('success' => true, 'msg' => lang('conta_cadastrada_sucesso'), 'update' =>  false));
			} else {
				echo json_encode(array('success' => false, 'msg' => lang('falha_cadastro_conta')));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('dados_fautosos')));
		}
	}

	public function editContaBancaria($id_conta)
	{
		$dados = $this->input->post();
		$requiredFields = ['titular', 'banco', 'agencia', 'conta', 'tipo', 'cpf'];

		foreach ($requiredFields as $field) {
			if (empty($dados[$field])) {
				echo json_encode([
					'success' => false,
					'msg' => lang("obrigatorio_campo_$field")
				]);
				return;
			}
		}

		$dados_conta_antiga = $this->usuario->getContaBancaria($id_conta);
		$dados_diff = json_decode(json_encode($dados_conta_antiga), true);

		unset(
			$dados_diff["id"],
			$dados_diff["data_cad"]
		);
		
		if(count(array_diff($dados, $dados_diff)) == 0) {
			echo json_encode([
				'success' => false,
				'msg' => "Nenhum dado alterado!",
				'cod' => true
			]);
			return;
		}
		
		if ($this->usuario->updateContaBancaria($id_conta, $dados)) {
			unset(
				$dados_conta_antiga->id,
				$dados_conta_antiga->data_cad,
				$dados_conta_antiga->status,
				$dados_conta_antiga->cad_retorno,
				$dados_conta_antiga->id_retorno
			);

			$id_user = $this->auth->get_login_dados('user');
			$id_user = (int) $id_user;

			$dados_conta_antiga = (array) $dados_conta_antiga;

			$dados_conta_nova = array_diff($dados, $dados_conta_antiga);
			$dados_conta_antiga = array_intersect_key($dados_conta_antiga, $dados_conta_nova);

			if (!empty($dados_conta_nova)) {
				$result = $this->log_shownet->gravar_log($id_user, 'cad_contasbank', $id_conta, 'atualizar', $dados_conta_antiga, $dados_conta_nova);
				if ($result == false) {
					throw new Exception('O log das alterações não pode ser salvo');
				}
			}

			$dados['tipo'] = $dados['tipo'] === 'corrente' ? lang('corrente') : lang('poupanca');
			echo json_encode([
				'success' => true,
				'retorno' => $dados,
				'update' =>  true,
				'msg' => lang('conta_atualizada_sucesso')
			]);
		} else {
			echo json_encode([
				'success' => false,
				'msg' => lang('falha_atualizar_conta')
			]);
		}
	}


	public function getUserBankAccount($account_id)
	{
		$account = $this->usuario->get_user_bank_account_by_id($account_id);
		if ($account) {
			echo json_encode(array('success' => true, 'result' => $account));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Não foi possível carregar os dados da conta bancária no momento, tente novamente mais tarde!'));
		}
	}

	/*
	 * TORNA UMA CONTA, EM CONTA PRINCIPAL
	 */
	public function tornaContaBancariaPrincipal()
	{
		$id_conta = $this->input->post('id_conta');
		$id_funcionario = $this->input->post('id_funcionario');
		if ($this->usuario->tornaContaBancariaPrincipal($id_conta, $id_funcionario)) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('impossivel_tornar_principal')));
		}
	}

	/*
	 * ADICIONA UM FUNCIONARIO
	 */
	public function addFuncionario()
	{
		$dados = $this->input->post();
		if ($dados) {
			if ($dados['login'] && $dados['senha']) {

				if (empty($dados['nome']))
					die(json_encode(array('success' => false, 'msg' => 'Campo nome vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['nacionalidade']))
					die(json_encode(array('success' => false, 'msg' => 'Campo nacionalidade vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['naturalidade']))
					die(json_encode(array('success' => false, 'msg' => 'Campo naturalidade vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['data_nasc']))
					die(json_encode(array('success' => false, 'msg' => 'Campo data de nascimento vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['sexo']))
					die(json_encode(array('success' => false, 'msg' => 'Campo sexo vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['cpf']))
					die(json_encode(array('success' => false, 'msg' => 'Campo CPF vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['rg']))
					die(json_encode(array('success' => false, 'msg' => 'Campo RG vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['emissor_rg']))
					die(json_encode(array('success' => false, 'msg' => 'Campo orgão emissor vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['data_emissor']))
					die(json_encode(array('success' => false, 'msg' => 'Campo data de emissão vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['ocupacao']))
					die(json_encode(array('success' => false, 'msg' => 'Campo ocupação vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['data_admissao']))
					die(json_encode(array('success' => false, 'msg' => 'Campo data de admissão vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['num_contrato']))
					die(json_encode(array('success' => false, 'msg' => 'Campo número do contrato vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['pis']))
					die(json_encode(array('success' => false, 'msg' => 'Campo PIS vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['salario']))
					die(json_encode(array('success' => false, 'msg' => 'Campo salário vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['city_job']))
					die(json_encode(array('success' => false, 'msg' => 'Campo cidade (Cidade Filial da Empresa) vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['ctps']))
					die(json_encode(array('success' => false, 'msg' => 'Campo carteira profissional (Carteira de trabalho) vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['cargo']))
					die(json_encode(array('success' => false, 'msg' => 'Campo cargo vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['id_departamentos']))
					die(json_encode(array('success' => false, 'msg' => str_replace("__campo__", lang("departamento"), lang("validacao_campo")))));

				$dados['status'] = '0'; //POR PADRAO, TODO FUNCIONARIO NOVO É CADASTRADO COMO INATIVO, PODENDO SER ATIVADO POSTERIORMENTE
				$dados['permissoes'] = $this->usuario->getPermissoesCargo($dados['cargo']);
				$dados['senha'] = md5($dados['senha']);

				$insert = $this->usuario->addUser($dados); //retorna o id do user criado
				$id_user = $this->auth->get_login_dados('user');
				$id_user = (int) $id_user;



				//salva no log de usuarios a ação de cadastro
				if ($insert != false) {
					//verifica se o array possui o campo cargo
					if (array_key_exists('cargo', $dados)) {
						//se possuir, pega o id do cargo
						$lista_cargos = $this->usuario->listCargos('id, descricao', array('status' => '1'));
					}
					//verifica se o array possui o campo departamento
					if (array_key_exists('id_departamentos', $dados)) {
						//se possuir, pega o id do departamento
						$lista_departamentos = $this->departamento->getDepartamentos();
					}
					$dados_formatados = $this->usuario->formataDadosLog($dados, $lista_cargos, $lista_departamentos);
					$result = $this->log_shownet->gravar_log($id_user, 'usuario', $insert, 'criar', 'null', $dados_formatados);
					if ($result == false) {
						throw new Exception('O log das alterações não pode ser salvo');
					}
				}

				if ($insert) {

					$btnAtivarInativar = $btnDemicao = $btnFerias = $btnDetalhes = '';

					if ($this->auth->is_allowed_block('status_funcionario')) {
						$btnAtivarInativar = '<button data-id="' . $insert . '" data-status_bloqueio="0" data-acao="ativar" class="btn btn-success ativarInativar ativarInativar_' . $insert . '" title="' . lang('ativar') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-check" style= "font-size: 20px;"></i> </button>';
					}
					if ($this->auth->is_allowed_block('aplicar_ferias')) {
						$btnFerias = '<button data-id="' . $insert . '" data-status="0" data-data_saida="" data-data_retorno="" class="btn btn-warning ferias ferias_' . $insert . '" title="' . lang('ferias') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-clock-o" style="font-size: 20px;"></i> </button>';
					}
					if ($this->auth->is_allowed_block('demitir')) {
						$btnDemicao = '<button data-id="' . $insert . '" data-status="0" data-acao="demitir" class="btn btn-danger demitir demitir_' . $insert . '" title="' . lang('demitir') . '" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-lock" style="font-size:20px; !important"></i> </button>';
					}
					if ($this->auth->is_allowed_block('detalhes')) {
						$btnDetalhes = '<button data-id="' . $insert . '" data-status="0" data-acao="detalhes" class="btn btn-danger detalhes detalhes_' . $insert . '" title="Detalhes da Demissão" style= "width: 46px; height: 36px; margin-top: 5px;"> <i class="fa fa-lock" style="font-size:20px; !important"></i> </button>';
					}

					$novoFunc = array(
						'id' => $insert,
						'nome' => $dados['nome'],
						'ocupacao' => $dados['ocupacao'],
						'telefone' => $dados['telefone'],
						'empresa' => substr($dados['empresa'], 0, 15),
						'filial' => $dados['city_job'],
						'data_nasc' => data_for_humans($dados['data_nasc']),
						'status' => show_status_funcionario($dados['status'], 0, $insert),
						'admin' => '<button data-id="' . $insert . '" class="btn btn-info btnEditFuncionario" title="' . lang('editar') . '" style= "width: 46px; height: 36px; margin-top: 5px;">
										<i class="fa fa-edit icon-white"></i>
									</button>
									<button data-id="' . $insert . '" class="btn btn-primary listContasbancarias" title="' . lang('contas_bancarias') . '" style= "width: 46px; height: 36px; margin-top: 5px;">
										<i class="fa fa-credit-card icon-white"></i>
									</button>
									<a class="btn btn-info" href="' . site_url('contratos/contrato_trabalho') . '/' . $insert . '" target="_blank" title="' . lang('imprimir_contrato') . '" style= "width: 46px; height: 36px; margin-top: 5px;">
					                    <i class="fa fa-print"></i>
					                </a> ' . $btnAtivarInativar . ' ' . $btnFerias . ' ' . $btnDemicao . ' ' . $btnDetalhes
					);
					echo json_encode(array('success' => true, 'retorno' => $novoFunc, 'msg' => 'Funcionário cadastrado com sucesso!'));
				} else {
					echo json_encode(array('success' => false, 'msg' => 'Funcionário já possui cadastro!'));
				}
			} else {
				echo json_encode(array('success' => true, 'msg' => 'Campo login e senha vazio na aba de acessos! Preencha todos os campos obrigatórios!'));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => 'Verifique as informações e tente novamente mais tarde!'));
		}
	}

	/*
	 * CARREGA OS DADOS DO FUNCIONARIO
	 */
	public function ajaxGetFuncionario()
	{
		$id_func = $this->input->post('id_func');

		$funcionario = $this->usuario->getFuncionario($id_func);
		if ($funcionario) {
			unset($funcionario->senha);

			echo json_encode(array('success' => true, 'retorno' => $funcionario));
		} else {
			echo json_encode(array('success' => false, 'msg' => 'Não foi possível carregar os dados do funcionário no momente, tente novamente mais tarde!'));
		}
	}

	/*
	 * ADICIONA UM FUNCIONARIO
	 */
	public function editFuncionario($id_func)
	{
		$dados = $this->input->post();
		if ($dados) {
			if ($dados['loginFunc']) {
				$dados['login'] = $dados['loginFunc'];
				unset($dados['loginFunc']);

				if (empty($dados['nome']))
					die(json_encode(array('success' => false, 'msg' => 'Campo nome vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['nacionalidade']))
					die(json_encode(array('success' => false, 'msg' => 'Campo nacionalidade vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['naturalidade']))
					die(json_encode(array('success' => false, 'msg' => 'Campo naturalidade vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['data_nasc']))
					die(json_encode(array('success' => false, 'msg' => 'Campo data de nascimento vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['sexo']))
					die(json_encode(array('success' => false, 'msg' => 'Campo sexo vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['cpf']))
					die(json_encode(array('success' => false, 'msg' => 'Campo CPF vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['rg']))
					die(json_encode(array('success' => false, 'msg' => 'Campo RG vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['emissor_rg']))
					die(json_encode(array('success' => false, 'msg' => 'Campo orgão emissor vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['data_emissor']))
					die(json_encode(array('success' => false, 'msg' => 'Campo data de emissão vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['ocupacao']))
					die(json_encode(array('success' => false, 'msg' => 'Campo ocupação vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['data_admissao']))
					die(json_encode(array('success' => false, 'msg' => 'Campo data de admissão vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['num_contrato']))
					die(json_encode(array('success' => false, 'msg' => 'Campo número do contrato vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['pis']))
					die(json_encode(array('success' => false, 'msg' => 'Campo PIS vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['salario']))
					die(json_encode(array('success' => false, 'msg' => 'Campo salário vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['empresa']))
					die(json_encode(array('success' => false, 'msg' => 'Campo empresa vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['city_job']))
					die(json_encode(array('success' => false, 'msg' => 'Campo cidade (Cidade Filial da Empresa) vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['ctps']))
					die(json_encode(array('success' => false, 'msg' => 'Campo carteira profissional (Carteira de trabalho) vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['cargo']))
					die(json_encode(array('success' => false, 'msg' => 'Campo cargo vazio! Preencha todos os campos obrigatórios!')));
				elseif (empty($dados['id_departamentos']))
					die(json_encode(array('success' => false, 'msg' => str_replace("__campo__", lang("departamento"), lang("validacao_campo")))));

				if (!empty($dados['salario'])) {
					$dados['salario'] = str_replace('.', '', $dados['salario']);
					$dados['salario'] = str_replace(',', '.', $dados['salario']);
				}

				if (empty($dados['funcao_portal'])) {
					$dados['funcao_portal'] = NULL;
				}

				$dados['permissoes'] = $this->usuario->getPermissoesCargo($dados['cargo']);

				if ($dados['senhaFunc'] && !empty($dados['senhaFunc']))
					$dados['senha'] = md5($dados['senhaFunc']);
				unset($dados['senhaFunc']);

				// pegando os dados do funcionario
				$dados_funcionario_antigo = $this->usuario->getFuncionario($id_func);


				$atualizou = $this->usuario->updateUser($id_func, $dados);
				if ($atualizou) {
					// inicializa
					$lista_cargos = [];
					$lista_departamentos = [];

					// pegando o id do do usuario que esta logado
					$id_user = $this->auth->get_login_dados('user');

					//compara os dados antigos com os novos
					$dados_novos = array_diff($dados, (array) $dados_funcionario_antigo);
					if (count($dados_novos) > 0) {
						//pega os valores antigos dos dados que foram alterados
						$dados_antigos = array_intersect_key((array) $dados_funcionario_antigo, $dados_novos);

						//verifica se o array possui o campo cargo
						if (array_key_exists('cargo', $dados_novos)) {
							$lista_cargos = $this->usuario->listCargos('id, descricao', array('status' => '1'));
						}
						//verifica se o array possui o campo departamento
						if (array_key_exists('id_departamentos', $dados_novos)) {
							$lista_departamentos = $this->departamento->getDepartamentos();
						}
						// formata os dados para serem salvos no log
						$dados_novos = $this->usuario->formataDadosLog($dados_novos, $lista_cargos, $lista_departamentos);
						$dados_antigos = $this->usuario->formataDadosLog($dados_antigos, $lista_cargos, $lista_departamentos);
						// salva as alterações no log
						$result = $this->log_shownet->gravar_log($id_user, 'usuario', $id_func, 'atualizar', $dados_antigos, $dados_novos);
						if ($result == false) {
							throw new Exception('O log das alterações não pode ser salvo');
						}
					}
					$funcEditado = array(
						'nome' => $dados['nome'],
						'ocupacao' => $dados['ocupacao'],
						'telefone' => $dados['telefone'],
						'empresa' => substr($dados['empresa'], 0, 15),
						'filial' => $dados['city_job'],
						'data_nasc' => data_for_humans($dados['data_nasc'])
					);
					echo json_encode(array('success' => true, 'retorno' => $funcEditado, 'msg' => 'Funcionário atualizado com sucesso!'));
				} else {
					echo json_encode(array('success' => false, 'msg' => 'O Funcionário já esta atualizado, altere alguma informação e tente novamente!'));
				}
			} else {
				echo json_encode(array('success' => true, 'msg' => 'Campo login vazio! Preencha todos os campos obrigatórios!'));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => 'Verifique as informações e tente novamente mais tarde!'));
		}
	}

	/*
	 * CADASTRA FUNCIONARIOS EM LOTE
	 */
	public function cadAttFuncionariosLote()
	{
		if ($novosDadosFunc = json_decode($this->input->post('funcionarios'), true)) {

			$emailTodosFuncs = [];
			$atualizarFuncs = [];
			$inserirFuncs = [];

			$funcionariosEmail = $this->usuario->listarFuncionarios('login');
			foreach ($funcionariosEmail as $key => $fun) {
				$emailTodosFuncs[] = trim(strtolower($fun->login));
			}

			$qtdAtualizados = 0;
			$qtdCadastrados = 0;
			foreach ($novosDadosFunc as $key => $funcionario) {

				if (isset($funcionario['login']) && trim($funcionario['login'])) {

					$data_admissao = DateTime::createFromFormat('d/m/Y', trim($funcionario['data_admissao']));
					$data_nasc = DateTime::createFromFormat('d/m/Y', trim($funcionario['data_nasc']));
					//VERIFICA SE AS DATAS SAO VALIDAS
					if (
						$data_admissao && $data_admissao->format('d/m/Y') == trim($funcionario['data_admissao'])
						&& $data_nasc && $data_nasc->format('d/m/Y') == trim($funcionario['data_nasc'])
					) {

						$login = trim(strtolower($funcionario['login']));
						$func = array(
							'login' => $login,
							'nome' => isset($funcionario['nome']) && $funcionario['nome'] ? trim($funcionario['nome']) : '',
							'ocupacao' => isset($funcionario['ocupacao']) && $funcionario['ocupacao'] ? trim($funcionario['ocupacao']) : '',
							'departamento' => isset($funcionario['departamento']) && $funcionario['departamento'] ? trim($funcionario['departamento']) : '',
							'chefia_imediata' => isset($funcionario['chefia_imediata']) && $funcionario['chefia_imediata'] ? trim($funcionario['chefia_imediata']) : '',
							'diretoria' => isset($funcionario['diretoria']) && $funcionario['diretoria'] ? trim($funcionario['diretoria']) : '',
							'unidade' => isset($funcionario['unidade']) && $funcionario['unidade'] ? trim($funcionario['unidade']) : '',
							'data_nasc' => data_for_unix(trim($funcionario['data_nasc'])),
							'data_admissao' => data_for_unix(trim($funcionario['data_admissao'])),
							'cpf' => isset($funcionario['cpf']) && $funcionario['cpf'] ? trim($funcionario['cpf']) : '',
							'empresa' => isset($funcionario['empresa']) && $funcionario['empresa'] ? trim($funcionario['empresa']) : ''
						);

						if (in_array($login, $emailTodosFuncs)) {
							$atualizarFuncs[] = $func;
							if (count($atualizarFuncs) == 99) {
								//ATUALIZA OS FUNCIONARIOS
								$qtdAtualizados += (int) $this->usuario->updateFuncionariosBatch($atualizarFuncs);
								$atualizarFuncs = [];
							}
						} else {
							$func['status'] = '0';
							$inserirFuncs[] = $func;
							if (count($inserirFuncs) == 99) {
								//CADASTRA OS FUNCIONARIOS
								$qtdCadastrados += $this->usuario->insertFuncionariosBatch($inserirFuncs);
								$inserirFuncs = [];
							}
						}
					} else {
						exit(json_encode(array('success' => false, 'msg' => lang('data_invalida_verifique_tente_novamente'))));
					}
				} else {
					exit(json_encode(array('success' => false, 'msg' => lang('email_inexistente_verifique_tente_novamente'))));
				}
			}

			if (!empty($atualizarFuncs))
				$qtdAtualizados += (int) $this->usuario->updateFuncionariosBatch($atualizarFuncs);
			if (!empty($inserirFuncs))
				$qtdCadastrados += (int) $this->usuario->insertFuncionariosBatch($inserirFuncs);

			if ($qtdCadastrados > 0 || $qtdAtualizados > 0) {
				echo json_encode(array('success' => true, 'msg' => lang('sucesso_cadastro_funcionario_lote')));
			} else {
				echo json_encode(array('success' => false, 'msg' => lang('funcionario_lote_ja_existem')));
			}
		} else {
			echo json_encode(array('success' => false, 'msg' => lang('dados_faltosos')));
		}
	}

	public function getAuditoriaShownetOld()
	{
		$this->auth->is_allowed('cad_auditoriashownet');
		$tabelas = $this->log_shownet->carregar_tabelas_log();

		$referencia_tabelas = array();
		foreach ($tabelas as $tabela) {
			array_push($referencia_tabelas, $tabela->referencia_tabela);
		}

		$query = $this->log_shownet->carregar_dados_log($referencia_tabelas, null, null, null, null, 1000);
		$dados['titulo'] = 'Auditoria - ' . lang('show_tecnologia');
		$dados['query'] = json_encode($query);
		$dados['load'] = array('buttons_html5', 'datapicket', 'datatable_responsive');
		$dados['tabelas'] = json_encode($tabelas);
		$this->mapa_calor->registrar_acessos_url(site_url('/usuarios/getAuditoriaShownet'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('usuarios/auditoria_shownet');
		$this->load->view('fix/footer_NS');
	}

	public function getAuditoriaShownet()
	{
		$this->auth->is_allowed('cad_auditoriashownet');

		$tabelas = $this->log_shownet->carregar_tabelas_log();

		$referencia_tabelas = array();
		foreach ($tabelas as $tabela) {
			array_push($referencia_tabelas, $tabela->referencia_tabela);
		}

		$dados['tabelas'] = json_encode($tabelas);
		$dados['referencia'] = json_encode($referencia_tabelas);
		$dados['titulo'] = lang('auditoriashownet');
		$dados['load'] = array('ag-grid', 'select2', 'mask');

		$this->mapa_calor->registrar_acessos_url(site_url('/usuarios/getAuditoriaShownet'));

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('usuarios/auditoria_shownet_new');
		$this->load->view('fix/footer_NS');
	}

	public function getAuditoriaShownetServerSide()
	{
		// verifica se o usuário tem permissão para acessar a página
		try {
			$query = $this->input->post();
			$startRow = $query['startRow'];
			$endRow = $query['endRow'];
			$coluna = $query['coluna'];
			$valor = $query['valorBusca'];
			$tabela = $query['tabelaBusca'];
			$evento = $query['eventoBusca'];
			$dataInicial = $query['dataInicial'];
			$dataFinal = $query['dataFinal'];
			$tabelas = $query['tabelas'];

			$limit = $endRow - $startRow;
			$offset = $startRow;

			if ($coluna == "0") {
				$rows = $this->log_shownet->obter_dados_log($tabelas, null, $valor, null, $dataInicial, $dataFinal, $limit, $offset);
				$lastRow = (int) $this->log_shownet->contar_dados_log($tabelas, null, $valor, null, $dataInicial, $dataFinal);
			} elseif ($coluna == "1") {
				$rows = $this->log_shownet->obter_dados_log($tabelas, $valor, null, null, $dataInicial, $dataFinal, $limit, $offset);
				$lastRow = (int) $this->log_shownet->contar_dados_log($tabelas, $valor, null, null, $dataInicial, $dataFinal);
			} elseif ($coluna == "2") {
				$rows = $this->log_shownet->obter_dados_log($tabelas, null, null, $evento, $dataInicial, $dataFinal, $limit, $offset);
				$lastRow = (int) $this->log_shownet->contar_dados_log($tabelas, null, null, $evento, $dataInicial, $dataFinal);
			} elseif ($coluna == "3") {
				$rows = $this->log_shownet->obter_dados_log($tabela, null, null, null, $dataInicial, $dataFinal, $limit, $offset);
				$lastRow = (int) $this->log_shownet->contar_dados_log($tabela, null, null, null, $dataInicial, $dataFinal);
			} else {
				$rows = $this->log_shownet->obter_dados_log($tabelas, null, null, null, null, null, $limit, $offset);
				$lastRow = (int) $this->log_shownet->contar_dados_log($tabelas, null, null, null, null, null);
			}

			if ($lastRow > 0) {
				foreach ($rows as $row) {
					$antes = new stdClass();
					$depois = new stdClass();
					if ($row->valor_anterior && $row->valor_anterior != 'null') {
						$antes->valor = "Anterior";
						$antes->item = $row->valor_anterior;
					} else {
						$antes->valor = "Anterior";
						$antes->item = "";
					}

					if ($row->valor_novo && $row->valor_novo != 'null') {
						$depois->valor = "Novo";
						$depois->item = $row->valor_novo;
					} else {
						$depois->valor = "Novo";
						$depois->item = "";
					}


					$row->details = array(
						$antes,
						$depois
					);
				}
				echo json_encode(
					array(
						"statusCode" => 200,
						"success" => true,
						"rows" => $rows,
						"lastRow" => $lastRow
					)
				);
			} else {
				echo json_encode(
					array(
						"statusCode" => 404,
						"success" => false,
						"message" => 'Dados não encontrados para os parâmetros informados.',
					)
				);
			}
		} catch (Exception $e) {
			echo json_encode(
				array(
					"statusCode" => 500,
					"success" => false,
					"message" => 'Erro ao listar a auditoria.',
				)
			);
		}
	}


	//inserir dados de demissao
	public function inserir_Dados_De_Demissao()
	{
		$nome = $this->input->post();

		$CI = &get_instance();

		$request = $CI->config->item('url_token_RH') . 'rh/inserirInfoDemissao';


		@session_start();

		if (!isset($_SESSION['tokenRH']) && !isset($_SESSION['validadeRH'])) {

			$this->load->model('usuario');

			$user = $this->auth->get_login_dados('email');
			$senha = '';
			foreach ($this->usuario->get("login ='$user'") as $key => $value) {
				if ($key == 'senha') :
					$senha = $value;
				endif;
			}
			//aquisição do token necessário para requisitar dados na api
			$token = getTokenRH($user, $senha);
			//salvando token na sessão
			$_SESSION['tokenRH'] = $token;
			$_SESSION['validadeRH'] = date("d/m/y H:i:s", strtotime(" + 30 minutes"));
		} else {
			if ($_SESSION['validadeRH'] > date('d/m/y H:i:s')) {
				$token = $_SESSION['tokenRH'];
			} else {
				unset($_SESSION['tokenRH']);
				unset($_SESSION['validadeRH']);
				return $this->inserir_Dados_De_Demissao();
			}
		}

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
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => json_encode(
					array(
						'idUsuario' => $nome['idUsuario'],
						'dataDemissao' => $nome['dataDemissao'],
						'tipoDemissao' => $nome['tipoDemissao'],
						'motivoDemissao' => $nome['motivoDemissao'],
						'recontratarFuturamente' => $nome['recontratarFuturamente']
					)
				),
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer ' . $token,
					'Content-Type: application/json'
				),
			)
		);

		if (curl_error($curl))
			throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);
		echo json_encode(
			array(
				'status' => $statusCode,
				'dados' => $resultado,
			)
		);
	} //fim inserir dados de demissão

	//buscar dados demissão

	public function buscarDemissao()
	{
		$idUsuario = $this->input->post('idUsuario');

		$CI = &get_instance();

		# URL configurada para a API
		$request = $CI->config->item('url_token_RH') . 'rh/listarInfoDemissao?idUsuario=';

		@session_start();

		if (!isset($_SESSION['tokenRH']) && !isset($_SESSION['validadeRH'])) {

			$this->load->model('usuario');

			$user = $this->auth->get_login_dados('email');
			$senha = '';
			foreach ($this->usuario->get("login ='$user'") as $key => $value) {
				if ($key == 'senha') :
					$senha = $value;
				endif;
			}
			//aquisição do token necessário para requisitar dados na api
			$token = getTokenLogistica($user, $senha);
			//salvando token na sessão
			$_SESSION['tokenRH'] = $token;
			$_SESSION['validadeRH'] = date("d/m/y H:i:s", strtotime(" + 30 minutes"));
		} else {
			if ($_SESSION['validadeRH'] > date('d/m/y H:i:s')) {
				$token = $_SESSION['tokenRH'];
			} else {
				$this->load->model('usuario');
				$user = $this->auth->get_login_dados('email');
				$senha = '';
				foreach ($this->usuario->get("login ='$user'") as $key => $value) {
					if ($key == 'senha') :
						$senha = $value;
					endif;
				}
				//aquisição do token necessário para requisitar dados na api
				$token = getTokenLogistica($user, $senha);
				//salvando token na sessão
				$_SESSION['tokenRH'] = $token;
				$_SESSION['validadeRH'] = date("d/m/y H:i:s", strtotime(" + 30 minutes"));
			}
		}
		// echo $request . $idUsuario;

		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $request . $idUsuario,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
					'Accept: application/json',
					'Authorization: Bearer ' . $token
				),
			)
		);

		if (curl_error($curl))
			throw new Exception(curl_error($curl));
		$resultado = json_decode(curl_exec($curl), true);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$result = array();

		if ($statusCode === 200) {
			foreach ($resultado as $value) {
				$result[] = array(
					'id' => $value['id'],
					'idUsuario' => $value['idUsuario'],
					'tipoDemissao' => $value['tipoDemissao'],
					'recontratarFuturamente' => $value['recontratarFuturamente'],
					'motivoDemissao' => $value['motivoDemissao'],
					'dataDemissao' => $value['dataDemissao'],
					'dataCadastro' => $value['dataCadastro'],
					'dataUpdate' => $value['dataUpdate'],
					'status' => $value['status'],
				);
			}
		} else {
			$result = $resultado;
		}

		curl_close($curl);

		echo json_encode(
			array(
				'status' => $statusCode,
				'dados' => $result,
			)
		);
	}


	public function custonAuditoriaShownet()
	{
		// verifica se o usuário tem permissão para acessar a página
		$query = $this->input->post();
		$pesquisa = $query['pesquisa'];
		$dataInicial = $query['dataInicial'];
		$dataFinal = $query['dataFinal'];
		if ($query['tipoPesquisa'] == "0") {
			$result = $this->log_shownet->carregar_dados_log(['usuario', 'cad_contasbank'], null, $pesquisa, $dataInicial, $dataFinal);
			echo json_encode(array('success' => true, 'retorno' => $result));
		} elseif ($query['tipoPesquisa'] == "1") {
			$result = $this->log_shownet->carregar_dados_log(['usuario', 'cad_contasbank'], $pesquisa, null, $dataInicial, $dataFinal);
			echo json_encode(array('success' => true, 'retorno' => $result));
		} elseif ($query['tipoPesquisa'] == "3") {
			$result = $this->log_shownet->carregar_dados_log($pesquisa, null, null, $dataInicial, $dataFinal);
			echo json_encode(array('success' => true, 'retorno' => $result));
		} else {
			$result = $this->log_shownet->carregar_dados_log(['usuario', 'cad_contasbank'], null, null, $dataInicial, $dataFinal);
			echo json_encode(array('success' => true, 'retorno' => $result));
		}
	}

	public function listar()
	{
		$this->load->model('usuario');
		$listaUsuarios = $this->usuario->listarFuncionarios('id, nome', ['status' => 1], 'nome asc');
		exit(json_encode($listaUsuarios));
	}

	public function listarUsuariosPortal($funcao = '') {
		$funcoesPortal = ['solicitante', 'aprovador'];
		if(!empty($funcao)) $funcoesPortal = [$funcao];

		$usuarios = $this->usuario->listarUsuarioPortal('id, nome', $funcoesPortal);
		$results = array();
		if ($usuarios) {
			foreach ($usuarios as $usuario) {
				$results[] = array(
					'id' => $usuario->id,
					'nome' => $usuario->nome,
				);
			}
		}
		echo json_encode($results);
	}

}
