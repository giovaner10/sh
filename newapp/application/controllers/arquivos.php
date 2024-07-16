<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Arquivos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('cliente');
		$this->load->model('files', 'arquivo');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
	}

    public function get_arquivos($id_cliente)
    {
        $data = [];
        if(isset($id_cliente)) {
            $select = ['nome_arquivos', 'link', 'descricao', 'id'];
            $where = [
                'id_cliente' => $id_cliente,
                'excluido' => 1,
                'tipo' => 'evidencia'
            ];

            $arquivos = $this->arquivo->get($select, $where);

            $data['data'] = $arquivos;
            $data['status'] = true;
            echo json_encode($data);
        } else {
            $data['status'] = false;
            echo json_encode($data);
        }

    }

    public function salvar() 
    {
        // Se um arquivo foi enviado, for maior que 0 e o campos estiverem preenchidos
        if ( isset($_FILES['arquivo']) 
             && $_FILES['arquivo']['size'] != 0 
             && $this->input->post() ){

            // Se o tamanho do arquivo for maior que 100mb não salvar
            if($_FILES['arquivo']['size'] > 12500000) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'O arquivo não pode ser maior que 100mb.'
                ]);
                return;
            }

            $target_dir = "uploads/evidencias";
            $imageFilename = basename( $_FILES["arquivo"]["name"]);

            // Faz o upload do arquivo para seu respectivo caminho
            move_uploaded_file($_FILES['arquivo']['tmp_name'], $target_dir . '/' . $imageFilename);

            $this->arquivo->fileSave([
                'id_cliente' => $this->input->post('id_cliente'),
                'nome_arquivos' => $imageFilename,
                'caminho' => $target_dir,
                'link' => base_url($target_dir . '/' . $imageFilename),
                'tipo' => 'evidencia',
                'descricao' => $this->input->post('descricao')
            ]);

            echo json_encode([
                'status' => true,
                'msg' => 'Arquivo salvo com sucesso.'
            ]);

        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Nenhum dado enviado.'
            ]);
        }
    }

    public function deletar()
    {
        if($this->input->post('arquivo_id')) {
            $id = $this->input->post('arquivo_id');

            $result = $this->arquivo->fileUpdate($id, [
                'excluido' => 0
            ]);

            echo json_encode([
                'status' => true,
                'msg' => 'Arquivo excluído com sucesso.'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Erro ao deletar arquivo.'
            ]);
        }

    }

}