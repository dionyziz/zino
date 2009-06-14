<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            
            $page->SetTitle( 'Επικοινωνία' );
            
            Element( 'about/contact/view' ); // TODO!
        }
    }
?>
