<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Infobip extends CI_Model {
    
    function __construct()
    {
		parent::__construct();
        $this->load->model('infobip_agente_status', 'infobipAgenteStatus');
        $this->load->model('infobip_atendimento_nao_atribuido_status', 'infobipAtendimentoNaoAtribuidoStatus');
        $this->load->model('infobip_canal_tipo', 'infobipCanalTipo');

        $this->load->helper("infobip_api");
        $this->load->helper("util");
        $this->InfobipAPI = new InfobipAPI_Helper();
	}

    public function getConversas($parametros = [])
    {
        $retorno = $this->InfobipAPI->getConversas($parametros);

        if ($retorno["status"] == 0)
            throw new Exception($retorno["mensagem"]);

        return $retorno["dados"];
    }

    public function getTags($parametros = [])
    {
        $retorno = $this->InfobipAPI->getTags($parametros);

        if ($retorno["status"] == 0)
            throw new Exception($retorno["mensagem"]);

        return $retorno["dados"];
    }

    public function getFilas($parametros = [])
    {
        $retorno = $this->InfobipAPI->getFilas($parametros);

        if ($retorno["status"] == 0)
            throw new Exception($retorno["mensagem"]);

        return $retorno["dados"];
    }

    public function getMensagens($conversaId, $parametros = [])
    {
        $retorno = $this->InfobipAPI->getMensagens($conversaId, $parametros);

        if ($retorno["status"] == 0)
            throw new Exception($retorno["mensagem"]);

        return $retorno["dados"];
    }

    # Portal = api do portal (retorna informações adicionais sobre o agente)
    public function getAgentes($parametros = [], $portal = false)
    {
        $retorno = $this->InfobipAPI->getAgentes($parametros, $portal);

        if ($retorno["status"] == 0)
            throw new Exception($retorno["mensagem"]);

        return $retorno["dados"];
    }

    public function getAgentesAtribuidosAFila($filaId, $parametros = [], $agentesStatus = [], $ignorarAgentesInativos = true)
    {
        $retorno = $this->InfobipAPI->getAgentesAtribuidosAFila($filaId, $parametros);

        if ($retorno["status"] == 0)
            throw new Exception($retorno["mensagem"]);

        # Filtra por agentes ativos
        if ($ignorarAgentesInativos)
        {
            $retorno["dados"] = array_filter(
                $retorno["dados"], function ($agente)
                {
                    if ($agente->enabled) # Se ATIVO
                        return $agente;
                }
            );
        }

        # Filtra por status dos agentes
        if (count($agentesStatus) > 0)
        {
            $retorno["dados"] = array_filter(
                $retorno["dados"], function ($agente) use ($agentesStatus)
                {
                    if (in_array($agente->availability, $agentesStatus))
                        return $agente;
                }
            );
        }

        return $retorno["dados"];
    }

    public function getAtendimentosNaoAtribuidos($parametros = [])
    {
        # Status de busca
        $conversasStatus = ["OPEN", "WAITING"];

        # Array conversas
        $conversas = [];

        # Busca conversas por status
        foreach ($conversasStatus as $status)
        {
            $parametros["status"] = $status;
            $retorno = $this->InfobipAPI->getConversas($parametros);

            if ($retorno["status"] == 0)
                throw new Exception($retorno["mensagem"]);
                
            $conversas = array_merge($conversas, $retorno["dados"]);
        }

        $conversas = array_values( # Reordena os indices numéricos
            array_filter( # Percorre as conversas
                $conversas, function($conversa)
                {
                    # Somente as conversas não atribuídas
                    if ($conversa->agentId == null)
                    {
                        return $conversa;
                    }
                }
            )
        );

        # Ordena dos atendimentos de maior tempo de espera para os de menor tempo de espera
        function compararData($conversa1, $conversa2)
        {
            $tempo1 = strtotime($conversa1->started);
            $tempo2 = strtotime($conversa2->started);
            return $tempo2 - $tempo1;
        }
        usort($conversas, "compararData");

        # Popula conversas
        foreach ($conversas as $conversa)
        {
            # Busca a ultima mensagem do atendimento
            $mensagem = $this->infobip->getMensagens(
                $conversa->id,
                ["orderBy" => "id:desc", "limit" => 1]
            )[0];
            
            # Canal de atendimento
            $conversa->channel = $mensagem->channel;
            
            # Preenche cliente no atendimento
            if (property_exists($mensagem->singleSendMessage, "contact"))
                $conversa->client = $mensagem->singleSendMessage->contact->name;
            else
                $conversa->client = $mensagem->from;


            # Farol de status - Tempo de Atendimento
            $iniciadoSegundos = tempoParaMinuto($conversa->started);

            $atendimentosNaoAtribuidosStatus = $this->infobipAtendimentoNaoAtribuidoStatus->get();

            $conversa->farolStatusClasse = "";

            foreach ($atendimentosNaoAtribuidosStatus as $status)
            {
                if (
                    $iniciadoSegundos >= $status->tempo_inicial_minutos &&
                    $iniciadoSegundos <= $status->tempo_final_minutos
                    )
                    {
                        $conversa->farolStatusClasse = $status->icone;
                        break;
                    }
            }
        }

        return $conversas;
    } 

    public function getAgentesAtribuidosPorFilas($parametros = [])
    {
        # Busca dados das filas
        $filas = $this->infobip->getFilas($parametros);

        $filasFormatadas = [];
        $filasAtendimentosAgentes = [];
        foreach($filas as $fila)
        {
            # Transforma indice do array para id da fila
            $filasFormatadas[$fila->id] = $fila;

            # Preenche o icone de acordo com o tipo de canal da fila
            $filaTipoCanal = explode(" - ", $fila->name);
            $filaTipoCanal = !empty($filaTipoCanal[1]) ? $filaTipoCanal[1] : "";

            # Busca o tipo de canal de atendimento
            $canalTipo = $this->infobipCanalTipo->get(["nome" => $filaTipoCanal]);
            
            # Se não encontrar busco o tipo de canal default (app)
            if (empty($canalTipo[0]))
                $canalTipo = $this->infobipCanalTipo->get(["id" => 14]);
                #Se ainda tiver vazio
                if (empty($canalTipo[0]))
                    $canalTipo = $this->infobipCanalTipo->get(["icone" => 'apps']);
                    if (empty($canalTipo[0]))
                        throw new Exception("Não foi possível definir o tipo de canal! Entre em contato com o suporte!");

            $canalTipo = $canalTipo[0];

            # Atribuo icone do canal
            $filasFormatadas[$fila->id]->icone = $canalTipo->icone;

            # Busca agentes atribuídos à fila
            $filasAtendimentosAgentes[$fila->id] = $this->infobip->getAgentesAtribuidosAFila(
                $fila->id,
                ["orderBy" => "displayName:asc"], # parametros
                ["ACTIVE", "BUSY", "AWAY"] # status dos agentes
            );
        }

        return [
            "filasAtendimentosAgentes" => $filasAtendimentosAgentes,
            "filasFormatadas" => $filasFormatadas
        ];
    }

    public function tratarDisponibilidadeAgente($filasAtendimentosAgentes, $filasFormatadas)
    {
        # Busca agentes 
        $agentes = $this->infobip->getAgentes([], true);

        # Formata lista
        $agentesFormatados = [];
        foreach ($agentes as $agente)
            $agentesFormatados[$agente->id] = $agente;


        # Busca status de agentes
        $agentesStatus = $this->infobipAgenteStatus->get();

        # Popula array com os nomes desses status
        $agentesStatusNomes = array_map(
            function ($status)
            {
                return $status->nome;
            },
            $agentesStatus
        );
        
        # Trata a disponibilidade do agente
        foreach ($filasAtendimentosAgentes as $filaId => $agentes)
        {
            foreach ($agentes as $agente)
            {
                # Init variáveis obrigatorias
                $agente->iconeDisponibilidade = "";
                $agente->textoVermelho = false;
                $agente->estouroPausa = false;

                # Atribui a propriedade effectiveStatusName do agente
                    #(não contém na chamada de agentes atribuídos a fila, somente na chamada de agentes)
                $agente->effectiveStatusName = $agentesFormatados[$agente->id]->effectiveStatusName;

                # Retorna o indice do status do agente (tabela infobip_agentes_status)
                $statusAgenteIndice = array_search(
                    $agente->effectiveStatusName, $agentesStatusNomes
                );
                
                # Status não identificado, retorna status e tempo em execução
                if (!$statusAgenteIndice)
                {
                    $agente->disponibilidade = 
                        $agente->effectiveStatusName . " | " .
                        $agente->updated;
            
                    continue;
                }

                # Atribui o icone de disponibilidade
                $agente->iconeDisponibilidade = $agentesStatus[$statusAgenteIndice]->icone_disponibilidade;

                # Verifica se o agente está em pausa
                if ($agentesStatus[$statusAgenteIndice]->em_pausa == "sim")
                {
                    # Identifica estouro de pausa
                    $tempoPausa = $agentesStatus[$statusAgenteIndice]->tempo_pausa;

                    if (strtotime($agente->updated) > strtotime($tempoPausa))
                        $agente->estouroPausa = true;
                }

                # Se o status do agente não possui conversas
                if ($agentesStatus[$statusAgenteIndice]->em_conversa != "sim")
                {
                    # retorna status e tempo em execução
                    $agente->disponibilidade = 
                        $agentesStatus[$statusAgenteIndice]->nome_formatado . " | " .
                        $agente->updated;
            
                    continue;      
                }
                
                # Busca a última conversa relacionada ao agente e á fila
                $agenteConversa = $this->infobip->getConversas([
                    "agentId" => $agente->id,
                    "queueIds" => [$filaId],
                    "status" => "OPEN",
                    "orderBy" => "id:desc",
                    "limit" => 1
                ]);

                # Busca e retorna a qtd de todas as conversas relacionadas ao agente independente da fila
                $agenteTodasConversasQtd = count(
                    $this->infobip->getConversas([
                        "agentId" => $agente->id,
                        "status" => "OPEN"
                    ])
                );

                # Se não achar a conversa, retorna status e tempo em execução
                if (count($agenteConversa) == 0)
                {
                    $agente->disponibilidade = 
                        $agentesStatus[$statusAgenteIndice]->nome_formatado
                        . " (" . $agenteTodasConversasQtd . ") | " .
                        $agente->updated;
            
                    continue;
                }

                $agenteConversa = $agenteConversa[0];

                # Busca a tag da conversa
                $conversaTag = $this->infobip->getTags([
                    "conversationId" => $agenteConversa->id,
                    "orderBy" => "createdAt:desc",
                    "limit" => 1
                ]);
                
                if (count($conversaTag) == 0)
                {
                    # Se não achar a tag, retorna status e tempo em execução
                    $agente->disponibilidade = 
                        $agentesStatus[$statusAgenteIndice]->nome_formatado
                        . " (" . $agenteTodasConversasQtd . ") | " .
                        $agente->updated;

                    continue;
                }

                $conversaTag = $conversaTag[0];

                # Tag - Tempo na conversa
                $agente->disponibilidade = 
                    $conversaTag->name
                    . " (" . $agenteTodasConversasQtd . ") | " .
                    $agenteConversa->updated;
            

                # Extrai o tipo de canal da fila
                $filaTipoCanal = explode(" - ", $filasFormatadas[$filaId]->name);
                $filaTipoCanal = !empty($filaTipoCanal[1]) ? $filaTipoCanal[1] : "";

                # Busca o tipo de canal de atendimento
                $canalTipo = $this->infobipCanalTipo->get(["nome" => $filaTipoCanal]);
                
                # Se não encontrar busca o tipo (apps)
                if (empty($canalTipo[0]))
                    $canalTipo = $this->infobipCanalTipo->get(["id" => 14]);
                    #Se ainda tiver vazio
                    if (empty($canalTipo[0]))
                        $canalTipo = $this->infobipCanalTipo->get(["icone" => 'apps']);
                        if (empty($canalTipo[0]))
                            throw new Exception("Não foi possível definir o tipo de canal! Entre em contato com o suporte!");

                $canalTipo = $canalTipo[0];

                # Verifica se o atendimento está dentro do tempo médio do tipo de canal
                if (strtotime($agenteConversa->updated) > strtotime($canalTipo->tempo_medio_atendimento))
                {
                    # Texto em vermelho
                    $agente->textoVermelho = true;
                }
            }
        }

        return [
            "filasAtendimentosAgentes" => $filasAtendimentosAgentes,
            "agentesFormatados" => $agentesFormatados
        ];
    }

    public function ordenarAtendimentosAgentes($filasAtendimentosAgentes, $filasOrdem)
    {
        # Dados ordenados
        $filasAtendimentosAgentesEmOrdem = [];

        # Mantém sem ordenação
        if (!$filasOrdem || count($filasOrdem) == 0)
            return $filasAtendimentosAgentes;

        # Ordena o array de resultados
        foreach ($filasOrdem as $fila)
        {
            # Evita erros quando filas do select foram desselecionadas mas existem nos paineis
            if (array_key_exists($fila, $filasAtendimentosAgentes))
            {
                # Atribui a fila ordenada
                $filasAtendimentosAgentesEmOrdem[$fila] = $filasAtendimentosAgentes[$fila];
                # Remove a fila atribuída à ordem
                unset($filasAtendimentosAgentes[$fila]);
            }
        }

        # Adiciona no final do array os registros novos
        $filasAtendimentosAgentesEmOrdem = array_merge($filasAtendimentosAgentesEmOrdem, $filasAtendimentosAgentes);

        return $filasAtendimentosAgentesEmOrdem;
    }

}