<?php if (!defined('BASEPATH'))
  exit(lang('nenhum_acesso_direto_script_permitido'));
class NewPedidos extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->auth->is_logged('admin');
    $this->load->model('auth');
    $this->auth->is_allowed('vis_clientestelevendas');
    $this->load->model('mapa_calor');
    $this->load->library('form_validation');
    $this->url_api_televendas = config_item('url_api_televendas');
    $CI =& get_instance();
    $this->tokenApiTelevendas = null;
  }

  public function index()
  {
    $this->auth->is_logged_api_shownet();
    $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/NewPedidos'));

    $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
    $dados['titulo'] = lang('pedidos');

    $this->load->view('new_views/fix/header', $dados);
    $this->load->view('comercial_televenda/new-pedido/index');
    $this->load->view('fix/footer_NS');
  }

  private function loginTelevendas()
  {
    $apiUrl = $this->url_api_televendas . "/auth/login";

    $senha = $this->auth->get_login_dados('senha');

    $ch = curl_init($apiUrl);

    curl_setopt($ch, CURLOPT_POST, 1);

    $postData = array(
      'login' => $this->auth->get_login_dados('email'),
      'password' => $senha,
    );

    $headers = array(
      'Content-Type: application/json',
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));

    if (curl_errno($ch)) {
      echo 'Erro na requisição cURL: ' . curl_error($ch);
    }

    curl_close($ch);

    return $response->token;
  }

  public function clientesPorVendedor($sellerId)
  {
    $this->tokenApiTelevendas;

    if (!$this->tokenApiTelevendas) {
      $this->tokenApiTelevendas = $this->loginTelevendas();
    }
    
    $apiUrl = $this->url_api_televendas . "/client/clients-by-seller/" . $sellerId;

    $headers = array(
      'Authorization: Bearer ' . $this->tokenApiTelevendas,
      'Content-Type: application/json',
    );
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 401) {
      $this->tokenApiTelevendas = $this->loginTelevendas();

      $headers['Authorization'] = 'Bearer ' . $this->tokenApiTelevendas;
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);
    }

    if (curl_errno($ch)) {
      echo 'Erro na requisição cURL: ' . curl_error($ch);
    }

    curl_close($ch);

    echo $response;
  }

}