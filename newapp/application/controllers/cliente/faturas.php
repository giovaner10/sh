<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faturas extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('cliente');
		$this->load->model('fatura');
	}

	public function index(){

		$dados['msg'] = $this->session->flashdata('msg');
		$dados['erros'] = $this->session->flashdata('erro');

		$dados['titulo'] = 'Clientes';
		$this->load->view('fix/header', $dados);
		$this->load->view('clientes/lista_cliente');
		$this->load->view('fix/footer');
	}

	public function view_email($id_fatura){

		$id_fatura = base64_decode($id_fatura);
		$hoje = date('Y-m-d');
		$dados = array();
		if (is_numeric($id_fatura)){
			$dados['faturas'] = $this->fatura->listar("cad_faturas.numero = {$id_fatura}",
			0, 1, 'data_vencimento', 'ASC');
				
			if (count($dados['faturas']) > 0){

				if($dados['faturas'][0]->status_fatura == 2 || $dados['faturas'][0]->status_fatura == 0){
					$simples="";
					if($this->input->get('simples')){
						$simples="&simples=1";
					}
					if(!$simples && $dados['faturas'][0]->data_vencimento < $hoje && $dados['faturas'][0]->dataatualizado_fatura < $hoje){
							
						if ($this->input->post('acao')){
							$data = data_for_unix($this->input->post('data_pag'));

							if(is_date($data)){
									
								if($data >= $hoje && diff_entre_datas($hoje, $data) <= 15){

									try{
										$this->fatura->atualizar_vencimento($dados['faturas'][0]->Id, $data);
										$paypal = "";
										if($this->input->get('paypal')){
											$paypal = "?paypal=1";
											$eua="";
											if($this->input->get('eua')){
												$eua="&eua=1";
											}
											
											redirect(base_url('index.php/cliente/faturas/view_email/'.base64_encode($id_fatura)).$paypal.$eua.$simples);
										}
										else{
											$id_fatura = $dados['faturas'][0]->Id;
											if($id_fatura) {
												$fatura['fatura'] = (array)$this->fatura->get(array('cad_faturas.Id' => $id_fatura),true);
												$fatura['itens']= (array)$this->fatura->get_items(array('id_fatura'=>$fatura['fatura']['Id']));
												foreach($fatura['itens'] as $key=>$item){
													unset($item->id_fatura);
													unset($item->id_item);
													$fatura['itens'][$key] = (array)$item;
												}
												unset($fatura['fatura']['Id']);
												unset($fatura['fatura']['numero']);
												try {
													$hoje = date('Y-m-d');
													$d_atualiza = array('datacancel_fatura' => $hoje, 'status' => 3, 'instrucoes1' => "Atualização de fatura");
													if($this->fatura->atualizar_fatura($id_fatura, $d_atualiza)) {
														$nova_fatura = $this->fatura->gravar_fatura($fatura);
														$this->fatura->inserir_fatura_log(array('fatura_antiga'=>$id_fatura,'fatura_atualizada'=>$nova_fatura,'usuario'=>"Cliente",'data_atualizacao'=>date('Y-m-d H:m:s'),'descricao'=>"O cliente atualizou a fatura."));
														$total_fat = $this->fatura->total_fatura($nova_fatura);
														$this->fatura->atualizar_fatura($nova_fatura, array('valor_total' => $total_fat));
														echo "Fatura Atualizada";
														redirect(base_url('index.php/cliente/faturas/view_email/'.base64_encode($nova_fatura)).$paypal.$simples);
													} else {
														echo 'Não foi possível atualizar a fatura. Tente novamente.';
													}
												} catch (Exception $e) {
													echo $e->getMessage();
												}
											}
										}
									}catch(Exception $e){
										$dados['msg'] = $e->getMessage();
									}

								}else{
									$dados['msg'] = 'Você deve escolher uma data até '.date('d/m/Y', strtotime("{$hoje} +15 days")).'.';
								}
									
							}else{
								$dados['msg'] = 'Por favor insira uma data válida. Ex.: '.date('d/m/Y', strtotime("{$hoje} +5 days")).'.';
							}
						}
							
						$this->load->view('faturas/atualiza_data_imprimir', $dados);
							
					}else{
						$this->load->view('faturas/imprimir_fatura', $dados);
					}

				}elseif($dados['faturas'][0]->status_fatura == 1){
					
					$dados['erro'] = 'Esta fatura já está paga.';
					$this->load->view('faturas/atualiza_data_imprimir', $dados);
					
				}else{
					$dados['erro'] = 'Esta fatura foi cancelada! Entre em contato conosco para mais informações.';
					$this->load->view('faturas/atualiza_data_imprimir', $dados);
				}

			}

		}

	}

}