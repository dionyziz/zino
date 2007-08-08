<?php
	function ElementUserLostPassword( tBoolean $nosuchuser, tBoolean $invalidmail, tBoolean $sent ) {
		global $user;
		global $page;
		
        $nosuchuser = $nosuchuser->Get();
        $invalidmail = $invalidmail->Get();
        $sent = $sent->Get();
        
		if ( $user->IsAnonymous() ) {
			$page->SetTitle( "Ξέχασα τον Κωδικό μου!" );
			
			?><br /><br /><h3>Ξέχασα τον Κωδικό μου!</h3><?php
			
			if ( $nosuchuser ) {
				?>Δεν υπάρχει κανένας χρήστης με αυτό το όνομα χρήστη! Δοκίμασε ξανά!<?php
			}
			else if ( $invalidmail ) {
				?>Δεν έχετε δηλώσει έγκυρο e-mail στον λογαριασμό σας!<br /> Δεν ήταν δυνατή η αποστολή του e-mail!<?php
			}
			else if ( $sent ) {
				?>Ένα e-mail με οδηγίες για το πώς να εισέλθεται στάλθηκε στον λογαριασμό που είχατε δηλώσει κατά την εγγραφή σας.<?php
			}
			else {
				?><br /><form action="do/user/lostpassword" method="post">
                    Γράψε μας το όνομα χρήστη σου και, αν έχεις δηλώσει μία e-mail διεύθυνση θα σου
                    στείλουμε εκεί οδηγίες για το πως να εισέλθεις! <br /><br />
                    Όνομα Χρήστη <input type="text" class="bigtext" name="username" /><br />
                    <input type="submit" class="mybutton" value="Αποστολή" />
                </form><?php
			}
		}
		else {
			?><a href="index.php">Πίσω στην Κεντρική Σελίδα</a><?php
		}
	}
?>
