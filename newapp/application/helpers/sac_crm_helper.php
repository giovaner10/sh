<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SacCrmHelper {

    protected $base_url;
    protected $timeout;
    protected $auth_name;
    protected $auth_pass;
    public function __construct() {
        # Cria instância do CI
        $CI =& get_instance();
        $this->base_url = $CI->config->item('base_url_crm');    
        $this->auth_name = $CI->config->item('username_crm');
        $this->auth_pass = $CI->config->item('password_crm');
        $this->crmintegration = $CI->config->item("base_url_api_crmintegration")."/crmintegration/api/";
        $this->timeout = 60;
    }
    
    /**
     * Retorna as anotações da ocorrência informada
     */
    function get_anotacoes($id_ocorrencia){
        $url = 'annotations';
        $api_request_parameters = array('$filter'=>'_objectid_value eq '.$id_ocorrencia, '$expand' => 'owninguser($select=yomifullname)');
        $response = $this->get($url, $api_request_parameters);
        
        return $response;
    }

    /**
     * Função que sinaliza pagamento ao CRM para liberação financeira
     * @param String $document
     * @param Boolean $bloqueioTotal
     */
    function putAvisoDePagamento($body) {
        $ch = curl_init($this->crmintegration . '/customers/blockUnblockCustomer');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($body));

        // remove o certificado ssl (apenas para desenvolvimento)
        if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }

        $response = json_decode(curl_exec($ch));
        $err = curl_error($ch);

        curl_close($curl);
        
        return $response;
    }

    /**
     * Função que recebe os valores antigos, com para com os valores novos e retorna apenas os valores que foram modificados para
     * ser atualizado
     * @param Array $valores_antigos
     * @param Array $valores_novos
     */
    public function filtrarValoresAlterados($valores_antigos, $valores_novos){
        
        $alterados = array();
        if(isset($valores_novos)){
            foreach ($valores_novos as $key => $novo) {
                if(isset($novo) && $novo != ""){
                    if(isset($valores_antigos) && isset($valores_antigos[$key])){
                        if($novo != $valores_antigos[$key]){
                            $alterados[$key] = $novo;
                        }
                    }else{
                        $alterados[$key] = $novo;
                    }
                }
            }
        }

        return $alterados;
    }

    /**
     * Função que realiza requisição get para api
     * @param String $url - nome da entidade
     * @param Array $api_request_parameters - array com parâmetros da requisição
     * 
     * Exemplo: 
     * array(
     *  '$filter' => "zatix_cnpj eq '{$document}'",
     *  '$expand' => "primarycontactid",
     * )
     * 
     * @return Object - Resposta da requisição
     * 
     */
    public function get($entity, $api_request_parameters = array()) {

        try {
            $url = $this->base_url . $entity . "?" . http_build_query($api_request_parameters);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
                'Accept: application/json',
                'OData-MaxVersion: 4.0',
                'OData-Version: 4.0',
                'Prefer: odata.include-annotations="*"'
            ));

            // remove o certificado ssl (apenas para desenvolvimento)
            if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
            curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->auth_name . ':' . $this->auth_pass);

            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            return (object) array("code" => $status_code, "data" => json_decode($response), "error" => $error);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Função para buscar dados da API do CRM.
     * 
     * * Simplificação da função get
     * 
     * @param string $entidade - nome da entidade/tabela para pesquisa de dados
     * @param string $parametros - parametros de busca codificados em URL
     * 
     * @return mixed Dados retornados da API do CRM
     */
    public function buscar($entidade, $parametros) {
        
        // Padrão da URL para chamada à API do CRM
        $url = "{$this->base_url}/$entidade?$parametros";

        // Opções do curl
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPAUTH       => CURLAUTH_NTLM,
            CURLOPT_USERPWD        => "{$this->auth_name}:{$this->auth_pass}",
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'OData-MaxVersion: 4.0',
                'OData-Version: 4.0',
                'Prefer: odata.include-annotations="*"'
            ]
        ]);

        // remove o certificado ssl (apenas para desenvolvimento)
        if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        // $resultado da chamada à API do CRM
        $resultado = json_decode(curl_exec($ch), true);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_error($ch)) {
            // Lança uma exceção caso haja algum erro
            throw new Exception(curl_error($ch));
        }
        $resultado['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $resultado['code'] = $statusCode;
        return $resultado;
    }
        
    /**
     * post
     *
     * @param  String $url
     * @param  Array $dataPost
     * @return Object
     */
    public function post($url, $dataPost) {
    
        try {
            $url = $this->base_url.$url;
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
                "Content-Type: application/json",
                'OData-Version: 4.0',
                'Accept: application/json',
                'OData-MaxVersion: 4.0',
                'Prefer: return=representation'
            ));

            // remove o certificado ssl (apenas para desenvolvimento)
            if(defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            }
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
            curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
            curl_setopt($ch, CURLOPT_POST, 1 );
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost)); 

            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            return (object) array("code" => $status_code, "data" => json_decode($response), "error" => $error);
    
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getRecursoNA($idAtividade){
        $url = $this->base_url . 'activityparties?$select=_partyid_value&$filter=_activityid_value eq ' . $idAtividade . ' and participationtypemask eq 10';

        $url = str_replace(' ', '%20', $url);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
            "Content-Type: application/json",
            'OData-Version: 4.0',
            'Accept: application/json',
            'OData-MaxVersion: 4.0',
            'Prefer: return=representation',
            'If-Match: *'
        ));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // remove o certificado ssl (apenas para desenvolvimento)
        if(defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
        // Set parametros

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if($status_code === 200)
            return (object) array("code" => $status_code, "data" => $response, "error" => null);
        else
            return (object) array("code" => $status_code, "data" => null, "error" => $error);
    }

    /**
     * Realiza requisição put para o id a dunção informada
     * @param String $function - entidade. Ex: (serviceAppointment/AlteraPrestador)
     * @param array $body - Parametros que serão atualizados
     */
    public function put($function, $body){
        $url = $this->crmintegration.$function;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
            "Content-Type: application/json",
            'OData-Version: 4.0',
            'Accept: application/json',
            'OData-MaxVersion: 4.0',
            'Prefer: return=representation',
            'If-Match: *'
        ));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        // remove o certificado ssl (apenas para desenvolvimento)
        if(defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
        // Set parametros
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if($status_code === 200)
            return $status_code;
        else
            return (object) array("code" => $status_code, "data" => null, "error" => $error);
    }

    /**
     * Realiza requisição patch para o id da entidade informada
     * @param String $entity - entidade. Ex: (accounts)
     * @param String $id - id da entidade. Ex: 00000000-0000-0000-0000-000000000001
     * @param String $api_request_parameters - Parametros que serão atualizados
     */
    public function patch($entity, $id, $api_request_parameters){
        $url = $this->base_url.$entity.'('.$id.')';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
            "Content-Type: application/json",
            'OData-Version: 4.0',
            'Accept: application/json',
            'OData-MaxVersion: 4.0',
            'Prefer: return=representation',
            'If-Match: *'
        ));
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        // remove o certificado ssl (apenas para desenvolvimento)
        if(defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
        // Set parametros
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_request_parameters));

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        return (object) array("code" => $status_code, "data" => json_decode($response), "error" => $error);

    }

    /**
     * Realiza requisição delete para o id da entidade informada
     * @param String $entity - entidade. Ex: (accounts)
     * @param String $id - id da entidade. Ex: 00000000-0000-0000-0000-000000000001
     */
    public function delete($entity, $id){
        $url = $this->base_url.$entity.'('.$id.')';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
            "Content-Type: application/json",
            'OData-Version: 4.0',
            'Accept: application/json',
            'OData-MaxVersion: 4.0',
            'Prefer: return=representation',
            'If-Match: *'
        ));
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        // remove o certificado ssl (apenas para desenvolvimento)
        if(defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
        

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        return (object) array("code" => $status_code, "data" => json_decode($response), "error" => $error);
    }
    
}
