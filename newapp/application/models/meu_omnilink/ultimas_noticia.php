<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Ultimas_Noticia extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function listar()
    {
	    return $this->db
			->where('status', 'ativo')
            ->order_by('id DESC')
			->get('meu_omnilink.ultimas_noticias')
			->result();
	}

	public function buscarPorId($id)
	{
	    $query = $this->db->get_where('meu_omnilink.ultimas_noticias', ['id' => $id]);
	    
	    return $query->result()[0];
	}
	
    public function adicionar($dados)
    {
        $banner = [
            'titulo' => $dados['titulo'],
            'descricao' => $dados['descricao'],
			'conteudo_url' => $dados['conteudo_url'],
			'imagem_nome' => $dados['imagem_nome'],
			'imagem_diretorio' => $dados['imagem_diretorio'],
			'id_usuario' => $dados['id_usuario'],
			'data_hora_cadastro' => date('Y-m-d H:i:s')
		];

        if (!$this->db->insert('meu_omnilink.ultimas_noticias', $banner))
            throw new Exception(lang('mensagem_erro'));
    }

	public function editar($id, $dados)
    {
		$banner = [
			'titulo' => $dados['titulo'],
			'descricao' => $dados['descricao'],
			'conteudo_url' => $dados['conteudo_url'],
			'id_usuario' => $dados['id_usuario'],
			'data_hora_alteracao' => date('Y-m-d H:i:s')
		];

        if(isset($dados['imagem_nome']))
        {
			$banner['imagem_nome'] = $dados['imagem_nome'];
			$banner['imagem_diretorio'] = $dados['imagem_diretorio'];
        }

        if (!$this->db->update("meu_omnilink.ultimas_noticias", $banner, ["id" => $id])) {
            throw new Exception(lang("mensagem_erro"));
        }
    }

    public function excluir($id)
    {
        if (!$this->db->update("meu_omnilink.ultimas_noticias", ["status" => "inativo"], ["id" => $id])) {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}