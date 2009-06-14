<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            
            $page->Title( 'Επικοινωνία' );
            
            Element( 'about/contact/view' ); // TODO!
        }
    }
?>
