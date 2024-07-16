<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

define("BASE_API", get_instance()->config->item("base_url_api_infobip"));
define("BASE_CONVERSATIONS", "ccaas/1/");
define("KEY", "App 91bf41ac2a1deaa835078d51cc80e107-0f1baaaf-97f2-4636-b5ec-0f02274ecefd");

/**
 * API Helper
 * 
 * @author		Everton Martins
 * 
 * Link da documentação da API: https://www.infobip.com/docs/api
 **/

class InfobipAPI_Helper
{

    public function getConversas($parametros = [])
    {
        try
        {
            $caminho = "conversations";
            $dados = $this->getComPanicacao($caminho, "conversations", $parametros);

            $dataAtual = new DateTime();
            
            # Trata propriedades de data e adiciona a propriedade "started"
            foreach($dados as $conversa)
            {
                $dataCriacao = $this->converteDataHoraParaFusoHorario($conversa->createdAt);
                $dataAlteracao = $this->converteDataHoraParaFusoHorario($conversa->updatedAt);

                # Formata datas
                $conversa->started = $dataCriacao->diff($dataAtual)->format('%H:%I:%S');
                $conversa->updated = $dataAlteracao->diff($dataAtual)->format('%H:%I:%S');
                $conversa->createdAt = $dataCriacao->format('Y-m-d H:i:s');
                $conversa->updatedAt = $dataAlteracao->format('Y-m-d H:i:s');
            }

            return [
                "status" => 1,
                "dados" => $dados
            ];
        }
        catch (Exception $e)
        {
            return [
                "status" => 0,
                "mensagem" => lang("excecao_conversas"),
            ];
        }
    }

    public function getTags($parametros = [])
    {
        try
        {
            $caminho = "tags";
            $dados = $this->getComPanicacao($caminho, "tags", $parametros);

            # Trata propriedades de data
            foreach($dados as $tag)
            {
                $dataCriacao = $this->converteDataHoraParaFusoHorario($tag->createdAt);
                $dataAlteracao = $this->converteDataHoraParaFusoHorario($tag->updatedAt);

                # Formata datas
                $tag->createdAt = $dataCriacao->format('Y-m-d H:i:s');
                $tag->updatedAt = $dataAlteracao->format('Y-m-d H:i:s');
            }

            return [
                "status" => 1,
                "dados" => $dados
            ];
        }
        catch (Exception $e)
        {
            return [
                "status" => 0,
                "mensagem" => lang("excecao_tags"),
            ];
        }
    }
    
    public function getFilas($parametros = [])
    {
        try
        {
            $caminho = "queues";
            $dados = $this->getComPanicacao($caminho, "queues", $parametros);

            return [
                "status" => 1,
                "dados" => $dados
            ];
        }
        catch (Exception $e)
        {
            return [
                "status" => 0,
                "mensagem" => lang("excecao_filas"),
            ];
        }
    }


    public function getMensagens($conversaId, $parametros = [])
    {
        try
        {
            $caminho = "conversations/{$conversaId}/messages";
            $dados = $this->getComPanicacao($caminho, "messages", $parametros);

            return [
                "status" => 1,
                "dados" => $dados
            ];
        }
        catch (Exception $e)
        {
            return [
                "status" => 0,
                "mensagem" => lang("excecao_mensagens"),
            ];
        }
    }

    public function getAgentes($parametros = [], $portal = false)
    {
        try
        {
            if (!$portal)
                $caminho = "agents";
            else
                $caminho = "portal/agents";
                
            $dados = $this->getComPanicacao($caminho, "agents", $parametros);

            $dataAtual = new DateTime();
            
            # Trata propriedades de data e adiciona a propriedade "started"
            foreach($dados as $agent)
            {
                $dataCriacao = $this->converteDataHoraParaFusoHorario($agent->createdAt);
                $dataAlteracao = $this->converteDataHoraParaFusoHorario($agent->updatedAt);

                # Formata datas
                $agent->updated = $dataAlteracao->diff($dataAtual)->format('%H:%I:%S');
                $agent->createdAt = $dataCriacao->format('Y-m-d H:i:s');
                $agent->updatedAt = $dataAlteracao->format('Y-m-d H:i:s');
            }

            return [
                "status" => 1,
                "dados" => $dados
            ];
        }
        catch (Exception $e)
        {
            return [
                "status" => 0,
                "mensagem" => lang("excecao_agentes"),
            ];
        }
    }

    public function getAgentesAtribuidosAFila($filaId, $parametros = [])
    {
        try
     
        {
            $caminho = "queues/{$filaId}/agents";
            $dados = $this->getComPanicacao($caminho, "agents", $parametros);

            $dataAtual = new DateTime();
            
            # Trata propriedades de data e adiciona a propriedade "started"
            foreach($dados as $agent)
            {
                $dataCriacao = $this->converteDataHoraParaFusoHorario($agent->createdAt);
                $dataAlteracao = $this->converteDataHoraParaFusoHorario($agent->updatedAt);

                # Formata datas
                $agent->updated = $dataAlteracao->diff($dataAtual)->format('%H:%I:%S');
                $agent->createdAt = $dataCriacao->format('Y-m-d H:i:s');
                $agent->updatedAt = $dataAlteracao->format('Y-m-d H:i:s');
            }

            return [
                "status" => 1,
                "dados" => $dados
            ];
        }
        catch (Exception $e)
        {
            return [
                "status" => 0,
                "mensagem" => lang("excecao_agentes_atribuidos_filas"),
            ];
        }
    }

    private function getComPanicacao($caminho, $indiceDadosRetorno, $parametros = [])
    {
        $parametros["page"] = 0;
        $parametros["limit"] = 100; # (limite maximo da api)
        $totalItens = 0;
        $primeiroLoop = true;
        $dados = [];

        try {
            # Busca dados paginados
            while (
                $primeiroLoop === true ||
                $parametros["page"] * $parametros["limit"] < $totalItens)
            {
                $retorno = $this->get($caminho, $parametros);

                $dados = array_merge($dados, $retorno->{$indiceDadosRetorno});
                $totalItens = $retorno->pagination->totalItems;
                
                $parametros["page"]++;
                $primeiroLoop = false;
            } 
        } catch (Exception $e) {
            throw new Exception();
        }

        return $dados;
    }

    private function get($caminho, $parametros = [])
    {
        try {
            $data = urldecode(http_build_query($parametros));

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => BASE_API.BASE_CONVERSATIONS.$caminho."?".$data,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 200,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: " . KEY,
                    "Accept: application/json"
                ),
                # Para funcionar sem SSL
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ));

            $resposta = curl_exec($curl);
            $httpCodigo = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            # Tratamento de erro
            if ($httpCodigo != 200)
                throw new Exception();

            return json_decode($resposta);

        } catch (Exception $e) {
            throw new Exception();
        }
        
    }
    
    
    function converteDataHoraParaFusoHorario($data)
    {
        return new DateTime($data, new DateTimeZone(date_default_timezone_get()));
    }
}