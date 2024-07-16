<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

class Conversor extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
    }

    function index() {
		$data['titulo'] = 'Converter arquivo CSV em arquivo XML de importalção de NFS-e';
		$this->load->view('fix/header', $data);
		$this->load->view('conversor/conversor');
		$this->load->view('fix/footer');
	}

	function converter() {

        $arquivo=($_FILES['csv']['tmp_name']);
        //Mapeia extrai e mapeia um array do CSV usando o delimitador de ponto e vírgula.
        $rows = array_map(function($v){return explode(';', $v);}, file($arquivo));

        //Remove a primeira linha do array, os títulos das colunas.
        $titulos = array_shift($rows);

        //Show = 1, Norio = 2
        $empresa = $this->getEmpresa($this->input->post('empresa'));

        //Monta o cabeçalho do XML.
        $xml = '<nfse:GerarNfseResposta xmlns:dsig="http://www.w3.org/2000/09/xmldsig#" 
        xmlns:nfse="http://www.abrasf.org.br/nfse.xsd"><nfse:ListaNfse>';

        foreach ($rows as $key => $nfse){
            $nota = [];

            //Calcular valor líquido da NFSE
            $nota['valor_liquido'] = $nfse[17] - $nfse[21];

            //Var para comparar descrição do serviço.
            $string = 'ALUGUEL DE OUTRAS MÁQUINAS E EQUIPAMENTOS COMERCIAIS E INDUSTRIAIS NÃO ESPECIFICADOS 
            ANTERIORMENTE, SEM OPERADOR';
            $servico = $nfse[24];
            //Caso a discriminação do serviço bater com a descrição contida na string, atribuiremos o código
            // do serviço pertencente ao serviço.
            //Caso contrário, será atribuido o código de serviço do outro serviço que a show e norio prestam.
            if(mb_strtolower($this->sanitizeString($servico), 'UTF-8')  == mb_strtolower($this->sanitizeString($servico), 'UTF-8')){
                $nota['item_lista_servico'] = '3';
                $codigoTributacaoMunicipio = '7739099';
            }else{
                $nota['item_lista_servico'] = '107';
                $codigoTributacaoMunicipio = '8020001';
            }
            $tomador = [];

            //Consumir api dos estados brasileiros.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
            curl_setopt($ch,CURLOPT_ENCODING , "");
            curl_setopt($ch, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v1/localidades/estados");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8") );
            $result = curl_exec($ch);

            curl_close($ch);

            $estados = json_decode($result, true);

            //Obter código IBGE do estado do tomador da NFS-e
            if($estados != '' and $estados != null){
                foreach ($estados as $estado){
                    if (mb_strtolower($this->sanitizeString($estado['sigla']), 'UTF-8') === mb_strtolower($this->sanitizeString($nfse[14]), 'UTF-8')){
                        $tomador['cod_uf'] = $estado['id'];
                        break;
                    }
                }
            }

            if (!isset($tomador['cod_uf'])){
                $tomador['cod_uf'] = '00';
            }

            //Consumir api de municípios usando o código IBGE do estado do tomador para buscar.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
            curl_setopt($ch,CURLOPT_ENCODING , "");
            curl_setopt($ch, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v1/localidades/estados/"
                .$tomador['cod_uf']."/municipios/");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8") );
            $result = curl_exec($ch);

            curl_close($ch);

            $municipios = json_decode($result, true);

            //Obter código IBGE do município do tomador.
            if($municipios != '' and $municipios != null){
                foreach ($municipios as $municipio){
                    if(mb_strtolower($this->sanitizeString($municipio['nome']), 'UTF-8') == mb_strtolower($this->sanitizeString($nfse[13]), 'UTF-8')){
                        $tomador['cod_municipio'] = $municipio['id'];
                        break;
                    }
                }
            }

            if (!isset($tomador['cod_municipio'])){
                $tomador['cod_municipio'] = '00';
            }

            //Caso haja valor de retenção de ISS, atribuiremos o valor 2, que representa que o responsável pela
            // retenção de ISS é um terceiro ao invés do tomador.
            $nota['responsavel_retencao'] = ($nfse[21] and  $nfse[21] > 0.00 and $nfse[21] != '0.00') ? '2' : '';
            $nota['exigibilidade_iss'] = (trim($servico) == trim($string)) ? '2' : '1';

            //Nesta etapa, iremos gerar uma tag "CompNFSE que representa uma NFS-e do lote, contendo os dados.
            $xml .= '<nfse:CompNfse>
                            <nfse:Nfse versao="2.02">
                                <nfse:InfNfse>
                                    <nfse:Numero>'.$nfse[0].'</nfse:Numero>
                                    <nfse:CodigoVerificacao>'.$nfse[1].'</nfse:CodigoVerificacao>
                                    <nfse:DataEmissao>'.$nfse[2].'</nfse:DataEmissao>
                                    <nfse:OutrasInformacoes>
                                    </nfse:OutrasInformacoes>
                                    <nfse:ValoresNfse>
                                        <nfse:BaseCalculo>'.$nfse[19].'</nfse:BaseCalculo>
                                        <nfse:Aliquota>'.''.'</nfse:Aliquota>
                                        <nfse:ValorLiquidoNfse>'.$nota['valor_liquido'].'</nfse:ValorLiquidoNfse>
                                    </nfse:ValoresNfse>
                                    <nfse:PrestadorServico>
                                        <nfse:IdentificacaoPrestador>
                                            <nfse:CpfCnpj>
                                                <nfse:Cnpj>'.$empresa['cnpj'].'</nfse:Cnpj>
                                            </nfse:CpfCnpj>
                                            <nfse:InscricaoMunicipal>'.$empresa['insc_municipal'].'</nfse:InscricaoMunicipal>
                                        </nfse:IdentificacaoPrestador>
                                        <nfse:RazaoSocial>'.$empresa['razao'].'</nfse:RazaoSocial>
                                        <nfse:NomeFantasia>'.$empresa['nome'].'</nfse:NomeFantasia>
                                        <nfse:Endereco>
                                            <nfse:Endereco>'.$empresa['endereco'].'</nfse:Endereco>
                                            <nfse:Numero>'.$empresa['numero'].'</nfse:Numero>
                                            <nfse:Complemento>'.$empresa['complemento'].'</nfse:Complemento>
                                            <nfse:Bairro>'.$empresa['bairro'].'</nfse:Bairro>
                                            <nfse:CodigoMunicipio>'.$empresa['cod_municipio'].'</nfse:CodigoMunicipio>
                                            <nfse:Uf>'.$empresa['UF'].'</nfse:Uf>
                                            <nfse:CodigoPais>'.$empresa['codpais'].'</nfse:CodigoPais>
                                            <nfse:Cep>'.$empresa['cep'].'</nfse:Cep>
                                        </nfse:Endereco>
                                        <nfse:Contato>
                                            <nfse:Telefone>'.$empresa['fone'].'</nfse:Telefone>
                                            <nfse:Email>'.$empresa['email'].'</nfse:Email>
                                        </nfse:Contato>
                                    </nfse:PrestadorServico>
                                    <nfse:OrgaoGerador>
                                        <nfse:CodigoMunicipio>'.$empresa['cod_municipio'].'</nfse:CodigoMunicipio>
                                        <nfse:Uf>'.$empresa['UF'].'</nfse:Uf>
                                    </nfse:OrgaoGerador>
                                    <nfse:DeclaracaoPrestacaoServico>
                                        <nfse:InfDeclaracaoPrestacaoServico>
                                            <nfse:Rps>
                                                <nfse:IdentificacaoRps>
                                                    <nfse:Numero>'.$nfse[3].'</nfse:Numero>
                                                    <nfse:Serie>'.$empresa['rps_serie'].'</nfse:Serie>
                                                    <nfse:Tipo>'.$empresa['rps_tipo'].'</nfse:Tipo>
                                                </nfse:IdentificacaoRps>
                                                <nfse:DataEmissao>'.$nfse[4].'</nfse:DataEmissao>
                                                <nfse:Status>'.''.'</nfse:Status>
                                            </nfse:Rps>
                                            <nfse:Competencia>'.$nfse[4].'</nfse:Competencia>
                                            <nfse:Servico>
                                                <nfse:Valores>
                                                    <nfse:ValorServicos>'.$nfse[17].'</nfse:ValorServicos>
                                                    <nfse:ValorIss>'.$nfse[20].'</nfse:ValorIss>
                                                    <nfse:Aliquota>'.''.'</nfse:Aliquota>
                                                </nfse:Valores>
                                                <nfse:IssRetido>'.$nfse[21].'</nfse:IssRetido>
                                                <nfse:ResponsavelRetencao>'.$nota['responsavel_retencao'].'</nfse:ResponsavelRetencao>
                                                <nfse:ItemListaServico>'.$nota['item_lista_servico'].'</nfse:ItemListaServico>
                                                <nfse:CodigoTributacaoMunicipio>'.$codigoTributacaoMunicipio.'</nfse:CodigoTributacaoMunicipio>
                                                <nfse:Discriminacao>'.$nfse[24].'</nfse:Discriminacao>
                                                <nfse:CodigoMunicipio>'.$empresa['cod_municipio'].'</nfse:CodigoMunicipio>
                                                <nfse:CodigoPais>'.$empresa['codpais'].'</nfse:CodigoPais>
                                                <nfse:ExigibilidadeISS>'.$nota['exigibilidade_iss'].'</nfse:ExigibilidadeISS>
                                            </nfse:Servico>
                                            <nfse:Prestador>
                                                <nfse:CpfCnpj>
                                                    <nfse:Cnpj>'.$empresa['cnpj'].'</nfse:Cnpj>
                                                </nfse:CpfCnpj>
                                                <nfse:InscricaoMunicipal>'.$empresa['insc_municipal'].'</nfse:InscricaoMunicipal>
                                            </nfse:Prestador>
                                            <nfse:Tomador>
                                                <nfse:IdentificacaoTomador>
                                                    <nfse:CpfCnpj>
                                                        <nfse:Cnpj>'.$this->sanitizeString($nfse[6]).'</nfse:Cnpj>
                                                    </nfse:CpfCnpj>
                                                </nfse:IdentificacaoTomador>
                                                <nfse:RazaoSocial>'.''.'</nfse:RazaoSocial>
                                                <nfse:Endereco>
                                                    <nfse:Endereco>'.$nfse[8].'</nfse:Endereco>
                                                    <nfse:Numero>'.$nfse[9].'</nfse:Numero>
                                                    <nfse:Bairro>'.$nfse[11].'</nfse:Bairro>
                                                    <nfse:CodigoMunicipio>'.$tomador['cod_municipio'].'</nfse:CodigoMunicipio>
                                                    <nfse:Uf>'.$nfse[14].'</nfse:Uf>
                                                    <nfse:CodigoPais>'.'1058'.'</nfse:CodigoPais>
                                                    <nfse:Cep>'.$this->sanitizeString($nfse[12]).'</nfse:Cep>
                                                </nfse:Endereco>
                                                <nfse:Contato>
                                                    <nfse:Telefone>'.''.'</nfse:Telefone>
                                                </nfse:Contato>
                                            </nfse:Tomador>
                                            <nfse:RegimeEspecialTributacao>'.''.'</nfse:RegimeEspecialTributacao>
                                            <nfse:OptanteSimplesNacional>'.''.'</nfse:OptanteSimplesNacional>
                                            <nfse:IncentivoFiscal>'.''.'</nfse:IncentivoFiscal>
                                        </nfse:InfDeclaracaoPrestacaoServico>
                                    </nfse:DeclaracaoPrestacaoServico>
                                </nfse:InfNfse>
                            </nfse:Nfse>
                        </nfse:CompNfse>';
        }

        //Monta o rodapé do XML
        $xml .= '<nfse:ListaMensagemAlertaRetorno/>
            </nfse:ListaNfse>
        </nfse:GerarNfseResposta>';

        $data['xml'] = $xml;

        $mes = '';

        //Ao editar o CSV no excel o formato da data muda o separador de - para /
        if (isset($nfse[2])){
            $dataDeEmissao = $nfse[2];
            $partes = explode("-", $dataDeEmissao);
            if (isset($partes[1])){
                $mes = $partes[1];
            }
        }

        if ($mes == '' or $mes == null){
            $dataDeEmissao = $nfse[2];
            $partes = explode("/", $dataDeEmissao);
            if (isset($partes[1])){
                $mes = $partes[1];
            }
        }
        $data['mesdeemissao'] = $mes;
        $data['conversor'] = $this;
        $this->load->view('conversor/xml', $data);
    }

	//Retorna os dados de uma das duas empresas de acordo com o valor passado no input (1 ou 2).
	function getEmpresa($empresa) {
	    switch ($empresa){
            case 1:
                $empresa = [];
                $empresa['cnpj'] = '09338999000158';
                $empresa['insc_municipal'] = '17209';
                $empresa['razao'] = 'SHOW PRESTADORA DE SERVICO DO BRASIL LTDA';
                $empresa['nome'] = 'SHOW PRESTADORA';
                $empresa['endereco'] = 'R RUI BARBOSA';
                $empresa['numero'] = '104';
                $empresa['complemento'] = 'ANEXO 112';
                $empresa['bairro'] = 'CENTRO';
                $empresa['cod_municipio'] = '2506301';
                $empresa['UF'] = 'PB';
                $empresa['codpais'] = '1058';
                $empresa['cep'] = '58200000';
                $empresa['fone'] = '8332714060';
                $empresa['email'] = '';
                $empresa['rps_serie'] = '';
                $empresa['rps_tipo'] = '';
            break;
            case 2:
                $empresa = [];
                $empresa['cnpj'] = '21698912000159';
                $empresa['insc_municipal'] = '17209';
                $empresa['razao'] = 'NORIO MOMOI';
                $empresa['nome'] = 'SIGA-ME RASTREAMENTO';
                $empresa['endereco'] = 'R NAPOLEAO LAUREANO';
                $empresa['numero'] = 'S/N';
                $empresa['complemento'] = 'S/C';
                $empresa['bairro'] = 'NOVO';
                $empresa['cod_municipio'] = '2506301';
                $empresa['UF'] = 'PB';
                $empresa['codpais'] = '1058';
                $empresa['cep'] = '58200000';
                $empresa['fone'] = '8332714060';
                $empresa['email'] = 'oficinadorastreador@gmail.com';
                $empresa['rps_serie'] = '';
                $empresa['rps_tipo'] = '';
            break;
        }
        return $empresa;
    }

    function sanitizeString($str) {
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        $str = preg_replace('/[^a-z0-9]/i', '', $str);
        $str = preg_replace('/_+/', '', $str);
        return $str;
    }
}
