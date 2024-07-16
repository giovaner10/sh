<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ashownett extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get($where = null, $order = null, $sort = null, $limit = null, $offset = null)
	{
		if ($where) {
			$this->db->where($where);
		}

		if ($order) {
			$this->db->order_by($order, $sort ? $sort : 'asc');
		}

		if ($limit) {
			$this->db->limit($limit, $offset ? $offset : null);
		}

		$query = $this->db->get('showtecsystem.arquivos');

		return $query->result();
	}

	public function salvar($dados)
	{
		return $this->db->insert('showtecsystem.cad_sobre_empresa', $dados);
	}
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------

	public function atualizar($where, $dados){

		$this->db->update('showtecsystem.cad_sobre_empresa', $dados, $where);
		if($this->db->affected_rows() > 0)
			return true;

		return false;

	}


	public function getDados($tabela){

	    $query = $this->db->select('*')->get("showtecsystem.$tabela");

	    return $query->result();

	}

	public function getDadosEdit($tabela,$where){

	    $comunicado = false;
	    $query = $this->db->get_where($tabela, $where);

	    if($query->num_rows() > 0)
	        foreach($query->result() as $comunicado);

	        return $comunicado;

	}

	public function editDados($titulo, $descricao, $missao, $visao, $valores, $id, $tabela) {

        $dados = array(
            'titulo' => $titulo,
            'descricao' => $descricao,
            'missao' => $missao,
            'visao' => $visao,
            'valores' => $valores
        );

        $sobre = $this->db->update("showtecsystem.$tabela", $dados, array('id' => $id));

        if($sobre) {
	        return true;
	    }else{
	        return false;
	    }

	}

	public function editDadosCorrecaoIrrf($titulo, $descricao, $id) {

	    $dados = array(
	        'titulo' => $titulo,
	        'descricao' => $descricao
	    );

	    $sobre = $this->db->update("showtecsystem.cad_correcao_irrf", $dados, array('id' => $id));

	    if($sobre) {
	        return true;
	    }else{
	        return false;
	    }

	}

	public function editDadosGenteGestao($descricao, $id) {

	    $dados = array(
	        'descricao' => $descricao
	    );

	    $sobre = $this->db->update("showtecsystem.cad_gente_gestao", $dados, array('id' => $id));

	    if($sobre) {
	        return true;
	    }else{
	        return false;
	    }

	}

	public function getContatosCorporativos(){

	    $query = $this->db->select('*')->get('showtecsystem.cad_contatos_corporativos');

	    return $query->result();

	}

	//RETORNA DADOS DE ATEND. AO CLIENTE / PROJETOS DEDICADOS
	public function ajaxAtendimentoOuProjetos($tipo){
	    $query = $this->db->select('id, titulo, descricao')
		->where('tipo', $tipo)
		->get('showtecsystem.cad_contatos_corporativos');

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	//RETORNA DADOS DA MATRIZ E FILIAIS
	public function getDadosMatrizFiliais($loja){
	    $query = $this->db->select('id, cidade, uf, complemento, endereco, numero, bairro, cep, complemento, telefone, cnpj')
		->where('loja', $loja)
		->get('showtecsystem.cad_contatos_corporativos');

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}


	public function getFolhetos(){

	    $query = $this->db->select('*')->where("pasta = 'folhetos'")->get('showtecsystem.arquivos');

	    return $query->result();

	}

	public function getArquivos($pasta){

	    $query = $this->db->select('*')->where("pasta = '$pasta'")->get('showtecsystem.arquivos');

	    return $query->result();

	}

	public function getAssuntos($tabela){

	    $query = $this->db->select('*')->where("ativo = '1'")->order_by('id ASC')->get("showtecsystem.$tabela");

	    return $query->result();

	}

	public function getAssuntosEditar($where){

	    $assunto = false;
	    $query = $this->db->get_where('cad_assuntos', $where);

	    if($query->num_rows() > 0)
	        foreach($query->result() as $assunto);

	        return $assunto;

	}

	public function getAssuntosEditarCampanhas($where){

	    $assunto = false;
	    $query = $this->db->get_where('cad_assunto_campanhas', $where);

	    if($query->num_rows() > 0)
	        foreach($query->result() as $assunto);

	        return $assunto;

	}

	public function getInformacaoEditar($where, $tabela){

	    $informacao = false;
	    $query = $this->db->get_where("$tabela", $where);

	    if($query->num_rows() > 0)
	        foreach($query->result() as $informacao);

	        return $informacao;

	}

	public function getProdutoById($id,$id_produto){

	    $query = $this->db->select('p.id, p.assunto, a.file,a.path, i.descricao, a.id_produto')
	    ->join('showtecsystem.cad_produto_informacoes_arquivos as a', 'p.id=a.id_assunto', 'inner')
		->join('showtecsystem.cad_produtos_informacoes as i', 'i.id_assunto=a.id_assunto', 'inner')
		->where("p.ativo = '1' and p.id = ".$id." and i.id = ".$id_produto)
	    ->get('showtecsystem.cad_assunto_produtos as p');

	    return $query->result();

	}
	
	
	public function getProdutos(){

	    $query = $this->db->select('p.id, p.assunto, a.file, a.id_produto')
	    ->join('showtecsystem.cad_produto_informacoes_arquivos as a', 'p.id=a.id_assunto', 'inner')
	    ->where("p.ativo = '1'")
	    ->group_by('p.id')
	    ->get('showtecsystem.cad_assunto_produtos as p');

	    return $query->result();

	}
	public function getProdutosAll(){

	    $query = $this->db->select('p.id, p.assunto, a.file, a.id_produto')
	    ->join('showtecsystem.cad_produto_informacoes_arquivos as a', 'p.id=a.id_assunto', 'inner')
	    ->where("p.ativo = '1'")
	    ->get('showtecsystem.cad_assunto_produtos as p');

	    return $query->result();

	}

	public function getProdutosView($id_assunto){
		//die(var_dump($id_assunto));
	    $query = $this->db->select('a.id, a.id_assunto,a.id_produto,a.file,a.path,s.assunto, i.descricao')
	    ->order_by('i.id', 'DESC')
	    ->join('showtecsystem.cad_produto_informacoes_arquivos as a', 'i.id = a.id_produto', 'inner')
	    ->join('showtecsystem.cad_assunto_produtos as s', 'i.id_assunto = s.id', 'inner')
	    ->where("i.id_assunto = '$id_assunto' AND  i.id_assunto=s.id AND i.ativo = '1'")
	    ->get('showtecsystem.cad_produtos_informacoes as i');

	    return $query->result();

	}
	public function addNewProduto($assunto,$descricao, $nome_do_arquivo,$path){
		$json = null;
		$verificaAssunto = $this->db->get_where('showtecsystem.cad_assunto_produtos', array('assunto' => $assunto))->num_rows();
		
		if($verificaAssunto >0){
			$json = array('success' => false, 'msg'=>"Já existe um produto com esse nome cadastro");
			return $json;
		}else{
			$dataAtual = date("Y-m-d H:i:s");
			$dados = array(
				'assunto' => $assunto,
				'data_criacao' => $dataAtual,
				'ativo' => '1'
			);
	
			$this->db->insert('showtecsystem.cad_assunto_produtos', $dados);
			$id_cad_assunto = $this->db->insert_id();
			//die(json_encode(($id_cad_assunto)));
			if($id_cad_assunto){
				$dados = array(
					'id_assunto' => $id_cad_assunto,
					'descricao' => $descricao,
					'data_criacao' => $dataAtual,
					'ativo' => '1'
				);
				$this->db->insert('showtecsystem.cad_produtos_informacoes', $dados);
				$id_cad_produto = $this->db->insert_id();
				if($id_cad_produto){
					$dados = array(
						'id_produto' => $id_cad_produto,
						'id_assunto' => $id_cad_assunto,
						'file' => $nome_do_arquivo,
						'pasta' => 'produtos',
						'path' => $path
					);
					$this->db->insert('showtecsystem.cad_produto_informacoes_arquivos', $dados);
					$id_cadastro = $this->db->insert_id();
					if($id_cadastro){
						$json = array('success' => true, 'msg'=>"Produto cadastrado com sucesso", 'id'=>$id_cadastro, 'id_produto'=>$id_cad_produto, 'id_assunto'=>$id_cad_assunto);
						return $json;
					}else{
						$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto" ,'tabela'=>'showtecsystem.cad_produto_informacoes_arquivos');
						return $json;
					}
				}else{
					$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto", 'tabela'=>'showtecsystem.cad_produtos_informacoes');
					return $json;
				}

			}else{
				$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto", 'tabela'=>'showtecsystem.cad_assunto_produtos');
				return $json;
			}
		}
	}
	public function addEditProduto($id,$assunto,$descricao, $nome_do_arquivo,$path){
		$json = null;
			
		$this->db->select('id_assunto');
		$this->db->where('id',$id);
		$id_assunto_aux = $this->db->get('showtecsystem.cad_produtos_informacoes')->result();
		
		$id_assunto = $id_assunto_aux[0]->id_assunto;

			$dataAtual = date("Y-m-d H:i:s");
			$dados = array(
				'assunto' => $assunto,
				'data_criacao' => $dataAtual,
				'ativo' => '1'
			);
			$this->db->where("id", $id_assunto);
			if($this->db->update('showtecsystem.cad_assunto_produtos', $dados)){
				$dados = array(
					'descricao' => $descricao,
					'data_criacao' => $dataAtual,
					'ativo' => '1'
				);
				$this->db->where("id", $id);
				if($this->db->update('showtecsystem.cad_produtos_informacoes', $dados)){
					$dados = array(
						'file' => $nome_do_arquivo,
						'pasta' => 'produtos',
						'path' => $path
					);
					$this->db->where(array('id_produto' => $id,'id_assunto' => $id_assunto));
					if($this->db->update('showtecsystem.cad_produto_informacoes_arquivos', $dados)){
						$json = array('success' => true, 'msg'=>"Produto cadastrado com sucesso", 'id_produto'=>$id, 'id_assunto'=>$id_assunto);
						return $json;
					}else{
						$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto" ,'tabela'=>'showtecsystem.cad_produto_informacoes_arquivos');
						return $json;
					}
				}else{
					$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto", 'tabela'=>'showtecsystem.cad_produtos_informacoes');
					return $json;
				}

			}else{
				$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto", 'tabela'=>'showtecsystem.cad_assunto_produtos');
				return $json;
			}
		
	}
	public function addNewArqProduto($id_assunto, $descricao, $file, $path){
		$dataAtual = date("Y-m-d H:i:s");
		$dados = array(
			'id_assunto' => $id_assunto,
			'descricao' => $descricao,
			'data_criacao' => $dataAtual,
			'ativo' => '1'
		);
		$this->db->insert('showtecsystem.cad_produtos_informacoes', $dados);
		$id_cad_produto = $this->db->insert_id();
		if($id_cad_produto){
			$dados = array(
				'id_produto' => $id_cad_produto,
				'id_assunto' => $id_assunto,
				'file' => $file,
				'pasta' => 'produtos',
				'path' => $path
			);
			$this->db->insert('showtecsystem.cad_produto_informacoes_arquivos', $dados);
			$id_cadastro = $this->db->insert_id();
			if($id_cadastro){
				$json = array('success' => true, 'msg'=>"Produto cadastrado com sucesso",  'id_produto'=>$id_cad_produto, 'id_assunto'=>$id_assunto);
				return $json;
			}else{
				$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto" ,'tabela'=>'showtecsystem.cad_produto_informacoes_arquivos');
				return $json;
			}
		}else{
			$json = array('success' => false, 'msg'=>"Erro ao cadastrar novo produto", 'tabela'=>'showtecsystem.cad_produtos_informacoes');
			return $json;
		}

	}
	public function deleteArqProduto($id){
		$query = $this->db->select('a.id, a.id_assunto,a.id_produto,a.file,a.path,s.assunto, i.descricao')
	    ->order_by('i.id', 'DESC')
	    ->join('showtecsystem.cad_produto_informacoes_arquivos as a', 'i.id = a.id_produto', 'inner')
	    ->join('showtecsystem.cad_assunto_produtos as s', 'i.id_assunto = s.id', 'inner')
	    ->where("a.id = '$id' AND  i.id_assunto=s.id AND i.ativo = '1'")
	    ->get('showtecsystem.cad_produtos_informacoes as i');
		$row = $query->result();
		//die(json_encode(var_dump($row[0]->file)));
		$json = null;
		if(!empty($row) || $row !=null){
			$file = $row[0]->file;
			$id_produto = $row[0]->id_produto; 
			$this->db->delete('showtecsystem.cad_produto_informacoes_arquivos', array('id'=> $id));
			$this->db->delete('showtecsystem.cad_produtos_informacoes', array('id'=> $id_produto));
			//return $this->db->where('id', $id)->delete('arquivos');
			$json = array('success' => true, 'msg'=>"Arquivo deletado com sucesso",  'file'=>$file);
			return $json;
		}else{
			$json = array('success' => false, 'msg'=>"Não foi encontrado nenhum arquivo");
			return $json;
		}

	}

	public function arqsToDeleteProduto($id){
		$this->db->select('file');
		$this->db->from('showtecsystem.cad_produto_informacoes_arquivos');
		$this->db->where('id_assunto',$id);
		return $this->db->get()->result_array();
	}
	
	public function deleteProduto($id){
		$json = null;
		$this->db->delete('showtecsystem.cad_produto_informacoes_arquivos', array('id_assunto'=> $id));
		$this->db->delete('showtecsystem.cad_produtos_informacoes', array('id_assunto'=> $id));
		$this->db->delete('showtecsystem.cad_assunto_produtos', array('id'=> $id));
		$json = array('success' => true, 'msg'=>"Produto deletado com sucesso");
			return $json;

	}

	public function getDadosEditDesconto($id, $mes){

	    if($mes != ""){
            $ano = date("Y");
            $consulta = "AND YEAR(mes_competencia) = '$ano' AND MONTH(mes_competencia) = '$mes'";
		}else{
    	    $ano = date("Y");
    	    $mesS = date("m");
    	    $consulta = "AND YEAR(mes_competencia) = '$ano' AND MONTH(mes_competencia) = '$mesS'";
    	}

	    $query = $this->db->select('*')
	    ->order_by('d.id', 'DESC')
	    ->join('showtecsystem.cad_colaborador_dependentes as u', 'u.id = d.id_dependente', 'inner')
	    ->where("d.id_funcionario = '$id' $consulta")
	    ->get('showtecsystem.cad_desconto_coparticipacao as d');

	    return $query->result();

	}

	public function getDadosAtividades($id){

	    $query = $this->db->select('u.nome, a.*')
	    ->join('showtecsystem.usuario as u', 'u.id = a.id_funcionario', 'inner')
		->where("a.id_funcionario = '$id'")
	    ->get('showtecsystem.cad_atividades as a');

	    return $query->result();

	}

	public function listaDadosAtividades(){

	    $query = $this->db->select('u.nome, a.*')
	    ->order_by('a.id DESC')
	    ->join('showtecsystem.usuario as u', 'u.id = a.id_funcionario', 'inner')
	    ->get('showtecsystem.cad_atividades as a');

	    return $query->result();

	}

	public function getAssuntosProdutos(){

	    $query = $this->db->select('*')->where("ativo = '1'")->order_by('assunto ASC')->get('showtecsystem.cad_assunto_produtos');

	    return $query->result();

	}

	public function getApresentacao($tabela){

	    $query = $this->db->select('*')->get("showtecsystem.$tabela");

	    return $query->result();

	}

	public function excluirApresentacaoById($id, $tabela) {
	    return $this->db->where('id', $id)->delete($tabela);
	}

	public function getDadosUsuarios(){

	    $query = $this->db->select('*')->where("status = '1'")->order_by('nome ASC')->get("showtecsystem.usuario");

	    return $query->result();

	}

	public function getArquivosIso(){

	    $query = $this->db->select('*')->where("pasta = 'iso'")->get('showtecsystem.arquivos');

	    return $query->result();

	}

	public function getArquivosComercial(){

	    $query = $this->db->select('*')->where("pasta = 'comercial'")->get('showtecsystem.arquivos');

	    return $query->result();

	}

	// public function getFuncionarios(){
		
	// 	$search = NULL;
	// 	$order_column = NULL;
	// 	$order_dir = NULL;
	// 	$column_search = array("showtecsystem.usuario.nome", "showtecsystem.usuario.ocupacao","showtecsystem.usuario.login");
	// 	$column_order = array("showtecsystem.usuario.nome", "showtecsystem.usuario.ocupacao","showtecsystem.usuario.login");
	// 	$order = $this->input->post("order");
	// 	$length = $this->input->post("length");
	// 	$start = $this->input->post("start");
		
	// 	if ($this->input->post("search")) {
	// 		$search = $this->input->post("search")["value"];
	// 	}
		
		
	// 	if (isset($order)) {
	// 		$order_column = $order[0]["column"];
	// 		$order_dir = $order[0]["dir"];
	// 	}
	// 	$this->db->select("showtecsystem.usuario.nome,showtecsystem.usuario.ocupacao,showtecsystem.usuario.login");
	// 	$this->db->from("showtecsystem.usuario");

	// 	if (isset($search)) {
	// 		$first = TRUE;
	// 		foreach ($this->column_search as $field) {
	// 			if ($first) {
	// 				$this->db->group_start();
	// 				$this->db->like($field, $search);
	// 				$first = FALSE;
	// 			} else {
	// 				$this->db->or_like($field, $search);
	// 			}
	// 		}
	// 		if (!$first) {
	// 			$this->db->group_end();
	// 		}
			
	// 	}

	// 	if (isset($order)) {
	// 		$this->db->order_by($this->column_order[$order_column], $order_dir);
	// 	}
	// 	// $query = $this->db->where($where)
	// 	// 				  ->order_by($campo_ordem, $ordem)
	// 	// 				  ->get('usuario', $limite, $paginacao);
		
		
	// 	if (isset($length) && $length != -1) {
	// 		$this->db->limit($length, $start);
	// 	}
	// 	return $this->db->get()->result();
	// }

}
