<?php

$id   = $_POST['idquestionario'];
$area = $_POST['nomeArea'];

$verifica_are = $this->db->query("SELECT * FROM plano_de_voo_areas WHERE id_questionario = '$id' AND area = '$area'");

if ($verifica_are->num_rows() > 0) {
    echo "<div class='alert alert-danger'>Essa área já foi cadastrada </div>";
}else{
   
    $cadastra_area = $this->db->query("INSERT INTO plano_de_voo_areas SET id_questionario = '$id', area = '$area'");
    
    if($cadastra_area){
        echo '<div class="alert alert-success">Cadastro realizado com sucesso!</div>';
    } else {
        echo "<div class='alert alert-danger'>Erro ao cadastrar área</div>";
    }
}