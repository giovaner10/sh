<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoramento_iscas extends CI_Controller
{
    public function __construct(){
    parent::__construct();
    $this->auth->is_logged('admin');
    $this->load->model('auth');
    $this->load->model('monitoramento_iscas_model');
    $this->load->model('iscas/iscas');
    $this->load->model('mapa_calor');
    }
    
    public function index(){
      $data['titulo'] = 'Monitoramento Iscas';
      $data['iscas'] = $this->iscas->getIscas(['id_cliente !='=>0]);
      $this->mapa_calor->registrar_acessos_url(site_url('/monitoramento_iscas'));
      $this->load->view('fix/header_NS', $data);
      $this->load->view('monitoramento/iscas');
      $this->load->view('fix/footer_NS');
    }

    protected function get_empresa_user_logado(){
      $this->load->model('auth');
      $this->load->model('usuario');
      $user = $this->auth->get_login_dados('email');
      $empresa = '';
      foreach ($this->usuario->get("login ='$user'") as $key => $value) {
          if ($key == 'empresa'):
              $empresa = $value;
          endif;
      }

      return $empresa;
    }

    public function ajax_list_iscas()
    {
      $visibilidade = $this->get_empresa_user_logado() != 'CEABS SERVICOS S/A' ? true : false;

      $length = $this->input->post("length");
      $start = $this->input->post("start");
      
      $query = $this->monitoramento_iscas_model->get_datatable();
      
      $iscas = $query;
      if(count($query) > $length) {
        $start = count($query) < $start ? 0 : $start;
        $iscas = array_slice($query, $start, $length);
      }
      $data = array();
      
		  foreach ($iscas as $isca) {        
        $row = array();
        $row['id'] = $isca->id;
        $row['serial'] = $isca->serial;
        $row['rotulo'] = isset($isca->descricao) ? $isca->descricao : '';
        $row['modelo'] = isset($isca->modelo) ? $isca->modelo : '';
        $row['fabricante'] = isset($isca->marca) ? $isca->marca : '';
        $row['cliente'] = isset($isca->nome) ? $isca->nome : 'Estoque';
        $row['placa'] = isset($isca->placa) ? $isca->placa : '' ;
        $row['dataGPS'] = isset($isca->data) ? $isca->data : '';
        $row['dataSis'] = isset($isca->datasys) ? $isca->datasys : '';
        $row['lat'] = isset($isca->y) ? $isca->y : '';
        $row['lng'] = isset($isca->x) ? $isca->x : '';
        $row['mapa'] = (isset($isca->y) && isset($isca->x)) ? ('https://www.google.com/maps/?q='.$isca->x.','.$isca->y) : '';
        $row['origem'] = isset($isca->GPS) && $isca->GPS == "1" ? 'GPS' :  'LBS';
        $row['modo'] = isset($isca->status) ? $isca->status : '';
        $row['voltagem'] = isset($isca->VOLTAGE) ? $isca->VOLTAGE : '';
        $row['endereco'] = isset($isca->ENDERECO) ? $isca->ENDERECO : ''; // $row[] = (isset($isca->y) && isset($isca->x)) ? $this->getAddressFromLatLng($isca->x, $isca->y) : '';
        $row['velocidade'] = isset($isca->vel) ? $isca->vel : '';
        $row['porcentagem'] = isset($isca->IN6) ? $isca->IN6 : '';
        $row['gprs'] = isset($isca->GPRS) ? ($isca->GPRS == 1 ? 'ONLINE' : 'OFFLINE') : '';

        $data[] = $row;
      }
      $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->monitoramento_iscas_model->records_total(),
        "recordsFiltered" => count($query),
        "data" => $data,
        "ceabs" => $visibilidade
      );
  
      echo json_encode($json);

    }
    public function ajax_list_dispositivos()
    {
      $length = $this->input->post("length");
      $start = $this->input->post("start");
      
      $query = $this->monitoramento_iscas_model->get_datatable_dispositivos($this->auth->get_login('admin', 'user'));
      
      $data = [];
		  foreach ($query as $isca) {
        $row = array();
        $row[] = $isca->id;
        $row[] = $isca->serial;
        $row[] = $isca->descricao;
        $row[] = isset($isca->marca) ? $isca->marca : '';
        $row[] = (isset($isca->nome) ? $isca->nome : 'Estoque');
        $row[] = isset($isca->placa) ? $isca->placa : '' ;
        $row[] = isset($isca->data) ? $isca->data : '';
        $row[] = isset($isca->datasys) ? $isca->datasys : '';
        $row[] = isset($isca->y) ? $isca->y : '';
        $row[] = isset($isca->x) ? $isca->x : '';
        $row[] = (isset($isca->y) && isset($isca->x)) ? ('https://www.google.com/maps/?q='.$isca->x.','.$isca->y) : '';
        $row[] = isset($isca->GPS) && $isca->GPS == "1" ? 'GPS' :  'LBS';
        $row[] = isset($isca->status) ? $isca->status : '';
        $row[] = isset($isca->modelo) ? $isca->modelo : '';
        $row[] = isset($isca->ENDERECO) ? $isca->ENDERECO : ''; // (isset($isca->y) && isset($isca->x)) ? $this->getAddressFromLatLng($isca->x, $isca->y) : '';
        $row[] = isset($isca->VOLTAGE) ? $isca->VOLTAGE : '';
        
        $data[] = $row;
      }

      // Ordenação dos resultados por coluna e direção
      $order_column = NULL;
      $order_dir = NULL;
      $order = $this->input->post("order");
      if (isset($order)) {
        $order_column = $order[0]["column"];
        $order_dir = $order[0]["dir"];
        $this->array_sort_by_column($data, intval($order_column, 10), $order_dir == 'asc' ? SORT_ASC : SORT_DESC);
      }

      $data = ($length != -1) ? array_slice($data, $start, $length) : $data; // Paginação dos resultados

      $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->monitoramento_iscas_model->records_total(),
        "recordsFiltered" => count($query),
        "data" => $data,
      );
  
      echo json_encode($json);

    }
    public function ajax_list_comandos()
    {
      $filtro = $this->definirFiltroComando($this->input->post("filters"));
      $length = $this->input->post("length");
      $start = $this->input->post("start");
      // pr($length);
      // pr($start);die();
      $iscas = $this->monitoramento_iscas_model->iscas_ativas_clientes(); //retorna as iscas que estão vinculadas a um cliente
      $aux = []; // Array auxiliar que guarda informações das iscas que serão utilizadas posteriormente
      foreach ($iscas as $key => $isca) {
        $aux[$isca['serial']] = [
          'id'=>$isca['id'],
          'marca'=>$isca['marca'],
          'nome'=>$isca['nome'],
          'placa'=>$isca['placa'],
        ];
      }
      
      $seriais = array_column($iscas,'serial'); //extrai os seriais das iscas
      
      $query = $this->monitoramento_iscas_model->get_comandos_monitoramento($seriais, $filtro); //comandos enviados para as iscas ativas dos clientes
      // $comandos = $this->monitoramento_iscas_model->get_comandos_monitoramento($seriais); //comandos enviados para as iscas ativas dos clientes
      
      $data = [];
      foreach ($query as $key => $comando) {
        $data[] = array(
          $comando['cmd_eqp'],
          $aux[trim($comando['cmd_eqp'])]['marca'],
          $aux[trim($comando['cmd_eqp'])]['nome'],
          $aux[trim($comando['cmd_eqp'])]['placa'],
          $comando['descricao_comando'],
          $comando['cmd_cadastro'],
          $comando['cmd_envio'],
          $comando['cmd_confirmacao'],
        );
      }

      // Ordenação dos resultados por coluna e direção
      $order_column = NULL;
      $order_dir = NULL;
      $order = $this->input->post("order");
      if (isset($order)) {
        $order_column = $order[0]["column"];
        $order_dir = $order[0]["dir"];
        $this->array_sort_by_column($data, intval($order_column, 10), $order_dir == 'asc' ? SORT_ASC : SORT_DESC);
      }

      $data = ($length != -1) ? array_slice($data, $start, $length) : $data; // Paginação dos resultados

      $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => count($query),
        "recordsFiltered" => count($query),        
        "data" => $data,
      );
  
      echo json_encode($json);

    }

    // Definir qual comando o filtro deve buscar
    private function definirFiltroComando($filtro)
    {
      if($filtro == 'CONFIG_CONEXAO'){
        return 'Configurar Conexão';
      }
      elseif($filtro == 'PARAM_ENVIO') {
        return 'Parâmetros de Envio';
      }
      elseif($filtro == 'START_REDE_COLAB'){
        return 'Iniciar Emergência';
      }
      elseif($filtro == 'STOP_REDE_COLAB'){
        return 'Parar Emergência';
      }
      elseif($filtro == 'SOLICITAR_ICCID'){
        return 'Solicitar ICCID';
      }
      elseif($filtro == 'SOLICITAR_CONFIG'){
        return 'Solicitar Configuração';
      }
      elseif($filtro == 'SOLICITAR_POSICAO'){
        return 'Solicitar Posição';
      }
      elseif($filtro == 'SOLICITAR_VERCAO_FIRMWARE'){
        return 'Solicitar Versão Firmware';
      }
      return null;
    }

    // Função que ordena array por coluna e direção
    private function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
      $sort_col = array();
      foreach ($arr as $key=> $row) {
          $sort_col[$key] = $row[$col];
      }
      array_multisort($sort_col, $dir, $arr);
    }

    public function getAddressFromLatLng($latitude, $longitude) 
    {
      $key = "AIzaSyBYK_0JnaXcWej_b62el2v38Xb4sL1ctB4";
      // Get cURL resource
      $curl = curl_init();
      // Set some options - we are passing in a useragent too here
      curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?&key='.$key.'&latlng='.$latitude.','.$longitude.'&language=pt-BR',
          CURLOPT_USERAGENT => 'Codular Sample cURL Request'
      ));
      // Send the request & save response to $resp
      $resp = curl_exec($curl);
      // Close request to clear up some resources
      curl_close($curl);

      return json_decode($resp)->results[0]->formatted_address;
    }

    public function comandos_iscas()
    {
        $data['titulo'] = lang("envio_comandos_iscas_lote");
        $this->mapa_calor->registrar_acessos_url(site_url('/monitoramento_iscas/comandos_iscas'));
        $data['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->load->view('new_views/fix/header', $data);
        $this->load->view('monitoramento/envio_comando_lote');
        $this->load->view('fix/footer_NS');
    }

    public function downloadModeloItens() {
      $caminho_arquivo = base_url('arq/iscas/comandos/planilha-dispositivos.xlsx');

      $response = [
          'status' => 200,
          'mensagem' => $caminho_arquivo
      ];

      echo json_encode($response);
  }

    public function comandos_iscas_old()
    {
        $data['titulo'] = 'Envio de Comandos em Massa';
        $this->mapa_calor->registrar_acessos_url(site_url('/monitoramento_iscas/comandos_iscas'));
        $this->load->view('new_views/fix/header', $data);
        $this->load->view('monitoramento/envio_comando_massa');
        $this->load->view('fix/footer_NS');
    }

    public function lista_clientes_iscascontrato()
    {
        $result = $this->iscas->load_clientes_hasIscas();

        $lista = array();

        if (is_array($result)) {
          foreach ($result as $client) {
            $lista[] = array(
              'id' => $client['id'],
              'text' => $client['nome'] . ' - ' . ($client['cnpj'] ? mask(apenasNumeros($client['cnpj']), '##.###.###/####-##') : ($client['cpf'] ? mask(apenasNumeros($client['cpf']), '###.###.###-##') : ''))
            );
          }
        }
        
        if($lista)
            echo json_encode(array('data'=> $lista));
        else
            echo json_encode(array('msg', 'Não existem clientes habilitados para iscas'));

    }


    public function lista_iscas_byCliente()
    {
        if($this->input->post())
        {
            $id_cliente = $this->input->post('id');
            $where['id_cliente'] = $id_cliente;
            $result = $this->iscas->getIscas($where);

            $lista = array();

            if (is_array($result)) {
              foreach ($result as $element) {
                $lista[] = array(
                  'id' => $element['id'],
                  'text' => $element['serial']
                );
              }
            }

            if($lista && count($lista) > 0)
                echo json_encode(array('status' => true, 'data'=> $lista));
            else
                echo json_encode(array('status' => false, 'msg' => 'Este cliente não possui iscas habilitadas.'));
        }
    }

    public function ajaxGetDispositivoBySerial(){
      
      $serial = $this->input->post('searchTerm');
      $result = $this->monitoramento_iscas_model->get_dispositivo_by_serial($serial);
      
      echo json_encode($result);
    }

    public function ajax_inserir_dispositivo(){
      $id_user = $this->auth->get_login('admin', 'user');
      $dados = $this->input->post();
      $tipo = $dados['tipo'];
      
      $resultado = [
        'status' => false,
        'msg' => 'Nenhuma isca adicionada',
        "iscas_inseridas" => [],
				"iscas_nao_inseridas" => [],
      ];

      $input = $dados['input'];
      $id_user = $this->auth->get_login('admin', 'user');

      if($tipo != 'nome') {
        $insert = explode(";",$input);  
        $resultado = $this->monitoramento_iscas_model->inserir_dispositivos($insert, $tipo, $id_user);
      } else {
        $resultado = $this->monitoramento_iscas_model->inserir_dispositivos_cliente($id_user, $input);
      }

      echo json_encode($resultado);
    }
    
    public function ajax_remover_dispositivo(){
      $id_isca = intval($this->input->post('id_isca'));
      $id_user = $this->auth->get_login('admin', 'user');
      
      $resultado = $this->monitoramento_iscas_model->remover_dispositivos($id_isca,$id_user);
      echo json_encode($resultado);

    }

    public function ajax_limpar_grid_dispositivo(){
      $id_user = $this->auth->get_login('admin', 'user');
      
      $resultado = $this->monitoramento_iscas_model->limpar_grid_dispositivos($id_user);
      echo json_encode($resultado);
    }
}
