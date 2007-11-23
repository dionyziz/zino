<?php
	$loggedin = true;
	
	include 'banner.php';
?>

<div style="margin-top: 70px; margin-bottom: 50px;">
	<table class="chess"><?php
		for ( $i = 0; $i < 8; ++$i ) {
			?><tr><?php
				for ( $j = 0; $j < 8; ++$j ) {
					?><td id="<?php
					echo "cpos_" . $i . "_" . $j;
					?>"<?php
					if ( $i % 2 == $j % 2  ) {
						?> class="b"<?php
					}
					?>><?php
					if ( $i < 2 || $i > 5 ) {
						?>πούλι<?php
					}
					?></td><?php
				}
			?></tr><?php
		}
	?></table>	
</div>