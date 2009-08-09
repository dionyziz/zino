<?php
	function UnitErrorhandler( tText $msg, tText $url, tInteger $linenumber ){
		global $user;
		$to = "thsourg@gmail.com";
		if( $user->Exists() ){
			$subject = "[Zino-jsDebug]Error from user " . $user->Name;
		}
		else{
			$subject = "[Zino-jsDebug]Error from a user";
		}
		$text = " Error on global.js, line $linenumber.

Page: $url.

Message: $msg.";
		mail( $to, $subject, $text );
	}
?>