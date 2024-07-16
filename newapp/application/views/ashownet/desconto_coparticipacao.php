<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_desconto_coparticipacao')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/listar_desconto_coparticipacao');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>RH</h5>
	<h2 class="TitPage">Desconto de Coparticipação</h2>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form method="post" name="frm" id="frm" action="">
						<?php 
						
						$nome = $this->auth->get_login('admin', 'nome');
						$query_usuario = $this->db->query("SELECT id, nome  FROM usuario WHERE nome = '$nome'");
						$row_usuario = $query_usuario->row();
						$nomefun = $row_usuario->nome;
						$idfuncionario = $row_usuario->id;
						
						$consulta = "";
						if($_POST){
						    $ano = date("Y");
						    $consulta = "AND YEAR(mes_competencia) = '$ano' AND MONTH(mes_competencia) = '$_POST[mesCpt]'";
						}else{
						    $ano = date("Y");
						    $mesS = date("m");
						    $consulta = "AND YEAR(mes_competencia) = '$ano' AND MONTH(mes_competencia) = '$mesS'";
						}
					
						$query = $this->db->query("SELECT d.*, nome FROM  cad_desconto_coparticipacao d, usuario u WHERE
                        d.id_funcionario=u.id AND id_funcionario = '$idfuncionario' $consulta GROUP BY id_funcionario");
						$row_func = $query->row();
						
						
						
						?>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<h5>
										<strong>Colaborador:</strong>&nbsp;<?php echo $nomefun;?>
									</h5>
								</div>
							</div>
						</div>
						<div class="btn-group pull-right">
							<table>
								<tr>
									<td style="padding: 0px 2px 0px 2px"><label>Selecione o mês de	competência (o mesmo da folha de pagamento):</label>&nbsp;</td>
									<td>
    									<select name="mesCpt" id="mesCpt" class="form-control col-lg-8" onChange="Buscar()">
    										<option value="">Selecione...</option>
    										<?php 
    										for($i = 01; $i <= 12; $i++){
    										    
    										    switch ($i) {
    										        case "01":    $mes = "Jan";     break;
    										        case "02":    $mes = "Fev";   break;
    										        case "03":    $mes = "Mar";       break;
    										        case "04":    $mes = "Abr";       break;
    										        case "05":    $mes = "Mai";        break;
    										        case "06":    $mes = "Jun";       break;
    										        case "07":    $mes = "Jul";       break;
    										        case "08":    $mes = "Ago";      break;
    										        case "09":    $mes = "Set";    break;
    										        case "10":    $mes = "Out";     break;
    										        case "11":    $mes = "Nov";    break;
    										        case "12":    $mes = "Dez";    break;
    										    }
    										    
    										    $selected = (isset($_POST['mesCpt']) && $_POST['mesCpt'] == $i) ? 'selected' : '';
    									
    										?>
    										<option value="<?=$i?>" <?=$selected?> ><?=$mes.'/'.date('Y')?></option>
    										<?php } ?>
    									</select>
									</td>
								</tr>
							</table>
						</div>
						<br>
						<br>
						
						<hr>	
						<?php 
						if($query->num_rows() != 0){
						?>					
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<h5>Confira abaixo o desconto de coparticipação que será
										realizado na folha de pagamento do mês selecionado:</h5>
								</div>
							</div>
						</div>
						<?php }else{ ?>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<h5>Não há desconto de coparticipação para mês selecionado:</h5>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php 
						if($query->num_rows() != 0){
						if(count($lista_dados) > 0) {?>
						<div class="form-group">
							<table class="table table-bordered table-hover col-lg-8">
								<thead>
									<tr>
										<th width="60%">NOME</th>
										<th>GRAU</th>
										<th>VALOR DA COPARTICIPAÇAO</th>
									</tr>
								</thead>
								
								<tr class="">
									<td><?php echo $row_func->nome;?></td>
									<td align="center">T</td>
									<td align="right"><strong><?php echo number_format($row_func->valor_funcionario,2,',','.');?></strong>&nbsp;</td>
								</tr>
            					<?php 
            					//var_dump($lista_dados);
            					$total = 0;
            					foreach ($lista_dados as $lista_dado){
            					    
            					    $total += $lista_dado->valor_dependente;           					    
            					   
            					?>
								
									<tr class="">
										<td><?php echo $lista_dado->dep_nome;?></td>
										<td align="center">D</td>
										<td align="right"><strong><?php echo number_format($lista_dado->valor_dependente,2,',','.');?></strong>&nbsp;</td>
									</tr>
																
								<?php } ?>
								<tfoot>
									<tr>
										<td colspan="2" align="right"><strong>Total</strong>&nbsp;</td>
										<td align="right"><strong><?php echo  number_format($total+$lista_dado->valor_funcionario,2,',','.');?></strong>&nbsp;</td>
									</tr>
								</tfoot>
							</table>

							<br>
							<p class="text-center">Os valores acima não contemplam
								parcelamento de meses anteriores.</p>
						</div>
						<?php }} ?>
						
						<div class="form-group text-center">
							<a href="<?php echo site_url('ashownet/adm_pessoal');?>" class="btn btn-default">&nbsp;&nbsp;Voltar&nbsp;&nbsp;</a>							
						</div>
					</form>

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->

	</div>

</div>
<script>
function Buscar(){
    //alert('SIM')
    
    var e = document.getElementById("mesCpt");
	var itemSelecionado = e.options[e.selectedIndex].text;

    document.frm.submit();		

}	
</script>