<?php


$id = $dados[0];
       
$deleta_item = $this->db->query("DELETE FROM plano_de_voo_itens WHERE id = '$id'");

if($deleta_item){
    echo '<div class="alert alert-success">Dados deletados com sucesso!</div>';
} else {
    echo "<div class='alert alert-danger'>Erro ao deletar item</div>";
}    