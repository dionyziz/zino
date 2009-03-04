<?php
    class ElementRecentView extends Element {
        public function Render() {
            global $page;
            
            $page->AttachScript( 'js/recent.js' );
            $page->AttachStylesheet( 'css/recent.css' );
            
            $favourite = New Favourite( 76 );
            var_dump( $favourite->Item->Id );
            die();
            
            $image = New Image( 100416 );
            
            var_dump( 
                ProportionalSize( 210, 210, $image->Width, $image->Height )
            );
            
            die();
            
            ?><div id="recentevents">
            <div id="debugstatus"></div>
            <img class="loader" src="http://static.zino.gr/phoenix/recent-loader.gif" alt="Loading..." />
            </div><?php
        }
    }
?>
