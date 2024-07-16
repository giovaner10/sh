<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Movidesk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
    }

    public function getServicosPai()
    {
        $dados = $this->to_get("movidesk/listarServicosPai");

        echo json_encode($dados);
    }

    public function getServicosFilho()
    {
        $id = $this->input->post('idPai');
        $dados = $this->to_get("movidesk/listarServicosByParentId?id=" . $id);

        echo json_encode($dados);
    }

    public function cadastrarTicket()
    {
        $POSTFIELDS = array(
            'email' => $this->input->post('email'),
            'assunto' => $this->input->post('assunto'),
            'categoria' => $this->input->post('categoria'),
            'urgencia' => (int) $this->input->post('urgencia'),
            'servico' => array(
                $this->input->post('servico')
            ),
            'cc' => $this->input->post('cc'),
            'servicoId' => (int) $this->input->post('servicoId'),
            'servicoNivel1' => $this->input->post('servicoNivel1'),  // Adicione um input hidden no formulário com o texto do serviço selecionado
            'prestadora' => (int) $this->input->post('prestadora'),
            'clienteId' => $this->input->post('clienteId'),
            'descricao' => $this->input->post('descricao')
        );

        if ($this->input->post('servicoNivel2') != '') {
            $POSTFIELDS['servico'][] = $this->input->post('servicoNivel2');
        }

        if ($this->input->post('servicoNivel3') != '') {
            $POSTFIELDS['servico'][] = $this->input->post('servicoNivel3');
        }

        $dados = $this->to_post('movidesk/cadastrarTicket', $POSTFIELDS);

        echo json_encode($dados);
    }

    public function enviarAnexo()
    {
        $protocolo = $this->input->post('protocolo');

        // Verifica se o arquivo foi enviado
        if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] != UPLOAD_ERR_OK) {
            echo json_encode(['status' => 400, 'mensagem' => 'Nenhum arquivo enviado ou erro no upload']);
            return;
        }

        // Lê o conteúdo do arquivo
        $fileContent = file_get_contents($_FILES['arquivo']['tmp_name']);
        $fileName = $_FILES['arquivo']['name'];

        // Prepara os dados para a API externa
        $formData = array(
            'protocolo' => $protocolo,
            'arquivo' => curl_file_create($_FILES['arquivo']['tmp_name'], $_FILES['arquivo']['type'], $fileName)
        );
        $dados = $this->to_post_formData('movidesk/anexarDocumentosAbertura', $formData);
        echo json_encode($dados);
    }


    private function to_patch($url, $POSTFIELDS)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        $body = json_encode($POSTFIELDS);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
            CURLOPT_HTTPHEADER => $headers,
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

    private function to_put($url, $POSTFIELDS)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
            CURLOPT_HTTPHEADER => $headers,
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

    private function to_post($url, $POSTFIELDS)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
            CURLOPT_HTTPHEADER => $headers,
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

    private function to_get($url)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }

    private function to_post_formData($url, $POSTFIELDS)
    {
        $CI = &get_instance();

        $request = $CI->config->item('url_api_shownet_rest') . $url;

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Authorization: Bearer ' . $token;
        // Não defina 'Content-Type' como 'application/json', pois estamos enviando 'form-data'

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $POSTFIELDS, // Passando os dados diretamente
            CURLOPT_HTTPHEADER => $headers,
        ));

        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }
}
