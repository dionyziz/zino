<?php
    class ElementiPhoneFooter extends Element {
        public function Render() {
            global $user;

            ?><div class="footer"><?php
            if ( $user->Exists() ) {
                ?><div class="toolbar"><form method="post" action="do/user/logout"><a href="" onclick="this.parentNode.submit(); return false">Έξοδος</a></form></div><?php
            }
            ?>&copy; 2008 Kamibu</div><?php
        }
    }
?>
