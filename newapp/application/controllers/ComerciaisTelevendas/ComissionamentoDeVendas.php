<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class ComissionamentoDeVendas extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('auth');
        $this->load->model('mapa_calor');
		$this->load->model('cliente_crm');
		$this->load->model('usuario');
		$this->load->model('log_shownet');
		$this->load->library('form_validation');
        $this->load->database();
    }

    public function campanhas(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/campanhas'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/campanha');
        $this->load->view('fix/footer_NS');
    }

    public function estornoVendas(){
        $dados['titulo'] = lang('comissionamento_vendas');

        $load['cenarios'] = array();

        $cenarios = get_listarCenariosDeVendas();

        if($cenarios['status'] == 200){
            $load['cenarios'] = $cenarios['results'];
        }
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/estornoVendas'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/estornoVendas', $load);
        $this->load->view('fix/footer_NS');
    }

    public function regionais(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/regionais'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/regionais');
        $this->load->view('fix/footer_NS');
    }

    public function comissoesCalculadas(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/comissoesCalculadas'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/comissoesCalculadas');
        $this->load->view('fix/footer_NS');
    }

    public function cargos(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $dados['empresas'] = array();
        $empresas = json_decode(buscarEmpresas());

        if($empresas->status == 200){
            $dados['empresas'] = $empresas->results;
        }
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/cargo'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/cargo', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function cenariosDeVendas(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/cenariosDeVendas'));		
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/cenariosDeVendas');
        $this->load->view('fix/footer_NS');
    }

    public function confCalculoComissao(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/confCalculoComissoes'));	
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/confCalculoComissoes');
        $this->load->view('fix/footer_NS');
    }

    public function vendedores(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/vendedores'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/vendedores');
        $this->load->view('fix/footer_NS');
    }
    
    public function VendasComissionadas(){
        $dados['titulo'] = lang('comissionamento_vendas');
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComissionamentoDeVendas/vendasComissionadas'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/comissionamentoDeVendas/vendasComissionadas');
        $this->load->view('fix/footer_NS');
    }

    public function listarCampanhas(){
        $idEmpresa = $this->input->post('idEmpresa');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');

        if ($idEmpresa) {
            if($dataInicial && $dataFinal){
                if($dataInicial){
                    $dataInicial = str_replace("-", "/", $dataInicial);
                    $dataInicial  = date('d/m/Y', strtotime($dataInicial));
                }
    
                if($dataFinal){
                    $dataFinal = str_replace("-", "/", $dataFinal);
                    $dataFinal  = date('d/m/Y', strtotime($dataFinal));
                }
    
                $retorno = json_decode(post_listarCampanhasComissionamento($idEmpresa, $dataInicial, $dataFinal));
            }else{
                $retorno = json_decode(get_listarCampanhasComissionamento($idEmpresa));
            }
        } else {
            $retorno = get_listarUltimasCampanhasComissionamento();
        }
        

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function listarEstornos(){
       
        $retorno = json_decode(get_listarDevolucaoVendas());

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function listarEstornosTop100(){
       
        $retorno = json_decode(get_listarDevolucaoVendasTop100());

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function buscarEstorno(){
       
        $id = $this->input->get("id");
        $retorno = get_DevolucaoVendas($id);

        echo json_encode($retorno);
    }

    public function atualizarEstorno(){
       
        $estorno = $this->input->post();

        $retorno = put_DevolucaoVendas($estorno);

        echo json_encode($retorno);
    }

    public function get_estornos() {
        $params = array();

        $params['cliente'] = $this->input->post('cliente') ? $this->input->post('cliente') : '';
        $params['proprietario'] = $this->input->post('prop') ? $this->input->post('prop') : '';
        $params['af'] = $this->input->post('af') ? $this->input->post('af') : '';
        $params['dataInicial'] = $this->input->post('dataInicial') ? $this->input->post('dataInicial') : '';
        $params['dataFinal'] = $this->input->post('dataFinal') ? $this->input->post('dataFinal') : '';

        $retorno = json_decode(post_obterDevolucoesVendas($params));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function adicionarEstorno(){
       
        $estorno = $this->input->post();
        
        $retorno = post_DevolucaoVendas($estorno);

        echo json_encode($retorno);
    }

    public function cadastrarCampanha(){
        $dados = $this->input->post();

        $retorno = json_decode(to_cadastrarCampanha($dados));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }
    
    public function buscarEmpresas(){
        $nome =  $this->input->get('q');
        $nome = str_replace(' ', '%20', $nome);
        $retorno = json_decode(buscarEmpresas($nome));

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }
    }

    public function editarCampanha(){
        $dados = $this->input->post();

        $retorno = json_decode(to_editarCadCampanha($dados));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function cadastrarCampanhaEItem(){
		$this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_cadastrarCampanhaEItens($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function cadastrarCampanhaECenario(){
		$this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_cadastrarCampanhaECenarios($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function listarItensCampanha(){
		$this->load->helper('util_helper');

		$idCampanha = $this->input->post('idCampanha');

		$retorno = (get_listarItensCampanha($idCampanha));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function listarCenariosCampanha(){
		$this->load->helper('util_helper');

		$idCampanha = $this->input->post('idCampanha');

		$retorno = get_listarCenariosCampanha($idCampanha);

        $cenarios = get_listarCenariosDeVendas();

        if ($retorno['status'] == '200') {
            foreach ($retorno['results'] as &$result){
                $result['cenarioNome'] = 'Indefinido';
                foreach ($cenarios['results'] as $objeto) {
                    if ($objeto['id'] === $result['idCenarioVenda']) {
                        $result['cenarioNome'] = $objeto['nome'];
                    }
                }
            }
        }

		if (!empty($retorno)){
			echo json_encode($retorno);
		}else{
			return false;
		}
	}

    public function cadastrarCenarioCampanha(){
        $this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_cadastrarCenarioCampanha($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function editarCenarioCampanha(){
        $this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_editarCenarioCampanha($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function alterarStatusCenarioCampanha(){
		$body = $this->input->post();
		
		$retorno = (to_alterarStatusCenarioCampanha($body));
			
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function cadastrarItemCampanha(){
        $this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_cadastrarItemCampanha($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function alterarStatusItemCampanha(){
		$body = $this->input->post();
		
		$retorno = (to_alterarStatusItemCampanha($body));
			
		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function editarItemCampanha(){
        $this->load->helper('util_helper');

		$body = $this->input->post();

		$retorno = json_decode((to_atualizaItemCampanha($body)));

		if (!empty($retorno)){
			return $retorno;
		}else{
			return false;
		}
	}

    public function listarRegionais(){
        $idEmpresa = $this->input->post('idEmpresa');

        $retorno = json_decode(get_listarRegionaisPorEmpresa($idEmpresa));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function cadastrarRegional(){
        $dados = $this->input->post();

        $retorno = json_decode(to_cadastrarRegional($dados));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function editarRegional(){
        $dados = $this->input->post();

        $retorno = json_decode(to_editarCadRegional($dados));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function listarTodasRegionais(){

        $retorno = json_decode(get_listarRegionaisAll());

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function listarComissoesCalculadas(){

        $retorno = json_decode(get_listarComissoesCalculadas());

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function listarComissoesCalculadasPorEmpresa(){
        $idEmpresa = $this->input->post('idEmpresa');

        $retorno = json_decode(get_listarComissoesCalculadasPorEmpresa($idEmpresa));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }



    public function listarCargos(){
       
        $retorno = get_listarCargos();

        echo json_encode($retorno);
    }

    public function listarVendedores(){
        $retorno = get_listarVendedores();

        echo json_encode($retorno);
    }

    public function cadastrarVendedor(){
        $dados = $this->input->post();

        $retorno = to_cadastrarVendedor($dados);

        echo json_encode($retorno); 
    }

    public function editarVendedor(){
        $dados = $this->input->post();

        $retorno = to_editarCadVendedor($dados);

        echo json_encode($retorno);
    }

    public function listarRegionaisSelect2(){

        $retorno = json_decode(get_listarRegionaisAll(true));


        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }
    }

    public function listarCargosSelect2(){

        $retorno = array('pagination' =>[
            'more' => false
        ]);

        $retorno += (get_listarCargos());

        foreach ($retorno['results'] as $key => $value) {
            $retorno['results'][$key] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }

    }

    public function listarProprietariosSelect2() {
        $retorno = array('pagination' => [
            'more' => false
        ]);

        $retorno += (get_listarVendedores());

        foreach ($retorno['results'] as $key => $value) {
            $retorno['results'][$key] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }
    }

    public function listarVendedoresByNomeOrEmpresaOrCargoOrRegional(){
        $nome = $this->input->post('nome');
        $idEmpresa = $this->input->post('empresa');
        $idCargo = $this->input->post('cargo');
        $idRegional = $this->input->post('regional');

        $retorno = get_listarVendedoresByNomeOrEmpresaOrCargoOrRegional($nome, $idEmpresa, $idCargo, $idRegional);
    
        echo json_encode($retorno);
    }

    public function listarVendedorPorEmpresa(){
        $idEmpresa = $this->input->post('idEmpresa');

        $retorno = get_listarVendedorPorEmpresa($idEmpresa);

        echo json_encode($retorno);
    }
    
    public function buscarCargo(){
       
        $id = $this->input->get("id");
        $retorno = get_Cargo($id);
        echo json_encode($retorno);
    }

    public function atualizarCargo(){
       
        $cargo = $this->input->post();
        $retorno = put_Cargo($cargo);
        echo json_encode($retorno);
    }

    public function adicionarCargo(){
       
        $cargo = $this->input->post();
        $retorno = post_Cargo($cargo);
        echo json_encode($retorno);
    }

    public function listarCenariosDeVendas(){
       
        $retorno = get_listarCenariosDeVendas();

        echo json_encode($retorno);
    }

    public function listarCenariosDeVendasSelect2(){

        $retorno = array('pagination' =>[
            'more' => false
        ]);

        $retorno += (get_listarCenariosDeVendas());

        foreach ($retorno['results'] as $key => $value) {
            $retorno['results'][$key] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

        echo json_encode($retorno);
    }

    public function listarCenariosDeVendasPorEmpresa(){
        $idEmpresa = $this->input->post('idEmpresa');

        $retorno = get_listarCenariosDeVendasPorEmpresa($idEmpresa);

        echo json_encode($retorno);
    }

    public function cadastrarCenarioDeVenda(){
       
        $dados = $this->input->post();

        $retorno = to_cadastrarCenarioDeVenda($dados);
        
        echo json_encode($retorno);
    }

    public function editarCenarioDeVenda(){
        $dados = $this->input->post();

        $retorno = to_editarCadCenarioDeVenda($dados);

        echo json_encode($retorno);
    }

    public function listarConfCalculoComissao(){
       
        $retorno = get_listarConfCalculoComissao();

        echo json_encode($retorno);
    }

    public function adicionarConfCalculoComissao(){
       
        $dados = $this->input->post();

        $retorno = to_cadastrarConfigCalculoComissao($dados);

        echo json_encode($retorno);
    }

    public function editarConfCalculoComissao(){
        $dados = $this->input->post();

        $retorno = to_editarCadConfigCalculoComissao($dados);

        echo json_encode($retorno);
    }

    public function buscarVendedores(){
        $nome =  $this->input->get('q');
        $nome = str_replace(' ', '%20', $nome);
        $retorno = json_decode(get_VendedorPorNome($nome));
    
        if ($retorno->status == 200) {            
            echo json_encode($retorno->results);
        } else {
            return false;
        }
    }

    public function buscarVendedores2(){        
        $retorno = get_listarVendedores();
        if ($retorno['status'] == 200) {            
            echo json_encode($retorno['results']);
        } else {
            return false;
        }
    }


    public function buscarCalculoporVendedor(){ 
        $vendedorId = $this->input->get('vendedorId');
        $dataInicio = $this->input->get('dataInicio');
        $dataFim = $this->input->get('dataFim');

        $retorno = get_calcularComissao($vendedorId, $dataInicio , $dataFim );

        if ($retorno['status'] == 200) {           
            echo json_encode($retorno['results']);
        } else {
            echo json_encode(array());
        }
    }

    public function cadastrarConfCalculoComissaoEItem(){
		$body = $this->input->post();

		$retorno = to_cadastrarConfCalculoComissaoEItens($body);

		echo json_encode($retorno);
	}
    public function listarItensConfCalculoComissaoIdConfig(){
		$idConfig = $this->input->post('idConfig');

		$retorno = get_listarItensConfCalculoComissaoIdConfig($idConfig);

		echo json_encode($retorno);
	}
    public function alterarStatusItemConfCalculoComissao(){
		$body = $this->input->post();

		$retorno = to_alterarStatusItemConfCalculoComissao($body);

		echo json_encode($retorno);
	}
    public function cadastrarItemConfCalculoComissao(){
		$body = $this->input->post();

		$retorno = to_cadastrarItemConfCalculoComissao($body);

		echo json_encode($retorno);
	}
    public function editarItemConfCalculoComissao(){
        $dados = $this->input->post();

        $retorno = to_editarItemConfigCalculoComissao($dados);
        
        echo json_encode($retorno);
    }

    public function listarVendedoresSelect2(){

        $retorno = array('pagination' =>[
            'more' => false
        ]);

        $retorno += (get_listarVendedores());

        foreach ($retorno['results'] as $key => $value) {
            $retorno['results'][$key] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }

    }

    public function listarVendedoresSelect2Nome(){
        $nome =  $this->input->get('q');
        $nome = str_replace(' ', '%20', $nome);
        
        $retorno = array('pagination' =>[
            'more' => false
        ]);

        $retorno += (get_listarVendedoresByNomeOrEmpresaOrCargoOrRegional($nome, $idEmpresa = null, $idCargo = null, $idRegional = null));

        foreach ($retorno['results'] as $key => $value) {
            $retorno['results'][$key] = array(
                'id' => $value['id'],
                'text' => $value['nome']
            );
        }

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }

    }

    
    public function listarVendas(){
        $dataInicial = $this->input->post("dataInicial");
        $dataFinal = $this->input->post("dataFinal");
       
        $retorno = json_decode(get_listarVendasComissionadas($dataInicial, $dataFinal));

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return false;
        }
    }

    public function buscarVenda(){
       
        $id = $this->input->get("id");
        $retorno = get_VendasComissionadas($id);

        echo json_encode($retorno);
    }

    public function atualizarVenda(){
       
        $estorno = $this->input->post();
        
		if($estorno["dataEmissao"]){
            $dataEmissao = str_replace("-", "/", $estorno["dataEmissao"]);
			$estorno['dataEmissao'] = date('d/m/Y', strtotime($dataEmissao));
		}
        
		if($estorno["dataInicio"]){
            $dataInicio = str_replace("-", "/", $estorno["dataInicio"]);
			$estorno['dataInicio'] = date('d/m/Y', strtotime($dataInicio));
		}

		if($estorno["dataFim"]){
            $dataFim = str_replace("-", "/", $estorno["dataFim"]);
			$estorno['dataFim'] = date('d/m/Y', strtotime($dataFim));
		}

        $retorno = put_VendasComissionadas($estorno);

        echo json_encode($retorno);
    }

    public function adicionarVenda(){
       
        $venda = $this->input->post();
        
		if($venda["dataCriacao"]){
            $dataCriacao = str_replace("-", "/", $venda["dataCriacao"]);
			$venda['dataCriacao'] = date('d/m/Y H:i:s', strtotime($dataCriacao));
		}
        
		if($venda["dataFechamento"]){
            $dataFechamento = str_replace("-", "/", $venda["dataFechamento"]);
			$venda['dataFechamento'] = date('d/m/Y H:i:s', strtotime($dataFechamento));
		}

        $retorno = post_VendasComissionadas($venda);

        echo json_encode($retorno);
    }

    
	public function pegar_condicaoPagamentoCRM()
	{
		$value = $this->input->get('value');				
		$retorno = $this->cliente_crm->pegar_condicaoPagamento($value);
		$jsonreturn = json_decode($retorno);		

		foreach ($jsonreturn as $datavalue) {			
			echo '<option value='.$datavalue->tz_name.'>'.$datavalue->tz_name.'</option>';
		 } 			
	}
    
    public function buscar_cliente(){
        $this->load->model('cliente');


        $search = $this->input->get('q');
        $tipoBusca = $this->input->get('tipoBusca');

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        $clientes = $this->cliente->getClientesExpedicao($search, $tipoBusca);

        if(count($clientes) > 0){
            foreach ($clientes as $key => $cliente) {
                //ocorreram alguns casos de o cpf está com poucos caracteres e bugava a verificação
                //necessário colocar uma verificação de caracteres 

                $resposta['results'][] = array(
                    'id' => $cliente['nome'],
                    'text' => $cliente['nome']." (" .$cliente['razao_social'] .")",
                    'cep' => $cliente['cep'],
                    'endereco' => $cliente['endereco'],
                    'uf' => $cliente['uf'],
                    'bairro' => $cliente['bairro'],
                    'cidade' => $cliente['cidade'],
                    'orgao' => $cliente['orgao'],
                );
            }
            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }



    }
    public function calcularComissaoVendedoresSelecionados(){
        $dados = $this->input->post();

        $retorno = to_calcularComissaoVendedoresSelecionados($dados);
        
        echo json_encode($retorno);
    }

    public function listarItensComissaoCalculada(){
		$idComissao = $this->input->post('idComissao');

		$retorno = get_listarItensComissaoCalculada($idComissao);

		echo json_encode($retorno);
	}

    public function buscarUsuariosPorNome(){
        $nome =  $this->input->get('q');
        $nome = str_replace(' ', '%20', $nome);
        $retorno = json_decode(get_usuariosByNome($nome));

        if (!empty($retorno)) {
            echo json_encode($retorno);
        }else{
            return false;
        }
    }

    public function listarUsuarios(){
        $retorno = get_listarUsuariosAll();

        echo json_encode($retorno);
    }

    public function solicitarCalculoVendedoresSelecionados(){
        $dados = $this->input->post();

        $retorno = to_solicitarCalculoVendedoresSelecionados($dados);
        
        echo json_encode($retorno);
    }
    public function listarSolicitacoesCalculoComissao(){
        
        $retorno = get_listarSolicitacoesCalculoComissao();

        echo json_encode($retorno);
    }

    public function listarComissoesCalculadasPorSolicitacao(){
        $idSolicitacao = $this->input->post('idSolicitacao');

        $retorno = get_listarComissoesCalculadasBySolicitacao($idSolicitacao);

        echo json_encode($retorno);
    }

    public function listarMetaPoridConfiguracao(){
        $idConfiguracao = $this->input->post('idConfiguracao');

        $retorno = get_listarMetaByidConfiguracao($idConfiguracao);

        echo json_encode($retorno);
    }

    public function cadastrarMetas(){
        $dados = $this->input->post();

        $retorno = to_cadastrarMetas($dados);
        
        echo json_encode($retorno);
    }

    public function editarMeta(){
        $dados = $this->input->post();

        $retorno = to_editarMeta($dados);
        
        echo json_encode($retorno);
    }

    public function alterarStatusMeta(){
        $dados = $this->input->post();

        $retorno = to_alterarStatusMeta($dados);
        
        echo json_encode($retorno);
    }

    public function listarIndicePoridConfiguracao(){
        $idConfiguracao = $this->input->post('idConfiguracao');

        $retorno = get_listarIndiceByidConfiguracao($idConfiguracao);

        echo json_encode($retorno);
    }

    public function cadastrarIndices(){
        $dados = $this->input->post();

        $retorno = to_cadastrarIndices($dados);
        
        echo json_encode($retorno);
    }

    public function editarIndice(){
        $dados = $this->input->post();

        $retorno = to_editarIndice($dados);
        
        echo json_encode($retorno);
    }

    public function alterarStatusIndice(){
        $dados = $this->input->post();

        $retorno = to_alterarStatusIndice($dados);
        
        echo json_encode($retorno);
    }

    public function cadastrarItensIndice(){
        $dados = $this->input->post();

        $retorno = to_cadastrarItensIndice($dados);
        
        echo json_encode($retorno);
    }

    public function listarItemIndicePorIdIndice(){
        $idIndice = $this->input->post('idIndice');

        $retorno = get_listarItensIndiceByIdIndice($idIndice);

        echo json_encode($retorno);
    }

    public function editaritemIndice(){
        $dados = $this->input->post();

        $retorno = to_editarItemIndice($dados);
        
        echo json_encode($retorno);
    }

    public function alterarStatusItemIndice(){
        $dados = $this->input->post();

        $retorno = to_alterarStatusItemIndice($dados);
        
        echo json_encode($retorno);
    }

    public function listarItensComissaoCalculadaTeveCampanha(){
		$idComissao = $this->input->post('idComissao');

		$retorno = get_listarItensComissaoCalculadaTeveCampanha($idComissao);

		echo json_encode($retorno);
	}
}


