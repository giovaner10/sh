<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

class Dashboards extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('dashboard');
		$this->load->model('menu');
		$this->load->model('mapa_calor');
    }

	public function index()
    {
        $dados['titulo'] = 'Dashboards';
		$this->mapa_calor->registrar_acessos_url(site_url('/Dashboards'));
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('dashboard/listagem_dashboards');
		$this->load->view('fix/footer_NS'); 
    }

	public function salvar_dashboard()
    {
		$dados = $this->input->post();
		$dados['ativo'] = boolval($dados['ativo']);
		$dados['criado_por'] = $this->auth->get_login_dados('email');
		$dados['modificado_por'] = $this->auth->get_login_dados('email');
		$dados['criado_em'] = date("Y-m-d H:i:s");
		$dados['modificado_em'] = date("Y-m-d H:i:s");
		
		echo $this->dashboard->salvar($dados);
	}
		
	public function buscar_dashboards()
	{
		try{
			
			$dashboards = $this->dashboard->buscar_dashboards();
			$dashboardsRetorno = [];
			foreach($dashboards as $dashboard){
				
				$dashboard->acoes = 
				"<button class='btn btn-primary' title='Editar Dashboard' onclick='editarDashboard(this, {$dashboard->id})' style='margin-bottom: 5px;'>
					<i class='fa fa-pencil-square-o' aria-hidden='true'></i>
				</button>
				<button class='btn btn-primary' title='Excluir Dashboard' onclick='deletarDashboard(this, {$dashboard->id})' style='margin-bottom: 5px;'>
					<i class='fa fa-trash' aria-hidden='true'></i>
				</button>
				";
				
				$dashboard->ativo === "1" ? $dashboard->ativo = "Ativo" : $dashboard->ativo = "Inativo";
				$dashboard->linkBi = "<a href=' $dashboard->link_bi ' title='$dashboard->link_bi'> Url BI </a>";
				
				$dashboard->criadoEm = date('d/m/Y:H:i:s',strtotime($dashboard->criado_em));
				$dashboard->criadoPor = $dashboard->criado_por;
				$dashboard->modificadoEm = date('d/m/Y:H:i:s',strtotime($dashboard->modificado_em));
				$dashboard->modificadoPor = $dashboard->modificado_por;
				$dashboardsRetorno[] = $dashboard;
		}
		echo json_encode(
			array('data'	=> $dashboardsRetorno,
				'status'	=> 200
			)
		);

		}catch(Exception $e){
			echo json_encode(
				array('data'	=> [],
					'status'	=> 500,
					'msg'		=> $e->getMessage()
				)
			);
		}
    }

	//retorna os dashboards ativos para preenchimento dinâmico do header
	public static function buscar_dashboards_ativos(){
		// carrega o framework do CI
		$ci =& get_instance();
		// faz a querry manualmente e retorna
		$buscaDashboards = $ci->db->query("SELECT id,titulo,permissao FROM showtecsystem.dashboards WHERE ativo= 1 ");
		$dashboards = $buscaDashboards->result();
		return $dashboards;
	}

	function alterar_dashboard(){
		$id = $this->input->post('id'); 
		$dados = $this->input->post(); 
		$dados['modificado_em'] = date("Y-m-d H:i:s");
		$dados['ativo'] = boolval($dados['ativo']);

		echo $this->dashboard->alterar_dashboard($dados, $id);
	}
	
	function buscar_dashboard(){
		try{
			$id = $this->input->post('id'); 
			$dashboard = $this->dashboard->buscar_dashboard($id);

			echo json_encode(
				array (
					'status'	=> 200,
					'data' 		=> $dashboard
				)
			);
		}catch(Exception $e){
			echo json_encode(
				array(
					'status'	=> 500,
					'data' 		=> $e->getMessage()
				)
			);
		}	
	}

	function deletar_dashboard(){
		echo $this->dashboard->deletar_dashboard( $this->input->post('id') );
	}

	function buscar_permissoes_dashboard(){
		try{
			$permissoes = $this->dashboard->buscar_permissoes_dashboard();

			echo json_encode(
				array(
					'status'=> 200,
					'data'	=> $permissoes
				)
			);

		}catch(Exception $e){
			echo json_encode(
				array(
					'status'=> 500,
					'error'	=> $e->getMessage()
				)
			);
		}
	}

	public function buscar_pagina_dashboard(){
		//pega as informações do dashboard para passar para a view
		$dashboard = $this->dashboard->buscar_dashboard($this->input->get('id'));
		$data['titulo'] 	= $dashboard->titulo . ' - ' . lang('show_tecnologia');
		$data['linkBi'] 	= $dashboard->link_bi;
		$data['permissao']	= $dashboard->permissao != "" ? strval($dashboard->permissao) : null;

		$this->load->view('fix/header-new', $data);
		$this->load->view('dashboard/dashboard');
		$this->load->view('fix/footer-new');
	}
}