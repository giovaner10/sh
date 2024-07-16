<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title></title>

<link href="<?php echo base_url('media')?>/css/bootstrap.css"
	rel="stylesheet">
<link href="<?php echo base_url('media')?>/css/bootstrap-responsive.css"
	rel="stylesheet">

<script>
function load()
{
window.print()
}
</script>

<style type="text/css">

		  
body{
	width: 100%;
	min-width: 100%;
	/*background-color: #FFFFFF;*/
	margin: 0;
	padding: 0;
}

#box{
	background-image: url(<?php echo base_url('media')?>/img/show_papel_timbrado.png);
	background-repeat: no-repeat;
	background-position: bottom right;
	height: auto;
	width: 720px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	margin-top: 0px;
	margin-bottom: 15px;
	border: 1px #CCCCCC solid;
}

#topo{
	height: 51px;
	width: 600px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	/*border: #CCCCCC solid 1px;*/
}

#logo_topo{
	height: 51px;
	width: 600px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	margin-top: 10px;
	/*border: #CCCCCC solid 1px;*/
}

#corpo{
	min-height: 700px;
	width: 700px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	/*border: #CCCCCC solid 1px;*/
}

#corpo h4{
	color: #484848; 
}

#titulo{
	text-align: center;
	padding: 2px;
	margin-top: 0px;
}

.numero_os{
	font-size: 12px;
}

.dados_cliente{
	width: 650px;
	margin-top: 10px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	background-color: #F9F9F9; 
}

.dados_cliente tr td{
	font-size: 12px;
	color: #383838;
}

.dados_instalacao{
	width: 650px;
	margin-top: 10px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	background-color: #F9F9F9; 
}

.dados_instalacao tr td{
	font-size: 12px;
	color: #383838;
}

.dados_contrato{
	width: 650px;
	margin-top: 10px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	background-color: #F9F9F9; 
}

.dados_contrato tr td{
	font-size: 12px;
	color: #383838;
	text-align: justify;
}

#assinatura{
	float: none;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

#assinatura p{
	font-size: 12px;
}

#assinatura table{
	float: none;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

#equipamentos{
	margin-top: 20px;
	width: 700px;
	float: none;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

#equipamentos table{
	float: none;
	margin-left: auto;
	margin-right: auto;
}

.dados{
	background-color: #F9F9F9;
}

#roda_pe{
	height: 100px;
	width: 700px;
	float: none;
	margin-left: auto;
	margin-right: auto;
}

#logo_roda_pe{
	height: 33px;
	width: 100px;
	float: none;
	margin-left: auto;
	margin-right: auto;
}

#endereco{
	margin-top: 5px;
	font-size: 8px;
	color: #383838;
	text-align: center;
	float: none;
	margin-left: auto;
	margin-right: auto;
}

#moldura {
	border: #CCCCCC solid 1px;
	height: auto;
	padding: 2px;
	margin-bottom: 30px;
	clear: both;
}

#moldura .titulo_moldura {
	background-color:#FFFFFF;
	color:#999999;
	display:inline;
	margin-bottom:10px;
	margin-left:-10px;
	margin-top:-30px;
	padding-left:10px;
	padding-right:10px;
	position:absolute;
	text-align:center;
	width:auto;
}

.table th, .table td {
	line-height: 10px !important;
	padding: 5px !important;
}

@media print{

	#menu{
		display: none;
	}

	#box{
		border: none;
	}

}


</style>

</head>

<body>

	<?php 
    	foreach ($ordens as $ordem):
            $id = $ordem->id;
            $solicitante = $ordem->solicitante;
            $data_solicitacao = dh_for_humans($ordem->data_solicitacao);
            // $data_solicitacao = $ordem->data_solicitacao;
            $contato = $ordem->contato;
            $telefone = $ordem->telefone;
            $id_instalador = $ordem->id_instalador;
            $data_inicial = dh_for_humans($ordem->data_inicial);
            // $data_inicial = $ordem->data_inicial;
            $data_final = dh_for_humans($ordem->data_final);
            // $data_final = $ordem->data_final;
            $hora_inicial = $ordem->hora_inicial;
            $hora_final = $ordem->hora_final;
            $observacoes = $ordem->observacoes;
        	$endereco_destino = $ordem->endereco_destino;
        	$nome_instalador = $ordem->nome_instalador;
        endforeach 
    ?>

    <?php
        foreach ($clientes as $cliente):
            $nome_cliente = $cliente->nome;
            $endereco_cliente = $cliente->endereco;
            $bairro_cliente = $cliente->bairro;
            $numero_cliente = $cliente->numero;
            $complemento_cliente = $cliente->complemento;
            $cidade_cliente = $cliente->cidade;
            $uf_cliente = $cliente->uf;
        endforeach 
    ?>

    <?php 
    	foreach ($contratos as $contrato):
    		$numero_contrato = $contrato->id;
    		$quantidade_veiculos = $contrato->quantidade_veiculos;
    		$valor_instalacao = number_format($contrato->valor_instalacao, 2, ',', '.');

    		if ($valor_instalacao != 0) {
    			$prestacoes = $contrato->prestacoes;
	            $total_instalacao = number_format($valor_instalacao*$quantidade_veiculos, 2, ',', '.');
	            $total_instalacao2 = $valor_instalacao*$quantidade_veiculos;
	            $valor_parcelas = number_format($total_instalacao2/$prestacoes, 2, ',', '.');


    		}
            
            $valor_mensal = number_format($contrato->valor_mensal, 2, ',', '.');
            $total_mensalidade = number_format($valor_mensal*$quantidade_veiculos, 2, ',', '.');
            $vencimento = $contrato->vencimento;
            $primeira_mensalidade = dh_for_humans($contrato->primeira_mensalidade);
            // $primeira_mensalidade = $contrato->primeira_mensalidade;

            $meses = $contrato->meses;
            $multa = $contrato->multa;
            if ($multa == 2) {
            	$multa_valor = $contrato->multa_valor;
        		$total_multa = number_format($multa_valor*$quantidade_veiculos, 2, ',', '.');
            }
            
        endforeach 
    ?>

    <?php
	 $tamanho = strlen($id); 

	 switch ($tamanho) {
	 	case '0':
	 		$numero = "XXXXXX";
	 		break;
	 	case '1':
	 		$numero = "00000".$id;
	 		break;
	 	case '2':
	 		$numero = "0000".$id;
	 		break;
	 	case '3':
	 		$numero = "000".$id;
	 		break;
	 	case '4':
	 		$numero = "00".$id;
	 		break;
	 	case '5':
	 		$numero = "0".$id;
	 		break;
	 	
	 	default:
	 		$numero = $id;
	 		break;
	 }

	?>

    <div class="well well-small" id = "menu">

	   	<a href="<?php echo site_url('servico')?>" title="" class="btn"><i class="icon-arrow-left"></i> Voltar</a>
	   	<a href="javascript:window.print()" title="" class="btn btn-primary"><i class="icon-print icon-white"></i> Imprimir</a>

	</div>

	<div id = "box">

		<div id = "topo">

			<div id = "logo_topo">
				<img src="<?php echo base_url('media')?>/img/logo_topo.png"/>
			</div>

		</div>
		
		<div id = "corpo">
			<div id = "titulo">
				<h5>Ordem de Serviços </br><b class = "numero_os">Nº <?php echo $numero ?></b></h5>
			</div>

			<div class = "dados_cliente">
				<div id="moldura">
	  			<div class="titulo_moldura">Dados do Cliente</div>
	            	<table border="0" >
	                    <table border="0" >
		                    <tr>
		                        <td><b>Nome: </b><?php echo $nome_cliente ?></td>
		                    </tr>
	                    </table>

	                  	<table border="0" >
		                    <tr>
		                        <td><b>Endereço: </b><?php echo $endereco_cliente ?> Nº <?php echo $numero_cliente ?> <?php echo $complemento_cliente ?>, <?php echo $bairro_cliente ?>, <?php echo $cidade_cliente ?>-<?php echo $uf_cliente ?></td>
		                    </tr>
	                    </table>

	    				<table border="0" >
		                    <tr>
		                        <td><b>Solicitante: </b><?php echo $solicitante ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		                        <td><b>Data de Emissão: </b><?php echo date('d-m-Y H:i:s', strtotime($ordens[0]->data_cadastro)) ?></td>
		                    </tr>
	                    </table>

	                    <table border="0" >
		                    <tr>
		                        <td><b>Contato: </b><?php echo $contato ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		                        <td><b>Telefone: </b><?php echo $telefone ?></td>
		                    </tr>
	                    </table>

	                    <table border="0" >
		                    <tr>
		                    	<td><b>Endereço Destino: </b><?php echo $endereco_destino ?></td>
		                    </tr>
	                    </table>                        
	                </table>
	            </div>
            </div>
            <div class = "dados_instalacao">
				<div id="moldura">
  				<div class="titulo_moldura">Dados da Instalação</div>
	            	<table border="0" >
		                <table border="0" >
		                    <tr>
		                        <td><b>Instalador: </b><?php echo $nome_instalador ?></td>
		                    </tr>
		                </table>

		                <table border="0" >
		                    <tr>
		                        <td style="margin: 10px;"><b>Data Inicial: </b> ___/___/_____</td>
		                        <td style="margin: 10px;"><b>Hora Inicial: </b> ___:___:___</td>
		                        <td style="margin: 10px;"><b>Data Final: </b> ___/___ /_____</td>
		                        <td style="margin: 10px;"><b>Hora Final: </b> ___:___:___</td>

		                    </tr>
		                </table>

		                <table border="0" >
		                    <tr>
		                        <td><b>Observações: </b><?php echo $observacoes ?></td>
		                    </tr>
		                </table>
	                </table>
	            </div>
	        </div>

			<div class = "dados_instalacao">
				<div id="moldura">
  				<div class="titulo_moldura">Dados do Veículo</div>
	            	<table border="0" >
	            		<table border="0" >
		                    <tr>
		                        <td><b>Placa: </b><?php echo $veiculos[0]->placa ?></td>
		                        <td><b>Serial: </b><?php echo $veiculos[0]->serial ?></td>
		                    </tr>
		                </table>
	                    <table border="0">
		                    <tr>
		                        <td><b>Marca/Modelo: </b>________________ </td>
		                        <td><b>Código: </b>______________ </td>
		                        <td><b>Nome do Resp.: </b>_________________________</td>
		                    </tr>
	                    </table>
                	</table>
                </div>
            </div>
			<div class = "dados_instalacao">
				<div id="moldura">
  				<div class="titulo_moldura">ITENS PARA INSPECIONAR</div>
        			<table class="table table-bordered">
						<tr>
							<th colspan="3">Anterior</th>
						    <th>INSPECIONAR</th>
						    <th colspan="3">Posterior</th>
						</tr>
						<tr>
						    <td>C</td>
						    <td>NC</td>
						    <td>NA</td>
						    <td></td>
						    <td>C</td>
						    <td>NC</td>
						    <td>NA</td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>01 - ACIONAMENTO/FUNCIONAMENTO DO MOTOR</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>02 - ACIONAMENTO/FUNCIONAMENTO DOS FAROIS E LANTERNAS TRASEIRAS</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>03 - FUNCIONAMENTO DA BUZINA</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>04 - FUNCIONAMENTO DO PAINEL DE INSTRUMENTOS</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>05 - FUNCIONAMENTO DO PISCA ALERTA E SETAS INDIVIDUAIS</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>06 - LUZ DE FREIO</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>07 - LUZ DE MARCHA RÉ</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>08 - FUNCIONAMENTO DOS LIMPADORES DE PARABRISA</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>09 - FUNCIONAMENTO DO APARELHO DE SOM</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>10 - VOLTAGEM DA BATERIA</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td></td>
						    <td></td>
						    <td></td>
						    <td>11 - FUNCIONAMENTO DE DISPOSITIVOS ELETRÔNICOS PREVIAMENTE INSTALADOS</td>
						    <td></td>
						    <td></td>
						    <td></td>
						</tr>
						<tr>
						    <td colspan="7">O VEÍCULO POSSUI BLOQUEIO?
						    <label class="checkbox inline"><input type="checkbox" value="">SIM</label>
							<label class="checkbox inline"><input type="checkbox" value="">NÃO</label>
							</td>
						</tr>
						<tr>
						    <td colspan="7">OBSERVAÇÕES:</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id = "assinatura">

			<table border="0" >
            <tr>
                <td><p>_____________________________________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p>Nome Completo</p></td>
                <td><p>______________</p><p>Cpf</p></td>
            <tr>
            	<td><p><b><?php echo $nome_cliente ?></b></p></td>
            </tr>
            </tr>
            </table>
		</div>
		<div id = "roda_pe">

			<div id = "logo_roda_pe">
				<img src="<?php echo base_url('media')?>/img/logo_show.png"/>
			</div>

			<div id = "endereco">
				<p>Av. Rui Barbosa, Nº 104 - CEP: 58200-000 - Centro - Guarabira/PB</br>www.showtecnologia.com | (83) 3271-6559</p>
			</div>
		</div>
	</div>
	
</body>
</html>

