<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * método para salvar o dashbaord
	 * @param $dados[]
	 * @return JSON
	 */
    public function salvar($dados)
	{
		try{
			//adiciona menu
			$dados['id_menu'] = $this->menu->adicionar([
				'nome' => $dados['titulo'],
				'caminho' => $dados['link_bi'],
				'codigo_permissao' => $dados['permissao'],
				'status' => $dados['ativo'] ? 'ativo' : 'inativo',
				'id_pai' => '129', # Dashboard - menu pai
				'icone' => 'dashboard',
			]);

			//adiciona dashboard
			$this->db->insert('showtecsystem.dashboards', $dados);

			//verifica se a inserção ocorreu com sucesso para retornar 
			if($this->db->insert_id()){
				$status = 201;
				$msg = 'Dashboard cadastrado com sucesso!';
			}else{
				$status = 500;
				$msg = 'Ocorreu um problema ao cadastrar o dashboard, tente novamente em alguns instantes.';
			}
			
			echo json_encode(
				array (
					'status'	=> $status,
					'msg' 		=> $msg
					)
				);

		}catch(Exception $e){
			echo json_encode(
				array (
					'status'	=> 500,
					'msg' 		=> $e->getMessage()
				)
			);
		}
	}

	/**
	 * método para alterar o dashbaord
	 * @param $dados[]
	 * @param $idDashboard int
	 * @return JSON
	 */
	public function alterar_dashboard($dados, $idDashboard){
		try{
			//edita menu
			$this->menu->editar(
				$dados['id_menu'],
				[
					'nome' => $dados['titulo'],
					'caminho' => $dados['link_bi'],
					'codigo_permissao' => $dados['permissao'],
					'status' => $dados['ativo'] ? 'ativo' : 'inativo'
				]
			);

			$this->db->update('dashboards', $dados, array('id' => $idDashboard));

			//verifica se teve alguma alteração para preencher o array corretamente
			$this->db->affected_rows() > 0 ? $retorno = array ('status'	=> 200, 'msg' => 'Dashboard alterado com sucesso!') : $retorno = array ('status' => 500, 'msg' => 'Ocorreu um problema ao alterar o Dashboard, tente novamente em instantes.');
			
			return json_encode($retorno);
			
		}catch(Exception $e){
			return json_encode( array ('status'	=> 500, 'msg' => $e->getMessage()));
		}
	}
	
	/**
	 * método para deletar o dashbaord
	 * @param $id int
	 * @return JSON
	 */
	public function deletar_dashboard($id){
		try{
			//deleta menu	
			$menu = $this->db->get_where("showtecsystem.dashboards", ["id" => $id])->result();
			if ($menu) {
				$this->db->delete('showtecsystem.menu', array('id' => $menu[0]->id_menu));
			}

			//deleta dashboard
			$this->db->delete('showtecsystem.dashboards', array('id'=> $id));

			//verifica se teve alguma alteração para preencher o array corretamente
			$this->db->affected_rows() > 0 ? $retorno = array ('status'	=> 200, 'msg' => 'Dashboard deletado com sucesso!') : $retorno = array ('status' => 500, 'msg' => 'Ocorreu um problema ao deletar o Dashboard, tente novamente em instantes.');
			return json_encode($retorno);
		}catch(Exception $e){
			return json_encode( array ('status'	=> 500, 'msg' => $e->getMessage()));
		}
	}

	/**
	 * método para buscar todos os dashbaords
	 * @return Object
	 */
	public function buscar_dashboards(){
		//retorna todos os dashboards
		return $this->db->select('*')->order_by('modificado_em', 'desc')->get('showtecsystem.dashboards')->result();    
	}
	
	/**
	 * método para buscar apenas os dashbaords ativos
	 * @return Object
	 */
	public function buscar_dashboards_ativos(){
		//retorna apenas os dashboards ativos
		return $this->db->select('id,titulo, permissao')->get_where('showtecsystem.dashboards', array('ativo' => 1))->result();	
	}
	
	/**
	 * método para buscar um único dashbaord
	 * @param $id int
	 * @return Object
	 */
	public function buscar_dashboard($id){
		return $this->db->select('*')->where('id', $id)->get('showtecsystem.dashboards')->result()[0];	
	}

	/**
	 * método para buscar as permissões de dashboard para preencher o select2
	 * @return Object
	 */
	public function buscar_permissoes_dashboard(){
		//permissões de funcionário que estão ativas e fazem parte do módulo "Dashboards"
		$where =  array('modulo' => 'Dashboards', 'status' => "1");
		return $this->db->select('descricao, cod_permissao')->get_where(
			'showtecsystem.cad_permissoes_funcionarios',$where)->result();
	}
}