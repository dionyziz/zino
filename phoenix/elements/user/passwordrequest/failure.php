<?php
    class ElementUserPasswordRequestFailure extends Element {
        public function Render( tText $username ) {
            $username = $username->Get();
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindByUsername( $username );
            ?><h2>Δεν μπορούμε να επαναφέρουμε τον κωδικό σου <span class="emoticon-cry">.</span></h2>
            <form action="user/passwordrequest" method="post">
                <p>
                    Συγγνώμη, αλλά δεν μπορέσαμε να επαναφέρουμε τον κωδικό σου, επειδή <?php
                    if ( $user->Exists() ) {
                        ?>δεν είχες δηλώσει μία έγκυρη διεύθυνση e-mail κατά την εγγραφή σου.
                        </p><p>
                        <strong><a href="http://www.zino.gr/?p=join" title="Φτιάξε ένα νέο προφίλ">Δημιούργησέ 
                        ένα νέο προφίλ</a></strong>!<?php
                    }
                    else {
                        ?>το όνομα χρήστη </strong><?php
                        echo htmlspecialchars( $username );
                        ?></strong> δεν υπάρχει.</p>
                        
                        <strong><a href="http://www.zino.gr/?p=join&amp;username=<?php
                        echo htmlspecialchars( $username );
                        ?>" title="Φτιάξε ένα νέο προφίλ">Δημιούργησέ το</a></strong>!<?php
                    }
                    ?>
                </p>
            </form><?php
        }
    }
?>
