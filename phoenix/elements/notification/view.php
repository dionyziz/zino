<?php

	function ElementNotificationView( $notif ) {
		?><div class="event" onclick="Notification.Visit( '<?php
			if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
				ob_start();
				Element( 'url' , $notif->Item );
				echo htmlspecialchars( ob_get_clean() );
			}
			else {
				Element( 'user/url' , $notif->FromUser );
			}
			?>' , '<?php
			echo $notif->Event->Typeid;
			?>' );">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $notif->Since;
				?></span>
			</div>
			<div class="who"><?php
				Element( 'user/avatar' , $notif->FromUser , 100 , 'avatar' , '' , true , 50 , 50 );
				Element( 'user/name' , $notif->FromUser , false );
				?> έγραψε:
			</div>
			<div class="subject"><?php
				if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
					?><p><span class="text">"<?php
					$comment = $notif->Item;
					$text = $comment->GetText( 35 );
					echo utf8_substr( $text , 0 , 30 );
					if ( strlen( $text ) > 30 ) {
						?>...<?php
					}
					?>"</span>
					, <?php
					switch ( $comment->Typeid ) {
						case TYPE_POLL:
							?>στη δημοσκόπηση <?php
							echo htmlspecialchars( $comment->Item->Title );
							break;
						case TYPE_IMAGE:
							?>στη φωτογραφία <?php
							Element( 'image' , $comment->Item , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , $comment->Item->Name , '' , true , 75 , 75 );
							break;
						case TYPE_JOURNAL:
							?>στο ημερολόγιο <?php
							echo htmlspecialchars( $comment->Item->Title );
							break;
					}
					?></p>
					<div class="eof"></div><?php
				}
				else {
				
				}
				?><div class="eof"></div>
			</div>
		</div><?php
	}
?>
