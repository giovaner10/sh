<?php

function uploadFTP($arquivoFile, $pathSave, $id_referencia, $tipo, $id_cli, $return='nome'){
    // Configura o tempo limite para ilimitado
    set_time_limit(0);

    /*-----------------------------------------------------------------------------*
     * Parte 1: Configurações do Envio de arquivos via FTP com PHP
    /*----------------------------------------------------------------------------*/

// IP do Servidor FTP
    $servidor_ftp = 'ftp-arquivos.showtecnologia.com';

// Usuário e senha para o servidor FTP
    $usuario_ftp = 'show';
    $senha_ftp   = 'show2592';

// Extensões de arquivos permitidas
    $extensoes_autorizadas = array('.pdf', '.jpeg', '.jpg', '.png', '.xml');

// Caminho da pasta FTP
    $caminho = $pathSave;

    /*
    Se quiser limitar o tamanho dos arquivo, basta colocar o tamanho máximo
    em bytes. Zero é ilimitado
    */
    $limitar_tamanho = 0;

    /*
    Qualquer valor diferente de 0 (zero) ou false, permite que o arquivo seja
    sobrescrito
    */
    $sobrescrever = true;

    /*-----------------------------------------------------------------------------*
     * Parte 2: Configurações do arquivo
    /*----------------------------------------------------------------------------*/

// Verifica se o arquivo não foi enviado. Se não; termina o script.
    if ( ! isset( $arquivoFile ) ) {
        exit('Nenhum arquivo enviado!');
    }

// Aqui o arquivo foi enviado e vamos configurar suas variáveis
    $arquivo = $arquivoFile;

    $rand = rand (1,999999999999);
// Nome do arquivo enviado
    $nome_arquivo = $rand.$arquivo['name'];

// Tamanho do arquivo enviado
    $tamanho_arquivo = $arquivo['size'];

// Nome do arquivo temporário
    $arquivo_temp = $arquivo['tmp_name'];

// Extensão do arquivo enviado
    $extensao_arquivo = strrchr( $nome_arquivo, '.' );

// O destino para qual o arquivo será enviado
    $destino = $caminho . $nome_arquivo;

    /*-----------------------------------------------------------------------------*
     *  Parte 3: Verificações do arquivo enviado
    /*----------------------------------------------------------------------------*/

    if ( $limitar_tamanho && $limitar_tamanho < $tamanho_arquivo ) {
        exit('Arquivo muito grande.');
    }

//        if ( ! empty( $extensoes_autorizadas ) && ! in_array( $extensao_arquivo, $extensoes_autorizadas ) ) {
//            exit('Tipo de arquivo não permitido.');
//        }

    /*-----------------------------------------------------------------------------*
     * Parte 4: Conexão FTP
    /*----------------------------------------------------------------------------*/

// Realiza a conexão
    $conexao_ftp = ftp_connect( $servidor_ftp );

// Tenta fazer login
    $login_ftp = @ftp_login( $conexao_ftp, $usuario_ftp, $senha_ftp );

    // Ativa modo passivo
    ftp_pasv($conexao_ftp, true);

    $file_exists = ftp_size( $conexao_ftp, $destino );
    if ( $file_exists != -1 ) {
        exit('Arquivo já existe.');
    }

// Se não conseguir fazer login, termina aqui
    if ( ! $login_ftp ) {
        exit('Usuário ou senha FTP incorretos.');
    }

// Envia o arquivo
    if ( @ftp_put( $conexao_ftp, $destino, $arquivo_temp, FTP_BINARY ) ) {
        // Cria ARRAY de Dados para inserção
        $dados = array(
            'id_cliente' => $id_cli,
            'nome_arquivos' => $nome_arquivo,
            'caminho' => $caminho,
            'link' => 'ftp://show:show2592@ftp-arquivos.showtecnologia.com/'.$caminho.$nome_arquivo,
            'id_referencia' => $id_referencia,
            'tipo' => $tipo
        );

        $CI =& get_instance();
        $CI->load->model('files');
        $insert = $CI->files->fileSave($dados);
        

        if ($insert) {
            // Fecha conexão ftp
            ftp_close($conexao_ftp);
            if ($return == 'id')
                return $insert;
            return $nome_arquivo;
        } else {
            exit('O Arquivo não pode ser transferido.');
        }

    } else {
        return FALSE;
    }
}
