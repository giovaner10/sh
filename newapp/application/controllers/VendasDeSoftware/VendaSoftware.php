<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class VendaSoftware extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
		$this->load->model('cliente');
		$this->load->helper('util_venda_software_helper');
	}

	public function index(){
		$this->auth->is_allowed('vis_vendas_de_software');
		$this->mapa_calor->registrar_acessos_url(site_url('/VendasDeSoftware/VendaSoftware'));

		$idUser = $this->auth->get_login_dados('user');
		$dados['permissoes'] = $this->auth->listar_permissoes_usuario($idUser);
		$dados['titulo'] = lang('vendas_software');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $_SESSION['menu_vendaSoftware'] = 'Produtos';
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('VendasDeSoftware/vendaSoftware');
		$this->load->view('fix/footer_NS');   
	}

	public function buscarProdutosTop100(){
		$habilitaNull = $this->input->get('exibeIdCrm') ? $this->input->get('exibeIdCrm') : '0';
        $dados = get_produtosTop100($habilitaNull);
        echo json_encode($dados);
    }

	public function cadastrarProduto(){
		$dados = array(
			'idCrm' => $this->input->post('idCrm'),
			'nomeProduto' => $this->input->post('nomeProduto'),
			'precoUnitario' => $this->input->post('precoUnitario'),
			'validaQuantidade' => $this->input->post('validaQuantidade'),
			'quantidadeMinima' => $this->input->post('validaQuantidade') == 1 ? $this->input->post('quantidadeMinima') : null,
			'quantidadeMaxima' => $this->input->post('validaQuantidade') == 1 ? $this->input->post('quantidadeMaxima') : null,
			'tipoProduto' => $this->input->post('tipoProduto'),
			'cobrancaExcedente' => false,
			'diasValidadePoc' => $this->input->post('permitePoc') ? $this->input->post('diasValidadePoc') : null,
			'permitePoc' => $this->input->post('permitePoc'),
			'composicoes' => $this->input->post('temComposicao') ? $this->input->post('produtosComposicaoIds') : null
		);

		$resultado = post_produto($dados);
		echo json_encode($resultado);
	}

	public function buscarProdutoPorId(){
		$id = $this->input->post('id');
		$habilitaNull = $this->input->post('exibeIdCrm') ? $this->input->post('exibeIdCrm') : '0';

		$dados = get_produtoById($id, $habilitaNull);
		
		echo json_encode($dados);
	}

	public function buscarComposicaoPorId(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		$dados = get_produtoComposicaoById($id);

		$produtosFiltrados = array();

		if ($status && $dados['status'] == 200 && count($dados['resultado']) > 0) {
			foreach ($dados['resultado'] as $produto) {
				if ($produto['status'] == $status) {
					$produtosFiltrados[] = $produto;
				}
			}
			$dados['resultado']  = $produtosFiltrados;
		}
		
		echo json_encode($dados);
	}

	public function cadastrarProdutoComposicao(){
		$dados = array(
			'idProdutoPrincipal' => $this->input->post('idProdutoPrincipal'),
			'idProdutoComposicao' => $this->input->post('idProdutoComposicao')
		);

		$resultado = post_cadastrarComposicaoProduto($dados);
		echo json_encode($resultado);
	}

	public function alterarStatusComposicao(){
		$dados = array(
			'id' => $this->input->post('id'),
			'status' => $this->input->post('status')
		);

		$resultado = patch_alterarStatusComposicao($dados);
		echo json_encode($resultado);
	}

	public function editarProduto(){
		$dados = array(
			'id' => $this->input->post('id'),
			'idCrm' => $this->input->post('idCrm'),
			'nomeProduto' => $this->input->post('nomeProduto'),
			'precoUnitario' => $this->input->post('precoUnitario'),
			'validaQuantidade' => $this->input->post('validaQuantidade'),
			'quantidadeMinima' => $this->input->post('quantidadeMinima'),
			'quantidadeMaxima' => $this->input->post('quantidadeMaxima'),
			'tipoProduto' => $this->input->post('tipoProduto'),
			'status' => $this->input->post('status'),
			'cobrancaExcedente' => false,
			'temComposicao' => $this->input->post('temComposicao'),
		);

		$resultado = put_produto($dados);
		echo json_encode($resultado);
	}

	public function alterarStatusProduto(){
		$dados = array(
			'id' => $this->input->post('id'),
			'status' => $this->input->post('status')
		);

		$resultado = patch_alterarStatusProduto($dados);
		echo json_encode($resultado);
	}

	public function buscarProdutoPorIdOuNome(){
		$id = $this->input->post('id') ? $this->input->post('id') : '';
		$nome = $this->input->post('nome') ? urlencode($this->input->post('nome')) : '';
		$habilitaNull = $this->input->post('exibeIdCrm') ? $this->input->post('exibeIdCrm') : '0';


		$dados = get_produtoByIdOrName($id, $nome, $habilitaNull);
		
		echo json_encode($dados);
	}

	public function buscarClientes() {
        $data = array('results' => array());

        if ($search = $this->input->get('term')) {
            $clientes = $this->cliente->listarClientesFilter($search);
        }else if ($search = $this->input->get('q')) {
			$clientes = $this->cliente->listarClienteById($search);
		} else {
            $clientes = $this->cliente->listar(array(), 0, 50);
        }

        if ($clientes) {
            foreach ($clientes as $key => $cliente) {
                $data['results'][] = array(
                    'id' => $cliente->id,
                    'text' => $cliente->id.' - '.$cliente->nome
                );
            }
        }

        echo json_encode($data);
    }

	public function listarVendedoresSelect2Propostas(){

        $retorno = array('pagination' =>[
            'more' => false
        ]);

        $retorno += (get_vendedoresPropostas());

        foreach ($retorno['resultado'] as $key => $value) {
            $retorno['resultado'][$key] = array(
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

	public function cadastrarProposta(){
		$dados = array(
			'idCliente' => $this->input->post('idCliente'),
			'idVendedor' => $this->input->post('idVendedor'),
			'af' => null,
			'valorTotal' => '0.00',
			'quantidadeTotal' => '0',
			'formaPagamento' => $this->input->post('formaPagamento'),
			'recorrencia' => $this->input->post('recorrencia'),
			'statusIntegracao' => 0,
			'enderecoFatura' => $this->input->post('enderecoFatura'),
			'enderecoPagamento' => $this->input->post('enderecoPagamento'),
			'nomeAutorizador' => $this->input->post('nomeAutorizador'),
			'documentoAutorizador' => $this->input->post('documentoAutorizador'),
			'emailAutorizador' => $this->input->post('emailAutorizador'),
			'observacao' => $this->input->post('observacaoProposta')
		);

		$resultado = post_proposta($dados);
		echo json_encode($resultado);
	}

	public function buscarPropostasTop100(){
        $dados = get_propostasTop100();
        echo json_encode($dados);
    }

	public function buscarPropostaPorDocumentoVendedorData(){
		$documento = $this->input->post('documento') ? $this->input->post('documento') : '';
		$idVendedor = $this->input->post('idVendedor') ? $this->input->post('idVendedor') : '';
		$dataInicial = $this->input->post('dataInicial') ? $this->input->post('dataInicial') : '';
		$dataFinal = $this->input->post('dataFinal') ? $this->input->post('dataFinal') : '';

		$dados = get_propostaByDocumentoVendedorData($documento, $idVendedor, $dataInicial, $dataFinal);
		
		echo json_encode($dados);
	}

	public function buscarPropostaPorId(){
		$id = $this->input->post('id');

		$dados = get_propostaById($id);

		if ($dados['status'] == '200') {
			$dadosAutorizacao = get_autorizacaoByIdProposta($id);
			if ($dadosAutorizacao['status'] == '200') {
				$dados['resultado'][0]['nomeAutorizador'] = $dadosAutorizacao['resultado'][0]['nomeAutorizador'];
				$dados['resultado'][0]['documentoAutorizador'] = $dadosAutorizacao['resultado'][0]['documentoAutorizador'];
				$dados['resultado'][0]['emailAutorizador'] = $dadosAutorizacao['resultado'][0]['emailAutorizador'];
				$dados['resultado'][0]['observacao'] = $dadosAutorizacao['resultado'][0]['observacao'];
				$dados['resultado'][0]['telefoneAutorizador'] = $dadosAutorizacao['resultado'][0]['telefoneAutorizador'];
			}
		}
		
		echo json_encode($dados);
	}

	public function editarProposta(){
		$dados = array(
			'id' => $this->input->post('id'),
			'idCliente' => $this->input->post('idCliente'),
			'idVendedor' => $this->input->post('idVendedor'),
			'af' => $this->input->post('af') == '-' || $this->input->post('af') == '0' ||  $this->input->post('af') == '' ? null : $this->input->post('af'),
			'valorTotal' => $this->input->post('valorTotal') ? $this->input->post('valorTotal') : '0',
			'quantidadeTotal' => $this->input->post('quantidadeTotal') ? $this->input->post('quantidadeTotal') : '0',
			'formaPagamento' => $this->input->post('formaPagamento'),
			'recorrencia' => $this->input->post('recorrencia'),
			'statusIntegracao' => 2,
			'enderecoFatura' => $this->input->post('enderecoFatura'),
			'enderecoPagamento' => $this->input->post('enderecoPagamento'),
			'status' => $this->input->post('status') ? $this->input->post('status') : 1
		);

		$resultado = put_proposta($dados);
		echo json_encode($resultado);
	}

	public function alterarStatusProposta(){
		$dados = array(
			'id' => $this->input->post('id'),
			'status' => $this->input->post('status')
		);

		$resultado = patch_alterarStatusProposta($dados);
		echo json_encode($resultado);
	}

	public function listarProdutoSelect2(){
		$nomeProduto = $this->input->get('q') ? urlencode($this->input->get('q')) : '';
        $id = $this->input->get('id') ? $this->input->get('id') : '';
		$habilitaNull = $this->input->get('exibeIdCrm') ? $this->input->get('exibeIdCrm') : '0';
		$retorno = array('pagination' =>[
            'more' => false
        ]);

		if ($nomeProduto){
        	$retorno += (get_produtoByIdOrName('', $nomeProduto, $habilitaNull));
		}else if ($id){
			$retorno += (get_produtoByIdOrName($id, '', $habilitaNull));
		}else{
			$retorno += (get_produtosTop100($habilitaNull));
		}

        foreach ($retorno['resultado'] as $key => $value) {
			if (isset($value['status']) && $value['status'] == 'Ativo'){
            	$retorno['resultado'][$key] = array(
            	    'id' => $value['id'],
					'idCrm' => $value['idCrm'],
					'status' => $value['status'],
            	    'text' => $value['nomeProduto'],
					'precoUnitario' => $value['precoUnitario']
            	);
			}
        }

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return json_encode(array(
				'status' => '404'
			));
        }
    }

	public function cadastrarItensProposta(){
		$itens = $this->input->post('dados') ? $this->input->post('dados') : [];

		if (!empty($itens)){
			foreach ($itens as $key => $value) {
				$itens[$key] = array(
					'idProposta' => $value['idProposta'] ? $value['idProposta'] : '',
					'idProduto' => $value['idProduto'] ? $value['idProduto'] : '',
					'quantidade' => $value['quantidade'] ? $value['quantidade'] : '',
					'valorUnitario' => $value['valorUnitario'] ? $value['valorUnitario'] : '',
					'percentualDesconto' => $value['percentualDesconto'] ? $value['percentualDesconto'] : 0,
					'valorTotal' => $value['valorTotal'] ? $value['valorTotal'] : 0,
					'valorDesconto' => $value['valorDesconto'] ? $value['valorDesconto'] : 0,
					'observacao' => $value['observacao'] ? $value['observacao'] : null,
				);
			}
		}

		$dadosEnv = array(
			'propostaItens' => $itens
		);

		$resultado = post_itensProposta($dadosEnv);
		echo json_encode($resultado);
	}

	public function buscarItensPropostaPorIdProposta(){
		$idProposta = $this->input->post('idProposta');

		$dados = get_itensPropostaByIdProposta($idProposta);
		
		echo json_encode($dados);
	}

	public function buscarItemPropostaPorId(){
		$id = $this->input->post('id');

		$dados = get_itemPropostaById($id);
		
		echo json_encode($dados);
	}

	public function editarItemProposta(){
		$dados = array(
			'id' => $this->input->post('id'),
			'idProduto' => $this->input->post('idProduto'),
			'quantidade' => $this->input->post('quantidade'),
			'valorUnitario' => $this->input->post('valorUnitario'),
			'percentualDesconto' => $this->input->post('percentualDesconto') == '' || $this->input->post('percentualDesconto') == '%' ? 0 : $this->input->post('percentualDesconto'),
			'valorTotal' => $this->input->post('valorTotal'),
			'valorDesconto' => $this->input->post('valorDesconto'),
			'observacao' => $this->input->post('observacao') ? $this->input->post('observacao') : null,
		);

		$resultado = put_itemProposta($dados);
		echo json_encode($resultado);
	}

	public function alterarStatusItemProposta(){
		$dados = array(
			'id' => $this->input->post('id'),
			'status' => 0
		);

		$resultado = patch_alterarStatusItemProposta($dados);
		echo json_encode($resultado);
	}

	public function cadastrarPropostaEndereco(){
		$enderecoFaturamento = $this->input->post('enderecoFatura') == 1 ? array(
			'tipoEndereco' => 1,
			'cep' => $this->input->post('cepFatura'),
			'logradouro' => $this->input->post('ruaFatura'),
			'numero' => $this->input->post('numeroFatura'),
			'bairro' => $this->input->post('bairroFatura'),
			'complemento' => $this->input->post('complementoFatura') ? $this->input->post('complementoFatura') : null,
			'cidade' => $this->input->post('cidadeFatura'),
			'uf' => $this->input->post('ufFatura'),
		)
		: [];

		$enderecoPagamento = $this->input->post('enderecoPagamento') == 1 ? array(
			'tipoEndereco' => 2,
			'cep' => $this->input->post('cepPagamento'),
			'logradouro' => $this->input->post('ruaPagamento'),
			'numero' => $this->input->post('numeroPagamento'),
			'bairro' => $this->input->post('bairroPagamento'),
			'complemento' => $this->input->post('complementoPagamento') ? $this->input->post('complementoPagamento') : null,
			'cidade' => $this->input->post('cidadePagamento'),
			'uf' => $this->input->post('ufPagamento'),
		)
		: [];

		$dados = array(
			'idCliente' => $this->input->post('idCliente'),
			'idVendedor' => $this->input->post('idVendedor'),
			'af' => null,
			'valorTotal' => '0.00',
			'quantidadeTotal' => '0',
			'formaPagamento' => $this->input->post('formaPagamento'),
			'recorrencia' => $this->input->post('recorrencia'),
			'statusIntegracao' => 0,
			'enderecoFatura' => $this->input->post('enderecoFatura'),
			'enderecoPagto' => $this->input->post('enderecoPagamento'),
			'enderecoFaturamento' => $enderecoFaturamento ? [$enderecoFaturamento] : [],
			'enderecoPagamento' => $enderecoPagamento ? [$enderecoPagamento] : [],
			'nomeAutorizador' => $this->input->post('nomeAutorizador'),
			'documentoAutorizador' => $this->input->post('documentoAutorizador'),
			'emailAutorizador' => $this->input->post('emailAutorizador'),
			'observacao' => $this->input->post('observacaoProposta') ? $this->input->post('observacaoProposta') : null,
			'telefoneAutorizador' => $this->input->post('telefoneAutorizador'),
			'dataVencimento' => $this->input->post('dataVencimento') ? $this->input->post('dataVencimento') : null,
			'diaVencimento' => $this->input->post('diaVencimento') ? $this->input->post('diaVencimento') : '',
			'permitePoc' => $this->input->post('permitePOC') ? $this->input->post('permitePOC') : 0,
			'idOportunidade' => $this->input->post('idOportunidade') ? $this->input->post('idOportunidade') : null
		);

		$resultado = post_propostaEndereco($dados);
		echo json_encode($resultado);
	}

	public function editarPropostaEndereco(){
		$enderecoFaturamento = $this->input->post('enderecoFatura') == 1 ? array(
			'id' => $this->input->post('idEnderecoFatura'),
			'tipoEndereco' => 1,
			'cep' => $this->input->post('cepFatura'),
			'logradouro' => $this->input->post('ruaFatura'),
			'numero' => $this->input->post('numeroFatura'),
			'bairro' => $this->input->post('bairroFatura'),
			'complemento' => $this->input->post('complementoFatura') ? $this->input->post('complementoFatura') : null,
			'cidade' => $this->input->post('cidadeFatura'),
			'uf' => $this->input->post('ufFatura'),
			'status' => '1'
		)
		: [];

		$enderecoPagamento = $this->input->post('enderecoPagamento') == 1 ? array(
			'id' => $this->input->post('idEnderecoPagamento'),
			'tipoEndereco' => 2,
			'cep' => $this->input->post('cepPagamento'),
			'logradouro' => $this->input->post('ruaPagamento'),
			'numero' => $this->input->post('numeroPagamento'),
			'bairro' => $this->input->post('bairroPagamento'),
			'complemento' => $this->input->post('complementoPagamento') ? $this->input->post('complementoPagamento') : null,
			'cidade' => $this->input->post('cidadePagamento'),
			'uf' => $this->input->post('ufPagamento'),
			'status' => '1'
		)
		: [];

		$dados = array(
			'id' => $this->input->post('id'),
			'idCliente' => $this->input->post('idCliente'),
			'idVendedor' => $this->input->post('idVendedor'),
			'af' => $this->input->post('af') == '-' || $this->input->post('af') == '0' ||  $this->input->post('af') == '' ? null : $this->input->post('af'),
			'valorTotal' => $this->input->post('valorTotal') ? $this->input->post('valorTotal') : '0',
			'quantidadeTotal' => $this->input->post('quantidadeTotal') ? $this->input->post('quantidadeTotal') : '0',
			'formaPagamento' => $this->input->post('formaPagamento'),
			'recorrencia' => $this->input->post('recorrencia'),
			'statusIntegracao' => 2,
			'enderecoFatura' => $this->input->post('enderecoFatura'),
			'enderecoPagto' => $this->input->post('enderecoPagamento'),
			'status' => $this->input->post('status') ? $this->input->post('status') : 1,
			'enderecoFaturamento' => $enderecoFaturamento ? [$enderecoFaturamento] : [],
			'enderecoPagamento' => $enderecoPagamento ? [$enderecoPagamento] : [],
			'nomeAutorizador' => $this->input->post('nomeAutorizador'),
			'documentoAutorizador' => $this->input->post('documentoAutorizador'),
			'emailAutorizador' => $this->input->post('emailAutorizador'),
			'observacao' => $this->input->post('observacaoProposta'),
			'telefoneAutorizador' => $this->input->post('telefoneAutorizador'),
			'dataVencimento' => $this->input->post('dataVencimento') ? $this->input->post('dataVencimento') : null,
			'diaVencimento' => $this->input->post('diaVencimento') ? $this->input->post('diaVencimento') : '',
			'permitePoc' => $this->input->post('permitePOC') ? $this->input->post('permitePOC') : 0,
		);

		$resultado = put_propostaEndereco($dados);
		echo json_encode($resultado);
	}

	public function buscarAutorizacaoPorParametros(){
		$idProposta = $this->input->post('idProposta') ? $this->input->post('idProposta') : '';
		$documento = $this->input->post('documento') ? $this->input->post('documento') : '';
		$idVendedor = $this->input->post('idVendedor') ? $this->input->post('idVendedor') : '';

		$dados = get_autorizacaoFaturamentoByParams($idProposta, $documento, $idVendedor);
		
		echo json_encode($dados);
	}

	public function buscarTodasAutorizacoesPaginacao(){
		$numeroPagina = (int) $this->input->post('numeroPagina');
		$tamanhoPagina = (int) $this->input->post('tamanhoPagina');

		$numeroPagina++;
		
		$dados = get_autorizacaoAllPagination($numeroPagina, $tamanhoPagina);

		if ($dados['status'] == '200'){
			echo json_encode(array(
				"success" => true,
				"resultado" => $dados['resultado']['autorizacoes'],
				"totalLinhas" => $dados['resultado']['qtdTotalAutorizacoes']
			));
		}else{
			echo json_encode(array(
				"success" => false,
				"message" => $dados['resultado']['mensagem'] ? $dados['resultado']['mensagem'] : ''
			));
		}
	}

	public function buscarEnderecoClienteId(){
		$idCliente = $this->input->get('idCliente');

		$dados = get_enderecoClienteByIdCliente($idCliente);
		
		echo json_encode($dados);
	}

	public function buscarClientesPorParametrosPaginado(){
		$numeroPagina = (int) $this->input->post('numeroPagina');
		$tamanhoPagina = (int) $this->input->post('tamanhoPagina');
		$idCliente = $this->input->post('idCliente') ? $this->input->post('idCliente') : '';
		$nomeCliente = $this->input->post('nomeCliente') ? urlencode($this->input->post('nomeCliente')) : '';
		$documento = $this->input->post('documento') ? $this->input->post('documento') : '';

		$numeroPagina++;
		
		$dados = get_clientesByParamsPagination($idCliente, $nomeCliente, $documento, $numeroPagina, $tamanhoPagina);

		if ($dados['status'] == '200'){
			echo json_encode(array(
				"success" => true,
				"resultado" => $dados['resultado']['clientesDTO'],
				"totalLinhas" => $dados['resultado']['qtdTotalClientes']
			));
		}else{
			echo json_encode(array(
				"success" => false,
				"message" => $dados['resultado']['mensagem'] ? $dados['resultado']['mensagem'] : 'Não foi possível buscar os clientes. Tente novamente.'
			));
		}
	}

	public function cadastrarCliente(){
		$dadosTelefone = array(
			'setor' => $this->input->post('setorClienteTelefone'),
			'observacao' => $this->input->post('obsClienteTelefone') ? $this->input->post('obsClienteTelefone') : '',
		);

		$dadosEmail = array(
			'setor' => $this->input->post('setorClienteEmail'),
			'observacao' => $this->input->post('obsClienteEmail') ? $this->input->post('obsClienteEmail') : '',
		);

		$dados = array(
			'nome' => $this->input->post('nomeCliente'),
			'rg' => $this->input->post('identidadeCliente') ? $this->input->post('identidadeCliente') : null,
			'orgaoExp' => $this->input->post('identidadeCliente') ? $this->input->post('orgaoExpedidor') : null,
			'cpf' => $this->input->post('cpfCliente'),
			'cnpj' => $this->input->post('cnpjCliente'),
			'inscricaoEstadual' => $this->input->post('inscricaoEstadualCliente') ? $this->input->post('inscricaoEstadualCliente') : null,
			'razaoSocial' => $this->input->post('razaoSocialCliente'),
			'dataNascimento' => $this->input->post('dataNascimentoCliente'),
			'nomeEmpresa' => 'OMNILINK',
			'cep' => $this->input->post('cepCliente'),
			'rua' => $this->input->post('ruaCliente'),
			'numero' => $this->input->post('numeroCliente'),
			'bairro' => $this->input->post('bairroCliente'),
			'cidade' => $this->input->post('cidadeCliente') ,
			'uf' => $this->input->post('ufCliente'),
			'complemento' => $this->input->post('complementoCliente') ? $this->input->post('complementoCliente') : null,
			'telefone' => $this->input->post('telefoneCliente'),
			'email' => $this->input->post('emailCliente'),
			'contatoTelefone' => $dadosTelefone,
			'contatoEmail' => $dadosEmail
		);

		if ($this->input->post('cpfCliente')){
			unset($dados['cnpj']);
			unset($dados['inscricaoEstadual']);
			unset($dados['razaoSocial']);
		}

		if ($this->input->post('cnpjCliente')){
			unset($dados['cpf']);
			unset($dados['rg']);
			unset($dados['orgaoExp']);
			unset($dados['dataNascimento']);
		}

		$resultado = post_cadastrarCliente($dados);
		echo json_encode($resultado);
	}
	
	public function enviarEmailAutorizacao(){
		$idProposta = $this->input->post('idProposta');

		$dados = post_enviarEmailAutorizacao($idProposta);
		
		echo json_encode($dados);
	}

	public function buscarVendedor(){
		$email = $this->auth->get_login_dados('email');

		$dados = get_buscarVendedorByEmail($email);

		echo json_encode($dados);
	}

	public function buscarSugestaoPorIdProposta(){
		$idProposta = $this->input->post('idProposta');

		$dados = get_buscarSugestaoByIdProposta($idProposta);
		
		echo json_encode($dados);
	}

	public function buscarOportunidadesPorParametrosPaginado(){
		$itemInicio = (int) $this->input->post('itemInicio');
		$itemFim = (int) $this->input->post('itemFim');
		$idVendedor = $this->input->post('idVendedor') ? $this->input->post('idVendedor') : '';
		$documentoCliente = $this->input->post('documentoCliente') ? $this->input->post('documentoCliente') : '';
		$dataInicial = $this->input->post('dataInicial') ? $this->input->post('dataInicial') : '';
		$dataFinal = $this->input->post('dataFinal') ? $this->input->post('dataFinal') : '';

		$itemInicio++;
		
		$dados = get_buscarOportunidadesByParamsPaginado($dataInicial, $dataFinal, $idVendedor, $documentoCliente, $itemInicio, $itemFim);

		if ($dados['status'] == '200'){
			echo json_encode(array(
				"success" => true,
				"resultado" => $dados['resultado']['oportunidades'],
				"totalLinhas" => $dados['resultado']['qtdOportunidades']
			));
		}else{
			echo json_encode(array(
				"success" => false,
				"message" => $dados['resultado']['mensagem'] ? $dados['resultado']['mensagem'] : 'Não foi possível buscar a(s) oportunidade(s). Tente novamente.'
			));
		}
	}

	public function cadastrarOportunidade(){
		$enderecoFaturamento = $this->input->post('enderecoFatura') == 1 ? array(
			'tipoEndereco' => 1,
			'cep' => $this->input->post('cepFatura'),
			'logradouro' => $this->input->post('ruaFatura'),
			'numero' => $this->input->post('numeroFatura'),
			'bairro' => $this->input->post('bairroFatura'),
			'complemento' => $this->input->post('complementoFatura') ? $this->input->post('complementoFatura') : null,
			'cidade' => $this->input->post('cidadeFatura'),
			'uf' => $this->input->post('ufFatura'),
		)
		: [];

		$enderecoPagamento = $this->input->post('enderecoPagamento') == 1 ? array(
			'tipoEndereco' => 2,
			'cep' => $this->input->post('cepPagamento'),
			'logradouro' => $this->input->post('ruaPagamento'),
			'numero' => $this->input->post('numeroPagamento'),
			'bairro' => $this->input->post('bairroPagamento'),
			'complemento' => $this->input->post('complementoPagamento') ? $this->input->post('complementoPagamento') : null,
			'cidade' => $this->input->post('cidadePagamento'),
			'uf' => $this->input->post('ufPagamento'),
		)
		: [];

		$itensOportunidade = $this->input->post('itensOportunidade') ? $this->input->post('itensOportunidade') : [];

		if (!empty($itensOportunidade)){
			foreach ($itensOportunidade as $key => $value) {
				$itensOportunidade[$key] = array(
					'idProduto' => $value['idProduto'] ? $value['idProduto'] : null,
					'quantidade' => $value['quantidade'] ? $value['quantidade'] : '',
					'valorUnitario' => $value['valorUnitario'] ? $value['valorUnitario'] : null,
					'percentualDesconto' => $value['percentualDesconto'] ? $value['percentualDesconto'] : 0,
					'valorTotal' => $value['valorTotal'] ? $value['valorTotal'] : 0,
					'valorDesconto' => $value['valorDesconto'] ? $value['valorDesconto'] : 0,
					'observacao' => $value['observacao'] ? $value['observacao'] : null,
				);
			}
		}

		$dados = array(
			'documentoCliente' => $this->input->post('documentoCliente'),
			'idVendedor' => $this->input->post('idVendedor'),
			'nomeCliente' => $this->input->post('nomeCliente'),
			'emailCliente' => $this->input->post('emailCliente'),
			'formaPagamento' => $this->input->post('formaPagamento'),
			'recorrencia' => $this->input->post('recorrencia'),
			'enderecoFatura' => $this->input->post('enderecoFatura'),
			'enderecoPagamento' => $this->input->post('enderecoPagamento'),
			'enderecosFaturaList' => $enderecoFaturamento ? [$enderecoFaturamento] : [],
			'enderecosPagamentoList' => $enderecoPagamento ? [$enderecoPagamento] : [],
			'dataVencimento' => $this->input->post('dataVencimento') ? $this->input->post('dataVencimento') : null,
			'diaVencimento' => $this->input->post('diaVencimento') ? $this->input->post('diaVencimento') : '',
			'permitePoc' => $this->input->post('permitePOC') ? $this->input->post('permitePOC') : 0,
			'oportunidadeItens' => $itensOportunidade
		);

		$resultado = post_oportunidade($dados);
		echo json_encode($resultado);
	}

	public function buscarOportunidadePorId(){
		$id = $this->input->post('id');

		$dados = get_oportunidadeById($id);

		echo json_encode($dados);
	}

	public function buscarItensOportunidadePorIdOportunidade(){
		$idProposta = $this->input->post('idOportunidade');

		$dados = get_itensOportunidadeByIdOportunidade($idProposta);
		
		echo json_encode($dados);
	}

	public function inativarItemOportunidade(){
		$dados = array(
			'id' => $this->input->post('id'),
			'status' => 0
		);

		$resultado = patch_alterarStatusItemOportunidade($dados);
		echo json_encode($resultado);
	}

	public function buscarItemOportunidadePorId(){
		$id = $this->input->post('id');

		$dados = get_itemOportunidadeById($id);
		
		echo json_encode($dados);
	}

	public function editarItemOportunidade(){
		$dados = array(
			'id' => $this->input->post('id'),
			'idOportunidade' => $this->input->post('idOportunidade'),
			'idProduto' => $this->input->post('idProduto'),
			'quantidade' => $this->input->post('quantidade'),
			'valorUnitario' => $this->input->post('valorUnitario'),
			'percentualDesconto' => $this->input->post('percentualDesconto') == '' || $this->input->post('percentualDesconto') == '%' ? 0 : $this->input->post('percentualDesconto'),
			'valorTotal' => $this->input->post('valorTotal'),
			'valorDesconto' => $this->input->post('valorDesconto'),
			'observacao' => $this->input->post('observacao') ? $this->input->post('observacao') : null,
		);

		$resultado = put_itemOportunidade($dados);
		echo json_encode($resultado);
	}

	public function cadastrarItensOportunidade(){
		/* $dados = $this->input->post('dados'); */

		$itensOportunidade = $this->input->post('dados') ? $this->input->post('dados') : [];

		if (!empty($itensOportunidade)){
			foreach ($itensOportunidade as $key => $value) {
				$itensOportunidade[$key] = array(
					'idOportunidade' => $value['idOportunidade'] ? $value['idOportunidade'] : '',
					'idProduto' => $value['idProduto'] ? $value['idProduto'] : null,
					'quantidade' => $value['quantidade'] ? $value['quantidade'] : '',
					'valorUnitario' => $value['valorUnitario'] ? $value['valorUnitario'] : null,
					'percentualDesconto' => $value['percentualDesconto'] ? $value['percentualDesconto'] : 0,
					'valorTotal' => $value['valorTotal'] ? $value['valorTotal'] : 0,
					'valorDesconto' => $value['valorDesconto'] ? $value['valorDesconto'] : 0,
					'observacao' => $value['observacao'] ? $value['observacao'] : null,
				);
			}
		}

		$dadosEnv = array(
			'oportunidadeItens' => $itensOportunidade
		);

		$resultado = post_itensOportunidade($dadosEnv);
		echo json_encode($resultado);
	}

	public function editarOportunidade(){
		$enderecoFaturamento = $this->input->post('enderecoFatura') == 1 ? array(
			'id' => $this->input->post('idEnderecoFatura'),
			'cep' => $this->input->post('cepFatura'),
			'logradouro' => $this->input->post('ruaFatura'),
			'numero' => $this->input->post('numeroFatura'),
			'bairro' => $this->input->post('bairroFatura'),
			'complemento' => $this->input->post('complementoFatura') ? $this->input->post('complementoFatura') : null,
			'cidade' => $this->input->post('cidadeFatura'),
			'uf' => $this->input->post('ufFatura'),
		)
		: [];

		$enderecoPagamento = $this->input->post('enderecoPagamento') == 1 ? array(
			'id' => $this->input->post('idEnderecoPagamento'),
			'cep' => $this->input->post('cepPagamento'),
			'logradouro' => $this->input->post('ruaPagamento'),
			'numero' => $this->input->post('numeroPagamento'),
			'bairro' => $this->input->post('bairroPagamento'),
			'complemento' => $this->input->post('complementoPagamento') ? $this->input->post('complementoPagamento') : null,
			'cidade' => $this->input->post('cidadePagamento'),
			'uf' => $this->input->post('ufPagamento'),
		)
		: [];

		$dados = array(
			'id' => $this->input->post('id'),
			'documentoCliente' => $this->input->post('documentoCliente'),
			'idVendedor' => $this->input->post('idVendedor'),
			'nomeCliente' => $this->input->post('nomeCliente'),
			'emailCliente' => $this->input->post('emailCliente'),
			'formaPagamento' => $this->input->post('formaPagamento'),
			'recorrencia' => $this->input->post('recorrencia'),
			'enderecoFatura' => $this->input->post('enderecoFatura'),
			'enderecoPagamento' => $this->input->post('enderecoPagamento'),
			'enderecosFaturaList' => $enderecoFaturamento ? [$enderecoFaturamento] : [],
			'enderecosPagamentoList' => $enderecoPagamento ? [$enderecoPagamento] : [],
			'dataVencimento' => $this->input->post('dataVencimento') ? $this->input->post('dataVencimento') : null,
			'diaVencimento' => $this->input->post('diaVencimento') ? $this->input->post('diaVencimento') : '',
			'permitePoc' => $this->input->post('permitePOC') ? $this->input->post('permitePOC') : 0,
		);

		$resultado = put_oportunidade($dados);
		echo json_encode($resultado);
	}

	public function alterarStatusOportunidade(){
		$dados = array(
			'id' => $this->input->post('id'),
			'statusOportunidade' => $this->input->post('status')
		);

		$resultado = patch_alterarStatusOportunidade($dados);
		echo json_encode($resultado);
	}

	// Kanban Autorização
	function buscarAutorizacoes() {

		$retorno = get_autorizacoes();

		echo json_encode($retorno);
	}
}