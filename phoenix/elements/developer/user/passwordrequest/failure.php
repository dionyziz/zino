<?php
    class ElementDeveloperUserPasswordRequestFailure extends Element {
        public function Render( tText $username ) {
            global $page;
            
            $page->SetTitle( 'Επαναφορά κωδικού' );
            
            $username = $username->Get();
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindByName( $username );
            ?><h2>Δεν μπορούμε να επαναφέρουμε τον κωδικό σου <span class="emoticon-cry">.</span></h2>
            <form action="user/passwordrequest" method="post" style="padding-bottom: 20px">
                <p>
                    Συγγνώμη, αλλά δεν μπορέσαμε να επαναφέρουμε τον κωδικό σου, επειδή <?php
                    if ( $user !== false ) {
                        ?>δεν είχες δηλώσει μία έγκυρη διεύθυνση e-mail κατά την εγγραφή σου.
                        </p><p>
                        <strong><a href="?p=join" title="Φτιάξε ένα νέο προφίλ">Δημιούργησέ 
                        ένα νέο προφίλ</a></strong>!<?php
                    }
                    else {
                        ?>το όνομα χρήστη <strong><?php
                        echo htmlspecialchars( $username );
                        ?></strong> δεν υπάρχει.</p><p>
                        
                        <strong><a href="?p=join&amp;username=<?php
                        echo htmlspecialchars( $username );
                        ?>" title="Φτιάξε ένα νέο προφίλ">Δημιούργησέ το</a></strong>!<?php
                    }
                    ?>
                </p>
            </form><?php
        }
    }
?>
