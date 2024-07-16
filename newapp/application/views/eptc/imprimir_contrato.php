<!-- InicioDaPagina -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>SHOW TECNOLOGIA - CONTRATO</title>
<link href="<?php echo base_url('media')?>/css/bootstrap.css"
	rel="stylesheet">
	<link
		href="<?php echo base_url('media')?>/css/bootstrap-responsive.css"
		rel="stylesheet">
		<link href="./{tpldir}/css/estilos.css" rel="stylesheet"
			type="text/css" />
		<style type="text/css">
.titulo {
	font-size: 12px;
	font-weight: bold;
}

.texto {
	font-size: 10px;
	text-align: justify;
}

.corpo {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	width: 15cm;
	text-align: left;
	vertical-align: top;
}

p {
	font-size: 10px;
	text-align: justify;
}

#salto_pagina_despois {
	page-break-after: always;
	page-break-inside: auto;
}

#salto_pagina_antes {
	page-break-before: always;
}

.saltopagina {
	page-break-after: always;
}

@media print {
	#menu {
		display: none;
	}
	#box {
		border: none;
	}
}
</style>
</head>
<body>
<?php
foreach ($contratos as $contrato) :

    $prefixo = $contrato->prefixo;
    $permissionario = $contrato->permissionario;
    $cpf = $contrato->cnpj;
endforeach
?>
 <div class="well well-small" id="menu">
	<a href="<?php echo site_url('contratos_eptc/listar_contratos')?>"
		title="" class="btn"><i class="icon-arrow-left"></i> Voltar</a> <a
		href="javascript:window.print()" title="" class="btn btn-primary"><i
		class="icon-print icon-white"></i> Imprimir</a>
</div>
	<center>
		<img src="<?php echo base_url('media')?>/img/logo_topo.png" />
	</center>
	<table align="center">
		<tr>
			<td style="text-align: center; font-size: 1.5em; float: right;"><h6>LOGIN: <?php echo $prefixo ?>@gmail.com &nbsp;&nbsp; SENHA: <?php echo $prefixo ?></h6></td>
		</tr>
		<tr>
			<td class="corpo"></br>

				<div align="center" class="titulo">

					<h5>
						<u>CONTRATO DE COMODATO</u>
					</h5>

				</div> </br> </br>
				<p>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SHOW PRESTADORA DE SERVIÇOS DO
					BRASIL LTDA - ME, inscrita no CNPJ sob nº 09.338.999/0001-58, com
					sede na Rua Rui Barbosa, 104, Bairro Novo, CEP 58.200-000, Cidade
					de Guarabira, doravante denominada COMODANTE; <strong><u><?php echo $permissionario ?></u></strong>,
					brasileiro, inscrito no CPF sob nº <strong><u><?php echo mascara_cpf($cpf,'###.###.###-##') ?></u></strong>,
					residente e domiciliado nesta Capital, permissionário do prefixo de
					táxi nº <strong><u><?php echo $prefixo ?></u></strong>, doravante
					denominado COMODATÁRIO, e EMPRESA PÚBLICA DE TRANSPORTE E
					CIRCULAÇÃO S/A - EPTC, Empresa Pública do Município de Porto
					Alegre, CNPJ nº 02510700/0001-51, com sede na Rua João Neves da
					Fontoura nº 07, Bairro Azenha, Porto Alegre, doravante denominada
					INTERVENIENTE, pelo presente Contrato de Comodato acordam o
					seguinte:
				</p>

				<p>
					<strong>1. CLÁUSULA PRIMEIRA - OBJETO</strong>
				</p>
				<p>
					<strong>1.1.</strong> Pelo presente instrumento, a COMODANTE,
					gratuitamente, oferece em comodato, os equipamentos para
					implantação do Sistema de Rastreamento em Tempo Real do Transporte
					Individual por Táxi do Município de Porto Alegre ao COMODATÁRIO,
					sob a supervisão da INTERVENIENTE. <br>PARÁGRAFO ÚNICO – O presente
						instrumento é parte da execução do contrato de prestação de
						serviços (Contrato nº 14/2014) firmado entre a COMODANTE e a
						Empresa Pública de Transporte e Circulação S/A (EPTC), ora
						INTERVENIENTE, gerando obrigações, no entanto, apenas entre
						COMODANTE e COMODATÁRIO. 
				
				</p>

				<p>
					<strong>2. CLÁUSULA SEGUNDA - DESTINAÇÃO</strong>
				</p>
				<p>
					<strong>2.1.</strong> Os equipamentos e serviços do Sistema de
					Rastreamento, objeto do presente comodato, destinam-se à utilização
					exclusiva pelo COMODATÁRIO, para o fim específico de rastreamento e
					dimensionamento da frota e segurança dos condutores do Transporte
					Individual por táxi do Município de Porto Alegre. <br>PARÁGRAFO
						ÚNICO – Fica vedada a utilização dos equipamentos e serviços
						comodatados para quaisquer outras finalidades, e proibidos o
						empréstimo, a locação ou sublocação, total ou parcial, cessão ou
						sub-rogação, ou transferência do presente contrato, salvo
						consentimento prévio e por escrito, reservando-se à COMODANTE e à
						INTERVENIENTE pleno direito de fiscalização. 
				
				</p>

				<p>
					<strong>3. CLÁUSULA TERCEIRA - PRAZO</strong>
				</p>
				<p>
					<strong>3.1.</strong> O Contrato de Comodato vigorará por 12 (doze
					meses), a contar de sua assinatura, podendo ser prorrogado por
					iguais períodos, caso nenhuma das partes manifeste o contrário,
					limitando-se em conformidade com a Lei de Licitações e a vigência
					do Contrato nº 14/2014, firmado entre COMODANTE e INTERVENIENTE.
				</p>

				<p>
					<strong>4. CLÁUSULA QUARTA - CONSERVAÇÃO</strong>
				</p>
				<p>
					<strong>4.1.</strong> O COMODATÁRIO aceita as condições avençadas e
					se compromete a manter e zelar pelos equipamentos comodatados, em
					cumprimento ao presente contrato e às leis vigentes. <br><strong>4.2.</strong>
						É de total responsabilidade do COMODATÁRIO a conservação dos
						equipamentos instalados no veículo, tendo a obrigação de
						devolvê-los, em perfeito estado. Caso o equipamento seja
						extraviado ou danificado, verificada responsabilidade do
						COMODATÁRIO, este assumirá uma multa por equipamento no valor de
						R$ 297,54 (duzentos e noventa e sete reais e cinqüenta e quatro
						centavos). <br><strong>4.3.</strong> A instalação, retirada,
							reinstalação ou correção somente será efetuada mediante
							comunicação ao suporte técnico da COMODANTE e será feita por seus
							técnicos. Caso sejam realizadas por outro técnico não autorizado,
							será cobrada multa de R$ 500,00 (quinhentos reais) por
							equipamento instalado. <br><strong>4.4.</strong> Caso o
								COMODATÁRIO necessite de manutenção, posterior à instalação, e
								que esta não seja decorrente de falha na instalação do
								equipamento, ou culpa da COMODANTE, será cobrado o valor de R$
								47,11(quarenta e sete reais e onze centavos) por serviço
								realizado, seja ele, uma correção, retirada ou reinstalação.
								Caso solicitado pelo COMODATÁRIO, que não queira se deslocar até
								a oficina autorizada para efetuar os serviços, será cobrado
								também o deslocamento do técnico para realização dos mesmos,
								contando como ponto inicial a localização do técnico mais
								próximo, no valor de R$ 2,00 (dois reais) por KM rodado até o
								destino final. 
				
				</p>

				<p>
					<strong>5. CLÁUSULA QUINTA - EXCLUSIVIDADE</strong>
				</p>
				<p>
					<strong>5.1.</strong> Tendo em vista o caráter estritamente pessoal
					da relação contratual, relativamente ao COMODATÁRIO, este não
					poderá transmitir os direitos de comodatário a quaisquer outras
					pessoas, nem admitir que quaisquer outras pessoas venham a usufruir
					do Contrato, sob qualquer alegação, salvo o disposto na Cláusula
					Segunda, Parágrafo Único.
				</p>

				<p>
					<strong>6. CLÁUSULA SEXTA - DAS OBRIGAÇÕES DA COMODANTE</strong>
				</p>
				<p>
					<strong>6.1.</strong> A COMODANTE disponibilizará todos os
					equipamentos necessários à implantação do Sistema de Rastreamento,
					bem como toda a assistência técnica necessária à fiel execução dos
					serviços. <br><strong>6.2.</strong> A COMODANTE disponibilizar-se-á
						para atendimento das ocorrências eventuais de suporte, de acordo
						com a necessidade e solicitação do permissionário. <br><strong>6.3.</strong>
							A COMODANTE executará eventuais serviços necessários ao
							atendimento de ocorrências em dias úteis, durante o horário
							comercial (08:30 h às 18:00 h). <br><strong>6.4.</strong>
								Apresentado o veículo, pelo COMODATÁRIO, compete à COMODANTE
								executar os serviços de manutenção e eventual substituição de
								equipamentos nos prazos da alínea “a” do item 4.7 do Termo de
								Referência do Pregão Eletrônico nº 07/2014. <br><strong>6.5.</strong>
									Na hipótese de o taxímetro disponibilizado no prefixo pelo
									COMODATÁRIO se encontrar em perfeito estado de funcionamento e
									atender às especificações dadas pela Seção I do Capítulo III do
									Decreto nº 18.593, de 19 de março de 2014, a COMODANTE garante
									a compatibilidade e o perfeito funcionamento do Sistema de
									Rastreamento com aquele aparelho taximétrico. <br><strong>6.6.</strong>
										A COMODANTE instalará os 02 (dois) pontos de acionamento do
										botão pânico nos locais determinados pelo COMODATÁRIO. 
				
				</p>

				<p>
					<strong>7. CLÁUSULA SÉTIMA - DAS OBRIGAÇÕES DO COMODATÁRIO</strong>
				</p>
				<p>
					<strong>7.1.</strong> O COMODATÁRIO, na qualidade de fiel
					depositário do equipamento cedido em comodato, deverá preservá-lo,
					evitando qualquer tipo de avaria ou danos nas ocasiões em que o
					veículo for objeto de consertos mecânicos, lavagens e lubrificações
					gerais, reformas, colisões, choques, entre outros, assim como
					deverá manter em perfeito estado de funcionamento a bateria do
					veículo, zelando pela integridade, inviolabilidade e permanente e
					correto funcionamento dos equipamentos sob sua guarda; <br><strong>7.2.</strong>
						O COMODATÁRIO deverá proceder à devolução dos equipamentos
						instalados, em regime de comodato, em perfeitas condições ao
						término do prazo contratual. <br><strong>7.3.</strong> O
							COMODATÁRIO deverá manter seu cadastro atualizado junto à
							INTERVENIENTE, que o repassará, quando e na medida das
							necessidades de instalação, à COMODANTE. <br><strong>7.4.</strong>
								O COMODATÁRIO se compromete a disponibilizar taxímetro em
								perfeito estado de funcionamento, conforme especificações da
								Seção I do Capítulo III do Decreto nº 18.593, de 19 de março de
								2014, a fim a transmissão dos dados ao Sistema de Rastreamento.
								<br><strong>7.5.</strong> O COMODATÁRIO deverá comparecer em uma
									das lojas indicadas pela COMODANTE para proceder à instalação
									dos equipamentos de rastreamento, no prazo definido no
									cronograma de instalação determinado pela EPTC. <br><strong>7.6.</strong>
										O COMODATÁRIO deverá comparecer ou apresentar quaisquer
										condutores auxiliares, inclusive eventual arrendatário do
										prefixo, na data e local agendados pela COMODANTE, para a
										realização do treinamento do sistema de rastreamento. 
				
				</p>

				<p>
					<strong>8. CLÁUSULA OITAVA - DA INTERVENIENTE (EPTC)</strong>
				</p>
				<p>
					<strong>8.1.</strong> A INTERVENIENTE, na qualidade de entidade de
					operação, controle e fiscalização do Sistema de Transporte Público
					e de Circulação do Município de Porto Alegre, nos termos da Lei
					Municipal nº 8.133/98, intervém no presente contrato a fim de
					garantir sua correta e perfeita execução. <br><strong>8.2.</strong>
						Caberá à INTERVENIENTE a fiscalização permanente do presente
						instrumento e de todos os seus termos, bem como a observância de
						sua fiel execução. <br><strong>8.3.</strong> A INTERVENIENTE
							supervisionará as relações efetuadas entre COMODANTE e
							COMODATÁRIO, intermediando as questões havidas na execução do
							presente contrato, desde sua assinatura até o acompanhamento dos
							trâmites de instalação dos equipamentos, em oficina
							autorizada/credenciada pela COMODANTE. <br><strong>8.4.</strong>
								Caberá, igualmente, à INTERVENIENTE, acompanhar a assinatura do
								presente contrato pelo COMODATÁRIO, o que deverá ser feito sob
								sua supervisão. <br><strong>8.5.</strong> A INTERVENIENTE poderá
									solicitar, a qualquer tempo, esclarecimentos e documentos que
									entender pertinentes à COMODANTE e ao COMODATÁRIO, visando à
									melhor execução deste instrumento. Igualmente poderá orientar
									as partes quanto a situações em que haja divergências, dúvidas
									ou obscuridades, determinando as providências que entender
									cabíveis, nos termos e limites do Contrato nº 14/2014, firmado
									entre a EPTC e a COMODANTE. <br><strong>8.6.</strong> A
										INTERVENIENTE não responderá por quaisquer danos ou avenças
										emanadas do presente contrato, que sejam oriundos do
										relacionamento firmado entre o COMODANTE e o COMODATÁRIO.
										Poderá, no entanto, compelir a COMODANTE a sanar eventuais
										defeitos na instalação dos equipamentos, assinando prazo para
										o seu cumprimento, nunca inferior a 10 (dez) dias, no qual o
										comodatário se compromete a disponibilizar o veiculo, para a
										execução o serviço. <br><strong>8.7.</strong> A não
											observância do prazo assinalado pela INTERVENIENTE na
											cláusula supra sujeitará a COMODANTE às sanções previstas no
											Contrato nº 14/2014, podendo configurar inexecução contratual
											(Cláusulas 7.1 a 7.3 do Contrato nº 14/2014), após a
											instauração de competente procedimento de aplicação de
											penalidade, nos termos da Lei Federal nº 8.666/93,
											ressalvadas as hipóteses de caso fortuito e força maior, ou
											devidamente justificadas. 
				
				</p>

				<p>
					<strong>9. CLÁUSULA NONA - DAS RESPONSABILIDADES</strong>
				</p>
				<p>
					<strong>9.1.</strong> É de responsabilidade exclusiva da COMODANTE,
					proceder à instalação dos equipamentos nos veículos dos
					permissionários. <br><strong>9.2.</strong> Responderá o COMODATÁRIO
						pelo mau funcionamento do módulo rastreador, decorrente de sua
						própria utilização indevida ou de terceiros, bem como pelos custos
						para sua reparação. <br><strong>9.3.</strong> A não
							disponibilização dos equipamentos à COMODANTE, nos casos
							previstos e no prazo estipulado no presente contrato, na Lei nº
							11.466, de 29 de julho de 2013, ou no Decreto nº 18.593, de 19 de
							março de 2014, após devida notificação, sujeita o COMODATÁRIO,
							uma vez vencido o prazo, ao pagamento de multa diária previamente
							estipulada, de 5% (cinco por cento) do valor de mercado dos
							equipamentos instalados, reajustada pela correção monetária
							anual, além das despesas incidentes, sem prejuízo das penalidades
							administrativas próprias da legislação correlata. <br><strong>9.4.</strong>
								A não devolução do equipamento dado em comodato, quando assim
								solicitado e devidamente notificado, além do vencimento
								antecipado da obrigação, caracterizará apropriação indébita e
								sujeitará o COMODATÁRIO à imediata cobrança do valor integral
								reajustado do equipamento, conforme seu valor de mercado, a
								título de cláusula penal. 
				
				</p>

				<p>
					<strong>10.CLÁUSULA DÉCIMA - LIMITAÇÕES À PRESTAÇÃO DO SERVIÇO</strong>
				</p>
				<p>
					<strong>10.1.</strong> O COMODATÁRIO concorda e tem pleno
					conhecimento de que os serviços disponibilizados limitam-se, única
					e exclusivamente, aos descritos na segunda cláusula da presente
					convenção, isentando-se a COMODANTE de quaisquer danos ou fatos
					oriundos de caso fortuito ou força maior que impeçam ou dificultem
					a localização do veículo sinistrado, tais como: problemas de rede
					telefônica, condições climáticas e topográficas adversas, dentre
					outras situações alheias a esfera de responsabilidade contratual.
					Parágrafo 1º. Fica esclarecido que o vínculo ora estabelecido entre
					a COMODANTE e o COMODATÁRIO, com a supervisão da INTERVENIENTE, não
					constitui e não representa, em hipótese alguma, um contrato de
					seguro, não havendo e/ou não implicando em qualquer cobertura, de
					qualquer natureza, para o COMODATÁRIO, usuários, condutores,
					veículo e/ou terceiros. Parágrafo 2º. O COMODATÁRIO declara que tem
					conhecimento de que poderão ocorrer interferências na área de
					Cobertura, por motivos alheios à vontade da COMODANTE, causando
					eventuais falhas no recebimento e transmissão do sinal do veículo.
					Está ciente, ainda, de que os Serviços poderão ser temporariamente
					interrompidos ou restringidos: se o COMODATÁRIO viajar para fora da
					área de cobertura; e/ou em decorrência de restrições operacionais,
					realocações, reparos e atividades similares, que se façam
					necessárias à apropriada operação e/ou à melhoria do Sistema; e/ou
					em decorrência de interferências de topografia, edificações,
					bloqueios, lugares fechados, condições atmosféricas, etc.; e/ou em
					decorrência de quedas e interrupções no fornecimento de energia e
					sinais de comunicação. Sendo que, na ocorrência das limitações
					acima mencionadas, a COMODANTE não poderá ser responsabilizada por
					quaisquer interrupções, atrasos ou defeitos nas transmissões. <br><strong>10.2.</strong>
						O COMODATÁRIO reconhece e aceita os riscos da instalação, tais
						como remoção e reinstalação do painel, remoção de componentes do
						interior do veículo para instalação do botão pânico, entre outros.
						A COMODANTE não se responsabilizará pelas modificações necessárias
						à instalação dos dispositivos no veiculo, salvo culpa ou dolo de
						seus prepostos, respondendo, outrossim, nos casos de garantia
						legal previstos no Código de Defesa do Consumidor – CDC (Lei
						Federal nº 8.078/90). A COMODANTE compromete-se a prestar toda a
						assistência para sanar eventuais problemas e/ou danos verificados
						durante e após a instalação dos equipamentos, decorrentes de suas
						atividades no veículo. <br><strong>10.3.</strong> A fim de
							conferir efetividade e segurança à cláusula supra, será efetuado
							check-list no veículo a ser alterado, antes e após os
							procedimentos de instalação, conforme modelo fornecido pela
							INTERVENIENTE à empresa COMODANTE, o qual será assinado pelo
							representante da COMODANTE e pelo COMODATÁRIO. 
				
				</p>

				<p>
					<strong>11. CLÁUSULA DÉCIMA PRIMEIRA - PROIBIÇÕES</strong>
				</p>
				<p>
					<strong>11.1.</strong> É expressamente proibido ao COMODATÁRIO: a)
					efetuar quaisquer modificações no equipamento instalado, sem a
					expressa autorização, por escrito, do COMODANTE, além de chancela
					da Empresa Pública de Transporte e Circulação S/A (EPTC), ora
					INTERVENIENTE; b) o acesso aos equipamentos contemplados neste
					contrato por outros prestadores de serviços de monitoramento e
					logística, tanto via tecnologia celular, quanto via satélite; c) a
					modificação das características do programa ou módulos de hardware
					que compõem o equipamento embarcado, sua ampliação, redução ou
					alteração de qualquer forma; d) descumprir ou praticar engenharia
					reversa. <br><strong>11.2.</strong> A infração a quaisquer das
						proibições acima elencadas sujeita o COMODATÁRIO às penas
						previstas na Cláusula Nona, sem prejuízo das sanções
						administrativas aplicadas pela EPTC. 
				
				</p>

				<p>
					<strong>12. CLÁUSULA DÉCIMA SEGUNDA – DOS EQUIPAMENTOS E DA
						TRANSFERÊNCIA A UM NOVO VEÍCULO</strong>
				</p>
				<p>
					<strong>12.1.</strong> O COMODATÁRIO se obriga e se compromete a
					informar à COMODANTE, antecipadamente, sobre toda e qualquer
					transferência do veículo a terceiros, ficando esclarecido que, até
					que isto ocorra, o COMODATÁRIO permanecerá responsável, obrigado e
					vinculado a todos os termos e condições deste contrato. <br><strong>12.2.</strong>
						São condições necessárias para efetuar a transferência dos
						equipamentos a um novo veículo, quando da substituição deste pelo
						COMODATÁRIO: a) Comunicação formal à COMODANTE dos dados do novo
						veículo; b) Declaração expressa do COMODATÁRIO de que é
						proprietário do novo veículo; c) Realização da transferência dos
						equipamentos ao outro veículo por centro de Atendimento Técnico ou
						Agente Técnico Autorizado. <br><strong>12.3.</strong> O
							COMODATÁRIO poderá solicitar a transferência dos Equipamentos
							para outro veiculo, mediante o pagamento da respectiva taxa de
							transferência, e prévia notificação à COMODANTE, bem como ao
							Centro de Atendimento Técnico e o Agente Técnico Autorizado,
							únicos competentes para o ato, permanecendo em vigor os termos e
							condições deste Contrato em relação ao novo veículo. <br><strong>12.4.</strong>
								A transferência e/ou manutenção dos Equipamentos, sem a estrita
								observância do disposto nesta Cláusula, desobrigará a COMODANTE
								da prestação dos Serviços, assim como isentará a mesma de
								qualquer responsabilidade sobre eventuais incidentes, danos
								morais e/ou pessoais causados a terceiros. <br><strong>12.5.</strong>
									A garantia do Equipamento existe enquanto durar o contrato, e
									tem por exclusiva finalidade, a substituição ou reparação, que
									deverão ser realizadas exclusivamente no Centro de Atendimento
									Técnico ou no Agente Técnico Autorizado, referente a
									comprovados vícios de fabricação e demais condições contidas no
									“Certificado de Garantia”, parte integrante do Manual do
									Cliente. <br><strong>12.6.</strong> O COMODATÁRIO declara ser o
										proprietário do veículo, assumindo total responsabilidade, sob
										as penas da lei, pela veracidade da declaração ora prestada e
										consequências dela advindas. 
				
				</p>

				<p>
					<strong>13.CLÁUSULA DÉCIMA TERCEIRA - RESCISÃO CONTRATUAL</strong>
				</p>
				<p>
					<strong>13.1.</strong> Havendo encerramento do contrato de
					prestação de serviços entre COMODANTE e INTERVENIENTE (EPTC),
					referido no parágrafo único da cláusula primeira, o comodato será
					automaticamente rescindido, sem prejuízo das outras obrigações
					assumidas pelo COMODATÁRIO, devendo este comparecer em oficina
					credenciada pela COMODANTE para retirada dos equipamentos
					embarcados, no prazo estabelecido em cronograma. <br><strong>13.2.</strong>
						Reservam-se, ainda, as partes o direito de declararem
						antecipadamente rescindido o presente Contrato, independentemente
						de interpelação, notificação ou aviso prévio, podendo a parte
						inocente exigir imediatamente o cumprimento das obrigações
						contratuais assumidas pela parte infratora nas hipóteses de: a)
						decretação de falência, requerimento de recuperação judicial,
						dissolução judicial, protesto legítimo de título de crédito,
						liquidação ou dissolução extrajudicial de qualquer das partes. b)
						descumprimento de qualquer obrigação assumida em decorrência deste
						Contrato. c) na hipótese de o COMODATÁRIO transferir os direitos e
						obrigações decorrentes do presente Contrato a terceiros, sem a
						prévia notificação e anuência da COMODANTE e da INTERVENIENTE, ou
						de o COMODATÁRIO deixar de manter atualizados seus dados
						cadastrais, de maneira a permitir sua imediata localização. d) na
						hipótese de o COMODATÁRIO utilizar os Serviços em desacordo com o
						Contrato, ou omitir informações que visem obter vantagens
						ilícitas. <br><strong>13.3.</strong> O COMODATÁRIO em caso de
							requerer a rescisão do presente contrato, estará automaticamente
							assumindo a multa contratual correspondente ao pagamento de todos
							os meses vincendos pertinentes ao presente contrato. 
				
				</p>


				<p>
					<strong>14. CLÁUSULA DÉCIMA QUARTA - DISPOSIÇÕES FINAIS</strong>
				</p>
				<p>
					<strong>14.1.</strong> Em qualquer ponto de conflito entre os
					termos de adesão, ou qualquer outro documento, além de acertos
					verbais e o presente instrumento, este CONTRATO prevalecerá. <br><strong>14.2.</strong>
						O COMODATÁRIO informará à COMODANTE, por escrito, sobre qualquer
						caso de alteração nas informações prestadas. As eventuais
						alterações somente obrigarão a COMODANTE, caso sejam entregues, e
						o COMODATÁRIO isenta a COMODANTE de qualquer prejuízo que venha
						ocorrer em função da falta de atualização ou da omissão na entrega
						das informações atualizadas pelo COMODATÁRIO. <br><strong>14.3.</strong>
							O COMODATÁRIO declara que leu todas as Cláusulas do presente
							Contrato e que conhece e entende o seu conteúdo, inclusive a
							essência e os pormenores dos Serviços contemplados neste
							Contrato, e que atesta serem compatíveis com a sua necessidade
							sob o aspecto do tipo, especificação, qualidade e característica
							dos Serviços, ora renunciando expressamente à alegação de
							incompatibilidade, de qualquer natureza ou espécie, conforme
							legislação aplicável. <br><strong>14.4.</strong> A tolerância ou
								omissão das partes do exercício do direito que lhes assista sob
								o presente Contrato, representará mera liberalidade, não podendo
								ser considerada como novação contratual ou renúncia aos mesmos,
								os quais poderão ser exercidos pela parte que se sentir
								prejudicada, a qualquer tempo. <br><strong>14.5.</strong> Se
									qualquer das cláusulas ou disposições do presente Contrato for
									considerada ilegal ou inválida, em qualquer procedimento
									administrativo ou judicial, a ilegalidade, invalidade ou
									ineficiência então verificada deverá ser interpretada
									restritivamente, não afetando as demais cláusulas do Contrato,
									as quais permanecerão válidas e em pleno vigor. <br><strong>14.6.</strong>
										O Contrato obriga as partes Contratantes e seus sucessores a
										qualquer título. <br><strong>14.7.</strong> Qualquer alteração
											e/ou aditamento do presente Contrato somente produzirá
											efeitos legais quando feitos por escrito e devidamente
											assinado pelas partes. <br><strong>14.8.</strong> A
												Contratada poderá ceder e transferir os direitos e
												obrigações decorrentes deste Contrato, integral ou
												parcialmente, a seu exclusivo critério, comunicando
												previamente o Contratante, assumindo o cessionário todas as
												obrigações perante o Contratante por força deste Contrato.
												Entretanto, a Contratada permanecerá obrigada, individual e
												solidariamente com o cessionário, durante o primeiro período
												de vigência deste Contrato. Toda e qualquer transferência
												deverá ser comunicada ao Contratante com antecedência de 10
												(dez) dias e por escrito, para a atualização dos dados
												cadastrais. <br><strong>14.9.</strong> As partes pactuam que
													todas as comunicações formais entre elas, inclusive, mas
													não limitadamente, para fins de pedido de devolução dos
													Equipamentos, dever-se-ão dar por meio escrito por carta
													registrada, via fac-símile com comprovante de transmissão,
													via correio eletrônico conforme dados constantes na
													primeira página deste Contrato, ou por via de telegrama com
													cópia e aviso de recepção, aos endereços apontados na
													qualificação delas neste instrumento, sendo ajustado também
													que a simples entrega da comunicação nestes endereços,
													independentemente da pessoa receptora ou, ainda mesmo, a
													não entrega desde que por motivo de recusa ou de endereço
													inexistente, serão consideradas como tendo sido eficazmente
													realizada a comunicação. <br><strong>14.10.</strong>
														Qualquer parte que vier alterar seu endereço deverá dar
														notícia à outra pela via escrita e na forma acima
														apontada, sob pena de não poder arguir a ineficiência de
														qualquer comunicação enviada ao endereço até então
														declarado. <br><strong>14.11.</strong> O COMODATÁRIO
															declara neste ato estar ciente que a instalação do
															equipamento e a prestação do serviço não garantem a
															recuperação do veículo em casos de furto ou roubo. <br><strong>14.12.</strong>
																A COMODANTE não se responsabilizará e não indenizará o
																COMODATÁRIO nos seguintes casos: a) Danos, furtos,
																roubos, assaltos ou seqüestros, que venham a ocorrer com
																o veículo do COMODATÁRIO e seus ocupantes; b) Mau uso do
																equipamento (abertura e remoção indevida do equipamento,
																alteração de voltagem do veículo, inversão do cabo de
																bateria, entre outros); c) Instalação e manutenções
																realizadas por terceiros, estranhos à equipe de
																instalação da COMODANTE ou que não tenham sido indicados
																por ela. d) Instalação e manutenções, efetuadas por
																terceiros, de quaisquer equipamentos que venham a
																utilizar ou interferir no sistema elétrico do veículo.
																e) Caso o COMODATÁRIO não compareça na oficina de
																reparos da COMODANTE, para manutenção do equipamento,
																quando solicitado para tanto. 
				
				</p>

				<p>
					<strong>15. CLÁUSULA DÉCIMA QUINTA - DO FORO</strong>
				</p>
				<p>
					<strong>15.1.</strong> Os contraentes elegem o foro da Comarca de
					Porto Alegre, Estado do Rio Grande do Sul, para a solução de
					quaisquer controvérsias que, porventura, surgirem em decorrência
					deste contrato, renunciando a qualquer outro, por mais privilegiado
					que seja. <br>Por estarem de comum acordo com as cláusulas do
						Contrato de Comodato, assinam COMODANTE, COMODATÁRIO e
						INTERVENIENTE, na presença de testemunhas, para que surtam os
						devidos e legais efeitos.
						<p>

							<br />

							<div align="center">
								<h6 align="center">Porto Alegre, ____/____/_____</h6>
							</div>

							<br />


							<div align="center">
								<center>
									<img src="<?php echo base_url('media')?>/img/ass_eduardo.png" />
								</center>
								________________________________________________________<br />Eduardo
								Lacet
					
					</br>Show Prestadora de Serviços do Brasil Ltda – ME</br>CNPJ.
					09.338.999/0001-58
					</div>

					<br />

					<div align="center">
						________________________________________________________<br />COMODATÁRIO
					</div>

					<br />

					<div align="center">
						________________________________________________________<br />Diretor-Presidente
						da EPTC (Interveniente)
					</div>

					<br />

					<div align="center">
						________________________________________________________<br />Diretor
						Administrativo-Financeiro da EPTC (Interveniente)
					</div>

					<br />

					<div align="center">
						________________________________________________________<br />
						1&ordf; Testemunha
					</div>

					<br />

					<div align="center">
						________________________________________________________<br />
						2&ordf; Testemunha
					</div>

					<br /> <br />

					<div align="center">
						<b>Impresso em <?php echo data_for_humans($data_impressao) ?></br>Av.
							Rui Barbosa, Nº 104 - CEP: 58200-000 - Centro - Guarabira/PB<br />www.showtecnologia.com
							| (83) 3271-6559
						</b>
					</div>
				</td>
		</tr>
	</table>
</body>
</html>