<?php
	class ElementUserProfileSidebarFlash extends Element {
		public function Render( $widgetid, User $theuser ) {
			global $libs;
			global $user;
			
			$libs->Load( 'music/grooveshark' );
			
			if ( $widgetid != -1 ){
				?><div class="player">
					<object>
						<param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param>
						<param name="wmode" value="opaque"></param>
						<param name="allowScriptAccess" value="always"></param>
						<param name="flashvars" value="hostname=cowbell.grooveshark.com&#38;widgetID=<?php
							echo $widgetid;
						?>&#38;style=metal&#38;p=0"></param>
						<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&#38;widgetID=<?php
							echo $widgetid;
						?>&#38;style=metal&#38;p=0" allowScriptAccess="always" wmode="opaque"></embed>
					</object><?php
					if( $user->HasPermission( 60 ) ){
						?><div class="toolbox">
							<span class="s1_0007 delete" title="Διαγραφή τραγουδιού.">&#160;</span>
							<span class="search" title="Αλλαγή τραγουδιού.">&#160;</span>
						</div><?php
					}
				?></div><?php
			}
			else if ( $theuser->HasPermission( 60 ) ) {
				?><div class="addsong"><a href="">Πρόσθεσε κάποιο τραγούδι στο προφίλ σου.</a></div><?php
			}
		}
	}
?>
