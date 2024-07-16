<!-- Upload_Titulo -->
<link href="./{tpldir}/css/estilos.css" rel="stylesheet" type="text/css" />
<h2>{Upload_Titulo}</h2>

<!-- Upload_SubTitulo -->
<h3><u>Arquivo</u>: {Upload_SubTitulo_Id} - {Upload_SubTitulo_Nome}</h3>

<!-- Upload_ID -->
<input id="ida" name="ida" type="hidden" value="{ida}"/>

<!-- Upload_Acao -->
<script type="text/javascript">
var ida = this.document.getElementById("ida").value;

function acao(com,grid)
{

	switch(com){
		case "Enviar":
	       	$.post("includes/Documentos/incUpload.php",
	       		{
	           		acao: "enviar_agenda",
				Id: ida,
	           		tipo: 1,
	           		id: ""
				},
				function(data){
					$('#conteudo').html(data);
				});
			break;

		case "Baixar":
				if($('.trSelected',grid).length>0){
			    	var items = $('.trSelected',grid);
			    	var id = items[0].cells[0].children[0].innerHTML;
			       	$.post("includes/incAcao.php",
			       		{
							acao: "BAIXAR_ARQUIVO",
							tipo: 2,
			           		id: id
						},
						function(data){
							$('#conteudo').html(data);
						});
				}				
				break;

	}
        
		
}
</script>

<!-- Upload_Formulario_INICIO -->

<script type="text/javascript" src="{tpldir}/js/ui/ui.draggable.js"></script>
<script type="text/javascript" src="{tpldir}/js/ui/ui.resizable.js"></script>
<script type="text/javascript" src="{tpldir}/js/ui/ui.dialog.js"></script>
<script type="text/javascript" src="{tpldir}/js/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript" src="{tpldir}/js/maskedinput/maskedinput-1.3.js"></script>

