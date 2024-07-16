<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nfes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('fatura');
    }

    function printNfe($empresa = false, $id_fatura = false)
    {
        if ($id_fatura && $empresa) {
            $fatura = $this->fatura->get(array('cad_faturas.Id' => "{$id_fatura}"));
            $id_res = $this->fatura->getNumRpsById($id_fatura);
            $per_cofins = number_format(($fatura->COFINS / 100) * $fatura->valor_total, 2, ',', '.');
            $per_irpj = number_format(($fatura->IRPJ / 100) * $fatura->valor_total, 2, ',', '.');
            $per_contSocial = number_format(($fatura->Cont_Social / 100) * $fatura->valor_total, 2, ',', '.');
            $per_pis = number_format(($fatura->PIS / 100) * $fatura->valor_total, 2, ',', '.');
            $per_iss = number_format(($fatura->ISS / 100) * $fatura->valor_total, 2, ',', '.');
            $valor_liquido = number_format($fatura->valor_total - ($per_pis + $per_cofins + $per_contSocial + $per_irpj + $per_iss), 2, ',', '.');

            $dados = array(
                'id_rps' => $id_res,
                'empresa' => $empresa,
                'id' => $fatura->Id,
                'razao_social' => $fatura->razao_social != '' ? $this->sanitizeString($fatura->razao_social) : $this->sanitizeString($fatura->nome),
                'cnpj' => $fatura->cnpj != '' ? $fatura->cnpj : $fatura->cpf,
                'tomador_inscrmunicipal' => '',
                'endereco' => $this->sanitizeString($fatura->endereco),
                'end_numero' => $fatura->id,
                'end_complemento' => $this->sanitizeString($fatura->complemento),
                'bairro' => $this->sanitizeString($fatura->bairro),
                'cep' => $fatura->cep,
                'municipio' => $this->sanitizeString($fatura->cidade),
                'uf' => $fatura->uf,
                'email' => isset($fatura->email) ? $fatura->email : '',
                'iss' => number_format($fatura->ISS, 2, ',', '.'),
                'discriminacao' => $this->sanitizeString($fatura->descricao_item),
                'valortotal' => number_format($fatura->valor_total, 2, ',', '.'),
                'valordeducoes' => number_format($per_iss + $per_cofins + $per_contSocial + $per_irpj + $per_pis, 2, ',', '.'),
                'codservico' => $fatura->cod_servico,
                'disc_servico' => $this->sanitizeString($fatura->descriminacao_servico),
                'valoriss' => number_format((($fatura->ISS / 100) * $fatura->valor_total), 2, ',', '.'),
                'basecalculo' => number_format($fatura->valor_total, 2, ',', '.'),
                'issretido' => number_format((($fatura->ISS / 100) * $fatura->valor_total), 2, ',', '.'),
                'nota_fiscal' => $fatura->nota_fiscal,
                'chave_nota' => $fatura->chave_nfe,
                'cofins' => number_format($per_cofins, 2, ',', '.'),
                'irpj' => number_format($per_irpj, 2, ',', '.'),
                'cont_social' => number_format($per_contSocial, 2, ',', '.'),
                'pis' => number_format($per_pis, 2, ',', '.'),
                'liquido' => $valor_liquido
            );
        }

        if ($empresa == '2')
            $this->load->view('faturas/printNfe_norio', $dados);
        else
            $this->load->view('faturas/printNfe_show', $dados);
    }

    function notaByIdFatura($empresa)
    {
        $id_fatura = $_POST['id'];
        $fatura = $this->fatura->get(array('cad_faturas.Id' => "{$id_fatura}"));
        $msg = false;

        if ($fatura->informacoes == 'SIMM2M') $empresa = '1'; # GERA NO LOTE DA SHOW

        // INFORMAÇÕES OBRIGATORIAS //
        if ($fatura->razao_social == '' AND $fatura->nome == '') $msg = 'Informação Obrigatória: Razão Social (Local: Cadastro do Cliente)';
        if ($fatura->endereco == '' || !isset($fatura->endereco) || $fatura->bairro == '' || !isset($fatura->bairro) || $fatura->cidade == '' || !isset($fatura->cidade))
            $msg = 'Informação Obrigatória: Endereço (Local: Cadastro do Cliente)';
        if ($fatura->cep == '' || !isset($fatura->cep)) $msg = 'Informação Obrigatória: CEP (Local: Cadastro do Cliente)';
        if (strlen($fatura->cep) <= 8) $msg = 'O CEP do cliente é inválido. Verifique e tente novamente mais tarde!';
        if ($fatura->descricao_item == '' || !isset($fatura->descricao_item)) $msg = 'Informção Obrigatória: Descricão Item (Local: Fatura)';
        if ($fatura->cod_servico == '' || !isset($fatura->cod_servico)) $msg = 'Informação Obrigatória: Código do Serviço (Local: Cadastro do Cliente)';
        if ($fatura->ISS == '' || !isset($fatura->ISS)) $msg = 'Informação Obrigatória: % ISS (Local: Cadastro do Cliente)';
        if ($fatura->descriminacao_servico == '' || !isset($fatura->descriminacao_servico)) $msg = 'Informação Obrigatória: Descrição do Serviço (Local: Cadastro do Cliente)';

        if (!$msg) {
            // BUSCA O NUMERO DO ULTIMO RPS GERADO
            $id_rps = $this->fatura->getEndRps();
            $id_res = $id_rps + 1;

            $dados = array(
                'id_rps' => $id_res,
                'empresa' => $empresa,
                'id' => $fatura->Id,
                'razao_social' => $fatura->razao_social != '' ? $this->sanitizeString($fatura->razao_social) : $this->sanitizeString($fatura->nome),
                'cnpj' => $fatura->cnpj != '' ? $fatura->cnpj : $fatura->cpf,
                'tomador_inscrmunicipal' => '',
                'endereco' => $this->sanitizeString($fatura->endereco),
                'end_numero' => $fatura->id,
                'end_complemento' => $this->sanitizeString($fatura->complemento),
                'bairro' => $this->sanitizeString($fatura->bairro),
                'cep' => str_replace('.', '', $fatura->cep),
                'municipio' => $this->sanitizeString($fatura->cidade),
                'uf' => $fatura->uf,
                'email' => isset($fatura->email) ? $fatura->email : '',
                'iss' => number_format($fatura->ISS, 2, '.', ''),
                'discriminacao' => $this->sanitizeString($fatura->descricao_item),
                'valortotal' => number_format($fatura->valor_total, 2, '.', ''),
                'valordeducoes' => number_format(($fatura->ISS / 100) * $fatura->valor_total, 2, '.', ''),
                'codservico' => $fatura->cod_servico,
                'cofins' => number_format($fatura->COFINS, 2, '.', ''),
                'irpj' => number_format($fatura->IRPJ, 2, '.', ''),
                'cont_social' => number_format($fatura->Cont_Social, 2, '.', ''),
                'pis' => number_format($fatura->PIS, 2, '.', ''),
                'disc_servico' => $this->sanitizeString($fatura->descriminacao_servico),
                'valoriss' => number_format((($fatura->ISS / 100) * $fatura->valor_total), 2, '.', ''),
                'basecalculo' => number_format($fatura->valor_total, 2, '.', ''),
                'issretido' => number_format((($fatura->ISS / 100) * $fatura->valor_total), 2, '.', '')
            );

            $add = $this->gera_nfe($dados);

            if ($add) {
                $this->fatura->updateStatusNfe($id_fatura, "Pendente", $id_res);
                echo json_encode(array('mensagem' => 'NF-e gerada com sucesso!', 'status' => 'OK'));
            } else {
                echo json_encode(array('mensagem' => 'Erro ao gerar NF-e, tente novamente mais tarde!', 'status' => 'ERRO'));
            }
        } else {
            echo json_encode(array('mensagem' => $msg, 'status' => 'ERRO'));
        }
    }

    function sanitizeString($valor) {
        $str = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($valor)));
        return $str;
    }

    function baixa_arquivo()
    {
        $bd = false;
        $dados = simplexml_load_file('media/nfe/importacao_nfes.xml');
        if (isset($dados->nota) && !empty($dados->nota)) {
            $num = mt_rand(1, 99999999);
            $nameArq = $num.date('Y_m_d');
            $file = 'media/nfe/importacao_nfes.xml';
            $newfile = 'media/nfe/geradas/'.$nameArq.'.xml';

            if (copy($file, $newfile)) {
                unlink($file);
                $arq_bck = 'media/import_bck.xml';
                $new_arq = 'media/nfe/importacao_nfes.xml';

                if (copy($arq_bck, $new_arq)) {
                    if (!$bd) $bd = true;
                    // GRAVA UPLOAD NA TABELA CAD_RPS
                    $this->fatura->cad_rps($nameArq.'.xml', $this->auth->get_login('admin', 'email'), '1');
                }
            }
        }

        $dados_norio = simplexml_load_file('media/nfe/importacao_nfes_norio.xml');
        if (isset($dados_norio->nota) && !empty($dados_norio->nota)) {
            $num = mt_rand(1, 99999999);
            $nameArq = $num.date('Y_m_d');
            $file = 'media/nfe/importacao_nfes_norio.xml';
            $newfile = 'media/nfe/geradas/'.$nameArq.'.xml';

            if (copy($file, $newfile)) {
                unlink($file);
                $arq_bck = 'media/import_bck.xml';
                $new_arq = 'media/nfe/importacao_nfes_norio.xml';

                if (copy($arq_bck, $new_arq)) {
                    if (!$bd) $bd = true;
                    // GRAVA UPLOAD NA TABELA CAD_RPS
                    $this->fatura->cad_rps($nameArq.'.xml', $this->auth->get_login('admin', 'email'), '2');
                }
            }
        }

        if ($bd) {
            $this->limpa_nfe();
            $this->session->set_flashdata('sucesso', 'Lote Gerado com Sucesso!');
            redirect('faturas');
        } else {
            $this->session->set_flashdata('erro', 'Não há notas pendentes para geração do lote.');
            redirect('faturas');
        }
    }

    function limpa_nfe()
    {
        $arqs = array('media/nfe/importacao_nfes.xml', 'media/nfe/importacao_nfes_norio.xml');

        foreach ($arqs as $emp) {
            $dados = simplexml_load_file($emp);
            if (isset($dados->nota) && !empty($dados->nota)) {
                foreach ($dados as $key => $nota) {
                    unset($dados->nota);
                    if (isset($dados->formatOutput))
                        unset($dados->formatOutput);
                }
            }
            $dados->asXML($emp);
        }
    }

    private function gera_nfe($dadosNfe)
    {
        if ($dadosNfe['empresa'] == '1')
            $importacao = simplexml_load_file('media/nfe/importacao_nfes.xml');
        else
            $importacao = simplexml_load_file('media/nfe/importacao_nfes_norio.xml');

        #nó filho (nota)
        $nota = $importacao->addChild("nota");

        #setando nomes e atributos dos elementos xml (nós)
        $nota->addChild("rps_numero", "{$dadosNfe['id_rps']}");
        $nota->addChild("rps_data", date('Y-m-d'));
        $nota->addChild("tomador_nome", "{$dadosNfe['razao_social']}");
        $nota->addChild("tomador_cnpjcpf", "{$dadosNfe['cnpj']}");
        $nota->addChild("tomador_logradouro", "{$dadosNfe['endereco']}");
        $nota->addChild("tomador_numero", "{$dadosNfe['end_numero']}");
        $nota->addChild("tomador_bairro", "{$dadosNfe['bairro']}");
        $nota->addChild("tomador_cep", "{$dadosNfe['cep']}");
        $nota->addChild("tomador_municipio", "{$dadosNfe['municipio']}");
        $nota->addChild("tomador_uf", "{$dadosNfe['uf']}");
        $nota->addChild("tomador_email", "{$dadosNfe['email']}");
        $nota->addChild("discriminacao", "{$dadosNfe['discriminacao']}");
        $nota->addChild("aliqinss", "0.00");
        $nota->addChild("aliqirrf", "0.00");
        $nota->addChild("deducaoirrf", "0.00");
        $nota->addChild("valortotal", "{$dadosNfe['valortotal']}");
        $nota->addChild("valordeducoes", "{$dadosNfe['valordeducoes']}");
        $nota->addChild("estado", "N");
        $nota->addChild("codservico", $dadosNfe['codservico']);
        $nota->addChild("valoriss", "{$dadosNfe['valoriss']}");
        $nota->addChild("basecalculo", "{$dadosNfe['basecalculo']}");
        $nota->addChild("aliqpercentual", "{$dadosNfe['iss']}");
        $nota->addChild("issretido", "{$dadosNfe['issretido']}");
        $nota->addChild("discriminacao", "{$dadosNfe['disc_servico']}");


        # Para salvar o arquivo, descomente a linha
        if ($dadosNfe['empresa'] == '1')
            $importacao->asXML("media/nfe/importacao_nfes.xml");
        else
            $importacao->asXML("media/nfe/importacao_nfes_norio.xml");

        return true;
/*
        #cabeçalho da página
        header("Content-Type: text/xml");
        # imprime o xml na tela
        print $dom->saveXML(); */
    }

    function leitura()
    {
        $ler = false;
        if (isset($_FILES['arq'])){
            $text = fopen($_FILES['arq']['tmp_name'], 'r');
            while (($line = fgetcsv($text)) !== false)
            {
                foreach ($line as $linha) {
                    $dados = explode(';', $linha);
                    if (count($dados) > 9) {
                        if ($dados[3] != '') {
                            $ler = true;
                            $this->fatura->insertChaveNfe($dados[3], $dados[1], $dados[0]);
                        }
                    }
                }
            }
            fclose($text);
        }

        if ($ler)
            $this->session->set_flashdata('sucesso', 'Arquivo processado com sucesso.');
        else
            $this->session->set_flashdata('erro', 'Não foi encontrado nenhuma fatura enviada');

        redirect('faturas');
    }
}
