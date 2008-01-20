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
					<td class="leftarea">
						<table>
							<tr>
								<td class="outerup1"></td>
							</tr>
							<tr>
								<table class="conesup1">
									<tr>
										<td class="leftup"></td>
										<td class="whiteup"></td>
										<td class="brownup"></td>
										<td class="whiteup"></td>
										<td class="brownup"></td>
										<td class="rightup"></td>
									</tr>
								</table>
							</tr>
							<tr>
								<td class="middle1"></td>
							</tr>
							<tr>
								<table class="conesdown1">
									<tr>
										<td class="leftdown"></td>
										<td class="whitedown"></td>
										<td class="browndown"></td>
										<td class="whitedown"></td>
										<td class="browndown"></td>
										<td class="rightdown"></td>
									</tr>
								</table>
							</tr>
							<tr>
								<td class="outerdown1" style="width: 338px;height: 25px;"></td>
							</tr>
						</table>
					</td>
					<td class="middlearea" style="width:54px;height:636px;">
					</td>
					<td class="rightarea" style="width:337px;height:636px;">
						<table>
							<tr>
								<td class="outerup2"></td>
							</tr>
							<tr>
								<table class="conesup2">
									<tr>
										<td class="leftup"></td>
										<td class="whiteup"></td>
										<td class="brownup"></td>
										<td class="whiteup"></td>
										<td class="brownup"></td>
										<td class="rightup"></td>
									</tr>
								</table>
							</tr>
							<tr>
								<td class="middle2"></td>
							</tr>
							<tr>
								<table class="conesdown2">
									<tr>
										<td class="leftdown"></td>
										<td class="whitedown"></td>
										<td class="browndown"></td>
										<td class="whitedown"></td>
										<td class="browndown"></td>
										<td class="rightdown"></td>
									</tr>
								</table>
							</tr>
							<tr>
								<td class="outerdown2"></td>
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