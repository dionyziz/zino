<?php    
	class ElementSearchImCredentials extends Element {
        public function Render( $im ) {
			global $rabbit_settings;
			
			$im = $im->Get();
			?><div id="im">
			<h2>Βρες τους φίλους σου στο <?php
			echo $rabbit_settings[ 'applicationame' ];
			?></h2>
			
			</div><?php
		}
	}
?>