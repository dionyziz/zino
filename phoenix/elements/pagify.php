<?php

	/*  
	page: current page number
	link: link to the page. it should end with offset= e.g. '/journal/Hello?offset='
	total_pages: the total pages of the entity
	*/
    function ElementPagify( $page, $link, $total_pages ) {
        global $water;

        $water->Trace( 'page', $page );
        $water->Trace( 'total_pages', $total_pages );

    	if ( $page > $total_pages || $page < 0 ) {
    		return;	
		}
		
		?><div class="pagify"><?php
		
		if ( $page > 1 ) { /* left arrow */
			?><span class="leftpage"><a href="<?php
			echo htmlspecialchars( $link . ( $page - 1 ) );
			?>" class="previous" title="Προηγούμενη"></a></span><?php
		}
		
		?><span><?php
		
		if ( $page > 5 ) {
			?>...<?php
		}
        
        $startpage = ( $page - 4 >= 1 ) ? $page - 4 : 1;
        $endpage = ( $page + 4 <= $total_pages ) ? $page + 4 : $total_pages;
        if ( $endpage - $startpage > 0 ) {
            for ( $p = $startpage; $p <= $endpage; ++$p ) {
                if ( $p == $page ) {
                    ?><strong><?php
                    echo $p;
                    ?></strong><?php
                }
                else {
                    ?><a href="<?php
                    echo htmlspecialchars( $link . $p );
                    ?>" class="nextbacklinks"><?php
                    echo $p;
                    ?></a><?php
                }

                if ( $p != $endpage ) {
                    ?> <?php
                }
            }
        }
		
		if ( $page + 4 < $total_pages ) {
			?> ...<?php
		}
		
		?></span><?php
		
		if ( $page + 1 <= $total_pages ) { /* right arrow */
			?><span class="rightpage"><a href="<?php
			echo htmlspecialchars( $link . ( $page + 1 ) );
			?>" class="next" title="Επόμενη"></a></span><?php
		}
		
		?></div><?php
    }

?>
