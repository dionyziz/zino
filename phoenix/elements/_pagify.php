<?php

    /*  
    pageno: current page number
    link: link to the page. it should end with offset= e.g. '/journal/Hello?offset='
    total_pages: the total pages of the entity
    text: Show a text under the 1 2 3
    */
    class ElementPagify extends Element {
        public function Render( $pageno, $link, $total_pages, $text = '' ) {
            if ( $pageno > $total_pages || $pageno < 0 ) {
                return;    
            }
            
            ?><div class="pagify"><?php
            
            if ( $pageno > 1 ) { /* left arrow */
                ?><span class="leftpage"><a href="<?php
                echo htmlspecialchars( $link . ( $pageno - 1 ) );
                ?>" class="previous" title="Προηγούμενη"></a></span><?php
            }
            
            ?><span><?php
            
            if ( $pageno > 5 ) {
                ?>...<?php
            }
            
            $startpage = ( $pageno - 4 >= 1 ) ? $pageno - 4 : 1;
            $endpage = ( $pageno + 4 <= $total_pages ) ? $pageno + 4 : $total_pages;
            if ( $endpage - $startpage > 0 ) {
                for ( $p = $startpage; $p <= $endpage; ++$p ) {
                    if ( $p == $pageno ) {
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
            
            if ( $pageno + 4 < $total_pages ) {
                ?> ...<?php
            }
            
            ?></span><?php
            
            if ( $pageno + 1 <= $total_pages ) { /* right arrow */
                ?><span class="rightpage"><a href="<?php
                echo htmlspecialchars( $link . ( $pageno + 1 ) );
                ?>" class="next" title="Επόμενη"></a></span><?php
            }
            
            if ( $text != '' ) {
                ?><div class="notifycount"><?php
                echo $text;
                ?></div><?php
            }
            ?></div><?php
        }

    }
?>
