<?php
############################ COMENTÁRIOS ###############################
#                                                                      #
#  Script de importação de retorno do Banco 001(Banco do Brasil)       #
#  CNAB 240, Carteira sem registro 17                                  #
#  Requerimento: Apenas passar o arquivo via form com os campos a      #
#  seguir:                                                             #
#  $nome = $_FILES['arquivo']['name']; Tipo Name do arquivo            #
#  $type = $_FILES['arquivo']['type']; Type do arquivo(txt, ret, etc)  #
#  $size = $_FILES['arquivo']['size']; Tamanho do arquivo              #
#  $tmp = $_FILES['arquivo']['tmp_name']; Nome temporário              #
#  No final da importação é chamado um relatório de baixa              #
#                                                                      #
#                                                                      #
#  Script Criado Por Jefferson Saavedra Paiva                          #
#  phpbelem.com.br / jspinformatica.com.br                             #
#  jefferson@jspinformatica.com.br                                     #
#                                                                      #
########################################################################

include_once("./conect.php");//Arquivo que faz a conexão com o banco de dados
 
$convenio = "1234567";//Codigo do convênio do cedente;
$tamanho_convenio = strlen($convenio);//Pega o tamanho do convênio
 
#Pegando dados do arquivo ##############################################################################
$nome = $_FILES['arquivo']['name'];
$type = $_FILES['arquivo']['type'];
$size = $_FILES['arquivo']['size'];
$tmp = $_FILES['arquivo']['tmp_name'];
 
$dir = '/retorno';
 
if(eregi('(x-ret|octet-stream)', $type)){
	if($tmp){
		if(move_uploaded_file($tmp, $dir."/".$nome)){
			$lendo = @fopen($dir."/".$nome,"r");
			if (!$lendo){
				echo "Erro ao abrir a URL.<br>";
				exit;
			}
			$i = 0;
			while (!feof($lendo)){
				$i++;
				$linha = fgets($lendo,9999);
				$t_u_segmento = substr($linha,13,1);//Segmento T ou U
				$t_tipo_reg = substr($linha,7,1);//Tipo de Registro
				if($t_u_segmento == 'T'){
					$t_cod_banco = substr($linha,0,3);//Código do banco na compensação
					$t_lote = substr($linha,3,4);//Sequencial do Lote
					$t_n_sequencial = substr($linha,8,5);//Sequencial de registro - incrementado em 1 a cada novo registro
					$t_cod_mov = substr($linha,15,2);//Codigo de Movimento (Vide Layout)
					$t_ag_mant = substr($linha,17,5);//Agencia Mantenedora da Conta
					$t_dig_ag_mant = substr($linha,22,1);//Digito Verificador da Agencia
					$t_num_cc = substr($linha,23,12);//Numero da Conta Corrente
					$t_dig_num_cc = substr($linha,35,1);//Digito Verificador da Conta
					$nosso_numero_provisorio = trim(substr($linha,37,20));//Identificacao do Titulo no Banco /Nosso Numero/ *** trunca em 11 + dv ou 17 ***
					$tamanho_t_nosso_numero = strlen($nosso_numero_provisorio);
					$inicio_nosso_numero = $tamanho_t_nosso_numero - $tamanho_convenio;
 
					if($tamanho_t_nosso_numero == 12){
						$t_nosso_numero = (substr($nosso_numero_provisorio, $tamanho_convenio, ($inicio_nosso_numero - 1))) + 0;
					}elseif($tamanho_t_nosso_numero == 17){
						$t_nosso_numero = (substr($nosso_numero_provisorio, $tamanho_convenio, ($inicio_nosso_numero))) + 0;
					}
 
					$t_cod_carteira = substr($linha,57,1);//Codigo da Carteira (Vide Layout)
					$t_ident_tit = substr($linha,57,15);//Numero do documento de cobranca /Seu Numero/ ** Limitado pelo CBR641 em 10 posicoes *** 
					$t_dt_vencimento = substr($linha,73,8);//Data do Vencimento do Titulo /DDMMAAAA/
					$t_v_nominal = substr($linha,81,13).'.'.substr($linha,94,2);//Valor Nominal do Titulo
					$t_num_banco = substr($linha,96,3);//Numero do Banco
					$t_cod_ag_receb = substr($linha,99,5);//Agencia Cobradora/Recebedora
					$t_dv_ag_receb = substr($linha,104,1);//Dígito verificador da agencia cobr/receb
					$t_id_titulo_empresa = substr($linha,105,25);//Identificacao do Titulo na Empresa
					$t_cod_moeda = substr($linha,130,2);//Código da moeda
					$t_tip_inscricao = substr($linha,132,1);//Tipo de Inscricao do Sacado (Vide Layout)
					$t_num_inscricao = substr($linha,133,15);//Numero de Inscricao do Sacado
					$t_nome = substr($linha,148,40);//Nome do Sacado
					$t_v_tarifa_custas = substr($linha,198,13);//Valor da tarifa/custas
					$t_id_rejeicoes = substr($linha,213,2);//Identificação para rejeições, tarifas, custas, liquidação e baixas
					$t_carteira = substr($linha,223,2);//Carteira
					$t_variacao_carteira = substr($linha,225,3);//Variação da Carteira
					$t_num_convenio = substr($linha,228,6);//Numero do Convenio
					$t_cod_carteira_ant = substr($linha,234,1);//Codigo da Carteira Anterior
				}
				if($t_u_segmento == 'U'){
					$t_nosso_numero;
					$u_cod_banco = substr($linha,0,3);//Código do banco na compensação
					$u_lote = substr($linha,3,4);//Sequencial do Lote
					$u_n_sequencial = substr($linha,8,5);//Sequencial de registro- incrementado em 1 a cada novo registro
					$t_cod_mov = substr($linha,15,2);//Codigo de Movimento ***igual ao informado no segmento T***
					$u_juros_multa = substr($linha,17,13).'.'.substr($linha,30,2);//Juros / Multa / Encargos					
					$u_desconto = substr($linha,32,13).'.'.substr($linha,45,2);//Valor do Desconto Concedido
					$u_abatimento = substr($linha,47,13).'.'.substr($linha,60,2);//Valor do Abatimento Concedido / Cancelado
					$u_iof = substr($linha,62,13).'.'.substr($linha,75,2);//Valor do IOF recolhido
					$u_v_pago = number_format((substr($linha,77,13).'.'.substr($linha,90,2)),2,',','.');//Valor Pago pelo Sacado
 
					$u_v_liquido = substr($linha,92,13).'.'.substr($linha,105,2);//Valor liquido a ser creditado
					$u_v_despesas = substr($linha,107,13).'.'.substr($linha,120,2);//Valor de outras despesas
					$u_v_creditos = substr($linha,122,13).'.'.substr($linha,135,2);//Valor de outros creditos
					$u_dt_ocorencia = substr(substr($linha,137,8),4,4).'-'.substr(substr($linha,137,8),2,2).'-'.substr(substr($linha,137,8),0,2);//Data da ocorrência
					$u_dt_efetivacao = substr(substr($linha,145,8),4,4).'-'.substr(substr($linha,145,8),2,2).'-'.substr(substr($linha,145,8),0,2);//Data da efetivação do credito
					$data_agora = date('Y-m-d');
					$hora_agora = date('H:i:s');
 
 
 
					echo $sql_update_financeiro = "UPDATE financeiro SET valor_pago='$u_v_pago', multa='$u_juros_multa', pagamento='$u_dt_ocorencia', status='Pago', financ_data='$data_agora', financ_hora='$hora_agora', retorno='$nome', baixa='Retorno' WHERE id='$t_nosso_numero'";//Faz Update no lançamento no BD ou pode dar insert se for o caso.
 
					mysql_query($sql_update_financeiro);
 
				}
			}
			fclose($lendo);
		}
	}
} else {
	echo '<script>alert("Arquivo nao suportado!!!");</script>';
	return false;
}
?>
