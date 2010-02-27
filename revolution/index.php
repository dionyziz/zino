<?php

    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    global $settings;
	$settings = include "settings.php";

    include 'models/resource.php';
    include 'models/page.php';

    Resource_RenderXML();

?>
