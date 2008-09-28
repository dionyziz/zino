<?php
	function ValidId( $x ) {
		if ( is_numeric( $x ) ) {
			$z = intval( $x );
			return ( ( $z == $x ) && ( $z > 0 ) );
		}
		return false;
	}

	function ValidURL( $url ) {
		$pattern = "/^(http|https)\:\/\/[a-zA-Z0-9_\/\?\=\-%\:\.#&]*$/";
		return ( bool )preg_match( $pattern, $url );
	}

	function ValidEmail( $email ) {
		// Partially incorrect:
		// * Will allow some invalid domain names such as domains containing same-label siblings.
		// * Won't allow the ' character in usernames, which can be valid
		//
		// If you need absolutely correct validation, use your own manual string manipulation
		//

		return ( bool )preg_match( '/^			  # start of string
			[a-z0-9%_+.-]+						  # username (can contain any of a-z, 0-9, and the symbols %, _, +, . and -
			@									   # @ symbol at the middle of the e-mail address
			(									   # after-@-symbol part; can be either a domain name...
				(								   # domain node (parts between the dots)
					[a-z0-9]						# must start with a letter or number (not a dash)
					[a-z0-9-]{0,62}				 # must be at most 63 characters long, and at least 1
					(?<!-)						  # cannot end in a dash
					\.							  # each part is separated from the next with a dot						
				)*								  # can have any number of domain nodes (even zero if this is a
													# top-level domain such as in admin@edu)
				(								   # top-level domain
					[a-z]{2,4}					  # country domain such as .gr, .de, .nl;
													# special cases such as .aero, .com, .edu;
					|museum						 # and the special "museum" case 
				)
			|									   # ...or an IP address
				(([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))\.){3} # (0-255).(0-255).(0-255).
				([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))		# (0-255)
				(?<!0\.0\.0\.0)											  # ...but it cannot be 0.0.0.0
			)
			$									   # end of string
									/ix', $email );
	}
?>
