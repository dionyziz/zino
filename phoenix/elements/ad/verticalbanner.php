<?php
	// Google Ads
	class ElementAdVerticalBanner extends Element {
        public function Render() {
            ?><object data="ads.php" type="text/html" style="width:120px;height:240px;"><?php
            Element( 'ad/plaintext' );
            ?></object><?php
        }
    }
?>
