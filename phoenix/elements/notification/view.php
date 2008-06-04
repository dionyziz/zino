<?php

	function ElementNotificationView( $notif ) {
		?><div class="event">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $notif->Since;
				?></span>
			</div>
			<div class="who"><?php
				Element( 'user/avatar' , $notif->FromUser , 100 , 'avatar' , '' , true , 75 , 75 );
				Element( 'user/name' , $notif->FromUser , false );
				?> έγραψε:
			</div>
			<div class="subject"><?php
				if ( $notif->Event->Typeid != EVENT_USERRELATION_CREATED ) {
					?><p><span class="text"><?php
					$text = $comment->GetText( 35 );
					echo utf8_substr( $text , 0 , 30 );
					$comment = $notif->Item;
					if ( strlen( $text ) > 30 ) {
						?>...<?php
					}
					?>"</span>
					, <?php
					switch ( $comment->Typeid ) {
						case TYPE_POLL:
							?>στη δημοσκόπηση <a href="<?php
							ob_start();
							Element( 'url' , $comment );
							echo htmlspecialchars( ob_get_clean() );
							?>"><?php
							echo htmlspecialchars( $comment->Item->Title );
							?></a><?php
							break;
						case TYPE_IMAGE:
							?>στη φωτογραφία <a href="<?php
							ob_start();
							Element( 'url' , $comment );
							echo htmlspecialchars( ob_get_clean() );
							?>" class="itempic"><?php
							Element( 'image' , $comment->Item , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , $comment->Item->Name , '' );
							?></a><?php
							break;
						case TYPE_JOURNAL:
							?>στο ημερολόγιο <a href="<?php
							ob_start();
							Element( 'url' , $comment );
							echo htmlspecialchars( ob_get_clean() );
							?>"><?php
							echo htmlspecialchars( $comment->Item->Title );
							?></a><?php
							break;
					}
					?></p><?php
				}
				else {
				
				}
				?><div class="eof"></div>
			</div>
		</div><?php
	}
?>
