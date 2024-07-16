<div class="containner">
	<h5>Gente & Gestão</h5>
	<h2 class="TitPage">Administração de Pessoal</h2>
	<div style="float: right">
		<a href="gente_gestao">Voltar</a>
	</div>
	<br>
	<?php 
	$nome = $this->auth->get_login('admin', 'nome');
	$query_usuario = $this->db->query("SELECT id, nome  FROM usuario WHERE nome = '$nome'");	
	$row_usuario = $query_usuario->row();
	$idfuncionario = $row_usuario->id;
	?>
	<div class="row ">
		<div class="panel text-center ">
			<div class="panel-body">
				<div class="col-sm-12" style="padding: 0px">
					<div class="list-group-item col-sm-3 text-center"
						style="padding-bottom: 21px; height: 210px;">
						<img src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/rh_cadastros.png');?>"><br> <br>
						<a href="formulario_adp" target="_blank" style="font-size: 12px">Cadastro Colaborador</a><br> 
						<a href="docs_pendentes/<?=$idfuncionario?>" target="_blank" style="font-size: 12px">Docs Pendentes</a>
					</div>
					<div class="list-group-item col-sm-3 text-center" style="padding-bottom: 21px; height: 210px;">
						<img src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/rh_adp.png');?>"><br> <strong>ADP
							Expert Services<br>
						</strong> <a href="https://www.adpweb.com.br/expert/" target="_blank" style="font-size: 12px">Portal do Gestor</a><br>
						<a href="https://www.adpweb.com.br/rhweb25/" target="_blank" style="font-size: 12px">Portal do Colaborador</a>
					</div>
					<div class="list-group-item col-sm-3 text-center;"
						style="padding-bottom: 16px; height: 210px;">
						<div style="margin-bottom: 4px">
							<img src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/img_RH4.jpg');?>">
						</div>
						<strong>Itinerários Fretado<br>
							<div style="line-height: 13px">
								<a href="<?php echo base_url('uploads/gente_gestao/adm_pessoal/entinerarios_fretado/itinerario_entrada_SEG_SEX2.pdf');?>" target="_blank" style="font-size: 10px">Entrada 
								<span style="color: #CC0000">(Seg. a Sexta)</span></a><br> 
								<a href="<?php echo base_url('uploads/gente_gestao/adm_pessoal/entinerarios_fretado/itinerario_saida_SEG_QUI_3.pdf');?>" target="_blank" style="font-size: 10px">Saída <span style="color: #CC0000">(Seg. a Quinta)</span></a><br>
								<a href="<?php echo base_url('uploads/gente_gestao/adm_pessoal/entinerarios_fretado/itinerario_saida_SEX_3.pdf');?>" target="_blank" style="font-size: 10px">Saída <span style="color: #CC0000">(Sexta)</span></a><br>
								<a href="<?php echo base_url('uploads/gente_gestao/adm_pessoal/entinerarios_fretado/itinerario_SAB_DOM_FERIADO2.pdf');?>" target="_blank" style="font-size: 10px"><span	style="color: #CC0000">Sáb, Dom e Feriados</span></a>
							</div></strong>
					</div>
					<a href="politicas_formulariosRH"
						class="list-group-item col-sm-3 text-center"
						style="padding-bottom: 24px; height: 210px"> <img
						src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/rh_001.jpg');?>"><br>
					<br> <strong>Pol&iacute;ticas e Formul&aacute;rios de Gente &amp;
							Gestão</strong><br>
					<br>
					</a>
				</div>
				<div class="col-sm-12" style="padding: 0px">
					<a href="correcao_irrf"
						class="list-group-item col-sm-3 text-center"
						style="padding-bottom: 24px; height: 210px"> <img
						src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/rh_irrf.png');?>"><br>
					<br> <strong>Aviso IRRF <br> Adiantamento fev/2018
					</strong><br>
					<br></a> <a href="central_unimed" class="list-group-item col-sm-3 text-center"	style="padding-bottom: 24px; height: 210px">
					<img src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/rh_unimed.png');?>"><br>
					<br> <strong>Assistência Médica<br> Unimed </strong>
					</a> <a href="central_bradesco"
						class="list-group-item col-sm-3 text-center"
						style="padding-bottom: 24px; height: 210px"> <img
						src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/rh_bradescoSaude.jpg');?>"><br>
					<br> <strong>Bradesco<br>Saúde
					</strong>
					</a> <a class="list-group-item col-sm-3 text-center"
						style="padding-bottom: 24px; height: 210px"
						href="desconto_coparticipacao/<?=$idfuncionario?>"> <img
						src="<?php echo base_url('uploads/gente_gestao/adm_pessoal/rh_descCopart.png');?>"> <br>
					<br>
					<strong>Descontos de<br>Coparticipação
					</strong>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>