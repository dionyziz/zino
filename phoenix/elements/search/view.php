<?php
    class ElementSearchView extends Element {
        public function Render() {
            ?><div id="search"><?php
            Element( 'search/options' );
            ?></div><?php
                $users = array();
                $users[] = New User( 1 );
                $users[] = New User( 791 );
            Element( 'user/list', $users );
        }
    }
?>
