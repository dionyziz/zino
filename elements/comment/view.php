<?php
	function ElementCommentView( $comment , $indent , $haschildren = false ) {
		global $water;
		global $user;
		
		$theuser = $comment->User();
		$allowreply = $indent < 50;
        
		?><div id="comment_<?php
		echo $comment->Id();
		?>" class="comment" style="margin-left:<?php
		echo 10 * $indent;
		?>px">
			<div class="upperline">
				<div class="leftcorner">&nbsp;</div>
				<div class="title"><?php
				if ( $theuser->IsAnonymous() ) {
					?>ανώνυμος<?php
				}
				else {
					Element( 'user/static' , $theuser );
				}
				?>, πριν <?php
				echo $comment->Since();
				
				if ( $user->CanModifyCategories() ) {
					?>&nbsp;&nbsp;<span style="opacity: 0.7"><?php
					echo $comment->Ip();
					?></span>
					&nbsp;<span style="opacity: 0.5"><small><?php
					echo $comment->Id();
					?></small></span><?php
				}
				
				?></div>
				<div class="fade">&nbsp;</div>
				<div class="rightcorner">&nbsp;</div>
				<div class="filler">&nbsp;</div>
			</div>
			<div class="avatar"><?php
				Element( 'user/icon' , $theuser );
			?></div>
			<div class="text">
				<div id="comment_text_<?php echo $comment->Id(); ?>"><?php
					echo $comment->Text();
					?><br /><br /><br /><div class="sig"><?php
					
					echo htmlspecialchars( $theuser->Signature() );
				
				?><br /><br /></div></div>
			</div>
			<div class="lowerline">
				<div class="leftcorner">&nbsp;</div>
				<div class="rightcorner">&nbsp;</div>
				<div class="middle">&nbsp;</div>
				<div class="toolbar">
					<ul id="comment_<?php
					echo $comment->Id(); 
					?>_toolbar"><?php
                        if ( $allowreply && !( $user->IsAnonymous() && !$xc_settings[ 'anonymouscomments' ] ) ) {
    						?><li><a onclick="Comments.Reply( <?php 
                            echo $comment->Id(); 
                            ?>, <?php 
                            echo $indent; 
                            ?> ); return false;">Απάντηση</a></li><?php
                        }
						if ( $user->CanModifyCategories() || ( $user->Exists() && $user->Id() == $theuser->Id() && daysDistance($comment->SQLDate() ) < 1 ) ) { 
							?><li><a style="cursor: pointer;" onclick="Comments.Edit( <?php 
                            echo $comment->Id(); 
                            ?> ); return false;">Επεξεργασία</a></li><?php
							if ( !$haschildren ) {
                                ?><li><a style="cursor:pointer" onclick="Comments.Delete( <?php 
                                echo $comment->Id(); 
                                ?> ); return false;">Διαγραφή</a></li><?php
							}
						}
						if ( $theuser->IsAnonymous() && !$haschildren && $user->CanModifyCategories() ) {
							?><li><a style="cursor:pointer" onclick="Comments.MarkAsSpam( <?php
							echo $comment->Id();
							?> ); return false;">Spam</a></li><?php
						}
						
					?></ul>
					<ul id="comment_edit_<?php 
                        echo $comment->Id(); 
                        ?>_toolbar" style="display: none">
						<li><a style="cursor: pointer;" onclick="Comments.checkEmpty( <?php
						echo $comment->Id();
						?> );">Επεξεργασία!</a></li>
						<li><a style="cursor: pointer;" onclick="Comments.cancelEdit( <?php 
                        echo $comment->Id(); 
                        ?> ); return false;">Ακύρωση</a></li>
					</ul>
				</div>
				
			</div>
		</div><?php
	}
	
?>
