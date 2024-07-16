<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Contatos Corporativos</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/contato_corporativo')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo Contato Corporativo</a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php 
if(count($lista_contatos) > 0){ 
?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="text-align: center;">Abas</th>
		<th style="text-align: center;">Titulo | Endereço</th>
		<th style="text-align: center;">Descrição</th>
        <th style="text-align: center;" colspan="2"></th>
	</thead>
	<tbody>
		<?php foreach ($lista_contatos as $lista_contato){ ?>
		<tr>
			<?php if($lista_contato->tipo == "Atendimento a Clientes" || $lista_contato->tipo == "Projetos Dedicados"){ ?>
			<td><?php echo $lista_contato->tipo; ?></td>
			<?php }else{ ?>
			<td><?php echo $lista_contato->loja; ?></td>
			<?php } ?>
			<?php if($lista_contato->tipo == "Atendimento a Clientes" || $lista_contato->tipo == "Projetos Dedicados"){ ?>
			<td><?php echo $lista_contato->titulo; ?></td>
			<?php }else{ ?>
			<td><?php echo $lista_contato->endereco.', '. $lista_contato->numero.', '. $lista_contato->bairro.', '. $lista_contato->complemento; ?></td>
			<?php } ?>
			<td><?php echo $lista_contato->descricao; ?></td>
			<td style="text-align: center; vertical-align: middle; ">
    			<a href="<?php echo site_url('cadastros/editar_contatos_corporativos/'.$lista_contato->id)?>" class="btn btn-mini btn-primary" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>    			
			</td>
			<td style="text-align: center;">
    			<a onclick="excluir(<?= $lista_contato->id ?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i class="fa fa-remove"></i>
                </a>
			</td>
		</tr>
		<?php }?>   
	</tbody>
</table>
<?php } ?>
<script type="text/javascript">
function excluir(id) {

	var r = confirm("Desseja realmente excluir esse contato? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_contatos_corporativos').'/' ?>"+id;
    
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