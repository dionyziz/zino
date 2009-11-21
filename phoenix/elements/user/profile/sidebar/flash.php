<?php
	class ElementUserProfileSidebarFlash extends Element{
		public function Render( $widgetid ){
			global $libs;
			$libs->Load( 'music/grooveshark' );
			if( $widgetid != -1 ){
				?><div class="player">
					<object>
						<param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param> 
						<param name="wmode" value="opaque"></param> 
						<param name="allowScriptAccess" value="always"></param> 
						<param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;widgetID=<?php
							echo $widgetid;
						?>&amp;style=metal&amp;p=0"></param> 
						<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" width="300" height="40" flashvars="hostname=cowbell.grooveshark.com&amp;widgetID=<?php
							echo $widgetid;
						?>&amp;style=metal&amp;p=0" allowScriptAccess="always" wmode="opaque">
						</embed>
					</object>
					<div class="toolbox">
						<span class="s1_0024 search">&nbsp;</span>
						<span class="s1_0007 delete">&nbsp;</span>
					</div>
				</div><?php
			}
			else{
				?><div class="addsong">
					<a href="">Πρόσθεσε κάποιο τραγούδι στο προφίλ σου.</a>
				</div>
				<?php
			}
		}
	}
?>
