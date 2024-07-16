<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Parlacom extends CI_Model
{
    private $url = 'http://www.parlacom.net/cgi-bin/parla';

    public function __construct()
    {
        parent::__construct();
    }

    public function bloqueio($data, $session=false)
    {
    	$response = $this->call('get', array(
			'function' => 'edittrafficpin',
			'output_type' => 'xml',
			'sessionid' => $data['session'],
			'login' => $data['login'],
			'company' => 'show',
			'PIN' => $data['id'],
			'ICCID' => $data['ccid'],
			'IMSI' => '',
			'CallerID' => $data['pin'],
			'pincarrier' => $data['operadora'],
			'traffic' => $data['status']
		));

		$data = $this->parseObject($response);

		if ( (int) $data->response->errorcode === 0) {
			return $data->response->ip;
		}

		return false;
    }

    public function cadastrarUsuario($data, $session)
	{
		$response = $this->call('post', array(
            'function' => 'adduser',
            'login' => $data['login'],
            'password' => $data['password'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'salesrep' => $data['salesrep'],
            'email' => $data['email'],
            'userid' => $data['userid'],
            'address1' => $data['address1'],
            'owner' => $data['owner'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zip' => $data['zip'],
            'phone' => $data['phone'],
            'sessionid' => $session,
            'output_type' => 'xml',
            'company' => 'show',
            'admin' => 'admin',
            'showmenu' => '2097151',
            'showoptions' => '950263',
            'cominstfee' => '0',
            'commrc' => '0',
            'comrateinstfee' => '0',
            'comratemrc' => '0',
            'address2' => '',
            'country' => 'USA',
            'agentlogin' => 'consultores',
            'mobile' => '0000000000',
            'invoicedate' => '25',
            'language' => 'pt',
            'record' => 'no',
            'usebank' => 'yes',
            'addedit' => 'no',
            'runinvoices' => 'no',
            'gtalk' => '',
            'logourl' => '',
            'smscel' => '',
            'boletourl' => '',
            'pushurl' => '',
            'changepassword' => 'no',
            'breakinv' => 'no',
            'due' => '10',
            'css' => '',
            'images' => '',
            'website' => '',
            'industry' => '',
            'skype' => '',
            'field1' => '',
            'field2' => '',
            'field3' => ''
		));

        return $response;
		return $this->parseObject($response);
	}

	public function servicos($login, $operadora = '2', $session)
	{
		$response = $this->call('get', array(
			'function' => 'getalluserservices',
			'company' => 'show',
			'outputtype' => 'xml',
			'ini' => '0',
			'total' => '100000',
			'qual' => '3',
			'servicetype' => '20',
			'pincarrier' => $operadora,
			'login' => $login,
			'sessionid' => $session
		));

		return $this->prepareServicos($response, $login);
	}

	public function consultores($login, $session)
	{
		$response = $this->call('get', array(
			'function' => 'getallusers',
			'company' => 'show',
			'outputtype' => 'xml',
			'login' => $login,
			'sessionid' => $session
		));

		return $this->prepareConsultores($response);
	}

	public function consultor($login, $session)
	{
		$response = $this->call('get', array(
			'function' => 'getallusers',
			'company' => 'show',
			'outputtype' => 'xml',
			'login' => $login,
			'sessionid' => $session
		));

		return $this->prepareConsultor($response);
	}

    public function login($login, $password)
    {
        $response = $this->call('get', array(
            'function' => 'getallusers',
            'company' => 'show',
            'outputtype' => 'xml',
            'admin' => $login,
            'adminpwd' => $password,
        ));

        return $this->prepareUser($response);
    }

    public function prepareUser($response)
    {
    	$data = $this->parseObject($response);

    	if ( ! (int) $data->response->errorcode) {

	    	$userData = explode('|', $data->response->user);

	    	$userSession = (string) $data->response->sessionid;

	    	return $this->arrayToObject([
				'dono' => $userData[0],
				'login' => $userData[1],
				'senha' => $userData[2],
				'email' => $userData[3],
				'data_ativacao' => $userData[4],
				'vencimento' => $userData[5],
				'tipo_pagamento' => $userData[6],
				'descricao' => $userData[7],
				'lingua' => $userData[8],
				'rode_faturamento' => $userData[9],
				'session' => $userSession
	    	]);

    	}

    	return null;
    }

    public function prepareServicos($response)
	{
		$data = $this->parseObject($response);


		if ( ! (int) $data->response->errorcode) {

			$services = [];

			foreach ($data->response->service as $service) {

				$serviceData = explode('|', $service);

				// 23888029000157|20|83991010248|0.00|45.90|2017-03-15 18:44:16|||0|2||51200.00|51,200.00|0.00000|0|1|0.00||2017-03-15 18:44:16|0|5583991010248|89551181639005545001|10.211.17.79||

				$services[] = $this->arrayToObject([
					'login' => $serviceData[0],
					'tipo' => $serviceData[1],
					'id' => $serviceData[2],
					'instalacao' => $serviceData[3],
					'mensalidade' => $serviceData[4],
					'data_ativacao' => $serviceData[5],
					'servico_associado' => $serviceData[6],
					'servico_associado2' => $serviceData[7],
					'status' => $serviceData[8],
					'operadora' => $serviceData[9],
					'10' => $serviceData[10],
					'mb' => $serviceData[11],
					'saldo' => $serviceData[12],
					'13' => $serviceData[13],
					'trafego' => $serviceData[14],
					'15' => $serviceData[15],
					'consumo' => $serviceData[16],
					'17' => $serviceData[17],
					'18' => $serviceData[18],
					'19' => $serviceData[19],
					'pin' => $serviceData[20],
					'ccid' => $serviceData[21],
					'ip' => $serviceData[22],
					'23' => $serviceData[23]
				]);

			}

			return $this->arrayToObject($services);
		}

		return null;
	}

	public function prepareConsultores($response)
	{
		$data = $this->parseObject($response);

		if ( ! (int) $data->response->errorcode) {

		$users = [];

		foreach ($data->response->user as $user) {

			$userData = explode('|', $user);

			$users[] = $this->arrayToObject([
				'dono' => $userData[0],
				'login' => $userData[1],
				'senha' => $userData[2],
				'email' => $userData[3],
				'data_ativacao' => $userData[4],
				'vencimento' => $userData[5],
				'tipo_pagamento' => $userData[6],
				'descricao' => $userData[7],
				'lingua' => $userData[8],
				'rode_faturamento' => $userData[9]
			]);

		}

			return $this->arrayToObject($users);
		}

		return null;
	}

	public function prepareConsultor($response)
	{
		$data = $this->parseObject($response);

		if ( ! (int) $data->response->errorcode) {

			$userData = explode('|', $data->response->user);

			$user = $this->arrayToObject([
				'dono' => $userData[0],
				'login' => $userData[1],
				'senha' => $userData[2],
				'email' => $userData[3],
				'data_ativacao' => $userData[4],
				'vencimento' => $userData[5],
				'tipo_pagamento' => $userData[6],
				'descricao' => $userData[7],
				'lingua' => $userData[8],
				'rode_faturamento' => $userData[9]
			]);

			return $this->arrayToObject($user);
		}

		return null;
	}

	public function parseObject($response)
    {
        $clear = str_replace('&', 'E', $response);

        return simplexml_load_string($clear);
    }

    public function arrayToObject($array)
    {
    	return json_decode(json_encode($array));
    }

    public function call($method, $data = false)
    {
        $postdata = http_build_query($data);

        if ($method == 'post') {

            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );

            $context  = stream_context_create($opts);

            $result = file_get_contents($this->url, false, $context);

        } else {

            $result = file_get_contents($this->url . '?' . $postdata);

        }


        // switch ($method) {
        //     case 'post':
        //         curl_setopt($curl, CURLOPT_POST, 1);
        //         if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //         break;
        //
        //     case 'put':
        //         curl_setopt($curl, CURLOPT_PUT, 1);
        //         if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //         break;
        //
        //     default:
        //         $url = $url . '?' . http_build_query($data);
        //         break;
        // }
        //
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "username:password");
		// curl_setopt($curl, CURLOPT_URL, $url);
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //
        // $result = curl_exec($curl);
        //
        // curl_close($curl);

        return $result;
    }
}
