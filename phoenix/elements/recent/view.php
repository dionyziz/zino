<?php
    class ElementRecentView extends Element {
        public function Render() {
            global $page;
            global $libs;
            
            $libs->Load( 'favourite' );
            
            $page->AttachScript( 'js/recent.js' );
            $page->AttachStylesheet( 'css/recent.css' );
            
            ?><div id="recentevents">
            <div id="debugstatus"></div>
            <img class="loader" src="http://static.zino.gr/phoenix/recent-loader.gif" alt="Loading..." />
            </div><?php
        }
    }
?>
