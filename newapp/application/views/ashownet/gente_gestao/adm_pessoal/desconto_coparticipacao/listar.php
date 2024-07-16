<?php if ($this->auth->is_allowed_block('cad_desconto_coparticipacao')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/colorbox.css" />
<script src="<?php echo base_url('assets');?>/js/jquery.colorbox.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Desconto de Coparticipação</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/desconto_coparticipacao')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo lançamento</a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php 
    if(count($lista_dados) > 0){ 
?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 600px; text-align: center;">Funcionários</th>
		<th style="text-align: center;">Mês de competência</th>
        <th style="width: 200px; text-align: center;">Ações</th>
	</thead>
	<tbody>
		<?php 
		
		foreach ($lista_dados as $lista_dado){ 	
		    
		    $mes_competencia = explode("-", $lista_dado->mes_competencia);		    
		    
		    switch ($mes_competencia[1]) {
		        case "01":    $mes = "Janeiro";     break;
		        case "02":    $mes = "Fevereiro";   break;
		        case "03":    $mes = "Março";       break;
		        case "04":    $mes = "Abril";       break;
		        case "05":    $mes = "Maio";        break;
		        case "06":    $mes = "Junho";       break;
		        case "07":    $mes = "Julho";       break;
		        case "08":    $mes = "Agosto";      break;
		        case "09":    $mes = "Setembro";    break;
		        case "10":    $mes = "Outubro";     break;
		        case "11":    $mes = "Novembro";    break;
		        case "12":    $mes = "Dezembro";    break;
		    }
		    
		 ?>
		<tr>
			<td><?=$lista_dado->nome?></td>
			<td style="text-align: center;"><?=$mes.'/'.$mes_competencia[0]?></td>			
    		<td style="text-align: center; vertical-align: middle; ">
    			<!-- <a href="<?php echo site_url('cadastros/editar_docs_pendentes/'.$lista_dado->id)?>" class="btn btn-mini btn-primary" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>   -->
                <a onclick="excluir(<?=$lista_dado->id?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i class="fa fa-remove"></i>
                </a>  			
			</td>
		</tr>
		<?php }?>   
	</tbody>
</table>
<script>

function excluir(id) {

	var r = confirm("Desseja realmente excluir esse desconto de coparticipação? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_desconto_coparticipacao').'/' ?>"+id;
    
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
<?php }else{ ?>
<div class="alert alert-warning">
	<strong>Desculpe!</strong> Nenhum dado cadastrado até o momento.
</div>
<?php } ?>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>