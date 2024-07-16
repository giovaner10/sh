<?php
$output = shell_exec('sh enviaSSH.sh ip address print');
echo "<pre>$output</pre>";
?>
