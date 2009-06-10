<?php
    class ElementUserPasswordRequestSuccess extends Element {
        public function Render( tText $username ) {
            $username = $username->Get();
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindByUsername( $username );
            ?><h2>Έτοιμ<?php
            if ( $user->Gender == 'f' ) {
                ?>η<?php
            }
            else {
                ?>ος<?php
            }
            ?>!</h2>
            <form action="user/passwordrequest" method="post">
                <p>
                    Σου έχουμε στείλει ένα e-mail με οδηγίες για το πώς θα αλλάξεις τον κωδικό σου.
                </p>
            </form><?php
        }
    }
?>
