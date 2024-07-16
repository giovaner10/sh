<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logistica extends CI_Model {
	private $db;
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database('default', true);
	}

	function estornar($id_log){
	    return $this->db->where('idlogistica', $id_log)->update('showtecsystem.cad_logistica', array('statusOS' => '9'));
    }

	public function listarOrdem() {
		$this->db->select('log.*, eq.id, eq.marca, eq.modelo, eq.serial, eq.id_chip,
						eq.status, eq.ccid, eq.data_cadastro, clie.nome as nome_clie, tec.nome as nome_tec,
						tec.sobrenome as sobrenome_tec');
		$this->db->join('cad_equipamentos as eq','eq.id = log.equipamento', 'INNER');
		$this->db->join('cad_clientes as clie', 'clie.id = log.cliente', 'LEFT');
		$this->db->join('showtecsystem.instaladores as tec', 'tec.id = log.tecnico', 'LEFT');
		$this->db->order_by('log.idlogistica', 'DESC');
		$query = $this->db->get('cad_logistica as log');
		
		return $query->result();
	}
	
	public function eqpPaginated($start = 0, $limit = 20, $search = NULL, $draw = 1){
		if ($search) {
			$sql = "SELECT cad_logistica.*, eq.id, eq.marca, eq.modelo, eq.serial, eq.id_chip, eq.status, eq.ccid, eq.data_cadastro, clie.nome as nome_clie, tec.nome as nome_tec, tec.sobrenome as sobrenome_tec
			FROM showtecsystem.cad_logistica
			INNER JOIN showtecsystem.cad_equipamentos as eq ON eq.id = cad_logistica.equipamento
			LEFT JOIN showtecsystem.cad_clientes as clie ON clie.id = cad_logistica.cliente
			LEFT JOIN showtecsystem.instaladores as tec ON tec.id = cad_logistica.tecnico
			WHERE equipamento like '%".$search."%' or solicitante like '%".$search."%' or placaOrdem like '%".$search."%' or clie.nome like '%".$search."%'
			ORDER BY cad_logistica.idlogistica DESC";
			
        }else {
            $sql = "SELECT cad_logistica.*, eq.id, eq.marca, eq.modelo, eq.serial, eq.id_chip, eq.status, eq.ccid, eq.data_cadastro, clie.nome as nome_clie, tec.nome as nome_tec, tec.sobrenome as sobrenome_tec
			FROM showtecsystem.cad_logistica
			INNER JOIN showtecsystem.cad_equipamentos as eq ON eq.id = cad_logistica.equipamento
			LEFT JOIN showtecsystem.cad_clientes as clie ON clie.id = cad_logistica.cliente
			LEFT JOIN showtecsystem.instaladores as tec ON tec.id = cad_logistica.tecnico
			ORDER BY cad_logistica.idlogistica DESC";
        }
		
        $data['recordsTotal'] = $this->db->get('cad_logistica')->num_rows(); # Total de registros
        $data['recordsFiltered'] = $this->db->query($sql.';')->num_rows; # Total de registros Filtrados
		$clients = $this->db->query($sql.' LIMIT '.$start.', '.$limit.';')->result(); # Lista de Clientes

        $data['draw'] = $draw+1; # Draw do datatable
		$data['data'] = array(); # Cria Array de registros
		
		$dados = array();
		$i = 0;
		
		foreach ($clients as $key => $dados) {
			if ($dados->cliente) {
 				if ($dados->nome_clie) {
					$pessoa = '<b title='.$dados->nome_clie.'>Cli.</b> '.$dados->nome_clie;
 				} else {
 					$pessoa = 'Cód. Cliente: '.$dados->cliente;
 				}

 			} else {
 				if ($dados->nome_tec) {
					$pessoa = '<b title='.$dados->nome_tec.'>Téc.</b> '.$dados->nome_tec.' '.$dados->sobrenome_tec;
 				} else {
 					$pessoa = 'Cód. Instalador: '.$dados->tecnico;
 				}
			}

			// TRATA TIPO OS //
			if ($dados->tipoOS == 1) $tipoOS = 'Manutenção';
            elseif ($dados->tipoOS == 2) $tipoOS = 'Instalação';
			else $tipoOS = 'Outro';

			// DESABILITA BTN'S
			$first_btn = array("disabled", "true");
			$second_btn = array("disabled", "true");
			$tecParaCli = "";
			$upload = "";

			// TRATA STATUS DA OS
			switch ($dados->statusOS) {
				case '0':
					$estado = 'Bloqueado';
					break;
				case '1':
					$estado = 'Configurado';
					break;
				case '2':
					$estado = 'Em teste';
					break;
				case '3':
					$estado ='Manutenção'; #chegou do cliente ou técnico, fica em estado de manutenção
					break;
				case '4':
					$estado = 'Enviado ao cliente';
					$upload = 7;
					$first_btn = array("", "false");
					break;
				case '5':
					$estado = 'Em uso (instalado)';
					break;
				case '6':
					$estado = 'Enviado ao Instalador';
					$upload = 8;
					$first_btn = array("", "false");
					break;
				case '7':
					$estado ='Posse do cliente';
					$tecParaCli = 0;
					$second_btn = array("", "false");
					break;
				case '8':
					$estado ='Posse do técnico';
					$tecParaCli = 1;
					$second_btn = array("", "false");
					break;
				case '9':
					$estado ='Estornado';
					break;
				default:
					$estado = '';
					break;
            }

			if ($chips = $this->get_chips($dados->equipamento)) {
				$inforChip = $num = $chips->numero;
				$inforOperadora = $chips->operadora;
			} else {
				$inforOperadora = $inforChip = "Sem número";
			}

			// Botões de administrar //
			$botaoDataChegada = '<a id="um" type="button"  data-toggle="modal" data-id="'.$dados->idlogistica.'" data-serial="'.$dados->serial.'" data-lastdate="'.date('Y-m-d', strtotime($dados->dataEnvio)).'" data-upload="'.$upload.'" data-target="#modal_ordem_11" title="Assinalar data de chegada" aria-disabled="'.$first_btn[1].'"  style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Assinalar Chegada</a>';
			$botaoConfirmarInstalacao = '<a id="dois" type="button"  data-toggle="modal" data-id="'.$dados->idlogistica.'" data-serial="'.$dados->serial.'" data-lastdate="'.date('Y-m-d', strtotime($dados->dataRecebimento)).'" data-oldserial="dataEnvio" data-tecparacli="'.$tecParaCli.'" data-target="#modal_ordem_2'.$dados->tipoOS.'" title="Assinalar instalação" aria-disabled="'.$second_btn[1].'" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Confirmar instalação</a>';	
			$botaoVerOrdem = '<a type="button"  href="gerencia_equipamentos/verEquip/?origem='.$dados->idlogistica.'" target="_blank" title="Detalhar ordem">Detalhar ordem</a>';
			if ($dados->statusOS == '5' || $dados->statusOS == '9')
                $botaoDevolucao = '<a type="button" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" title="Estornar Equipamento" disabled="disabled">Estornar Equipamento</a>';
            else
                $botaoDevolucao = '<a id="buttonRet'.$dados->idlogistica.'" type="button" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" onclick="devolve('.$dados->idlogistica.')">Estornar Equipamento</a>';

			$result[$i] = array('id' => $dados->idlogistica,
								'modelo' => $dados->modelo,
								'serial' => $dados->serial,
								'linha' => $inforChip,
								'operadora' => $inforOperadora,
								'placa' => ($dados->placaOrdem != null ? $dados->placaOrdem : 'Não informada'),
								//'estado' => '<span id="status'.$dados->idlogistica.'">'.$estado.'</span>',
								'estado' => $estado,
								'solicitante' => $dados->solicitante,
								'os' => $tipoOS,
								'data' => date('d/m/Y' , strtotime($dados->dataEnvio)),
								'destino' => $pessoa,
								'tipoDeEnvio' => ($dados->tipoEnvio==1 ? 'Correio' : ($dados->tipoEnvio==2 ? 'Tam Cargo' : 'Outro')), #correio, tamCargo, outro..
								'rastreio' => '<a href="gerencia_equipamentos/rastreio_correios/'.$dados->inforTipo.'/" target="_blank" role="button" data-toggle="modal" data-code="'.$dados->inforTipo.'">'.$dados->inforTipo.'</a>',
								'gerenciar' => "<nobr>$botaoDataChegada $botaoConfirmarInstalacao $botaoVerOrdem $botaoDevolucao</nobr>",
								'botaoDataChegada' => $botaoDataChegada,
								'botaoConfirmarInstalacao' => $botaoConfirmarInstalacao,
								'botaoVerOrdem' => $botaoVerOrdem,
								'botaoDevolucao' => $botaoDevolucao,
							);
			$i++;
		}
		$output = array(
			"draw" => $data['draw'],
        	"iTotalRecords" => $data['recordsTotal'],
        	"iTotalDisplayRecords" => $data['recordsFiltered'],
			"aaData" => $result,
        );
		echo json_encode($output);
	}

    public function get_chips($id_eqp) {
        return $this->db->get_where('cad_chips', array('id_equipamento' => $id_eqp))->row();
    }

	public function getOrdem($coluna, $chave) {
		$this->db->where($coluna, $chave);
		$this->db->join('cad_equipamentos','cad_equipamentos.id = cad_logistica.equipamento');
		$query = $this->db->get('cad_logistica');
		if ($query->num_rows()!=0) {
			return $query->result();
		}else{
			return FALSE;
		}	
	}
	public function getSame($ordem) {
		$this->db->select('idlogistica, cad_equipamentos.serial as equipamento')->where("solicitante = '".$ordem->solicitante."' and tipoOS = ".$ordem->tipoOS." and statusOS = ".$ordem->statusOS." and infors = '".$ordem->infors."' and tipoEnvio = ".$ordem->tipoEnvio." and inforTipo = '".$ordem->inforTipo."' and dataEnvio = '".$ordem->dataEnvio."'");
		$this->db->join('cad_equipamentos','cad_equipamentos.id = cad_logistica.equipamento');
		$query = $this->db->get('cad_logistica');
		if ($query->num_rows()!=0) {
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function inserirOrdem($dados) {
		$this->db->insert('cad_logistica', $dados);
		return $this->db->insert_id();
	}

	# BUSCA ID DO EQUIPAMENTO ENVIADO ATRAVÉS DO ID DA ORDEM
	public function buscaIdEqp_byIdLog($id_log) {
		$this->db->select('equipamento');
		$this->db->where('idlogistica', $id_log);
		$query = $this->db->get('showtecsystem.cad_logistica')->result();

		return $query[0]->equipamento;
	}

	public function atualizarOrdem($id, $ordem){
		$this->db->where('idlogistica', $id);
		return $this->db->update('showtecsystem.cad_logistica', $ordem);
	}

	public function contaOrdem ($where = ''){
		$this->db->from('showtecsystem.cad_logistica');
		if ($where != ''){
			$this->db->where($where);
		}
		return $this->db->count_all_results();
	}

	// METODOS DESENVOLVIDOS POR SAULO MENDES - EQUIPAMENTOS POR CLIENTE(SHOWNET) //
	public function equipSubst($id_cliente) {
		$this->db->select('eq.serial, log.dataEnvio as data_retirada, log.dataRecebidoRetorno as dataRecebimento, log.placaOrdem as placa');
		$this->db->distinct('veic.placa');
		$this->db->from('showtecsystem.os as os');
		$this->db->join('showtecsystem.os_equipamentos as eq', 'eq.id_os = os.id', 'INNER');
		$this->db->join('showtecsystem.cad_logistica as log', 'eq.id_equipamento = log.equipamento', 'INNER');
		$this->db->where('os.id_cliente', $id_cliente);
		$this->db->where('os.tipo_os', 2);
		$this->db->where('eq.serial_retirado IS NOT NULL', null, false);
		$query = $this->db->get();

			return $query->result();
	}

	public function listar_veiculos_instalados($id_cliente) {

	    $retorno = array();

	    $this->db->select('plac.serial, veic.placa, veic.status, veic.data_cadastro, chip.ccid, chip.numero, chip.operadora');
	    $this->db->distinct('plac.placa');
	    $this->db->from('showtecsystem.contratos_veiculos as veic');
	    $this->db->join('systems.cadastro_veiculo as plac','veic.placa = plac.placa', 'INNER');
	    $this->db->join('showtecsystem.cad_equipamentos as eq', 'plac.serial = eq.serial', 'INNER');
	    $this->db->join('showtecsystem.cad_chips as chip', 'eq.ccid = chip.ccid', 'left');
	    $this->db->where('veic.id_cliente', $id_cliente);
	   	$this->db->where_in('veic.status', 'ativo');
	    $query = $this->db->get();
	    
	        return $query->result();
	}

	public function equipDisponivel($id_cliente) {
		$this->db->select('eq.serial, log.dataEnvio, log.dataRecebimento, log.placaOrdem as placa, c_veic.status, chip.ccid, chip.numero, chip.operadora');
		$this->db->distinct('veic.placa');
		$this->db->from('showtecsystem.cad_logistica as log');
		$this->db->join('showtecsystem.cad_equipamentos as eq', 'log.equipamento = eq.id', 'INNER');
		$this->db->join('systems.cadastro_veiculo as veic', 'eq.serial = veic.serial', 'left');
		$this->db->join('showtecsystem.contratos_veiculos as c_veic', 'veic.placa = c_veic.placa', 'left');
		$this->db->join('showtecsystem.cad_chips as chip', 'eq.id_chip = chip.id', 'left');
		$this->db->where('log.cliente', $id_cliente);
		$this->db->where('log.statusOS !=', 9);
		$this->db->where('log.dataInstalacao', null);
		$query = $this->db->get();

			return $query->result();
	}

	public function equipRetirados($id_cliente) {
		$this->db->select('eq.placa, eq.serial, os.data_inicial as data_retirada, log.dataRecebimento');
		$this->db->from('showtecsystem.os as os');
		$this->db->join('showtecsystem.os_equipamentos as eq', 'eq.id_os = os.id', 'INNER');
		$this->db->join('showtecsystem.cad_logistica as log', 'eq.id_equipamento = log.equipamento', 'INNER');
		$this->db->where('os.id_cliente', $id_cliente);
		$this->db->where('os.tipo_os', 3);
		$query = $this->db->get();

			return $query->result();
	}

	// RASTREIO DE ENVIO VIA CORREIOS - LOGISTICA (SAULO MENDES) //
	public function request_correios($codigo) {
		$post = array('Objetos' => $codigo);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($post));
		// Acessar a URL e retornar a saída
		$output = curl_exec($ch);
		// liberar
		curl_close($ch);

		return $output;
	}

	# BUSCA A QUANTIDADE DE REGISTROS (PAGINAÇÃO)
	public function get_count_pag() {
		$query = $this->db->count_all('showtecsystem.cadastro_correspondencia');

		return $query;
	}

	# LISTA MOVIMENTAÇÕES COM PAGINAÇÃO
	public function lista_mov($pg_atual = 0, $qntd = 100, $tipo = '') {
		if ($tipo == 'rec') {
			$this->db->where('tipo_movimentacao', 1);
		} elseif ($tipo == 'env') {
			$this->db->where('tipo_movimentacao', 0);
		}
		$this->db->limit($qntd, $pg_atual);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('showtecsystem.cadastro_correspondencia');

		return $query->result();
	}

	# CAMPO PESQUISA
	public function filtrar_corresp_by_id($coluna, $campo) {
		if ($coluna == 'dest') {
			$pesq = $this->getId_Dest($campo);
			$this->db->where('id_destinatario', $pesq);
		} else { 
			$this->db->where($coluna, $campo);
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('showtecsystem.cadastro_correspondencia');

		return $query->result();
	}

	# BUSCA O NOME DO DESTINATÁRIO
	public function get_name_dest($id_dest) {
		$this->db->where('id', $id_dest);
		$query = $this->db->get('showtecsystem.destinatario')->result();

		return $query[0]->nome;
	}

	# BUSCA ID DO DESTINATÁRIO
	public function getId_Dest($nome_dest) {
		$this->db->where('nome', $nome_dest);
		$query = $this->db->get('showtecsystem.destinatario')->result();

		return $query[0]->id;
	}

	# LISTA DESTINATARIOS
	public function get_destinatarios(){
		$query = $this->db->get('showtecsystem.destinatario');

		return $query->result();
	}

	# CADASTRO DE MOVIMENTAÇÃO - CORRESPONDÊNCIAS
	public function insert_mov($dados) {
		$query = $this->db->insert('showtecsystem.cadastro_correspondencia', $dados);

		return $query;
	}

	# CADASTRO DE DESTINATARIOS - CORRESPONDÊNCIAS
	public function cadastrar_dest($dados) {
		$query = $this->db->insert('showtecsystem.destinatario', $dados);

		return $query;
	}

	# ATUALIZA STATUS DA CORRESPONDENCIA
	public function atualizar_corresp($id, $insert) {
		$this->db->where('id', $id);
		$query = $this->db->update('showtecsystem.cadastro_correspondencia', $insert);

		return $query;
	}

	// ---- SAULO MENDES | SOLICITAÇÕES DE EQUIPAMENTOS ----- //

    /*
     * BUSCA TODOS OS REGISTROS DE SOLICITAÇÕES REALIZADAS
     */
    public function get_all_solicitacoes() {
        $query = $this->db->order_by('ID', "DESC")
            ->get('systems.solicitacao_equipamento')->result();

        if (count($query)) {
            return $query;
        }
        return array();
    }

    // ------------------------ FIM ------------------------- //
}
