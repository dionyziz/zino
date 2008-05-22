<?php
	
	function ElementCommentView( tInteger $commentid , tInteger $indent ) {
		global $water;
		global $user;
		global $libs;
		
		$libs->Load( 'comment' );
		
		$comment = New Comment( $commentid->Get() );
		$indent = $indent->Get();
		$water->Trace( 'comment typeid ' . $comment->Typeid );
		$water->Trace( 'comment itemid ' . $comment->Itemid );
		$water->Trace( 'comment parentid ' . $comment->Parentid );
		?><div id="comment_<?php
		echo $comment->Id;
		?>" class="comment" style="border-color: #dee;">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $comment->Since;
				?></span><?php
				if ( $user->Id == $comment->User->Id || $user->HasPermission( PERMISSION_COMMENT_DELETE_ALL ) ) {
					?><a href="" onclick="return false" title="Διαγραφή"></a><?php
				}
			?></div>
			<div class="who">
				<a href="user/smilemagic">
					<img src="http://static.zino.gr/phoenix/mockups/smilemagic.jpg" class="avatar" alt="SmilEMagiC" /><?php
					echo $comment->User->Name;
				?></a> είπε:
			</div>
			<div class="text"><?php
				echo $comment->Text;
			?></div><?php
			if ( $indent <= 50 && $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
				?><div class="bottom">
					<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
				</div><?php
			}
		?></div><?php
	}
?>
