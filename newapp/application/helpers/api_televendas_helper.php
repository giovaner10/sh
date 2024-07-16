<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

define("BASE_API", get_instance()->config->item('url_api_televendas'));

/**
 * API Televendas Helper
 * @author Renato Silva
 */

class API_Televendas_Helper {
	public static function login($usuario, $hash) {
		return API_Televendas_Helper::post('/auth/login', ['login' => $usuario, 'password' => $hash]);
	}

	public static function get($uri, $tentaNovamente = true) {
		$TOKEN = get_instance()->auth->get_login_dados('tokenApiTelevendas');

		try {
			$headers = [
				"Content-Type: application/json",
				"Authorization: Bearer " . $TOKEN
			];
			
			$url = BASE_API . $uri;
			$url = str_replace(' ', '%20', $url);

			$curl = curl_init();

			curl_setopt_array($curl, [
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_CONNECTTIMEOUT => 10
			]);

			$resposta = curl_exec($curl);
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			if (curl_errno($curl) || $statusCode == 401) {
				// Renova o token e tenta novamente
				if ($tentaNovamente) {
					curl_close($curl);
					API_Televendas_Helper::atualizarToken();
					API_Televendas_Helper::get($uri, false);
				}
			}

			curl_close($curl);
			return $resposta;
		} 
		catch (Exception $e) {
			return json_encode(
				array(
					'statusCode' => $e->getCode(),
					'message' => 'falha ao consultar API',
					'error' => $e->getMessage(),
				)
			);
		}
	}

	// public static function post($uri, $body = [], $tentaNovamente = true) {
	// 	try {
	// 		$TOKEN = get_instance()->auth->get_login_dados('tokenApiTelevendas');

	// 		$headers = [
	// 			"Content-Type: application/json",
	// 			"Authorization: Bearer " . $TOKEN
	// 		];

	// 		$url = BASE_API . $uri;
	// 		$url = str_replace(' ', '%20', $url);

	// 		$curl = curl_init();

	// 		curl_setopt_array($curl, [
	// 			CURLOPT_SSL_VERIFYHOST => 0,
	// 			CURLOPT_SSL_VERIFYPEER => 0,
	// 			CURLOPT_HTTPHEADER => $headers,
	// 			CURLOPT_RETURNTRANSFER => 1,
	// 			CURLOPT_POST => 1,
	// 			CURLOPT_POSTFIELDS => json_encode($body),
	// 			CURLOPT_URL => $url,
	// 			CURLOPT_TIMEOUT => 60,
	// 			CURLOPT_CONNECTTIMEOUT => 10
	// 		]);

	// 		$resposta = curl_exec($curl);
	// 		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	// 		if (curl_errno($curl) || $statusCode == 404) {
	// 			throw new Exception('Erro: ' . curl_error($curl));
	// 		}

	// 		if (curl_errno($curl) || $statusCode == 401) {
	// 			// Renova o token e tenta novamente
	// 			if ($tentaNovamente) {
	// 				curl_close($curl);
	// 				API_Televendas_Helper::atualizarToken();
	// 				API_Televendas_Helper::post($uri, $body, false);
	// 			}
	// 		}

	// 		curl_close($curl);
	// 		return $resposta;
	// 	} 
	// 	catch (Exception $e) {
	// 		return json_encode(
	// 			array(
	// 				'status' => -1,
	// 				'message' => 'falha ao consultar API',
	// 				'err' => $e->getMessage(),
	// 			)
	// 		);
	// 	}
	// }

	public static function post($uri, $body = [], $tentaNovamente = true) {
		$resposta = null;
		try {
			$TOKEN = get_instance()->auth->get_login_dados('tokenApiTelevendas');
	
			$headers = [
				"Content-Type: application/json",
				"Authorization: Bearer " . $TOKEN
			];
	
			$url = BASE_API . $uri;
			$url = str_replace(' ', '%20', $url);
	
			$curl = curl_init();
	
			curl_setopt_array($curl, [
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => json_encode($body),
				CURLOPT_URL => $url,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_CONNECTTIMEOUT => 10
			]);
	
			$resposta = curl_exec($curl);
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
			if (curl_errno($curl) || $statusCode == 404) {
				throw new Exception('Erro: ' . curl_error($curl));
			}
	
			if (curl_errno($curl) || $statusCode == 401) {
				if ($tentaNovamente) {
					curl_close($curl);
					$curl = null;  
					API_Televendas_Helper::atualizarToken();
					return API_Televendas_Helper::post($uri, $body, false);
				}
			}
		} catch (Exception $e) {
			$resposta = json_encode(
				array(
					'status' => -1,
					'message' => 'falha ao consultar API',
					'err' => $e->getMessage(),
				)
			);
		} finally {
			if ($curl !== null) {
				curl_close($curl);
			}
		}
		return $resposta;
	}
	

	public static function atualizarToken($area = 'admin') {
		$sessao = get_instance()->session->userdata('log_' . $area);
		$usuario = get_instance()->usuario->get(['login' => $sessao['email']]);

		$uri = '/auth/login';
		$body = ['login' => $usuario->login, 'password' => $usuario->senha];

		try {
			$headers = [
				"Content-Type: application/json"
			];

			$url = BASE_API . $uri;
			$url = str_replace(' ', '%20', $url);

			$curl = curl_init();

			curl_setopt_array($curl, [
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => json_encode($body),
				CURLOPT_URL => $url,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_CONNECTTIMEOUT => 10
			]);

			$resposta = curl_exec($curl);
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if (curl_errno($curl) || $statusCode == 404) {
				throw new Exception('Erro: ' . curl_error($curl));
			}

			curl_close($curl);
			$resposta = json_decode($resposta);
			
			if (!empty($resposta->token)) {
				$sessao['tokenApiTelevendas'] = $resposta->token;
				$sessao['expireTokenApiTelevendas'] = time() + 1000 * 60 * 60 * 2; // 2 horas
			}
			else {
				$sessao['tokenApiTelevendas'] = '';
				$sessao['expireTokenApiTelevendas'] = 0;
			}

			get_instance()->session->set_userdata('log_' . $area, $sessao);
		} 
		catch (Exception $e) {
			return json_encode(
				array(
					'status' => -1,
					'message' => 'falha ao consultar API',
					'err' => $e->getMessage(),
				)
			);
		}
	}

}
