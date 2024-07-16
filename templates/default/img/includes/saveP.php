<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<?php
session_start();

$log_ip = $_SERVER['REMOTE_ADDR'];
$log_usuario = $_SESSION["usuario_login"];
$log_data = date('Y-m-d H:i:s');
                
$destino = '- ./../../templates/default/img/propostas/'; //$_POST['pasta'];

if(!$_FILES){
	echo '<p>Nenhum arquivo enviado!</p>';
}else{
	$file_name = $_POST['filename'];
    	//$file_name = $_FILES['file']['name'];
        $file_name_ = $_FILES['file1']['name'];
	$file_type = $_FILES['file1']['type'];
	$file_size = $_FILES['file1']['size'];
	$file_tmp_name = $_FILES['file1']['tmp_name'];
	$error = $_FILES['file1']['error'];
        $pasta = $_POST['pasta'];
}

switch ($error){
	case 0:
		break;
	case 1:
		echo 'O tamanho do arquivo é maior que o definido nas configurações do PHP!';
		break;
	case 2:
		echo 'O tamanho do arquivo é maior do que o permitido!';
		break;
	case 3:
		echo 'O upload não foi concluído!';
		break;
	case 4:
		echo 'O upload não foi feito!';
		break;
}

if($error == 0){
	if(!is_uploaded_file($file_tmp_name)){
		echo 'Erro ao processar arquivo!';
	}else{
		if(!move_uploaded_file($file_tmp_name,$destino.$file_name)){
			echo 'Não foi possível salvar o arquivo!';
		}else{
/*
			echo 'Processo concluído com sucesso!<br>';
                        echo "Nome da pasta  : $pasta<br>";
			echo "Nome do arquivo: ".  utf8_encode($file_name)."<br>";
			echo "Tipo de arquivo: $file_type<br>";
			echo "Tamanho em byte: $file_size<br>";
 * 
 */
		}
	}
}
?>
<script>
<?php if ($_POST['filename']=='proposta1.pdf'){ ?>
parent.frames['p2'].location.reload();
<?php } else { ?>
parent.frames['p1'].location.reload();
<?php }?>
</script>