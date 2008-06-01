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
					Element( 'user/avatar' , 100 , 'avatar' );
					echo $comment->User->Name;
				?></a> έγραψε:
			</div>
			<div class="subject">
				<p>
					<span class="text">"eleos mori skatoulitsa"</span>
					, <?php
					switch ( $comment->Typeid ) {
						case TYPE_POLL:
							?>στη δημοσκόπηση <a href="<?php
							Element( 'url' , $comment );
							?>"><?php
							echo htmlspecialchars( $comment->Item->Title );
							?></a><?php
							break;
						case TYPE_IMAGE:
							?>στην εικόνα <a href="<?php
							Element( 'url' , $comment );
							?>" class="itempic"><?php
							Element( 'image' , $comment->Item , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , $comment->Item->Name , '' );
							?></a><?php
							break;
						case TYPE_USERPROFILE:
							?>στο προφίλ <?php
							if ( $comment->Item->Gender == 'f' ) {
								?>της <?php
							}
							else {
								?>του <?php
							}
							?><a href="<?php
							Element( 'url' , $comment );
							?>" class="itempic"><?php
							Element( 'user/avatar' , $comment->Item , IMAGE_CROPPED_100x100 );
							?></a><?php
							break;
						case TYPE_JOURNAL:
							?>στο ημερολόγιο <a href="<?php
							Element( 'url' , $comment );
							?>"><?php
							echo htmlspecialchars( $comment->Item->Title );
							?></a><?php
							break;
					}?>
				</p>
				<a href="#" class="viewcom">Προβολή σχολίου&raquo;</a>
			</div>
		</div><?php
	}
?>
