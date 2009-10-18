#!/usr/bin/env php

<?php
$ourfile = file_get_contents( $argv[ 1 ] );
$ourfile = str_replace( "RewriteCond %{SERVER_NAME} ^beta\.zino\.gr", "RewriteCond %{SERVER_NAME} ^www\.zino\.gr", $ourfile );
$ourfile = str_replace( "RewriteRule ^", "RewriteRule ^/", $ourfile );

$ourfile = preg_replace( "/RewriteCond %\\{SERVER_NAME\\} (\\!)?\\^([a-z]+)(\\\\\\.)?beta\\\\\\.zino\\\\\\.gr/",
                         "RewriteCond %{SERVER_NAME} \\1^\\2\\3zino\\.gr", $ourfile );

$ourfile = str_replace( "RewriteCond %{SERVER_NAME} ^(www)?([^.]+)\.beta\.zino\.gr", "RewriteCond %{SERVER_NAME} ^(www)?([^.]+)\.zino\.gr", $ourfile );
$ourfile = str_replace( "RewriteCond %{SERVER_NAME} ^([^.]+)\.beta\.zino\.gr", "RewriteCond %{SERVER_NAME} ^([^.]+)\.zino\.gr", $ourfile );

echo "# DONT EDIT THIS FILE. ThIS FILE IS GETTING REPLACED ON EVERY SYNC\n# EDIT THE .htaccess FILE INSTEAD\n";
echo "# GENERATED FROM " . $argv[ 1 ] . "\n";
echo $ourfile;
?>
