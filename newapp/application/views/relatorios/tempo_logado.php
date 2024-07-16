<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>

#email_suggestions {
    position: absolute;
    width: 100%;
    margin-top: 38px; 
    background-color: #fff;
    border: 1px solid #ccc;
    border-top: none;
    list-style: none;
    padding: 0;
    z-index: 1;

}

@media print {
        .no-print {
            display: none;
        }
    }

	.suggestions li {
        list-style-type: none;
        padding: 5px;
        margin: 0;
        cursor: pointer;
    }

    .suggestion-item {
        display: inline-block;
        margin: 5px; /* Adapte a margem conforme necessário */
    }


</style>

<h3>Relatório de Tempo Logado</h3>
<hr><hr style="border:none;">

<h3><?=lang("relatoriodetempologado")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>" class="no-print">Home</a> <span class="no-print"> ><span>
    <?=lang('relatorios')?> >
    Tempo Logado 
</div>

<div class="row-fluid no-print">
    <div class="well well-small" style="height: 100px; display: flex; align-items: center;">
        <?php echo form_open('relatorios/tempo_logado')?>
        <div class="span9 input-prepend input-append" style="display: inline-flex; position: absolute;">
			<input type="text" id="usuario_email" name="usuario_email" class="form-control" style="width: 300px; text-align: left;"
				placeholder="Digite o email" autocomplete="off" value="<?php echo $this->input->post('usuario_email') ?>" />
				<div class="span9 input-prepend" style="display: inline-flex; position: absolute;">
            		<ul id="email_suggestions" class="suggestions" style="background-color: white; width: auto;">
				    </ul>
				</div>		
		</div>
        <div style="display: inline-flex; margin-left: 310px;;">
            <span class="add-on"><i class="fa fa-calendar" style="margin: 4px; font-size: 26px;"></i> </span> 
            <input type="text" name="dt_ini" class="form-control datepicker" placeholder="Data Início" autocomplete="off" id="dp1" value="<?php echo $dt_ini ?>" required /> 
        </div>
        <div style="display: inline-flex; margin-left: 10px;">
            <span class="add-on"><i class="fa fa-calendar" style="margin: 4px; font-size: 26px;"></i> </span> 
            <input type="text" name="dt_fim" class="form-control datepicker" placeholder="Data Fim" autocomplete="off" id="dp2" value="<?php echo $dt_fim ?>" required />
        </div>
        <div class="span2" style="position: absolute; margin-left: 790px; margin-top: -34px;" >
            <button type="submit" class="btn btn-primary" style="height: 35px;font-size: 13px;">
                </i> Gerar
            </button>
            <!-- <button type="button" id="limparCampos" class="btn btn-default" style="height: 34.7px;">Limpar</button> -->
            <a href="javascript:window.print();" class="btn btn-default" style="height: 35px; width: 46px;" title="Imprimir">
                <i class="fa fa-print" style="color: black;"></i> 
            </a>
        </div>
        <?php echo form_close()?>
    </div>
</div>

<?php if($this->input->post()):?>
<div class="alert alert-info">
  <b>Período analisado:</b>
  <?php echo 'De '.$dt_ini. ' 00:00:00 até '.$dt_fim.' 23:59:59.'; ?>
</div>

<?php if ($msg): ?>
<div class="alert alert-error">
	<?php echo $msg ?>
</div>
<?php endif; ?>

<table class="table table-bordered">
	<tbody>
		<?php if ($relatorio): ?>
			<?php foreach ($relatorio as $usuario => $sessoes) : ?>
				<?php if (count($sessoes) != 0) : ?>
					<tr style="background-color: #f5f5f5">
						<td class="span8"><b>Usuário:</b> <?php echo $usuario ?></td>
						<td class="span1"><b>Sessões:</b> <?php echo count($sessoes) ?></td>
					</tr>
					<tr>
						<td colspan="4">
							<div class="span12">
								<table class="table">
									<thead>
										<th class="span1">Email</th>
										<th class="span1">Data</th>
										<th class="span2">Tempo Logado/DIA</th>
										<th class="span2">Horário de Login</th>
										<th class="span2">Horário de Logout</th>
										<th class="span2">Total da Sessão</th>					
									</thead>
									<tbody>
									<?php foreach ($sessoes as $sessao) : ?>
										<tr>
											<td><?php echo $sessao['email'] ?></td>
											<td><?php echo $sessao['data'] ?></td>
											<td><?php echo $sessao['tempo_logado_dia'] ?></td>
											<td><?php echo $sessao['horario_login'] ?></td>
											<td><?php echo $sessao['horario_logout'] ?></td>
											<td><?php echo $sessao['tempo_logado'] ?></td>
											
										</tr>
									<?php endforeach; ?>
									</tbody>
									
								</table>

							</div>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach ?>
		<?php else:?>
				<tr>
					<td colspan="8">Nenhum registro encontrado.</td>
				</tr>
		<?php endif;?>
	</tbody>
</table>
<?php endif;?>

<script>
    $(document).ready(function() {

        $('#usuario_email').keyup(function() {
            var inputVal = $(this).val();
            if (inputVal) {
                $.ajax({
                    url: '<?php echo site_url("relatorios/getLoginUsers"); ?>',
                    type: 'GET',
                    data: { input: inputVal },
                    success: function(response) {
                        var suggestions = JSON.parse(response);
                        var suggestionsList = $('#email_suggestions');
                        suggestionsList.empty();
                        
                        for (var i = 0; i < suggestions.length; i++) {
                            var suggestionItem = $('<li class="suggestion-item">' + suggestions[i] + '</li>');
                            
                            suggestionItem.mouseover(function() {
                                $(this).css('background-color', '#03A9F4 !important');
                            });
                            
                            suggestionItem.mouseout(function() {
                                $(this).css('background-color', '');
                            });
                            
                            suggestionsList.append(suggestionItem);
                        }
                        
                        suggestionsList.show();
                    }
                });
            } else {
                $('#email_suggestions').empty().hide();
            }
        });



    // Adiciona evento de clique aos itens de sugestão
    $('#email_suggestions').on('click', '.suggestion-item', function() {
        var suggestion = $(this).text();
        $('#usuario_email').val(suggestion);
        $('#email_suggestions').empty().hide();
    });

	// $('#limparCampos').click(function() {
    //     $('#usuario_email').val('');
    //     $('#dt_ini').val('');
    //     $('#dt_fim').val('');
    //     // Ou seja, limpar os valores de todos os três inputs.
    // });
	
	// Máscara para o campo de data Início (Formato: dd/mm/yyyy)
	$('#dp1').mask('99/99/9999');
        
    // Máscara para o campo de data Fim (Formato: dd/mm/yyyy)
    $('#dp2').mask('99/99/9999');

    // Inicialize o Datepicker nos campos de data
    $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy', // Formato da data exibida
            showButtonPanel: true, // Exibir botões "Hoje" e "Limpar"
            changeMonth: true, // Permitir seleção do mês
            changeYear: true, // Permitir seleção do ano
    });
	
});

</script>
