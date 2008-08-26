<?php
    class ElementSearchView extends Element {
        public function Render() {
            ?><div id="search"><?php
            Element( 'search/options' );
            ?></div><?php
        }
    }
?>
