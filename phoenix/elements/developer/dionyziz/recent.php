<?php
    /* Content-type: text/js */
    class ElementDeveloperDionyzizRecent extends Element {
        public function Render() {
            global $page;
            
            $page->AttachScript( 'js/recent.js' );
            $page->AttachStylesheet( 'css/recent.css' );
            
            ?><div id="recentevents"></div><?php
        }
    }
?>
