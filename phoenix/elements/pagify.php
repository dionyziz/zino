<?php

	/*  
	offset: current page number
	link: link to the page. it should end with offset= e.g. '/journal/Hello?offset='
	total_pages: the total pages of the entity
	*/
    function ElementPagify( $offset, $link, $total_pages ) {
        global $water;

    	if ( $offset > $total_pages || $offset < 0 || $total_pages < 2 ) {
            $water->Trace( "Pagify: $offset, $link, $total_pages" );
            $water->Trace( "No need for pagify. Stopping." );
    		return;	
		}
		
		?><div class="pagify"><?php
		
		if ( $offset > 1 ) { /* left arrow */
			?><span class="leftpage"><a href="<?php
			echo htmlspecialchars( $link . $offset - 1 );
			?>" class="previous" title="Προηγούμενη"></a></span><?php
		}
		
		?><span><?php
		
		if ( $offset > 5 ) {
			?>...<?php
		}
        
        $startpage = ( $offset - 4 >= 1 ) ? $offset - 4 : 1;
        $endpage = ( $offset + 4 <= $total_pages ) ? $offset + 4 : $total_pages;
        for ( $page = $startpage; $page <= $endpage; ++$page ) {
        	?><a href="<?php
        	echo htmlspecialchars( $link . $page );
        	?>" class="nextbacklinks"<?php
        	if ( $page == $offset ) {
        		?> style="font-weight: bold;"<?php
			}
			?>><?php
			echo $page;
			?></a><?php
			if ( $page != $endpage ) {
				?>, <?php
			}
		}
		
		if ( $offset + 4 < $total_pages ) {
			?> ...<?php
		}
		
		?></span><?php
		
		if ( $offset + 1 <= $total_pages ) { /* right arrow */
			?><span class="rightpage"><a href="<?php
			echo htmlspecialchars( $link . $offset + 1 );
			?>" class="next" title="Επόμενη"></a></span><?php
		}
		
		?></div><?php
    }

?>
