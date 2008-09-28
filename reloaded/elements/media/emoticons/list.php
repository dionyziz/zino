<?php

	function ElementMediaEmoticonsList() {
		global $page;
		
		$page->SetTitle( 'Χαμόγελα' );
		$page->AttachStyleSheet( 'css/emoticons.css' );
		
		// keep smileys in this order ;-)
		static $smileys = array( 
			":D" 			=> "teeth" ,
			":-)" 			=> "smile" ,
			":)" 			=> "smile" ,
			";-)" 			=> "wink" , 
			";)" 			=> "wink" ,
			":-D" 			=> "teeth" ,
			":-S" 			=> "confused" ,
			":S" 			=> "confused" ,
			":'(" 			=> "cry" , 
			":angel:" 		=> "innocent" ,
			":angry:"		=> "angry" , 
			":bat:" 		=> "bat" ,
			":beer:" 		=> "beer" ,
			":cake:" 		=> "cake" ,
			":photo:" 		=> "camera" ,
			":cat:" 		=> "cat" ,
			":clock:" 		=> "clock" ,
			":drink:"		=> "cocktail" ,
			":cafe:" 		=> "cup" ,
			":666:" 		=> "devil" ,
			":evil:" 		=> "devil" ,
			":dog:" 		=> "dog" ,
			":mail:"		=> "email" ,
			":email:" 		=> "email" ,
			":e-mail:" 		=> "email" ,
			"^^Uu" 			=> "embarassed" ,
			":film:"	 	=> "film" ,
			":smooch:" 		=> "kiss" ,
			":idea:" 		=> "lightbulb" ,
			"LOL" 			=> "lol" ,
			":phone:" 		=> "phone" ,
			":cool:" 		=> "shade" ,
			":no:" 			=> "thumbs_down" ,
			":yes:" 		=> "thumbs_up" ,
			":yuck:" 		=> "tongue" ,
			":heartbroken:" => "unlove" ,
			":unlove:" 		=> "unlove" ,
			":hate:" 		=> "unlove" ,
			":rose:" 		=> "wilted_rose" ,
			":star:" 		=> "star" ,
			":X" 			=> "uptight" ,
			":gift:" 		=> "present" ,
			":present:" 	=> "present" ,
			":love:" 		=> "love" ,
			":heart:" 		=> "love" ,
			":music:" 		=> "note" ,
			":note:" 		=> "note" ,
			":airplane:" 	=> "airplane" , 
			":boy:" 		=> "boy" ,
			":car:" 		=> "car" ,
			":smoke:" 		=> "cigarette" ,
			":computer:" 	=> "computer" , 
			":girl:" 		=> "girl" ,
			":-I" 			=> "indifferent" ,
			":-|" 			=> "indifferent" ,
			":island:" 		=> "ip" ,
			":!!:" 			=> "lightning" ,
			":sms:" 		=> "mobile_phone" ,
			":wow:" 		=> "omg" ,
			":-(" 			=> "sad" ,
			":sheep:" 		=> "sheep" ,
			":@:" 			=> "snail" ,
			":ball:" 		=> "soccer" , 
			":kaboom:" 		=> "storm" ,
			":sun:" 		=> "sun" ,
			":turtle:" 		=> "turtle" ,
			":?:" 			=> "thinking" ,
			":umbrella:" 	=> "umbrella" ,
			":~:" 			=> "ugly" ,
			":::" 			=> "empty" 
		);				 
		?><br /><br />
		<br /><br />
		<h1 style="padding-left:60px;">Χαμόγελα</h1><br />
		<div class="content">
			<div class="allsmilies"><?php
			$i = 0;
			foreach ( $smileys as $text => $image ) {
				if ( $i <= 7 ) {
					$ext = ".gif";
				}
				else {
					$ext = ".png";
				}
				?>
				<div class="outersmiley">
					<div class="thesmiley">
						<span class="smileyicon">
							<img src="images/emoticons/<?php 
							echo $image.$ext;
							?>" alt="<?php
							echo $text;
							?>" title="<?php
							echo $text;
							?>" />
						</span>
						<span class="smileytext"><?php
						echo $text;
						?></span>
					</div>
				</div><?php
				++$i;
			}
		?></div>
		</div><?php
	}

?>