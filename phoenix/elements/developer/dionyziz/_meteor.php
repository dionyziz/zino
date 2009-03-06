<?php
    class ElementDeveloperDionyzizMeteor extends Element {
        public function Render() {
            global $page;
            
            $page->AttachScript( 'js/meteor.js' );
            $page->AttachScript( 'js/universe.js' );
        }
    }
?>
