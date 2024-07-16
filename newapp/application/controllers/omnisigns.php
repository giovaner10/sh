<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Omnisigns extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('omnisign');
    }

    public function index()
    {
        $dados = [
            'load' => array('ag-grid', 'select2', 'mask'),
            'titulo' => 'Assinatura Eletrônica'
        ];
        
		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('omnisign/index');
        $this->load->view('fix/footer_NS');
    }

    /** View da Caixa de Entrada do usuário */
    public function inbox()
    {
        $dados = [
            'load' => array('ag-grid', 'select2', 'mask'),
            'titulo' => 'Assinatura Eletrônica'
        ];
        
		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('omnisign/inbox');
        $this->load->view('fix/footer_NS');   
    }

    /** Função retorna lista de documentos para assinatura de um usuário shownet */
    public function getMyInbox()
    {
        $dataResponse = [];
        $emailUser = $this->auth->get_login_dados('email');

        // Recupera lista de assinaturas pendentes do usuário
        $dataSignature = $this->omnisign->getDestinatarios([ 'destinatario' => $emailUser, 'status' => '0']);
        if (is_array($dataSignature) && !empty($dataSignature)) {
            $ids = array_map(function($sign) {
                return $sign->idOmnisign;
            }, $dataSignature);

            $dataResponse = $this->omnisign->getDataFilesSignature($ids);
        }

        exit(json_encode($dataResponse));
    }

    public function sendFileAsign()
    {
        $body = $this->input->post(); // recupera dados enviado via POST
        try {
            if (isset($_FILES['arquivoDocumento']) && $_FILES['arquivoDocumento']['size'] > 0) {
                $target_dir = "uploads/omnisign/";
                $fileType = strtolower(pathinfo($_FILES['arquivoDocumento']['name'], PATHINFO_EXTENSION));
                $filename = strtolower(pathinfo($_FILES['arquivoDocumento']['name'], PATHINFO_FILENAME));
                $name = strtotime('now') . preg_replace('/[^A-Za-z0-9]/', '', $filename) . '.' . $fileType;
                
                if (in_array($fileType, ['docx'])) {
                    // Verifica se foi possível realizar o upload do arquivo
                    if (move_uploaded_file($_FILES["arquivoDocumento"]["tmp_name"], $target_dir.$name)) {
                        // Cria código Hash de localização
                        $hashFile = md5(strtotime('now'));
    
                        // Monta registro a ser salvo no banco
                        $data = array(
                            'nome'          => $body['nomeDocumento'], // Nome do Arquivo
                            'categoria'     => $body['categoriaDocumento'], // Categoria do arquivo
                            'arquivo'       => $target_dir.$name, // Caminho do arquivo
                            'status'        => '0', // Aguardando assinaturas
                            'data_cadastro' => date('Y-m-d H:i:s'), // Data do cadastro
                            'data_update'   => date('Y-m-d H:i:s'), // Data da ultima atualização do registro
                            'signature'     => count($body['destinatarios']), // Quantidade de assinaturas esperadas
                            'signed'        => 0, // Quantidade de assinaturas realizadas
                            'hashFile'      => $hashFile, // Hash de identificação do arquivo
                            'id_user'       => $this->auth->get_login_dados('user') // Id do usuário que está realizando o cadastro
                        );
    
                        $idOmnisign = $this->omnisign->addFile($data);
                        if (is_numeric($idOmnisign)) {
                            $data['id'] = $idOmnisign;

                            $dataDestinatario = array();
                            foreach ($body['destinatarios'] as $key => $destinatario) {
                                $dataDestinatario[$key] = array(
                                    'destinatario'  => $destinatario['email'], // Email do destinatario
                                    'nome'          => $destinatario['nome'], // Nome do destinatario
                                    'status'        => '0', // Aguardando assinatura
                                    'data_cadastro' => date('Y-m-d H:i:s'), // Data do cadastro
                                    'data_reenvio'  => NULL, // Data do reenvio (Caso exista)
                                    'data_aceite'   => NULL, // Data do aceite realizado
                                    'data_recusa'   => NULL, // Data da recusa da assinatura
                                    'idOmnisign'    => $idOmnisign, // ID do registro Arquivo
                                    'hash'          => md5(strtotime('now').$key) // Hash de acesso ao aceite
                                );

                                // Estrutura dados para envio de email
                                $htmlEmail = $this->htmlSendDocument(
                                    (object) $data,
                                    (object) $dataDestinatario[$key]
                                );
                                
                                // Envia email para assinatura
                                $this->sendEmail($destinatario['email'], 'OmniSign - Assinatura Eletrônica', $htmlEmail);
                            }
    
                            // Salva informações dos destinatarios
                            $this->omnisign->addDestinatarios($dataDestinatario);
    
                            exit(json_encode(array(
                                'status'    => true,
                                'message'   => 'Registro realizado e enviado para assinatura.',
                                'data'      => $data
                            )));
                        } else {
                            exit(json_encode(array(
                                'status' => false,
                                'message' => 'Não foi possível realizar o upload do arquivo. Verifique as informações e tente novamente mais tarde!'
                            )));
                        }
                    }
                } else {
                    exit(json_encode(array(
                        'status' => false,
                        'message' => 'Apenas é permitido o envio de arquivos PDF ou DOCX. Verique o arquivo e tente novamente!'
                    )));
                }
            } else {
                exit(json_encode(array(
                    'status' => false,
                    'message' => 'Nenhum arquivo enviado. Verifique o campo e tente novamente!'
                )));
            }
        } catch (\Throwable $th) {
            exit(json_encode(json_encode(array(
                'status' => false,
                'message' => 'Erro ao processar solicitação. Contate o administrador do sistema!',
                'data' => $th
            ))));
        }
    }

    /** Função remove um destinatario */
    public function removeDestinatario()
    {
        $idSign = $this->input->post('id');
        
        if (is_numeric($idSign)) {
            $delete = $this->omnisign->deleteSignature([ 'id' => $idSign ]);
        }

        exit(json_encode([
            'status' => isset($delete) ? $delete : false,
            'message' => isset($delete) && $delete ? 'Destinatário removido com sucesso.' : 'Não foi possível remover o destinatário. Tente novamente mais tarde!'
        ]));
    }

    /** Função cancela um Documento/Omnisign */
    public function cancelOmnisign()
    {
        $idFile = $this->input->post('idFile');

        if (is_numeric($idFile)) {
            $update = $this->omnisign->updateFile(
                $idFile,
                [
                    'data_update' => date('Y-m-d H:i:s'),
                    'status'      => '4'
                ]
            );
        }

        exit(json_encode([
            'status' => isset($update) ? $update : false,
            'message' => isset($update) && $update ? 'Omnisign/Documento cancelado com sucesso.' : 'Não foi possível cancelar o documento. Contate o administrador do sistema.'
        ]));
    }

    /** Função adiciona um destinatario */
    public function addDestinatario()
    {
        $idFile = $this->input->post('idFileOpen');
        $email = $this->input->post('email');

        if (is_numeric($idFile) && is_string($email)) {
            $insert = $this->omnisign->addDestinatarios([
                [
                    'destinatario' => $email,
                    'nome'         => explode('@', $email)[0],
                    'data_cadastro'=> date('Y-m-d H:i:s'),
                    'idOmnisign'   => $idFile,
                    'hash'         => md5(strtotime('now'))
                ]
            ]);
        }

        exit(json_encode([
            'status' => isset($insert) ? $insert : false,
            'message' => isset($insert) && $insert ? 'Destinatário cadastrado e solicitação de assinatura enviada com sucesso.' : 'Não foi possível cadastrar o destinatário. Tente novamente mais tarde!'
        ]));
    }

    /** Função retorna lista de destinatarios */
    public function getDestinatarios()
    {
        try {
            $idFile = $this->input->get('id'); // Recupera id do registro solicitaodo
            $data = []; // Cria variavel de retorno padrão

            // Verifica se foi passado ID do registro
            if (is_numeric($idFile)) {
                // BUsca destinatarios do arquivo
                $data = $this->omnisign->getDestinatarios([ 'idOmnisign' => $idFile ]);
            }

            exit(json_encode([ 'status' => true, 'data' => $data ]));
        } catch (\Throwable $th) {
            exit(json_encode([
                'status' => false,
                'message' => 'Não foi possível processar sua solicitação. Contate o admistrador do sistema.',
                'error' => $th
            ]));
        }

    }

    /** Função de reenvio de documento para assinatura */
    public function reenviarDocumento()
    {
        try {
            $idSign = $this->input->post('id');

            if (is_numeric($idSign)) {
                // Recupera dados do assinante
                $dataSign = $this->omnisign->getDestinatarios([ 'id' => $idSign ]);

                // Verifica se assinante existe
                if (is_array($dataSign) && !empty($dataSign)) {
                    $dataFile = $this->omnisign->getDataFiles([ 'id' => $dataSign[0]->idOmnisign ]);

                    if (is_array($dataFile) && !empty($dataFile)) {
                        $htmlEmail = $this->htmlSendDocument($dataFile[0], $dataSign[0]);

                        if ($this->sendEmail($dataSign[0]->destinatario, 'OmniSign - Assinatura Eletrônica', $htmlEmail)) {
                            $this->omnisign->updateSignature($dataSign[0]->id, [ 'status' => '0', 'data_reenvio' => date('Y-m-d H:i:s') ]);
                            if ($dataSign[0]->status == '2') {
                                $this->omnisign->updateFile($dataSign[0]->idOmnisign, [ 'status' => '0' ]);
                            }

                            $message = 'Documento enviado com sucesso.';
                        } else {
                            $message = 'Não foi possível enviar o documento. Tente novamente mais tarde!';
                        }
                    } else {
                        $message = 'Documento não localizado. Contate o administrador do sistema.';
                    }
                } else {
                    $message = 'Destinatário não encontrado. Contate o administrador do sistema.';
                }
            }

            exit(json_encode([
                'status'  => true,
                'message' => isset($message) ? $message : 'Não foi possível processar sua solicitação.'
            ]));
            
        } catch (\Throwable $th) {
            exit(json_encode([
                'status' => false,
                'message' => 'Erro inesperado. Contate o administrador do sistema.',
                'error' => $th
            ]));
        }
    }

    /** Função retorna lista de documentos de um determinado usuário */
    public function getMyDocs()
    {
        $dataArray = []; // Array de retorno da chamada
        $idUser = $this->auth->get_login_dados('user'); // Id Usuário logado
        
        if (is_numeric($idUser)) {
            $dataArray = $this->omnisign->getDataFiles([ 'id_user' => $idUser ]);
        }

        exit(json_encode( $dataArray ));
    }

    /** Função responsável por disparar email's */
    private function sendEmail($destinatario, $assunto, $html)
    {
        try {
            $this->load->library('email');

            $config = array(
                'protocol'      => 'smtp',
                'smtp_host' => $this->config->item('host_smtp'),
                'smtp_port' => $this->config->item('porta_smtp'),
                'smtp_user' => $this->config->item('email_smtp'),
                'smtp_pass' => $this->config->item('senha_smtp'),
                'smtp_crypto'   => 'ssl',
                'mailtype'      => 'html',
                'newline'       => "\r\n"
            );
    
            $this->email->initialize($config);
    
            $this->email->from('no-reply@notificacaogestor.com', 'Omnisign - Assinatura Eletrônica');
            $this->email->to($destinatario);
            $this->email->subject($assunto);
            $this->email->message($html);
            
            return $this->email->send();
        } catch (\Throwable $th) {
            return false;
        }
    }

    /** Função monta o HTML a ser enviado pelo email */
    private function htmlSendDocument($file, $signature)
    {
        return '
        <!DOCTYPE html>
        <html lang="pt">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Documento para Assinatura</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
            .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; }
            .header { background-color: #004684; color: #ffffff; padding: 10px; text-align: center; }
            .content { padding: 20px; text-align: center; }
            .footer { background-color: #f4f4f4; color: #888888; text-align: center; padding: 10px; font-size: 12px; }
            .button { background-color: #22B573; color: #ffffff; padding: 10px 20px; margin: 20px 0; display: inline-block; text-decoration: none; border-radius: 5px; }
        </style>
        </head>
        <body>
        <div class="container">
            <div class="header">
                <h1>OmniSign - Omnilink Tecnologia S.A.</h1>
            </div>
            <div class="content">
                <p>Prezado(a), '. $signature->nome .'.</p>
                <p>Você recebeu um documento para assinatura através do OmniSign, o sistema de assinaturas digitais da Omnilink Tecnologia S.A.</p>
                <p>Por favor, clique no botão abaixo para visualizar o documento e proceder com a assinatura:</p>
                <a href="'. site_url("omnisignsSignature/index/".$file->hashFile."/".$signature->hash) .'" class="button">Visualizar Documento</a>
                <p>Caso tenha dúvidas ou necessite de suporte, por favor, entre em contato conosco.</p>
            </div>
            <div class="footer">
                Este é um e-mail automático gerado pelo OmniSign. Por favor, não responda.
            </div>
        </div>
        </body>
        </html>
        ';
    }

}