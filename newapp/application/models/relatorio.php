<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relatorio extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->model('cliente');
    }

    /*
     * metódo para gerar relatorio financeiro/faturas a pagar e a vencer
     * @param Date $dt_inicio
     * @param Date $dt_fim
     * @param String $cliente
     * @param String $status_fatura
     * @return Array $faturas
     */

    public function getPointsByClie($post) {
        $query = "SELECT cart.pontos, clie.nome, cart.id_cliente, cart.data FROM showroutes.carteira_clientes as cart
                    INNER JOIN showtecsystem.cad_clientes as clie ON clie.id = cart.id_cliente
                    WHERE cart.tipo = 'A' AND cart.id_cliente = ".$post['id_cliente']." AND cart.data >= '".$post['inicio']." 00:00:01' AND cart.data <= '".$post['fim']." 23:59:59';";
        return $this->db->query($query)->result();
    }

    public function getPointsByDate($data_ini, $data_fim) {
        $query = "SELECT SUM(cart.pontos) as soma, clie.nome, cart.id_cliente FROM showroutes.carteira_clientes as cart
                    INNER JOIN showtecsystem.cad_clientes as clie ON clie.id = cart.id_cliente
                    WHERE cart.tipo = 'A' AND cart.data >= '".$data_ini." 00:00:01' AND cart.data <= '".$data_fim." 23:59:59' GROUP BY cart.id_cliente;";
        return $this->db->query($query)->result();
    }

    public function fatura($dt_inicio, $dt_fim, $cliente, $status_fatura, $empresa, $uf = false, $vendedor = false, $orgao = false, $tipoPessoa = false, $tipoAtividade = false, $informacoes =  false, $status_financeiro_cliente = false) {
        if ($vendedor != 'todos') {
            $ids = $this->getClientesByVendedor($vendedor);
        }

        // sub query para soma dos valores das taxas da fatura
        $taxas = 'SELECT SUM(valor_item) FROM fatura_itens as item	WHERE item.id_fatura = fatura.Id
					AND item.taxa_item = "sim"';

        $order_by = $status_fatura ? 'fatura.data_vencimento' : 'fatura.data_emissao';
        $data_venc = '';
        $data_pag = '';

        if (!is_array($status_fatura)){
            $status_fatura = [];
        }elseif (in_array('0', $status_fatura) && !in_array('2', $status_fatura)){
            $status_fatura[] = 2;
        }

        if (in_array(1, $status_fatura)){
            if (in_array(0, $status_fatura)){
                $where = "((fatura.data_vencimento BETWEEN '{$dt_inicio}' AND '{$dt_fim}') OR (fatura.data_pagto BETWEEN '{$dt_inicio}' AND '{$dt_fim}'))";
            }
            elseif(in_array(3, $status_fatura)){
                $where = "((fatura.data_vencimento BETWEEN '{$dt_inicio}' AND '{$dt_fim}') OR (fatura.data_pagto BETWEEN '{$dt_inicio}' AND '{$dt_fim}'))";
            }
            else{
                $where = "(fatura.data_pagto BETWEEN '{$dt_inicio}' AND '{$dt_fim}')";
            }
        }
        elseif (in_array(0, $status_fatura) OR in_array(3, $status_fatura)){
            $where = "(fatura.data_vencimento BETWEEN '{$dt_inicio}' AND '{$dt_fim}')";
        }elseif (count($status_fatura) == 0){
            $where = "(fatura.data_emissao BETWEEN '{$dt_inicio}' AND '{$dt_fim}')";
        }
        
        if ($tipoAtividade != false || $tipoAtividade != ''){
            $where .= " AND fatura.atividade = '{$tipoAtividade}'";
        }

        $this->db->select("DISTINCT(fatura.Id), fatura.valor_total, fatura.data_vencimento, cliente.nome,
						({$taxas}) total_taxa, fatura.mes_referencia, fatura.nota_fiscal, fatura.periodo_inicial, fatura.periodo_final, fatura.status, fatura.data_pagto, fatura.valor_pago,
						contratos.meses, contratos.data_contrato, fatura.formapagamento_fatura, fatura.data_emissao, fatura.retorno_fn, fatura.numero, cliente.informacoes, cliente.cnpj, cliente.cpf, cliente.orgao, cliente.status as status_cliente, fatura.generator, fatura.atividade");
        $this->db->join('cad_clientes as cliente', 'cliente.id = fatura.id_cliente');
        $this->db->join('contratos', 'contratos.id = fatura.id_contrato', 'left');
        $this->db->where($where);
        (count($status_fatura) == 0) ? '' : $this->db->where_in('fatura.status', $status_fatura);

        if ($cliente && is_array($cliente)) $this->db->where_in('fatura.id_cliente', $cliente);
        if ($empresa != 'todos') $this->db->where('cliente.informacoes', $empresa);

        if ($vendedor != false && $vendedor != 'todos') {
            if ($ids && count($ids) > 0)
                $this->db->where_in('cliente.id', $ids);
            else
                $this->db->where('cliente.id_vendedor', $vendedor);
        }

        if ($orgao) {
            $this->db->where('cliente.orgao', $orgao);
        }

        if ($status_financeiro_cliente) {
            $this->db->where('cliente.status', $status_financeiro_cliente);
        }
        if ($uf) {
            $explode_uf = explode('-',$uf);
            if($explode_uf[1]=='EUA'){
                $this->db->where('cliente.informacoes', 'EUA');
                $this->db->where('cliente.uf', $explode_uf[0]);
            }
            else{
                $this->db->where('cliente.informacoes !=', 'EUA');
                $this->db->where('cliente.uf', $explode_uf[0]);
            }
        }

       // var_dump($informacoes);

        if($informacoes == 'parceiro'){
            $this->db->where('cliente.informacoes', 'EMBARCADORES');
        }
        else if($informacoes == 'cliente'){
            $this->db->where('cliente.informacoes !=', 'EMBARCADORES');
        }

        if (is_array($tipoPessoa)) {
            if ($tipoPessoa[0] == 'pessoaFisica') {
                $this->db->where('cliente.cnpj', NUll);
            } else if ($tipoPessoa[0] == 'pessoaJuridica') {
                $this->db->where('cliente.cpf', NUll);
            }
        }

        $this->db->order_by($order_by, 'asc');
        $query = $this->db->get('cad_faturas as fatura')->result();

        $cods_id = array();
        foreach ($query as $fatura) {
            $fatura->id_transacao = '';
            $result = false;
            if (substr($fatura->retorno_fn, 0, 7) == 'EXTRATO') {
               $ax = explode('-', $fatura->retorno_fn);
               if ($ax[1]) $cods_id[] = $ax[1];
               $fatura->tipo_pag = 'DEPÓSITO';
            } elseif (strlen($fatura->retorno_fn) <= 4) {
                if ($fatura->retorno_fn) $cods_id[] = $fatura->retorno_fn;
                $fatura->tipo_pag = 'DEPÓSITO';
            } elseif (is_string($fatura->retorno_fn) && strstr($fatura->retorno_fn, '.ret')) {
                $fatura->tipo_pag = 'ARQ. RETORNO';
            } else {
                $fatura->tipo_pag = 'N/A';
            }

            if (!is_null($fatura->data_contrato) && $fatura->data_contrato != '' && is_numeric($fatura->meses)) {
                $fatura->fim_contrato = date('d/m/Y', strtotime($fatura->data_contrato . ' + ' . $fatura->meses . ' MONTH'));
            }
        }

        if (count($cods_id) > 0) $cods = $this->fatura->getCodsVerification(implode(',', $cods_id));

        if(isset($cods)){
            foreach ($query as $fatura) {
                if (isset($cods[$fatura->Id])) {
                    $fatura->id_transacao = $cods[$fatura->Id];
                }
            }
        }

        return $query;
    }

    private function getClientesByVendedor($vendedor) {
        $contratos = $this->db->select('id_cliente')->get_where('contratos', array('id_vendedor' => $vendedor))->result();

        $ids = array();
        foreach ($contratos as $contrato) {
            $ids[] = $contrato->id_cliente;
        }

        return $ids;
    }

    /*
     * metódo para corrigir a exibição duplicada de faturas com o mesmo número no relatório de faturas
    * @param Array $faturas
    * @return Array $fat_joined
    */
    public function unir_fatura_numero($faturas){
        $n_fats = array();
        $fat_joined = array();
        //faz um join nas faturas por número
        foreach ($faturas as $fatura){

            if ($fatura->numero > 0){
                $n_fats[$fatura->numero][] = $fatura;
            }else{
                $fat_joined[] = $fatura;
            }

        }

        if (count($n_fats) > 0){

            foreach ($n_fats as $key => $fats){

                $total_fatura = 0;
                foreach ($fats as $fat){
                    $total_fatura += $fat->valor_total;
                }

                $fat_joined[] = (object)array('Id' => $key, 'valor_total' => $total_fatura,
                    'data_vencimento' => $fat->data_vencimento, 'nome' => $fat->nome,
                    'total_taxa' => $fat->total_taxa, 'status' => $fat->status,
                    'data_pagto' => $fat->data_pagto, 'valor_pago' => $fat->valor_pago,
                    'formapagamento_fatura' => $fat->formapagamento_fatura,
                    'retorno_fn' => $fat->retorno_fn, 'numero' => $fat->numero, 'empresa' => $fat->informacoes);

            }
        }

        return $fat_joined;
    }

    public function get_tempo_logado_por_data($usuario_email,$data){

        $tempo_logado = '00:00:00';
        $this->db->where('usuario_email', $usuario_email);
        $this->db->where('data', $data);
        $sessoes = $this->db->get('showtecsystem.horario_acesso')->result();

        if($this->db->affected_rows() > 0) {
            foreach($sessoes as $sessao){
                $tempo_logado = sum_hours($tempo_logado, $sessao->tempo_logado);
            }
        }
        return $tempo_logado;
    }

    public function get_usuarios() {

        $this->db->select('nome');
        $this->db->order_by('nome', 'asc');
        $this->db->select('login');
        $query = $this->db->get('showtecsystem.usuario');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function get_user_logins() {
        $this->db->select('login as l');
        $query = $this->db->get('showtecsystem.usuario');
        $this->db->limit(8);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function tempo_logado($dados) {

        if($dados['usuario_email']) {

            $sessoes = $this->db->where('usuario_email', $dados['usuario_email'])
                ->where('data >=', $dados['dt_ini'])
                ->where('data <=', $dados['dt_fim'])
                ->where('h.tempo_logado !=', '00:00:00')
                ->select('h.tempo_logado as TP, u.tempo_logado as TP2, h.usuario_email, h.data, u.nome, h.horario_login, h.horario_logout')
                ->join('showtecsystem.usuario as u', 'h.usuario_email = u.login')
                ->get('showtecsystem.horario_acesso as h');

            if($sessoes->num_rows() > 0) {

                $sessoes = $sessoes->result();

                $sessoes_usuario = array();
                foreach($sessoes as $sessao){

                    $tempo_logado = $this->get_tempo_logado_por_data($sessao->usuario_email,$sessao->data);

                    array_push($sessoes_usuario, array('data' => data_for_humans($sessao->data), 'horario_login' => $sessao->horario_login, 'horario_logout' => $sessao->horario_logout,'tempo_logado' => $sessao->TP, 'TP_max' => $sessao->TP2, 'email' => $sessao->usuario_email, 'tempo_logado_dia' => $tempo_logado));
                }

                $relatorio[$sessoes[0]->nome] = $sessoes_usuario;

                return $relatorio;

            }else{

                return false;
            }

        }else{

            $sessoes = $this->db->where('data >=', $dados['dt_ini'])
                ->where('data <=', $dados['dt_fim'])
                ->where('h.tempo_logado !=', '00:00:00')
                ->select('h.tempo_logado as TP, u.tempo_logado as TP2, h.usuario_email, h.data, u.nome, h.horario_login, h.horario_logout')
                ->join('showtecsystem.usuario as u', 'h.usuario_email = u.login')
                ->get('showtecsystem.horario_acesso as h');

            if($sessoes->num_rows() > 0) {
                $sessoes = $sessoes->result();
                $usuarios = $this->get_usuarios();

                foreach($usuarios as $usuario){

                    $sessoes_usuario = array();
                    foreach($sessoes as $sessao){

                        if($sessao->usuario_email == $usuario->login){

                            $tempo_logado = $this->get_tempo_logado_por_data($sessao->usuario_email,$sessao->data);

                            array_push($sessoes_usuario, array('data' => data_for_humans($sessao->data), 'horario_login' => $sessao->horario_login, 'horario_logout' => $sessao->horario_logout,'tempo_logado' => $sessao->TP, 'TP_max' => $sessao->TP2, 'email' => $usuario->login, 'tempo_logado_dia' => $tempo_logado));
                        }
                    }

                    $relatorio[$usuario->nome] = $sessoes_usuario;
                }
                return $relatorio;

            }else{

                return false;
            }
        }
    }

    public function fatura_by_deve($dt_inicio, $dt_fim, $cliente){
        $order_by = 'fatura.data_vencimento';
        $data_venc = '';
        $data_pag = '';
        $clie = '';
        if ($cliente != ''){
            $clie = 'fatura.id_cliente = '.$cliente.' AND ';

        }
        $data_venc = "(fatura.data_vencimento BETWEEN '{$dt_inicio}' AND '{$dt_fim}')";
        $where = $clie.'('.$data_venc.$data_pag.')';
        $query = $this->db->select("count(fatura.Id) as faturasEmAtraso, avg(fatura.valor_total) as valorMédio, sum(fatura.valor_total) as valorTotalDoDébito, cliente.nome as cliente")
            ->join('cad_clientes as cliente', 'cliente.id = fatura.id_cliente')
            ->where($where)
            ->where_in('fatura.status', 0)
            ->order_by($order_by, 'asc')
            ->get('cad_faturas as fatura');
        return $query->result()[0];
    }

    public function get_clientes_uf($uf, $empresa = 'todos', $orgao = 'todos') {
        $this->db->select('id, nome, endereco, numero, bairro, cep, cidade, uf, fone, email, data_cadastro, informacoes');
        $this->db->where('uf', $uf);
        $this->db->where('status', 1);

        if ($orgao != 'todos')
            $this->db->where('orgao', $orgao);

        if ($empresa != 'todos')
            $this->db->where('informacoes', $empresa);

        $query = $this->db->get('showtecsystem.cad_clientes');
        if ($query->num_rows()>0)
            return $query->result();

        return false;
    }

    public function resumo_faturas($mes_ini, $mes_fim, $clientes) {
        $data_sql = '';
        foreach ($clientes as $cliente) {

            $data_sql = $mes_ini;
            while ($data_sql <= $mes_fim) {
                $dateIni = $data_sql.'-01';
                $dateFim = $data_sql.'-31';

                // SOMA TOTAL FATURADO PARA O CLIENTE DURANTE O MÊS
                $faturado_mes = $this->db->select_sum('valor_total')
                    ->where('status !=', 3)
                    ->where('id_cliente', $cliente->id)
                    ->where('data_vencimento >=', $dateIni)
                    ->where('data_vencimento <=', $dateFim)
                    ->get('showtecsystem.cad_faturas')->row();

                // SOMA TOTAL PAGO DO CLIENTE DURANTE O MÊS
                $pago_mes = $this->db->select_sum('valor_pago')
                    ->where('status', 1)
                    ->where('id_cliente', $cliente->id)
                    ->where('data_vencimento >=', $dateIni)
                    ->where('data_vencimento <=', $dateFim)
                    ->get('showtecsystem.cad_faturas')->row();

                $cliente->meses[] = array ('referencia' => $data_sql, 'faturado' => $faturado_mes, 'pago' => $pago_mes);

                $data_sql = date('Y-m', strtotime("+1 month", strtotime($data_sql)));
                //pr($data_sql); die;
            }
        }
        //pr($clientes); die;
        return $clientes;
    }

    public function saeb1() {
        // BUSCA VEICULOS NO CONTRATO
        $veiculos = $this->db->get_where('showtecsystem.contratos_veiculos', array('id_cliente' => 2121, 'status' => 'ativo'))->result();

        foreach ($veiculos as $veiculo) {
            // BUSCA REGISTROS DO VEICULO
            $veics = $this->db->get_where('systems.cadastro_veiculo', array('placa' => $veiculo->placa))->result();

            if ($veics) {
                foreach ($veics as $veic) {
                    if ($veic->status == 0) {
                        $this->db->where('code', $veic->code);
                        $this->db->update('systems.cadastro_veiculo', array('status' => 1));

                        echo "A placa ".$veic->placa." se encontrava desativada no cadastro de veiculo </br>";
                    } else {
                        echo "Placa ".$veic->placa." já atualizada. </br>";
                    }
                }
            } else {
                echo "Não encontrei a placa ".$veiculo->placa." no cadastro de veiculos.</br>";
            }
        }
    }

    public function saeb() {
        // BUSCA REGISTROS DA SAEB
        $query = $this->db->select('veic.placa')
            ->from('showtecsystem.cadastro_grupo as grupo')
            ->join('showtecsystem.veic_x_group as veic', 'grupo.id = veic.groupon', 'INNER')
            ->where(array('grupo.id_cliente' => 2121, 'grupo.status' => 1, 'veic.status' => 1))
            ->group_by('veic.placa')
            ->get()->result();

        foreach ($query as $veiculo) {
            $verifica = $this->db->get_where('showtecsystem.contratos_veiculos', array('placa' => $veiculo->placa, 'id_cliente' => 2121, 'status' => 'ativo'))->result();

            if (!$verifica) {
                echo "A placa ".$veiculo->placa." não esta ativa no contrato. </br>";
            }
        }

        echo "</br>".count($query);
    }

    public function assinatura_eptc(){
        $query = $this->db->select('a.*,us.nome_usuario,us.usuario')
            ->from('showtecsystem.aceite_eptc a')
            ->join('showtecsystem.usuario_gestor us','us.code = a.user')
            ->order_by('datahora','desc')
            ->get()->result();
        return $query;
    }
    public function alterar_status_assinatura_eptc($id,$status){
        $this->db->where('id',$id);
        $this->db->set('status',$status);
        $this->db->update('showtecsystem.aceite_eptc');
        return true;
    }    

    public function getContratosByVendedor($id_vendedor, $dt_inicial, $dt_final) {
        $this->db->select('cliente.nome, sum(quantidade_veiculos) as quantidade_veiculos, contrato.*');
        $this->db->from('showtecsystem.contratos contrato');
        $this->db->join('showtecsystem.cad_clientes cliente', 'contrato.id_cliente = cliente.id');
        $this->db->where('contrato.id_vendedor', $id_vendedor);
        /* $this->db->where("contrato.data_cadastro between '{$dt_inicial}' and '{$dt_final}'"); */
        $this->db->where_in('contrato.status', array(1, 2));
        $this->db->group_by('cliente.nome');

        $query = $this->db->get();

        if($query->num_rows() > 0) {
            return $query->result();
        }
        else {
            return array();
        }
    }

    public function getVeiculosContrato($id_contrato) {
        $this->db->where('id_contrato', $id_contrato);
        $this->db->where('status', 'ativo');

        $query = $this->db->get('showtecsystem.contratos_veiculos');

        if($query->num_rows() > 0) {
            return $query->result();
        }
        else {
            return array();
        }
    }

    public function getVeiculosbyPlaca($placa, $dt_inicial, $dt_final) {
        $this->db->where('placa', $placa);
        $this->db->where("data_instalacao between '{$dt_inicial}' and '{$dt_final}'");

        $query = $this->db->get('systems.cadastro_veiculo');

        if($query->num_rows() > 0) {
            return $query->result()[0];
        }
        else {
            return array();
        }
    }
}
