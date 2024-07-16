<?php

$id = $dados[0];

$deleta_area = $this->db->query("DELETE FROM plano_de_voo_areas WHERE id = '$id'");

$deleta_areaItens = $this->db->query("DELETE FROM plano_de_voo_itens WHERE id_area = '$id'");

if($deleta_area || $deleta_areaItens){
    echo '<div class="alert alert-success">Dados deletados com sucesso!</div>';
} else {
    echo "<div class='alert alert-danger'>Erro ao deletar área</div>";
}