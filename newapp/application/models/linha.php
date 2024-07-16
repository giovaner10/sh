<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Linha extends CI_Model {

	public $Handle;
	public $Host_Mikrotik = '192.168.0.12';
	public $Host_Servidor = '192.168.0.102';
	public $MSG_erros = array();

	public function __construct() {
		parent::__construct();
	}

	function getSolicitacao($id) {
		return $this->db->join('showtecsystem.cancelamento_itens as itens', 'itens.id_cancelamento = cad.id', 'inner')
		->where('cad.id', $id)
		->get('showtecsystem.cad_cancelamentos as cad')
		->result();
	}

	function getSerialByCcid($ccid) {
		$equipamento = $this->db->get_where('showtecsystem.cad_equipamentos', array('ccid' => "{$ccid}"))->row();

		if ($equipamento) {
			return $equipamento->serial;
		} else {
			$result = $this->db->select('eqp.serial')
			->join('showtecsystem.cad_equipamentos as eqp', 'eqp.id_chip = cad_chips.id', 'left')
			->where(array('cad_chips.ccid' => "{$ccid}", 'eqp.serial !=' => ''))
			->get('showtecsystem.cad_chips')
			->row();

			if ($result)
				return $result->serial;
		}

		return false;
	}

	function getClieBySerial($serial) {
		$result = $this->db->select('contratos_veiculos.id_cliente')
		->join('showtecsystem.contratos_veiculos', 'contratos_veiculos.placa = cadastro_veiculo.placa', 'left')
		->where(array('cadastro_veiculo.serial' => "{$serial}", 'contratos_veiculos.status' => 'ativo'))
		->get('systems.cadastro_veiculo')
		->row();

		if ($result)
			return $result->id_cliente;
		return false;
	}

	function getClieByCcid($ccid) {
		$result = $this->db->get_where('showtecsystem.cad_chips', array('ccid' => "{$ccid}", 'id_cliente_sim2m !=' => ''))->row();

		if ($result)
			return $result->id_cliente_sim2m;
		return false;
	}

	function gravaDadosConta($dados, $ref) {
		if (!$this->db->get_where('showtecsystem.detalhamento_conta', array('referencia' => "{$ref}"))->result()) {
			return $this->db->insert_batch('showtecsystem.detalhamento_conta', $dados);
		}

		return false;
	}

	function verificaRefContas($ref) {
		return $this->db->get_where('showtecsystem.detalhamento_conta', array('referencia' => "{$ref}"))->result();
	}

	function createGraficContas() {
		$string = '';
	    /*for ($i = 1; $i <= date('m'); $i++) {
            if ($i < 10)
                $ref = '0'.$i.'/'.date('Y');
            else
                $ref = $i.'/'.date('Y');

            $dados = $this->getRelContas($ref);

            if ($dados) {
                if ($i > 1)
                    $string .= ',';
                $string .= "['".$ref."', ".count($dados['CLIENTE']).", ".count($dados['VINC']).", ".count($dados['NOT']).", ".count($dados['BLOCK']).", ".count($dados['CAN']).", '']";
            }
        }*/

        return $string;
    }

    function getLinhasCancelamento($ref) {
    	$retorno = array();
    	$aux = $this->db->select('det.ccid, det.ddd, det.linha, resposta.DATA')
    	->join('systems.resposta', 'resposta.ID = det.serial', 'left')
    	->where(array('det.id_cliente' => NULL, 'det.serial' => NULL, 'det.referencia' => "{$ref}"))
    	->get('showtecsystem.detalhamento_conta as det')
    	->result();

    	if ($aux) {
    		$aux1 = $this->db->select('det.ccid, det.ddd, det.linha, resposta.DATA')
    		->join('systems.resposta', 'resposta.ID = det.serial', 'left')
    		->where(array('det.id_cliente' => NULL, 'det.serial is NOT NULL' => NULL, 'det.referencia' => "{$ref}"))
    		->get('showtecsystem.detalhamento_conta as det')
    		->result();

    		$retorno = array_merge($aux, $aux1);

    		if ($retorno) {
    			foreach ($retorno as $key => $r) {
    				if ($r->DATA) {
    					if ($r->DATA > date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .'-3 days'))) {
    						unset($retorno[$key]);
    					}
    				}
    			}
    		}
    	}

    	return $retorno;
    }

    function cadLinhasCancelamento($data) {
    	return $this->db->insert_batch('showtecsystem.cancelamento_itens', $data);
    }

    function cadCancelamentoChips($data) {
    	if ($this->db->insert('showtecsystem.cad_cancelamentos', $data))
    		return $this->db->insert_id();
    	return FALSE;
    }

    function getSolicitCanByRef($ref) {
    	return $this->db->get_where('showtecsystem.cad_cancelamentos', array('referencia' => "{$ref}"))->result();
    }

    function lista_solicitacoes() {
    	return $this->db->select('cad.*, COUNT(itens.ccid) as quantidade')
    	->join('showtecsystem.cancelamento_itens as itens', 'itens.id_cancelamento = cad.id')
    	->group_by('itens.id_cancelamento')
    	->get('showtecsystem.cad_cancelamentos as cad')->result();
    }

    function getRelContas($ref, $tipo = false) {
    	$dados = array('NOT' => array(), 'VINC' => array(), 'CLIENTE' => array(), 'BLOCK' => array(), 'CAN' => array());

    	if ($tipo) {
    		if ($tipo == 's')
    			$where = array('det.id_cliente' => NULL, 'det.serial' => NULL, 'det.referencia' => "{$ref}");
    		elseif ($tipo == 'c')
    			$where = array('det.id_cliente' => NULL, 'det.serial !=' => NULL, 'det.referencia' => "{$ref}");
    		elseif ($tipo == 'u')
    			$where = array('det.id_cliente !=' => NULL, 'det.serial !=' => NULL, 'det.status_linha' => 'ativo', 'det.referencia' => "{$ref}");
    	} else {
    		$where = array('det.referencia' => "{$ref}");
    	}

    	$retorno = $this->db->select('det.*, cad_clientes.nome, cad_can.data_insert as data_block')
    	->join('showtecsystem.cad_clientes', 'cad_clientes.id = det.id_cliente', 'left')
    	//->join('systems.resposta', 'resposta.ID = det.serial', 'left')
    	->join('showtecsystem.cancelamento_itens as can', 'can.ccid = det.ccid', 'left')
    	->join('showtecsystem.cad_cancelamentos as cad_can', 'cad_can.id = can.id_cancelamento', 'left')
    	->where($where)
    	->get('showtecsystem.detalhamento_conta as det')
    	->result();

    	if ($retorno) {
    		foreach ($retorno as $r) {
    			if ($r->status_linha == 'bloqueado')
    				$dados['BLOCK'][] = $r;
    			elseif ($r->status_linha == 'cancelado')
    				$dados['CAN'][] = $r;
    			elseif (!$r->id_cliente && !$r->serial)
    				$dados['NOT'][] = $r;
    			elseif (!$r->id_cliente && $r->serial)
    				$dados['VINC'][] = $r;
    			else
    				$dados['CLIENTE'][] = $r;
    		}
    	}

    	return $dados;
    }

    function listReferenciasContas() {
    	$result = $this->db->select('referencia')->group_by('referencia')->get('showtecsystem.detalhamento_conta')->result();

    	if ($result)
    		return $result;
    	return array();
    }

    function verificaChipImport($ccid, $linha, $status, $conta) {
    	if ($status == 'ativo') $status = 1;
    	else if($status == 'bloqueado') $status = 2;
    	else if($status == 'cancelado') $status = 3;

    	if ($result = $this->db->get_where('showtecsystem.cad_chips', array('ccid' => "{$ccid}"))->row()) {
    		if ($result->numero != $linha) {
    			if ($result->status == $status) {
    				if ($resultados = $this->db->get_where('showtecsystem.cad_chips', array('numero' => $linha))->result()) {
    					foreach ($resultados as $resultado) {
    						$this->db->where('id', $resultado->id)->update('showtecsystem.cad_chips', array('numero' => NULL));
    					}
    				}

    				return $this->db->where('ccid', $ccid)
    				->update('showtecsystem.cad_chips', array('numero' => $linha));
    			} else {
    				if ($resultados = $this->db->get_where('showtecsystem.cad_chips', array('numero' => $linha))->result()) {
    					foreach ($resultados as $resultado) {
    						$this->db->where('id', $resultado->id)->update('showtecsystem.cad_chips', array('numero' => NULL));
    					}
    				}

    				return $this->db->where('ccid', $ccid)
    				->update('showtecsystem.cad_chips', array('numero' => $linha, 'status' => $status));
    			}
    		} else if ($result->status != $status) {
    			return $this->db->where('ccid', $ccid)
    			->update('showtecsystem.cad_chips', array('status' => $status));
    		}
    	} else {
    		if ($resultados = $this->db->get_where('showtecsystem.cad_chips', array('numero' => $linha))->result()) {
    			foreach ($resultados as $resultado) {
    				$this->db->where('id', $resultado->id)->update('showtecsystem.cad_chips', array('numero' => NULL));
    			}
    		}

    		$data = array(
    			'ccid' => $ccid,
    			'numero' => $linha,
    			'operadora' => 'VIVO',
    			'custo_mes' => '0.00',
    			'mb_mes' => 0,
    			'status' => $status,
    			'conta' => $conta
    		);
    		return $this->db->insert('showtecsystem.cad_chips', $data);
    	}
    }

	/* Função processar envio das linhas para Mikrotik
	 * by Luciano
	 *
	 */

	public function processar($pasta, $arquivo) {

		$usuarios = $this->get_usuarios_mikrotik();

		$full_path = $pasta . '/' . $arquivo;

		if (file_exists($full_path)) {

			$lendo = @fopen($full_path, "r");
			if (!$lendo) {
				throw new Exception('Erro: Não foi possível processar o arquivo. Por favor tente novamente.');
			}

			$add = array();
			$nadd = array();
			$ln = array();

			while (!feof($lendo)) {

				$x = array("'", '"'); // retira os caracteres da linha a ser lidas
				$reg = str_replace($x, '', fgets($lendo, 9999));

				if (trim($reg) != '') {

					$ln = explode(";", trim($reg));

					$linha = $ln[0]; // numero da linha conforme está no arquivo

					$linha_ = $this->validalinha($linha); // retorna numero da linha os 10 primeiros caracteres
					if ($linha_) {
						$busca = $this->searchForId($linha_, $usuarios);
						if (!$busca) {
							array_push($add, $linha_); // enviara para o mikrotik
						} else {
							array_push($nadd, $linha_); // nao enviará para o mikrotik
						}
						$this->salvar_db($ln); // salva no banco SHOWTEC tabela de cad_chips
					} else {
						array_push($nadd, $linha_); // nao enviará para o mikrotik
					}
				}
			}

			$this->session->set_flashdata('msg_erros', $this->MSG_erros);

			if ($add) {
				// prepara o arquivo o Mikrotik fazer download
				$this->prepara_file($add, $full_path);
				// envia um scrypt para o Mikrotik que fará o download
				$this->enviar_scrypt_mikrotik($this->Host_Servidor, 'show/financeiro-show/newapp/' . $full_path, 'getLinha');
				// executa o scrypti do download
				$this->run_scrypt_mikrotik('getLinha');
				// pega os usuarios ppp depois da importação
				$this->importa_usuarios_mikrotik($arquivo);
				// prepara para os resultados dos enviados
				$this->session->set_flashdata('enviados', $add);
			}

			if ($nadd > 0) {
				// prepara para os resultados doa não enviados
				$this->session->set_flashdata('nenviados', $nadd);
			}

			// pega nova lista dos usuarios atuais
			$usuarios_atual = $this->get_usuarios_mikrotik();
			$lista = $this->listaLinha($usuarios_atual);
			// prepara para os resultados lista dos usuarios
			$this->session->set_flashdata('cadLinhas', $lista);

			fclose($this->Handle);
		}
	}

	/*
	 * Valida o numero da linha e retorno numero validado
	 * O numero da linha deve conter apenas 10 caracteres numericos
	 */

	function validalinha($linha) {
		if (is_numeric(substr(trim($linha), 0, 10))) {
			return substr(trim($linha), 0, 10);
		}
		return FALSE;
	}

	/*
	* Verifica se a linha esta cadastrada em outro equipamento.
	*/

	public function validaVinculo_linha($id_linha) {
		$where = array('id' => $id_linha);
		$result = $this->db->get('showtecsystem.cad_chips', $where);

		if ($result) {
			return false;
		} else {
			return true;
		}
	}

	/*
	 * Procura se existe o numero passado na lista retornada do Mikrotik
	 */

	function searchForId($id, $array) {

		foreach ($array as $key => $val) {
			if (isset($val[3])) {
				$linha = $val[3];
				if ($linha === ';;;') { // quando ha um comentario feito no usuario
					continue;
					$linha = $val[0];
				}

				if ($linha === $id) {
					return $key;
				}
			}
		}
		return null;
	}

	/*
	 * Faz uma array apenas com os numero da linha retornada do Mikrotik
	 */

	function listaLinha($array) {
		$lista = array();
		foreach ($array as $key => $val) {

			if (isset($val[3])) {
				$linha = $val[3];
				if ($linha === ';;;') { // quando ha um comentario feito no usuario
					continue;
					$linha = $val[0];
				}
				if ($this->validalinha($linha)) {
					array_push($lista, $linha);
				}
			}
		}
		return $lista;
	}

	/*
	 * Prepara um arquivo com a extenção .rsc para o Mikrotik fazer download
	 * Esse comando é para cadastrar novas linha ou seja novos usuarios ppp
	 * no Mikrotik
	 */

	function prepara_file($array = false, $arquivo = '') {

		if ($array) {
			$data = date('d-m-y h:i');
			$cab = "# $data enviado pelo sistema SHOWNET\n"
			. "# software id = 7KM3-7IIS \n"
			. "# by Luciano\n"
			. "# \n"
			. "### Inicio ###\n\n";
			$cmd = '/ppp secret' . "\n\n";
			$fim = "\n### FIM ###\n";
			$file = $arquivo . '.rsc';

			$dados = $cab . $cmd;

			if ($fp = fopen($file, 'w')) {
				fwrite($fp, $dados);

				foreach ($array as $key => $value) {
					$add = "add name=$value password=show profile=default-encryption\n";
					fwrite($fp, $add);
				}

				fwrite($fp, $fim);
				fclose($fp);
			}
		}
	}

	/*
	 * Se o numero da linha, CCID e Operadora estivem no arquivo passado pelo usuario
	 * sera salvo no banco de dados do SHOWTEC CAD_CHIPS
	 */

	function salvar_db($linha = array()) {
		if ($linha) {
			$linha_ = $this->validalinha($linha[0]);
			if (!$this->get_linhaDB(array('numero' => $linha_ ))) { // numero obrigatori
				if (isset($linha[1])) { // ccid obrigatorio
					if (isset($linha[2])) { // operadora obrigatorio
						if (!$this->get_linhaDB(array('ccid' => $linha[1]))) {
							$this->insert_linhaDB($linha);
						} else {
							$this->coleta_err("CCID ja Cadastrado linha => $linha_; CCID => $linha[1]");
						}
					} else {
						$this->coleta_err("Sem OPERADORA a linha => $linha_");
					}
				} else {
					$this->coleta_err("Sem CCID a linha => $linha_");
				}
			} else {
				$this->coleta_err("Linha ja cadastrada => $linha_");
			}
		}
	}

	public function listar($where=array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'DESC', $select = '*', $like = NULL) {

		$this->db->select($select);
		if ($like)
			$this->db->like($like);

		return $this->db->where($where)->order_by($campo_ordem, $ordem)->get('showtecsystem.cad_chips', $limite, $paginacao)->result();
	}

	/*
	 * Retorna o numero da linha pesquisada no banco de dados
	 */

	public function get_linhaDB($where) {

		$this->db->where($where);
		$query = $this->db->get('showtecsystem.cad_chips');
		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	public function update_linha($dados, $id_chip) {
		return $this->db->update('showtecsystem.cad_chips', $dados, array('id' => $id_chip));
	}

	public function log_linha($log) {
		return $this->db->insert('showtecsystem.log_linhas', $log);
	}

	/**
	 * LINHAS ATIVAS
	 */
	function linhas_ativas($where=array()) {
		return $this->db->from('showtecsystem.cad_chips')->where($where)->count_all_results();
	}

	public function insert_linhaDB($ln) {

		$linha = $ccid = $operadora = $custo = $mb = $equipamento = '';

		if (isset($ln[0])) {
			$linha = $ln[0];
		}

		if (isset($ln[1])) {
			$ccid = $ln[1];
			if (strlen($ccid) <> 20) {
				$this->coleta_err("CCID deve conter 20 caracteres linha => $ln[0], CCID => $ccid");
			}
		}

		if (isset($ln[2])) {
			$operadora = $ln[2];
		}

		if (isset($ln[3])) {
			$custo = $ln[3];
		}

		if (isset($ln[4])) {
			$mb = $ln[4];
		}

		if (isset($ln[5])) {
			$equipamento = $ln[5];
		}


		$dados_linha = array(
			'numero' => $linha,
			'ccid' => $ccid,
			'operadora' => $operadora,
			'custo_mes' => $custo,
			'mb_mes' => $mb,
			'id_equipamento' => $equipamento
		);

		$resposta_linha = $this->db->insert('showtecsystem.cad_chips', $dados_linha);
		$linha_id = $this->db->insert_id();
	}

	public function coleta_err($msg) {
		array_push($this->MSG_erros, $msg);
	}

	function desativa_err() {
		ini_set('display_errors', 'Off');
		error_reporting(0);
		define('MP_DB_DEBUG', false);
	}

	function ativa_err() {
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);
		//define('MP_DB_DEBUG', TRUE);
	}

	/* Funções de comandos com o Mikrotik
	 * by Luciano
	 *
	 */

	public function cmd_mikrotik($cmd = '/') {

		$this->Handle = false;
		$this->desativa_err();

		if (!($connection = ssh2_connect($this->Host_Mikrotik, 22))) {
			throw new Exception("Could not connect to $this->Host_Mikrotik on port 22.");
		} else {

			if (!ssh2_auth_password($connection, 'app_show', 'show123')) {
				throw new Exception("Falha na autenticação em $this->Host_Mikrotik on port 22.");
			} else {
				$this->Handle = ssh2_exec($connection, $cmd);
				stream_set_blocking($this->Handle, true);
			}
		}


		$this->ativa_err();
		return $this->Handle;
	}

	/* Pega todos usuarios ppp do Mikrotik
	 * Retorno:
	 *  8   8381252804                        any                                           show                                 default-encryption
	 *  9   8381010839                        any                                           show                                 default-encryption
	 * 10   8381070388                        any                                           show                                 default-encryption
	 * 11   8381168524                        any                                           show                                 default-encryption
	 *
	 *
	 */

	public function get_usuarios_mikrotik() {

		$usuarios = $this->cmd_mikrotik('ppp secret print');
		$lista = array();
		while ($line = fgets($usuarios)) {
			array_push($lista, explode(" ", trim($line)));
		}

		return $lista;
	}

	/*
	 *
	 */

	public function importa_usuarios_mikrotik($file) {

		// Remover arquivo do mikrotik
		$ret = $this->cmd_mikrotik("/import file-name=$file.rsc");
		return $ret;
	}

	/*
	 *
	 */

	public function remove_arquivo_mikrotik($file) {

		$ret = $this->cmd_mikrotik("/file remove $file.rsc");
		return $ret;
	}

	/*
	 *
	 */

	public function enviar_scrypt_mikrotik($endereco, $file, $nome) {

		$script = "/tool fetch address=$endereco src-path=$file.rsc mode=http;";
		// Remove scrypt existente
		$setScript = "system script remove $nome";
		$ret = $this->cmd_mikrotik($setScript);
		// Cria scrypt
		$setScript = 'system script add name=' . $nome . ' source="' . $script . '"';
		$ret = $this->cmd_mikrotik($setScript);

		return $ret;
	}

	/*
	 *
	 */

	public function run_scrypt_mikrotik($scrypt) {
		// Executa scrypt
		$runScript = "system script run $scrypt";
		$ret = $this->cmd_mikrotik($runScript);

		return $ret;
	}

	public function listar_linhas($limit, $offset) {
		$this->db->select('chip.*, equip.serial, equip.ultima_atualizacao_chip, clie.nome, equip.ccid as ccid_auto, clie_sim.nome as nome_sim, clie.informacoes as info');
		$this->db->from('showtecsystem.cad_chips as chip');
		$this->db->join('showtecsystem.cad_equipamentos as equip', 'equip.id = chip.id_equipamento', 'left');
		$this->db->join('systems.cadastro_veiculo as veic', 'veic.serial = equip.serial', 'left');
		$this->db->join('showtecsystem.cad_clientes as clie', 'clie.id = veic.id_usuario', 'left');
		$this->db->join('showtecsystem.cad_clientes as clie_sim', 'clie_sim.id = chip.id_cliente_sim2m', 'left');
		$this->db->order_by('chip.data_cadastro','desc');
		$this->db->limit($offset,$limit);
		$query = $this->db->get();

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	public function total_linhas() {
		return $this->db->count_all('showtecsystem.cad_chips');
	}

	function pesquisa_linha($pesquisa) {
		$this->db->where($pesquisa['coluna'], $pesquisa['palavra']);
		return $this->db->get('showtecsystem.cad_chips')->result();
	}

	function verificaVincChip($ccid) {
		return $this->db->select('eqp.serial')
		->from('showtecsystem.cad_chips as chip')
		->join('showtecsystem.cad_equipamentos as eqp', 'eqp.id = chip.id_equipamento', 'INNER')
		->where('chip.ccid', $ccid)
		->get()
		->row();

	}

	public function listar_pesquisa_linha($pesquisa) {
		if ($pesquisa['palavra']) {
			$this->db->select('chip.*, equip.serial, equip.ultima_atualizacao_chip, clie.nome, equip.ccid as ccid_auto, clie_sim.nome as nome_sim, clie.informacoes as info');
			$this->db->from('showtecsystem.cad_chips as chip');
			$this->db->join('showtecsystem.cad_equipamentos as equip', 'equip.id = chip.id_equipamento', 'left');
			$this->db->join('systems.cadastro_veiculo as veic', 'veic.serial = equip.serial', 'left');
			$this->db->join('showtecsystem.cad_clientes as clie', 'clie.id = veic.id_usuario', 'left');
			$this->db->join('showtecsystem.cad_clientes as clie_sim', 'clie_sim.id = chip.id_cliente_sim2m', 'left');
			$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);

			if ($pesquisa['coluna'] == 'clie.nome')
				$this->db->or_like('clie_sim.nome', $pesquisa['palavra']);

			$query = $this->db->get();

			if ($query->num_rows()) {
				return $query->result();
			}
		}

		return array();
	}


	public function getCad_chip($ano,$operadora,$empresa) {

		$this->db->select('chip.*, equip.serial, equip.ultima_atualizacao_chip, clie.nome, equip.ccid as ccid_auto, clie_sim.nome as nome_sim, clie.informacoes as info');
		$this->db->from('showtecsystem.cad_chips as chip');
		$this->db->join('showtecsystem.cad_equipamentos as equip', 'equip.id = chip.id_equipamento', 'left');
		$this->db->join('systems.cadastro_veiculo as veic', 'veic.serial = equip.serial', 'left');
		$this->db->join('showtecsystem.cad_clientes as clie', 'clie.id = veic.id_usuario', 'left');
		$this->db->join('showtecsystem.cad_clientes as clie_sim', 'clie_sim.id = chip.id_cliente_sim2m', 'left');
		$this->db->like('chip.data_cadastro', $ano);
		$this->db->where('chip.operadora',$operadora);
		if($empresa == true){
			$this->db->where('clie.nome is null');
		}else{
			$this->db->where("clie.nome is not null");
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	public function correcao() {
		$equipamentos = $this->db->get('showtecsystem.cad_equipamentos')->result();

		foreach ($equipamentos as $equipamento) {
			if ($equipamento->ccid) {
				if ($chip = $this->db->get_where('showtecsystem.cad_chips', array('ccid' => $equipamento->ccid))->row()) {
					if ($chip->id_equipamento != $equipamento->id) {
						$this->db->update('showtecsystem.cad_chips', array('id_equipamento' => NULL), array('id_equipamento' => $equipamento->id));
						$this->db->update('showtecsystem.cad_chips', array('id_equipamento' => $equipamento->id), array('id' => $chip->id));
						$this->db->update('showtecsystem.cad_equipamentos', array('id_chip' => $chip->id), array('id' => $equipamento->id));
					}
				}
			}
		}

		echo "FIM";
	}

	public function validar_chip($where) {
		$query = $this->db->get_where('showtecsystem.cad_chips', $where);

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	public function adicionar_linha($linha) {
		$dados = array(
			'ccid' => $linha['ccid'],
			'numero' => $linha['numero'],
			'conta' => $linha['conta'],
			'operadora' => $linha['operadora'],
			'data_cadastro' => date('Y-m-d H:i:s')
		);
		return $this->db->insert('showtecsystem.cad_chips', $dados);
	}

	// BUSCA ID DO EQUIPAMENTO PELO NUMERO DA LINHA - SAULO MENDES //
	public function buscaId_byLinha($linha) {
		$this->db->select('id');
		$this->db->where('numero', $linha);
		$query = $this->db->get('showtecsystem.cad_chips');
		$result = $query->result();

		if ($result) {
			return $result[0]->id;
		} else {
			return false;
		}
	}

	public function buscaCcid_byLinha($linha) {
		$this->db->select('ccid');
		$this->db->where('numero', $linha);
		$query = $this->db->get('showtecsystem.cad_chips');
		$result = $query->result();

		if ($result) {
			return $result[0]->ccid;
		} else {
			return false;
		}
	}

	public function getLinha_by_Id($id_linha) {
		return $this->db->get_where('showtecsystem.cad_chips', array('id' => $id_linha))->row();
	}

	public function atualizaLinha_equip($id_equipamento, $id_linha, $ccid) {
		$insert = array('id_chip' => $id_linha, 'ccid' => $ccid);
		$this->db->where('id', $id_equipamento);
		$update = $this->db->update('showtecsystem.cad_equipamentos', $insert);

		return $update;
	}

	public function atualiza_chip2_equip($id_equipamento, $id_chip2, $ccid) {
		$insert = array('id_chip_2' => $id_chip2, 'ccid' => $ccid);
		$this->db->where('id', $id_equipamento);
		$update = $this->db->update('showtecsystem.cad_equipamentos', $insert);

		return $update;
	}

	public function atualizaEquip_linha($id_equipamento, $id_linha) {
		$insert = array('id_equipamento' => $id_equipamento);
		$this->db->where('id', $id_linha);
		$update = $this->db->update('showtecsystem.cad_chips', $insert);

		return $update;
	}

	function desvinculaChipEqp($id_equipamento) {
		return $this->db->update('showtecsystem.cad_chips', array('id_equipamento' => NULL, 'status' => '0'), array('id_equipamento' => $id_equipamento));
	}

	/*
	* LISTAGEM DE CONTRATOS POR SERVE-SIDE
	*/
	public function listLinhasServeSide($select = '*', $start=0, $limit=10, $search = NULL, $draw = 1)
	{
		//SE FOI DIGITADO NO CAMPO DE BUSCA
        if ($search){

				//BUSCA PELO CCID, LINHA OU CONTA
				$this->db->select($select);
				$this->db->join('showtecsystem.cad_equipamentos as equip', 'equip.id = chip.id_equipamento', 'left');
				$this->db->join('systems.cadastro_veiculo as veic', 'veic.serial = equip.serial', 'left');
				$this->db->join('showtecsystem.cad_clientes as clie', 'clie.id = veic.id_usuario', 'left');

				if (is_numeric($search)) {
					//SE A BUSCA FOR UM NUMERO
					$this->db->or_where('chip.ccid', $search);
					$this->db->or_where('chip.numero', $search);
					$this->db->or_where('chip.conta', $search);
				} else {
					//SE A BUSCA FOR UM NOME
					$this->db->like('clie.nome', $search);
					$this->db->or_like('clie_sim.nome', $search);
				}

				$this->db->join('showtecsystem.cad_clientes as clie_sim', 'clie_sim.id = chip.id_cliente_sim2m', 'left');
				$this->db->order_by('chip.data_cadastro', 'desc');
				$query = $this->db->get('showtecsystem.cad_chips as chip', $limit, $start);


        } else {
			//retorna todos os contratos do cliente
			$query = $this->db->select($select)
			->join('showtecsystem.cad_equipamentos as equip', 'equip.id = chip.id_equipamento', 'left')
			->join('systems.cadastro_veiculo as veic', 'veic.serial = equip.serial', 'left')
			->join('showtecsystem.cad_clientes as clie', 'clie.id = veic.id_usuario', 'left')
			->join('showtecsystem.cad_clientes as clie_sim', 'clie_sim.id = chip.id_cliente_sim2m', 'left')
			->order_by('chip.data_cadastro', 'desc')
			->get('showtecsystem.cad_chips as chip', $limit, $start);
        }

		if($query->num_rows()>0){
			$dados['linhas'] = $query->result(); # Lista de contratos
			$dados['recordsTotal'] = $this->db->count_all_results('showtecsystem.cad_chips'); # Total de registros
			$dados['recordsFiltered'] = $dados['recordsTotal']; # atribui o mesmo valor do recordsTotal ao recordsFiltered para que tivesse todas as paginas na datatable
			// $dados['recordsFiltered'] = $query->num_rows(); # Total de registros Filtrados
	        $dados['draw'] = $draw++; # Draw do datatable

			return $dados;
		}

		return false;
    }

	//RETORNA TODAS OS CHIPS
	public function listNumerosCcids( $select='*') {
		$query = $this->db->select($select)->order_by('id', 'desc')->get('showtecsystem.cad_chips');

		if ($query->num_rows()) {
			return $query->result();
		}
		return false;
	}

	/*
    *  SALVA DADOS DE CHIPS EM LOTE, RETORNA A QUANTIDADE DE CHIPS CADASTRADOS
    */
	public function insertChipBatchString ($data=array(), $ignore = false) {
	    $sql = '';
	    if (!empty($data)) {
	        $linhas = array();

	        foreach ($data as $row) {
	            $insert_string = $this->db->insert_string('showtecsystem.cad_chips', $row);
	            if (empty($linhas) && $sql == '')
	                $sql = substr($insert_string, 0, stripos($insert_string, 'VALUES'));

	            $linhas[] = trim(substr($insert_string, stripos($insert_string, 'VALUES') + 6));
	        }
	        $sql.= 'VALUES' .implode (',', $linhas);

	        if ($ignore) $sql = str_ireplace('INSERT INTO', 'INSERT IGNORE INTO', $sql);

			$this->db->query($sql);
			return $this->db->affected_rows();
	    }
	    return false;
	}

	public function getLinhasCliente($id_usuario){
		//STATUS: 0-CADASTRADO, 1-HABILITADO, 2-EM USO
        $query = $this->db->select('chips.numero, gestor.usuario, gestor.nome_usuario')
            ->join('showtecsystem.usuario_gestor as gestor', 'gestor.id_cliente = chips.id_cliente_sim2m')
            ->where('gestor.code', $id_usuario)
			->where_in('chips.status', array('0','1','2'))
            ->order_by('chips.id', 'ASC')
            ->get('showtecsystem.cad_chips as chips');

        if($query->num_rows()>0){
            return $query->result();
        }
        return false;
    }

	/*
	* RETORNA OS DADOS DOS CHIPS QUE FAZEM PARTE DE UM CONJUNTO DE CCIDS
	*/
	public function getLinhasPorCCIDs($listaCCIDs, $select='*'){
        $query = $this->db->select($select)->where_in('ccid', $listaCCIDs)->get('showtecsystem.cad_chips');
        return $query->num_rows() > 0 ? $query->result() : false;
    }

	/*
	* RETORNA OS DADOS DOS CHIPS QUE FAZEM PARTE DE UM CONJUNTO DE NUMEROS(LINHAS)
	*/
	public function getLinhasPorNUMEROs($listaNumeros, $select='*'){
        $query = $this->db->select($select)->where_in('numero', $listaNumeros)->get('showtecsystem.cad_chips');
        return $query->num_rows() > 0 ? $query->result() : false;
    }

	/*
	* RETORNA OS DADOS DOS CHIPS QUE FAZEM PARTE DE UM CONJUNTO DE IDS DE CHIPS
	*/
	public function getLinhasPorIDs($listaIds, $select='*'){
        $query = $this->db->select($select)->where_in('id', $listaIds)->get('showtecsystem.cad_chips');
        return $query->num_rows() > 0 ? $query->result() : false;
    }


	//RETORNA A LINHA PELO NUMERO DA LINHA - GESTÃO DE CHIPS
	public function getLinhaId($linha){
		$linhaId = $this->db->select('id')->
			from('ERP.cadastro_linha_operadora')->
			where('linha', $linha)->
			get()->result_array();

		return $linhaId;
    }

	public function get($where) {
		return $this->db->get_where('ERP.cadastro_linha_operadora', $where)->row();
	}

	public function getLogStatus($where) {
		$this->db->select('status');
		return $this->db->get_where('ERP.cadastro_linha_operadora', $where)->row();
	}
}
