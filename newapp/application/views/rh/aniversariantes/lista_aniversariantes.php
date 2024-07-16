<?php if ($this->auth->is_allowed_block('cad_aniversariantes')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Aniversariantes</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/aniversariantes')?>" class="btn btn-success"><i class="fa fa-plus icon-white"></i> Novo</a>
            </div>
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/smtpc')?>" class="btn btn-warning"><i class="fa fa-cogs icon-white"></i> Configurações de E-mail</a>
            </div>
            <div class="btn-group">
            	<button type="button" class="btn-info btn" id="enviaEmails">
                	<i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar e-mail para os aniversáriantes do dia</a>
           		</button>
           		<br>
           		<div id="result"></div>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php if(count($aniversariantes) > 0){ ?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 200px; text-align: center;">Dia mês Nascimento</th>
		<th style="width: 500px; text-align: center;">Nome</th>
		<th style="width: 200px; text-align: center;">empresa</th>
		<th style="width: 400px; text-align: center;">email</th>
		<th style="width: 30px; text-align: center;">Cargo</th>
		<th style="width: 200px; text-align: center;">Ações</th>
	</thead>
	<tbody>
	<?php  foreach ($aniversariantes as $aniversariante){ ?>
		<tr>
			<td style="text-align: center; vertical-align: middle;"><?=date("d/m", strtotime($aniversariante->data_nasc))?></td>					
			<td style="vertical-align: middle;"><?=$aniversariante->nome?></td>
			<td style="text-align: center; vertical-align: middle;"><?=$aniversariante->empresa?></td>
			<td style="vertical-align: middle;"><?=$aniversariante->email?></td>
			<td style="text-align: center; vertical-align: middle;"><?=$aniversariante->cargo?></td>
			<td style="text-align: center; vertical-align: middle;">
				<a href="<?php echo site_url("cadastros/editar_aniversariantes/$aniversariante->id")?>" class="btn btn-mini btn-primary" title="Editar colaborador">
                    <i class="fa fa-edit"></i>
                </a>
    			<a onclick="excluir(<?=$aniversariante->id?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i id="icon" class="fa fa-remove"></i>
                </a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php }else{ ?>
<div class="alert alert-warning">
	<strong>Desculpe!</strong> Nenhum colaborador cadastrado até o momento.
</div>
<?php } ?>
<script>
function excluir(id) {

	var r = confirm("Desseja realmente excluir esse cadasttro? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_aniversariante').'/' ?>"+id;
    
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

$('#enviaEmails').click(function(e){      
    
	e.preventDefault();

	$('#enviaEmails').html('<i class="fa fa-paper-plane"></i> Enviando emails...');
	$('#enviaEmails').prop('disabled', true);

    $('#result').html('');
    $.ajax({  
        url: "<?php echo site_url("cadastros/smtp_emails_nivers_do_dia");?>",
        success:function(data)  
        {              
        	if(data == 0)
            {
        		$('#enviaEmails').removeClass('btn-warning').addClass('btn-success btn');
                $('#enviaEmails').html('<i class="fa fa-paper-plane"></i> E-mails enviados com sucesso!');
                $('#enviaEmails').prop('disabled', false);
            }
            else{
                $('#enviaEmails').removeClass('btn-success').addClass('btn-danger');
                $('#enviaEmails').html('<i class="fa fa-paper-plane"></i> Tentar novamente');
                $('#result').append('<h5 class="alert alert-warning">'+data+'</h5>');
                $('#enviaEmails').prop('disabled', false);
            }
        } 
   });  
})
</script>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>