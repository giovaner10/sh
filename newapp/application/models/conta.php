<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Conta extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function getNameFornecedores()
    {
        $f = $this->db->select('fornecedor')->distinct('fornecedor')->get('showtecsystem.cad_contas')->result();

        $names = array();
        foreach ($f as $for) {
            $names[] = $for->fornecedor;
        }

        return $names;
    }

    public function get_totAll($where=array()) {
        if ($where)
            $this->db->where($where);
        return $this->db->count_all_results('showtecsystem.cad_contas');
    }

    public function add($data) {
//        pr($data);die;
        $this->db->insert('cad_contas', $data);
        if($this->db->affected_rows() > 0)
            return $this->db->insert_id();
        return 0;
    }

    public function addCategoria($nome) {
        $name = strtoupper($nome);
        $query = $this->db->get_where('systems.categoria_contas_apagar', array('nome' => $name))->result();

        if(!$query) {
            $insert = $this->db->insert('systems.categoria_contas_apagar', array('nome' => $name, 'status' => 1));
            return $insert;
        }
        return false;
    }

    public function get($where) {
        $query = $this->db->select('*')->where($where)->get('cad_contas');
        if($query->num_rows() > 0) {
            foreach($query->result() as $conta);
            return $conta;
        }
        return array();
    }

    public function get_contas($where) {
        $query = $this->db->select('*, cc.id as conta_id')->join('showtecsystem.cad_contasbank c','c.id = cc.id_contas')->where_in('cc.id',$where)->get('cad_contas cc');
        if($query->num_rows() > 0) {
            $query = $query->result();
            foreach($query as $line){
                if($line->banco=="001"){
                    $line->forma_lancamento="01";
                }
                else{
                    $line->forma_lancamento="41"; 
                }
                $line->codigo_banco=$line->banco;
                $ag = explode('-',$line->agencia);
                $line->agencia=$ag[0];
                $line->dv_agencia=strtoupper($ag[1]);
                $conta = explode('-',$line->conta);
                $line->conta=str_replace(".","",$conta[0]);
                $line->dv_conta=strtoupper($conta[1]);
                $line->fornecedor=$tr = strtr(strtoupper($line->titular),
                array (
                      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
                      'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
                      'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
                      'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
                      'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
                      'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
                      'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
                      'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
                      'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                      'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
                      'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r'
                    )
                );
                $cpf = preg_replace("/[^0-9]/", "", str_replace("/","",str_replace("-","",str_replace(".","",$line->cpf))));
                $line->cpf_cnpj = $cpf;
                if(strlen($cpf)==11){
                    $line->pf_pj=1;
                }
                else{
                    $line->pf_pj=2;
                }
            }
            return $query;
        }
    }

    public function get_contas_boleto($titulos,$guias,$guia = false){
        if(!$guia){
            $virgula = false;
            $ids = "";
            foreach($titulos as $titulo){
                if($virgula){
                    $ids.=",";
                }
                $ids .= "'".$titulo."'";
                $virgula=true;
            }
            $result = $this->db->query("SELECT *,cc.id as conta_id FROM showtecsystem.cad_contas cc where cc.id in (".$ids.") and cc.status=0 and (cc.id_contas!='-2' or cc.id_contas is null) and cc.codigo_barra is not null order by cc.codigo_barra")->result();
        }
        else{
            $virgula = false;
            $ids = "";
            foreach($guias as $guia){
                if($virgula){
                    $ids.=",";
                }
                $ids .= "'".$guia."'";
                $virgula=true;
            }
            $result = $this->db->query("SELECT *,cc.id as conta_id FROM showtecsystem.cad_contas cc where cc.id in (".$ids.") and cc.status=0 and cc.id_contas='-2' and cc.codigo_barra is not null")->result();
        }
        $fornecedores = array();
        foreach($result as $r){
            $fornecedores[] = explode(' - ',$r->fornecedor)[0];
        }
        $this->db->where_in('id',$fornecedores);
        $query_fornecedor = $this->db->get('showtecsystem.fornecedores')->result();
        $array_fornecedor = array();
        foreach($query_fornecedor as $f){
            $cnpj = preg_replace("/[^0-9]/", "", str_replace("/","",str_replace("-","",str_replace(".","",$f->cnpj))));
            $f->cnpj = $cnpj;
            if(strlen($cnpj)==11){
                $f->pf_pj=1;
            }
            else{
                $f->pf_pj=2;
            }
            $f->nome=$tr = strtr(strtoupper($f->nome),
                array (
                      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
                      'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
                      'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
                      'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
                      'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
                      'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
                      'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
                      'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
                      'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                      'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
                      'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r'
                    )
                );
            $array_fornecedor[$f->id] = $f;

        }
        foreach($result as $r){
            $r->fornecedor=$array_fornecedor[explode(' - ',$r->fornecedor)[0]];
        }
        return $result;
    }

    public function get_contas_fornecedore_pendentes($empresa){
        $result = $this->db->query("SELECT *,cc.id as conta_id FROM showtecsystem.cad_contas cc join showtecsystem.cad_contasbank cb on cb.id = cc.id_contas and cb.cad_retorno = 'fornecedor' and cb.status=1 where cc.empresa = '$empresa' and cc.status=0 and cc.id_contas is not null order by cb.banco, cb.tipo")->result();
        foreach($result as $r){
            $r->valor=number_format($r->valor, 2, ',', '.');
        }
        return $result;
    }
    
    public function get_contas_titulo_pendentes($empresa){
        $result = $this->db->query("SELECT *,cc.id as conta_id FROM showtecsystem.cad_contas cc where cc.empresa = '$empresa' and cc.status=0 and cc.codigo_barra is not null and cc.id_contas != '-2'")->result();
        foreach($result as $r){
            $r->valor=number_format($r->valor, 2, ',', '.');
        }
        return $result;
    }

    public function get_contas_guia_pendentes($empresa){
        $result = $this->db->query("SELECT *,cc.id as conta_id FROM showtecsystem.cad_contas cc where cc.empresa = '$empresa' and cc.status=0 and cc.codigo_barra is not null and cc.id_contas = '-2'")->result();
        foreach($result as $r){
            $r->valor=number_format($r->valor, 2, ',', '.');
        }
        return $result;
    }

    public function get_contas_instaladores_pendentes($empresa){
        $result = $this->db->query("SELECT *,cc.id as conta_id FROM showtecsystem.cad_contas cc join showtecsystem.cad_contasbank cb on cb.id_retorno = cc.id_instalador and cb.cad_retorno = 'instalador' and cb.status=1 where cc.empresa = '$empresa' and categoria = 'INSTALADOR' and cc.status=0 and cc.id_instalador is not null order by cb.banco, cb.tipo")->result();
        foreach($result as $r){
            $r->valor=number_format($r->valor, 2, ',', '.');
        }
        return $result;
    }

    public function get_contas_salarios_pendentes($empresa){
        $result = $this->db->query("SELECT *,cc.id as conta_id FROM showtecsystem.cad_contas cc join showtecsystem.cad_contasbank cb on cb.id = cc.id_contas and cb.cad_retorno = 'usuario' and cb.status=1 where cc.empresa = '$empresa' and cc.status=0 and cc.id_contas is not null order by cb.banco, cb.tipo")->result();
        foreach($result as $r){
            $r->valor=number_format($r->valor, 2, ',', '.');
        }
        return $result;
    }

    public function deleteConta($id) {
        $this->db->delete('showtecsystem.cad_contas', array('id'=>$id));
        return $this->db->affected_rows() > 0;
    }
    

    public function get_remessas_erros($empresa){
        $this->lang->load('pt', 'portuguese');
        $result = $this->db->query("SELECT cc.*,cb.*,cc.id as conta_id,rp.status_pagamento FROM showtecsystem.retorno_pagamento rp join showtecsystem.cad_contas cc on cc.id = rp.contas_numero_retorno and cc.empresa = '$empresa' and categoria = 'INSTALADOR' and cc.status=0 and cc.id_instalador is not null join showtecsystem.cad_contasbank cb on cb.id_retorno = cc.id_instalador and cb.cad_retorno = 'instalador' and cb.status=1 where status_pagamento not like 'BD%' order by cb.banco, cb.tipo")->result();
        $id_contas = array();
        foreach($result as $erros){
            $id_contas[]=$erros->conta_id;
        }
        if(!$id_contas){
            return [];
        }
        $ok = $this->db->where_in('contas_numero_retorno',$id_contas)->like('status_pagamento',"BD")->get('showtecsystem.retorno_pagamento')->result();
        $estao_ok = array();
        foreach($ok as $sem_erros){
            $estao_ok[$sem_erros->contas_numero_retorno]=true;
        }
        $retorno = array();
        foreach($result as $index=>$r){
            if(!isset($estao_ok[$r->conta_id])){
                $status = "";
                $r->valor=number_format($r->valor, 2, ',', '.');
                $status .= lang('retorno_cod_'.substr($r->status_pagamento,0,2));
                if(substr($r->status_pagamento,2,2)!="  "){
                    $status .= lang('retorno_cod_'.substr($r->status_pagamento,2,2));
                }
                if(substr($r->status_pagamento,4,2)!="  "){
                    $status .= lang('retorno_cod_'.substr($r->status_pagamento,4,2));
                }
                if(substr($r->status_pagamento,6,2)!="  "){
                    $status .= lang('retorno_cod_'.substr($r->status_pagamento,6,2));
                }
                if(substr($r->status_pagamento,8,2)!="  "){
                    $status .= lang('retorno_cod_'.substr($r->status_pagamento,8,2));
                }
                $r->status_pagamento = $status;
                $retorno[]= $r;
            }
        }
        return $retorno;
    }

    public function get_contas_instaladores($ids) {
        $retorno = array('corrente'=>array(),'poupanca'=>array(),'ted'=>array());
        $query = $this->db->select('*, cc.id as conta_id')->join('showtecsystem.cad_contasbank c','c.id_retorno = cc.id_instalador and c.cad_retorno = "instalador" and c.status=1 ')->where_in('cc.id',$ids)->get('cad_contas cc');
        if($query->num_rows() > 0) {
            $query = $query->result();
            foreach($query as $line){
                $tipo_array = "";
                if($line->banco=="001"){
                    if($line->tipo[0]=='c'||$line->tipo[0]=='C'){
                        $line->forma_lancamento="01";
                        $tipo_array='corrente';
                    }
                    else{
                        $line->forma_lancamento="05";
                        $tipo_array='poupanca';
                    }
                }
                else{
                    $line->forma_lancamento="41"; 
                    $tipo_array='ted';
                }
                $line->codigo_banco=$line->banco;
                $ag = explode('-',$line->agencia);
                $line->agencia=$ag[0];
                if(isset($ag[1])){
                    $line->dv_agencia=strtoupper($ag[1]);
                }
                else{
                    $line->dv_agencia=" ";
                }
                $conta = explode('-',$line->conta);
                $line->conta=str_replace(".","",$conta[0]);
                if(isset($conta[1])){
                    $line->dv_conta=strtoupper($conta[1]);
                }
                else{
                    $line->dv_conta=" ";
                }
                $line->fornecedor=strtr(strtoupper($line->titular),
                array (
                      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
                      'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
                      'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
                      'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
                      'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
                      'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
                      'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
                      'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
                      'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                      'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
                      'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r'
                    )
                );
                $cpf = preg_replace("/[^0-9]/", "", (str_replace("'","",str_replace("/","",str_replace("-","",str_replace(".","",$line->cpf))))));
                $line->cpf_cnpj = $cpf;
                if(strlen($cpf)==11){
                    $line->pf_pj=1;
                }
                else{
                    $line->pf_pj=2;
                }
                $retorno[$tipo_array][]=$line;
            }
            return $retorno;
        }
    }
    public function get_contas_fornecedores($ids) {
        $retorno = array('corrente'=>array(),'poupanca'=>array(),'ted'=>array());
        $query = $this->db->select('*, cc.id as conta_id')->join('showtecsystem.cad_contasbank c','c.id = cc.id_contas and c.cad_retorno = "fornecedor" and c.status=1 ')->where_in('cc.id',$ids)->get('cad_contas cc');
        if($query->num_rows() > 0) {
            $query = $query->result();
            foreach($query as $line){
                $tipo_array = "";
                if($line->banco=="001"){
                    if($line->tipo[0]=='c'||$line->tipo[0]=='C'){
                        $line->forma_lancamento="01";
                        $tipo_array='corrente';
                    }
                    else{
                        $line->forma_lancamento="05";
                        $tipo_array='poupanca';
                    }
                }
                else{
                    $line->forma_lancamento="41"; 
                    $tipo_array='ted';
                }
                $line->codigo_banco=$line->banco;
                $ag = explode('-',$line->agencia);
                $line->agencia=$ag[0];
                if(isset($ag[1])){
                    $line->dv_agencia=strtoupper($ag[1]);
                }
                else{
                    $line->dv_agencia=" ";
                }
                $conta = explode('-',$line->conta);
                $line->conta=str_replace(".","",$conta[0]);
                if(isset($conta[1])){
                    $line->dv_conta=strtoupper($conta[1]);
                }
                else{
                    $line->dv_conta=" ";
                }
                $line->fornecedor=strtr(strtoupper($line->titular),
                array (
                      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
                      'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
                      'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
                      'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
                      'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
                      'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
                      'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
                      'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
                      'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                      'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
                      'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r'
                    )
                );
                $cpf = preg_replace("/[^0-9]/", "", (str_replace("'","",str_replace("/","",str_replace("-","",str_replace(".","",$line->cpf))))));
                $line->cpf_cnpj = $cpf;
                if(strlen($cpf)==11){
                    $line->pf_pj=1;
                }
                else{
                    $line->pf_pj=2;
                }
                $retorno[$tipo_array][]=$line;
            }
            return $retorno;
        }
    }

    public function update_remessa($contas,$data,$arquivo) {
        $data= array(
            'data_remessa'=>$data,
            'arquivo_remessa'=>$arquivo,
            'status_remessa'=>'1'
        );

        foreach($contas as $conta){
            $this->db->where('id',$conta->conta_id);
            $this->db->update('cad_contas',$data);
        }
    }

    public function update_remessa_instaladores($contas,$data,$arquivo) {
        $data= array(
            'data_remessa'=>$data,
            'arquivo_remessa'=>$arquivo,
            'status_remessa'=>'1'
        );

        foreach($contas['corrente'] as $conta){
            $this->db->where('id',$conta->conta_id);
            $this->db->update('cad_contas',$data);
        }
        foreach($contas['poupanca'] as $conta){
            $this->db->where('id',$conta->conta_id);
            $this->db->update('cad_contas',$data);
        }
        foreach($contas['ted'] as $conta){
            $this->db->where('id',$conta->conta_id);
            $this->db->update('cad_contas',$data);
        }
    }

    public function getCategorias() {
        $this->db->where('status', 1)->order_by('nome', 'ASC');
        $query = $this->db->get('systems.categoria_contas_apagar');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $categ) {
                $categorias[] = $categ->nome;
            }
            return $categorias;
        }
        return array();
    }

    public function get_all_accountInst($where = array('empresa' => 1)) {
        return $this->db->order_by('id', 'DESC')
            ->where('status', 0)
            ->get_where('showtecsystem.cad_contas', $where)
            ->result();
    }

    public function get_all($where = array('empresa' => 1), $start = 0, $limit = 10000000000000, $search = NULL) {
        $this->db->where($where);
        $this->db->where('status_aprovacao', 1);

        if ($search && is_numeric($search)) {
            $this->db->where("id LIKE '%".$search."%'");
        } else if ($search && is_string($search)) {
            $this->db->where("(descricao LIKE '%".$search."%' OR fornecedor LIKE '%".$search."%')");
        }


        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('showtecsystem.cad_contas')->result();
    }

    public function count_all($where=array(), $search = NULL)
    {
        $this->db->where($where);
        $this->db->where('status_aprovacao', 1);

        if ($search && is_numeric($search)) {
            $this->db->where("id LIKE '%".$search."%'");
        } else if ($search && is_string($search)) {
            $this->db->where("(descricao LIKE '%".$search."%' OR fornecedor LIKE '%".$search."%')");
        }

        $this->db->order_by('id', 'DESC');
        return $this->db->count_all_results('cad_contas');
    }

    public function get_all_aprovacao($user, $admin) {
        $this->db->order_by('id', 'desc');
        if ($admin) {
            $sql = "select * from showtecsystem.cad_contas where status_aprovacao = 0 and status != 3 or status_aprovacao = 2 and status != 3 order by id desc;";
        } else {
            $sql = "select * from showtecsystem.cad_contas where status_aprovacao = 0 and user_cad = '".$user."' and status != 3 or status_aprovacao = 2 and user_cad = '".$user."' and status != 3 order by id desc;";
        }
        
        return $this->db->query($sql)->result();
    }

    /*
    * Retorna a quantidade de registros
    */
    public function countAll_filter($where=array(), $search = null)
    {
        $this->db->where($where);

        if ($search && is_numeric($search)) {
            $this->db->where("id LIKE '%".$search."%'");
        } else if ($search && is_string($search)) {
            $this->db->where("(descricao LIKE '%".$search."%' OR fornecedor LIKE '%".$search."%')", NULL, FALSE);
        }

        $this->db->order_by('dh_lancamento', 'ASC', NULL, FALSE);
        return $this->db->count_all_results('cad_contas');
    }

    public function get_all_inst($where=array(), $start = 0, $limit = 10, $search = null) {
        $this->db->where($where);

        if ($search && is_numeric($search)) {
            $this->db->where("id LIKE '%".$search."%'", NULL, FALSE);
        } else if ($search && is_string($search)) {
            $this->db->where("(descricao LIKE '%".$search."%' OR fornecedor LIKE '%".$search."%')", NULL, FALSE);
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('dh_lancamento', 'ASC');
        return $this->db->get('cad_contas')->result();
    }

    public function get_fluxo ($fluxo = 'showtecsystem.fluxo_caixa') {
        return $this->db->get($fluxo)->row(0);
    }

    public function addCaixaConsolidado($emp, $valor) {
        $this->db->insert('showtecsystem.consolidado_caixa', array('referencia' => date('m/Y'), 'caixa_total' => $valor, 'data_update' => date('Y-m-d H:i:s'), 'empresa' => $emp));
    }

    public function get_bills($date_start, $date_end, $provider, $status, $empresa, $categoria) {
        $column = 'id';
        $order = 'asc';
        $this->db->select('*');
        if ($provider)
            $this->db->like('fornecedor', $provider);
        if ($categoria)
            $this->db->where('categoria', $categoria);

        // SEPARA POR EMPRESA
        switch ($empresa) {
            case 'Norio':
                $id_empresa = 3;
                break;

            case 'PneuShow':
                $id_empresa = 2;
                break;

            case 'ShowTecnologia':
                $id_empresa = 1;
                break;
            
            default:
                $id_empresa = array(1,2,3);
                break;
        }
        $this->db->where_in('empresa', $id_empresa);
        foreach ($status as $value) {
            switch ($value) {
                case 0:
                    $dates = array('data_vencimento >=' => $date_start, 'data_vencimento <=' => $date_end);
                    $this->db->where($dates);
                    $this->db->where('status', 0);
                    break;
                case 1:
                    $dates = array('data_pagamento >=' => $date_start, 'data_pagamento <=' => $date_end);
                    $this->db->where($dates);
                    $this->db->where('status', 1);
                    break;
                case 3:
                    $dates = array('data_vencimento >=' => $date_start, 'data_vencimento <=' => $date_end);
                    $this->db->where($dates);
                    $this->db->where('status', 3);
                    break;
            }
        }
        $this->db->order_by($column, $order);
        $query = $this->db->get('cad_contas');

        if($query->num_rows() > 0)
            return $query->result();
        return array();
    }

    public function update($data) {
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $teste = $this->db->update('showtecsystem.cad_contas', $data, array('id' => $id));
            return $this->db->update('showtecsystem.cad_contas', $data, array('id' => $id));
        } else {
            return false;
        }
    }

    public function verifyEditValueAccount($id = false, $valor) {
        if ($id && is_numeric($id)) {
            if ($valor)
                $this->db->where('valor !=', $valor);
            return $this->db->where(array('id' => $id, 'status_aprovacao' => 1, 'status' => 0))
                ->from('showtecsystem.cad_contas')
                ->count_all_results();
        } else {
            return false;
        }
    }

    public function addMsgAccount($dados) {
        return $this->db->insert('showtecsystem.comments_account', $dados);
    }

    public function confirmNotificationAccount($id, $user) {
        $this->db->where(array('notification' => '0', 'user !=' => $user, 'id_account' => $id));
        $this->db->update('showtecsystem.comments_account', array('notification' => '1'));
    }

    public function getNotifyByUser($user, $admin = false) {
        $this->db->select('notify.*');
        $this->db->from('showtecsystem.cad_contas as cad');
        $this->db->join('showtecsystem.comments_account as notify', 'notify.id_account = cad.id', 'inner');
        if (!$admin) {
            $this->db->where(array('cad.user_cad' => $user, 'notify.notification' => '0', 'notify.user !=' => $user));
        } else {
            $this->db->where(array('notify.notification' => '0', 'notify.user !=' => $user));
        }

        return $this->db->get()->result();
    }

    public function getMsgByAccount($id) {
        return $this->db->get_where('showtecsystem.comments_account', array('id_account' => $id))->result();
    }

    public function get_files($prefixo) {
        $this->db->where('ndoc', $prefixo);
        $this->db->where('pasta', 'contas');
        $query = $this->db->get('showtecsystem.arquivos');
        if ($query->num_rows())
            return $query->result();
        return false;
    }

    public function digitalizacao($contrato, $descricao, $nome_arquivo,$tem_comprovante, $path) {

        $comprovante = $tem_comprovante ? $tem_comprovante : 0;

        $pasta = "contas";
        $dados = array(
            'file' => $nome_arquivo,
            'descricao' => $descricao,            
            'pasta' => $pasta,
            'ndoc' => $contrato,
            'path' => $path
        );
        
        $resposta = $this->db->insert('showtecsystem.arquivos', $dados);
    
        $last_id =  $this->db->insert_id();
        
        if ($resposta) {
            if($comprovante == 1 ){
                $this->db->update('showtecsystem.cad_contas', array('tem_comprovante' => 1,'arquivo'=> 1), array('id' => $contrato));
            }
            else{
                $this->db->update('showtecsystem.cad_contas', array('arquivo' => 1), array('id' => $contrato));
            }
            
            return array('id' => $last_id, 'descricao' => $descricao, 'file' => $nome_arquivo);
        } else {
            return false;
        }
    }

    public function add_entrada($entrada){
        try {
            if ($this->db->affected_rows() > 0) {
                return $this->db->insert_id();
            } else {
                throw new Exception("Erro ao inserir no banco de dados.");
            }
        } catch (Exception $e) {
            error_log('Falha ao adicionar a conta: ' . $e->getMessage());
            return 0;
        }
        
    }

    public function add_conta($data) {
        try {
            $this->db->insert('contas', $data);
            
            if ($this->db->affected_rows() > 0) {
                return array('success' => true, 'message' => 'Conta adicionada com sucesso!');
            } else {
                return array('success' => false, 'message' => 'Falha ao adicionar a conta.');
            }
        } catch (Exception $e) {
            return array('success' => false, 'message' => 'Erro ao adicionar a conta: ' . $e->getMessage());
        }
    }

    public function get_all_entradas($where = array()) {
        $query = $this->db->select('*')
            ->where($where)
            ->order_by('data', 'DESC')
            ->get('entradas_show');

        if($query->num_rows() > 0)
            return $query->result();
        return array();
    }

    public function delete_entrada_show($where) {
        $this->db->delete('entradas_show', $where);
        return $this->db->affected_rows();
    }

    public function add_entrada_norio($entrada){
        $this->db->insert('showtecsystem.entradas_norio', $entrada);
    }

    public function get_norio_entradas($where = array()) {
        $query = $this->db->select('*')
            ->where($where)
            ->order_by('data', 'DESC')
            ->get('showtecsystem.entradas_norio');
        if($query->num_rows() > 0)
            return $query->result();
        return array();
    }

    public function delete_entrada_norio($where) {
        $this->db->delete('showtecsystem.entradas_norio', $where);
        return $this->db->affected_rows();
    }

    public function delete_contas_eua($where){
        $this->db->delete('showtecsystem.arquivos', $where);
        return $this->db->affected_rows();
    }

    public function verify($where) {
       return $this->db
            ->where($where)
            ->where('status', 0)
            ->where('status_aprovacao', 1)
            ->get('showtecsystem.cad_contas')
            ->result();
    }

//    public function updateForUnify($id){
//        $this->db->where('id', $id)
//            ->update('showtecsystem.cad_contas', array('status_aprovacao' => 2, 'status' => 0));
//    }

    public function updateForAdd($id, $dados) {
        $this->db->where('id', $id)
            ->update('showtecsystem.cad_contas', $dados);
    }
    public function funcionarios(){
        return $this->db->where('status', '1')
                ->order_by('nome')
                ->get('showtecsystem.usuario')
                ->result();
    }
    public function conta_funcionario($funcionario){
        return $this->db->where('cad_retorno', 'usuario')
                ->where('status', '1')
                ->where('id_retorno', $funcionario)
                ->get('showtecsystem.cad_contasbank')
                ->row();
    }
    public function conta_fornecedor($fornecedor){
        return $this->db->where('cad_retorno', 'fornecedor')
                ->where('status', '1')
                ->where('id_retorno', $fornecedor)
                ->get('showtecsystem.cad_contasbank')
                ->row();
    }
    public function baixa_novo_retorno($contas_retorno) {
        $contas_execs = array();
        if (count($contas_retorno) > 0) {
            $msg_erro = '';
            foreach ($contas_retorno as $conta_retorno) {
                $contas_execs = $contas_retorno;
                if(substr($conta_retorno->status_pagamento,0,2)=="BD"){
                    $this->db->where('id',$conta_retorno->contas_numero_retorno);
                    $conta_num=$this->db->get('cad_contas')->result();
                    if (count($conta_num) > 0) {
                        $d_update = array('valor_pago' => $conta_retorno->valorpago_retorno,
                            'data_pagamento' => $conta_retorno->datapagto_retorno,'status_remessa' => 2);
                        $this->db->where('id',$conta_retorno->contas_numero_retorno);
                        $this->db->update('cad_contas',$d_update);
                    }
                }
                elseif(substr($conta_retorno->status_pagamento,0,2)=="00"){
                    $this->db->where('id',$conta_retorno->contas_numero_retorno);
                    $conta_num=$this->db->get('cad_contas')->result();
                    if (count($conta_num) > 0) {
                        $d_update = array('valor_pago' => $conta_retorno->valorpago_retorno,
                            'data_pagamento' => $conta_retorno->datapagto_retorno,'status_remessa' => 2,
                            'status' => 1);
                        $this->db->where('id',$conta_retorno->contas_numero_retorno);
                        $this->db->update('cad_contas',$d_update);
                    }
                }
            }
        }
        return $contas_execs;
    }
}
