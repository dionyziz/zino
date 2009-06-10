<?php
    class ElementUserPasswordRequestSuccess extends Element {
        public function Render( tText $username ) {
            global $page;
            
            $page->SetTitle( 'Επαναφορά κωδικού' );
            
            $username = $username->Get();
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindByName( $username );
            if ( $user === false ) {
                return Redirect( 'forgot/failure' );
            }
            
            ?><h2>Έτοιμ<?php
            if ( $user->Gender == 'f' ) {
                ?>η<?php
            }
            else {
                ?>ος<?php
            }
            ?>!</h2>
            <p style="padding-bottom: 20px; margin-bottom: 0">
                Σου έχουμε στείλει ένα e-mail με οδηγίες για το πώς θα αλλάξεις τον κωδικό σου.
            </p>
            <?php
        }
    }
?>
