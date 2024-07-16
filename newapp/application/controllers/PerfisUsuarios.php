<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class PerfisUsuarios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');

        $this->load->model('usuario');
        $this->load->model('arquivo');

        # Helpers
		$this->load->helper('upload');
		$this->uploadHelper = new Upload_Helper();

		# Vars
		$this->extensoesPermitidas = "jpg|pjpeg|jpeg|png|gif|bmp";
    }

    public function modalPerfil()
    {
        $dados['modalTitulo'] = lang('perfil');
        $dados['formatosPermitidos'] = str_replace('|', ' | ', $this->extensoesPermitidas);

        $usuario = $this->usuario->getUser(
            $this->auth->get_login_dados('user') # id do usuario
        )[0];

        $arquivo = $this->arquivo->getArquivos(['id' => $usuario->id_arquivos]);

        # Trata a foto de perfil do usuário
        if (!$arquivo)
            $usuario->fotoPerfilUrl = base_url('media/img/usuario-anonimo.png');
        else
            if (file_exists("./uploads/perfil_usuarios/{$arquivo[0]->file}"))
                $usuario->fotoPerfilUrl = base_url("uploads/perfil_usuarios/{$arquivo[0]->file}");
            else
                // Se o arquivo não for encontrado, defina a URL da imagem padrão
                $usuario->fotoPerfilUrl = base_url('media/img/usuario-anonimo.png');

        $dados['usuario'] = $usuario;
        $this->load->view("perfil_usuario/index", $dados);
    }

    public function perfilUsuarioEdicao()
    {
        try
        {
            $dados = $this->input->post();

            if (!$dados)
                throw new Exception(lang('a_imagem_deve_ter_no_maximo_2mb'));

            $usuarioId = $this->auth->get_login_dados('user');
            $usuario = $this->usuario->getUser($usuarioId)[0];

            # Valida se a senha atual inserida é válida
            if ($dados['senhaAtual'] != $usuario->senha)
                throw new Exception(lang('senha_atual_invalida'));

            # Valida se a senha inserida e a senha de confirmação inserida são iguais
            if ($dados['senhaNova'] && $dados['senhaNova'] != $dados['senhaNovaConfirmacao'])
                throw new Exception(lang('a_senha_e_a_confirmacao_da_senha_sao_diferentes'));

            # Valida se a senha nova inserida é diferente da senha atual
            if ($dados['senhaNova'] == $dados['senhaAtual'])
                throw new Exception(lang('senha_nova_e_igual_a_senha_atual'));

            # Get arquivo antigo (opcional)
            $arquivoAntigo = $this->arquivo->getArquivos(['id' => $dados['arquivoId']]);

            if ($arquivoAntigo)
            {
                $arquivoAntigo = $arquivoAntigo[0];
                $arquivoId = $arquivoAntigo->id;
            }

            # Se tiver imagem
            if ($_FILES["imagem"]["name"] != '')
            {

                # Valida extensao da imagem
                $this->uploadHelper->validaExtensao($this->extensoesPermitidas, 'imagem');

                # Valida tamanho da imagem
                if ($_FILES['imagem']["size"] == 0 || $_FILES['imagem']["size"] > 2097152) // 2MB
                    throw new Exception(lang('a_imagem_deve_ter_no_maximo_2mb'));

                $ext = explode('/', $_FILES['imagem']['type'])[1];

                if (!in_array($ext, explode('|', $this->extensoesPermitidas)))
                    throw new Exception(lang('arquivo_extensao_invalida'));

                mt_srand();
                $filename = md5(uniqid(mt_rand())).'.'.$ext;

                $destinationPath = "./uploads/perfil_usuarios/".$filename;

                if(!is_uploaded_file($_FILES['imagem']['tmp_name']))
                    throw new Exception(lang('falha_envio_arquivo'));

                if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $destinationPath))
                    throw new Exception(lang('falha_envio_arquivo'));
                    

                $imagemNome = $filename;
                $imagem = realpath($destinationPath);

                # Se o usuário já tiver foto de perfil
                if ($arquivoAntigo)
                {
                    # Deleta imagem antiga (Servidor)
                    if (file_exists($arquivoAntigo->path))
                        unlink($arquivoAntigo->path);

                    # Deleta imagem antiga (BD)
                    $this->arquivo->excluir($arquivoAntigo->id);
                }

                # Add imagem nova
                $arquivoId = $this->arquivo->adicionar([
                    'file' => $imagemNome,
                    'descricao' => "Foto de perfil - {$dados['nome']}",
                    'pasta' => "perfil_usuarios",
                    'ndoc' => '',
                    'path' => $imagem
                ]);
            }

            $this->usuario->updateUser(
                $usuarioId,
                [
                    'nome' => $dados['nome'],
                    'senha' => $dados['senhaNova'] ? $dados['senhaNova'] : $usuario->senha,
                    'id_arquivos' => isset($arquivoId) ? $arquivoId : null
                ]
            );

            echo json_encode([
                'status' => 1,
                'mensagem' => lang('mensagem_sucesso')
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                'status' => 0,
                'mensagem' => $e->getMessage() ? : lang('mensagem_erro')
            ]);
        }
    }
}