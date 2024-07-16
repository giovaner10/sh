<?php

$id    = $_POST['idarea'];
$area  = $_POST['nomeAreaEdit'];


$altera_area = $this->db->query("UPDATE plano_de_voo_areas SET area = '$area' WHERE id='$id'");

if($altera_area){
    echo '<div class="alert alert-success">Dados alterados com sucesso!</div>';
} else {
    echo "<div class='alert alert-danger'>Erro ao alterar área</div>";
}