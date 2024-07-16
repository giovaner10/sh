<?php 
/*********************************************************** 
* 
*    class P_Tpl 
* 
*     Management of a template 
* 
*    @author    Sergio A. Pohlmann 
*     @mailto    sergiop@mail.com 
*    @date    july, 06 of 2002 
* 
***********************************************************/ 
 
class Template { 
 
	
    /******************************************************* 
     *     Constructor of the class 
     *******************************************************/ 
    function Template ( $file="" ){ 
	    $this->file=$file;    // File of template 
	 
	    // Check if file variable is defined and file exist 
	    if (empty( $this->file ) ) { 
	        die("Not defined file to template"); 
	    } 
	    else { 
	        if ( !file_exists( $this->file ) ) { 
	            die("File $this->file do not exist!"); 
	        } 
	    } 
    } 
 
 
    /******************************************************* 
     *     Set     To set any variable to the template 
     *******************************************************/ 
    function Set ( $var, $value ) { 
    	$this->$var=$value; 
    } 
 
	/******************************************************* 
     *     Show     Make a prints in the HTML code 
     *******************************************************/ 
    function Show ( $ident = "", $show = "" ) { 
    		
    	$this->show = $show;
	    // Make an array 
	    $arr = file ( $this->file ); 
	 
	    // If $ident has not defined  
	    if ( $ident=="" ) { 
	        $c = 0;  // a single counter 
	        $len = count ($arr);  // the lenght of the array 
	        while( $c < $len ) { 
//	            $temp = str_replace ("{", "$"."this->", $arr[$c] );  
//	            $temp = str_replace ( "}", "", $temp ); 
//	            $temp = addslashes($temp); 
//	            eval("\$x = \"$temp\";");
//	            echo $x; 

				$this->troca($arr[$c]);
	            $c++;  
	        } 
	     } 
 
	    // if exist an identificator ($ident) 
	    else { 
	        $c = 0;  // a single counter 
	        $len = count ($arr);  // the lenght of the array 
	        $tag = "<!-- " . $ident . " -->";  // a tag to search
	        while( $c < $len ) { 
	            // when encounter the tag 
	            if ( trim($arr[$c]) == $tag ) { 
	                $c++; 
	                
	                //Inicia a variavel que armazena o conteúdo a ser impresso
	                $this->conteudo = "";
	                
	                // while not found new tag
	                while( ( $c < $len ) && (substr( $arr[$c], 0 , 4) != "<!--" ) ) {
						$this->troca($arr[$c]);
	                    $c++;  
	                } 
	                // forces end of check
	                $c = $len; 
	           } 
	          $c++; 
	       } 
	   }
	} // function show
	
	function troca($conteudo){
		$chaveIni = strpos($conteudo,"{");
		$chaveFim = strpos($conteudo,"}");
                
		if ($chaveIni != "" && $chaveFim != ""){
			$chaveIni++;
			$ts = $chaveFim - $chaveIni;
			$entreChaves = substr($conteudo,$chaveIni,$ts);
			$verTroca = "$"."this->".$entreChaves;
			$verTroca = addcslashes($verTroca,"\""); //'$this->Unidade_Fone'
			eval("\$troca= \"$verTroca\";"); // $troca = 
			
			if (!is_null($troca)){
				$temp = str_replace ("{", "$"."this->", $conteudo );  
				$temp = str_replace ("}", "", $temp ); 
				$temp = addcslashes($temp,"\""); 
				eval("\$x= \"$temp\";"); 
				$this->conteudo .= $x . "\n"; //Acumula o conteúdo a ser armazenado
				if ($this->show == "") echo $x ;
			}
		} else { 
			$this->conteudo .= $conteudo . "\n"; //Acumula o conteúdo a ser armazenado
			if ($this->show == "") echo $conteudo;
		}
		$chaveIni = $chaveFim = "";
	} // function troca
}
?>