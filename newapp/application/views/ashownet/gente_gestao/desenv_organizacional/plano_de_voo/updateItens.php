<?php


$id = $_POST['iditem'];
$item = $_POST['item'];
$tipoResposta = $_POST['tipo'];
    
$atualiza_item = $this->db->query("UPDATE plano_de_voo_itens SET item = '$item', tipo = '$tipoResposta' WHERE id = '$id'");

if ($atualiza_item) {
    echo '<div class="alert alert-success">Dados atualizados com sucesso!</div>';
} else {
    echo "<div class='alert alert-danger'>Erro ao atualizar Pergunta</div>";
}