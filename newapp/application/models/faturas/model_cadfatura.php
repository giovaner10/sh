<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_gestor extends CI_Model {

	public function __construct(){
		parent::__construct();
		//$this->load->helper('email');
	}

	
	public function getUsuariosGrupo($id_grupo) {
		return $this->db->query("SELECT * FROM showtecsystem.user_x_group WHERE id_group = ".$id_grupo." AND status = 1;")->result_array();
	}

	public function getPlacasGrupo($id_grupo) {
		return $this->db->query("SELECT * FROM showtecsystem.veic_x_group WHERE groupon = ".$id_grupo." AND status = 1;")->result_array();
	}

	public function getIscasGrupo($id_grupo) {
		return $this->db->query("SELECT * FROM showtecsystem.isca_x_group WHERE id_group = ".$id_grupo." AND status = 1;")->result_array();
	}
	
	public function deleteGroup($id) {
		pr('deletou o grupo: '.$id.'</br>');
		return $this->db->delete('showtecsystem.cadastro_grupo', [ 'id' => $id ]);
	}

	public function insertUserMaster($array, $master) {
		pr('Removeu o usuário: '.$array['id_user'].' do grupo: '.$array['id_group'].' e inseriu no grupo: '.$master['id'].'</br>');
		return $this->db->update('showtecsystem.user_x_group', [ 'id_group' => $master['id'] ], [ 'id' => $array['id'] ]);
	}

	public function insertPlacasMaster($array, $master) {
		pr('Removeu a placa: '.$array['placa'].' do grupo: '.$array['id_group'].' e inseriu no grupo: '.$master['id'].'</br>');
		return $this->db->update('showtecsystem.veic_x_group', [ 'groupon' => $master['id'] ], [ 'id' => $array['id'] ]);
	}

	public function insertIscasMaster($array, $master) {
		pr('Removeu a isca: '.$array['serial'].' do grupo: '.$array['id_group'].' e inseriu no grupo: '.$master['id'].'</br>');
		return $this->db->update('showtecsystem.isca_x_group', [ 'id_group' => $master['id'] ], [ 'id' => $array['id'] ]);
	}

	public function getGruposCliente($id_cliente) {
		return $this->db->query("SELECT * FROM showtecsystem.cadastro_grupo 
			WHERE id_cliente = ".$id_cliente." AND nome = 'MASTER' AND status = 1;"
		)->result_array();
	}

	public function add($dados) {
		$validacao_usuario = $this->validar_email($dados['usuario']);
		$validacao_cpf = $this->validar_cpf($dados['cpf']);
		$validacao_telefone = $this->validar_telefone($dados['celular']);
		
		if ($validacao_usuario == true && $validacao_cpf == true && $validacao_telefone == true) {
			$cliente = [];
			if (isset($dados['id_cliente']) && is_numeric($dados['id_cliente']))
				$cliente = $this->db->select('permissoes')->get_where('showtecsystem.cad_clientes', array('id' => $dados['id_cliente']))->row();

			$dados['token'] = md5($dados['usuario'].'-'.$dados['senha']);
			$dados['senha'] = md5($dados['senha']);
			if (isset($cliente->permissoes) && $cliente->permissoes)
				$dados['permissoes'] = serialize(json_decode($cliente->permissoes));

			// deleta o campo código da variavel $dados
			unset($dados['codigo']);
			unset($dados['codigoEmail']);

			$this->db->insert('usuario_gestor', $dados);
			$id_user = $this->db->insert_id();

			if ($this->db->affected_rows() > 0){
			    //return TRUE;
				return $id_user;
            } else {
                throw new Exception('Não foi possível gravar no banco de dados. Tente novamente.');
            }
		} else
			throw new Exception('Usuário ou CPF inválido.');
	}

	/*
	 * VINCULA USUARIO À UM GRUPO
	 */
	public function vinculaUser_group($id_user, $id_group) {
		if (is_numeric($id_user) && is_numeric($id_group)) {
			// VERIFICA SE O USUARIO JÁ PERTENCE AO GRUPO
			$where = array('status' => '1', 'id_user' => $id_user, 'id_group' => $id_group);
			$query = $this->db->where($where)
				->from('showtecsystem.user_x_group')
				->count_all_results();

			if ($query === 0) {
				$dados = array(
					'id_user' => $id_user,
					'id_group' => $id_group,
					'status' => '1'
				);
				return $this->db->insert('showtecsystem.user_x_group', $dados);
			}
		}

        return FALSE;
    }

	public function getGruposUser($id_user) {
		$this->db->select('id_group, uxg.status, g.nome');
		$this->db->where('uxg.id_user', $id_user);
		$this->db->where('uxg.status', '1');
		$this->db->join('showtecsystem.grupos as g', 'g.id = uxg.id_group');
		$query = $this->db->get('showtecsystem.user_x_group as uxg');
		return $query->result();
	}
	public function getVeiculosGrupo($id_group) {
		$this->db->select('id, vxg.espelhamento, vxg.placa');
		$this->db->where('vxg.groupon', $id_group);
		$this->db->where('vxg.status', '1');
		$query = $this->db->get('showtecsystem.veic_x_group as vxg');
		return $query->result();
	}

	
	public function validar_email($email) {
			$email_exists = $this->db->from('usuario_gestor')
				->where(array('usuario' => $email))
				->get()->result();	
			if (count($email_exists) != 0){
				throw new Exception('O e-mail que está tentando cadastrar já está em uso pelo cliente: '.strtoupper($email_exists[0]->id_cliente));
			};
		
		return true;
	}
	
	public function validar_cpf($cpf) {
		// VERIFICA SE O CPF É VÁLIDO
		$cpfSemFormatacao = $cpf;
		$cpf_exists = $this->db->from('usuario_gestor')
			->where(array('cpf' => $cpfSemFormatacao))
			->get()->result();
		if (count($cpf_exists) != 0){
			throw new Exception('O CPF que está tentando cadastrar já está em uso');
		};
		return true;
	}

	public function validar_telefone($telefone) {
		// validar se o celular é válido
		$telefone_exists = $this->db->from('usuario_gestor')
			->where(array('celular' => $telefone))
			->get()->result();
		if (count($telefone_exists) != 0){
			throw new Exception('O telefone que está tentando cadastrar já está em uso');
		};
		return true;
	}

	public function listar($where, $select='*') {
		$this->db->select($select);
		$query = $this->db->get_where('showtecsystem.usuario_gestor', $where);
		return $query->result();
	}

    function bloquearUser_parcial($where) {
	    $this->db->where($where)->update('usuario_gestor', array('status_usuario' => 'parcial'));
        return $this->db->where('id', $where['id_cliente'])->update('showtecsystem.cad_clientes', array('status' => 6));
    }

    function desbloquearUser_parcial($where) {
        $this->db->where($where)->update('usuario_gestor', array('status_usuario' => 'ativo'));
        return $this->db->where('id', $where['id_cliente'])->update('showtecsystem.cad_clientes', array('status' => 1));
    }

	public function get($where = null) {
		$usuario = array();
		if ($where) { $this->db->where($where); }
		$query = $this->db->get('showtecsystem.usuario_gestor');
		if($query->num_rows() > 0) {
			foreach ($query->result() as $usuario);
		}
		if($usuario){
			$this->db->select("data_vencimento");
			$this->db->where("status",0);
			$this->db->where("id_cliente",$usuario->id_cliente);
			$this->db->order_by("data_vencimento");

			$faturas = $this->db->get('showtecsystem.cad_faturas',1,0);
			if($faturas->num_rows() > 0){
				$faturas=$faturas->result();
				$datetime1 = new DateTime($faturas[0]->data_vencimento);
				$datetime2 = new DateTime();
				$interval = $datetime1->diff($datetime2);
				$usuario->fatura_aberta=$interval->days;
			}
			else{
				$usuario->fatura_aberta=0;
			}
		}
		return $usuario;
	}

	public function permissoes($where = null) {
		$permissoes = null;
		$queryGrupo = $this->db->get_where('showtecsystem.grupos', $where);
		if ($queryGrupo->num_rows()) {
			foreach ($queryGrupo->result() as $group) {
				$queryPermissao = $this->db->get_where('showtecsystem.grupos_permissoes', ['grupo_id' => $group->id]);
				if ($queryPermissao->num_rows()) {
					foreach ($queryPermissao->result() as $permissao) {
						$permissoes = $permissao->permissoes;
					}
				}
			}
		}
		return $permissoes;
	}

	public function get_parametros() {
		$query = $this->db->select('email, cnpj')->where('receber_veiculos_desatualizados', 'sim')->where('email !=', '')->get('systems.parametro_relatorio');
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	public function get_cliente_usuario($cnpj) {
		$query = $this->db
			->select('usuario_gestor.usuario, usuario_gestor.nome_usuario, usuario_gestor.code, cad_clientes.nome')
			->from('usuario_gestor')
			->join('cad_clientes', 'usuario_gestor.id_cliente = cad_clientes.id')
			->where('CNPJ_', $cnpj)
			->get();
		if ($query->num_rows())
			return $query->row();
		return false;
	}

	/*
	 * BUSCA GRUPOS DO CLIENTE
	 */
	public function get_groups_byClients($where) {
	    $this->db->where('status', 1);
	    $query = $this->db->get_where('showtecsystem.cadastro_grupo', $where);

	    return $query->result();

    }

	public function atualizar($id_usuario, $dados) {
		$erro = false;
		$usuario = $this->get(array('code' => $id_usuario));
		if($usuario){
			if($usuario->usuario != $dados['usuario']){
				if (!$this->validar_email($dados['usuario'])) $erro = true;
			}
			if (isset($dados['senha']) && $dados['senha'] != '')
				$dados['senha'] = md5($dados['senha']);
			else
				unset($dados['senha']);
			if (!$erro) {
                // $id_group = $dados['group'];
				// unset($dados['group']);

				if(isset($dados['senha'])){
					$dados['token'] = md5($dados['usuario'].'-'.$dados['senha']);
				}   
				$update = $this->db->update('usuario_gestor', $dados, array('code' => $id_usuario));         
			} else
				throw new Exception('O email informado já está em uso. Tente utilizar outro email.');
		} else
			throw new Exception('Usuário não encontrado.');
	}

	public function atualizar_conta($id_usuario, $dados) {
		$this->db->update('usuario_gestor', $dados, array('code' => $id_usuario));
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}

	public function find_by_placa ($id_cliente) {
		$query = $this->db->query("SELECT code, usuario, CNPJ_ FROM showtecsystem.usuario_gestor WHERE id_cliente = '{$id_cliente}'");
		if ($query->num_rows())
			return $query->result();
		return false;
	}

	function getIdUser($user){
		return $this->db->select('code')->where('usuario', $user)->get('showtecsystem.usuario_gestor')->result()[0]->code;
	}

	public function get_nameUser($id_user){
		if ($id_user)
			return $this->db->select('usuario')->where('code', $id_user)->get('showtecsystem.usuario_gestor')->result();
	}

	public function atualizar_avatar($id_usuario) {
		// Se o usuário clicou no botão cadastrar efetua as ações
		if (isset($_POST['cadastrar'])) {

			// Recupera os dados dos campos
			$foto = $_FILES["foto"];

			// Se a foto estiver sido selecionada
			if (!empty($foto["name"])) {

				// Largura máxima em pixels
				$largura = 800;
				// Altura máxima em pixels
				$altura = 600;
				// Tamanho máximo do arquivo em bytes
				$tamanho = 5000;

				$error = array();

				// Verifica se o arquivo é uma imagem
				if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
				   $error[1] = "Isso não é uma imagem.";
				}

				// Pega as dimensões da imagem
				$dimensoes = getimagesize($foto["tmp_name"]);

				// Verifica se a largura da imagem é maior que a largura permitida
				if($dimensoes[0] > $largura) {
					$error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
				}

				// Verifica se a altura da imagem é maior que a altura permitida
				if($dimensoes[1] > $altura) {
					$error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
				}

				// Verifica se o tamanho da imagem é maior que o tamanho permitido
				if($foto["size"] > $tamanho) {
					$error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
				}

				// Se não houver nenhum erro
				if (count($error) == 0) {

					// Pega extensão da imagem
					preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

					// Gera um nome único para a imagem
					$nome_imagem = $id_usuario . "." . $ext[1];

					// Caminho de onde ficará a imagem
					$caminho_imagem = "../../uploads/users/" . $nome_imagem;

					// Faz o upload da imagem para seu respectivo caminho
					move_uploaded_file($foto["tmp_name"], $caminho_imagem);

				}

				// Se houver mensagens de erro, exibe-as
				if (count($error) != 0) {
					foreach ($error as $erro) {
						echo $erro . "<br />";
					}
				}
			}
		}
	}

	public function get_gestores($like, $id_cliente, $limit=999999){
		$this->db->select('nome_usuario as text, code as id');
		if ($like)
			$this->db->like($like);

		$where = array('status_usuario' => 'ativo');
		if ($id_cliente)
			$where = array('status_usuario' => 'ativo', 'id_cliente' => $id_cliente);

		$this->db->order_by('nome_usuario', "ASC");
		$this->db->where($where);
		$query = $this->db->get('showtecsystem.usuario_gestor', $limit, 0);

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	function negativar_positivar($id_cliente, $acao) {
		if ($acao == 0) {
			$this->db->where('id_cliente', $id_cliente)->update('usuario_gestor', array('status_usuario' => 'parcial'));
	        return $this->db->where('id', $id_cliente)->update('showtecsystem.cad_clientes', array('status' => 7));
		}
		$this->db->where('id_cliente', $id_cliente)->update('usuario_gestor', array('status_usuario' => 'ativo'));
        return $this->db->where('id', $id_cliente)->update('showtecsystem.cad_clientes', array('status' => 1));
    }

	/*
	* BUSCAR OS USUÁRIO GESTORES DE UM CLIENTE
	*/
	public function get_gestores_cliente($select='*', $where=array()){
		$this->db->select($select );
		$this->db->order_by('nome_usuario', "ASC");
		$query = $this->db->get_where('showtecsystem.usuario_gestor', $where);

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	/*
	* RETORNA OS USUARIOS DE UM GRUPO DE CLIENTES
    */
    public function listUsuarios($clientes){
        //A PESQUISA RETORNA OS DADOS DOS USUARIOS NO FORMATO:[cliente]->[usuario1,usuario2,usuario3...]
		$sql = "SELECT id_cliente, GROUP_CONCAT( code SEPARATOR ',' ) as usuarios
                FROM showtecsystem.usuario_gestor
                WHERE id_cliente in ($clientes) and status_usuario = 'ativo'
                GROUP BY id_cliente";
        $query = $this->db->query($sql);

        if($query->num_rows()>0){
            return $query->result();
        }
        return false;
    }

	/**
	 * Retorna os dados de um usuário gestor
	*/
	public function get_usuario_for_id($id_usuario, $colunas) {
        return $this->db->select($colunas)
		->where('code', $id_usuario)
		->get('showtecsystem.usuario_gestor')->row();
    }

}
