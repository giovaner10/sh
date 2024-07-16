<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Linhas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('linha');
        $this->load->model('sender');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
    }

    public function index(){
        $this->listar();
    }

    public function cadastro_linhas(){
        $this->mapa_calor->registrar_acessos_url(site_url('/linhas/cadastro_linhas'));
        $this->listar();
    }

    public function enviar_linhas() {
        $this->load->model('send_filetxt');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->send_filetxt->envia_http($this->input->post());
        }
    }

    function analise_contaVivo() {
        if (isset($_FILES['conta'])) {
            $i = 0;
            $aux = explode('_', $_FILES['conta']['name']);

            if (count($aux) > 5)
                $referencia = substr($aux[5], 2, 2).'/'.substr($aux[5], 4, 4);
            else
                $referencia = substr($aux[4], 2, 2).'/'.substr($aux[4], 4, 4);

            if (!$this->linha->verificaRefContas($referencia)) {
                $file = fopen($_FILES['conta']['tmp_name'], 'r');
                while (($line = fgetcsv($file)) !== false)
                {
                    if ($i == 0) {
                        $i++;
                    } else {
                        $linha = explode(';', $line[0]);
                        if (count($linha) > 0) {
                            $serial = $this->linha->getSerialByCcid(str_replace("'", '', $linha[11]));

                            if ($serial)
                                $id_cliente = $this->linha->getClieBySerial($serial);
                            else
                                $id_cliente = $this->linha->getClieByCcid(str_replace("'", '', $linha[11]));

                            if (strtolower($linha[6]) == 'bloqueio parcial')
                                $status = 'bloqueado';
                            elseif (strtolower($linha[6]) == 'cancelado')
                                $status = 'cancelado';
                            else
                                $status = 'ativo';

                            $dados[] = array(
                                'ccid' => str_replace("'", '', $linha[11]),
                                'ddd' => $linha[1],
                                'linha' => $linha[2],
                                'status_linha' => $status,
                                'status_conta' => strtolower($linha[7]),
                                'data_ativacao' => substr(str_replace("'", '', $linha[3]), 4, 4).'-'.substr(str_replace("'", '', $linha[3]), 2, 2).'-'.substr(str_replace("'", '', $linha[3]), 0, 2),
                                'servico' => str_replace('"', '', str_replace(' QTDE:2', '', $linha[15])),
                                'plano' => str_replace('"', '', str_replace(' QTDE:2', '', $linha[16])),
                                'referencia' => $referencia,
                                'data_insert' => date('Y-m-d H:i:s'),
                                'conta' => str_replace("'", '', $linha[12]),
                                'serial' => $serial ? $serial : NULL,
                                'id_cliente' => $id_cliente ? $id_cliente : NULL
                            );
                        }
                    }
                }
                fclose($file);
                $resultado = $this->linha->gravaDadosConta($dados, $referencia);

                if ($resultado)
                    $this->session->set_flashdata('sucesso', 'Dados importados com sucesso.');
                else
                    $this->session->set_flashdata('sucesso', 'Não foi possível importar o arquivo.');

            } else {
                $this->session->set_flashdata('sucesso', 'Não foi possível importar o arquivo, , pois já existe dados registrados para o mês de referência.');
            }
        }

        redirect(site_url('linhas/detConta'));
    }

    function solicitaCancelamento($ref) {
        $ref = str_replace('-', '/', $ref);
        if ($chips = $this->linha->getLinhasCancelamento($ref)) {
            $email = $this->auth->get_login('admin', 'email');
            $data = array('operadora' => 'VIVO', 'email_user' => $email, 'data_insert' => date('Y-m-d H:i:s'), 'referencia' => "{$ref}");
            if ($id_can = $this->linha->cadCancelamentoChips($data)) {

                $insert = array();
                $lista_email = '';
                foreach ($chips as $chip) {
                    $insert[] = array(
                        'id_cancelamento' => $id_can,
                        'ccid' => $chip->ccid,
                        'linha' => $chip->ddd . $chip->linha
                    );
                    $lista_email .= $chip->ccid . ' - ' . $chip->ddd . $chip->linha . '<br/>';
                }

                if ($insert) {
                    if ($this->linha->cadLinhasCancelamento($insert)) {
                        $this->load->model('sender');
                        $this->sender->enviar_email('show@gmail.com', 'Saulo Mendes', 'saulomendes25@gmail.com', 'TESTE',
                            $lista_email
                            , false, false, false);

                        die(json_encode(array('status' => 'OK', 'mensagem' => 'Cancelamento realizado com sucesso.')));
                    } else {
                        die(json_encode(array('status' => 'ERRO', 'mensagem' => 'Não foi possível solicitar o cancelamento das linhas do mês de referência.')));
                    }
                }
            } else {
                die(json_encode(array('status' => 'ERRO', 'mensagem' => 'Erro ao tentar cadastrar a solicitação de cancelamento.')));
            }
        } else {
            die(json_encode(array('status' => 'ERRO', 'mensagem' => 'Não foi encontrado nenhuma linha pendente de cancelamento no mês de referência.')));
        }
    }

    function lista_solicitacoes() {
        $dados['titulo'] = 'Listagem de solicitações de cancelamento';
        $dados['solicitacoes'] = $this->linha->lista_solicitacoes();
        $this->load->view('fix/header', $dados);
        $this->load->view('linhas/lista_solicitacoes');
        $this->load->view('fix/footer');
    }

    function getSolicitacao() {
        if (isset($_POST)) {
            $detalhes = $this->linha->getSolicitacao($_POST['id']);

            if ($detalhes)
                die(json_encode(array('status' => 'OK', 'detalhes' => $detalhes)));
            die(json_encode(array('status' => 'ERRO', 'detalhes' => array())));
        }

        die(json_encode(array('status' => 'ERRO', 'detalhes' => array())));
    }

    function gerar_relContas($id = false, $tipo = false) {
        $dados['titulo'] = 'Detalhamento de Conta';
        $dados['relatorio'] = $id ? $this->linha->getRelContas(str_replace('-', '/', $id), $tipo) : $this->linha->getRelContas($_POST['ref']);
        $dados['grafico'] = $this->linha->createGraficContas();
        $dados['id_ref'] = isset($_POST['ref']) ? str_replace('/', '-', $_POST['ref']) : $id;
        $dados['block_button'] = $this->linha->getSolicitCanByRef(isset($_POST['ref']) ? $_POST['ref'] : str_replace('-', '/', $id));

        $this->load->view('fix/header', $dados);
        $this->load->view('linhas/view_rel_contas');
        $this->load->view('fix/footer');
    }

    // function detConta_antigo() {
    //     $dados['titulo'] = 'Detalhamento de Conta Operadoras';
    //     $dados['referencias'] = $this->linha->listReferenciasContas();
    //     $this->mapa_calor->registrar_acessos_url(site_url('/linhas/detConta'));
    //     $this->load->view('fix/header', $dados);
    //     $this->load->view('linhas/analise_contaVivo');
    //     $this->load->view('fix/footer');
    // }

    function detConta() {

		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $dados['titulo'] = 'Detalhamento de Conta Operadoras';
        $dados['referencias'] = $this->linha->listReferenciasContas();
        $this->mapa_calor->registrar_acessos_url(site_url('/linhas/detConta'));
		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('linhas/analise_contaVivo');
		$this->load->view('fix/footer_NS');
    }

    public function ajax_cad_linhas() {

        $arquivo = $this->session->flashdata('file_linha');
        if ($arquivo) {

            $dados['msg'] = '';
            $dados['msg_erros'] = $this->session->flashdata('msg_erros');
            $dados['content_linhas'] = $cadastrados = $this->session->flashdata('cadLinhas');
            $dados['content_enviados'] = $enviados = $this->session->flashdata('enviados');
            $dados['content_nenviados'] = $nenviados = $this->session->flashdata('nenviados');

            $dados['titulo'] = 'Cadastro de linhas';
            $this->load->view('servicos/resultaLinhas', $dados);

        } else {
            echo 'Nenhuma linha encontrada nesse arquivo...';
        }
    }

    private function estado($status) {
        switch ($status) {
            case 0:
            $estado="cadastrada";
            break;
            case 1:
            $estado="habilitada";
            break;
            case 2:
            $estado="ativa";
            break;
            case 3:
            $estado="cancelada";
            break;
            case 4:
            $estado="bloqueada";
            break;
            case 5:
            $estado="suspensa";
            break;
            default:
            $estado="erro";
            break;
        }
        return $estado;
    }

    private function formataNum($numero){
        $pais="";
        if (strlen($numero)==13){
            $pais="+".substr($numero, 0, 2)." ";
            $numero=substr($numero, 2);
        }
        return $pais.'('.substr($numero, 0, 2).') '.substr($numero, 2, -4).'-'.substr($numero, -4);
    }

    private function setEmail($pedidos, $pedido) {
        $d = date("H");
        if ($d>4 && $d<12) {
            $sauda = 'Bom dia';
        }elseif ($d>11 && $d<18) {
            $sauda = 'Boa tarde';
        }else{
            $sauda = 'Boa noite';
        }
        $mensagem = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        </head>
        <body style="margin: 0; padding: 5px;">
        SHOW PRESTADORA DE SERVICO DO BRASIL LTDA-ME
        <br>
        <br>
        CNPJ: 09.338.999/0001-58<br>
        Gestor: EDUARDO LEITE CRUZ LACET
        <br>
        Contatos: (83) 98140-2032
        <br>
        <br>
        '.$sauda.',
        <br>
        <br>
        Gostaria de solicitar '.$pedido.' da(s) sequinte(s) linha(s):
        <br>
        <br>'
        .$pedidos.'
        <br>
        Att;
        <br>
        <br>'
        .$this->session->userdata('log_admin')['nome'].' - Show Tecnologia
        </body>
        </html>';
        return $mensagem;
    }

    public function bloquear() {
        if ($this->input->post()) {
            try {
                $from = $this->session->userdata('log_admin')['nome'].': Razão Social: SHOW PRESTADORA DE SERVICO DO BRASIL LTDA-ME';
                $toEmail = $this->input->post('destino');
                $chips = explode(",", $this->input->post('chips'));
                $pedidos="";
                foreach ($chips as $chip) {
                    $unico = $this->linha->pesquisa_linha($pesquisa = array('coluna' => 'id', 'palavra' => $chip));
                    $dados = array('status' => 4);
                    $this->linha->update_linha($dados, $unico[0]->ccid);
                    $pedidos .= '# '.$unico[0]->ccid.' - '.$this->formataNum($unico[0]->numero).'
                    <br>';
                }
                $email = $this->setEmail($pedidos, "o bloqueio");
                $envio = $this->sender->enviar_email('suporte@showtecnologia.com', $from, $toEmail, 'Solicitação de bloqueio de linhas', $email);
                // print($mensagem);die;
                $this->session->set_flashdata('sucesso', 'Pedido enviado com sucesso.');
            }catch (Exception $e){
                $this->session->set_flashdata('erro', $e->getMessage());
            }
        }else{
            $this->session->set_flashdata('erro', 'Atenção - Não foi possível enviar pedido de bloqueio.');
        }
        redirect('linhas/listar');
    }

    public function desbloquear() {
        if ($this->input->post()) {
            try {
                $from = $this->session->userdata('log_admin')['nome'].': Razão Social: SHOW PRESTADORA DE SERVICO DO BRASIL LTDA-ME';
                $toEmail = 'leandrogomes@showtecnologia.com';#$this->input->post('destino');
                $chips = explode(",", $this->input->post('chips'));
                $pedidos="";
                foreach ($chips as $chip) {
                    $unico = $this->linha->pesquisa_linha($pesquisa = array('coluna' => 'id', 'palavra' => $chip));
                    $dados = array('status' => 2);
                    $this->linha->update_linha($dados, $unico[0]->ccid);
                    $pedidos .= '# '.$unico[0]->ccid.' - '.$this->formataNum($unico[0]->numero).'
                    <br>';
                }
                $email = $this->setEmail($pedidos, "o desbloqueio");
                $envio = $this->sender->enviar_email('suporte@showtecnologia.com', $from, $toEmail, 'Solicitação de desbloqueio de linhas', $email);
                // print($mensagem);die;
                $this->session->set_flashdata('sucesso', 'Pedido enviado com sucesso.');
            }catch (Exception $e){
                $this->session->set_flashdata('erro', $e->getMessage());
            }
        }else{
            $this->session->set_flashdata('erro', 'Atenção - Não foi possível enviar pedido de bloqueio.');
        }
        redirect('linhas/listar');
    }

    public function correct() {
        $this->linha->correcao();
    }

    public function listar($page = 0)
    {
        if($pesquisa = $this->input->get()){
            if ($pesquisa['coluna'] == 'ccid' || $pesquisa['coluna'] == 'numero') {
                $pesquisa['coluna'] = 'chip.'.$pesquisa['coluna'];
            } else {
                $pesquisa['coluna'] = 'clie.'.$pesquisa['coluna'];
                $pesquisa['palavra'] = $pesquisa['cliente'];
            }

            $dados['linhas'] = $this->linha->listar_pesquisa_linha($pesquisa);
            if ($dados['linhas']) {
                foreach ($dados['linhas'] as $linha) {
                    $linha->status = $this->estado($linha->status);
                }
            }
        } else {
            $config['base_url'] = site_url('linhas/listar');
            $config['total_rows'] = $this->linha->total_linhas();
            $config['per_page'] = 15;
            $config['next_link'] = 'Próxima';
            $config['prev_link'] = 'Anterior';
            $config['full_tag_open'] = '<div class="pagination alternate"><ul class="pagination">';
            $config['full_tag_close'] = '</ul></div>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li><a style="color: #27a9e3"><b>';
            $config['cur_tag_close'] = '</b></a></li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['first_link'] = 'Primeira';
            $config['last_link'] = 'Última';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';

            $this->pagination->initialize($config);

            $linhas = $this->linha->listar_linhas($page, $config['per_page']);

            $j_linhas = array();
            if (count($linhas) > 0) {
                foreach ($linhas as $linha) {
                    $j_linhas[] = $linha->numero;
                    $linha->status = $this->estado($linha->status);
                }
            }
            $dados['j_linhas'] = json_encode($j_linhas);
            $dados['linhas'] = $linhas;
        }
       
        $dados['titulo'] = 'Show Tecnologia';
        $this->load->view('fix/header4', $dados);
        $this->load->view('linhas/cadastro1');
        $this->load->view('fix/footer4');

    }

    public function cadastrar() {
        $this->load->view('linhas/cadastrar');
    }

    public function adicionar()
    {
        if ($linha = $this->input->post('linha')) {
            if ($this->linha->validar_chip(array('ccid' => $linha['ccid']))) {
                $this->session->set_flashdata('erro', 'Atenção - CCID '.$linha['ccid'].' já cadastrado, informe outro CCID!');
                redirect('linhas/listar');
            }else{
                if ($this->linha->validar_chip(array('numero' => $linha['numero']))) {
                    $this->session->set_flashdata('erro', 'Atenção - Chip de linha '.$linha['numero'].' já cadastrado, informe outro número!');
                    redirect('linhas/listar');
                }else{
                    if ($this->linha->adicionar_linha($linha)) {
                        $this->session->set_flashdata('sucesso', 'Chip de número '.$linha['numero'].' cadastrado com sucesso!');
                        redirect('linhas/listar');
                    }else{
                        $this->session->set_flashdata('erro', 'Atenção - Erro ao cadastrar chip!');
                        redirect('linhas/listar');
                    }
                }
            }
        }else{
            $this->session->set_flashdata('erro', 'Atenção - Erro ao cadastrar chip!');
            redirect('linhas/listar');
        }

    }

    /*
	* Lista linhas para select2
	*/
	function ajaxListSelect() {
		$like = NULL;
		if ($search = $this->input->get('q'))
			$like = array('numero' => $search);

        $where = "numero is not null AND (id_equipamento = '0' OR id_equipamento = '' OR id_equipamento is null)";

		echo json_encode(array('results' => $this->linha->listar($where, 0, 10, 'numero', 'DESC', 'numero as text, id', $like)));
	}

    /*
    * EDITA UM CHIP
    */
    public function AjaxEditChip() {
        if ($data = $this->input->post()['linha']) {

            $dados = array(
                'ccid' => $data['ccid'],
                'numero' => $data['numero'],
                'conta' => $data['conta'],
                'operadora' => $data['operadora'],
                'status' => $data['status']
            );

            $update = $this->linha->update_linha($dados, $data['id']);

            if($update){
                $chip = $this->linha->getLinha_by_Id($data['id']);
                $ccid = $chip->ccid != $dados['ccid'] ? "ALTEROU PARA ".$dados['ccid'] : "NÃO ALTEROU" ;
                $numero = $chip->numero != $dados['numero'] ? "ALTEROU PARA ".$dados['numero'] : "NÃO ALTEROU" ;
                $conta = $chip->conta != $dados['conta'] ? "ALTEROU PARA ".$dados['conta'] : "NÃO ALTEROU" ;
                $operadora = $chip->operadora != $dados['operadora'] ? "ALTEROU PARA".$dados['operadora'] : "NÃO ALTEROU" ;
                $status = $chip->status != $dados['status'] ? "ALTEROU PARA ".$dados['status'] : "NÃO ALTEROU" ;

                $acao = "ccid: ".$ccid. " numero: ". $numero. " conta: ". $conta. " operadora: ".$operadora. " status: ".$status;

                $log = array(
                    'nome'  => $this->auth->get_login('admin', 'nome'),
                    'email' => $this->auth->get_login('admin', 'email'),
                    'acao'  => $acao,
                    'data'  => date("Y-m-d H:i:s")
                );

                $this->linha->log_linha($log);
                echo json_encode(array('status' => true, 'registro' => $dados, 'msn' => lang('chip_atualizado') ));
            }else{
                echo json_encode(array('status' => false, 'msn' => lang('chip_nao_atualizado') ));
            }
        }
    }

    public function editar($id_linha) {

        $dados['titulo'] = 'Show Tecnologia';
        $dados['linha'] = $this->linha->getLinha_by_Id($id_linha);
        $dados['id_linha'] = $id_linha;
        $this->load->view('fix/header4',$dados);
        $this->load->view('linhas/editar_novo');
        $this->load->view('fix/footer4');

    }

    public function linhasCad(){

        if ($this->input->post()) {
             $ano =  $this->input->post('ano');
             $operadora = $this->input->post('operadora');

             if ($this->input->post('empresa') == '0')
                 $empresa = true;
             else
                 $empresa = false;

             $dados['linhas'] = $this->linha->getCad_chip($ano,$operadora,$empresa);

             $array;
             if ($dados['linhas']) {
                foreach ($dados['linhas'] as $key) {

                 switch ($key['status']) {
                    case 0:
                    $estado="<span style='background-color: #4676bf;' class='badge badge-cadastrada'>CADASTRADA</span>";
                    break;
                    case 1:
                    $estado="<span style='background-color: #d88c2f;' class='badge badge-habilitada'>HABILITADA</span>";
                    break;
                    case 2:
                    $estado="<span style='background-color: #18b23a;' class='badge badge ativa'>ATIVA</span>";
                    break;
                    case 3:
                    $estado="<span style='background-color: #e0db4e;' class='badge badge-cancelada'>CANCELADA</span>";
                    break;
                    case 4:
                    $estado="<span style='background-color: #d60c0c;' class='badge badge-bloqueada'>BLOQUEADA</span>";
                    break;
                    case 5:
                    $estado="<span style='background-color: #9313c1;' class='badge badge-suspensa'>SUSPENSA</span>";
                    break;
                    default:
                    $estado="<span style='background-color: #a9a4ad;' class='badge badge-erro'>ERRO</span>";
                    break;
                }

                if ($key['nome_sim']){
                    $cliente =  $key['nome_sim'];
                }
                else{
                    $cliente = $key['nome'];
                }

                if ($key['nome_sim']){
                    $prestadora = "SIMM2M";
                }
                elseif ($key['info'] == 'TRACKER'){
                    $prestadora = "SHOW TECNOLOGIA";
                }
                elseif ($key['info'] == 'NORIO'){
                    $prestadora = "NORIO EPP";
                }
                elseif ($key['info'] == 'EUA'){
                    $prestadora = "SHOW TECHNOLOGY EUA";
                }else{
                    $prestadora = 'NÃO VINCULADO';
                }
                if ($key['ultima_atualizacao_chip']) {
                  $ult = date('d-m-Y H:i:s',strtotime($key['ultima_atualizacao_chip']));
              }else{
                  $ult = '';
              }

              $array[] = array(

                'id'                  =>  $key['id'],
                'ccid'                =>  $key['ccid'],
                'numero'              =>  $key['numero'],
                'operadora'           =>  $key['operadora'],
                'serial'              =>  $key['serial'],
                'ultima_atualizacao'  =>  $ult,
                'cliente'             =>  $cliente,
                'vinc_auto'           =>  $key['ccid_auto'],
                'prestadora'          =>  $prestadora,
                'data_cadastro'       =>  dh_for_humans($key['data_cadastro']),
                'status'              =>  $estado
                );
              }
              echo json_encode($array);

            }else{
               echo json_encode(0);
            }
        }

    }

    public function listarchip(){
        $dados['titulo'] = lang('lista_de_chips');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->mapa_calor->registrar_acessos_url(site_url('/linhas/listarchip'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('linhas/cadastro');
        $this->load->view('fix/footer_NS');

    }


    /*
    * CARREGA VIEW LIST CHIPS
    */
    public function listChips() {
        $dados['titulo'] = lang('chips');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->mapa_calor->registrar_acessos_url(site_url('/linhas/listChips'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('linhas/index');
        $this->load->view('fix/footer_NS');
    }

    /*
    * CARREGA LISTA DE CHIPS
    */
    public function ajaxListarChips() {

        if ( $dados = $this->input->post() ) {
            $draw = $dados['draw'] ? $dados['draw'] : 1;                                # Draw Datatable
            $search = $dados['search']['value'] ? $dados['search']['value'] : false;    # Campo pesquisa
            $start = $dados['start'] ? $dados['start'] : 0;                             # Contador (Página Atual)
            $limit = $dados['length'] ? $dados['length'] : 10;                          # Limite (Atual)

            //PEGA AS LINHAS
            $retorno = $this->linha->listLinhasServeSide(
                'chip.*, equip.serial, equip.ultima_atualizacao_chip, equip.ccid as ccid_auto, clie.nome, clie_sim.nome as nome_sim, clie.informacoes as info',
                $start, $limit, $search, $draw
                );

            if ($retorno['linhas']) {
                $dadosLinhas = array();

                foreach ($retorno['linhas'] as $key => $linha) {
                    $nome = $linha->nome_sim ? $linha->nome_sim: $linha->nome;
                    $prestadora = $linha->nome_sim ? 'SIMM2M': $linha->info == 'TRACKER' ? 'SHOW TECNOLOGIA': $linha->info == 'NORIO' ? 'NORIO EPP': $linha->info == 'EUA' ? 'SHOW TECHNOLOGY EUA': '';

                    $dadosLinhas[] = array(
                        'id' => $linha->id,
                        'ccid' => $linha->ccid,
                        'linha' => $linha->numero,
                        'operadora' => strtoupper($linha->operadora),
                        'conta' => $linha->conta,
                        'serial' => $linha->serial,
                        'ultima_atualizacao' => data_for_humans(explode(' ', $linha->ultima_atualizacao_chip)[0]),
                        'cliente' => $nome,
                        'vinc_auto' => $linha->ccid_auto,
                        'prestadora' => $prestadora,
                        'data_cadastro' => data_for_humans(explode(' ', $linha->data_cadastro)[0]),
                        'status' => $linha->status
                    );
                }
                echo json_encode(array('draw' => intval($retorno['draw']), 'recordsTotal' =>  intval($retorno['recordsTotal']), 'recordsFiltered' =>  intval($retorno['recordsFiltered']), 'data'=> $dadosLinhas) );
             }else{
                 echo json_encode(array('status' => false, 'data' => array(), 'draw' => intval($retorno['draw'])));
             }
         }
    }

    /*
    * CADASTRA CHIP
    */
    public function AjaxAddChip()
    {
        if ($linha = $this->input->post('linha')) {
            $linha['operadora'] = strtoupper($linha['operadora']);
            if ($this->linha->validar_chip(array('ccid' => $linha['ccid']))) {
                echo json_encode(array('status' => false, 'msn' => 'CCID '.$linha['ccid'].' '. lang('chip_ja_cadastrado') ));
            }else{
                if ($this->linha->validar_chip(array('numero' => $linha['numero']))) {
                    echo json_encode(array('status' => false, 'msn' => lang('chip_de_linha').' '.$linha['numero'].' '. lang('numero_ja_cadastrado') ));
                }else{
                    if ($id_insert = $this->linha->adicionar_linha($linha)){
                        $linha['id'] = $id_insert;
                        $linha['data_cadastro'] = date('Y-m-d');
                        echo json_encode(array('status' => true, 'registro' => $linha, 'msn' => lang('chip_de_numero').' '.$linha['numero'].' '.lang('cadastro_sucesso') ));

                    }else{
                        echo json_encode(array('status' => false, 'msn' => lang('erro_cadastro_chip') ));
                    }
                }
            }
        }else{
            echo json_encode(array('status' => false, 'msn' => lang('erro_cadastro_chip') ));
        }
    }

    /*
    * GET CHIP
    */
    public function ajaxGetChip() {

        if ($id_linha = $this->input->post('id_chip')) {
            //PEGA OS DADOS DAQUELE CHIP
            $linha = $this->linha->getLinha_by_Id($id_linha);
            $chip = array();
            if ($linha){
                $chip = array(
                    'id' => $linha->id,
                    'ccid' => $linha->ccid,
                    'numero' => $linha->numero,
                    'conta' => $linha->conta,
                    'operadora' => $linha->operadora,
                    'status' => $linha->status,
                );
                echo json_encode(array('status' => true, 'chip' => $chip));
            }else{
                echo json_encode(array('status' => false, 'msn' => lang('chip_nao_encontrato') ));
            }

        }else {
            echo json_encode(array('status' => false, 'msn' => lang('tente_mais_tarde') ));
        }
    }

    /*
    * CADASTRA CHIPS EM LOTE
    */
    public function cadastrarChipsLote() {

        if ($novosChips = json_decode($this->input->post('linhas'), true)) {
            $operadoras = ['ALGAR', 'CLARO', 'OI', 'TIM', 'VIVO', 'VODAFONE', 'CONNECT 4.0'];
            $linhaInvalida['status'] = false;

            foreach ($novosChips as $key => $linha) {
                //SE A OPERADORA FOR UMA QUE NAO TRABALHAMOS, A EMITE PARA A INSERCAO
                if (!in_array(strtoupper($linha['operadora']), $operadoras) || count($linha) != 6 ){
                    $linhaInvalida['status'] = true;
                    $linhaInvalida['linha'] = $linha;
                    break;
                }else{
                    $novosChips[$key]['operadora'] = strtoupper($linha['operadora']);
                }
            }

            if ($linhaInvalida['status']) {
                if (count($linhaInvalida['linha']) != 6)
                    echo json_encode(array('status' => false, 'msg' => sprintf(lang('linha_campo_ausente'), $linhaInvalida['linha']['numero']) ));
                else
                    echo json_encode(array('status' => false, 'msg' => sprintf(lang('linha_operadora_invalida'), $linhaInvalida['linha']['operadora'], $linhaInvalida['linha']['numero']) ));
            }else {
                //CADASTRA OS CHIPS POR 'INSERT IGNORE BATCH'
                $qtdCadastrados = $this->linha->insertChipBatchString($novosChips, true);
                if ($qtdCadastrados > 0)
                    echo json_encode(array('status' => true, 'msg' => '['.$qtdCadastrados.'/'.count($novosChips).'] '.lang('chips_lote_cadastrado') ));
                elseif($qtdCadastrados == 0)
                    echo json_encode(array('status' => false, 'msg' => lang('chips_ja_existem') ));
                else
                    echo json_encode(array('status' => false, 'msg' => lang('erro_cadastro_chip') ));
            }

        }else {
            echo json_encode(array('status' => false, 'msg' => lang('erro_cadastro_chip') ));
        }
    }

    //LISTA AS LINHA DO CLIENTE
    public function listaLinhasCliente(){
        $id_usuario = $this->input->post('id_usuario');
        $result = array();
		$dados = $this->linha->getLinhasCliente($id_usuario);
		if ($dados){
            foreach ($dados as $key => $dado) {
                $result[] = array(
                    'numero' => $dado->numero,
                    'usuario' => $dado->usuario,
                    'nome_usuario' => $dado->nome_usuario
                );
            }
			echo json_encode(array('status' => 'OK', 'results' => $result));
        }else{
            echo json_encode(array('status' => 'FALSE', 'results' => $result));
        }
	}

    public function adicionarChip(){
        $POSTFIELDS = array(
            'ccid' => $this->input->post('ccid'),
        'numero' => $this->input->post('numero'),
        'conta'=> $this->input->post('conta'),
        'operadora' => $this->input->post('operadora')
        );

        $dados = $this->to_post('chip/cadastrarChips', $POSTFIELDS);
        echo json_encode($dados);
    }

    public function listarChips() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $ccid = $this->input->post('ccid');
        $linha = $this->input->post('linha');
        $operadora = $this->input->post('operadora');

        $startRow++;

        $url = 'chip/listarChips?itemInicio='. $startRow .'&itemFim=' . $endRow;

        if(isset($ccid) && $ccid){
            $url .= '&ccid=' . $ccid;
        }
        if(isset($linha) && $linha){
            $url .= '&linha=' . $linha;
        }
        if(isset($operadora) && $operadora){
            $url .= '&operadora=' . $operadora;
        }

        $dados = $this->to_get($url);
        
        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['chips'],
                "lastRow" => $dados['resultado']['qtdTotalChips']
            ));
        } else if ($dados['status'] == 404) {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }

    public function listarChipPorId(){
        $id = (int) $this->input->post('id');

        $url = 'chip/listarChipPorId?idChip='. $id;

        $dados = $this->to_get($url);

        echo json_encode($dados);
    }

    public function editarChip(){
        $POSTFIELDS = array(
        'idChip' => $this->input->post('id'),
        'ccid' => $this->input->post('ccid'),
        'numero' => $this->input->post('numero'),
        'conta'=> $this->input->post('conta'),
        'operadora' => $this->input->post('operadora'),
        'status' => $this->input->post('status'),
        );

        $dados = $this->to_put('chip/editarChip', $POSTFIELDS);
        echo json_encode($dados);
    }

    public function adicionarChipLote(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $url = "chip/cadastrarChipsLote";

        $dados = $this->to_post($url, $dadosRecebidos);
        
        echo json_encode($dados);
    }

    public function listarChipsRelatorio() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $ano = $this->input->post('ano');
        $vinculada = (int) $this->input->post('empresa');
        $operadora = $this->input->post('operadora');

        $startRow++;

        $url = 'chip/listarChipsRelatorio?itemInicio='. $startRow .'&itemFim=' . $endRow;

        if(isset($ano) && $ano){
            $url .= '&ano=' . $ano;
        }
        if($vinculada === 0 || $vinculada === 1){
            $url .= '&vinculada=' . $vinculada;
        }
        if(isset($operadora) && $operadora){
            $url .= '&operadora=' . $operadora;
        }

        $dados = $this->to_get($url);
        
        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['chips'],
                "lastRow" => $dados['resultado']['qtdTotalChips']
            ));
        } else if ($dados['status'] == 404) {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }

    private function to_put($url, $POSTFIELDS){
        $CI =& get_instance();
    
        $request = $CI->config->item('url_api_shownet_rest').$url;
        
        $token = $CI->config->item('token_api_shownet_rest');
    
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
            CURLOPT_HTTPHEADER => $headers,
        ));
    
        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
        curl_close($curl);
    
        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }
    
    private function to_patch($url, $POSTFIELDS){
        $CI =& get_instance();
    
        $request = $CI->config->item('url_api_shownet_rest').$url;
        
        $token = $CI->config->item('token_api_shownet_rest');
    
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
            CURLOPT_HTTPHEADER => $headers,
        ));
    
        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
        curl_close($curl);
    
        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }
    
    private function to_post($url, $POSTFIELDS){
        $CI =& get_instance();
    
        $request = $CI->config->item('url_api_shownet_rest').$url;
        
        $token = $CI->config->item('token_api_shownet_rest');
    
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
            CURLOPT_HTTPHEADER => $headers,
        ));
    
        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
        curl_close($curl);
    
        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }
    
    private function to_get($url){
        $CI = &get_instance();
    
        $request = $CI->config->item('url_api_shownet_rest').$url;
    
        $token = $CI->config->item('token_api_shownet_rest');
    
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ));
    
        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    
        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

}
