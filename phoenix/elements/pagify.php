<?php	
	function ElementPagify( $offset , $link , $totalnum , $showing , $offsetcaption = 'offset' ) {
		global $water;
		global $rabbit_settings;
		/*
			$offset is the page number
			$totalnum is the total number of elements that exist... eg the number of all albums of a user
			$showing is the number of elements shown on each page, eg 20 albums on each page
			
		*/
		$pages = intval( $totalnum / $showing );
		if ( $totalnum % $showing != 0 ) {
			++$pages;
		}
		if ( $offset > $pages || $offset < 0 ) {
			return;
		}

		$pagebefore = $offset - 1;
		$pagenext = $offset + 1;
		if ( $showing < $totalnum ) {
			?><div class="pagify"><?php
			if ( $pagebefore >= 1 ) {
				?><span class="leftpage">
					<a href="index.php?p=<?php
					echo htmlspecialchars( $link );
					?>&amp;<?php
					echo $offsetcaption;
					?>=<?php
						echo $pagebefore;
						?>" class="previous" title="Προηγούμενη"></a>
				</span><?php
			}
			$startpos = $offset - 4;
			if ( $startpos < 1 ) {
				$startpos = 1;
			}
			$endpos = $offset + 4;
			if ( $endpos > $pages ) {
				$endpos = $pages;
			}
			?><span><?php
			if ( $offset > 5 ) {
				?>... <?php
			}
			
			for ( $i = $startpos; $i <= $endpos; ++$i ) {
			?><a href="index.php?p=<?php
			echo htmlspecialchars( $link );
			?>&amp;<?php
			echo $offsetcaption;
			?>=<?php
				echo $i;
				?>" class="nextbacklinks"<?php
				if ( $i == $offset ) {
					?> style="font-weight:bold"<?php
				}
				?>><?php
				echo $i;
				?></a><?php
				if ( $i != $endpos ) {
					?>, <?php
				}
			}
			if ( $offset + 4 < $pages ) {
				?> ...<?php
			}
			?></span><?php
			if ( $pagenext <= $pages ) {
				?><span class="rightpage">
				<a href="index.php?p=<?php
				echo htmlspecialchars( $link );
				?>&amp;<?php
				echo $offsetcaption;
				?>=<?php
					echo $pagenext;
					?>" class="next" title="Επόμενη"></a>
				</span><?php
			}
			?></div><?php
		}
	}
?>