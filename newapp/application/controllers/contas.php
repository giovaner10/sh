<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('conta');
        $this->load->library('upload');
        $this->load->model('retorno');
        $this->load->model('fatura');
        $this->load->model('mapa_calor');
        //$this->auth->is_allowed('contas_visualiza');
    }

    public function index_antiga()
    {
        $this->auth->is_allowed('contas_showtecnologia');
        $dados['titulo'] = "Contas";
        $dados['contas'] = $this->conta->get_all();
        $dados['estatisticas'] = $this->conta->get_fluxo();
        $dados['categorias'] = $this->conta->getCategorias();
        $fornecedores = $this->conta->getNameFornecedores();
        $dados['fornecedores'] = json_encode($fornecedores);
        $dados["load"] = ["select2", "mask", "jquery-form"];
        $this->mapa_calor->registrar_acessos_url(site_url('/contas'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('contas/view_NS', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function index_old()
    {
        $this->auth->is_allowed('contas_showtecnologia');
        $dados['titulo'] = "Contas";
        $dados['contas'] = $this->conta->get_all();
        $dados['estatisticas'] = $this->conta->get_fluxo();
        $dados['categorias'] = $this->conta->getCategorias();
        $fornecedores = $this->conta->getNameFornecedores();
        $dados['fornecedores'] = json_encode($fornecedores);
        $dados["load"] = ["select2", "mask", "jquery-form"];

        $this->load->view('fix/header_NS', $dados);
        $this->load->view('contas/view_NS', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function contas_eua_antiga()
    {
        $this->auth->is_allowed('contas_eua');
        $dados['titulo'] = "Contas Show Technology";
        $dados['estatisticas'] = $this->conta->get_fluxo('showtecsystem.fluxo_eua');
        $dados['categorias'] = $this->conta->getCategorias();
        $dados['fornecedores'] = json_encode($this->conta->getNameFornecedores());
        $this->mapa_calor->registrar_acessos_url(site_url('/contas/contas_eua'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('contas/view_eua');
        $this->load->view('fix/footer_NS');
    }

    public function contas_eua()
    {
        $this->auth->is_allowed('contas_eua');
        $dados['titulo'] = "Contas Show Technology";
        $dados['estatisticas'] = $this->conta->get_fluxo('showtecsystem.fluxo_eua');
        $dados['categorias'] = $this->conta->getCategorias();
        $dados['fornecedores'] = json_encode($this->conta->getNameFornecedores());
        $this->mapa_calor->registrar_acessos_url(site_url('/contas/contas_eua'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('contas/view_eua_nova', $dados);
        $this->load->view('fix/footer_NS');
    }


    public function add_categoria()
    {
        $name_cat = $this->input->post('categoria');
        $insert = $this->conta->addCategoria($name_cat);

        echo json_encode($insert);
    }

    public function add_categoria_contas()
    {
        $name_cat = $this->input->post('categoria');
        $insert = $this->conta->addCategoria($name_cat);

        echo json_encode($insert);
    }

    public function add($us = false)
    {
        $user = $this->auth->get_login('admin', 'email');
        $ax = $this->input->post('valor');

        $empresa = $this->input->post('empresa');
        $data['empresa'] =  $empresa ? $empresa : 1;
        $data['data_vencimento'] = $this->input->post('data_vencimento');
        $data['fornecedor'] = $this->input->post('fornecedor');
        $data['descricao'] = $this->input->post('descricao');
        $data['categoria'] = $this->input->post('categoria');
        $data['user_Cad'] = $user;
        $data['status'] = 0;
        $data['dh_lancamento'] = date('Y-m-d H:i:s');

        if ($data['empresa'] == 4) {
            if (strlen($ax) <= 7) {
                $data['valor'] = str_replace(',', '.', $ax);
            } elseif (strlen($ax) > 7) {
                $data['valor'] = str_replace(',', '', $ax);
            }
        } else {
            if (strlen($ax) <= 7) {
                $data['valor'] = str_replace(',', '.', $ax);
            } elseif (strlen($ax) > 7) {
                $data['valor'] = str_replace(',', '.', str_replace('.', '', $ax));
            }
        }

        $seriais = $this->input->post('seriais');
        $data['seriais'] = serialize($seriais);
        $cliente = $this->input->post('cliente');
        $data['cliente'] = serialize($cliente);
        $placa = $this->input->post('placa');
        $data['placa'] = serialize($placa);
        $id_os = $this->input->post('id_os');
        $data['id_os'] = serialize($id_os);
        $valorServ = $this->input->post('valorServ');
        $data['valorServ'] = serialize($valorServ);
        $dataServ = $this->input->post('dataServ');
        $data['dataServ'] = serialize($dataServ);
        $servicoOs = $this->input->post('servicoOs');
        $data['servicoOs'] = serialize($servicoOs);
        $userOs = $this->input->post('userOs');
        $data['userOs'] = serialize($userOs);
        $serial_retirado = $this->input->post('serial_retirado');
        $data['serial_ret'] = serialize($serial_retirado);
        if ($this->input->post('cod_barras') != "") {
            $data['codigo_barra'] =  $this->input->post('cod_barras');
            if ($this->input->post('id_conta') == "-2") {
                $data['id_contas'] =  $this->input->post('id_conta');
            }
        } elseif ($this->input->post('id_conta') != "-1") {
            $data['id_contas'] =  $this->input->post('id_conta');
        }


        if ($id = $this->conta->add($data)) {
            $comment = array(
                'user' => $user,
                'comment' => 'Novo Lançamento',
                'id_account' => $id,
                'notification' => '0',
                'date' => date('Y-m-d H:i:s')
            );
            $this->conta->addMsgAccount($comment);
        }

        echo (json_encode($id));
    }

    public function contas_ajax($empresa = false)
    {
        if (!$empresa) $where = array('empresa' => 1);
        else $where = array('empresa' => $empresa);

        $page = $this->input->get('start') / $this->input->get('per_page');
        $per_page = $this->input->get('per_page');

        $config['per_page'] = 20;
        $data_contas['per_page'] = 99999;

        $draw = 1; # Draw Datatable

        $search = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $config['per_page'] = $per_page;
            $data_contas['per_page'] = $per_page;
        }

        $config['per_page'] = $per_page;
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
        $config['base_url'] = site_url('contas/contas_ajax/');
        $config['reuse_query_string'] = FALSE;

        $data = $dados = array();
        $data_contas = $this->conta->get_all($where, $page, $per_page, $search);
        $total_filter = $this->conta->countAll_filter($where, $search);

        $this->pagination->initialize($config);

        foreach ($data_contas as $key => $v) {
            $inst = array();
            $class = $v->arquivo;
            $cp = $v->tem_comprovante;
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $botao_pagar = "<button class='btn btn-mini btn-design-pg btn-primary' style='height: 36px;width: 46px; margin-bottom: 6px;' onclick='estornar($v->id)' title='Estornar pagamento'>
                <i class='fa fa-rotate-left'></i> </button>";
            } else {
                $botao_pagar = "<button class='btn btn-primary btn-mini $status' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' 
                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
                title='Pagar' data-original-title='Tooltip on top'>
                <i class='fa fa-money'></i></button>";
            }
            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                $none = 'btn';

                if (!isset($inst[$v->id_instalador])) {
                    $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                    $inst[$v->id_instalador] = $instalador[0];
                }
            }

            if (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '1' && $inst[$v->id_instalador]->comp_cpf == '1' && $inst[$v->id_instalador]->comp_rg == '1' && $inst[$v->id_instalador]->comp_conta == '1') {
                $btn_class = 'btn-success';
            } elseif (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '0' && $inst[$v->id_instalador]->comp_cpf == '0' && $inst[$v->id_instalador]->comp_rg == '0' && $inst[$v->id_instalador]->comp_conta == '0') {
                $btn_class = 'btn-default';
            } else {
                $btn_class = 'btn-warning';
            }
            $linkComprov = "<button data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id_instalador) . " data-conta='$v->id_instalador'
                        class='btn btn-default dropdown-toggle $btn_class $none'
                        class='btn btn-default dropdown-toggle $btn_class $none'
                        title='Comprovantes' data-target='#myModal_comprovantes'> 
                        <i class='fa fa-file' style='color: black;'></i>
                        </button>";

            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            if ($class == 1) {

                $classe = $cp ? 'btn-success' : 'btn-warning';
            } else {

                $classe = 'btn-default';
                $icon = 'fa fa-folder-open';
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            if ($empresa && $empresa == 4)
                $valor_formatado = 'US$ ' . number_format($v->valor, 2, '.', ',');
            else
                $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');

            $data['data'][] = array(
                $v->id,
                strtoupper($v->fornecedor),
                $v->descricao,
                $categoria,
                strtoupper($responsavel),
                dh_for_humans($v->dh_lancamento, false, false),
                dh_for_humans($v->data_vencimento, false, false),
                $valor_formatado,
                status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                $pagamento,
                "                    
                <button style='color: whitesmoke; text-shadow: none' class='btn btn-default $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                 onclick='dados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                 data-user='$userOs' data-serial_ret='$serial_retirado'
                title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
                <i class='fa fa-dashboard' style='color: black;'></i></button> 
                
                $linkComprov
                
                $botao_pagar
                
                <button data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-default' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
				data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
				<i class='fa fa-wrench' aria-hidden='true'></i>
				</button>  
				<button data-toggle='modal' href=" . site_url('contas/digitalizar_NS/' . $v->id) . " data-conta='$v->id'
				class='btn dropdown-toggle btn-atualizar-equipamento btn-default'
				title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
                <i class='fa fa-folder-open-o' aria-hidden='true'></i>
				</button>
				<button data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='btn btn-default $status' data-conta='$v->id'  onclick='cancel(this)'>
				<i class='fa fa-times' aria-hidden='true'></i>
				</button>
				",
            );
        }

        $data['draw'] = $draw + 1;
        $data['recordsTotal'] = $this->conta->get_totAll($where);
        $data['recordsFiltered'] = $total_filter;

        echo json_encode($data);
    }

    public function contas_ajax2($empresa = false)
    {
        if (!$empresa) $where = array('empresa' => 1);
        else $where = array('empresa' => $empresa);

        $page = $this->input->get('start') / $this->input->get('per_page');
        $per_page = $this->input->get('per_page');

        $config['per_page'] = 20;
        $data_contas['per_page'] = 99999;

        $draw = 1; # Draw Datatable
        //$start = 0; # Contador (Start request)
        //$limit = 10; # Limite de registros na consulta

        $search = NULL;
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $config['per_page'] = $per_page;
            $data_contas['per_page'] = $per_page;
        }

        $config['per_page'] = $per_page;
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
        $config['base_url'] = site_url('contas/contas_ajax/');
        $config['reuse_query_string'] = FALSE;

        $data = $dados = array();
        $data_contas = $this->conta->get_all($where, $page, $per_page, $search);
        $total_filter = $this->conta->countAll_filter($where, $search);

        $this->pagination->initialize($config);

        foreach ($data_contas as $key => $v) {
            $inst = array();
            $class = $v->arquivo;
            $cp = $v->tem_comprovante;
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $botao_pagar = "<button class='btn btn-mini btn-design-pg btn-primary' style='height: 36px;width: 46px; margin-bottom: 6px;' onclick='estornar($v->id)' title='Estornar pagamento'>
                <i class='fa fa-rotate-left'></i> </button>";
            } else {
                $botao_pagar = "<button class='btn btn-primary btn-mini $status' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' 
                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
                title='Pagar' data-original-title='Tooltip on top'>
                <i class='fa fa-money'></i></button>";
            }
            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                $none = 'btn';

                if (!isset($inst[$v->id_instalador])) {
                    $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                    $inst[$v->id_instalador] = $instalador[0];
                }
            }

            if (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '1' && $inst[$v->id_instalador]->comp_cpf == '1' && $inst[$v->id_instalador]->comp_rg == '1' && $inst[$v->id_instalador]->comp_conta == '1') {
                $btn_class = 'btn-success';
            } elseif (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '0' && $inst[$v->id_instalador]->comp_cpf == '0' && $inst[$v->id_instalador]->comp_rg == '0' && $inst[$v->id_instalador]->comp_conta == '0') {
                $btn_class = 'btn-default';
            } else {
                $btn_class = 'btn-warning';
            }
            $linkComprov = "<button data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id_instalador) . " data-conta='$v->id_instalador'
                        style='height: 36px; width: 46px; margin-bottom: 6px;' class='btn btn-primary btn-mini dropdown-toggle $btn_class $none'
                        title='Comprovantes' data-target='#myModal_comprovantes'> 
                        <i class='fa fa-file'></i>
                        </button>";

            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            if ($class == 1) {

                $classe = $cp ? 'btn-success' : 'btn-warning';
            } else {

                $classe = 'btn-default';
                $icon = 'fa fa-folder-open';
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            if ($empresa && $empresa == 4)
                $valor_formatado = 'US$ ' . number_format($v->valor, 2, '.', ',');
            else
                $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');

            $data['data'][] = array(
                $v->id,
                strtoupper($v->fornecedor),
                $v->descricao,
                $categoria,
                strtoupper($responsavel),
                dh_for_humans($v->dh_lancamento, false, false),
                dh_for_humans($v->data_vencimento, false, false),
                $valor_formatado,
                status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                $pagamento,
                "                    
                <button style='color: whitesmoke; text-shadow: none; height: 36px; width: 46px; margin-bottom: 6px;' class='btn btn-primary $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                 onclick='dados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                 data-user='$userOs' data-serial_ret='$serial_retirado'
                title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
                <i class='fa fa-dashboard'></i></button> 
                
                $linkComprov
                
                $botao_pagar
                
                <button data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-sm btn-primary' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
				data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
				<i class='fa fa-edit'></i>
				</button>  
				<button data-toggle='modal' href=" . site_url('contas/digitalizar/' . $v->id) . " data-conta='$v->id'
				class='btn btn-mini dropdown-toggle btn-atualizar-equipamento <?php echo $classe; ?> btn-primary' style='height: 37px;width: 47px; margin-bottom: 5px;'
				title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
				<i class='fa fa-folder-open'></i>
				</button>
                <button
                data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/deleteConta/' . $v->id) . "
                data-fornecedor='' class='btn btn-sm btn-primary $status' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' onclick='cancel(this)'>
                <i class='fa fa-trash-o fa-lg'></i>
                </button>
				",
            );
        }

        $data['draw'] = $draw + 1;
        $data['recordsTotal'] = $this->conta->get_totAll($where);
        $data['recordsFiltered'] = $total_filter;

        echo json_encode($data);
    }

    public function deleteConta()
    {
        // VerifiCAR se o usuário tem permissão para excluir a conta, se necessário

        $id = $this->input->post('id');
        $resultado = $this->conta->deleteConta($id);

        if ($resultado) {
            echo json_encode($resultado);
        } else {
            return false;
        }
    }

    public function contas_ajax_NS($empresa = false)
    {
        if (!$empresa) $where = array('empresa' => 1, 'status_aprovacao' => 1);
        else $where = array('empresa' => $empresa, 'status_aprovacao' => 1);

        $draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $search = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)
        }

        $data = $dados = array();
        $data_contas = $this->conta->get_all($where, $start, $limit, $search);
        $total_filter = $this->conta->countAll_filter($where, $search);

        foreach ($data_contas as $key => $v) {
            $inst = array();
            $class = $v->arquivo;
            $cp = $v->tem_comprovante;
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $botao_pagar = "<button style='margin-left: 5px;' class='btn btn-primary ' onclick='estornar($v->id)' title='Estornar pagamento'>
                <i class='fa fa-folder-open-o' aria-hidden='true'></i> Estornar </button>";
            } else {
                $botao_pagar = "<button style='margin-left: 5px;' class='btn btn-primary  $status' data-conta='$v->id' 
                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
                title='Pagar' data-original-title='Tooltip on top'>
                <i class='fa fa-folder-open-o' aria-hidden='true'></i> Pagar </button>";
            }
            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                $none = 'btn';

                if (!isset($inst[$v->id_instalador])) {
                    $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                    $inst[$v->id_instalador] = $instalador[0];
                }
            }

            if (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '1' && $inst[$v->id_instalador]->comp_cpf == '1' && $inst[$v->id_instalador]->comp_rg == '1' && $inst[$v->id_instalador]->comp_conta == '1') {
                $btn_class = 'btn-success';
            } elseif (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '0' && $inst[$v->id_instalador]->comp_cpf == '0' && $inst[$v->id_instalador]->comp_rg == '0' && $inst[$v->id_instalador]->comp_conta == '0') {
                $btn_class = 'btn-primary ';
            } else {
                $btn_class = 'btn-warning';
            }
            $linkComprov = "<button style='margin-left: 5px;' data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id_instalador) . " data-conta='$v->id_instalador'
                        class='btn btn-primary dropdown-toggle $btn_class $none'
                        title='Comprovantes' data-target='#myModal_comprovantes'> 
                        <i class='fa fa-folder-open' aria-hidden='true'></i>
                        </button>";

            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            if ($class == 1) {

                $classe = $cp ? 'btn-success' : 'btn-warning';
            } else {

                $classe = 'btn-primary ';
                $icon = 'fa fa-folder-open';
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            if ($empresa && $empresa == 4)
                $valor_formatado = 'US$ ' . number_format($v->valor, 2, '.', ',');
            else
                $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');

            $data['data'][] = array(
                $v->id,
                strtoupper($v->fornecedor),
                $v->descricao,
                $categoria,
                strtoupper($responsavel),
                dh_for_humans($v->dh_lancamento, false, false),
                dh_for_humans($v->data_vencimento, false, false),
                $valor_formatado,
                status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                $pagamento,
                "                
                <div style='display:flex'>    
                <button style='margin-left: 5px; color: whitesmoke; text-shadow: none' class='btn btn-primary  $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                 onclick='exibirDados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                 data-user='$userOs' data-serial_ret='$serial_retirado'
                title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
                <i class='fa fa-dashboard'></i> Dados </button> 
                
                $linkComprov
                
                $botao_pagar
                
                <button style='margin-left: 5px;' data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-primary ' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
				data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
				<i class='fa fa-wrench' aria-hidden='true'></i>
				</button>  
				<button  style='margin-left: 5px;' data-toggle='modal' href=" . site_url('contas/digitalizar_NS/' . $v->id) . " data-conta='$v->id'
				class='btn btn-primary dropdown-toggle btn-atualizar-equipamento $classe'
				title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
                <i class='fa fa-cloud-upload' aria-hidden='true'></i>
				</button>
				<button style='margin-left: 5px;' data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='btn btn-primary $status' data-conta='$v->id'  onclick='cancel(this)'>
				<i class='fa fa-times' aria-hidden='true'></i>
				</button>
				",
            );
        }

        $data['draw'] = $draw + 1;
        $data['recordsTotal'] = $this->conta->get_totAll($where);
        $data['recordsFiltered'] = $total_filter;

        echo json_encode($data);
    }

    public function get()
    {
        exit(json_encode($this->conta->get($this->input->post())));
    }

    public function update($us = false)
    {
        $dados['titulo'] = "Contas";
        if ($this->auth->is_allowed_block('cancelar_conta')) {
            if ($this->input->post('fornecedor') != '') {
                $data['fornecedor'] = $this->input->post('fornecedor');
                $data['descricao'] = $this->input->post('descricao-form');
                $data['categoria'] = $this->input->post('categoria');
                $data['data_vencimento'] = $this->input->post('dt_vencimento');
                if ($us) $data['valor'] = trim(str_replace("R$", "", $this->input->post('valor')));
                else $data['valor'] = trim(str_replace("R$", "", str_replace(",", ".", str_replace(".", "", $this->input->post('valor')))));
                $post = $this->input->post();
                if (isset($post['cod_barras']) && $this->input->post('cod_barras') != "") {
                    $data['codigo_barra'] =  $this->input->post('cod_barras');
                    if ($this->input->post('id_conta') == "-2") {
                        $data['id_contas'] =  $this->input->post('id_conta');
                    } elseif ($this->input->post('id_conta') == "-1") {
                        $data['id_contas'] =  null;
                    }
                } elseif (isset($post['id_conta']) && $this->input->post('id_conta') != "-1") {
                    $data['id_contas'] =  $this->input->post('id_conta');
                }

                # Cria notification e comentário no chat
                $user = $this->auth->get_login('admin', 'email');
                $comment = array(
                    'user' => $user,
                    'comment' => 'O usuário ' . $user . ' alterou dados da conta.',
                    'id_account' => $this->input->post('id'),
                    'notification' => '0',
                    'date' => date('Y-m-d H:i:s')
                );
                $this->conta->addMsgAccount($comment);
            } else {
                if ($this->input->post('valor_pago') && $this->input->post('data_pagamento')) {
                    if ((md5($this->input->post('senha_pagamento')) == $this->fatura->senhaExclusaoFatura()) || (md5($this->input->post('senha_pagamento')) == $this->fatura->senhaExclusaoFatura2())) {
                        $ax = $this->input->post('valor_pago');
                        if (strlen($ax) <= 7)
                            $data['valor_pago'] = str_replace(',', '.', $ax);
                        else if (strlen($ax) > 7)
                            $data['valor_pago'] = str_replace(',', '.', str_replace('.', '', $ax));

                        $data['data_pagamento'] = $this->input->post('data_pagamento');
                        $data['status'] = 1;

                        # Cria notification e comentário no chat
                        $user = $this->auth->get_login('admin', 'email');
                        $comment = array(
                            'user' => $user,
                            'comment' => 'Pagamento realizado com sucesso.',
                            'id_account' => $this->input->post('id'),
                            'notification' => '0',
                            'date' => date('Y-m-d H:i:s')
                        );
                        $this->conta->addMsgAccount($comment);
                    } else {
                        exit(json_encode(4)); # MANDA CODIGO DE ERRO PERMISSÃO PARA O JS
                    }
                } else {
                    # Cria notification e comentário no chat
                    $user = $this->auth->get_login('admin', 'email');
                    $comment = array(
                        'user' => $user,
                        'comment' => 'Conta cancelada com sucesso.',
                        'id_account' => $this->input->post('id'),
                        'notification' => '0',
                        'date' => date('Y-m-d H:i:s')
                    );
                    $this->conta->addMsgAccount($comment);

                    $data['status'] = 3;
                }
            }
        } else {
            exit(json_encode(3)); # MANDA CODIGO DE ERRO PERMISSÃO PARA O JS
        }

        # Verifica se conta estava com aprovação e pendente para pagamento, e foi alterado o valor
        if (isset($data['status']) && $data['status'] != 3 && isset($data['valor'])) {
            if ($this->conta->verifyEditValueAccount($this->input->post('id'), $data['valor']) > 0) {
                $data['status_aprovacao'] = '0'; # Retorna o pagamento para pré-aprovação
            }
        }

        $data['updated'] = 1;
        $data['id'] = $this->input->post('id');

        echo json_encode($this->conta->update($data));
    }

    public function estornar()
    {
        if ($this->auth->is_allowed_block('cancelar_conta')) {
            if (md5($this->input->post('senha')) == $this->fatura->senhaExclusaoFatura()) {
                $data['updated'] = 1;
                $data['status'] = 0;
                $data['data_pagamento'] = null;
                $data['valor_pago'] = null;
                $data['observacoes'] = $this->input->post('observacoes');;
                $data['id'] = $this->input->post('id_conta');
                exit(json_encode($this->conta->update($data)));
            } else {
                exit(json_encode(4)); # MANDA CODIGO DE ERRO PERMISSÃO PARA O JS
            }
        } else {
            exit(json_encode(3)); # MANDA CODIGO DE ERRO PERMISSÃO PARA O JS
        }
    }

    public function view_comprovantes($id)
    {
        $this->load->model('instalador');
        $instalador = $this->instalador->get_dataInstalador($id);
        $data['arquivos'] = array(
            'end' => $instalador ? $instalador[0]->comp_end : false,
            'account' => $instalador ? $instalador[0]->comp_conta : false,
            'cpf' => $instalador ? $instalador[0]->comp_cpf : false,
            'rg' => $instalador ? $instalador[0]->comp_rg : false
        );

        $data['id'] = $id;
        $this->load->view('instaladores/comprovantes', $data);
    }

    public function view_comprovantes_new($id)
    {
        $this->load->model('instalador');
        $instalador = $this->instalador->get_dataInstalador($id);
        $data['arquivos'] = array(
            'end' => $instalador ? $instalador[0]->comp_end : false,
            'account' => $instalador ? $instalador[0]->comp_conta : false,
            'cpf' => $instalador ? $instalador[0]->comp_cpf : false,
            'rg' => $instalador ? $instalador[0]->comp_rg : false
        );

        $data['id'] = $id;
        $this->load->view('instaladores/comprovantes_new', $data);
    }

    public function digitalizar($id)
    {
        $data['arquivos'] = $this->conta->get_files($id);
        $data['id_conta'] = $id;

        $this->load->view('contas/digitalizar', $data);
    }

    public function digitalizar_NS($id)
    {
        $data['arquivos'] = $this->conta->get_files($id);
        $data['id_conta'] = $id;

        $this->load->view('contas/digitalizar_NS', $data);
    }


    public function digitalizar_new($id)
    {
        $data['arquivos'] = $this->conta->get_files($id);
        $data['id_conta'] = $id;

        $this->load->view('contas/digitalizar_new', $data);
    }

    public function view_file($path)
    {
        redirect(base_url() . 'uploads/contas/' . $path);
    }

    public function digitalizacao_comp($inst_id)
    {
        if (isset($_FILES['file']) && $_FILES['file']) { # Verifica se veio arquivo na requisição AJAX
            if (isset($_POST['tipo'])) { # Verifica se TIPO veio na requisição
                $extend = $this->input->post('tipo');
                // Pasta onde o arquivo vai ser salvo
                $_UP['pasta'] = './uploads/instaladores/';

                // Tamanho máximo do arquivo (em Bytes)
                $_UP['tamanho'] = 1024 * 1024 * 20; // 20Mb

                // Array com as extensões permitidas
                $_UP['extensoes'] = array('pdf');

                // Array com os tipos de erros de upload do PHP
                $_UP['erros'][0] = 'Não houve erro';
                $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
                $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
                $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
                $_UP['erros'][4] = 'Não foi feito o upload do arquivo';

                // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
                if ($_FILES['file']['error'] != 0) {
                    echo json_encode(array('status' => 'ERRO', 'msg' => 'Não foi possível fazer o upload, erro: ' . $_UP['erros'][$_FILES['file']['error']]));
                    exit; // Para a execução do script
                }

                // Faz a verificação da extensão do arquivo
                $extensao = strtolower(end(explode('.', $_FILES['file']['name'])));

                if (array_search($extensao, $_UP['extensoes']) === false) {
                    echo json_encode(array('status' => 'ERRO', 'msg' => "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif"));
                    exit;
                } else if ($_UP['tamanho'] < $_FILES['file']['size']) {
                    echo json_encode(array('status' => 'ERRO', 'msg' => "O arquivo enviado é muito grande, envie arquivos de até 20Mb."));
                    exit;
                } else {
                    // Mantém o nome original do arquivo
                    $nome_final = $inst_id . '_' . $extend;
                }

                // Depois verifica se é possível mover o arquivo para a pasta escolhida
                if (move_uploaded_file($_FILES['file']['tmp_name'], $_UP['pasta'] . $nome_final . '.pdf')) {
                    $this->load->model('instalador');
                    $update = array();
                    if ($extend == 'account') $update = array('comp_conta' => '1');
                    elseif ($extend == 'rg') $update = array('comp_rg' => '1');
                    elseif ($extend == 'cpf') $update = array('comp_cpf' => '1');
                    else $update = array('comp_end' => '1');

                    $update['id'] = $inst_id;
                    $teste = $this->instalador->updateData($update);
                    // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                    echo json_encode(array('status' => 'OK', 'href' => base_url('uploads/instaladores/' . $inst_id . '_' . $extend . '.pdf'), 'ext' => $extend));
                } else {
                    // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                    echo json_encode(array('status' => 'ERRO', 'msg' => "Não foi possível enviar o arquivo, tente novamente"));
                }
            } else {
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Parâmetro ID incorreto ou não enviado.'));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'msg' => 'O Arquivo não foi enviado.'));
        }
    }

    public function digitalizacao($conta_id)
    {
        $nome_arquivo = "";
        $arquivo_enviado = false;
        $descricao = $this->input->post('descricao');
        $is_comprovante = $this->input->post('comprovante');
        $conta = $conta_id;
        $arquivo = isset($_FILES) ? $_FILES['arquivo'] : false;

        if ($descricao != false && $descricao == "") {
            die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
        } else {
            if ($arquivo) {
                if ($dados = $this->upload()) {
                    $nome_arquivo = $dados['file_name'];
                    $arquivo_enviado = true;
                    $full_path = $dados['full_path'];
                }
                if ($arquivo_enviado) {
                    $retorno = $this->conta->digitalizacao($conta, $descricao, $nome_arquivo, $is_comprovante, $full_path);
                    die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Processo realizado com sucesso!</div>', 'registro' => $retorno)));
                } else {
                    die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Verifique a extensão do arquivo. (Aceito apenas PDF)</div>')));
                }
            } else {
                die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
            }
        }
    }

    private function upload()
    {
        $config['upload_path'] = './uploads/contas';
        $config['allowed_types'] = 'pdf';
        $config['max_size']    = '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('arquivo')) {
            $data = $this->upload->data();
            return $data;
        } else {
            $error = array('error' => $this->upload->display_errors());
            return false;
        }
    }

    public function pneushow_antiga()
    {
        redirect('erros/erro_403'); // tela descontinuada, redirecionando para evitar acessos indevidos
        $this->auth->is_allowed('contas_pneushow');

        $dados['titulo'] = "Contas PneuShow";
        $dados['msg'] = '';
        $dados['contas'] = $this->conta->get_all(array('empresa' => 2));
        $dados['estatisticas'] = $this->conta->get_fluxo('showtecsystem.fluxo_pneushow');

        $fornecedores = array();
        foreach ($dados['contas'] as $f) :
            if (!in_array($f->fornecedor, $fornecedores))
                $fornecedores[] = $f->fornecedor;
        endforeach;

        $dados['fornecedores'] = json_encode($fornecedores);
        $this->mapa_calor->registrar_acessos_url(site_url('/contas/pneushow'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('contas/view_pneushow');
        $this->load->view('fix/footer_NS', $dados);
    }

    public function pneushow()
    {
        redirect('erros/erro_403'); // tela descontinuada, redirecionando para evitar acessos indevidos
        $this->auth->is_allowed('contas_pneushow');
        $dados['titulo'] = "Contas PneuShow";
        $dados['msg'] = '';
        $dados['contas'] = $this->conta->get_all(array('empresa' => 2));
        $dados['estatisticas'] = $this->conta->get_fluxo('showtecsystem.fluxo_pneushow');
        $fornecedores = array();
        foreach ($dados['contas'] as $f) :
            if (!in_array($f->fornecedor, $fornecedores))
                $fornecedores[] = $f->fornecedor;
        endforeach;
        $dados['fornecedores'] = json_encode($fornecedores);
        $this->mapa_calor->registrar_acessos_url(site_url('/contas/pneushow'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('contas/view_pneushow_nova', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function add_entrada()
    {
        $data['data']      = data_for_unix($this->input->post('data'));
        $data['descricao'] = $this->input->post('descricao');
        $data['valor']     = str_replace(',', '.', str_replace('.', '', $this->input->post('valor')));

        $resultado = $this->conta->add_entrada($data);

        echo json_encode($resultado);
    }

    public function lista_entradas()
    {

        $dados['entradas'] = $this->conta->get_all_entradas("data BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(CURDATE())");
        $this->load->view('contas/lista_entradas', $dados);
    }

    public function remove_entrada()
    {

        if ($this->input->post('id_entrada')) {
            if ($this->conta->delete_entrada_show(array('id_entrada' => $this->input->post('id_entrada')))) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false));
            }
        }
    }

    public function pre_aprovacao()
    {
        $this->auth->is_allowed('lancamentos');
        $dados['titulo'] = 'Pré-Aprovação - SHOW TECNOLOGIA';
        $dados['categorias'] = $this->conta->getCategorias();
        $this->mapa_calor->registrar_acessos_url(site_url('/contas/pre_aprovacao'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('contas/pre_aprovacao');
        $this->load->view('fix/footer_NS');
    }

    public function ajaxLoadAprovacao()
    {
        $this->load->model('instalador');
        $data['data'] = array();
        $aprov = $this->auth->is_allowed_block('aprovador');
        if ($aprov) {
            $data_contas = $this->conta->get_all_aprovacao($this->auth->get_login('admin', 'email'), true);
        } else {
            $data_contas = $this->conta->get_all_aprovacao($this->auth->get_login('admin', 'email'), false);
        }
        foreach ($data_contas as $key => $v) {
            $class = $v->arquivo;
            $desc_status = $v->status_aprovacao == 2 ? 'reprovado' : 'ativo';
            $url_mens = site_url('contas/view_msgAcount/' . $v->id);
            $none = 'hide';
            $class_btn = 'btn-default';
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                $none = 'btn';

                if (!isset($inst[$v->id_instalador])) {
                    $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                    $inst[$v->id_instalador] = $instalador[0];
                }
            }

            if (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '1' && $inst[$v->id_instalador]->comp_cpf == '1' && $inst[$v->id_instalador]->comp_rg == '1' && $inst[$v->id_instalador]->comp_conta == '1') {
                $btn_class = 'btn-success';
            } elseif (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '0' && $inst[$v->id_instalador]->comp_cpf == '0' && $inst[$v->id_instalador]->comp_rg == '0' && $inst[$v->id_instalador]->comp_conta == '0') {
                $btn_class = 'btn-default';
            } else {
                $btn_class = 'btn-warning';
            }
            $linkComprov = "<button data-toggle='modal' onclick='abrirComprovantes(this, $v->id_instalador)' data-conta='$v->id_instalador'
                    class='btn btn-mini dropdown-toggle $btn_class $none'
                    title='Comprovantes' style='margin-left: 5px;'> 
                    <i class='fa fa-folder-open'></i> 
                    </button>";

            switch ($class) {
                case (1);
                    $classe = 'btn-success';
                    break;
                case (0);
                    $classe = 'btn-default';
                    break;
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }
            if ($v->status_aprovacao == 2) {
                $class_btn = 'btn-danger';
            }

            // Verifica empresa
            if ($v->empresa == 4) {
                $desc_empresa = 'Show Technology';
            } elseif ($v->empresa == 3) {
                $desc_empresa = 'Norio Momoi EPP';
            } else {
                $desc_empresa = 'Show Tecnologia';
            }

            $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');
            $data['data'][] = array(
                $v->id,
                strtoupper($v->fornecedor),
                $v->descricao,
                $categoria,
                strtoupper($responsavel),
                dh_for_humans($v->dh_lancamento, false, false),
                dh_for_humans($v->data_vencimento, false, false),
                $valor_formatado,
                $desc_empresa,
                $v->status_aprovacao == 0 ? '<span id="label' . $v->id . '" class="label label-warning">Aguardando</span>' : '<span id="label' . $v->id . '" class="label label-danger">Reprovado</span>',
                $aprov == true ? "
                
                    <button id='aprova' title='Aprovar' data-id='$v->id' class='btn btn-primary'><i class='fa fa-thumbs-up'></i></button>
                    <button id='reprova' title='Reprovar' data-status='$desc_status' data-id='$v->id' class='btn btn-primary'><i class='fa fa-thumbs-down'></i></button>
                " : "
                    <button title='Aprovar' class='btn btn-primary' disabled='disabled'><i class='fa fa-thumbs-up'></i></button>
                    <button title='Reprovar' class='btn btn-primary' disabled='disabled'><i class='fa fa-thumbs-down'></i></button>
                ",
                "   <div style='display:flex'>
                        <button style='color: whitesmoke; text-shadow: none' class='btn-primary btn-design $none btn-info' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                            onclick='exibirDados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                            data-user='$userOs' data-serial_ret='$serial_retirado'
                        title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
                        <i class='fa fa-dashboard'></i> Dados
                        </button> $linkComprov

                        <button data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-primary' data-conta='$v->id' 
                        data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
                        data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)' style='margin-left: 5px;'>
                        <i class='fa fa-edit'></i>
                        </button>

                        <button data-toggle='modal' onclick='digitalizaDocs(this, $v->id)' data-conta='$v->id'
                        class='btn btn-atualizar-equipamento $classe'
                        title='Digitalizar Documentos' style='margin-left: 5px;'> 
                        <i class='fa fa-cloud-upload'></i>
                        </button>

                        <button data-toggle='modal' data-id='' title='Cancelar' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='btn' data-conta='$v->id'  onclick='cancel(this)' style='margin-left: 5px;background-color: red;color:white'>
                        <i class='fa fa-close'></i>
                        </button>

                        <a title='Mensagens' class='btn btn-primary' target='_blank' href='$url_mens' style='margin-left: 5px;'>
                            <i class='fa fa-comments'></i>
                        </a>
                   </div>
                "
            );
        }
        echo json_encode($data);
    }

    public function approveAcount()
    {
        # Verifica se é um inteiro e se o parâmetro ID foi passado na requisição
        if ($this->input->post('id') && is_numeric($this->input->post('id'))) {
            $id = $this->input->post('id'); # Cria referência do ID

            # Cria array de dados para update
            $update = array(
                'id' => $id,
                'status_aprovacao' => 1, # Status de conta aprovada
                'data_aprov' => date('Y-m-d'),
                'user_aprov' => $this->auth->get_login('admin', 'email')
            );

            if ($this->conta->update($update)) { # Envia dados para UPDATE
                # Retorno caso insert realizado com sucesso
                echo json_encode(array('status' => 'OK', 'msg' => 'Conta aprovada com sucesso. O usuário responsável será notificado sobre a aprovação!'));
            } else {
                # Retorno caso erro no insert
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Não foi possível aprovar a conta, tente novamente mais tarde!'));
            }
        } else {
            # Retorno caso parâmetro incorreto ou não informado
            echo json_encode(array('status' => 'ERRO', 'msg' => 'Parâmetro ID incorreto ou não informado.'));
        }
    }

    public function disapproveAcount()
    {
        # Verifica se é um inteiro e se o parâmetro ID foi passado na requisição
        if ($this->input->post('id') && is_numeric($this->input->post('id'))) {
            $id = $this->input->post('id'); # Cria referência do ID

            # Cria array de dados para update
            $update = array(
                'id' => $id,
                'status_aprovacao' => 2 # Status de conta reprovada
            );

            if ($this->conta->update($update)) { # Envia dados para UPDATE
                # Retorno caso update realizado com sucesso
                echo json_encode(array('status' => 'OK', 'msg' => 'Conta reprovada. O usuário responsável será notificado sobre a reprovação!'));
            } else {
                # Retorno caso erro no update
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Não foi possível reprovar a conta, tente novamente mais tarde!'));
            }
        } else {
            # Retorno caso parâmetro incorreto ou não informado
            echo json_encode(array('status' => 'ERRO', 'msg' => 'Parâmetro ID incorreto ou não informado.'));
        }
    }

    public function view_msgAcount($id = false, $conf = false)
    {
        $dados['titulo'] = 'Mensagens - Show Tecnologia'; # Titulo da Página
        $dados['msgs'] = $this->get_mensagensAccount($id); # Busca mensagens referentes a conta
        $dados['id_conta'] = $id;

        if ($conf) {
            $user = $this->auth->get_login('admin', 'email');
            $this->conta->confirmNotificationAccount($id, $user);
        }

        $this->load->view('fix/header', $dados);
        $this->load->view('contas/mensagens_aprov');
        $this->load->view('fix/footer');
    }

    public function uploadArchiveByAccount()
    {
        if (isset($_FILES['file']) && $_FILES['file']) { # Verifica se veio arquivo na requisição AJAX
            if (isset($_POST['id_conta']) && is_numeric($this->input->post('id_conta'))) { # Verifica se ID_CONTA é numerico e se veio na requisição
                // Pasta onde o arquivo vai ser salvo
                $_UP['pasta'] = './uploads/contas/';

                // Tamanho máximo do arquivo (em Bytes)
                $_UP['tamanho'] = 1024 * 1024 * 20; // 20Mb

                // Array com as extensões permitidas
                $_UP['extensoes'] = array('jpg', 'png', 'gif', 'pdf', 'xls', 'xlsx');

                // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
                $_UP['renomeia'] = false;

                // Array com os tipos de erros de upload do PHP
                $_UP['erros'][0] = 'Não houve erro';
                $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
                $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
                $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
                $_UP['erros'][4] = 'Não foi feito o upload do arquivo';

                // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
                if ($_FILES['file']['error'] != 0) {
                    echo json_encode(array('status' => 'ERRO', 'msg' => 'Não foi possível fazer o upload, erro: ' . $_UP['erros'][$_FILES['file']['error']]));
                    exit; // Para a execução do script
                }

                // Faz a verificação da extensão do arquivo
                $extensao = strtolower(end(explode('.', $_FILES['file']['name'])));

                if (array_search($extensao, $_UP['extensoes']) === false) {
                    echo json_encode(array('status' => 'ERRO', 'msg' => "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif"));
                    exit;
                } else if ($_UP['tamanho'] < $_FILES['file']['size']) {
                    echo json_encode(array('status' => 'ERRO', 'msg' => "O arquivo enviado é muito grande, envie arquivos de até 20Mb."));
                    exit;
                } else {
                    // Mantém o nome original do arquivo
                    $nome_final = $_FILES['file']['name'];
                }

                // Depois verifica se é possível mover o arquivo para a pasta escolhida
                if (move_uploaded_file($_FILES['file']['tmp_name'], $_UP['pasta'] . $nome_final)) {
                    // Comment chat by account
                    $chat = array(
                        'user' => $this->auth->get_login('admin', 'email'),
                        'comment' => '<a class="anexo" href="' . base_url('uploads/contas/' . $nome_final) . '" target="_blank"><i class="fa fa-file-archive-o"></i> ' . $nome_final . ' (Anexo)</a>',
                        'id_account' => $this->input->post('id_conta'),
                        'notification' => '0',
                        'date' => date('Y-m-d H:i:s')
                    );
                    // Insert comment
                    $this->conta->addMsgAccount($chat);
                    // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                    echo json_encode(array('status' => 'OK', 'msg' => '<a class="anexo" href="' . base_url('uploads/contas/' . $nome_final) . '" target="_blank"><i class="fa fa-file-archive-o"></i> ' . $nome_final . ' (Anexo)</a>'));
                } else {
                    // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                    echo json_encode(array('status' => 'OK', 'msg' => "Não foi possível enviar o arquivo, tente novamente"));
                }
            } else {
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Parâmetro ID incorreto ou não enviado.'));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'msg' => 'O Arquivo não foi enviado.'));
        }
    }

    public function ajaxNotificationAccount()
    {
        $user = $this->auth->get_login('admin', 'email');

        if ($this->auth->is_allowed_block('aprovador')) {
            $retorno = $this->conta->getNotifyByUser($user, true);
        } else {
            $retorno = $this->conta->getNotifyByUser($user);
        }

        if ($retorno && is_array($retorno)) {
            echo json_encode(array('status' => 'OK', 'notify' => $retorno));
        } else {
            echo json_encode(array('status' => 'ZERO', 'notify' => array()));
        }
    }

    public function sendMsgAccount()
    {
        $id = $this->input->post('id');
        $mensagem = $this->input->post('text');

        if ($id && is_numeric($id)) {
            if ($mensagem) {
                $dados = array(
                    'comment' => $mensagem,
                    'date' => date('Y-m-d H:i:s'),
                    'user' => $this->auth->get_login('admin', 'email'),
                    'id_account' => $id,
                    'notification' => '0'
                );

                if ($this->conta->addMsgAccount($dados)) {
                    echo json_encode(array('status' => 'OK', 'msg' => 'Mensagem enviada com sucesso.'));
                } else {
                    echo json_encode(array('status' => 'ERRO', 'msg' => 'Não foi possível enviar a mensagem, tente novamente mais tarde!'));
                }
            } else {
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Parâmetro MENSAGEM não enviada.'));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'msg' => 'Parâmetro ID não enviado ou incorreto.'));
        }
    }

    private function get_mensagensAccount($id = false)
    {
        $retorno = array();
        if ($id) {
            $dados = $this->conta->getMsgByAccount($id);

            if ($dados && is_array($dados)) {
                $usuario = $this->auth->get_login('admin', 'email');
                foreach ($dados as $d) {
                    $retorno[] = array(
                        'comment' => $d->comment,
                        'data' => date('d/m/Y H:i:s', strtotime($d->date)),
                        'user' => $d->user,
                        'tipo' => $d->user == $usuario ? 'out' : 'in'
                    );
                }
            }
        }

        return $retorno;
    }

    public function norio_antigo()
    {
        $this->auth->is_allowed('contas_showtecnologia');
        $dados['titulo'] = "Contas Norio Momoi";
        $dados['contas'] = $this->conta->get_all(array('empresa' => 3));
        $dados['estatisticas'] = $this->conta->get_fluxo('showtecsystem.fluxo_norio');
        $dados['categorias'] = $this->conta->getCategorias();
        $fornecedores = array();
        foreach ($dados['contas'] as $f) :
            if (!in_array($f->fornecedor, $fornecedores))
                $fornecedores[] = $f->fornecedor;
        endforeach;
        $dados['fornecedores'] = json_encode($fornecedores);

        $this->load->view('fix/header', $dados);
        $this->load->view('contas/view_norio');
        $this->load->view('fix/footer');
    }


    public function norio_antiga()
    {
        $this->auth->is_allowed('contas_showtecnologia');
        $dados['titulo'] = "Contas Norio Momoi";
        $dados['contas'] = $this->conta->get_all(array('empresa' => 3));
        $dados['estatisticas'] = $this->conta->get_fluxo('showtecsystem.fluxo_norio');
        $dados['categorias'] = $this->conta->getCategorias();
        $fornecedores = array();
        foreach ($dados['contas'] as $f) :
            if (!in_array($f->fornecedor, $fornecedores))
                $fornecedores[] = $f->fornecedor;
        endforeach;
        $dados['fornecedores'] = json_encode($fornecedores);
        $dados["load"] = ["select2", "mask", "jquery-form"];
        $this->mapa_calor->registrar_acessos_url(site_url('/contas/norio'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('contas/view_norio_NS');
        $this->load->view('fix/footer_NS');
    }


    public function norio()
    {
        $this->auth->is_allowed('contas_showtecnologia');
        $dados['titulo'] = "Contas Norio Momoi";
        $dados['contas'] = $this->conta->get_all(array('empresa' => 3));
        $dados['estatisticas'] = $this->conta->get_fluxo('showtecsystem.fluxo_norio');
        $dados['categorias'] = $this->conta->getCategorias();
        $fornecedores = array();
        foreach ($dados['contas'] as $f) :
            if (!in_array($f->fornecedor, $fornecedores))
                $fornecedores[] = $f->fornecedor;
        endforeach;
        $dados['fornecedores'] = json_encode($fornecedores);
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX', 'jquery-form');
        $this->mapa_calor->registrar_acessos_url(site_url('/contas/norio'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('contas/view_norio_NS_nova', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function contasNorio()
    {

        $this->auth->is_allowed('contas_showtecnologia');

        $data = array();
        $data_contas = $this->conta->get_all(array('empresa' => 3));
        foreach ($data_contas as $key => $v) {
            $class = $v->arquivo;
            $cp = $v->tem_comprovante;
            $status = $v->status;
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            if ($status == 1) {
                $status = 'disabled';
            }
            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            // switch ($class){
            //     case (1); $classe = 'btn btn-warning'; $icon = 'icon-white'; break;
            //     case (2); $classe = 'btn-design-green'; $icon = 'icon-white'; break;
            //     case (0); $classe = 'btn-design-pg'; $icon = 'icon-black'; break;
            // }

            if ($class == 1) {

                $classe = $cp ? 'btn-design-yellow' : 'btn-design-green';
            } else {

                $classe = 'btn-design-pg';
                $icon = 'icon-black';
            }
            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            $data[] = array(
                'id'              => $v->id,
                'fornecedor'      => strtoupper($v->fornecedor),
                'descrição'       => $v->descricao,
                'categoria'       => $categoria,
                'responsável'     => strtoupper($responsavel),
                'lançamento'      => dh_for_humans($v->dh_lancamento, false, false),
                'vencimento'      => dh_for_humans($v->data_vencimento, false, false),
                'valor'           => 'R$ ' . number_format($v->valor, 2, ',', '.'),
                'status'          => status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                'pagamento'       => $pagamento,
                'ferramentas'     =>
                "<button class='btn btn-mini btn-design-pg $status' data-conta='$v->id' 
					data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
					title='Pagar' data-original-title='Tooltip on top'>
					<i class='fa fa-money'></i> Pagar </button>
					<button data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-design-pg btn-mini' data-conta='$v->id' 
					data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
					data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
					<i class='icon-wrench icon-black'></i>
					</button>  
					<button data-toggle='modal' href=" . site_url('contas/digitalizar/' . $v->id) . " data-conta='$v->id'
					class='btn btn-mini dropdown-toggle btn-atualizar-equipamento $classe'
					title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
					<i class='icon-th $icon'></i> 
					</button>
					<button data-toggle='modal' data-id='' title='Cancelar' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='btn btn-mini btn-design-red $status' data-conta='$v->id'  onclick='cancel(this)'>
					<i class='icon-remove icon-white'></i>
					</button>
					",
            );
        }
        echo json_encode($data);
    }

    public function addNorio()
    {
        $ax = $this->input->post('valor');
        $user = $this->auth->get_login('admin', 'email');
        if (strlen($ax) <= 7)
            $data['valor'] = str_replace(',', '.', $ax);
        else if (strlen($ax) > 7)
            $data['valor'] = str_replace(',', '.', str_replace('.', '', $ax));
        $data['data_vencimento'] = data_for_unix($this->input->post('data_vencimento'));
        $data['fornecedor'] = $this->input->post('fornecedor');
        $data['descricao'] = $this->input->post('descricao');
        $data['categoria'] = $this->input->post('categoria');
        $data['user_Cad'] = $user;
        $data['status'] = 0;
        $data['dh_lancamento'] = date('Y-m-d H:i:s');
        $empresa = $this->input->post('empresa');
        $data['empresa'] =  3;
        if ($this->input->post('id_conta') != "-1") {
            $data['id_contas'] =  $this->input->post('id_conta');
        }

        if ($id = $this->conta->add($data)) {
            # Cria notification e comentário no chat
            $comment = array(
                'user' => $user,
                'comment' => 'Novo Lançamento',
                'id_account' => $id,
                'notification' => '0',
                'date' => date('Y-m-d H:i:s')
            );
            $this->conta->addMsgAccount($comment);
        }

        echo (json_encode($id));
        redirect(base_url() . 'index.php/contas/norio');
    }

    public function add_entrada_norio()
    {
        $data['data']      = $this->input->post('data');
        $data['descricao'] = $this->input->post('descricao');
        $data['valor'] = str_replace(',', '.', str_replace('.', '', $this->input->post('valor')));

        echo (json_encode($this->conta->add_entrada_norio($data)));
    }

    public function lista_entradas_norio()
    {

        $dados['entradas'] = $this->conta->get_norio_entradas("data BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(CURDATE())");
        $this->load->view('contas/lista_entradas_norio', $dados);
    }

    public function lista_entradas_norio_NS()
    {

        $dados['entradas'] = $this->conta->get_norio_entradas("data BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(CURDATE())");
        $this->load->view('contas/lista_entradas_norio_NS', $dados);
    }

    public function lista_entradas_norio_ajax()
    {
        $resultado = $this->conta->get_norio_entradas("data BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(CURDATE())");

        if ($resultado && count($resultado) > 0) {
            foreach ($resultado as $r) {
                if (!$r->data) $r->data = '';

                if ($r->valor) $r->valor = (float) $r->valor;
                else $r->valor = 0;

                if (!$r->descricao) $r->descricao = '';
            }
        }

        echo json_encode($resultado);
    }

    public function remove_entrada_norio()
    {
        //$this->conta->delete_entrada_norio(array('id_entrada' => $this->input->post('id_entrada')));

        if ($this->input->post('id_entrada')) {
            if ($this->conta->delete_entrada_norio(array('id_entrada' => $this->input->post('id_entrada')))) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false));
            }
        }
    }

    public function remove_conta_eua()
    {

        if ($this->input->post('id')) {
            if ($this->conta->delete_contas_eua(array('id' => $this->input->post('id')))) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false));
            }
        }
    }

    public function contasPneuShow()
    {

        $this->auth->is_allowed('contas_pneushow');

        $data = array();
        $data_contas = $this->conta->get_all(array('empresa' => 2));
        foreach ($data_contas as $key => $v) {
            $class = $v->arquivo;
            $status = $v->status;
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            if ($status == 1) {
                $status = 'disabled';
            }
            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            switch ($class) {
                case (1);
                    $classe = 'btn-design-green';
                    $icon = 'icon-white';
                    break;
                case (0);
                    $classe = 'btn-design-pg';
                    $icon = 'icon-black';
                    break;
            }
            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            $data[] = array(
                'id'              => $v->id,
                'fornecedor'      => strtoupper($v->fornecedor),
                'descrição'       => $v->descricao,
                'responsável'     => strtoupper($responsavel),
                'lançamento'      => dh_for_humans($v->dh_lancamento, false, false),
                'vencimento'      => dh_for_humans($v->data_vencimento, false, false),
                'valor'           => 'R$ ' . number_format($v->valor, 2, ',', '.'),
                'status'          => status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                'pagamento'       => $pagamento,
                'ferramentas'     =>
                "<button class='btn btn-mini btn-design-pg $status' data-conta='$v->id' 
					data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
					title='Pagar' data-original-title='Tooltip on top'>
					<i class='icon-folder-open icon-black'></i> Pagar </button>
					<button data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-design-pg btn-mini' data-conta='$v->id' 
					data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
					data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
					<i class='icon-wrench icon-black'></i>
					</button>  
					<button data-toggle='modal' href=" . site_url('contas/digitalizar/' . $v->id) . " data-conta='$v->id'
					class='btn btn-mini dropdown-toggle btn-atualizar-equipamento $classe'
					title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
					<i class='icon-th $icon'></i> 
					</button>
					<button data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='btn btn-mini btn-design-red $status' data-conta='$v->id'  onclick='cancel(this)'>
					<i class='icon-remove icon-white'></i>
					</button>
					",
            );
        }
        echo json_encode($data);
    }

    public function addPneushow()
    {
        $ax = $this->input->post('valor');
        if (strlen($ax) <= 7)
            $data['valor'] = str_replace(',', '.', $ax);
        else if (strlen($ax) > 7)
            $data['valor'] = str_replace(',', '.', str_replace('.', '', $ax));
        $data['data_vencimento'] = data_for_unix($this->input->post('data_vencimento'));
        $data['fornecedor'] = $this->input->post('fornecedor');
        $data['descricao'] = $this->input->post('descricao');
        $data['user_Cad'] = $this->auth->get_login('admin', 'email');
        $data['status'] = 0;
        $data['dh_lancamento'] = date('Y-m-d H:i:s');
        $empresa = $this->input->post('empresa');
        $data['empresa'] =  $empresa ? $empresa : 2;
        echo (json_encode($this->conta->add($data)));
        redirect(base_url() . 'index.php/contas/pneushow');
    }

    public function getLastId()
    {
        $lastId = $this->conta->get_all()[0]->id;
        echo json_encode($lastId);
    }

    public function getLastIdChanged()
    {
        $lastId = $this->conta->get_all()[0]->id;
        echo json_encode(array("id" => $lastId));
    }

    public function contas_por_inst()
    {
        $data = array();

        $draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $search = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)
        }

        $data_contas = $this->conta->get_all_inst(array('empresa' => 1, 'seriais !=' => 'b:0;', 'status' => 0), $start, $limit, $search);
        $total_filter = $this->conta->countAll_filter(array('empresa' => 1, 'seriais !=' => 'b:0;', 'status' => 0), $search);

        foreach ($data_contas as $key => $v) {
            $class = $v->arquivo;
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $status = 'disabled';
            }
            if ($v->seriais != 'b:0;') {
                $none = 'btn';
            }
            if ($v->seriais == null) {
                $none = 'hide';
            }
            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            switch ($class) {
                case (1);
                    $classe = 'btn-design-green';
                    $icon = 'icon-white';
                    break;
                case (0);
                    $classe = 'btn-design-pg';
                    $icon = 'icon-black';
                    break;
            }
            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            $data['data'][] = array(
                $v->id,
                strtoupper($v->fornecedor),
                $v->descricao,
                $categoria,
                strtoupper($responsavel),
                dh_for_humans($v->dh_lancamento, false, false),
                dh_for_humans($v->data_vencimento, false, false),
                'R$ ' . number_format($v->valor, 2, ',', '.'),
                status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                $pagamento,
                "                    
                <button style='color: whitesmoke; text-shadow: none' class='btn btn-mini btn-design $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                 onclick='dados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                 data-user='$userOs' data-serial_ret='$serial_retirado'
                title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
				<i class='fa fa-dashboard'></i> Dados </button>
                <button class='btn btn-mini btn-design-pg $status' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
				title='Pagar' data-original-title='Tooltip on top'>
				<i class='icon-folder-open icon-black'></i> Pagar </button>
				<button data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-design-pg btn-mini' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
				data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
				<i class='icon-wrench icon-black'></i>
				</button>  
				<button data-toggle='modal' href=" . site_url('contas/digitalizar/' . $v->id) . " data-conta='$v->id'
				class='btn btn-mini dropdown-toggle btn-atualizar-equipamento $classe'
				title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
				<i class='icon-th $icon'></i> 
				</button>
				<button data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='btn btn-mini btn-design-red $status' data-conta='$v->id'  onclick='cancel(this)'>
				<i class='icon-remove icon-white'></i>
				</button>
				",
            );
        }

        $data['draw'] = $draw + 1;
        $data['recordsTotal'] = $this->conta->get_totAll();
        $data['recordsFiltered'] = $total_filter;

        echo json_encode($data);
    }

    public function contas_por_inst_NS()
    {
        $data = array();

        $draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $search = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)
        }

        $data_contas = $this->conta->get_all_inst(array('empresa' => 1, 'seriais !=' => 'b:0;', 'status' => 0), $start, $limit, $search);
        $total_filter = $this->conta->countAll_filter(array('empresa' => 1, 'seriais !=' => 'b:0;', 'status' => 0), $search);

        foreach ($data_contas as $key => $v) {
            $class = $v->arquivo;
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $status = 'disabled';
            }
            if ($v->seriais != 'b:0;') {
                $none = 'btn';
            }
            if ($v->seriais == null) {
                $none = 'hide';
            }
            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            switch ($class) {
                case (1);
                    $classe = 'btn-success';
                    $icon = 'icon-white';
                    break;
                case (0);
                    $classe = 'btn-primary ';
                    $icon = 'icon-black';
                    break;
            }
            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            $data['data'][] = array(
                $v->id,
                strtoupper($v->fornecedor),
                $v->descricao,
                $categoria,
                strtoupper($responsavel),
                dh_for_humans($v->dh_lancamento, false, false),
                dh_for_humans($v->data_vencimento, false, false),
                'R$ ' . number_format($v->valor, 2, ',', '.'),
                status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                $pagamento,
                "
                <div style='display:flex'>                     
                <button style='margin-left: 5px; color: whitesmoke; text-shadow: none' class='btn btn-primary $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                 onclick='dados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                 data-user='$userOs' data-serial_ret='$serial_retirado'
                title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
				<i class='fa fa-dashboard'></i> Dados </button>
                <button style='margin-left: 5px;' class='btn btn-primary  $status' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
				title='Pagar' data-original-title='Tooltip on top'>
				<i class='fa fa-folder-open-o'></i> Pagar </button>
				<button style='margin-left: 5px;' data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='btn btn-primary ' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
				data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
				<i class='fa fa-wrench'></i>
				</button>  
				<button style='margin-left: 5px;' data-toggle='modal' href=" . site_url('contas/digitalizar/' . $v->id) . " data-conta='$v->id'
				class='btn dropdown-toggle btn-atualizar-equipamento $classe'
				title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
                <i class='fa fa-cloud-upload' aria-hidden='true'></i>
				</button>
				<button data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='btn btn-danger $status' data-conta='$v->id'  onclick='cancel(this)' style='margin-left: 5px;background-color: red;color:white'>
				<i class='fa fa-close' aria-hidden='true'></i>
				</button>     
                </div>  
				",
            );
        }

        $data['draw'] = $draw + 1;
        $data['recordsTotal'] = $this->conta->get_totAll();
        $data['recordsFiltered'] = $total_filter;

        echo json_encode($data);
    }

    public function addContaOs()
    {
        $ax = $this->input->post('valor');
        $user = $this->auth->get_login('admin', 'email');
        if (strlen($ax) <= 7)
            $data['valor'] = str_replace(',', '.', $ax);
        else if (strlen($ax) > 7)
            $data['valor'] = str_replace(',', '.', str_replace('.', '', $ax));
        $instalador = $this->input->post('instalador');
        $data['id_instalador'] = $this->input->post('id_instalador');
        $infoBanc = $this->input->post('info_bancaria');
        $weekly = $this->input->post('weekly');
        $data['data_vencimento'] = date('Y-m-d', strtotime('+10 days'));
        $data['fornecedor'] = $this->input->post('fornecedor');
        $data['descricao'] = $this->input->post('descricao');
        $data['categoria'] = $this->input->post('categoria');
        $data['user_Cad'] = $user;
        $data['status'] = 0;
        $data['dh_lancamento'] = date('Y-m-d H:i:s');
        $empresa = $this->input->post('empresa');
        $data['empresa'] =  $empresa ? $empresa : 1;

        $seriais = $this->input->post('seriais');
        $data['seriais'] = serialize($seriais);
        $cliente = $this->input->post('cliente');
        $data['cliente'] = serialize($cliente);
        $placa = $this->input->post('placa');
        $data['placa'] = serialize($placa);
        $id_os = $this->input->post('id_os');
        $data['id_os'] = serialize($id_os);
        $valorServ = $this->input->post('valorServ');
        $data['valorServ'] = serialize($valorServ);
        $dataServ = $this->input->post('dataServ');
        $data['dataServ'] = serialize($dataServ);
        $servicoOs = $this->input->post('servicoOs');
        $data['servicoOs'] = serialize($servicoOs);
        $userOs = $this->input->post('userOs');
        $data['userOs'] = serialize($userOs);
        $serial_retirado = $this->input->post('serial_retirado');
        $data['serial_ret'] = serialize($serial_retirado);
        $id_conta = $this->input->post('id_conta');
        //VERIFICAR SE EXISTE CONTA DO INSTALADOR EM ABERTO && SE A CONTA ESTÁ DENTRO DA MESMA SEMANA 
        if ($id_conta) {
            $verifica = $this->conta->get_all_accountInst(array('id_instalador' => $data['id_instalador'], 'id' => $id_conta));
            if (count($verifica) > 0) {
                $this->unificaConta($data, $verifica, $instalador, $infoBanc);
            } else {
                # Cria notification e comentário no chat
                if ($id = $this->conta->add($data)) {
                    $comment = array(
                        'user' => $user,
                        'comment' => 'Novo Lançamento',
                        'id_account' => $id,
                        'notification' => '0',
                        'date' => date('Y-m-d H:i:s')
                    );
                    $this->conta->addMsgAccount($comment);
                }

                echo (json_encode((int) $id));
            }
        } else {
            if ($id = $this->conta->add($data)) {
                # Cria notification e comentário no chat
                $comment = array(
                    'user' => $user,
                    'comment' => 'Novo Lançamento',
                    'id_account' => $id,
                    'notification' => '0',
                    'date' => date('Y-m-d H:i:s')
                );
                $this->conta->addMsgAccount($comment);
            }

            echo (json_encode((int) $id));
        }
    }

    private function unificaConta($conta, $conta2, $instalador, $infoBanc)
    {
        $valorSoma = null;
        $descSoma = null;
        $seriaisSoma = array();
        $clienteSoma = array();
        $placaSoma = array();
        $id_osSoma = array();
        $valorServSoma = array();
        $dataServSoma = array();
        $servicoOsSoma = array();
        $userOsSoma = array();
        $fornecedor = $conta['fornecedor'];
        $descricao = explode(' ', $conta['descricao']);
        $seriais = unserialize($conta['seriais']);
        $clientes = unserialize($conta['cliente']);
        $placa = unserialize($conta['placa']);
        $id_os = unserialize($conta['id_os']);
        $valorServ = unserialize($conta['valorServ']);
        $dataServ = unserialize($conta['dataServ']);
        $servicoOs = unserialize($conta['servicoOs']);
        $userOs = unserialize($conta['userOs']);
        $qtdService = $descricao[5];
        foreach ($conta2 as $c) {
            $descricao2  = explode(' ', $c->descricao);
            $valorSoma  += $c->valor;
            $descSoma   += $descricao2[5];
            $seriaisSoma   = array_merge($seriaisSoma, unserialize($c->seriais));
            $clienteSoma   = array_merge($clienteSoma, unserialize($c->cliente));
            $placaSoma     = array_merge($placaSoma, unserialize($c->placa));
            $id_osSoma     = array_merge($id_osSoma, unserialize($c->id_os));
            $valorServSoma = array_merge($valorServSoma, unserialize($c->valorServ));
            $dataServSoma  = array_merge($dataServSoma, unserialize($c->dataServ));
            $servicoOsSoma = array_merge($servicoOsSoma, unserialize($c->servicoOs));
            $userOsSoma    = array_merge($userOsSoma, unserialize($c->userOs));
        }

        $qtdServiceTotal = ($descSoma + $qtdService);
        $dados['valor'] = $conta['valor'] + $valorSoma;
        $dados['data_vencimento'] = date('Y-m-d');
        $dados['descricao'] = "ORDEM DE PAGAMENTO REF A " . $qtdServiceTotal . " SERVIÇO(S) DO TÉC. " . $instalador . " " . $infoBanc;
        //$dados['status'] = 0;
        $dados['fornecedor'] = $fornecedor;
        //$dados['empresa'] = 1;
        //$dados['user_cad'] = $this->auth->get_login('admin', 'email');
        $dados['categoria'] = "INSTALADOR";
        $dados['id_instalador'] = $conta['id_instalador'];
        $dados['dh_lancamento'] = date('Y-m-d H:i:s');
        $dados['seriais'] = serialize(array_merge($seriais, $seriaisSoma));
        $dados['cliente'] = serialize(array_merge($clientes, $clienteSoma));
        $dados['placa'] = serialize(array_merge($placa, $placaSoma));
        $dados['id_os'] = serialize(array_merge($id_os, $id_osSoma));
        $dados['valorServ'] = serialize(array_merge($valorServ, $valorServSoma));
        $dados['dataServ'] = serialize(array_merge($dataServ, $dataServSoma));
        $dados['servicoOs'] = serialize(array_merge($servicoOs, $servicoOsSoma));
        $dados['userOs'] = serialize(array_merge($userOs, $userOsSoma));

        $this->conta->updateForAdd($conta2[0]->id, $dados);
        echo (json_encode((int) $conta2[0]->id));
        //        redirect(base_url().'index.php/contas');

    }

    public function get_contas_instalador($instalador, $status = false)
    {
        echo json_encode($this->conta->get_all_accountInst(array('id_instalador' => $instalador)));
    }

    public function get_funcionarios()
    {
        echo json_encode($this->conta->funcionarios());
    }
    public function get_conta_funcionario($funcionario)
    {
        echo json_encode($this->conta->conta_funcionario($funcionario));
    }
    public function get_conta_fornecedores($fornecedor)
    {
        echo json_encode($this->conta->conta_fornecedor($fornecedor));
    }
    /*
     * baixa faturas
    */
    public function baixar($pagina = false)
    {
        $this->auth->is_allowed('contas_a_pagar');
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['titulo'] = 'Baixar Contas';
        $this->mapa_calor->registrar_acessos_url(site_url('/contas'));
        $this->load->view('fix/header', $dados);
        $this->load->view('contas/lista_baixadas');
        $this->load->view('fix/footer');
    }
    public function ajax_baixa_retorno()
    {
        $dados['fats_retorno'] = array();
        $arquivo = $this->session->flashdata('file_retorno');
        if ($arquivo) {
            $fats_retorno = $this->retorno->listar(array('arquivo_retorno' => $arquivo, 'operacao' => 'D'));
            if (count($fats_retorno) > 0) {
                $dados['content_retorno_pagamento'] = $this->conta->baixa_novo_retorno($fats_retorno);
                $this->load->view('faturas/retorno_baixa', $dados);
            } else {
                echo 'Nenhuma conta processada no retorno';
            }
        } else {
            echo 'Nenhuma conta encontrada nesse retorno';
        }
    }
    public function enviar_retorno()
    {
        $this->load->model('send_file');
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
            echo $this->send_file->envia_http($this->input->post());
    }

    public function remessas()
    {
        $dados['titulo'] = "Remessas";

        $this->load->view('fix/header', $dados);
        $this->load->view('contas/view_remessas');
        $this->load->view('fix/footer');
    }

    public function remessas_beta()
    {
        $dados['titulo'] = lang('Remessas');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/contas/remessas'));

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('remessas/view_remessas');
        $this->load->view('fix/footer_NS');
    }


    public function remessas_norio()
    {
        $dados['titulo'] = "Remessas Norio";

        $this->load->view('fix/header', $dados);
        $this->load->view('contas/view_remessas_norio');
        $this->load->view('fix/footer');
    }


    public function remessas_norio_beta()
    {
        $dados['titulo'] = "Remessas Norio";
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/contas/remessas_norio_beta'));


        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('remessas/view_remessasnorio');
        $this->load->view('fix/footer_NS');
    }


    public function updateContasPreAprovacao($us = false)
    {
        $dados['titulo'] = "Contas";
        if ($this->auth->is_allowed_block('cancelar_conta')) {
            if ($this->input->post('fornecedor') != '') {
                $data['fornecedor'] = $this->input->post('fornecedor');
                $data['descricao'] = $this->input->post('descricao-forn');
                $data['categoria'] = $this->input->post('categoria');
                $data['data_vencimento'] = $this->input->post('dt_vencimento');
                if ($us) $data['valor'] = str_replace(',', '', $this->input->post('valor'));
                else $data['valor'] = str_replace(',', '.', str_replace('.', '', $this->input->post('valor')));
                $post = $this->input->post();
                if (isset($post['cod_barras']) && $this->input->post('cod_barras') != "") {
                    $data['codigo_barra'] =  $this->input->post('cod_barras');
                    if ($this->input->post('id_conta') == "-2") {
                        $data['id_contas'] =  $this->input->post('id_conta');
                    } elseif ($this->input->post('id_conta') == "-1") {
                        $data['id_contas'] =  "";
                    }
                } elseif (isset($post['id_conta']) && $this->input->post('id_conta') != "-1") {
                    $data['id_contas'] =  $this->input->post('id_conta');
                }

                # Cria notification e comentário no chat
                $user = $this->auth->get_login('admin', 'email');
                $comment = array(
                    'user' => $user,
                    'comment' => 'O usuário ' . $user . ' alterou dados da conta.',
                    'id_account' => $this->input->post('id'),
                    'notification' => '0',
                    'date' => date('Y-m-d H:i:s')
                );
                $this->conta->addMsgAccount($comment);
            } else {
                if ($this->input->post('valor_pago') && $this->input->post('data_pagamento')) {
                    if ((md5($this->input->post('senha_pagamento')) == $this->fatura->senhaExclusaoFatura()) || (md5($this->input->post('senha_pagamento')) == $this->fatura->senhaExclusaoFatura2())) {
                        $ax = $this->input->post('valor_pago');
                        if (strlen($ax) <= 7)
                            $data['valor_pago'] = str_replace(',', '.', $ax);
                        else if (strlen($ax) > 7)
                            $data['valor_pago'] = str_replace(',', '.', str_replace('.', '', $ax));

                        $data['data_pagamento'] = data_for_unix($this->input->post('data_pagamento'));
                        $data['status'] = 1;

                        # Cria notification e comentário no chat
                        $user = $this->auth->get_login('admin', 'email');
                        $comment = array(
                            'user' => $user,
                            'comment' => 'Pagamento realizado com sucesso.',
                            'id_account' => $this->input->post('id'),
                            'notification' => '0',
                            'date' => date('Y-m-d H:i:s')
                        );
                        $this->conta->addMsgAccount($comment);
                    } else {
                        exit(json_encode(4)); # MANDA CODIGO DE ERRO PERMISSÃO PARA O JS
                    }
                } else {
                    # Cria notification e comentário no chat
                    $user = $this->auth->get_login('admin', 'email');
                    $comment = array(
                        'user' => $user,
                        'comment' => 'Pagamento realizado com sucesso.',
                        'id_account' => $this->input->post('id'),
                        'notification' => '0',
                        'date' => date('Y-m-d H:i:s')
                    );
                    $this->conta->addMsgAccount($comment);

                    $data['status'] = 3;
                }
            }
        } else {
            exit(json_encode(3)); # MANDA CODIGO DE ERRO PERMISSÃO PARA O JS
        }

        # Verifica se conta estava com aprovação e pendente para pagamento, e foi alterado o valor
        if (isset($data['status']) && $data['status'] != 3 && isset($data['valor'])) {
            if ($this->conta->verifyEditValueAccount($this->input->post('id'), $data['valor']) > 0) {
                $data['status_aprovacao'] = '0'; # Retorna o pagamento para pré-aprovação
            }
        }

        $data['updated'] = 1;
        $data['id'] = $this->input->post('id');

        echo json_encode($this->conta->update($data));
    }

    public function contas_ajax_nv($empresa = false)
    {
        if (!$empresa) $where = array('empresa' => 1);
        else $where = array('empresa' => $empresa);

        $data_contas = $this->conta->get_all($where); //, $page, $per_page, $search);

        $arObj = [];
        $x = 0;

        foreach ($data_contas as $key => $v) {
            $inst = array();
            $class = $v->arquivo;
            $cp = $v->tem_comprovante;
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $botao_pagar = "<a class='btn-design-pg' style='height: 36px;width: 46px; margin-bottom: 6px;' onclick='estornar($v->id)' title='Estornar pagamento'>
                Estornar </a>";
            } else {
                $botao_pagar = "<a style='cursor: pointer; color: black;' class='$status' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' 
                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
                title='Pagar' data-original-title='Tooltip on top'>
                Pagar</a>";
            }
            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                $none = 'btn';

                if (!isset($inst[$v->id_instalador])) {
                    $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                    $inst[$v->id_instalador] = $instalador[0];
                }
            }

            if (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '1' && $inst[$v->id_instalador]->comp_cpf == '1' && $inst[$v->id_instalador]->comp_rg == '1' && $inst[$v->id_instalador]->comp_conta == '1') {
                $btn_class = 'btn-success';
            } elseif (isset($inst[$v->id_instalador]) && $inst[$v->id_instalador]->comp_end == '0' && $inst[$v->id_instalador]->comp_cpf == '0' && $inst[$v->id_instalador]->comp_rg == '0' && $inst[$v->id_instalador]->comp_conta == '0') {
                $btn_class = 'btn-default';
            } else {
                $btn_class = 'btn-warning';
            }
            $linkComprov = "<a data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id_instalador) . " data-conta='$v->id_instalador'
                        class='dropdown-toggle $btn_class $none'
                        class='dropdown-toggle $btn_class $none'
                        title='Comprovantes' data-target='#myModal_comprovantes' style='cursor: pointer; color: black;' > 
                        Comprovante
                        </a>";

            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            if ($class == 1) {

                $classe = $cp ? 'btn-success' : 'btn-warning';
            } else {

                $classe = 'btn-default';
                $icon = 'fa fa-folder-open';
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            if ($empresa && $empresa == 4)
                $valor_formatado = 'US$ ' . number_format($v->valor, 2, '.', ',');
            else
                $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');

            $obj = (object) [
                "id" => $v->id,
                "fornecedor" => strtoupper($v->fornecedor),
                "descricao" => $v->descricao,
                "categoria" => $categoria,
                "responsavel" => strtoupper($responsavel),
                "dh_lancamento" => dh_for_humans($v->dh_lancamento, false, false),
                "data_vencimento" => dh_for_humans($v->data_vencimento, false, false),
                "valor_formatado" => $valor_formatado,
                "status_fatura" => status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                "pagamento" => $pagamento,
                "botaoEditar" => "<a data-toggle='modal'  data-target='#myModal_editar' title='Editar' style='cursor: pointer; color: black;'  data-conta='$v->id' data-controller='" . site_url('contas/get') . "' data-id='$v->id' data-vencimento=" . $v->data_vencimento . " data-valor='" . number_format($v->valor, 2, ',', '.') . "' data-categoria='$v->categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>Editar</a>",
                "botaoDigitalizar" => "<a data-toggle='modal' href=" . site_url('contas/digitalizar_NS/' . $v->id) . " data-conta='$v->id' class='dropdown-toggle btn-atualizar-equipamento' style='cursor: pointer; color: black;'  title='Digitalizar Documentos' data-target='#myModal_digitalizar'> Digitalizar Documentos</a>",
                "botaoExcluir" => "<a data-toggle='modal' style='cursor: pointer; color: black;'  data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='$status' data-conta='$v->id'  onclick='cancel(this)'>Excluir</a>",
                "botaPagar" => $botao_pagar,
                "botaoComprov" => $linkComprov,
                "botaoServicoPrestado" => "<a style='cursor: pointer; color: black;'  class=$none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "onclick='dados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs' data-user='$userOs' data-serial_ret='$serial_retirado' title='Dados Serviço Prestado' data-original-title='Tooltip on top'>Dados Serviço Prestado</a> "
            ];

            $arObj[$x] = $obj;
            $x++;
        }
        echo json_encode($arObj);
    }



    public function contas_ajax_NS_nova($empresa = false)
    {
        if (!$empresa) $where = array('empresa' => 1, 'status_aprovacao' => 1);
        else $where = array('empresa' => $empresa, 'status_aprovacao' => 1);

        $data_contas = $this->conta->get_all($where);

        $arObj = [];
        $x = 0;

        foreach ($data_contas as $key => $v) {
            $inst = array();
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $botao_pagar = "<a style='margin-left: 5px;' class=' ' onclick='estornar($v->id)' title='Estornar pagamento'>Estornar </a>";
            } else {
                $botao_pagar = "<a style='margin-left: 5px;' class='  $status' data-conta='$v->id' 
                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
                title='Pagar' data-original-title='Tooltip on top'>Pagar</a>";
            }
            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                if (!isset($inst[$v->id_instalador])) {
                    $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                    $inst[$v->id_instalador] = $instalador[0];
                }
            }

            $linkComprov = "<a style='margin-left: 5px;' data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id_instalador) . " data-conta='$v->id_instalador'
                        class='dropdown-toggle $none'
                        title='Comprovantes' data-target='#myModal_comprovantes'> 
                        Comprovantes
                        </a>";

            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }


            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            if ($empresa && $empresa == 4)
                $valor_formatado = 'US$ ' . number_format($v->valor, 2, '.', ',');
            else
                $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');

            $obj = (object) [
                "id" => $v->id,
                "fornecedor" => strtoupper($v->fornecedor),
                "descricao" => $v->descricao,
                "categoria" => $categoria,
                "responsavel" => strtoupper($responsavel),
                "dh_lancamento" => dh_for_humans($v->dh_lancamento, false, false),
                "data_vencimento" => dh_for_humans($v->data_vencimento, false, false),
                "valor_formatado" => $valor_formatado,
                "status_fatura" => status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                "pagamento" => $pagamento,
                "botaoEditar" => "<a style='margin-left: 5px;' data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='dropdown-item-acoes-editar' data-conta='$v->id' data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . " data-valor=" . "R$" . $v->valor . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>Editar</a>",
                "botaoDigitalizar" => "<a  style='margin-left: 5px;' data-toggle='modal' href=" . site_url('contas/digitalizar_NS/' . $v->id) . " data-conta='$v->id' class='dropdown-toggle ' title='Digitalizar Documentos' data-target='#myModal_digitalizar'>Digitalizar</a>",
                "botaoExcluir" => "<a style='margin-left: 5px;' data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='$status' data-conta='$v->id'  onclick='cancel(this)'>Excluir</a>",
                "botaPagar" => $botao_pagar,
                "botaoComprov" => $linkComprov,
                "botaoServicoPrestado" => "<a style='margin-left: 5px; color: whitesmoke; text-shadow: none' class=' $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . " onclick='exibirDados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs' data-user='$userOs' data-serial_ret='$serial_retirado' title='Dados Serviço Prestado' data-original-title='Tooltip on top'> Dados</a>"
            ];

            $arObj[$x] = $obj;
            $x++;
        }

        echo json_encode($arObj);
    }


    public function index()
    {
        $this->auth->is_allowed('contas_showtecnologia');
        $dados['titulo'] = "Contas";
        $dados['contas'] = $this->conta->get_all();
        $dados['estatisticas'] = $this->conta->get_fluxo();
        $dados['categorias'] = $this->conta->getCategorias();
        $fornecedores = $this->conta->getNameFornecedores();
        $dados['fornecedores'] = json_encode($fornecedores);
        $dados["load"] = ["select2", "mask", "jquery-form"];
        $this->mapa_calor->registrar_acessos_url(site_url('/contas'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('contas/view_NS_show', $dados);
        $this->load->view('fix/footer_NS');
    }


    public function contas_ajax_grid($empresa = false)
    {
        if (!$empresa) $where = array('empresa' => 1, 'status_aprovacao' => 1);
        else $where = array('empresa' => $empresa, 'status_aprovacao' => 1);
        $offset = 0;
        $limit = 100;

        $search = NULL;
        if ($this->input->get()) {
            $search = $this->input->get('search');
            $startRow = (int) $this->input->get('startRow');
            $endRow = (int) $this->input->get('endRow');
            $limit = $endRow - $startRow;
            $offset = $startRow;
        }

        $data_contas = $this->conta->get_all($where, $offset, $limit, $search);
        $num_rows = (int) $this->conta->count_all($where, $search);

        $arObj = [];
        $x = 0;

        foreach ($data_contas as $key => $v) {
            $inst = array();
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $botao_pagar = "<a class=' ' onclick='estornar($v->id)' title='Estornar pagamento'>
                 Estornar </a>";
            } else {
                $botao_pagar = "<a class='  $status' data-conta='$v->id' 
                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
                title='Pagar' data-original-title='Tooltip on top'>
                Pagar </a>";
            }
            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                $none = 'btn-sss';

                // if (!isset($inst[$v->id_instalador])) {
                //     $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                //     $inst[$v->id_instalador] = $instalador[0];
                // }
            }

            $linkComprov = "<a data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id_instalador) . " data-conta='$v->id_instalador'
                        class=' dropdown-toggle $none'
                        title='Comprovantes' data-target='#myModal_comprovantes'> 
                        Comprovantes
                        </a>";

            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            if ($empresa && $empresa == 4)
                $valor_formatado = 'US$ ' . number_format($v->valor, 2, '.', ',');
            else
                $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');

            $obj = (object) [
                "id" => $v->id,
                "fornecedor" => strtoupper($v->fornecedor),
                "descricao" => $v->descricao,
                "categoria" => $categoria,
                "responsavel" => strtoupper($responsavel),
                "dh_lancamento" => dh_for_humans($v->dh_lancamento, false, false),
                "data_vencimento" => dh_for_humans($v->data_vencimento, false, false),
                "valor_formatado" => $valor_formatado,
                "status_fatura" => status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                "pagamento" => $pagamento,
                "botaoEditar" => "<a data-toggle='modal'  data-target='#myModal_editar' title='Editar' class=' ' data-conta='$v->id' data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . $v->data_vencimento . " data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>Editar</a>",
                "botaoDigitalizar" => "<a class='digitalizarDocumento' data-conta='$v->id' title='Digitalizar Documentos'>Digitalizar</a>",
                "botaoExcluir" => "<a data-toggle='modal' data-id='' title='Excluir' data-status='$v->status' data-controller=" . site_url('contas/get') . " data-fornecedor='' class=' $status' data-conta='$v->id'  onclick='cancel(this)'>Cancelar</a>",
                "botaPagar" => $botao_pagar,
                "botaoComprov" => '',
                "botaoServicoPrestado" => "<a class='  $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . " onclick='exibirDados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs' data-user='$userOs' data-serial_ret='$serial_retirado' title='Dados Serviço Prestado' data-original-title='Tooltip on top'><i class='fa fa-dashboard'></i> Dados </a>"
            ];

            $arObj[$x] = $obj;
            $x++;
        }

        if (count($arObj) && $num_rows) {
            echo json_encode(array(
                "status" => "200",
                "lastRow" => $num_rows,
                "rows" => $arObj
            ));
        } else {
            echo json_encode(array(
                "status" => "404",
                "lastRow" => 0,
                "rows" => []
            ));
        }
    }

    public function getFiles($id)
    {
        try {
            $arquivos = $this->conta->get_files($id);

            if ($arquivos) {
                echo json_encode(array(
                    "status" => "200",
                    "arquivos" => $arquivos
                ));
            } else {
                echo json_encode(array(
                    "status" => "404",
                    "arquivos" => [],
                ));
            }
        } catch (\Exception $e) {
            echo false;
        }
    }



    public function contas_por_inst_NS_nova()
    {
        $search = null;
        $where = array('empresa' => 1, 'seriais !=' => 'b:0;', 'status' => 0);
        $offset = 0;
        $limit = 100;

        if ($this->input->get()) {
            $search = $this->input->get('search') ? $this->input->get('search') : null;
            $startRow = (int) $this->input->get('startRow');
            $endRow = (int) $this->input->get('endRow');
            $limit = $endRow - $startRow;
            $offset = $startRow;
        }

        $data_contas = $this->conta->get_all_inst($where, $offset, $limit, $search);

        $num_rows = (int) $this->conta->countAll_filter($where, $search);

        $arObj = [];
        $x = 0;

        foreach ($data_contas as $key => $v) {
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $status = 'disabled';
            }
            if ($v->seriais == null) {
                $none = 'hide';
            }
            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            $obj = (object) [
                "id" => $v->id,
                "fornecedor" => strtoupper($v->fornecedor),
                "descricao" => $v->descricao,
                "categoria" => $categoria,
                "responsavel" => strtoupper($responsavel),
                "dh_lancamento" => dh_for_humans($v->dh_lancamento, false, false),
                "data_vencimento" => dh_for_humans($v->data_vencimento, false, false),
                "valor_formatado" =>  'R$ ' . number_format($v->valor, 2, ',', '.'),
                "status_fatura" => status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                "pagamento" => $pagamento,

                "botaoEditar" => "<a data-toggle='modal'  data-target='#myModal_editar' title='Editar' class=' ' data-conta='$v->id' 
				                    data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . $v->data_vencimento . "
				                    data-valor=" . number_format($v->valor, 2, ',', '.') . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
				                    Editar
				                    </a> ",

                "botaoDigitalizar" => "<a class='digitalizarDocumento' data-conta='$v->id' title='Digitalizar Documentos'>Digitalizar</a>",

                "botaoExcluir" => "<a data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/get') . " data-fornecedor='' class='$status' data-conta='$v->id' data-status='$v->status' onclick='cancel(this)'>
				                    Cancelar</i>
				                    </a>",

                "botaPagar" => "<a class='  $status' data-conta='$v->id' 
				                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
				                title='Pagar' data-original-title='Tooltip on top'>
				                Pagar 
                                </a>",

                "botaoServicoPrestado" => "<a  class=' $none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                                            onclick='dados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                                            data-user='$userOs' data-serial_ret='$serial_retirado'
                                            title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
                                             Dados
                                             </a>"
            ];

            $arObj[$x] = $obj;
            $x++;
        }

        if (count($arObj) && $num_rows) {
            echo json_encode(array(
                "status" => "200",
                "lastRow" => $num_rows,
                "rows" => $arObj
            ));
        } else {
            echo json_encode(array(
                "status" => "404",
                "lastRow" => 0,
                "rows" => []
            ));
        }
    }



    public function contas_ajax2_nova($empresa = false)
    {
        if (!$empresa) $where = array('empresa' => 1);
        else $where = array('empresa' => $empresa);

        $search = NULL;
        if ($this->input->get()) {
            $search = $this->input->get('search');
        }

        $data_contas = $this->conta->get_all($where, 0, 100, $search);

        $arObj = [];
        $x = 0;


        foreach ($data_contas as $key => $v) {
            $inst = array();
            $status = $v->status;
            $none = 'hide';
            $pagamento = $v->data_pagamento;
            $categoria = $v->categoria;
            $responsavel = substr($v->user_cad, 0, strpos($v->user_cad, '@') + 0);

            $seriais = json_encode(unserialize($v->seriais));
            $placas = json_encode(unserialize($v->placa));
            $clientes = json_encode(unserialize($v->cliente));
            $os = json_encode(unserialize($v->id_os));
            $valorServ = json_encode(unserialize($v->valorServ));
            $dataServ = json_encode(unserialize($v->dataServ));
            $servicoOs = json_encode(unserialize($v->servicoOs));
            $userOs = json_encode(unserialize($v->userOs));
            $serial_retirado = json_encode(unserialize($v->serial_ret));

            if ($status == 1) {
                $botao_pagar = "<a style='height: 36px; margin-bottom: 6px;' onclick='estornar($v->id)' title='Estornar pagamento'>
                Estornar </a>";
            } else {
                $botao_pagar = "<a class='$status' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' 
                data-controller=" . site_url('contas/get') . " data-valor=" . number_format($v->valor, 2, ',', '.') . " onclick='update(this)'
                title='Pagar' data-original-title='Tooltip on top'>
                Pagar</a>";
            }
            if ($v->seriais == null || $v->seriais == 'b:0;' || $v->seriais == '') {
                $none = 'hide';
            } else {
                $none = 'block';

                if (!isset($inst[$v->id_instalador])) {
                    $instalador = $this->instalador->get_dataInstalador($v->id_instalador);
                    $inst[$v->id_instalador] = $instalador[0];
                }
            }

            $linkComprov = "<a data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id_instalador) . " data-conta='$v->id_instalador'
                        style='height: 36px; width: 46px; margin-bottom: 6px;' class='dropdown-toggle $none'
                        title='Comprovantes' data-target='#myModal_comprovantes'> 
                        Comprovantes
                        </a>";

            if ($pagamento == null) {
                $pagamento = 'Aguardando';
            } else {
                $pagamento = dh_for_humans($v->data_pagamento, false, false);
            }

            if ($categoria == null) {
                $categoria = '';
            }
            if ($responsavel == null) {
                $responsavel = '';
            }

            if ($empresa && $empresa == 4)
                $valor_formatado = 'US$ ' . number_format($v->valor, 2, '.', ',');
            else
                $valor_formatado = 'R$ ' . number_format($v->valor, 2, ',', '.');

            $obj = (object) [
                "id" => $v->id,
                "fornecedor" => strtoupper($v->fornecedor),
                "descricao" => $v->descricao,
                "categoria" => $categoria,
                "responsavel" => strtoupper($responsavel),
                "dh_lancamento" => dh_for_humans($v->dh_lancamento, false, false),
                "data_vencimento" => dh_for_humans($v->data_vencimento, false, false),
                "valor_formatado" =>  $valor_formatado,
                "status_fatura" => status_fatura($v->status, $v->data_vencimento, '', $v->status_remessa),
                "pagamento" => $pagamento,

                "botaoEditar" => "<a data-toggle='modal'  data-target='#myModal_editar' title='Editar' class='' style='height: 37px; margin-bottom: 5px;' data-conta='$v->id' 
				data-controller=" . site_url('contas/get') . " data-id='$v->id' data-vencimento=" . dh_for_humans($v->data_vencimento, false, false) . "
                data-valor=" . "R$" . $v->valor . " data-categoria='$categoria' data-fornecedor='$v->fornecedor' data-descricao='$v->descricao' onclick='edit(this)'>
				Editar
				</a>  ",

                "botaoDigitalizar" => "<a data-toggle='modal' href=" . site_url('contas/digitalizar/' . $v->id) . " data-conta='$v->id'
				class=' dropdown-toggle btn-atualizar-equipamento' style='height: 37px;width: 47px; margin-bottom: 5px;'
				title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
				Digitalizar
				</a>",

                // "botaoExcluir" => "<a
                // data-toggle='modal' data-id='' title='Excluir' data-controller=" . site_url('contas/deleteConta/' . $v->id) . "
                // data-fornecedor='' class='$status' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' onclick='cancel(this)'>
                // Excluir
                // </a>",

                "botaoExcluir" => "<a
                href='#' title='Excluir' data-controller='" . site_url('contas/deleteConta/' . $v->id) . "'
                class='$status' style='height: 37px;width: 47px; margin-bottom: 5px;' data-conta='$v->id' onclick='confirmDelete(this)'>
                Excluir
                </a>",

                "botaPagar" => $botao_pagar,

                "botaoComprov" => $linkComprov,

                "botaoServicoPrestado" => "<a style='color: whitesmoke; text-shadow: none; height: 36px; width: 46px; margin-bottom: 6px;' class='$none $status' data-valor=" . $valorServ . " data-total=" . number_format($v->valor, 2, ',', '.') . "
                onclick='dados(this)' data-serial='$seriais' data-placa='$placas' data-cliente='$clientes'  data-id_os='$os' data-data='$dataServ' data-servico='$servicoOs'
                data-user='$userOs' data-serial_ret='$serial_retirado'
                title='Dados Serviço Prestado' data-original-title='Tooltip on top'>
                Serviço Prestado
                </a>"
            ];

            $arObj[$x] = $obj;
            $x++;
        }

        echo json_encode($arObj);
    }
}
