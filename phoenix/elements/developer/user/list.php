<?php

    class ElementDeveloperUserList extends Element {
        public function Render( $relations ) { /* array of Relation instances or User instances */
            global $libs;
            
            $libs->Load( 'image/image' );
            
            ?><div class="people">
                <ul class="lst ul1"><?php
                    foreach ( $relations as $relation ) {
                        $theuser = ( get_class( $relation ) == "User" ) ? $relation : $relation->Friend;
                        ?><li><a href="<?php
                        ob_start();
                        Element( 'developer/user/url', $theuser->Id , $theuser->Subdomain );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>"><?php
                        Element( 'developer/user/avatar', $theuser->Avatarid , $theuser->Id , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 100 , '' , '' , false , 0 , 0 );
                        ?></a></li><?php
                    }            
                ?></ul>
                <div class="eof"></div>
            </div><?php
        }
    }

?>
