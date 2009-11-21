<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser ) {
	//	if( $theuser->Profile->Songwidgetid != -1 ){
			?>
			<object width="300" height="40"> 
				<param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param> 
				<param name="wmode" value="window"></param> 
				<param name="allowScriptAccess" value="always"></param> 
				<param name="flashvars" value="hostname=cowbell.grooveshark.com&widgetID=16646135&style=metal&p=0"></param> 
				<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" width="300" height="40" flashvars="hostname=cowbell.grooveshark.com&widgetID=16646135&style=metal&p=0" allowScriptAccess="always" wmode="window">
				</embed>
			</object><?php
	//	}
	}
    }
?>

