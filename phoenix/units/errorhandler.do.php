<?php
	function UnitErrorhandler( tText $msg, tText $url, tInteger $linenumber ){
		global $libs;
		$libs->Load( 'rabbit/helpers/email' );
		global $user;
		$toname = "team";
		$toemail = "thsourg@gmail.com";
		if( $user->Exists() ){
			$subject = "[Zino-jsDebug]Error from user " . $user->Name;
			$fromname = $user->Name;
			$fromemail = "debug@zino.gr";
		}
		else{
			$subject = "[Zino-jsDebug]Error from a user";
			$fromname = "guest";
			$fromemail = "debug@zino.gr";
		}
		$message = " Error on global.js, line $linenumber.

Page: $url.

Message: $msg.";
		Email( $toname, $toemail, $subject, $message, $fromname, $fromemail );
	}
?>