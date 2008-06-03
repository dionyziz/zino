<?php
	
	function ElementFrontpageCommentView( $comment ) {
		?><div class="event">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $comment->Since;
				?></span>
			</div>
			<div class="who">
				<a href="<?php
				Element( 'user/url' , $comment->User );
				?>"><?php
					Element( 'user/avatar' , $comment->User , 100 , 'avatar' , '' , true , 50 , 50 );
					echo $comment->User->Name;
				?></a> έγραψε:
			</div>
			<div class="subject">
				<p><?php
				$text = $comment->GetText( 35 );
					?><span class="text">"<?php
					echo utf8_substr( $text , 0 , 30 );
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
						case TYPE_USERPROFILE:
							$temp = New User( $comment->Item->Userid );
							?>στο προφίλ <?php
							if ( $temp->Gender == 'f' ) {
								?>της <?php
							}
							else {
								?>του <?php
							}
							?><a href="<?php
							ob_start();
							Element( 'url' , $comment );
							echo htmlspecialchars( ob_get_clean() );
							?>" class="itempic"><?php
							Element( 'user/avatar' , $temp , IMAGE_CROPPED_100x100 );
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
					}?>
				</p>
				<a href="<?php
				ob_start();
				Element( 'url' , $comment );
				echo htmlspecialchars( ob_get_clean() );
				?>" class="viewcom">Προβολή σχολίου&raquo;</a>
			</div>
		</div><?php
	}
?>
