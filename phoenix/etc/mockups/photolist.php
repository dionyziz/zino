<?php
	include 'usersections.php';
?>
<div id="photolist">
	<h2>Ουρανοξύστες</h2>
	<dl>
		<dt class="photonum">29 φωτογραφίες</dt>
		<dt class="commentsnum">328 σχόλια</dt>
	</dl>
	<ul><?php
		for ( $i = 0; $i < 11; ++$i ) {
			?><li><?php
				Element( 'album/photo/small' );
			?></li><?php
		}
	?></ul>
	<div class="eof"></div>
</div>
