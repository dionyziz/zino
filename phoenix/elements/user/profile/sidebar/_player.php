<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
		?><div class="mplayer"><?php
		if( $theuser->Profile->Songwidgetid != -1 ){	
			?>
			<object width="300" height="40"> 
				<param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param> 
				<param name="wmode" value="window"></param> 
				<param name="allowScriptAccess" value="always"></param> 
				<param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;idgetID=<?php
					echo $theuser->Profile->Songwidgetid;
				?>&amp;style=metal&amp;p=0"></param> 
				<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" width="300" height="40" flashvars="hostname=cowbell.grooveshark.com&amp;widgetID=<?php
					echo $theuser->Profile->Songwidgetid;
				?>&amp;style=metal&amp;p=0" allowScriptAccess="always" wmode="window">
				</embed>
			</object><?php
		}
		?></div><?php
	}
    }
?>
