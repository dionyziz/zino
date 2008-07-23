<?php
	require_once('libs/rabbit/rabbit.php');

	Rabbit_Construct();

	global $libs;
	
	$libs->Load('sanitizer');

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
		return $text;
	}

	$test = 'http://localhost/index.php?p=comments&a=show <-- look here';

	function works($text) {
		return WYSIWYG_TextProcess($text);
	}

	function doesNotWork($text) {
		$sanitizer = New XHTMLSanitizer();
		$sanitizer->SetSource($text);
	    $sanitizer->SetTextProcessor('WYSIWYG_TextProcess');
		return $sanitizer->GetXHTML();
	}

    $w = works($test);
    $d = doesNotWork($test);
    echo "$w | $d <br></br>\n";

	Rabbit_Destruct();
?>

