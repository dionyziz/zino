<?php

    class ElementUserList extends Element {
        public function Render( $relations ) {
            ?><div class="people">
                <ul><?php
                    foreach ( $relations as $relation ) {
                        ?><li><a href="<?php
                        Element( 'user/url', $relation->Friend->Id , $relation->Friend->Subdomain );
                        ?>"><?php
                        Element( 'user/avatar', $relation->Friend, 100, '', '', false, 0, 0 );
                        ?><strong><?php
                        echo Element( 'user/name', $relation->Friend, false );
                        ?></strong><span>προβολή προφίλ &raquo;</span></a></li><?php
                    }            
                ?></ul>
                <div class="eof"></div>
            </div><?php
        }
    }

?>
