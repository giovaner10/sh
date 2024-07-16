<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gestor_vendasmodel extends CI_Model {
	private $db;

    public function __construct()
    {
       	parent::__construct();
       	$this->db = $this->load->database('aurora', TRUE);
		ini_set("memory_limit","5012M");
    }

	private function updatedOnNegocio($id) {
		$this->db->query("UPDATE gestorvendas.negocio SET updated_on = NOW() WHERE id = {$id};");
	}

	function gain($data) {
		return $this->db->query("UPDATE gestorvendas.negocio SET is_gain = '1', updated_on = NOW() WHERE id = {$data['id']};");
	}

	function lost($data) {
		$sql = "UPDATE gestorvendas.negocio SET 
					is_gain = '0', updated_on = NOW(), motive = '{$data['motive']}', comment = '{$data['comment']}'
				WHERE id = {$data['id']};";
		return $this->db->query( $sql );
	}

	function addNotation($data) {
		$this->saveHistoric("Nota criada", $data['id']);
		$data['nota'] = str_replace('\n', ' ', $data['nota']);
		return $this->db->query("INSERT INTO gestorvendas.notation (text, business_id) VALUES ('{$data['nota']}', {$data['id']});");
	}
	function addNotation2($data) {
		$this->db->db_debug = FALSE;
		return $this->db->insert("gestorvendas.notation",$data);
	}
	function addAgend($data) {
		$this->saveHistoric("Atividade {$data['activity']} criada", $data['id']);
		$this->updatedOnNegocio($data['id']);
		$sql = "INSERT INTO gestorvendas.agend (type, activity, date, hour, duration, notation, realized, business_id, user_id)
		VALUES ('{$data['type']}', '{$data['activity']}', '{$data['date']}', '{$data['hour']}', '{$data['duration']}', '{$data['notation']}', '{$data['realized']}', {$data['id']}, {$data['user_id']});";
		return $this->db->query($sql);
	}

	function migrar_usuario($usuario_antigo,$novo_usuario){
		if($usuario_antigo&&$novo_usuario){
			$this->db->where('user_id',$usuario_antigo)->update('gestorvendas.negocio',array('user_id'=>$novo_usuario));
		}
	}

	function checkedAgend($data) {
		$this->updatedOnNegocio($data['id']);
		if ($data['boo'] == 'true')
			return $this->db->query("UPDATE gestorvendas.agend SET realized = '1' WHERE id = {$data['id']};");
		else
			return $this->db->query("UPDATE gestorvendas.agend SET realized = '0' WHERE id = {$data['id']};");
	}

	function getHistoric($data) {
		$array = array();
		$array['historic'] = $this->db->query("SELECT * FROM gestorvendas.historic WHERE business_id = {$data['id']} ORDER BY created_on DESC LIMIT 50;")->result();
		$array['agend'] = $this->db->query("SELECT * FROM gestorvendas.agend WHERE business_id = {$data['id']} ORDER BY date DESC LIMIT 50;")->result();
		$array['notation'] = $this->db->query("SELECT * FROM gestorvendas.notation WHERE business_id = {$data['id']} ORDER BY created_on DESC LIMIT 50;")->result();
		return $array;
	}

	//

	function diffEtapaBusiness($data) {
		return $this->db->query("
					SELECT	
						IF(days, IF(date_end, days, days + DATEDIFF(now(), date_init)),
						IF(date_end, DATEDIFF(date_end, date_init), DATEDIFF(NOW(), date_init)) ) AS diff,
						DATEDIFF(NOW(), date_init) AS diffStagnado
					FROM 
						gestorvendas.etapa_business 
					WHERE 
						business_id = {$data['business_id']} AND etapa_id = {$data['etapa_id']} AND status=1;")->result();
	}

	private function deleteBusinessEtapa($data) {
		return $this->db->query("UPDATE gestorvendas.etapa_business SET status=0 WHERE business_id = {$data['id']} AND etapa_id = {$data['etapa_id']};");
	}

	private function saveTimeBusinessEtapa($business_id, $etapa_id) {
		$this->db->query("INSERT INTO gestorvendas.etapa_business (business_id, etapa_id,status) VALUES ({$business_id}, {$etapa_id},1);");
	}

	private function isBusinessEtapa($id, $etapa_id) {
		return $this->db->query("SELECT id FROM gestorvendas.etapa_business WHERE business_id = {$id} AND etapa_id = {$etapa_id} AND status=1;")->result();
	}

	private function updateDateDiff($id, $etapa_old) {
		$this->db->query("UPDATE gestorvendas.etapa_business SET date_end = NOW(), date_ax_end = NOW(),
					days = (IF(days, days, 0) + IF(date_end, 
					DATEDIFF(date_end, date_init), 
					DATEDIFF(now(), date_init)))
				WHERE business_id = {$id} AND etapa_id = {$etapa_old};");
	}

	private function editSaveTimeBusinessEtapa($etapa_id, $id, $etapa_old) {
		if ($this->isBusinessEtapa($id, $etapa_id)) {
			$this->updateDateDiff($id, $etapa_old);
			$this->db->query("UPDATE gestorvendas.etapa_business SET date_end = NULL, date_init = NOW() WHERE business_id = {$id} AND etapa_id = {$etapa_id};");
		} else {
			$this->updateDateDiff($id, $etapa_old);
			$this->saveTimeBusinessEtapa($id, $etapa_id);
		}
	}

	private function saveHistoric($str, $id) {
		$this->db->query("INSERT INTO gestorvendas.historic ( description, business_id ) VALUES ( '{$str}', {$id} );");
	}

	function saveBusiness($data) {
		$sql = 'INSERT INTO gestorvendas.negocio ( name_contact, company, title, value, coin,
					funil_id, etapa_id, date, phone_company, email_company, phone_contact,
					email_contact, type_phone, type_email, cnpj, provider, segmento, street, number,
					city, state, district, status, user_id
				) VALUES (
				\''.$data['name_contact'].'\',\''.$data['company'].'\',
				\''.$data['title'].'\',\''.$data['value'].'\',
				\''.$data['coin'].'\','.$data['funil_id'].',
				'.$data['etapa_id'].',\''.$data['date'].'\',
				\''.$data['phone_company'].'\',\''.$data['email_company'].'\',
				\''.$data['phone_contact'].'\',\''.$data['email_contact'].'\',
				\''.$data['type_phone'].'\',\''.$data['type_email'].'\',
				\''.$data['cnpj'].'\',\''.$data['provider'].'\',\''.$data['segmento'].'\',
				\''.$data['street'].'\',\''.$data['number'].'\',
				\''.$data['city'].'\',\''.$data['state'].'\',
				\''.$data['district'].'\',1,\''.$data['user_id'].'\'
			);';
		$ax = $this->db->query($sql);
		$id = $this->db->insert_id();
		$this->saveHistoric('Negócio '.$data['title'].' criando na etapa → '.$data['etapa_name'], $id);
		$this->saveTimeBusinessEtapa($id, $data['etapa_id']);
		return $ax;
	}

	function editBusinessEtapa($data) {
		$str = 'Negócio '.$data['business'].' movido da Etapa: '.$data['etapa_old_name'].' → '.$data['etapa_name'];
		$this->saveHistoric($str, $data['id']);
		$this->editSaveTimeBusinessEtapa($data['etapa_id'], $data['id'], $data['etapa_old']); 
		return $this->db->query("UPDATE gestorvendas.negocio SET etapa_id = {$data['etapa_id']}, updated_on = NOW() WHERE id = {$data['id']};");
	}
	function getContatos($id){
		return $this->db->where('negocio',$id)->get('gestorvendas.contato_pessoas')->result();
	}
	function updateContatos($data){
		$this->db->db_debug = FALSE;
		return $this->db->insert('gestorvendas.contato_pessoas',$data);
	}
	function organizacoes(){
		return $this->db->where('email_company is null')->get('gestorvendas.negocio')->result();
	}
	function organizacoes_update($id,$data){
		return $this->db->where('id',$id)->update('gestorvendas.negocio',$data);
	}
	function insert_telefone($data){
		return $this->db->insert('gestorvendas.contato_pessoas',$data);
	}
	function update_negocio($data){
		return $this->db->where('id',$data['id'])->update('gestorvendas.negocio',$data);
	}
	function insert_atividade($data){
		$this->db->db_debug = FALSE;
		return $this->db->insert('gestorvendas.agend',$data);
	}
	function update_atividade($data){
		return $this->db->where('id',$data['id'])->update('gestorvendas.agend',$data);
	}
	function get_negocio_usuario($usuario=null){
        if ($usuario)
            $this->db->where('user_id',$usuario);
		return $this->db->select('id,title')->where('status','1')->order_by('id','desc')->get('gestorvendas.negocio')->result();
	}
    function get_negocio($usuario=null){
        if ($usuario)
            $this->db->where('user_id',$usuario);
        return $this->db->where('status','1')->order_by('id','desc')->get('gestorvendas.negocio')->result();
    }
	function get_atividades_usuario($usuario){
		$negocios = $this->db->where('user_id',$usuario)->where('status','1')->order_by('id','desc')->get('gestorvendas.negocio')->result();
		$list_array = array();
		foreach($negocios as $negocio){
			$list_array[] = $negocio->id;
		}
		return $this->db->select('a.*,n.title as nome_empresa')->where_in('business_id',$list_array)->join('gestorvendas.negocio n','a.business_id = n.id')->get('gestorvendas.agend a')->result();
	}
	function deleteBusiness($data) {
		// deletar atividades e históricos
		$this->deleteBusinessEtapa($data);
		return $this->db->query("UPDATE gestorvendas.negocio SET status=0 WHERE id = {$data['id']};");
	}

	function editNameBusiness($data) {
		return $this->db->query("UPDATE gestorvendas.negocio SET title = '{$data['name']}', updated_on = NOW() WHERE id = {$data['id']};");
	}

	private function isStagnado($business_id, $etapa_id) {
		$sql = "SELECT 
					DATEDIFF(NOW(), date_init) AS diffStagnado
				FROM 
					gestorvendas.etapa_business
				WHERE 
					business_id = {$business_id} AND etapa_id = {$etapa_id} and status=1;";
		return $this->db->query( $sql )->result();
	}

	private function agendDateHour($data) {
		$c = count($data);
		$empresas = "(";
		for ($i=0; $i < $c; $i++) {
			if($i){
				$empresas .=",";
			}
			$empresas .= $data[$i]->id;
		}
		$empresas .= ")";
		$agendas = array();
		$etapas = array();
		if($c){
			$query_agend = $this->db->query("SELECT a.business_id,a.id, a.date, a.hour, DATEDIFF(a.date, NOW()) AS diffDays, TIMEDIFF(a.hour, TIME(NOW())) AS diffTime FROM gestorvendas.agend AS a WHERE a.business_id in {$empresas} ORDER BY a.date, a.hour DESC;")->result();
			
			foreach($query_agend as $agenda){
				$agendas['a'.$agenda->business_id]=$agenda;
			}

			$sql = "SELECT business_id,etapa_id,
					DATEDIFF(NOW(), date_init) AS diffStagnado
				FROM 
					gestorvendas.etapa_business
				WHERE 
					business_id in {$empresas} AND status=1;";
			$query_etapa = $this->db->query( $sql )->result();
			foreach($query_etapa as $etapa){
				if(!isset($etapas['e'.$etapa->business_id])){
					$etapas['e'.$etapa->business_id]=array();
				}
				$etapas['e'.$etapa->business_id]['e_i'.$etapa->etapa_id]=$etapa;
			}
		}
		for ($i=0; $i < $c; $i++) {
			$isStagnado = false;
			if(isset($etapas['e'.$data[$i]->id])){
				if(isset($etapas['e_i'.$data[$i]->etapa_id])){
					$isStagnado = $etapas['e'.$data[$i]->id]['e_i'.$data[$i]->etapa_id];
				}
			}
			$agend = isset($agendas['a'.$data[$i]->id])?$agendas['a'.$data[$i]->id]:[];
			$isStagnado ? $data[$i]->diffStagnado = $isStagnado->diffStagnado : $data[$i]->diffStagnado = NULL ;
			if ($agend) {
				$data[$i]->agend_id = $agend->id;
				$data[$i]->date_a = $agend->date;
				$data[$i]->hour_a = $agend->hour;
				$data[$i]->diffDays = $agend->diffDays;
				$data[$i]->diffTime = $agend->diffTime;
			} else {
				$data[$i]->diffDays = '';
				$data[$i]->diffTime = '';
			}
		}
		return $data;
	}

	function getBusiness($data) {
		if (gettype($data) == 'array') {
			$sql = "SELECT id, title, value, created_on, updated_on,user_id, 
							company, name_contact, funil_id, etapa_id, segmento,
							DATEDIFF(NOW(), IF(updated_on, updated_on, created_on)) AS diffInactive
					FROM 
						gestorvendas.negocio 
					WHERE 
					etapa_id = {$data['etapa_id']} AND user_id = {$data['user_id']} AND is_gain IS NULL AND status=1;";

			$array = $this->db->query( $sql )->result();
			return $this->agendDateHour($array);
		} else {
			$sql = "SELECT 
						id, name_contact, company, title, value, coin, funil_id,
						etapa_id, date, phone_company, email_company, phone_contact,
						email_contact, type_email, type_phone, cnpj, provider,user_id,
						street, number, city, state, created_on, updated_on, district,segmento,
						is_gain
					FROM 
						gestorvendas.negocio 
					WHERE id = {$data} AND status=1;";
			$array = $this->db->query( $sql )->result();
			return $this->agendDateHour($array);
		}
	}

	function editProvider($data) {
		return $this->db->query("UPDATE gestorvendas.negocio SET provider = {$data['provider']}, , updated_on = NOW() WHERE id = {$data['id']};");
	}

	function editOrg($data) {
		$this->saveHistoric('Atualização de dados da organização', $data['id']);
		return $this->db->query("UPDATE gestorvendas.negocio SET street = '{$data['street']}', number = '{$data['number']}', district = '{$data['district']}', city = '{$data['city']}', segmento = '{$data['segmento']}', phone_company = '{$data['phone_company']}', email_company = '{$data['email_company']}', state = '{$data['state']}', updated_on = NOW() WHERE id = {$data['id']};");
	}

	function editPerson($data) {
		if(!$data['name']){
			$data['name']="";
		}
		if(!$data['phone']){
			$data['phone']="";
		}
		if(!$data['type_email']){
			$data['type_email']="";
		}
		if(!$data['type_phone']){
			$data['type_phone']="";
		}
		if(!$data['email_contact']){
			$data['email_contact']="";
		}
		return $this->db->query("UPDATE gestorvendas.negocio SET updated_on = NOW(), name_contact = '{$data['name']}', phone_contact = '{$data['phone']}', type_email = '{$data['type_email']}', type_phone = '{$data['type_phone']}', email_contact = '{$data['email']}' WHERE id = {$data['id']};");
	}
	// business end
	// etapa init
	function saveEtapa($data) {
		return $this->db->query("INSERT INTO gestorvendas.etapa (name, funil_id, is_on, stagnado,status) VALUES ('{$data['name']}', {$data['company_id']}, '{$data['is_on']}', {$data['stagnado']},1);");
	}

	function editEtapa($data) {
		return $this->db->query("UPDATE gestorvendas.etapa SET name = '{$data['name']}', updated_on = '{$data['updated_on']}', is_on = '{$data['is_on']}', stagnado = {$data['stagnado']} WHERE id = {$data['id']};");
	}

	function deleteEtapa($data) {
		// levantar uma exception 
		return $this->db->query("UPDATE gestorvendas.etapa set status = 0 WHERE id = {$data['id']};");
	}

	function getEtapa($id) {
		return $this->db->query("SELECT * FROM gestorvendas.etapa WHERE id = {$id['id']} and status=1;")->result();
	}

	function getEtapas($data) {
		return $this->db->query("SELECT id, name, is_on, stagnado FROM gestorvendas.etapa WHERE funil_id = {$data['company_id']} and status=1;")->result();
	}
	// etapa end
	// funil init
	function saveFunil($name) {
		return $this->db->query("INSERT INTO gestorvendas.funil (name,status) VALUES ('{$name}',1);");
	}

	function editFunil($data) {
		return $this->db->query("UPDATE gestorvendas.funil SET name = '{$data['name']}', updated_on = '{$data['updated_on']}' WHERE id = {$data['id']};");
	}

	function deleteFunil($data) {
		// levantar uma exception 
		return $this->db->query("UPDATE gestorvendas.funil SET status = 0 WHERE id = {$data['id']};");
	}

	function getFunil() {
		return $this->db->query('SELECT id, name FROM gestorvendas.funil where status=1;')->result();
	}
	// funil end
}