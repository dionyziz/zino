<?php
    class ElementDeveloperDionyzizMeteor extends Element {
        public function Render() {
            global $page;
            
            $page->AttachScript( 'http://universe.zino.gr/meteor.js' );
            $page->AttachScript( 'js/universe.js' );
        }
    }
?>
