<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser ) {
		if( $theuser->Profile->Songwidgetid != -1 ){
			echo $theuser->Profile->Songwidgetid;
		}
	}
    }
?>

