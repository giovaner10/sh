<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Produtos</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/produtos')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo Produto</a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php if(count($produtos) > 0){ ?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 200px; text-align: center;">Capa</th>
		<th style="width: 400px; text-align: center;">Assunto</th>
		<th style="width: 100px; text-align: center;">Excluir</th>
	</thead>
	<tbody>
	<?php 
	    
        foreach ($produtos as $produto){
           
            $query = $this->db->query("SELECT * FROM cad_produto_informacoes_arquivos WHERE id_assunto = '$produto->id_assunto'");
            
            $i=0;
            $foto = "";
            foreach ($query->result_array() as $row) { 
               
                $ext = substr($row['file'], (strlen($row['file']) - 3), strlen($row['file']));  
                
                if($ext == "jpg" || $ext == "png"){
                    $foto .= "<img src='".base_url("uploads/produtos/$row[file]")."' width='200' height='100'>";
                }
            }
        ?>
		<tr>
			<td><center><?=$foto?></center></td>	
			<td style="vertical-align: middle;"><?php echo $produto->assunto; ?></td>
			<td style="text-align: center; vertical-align: middle;">
				<a href="<?php echo site_url("cadastros/editar_produto/$produto->id_assunto")?>" class="btn btn-mini btn-primary" title="Editar Produto">
                    <i class="fa fa-edit"></i>
                </a>
    			<a onclick="excluir(<?= $produto->id_assunto ?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i id="icon<?= $produto->id_assunto ?>" class="fa fa-remove"></i>
                </a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php }else{ ?>
<div class="alert alert-warning">
	<strong>Desculpe!</strong> Nenhum produto cadastrado até o momento.
</div>
<?php } ?>
<script>
function excluir(id) {

	var r = confirm("Desseja realmente excluir esse produto? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_produto').'/' ?>"+id;
    
         $.ajax({
            url : url,
            type : 'POST',
            beforeSend: function(){
    			alert('Aguarde um momento por favor...');
            },
            success : function(data){
            	alert(data);
    	        window.location.reload();  
            },
            error : function () {
                alert('Error...');
            }
        });
	}
}
</script>