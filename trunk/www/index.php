<?php
include '../config.php';

$config = new shareMvcConfiguration( $ini );

$dispatcher = new ezcMvcConfigurableDispatcher( $config );
$dispatcher->run();
?>
