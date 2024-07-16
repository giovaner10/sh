<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class linhas extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('mapa_calor');
	}

	public function linha()
    {
        $this->auth->is_allowed('cadastros_shownet');
		$dados['titulo'] = lang('linhas');
		$this->mapa_calor->registrar_acessos_url(site_url('/GestaoDeChips/linhas/linha'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('gestaoDeChips/linha');
		$this->load->view('fix/footer_NS');   
	}

    public function listarLinhas(){
        $this->load->helper('util_helper');

		$idOperadora = $this->input->post('idOperadora');

		$retorno = (get_listarLinhas($idOperadora));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function listarOperadoras(){
        $this->load->helper('util_helper');
		
		$retorno = (get_listarOperadoras('', ''));

        $result = array();

        foreach ($retorno as $key => $value) {
            $result [] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

		if (!empty($result)){
			echo json_encode($result);
		}else{
			return false;
		}
	}

	public function cadastrarLinha(){
		$this->load->helper('util_helper');
		
		$dados = $this->input->post();

		$retorno = json_decode(to_cadastrarLinha($dados));

		if ($retorno){
			return ($retorno);
		}else{
			return false;
		}


	}

	public function listarEmpresas(){
		
		$retorno = $this->empresas_logistica->buscarEmpresas();

        $result = array();

        foreach ($retorno as $key => $value) {
            $result [] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

		if (!empty($result)){
			return $result;
		}else{
			return false;
		}
	}

	public function listarFornecedores(){
		$empresa = $this->input->post('idEmpresa');

		$this->load->library('../controllers/GestaoDeChips/fornecedores', 'fornecedores');

		$retorno = to_listarFornecedores($empresa);

		$result = array();

		foreach ($retorno['dados'] as $key => $value) {
			$result [] = array(
				'id' => $value['id'],
				'text' => $value['razaoSocial']
			);
		}


		if (!empty($result)){
			echo json_encode(array(
				'results' => $result));
		}else{
			return false;
		}
	}

	public function editarLinha(){
		$body = $this->input->post();
	
		$retorno = (to_atualizaCadLinha($body));
		
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function alterarStatusLinha(){
		$body = $this->input->post();
	
		$retorno = (to_atualizaStatusLinha($body));
		
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function cadastrarHistoricoLinha(){
		$body = $this->input->post();
	
		$retorno = json_decode(to_cadastrarHistoricoLinha($body));
		
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function retornaIdLinha(){
		$this->load->model('linha');

		$linha = $this->input->get('linha');
		

		$retorno = $this->linha->getLinhaId($linha);

		if (!empty($retorno)){
			echo json_encode($retorno);
		}else{
			return false;
		}

	}

	public function listarHistoricoLinha(){
		$this->load->helper('util_helper');

		$idLinha = $this->input->post('idLinha');

		$retorno = (get_listarHistoricoLinha($idLinha));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function alterarStatusHistoricoLinha(){
		$body = $this->input->post();
	
		$retorno = (to_atualizaStatusHistoricoLinha($body));
		
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function editarHistoricoLinha(){
		$body = $this->input->post();
	
		$retorno = (to_atualizaHistoricoLinha($body));
		
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

	public function listarLinhasPorLinhaCcid(){

		$linha = $this->input->post('linha');
		$ccid = $this->input->post('ccid');

		$retorno = (get_listarLinhasByLinhaCcid($linha, $ccid));

		echo json_encode($retorno);
	}

	public function listarLinhasRecentes(){

		$retorno = get_listarLinhasRecentes();

		echo json_encode($retorno);
	}

	public function listarlinhasPorOperadoraCcid(){
		$idOperadora = $this->input->post('idOperadora');
		$ccid = $this->input->post('ccid');

		$retorno = (get_listarLinhasByOperadoraCcid($idOperadora, $ccid));

		echo json_encode($retorno);
	}

	public function listarlinhasPorOperadoraLinha(){
		$idOperadora = $this->input->post('idOperadora');
		$linha = $this->input->post('linha');

		$retorno = (get_listarLinhasByOperadoraLinha($idOperadora, $linha));

		echo json_encode($retorno);
	}

	// public function desbloquearChip(){
	// 	$body = $this->input->post();
	
	// 	$retorno = (to_desbloquearChip($body));
		
	// 	if (!empty($retorno)){
	// 		return $retorno;
	// 	}else{
	// 		return false;
	// 	}
	// }
    
    
}