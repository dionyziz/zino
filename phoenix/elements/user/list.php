<?php

    class ElementUserList extends Element {
        public function Render( $relations ) {
            ?><div class="people">
                <ul><?php
                    foreach ( $relations as $relation ) {
                        ?><li><a href="<?php
                        Element( 'user/url', $relation->Friend->Id , $relation->Friend->Subdomain );
                        ?>"><?php
                        Element( 'user/avatar', $relation->Friend->Avatar->Id , $relation->Friend->Id , $relation->Friend->Avatar->Width , $relation->Friend->Avatar->Height , $relation->Friend->Name , 100 , '' , '' , false , 0 , 0 );
                        ?><strong><?php
                        echo Element( 'user/name', $relation->Friend->Id , $relation->Friend->Name , $relation->Friend->Subdomain , false );
                        ?></strong><span>προβολή προφίλ &raquo;</span></a></li><?php
                    }            
                ?></ul>
                <div class="eof"></div>
            </div><?php
        }
    }

?>
