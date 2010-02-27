<?php

    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    global $settings;
	$settings = include "settings.php";

    include 'resource.php';
    include 'page.php';

    Resource_RenderXML();

?>
