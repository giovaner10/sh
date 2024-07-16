<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gestor_vendas extends CI_Controller {

	function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->helper('url');
		$this->load->model('gestor_vendasmodel', 'neg');
	}

	public function index() {
		$this->load->view('gestor_vendas/negocio');
	}

	function company() {
		$this->load->view('gestor_vendas/funil');
	}

	// API DE CONSULTA DE CNPJ
	public function consulta_cnpj($cnpj)
    {
        echo file_get_contents('http://receitaws.com.br/v1/cnpj/'.$cnpj);
	}
	
	function empresas() {
		$this->load->view('gestor_vendas/empresas');
	}

	function ajaxListEmpresas($id=null) {
        if ($this->auth->is_allowed_block('crm_vendas_admin')) {
            $empresas = $this->neg->get_negocio();
        } else {
            $empresas = $this->neg->get_negocio($this->auth->get_login_dados('id_user'));
        }

        $retorno = array();
        foreach ($empresas as $empresa) {
            if ($empresa->is_gain == "1")
                $negocio = '<span class="badge badge-success">Negócio Ganho</span>';
            elseif ($empresa->is_gain == "0")
                $negocio = '<span class="badge badge-danger">Negócio Perdido</span>';
            else
                $negocio = '<span class="badge badge-info">Aguardando</span>';

            $retorno['data'][] = array(
                $empresa->id,
                $empresa->name_contact,
                $empresa->title,
                $empresa->phone_company,
                $empresa->email_company,
                $empresa->cnpj,
                $empresa->city,
                $empresa->state,
                $negocio,
                $empresa->segmento,
                '<div style="text-align: center !important;"><a href="'.site_url("gestor_vendas/deal").'/'.$empresa->id.'" target="_blank"><i class="fa fa-folder-open" style="font-size: 18px"></i></a></div>'
            );
        }

        echo json_encode($retorno);
    }

	function migrar_empresas(){
		$usuario_antigo = $this->input->post('usuario_antigo');
		$novo_usuario = $this->input->post('novo_usuario');
		if(!$usuario_antigo||!$novo_usuario){
			echo json_encode(array('status'=>false,'msg'=>'faltando parâmetros'));
			die;
		}
		else{
			$this->neg->migrar_usuario($usuario_antigo,$novo_usuario); 
			echo "1";
		}
	}
	function atividades($id=null) {
		$ax = null;
		$id = $this->auth->get_login('admin','user');
		if($this->input->get('user_id')){
			$id = $this->input->get('user_id');
		}
		if(!$id){
			redirect(site_url('acesso/entrar'));
		}
		$ax['atividades'] = $this->neg->get_atividades_usuario($id); 
		//var_dump($ax);die;
		$this->load->view('gestor_vendas/atividades', $ax);	
	}

	function deal($id=null) {
		$ax = null;
		$id = intval($id);
		$ax['businness'] = array();
		if ($id) {
			$ax['businness'] = $this->getBusiness($id);
			$ax['contatos'] = $this->neg->getContatos($id);

		}
		$this->load->view('gestor_vendas/deal', $ax);	
	}

	function gain() {
		echo json_encode($this->neg->gain($this->input->post()));
	}

	function lost() {
		echo json_encode($this->neg->lost($this->input->post()));
	}

	function addNotation() {
		echo json_encode($this->neg->addNotation($this->input->post()));
	}

	function addAgend() {
		$data = $this->input->post();
		$data['user_id'] = $this->auth->get_login('admin','user');
		if(!$data['user_id']){
			redirect(site_url('acesso/entrar'));
		}
		echo json_encode($this->neg->addAgend($data));
	}

	function checkedAgend() {
		echo json_encode($this->neg->checkedAgend($this->input->post()));
	}

	function getHistoric() {
		echo json_encode($this->neg->getHistoric($this->input->get()));
	}

	//

	function diffEtapaBusiness() {
		echo json_encode($this->neg->diffEtapaBusiness($this->input->get()));
	}

	function saveBusiness() {
		$data = $this->input->post();
		$data['user_id'] = $this->auth->get_login('admin','user');
		if(!$data['user_id']){
			echo json_encode(array('status'=>false,'error'=>'Faça o login no sistema'));
			die;
		}
		echo json_encode($this->neg->saveBusiness($data));
	}

	function editBusinessEtapa() {
		echo json_encode($this->neg->editBusinessEtapa($this->input->post()));
	}

	function editProvider() {
		echo json_encode($this->neg->editProvider($this->input->post()));
	}

	function editOrg() {
		echo json_encode($this->neg->editOrg($this->input->post()));
	}

	function editPerson() {
		echo json_encode($this->neg->editPerson($this->input->post()));
	}

	function deleteBusiness() {
		echo json_encode($this->neg->deleteBusiness($this->input->post()));
	}


	function editNameBusiness() {
		echo json_encode($this->neg->editNameBusiness($this->input->post()));
	}

	function getBusiness($id=null) {
		if ($id == null){
			$data = $this->input->get();
			$data['user_id'] = $this->auth->get_login('admin','user');
			if(!$data['user_id']){
				echo json_encode(array('status'=>false,'error'=>'Faça o login no sistema'));
				die;
			}
			if($this->input->get('user_id')){
				$data['user_id'] = $this->input->get('user_id');
			}
			
			echo json_encode($this->neg->getBusiness($data));
		}
		else
			return $this->neg->getBusiness($id);
	}

	// etapa init
	function saveEtapa() {
		echo json_encode($this->neg->saveEtapa($this->input->post()));
	}

	function editEtapa() {
		echo json_encode($this->neg->editEtapa($this->input->post()));
	}

	function deleteEtapa() {
		echo json_encode($this->neg->deleteEtapa($this->input->get()));
	}

	function getEtapa() {
		echo json_encode($this->neg->getEtapa($this->input->get()));
	}

	function getEtapas() {
		echo json_encode($this->neg->getEtapas($this->input->get()));
	}
	// etapa end
	// funil init
	function saveFunil() {
		echo json_encode($this->neg->saveFunil($this->input->post()['name']));
	}

	function editFunil() {
		echo json_encode($this->neg->editFunil($this->input->post()));
	}

	function deleteFunil() {
		echo json_encode($this->neg->deleteFunil($this->input->get()));
	}

	function getFunil() {
		echo json_encode($this->neg->getFunil());
	}
	// funil end
	function organizacoes(){
		$this->load->helper('file');
		$organizacoes = $this->neg->organizacoes();
		$resultado = [];
		foreach($organizacoes as $key => $organizacao){
			$resultado[$organizacao->company] = $organizacao->id;
			unset($organizacoes[$key]);
		}
		$empresas = json_decode(read_file('/home/gabriel/Downloads/convertcsv (2).json'));
		foreach($empresas as $empresa){
			if(isset($resultado[substr($empresa->company,0,40)])){
				echo $resultado[$empresa->company]."<BR>";
				$this->neg->organizacoes_update($resultado[$empresa->company],$empresa);
				
			}
		}
	}

	function migracao(){
		$file = fopen("/home/show/http/deals.csv","r");
		$dados = array();
		$vendedores = array();
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Ana Montenegro - PE']='204';
		$vendedores['André Luiz - RS']='222';
		$vendedores['Carlos Henrique - RN']='242';
		$vendedores['Cassius Macssuara - JPA']='201';
		$vendedores['Cristina Lopes - POA']='233';
		$vendedores['Edaniela Santos - BA']='223';
		$vendedores['Eduardo Mattos']='258';
		$vendedores['Elizabeth Cristina - COO - REC/JPA/FORT']='61';
		$vendedores['Elvis Aguiar  - PB']='202';
		$vendedores['Gustavo Toscano - PE']='205';
		$vendedores['Hilder Alves - CE']='235';
		$vendedores['Igor Marques - BA']='225';
		$vendedores['João Jardel - CE']='263';
		$vendedores['Juliana Marcolino - SIM JPA']='184';
		$vendedores['juliana Mirtes - GBA']='210';
		$vendedores['Kaliandra Gomes - COO - SP/RS/BA']='198';
		$vendedores['Katiana Rodrigues']='180';
		$vendedores['Laerte - SPA']='264';
		$vendedores['Lorena Medina - BA']='265';
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Marcio Meireles']='260';
		$vendedores['Mathias Junior - SP']='239';
		$vendedores['Maxwell Nery - RN']='226';
		$vendedores['Paulo oliveira']='259';
		$vendedores['Paulo Sérgio - RS']='227';
		$vendedores['Pedro Alves  - EUA']='219';
		$vendedores['Rodger - CE']='262';
		$vendedores['Roosevelt Silva - RN']='240';
		$vendedores['Sandra Apolônio -PE']='203';
		$vendedores['Sandra Valéria - PB']='200';
		$vendedores['SHOW TECNOLOGIA']='189';
		$vendedores['Viviane Matos']='261';

		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($vendedores[$data[2]])&&$data[2]!='Sandra Valéria - PB'&&$data[2]!='Marcio Meireles'){
				$data[2]=$vendedores[$data[2]];
				if($data[13]=="Perdido"){
					$data[13]='0';
				}
				elseif($data[13]=="Ganho"){
					$data[13]='1';
				}
				else{
					$data[13]=NULL;
				}
				if($data[12]=="Contatos Mailling" || $data[12]=="Contatos Maillings"){
					$data[12]='1';
				}
				elseif($data[12]=="Leads Coordenação"){
					$data[12]='2';
				}
				elseif($data[12]=="Empresas Visitadas"){
					$data[12]='3';
				}
				elseif($data[12]=="Primeiro Contato / Prospectado"){
					$data[12]='5';
				}
				elseif($data[12]=="Apresentações Enviadas"){
					$data[12]='6';
				}
				elseif($data[12]=="Em Negociação"){
					$data[12]='7';
				}
				elseif($data[12]=="Prospectando"){
					$data[12]='9';
				}
				$dados[$data[9]] = array(
					'id'=>$data[0],
					'name_contact'=>$data[11],
					'company'=>$data[9],
					'title'=>$data[1],
					'value'=>$data[3],
					'coin'=>$data[4],
					'funil_id'=>'1',
					'etapa_id'=>$data[12],
					'date'=>$data[14],
					/*'phone_company'=>$data[35],
					'email_company'=>$data[36],
					'phone_contact'=>$data[0],
					'email_contact'=>$data[0],
					'type_phone'=>$data[0],
					'type_email'=>$data[0],
					'cnpj'=>$data[0],
					'provider'=>$data[0],
					'street'=>$data[0],
					'number'=>$data[0],
					'city'=>$data[0],
					'state'=>$data[0],
					'district'=>$data[0],
					'comment'=>$data[0],*/
					'created_on'=>$data[14],
					'updated_on'=>$data[15],
					'is_gain'=>$data[13],
					'user_id'=>$data[2],
					'motive'=>$data[22],
					'status'=>'1');
			}
			//var_dump($data);
			//echo "<br><br>";
		}
		//var_dump($dados);
		fclose($file);

		$file = fopen("/home/show/http/organizations.csv","r");

		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($vendedores[$data[3]])){
				if(isset($dados[$data[1]])){
				$dados[$data[1]] = array_merge(array(
					'phone_contact'=>$data[35],
					'email_contact'=>$data[36],
					'cnpj'=>$data[34],
					'provider'=>$data[32],
					'street'=>$data[22],
					'number'=>$data[21],
					'city'=>$data[24],
					'state'=>$data[25],
					'district'=>$data[23],
					'comment'=>$data[33],
					'segmento'=>$data[30]),$dados[$data[1]]);
				}
			}
		}
		fclose($file);
		foreach($dados as $dado){
			$this->neg->update_negocio($dado);
			echo json_encode($dado);
			echo "<br><br>";
		}
	}
	function migracao2(){
		$vendedores = array();
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Ana Montenegro - PE']='204';
		$vendedores['André Luiz - RS']='222';
		$vendedores['Carlos Henrique - RN']='242';
		$vendedores['Cassius Macssuara - JPA']='201';
		$vendedores['Cristina Lopes - POA']='233';
		$vendedores['Edaniela Santos - BA']='223';
		$vendedores['Eduardo Mattos']='258';
		$vendedores['Elizabeth Cristina - COO - REC/JPA/FORT']='61';
		$vendedores['Elvis Aguiar  - PB']='202';
		$vendedores['Gustavo Toscano - PE']='205';
		$vendedores['Hilder Alves - CE']='235';
		$vendedores['Igor Marques - BA']='225';
		$vendedores['João Jardel - CE']='263';
		$vendedores['Juliana Marcolino - SIM JPA']='184';
		$vendedores['juliana Mirtes - GBA']='210';
		$vendedores['Kaliandra Gomes - COO - SP/RS/BA']='198';
		$vendedores['Katiana Rodrigues']='180';
		$vendedores['Laerte - SPA']='264';
		$vendedores['Lorena Medina - BA']='265';
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Marcio Meireles']='260';
		$vendedores['Mathias Junior - SP']='239';
		$vendedores['Maxwell Nery - RN']='226';
		$vendedores['Paulo oliveira']='259';
		$vendedores['Paulo Sérgio - RS']='227';
		$vendedores['Pedro Alves  - EUA']='219';
		$vendedores['Rodger - CE']='262';
		$vendedores['Roosevelt Silva - RN']='240';
		$vendedores['Sandra Apolônio -PE']='203';
		$vendedores['Sandra Valéria - PB']='200';
		$vendedores['SHOW TECNOLOGIA']='189';
		$vendedores['Viviane Matos']='261';
		$file = fopen("/home/show/http/deals.csv","r");
		$dados = array();
		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($vendedores[$data[2]])){
				$data[2]=$vendedores[$data[2]];
				if($data[13]=="Perdido"){
					$data[13]='0';
				}
				elseif($data[13]=="Ganho"){
					$data[13]='1';
				}
				else{
					$data[13]=NULL;
				}
				if($data[12]=="Contatos Mailling" || $data[12]=="Contatos Maillings"){
					$data[12]='1';
				}
				elseif($data[12]=="Leads Coordenação"){
					$data[12]='2';
				}
				elseif($data[12]=="Empresas Visitadas"){
					$data[12]='3';
				}
				elseif($data[12]=="Primeiro Contato / Prospectado"){
					$data[12]='5';
				}
				elseif($data[12]=="Apresentações Enviadas"){
					$data[12]='6';
				}
				elseif($data[12]=="Em Negociação"){
					$data[12]='7';
				}
				elseif($data[12]=="Prospectando"){
					$data[12]='9';
				}
				//var_dump(utf8_decode($data[1]));die;
				$dados[$data[1]] = array(
					'id'=>$data[0],
					'name_contact'=>$data[11],
					'company'=>$data[9],
					'title'=>$data[1],
					'value'=>$data[3],
					'coin'=>$data[4],
					'funil_id'=>'1',
					'etapa_id'=>$data[12],
					'date'=>$data[14],
					/*'phone_company'=>$data[35],
					'email_company'=>$data[36],
					'phone_contact'=>$data[0],
					'email_contact'=>$data[0],
					'type_phone'=>$data[0],
					'type_email'=>$data[0],
					'cnpj'=>$data[0],
					'provider'=>$data[0],
					'street'=>$data[0],
					'number'=>$data[0],
					'city'=>$data[0],
					'state'=>$data[0],
					'district'=>$data[0],
					'comment'=>$data[0],*/
					'created_on'=>$data[14],
					'updated_on'=>$data[15],
					'is_gain'=>$data[13],
					'user_id'=>$data[2],
					'motive'=>$data[22],
					'status'=>'1');
			}
			//var_dump($data);
			//echo "<br><br>";
		}
		//var_dump($dados);
		fclose($file);
		//var_dump($dados['Negócio PREFEITURA MUNICIPAL DE PIRENÓPOLIS - GO']);die;

		$file = fopen("/home/show/http/activities-2190851-86.csv","r");
		//$dados = array();
		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($vendedores[$data[14]])){
				//if($data[0]>'70767'){
					if(isset($dados[$data[7]])){
						if($data[3]=="Para fazer"){
							$data[3]='0';
						}
						else{
							$data[3]='1';
						}
						$dados1 = array(
							'id'=>$data[0],
							'type'=>$data[2],
							'activity'=>$data[1],
							'date'=>$data[8],
							'hour'=>$data[9],
							'duration'=>$data[10],
							'notation'=>$data[11],
							'realized'=>$data[3],
							'business_id'=>$dados[$data[7]]['id'],
							'created_on'=>$data[12],
							'user_id'=>$vendedores[$data[14]]);
						var_dump($dados1);echo "<br><br>";
						$this->neg->insert_atividade($dados1);
					}
				}
			//}
		}
	}
	function migracao3(){
		$vendedores = array();
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Ana Montenegro - PE']='204';
		$vendedores['André Luiz - RS']='222';
		$vendedores['Carlos Henrique - RN']='242';
		$vendedores['Cassius Macssuara - JPA']='201';
		$vendedores['Cristina Lopes - POA']='233';
		$vendedores['Edaniela Santos - BA']='223';
		$vendedores['Eduardo Mattos']='258';
		$vendedores['Elizabeth Cristina - COO - REC/JPA/FORT']='61';
		$vendedores['Elvis Aguiar  - PB']='202';
		$vendedores['Gustavo Toscano - PE']='205';
		$vendedores['Hilder Alves - CE']='235';
		$vendedores['Igor Marques - BA']='225';
		$vendedores['João Jardel - CE']='263';
		$vendedores['Juliana Marcolino - SIM JPA']='184';
		$vendedores['juliana Mirtes - GBA']='210';
		$vendedores['Kaliandra Gomes - COO - SP/RS/BA']='198';
		$vendedores['Katiana Rodrigues']='180';
		$vendedores['Laerte - SPA']='264';
		$vendedores['Lorena Medina - BA']='265';
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Marcio Meireles']='260';
		$vendedores['Mathias Junior - SP']='239';
		$vendedores['Maxwell Nery - RN']='226';
		$vendedores['Paulo oliveira']='259';
		$vendedores['Paulo Sérgio - RS']='227';
		$vendedores['Pedro Alves  - EUA']='219';
		$vendedores['Rodger - CE']='262';
		$vendedores['Roosevelt Silva - RN']='240';
		$vendedores['Sandra Apolônio -PE']='203';
		$vendedores['Sandra Valéria - PB']='200';
		$vendedores['SHOW TECNOLOGIA']='189';
		$vendedores['Viviane Matos']='261';
		$file = fopen("/home/show/http/deals.csv","r");
		$dados = array();
		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($vendedores[$data[2]])){
				$data[2]=$vendedores[$data[2]];
				if($data[13]=="Perdido"){
					$data[13]='0';
				}
				elseif($data[13]=="Ganho"){
					$data[13]='1';
				}
				else{
					$data[13]=NULL;
				}
				if($data[12]=="Contatos Mailling" || $data[12]=="Contatos Maillings"){
					$data[12]='1';
				}
				elseif($data[12]=="Leads Coordenação"){
					$data[12]='2';
				}
				elseif($data[12]=="Empresas Visitadas"){
					$data[12]='3';
				}
				elseif($data[12]=="Primeiro Contato / Prospectado"){
					$data[12]='5';
				}
				elseif($data[12]=="Apresentações Enviadas"){
					$data[12]='6';
				}
				elseif($data[12]=="Em Negociação"){
					$data[12]='7';
				}
				elseif($data[12]=="Prospectando"){
					$data[12]='9';
				}
				//var_dump(utf8_decode($data[1]));die;
				$dados[$data[9]] = array(
					'id'=>$data[0],
					'name_contact'=>$data[11],
					'company'=>$data[9],
					'title'=>$data[1],
					'value'=>$data[3],
					'coin'=>$data[4],
					'funil_id'=>'1',
					'etapa_id'=>$data[12],
					'date'=>$data[14],
					/*'phone_company'=>$data[35],
					'email_company'=>$data[36],
					'phone_contact'=>$data[0],
					'email_contact'=>$data[0],
					'type_phone'=>$data[0],
					'type_email'=>$data[0],
					'cnpj'=>$data[0],
					'provider'=>$data[0],
					'street'=>$data[0],
					'number'=>$data[0],
					'city'=>$data[0],
					'state'=>$data[0],
					'district'=>$data[0],
					'comment'=>$data[0],*/
					'created_on'=>$data[14],
					'updated_on'=>$data[15],
					'is_gain'=>$data[13],
					'user_id'=>$data[2],
					'motive'=>$data[22],
					'status'=>'1');
			}
			//var_dump($data);
			//echo "<br><br>";
		}
		//var_dump($dados);
		fclose($file);
		//var_dump($dados['Negócio PREFEITURA MUNICIPAL DE PIRENÓPOLIS - GO']);die;

		$file = fopen("/home/show/http/people.csv","r");
		//$dados = array();
		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($dados[$data[7]])){
				$dados1 = array(
					'id'=>$data[0],
					'nome'=>$data[1],
					'email'=>$data[4],
					'telefone'=>$data[3],
					'negocio'=>$dados[$data[7]]['id']);
				var_dump($dados1);echo "<br><br>";
				$this->neg->updateContatos($dados1);
			}
		}
	}
	function migracao4(){
		$vendedores = array();
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Ana Montenegro - PE']='204';
		$vendedores['André Luiz - RS']='222';
		$vendedores['Carlos Henrique - RN']='242';
		$vendedores['Cassius Macssuara - JPA']='201';
		$vendedores['Cristina Lopes - POA']='233';
		$vendedores['Edaniela Santos - BA']='223';
		$vendedores['Eduardo Mattos']='258';
		$vendedores['Elizabeth Cristina - COO - REC/JPA/FORT']='61';
		$vendedores['Elvis Aguiar  - PB']='202';
		$vendedores['Gustavo Toscano - PE']='205';
		$vendedores['Hilder Alves - CE']='235';
		$vendedores['Igor Marques - BA']='225';
		$vendedores['João Jardel - CE']='263';
		$vendedores['Juliana Marcolino - SIM JPA']='184';
		$vendedores['juliana Mirtes - GBA']='210';
		$vendedores['Kaliandra Gomes - COO - SP/RS/BA']='198';
		$vendedores['Katiana Rodrigues']='180';
		$vendedores['Laerte - SPA']='264';
		$vendedores['Lorena Medina - BA']='265';
		$vendedores['Luiz Claudio- SPA']='226';
		$vendedores['Marcio Meireles']='260';
		$vendedores['Mathias Junior - SP']='239';
		$vendedores['Maxwell Nery - RN']='226';
		$vendedores['Paulo oliveira']='259';
		$vendedores['Paulo Sérgio - RS']='227';
		$vendedores['Pedro Alves  - EUA']='219';
		$vendedores['Rodger - CE']='262';
		$vendedores['Roosevelt Silva - RN']='240';
		$vendedores['Sandra Apolônio -PE']='203';
		$vendedores['Sandra Valéria - PB']='200';
		$vendedores['SHOW TECNOLOGIA']='189';
		$vendedores['Viviane Matos']='261';
		$file = fopen("/home/show/http/deals.csv","r");
		$dados = array();
		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($vendedores[$data[2]])){
				$data[2]=$vendedores[$data[2]];
				if($data[13]=="Perdido"){
					$data[13]='0';
				}
				elseif($data[13]=="Ganho"){
					$data[13]='1';
				}
				else{
					$data[13]=NULL;
				}
				if($data[12]=="Contatos Mailling" || $data[12]=="Contatos Maillings"){
					$data[12]='1';
				}
				elseif($data[12]=="Leads Coordenação"){
					$data[12]='2';
				}
				elseif($data[12]=="Empresas Visitadas"){
					$data[12]='3';
				}
				elseif($data[12]=="Primeiro Contato / Prospectado"){
					$data[12]='5';
				}
				elseif($data[12]=="Apresentações Enviadas"){
					$data[12]='6';
				}
				elseif($data[12]=="Em Negociação"){
					$data[12]='7';
				}
				elseif($data[12]=="Prospectando"){
					$data[12]='9';
				}
				//var_dump(utf8_decode($data[1]));die;
				$dados[$data[1]] = array(
					'id'=>$data[0],
					'name_contact'=>$data[11],
					'company'=>$data[9],
					'title'=>$data[1],
					'value'=>$data[3],
					'coin'=>$data[4],
					'funil_id'=>'1',
					'etapa_id'=>$data[12],
					'date'=>$data[14],
					/*'phone_company'=>$data[35],
					'email_company'=>$data[36],
					'phone_contact'=>$data[0],
					'email_contact'=>$data[0],
					'type_phone'=>$data[0],
					'type_email'=>$data[0],
					'cnpj'=>$data[0],
					'provider'=>$data[0],
					'street'=>$data[0],
					'number'=>$data[0],
					'city'=>$data[0],
					'state'=>$data[0],
					'district'=>$data[0],
					'comment'=>$data[0],*/
					'created_on'=>$data[14],
					'updated_on'=>$data[15],
					'is_gain'=>$data[13],
					'user_id'=>$data[2],
					'motive'=>$data[22],
					'status'=>'1');
			}
			//var_dump($data);
			//echo "<br><br>";
		}
		//var_dump($dados);
		fclose($file);
		//var_dump($dados['Negócio PREFEITURA MUNICIPAL DE PIRENÓPOLIS - GO']);die;

		$file = fopen("/home/show/http/notes-2190851-87.csv","r");
		//$dados = array();
		while (($data = fgetcsv($file)) !== FALSE) {
			if(isset($dados[$data[4]])){
				if(isset($vendedores[$data[7]])){
					$dados1 = array(
						'id'=>$data[0],
						'text'=>$data[1],
						'created_on'=>$data[5],
						'business_id'=>$dados[$data[4]]['id'],
						'user_id'=>$vendedores[$data[7]]);
					var_dump($dados1);echo "<br><br>";
					$this->neg->addNotation2($dados1);
				}
			}
		}
	}
}
