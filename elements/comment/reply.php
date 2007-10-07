<?php
	function ElementCommentReply( $item, $typeid ) {
		global $page;
		global $water;
		global $user;
		global $xc_settings;
		
		$page->AttachStyleSheet( 'css/comment.css' ); 
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/comments.js' );
		
		if ( ( $user->IsAnonymous() && !$xc_settings[ 'anonymouscomments' ] ) || $xc_settings[ 'readonly' ] > $user->Rights() ) {
			return;
		}
		
		?><form id="comment_new" onsubmit="Comments.Wait( false, 0 );Coala.Warm( 'comments/new', { 'text' : this.getElementsByTagName( 'textarea' )[ 0 ].value, 'parent' : 0, 'compage' : <?php
				echo $item->Id();
			?>, 'type' : <?php
				echo $typeid;
			?>, 'indent' : 0, 'callback' : Comments.NewCommentCallback } );return false;"> 
			<div class="comment">
				<div class="upperline">
					<div class="leftcorner">&nbsp;</div>
					<div class="title"><?php
					Element( 'user/static' , $user ); 
					?></div>
					<div class="fade">&nbsp;</div>
					<div class="rightcorner">&nbsp;</div>
					<div class="filler">&nbsp;</div>
				</div>
				<div class="avatar"><?php
					Element( 'user/icon' , $user );
				?></div>
				<div class="text" style="padding-left: 10px; _z-index: 1;">				
					<div style="margin: 0px; padding: 0px; text-align: right; float: right; margin-right: 35px;"><?php
					Element( 'media/emoticons/link' );
					?></div>
					<textarea style="width:95%; height: 100px; font-family: verdana; font-size: 98%;" cols="120" rows="30" name="text" id="comment_new_textarea"></textarea>
					<input type="hidden" id="compage" name="compage" value="<?php echo $item->Id(); ?>" />
					<input type="hidden" id="type" name="type" value="<?php echo $typeid; ?>" /><br />
					<a href="?p=faqc&amp;id=9" style="display: inline;">
						<img src="<?php
							echo $xc_settings[ 'staticimagesurl' ];
						?>icons/help.png" alt="Πληροφορίες για τα σχόλια" style="width: 16px; height: 16px; opacity: 0.5;" onmouseover="this.style.opacity=1;g( 'commenthelp' ).style.visibility='visible';" onmouseout="this.style.opacity=0.5;g( 'commenthelp' ).style.visibility='hidden';" />
					</a>
					<div id="commenthelp" style="visibility: hidden; display: inline;font-size: 80%;">Πληροφορίες για τα σχόλια</div>
				</div>	
				<div class="lowerline">
					<div class="leftcorner">&nbsp;</div>
					<div class="rightcorner">&nbsp;</div>
					<div class="middle">&nbsp;</div>
					<div class="toolbar">
						<ul style="_z-index:2">
							<li style="display: none;"><a onclick="Comments.cancelReply( this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode )">Ακύρωση</a></li>
							<li><a onclick="(function(kati) {
			if( kati.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.elements[0].value != '' ) {
				kati.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.onsubmit();
			}
			else {
				alert( 'Δεν μπορείς να δημοσιεύσεις κενό σχόλιο' );
			}
		})(this);">Σχολίασε!</a></li>
						</ul>
					</div>
				</div>
			</div>
		</form><div></div><?php // Don't remove the dumb divs.Without them the first comments won't work correctly in IE
	}
?>
