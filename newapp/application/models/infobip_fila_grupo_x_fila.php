<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Infobip_Fila_Grupo_X_Fila extends CI_Model
{
    function __construct()
    {
		parent::__construct();
	}

    public function get($where = [], $select = null, $returnType = "object", $whereInColumn = "", $whereInValue = [])
    {
        if ($where)
            $this->db->where($where);
        if ($select)
            $this->db->select($select);
        if ($whereInColumn && $whereInValue)
            $this->db->where_in($whereInColumn, $whereInValue);
        if ($returnType == "object")
            return $this->db->get("infobip.filas_grupos_x_filas")->result();
        else
            return $this->db->get("infobip.filas_grupos_x_filas")->result_array();
    }

    public function adicionar($dados)
    {
        if (!$this->db->insert("infobip.filas_grupos_x_filas", $dados))
            throw new Exception(lang("mensagem_erro"));
     
        return $this->db->insert_id();
    }

    public function editar($id, $dados)
    {
        if (!$this->db->update("infobip.filas_grupos_x_filas", $dados, ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }

    public function excluir($id)
    {
        if (!$this->db->update("infobip.filas_grupos_x_filas", ["status" => "inativo"], ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarEmLotePorFilas($ids, $dados)
    {
        $this->db->where_in("id", $ids);

        if (!$this->db->update("infobip.filas_grupos_x_filas", $dados))
            throw new Exception(lang("mensagem_erro"));
    }

    public function excluirEmLotePorFilas($ids)
    {
        $this->db->where_in("id", $ids);

        if (!$this->db->update("infobip.filas_grupos_x_filas", ["status" => "inativo"]))
            throw new Exception(lang("mensagem_erro"));
    }

    public function adicionarPorGrupo($filaGrupoId, $filasIds)
    {
        $dados = [];
        $x = 0;
        foreach ($filasIds as $filaId)
        {
            $dados[$x]["id_filas_grupos"] = $filaGrupoId;
            $dados[$x]["codigo_filas"] = $filaId;
            $x++;
        }

        if (!$this->db->insert_batch("infobip.filas_grupos_x_filas", $dados))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editarPorGrupo($filaGrupoId, $filasIds)
    {
        # grupos de filas
        $filasGruposBD = $this->get(
            [ # Where
                "id_filas_grupos" => $filaGrupoId
            ],
            "codigo_filas", # Select
            "array" # Tipo de retorno
        );
        # Formata array para - indice => valor ([[0] => 123, [1] => 345])
        $filasGruposIdsBD = array_column($filasGruposBD, "codigo_filas");

        # grupos de filas ativos
        $filasGruposAtivosBD = $this->get(
            [ # Where
                "id_filas_grupos" => $filaGrupoId,
                "status" => "ativo"
            ],
            "codigo_filas", # Select
            "array" # Tipo de retorno
        );
        # Formata array para - indice => valor ([[0] => 123, [1] => 345])
        $filasGruposAtivosIdsBD = array_column($filasGruposAtivosBD, "codigo_filas");

        # Exclui registros
        $filasIdsExclusao = array_diff($filasGruposAtivosIdsBD, $filasIds);
        if ($filasIdsExclusao)
        {
            # get filas_grupos_x_filas - ids
            $filasGruposXFilas = $this->get(
                [ # Where
                    "id_filas_grupos" => $filaGrupoId
                ],
                "id", # Select
                "array", # Tipo de retorno
                "codigo_filas", # Column Where in
                $filasIdsExclusao # Where in
            );
            # Formata array para - indice => valor ([[0] => 123, [1] => 345])
            $filasGruposXFilasIds = array_column($filasGruposXFilas, "id");
            
            $this->excluirEmLotePorFilas($filasGruposXFilasIds);
        }

        # Adiciona registros
        $filasIdsAdicao = array_diff($filasIds, $filasGruposIdsBD);
        if ($filasIdsAdicao)
        {
            $this->adicionarPorGrupo(
                $filaGrupoId,
                $filasIdsAdicao
            );
        }

        # Filas que o usuário selecionou e tem no banco
        $grupoFilasEmComumIds = array_intersect($filasIds, $filasGruposIdsBD);

        if ($grupoFilasEmComumIds)
        {
            # Filtra somente as inativas
            $filasGruposReativar = $this->get(
                [ # Where
                    "id_filas_grupos" => $filaGrupoId,
                    "status" => "inativo"
                ],
                "id", # Select
                "array", # Tipo de retorno
                "codigo_filas", # Column Where in
                $grupoFilasEmComumIds # Where in
            );
            # Formata array para - indice => valor ([[0] => 123, [1] => 345])
            $filasGruposReativarIds = array_column($filasGruposReativar, "id");

            if ($filasGruposReativar)
            {
                # Reativa as filas
                $this->editarEmLotePorFilas(
                    $filasGruposReativarIds,
                    ["status" => "ativo"]
                );
            }
        }
    }

    public function excluirPorGrupo($filaGrupoId)
    {
        if (!$this->db->update(
            "infobip.filas_grupos_x_filas", # Tab
            ["status" => "inativo"], # Set
            ["id_filas_grupos" => $filaGrupoId]) # Where
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

}