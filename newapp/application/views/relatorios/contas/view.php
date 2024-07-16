<style type="text/css">
    @media print{
        @page{
            size: landscape;
        }

        #landscape{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }
    }
</style>

<script type="text/javascript" src="<?php echo base_url('assets')?>/plugins/datepicker/js/bootstrap-datepicker.js"></script>
<h3>Relatório de Contas</h3>
<?php if(!empty($msg)):?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $msg?>
	</div>
<?php endif;?>
<hr>

<div class="well well-small">
	<?php echo form_open('')?>
	<div>
		<div class="input-prepend">
			<!-- FORNECEDOR -->
			<input type="text" name="fornecedor" class="span3"
				data-provide="typeahead" data-source='<?php echo $fornecedores?>'
				data-items="6"	placeholder="Digite o fornecedor ou deixe em branco para gerar todos."
				autocomplete="off" value="<?php echo $this->input->post('fornecedor') ?>" />
			
			<!-- DATA INICIO -->
			<span class="add-on"><i class="icon-calendar"></i> </span>
			<input type="text" name="dt_ini" class="input-small datepicker"	placeholder="Data Início" autocomplete="off" id="dp1" value="<?php echo $this->input->post('dt_ini') ?>" required />
			
			<!-- DATA FIM -->
			<span class="add-on"><i class="icon-calendar"></i> </span>
			<input	type="text" name="dt_fim" class="input-small datepicker" placeholder="Data Fim" autocomplete="off" id="dp2"	value="<?php echo $this->input->post('dt_fim') ?>" required />

			<!-- EMPRESA -->
			<span class="add-on"><i class="fa fa-institution"></i></span>
			<select class="form-control" name="empresa">
			    <option>ShowTecnologia</option>
			    <option>Norio</option>
			    <option>PneuShow</option>
			    <option selected>Todos</option>
            </select>
            <select class="form-control" id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria): ?>
                    <option><?= $categoria; ?></option>
                <?php endforeach; ?>
            </select>

		  	<!-- BOTÃO GERAR -->
			<button style="margin-left: 10px;" type="submit" class="btn">
				<i class="icon-list-alt"></i> Gerar
			</button>

			<!-- IMPRIMIR -->
			<?php if($this->input->post()):?>
                <button class="btn btn-info" onclick="imprimir();" type="button" style="margin-right: 5px;"><i class="fa fa-print"></i></button>
			<?php endif;?>
		</div>
	</div>
	<div>
		<div>
			<label
				class="checkbox inline">
			<input type="radio" id="inlineCheckbox1" value="0" name="status_conta[]" <?php echo $tipo_pendente == true ? 'checked' : '' ?>> Pendente
			</label>
			<label
				class="checkbox inline">
			<input type="radio" id="inlineCheckbox1" value="1" name="status_conta[]" <?php echo $tipo_pago == true ? 'checked' : '' ?>> Pago
			</label>
			<label
				class="checkbox inline">
			<input type="radio" id="inlineCheckbox1" value="3" name="status_conta[]" <?php echo $tipo_cancelado == true ? 'checked' : '' ?>> Cancelado
			</label>
			<label
				class="checkbox inline">
			<input type="checkbox" value="4" name="status_conta[]" title="Agrupar por cliente" <?php echo $agrupar_cliente == true ? 'checked' : '' ?>> <i class="icon-align-justify" title="Agrupar por cliente" ></i>
			</label>
		</div>
	</div>
	<?php echo form_close()?>
	<div class="clearfix"></div>
</div>

<div id="exportar_tabela" class="landscape">
    <table class="table table-striped table-nowrap" id="relatorio">
        <thead>
            <th>ID</th>
            <th>Empresa</th>
            <th>Fornecedor</th>
            <th>Categoria</th>
            <th>Descrição</th>
            <th>D. Vencimento</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Valor Pago</th>
            <th>D. Pagamento</th>
        </thead>
        <tbody>
            <?php if(isset($contas)): ?>
                <?php foreach ($contas as $conta): ?>
                    <tr>
                        <td><?php echo $conta->id;?></td>
                        <td>
                            <?php switch ($conta->empresa) {
                                case 2:
                                    echo "PNEUSHOW";
                                    break;

                                case 3:
                                    echo "NORIO";
                                    break;

                                default:
                                    echo "SHOWTECNOLOGIA";
                                    break;
                            } ?>
                        </td>
                        <td><?php echo $conta->fornecedor; ?></td>
                        <td><?php echo $conta->categoria; ?></td>
                        <td style="width: 10px !important;"><?php echo $conta->descricao; ?></td>
                        <td><?php echo dh_for_humans($conta->data_vencimento, false, false); ?></td>
                        <td>R$ <?php echo number_format($conta->valor, 2, ',', '.'); ?></td>
                        <td><?php echo status_fatura($conta->status, $conta->data_vencimento); ?></td>
                        <?php if ($conta->valor_pago > $conta->valor) { ?>
                            <td>R$ <?php echo $conta->valor_pago ? number_format($conta->valor, 2, ',', '.') : ''; ?></td>
                        <?php } else { ?>
                            <td>R$ <?php echo $conta->valor_pago ? number_format($conta->valor_pago, 2, ',', '.') : ''; ?></td>
                        <?php } ?>
                        <td><?php echo dh_for_humans($conta->data_pagamento, false, false); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="span4">Utilize o filtro para gerar relatório de contas.</td>
                </tr>
            <?php endif;?>
        </tbody>
    </table>
    <hr>
    <ul class="unstyled pull pull-right">
        <li>Total Pago: <strong>R$ <?php echo number_format($valor_pago, 2, ',', '.')?></strong></li>
        <li>Total Economia: <strong>R$ <?php echo number_format($valor_eco, 2, ',', '.')?></strong></li>
        <li>Total Pendente: <strong>R$ <?php echo number_format($valor_pendente, 2, ',', '.')?></strong></li>
        <li>Total Contas: <strong>R$ <?php echo number_format($valor_total, 2, ',', '.')?></strong></li>
    </ul>
</div>