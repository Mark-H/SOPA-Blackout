<?php
$path = $modx->getOption('sopablackout.core_path',null,$modx->getOption('core_path').'components/sopablackout/');
return include ($path.'elements/plugins/sopablackout.php');
?>
