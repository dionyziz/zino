<?php
	function UnitErrorhandler( tText $msg, tText $url, tInteger $linenumber ){
		$libs->Load( 'rabbit/helpers/email' );
		global $user;
		$toname = "team";
		$toemail = "thsourg@gmail.com";
		if( $user->Exists() ){
			$subject = "[Zino-jsDebug]Error from user " . $user->Name;
			$fromname = $user->Name;
			$fromemail = $user->Profile->Email;
		}
		else{
			$subject = "[Zino-jsDebug]Error from a user";
			$fromname = "guest";
			$fromemail = "";
		}
		$message = " Error on global.js, line $linenumber.

Page: $url.

Message: $msg.";
		Email( $toname, $toemail, $subject, $message, $fromname, $fromemail );
	}
?>