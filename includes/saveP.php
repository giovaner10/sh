<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<?php
session_start();
     
$destino = '../templates/default/img/propostas/'; //$_POST['pasta'];
//echo basename(getcwd()).'<br>'; 
if(!$_FILES){
	echo 'Nenhum arquivo enviado!';
}else{
	$file_name = $_FILES['file1']['name'];
	$file_type = $_FILES['file1']['type'];
	$file_size = $_FILES['file1']['size'];
	$file_tmp_name = $_FILES['file1']['tmp_name'];
	$error = $_FILES['file1']['error'];
        $file_name_destino = $_POST['filename'];
}

switch ($error){
	case 0:
		break;
	case 1:
		echo 'O tamanho do arquivo Ã© maior que o definido nas configuraÃ§Ãµes do PHP!';
		break;
	case 2:
		echo 'O tamanho do arquivo Ã© maior do que o permitido!';
		break;
	case 3:
		echo 'O upload nÃ£o foi concluÃ­do!';
		break;
	case 4:
		echo 'O upload nÃ£o foi feito!';
		break;
}

if($error == 0){
	if(!is_uploaded_file($file_tmp_name)){
		echo 'Erro ao processar arquivo!';
	}else{
		if(!move_uploaded_file($file_tmp_name,$destino.$file_name_destino)){
			echo 'Não foi possível salvar o arquivo!';
		}else{
			echo 'Processo concluído com sucesso!<br>';
			echo "Nome do arquivo: $file_name<br>";
			echo "Tipo de arquivo: $file_type<br>";
			echo "Tamanho em byte: $file_size<br>";
                        
                        echo "<script>";
                        if ($file_name_destino=='proposta0.pdf'){
                            echo "parent.frames['p1'].location.reload();";
                        } else {
                            echo "parent.frames['p2'].location.reload();";
                        }
                        echo "</script>";
		}
	}
}

?>
