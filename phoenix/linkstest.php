<?php
	require_once('libs/rabbit/rabbit.php');

	Rabbit_Construct();

	global $libs;
	
	$libs->Load('sanitizer');

	function WYSIWYG_PreProcess($html) {
		global $rabbit_settings;

		$html = preg_replace(
			'#\<object [^>]++\>\s*\<param [^>]*?value\="http\://www\.youtube\.com/v/([a-zA-Z0-9_-]+)"[^>]*+\>.*?\</object\>#i',
			'<img src="' . $rabbit_settings['imagesurl'] . 'video-placeholder.png?v=$1" />',
			$html
		);
		$html = preg_replace(
			'#\<embed\s*+src\="http\://www\.veoh\.com\/videodetails2\.swf\?permalinkId\=([a-zA-Z0-9_\-]+)[^"]++"[^>]++\>\</embed\>#i',
			'<img src="' . $rabbit_settings['imagesurl'] . 'video-placeholder.png?w=$1" />',
			$html
		);

		return $html;
	}

	function WYSIWYG_PostProcess($html) {
		global $xhtmlsanitizer_goodtags, $rabbit_settings;

		$html = str_replace('&nbsp;', ' ', $html);

		$sanitizer = New XHTMLSanitizer();

		foreach ($xhtmlsanitizer_goodtags as $tag => $attributes) {
			if ($tag == '') {
				continue;
			}

			$goodtag = New XHTMLSaneTag($tag);
			if (is_array($attributes)) {
				foreach ($attributes as $attribute => $true) {
					$goodtag->AllowAttribute(New XHTMLSaneAttribute($attribute));
				}
			}
			foreach ($xhtmlsanitizer_goodtags[''] as $attribute => $true) {
				$goodtag->AllowAttribute(New XHTMLSaneAttribute($attribute));
			}
			$sanitizer->AllowTag($goodtag);
		}
		$sanitizer->SetSource($html);
		$sanitizer->SetTextProcessor('WYSIWYG_TextProcess');
		$html = $sanitizer->GetXHTML();
		
		$html = preg_replace(
		   '#\<img[^>]*?src\=(["\']?)' 
			. preg_quote($rabbit_settings['imagesurl'], "#i")
			. 'video-placeholder\.png\?v\=([a-zA-Z0-9_-]+)\1[^>]*/?\>#i',
			'<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/\2"></param><embed src="http://www.youtube.com/v/\2" type="application/x-shockwave-flash" width="425" height="344"></embed></object>', 
			$html
		);
		
		$html = preg_replace(
			'#\<img[^>]*?src\=(["\']?)'
			. preg_quote($rabbit_settings['imagesurl'], '#i')
			. 'video-placeholder\.png\?w\=([a-zA-Z0-9_-]+)\1[^>]*/?\>#i',
			'<embed src="http://www.veoh.com/videodetails2.swf?permalinkId=\2&amp;id=anonymous&amp;player=videodetailsembedded&amp;videoAutoPlay=0" allowFullScreen="true" width="540" height="438" bgcolor="#000000" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>',
			$html
		);
   
		return $html;
	}

	function WYSIWYG_Links($text) {
		$text = preg_replace(
			'#\b(https?\://[a-z0-9.-]+(/[a-zA-Z0-9./+?;&=%-]*)?)#',
			'<a href="\1">\1</a>',
			$text
		);
		return $text;
	}

	function WYSIWYG_TextProcess($text) {
		$text = htmlspecialchars($text);
		$text = WYSIWYG_Links($text);
		$text = WYSIWYG_Smileys($text);
		return $text;
	}

	function WYSIWYG_Smileys($text) {
		static $smileys = array(
			":D" => "teeth",
			":-)" => "smile",
			":)" => "smile",
			":P" => "tongue",
			":p" => "tongue",
			":-P" => "tongue",
			":-p" => "tongue",
			":-D" => "teeth",
			":-S" => "confused",
			":S" => "confused",
			":'(" => "cry", 
			":angel:" => "innocent",
			":angry:" => "angry", 
			":bat:" => "bat",
			":beer:" => "beer",
			":cake:" => "cake",
			":photo:" => "camera",
			":cat:" => "cat",
			":clock:" => "clock",
			":drink:" => "cocktail",
			":cafe:" => "cup",
			":666:" => "devil",
			":evil:" => "devil",
			":dog:" => "dog",
			":mail:" => "email",
			":email:" => "email",
			":e-mail:" => "email",
			"^^Uu" => "embarassed",
			":film:" => "film",
			":smooch:" => "kiss",
			":idea:" => "lightbulb",
			"LOL" => "lol",
			":phone:" => "phone",
			":cool:" => "shade",
			":no:" => "thumbs_down",
			":yes:" => "thumbs_up",
			":yuck:" => "tongue",
			":heartbroken:" => "unlove",
			":unlove:" => "unlove",
			":hate:" => "unlove",
			":rose:" => "wilted_rose",
			":star:" => "star",
			":X" => "uptight",
			":gift:" => "present",
			":present:" => "present",
			":love:" => "love",
			":heart:" => "love",
			":music:" => "note",
			":note:" => "note",
			":airplane:" => "airplane", 
			":boy:" => "boy",
			":car:" => "car",
			":smoke:" => "cigarette",
			":computer:" => "computer", 
			":girl:" => "girl",
			":-I" => "indifferent",
			":-|" => "indifferent",
			":island:" => "ip",
			":!!:" => "lightning",
			":sms:" => "mobile_phone",
			":wow:" => "omg",
			":-(" => "sad",
			":sheep:" => "sheep",
			":@:" => "snail",
			":ball:" => "soccer", 
			":kaboom:" => "storm",
			":sun:" => "sun",
			":turtle:" => "turtle",
			":?:" => "thinking",
			":umbrella:" => "umbrella",
			":~:" => "ugly",
			":::" => "empty"
		);
		static $smileysprocessed = false;
		static $smileysprocessedkeys = false;
		global $xc_settings;

		if ($smileysprocessed === false) {
			foreach ($smileys as $i => $smiley) {
				$smileysprocessed[$i] = '<img src=\'' 
							. $xc_settings['staticimagesurl'] 
							. 'emoticons/' 
							. $smiley 
							. '.png\' alt=\'' 
							. htmlspecialchars($i) 
							. '\' title=\'' 
							. htmlspecialchars($i) 
							. '\' class=\'emoticon\' width=\'22\' height=\'22\' />';
			}
			$smileysprocessedkeys = array_keys($smileysprocessed);
		}

		$text = str_replace($smileysprocessedkeys, $smileysprocessed, $text);
		$text = preg_replace(
			'#(^|\s);-?\)(\s|$)#',
			'<img src=\''
			. $xc_settings['staticimagesurl'] 
			. 'emoticons/wink.png\' alt=\';-)\' title=\';-)\' class=\'emoticon\' width=\'22\' height=\'22\' />',
			$text
		);
		return $text;
	}

	$tests = array(
		'http://www.google.com/',
		'Hello https://python.org/ !',
		'http://localhost/index.php?p=comments&a=show <-- look here',
		'OK https://foo.bar.gr/wiki.php?a=true&s=false ... htts://mistake.org/ http:/another.net/index.php ...'
	);

	function works($text) {
		return $WYSIWYG_TextProcess($text);
	}

	function doesNotWork($text) {
		$sanitizer = New XHTMLSanitizer();
		$sanitizer->SetSource($text);
		$sanitizer->SetTextProcessor('WYSIWYG_TextProcess');
		return $sanitizer->GetXHTML();
	}

	foreach ($tests as $t) {
		$w = works($t);
		$d = doesNotWork($t);
		echo "$w | $d <br></br>\n";
	}

	Rabbit_Destruct();
?>

