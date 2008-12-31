<?php
    class ElementiPhoneFooter extends Element {
        public function Render() {
            global $user;

            ?><div class="footer">&copy; 2008 Kamibu<?php
            if ( $user->Exists() ) {
                ?> | <form method="post" action="do/user/logout"><a href="" onclick="this.parentNode.submit(); return false">Έξοδος</a></form><?php
            }
            ?></div><?php
        }
    }
?>
