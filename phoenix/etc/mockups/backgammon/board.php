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
			<div class="board">
				<div class="leftborder">
					<div class="container1">
						<div class="up">
						</div>
						<div class="middle">
						</div>
						<div class="bottom">
						</div>
					</div>
					<div class="container2">
						<div class="up">
						</div>
						<div class="middle">
						</div>
						<div class="bottom">
						</div>
					</div>
				</div>
				<div class="left">
					<div class="up">
					</div>
					<div class="gamearea">
						<div class="up">
							<div class="left">
							</div>
							<div class="">
							</div>
							<div class="">
							</div>
							<div class="">
							</div>
							<div class="">
							</div>
							<div class="right">
							</div>
						</div>
						<div class="middle">
						</div>
						<div class="down">
						</div>
					</div>
					<div class="down">
					</div>
				</div>
				<div class="middleborder">
				</div>
				<div class="right">
					<div class="up">
					</div>
					<div class="gamearea">
					</div>
					<div class="down">
					</div>
				</div>
				<div class="rightborder">
				</div>
			</div>
		</div>
	</body>
</html>