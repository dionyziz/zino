<?php

	class ElementUserSections extends Element {
        public function Render( $section , $theuser ) {
            ?><div class="usersections">
                <a href="<?php
                    Element( 'user/url' , $theuser );
                    ?>"><?php
                    Element( 'user/avatar' , $theuser , 150 , '' , '' );
                    ?><span class="name"><?php
                    echo $theuser->Name;
                    ?></span>
                </a>
                <ul>
                    <li<?php
                    if ( $section == 'album' ) {
                        ?> class="selected"<?php
                    }
                    ?>><a href="<?php
                    Element( 'user/url' , $theuser );
                    ?>albums">Albums</a></li>
                    <li>·</li>
                    <li<?php
                    if ( $section == 'poll' ) {
                        ?> class="selected"<?php
                    }
                    ?>><a href="<?php
                    Element( 'user/url' , $theuser );
                    ?>polls">Δημοσκοπήσεις</a></li>
                    <li>·</li>
                    <li<?php
                    if ( $section == 'journal' ) {
                        ?> class="selected"<?php
                    }
                    ?>><a href="<?php
                    Element( 'user/url' , $theuser );
                    ?>journals">Ημερολόγιο</a></li>
                    <li>·</li>
                    <li<?php
                    if ( $section == 'space' ) {
                        ?> class="selected"<?php
                    }
                    ?>><a href="<?php
                    Element( 'user/url' , $theuser );
                    ?>space">Χώρος</a></li>
                    <li>·</li>
                    <li<?php
                    if ( $section == 'relations' ) {
                        ?> class="selected"<?php
                    }
                    ?>><a href="<?php
                    Element( 'user/url' , $theuser );
                    ?>friends">Φίλοι</a></li>
                </ul>
            </div><?php
        }
    }
?>
