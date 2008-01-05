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
		<title>
			Zino - είσαι μέσα;
		</title>
		<link rel="stylesheet" type="text/css" href="modal.css" />
		<!--[if IE]><link rel="stylesheet" type="text/css" href="modal-ie.css" /><![endif]-->
		<link rel="stylesheet" type="text/css" href="default.css" />
		<link rel="stylesheet" type="text/css" href="links.css" />
		<link rel="stylesheet" type="text/css" href="banner.css" />
		<!--[if IE]><link rel="stylesheet" type="text/css" href="banner-ie.css" /><![endif]-->
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="banner-ie6.css" /><![endif]-->
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="headlines.css" />
		<link rel="stylesheet" type="text/css" href="bubbles.css" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="bubbles-ie6.css" /><![endif]-->
		<link rel="stylesheet" type="text/css" href="people.css" />
		<link rel="stylesheet" type="text/css" href="paginate.css" />
		<link rel="stylesheet" type="text/css" href="forms.css" />
		<link rel="stylesheet" type="text/css" href="comments.css" />
		<link rel="stylesheet" type="text/css" href="usersections.css" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="usersections-ie6.css" /><![endif]-->
		<link rel="stylesheet" type="text/css" href="events.css" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="events-ie6.css" /><![endif]-->
		<?php
		if ( isset( $_GET[ 'p' ] ) ) {
			$p = $_GET[ 'p' ];
		}
		else {
			$p = 'frontpage';
		}
		switch ( $p ) {
			case 'join':
				?><link rel="stylesheet" type="text/css" href="join.css" />
				<!--[if IE 6]><link rel="stylesheet" type="text/css" href="join-ie6.css" /><![endif]--><?php
				break;
			case 'search':
				?><link rel="stylesheet" type="text/css" href="search.css" />
				<!--[if IE 6]><link rel="stylesheet" type="text/css" href="search-ie6.css" /><![endif]--><?php
				break;
			case 'joined':
				?><link rel="stylesheet" type="text/css" href="joined.css" /><?php
				break;
			case 'albums':
				?><link rel="stylesheet" type="text/css" href="albums.css" />
				<!--[if IE 6]><link rel="stylesheet" type="text/css" href="albums-ie6.css" /><![endif]--><?php
				break;
			case 'photoview':
				?><link rel="stylesheet" type="text/css" href="photoview.css" />
				<!--[if IE]><link rel="stylesheet" type="text/css" href="photoview-ie.css" /><![endif]--><?php
			case 'pollview':
				?><link rel="stylesheet" type="text/css" href="polllist.css" />
				<link rel="stylesheet" type="text/css" href="pollview.css" />
				<!--[if IE 6]><link rel="stylesheet" type="text/css" href="pollview-ie6.css" /><![endif]--><?php
				break;
			case 'polllist':
				?><link rel="stylesheet" type="text/css" href="polllist.css" /><?php
				break;
			case 'journalview':
				?><link rel="stylesheet" type="text/css" href="journalview.css" /><?php
				break;
			case 'journallist':
				?><link rel="stylesheet" type="text/css" href="journallist.css" /><?php
				break;
			case 'photolist':
				?><link rel="stylesheet" type="text/css" href="photolist.css" /><?php
				break;
			case 'interestlist':
				?><link rel="stylesheet" type="text/css" href="interestlist.css" /><?php
				break;
			case 'favourites':
				?><link rel="stylesheet" type="text/css" href="favourites.css" /><?php
				break;
			case 'settings':
				?><link rel="stylesheet" type="text/css" href="settings.css" /><?php
				break;
			case 'contactform':
				?><link rel="stylesheet" type="text/css" href="contactform.css" /><?php
				break;
			case 'userprofile':
				?><link rel="stylesheet" type="text/css" href="userprofile.css" /><?php
				break;
			case 'frontpage':
			default:
				?><link rel="stylesheet" type="text/css" href="frontpage.css" />
				<!--[if IE]><link rel="stylesheet" type="text/css" href="frontpage-ie.css" /><![endif]--><?php
		}
	?>
	</head>
	<body><?php
    include 'banner.php';
	?><div class="content" id="content"><?php
	switch ( $p ) {
		case 'join':
			include 'join.php';
			break;
        case 'search':
            include 'search.php';
            break;
		case 'joined':
			include 'joined.php';
			break;
		case 'usersections':
			include 'usersections.php';
			break;
		case 'albums':
			include 'albums.php';
			break;
		case 'photoview';
			include 'photoview.php';
			break;
		case 'pollview';
			include 'pollview.php';
			break;
		case 'polllist':
			include 'polllist.php';
			break;
		case 'journalview':
			include 'journalview.php';
			break;
		case 'journallist':
			include 'journallist.php';
			break;
		case 'photolist':
			include 'photolist.php';
			break;
		case 'interestlist':
			include 'interestlist.php';
			break;
		case 'favourites':
			include 'favourites.php';
			break;
        case 'settings':
            include 'settings.php';
            break;
		case 'contactform':
			include 'contactform.php';
			break;
		case 'userprofile':
			include 'userprofile.php';
			break;
		case 'frontpage':
		default:
			include 'frontpage.php';
	}
	?></div><?php
	include 'footer.php';
	?>
        <script type="text/javascript" src="../../js/pngfix.js"></script>
	</body>
</html>
