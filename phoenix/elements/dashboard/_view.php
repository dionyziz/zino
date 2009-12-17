<?php
    class ElementDashboardView extends Element {
        public function Render() {
            global $page;

            $page->AttachStylesheet( 'css/default.css' );
            $page->AttachStylesheet( 'css/banner.css' );
            $page->AttachStylesheet( 'css/footer.css' );
            $page->AttachStylesheet( 'css/links.css' );
            $page->AttachStylesheet( 'css/spriting/sprite1.css' );
            $page->AttachStylesheet( 'css/spriting/sprite2.css' );
            $page->AttachStylesheet( 'css/spriting/spritex.css' );

            $page->AttachStylesheet( 'css/dashboard.css' );

            $page->AttachScript( 'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js' );
            $page->AttachScript( 'js/kamibu.js' );
            $page->AttachScript( 'js/dashboard.js' ); 

        }
    }
?>
