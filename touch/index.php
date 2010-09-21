<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0;" />
        <title>Zino</title>
        <link rel="stylesheet" href="sencha/resources/css/ext-touch.css" type="text/css" />
        <link rel="stylesheet" href="css/photos.css" type="text/css" />
        <script type="text/javascript" src="js/ext-touch-debug.js"></script>
		<script type="text/javascript" src="js/ext-touch-extend.js"></script>
		<script type="text/javascript" src="js/date.js"></script>
		<script type="text/javascript" src="js/kamibu.js"></script>
		<script type="text/javascript">
				var Now = '<?=date( "Y-m-d H:i:s", $_SERVER[ 'REQUEST_TIME' ] );?>';
				var NowDate = stringToDate( Now );
				setInterval( function(){
					NowDate.setSeconds( NowDate.getSeconds() + 5 );
					Now = dateToString( NowDate );
				}, 5 * 1000 );
				window.Now = Now;
				window.NowDate = NowDate;
		</script>
        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body>
    </body>
</html>
