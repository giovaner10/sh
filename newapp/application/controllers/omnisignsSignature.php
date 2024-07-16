<?php

class OmnisignsSignature extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('omnisign');
    }

    public function index($tokenArchive = false, $tokenSignature = false)
    {
        // Caso um dos tokens não seja passado
        if (!$tokenArchive || !$tokenSignature) {
            // Carrega view de arquivo não encontrado
            $this->load->view('omnisign/arquivo_nao_encontrado');
        } else {
            // Recupera dados do token Archive
            $dataArchive = $this->omnisign->getDataFiles([ 'hashFile' => $tokenArchive ], [ 'status' => ['0', '1'] ]);

            // Verifica se arquivo foi encontrado
            if (is_array($dataArchive) && !empty($dataArchive)) {
                // Recupera dados do token Signature
                $dataSignature = $this->omnisign->getDestinatarios([ 'hash' => $tokenSignature ]);

                if (is_array($dataSignature) && !empty($dataSignature)) {
                    switch ($dataSignature[0]->status) {
                        case '0':
                            $dataView = [ 'file' => $dataArchive[0], 'signature' => $dataSignature[0] ];
                            $this->load->view('omnisign/assinatura', $dataView);
                            break;
                        case '1':
                            $this->load->view('omnisign/arquivo_assinado');
                            break;
                        default:
                            $this->load->view('omnisign/arquivo_rejeitado');
                            break;
                    }
                } else {
                    // Carrega view de usuário sem permissão
                    $this->load->view('omnisign/sem_permissao');
                }
            } else {
                // Carrega view de Arquivo não encontrado
                $this->load->view('omnisign/arquivo_nao_encontrado');
            }
        }
    }

    /** Função rejeita um documento inbox */
    public function rejeitaDocumentoInbox()
    {
        $status = false; // Variavel de controle do retorno
        $idFile = $this->input->post('idFile');

        if (is_numeric($idFile)) {
            // Recupera usuário logado
            $usuario = $this->auth->get_login_dados('email');

            // Recupera CPF do usuário
            $cpf_aceite = $this->auth->get_login_dados('cpf');

            // Recupera Signature
            $dataSignature = $this->omnisign->getDestinatarios([ 'idOmnisign' => $idFile, 'destinatario' => $usuario ]);

            if (is_array($dataSignature) && !empty($dataSignature)) {
                // Atualiza registro (Destinatario)
                $this->omnisign->updateSignature($dataSignature[0]->id, [
                    'status' => '2',
                    'data_recusa' => date('Y-m-d H:i:s')
                ]);

                // Atualiza registro (Arquivo)
                $this->omnisign->updateFile($dataSignature[0]->idOmnisign, [
                    'status' => '3',
                    'data_update' => date('Y-m-d H:i:s')
                ]);

                // Atualiza status e mensagem de retorno
                $status = TRUE;
                $message = 'Documento rejeitado com sucesso!';
            } else {
                $message = 'Não foi localizado assinatura pendente para o documento. Caso persista o resultado, entre em contato com o administrador do sistema.';
            }
        } else {
            $message = 'Parâmetro não enviado. Contate o administrador do sistema.';
        }

        exit(json_encode([
            'status' => $status,
            'message' => isset($message) ? $message : 'Não foi possível realizar o aceite do documento. Tente novamente mais tarde!'
        ]));
    }

    /** Função aceita um documento inbox */
    public function aceitarDocumentoInbox()
    {
        $status = false; // Variavel de controle do retorno
        $idFile = $this->input->post('idFile');

        if (is_numeric($idFile)) {
            // Recupera usuário logado
            $usuario = $this->auth->get_login_dados('email');

            // Recupera CPF do usuário
            $cpf_aceite = $this->auth->get_login_dados('cpf');

            // Recupera Signature
            $dataSignature = $this->omnisign->getDestinatarios([ 'idOmnisign' => $idFile, 'destinatario' => $usuario ]);

            // Recupera File
            $dataArchive = $this->omnisign->getDataFiles([ 'id' => $idFile ], [ 'status' => ['0', '1'] ]);

            if (is_array($dataSignature) && !empty($dataSignature)) {
                if ($this->editArchive($dataArchive[0]->arquivo, $dataSignature[0])) {
                    // Atualiza registro (Destinatario)
                    $this->omnisign->updateSignature($dataSignature[0]->id, [
                        'status' => '1',
                        'data_aceite' => date('Y-m-d H:i:s'),
                        'cpf_aceite' => $cpf_aceite
                    ]);

                    // Atualiza registro (Arquivo)
                    $this->omnisign->updateFile($dataArchive[0]->id, [
                        'status' => intval($dataArchive[0]->signed) + 1 == intval($dataArchive[0]->signature) ? '2' : '1',
                        'data_update' => date('Y-m-d H:i:s'),
                        'signed' => intval($dataArchive[0]->signed) + 1
                    ]);

                    // Atualiza status e mensagem de retorno
                    $status = TRUE;
                    $message = 'Arquivo assinado com sucesso.';
                } else {
                    $message = 'Falha ao realizar assinatura. Entre em contato com a Omnilink.';
                }
            } else {
                $message = 'Não foi localizado assinatura pendente para o documento. Caso persista o resultado, entre em contato com o administrador do sistema.';
            }
        } else {
            $message = 'Parâmetro não enviado. Contate o administrador do sistema.';
        }

        exit(json_encode([
            'status' => $status,
            'message' => isset($message) ? $message : 'Não foi possível realizar o aceite do documento. Tente novamente mais tarde!'
        ]));
    }

    public function receptSignature()
    {
        try {
            $idFile = $this->input->post('idFile');
            $idSign = $this->input->post('idSign');
            $cpf = $this->input->post('cpf');
            $status = false; // Váriavel de controle de retorno

            // Valida se todos os campos estão setados corretamente
            if (!$idFile || !is_numeric($idFile) || !$idSign || !is_numeric($idSign)) {
                exit(json_encode([ 'status' => $status, 'message' => 'Falha no carregamento da assinatura. Entre em contato com a Omnilink.' ]));
            }

            // Recupera dados da assinatura
            $dataSignature = $this->omnisign->getDestinatarios([ 'id' => $idSign, 'status' => '0' ]);

            if (is_array($dataSignature) && !empty($dataSignature)) {
                // Recupera dados do Arquivo
                $dataArchive = $this->omnisign->getDataFiles([ 'id' => $dataSignature[0]->idOmnisign ]);

                if (is_array($dataArchive) && !empty($dataArchive)) {
                    // Realiza assinatura do documento
                    if ($this->editArchive($dataArchive[0]->arquivo, $dataSignature[0])) {
                        // Atualiza registro (Destinatario)
                        $this->omnisign->updateSignature($dataSignature[0]->id, [
                            'status' => '1',
                            'data_aceite' => date('Y-m-d H:i:s'),
                            'cpf_aceite' => $cpf
                        ]);

                        // Atualiza registro (Arquivo)
                        $this->omnisign->updateFile($dataArchive[0]->id, [
                            'status' => intval($dataArchive[0]->signed) + 1 == intval($dataArchive[0]->signature) ? '2' : '1',
                            'data_update' => date('Y-m-d H:i:s'),
                            'signed' => intval($dataArchive[0]->signed) + 1
                        ]);

                        // Atualiza status e mensagem de retorno
                        $status = TRUE;
                        $message = 'Arquivo assinado com sucesso.';
                    } else {
                        $message = 'Falha ao realizar assinatura. Entre em contato com a Omnilink.';
                    }
                } else {
                    $message = 'Arquivo não localizado. Caso o problema persista entre em contato com a Omnilink.';
                }
            } else {
                $message = 'Não foi possível processar a sua solicitação. Caso o problema persista entre em contato com a Omnilink.';
            }

            exit(json_encode([ 'status' => $status, 'message' => isset($message) ? $message : 'Falha no processamento da solicitação. Tente novamente mais tarde!']));
        } catch (\Throwable $th) {
            exit(json_encode([ 'status' => false, 'message' => 'Falha no processamento. Entre em contato com a Omnilink.', 'erro' => $th]));
        }
    }

    private function editArchive($arquivo, $signature)
    {
        try {
            require APPPATH . 'libraries/vendor/autoload.php';

            $phpWord = \PhpOffice\PhpWord\IOFactory::load($arquivo);

            // Configura style do texto adicionado
            $fontStyle = array(
                'name' => 'Courier New',
                'size' => 6,
                'bold' => true,
                'color' => '808080',
            );

            // Acessar a seção e adicionar texto
            $secao = $phpWord->getSections()[0]; // Acessa a primeira seção
            $secao->addText('Nome: '. $signature->nome .' | Data assinatura: '. date("d/m/Y H:i:s") .' | Chave de segurança: '. $signature->hash, $fontStyle);

            // Salvar as alterações em um novo arquivo
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord);
            $objWriter->save($arquivo);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}