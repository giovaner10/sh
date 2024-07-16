<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<?php
$idfuncionario = $_POST['idfuncionario'];

$query = $this->db->query("SELECT * FROM cad_colaborador_dependentes WHERE id_funcionario = '$idfuncionario'");

if ($query->num_rows() > 0) {

    foreach ($query->result_array() as $row) {
        ?>
<div class="form-group">
	<div class="col-sm-6">
		<input type="hidden"  name="iddependente[]" value="<?=$row['id']?>">
		<input type="text" class="form-control" name="dependente[]" value="<?=$row['dep_nome']?>" disabled="disabled">
		<input type="text" class="form-control dinheiro" name="valcoparticiacaodepen[]" value="" placeholder="valor dacoparticipação" required>
	</div>
</div>
<?php
    }
} else {
    ?>
<div class="form-group">
	<div class="col-sm-6">
		<input type="text" class="form-control" value="Não há dependentes cadastrados para o colaborador selecionado" disabled="disabled">
	</div>
</div>
<?php }?>
 <script type="text/javascript">
 $(".dinheiro").maskMoney();
 </script>