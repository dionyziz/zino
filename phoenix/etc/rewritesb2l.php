#!/usr/bin/env php

<?php
$ourfile = file_get_contents( $argv[ 1 ] );
$ourfile = str_replace( "RewriteCond %{SERVER_NAME} ^beta\.zino\.gr", "RewriteCond %{SERVER_NAME} ^www\.zino\.gr", $ourfile );
$ourfile = str_replace( "RewriteRule ^", "RewriteRule ^/", $ourfile );
$ourfile = str_replace( "RewriteCond %{SERVER_NAME} ^api\.beta\.zino\.gr", "RewriteCond %{SERVER_NAME} ^api\.zino\.gr", $ourfile );
$ourfile = str_replace( "RewriteCond %{SERVER_NAME} !^beta\.zino\.gr", "RewriteCond %{SERVER_NAME} !^www\.zino\.gr", $ourfile );
$ourfile = str_replace( "RewriteCond %{SERVER_NAME} ^(www)?([^.]+)\.beta\.zino\.gr", "RewriteCond %{SERVER_NAME} ^(www)?([^.]+)\.zino\.gr", $ourfile );
$ourfile = str_replace( "RewriteCond %{SERVER_NAME} ^([^.]+)\.beta\.zino\.gr", "RewriteCond %{SERVER_NAME} ^([^.]+)\.zino\.gr", $ourfile );

echo $ourfile;
?>
