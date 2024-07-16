<?php

// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa				        |
// | 														                                   			  |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+
// +--------------------------------------------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>              		             				|
// | Desenvolvimento Boleto Banco do Brasil: Daniel William Schultz / Leandro Maniezo / Rogério Dias Pereira|
// +--------------------------------------------------------------------------------------------------------+


if (!defined('BASEPATH'))
  exit('No direct script access allowed');
if (!function_exists('boleto_bb')) {

    /**
     * Boleto Banco do Brasil
     * @param  Array $dados_cliente  Dados do cliente
     * @param  Array $dados_empresa  Dados da Empresa
     * @param  Array $dados_boleto   Dados do Boleto
     * @param  Array $valores_boleto Valores do Boleto
     */
    //function boleto_bb($dados_cliente = null, $dados_empresa = null, $dados_boleto = null, $valores_boleto = null) {
    function boleto_bb($dados = null) {
        // DADOS DO BOLETO PARA O SEU CLIENTE
      $dias_de_prazo_para_pagamento = $dados['dias_de_prazo_para_pagamento'];
      $taxa_boleto = $dados['taxa_boleto'];
      $juros_mes = $dados['juros_mes'];
      $dadosboleto["juros"] = (($dados['valor_cobrado'] * $juros_mes)/100)/30;
      
        $data_venc = $dados['data_venc']; //date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
        $valor_cobrado = $dados['valor_cobrado']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".", $valor_cobrado);
        $valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');
        


        $dadosboleto["nosso_numero"] = $dados['nosso_numero'];
        $dadosboleto["numero_documento"] = $dados['numero_documento']; // Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $dados['data_vencimento']; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = $dados['data_documento'];// date("d/m/Y"); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = $dados['data_processamento']; //date("d/m/Y"); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = number_format($dados['valor_boleto'] + $taxa_boleto, 2, ',', ''); //$valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
        // DADOS DO SEU CLIENTE
        $dadosboleto["sacado"]    = $dados['sacado']; //"Nome do seu Cliente";
        $dadosboleto["endereco1"] = $dados['endereco1']; //"Endereço do seu Cliente";
        $dadosboleto["endereco2"] = $dados['endereco2']; //"Cidade - Estado -  CEP: 00000-000";

        // INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo1"] = "Pagamento referente fatura: ".$dados['numero_documento'];
        $dadosboleto["demonstrativo2"] = "<br>" ;//. number_format($taxa_boleto, 2, ',', '');
        $dadosboleto["demonstrativo3"] = "2a. via - http://www.showtecnologia.com/financeiro";

        // INSTRUÇÕES PARA O CAIXA
        $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento e juros de 0,33% ao dia";
        $dadosboleto["instrucoes2"] = "- Receber até ".$dias_de_prazo_para_pagamento." dias após o vencimento";
        $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: (83) 3271-6559";
        $dadosboleto["instrucoes4"] = "- Email: financeiro@showtecnologia.com";

        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "N";
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "DM";


        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
        // DADOS DA SUA CONTA - BANCO DO BRASIL
        $dadosboleto["agencia"] = "0200"; // Num da agencia, sem digito
        $dadosboleto["conta"] = "28629"; 	// Num da conta, sem digito
        // DADOS PERSONALIZADOS - BANCO DO BRASIL
        $dadosboleto["convenio"] = "2852865";  // Num do convenio - REGRA: 6 ou 7 ou 8 digitos
        //CONVENIO:  2409314 - sem registro    --    2325061 - com registro
        $dadosboleto["contrato"] = "018899626"; // Num do seu contrato
        //CONTRATO:  018899626 - sem registro  --    018810720 - com registro
        $dadosboleto["carteira"] = "17";    // 18 - sem registro   --   17 - com registro
        $dadosboleto["variacao_carteira"] = "-027";  // Variaçãoo da Carteira, com traço (opcional)

        // TIPO DO BOLETO
        $dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convenio c/ 8 digitos, 7 p/ Convenio c/ 7 digitos, ou 6 se Convenio c/ 6 digitos
        $dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convenio c/ 6 digitos: informe 1 se for NossoNumero de ate 5 digitos ou 2 para opção de ate 17 digitos
        
        /*
          #################################################
          DESENVOLVIDO PARA CARTEIRA 18

          - Carteira 18 com Convenio de 8 digitos
          Nosso número: pode ser até 9 dígitos

          - Carteira 18 com Convenio de 7 digitos
          Nosso número: pode ser até 10 dígitos

          - Carteira 18 com Convenio de 6 digitos
          Nosso número:
          de 1 a 99999 para opção de até 5 dígitos
          de 1 a 99999999999999999 para opção de até 17 dígitos

          #################################################
         */


        // SEUS DADOS

          $dadosboleto["identificacao"] = "SHOW PRESTADORA DE SERVICOS DO BRASIL LTDA";
          $dadosboleto["cpf_cnpj"] = "09.338.999/0001-58";
          $dadosboleto["endereco"] = "Av. Ruy Barbosa, 104 - Centro";
          $dadosboleto["cidade_uf"] = "Guarabira - PB - CEP: 58.200-000";
          $dadosboleto["cedente"] = "SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA";


        // NÃO ALTERAR!
          include("include/funcoes_bb.php");
          include("include/layout_bb.php");
        }


        function boleto_unicred ($dados){
            // DADOS DO BOLETO PARA O SEU CLIENTE
              $dias_de_prazo_para_pagamento = $dados['dias_de_prazo_para_pagamento'];
            $taxa_boleto = $dados['taxa_boleto'];
            $juros_mes = $dados['juros_mes'];
            $dadosboleto["juros"] = (($dados['valor_cobrado'] * $juros_mes)/100)/30;
            
              $data_venc = $dados['data_venc']; //date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
              $valor_cobrado = $dados['valor_cobrado']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
              $valor_cobrado = str_replace(",", ".", $valor_cobrado);
              $valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');
              

              $dadosboleto["inicio_nosso_numero"] = date("y");  // Ano da geração do título ex: 07 para 2007
              $dadosboleto["nosso_numero"] = $dados['nosso_numero'];
              $dadosboleto["numero_documento"] = $dados['numero_documento']; // Num do pedido ou do documento
              $dadosboleto["data_vencimento"] = $dados['data_vencimento']; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
              $dadosboleto["data_documento"] = $dados['data_documento'];// date("d/m/Y"); // Data de emissão do Boleto
              $dadosboleto["data_processamento"] = $dados['data_processamento']; //date("d/m/Y"); // Data de processamento do boleto (opcional)
              $dadosboleto["valor_boleto"] = number_format($dados['valor_boleto'] + $taxa_boleto, 2, ',', ''); //$valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
              // DADOS DO SEU CLIENTE
              $dadosboleto["sacado"]    = $dados['sacado']; //"Nome do seu Cliente";
              $dadosboleto["endereco1"] = $dados['endereco1']; //"Endereço do seu Cliente";
              $dadosboleto["endereco2"] = $dados['endereco2']; //"Cidade - Estado -  CEP: 00000-000";

              // INFORMACOES PARA O CLIENTE
              $dadosboleto["demonstrativo1"] = "Pagamento referente fatura: ".$dados['numero_documento'];
              $dadosboleto["demonstrativo2"] = "<br>" ;//. number_format($taxa_boleto, 2, ',', '');
              $dadosboleto["demonstrativo3"] = "2a. via - http://www.showtecnologia.com/financeiro";

              // INSTRUÇÕES PARA O CAIXA
              $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento e juros de 0,33% ao dia";
              $dadosboleto["instrucoes2"] = "- Receber até ".$dias_de_prazo_para_pagamento." dias após o vencimento";
              $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: (83) 3271-6559";
              $dadosboleto["instrucoes4"] = "- Email: financeiro@showtecnologia.com";

              // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
              $dadosboleto["quantidade"] = "";
              $dadosboleto["valor_unitario"] = "";
              $dadosboleto["aceite"] = "N";
              $dadosboleto["especie"] = "R$";
              $dadosboleto["especie_doc"] = "DM";


              // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
              // DADOS DA SUA CONTA - SICREDI
              $dadosboleto["agencia"] = "2201";   // Num da agencia (4 digitos), sem Digito Verificador
              $dadosboleto["conta"] = "17536";  // Num da conta (5 digitos), sem Digito Verificador
              $dadosboleto["conta_dv"] = "6";   // Digito Verificador do Num da conta

              // DADOS PERSONALIZADOS - SICREDI
              $dadosboleto["posto"]= "01";      // Código do posto da cooperativa de crédito
              $dadosboleto["byte_idt"]= "2";    // Byte de identificação do cedente do bloqueto utilizado para compor o nosso número.
              // 1 - Idtf emitente: Cooperativa | 2 a 9 - Idtf emitente: Cedente
              $dadosboleto["carteira"] = "A";   // Código da Carteira: A (Simples)

              // SEUS DADOS
              $dadosboleto["identificacao"] = "NORIO MOMOI EPP";
              $dadosboleto["cpf_cnpj"] = "21.698.912/0001-59";
              $dadosboleto["endereco"] = "Rua Augusto Almeida, S/N - Novo";
              $dadosboleto["cidade_uf"] = "Guarabira - PB - CEP: 58.200-000";
              $dadosboleto["cedente"] = "NORIO MOMOI EPP";

              // NÃO ALTERAR!
              include("include/funcoes_sicredi.php");
              include("include/layout_sicredi.php");
    }

}
?>
