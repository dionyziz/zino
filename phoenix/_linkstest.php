<?php
	function WYSIWYG_Links($text) {
		$text = preg_replace(
			'#\b(https?\://[a-z0-9.-]+(/[a-zA-Z0-9./+?;&=%-]*)?)(\s|$)#',
			'<a href="\1">\1</a>',
			$text
		);
		return $text;
	}

	$tests = array(
		'http://www.google.com/',
		'Hello https://python.org/ !',
		'http://localhost/index.php?p=comments&a=show <-- look here',
		'OK https://foo.bar.gr/wiki.php?a=true&s=false ... htts://mistake.org/ http:/another.net/index.php ...'
	);

	foreach ( $tests as $t ) {
		$result = WYSIWYG_Links($t);
		echo "$result <br />\n";
	}
?>

