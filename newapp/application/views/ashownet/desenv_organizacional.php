<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/colorbox.css" />
<script src="<?php echo base_url('assets');?>/js/jquery.colorbox.js"></script>
<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_treinamentos')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/listar_treinamentos');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Gente & Gestão</h5>
	<div class="row">
		<div class="box box-info">
			<div class="box-body">
				<h2 class="TitPage">Desenvolvimento Organizacional</h2>
				<div class="col-sm-12" style="padding:0px">
					<a href="treinamentos" class="list-group-item col-sm-3 text-center" style="padding-bottom:15px">
                  		<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/rh_treinaEAD.png");?>" style="width: 150px; height: 150px;">
                  		<strong><br><br>Treinamentos EAD</strong>
                    </a>              
                    <?php 
                    $nome = $this->auth->get_login('admin', 'nome');
                    $query_usuario = $this->db->query("SELECT * FROM usuario WHERE nome = '$nome'");
                    $row_usuario = $query_usuario->row();
                    $idfuncionario = $row_usuario->id;
                    
                    ?>      
                    <a href="atividades/<?=$idfuncionario?>" class="list-group-item col-sm-3 text-center" style="padding-bottom:15px">
                  		<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/rh_treinaOnline.png");?>" style="width: 150px; height: 150px;">
                  		<strong><br><br>Meus Treinamentos</strong>
                    </a>
                    <?php 
                        foreach ($parcerias as $parceria){
                        
        				$query = $this->db->query("SELECT id_apresentacao, file FROM cad_parcerias_arquivos
                        WHERE id_apresentacao ='$parceria->id'");
        				
        				$i=0;
        				$visualizarImagem = "";
        				$arquivoDownload = "";
        				foreach ($query->result_array() as $row) { 
        				  
        				    $ext = substr($row['file'], (strlen($row['file']) - 3), strlen($row['file']));
        				
        				    if($ext != "ppt" && $ext != "ptx"){
        				        $arquivoImage = $row['file'];
        				    }else{
        				        $arquivoDownload = $row['file'];
        				    }
        				    
        				    if($i !=0 && ($ext != "ppt" || $ext != "ptx")){
        				        $visualizarImagem = 'style="display: none;"';
        				    }else{
        				        $visualizarImagem = "";
        				    }
        				    
        				    if($i !=0 && ($ext == "ppt" || $ext == "ptx")){
        				        $visualizarDownload= 'style="display: hide;"';
        				    }else{
        				        $visualizarDownload= 'style="display: none;"';
        				    }
        				
        				    if($ext != "ppt" && $ext != "ptx"){
        				?>
        	            <a <?php echo $visualizarImagem;?> href="<?php echo base_url("uploads//gente_gestao/desenv_organizagional/parcerias/$arquivoImage");?>" class="list-group-item col-sm-3 text-center vapresentacao<?=$row['id_apresentacao']?>" style="padding-bottom:15px">
                      		<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/rh_parcerias.png");?>" style="width: 150px; height: 150px;">
                      		<strong><br><br>Parcerias</strong>
                        </a>
                        <?php } 
                        $i++;} ?>        				
        		<script>
        			$(".vapresentacao<?=$parceria->id?>").colorbox({rel:'vapresentacao<?=$parceria->id?>'});
        		</script>
        		<?php }?>
                    
                    <!--<a href="" class="list-group-item col-sm-3 text-center" style="padding-bottom:15px">
                  		<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/Conhecendo100.png");?>" style="width: 150px; height: 150px;">
                  		<strong><br><br>Gestão de Carreira</strong>
                    </a> -->
				</div>
				<div class="col-sm-12" style="padding:0px">
					<a href="politicas_formulariosRH" class="list-group-item col-sm-3 text-center" style="padding-bottom:15px">
                  		<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/rh_001.jpg");?>" style="width: 150px; height: 150px;">
                  		<strong><br><br>Políticas e Formulários de Gente & Gestão</strong>
                    </a>
                    <a href="plano_de_voo" class="list-group-item col-sm-3 text-center" style="padding-bottom:15px">
                  		<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/plano_de_voo.png");?>" style="width: 150px; height: 150px;">
                  		<strong><br><br>Plano de Voo</strong>
                    </a>
                    <a href="conectando_liderancas" class="list-group-item col-sm-3 text-center" style="padding-bottom:15px">
                  		<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/liderancasN.png");?>" style="width: 150px; height: 150px;">
                  		<strong><br><br>Conectando Lideranças</strong>
                    </a>
				</div>				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(".vapresentacao14").colorbox({rel:'vapresentacao14'});
</script>