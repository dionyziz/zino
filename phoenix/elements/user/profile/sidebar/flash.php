<?php
	class ElementUserProfileSidebarFlash extends Element {
		public function Render( $songid, User $theuser, $autoplay = false ) {
			global $libs;
			global $user;
			
			if ( $songid != -1 ){
				?><div class="player">
					<object>
						<param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param>
						<param name="wmode" value="opaque"></param>
						<param name="allowScriptAccess" value="always"></param>
						<param name="flashvars" value="hostname=cowbell.grooveshark.com&#38;songID=<?php
							echo $songid;
						?>&#38;style=metal&#38;p=<?php
							if( $autoplay ){
								echo 1;
							}
							else {
								echo 0;
							}
						?>"></param>
						<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&#38;songID=<?php
							echo $songid;
						?>&#38;style=metal&#38;p=<?php
							if( $autoplay ){
								echo 1;
							}
							else {
								echo 0;
							}
						?>" allowScriptAccess="always" wmode="opaque"></embed>
					</object><?php
					if ( $theuser->HasPermission( PERMISSION_SONG_EDIT ) ){
						?><div class="toolbox">
							<span class="s1_0007 delete" title="Διαγραφή τραγουδιού.">&#160;</span>
							<span class="search" title="Αλλαγή τραγουδιού.">&#160;</span>
						</div><?php
					}
				?></div><?php
			}
			else if ( $theuser->HasPermission( PERMISSION_SONG_CREATE ) ) {
				?><div class="addsong"><a href="">Πρόσθεσε κάποιο τραγούδι στο προφίλ σου.</a></div><?php
			}
		}
	}
?>
