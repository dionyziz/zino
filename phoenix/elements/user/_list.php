<?php

    class ElementUserList extends Element {
        public function Render( $relations ) { /* array of Relation instances or User instances */
            ?><div class="people">
                <ul><?php
                    foreach ( $relations as $relation ) {
                        $theuser = ( get_class( $relation ) == "User" ) ? $relation : $relation->Friend;
                        ?><li><a href="<?php
                        ob_start();
                        Element( 'user/url', $theuser->Id , $theuser->Subdomain );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>"><?php
                        Element( 'user/avatar', $theuser->Avatar->Id , $theuser->Id , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 100 , '' , '' , false , 0 , 0 );
                        ?></a></li><?php
                    }            
                ?></ul>
                <div class="eof"></div>
            </div><?php
        }
    }

?>
