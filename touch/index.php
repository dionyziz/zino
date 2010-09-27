<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0;" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		
        <title>Zino</title>
        <link rel="stylesheet" href="css/ext-touch.css" type="text/css" />
        <link rel="stylesheet" href="css/photos.css" type="text/css" />
        <script type="text/javascript" src="js/ext-touch-debug.js"></script>
		<script type="text/javascript" src="js/ext-touch-extend.js"></script>
		<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="js/date.js"></script>
		<script type="text/javascript" src="js/kamibu.js"></script>
		<script type="text/javascript">
				Now = '<?=date( "Y-m-d H:i:s", $_SERVER[ 'REQUEST_TIME' ] );?>';
				NowDate = stringToDate( Now );<?php
				if( file_exists( 'proxy.php' ) ){
					?>base = 'proxy.php';<?php
				}
				else{
					?>base = 'http://beta.zino.gr/ted/';<?php
				}
				?>
				setInterval( function(){
					NowDate.setSeconds( NowDate.getSeconds() + 5 );
					Now = dateToString( NowDate );
				}, 5 * 1000 );
		</script>
        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body>
		<div class="ph">Φόρτωση...</div>
    </body>
</html>
