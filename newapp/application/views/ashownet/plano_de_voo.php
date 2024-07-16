<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_plano_de_voo')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/listar_plano_de_voo');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Gestão de Carreira</h5>
	<h2 class="TitPage">Plano de Voo</h2>
	<?php if (count($planos) > 0) {	?>
	<div class="row">
		<div class="box box-info">
			<div class="box-body">
				<div class="panel-body">
					<div id="topo">
						<div class="form-group">
							<div class="row">
								<?php 
								$nome = $this->auth->get_login('admin', 'nome');
								$query_usuario = $this->db->query("SELECT id, nome, cpf FROM usuario WHERE nome = '$nome'");
								
								$row_usuario = $query_usuario->row();								
								$idfuncionario = $row_usuario->id;
								?>
								<div class="col-xs-8">
									<h4>
										<strong>Nome:</strong>&nbsp;<?php  echo $row_usuario->nome;?>
									</h4>
								</div>
								<div class="col-xs-4">
									<h4>
										<strong>CPF:</strong>&nbsp;<?php  echo $row_usuario->cpf;?>
									</h4>
								</div>
							</div>
						</div>
						<div class="col-xs-12">
							<p>
								<span style="color: #F00;">* Preenchimento obrigatório.</span>
							</p>
						</div>
					</div>
					<?php 
					$i = 0;
					?>
					<div id="print">
    					<form method="post" name="frm" action="">
    						<?php 
    						foreach ($planos as $plano){
    						    
    						    $query_areas = $this->db->query("SELECT * FROM plano_de_voo_areas WHERE id_questionario = '$plano->id'");
    						    
    						    foreach ($query_areas->result_array() as $row_areas) {
    						?>
    						<div class="form-group">
    							<input type="hidden" value="<?php echo $row_areas['id_questionario'];?>" name="questionario" id="questionario" />
    							<table class="table">
    								<thead>
    									<tr>
    										<th colspan="2"	style="background-color: #5a8cb7; color: #FFF;"	class="col-xs-1 text-center"><?php echo $row_areas['area'];?></th>
    									</tr>
    								</thead>
    								<tbody>
    									<?php 
    									$query_perguntas = $this->db->query("SELECT * FROM plano_de_voo_itens  WHERE id_questionario = '$row_areas[id_questionario]' AND id_area = '$row_areas[id]'");
    									
    									foreach ($query_perguntas->result_array() as $row_perguntas) {
    									    
    									    $verificaResposta = $this->db->query("SELECT * FROM plano_de_voo_respostas WHERE id_questionario = '$row_perguntas[id_questionario]' AND id_funcionario = '$idfuncionario'
                                            AND id_pergunta = '$row_perguntas[id]'");									   
    									   
    									    $row_resposta = $verificaResposta->row();
    									?>
    									<tr>
    										<td class="text-left" width="60%">
    											<h5 style="font-weight: bold;">
    												<span style="color: #F00;">*</span> <?php echo $row_perguntas['item'];?>
    											</h5>
    										</td>
    										<td>
    											<div class="btn-resp">
    											<?php if($row_perguntas['tipo'] == '1'){?>
    											
        											<div class="radio">
    													<label><input type="radio" class="btn" name="opt<?php echo $i?>[]" id="<?php echo $row_perguntas['id'];?>-1" value="1" <?php if($verificaResposta->num_rows() > 0 && $row_resposta->resposta == '1'){ echo 'checked="checked"';}?>>Sim</label>&nbsp;&nbsp;&nbsp;
    													<label><input type="radio" class="btn" name="opt<?php echo $i?>[]" id="<?php echo $row_perguntas['id'];?>-0" value="0" <?php if($verificaResposta->num_rows() > 0 && $row_resposta->resposta == '0'){ echo 'checked="checked"';}?>>Não</label>&nbsp;
        											</div>
        											<?php }elseif($row_perguntas['tipo'] == '2'){ ?>
        											<div class="radio">
        												<label><input type="radio" class="btn" name="opt<?php echo $i?>[]" id="<?php echo $row_perguntas['id'];?>-4" value="4" <?php if($verificaResposta->num_rows() > 0 && $row_resposta->resposta == '4'){ echo 'checked="checked"';}?>>Ótimo</label>&nbsp;&nbsp;
                                                    	<label><input type="radio" class="btn" name="opt<?php echo $i?>[]" id="<?php echo $row_perguntas['id'];?>-3" value="3" <?php if($verificaResposta->num_rows() > 0 && $row_resposta->resposta == '3'){ echo 'checked="checked"';}?>>Bom</label>&nbsp;&nbsp;
                                                     	<label><input type="radio" class="btn" name="opt<?php echo $i?>[]" id="<?php echo $row_perguntas['id'];?>-2" value="2" <?php if($verificaResposta->num_rows() > 0 && $row_resposta->resposta == '2'){ echo 'checked="checked"';}?>>Regular</label>&nbsp;&nbsp;
                                                    	<label><input type="radio" class="btn" name="opt<?php echo $i?>[]" id="<?php echo $row_perguntas['id'];?>-1" value="1" <?php if($verificaResposta->num_rows() > 0 && $row_resposta->resposta == '1'){ echo 'checked="checked"';}?>>Ruim</label>&nbsp;
        											</div>
        											<?php }else{ ?>
                                                        <textarea class="form-control" name="opt<?php echo $i?>[]" value="" rows="3" ></textarea>                                            
        											<?php } ?>
    											</div>
    										</td>
    									</tr>
    									<?php $i++;} ?>
    								</tbody>
    							</table>
    						</div>
    						<?php }} ?>
    						<div class="form-group">
    							<h4 class="page-header">&nbsp;</h4>
    						</div>
    						<br>
    						<div class="form-group text-center">
    							&nbsp;&nbsp; <input type="hidden" name="dep_qtde" value="-1" /> 
    							<input type="button" class="btn btn-default" name="imprimir" target="_blank" value="Imprimir" onclick="printIt()">
    						</div>
    					</form>
					</div>
				</div>
				<!-- /.panel-body -->
			</div>
		</div>
	</div>
	<?php }else{ ?>
	<div class="alert alert-warning">
      Não há plano cadastrado no momento.
    </div>
	<?php } ?>
</div>
<script type="text/javascript">

function printIt(printThis) {
	let divToPrint = document.getElementById('print');
    let htmlToPrint = 
        '<style type="text/css">' + 
        'table {'+'font-family: arial, sans-serif;'+ 
        'border-collapse: collapse;'+'width: 95%;'+ 
        'margin-left: 20px'+'}'+   
        'th, td {' +
        'border:1px solid #000;' +
        'padding: 8px;' +
        '}'+ 'tr:nth-child(even) {'+
        'background-color: #dddddd;'+'}'+
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    let windowToPrint = window.open("");    
    windowToPrint.document.write(htmlToPrint);
    windowToPrint.print();
    windowToPrint.close();
}

$('.btn-resp').on('click', '.btn', function(e){
    
	var item 			= e.target.id;
	resp = item.split("-");
	
	
	var idquestionario	= document.getElementById("questionario").value;
	var idfuncionario 	= <?=$idfuncionario?>;

	/*alert(idfuncionario);
    alert(resp[0]);
    alert(resp[1]);*/
     
    if(resp != ""){
    	$.ajax({
        	type: "POST",
            url: "<?php echo site_url("cadastros/save_respostas");?>/"+idquestionario+"/"+idfuncionario+"/"+resp[0]+"/"+resp[1],
            success: function(data) {}
        });
    }

    /*if(resp[1] == 0){     
    	$('#'+resp[0]+'-0').parents('.btn').addClass('active');
     	$('#'+resp[0]+'-1').parents('.btn').removeClass('active');
     	
    }else if(resp[1] == 1){
        $('#'+resp[0]+'-1').parents('.btn').addClass('active');
     	$('#'+resp[0]+'-0').parents('.btn').removeClass('active');
        $('#'+resp[0]+'-2').parents('.btn').removeClass('btn-success');
    }*/
}); 
</script>