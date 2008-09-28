<?php
	// TODO: remove this file, replace with WYSIWYG + XHTML/XML validation
	
	function smileys( $text ) {		
		static $smileys = array( 
					  ":D" => "teeth" ,
					  ":-)" => "smile" ,
					  ":)" => "smile" ,
					  ";-)" => "wink" , 
					  // ";)" => "wink" ,
					  ":P" => "tongue",
					  ":p" => "tongue",
					  ":-P" => "tongue",
					  ":-p" => "tongue",
					  ":-D" => "teeth" ,
					  ":-S" => "confused" ,
					  ":S" => "confused" ,
					  ":'(" => "cry" , 
					  ":angel:" => "innocent" ,
					  ":angry:" => "angry" , 
					  ":bat:" => "bat" ,
					  ":beer:" => "beer" ,
					  ":cake:" => "cake" ,
					  ":photo:" => "camera" ,
					  ":cat:" => "cat" ,
					  ":clock:" => "clock" ,
					  ":drink:" => "cocktail" ,
					  ":cafe:" => "cup" ,
					  ":666:" => "devil" ,
					  ":evil:" => "devil" ,
					  ":dog:" => "dog" ,
					  ":mail:" => "email" ,
					  ":email:" => "email" ,
					  ":e-mail:" => "email" ,
					  "^^Uu" => "embarassed" ,
					  ":film:" => "film" ,
					  ":smooch:" => "kiss" ,
					  ":idea:" => "lightbulb" ,
					  "LOL" => "lol" ,
					  ":phone:" => "phone" ,
					  ":cool:" => "shade" ,
					  ":no:" => "thumbs_down" ,
					  ":yes:" => "thumbs_up" ,
					  ":yuck:" => "tongue" ,
					  ":heartbroken:" => "unlove" ,
					  ":unlove:" => "unlove" ,
					  ":hate:" => "unlove" ,
					  ":rose:" => "wilted_rose" ,
					  ":star:" => "star" ,
					  ":X" => "uptight" ,
					  ":gift:" => "present" ,
					  ":present:" => "present" ,
					  ":love:" => "love" ,
					  ":heart:" => "love" ,
					  ":music:" => "note" ,
					  ":note:" => "note" ,
					  ":airplane:" => "airplane" , 
					  ":boy:" => "boy" ,
					  ":car:" => "car" ,
					  ":smoke:" => "cigarette" ,
					  ":computer:" => "computer" , 
					  ":girl:" => "girl" ,
					  ":-I" => "indifferent" ,
					  ":-|" => "indifferent" ,
					  ":island:" => "ip" ,
					  ":!!:" => "lightning" ,
					  ":sms:" => "mobile_phone" ,
					  ":wow:" => "omg" ,
					  ":-(" => "sad" ,
					  ":sheep:" => "sheep" ,
					  ":@:" => "snail" ,
					  ":ball:" => "soccer" , 
					  ":kaboom:" => "storm" ,
					  ":sun:" => "sun" ,
					  ":turtle:" => "turtle" ,
					  ":?:" => "thinking" ,
					  ":umbrella:" => "umbrella" ,
					  ":~:" => "ugly" ,
					  ":::" => "empty" );
		static $smileysprocessed = false;
		static $smileysprocessedkeys = false;
		global $xc_settings;
		global $water;
		
		if ( $smileysprocessed === false ) {
			foreach ( $smileys as $i => $smiley ) {
				$smileysprocessed[ $i ] = '<img src="' 
										. $xc_settings[ 'staticimagesurl' ] 
										. 'emoticons/' 
										. $smiley 
										. '.png" alt="' 
										. htmlspecialchars( $i ) 
										. '" title="' 
										. htmlspecialchars( ucfirst( $smiley ) ) 
										. '" class="emoticon" style="width:22px;height:22px;" />';
			}
			$smileysprocessedkeys = array_keys( $smileysprocessed );
		}
		
		return str_replace( $smileysprocessedkeys, $smileysprocessed, $text );
	}
?>
