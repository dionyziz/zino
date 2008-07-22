<?php
	function linksReplaced($text) {
		$text = preg_replace(
			'#\b(https?\://[a-z0-9.-]+(/[a-zA-Z0-9./+?=&;%-]*)?)#',
			'<a href="\1">\1</a>',
			$text
        	);
        	return $text;
	}

	$tests = array(
		'http://www.foo.com/',
		'https://lol.bar.gr/index.html hey',
		'http://plol/s.rb?p=show&a=lok',
		'yeah https://ex.org/q.php?t=po&amp;ww=uy ..!'
	);

	foreach ( $tests as $t ) {
		$result = linksReplaced($t);
		echo "$result<br></br>";
	}
?>

