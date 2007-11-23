<?php
	$loggedin = true;	
	include "banner.php";
	
	$comments = array(
		array(
			'type' => 'operator',
			'nick' => 'Noel1',
			'time' => 'πριν 5 λεπτά',
			'text' => 'Κλαίω πάαααρα πολύ!!!!!!'
		),
		array(
			'type' => 'developer',
			'nick' => 'Dionyziz',
			'time' => 'πριν 3 λεπτά',
			'text' => 'Ρε noel συμαζέψου!<br />Δεν είναι παιδικός σταθμός εδώ!'
		),
		array(
			'type' => 'operator',
			'nick' => 'Blink',
			'time' => 'πριν λίγο',
			'text' => 'Dikio exei o dionyziz<br />aman to gamisame re paidia'
		),
	);
?>
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<div class="comments">
	<?php
		$indent = 0;
		foreach ( $comments as $comment ) {
			$type = $comment[ 'type' ];
			$nick = $comment[ 'nick' ];
			$time = $comment[ 'time' ];
			$text = $comment[ 'text' ];
			++$indent;
			include 'comment.php';
		}
	?>
</div>
