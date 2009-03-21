<?php
	function ActionUserRevalidate( tInteger $userid ) {
		global $user;
		
		$userid = $userid->Get();
		$user = New $user( $userid );
		
		ob_start();
		$link = $user->Profile->ChangedEmail( '', $user->Name );
		echo $link;
		$subject = Element( '/email/validate', $link );
		echo 'here';
		$text = ob_get_clean();
		Email( $user->Name, $user->Profile->Email, $subject, $text, "Zino", "noreply@zino.gr" );
		
    return Redirect( '?p=revalidate' );
}
?>