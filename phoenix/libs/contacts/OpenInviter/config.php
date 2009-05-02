<?php
	$openinviter_settings=array(
		"username"=>"pagio911",
		"private_key"=>"65c8bd7f7185b51ddfa8fcef77507be4",
		"cookie_path"=>'/tmp',
		"message_body"=>"You are invited to www.pagio.com", // www.pagio.com is the website on your account. If wrong, please update your account at OpenInviter.com
		"message_subject"=>" is inviting you to www.pagio.com", // www.pagio.com is the website on your account. If wrong, please update your account at OpenInviter.com
		"transport"=>"curl", //Replace "curl" with "wget" if you would like to use wget instead
		"local_debug"=>"on_error", //Available options: on_error => log only requests containing errors; always => log all requests; false => don`t log anything
		"remote_debug"=>FALSE //When set to TRUE OpenInviter sends debug information to our servers. Set it to FALSE to disable this feature
	);
	?>