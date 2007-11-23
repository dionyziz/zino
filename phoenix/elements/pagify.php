<?php	
	function ElementPagify( $offset , $link , $totalnum , $showing , $offsetcaption = 'offset' ) {
		global $page;
		global $water;
		
		$page->AttachStyleSheet( 'css/pagify.css' );
		$pages = intval( $totalnum / $showing );
		if ( $totalnum % $showing != 0 ) {
			++$pages;
		}
		if ( $offset > $pages || $offset < 0 ) {
			return;
		}

		$pagebefore = $offset - 1;
		$pagenext = $offset + 1;
		if ( $pagebefore >= 1 ) {
			?><span class="leftpage">
				<a href="index.php?p=<?php
				echo $link;
				?>&amp;<?php
				echo $offsetcaption;
				?>=<?php
					echo $pagebefore;
					?>" class="nextbacklinks">&#171;Προηγούμενη
				</a>&nbsp;
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
		$water->Trace( 'endpos: '.$endpos );
		$water->Trace( 'offset: '.$offset );
		?><span><?php
		if ( $offset > 5 ) {
			?>... <?php
		}
		
		for ( $i = $startpos; $i <= $endpos; ++$i ) {
		?><a href="index.php?p=<?php
		echo $link;
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
			?><span class="rightpage">&nbsp;
			<a href="index.php?p=<?php
			echo $link;
			?>&amp;<?php
			echo $offsetcaption;
			?>=<?php
				echo $pagenext;
				?>" class="nextbacklinks">Επόμενη&#187;
			</a>
			</span><?php
		}
	}
?>