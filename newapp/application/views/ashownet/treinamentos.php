<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_treinamentos')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/listar_treinamentos');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>RH | Treinamentos</h5>

	
	<h2 class="TitPage">Treinamentos Online</h2>
	<div class="row">
		<div class="box box-info">
			<div class="box-body">
				<div class="col-sm-12" style="padding: 0px">
				<?php foreach ($dados as $dado){ ?>
				<?php if($dado->tipo == "online"){?>
					<a href="javascript: void(0)" onclick="popup('<?php echo $dado->link;?>')" class="list-group-item col-sm-4 text-center">
						<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/treinamentos/$dado->file");?>" width="200px">
						<br><strong><?php echo $dado->descricao;?></strong><br>
					</a>	
				<?php }} ?>				
				</div>
			</div>
		</div>
	</div>
	<h2 class="TitPage">VideoAulas</h2>
	<div class="row ">
		<div class="box box-info">
			<div class="box-body">
				<div class="col-sm-12" style="padding: 0px">
				<?php foreach ($dados as $dado){ ?>
				 <?php if($dado->tipo == "videos"){?>
					<a href="javascript: void(0)" class="list-group-item col-sm-4 text-center" onclick="popup('<?php echo $dado->link;?>')">
						<img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/treinamentos/$dado->file");?>" width="200px">
						<br> <strong><?php echo $dado->descricao;?></strong><br>
					</a> 
				</div>
				<?php }}?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function popup(url)
{
    params  = 'width='+screen.width;
    params += ', height='+screen.height;
    params += ', top=0, left=0'
    params += ', fullscreen=yes';
    
    newwin=window.open(url,'windowname4', params);
    if (window.focus) {newwin.focus()}
    return false;
}
</script>