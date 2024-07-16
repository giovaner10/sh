<?php

date_default_timezone_set('America/Recife');

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cadastro extends CI_Model {

	public function __construct() {
		parent::__construct();
		
		//para registro de log
		$this->load->model('log_shownet');
		
	}

	public function verificar_cnpj_cliente($where) {

		$this->db->where($where);
		$query = $this->db->get('showtecsystem.cad_clientes');

		if ($query->num_rows() > 0)
			return $query->row();
		else
			return false;
	}

	public function add_modulo_permissao_lote($insert)
	{
		if (is_array($insert) && !empty($insert)) {
			return $this->db->insert_batch('showtecsystem.cad_modulo_permissao', $insert);
		} else {
			return false;
		}
	}

	public function get_permissoes_modulo($id_modulo, $select = 'id_permissao')
	{
		$this->db->select($select);
		$this->db->from('showtecsystem.cad_modulo_permissao');
		$this->db->join('showtecsystem.cad_permissoes', 'showtecsystem.cad_modulo_permissao.id_permissao = showtecsystem.cad_permissoes.id', 'left');
		$this->db->where(array('id_modulo' => $id_modulo, 'showtecsystem.cad_modulo_permissao.status' => '1', 'showtecsystem.cad_permissoes.status' => '1'));
		return $this->db->get()->result();
	}

	public function get_old_permissoes_modulo($id_modulo)
	{
		$this->db->select('id_permissao');
		$this->db->from('showtecsystem.cad_modulo_permissao');
		$this->db->where(array('id_modulo' => $id_modulo));
		return $this->db->get()->result();
	}

	public function get_modulo_permissao($id_modulo, $id_permissao)
	{
		$this->db->where(array('id_modulo' => $id_modulo, 'id_permissao' => $id_permissao));
		return $this->db->get('showtecsystem.cad_modulo_permissao')->result()[0];
	}

	public function edit_modulo_permissao($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_modulo_permissao', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function getPermissoes($where = false, $where_in = false) {
		if ($where && is_array($where))
			$this->db->where($where);
		if ($where_in && is_array($where_in))
			$this->db->where_in('cod_permissao', $where_in);

		return $this->db->get('showtecsystem.cad_permissoes')->result();
	}

	public function getPermissaoUser($id = false) {
		if (is_numeric($id))
			return $this->db->get_where('showtecsystem.usuario_gestor', array('code' => $id))->row();

		return false;
	}

	public function editPlano($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_planos', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function getPlanos($select = '*', $where = false, $order = false, $result = 'result') {
		if ($select && is_string($select))
			$this->db->select($select);
		if ($where && is_array($where))
			$this->db->where($where);
		if ($order && is_array($order))
			$this->db->order_by($order);

		$query = $this->db->get('showtecsystem.cad_planos');
		if ($result == 'row')
			return $query->row();

		return $query->result();
	}

	public function add_plano($data)
	{
		$this->db->insert('showtecsystem.cad_planos', $data);
		return $this->db->insert_id();
	}

	public function add_plano_modulo_lote($insert)
	{
		if (is_array($insert) && !empty($insert)) {
			return $this->db->insert_batch('showtecsystem.cad_plano_modulo', $insert);
		} else {
			return false;
		}
	}

	public function get_modulos_plano($where)
	{
		$this->db->select('id_modulo')->where($where);
		return $this->db->get('showtecsystem.cad_plano_modulo')->result();
	}

	public function get_plano_modulo($id_plano, $id_modulo)
	{
		$this->db->where(array('id_plano' => $id_plano, 'id_modulo' => $id_modulo));
		return $this->db->get('showtecsystem.cad_plano_modulo')->result()[0];
	}

	public function edit_plano_modulo($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_plano_modulo', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function editModulo($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_modulos', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function getModulos($select = '*', $where = false, $order = false, $result = 'result') {
		if ($select && is_string($select))
			$this->db->select($select);
		if ($where && is_array($where))
			$this->db->where($where);
		if ($order && is_array($order))
			$this->db->order_by($order);

		$query = $this->db->get('showtecsystem.cad_modulos');
		if ($result == 'row')
			return $query->row();

		return $query->result();
	}

	public function add_modulo($data)
	{
		$this->db->insert('showtecsystem.cad_modulos', $data);
		return $this->db->insert_id();

	}

	public function add_permissao($insert)
	{
		$this->db->insert('showtecsystem.cad_permissoes', $insert);
		return $this->db->insert_id();
	}

	public function edit_permissao($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_permissoes', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function edit_produto($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_produtos', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function get_produto($where) {
		return $this->db->get_where('showtecsystem.cad_produtos', $where)->row();
	}

	public function get_produtos($select = '*', $where = false, $order = false, $result = 'result') {
		if ($select && is_string($select))
			$this->db->select($select);
		if ($where && is_array($where))
			$this->db->where($where);
		if ($order && is_array($order))
			$this->db->order_by($order);

		$query = $this->db->get('showtecsystem.cad_produtos');
		if ($result == 'row')
			return $query->row();

		return $query->result();
	}

	public function add_produto($data)
	{
		$this->db->insert('showtecsystem.cad_produtos', $data);
		return $this->db->insert_id();

	}

	public function add_produto_plano_lote($insert)
	{
		if (is_array($insert) && !empty($insert)) {
			return $this->db->insert_batch('showtecsystem.cad_produto_plano', $insert);
		} else {
			return false;
		}
	}

	public function add_produto_permissao_lote($insert)
	{
		if (is_array($insert) && !empty($insert)) {
			return $this->db->insert_batch('showtecsystem.cad_produto_permissao', $insert);
		} else {
			return false;
		}
	}

	public function get_planos_produto($id_produto, $select = 'id_plano')
	{
		$this->db->select($select);
		$this->db->from('showtecsystem.cad_produto_plano');
		$this->db->join('showtecsystem.cad_planos', 'showtecsystem.cad_produto_plano.id_plano = showtecsystem.cad_planos.id', 'left');
		$this->db->where(array('id_produto' => $id_produto, 'showtecsystem.cad_produto_plano.status' => '1', 'showtecsystem.cad_planos.status' => '1'));
		return $this->db->get()->result();
	}

	public function get_permissoes_produto($id_produto, $select = 'id_permissao')
	{
		$this->db->select($select);
		$this->db->from('showtecsystem.cad_produto_permissao');
		$this->db->join('showtecsystem.cad_permissoes', 'showtecsystem.cad_produto_permissao.id_permissao = showtecsystem.cad_permissoes.id', 'left');
		$this->db->where(array('id_produto' => $id_produto, 'showtecsystem.cad_permissoes.status' => '1'));
		return $this->db->get()->result();
	}

	public function get_old_planos_produto($id_produto)
	{
		$this->db->select('id_plano');
		$this->db->from('showtecsystem.cad_produto_plano');
		$this->db->where(array('id_produto' => $id_produto));
		return $this->db->get()->result();
	}

	public function get_old_permissoes_produto($id_produto)
	{
		$this->db->select('id, id_permissao');
		$this->db->from('showtecsystem.cad_produto_permissao');
		$this->db->where(array('id_produto' => $id_produto));
		return $this->db->get()->result();
	}

	public function get_produto_plano($id_produto, $id_plano)
	{
		$this->db->where(array('id_produto' => $id_produto, 'id_plano' => $id_plano));
		return $this->db->get('showtecsystem.cad_produto_plano')->result()[0];
	}

	public function get_produto_permissao($id_produto, $id_permissao)
	{
		$this->db->where(array('id_produto' => $id_produto, 'id_permissao' => $id_permissao));
		return $this->db->get('showtecsystem.cad_produto_permissao')->result()[0];
	}

	public function edit_produto_plano($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_produto_plano', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function delete_produto_permissao($id_produto, $ids_produto_permissao)
	{
		if (!empty($ids_produto_permissao) && is_array($ids_produto_permissao)) {
			return $this->db->where('id_produto', $id_produto)
				->where_in('id_permissao', $ids_produto_permissao)
				->delete('showtecsystem.cad_produto_permissao');
		}

		return false;
	}

	public function get_planos_cliente($select = 'id_plano', $id_cliente)
	{
		$this->db->select($select);
		$this->db->from('showtecsystem.cad_cliente_plano');
		$this->db->join('showtecsystem.cad_planos', 'showtecsystem.cad_cliente_plano.id_plano = showtecsystem.cad_planos.id', 'left');
		$this->db->where(array('id_cliente' => $id_cliente, 'showtecsystem.cad_cliente_plano.status' => '1', 'showtecsystem.cad_planos.status' => '1'));
		return $this->db->get()->result();
	}

	public function get_old_planos_cliente($id_cliente)
	{
		$this->db->select('id_plano');
		$this->db->from('showtecsystem.cad_cliente_plano');
		$this->db->where(array('id_cliente' => $id_cliente));
		return $this->db->get()->result();
	}

	public function get_cliente_plano($id_cliente, $id_plano)
	{
		$this->db->where(array('id_cliente' => $id_cliente, 'id_plano' => $id_plano));
		return $this->db->get('showtecsystem.cad_cliente_plano')->result()[0];
	}
	
	public function atualizar_permissoes_lote($id_clientes, $dados){
		if (is_array($id_clientes) && !empty($id_clientes) && is_array($dados) && !empty($dados)) {
			$this->db->where_in('id', $id_clientes);
			return $this->db->update('showtecsystem.cad_clientes', $dados);
		} else {
			return false;
		}

	}
	
	// public function update_permissoes_cliente_lote($update)
	// {
	// 	if (is_array($update) && !empty($update)) {
	// 		return $this->db->update_batch('showtecsystem.cad_clientes', $update, 'id');
	// 	} else {
	// 		return false;
	// 	}
	// }

	public function edit_cliente_plano($id, $data)
	{
		if ($id && is_numeric($id)) {
			if ($data && is_array($data)) {
				return $this->db->update('showtecsystem.cad_cliente_plano', $data, array('id' => $id));
			}
		}

		return false;
	}

	public function add_cliente_plano_lote($insert)
	{
		if (is_array($insert) && !empty($insert)) {
			return $this->db->insert_batch('showtecsystem.cad_cliente_plano', $insert);
		} else {
			return false;
		}
	}

	public function getPermissoesProdutoOptions($id_produto, $permissoes_cliente, $planos_cliente_ids = false, $readonly = false){
		$permissoes = [];

		$soLeitura = $readonly ? 'readonly' : ' ';

		$planos = $this->get_planos_produto($id_produto, 'id_plano, nome, editavel');

		foreach ($planos as $plano) {
			$this->db->select('id_modulo, showtecsystem.cad_modulos.nome');
			$this->db->from('showtecsystem.cad_plano_modulo');
			$this->db->join('showtecsystem.cad_modulos', 'showtecsystem.cad_plano_modulo.id_modulo = showtecsystem.cad_modulos.id', 'left');
			$this->db->where(array('id_plano' => $plano->id_plano, 'showtecsystem.cad_plano_modulo.status' => '1', 'showtecsystem.cad_modulos.status' => '1'));
			$modulos =  $this->db->get()->result();

			//planos do cliente com opções selecionadas
			$selected = is_array($planos_cliente_ids) && in_array($plano->id_plano, $planos_cliente_ids) ? 'selected' : ' ';

			foreach ($modulos as $modulo) {

				$perm = $this->get_permissoes_modulo($modulo->id_modulo,'*');

				foreach ($perm as $p) {
					if($plano->editavel == '1'){
						if(is_array($permissoes_cliente) && in_array($p->cod_permissao, $permissoes_cliente)){
							$permissoes[] = '<option data-editavel="'.$plano->editavel.'" value="'.$p->cod_permissao.'" class="adt" '.$selected.' '.$soLeitura.' data-section="'.$plano->nome.'/'.$modulo->nome.'">'.$p->descricao.'</option>';
						}else{
							$permissoes[] = '<option data-editavel="'.$plano->editavel.'" value="'.$p->cod_permissao.'" class="adt" '.$soLeitura.' data-section="'.$plano->nome.'/'.$modulo->nome.'">'.$p->descricao.'</option>';
						}
					}else{
						$permissoes[] = '<option data-editavel="'.$plano->editavel.'" value="'.$p->cod_permissao.'" class="adt" '.$selected.' '.$soLeitura.' data-section="'.$plano->nome.'/'.$modulo->nome.'">'.$p->descricao.'</option>';
					}
				}
			}
		}

		return $permissoes;
	}

	public function getPermissoesModulosOptions($permissoes_cliente = false, $readonly = false){
		$permissoes = [];
		$soLeitura = $readonly ? 'readonly' : ' ';

		$modulos = $this->getModulos('id, nome', array('status' => '1'));

		foreach ($modulos as $modulo) {

			$perm = $this->get_permissoes_modulo($modulo->id, '*');

			foreach ($perm as $p) {
				if(is_array($permissoes_cliente) && in_array($p->cod_permissao, $permissoes_cliente)){
					$permissoes[] = '<option value="'.$p->cod_permissao.'" class="adt" selected '.$soLeitura.' data-section="'.$modulo->nome.'">'.$p->descricao.'</option>';
				}else{
					$permissoes[] = '<option value="'.$p->cod_permissao.'" class="adt" '.$soLeitura.' data-section="'.$modulo->nome.'">'.$p->descricao.'</option>';
				}
			}
		}

		return $permissoes;
	}

	public function getPermissoesModulosOptionsExtras($permissoes_cliente = false, $id_produto, $planos_cliente_ids, $readonly = false){
		$permissoes = [];

		$planos_produto = $this->get_planos_produto($id_produto, 'id_plano');

		if($id_produto && $planos_produto && is_array($planos_cliente_ids)){
			$soLeitura = $readonly ? 'readonly' : ' ';

			$modulos = $this->getModulos('id, nome', array('status' => '1'));

			$modulos_ids = [];
			foreach ($planos_cliente_ids as $id_plano) {
				$modulosPlano = $this->get_modulos_plano(array('id_plano' => $id_plano, 'status' => '1'));

				foreach ($modulosPlano as $mod) {
					if(!in_array($mod->id_modulo, $modulos_ids)){
						array_push($modulos_ids, $mod->id_modulo);
					}
				}
			}

			foreach ($modulos as $modulo) {

				$perm = $this->get_permissoes_modulo($modulo->id, '*');

				$selected = in_array($modulo->id, $modulos_ids) ? ' ' : 'selected';

				foreach ($perm as $p) {
					if(is_array($permissoes_cliente) && in_array($p->cod_permissao, $permissoes_cliente)){
						$permissoes[] = '<option value="'.$p->cod_permissao.'" class="adt" '.$selected.' '.$soLeitura.' data-section="'.$modulo->nome.'">'.$p->descricao.'</option>';
					}else{
						$permissoes[] = '<option value="'.$p->cod_permissao.'" class="adt" '.$soLeitura.' data-section="'.$modulo->nome.'">'.$p->descricao.'</option>';
					}
				}
			}

			return $permissoes;
		}else{
			$permissoes = $this->getPermissoesModulosOptions($permissoes_cliente, $readonly);
			return $permissoes;
		}

	}

	public function sanitizeString($str) {
		$str = preg_replace('/[áàãâä]/ui', 'a', $str);
		$str = preg_replace('/[éèêë]/ui', 'e', $str);
		$str = preg_replace('/[íìîï]/ui', 'i', $str);
		$str = preg_replace('/[óòõôö]/ui', 'o', $str);
		$str = preg_replace('/[úùûü]/ui', 'u', $str);
		$str = preg_replace('/[ç]/ui', 'c', $str);
		// $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
		$str = preg_replace('/[^a-z0-9]/i', '_', $str);
		$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
		return $str;
	}

	public function cadastrar_cliente($cliente, $cartao, $endereco, $email, $telefone) {

		$this->load->model('log_shownet');
		//para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;


		if ($cliente['pessoa'] == 1)
	        $nome = $this->verificar_cnpj_cliente(array('cpf' => $cliente['cpf']));
		else
            $nome = $this->verificar_cnpj_cliente(array('cnpj' => $cliente['cnpj']));

		// receber valor, veio do site
		if ($nome) {
			$status = 1;
			return array('id_cliente' => $nome->id, 'status' => $status, 'nome_cliente' => $nome->nome);
		} else {
			if ($cliente['pessoa'] == 1) {

				//Cliente
				$cliente_nome = strtoupper($cliente['nome']);
				$cliente_rg = isset($cliente['rg']) ? $cliente['rg'] : '';
                $end = isset($endereco['pais']) ? strtoupper($endereco['pais']) : 'BRA';
				$cliente_rg_orgao = isset($cliente['rg_orgao']) ? strtoupper($cliente['rg_orgao']) : '';
				$cliente_cpf = $cliente['cpf'];
				$cliente_informacoes = strtoupper($cliente['informacoes']);

				// if ($this->$cliente_site != null) {
				// 	$cliente_status = 5;
				// 	//$cliente_site = $cliente_site;
				// } else {
				// $cliente_status = 2;
				// 	$cliente_site = 0;
				// }

				if ($cliente['informacoes'] == 'SEGURADORA') {
					$cliente_status = 1;
				} else {
					$cliente_status = 2;
				}

				$data_cadastro = date('Y-m-d H:i:s');
				$data_nascimento = $cliente['data_nascimento'];

				$dados_cliente = array(
					'nome' => $cliente_nome,
                    'id_vendedor' => isset($cliente['id_vendedor']) ? $cliente['id_vendedor'] : 1,
					'informacoes' => $cliente_informacoes,
					'cpf' => $cliente_cpf,
					'identidade' => $cliente_rg,
					'orgaoexp' => $cliente_rg_orgao,
					'status' => $cliente_status,
					'data_cadastro' => $data_cadastro,
					'data_nascimento' => $data_nascimento,
					'consultor_m2m' => isset($cliente['consultor']) ? $cliente['consultor'] : NULL,
					'pais' => $end,
					'cod_servico' => isset($cliente['cod_servico']) ? $cliente['cod_servico'] : NULL,
					'descriminacao_servico' => isset($cliente['descriminacao_servico']) ? $cliente['descriminacao_servico'] : NULL,
					'troca_periodica_senhas' => 0,
					'gmt' => $cliente['gmt']
				);
				$resposta_cliente = $this->db->insert('showtecsystem.cad_clientes', $dados_cliente);
				$cliente_id = $this->db->insert_id();

				//registra o log 
				$result = $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente_id, 'criar', 'null', $dados_cliente);



			} else {
				//Cliente
				$cliente_nome = strtoupper($cliente['nome']);
				$cliente_razao = strtoupper($cliente['razao_social']);
                $end = isset($endereco['pais']) ? strtoupper($endereco['pais']) : 'BRA';
				$cliente_cnpj = $cliente['cnpj'];
				$cliente_ie = $cliente['ie'];
				$cliente_informacoes = strtoupper($cliente['informacoes']);

				// if ($this->$cliente_site != null) {
				// 	$cliente_status = 5;
				// 	$cliente_site = $cliente_site;
				// } else {
				// $cliente_status = 2;
				// 	$cliente_site = 0;
				// }

				if ($cliente['informacoes'] == 'SEGURADORA') {
					$cliente_status = 1;
				} else {
					$cliente_status = 2;
				}

				$data_cadastro = date('Y-m-d H:i:s');

				$dados_cliente = array(
					'nome' => $cliente_nome,
                    'id_vendedor' => isset($cliente['id_vendedor']) ? $cliente['id_vendedor'] : 1,
					'informacoes' => $cliente_informacoes,
					'cnpj' => $cliente_cnpj,
					'razao_social' => $cliente_razao,
					'inscricao_estadual' => $cliente_ie,
					'status' => $cliente_status,
					'data_cadastro' => $data_cadastro,
                    'consultor_m2m' => isset($cliente['consultor']) ? $cliente['consultor'] : NULL,
				    'pais' => $end,
					'cod_servico' => isset($cliente['cod_servico']) ? $cliente['cod_servico'] : NULL,
					'descriminacao_servico' => isset($cliente['descriminacao_servico']) ? $cliente['descriminacao_servico'] : NULL,
					'orgao' => isset($cliente['orgao']) ? $cliente['orgao'] : NULL,
					'troca_periodica_senhas' => 0,
					'gmt' => $cliente['gmt']
				//	'cad_site' => $cliente_site
				);

				$resposta_cliente = $this->db->insert('showtecsystem.cad_clientes', $dados_cliente);
				$cliente_id = $this->db->insert_id();

				//registra o log 
				$result2 = $this->log_shownet->gravar_log($id_user, 'cad_clientes', $cliente_id, 'criar', 'null', $dados_cliente);
			}


			//Cartão
			$quant_cartao = count($cartao);

			for ($i = 0; $i < $quant_cartao; $i++)
			{
				if ($cartao[$i]['numero'] && strlen(preg_replace('/[^\d]/', '', $cartao[$i]['numero'])) > 0 &&
					$cartao[$i]['vencimento'] && strlen(preg_replace('/[^\d]/', '', $cartao[$i]['vencimento'])) > 0)
				{
					$cartao_numero = $cartao[$i]['numero'];
					$cartao_bandeira = strtoupper($cartao[$i]['bandeira']);

					$data = $cartao[$i]['vencimento'];
					$data_venci = explode("/", $data);
					$cartao_vencimento = "20" . $data_venci[1] . "-" . $data_venci[0] . "-01";

					$cartao_codigo = $cartao[$i]['codigo'];
					$cartao_nome = $cartao[$i]['nome'];
					$cartao_data_criado = date('Y-m-d');
					$cartao_hora_criado = date('H:i:s');

					$dados_cartao = array(
						'numero' => $cartao_numero,
						'bandeira' => $cartao_bandeira,
						'vencimento' => $cartao_vencimento,
						'codigo' => $cartao_codigo,
						'nome' => $cartao_nome,
						'data_criado' => $cartao_data_criado,
						'hora_criado' => $cartao_hora_criado,
						'cliente_id' => $cliente_id
					);

					$resposta_cartao = $this->db->insert('showtecsystem.clientes_cartoes', $dados_cartao);
					$cartoes_id = $this->db->insert_id();

					//registra o log 
					$result3 = $this->log_shownet->gravar_log($id_user, 'clientes_cartoes', $cartoes_id, 'criar', 'null', $dados_cartao);
				}
			}

			//Endereço
			$quant_endereco = count($endereco);
			for ($i = 0; $i < $quant_endereco; $i++) {

				$endereco_latitude = $endereco[$i]['latitude'];
				$endereco_longitude = $endereco[$i]['longitude'];
				$endereco_cep = $endereco[$i]['cep'];
				$endereco_rua = strtoupper($endereco[$i]['rua']);
				$endereco_numero = $endereco[$i]['numero'];
				$endereco_bairro = strtoupper($endereco[$i]['bairro']);
				$endereco_uf = $endereco[$i]['uf'];
				$endereco_cidade = strtoupper($endereco[$i]['cidade']);
				$endereco_complemento = strtoupper($endereco[$i]['complemento']);
				$endereco_data_criado = date('Y-m-d');
				$endereco_hora_criado = date('H:i:s');

				$dados_endereco = array(
					'rua' => $endereco_rua,
					'numero' => $endereco_numero,
					'bairro' => $endereco_bairro,
					'complemento' => $endereco_complemento,
					'cep' => $endereco_cep,
					'cidade' => $endereco_cidade,
					'uf' => $endereco_uf,
					'latitude' => $endereco_latitude,
					'longitude' => $endereco_longitude,
					'data_criado' => $endereco_data_criado,
					'hora_criado' => $endereco_hora_criado,
					'cliente_id' => $cliente_id,
                    'pais' => $end
				);

				$resposta_endereco = $this->db->insert('showtecsystem.clientes_enderecos', $dados_endereco);
				$endereco_id = $this->db->insert_id();

				//registra o log 
				$result4 = $this->log_shownet->gravar_log($id_user, 'clientes_enderecos', $endereco_id, 'criar', 'null', $dados_endereco);

				if ($i == 0) {

					$dados_endereco_cli = array(
						'endereco' => $endereco_rua,
						'numero' => $endereco_numero,
						'bairro' => $endereco_bairro,
						'complemento' => $endereco_complemento,
						'cep' => $endereco_cep,
						'cidade' => $endereco_cidade,
						'uf' => $endereco_uf,
						'latitude' => $endereco_latitude,
						'longitude' => $endereco_longitude,
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_endereco_cli);
				}
			}
			//Contatos - Email
			$quant_email = count($email);

			for ($i = 0; $i < $quant_email; $i++) {

				$email_email = strtoupper($email[$i]['emails']);
				$email_setor = $email[$i]['setor'];
				$email_observacao = strtoupper($email[$i]['observacao']);
				$email_data_criado = date('Y-m-d');
				$email_hora_criado = date('H:i:s');

				$dados_email = array(
					'email' => $email_email,
					'observacao' => $email_observacao,
					'setor' => $email_setor,
					'data_criado' => $email_data_criado,
					'hora_criado' => $email_hora_criado,
					'cliente_id' => $cliente_id
				);

			   $resposta_email = $this->db->insert('showtecsystem.clientes_emails', $dados_email);
			   $email_id = $this->db->insert_id();

				//registra o log 
				$result5 = $this->log_shownet->gravar_log($id_user, 'clientes_emails', $email_id, 'criar', 'null', $dados_email);


				if ($i == 0) {

					$dados_email_cli = array(
						'email' => $email_email
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_email_cli);
				} else if ($i == 1) {

					$dados_email_cli = array(
						'email2' => $email_email
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_email_cli);
				}

				if ($email_setor == 0) {

					$dados_email_cli2 = array(
						'email2' => $email_email
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_email_cli2);
				}
			}
			//Contatos - Telefone
			$quant_telefone = count($telefone);

			for ($i = 0; $i < $quant_telefone; $i++) {

				$telefone_ddd = $telefone[$i]['ddd'];
				$telefone_numero = $telefone[$i]['numero'];
				$telefone_setor = isset($telefone[$i]['setor']) ? $telefone[$i]['setor'] : '';
				$telefone_observacao = strtoupper($telefone[$i]['observacao']);
				$telefone_data_criado = date('Y-m-d');
				$telefone_hora_criado = date('H:i:s');

				$dados_telefone = array(
					'ddd' => $telefone_ddd,
					'numero' => $telefone_numero,
					'setor' => $telefone_setor,
					'observacao' => $telefone_observacao,
					'data_criado' => $telefone_data_criado,
					'hora_criado' => $telefone_hora_criado,
					'cliente_id' => $cliente_id
				);

				$resposta_telefone = $this->db->insert('showtecsystem.clientes_telefones', $dados_telefone);
				$telefone_id = $this->db->insert_id();

				//registra o log 
				$result6 = $this->log_shownet->gravar_log($id_user, 'clientes_telefones', $telefone_id, 'criar', 'null', $dados_telefone);	

				if ($i == 0) {

					$telefone_cli = "(" . $telefone_ddd . ") " . $telefone_numero;

					$dados_telefone_cli = array(
						'fone' => $telefone_cli
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_telefone_cli);
				}
			}

			if ($resposta_cliente && $resposta_endereco && $resposta_email && $resposta_telefone) {
			    $this->cad_grupo_byMaster($cliente_id);
			    $status = 2;
				return array('id_cliente' => $cliente_id, 'status' => $status, 'nome_cliente' => $cliente_nome, 'id_cliente' => $cliente_id);
			}
		}
	}

	public function getPermissoesCliente($id) {
		$query = $this->db->select('permissoes')->get_where('showtecsystem.cad_clientes', array('id' => $id))->row();
		if ($query)
			return $query->permissoes;
		return array();
	}

	public function atualizar_dados($cliente_id, $dados){
        $this->db->where('id', $cliente_id);
        return $this->db->update('showtecsystem.cad_clientes', $dados);
	}

	public function atualizar_endereco($cliente_id, $endereco, $id = false, $flag = false){
	    if ($id) {

	        unset($endereco['cliente_id']);
            unset($endereco['data_criado']);
            unset($endereco['hora_criado']);

            $this->db->where('id', $id);
            $retorno = $this->db->update('showtecsystem.clientes_enderecos', $endereco);

            if ($flag == 1) {
                unset($endereco['data_modificado']);
                unset($endereco['hora_modificado']);
                $endereco['endereco'] = $endereco['rua'];
                unset($endereco['rua']);

                $this->db->where('id', $cliente_id);
                $retorno = $this->db->update('showtecsystem.cad_clientes', $endereco);
            }

            return $retorno;
        }else{
            unset($endereco['data_modificado']);
            unset($endereco['hora_modificado']);

            $retorno = $this->db->insert('showtecsystem.clientes_enderecos', $endereco);
            if ($flag == 1){
                unset($endereco['cliente_id']);
                unset($endereco['data_criado']);
                unset($endereco['hora_criado']);

                $endereco['endereco'] = $endereco['rua'];
                unset($endereco['rua']);

                $this->db->where('id', $cliente_id);
                $retorno = $this->db->update('showtecsystem.cad_clientes', $endereco);
            }
            return $retorno;
        }
    }

	public function getDadosCartao($where) {
		return $this->db->get_where('showtecsystem.clientes_cartoes', $where)->row();
	}
	
    public function atualizar_cartao($cartao, $id_cartao = null){
	    if ($id_cartao) {
            $this->db->where('id', $id_cartao);
            return $this->db->update('showtecsystem.clientes_cartoes', $cartao);
        }else{
            return $this->db->insert('showtecsystem.clientes_cartoes', $cartao);
        }
    }

    public function atualizar_impostos($cliente_id, $impostos){
        $this->db->where('id', $cliente_id);
        return $this->db->update('showtecsystem.cad_clientes', $impostos);
    }

	public function getDadosImpostos($where){
		return $this->db->get_where('showtecsystem.cad_clientes', $where)->row();       
    }

    public function atualizar_permissoes($cliente_id, $dados){
        $this->db->where('id', $cliente_id);
        return $this->db->update('showtecsystem.cad_clientes', $dados);
    }

    public function integracao_linker($cliente_id, $dados){
        $this->db->where('id', $cliente_id);
        return $this->db->update('showtecsystem.cad_clientes', $dados);
	}
	
	public function getDadosIntegracaoLinker($where){       
		return $this->db->get_where('showtecsystem.cad_clientes', $where)->row();
	}


	/**
	 * Esta função faz com que seja habilitado a troca periódica de senha para o cliente.
	 * Onde os usuários pertencentes a ele deve atualizar a senha a cada 60 dias
	 */
    public function forcar_atualizacao_senha($cliente_id, $dados){
        $this->db->where('id', $cliente_id);
        return $this->db->update('showtecsystem.cad_clientes', $dados);
    }

	public function getDadosForcarAtualizacaoSenha($where){       
		return $this->db->get_where('showtecsystem.cad_clientes', $where)->row();
	}

	public function atualizar_cliente($cliente_id, $cliente, $cartao, $endereco, $email, $telefone, $consultor, $vendedor,
        $irpj, $cont_social, $pis, $cofins, $iss,$opentech = 0, $excessoVia = 0) {
		
			var_dump($cliente);
			exit;
		//Cliente
		$cliente_pessoa = $cliente['pessoa'];

		if ($cliente_pessoa == 1) {
			$cliente_nome = strtoupper($cliente['nome']);
			$cliente_rg = $cliente['rg'];
			$cliente_rg_orgao = strtoupper($cliente['rg_orgao']);
			$cliente_cpf = $cliente['cpf'];
			$cliente_informacoes = strtoupper($cliente['informacoes']);
			$cliente_orgao = $cliente['orgao'];
			$data_nascimento = date('d/m/Y',$cliente['data_nascimento']);

			$dados_cliente = array(
				'nome' => $cliente_nome,
				'cpf' => $cliente_cpf,
				'identidade' => $cliente_rg,
				'orgaoexp' => $cliente_rg_orgao,
				'data_nascimento'=>$data_nascimento,
				'informacoes' => $cliente_informacoes,
                'consultor_m2m' => $consultor,
                'id_vendedor' => $vendedor,
                'IRPJ' => $irpj,
                'Cont_Social' => $cont_social,
                'PIS' => $pis,
                'COFINS' => $cofins,
				'ISS' => $iss,
				'cod_servico' => $cliente['cod_servico'],
				'descriminacao_servico' => $cliente['descriminacao_servico'],
				'opentech' => $opentech,
				'velocidade_via' => $excessoVia,
				'orgao' => $cliente_orgao,
				'permissoes' => $cliente['permissoes'],
				'id_produto' => $cliente['id_produto'],
				'observacoes' => $cliente['observacoes']
			);

			$this->db->where('id', $cliente_id);

			$this->db->update('showtecsystem.cad_clientes', $dados_cliente);
		} else {
			$cliente_nome = strtoupper($cliente['nome']);
			$cliente_razao = strtoupper($cliente['razao_social']);
			$cliente_cnpj = $cliente['cnpj'];
			$cliente_ie = $cliente['ie'];
			$cliente_informacoes = strtoupper($cliente['informacoes']);
			$cliente_orgao = $cliente['orgao'];

			$dados_cliente = array(
				'nome' => $cliente_nome,
				'informacoes' => $cliente_informacoes,
				'cnpj' => $cliente_cnpj,
				'razao_social' => $cliente_razao,
				'inscricao_estadual' => $cliente_ie,
                'consultor_m2m' => $consultor,
                'id_vendedor' => $vendedor,
                'IRPJ' => $irpj,
                'Cont_Social' => $cont_social,
                'PIS' => $pis,
                'COFINS' => $cofins,
                'ISS' => $iss,
				'cod_servico' => $cliente['cod_servico'],
                'descriminacao_servico' => $cliente['descriminacao_servico'],
				'opentech' => $opentech,
				'velocidade_via' => $excessoVia,
				'orgao' => $cliente_orgao,
				'permissoes' => $cliente['permissoes'],
				'id_produto' => $cliente['id_produto'],
				'observacoes' => $cliente['observacoes']
			);

			$this->db->where('id', $cliente_id);
			$this->db->update('showtecsystem.cad_clientes', $dados_cliente);
		}

		//Planos
		$planos = [];
		foreach($cliente['planos_nomes'] as $plano_nome){
			$plano = $this->getPlanos('id', array('status' => '1', 'nome' => $plano_nome))[0];
			array_push($planos, $plano->id);
		}

		$planos_cliente_old = $this->get_old_planos_cliente($cliente_id);

		foreach ($planos_cliente_old as $plano) {
			if (($key = array_search($plano->id_plano, $planos)) !== false) {
				$cliente_plano = $this->get_cliente_plano($cliente_id, $plano->id_plano);
				$this->edit_cliente_plano($cliente_plano->id, array('status' => '1'));
				unset($planos[$key]);
			}else{
				$cliente_plano = $this->get_cliente_plano($cliente_id, $plano->id_plano);
				$this->edit_cliente_plano($cliente_plano->id, array('status' => '0'));
			}
		}

		$insert_batch = [];
		foreach ($planos as $id_plano) {
			$insert_batch[] = array(
				'id_plano' => $id_plano,
				'id_cliente' => $cliente_id,
			);
		}

		if($insert_batch){
			$this->add_cliente_plano_lote($insert_batch);
		}

		//Cartão
		$quant_cartao = count($cartao);

		for ($i = 0; $i < $quant_cartao; $i++) {

			if ($cartao[$i]['numero']) {

				if ($cartao[$i]['status'] == 1) {

					$cartao_id = $cartao[$i]['id'];
					$cartao_numero = $cartao[$i]['numero'];
					$cartao_bandeira = strtoupper($cartao[$i]['bandeira']);

					$data = $cartao[$i]['vencimento'];
					$data_venci = explode("/", $data);
					$cartao_vencimento = "20" . $data_venci[1] . "-" . $data_venci[0] . "-01";

					$cartao_codigo = $cartao[$i]['codigo'];
					$cartao_nome = $cartao[$i]['nome'];
					$cartao_data_modificado = date('Y-m-d');
					$cartao_hora_modificado = date('H:i:s');

					$dados_cartao = array(
						'numero' => $cartao_numero,
						'bandeira' => $cartao_bandeira,
						'vencimento' => $cartao_vencimento,
						'codigo' => $cartao_codigo,
						'nome' => $cartao_nome,
						'data_modificado' => $cartao_data_modificado,
						'hora_modificado' => $cartao_hora_modificado
					);

					$this->db->where('id', $cartao_id);
					$this->db->update('showtecsystem.clientes_cartoes', $dados_cartao);
				} else {

					$cartao_numero = $cartao[$i]['numero'];
					$cartao_bandeira = strtoupper($cartao[$i]['bandeira']);

					$data = $cartao[$i]['vencimento'];
					$data_venci = explode("/", $data);
					$cartao_vencimento = "20" . $data_venci[1] . "-" . $data_venci[0] . "-01";

					$cartao_codigo = $cartao[$i]['codigo'];
					$cartao_nome = $cartao[$i]['nome'];
					$cartao_data_criado = date('Y-m-d');
					$cartao_hora_criado = date('H:i:s');

					$dados_cartao = array(
						'numero' => $cartao_numero,
						'bandeira' => $cartao_bandeira,
						'vencimento' => $cartao_vencimento,
						'codigo' => $cartao_codigo,
						'nome' => $cartao_nome,
						'data_criado' => $cartao_data_criado,
						'hora_criado' => $cartao_hora_criado,
						'cliente_id' => $cliente_id
					);

					$resposta_cartao = $this->db->insert('showtecsystem.clientes_cartoes', $dados_cartao);
				}
			}
		}


		//Endereço
		$quant_endereco = count($endereco);

		for ($i = 0; $i < $quant_endereco; $i++) {

			if ($endereco[$i]['status'] == 1) {

				$endereco_id = $endereco[$i]['id'];
				$endereco_latitude = $endereco[$i]['latitude'];
				$endereco_longitude = $endereco[$i]['longitude'];
				$endereco_cep = $endereco[$i]['cep'];
				$endereco_rua = strtoupper($endereco[$i]['rua']);
				$endereco_numero = $endereco[$i]['numero'];
				$endereco_bairro = strtoupper($endereco[$i]['bairro']);
				$endereco_uf = $endereco[$i]['uf'];
				$endereco_cidade = strtoupper($endereco[$i]['cidade']);
				$endereco_complemento = strtoupper($endereco[$i]['complemento']);
				$endereco_data_modificado = date('Y-m-d');
				$endereco_hora_modificado = date('H:i:s');

				$dados_endereco = array(
					'rua' => $endereco_rua,
					'numero' => $endereco_numero,
					'bairro' => $endereco_bairro,
					'complemento' => $endereco_complemento,
					'cep' => $endereco_cep,
					'cidade' => $endereco_cidade,
					'uf' => $endereco_uf,
					'latitude' => $endereco_latitude,
					'longitude' => $endereco_longitude,
					'data_modificado' => $endereco_data_modificado,
					'hora_modificado' => $endereco_hora_modificado
				);

				$this->db->where('id', $endereco_id);
				$this->db->update('showtecsystem.clientes_enderecos', $dados_endereco);

				if ($i == 0) {

					$dados_endereco_cli = array(
						'endereco' => $endereco_rua,
						'numero' => $endereco_numero,
						'bairro' => $endereco_bairro,
						'complemento' => $endereco_complemento,
						'cep' => $endereco_cep,
						'cidade' => $endereco_cidade,
						'uf' => $endereco_uf,
						'latitude' => $endereco_latitude,
						'longitude' => $endereco_longitude
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_endereco_cli);
				}
			} else {

				$endereco_latitude = $endereco[$i]['latitude'];
				$endereco_longitude = $endereco[$i]['longitude'];
				$endereco_cep = $endereco[$i]['cep'];
				$endereco_rua = strtoupper($endereco[$i]['rua']);
				$endereco_numero = $endereco[$i]['numero'];
				$endereco_bairro = strtoupper($endereco[$i]['bairro']);
				$endereco_uf = $endereco[$i]['uf'];
				$endereco_cidade = strtoupper($endereco[$i]['cidade']);
				$endereco_complemento = strtoupper($endereco[$i]['complemento']);
				$endereco_data_criado = date('Y-m-d');
				$endereco_hora_criado = date('H:i:s');

				$dados_endereco = array(
					'rua' => $endereco_rua,
					'numero' => $endereco_numero,
					'bairro' => $endereco_bairro,
					'complemento' => $endereco_complemento,
					'cep' => $endereco_cep,
					'cidade' => $endereco_cidade,
					'uf' => $endereco_uf,
					'latitude' => $endereco_latitude,
					'longitude' => $endereco_longitude,
					'data_criado' => $endereco_data_criado,
					'hora_criado' => $endereco_hora_criado,
					'cliente_id' => $cliente_id
				);

				$resposta_endereco = $this->db->insert('showtecsystem.clientes_enderecos', $dados_endereco);

				if ($i == 0) {

					$dados_endereco_cli = array(
						'endereco' => $endereco_rua,
						'numero' => $endereco_numero,
						'bairro' => $endereco_bairro,
						'complemento' => $endereco_complemento,
						'cep' => $endereco_cep,
						'cidade' => $endereco_cidade,
						'uf' => $endereco_uf,
						'latitude' => $endereco_latitude,
						'longitude' => $endereco_longitude
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_endereco_cli);
				}
			}
		}

		//Contatos - Email

		$quant_email = count($email);

		for ($i = 0; $i < $quant_email; $i++) {

			if ($i == 0) {
				$email_verif = true;
			} else {
				$email_verif = false;
			}

			if ($email[$i]['status'] == 1) {

				$email_id = $email[$i]['id'];
				$email_email = strtoupper($email[$i]['emails']);
				$email_setor = $email[$i]['setor'];
				$email_observacao = strtoupper($email[$i]['observacao']);
				$email_data_modificado = date('Y-m-d');
				$email_hora_modificado = date('H:i:s');

				$dados_email = array(
					'email' => $email_email,
					'observacao' => $email_observacao,
					'setor' => $email_setor,
					'data_modificado' => $email_data_modificado,
					'hora_modificado' => $email_hora_modificado
				);

				$this->db->where('id', $email_id);
				$this->db->update('showtecsystem.clientes_emails', $dados_email);

				if ($i == 0) {

					$dados_email_cli = array(
						'email' => $email_email
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_email_cli);
				}

				if ($email_setor == 0) {

					$dados_email_cli2 = array(
						'email2' => $email_email
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_email_cli2);
				}
			} else {

				$email_email = strtoupper($email[$i]['emails']);
				$email_setor = $email[$i]['setor'];
				$email_observacao = strtoupper($email[$i]['observacao']);
				$email_data_criado = date('Y-m-d');
				$email_hora_criado = date('H:i:s');

				$dados_email = array(
					'email' => $email_email,
					'observacao' => $email_observacao,
					'setor' => $email_setor,
					'data_criado' => $email_data_criado,
					'hora_criado' => $email_hora_criado,
					'cliente_id' => $cliente_id
				);

				$resposta_email = $this->db->insert('showtecsystem.clientes_emails', $dados_email);

				if ($i == 0 && $email_verif == true) {

					$dados_email_cli = array(
						'email' => $email_email
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_email_cli);
				}

				if ($email_setor == 0 && $email_verif == true) {

					$dados_email_cli3 = array(
						'email2' => $email_email
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_email_cli3);
				}
			}
		}
		// //Contatos - Telefone

		$quant_telefone = count($telefone);

		for ($i = 0; $i < $quant_telefone; $i++) {

			if ($i == 0) {
				$telefone_verif = true;
			} else {
				$telefone_verif = false;
			}

			if ($telefone[$i]['status'] == 1) {

				$telefone_id = $telefone[$i]['id'];
				$telefone_ddd = $telefone[$i]['ddd'];
				$telefone_numero = $telefone[$i]['numero'];
				$telefone_setor = $telefone[$i]['setor'];
				$telefone_observacao = strtoupper($telefone[$i]['observacao']);
				$telefone_data_modificado = date('Y-m-d');
				$telefone_hora_modificado = date('H:i:s');

				$dados_telefone = array(
					'ddd' => $telefone_ddd,
					'numero' => $telefone_numero,
					'setor' => $telefone_setor,
					'observacao' => $telefone_observacao,
					'data_modificado' => $telefone_data_modificado,
					'hora_modificado' => $telefone_hora_modificado
				);

				$this->db->where('id', $telefone_id);
				$this->db->update('showtecsystem.clientes_telefones', $dados_telefone);

				if ($i == 0) {

					$telefone_cli = "(" . $telefone_ddd . ") " . $telefone_numero;

					$dados_telefone_cli = array(
						'fone' => $telefone_cli
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_telefone_cli);
				}
			} else {


				$telefone_ddd = $telefone[$i]['ddd'];
				$telefone_numero = $telefone[$i]['numero'];
				$telefone_setor = $telefone[$i]['setor'];
				$telefone_observacao = strtoupper($telefone[$i]['observacao']);
				$telefone_data_criado = date('Y-m-d');
				$telefone_hora_criado = date('H:i:s');

				$dados_telefone = array(
					'ddd' => $telefone_ddd,
					'numero' => $telefone_numero,
					'setor' => $telefone_setor,
					'observacao' => $telefone_observacao,
					'data_criado' => $telefone_data_criado,
					'hora_criado' => $telefone_hora_criado,
					'cliente_id' => $cliente_id
				);

				$resposta_telefone = $this->db->insert('showtecsystem.clientes_telefones', $dados_telefone);

				if ($i == 0 && $telefone_verif == true) {

					$telefone_cli = "(" . $telefone_ddd . ") " . $telefone_numero;

					$dados_telefone_cli = array(
						'fone' => $telefone_cli
					);

					$this->db->where('id', $cliente_id);
					$this->db->update('showtecsystem.cad_clientes', $dados_telefone_cli);
				}
			}
		}
		return array('status' => 1, 'nome_cliente' => $cliente_nome, 'id_cliente' => $cliente_id);
	}

	/*
	 * CADASTRA USUARIO MASTER DO CLIENTE (METODO UTILIZADO QUANDO CRIADO UM NOVO CLIENTE)
	 */
    public function cad_grupo_byMaster($id_cliente) {
        // VERIFICA SE JÁ EXISTE GRUPO MASTER P/ O CLIENTE
        $this->db->where(array('nome' => 'MASTER', 'id_cliente' => $id_cliente));
        $query = $this->db->count_all_results('showtecsystem.cadastro_grupo');

        if (!$query && $query == 0) {
            // GRAVA GRUPO MASTER CLIENTE
            $dados = array(
                'nome' => 'MASTER',
                'id_cliente' => $id_cliente,
                'status' => 1
            );

            return $this->db->insert('showtecsystem.cadastro_grupo', $dados);
        }

        return FALSE;
	}
    public function get_grupo($id_cliente, $select = '*') {
		$this->db->select($select);
        $this->db->where(array('id_cliente' => $id_cliente));
        $query = $this->db->get('showtecsystem.cadastro_grupo')->result();
        return $query;
    }

	public function atualiza_grupo($id, $dados) {

		$this->db->where('id', $id);
		$this->db->update('showtecsystem.cadastro_grupo', $dados);
		if ($this->db->affected_rows() > 0)
			return true;

		return false;
    }

	public function digitalizacaoBanner($descricao, $nome_arquivo, $path) {
        $pasta = "banners";
        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path

        );

        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);

        if ($resposta) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function cadAtividades($funcionario,$curso,$tipo,$data_inicio,$data_fim,$cargahr,$status) {

        $dados = array(
            'id_funcionario' => $funcionario,
            'curso' => $curso,
            'tipo' => $tipo,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'carga_hr' => $cargahr,
            'status' => $status
        );

        $this->db->insert('showtecsystem.cad_atividades', $dados);
    }

    public function editAtividades($funcionario,$curso,$tipo,$data_inicio,$data_fim,$cargahr,$status,$id) {

        $dados = array(
            'id_funcionario' => $funcionario,
            'curso' => $curso,
            'tipo' => $tipo,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'carga_hr' => $cargahr,
            'status' => $status
        );

        $this->db->update('showtecsystem.cad_atividades', $dados, array('id' => $id));
    }

    public function digitalizacaoFolheto($descricao, $nome_arquivo, $path) {
        $pasta = "folhetos";
        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path

        );

        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);

        if ($resposta) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

	}
	public function digitalizacaoIso($descricao, $nome_arquivo, $path) {
        $pasta = "iso";
        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path

        );

        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);

        if ($resposta) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

	}
	public function digitalizacaoComercial($descricao, $nome_arquivo, $path) {
        $pasta = "comercial";
        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path

        );

        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);

        if ($resposta) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function digitalizacaoArquivo($descricao, $nome_arquivo, $path, $pasta) {

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path

        );

        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);

        if ($resposta) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function digitalizacaoArquivoTreinamento($descricao, $tipo, $link, $nome_arquivo, $path, $pasta) {

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path,
            'tipo' => $tipo,
            'link' => $link

        );

        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);

        if ($resposta) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function digitalizacaoApresentacao($last_id, $descricao, $nome_arquivo, $path, $ordem, $tabela) {

        $dados = array(
            'id_apresentacao' => $last_id,
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'path' => $path,
            'ordem' => $ordem

        );

        $resposta = $this->db->insert("showtecsystem.$tabela", $dados);

        if ($resposta) {
            return array('id' => $this->db->insert_id(), 'id_apresentacao' => $last_id,'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function digitalizacaoDocsPendente($nome_arquivo, $path) {

        $dados = array(
            'file' => $nome_arquivo,
            'path' => $path

        );

        $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
        $last_id =  $this->db->insert_id();

        $dadosDocPen= array(
            'id_arquivo' => $descricao,
            'id_arquivo' => $last_id,
            'id_usuario' => $id,
            'data' => $dataAtual
        );

        $comunicado = $this->db->insert('showtecsystem.cad_comunicados', $dadosComunicado);

    }

    public function cadPlanoVoo($nome, $descricao) {

        $dados = array(
            'nome' => $nome,
            'descricao' => $descricao
        );

        $this->db->insert("showtecsystem.plano_de_voo_questionario", $dados);

    }

    public function cadDescontoCoparticipacao($idfuncionario, $iddependente, $valorfun, $valordepen, $mescompetencia) {

        $dados = array(
            'id_funcionario' => $idfuncionario,
            'id_dependente' => $iddependente,
            'valor_funcionario' => $valorfun,
            'valor_dependente' => $valordepen,
            'mes_competencia' => $mescompetencia
        );

        $this->db->insert("showtecsystem.cad_desconto_coparticipacao", $dados);

    }

    public function cadComunicado($descricao, $nome_arquivo, $path, $id) {
        $pasta = "comunicados";
        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path
        );

        $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
        $last_id =  $this->db->insert_id();

        $dadosComunicado = array(
            'comunicado' => $descricao,
            'id_arquivo' => $last_id,
            'id_usuario' => $id,
            'data' => $dataAtual
        );

        $comunicado = $this->db->insert('showtecsystem.cad_comunicados', $dadosComunicado);

        if($arquivo) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function editComunicado($descricao, $nome_arquivo, $path, $id, $id_comunicado, $ativo) {
        $pasta = "comunicados";
        $dataAtual = date("Y-m-d H:i:s");

        if($path != ""){

            $dados = array(
                'file' => $nome_arquivo,
                'descricao' => $descricao,
                'pasta' => $pasta,
                'ndoc' => '',
                'path' => $path
            );

            $this->db->where('id', $id);
            $file = $this->db->get('arquivos',1)->row();

            if(unlink("$file->path")){

                $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
                $last_id =  $this->db->insert_id();


                $dadosComunicado = array(
                    'comunicado' => $descricao,
                    'id_arquivo' => $last_id,
                    'id_usuario' => $id,
                    'data' => $dataAtual
                );
                }
        }else{

            $dadosComunicado = array(
                'comunicado' => $descricao,
                'id_usuario' => $id,
                'data' => $dataAtual,
                'ativo' => $ativo
            );
        }

        $comunicado = $this->db->update('showtecsystem.cad_comunicados', $dadosComunicado, array('id' => $id_comunicado));

        if($comunicado) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    function verificaCadastroArquivo($descricao, $tabela, $pasta) {

        if($pasta != "")
        {
            $campo = "AND pasta = '$pasta'";
        }else{
            $campo = "";
        }

        $this->db->where("descricao = '$descricao' $campo");
        $query = $this->db->get($tabela);
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function verificaCadastroArquivoDocsPendente($id) {

        $this->db->where("id_funcionario = '$id'");
        $query = $this->db->get('cad_docs_pendente_arquivos');
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function verificaCadastroComunicado($comunicado) {
        $this->db->where('comunicado', $comunicado);
        $query = $this->db->get('cad_comunicados');
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function verificaCadastroAssunto($assunto, $tabela) {
        $this->db->where('assunto', $assunto);
        $query = $this->db->get("$tabela");
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function verificaCadastro($campo, $verificador, $tabela) {
        $this->db->where($campo, $verificador);
        $query = $this->db->get("$tabela");
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function verificaCadastroDescontoCoparticipacao($campo, $verificador, $campo2, $verificador2 , $tabela) {
        $this->db->where("$campo = $verificador AND $campo2 = $verificador2");
        $query = $this->db->get("$tabela");
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function verificaCadastroAssuntoProduto($assunto) {
        $this->db->where('assunto', $assunto);
        $query = $this->db->get('cad_assunto_produtos');
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getComunicado($where){

        $comunicado = false;
        $query = $this->db->get_where('cad_comunicados', $where);

        if($query->num_rows() > 0)
            foreach($query->result() as $comunicado);

            return $comunicado;

    }

    public function getDocumentosPendentes(){


        $query = $this->db->select('d.*, nome')
        ->order_by('u.nome', 'DESC')
        ->where('recebido = 0')
        ->join('showtecsystem.usuario as u', 'u.id = d.id_funcionario', 'inner')
        ->get('showtecsystem.cad_docs_pendentes as d');

        //$query = $this->db->get_where($tabela, $where);

        return $query->result();

    }

    public function getDescontosCoparticipacao(){


        $query = $this->db->select('d.*, nome')
        ->order_by('u.nome', 'DESC')
        ->group_by('mes_competencia')
        ->join('showtecsystem.usuario as u', 'u.id = d.id_funcionario', 'inner')
        ->get('showtecsystem.cad_desconto_coparticipacao as d');

        //$query = $this->db->get_where($tabela, $where);

        return $query->result();

    }

    public function getApresentacao($tabela,$where){

        $comunicado = false;
        $query = $this->db->get_where($tabela, $where);

        if($query->num_rows() > 0)
            foreach($query->result() as $comunicado);

            return $comunicado;

    }

    public function getContatosCorporativos(){

        $query = $this->db->select('*')->get('showtecsystem.cad_contatos_corporativos');

        return $query->result();

    }

	//RETORNA OS DADOS DO CONTATO CORPORATIVO
	public function getContatoCorporativo($select, $where){
	    $query = $this->db->select($select)
		->where($where)
		->get('showtecsystem.cad_contatos_corporativos');

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	/*
	* CADASTRAR NOVO CONTATO CORPORATIVO
	*/
    public function cadContatoCorporativo($dados) {
		return $this->db->insert('showtecsystem.cad_contatos_corporativos', $dados);
    }

	/*
	* SALVA OS DADOS EDITADOS DE UM CONTATO CORPORATIVO
	*/
    public function editContatoCorporativo($id, $dados) {
        return $this->db->update('showtecsystem.cad_contatos_corporativos', $dados, array('id' => $id));
    }

    public function excluirContatoCorporativoById($id) {
        return $this->db->where('id', $id)->delete('cad_contatos_corporativos');
    }

    public function excluirById($id, $tabela) {
        return $this->db->where('id', $id)->delete($tabela);
    }

    public function excluirDependenteById($id) {
        return $this->db->where('id', $id)->delete('cad_colaborador_dependentes');
    }

    public function editApresentacao($descricao, $id, $tabela) {

        $dados = array(
            'descricao' => $descricao
        );

        $apresentacao = $this->db->update("showtecsystem.$tabela", $dados, array('id' => $id));

        if($apresentacao) {
            return array('id' => $this->db->insert_id(), 'descricao' => $descricao);
        }else{
            return false;
        }

    }

    public function cadAssunto($asunto, $tabela) {

        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'assunto' => $asunto,
            'data_criacao' => $dataAtual
        );

        $comunicado = $this->db->insert("showtecsystem.$tabela", $dados);

        if($comunicado) {
            return array('id' => $this->db->insert_id(), 'assunto' => $asunto);
        }else{
            return false;
        }

    }

    public function cadPoliticaFormulario($descricao, $nome_arquivo, $path, $assunto, $tipo) {
        $pasta = "politica_formulario";
        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path
        );

        $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
        $last_id =  $this->db->insert_id();

        $dadosComunicado = array(
            'id_assunto' => $assunto,
            'tipo' => $tipo,
            'descricao' => $descricao,
            'id_arquivos' => $last_id,
            'data_criacao' => $dataAtual
        );

        $comunicado = $this->db->insert('showtecsystem.cad_formularios_informacoes', $dadosComunicado);

        if($arquivo){
            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function digitalizacaoDocsPendentes($idfuncionario, $nome_arquivo, $path) {
        $pasta = "docs_pendentes";
        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'id_funcionario' => $idfuncionario,
            'file' => $nome_arquivo,
            'pasta' => $pasta,
            'path' => $path,
            'data_envio' => $dataAtual
        );

        $arquivo = $this->db->insert('showtecsystem.cad_docs_pendente_arquivos', $dados);

        $dados2 = array(
            'status' =>  '0'
        );


        $this->db->where('id_funcionario', $idfuncionario);

        $this->db->update('showtecsystem.cad_docs_pendentes', $dados2);

    }

    public function cadPoliticaFormularioRh($descricao, $nome_arquivo, $path, $tipo) {
        $pasta = "politica_formulario";
        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path
        );

        $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
        $last_id =  $this->db->insert_id();

        $dadosComunicado = array(
            'tipo' => $tipo,
            'descricao' => $descricao,
            'id_arquivo' => $last_id,
            'data_criacao' => $dataAtual
        );

        $comunicado = $this->db->insert('showtecsystem.cad_politicas_formularios', $dadosComunicado);

        if($arquivo){
            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function cadMarketingFormulario($descricao, $nome_arquivo, $path, $assunto, $pasta) {

        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path
        );

        $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
        $last_id =  $this->db->insert_id();

        $dadosMarketing = array(
            'id_assunto' => $assunto,
            'descricao' => $descricao,
            'id_arquivo' => $last_id,
            'data_criacao' => $dataAtual
        );

        $comunicado = $this->db->insert('showtecsystem.cad_marketing_campanhas', $dadosMarketing);

        if($arquivo){
            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function editMarketingFormulario($descricao, $nome_arquivo, $path, $assunto, $ativo, $pasta, $id_informacao) {

        $dataAtual = date("Y-m-d H:i:s");

        if($nome_arquivo != ""){

            $dados = array(
                'file' => $nome_arquivo,
                'descricao' => $descricao,
                'pasta' => $pasta,
                'ndoc' => '',
                'path' => $path
            );

            $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
            $last_id =  $this->db->insert_id();

            $dadosMarketing = array(
                'id_assunto' => $assunto,
                'descricao' => $descricao,
                'id_arquivo' => $last_id,
                'data_criacao' => $dataAtual,
                'ativo' => $ativo
            );
        }else{

            $dadosMarketing = array(
                'id_assunto' => $assunto,
                'descricao' => $descricao,
                'ativo' => $ativo
            );

        }

        $this->db->update('showtecsystem.cad_marketing_campanhas', $dadosMarketing, array('id' => $id_informacao));


    }

    public function editAssunto($assunto, $id_assunto, $ativo, $tabela) {

        $dados = array(
            'assunto' => $assunto,
            'ativo' => $ativo
        );

        $result = $this->db->update("showtecsystem.$tabela", $dados, array('id' => $id_assunto));

        if($result) {
            return array('id' => $this->db->insert_id(), 'assunto' => $assunto);
        }else{
            return false;
        }

    }

    public function editInformacao($assunto, $tipo, $descricao, $ativo, $id_informacao) {

        $dados = array(
            'id_assunto' => $assunto,
            'tipo' => $tipo,
            'descricao' => $descricao,
            'status' => $ativo
        );

       $this->db->update('showtecsystem.cad_formularios_informacoes', $dados, array('id' => $id_informacao));

    }

    public function editPoliticaFormulario($descricao, $nome_arquivo, $path, $assunto, $tipo, $id_informacao, $ativo) {
        $pasta = "politica_formulario";
        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path
        );

        $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
        $last_id =  $this->db->insert_id();

        $dadosInfo= array(
            'id_assunto' => $assunto,
            'tipo' => $tipo,
            'descricao' => $descricao,
            'id_arquivo' => $last_id,
            'data_criacao' => $dataAtual,
            'status' => $ativo
        );

        $this->db->update('showtecsystem.cad_formularios_informacoes', $dadosInfo, array('id' => $id_informacao));

    }

    public function editPoliticaFormularioRh($descricao, $nome_arquivo, $path, $tipo, $id_informacao, $ativo) {
        $pasta = "politica_formulario";
        $dataAtual = date("Y-m-d H:i:s");


        if($nome_arquivo != ""){
            $dados = array(
                'file' => $nome_arquivo,
                'descricao' => $descricao,
                'pasta' => $pasta,
                'ndoc' => '',
                'path' => $path
            );

            $arquivo = $this->db->insert('showtecsystem.arquivos', $dados);
            $last_id =  $this->db->insert_id();

            $dadosInfo= array(
                'tipo' => $tipo,
                'descricao' => $descricao,
                'id_arquivo' => $last_id,
                'data_criacao' => $dataAtual,
                'ativo' => $ativo
            );
        }else{

            $dadosInfo= array(
                'tipo' => $tipo,
                'descricao' => $descricao,
                'ativo' => $ativo
            );

        }
        $this->db->update('showtecsystem.cad_politicas_formularios', $dadosInfo, array('id' => $id_informacao));

    }

    public function editPlanoVoo($nome, $descricao, $id_plano) {

        $dadosInfo= array(
            'nome' => $nome,
            'descricao' => $descricao
        );

        $this->db->update('showtecsystem.plano_de_voo_questionario', $dadosInfo, array('id' => $id_plano));

    }



    public function editArquivoFormulario($descricao, $nome_arquivo = false, $path = false, $pasta, $id_arquivo) {

        if($nome_arquivo == "" || $nome_arquivo == false){

            $dados = array(
                'descricao' => $descricao
            );

        }else{

            $dados = array(
                'file' => $nome_arquivo,
                'descricao' => $descricao,
                'pasta' => $pasta,
                'ndoc' => '',
                'path' => $path
            );

        }

        $this->db->update('showtecsystem.arquivos', $dados, array('id' => $id_arquivo));

	}
	public function editArquivoFormularioComercial($descricao, $id_arquivo) {

		$dados = array(
			'descricao' => $descricao
		);

        $this->db->update('showtecsystem.arquivos', $dados, array('id' => $id_arquivo));

	}


    public function editArquivoFormularioTreinamento($descricao, $tipo, $link, $nome_arquivo, $path, $pasta, $id_arquivo) {

        if($nome_arquivo == ""){

            $dados = array(
                'descricao' => $descricao,
                'tipo' => $tipo,
                'link' => $link

            );

        }else{

            $dados = array(
                'file' => $nome_arquivo,
                'descricao' => $descricao,
                'pasta' => $pasta,
                'ndoc' => '',
                'path' => $path,
                'tipo' => $tipo,
                'link' => $link
            );

        }

        $this->db->update('showtecsystem.arquivos', $dados, array('id' => $id_arquivo));

    }

    public function digitalizacaoProduto($descricao, $nome_arquivo, $path) {
        $pasta = "produtos";
        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $path

        );

        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);

        if ($resposta) {

            return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo, 'path' => $path);
        }else{
            return false;
        }

    }

    public function cadProdutoFormulario($id_produto, $id_assunto, $nome_arquivo, $path) {
        $pasta = "produtos";

        $dados = array(
            'id_produto' => $id_produto,
            'id_assunto' => $id_assunto,
            'file' => $nome_arquivo,
            'pasta' => $pasta,
            'path' => $path
        );

        $arquivo = $this->db->insert('showtecsystem.cad_produto_informacoes_arquivos', $dados);

    }

    public function cadAssuntoProduto($asunto) {

        $dataAtual = date("Y-m-d H:i:s");

        $dados = array(
            'assunto' => $asunto,
            'data_criacao' => $dataAtual
        );

        $comunicado = $this->db->insert('showtecsystem.cad_assunto_produtos', $dados);

        if($comunicado) {
            return array('id' => $this->db->insert_id(), 'assunto' => $asunto);
        }else{
            return false;
        }

    }

    public function getProdutos(){

        $query = $this->db->select('i.*, assunto, id_assunto')
        ->group_by('assunto')
        ->order_by('i.id', 'DESC')
        ->join('showtecsystem.cad_assunto_produtos as a', 'i.id_assunto = a.id', 'inner')
        ->where("i.ativo = '1'")
        ->get('showtecsystem.cad_produtos_informacoes as i');

        return $query->result();

    }

    public function excluirProdutoById($id) {
        return $this->db->where('id_assunto', $id)->delete('cad_produtos_informacoes');
    }

    public function excluirProdutoEditarById($id) {
        return $this->db->where('id', $id)->delete('cad_produtos_informacoes');
    }

    public function excluirProdutoArquivosById($id) {
        return $this->db->where('id_assunto', $id)->delete('cad_produto_informacoes_arquivos');
    }

    public function excluirProdutoArquivosEditarById($id) {
        return $this->db->where('id', $id)->delete('cad_produto_informacoes_arquivos');
    }

    public function excluirPfById($id) {
        return $this->db->where('id', $id)->delete('cad_politicas_formularios');
    }

    public function getProdutoEditar($id){


        $query = $this->db->select('i.*, assunto')
        ->order_by('i.id', 'DESC')
        ->join('showtecsystem.cad_assunto_produtos as a', 'i.id_assunto = a.id', 'inner')
        ->where("i.id_assunto = '$id' AND i.ativo = '1'")
        ->get('showtecsystem.cad_produtos_informacoes as i');

        if($query->num_rows() > 0)
            foreach($query->result() as $produtos);

            return $produtos;

	}

	public function editProdutoFormulario($id_produto, $id_assunto, $nome_arquivo, $path) {

	    $pasta = "produtos";

	    $dados = array(
	        'id_produto' => $id_produto,
	        'id_assunto' => $id_assunto,
	        'file' => $nome_arquivo,
	        'pasta' => $pasta,
	        'path' => $path
	    );

	    $arquivo = $this->db->insert('showtecsystem.cad_produto_informacoes_arquivos', $dados);
	}

	public function editInformacaoProduto($assunto, $descricao, $ativo, $id_produto) {

	    $dados = array(
	        'id_assunto' => $assunto,
	        'descricao' => $descricao,
	        'ativo' => $ativo
	    );

	    $this->db->update('showtecsystem.cad_produtos_informacoes', $dados, array('id' => $id_produto));

	}

	public function editInformacaoProdutoAssunto($assunto, $id_produto) {


	    $query_produtos_info = $this->db->query("SELECT id, id_assunto FROM cad_produtos_informacoes WHERE id = '$id_produto'");

	    foreach ($query_produtos_info->result_array() as $row) {

	        $dados = array(
	            'assunto' => $assunto,
	        );

	        $this->db->update('showtecsystem.cad_assunto_produtos', $dados, array('id' => $row[id_assunto]));

	    }

	}

	public function cadCorrecaoIrrf($titulo, $descricao) {

	    $dataAtual = date("Y-m-d H:i:s");

	    $dados = array(
	        'titulo' => $titulo,
	        'descricao' => $descricao,
	        'data_criacao' => $dataAtual
	    );

	    $comunicado = $this->db->insert("showtecsystem.cad_correcao_irrf", $dados);

	    if($comunicado) {
	        return array('id' => $this->db->insert_id(), 'assunto' => $asunto);
	    }else{
	        return false;
	    }

	}

	public function excluirCorrecaoIrrfById($id) {
	    return $this->db->where('id', $id)->delete('cad_correcao_irrf');
	}

	public function excluirDocspendById($id) {
	    return $this->db->where('id_funcionario', $id)->delete('cad_docs_pendentes');
	}

	public function add_usuario($dados) {
	    return $this->db->insert('showtecsystem.cad_colaborador', $dados);
	}

	public function update_usuario($dados, $id) {

	    if ($id && is_numeric($id)) {
	        if ($dados && is_array($dados)) {
	            return $this->db->update('showtecsystem.cad_colaborador', $dados, array('id' => $id));
	        }
	    }

	    return false;
	}

	public function add_dependente($dados) {
	    return $this->db->insert('showtecsystem.cad_colaborador_dependentes', $dados);
	}

	public function update_dependente($dados, $id) {

	    if ($id && is_numeric($id)) {
	        if ($dados && is_array($dados)) {
	            return $this->db->update('showtecsystem.cad_colaborador_dependentes', $dados, array('id' => $id));
	        }
	    }

	    return false;
	}

	public function cadDocumentosPendentes($idfuncionario, $residencia, $cpf, $rg, $banco,$prazo) {

	    $dataAtual = date("Y-m-d");

	    $dados = array(
	        'id_funcionario' => $idfuncionario,
	        'residencia' => $residencia,
	        'cpf' => $cpf,
	        'rg' => $rg,
	        'banco' => $banco,
	        'status' => '1',
	        'data_solicitacao' => $dataAtual,
	        'prazo_maximo' => $prazo
	    );

	    $this->db->insert("showtecsystem.cad_docs_pendentes", $dados);

	}

	public function editDocumentosPendentes($idfuncionario, $residencia, $cpf, $rg, $banco,$prazo, $recebido, $id) {

	    $dataAtual = date("Y-m-d");

	    $dados = array(
	        'id_funcionario' => $idfuncionario,
	        'residencia' => $residencia,
	        'cpf' => $cpf,
	        'rg' => $rg,
	        'banco' => $banco,
	        'status' => '0',
	        'recebido' => $recebido,
	        'prazo_maximo' => $prazo,
	        'data_entrega' =>  $dataAtual
	    );


	    $this->db->where('id', $id);

	    $this->db->update('showtecsystem.cad_docs_pendentes', $dados);

	}

	public function getAniversariantes(){

	    $query = $this->db->select('*')
	    ->order_by('nome', 'ASC')
	    ->where("ativo = '1'")
	    ->get('showtecsystem.cad_aniversariantes');

	    return $query->result();

	}

	public function getConfigSmtp(){

        $configuracao = false;
        $query = $this->db->get_where('showtecsystem.smtp');

        if($query->num_rows() > 0)
            foreach($query->result() as $configuracao);

            return $configuracao;

	}

	function verificaCadastroAniversariante($cpf) {

	    $this->db->where("cpf = '$cpf'");
	    $query = $this->db->get('cad_aniversariantes');
	    if ($query->num_rows() > 0) {
	        return FALSE;
	    } else {
	        return TRUE;
	    }
	}

	public function cadAniversariantes($nome, $cpf, $email, $empresa, $cargo, $datanasc) {

	    $dados = array(
	        'nome' => $nome,
	        'cpf' => $cpf,
	        'email' => $email,
	        'empresa   ' => $empresa,
	        'cargo' => $cargo,
	        'data_nasc' => $datanasc
	    );

	    $nivers = $this->db->insert('showtecsystem.cad_aniversariantes', $dados);

	    if($nivers) {
	        return true;
	    }else{
	        return false;
	    }

	}

	public function updateAniversariantes($nome, $email, $empresa, $cargo, $datanasc, $ativo, $id) {

	    $dados = array(
	        'nome' => $nome,
	        'email' => $email,
	        'empresa   ' => $empresa,
	        'cargo' => $cargo,
	        'data_nasc' => $datanasc,
	        'ativo' => $ativo,
	    );

	    $nivers = $this->db->update('showtecsystem.cad_aniversariantes', $dados, array('id' => $id));

	    if($nivers) {
	        return true;
	    }else{
	        return false;
	    }

	}

	public function updateSmtp($servidor, $porta, $email, $senha, $titulo, $copia) {

	    if($senha == ""){
	        $dados = array(
	            'smtp_host' => $servidor,
	            'smtp_username' => $email,
	            'smtp_fromname' => $titulo,
	            'smtp_bcc' => $copia,
	            'smtp_port' => $porta
	        );
	    }else{

    	    $dados = array(
    	        'smtp_host' => $servidor,
    	        'smtp_username' => $email,
    	        'smtp_password' => $senha,
    	        'smtp_fromname' => $titulo,
    	        'smtp_bcc' => $copia,
    	        'smtp_port' => $porta
    	    );
	    }

	    $nivers = $this->db->update('showtecsystem.smtp', $dados, array('smtp_id' => '1'));

	    if($nivers) {
	        return true;
	    }else{
	        return false;
	    }

	}

	/**
	 * TOTAL DE TORNOZELEIRAS ATIVAS
	*/
	function total_tornozeleiras_ativas($where=array())
	{
		$this->db->from("showtecsystem.contratos as c");
		$this->db->join("showtecsystem.contratos_veiculos as v", 'c.id = v.id_contrato');
		$this->db->where('c.tipo_proposta = 4');
		$this->db->where($where);

		return $this->db->count_all_results();
	}

	/**
	 * Retorna as permissões de um conjunto de produtos
	 * @param array $ids_produtos - ids dos produtos
	*/
	public function get_permissoes_produtos($ids_produtos, $select = 'id_permissao'){
		return $this->db->select($select)
		->from('showtecsystem.cad_produto_permissao as produto')
		->join('showtecsystem.cad_permissoes as permissao', 'produto.id_permissao = permissao.id', 'left')
		->where(['permissao.status' => '1'])
		->where_in('id_produto', $ids_produtos)
		->get()->result();
	}

	/**
	 * Retorna os clientes que possuem o produto informado
	 * @param int $id_produto - id do produto
	 * @param string $select - campos a serem retornados
	*/
	public function buscar_clientes_pelo_produto($id_produto, $select = '*') {
		$sql = "SELECT {$select} 
						FROM showtecsystem.cad_clientes 
						WHERE JSON_CONTAINS(ids_produtos, '{$id_produto}', '$') 
							OR JSON_CONTAINS(ids_produtos, '\"{$id_produto}\"', '$')";

		$query = $this->db->query($sql);
		return $query->result();
	}

	/**
	 * Retorna as permissões de um conjunto de ids de permissoes
	 * @param int $ids - ids das permissoes
	 * @param string $select - campos a serem retornados
	*/
	public function get_permissoes_por_ids($ids, $select = 'cod_permissao') {
		return $this->db->select($select)
			->where_in('id', $ids)
			->get('showtecsystem.cad_permissoes')->result();
	}


}
