<?php

$idquestionario     = $dados[0];
$idfuncionario      = $dados[1];
$idpergunta         = $dados[2];
$resposta           = $dados[3];

echo $idquestionario.' '.$idfuncionario.' '.$idpergunta. ' '.$resposta;

$verifica_itens = $this->db->query("SELECT * FROM plano_de_voo_respostas WHERE id_questionario = '$idquestionario' AND id_funcionario = '$idfuncionario'
AND id_pergunta = '$idpergunta'");

if ($verifica_itens->num_rows() > 0) {
    $update_resposta = $this->db->query("UPDATE plano_de_voo_respostas SET resposta = '$resposta', data = NOW() WHERE id_questionario = '$idquestionario' AND id_funcionario = '$idfuncionario'
    AND id_pergunta = '$idpergunta'");
}else{
    $insert_resposta = $this->db->query("INSERT INTO plano_de_voo_respostas SET id_questionario = '$idquestionario', id_funcionario = '$idfuncionario',
    id_pergunta = '$idpergunta', resposta = '$resposta', data = NOW()");
}
