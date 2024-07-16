<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("BASE_API", get_instance()->config->item('base_url_relatorios'));
define("TOKEN", 'd0763b6e9ada39138f3a23be3e799c00');

// ------------------------------------------------------------------------

/**
 * API Helper
 * 
 * @author		Felipe Libório
 * 
 *  Para usar tokens fixos (eg. Token do Shownet) basta passar o token na 
 *  chamada dos métodos. A API interna pode fazer consultas sem token, 
 *  para isso o valor do token pode ser passado como -1
 */

// ------------------------------------------------------------------------
class API_Helper
{
    public static function login($usuario, $hash) {
        $resposta = API_Helper::post('login', [ 'user' => $usuario, 'password' => $hash ]);
        if (strpos($resposta, 'false')) return $resposta;
        return $resposta->token;
    }

    public static function get($url, $token_alternativo = NULL) {
        try {
            $headers = [ "Content-Type: application/json" ];

            if ($token_alternativo) $headers[] = "x-access-token: ".$token_alternativo;
            else $headers[] = "x-access-token: ".TOKEN; 

            return API_Helper::get_request(BASE_API.$url, $headers);
            
        } catch (Exception $e) {
            return json_encode(array(
                'status' => -1000,
                'message' => 'falha ao consultar API',
                'err' => $e->getMessage(),
            ));
        }
    }

    public static function post($url, $body = [], $token_alternativo = NULL) {
        try {
            $headers = [ "Content-Type: application/json" ];

            if ($token_alternativo) $headers[] = "x-access-token: ".$token_alternativo;
            else $headers[] = "x-access-token: ".TOKEN;
            
            return API_Helper::post_request(BASE_API.$url, $body, $headers);
        } catch (Exception $e) {
            return json_encode(array(
                'status' => -1000,
                'message' => 'falha ao consultar API',
                'err' => $e->getMessage(),
            ));
        }
    }

    private static function post_request($url, $body, $headers)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_URL => $url
        ]);

        $resposta = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) throw new Exception('Erro: '.curl_error($curl));
        if ($statusCode == 404) throw new Exception('Erro: '.curl_error($curl));
        curl_close($curl);

        return $resposta;
    }

    private static function get_request($url, $headers)
    {
        try {
            $url = str_replace(' ', '%20', $url);
        
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            ]);
            
            $resposta = curl_exec($curl);
            curl_close($curl);

            return $resposta;
            
        }catch (Exception $e) { 
            return $e->getMessage();              
        }       
    }

    private static function get_url_APIShow($url){
        $CI =& get_instance();
        $request = $CI->config->item('url_api_shownet_rest').$url;
        return $request;
    }

    private static function get_token_APIShow(){
        @session_start();
        
        $con = new CI_Controller();
        if (!isset($_SESSION['tokenLogistica']) && !isset($_SESSION['validadeLogistica'])) {
        
            $con->load->model('usuario');
        
            $user = $con->auth->get_login_dados('email');
            $senha = '';
            foreach ($con->usuario->get("login ='$user'") as $key => $value) {
                if ($key == 'senha'):
                    $senha = $value;
                endif;
            }
            //aquisição do token necessário para requisitar dados na api
            $token = getTokenLogistica($user, $senha);
            //salvando token na sessão
            $_SESSION['tokenLogistica'] = $token;
            $_SESSION['validadeLogistica'] = date("d/m/y H:i:s",strtotime(" + 30 minutes"));
            return $token;
        } else {
            if($_SESSION['validadeLogistica'] > date('d/m/y H:i:s')){
                $token = $_SESSION['tokenLogistica'];
                return $token;
            } else {
                unset($_SESSION['tokenLogistica']);
                unset($_SESSION['validadeLogistica']);
                return API_Helper::get_token_APIShow();
            }
        }
    }
    
    public static function getAPIShow($url) {
        try {
            $url = API_Helper::get_url_APIShow($url);
            $curl = curl_init();
            $token = API_Helper::get_token_APIShow();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '. $token
                ),
            ));
            if (curl_error($curl))  throw new Exception(curl_error($curl));
            $resultado = json_decode(curl_exec($curl), true);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            return array(
                'status' => $statusCode,
                'data' => $resultado
            );
        } catch (Exception $e) {
            return array(
                'status' => -1000,
                'message' => 'falha ao consultar API',
                'err' => $e->getMessage(),
            );
        }
    }
}
