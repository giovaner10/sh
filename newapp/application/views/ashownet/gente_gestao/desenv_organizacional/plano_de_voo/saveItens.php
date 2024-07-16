<?php

$idarea                 = $_POST['idarea'];
$item                   = $_POST['item'];
$idchecklist            = $_POST['idchecklist'];
$tipoResposta           = $_POST['tipo'];

$verifica_itens = $this->db->query("SELECT item FROM plano_de_voo_itens WHERE id_area = '$idarea' AND id_questionario = '$idchecklist' AND item = '$item'");

if ($verifica_itens->num_rows() > 0) {
    echo "<div class='alert alert-danger'>Desculpe... essa pergunta já foi cadastrada</div>";
}else{
    $inseri_item = $this->db->query("INSERT INTO plano_de_voo_itens SET id_area = '$idarea', id_questionario = '$idchecklist', item = '$item',
    tipo = '$tipoResposta'");

    
    if($inseri_item){
        echo '<div class="alert alert-success">Dados inseridos com sucesso!</div>';
    } else {
        echo "<div class='alert alert-danger'>Erro ao inserir Item</div>";
    }
}
