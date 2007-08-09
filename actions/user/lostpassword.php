<?php
    function ActionUserLostPassword( tString $username ) {
	global $rabbit_settings;

    	$username = $username->Get(); // Gets the username from the elements/user/lostpassword.php
    	$user = new User( $username ); // Attempts to create a new instance of the user class
    	
    	if ( $user == false ) { // If the user does not exist in the database
            return Redirect( '?p=lostpassword&nosuchuser=yes' );
    	}
    	if ( $user->Email() == "" ) { // If the user didn't provide an email address
            return Redirect( '?p=lostpassword&invalidmail=yes' );
    	}
    	$to = $user->Email();
    	$subject = $rabbit_settings[ 'applicationname' ] . ": Νέος κωδικός πρόσβασης";
    	$msg = "Αγαπητέ χρήστη του " . $rabbit_settings[ 'applicationname' ] . ",\r\n\r\n
    	Εσείς (ή κάποιος άλλος) ζητήσατε να δημιουργήσουμε για σας
    	έναν νέο κωδικό πρόσβασης, επειδή ξεχάσατε τον παλιό σας
    	κωδικό. \r\n\r\nΑκολουθήστε τον εξής σύνδεσμο για να αλλάξετε τον κωδικό σας:\r\n";
    	$msg .= $rabbit_settings[ 'webaddress' ];
        $msg .= "?p=chpasswd&uid=" . $user->Id() . "&oldpass=" . $user->Password();
        $msg .= "\r\n\r\nΕυχαριστούμε που χρησιμοποιείτε το " . $rabbit_settings[ 'applicationname' ] . "!!\r\n\r\nΗ Ομάδα του " . $rabbit_settings[ 'applicationname' ] . "\r\n\r\n
Για οποιοδήποτε πρόβλημα μην διστάσετε να επικοινωνήσετε μαζί μας :-)";

        $headers = "From: admin@chit-chat.gr";
    	
    	if ( mail( $to , $subject , $msg, $headers ) ) { //Attempt to send the mail
            return Redirect( '?p=lostpassword&sent=yes' );
    	}
        return Redirect( '?p=lostpassword&invalidmail=yes' );
    }
?>
