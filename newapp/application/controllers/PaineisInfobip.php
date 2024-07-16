<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class PaineisInfobip extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->model('infobip');
        $this->load->model('infobip_agente_status', 'infobipAgenteStatus');
        $this->load->model('infobip_atendimento_nao_atribuido_status', 'infobipAtendimentoNaoAtribuidoStatus');
        $this->load->model('infobip_fila_grupo', 'infobipFilaGrupo');
        $this->load->model('infobip_fila_grupo_x_fila', 'infobipFilaGrupoXFila');
        $this->load->model('infobip_canal_tipo', 'InfobipCanalTipo');
    }

    public function index()
    {
        $dados["titulo"] = lang('painel_infobip');
        $dados["load"] = ["jquery-ui", "bootstrap-multiselect"];

        try
        {
            # Busca dados das filas
            $filas = $this->infobip->getFilas();
            $grupos = $this->infobipFilaGrupo->get();

            $dados["status"] = 1;
            $dados["dados"]["grupos"] = $grupos;
            $dados["dados"]["filas"] = $filas;
        }
        catch (Exception $e)
        {
            $dados["status"] = 0;
            $dados["mensagem"] = $e->getMessage();
        }
        finally
        {
            $this->mapa_calor->registrar_acessos_url(site_url('/PaineisInfobip'));
            $this->load->view("new_views/fix/header", $dados);
            $this->load->view("painel_infobip/index");
            $this->load->view("fix/footer_NS");
        }
    }

    public function getDados()
    {
        try
        {
            $dadosView = [];
            $parametrosAtendimentos = [];

            # Filtro por filas (array)
            if ($this->input->post("filaIds"))
                $parametrosAtendimentos["queueIds"] = $this->input->post("filaIds");

            # Busca fila de atendimentos não atribuídos
            $atendimentosNaoAtribuidos = $this->infobip->getAtendimentosNaoAtribuidos($parametrosAtendimentos);
           

            $parametrosFilas = [];

            # Filtro por filas (array)
            if ($this->input->post("filaIds"))
                $parametrosFilas["queueIds"] = $this->input->post("filaIds");

            # Busca agentes atribuidos por filas
            $agentesAtribuidosPorFilasDados = $this->infobip->getAgentesAtribuidosPorFilas($parametrosFilas);
            
            $filasFormatadas = $agentesAtribuidosPorFilasDados["filasFormatadas"];
            $filasAtendimentosAgentes = $agentesAtribuidosPorFilasDados["filasAtendimentosAgentes"];


            # Trata disponibilidade do agente
            $dispobinibidadeAgenteDados = $this->infobip->tratarDisponibilidadeAgente(
                $filasAtendimentosAgentes,
                $filasFormatadas
            );

            $filasAtendimentosAgentes = $dispobinibidadeAgenteDados["filasAtendimentosAgentes"];
            $agentesFormatados = $dispobinibidadeAgenteDados["agentesFormatados"];


            # Array de ordenação (de acordo com o que o usuário tiver movido no front)
            $filasOrdem = $this->input->post("filasOrdens");
            
            # Ordena filas de acordo com array
            $filasAtendimentosAgentesEmOrdem = $this->infobip->ordenarAtendimentosAgentes(
                $filasAtendimentosAgentes,
                $filasOrdem
            );


            $dadosView = [
                "status" => 1,
                "dados" => [
                    "atendimentosNaoAtribuidos" => $atendimentosNaoAtribuidos,
                    "filas"     => $filasFormatadas, # No front utilizar = $filas[$atendimentosNaoAtribuidos->queueId]
                    "filasAtendimentosAgentes" => $filasAtendimentosAgentesEmOrdem,
                    "agentes" => $agentesFormatados # No front utilizar = $agentes[$agenteDaFila->id]
                ]
            ];
        }
        catch (Exception $e)
        {
            $dadosView = [
                "status"   => 0,
                "mensagem" => $e->getMessage()
            ];
        }
        finally
        {
            $this->load->view("painel_infobip/dados", $dadosView);
        }
    }

    public function getGrupoFilas($grupoId)
    {
        try
        {
            $dados = $this->infobipFilaGrupoXFila->get(
                [ # Where
                    "id_filas_grupos" => $grupoId,
                    "status" => "ativo"
                ],
                "codigo_filas", # Select
                "array"
            );

            # Formata para lista unidimensional
            $dados = array_column($dados, "codigo_filas");

            echo json_encode([
                "status"   => 1,
                "dados" => $dados
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                "status"   => 0,
                "mensagem" => lang("mensagem_erro_carregamento_filas")
            ]);
        }
    }
    
    public function parametrosAdministrar()
    {
        $dados["modalTitulo"] = lang("administrar_parametros");
        
        $this->load->view("painel_infobip/parametros_administrar", $dados);
    }

    public function parametrosAtendimentosNaoAtruibidosStatus()
    {
        $atendimentosNaoAtribuidosStatus = $this->infobipAtendimentoNaoAtribuidoStatus->get();

        $data = [];
        $x = 0;
        foreach ($atendimentosNaoAtribuidosStatus as $status)
        {
            if ($status->id == 3)
                $status->tempo_final_minutos = "*";

            $tempo = "De $status->tempo_inicial_minutos até $status->tempo_final_minutos minutos";
            $data[$x] =
            [
                $status->icone,
                $tempo,
                $status->id # Editar
            ];
            $x++;
        }

        echo json_encode(["data" => $data]);
    }

    public function formularioAtendimentoNaoAtribuidoStatus($id)
    {
        $dados["modalTitulo"] = lang("status").': ';
        
        # Get atendimentoNaoAtribuidoStatus de edição
        $dados["status"] = $this->infobipAtendimentoNaoAtribuidoStatus->getPorId($id);

        $this->load->view("painel_infobip/parametros_atendimento_nao_atribuido_status", $dados);
    }

    public function atendimentoNaoAtribuidoStatusEditar($id)
    {
        try
        {
            # Dados
            $dados = $this->input->post();

            if (!empty($dados["tempo_final_minutos"]))
                $status["tempo_final_minutos"] = $dados["tempo_final_minutos"];

            if (!empty($dados["tempo_inicial_minutos"]))
                $status["tempo_inicial_minutos"] = $dados["tempo_inicial_minutos"];
                
            # Editar status
            $this->infobipAtendimentoNaoAtribuidoStatus->editar(
                $id, # Where
                $status # Dados
            );

            # Para status 2 e 3
            if ($dados["statusId"] != 1)
            {
                $idAnterior = $id -1;
                # Get status anterior
                $statusAnterior = $this->infobipAtendimentoNaoAtribuidoStatus->getPorId($idAnterior);
                
                # O status anterior recebe como tempo final o tempo inicial do status em edição.
                $statusAnteriorEditar["tempo_final_minutos"] = $status["tempo_inicial_minutos"];

                # Caso o tempo inicial seja maior que o final ocorre o reajuste
                if ($statusAnterior->tempo_inicial_minutos >= $status["tempo_inicial_minutos"])
                    $statusAnteriorEditar["tempo_inicial_minutos"] = $status["tempo_inicial_minutos"] -1;

                $this->infobipAtendimentoNaoAtribuidoStatus->editar(
                    $idAnterior, # Where
                    $statusAnteriorEditar # Dados
                );
            }

            # Para status 1 e 2
            if ($dados["statusId"] != 3)
            {
                $idPosterior = $id +1;
                # Get status anterior
                $statusPosterior = $this->infobipAtendimentoNaoAtribuidoStatus->getPorId($idPosterior);

                # O status posterior recebe como tempo inicial o tempo final do status em edição.
                $statusPosteriorEditar["tempo_inicial_minutos"] = $status["tempo_final_minutos"];
    
                # Caso o tempo final seja menor que o inicial ocorre o reajuste
                if ($statusPosterior->tempo_final_minutos <= $status["tempo_final_minutos"])
                    $statusPosteriorEditar["tempo_final_minutos"] = $status["tempo_final_minutos"] +1;

                $this->infobipAtendimentoNaoAtribuidoStatus->editar(
                    $idPosterior, # Where
                    $statusPosteriorEditar # Dados
                );
            }

            # Registros atualizados
            $statusPrimeiro = $this->infobipAtendimentoNaoAtribuidoStatus->getPorId(1);
            $statusTerceiro = $this->infobipAtendimentoNaoAtribuidoStatus->getPorId(3);
            $statusSegundo = $this->infobipAtendimentoNaoAtribuidoStatus->getPorId(2);

            # Para status 1
            if ($dados["statusId"] == 1)
            {
                # Ajuste de tempo inicial do registro 3
                if ($statusTerceiro->tempo_inicial_minutos < $statusSegundo->tempo_final_minutos)
                {
                    $statusTerceiroDados["tempo_inicial_minutos"] = $statusSegundo->tempo_final_minutos;

                    $this->infobipAtendimentoNaoAtribuidoStatus->editar(
                        $statusTerceiro->id, # Where
                        $statusTerceiroDados # Dados
                    );
                }
            }
        
            # Para status 3
            if ($dados["statusId"] == 3)
            {
                # Ajuste de tempo final do registro 1
                if ($statusTerceiro->tempo_final_minutos > $statusSegundo->tempo_inicial_minutos)
                {
                    $statusPrimeiroDados["tempo_final_minutos"] = $statusSegundo->tempo_inicial_minutos;

                    $this->infobipAtendimentoNaoAtribuidoStatus->editar(
                        $statusPrimeiro->id, # Where
                        $statusPrimeiroDados # Dados
                    );
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

    public function parametrosTempoPausas()
    {
        $pausas = $this->infobipAgenteStatus->get(["em_pausa" => "sim"]);

        $data = [];
        $x = 0;
        foreach ($pausas as $pausa)
        {
            $data[$x] =
            [
                $pausa->nome_formatado,
                date("H:i", strtotime($pausa->tempo_pausa)),
                $pausa->id # Editar
            ];
            $x++;
        }

        echo json_encode(["data" => $data]);
    }

    public function formularioTempoPausa($pausaId)
    {
        $dados["modalTitulo"] = lang("pausa");
        $dados["pausa"] = $this->infobipAgenteStatus->getAgenteStatus($pausaId);
        
        $this->load->view("painel_infobip/parametros_tempo_pausa", $dados);
    }

    public function editarTempoPausa($pausaId)
    {
        try
        {
            $tempo = $this->input->post("tempo");

            $this->infobipAgenteStatus->editar(
                $pausaId,
                ["tempo_pausa" => $tempo]
            );

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
    
    public function parametrosFilasGrupos()
    {
        $grupos = $this->infobipFilaGrupo->get();

        $data = [];
        $x = 0;
        foreach ($grupos as $grupo)
        {
            $data[$x] =
            [
                $grupo->id,
                $grupo->nome,
                $grupo->id # Acoes
            ];
            $x++;
        }

        echo json_encode(["data" => $data]);
    }

    public function formularioFilasGrupos($grupoId = null)
    {
        $dados = [];

        # Busca as filas de atendimentos
        $dados["filas"] = $this->infobip->getFilas();

        if ($grupoId)
        {
            $dados["modalTitulo"] = lang("editar_grupo");

            $dados["grupo"] = $this->infobipFilaGrupo->getFilaGrupo($grupoId);

            $grupoFilasIds = $this->infobipFilaGrupoXFila->get(
                [ # Where
                    "id_filas_grupos" => $grupoId,
                    "status" => "ativo"
                ],
                "codigo_filas", # Select
                "array" # Tipo de retorno
            );

            # Formata array para - indice => valor ([[0] => 123, [1] => 345])
            $dados["grupoFilasIds"] = array_column($grupoFilasIds, "codigo_filas");
        }
        else
            $dados["modalTitulo"] = lang("novo_grupo");

        $this->load->view("painel_infobip/parametros_fila_grupo", $dados);
    }

    public function adicionarFilaGrupo()
    {
        try
        {
            $dados = $this->input->post();

            # Adiciona o grupo
            $filaGrupoId = $this->infobipFilaGrupo->adicionar(
                ["nome" => $dados["grupoNome"]]
            );

            # Adiciona as filas do grupo
            $this->infobipFilaGrupoXFila->adicionarPorGrupo($filaGrupoId, $dados["grupoFilasIds"]);

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

    public function editarFilaGrupo($grupoId)
    {
        try
        {
            $dados = $this->input->post();

            # Edita o grupo
            $this->infobipFilaGrupo->editar(
                $grupoId,
                ["nome" => $dados["grupoNome"]]
            );

            # Edita as filas do grupo
            $this->infobipFilaGrupoXFila->editarPorGrupo(
                $grupoId,
                $dados["grupoFilasIds"]
            );

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

    public function excluirFilaGrupo($grupoId)
    {
        try
        {
            # Exclui o grupo
            $this->infobipFilaGrupo->excluir($grupoId);

            # Exclui as filas do grupo
            $this->infobipFilaGrupoXFila->excluirPorGrupo($grupoId);

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
    
    public function parametrosAtendimentosTempoMedio()
    {
        $canaisTipos = $this->InfobipCanalTipo->get();

        $data = [];
        $x = 0;
        foreach ($canaisTipos as $tipo)
        {
            $data[$x] =
            [
                $tipo->nome,
                date("H:i", strtotime($tipo->tempo_medio_atendimento)),
                $tipo->id # Editar
            ];
            $x++;
        }

        echo json_encode(["data" => $data]);
    }

    public function formularioAtendimentosTempoMedio($id)
    {
        $dados["modalTitulo"] = lang("tempo_medio");
        $dados["canalTipo"] = $this->InfobipCanalTipo->getCanalTipo($id);
        
        $this->load->view("painel_infobip/parametros_atendimento_tempo_medio", $dados);
    }

    public function editarAtendimentoTempoMedio($id)
    {
        try
        {
            $tempoMedio = $this->input->post("tempoMedio");

            $this->InfobipCanalTipo->editar(
                $id,
                ["tempo_medio_atendimento" => $tempoMedio]
            );

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

}