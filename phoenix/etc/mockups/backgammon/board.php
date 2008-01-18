<?php

$xmlmimetype = 'application/xhtml+xml';
$accepted = explode( ',' , $_SERVER[ 'HTTP_ACCEPT' ] );
if ( in_array( $xmlmimetype , $accepted ) ) {
	header( "Content-Type: application/xhtml+xml; charset=utf-8" );
}
else {
	header( "Content-Type: text/html; charset=utf-8" );
}
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
	<head>
		<title>Τάβλι</title>
	
		<link rel="stylesheet" type="text/css" href="board.css" />
	</head>
	<body>
		<div id="main">
			<table style="width: 100px;border: 1px solid black;">
				<tr>
					<td style="border: 1px solid green;"></td>
					<td style="border: 1px solid red;"></td>
				</tr>
			</table><!--
			<table class="board">
				<tr>	
					<td class="outerleft">
						
					</td>
					<td class="gamearea1">
					</td>
					<td class="outermiddle">
					</td>
					<td class="gamearea2">
					</td>
					<td class="outerright">
					</td>
				</tr>
			</table>//-->
			
		</div>
	</body>
</html>