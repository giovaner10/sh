<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fatura extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('contrato');
        $this->load->model('cliente');
        $this->load->model('envio_fatura');
    }

    public function getNumRpsById($id_fatura)
    {
        $fatura = $this->db->select('rps')->get_where('cad_faturas', array('Id' => $id_fatura))->row();

        if ($fatura) {
            if ($fatura->rps != '' && !empty($fatura->rps))
                return $fatura->rps;
        }
        return false;
    }

    public function inserir_fatura($dados) {
        $this->db->insert('cad_faturas', $dados);
        if($this->db->affected_rows() > 0)
            return $this->db->insert_id();
        return false;
    }

    public function inserir_fatura_log($dados) {
        $this->db->insert('showtecsystem.faturas_log', $dados);
        if($this->db->affected_rows() > 0)
            return $this->db->insert_id();
        return false;
    }

    public function inserir_item_fatura($item) {
        $this->db->insert('fatura_itens', $item);
        if($this->db->affected_rows() > 0)
            return true;
        return false;
    }

    public function atualizar_fatura($id_fatura, $dados) {
        $this->db->update('cad_faturas', $dados, array('Id' => $id_fatura));
        if($this->db->affected_rows() > 0)
            return true;
        return false;
    }
    public function update_comment_item($id_item, $dados) {
        $this->db->set('descricao_item',$dados);
        $this->db->where('id_item',$id_item);
        $this->db->update('showtecsystem.fatura_itens');
    }

    public function atualizar_item($id_item, $dados) {
        $this->db->update('fatura_itens', $dados, array('id_item' => $id_item));
        if ($this->db->affected_rows() > 0)
            return true;
        return false;
    }

    public function gravar($dados) {
        $this->db->insert('cad_faturas', $dados);
        if($this->db->affected_rows() > 0)
            return true;
        throw new Exception('Não foi possível gravar no banco de dados');
    }

    public function get($where,$sem_join = false, $select='*') {
        if(!$sem_join){
            $query = $this->db->select('cad_clientes.*, cad_faturas.*, fatura_itens.*, cad_faturas.status status_fatura')
                ->join('cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')
                ->join('fatura_itens', 'cad_faturas.Id = fatura_itens.id_fatura','left')
                ->where($where)
                ->get('cad_faturas');
            if($query->num_rows() > 0){
                foreach($query->result() as $fatura) {
                    $fatura->comments_status = $this->getStComment($fatura->Id);
                    $fatura->qtd_comments = $this->getQntdComments($fatura->Id);
                    $fatura->valor_abono = $this->valor_abonado($fatura->Id);
                    $fatura->valor_total -= $fatura->valor_abono;

                    return $fatura;
                }
            }
            return array();
        }
        else{
            $query = $this->db->select($select)
                ->where($where)
                ->get('cad_faturas');
            if($query->num_rows() > 0){
                return $query->row();
            }
            return array();
        }
    }

    private function getQntdComments($id_fatura)
    {
        return $this->db->where('id_fatura', $id_fatura)->from('comments_fatura')->count_all_results();
    }


    /*
     * RETORNA O VALOR TOTAL ABONADO DA FATURA
     */
    private function valor_abonado($id_fatura) {
        $total = 0;
        $abonos = $this->db->get_where('showtecsystem.fatura_itens', array('id_fatura' => $id_fatura, 'status' => 0))->result();

        if ($abonos) {
            foreach ($abonos as $abono) {
                $total += $abono->valor_item;
            }
        }

        return $total;
    }

    /*
    * METODO BUSCA TODAS AS FATURAS PEPNDENTES DE ENVIO
    */
    public function get_faturasPendentesEnv() {
        $this->db->limit(100);
        return $this->db->get_where('showtecsystem.cad_faturas', array('status' => 2))->result();
    }

    public function get_items($where, $select='*') {
        $query = $this->db->select($select)->where($where)->get('fatura_itens');
        if($query->num_rows() > 0)
            return $query->result();
        return array();
    }

    public function get_item($where) {
        $query = $this->db->get_where('fatura_itens', $where);
        if($query->num_rows() > 0) {
            foreach ($query->result() as $item);

            return $item;
        }
        return array();
    }

    public function delete_item($id_item) {
        $abono = false;
        $this->db->where('id_item', $id_item);
        $this->db->update('fatura_itens', array('status' => 0));
        if ($this->db->affected_rows() > 0)
            $abono = true;
        return $abono;
    }

    public function gravar_fatura($fatura, $eptc = false) {
        $this->db->insert('cad_faturas', $fatura['fatura']);
        if($this->db->affected_rows() > 0) {
            $id_fatura = $this->db->insert_id();
            $this->atualizar_fatura($id_fatura, array('numero' => $id_fatura));
            if(!$eptc) {
                foreach ($fatura['itens'] as $key => $item) {
                    $fatura['itens'][$key]['id_fatura'] = $id_fatura;
                }
                try {
                    $this->gravar_itens($fatura['itens']);
                    $this->session->unset_userdata('fatura');
                    return $id_fatura;
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            }
        } else {
            throw new Exception('Não foi possível gravar a fatura no banco de dados.');
        }
    }

    public function gravar_itens($itens) {
        $this->db->insert_batch('fatura_itens', $itens);
        if($this->db->affected_rows() == 0)
            throw new Exception('Não foi possível gravar os itens no banco de dados');
    }

    function transfereFatura($id, $status)
    {
        return $this->db->where('Id', $id)->update('cad_faturas', array('status' => $status));
    }

    public function ajaxListFaturas($start = 0, $limit = 10, $search = NULL, $draw = 1, $list = 'cad_faturas.status != 4') {
        if ($search && is_numeric($search)) {
            $sql = $this->db->select('cad_faturas.*, cad_faturas.status as status_fatura, cad_faturas.numero as numero_fatura, cad_clientes.*')
                    ->join('showtecsystem.cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')
		            ->where($list)
                    ->where(array('cad_faturas.Id' => $search))
		            ->order_by('cad_faturas.Id', 'DESC')
		            ->get('showtecsystem.cad_faturas', $limit, $start);
            $data['recordsFiltered'] = $this->db->where($list)->where(array('cad_faturas.Id' => $search))->count_all_results('showtecsystem.cad_faturas'); # Total de registros

        } elseif ($search && is_string($search)) {
            $sql = $this->db->select('cad_faturas.*, cad_faturas.status as status_fatura, cad_faturas.numero as numero_fatura, cad_clientes.*')
                    ->join('showtecsystem.cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')
                    ->where($list)
                    ->like('cad_clientes.nome' , $search)
		            ->order_by('cad_faturas.Id', 'DESC')
		            ->get('showtecsystem.cad_faturas', $limit, $start);
            $data['recordsFiltered'] = $this->db->join('showtecsystem.cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')->where($list)->like('cad_clientes.nome' , $search)->count_all_results('showtecsystem.cad_faturas'); # Total de registros

        } else {
            $sql = $this->db->select('cad_faturas.*, cad_faturas.status as status_fatura, cad_faturas.numero as numero_fatura, cad_clientes.*')
                    ->join('showtecsystem.cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')
		            ->where($list)
		            ->order_by('cad_faturas.Id', 'DESC')
		            ->get('showtecsystem.cad_faturas', $limit, $start);
            $data['recordsFiltered'] = $this->db->where($list)->count_all_results('showtecsystem.cad_faturas'); # Total de registros
        }

        if($sql->num_rows()>0){
			$faturas = $sql->result(); # Lista de Faturas
			// $data['recordsFiltered'] = $sql->num_rows(); # Total de registros Filtrados
            $data['recordsTotal'] = $this->db->where($list)->count_all_results('showtecsystem.cad_faturas'); # Total de registros
	        $data['draw'] = $draw+1; # Draw do datatable

            $data['data'] = array(); # Cria Array de registros
            foreach ($faturas as $key => $v) {
                $atalhos_nfe = '<div class="btn-group">';
                switch ($v->status_nfe) {
                    case 'Em Aberto':
                        if ($v->informacoes == 'TRACKER') {
                            $atalhos_nfe = '<a onclick="gerar_nota('.$v->Id.', "1")" id="gerar_nota'.$v->Id.'" class="btn btn-mini btn-primary" title="Gerar Nf-e"><i class="fa fa-file-code-o"></i></a>';
                        } else {
                            $atalhos_nfe = '<a onclick="gerar_nota('.$v->Id.', "2")" id="gerar_nota'.$v->Id.'" class="btn btn-mini btn-primary" title="Gerar Nf-e"><i class="fa fa-file-code-o"></i></a>';
                        }
                        $status_nfe = '<h4><span class="label label-primary">Em Aberto</span></h4>';
                        break;
                    case 'Pendente':
                        $status_nfe = '<h4><span class="label label-warning">Pendente</span></h4>';
                        break;
                    case 'Enviada':
                        $status_nfe = '<h4><span class="label label-info">Enviada</span></h4>';
                        break;
                    default:
                        $status_nfe = '<h4><span class="label label-success">Gerada</span></h4>';
                        break;
                }

                if (!$atalhos_nfe || $atalhos_nfe == '') {
                    $atalhos_nfe .= '<a id="gerar_nota'.$v->Id.'" class="btn btn-xs btn-primary" title="Gerar Nf-e" disabled="disabled"><i class="fa fa-file-code-o"></i></a>';
                }

                if ($v->rps) {
                    $i = $v->informacoes == "NORIO" ? "2" : "1";
                    $atalhos_nfe .= '<a href="'.site_url('nfes/printNfe').'/'.$i.'/'.$v->Id.'" target="_blank" class="btn btn-success btn-mini" title="Imprimir NF-e"><i class="fa fa-print"></i></a>';
                } else {
                    $atalhos_nfe .= '<button type="button" class="btn btn-success btn-mini" title="Imprimir Nf-e" disabled><i class="fa fa-print"></i></button>';
                }

                $valor_total = $v->valor_total-($v->valor_total * ($v->iss / 100))-($v->valor_total * ($v->cofins / 100))-($v->valor_total * ($v->irpj / 100))-($v->valor_total * ($v->csll / 100))-($v->valor_total * ($v->pis / 100));

                if ($v->status_fatura == '0' || $v->status_fatura == '2'){
                    $a_cancelar = '<a href="" onclick="transFatura('.$v->Id.', 4)"><i class="fa fa-exchange"></i> A Cancelar</a>';
                } elseif ($v->status_fatura == '4') {
                    $a_cancelar = '<a href="" onclick="transFatura('.$v->Id.', 0)"><i class="fa fa-exchange"></i> A Pagar</a>';
                } else {
                    $a_cancelar = '<a><i class="fa fa-exchange"></i> A Pagar</a>';
                }

                $v->comments_status = $this->getStComment($v->Id);
                $v->qtd_comments = $this->getQntdComments($v->Id);
                $v->valor_total -= $this->valor_abonado($v->Id);

                $class_comment = $v->comments_status == 0 ? 'btn-primary' : 'btn-danger';
                $data['data'][] = array(
                    '<input type="checkbox" onchange="checkBoxEvent(this)" name="cod_fatura[]" value="'.$v->Id.'"/>',
                    $v->Id,
                    $v->nome,
                    data_for_humans($v->data_vencimento),
                    'R$ '.number_format($valor_total, 2, ',', '.'),
                    $v->nota_fiscal,
                    $v->mes_referencia,
                    data_for_humans($v->periodo_inicial),
                    data_for_humans($v->periodo_final),
                    $v->data_pagto ? data_for_humans($v->data_pagto) : '',
                    'R$ '.number_format($v->valor_pago, 2, ',', '.'),
                    '<h4><span class="status_fatura_'.$v->numero_fatura.'">'.status_fatura($v->status_fatura, $v->data_vencimento).label_nova_data($v->status_fatura, $v->dataatualizado_fatura).'</span></h4>',
                    $status_nfe,
                    $atalhos_nfe.'<div class="btn-group">
                            <button class="btn btn-primary btn-mini dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i></button>
                            <ul class="dropdown-menu" style="margin-left: -95px;min-width: 0px !important;">
                                <li>
                                    <a href="'.site_url("faturas/abrir_NS/".$v->Id).'"><i class="fa fa-folder-open"></i> Abrir</a></li>
                                <li>
                                    <a href="#envia_anexo" onclick="countAnexo('.$v->Id.')" id="getAnexo" data-toggle="modal" data-target="#envia_anexo"><i class="fa fa-file"></i>
                                        Anexar
                                    </a>
                                </li>
                                <li>
                                    <a href="#"class="enviaFat" data-numero="'.$v->numero_fatura.'" ><i class="fa fa-envelope icon-black"></i>
                                        Enviar
                                    </a>
                                </li>
                                <li>
                                    <a href="'.site_url("faturas/imprimir_fatura/".$v->Id).'" target="_blank" ><i class="fa fa-barcode"></i>
                                        Boleto
                                    </a>
                                </li>
                                <li>
                                '.$a_cancelar.'
                                </li>
                            </ul>
                        </div></div>'.
                    '<a id="buttoncomment'.$v->Id.'" class="btn '.$class_comment.' btn-mini linkComment'.$v->Id.'" onclick="comments('.$v->Id.')">
                        <i class="fa fa-comments"></i>
                        <span class="badge">'.$v->qtd_comments.'</span>
                    </a>'
                );
            }

            return json_encode( (object) $data );
        }else {
            // return false;
            return json_encode( array() );
        }
    }

    public function listar($where = array(), $inicio, $limite, $order_campo = 'cad_faturas.Id', $order = 'DESC') {
        $query = $this->db->select('cad_faturas.*, cad_faturas.status status_fatura, cad_faturas.numero numero_fatura,
				cad_clientes.*, cad_clientes.informacoes')
            ->join('cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')
            ->where($where)
            ->order_by($order_campo, $order)
            ->get('cad_faturas', $limite, $inicio);
        if($query->num_rows() > 0) {
            $faturas = $query->result();
            foreach ($faturas as $fatura) {
                $fatura->comments_status = $this->getStComment($fatura->Id);
                $fatura->qtd_comments = $this->getQntdComments($fatura->Id);
                $fatura->valor_total -= $this->valor_abonado($fatura->Id);
            }
            return $faturas;
        }

        return false;
    }


    function getStComment($id_fatura)
    {
        return $this->db->where(array('status' => 0, 'id_fatura' => $id_fatura))->from('comments_fatura')->count_all_results();
    }

    public function total($where = array()) {
        return $this->db->where($where)
            ->join('cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')
            ->order_by('data_vencimento', 'ASC')
            ->count_all_results('cad_faturas');
    }

    public function listar_por_contrato($where = array(), $inicio, $limite, $order_campo = 'cad_faturas.Id', $order = 'DESC') {
        $query = $this->db->select('cad_faturas.*, cad_faturas.status status_fatura, cad_faturas.numero numero_fatura,
				cad_clientes.*, cad_clientes.numero numero_cliente,')
            ->join('cad_clientes', 'cad_clientes.id = cad_faturas.id_cliente')
            ->join('fatura_itens as itens', 'itens.id_fatura = cad_faturas.Id')
            ->where($where)
            ->group_by('cad_faturas.Id')
            ->order_by($order_campo, $order)
            ->get('cad_faturas', $limite, $inicio);
        if($query->num_rows() > 0)
            return $query->result();
        return false;
    }

    private function gerar_fatura($tipo_fatura, $descricao = '', $qtd, $first_due, $dados_fatura) {
        $hoje = date("Y-m-d");
        $vencimento = $first_due;
        $valor_boleto = 0;
        $valor_unit = 0;
        $valor_total = 0;
        $taxa_boleto = $dados_fatura['boleto'] == 1 ? 4.50 : 0;
        $instrucoes1 = utf8_decode($dados_fatura['instrucoes1']);
        $numero = $this->get_next_id();
        for($i = 1; $i <= $qtd; $i++) {
            if ($tipo_fatura == 'adesao') {
                $descricao = $descricao == '' ? 'Valor referente à taxa de adesão '.$i.' / '.$qtd : $descricao;
                $valor_boleto = $dados['valor_prestacao'];
                $valor_unit = $dados['valor_instalacao'];
                $vencimento = $dados['data_prestacao'];
            } else {
                $valor_unit = $dados_fatura['valor_mensal'];
            }
            $valor_total = $valor_unit * $dados_fatura['qtd_veiculos'];
            $dados_db = array('descricao' => $descricao, 'id_contrato' => $dados_fatura['id_contrato'],
                'id_cliente' => $dados_fatura['id_cliente'], 'data_vencimento' => $vencimento,
                'boleto_vencimento' => $vencimento, 'valor_boleto' => $valor_boleto,
                'quantidade' => $dados_fatura['qtd'], 'valor_unitario' => $valor_unit,
                'valor_total' => $valor_total, 'data_emissao' => $hoje,
                'status' => $dados_fatura['status'], 'instrucoes1' => $instrucoes1);
            try {
                $this->gravar($dados_db);
                $ids[] = $this->db->insert_id();
            } catch (Exception $e) {
                $erros = $e->getMessage();
            }
            if($i > 0)
                $vencimento = date('Y-m-d', strtotime("+30 day"));
        }
        if(!isset($erros) && isset($ids)) {
            try {
                $this->unificar_faturas($ids);
            } catch (Exception $erro_uni) {
                return $erro_uni->getMessage();
            }
        }
    }

    private function unificar_faturas($ids_faturas, $join = false) {
        $faturas = $this->db->where_in('id', $ids_faturas)
            ->order_by('data_vencimento', 'asc')
            ->get('cad_faturas');
        $numero_fatura = $this->get_next_id();
        $valor_boleto = 0;
        $total_fatura = 0;
        if (count($faturas) > 0) {
            $registro = 0;
            foreach ($faturas as $fatura) {
                $dt_venc_fatura_anterior = $registro == 0 ? $fatura->data_vencimento : $dt_venc_fatura_anterior;
                if ($join || $fatura->data_vencimento == $dt_venc_fatura_anterior) {
                    $i = 0;
                    if ($i == 0)
                        $data_venc = $fatura->data_vencimento;
                    $numero = $fatura->numero;
                    $valor_boleto += $fatura->valor_total;
                    $total_fatura = $valor_boleto;
                    $db_update[] = array('id' => $fatura->id, 'valor_boleto' => $valor_boleto,
                        'total_fatura' => $total_fatura,
                        'data_vencimento' => $data_venc,
                        'data_boleto' => $data_venc);
                    $i++;
                }
                $dt_venc_fatura_anterior = $fatura->data_vencimento;
                $registro++;
            }
            if (isset($db_update)) {
                $this->db->update('cad_faturas', $db_update, 'id');
                if($this->db->affected_rows() > 0)
                    return true;
            }
        }
        throw new Exception('Nenhuma fatura pode ser unificada. Verifique os parametros.');
    }

    /*
     * gera um novo número para faturas
    * tabela next_id
    */
    private function get_next_id() {
        $this->db->trans_start();
        $query = $this->db->get_where('next_id', array('id' => 1));
        if($query->num_rows() > 0) {
            foreach ($query->result() as $numero);
            $this->db->where('id', 1)
                ->update('cad_faturas', array('next_id' => $numero->next_id + 1));
            $this->db->trans_complete();
            return $numero->next_id;
        }
        return false;
    }
    /*
     * sessao com dados de faturas
    */
    public function sess_add_fatura($dados) {
        $nota_fiscal="";
        $mes_referencia="";
        $periodo_inicial="";
        $periodo_final="";
        $secretaria = null;
        $ticket = null;
        if($dados['nota_fiscal']){
            $nota_fiscal = $dados['nota_fiscal'];
        }
        if($dados['mes_referencia']){
            $mes_referencia = $dados['mes_referencia'];
        }
        if($dados['periodo_final']){
            $periodo_inicial = $dados['periodo_inicial'];
        }
        if($dados['periodo_inicial']){
            $periodo_final = $dados['periodo_final'];
        }
        if ($dados['secretaria']) {
            $secretaria = explode('-', $dados['secretaria']);
        }
        if($dados['id_ticket']){
            $ticket = $dados['id_ticket'];
        }

        $clie = explode('-', $dados['cliente']);
        if (is_array($clie))
            $cliente = $this->cliente->get(array('id'=>$clie[0]));
        elseif (is_numeric($clie))
            $cliente = $this->cliente->get(array('id' =>$clie));

        $sessao['fatura'] = array(
            'id_cliente' => $cliente->id,
            'data_vencimento' => $dados['data_vencimento'],
            'formapagamento_fatura' => $dados['forma_pagamento'],
            'id_ticket' => $ticket,
            'id_secretaria' => $secretaria[0],
            'secretaria' => $secretaria[1],
            'nota_fiscal' => $nota_fiscal,
            'mes_referencia' => $mes_referencia,
            'periodo_inicial' => $periodo_inicial,
            'cliente' => $cliente,
            'periodo_final' => $periodo_final,
            'itens' => array(),
            'nome_cliente' => $dados['cliente']
        );

        if ($this->session->userdata('fatura'))
            $this->session->unset_userdata('fatura');
        $this->session->set_userdata($sessao);
    }

    public function sess_get_fatura() {
        return $this->session->userdata('fatura');
    }

    public function sess_add_item_fatura($item) {
        $fatura = $this->sess_get_fatura();
        $fatura['itens'][] = array(
            'id_cliente' => $fatura['id_cliente'],
            'tipo_item' => $item['tipo_item'],
            'descricao_item' => $item['descricao_item'],
            'valor_item' => str_replace(',', '.', str_replace('.', '', $item['valor_item'])),
            'taxa_item' => $item['taxa_item'],
            'tipotaxa_item' => $item['tipotaxa_item'],
            'vencimento_item' => $fatura['data_vencimento'],
            'relid_item' => $item['relid_item'],
            'obs_item' => $item['obs_item']
        );
        $this->session->set_userdata('fatura', $fatura);
    }
    public function sess_remover_item_fatura($item) {
        $fatura = $this->sess_get_fatura();
        unset($fatura['itens'][$item['index']-1]);
        $itens=[];
        foreach($fatura['itens'] as $i){
            $itens[]=$i;
        }
        $fatura['itens'] = $itens;
        $this->session->set_userdata('fatura', $fatura);
    }

    public function sess_get_itens_fatura() {
        $fatura = $this->sess_get_fatura();
        return $fatura['itens'];
    }

    public function sess_fatura_delete() {
        $this->session->unset_userdata('fatura');
    }

    /*
     * metodos uteis para sessao da fatura
    */
    // metodo para calcular itens juros
    public function sess_total_juros() {
        $fatura = $this->session->userdata('fatura');
        $total = 0;
        if (count($fatura['itens']) > 0) {
            foreach($fatura['itens'] as $item) {
                if($item['taxa_item'] == 'sim' && $item['tipotaxa_item'] == 'juros') {
                    $total += $item['valor_item'];
                }
            }
        }
        return $total;
    }

    // metodo para calcular itens boleto
    public function sess_total_boleto() {
        $fatura = $this->session->userdata('fatura');
        $total = 0;
        if (count($fatura['itens']) > 0) {
            foreach($fatura['itens'] as $item) {
                if($item['taxa_item'] == 'sim' && $item['tipotaxa_item'] == 'boleto') {
                    $total += $item['valor_item'];
                }
            }
        }
        return $total;
    }

    // pega subtotal da fatura
    public function sess_subtotal_fatura() {
        $fatura = $this->session->userdata('fatura');
        $total = 0;
        if (count($fatura['itens']) > 0) {
            foreach($fatura['itens'] as $item) {
                if($item['taxa_item'] != 'sim') {
                    $total += $item['valor_item'];
                }
            }
        }
        return $total;
    }

    // total da fatura
    public function sess_total_fatura() {
        $subtotal = $this->sess_subtotal_fatura();
        $juros = $this->sess_total_juros();
        $boleto = $this->sess_total_boleto();
        return ($subtotal + ($juros + $boleto));
    }

    public function baixar_retorno() {
        $nome = $_FILES['arquivo']['name'];
        $type = $_FILES['arquivo']['type'];
        $size = $_FILES['arquivo']['size'];
        $tmp = $_FILES['arquivo']['tmp_name'];
        $pasta = "uploads/retorno"; //Nome da pasta onde vao ficar armazenados os arquivos;
        echo '<table border="1">
				<tr>
				<td>Ord</td>
				<td>Fatura</td>
				<td>Vencimento</td>
				<td>Pagamento</td>
				<td>Valor</td>
				<td>Cliente</td>
				</tr>
				<tr>';
        //echo $type;
        if ($type == 'application/octet-stream' ) { //application/x-ret'){
            //echo $pasta."/".$nome;
            if ($tmp) {
                if (!file_exists($pasta."/".$nome)) {
                    if (move_uploaded_file($tmp, $pasta."/".$nome)) {
                        $lendo = @fopen($pasta."/".$nome,"r");
                        if (!$lendo) {
                            echo "Erro ao abrir a URL.";
                            exit;
                        }
                        $i = 0;
                        $ii = 0;
                        $fatura_baixada = 0;

                        while (!feof($lendo)) {
                            $i++;
                            $linha = fgets($lendo,9999);
                            $t_u_segmento = substr($linha,13,1);//Segmento T ou U
                            $t_tipo_reg = substr($linha,7,1);//Tipo de Registro

                            if ($t_u_segmento == 'T') {
                                $t_cod_banco = substr($linha,0,3);//Código do banco na compensação
                                $t_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
                                $t_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
                                $t_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
                                $t_cod_conv_banco = substr($linha,23,6);//Código do convênio no banco - Código fornecido pela CAIXA, através da agência de relacionamento do cliente. Deve ser preenchido com o código do Cedente (6 posições).
                                $t_n_banco_sac = substr($linha,32,3);//Numero do banco de sacados
                                $t_mod_nosso_n = substr($linha,39,2);//Modalidade nosso número
                                $t_id_titulo_banco = substr($linha,41,15);//Identificação do titulo no banco - Número adotado pelo Banco Cedente para identificar o Título.
                                $t_cod_carteira = substr($linha,57,1);//Código da carteira - Código adotado pela FEBRABAN, para identificar a característica dos títulos. 1=Cobrança Simples, 3=Cobrança Caucionada, 4=Cobrança Descontada
                                $t_num_doc_cob = substr($linha,58,11);//Número do documento de cobrança - Número utilizado e controlado pelo Cliente, para identificar o título de cobrança.
                                $t_dt_vencimento = substr($linha,73,8);//Data de vencimento do titulo - Data de vencimento do título de cobrança.
                                $t_v_nominal = substr($linha,81,13);//Valor nominal do titulo - Valor original do Título. Quando o valor for expresso em moeda corrente, utilizar 2 casas decimais.
                                $t_cod_banco2 = substr($linha,96,3);//Código do banco
                                $t_cod_ag_receb = substr($linha,99,5);//Codigo da agencia cobr/receb - Código adotado pelo Banco responsável pela cobrança, para identificar o estabelecimento bancário responsável pela cobrança do título.
                                $t_dv_ag_receb = substr($linha,104,1);//Dígito verificador da agencia cobr/receb
                                $t_id_titulo_empresa = substr($linha,105,25);//identificação do título na empresa - Campo destinado para uso da Empresa Cedente para identificação do Título. Informar o Número do Documento - Seu Número.
                                $t_cod_moeda = substr($linha,130,2);//Código da moeda
                                $t_tip_inscricao = substr($linha,132,1);//Tipo de inscrição - Código que identifica o tipo de inscrição da Empresa ou Pessoa Física perante uma Instituição governamental: 0=Não informado, 1=CPF, 2=CGC / CNPJ, 9=Outros.
                                $t_num_inscricao = substr($linha,133,15);//Número de inscrição - Número de inscrição da Empresa (CNPJ) ou Pessoa Física (CPF).
                                $t_nome = substr($linha,148,40);//Nome - Nome que identifica a entidade, pessoa física ou jurídica, Cedente original do título de cobrança.
                                $t_v_tarifa_custas = substr($linha,198,13);//Valor da tarifa/custas
                                $t_id_rejeicoes = substr($linha,213,10);//Identificação para rejeições, tarifas, custas, liquidação e baixas
                                $numero = substr($t_id_titulo_banco,3,10);
                            }

                            if ($t_u_segmento == 'U') {
                                $t_id_titulo_banco;
                                $u_cod_banco = substr($linha,0,3);//Código do banco na compensação
                                $u_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
                                $u_tipo_reg = substr($linha,7,1);//Tipo de Registro - Código adotado pela FEBRABAN para identificar o tipo de registro: 0=Header de Arquivo, 1=Header de Lote, 3=Detalhe, 5=Trailer de Lote, 9=Trailer de Arquivo.
                                $u_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
                                $u_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
                                $u_juros_multa = substr($linha,17,15);//Jurus / Multa / Encargos - Valor dos acréscimos efetuados no título de cobrança, expresso em moeda corrente.
                                $u_desconto = substr($linha,32,15);//Valor do desconto concedido - Valor dos descontos efetuados no título de cobrança, expresso em moeda corrente.
                                $u_abatimento = substr($linha,47,15);//Valor do abat. concedido/cancel. - Valor dos abatimentos efetuados ou cancelados no título de cobrança, expresso em moeda corrente.
                                $u_iof = substr($linha,62,15);//Valor do IOF recolhido - Valor do IOF - Imposto sobre Operações Financeiras - recolhido sobre o Título, expresso em moeda corrente.
                                $u_v_pago = substr($linha,77,15);//Valor pago pelo sacado - Valor do pagamento efetuado pelo Sacado referente ao título de cobrança, expresso em moeda corrente.
                                $u_v_liquido = substr($linha,92,15);//Valor liquido a ser creditado - Valor efetivo a ser creditado referente ao Título, expresso em moeda corrente.
                                $u_v_despesas = substr($linha,107,15);//Valor de outras despesas - Valor de despesas referente a Custas Cartorárias, se houver.
                                $u_v_creditos = substr($linha,122,15);//Valor de outros creditos - Valor efetivo de créditos referente ao título de cobrança, expresso em moeda corrente.
                                $u_dt_ocorencia = substr(substr($linha,137,8),4,4).'-'.substr(substr($linha,137,8),2,2).'-'.substr(substr($linha,137,8),0,2);//Data da ocorrência - Data do evento que afeta o estado do título de cobrança.
                                $u_dt_efetivacao = substr($linha,145,8);//Data da efetivação do credito - Data de efetivação do crédito referente ao pagamento do título de cobrança.
                                $u_dt_debito = substr($linha,157,8);//Data do débito da tarifa
                                $u_cod_sacado = substr($linha,167,15);//Código do sacado no banco
                                $u_cod_banco_comp = substr($linha,210,3);//Cód. Banco Correspondente compens - Código fornecido pelo Banco Central para identificação na Câmara de Compensação, do Banco ao qual será repassada a Cobrança do Título.
                                $u_nn_banco = substr($linha,213,20);//Nosso Nº banco correspondente - Código fornecido pelo Banco Correspondente para identificação do Título de Cobrança. Deixar branco (Somente para troca de arquivos entre Bancos).

                                $u_juros_multa = substr($u_juros_multa,0,13).'.'.substr($u_juros_multa,13,2);
                                $u_desconto = substr($u_desconto,0,13).'.'.substr($u_desconto,13,2);
                                $u_abatimento = substr($u_abatimento,0,13).'.'.substr($u_abatimento,13,2);
                                $u_iof = substr($u_iof,0,13).'.'.substr($u_iof,13,2);
                                $u_v_pago = substr($u_v_pago,0,13).'.'.substr($u_v_pago,13,2);
                                $u_v_liquido = substr($u_v_liquido,0,13).'.'.substr($u_v_liquido,13,2);
                                $u_v_despesas = substr($u_v_despesas,0,13).'.'.substr($u_v_despesas,13,2);
                                $u_v_creditos = substr($u_v_creditos,0,13).'.'.substr($u_v_creditos,13,2);

                                $data_agora = date('Y-m-d');
                                $hora_agora = date('H:i:s');

                                $numero = intval($numero);
                                $ii++;

                                echo $u_v_creditos.' - '.$u_v_despesas.' - '.$u_v_liquido.' / ';

                                $fatura = array();
                                $d_update = array('valor_pago' => $u_v_pago, 'data_pagto' => $u_dt_ocorencia,
                                    'valor_pago_fn' => $u_v_pago,
                                    'pagamento_fn' => $u_dt_ocorencia,
                                    'status_fn' => 'Pago',
                                    'data_fn' => $data_agora,
                                    'hora_fn' => $hora_agora,
                                    'retorno_fn' => $nome,
                                    'status' => 1);
                                if($numero < 30000){
                                    $fatura = $this->get(array('cad_faturas.numero' => $numero));
                                    if (count($fatura) > 0){
                                        //$this->db->update('cad_faturas', $d_update, array('numero' => $numero));
                                        $fatura_baixada++;
                                    }else {
                                        goto gravaNova;
                                    }
                                }else{
                                    gravaNova:
                                    $fatura = $this->get(array('cad_faturas.Id' => $numero));
                                    if (count($fatura) > 0) {
                                        //$this->db->update('cad_faturas', $d_update, array('Id' => $numero));
                                        $fatura_baixada++;
                                    }
                                }

                                if(count($fatura) > 0){
                                    echo '<tr>';
                                    echo '<td align="left">'.$ii.'</td>';
                                    echo '<td align="left">'.$numero.'</td>';
                                    echo '<td align="left">'.data_for_humans($fatura->data_vencimento).'</td>';
                                    echo '<td align="left">'.data_for_humans($u_dt_ocorencia).'</td>';
                                    echo '<td align="right">'.number_format($u_v_pago, 2, ',', '.').'</td>';
                                    echo '<td>'.$fatura->nome.'</td>';
                                    echo '</tr>';
                                }


                            }
                        }

                        fclose($lendo);
                    }
                }else{
                    unlink($pasta."/".$nome);
                    echo '<td colspan="1000">Não foi possível importar arquivo. Por favor, tente novamente!</td>';
                }
            }
        }
        echo '<tr><td colspan="6">Faturas no Retorno: <b>'.$ii.'</b> - Faturas Baixadas: <b>'.$fatura_baixada.'</b></td></tr>';
        echo '</table>';
    }

    public function baixar_retorno_sicredi() {
        $nome = $_FILES['arquivo']['name'];
        $type = $_FILES['arquivo']['type'];
        $size = $_FILES['arquivo']['size'];
        $tmp = $_FILES['arquivo']['tmp_name'];
        $pasta = "uploads/retorno"; //Nome da pasta onde vao ficar armazenados os arquivos;
        echo '<table border="1">
				<tr>
				<td>Ord</td>
				<td>Fatura</td>
				<td>Vencimento</td>
				<td>Pagamento</td>
				<td>Valor</td>
				<td>Cliente</td>
				</tr>
				<tr>';
        $faturas_baixar = array();
        //echo $type;
        if($type == 'application/octet-stream' ){ //application/x-ret'){

            //echo $pasta."/".$nome;
            if($tmp){

                if(!file_exists($pasta."/".$nome)){

                    if(move_uploaded_file($tmp, $pasta."/".$nome)){

                        $lendo = @fopen($pasta."/".$nome,"r");

                        if (!$lendo){
                            echo "Erro ao abrir a URL.";
                            exit;
                        }

                        $i = 0;
                        $ii = 0;
                        $fatura_baixada = 0;

                        while (!feof($lendo)){

                            $i++;
                            $linha = fgets($lendo,9999);
                            $t_ini_detalhe = substr($linha, 0, 1);

                            if ($t_ini_detalhe == 1){
                                $numero_fatura = substr($linha, 50, 55);
                                $valor_pago = intval(substr($linha, 253, 265));
                                $data_pagamento = substr($linha, 110, 115);

                                if (!in_array($numero_fatura, $faturas_baixar)){
                                    $faturas_baixar[$numero_fatura]['valor'] = $valor_pago;
                                }
                            }
                        }
                    }
                }
            }
        }
        var_dump($faturas_baixar);
    }

    public function have_fee($fee_type, $itens) {
        $valor_fee = 0.00;
        foreach ($itens as $item) {
            if($item->taxa_item == 'sim' && $item->tipotaxa_item == $fee_type)
                $valor_fee += $item->valor_item;
        }
        return $valor_fee;
    }

    public function subtotal_fatura($itens) {
        $valor_fee = 0.00;
        foreach ($itens as $item) {
            if($item->taxa_item == 'nao') {
                    $valor_fee += $item->valor_item;
            }
        }
        return $valor_fee;
    }

    public function subtot_fatura($id_fatura) {
        $valor = 0;
        $query = $this->db->select_sum('valor_item')
            ->where(array('id_fatura' => $id_fatura, 'taxa_item' => 'nao'))
            ->get('fatura_itens');
        if ($query->num_rows() > 0) {
            $resultado = $query->result();
            $valor = $resultado[0]->valor_item;
        }
        return $valor;
    }

    public function total_fatura($id_fatura) {
        $valor = 0;
        $query = $this->db->select_sum('valor_item')
            ->where(array('id_fatura' => $id_fatura))
            ->get('fatura_itens');
        if($query->num_rows() > 0) {
            $resultado = $query->result();
            $valor = $resultado[0]->valor_item;
        }
        return $valor;
    }

    /*
     * valida se o item já existe na fatura
    */
    private function have_item($tipo_item, $itens, $relid) {
        $exists = false;
        foreach ($itens as $item) {
            if($item->tipo_item == $tipo_item && $item->relid_item == $relid)
                $exists = true;
        }
        return $exists;
    }

    /*
     * verifica se fatura tem juros
    */
    public function have_juros($itens) {
        $juros = false;
        if (count($itens) > 0) {
            foreach ($itens as $item){
                if($item->taxa_item == 'sim' && $item->tipotaxa_item == 'juros')
                    $juros = $item;
            }
        }
        return $juros;
    }

    /*
     * verifica se existe taxa boleto
    */
    public function validar_taxa($id_fatura, $taxa) {
        // $taxa = false;
        $itens = $this->get_items(array('fatura_itens.id_fatura' => $id_fatura, 'fatura_itens.tipotaxa_item' => $taxa));
        if(count($itens) > 0)
            return true;
        return false;
    }

    /*
     * adiciona juros na fatura
    */
    public function atualizar_vencimento($id_fatura, $dt_atualizada, $taxa_juros = 0.33) {
        $fatura = $this->get(array('cad_faturas.Id' => $id_fatura));
        if(is_date($dt_atualizada)) {
            if(count($fatura) > 0) {
                $dt_venc = new DateTime($fatura->data_vencimento);
                $dt_atual = new DateTime($dt_atualizada);
                if ($dt_atual > $dt_venc) {
                    $atraso = $dt_venc->diff($dt_atual);
                    $val_juros = calcula_juros($this->subtot_fatura($id_fatura), $taxa_juros, $atraso->days);
                    $itens_fatura = $this->get_items(array('fatura_itens.id_fatura' => $fatura->Id));
                    // verifica se já existe juros nos itens da fatura
                    $juros = $this->have_juros($itens_fatura);
                    if( $juros ) {
                        $this->atualizar_item($juros->id_item, array('valor_item' => $val_juros));
                    } else {
                        $d_item = array('id_fatura' => $fatura->Id, 'id_cliente' => $fatura->id_cliente,
                            'descricao_item' => 'Juros', 'valor_item' => $val_juros,
                            'taxa_item' => 'sim', 'tipotaxa_item' => 'juros', 'vencimento_item' => $dt_atualizada);
                        $this->inserir_item_fatura($d_item);
                    }
                }
                // atualiza valor fatura com juros
                $val_fatura = $this->total_fatura($id_fatura);
                $this->atualizar_fatura($id_fatura, array('valor_total' => $val_fatura,
                    'dataatualizado_fatura' => $dt_atualizada));
            } else {
                throw new Exception('Nenhuma fatura localizada com o ID informado.');
            }
        } else {
            throw new Exception('A data informada não é válida! Insira uma nova data.');
        }
    }

    public function migrar($id_fatura, $numero) {
        $faturas = $this->listar(array('cad_faturas.numero' => $numero), 0, 99999);
        if (count($faturas) > 0){
            foreach ($faturas as $fatura){
                $d_contrato = $fatura->id_contrato > 0 ? '[Contrato '.$fatura->id_contrato.'] ' : '';
                $d_qtd = ' {'.intval($fatura->quantidade).' unidade(s)}';
                if(substr($fatura->descricao,0,19)=='FATURA PROPORCIONAL'){
                    $descricao = $d_contrato.$fatura->descricao;
                    $valor_item = $fatura->valor_total;
                }
                else{
                    $descricao = $d_contrato.$fatura->descricao.$d_qtd;
                    $valor_item = $fatura->valor_unitario * $fatura->quantidade;
                }
                $d_item = array('id_cliente' => $fatura->id_cliente, 'id_fatura' => $id_fatura,
                    'tipo_item' => 'avulso', 'descricao_item' => $descricao, 'relid_item' => $fatura->id_contrato,
                    'valor_item' => $valor_item, 'vencimento_item' => $fatura->data_vencimento );
                if($this->inserir_item_fatura($d_item) && $fatura->Id != $id_fatura) {
                    $this->db->delete('cad_faturas', array('Id' => $fatura->Id));
                }
            }
            /*$item_boleto = array('id_cliente' => $fatura->id_cliente, 'id_fatura' => $id_fatura,
                'tipo_item' => '', 'descricao_item' => 'Taxa Boleto',
                'valor_item' => 4.5, 'taxa_item' => 'sim', 'tipotaxa_item' => 'boleto',
                'vencimento_item' => $fatura->data_vencimento );
            $this->inserir_item_fatura($item_boleto);*/
            $val_fatura = $this->total_fatura($id_fatura);
            $d_fatura_atualizado = array('descricao' => '', 'id_contrato' => '', 'valor_total' => $val_fatura);
            if(!$this->atualizar_fatura($id_fatura, $d_fatura_atualizado)){
                throw new Exception('Não foi possível atualizar os valores da migração dessa fatura.');
            }
        }
    }

    public function migrar_todas() {

        $faturas = $this->listar(array('cad_faturas.numero >' => '0','descricao !='=>'','cad_faturas.status !='=>'1'), 0, 1);
        //echo json_encode($faturas);die;
        if (count($faturas) > 0){
            foreach ($faturas as $fatura){
                $id_fatura=$fatura->Id;
                $numero=$fatura->numero_fatura;
                echo $id_fatura."<br>";
                $d_contrato = $fatura->id_contrato > 0 ? '[Contrato '.$fatura->id_contrato.'] ' : '';
                $d_qtd = ' {'.intval($fatura->quantidade).' unidade(s)}';
                if(substr($fatura->descricao,0,19)=='FATURA PROPORCIONAL'){
                    $descricao = $d_contrato.$fatura->descricao;
                    $valor_item = $fatura->valor_total;
                }
                else{
                    $descricao = $d_contrato.$fatura->descricao.$d_qtd;
                    $valor_item = $fatura->valor_unitario * $fatura->quantidade;
                }
                $d_item = array('id_cliente' => $fatura->id_cliente, 'id_fatura' => $id_fatura,
                    'tipo_item' => 'avulso', 'descricao_item' => $descricao, 'relid_item' => $fatura->id_contrato,
                    'valor_item' => $valor_item, 'vencimento_item' => $fatura->data_vencimento );
                if($this->inserir_item_fatura($d_item) && $fatura->Id != $id_fatura) {
                    $this->db->delete('cad_faturas', array('Id' => $fatura->Id));
                }
            }
            /*$item_boleto = array('id_cliente' => $fatura->id_cliente, 'id_fatura' => $id_fatura,
                'tipo_item' => '', 'descricao_item' => 'Taxa Boleto',
                'valor_item' => 4.5, 'taxa_item' => 'sim', 'tipotaxa_item' => 'boleto',
                'vencimento_item' => $fatura->data_vencimento );
            $this->inserir_item_fatura($item_boleto);*/
            $val_fatura = $this->total_fatura($id_fatura);
            $d_fatura_atualizado = array('descricao' => '', 'id_contrato' => '', 'valor_total' => $val_fatura);
            if(!$this->atualizar_fatura($id_fatura, $d_fatura_atualizado)){
                throw new Exception('Não foi possível atualizar os valores da migração dessa fatura.');
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function baixa_novo_retorno($fats_retorno) {
        $fats_execs = array();
        if (count($fats_retorno) > 0) {
            $msg_erro = '';
            foreach ($fats_retorno as $fat_retorno) {
                $fats_execs['retorno'][] = $fat_retorno;
                if ($fat_retorno->fatnumero_retorno > 30000) {
                    $fat_num = $this->get(array('cad_faturas.numero' => $fat_retorno->fatnumero_retorno));
                    if (count($fat_num)) {
                        $d_update = array('valor_pago' => $fat_retorno->valorpago_retorno,
                            'data_pagto' => $fat_retorno->datapagto_retorno,
                            'valor_pago_fn' => $fat_retorno->valorpago_retorno,
                            'status_fn' => 'Pago',
                            'retorno_fn' => $fat_retorno->arquivo_retorno,
                            'status' => 1);
                        $val_fatura = $fat_num->valor_boleto > $fat_num->valor_total ? $fat_num->valor_boleto : $fat_num->valor_total;
                        $percent_boleto = compara_valor_porcent($val_fatura, $fat_retorno->valorpago_retorno);
                        if ($percent_boleto > 10 || $fat_retorno->valorpago_retorno == 0) {
                            $msg_erro = 'Divergência com o valor da fatura.';
                            goto erro_valor;
                        }
                        $this->db->update('cad_faturas', $d_update, array('numero' => $fat_retorno->fatnumero_retorno));
                        $fat_num = $this->get(array('cad_faturas.numero' => $fat_retorno->fatnumero_retorno));
                        $fats_execs['financeiro'][] = $fat_num;
                    } else {
                        $msg_erro = 'Não foi possível encontrar a fatura pelo número.';
                        goto grava_erro;
                    }
                } else {
                    $fat_num = $this->get(array('cad_faturas.numero' => $fat_retorno->fatnumero_retorno));
                    $d_update = array('valor_pago' => $fat_retorno->valorpago_retorno,
                        'data_pagto' => $fat_retorno->datapagto_retorno,
                        'valor_pago_fn' => $fat_retorno->valorpago_retorno,
                        'status_fn' => 'Pago',
                        'retorno_fn' => $fat_retorno->arquivo_retorno,
                        'status' => 1);

                    if (count($fat_num) > 0) {
                        $val_fatura = $fat_num->valor_boleto > $fat_num->valor_total ? $fat_num->valor_boleto : $fat_num->valor_total;
                        $percent_boleto = compara_valor_porcent($val_fatura, $fat_retorno->valorpago_retorno);
                        if ($percent_boleto > 10 || $fat_retorno->valorpago_retorno == 0) {
                            $msg_erro = 'Divergência com o valor da fatura.';
                            goto erro_valor;
                        }
                        $this->db->update('cad_faturas', $d_update, array('numero' => $fat_retorno->fatnumero_retorno));
                        $d_erro_retorno = array('statusexec_retorno' => 'ok',
                            'msgstatusexec_retorno' => '');

                        $this->db->update('retorno_pagamento', $d_erro_retorno,
                            array('arquivo_retorno' => $fat_retorno->arquivo_retorno,
                                'fatnumero_retorno' => $fat_retorno->fatnumero_retorno));

                        $fat_num = $this->get(array('cad_faturas.numero' => $fat_retorno->fatnumero_retorno));
                        $fats_execs['financeiro'][] = $fat_num;
                    } else {
                        erro_valor:
                        $fat_id = $this->get(array('cad_faturas.Id' => $fat_retorno->fatnumero_retorno));
                        if (count($fat_id) > 0) {
                            $val_fatura = $fat_id->valor_boleto > $fat_id->valor_total ? $fat_id->valor_boleto : $fat_id->valor_total;
                            $percent_boleto = compara_valor_porcent($val_fatura, $fat_retorno->valorpago_retorno);

                            // ============== debug comparação fatura ================= //
                            if ($percent_boleto > 10) {
                                $msg_erro = 'Divergência com o valor da fatura.';
                                goto grava_erro;
                            }
                            $this->db->update('cad_faturas', $d_update, array('Id' => $fat_retorno->fatnumero_retorno));
                            $d_erro_retorno = array('statusexec_retorno' => 'ok',
                                'msgstatusexec_retorno' => '');

                            $this->db->update('retorno_pagamento', $d_erro_retorno,
                                array('arquivo_retorno' => $fat_retorno->arquivo_retorno,
                                    'fatnumero_retorno' => $fat_retorno->fatnumero_retorno));

                            $fat_id = $this->get(array('cad_faturas.Id' => $fat_retorno->fatnumero_retorno));
                            $fats_execs['financeiro'][] = $fat_id;

                        } else {
                            $msg_erro = 'Não foi possível encontrar a fatura pelo número.';
                            grava_erro:
                            $d_erro_retorno = array('statusexec_retorno' => 'erro',
                                'msgstatusexec_retorno' => $msg_erro);

                            $this->db->update('retorno_pagamento', $d_erro_retorno,
                                array('arquivo_retorno' => $fat_retorno->arquivo_retorno,
                                    'fatnumero_retorno' => $fat_retorno->fatnumero_retorno));

                            $fats_execs['financeiro'][] = (object)array('numero' => $fat_retorno->fatnumero_retorno,
                                'erro' => $d_erro_retorno['msgstatusexec_retorno'],
                                'valor_boleto' => 0, 'data_pagto' => '0000-00-00',
                                'valor_pago' => 0);
                        }
                    }
                }
            }
        }
        return $fats_execs;
    }

    /*
     * metódo para gerar faturas de todos os contratos
     */
    public function gerar_fatura_automatizado($mes = '') {
        $mes = $mes == '' ? date('m') : $mes;
        $contratos = $this->contrato->listar('');
        if (count($contratos) > 0) {
            foreach ($contratos as $contrato) {
                $this->gerar_fatura_person_contrato($id_contrato, $dt_inicio, $dt_fim, $tipo);
            }
        }
    }

    /*
     * metódo para gerar fatura refatorado2
     * data refatorado 25/10/2013
     */
    public function gerar_fatura_clientes($id_cliente, $mes, $px_data = false) {
        $contratos = $this->contrato->listar("ctr.id_cliente = $id_cliente AND ctr.status IN (1,2)", 0, 999999);
        $ids_envio = array();
        if ($contratos) {
            foreach ($contratos as $contrato) {
                $dia_prestacao = get_data_exploded($contrato->data_prestacao, 2, false, '-');
                $dia_mensalidade = get_data_exploded($contrato->primeira_mensalidade, 2, false, '-');
                $qtd_prestacao = $contrato->prestacoes;
                $ano_atual = date('Y');
                $venc_mensalidade = next_data($ano_atual.'-'.$mes.'-'.$dia_mensalidade);
                // pr( $venc_mensalidade );
                if (!$px_data) $px_data = date('Y-m-d');
                // data de vencimento do novo débito
                $prox_mes = next_data($px_data);
                $diff_datas = diff_entre_datas($contrato->data_prestacao, $prox_mes, 'mes');
                $venc_adesao = next_data($ano_atual.'-'.$mes.'-'.$dia_prestacao);

                if (is_date($venc_adesao) && $venc_adesao == $prox_mes && $contrato->data_prestacao <= $venc_adesao
                    && $diff_datas < $qtd_prestacao) {
                    // verifica se fatura já existe
                    pr('verifica se fatura já existe');die;
                    $fatura = $this->get(array('cad_faturas.data_vencimento' => $venc_adesao,
                        'cad_faturas.id_cliente' => $contrato->id_cliente,
                        'cad_faturas.numero >=' => 30000,
                        'cad_faturas.status !=' => 1, 'cad_faturas.status !=' => 3));

                    if (count($fatura) > 0) {
                        $this->add_adesao($contrato->id, $fatura->Id, $venc_adesao);
                    } else {
                        $d_fatura = array('id_cliente' => $contrato->id_cliente, 'data_emissao' => date('Y-m-d'),
                            'data_vencimento' => $venc_adesao, 'status' => 0);
                        $id_fatura = $this->inserir_fatura($d_fatura);
                        $this->atualizar_fatura($id_fatura, array('numero' => $id_fatura));
                        if($id_fatura) {
                            $this->atualizar_fatura($id_fatura, array('numero' => $id_fatura));
                            $ids_envio[] = $id_fatura;
                            $this->add_adesao($contrato->id, $id_fatura, $venc_adesao);
                            if ($contrato->boleto == 1)
                                $this->add_taxa_boleto($id_fatura);
                        }
                    }
                }

                // gera mensalidade
                if ($venc_mensalidade == $prox_mes && $venc_mensalidade >= $contrato->primeira_mensalidade) {
                    pr('gera mensalidade');die;
                    $fatura_mensal = $this->get(array('cad_faturas.data_vencimento' => $venc_mensalidade,
                        'cad_faturas.id_cliente' => $contrato->id_cliente,
                        'cad_faturas.numero >=' => 30000,
                        'cad_faturas.status !=' => 1, 'cad_faturas.status !=' => 3));
                    // pr( $fatura_mensal );die;

                    if (count($fatura_mensal) > 0) {
                        $this->add_mensalidade($contrato->id, $fatura_mensal->Id);
                    } else {
                        $d_fatura = array('id_cliente' => $contrato->id_cliente, 'data_emissao' => date('Y-m-d'), 'data_vencimento' => $venc_mensalidade, 'status' => 0);
                        $id_fatura = $this->inserir_fatura($d_fatura);
                        if ($id_fatura) {
                            $ids_envio[] = $id_fatura;
                            $this->atualizar_fatura($id_fatura, array('numero' => $id_fatura));
                            $this->add_mensalidade($contrato->id, $id_fatura);
                            if ($contrato->boleto == 1)
                                $this->add_taxa_boleto($id_fatura);
                        }
                    }
                }
                pr('stop');die;
                //cadastra faturas para envio
                if (count($ids_envio) > 0) {
                    $ids_unicos = array_unique($ids_envio);
                    $now = date('Y-m-d H:i:s');
                    foreach ($ids_unicos as $id)
                        $d_envio[] = array('id_fatura' => $id, 'dhcad_envio' => $now);
                    $this->envio_fatura->inserir($d_envio);
                }
            }
        }
    }

    public function add_adesao($id_contrato, $id_fatura, $vencimento) {
        $contrato = $this->contrato->get(array('id' => $id_contrato));
        $hoje = date('Y-m-d');
        $d_item = array();
        if ($contrato) {
            $qtd_prestacao = $contrato->prestacoes;
            $data_prestacao = $contrato->data_prestacao;
            if ($data_prestacao <= $vencimento) {
                $diff_datas = diff_entre_datas($data_prestacao, $vencimento, 'mes');
                //verifica se já existe a adesão deste contrato na fatura
                $adesao = $this->get_items(array('id_fatura' => $id_fatura, 'tipo_item' => 'adesao',
                    'relid_item' => $contrato->id));
                if ($diff_datas < $qtd_prestacao && count($adesao) == 0) {
                    $descricao = '[Contrato '.$contrato->id.'] Valor referente a adesão '.($diff_datas+1).
                        ' / '.$contrato->prestacoes;

                    $d_item = array('id_cliente' => $contrato->id_cliente, 'id_fatura' => $id_fatura,
                        'tipo_item' => 'adesao',
                        'descricao_item' => $descricao, 'relid_item' => $contrato->id,
                        'valor_item' => $contrato->valor_prestacao,
                        'vencimento_item' => '');
                    //insere o item na fatura
                    $this->inserir_item_fatura($d_item);
                    // atualiza o valor da fatura
                    $total_fatura = $this->total_fatura($id_fatura);
                    $this->atualizar_fatura($id_fatura, array('valor_total' => $total_fatura));
                }
            }
        }
        return true;
    }

    public function add_taxa_boleto($id_fatura, $valor_taxa = 4.5) {
        if (!$this->validar_taxa($id_fatura, 'boleto')) {
            $fatura = $this->get(array('cad_faturas.Id' => $id_fatura));
            if (count($fatura) > 0) {
                $d_item = array('id_cliente' => $fatura->id_cliente, 'id_fatura' => $fatura->Id,
                    'tipo_item' => '',
                    'descricao_item' => 'Taxa Boleto', 'valor_item' => $valor_taxa,
                    'taxa_item' => 'sim', 'tipotaxa_item' => 'boleto',
                    'vencimento_item' => '');

                $this->inserir_item_fatura($d_item);
                // atualiza o valor da fatura
                $total_fatura = $this->total_fatura($id_fatura);
                $this->atualizar_fatura($id_fatura, array('valor_total' => $total_fatura));
            }
        }
    }

    public function add_mensalidade($id_contrato, $id_fatura) {
        $contrato = $this->contrato->get(array('id' => $id_contrato));
        $hoje = date('Y-m-d');
        if ($contrato) {
            //verifica se já existe a mensalidade deste contrato na fatura
            $mensalidade = $this->get_items(array('id_fatura' => $id_fatura, 'tipo_item' => 'mensalidade', 'relid_item' => $contrato->id));
            if (count($mensalidade) == 0) {
                if ( $contrato->tipo_proposta == 1 )
                    $descricao = '[Contrato '.$contrato->id.'] Locação de SIM CARD {'.$contrato->quantidade_veiculos.' unidades}';
                else
                    $descricao = '[Contrato '.$contrato->id.'] Locação de módulos para rastreamento veicular {'.$contrato->quantidade_veiculos.' unidades}';
                $valor_item = $contrato->quantidade_veiculos * $contrato->valor_mensal;
                $d_item = array('id_cliente' => $contrato->id_cliente, 'id_fatura' => $id_fatura,
                    'tipo_item' => 'mensalidade',
                    'descricao_item' => $descricao, 'relid_item' => $contrato->id,
                    'valor_item' => $valor_item,
                    'vencimento_item' => '');
                //insere o item na fatura
                $this->inserir_item_fatura($d_item);
                // atualiza o valor da fatura
                $total_fatura = $this->total_fatura($id_fatura);
                $this->atualizar_fatura($id_fatura, array('valor_total' => $total_fatura));
            }
        }
        return true;
    }

    public function get_ultimo() {
        $query = $this->db->select('*')
            ->where('id_cliente', '2089')
            ->order_by('Id', 'DESC')
            ->limit(1)
            ->get('cad_faturas');
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fatura);

            return $fatura;
        }
        return array();
    }

    public function validar_permissionario($prefixo, $cpf) {
        if ($cpf{0} == '0' && $cpf{1} == '0') {
            $cpf = substr($cpf, 1);
            $cpf = substr($cpf, 1);
        } elseif($cpf{0} == '0') {
            $cpf = substr($cpf, 1);
        }
        $query = $this->db->select('*')
            ->where('prefixo', $prefixo)
            ->where('cnpj', $cpf)
            ->get('contratos_eptc');
        if($query->num_rows() > 0)
            return true;
        return false;
    }

    public function calcular_porcentagens() {
        $this->load->model('relatorio');
        $dados = array();
        $data_atual = date('Y-m-d');
        //mes atual
        $total_fatura = 0;
        $total_pago = 0;
        $aux = date("d") - 1;
        $data_in = date("Y-m-d", strtotime("-".$aux." day"));
        $faturas = $this->relatorio->fatura($data_in, $data_atual, '', '', 'todos');

        foreach ($faturas as $fatura) {
            $total_fatura += $fatura->valor_total;
            $total_pago += $fatura->valor_pago;
        }
        $dados['mes_atual'] = 100 - (($total_pago * 100) / $total_fatura);
        //mes anterior
        $total_fatura = 0;
        $total_pago = 0;
        $data_in = date('Y-m-d',mktime(0, 0, 0, date('m')-1 , 1 , date('Y')));
        $data_end = date('Y-m-d',mktime(23, 59, 59, date('m'), date('d')-date('j'), date('Y')));
        $faturas = $this->relatorio->fatura($data_in, $data_end, null, null, 'todos');
        foreach ($faturas as $fatura) {
            $total_fatura += $fatura->valor_total;
            $total_pago += $fatura->valor_pago;
        }
        $dados['mes_anterior'] = 100 - (($total_pago * 100) / $total_fatura);
        //Ultimos 12 meses
        $total_fatura = 0;
        $total_pago = 0;
        $data_in = date( "Y-m-d", strtotime("-12 month"));
        $faturas = $this->relatorio->fatura($data_in, $data_atual, '', '', 'todos');
        foreach ($faturas as $fatura) {
            $total_fatura += $fatura->valor_total;
            $total_pago += $fatura->valor_pago;
        }
        $dados['doze_meses'] = 100 - (($total_pago * 100) / $total_fatura);
        return $dados;
    }

    public function salvar_porcentagens($porcentagens) {
        $antigas = $this->get_porcentagens();
        if($antigas->mes_atual != strval($porcentagens['mes_atual']) OR $antigas->mes_anterior != strval($porcentagens['mes_anterior']) OR $antigas->doze_meses != strval($porcentagens['doze_meses'])) {
            $porcentagens['data'] = date('Y-m-d H:i:s');
            
            return $this->db->insert('estatisticas_faturas', $porcentagens);
        }
    }

    public function get_porcentagens() {
        return $this->db->order_by('data', 'DESC')
            ->get('estatisticas_faturas')->row_array();
    }

    /*
    * gerar fatura consumo
    */
    public function gerar_fatura_consumo($id_cliente, $mes, $px_data = false) {
        $contratos = $this->contrato->listar("ctr.id_cliente = $id_cliente AND ctr.status IN (1,2)", 0, 999999);
        if ($contratos) {
            foreach ($contratos as $contrato) {
                $ids_envio = array();
                $dia_prestacao = get_data_exploded($contrato->data_prestacao, 2, false, '-');
                $dia_mensalidade = get_data_exploded($contrato->primeira_mensalidade, 2, false, '-');
                $qtd_prestacao = $contrato->prestacoes;
                $ano_atual = date('Y');
                $venc_mensalidade = date("Y-m-".$contrato->vencimento);
                // pr( $venc_mensalidade );
                if (!$px_data)
                    $px_data = date('Y-m-d');
                //data de vencimento do novo débito

                $prox_mes = next_data($px_data);
                $hoje = date('Y-m-d');
                $dia_hoje = date('j');

                if ($dia_hoje > $contrato->vencimento)
                    $venc_mensalidade = next_data($venc_mensalidade);
                $diff_datas = diff_entre_datas($hoje, $venc_mensalidade, 'dias');
                $venc_adesao = next_data($ano_atual.'-'.$mes.'-'.$dia_prestacao);

                if ($diff_datas == 10) {
                    // verifica se fatura já existe
                    $fatura = $this->get(array('cad_faturas.data_vencimento' => $venc_mensalidade,
                        'cad_faturas.id_cliente' => $contrato->id_cliente,
                        'cad_faturas.numero >=' => 30000,
                        'cad_faturas.status !=' => 1, 'cad_faturas.status !=' => 3));
                    if (count($fatura) > 0) {
                        $this->add_consumo($contrato->id, $fatura->Id);
                    } else {
                        $d_fatura = array('id_cliente' => $contrato->id_cliente, 'data_emissao' => date('Y-m-d'),
                            'data_vencimento' => $venc_mensalidade, 'status' => 0);

                        $id_fatura = $this->inserir_fatura($d_fatura);
                        $this->atualizar_fatura($id_fatura, array('numero' => $id_fatura));
                        if($id_fatura) {
                            $this->atualizar_fatura($id_fatura, array('numero' => $id_fatura));
                            $this->add_consumo($contrato->id, $id_fatura);
                            $ids_envio[] = $id_fatura;
                            if ($contrato->boleto == 1)
                                $this->add_taxa_boleto($id_fatura);
                        }
                    }
                }
                //cadastra faturas para envio
                if (count($ids_envio) > 0){
                    $ids_unicos = array_unique($ids_envio);
                    $now = date('Y-m-d H:i:s');
                    foreach ($ids_unicos as $id)
                        $d_envio[] = array('id_fatura' => $id, 'dhcad_envio' => $now);
                    $this->envio_fatura->inserir($d_envio);
                }
            }
        }
    }

    public function add_consumo($id_contrato, $id_fatura) {
        $hoje = date('Y-m-d');
        $contrato = $this->contrato->get(array('id' => $id_contrato));
        if ($contrato) {
            $dt_ini = date('Y-m-').$contrato->vencimento;
            $inicio_periodo = date('Y-m-d', strtotime($dt_ini.' - 30 days'));
            $consumo = $this->db->query("SELECT (SELECT COUNT(DISTINCT(placa))
													FROM showtecsystem.fatura_consumo
													WHERE id_contrato = {$id_contrato}
													AND DATE(data) BETWEEN '{$inicio_periodo}' AND '{$hoje}') veiculos, id_contrato, SUM(valor) valor
										FROM showtecsystem.fatura_consumo
										WHERE id_contrato = {$id_contrato}
										AND DATE(data) BETWEEN '{$inicio_periodo}' AND '{$hoje}'");
            //verifica se já existe a mensalidade deste contrato na fatura
            $mensalidade = $this->get_items(array('id_fatura' => $id_fatura, 'tipo_item' => 'mensalidade', 'relid_item' => $contrato->id));
            if (count($mensalidade) == 0 && $consumo->result()) {
                if ( $contrato->tipo_proposta == 1 )
                    $descricao = '[Contrato '.$contrato->id.'] Locação de SIM CARD {'.$consumo->row(0)->veiculos.' unidades}';
                else
                    $descricao = '[Contrato '.$contrato->id.'] Locação de módulos para rastreamento veicular {'.$consumo->row(0)->veiculos.' unidades}';
                $valor_item = round($consumo->row(0)->valor, 2);
                $d_item = array('id_cliente' => $contrato->id_cliente, 'id_fatura' => $id_fatura,
                    'tipo_item' => 'mensalidade',
                    'descricao_item' => $descricao, 'relid_item' => $contrato->id,
                    'valor_item' => $valor_item,
                    'vencimento_item' => '');
                //insere o item na fatura
                $this->inserir_item_fatura($d_item);
                // atualiza o valor da fatura
                $total_fatura = $this->total_fatura($id_fatura);
                $this->atualizar_fatura($id_fatura, array('valor_total' => $total_fatura));
            }
        }
        return true;
    }

    public function salvar_anexo($fatura_id, $nome_arquivo, $nfe = false) {
        if ($nfe) {
            $pasta = 'nfe';
            $descricao = "NF-e";
        } else {
            $pasta = "anexo_fatura";
            $descricao = "Fatura Nº ".$fatura_id;
        }

        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => $fatura_id

        );
        $this->db->insert('showtecsystem.arquivos', $dados);
        return true;
    }

    public function count($fatura_id) {
        $this->db->where('ndoc', $fatura_id);
        return $this->db->get('showtecsystem.arquivos')->num_rows();
    }

    public function get_arquivos($fatura_id) {
        $this->db->select('file, pasta');
        $this->db->where('ndoc', $fatura_id);
        $this->db->order_by('id', 'desc');
        return $this->db->get('showtecsystem.arquivos')->result();
    }

    public function saveComents($data) {
        $this->db->insert('showtecsystem.comments_fatura', $data);
    }

    public function get_comments($fatura_id) {
        $this->statusLeituraComments($fatura_id);
        $this->db->where('id_fatura', $fatura_id)->order_by('id', 'desc')->limit(10);
        return $this->db->get('showtecsystem.comments_fatura')->result();
    }

    private function statusLeituraComments($id_fatura)
    {
        return $this->db->where('id_fatura', $id_fatura)->update('comments_fatura', array('status' => 1));
    }

    public function get_dados_contrato($dias, $id_cliente, $id_contrato) {
        $dados['fatura'] = $this->db->select('c.id as id_contrato, c.id_cliente, c.quantidade_veiculos, c.data_prestacao, c.data_cadastro, COUNT(car.placa) as carros_monitorados, c.valor_mensal, (COUNT(car.status) * c.valor_mensal) as valor_total, (c.valor_mensal / '.$dias.') as valor_por_veiculo, ((c.valor_mensal / '.$dias.') * COUNT(car.placa)) as valor_consumo_dia, c.vencimento as data_vencimento')
            ->where('car.id_contrato', $id_contrato)
            ->where('c.id_cliente', $id_cliente)
            ->where('car.status', 'ativo')
            ->join('showtecsystem.contratos_veiculos as car', 'c.id = car.id_contrato', 'right')
            ->join('showtecsystem.cad_clientes as cli', 'c.id_cliente = cli.id', 'right')
            ->get('showtecsystem.contratos as c')
            ->result();

        $placas['placas_ativas'] = serialize($this->db->select('placa')
            ->where('id_contrato', $id_contrato)
            ->where('status', 'ativo')
            ->get('showtecsystem.contratos_veiculos ')->result());

        $dados = array_merge($dados, $placas);
        return $dados;
    }

    public function get_dados_consumo($inicioCobranca, $id_contrato, $id_cliente){
        $query = $this->db
            ->where('con.id_contrato', $id_contrato)
            ->where('con.id_cliente', $id_cliente)
            ->where('con.data_save >=', $inicioCobranca)
            ->where('con.data_vencimento', 10)
            ->get('showtecsystem.consumo_fatura as con')->result();
        return $query;
    }

    public function get_number_contrato($cliente_id) {
        return $this->db->select('contratos.id')->where('contratos.id_cliente', $cliente_id)->get('showtecsystem.contratos')->result();
    }

    public function set_consumo_fatura($dados, $placas) {
        $this->db->insert('showtecsystem.consumo_fatura', $dados['fatura'][0]);
        $id = $this->db->insert_id();
        if ($this->db->insert_id()) {
            $sql =  "UPDATE showtecsystem.consumo_fatura
                        SET placas_ativas = '$placas'
                        WHERE id = $id";
            $this->db->query($sql);
        }
    }

// +++++++++++++++++++++++ jerônimo gabriel init ++++++++++++++++++++++++++++
    // métodos desenvolvidos

    function updateForTaxe($taxes, $cliente_id) {
        $this->db->where('id_cliente', $cliente_id)->update('showtecsystem.cad_faturas', $taxes);
    }

    function arrears() {
        $sql = "SELECT
					DATEDIFF(NOW(), f.data_vencimento) AS diff,
					f.data_vencimento, c.nome
				FROM
					showtecsystem.cad_faturas AS f
						INNER JOIN
					showtecsystem.cad_clientes AS c ON f.id_cliente = c.id
				ORDER BY diff DESC;";
        return json_encode( $this->db->query($sql)->result() );
    }

    function invoicesPaidOnTheDay() {
        $sql = "SELECT
					f.*
				FROM
					showtecsystem.cad_faturas AS f
						INNER JOIN
					showtecsystem.cad_clientes AS c ON f.id_cliente = c.id
				WHERE
					f.data_pagto = NOW();";
        return json_encode( $this->db->query($sql)->result() );
    }

    function invoicesPaidInTheMonth() {
        $sql = "SELECT
					f.*
				FROM
					showtecsystem.cad_faturas AS f
						INNER JOIN
					showtecsystem.cad_clientes AS c ON f.id_cliente = c.id
				WHERE
					MONTH(f.data_pagto) = MONTH(NOW()) AND YEAR(f.data_pagto) = YEAR(NOW());";
        return json_encode( $this->db->query($sql)->result() );
    }

    function openDayInvoices() {
        $sql = "SELECT
					f.*
				FROM
					showtecsystem.cad_faturas AS f
						INNER JOIN
					showtecsystem.cad_clientes AS c ON f.id_cliente = c.id
				WHERE
					f.data_pagto = NOW() AND f.status = 1;";
        return json_encode( $this->db->query($sql)->result() );
    }

    function openWeekInvoices() {
        $week = date('w', strtotime($data));
        $data = date('Y-m-d');
        $init = null;
        $end  = null;
        switch ($week) {
            case 0:
                $init = date('Y-m-d', strtotime("$data")) . "\n";
                $end  = date('Y-m-d', strtotime("$data + 6 DAY")) . "\n";
                break;
            case 1:
                $init = date('Y-m-d', strtotime("$data - 1 DAY")) . "\n";
                $end  = date('Y-m-d', strtotime("$data + 5 DAY")) . "\n";
                break;
            case 2:
                $init = date('Y-m-d', strtotime("$data - 2 DAY")) . "\n";
                $end  = date('Y-m-d', strtotime("$data + 4 DAY")) . "\n";
                break;
            case 3:
                $init = date('Y-m-d', strtotime("$data - 3 DAY")) . "\n";
                $end  = date('Y-m-d', strtotime("$data + 3 DAY")) . "\n";
                break;
            case 4:
                $init = date('Y-m-d', strtotime("$data - 4 DAY")) . "\n";
                $end  = date('Y-m-d', strtotime("$data + 2 DAY")) . "\n";
                break;
            case 5:
                $init = date('Y-m-d', strtotime("$data - 5 DAY")) . "\n";
                $end  = date('Y-m-d', strtotime("$data + 1 DAY")) . "\n";
                break;
            case 6:
                $init = date('Y-m-d', strtotime("$data - 6 DAY")) . "\n";
                $end  = date('Y-m-d', strtotime("$data")) . "\n";
                break;
        }
        $sql = "SELECT
					f.*
				FROM
					showtecsystem.cad_faturas AS f
						INNER JOIN
					showtecsystem.cad_clientes AS c ON f.id_cliente = c.id
				WHERE
					f.sdata_pagto >= {$init} AND f.data_pagto <= {$end} AND f.status = 1;";
        return json_encode( $this->db->query($sql)->result() );
    }

    function openMonthInvoices() {
        $sql = "SELECT
					f.*
				FROM
					showtecsystem.cad_faturas AS f
						INNER JOIN
					showtecsystem.cad_clientes AS c ON f.id_cliente = c.id
				WHERE
					MONTH(f.data_pagto) = MONTH(NOW()) AND f.status = 1;";
        return json_encode( $this->db->query($sql)->result() );
    }

    private function existFatura($id_client, $date) {
        $sql = "SELECT
					Id
				FROM
					showtecsystem.cad_faturas
				WHERE
					id_cliente = {$id_client} AND status <> 1
						AND status <> 3
						AND (data_vencimento = '{$date}' OR boleto_vencimento = '{$date}');";
        return $this->db->query($sql)->num_rows();
    }

    private function sendFatura($id_client, $id_contrato, $id_fatura) {
        $this->load->library('parser');
        $sql = "SELECT
					nome,
					email,
					email2
				FROM
					showtecsystem.cad_clientes
				WHERE
					id = {$id_client};";
        $cliente = $this->db->query($sql)->result();
        $data = array(
            'cliente' => $cliente[0]->nome,
            'link_fatura' => 'https://gestor.showtecnologia.com/sistema/newapp/index.php/cliente/faturas/view_email/'.base64_encode($id_fatura)
        );
        $mensagem = $this->parser->parse('template/emails/fatura', $data, true);
        $dados['faturas'] = $this->listar("cad_faturas.Id = {$id_fatura}", 0, 1, 'data_vencimento', 'ASC');
        $boleto = $this->load->view('faturas/imprimir_fatura', $dados, TRUE);

        $envio = $this->sender->enviar_email('financeiro@showtecnologia.com', 'Show Tecnologia', ($cliente[0]->email ? $cliente[0]->email : $cliente[0]->email2),
            'Fatura para Pagamento #'.$id_fatura, $mensagem, false, false, false);
        if ($envio) {
            $now = date('Y-m-d H:i:s');
            $this->fatura->atualizar_fatura($id_fatura, array('status' => 0));
        }
    }

    function gerar($id_client) {
        $sql = "SELECT
					id,
					tipo_proposta AS tipo,
					quantidade_veiculos AS qtdCar,
					valor_mensal AS valor,
					primeira_mensalidade AS mensalidade,
					valor_prestacao AS prestacao,
					boleto
				FROM
					showtecsystem.contratos
				WHERE
					id_cliente = {$id_client}
						AND status IN (1 , 2)
						AND primeira_mensalidade <> '0000-00-00'";
        $contratos = $this->db->query($sql)->result();
        foreach ($contratos as $key => $contrato) {
            $date = date('Y-m-d', strtotime(date('Y-m') . '-' . substr($contrato->mensalidade, 8)));
            $date = date('Y-m-d', strtotime("$date + 1 MONTH"));
            if ($this->existFatura($id_client, $date)) {
                continue;
            } else {
                if ($contrato->boleto == 1) {
                    $tributos = $this->db->select('IRPJ, Cont_Social, PIS, COFINS, ISS')->get_where('showtecsystem.cad_clientes', array('id' => $id_client))->row();
                    $total = $contrato->valor * $contrato->qtdCar;
                    $d = date('Y-m-d');
                    $sql = "INSERT INTO
								showtecsystem.cad_faturas
								(
									id_contrato,
									id_cliente,
									data_vencimento,
									boleto_vencimento,
									valor_boleto,
									quantidade,
									valor_unitario,
									valor_total,
									total_fatura,
									taxa_boleto,
									data_emissao,
									status,
									IRJP,
									Cont_Social,
									PIS,
									COFINS,
									ISS
								)
							VALUES
								(
									{$contrato->id},
									{$id_client},
									'{$date}',
									'{$date}',
									$total,
									{$contrato->qtdCar},
									{$contrato->valor},
									$total,
									$total,
									4.50,
									'{$d}',
									0,
									$tributos->IRPJ,
									$tributos->Cont_Social,
									$tributos->PIS,
									$tributos->COFINS,
									$tributos->ISS
								);";
                    $this->db->query($sql);
                    $id_fatura = $this->db->insert_id();
                    $sql = "UPDATE showtecsystem.cad_faturas SET numero = {$id_fatura} WHERE Id = {$id_fatura};";
                    $this->db->query($sql);
                    $this->add_mensalidade($contrato->id, $id_fatura);
                    $this->add_taxa_boleto($id_fatura);
                    $this->add_adesao($contrato->id, $id_fatura, $date);
                    $this->sendFatura($id_client, $contrato->id, $id_fatura);
                } else {
                    // gerar errors para faturas
                }
            }
        }
    }

    function gerarConsumo($id_client, $user) {
        $sql = "SELECT
					id,
					quantidade_veiculos AS qtdCar
				FROM
					showtecsystem.contratos
				WHERE
					id_cliente = {$id_client}
						AND status IN (1 , 2)
						AND primeira_mensalidade <> '0000-00-00';";
        $contratos = $this->db->query($sql)->result();
//        pr($contratos);
        $day = date('d');
        foreach ($contratos as $key => $contrato) {
            $sql = "SELECT
					id_contrato,
					data_vencimento,
					quantidade_veiculos AS qtdCar,
					valor_por_veiculo AS valorCar,
					valor_consumo_dia AS consumo,
					valor_total AS total,
					data_save,
					placas_ativas
				FROM
					showtecsystem.consumo_fatura
				WHERE
					id_cliente = {$id_client}
						AND id_contrato = {$contrato->id}
						AND SUBSTRING(data_save, 1, 10) >= SUBDATE(CURDATE(), INTERVAL 1 MONTH)
						AND SUBSTRING(data_save, 1, 10) <= CURDATE()
						AND data_vencimento = {$day};";
            $infos = $this->db->query($sql)->result();

            pr( $infos );
            $date = date('Y-m-d');
            $date = date('Y-m-d', strtotime("$date + 5 DAY"));

            if (!$infos) continue;
            if ($this->existFatura($id_client, date('Y-m-d'))) continue;

            $sql = "INSERT INTO
						showtecsystem.cad_faturas
						(
							id_contrato,
							id_cliente,
							data_vencimento,
							boleto_vencimento,
							quantidade,
							taxa_boleto,
							data_emissao,
							status,
							generator
						)
					VALUES
						(
							{$contrato->id},
							{$id_client},
							'{$date}',
							'{$date}',
							{$contrato->qtdCar},
							0.0,
							'{$date}',
							0,
							1
						);";
            $this->db->query($sql);
            $id_fatura = $this->db->insert_id();
            $sum = 0;
            $descricao = 'teste';

            foreach ($infos as $key => $info) {
                $sum += $info->consumo;
                $placasAtivas = unserialize($info->placas_ativas);
                $descricao = "<b>FATURA GERADA POR DISPONIBILIDADE</b>";

                $sql = "INSERT INTO
							showtecsystem.fatura_itens
							(
								id_cliente,
								id_fatura,
								relid_item,
								descricao_item,
								valor_item,
								tipo_item
							)
						VALUES
							(
								{$id_client},
								{$id_fatura},
								{$contrato->id},
								'{$descricao}',
								{$info->consumo},
								'mensalidade'
							);";
                $this->db->query($sql);
            }
            pr( $sum );
            $sql = "UPDATE
						showtecsystem.cad_faturas
					SET
						numero = {$id_fatura},
						valor_unitario = {$info->valorCar},
						valor_boleto = {$info->total},
						total_fatura = {$info->total},
						valor_total  = {$info->total}
					WHERE
						Id = {$id_fatura};";
            $this->db->query($sql);
            pr( $sql );die;
        }
    }

    /*
    * BUSCA CONTRATOS COM VENCIMENTO NO DIA SEGUINTE - DEV SAULO MENDES
    */
    public function getFaturas_by_dia($proporcional = false) {
        # 1 = OS, 2 = ATIVOS, 4 = TESTE, 5 = BLOQUEADO
        $status = array(1, 2, 4, 5);
        $dias = "";
        if($proporcional){
            $this->db->where('consumo_fatura', 1); //Todos que pagam proporcional
            $dias = "30";
        }
        else{
            $this->db->where('consumo_fatura', 0); //Remove todos que pagam proporcional
            $dias = "30";
        }
        $this->db->select('c.*,cc.pais,cc.ISS,cc.IRPJ,cc.Cont_Social,cc.PIS,cc.COFINS');
        $this->db->where_in('c.status', $status);
        $this->db->join('showtecsystem.cad_clientes cc', 'cc.id = c.id_cliente');
        $this->db->where('vencimento', date('d', strtotime("+".$dias." days", strtotime(date('Y-m-d')))));
        $query = $this->db->get('showtecsystem.contratos c')->result();
        $i = 0;
        foreach ($query as $contrato) {
            $data_inicio = new DateTime($contrato->primeira_mensalidade);
            $data_fim = new DateTime(date('Y-m-d', strtotime("+".$dias." days", strtotime(date('Y-m-d')))));

            $diferenca = $data_inicio->diff($data_fim);

            if ($diferenca->y > 0) {
                $meses_diff = ($diferenca->y * 12) + $diferenca->m;
            } else {
                $meses_diff = $diferenca->m;
            }

            if ($this->existFatura($contrato->id_cliente, date('Y-m-d', strtotime("+".$dias." days", strtotime(date('Y-m-d')))))) {//|| $contrato->boleto == 0, atributo boleto é referente a taxa de boleto
                unset($query[$i]);
            }

            $i++;
        }

        return $query;
    }

    /*
     * INSERI O NUMERO DA FATURA NO CAD_FATURAS
     * $id_fatura: NUMERO DA FATURA
     */
    public function gravaNumFatura($id_fatura) {
        return $this->db->update(
            'showtecsystem.cad_faturas',
            array('numero' => $id_fatura),
            array('Id' => $id_fatura)
        );
    }

    /*
     * GERA FATURAS DOS PROXIMOS 5 DIAS
     * $dados: DADOS DA FATURA || $tipo: TIPO DO CONTRATO (CHIP OU RASTREAMENTO)
     */
    public function gerarFaturaDia($dados, $tipo, $descricao=false) {
        // VERIFICA SE EXISTE FATURA DE OUTRO CONTRATO DO CLIENTE NO MESMO DIA
        $data = date('Y-m-d', strtotime("+30 days", strtotime(date('Y-m-d'))));
        $this->db->select('Id');
        $this->db->where('status !=', '3');
        $this->db->where('data_vencimento', $data);
        $this->db->where('id_cliente', $dados['id_cliente']);
        $f_anterior = $this->db->get('showtecsystem.cad_faturas')->result();

        if ($f_anterior && !empty($f_anterior)) {
            $id_insert = $f_anterior[0]->Id;
        } else {
            $this->db->insert('showtecsystem.cad_faturas', $dados);
            $id_insert = $this->db->insert_id();
        }

        // VERIFICA TIPO FATURA
        if ($tipo != 1) {
            // CRIA ARRAY DADOS RASTREADOR
            $dados_itens = array(
                'id_cliente' => $dados['id_cliente'],
                'id_fatura' => $id_insert,
                'tipo_item' => 'mensalidade',
                'descricao_item' => $descricao, //"[Contrato ".$dados['id_contrato']."] Locação de módulos para rastreamento veicular {".$dados['quantidade']." unidades}",
                'relid_item' => 0,
                'valor_item' => $dados['total_fatura'],
                'taxa_item' => 'nao',
                'tipotaxa_item' => 'boleto',
                'vencimento_item' => $dados['data_vencimento']
            );
        } else {
            // CRIA ARRAY DADOS CHIP
            $dados_itens = array(
                'id_cliente' => $dados['id_cliente'],
                'id_fatura' => $id_insert,
                'tipo_item' => 'mensalidade',
                'descricao_item' => "[Contrato ".$dados['id_contrato']."] Locação de SIM CARD {".$dados['quantidade']." chips}",
                'relid_item' => 0,
                'valor_item' => $dados['total_fatura'],
                'taxa_item' => 'nao',
                'tipotaxa_item' => 'boleto',
                'vencimento_item' => $dados['data_vencimento']
            );
        }

        // VERIFICA SE JÁ EXISTE FATURA_ITEM
        $verifica = $this->db->get_where('showtecsystem.fatura_itens', $dados_itens)->result();
        if (!$verifica && !empty($dados_itens)&&$descricao) {
            $this->db->insert('showtecsystem.fatura_itens', $dados_itens);
            $val_fatura = $this->total_fatura($id_insert);
            $d_fatura_atualizado = array('descricao' => '', 'id_contrato' => '', 'valor_total' => $val_fatura);
            if(!$this->atualizar_fatura($id_insert, $d_fatura_atualizado)){
                echo ('Não foi possível atualizar os valores da fatura.');
            }
        }

        return $id_insert;
    }

    /*
     * GERA TAXA DE BOLETO DA FATURA
     * $id_cliente: ID DO CLIENTE || $id_fatura: NUMERO DA FATURA || $vecimento: DATA DE VENCIMENTO DO BOLETO
     */
    public function gerarTaxaBoleto($id_cliente, $id_fatura, $vencimento) {
        $where = array('id_fatura' => $id_cliente, 'descricao_item' => 'taxa boleto', 'vencimento_item' => $vencimento);
        // VERIFICA SE JÁ EXISTE A TAXA DE BOLETO PARA A FATURA
        $this->db->where($where);
        $result = $this->db->count_all_results('showtecsystem.fatura_itens');

        if ($result == 0) {
            $dados = array(
                'id_cliente' => $id_cliente,
                'id_fatura' => $id_fatura,
                'tipo_item' => 'taxa',
                'descricao_item' => 'Taxa Boleto',
                'valor_item' => 4.50,
                'taxa_item' => 'sim',
                'tipotaxa_item' => 'boleto',
                'vencimento_item' => $vencimento
            );
            return $this->db->insert('showtecsystem.fatura_itens', $dados);
        }

        return FALSE;
    }

    /*
     * BUSCA SENHA USUARIO MASTER (P/ CANCELAMENTO DE FATURAS)
     */
    public function senhaExclusaoFatura() {
        $this->db->select('senha');
        $query = $this->db->get_where('showtecsystem.usuario', array('login' => 'eduardo@showtecnologia.com'))->row();
        if ($query) {
            return $query->senha;
        } else {
            return FALSE;
        }
    }

    /*
     * BUSCA SENHA USUARIO MASTER (P/ CANCELAMENTO DE FATURAS)
     */
    public function senhaExclusaoFatura2() {
        $this->db->select('senha');
        $query = $this->db->get_where('showtecsystem.usuario', array('login' => 'cristiane@showtecnologia.com'))->row();
        if ($query) {
            return $query->senha;
        } else {
            return FALSE;
        }
    }

    /*
     * BUSCA SENHA USUARIO MASTER (P/ À CANCELAR FATURAS)
     */
    public function senha_a_cancelar_fatura() {
        $this->db->select('senha');
        $query = $this->db->get_where('showtecsystem.usuario', array('login' => 'lennon@showtecnologia.com'))->row();
        if ($query) {
            return $query->senha;
        } else {
            return FALSE;
        }
    }

    /**BUSCA AS FATURAS CUJO CONTRATOS SÃO POR DISPONIBILIDADE**/
    public function get_fatura_by_contrato() {
        return $this->db->select('f.id, f.id_cliente, f.data_vencimento, c.id as id_contrato')
            ->where(array('f.status !=' => 1, 'c.consumo_fatura' => 1))
            ->order_by('f.id', 'DESC')
            ->join('showtecsystem.contratos as c', 'f.id_contrato = c.id', 'inner')
            ->get('showtecsystem.cad_faturas as f')->result();
    }

    /**GERA UM RESUMO DA FATURA GERADA POR DISPONIBILIDADE**/
    public function getResumoConsumo($id_contrato, $lastDayConsumo) {
        $dados = [];
        $lastMonth = date('Y-m-d', strtotime("$lastDayConsumo -1 MONTH")) . " 00:00:00";
        $consumo = $this->db->select('*')
            ->where("data_save BETWEEN '$lastMonth' AND '$lastDayConsumo'")
            ->where('id_contrato', $id_contrato)
            ->get('showtecsystem.consumo_fatura')->result();
        if (count($consumo) > 0) {
            foreach ($consumo as $item) {
                $dados[] = array(
                    'data' => data_for_humans(date('Y-m-d', strtotime($item->data_save))),
                    'veic_monitorados' => $item->carros_monitorados,
                    'valorDia' => $item->valor_consumo_dia,
                    'placas_ativas' => json_encode(unserialize($item->placas_ativas))
                );
            }
        } else {
            $dados = [];
        }
        return $dados;
    }

    function insertChaveNfe($id_rps, $chave, $num_nfe)
    {
        $this->db->where('rps', $id_rps)
            ->update('showtecsystem.cad_faturas', array('status_nfe' => 'Gerada', 'chave_nfe' => $chave, 'nota_fiscal' => $num_nfe));
    }

    function getEndRps()
    {
        $resultado = $this->db->select('rps')->where('rps !=', '')->order_by('rps', "desc")->limit(1)->get('cad_faturas')->row();

        if ($resultado)
            return $resultado->rps;
        else
            return 0;
    }

    function load_rps()
    {
        return $this->db->order_by('id', "DESC")->get('systems.cad_rps')->result();
    }

    function cad_rps($name_arq, $email, $empresa)
    {
        $dados = array(
            'nome_arquivo' => $name_arq,
            'data' => date('Y-m-d H:i:s'),
            'user' => $email,
            'empresa' => $empresa
        );
        $this->db->insert('systems.cad_rps', $dados);

        $this->db->where('status_nfe', 'Pendente');
        return $this->db->update('showtecsystem.cad_faturas', array('status_nfe' => 'Enviada'));
    }

    function updateStatusNfe($id_fatura, $status, $rps)
    {
        $this->db->where('Id', $id_fatura);
        return $this->db->update('showtecsystem.cad_faturas', array('status_nfe' => $status, 'rps' => $rps));
    }

    function transfereNfe($id_fatura)
    {
        $FILE = fopen(base_url("media/nfe/importacao_nfes.xml"), 'r');

        $connection = ftp_connect('ftp-arquivos.showtecnologia.com');
        if (!$connection) echo json_encode(array('mensagem' => 'ERROR: connection fail!'));
            $log = ftp_login($connection, 'show', 'show2592');
        if (!$log) echo json_encode(array('mensagem' => 'ERROR: autenticar fail!'));
            ftp_pasv($connection, true);
                $send = ftp_fput($connection, "particao_ftp/uploads/nfes/Nfe_{$id_fatura}.xml", $FILE, FTP_ASCII);
            ftp_close($connection);

        if ($send) {
            $this->salvar_anexo($id_fatura, "Nfe_{$id_fatura}.xml", true);

            return $this->db->where('Id', $id_fatura)
                ->update('showtecsystem.cad_faturas', array('status_nfe' => 'Gerada'));
            //unlink("media/nfe/{$id_fatura}.xml");
        }

    }
    public function recuperaQtdModulos(){
        return $this->db->query("SELECT * FROM showtecsystem.estatisticas_faturas ORDER BY data DESC LIMIT 24")->result();
    }

    public function recuperaDadosRelatorio($inicio = false, $fim = false){
        return $this->db->query("SELECT * FROM showtecsystem.estatisticas_faturas WHERE data BETWEEN $inicio AND $fim ORDER BY data ASC");
    }


    public function listar_por_tipo_servico($di, $df, $id_cliente, $inicio=0, $limite=100, $order_campo = 'f.data_vencimento', $order = 'DESC') {
        $query = $this->db->select('f.*, c.nome, os.tipo_os')
            ->join('showtecsystem.cad_clientes as c', 'c.id = f.id_cliente')
            ->join('showtecsystem.os as os', 'os.id = f.id_os')
            ->where(array('f.id_cliente' => $id_cliente, 'f.data_vencimento >=' => $di, 'f.data_vencimento <=' => $df))
            ->order_by($order_campo, $order)
            ->get('showtecsystem.cad_faturas as f', $limite, $inicio);

        if($query->num_rows()){
            return $query->result();
        }

        return false;
    }

    public function listar_por_cliente($select = '*', $where = array(), $inicio=0, $limite=100, $order_campo = 'cad_faturas.Id', $order = 'DESC') {
        $query = $this->db->select($select)
            ->where($where)
            ->order_by($order_campo, $order)
            ->get('cad_faturas', $limite, $inicio);

        if($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
    * LISTAS TODAS AS FATURAS PAGAS E ABERTAS
    */
    public function listFaturas($select, $where=array()){
        $status =  array(0,1,2);       //FATURAS PAGAS E ABERTAS
        $query = $this->db->select($select)
            ->where_in('status', $status)
            ->where($where)
            ->order_by('Id', 'asc')
            ->get('showtecsystem.cad_faturas');

        if($query->num_rows() > 0) {
            $faturas = $query->result();
            foreach ($faturas as $fatura) {
                $fatura->valor_total -= $this->valor_abonado($fatura->Id);
            }
            return $faturas;
        }
        return false;
    }

    /*
    * LISTAS TODOS OS ITENS DE UM GRUPO DE FATURAS
    */
    //$grupoFaturas é o grupo de id's de faturas
    public function listItensFaturas($grupoFaturas, $select = '*') {
        $query = $this->db->select($select)
            ->where_in('id_fatura', $grupoFaturas)
            ->get('showtecsystem.fatura_itens');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    /*
    * LISTAS TODOS OS ITENS DE UM GRUPO DE FATURAS E CONTRATO
    */
    public function listaItensContratoFaturas($faturas, $select = '*') {
        $query = $this->db->select($select)
            ->where_in('id_fatura', $faturas)
            ->order_by('id_item', 'asc')
            ->get('showtecsystem.fatura_itens');

        if($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
    * RETORNA A FATURA INSERIDA MAIS RECENTEMENTE
    */
    public function faturasRecentes($select = '*', $inicio=0, $limit=1) {
        $query = $this->db->select($select)
            ->order_by('Id', 'DESC')
            ->get('showtecsystem.cad_faturas', $limit, $inicio);

        if($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
    *  SALVA DADOS DE FATURAS EM LOTE
    */
    public function insertFaturasBatch($insert){
        return $this->db->insert_batch('showtecsystem.cad_faturas', $insert);
    }

    /*
    *  ATUALIZA DADOS DE FATURAS EM LOTE
    */
    public function updateFaturasBatch($update){
        return @$this->db->update_batch('showtecsystem.cad_faturas', $update, 'Id');
    }

    /*
    *  SALVA DADOS DE ITENS EM LOTE
    */
    public function insertItensBatch($insertItens){
        return $this->db->insert_batch('showtecsystem.fatura_itens', $insertItens);
    }

    /*
    *  ATUALIZA DADOS DE ITENS EM LOTE
    */
    public function updateItensBatch($updateItens){
        return @$this->db->update_batch('showtecsystem.fatura_itens', $updateItens, 'id_item');
    }

    /*
    *  ATUALIZA OS VALORES DAS FATURAS EM LOTE
    */
    public function updateValorFaturasBatch($faturas){
        $update = $this->listValorTotalFaturas($faturas);
        //O @ ESTA OMITINDO UM ERRO GERADO POR UM BUG DA VERSAO DO CODEIGNITER, MAS A ATUALIZACAO DOS DADOS ESTA SENDO FEITA NORMALMENTE
        return @$this->db->update_batch('showtecsystem.cad_faturas', $update, 'Id');
    }

    /*
    * LISTA FATURA COM SEU VALOR TOTAL
    */
    public function listValorTotalFaturas($faturas){
        $sql_fatsVal = "SELECT id_fatura as Id, sum(valor_item) as valor_total, sum(valor_item) as total_fatura, sum(valor_item) as valor_boleto
                FROM showtecsystem.fatura_itens
                WHERE id_fatura in ($faturas)
                GROUP BY id_fatura";
        $faturasValores = $this->db->query($sql_fatsVal);

        if($faturasValores->num_rows()>0){
            return $faturasValores->result_array();
        }
        return false;
    }

    /*
    * LISTAS TODOS OS DADOS DAS FATURAS PASSADAS POR PARAMETRO
    */
    //$faturas eh um array de id_fatura
    public function listaFaturasPorGrupoId($faturas, $select = '*') {
        $query = $this->db->select($select)
            ->where_in('Id', $faturas)
            ->order_by('Id', 'asc')
            ->get('showtecsystem.cad_faturas');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    /*
    * RETORNA OS CONTRATOS COM ADESAO CADASTRADA
    */
	public function listaContratosAdesao($contratos) {
        $query = $this->db->distinct()
                ->select('relid_item as id_contrato')
                ->where('tipo_item', 'adesao')
                ->where_in('relid_item', $contratos)
                ->group_by('id_item')
                ->get('showtecsystem.fatura_itens');

        if($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
	}

    /*
	* CARREGA A QUERY DAS FATURAS PELOS FILTROS SERVE-SIDE
	*/
	public function getFaturasServeSide($where=array(), $status, $filtro, $select, $order=false, $start=0, $limit=10, $search=NULL, $draw=1, $getQtdTotal=false){
        $colunas = array('todos', 'Id', 'data_vencimento', 'valor_total', 'sub_total', 'nota_fiscal', 'mes_referencia', 'periodo_inicial', 'periodo_final', 'data_pagto', 'valor_pago', 'id_secretaria', 'id_ticket', 'atividade', 'status');
		$this->db->select($select);
        if(count($status)>0){
            $this->db->where_in('status', $status);
        }
        if($search){
            $this->db->where($filtro, $search);
        }
		$this->db->where($where);

        if ($order) {
            $coluna = $colunas[$order['column']];
            if ($coluna == 'sub_total') {
                $coluna = 'valor_total';
            }

            $this->db->order_by($coluna, $order['dir']);
        }else {
            $this->db->order_by('Id', 'DESC');
        }

		if ($getQtdTotal) {
            $query = $this->db->get('showtecsystem.cad_faturas');
        }else {
            $query = $this->db->get('showtecsystem.cad_faturas', $limit, $start);
        }

        return $query;
    }

    /*
	* LISTAGEM DE FATURAS POR SERVE-SIDE
	*/
	public function listFaturasServeside($where=array(), $status, $filtro, $select, $order=false, $start=0, $limit=10, $search=NULL, $draw=1){
        $dados = array();
		$queryFaturas = $this->getFaturasServeSide($where, $status, $filtro, $select, $order, $start, $limit, $search, $draw);
		$queryQtdTotalFaturas = $this->getFaturasServeSide($where, $status, $filtro, $select, $order, $start, $limit, $search, $draw, true)->num_rows();

		if($queryFaturas->num_rows() > 0){
            $faturas = $queryFaturas->result();
            foreach ($faturas as $fatura) {
                $fatura->valor_total -= $this->valor_abonado($fatura->Id);
            }

			$dados['faturas'] = $faturas; # Lista de faturas
			$dados['recordsTotal'] = $queryQtdTotalFaturas; # Total de registros
            $dados['recordsFiltered'] = $dados['recordsTotal']; # atribui o mesmo valor do recordsTotal ao recordsFiltered para que tivesse todas as paginas na datatable
            // $dados['recordsFiltered'] = $queryFaturas->num_rows(); # Total de registros Filtrados
	        $dados['draw'] = $draw++; # Draw do datatable

			return $dados;
		}

		return false;
    }

    /*
    * LISTAS TODAS AS FATURAS DE UM PERIODO DE TEMPO
    */
    public function getFaturasGeradas($select = '*', $where=array(), $situacao, $join=true) {
        $this->db->select($select);
        if ($join) $this->db->join('showtecsystem.cad_clientes as cli', 'cli.id = f.id_cliente');
        $this->db->where($where);
        $this->db->where_in('f.status', $situacao);
        $this->db->order_by('f.Id', 'DESC');
        $query = $this->db->get('showtecsystem.cad_faturas as f');

        if($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /*
    * RETORNA OS CODIGOS DE VERIFICACAO DE UM GRUPO DE FATURAS
    */
    public function getCodsVerification($cods_ids){
        $sql = "SELECT id_fatura, GROUP_CONCAT( id SEPARATOR ',' ) as ids
                FROM showtecsystem.extract
                WHERE id in ($cods_ids)
                GROUP BY id_fatura";
        $query = $this->db->query($sql);

        if($query->num_rows()>0){
            return $query->result();
        }
        return false;
    }

    /*
    * LISTA FATURAS EM ATRASO
    */
    public function faturasDiasAtraso($di, $df, $id_cliente=false, $empresa='todas', $orgao='todos'){
        $hoje = date('Y-m-d');
        $this->db->select('fat.Id, cli.nome, fat.data_emissao, fat.data_vencimento, fat.valor_total, fat.status');
        $this->db->join('showtecsystem.cad_clientes as cli', 'cli.id = fat.id_cliente');
        if($id_cliente) $this->db->where('cli.id', $id_cliente);
        if($empresa != 'todas') $this->db->where('cli.informacoes',$empresa);
        if($orgao != 'todos') $this->db->where('cli.orgao',$orgao);
        $this->db->where(array('fat.data_vencimento >=' => $di, 'fat.data_vencimento <=' => $df, 'fat.data_vencimento <' => $hoje));
        $this->db->where_in('fat.status', array(0,2));
        $this->db->order_by('fat.Id','desc');
        $query = $this->db->get('showtecsystem.cad_faturas as fat');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function faturasDiasProcessadas($di, $df){
        $query = $this->db->select('*')->from('showtecsystem.retorno_pagamento')->where('date(dataexec_retorno) >= ',$di)->where('date(dataexec_retorno) <= ',$df)->get();
           
        if ($query->num_rows() > 0) {
            return $query->result();           
        }
        return false;
    }

    public function getRelatorioFaturas($data_inicial, $data_final, $empresa='todas', $status, $orgao){
        $this->db->select('cli.id, cli.nome, cli.cpf, cli.cnpj,cli.orgao, fat.data_emissao, fat.data_vencimento, fat.valor_total, fat.status,cli.informacoes, fat.data_emissao, fat.quantidade_veiculos,fat.valor_total, fat.mes_referencia, fat.numero, fat.atividade, fat.iss, fat.irpj, fat.csll, fat.pis, fat.cofins, fat.nota_fiscal');
        $this->db->join('showtecsystem.cad_clientes as cli', 'cli.id = fat.id_cliente');
        if($orgao) $this->db->where('cli.orgao', $orgao);
        if($empresa != 'todas') $this->db->where('cli.informacoes',$empresa);
        if($status[0] != '5') $this->db->where_in('fat.status',$status);
        $this->db->where(array('fat.data_emissao >=' => $data_inicial, 'fat.data_emissao <=' => $data_final));
        $this->db->order_by('fat.Id','desc');
        $query = $this->db->get('showtecsystem.cad_faturas as fat');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /**
     * SALVA UM COMENTARIO PARA A FATURA
    */
    public function addComentFatura($data) {
        $this->db->insert('showtecsystem.comments_fatura', $data);
        return $this->db->insert_id();
    }

    public function getStatusFatura($id_fatura){
        $this->db->select('status');
        $this->db->where('Id', $id_fatura);
        $query = $this->db->get('showtecsystem.cad_faturas');

        if($query->num_rows() > 0){
            return $query->row()->status;
        }
        return false;
    }

    public function to_alterarStatusFatura($id_fatura, $status){
        $this->db->where('Id', $id_fatura);
        $this->db->update('showtecsystem.cad_faturas', array('status' => $status));

        if ($this->db->affected_rows() > 0) {
            return true;
        }else{
            return false;
        }
        
    }

}
