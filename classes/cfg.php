<?php
	// Dados para acesso ao MySQL
	$hst = "3.15.117.58:3306";
	$dbs = "showtecsystem";
	$usr = "devs_homologacao";
	$pwd = "192uUA_sp_VF";

	// Definicoes do TEMA (Templates)       
	$tema = "default";
	$template_dir = "../../templates/". $tema;
        date_default_timezone_set('America/Recife');
	$js_dir = "js";
        
        $DB_ = mysqli_connect($hst, $usr, $pwd) or trigger_error(mysqli_error(),E_USER_ERROR);

        
        

?>
