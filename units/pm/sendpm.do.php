<?php
function UnitPmSendpm( tString $usernames , tString $pmtext ) {
	global $user;
	global $libs;

	$libs->Load( 'pm' );
	$usernames = $usernames->Get();
	$pmtext = $pmtext->Get();
	
	$test = explode( ' ' , $usernames );
	$userreceivers = User_ByUsername( $test );
	$pm = new PM();
	$pm->SenderId = $user->Id();
	$pm->Text = $pmtext;
	foreach ( $userreceivers as $receiver ) {	
		$pm->AddReceiver( $receiver );
	}
	$pm->Save();
	?>pms.NewMessage( '' , '' );<?php
}
?>