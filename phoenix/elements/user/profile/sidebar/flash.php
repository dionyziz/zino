<?php
	class ElementUserProfileSidebarFlash extends Element {
		public function Render( User $theuser, $autoplay = false ) {
			global $libs;
			global $user;
			
			if ( $theuser->Profile->Songid != -1 ){
				?><div class="player">
					<object>
						<param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param>
						<param name="wmode" value="opaque"></param>
						<param name="allowScriptAccess" value="always"></param>
						<param name="flashvars" value="hostname=cowbell.grooveshark.com&#38;songID=<?php
							echo $theuser->Profile->Songid;
						?>&#38;style=metal&#38;p=<?php
							if( $autoplay ){
								echo 1;
							}
							else {
								echo 0;
							}
						?>"></param>
						<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&#38;songID=<?php
							echo $theuser->Profile->Songid;
						?>&#38;style=metal&#38;p=<?php
							if( $autoplay || $user->Name == "dionyziz" ){
								echo 1;
							}
							else {
								echo 0;
							}
						?>" allowScriptAccess="always" wmode="opaque"></embed>
					</object>
					<div class="toolbox">
						<span class="s1_0007 delete" title="Διαγραφή τραγουδιού.">&#160;</span>
						<span class="search" title="Αλλαγή τραγουδιού.">&#160;</span>
					</div>
				</div><?php
			}
			else{
				?><div class="addsong"><a href="">Πρόσθεσε κάποιο τραγούδι στο προφίλ σου.</a></div><?php
			}
		}
	}
?>
