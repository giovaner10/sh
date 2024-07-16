<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Formulario_informacao extends CI_Model
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model("arquivo");
	}

	public function getPoliticasFormularios($where = [])
	{
		$query = $this->db
            ->select('informacao.*, arquivos.file, arquivos.path')
            ->where([
                "informacao.status" => "ativo",
                "arquivos.status" => "ativo"
            ])
            ->where($where)
            ->order_by("informacao.id", "DESC")
            ->join('showtecsystem.arquivos as arquivos', 'informacao.id_arquivos = arquivos.id', 'inner')
            ->get('showtecsystem.cad_formularios_informacoes AS informacao');
	    
	    return $query->result();
	}

    public function getPoliticaFormulario($id)
	{
	    $query = $this->db->get_where("cad_formularios_informacoes", ["id" => $id]);
	    
	    return $query->result()[0];
	} 
    
    public function adicionarFormularioInformacao($descricao, $arquivoNome, $arquivo, $tipo, $departamentoId)
    {
        $pasta = "politica_formulario";
        $dataAtual = date("Y-m-d H:i:s");

        # Add arquivo
        $arquivoId = $this->arquivo->adicionar([
            'file' => $arquivoNome,
            'descricao' => $descricao,
            'pasta' => $pasta,
            'ndoc' => '',
            'path' => $arquivo
        ]);

        $dadosFormularioInformacao = array(
            'id_departamentos' => $departamentoId,
            'tipo' => $tipo,
            'descricao' => $descricao,
            'id_arquivos' => $arquivoId,
            'data_criacao' => $dataAtual
        );

        if (!$this->db->insert('showtecsystem.cad_formularios_informacoes', $dadosFormularioInformacao))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarFormularioInformacao($formularioInformacaoId, $descricao, $arquivoNome, $arquivo, $tipo, $departamentoId)
    {
        $pasta = "politica_formulario";

        # Sem mudança de arquivo
        if(!$arquivo)
        {
            $dadosInfo = array(
                'id_departamentos' => $departamentoId,
                'tipo' => $tipo,
                'descricao' => $descricao
            );
        }
        # Com mudança de arquivo
        else
        {
            # Get id arquivo do formulario
            $arquivoId = $this->getPoliticaFormulario($formularioInformacaoId)->id_arquivos;

            # Get arquivo antigo
            $arquivoAntigo = $this->arquivo->getArquivos(['id' => $arquivoId])[0];

            # Deleta arquivo antigo (Servidor)
            if (file_exists($arquivoAntigo->path))
                unlink($arquivoAntigo->path);

            # Deleta arquivo antigo (BD)
            $this->arquivo->excluir($arquivoAntigo->id);

            # Add novo arquivo
            $arquivoNovoId = $this->arquivo->adicionar([
                'file' => $arquivoNome,
                'descricao' => $descricao,
                'pasta' => $pasta,
                'ndoc' => '',
                'path' => $arquivo
            ]);

            $dadosInfo = array(
                'id_departamentos' => $departamentoId,
                'tipo' => $tipo,
                'descricao' => $descricao,
                'id_arquivos' => $arquivoNovoId
            );
        }

        if (!$this->db->update(
                'showtecsystem.cad_formularios_informacoes',
                $dadosInfo,
                ['id' => $formularioInformacaoId]
            )
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
    
    public function excluirFormularioInformacao($id)
    {
        # Inativa o comunicado
        if (!$this->db->update(
                'showtecsystem.cad_formularios_informacoes',
                ["status" => "inativo"],
                ['id' => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}