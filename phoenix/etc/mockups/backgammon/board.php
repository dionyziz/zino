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
			<table class="board">
				<tr>	
					<td class="outerleft">
						
					</td>
					<td class="gamearea1">
						<table>
							<tr>
								<td class="outerup"></td>
							</tr>
							<tr>
								<td class="upperleft"></td>
								<td class="upperwhite"></td>
								<td class="upperbrown"></td>
								<td class="upperwhite"></td>
								<td class="upperbrown"></td>
								<td class="upperright"></td>
							</tr>
							<tr>
								<td class="middle"></td>
							</tr>
							<tr>
								<td class="downleft"></td>
								<td class="downwhite"></td>
								<td class="downbrown"></td>
								<td class="downwhite"></td>
								<td class="downbrown"></td>
								<td class="downright"></td>
							
							</tr>
							<tr>
								<td class="outerdown"></td>
							</tr>
						</table>
					</td>
					<td class="outermiddle">
					</td>
					<td class="gamearea2">
						<table>
							<tr>
								<td class="outerup"></td>
							</tr>
							<tr>
								
							</tr>
							<tr>
								<td class="middle"></td>
							</tr>
							<tr>
							
							</tr>
							<tr>
								<td class="outerdown"></td>
							</tr>
						</table>
					</td>
					<td class="outerright">
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>