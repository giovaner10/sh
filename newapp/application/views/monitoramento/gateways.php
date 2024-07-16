<div class="row-fluid show-grid">
	<div class="span6">
		<div class="well well-small">
			<b>Calamp</b>
		</div>
		<div id="log-calamp" style="height: 300px; overflow: auto" data-controller="<?php echo site_url('monitoramento/ajax_read_log_calamp') ?>">
			<p class="line"></p>
		</div>
	</div>
	<div class="span6"></div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		setInterval(getLine, 500);
		
	});

	var lineAnt = '';

	function getLine(){
		var url = $("#log-calamp").attr("data-controller");
		
		$.ajax({url: url})
		.done(function( line ) {

			if (lineAnt != line){
				$( ".line" ).prepend( "<p>"+line+"</p>" );
				lineAnt = line;
			}
			
		});
	}
</script>