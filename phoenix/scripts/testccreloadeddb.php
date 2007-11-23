<?php
return;

set_include_path( '../:./' );

global $water;

require 'header.php';

$water->Enable();

?><html><head><title></title></head><body><?php

$water->SetSetting( 'strict' , false );

$testdb = New Database( 'ccreloaded' );
$testdb->Connect( 'localhost' );
$testdb->Authenticate( 'ccreloaded' , 'test11test' );
$testdb->SetCharset( 'DEFAULT' );
$res = $testdb->Query('SHOW TABLES');

if ($res === false) {
    ?>Query failed.<?php
    $water->GenerateHTML();
    ?></body></html><?php
    die();
}

echo $res->NumRows() . ' tables in dbase.<br />';

while ( $row = $res->FetchArray() ) {
    print_r( $row );
    ?><br /><?php
}

$water->GenerateHTML();

?></body></html>
