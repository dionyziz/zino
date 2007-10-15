<?php
    function ElementUserBox( $transparent = false ) {
        global $user;
        global $xc_settings;
        
        ?><div class="aku<?php
        if ( $user->IsAnonymous() ) {
            ?> akuloggedout<?php
        }
        ?>" style="<?php
        if ( $user->UserboxStatus() == "hidden" ) {
            ?>position:absolute;top: -51px;<?php
        }
        if ( $transparent ) {
            ?>opacity:0;<?php
        }
        ?>">
        <div style="float:right"><img src="<?php
        echo $xc_settings[ 'staticimagesurl' ];
        ?>akuright.jpg" alt="" /></div>
        <div style="float:left"><img src="<?php
        echo $xc_settings[ 'staticimagesurl' ];
        ?>akuleft.jpg" alt="" /></div>
        <div class="content">
            <div>
                <?php
                if ( !$user->IsAnonymous() ) {
                    Element( "user/loggedin" );
                }
                else {
                    ?><br /><?php
                    Element( "user/loginform" );
                }
            ?></div>
        </div><?php
        if( !$user->IsAnonymous() ) {
            ?><ul><?php
                if ( $user->Rights() >= $xc_settings[ 'readonly' ] ) {
                    ?><li><a class="options" href="?p=p">Επιλογές</a></li><?php
                }

                ?><li><a class="profile" href="user/<?php 
                echo $user->Username(); 
                ?>">Προφίλ</a></li><?php
                if ( $user->Rights() >= $xc_settings[ 'chat' ][ 'enabled' ] ) {
                    ?><li><a class="chat" href="" onclick="window.open('chat/', 'ccchat');return false;">Κάνε Chat</a></li><?php
                }
                ?><li><a href="javascript:Userbox.Animate();" class="arrow" style="visibility:hidden;" title="Προβολή κάρτας χρήστη" id="userboxshow"></a></li>
            </ul><?php
        }
        ?>
        </div><?php
    }

?>
