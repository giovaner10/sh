<?php if ( ! defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Upload_Helper extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
    }

    /**
     * Undocumented function
     *
     * @param [string] $pasta
     * @param [string] $inputNome
     * @param [string] $extencao
     * @return void
     */
    function upload($pasta, $inputNome, $extencao = null)
    {
        if ($_FILES["$inputNome"]['error'])
            throw new Exception(lang('falha_envio_arquivo'));

        $extencaoaux = $extencao
            ? $extencao
            : 'pptx|ppt|pdf|doc|docx|xlsx|xls|XLSL|XLSB|txt|png|jpg';
        
        if (!is_dir($pasta))
            mkdir($pasta, 0777, true);

        $config['upload_path'] = $pasta;
        $config['allowed_types'] = $extencaoaux;
        $config['max_size'] = '0';
        $config['max_width'] = '0';
        $config['max_height'] = '0';
        $config['encrypt_name'] = 'true';

        $this->upload->initialize($config);
        $this->load->library('upload', $config);

		# Valida se o arquivo foi salvo no servidor
        if (!$this->upload->do_upload($inputNome))
            throw new Exception(lang('falha_envio_arquivo'));

        return $this->upload->data();
    }

    /**
     * Undocumented function
     *
     * @param [string] $extensoesPermitidas = pdf|doc|docx (ex)
     * @param [string] $inputNome
     * @return void
     */
    function validaExtensao($extensoesPermitidas, $inputNome)
    {
        # Valida campo
        if (!$_FILES[$inputNome]['name'])
            throw new Exception(lang('o_arquivo_e_obrigatorio'));

        $arquivoExtensao = end(explode('.', $_FILES[$inputNome]['name']));

        # Valida extensao do arquivo
        if (! (in_array($arquivoExtensao, explode('|', $extensoesPermitidas))) )
            throw new Exception(lang('arquivo_extensao_invalida'));
    }
}