<?php    
	class ElementSearchImCredentials extends Element {
        public function Render( tString $im ) {
			global $rabbit_settings;
			
			$im = $im->Get();
			?><div id="im">
			<h2>Βρες τους φίλους σου στο <?php
			echo $rabbit_settings[ 'applicationname' ];
			?></h2>
			
			</div><?php
		}
	}
?>