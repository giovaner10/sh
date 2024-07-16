<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function editUser($id_user, $dados)
	{
		return $this->db->where('id', $id_user)->update('usuario', $dados);
	}

	public function update_conta($dados, $id)
	{
		return $this->db->where('id', $id)->update('cad_contasbank', $dados);
	}

	public function upgrade_contabank($id)
	{
		$conta = $this->get_contaById($id);
		$contas = $this->get_contasById($conta->id_retorno);

		$cs = array();
		foreach ($contas as $conta) {
			if ($conta->status != '2')
				$cs[] = $conta->id;
		}

		if ($cs && count($cs) > 0)
			$this->db->where_in('id', $cs)->update('cad_contasbank', array('status' => '0'));
		return $this->db->where('id', $id)->update('cad_contasbank', array('status' => '1'));
	}

	public function get_contaById($id)
	{
		return $this->db->get_where('cad_contasbank', array('id' => $id))->row();
	}

	public function get_contasById($id)
	{
		return $this->db->get_where('cad_contasbank', array('id_retorno' => $id, 'cad_retorno' => 'usuario'))->result();
	}

	public function addUser($dados)
	{
		$query = $this->db->get_where('usuario', array('login' => $dados['login']))->result();
		if (!$query) {
			$this->db->insert('usuario', $dados);
			return $this->db->insert_id();
		}
		return false;
	}

	public function ativarUserById($id_user)
	{
		return $this->db->where('id', $id_user)->update('usuario', array('status' => '1'));
	}

	public function inativarUserById($id_user)
	{
		return $this->db->where('id', $id_user)->update('usuario', array('status' => '0'));
	}

	public function getContasUser($id_user)
	{
		return $this->db->get_where('cad_contasbank', array('cad_retorno' => 'usuario', 'id_retorno' => $id_user))->result();
	}

	public function get_all_user_account_details($startRow = 0, $endRow = 10, $id_user)
	{
		$limit = $endRow - $startRow;
		$offset = $startRow;

		$this->db->select('i.id, i.titular, i.cpf, i.banco, i.agencia, i.conta, i.operacao, i.tipo, i.data_cad, i.status, i.id_retorno');
		$this->db->from('showtecsystem.cad_contasbank i');
		$this->db->where('i.cad_retorno', 'usuario');
		$this->db->where('i.id_retorno', $id_user);
		$this->db->order_by('i.id', 'DESC');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}

	public function get_all_user_account_count($id_user = null)
	{
		$this->db->select('COUNT(DISTINCT i.id) AS total');
		$this->db->from('showtecsystem.cad_contasbank i');
		$this->db->where('i.cad_retorno', 'usuario');
		$this->db->where('i.id_retorno', $id_user);
		return $this->db->get()->row()->total;
	}

	public function get_user_bank_account_by_id($account_id, $select = '*')
	{
		$query = $this->db->select($select)->where('i.id', $account_id)->get('showtecsystem.cad_contasbank i');
		return $query->num_rows() > 0 ? $query->row() : false;
	}


	public function listar($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC')
	{
		$query = $this->db//->where($where)
			->order_by($campo_ordem, $ordem)
			->get('usuario', $limite, $paginacao);
		return $query->result();
	}

	//RETORNA OS FUNCIONARIOS/USUARIOS
	public function listarFuncionarios($select = '*', $where = [], $order = "id DESC")
	{
		$query = $this->db->select($select)->where($where)->order_by($order)->get('showtecsystem.usuario');
		return $query->num_rows() > 0 ? $query->result() : false;
	}

	public function listarAniversariantes($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'DAY(data_nasc)', $ordem = 'ASC')
	{

		$query = $this->db->where($where)
			->order_by($campo_ordem, $ordem)
			->get('cad_aniversariantes', $limite, $paginacao);

		return $query->result();
	}

	public function cadConta($dados)
	{
		$query = $this->db->get_where('cad_contasbank', $dados)->result();
		if (!$query) {
			$insert = $this->db->insert('cad_contasbank', $dados);

			if ($insert)
				$this->upgrade_contabank($this->db->insert_id());

			return $dados;
		}
		return false;
	}

	//SALVA UMA NOVA CONTA NO BANCO
	public function cadContaBancaria($dados)
	{
		$query = $this->db->get_where('cad_contasbank', $dados)->result();
		if (!$query) {
			$this->db->insert('cad_contasbank', $dados);
			return $this->db->insert_id();
		}
		return false;
	}

	public function getTokenByIdClie($id_cliente)
	{
		$aux = $this->db->select('token')->get_where('showtecsystem.usuario_gestor', array('id_cliente' => $id_cliente, 'status_usuario' => 'ativo'))->row();

		if ($aux)
			return $aux->token;
		return false;
	}

	public function all()
	{
		$query = $this->db->order_by('nome', "ASC")->get_where('usuario', array('status' => '1'));

		if ($query->num_rows()) {
			return $query->result();
		}

		return false;
	}

	public function total_lista($where = array())
	{

		$total = $this->db->where($where)->count_all_results('usuario');

		return $total;
	}

	public function inserir($dados)
	{

		if ($this->validar_email_unico($dados['login'])) {

			$this->db->insert('usuario', $dados);
			if ($this->db->affected_rows() == 0)
				throw new Exception('Não foi possível salvar no banco de dados. Por favor tente novamente');
		} else {

			throw new Exception('O email já está em uso, por favor utilize outro email para o cadastro.');
		}
	}

	public function atualizar($id, $dados)
	{

		$usuario = $this->get(array('id' => $id));
		$d_bd = $dados;

		if (isset($dados['senha']) && $dados['senha'] != '') {
			$d_bd['senha'] = md5($dados['senha']);
		} else {
			unset($d_bd['senha']);
		}

		if ($dados['login'] != $usuario->login && !$this->validar_email_unico($dados['login'])) {
			throw new Exception('O email informado já está em uso. Tente utilizar outro email.');
		}

		$this->db->update('usuario', $d_bd, array('id' => $id));
	}

	public function get($where)
	{

		$usuario = false;
		$query = $this->db->get_where('usuario', $where);

		if ($query->num_rows() > 0)
			foreach ($query->result() as $usuario);

		return $usuario;
	}

	private function validar_email_unico($email)
	{

		$total = $this->db->where(array('login' => $email))->count_all_results('usuario');

		if ($total == 0)
			return true;

		return false;
	}

	public function get_with_cliente($id)
	{
		$query = $this->db
			->select('u.code as id, u.usuario as login, u.nome_usuario as nome, c.nome as cliente')
			->from('usuario_gestor u')
			->join('cad_clientes c', 'u.id_cliente = c.id')
			->where('code', $id)
			->limit(1)
			->get();

		if ($query->num_rows()) {
			return $query->row();
		}

		return false;
	}

	public function get_usuario_gestor($code)
	{
		// VERIFICA QUAL A PLACA
		$placa = $this->db->select('placa, id_usuario')->get_where('systems.cadastro_veiculo', array('code' => $code))->row();

		// BUSCA GRUPO MASTER DO CLIENTE
		$id_master = $this->db->select('id')->get_where('showtecsystem.cadastro_grupo', array('id_cliente' => $placa->id_usuario, 'nome' => 'MASTER'))->row();
		$placa = $this->db->select('placa, id_usuario')->get_where('systems.cadastro_veiculo', array('code' => $code))->row();

		if ($id_master) {
			if ($placa && property_exists($placa, 'placa')) {
				$placaValue = $placa->placa;
				$query = $this->db->select('usuario.usuario')
					->from('showtecsystem.veic_x_group as veic')
					->join('showtecsystem.user_x_group as user', 'veic.groupon = user.id_group', 'INNER')
					->join('showtecsystem.usuario_gestor as usuario', 'user.id_user = usuario.code', 'INNER')
					->where('veic.placa', $placa->placa)
					->where('user.id_group', $id_master->id)
					->get();

				if ($query->num_rows()) {
					return $query->result();
				}
			} else {
				return false;
			}
		}

		return false;
	}

	public function get_usuGestor()
	{
		$query = $this->db->select('id_cliente, usuario')->get('usuario_gestor');

		if ($query->num_rows() > 0)
			return $query->result();
		return false;
	}

	public function get_files($id)
	{
		$this->db->where('ndoc', $id);
		$this->db->where('pasta', 'usuarios');
		$query = $this->db->get('showtecsystem.arquivos');

		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function digitalizacao($id, $descricao, $nome_arquivo)
	{
		$pasta = "usuarios";
		$dados = array(
			'file' => $nome_arquivo,
			'descricao' => $descricao,
			'pasta' => $pasta,
			'ndoc' => $id

		);

		$resposta = $this->db->insert('showtecsystem.arquivos', $dados);

		if ($resposta) {
			$this->db->update('showtecsystem.usuario', array('arquivo' => 1), array('id' => $id));
			return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo);
		} else {
			return false;
		}
	}

	function vincularUsuarioComClienteSim2m($emails, $id_cliente)
	{
		pr($emails);
		die;
		foreach ($emails as $email) {
			$this->db->where('login', $email);
			$this->db->update('usuario', array('id_cliente_sim2m' => $id_cliente));
		}
	}

	function atendenteSim2m($id_cliente)
	{
		$this->db->where('vendedor_sim2m !=', 'v');
		$this->db->where('id_cliente_sim2m', $id_cliente);
		return $this->db->get('showtecsystem.usuario')->result();
	}

	function vincularVendedorComClienteSim2m($emails, $id_cliente)
	{
		foreach ($emails as $email) {
			# code...
			$this->db->where('login', $email);
			$this->db->update('usuario', array('id_cliente_sim2m' => $id_cliente, 'vendedor_sim2m' => 'v'));
		}
	}

	function vendedorSim2m($id_cliente)
	{
		$this->db->where('id_cliente_sim2m', $id_cliente);
		$this->db->where('vendedor_sim2m', 'v');
		return $this->db->get('showtecsystem.usuario')->result();
	}

	function search($where)
	{
		$usuario = false;
		$this->db->like('nome', $where['nome']);
		return $this->db->get('showtecsystem.usuario')->result();
	}


	public function getColaboradores($campo_ordem = 'nome', $ordem = 'ASC')
	{

		$query = $this->db->select('nome,ocupacao,ramal_telefone,login')->from('usuario')->order_by($campo_ordem, $ordem)->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return null;
	}

	public function getUser($id_user, $select = "*")
	{
		$query = $this->db->select($select)
			->where('id', $id_user)
			->get('showtecsystem.usuario');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function getUser_posVenda($id_user)
	{
		$query = $this->db->select('nome')
			->where('id', $id_user)
			->get('showtecsystem.usuario');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	//BUSCA OS NOMES DE TODOS OS USUARIOS/FUNCIONARIOS
	public function listNomeUsuarios($like)
	{
		$this->db->select('nome');
		if ($like)
			$this->db->like($like);
		$this->db->order_by('nome', "ASC");
		$query = $this->db->get_where('showtecsystem.usuario', array('status' => 1), 10, 0);

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	//BUSCA AS PERMISSOES DE FUNCIONARIOS
	public function listPermissoes($select)
	{
		$query = $this->db->select($select)
			->order_by('id', "ASC")
			->get('showtecsystem.cad_permissoes_funcionarios');

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	//ADICIONA UMA NOVA PERMISSAO AO BANCO
	public function addPermissao($dados)
	{
		$query = $this->db->get_where('showtecsystem.cad_permissoes_funcionarios', array('cod_permissao' => $dados['cod_permissao']))->result();
		if (!$query) {
			$this->db->insert('showtecsystem.cad_permissoes_funcionarios', $dados);
			return $this->db->insert_id();
		}
		return false;
	}

	//ATUALIZA UMA PERMISSAO
	public function updatePermissao($id, $dados)
	{
		$this->db->where('id', $id)->update('showtecsystem.cad_permissoes_funcionarios', $dados);
		return $this->db->affected_rows();
	}

	//ATUALIZA UM CARGO
	public function updateCargo($id, $dados)
	{
		$this->db->where('id', $id)->update('showtecsystem.cad_cargo', $dados);
		return $this->db->affected_rows();
	}

	public function addCargoPermissao($dados)
	{
		$query = $this->db->get_where('showtecsystem.cad_cargo', array('descricao' => $dados['descricao']))->result();
		if (!$query) {
			$this->db->insert('showtecsystem.cad_cargo', $dados);
			return $this->db->insert_id();
		}
		return false;
	}

	public function addCargoPermissaoId($modulos, $id)
	{

		foreach ($modulos as $modulo) {
			$data = array(
				'id_modulo' => $modulo,
				'id_cargo' => $id
			);
			$this->db->insert('showtecsystem.cad_cargo_modulo_funcionario', $data);
		}
		if ($data) {
			return true;
		}
		return false;
	}

	//BUSCA AS CARGOS
	public function listCargos($select, $where = array())
	{
		$query = $this->db->select($select)
			->where($where)
			->order_by('id', "ASC")
			->get('showtecsystem.cad_cargo');

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	//BUSCA CARGO POR ID
	public function get_data_cargo($id)
	{
		$this->db->select("showtecsystem.cad_cargo_modulo_funcionario.id,
		showtecsystem.cad_cargo.id as id_cargo,
		showtecsystem.cad_cargo_modulo_funcionario.id_modulo,
		showtecsystem.cad_cargo.descricao,
		showtecsystem.cad_modulos_funcionarios.nome");
		$this->db->from("showtecsystem.cad_cargo_modulo_funcionario");
		$this->db->join("showtecsystem.cad_cargo ", 'showtecsystem.cad_cargo.id = showtecsystem.cad_cargo_modulo_funcionario.id_cargo');
		$this->db->join("showtecsystem.cad_modulos_funcionarios", 'showtecsystem.cad_cargo_modulo_funcionario.id_modulo = showtecsystem.cad_modulos_funcionarios.id');
		$this->db->where("showtecsystem.cad_cargo.id", $id);
		$query = $this->db->get();
		if ($query->num_rows()) {
			return $query;
		} else {
			$this->db->select("showtecsystem.cad_cargo.id, showtecsystem.cad_cargo.descricao");
			$this->db->from("showtecsystem.cad_cargo");
			$this->db->where("showtecsystem.cad_cargo.id", $id);
			return $this->db->get();
		}
		return false;
	}

	//ATUALIZA UM MODULO
	public function updateModulo($id, $dados)
	{
		$this->db->where('id', $id)->update('showtecsystem.cad_modulos_funcionarios', $dados);
		return $this->db->affected_rows();
	}

	public function addModuloPermissao($modulo_nome)
	{
		$query = $this->db->get_where('showtecsystem.cad_modulos_funcionarios', array('nome' => $modulo_nome))->result();
		if (!$query) {
			$data = array(
				'nome' => $modulo_nome,
				'status' => '1'
			);
			$this->db->insert('showtecsystem.cad_modulos_funcionarios', $data);
			return $this->db->insert_id();
		}
		return false;
	}

	public function addModuloPermissaoId($id_permissao, $id_modulo)
	{
		$data = array('id_modulo' => $id_modulo, 'id_permissao' => $id_permissao);
		$this->db->insert('showtecsystem.cad_modulo_permissao_funcionario', $data);

		if ($this->db->affected_rows()) return true;
		return false;
	}

	public function editModuloPermissao($id, $permissoes, $modulo_nome)
	{
		$this->db->set('nome', $modulo_nome);
		$this->db->where('id', $id);
		$this->db->update('showtecsystem.cad_modulos_funcionarios');

		$this->db->delete('showtecsystem.cad_modulo_permissao_funcionario', array('id_modulo' => $id));
		foreach ($permissoes as $permissao) {
			$data = array(
				'id_modulo' => $id,
				'id_permissao' => $permissao
			);
			if (!($this->db->insert('showtecsystem.cad_modulo_permissao_funcionario', $data))) {
				return false;
			}
		}

		return true;
	}

	//BUSCA OS MODULOS
	public function listModulos($select, $where = null)
	{
		$this->db->select($select);
		$this->db->from('showtecsystem.cad_modulos_funcionarios');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('id', "ASC");
		$query = $this->db->get();
		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	//BUSCA MODULO POR ID
	public function get_data_modulo($id)
	{
		$this->db->select("showtecsystem.cad_modulos_funcionarios.id as id_modulo,
		showtecsystem.cad_modulos_funcionarios.nome as nome_modulo,
		showtecsystem.cad_modulos_funcionarios.status as status_modulo,
		showtecsystem.cad_modulo_permissao_funcionario.id_modulo,
		showtecsystem.cad_modulo_permissao_funcionario.id_permissao,
		showtecsystem.cad_permissoes_funcionarios.id,
		showtecsystem.cad_permissoes_funcionarios.descricao,
		showtecsystem.cad_permissoes_funcionarios.cod_permissao");
		$this->db->from("showtecsystem.cad_modulos_funcionarios");
		$this->db->join("showtecsystem.cad_modulo_permissao_funcionario", 'showtecsystem.cad_modulos_funcionarios.id = showtecsystem.cad_modulo_permissao_funcionario.id_modulo');
		$this->db->join("showtecsystem.cad_permissoes_funcionarios", 'showtecsystem.cad_permissoes_funcionarios.id = showtecsystem.cad_modulo_permissao_funcionario.id_permissao');
		$this->db->where("showtecsystem.cad_modulos_funcionarios.id", $id);
		return $this->db->get();
	}


	//ATUALIZA UM FUNCIONARIO
	public function updateUsuario($id, $dados)
	{
		$this->db->where('id', $id)->update('showtecsystem.usuario', $dados);
		return $this->db->affected_rows();
	}

	//ATUALIZA FUNCIONARIOS POR WHERE
	public function updateFuncionarios($where, $dados)
	{
		$this->db->where($where)->update('showtecsystem.usuario', $dados);
		return $this->db->affected_rows();
	}

	//CARREGA AS PERMISSOES DE UM USUARIO
	public function getPermissoesUsuario($id_func)
	{
		$query = $this->db->select('permissoes')->where(array('id' => $id_func))->get('showtecsystem.usuario');
		if ($query->num_rows() > 0) {
			return $query->row()->permissoes;
		}
		return false;
	}

	/*
	 * TORNA UMA CONTA, EM CONTA PRINCIPAL
	 */
	public function tornaContaBancariaPrincipal($id_conta, $id_funcionario)
	{
		$contaPrincipalAtual = $this->db->select('id')->where(array('cad_retorno' => 'usuario', 'id_retorno' => $id_funcionario, 'status' => '1'))->get('showtecsystem.cad_contasbank')->row();
		if ($contaPrincipalAtual)
			$this->db->where('id', $contaPrincipalAtual->id)->update('showtecsystem.cad_contasbank', array('status' => '0'));

		$this->db->where('id', $id_conta)->update('showtecsystem.cad_contasbank', array('status' => '1'));
		return $this->db->affected_rows();
	}

	/*
	 * ATUALIZA UMA CONTA BANCARIA
	 */
	public function updateContaBancaria($id_conta, $dados)
	{
		$this->db->where('id', $id_conta)->update('showtecsystem.cad_contasbank', $dados);
		return $this->db->affected_rows();
	}

	//RETORNA OS DADOS DO FUNCIONARIO/USUARIO
	public function getFuncionario($id_func, $select = '*')
	{

		$query = $this->db->select($select)->where('id', $id_func)->get('showtecsystem.usuario');
		return $query->num_rows() > 0 ? $query->row() : false;
	}

	/*
	 * ATUALIZA UM FUNCIONARIO/USUARIO
	 */
	public function updateUser($id_user, $dados)
	{
		$this->db->where('id', $id_user)->update('showtecsystem.usuario', $dados);
		return $this->db->affected_rows();
	}

	/*
	 *  SALVA DADOS DE FUNCIONARIOS EM LOTE, RETORNA A QUANTIDADE DE FUNCIONARIOS CADASTRADOS
	 */
	public function insertFuncionariosBatchString($data = array(), $ignore = false)
	{
		$sql = '';
		if (!empty($data)) {
			$funcionarios = array();

			foreach ($data as $row) {
				$insert_string = $this->db->insert_string('showtecsystem.usuario', $row);
				if (empty($funcionarios) && $sql == '')
					$sql = substr($insert_string, 0, stripos($insert_string, 'VALUES'));

				$funcionarios[] = trim(substr($insert_string, stripos($insert_string, 'VALUES') + 6));
			}

			$sql .= 'VALUES' . implode(',', $funcionarios);

			if ($ignore) $sql = str_ireplace('INSERT INTO', 'INSERT IGNORE INTO', $sql);

			$this->db->query($sql);
			return $this->db->affected_rows();
		}
		return false;
	}

	/*
	 *  ATUALIZA DADOS DE FUNCIONARIOS EM LOTE, RETORNA A QUANTIDADE DE FUNCIONARIOS ATUALIZADOS
	 */
	public function updateFuncionariosBatch($update, $identificador = 'login')
	{
		@$this->db->update_batch('showtecsystem.usuario', $update, $identificador);
		return $this->db->affected_rows();
	}

	/*
	 *  INSERE FUNCIONARIOS EM LOTE
	 */
	public function insertFuncionariosBatch($insert)
	{
		$this->db->insert_batch('showtecsystem.usuario', $insert);
		return $this->db->affected_rows();
	}


	//RETORNA TODAS AS PERMISSOES
	public function todasPermissoesModulos()
	{
		$query = $this->db->select('cod_permissao, descricao, modulo')
			->where(array('status' => '1'))
			->get('showtecsystem.cad_permissoes_funcionarios');

		if ($query->num_rows() > 0) return $query->result();
		return false;
	}

	//RETORNA AS PERMISSOES DE UM CARGO
	public function getPermissoesCargo($id_cargo)
	{
		$query = $this->db->select('permissoes')
			->where(array('id' => $id_cargo))
			->get('showtecsystem.cad_cargo');

		if ($query->num_rows() > 0) return $query->row()->permissoes;
		return false;
	}

	//RETORNA OS MODULOS DE UM CONJUNTO DE PERMISSOES
	//$permissoes eh um array de cod_permissao
	public function getModulosPermissoes($permissoes)
	{
		$query = $this->db->select('modulo, cod_permissao, descricao')
			->where_in('cod_permissao', $permissoes)
			->get('showtecsystem.cad_permissoes_funcionarios');

		if ($query->num_rows() > 0) return $query->result();
		return false;
	}

	/*
	 * RETORNA DADOS DE UM CONJUNTO DE FUNCIONARIOS/USUARIOS
	 */
	public function listDadosUsersShownet($ids_users, $select = '*')
	{
		$query = $this->db->select($select)->where_in('id', $ids_users)->get('showtecsystem.usuario');
		return $query->num_rows() > 0 ? $query->result() : false;
	}

	public function isertLogUsusario($acao, $id_user = 0, $id_user_alterado, $campos = "")
	{
		if ($campos != "") {
			$campos = json_encode($campos, JSON_UNESCAPED_UNICODE);
		}
		$data = array(
			'id_usuario' => $id_user,
			'id_usuario_alterado' => $id_user_alterado,
			'acao' => $acao,
			'campos_modificados' => $campos
		);
		$query = $this->db->insert('showtecsystem.usuario_log', $data);
		return $query;
	}
	public function getAllLogsUsuarios($id_user = 0, $limit = 100, $where = array())
	{
		if ($id_user == 0) {
			$query = $this->db->select('usuario_log.*')
				->where($where)
				->order_by('usuario_log.id', 'DESC')
				->get('showtecsystem.usuario_log', $limit);
			if ($query->num_rows() > 0) {
				$resultado = $query->result();
				foreach ($resultado as $key => $value) {
					$resultado[$key]->id_usuario_alterado = $this->getFuncionario($value->id_usuario_alterado, 'nome');
					$resultado[$key]->id_usuario = $this->getFuncionario($value->id_usuario, 'nome');
				}
				return $resultado;
			}
		}

		return false;
	}

	public function getContaBancaria($id_conta)
	{
		$query = $this->db->select('*')
			->where(array('id' => $id_conta))
			->get('showtecsystem.cad_contasbank');
		if ($query->num_rows() > 0) return $query->row();
		return false;
	}

	public function formataDadosLog($dados, $lista_cargos = [], $lista_departamentos = [])
	{

		// verifica se existe o campo sexo
		if (isset($dados['sexo'])) {
			$dados['sexo'] = $dados['sexo'] == 'M' ? 'Masculino' : 'Feminino';
		}
		// verifica se existe o campo id_departamentos
		if (isset($dados['id_departamentos'])) {
			foreach ($lista_departamentos as $key => $value) {
				if ($value->id == $dados['id_departamentos']) {
					$dados['id_departamentos'] = $value->nome;
				}
			}
			//troca o nome da key id_departamentos para departamento
			$dados['departamento'] = $dados['id_departamentos'];
			unset($dados['id_departamentos']);
		}
		if (isset($lista_cargos)) {
			foreach ($lista_cargos as $key => $value) {
				if ($dados['cargo'] == $value->id) {
					$dados['cargo'] = $value->descricao;
				}
			}
		}

		// verifica se existe o campo data_nasc e troca por data de nascimento
		if (isset($dados['data_nasc'])) {
			$dados['data de nascimento'] = $dados['data_nasc'];
			unset($dados['data_nasc']);
		}

		if (isset($dados['civil'])) {
			$dados['estado civil'] = $dados['civil'];
			unset($dados['civil']);
		}

		if (isset($dados['emissor_rg'])) {
			$dados['orgão emissor'] = $dados['emissor_rg'];
			unset($dados['emissor_rg']);
		}

		if (isset($dados['data_emissor'])) {
			$dados['data emissão'] = $dados['data_emissor'];
			unset($dados['data_emissor']);
		}

		if (isset($dados['data_admissao'])) {

			$dados['data de admissão'] = $dados['data_admissao'];
			unset($dados['data_admissao']);
		}

		if (isset($dados['num_contrato'])) {
			$dados['número do contrato'] = $dados['num_contrato'];
			unset($dados['num_contrato']);
		}

		if (isset($dados['chefia_imediata'])) {
			$dados['chefia imediata'] = $dados['chefia_imediata'];
			unset($dados['chefia_imediata']);
		}

		if (isset($dados['city_job'])) {
			$dados['cidade do trabalho'] = $dados['city_job'];
			unset($dados['city_job']);
		}

		if (isset($dados['fim_job'])) {
			$dados['fim da jornada'] = $dados['fim_job'];
			unset($dados['fim_job']);
		}

		if (isset($dados['inicio_job'])) {
			$dados['início da jornada'] = $dados['inicio_job'];
			unset($dados['inicio_job']);
		}

		if (isset($dados['intervalo_job'])) {
			$dados['intervalo de almoço'] = $dados['intervalo_job'];
			unset($dados['intervalo_job']);
		}

		if (isset($dados['tempo_logado'])) {
			$dados['tempo logado'] = $dados['tempo_logado'];
			unset($dados['tempo_logado']);
		}

		if (isset($dados['conta_skype'])) {
			$dados['conta skype'] = $dados['conta_skype'];
			unset($dados['conta_skype']);
		}

		if (isset($dados['ctps'])) {
			$dados['carteira de trabalho'] = $dados['ctps'];
			unset($dados['ctps']);
		}

		if (isset($dados['ramal_telefone'])) {
			$dados['ramal'] = $dados['ramal_telefone'];
			unset($dados['ramal_telefone']);
		}
		if (isset($dados['data_saida_ferias'])) {
			$dados['data de saida de ferias'] = $dados['data_saida_ferias'];
			unset($dados['data_saida_ferias']);
		}

		if (isset($dados['data_retorno_ferias'])) {
			$dados['data de retorno de ferias'] = $dados['data_retorno_ferias'];
			unset($dados['data_retorno_ferias']);
		}

		if (isset($dados['status_bloqueio'])) {
			$dados['status do funcionario'] = $dados['status_bloqueio'];
			unset($dados['status_bloqueio']);
		}




		unset($dados['permissoes']);

		return $dados;
	}

	public function getUserByName($nome)
	{
		$this->db->select('id, nome');
		$this->db->from('usuario');
		$this->db->where('nome', $nome);
		$query = $this->db->get();
		if ($query->num_rows() > 0) return $query->result();
		return false;
	}

	public function getUserByLogin($login)
	{
		$this->db->select('id');
		$this->db->from('usuario');
		$this->db->where('login', $login);
		$query = $this->db->get();
		if ($query->num_rows() > 0) return $query->result();
		return false;
	}

	public function get_usuarios($search, $isAtivo = true)
	{

		$where = "(nome like '%" . $search . "%' or cpf = '" . $search . "' or id = '" . $search . "')";

		if ($isAtivo) {
			$where .= " and status = 1";
		}

		$script = "select nome, id";
		$script .= " from showtecsystem.usuario as u  where " . $where;

		$query = $this->db->query($script);
		$result = $query->result();
		return $result;
	}

	public function get_usuarioByID($id)
	{

		$this->db->select('nome');
		$this->db->from('showtecsystem.usuario');
		$this->db->where('id', $id);

		$query = $this->db->get();
		if ($query->num_rows() > 0) return $query->result();
		return false;
	}

	public function get_allUsuariosNomeEmail()
	{
		$this->db->select('login');
		$this->db->from('showtecsystem.usuario');
		$this->db->where('status', 1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) return $query->result();

		return false;
	}

	public function listarUsuarioPortal( $colunas = '*', $funcoes ) {
    return $this->db
      ->select($colunas)
      ->where(['status' => '1'])
			->where_in('funcao_portal', $funcoes)
      ->get('showtecsystem.usuario')
      ->result();
  }
}
