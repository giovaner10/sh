<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class AdministracoesPessoais extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged("admin");

		$this->load->model("usuario");
		$this->load->model("colaborador");
		$this->load->model("colaborador_dependente", "colaboradorDependente");
		$this->load->model("documento_pendente", "documentoPendente");
        $this->load->model('mapa_calor');
		$this->load->model("estado_civil", "estadoCivil");
		$this->load->model("escolaridade");
		$this->load->model("estado");
		$this->load->model("cor_pele", "corPele");
		$this->load->model("parentesco");
        $this->load->model('auth');

		$this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();

        # Models in model
        $this->load->model("documento_pendente_arquivo", "documentoPendenteArquivo");

        # Vars
        $this->extensoesPermitidasDocsPendentes = "pptx|ppt|pdf|doc|docx|xlsx|xls|png|jpg|jpeg";
	}

    public function index()
    {
        $dados["titulo"] = lang("administracao_pessoal");
        $dados["colaboradorDependentes"] = [];
        $funcionarioId = $this->auth->get_login_dados()["user"];

        $dados["usuario"] = $this->usuario->get(["id" => $funcionarioId]);

        # get documentos pendentes
	    $documentoPendentes = $this->documentoPendente->get(
            [
                "id_usuario" => $funcionarioId,
                "recebido" => "nao"
            ]
        );
        
        $dados["documentosPendentes"] = count($documentoPendentes) > 0 ? $documentoPendentes[0] : [];

        # get colaborador
        $colaborador = $this->colaborador->getColaborador($funcionarioId);

        # get dependentes
        if (isset($colaborador->id))
            $dados["colaboradorDependentes"] = $this->colaboradorDependente->get($colaborador->id);
        
        # dados de seção
        if (empty($colaborador->nome))
            $colaborador->nome = $this->auth->get_login('admin', 'nome');
        if (empty($colaborador->cpf))
            $colaborador->cpf = "";

        $dados["colaborador"] = $colaborador;

        # get dados selects
        $dados["estadosCivis"] = $this->estadoCivil->get();
        $dados["escolaridades"] = $this->escolaridade->get();
        $dados["estados"] = $this->estado->get();
        $dados["corPeles"] = $this->corPele->get();
        $dados["parentescos"] = $this->parentesco->get();
        
        $dados["load"] = ["mask", "select-chosen"];
        $this->mapa_calor->registrar_acessos_url(site_url('/GentesGestoes/AdministracoesPessoais'));
		$this->load->view("fix/header_NS", $dados);
		$this->load->view("gente_gestao/administracao_pessoal/index");
		$this->load->view("fix/footer_NS");
    }

    public function bradescoSaude()
    {
        $dados["modalTitulo"] = lang("bradesco_saude");

		$this->load->view("gente_gestao/administracao_pessoal/bradesco_saude", $dados);
    }

    public function colaboradorAtualizar()
    {
        try
        {
            $dados = $this->input->post();

            # Fomata números de telefone|celular para o banco
            $telefoneResidencial = preg_replace("/[^0-9]/", "", $dados['telefone_residencial']);
            $celular = preg_replace("/[^0-9]/", "", $dados['celular']);
            $telefoneEmergencia = preg_replace("/[^0-9]/", "", $dados['telefone_emergencia']);

            # Colaborador
            $dadosColaborador = array(
                'id_usuario' => $dados['usuarioId'],
                'nome' => $dados['nome'],
                'sexo' => $dados['sexo'],
                'data_nascimento' => $dados['data_nascimento'],
                'id_estado_civil' => $dados['id_estado_civil'],
                'id_escolaridade' => $dados['id_escolaridade'],
                'naturalidade' => $dados['naturalidade'],
                'nacionalidade' => $dados['nacionalidade'],
                'pai' => $dados['pai'],
                'mae' => $dados['mae'],
                'cpf' => $dados['cpf'],
                'rg' => $dados['rg'],
                'rg_orgao' => $dados['rg_orgao'],
                'rg_id_estado' => $dados['rg_id_estado'],
                'rg_expedicao' => $dados['rg_expedicao'],
                'reservista' => $dados['reservista'],
                'pis' => $dados['pis'],
                'pis_expedicao' => $dados['pis_expedicao'],
                'cnh' => $dados['cnh'],
                'cnh_validade' => $dados['cnh_validade'],
                'cnh_expedicao' => $dados['cnh_expedicao'],
                'cnh_orgao' => $dados['cnh_orgao'],
                'cnh_id_estado' => $dados['cnh_id_estado'] ? $dados['cnh_id_estado'] : null,
                'cnh_primeiro' => $dados['cnh_primeiro'],
                'ctps' => $dados['ctps'],
                'ctps_serie' => $dados['ctps_serie'],
                'ctps_id_estado' => $dados['ctps_id_estado'],
                'ctps_expedicao' => $dados['ctps_expedicao'],
                'titulo_eleitor' => $dados['titulo_eleitor'],
                'titulo_zona' => $dados['titulo_zona'],
                'titulo_secao' => $dados['titulo_secao'],
                'titulo_municipio' => $dados['titulo_municipio'],
                'cep' => $dados['cep'],
                'endereco' => $dados['endereco'],
                'endereco_numero' => $dados['endereco_numero'],
                'endereco_complemento' => $dados['endereco_complemento'],
                'bairro' => $dados['bairro'],
                'cidade' => $dados['cidade'],
                'estado' => $dados['estado'],
                'telefone_residencial' => $telefoneResidencial,
                'celular' => $celular,
                'telefone_emergencia' => $telefoneEmergencia,
                'emergencia_contato' => $dados['emergencia_contato'],
                'email_particular' => $dados['email_particular'],
                'email_corporativo' => $dados['email_corporativo'],
                'id_cor_pele' => $dados['id_cor_pele'],
                'cns' => $dados['cns'],
                'ppd' => $dados['ppd'],
                'aposentado' => $dados['aposentado'],
                'filhos' => $dados['filhos'],
                'gestante' => $dados['gestante'],
                'dependentes' => $dados['dependentes']
            );

            $colaboradorId = $dados['colaboradorId'];

            if ($colaboradorId)
                $this->colaborador->editar($colaboradorId, $dadosColaborador);
            else
                $colaboradorId = $this->colaborador->adicionar($dadosColaborador);

            # Dependentes
            if ($dados['dependentes'] == 1 && !empty($dados["dependentes_operacao"]))
            {
                foreach ($dados["dependentes_operacao"] as $p)
                {
                    $dadosColaboradorDependente = array(
                        'id_usuario' => $dados['usuarioId'],
                        'id_cad_colaborador' => $colaboradorId,
                        'nome' => $dados["dependente_nome_$p"],
                        'id_estado_civil' => $dados["dependente_id_estado_civil_$p"],
                        'cpf' => $dados["dependente_cpf_$p"],
                        'id_parentesco' => $dados["dependente_id_parentesco_$p"],
                        'sexo' => $dados["dependente_sexo_$p"],
                        'data_nascimento' => $dados["dependente_data_nascimento_$p"],
                        'mae' => $dados["dependente_mae_$p"],
                        'data_casamento' => $dados["dependente_data_casamento_$p"],
                        'naturalidade' => $dados["dependente_naturalidade_$p"],
                        'id_cor_pele' => $dados["dependente_id_cor_pele_$p"],
                        'ppd' => $dados["dependente_ppd_$p"],
                        'cns' => $dados["dependente_cns_$p"],
                        'cartorio' => $dados["dependente_cartorio_$p"],
                        'registro' => $dados["dependente_registro_$p"],
                        'declar_vivo' => $dados["dependente_declar_vivo_$p"],
                        'irrf' => $dados["dependente_irrf_$p"]
                    );

                    $depentendeId = isset($dados["dependente_id_$p"]) ? $dados["dependente_id_$p"] : '';

                    if ($depentendeId)
                        $this->colaboradorDependente->editar($depentendeId, $dadosColaboradorDependente);
                    else
                        $this->colaboradorDependente->adicionar($dadosColaboradorDependente);
                }
            }

            echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
        }
        catch (Exception $e)
        {
            echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
        }
    }

    public function dependenteExcluir($id)
    {
        try
        {
            $this->colaboradorDependente->excluir($id);

            echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
        }
        catch (Exception $e)
        {
            echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
        }
    }

    public function documentosPendentesEnviarArquivos()
    {
        try
        {
            $funcionarioId = $this->auth->get_login_dados()["user"];
            $documentosPendentesId = $this->documentoPendente->get([
                "id_usuario" => $funcionarioId,
                "recebido" => "nao",
                "status" => "ativo"
            ])[0]->id;

            $arquivoNome = "";
            $path = "";
            $arquivo = isset($_FILES) ? $_FILES['arquivos']['name'] : false;

            if (!$arquivo)
                throw new Exception(lang("nenhum_arquivo_selecionado"));
            
            $count = count($_FILES['arquivos']['name']);

            for ($i = 0; $i < $count; $i ++)
            {
                if (!empty($_FILES['arquivos']['name'][$i]))
                {
                    # Variáveis temporárias para salvar um arquivo por vez
                    $_FILES['arquivo']['name'] = $_FILES['arquivos']['name'][$i];
                    $_FILES['arquivo']['type'] = $_FILES['arquivos']['type'][$i];
                    $_FILES['arquivo']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
                    $_FILES['arquivo']['error'] = $_FILES['arquivos']['error'][$i];
                    $_FILES['arquivo']['size'] = $_FILES['arquivos']['size'][$i];
                    
                    # Valida extensao do arquivo
				    $this->uploadHelper->validaExtensao($this->extensoesPermitidasDocsPendentes, "arquivo");

        			$arquivoDados = $this->uploadHelper->upload(
                        "./uploads/docs_pendentes",
                        "arquivo",
                        $this->extensoesPermitidasDocsPendentes
                    );

                    $arquivoNome = $arquivoDados["file_name"];
                    $arquivo = $arquivoDados["full_path"];

                    $this->documentoPendenteArquivo->adicionar($documentosPendentesId, $funcionarioId, $arquivoNome, $path);
                }
            }

            echo json_encode([
                "status" => 1,
                "mensagem" => lang("mensagem_sucesso")
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                "status" => 0,
                "mensagem" => $e->getMessage()
            ]);
        }
    }

    # Carrega view - Formulário de administração de documentos
    public function documentosPendentesAdministrar()
    {
        $dados["modalTitulo"] = lang("solicitacao_documentos");
		$this->load->view("gente_gestao/administracao_pessoal/documentos_pendentes_adm", $dados);
    }

    public function documentosPendentesListar()
    {
        $documentosPendentes = $this->documentoPendente->getDocumentosPendentesUsuarios();

        $data = [];
        $x = 0;
        foreach ($documentosPendentes as $documentoPendente)
        {
            $data[$x] =
            [
                $documentoPendente->funcionario,
                $documentoPendente->documentos,
                ucwords($documentoPendente->status_documentos),
                $documentoPendente->id, # arquivos
                $documentoPendente->id # acoes
            ];
            $x++;
        }

		echo json_encode(["data" => $data]);
    }

    public function documentosPendentesFormulario($documentoPendenteId = null)
	{
		$dados = [];

        $dados["funcionarios"] = $this->usuario->listarFuncionarios(
			"id, nome", # select
			["status" => 1], # where
			"nome ASC" # order
		);
        
		if ($documentoPendenteId)
		{
			$dados["modalTitulo"] = lang("editar_solicitacao");
			$dados["documentoPendente"] = $this->documentoPendente->getDocumentoPendente($documentoPendenteId);
		}
		else
			$dados["modalTitulo"] = lang("nova_solicitacao");

		$this->load->view("gente_gestao/administracao_pessoal/documento_pendente", $dados);
	}

    public function documentosPendentesAdicionar()
    {
        try
        {
            $dados = $this->input->post();

            # documento obrigatorio
            if (empty($dados["documentos"]))
                throw new Exception(lang("selecione_um_ou_mais_documentos"));
            
            # valida se ja existe solicitacao para esse funcionario
            if ($this->documentoPendente->verficarSeExiste($dados["funcionario_id"]))
                throw new Exception(lang("documentacao_ja_solicitada"));


            # trata documentos
            $documentos = $this->formataDocumentosParaBD($dados["documentos"]);

            $this->documentoPendente->adicionar(
            [
                "id_usuario" => $dados["funcionario_id"],
                "residencia" => $documentos["residencia"],
                "cpf" => $documentos["cpf"],
                "rg" => $documentos["rg"],
                "banco" => $documentos["banco"],
                "status_documentos" => 'pendente',
                "data_solicitacao" => date("Y-m-d"),
                "prazo_maximo" => $dados["prazo_entrega"]
            ]);

            echo json_encode([
                "status" => 1,
                "mensagem" => lang("mensagem_sucesso")
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                "status" => 0,
                "mensagem" => $e->getMessage()
            ]);
        }
    }

    public function documentosPendentesEditar($id)
    {
        try
        {
            $dados = $this->input->post();

            # documento obrigatorio
            if (count($dados["documentos"]) == 0)
                throw new Exception(lang("selecione_um_ou_mais_documentos"));

            $documentoPendente = $this->documentoPendente->getDocumentoPendente($id);

            # se houver alteração de funcionário
            if ($documentoPendente->id_usuario != $dados["funcionario_id"])
            {
                # valida se ja existe solicitacao para esse funcionario
                if ($this->documentoPendente->verficarSeExiste($dados["funcionario_id"]))
                    throw new Exception(lang("documentacao_ja_solicitada"));
            }

            # trata documentos
            $documentos = $this->formataDocumentosParaBD($dados["documentos"]);

            $this->documentoPendente->editar($id,
            [
                "id_usuario" => $dados["funcionario_id"],
                "residencia" => $documentos["residencia"],
                "cpf" => $documentos["cpf"],
                "rg" => $documentos["rg"],
                "banco" => $documentos["banco"],
    	        'recebido' => isset($dados["recebido"]) ? "sim" : "nao",
                "data_entrega" => isset($dados["recebido"]) ? date("Y-m-d") : null,
                "prazo_maximo" => $dados["prazo_entrega"]
            ]);

            echo json_encode([
                "status" => 1,
                "mensagem" => lang("mensagem_sucesso")
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                "status" => 0,
                "mensagem" => $e->getMessage()
            ]);
        }
    }

    public function documentosPendentesExcluir($id)
    {
        try
        {
            $this->documentoPendente->excluir($id);

            echo json_encode([
                "status" => 1,
                "mensagem" => lang("mensagem_sucesso")
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                "status" => 0,
                "mensagem" => $e->getMessage()
            ]);
        }
    }

    public function documentosPendentesAtualizarListagem()
    {
        $funcionarioId = $this->auth->get_login_dados()["user"];

        # get documentos pendentes
	    $documentoPendentes = $this->documentoPendente->get(
            [
                "id_usuario" => $funcionarioId,
                "recebido" => "nao"
            ]
        );
        
        $dados["documentosPendentes"] = count($documentoPendentes) > 0 ? $documentoPendentes[0] : [];
        $dados["usuario"] = $this->usuario->get(["id" => $funcionarioId]);

        $this->load->view("gente_gestao/administracao_pessoal/documentos_pendentes", $dados);
    }

    public function documentosPendentesVisualizarArquivos($documentosId)
    {
        $docArquivos = $this->documentoPendenteArquivo->get($documentosId);
        
        $x = 0;
        $arquivos = [];
        $imagensExt = ["png", "jpg", "jpeg"];

        foreach ($docArquivos as $arquivo)
        {
            # Separa a extensão do arquivo
            $ext = explode(".", $arquivo->file);

            # Se não for imagem
            if (in_array($ext[1], $imagensExt))
                $arquivo->imagem = base_url("uploads/docs_pendentes/".$arquivo->file);
            else
                $arquivo->imagem = base_url("media/img/documento.png");

            # Caminho do arquivo no servidor
            $arquivo->arquivoCompleto = base_url("uploads/docs_pendentes/".$arquivo->file);

            # Popula array
            $arquivos[$x] = $arquivo;
            $x++;
        }
        
        $funcionarioNome = isset($arquivos[0]->funcionario) 
            ? " - " . ucwords(strtolower($arquivos[0]->funcionario))
            : "";

        $dados["modalTitulo"] = lang("documentos") . $funcionarioNome;
        $dados["arquivos"] = $arquivos;
		$this->load->view("gente_gestao/administracao_pessoal/documentos", $dados);
    }

    private function formataDocumentosParaBD($documentos)
    {
        $dados["residencia"] = "nao";
        $dados["cpf"] = "nao";
        $dados["rg"] = "nao";
        $dados["banco"] = "nao";
        
        foreach ($documentos as $documento)
        {
            if ($documento == "residencia")
                $dados["residencia"] = "sim";
            if ($documento == "cpf")
                $dados["cpf"] = "sim";
            if ($documento == "rg")
                $dados["rg"] = "sim";
            if ($documento == "banco")
                $dados["banco"] = "sim";
        }

        return $dados;
    }
}