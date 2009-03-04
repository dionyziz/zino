<?php
    class ElementRecentView extends Element {
        public function Render() {
            global $page;
            
            $page->AttachScript( 'js/recent.js' );
            $page->AttachStylesheet( 'css/recent.css' );
            
            var_dump( 
                ProportionalSize( 210, 210, 400, 100 )
            );
            
            die();
            
            ?><div id="recentevents">
            <div id="debugstatus"></div>
            <img class="loader" src="http://static.zino.gr/phoenix/recent-loader.gif" alt="Loading..." />
            </div><?php
        }
    }
?>
