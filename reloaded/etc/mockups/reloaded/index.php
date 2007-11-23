<?php
	$xmlmimetype = 'application/xhtml+xml';
	$accepted = explode( ',' , $_SERVER[ 'HTTP_ACCEPT' ] );
	if ( in_array( $xmlmimetype , $accepted ) ) {
		$xmlbrowser = true;
		header( "Content-Type: application/xhtml+xml; charset=utf-8" );
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo '<?xml-stylesheet href="style.css.php" type="text/css"?>';
	}
	else {
		$xmlbrowser = false;
		header( "Content-Type: text/html; charset=utf-8" );
		echo '<?xml version="1.0" encoding="utf-8"?>';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<?php
			include "title.php";
			if (!$xmlbrowser) {
				?><link href="style.css.php" rel="stylesheet" type="text/css" /><?php
			}
		?>
        <script type="text/javascript" src="../../../js/animations.js"></script>
		<script type="text/javascript" src="js/chess.js"></script>
	</head>
	<body>
		<div class="axel">
			<?php
                if ( !isset( $_GET[ 'p' ] ) ) {
                    $_GET[ 'p' ] = '';
                }
				switch ( $_GET[ 'p' ] ) {
					case 'chess':
						include 'chess.php';
						break;
					case 'frontpagedebug':
						include 'frontpage-debug.php';
						break;
					case 'photos':
						include 'photos.php';
						break;
					case 'frontpageloggedout':
						include 'frontpage-loggedout.php';
						break;
					case 'userpage':
						include 'userpage.php';
						break;
					case 'register':
						include 'register.php';
						break;
					case 'comment':
						include 'comments.php';
						break;
					case 'newarticle':
						include 'newarticlepage.php';
						break;
					case 'settings':
						include 'settings.php';
						break;
					case 'article':
						include 'article.php';
						break;
					case 'search':
						include 'search.php';
						break;
					case 'chat':
						include 'chat.php';
						break;
					case 'pmnew':
						include 'pmnew.php';
						break;
					case 'pms':
						include 'pms.php';
						break;
					case 'faq':
					case 'faqq':
					case 'faqc':
					case 'faqs':
						include $_GET[ 'p' ] . ".php";
						break;
					case 'frontpage':
					default:
						include 'frontpage.php';
				}
				include 'copyright.php';
			?>
		</div>
	</body>
</html>
